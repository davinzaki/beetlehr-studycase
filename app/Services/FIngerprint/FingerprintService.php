<?php

namespace App\Services\Fingerprint;

use App\Models\Fingerprint;

class FingerprintService 
{
    public function getData($request)
    {
        $query = Fingerprint::paginate(10);

        return $query;
    }  

    public function deleteData($id)
    {
        $fingerprint = Fingerprint::findOrFail($id);
        $fingerprint->delete();

        return $fingerprint;
    }

    public function createData($request)
    {
        $inputs = $request->only(['name', 'serial_number', 'is_active']);

        if ($request->name === 'all' || $request->name === 'null') {
            $inputs['name'] = null;
        } else {
            $inputs['name'] = $request->name;
        }
        
        // if($request->type === 'document') {
        //     // Upload File
        //     $fileService = new FileService();
        //     $file = $fileService->uploadFile($request->file('file'));
        //     $inputs['file'] = $file->id;
        // }else{
        //     $inputs['description'] = $request->description;
        // }

        $fingerprint = Fingerprint::create($inputs);
        return $fingerprint;
    }

    public function updateData($id, $request)
    {
        $inputs = $request->only(['name', 'serial_number', 'is_active']);

        if ($request->name === 'all' || $request->name === 'null') {
            $inputs['name'] = null;
        } else {
            $inputs['name'] = $request->name;
        }
        
        $fingerprint = Fingerprint::findOrFail($id);
        $fingerprint->update($inputs);
        
        return $fingerprint;
    }
}
