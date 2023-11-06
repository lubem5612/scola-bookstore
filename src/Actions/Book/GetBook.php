<?php

namespace Transave\ScolaBookstore\Actions\Book;

use Transave\ScolaBookstore\Events\BookViewed;
use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\Book;

class GetBook
{
    use ValidationHelper, ResponseHelper;

    private array $request;
    private array $validatedInput;
    private Book $book;

    public function __construct(array $request)
    {
        $this->request = $request;
    }


    public function execute()
    {
        try {
            return $this
                ->validateRequest()
                ->setBook()
                ->sendSuccess($this->book, 'book fetched successfully');
        }catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }
    private function setBook()
    {
        $this->book = Book::query()
            ->with(['user', 'category', 'publisher'])
            ->find($this->request['id']);
//        $user = auth()->user();
//        event(new BookViewed($user, $this->book));

        return $this;
    }

    private function validateRequest(): self
    {
        $id = $this->validate($this->request, [
            'id' => 'required|exists:books,id'
        ]);
        $this->validatedInput = $id;
        return $this;
    }
}