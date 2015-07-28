<?php
/**
 * ctl基础类
 * 子类必须实现action方法
 * 推荐实现init方法
 * 定义了参数,验证参数,
 * 输出
 * 跳转
 * @author dvinci
 */
class Controller {

    static protected $outType = 'smarty';   // smarty json ajax
    public  $tpl = null;  // index.tpl
    public $out = null;
    
   
    /**
     * Display
     * @param mix $data 如果
     * @param mix $outType
     */
    public function display() {
        // 如果out=true,会封装成['data'] = true输出
        if(is_bool($this->out)){
            $_bool =  $this->out;
            unset($this->out);
            $this->out['data'] = $_bool;
        }
        
        
        // 调试信息
        if (DEBUG && isset( $GLOBALS['sys']['errorInfo'])){
           $this->out['_debug'] = $GLOBALS['sys']['errorInfo'];
        }
        
        // json跨域方式输出,
        // 如果有jsoncallback,默认用json输出
        if (self::$outType == 'json') {
            $callback = $this->R('jsoncallback');
            exit($callback .'('.json_encode($this->out).')');
        }
        // ajax直接输出
        elseif (self::$outType == 'ajax') {
            exit(json_encode($this->out));
        }
        // smarty输出
        // self::$outType == 'smarty'
        else {
            LibTemplate::displayAll($this->tpl,$this->out);
            if (!empty($this->out['_debug'])) {
                LibTemplate::assign("debug_info",  $this->out['_debug']);
                LibTemplate::display(PATH_APP . '/template/debug.tpl');
            }
        }
    }
    
    

    /**
     * 设置输出类型及模板,如果是'ajax'或者'json',则以ajax,json输出.
     * 如果$outType = '*.tpl',则用smarty模板输出
     * @param string $outType 
     */
    public function setOutType($outType){
        if(!$outType){
            throw new LogicException('$outType不得为空');
        }
        if($outType == 'json' || $outType == 'ajax' || $outType == 'smarty' ){
            self::$outType =  $outType;
            return true;
        }
        throw new LogicException('$outType参数错误');
    }
    

    /**
     * 跳转
     */
    public function GO($url) {
        if(!$url){
            throw new LogicException('$url必须为true');
        }
        
        // 跳转
        header('Location: ' . $url);
        exit;
    }

    /**
     * 取得所有的GET值,POST值,主要用于调试
     * @param string $filter post,get
     * @return array
     */
    public function RA($filter="") {
		// 如果不是测试环境直接输出空值
		if (!DEBUG){ return array(); }
		
        if ($filter == "post") {
            $param = $_POST;
        } elseif ($filter == "get") {
            $param = $_GET;
        } else {
            $param = array_merge($_GET, $_POST);
        }

        if ($param) {
            foreach ($param as $k => $v) {
                if (is_array($v)) {
                    array_walk($v, 'trim');
                    $param[trim($k)] =$v;
                } else {
                    $param[trim($k)] = htmlspecialchars(trim($v));
                }
            }
        }
        return $param;
    }
 

    /**
     * 或者请求的参数的方法
     * @param string $item          变量名
     * @param var $defaultValue    默认值
     * @param string $varType      强制类型转换
     */
    public function R($key, $defaultValue="", $varType="", $callback='Controller::_R') {
        if(!$key){
            throw new LogicException('$item不能为空');
        }
		
        //如果$_POST[$key]不存在，则取$_GET[$key]
        $_tmp = isset($_POST[$key])?$_POST[$key]:
                (isset($_GET[$key])?$_GET[$key]:$defaultValue);

        if (!empty($callback)){
            $_tmp = call_user_func($callback, $_tmp);
        }

        //强制类型转换
        if(!empty($varType)) settype($_tmp, $varType);

        return $_tmp;
    }
	
    /**
    * 默认R值处理函数
    * 
    * @param mixed $var
    * @return mixed 
    */
    private static function _R($var) {
            if (!is_array($var)){
                    $_tmp = htmlspecialchars(addslashes(trim($var)));
            }else{
                    foreach($var as $k=>$v){
                            $_tmp[$k] = self::_R($v);
                    }
            }
            return $_tmp;
    }
}
?>
