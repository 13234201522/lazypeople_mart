<?php

namespace app\admin\model;

use think\Model;
use traits\model\SoftDelete;

class Banner extends Model
{

    use SoftDelete;

    

    // 表名
    protected $name = 'banner';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    // 追加属性
    protected $append = [
        'type_text',
        'status_text',
        'link_url_text'
    ];


    protected static function init()
    {
        self::afterInsert(function ($row) {
            $pk = $row->getPk();
            $row->getQuery()->where($pk, $row[$pk])->update(['weigh' => $row[$pk]]);
        });
    }





    public function getTypeList()
    {
        return ['seckill' => __('Type seckill'), 'coupon' => __('Type coupon'), 'goods' => __('Type goods'), 'fullcut' => __('Type fullcut'), 'category' => __('Type category'), 'vip' => __('Type vip'), 'other' => __('Type other')];
    }

    public function getStatusList()
    {
        return ['normal' => __('Status normal'), 'hidden' => __('Status hidden')];
    }


    public function getLinkUrlTextAttr($value, $data)
    {
        $type = $data['type'];
        $link_url = $data['link_url'];
        switch ($type) {
            case "seckill" : //1.秒杀专区
                $text = '';
                break;
            case "coupon" : //2.领券中心
                $text = '';
                break;
            case "goods" : //3.商品详情
                //获取商品名称
                $model_goods = new Goods();
                $text = $model_goods->where('id',$link_url)->value('name');
                break;
            case "fullcut" : //4.满减列表
                $model_fullcut = new Fullcut();
                $text = $model_fullcut->where('id',$link_url)->value('name');
                break;
            case "category" : //5.商品列表(某个二级分类下的)
                //获取商品名称
                $model_category = new Category();
                $text = $model_category->where('id',$link_url)->value('name');
                break;
            case "vip" : //6.会员卡
                $text = '';
                break;
            case "other" : //7.外链
                $text = $link_url;
                break;
        }
        return $text;
    }


    public function getTypeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['type']) ? $data['type'] : '');
        $list = $this->getTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }




}
