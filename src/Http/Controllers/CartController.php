<?php

namespace Transave\ScolaBookstore\Http\Controllers;

use Illuminate\Http\Request;
use Transave\ScolaBookstore\Actions\Cart\RemoveItem;
use Transave\ScolaBookstore\Actions\Cart\ClearCart;
use Transave\ScolaBookstore\Actions\Cart\AddToCart;
use Transave\ScolaBookstore\Actions\Cart\GetCartItem;
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
        return (new SearchBook(Book::class, ['user', 'category', 'publisher']))->execute();
    }



    public function store(Request $request)
    {
        return (new AddToCart($request->all()))->execute();
    }



    public function show($userId)
    {
        return (new GetCartItem(['id' => $userId]))->execute();
    }



    public function update(Request $request, $id)
    {
        $inputs = $request->merge(['book_id' => $id])->all();
        return (new UpdateBook($inputs))->execute();
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
