<?php

/**
 * 登录注册
 */
namespace app\api\model;

use app\common\library\Rongcloud;
use app\common\library\Token;
use fast\Pinyin;
use fast\Random;
use think\Db;
use think\Exception;
use think\Model;

class Login extends Model
{
    protected $_error;

    //Token默认有效时长
    protected $keeptime = 2592000;

    /**
     * @param $openid 微信唯一id
     * @param $avatar 头像
     * @param $nickname 昵称
     * @throws \Exception
     */
    public function register($openid, $avatar, $nickname)
    {
        $user_model = new User();

        $time = time();
        //将头像保存本地
        $avatar = download($avatar);

        $params = [
            'nickname'   => $nickname,
            'avatar'   => $avatar,
            'openid'   => $openid,
            'prevtime'  => $time,
            'createtime'  => $time,
            'updatetime'  => $time,
            'status'    => 1,
        ];

        //账号注册时需要开启事务,避免出现垃圾数据
        Db::startTrans();
        try {

            //注册
            $user_id = $user_model->insertGetId($params);

            //直接登录会员
            $token = $this->direct($user_id);

            Db::commit();
        } catch (Exception $e) {
            $this->setError($e->getMessage());
            Db::rollback();
            return false;
        }
        return $token;
    }

    /**
     * 直接登录账号
     * @param int $user_id
     * @return boolean
     */
    public function direct($user_id)
    {
        $user_model = new User();
        $user = $user_model::get($user_id)->getData();
        if ($user) {
            Db::startTrans();
            try {
                $time = time();
                $token = Random::uuid();
                $user['token'] = $token;
                $user['prevtime'] = $time;
                Token::set($token, $user_id, $this->keeptime);

                $user_model->where('id',$user['id'])->update($user);

                Db::commit();
            } catch (Exception $e) {
                Db::rollback();
                $this->setError($e->getMessage());
                return false;
            }

            return $token;
        } else {
            return false;
        }
    }

    /**
     * 设置错误信息
     *
     * @param $error 错误信息
     * @return Auth
     */
    public function setError($error)
    {
        $this->_error = $error;
        return $this;
    }

    /**
     * 获取错误信息
     * @return string
     */
    public function getError()
    {
        return $this->_error ? __($this->_error) : '';
    }
}