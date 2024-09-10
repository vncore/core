<?php
namespace Vncore\Core\Admin\Controllers;

use Vncore\Core\Admin\Controllers\RootAdminController;
use Vncore\Core\Admin\Models\AdminLanguage;
use Validator;

class AdminLanguageController extends RootAdminController
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index()
    {
        $data = [
            'title' => vncore_language_render('admin.language.list'),
            'title_action' => '<i class="fa fa-plus" aria-hidden="true"></i> ' . vncore_language_render('admin.language.add_new_title'),
            'subTitle' => '',
            'urlDeleteItem' => vncore_route_admin('admin_language.delete'),
            'removeList' => 0, // 1 - Enable function delete list item
            'buttonRefresh' => 0, // 1 - Enable button refresh
            'url_action' => vncore_route_admin('admin_language.create'),
        ];

        $listTh = [
            'name' => vncore_language_render('admin.language.name'),
            'code' => vncore_language_render('admin.language.code'),
            'icon' => vncore_language_render('admin.language.icon'),
            'rtl' => vncore_language_render('admin.language.layout_rtl'),
            'sort' => vncore_language_render('admin.language.sort'),
            'status' => vncore_language_render('admin.language.status'),
            'action' => vncore_language_render('action.title'),
        ];

        $obj = new AdminLanguage;
        $obj = $obj->orderBy('id', 'desc');
        $dataTmp = $obj->paginate(20);

        $dataTr = [];
        foreach ($dataTmp as $key => $row) {
            $arrAction = [
            '<a href="' . vncore_route_admin('admin_language.edit', ['id' => $row['id'], 'page' => request('page')]) . '"  class="dropdown-item"><i class="fa fa-edit"></i> '.vncore_language_render('action.edit').'</a>',
            ];
            if (!in_array($row['id'], VNCORE_GUARD_LANGUAGE)) {
                $arrAction[] = '<a href="#" onclick="deleteItem(\'' . $row['id'] . '\');"  title="' . vncore_language_render('action.delete') . '" class="dropdown-item"><i class="fas fa-trash-alt"></i> '.vncore_language_render('action.remove').'</a>';
            }

            $action = $this->procesListAction($arrAction);

            $dataTr[$row['id']] = [
                'name' => $row['name'],
                'code' => $row['code'],
                'icon' => vncore_image_render($row['icon'], '30px', '30px', $row['name']),
                'rtl' => $row['rtl'],
                'sort' => $row['sort'],
                'status' => $row['status'] ? '<span class="badge badge-success">ON</span>' : '<span class="badge badge-danger">OFF</span>',
                'action' => $action,
            ];
        }

        $data['listTh'] = $listTh;
        $data['dataTr'] = $dataTr;
        $data['pagination'] = $dataTmp->appends(request()->except(['_token', '_pjax']))->links($this->vncore_templatePathAdmin.'component.pagination');
        $data['resultItems'] = vncore_language_render('admin.result_item', ['item_from' => $dataTmp->firstItem(), 'item_to' => $dataTmp->lastItem(), 'total' =>  $dataTmp->total()]);

        $data['layout'] = 'index';
        return view($this->vncore_templatePathAdmin.'screen.language')
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
            'icon' => 'required',
            'sort' => 'numeric|min:0',
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:10|unique:"'.AdminLanguage::class.'",code',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $dataCreate = [
            'icon' => $data['icon'],
            'name' => $data['name'],
            'code' => $data['code'],
            'rtl' => empty($data['rtl']) ? 0 : 1,
            'status' => empty($data['status']) ? 0 : 1,
            'sort' => (int) $data['sort'],
        ];
        $dataCreate = vncore_clean($dataCreate, [], true);
        $obj = AdminLanguage::create($dataCreate);

        return redirect()->route('admin_language.edit', ['id' => $obj['id']])->with('success', vncore_language_render('action.create_success'));
    }

    /**
     * Form edit
     */
    public function edit($id)
    {
        $language = AdminLanguage::find($id);
        if (!$language) {
            return 'No data';
        }
        $data = [
        'title' => vncore_language_render('admin.language.list'),
        'title_action' => '<i class="fa fa-edit" aria-hidden="true"></i> ' . vncore_language_render('action.edit'),
        'subTitle' => '',
        'icon' => 'fa fa-tasks',
        'urlDeleteItem' => vncore_route_admin('admin_language.delete'),
        'removeList' => 0, // 1 - Enable function delete list item
        'buttonRefresh' => 0, // 1 - Enable button refresh
        'url_action' => vncore_route_admin('admin_language.post_edit', ['id' => $language['id']]),
        'language' => $language,
    ];

        $listTh = [
        'name' => vncore_language_render('admin.language.name'),
        'code' => vncore_language_render('admin.language.code'),
        'icon' => vncore_language_render('admin.language.icon'),
        'rtl' => vncore_language_render('admin.language.layout_rtl'),
        'sort' => vncore_language_render('admin.language.sort'),
        'status' => vncore_language_render('admin.language.status'),
        'action' => vncore_language_render('action.title'),
    ];
        $obj = new AdminLanguage;
        $obj = $obj->orderBy('id', 'desc');
        $dataTmp = $obj->paginate(20);

        $dataTr = [];
        foreach ($dataTmp as $key => $row) {
            $arrAction = [
            '<a href="' . vncore_route_admin('admin_language.edit', ['id' => $row['id'], 'page' => request('page')]) . '"  class="dropdown-item"><i class="fa fa-edit"></i> '.vncore_language_render('action.edit').'</a>',
            ];
            if (!in_array($row['id'], VNCORE_GUARD_LANGUAGE)) {
                $arrAction[] = '<a href="#" onclick="deleteItem(\'' . $row['id'] . '\');"  title="' . vncore_language_render('action.delete') . '" class="dropdown-item"><i class="fas fa-trash-alt"></i> '.vncore_language_render('action.remove').'</a>';
            }

            $action = $this->procesListAction($arrAction);

            $dataTr[$row['id']] = [
            'name' => $row['name'],
            'code' => $row['code'],
            'icon' => vncore_image_render($row['icon'], '30px', '30px', $row['name']),
            'rtl' => $row['rtl'],
            'sort' => $row['sort'],
            'status' => $row['status'] ? '<span class="badge badge-success">ON</span>' : '<span class="badge badge-danger">OFF</span>',
            'action' => $action,
        ];
        }

        $data['listTh'] = $listTh;
        $data['dataTr'] = $dataTr;
        $data['pagination'] = $dataTmp->appends(request()->except(['_token', '_pjax']))->links($this->vncore_templatePathAdmin.'component.pagination');
        $data['resultItems'] = vncore_language_render('admin.result_item', ['item_from' => $dataTmp->firstItem(), 'item_to' => $dataTmp->lastItem(), 'total' =>  $dataTmp->total()]);

        $data['layout'] = 'edit';
        return view($this->vncore_templatePathAdmin.'screen.language')
        ->with($data);
    }

    /**
     * update
     */
    public function postEdit($id)
    {
        $language = AdminLanguage::find($id);
        $data = request()->all();
        $dataOrigin = request()->all();
        $validator = Validator::make($dataOrigin, [
            'icon' => 'required',
            'name' => 'required',
            'sort' => 'numeric|min:0',
            'code' => 'required|string|max:10|unique:"'.AdminLanguage::class.'",code,' . $language->id . ',id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        //Edit

        $dataUpdate = [
            'icon' => $data['icon'],
            'name' => $data['name'],
            'code' => $data['code'],
            'rtl' => empty($data['rtl']) ? 0 : 1,
            'sort' => (int)$data['sort'],
        ];
        //Check status before change, sure have one language is default
        $check = AdminLanguage::where('status', 1)->where('code', '<>', $data['code'])->count();
        if ($check) {
            $dataUpdate['status'] = empty($data['status']) ? 0 : 1;
        } else {
            $dataUpdate['status'] = 1;
        }
        //End check status
        $obj = AdminLanguage::find($id);
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
            $arrID = array_diff($arrID, VNCORE_GUARD_LANGUAGE);
            AdminLanguage::destroy($arrID);
            return response()->json(['error' => 0, 'msg' => vncore_language_render('action.update_success')]);
        }
    }
}
