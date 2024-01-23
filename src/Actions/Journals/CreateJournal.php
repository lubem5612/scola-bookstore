<?php

namespace Transave\ScolaBookstore\Actions\Journals;

use Illuminate\Support\Arr;
use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\UploadHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\Journal;
use Transave\ScolaBookstore\Http\Models\User;
use Illuminate\Support\Facades\Config;



class CreateJournal
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
                ->setUser()
                ->uploadFile()
                ->createContent()
                ->createAbstract()
                ->setPercentageShare()
                ->createJournal();
        } catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }


  
  private function validateRequest(): self
    {
    $this->validatedInput = $this->validate($this->request, [
            'user_id' => 'required|exists:users,id|max:255',
            'category_id' => 'required|exists:categories,id|max:255',
            'publisher_id' => 'nullable|exists:publishers,id|max:255',
            'title' => 'required|string|max:255',
            'volume' => 'required|string|max:255',
            'price' => 'required|integer',
            'subtitle' => 'string|max:255',
            'publisher'=> 'string|max:255',
            'publication_date' => 'string|max:255',
            'publication_year' => 'string|max:255',
            'abstract' => 'string|max:255',
            'content' => 'string|max:255', //the material
            'page_start' => 'string|max:255',
            'page_end' => 'string|max:255',
            'editors'=> 'json|max:255',
            'website'=> 'string|max:255',
            'editorial'=> 'string|max:255',
            'editorial_board_members'=> 'json|max:255',
            'file_path' => 'file|max:10000|mimes:pdf,doc,wps,wpd,docx',
            'conclusion' => 'string|max:255',
            'percentage_share' => 'numeric',
            
        ]);

        Arr::forget($this->validatedInput, ['file_path', 'content', 'abstract']);
        return $this;

    }



       private function uploadFile(): self
    {
        if (array_key_exists('file_path', $this->request) && $this->request['file_path']->isValid()) {
            $file = $this->request['file_path'];
            $response = $this->uploader->uploadFile($file, 'journals', 'local');

            if ($response['success']) {
                $this->validatedInput['file_path'] = $response['upload_url'];
            }
        }

        return $this;
    }



    private function createContent(): self
    {
        if (array_key_exists('content', $this->request)) {
            $this->validatedInput['content'] = $this->request['content'];
        }

        return $this;
    }



    private function createAbstract(): self
    {
        if (array_key_exists('abstract', $this->request)) {
            $this->validatedInput['abstract'] = $this->request['abstract'];
        }

        return $this;
    }



    private function setUser(): self
    {
        $this->user = Config::get('scola-bookstore.auth_model')::query()->find($this->validatedInput['user_id']);
        return $this;
    }



    private function createJournal()
    {
        $journal = Journal::query()->create($this->validatedInput);
        return $this->sendSuccess($journal->load('user', 'category', 'publisher'), 'Journal created successfully');
    }

 
    private function setPercentageShare(): self
    {
        if (!array_key_exists('percentage_share', $this->request)) {
            $this->request['percentage_share'] = Config::get('scola-bookstore.percentage_share');
        }

        return $this;
    }
    
}

 