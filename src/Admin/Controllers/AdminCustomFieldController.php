<?php
namespace Vncore\Core\Admin\Controllers;

use Vncore\Core\Admin\Controllers\RootAdminController;
use Vncore\Core\Admin\Models\AdminCustomField;
use Validator;

class AdminCustomFieldController extends RootAdminController
{

    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        $data = [
            'title' => vncore_language_render('admin.custom_field.list'),
            'title_action' => '<i class="fa fa-plus" aria-hidden="true"></i> ' . vncore_language_render('admin.custom_field.add_new_title'),
            'subTitle' => '',
            'urlDeleteItem' => vncore_route_admin('admin_custom_field.delete'),
            'removeList' => 0, // 1 - Enable function delete list item
            'buttonRefresh' => 0, // 1 - Enable button refresh
            'url_action' => vncore_route_admin('admin_custom_field.create'),
        ];

        $listTh = [
            'type' => vncore_language_render('admin.custom_field.type'),
            'code' => vncore_language_render('admin.custom_field.code'),
            'name' => vncore_language_render('admin.custom_field.name'),
            'required' => vncore_language_render('admin.custom_field.required'),
            'option' => vncore_language_render('admin.custom_field.option'),
            'default' => vncore_language_render('admin.custom_field.default'),
            'status' => vncore_language_render('admin.custom_field.status'),
            'action' => vncore_language_render('action.title'),
        ];
        $obj = new AdminCustomField;
        $obj = $obj->orderBy('id', 'desc');
        $dataTmp = $obj->paginate(20);

        $dataTr = [];
        foreach ($dataTmp as $key => $row) {
            $arrAction = [
            '<a href="' . vncore_route_admin('admin_custom_field.edit', ['id' => $row['id'], 'page' => request('page')]) . '"  class="dropdown-item"><i class="fa fa-edit"></i> '.vncore_language_render('action.edit').'</a>',
            '<a href="#" onclick="deleteItem(\'' . $row['id'] . '\');"  title="' . vncore_language_render('action.delete') . '" class="dropdown-item"><i class="fas fa-trash-alt"></i> '.vncore_language_render('action.remove').'</a>',
            ];
            $action = $this->procesListAction($arrAction);

            $dataTr[$row['id']] = [
                'type' => $this->fieldTypes()[$row['type']] ?? $row['type'],
                'code' => $row['code'],
                'name' => $row['name'],
                'required' => $row['required'],
                'option' => $row['option'],
                'default' => $row['default'],
                'status' => $row['status'] ? '<span class="badge badge-success">ON</span>' : '<span class="badge badge-danger">OFF</span>',
                'action' => $action,
            ];
        }

        $data['listTh'] = $listTh;
        $data['dataTr'] = $dataTr;
        $data['pagination'] = $dataTmp->appends(request()->except(['_token', '_pjax']))->links($this->vncore_templatePathAdmin.'component.pagination');
        $data['resultItems'] = vncore_language_render('admin.result_item', ['item_from' => $dataTmp->firstItem(), 'item_to' => $dataTmp->lastItem(), 'total' =>  $dataTmp->total()]);

