<?php

/**
 * 公共Wiget
 * 一般用于模板文件的显示
 * 也可以在其它地方如controller中调用 
 * @author dvinci
 */
class PubWiget {
    /**
     * 一个加星号的widget,会把dvinci@gmail.com -> d***ci@gmail.com
     * 
     * @param type $string 
     */
    function asterisk($string){
         $_p = strpos($string, '@');
        $emailLeft = '';
        if ($_p) {
            $emailLeft = substr($string, $_p);
            $string = substr($string, 0, $_p);
        }
        $length = strlen($string);
        if ($length == 0) {
            return '';
        }
        $start = ceil(0.2 * $length);
        $repeatTime = ceil(0.5 * $length);
        $return = substr($string, 0, $start);
        $return.=str_repeat("*", $repeatTime);
        $left = $length - strlen($return);
        if ($left) {
            $return.=substr($string, -1 * $left);
        }
        $return .=$emailLeft;

        return $return;
    }
}

