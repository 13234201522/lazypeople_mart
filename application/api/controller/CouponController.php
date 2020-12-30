<?php


namespace app\api\controller;


use app\api\model\Coupon;
use app\api\model\UserCoupon;
use app\api\validate\CouponValidate;
use app\common\controller\Api;

/**
 * 优惠券
 * Class CouponController
 * @package app\api\controller
 */
class CouponController extends Api
{
    protected $noNeedLogin = [];
    protected $noNeedRight = ['*'];

    /**
     * 全部优惠券
     */
    public function index(Coupon $model_coupon, UserCoupon $model_user_coupon,CouponValidate $validate)
    {
        $param = $this->request->param();
        //数据验证
        $validate_result = $validate->scene('index')->check($param);
        if (!$validate_result) {
            $this->error($validate->getError());
        }
        $user_id = $this->_uid;
        $page = $param['page'];
        $num = 10;

       // $beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
        $beginToday = time();
        $coupon = $model_coupon
            ->where([
                'starttime' => ['<=',$beginToday],
                'endtime' => ['>=',$beginToday],
                'hot_switch' => 1,
                'status' => 1
            ])
            ->order([
                'use_price' => 'asc'
            ])
            ->page($page)
            ->limit($num)
            ->select();

        //判断当前用户当前优惠券是否已经存在
        foreach ($coupon as $value) {
            $value['is_receive'] = $model_user_coupon->where(['user_id' => $user_id, 'coupon_id'=>$value['id']])->find() ? 1 : 0;
        }

        $count = $model_coupon
            ->where([
                'starttime' => ['<=',$beginToday],
                'endtime' => ['>=',$beginToday],
                'hot_switch' => 1,
                'surplus_num' => ['neq', 0]
            ])
            ->count();
        $allpage = (string)ceil($count/$num);

        $data = [
            'coupon' => $coupon,
            'page' => $page,
            'allpage' => $allpage,
        ];
        $this->success('查询成功',$data);
    }

    //领取
    public function receive(Coupon $model_coupon,UserCoupon $model_user_coupon, CouponValidate $validate)
    {
        $param = $this->request->param();
        //数据验证
        $validate_result = $validate->scene('receive')->check($param);
        if (!$validate_result) {
            $this->error($validate->getError());
        }
        $user_id = $this->_uid;

        $coupon_id = $param['coupon_id'];
        //1.判断当前优惠券是否充足
        $coupon_info = $model_coupon->where(['id'=>$coupon_id])->find();
        if ($coupon_info['surplus_num'] <= 0) {
            $this->error('优惠券已经领光了，下次再来吧');
        }
        //2.判断当前用户当前优惠券是否已存在
        if ($model_user_coupon->where(['user_id'=>$user_id,'coupon_id'=>$coupon_id])->find()) {
            $this->error('请勿重复领取');
        }

        $param['user_id'] = $user_id;
        $param['use_price'] = $coupon_info['use_price']; //使用金额
        $param['cut_price'] = $coupon_info['cut_price']; //满减金额
        unset($param['token']);
        if ($model_user_coupon->save($param)) {
            //领取成功,扣除优惠券数量
            $model_coupon->where('id',$coupon_id)->setDec('surplus_num',1);
            $this->success('领取成功');
        } else {
            $this->error('领取失败');
        }
    }
}
