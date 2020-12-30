<?php


namespace app\api\model;


use think\Model;

class Seckill extends Model
{
    protected $append = [
        'starttime_text',
        'endtime_text',
    ];

    //开始时间获取器
    public function getStarttimeTextAttr($value,$data)
    {
        return date('Y-m-d H:i:s',$data['starttime']);
    }

    //结束时间获取器
    public function getEndtimeTextAttr($value,$data)
    {
        return date('Y-m-d H:i:s',$data['endtime']);

    }
}