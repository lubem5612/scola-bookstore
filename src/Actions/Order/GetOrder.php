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
                ->sendSuccess($this->order, 'order fetched successfully');
        }catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }


    private function setOrder(): self
    {
        $this->order = Order::query()->with(['user', 'book'])->find($this->request['id']);
        return $this;
    }

    private function validateRequest(): self
    {
        $id = $this->validate($this->request, [
            'id' => 'required|exists:orders,id'
        ]);
        $this->validatedInput = $id;
        return $this;
    }
}