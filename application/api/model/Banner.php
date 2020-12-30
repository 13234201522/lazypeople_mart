<?php


namespace app\api\model;


use think\Model;

class Banner extends Model
{

    //图片获取器
    public function getImageAttr($value)
    {
        return $value ? cdnurl($value) : '';
    }
}