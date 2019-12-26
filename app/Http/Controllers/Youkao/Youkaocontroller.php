<?php

namespace App\Http\Controllers\Youkao;

use App\Http\Controllers\Controller;
use App\Model\CourseModel;
use App\Model\UserCourse;
use App\Model\WeixinUser;
use GuzzleHttp\Client;
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
        file_put_contents('wx.log',$xml,FILE_APPEND);
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
                            <Content><![CDATA[欢迎'.$userinfo->nickname.'同学进入选课系统]]></Content>
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
                            <Content><![CDATA[欢迎'.$userinfo['nickname'].'同学进入选课系统]]></Content>
                          </xml>';
                    echo $xmltext;
                }
            }elseif($xml_obj->Event=='CLICK'){
                $userinfo=UserCourse::where('openid',$openid)->first();
                if($userinfo){
                    $userdata=$this->getuserinfo($openid);
                    $text="您好，".$userdata['nickname']."同学，您当前的课程安排如下\n"."第一节:".$userinfo['course_1']."\n"."第二节:".$userinfo['course_2']."\n"."第三节:".$userinfo['course_3']."\n"."第四节:".$userinfo['course_4'];
                    $xmltext='<xml>
                            <ToUserName><![CDATA['.$openid.']]></ToUserName>
                            <FromUserName><![CDATA['.$xml_obj->TouserName.']]></FromUserName>
                            <CreateTime>'.time().'</CreateTime>
                            <MsgType><![CDATA[text]]></MsgType>
                            <Content><![CDATA['.$text.']]></Content>
                          </xml>';
                    echo $xmltext;
                }else{
                    $xmltext='<xml>
                            <ToUserName><![CDATA['.$openid.']]></ToUserName>
                            <FromUserName><![CDATA['.$xml_obj->TouserName.']]></FromUserName>
                            <CreateTime>'.time().'</CreateTime>
                            <MsgType><![CDATA[text]]></MsgType>
                            <Content><![CDATA[请先选择课程。]]></Content>
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
    public function caidan(){
        $url='https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$this->Access_Token;
        $url1=urlencode('http://1905sunhao.comcto.com/glkc');
//        $view_url='https://open.weixin.qq.com/connect/oauth2/authorize?appid='.env('APPID').'&redirect_uri='.urlencode($url1).'&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';
        $data=[
            'button'=>[
                [
                    'type'=>'click',
                    'name'=>'查看课程',
                    'key'=>'ckkc'
                ],
                [
                    'type'=>'view',
                    'name'=>'管理课程',
                    'url'=>'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx4fdcb23b1ce7f2c6&redirect_uri='.$url1.'&response_type=code&scope=snsapi_userinfo&state=ABCD1905#wechat_redirect'
                ]
            ]
        ];


        $body=json_encode($data,JSON_UNESCAPED_UNICODE);
        $client=new Client();
        $aaa=$client->request('POST',$url,[
            'body'=>$body
        ]);
        echo $aaa->getBody();
    }
    public function glkc(){
       $code=$_GET['code'];
        $url='https://api.weixin.qq.com/sns/oauth2/access_token?appid='.env('APPID').'&secret='.env('APPSECRE').'&code='.$code.'&grant_type=authorization_code';
        $array_token=json_decode(file_get_contents($url),true);
//        $access_token=$array_token['access_token'];
        $openid=$array_token['openid'];
        return ('/glkc2/'.$openid);
    }
    public function glkc2($openid){
        $courseinfo=UserCourse::where('openid',$openid)->first();
        if($courseinfo){
            return redirect('course/index/'.$openid);
        }else{
            return redirect('/course/add/'.$openid);
        }
    }
    public function add($openid){
        $course=CourseModel::get();
        return view('course.add',['data'=>$course,'openid'=>$openid]);
    }
    public function create(){
        $data=request()->all();
        $id=UserCourse::insertGetId($data);
    }
    public function index($openid){
        $courseinfo=UserCourse::where('openid',$openid)->first();
        return view('course.index',['data'=>$courseinfo,'openid'=>$openid]);
    }
    public function update($openid){
        $course=CourseModel::get();
        $courseinfo=UserCourse::where('openid',$openid)->first();
        return view('course.update',['data'=>$course,'openid'=>$openid,'courseinfo'=>$courseinfo]);

    }
    public function updo($openid){
        $data=request()->all();
            $res=UserCourse::where(['openid'=>$openid])->update($data);
    }
}
