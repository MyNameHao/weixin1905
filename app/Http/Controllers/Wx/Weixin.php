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
        $xml=file_get_contents("php://input");
        $data = date('Y-m-d H:i:s').$xml;
        file_put_contents($loc_file,$data,FILE_APPEND);
        //处理xml数据
        $xml_obj =simplexml_load_string($xml);
        //入库--其他操作
            $token='28_HeReUttBN2jUb2z5fnuVDE3LZPaoDOODx-hOdxf7ERDe8xZ3-DBuS_0-jLMpnF_ZWSw-0CxCuKNX_7n-BLX4NVEW9piJHptn8XPMVUylm5lfuEIEa2HZ3i7UAS0WBYaAFABGD';
            $openid=$xml_obj->FromUserName;
            $this->GetUserInfo($token,$openid);



    }
    /*
     * 获取用户基本信息
     * */
    public function GetUserInfo($token,$openid){
        $url='https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$token.'&openid='.$openid.'&lang=zh_CN';
        $json_srt=file_get_contents($url);
        $log_file='wx_user.log';
        file_put_contents($log_file,$json_srt,FILE_ADDEND);
    }
    public function xmltest(){
            $xml_str = '<xml>
                            <ToUserName><![CDATA[gh_037619b92bc0]]></ToUserName>
                            <FromUserName><![CDATA[oOWCkwpc0xrL17uauyKckwF4qaKI]]></FromUserName>
                            <CreateTime>1575961667</CreateTime>
                            <MsgType><![CDATA[event]]></MsgType>
                            <Event><![CDATA[subscribe]]></Event>
                            <EventKey><![CDATA[]]></EventKey>
                        </xml>';
        $xml_obj=simplexml_load_string($xml_str);
        echo $xml_obj->Event;exit;
        echo $xml_obj['FromUserName'].'<br>';
        echo $xml_obj['ToUserName'].'<br>';
        echo $xml_obj['Content'].'<br>';
    }

}
