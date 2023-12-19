<?php

namespace Transave\ScolaBookstore\Actions\ConferencePaper;

use Illuminate\Support\Arr;
use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\UploadHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\ConferencePaper;

class UpdateConferencePaper
{
    use ValidationHelper, ResponseHelper;

    private array $request;
    private array $validatedInput;
    private $user;
    private $uploader;
    private $conferencePaper;

    public function __construct(array $request)
    {
        $this->request = $request;
        $this->uploader = new UploadHelper();
    }

    public function execute()
    {
        try {
            return $this->validateRequest()
                ->setPaperId()
                ->uploadFileIfExists()
                ->updatePaper();
        }catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }


    private function setPaperId()
    {
        $this->conferencePaper = ConferencePaper::query()->find($this->validatedInput['paper_id']);
        return $this;
    }


    private function uploadFileIfExists()
    {
        if (isset($this->request['file']) && $this->request['file']) {
            $response = $this->uploader->uploadOrReplaceFile($this->request['file'], 'scola-bookstore/Papers', $this->conferencePaper, 'file');
            if ($response['success']) {
                $this->validatedInput['file'] = $response['upload_url'];
            }
        }
        return $this;
    }



    private function updatePaper()
    {
        $this->conferencePaper->fill($this->validatedInput)->save();
        return $this->sendSuccess($this->conferencePaper->refresh(), 'Conference Paper updated');
    }


    private function validateRequest(): self
    {
        $data = $this->validate($this->request, [
            'paper_id' => 'required|exists:conference_papers,id',
            'user_id' => 'required|exists:users,id',
            'title' => 'sometimes|required|string|max:255',
            'category_id' => 'sometimes|required|exists:categories,id',
            'subtitle' => 'sometimes|required|string|max:255',
            'abstract' => 'sometimes|required|string|max:255',
            'primary_author' => 'sometimes|required|string|max:255',
            'other_authors' => 'sometimes|required|json',
            'file' => 'sometimes|required|file|max:10000|mimes:pdf,doc,wps,wpd,docx',
            'conference_title'=> 'sometimes|required|string|max:225',
            'conference_date'=> 'sometimes|required|string|max:255',
            'keywords' => 'sometimes|required|string|max:255|json',
            'references' => 'sometimes|required|string|max:255|json',
            'introduction' => 'sometimes|required|string|max:255',
            'background' => 'sometimes|required|string|max:255',
            'methodology' => 'sometimes|required|string|max:255',
            'location' => 'sometimes|required|string|max:225',
            'pages' => 'sometimes|required|string|max:225',
            'conclusion' => 'sometimes|required|string|max:225',
            'result' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|integer',
            'percentage_share' => 'sometimes|required',
        ]);

        $this->validatedInput = Arr::except($data, ['file']);
        return $this;

    }
}
