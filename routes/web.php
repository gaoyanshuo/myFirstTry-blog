<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaticPagesController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\StatusesController;
use App\Http\Controllers\FollowersController;
//Auth::routes();
//
//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');



//homepage
Route::get('/',[StaticPagesController::class,'home'])->name('home');
Route::get('/help',[StaticPagesController::class,'help'])->name('help');
Route::get('/about',[StaticPagesController::class,'about'])->name('about');

//signup
Route::get('/signup',[UsersController::class,'create'])->name('signup');

//user_CRUD
Route::resource('users', UsersController::class);

//show
Route::get('/users/{user}', [UsersController::class,'show'])->name('users.show');

//login-logout
Route::get('login', [SessionController::class,'create'])->name('login');
Route::post('login', [SessionController::class,'store'])->name('login');
Route::delete('logout', [SessionController::class,'destroy'])->name('logout');

//token
Route::get('signup/confirm/{token}',[UsersController::class,'confirmEmail'])->name('confirm_email');

//change_user_password
Route::get('/password/reset',[PasswordController::class,'showLinkRequestForm'])->name('password.request');
Route::post('/password/email',[PasswordController::class,'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}',[PasswordController::class,'showResetForm'])->name('password.reset');
Route::post('/password/reset',[PasswordController::class,'reset'])->name('password.update');

//statuses
Route::resource('statuses', StatusesController::class,['only' => ['store','destroy']]);

//获取关注数,粉丝数
Route::get('/user/{user}/following',[UsersController::class,'followings'])->name('users.followings');
Route::get('/user/{user}/followers',[UsersController::class,'followers'])->name('users.followers');

//添加关注，取消关注
Route::post('/user/followers/{user}',[FollowersController::class,'store'])->name('followers.store');
Route::delete('user/follwers/{user}',[FollowersController::class,'destroy'])->name('followers.destroy');
