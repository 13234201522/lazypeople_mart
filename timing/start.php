<?php
use Workerman\Worker;
use Workerman\Timer;

require_once __DIR__ . '/vendor/autoload.php';

$task = new Worker();
$task->onWorkerStart = function ($task) {
    $time_interval = 1; //执行周期
    $timer_id = Timer::add($time_interval, function () {
        //请求定时任务控制器
        //开启秒杀
        $res1 = send_post('https://site.lazypeoplemart.store/api/timing/startSeckill','');

        //结束秒杀
        $res2 = send_post('https://site.lazypeoplemart.store/api/timing/endSeckill','');

        //用户会员状态
        $res3 = send_post('https://site.lazypeoplemart.store/api/timing/userLevel','');

        //下单十五分钟未支付取消订单
        $res4 = send_post('https://site.lazypeoplemart.store/api/timing/cancelOrder','');

        //发货十五天自动收货
        $res5 = send_post('https://site.lazypeoplemart.store/api/timing/receiveOrder','');

        //优惠券过期
        $res6 = send_post('https://site.lazypeoplemart.store/api/timing/overTimeCoupon','');

        //满减活动开启
        $res7 = send_post('https://site.lazypeoplemart.store/api/timing/startFullcut','');

        //刷新配置文件
        $res8 = send_post('http://site.lazypeoplemart.store/api/timing/refreshFile','');

    });
};

function send_post( $url, $post = '', $timeout = 1 ){
    if( empty( $url ) ){
        return ;
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

    if( $post != '' && !empty( $post ) ){
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($post)));
    }
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}


// Run all workers
Worker::runAll();
