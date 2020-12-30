<?php


namespace app\api\model;


use think\Model;

class UserFootprint extends Model
{

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';

    protected $append = [
        'createtime_text'
    ];



    //关联商品信息
    public function goods()
    {
        return $this->belongsTo('Goods', 'goods_id', 'id', [], 'LEFT');
    }


    //下单时间
    public function getCreatetimeTextAttr($value,$data)
    {

        $value = $value ? $value : (isset($data['createtime']) ? $data['createtime'] : '');
        return is_numeric($value) ? date("Y-m-d", $value) : $value;
    }
}