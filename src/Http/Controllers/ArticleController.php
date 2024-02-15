<?php

namespace Transave\ScolaBookstore\Http\Controllers;

use Illuminate\Http\Request;

use Transave\ScolaBookstore\Actions\Article\CreateArticle;
use Transave\ScolaBookstore\Actions\Article\DeleteArticle;
use Transave\ScolaBookstore\Actions\Article\SearchArticle;
use Transave\ScolaBookstore\Actions\Article\UpdateArticle;
use Transave\ScolaBookstore\Http\Models\Resource;


class ArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index()
    {
        return (new SearchArticle(Resource::class, ['author']))->execute();
    }

    public function store(Request $request)
    {
        return (new CreateArticle($request->all()))->execute();
    }

    public function show($id)
    {
        return (new SearchArticle(Resource::class, ['author'], $id))->execute();
    }

    public function update(Request $request, $id)
    {
        $data = $request->merge(['resource_id' => $id])->all();
        return (new UpdateArticle($data))->execute();
    }

    public function destroy($id)
    {
        return (new DeleteArticle(['resource_id' => $id]))->execute();
    }
}
