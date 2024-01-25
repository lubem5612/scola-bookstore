<?php

namespace Transave\ScolaBookstore\Actions\Cart;

use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\Cart;

class GetCartItem
{
    use ValidationHelper, ResponseHelper;

    private array $request;
    private array $validatedInput;
    private Cart $cart;


    public function __construct(array $request)
    {
        $this->request = $request;
    }


    public function execute()
    {
        try {
            return $this
                ->validateRequest()
                ->setCart()
                ->sendSuccess($this->cart, 'Cart Items fetched');
        }catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }



    private function setCart()
    {
        $this->cart = Cart::query()->find($this->request['id']);
        return $this;
    }


    

    private function validateRequest(): self
    {
        $id = $this->validate($this->request, [
            'id' => 'required|exists:carts,id'
        ]);
        $this->validatedInput = $id;
        return $this;
    }
}