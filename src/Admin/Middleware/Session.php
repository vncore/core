<?php

namespace Vncore\Core\Admin\Middleware;

use Illuminate\Http\Request;

class Session
{
    public function handle(Request $request, \Closure $next)
    {
        $path = '/' . trim(VNCORE_ADMIN_PREFIX, '/');

        config(['session.path' => $path]);

        if ($domain = config('vncore-config.admin.route.domain')) {
            config(['session.domain' => $domain]);
        }

        return $next($request);
    }
}
