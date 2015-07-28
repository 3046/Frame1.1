<?php
/**
 * 框架基础文件,本文件定义
 * 1. autoload按需加载方法
 * 2. load_config按需加载配置
 * 3. 错误信息收集
 * 
 * 全局变量
 * VIEW
 * $GLOBALS['sys']['outType'] :json/smarty/ajax
 * $GLOBALS['sys']['tpl'] index.tpl smarty模板
 * app需要自定目录
 *
 *
 * @author Lijinming <lijinming@4399.net>
 * @version $Id $
 * @since 0.1
 */
header("Content-type: text/html; charset=utf-8"); 
// 定义关键常量
if(!defined('APP')){
    // 取/xtest/index.php xtest部分
    define('APP',  substr($_SERVER['PHP_SELF'],1,strlen($_SERVER['PHP_SELF'])-11)); 
    // fix for local
    $strArr = explode("/",APP);
    $strSize = count($strArr);
    define('APP1', $strArr[$strSize - 1]);
    // fix by aleck 2015.7.28
}

// APP需要Include之前定义,DEFINE,单个单词不超过5个字母
define('PATH_ROOT', dirname(dirname(__FILE__))); 
define('PATH_APP',PATH_ROOT.'/app');        // /root/app 

define('PATH_CUR_APP',PATH_APP.'/'.APP1);        // -> /root/app/appA
define('PATH_TEMP',PATH_CUR_APP.'/template');   // -> /root/app/appA/template
define('PATH_MOD',PATH_CUR_APP.'/model');      // -> /root/app/appA/model
define('PATH_CTL',PATH_CUR_APP.'/controller');     // -> /root/app/appA/controller
define('PATH_API',PATH_CUR_APP.'/api');      // -> /root/app/appA/model
define('PATH_SER',PATH_CUR_APP.'/service');     // -> /root/app/appA/controller
define('PATH_ENT',PATH_CUR_APP.'/entity');     // -> /root/app/appA/controller
define('PATH_CONFIG', PATH_CUR_APP . '/config'); // ->/root/app/appA/config

define('PATH_PUB',PATH_ROOT.'/public'); 
define('PATH_CACHE',PATH_ROOT.'/cache');
define('PATH_CORE', PATH_ROOT . '/core');
define('PATH_INC', PATH_ROOT . '/config');  
define('PATH_LIB', PATH_CORE . '/library');
define('PATH_ORG', PATH_CORE . '/library/org');

// 系统级异常.
define('EXCEPT_CORE', -10000);   //  如自动加载不存的文件异常
define('EXCEPT_CONFIG', -10001); // 加载配置异常
define('EXCEPT_DB', -10002);      // 数据库语句异常
define('EXCEPT_CACHE', -10003);  // 连接cache异常
define('EXCEPT_API', -10004);      // 以php连接其它API接口出现的异常

define('EXCEPT_REQ', -20000);      // 参数出现的错误

// 自加载
spl_autoload_register('autoload');  // aotuload注册

// 时区还是设置一下 有时候date会警告
date_default_timezone_set("PRC");

// 调试状态
switch (DEMO) {
    case 1:
        define('DEBUG', true);
        break;
    case 2:
        if (defined('SAFE_IP')  && LibUtil::getIp() == SAFE_IP) {
            error_reporting(E_ALL);
            define('DEBUG', true);
        } else {
            define('DEBUG', false);
        }
        break;
    default:
        error_reporting(0);
        define('DEBUG', false);
        break;
}

// 在框架项目之下
if (DEBUG) {
    set_error_handler("error_handler");
}

// 处理异常
set_exception_handler('exception_handler'); 



/**
 * 默认处理exception的方法
 * @param Exception $e 
 */
function exception_handler(Exception $e){
    $exceptionHandler = new ExceptionHandler;
    $exceptionHandler->handler($e);
}

/**
 * 路由实现,及默认模式
 * @param string $controller
 * @param string $action 
 * @return boolean true/false
 */
function route($controller,$action,$defaultOut='smarty',$ctl='Ctl'){
    $ctlClass= $ctl.ucfirst($controller);
    $_conObj = new $ctlClass;
    // 指定默认的输出格式
    $_conObj->setOutType($defaultOut);
    if($defaultOut == 'smarty'){
        if($action == 'index'){
            $_conObj->tpl = "{$controller}.tpl";
        }
        else{
            $_conObj->tpl = "{$controller}_{$action}.tpl";
        }
    }
    // 禁止访问Controller下的方法
    $reflection = new ReflectionClass('Controller');
    if($reflection->hasMethod($action)){
        throw new RuntimeException('Forbidden',403);
    }
    
    // 方法不存在
    if (!method_exists($_conObj, $action)) {
        throw new RuntimeException('Not Found',404);
    }
    
    $_conObj->$action();
    $_conObj->display();
    return true;
}


