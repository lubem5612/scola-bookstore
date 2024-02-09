<?php


namespace Transave\ScolaBookstore\Actions\Address;


use Illuminate\Support\Arr;
use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\Address;

class CreateAddress
{
    use ValidationHelper, ResponseHelper;
    private $request, $validatedData;

    public function __construct(array $request)
    {
        $this->request = $request;
    }

    public function execute()
    {
        try {
            $this->validateRequest();
            $this->setDefaultAddress();
            return $this->createAddress();
        }catch (\Exception $exception) {
            return $this->sendServerError($exception);
        }
    }

    private function createAddress()
    {
        $address = Address::query()->create($this->validatedData);
        return $this->sendSuccess($address, 'address created successfully');
    }

    private function setDefaultAddress()
    {
        if (Arr::exists($this->validatedData, 'is_default') && $this->validatedData['is_default']) {
            Address::query()->where('is_default', 1)
                ->where('user_id', $this->validatedData['user_id'])
                ->update(['is_default', 0]);
            $this->validatedData['is_default'] = 1;
        }else {
            $this->validatedData['is_default'] = 0;
        }
    }

    private function validateRequest()
    {
        $this->validatedData = $this->validate($this->request, [
            'user_id' => 'required|exists:users,id',
            'address' => 'required|string|max:400',
            'is_default' => 'sometimes|in:0,1'
        ]);
    }
}