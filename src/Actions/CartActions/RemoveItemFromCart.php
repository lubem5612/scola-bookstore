<?php

namespace Transave\ScolaBookstore\Actions\CartActions;

use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\Cart;

class RemoveItemFromCart
{
    use ValidationHelper, ResponseHelper;

    private array $validatedInput;
    private array $request;
    

    public function __construct(array $request)
    {
        $this->request = $request;
    }



    public function execute()
    {
        try {
            $this->validateRequest()
                ->setCartItemId()
                ->removeCartItem();
        } catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }



    private function setCartItemId(): self
    {
        $this->cartItem = Cart::query()->find($this->validatedInput['cart_item_id']);
        return $this;
    }


    private function removeCartItem()
    {
        $this->cartItem->delete();
        return $this->sendSuccess(null, 'Cart item removed');
    }



    private function validateRequest(): self
    {
        $this->validatedInput = $this->validate($this->validatedInput, [
            'cart_item_id' => 'required|string|max:225',
        ]);

        return $this;
    }
    
}

