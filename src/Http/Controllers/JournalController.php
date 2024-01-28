<?php

namespace Transave\ScolaBookstore\Http\Controllers;

use Illuminate\Http\Request;
use Transave\ScolaBookstore\Actions\Journals\CreateJournal;
use Transave\ScolaBookstore\Actions\Journals\DeleteJournal;
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


    public function index()
    {
        return (new SearchJournal(Journal::class, ['user', 'category', 'publisher']))->execute();
    }



    public function store(Request $request)
    {
        return (new CreateJournal($request->all()))->execute();
    }


    public function show($id)
    {
        return (new SearchJournal(Journal::class, ['user', 'category', 'publisher'], $id))->execute();
    }


    public function update(Request $request, $id)
    {
        $inputs = $request->merge(['journal_id' => $id])->all();
        return (new UpdateJournal($inputs))->execute();
    }


    public function destroy($id)
    {
        return (new DeleteJournal(['id' => $id]))->execute();
    }
}
