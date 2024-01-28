<?php

namespace Transave\ScolaBookstore\Http\Controllers;

use Illuminate\Http\Request;
use Transave\ScolaBookstore\Actions\Order\CreateOrder;
use Transave\ScolaBookstore\Actions\Order\DeleteOrder;
use Transave\ScolaBookstore\Actions\Order\DeleteOrderItem;
use Transave\ScolaBookstore\Actions\Order\SearchOrder;
use Transave\ScolaBookstore\Actions\Order\UpdateOrder;
use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Http\Models\Order;

class OrderController extends Controller
{
    use ResponseHelper;


    public function index()
    {
        return (new SearchOrder(OrderItem::class, ['user', 'order', 'book', 'report', 'journal', 'festchrisft', 'conference_paper', 'research_resource', 'monograph', 'article']))->execute();
    }


    public function store(Request $request)
    {
        return (new CreateOrder($request->all()))->execute();
    }


    public function show($invoiceNumber)
    {
        return (new SearchOrder(OrderItem::class, ['user', 'order', 'book', 'report', 'journal', 'festchrisft', 'conference_paper', 'research_resource', 'monograph', 'article'], $invoiceNumber))->execute();
    }



    public function update(Request $request, $id)
    {
        $inputs = $request->merge(['order_id' => $id])->all();
        return (new UpdateOrder($inputs))->execute();
    }


    public function deleteOrder($id)
    {
        $request = ['order_id' => $id];
        $action = new DeleteOrder($request);
        return $action->execute();
    }


    public function deleteOrderItem($id)
    {
        $request = ['order_item_id' => $id];
        $action = new DeleteOrderItem($request);
        return $action->execute();
    }
}