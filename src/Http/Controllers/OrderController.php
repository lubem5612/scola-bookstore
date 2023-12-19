<?php

namespace Transave\ScolaBookstore\Http\Controllers;

use Illuminate\Http\Request;
use Transave\ScolaBookstore\Actions\Order\CreateOrder;
use Transave\ScolaBookstore\Actions\Order\GetOrder;
use Transave\ScolaBookstore\Actions\Order\SearchOrder;
use Transave\ScolaBookstore\Actions\Order\UpdateOrder;
use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Http\Models\Order;

class OrderController extends Controller
{
    use ResponseHelper;


    /**
     * Get a listing of orders
     *
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaBookstore\Helpers\Response
     */
    public function index()
    {
        return (new SearchOrder(Order::class, ['user', 'book']))->execute();
    }



    /**
     * create an order
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaBookstore\Helpers\Response
     */
    public function store(Request $request)
    {
        return (new CreateOrder($request->all()))->execute();
    }



    /**
     * Get an order by id
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaBookstore\Helpers\Response
     */
    public function show($id)
    {
        return (new GetOrder(['id' => $id]))->execute();
    }



    /**
     * Update a specified order
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaBookstore\Helpers\Response
     */
    public function update(Request $request, $id)
    {
        $inputs = $request->merge(['order_id' => $id])->all();
        return (new UpdateOrder($inputs))->execute();
    }
}