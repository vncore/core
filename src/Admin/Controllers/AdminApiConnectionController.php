<?php
namespace Vncore\Core\Admin\Controllers;

use Vncore\Core\Admin\Controllers\RootAdminController;
use Vncore\Core\Admin\Models\AdminApiConnection;
use Validator;

class AdminApiConnectionController extends RootAdminController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        $data = [
            'title' => vncore_language_render('admin.api_connection.list'),
            'title_action' => '<i class="fa fa-plus" aria-hidden="true"></i> ' . vncore_language_render('admin.api_connection.create'),
            'subTitle' => '',
            'urlDeleteItem' => vncore_route_admin('admin_api_connection.delete'),
            'url_action' => vncore_route_admin('admin_api_connection.create'),
            'layout' => 'index',
        ];

        $listTh = [
            'id' => 'ID',
            'description' => vncore_language_render('admin.api_connection.description'),
            'apiconnection' => vncore_language_render('admin.api_connection.connection'),
            'apikey' => vncore_language_render('admin.api_connection.apikey'),
            'expire' => vncore_language_render('admin.api_connection.expire'),
            'last_active' => vncore_language_render('admin.api_connection.last_active'),
            'status' => vncore_language_render('admin.api_connection.status'),
            'action' => vncore_language_render('action.title'),
        ];

        $obj = new AdminApiConnection;
        $obj = $obj->orderBy('id', 'desc');
        $dataTmp = $obj->paginate(20);

        $routes = app()->routes->getRoutes();
        $listApi = [];
        foreach ($routes as $route) {
            if (\Str::startsWith($route->uri(), 'api')) {
                $listApi[] = $route->uri();
            }
        }

        $dataTr = [];
        foreach ($dataTmp as $key => $row) {
            $arrAction = [
            '<a href="' . vncore_route_admin('admin_api_connection.edit', ['id' => $row['id'], 'page' => request('page')]) . '"  class="dropdown-item"><i class="fa fa-edit"></i> '.vncore_language_render('action.edit').'</a>',
            '<a href="#" onclick="deleteItem(\'' . $row['id'] . '\');"  title="' . vncore_language_render('action.delete') . '" class="dropdown-item"><i class="fas fa-trash-alt"></i> '.vncore_language_render('action.remove').'</a>',
            ];
            $action = $this->procesListAction($arrAction);

            $dataTr[$row['id']] = [
                'id' => $row['id'],
                'description' => $row['description'],
                'apiconnection' => $row['apiconnection'],
                'apikey' => $row['apikey'],
                'expire' => $row['expire'],
                'last_active' => $row['last_active'],
                'status' => $row['status'] ? '<span class="badge badge-success">ON</span>' : '<span class="badge badge-danger">OFF</span>',
                'action' => $action,
            ];
        }

        $data['listTh'] = $listTh;
        $data['dataTr'] = $dataTr;
        $data['listApi'] = $listApi;
        $data['pagination'] = $dataTmp->appends(request()->except(['_token', '_pjax']))->links('vncore-admin::component.pagination');
        $data['resultItems'] = vncore_language_render('admin.result_item', ['item_from' => $dataTmp->firstItem(), 'item_to' => $dataTmp->lastItem(), 'total' =>  $dataTmp->total()]);
    
        return view('vncore-admin::screen.api_connection')
            ->with($data);
    }

    /**
     * Post create new item in admin
     * @return [type] [description]
     */
    public function postCreate()
    {
        $data = request()->all();
        $dataOrigin = request()->all();
        $validator = Validator::make($dataOrigin, [
            'description' => 'string|required',
            'apiconnection' => 'string|required|regex:/(^([0-9a-z\-_]+)$)/|unique:"'.AdminApiConnection::class.'",apiconnection',
            'apikey' => 'string|regex:/(^([0-9a-z\-_]+)$)/',
        ], [
            'apiconnection.regex' => vncore_language_render('admin.api_connection.validate_regex'),
            'apikey.regex' => vncore_language_render('admin.api_connection.validate_regex'),
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $dataCreate = [
            'description' => $data['description'],
            'apiconnection' => $data['apiconnection'],
            'apikey' => $data['apikey'],
            'expire' => $data['expire'],
            'status' => empty($data['status']) ? 0 : 1,
        ];
        $dataCreate = vncore_clean($dataCreate, [], true);
        AdminApiConnection::create($dataCreate);

        return redirect()->route('admin_api_connection.index')->with('success', vncore_language_render('action.create_success'));
    }

    /**
     * Form edit
     */

    public function edit($id)
    {
        $api_connection = AdminApiConnection::find($id);
        if ($api_connection === null) {
            return vncore_language_render('admin.data_not_found_detail');
        }
        $data = [
        'title' => vncore_language_render('admin.api_connection.list'),
        'title_action' => '<i class="fa fa-edit" aria-hidden="true"></i> ' . vncore_language_render('admin.api_connection.edit'),
        'subTitle' => '',
        'urlDeleteItem' => vncore_route_admin('admin_api_connection.delete'),
        'api_connection' => $api_connection,
        'url_action' => vncore_route_admin('admin_api_connection.edit', ['id' => $api_connection['id']]),
        'layout' => 'edit',
        'id' => $id,
    ];

        $listTh = [
        'id' => 'ID',
        'description' => vncore_language_render('admin.api_connection.description'),
        'apiconnection' => vncore_language_render('admin.api_connection.apikey'),
        'apikey' => vncore_language_render('admin.api_connection.apikey'),
        'expire' => vncore_language_render('admin.api_connection.expire'),
        'last_active' => vncore_language_render('admin.api_connection.last_active'),
        'status' => vncore_language_render('admin.api_connection.status'),
        'action' => vncore_language_render('action.title'),
    ];

        $obj = new AdminApiConnection;
        $obj = $obj->orderBy('id', 'desc');
        $dataTmp = $obj->paginate(20);

        $dataTr = [];
        foreach ($dataTmp as $key => $row) {
            $arrAction = [
            '<a href="' . vncore_route_admin('admin_api_connection.edit', ['id' => $row['id'], 'page' => request('page')]) . '"  class="dropdown-item"><i class="fa fa-edit"></i> '.vncore_language_render('action.edit').'</a>',
            '<a href="#" onclick="deleteItem(\'' . $row['id'] . '\');"  title="' . vncore_language_render('action.delete') . '" class="dropdown-item"><i class="fas fa-trash-alt"></i> '.vncore_language_render('action.remove').'</a>',
            ];
            $action = $this->procesListAction($arrAction);

            $dataTr[$row['id']] = [
                'id' => $row['id'],
                'description' => $row['description'],
                'apiconnection' => $row['apiconnection'],
                'apikey' => $row['apikey'],
                'expire' => $row['expire'],
                'last_active' => $row['last_active'],
                'status' => $row['status'] ? '<span class="badge badge-success">ON</span>' : '<span class="badge badge-danger">OFF</span>',
                'action' => $action,
            ];
        }

        $data['listTh'] = $listTh;
        $data['dataTr'] = $dataTr;
        $data['pagination'] = $dataTmp->appends(request()->except(['_token', '_pjax']))->links('vncore-admin::component.pagination');
        $data['resultItems'] = vncore_language_render('admin.result_item', ['item_from' => $dataTmp->firstItem(), 'item_to' => $dataTmp->lastItem(), 'total' =>  $dataTmp->total()]);
    
        $data['rightContentMain'] = '<input class="switch-data-config" data-store=0 name="api_connection_required" type="checkbox"  '.(vncore_config_global('api_connection_required')?'checked':'').'><br> '.vncore_language_render('admin.api_connection.api_connection_required_help');

        $optionSort = '';
        $data['optionSort'] = $optionSort;
        return view('vncore-admin::screen.api_connection')
        ->with($data);
    }


    /**
     * update status
     */
    public function postEdit($id)
    {
        $data = request()->all();
        $dataOrigin = request()->all();
        $obj = AdminApiConnection::find($id);
        $validator = Validator::make($dataOrigin, [
            'description' => 'string|required',
            'apiconnection' => 'string|required|regex:/(^([0-9a-z\-_]+)$)/|unique:"'.AdminApiConnection::class.'",apiconnection,' . $obj->id . ',id',
            'apikey' => 'string|regex:/(^([0-9a-z\-_]+)$)/',
        ], [
            'apiconnection.regex' => vncore_language_render('admin.api_connection.validate_regex'),
            'apikey.regex' => vncore_language_render('admin.api_connection.validate_regex'),
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        //Edit

        $dataUpdate = [
            'description' => $data['description'],
            'apiconnection' => $data['apiconnection'],
            'apikey' => $data['apikey'],
            'expire' => $data['expire'],
            'status' => empty($data['status']) ? 0 : 1,
        ];
        $dataUpdate = vncore_clean($dataUpdate, [], true);
        $obj->update($dataUpdate);

//
        return redirect()->back()->with('success', vncore_language_render('action.edit_success'));
    }

    /*
    Delete list item
    Need mothod destroy to boot deleting in model
     */
    public function deleteList()
    {
        if (!request()->ajax()) {
            return response()->json(['error' => 1, 'msg' => vncore_language_render('admin.method_not_allow')]);
        } else {
            $ids = request('ids');
            $arrID = explode(',', $ids);
            AdminApiConnection::destroy($arrID);
            return response()->json(['error' => 0, 'msg' => vncore_language_render('action.update_success')]);
        }
    }

    public function generateKey()
    {
        return response()->json(['data' => md5(time())]);
    }
}
