<?php

namespace Transave\ScolaBookstore\Http\Controllers;

use Illuminate\Http\Request;
use Transave\ScolaBookstore\Actions\ConferencePaper\CreateConferencePaper;
use Transave\ScolaBookstore\Actions\ConferencePaper\DeleteConferencePaper;
use Transave\ScolaBookstore\Actions\ConferencePaper\GetConferencePaper;
use Transave\ScolaBookstore\Actions\ConferencePaper\SearchConferencePaper;
use Transave\ScolaBookstore\Actions\ConferencePaper\UpdateConferencePaper;
use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Http\Models\ConferencePaper;


class ConferencePaperController extends Controller
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
     * Get a listing of Conference Paper;
     *
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaBookstore\Helpers\Response
     */
    public function index()
    {
        return (new SearchConferencePaper(ConferencePaper::class, ['user', 'category']))->execute();
    }




    /**
     * create a Conference Paper;
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaBookstore\Helpers\Response
     */
    public function store(Request $request)
    {
        return (new CreateConferencePaper($request->all()))->execute();
    }



    /**
     * Get a specified Conference Paper;
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaBookstore\Helpers\Response
     */
    public function show($id)
    {
        return (new GetConferencePaper(['id' => $id]))->execute();
    }



    /**
     * Update a specified Conference Paper;
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaBookstore\Helpers\Response
     */
    public function update(Request $request, $id)
    {
        $inputs = $request->merge(['paper_id' => $id])->all();
        return (new UpdateConferencePaper($inputs))->execute();
    }


    /**
     * Delete a specified Conference Paper;
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaBookstore\Helpers\Response
     */
    public function destroy($id)
    {
        return (new DeleteConferencePaper(['id' => $id]))->execute();
    }
}
