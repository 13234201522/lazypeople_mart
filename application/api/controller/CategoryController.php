<?php


namespace app\api\controller;


use app\api\model\Category;
use app\api\validate\CategoryValidate;
use app\common\controller\Api;

class CategoryController extends Api
{
    protected $noNeedLogin = [];
    protected $noNeedRight = ['*'];

    /**
     * 全部分类
     */
    public function all_category (Category $model, CategoryValidate $validate)
    {
        $param = $this->request->param();
        //数据验证
        $validate_result = $validate->scene('all_category')->check($param);
        if (!$validate_result) {
            $this->error($validate->getError());
        }
        $list = $model
            ->with(['sonList'])
            ->where(['pid'=>0,'status'=>'normal'])
            ->order(['weigh'=>'desc'])
            ->select();

        $data = [
            'list' => $list
        ];
        $this->success('查询成功', $data);
    }

}