<?php

namespace Transave\ScolaBookstore\Actions\PickupDetails;

use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\PickupDetail;

class UpdateDetails
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
                ->updatePickup()
                ->handleSuccess();
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    private function findPickup(): self
    {
        $this->pickup = PickupDetail::find($this->validatedInput['pickup_id']);

        if (!$this->pickup) {
            $this->sendError('Pickup not found', [], 404);
        }

        return $this;
    }

    private function updatePickup(): self
    {
        $this->pickup->update([
            'address' => $this->validatedInput['address'],
            'country_id' => $this->validatedInput['country_id'],
            'state_id' => $this->validatedInput['state_id'],
            'lg_id' => $this->validatedInput['lg_id'],
            'postal_code' => $this->validatedInput['postal_code'],
            'recipient_first_name' => $this->validatedInput['recipient_first_name'],
            'recipient_last_name' => $this->validatedInput['recipient_last_name'],
            'email' => $this->validatedInput['email'],
            'alternative_phone' => $this->validatedInput['alternative_phone'],
        ]);

        return $this;
    }

    private function handleSuccess()
    {
        return $this->sendSuccess('Pickup updated successfully.');
    }

    private function handleError(\Exception $e)
    {
        return $this->sendError($e->getMessage());
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
