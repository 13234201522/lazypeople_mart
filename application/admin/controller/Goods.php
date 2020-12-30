<?php

namespace app\admin\controller;

use app\common\controller\Backend;
use fast\Tree;
use think\Db;
use think\exception\PDOException;
use think\exception\ValidateException;

/**
 * 商品
 *
 * @icon fa fa-circle-o
 */
class Goods extends Backend
{

    /**
     * Goods模型对象
     * @var \app\admin\model\Goods
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\Goods;
        $this->model_category = model('app\admin\model\Category');
        $this->model_seckill = new \app\admin\model\SeckillGoods;
        $this->view->assign("statusList", $this->model->getStatusList());
        
        
        $this->categorys = $this->model_category->field('id,pid,name,weigh')->order('weigh desc,id desc')->select();

        $this->categoryList = collection($this->categorys)->toArray();
        Tree::instance()->init($this->categoryList, 'pid');
        $this->categoryList = Tree::instance()->getTreeList(Tree::instance()->getTreeArray(0), 'name');
        $categorydata = [0 => ['name' => __('None')]];
        foreach ($this->categoryList as $k => &$v) {
            $categorydata[$v['id']] = $v;
        }
        unset($v);
        $this->view->assign('categorydata', $categorydata);
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

    /**
     * 添加
     */
    public function add()
    {
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                $params = $this->preExcludeFields($params);

                if ($this->dataLimit && $this->dataLimitFieldAutoFill) {
                    $params[$this->dataLimitField] = $this->auth->id;
                }
                //1.判断当前商品分类是一级还是二级
                $category = \app\admin\model\Category::get($params['category_id']);
                if ($category['pid'] == 0) {
                    $this->error(__('请勿选择一级商品分类'));
                }
                //2.判断当前商品规格是否为空
                $specs_arr = json_decode($params['json'], true);
                if (!$specs_arr) {
                    $this->error(__('请上传商品规格'));
                }
                foreach ($specs_arr as $key => $value) {
                    if (!$value['specs']) {
                        $this->error(__('规格不能为空'));
                    }
                    if (!$value['price']) {
                        $this->error(__('价格不能为空'));
                    }
                    if ($value['stock'] == '') {
                        $this->error(__('库存不能为空'));
                    }
                    if (!$value['specs_image']) {
                        $this->error(__('规格图片不能为空'));
                    }
                    
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
                    $params['default_price'] = $specs_arr[0]['price'];
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

            //判断当前商品是否处于秒杀状态,秒杀状态中的商品不能修改
            if (goodsSeckillStatus($ids)['seckill_status'] == 1) {
                $this->error('当前商品秒杀中请勿修改或删除');
            }

            $params = $this->request->post("row/a");
            if ($params) {
                $params = $this->preExcludeFields($params);
                //1.判断当前商品分类是一级还是二级
                $category = \app\admin\model\Category::get($params['category_id']);
                if ($category['pid'] == 0) {
                    $this->error(__('请勿选择一级商品分类'));
                }
                //2.判断当前商品规格是否为空
                $specs_arr = json_decode($params['json'], true);
                if (!$specs_arr) {
                    $this->error(__('请上传商品规格'));
                }
                foreach ($specs_arr as $key => $value) {
                    if (!$value['specs']) {
                        $this->error(__('规格不能为空'));
                    }
                    if (!$value['price']) {
                        $this->error(__('价格不能为空'));
                    }
                    if ($value['stock'] == '') {
                        $this->error(__('库存不能为空'));
                    }
                    if (!$value['specs_image']) {
                        $this->error(__('规格图片不能为空'));
                    }
                    
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
                    $params['default_price'] = $specs_arr[0]['price'];
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
     * 删除
     */
    public function del($ids = "")
    {
        if ($ids) {

            //判断当前商品是否处于秒杀状态,秒杀状态中的商品不能修改
            if (goodsSeckillStatus($ids)['seckill_status'] == 1) {
                $this->error('当前商品秒杀中请勿修改或删除');
            }

            $pk = $this->model->getPk();
            $adminIds = $this->getDataLimitAdminIds();
            if (is_array($adminIds)) {
                $this->model->where($this->dataLimitField, 'in', $adminIds);
            }
            $list = $this->model->where($pk, 'in', $ids)->select();

            $count = 0;
            Db::startTrans();
            try {
                foreach ($list as $k => $v) {
                    $count += $v->delete();
                }
                Db::commit();
            } catch (PDOException $e) {
                Db::rollback();
                $this->error($e->getMessage());
            } catch (Exception $e) {
                Db::rollback();
                $this->error($e->getMessage());
            }
            if ($count) {
                $this->success();
            } else {
                $this->error(__('No rows were deleted'));
            }
        }
        $this->error(__('Parameter %s can not be empty', 'ids'));
    }
}
