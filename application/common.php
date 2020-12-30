<?php

// 公共助手函数

use app\api\model\Seckill;
use app\api\model\SeckillGoods;
use app\api\model\Fullcut;
use fast\Random;

if (!function_exists('__')) {

    /**
     * 获取语言变量值
     * @param string $name 语言变量名
     * @param array  $vars 动态变量值
     * @param string $lang 语言
     * @return mixed
     */
    function __($name, $vars = [], $lang = '')
    {
        if (is_numeric($name) || !$name) {
            return $name;
        }
        if (!is_array($vars)) {
            $vars = func_get_args();
            array_shift($vars);
            $lang = '';
        }
        return \think\Lang::get($name, $vars, $lang);
    }
}

if (!function_exists('format_bytes')) {

    /**
     * 将字节转换为可读文本
     * @param int    $size      大小
     * @param string $delimiter 分隔符
     * @return string
     */
    function format_bytes($size, $delimiter = '')
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
        for ($i = 0; $size >= 1024 && $i < 6; $i++) {
            $size /= 1024;
        }
        return round($size, 2) . $delimiter . $units[$i];
    }
}

if (!function_exists('datetime')) {

    /**
     * 将时间戳转换为日期时间
     * @param int    $time   时间戳
     * @param string $format 日期时间格式
     * @return string
     */
    function datetime($time, $format = 'Y-m-d H:i:s')
    {
        $time = is_numeric($time) ? $time : strtotime($time);
        return date($format, $time);
    }
}

if (!function_exists('human_date')) {

    /**
     * 获取语义化时间
     * @param int $time  时间
     * @param int $local 本地时间
     * @return string
     */
    function human_date($time, $local = null)
    {
        return \fast\Date::human($time, $local);
    }
}

if (!function_exists('cdnurl')) {

    /**
     * 获取上传资源的CDN的地址
     * @param string  $url    资源相对地址
     * @param boolean $domain 是否显示域名 或者直接传入域名
     * @return string
     */
    function cdnurl($url, $domain = false)
    {
        $regex = "/^((?:[a-z]+:)?\/\/|data:image\/)(.*)/i";
//        $url = preg_match($regex, $url) ? $url : \think\Config::get('upload.cdnurl') . $url;
        $url = preg_match($regex, $url) ? $url : config('site.cdnurl') . $url;
        if ($domain && !preg_match($regex, $url)) {
            $domain = is_bool($domain) ? request()->domain() : $domain;
            $url = $domain . $url;
        }
        return $url;
    }
}

if (!function_exists('delCdnurl')) {

    /**
     * 获取上传资源的CDN的地址
     * @param string  $url    资源相对地址
     * @param boolean $domain 是否显示域名 或者直接传入域名
     * @return string
     */
    function delCdnurl($url)
    {
        $cdnurl = config('site.cdnurl');
        return str_replace($cdnurl, '' , $url);
    }
}


if (!function_exists('is_really_writable')) {

    /**
     * 判断文件或文件夹是否可写
     * @param    string $file 文件或目录
     * @return    bool
     */
    function is_really_writable($file)
    {
        if (DIRECTORY_SEPARATOR === '/') {
            return is_writable($file);
        }
        if (is_dir($file)) {
            $file = rtrim($file, '/') . '/' . md5(mt_rand());
            if (($fp = @fopen($file, 'ab')) === false) {
                return false;
            }
            fclose($fp);
            @chmod($file, 0777);
            @unlink($file);
            return true;
        } elseif (!is_file($file) or ($fp = @fopen($file, 'ab')) === false) {
            return false;
        }
        fclose($fp);
        return true;
    }
}

if (!function_exists('rmdirs')) {

    /**
     * 删除文件夹
     * @param string $dirname  目录
     * @param bool   $withself 是否删除自身
     * @return boolean
     */
    function rmdirs($dirname, $withself = true)
    {
        if (!is_dir($dirname)) {
            return false;
        }
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dirname, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($files as $fileinfo) {
            $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
            $todo($fileinfo->getRealPath());
        }
        if ($withself) {
            @rmdir($dirname);
        }
        return true;
    }
}

