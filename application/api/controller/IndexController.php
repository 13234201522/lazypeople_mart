<?php

namespace app\api\controller;

use app\api\model\Banner;
use app\api\model\Category;
use app\api\model\Coupon;
use app\api\model\Goods;
use app\api\model\Seckill;
use app\api\model\SeckillGoods;
use app\api\model\UserCoupon;
use app\api\validate\IndexValidate;
use app\common\controller\Api;

/**
 * 首页接口
 */
class IndexController extends Api
{
    protected $noNeedLogin = ['ceshi'];
    protected $noNeedRight = ['*'];

//    public function ceshi(){
//        $coupon_can_use_list = couponCanUse(15, 3,'goods',5);
//        dump($coupon_can_use_list);
//    }
    /**
     * 首页
     *
     */
    public function index(Banner $model_banner, Category $model_category, Seckill $model_seckill, SeckillGoods $model_seckill_goods, Coupon $model_coupon, UserCoupon $model_user_coupon, Goods $model_goods, IndexValidate $validate)
    {

        $param = $this->request->param();
        //数据验证
        $validate_result = $validate->scene('index')->check($param);
        if (!$validate_result) {
            $this->error($validate->getError());
        }
        $user_id = $this->_uid;
        $page = $param['page'];
        $keywords = $param['keywords'];
        $num = 10;

        //1.轮播图
        $data_banner = $model_banner
            ->where([
                'status' => 'normal',
            ])
            ->order([
                'weigh' => 'desc'
            ])
            ->select();

        //2.分类
        $data_category = $model_category
            ->where([
                'pid'=>0,
                'status' => 'normal'
            ])
            ->order([
                'weigh' => 'desc'
            ])
            ->limit(8)
            ->select();

        //3.超市公告
        $data_notice['notice_switch'] = config('site.notice_switch');
        $data_notice['mart_notice'] = $data_notice['notice_switch'] ? config('site.mart_notice') : '';

        //4.秒杀
        //4.1秒杀开关
        $data_seckill['seckill_switch'] = config('site.seckill_switch');
        $beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
        //4.2 今日秒杀
        $seckill_today = $model_seckill
            ->where([
                'starttime' => ['<=',time()],
                'endtime' => ['>=',time()],
            ])
            ->find();
        $data_seckill['seckill_status'] = $seckill_today ? $seckill_today['status'] : '';
        $data_seckill['starttime'] = $seckill_today ? $seckill_today['starttime'] : '';
        $data_seckill['endtime'] = $seckill_today ? $seckill_today['endtime'] : '';
        //4.2秒杀商品
        $data_seckill['goods_list'] = $model_seckill_goods
            ->with('goods_brief')
            ->where(['seckill_id'=>$seckill_today['id']])
            ->limit(2)
            ->select();


        //5.优惠券
        
        $beginToday = time();
        $data_coupon = $model_coupon
            ->where([
                'starttime' => ['<=',$beginToday],
                'endtime' => ['>=',$beginToday],
                'hot_switch' => 1,
                'status' => 1,
                'surplus_num' => ['neq', 0]
            ])
            ->order([
                'use_price' => 'asc'
            ])
            ->limit(3)
            ->select();

        //判断当前用户当前优惠券是否已经存在
        foreach ($data_coupon as $value) {
            $value['is_receive'] = $model_user_coupon->where(['user_id' => $user_id, 'coupon_id'=>$value['id']])->find() ? 1 : 0;
        }

        //6.热门商品
        //获取当前在秒杀中的全部商品(并不在首页展示)
        $seckill_goods_ids = $model_seckill_goods
            ->where(['seckill_id'=>$seckill_today['id']])
            ->column('id');
        if ($keywords) {
            $where['name'] = ['like','%'.$keywords.'%'];
        } else {
            $where['hot_switch'] = 1;
            if ($data_seckill['seckill_status'] && !$keywords) {
                $where['id'] = ['not in',$seckill_goods_ids];
            }
        }

        $where['status'] = 1;
        $data_goods = $model_goods
            ->field('id,name,images,json,vip_switch,weigh,status')
            ->where($where)
            ->order([
                'weigh' => 'desc'
            ])
            ->page($page)
            ->limit($num)
            ->select();

        $count = $model_goods
            ->where($where)
            ->count();

        $allpage = (string)ceil($count/$num);

        $data = [
            'banner' => $data_banner,
            'category' => $data_category,
            'notice' => $data_notice,
            'seckill' => $data_seckill,
            'coupon' => $data_coupon,
            'hot_goods' => $data_goods,
            'page' => $page,
            'allpage' => $allpage,
        ];
        $this->success('查询成功', $data);
    }
}
