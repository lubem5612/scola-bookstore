<?php

namespace Transave\ScolaBookstore\Http\Controllers;

use Illuminate\Http\Request;
use Transave\ScolaBookstore\Actions\ConferencePaper\CreateConferencePaper;
use Transave\ScolaBookstore\Actions\ConferencePaper\DeleteConferencePaper;
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


    public function index()
    {
        return (new SearchConferencePaper(ConferencePaper::class, ['user', 'category']))->execute();
    }



    public function store(Request $request)
    {
        return (new CreateConferencePaper($request->all()))->execute();
    }


    public function show($id)
    {
       return (new SearchConferencePaper(ConferencePaper::class, ['user', 'category'], $id))->execute();
    }


    public function update(Request $request, $id)
    {
        $inputs = $request->merge(['paper_id' => $id])->all();
        return (new UpdateConferencePaper($inputs))->execute();
    }


    public function destroy($id)
    {
        return (new DeleteConferencePaper(['id' => $id]))->execute();
    }
}
