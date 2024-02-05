<?php

namespace Transave\ScolaBookstore\Actions\ResearchResources;

use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\UploadHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\ResourceCategory;

class DeleteResearchResource
{
    use ResponseHelper, ValidationHelper;
    private array $request;
    private array $validatedInput;
    private $uploader;
    private ResourceCategory $researchResource;

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
                ->setResearchResource()
                ->deleteFile()
                ->deleteCover()
                ->deleteResearchResource();
        }catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }



    private function deleteResearchResource()
    {
        $this->researchResource->delete();
        return $this->sendSuccess(null, 'Research Resource deleted successfully');
    }


    /**
     * @return $this
     */
    private function deleteFile() : self
    {
        if (request()->hasFile('file_path')) {
            $file = request()->file('file_path');
            $this->uploader->deleteFile($file, 'local');
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
            $this->uploader->deleteFile($file, 'local');
        }
        return $this;
    }



    /**
     * @return $this
     */
    private function setResearchResource() :self
    {
        $this->researchResource = ResourceCategory::query()->find($this->validatedInput['id']);
        return $this;
    }


    /**
     * @return $this
     */
    private function validateRequest() : self
    {
        $data = $this->validate($this->request, [
            'id' => 'required|exists:research_resources,id'
        ]);
        $this->validatedInput = $data;
        return $this;
    }
}