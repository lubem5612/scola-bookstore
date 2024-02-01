<?php


namespace Transave\ScolaBookstore\Http\Controllers;


use Illuminate\Http\Request;
use Transave\ScolaBookstore\Actions\Resource\DeleteResource;
use Transave\ScolaBookstore\Actions\Resource\GetResource;
use Transave\ScolaBookstore\Actions\Resource\SearchResources;
use Transave\ScolaBookstore\Actions\Resource\StoreResource;
use Transave\ScolaBookstore\Actions\Resource\UpdateResource;

class ResourceController extends Controller
{
    /**
     * ResourceController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['store', 'destroy', 'update']);
    }

    /**
     * Get a listing of resources
     *
     * @param $endpoint
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function index($endpoint)
    {
        return (new SearchResources(['endpoint' => $endpoint]))->execute();
    }

    /**
     * Create a new resource
     *
     * @param Request $request
     * @param $endpoint
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function store(Request $request, $endpoint)
    {
        return (new StoreResource(['endpoint' => $endpoint, 'data' => $request->all()]))->execute();
    }

    /**
     * Get a specified resource from storage
     *
     * @param $endpoint
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function show($endpoint, $id)
    {
        return (new GetResource(['endpoint' => $endpoint, 'id' => $id]))->execute();
    }

    /**
     * Update a specified resource
     *
     * @param Request $request
     * @param $endpoint
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function update(Request $request, $endpoint, $id)
    {
        $data = array_merge($request->all(), ['id' => $id]);
        return (new UpdateResource(['endpoint' => $endpoint, 'data' => $data]))->execute();
    }

    /**
     * Delete a specified resource from storage
     *
     * @param $endpoint
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function destroy($endpoint, $id)
    {
        return (new DeleteResource(['endpoint' => $endpoint, 'id' => $id]))->execute();
    }

}