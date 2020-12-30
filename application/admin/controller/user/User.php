<?php

namespace app\admin\controller\user;

use app\common\controller\Backend;
use fast\Tree;

/**
 * 会员管理
 *
 * @icon fa fa-user
 */
class User extends Backend
{
    
    /**
     * User模型对象
     * @var \app\admin\model\user\User
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\user\User;
        $this->view->assign("statusList", $this->model->getStatusList());
        $this->view->assign("levelList", $this->model->getLevelList());
    }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */
    /**
     * Selectpage搜索
     *
     * @internal
     */
    public function selectpage()
    {
        //当前页
        $page = $this->request->request("pageNumber");
        //分页大小
        $pagesize = $this->request->request("pageSize");
        $list = $this->model->page($page,$pagesize)->select();
        $total = $this->model->count();
        foreach ($list as &$row){
            $row['name'] = $row->nickname;
        }
        return json(['list' => $list,'total'=>$total]);
    }

}
