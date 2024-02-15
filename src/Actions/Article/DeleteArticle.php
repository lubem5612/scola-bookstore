<?php


namespace Transave\ScolaBookstore\Actions\Article;


use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\UploadHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\Resource;

class DeleteArticle
{
    use ValidationHelper, ResponseHelper;
    private $request, $validatedData, $uploader;
    private Resource $resource;

    public function __construct(array $request)
    {
        $this->request = $request;
        $this->uploader = new UploadHelper();
    }

    public function execute()
    {
        try {
            $this->validateRequest();
            $this->getResource();
            $this->deleteCoverImage();
            $this->deleteUploaded();
            return $this->deleteResource();
        }catch (\Exception $exception) {
            return $this->sendServerError($exception);
        }
    }

    private function getResource()
    {
        $this->resource = Resource::query()->find($this->validatedData['resource_id']);
    }

    private function deleteCoverImage()
    {
        if ($this->resource->cover_imahe) {
            $this->uploader->deleteFile($this->resource->cover_imahe);
        }
    }

    private function deleteUploaded()
    {
        if ($this->resource->file_path) {
            $this->uploader->deleteFile($this->resource->file_path);
        }
    }

    private function deleteResource()
    {
        $this->resource->delete();
        return $this->sendSuccess(null, 'resource deleted successfully');
    }

    private function validateRequest()
    {
        $this->validatedData = $this->validate($this->request, [
            'resource_id' => 'required|exists:resources,id'
        ]);
    }
}