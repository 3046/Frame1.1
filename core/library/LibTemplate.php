<?php
/**
 * Smarty 模板引擎
 *
 * @author 李金鸣 lijinming@4399.net
 * @version $Id$
 */
require PATH_ORG . '/smarty/Smarty.class.php';
class LibTemplate
{
    private static $instance=null;
    public static function init ()
    {
        if (self::$instance === null)
        {
            self::$instance = new Smarty();
            self::$instance->template_dir = PATH_CUR_APP .'/template';
            self::$instance->compile_dir = PATH_CACHE .'/tplCompile';
            self::$instance->cache_dir = PATH_CACHE .'/tplCache';
            self::$instance->left_delimiter = '<{';
            self::$instance->right_delimiter = '}>';
            self::$instance->caching = false;
            self::$instance->compile_check = true;
            self::$instance->plugins_dir[] = PATH_ORG . '/smarty/plugins';
        }
        return self::$instance;
    }
    
    /**
     * 以$out数组为变量显示模板
     * @param type $out
     * @param type $tpl 
     */
    public static function displayAll($tpl,$out=array()){
        if(!$tpl){
            throw new LogicException('模板$tpl不能为空',EXCEPT_CORE);
        }
        
        
        
        $instance = self::init();
        // $instance->tpl_vars = $out;
        foreach($out as $k=>$v){
            $instance->assign($k,$v);
        }
        unset($out);
        $instance->display($tpl);
    }
    
    
    /**
     *  同smarty的assign
     * @param type $tpl_var
     * @param type $value 
     */
    public static function assign ($tpl_var, $value)
    {
        $instance = self::init();
        $instance->assign($tpl_var, $value);
    }
    
    /**
     * 同smarty的display
     * @param type $tpl
     */
    public static function display ($tpl)
    {
        $instance = self::init();
        $instance->display($tpl);
    }

    
        /**
     * 同smarty的fetch
     * @param type $tpl
     */
    public static function fetch($tpl)
    {
        $instance = self::init();
        return $instance->fetch($tpl);
    }
}
?>