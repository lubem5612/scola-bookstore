<?php

namespace Transave\ScolaBookstore\Actions\Order;

use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\Order;

class DeleteOrder
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
            return $this->validateRequest()
                ->deleteOrder();
        } catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }

    private function deleteOrder()
    {
        $order = Order::query()->find($this->validatedInput['order_id']);
        if (empty($order)) {
            return $this->sendError('order not found');
        }elseif ($order->payment_status == 'paid') {
            return $this->sendError('order has already been paid and cant be cancelled');
        }else {
            $order->delete();
            return $this->sendSuccess(null, 'order deleted successfully');
        }
    }

    private function validateRequest(): self
    {
        $this->validatedInput = $this->validate($this->request, [
            'order_id' => 'required|exists:orders,id',
        ]);
        return $this;
    }
}
