<?php

namespace app\admin\model;

use think\Model;


class Seckill extends Model
{

    

    

    // 表名
    protected $name = 'seckill';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'seckilltime_text',
        'starttime_text',
        'endtime_text',
        'status_text'
    ];
    

    
    public function getStatusList()
    {
        return ['0' => __('Status 0'), '1' => __('Status 1'), '2' => __('Status 2')];
    }


    public function getSeckilltimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['seckilltime']) ? $data['seckilltime'] : '');
        return is_numeric($value) ? date("Y-m-d", $value) : $value;
    }


    public function getStarttimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['starttime']) ? $data['starttime'] : '');
        return is_numeric($value) ? $value.":00:00" : $value;
    }


    public function getEndtimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['endtime']) ? $data['endtime'] : '');
        return is_numeric($value) ? $value.":00:00" : $value;
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }

    protected function setSeckilltimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }
//
//    protected function setStarttimeAttr($value)
//    {
//        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
//    }
//
//    protected function setEndtimeAttr($value)
//    {
//        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
//    }


}
