<?php

namespace Transave\ScolaBookstore\Actions\Order;

use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\Order;
use Transave\ScolaBookstore\Http\Models\OrderItem;

class UpdateOrder
{
    use ValidationHelper, ResponseHelper;

    private array $request;
    private array $validatedInput;
    private Order $order;

    public function __construct(array $request)
    {
        $this->request = $request;
    }

    public function execute()
    {
        try {
            return $this->validateRequest()
                ->setOrder()
                ->updateOrder()
                ->updateOrderItems()
                ->sendSuccess($this->order, 'Order updated successfully');
        } catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }

    private function setOrder(): self
    {
        $this->order = Order::findOrFail($this->validatedInput['order_id']);
        return $this;
    }

    private function updateOrder(): self
    {
        $orderDetails = [
            'order_status' => $this->validatedInput['order_status'] ?? $this->order->order_status,
            'delivery_status' => $this->validatedInput['delivery_status'] ?? $this->order->delivery_status,
        ];

        $this->order->update($orderDetails);

        return $this;
    }


    private function updateOrderItems(): self
    {
       foreach ($this->validatedInput as $key => $itemData) {
            $orderItem = OrderItem::findOrFail($this->validatedInput['order_item_id']);
            $totalAmount = ($itemData['unit_price'] ?? $orderItem->unit_price) * ($itemData['quantity'] ?? $orderItem->quantity);

            $orderItem->update([
                'quantity' => $itemData['quantity'] ?? $orderItem->quantity,
                'unit_price' => $itemData['unit_price'] ?? $orderItem->unit_price,
                'total_amount' => $totalAmount,
            ]);
        }

        return $this;
    }



    private function validateRequest(): self
    {
        $this->validatedInput = $this->validate($this->request, [
            'order_id' => 'required|exists:orders,id',
            'status' => 'sometimes|required|string|max:225',
            'order_item_id' => 'required|exists:order_items,id',
            'quantity' => 'sometimes|required|integer|max:225',
            'unit_price' => 'sometimes|required|max:225|numeric',
            'total_amount' => 'sometimes|required|max:225|numeric',
        ]);

        return $this;
    }
}
