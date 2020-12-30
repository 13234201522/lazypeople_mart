<?php


namespace app\api\validate;


use think\Validate;

class UserValidate extends Validate
{
    protected $rule = [
        'nickname|昵称' => 'require',
        'avatar|头像' => 'require',
        'code|验证码' => 'require',
        'mobile|手机号' => 'require',
        'user_coupon_id|用户优惠券id' => 'require',
        'type|订单类型' => 'require|in:all,0,1,2,3',
        'page|当前页' => 'require',
        'goods_id|商品id' => 'require',
    ];

    protected $message = [

    ];

    protected $scene = [
        'edit_mobile' => ['mobile'],
        'my_coupon_del' => ['user_coupon_id'],
        'user_footprint' => ['page'],
        'footprint_del' => ['goods_id'],
    ];
}