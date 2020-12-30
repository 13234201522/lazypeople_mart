<?php

namespace app\common\model;

use think\Model;

/**
 * 分类模型
 */
class Category extends Model
{

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    // 追加属性
    protected $append = [
    ];

    protected static function init()
    {
        self::afterInsert(function ($row) {
            $row->save(['weigh' => $row['id']]);
        });
    }

    public function getStatusList()
    {
        return ['normal' => __('Normal'), 'hidden' => __('Hidden')];
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }

    /**
     * 读取分类列表
     * @param string $type   指定类型
     * @param string $status 指定状态
     * @return array
     */
    public static function getCategoryArray($type = null, $status = null)
    {
        $list = collection(self::where(function ($query) use ($type, $status) {
            if (!is_null($type)) {
                $query->where('type', '=', $type);
            }
            if (!is_null($status)) {
                $query->where('status', '=', $status);
            }
        })->order('weigh', 'desc')->select())->toArray();
        return $list;
    }
}
