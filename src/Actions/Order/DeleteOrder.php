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
                ->deleteOrder()
                ->sendSuccess(null, 'Order and associated items deleted successfully');
        } catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }

    private function deleteOrder(): self
    {
        Order::destroy($this->validatedInput['order_id']);
        return $this;
    }

    private function validateRequest(): self
    {
        $this->validatedInput = $this->validate($this->request, [
            'order_id' => 'required|exists:orders,id',
        ]);
        return $this;
    }
}