/**
 * 自动加载魔术方法
 * 支持core系统下的Mod,Lib,Pub,Ctl及三种基础类的自动加载
 * @param 类名 $className
 * @return null
 */
function autoload($className) {
    $_file = '';
    // controller model
    if ($className == 'Controller' || $className == 'Model' || $className == 'ExceptionHandler') {
        $_file = PATH_CORE . '/' .$className . '.php';
    }
    else{ 
        // Mod,Lib,Pub,Ctl类的自动调用
        $clsPath = array('Mod' => PATH_MOD, 'Lib' => PATH_LIB,'Pub'=>PATH_PUB,'Ctl'=>PATH_CTL,'Ent'=>PATH_ENT,'Api'=>PATH_API,'Ser'=>PATH_SER);
        $_classType = substr($className, 0, 3);
        if (isset($clsPath[$_classType])) {
            $_file = "{$clsPath[$_classType]}/" .basename($className) . '.php';
        }
    }
    if($_file){
       // auto中不能抛出错误
       if(!is_file($_file)){
           exception_handler(new RuntimeException("Not Found",404));
           exit;
       }
       require $_file;
       return true;
    }

    return false;
}

/**
 * 根据不同的DEBUG来显示BUG显示
 */
function error_handler($code, $msg, $file, $line) {
    // 错误信息
    $errorArray = compact('code', 'msg', 'file', 'line');
    
    // 调试错误表
    $errCodes  = array(
        E_ERROR => 'ERROR',
        E_WARNING => 'WARNING',
        E_PARSE => 'PARSE',
        E_NOTICE => 'NOTICE',
    );
    
    $errorArray['type'] = isset($errCodes[$code]) ? $errCodes[$code] : "OTHER";

    // 显示跟踪信息
    $traces = debug_backtrace();
    array_shift($traces);
    foreach ($traces as $v) {
        // 有可能
        if (!isset($v['file'])) {
            $track_info = 'error not in file,may be in memory';
        } else {
            $v['file'] = str_replace(PATH_ROOT, '', $v['file']);
            if (isset($v['class'])) {
                $track_info = "{$v['file']}, {$v['line']}, {$v['class']}{$v['type']}{$v['function']}";
            } elseif ($v['function']) {
                $track_info = "{$v['file']}, {$v['line']}, {$v['function']}";
            } else {
                $track_info = "{$v['file']}, {$v['line']}, ";
            }
            if (isset($v['args']) && is_array($v['args'])) {
                // 对参数进行整理
                foreach ($v['args'] as $k2 => $v2) {
                    if (is_object($v2)) {
                        unset($v['args'][$k2]);
                        $v['args'][$k2] = gettype($v2) . " obj";
                    } elseif (is_array($v2)) {
                        unset($v['args'][$k2]);
                        $v['args'][$k2] = var_export($v2, true);
                    }
                }
                $track_info .= '(' . implode(',', $v['args']) . ')';
            } elseif (isset($v['args'])) {
                $track_info .= '(' . $v['args'] . ')';
            } elseif ($v['function']) {
                $track_info .= "()";
            }
            $errorArray['track'] [] = $track_info;
        }
    }
    
    $GLOBALS['sys']['errorInfo'][] = $errorArray;
}

/**
 * 加载config文件
 * @param string $config 配置名称,加载的$GLOBALS['CONFIG]下标
 * @param bool $app 是否加载当前app的私有config
 * @return boolean true of false 
 * @throws Exception message = config is empty,code=EXCEPT_CONFIG
 */
function load_config($config) {
    if (!$config) {
       throw new LogicException('$config不能为空',EXCEPT_CONFIG); 
    }
	$config = basename($config);
    // 判断 globals里是否已经有
    if (isset($GLOBALS['config'][$config])) {
        return $GLOBALS['config'][$config];
    }
    // 转为首字母大写
    $configPath = ucfirst($config);
    if(substr($configPath,0,4)=='Conf'){
        $_file = PATH_CONFIG . '/'.$configPath.'.php';
    }else{
        $_file = PATH_INC. '/Inc'.$configPath.'.php';
    }
    // 如果没有直接requrie文件,config/confBd.php, 驼峰文件名
    if (!is_file($_file)) {
       throw new LogicException("config文件 {$config} 不存在",EXCEPT_CONFIG);
    }
    require $_file;
    return isset($GLOBALS['config'][$config])?$GLOBALS['config'][$config]:true;
}
