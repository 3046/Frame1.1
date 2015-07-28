<?php

/**
 * 处理exception的类,具有Controller的特性
 * 如果需要自己定义处理类,请extends该类,重写handle方法
 * 
 * 
 * @author dvinci
 */
class ExceptionHandler Extends Controller{
    
    /**
     * 记录日志
     * @return boolean true/false
     */
    private static function log($out){
        $body = "[ TIME ] : ".date('H:i:s')."\n";
        $body .= "[ REQ ]  : ".$_SERVER['REQUEST_URI']."\n";
        $body .= "[ MSG ]  : ".$out['_msg']."\n";
        $body .= "[ CODE ] : ".$out['_code']."\n";
        $body .= "[DETAIL] : Exception  in " .$out['_exception_detail']['file'] ." on  ". $out['_exception_detail']['line']."\n";
        foreach($out['_exception_detail']['track'] as $k=> $v){
            $body .= "[TRACK{$k}] : {$v}\n";
        }
        $handle = fopen(PATH_CACHE.'/exception_log/'.date('Y-m-d').'.log', 'a');
        fwrite($handle, $body."\n");
        fclose($handle);
        return true;
    }
    

    /**
     * 取得该次错误的回溯信息
     * @param type $traces
     * @return string 
     */
    private static function getTraceDesc($traces){
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
                    $result[] = $track_info;
                }
            }
        
        return $result;
    }
    
    
    /**
     * 显示exception错误信息
     * @param LogicException $e 
     */
    function handler($e){
        if($e->getCode() == 404 && !DEBUG){
           header("HTTP/1.0 404 Not Found");
           exit;
        }
        
        // 正式情况下
        $this->out['_msg'] = $e->getMessage();
        $this->out['_code'] = $e->getCode();

        // 只有LogicException才需要显示详细,记录日志
        if ($e instanceof LogicException ) {
            // DEBUG时显示调试详细信息
            if(DEBUG){
                $this->out['_exception_detail']['trace'] = $this->getTraceDesc($e->getTrace());
                $this->out['_exception_detail']['file'] = $e->getFile();
                $this->out['_exception_detail']['line'] = $e->getLine();
            }
            // 逻辑错误记录日志,在正式环境中显示"系统错误"
            else {
                $this->out['_msg'] = '系统错误';
            }
        }
        if (!$this->tpl) {
            $this->tpl = PATH_APP . '/template/msg.tpl';
        }
        $this->display();
    }
}