<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;

class WeixinUser extends Model
{
    protected $table='p_wx_users';
    protected $primaryKey='uid';
    static function GetAccess_Token(){
        $keys='wx_access_token';
        $access_token=Redis::get($keys);
        $url='https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid=oOWCkwpc0xrL17uauyKckwF4qaKI=&lang=zh_CN';
        $json=json_decode(file_get_contents($url),true);
        $aa=array_key_exists('errcode',$json);
        if($aa){
            $errcode=true;
            if($json['errcode']==40001||$json['errcode']==42001){
                $errcode=false;
            }
        }
        if($access_token&&$errcode){
            return $access_token;
        }
        $url='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx4fdcb23b1ce7f2c6&secret=24faac13d7af0aa67ddafc442eded79f';
        $access_token=json_decode($token=file_get_contents($url))->access_token;
        $keys='wx_access_token';
        Redis::set($keys,$access_token);
        Redis::expire($keys,3600);
        return $access_token;//报错先检查网络是否连接

    }
    static public function jsapi_ticket(){
        $key='wx_jsapi_ticket';
        $js_sdk=Redis::get($key);
        if($js_sdk){
            return $js_sdk;
        }
        $access_token=WeixinUser::GetAccess_Token();
        $url='https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$access_token.'&type=jsapi';
        $json_jssdk=file_get_contents($url);
        $js_sdk=json_decode($json_jssdk,true)['ticket'];
        Redis::set($key,$js_sdk);
        Redis::expire($key,3600);
        return $js_sdk;

    }
}
