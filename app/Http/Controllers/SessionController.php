<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest',[
           'only' => ['create']
        ]);
    }

    public function create()
    {
        return view('sessions.create');
    }

    public function store(Request $request)
    {
        $credentials = $this->validate($request,[
            'email' => 'required|email|max:255',
            'password' => 'required'
        ]);
        if (Auth::attempt($credentials, $request->has('remember'))) {
            session()->flash('success','ようこそ');
            $fallback = route('users.show', [Auth::user()]);
            return redirect()->intended($fallback);
        } else {
            session()->flash('danger','邮箱或密码不正确');
            return redirect()->back()->withInput();
        }
    }

    public function destory()
    {
        Auth::logout();
        session()->flash('success','退出成功');
        return redirect()->route('login');
    }
}
