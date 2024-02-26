<?php


namespace Transave\ScolaBookstore\Http\Controllers;


use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Http\Models\Resource;

class PageController extends Controller
{
    use ResponseHelper;

    public function __construct()
    {

    }

    public function homePage()
    {
        $data = [];
        $data['best_selling_resources'] = Resource::query()->withCount(['author.user', 'orderItems.order'])->get();

        return $this->sendSuccess($data, 'homepage data returned successfully');
    }
}