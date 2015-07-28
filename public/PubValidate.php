<?php
/**
 * 验证类,全静态,用于controller里的参数验证
 * 
 * @todo 需完善
 * @author dvinci
 */
class PubValidate {

    /**
     * 手机号验证
     * @param string $str
     * @return bool
     */
    static function mobile($str){
        return preg_match("/^(13|15|18|14)\d{9}$/", $str);
    }

    /**
     * 下行手机验证码 6位数字
     * @param string $str
     * @return bool
     */
    static function smsCode($str){
        return preg_match("/^\d{6}$/",$str);
    }

    
    /**
     * EMAIL验证 
     */
    static function email($str){
        return preg_match("/^([a-zA-Z0-9]+[_|\-|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\-|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/",$str);
    }
   
    
    /**
     * QQ验证
     */
    static function qq($str){
        return preg_match("/^[1-9]\d{4,11}$/", $str);
    }

    /**
     * 用户密码
     * @param string $str
     * @return boolean 
     */
    static function passwd($str){
        if(strlen($str) <6 || strlen($str) > 20){
            return false;
        }
        return true;
    }

}
?>
