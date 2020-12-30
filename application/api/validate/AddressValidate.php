<?php


namespace app\api\validate;


use think\Validate;

class AddressValidate extends Validate
{
    protected $rule = [
        'address_id|地址id' => 'require',
        'link_name|联系人' => 'require',
        'link_mobile|联系电话' => 'require',
        'province|省' => 'require',
        'province_place_id|谷歌省id' => 'require',
        'city|市' => 'require',
        'city_place_id|谷歌市id' => 'require',
        'area|区' => 'require',
        'detail|详细地址' => 'require',
        'status|默认状态' => 'require|in:0,1',
    ];

    protected $message = [

    ];

    protected $scene = [
        'add' => ['link_name','link_mobile','province','province_place_id','city','city_place_id','detail','status'],
        'del' => ['address_id'],
        'edit' => ['address_id','link_name','link_mobile','province','province_place_id','city','city_place_id','detail','status'],
    ];
}