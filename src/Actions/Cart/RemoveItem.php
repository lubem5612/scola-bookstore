<?php

namespace Transave\ScolaBookstore\Actions\Cart;

use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\Cart;


class RemoveItem
{
    use ResponseHelper, ValidationHelper;

    private array $request;
    private array $validatedInput;
    private Cart $cart;


    public function __construct(array $request)
    {
        $this->request = $request;
    }

    

    /**
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaBookstore\Helpers\Response
     */
    public function execute()
    {
        try {
            return $this
                ->validateRequest()
                ->setItem()
                ->deleteItem();
        } catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }


    private function deleteItem()
    {
        $this->cart->delete();
        return $this->sendSuccess(null, 'Item Removed successfully');
    }


  

    /**
     * @return $this
     */
    private function setItem(): self
    {
        $this->cart = Cart::query()->find($this->validatedInput['id']);
        return $this;
    }



    /**
     * @return $this
     */
    private function validateRequest(): self
    {
        $this->validatedInput  = $this->validate($this->request, [
            'id' => 'required|exists:carts,id',
        ]);
        return $this;
    }
}
