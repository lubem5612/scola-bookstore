<?php

namespace Transave\ScolaBookstore\Actions\ConferencePaper;

use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\UploadHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\ConferencePaper;

class DeleteConferencePaper
{
    use ResponseHelper, ValidationHelper;
    private array $request;
    private array $validatedInput;
    private $uploader;
    private ConferencePaper $conferencePaper;

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
                ->setPaper()
                ->deleteFile()
                ->deletePaper();
        }catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }



    private function deletePaper()
    {
        $this->conferencePaper->delete();
        return $this->sendSuccess(null, 'Conference Paper deleted successfully');
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
    private function setPaper() :self
    {
        $this->conferencePaper = ConferencePaper::query()->find($this->validatedInput['id']);
        return $this;
    }


    /**
     * @return $this
     */
    private function validateRequest() : self
    {
        $data = $this->validate($this->request, [
            'id' => 'required|exists:conference_papers,id'
        ]);
        $this->validatedInput = $data;
        return $this;
    }
}