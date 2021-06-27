<?php

namespace App\Http\Controllers\StoredResource;

use App\Http\Controllers\Controller;
use App\Models\StoredResourceContent;
use Illuminate\Http\Request;

class StoredResourceContentController extends Controller
{
    public function store($id, $content): StoredResourceContent
    {
        $storedresourceContent = new StoredResourceContent();
        $storedresourceContent->stored_resource_id = $id;
        $storedresourceContent->data = $content;
        $storedresourceContent->save();

        return $storedresourceContent;
    }
}
