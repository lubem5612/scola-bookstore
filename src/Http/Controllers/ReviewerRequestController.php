<?php

namespace Transave\ScolaBookstore\Http\Controllers;

use Illuminate\Http\Request;
use Transave\ScolaBookstore\Actions\Reviewer\BecomeReviewer;
use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Http\Models\ReviewerRequest;


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
        return (new SearchReviewer(ReviewerRequest::class, ['user']))->execute();
    }


    public function store(Request $request)
    {
        return (new BecomeReviewer($request->all()))->execute();
    }


    public function show($id)
    {
        return (new SearchReviewer(ReviewerRequest::class, ['user'], $id))->execute();
    }



    public function update(Request $request, $id)
    {
        $inputs = $request->merge(['reviewer_id' => $id])->all();
        return (new UpdateReviewer($inputs))->execute();
    }



    public function destroy($id)
    {
        return (new DeleteReviewer(['reviewer_id' => $id]))->execute();
    }

}
