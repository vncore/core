<?php

namespace Vncore\Core\Admin\Middleware;

use Illuminate\Http\Request;

class Session
{
    public function handle(Request $request, \Closure $next)
    {
        $path = '/' . trim(VNCORE_ADMIN_PREFIX, '/');

        config(['session.path' => $path]);

        return $next($request);
    }
}
