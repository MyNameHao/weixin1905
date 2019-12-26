<?php

namespace App\Http\Controllers\test;

use App\Http\Controllers\Controller;
use App\Model\WeixinUser;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function test(){
//        $filename='test_wx.log';
        $xml=file_get_contents("php://input");
        $xml_obj=simplexml_load_string($xml);
        //判断消息类型---判断消息类型是否等于事件（event）
        if($xml_obj->MsgType=='event'){
            //判断事件类型---判断事件是否为关注事件
            if($xml_obj->Event=='subscribe'){
                //获取openid
                    $openid=$xml_obj->FromUserName;
                //查库查询本地是否为新用户
                $userinfo=WeixinUser::where('openid',$openid)->first();
                //判断用户是否是新用户
                if($userinfo){
                    //回复欢迎回来
                    echo '欢迎回来';
                }else{
                    //使用get访问| |https://api.weixin.qq.com/cgi-bin/user/info?access_token=ACCESS_TOKEN&openid=OPENID&lang=zh_CN  ||获取用户信息
                    $user_json='{
                                "subscribe": 1,
                                "openid": "oOWCkwpc0xrL17uauyKckwF4qaKI",
                                "nickname": "ㅤㅤ未成年ㅤㅤ",
                                "sex": 1,
                                "language": "zh_CN",
                                "city": "邯郸",
                                "province": "河北",
                                "country": "中国",
                                "headimgurl": "http://thirdwx.qlogo.cn/mmopen/XM70P5zXcpykDTzCd8tHicXnMib9Q7zGSUrIdXjWzl1KNohkPLFHaUmpx3ndWfoT638pntdPkUcOOk38rIY4UhX3JW0Y7uqoMH/132",
                                "subscribe_time": 1577173295,
                                "remark": "",
                                "groupid": 0,
                                "tagid_list": [],
                                "subscribe_scene": "ADD_SCENE_SEARCH",
                                "qr_scene": 0,
                                "qr_scene_str": ""
                            }';
                    $userdata=json_decode($user_json,true);
                    dd($userdata);
                }
                //判断当前是佛是点击菜单事件
            }elseif($xml_obj->Event=='CLICK'){
                //判断当前是否是天气事件
                if($xml_obj->EventKey=='yitian'){
                    $url='https://free-api.heweather.net/s6/weather/now?location=handan&key=c112fe6655584d8383d2fffd67cabc4a';
                    $tianqi_json=file_get_contents($url);
                    $tianqi_str=json_decode($tianqi_json,true);
                    echo $tianqi_str['HeWeather6'][0]['now']['cond_txt'];
                }
            }
        }elseif($xml_obj->MsgType=='text'){
            //如果是文字将信息返回并且将信息入库,
            echo '文字信息';
        }elseif($xml_obj->MsgType=='voice'){
                //如果是语音消息将通过网址|https://api.weixin.qq.com/cgi-bin/media/get?access_token=ACCESS_TOKEN&media_id=MEDIA_ID|和mediaid获取语音文件,将语音文件放到本地
            echo '语音消息';
        }elseif($xml_obj->MsgType=='video'){
            //如果是视频消息将通过网址|https://api.weixin.qq.com/cgi-bin/media/get?access_token=ACCESS_TOKEN&media_id=MEDIA_ID|和mediaid获取视频文件,将视频文件放到本地
            echo '视频消息';
        }elseif($xml_obj->MsgType=='image'){
            //文件下载实例代码
            $mediaid= $xml_obj->MediaId;
            $access_token='28_KMRnvsmnCcHwqkQ2fXCB_ipCR0TYbS2DjXAgJ3hv12ZXl1JdQ-DrUs3cR39glGxdeeNG3wgxtbY_9X0o9vNmlS8Xu8BaIguWAKCtEOT1XFIyQD0106GxBc00T53hvkrrRX3Mbvzvfs4rs0qbUDMgABAKTC';
            $url='https://api.weixin.qq.com/cgi-bin/media/get?access_token='.$access_token.'&media_id='.$mediaid;
            $imgname=time().'.jpeg';
            file_put_contents($imgname,file_get_contents($url));
        }
    }
    public function wxqr(){
        //通过post请求获取ticket值
        $url='https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=28_Tm2bFcEIjaCxS0xk_dM2lWqSpWyU70Tr6k1H5xbSUylXVt1SNMMFyAmPvyUFs0lddGKtvyMdudLxjGa4OdYhiP7ByW0a75gnSy94jpv5gRMLAJG2deG14P4hKv3WdcoBoxIu3sUh-xbpvjzHPYKhABAAJV';
        //请求上面代码所需的参数
            $data=[
                'expire_seconds'=>2592000,
                'action_name'=>'QR_STR_SCENE',
                'action_info'=>[
                    'scene'=>[
                        'scene_id'=>'1231234312332'
                    ]
                ]

            ];
        $client=new Client();
        $huidiao=$client->request('POST',$url,[
            'body'=>json_encode($data)
        ]);
        $ticket=json_decode($huidiao->getBody(),true)['ticket'];
        $url1='https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.urlencode($ticket);
            return redirect($url1);
//        dd($json_str);
    }
    //节日首页
    public function festival(){
        $code=$_GET['code'];
    }
}
