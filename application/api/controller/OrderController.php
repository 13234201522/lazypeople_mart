<?php


namespace app\api\controller;


use app\api\model\Address;
use app\api\model\Cart;
use app\api\model\Coupon;
use app\api\model\Category;
use app\api\model\Goods;
use app\api\model\Order;
use app\api\model\OrderDetail;
use app\api\model\OrderLevel;
use app\api\model\User;
use app\api\model\UserCoupon;
use app\api\validate\OrderValidate;
use app\common\controller\Api;
use app\common\library\Utility;
use app\common\library\UtilityCard;
use think\Db;
use think\Exception;

/**
 * 订单
 * Class OrderController
 * @package app\api\controller
 */
class OrderController extends Api
{
    protected $noNeedLogin = ['notify'];
    protected $noNeedRight = '*';

    /**
     * 下单页面
     */
    public function page(Order $model, Goods $model_goods,Category $model_category, Cart $model_cart, Address $model_address,UserCoupon $model_coupon, OrderValidate $validate)
    {
        $param = $this->request->param();
        //数据验证
        $validate_result = $validate->scene('page')->check($param);
        if (!$validate_result) {
            $this->error($validate->getError());
        }
        $user_id = $this->_uid;
        $user_level = $this->_level;
        $type = $param['type'];
        $address_id = $param['address_id'];
        //1.地址(当前用户默认地址)
        if (!$address_id) {
            //获取当前用户默认地址
            $address = $model_address->where(['user_id'=>$user_id,'status'=>'1'])->find();
        } else {
            $address = $model_address->where(['id'=>$address_id])->find();
        }
//        $address_id = 5;
        $goods_list = [];
        $fu = 0;
        //2.获取商品信息
        if ($type == 'goods') { //商品详情下单
            $goods_id = $param['ids']; //商品id
            $specs = $param['specs']; //规格
            $num = $param['num']; //数量
            $goods = $model_goods::get($goods_id);
            if($this->checkReal($user_id,$goods['category_id'])){
                $this->error('请前往实名认证','1001');
            }
            $specs_text = $goods['specs_text'];
            $specs_select = selectSpecs($specs_text,$specs);
            //1.1判断规格
            if (!$specs_select) {
                $this->error('所选规格不存在');
            }
            //1.2判断库存
            if ($num > $specs_select['stock']) {
                $this->error('库存不足');
            }
            $goods_info = [
                'id' => $goods['id'],
                'category_pid' => $model_category->where('id',$goods['category_id'])->value('pid'),
                'name' => $goods['name'],
                'specs' => $specs,
                'image' => $specs_select['specs_image'],
                'num' => $num,
                'is_fullcut' => $goods['fullcut_switch'],
            ];
            //1.3 处理商品单价
            //1.3.1判断当前商品是否处于秒杀状态
            $miaosha = 0;
            if ($goods['seckill_status_text'] == 1) {
                $today = db('order')->where('to_days(FROM_UNIXTIME(createtime,"%Y-%m-%d %H:%i:%s")) = to_days(now())')
                    ->where(['user_id'=>$this->_uid,'is_miaosha'=>1])->whereIn('status',['0','1','2','3'])->find();
                if($today){
                    $this->error('秒杀商品一天只能购买一件');
                }
                //秒杀商品数量只能是1
                if ($num > 1) {
                    $this->error('秒杀商品一天只能购买一件');
                }
                $miaosha = 1;
                $price = $specs_select['seckill_price'];
            } else {
                //1.3.2 判断当前商品是否处于会员状态并且用户处于会员状态
                if ($goods['vip_switch'] == 1 && $user_level == 1) {
                    $price = $specs_select['price']*config('site.level_discount')/100;
                } else {
                    $price = $specs_select['price'];
                }
            }
            $goods_info['price'] = $price;//商品单价

            $goods_list[] = $goods_info;

            $goods_total_price = $price * $num;
            
            $fullcut_goods_total_price = $goods['fullcut_switch'] ? $goods_total_price : 0;

        } else { //购物车下单
            $cart_ids = $param['ids']; //购物车拼接id
            $carts = $model_cart->with('goods')->where(['id'=>['in',$cart_ids]])->select();
            $goods_arr = [];
            $goods_total_price = 0;
            $fullcut_goods_total_price = 0;
            $miaosha = 0;
            foreach ($carts as $value) {
                $specs = $value['specs']; //规格
                $num = $value['num']; //数量
                $goods = $value['goods'];
                $specs_text = $goods['specs_text'];
                $specs_select = selectSpecs($specs_text,$specs);
                if($this->checkReal($user_id,$goods['category_id'])){
                    $this->error('请前往实名认证','1001');
                }
                //1.1判断规格
                if (!$specs_select) {
                    $this->error('所选规格不存在');
                }
                if($goods['fullcut_switch'] == 1){
                    $fu = 1;
                }
                //1.2判断库存
                if ($num > $specs_select['stock']) {
                    $this->error('库存不足');
                }
                $goods_info = [
                    'id' => $goods['id'],
                    'category_pid' => $model_category->where('id',$goods['category_id'])->value('pid'),
                    'name' => $goods['name'],
                    'specs' => $specs,
                    'image' => $specs_select['specs_image'],
                    'num' => $num,
                    'is_fullcut' => $goods['fullcut_switch'],
                ];
                //1.3 处理商品单价
                //1.3.1判断当前商品是否处于秒杀状态
                if ($goods['seckill_status_text'] == 1) {
                    $today = db('order')->where('to_days(FROM_UNIXTIME(createtime,"%Y-%m-%d %H:%i:%s")) = to_days(now())')
                        ->where(['user_id'=>$this->_uid,'is_miaosha'=>1])->whereIn('status',['0','1','2','3'])->find();
                    if($today){
                        $this->error('秒杀商品一天只能购买一件');
                    }
                    //秒杀商品数量只能是1
                    if (($num+$miaosha) > 1) {
                        $this->error('秒杀商品一天只能购买一件');
                    }
                    $miaosha ++;
                    $price = $specs_select['seckill_price'];
                } else {
                    //1.3.2 判断当前商品是否处于会员状态
                    if ($goods['vip_switch'] == 1 && $user_level == 1) {
                        $price = $specs_select['price']*config('site.level_discount')/100;;
                    } else {
                        $price = $specs_select['price'];
                    }
                }
                $goods_info['price'] = $price;//商品单价
                $goods_info['is_fullcut'] = $goods['fullcut_switch'];

                $goods_total_price += $price * $num;

                $fullcut_goods_total_price += $goods['fullcut_switch'] ?  $price * $num : 0;

                $goods_list[] = $goods_info;
            }
        }
        //商品总价计算1.先算商品
        $total_price = $goods_total_price;
        $good_ids = array_column($goods_list,'id');
        //3.满减
        $fullcut = fullCutConfig($good_ids,$fullcut_goods_total_price);
        $time = time();
        //3.1判断当前满减状态
        if (config('site.fullcut_switch') && $fullcut) {
            $fullcut_price = $fullcut['cut_price'];
        } else {
            $fullcut_price = 0;
        }
        //计算每个商品真实购买价格
        foreach ($goods_list as $key => $value) {
            if ($value['is_fullcut'] && $fullcut_price) {
                
                $real_price = floor((($fullcut_goods_total_price-$fullcut_price)/$fullcut_goods_total_price) * $value['price'] * 100)  / 100 ;
                if ($real_price < 0) {
                    $real_price = 0;
                }
                $value['real_price'] = $real_price;
            } else {
                $value['real_price'] = $value['price'];
            }
            $goods_list[$key] = $value;
        }
        //商品总价计算2.算满减
        $total_price -= $fullcut_price;
        if ($total_price <= 0) {
            $total_price = 0.01;
        }
        
        $coupon_can_use_list = couponCanUse2($user_id,$goods_list);

        //4.可用优惠券(获取当前商品总价可用最高优惠券)
        // $coupon_can_use_list = couponCanUse($user_id, $goods_total_price,$type,$param['ids']);
        
        
        //5.选中优惠券
        $category_usearr = [];
        if (isset($param['coupon_id'])) {
            if ($param['coupon_id']) {
                $coupon_select = $model_coupon->with('couponCategory')->where('id',$param['coupon_id'])->find();
                $category_usearr = explode(',',$coupon_select->category_ids);
            } else {
                $coupon_select = [];
            }
        } else {
            $coupon_select = $coupon_can_use_list ? $coupon_can_use_list[0] : [];
        }
        
        //5.优惠券价格
        $coupon_price = $coupon_select ? $coupon_select['cut_price'] : 0;
        
        $total_coupon_goods_price = 0;
        //计算每个商品真实购买价格
        foreach ($goods_list as $key => $value) {
            $value['is_coupon'] = 0;
            if (in_array($value['category_pid'],$category_usearr)) {
                $total_coupon_goods_price += $value['real_price'] * $value['num'];
                $value['is_coupon'] = 1;
            }
            $goods_list[$key] = $value;
        }
        foreach ($goods_list as $key => $value) {
            
            if ($value['is_coupon']) {
                $value['real_price'] = floor((($total_coupon_goods_price-$coupon_price)/$total_coupon_goods_price) * $value['real_price'] * 100)  / 100;
            }
            $goods_list[$key] = $value;
        }
        

        //商品总价计算3.算优惠券
        $total_price -= $coupon_price;
        
        if ($total_price <= 0) {
            $total_price = 0.01;
        }
        
        //商品总价计算4.1.算邮费
        //4.运费(根据地址中的运费来算)
        $postage_rule = $address['postage_text'];
        // halt($postage_rule);
        if (($goods_total_price >= $postage_rule['price']) && $postage_rule['type'] == '快递') {
            $postage_price = 0;
        } else {
            $postage_price = $postage_rule['fixed_price'];
        }
        

        // $total_price += $postage_price;

        //最后总价
        // $last_price = $total_price;

        //7.税费
        
        
        $tax_rate = $address['tax_text']/100;
        //商品总价计算4.2.算税费
        $tax_price = sprintf("%.2f", $total_price * $tax_rate);
        // halt($tax_price);
        $charge_rate = Config('site.charge')/100;
        //商品总价计算4.3.算手续费
        $charge_price = sprintf("%.2f", $total_price * $charge_rate);
        
        foreach ($goods_list as $key => $value) {
            if ($tax_price) {
                $value['real_tax_price'] = floor($tax_rate * $value['real_price'] * 100)  / 100;
            }
            if ($charge_price) {
                $value['real_charge_price'] = floor($charge_rate * $value['real_price'] * 100)  / 100;
            }
            $goods_list[$key] = $value;
        }
        

        //商品总价计算5.算总价
        //8.总价
        $total_price = $total_price + $tax_price + $charge_price + $postage_price;
        
        //打赏留给前端

        //手续费
        //$charge_price = ceil($last_price*Config('site.charge'))/100;

        //$total_price += $charge_price;
//        $total_price = $goods_total_price + $postage_price - $fullcut_price - $coupon_price + $tax_price;


        $data = [
            'postage'=>$postage_rule,
            'address'=>$address, //地址
            'goods_list' => $goods_list, //商品
            'coupon_can_use_list' => $coupon_can_use_list, //可用优惠券
            'coupon_select' => $coupon_select, //选中优惠券
            'goods_total_price' => $goods_total_price, //商品总价
            'postage_price' => $postage_price, //运费
            'fullcut_price' => $fullcut_price, //满减
            'coupon_price' => $coupon_price, //优惠券
            'tax_rate' => $tax_rate, //税费费率
            'tax_price' => $tax_price, //税费
            'charge_price'=> $charge_price,//手续费率
            'total_price' => $total_price,
            'is_miaosha'=>$miaosha,//是否含有秒杀商品
        ];
        $this->success('查询成功',$data);
    }
    
