<?php


namespace Transave\ScolaBookstore\Actions\Dashboard;


use Illuminate\Support\Arr;
use Transave\ScolaBookstore\Actions\BaseAction;
use Transave\ScolaBookstore\Http\Models\Order;

class AdminAnalytic extends BaseAction
{
    private $response = [];
    private array $months = ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'];
    private $year;

    public function __construct(array $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        $this->setYear();
        $this->getGraphData();
        return $this->sendSuccess($this->response, 'admin sales data retrieved');
    }

    public function setValidationRules(): array
    {
        return [
            "year" => "sometimes|required|integer|min:2024|max:2100",
        ];
    }

    private function setYear()
    {
        if (!Arr::exists($this->validatedData, "year")) {
            $this->year = date('Y');
        }
    }

    private function getGraphData()
    {
        foreach (range(0, 11) as $item) {
            $month = $item + 1;
            $data = [
                "sales" => Order::query()->whereYear('created_at', $this->year)
                    ->whereMonth('created_at', $month)->sum('total_amount'),
                "month" => $this->months[$item]
            ];
            array_push($this->response, $data);
        }
    }
}