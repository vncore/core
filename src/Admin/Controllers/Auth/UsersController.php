<?php
namespace Vncore\Core\Admin\Controllers\Auth;

use Vncore\Core\Admin\Models\AdminPermission;
use Vncore\Core\Admin\Models\AdminRole;
use Vncore\Core\Admin\Models\AdminUser;
use Vncore\Core\Admin\Controllers\RootAdminController;
use Validator;

class UsersController extends RootAdminController
{
    public $permissions;
    public $roles;
    public function __construct()
    {
        parent::__construct();
        $this->permissions = AdminPermission::pluck('name', 'id')->all();
        $this->roles       = AdminRole::pluck('name', 'id')->all();
    }

    public function index()
    {
        $data = [
            'title'         => vncore_language_render('admin.user.list'),
            'subTitle'      => '',
            'urlDeleteItem' => vncore_route_admin('admin_user.delete'),
            'removeList'    => 0, // 1 - Enable function delete list item
            'buttonRefresh' => 1, // 1 - Enable button refresh
        ];
        $listTh = [
            'id'         => 'ID',
            'username'   => vncore_language_render('admin.user.user_name'),
            'name'       => vncore_language_render('admin.user.name'),
            'roles'      => vncore_language_render('admin.user.roles'),
            'permission' => vncore_language_render('admin.user.permission'),
            'status' => vncore_language_render('admin.user.status'),
            'created_at' => vncore_language_render('admin.created_at'),
            'action'     => vncore_language_render('action.title'),
        ];
        $sort_order = vncore_clean(request('sort_order') ?? 'id_desc');
        $keyword    = vncore_clean(request('keyword') ?? '');
        $arrSort = [
            'id__desc'       => vncore_language_render('filter_sort.id_desc'),
            'id__asc'        => vncore_language_render('filter_sort.id_asc'),
            'username__desc' => vncore_language_render('filter_sort.alpha_desc', ['alpha' => 'username']),
            'alpha__asc'  => vncore_language_render('filter_sort.alpha_asc', ['alpha' => 'username']),
            'name__desc'     => vncore_language_render('filter_sort.name_desc'),
            'name__asc'      => vncore_language_render('filter_sort.name_asc'),
        ];
        $obj = new AdminUser;

        if ($keyword) {
            $obj = $obj->whereRaw('id = ?  OR name like "%' . $keyword . '%" OR username like "%' . $keyword . '%"  ', [$keyword]);
        }
        if ($sort_order && array_key_exists($sort_order, $arrSort)) {
            $field = explode('__', $sort_order)[0];
            $sort_field = explode('__', $sort_order)[1];
            $obj = $obj->orderBy($field, $sort_field);
        } else {
            $obj = $obj->orderBy('id', 'desc');
        }
        $dataTmp = $obj->paginate(20);

        $dataTr = [];
        foreach ($dataTmp as $key => $row) {
            $showRoles = '';
            if ($row['roles']->count()) {
                foreach ($row['roles'] as $key => $rols) {
                    $showRoles .= '<span class="badge badge-success">' . $rols->name . '</span> ';
                }
            }
            $showPermission = '';
            if ($row['permissions']->count()) {
                foreach ($row['permissions'] as $key => $p) {
                    $showPermission .= '<span class="badge badge-success">' . $p->name . '</span> ';
                }
            }

            $arrAction = [
                '<a href="' . vncore_route_admin('admin_user.edit', ['id' => $row['id'], 'page' => request('page')]) . '"  class="dropdown-item"><i class="fa fa-edit"></i> '.vncore_language_render('action.edit').'</a>',
                ];
            if (auth('admin')->user()->id == $row['id'] || in_array($row['id'], VNCORE_GUARD_ADMIN)) {
                //
            } else {
                $arrAction[] = '<a href="#" onclick="deleteItem(\'' . $row['id'] . '\');"  title="' . vncore_language_render('action.delete') . '" class="dropdown-item"><i class="fas fa-trash-alt"></i> '.vncore_language_render('action.remove').'</a>';
            }
            $action = $this->procesListAction($arrAction);


            $dataTr[] = [
                'id' => $row['id'],
                'username' => $row['username'],
                'name' => $row['name'],
                'roles' => $showRoles,
                'permission' => $showPermission,
                'status' => $row['status'] ? '<span class="badge badge-success">'.vncore_language_render('admin.user.active').'</span>' : '<span class="badge badge-danger">'.vncore_language_render('admin.user.inactive').'</span>',
                'created_at' => $row['created_at'],
                'action' => $action,
            ];
        }

        $data['listTh'] = $listTh;
        $data['dataTr'] = $dataTr;
        $data['pagination'] = $dataTmp->appends(request()->except(['_token', '_pjax']))->links($this->vncore_templatePathAdmin.'component.pagination');
        $data['resultItems'] = vncore_language_render('admin.result_item', ['item_from' => $dataTmp->firstItem(), 'item_to' => $dataTmp->lastItem(), 'total' =>  $dataTmp->total()]);

        //menuRight
        $data['menuRight'][] = '<a href="' . vncore_route_admin('admin_user.create') . '" class="btn btn-sm  btn-success  btn-flat" title="New" id="button_create_new">
                           <i class="fa fa-plus" title="'.vncore_language_render('action.add').'"></i>
                           </a>';
        //=menuRight

        //menuSort
        $optionSort = '';
        foreach ($arrSort as $key => $status) {
            $optionSort .= '<option  ' . (($sort_order == $key) ? "selected" : "") . ' value="' . $key . '">' . $status . '</option>';
        }
        //=menuSort

        //menuSearch
        $data['topMenuRight'][] = '
                <form action="' . vncore_route_admin('admin_user.index') . '" id="button_search">
                <div class="input-group input-group" style="width: 350px;">
                    <select class="form-control form-control-sm rounded-0 select2" name="sort_order" id="sort_order">
                    '.$optionSort.'
                    </select> &nbsp;
                    <input type="text" name="keyword" class="form-control form-control-sm rounded-0 float-right" placeholder="' . vncore_language_render('admin.user.search_place') . '" value="' . $keyword . '">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-search"></i></button>
                    </div>
                </div>
                </form>';
        //=menuSearch


        return view($this->vncore_templatePathAdmin.'screen.list')
            ->with($data);
    }

