<?php

namespace Transave\ScolaBookstore\Http\Controllers;

use Transave\ScolaBookstore\Actions\Search\SearchCarts;
use Transave\ScolaBookstore\Actions\Search\SearchCategories;
use Transave\ScolaBookstore\Actions\Search\SearchOrderDetails;
use Transave\ScolaBookstore\Actions\Search\SearchPublishers;
use Transave\ScolaBookstore\Actions\Search\SearchSaves;
use Transave\ScolaBookstore\Actions\Search\SearchSchools;
use Transave\ScolaBookstore\Http\Models\Cart;
use Transave\ScolaBookstore\Http\Models\Category;
use Transave\ScolaBookstore\Http\Models\OrderDetail;
use Transave\ScolaBookstore\Http\Models\Publisher;
use Transave\ScolaBookstore\Http\Models\Save;
use Transave\ScolaBookstore\Http\Models\School;

class SearchController extends Controller
{
    public function __construct()
    {

    }

    public function indexCategories()
    {
        return (new SearchCategories(Category::class, []))->execute();
    }


    public function indexPublishers()
    {
        return (new SearchPublishers(Publisher::class, []))->execute();
    }

    public function indexCarts()
    {
        return (new SearchCarts(Cart::class, []))->execute();
    }

    public function indexSchools()
    {
        return (new SearchSchools(School::class, []))->execute();
    }

    public function indexSaves()
    {
        return (new SearchSaves(Save::class, []))->execute();
    }

    public function indexOrderDetails()
    {
        return (new SearchOrderDetails(OrderDetail::class, []))->execute();
    }
}