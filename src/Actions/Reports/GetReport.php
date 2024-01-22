<?php

namespace Transave\ScolaBookstore\Actions\Reports;

use Transave\ScolaBookstore\Events\ReportViewed;
use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\Report;

class GetReport
{
    use ValidationHelper, ResponseHelper;

    private array $request;
    private array $validatedInput;
    private Report $report;

    public function __construct(array $request)
    {
        $this->request = $request;
    }


    public function execute()
    {
        try {
            return $this
                ->validateRequest()
                ->getReport()
                ->sendSuccess($this->report, 'Report fetched successfully');
        }catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }
    private function getReport()
    {
        $this->report = Report::query()->with(['user', 'category', 'publisher'])->find($this->request['id']);
        return $this;
    }

    private function validateRequest(): self
    {
        $id = $this->validate($this->request, [
            'id' => 'required|exists:reports,id'
        ]);
        $this->validatedInput = $id;
        return $this;
    }
}