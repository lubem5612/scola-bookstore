<?php


namespace Transave\ScolaBookstore\Actions;


use RaadaaPartners\RaadaaBase\Helpers\ResponseHelper;
use RaadaaPartners\RaadaaBase\Helpers\ValidationHelper;

class BaseAction
{
    protected array $request = [];
    protected array $validatedData = [];
    protected array $errorBags = [];

    use ResponseHelper, ValidationHelper;

    public function execute()
    {
        try {
            $this->validateInputRequest();
            return $this->handle();
        }catch (\Exception $exception) {
            return $this->sendServerError($exception);
        }
    }

    public function handle()
    {
        return $this;
    }

    public function setValidationRules() : array
    {
        return [

        ];
    }

    public function validateInputRequest()
    {
        $this->validatedData = $this->validate($this->request, $this->setValidationRules());
    }
}