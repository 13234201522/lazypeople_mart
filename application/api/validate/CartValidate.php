<?php


namespace app\api\validate;


use think\Validate;

class CartValidate extends Validate
{
    protected $rule = [
        'cart_id|购物车id' => 'require',
        'cart_ids|购物车ids' => 'require',
        'goods_id|商品id' => 'require',
        'specs|规格' => 'require',
        'price|价格' => 'require',
        'num|数量' => 'require',
    ];

    protected $message = [

    ];

    protected $scene = [
        'add_cart' => ['goods_id','specs','price','num'],
        'index' => [''],
        'edit_specs' => ['cart_id','specs','price','num'],
        'num_plus' => ['cart_id'],
        'num_dec' => ['cart_id'],
    ];
}