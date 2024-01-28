<?php

namespace Transave\ScolaBookstore\Http\Controllers;

use Illuminate\Http\Request;
use Transave\ScolaBookstore\Actions\Articles\CreateArticle;
use Transave\ScolaBookstore\Actions\Articles\DeleteArticle;
use Transave\ScolaBookstore\Actions\Articles\SearchArticle;
use Transave\ScolaBookstore\Actions\Articles\UpdateArticle;
use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Http\Models\Article;


class ArticleController extends Controller
{
    use ResponseHelper;



    /**
     * AuthController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }



    public function index()
    {
        return (new SearchArticle(Article::class, ['user', 'category', 'publisher']))->execute();
    }


    public function store(Request $request)
    {
        return (new CreateArticle($request->all()))->execute();
    }


    public function show($id)
    {
        return (new SearchArticle(Article::class, ['user', 'category', 'publisher'], $id))->execute();
    }


    public function update(Request $request, $id)
    {
        $inputs = $request->merge(['article_id' => $id])->all();
        return (new UpdateArticle($inputs))->execute();
    }


    public function destroy($id)
    {
        return (new DeleteArticle(['id' => $id]))->execute();
    }
}
