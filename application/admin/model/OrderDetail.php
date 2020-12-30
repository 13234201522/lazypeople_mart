<?php

namespace app\admin\model;

use think\Model;


class OrderDetail extends Model
{

    

    

    // 表名
    protected $name = 'order_detail';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [

    ];
    

    







    public function goods()
    {
        return $this->belongsTo('Goods', 'goods_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }

    public function order()
    {
        return $this->belongsTo('Order', 'order_id', 'id', [], 'LEFT');
    }
}
