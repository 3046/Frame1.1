<?php
/**
 * 脚本统一入口文件.
 * User: xkj
 * Date: 15-5-12
 * Time: 下午12:19
 * To change this template use File | Settings | File Templates.
 */
if(empty($_GET)){
    parse_str($argv[1],$_GET);
}

define('DEMO', 1);

define('SYS_TIME',time());

error_reporting(E_ALL);
require  dirname(dirname(dirname(__FILE__))).'/core/init.php';
if(PHP_SAPI!='cli'){ //内部脚本使用
    echo 'error';
}