    //判断是否需要实名认证
    public function checkReal($uid,$cid){
        $model_category = new Category();
        $category = $model_category->where(['id'=>$cid])->find();
        if($category['pid'] == 155){
            //烟酒类 需要实名验证
            $real = db('real')->where(['user_id'=>$uid])->whereIn('status',[1,2])->find();
            if(!$real){
                return true;
            }
        }
        return false;
    }
    
    /**
     * 提交订单
     */
    public function submit(Order $model,OrderDetail $model_detail, Goods $model_goods, Cart $model_cart, UserCoupon $model_coupon, OrderValidate $validate)
    {
        $param = $this->request->param();
    
        //数据验证
        $validate_result = $validate->scene('submit')->check($param);
        if (!$validate_result) {
            $this->error($validate->getError());
        }
        $user_id = $this->_uid;
        $goods = json_decode(htmlspecialchars_decode($param['goods']),true);
        //获取用户优惠券可用分类
        // $category_ids = '';
        // $category_uses = $model_coupon->with('couponCategory')->where('id',$param['user_coupon_id'])->find();
        // if ($category_uses) {
        //     $category_ids = $category_uses->category_ids;
        // }
        // $category_usearr = explode(',',$category_ids);

        unset($param['goods'],$param['token']);
        $order_num = orderNum();
        $param['user_id'] = $user_id;
        $param['order_num'] = $order_num;
        //商品打赏占比金额 
        $total_goods_reward_price = $param['reward_price'] - ($param['postage_price'] * ($param['reward_price'] / ($param['pay_price'] - $param['reward_price'])));
        
        
        Db::startTrans();
        try {
            
            //清除购物车
            if (isset($param['cart_ids']) && $param['cart_ids']) {
                $cart_ids = $param['cart_ids'];
                $model_cart->where(['id'=>['in',$cart_ids]])->delete();
                unset($param['cart_ids']);
            }
            //修改优惠券状态
            if (isset($param['user_coupon_id']) && $param['user_coupon_id']) {
                $coupon_id  = $param['user_coupon_id'];
                $model_coupon->where('id',$coupon_id)->update(['use_status'=>'1','updatetime'=>time()]);
            }

            //1.保存主订单
            $model->save($param);
            $order_id = $model->id;
            //2.保存订单商品
            $param_detail = [];
            foreach ($goods as $value) {
                
                //优惠券商品总价格
                // if (in_array($value['category_pid'],$category_usearr)) {
                //     $total_coupon_goods_price += $value['real_price'] * $value['num'];
                // }
                
                //打赏
                $real_reward_price = floor($total_goods_reward_price * (($value['real_price'] + $value['real_tax_price'] + $value['real_charge_price']) / ($param['pay_price'] - $param['reward_price'] - $param['postage_price'])) * 100)  / 100;
                $param_detail[] = [
                    'order_id' => $order_id,
                    'goods_id' => $value['id'],
                    'specs' => $value['specs'],
                    'num' => $value['num'],
                    'price' => $value['price'],
                    'image' => delCdnurl($value['image']),
                    // 'is_coupon' => in_array($value['category_pid'],$category_usearr) ? 1:0,
                    // 'is_fullcut' => $value['is_fullcut'],
                    'real_price' => $value['real_price'],
                    'real_tax_price' => $value['real_tax_price'],
                    'real_charge_price' => $value['real_charge_price'],
                    'real_reward_price' => $real_reward_price,
                    // 'real_reward_price' => floor(100 * ($param['reward_price'] * $param['pay_price'] * ($value['real_price'] + $value['real_tax_price'] + $value['real_charge_price'])) / ($param['pay_price'] - $param['reward_price'])) / 100,
                ];
                
                
                //3.订单商品扣除库存
                $goods = $model_goods::get($value['id'])->getData();
                $specs_arr = json_decode($goods['json'],true);
                foreach ($specs_arr as $key => $val) {
                    if ($val['specs'] == $value['specs']) {
                        $val['stock'] -= $value['num'];
                    }
                    $specs_arr[$key] = $val;
                }
                $model_goods->where(['id'=>$value['id']])->update(['json'=>json_encode($specs_arr)]);
            }
            
            // foreach ($param_detail as $key => $value) {
            //     //优惠券商品总价格
            //     if ($value['is_coupon']) {
            //         $value['real_price'] = floor((($total_coupon_goods_price-$param['coupon_price'])/$total_coupon_goods_price) * $value['real_price'] * 100)  / 100;
            //     }
            //     unset($value['is_coupon']);
            //     unset($value['is_fullcut']);
            //     $param_detail[$key] = $value;
            // }
            //
            $model_detail->saveAll($param_detail);
            Db::commit();
        } catch (Exception $e) {
            Db::rollback();
            $this->error($e->getMessage());
        }

        $data = [
            'order_num' => $order_num
        ];
        $this->success('下单成功',$data);

    }
    //退款商品信息
    public function orderRefundGoodsInfo(){
        $id = input('id');
        $data = db('order_detail')
            ->alias('a')
            ->field('a.specs,a.image,a.num,a.price,a.real_price,a.real_tax_price,a.real_charge_price,a.real_reward_price,b.total_goods_price,b.pay_price,b.postage_price,c.name')
            ->join('order b','a.order_id = b.id')
            ->join('goods c','a.goods_id = c.id')
            ->where(['a.id'=>$id])
            ->find();
        // $data['refund_price'] = $data['price']*$data['num']/$data['total_goods_price']*($data['pay_price']-$data['postage_price']);
        $data['refund_price'] = $data['real_price'] + $data['real_tax_price'] + $data['real_charge_price'] + $data['real_reward_price'];
        $refundSite = Config('site.refund');
        $refundReason = [];
        foreach ($refundSite as $row){
            $refundReason[] = $row;
        }
        $data['image'] = 'https://site.lazypeoplemart.store'.$data['image'];
        $data['refund_reason'] = $refundReason;
        $this->success('查询成功',$data);
    }
    //申请退款
    public function orderRefund(){
        $id = input('id');
        $num = input('num');//退款数量
        $data = db('order_detail')
            ->alias('a')
            ->field('a.*,b.id,b.total_goods_price,b.postage_price,b.pay_price,b.user_id,b.payOrderId,b.status')
            ->join('order b','a.order_id = b.id')
            ->where(['a.id'=>$id])
            ->find();
        // if($data['status'] != 1){
        //     $this->error('不可重复退款');
        // }
        if($data['payOrderId'] == null){
            $this->error('该订单不可退款');
        }
        if ($num <= 0) {
            $this->error('数量不能为0,无法退单');
        }
        
        $refund_price = $data['real_price'] + $data['real_tax_price'] + $data['real_charge_price'] + $data['real_reward_price'];
        if ($refund_price <= 0) {
            $this->error('订单金额过低,无法退单');
        }
        //获取订单,退款中的数量
        $refund_ing = db('order_refund')->where(['detail_id'=>$id,'status'=>0])->sum('num');
        if($num > $data['num']-$data['refund_num']-$refund_ing) {
            $this->error('数量不足,无法退单');
        }
        //应退金额
        // $price = $data['price'] * $num /$data['total_goods_price']*($data['pay_price']-$data['postage_price']);
        $insertData = [
            'user_id'=>$data['user_id'],
            'detail_id'=>$id,
            'goods_id'=>$data['goods_id'],
            'order_num'=>$data['payOrderId'],
            'refund_num'=>orderNum(),
            'price'=>$refund_price * $num,            
            'refund_goods_price'=>$data['real_price'] * $num,            
            'refund_tax_price'=>$data['real_tax_price'] * $num,            
            'refund_charge_price'=>$data['real_charge_price'] * $num,            
            'refund_reward_price'=>$data['real_reward_price'] * $num,            
            'num'=>$num,
            'images'=>input('images'),
            'reason'=>input('reason'),//退款原因
            'mark'=>input('mark'),//退款说明
            'status'=>0,
            'createtime'=>time(),
            'pay_status'=>1,
            'old_status'=>$data['status'],
        ];
        db('order_refund')->insert($insertData);
        // $edit_detail['refund_num'] =$data['refund_num']+$num;
        
        // if($num == $data['num']) {
        $edit_detail['status'] = 2;
        // } else {
        //     $edit_detail['status'] = 1;
        // }
        db('order_detail')->where(['id'=>$id])->update($edit_detail);
        // //判断当前订单下商品是否全部退款
        // if (0 == db('order_detail')->where(['order_id'=>$data['id'],'status'=>1])->count()) {
        //     db('order')->where(['payOrderId'=>$data['payOrderId']])->update(['status'=>5]);
        // }
        $this->success('申请退款成功,请等待后台审核');
    }
    //退款列表
    public function refundList(){
        $list = db('order_refund')
            ->alias('a')
            ->field('a.createtime,a.status,a.id,b.specs,b.image,a.num,b.price,c.name,a.detail_id')
            ->join('order_detail b','a.detail_id=b.id')
            ->join('goods c','b.goods_id=c.id')
            ->where(['a.user_id'=>$this->_uid])
            ->order('a.createtime','desc')
            ->select();
        foreach ($list as &$row){
            $row['createtime'] = date('Y-m-d H:i:s',$row['createtime']);
        }
        $this->success('查询成功',$list);
    }
    //退款详情
    public function refundInfo(){
        $id = input('id');
        $list = db('order_refund')
            ->alias('a')
            ->field('a.createtime,a.status,a.refund_num,a.price as refund_price,a.reason,a.refuse,b.specs,b.image,b.num,b.price,c.name')
            ->join('order_detail b','a.detail_id=b.id')
            ->join('goods c','b.goods_id=c.id')
            ->where(['b.id'=>$id])
            ->find();
        if($list){
            $list['createtime'] = date('Y-m-d H:i:s',$list['createtime']);
            $list['image'] = 'https://site.lazypeoplemart.store'.$list['image'];
        }else{
            $this->error('没有找到退款信息');
        }
        $this->success('查询成功',$list);
    }
    /**
     * 我的订单
     */
    public function myOrder(Order $model, OrderValidate $validate)
    {
        $param = $this->request->param();
        //数据验证
        $validate_result = $validate->scene('my_order')->check($param);
        if (!$validate_result) {
            $this->error($validate->getError());
        }
        $user_id = $this->_uid;
        $status = $param['status'];
        $page = $param['page'];
        $num = 10;
        $where['user_id'] = $user_id;
        if ($status != 'all') {
            $where['status'] = $status;
        } else {
            $where['status'] = ['in',[0,1,2,3,4,5]];
        }

        $list = $model
            ->with('orderDetail')
            ->where($where)
            ->order([
                'createtime' => 'desc'
            ])
            ->page($page)
            ->limit($num)
            ->select();

        $count = $model
            ->where($where)
            ->count();
        $allpage = (string)ceil($count/$num);

        $data = [
            'list' => $list,
            'page' => $page,
            'allpage' => $allpage,
        ];

        $this->success('查询成功', $data);
    }

