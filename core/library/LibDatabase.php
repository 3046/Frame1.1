<?php

// 先行加载['config']['DB']
load_config('Db');

/**
 * 数据库类
 * 注意,与原来的cls_db完全不同,query只会返回当前的resouce_id,不会返回数据
 * 如需要取某一条数据,请使用getRow($sql),返回一维数组
 * 如需要取多条数据,请使用getAll($sql),返回二维数组
 * 注意mysql insert,update对所有数据库值都加addslave处理,
 * 但不会处理html,在control中 htmlspecialchars
 * 封装 update,insert,getStructure
 * 
 * @author dvinci
 */
final class LibDatabase {
    
    
    public $configName = null;       // 当前数据库配置
    public $masterForce = false;   // 是否强制连接主库

    protected $queryCount = 0;     // 数据库查询次数
    protected $query = null;       // 当前resource_id
    protected $masterIgnore = null;  // 是否无主从
    
    private static $connections = array(); // 用静态变量来存储多个连接句柄
    

    /**
     * 只初始化config
     * @param type $dbConfig 
     */
    function __construct($dbConfig) {
        $this->config($dbConfig);
    }
    
   /**
     *  引入DB
     * configType
     * 现qq163 支持 user, card, gameData对应三个库qq163_user,qq163_card,qq163_game_db
     * @param string $dbConfig  配置名称,对应confDB.php里的配置
     */
    function config($dbConfig){
        
        // 默认用default
        if($dbConfig == ''){
            $dbConfig = 'default';
        }
        
        if (is_array($GLOBALS['config']['db'][$dbConfig])) {
            $this->config = $GLOBALS['config']['db'][$dbConfig];
            $this->configName = $dbConfig;
            
            // 有主从
            if( isset($this->config['master'])){
                $this->masterIgnore = false;
            }
            else{
                $this->masterIgnore = true;
            }
            
        } else {
            throw new LogicException("{$dbConfig}数据库配置不存在",EXCEPT_CONFIG);
        }
    }

    /**
     * 根据主从连接数据库
     * @param bool $isMaster  是否连主库
     */
    private function connect($isMaster = false) {
    	
        // 忽略主从 // isset->isresource
        if($this->masterIgnore){
        	if (PHP_SAPI=='cli'){
        		return mysql_connect($this->config['host'], $this->config['user'], $this->config['passwd'],true);
        	}
            if (empty(self::$connections[$this->configName])) {
                self::$connections[$this->configName] = mysql_connect($this->config['host'], $this->config['user'], $this->config['passwd']);
            }
            return self::$connections[$this->configName];
        }
        else{
            // 强制主库 || 自动主库
            if ($this->masterForce || $isMaster) {
            	if (PHP_SAPI=='cli'){
            		return mysql_connect($this->config['master']['host'], $this->config['master']['user'], $this->config['master']['passwd'],true);
            	}
                if (empty(self::$connections[$this->configName . 'write'])) {
                    self::$connections[$this->configName . 'write'] = mysql_connect($this->config['master']['host'], $this->config['master']['user'], $this->config['master']['passwd']);
                }
                return self::$connections[$this->configName . 'write'];
            } else {
            	if (PHP_SAPI=='cli'){
            		$_host = $this->config['slave']['host'][ip2long($_SERVER['REMOTE_ADDR']) % count($this->config['slave']['host'])];
            		return mysql_connect($_host, $this->config['slave']['user'], $this->config['slave']['passwd'],true);
            	}
                if (empty(self::$connections[$this->configName . 'read'])) {
                    // 根据访问者的IP 平均分配从库资源,最大限度利用mysql高速缓存
                    $_host = $this->config['slave']['host'][ip2long($_SERVER['REMOTE_ADDR']) % count($this->config['slave']['host'])];
                    self::$connections[$this->configName . 'read'] = mysql_connect($_host, $this->config['slave']['user'], $this->config['slave']['passwd']);
                }
                return self::$connections[$this->configName . 'read'];
            }
        }
    }
    
    
    /**
     * 自动根据$sql语句来返回结果的方法,
     * 只建议在执行sql语句时使用,SELECT建议使用getOne,getAll
     * @param string $sql
     * @return type mix 可能是二维array,或者执行的bool值
     */
    function query($sql){
         if (substr(strtoupper($sql), 0, 1) === 'S') {
             return $this->getAll($sql);
         }
         else{
             return $this->excute($sql);
         }
    }

    /**
     * 执行一条sql语句
     * 默认这里不允许执行
     * @param string $sql 
     * @param string $delCheck 是否检查Delete,
     */
    function excute($sql,$delCheck=true) {
        // 判断主从
        //$slowsqlMonitor = SlowsqlMonitor::getInstance($sql);
        $sql = trim($sql);
        // delete,truncate,drop 安全判断
        if($delCheck && preg_match('/^(DELETE|TRUNCATE|DROP)/', strtoupper($sql))){
            throw new LogicException('执行删除操作,请带上delcheck参数=true: '.$sql,EXCEPT_DB);
        }
        
        // 主从分离
        if(!$this->masterIgnore){
            if (substr(strtoupper($sql), 0, 1) === 'S') {
                $link = $this->connect();
            } else {
                $link = $this->connect(true);
            }
        }
        else{
            $link = $this->connect();
        }
        // 选择数据库
        if (isset($this->config['db'])) {
            if (!mysql_select_db($this->config['db'], $link)) {
                throw new LogicException('数据库' . $this->config['db'] . '不存在', EXCEPT_DB);
            }
        }

		mysql_set_charset('utf8',$link);

        $this->query = mysql_query($sql, $link);
        // 连不上数据库,应该写入数据库错误
        if ($this->query === false) {
            throw new LogicException('数据库错误:' . mysql_error() . ':' . $sql, EXCEPT_DB);
        }
        $this->queryCount++;
        //$slowsqlMonitor->end("succ", 0);
        return $this->query;
    }

