<?php

namespace Transave\ScolaBookstore\Actions\Sales;

use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Models\OrderItem;
use Transave\ScolaBookstore\Models\User;

class UserPurchases
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
                ->getItemsPurchasedByUser();
        } catch (\Exception $e) {
            return $this->sendServerError($e->getMessage());
        }
    }

    /**
     * @return $this
     */
    private function getItemsPurchasedByUser(): self
    {
        $items = OrderItem::whereHas('order', function ($query) {
            $query->where('user_id', $this->user->id);
        })->get();

        return $this->sendSuccess($items, 'Items purchased by the user retrieved successfully');
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
