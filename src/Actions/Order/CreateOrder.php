<?php

namespace Transave\ScolaBookstore\Actions\Order;

use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\Order;

class CreateOrder
{
    use ValidationHelper, ResponseHelper;

    private array $request;
    private array $validatedInput;
    private $user;
    private $book;
    private $order;


    public function __construct(array $request)
    {
        $this->request = $request;
    }

    public function execute()
    {
        try {
            return $this->validateRequest()
                ->setUser()
                ->setInvoice()
                ->setStatus()
                ->createOrder();
        } catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }

    private function setInvoice(): self
    {
        do {
            $newInvoiceNumber = mt_rand(10000000, 99999999);
            $exists = Order::where('invoice_no', $newInvoiceNumber)->exists();
        } while ($exists);
        $this->validatedInput['invoice_no'] = $newInvoiceNumber;

        return $this;
    }


    private function setStatus(): self
    {
        $this->validatedInput['status'] = 'processing';
        return $this;
    }

    private function createOrder()
    {
        $order = Order::query()->create($this->validatedInput);
        return $this->sendSuccess($order->load('user', 'book'), 'Order placed successfully');
    }

    private function setUser(): self
    {
        $this->user = config('scola-bookstore.auth_model')::query()->find($this->validatedInput['user_id']);
        return $this;
    }

    private function validateRequest(): self
    {
        $data = $this->validate($this->request, [
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'amount' => 'required',
            'total_amount' => 'required',
        ]);
        $this->validatedInput = $data;
        return $this;
    }
}