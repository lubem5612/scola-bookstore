<?php

namespace Transave\ScolaBookstore\Http\Controllers;

use Illuminate\Http\Request;
use Transave\ScolaBookstore\Actions\Cart\RemoveItem;
use Transave\ScolaBookstore\Http\Models\Cart;
use Transave\ScolaBookstore\Actions\Cart\ClearCart;
use Transave\ScolaBookstore\Actions\Cart\UpdateCart;
use Transave\ScolaBookstore\Actions\Cart\AddToCart;
use Transave\ScolaBookstore\Actions\Cart\CheckOut;
use Transave\ScolaBookstore\Actions\Cart\SearchCart;
use Transave\ScolaBookstore\Helpers\ResponseHelper;



class CartController extends Controller
{
    use ResponseHelper;


    /**
     * AuthController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }



    public function index()
    {
        return (new SearchCart(Cart::class, ['user']))->execute();
    }



    public function store(Request $request)
    {
        return (new AddToCart($request->all()))->execute();
    }



    public function checkout(Request $request)
    {
        return (new CheckOut($request->all()))->execute();
    }



    public function show($id)
    {
        return (new SearchCart(Cart::class, ['user'], $id))->execute();
    }


    public function update(Request $request, $id)
    {
        $inputs = $request->merge(['cart_item_id' => $id])->all();
        return (new UpdateCart($inputs))->execute();
    }



   public function clearCart(ClearCart $clearCart, $userId)
    {
        return $clearCart->execute($userId);
    }



    public function removeItem($cartItemId)
    {
        return (new RemoveItem(['id' => $cartItemId]))->execute();
    }



}
