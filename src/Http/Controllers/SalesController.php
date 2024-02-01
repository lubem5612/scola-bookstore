<?php

namespace Transave\ScolaBookstore\Http\Controllers;

use Illuminate\Http\Request;
use Transave\ScolaBookstore\Actions\Sales\SearchSales;
use Transave\ScolaBookstore\Actions\Sales\UserPurchases;
use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Http\Models\Sale;

class SalesController extends Controller
{
    use ResponseHelper;


    public function index()
    {
        return (new SearchSales(Sale::class, ['user', 'order', 'book', 'report', 'journal', 'festchrisft', 'conference_paper', 'research_resource', 'monograph', 'article']))->execute();
    }


    public function show($id)
    {
        return (new SearchSales(Sale::class, ['user', 'order', 'book', 'report', 'journal', 'festchrisft', 'conference_paper', 'research_resource', 'monograph', 'article'], $id))->execute();
    }


        public function userPurchase($id)
    {
        $request = ['user_id' => $id];
        $action = new UserPurchases($request);
        return $action->execute();
    }

}