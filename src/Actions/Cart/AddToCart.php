<?php

namespace Transave\ScolaBookstore\Actions\Cart;

use Illuminate\Support\Arr;
use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\Cart;
use Transave\ScolaBookstore\Http\Models\User;
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
            return $this->validateRequest()
                ->setUser()
                ->createCart();
        } catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }



    private function setUser(): self
    {
        $this->user = Config::get('scola-bookstore.auth_model')::query()->find($this->validatedInput['user_id']);
        return $this;
    }



    private function createCart()
    {
        $cart = Cart::query()->create($this->validatedInput);
        return $this->sendSuccess($cart, 'Item added to Cart');
    }

    

    private function validateRequest(): self
    {
        $this->validatedInput = $this->validate($this->request, [
            'user_id' => 'required|string|max:225|exists:users,id',
            'resource_id' => 'required|string|max:225',
            'resource_type' => 'required|max:225|string',
            'quantity' => 'required|max:225|integer',
            'unit_price' => 'required|max:225|numeric',
        ]);

        return $this;

    }
}

