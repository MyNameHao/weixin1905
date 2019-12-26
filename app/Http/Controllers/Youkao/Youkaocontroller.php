<?php

namespace App\Http\Controllers\Youkao;

use App\Http\Controllers\Controller;
use App\Model\WeixinUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class Youkaocontroller extends Controller
{
    public $Access_Token;
    public function __construct(){
        $this->Access_Token=$this->getToken();
    }
    public function getToken(){
        $redis_key='yk_access_token';
        $access_token=Redis::get($redis_key);
        if($access_token){
            return $access_token;
        }
        $url='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.env('APPID').'&secret='.env('APPSECRE');
        $access_token=json_decode(file_get_contents($url),true)['access_token'];
        Redis::set($redis_key,$access_token);
        Redis::expire($redis_key,3600);
        return $access_token;
    }
    public function weixinurl(){
        $xml=file_get_contents("php://input");
        $xml_obj=simplexml_load_string($xml);
        $openid=$xml_obj->FromUserName;
        if($xml_obj->MsgType=='event'){
            if($xml_obj->Event=='subscribe'){
                $userinfo=WeixinUser::where('openid',$openid)->first();
                if($userinfo){
                    $xmltext='<xml>
                            <ToUserName><![CDATA['.$openid.']]></ToUserName>
                            <FromUserName><![CDATA['.$xml_obj->TouserName.']]></FromUserName>
                            <CreateTime>'.time().'</CreateTime>
                            <MsgType><![CDATA[text]]></MsgType>
                            <Content><![CDATA[欢迎xx同学进入选课系统]]></Content>
                          </xml>';
                    echo $xmltext;
                }else{
                    $userinfo=$this->getuserinfo($openid);
                    $data=[
                        'openid'=>$userinfo['openid'],
                        'sub_time'=>$userinfo['subscribe_time'],
                        'sex'=>$userinfo['sex'],
                        'nickname'=>$userinfo['nickname'],
                        'headimgurl'=>$userinfo['headimgurl']
                    ];
                    WeixinUser::insertGetId($data);
                    $xmltext='<xml>
                            <ToUserName><![CDATA['.$openid.']]></ToUserName>
                            <FromUserName><![CDATA['.$xml_obj->TouserName.']]></FromUserName>
                            <CreateTime>'.time().'</CreateTime>
                            <MsgType><![CDATA[text]]></MsgType>
                            <Content><![CDATA[欢迎xx同学进入选课系统]]></Content>
                          </xml>';
                    echo $xmltext;
                }
            }
        }
    }
    public function getuserinfo($openid){

        $url='https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$this->Access_Token.'&openid='.$openid.'&lang=zh_CN';
        $UserInfo=json_decode(file_get_contents($url),true);
        return $UserInfo;
    }
    public function ceshi1(){
        echo $this->Access_Token;
    }
}
