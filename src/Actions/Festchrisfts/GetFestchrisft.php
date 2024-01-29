<?php

namespace Transave\ScolaBookstore\Actions\Festchrisfts;

use Transave\ScolaBookstore\Events\FestchrisftViewed;
use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\Festchrisft;


class GetFestchrisft
{
    use ValidationHelper, ResponseHelper;

    private array $request;
    private array $validatedInput;
    private Festchrisft $festchrisft;

    public function __construct(array $request)
    {
        $this->request = $request;
    }


    public function execute()
    {
        try {
            return $this
                ->validateRequest()
                ->setFestchrisft()
                ->sendSuccess($this->festchrisft, 'Festchrisft fetched successfully');
        }catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }
    private function setFestchrisft()
    {
        $this->festchrisft = Festchrisft::query()->with(['user', 'category', 'publisher'])->find($this->request['id']);
        return $this;
    }

    private function validateRequest(): self
    {
        $id = $this->validate($this->request, [
            'id' => 'required|exists:festchrisfts,id'
        ]);
        $this->validatedInput = $id;
        return $this;
    }
}