if (!function_exists('copydirs')) {

    /**
     * 复制文件夹
     * @param string $source 源文件夹
     * @param string $dest   目标文件夹
     */
    function copydirs($source, $dest)
    {
        if (!is_dir($dest)) {
            mkdir($dest, 0755, true);
        }
        foreach (
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::SELF_FIRST
            ) as $item
        ) {
            if ($item->isDir()) {
                $sontDir = $dest . DS . $iterator->getSubPathName();
                if (!is_dir($sontDir)) {
                    mkdir($sontDir, 0755, true);
                }
            } else {
                copy($item, $dest . DS . $iterator->getSubPathName());
            }
        }
    }
}

if (!function_exists('mb_ucfirst')) {
    function mb_ucfirst($string)
    {
        return mb_strtoupper(mb_substr($string, 0, 1)) . mb_strtolower(mb_substr($string, 1));
    }
}

if (!function_exists('addtion')) {

    /**
     * 附加关联字段数据
     * @param array $items  数据列表
     * @param mixed $fields 渲染的来源字段
     * @return array
     */
    function addtion($items, $fields)
    {
        if (!$items || !$fields) {
            return $items;
        }
        $fieldsArr = [];
        if (!is_array($fields)) {
            $arr = explode(',', $fields);
            foreach ($arr as $k => $v) {
                $fieldsArr[$v] = ['field' => $v];
            }
        } else {
            foreach ($fields as $k => $v) {
                if (is_array($v)) {
                    $v['field'] = isset($v['field']) ? $v['field'] : $k;
                } else {
                    $v = ['field' => $v];
                }
                $fieldsArr[$v['field']] = $v;
            }
        }
        foreach ($fieldsArr as $k => &$v) {
            $v = is_array($v) ? $v : ['field' => $v];
            $v['display'] = isset($v['display']) ? $v['display'] : str_replace(['_ids', '_id'], ['_names', '_name'], $v['field']);
            $v['primary'] = isset($v['primary']) ? $v['primary'] : '';
            $v['column'] = isset($v['column']) ? $v['column'] : 'name';
            $v['model'] = isset($v['model']) ? $v['model'] : '';
            $v['table'] = isset($v['table']) ? $v['table'] : '';
            $v['name'] = isset($v['name']) ? $v['name'] : str_replace(['_ids', '_id'], '', $v['field']);
        }
        unset($v);
        $ids = [];
        $fields = array_keys($fieldsArr);
        foreach ($items as $k => $v) {
            foreach ($fields as $m => $n) {
                if (isset($v[$n])) {
                    $ids[$n] = array_merge(isset($ids[$n]) && is_array($ids[$n]) ? $ids[$n] : [], explode(',', $v[$n]));
                }
            }
        }
        $result = [];
        foreach ($fieldsArr as $k => $v) {
            if ($v['model']) {
                $model = new $v['model'];
            } else {
                $model = $v['name'] ? \think\Db::name($v['name']) : \think\Db::table($v['table']);
            }
            $primary = $v['primary'] ? $v['primary'] : $model->getPk();
            $result[$v['field']] = $model->where($primary, 'in', $ids[$v['field']])->column("{$primary},{$v['column']}");
        }

        foreach ($items as $k => &$v) {
            foreach ($fields as $m => $n) {
                if (isset($v[$n])) {
                    $curr = array_flip(explode(',', $v[$n]));

                    $v[$fieldsArr[$n]['display']] = implode(',', array_intersect_key($result[$n], $curr));
                }
            }
        }
        return $items;
    }
}

if (!function_exists('var_export_short')) {

    /**
     * 返回打印数组结构
     * @param string $var    数组
     * @param string $indent 缩进字符
     * @return string
     */
    function var_export_short($var, $indent = "")
    {
        switch (gettype($var)) {
            case "string":
                return '"' . addcslashes($var, "\\\$\"\r\n\t\v\f") . '"';
            case "array":
                $indexed = array_keys($var) === range(0, count($var) - 1);
                $r = [];
                foreach ($var as $key => $value) {
                    $r[] = "$indent    "
                        . ($indexed ? "" : var_export_short($key) . " => ")
                        . var_export_short($value, "$indent    ");
                }
                return "[\n" . implode(",\n", $r) . "\n" . $indent . "]";
            case "boolean":
                return $var ? "TRUE" : "FALSE";
            default:
                return var_export($var, true);
        }
    }
}

