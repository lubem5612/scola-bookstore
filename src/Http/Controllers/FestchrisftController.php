<?php

namespace Transave\ScolaBookstore\Http\Controllers;

use Illuminate\Http\Request;
use Transave\ScolaBookstore\Actions\Festchrisfts\CreateFestchrisft;
use Transave\ScolaBookstore\Actions\Festchrisfts\DeleteFestchrisft;
use Transave\ScolaBookstore\Actions\Festchrisfts\GetFestchrisft;
use Transave\ScolaBookstore\Actions\Festchrisfts\SearchFestchrisft;
use Transave\ScolaBookstore\Actions\Festchrisfts\UpdateFestchrisft;
use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Http\Models\Festchrisft;


class FestchrisftController extends Controller
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
     * Get a listing of Festchrisft;
     *
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaBookstore\Helpers\Response
     */
    public function index()
    {
        return (new SearchFestchrisft(Festchrisft::class, ['user', 'category', 'publisher']))->execute();
    }



    /**
     * create Festchrisft;
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaBookstore\Helpers\Response
     */
    public function store(Request $request)
    {
        return (new CreateFestchrisft($request->all()))->execute();
    }



    /**
     * Get a specified Festchrisft;
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaBookstore\Helpers\Response
     */
    public function show($id)
    {
        return (new GetFestchrisft(['id' => $id]))->execute();
    }



    /**
     * Update a specified Festchrisft;
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaBookstore\Helpers\Response
     */
    public function update(Request $request, $id)
    {
        $inputs = $request->merge(['Festchrisft_id' => $id])->all();
        return (new UpdateFestchrisft($inputs))->execute();
    }


    /**
     * Delete a specified Festchrisft;
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaBookstore\Helpers\Response
     */
    public function destroy($id)
    {
        return (new DeleteFestchrisft(['id' => $id]))->execute();
    }
}
