<?php

namespace Transave\ScolaBookstore\Actions\CartActions;

use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\Cart;
use Illuminate\Support\Facades\Config;


class ClearCart
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
                ->clearCart();
        } catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }



    private function setUser(): self
    {
        $this->user = Cart::query()->find($this->validatedInput['user_id']);
        return $this;
    }



    private function clearCart()
    {
        $this->user->cart()->delete();

        return $this->sendSuccess(null, 'Cart cleared successfully');
    }



    private function validateRequest(): self
    {
        $this->validatedInput = $this->validate($this->request, [
            'user_id' => 'required|string|max:225|exists:users,id',
        ]);

        return $this;
    }
}
