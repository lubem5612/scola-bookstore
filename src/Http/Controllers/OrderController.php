<?php

namespace Transave\ScolaBookstore\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Transave\ScolaBookstore\Actions\Order\CreateOrder;
use Transave\ScolaBookstore\Actions\Order\DeleteOrder;
use Transave\ScolaBookstore\Actions\Order\SearchOrder;
use Transave\ScolaBookstore\Actions\Order\UpdateOrder;
use Transave\ScolaBookstore\Actions\Order\VerifyOrder;
use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Http\Models\Order;

class OrderController extends Controller
{
    use ResponseHelper;

    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index()
    {
        return (new SearchOrder(Order::class, ['user', 'orderItems', 'pickup']))->execute();
    }

    public function store(Request $request)
    {
        return (new CreateOrder($request->all()))->execute();
    }

    public function show($id)
    {
        if (Str::isUuid($id)) {
            return (new SearchOrder(Order::class, ['user', 'orderItems', 'pickup'], $id))->execute();
        }else {
            $order = Order::query()->with(['user', 'orderItems', 'pickup'])->where('invoice_number', $id)->first();
            return $this->sendSuccess($order, 'order returned successfully');
        }
    }

    public function update(Request $request, $id)
    {
        $inputs = $request->merge(['order_id' => $id])->all();
        return (new UpdateOrder($inputs))->execute();
    }

    public function verify($reference)
    {
        return (new VerifyOrder(['reference' => $reference]))->execute();
    }

    public function destroy($id)
    {
        $request = ['order_id' => $id];
        $action = new DeleteOrder($request);
        return $action->execute();
    }
}