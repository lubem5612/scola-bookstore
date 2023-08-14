<?php

namespace Transave\ScolaBookstore\Helpers;

use Carbon\Carbon;

trait SearchHelper
{
    use ResponseHelper;
    private $output, $queryBuilder, $relationshipArray, $searchParam, $perPage, $startAt, $endAt, $id;

    public function __construct($model, array $relationshipArray=[], $id=null)
    {
        $this->relationshipArray = $relationshipArray;
        $this->queryBuilder = $model::query();
        $this->searchParam = request()->query("search");
        $this->perPage = request()->query("per_page");
        $this->endAt = request()->query("end");
        $this->startAt = request()->query("start");
        $this->id = $id;
    }

    public function execute()
    {
        try {
            return
                $this
                    ->modelHasRelationship()
                    ->querySingleResource()
                    ->handleTimeStampQuery()
                    ->searchTerms()
                    ->groupedBy()
                    ->handlePagination()
                    ->sendSuccess($this->output, 'query returned Ok');

        }catch (\Exception $ex) {
            return $this->sendServerError($ex);
        }
    }

    private function handleTimeStampQuery()
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

    private function modelHasRelationship()
    {
        if (count($this->relationshipArray)) {
            $this->queryBuilder = $this->queryBuilder->with($this->relationshipArray);
        }
        return $this;
    }

    private function querySingleResource()
    {
        if (!is_null($this->id) || isset($this->id)) {
            $result = $this->queryBuilder->find($this->id);
            return $this->sendSuccess($result, 'result returned Ok');
        }
        return $this;
    }

    private function handlePagination()
    {
        if (isset($this->perPage)) {
            $this->output = $this->queryBuilder->paginate($this->perPage);
        }else
            $this->output = $this->queryBuilder->get();

        return $this;
    }

    private function searchTerms()
    {
        //
        return $this;
    }

    private function groupedBy()
    {
        $this->queryBuilder = $this->queryBuilder->orderBy("created_at", "DESC");
        return $this;
    }

}