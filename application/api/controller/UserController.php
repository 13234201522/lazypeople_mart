<?php

namespace app\api\controller;

use app\api\model\Coupon;
use app\api\model\Order;
use app\api\model\User;
use app\api\model\UserCoupon;
use app\api\model\UserFootprint;
use app\api\validate\UserValidate;
use app\common\controller\Api;
use app\common\library\Ems;
use app\common\library\Sms;
use fast\Random;
use think\Validate;

/**
 * 会员接口
 */
class UserController extends Api
{
    protected $noNeedLogin = [];
    protected $noNeedRight = '*';

    public function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 会员中心
     */
    public function index(User $model)
    {
        $user_id = $this->_uid;
        $user = $model::get($user_id);

        $data = [
            'user' => $user
        ];
        $this->success('查询成功',$data);
    }

    /**
     * 修改用户信息
     */
    public function edit(User $model)
    {
        $param = $this->request->param();
        $user_id = $this->_uid;
        //昵称
        if (isset($param['nickname']) && !$param['nickname']) {
            $this->error('修改昵称不能为空');
        }
        //头像
        if (isset($param['avatar']) && !$param['avatar']) {
            $this->error('修改头像不能为空');
        }
        unset($param['token']);
        if (empty($param)) {
            $this->error('修改失败');
        }
        $param['updatetime'] = time();
        if ($model->where('id',$user_id)->update($param)) {
            $this->success('修改成功');
        } else {
            $this->error('修改失败');
        }
    }

    /**
     * 修改绑定手机号
     * Author @YJQ@
     */
    public function editMobile(User $model, UserValidate $validate)
    {
        $param = $this->request->param();
        //数据验证
        $validate_result = $validate->scene('edit_mobile')->check($param);
        if (!$validate_result) {
            $this->error($validate->getError());
        }
        $user_id = $this->_uid;
        $mobile = $param['mobile'];
        //判断当前手机号是否已存在
        if ($model->where(['mobile'=>$mobile])->find()) {
            $this->error('当前手机号已经存在');
        }
        //修改绑定手机号
        if ($model->where('id',$user_id)->update(['mobile'=>$mobile,'updatetime'=>time()])) {
            $this->success('修改成功');
        } else {
            $this->error('修改失败');
        }
    }

    /**
     * 我的优惠券
     */
    public function myCoupon(Coupon $model_Coupon, UserCoupon $model_user_coupon, UserValidate $validate)
    {
        $param = $this->request->param();
        $user_id = $this->_uid;
        //
        $list = $model_user_coupon
            ->with('coupon')
            ->where([
                'user_id' => $user_id,
                'status' => '1',
                'use_status' => 0,
            ])
            ->select();
            
        $res = [];
        foreach ($list as $key => $value) {
            if ($value['coupon']) {
                $res[] = $value;
            }
        }

        $data = [
            'list' => $res,
        ];
        $this->success('查询成功',$data);
    }
    //优惠券详情
    public function myCouponInfo(UserCoupon $model_user_coupon){
        $id = input('id');
        $data = db('coupon')
            ->field('cut_price,use_price,starttime,endtime,category_ids')
            ->where(['id'=>$id])
            ->find();
        $data['starttime'] = date('Y-m-d',$data['starttime']);
        $data['endtime'] = date('Y-m-d',$data['endtime']);
        $data['category'] = db('category')->field('name')->whereIn('id',$data['category_ids'])->select();
        unset($data['category_ids']);
        $this->success('查询成功',$data);
    }

    //删除优惠券
    public function myCouponDel(Coupon $model_Coupon, UserCoupon $model_user_coupon, UserValidate $validate)
    {
        $param = $this->request->param();
        //数据验证
        $validate_result = $validate->scene('my_coupon_del')->check($param);
        if (!$validate_result) {
            $this->error($validate->getError());
        }
        $user_id = $this->_uid;
        $user_coupon_id = $param['user_coupon_id'];
        if ($model_user_coupon::destroy($user_coupon_id)) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }

    //会员套餐
    public function level()
    {
        $silver_price = config('site.silver_price');
        $gold_price = config('site.gold_price');
        $masonry_price = config('site.masonry_price');
        $level_privilege = config('site.level_privilege');

        $data = [
            'silver_price' => $silver_price,
            'gold_price' => $gold_price,
            'masonry_price' => $masonry_price,
            'level_privilege' => contentImageUrl($level_privilege),
        ];
        $this->success('查询成功',$data);
    }

    //用户足迹
    public function userFootprint(UserFootprint $model, UserValidate $validate)
    {
        $param = $this->request->param();
        //数据验证
        $validate_result = $validate->scene('user_footprint')->check($param);
        if (!$validate_result) {
            $this->error($validate->getError());
        }
        $user_id = $this->_uid;
        $page = $param['page'];
        $num = 3;

        $list = $model
            ->with('goods')
            ->where('user_id',$user_id)
            ->group('goods_id')
            ->order('createtime','desc')
            ->select();
        $list_date = [];
        foreach ($list as $value) {
            if (!in_array($value['createtime_text'], $list_date)) {
                $list_date[] = $value['createtime_text'];
            }
        }
        $list_goods = [];
        foreach ($list_date as $value) {
            foreach ($list as $val) {
                if ($value == $val['createtime_text']) {
                    $list_goods[$value][] = $val;
                }
            }
        }
        $start=($page-1)*$num;
        $totals=count($list_goods);
        $allpage=ceil($totals/$num); #计算总页面数

        $pagedata=array_slice($list_goods,$start,$num);


        $data = [
            'list' => $pagedata,
            'page' => $page,
            'allpage' => $allpage,
        ];
        $this->success('查询成功',$data);
    }

    /**
     * 足迹删除
     */
    public function footprintDel(UserFootprint $model, UserValidate $validate)
    {
        $param = $this->request->param();
        //数据验证
        $validate_result = $validate->scene('footprint_del')->check($param);
        if (!$validate_result) {
            $this->error($validate->getError());
        }
        $user_id = $this->_uid;
        $goods_id = $param['goods_id'];
        if ($model->where(['user_id'=>$user_id,'goods_id'=>$goods_id])->delete()) {
            $this->success('操作成功');
        } else {
            $this->error('操作失败');
        }
    }

    /**
     * 关于我们
     */
    public function aboutUs()
    {
        $about_us = config('site.about_us');
        $data = [
            'about_us' => contentImageUrl($about_us)
        ];
        $this->success('查询成功', $data);
    }

    //当前实名状态
    public function userRealStatus(){
        $data = db('real')->field('front_image,behind_image,hold_image,status')
            ->where(['user_id'=>$this->_uid])->find();
        $data = $data?:[
            'status'=>4
        ];
        $this->success('成功',$data);
    }
    //提交实名认证
    public function userRealCheck(){
        $uid = $this->_uid;
        $data = [
            'front_image'=>input('front_image'),
            'behind_image'=>input('behind_image'),
            'hold_image'=>input('hold_image'),
            'status'=>1
        ];
        $isReal = db('real')->where(['user_id'=>$uid])->find();
        if($isReal){
            //提交过
            if($isReal['status'] != 3){
                $this->error('请勿重复提交');
            }
            db('real')->where(['user_id'=>$uid])->update($data);
        }else{
            $data['user_id'] = $uid;
            $data['createtime'] = time();
            db('real')->insert($data);
        }
        $this->success('提交成功,请耐心等待审核');
    }


}
