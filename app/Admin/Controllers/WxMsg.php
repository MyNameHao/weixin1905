<?php

namespace App\Admin\Controllers;

use App\Model\TextModel;
use App\Model\WeixinUser;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use GuzzleHttp\Client;

class WxMsg extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\Model\TextModel';


    public function sendMsg(){
        $openid_array=WeixinUser::select('openid')->get()->toarray();
        $openid=array_column($openid_array,'openid');
        $msg=date('Y-m-d H:i:s').'Are you ready';
        $url='https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token=28_h_w5MxqVeINlO4XwfwjAC6e25hWOEzxROAf9Ch-SjnWSH7DlZCx9XqVrJwZZxClr4g1l7_wTVFwM6eAlC1LFw0H50_NugsauJuIjgkIM-fhcpaxeR5LZSb2-xd5HlXixrEsjJEnq40ty33hZGDEiAFACZA';
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