    /**
     * 订单详情
     */
    public function info(Order $model,OrderValidate $validate)
    {
        $param = $this->request->param();
        //数据验证
        $validate_result = $validate->scene('info')->check($param);
        if (!$validate_result) {
            $this->error($validate->getError());
        }
        $user_id = $this->_uid;
        $order_id = $param['order_id'];
        $info = $model
            ->with(['address','order_detail'])
            ->where(['id'=>$order_id])
            ->find();

        $data = [
            'info' => $info
        ];

        $this->success('查询成功', $data);
    }

    /**
     * 取消订单
     */
    public function cancelOrder(Order $model, OrderValidate $validate)
    {
        $param = $this->request->param();
        //数据验证
        $validate_result = $validate->scene('cancel')->check($param);
        if (!$validate_result) {
            $this->error($validate->getError());
        }
        $user_id = $this->_uid;
        $order_id = $param['order_id'];
        if ($model->cancelOrder($order_id)) {
            $this->success('操作成功');
        } else {
            $this->error('操作失败');
        }
    }

    /*
     * 确认收货
     */
    public function receive(Order $model, OrderValidate $validate)
    {
        $param = $this->request->param();
        //数据验证
        $validate_result = $validate->scene('receive')->check($param);
        if (!$validate_result) {
            $this->error($validate->getError());
        }
        $user_id = $this->_uid;
        $order_id = $param['order_id'];
        $order = $model::get($order_id);
        if ($order['status'] != 2) {
            $this->error('当前订单状态不可确认收货');
        }
        if ($model->receive($order_id)) {
            $this->success('操作成功');
        } else {
            $this->error('操作失败');
        }
    }

