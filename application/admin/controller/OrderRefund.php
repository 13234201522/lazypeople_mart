<?php

namespace app\admin\controller;

use app\common\controller\Backend;
use app\common\library\Utility;
use think\Db;
use think\exception\PDOException;
use think\exception\ValidateException;

/**
 * 退款申请
 *
 * @icon fa fa-circle-o
 */
class OrderRefund extends Backend
{
    
    /**
     * OrderRefund模型对象
     * @var \app\admin\model\OrderRefund
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\OrderRefund;
        $this->view->assign("statusList", $this->model->getStatusList());
        $this->view->assign("payStatusList", $this->model->getPayStatusList());
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
            $total = $this->model
                    ->with(['user','order_detail','goods'])
                    ->where($where)
                    ->order($sort, $order)
                    ->count();

            $list = $this->model
                    ->with(['user','orderDetail','goods'])
                    ->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();

            foreach ($list as $row) {
                // $row->visible(['id','refund_num','price','images','reason','mark','status','createtime','checktime','pay_status','num']);
                // $row->visible(['user']);
                // $row->visible(['order_detail']);
                // $row->visible(['goods']);
				$row->getRelation('user')->visible(['nickname','avatar']);
				$row->getRelation('order_detail')->visible(['id','goods_id','specs','price','image']);
				$row->getRelation('goods')->visible(['name']);
            }
            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }
    public function edit($ids = null)
    {
        $row = $this->model->get($ids);
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds)) {
            if (!in_array($row[$this->dataLimitField], $adminIds)) {
                $this->error(__('You have no permission'));
            }
        }
        if ($this->request->isPost()) {
            if($row['status'] != 0){
                $this->error('错误操作');
            }
            $params = $this->request->post("row/a");
            $model_detail = new \app\admin\model\OrderDetail; 
            $model_order = new \app\admin\model\Order;
            $detail = $model_detail->where('id',$row->detail_id)->find();
            $order = $model_order->where('id',$detail->order_id)->find();
            
            if ($params) {
                $params['checktime'] = time();
                if($params['status'] == 1){
                    //审核通过 执行打款操作
                    $this->refundPay($row);
                    $params['pay_status'] = 2;
                    //将子订单状态改为部分退款
                    $detail->refund_num += $row->num;
                    if ($detail->refund_num < $detail->num) { //部分退款
                        $detail->refund_status = 1;
                    } else { //全部退款
                        $detail->refund_status = 2;
                    }
                    $detail->save();
                    //获取当前订单下是否有部分退款订单
                    if ($model_detail->where(['order_id'=>$detail->order_id,'refund_status'=>['neq',2]])->count() == 0) {
                        //将主订单状态改为全部退款
                        $order->refund_status = 2;
                    } else {
                        $order->refund_status = 1;
                    }
                    $order->save();
                    
                }
                if($params['status'] == 2) {
                    //将商品商品的变回正常状态
                    $model_detail = new \app\admin\model\OrderDetail;
                    // $model_order = new \app\admin\model\Order;
                    // //数量加回
                    // $detail = $model_detail->where('id',$row->detail_id)->find();
                    $model_detail
                        ->where('id',$row->detail_id)
                        ->update([
                            'status'=>1,
                        ]);
                    // //将主订单修改为待发货状态
                    // $model_order->where('id',$detail->order_id)->update(['status'=>$row->old_status]);
                }
                
                $params = $this->preExcludeFields($params);
                $result = false;
                Db::startTrans();
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : $name) : $this->modelValidate;
                        $row->validateFailException(true)->validate($validate);
                    }
                    $result = $row->allowField(true)->save($params);
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