if (!function_exists('letter_avatar')) {
    /**
     * 首字母头像
     * @param $text
     * @return string
     */
    function letter_avatar($text)
    {
        $total = unpack('L', hash('adler32', $text, true))[1];
        $hue = $total % 360;
        list($r, $g, $b) = hsv2rgb($hue / 360, 0.3, 0.9);

        $bg = "rgb({$r},{$g},{$b})";
        $color = "#ffffff";
        $first = mb_strtoupper(mb_substr($text, 0, 1));
        $src = base64_encode('<svg xmlns="http://www.w3.org/2000/svg" version="1.1" height="100" width="100"><rect fill="' . $bg . '" x="0" y="0" width="100" height="100"></rect><text x="50" y="50" font-size="50" text-copy="fast" fill="' . $color . '" text-anchor="middle" text-rights="admin" alignment-baseline="central">' . $first . '</text></svg>');
        $value = 'data:image/svg+xml;base64,' . $src;
        return $value;
    }
}

if (!function_exists('hsv2rgb')) {
    function hsv2rgb($h, $s, $v)
    {
        $r = $g = $b = 0;

        $i = floor($h * 6);
        $f = $h * 6 - $i;
        $p = $v * (1 - $s);
        $q = $v * (1 - $f * $s);
        $t = $v * (1 - (1 - $f) * $s);

        switch ($i % 6) {
            case 0:
                $r = $v;
                $g = $t;
                $b = $p;
                break;
            case 1:
                $r = $q;
                $g = $v;
                $b = $p;
                break;
            case 2:
                $r = $p;
                $g = $v;
                $b = $t;
                break;
            case 3:
                $r = $p;
                $g = $q;
                $b = $v;
                break;
            case 4:
                $r = $t;
                $g = $p;
                $b = $v;
                break;
            case 5:
                $r = $v;
                $g = $p;
                $b = $q;
                break;
        }

        return [
            floor($r * 255),
            floor($g * 255),
            floor($b * 255)
        ];
    }
}


if (!function_exists('download')) {


    function download($url){

        ob_start();
        readfile($url);
        $img=ob_get_contents();
        ob_end_clean();
        $save_dir = "./uploads/".date("Ymd",time())."/";
        //创建保存目录
        if(!file_exists($save_dir)&&!mkdir($save_dir,0777,true)){
            return array('file_name'=>'','save_path'=>'','error'=>5);
        }

        $new_file = $save_dir .Random::alnum(32).".png";
        $fp2=@fopen($new_file,'a');

        fwrite($fp2,$img);
        fclose($fp2);

        return substr($new_file,1);
    }
}


if (!function_exists('contentImageUrl')) {

    //富文本中图片全路径
    function contentImageUrl($content) {

        $url = config('site.cdnurl');
        $new_content = richTextAbsoluteUrl($content, $url);




        /**
        $pregRule = "/<[img|IMG].*?src=[\'|\"](.*?(?:[\.jpg|\.jpeg|\.png|\.gif|\.bmp]))[\'|\"].*?[\/]?>/";
        $content = preg_replace($pregRule, '<img src="'.$url.'${1}" style="max-width:100%">', $content);
         **/
        return $new_content;
    }
}


if (!function_exists('richTextAbsoluteUrl')) {

    function richTextAbsoluteUrl($html_content, $host)
    {
        if (preg_match_all("/(<img[^>]+src=\"([^\"]+)\"[^>]*>)|(<a[^>]+href=\"([^\"]+)\"[^>]*>)|(<img[^>]+src='([^']+)'[^>]*>)|(<a[^>]+href='([^']+)'[^>]*>)/i", $html_content, $regs)) {
            foreach ($regs [0] as $num => $url) {
                $html_content = str_replace($url, lIIIIl($url, $host), $html_content);
            }
        }
        return $html_content;
    }
}