    /**
     * 支付订单
     */
    public function payOrder(Order $model, Utility $utility, UtilityCard $utility_card, OrderValidate $validate)
    {
        $param = $this->request->param();
        //数据验证
        $validate_result = $validate->scene('pay_order')->check($param);
        if (!$validate_result) {
            $this->error($validate->getError());
        }
        $order_num = $param['order_num'];
        $pay_state = $param['pay_state'];
        $order_info = $model
            ->where([
                'order_num'=>$order_num,
                'status'=>0
                ])
            ->find();
        if (!$order_info) {
            $this->success('订单不存在');
        }

        $price = $order_info['pay_price'];
        $ip = $utility->real_ip();
        $merchant_id = config('site.merchantid');
        $merchant_key = config('site.merchantKey');
        $notifyUrl = 'https://'. $_SERVER['HTTP_HOST'] .'/api/order/notify';
        //$returnUrl = 'https://'. $_SERVER['HTTP_HOST'] .'/payreturn/index.html';
        $returnUrl = 'https://'. $_SERVER['HTTP_HOST'] .'/H5/#/pages/Order/Orderpages/OrderManagement/OrderManagement?index=0';
        $subject = '购买商品';
        $body = '';

        if ($pay_state == 'wechat') {
            $url = 'https://api.iotpaycloud.com/v1/payForSubmit';
            $arr = array(
                'mchId' => $merchant_id,
                'mchOrderNo' => $order_num,
                'channelId' => 'WX_JSAPI',
                'currency' => 'CAD',
                'amount' => intval($price * 100),
                'clientIp' => $ip,
                'notifyUrl' => $notifyUrl,
                'returnUrl' => $returnUrl,
                'subject' => $subject,
                'body' => $body
            );
            $sort_array = $utility->arg_sort($arr);
            $arr['sign'] = $utility->build_mysign($sort_array, $merchant_key, "MD5");//Generate signature parameter sign
            $param = 'params=' . json_encode($arr);
            $resBody = $utility->request($url, $param);
            $res = json_decode($resBody, true);
        }else if($pay_state == 'alipay'){
            $url = 'https://api.iotpaycloud.com/v1/create_order';
            $arr = array(
                'mchId' => $merchant_id,
                'mchOrderNo' => $order_num,
                'channelId' => 'ALIPAY_WAP',
                'currency' => 'CAD',
                'amount' => intval($price * 100),
                'clientIp' => $ip,
                'notifyUrl' => $notifyUrl,
                'returnUrl' => $returnUrl,
                'subject' => $subject,
                'body' => $body
            );
            $sort_array = $utility->arg_sort($arr);
            $arr['sign'] = $utility->build_mysign($sort_array, $merchant_key, "MD5");//Generate signature parameter sign
            $param = 'params=' . json_encode($arr);
            $resBody = $utility->request($url, $param);
            $res = json_decode($resBody, true);
        } else {
            $url = 'https://api.iotpaycloud.com/v2/cc_purchase';
            $customerId = sprintf("%08d", rand(100, 999));
            $jobNo = 'lazypmart';
            $arr = array(
                'customerId' => $customerId,
                'mchOrderNo' => $order_num,
                'mchId' => $merchant_id,
                'currency' => 'CAD',
                'amount' => intval($price * 100),
                'jobNo' => $jobNo,
                'notifyUrl' => $notifyUrl,
                'returnUrl' => $returnUrl,
                'subject' => $subject,
                'body' => $body,
            );
            $sort_array = $utility_card->arg_sort($arr);
            $arr['sign'] = $utility_card->build_mysign($sort_array, $merchant_key, "MD5");
            $param = json_encode($arr);
            $resBody = $utility_card->request($url, $param);//Submit to the gateway
            $res = json_decode($resBody, true);
            $res['url'] = $res['payUrl'];
        }
        if ($res['retCode'] == "SUCCESS") {
            $this->success('下单成功',$res);
        } else {
            $this->error('下单失败');
        }
    }