    /**
     * Form create new item in admin
     * @return [type] [description]
     */
    public function create()
    {
        $data = [
            'title'             => vncore_language_render('admin.user.add_new_title'),
            'subTitle'          => '',
            'title_description' => vncore_language_render('admin.user.add_new_des'),
            'user'              => [],
            'roles'             => $this->roles,
            'permissions'       => $this->permissions,
            'url_action'        => vncore_route_admin('admin_user.post_create'),
        ];

        return view($this->vncore_templatePathAdmin.'auth.user')
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
            'name'     => 'required|string|max:100',
            'username' => 'required|regex:/(^([0-9A-Za-z@\._]+)$)/|unique:"'.AdminUser::class.'",username|string|max:100|min:3',
            'avatar'   => 'nullable|string|max:255',
            'password' => 'required|string|max:60|min:3|confirmed',
            'email'    => 'required|string|email|max:255|unique:"'.AdminUser::class.'",email',
        ], [
            'username.regex' => vncore_language_render('admin.user.username_validate'),
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $dataCreate = [
            'name'     => $data['name'],
            'username' => strtolower($data['username']),
            'avatar'   => $data['avatar'],
            'email'    => strtolower($data['email']),
            'password' => bcrypt($data['password']),
        ];
        $dataCreate = vncore_clean($dataCreate, [], true);
        $user = AdminUser::createUser($dataCreate);

        $roles = $data['roles'] ?? [];
        $permission = $data['permission'] ?? [];

        //Process role special
        if (in_array(1, $roles)) {
            // If group admin
            $roles = [1];
            $permission = [];
        } elseif (in_array(2, $roles)) {
            // If group onlyview
            $roles = [2];
            $permission = [];
        }
        //End process role special

        //Insert roles
        if ($roles) {
            $user->roles()->attach($roles);
        }
        //Insert permission
        if ($permission) {
            $user->permissions()->attach($permission);
        }

        return redirect()->route('admin_user.index')->with('success', vncore_language_render('action.create_success'));
    }

    /**
     * Form edit
     */
    public function edit($id)
    {
        $user = AdminUser::find($id);
        if ($user === null) {
            return vncore_language_render('admin.data_not_found_detail');
        }
        $data = [
            'title'             => vncore_language_render('action.edit'),
            'subTitle'          => '',
            'title_description' => '',
            'user'              => $user,
            'roles'             => $this->roles,
            'permissions'       => $this->permissions,
            'url_action'        => vncore_route_admin('admin_user.post_edit', ['id' => $user['id']]),
            'isAllStore'        => ($user->isAdministrator() || $user->isViewAll()) ? 1: 0,

        ];
        return view($this->vncore_templatePathAdmin.'auth.user')
            ->with($data);
    }

    /**
     * update status
     */
    public function postEdit($id)
    {
        $user = AdminUser::find($id);
        $data = request()->all();
        $dataOrigin = request()->all();
        $validator = Validator::make($dataOrigin, [
            'name'     => 'required|string|max:100',
            'username' => 'required|regex:/(^([0-9A-Za-z@\._]+)$)/|unique:"'.AdminUser::class.'",username,' . $user->id . '|string|max:100|min:3',
            'avatar'   => 'nullable|string|max:255',
            'password' => 'nullable|string|max:60|min:3|confirmed',
            'email'    => 'required|string|email|max:255|unique:"'.AdminUser::class.'",email,' . $user->id,
        ], [
            'username.regex' => vncore_language_render('admin.user.username_validate'),
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        //Edit
        $dataUpdate = [
            'name' => $data['name'],
            'username' => strtolower($data['username']),
            'avatar' => $data['avatar'],
            'email' => strtolower($data['email']),
        ];

        if ($id != admin()->user()->id) {
            //Ony change status if not current user
            $dataUpdate['status'] = empty($data['status']) ? 0 : 1;
        }
        
        if ($data['password']) {
            $dataUpdate['password'] = bcrypt($data['password']);
        }
        $dataUpdate = vncore_clean($dataUpdate, [], true);
        AdminUser::updateInfo($dataUpdate, $id);

        if (!in_array($user->id, VNCORE_GUARD_ADMIN)) {
            $roles = $data['roles'] ?? [];
            $permission = $data['permission'] ?? [];
            $user->roles()->detach();
            $user->permissions()->detach();
            //Insert roles
            if ($roles) {
                $user->roles()->attach($roles);
            }
            //Insert permission
            if ($permission) {
                $user->permissions()->attach($permission);
            }
        }

        return redirect()->route('admin_user.index')->with('success', vncore_language_render('action.edit_success'));
    }

    /*
    Delete list Item
    Need mothod destroy to boot deleting in model
     */
    public function deleteList()
    {
        if (!request()->ajax()) {
            return response()->json(['error' => 1, 'msg' => vncore_language_render('admin.method_not_allow')]);
        } else {
            $ids = request('ids');
            $arrID = explode(',', $ids);
            $arrID = array_diff($arrID, VNCORE_GUARD_ADMIN);
            AdminUser::destroy($arrID);
            return response()->json(['error' => 1, 'msg' => '']);
        }
    }
}
