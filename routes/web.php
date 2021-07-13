<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaticPagesController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\PasswordController;

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
