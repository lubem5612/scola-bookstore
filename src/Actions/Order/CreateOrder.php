<?php

namespace Transave\ScolaBookstore\Actions\Order;

use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Actions\Order\PaystackInitialization;
use Illuminate\Support\Facades\Mail;
use Transave\ScolaBookstore\Actions\Mail\PaymentReceipt;
use Transave\ScolaBookstore\Http\Models\Order;
use Transave\ScolaBookstore\Http\Models\Cart;
use Illuminate\Support\Facades\Config;
use Transave\ScolaBookstore\Http\Models\OrderItem;
use Carbon\Carbon;
use Paystack\Paystack;


class CreateOrder
{
    use ValidationHelper, ResponseHelper;

    private array $request;
    private array $validatedInput;
    private $user;
    private $order;
    private $orderItem;


    public function __construct(array $request)
    {
        $this->request = $request;
    }

    public function execute()
    {
        try {
            return $this->validateRequest()
                ->setUser()
                ->confirmUser()
                ->checkCart()
                ->setDeliveryStatus()
                ->createOrder()
                ->initializePaystackTransaction()
                ->verifyPaystackPayment()
                ->sendReceiptEmail()
                ->clearUserCart()
                ->handleSuccess();
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }



    private function setUser(): self
    {
        $this->user = Config::get('scola-bookstore.auth_model')::query()->find($this->validatedInput['user_id']);
        return $this;
    }



    private function confirmUser(): self
    {
        $this->cart = Cart::query()->find($this->user->id);
        return $this;
    }



    private function checkCart(): self
    {
        if ($this->cart->isEmpty()) {
            $this->sendError('Cart is empty. Add items before checkout', [], 401);
        }
        return $this;
    }



    private function setDeliveryStatus(): void
    {
        $this->validatedInput['delivery_status'] = 'processing';
    }


    protected function createOrder(): self 
    {
        $this->order = Order::create([
            'user_id' => $this->validatedInput['user_id'],
            'invoice_number' => mt_rand(10000000, 99999999),
            'order_date' => Carbon::now(),
            'status' => 'success',
        ]);

        foreach ($this->validatedInput as $key => $value) {
            if (is_array($value) && isset($value['resource_id'])) {
                $totalAmount = $value['quantity'] * $value['unit_price'];

                $this->orderItem = OrderItem::create([
                    'order_id' => $this->order->id,
                    'resource_id' => $this->cart->resource_type,
                    'resource_type' => $this->cart->resource_type,
                    'quantity' => $this->cart->quantity,
                    'unit_price' => $this->cart->unit_price,
                    'total_amount' => $totalAmount,
                ]);
            }
        }

        return $this;
    }



    protected function initializePaystackTransaction()
    {
        $paystack = new PaystackInitialization();
        $response = $paystack->initializePaystackTransaction($this->validatedInput['total_amount'], $this->user->email);

        if (!$this->verifyPaystackPayment($response['data']['reference'])) {
            $this->sendError('Payment verification failed', [], 401);
        }

        $this->order->update([
            'payment_status' => 'success',
            'payment_reference' => $response['data']['reference'],
        ]);

        return $this;
    }



    protected function verifyPaystackPayment()
    {
        $paystack = new PaystackInitialization();
        $verification = $paystack->verifyPayment($this->data['reference']);

        if ($verification['payment_status'] !== 'success') {
            $this->sendError('Payment verification failed', [], 401);
        }

        return $this;
    }



    protected function sendReceiptEmail()
    {
        try {
            Mail::to($this->user->email)->send(new PaymentReceipt($this->order));
        } catch (\Exception $e) {
            // Handle email sending error (log, etc.)
        }
        return $this;
    }


    private function clearCart(): self
    {
        $this->user->cart()->delete();
        return $this;
    }



    protected function handleSuccess()
    {
         return $this->sendSuccess('Order placed successfully.');
    }



    protected function handleError(\Exception $e)
    {
        return $this->sendError($e->getMessage());
    }

    
    
    private function validateRequest(): self
    {
        $this->validatedInput = $this->validate($this->request, [ 
                'user_id' => 'required|exists:users,id',
                'resource_id' => 'required|string|max:255',
                'quantity' => 'required|integer|max:255',
                'order_date' => 'required|max:255',
                'unit_price' => 'required|max:255|numeric',
                'total_amount' => 'required|max:255|numeric',
                'invoice_number' => 'required|string|max:255',
                'status' => 'required|string|max:255',
                'resource_type' => 'required|string|max:255',
                'payment_status' => 'string|max:255',
                'delivery_status' => 'required|string|max:255',
                'payment_reference' => 'string|max:255',
        ]);

        return $this;
    }


}
