<?php

/**
 * ModUser
 * 用户登录,注销,取得某
 *
 * @author dvinci
 */
class PubAuth{

    private static $cookieName = 'frameCookie';
    
    /**
     * 写入当前用户的权限 
     */
    static function setAuth($userInfo){
        return LibUtil::cookie(self::$cookieName, json_encode($userInfo));
    }
    
    


    /**
     * 根据cookie中的值,来判断当前用户的权限
     * @return array
     */
    static function getAuth(){
        return json_decode(LibUtil::cookie(self::$cookieName),true);
    }
    
    
    /**
     * 判断当前用户访问该resource的权限
     * @param mix $resource
     * @return boolean true/false 
     */
   static  function checkAuth($resource){
        return false;
    }
    
    
    static function isLogin(){
        echo $a;
        if(self::getAuth()){
            return true;
        }
        return false;
    }
   
    
    static function logout(){
        if(!LibUtil::cookie(self::$cookieName)){
            throw new RuntimeException('您还未登录');
        }
        return LibUtil::cookie(self::$cookieName,'');
    }

    public static function apiAuth($app){
        load_config('apikey');
        if(empty($GLOBALS['config']['apikey'][$app])) exit('key config error');
        if(empty($_REQUEST['time']) || ($_REQUEST['time']+120)<=time() || $_REQUEST['sign']!=md5($_REQUEST['_c'].$GLOBALS['config']['apikey'][$app].$_REQUEST['time'])){
            echo "0";
            exit;
        }
    }
}

