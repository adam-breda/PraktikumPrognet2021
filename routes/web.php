<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->middleware('auth:web')->name('home');
Route::get('/user/logout', 'Auth\LoginController@userLogout')->middleware('auth:web')->name('user.logout');

Route::prefix('admin')->group(function (){
    Route::group(['middleware' => ['auth:admin']], function(){
        //Logout routes
        Route::post('/logout', 'Auth\AdminLoginController@logout')->name('admin.logout');
        //Dashboard routes
        Route::get('/', 'AdminController@index')->name('admin.dashboard');
    });

    Route::group(['middleware' => ['guest']], function(){
        //Login routes
        Route::get('/login', 'Auth\AdminLoginController@showLoginForm')->name('admin.login');
        Route::post('/login', 'Auth\AdminLoginController@login')->name('admin.login.submit');
        //Register routes
        Route::get('/register', 'Auth\AdminRegisterController@showRegistrationForm')->name('admin.register');
        Route::post('/register', 'Auth\AdminRegisterController@register')->name('admin.register.submit');
        //Reset password routes
        Route::get('/password/reset', 'Auth\AdminForgotPasswordController@showLinkRequestForm')->name('admin.password.request');
        Route::post('/password/email', 'Auth\AdminForgotPasswordController@sendResetLinkEmail')->name('admin.password.email');
        Route::get('/password/reset/{token}', 'Auth\AdminResetPasswordController@showResetForm')->name('admin.password.reset');
        Route::post('/password/reset', 'Auth\AdminResetPasswordController@reset')->name('admin.password.update');
    });

});
