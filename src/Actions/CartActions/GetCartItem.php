<?php

namespace Transave\ScolaBookstore\Actions\CartActions;

use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\Cart;

class GetCartItem
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
                ->setUser();
        } catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }


    
    private function setUser()
    {
        $this->user = Cart::query()->with([
                    'user', 
                    'report', 
                    'monograph', 
                    'journal', 
                    'book', 
                    'festchrisft', 
                    'conference_paper', 
                    'research_resource', 
                    'article',
            ])
            ->find($this->validatedInput['user_id']);
         return $this->sendSuccess($user, 'Cart items retrieved successfully');
    }




    private function validateRequest(): self
    {
        $this->validatedInput = $this->validate($this->request, [
            'user_id' => 'required|string|max:225|exists:users,id',
        ]);

        return $this;
    }
}

