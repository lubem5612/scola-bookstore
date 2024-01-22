<?php

namespace Transave\ScolaBookstore\Http\Controllers;

use Illuminate\Http\Request;
use Transave\ScolaBookstore\Actions\ResearchResources\CreateResearchResource;
use Transave\ScolaBookstore\Actions\ResearchResources\DeleteResearchResource;
use Transave\ScolaBookstore\Actions\ResearchResources\GetResearchResource;
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


    /**
     * Get a listing of ResearchResource;
     *
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaBookstore\Helpers\Response
     */
    public function index()
    {
        return (new SearchResearchResource(ResearchResource::class, ['user', 'category', 'publisher']))->execute();
    }




    /**
     * create ResearchResource;
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaBookstore\Helpers\Response
     */
    public function store(Request $request)
    {
        return (new CreateResearchResource($request->all()))->execute();
    }



    /**
     * Get a specified ResearchResource;
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaBookstore\Helpers\Response
     */
    public function show($id)
    {
        return (new GetResearchResource(['id' => $id]))->execute();
    }



    /**
     * Update a specified ResearchResource;
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaBookstore\Helpers\Response
     */
    public function update(Request $request, $id)
    {
        $inputs = $request->merge(['researchResource_id' => $id])->all();
        return (new UpdateResearchResource($inputs))->execute();
    }


    /**
     * Delete a specified ResearchResource;
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaBookstore\Helpers\Response
     */
    public function destroy($id)
    {
        return (new DeleteResearchResource(['id' => $id]))->execute();
    }
}
