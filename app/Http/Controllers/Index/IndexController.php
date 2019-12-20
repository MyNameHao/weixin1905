<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
//use App\Model\UserModel;
use App\Model\GoodsModel;
use App\Model\WeixinUser;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function code(){
        //获取微信认证code码
        $code=$_GET['code'];
        //根据code码获取access_token
        $data=$this->GetAccessToken($code);
        //获取用户信息
        $userinfo=$this->GetUserInfo($data['access_token'],$data['openid']);
       return redirect('/'.$data['openid']);
    }
    public function  index($openid){
        $data=WeixinUser::where('openid',$openid)->first();
        $NewGoods=GoodsModel::where('is_new',1)->limit(4)->get();
        $HotGoods=GoodsModel::where('is_hot',1)->limit(6)->get();
        return view('index.index',['img'=>$data['headimgurl'],'newgoods'=>$NewGoods,'hotgoods'=>$HotGoods]);
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
}
