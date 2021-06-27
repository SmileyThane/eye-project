<?php

namespace App\Http\Controllers\StoredResource;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoredResource\CreateRequest;
use App\Http\Requests\StoredResource\UpdateRequest;
use App\Models\StoredResource;
use App\Models\StoredResourceMonitoringOption;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Facades\Auth;
use Spatie\SslCertificate\SslCertificate;

class StoredResourceController extends Controller
{
    public function index()
    {
        $storedResources = StoredResource::query()->where(['user_id' => Auth::id()])->get();

        return view("stored-resource/index", ['storedResources' => $storedResources]);
    }

    public function find($id)
    {
        $storedResource = StoredResource::query()->find($id);

        return view("stored-resource/item", ['storedResource' => $storedResource]);
    }

    public function create()
    {
        return view("stored-resource/item");
    }

    public function store(CreateRequest $request)
    {
        $storedResource = StoredResource::create($this->prepareStoredResourceArray($request->all()));
        $this->updateSslInfo($storedResource);

        return redirect()->route('get-stored-resources');
    }

    private function prepareStoredResourceArray($data)
    {
        $data['user_id'] = Auth::id();
        $data['ip'] = ip2long($data['domain']) === false ? gethostbyname($data['domain']) : $data['domain'];
        if ($data['port'] === null) {
            unset($data['port']);
        }

        return $data;
    }

    private function updateSslInfo(StoredResource $storedResource): StoredResource
    {
        if ($storedResource->protocol === StoredResource::DEFAULT_PROTOCOL &&
            ip2long($storedResource->domain) === false
        ) {
            $certificate = SslCertificate::createForHostName($storedResource->domain);
            $storedResource->is_active_ssl = $certificate->isValid();
            $storedResource->ssl_expired_at = $certificate->expirationDate();
            $storedResource->save();
        }

        return $storedResource;
    }

    public function edit(UpdateRequest $request, $id)
    {
        $storedResource = StoredResource::query()->find($id);
        $storedResource->update($this->prepareStoredResourceArray($request->all()));
        $this->updateSslInfo($storedResource);

        return redirect()->route('get-stored-resources');
    }

    public function delete($id)
    {
        StoredResource::query()->where(['id' => $id])->delete();
    }

    public function check($id)
    {
        $storedResource = StoredResource::query()->find($id);
        if ($storedResource) {
            $client = new Client([
                'base_uri' => $storedResource->domain . ':' . $storedResource->port,
            ]);
            $type = 'GET';
            $headers = [];
            $parameters = [];
            $body = [];
            $monitoringOptions = StoredResourceMonitoringOption::query()->where(['stored_resource_id' => $id])->first();
            if ($monitoringOptions) {
                $type = $monitoringOptions->request_type;
                $headers = json_decode($monitoringOptions->request_headers, true);
                $parameters = json_decode($monitoringOptions->request_parameters, true);
                $body = json_decode($monitoringOptions->request_body, true);
            }

            $startTime = now();

            try {
                $result = $client->request($type, '/', [
                    RequestOptions::HEADERS => $headers,
                    RequestOptions::QUERY => $parameters,
                    RequestOptions::FORM_PARAMS => $body
                ]);

                $timing = now()->diffInMilliseconds($startTime);
                $status = $result->getStatusCode();
                $content = $result->getBody()->getContents();

            } catch (\Throwable $throwable) {
                $timing = now()->diff($startTime)->format("");
                $status = 500;
                $content = $throwable;
            }

            $storedResourceContent = (new StoredResourceContentController())->store($id, $content);
            (new StoredResourceHistoryController())->store($id, $storedResourceContent->id, $timing, $status);

            $storedResource->last_status = $status;
            $storedResource->last_request_execution_time = $timing;
            $storedResource->last_checked_at = now();
            $storedResource->save();

            return redirect()->route('get-stored-resources');
        }

    }
}
