<?php

namespace Transave\ScolaBookstore\Http\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Transave\ScolaBookstore\Helpers\ResponseHelper;

class VerifiedAccount
{
    use ResponseHelper;
    public function handle(Request $request, Closure $next)
    {
        $user = auth('sanctum')->user();
        if (!empty($user) && $user->is_verified) {
            return $next($request);
        }
        return $this->sendError('you must verify your account to perform this operation', [], 401);
    }
}