        $data['layout'] = 'index';
        $data['fieldTypes'] = $this->fieldTypes();
        $data['selectTypes'] = $this->selectTypes();
        return view($this->vncore_templatePathAdmin.'screen.custom_field')
            ->with($data);
    }


    /**
     * Post create new item in admin
     * @return [type] [description]
     */
    public function postCreate()
    {
        $data = request()->all();

        $validator = Validator::make($data, [
            'type' => 'required|string|max:100',
            'code' => 'required|string|max:100',
            'name' => 'required|string|max:250',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($data);
        }
        $dataCreate = [
            'type' => $data['type'],
            'name' => $data['name'],
            'code' => $data['code'],
            'option' => $data['option'],
            'default' => $data['default'],
            'required' => (!empty($data['required']) ? 1 : 0),
            'status' => (!empty($data['status']) ? 1 : 0),
        ];
        $dataCreate = vncore_clean($dataCreate, ['default'], true);
        AdminCustomField::create($dataCreate);

        return redirect()->route('admin_custom_field.index')->with('success', vncore_language_render('action.create_success'));
    }

    /**
     * Form edit
     */
    public function edit($id)
    {
        $customField = AdminCustomField::find($id);
        if (!$customField) {
            return 'No data';
        }
        $data = [
            'title' => vncore_language_render('admin.custom_field.list'),
            'title_action' => '<i class="fa fa-edit" aria-hidden="true"></i> ' . vncore_language_render('action.edit'),
            'subTitle' => '',
            'urlDeleteItem' => vncore_route_admin('admin_custom_field.delete'),
            'removeList' => 0, // 1 - Enable function delete list item
            'buttonRefresh' => 0, // 1 - Enable button refresh
            'buttonSort' => 0, // 1 - Enable button sort
            'url_action' => vncore_route_admin('admin_custom_field.post_edit', ['id' => $customField['id']]),
            'customField' => $customField,
            'id' => $id,
        ];

        $listTh = [
            'type' => vncore_language_render('admin.custom_field.type'),
            'code' => vncore_language_render('admin.custom_field.code'),
            'name' => vncore_language_render('admin.custom_field.name'),
            'required' => vncore_language_render('admin.custom_field.required'),
            'option' => vncore_language_render('admin.custom_field.option'),
            'default' => vncore_language_render('admin.custom_field.default'),
            'status' => vncore_language_render('admin.custom_field.status'),
            'action' => vncore_language_render('action.title'),
        ];
        $obj = new AdminCustomField;
        $obj = $obj->orderBy('id', 'desc');
        $dataTmp = $obj->paginate(20);

        $dataTr = [];
        foreach ($dataTmp as $key => $row) {
            $arrAction = [
            '<a href="' . vncore_route_admin('admin_custom_field.edit', ['id' => $row['id'], 'page' => request('page')]) . '"  class="dropdown-item"><i class="fa fa-edit"></i> '.vncore_language_render('action.edit').'</a>',
            '<a href="#" onclick="deleteItem(\'' . $row['id'] . '\');"  title="' . vncore_language_render('action.delete') . '" class="dropdown-item"><i class="fas fa-trash-alt"></i> '.vncore_language_render('action.remove').'</a>',
            ];
            $action = $this->procesListAction($arrAction);

            $dataTr[$row['id']] = [
                'type' => $this->fieldTypes()[$row['type']] ?? $row['type'],
                'code' => $row['code'],
                'name' => $row['name'],
                'required' => $row['required'],
                'option' => $row['option'],
                'default' => $row['default'],
                'status' => $row['status'] ? '<span class="badge badge-success">ON</span>' : '<span class="badge badge-danger">OFF</span>',
                'action' => $action,
            ];
        }

        $data['listTh'] = $listTh;
        $data['dataTr'] = $dataTr;
        $data['pagination'] = $dataTmp->appends(request()->except(['_token', '_pjax']))->links($this->vncore_templatePathAdmin.'component.pagination');
        $data['resultItems'] = vncore_language_render('admin.result_item', ['item_from' => $dataTmp->firstItem(), 'item_to' => $dataTmp->lastItem(), 'total' =>  $dataTmp->total()]);
        $data['fieldTypes'] = $this->fieldTypes();
        $data['selectTypes'] = $this->selectTypes();
        $data['layout'] = 'edit';
        return view($this->vncore_templatePathAdmin.'screen.custom_field')
            ->with($data);
    }


    /**
     * update status
     */
    public function postEdit($id)
    {
        $customField = AdminCustomField::find($id);
        $data = request()->all();
        $validator = Validator::make($data, [
            'type' => 'required|string|max:100',
            'code' => 'required|string|max:100',
            'name' => 'required|string|max:250',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($data);
        }
        //Edit

        $dataUpdate = [
            'type' => $data['type'],
            'name' => $data['name'],
            'code' => $data['code'],
            'option' => $data['option'],
            'default' => $data['default'],
            'required' => (!empty($data['required']) ? 1 : 0),
            'status' => (!empty($data['status']) ? 1 : 0),
        ];
        $dataUpdate = vncore_clean($dataUpdate, ['default'], true);

        $customField->update($dataUpdate);
        
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
            AdminCustomField::destroy($arrID);
            return response()->json(['error' => 0, 'msg' => vncore_language_render('action.update_success')]);
        }
    }

    /**
     * List field support custom
     *
     * @return  [type]  [return description]
     */
    public function fieldTypes() {
        return config('vncore-config.schema_customize', []);
    }

    public function selectTypes() {
        return [
            'text'     => 'Text', 
            'number'   => 'Number', 
            'date'     => 'Date', 
            'month'    => 'Month', 
            'week'     => 'Week', 
            'time'     => 'Time', 
            'color'    => 'Color', 
            'email'    => 'Email', 
            'password' => 'Password', 
            'url'      => 'Url', 
            'range'    => 'Range', 
            'textarea' => 'Textarea', 
            'select'   => 'Select', 
            'radio'    => 'Radio', 
            'checkbox' => 'Check box',
        ];
    }
}
