<?php

/**
 * Class 登录验证器
 * Author: @YJQ@
 */

namespace app\api\validate;


use think\Validate;

class LoginValidate extends Validate
{
    protected $rule = [
        'mobile|手机号' => 'require|^1\d{10}$',
        'avatar|头像' => 'require',
        'nickname|昵称' => 'require',
        'openid|微信唯一标识' => 'require',
    ];

    protected $message = [

    ];

    protected $scene = [
        'third_login' => ['avatar','nickname','openid'],
        'bind_mobile' => ['mobile'],
    ];
}