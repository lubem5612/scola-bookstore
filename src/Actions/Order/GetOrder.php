<?php

namespace Transave\ScolaBookstore\Actions\Order;

use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\Order;

class GetOrder
{
    use ValidationHelper, ResponseHelper;

    private array $request;
    private array $validatedInput;
    private array $orders; // Change to an array to store multiple orders

    public function __construct(array $request)
    {
        $this->request = $request;
    }

    public function execute()
    {
        try {
            return $this
                ->validateRequest()
                ->setOrders()
                ->sendSuccess($this->orders, 'Orders fetched successfully');
        } catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }

    private function setOrders(): self
    {
        $userOrders = Order::query()
            ->where('user_id', $this->validatedInput['user_id'])
            ->with(['user', 'book'])
            ->get();

        $this->orders = [];

        foreach ($userOrders as $order) {
            $this->orders[] = [
                'order' => $order,
                'invoice_number' => $order->invoice_number,
            ];
        }

        return $this;
    }


    private function validateRequest(): self
    {
        $validatedInput = $this->validate($this->request, [
            'user_id' => 'required|exists:users,id', // Assuming there's a 'users' table with 'id' as the primary key
        ]);

        $this->validatedInput = $validatedInput;
        return $this;
    }
}
