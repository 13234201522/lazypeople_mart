<?php


namespace app\api\model;


use think\Model;
use traits\model\SoftDelete;

/**
 * 地址
 * Class Address
 * @package app\api\model
 */
class Address extends Model
{

    use SoftDelete;


    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    protected $append = [
        'postage_text',
        'tax_text'
    ];

    //获取邮费信息
    public function getPostageTextAttr($value,$data)
    {
        $value = ['name'=>'','fixed_price'=>0,'price'=>0,'type'=>''];
        $model_postage = new Postage();
        $model_postage_city = new PostageCity();
        //当前城市place_id
        $city_place_id = $data['city_place_id'];
        $postage_id = $model_postage_city->where(['place_id'=>$city_place_id])->value('postage_id');
        if ($postage_id) {
            //获取当前
            $postage = $model_postage->where(['id'=>$postage_id])->find();
            if ($postage) {
                $value = [
                    'name' => $postage['name'],
                    'fixed_price' => $postage['fixed_price'],
                    'price' => $postage['price'],
                    'type'=>$postage['type'],
                ];
            }
        }
        return $value;
    }

    //获取税费
    public function getTaxTextAttr($value,$data)
    {
        $value = ['pax'=>0];
        $model_tax = new ProvinceTax();
        //当前城市place_id
        $province_place_id = $data['province_place_id'];
        //获取当前
        $tax_rate = $model_tax->where(['place_id'=>$province_place_id])->value('tax_rate');
        return $tax_rate ? $tax_rate : 0;

    }

}
