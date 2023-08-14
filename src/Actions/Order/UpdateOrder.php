<?php

namespace Transave\ScolaBookstore\Actions\Order;

use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\Order;

class UpdateOrder
{
    use ValidationHelper, ResponseHelper;

    private array $request;
    private array $validatedInput;
    private $user;
    private $uploader;
    private $order;


    public function __construct(array $request)
    {
        $this->request = $request;
    }


    public function execute()
    {
        try {
            return $this->validateRequest()
                ->setOrderId()
                ->updateOrder();
        }catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }


    private function setOrderId(){
        $this->order = Order::query()->find($this->validatedInput['order_id']);
        return $this;
    }



    private function updateOrder()
    {
        if (isset($this->validatedInput['status'])) {
            $this->validate($this->validatedInput, [
                'status' => 'in:processing,on_the_way,arrived,delivered,cancelled',
            ]);
            $this->order->status = $this->validatedInput['status'];
        }
        $this->order->fill($this->validatedInput)->save();
        return $this->sendSuccess($this->order->refresh(), 'Order updated');
    }



    private function validateRequest(): self
    {
        $data = $this->validate($this->request, [
            'order_id' => 'required|exists:orders,id',
            'user_id' => 'sometimes|required|exists:users,id',
            'book_id' => 'sometimes|required|exists:books,id',
            'amount' => 'sometimes|required',
            'total_amount' => 'sometimes|required',
            'status' => 'sometimes|required|in:processing,on_the_way,arrived,delivered,cancelled',
        ]);
        $this->validatedInput = $data;
        return $this;
    }
}