    //支付回调
    public function notify(Order $model, Goods $model_goods, OrderDetail $model_detail, User $model_user, Utility $utility)
    {
        $notifytime = time();
        parse_str($_SERVER['QUERY_STRING'], $arr);
        unset($arr["sign"]);
        unset($arr["s"]);
        $merchant_key = config('site.merchantKey');

        $sort_array = $utility->arg_sort($arr);
        $mysign = $utility->build_mysign($sort_array, $merchant_key, "MD5");
        $backsign = $_POST["sign"];
        //file_put_contents('pay.txt',$_SERVER['QUERY_STRING']);
        if ($mysign == $backsign) {
            $order_num = $arr['mchOrderNo']; //订单
            $order = $model->where(['order_num'=>$order_num,'status'=>0])->find();
            if (!$order) {
                return 'success';
            }
            Db::startTrans();
            try {
                //1.商品销量加
                $order_detail = $model_detail->where(['order_id'=>$order['id']])->select();
                foreach ($order_detail as $value) {
                    //1.1.将商品销量加上
                    $model_goods->where('id',$value['goods_id'])->setInc('sales',$value['num']);
                }
                //2.修改订单状态
                $order->status = 1;
                $order->pay_status = 1;
                //3.支付方式
                if($_POST['channeId'] == 'WX_JSAPI'){
                    $order->pay_state = 'wechat';
                }else if($_POST['channeId'] == 'ALIPAY_WAP'){
                    $order->pay_state = 'alipay';
                }else{
                    $order->pay_state = 'card';
                }
                $order->paytime = $notifytime;
                $order->payOrderId = $_POST['payOrderId'];
                $order->save();

                //setPayLog('goods', 'success', $notifytime ,"order_num: ". $order_num. "  " . "\r\n");
                Db::commit();
            } catch (Exception $e) {
                file_put_contents('pay.txt',$e->getMessage(),FILE_APPEND);
                Db::rollback();
                //setPayLog('goods', 'error', $notifytime ,"order_num: ". $order_num. "   错误信息: " . $e->getMessage() . "\r\n");
                $this->success('支付失败');
            }
            $this->success('支付成功');
        } else {
            file_put_contents('pay.txt','签名失败',FILE_APPEND);
            //setPayLog('goods', 'error', $notifytime ,"faild:" . "mysign=" . $mysign . " backsign=" . $backsign . " " . date("Y/m/d") . "   " . date("h:i:sa") . "   " . "Notify" . "   " . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . "\r\n");
            $this->success('支付失败');
        }
    }

    /**
     * 查询订单支付状态
     */
    public function checkOrder(Order $model, OrderValidate $validate)
    {
        $param = $this->request->param();
        //数据验证
        $validate_result = $validate->scene('check_order')->check($param);
        if (!$validate_result) {
            $this->error($validate->getError());
        }
        $order_num = $param['order_num'];
        //判断当前订单支付状态
        $order_status = $model->where('order_num', $order_num)->value('pay_status');

        $data = [
            'order_status' => $order_status
        ];
        $this->success('查询成功', $data);
    }
}
