<?php

namespace App\Http\Controllers\Fingerprint;

use Illuminate\Http\Request;
use App\Http\Controllers\AdminBaseController;
use App\Services\Fingerprint\FingerprintService;
use App\Http\Requests\Fingerprint\CreateFingerprintRequest;
use App\Http\Requests\Fingerprint\UpdateFingerprintRequest;
use App\Http\Resources\Fingerprint\FingerprintListResource;
use App\Http\Resources\Fingerprint\SubmitFingerprintResource;

class FingerprintController extends AdminBaseController
{
    public function __construct(FingerprintService $fingerprintService) {

        $this->fingerprintService= $fingerprintService;
        
        $this->title = "BattleHR | Fingerprint";
        $this->path = "fingerprint/index";
    }

    public function getData(Request $request) {
        try {
            $data = $this->fingerprintService->getData($request);

            $result = new FingerprintListResource($data);
            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    
    public function createData(CreateFingerprintRequest $request)
    {
        try {
            $data = $this->fingerprintService->createData($request);

            $result = new SubmitFingerprintResource($data, 'Success Create Fingerprint');
            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function deleteData($id)
    {
        try {
            $data = $this->fingerprintService->deleteData($id);

            $result = new SubmitFingerprintResource($data, 'Success Delete Fingerprint');
            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function updateData($id, UpdateFingerprintRequest $request)
    {
        try {
            $data = $this->fingerprintService->updateData($id, $request);

            $result = new SubmitFingerprintResource($data, 'Success Update Notice Board');
            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }
    
}
