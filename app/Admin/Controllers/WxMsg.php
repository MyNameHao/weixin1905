<?php

namespace App\Admin\Controllers;

use App\Model\TextModel;
use App\Model\WeixinUser;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Redis;

class WxMsg extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\Model\TextModel';


    public function sendMsg(){
        $key='wx_access_token';
        $access_token=Redis::get($key);
        $openid_array=WeixinUser::select('openid')->get()->toarray();
        $openid=array_column($openid_array,'openid');
        $msg=date('Y-m-d H:i:s').'Are you ready';
        $url='https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token='.$access_token;
        $data=[
            'touser'=>$openid,
            'msgtype'=>'text',
            'text'=>['content'=>$msg]
        ];
        $client=new Client();
        $reponse=$client->request('POST',$url,[
            'body'=>json_encode($data,JSON_UNESCAPED_UNICODE)
        ]);
        echo $reponse->getBody();
    }
}
