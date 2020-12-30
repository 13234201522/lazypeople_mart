<?php


namespace app\api\model;


use think\Model;
use traits\model\SoftDelete;

class Category extends Model
{
    use SoftDelete;
    protected $deleteTime = 'deletetime';

    //图片获取器
    public function getImageAttr($value)
    {
        return $value ? cdnurl($value) : '';
    }

    //获取子分类
    public function sonList()
    {
        return $this->hasMany(Category::class,'pid','id')->where(['status'=>'normal'])->order('weigh','desc');
    }
}