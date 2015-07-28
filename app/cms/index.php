<?php
/**
 * APP首页
 * 1. 路由
 * 2. 默认exception处理方法
 * 3. 其它全局业务代码
 * 
 * @author Lijinming <lijinming@4399.net>
 * @version $Id $
 * @since 0.1
 */
define('DEMO',1); // 1:测试环境, 2:加载正式环境配置 0:正式运行
define('SAFE_IP','211.156.184.10');   // 公司IP,调试用的
require  dirname(dirname(dirname(__FILE__))).'/core/init.php';
load_config('common');
// 自定义路由
$ct = isset($_GET['_c'])?trim($_GET['_c']):'index';
$ac = isset($_GET['_a'])?trim($_GET['_a']):'index';
route($ct, $ac);