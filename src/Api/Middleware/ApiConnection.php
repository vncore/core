<?php

namespace Vncore\Core\Api\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiConnection
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!vncore_config('api_connection_required')) {
            return $next($request);
        }
        $apiconnection = $request->header('apiconnection');
        $apikey = $request->header('apikey');
        if (!$apiconnection || !$apikey) {
            return  response()->json(['error' => 1, 'msg' => 'apiconnection or apikey not found']);
        }
        $check = \Vncore\Core\Admin\Models\AdminApiConnection::check($apiconnection, $apikey);
        if ($check) {
            $check->update(['last_active' => vncore_time_now()]);
            return $next($request);
        } else {
            return  response()->json(['error' => 1, 'msg' => 'Connection not correct']);
        }
        return $next($request);
    }
}
