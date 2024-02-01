<?php

namespace Transave\ScolaBookstore\Actions\Order;

use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Models\Order;
use Transave\ScolaBookstore\Models\User;

class UserOrder
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
                ->getOrdersMadeByUser();
        } catch (\Exception $e) {
            return $this->sendServerError($e->getMessage());
        }
    }

    /**
     * @return $this
     */
    private function getOrdersMadeByUser(): self
    {
        $orders = Order::where('user_id', $this->user->id)
            ->with('orderItems', 'orderItems.book', 'orderItems.report', 'orderItems.journal', 'orderItems.festchrisft', 'orderItems.conference_paper', 'orderItems.research_resource', 'orderItems.monograph', 'orderItems.article')
            ->get();

        return $this->sendSuccess($orders, 'Orders made by the user retrieved successfully');
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
