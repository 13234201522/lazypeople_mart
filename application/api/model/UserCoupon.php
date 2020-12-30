<?php


namespace app\api\model;


use think\Model;
use traits\model\SoftDelete;

class UserCoupon extends Model
{

    use SoftDelete;

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    //优惠券
    public function coupon()
    {
        return $this->belongsTo('Coupon', 'coupon_id', 'id', [], 'LEFT');
    }

    //优惠券可用分类
    public function couponCategory()
    {
        return $this->belongsTo('Coupon', 'coupon_id', 'id', [], 'LEFT')->bind('category_ids');
    }

}