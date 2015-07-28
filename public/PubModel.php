<?php
/**
 *
 * User: XKJ
 * Date: 15-5-6
 * Time: 下午5:14
 */
class PubModel{
    public static function factory($className,$dir=''){
        $_file = '';
            // Mod,Lib,Pub,Ctl类的自动调用
        $clsPath = array('Mod' => PATH_MOD,'Ent'=>PATH_ENT,'Ser'=>PATH_SER);
        $_classType = substr($className, 0, 3);
        if (isset($clsPath[$_classType])) {
            $_file = "{$clsPath[$_classType]}/{$dir}" .basename($className) . '.php';
        }
        if($_file){
            // auto中不能抛出错误
            if(!is_file($_file)){
                exception_handler(new RuntimeException("Not Found",404));
                exit;
            }
            require $_file;
            return new $className();
        }

        return false;
    }
}