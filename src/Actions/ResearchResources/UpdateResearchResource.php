<?php

namespace Transave\ScolaBookstore\Actions\ResearchResources;

use Illuminate\Support\Arr;
use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\UploadHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\ResourceCategory;

class UpdateResearchResource
{
    use ValidationHelper, ResponseHelper;

    private array $request;
    private array $validatedInput;
    private $user;
    private $uploader;


    public function __construct(array $request)
    {
        $this->request = $request;
        $this->uploader = new UploadHelper();
    }

    public function execute()
    {
        try {
            return $this->validateRequest()
                ->setResearchResourceId()
                ->updateAbstractIfExists()
                ->updateContentIfExists()
                ->uploadFileIfExists()
                ->uploadCoverIfExists()
                ->updateResearchResource();
        }catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }


    private function setResearchResourceId()
    {
        $this->researchResource = ResourceCategory::query()->find($this->validatedInput['researchResource_id']);
        return $this;
    }


        private function updateAbstractIfExists()
    {
        if (isset($this->request['abstract'])) {
            $this->validatedInput['abstract'] = $this->request['abstract'];
        }
        return $this;
    }



    private function updateContentIfExists()
    {
        if (isset($this->request['content'])) {
            $this->validatedInput['content'] = $this->request['content'];
        }
        return $this;
    }



    private function uploadCoverIfExists(): self
    {
        if (request()->hasFile('cover_image')) {
            $file = request()->file('cover_image');

            $response = $this->uploader->uploadFile($file, 'research_resources', 'local');

            if ($response['success']) {
                $this->validatedInput['cover_image'] = $response['upload_url'];
            }
        }
        return $this;
    }



        private function uploadFileIfExists(): self
    {
        if (request()->hasFile('file_path')) {
            $file = request()->file('file_path');

            $response = $this->uploader->uploadFile($file, 'research_resources', 'local');

            if ($response['success']) {
                $this->validatedInput['file_path'] = $response['upload_url'];
            }
        }
         return $this;
    }



    private function updateResearchResource()
    {
        $this->researchResource->fill($this->validatedInput)->save();
        return $this->sendSuccess($this->researchResource->refresh(), 'Research Resources updated');
    }


    private function validateRequest(): self
    {
        $data = $this->validate($this->request, [
            'researchResource_id' => 'required|exists:research_resources,id',
            'user_id' => 'required|exists:users,id|max:255',
            'category_id' => 'sometimes|required|exists:categories,id|max:255',
            'publisher_id' => 'sometimes|required|exists:publishers,id|max:255',
            'publisher'=> 'sometimes|required|string|max:255',
            'publication_date' => 'sometimes|required|string|max:255',
            'publication_year' => 'sometimes|required|string|max:255',
            'source' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string|max:255',
            'abstract' => 'sometimes|required|string|max:255',
            'resource_url' => 'sometimes|required|string|max:255',
            'primary_author'=> 'required|string|max:255',
            'contributors'=> 'sometimes|required|json|max:255',
            'title' => 'required|string|max:255',
            'subtitle' => 'sometimes|required|string|max:255', 
            'overview'=> 'sometimes|required|string|max:255',
            'resource_type'=> 'sometimes|required|string|max:255', // E.g., Dataset, Software, Educational Material
            'keywords'=> 'sometimes|required|json|max:255',
            'file_path' => 'sometimes|required|file|max:10000|mimes:pdf,doc,wps,wpd,docx',
            'cover_image' => 'sometimes|required|image|max:5000|mimes:png,jpeg,jpg,gif,webp',
            'faculty' => 'string|max:255',
            'department' => 'string|max:255',
            'price' => 'sometimes|required|integer',
            'percentage_share' => 'sometimes|required|max:255',
        ]);

        $this->validatedInput = Arr::except($data, ['file_path', 'cover_image', 'abstract', 'content']);
        return $this;

    }
}
