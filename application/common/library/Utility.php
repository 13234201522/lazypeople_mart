<?php


namespace app\common\library;


class Utility
{
    public static function request($url, $params)
    {
        $ch = curl_init();
        $this_header = array("content-type: application/x-www-form-urlencoded;charset=UTF-8");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this_header);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    public static function real_ip()
    {
        static $realip = NULL;
        if ($realip !== NULL) {
            return $realip;
        }
        if (isset($_SERVER)) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                /* Take the first non-unknown valid IP string in X-Forwarded-For */
                foreach ($arr AS $ip) {
                    $ip = trim($ip);
                    if ($ip != 'unknown') {
                        $realip = $ip;
                        break;
                    }
                }
            } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $realip = $_SERVER['HTTP_CLIENT_IP'];
            } else {
                if (isset($_SERVER['REMOTE_ADDR'])) {
                    $realip = $_SERVER['REMOTE_ADDR'];
                } else {
                    $realip = '0.0.0.0';
                }
            }
        } else {
            if (getenv('HTTP_X_FORWARDED_FOR')) {
                $realip = getenv('HTTP_X_FORWARDED_FOR');
            } elseif (getenv('HTTP_CLIENT_IP')) {
                $realip = getenv('HTTP_CLIENT_IP');
            } else {
                $realip = getenv('REMOTE_ADDR');
            }
        }
        preg_match("/[\d\.]{7,15}/", $realip, $onlineip);
        $realip = !empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';
        return $realip;
    }

    /** Signature string
     *$prestr: Strings to be signed
     *return: Signature result
     */
    public static function md5sign($prestr, $sign_type)
    {
        $sign = '';
        if ($sign_type == 'MD5') {
            $sign = strtoupper(md5($prestr));//All uppercase letters
        } else {
            die("The signature method " . $sign_type . " is not supported at this time");
        }
        return $sign;
    }

    /**
     *Put all the elements of the array into a string with the "&" character according to the pattern of "parameter = parameter value"
     *$array: array to be stitched
     *return: string after stitching completed
     */
    public static function create_linkstring($array)
    {
        $arg = "";
        foreach ($array as $k => $v) {
            if ($v !== '') {
                $arg .= $k . '=' . $v . '&';
            }
        }
        $arg = substr($arg, 0, strlen($arg) - 1);             //Remove the last & character
        return $arg;
    }

    public static function build_mysign($sort_array, $key, $sign_type = "MD5")
    {
        $prestr = self::create_linkstring($sort_array);
        $prestr = $prestr . "&key=" . $key;
        //Connect the stitched string directly to the security check code
        $mysgin = self::md5sign($prestr, $sign_type);
        //Sign the final string to get the signature result
        return $mysgin;
    }

    /**Sort array
     *$array: array before sorting
     *return: sorted array
     */
    public static function arg_sort($array)
    {
        ksort($array, SORT_NATURAL | SORT_FLAG_CASE);
        reset($array);
        return $array;
    }
}
