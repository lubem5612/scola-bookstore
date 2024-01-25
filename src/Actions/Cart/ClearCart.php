<?php

namespace Transave\ScolaBookstore\Actions\Cart;

use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Helpers\ValidationHelper;
use Transave\ScolaBookstore\Http\Models\Cart;
use Transave\ScolaBookstore\Http\Models\User;
use Illuminate\Support\Facades\Config;


class ClearCart
{
    use ResponseHelper, ValidationHelper;


  public function execute($userId)
    {
        $user = Config::get('scola-bookstore.auth_model')::query()->find($userId);
        $user->cart()->delete();

        return $this->sendSuccess(null, 'Cart Cleared successfully');
    }
}
