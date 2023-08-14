<?php

namespace Transave\ScolaCbt\Http\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Transave\ScolaBookstore\Helpers\ResponseHelper;

class AllowIfSuperAdmin
{
    use ResponseHelper;
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if (!empty($user) && $user->role == 'superAdmin') {
            return $next($request);
        }
        return $this->sendError('you are not an admin to perform this operation', [], 401);
    }
}