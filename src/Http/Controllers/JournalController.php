<?php

namespace Transave\ScolaBookstore\Http\Controllers;

use Illuminate\Http\Request;
use Transave\ScolaBookstore\Actions\Journals\CreateJournal;
use Transave\ScolaBookstore\Actions\Journals\DeleteJournal;
use Transave\ScolaBookstore\Actions\Journals\GetJournal;
use Transave\ScolaBookstore\Actions\Journals\SearchJournal;
use Transave\ScolaBookstore\Actions\Journals\UpdateJournal;
use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Http\Models\Journal;


class JournalController extends Controller
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
     * Get a listing of Journal;
     *
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaBookstore\Helpers\Response
     */
    public function index()
    {
        return (new SearchJournal(Journal::class, ['user', 'category', 'publisher']))->execute();
    }




    /**
     * create Journal;
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaBookstore\Helpers\Response
     */
    public function store(Request $request)
    {
        return (new CreateJournal($request->all()))->execute();
    }



    /**
     * Get a specified Journal;
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaBookstore\Helpers\Response
     */
    public function show($id)
    {
        return (new GetJournal(['id' => $id]))->execute();
    }



    /**
     * Update a specified Journal;
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaBookstore\Helpers\Response
     */
    public function update(Request $request, $id)
    {
        $inputs = $request->merge(['journal_id' => $id])->all();
        return (new UpdateJournal($inputs))->execute();
    }


    /**
     * Delete a specified Journal;
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaBookstore\Helpers\Response
     */
    public function destroy($id)
    {
        return (new DeleteJournal(['id' => $id]))->execute();
    }
}
