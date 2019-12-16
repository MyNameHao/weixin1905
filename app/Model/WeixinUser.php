<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class WeixinUser extends Model
{
    protected $table='p_wx_users';
    protected $primaryKey='uid';
}
