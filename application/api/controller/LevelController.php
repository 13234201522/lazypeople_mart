<?php


namespace app\api\controller;


use app\api\model\OrderLevel;
use app\api\model\User;
use app\api\validate\OrderLevelValidate;
use app\common\controller\Api;
use app\common\library\Utility;
use app\common\library\UtilityCard;
use think\Db;
use think\Exception;

/**
 * 会员
 * Class LevelController
 * @package app\api\controller
 */
class LevelController extends Api
{
    protected $noNeedLogin = ['notify'];
    protected $noNeedRight = ['*'];

    protected $level_arr = [
        'silver' => 1 * 30 * 86400,
        'gold' => 6 * 30 * 86400,
        'masonry' => 12 *30 * 86400,
    ];

    /**
     * 购买会员
     */
    public function buyLevel(OrderLevel $model,User $model_user, Utility $utility, UtilityCard $utility_card, OrderLevelValidate $validate)
    {
        $param = $this->request->param();
        //数据验证
        $validate_result = $validate->scene('bug_level')->check($param);
        if (!$validate_result) {
            $this->error($validate->getError());
        }
        $merchant_id = config('site.merchantid');
        $merchant_key = config('site.merchantKey');
        $appId = config('site.appId');
        if (!($merchant_id && $merchant_key && $appId)) {
            $this->error('支付暂未开通: 缺少支付配置');
        }

        $user_id = $this->_uid;
        //1.生成待支付订单
        $order_num = orderNum();
        $level = $param['level'];
        $pay_state = $param['pay_state'];
        $user = $model_user::get($user_id);
        $ip = $utility->real_ip();
        $price = config('site.'.$level.'_price');
        $body = '用户'.$user_id.'购买会员';
        $params_order = [
            'user_id' => $user_id,
            'order_num' => $order_num,
            'level' => $level,
            'price' => $price,
            'client_ip' => $ip,
            'remarks' => $body,
        ];

        if (!$model->save($params_order)) {
            $this->error('下单失败');
        }

        $subject = '购买会员';
        $body = '用户'.$user_id.'购买会员';
        $notifyUrl = 'https://'. $_SERVER['HTTP_HOST'] .'/api/level/notify';
        $returnUrl = 'https://'. $_SERVER['HTTP_HOST'] .'/payreturn/index.html'; //required

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

//            file_put_contents('1.txt', '发送参数:'.$param."\r\n",FILE_APPEND);

            $resBody = $utility->request($url, $param);
            $res = json_decode($resBody, true);
//            file_put_contents('1.txt', '请求路径:'.$res['url']."\r\n",FILE_APPEND);
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

        if ($res['retCode'] == 'SUCCESS') {
            $this->success('下单成功',$res);
        } else {
            $this->error('下单失败');
        }
    }

    /**
     * 回调
     */
    public function notify(OrderLevel $model, User $model_user, Utility $utility)
    {
        $notifytime = time();
//        file_put_contents('1.txt', '原始数据:'.$_SERVER['QUERY_STRING']."\r\n",FILE_APPEND);
        parse_str($_SERVER['QUERY_STRING'], $arr);
        unset($arr["sign"]);
        unset($arr["s"]);
        $merchant_key = config('site.merchantKey');

        $sort_array = $utility->arg_sort($arr);
        $mysign = $utility->build_mysign($sort_array, $merchant_key, "MD5");
        $backsign = $_POST["sign"];

//        file_put_contents('1.txt', '订单号:'.$arr['mchOrderNo']."\r\n",FILE_APPEND);
//        file_put_contents('1.txt', '返回数组信息:'.json_encode($arr)."\r\n",FILE_APPEND);
//        file_put_contents('1.txt', '生成签名:'.$mysign."\r\n",FILE_APPEND);
//        file_put_contents('1.txt', '返回签名:'.$backsign."\r\n\n",FILE_APPEND);
        if ($mysign == $backsign) {
//            setPayLog('level', 'success', $notifytime ,"success:" . "mysign=" . $mysign . " backsign=" . $backsign . " " . date("Y/m/d") . "   " . date("h:i:sa") . "   " . "Notify" . "   " . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . "\r\n");
//            //Complete the system payment transaction process, such as: change the order payment status to paid

            $order_num = $arr['mchOrderNo'];
            $order_level = $model->where(['order_num'=>$order_num,'status'=>0])->find();
            if (!$order_level) {
                $this->success('支付成功');
            }
            Db::startTrans();
            try {
                //1.修改用户会员状态
                $user = User::get($order_level['user_id']);
                if ($user->level == 1) { //续费
                    //结束时间
                    $user->leveltime += $this->level_arr[$order_level->level];
                } else { //新购
                    $user->level = 1;
                    $user->starttime = $notifytime;
                    $user->leveltime = $notifytime + $this->level_arr[$order_level->level];
                }
                $user->save();

                //2.修改订单状态
                $order_level->status = 1;
                //3.支付方式


                $order_level->paytime = $notifytime;
                $order_level->save();

                Db::commit();
            } catch (Exception $e) {
                Db::rollback();
                setPayLog('level','error', $notifytime ,"order_num: ". $order_num. "   错误信息: " . $e->getMessage() . "\r\n");
                $this->success('支付失败');
            }


            $this->success('支付成功');
        } else {
            setPayLog('level', 'error', $notifytime ,"faild:" . "mysign=" . $mysign . " backsign=" . $backsign . " " . date("Y/m/d") . "   " . date("h:i:sa") . "   " . "Notify" . "   " . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . "\r\n");
            $this->success('支付失败');
        }
    }

    /**
     * 检查订单是否支付成功
     */
    public function orderCheck(OrderLevel $model, OrderLevelValidate $validate)
    {
        $param = $this->request->param();
        //数据验证
        $validate_result = $validate->scene('order_check')->check($param);
        if (!$validate_result) {
            $this->error($validate->getError());
        }
        $order_num = $param['order_num'];
        $order_status = $model->where('order_num', $order_num)->value('status');

        $data = [
            'order_status' => $order_status
        ];
        $this->success('查询成功', $data);
    }
}
