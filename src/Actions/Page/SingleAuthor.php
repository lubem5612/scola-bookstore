<?php


namespace Transave\ScolaBookstore\Actions\Page;


use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\Author;
use Transave\ScolaBookstore\Http\Models\Resource;

class SingleAuthor
{
    use ValidationHelper, ResponseHelper;
    private $request, $validatedData, $outputData;

    public function __construct(array $request)
    {
        $this->request = $request;
    }

    public function execute()
    {
        try {
            $this->validateRequest();
            $this->setAuthor();
            $this->getAuthorResources();
            return $this->sendSuccess($this->outputData, 'author details retrieved successfully');
        }catch (\Exception $exception) {
            return $this->sendServerError($exception);
        }
    }

    private function setAuthor()
    {
        $this->outputData['author'] = Author::query()->with(['user'])->find($this->validatedData['author_id']);
        $this->outputData['total_resources'] = Resource::query()
            ->where('author_id', $this->validatedData['author_id'])
            ->count();
    }

    private function getAuthorResources()
    {
        $this->outputData['resources'] = Resource::query()
            ->where('author_id', $this->validatedData['author_id'])->orderBy('number_of_views', 'desc')
            ->take(10)->get()->toArray();
    }

    private function validateRequest()
    {
        $this->validatedData = $this->validate($this->request, [
            'author_id' => 'required|exists:authors,id'
        ]);
    }
}