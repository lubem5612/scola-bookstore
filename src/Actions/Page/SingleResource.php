<?php


namespace Transave\ScolaBookstore\Actions\Page;


use Illuminate\Database\Eloquent\Builder;
use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\Resource;

class SingleResource
{
    use ValidationHelper, ResponseHelper;
    private $request, $validatedData;
    private ?Resource $resource;

    public function __construct(array $request)
    {
        $this->request = $request;
    }

    public function execute()
    {
        try {
            $this->validateRequest();
            $this->getResource();
            return $this->similarResources();
        }catch (\Exception $exception) {
            return $this->sendServerError($exception);
        }
    }

    private function similarResources()
    {
        $resources = [];
        $keywordList = explode(' ', $this->resource->keywords);
        $keywordList = str_replace(',', '', $keywordList);

        if (is_array($keywordList) && count($keywordList) > 0) {
            foreach ($keywordList as $keyword) {
                $list = Resource::query()
                    ->where('id', '!=', $this->resource->id)
                    ->where(function(Builder $builder) use ($keyword) {
                        $builder->where('title', 'like', "%$keyword%")
                            ->orWhere('subtitle', 'like', "%$keyword%")
                            ->orWhere('abstract', 'like', "%$keyword%")
                            ->orWhere('content', 'like', "%$keyword%")
                            ->orWhere('summary', 'like', "%$keyword%")
                            ->orWhere('overview', 'like', "%$keyword%");
                    })->get()->toArray();

                $resources = array_merge($resources, $list);
            }
        }

        if (count($resources) < 10) {
            $available = Resource::query()->inRandomOrder()->take(10)->get();
            $resources = array_merge($resources, $available->toArray());
        }

        $result['resource'] = $this->resource;
        $result['similar_resources'] = collect(array_slice($resources, 0, 10));
        return $this->sendSuccess($result, 'resource data fetched successfully');
    }

    private function getResource()
    {
        $this->resource = Resource::query()->with(['author.user'])->find($this->validatedData['resource_id']);
        $this->resource->update([
            'number_of_views' => (int)$this->resource->number_of_views + 1,
        ]);
    }

    private function validateRequest()
    {
        $this->validatedData = $this->validate($this->request, [
            'resource_id' => 'required|exists:resources,id'
        ]);
    }
}