<?php

namespace App\Http\Controllers\Wx;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Model\WeixinUser;

class WxQRController extends Controller
{

    public function qrcode(){
        $scene=$_GET['scene'];
        $access_token=WeixinUser::GetAccess_Token();
        //获取ticket
        $data=[
            'expire_seconds'=>2592000,
            'action_name'=>'QR_STR_SCENE',
            'action_info'=>[
                'scene_id'=>$scene
            ]
        ];
        $json_data=json_encode($data);
        $url='https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$access_token;
        $client= new Client();
        $response=$client->request('POST',$url,[
            'body'=>$json_data
        ]);
        $json_ticket= $response->getBody();
        $ticket=json_decode($json_ticket,true)['ticket'];
        $url2='https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.urlencode($ticket);
        return redirect($url2);
    }
}
