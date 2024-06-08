<?php


namespace Transave\ScolaBookstore\Http\Controllers;


use Illuminate\Http\Request;
use Transave\ScolaBookstore\Actions\Dashboard\Admin;
use Transave\ScolaBookstore\Actions\Dashboard\AdminAnalytic;
use Transave\ScolaBookstore\Actions\Dashboard\AuthorOrders;
use Transave\ScolaBookstore\Actions\Dashboard\AuthorRevenue;
use Transave\ScolaBookstore\Http\Models\OrderItem;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function adminAnalytics()
    {
        return (new Admin())->execute();
    }

    public function adminGraph(Request $request)
    {

        return (new AdminAnalytic(['year' => $request->query('year')]))->execute();
    }

    public function authorOrderItems()
    {
        return (new AuthorOrders(OrderItem::class, ['resource' => function($query) {
           $query->select('title', 'price', 'author_id');
        }, 'resource.author.user']))->execute();
    }

    public function authorRevenue($id)
    {
        return (new AuthorRevenue(['author_id' => $id]))->execute();
    }
}