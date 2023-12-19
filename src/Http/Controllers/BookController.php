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



    /**
     * AuthController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }


    /**
     * Get a listing of Books
     *
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaBookstore\Helpers\Response
     */
    public function index()
    {
        return (new SearchBook(Book::class, ['user', 'category', 'publisher']))->execute();
    }




    /**
     * create a book
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaBookstore\Helpers\Response
     */
    public function store(Request $request)
    {
        return (new CreateBook($request->all()))->execute();
    }



    /**
     * Get a specified book
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaBookstore\Helpers\Response
     */
    public function show($id)
    {
        return (new GetBook(['id' => $id]))->execute();
    }



    /**
     * Update a specified book
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaBookstore\Helpers\Response
     */
    public function update(Request $request, $id)
    {
        $inputs = $request->merge(['book_id' => $id])->all();
        return (new UpdateBook($inputs))->execute();
    }


    /**
     * Delete a specified Book
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaBookstore\Helpers\Response
     */
    public function destroy($id)
    {
        return (new DeleteBook(['id' => $id]))->execute();
    }
}
