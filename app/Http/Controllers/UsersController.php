<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UsersController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth',[
            'except' => ['create','show','store','index','confirmEmail']
        ]);

        $this->middleware('guest',[
            'only' => ['create']
        ]);

        // 限流 一个小时内只能提交 10 次请求；
        $this->middleware('throttle:10,60', [
            'only' => ['store']
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

        //调用成员方法，传参

        $this->sendEmailConfirmationTo($user);
        session()->flash('success','账号激活的邮件已发送至您的邮箱，请注意查收');
        return redirect('/');
    }

    protected function sendEmailConfirmationTo($user)
    {
        $view = 'email.confirm';
        $data = ['user' => $user];

        $to = $user->email;
        $subject = '感谢您注册Gao App,请确认您的邮箱';

        Mail::send($view,$data,function ($message) use ($to,$subject) {
            $message->to($to)->subject($subject);
        });
    }

    public function confirmEmail($token)
    {
        $user = User::where('activation_token', $token)->firstOrFail();
        $user->activated = true;
        $user->activation_token = null;
        $user->save();

        Auth::login($user);
        session()->flash('success','恭喜您，账号激活成功');
        return redirect()->route('users.show',['user' => $user]);
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

    public function destroy(User $user)
    {
        $this->authorize('destroy', $user);
        $user->delete();
        session()->flash('success','删除成功');
        return back();
    }


}
