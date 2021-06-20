<?php

namespace App\Http\Controllers\StoredResource;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoredResource\CreateRequest;
use App\Models\StoredResource;
use Illuminate\Http\Request;
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
    }

    public function create()
    {
        return view("stored-resource/item");
    }

    public function store(CreateRequest $request)
    {
        $request['user_id'] = Auth::id();
        if (ip2long($request['domain']) === false) {
            $request['ip'] = gethostbyname($request['domain']);
            if ($request['protocol'] === 'https') {
                $certificate = SslCertificate::createForHostName($request['domain']);
                $request['is_active_ssl'] = $certificate->isValid();
                $request['ssl_expired_at'] = $certificate->expirationDate();
            }
        } else {
            $request['ip'] = $request['domain'];
        }

        if ($request['port'] === null) {
            unset($request['port']);
        }
        StoredResource::query()->create($request->all());

        return redirect()->route('get-stored-resources');
    }

    public function udpate($id)
    {

    }

    public function edit(Request $request, $id)
    {
        StoredResource::query()->where(['id' => $id])->update($request->all());
    }

    public function delete($id)
    {
        StoredResource::query()->where(['id' => $id])->delete();
    }
}
