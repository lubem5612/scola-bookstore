<?php


namespace Transave\ScolaBookstore\Helpers;


use Illuminate\Support\Facades\Validator;



class RestFulAPIHelper
{
    use ResponseHelper;
    /**
     * @var \Illuminate\Config\Repository|mixed
     */
    public $routes;

    public function __construct()
    {
        $this->routes = config('endpoints.routes');

    }

    public function fetchAllResources($request, $endpoint)
    {
        try {
            if(!array_key_exists($endpoint, $this->routes)) {
                return $this->buildResponse('endpoint not found', false, null, 404);
            }

            if (!array_key_exists('model', $this->routes[$endpoint])) {
                return $this->buildResponse('model not found', false, null, 502);
            }

            if (array_key_exists('order', $this->routes[$endpoint]) &&
                array_key_exists('column', $this->routes[$endpoint]['order']) &&
                array_key_exists('pattern', $this->routes[$endpoint]['order'])) {
                $builder = $this->routes[$endpoint]['model']::orderBy($this->routes[$endpoint]['order']['column'], $this->routes[$endpoint]['order']['pattern']);
            }else {
                $builder = $this->routes[$endpoint]['model']::orderBy('created_at', 'desc');
            }

            $query = $request->query();

            if(isset($query) && count($query) > 0) {
                unset($query['page']);
                unset($query['limit']);
                $builder->where($query);
            }

            if ($request->query('page')) {
                $limit = $request->query('limit');
                $results = isset($limit)? $builder->paginate($limit) : $builder->paginate(10);
            }else {
                $results = $builder->get();
            }

            return $this->buildResponse('resources fetched successfully', true, $results->toArray());

        } catch (\Exception $exception) {
            return $this->buildResponse('server error', false, $exception->getTrace(), 500);
        }
    }

    /**
     * create a new resource
     *
     * @param $request
     * @param $endpoint
     * @return mixed
     */
    public function saveResource($request, $endpoint)
    {
        try {
            if(!array_key_exists($endpoint, $this->routes)) {
                return $this->buildResponse('endpoint not found', false, null, 404);
            }

            if (array_key_exists('rules', $this->routes[$endpoint]) && array_key_exists('store', $this->routes[$endpoint]['rules'])) {
                $rules = $this->routes[$endpoint]['rules']['store'];
                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    $error = $validator->errors();
                    return $this->buildResponse('validation errors', false, $error, 422);
                }
            }

            $input = $request->all();
            if (!array_key_exists('model', $this->routes[$endpoint])) {
                return $this->buildResponse('model not specified', false, null, 502);
            }
            $response = $this->routes[$endpoint]['model']::create($input);
            return $this->buildResponse('resource created successfully', true, $response);
        } catch (\Exception $exception) {
            return $this->buildResponse('server error', false, $exception->getTrace(), 500);
        }
    }

    /**
     * get a specified resource
     *
     * @param $endpoint
     * @param $id
     * @return mixed
     */
    public function fetchResource($endpoint, $id)
    {
        try {
            if(!array_key_exists($endpoint, $this->routes)) {
                return $this->buildResponse('endpoint not found', false, null, 404);
            }

            if (!array_key_exists('model', $this->routes[$endpoint])) {
                return $this->buildResponse('model not found', false, null, 502);
            }
            $builder = $this->routes[$endpoint]['model']::query();
            if (array_key_exists('relationships', $this->routes[$endpoint])) {
                if (count($this->routes[$endpoint]['relationships']) > 0) {
                    $builder = $builder->with($this->routes[$endpoint]['relationships']);
                }
            }
            $resource = $builder->find($id);
            if (empty($resource)) {
                return $this->buildResponse('resource not found', true, null,404);
            }

            return $this->buildResponse('resource retrieved successfully', true, $resource);
        } catch (\Exception $exception) {
            return $this->buildResponse('server error', false, $exception->getTrace(), 500);
        }
    }

    /**
     * update a specified resource
     *
     * @param $request
     * @param $endpoint
     * @param $id
     * @return mixed
     */
    public function updateResource($request, $endpoint, $id)
    {
        try {
            if(!array_key_exists($endpoint, $this->routes)) {
                return $this->buildResponse('endpoint not found', false, null, 404);
            }

            if (array_key_exists('rules', $this->routes[$endpoint]) && array_key_exists('update', $this->routes[$endpoint]['rules'])) {
                $rules = $this->routes[$endpoint]['rules']['update'];
                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    $error = $validator->errors();
                    return $this->buildResponse('validation errors', false, $error, 422);
                }
            }

            if (!array_key_exists('model', $this->routes[$endpoint])) {
                return $this->buildResponse('model not found', false, null, 502);
            }
            $resource = $this->routes[$endpoint]['model']::find($id);
            if (empty($resource)) {
                return $this->buildResponse('resource not found', true, null,404);
            }
            $updated = $resource->fill($request->all())->save();

            if ($updated) {
                return $this->buildResponse('resource updated successfully', true, $resource);
            }
            return $this->buildResponse('error in updating resource', false, null, 502);

        } catch (\Exception $exception) {
            return $this->buildResponse('server error', false, $exception->getTrace(), 500);
        }
    }

    /**
     * delete specified resource
     *
     * @param $endpoint
     * @param $id
     * @return mixed
     */
    public function deleteResource($endpoint, $id)
    {
        try {
            if(!array_key_exists($endpoint, $this->routes)) {
                return $this->buildResponse('endpoint not found', false, null, 404);
            }

            if (!array_key_exists('model', $this->routes[$endpoint])) {
                return $this->buildResponse('model not found', false, null, 502);
            }

            if ($this->routes[$endpoint]['model']::where('id', $id)->exists()) {
                $resource = $this->routes[$endpoint]['model']::find($id)->delete();

                return $this->buildResponse('resource deleted successfully', true, $resource);
            } else {
                return $this->buildResponse('resource not found in database', false, null, 404);
            }
        } catch (\Exception $exception) {
            return $this->buildResponse('server error', false, $exception->getTrace(), 500);
        }
    }
}