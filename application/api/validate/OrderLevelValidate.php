<?php


namespace app\api\validate;


use think\Validate;

class OrderLevelValidate extends Validate
{
    protected $rule = [
        'level|会员等级' => 'require|in:silver,gold,masonry',
        'pay_state|支付方式' => 'require|in:wechat,card',
        'order_num|订单编号' => 'require',
    ];

    protected $message = [

    ];

    protected $scene = [
        'bug_level' => ['level','pay_state'],
        'order_check' => ['order_num'],
    ];
}
