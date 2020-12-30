<?php


namespace app\api\controller;


use app\api\model\Coupon;
use app\api\model\Order;
use app\api\model\Seckill;
use app\api\model\SeckillGoods;
use app\api\model\User;
use app\api\model\UserCoupon;
use app\common\controller\Api;
use app\common\model\Config;
use think\Db;

/**
 * 定时任务
 * Class TimingController
 * @package app\api\controller
 */
class TimingController extends Api
{

    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];

    //开启秒杀
    public function startSeckill(Seckill $model_seckill, SeckillGoods $model_seckill_goods)
    {
        $time = time();
        //1.秒杀开关
        $seckill_switch = config('site.seckill_switch');

        $seckill = $model_seckill
            ->where([
                'starttime' => ['<=',time()],
                'endtime' => ['>=',time()],
            ])
            ->find();
        //
        if ($seckill_switch && $seckill) {
            // 启动事务
            Db::startTrans();
            try{
                //更新抢购状态
                $model_seckill->where(['id'=>$seckill['id']])->update(['status'=>1, 'updatetime'=>$time]);

                //更新抢购商品状态
                $model_seckill_goods->where(['seckill_id'=>$seckill['id']])->update(['status'=>1, 'updatetime'=>$time]);

                // 提交事务
                Db::commit();
            } catch (\Exception $e) {
                $this->error('执行失败:'.$e->getMessage());
                // 回滚事务
                Db::rollback();
            }
            $this->success('执行成功');
        }
    }

    //结束秒杀
    public function endSeckill(Seckill $model_seckill, SeckillGoods $model_seckill_goods)
    {
        $time = time();
        //1.秒杀开关
        $seckill_switch = config('site.seckill_switch');
        //今日开始时间
        $beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
        $endtime = date('H',$time);
        
        //今日秒杀
        $seckill_arr = $model_seckill
            ->where([
                'endtime' => ['<=',time()],
                'status' => ['neq',2],
            ])
            ->select();
            // 启动事务
        Db::startTrans();
        try{
            foreach ($seckill_arr as $value){
                //更新抢购状态
                $model_seckill->where(['id'=>$value['id']])->update(['status'=>2, 'updatetime'=>$time]);
                //更新抢购商品状态
                $model_seckill_goods->where(['seckill_id'=>$value['id']])->update(['status'=>2, 'updatetime'=>$time]);
                            
            }
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            $this->error('执行失败:'.$e->getMessage());
            // 回滚事务
            Db::rollback();
        }
        $this->success('执行成功');
    }

    //用户会员状态
    public function userLevel(User $model)
    {
        $time = time();
        $res = $model
            ->where(['level'=>1,'leveltime'=>['<=',$time]])
            ->update(['level'=>0]);
        $this->success('执行成功');
    }

    //十五分钟未支付取消订单
    public function cancelOrder(Order $model)
    {
        $time = time();
        $order_ids = $model
            ->where([
                'status' => 0,
                'createtime' => ['<=',($time-60*15)],
            ])
            ->column('id');
        $order_ids = implode(',',$order_ids);
        $res = $model->cancelOrder($order_ids);
        $this->success('执行成功');
    }

    //发货15天后自动收货
    public function receiveOrder(Order $model)
    {
        $time = time();
        $order_ids = $model
            ->where([
                'status' => 3,
                'sendtime' => ['<=',($time-60*60*24*15)],
            ])
            ->column('id');
        $order_ids = implode(',',$order_ids);
        $res = $model->receive($order_ids);
        $this->success('执行成功');
    }

    //优惠券过期
    public function overTimeCoupon(Coupon $model, UserCoupon $model_user_coupon)
    {
        $time = time();
        //获取超过时间优惠券
        $coupoen_ids = $model
            ->where(['endtime'=>['<',$time]])
            ->column('id');

        // 启动事务
        Db::startTrans();
        try{

            //将优惠券修改为过期状态
            $model->where(['id'=>['in',$coupoen_ids]])->update(['status'=>0,'hot_switch'=>0,'updatetime'=>$time]);

            //将用户优惠券改为过期状态
            $model_user_coupon->where(['coupon_id'=>['in',$coupoen_ids]])->update(['status'=>0,'updatetime'=>$time]);

            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            $this->error('执行失败:'.$e->getMessage());
            // 回滚事务
            Db::rollback();
        }
        $this->success('执行成功');
    }

    /**
     * 开启满减活动
     */
    public function startFullcut(Config $model)
    {
        //进行中
        $fullcut = db('fullcut')
            ->where('starttime <' .time())
            ->where('endtime >' .time())
            ->field('id')
            ->where(['status'=>'normal'])->select();
        foreach ($fullcut as $row){
            db('fullcut')->where(['id'=>$row['id']])->update([
                'sta_code'=>2
            ]);
            $this->changeGoodsStatus($row['id'],1);
        }
        //结束
        $fullcut2 = db('fullcut')
            ->where('endtime <' .time())
            ->field('id')
            ->where(['sta_code'=>2])->select();
        foreach ($fullcut2 as $r){
            db('fullcut')->where(['id'=>$r['id']])->update([
                'sta_code'=>3
            ]);
            $this->changeGoodsStatus($r['id'],2);
        }
        $this->success('成功');
    }

    //改变商品满减状态
    function changeGoodsStatus($fullcutid,$type){
        $list = db('fullcut_goods')->where([
            'fullcut_id'=>$fullcutid
        ])->select();
        if($type == 1){
            //开启满减
            foreach ($list as $item){
                db('goods')->where(['id'=>$item['goods_id']])->update([
                    'fullcut_switch'=>1
                ]);
            }
        }else{
            //关闭满减
            foreach ($list as $item){
                db('goods')->where(['id'=>$item['goods_id']])->update([
                    'fullcut_switch'=>0
                ]);
            }
        }
    }

    /**
     * 刷新配置文件
     */
    public function refreshFile()
    {
        $model = new Config();
        $config = [];
        foreach ($model->all() as $k => $v) {
            $value = $v->toArray();
            if (in_array($value['type'], ['selects', 'checkbox', 'images', 'files'])) {
                $value['value'] = explode(',', $value['value']);
            }
            if ($value['type'] == 'array') {
                $value['value'] = (array)json_decode($value['value'], true);
            }
            $config[$value['name']] = $value['value'];
        }
        file_put_contents(
            APP_PATH . 'extra' . DS . 'site.php',
            '<?php' . "\n\nreturn " . var_export($config, true) . ";"
        );
    }

}
