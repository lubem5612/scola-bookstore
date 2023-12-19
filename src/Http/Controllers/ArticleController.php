<?php

namespace Transave\ScolaBookstore\Http\Controllers;

use Illuminate\Http\Request;
use Transave\ScolaBookstore\Actions\Articles\CreateArticle;
use Transave\ScolaBookstore\Actions\Articles\DeleteArticle;
use Transave\ScolaBookstore\Actions\Articles\GetArticle;
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


    /**
     * Get a listing of Article;
     *
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaBookstore\Helpers\Response
     */
    public function index()
    {
        return (new SearchArticle(Article::class, ['user', 'category']))->execute();
    }




    /**
     * create Article;
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaBookstore\Helpers\Response
     */
    public function store(Request $request)
    {
        return (new CreateArticle($request->all()))->execute();
    }



    /**
     * Get a specified Article;
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaBookstore\Helpers\Response
     */
    public function show($id)
    {
        return (new GetArticle(['id' => $id]))->execute();
    }



    /**
     * Update a specified Article;
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaBookstore\Helpers\Response
     */
    public function update(Request $request, $id)
    {
        $inputs = $request->merge(['article_id' => $id])->all();
        return (new UpdateArticle($inputs))->execute();
    }


    /**
     * Delete a specified Article;
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaBookstore\Helpers\Response
     */
    public function destroy($id)
    {
        return (new DeleteArticle(['id' => $id]))->execute();
    }
}
