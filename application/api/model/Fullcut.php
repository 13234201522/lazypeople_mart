<?php

namespace app\api\model;

use think\Model;
use traits\model\SoftDelete;

class Fullcut extends Model
{

    use SoftDelete;

    

    // 表名
    protected $name = 'fullcut';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';
    
}