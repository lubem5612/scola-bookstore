<?php


namespace Transave\ScolaBookstore\Actions\Author;


use Illuminate\Support\Arr;
use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\Author;

class CreateAuthor
{
    use ValidationHelper, ResponseHelper;
    private $request, $validatedData;

    public function __construct(array $request)
    {
        $this->request = $request;
    }

    public function execute()
    {
        try {
            $this->validateRequest();
            $this->setBankInformation();
            return $this->createAuthor();
        }catch (\Exception $exception) {
            return $this->sendServerError($exception);
        }
    }

    private function createAuthor()
    {
        $author = Author::query()->create($this->validatedData);
        return $this->sendSuccess($author->load('user'), 'author created successfully');
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
            'user_id' => 'required|exists:users,id',
            'department_id' => 'required|exists:departments,id',
            'faculty_id' => 'required|exists:faculties,id',
            'specialization' => 'required|string|max:700',
            'bio' => 'nullable',
            'bank_info' => 'nullable|array',
            'bank_info.*' => 'nullable|string',
        ]);
        $this->validatedData = Arr::except($validator, ['bank_info']);
    }
}