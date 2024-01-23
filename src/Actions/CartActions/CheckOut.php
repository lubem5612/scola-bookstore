<?php

namespace Transave\ScolaBookstore\Actions\CartActions;

use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\Cart;
use Transave\ScolaBookstore\Http\Models\Order;
use Carbon\Carbon;
use Paystack\Paystack;

class CheckOut
{
    use ValidationHelper, ResponseHelper;

    private array $request;
    private array $validatedInput;
    private $order;
    private $user;

    public function __construct(array $request)
    {
        $this->request = $request;
    }

    public function execute()
    {
        try {
            $this->validateRequest()
                ->setUser()
                ->checkCart()
                ->placeOrder()
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

    private function placeOrder(): self
    {
        // Extract commonly used properties for cleaner code
        $orderDetails = [
            'user_id' => $this->user->id,
            'order_date' => Carbon::now(),
            'status' => 'processing',
        ];

        $cartItems = [];

        foreach ($this->user->cart as $cartItem) {
            $orderItem = new Order([
                'order_id' => $cartItem->id,
                'resource_id' => $cartItem->resource_id,
                'quantity' => $cartItem->quantity,
                'unit_price' => $cartItem->unit_price,
                'total_amount' => $cartItem->unit_price * $cartItem->quantity,
                'invoice_number' => rand(100000, 999999),
                // Include commonly used order details
                ...$orderDetails,
            ]);

            $cartItems[] = $orderItem;
        }

        $this->order = Order::query()->create($orderDetails);
        $this->order->orderItems()->saveMany($cartItems);

        return $this;
    }

    private function processPayment(): self
    {
        // Assuming you have the Paystack secret key
        $paystack = new Paystack('your_paystack_secret_key');

        // You need to implement logic to get the payment details from your client-side
        $paymentDetails = $this->getPaymentDetails();

        // Call Paystack to verify the payment
        $verificationResponse = $paystack->transaction->verify([
            'reference' => $paymentDetails['reference'],
        ]);

        if (!$verificationResponse->data->status) {
            $this->sendError('Payment verification failed', 401);
        }

        return $this;
    }

    private function getPaymentDetails()
    {
        // Implement logic to retrieve payment details from your client-side
        return [
            'reference' => $this->validatedInput['payment_reference'],
            'amount' => $this->validatedInput['amount'],
            'user_id' => $this->validatedInput['user_id'],
            'invoice_number' => $this->validatedInput['invoice_number'],
        ];
    }

    private function clearCart()
    {
        $this->user->cart()->delete();

        return $this->sendSuccess(null, 'Order placed successfully. Cart cleared.');
    }

    private function validateRequest(): self
    {
        $this->validatedInput = $this->validate($this->request, [
            'user_id' => 'required|string|max:225|exists:users,id',
            'order_id' => 'required|string|max:225',
            'quantity' => 'required|max:225',
            'unit_price' => 'required|max:225',
            'amount' => 'required|max:225',
            'invoice_number' => 'required|max:225',
            'order_date' => 'required|max:225',
            'payment_reference' => 'required|string|max:225',
        ]);

        return $this;
    }
}
