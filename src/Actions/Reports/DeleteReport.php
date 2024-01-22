<?php

namespace Transave\ScolaBookstore\Actions\Reports;

use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\UploadHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\Report;

class DeleteReport
{
    use ResponseHelper, ValidationHelper;
    private array $request;
    private array $validatedInput;
    private $uploader;
    private Report $report;

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
                ->setReport()
                ->deleteFile()
                ->deleteCover()
                ->deleteReport();
        }catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }



    private function deleteReport()
    {
        $this->report->delete();
        return $this->sendSuccess(null, 'Report deleted successfully');
    }


    /**
     * @return $this
     */
    private function deleteFile() : self
    {
        if (request()->hasFile('file_path')) {
            $file = request()->file('file_path');
            $this->uploader->DeleteFile($file, 'local');
        }
        return $this;
    }


       /**
     * @return $this
     */
    private function deleteCover() : self
    {
        if (request()->hasFile('cover_image')) {
            $file = request()->file('cover_image');
            $this->uploader->DeleteFile($file, 'local');
        }
        return $this;
    }



    /**
     * @return $this
     */
    private function setReport() :self
    {
        $this->report = Report::query()->find($this->validatedInput['id']);
        return $this;
    }


    /**
     * @return $this
     */
    private function validateRequest() : self
    {
        $data = $this->validate($this->request, [
            'id' => 'required|exists:reports,id'
        ]);
        $this->validatedInput = $data;
        return $this;
    }
}