<?php


namespace app\api\model;


use think\Model;

class SeckillGoods extends Model
{
    protected $append = [
        'seckill_specs_text'
    ];

    //关联商品信息
    public function goodsBrief()
    {
        return $this->belongsTo('Goods', 'goods_id', 'id', [], 'LEFT')->field('id,name,images');
    }

    // 商品规格详情
    public function getSeckillSpecsTextAttr($value,$data)
    {
        $json = isset($data['seckill_json']) ? $data['seckill_json'] : [];
        $json = $json ? ewArrSort(json_decode( $json,true),'price','asc') : [];

        return $json;
    }


}