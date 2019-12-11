<?php

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
Route::get('/info', function () {
    phpinfo();
});
Route::get('/adduser','User\UserController@adduser');
Route::get('/deluser','User\UserController@deluser');
Route::get('/upuser','User\UserController@upuser');
Route::get('/index','User\UserController@index');
Route::get('/redis1','User\UserController@redis1');
Route::get('/qishou','User\UserController@qishou');
Route::get('/fanyi','User\UserController@fanyi');

//微信
Route::get('/weixinurl','Wx\Weixin@weixinurl');
Route::post('/weixinurl','Wx\Weixin@receiv');      //接受微信的推送事件
Route::get('/xmltest','Wx\Weixin@xmltest');      //接受微信的推送事件
Route::get('/ceshi','Wx\Weixin@ceshi');      //测试方法
