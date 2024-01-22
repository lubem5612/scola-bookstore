<?php

namespace Transave\ScolaBookstore\Http\Controllers;

use Illuminate\Http\Request;
use Transave\ScolaBookstore\Actions\Reports\CreateReport;
use Transave\ScolaBookstore\Actions\Reports\DeleteReport;
use Transave\ScolaBookstore\Actions\Reports\GetReport;
use Transave\ScolaBookstore\Actions\Reports\SearchReport;
use Transave\ScolaBookstore\Actions\Reports\UpdateReport;
use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Http\Models\Report;


class ReportController extends Controller
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
     * Get a listing of Report;
     *
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaBookstore\Helpers\Response
     */
    public function index()
    {
        return (new SearchReport(Report::class, ['user', 'category', 'publisher']))->execute();
    }




    /**
     * create Report;
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaBookstore\Helpers\Response
     */
    public function store(Request $request)
    {
        return (new CreateReport($request->all()))->execute();
    }



    /**
     * Get a specified Report;
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaBookstore\Helpers\Response
     */
    public function show($id)
    {
        return (new GetReport(['id' => $id]))->execute();
    }



    /**
     * Update a specified Report;
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaBookstore\Helpers\Response
     */
    public function update(Request $request, $id)
    {
        $inputs = $request->merge(['report_id' => $id])->all();
        return (new UpdateReport($inputs))->execute();
    }


    /**
     * Delete a specified Report;
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaBookstore\Helpers\Response
     */
    public function destroy($id)
    {
        return (new DeleteReport(['id' => $id]))->execute();
    }
}
