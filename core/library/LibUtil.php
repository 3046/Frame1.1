<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LibUtil
 *
 * @author dryoung
 */
class LibUtil {

    /**
     * PHP请求服务端
     * @param string $url
     * @param type $argument
     * @param type $ttl
     * @param type $method
     * @return type
     */
    static function makeRequest($url, $argument = array(), $ttl = 5, $method = "GET") {

        if (!$url) {
            throw new LogicException('$url不能为空');
        }

        if (substr($url, 0, 7) != 'http://' && substr($url, 0, 8) != 'https://') {
            return array('result' => NULL, 'code' => '400');
        }
        if ($method == 'GET' && count($argument) > 0) {
            $url .= "?" . (http_build_query($argument));
        }
        $header = array(
            'Accept-Language: zh-cn',
            'Connection: Keep-Alive',
            'Cache-Control: no-cache'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        if ($method == 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $argument);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $ttl);
        curl_setopt($ch, CURLOPT_USERAGENT, 'QQ163.COM API REQUEST(CURL)');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $return = array();
        $return['result'] = curl_exec($ch);
        $return['code'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        unset($ch);

        return $return;
    }

    /**
     * 取得真实IP
     * @staticvar string $realIp
     * @return string $Ip 
     */
    public static function getIp() {
        static $realIp = NULL;
        if ($realIp !== NULL) {
            return $realIp;
        }
        if (isset($_SERVER)) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR2'])) {
                $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR2']);
                /* 取X-Forwarded-For2中第?个非unknown的有效IP字符? */
                foreach ($arr as $ip) {
                    $ip = trim($ip);
                    if ($ip != 'unknown') {
                        $realIp = $ip;
                        break;
                    }
                }
            } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                /* 取X-Forwarded-For中第?个非unknown的有效IP字符? */
                foreach ($arr as $ip) {
                    $ip = trim($ip);
                    if ($ip != 'unknown') {
                        $realIp = $ip;
                        break;
                    }
                }
            } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $realIp = $_SERVER['HTTP_CLIENT_IP'];
            } else {
                if (isset($_SERVER['REMOTE_ADDR'])) {
                    $realIp = $_SERVER['REMOTE_ADDR'];
                } else {
                    $realIp = '0.0.0.0';
                }
            }
        } else {
            if (getenv('HTTP_X_FORWARDED_FOR2')) {
                $realIp = getenv('HTTP_X_FORWARDED_FOR2');
            } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
                $realIp = getenv('HTTP_X_FORWARDED_FOR');
            } elseif (getenv('HTTP_CLIENT_IP')) {
                $realIp = getenv('HTTP_CLIENT_IP');
            } else {
                $realIp = getenv('REMOTE_ADDR');
            }
        }
        $onlineip = array();
        preg_match("/[\d\.]{7,15}/", $realIp, $onlineip);
        $realIp = !empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';
        return $realIp;
    }

    /**
     * cookie操作方法
     * 操作.xx.com下的根目录cookie的方法
     * @param string $name cookie名称
     * @param type $value 留空为取cookie,''为deletecookie
     * @param type $expire 留空则set一个session时效的cookie
     * @return mix $cookieValue 
     */
    public static function cookie($name, $value = null, $expire = null) {
        if (!$name) {
            throw new LogicException('cookie调用参数错误');
        }
        // 留空为取cookie
        if ($value === null) {
            return isset($_COOKIE[$name]) ? $_COOKIE[$name] : false;
        }
        // ''为清除cookie
        if ($value === '') {
            return setcookie($name, $value, time() - 1, '/', strstr($_SERVER['SERVER_NAME'], '.'));
        }
        // 写cookie
        else {
            if ($expire) {
                $expire +=time();
            }
            return setcookie($name, $value, $expire, '/', strstr($_SERVER['SERVER_NAME'], '.'));
        }
    }

    /**
     * 取得次日0点的时间
     * @param type $time
     * @return type 
     */
    static function getNextdayTime($time = '') {
        $time = (int) $time;
        if (!$time) {
            $time = time();
        }
        return strtotime(date("Y-m-d", strtotime('+1 day', $time)));
    }

}
