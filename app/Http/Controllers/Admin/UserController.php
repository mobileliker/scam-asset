<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User, App\Alog;
use Redirect;
use IQuery;

class UserController extends Controller
{
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
    public function create()
    {
        return view(config('app.theme') . '.admin.user.create');
    }

    public function storeOrUpdate(Request $request, $id = -1)
    {
        $this->validate($request, [
            'name' => 'string|max:255',
            'email' => 'required|string|max:255|email|unique:users,email,' . $id,
            'password' => 'required_without:id|string|max:30',
            'password2' => 'same:password',
            'type' => 'required|integer|in:1,2',
        ]);


        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');
        $type = $request->input('type');

        if ($id == -1) {
            $user = new User;
        } else {
            $user = User::findOrFail($id);
        }

        $user->name = $name;
        $user->email = $email;
        if ($password != null && $password != '') $user->password = bcrypt($password);
        $user->type = $type;

        if ($user->save()) {
            $user->type = User::TYPE[$user->type];
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
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($request->ajax()) return $user;

        return view(config('app.theme') . '.admin.user.edit')->withUser($user);
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

    public function settings(Request $request, $id)
    {
        $user = User::find($id);

        $name = $request->input('sname');
        $password = $request->input('spassword');

        if ($name != null && $name != '') $user->name = $name;
        if ($password != null && $password != '') $user->password = bcrypt($password);

        if ($user->save()) {
            return $user;
        } else {
            return "false";
        }
    }

    public function all()
    {
        $users = User::select('id', 'name')->get();
        return $users;
    }
}
