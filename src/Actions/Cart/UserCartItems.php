<?php

namespace Transave\ScolaBookstore\Actions\Cart;

use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Models\Cart;
use Transave\ScolaBookstore\Models\User;

class UserCartItems
{
    use ResponseHelper;

    private array $request;
    private array $validatedInput;
    private User $user;

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
                ->setUser()
                ->getItemsInCartByUser();
        } catch (\Exception $e) {
            return $this->sendServerError($e->getMessage());
        }
    }

    /**
     * @return $this
     */
    private function getItemsInCartByUser(): self
    {
        $itemsInCart = Cart::where('user_id', $this->user->id)
            ->with('book', 'report', 'journal', 'festchrisft', 'conference_paper', 'research_resource', 'monograph', 'article')
            ->get();

        return $this->sendSuccess($itemsInCart, 'Items in the user\'s cart retrieved successfully');
    }

    /**
     * @return $this
     */
    private function setUser(): self
    {
        $this->user = User::findOrFail($this->validatedInput['user_id']);
        return $this;
    }

    /**
     * @return $this
     */
    private function validateRequest(): self
    {
        $data = $this->validate($this->request, [
            'user_id' => 'required|exists:users,id',
        ]);
        $this->validatedInput = $data;
        return $this;
    }
}
