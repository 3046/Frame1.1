<?php /* Smarty version Smarty-3.0.8, created on 2015-05-06 17:31:34
         compiled from "F:\www\frame1.1/app/template/msg.tpl" */ ?>
<?php /*%%SmartyHeaderCode:168335549df765aef58-44667655%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2be41f2ccb3415836a9f047a8a5433cb55ac69c6' => 
    array (
      0 => 'F:\\www\\frame1.1/app/template/msg.tpl',
      1 => 1419068159,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '168335549df765aef58-44667655',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<html>
    <head></head>
    <body>
        <!-- 该模板比较简单,可按需进行修改 -->
        <div>
            <h1>系统提示</h1>
            <?php if ($_smarty_tpl->getVariable('_code')->value!=0){?>
               <b> 错误代码:</b><?php echo $_smarty_tpl->getVariable('_code')->value;?>

                <br />
            <?php }?>
            <b>出错信息:</b> <?php echo $_smarty_tpl->getVariable('_msg')->value;?>
<br />
            <?php if (!empty($_smarty_tpl->getVariable('_exception_detail',null,true,false)->value)){?>
                <div>
                 <b>详细信息: </b>Exception  in <?php echo $_smarty_tpl->getVariable('_exception_detail')->value['file'];?>
  on <?php echo $_smarty_tpl->getVariable('_exception_detail')->value['line'];?>
 <br/>
                 <dl>
                     <dt><b>错误跟踪:</b> </dt>
                 <?php  $_smarty_tpl->tpl_vars['tr'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('_exception_detail')->value['trace']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['tr']->key => $_smarty_tpl->tpl_vars['tr']->value){
?>
                    <dd><?php echo $_smarty_tpl->tpl_vars['tr']->value;?>
</dd>
                <?php }} ?>
                <dl>
                </div>
            <?php }?>
            
        </div>

    </body>
</html>