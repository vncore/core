<?php
namespace Vncore\Core\Admin\Controllers;

use Vncore\Core\Admin\Controllers\RootAdminController;
use Vncore\Core\Admin\Models\AdminHome;
use Validator;
use Illuminate\Support\Facades\View;

class AdminHomeConfigController extends RootAdminController
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index()
    {
        $data = [
            'title' => vncore_language_render('admin.admin_home_config.list'),
            'title_action' => '<i class="fa fa-plus" aria-hidden="true"></i> ' . vncore_language_render('admin.admin_home_config.add_new_title'),
            'subTitle' => '',
            'urlDeleteItem' => vncore_route_admin('admin_home_config.delete'),
            'url_action' => vncore_route_admin('admin_home_config.create'),
        ];

        $listTh = [
            'view' => vncore_language_render('admin.admin_home_config.view'),
            'size' => vncore_language_render('admin.admin_home_config.size'),
            'sort' => vncore_language_render('admin.admin_home_config.sort'),
            'status' => vncore_language_render('admin.admin_home_config.status'),
            'view_status' => vncore_language_render('admin.admin_home_config.view_status'),
            'action' => vncore_language_render('action.title'),
        ];

        $obj = new AdminHome;
        $obj = $obj->orderBy('created_at', 'desc');
        $dataTmp = $obj->paginate(20);

        $dataTr = [];
        foreach ($dataTmp as $key => $row) {
            $arrAction = [
            '<a href="' . vncore_route_admin('admin_home_config.edit', ['id' => $row['id'], 'page' => request('page')]) . '"  class="dropdown-item"><i class="fa fa-edit"></i> '.vncore_language_render('action.edit').'</a>',
            ];
            $arrAction[] = '<a href="#" onclick="deleteItem(\'' . $row['id'] . '\');"  title="' . vncore_language_render('action.delete') . '" class="dropdown-item"><i class="fas fa-trash-alt"></i> '.vncore_language_render('action.remove').'</a>';

            $action = $this->procesListAction($arrAction);
            if (View::exists($row['view'])) {
                $view_status = 1;
            } else {
                $view_status = 0;
            }
            $dataTr[$row['id']] = [
                'view' => $row['view'],
                'size' => $row['size'],
                'sort' => $row['sort'],
                'status' => $row['status'] ? '<span class="badge badge-success">ON</span>' : '<span class="badge badge-danger">OFF</span>',
                'view_status' => $view_status ? '<span class="badge badge-success">OK</span>' : '<span class="badge badge-danger">'.vncore_language_render('admin.data_not_found').'</span>',
                'action' => $action,
            ];
        }

        $data['listTh'] = $listTh;
        $data['dataTr'] = $dataTr;
        $data['listView'] = config('vncore-module.homepage');
        $data['pagination'] = $dataTmp->appends(request()->except(['_token', '_pjax']))->links($this->vncore_templatePathAdmin.'component.pagination');
        $data['resultItems'] = vncore_language_render('admin.result_item', ['item_from' => $dataTmp->firstItem(), 'item_to' => $dataTmp->lastItem(), 'total' =>  $dataTmp->total()]);

        $data['layout'] = 'index';
        return view($this->vncore_templatePathAdmin.'screen.home_config')
            ->with($data);
    }

    /**
     * Post create
     * @return [type] [description]
     */
    public function postCreate()
    {
        $data = request()->all();
        $dataOrigin = request()->all();
        $validator = Validator::make($dataOrigin, [
            'size' => 'numeric|min:1|max:12',
            'sort' => 'numeric|min:0',
            'view' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $viewTmp = explode('::', $data['view']);
        $extension = $viewTmp[0];
        $dataCreate = [
            'view' => $data['view'],
            'size' => $data['size'],
            'extension' => $extension,
            'status' => empty($data['status']) ? 0 : 1,
            'sort' => (int) $data['sort'],
        ];
        $dataCreate = vncore_clean($dataCreate, [], true);
        $obj = AdminHome::create($dataCreate);

        return redirect()->route('admin_home_config.edit', ['id' => $obj['id']])->with('success', vncore_language_render('action.create_success'));
    }

    /**
     * Form edit
     */
    public function edit($id)
    {
        $block = AdminHome::find($id);
        if (!$block) {
            return 'No data';
        }
        $data = [
            'title' => vncore_language_render('admin.admin_home_config.list'),
            'title_action' => '<i class="fa fa-edit" aria-hidden="true"></i> ' . vncore_language_render('action.edit'),
            'subTitle' => '',
            'urlDeleteItem' => vncore_route_admin('admin_home_config.delete'),
            'url_action' => vncore_route_admin('admin_home_config.post_edit', ['id' => $block['id']]),
            'block' => $block,
        ];

        $listTh = [
            'view' => vncore_language_render('admin.admin_home_config.view'),
            'size' => vncore_language_render('admin.admin_home_config.size'),
            'sort' => vncore_language_render('admin.admin_home_config.sort'),
            'status' => vncore_language_render('admin.admin_home_config.status'),
            'view_status' => vncore_language_render('admin.admin_home_config.view_status'),
            'action' => vncore_language_render('action.title'),
        ];
        $obj = new AdminHome;
        $obj = $obj->orderBy('created_at', 'desc');
        $dataTmp = $obj->paginate(20);

        $dataTr = [];
        foreach ($dataTmp as $key => $row) {
            $arrAction = [
            '<a href="' . vncore_route_admin('admin_home_config.edit', ['id' => $row['id'], 'page' => request('page')]) . '"  class="dropdown-item"><i class="fa fa-edit"></i> '.vncore_language_render('action.edit').'</a>',
            ];
            $arrAction[] = '<a href="#" onclick="deleteItem(\'' . $row['id'] . '\');"  title="' . vncore_language_render('action.delete') . '" class="dropdown-item"><i class="fas fa-trash-alt"></i> '.vncore_language_render('action.remove').'</a>';

            $action = $this->procesListAction($arrAction);

            if (View::exists($row['view'])) {
                $view_status = 1;
            } else {
                $view_status = 0;
            }
            $dataTr[$row['id']] = [
                'view' => $row['view'],
                'size' => $row['size'],
                'sort' => $row['sort'],
                'status' => $row['status'] ? '<span class="badge badge-success">ON</span>' : '<span class="badge badge-danger">OFF</span>',
                'view_status' => $view_status ? '<span class="badge badge-success">OK</span>' : '<span class="badge badge-danger">'.vncore_language_render('admin.data_not_found').'</span>',
                'action' => $action,
            ];
        }

        $data['listTh'] = $listTh;
        $data['dataTr'] = $dataTr;
        $data['listView'] = config('vncore-module.homepage');
        $data['pagination'] = $dataTmp->appends(request()->except(['_token', '_pjax']))->links($this->vncore_templatePathAdmin.'component.pagination');
        $data['resultItems'] = vncore_language_render('admin.result_item', ['item_from' => $dataTmp->firstItem(), 'item_to' => $dataTmp->lastItem(), 'total' =>  $dataTmp->total()]);

        $data['layout'] = 'edit';
        return view($this->vncore_templatePathAdmin.'screen.home_config')
        ->with($data);
    }

    /**
     * update
     */
    public function postEdit($id)
    {
        $block = AdminHome::find($id);
        $data = request()->all();
        $dataOrigin = request()->all();
        $validator = Validator::make($dataOrigin, [
            'size' => 'numeric|min:1|max:12',
            'sort' => 'numeric|min:0',
            'view' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        //Edit

        $dataUpdate = [
            'view' => $data['view'],
            'size' => $data['size'],
            'sort' => (int)$data['sort'],
            'status' => empty($data['status']) ? 0 : 1,
        ];

        $obj = AdminHome::find($id);
        $dataUpdate =  vncore_clean($dataUpdate, [], true);
        $obj->update($dataUpdate);

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
            AdminHome::destroy($arrID);
            return response()->json(['error' => 0, 'msg' => '']);
        }
    }
}
