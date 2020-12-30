<?php


namespace app\api\validate;


use think\Validate;

class IndexValidate extends Validate
{
    protected $rule = [
        'page|当前页' => 'require',
        'keywords|搜索关键词' => 'require',
    ];

    protected $message = [

    ];

    protected $scene = [
        'index' => ['page'],
    ];
}