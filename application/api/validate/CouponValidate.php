<?php


namespace app\api\validate;


use think\Validate;

class CouponValidate extends Validate
{
    protected $rule = [
        'coupon_id|优惠券id' => 'require',
        'page|当前页' => 'require',
    ];

    protected $message = [

    ];

    protected $scene = [
        'index' => ['page'],
        'receive' => ['coupon_id'],
    ];
}