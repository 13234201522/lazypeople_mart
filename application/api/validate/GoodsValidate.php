<?php


namespace app\api\validate;


use think\Validate;

class GoodsValidate extends Validate
{
    protected $rule = [
        'goods_id|商品id' => 'require',
        'page|当前页' => 'require',
        //'category_id|分类id' => 'require',
        'keywords|关键词' => 'require',
        'sort|排序方式' => 'require|in:normal,sales,price_high,price_low',
    ];

    protected $message = [

    ];

    protected $scene = [
        'by_category' => ['category_id','sort','page'],
        'info' => ['goods_id'],
    ];
}
