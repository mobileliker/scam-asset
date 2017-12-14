<?php

/**
 * @version : 2.0
 * @author: wuzhihui
 * @date: 2017/6/16
 * @description:
 * （1）添加权限控制的中间件；
 * （2）edit添加返回用户的所有角色，并去除返回页面的代码，仅保留ajax方式的访问；
 * （3）storeOrUpdate添加角色的保存功能
 * （4）setting改为不需要传入id，而是根据当前登录的用户来设置
 * （5）添加批量删除功能，验证功能（2017/7/3）
 * （6）修改了setting方法，优化了错误的提示；（2017/7/6）
 * （7）添加获取所有用户的方法，添加label和value；（2017/7/10）
 */

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\PermissionCategory, App\Permission, App\User, App\Role;
use Auth;
use IQuery;
use Hash, Cache;

class UserController extends Controller
{
    protected $model = User::class;

    public function __construct()
    {
        $this->middleware('ability:UserMethod|Method-User-Index,true')->only('index');
        $this->middleware('ability:UserMethod|Method-User-Store,true')->only('store');
        $this->middleware('ability:UserMethod|Method-User-Edit,true')->only('edit');
        $this->middleware('ability:UserMethod|Method-User-Update,true')->only('update');
        $this->middleware('ability:UserMethod|Method-user-Destroy,true')->only('destroy');
        $this->middleware('ability:Common|Method-Common-Settings,true')->only('settings');
        $this->middleware('ability:Common|Method-Common-Menu,true')->only('menu');
        $this->middleware('ability:Asset|Method-Asset-UserAll,true')->only('all');
        $this->middleware('ability:UserMethod|Method-User-BatchDelete,true')->only('batchDelete');
        $this->middleware('ability:UserMethod|Method-User-Check,true')->only('check');
    }

    public function storeOrUpdate(Request $request, $id = -1)
    {
        $this->validate($request, [
            'name' => 'string|max:255',
            'email' => 'required|string|max:255|email|unique:users,email,' . $id,
            'password' => 'required_without:id|string|max:30',
            'password2' => 'same:password',
            //'type' => 'required|integer|in:1,2',
        ]);


        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');
        //$type = $request->input('type');
        $role_ids = $request->role_ids;

        if ($id == -1) {
            $user = new User;
        } else {
            $user = User::findOrFail($id);
        }

        $user->name = $name;
        $user->email = $email;
        if ($password != null && $password != '') $user->password = bcrypt($password);
        //$user->type = $type;

        if ($user->save()) {
            foreach($role_ids as $key=>$role_id){
                $role_ids[$key] = intval($role_id);
            }
            $user->roles()->sync($role_ids);

            //$user->type = User::TYPE[$user->type];
            return $user;
//            if($id == -1) $operate = Alog::OPERATE_CREATE;
//            else $operate = Alog::OPERATE_UPDATE;
//            Alog::log('User', $operate, $user->name.'('.$user->email.')', $request->getClientIp());
//            return Redirect::to('admin/user')->with('status', '保存成功');
        } else {
            abort(500);
//            return Redirect::back()->withErrors('保存失败');
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //return "admin.user.index";

        //$users = User::paginate(10);

        //$request->flash();

        $users = User::whereRaw('1 = 1');

        //文本查询
        $query_text = $request->input('query_text');
        if ($query_text != null && $request != '') {
            $texts = explode(' ', $query_text);
            foreach ($texts as $text) {
                $users = $users->where('name', 'like', '%' . $text . '%');
            }

        }

        $type = $request->input('type');
        if ($type != null && $type != '') {
            $users = $users->where('type', '=', $type);
        }

        IQuery::ofOrder($users, $request);


        if ($request->paginate != null && $request->paginate != '')
            $users = $users->paginate($request->paginate);
        else
            $users = $users->paginate(10);

        if ($request->ajax()) {
            foreach ($users as $user) {
                $user->type = User::TYPE[$user->type];
            }
            return $users;
        }

        if ($users == null || count($users) == 0) {
            return view(config('app.theme') . '.admin.user.index')->withUsers($users)->with('status', '查询结果为空');
        } else {
            return view(config('app.theme') . '.admin.user.index')->withUsers($users);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
//    public function create()
//    {
//        return view(config('app.theme') . '.admin.user.create');
//    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->storeOrUpdate($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
//    public function show($id)
//    {
//        //
//    }

    /**
     * Show the form for editing the specified resource.
     * v2.0
     * 添加返回该用户所有角色，并去除返回页面的代码，仅保留ajax方式的访问；
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $user = User::findOrFail($id);

//        $roles = $user->roles;
//        $user->roles = $roles;

        $roles = $user->roles->pluck('id');
        $user->role_ids = $roles;
        foreach($user->role_ids as $key=>$role_id){
            $user->role_ids[$key] = '' . $role_id;
        }

        return $user;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return $this->storeOrUpdate($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if ($user->delete()) {
            return $user;
            //Alog::log('User', Alog::OPERATE_DELETE, $user->name.'('.$user->email.')', $request->getClientIp());
            //return Redirect::back()->with('status', '删除成功');
        } else {
            abort(500);
            //return Redirec::back()->withErrors();
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return string
     */
    public function settings(Request $request)
    {
        $this->validate($request, [
            'sname' => 'required|max:255',
            'password' => 'required|max:30',
            'spassword' => 'nullable|string|max:30',
            'spassword2' => 'nullable|same:spassword',
        ]);

        $user = Auth::user();

        if(!Hash::check($request->password, $user->getAuthPassword())){
            $errors = [
                'password' => [
                    'password' => '密码错误'
                ]
            ];
            return new JsonResponse($errors, 422);
        }

        $name = $request->input('sname');
        $password = $request->input('spassword');

        if ($name != null && $name != '') $user->name = $name;
        if ($password != null && $password != '') $user->password = bcrypt($password);

        if ($user->save()) {
            return $user;
        } else {
            abort(500, '保存错误');
        }
    }


    public function menu()
    {
        $user = Auth::user();

        $menuPermissionCategories = PermissionCategory::join('permission_categories as p', function($join) {
            $join->on('p.id', '=', 'permission_categories.pid')->whereNull('p.deleted_at')->where('p.name', '=', 'Menu');
        })->select('permission_categories.*')->get();

        $res = array();
        foreach($menuPermissionCategories as $permissionCategory){
            $permissions = Permission::where('permissions.permission_category_id', '=', $permissionCategory->id)->get();

            $allow_permissions = array();
            foreach($permissions as $permission){
                if($user->can($permission->name)){
                    $allow_permissions[] = $permission;
                }
            }
            if(count($allow_permissions) > 0){
                $permissionCategory->data = $allow_permissions;
                $res[] = $permissionCategory;
            }

        }
        return $res;
    }

    public function all()
    {
        $users = Cache::rememberForever('user_all', function() {
            \Log::info('record cache');
            return User::select('id', 'name', 'id as value', 'name as label')->get();
        });
        return $users;
    }

    /**
     * 批量删除功能
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function batchDelete(Request $request)
    {
        return parent::batchDelete($request);
    }

    /**
     * 验证功能
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function check(Request $request)
    {
        return parent::check($request);
    }
}
