<?php


namespace app\api\controller;


use app\api\model\Goods;
use app\api\model\Seckill;
use app\api\model\SeckillGoods;
use app\api\validate\SeckillValidate;
use app\common\controller\Api;


/**
 * 秒杀商品
 * Class SeckillController
 * @package app\api\controller
 */
class SeckillController extends Api
{
    protected $noNeedLogin = [];
    protected $noNeedRight = ['*'];

    /**
     * 全部秒杀商品列表
     */
    public function index(Seckill $model_seckill, SeckillGoods $model_seckill_goods, Goods $model_goods,SeckillValidate $validate)
    {
        $param = $this->request->param();
        //数据验证
        $validate_result = $validate->scene('index')->check($param);
        if (!$validate_result) {
            $this->error($validate->getError());
        }
        $page = $param['page'];
        $num = 10;
        $beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
        $time = time();
        //今日秒杀
        $seckill_today = $model_seckill
            ->where([
                'endtime'=>['>',$time],
                'starttime'=>['<',$time]
                ])
            ->find();
        $seckill_status = $seckill_today ? $seckill_today['status'] : '';
        $starttime = $seckill_today ? $seckill_today['starttime_text'] : '';
        $endtime = $seckill_today ? $seckill_today['endtime_text'] : '';
        //4.2秒杀商品
        $goods = $model_seckill_goods
            ->with('goods_brief')
            ->where(['seckill_id'=>$seckill_today['id']])
            ->page($page)
            ->limit($num)
            ->select();

        $count = $model_seckill_goods
            ->where(['seckill_id'=>$seckill_today['id']])
            ->count();
        $allpage = (string)ceil($count/$num);


        $data = [
            'seckill_status' => $seckill_status,
            'starttime' => $starttime,
            'endtime' => $endtime,
            'goods' => $goods,
            'page' => $page,
            'allpage' => $allpage,
        ];

        $this->success('查询成功',$data);
    }
}