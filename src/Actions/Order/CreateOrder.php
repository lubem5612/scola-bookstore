<?php


namespace Transave\ScolaBookstore\Actions\Order;


use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\Address;
use Transave\ScolaBookstore\Http\Models\Cart;
use Transave\ScolaBookstore\Http\Models\Order;
use Transave\ScolaBookstore\Http\Models\OrderItem;
use Transave\ScolaBookstore\Http\Models\Pickup;
use Transave\ScolaBookstore\Http\Models\Resource;

class CreateOrder
{
    use ValidationHelper, ResponseHelper;
    private $request, $validatedData;
    private User $user;
    private Order $order;
    private array $cart;
    private float $totalPrice = 0;
    private float $totalCommission = 0;
    private float $totalEarning = 0;

    public function __construct(array $request)
    {
        $this->request = $request;
    }

    public function execute()
    {
        try {
            $this->validateRequest();
            $this->setUser();
            $this->getSelectedCart();
            $this->getSelectedResource();
            $this->createOrder();
            $this->createOrderItems();
            $this->creatPickUp();
            $this->clearCheckedOutCart();
            return $this->sendSuccess($this->order, 'order created successfully');
        }catch (\Exception $exception) {
            return $this->sendServerError($exception);
        }
    }

    private function setUser()
    {
        $this->user = config('scola-bookstore.auth_model')::query()->find($this->validatedData['user_id']);
    }

    private function getSelectedCart()
    {
        $this->cart = Cart::query()
            ->where('is_selected', 1)
            ->where('user_id', $this->validatedData['user_id'])
            ->get()->toArray();
    }

    private function clearCheckedOutCart()
    {
        $this->cart = Cart::query()
            ->where('is_selected', 1)
            ->where('user_id', $this->validatedData['user_id'])
            ->delete();
    }

    private function getSelectedResource()
    {
        if (Arr::exists($this->validatedData, 'resource_id')) {
            if (is_array($this->validatedData['resource_id']) && count($this->validatedData['resource_id']) > 0) {
                foreach ($this->validatedData['resource_id'] as $validatedDatum) {

                    $item['id'] = Str::uuid()->toString();
                    $item['resource_id'] = $validatedDatum;
                    $item['user_id'] = $this->validatedData['user_id'];
                    $item['quantity'] = 1;
                    $item['is_selected'] = 1;

                    array_push($this->cart, $item);
                }
            }else {

                $item['id'] = Str::uuid()->toString();
                $item['resource_id'] = $this->validatedData['resource_id'];
                $item['user_id'] = $this->validatedData['user_id'];
                $item['quantity'] = 1;
                $item['is_selected'] = 1;

                array_push($this->cart, $item);
            }
        }
    }

    private function createOrder()
    {
        if (count($this->cart) > 0) {
            abort(404, 'cart is empty');
        }else {
            $this->order = Order::query()->create([
                'user_id' => $this->validatedData['user_id'],
                'invoice_number' => Carbon::now()->format('Ymd').'-'.rand(100000, 999999),
                'payment_status' => 'unpaid',
                'payment_reference' => $this->validatedData['reference']
            ]);
        }
    }

    private function createOrderItems()
    {
        foreach ($this->cart as $item) {
            $resource = Resource::query()->find($item['resource_id']);
            $item['unit_price'] = $resource->price;
            $this->totalPrice = $this->totalPrice + (float) $item['unit_price'] * (float)$item['quantity'];

            $this->totalCommission = $this->totalCommission + (float)($resource->percentage_share/100) * (float)$item['unit_price'] * (float)$item['quantity'];

            $percentageEarning = (float)(100 - $resource->percentage_share)/100;
            $this->totalEarning = $this->totalEarning + $percentageEarning * (float)$item['unit_price'] * (float)$item['quantity'];

            OrderItem::query()->create([
                'order_id' => $this->order->id,
                'resource_id' => $item['resource_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'discount' => 0
            ]);
        }

        $this->order->update([
            'total_amount' => $this->totalPrice,
            'meta_data' => json_encode(['commission' => $this->totalCommission, 'earning' => $this->totalEarning, 'message' => 'resource purchase attempted']),
        ]);
    }

    private function creatPickUp()
    {
        $address = Address::query()->where('user_id', $this->user->id)
            ->where('is_default', 1)->first();

        if (!Arr::exists($this->validatedData, 'address'))
            $this->validatedData['address'] = $address->address;

        if (!Arr::exists($this->validatedData, 'country_id'))
            $this->validatedData['country_id'] = $address->country_id;

        if (!Arr::exists($this->validatedData, 'state_id'))
            $this->validatedData['state_id'] = $address->state_id;

        if (!Arr::exists($this->validatedData, 'lg_id'))
            $this->validatedData['lg_id'] = $address->lg_id;

        if (!Arr::exists($this->validatedData, 'recipient_name'))
            $this->validatedData['recipient_name'] = $this->user->first_name.' '.$this->user->last_name;

        if (!Arr::exists($this->validatedData, 'email'))
            $this->validatedData['email'] = $this->user->email;

        if (!Arr::exists($this->validatedData, 'alternative_phone'))
            $this->validatedData['alternative_phone'] = $this->user->phone;

        $pickUpData = Arr::only($this->validatedData, ['address', 'country_id', 'state_id', 'lg_id', 'email', 'recipient_name', 'postal_code', 'alternative_phone']);
        $pickUpData['order_id'] = $this->order->id;

        Pickup::query()->create($pickUpData);
    }

    private function validateRequest()
    {
        $this->validatedData = $this->validate($this->request, [
            'resource_id' => 'nullable',
            'reference' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'address' => 'required|string|max:750',
            'country_id' => 'required|exists:countries,id',
            'state_id' => 'required|exists:states,id',
            'lg_id' => 'required|exists:lgs,id',
            'recipient_name' => 'nullable|string|max:150',
            'postal_code' => 'nullable|string|max:16',
            'email' => 'nullable|email|max:100',
            'alternative_phone' => 'nullable|string|max:16|min:8'
        ]);
    }
}