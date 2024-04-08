<?php

namespace Transave\ScolaCbt\Http\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Transave\ScolaBookstore\Helpers\ResponseHelper;

class AllowIfPublisher
{
    use ResponseHelper;
    public function handle(Request $request, Closure $next)
    {
        $user = auth('sanctum')->user();
        if (!empty($user) && in_array($user->role,['super_admin', 'admin', 'author'])) {
            return $next($request);
        }
        return $this->sendError('You are not allowed to perform this operation', [], 401);
    }
}