<?php


namespace app\api\model;


use think\Model;
use traits\model\SoftDelete;

class Goods extends Model
{

    use SoftDelete;

    protected $deleteTime = 'deletetime';

    protected $append = [
        'specs_text',
        'seckill_status_text',
        'seckill_starttime_text',
        'total_stock_text',
    ];

    //多图获取器
    public function getImagesAttr($value)
    {
        $arr = explode(',',$value);
        foreach ($arr as $key => $value) {
            $arr[$key] =cdnurl($value);
        }
        return $value ? $arr : [];
    }

    //富文本图片拼接全路径
    public function getDetailAttr($value)
    {
        return $value ? contentImageUrl($value) : '';
    }

//    //商品规格获取器
//    public function getJsonAttr($value)
//    {
//        $value = $value ? ewArrSort(json_decode( $value,true),'price','asc') : [];
//
//        return $value ? $value : [];
//    }

    //秒杀状态
    public function getSeckillStatusTextAttr($value,$data)
    {
        $goods_id = $data['id'];
        return goodsSeckillStatus($goods_id)['seckill_status'];
    }

    public function getSeckillStarttimeTextAttr($value,$data)
    {
        $goods_id = $data['id'];
        $seckill_status =  goodsSeckillStatus($goods_id)['seckill_status'];
        $seckill_id =  goodsSeckillStatus($goods_id)['seckill_id'];

        $model_seckill = new Seckill();
        $seckill = $model_seckill->where(['id'=>$seckill_id])->find();

        return $seckill_status != 2 ? $seckill['starttime_text'] : '';
    }

    public function getTotalStockTextAttr($value,$data)
    {
        $json = isset($data['json']) ? $data['json'] : [];
    
        $json = $json ? ewArrSort(json_decode( $json,true),'price','asc') : [];
        $value = 0;
        foreach ($json as $val) {
            $value += (int)$val['stock'];
        }

        return $value;

    }



    // 商品规格详情
    public function getSpecsTextAttr($value,$data)
    {
        $goods_id = $data['id'];
        $vip_switch = isset($data['vip_switch']) ? $data['vip_switch'] : '';
        if ($vip_switch === '') {
            return [];
        }
        $json = isset($data['json']) ? $data['json'] : [];
        $json = $json ? ewArrSort(json_decode( $json,true),'price','asc') : [];
        foreach ($json as $key => $val) {
            $val['specs_image'] = $val['specs_image'] ? cdnurl($val['specs_image']) : '';
            $val['vip_price'] = $vip_switch ? vipPrive($val['price']) : $val['price'];
            $val['seckill_price'] = 0;
            $json[$key] = $val;
        }

        //判断当前商品秒杀状态
        $seckill = goodsSeckillStatus($goods_id);
        $seckill_status = $seckill['seckill_status'];
        $seckill_id = $seckill['seckill_id'];
        if ($seckill_status != 2) {
            //查询商品规格
            $model_seckill_goods = new SeckillGoods();
            $seckill_json = $model_seckill_goods->where(['seckill_id'=>$seckill_id,'goods_id'=>$goods_id])->find()['seckill_specs_text'];

            foreach ($json as $key => $value) {
                foreach ($seckill_json as $kk => $vv) {
                    if ($value['specs'] == $vv['specs']) {
                        $value['seckill_price'] = $vv['seckill_price'];
                    }
                }
                $json[$key] = $value;
            }
        }

        return $json;
    }
}