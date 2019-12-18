<?php

namespace App\Http\Controllers\Wx;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;
use App\Model\WeixinUser;
use App\Model\TextModel;
use App\Model\ImgModel;
use App\Model\VoiceModel;
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
        $url='https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid=oOWCkwpc0xrL17uauyKckwF4qaKI=&lang=zh_CN';
        $json=json_decode(file_get_contents($url),true);
        $aa=array_key_exists('errcode',$json);
        if($aa){
            $errcode=true;
            if($json['errcode']==40001||$json['errcode']==42001){
                $errcode=false;
            }
        }
        if($access_token&&$errcode){
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
     * 处理接入---绑定token值
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
    /*
     * 处理接入---接收推送的数据
     * */
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
            }elseif($xml_obj->Event=='CLICK'){              //判断点击手册
                    if($xml_obj->EventKey=='yitian'){
                        $token=$this->access_token;
                        $openid=$xml_obj->FromUserName;
                        $json_str=$this->GetUserInfo($token,$openid);
                        $url='https://free-api.heweather.net/s6/weather/now?location=beijing&key=c112fe6655584d8383d2fffd67cabc4a';
                        $json_str=file_get_contents($url);
                        $json_arr=json_decode($json_str,true)['HeWeather6']['0']['now'];
                        $location=json_decode($json_str,true)['HeWeather6']['0']['basic']['location'];
                        $data='所查城市：'.$location."\n天气状况：".$json_arr['cond_txt']."\n温度：".$json_arr['tmp']."\n风力：".$json_arr['wind_sc']."级\n风向：".$json_arr['wind_dir']."\n风速：".$json_arr['wind_spd'];

                        $this->respond($xml_obj,4,$json_str,$data);
                    }elseif($xml_obj->EventKey=='jinjitian'){
                        $token=$this->access_token;
                        $openid=$xml_obj->FromUserName;
                        $json_str=$this->GetUserInfo($token,$openid);
                        $this->respond($xml_obj,4,$json_str,"周一:晴天\n周二:雨天\n周三:多云\n周四:晴天\n周五:晴天\n周六:晴天\n周日:阴天");
                    }
            }
        }

        //收到文字类消息回复并且入库
        if($xml_obj->MsgType=='text'){
            $token=$this->access_token;
            $openid=$xml_obj->FromUserName;
            //调用方法---获取客户信息
            $json_str=$this->GetUserInfo($token,$openid);

            $json_arr=json_decode($json_str,true);
                //调用方法---收到信息自动回复
               $this->respond($xml_obj,1,$json_str);

            //入库操作
            $textdata=[
                'openid'=>$openid,
                'content'=>$xml_obj->Content,
                'nickname'=>$json_arr['nickname']
            ];
            $tid=TextModel::insertGetId($textdata);
        }

        //收到图片类进行消息下载
        if($xml_obj->MsgType=='image'){
            $token=$this->access_token;
            $media_id=$xml_obj->MediaId;
            $openid=$xml_obj->FromUserName;

            //调用方法--获取文件夹名称
            $paperfile=$this->paperfile($xml_obj,'image');

            //调用方法--获取后缀名
           $fromat=$this->fromat($media_id);

            //调用方法---下载文件
            $filename=$this->download($paperfile,$fromat,$media_id);

            //调用方法---获取客户信息
            $json_str=$this->GetUserInfo($token,$openid);
            $json_arr=json_decode($json_str,true);

            //图片路径入库
            $imgdata=[
                'openid'=>$openid,
                'img'=>$filename,
                'nickname'=>$json_arr['nickname']
            ];
            $tid=ImgModel::insertGetId($imgdata);

            //调用方法---接收成功向客户发送路径
            $this->respond($xml_obj,4,$json_str,'图片已接收在:'.$filename);

        }

        //收到语音类进行消息下载
        if($xml_obj->MsgType=='voice'){
            $token=$this->access_token;
            $media_id=$xml_obj->MediaId;
            $openid=$xml_obj->FromUserName;
            //调用方法---文件夹名称
            $paperfile=$this->paperfile($xml_obj,'voice');

            //调用方法--获取后缀名
            $fromat=$this->fromat($media_id);

            //调用方法---下载文件
            $filename=$this->download($paperfile,$fromat,$media_id);
            //调用方法---获取客户信息
            $json_str=$this->GetUserInfo($token,$openid);
            $json_arr=json_decode($json_str,true);

            //语音路径入库
            $imgdata=[
                'openid'=>$openid,
                'voice'=>$filename,
                'nickname'=>$json_arr['nickname']
            ];
            $tid=VoiceModel::insertGetId($imgdata);

            //调用方法---接收完成向客户发送路径
            $this->respond($xml_obj,4,$json_str,'语音已接收在:'.$filename);
        }

        //收到视频类进行消息下载
        if($xml_obj->MsgType=='video'){
            $token=$this->access_token;
            $media_id=$xml_obj->MediaId;
            $openid=$xml_obj->FromUserName;

            //调用方法---文件夹名称
            $paperfile=$this->paperfile($xml_obj,'video');

            //调用方法--获取后缀名
            $fromat=$this->fromat($media_id);

            //调用方法---下载文件
            $filename=$this->download($paperfile,$fromat,$media_id);

            //调用方法---获取客户信息
            $json_str=$this->GetUserInfo($token,$openid);

            //调用方法---接收完成向客户发送路径
            $this->respond($xml_obj,4,$json_str,'视频已接收在:'.$filename);
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
        }elseif($code==4){
            $content=$weixininfo;
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

    /*
     * 获取文件后缀名
     * */
    public function fromat($media_id){
        $client = new Client();
        $url='https://api.weixin.qq.com/cgi-bin/media/get?access_token='.$this->access_token.'&media_id='.$media_id;
        $format=$client->request('GET',$url)->getHeader('Content-disposition')[0];
        $format=trim(substr($format,strpos($format,'.')+1),'\"');
        return '.'.$format;
    }

    /*
     * 获取文件夹名称
     * */
    public function paperfile($xml_obj,$format){

//        $paperfile='paperfile/video/'.date('Ymd').'/';
//        if(!is_dir($paperfile)){
//            mkdir($paperfile,0777,true);
//        }
//        if(is_dir($paperfile)){
//            $paperfile=$paperfile.$openid=$xml_obj->FromUserName.'/';
//            mkdir($paperfile,0777,true);
//        }else{
//            $paperfile='paperfile/image/errorpath/';//备用存放处
//        }

        $paperfile='paperfile/'.$format.'/'.date('Ymd').'/'.$openid=$xml_obj->FromUserName.'/';
        if(!is_dir($paperfile)){
            mkdir($paperfile,0777,true);
            return $paperfile;
        }else{
            return $paperfile;
        }
    }

    /*
     * 下载文件
     * */
    public function download($paperfile,$fromat,$media_id){
        $filename=$paperfile.'weixin_'.date('YmdHs').'_'.rand('1000','9999').$fromat;
        $url='https://api.weixin.qq.com/cgi-bin/media/get?access_token='.$this->access_token.'&media_id='.$media_id;
        file_put_contents($filename,file_get_contents($url));
        return $filename;
    }

    /*
     * 创建自定义菜单
     * */
    public function createMeun(){
        $url='https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$this->access_token;
        $meun=[
            'button'=>
                [
                    [
                        'type'=>'click',
                        'name'=>'今日天气',
                        'key'=>'yitian',
                    ],
                    [
                        'type'=>'click',
                        'name'=>'近日天气',
                        'key'=>'jinjitian',
                    ],
                    [
                        'name'=>'菜单',
                        'sub_button'=>[
                            [
                                'type'=>'click',
                                'name'=>'点个赞呗',
                                'key'=>'dianzan'
                            ],
                            [
                                'type'=>'view',
                                'name'=>'投票',
                                'url'=>'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx4fdcb23b1ce7f2c6&redirect_uri='.urlEncode($url).'&response_type=code&scope=snsapi_userinfo&state=ABCD1905#wechat_redirect'
                            ]
                        ]
                    ]

                ]
        ];
        $json_meun=json_encode($meun,JSON_UNESCAPED_UNICODE);
        $client=new Client();
        $aaa=$client->request('POST',$url,[
            'body'=>$json_meun
        ]);
        echo $aaa->getBody();
    }

    /*
     * 接受网页回调---code
     * */
    public function vote(){
        $url='http://1905sunhao.comcto.com/vote';
        $url='https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx4fdcb23b1ce7f2c6&redirect_uri='.urlEncode($url).'&response_type=code&scope=snsapi_userinfo&state=ABCD1905#wechat_redirect';
        echo $url;
    }






    /*
     * 以下均是测试代码；
     * */

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
        $meun=[
            'button'=>
                [
                    [
                        'type'=>'click',
                        'name'=>'一级菜单',
                        'key'=>'yijicaidan',
                    ],
                    [
                        'name'=>'二级菜单',
                        'sub_button'=>
                            [
                                [
                                    'type'=>'view',
                                    'name'=>'搜索',
                                    'url'=>'http://www.soso.com/'
                                ],
                                [
                                    'type'=>'click',
                                    'name'=>'点个赞呗',
                                    'diangezanbei'
                                ]
                            ]

                    ]
                ]
        ];
        echo json_encode($meun);exit;
        $json_str='{
     "button":[
     {
          "type":"click",
          "name":"今日歌曲",
          "key":"V1001_TODAY_MUSIC"
      },
      {
           "name":"菜单",
           "sub_button":[
           {
               "type":"view",
               "name":"搜索",
               "url":"http://www.soso.com/"
            },
            {
                 "type":"miniprogram",
                 "name":"wxa",
                 "url":"http://mp.weixin.qq.com",
                 "appid":"wx286b93c14bbf93aa",
                 "pagepath":"pages/lunar/index"
             },
            {
               "type":"click",
               "name":"赞一下我们",
               "key":"V1001_GOOD"
            }]
       }]
 }';
