<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
//use App\Model\UserModel;
use App\Model\GoodsModel;
use App\Model\WeixinUser;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class IndexController extends Controller
{
    public function code(){
        //获取微信认证code码
        $code=$_GET['code'];
        //根据code码获取access_token
        $data=$this->GetAccessToken($code);
        //获取用户信息
        $userinfo=$this->GetUserInfo($data['access_token'],$data['openid']);
       return redirect('/index/'.$data['openid']);
    }
    public function  index($openid){
        $data=WeixinUser::where('openid',$openid)->first();
        $goodsifo=GoodsModel::get();
        $js_sdk=$this->jssdk();
        return view('index.index',['img'=>$data['headimgurl'],'goodsinfo'=>$goodsifo,'js_sdk'=>$js_sdk]);
    }
    /*
 * 根据code获取accesstoken
 * */
    protected function GetAccessToken($code){
        $url='https://api.weixin.qq.com/sns/oauth2/access_token?appid='.env('APPID').'&secret='.env('APPSECRE').'&code='.$code.'&grant_type=authorization_code';
        $json_arr=json_decode(file_get_contents($url),true);
        return $json_arr;
    }
    /*
     * 根据Access_Token和Openid获取用户信息
     * */
    public function GetUserInfo($token,$openid){
        $url='https://api.weixin.qq.com/sns/userinfo?access_token='.$token.'&openid='.$openid.'&lang=zh_CN';
        $userinfo_json=file_get_contents($url);
        $userinfo=json_decode($userinfo_json,true);
        if(isset($userinfo['errcode'])){
            die('出错了 40001');  //40001表示用户信息获取失败
        }
        return $userinfo; //返回用户信息
    }
    public function jssdk(){
        $jsapi_ticket=WeixinUser::jsapi_ticket();
//        $signature = "";
        $nonceStr=Str::random(8);
        $timestamp=time();
        $url=$_SERVER['APP_URL'].$_SERVER['REQUEST_URI'];
        $string1="jsapi_ticket={$jsapi_ticket}&noncestr={$nonceStr}&timestamp={$timestamp}&url=".$url;
        $signature=sha1($string1);
        $data=[
            'timestamp'=>$timestamp,
            'nonceStr'=>$nonceStr,
            'signature'=>$signature,
        ];
        return $data;
    }
}
