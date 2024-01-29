<?php

namespace Transave\ScolaBookstore\Actions\Monographs;

use Transave\ScolaBookstore\Events\MonographViewed;
use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\Monograph;

class GetMonograph
{
    use ValidationHelper, ResponseHelper;

    private array $request;
    private array $validatedInput;
    private Monograph $monograph;

    public function __construct(array $request)
    {
        $this->request = $request;
    }


    public function execute()
    {
        try {
            return $this
                ->validateRequest()
                ->getMonograph()
                ->sendSuccess($this->monograph, 'Monograph fetched successfully');
        }catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }
    private function getMonograph()
    {
        $this->monograph = Monograph::query()->with(['user', 'category', 'publisher'])->find($this->request['id']);
        return $this;
    }

    private function validateRequest(): self
    {
        $id = $this->validate($this->request, [
            'id' => 'required|exists:monographs,id'
        ]);
        $this->validatedInput = $id;
        return $this;
    }
}