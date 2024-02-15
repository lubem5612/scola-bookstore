<?php


namespace Transave\ScolaBookstore\Actions\Author;


use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\Author;

class DeleteAuthor
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
            return $this->deleteAuthor();
        }catch (\Exception $exception){
            return $this->sendServerError($exception);
        }
    }

    private function deleteAuthor()
    {
        Author::destroy($this->validatedData['author_id']);
        return $this->sendSuccess(null, 'author deleted successfully');
    }

    private function validateRequest()
    {
        $this->validatedData = $this->validate($this->request, [
            'author_id' => 'required|exists:authors,id'
        ]);
    }
}