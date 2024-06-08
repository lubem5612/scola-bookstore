<?php


namespace Transave\ScolaBookstore\Actions\Dashboard;


use Illuminate\Database\Eloquent\Builder;
use Transave\ScolaBookstore\Actions\BaseAction;
use Transave\ScolaBookstore\Http\Models\Order;

class AuthorRevenue extends BaseAction
{
    private array $response = [];
    private ?Builder $queryBuilder;

    public function __construct(array $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        $this->initialiseQuery();
        $this->getAuthorsRevenue();
        $this->getAuthorOrderedResources();
        return $this->sendSuccess($this->response, 'authors metrics returned');
    }

    public function setValidationRules(): array
    {
        return [
            "author_id" => "required|exists:authors,id",
        ];
    }

    private function initialiseQuery()
    {
        $authorId = $this->validatedData['author_id'];
        $this->queryBuilder = Order::query()
            ->whereHas('orderItems.resource', function (Builder $builder) use ($authorId) {
                $builder->where('orderItems.resource.author_id', $authorId);
            })->where('order_status', 'success');
    }

    private function getAuthorsRevenue()
    {
        $this->response['author_revenue'] = $this->queryBuilder->sum('total_amount');
    }

    private function getAuthorOrderedResources()
    {
        $this->response['author_books_sold'] = $this->queryBuilder->count();
    }
}