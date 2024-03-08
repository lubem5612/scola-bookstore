<?php


namespace Transave\ScolaBookstore\Http\Controllers;


use Illuminate\Http\Request;
use Transave\ScolaBookstore\Actions\Author\CreateAuthor;
use Transave\ScolaBookstore\Actions\Author\DeleteAuthor;
use Transave\ScolaBookstore\Actions\Author\SearchAuthor;
use Transave\ScolaBookstore\Actions\Author\UpdateAuthor;
use Transave\ScolaBookstore\Http\Models\Author;

class AuthorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index', 'show']);
    }

    public function index()
    {
        return (new SearchAuthor(Author::class, ['user']))->execute();
    }

    public function store(Request $request)
    {
        return (new CreateAuthor($request->all()))->execute();
    }

    public function show($id)
    {
        return (new SearchAuthor(Author::class, ['user'], $id))->execute();
    }

    public function update(Request $request, $id)
    {
        $data = $request->merge(['author_id' => $id])->all();
        return (new UpdateAuthor($data))->execute();
    }

    public function destroy($id)
    {
        return (new DeleteAuthor(['author_id' => $id]))->execute();
    }
}