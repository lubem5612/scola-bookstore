<?php

namespace Transave\ScolaBookstore\Http\Controllers;

use Illuminate\Http\Request;
use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\RestFulAPIHelper;

class RestfulAPIController extends Controller
{
    use ResponseHelper;
    private $api;

    public function __construct(RestFulAPIHelper $api)
    {
        $this->api = $api;
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request, $endpoint)
    {
        return $this->structureResponse($this->api->fetchAllResources($request, $endpoint));
    }

    public function store(Request $request, $endpoint)
    {
        return $this->structureResponse($this->api->saveResource($request, $endpoint));
    }

    public function show($endpoint, $id)
    {
        return $this->structureResponse($this->api->fetchResource($endpoint, $id));
    }

    public function update(Request $request, $endpoint, $id)
    {
        return $this->structureResponse($this->api->updateResource($request, $endpoint, $id));
    }

    public function destroy($endpoint, $id)
    {
        return $this->structureResponse($this->api->deleteResource($endpoint, $id));
    }

    private function structureResponse(array $data)
    {
        if ($data['success']) {
            return $this->sendSuccess($data['data'], $data['message']);
        }
        return $this->sendError($data['message'], $data['data'], $data['code']);
    }
}