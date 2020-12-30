<?php

namespace app\admin\controller;

use app\common\controller\Backend;

/**
 * 优惠券
 *
 * @icon fa fa-circle-o
 */
class Coupon extends Backend
{
    
    /**
     * Coupon模型对象
     * @var \app\admin\model\Coupon
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\Coupon;
        $this->view->assign("statusList", $this->model->getStatusList());
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
                    ->with(['category'])
                    ->where($where)
                    ->order($sort, $order)
                    ->count();

            $list = $this->model
                    ->with(['category'])
                    ->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();

            foreach ($list as $row) {
                
                $row->getRelation('category')->visible(['name']);
            }
            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }
    public function send($ids){
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            $couponInfo = $this->model->where(['id'=>$ids])->find();
            if ($couponInfo->status == 0) {
                $this->error('过期优惠券无法派发');
            }
            
            $userIds = $params['user_ids'];
            $data = [];
            if($userIds == ''){
                //全部用户
                $userIds = db('user')->field('id')->select();
                foreach ($userIds as $row){
                    $arr = [
                        'user_id'=>$row['id'],
                        'coupon_id'=>$ids,
                        'use_price'=>$couponInfo['use_price'],
                        'cut_price'=>$couponInfo['cut_price'],
                        'use_status'=>'0',
                        'status'=>'1',
                        'createtime'=>time(),
                        'updatetime'=>time(),
                    ];
                    $data[] = $arr;
                }
            }else{
                $userIds = explode(',',$userIds);
                foreach ($userIds as $row){
                    $arr = [
                        'user_id'=>$row,
                        'coupon_id'=>$ids,
                        'use_price'=>$couponInfo['use_price'],
                        'cut_price'=>$couponInfo['cut_price'],
                        'use_status'=>'0',
                        'status'=>'1',
                        'createtime'=>time(),
                        'updatetime'=>time(),
                    ];
                    $data[] = $arr;
                }
            }
            db('user_coupon')->insertAll($data);
            $this->success();
        }
        return $this->view->fetch();
    }
}
