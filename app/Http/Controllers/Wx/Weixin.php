<?php

namespace App\Http\Controllers\Wx;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Weixin extends Controller
{
    /*
     * 处理接入
     * */
    public function weixinurl(){
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $token = '13f28331544668e5081ad31235242a34';
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            echo $_GET['echostr'];
        }else{
            return false;
        }
    }
    public function receiv(){

        //将接收到的数据写入日志
        $loc_file='wx.log';
        $data =  json_encode($_POST);
        file_put_contents($loc_file,$data,FILE_APPEND);
    }
    /*
     * 获取用户基本信息
     * */
    public function GetUserInfo(){
        $url='https://api.weixin.qq.com/cgi-bin/user/info?access_token=ACCESS_TOKEN&openid=OPENID&lang=zh_CN';
    }

}
