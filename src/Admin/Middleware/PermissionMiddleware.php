<?php

namespace Vncore\Core\Admin\Middleware;

use Vncore\Core\Admin\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param array                    $args
     *
     * @return mixed
     */
    public function handle(Request $request, \Closure $next, ...$args)
    {
        if (!empty($args) || (admin()->user() && admin()->user()->isAdministrator())) {
            return $next($request);
        }

        //Group view all
        // this group can view all path, but cannot change data
        // This condition need add before check permissions below
        if (admin()->user() && admin()->user()->isViewAll()) {
            if ($request->method() == 'GET'
                && !collect($this->viewWithoutToMessage())->contains($request->path())
                && !collect($this->viewWithout())->contains($request->path())
            ) {
                return $next($request);
            } else {
                if (!request()->ajax()) {
                    if (collect($this->viewWithoutToMessage())->contains($request->path())) {
                        return redirect()->route('admin.deny_single')->with(['url' => $request->url(), 'method' => $request->method()]);
                    }
                    return redirect()->route('admin.deny')->with(['url' => $request->url(), 'method' => $request->method()]);
                } else {
                    if (collect($this->viewWithoutToMessage())->contains($request->path())) {
                        return redirect()->route('admin.deny_single')->with(['url' => $request->url(), 'method' => $request->method()]);
                    }
                    return Permission::error();
                }
            }
        }


        // Allow access route
        if ($this->routeDefaultPass($request)) {
            return $next($request);
        }

        // Allow access path
        if ($this->pathDefaultPass($request)) {
            return $next($request);
        }

        // Allow notice
        if (Str::startsWith($request->route()->getName(), 'admin_notice.')) {
            return $next($request);
        }

        if (admin()->user() && !admin()->user()->allPermissions()->first(function ($modelPermission) use ($request) {
            return $modelPermission->passRequest($request);
        })) {
            if (!request()->ajax()) {
                if (request()->route()->getName() == 'admin.home') {
                    return redirect()->route('admin.default')->with(['title' => vncore_language_render('admin.home')]);
                }
                return redirect()->route('admin.deny')->with(['url' => $request->url(), 'method' => $request->method()]);
            } else {
                return Permission::error();
            }
        }
        return $next($request);
    }

    /**
     * Determine if the request has a URI that should pass through verification.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return bool
     */
    protected function pathDefaultPass($request)
    {
        $routePath = $request->path();
        $exceptsPAth = Permission::listPathDefaultPassThrough();
        return in_array($routePath, $exceptsPAth);
    }

    /*
    Check route defualt allow access
    */
    public function routeDefaultPass($request)
    {
        $routeName = $request->route()->getName();
        $allowRoute = Permission::listRouteDefaultPassThrough();
        return in_array($routeName, $allowRoute);
    }

    public function viewWithout()
    {
        return [
            // Array item in here
        ];
    }

    /**
     * Send page deny as meeasge
     *
     * @return  [type]  [return description]
     */
    public function viewWithoutToMessage()
    {
        return [
            VNCORE_ADMIN_PREFIX . '/uploads/delete',
            VNCORE_ADMIN_PREFIX . '/uploads/newfolder',
            VNCORE_ADMIN_PREFIX . '/uploads/domove',
            VNCORE_ADMIN_PREFIX . '/uploads/rename',
            VNCORE_ADMIN_PREFIX . '/uploads/resize',
            VNCORE_ADMIN_PREFIX . '/uploads/doresize',
            VNCORE_ADMIN_PREFIX . '/uploads/cropimage',
            VNCORE_ADMIN_PREFIX . '/uploads/crop',
            VNCORE_ADMIN_PREFIX . '/uploads/move',
        ];
    }
}