if (!function_exists('lIIIIl')) {

    function lIIIIl($l1, $l2)
    {
        if (preg_match("/(.*)(href|src)\=(.+?)( |\/\>|\>).*/i", $l1, $regs)) {
            $I2 = $regs [3];
        }
        if (strlen($I2) > 0) {
            $I1 = str_replace(chr(34), "", $I2);
            $I1 = str_replace(chr(39), "", $I1);
        } else {
            return $l1;
        }
        $url_parsed = parse_url($l2);
        $scheme = isset($url_parsed['scheme']) ? $url_parsed ["scheme"] : '';
        if ($scheme != "") {
            $scheme = $scheme . "://";
        }
        $host = isset($url_parsed ["host"]) ? $url_parsed['host'] : '';
        $l3 = $scheme . $host;
        if (strlen($l3) == 0) {
            return $l1;
        }
        $path = isset($url_parsed ["path"]) ? dirname($url_parsed ["path"]) : '' ;
        if(!empty($path)){
            if ($path [0] == "\\") {
                $path = "";
            }
        }
        $pos = strpos($I1, "#");
        if ($pos > 0)
            $I1 = substr($I1, 0, $pos);

        //判断类型
        if (preg_match("/^(http|https|ftp):(\/\/|\\\\)(([\w\/\\\+\-~`@:%])+\.)+([\w\/\\\.\=\?\+\-~`@\':!%#]|(&amp;)|&)+/i", $I1)) {
            return $l1;
        } //http开头的url类型要跳过
        elseif ($I1 [0] == "/") {
            $I1 = $l3 . $I1;
        } //绝对路径
        elseif (substr($I1, 0, 3) == "../") { //相对路径
            while (substr($I1, 0, 3) == "../") {
                $I1 = substr($I1, strlen($I1) - (strlen($I1) - 3), strlen($I1) - 3);
                if (strlen($path) > 0) {
                    $path = dirname($path);
                }
            }
            $I1 = $l3 . $path . "/" . $I1;
        } elseif (substr($I1, 0, 2) == "./") {
            $I1 = $l3 . $path . substr($I1, strlen($I1) - (strlen($I1) - 1), strlen($I1) - 1);
        } elseif (strtolower(substr($I1, 0, 7)) == "mailto:" || strtolower(substr($I1, 0, 11)) == "javascript:") {
            return $l1;
        } else {
            $I1 = $l3 . $path . "/" . $I1;
        }
        return str_replace($I2, "\"$I1\"", $l1);
    }
}







/**
 *  富文本绝对路径替换成相对路径
 * @param $html_content
 * @param $host
 * @return mixed
 */
function richTextRelativeUrl($html_content, $host)
{
    return str_replace($host, '', $html_content);
}


if (!function_exists('vipPrive')) {

    // vip折扣
    function vipPrive($price) {

        $level_discount = config('site.level_discount');
        $vip_price = $price ? number_format(($level_discount/100) * $price , '2') : $price;
        return $vip_price;
    }
}


if (!function_exists('ewArrSort')) {

    // vip折扣
    function ewArrSort($arr, $key, $sort = 'asc') {
        if (!$arr) {
            return $arr;
        }
        if (!$key) {
            return $arr;
        }

        $column = array_column($arr, $key);

        $sort = $sort == 'asc' ?  SORT_ASC : SORT_DESC;
        array_multisort($column, $sort, $arr);

        return $arr;
    }
}

