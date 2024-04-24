<?php


namespace Transave\ScolaBookstore\Actions\Order;


use Illuminate\Support\Arr;
use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\Order;
use Transave\ScolaBookstore\Http\Models\Pickup;

class UpdateOrder
{
    use ValidationHelper, ResponseHelper;
    private $request, $validatedData;
    private Order $order;

    public function __construct(array $request)
    {
        $this->request = $request;
    }

    public function execute()
    {
        try {
            $this->validateRequest();
            $this->setOrder();
            $this->updatePickupLocation();
            return $this->updateOrder();
        }catch (\Exception $exception) {
            return $this->sendServerError($exception);
        }
    }

    private function setOrder()
    {
        $this->order = Order::query()->find($this->validatedData['order_id']);
    }

    private function updatePickupLocation()
    {
        $pickup = Pickup::query()->where('order_id', $this->order->id)->first();
        $data = Arr::only($this->validatedData, ['address', 'country_id', 'state_id', 'lg_id', 'recipient_name', 'postal_code', 'email', 'alternative_phone']);
        $pickup->fill($data)->save();
    }

    private function updateOrder()
    {
        $data = Arr::only($this->validatedData, ['delivery_status', 'order_status', 'payment_status']);
        $this->order->fill($data)->save();
        return $this->sendSuccess($this->order->refresh(), 'order updated successfully');
    }

    private function validateRequest()
    {
        $this->validatedData = $this->validate($this->request, [
            'order_id' => 'required|exists:orders,id',
            'delivery_status' => 'sometimes|required|in:processing,on_the_way,arrived,delivered,cancelled',
            'order_status' => 'sometimes|required|in:success,failed',
            'payment_status' => 'sometimes|required|in:paid,unpaid',
            'address' => 'nullable|string|max:750',
            'country_id' => 'nullable|exists:countries,id',
            'state_id' => 'nullable|exists:states,id',
            'lg_id' => 'nullable|exists:lgs,id',
            'recipient_name' => 'nullable|string|max:150',
            'postal_code' => 'nullable|string|max:16',
            'email' => 'nullable|email|max:100',
            'alternative_phone' => 'nullable|string|max:16|min:8'
        ]);
    }
}