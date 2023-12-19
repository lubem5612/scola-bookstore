<?php

namespace Transave\ScolaBookstore\Actions\Festchrisfts;

use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\UploadHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\Festchrisft;

class DeleteFestchrisft
{
    use ResponseHelper, ValidationHelper;
    private array $request;
    private array $validatedInput;
    private $uploader;
    private Festchrisft $festchrisft;

    public function __construct(array $request)
    {
        $this->request = $request;
        $this->uploader = new UploadHelper();
    }

    /**
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaBookstore\Helpers\Response
     */
    public function execute()
    {
        try {
            return $this
                ->validateRequest()
                ->setFestchrisft()
                ->deleteCover()
                ->deleteFile()
                ->deleteFestchrisft();
        }catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }



    private function deleteFestchrisft()
    {
        $this->journal->delete();
        return $this->sendSuccess(null, 'festchrisft deleted successfully');
    }


    /**
     * @return $this
     */
    private function deleteFile() : self
    {
        if (request()->hasFile('file')) {
            $file = request()->file('file');
            $this->uploader->DeleteFile($file, 'local');
        }
        return $this;
    }


    /**
     * @return $this
     */
    private function deleteCover() : self
    {
        if (request()->hasFile('cover')) {
            $file = request()->file('cover');
            $this->uploader->DeleteFile($file, 'local');
        }
        return $this;
    }


    /**
     * @return $this
     */
    private function setFestchrisft() :self
    {
        $this->festchrisft = Festchrisft::query()->find($this->validatedInput['id']);
        return $this;
    }


    /**
     * @return $this
     */
    private function validateRequest() : self
    {
        $data = $this->validate($this->request, [
            'id' => 'required|exists:festchrisfts,id'
        ]);
        $this->validatedInput = $data;
        return $this;
    }
}