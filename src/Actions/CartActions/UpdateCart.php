<?php

// UpdateCartAction.php

namespace Transave\ScolaBookstore\Actions\CartActions;

use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\Cart;

class UpdateCartAction
{
    use ValidationHelper, ResponseHelper;

    private array $request;
    private array $validatedInput;


    public function __construct(array $request)
    {
        $this->request = $request;
    }

    

    public function execute()
    {
        try {
            $this->validateRequest()
                ->setCartItemId()
                ->updateCartItem();
        } catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }



    private function setCartItemId(): self
    {
        $this->cartItem = Cart::query()->find($this->validatedInput['cart_item_id']);
        return $this;
    }



    private function updateCartItem()
    {
        $this->cartItem->fill($this->validatedInput)->save();
        return $this->sendSuccess($this->cartItem->refresh(), 'Cart item updated');
    }



    private function validateRequest(): self
    {
        $this->validatedInput = $this->validate($this->request, [
            'cart_item_id' => 'required|string|max:225',
            'quantity' => 'required|max:225',
        ]);

        return $this;
    }
}

