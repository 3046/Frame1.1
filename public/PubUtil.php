<?php

/**
 * 工具类
 *
 * @author dvinci
 */
class PubUtil {
    /**
     * 由email取得邮件服务商的信息
     * @param type $email 邮箱名
     * @return array ['name'] && ['url']
     */
    static function emailServer($email) {
        $emailServer = array("name" => "", "url" => "");
        load_config('emailServer');
        $emailDomain = substr($email, stripos($email, '@') + 1);
        if (isset($GLOBALS['config']['emailServer'][$emailDomain])) {
            $emailServer = $GLOBALS['config']['emailServer'][$emailDomain];
        } else {
            $emailServer['name'] = "邮箱";
            $emailServer['url'] = "http://www." . $email_domain;
        }

        return $emailServer;
    }

}

