<?php

namespace Transave\ScolaBookstore\Http\Controllers;

use Illuminate\Http\Request;
use Transave\ScolaBookstore\Actions\Book\CreateBook;
use Transave\ScolaBookstore\Actions\Book\DeleteBook;
use Transave\ScolaBookstore\Actions\Book\GetBook;
use Transave\ScolaBookstore\Actions\Book\SearchBook;
use Transave\ScolaBookstore\Actions\Book\UpdateBook;
use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Http\Models\Book;


class BookController extends Controller
{
    use ResponseHelper;


    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }



    public function index()
    {
        return (new SearchBook(Book::class, ['user', 'category', 'publisher']))->execute();
    }


    public function store(Request $request)
    {
        return (new CreateBook($request->all()))->execute();
    }


    public function show($id)
    {
        return (new SearchBook(Book::class, ['user', 'category', 'publisher'], $id))->execute();
    }



    public function update(Request $request, $id)
    {
        $inputs = $request->merge(['book_id' => $id])->all();
        return (new UpdateBook($inputs))->execute();
    }


    public function destroy($id)
    {
        return (new DeleteBook(['id' => $id]))->execute();
    }
}
