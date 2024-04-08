<?php

namespace Transave\ScolaBookstore\Http\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Transave\ScolaBookstore\Helpers\ResponseHelper;

class AllowIfUser
{
    use ResponseHelper;
    public function handle(Request $request, Closure $next)
    {
        $user = auth('sanctum')->user();
        if (!empty($user) && in_array($user->role,['super_admin', 'admin', 'author', 'user', 'reviewer'])) {
            return $next($request);
        }
        return $this->sendError('You are not registered.', [], 401);
    }
}