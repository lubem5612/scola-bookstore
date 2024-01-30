<?php

namespace Transave\ScolaBookstore\Actions\Order;

use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\Sale;
use Transave\ScolaBookstore\Http\Models\Order;
use Carbon\Carbon;

class SaleAction
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
            return $this
                ->validateRequest()
                ->setOrder()
                ->createSale()
                ->handleSuccess();
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    private function validateRequest(): self
    {
        $this->validatedInput = $this->validate($this->request, [
            'order_id' => 'required|exists:orders,id',
            'amount' => 'required|numeric',
        ]);

        return $this;
    }

    private function setOrder(): self
    {
        $this->order = Order::find($this->validatedInput['order_id']);

        if (!$this->order) {
            $this->sendError('Order not found', [], 404);
        }

        return $this;
    }

    private function createSale(): self
    {
        Sale::create([
            'order_id' => $this->order->id,
            'amount' => $this->validatedInput['amount'],
            'created_at' => Carbon::now(),
        ]);

        return $this;
    }

    private function handleSuccess()
    {
        return $this->sendSuccess('Sale created successfully.');
    }

    private function handleError(\Exception $e)
    {
        // Log the entire exception (including stack trace)
        \Log::error($e);

        return $this->sendError($e->getMessage());
    }
}
