<?php

/**
 * Class 登录注册
 * Author: @YJQ@
 */

namespace app\api\controller;


use app\api\model\Login;
use app\api\model\User;
use app\api\validate\LoginValidate;
use app\common\controller\Api;
use app\common\library\Sms;

class LoginController extends Api
{
    protected $noNeedLogin = ['thirdLogin','weChatLogin'];
    protected $noNeedRight = '*';


    //微信网页登录
    public function weChatLogin(){
        $code = input('code');
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=wx77b42ee4be836c7e&secret=8d663f69b7444e1b37f9ea7962224ede&code='.$code.'&grant_type=authorization_code';
        $weChatData = json_decode(file_get_contents($url),true);
        if(!empty($weChatData['openid'])){
            $infoUrl = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$weChatData['access_token'].'&openid='.$weChatData['openid'].'&lang=zh_CN';
            $info = json_decode(file_get_contents($infoUrl),true);
            $model_user = new User();
            $login = new Login();
            $user = $model_user::get(['openid'=>$info['openid']]);
            if ($user) {
                //如果已经有账号则直接登录
                $token = $login->direct($user->id);
                $is_mobile = $user->mobile ? 1 : 0;
            } else {
                //没有账号进行注册
                $token = $login->register($info['openid'],$info['headimgurl'],$info['nickname']);
                $is_mobile = 0;
            }
            if ($token) {
                $data = [
                    'token' => $token,
                    'is_mobile' => $is_mobile,
                ];
                $this->success(__('登录成功'), $data);
            } else {
                $this->error($login->getError());
            }
        }else{
            $this->error($weChatData['errmsg'],$weChatData['errcode']);
        }

    }
    /**
     * 三方登录
     * Author @YJQ@
     */
    public function thirdLogin(Login $model, User $model_user, LoginValidate $validate)
    {
        $param = $this->request->param();
        //数据验证
        $validate_result = $validate->scene('third_login')->check($param);
        if (!$validate_result) {
            $this->error($validate->getError());
        }
        $openid = $param['openid'];
        $avatar = $param['avatar'];
        $nickname = $param['nickname'];

        $user = $model_user::get(['openid'=>$openid]);
        if ($user) {
            //如果已经有账号则直接登录
            $token = $model->direct($user->id);
            $is_mobile = $user->mobile ? 1 : 0;
        } else {
            //没有账号进行注册
            $token = $model->register($openid, $avatar, $nickname);
            $is_mobile = 0;

        }
        if ($token) {
            $data = [
                'token' => $token,
                'is_mobile' => $is_mobile,
            ];
            $this->success(__('登录成功'), $data);
        } else {
            $this->error($model->getError());
        }
    }

    /**
     * 微信授权手机号
     * Author @YJQ@
     */
    public function bindMobile(Login $model, User $model_user, LoginValidate $validate)
    {
        $param = $this->request->param();
        //数据验证
        $validate_result = $validate->scene('bind_mobile')->check($param);
        if (!$validate_result) {
            $this->error($validate->getError());
        }
        $user_id = $this->_uid;
        $mobile = $param['mobile'];

        //判断当前手机号是否绑定三方
        $user = $model_user::get(['mobile'=>$mobile]);
        if ($user) {
            $this->error('当前手机号已注册');
        }
        //将手机号绑定到用户
        $res = $model_user->where(['id'=>$user_id])->update(['mobile'=>$mobile,'updatetime'=>time()]);
        if (!$res) {
            $this->error('手机号授权失败');
        } else {
            $this->success('授权成功');
        }

    }

}