<?php

namespace Transave\ScolaBookstore\Actions\Pickup;

use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\Pickup;

class UpdatePickup
{
    use ValidationHelper, ResponseHelper;

    private array $request;
    private array $validatedInput;
    private $pickup;

    public function __construct(array $request)
    {
        $this->request = $request;
    }

    public function execute()
    {
        try {
            return $this->validateRequest()
                ->findPickup()
                ->updatePickup();
        } catch (\Exception $e) {
             return $this->sendServerError($e);
        }
    }

    private function findPickup(): self
    {
        $this->pickup = Pickup::find($this->validatedInput['pickup_id']);
        return $this;
    }

        private function updatePickup()
    {
        $this->pickup->fill($this->validatedInput)->save();
        return $this->sendSuccess($this->pickup->refresh(), 'Pickup Contacts updated');
    }


    private function validateRequest(): self
    {
        $this->validatedInput = $this->validate($this->request, [ 
               'pickup_id' => 'required|exists:pickups,id',
                'state_id' => 'sometimes|required|exists:states,id',
                'lg_id' => 'sometimes|required|exists:lgs,id',
                'country_id' => 'sometimes|required|exists:countries,id',
                'address' => 'sometimes|required|string|max:255',
                'postal_code' => 'sometimes|required|string|max:255',
                'recipient_first_name' => 'sometimes|required|string|max:255',
                'recipient_last_name' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|string|max:255',
                'alternative_phone' => 'sometimes|required|string|max:255',
        ]);

        return $this;
    }
}
