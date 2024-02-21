<?php

namespace Transave\ScolaBookstore\Actions\Order;

use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\Order;
use Transave\ScolaBookstore\Http\Models\OrderItem;

class DeleteOrderItem
{
    use ValidationHelper, ResponseHelper;

    private array $request;
    private array $validatedInput;
    private Order $order;
    private OrderItem $orderItem;

    public function __construct(array $request)
    {
        $this->request = $request;
    }

    public function execute()
    {
        try {
            $this->validateRequest();
            $this->getOrder();
            $this->updateOrder();
            return $this->deleteOrderIdem();
        } catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }

    private function getOrder()
    {
        $this->orderItem = OrderItem::query()->find($this->validatedInput['order_item_id']);
        $this->order = Order::query()->find($this->orderItem->order_id);
    }

    public function updateOrder()
    {
        $orderItemPrice = (float)$this->orderItem->unit_price * (float)$this->orderItem->quantity;
        $this->order->update([
            'total_amount' => (float)$this->order->total_amount - $orderItemPrice
        ]);
    }

    public function deleteOrderIdem()
    {
        $this->orderItem->delete();
        return $this->sendSuccess(null, 'order item deleted');
    }

    private function validateRequest(): self
    {
        $this->validatedInput = $this->validate($this->request, [
            'order_item_id' => 'required|exists:order_items,id',
        ]);
    }
}
