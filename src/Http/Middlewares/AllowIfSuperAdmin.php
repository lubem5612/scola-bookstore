<?php

namespace Transave\ScolaBookstore\Http\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Transave\ScolaBookstore\Helpers\ResponseHelper;

class AllowIfSuperAdmin
{
    use ResponseHelper;
    public function handle(Request $request, Closure $next)
    {
        $user = auth('sanctum')->user();
        if (!empty($user) && $user->role == 'super_admin') {
            return $next($request);
        }
        return $this->sendError('you are not an admin to perform this operation', [], 401);
    }
}