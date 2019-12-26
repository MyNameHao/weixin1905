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

//Route::get('/', function () {
//   return view('welcome');
//});
//商城首页
Route::get('/code', 'Index\IndexController@code');
Route::get('/', 'Index\IndexController@index');
//商城详情页
Route::get('/detail/{id}', 'Goods\GoodsController@detail');




Route::get('/info', function () {
    phpinfo();
});
Route::get('/adduser','User\UserController@adduser');
Route::get('/deluser','User\UserController@deluser');
Route::get('/upuser','User\UserController@upuser');
//Route::get('/index','User\UserController@index');
Route::get('/redis1','User\UserController@redis1');
Route::get('/qishou','User\UserController@qishou');
Route::get('/fanyi','User\UserController@fanyi');

//微信
Route::get('/weixinurl','Wx\Weixin@weixinurl');
//Route::post('/weixinurl','Wx\Weixin@receiv');      //接受微信的推送事件
Route::get('/xmltest','Wx\Weixin@xmltest');      //接受微信的推送事件    
Route::get('/ceshi','Wx\Weixin@ceshi');      //测试方法
Route::get('/ceshi2','Wx\Weixin@ceshi2');      //测试方法
Route::get('/tupianceshi','Wx\Weixin@tupianceshi');      //图片测试方法
Route::get('/createMeun','Wx\Weixin@createMeun');      //更换自定义菜单
//
Route::get('/vote','Wx\VoteController@index');      //接收网页回调--code
Route::get('/votes/{openid}','Wx\VoteController@votes');      //微信网页展示
//weather---天气
Route::get('/weather','Wx\Weixin@weather');
Route::get('/qrcode','Wx\WxQRController@qrcode');

//测试事件
Route::post('/weixinurl','test\TestController@test');      //接受微信的推送事件
Route::get('/wxqr','test\TestController@wxqr');      //接受微信的推送事件
Route::get('/festival','test\TestController@festival');      //节日主页



