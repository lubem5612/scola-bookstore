<?php

namespace Transave\ScolaBookstore\Http\Controllers;

use Illuminate\Http\Request;
use Transave\ScolaBookstore\Actions\CartActions\AddToCart;
use Transave\ScolaBookstore\Actions\CartActions\CheckOut;
use Transave\ScolaBookstore\Actions\CartActions\ClearCart;
use Transave\ScolaBookstore\Actions\CartActions\GetCartItem;
use Transave\ScolaBookstore\Actions\CartActions\RemoveItemFromCart;
use Transave\ScolaBookstore\Actions\CartActions\UpdateCart;
use Transave\ScolaBookstore\Actions\CartActions\SearchCart;
use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Http\Models\Cart;


class CartController extends Controller
{
    use ResponseHelper;


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


    public function show($userId)
    {
        return (new GetCartItem(['user_id' => $userId]))->execute();
    }


    public function update(Request $request, $cartItemId)
    {
        $inputs = $request->merge(['cart_item_id' => $cartItemId])->all();
        return (new UpdateCart($inputs))->execute();
    }



    public function clearCart($userId)
    {
        return (new ClearCart(['user_id' => $userId]))->execute();
    }



    public function destroy($cartItemId)
    {
        return (new RemoveItemFromCart(['cart_item_id' => $cartItemId]))->execute();
    }
}
