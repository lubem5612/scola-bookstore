<?php

namespace Transave\ScolaBookstore\Actions\Monographs;

use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\UploadHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\Monograph;

class DeleteMonograph
{
    use ResponseHelper, ValidationHelper;
    private array $request;
    private array $validatedInput;
    private $uploader;
    private Monograph $monograph;

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
                ->setMonograph()
                ->deleteFile()
                ->deleteCover()
                ->deleteMonograph();
        }catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }



    private function deleteMonograph()
    {
        $this->monograph->delete();
        return $this->sendSuccess(null, 'Monograph deleted successfully');
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
    private function setMonograph() :self
    {
        $this->monograph = Monograph::query()->find($this->validatedInput['id']);
        return $this;
    }


    /**
     * @return $this
     */
    private function validateRequest() : self
    {
        $data = $this->validate($this->request, [
            'id' => 'required|exists:monographs,id'
        ]);
        $this->validatedInput = $data;
        return $this;
    }
}