if (!function_exists('goodsSeckillStatus')) {

    /**
     * 商品秒杀状态
     * @param $goods_id
     * @return int 0:未开始,1=秒杀中,2=已结束/普通商品
     */
    function goodsSeckillStatus($goods_id) {
        $res = [
            'seckill_status' => 2,
            'seckill_id' => 0,
        ];
        if (!$goods_id) {
            return $res;
        }
        //1.秒杀开关
        $seckill_switch = config('site.seckill_switch');
        if (!$seckill_switch) {
            return $res;
        }
        //2.判断当前商品是否是秒杀商品
        $model_seckill_goods = new SeckillGoods();
        $seckill_goods = $model_seckill_goods->where(['goods_id'=>$goods_id])->find();
        if (!$seckill_goods) {
            return $res;
        }
        //3.判断秒杀商品的秒杀场次是否是是今天,
        $seckill_id = $seckill_goods['seckill_id'];
        $model_seckill = new Seckill();
        $seckill = $model_seckill->where([
            'starttime' => ['<=',time()],
            'id' => $seckill_id,
        ])->find();
        if (!$seckill) {
            return $res;
        }
        $seckill_status = $seckill['status'];
        if ($seckill_status == 2) {
            return $res;
        } else {
            $res = [
                'seckill_status' => $seckill_status,
                'seckill_id' => $seckill['id'],
            ];
            return $res;
        }
    }
}

if (!function_exists('orderNum')) {
    function orderNum()
    {
        //生成24位唯一订单号码，格式：YYYY-MMDD-HHII-SS-NNNN,NNNN-CC，
        //其中：YYYY=年份，MM=月份，DD=日期，HH=24格式小时，II=分，SS=秒，NNNNNNNN=随机数，CC=检查码
        //飞鸟慕鱼博客
        @date_default_timezone_set("PRC");
        while(true) {
            //订购日期
            $order_date = date('Y-m-d');
            //订单号码主体（YYYYMMDDHHIISSNNNNNNNN）
            $order_id_main = date('YmdHis') . rand(10000000, 99999999);
            //订单号码主体长度
            $order_id_len = strlen($order_id_main);
            $order_id_sum = 0;
            for ($i = 0; $i < $order_id_len; $i++) {
                $order_id_sum += (int)(substr($order_id_main, $i, 1));
            }
            //唯一订单号码（YYYYMMDDHHIISSNNNNNNNNCC）
            $order_id = $order_id_main . str_pad((100 - $order_id_sum % 100) % 100, 2, '0', STR_PAD_LEFT);
            return $order_id;
        }
    }
}




if (!function_exists('selectSpecs')) {
    /**
     * 获取选中规格
     */
    function selectSpecs($specs_text,$specs)
    {
        $specs_select = [];
        foreach ($specs_text as $key => $value) {
            if (trim($value['specs']) == trim($specs)) {
                $specs_select = $value;
            }
        }
        return $specs_select;
    }
}




if (!function_exists('couponCanUse2')) {
    /**
     * 获取用户可用最高优惠券(结算用)
     */
    function couponCanUse2($user_id,$good_list)
    {
        $model_coupon = new \app\api\model\UserCoupon();
        $category_total = [];
        // var_dump($good_list);
        //获取商品列表中
        foreach($good_list as $key => $value) {
            if (!isset($category_total[$value['category_pid']])) {
                $category_total[$value['category_pid']] = 0;
            }
            $category_total[$value['category_pid']] += $value['real_price'] * $value['num'];
        }
        
        $coupon_ids = [];
        //用户全部优惠券
        $user_coupons = $model_coupon->with('couponCategory')
            ->where([
                'user_id' => $user_id,
                'status' => '1',
                'use_status' => 0,
            ])->select();
        //获取所有可用优惠券
        foreach($category_total as $key => $value) {
            foreach($user_coupons as $val) {
                if (in_array($key,explode(',',$val['category_ids'])) && $value >= $val['use_price']) {
                    $coupon_ids[] = $val['id'];
                }
            }
        }
        $coupon_ids = array_unique($coupon_ids);
        //获取优惠券信息
        $userCoupon = $model_coupon
            ->where(['id'=>['in',$coupon_ids]])
            ->order('use_price','desc')
            ->select();
        return $userCoupon;
    }
}




