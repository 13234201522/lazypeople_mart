<?php


namespace app\api\controller;


use app\api\model\Cart;
use app\api\model\Goods;
use app\api\validate\CartValidate;
use app\common\controller\Api;
use think\Db;
use think\Exception;

class CartController extends Api
{
    protected $noNeedLogin = [];
    protected $noNeedRight = ['*'];

    /**
     * 加入购物车
     */
    public function add(Cart $model, Goods $model_goods,CartValidate $validate)
    {
        $param = $this->request->param();
        //数据验证
        $validate_result = $validate->scene('add_cart')->check($param);
        if (!$validate_result) {
            $this->error($validate->getError());
        }
        $goods_id = $param['goods_id'];
        $specs = $param['specs'];
        $num = $param['num'];
        $price = $param['price'];
        $goods = $model_goods::get($goods_id);
        if (!$goods) {
            $this->error('加入失败,商品不存在');
        }
        $specs_text = $goods['specs_text'];
        $specs_select = selectSpecs($specs_text,$specs);
        if (!$specs_select) {
            $this->error('加入失败,所选规格不存在');
        }
        //1.判断库存
        if ($num > $specs_select['stock']) {
            $this->error('加入失败,库存不足');
        }
        $user_id = $this->_uid;
        unset($param['token']);
        $param['user_id'] = $user_id;
        $cart = $model->where(['user_id'=>$user_id,'goods_id'=>$goods_id,'specs'=>$specs])->find();
        if (!$cart) {
            //加入购物车
            $res = $model->save($param);
        } else {
            //将购物车数量相加
            $cart['num'] += $num;

            if ($cart['num'] > $specs_select['stock']) {
                $this->error('加入失败,库存不足');
            }
            $cart['price'] = $price;
            $res = $cart->save();
        }
        if ($res) {
            $this->success('加入成功');
        } else {
            $this->error('加入失败');
        }
    }

    /**
     * 购物车列表
     */
    public function index(Cart $model,CartValidate $validate)
    {
        $param = $this->request->param();
        //数据验证
        $validate_result = $validate->scene('index')->check($param);
        if (!$validate_result) {
            $this->error($validate->getError());
        }
        $user_id = $this->_uid;

        //获取当前用户购物车商品列表
        $list = $model
            ->with('goods')
            ->where(['user_id'=>$user_id])
            ->order(['createtime'=>'desc'])
            ->select();
//        $arr2 = [];
//        foreach ($list as $key=>$row){
//            $arr = $row->toArray();
//            $arr['goods']['images'] = $arr['goods']['images'][0];
//            $arr2[] = $arr;
//        }

        $data = [
            'list' => $list
        ];
        $this->success('查询成功',$data);
    }

    /**
     * 购物车多选删除
     */
    public function del(Cart $model,CartValidate $validate)
    {
        $param = $this->request->param();
        //数据验证
        $validate_result = $validate->scene('index')->check($param);
        if (!$validate_result) {
            $this->error($validate->getError());
        }
        $user_id = $this->_uid;
        $cart_ids = $param['cart_ids'];
        //
        $cart_ids_arr = explode(',',$cart_ids);
        $res = $model->where([
            'id' => ['in',$cart_ids_arr]
        ])->delete();
        if ($res) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }

