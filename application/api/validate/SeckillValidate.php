<?php


namespace app\api\validate;


use think\Validate;

class SeckillValidate extends Validate
{
    protected $rule = [
        'page|当前页' => 'require',
    ];

    protected $message = [

    ];

    protected $scene = [
        'index' => ['page'],
    ];
}