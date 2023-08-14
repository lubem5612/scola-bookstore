<?php

namespace Transave\ScolaBookstore\Facades;

use Illuminate\Support\Facades\Facade;

class ScolaBookstore extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'scola-bookstore';
    }
}