if (!function_exists('couponCanUse')) {
    /**
     * 获取用户可用最高优惠券
     */
    function couponCanUse($user_id, $total_price,$type,$ids)
    {
        $model_coupon = new \app\api\model\UserCoupon();
        if($type == 'goods'){
            //单件商品
            $categoryId = db('goods')->where(['id'=>$ids])->value('category_id');
            //获取顶级分类
            $cid = db('category')->where(['id'=>$categoryId])->value('pid');
            $coupon = $model_coupon
                ->alias('a')
                ->field('a.*')
                ->join('coupon b','a.coupon_id=b.id')
                ->where(['a.use_price'=>['<=',$total_price],'a.user_id'=>$user_id,'a.status'=>'1','a.use_status'=>'0'])
                ->whereLike('b.category_ids','%'.$cid.'%')
                ->order(['a.use_price'=>'desc'])
                ->select();
        }else{
            //购物车结算
            $userCoupon = $model_coupon
                ->where(['use_price'=>['<=',$total_price],'user_id'=>$user_id,'status'=>'1','use_status'=>0])
                ->order('use_price','desc')
                ->select();
            $coupon = [];
            foreach ($userCoupon as $row){
                if(checkCoupon($row['coupon_id'],$ids)){
                    $coupon[] = $row;
                }
            }
        }
        return $coupon;
    }
    
    //判断这个优惠券能不能用在这笔订单
    function checkCoupon($couponId,$ids){
        $cart = new \app\api\model\Cart();
        $couponInfo = db('coupon')->where(['id'=>$couponId])->find();
        //可用分类数组
        $cids = explode(',',$couponInfo['category_ids']);
        $carts = $cart->with('goods')->where(['id'=>['in',$ids]])->select();
        $totalPrice = 0;
        foreach ($carts as $value) {
            $specs = $value['specs']; //规格
            $num = $value['num']; //数量
            $goods = $value['goods'];
            $specs_text = $goods['specs_text'];
            $specs_select = selectSpecs($specs_text,$specs);
            if ($goods['seckill_status_text'] == 1) {
                $price = $specs_select['seckill_price'];
            } else {
                //1.3.2 判断当前商品是否处于会员状态
                if ($goods['vip_switch'] == 1) {
                    $price = $specs_select['vip_price'];
                } else {
                    $price = $specs_select['price'];
                }
            }
            $cid = db('category')->where(['id'=>$goods['category_id']])->value('pid');
            if(in_array($cid,$cids)){
                $totalPrice += $price*$num;
            }
        }
        if($totalPrice >= $couponInfo['use_price']){
            return true;
        }
        return false;
    }
}

if (!function_exists('fullCutConfig')) {
    /**
     * 满减配置
     */
    function fullCutConfig($goods_ids = [],$orderPrice)
    {
        
        //获取商品所在的满减
        $fullcut_ids = db('fullcut_goods')->where(['goods_id'=>['in',$goods_ids]])->column('fullcut_id');

//        $fullcut = [
//            'fullcut_switch' => config('site.fullcut_switch'),
//            'full_price' => config('site.full_price'),
//            'cut_price' => config('site.cut_price'),
//            'starttime' => strtotime(config('site.starttime')),
//            'endtime' => strtotime(config('site.endtime')),
//        ];
        $model_fullcut = new Fullcut();
        $fullcut = $model_fullcut
            ->where('full_price <= '.$orderPrice)
            ->where(['status'=>'normal'])
            ->where(['id'=>['in',$fullcut_ids]])
            ->where('starttime <' .time())
            ->where('endtime >' .time())
            ->order('cut_price','desc')
            ->find();
        return $fullcut;
    }
}

if (!function_exists('setPayLog')) {
    /**
     * 写入支付记录
     */
    function setPayLog($type = 'goods',$msg='success', $time , $content)
    {
        $date = date('Y-m-d H:i:s', $time);
        //
        $content .= "订单类型: $type \n";
        $content .= "支付状态: $msg \n";
        $content .= "时间: ". $date ." \n";
        $content .= "内容: $content \n\n\n";
        $file_name = date('Ymd');
        $path = ROOT_PATH . "/runtime/pay/";
        file_put_contents($path.$file_name.'txt', $content, FILE_APPEND);

        return;
    }
}








