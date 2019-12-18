<?php

namespace App\Http\Controllers\Wx;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    public function index(){
        print_r($_GET);

        $code=$_GET['code'];
        $data=$this->GetAccessToken($code);
        $userinfo=$this->GetUserInfo($data['access_token'],$data['poenid']);
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
        $userinfo=file_get_contents($url);
        dd($userinfo);
    }
}
