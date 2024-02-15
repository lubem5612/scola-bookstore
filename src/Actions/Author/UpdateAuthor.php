<?php


namespace Transave\ScolaBookstore\Actions\Author;


use Illuminate\Support\Arr;
use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\Author;

class UpdateAuthor
{
    use ValidationHelper, ResponseHelper;
    private ?Author $author;
    private $request, $validatedData;

    public function __construct(array $request)
    {
        $this->request = $request;
    }

    public function execute()
    {
        try {
            $this->validateRequest();
            $this->getAuthor();
            $this->setBankInformation();
            return $this->updateAuthor();
        }catch (\Exception $exception) {
            return $this->sendServerError($exception);
        }
    }

    private function getAuthor()
    {
        $this->author = Author::query()->find($this->validatedData['author_id']);
    }

    private function updateAuthor()
    {
        $this->author->fill($this->validatedData)->save();
        return $this->sendSuccess($this->author->load('user')->refresh(), 'author updated successfully');
    }

    private function setBankInformation()
    {
        if (Arr::exists($this->request, 'bank_info') && $this->request['bank_info'])
        {
            $validator = $this->validate($this->request['bank_info'], [
                'bank_code' => 'required',
                'account_no' => 'required|string',
                'account_name' => 'required|string'
            ]);
            $this->validatedData['bank_info'] = json_encode($this->request['bank_info']);
        }
    }

    private function validateRequest()
    {
        $validator = $this->validate($this->request, [
            'author_id' => 'required|exists:authors,id',
            'department_id' => 'nullable|exists:departments,id',
            'faculty_id' => 'nullable|exists:faculties,id',
            'specialization' => 'nullable|string|max:700',
            'bio' => 'nullable',
            'bank_info' => 'nullable|array',
            'bank_info.*' => 'nullable|string',
        ]);
        $this->validatedData = Arr::except($validator, ['bank_info']);
    }
}