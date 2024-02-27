<?php


namespace Transave\ScolaBookstore\Actions\Page;


use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Http\Models\Author;
use Transave\ScolaBookstore\Http\Models\Resource;

class HomePageService
{
    use ResponseHelper;

    public function execute()
    {
        try {
            return $this->getHomeResources();
        }catch (\Exception $exception) {
            return $this->sendServerError($exception);
        }
    }

    private function getHomeResources()
    {
        $data = [];
        $data['best_selling_resources'] = Resource::query()->with(['author.user'])->withCount(['orders'])
            ->orderBy('orders_count', 'desc')->take(12)->get();

        $data['popular_resources'] = Resource::query()->with(['author.user'])->withCount(['orders'])
            ->orderBy('number_of_views', 'desc')->take(12)->get();

        $data['latest_resources'] = Resource::query()->with(['author.user'])->withCount(['orders'])
            ->orderBy('created_at', 'desc')->take(12)->get();

        $data['top_authors'] = Author::query()->with(['user'])->withCount(['resources'])
            ->orderBy('resources_count', 'desc')->take(12)->get();

        return $this->sendSuccess($data, 'homepage data returned successfully');
    }
}