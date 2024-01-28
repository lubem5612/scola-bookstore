<?php

namespace Transave\ScolaBookstore\Actions\Order;

use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\OrderItem;

class DeleteOrderItem
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
                ->deleteOrderItem()
                ->sendSuccess(null, 'Order item deleted successfully');
        } catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }

    private function deleteOrderItem(): self
    {
        OrderItem::where('id', $this->validatedInput['order_item_id'])
            ->where('order_id', $this->validatedInput['order_id'])
            ->delete();

        return $this;
    }

    private function validateRequest(): self
    {
        $this->validatedInput = $this->validate($this->request, [
            'order_id' => 'required|exists:orders,id',
            'order_item_id' => 'required|exists:order_items,id',
        ]);

        return $this;
    }
}
