<?php


namespace app\api\controller;


use app\api\model\Address;
use app\api\validate\AddressValidate;
use app\common\controller\Api;
use think\Cache;
use think\Db;

/**
 * 地址
 * Class AddressController
 * @package app\api\controller
 */
class AddressController extends Api
{
    protected $noNeedLogin = [];
    protected $noNeedRight = ['*'];

    //列表
    public function index(Address $model, AddressValidate $validate)
    {
        $param = $this->request->param();
        //数据验证
//        $validate_result = $validate->scene('index')->check($param);
//        if (!$validate_result) {
//            $this->error($validate->getError());
//        }
        $user_id = $this->_uid;
        //获取当前用户全部
        $list = $model
            ->where(['user_id'=>$user_id])
            ->order('status','desc')
            ->select();
        $data = [
            'list' => $list
        ];

        $this->success('查询成功',$data);
    }

    //添加
    public function add(Address $model, AddressValidate $validate)
    {
        $param = $this->request->param();
        //数据验证
        $validate_result = $validate->scene('add')->check($param);
        if (!$validate_result) {
            $this->error($validate->getError());
        }
        $user_id = $this->_uid;
        $param['user_id'] = $user_id;
        $status = $param['status'];
        unset($param['token']);
        Db::startTrans();
        try{
            if ($status == 1) {
                //1.将当前用户的其他地址更新为普通
                $model
                    ->where([
                        'user_id' => $user_id
                    ])
                    ->update([
                        'status'=>0,
                        'updatetime'=>time()
                    ]);
            }
            //2.新增地址
            $model->save($param);
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            $this->error('添加失败:'.$e->getMessage());
            // 回滚事务
            Db::rollback();
        }
        $this->success('添加成功');
    }

    //修改
    public function edit(Address $model, AddressValidate $validate)
    {
        $param = $this->request->param();
        //数据验证
        $validate_result = $validate->scene('edit')->check($param);
        if (!$validate_result) {
            $this->error($validate->getError());
        }
        $user_id = $this->_uid;
        $address_id = $param['address_id'];
        $status = $param['status'];
        unset($param['token']);
        unset($param['address_id']);
        Db::startTrans();
        try{
            $address = $model::get($address_id);
            if ($status == 1) {
                //1.将当前用户的其他地址更新为普通
                $model
                    ->where([
                        'user_id' => $user_id
                    ])
                    ->update([
                        'status'=>0,
                        'updatetime'=>time()
                    ]);
            }
            //2.新增地址
            $address->save($param);
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            $this->error('修改失败:'.$e->getMessage());
            // 回滚事务
            Db::rollback();
        }
        $this->success('修改成功');

    }

    //删除
    public function del(Address $model, AddressValidate $validate)
    {
        $param = $this->request->param();
        //数据验证
        $validate_result = $validate->scene('del')->check($param);
        if (!$validate_result) {
            $this->error($validate->getError());
        }
        $user_id = $this->_uid;
        $address_id = $param['address_id'];
        if ($model::destroy($address_id)) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
     }

    /**
     * 刘志旭臭不要脸非让我加的接口
     */
     public function liuzhixuSet()
     {
         $param = $this->request->param();
         $user_id = $this->_uid;
         unset($param['token']);

         if (cache('address_'.$user_id, json_encode($param))) {
            $this->success('成功');
         } else {
             $this->error('失败');
         }
     }

     public function liuzhixuGet()
     {
         $param = $this->request->param();
         $user_id = $this->_uid;
         $data = cache('address_'.$user_id);
         $this->success('成功',json_decode(htmlspecialchars_decode($data),true));
     }
}
