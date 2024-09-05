<?php

namespace Vncore\Core\Admin\Controllers;

use Vncore\Core\Admin\Controllers\RootAdminController;
use Illuminate\Http\Request;
use Vncore\Core\Admin\Models\AdminHome;

class HomeController extends RootAdminController
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index(Request $request)
    {
        $blockDashboard = AdminHome::getBlockHome();
        $data                   = [];
        $data['blockDashboard'] = $blockDashboard;
        $data['title']          = vncore_language_render('admin.home');
        return view($this->vncore_templatePathAdmin.'home', $data);
    }

    public function default()
    {
        $data['title'] = vncore_language_render('admin.home');
        return view($this->vncore_templatePathAdmin.'default', $data);
    }

    /**
     * Page not found
     *
     * @return  [type]  [return description]
     */
    public function dataNotFound()
    {
        $data = [
            'title' => vncore_language_render('admin.data_not_found'),
            'url' => session('url'),
        ];
        return view($this->vncore_templatePathAdmin.'data_not_found', $data);
    }


    /**
     * Page deny
     *
     * @return  [type]  [return description]
     */
    public function deny()
    {
        $data = [
            'title' => vncore_language_render('admin.deny'),
            'method' => session('method'),
            'url' => session('url'),
        ];
        return view($this->vncore_templatePathAdmin.'deny', $data);
    }

    /**
     * [denySingle description]
     *
     * @return  [type]  [return description]
     */
    public function denySingle()
    {
        $data = [
            'method' => session('method'),
            'url' => session('url'),
        ];
        return view($this->vncore_templatePathAdmin.'deny_single', $data);
    }
}
