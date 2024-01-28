<?php

namespace Transave\ScolaBookstore\Actions\Order;

use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
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


    public function __construct(array $request)
    {
        $this->request = $request;
    }

    public function execute()
    {
        try {
            return $this->validateRequest()
                ->setUser()
                ->checkCart()
                ->setInvoice()
                ->setOrderStatus()
                ->placeOrder()
                ->getPaymentDetails()
                ->processPayment()
                ->clearCart();
        } catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }

 
    private function setUser(): self
    {
        $this->user = Cart::query()->find($this->validatedInput['user_id']);
        return $this;
    }


    private function checkCart(): self
    {
        if ($this->user->cart->isEmpty()) {
            $this->sendError('Cart is empty. Add items before checkout', [], 401);
        }
        return $this;
    }


    private function setInvoice(): self
    {
        $newInvoiceNumber = mt_rand(10000000, 99999999);
        $this->validatedInput['invoice_number'] = $newInvoiceNumber;
        return $this;
    }

    private function setOrderStatus(): void
    {
        $this->validatedInput['status'] = 'processing';
    }


    private function placeOrder(): self
    {
        $orderDetails = [
            'user_id' => $this->user->id,
            'order_date' => Carbon::now(),
            'status' => $this->validatedInput['status'],
            'invoice_number' => $this->validatedInput['invoice_number'],
            ]; 
                        
            $cartItems = [];

        foreach ($this->user->cart as $cartItem) {
           $orderItem = new OrderItem([
                'order_id' => $cartItem->id,
                'resource_id' => $cartItem->resource_id,
                'quantity' => $cartItem->quantity,
                'unit_price'=> $cartItem->unit_price,
                'total_amount' => $cartItem->unit_price * $cartItem->quantity,
                'invoice_number'=> $orderDetails['invoice_number'],            
            ]);

            $cartItems[] = $orderItem;
        }

        $this->order = Order::query()->create($orderDetails);
        $this->order->orderItems()->saveMany($cartItems);

        return $this;
    }


    private function getPaymentDetails()
    {
        return [
            'total_amount' => $this->validatedInput['total_amount'],
            'user_id' => $this->validatedInput['user_id'],
            'invoice_number' => $this->validatedInput['invoice_number'],
        ];
    }


    private function processPayment(): self
    {
        // Assuming you have the Paystack secret key
        $paystack = new Paystack('your_paystack_secret_key');

        //payment details from your client-side
        $paymentDetails = $this->getPaymentDetails();

        // Call Paystack to verify the payment
        $verificationResponse = $paystack->transaction->verify([
            'invoice_number' => $paymentDetails['invoice_number'],
        ]);

        if (!$verificationResponse->data->status) {
            $this->sendError('Payment verification failed', 401);
        }
        return $this;
    }


    private function clearCart()
    {
        $this->user->cart()->delete();
        return $this->sendSuccess(null, 'Order placed successfully.');
    }


    private function validateRequest(): self
    {
        $this->validatedInput = $this->validate($this->request, [
            'user_id' => 'required|exists:users,id',
            'resource_id' => 'required|string|max:225',
            'quantity' => 'required|integer|max:225',
            'order_date' => 'required|max:225',
            'unit_price' => 'required|max:225|numeric',
            'total_amount' => 'required|max:225|numeric',
            'invoice_number' => 'required|string|max:225',
            'status' => 'required|string|max:225',
            'resource_type' => 'required|string|max:225',
        ]);
        return $this;
    }
}
