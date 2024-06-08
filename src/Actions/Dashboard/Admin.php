<?php


namespace Transave\ScolaBookstore\Actions\Dashboard;

use Illuminate\Foundation\Auth\User;
use Transave\ScolaBookstore\Actions\BaseAction;
use Transave\ScolaBookstore\Http\Models\Author;
use Transave\ScolaBookstore\Http\Models\Order;
use Transave\ScolaBookstore\Http\Models\Resource;

class Admin extends BaseAction
{
    private $response = [];

    public function execute()
    {
        try {
            $this->setTopAuthors();
            $this->setTopResources();
            $this->setUsersCount();
            $this->setResourceCount();
            $this->setViewsCount();
            $this->setSalesCount();
            return $this->sendSuccess($this->response, 'admin dashboard data retrieved');
        }catch (\Exception $exception) {
            return $this->sendServerError($exception);
        }
    }

    private function setTopAuthors()
    {
        $this->response['top_authors'] = Author::query()
            ->with(['user'])->withCount(['resources'])
            ->orderBy('resources_count', 'desc')->take(9)->get();
    }

    private function setTopResources()
    {
        $this->response['best_selling_resources'] = Author::query()
            ->with(['author.user'])->withCount(['orders'])
            ->orderBy('orders_count', 'desc')->take(9)->get();
    }

    private function setUsersCount()
    {
        $this->response['users_count'] = User::query()->count();
    }

    private function setResourceCount()
    {
        $this->response['resources_count'] = Resource::query()->count();
    }

    private function setViewsCount()
    {
        $this->response['views_count'] = Resource::query()->sum('number_of_views');
    }

    private function setSalesCount()
    {
        $this->response['total_sales'] = Order::query()->where('order_status', 'success')->sum('total_amount');
    }
}