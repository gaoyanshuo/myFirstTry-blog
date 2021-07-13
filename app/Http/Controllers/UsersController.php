<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth',[
            'except' => 'create','show','store','index'
        ]);

        $this->middleware('guest',[
            'only' => ['create']
        ]);
    }

    public function create()
    {
        return view('users.create');
    }

    public function show(User $user)
    {
        return view('users.show', ['user' => $user]);
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|unique:users|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6'
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();
        Auth::login($user);
        session()->flash('success','注册成功');
        return redirect()->route('users.show',[$user]);
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);

        return view('users.edit',['user' => $user]);
    }

    public function update(User $user, Request $request)
    {
        $this->authorize('update', $user);

        $this->validate($request,[
            'name' => 'required | max:50',
            'password' => 'nullable | confirmed | min:6'
        ]);

        $user->update([
            'name' => $request->name,
            'password' =>bcrypt($request->password)
        ]);

        session()->flash('success','个人信息更新成功');

        return redirect()->route('users.show', $user);
    }

    public function index()
    {
        $users = User::paginate(10);
        return view('users.index', ['users' => $users]);
    }
}
