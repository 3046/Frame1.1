<?php
/**
 * Model基础类
 * 
 * Model实现两种类型的功能
 * 1. 与Database,Cache的CURD,如 ModUser::getOne()之类的,
 *     建议这一类只返回true/false/array
 * 
 * 2. 与实际业务有关,如ModUser::login();建议这一类,
 *    除返回true/false/array以外
 *    用throw new RuntimeException来表示各种逻辑错误
 * 
 * @author dvinci
 */
class Model{
    function __get($name) {
        // 自动加载db,单例 $this->dbUser->getOne($sql);
        if(substr($name, 0,2) =='db'){
            $config = strtolower(substr($name, 2));
            // 只初始化一个LibDatabase实例
            if(empty($this->objDb)){
                $this->objDb = new LibDatabase($config);
            }else{
                $this->objDb->config($config);
            }
            return $this->objDb;  
        }
        
        // 自动加载memcache,单例
        if(substr($name, 0,5) =='cache'){
             $config = strtolower(substr($name, 5));
            // 只初始化一个LibDatabase实例
            if(empty($this->objCache)){
                $this->objCache = new LibMemcache($config);
            }else{
                $this->objCache->config($config);
            }
            return $this->objCache;  
        }
        
        throw new LogicException("变量{$name}不被支持,请预先在Model中定义",EXCEPT_CORE);
    }
}
?>
