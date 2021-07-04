<?php


namespace App\Services\StoredResource;


use App\Http\Controllers\StoredResource\StoredResourceContentController;
use App\Http\Controllers\StoredResource\StoredResourceHistoryController;
use App\Models\StoredResource;
use App\Models\StoredResourceMonitoringOption;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Throwable;

class StoredResourceCheckingService
{

    public function check($id)
    {
        $storedResource = StoredResource::query()->find($id);
        if ($storedResource) {
            $client = new Client([
                'base_uri' => $storedResource->domain . ':' . $storedResource->port,
            ]);
            $monitoringOption = StoredResourceMonitoringOption::query()->where(['stored_resource_id' => $id])->first();
            $type = $this->handleRequestType($monitoringOption);
            $requestOptions = $this->handleRequestOptions($monitoringOption);
            $startTime = now();

            try {
                $result = $client->request($type, '/', $requestOptions);
                $status = $result->getStatusCode();
                $content = $result->getBody()->getContents();

            } catch (Throwable $throwable) {
                $status = 500;
                $content = $throwable;
            }

            $timing = now()->diffInMilliseconds($startTime);

            $storedResourceContent = (new StoredResourceContentController())->store($id, $content);
            (new StoredResourceHistoryController())->store($id, $storedResourceContent->id, $timing, $status);

            $storedResource->last_status = $status;
            $storedResource->last_request_execution_time = $timing;
            $storedResource->last_checked_at = now();
            $storedResource->save();

            return $storedResource;
        }

        return null;
    }

    private function handleRequestType($monitoringOption)
    {
        return $monitoringOption ? $monitoringOption->request_type : 'GET';
    }

    private function handleRequestOptions($monitoringOption): array
    {
        $headers = $monitoringOption ? json_decode($monitoringOption->request_headers, true) : [];
        $parameters = $monitoringOption ? json_decode($monitoringOption->request_parameters, true) : [];
        $body = $monitoringOption ? json_decode($monitoringOption->request_body, true) : [];

        return [
            RequestOptions::HEADERS => $headers,
            RequestOptions::QUERY => $parameters,
            RequestOptions::FORM_PARAMS => $body
        ];
    }
}
