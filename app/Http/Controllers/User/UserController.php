<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Model\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use GuzzleHttp\Client;


class UserController extends Controller
{
    public function adduser(){
        echo md5(115183);
        phpinfo();
        $data=[
            'user_name'=>'张三',
            'password'=>password_hash('123456abc',PASSWORD_BCRYPT),
            'email'=>'123123123@qq.com'
        ];
        $id=UserModel::insertGetId($data);
      print_r(UserModel::where('uid',$id)->first()->toarray());
        echo UserModel::count();
    }
    public function deluser(){
        $res=UserModel::where('uid','9')->delete();
        echo UserModel::count();
    }
    public function upuser(){
        $data=[
            'user_name'=>'李四',
            'password'=>password_hash('aaaaaa',PASSWORD_BCRYPT),
            'email'=>'2@qq.com'
        ];
        $res=UserModel::where('uid',5)->update($data);
        print_r(UserModel::where('uid',5)->first()->toarray());
        echo UserModel::count();
    }
    public function index(){
        $info=UserModel::get()->toarray();
        print_r($info);
    }
    public function redis1(){
        Redis::set('aaa','aaaa');
        echo Redis::get('aaa');
    }
    public function qishou()
    {
        $url='http://www.baidu.com/';
        $client = new Client();
        $aaa=$client->request('GET',$url);
        echo $aaa->getBody();
    }
    public function fanyi(){
        $url='https://fanyi.baidu.com/';
        $client = new Client();
        $aaa=$client->request('GET',$url);
        echo $aaa->getBody();
    }
}
