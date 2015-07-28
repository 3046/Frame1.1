<?php
/**
 * IncCommon加载一些全局必须要用到的配置
 *  
 */
// 域名配置
if(DEMO){
    define('DOMAIN','http://frame.demo.qq163.com');
}
else{
    define('DOMAIN','http://frame.qq163.com');
}
define('URL_CMS', DOMAIN.'/cms');
define('URL_USER', DOMAIN.'/user');




