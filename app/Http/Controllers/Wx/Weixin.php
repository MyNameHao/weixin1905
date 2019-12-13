<?php

namespace App\Http\Controllers\Wx;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;
use App\Model\WeixinUser;
use GuzzleHttp\Client;


class Weixin extends Controller
{
    private $access_token;
    public function __construct(){
        $this->access_token=$this->GetAccess_Token();
    }
    public function GetAccess_Token(){
        $keys='wx_access_token';
        $access_token=Redis::get($keys);
        if($access_token){
            return $access_token;
        }
       $url='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx4fdcb23b1ce7f2c6&secret=24faac13d7af0aa67ddafc442eded79f';
        $access_token=json_decode($token=file_get_contents($url))->access_token;
        $keys='wx_access_token';
        Redis::set($keys,$access_token);
        Redis::expire($keys,3600);
        return $access_token;//报错先检查网络是否连接

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
                $json_str=$this->GetUserInfo($token,$openid);
                //查询当前用户是否注册过-----用户关注自动回复
                $weixininfo=WeixinUser::where('openid',$openid)->first();
                $this->attention($weixininfo,$json_str,$xml_obj);
            }
        }
        if($xml_obj->MsgType=='text'){
            //收到信息自动回复
            $token=$this->access_token;
            $openid=$xml_obj->FromUserName;
            $json_str=$this->GetUserInfo($token,$openid);
               $this->respond($xml_obj,1,$json_str);
        }
        if($xml_obj->MsgType=='image'){
            $token=$this->access_token;
            $media_id=$xml_obj->MediaId;
            $imgname='weixin'.date('Y-m-d H:i:s').rand('1000','9999').'.jpeg';
                $url='https://api.weixin.qq.com/cgi-bin/media/get?access_token='.$token.'&media_id='.$media_id;
            $aaa=file_put_contents("$imgname",file_get_contents($url));
        }


    }
    /*
     *判断用户是否是老用户
     * */
    public function attention($weixininfo,$json_str,$xml_obj){
        if($weixininfo){
            $this->respond($xml_obj,3,$json_str,$weixininfo);
        }else{
            $json_str=json_decode($json_str,true);
            $data=[
                'openid'=>$json_str['openid'],
                'sub_time'=>$json_str['subscribe_time'],
                'sex'=>$json_str['sex'],
                'nickname'=>$json_str['nickname'],
            ];
            $uid=WeixinUser::insertGetId($data);
            $this->respond($xml_obj,2,$json_str);
        }
    }
    /*
     * 信息自动回复
     * */
    public function respond($xml_obj,$code,$json_str,$weixininfo=''){
        $tousername=$xml_obj->ToUserName;
        $fromusername=$xml_obj->FromUserName;
        $createtime=time();
        $json_str=json_decode($json_str,true);
        if($code==1){
            $content=date('Y-m-d H:i:s').$json_str['nickname'].$xml_obj->Content;
        }elseif($code==2){
            $content=date('Y-m-d H:i:s').$json_str['nickname'].'感谢关注';
        }elseif($code==3){
            $content=date('Y-m-d H:i:s',$weixininfo['sub_time']).$json_str['nickname'].'欢迎回来';
        }

        $textinfo='<xml><ToUserName><![CDATA['.$fromusername.']]></ToUserName>
                                <FromUserName><![CDATA['.$tousername.']]></FromUserName>
                                <CreateTime>'.$createtime.'</CreateTime>
                                <MsgType><![CDATA[text]]></MsgType>
                                <Content><![CDATA['.$content.']]></Content>
                           </xml>';
        echo $textinfo;
    }
    /*
     * 获取用户基本信息
     * */
    public function GetUserInfo($token,$openid){
        $url='https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$token.'&openid='.$openid.'&lang=zh_CN';
        $json_srt=file_get_contents($url);
        $log_file='wx_user.log';
        file_put_contents($log_file,$json_srt,FILE_APPEND);
        return $json_srt;
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
            $textinfo='<xml>
                              <ToUserName><![CDATA['.$tousername.']]></ToUserName>
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
    public function ceshi(){
        $json_str=json_decode('{"subscribe":1,"openid":"oOWCkwpc0xrL17uauyKckwF4qaKI","nickname":"ㅤㅤ未成年ㅤㅤ","sex":1,"language":"zh_CN","city":"邯郸","province":"河北","country":"中国","headimgurl":"http:\/\/thirdwx.qlogo.cn\/mmopen\/XM70P5zXcpykDTzCd8tHicXnMib9Q7zGSUrIdXjWzl1KNohkPLFHaUmpx3ndWfoT638pntdPkUcOOk38rIY4UhX3JW0Y7uqoMH\/132","subscribe_time":1576059545,"remark":"","groupid":0,"tagid_list":[],"subscribe_scene":"ADD_SCENE_QR_CODE","qr_scene":0,"qr_scene_str":""}',true);
        $data=[
            'openid'=>$json_str['openid'],
            'sub_time'=>$json_str['subscribe_time'],
            'sex'=>$json_str['sex'],
            'nickname'=>$json_str['nickname'],
        ];
        dd($data);
    }
    public function tupianceshi(){
        //第一阶段
            //    $xml_str='<xml>
            //                    <ToUserName><![CDATA[gh_037619b92bc0]]></ToUserName>
            //                    <FromUserName><![CDATA[oOWCkwpc0xrL17uauyKckwF4qaKI]]></FromUserName>
            //                    <CreateTime>1576146320</CreateTime>
            //                    <MsgType><![CDATA[image]]></MsgType>
            //                    <PicUrl><![CDATA[http://mmbiz.qpic.cn/mmbiz_jpg/5NtdgKzKc79hQ5axHD5aMdQRHib4gicSCiadswH4fIib4jJbrQFAfH4vnHVQAxxHHufKYykJ3pEicEia4VxTPxiaV1UPQ/0]]></PicUrl>
            //                    <MsgId>22564992125915158</MsgId>
            //                    <MediaId><![CDATA[PBxFlf4TA2_Dyu7qjwNWxaubFBIyFJOakDBWrssmKNf0cCwubHC56YFxbaXMzb5x]]></MediaId>
            //                </xml>';
            //        $xml_obj=simplexml_load_string($xml_str);
            //        echo $tousername=$xml_obj->ToUserName;
            //        echo '<br>';
            //        echo $fromusername=$xml_obj->FromUserName;
            //        echo '<br>';
            //        echo $msgtype=$xml_obj->MsgType;
            //        echo '<br>';
            //        echo $msgid=$xml_obj->MsgId;
            //        echo '<br>';
            //        echo $mediaid=$xml_obj->MediaId;
            //        echo '<br>';
            //        echo $picurl=$xml_obj->PicUrl;
            //        echo '<br>';
                    echo $this->access_token;exit;
        //第二阶段
        $url='https://api.weixin.qq.com/cgi-bin/media/get?access_token=28_ls7xK117wHaDkn-BmlLtnJsn6YfwndhPiC3KnuTvq0plXUMEst_1AFQXz3T7FLu_g22hbYK_IcihpTB7vsBb-h73dOvkhPtWmeqLXB_CLdG_5-eefLJtI3WU1nQH__7-0jM3ScLjaqslSE-iOEPaAJAVLW&media_id=PBxFlf4TA2_Dyu7qjwNWxaubFBIyFJOakDBWrssmKNf0cCwubHC56YFxbaXMzb5x';
        file_get_contents($url);exit;
//        $client = new Client();
//        $aaa=$client->request('GET',$url)->getBody('headers');
        $aaa=file_put_contents('ddd.jpeg',file_get_contents($url));
//        echo '下载成功';







}

}
