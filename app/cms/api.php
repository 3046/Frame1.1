<?php
/**
 *
 * User: XKJ
 * Date: 15-5-6
 * Time: 下午4:22
 */
define('DEMO',0); // 1:测试环境, 2:加载正式环境配置 0:正式运行
define('SAFE_IP','211.156.184.10');   // 公司IP,调试用的
require dirname(dirname(dirname(__FILE__))) . '/core/init.php';
load_config('common');

session_start();
PubAuth::apiAuth(APP);

// 自定义路由
$ct = isset($_REQUEST['_c'])?trim($_REQUEST['_c']):'index';
$ac = isset($_REQUEST['_a'])?trim($_REQUEST['_a']):'index';
route($ct, $ac, 'smarty','Api');