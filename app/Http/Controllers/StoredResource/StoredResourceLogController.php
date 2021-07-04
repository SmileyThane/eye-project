<?php

namespace App\Http\Controllers\StoredResource;

use App\Http\Controllers\Controller;
use App\Models\StoredResourceStatusHistory;

class StoredResourceLogController extends Controller
{
    public function store($id, $contentId, $executionTime, $status): StoredResourceStatusHistory
    {
        $storedResourceStatusHistory = new StoredResourceStatusHistory();
        $storedResourceStatusHistory->stored_resource_id = $id;
        $storedResourceStatusHistory->stored_resource_content_id = $contentId;
        $storedResourceStatusHistory->request_execution_time = $executionTime;
        $storedResourceStatusHistory->status = $status;
        $storedResourceStatusHistory->save();

        return $storedResourceStatusHistory;
    }
}
