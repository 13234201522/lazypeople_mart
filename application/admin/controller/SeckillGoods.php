<?php

namespace app\admin\controller;

use app\common\controller\Backend;
use think\Console;
use think\Db;
use think\exception\PDOException;
use think\exception\ValidateException;

/**
 * 秒杀商品
 *
 * @icon fa fa-circle-o
 */
class SeckillGoods extends Backend
{
    
    /**
     * SeckillGoods模型对象
     * @var \app\admin\model\SeckillGoods
     */
    protected $model = null;
    protected $redis = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\SeckillGoods;
        $this->seckill_model = new \app\admin\model\Seckill;
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
        $seckill_id = $this->request->param('seckill_id');
        if ($this->request->isAjax())
        {
            $seckill_id = $this->request->param('seckill_id');

            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField'))
            {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                    ->with(['goods'])
                    ->where('seckill_id',$seckill_id)
                    ->where($where)
                    ->order($sort, $order)
                    ->count();

            $list = $this->model
                    ->with(['goods'])
                    ->where('seckill_id',$seckill_id)
                    ->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();

            foreach ($list as $row) {
                
                $row->getRelation('goods')->visible(['name']);
            }
            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list, "seckill_id" => $seckill_id);

            return json($result);
        }
        $this->assignconfig('seckill_id', $seckill_id);
        return $this->view->fetch();
    }


    /**
     * 添加
     */
    public function add()
    {
        $seckill_id = $this->request->param('seckill_id');
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                $params = $this->preExcludeFields($params);

                //1.判断当前秒杀当前商品是否存在
                if ($this->model->where(['seckill_id'=>$params['seckill_id'],'goods_id'=>$params['goods_id']])->find()) {
                    $this->error('当前秒杀当前商品已存在');
                }

                $seckill_specs = json_decode($params['seckill_json'],true);
                foreach ($seckill_specs as $key => $value) {
                    //2.判断原价和秒杀价
                    if ($value['price'] < $value['seckill_price']) {
                        $this->error('秒杀价不能大于原价');
                    }
                }
                

                if ($this->dataLimit && $this->dataLimitFieldAutoFill) {
                    $params[$this->dataLimitField] = $this->auth->id;
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
                    //当前秒杀状态
                    $params['status'] = $this->seckill_model->where('id',$params['seckill_id'])->value('status');

                    $result = $this->model->allowField(true)->save($params);
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
                    $this->error(__('No rows were inserted'));
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $this->assign('seckill_id',$seckill_id);
        return $this->view->fetch();
    }

    /**
     * 编辑
     */
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
            $params = $this->request->post("row/a");
            if ($params) {
                $params = $this->preExcludeFields($params);

                $seckill_specs = json_decode($params['seckill_json'],true);
                foreach ($seckill_specs as $key => $value) {
                    //2.判断原价和秒杀价
                    if ($value['price'] < $value['seckill_price']) {
                        $this->error('秒杀价不能大于原价');
                    }
                    //2.判断剩余库存和秒杀库存
//                    if ($value['stock'] < $value['seckill_stock']) {
//                        $this->error('秒杀库存不能大于剩余库存');
//                    }
                }

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

    /**
     * 商品规格数据处理
     */
    public function goods_specs()
    {
        $params = $this->request->post();
        $goods_id = $params['goods_id'];
        //获取当前
        $goods = \app\admin\model\Goods::get($goods_id);
        $specs_arr = json_decode($goods->json,true);
        $seckill_specs = [];
        foreach ($specs_arr as $key => $value) {
            //将数据赋值
            $seckill = [
                'specs' => $value['specs'],
                'price' => $value['price'],
                'stock' => $value['stock'],
                'seckill_price' => '',
                'seckill_stock' => ''
            ];
            $seckill_specs[$key] = $seckill;
        }
        $data = [
            'seckill_specs' => json_encode($seckill_specs),
        ];
        $this->success("", "", $data);
    }
}
