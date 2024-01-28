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


    public function index()
    {
        return (new SearchMonograph(Monograph::class, ['user', 'category', 'publisher']))->execute();
    }



    public function store(Request $request)
    {
        return (new CreateMonograph($request->all()))->execute();
    }


    public function show($id)
    {
        return (new SearchMonograph(Monograph::class, ['user', 'category', 'publisher'], $id))->execute();
    }


    public function update(Request $request, $id)
    {
        $inputs = $request->merge(['monograph_id' => $id])->all();
        return (new UpdateMonograph($inputs))->execute();
    }


    public function destroy($id)
    {
        return (new DeleteMonograph(['id' => $id]))->execute();
    }
}
