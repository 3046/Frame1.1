<?php
load_config('redis');
/**
 *
 * User: XKJ
 * Date: 15-5-6
 * Time: 下午3:28
 */
final class LibRedis {
    private $conn;       // Redis连接
    private $config;     // Redis配置
    private $configName; // configName
    private static $connections;

    /**
     * 初始化Redis链接
     * @param string $memConfig
     */
    function __construct($memConfig) {
        $this->config($memConfig);
    }

    /**
     * 配置Redis,model切换config用
     * @param type $memConfig
     */
    function config($memConfig){

        if($memConfig == ''){
            $memConfig = 'default';
        }
        $this->config = $GLOBALS['config']['redis'][$memConfig];
        $this->configName  = $memConfig;
    }

    /**
     * 连接数据库,得到memcache对象
     * @return boolean
     */
    function connect(){
        // 避免重复初始化变量
        if(empty(self::$connections[$this->configName])){
            self::$connections[$this->configName] = new Redis();
            self::$connections[$this->configName]->connect($this->config[0], $this->config[1], $this->config[2]);
        }
        return self::$connections[$this->configName];
    }
}