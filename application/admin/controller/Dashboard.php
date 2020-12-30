<?php

namespace app\admin\controller;

use app\admin\model\User;
use app\common\controller\Backend;
use think\Config;

/**
 * 控制台
 *
 * @icon fa fa-dashboard
 * @remark 用于展示当前系统中的统计数据、统计报表及重要实时数据
 */
class Dashboard extends Backend
{

    /**
     * 查看
     */
    public function index()
    {
//        $seventtime = \fast\Date::unixtime('day', -7);
//        $paylist = $createlist = [];
//        for ($i = 0; $i < 7; $i++)
//        {
//            $day = date("Y-m-d", $seventtime + ($i * 86400));
//            $createlist[$day] = mt_rand(20, 200);
//            $paylist[$day] = mt_rand(1, mt_rand(1, $createlist[$day]));
//        }
//        $hooks = config('addons.hooks');
//        $uploadmode = isset($hooks['upload_config_init']) && $hooks['upload_config_init'] ? implode(',', $hooks['upload_config_init']) : 'local';
//        $addonComposerCfg = ROOT_PATH . '/vendor/karsonzhang/fastadmin-addons/composer.json';
//        Config::parse($addonComposerCfg, "json", "composer");
//        $config = Config::get("composer");
//        $addonVersion = isset($config['version']) ? $config['version'] : __('Unknown');
        $model_user = new User();
        $model_order = new \app\admin\model\Order();
        $model_level = new \app\admin\model\OrderLevel();
        //全部用户
        //总用户数量
        $totaluser = $model_user->count();
        //今日注册
        $beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
        $endToday=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
        $usertoday = $model_user->where(['createtime'=>['between',[$beginToday,$endToday]]])->count();

        //订单总数
        $totalorder = $model_order->where(['pay_status'=>1])->count() +$model_level->where(['status'=>1])->count();
        //总金额
        $totalorderamount = $model_order->where(['pay_status'=>1,'status'=>['NEQ',5]])->sum('pay_price') + $model_level->where(['status'=>1])->sum('price');

        $this->view->assign([
            'totaluser'        => $totaluser,
            'totalviews'       => $usertoday,
            'totalorder'       => $totalorder,
            'totalorderamount' => $totalorderamount,
//            'todayuserlogin'   => 321,
//            'todayusersignup'  => 430,
//            'todayorder'       => 2324,
//            'unsettleorder'    => 132,
//            'sevendnu'         => '80%',
//            'sevendau'         => '32%',
//            'paylist'          => $paylist,
//            'createlist'       => $createlist,
//            'addonversion'       => $addonVersion,
//            'uploadmode'       => $uploadmode
        ]);

        return $this->view->fetch();
    }

}
