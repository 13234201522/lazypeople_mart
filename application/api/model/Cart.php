<?php


namespace app\api\model;


use think\Model;

class Cart extends Model
{


    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';

    protected $append = [
        'select_text',
    ];

    public function goods()
    {
        return $this->belongsTo('Goods', 'goods_id', 'id', [], 'LEFT');
    }

    //选中状态
    public function getSelectTextAttr($value,$data)
    {
        return 0;
    }
}