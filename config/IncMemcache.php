<?php
/**
 * memcache配置
 * @todo: 该配置不得直接上传外网
 * ModXxx类中用进行调用相应的配置
 * $this->cacheActive -> Active配置
 * $this->cache ->defaut配置
 */

$GLOBALS['config']['memcache'] = array(
    'default' => array(
        // 三个分别是ip,端口,权重,
        // 权重以1-10的数做为参照,平均都写1,如果有不相等的话,务必总数等于10
        array('127.0.0.1','11211',1), 
        array('127.0.0.1','11211',1),
        array('127.0.0.1','11211',1),
    ),
    'user' => array(
        array('127.0.0.1','11211',1), 
        array('127.0.0.1','11211',1),
        array('127.0.0.1','11211',1),
    ),
);
