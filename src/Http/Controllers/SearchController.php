<?php

namespace Transave\ScolaBookstore\Http\Controllers;

use Transave\ScolaBookstore\Actions\Search\SearchCategories;
use Transave\ScolaBookstore\Actions\Search\SearchBanks;
use Transave\ScolaBookstore\Actions\Search\SearchBankDetails;
use Transave\ScolaBookstore\Actions\Search\SearchPublishers;
use Transave\ScolaBookstore\Actions\Search\SearchSaves;
use Transave\ScolaBookstore\Actions\Search\SearchSchools;
use Transave\ScolaBookstore\Http\Models\Category;
use Transave\ScolaBookstore\Http\Models\Author;
use Transave\ScolaBookstore\Http\Models\Bank;
use Transave\ScolaBookstore\Http\Models\BankDetail;
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
        return (new SearchPublishers(Author::class, []))->execute();
    }


    public function indexSchools()
    {
        return (new SearchSchools(School::class, []))->execute();
    }

    public function indexSaves()
    {
        return (new SearchSaves(Save::class, []))->execute();
    }

    public function indexBanks()
    {
        return (new SearchBanks(Bank::class, []))->execute();
    }

    public function indexBankDetails()
    {
        return (new SearchBankDetails(BankDetail::class, ['user', 'bank']))->execute();
    }
}