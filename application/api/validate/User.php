<?php

namespace app\api\validate;

use think\Validate;

class User extends Validate
{
    protected $rule = [
        "username|用户名" => "require|unique:user",
        "psw|密码"=>"require|min:6",
        "tel|手机号"=>"require|regex:[1][356789]\d{9}",
        "email|邮箱"=>"require|email",

    ];

}