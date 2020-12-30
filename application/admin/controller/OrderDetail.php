<?php

namespace app\admin\controller;

use app\common\controller\Backend;
use app\common\library\Utility;
use think\Db;
use think\Exception;

/**
 * 订单详情
 *
 * @icon fa fa-circle-o
 */
class OrderDetail extends Backend
{
    
    /**
     * OrderDetail模型对象
     * @var \app\admin\model\OrderDetail
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\OrderDetail;
        $this->order_model = new \app\admin\model\Order;

    }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */
    

    /**
     * 查看
     */
    public function index()
    {
        //当前是否为关联查询
        $this->relationSearch = true;
        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
        if ($this->request->isAjax())
        {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField'))
            {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $refundId = input('refund_id');
            $where1 = [];
            if(!empty($refundId)){
                $where1['order_detail.id'] = db('order_refund')->where(['id'=>$refundId])->value('detail_id');
            }
            $total = $this->model
                    ->with(['goods'])
                    ->where($where)
                    ->where($where1)
                    ->order($sort, $order)
                    ->count();

            $list = $this->model
                    ->with(['goods'])
                    ->where($where)
                    ->where($where1)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();
            foreach ($list as $row) {
                $row->getRelation('goods')->visible(['name']);
            }
            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }
    
    //退款
    public function refund($ids) {
        $row = $this->model->get($ids);
        
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        //获取主订单
        $order = $this->order_model->get($row->order_id);
        
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds)) {
            if (!in_array($row[$this->dataLimitField], $adminIds)) {
                $this->error(__('You have no permission'));
            }
        }
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                $params = $this->preExcludeFields($params);
                if (!in_array($order->status,[1,2,3])) {
                    $this->error(__('当前订单状态不允许退款'));
                }
                if ($row->real_price <= 0) {
                    $this->error(__('实际支付金额过少,无法退款'));
                }
                //判断数量
                
                $refund_ing = db('order_refund')->where(['detail_id'=>$ids,'status'=>0])->sum('num');
                if($params['refund_num'] > $row->num-$row->refund_num-$refund_ing) {
                    $this->error('数量不足,无法退单');
                }
                $result = false;
                Db::startTrans();
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.add' : $name) : $this->modelValidate;
                        $this->model->validateFailException(true)->validate($validate);
                    }
                    //生成退款记录
                    $refund_price = $row->real_price + $row->real_tax_price + $row->real_charge_price + $row->real_reward_price;
                    $insertData = [
                        'user_id'=>$order->user_id,
                        'detail_id'=>$ids,
                        'goods_id'=>$row->goods_id,
                        'order_num'=>$order->payOrderId,
                        'refund_num'=>orderNum(),
                        'price'=>$refund_price * $params['refund_num'],
                        // 'price'=>$refund_price * $num,
                        'refund_goods_price'=>$row['real_price'] * $params['refund_num'],
                        'refund_tax_price'=>$row['real_tax_price'] * $params['refund_num'],
                        'refund_charge_price'=>$row['real_charge_price'] * $params['refund_num'],
                        'refund_reward_price'=>$row['real_reward_price'] * $params['refund_num'],
                        'num'=>$params['refund_num'],
                        'reason'=>$params['reason'],//退款原因
                        'mark'=>$params['mark'],//退款说明
                        'status'=>1,
                        'createtime'=>time(),
                        'checktime'=>time(),
                        'pay_status'=>2,
                        'old_status'=>$order->status,
                    ];
                    db('order_refund')->insert($insertData);
                    //将子订单状态改为部分退款
                    $row->refund_num += $params['refund_num'];
                    if ($row->refund_num < $row->num) { //部分退款
                        $row->refund_status = 1;
                    } else { //全部退款
                        $row->refund_status = 2;
                    }
                    $row->status = 2;
                    $result = $row->save();
                    //获取当前订单下是否有部分退款订单
                    if ($this->model->where(['order_id'=>$order->id,'refund_status'=>['neq',2]])->count() == 0) {
                        //将主订单状态改为全部退款
                        $order->refund_status = 2;
                    } else {
                        $order->refund_status = 1;
                    }
                    $order->save();
                    
                    $this->refundPay($insertData);
                    Db::commit();
                } catch (ValidateException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (PDOException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (Exception $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                }
                if ($result !== false) {
                    $this->success();
                } else {
                    $this->error(__('No rows were updated'));
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $this->view->assign("row", $row);
        return $this->view->fetch();
    }
    
    
    public function refundPay($row){
        $utility = new Utility();
        $merchant_id = config('site.merchantid');
        $merchant_key = config('site.merchantKey');
        $url = 'https://api.iotpaycloud.com/v1/refund_order';
        $arr = array(
            'mchId' => $merchant_id,
            'mchRefundNo' => $row['refund_num'],
            'loginName' => 'lazypmart',
            'currency' => 'CAD',
            'refundAmount' => $row['price'] * 100,
            'clientIp' => $utility->real_ip(),
            'payOrderId' => $row['order_num'],
        );
        $sort_array = $utility->arg_sort($arr);
        $arr['sign'] = $utility->build_mysign($sort_array, $merchant_key, "MD5");//Generate signature parameter sign
        $param = 'params=' . json_encode($arr);
        $resBody = $utility->request($url, $param);
        $res = json_decode($resBody, true);
        if ($res['retCode'] != "SUCCESS") {
            $this->error($res['retMsg']);
        }
    }
}
