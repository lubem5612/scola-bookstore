<?php


namespace Transave\ScolaBookstore\Actions\Address;


use Illuminate\Support\Arr;
use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\Address;

class UpdateAddress
{
    use ValidationHelper, ResponseHelper;
    private $request, $validatedData;
    private ?Address $address;

    public function __construct(array $request)
    {
        $this->request = $request;
    }

    public function execute()
    {
        try {
            $this->validateRequest();
            $this->setAddress();
            $this->setDefaultAddress();
            return $this->updateAddress();
        }catch (\Exception $exception) {
            return $this->sendServerError($exception);
        }
    }

    private function setAddress()
    {
        $this->address = Address::query()->find($this->validatedData['address_id']);
    }

    private function updateAddress()
    {
        $this->address->fill(Arr::except($this->validatedData, ['address_id']))->save();
        return $this->sendSuccess($this->address->refresh(), 'address updated successfully');
    }

    private function setDefaultAddress()
    {
        if (Arr::exists($this->validatedData, 'is_default') && $this->validatedData['is_default']) {
            Address::query()->where('is_default', 1)
                ->where('user_id', $this->address->user_id)
                ->update(['is_default', 0]);
            $this->validatedData['is_default'] = 1;
        }else {
            $this->validatedData['is_default'] = 0;
        }
    }

    private function validateRequest()
    {
        $this->validatedData = $this->validate($this->request, [
            'address_id' => 'required|exists:addresses,id',
            'address' => 'sometimes|required|string|max:400',
            'is_default' => 'sometimes|in:0,1',
            'country_id' => 'sometimes|required|exists:countries,id',
            'state_id' => 'sometimes|required|exists:states,id',
            'lg_id' => 'sometimes|required|exists:lgs,id',
            'postal_code' => 'nullable'
        ]);
    }
}