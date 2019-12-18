<?php

namespace App\Http\Controllers\Wx;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    public function index(){
        print_r($_GET);

        $code=$_GET['code'];
        $this->GetAccessToken($code);
    }
    /*
     * 根据code获取accesstoken
     * */
    protected function GetAccessToken($code){
        $url='https://api.weixin.qq.com/sns/oauth2/access_token?appid='.env('APPID').'&secret='.env('APPSECRE').'&code='.$code.'&grant_type=authorization_code';
        $json_obj=json_decode(file_get_contents($url),true);
        dd($json_obj);
    }
}