    /**
     * 最得insertId
     * @return int 
     */
    public function insertId() {
        return mysql_insert_id();
    }

    /**
     * 获取方法扩展
     * 
     * @param string $sql
     * @param 
     */
    public function getAll($sql, $is_master = false, $type=MYSQL_ASSOC) {
        $query = $this->excute($sql, $is_master);
        $row = $rows = array();
        while ($row = mysql_fetch_array($query, $type)) {
            $rows[] = $row;
        }
        mysql_free_result($query);
        return $rows;
    }

    /**
     * 获取单行数据
     *
     * @param string $sql
     * @param bool $
     * @return array
     */
    public function getOne($sql) {
        // 去除连续空格
        if (substr(strtoupper(trim(preg_replace('/\s{2,}/', ' ', $sql))), -7, 7) != 'LIMIT 1') {
            $sql .=' LIMIT 1';
        }
        $query = $this->excute($sql);
        return mysql_fetch_array($query, MYSQL_ASSOC);
    }
    
    /**
     * 自动加上`符号 
     * @param string $item 字段
     * @return string $item 加工后字段
     */
    private function graveAccent($item){
        if(substr($item, 0,1) != '`'){
            return '`'.$item.'`';
        }
        return $item;
    }

    /**
     * 以新的$key_values更新mysql数据,
     * 
     * 注意:该方法不检查key_falues的数据正确性,不支持诸如UNIX_TIMESTAMP()等mysql方法
     * 
     * @param array $keyValues array('aid'=>1,'cid'=>2)
     * @param string $where e.g. `file_id` = 10024 AND `user_id` = 122332
     * @param string $tableName  e.g. u_user_file_002
     * @return boolean 如果想得到affected_rows请调用cls_database::affected_rows
     */
    public function update($keyValues, $where, $tableName,$updateOne = true) {
        $sql = 'UPDATE '. $this->graveAccent($tableName).' SET ';
        foreach ($keyValues as $k => $v) {
            $sql .= $this->graveAccent(addslashes($k))."='".addslashes($v)."',"; // `db`.`zzz` update `zzz` set
        }
        $sql = substr($sql, 0, -1) . "  WHERE {$where}";
        // 默认只更新一条
        if($updateOne && substr(strtoupper(trim(preg_replace('/\s{2,}/', ' ', $sql))), -7, 7) != 'LIMIT 1') {
            $sql .=' LIMIT 1';
        }
        return $this->excute($sql);
    }

    /**
     * 插入一条新的数据
     * 注意:该方法不检查key_falues的数据正确性,不支持诸如UNIX_TIMESTAMP()等mysql方法
     * 返回true,如果
     * @param array $keyValues array('aid'=>1,'cid'=>2)
     * @param string $where e.g. `file_id` = 10024 AND `user_id` = 122332
     * @param string $tableName  e.g. u_user_file_002
     * @return boolean 如果想得到insert_id请调用cls_database::insert_id()
     */
    public function insert($keyValues, $tableName,$sqlSafe=true, $ignore=false) {
        $items_sql = $values_sql = "";
        foreach ($keyValues as $k => $v) {
            $k = $this->graveAccent(addslashes($k));
            $v = addslashes($v);
            $items_sql .= "$k,";
            $values_sql .= "'$v',";
        }
        $sql = "INSERT ".($ignore?"IGNORE":"")." INTO {$tableName} (" . substr($items_sql, 0, -1) . ") VALUES (" . substr($values_sql, 0, -1) . ")";
        return $this->excute($sql);
    }

    /**
     * 多条记录同时insert或者replace
     * @param array $keyValues
     * @param string $table_names
     * @return boolean true/false
     */
    public function multiInsert($keyValues, $tableName, $replace = false) {
        $items = $values = array();
        foreach ($keyValues as $k => $v) {
            if (!$items) {
                $items = array_keys($v);
            }
            $values[$k] = "('" . join("','", $v) . "')";
        }

        $sql = $replace ? 'REPLACE INTO ' : 'INSERT INTO';
        if ($items && $values) {
            array_walk($items, 'addslashes');
            array_walk($values, 'addslashes');
            $item_str = join(",",  $this->graveAccent($items));
            $value_str = join(',', $values);
            $sql .= " {$tableName} ({$item_str}) VALUES {$value_str}";
            return $this->excute($sql);
        } else {
            return false;
        }
    }

    /**
     * 取得一个表的初始数组,包括所有表字段及默认值，无默认值为''
     * @param string $tableName  表名
     * @return array $result 表结构数组
     */
    public function getStructure($tableName) {
        $rt = $this->getAll("DESC `{$tableName}`");
        $result = array();
        foreach ($rt as $v) {
            $result[$v['Field']] = $v['Default'] === NULL ? '' : $v['Default'];
        }
        return $result;
    }

    /**
     * 释放连接,一般没有必要释放连接
     * @param  $type  read or write
     */
    public function close() {

        // 关闭
        if($this->masterIgnore){
            @mysql_close(self::$connections[$this->configName]);
        }
        else{
            @mysql_close(self::$connections[$this->configName . 'write']);
            @mysql_close(self::$connections[$this->configName . 'read']);
        }
    }

	public function getIn($field, $data){
		//AND (`f` = $data1 OR `f` = $data2....)
		$out = array();
		if (!$data) return "";
		foreach ($data as $d) {
			$out[] = "`$field` = '$d'";
		}
		return "AND (" . implode(" OR ", $out) . ")";

	}
}