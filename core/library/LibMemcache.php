<?php
load_config('memcache');
/**
 *  memcache缓存类
 *  已经兼容以前的写法fetch store delete
 *  新加有前缀的get set remove
 * @todo 建议换成memcached客户端
 * @todo 需要完善cls_redis
 * @author dvinci
 */
final class LibMemcache {
    private $conn;       // memcache连接
    private $config;     // memcache配置
    private $configName; // configName
    private static $connections;

    /**
     * 初始化memcache链接
     * @param string $memConfig 
     */
    function __construct($memConfig) {
        $this->config($memConfig);
    }
    
    /**
     * 配置memcache,model切换config用
     * @param type $memConfig 
     */
    function config($memConfig){
        
        if($memConfig == ''){
            $memConfig = 'default';
        }
         $this->config = $GLOBALS['config']['memcache'][$memConfig];
         $this->configName  = $memConfig;
    }
    
   /**
     * 连接数据库,得到memcache对象
     * @return boolean 
     */
    function connect(){
        // 避免重复初始化变量
        if(empty(self::$connections[$this->configName])){
            self::$connections[$this->configName] = new Memcache;
            foreach ($this->config as $config) {
                self::$connections[$this->configName]->addServer($config[0], $config[1], true, $config[2]);
            }
        }
        return self::$connections[$this->configName];
    }
    


    /**
     * 带前缀的取缓存
     * @param string $prefix
     * @param string $key
     * @return mix 
     */
    public function get($prefix, $key) {
        if ($key == '') {
            return null;
        }
        if ($prefix == '') {
            $_key = $key;
        } else {
            $_key = "{$prefix}_{$key}";
        }
        return $this->connect()->get($_key);
    }

    /**
     * 不带前缀的取缓存 
     * @param type $key
     * @return null 
     */
    public function fetch($key) {
        return $this->get('', $key);
    }

    /**
     *  不带前缀的存储
     * @param string $key key
     * @param mix $value 值
     * @param int  $ttl 缓存时间
     * @return boolean  true/false
     */
    public function store($key, $value, $ttl = 3600) {
        return $this->set('', $key, $value, $ttl);
    }

    /**
     * 带前缀的存储
     * @param string $prefix 前缀
     * @param string $key key
     * @param mix $value 值
     * @param int  $ttl 缓存时间
     * @return boolean  true/false
     */
    public function set($prefix, $key, $value, $ttl = 3600) {
        if ($key == '') {
            return false;
        }
        if ($prefix == '') {
            $_key = $key;
        } else {
            $_key = "{$prefix}_{$key}";
        }
        $ttl = intval($ttl);
        if ($ttl > 2592000) {
            $ttl = 2592000;
        } else if ($ttl <= 0) {
            $ttl = 3600;
        }
        return $this->connect()->set($_key, $value, false, $ttl);
    }

    /**
     * 不带前缀的删除
     * @param string $key
     * @return return boolean true/false 
     */
    public function delete($key) {
        return $this->remove('', $key);
    }

    /**
     * 带前缀的删除
     * @param string $prefix 前缀
     * @param string $key key 
     * @return return boolean true/false 
     */
    public function remove($prefix, $key) {
        if ($key == '') {
            return false;
        }
        if ($prefix == '') {
            $_key = $key;
        } else {
            $_key = "{$prefix}_{$key}";
        }
        return $this->connect()->delete($_key);
    }

}