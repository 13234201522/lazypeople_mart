<?php


namespace app\api\model;


use think\Db;
use think\Exception;
use think\Model;
use function fast\array_except;

class Order extends Model
{


    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';


    protected $append = [
        'createtime_text',
        'paytime_text',
    ];


    //订单详情
    public function orderDetail()
    {
        // return $this->hasMany('OrderDetail', 'order_id', 'id', [], 'LEFT')->with('goods')->where('status',1);
        return $this->hasMany('OrderDetail', 'order_id', 'id', [], 'LEFT')->with('goods');
    }

    //地址
    public function address()
    {
        return $this->belongsTo('Address', 'address_id', 'id', [], 'LEFT');
    }

    //下单时间
    public function getCreatetimeTextAttr($value,$data)
    {

        $value = $value ? $value : (isset($data['createtime']) ? $data['createtime'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    //支付时间
    public function getPaytimeTextAttr($value,$data)
    {

        $value = $value ? $value : (isset($data['paytime']) ? $data['paytime'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    //取消订单
    public function cancelOrder($order_ids)
    {
        $model_detail = new OrderDetail();
        $model_goods = new Goods();
        $model_user_coupon = new UserCoupon();

        Db::startTrans();
        try {
            //1.修改当前订单状态
            $res = $this->where(['id'=>['in',$order_ids]])->update(['status'=>4, 'updatetime'=>time()]);
            //2.返还库存
            $order_details = $model_detail->where(['order_id'=>['in',$order_ids]])->select();
            //获取所有被购买的商品
            foreach ($order_details as $value) {
                $goods = $model_goods::get($value['goods_id'])->getData();
                $specs = json_decode($goods['json'],true);
                foreach ($specs as $key => $val) {
                    if ($val['specs'] == $value['specs']) {
                        $val['stock'] += $value['num'];
                    }
                    $specs[$key] = $val;
                }
                $json = json_encode($specs);
                $model_goods->where(['id'=>$value['goods_id']])->update(['json'=>$json,'updatetime'=>time()]);
            }
            //3.如有使用优惠券,返还优惠券
            $user_coupon_id = $this->where('id',$order_ids)->value('user_coupon_id');
            if ($user_coupon_id) {
                //将当前用户优惠券
                $model_user_coupon->where(['id'=>$user_coupon_id])->update(['use_status'=>0,'updatetime'=>time()]);
            }

            Db::commit();
        } catch (Exception $e) {
            Db::rollback();
            return false;
        }
        return true;
    }

    /**
     * 确认收货
     */
    public function receive($order_ids)
    {

        Db::startTrans();
        try {

            //1.修改当前订单状态
            $res = $this->where(['id'=>['in',$order_ids]])->update(['status'=>3, 'updatetime'=>time()]);

            Db::commit();
        } catch (Exception $e) {
            Db::rollback();
            return false;
        }
        return true;
    }
}
