<?php

namespace app\admin\model;

use think\Model;


class Postage extends Model
{

    

    

    // 表名
    protected $name = 'postage';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [

    ];

    public function getStatusList()
    {
        return ['快递' => '快递', '平台配送' => '平台配送'];
    }
    







}
