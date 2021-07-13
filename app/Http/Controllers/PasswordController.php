<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
class PasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('password.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $this->validate($request,[
            'email' => 'required | email'
        ]);

        //如果邮箱不存在

        $user = User::where('email',$request->email)->first();
        if (is_null($user)) {
            session()->flash('danger','邮箱未注册');
            return redirect()->withInput();
        }

        //邮箱存在
        //生成token

        $token = hash_hmac('sha256', Str::random(40),config('app.key'));
        $email = $request->email;
        //入库

        DB::table('password_resets')->updateOrInsert(['email' => $email], [
            'email' => $email,
            'token' => Hash::make($token),
            'created_at' => new Carbon,
        ]);

        //令牌发送给用户
        Mail::send('email.reset_link',['token' => $token],function ($message) use ($email) {
            $message->to($email)->subject('忘记密码');
        });

        session()->flash('success', '重置邮件发送成功，请查收');
        return redirect()->back();
    }

    public function showResetForm($token)
    {
        return view('email.reset_link',['token' => $token]);
    }
}
