<?php


namespace app\api\model;


use think\Model;

class OrderDetail extends Model
{

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    // 追加属性
    protected $append = [
        'refund_id_text',
    ];


    public function getImageAttr($value)
    {
        return $value ? cdnurl($value) : '';
    }

    public function goods()
    {
        return $this->belongsTo('Goods', 'goods_id', 'id', [], 'LEFT')->bind('name');
    }
    
    
    public function getRefundIdTextAttr($value,$data)
    {
        $model = new OrderRefund();
        $id = '';
        //获取当前订单在退款记录的id最新记录
        if ($data['status'] == 2) {
            //获取
            $id = $model->where('detail_id',$data['id'])->order('id','desc')->value('id');
        }
        return $id;
    }
}
