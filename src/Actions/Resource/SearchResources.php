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
            case "schools":
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
        
        // start of saves
            case "saves": {
             $search = $this->searchParam;

    $this->queryBuilder->where(function ($query) use ($search) {
        $query->where('resource_type', 'like', "%$search%")
            ->orWhere('resource_id', 'like', "%$search%")
            ->orWhereHas('user', function ($userQuery) use ($search) {
                $userQuery->where('first_name', 'like', "%$search%")
                    ->orWhere('last_name', 'like', "%$search%")
                    ->orWhere('role', 'like', "%$search%");
            })
            ->orWhereHas('book', function ($bookQuery) use ($search) {
                $bookQuery->where('title', 'like', "%$search%")
                            ->orWhere('primary_author', 'like', "%$search%")
                            ->orWhere('contributors', 'like', "%$search%")
                            ->orWhere('ISBN', 'like', "%$search%")
                            ->orWhere('publication_date', 'like', "%$search%")
                            ->orWhere('publisher', 'like', "%$search%")
                            ->orWhere('edition', 'like', "%$search%")
                            ->orWhereHas('user', function ($bookQuery1) use ($search) {
                                $bookQuery1->where('first_name', 'like', "%$search%")
                                    ->orWhere('last_name', 'like', "%$search%")
                                    ->orWhere('email', 'like', "%$search%");
                            })
                            ->orWhereHas('category', function ($bookQuery2) use ($search) {
                                $bookQuery2->where('name', 'like', "%$search%");
                            })
                            ->orWhereHas('publisher', function ($bookQuery3) use ($search) {
                                $bookQuery3->where('name', 'like', "%$search%");
                            });
            })
            ->orWhereHas('report', function ($reportQuery) use ($search) {
                $reportQuery->where('title', 'like', "%$search%")
                                ->orWhere('publisher', 'like', "%$search%") 
                                ->orWhere('organization', 'like', "%$search%") 
                                ->orWhere('publication_date', 'like', "%$search%")
                                ->orWhere('publication_year', 'like', "%$search%")
                                ->orWhere('report_number', 'like', "%$search%")
                                ->orWhere('institutional_affiliations', 'like', "%$search%")
                                ->orWhere('primary_author', 'like', "%$search%")
                                ->orWhere('contributors', 'like', "%$search%")
                                ->orWhere('keywords', 'like', "%$search%")
                                ->orWhere('price', 'like', "%$search%")
                                ->orWhereHas('user', function ($reportQuery1) use ($search) {
                                    $reportQuery1->where('first_name', 'like', "%$search%")
                                        ->orWhere('last_name', 'like', "%$search%")
                                        ->orWhere('email', 'like', "%$search%");
                                })
                                ->orWhereHas('category', function ($reportQuery2) use ($search) {
                                    $reportQuery2->where('name', 'like', "%$search%");
                                })
                                ->orWhereHas('publisher', function ($reportQuery3) use ($search) {
                                    $reportQuery3->where('name', 'like', "%$search%");
                                });
            })
            ->orWhereHas('journal', function ($journalQuery) use ($search) {
                $journalQuery->where('title', 'like', "%$search%")
                           ->orWhere('editorial_board_members', 'like', "%$search%")
                            ->orWhere('website', 'like', "%$search%")
                            ->orWhere('editors', 'like', "%$search%")
                            ->orWhere('editorial_board_members', 'like', "%$search%")
                            ->orWhere('publication_date', 'like', "%$search%")
                            ->orWhere('publication_year', 'like', "%$search%")
                            ->orWhere('volume', 'like', "%$search%")
                            ->orWhere('page_start', 'like', "%$search%")
                            ->orWhere('page_end', 'like', "%$search%")
                            ->orWhere('publisher', 'like', "%$search%")
                            ->orWhere('price', 'like', "%$search%")
                            ->orWhereHas('user', function ($journalQuery1) use ($search) {
                                $journalQuery1->where('first_name', 'like', "%$search%")
                                    ->orWhere('last_name', 'like', "%$search%")
                                    ->orWhere('email', 'like', "%$search%");
                            })
                            ->orWhereHas('category', function ($journalQuery2) use ($search) {
                                $journalQuery2->where('name', 'like', "%$search%");
                            })
                            ->orWhereHas('publisher', function ($journalQuery3) use ($search) {
                                $journalQuery3->where('name', 'like', "%$search%");
                            });
            })
            ->orWhereHas('festchrisft', function ($festchrisftQuery) use ($search) {
                $festchrisftQuery->where('title', 'like', "%$search%")
                                ->orWhere('publisher', 'like', "%$search%")
                                ->orWhere('editors', 'like', "%$search%")
                                ->orWhere('keywords', 'like', "%$search%")
                                ->orWhere('publication_date', 'like', "%$search%")
                                ->orWhere('dedicatees', 'like', "%$search%")
                                ->orWhere('price', 'like', "%$search%");
            })
            ->orWhereHas('conference_paper', function ($conferencePaperQuery) use ($search) {
                $conferencePaperQuery->where('title', 'like', "%$search%")
                                ->orWhere('subtitle', 'like', "%$search%")
                                ->orWhere('primary_author', 'like', "%$search%")
                                ->orWhere('contributors', 'like', "%$search%")
                                ->orWhere('keywords', 'like', "%$search%")
                                ->orWhere('conference_date', 'like', "%$search%")
                                ->orWhere('conference_name', 'like', "%$search%")
                                ->orWhere('conference_year', 'like', "%$search%")
                                ->orWhere('conference_location', 'like', "%$search%")
                                ->orWhere('institutional_affiliations', 'like', "%$search%")
                                ->orWhere('price', 'like', "%$search%")
                                ->orWhere('keywords', 'like', "%$search%");
            })
            ->orWhereHas('research_resource', function ($researchResourceQuery) use ($search) {
                $researchResourceQuery->where('title', 'like', "%$search%")
                                ->orWhere('primary_author', 'like', "%$search%")
                                ->orWhere('publisher', 'like', "%$search%")
                                ->orWhere('contributors', 'like', "%$search%")
                                ->orWhere('keywords', 'like', "%$search%")
                                ->orWhere('publication_date', 'like', "%$search%")
                                ->orWhere('publication_year', 'like', "%$search%")
                                ->orWhere('source', 'like', "%$search%")
                                ->orWhere('resource_url', 'like', "%$search%")
                                ->orWhere('resource_type', 'like', "%$search%")
                                ->orWhere('keywords', 'like', "%$search%")
                                ->orWhere('price', 'like', "%$search%")
                                ->orWhereHas('user', function ($researchResourceQuery1) use ($search) {
                                    $researchResourceQuery1->where('first_name', 'like', "%$search%")
                                        ->orWhere('last_name', 'like', "%$search%")
                                        ->orWhere('email', 'like', "%$search%");
                                })
                                ->orWhereHas('category', function ($researchResourceQuery2) use ($search) {
                                    $researchResourceQuery2->where('name', 'like', "%$search%");
                                })
                                ->orWhereHas('publisher', function ($researchResourceQuery3) use ($search) {
                                    $researchResourceQuery3->where('name', 'like', "%$search%");
                                });
            })
            ->orWhereHas('monograph', function ($monographQuery) use ($search) {
                $monographQuery->where('title', 'like', "%$search%")
                                ->orWhere('primary_author', 'like', "%$search%")
                                ->orWhere('publisher', 'like', "%$search%")
                                ->orWhere('contributors', 'like', "%$search%")
                                ->orWhere('keywords', 'like', "%$search%")
                                ->orWhere('publication_date', 'like', "%$search%")
                                ->orWhere('publication_year', 'like', "%$search%")
                                ->orWhere('ISBN', 'like', "%$search%")
                                ->orWhere('edition', 'like', "%$search%")
                                ->orWhere('keywords', 'like', "%$search%")
                                ->orWhere('price', 'like', "%$search%")
                                ->orWhereHas('user', function ($monographQuery1) use ($search) {
                                    $monographQuery1->where('first_name', 'like', "%$search%")
                                        ->orWhere('last_name', 'like', "%$search%")
                                        ->orWhere('email', 'like', "%$search%");
                                })
                                ->orWhereHas('category', function ($monographQuery2) use ($search) {
                                    $monographQuery2->where('name', 'like', "%$search%");
                                })
                                ->orWhereHas('publisher', function ($monographQuery3) use ($search) {
                                    $monographQuery3->where('name', 'like', "%$search%");
                                });
            })
            ->orWhereHas('article', function ($articleQuery) use ($search) {
                $articleQuery->where('title', 'like', "%$search%")
                            ->orWhere('publisher', 'like', "%$search%")
                            ->orWhere('primary_author', 'like', "%$search%")
                            ->orWhere('contributors', 'like', "%$search%")
                            ->orWhere('publication_date', 'like', "%$search%")
                            ->orWhere('keywords', 'like', "%$search%")
                            ->orWhere('price', 'like', "%$search%")
                            ->orWhereHas('user', function ($articleQuery1) use ($search) {
                                $articleQuery1->where('first_name', 'like', "%$search%")
                                    ->orWhere('last_name', 'like', "%$search%")
                                    ->orWhere('email', 'like', "%$search%");
                            })
                            ->orWhereHas('category', function ($articleQuery2) use ($search) {
                                $articleQuery2->where('name', 'like', "%$search%");
                            })
                            ->orWhereHas('publisher', function ($articleQuery3) use ($search) {
                                $articleQuery3->where('name', 'like', "%$search%");
                            });
                        });
                });

                break;
            }
            

// end of saves

            case "banks": {
                $search = $this->searchParam;
                $this->queryBuilder->where(function($q) use ($search) {
                    $q->where("name", "like", "%$search%")
                      ->orWhere("code", "like", "%$search%")
                        ->orWhereHas("country", function ($q2) use ($search) {
                            $q2->where("name", "like", "%$search%")
                               ->orWhere("code", "like", "%$this->searchParam%");
                     });
                });
                break;
            }

            case "bank_details": {
                $search = $this->searchParam;
                $this->queryBuilder->where(function($q) use ($search) {
                    $q->where("account_number", "like", "%$search%")
                      ->orWhere("account_name", "like", "%$search%")
                        ->orWhereHas("bank", function ($q2) use ($search) {
                           $q2->where("name", "like", "%$search%")
                              ->orWhere("code", "like", "%$search%")
                                ->orWhereHas("country", function ($q3) use ($search) {
                                    $q3->where("name", "like", "%$search%")
                                    ->orWhere("code", "like", "%$this->searchParam%");
                            });
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