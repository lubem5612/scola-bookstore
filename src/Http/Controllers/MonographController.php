<?php

namespace Transave\ScolaBookstore\Http\Controllers;

use Illuminate\Http\Request;
use Transave\ScolaBookstore\Actions\Monographs\CreateMonograph;
use Transave\ScolaBookstore\Actions\Monographs\DeleteMonograph;
use Transave\ScolaBookstore\Actions\Monographs\GetMonograph;
use Transave\ScolaBookstore\Actions\Monographs\SearchMonograph;
use Transave\ScolaBookstore\Actions\Monographs\UpdateMonograph;
use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Http\Models\Monograph;


class MonographController extends Controller
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
     * Get a listing of Monograph;
     *
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaBookstore\Helpers\Response
     */
    public function index()
    {
        return (new SearchMonograph(Monograph::class, ['user', 'category', 'publisher']))->execute();
    }




    /**
     * create Monograph;
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaBookstore\Helpers\Response
     */
    public function store(Request $request)
    {
        return (new CreateMonograph($request->all()))->execute();
    }



    /**
     * Get a specified Monograph;
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaBookstore\Helpers\Response
     */
    public function show($id)
    {
        return (new GetMonograph(['id' => $id]))->execute();
    }



    /**
     * Update a specified Monograph;
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaBookstore\Helpers\Response
     */
    public function update(Request $request, $id)
    {
        $inputs = $request->merge(['monograph_id' => $id])->all();
        return (new UpdateMonograph($inputs))->execute();
    }


    /**
     * Delete a specified Monograph;
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaBookstore\Helpers\Response
     */
    public function destroy($id)
    {
        return (new DeleteMonograph(['id' => $id]))->execute();
    }
}
