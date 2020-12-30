<?php


namespace app\api\controller;


use app\api\model\Address;
use app\api\model\Category;
use app\api\model\Coupon;
use app\api\model\Goods;
use app\api\model\SeckillGoods;
use app\api\model\UserCoupon;
use app\api\model\Fullcut;
use app\api\model\UserFootprint;
use app\api\validate\GoodsValidate;
use app\common\controller\Api;

/**
 * 商品详情
 * Class GoodsController
 * @package app\api\controller
 */
class GoodsController extends Api
{
    protected $noNeedLogin = [];
    protected $noNeedRight = ['*'];

    /**
     * 分类下商品列表
     */
    public function goodsListByCategory(Goods $model_goods, Category $model_category, Coupon $model_coupon, GoodsValidate $validate)
    {
        $param = $this->request->param();
        //数据验证
        $validate_result = $validate->scene('by_category')->check($param);
        if (!$validate_result) {
            $this->error($validate->getError());
        }
        $keywords = $param['keywords'];
        $sort = $param['sort'];
        $page = $param['page'];
        $num = 10;
        $category = [];
        $fullcut = [];
        $where = [];
        if(!empty($param['category_id'])){
            $where['category_id'] = $param['category_id'];
            $category = $model_category::get($param['category_id']);
        }
        if (!empty($param['coupon_id'])) {
            //获取优惠券所属分类
            $category_one_ids = $model_coupon->where('id',$param['coupon_id'])->value('category_ids');
            $category_two_ids = $model_category->where('pid','in',explode(',',$category_one_ids))->column('id');
            $where['category_id'] = ['in',$category_two_ids];
            $category = [];
            
        }
        if(!empty($param['fullcut'])){
            $fullcut = db('fullcut')->where('id',$param['fullcut'])->find();
            //获取当前满减下的商品
            $goods_ids = db('fullcut_goods')->where('fullcut_id',$param['fullcut'])->column('goods_id');
            
            $where['id'] = ['in',$goods_ids];
        }
        //获取分类信息


        switch ($sort) {
            case 'normal':
                $order = [
                    'weigh' => 'desc'
                ];
                break;
            case 'sales':
                $order = [
                    'sales' => 'desc'
                ];
                break;
            case 'price_high':
                $order = [
                    'default_price' => 'desc'
                ];
                break;
            case 'price_low':
                $order = [
                    'default_price' => 'asc'
                ];
                break;
        }
        //获取商品列表
        $goods = $model_goods
            ->field('id,category_id,name,images,json,vip_switch,weigh,status')
            ->where([
                'status'=> 1,
                'name'=> ['like','%'.$keywords.'%'],
            ])
            ->where($where)
            ->order($order)
            // ->page($page)
            // ->limit($num)
            ->select();

        $count = $model_goods
            ->where([
                'status'=>1,
                'name'=> ['like','%'.$keywords.'%'],
            ])
            ->where($where)
            ->count();
        $allpage = (string)ceil($count/$num);


        $data = [
            'fullcut' => $fullcut,
            'category' => $category,
            'goods' => $goods,
            'page' => $page,
            'allpage' => $allpage,
        ];
        $this->success('查询成功',$data);
    }

    // 商品详情
    public function info(Goods $model_goods, Address $model_address, Coupon $model_coupon, Fullcut $model_fullcut, UserFootprint $model_footprint, GoodsValidate $validate)
    {
        $param = $this->request->param();
        //数据验证
        $validate_result = $validate->scene('info')->check($param);
        if (!$validate_result) {
            $this->error($validate->getError());
        }
        $user_id = $this->_uid;
        //当前用户会员状态
        $level = $this->_level;
        $goods_id = $param['goods_id'];
        //1.商品
        $goods = $model_goods::get($goods_id);
        if (!$goods) {
            $this->error('商品不存在');
        }
        //获取顶级分类ID
        $cid = db('category')->where(['id'=>$goods->category_id])->value('pid');
        //2.优惠券(展示钱最多的一个)
        $model_user_coupon = new UserCoupon();
        $coupon = $model_coupon->where(['status'=>'1'])->whereLike('category_ids','%'.$cid.'%')->order(['cut_price'=>'desc'])->find();
        if ($coupon) {
            $coupon->starttime_text = date("Y.m.d",$coupon->starttime);
            $coupon->endtime_text = date("Y.m.d",$coupon->endtime);
        }
        $coupon['is_receive'] = $model_user_coupon->where(['user_id' => $user_id, 'coupon_id'=>$coupon['id']])->find() ? 1 : 0;
        //3.满减
        $fullcut_switch = (config('site.fullcut_switch') && $goods['fullcut_switch']) ;
//        $fullcut = [
//            'full_price' => config('site.full_price'),
//            'cut_price' => config('site.cut_price'),
//            'starttime' => config('site.starttime'),
//            'endtime' => config('site.endtime'),
//        ];
        $fullcut = $model_fullcut
            ->alias('a')
            ->join('fullcut_goods b','a.id=b.fullcut_id')
            ->join('goods c','b.goods_id=c.id')
            ->where('a.starttime <' .time())
            ->where('a.endtime >' .time())
            ->field('a.id,a.full_price,a.cut_price,a.endtime')
            ->where(['a.status'=>'normal','c.id'=>$goods_id])->select();
        //4.地址(当前用户默认地址)
        $address = $model_address->where(['user_id'=>$user_id,'status'=>'1'])->find();

//        $address_id = 13;
//        $address = $model_address->where('id',$address_id)->find();

        //5.生成足迹
        $model_footprint->save(['user_id'=>$user_id,'goods_id'=>$goods_id]);

        //秒杀查询
        $model_seckill_goods = new SeckillGoods();
        $secKill = $model_seckill_goods
            ->alias('a')
            ->field('a.seckill_json,b.starttime,b.status')
            ->join('seckill b','a.seckill_id=b.id')
            ->where(['a.goods_id'=>$goods_id,'a.status'=>['neq',2]])
            ->find();
        $data = [
            'level'=>$level,
            'goods'=>$goods,
            'coupon'=>$coupon?:0,
            'fullcut_switch'=>$fullcut_switch,
            'fullcut'=>$fullcut,
            'address'=>$address,
        ];
        if($secKill){
            if($secKill['status'] == 1){
                $secKill['starttime'] = date('m-d H:i:s',$secKill['starttime']);
                $data['seckill_data'] = $secKill;
            }else{
                $data['seckill_data'] = [];
            }
        }
        $this->success('查询成功',$data);

    }
}