    /**
     * 修改规格
     */
    public function editSpecs(Cart $model,Goods $model_goods, CartValidate $validate)
    {
        $param = $this->request->param();
        //数据验证
        $validate_result = $validate->scene('edit_specs')->check($param);
        if (!$validate_result) {
            $this->error($validate->getError());
        }
        $user_id = $this->_uid;
        $cart_id = $param['cart_id'];
        $specs = $param['specs'];
        $num = $param['num'];
        $price = $param['price'];
        $goods_id = $model->where(['id'=>$cart_id])->value('goods_id');
        $goods = $model_goods::get($goods_id);
        $specs_text = $goods['specs_text'];
        $specs_select = selectSpecs($specs_text,$specs);
        if (!$specs_select) {
            $this->error('加入失败,所选规格不存在');
        }
        //1.判断库存
        if ($num > $specs_select['stock']) {
            $this->error('加入失败,库存不足');
        }
        unset($param['cart_id'],$param['token']);
        $param['updatetime'] = time();
        //
        $cart = $model->where(['user_id'=>$user_id,'goods_id'=>$goods_id,'specs'=>$specs])->find();

        Db::startTrans();
        try {
            if (!$cart) {
                //加入购物车
                $model->where(['id'=>$cart_id])->update($param);
            } else {
                if ($cart_id != $cart['id']) {
                    //将购物车数量相加
                    $cart['num'] += $num;
                    //并删除当前购物车内容
                    $model->where(['id'=>$cart_id])->delete();
                } else {
                    $cart['num'] = $num;
                }
                if ($cart['num'] > $specs_select['stock']) {
                    throw new Exception('加入失败,库存不足');
                }
                $cart['price'] = $price;
                $cart->save();
            }
            Db::commit();
        } catch (Exception $e) {
            $this->error($e->getMessage());
            Db::rollback();
        }
        $this->success('修改成功');
    }

    /**
     * 数量加
     */
    public function numInc(Cart $model, Goods $model_goods, CartValidate $validate)
    {
        $param = $this->request->param();
        //数据验证
        $validate_result = $validate->scene('num_plus')->check($param);
        if (!$validate_result) {
            $this->error($validate->getError());
        }
        $user_id = $this->_uid;
        $cart_id = $param['cart_id'];
        $cart = $model::get($cart_id);
        $goods_id = $cart['goods_id'];
        $specs = $cart['specs'];
        $goods = $model_goods::get($goods_id);
        if (!$goods) {
            $this->error('操作失败,商品不存在');
        }
        $specs_text = $goods['specs_text'];
        $specs_select = selectSpecs($specs_text,$specs);

        if (!$specs_select) {
            //将当前购物车数量清0
            $cart['num'] = 0;
            $cart->save();
            $this->error('操作失败,所选规格不存在');
        }
        //判断当前购物车商品数量是否已经超出库存
        $cart['num'] += 1;
        if ($cart['num'] > $specs_select['stock']) {
            $cart['num'] = $specs_select['stock'];
            $cart->save();
            $this->error('操作失败,库存不足');
        }

        if ($cart->save()) {
            $this->success('操作成功');
        } else {
            $this->error('操作失败');
        }
    }

    /**
     * 数量减
     */
    public function numDec(Cart $model, Goods $model_goods, CartValidate $validate)
    {
        $param = $this->request->param();
        //数据验证
        $validate_result = $validate->scene('num_dec')->check($param);
        if (!$validate_result) {
            $this->error($validate->getError());
        }
        $user_id = $this->_uid;
        $cart_id = $param['cart_id'];
        $cart = $model::get($cart_id);
        $goods_id = $cart['goods_id'];
        $specs = $cart['specs'];
        $goods = $model_goods::get($goods_id);
        if (!$goods) {
            $this->error('操作失败,商品不存在');
        }
        $specs_text = $goods['specs_text'];
        $specs_select = selectSpecs($specs_text,$specs);

        if (!$specs_select) {
            //将当前购物车数量清0
            $cart['num'] = 0;
            $cart->save();
            $this->error('操作失败,所选规格不存在');
        }
        //判断当前购物车商品数量是否已经超出库存
        $cart['num'] -= 1;
        if ($cart['num'] <= 0) {
            $this->error('操作失败,数量不能为0');
        }
        if ($cart['num'] > $specs_select['stock']) {
            $cart['num'] = $specs_select['stock'];
            $cart->save();
            $this->error('操作失败,库存不足');
        }

        if ($cart->save()) {
            $this->success('操作成功');
        } else {
            $this->error('操作失败');
        }
    }
}