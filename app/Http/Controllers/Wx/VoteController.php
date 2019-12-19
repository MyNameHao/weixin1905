<?php

namespace App\Http\Controllers\Wx;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class VoteController extends Controller
{
    public function index(){
//        print_r($_GET);

        $code=$_GET['code'];
        $data=$this->GetAccessToken($code);
        $userinfo=$this->GetUserInfo($data['access_token'],$data['openid']);
        //将客户信息存入redis
        $h_key='H_keys_'.$data['openid'];
        Redis::hmset($h_key,$userinfo);
        //访问量
        $browse_key='vote';
        $number=Redis::incr($browse_key);
        //投票
//        $redis_key='s:weixin';
//        if(!Redis::sismember($redis_key,$userinfo['openid'])){
//            Redis::sadd($redis_key,$userinfo['openid']);
//            echo '恭喜您投票成功'.'<br/>';
//        }else{
//            echo '您已经投过票了'.'<br/>';
//        }
//        $data=Redis::smembers($redis_key);
//        print_r($data);
        dd(redirect('/votes/'.$userinfo['openid']));
        return redirect('/votes/'.$userinfo['openid']);
    }
    public function votes($openid){
        $browse_key='vote';
        echo '当前访问量:'.Redis::get($browse_key).'<br>';
        $redis_keys='ss:weixin';
        if(Redis::zrank($redis_keys,$openid)){
            echo '您已经投过票了'.'<br/>'.'<br/>';
        }else{
            Redis::zadd($redis_keys,time(),$openid);
            echo '恭喜您投票成功'.'<br/>'.'<br/>';
        }
        $data=Redis::zrange($redis_keys,0,-1,true);
//        print_r($data);
        echo '投票名单'.'<br/>';

        foreach($data as $k=>$v){
//            echo '投票用户:'.$k.'<br>'.'投票时间:'.$v.'<br>';
            echo '投票用户:'.Redis::hget('H_keys_'.$k,'nickname').'<br>';echo '投票时间:'.date('Y-m-d H:i:s',$v).'<br>';
        }
        echo '<br>';
        echo'投票人数'.Redis::zcard($redis_keys).'<br>';
        echo '<hr>';
        foreach($data as $k=>$v){
            echo '<img src="'.Redis::hget('H_keys_'.$k,'headimgurl').'">';
        }
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
