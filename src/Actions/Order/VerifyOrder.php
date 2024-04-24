<?php


namespace Transave\ScolaBookstore\Actions\Order;


use RaadaaPartners\RaadaaBase\Actions\Paystack\VerifyTransaction;
use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\Order;
use Transave\ScolaBookstore\Http\Models\Transaction;

class VerifyOrder
{
    use ValidationHelper, ResponseHelper;
    private array $request;
    private array $validatedData;
    private $response, $transaction;
    private Order $order;

    public function __construct(array $request)
    {
        $this->request = $request;
    }

    public function execute()
    {
        try {
            $this->validateRequest();
            $this->getOrder();
            $this->getTransaction();
            $this->createTransaction();
            $this->updateOrder();
            return $this->sendSuccess($this->transaction, 'transaction created successfully');
        }catch (\Exception $exception) {
            return $this->sendServerError($exception);
        }
    }

    private function getOrder()
    {
        $this->order = Order::query()->where('payment_reference', $this->validatedData['reference'])->first();
    }

    private function getTransaction()
    {
        $this->response = (new VerifyTransaction([
            'reference' => $this->validatedData['reference']
        ]))->execute();

        if (!$this->response['success']) {
            abort(403, 'transaction failed verification');
        }
    }

    private function createTransaction()
    {
        $metaData = json_decode($this->order->meta_data, true);
        $this->transaction = Transaction::query()->create([
            'user_id' => $this->order->user_id,
            'reference' => $this->validatedData['reference'],
            'amount' => $this->order->total_amount,
            'charges' => $metaData['earning'],
            'commission' => $metaData['commission'],
            'type' => 'credit',
            'description' => 'Resource purchase attempted',
            'status' => 'successful',
            'payload' => json_encode($this->response),
        ]);
    }

    private function updateOrder()
    {
        $this->order->update([
            'payment_status' => 'paid',
            'delivery_status' => 'processing',
            'order_status' => 'success'
        ]);
    }

    public function validateRequest()
    {
        $this->validatedData = $this->validate($this->request, [
            'reference' => 'required|exists:orders,payment_reference',
        ]);
        return $this;
    }
}