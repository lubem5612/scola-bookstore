<?php


namespace Transave\ScolaBookstore\Actions\Address;


use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\Address;

class DeleteAddress
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
            $this->getAddress();
            $this->checkIfDefault();
            return $this->deleteAddress();
        }catch (\Exception $exception) {
            return $this->sendServerError($exception);
        }
    }

    private function getAddress()
    {
        $this->address = Address::query()->find($this->validatedData['address_id']);
    }

    private function checkIfDefault()
    {
        if ($this->address->is_default) {
            $address = Address::query()->where('user_id', $this->address->user_id)->latest()->first();
            $address->update(['is_default' => 1]);
        }
    }

    private function deleteAddress()
    {
        $this->address->delete();
        return $this->sendSuccess(null, 'address deleted successfully');
    }

    private function validateRequest()
    {
        $this->validatedData = $this->validate($this->request, [
            'address_id' => 'required|exists:addresses,id'
        ]);
    }

}