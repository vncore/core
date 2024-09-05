<?php

namespace Vncore\Core\Api\Controllers;

use Vncore\Core\Admin\Controllers\RootAdminController;
use Illuminate\Http\Request;

class AdminController extends RootAdminController
{

    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function getInfo(Request $request)
    {
        return response()->json($request->user());
    }
}
