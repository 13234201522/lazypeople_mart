<?php

namespace app\api\controller;

use app\common\controller\Api;

class ConfigController extends Api
{
    protected $noNeedLogin = ['getAgreement'];
    protected $noNeedRight = '*';

    //协议获取
    public function getAgreement(){
        $type = input('type');
        if($type == 1){
            //注册协议
            $data['content'] = config("site.register");
        }else{
            //隐私政策
            $data['content'] = config("site.Privacy");
        }
        $this->success('成功',$data);
    }

    //获取平台配置
    public function systemConfig(){
        $data = [
            'reward'=>config('site.reward'),
            'real'=>config('site.real'),
        ];
        $this->success('成功',$data);
    }

}
