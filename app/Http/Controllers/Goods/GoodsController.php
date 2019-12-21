<?php

namespace App\Http\Controllers\Goods;

use App\Http\Controllers\Controller;
use App\Model\GoodsModel;
use App\Model\WeixinUser;
use Illuminate\Http\Request;

class GoodsController extends Controller
{
    public function detail($id){
        $openid='oOWCkwpc0xrL17uauyKckwF4qaKI';
        $userimg=WeixinUser::value('headimgurl');
        $goodsinfo=GoodsModel::where('goods_id',$id)->first();
        return view('goods.detail',['goodsinfo'=>$goodsinfo,'img'=>$userimg]);
    }
}
