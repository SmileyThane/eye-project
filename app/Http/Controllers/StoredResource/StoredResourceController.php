<?php

namespace App\Http\Controllers\StoredResource;

use App\Http\Controllers\Controller;
use App\Models\StoredResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoredResourceController extends Controller
{
    public function index()
    {
        $storedResources = StoredResource::query()->where(['user_id' => Auth::id()])->get();
    }

    public function find($id)
    {
        $storedResource = StoredResource::query()->find($id);
    }

    public function create()
    {

    }

    public function store(Request $request)
    {
        StoredResource::query()->create($request->all());
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
