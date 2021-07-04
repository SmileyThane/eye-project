<?php

namespace App\Http\Controllers\StoredResource;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoredResource\CreateRequest;
use App\Http\Requests\StoredResource\UpdateRequest;
use App\Models\StoredResource;
use App\Models\StoredResourceMonitoringOption;
use App\Services\StoredResource\StoredResourceCheckingService;
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
        (new StoredResourceCheckingService())->processChecking($id);

        return redirect()->route('get-stored-resources');
    }
}