//        $json_arr=json_decode($json_str,true);
       dump($json_str);
        $wx_arr=[
            'button'=>[
                [
                    'type'=>'click',
                    'name'=>'今日分享',
                    'key'=>'fenxiang',
                ],
                [
                    'name'=>'菜单',
                    'sub_button'=>
                    [
                        [
                            'type'=>'click',
                            'name'=>'今日天气',
                            'key'=>'tianqi'
                        ],
                        [
                            'type'=>'click',
                            'name'=>'近期天气',
                            'key'=>'zuijintianqi'
                        ],

                    ]

                ]
            ]
        ];
        dd(json_encode($wx_arr));
//        $json_str=json_decode('{"subscribe":1,"openid":"oOWCkwpc0xrL17uauyKckwF4qaKI","nickname":"ㅤㅤ未成年ㅤㅤ","sex":1,"language":"zh_CN","city":"邯郸","province":"河北","country":"中国","headimgurl":"http:\/\/thirdwx.qlogo.cn\/mmopen\/XM70P5zXcpykDTzCd8tHicXnMib9Q7zGSUrIdXjWzl1KNohkPLFHaUmpx3ndWfoT638pntdPkUcOOk38rIY4UhX3JW0Y7uqoMH\/132","subscribe_time":1576059545,"remark":"","groupid":0,"tagid_list":[],"subscribe_scene":"ADD_SCENE_QR_CODE","qr_scene":0,"qr_scene_str":""}',true);
//        $data=[
//            'openid'=>$json_str['openid'],
//            'sub_time'=>$json_str['subscribe_time'],
//            'sex'=>$json_str['sex'],
//            'nickname'=>$json_str['nickname'],
//        ];
//        dd($data);
    }
    public function tupianceshi(){
        $json_str='{"HeWeather6":[{"basic":{"cid":"CN101010100","location":"北京","parent_city":"北京","admin_area":"北京","cnty":"中国","lat":"39.90498734","lon":"116.4052887","tz":"+8.00"},"update":{"loc":"2019-12-17 20:09","utc":"2019-12-17 12:09"},"status":"ok","now":{"cloud":"0","cond_code":"100","cond_txt":"晴","fl":"-4","hum":"27","pcpn":"0.0","pres":"1029","tmp":"1","vis":"16","wind_deg":"3","wind_dir":"北风","wind_sc":"3","wind_spd":"15"}}]}';
        $json_arr=json_decode($json_str,true);
        dd($json_arr);



//        echo "<img src='paperfile/image/20191216/oOWCkwoKZDr-hYmGU3Yp06nuJMgI/weixin_201912161907_5304.jpg'>";
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
    public function ceshi2(){
        echo 'APPID:'.env('APPID')."<br><br>APPSECRE:".env('APPSECRE');
        echo '<br><br>';
        echo 'access_token：'.$this->access_token;
    }

}
