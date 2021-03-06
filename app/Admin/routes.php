<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('admin.home');
    $router->get('/wxsendmsg', 'WxMsg@sendMsg')->name('admin.home');
    $router->get('/mpnews', 'WxMsg@mpnews')->name('admin.home');
    $router->resource('users', WxUserController::class);
    $router->resource('wxmsg', WxMsgController::class);
    $router->resource('wxvoice', WxVoiceController::class);
    $router->resource('wximg', WxImgController::class);
    $router->resource('goods', GoodsController::class);
    $router->resource('mediaimg', MediaImgController::class);

});
