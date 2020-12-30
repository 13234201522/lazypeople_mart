<?php


namespace app\api\validate;


use think\Validate;

class OrderValidate extends Validate
{
    protected $rule = [
        'type|下单类型' => 'require|in:goods,cart',
        'ids|id' => 'require',
        'address_id|地址id' => 'require',
        'coupon_id|优惠券id' => 'require',
        'user_coupon_id|用户优惠券id' => 'require',
        'order_notes|订单备注' => 'require',
        'total_goods_price|商品总价' => 'require',
        'postage_price|运费' => 'require',
        'fullcut_price|满减金额' => 'require',
        'coupon_price|优惠券金额' => 'require',
        'reward_price|打赏金额' => 'require',
        'tax_price|税费金额' => 'require',
        'pay_price|实际支付金额' => 'require',
        'goods|商品信息' => 'require',
        'status|订单类型' => 'require|in:all,0,1,2,3,4,5',
        'page|当前页' => 'require',
        'order_id|订单id' => 'require',
        'order_num|订单编号' => 'require',
        'pay_state|支付方式' => 'require|in:wechat,card',
    ];

    protected $message = [

    ];

    protected $scene = [
        'page' => ['type','ids'],
        'submit' => ['address_id','total_goods_price','postage_price','fullcut_price','coupon_price','reward_price','tax_price','pay_price','goods'],
        'my_order' => ['status','page'],
        'info' => ['order_id'],
        'cancel' => ['order_id'],
        'receive' => ['order_id'],
        'pay_order' => ['order_num'],
        'check_order' => ['order_num'],
    ];
}
