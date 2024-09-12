<?php

namespace Vncore\Core\Admin\Middleware;

use Closure;

class AdminStoreId
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (admin()->user()) {
            session(['adminStoreId' => VNCORE_ID_ROOT]);
        } else {
            session()->forget('adminStoreId');
        }
        return $next($request);
    }
}
