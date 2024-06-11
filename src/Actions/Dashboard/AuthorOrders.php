<?php


namespace Transave\ScolaBookstore\Actions\Dashboard;


use Illuminate\Database\Eloquent\Builder;
use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Http\Models\OrderItem;

class AuthorOrders
{
    use ResponseHelper;

    private $output;
    private Builder $queryBuilder;
    private $httpRequest, $per_page, $page;

    public function __construct()
    {
        $this->httpRequest = request();
        $this->per_page = $this->httpRequest->query('per_page');
        $this->page = $this->httpRequest->query('page');
    }

    public function execute()
    {
        try {
            $this->initQueryBuilder();
            $this->filterByAuthorId();
            $this->filterByOrderStatus();
            $this->filterByPaymentStatus();
            $this->orderingPattern();
            $this->handlePagination();
            return $this->sendSuccess($this->output, 'authors ordered resources retrieved');
        }catch (\Exception $exception) {
            return $this->sendServerError($exception);
        }
    }

    private function initQueryBuilder()
    {
        $this->queryBuilder = OrderItem::query()
            ->leftJoin('resources', 'order_items.resource_id', '=', 'resources.id')
            ->leftJoin('orders', 'order_items.order_id', '=', 'orders.id')
            ->leftJoin('authors', 'resources.author_id', '=', 'authors.id')
            ->leftJoin('users', 'authors.user_id', '=', 'users.id')
            ->select('order_items.id as order_item_id'.'order_items.quantity'. 'order_items.unit_price',
                'resources.title', 'resources.source', 'resources.price', 'resources.page_url', 'orders.payment_status',
                'orders.order_status', 'users.first_name', 'users.last_name', 'users.email');
    }

    private function filterByAuthorId()
    {
        $author = $this->httpRequest->query('author_id');
        if (isset($author)) {
            $this->queryBuilder->where('resources.author_id', $author);
        }
    }

    private function filterByOrderStatus()
    {
        $orderStatus = $this->httpRequest->query('order_status');
        if (isset($orderStatus)) {
            $this->queryBuilder->where('orders.order_status', $orderStatus);
        }
    }

    private function filterByPaymentStatus()
    {
        $paymentStatus = $this->httpRequest->query('payment_status');
        if (isset($orderStatus)) {
            $this->queryBuilder->where('orders.payment_status', $paymentStatus);
        }
    }

    private function orderingPattern()
    {
        $this->queryBuilder->orderByDesc('order_items.created_at');
    }

    private function handlePagination()
    {
        if (isset($this->per_page) || isset($this->page)) {
            $perPage = isset($this->per_page)? $this->per_page : 10;
            $this->output = $this->queryBuilder->paginate($perPage);
        }else {
            $this->output = $this->queryBuilder->get();
        }
    }
}