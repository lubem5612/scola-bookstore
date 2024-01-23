<?php

namespace Transave\ScolaBookstore\Actions\CartActions;

use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\Cart;
use Illuminate\Support\Facades\Config;


class AddToCart
{
    use ValidationHelper, ResponseHelper;

    private array $request;
    private array $validatedInput;
    private $user;

    public function __construct(array $request)
    {
        $this->request = $request;
    }

    public function execute()
    {
        try {
            $this->validateRequest()
                ->setUser()
                ->addItemToCart();
        } catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }



    private function setUser(): self
    {
        $this->user = Config::get('scola-bookstore.auth_model')::query()->find($this->validatedInput['user_id']);
        return $this;
    }



    private function addItemToCart()
    {
        $cartData = array_merge(['user_id' => $this->user->id], $this->validatedInput);

        $cartItem = Cart::create($cartData);
        return $this->sendSuccess($cartItem, 'Item added to cart');
    }



    private function validateRequest(): self
    {
        $this->validatedInput = $this->validate($this->request, [
            'user_id' => 'required|string|max:225|exists:users,id',
            'resource_id' => 'required|string|max:225',
            'quantity' => 'required|max:225',
            'unit_price' => 'required|max:225',
            'cart_status' => 'required|max:225',
        ]);

        return $this;
    }
}

