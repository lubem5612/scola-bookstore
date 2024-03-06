<?php


namespace Transave\ScolaBookstore\Http\Controllers;


use Transave\ScolaBookstore\Actions\Page\HomePageService;
use Transave\ScolaBookstore\Actions\Page\SingleAuthor;
use Transave\ScolaBookstore\Actions\Page\SingleResource;
use Transave\ScolaBookstore\Helpers\ResponseHelper;

class PageController extends Controller
{
    use ResponseHelper;

    public function __construct()
    {

    }

    public function homePage()
    {
       return (new HomePageService())->execute();
    }

    public function singleResource($id)
    {
        return (new SingleResource(['resource_id' => $id]))->execute();
    }

    public function singleAuthor($id)
    {
        return (new SingleAuthor(['author_id' => $id]))->execute();
    }
}