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


    public function index()
    {
        return (new SearchReport(Report::class, ['user', 'category', 'publisher']))->execute();
    }


    public function store(Request $request)
    {
        return (new CreateReport($request->all()))->execute();
    }


    public function show($id)
    {
        return (new SearchReport(Report::class, ['user', 'category', 'publisher'], $id))->execute();
    }



    public function update(Request $request, $id)
    {
        $inputs = $request->merge(['report_id' => $id])->all();
        return (new UpdateReport($inputs))->execute();
    }



    public function destroy($id)
    {
        return (new DeleteReport(['id' => $id]))->execute();
    }
}
