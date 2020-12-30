<?php


namespace app\api\model;


use think\Model;
use traits\model\SoftDelete;

class Coupon extends Model
{
    use SoftDelete;

    protected $deleteTime = 'deletetime';
    
    protected $append = [
        'starttime_text',
        'endtime_text',
        'surplus_text',
    ];



    public function getStarttimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['starttime']) ? $data['starttime'] : '');
        return is_numeric($value) ? date("Y-m-d", $value) : $value;
    }


    public function getEndtimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['endtime']) ? $data['endtime'] : '');
        return is_numeric($value) ? date("Y-m-d", $value) : $value;
    }

    //剩余可抢
    public function getSurplusTextAttr($value, $data)
    {
        $total_num = $data['total_num'];
        $surplus_num = $data['surplus_num'];
        if ($surplus_num * 100/$total_num > 99 && $surplus_num * 100/$total_num < 100) {
            $surplus_percent = 99;
        } elseif ($surplus_num * 100/$total_num >0 && $surplus_num * 100/$total_num < 1) {
            $surplus_percent = 1;
        } else {
            $surplus_percent = ceil($surplus_num * 100/$total_num);
        }
        
        return $surplus_percent;

    }
}