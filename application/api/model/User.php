<?php

/**
 * Class 用户模型
 * Author: @YJQ@
 */

namespace app\api\model;


use fast\Random;
use think\Model;

class User extends Model
{

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';

    protected $append = [
    ];

    protected $hidden = [
        'openid',
        'token',
    ];

    /**
     * 根据手机号
     * Author @YJQ@
     */
    public function getByMobile($mobile)
    {
        return $this->where('mobile',$mobile)->find();
    }


    //头像获取器
    public function getAvatarAttr($value)
    {
        return cdnurl($value);
    }


    //注册时间
    public function getCreatetimeAttr($value)
    {
        return date('Y-m-d',$value);
    }

    //会员开始时间
    public function getStarttimeAttr($value)
    {
        return $value ? date('Y-m-d',$value) : '';
    }

    //会员到期时间
    public function getLeveltimeAttr($value)
    {
        return $value ? date('Y-m-d',$value) : '';
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