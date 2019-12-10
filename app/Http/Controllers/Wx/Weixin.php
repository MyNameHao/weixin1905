<?php

namespace App\Http\Controllers\Wx;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;

class Weixin extends Controller
{
    private $access_token;
    public function __construct(){
//        $this->access_token=$this->GetAccess_Token();
    }
    public function GetAccess_Token(){
       $url='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx4fdcb23b1ce7f2c6&secret=24faac13d7af0aa67ddafc442eded79f';
        return json_decode($token=file_get_contents($url))->access_token;

    }
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
        $xml=file_get_contents("php://input");
        $data = date('Y-m-d H:i:s').$xml;
        file_put_contents($loc_file,$data,FILE_APPEND);
        //处理xml数据
        $xml_obj =simplexml_load_string($xml);
        //入库--其他操作
        if($xml_obj->MsgType=='event'){
            if($xml_obj->Event=='subscribe'){
                $token=$this->access_token;
                $openid=$xml_obj->FromUserName;
                $this->GetUserInfo($token,$openid);
            }
        }
        if($xml_obj->MsgType=='text'){
                $tousername=$xml_obj->ToUserName;
                $fromusername=$xml_obj->FromUserName;
                $createtime=time();
                $content=date('Y-m-d H:i:s').'  '.$xml_obj->Content;
                $textinfo='<xml><ToUserName><![CDATA['.$tousername.']]></ToUserName>
                                <FromUserName><![CDATA['.$fromusername.']]></FromUserName>
                                <CreateTime>'.$createtime.'</CreateTime>
                                <MsgType><![CDATA[text]]></MsgType>
                                <Content><![CDATA['.$content.']]></Content>
                           </xml>';
            echo $textinfo;
        }


    }
    /*
     * 获取用户基本信息
     * */
    public function GetUserInfo($token,$openid){
        $url='https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$token.'&openid='.$openid.'&lang=zh_CN';
        $json_srt=file_get_contents($url);
        $log_file='wx_user.log';
        file_put_contents($log_file,$json_srt,FILE_APPEND);
    }
    public function xmltest(){

//        $aaa=json_decode('{"access_token":"28_HeReUttBN2jUb2z5fnuVDE3LZPaoDOODx-hOdxf7ERDe8xZ3-DBuS_0-jLMpnF_ZWSw-0CxCuKNX_7n-BLX4NVEW9piJHptn8XPMVUylm5lfuEIEa2HZ3i7UAS0WBYaAFABGD","expires_in":7200}');
//        $bbb=json_decode('{"errcode":42001,"errmsg":"access_token expired hints: [qIEFuSeNRa-pdVBHA!]"}');

        $xml_str = '<xml><ToUserName><![CDATA[gh_037619b92bc0]]></ToUserName>
                                <FromUserName><![CDATA[oOWCkwpc0xrL17uauyKckwF4qaKI]]></FromUserName>
                                <CreateTime>1575976464</CreateTime>
                                <MsgType><![CDATA[text]]></MsgType>
                                <Content><![CDATA[⊙∀⊙！]]></Content>
                           </xml>';
        $xml_obj=simplexml_load_string($xml_str);

        if($xml_obj->MsgType=='text'){
            $tousername=$xml_obj->ToUserName;
            $fromusername=$xml_obj->FromUserName;
            $createtime=time();
            $content=date('Y-m-d H:i:s').'  '.$xml_obj->Content;
            $textinfo='<xml><ToUserName><![CDATA['.$tousername.']]></ToUserName>
                                <FromUserName><![CDATA['.$fromusername.']]></FromUserName>
                                <CreateTime>'.$createtime.'</CreateTime>
                                <MsgType><![CDATA[text]]></MsgType>
                                <Content><![CDATA['.$content.']]></Content>
                           </xml>';
            echo $textinfo;
        }




//                $token='28_HeReUttBN2jUb2z5fnuVDE3LZPaoDOODx-hOdxf7ERDe8xZ3-DBuS_0-jLMpnF_ZWSw-0CxCuKNX_7n-BLX4NVEW9piJHptn8XPMVUylm5lfuEIEa2HZ3i7UAS0WBYaAFABGD';
//                $openid=$xml_obj->FromUserName;
//
//
//        $url='https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$token.'&openid='.$openid.'&lang=zh_CN';
//        $json_srt=file_get_contents($url);
//        dd(json_decode($json_srt)->errcode);


    }

}
