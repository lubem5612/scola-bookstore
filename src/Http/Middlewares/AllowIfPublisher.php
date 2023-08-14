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
        $user = $request->user();
        if (!empty($user) && in_array($user->role,['superAdmin', 'admin', 'publisher'])) {
            return $next($request);
        }
        return $this->sendError('You are not an Admin.', [], 401);
    }
}