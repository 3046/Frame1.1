<?php

/**
 * 对exceptionHandler进行扩展
 *
 * @author dvinci
 */
class PubExceptionHandler {
    
    
    /**
     * 简单的自定义处理handler的方法handlerIndex
     * 可以不用exceptionHandler类下处理,但前提你你知道自己在做什么
     * @param type $e  
     */
    static function handlerIndex($e){
        $excepHandler = new ExceptionHandler();
        $excepHandler->out['_url'][] = array('url'=>'/','text'=>'返回首页');
        $excepHandler->out['_url'][] = array('url'=>'/','text'=>'返回上页');

        $excepHandler->tpl = PATH_APP.'/template/index_msg.tpl';

        $excepHandler->handler($e);
    }
}

