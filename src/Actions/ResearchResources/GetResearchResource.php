<?php

namespace Transave\ScolaBookstore\Actions\ResearchResources;

use Transave\ScolaBookstore\Events\ResearchResourceViewed;
use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\ResearchResource;


class GetResearchResource
{
    use ValidationHelper, ResponseHelper;

    private array $request;
    private array $validatedInput;
    private ResearchResource $researchResource;

    public function __construct(array $request)
    {
        $this->request = $request;
    }


    public function execute()
    {
        try {
            return $this
                ->validateRequest()
                ->getResearchResource()
                ->sendSuccess($this->researchResource, 'Research Resource fetched successfully');
        }catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }
    private function getResearchResource()
    {
        $this->researchResource = ResearchResource::query()->with(['user', 'category', 'publisher'])->find($this->request['id']);
        return $this;
    }

    private function validateRequest(): self
    {
        $id = $this->validate($this->request, [
            'id' => 'required|exists:research_resources,id'
        ]);
        $this->validatedInput = $id;
        return $this;
    }
}