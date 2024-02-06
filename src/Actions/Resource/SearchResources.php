<?php


namespace Transave\ScolaBookstore\Actions\Resource;


use Carbon\Carbon;
use Transave\ScolaBookstore\Helpers\ResponseHelper;

class SearchResources
{
    use ResponseHelper;

    private $request;
    private $route;
    private $relationships = [];
    private $model;
    private $routeConfig = [];
    private $queryBuilder;
    private $searchParam;
    private $perPage = 10;
    private $startAt = '';
    private $endAt = '';
    private $resources = [];

    public function __construct(array $request)
    {
        $this->request = $request;
        $this->routeConfig = config('endpoints.routes');
    }

    public function execute()
    {
        try {
            return $this
                ->validateAndSetDefaults()
                ->setModel()
                ->setModelRelationship()
                ->searchTerms()
                ->filterWithTimeStamps()
                ->filterWithOrder()
                ->getResources()
                ->sendSuccess($this->resources, 'resources returned');
        }catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }

    private function setModel()
    {
        abort_if(!array_key_exists('model', $this->route), 401, 'model not configured');
        $this->model = $this->route['model'];
        $this->queryBuilder = $this->model::query();
        return $this;
    }

    private function searchTerms()
    {
        switch ($this->request['endpoint'])
        {
            case "countries": {
                $this->queryBuilder->where('name', 'like', "%$this->searchParam%")
                    ->orWhere("code", "like", "%$this->searchParam%");
                break;
            }
            case "states": {
                $search = $this->searchParam;
                $this->queryBuilder->where('name', 'like', "%$search%")
                    ->orWhere("capital", "like", "%$search%")
                    ->orWhereHas("country", function ($q) use ($search) {
                        $q->where("name", "like", "%$search%");
                    });
                break;
            }
            case "lgs": {
                $search = $this->searchParam;
                $this->queryBuilder->where(function($q) use ($search) {
                    $q->where("name", "like", "%$search%")
                        ->orWhereHas("state", function ($q2) use ($search) {
                            $q2->where("name", "like", "%$search%");
                        });
                });
                break;
            }

            case "categories": {
                $search = $this->searchParam;
                $this->queryBuilder->where(function($q) use ($search) {
                    $q->where("name", "like", "%$search%");
                });
                break;
            }

            case "publishers": {
                $search = $this->searchParam;
                $this->queryBuilder->where(function($q) use ($search) {
                    $q->where("name", "like", "%$search%");
                });
                break;
            }

            case "faculties" : {
                $search = $this->searchParam;
                $this->queryBuilder->where(function($q) use ($search) {
                    $q->where("name", "like", "%$search%");
                });
                break;
            }

            case "departments" : {
                //../bookstore/general/departments?faculty_id={$facultyId}
                $facultyId = request()->query('faculty_id');
                if (isset($facultyId)) {
                    $this->queryBuilder->where('faculty_id', $facultyId);
                }
                $search = $this->searchParam;
                $this->queryBuilder->where(function($q) use ($search) {
                    $q->where("name", "like", "%$search%")
                        ->orWhereHas('faculty', function ($r) use ($search) {
                            $r->where('name', "like", "%$search%");
                        });
                });
                break;
            }

            case "carts" : {
                $userId = request()->query('user_id');
                if (isset($userId)) {
                    $this->queryBuilder->where('user_id', $userId);
                }

                $resourceId = request()->query('resource_id');
                if (isset($resourceId)) {
                    $this->queryBuilder->where('resource_id', $resourceId);
                }

                $isSelected = request()->query('checked');
                if (isset($resourceId) || $isSelected == '0') {
                    $this->queryBuilder->where('is_selected', $isSelected);
                }
                $search = $this->searchParam;
                $this->queryBuilder->where(function($q) use ($search) {
                    $q->where("quantity", "like", "%$search%")->orWhere("unit_price", "like", "%$search%");
                });
                break;
            }

            case "notifications" : {

            }

            case "notification-receivers" : {

            }

            case "order-items" : {

            }

            case "pickups" : {
                $orderId = request()->query('order_id');
                if (isset($orderId)) {
                    $this->queryBuilder->where('order_id', $orderId);
                }
                $search = $this->searchParam;
                $this->queryBuilder->where(function($q) use ($search) {
                    $q->where("address", "like", "%$search%")
                        ->orWhere("recipient_name", "like", "%$search%")
                        ->orWhere("postal_code", "like", "%$search%")
                        ->orWhere("email", "like", "%$search%")
                        ->orWhere("alternative_phone", "like", "%$search%")
                        ->orWhereHas('country', function ($r) use ($search) {
                            $r->where('name', "like", "%$search%");
                        })
                        ->orWhereHas('state', function ($r) use ($search) {
                            $r->where('name', "like", "%$search%");
                        })
                        ->orWhereHas('lg', function ($r) use ($search) {
                            $r->where('name', "like", "%$search%");
                        });
                });
                break;
            }

            default:
                return $this;
        }
        return $this;
    }

    private function filterWithTimeStamps()
    {
        if ($this->startAt!="null" || $this->endAt!="null" || $this->startAt!=null || $this->endAt!=null) {
            if (isset($this->startAt) && isset($this->endAt)) {
                $start = Carbon::parse($this->startAt);
                $end = Carbon::parse($this->endAt);
                $this->queryBuilder = $this->queryBuilder
                    ->whereBetween('created_at', [$start, $end]);
            }
        }
        return $this;
    }

    private function getResources()
    {
        if (isset($this->perPage)) {
            $this->resources = $this->queryBuilder->paginate($this->perPage);
        }else
            $this->resources = $this->queryBuilder->get();
        return $this;
    }

    private function filterWithOrder()
    {
        if (array_key_exists('order', $this->route)) {
            if (array_key_exists('column', $this->route['order']) && array_key_exists('pattern', $this->route['order'])) {
                $this->queryBuilder = $this->queryBuilder->orderBy($this->route['order']['column'], $this->route['order']['pattern']);
            }
        }else {
            $this->queryBuilder = $this->queryBuilder->orderBy('created_at', 'desc');
        }
        return $this;
    }

    private function setModelRelationship()
    {
        if (array_key_exists('relationships', $this->route) && count($this->route['relationships']) > 0) {
            $this->relationships = $this->route['relationships'];
            $this->queryBuilder = $this->queryBuilder->with($this->relationships);
        }
        return $this;
    }

    private function validateAndSetDefaults()
    {
        abort_if(!array_key_exists($this->request['endpoint'], $this->routeConfig), 401, 'endpoint not found');
        $this->route = $this->routeConfig[$this->request['endpoint']];
        $this->startAt = request()->query('start');
        $this->endAt = request()->query('end');
        $this->searchParam = request()->query("search");
        $this->perPage = request()->query("per_page");
        return $this;
    }

}