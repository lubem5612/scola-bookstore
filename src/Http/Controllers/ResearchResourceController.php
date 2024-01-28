<?php

namespace Transave\ScolaBookstore\Http\Controllers;

use Illuminate\Http\Request;
use Transave\ScolaBookstore\Actions\ResearchResources\CreateResearchResource;
use Transave\ScolaBookstore\Actions\ResearchResources\DeleteResearchResource;
use Transave\ScolaBookstore\Actions\ResearchResources\SearchResearchResource;
use Transave\ScolaBookstore\Actions\ResearchResources\UpdateResearchResource;
use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Http\Models\ResearchResource;


class ResearchResourceController extends Controller
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
        return (new SearchResearchResource(ResearchResource::class, ['user', 'category', 'publisher']))->execute();
    }



    public function store(Request $request)
    {
        return (new CreateResearchResource($request->all()))->execute();
    }



    public function show($id)
    {
        return (new SearchResearchResource(ResearchResource::class, ['user', 'category', 'publisher'], $id))->execute();
    }


    public function update(Request $request, $id)
    {
        $inputs = $request->merge(['researchResource_id' => $id])->all();
        return (new UpdateResearchResource($inputs))->execute();
    }


    public function destroy($id)
    {
        return (new DeleteResearchResource(['id' => $id]))->execute();
    }
}
