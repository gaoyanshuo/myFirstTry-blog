<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $table = 'users';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function gravatar($size = '100')
    {
        $hash = md5(strtolower(trim($this->attributes['email'])));
        return "http://www.gravatar.com/avatar/$hash?s=$size";
    }

    public static function boot()
    {
        parent::boot();

//      boot 方法会在用户模型类完成初始化之后进行加载

        static::creating(function ($user) {
            $user->activation_token = Str::random(10);
        });
    }

    public function statuses()
    {
        return $this->hasMany(Status::class);
    }

    public function seed()
    {
        return $this->statuses()->orderByDesc('created_at');
    }

    //获取粉丝关系列表

    public function followers()
    {
        return $this->belongsToMany(User::class,'followers','user_id','followers_id');
    }

    //获取用户关注人列表

    public function followings()
    {
        return $this->belongsToMany(User::class,'followers','followers_id','user_id');
    }

    //关注

    public function follow($user_id)
    {
        $this->followings()->sync(['user_id' => $user_id], false);
    }

    //取关

    public function unfollow($user_id)
    {
        $this->followings()->detach(['user_id' => $user_id]);
    }

    //判断是否关注

    public function is_following($user_id)
    {
        return $this->followings->contains($user_id);
    }
}
