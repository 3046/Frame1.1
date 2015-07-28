<?php /* Smarty version Smarty-3.0.8, created on 2015-05-06 17:31:34
         compiled from "F:\www\frame1.1/app/template/debug.tpl" */ ?>
<?php /*%%SmartyHeaderCode:321025549df766dfa95-60446258%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0f58bf7e2ddfc62668d9fbf7c46839bc1f41c568' => 
    array (
      0 => 'F:\\www\\frame1.1/app/template/debug.tpl',
      1 => 1419068159,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '321025549df766dfa95-60446258',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<style>
    #debug_4399{
        z-index:99999;
        overflow-x:auto;
        border:  1px solid #DDD;
        background: #F4F6F9;
        margin:0;
        padding:5px 10px 0px 10px;
        font:12px/1.8 'lucida grande', tahoma, verdana, arial, sans-serif, "\5B8B\4F53";
        color:#333;
        font-family:Tahoma, Geneva, "\5fae\8f6f\96c5\9ed1", "\5B8B\4F53";
        overflow-y:scroll;
        height:400px;
        width:600px;
        position: fixed;
        right: 0px;
        bottom: 30px;
    }


    #debug_show {
        z-index:99999;
        border:  1px solid #DDD;
        background: red;
        padding-left: 5px;
        padding-right: 5px;
        height:30px;
        font:12px/1.8 'lucida grande', tahoma, verdana, arial, sans-serif, "\5B8B\4F53";
        color:#fff;
        font-family:Tahoma, Geneva, "\5fae\8f6f\96c5\9ed1", "\5B8B\4F53";
        text-decoration:  none;
        line-height: 30px;
        position: fixed;
        right: 0px;
        bottom: 0px;
    }


    #debug_4399 dt{
        font-size: 12px;
        font-weight:bold;
        margin-left:10px;
    }

    #debug_4399 dd{
        font-size: 10px;
        margin-left:20px;
    }
</style>
<div id='debug_4399' style='display:none'>
    <?php  $_smarty_tpl->tpl_vars['d'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['dk'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('debug_info')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['d']->key => $_smarty_tpl->tpl_vars['d']->value){
 $_smarty_tpl->tpl_vars['dk']->value = $_smarty_tpl->tpl_vars['d']->key;
?>
        <dl>
            <dt>
            <?php echo $_smarty_tpl->tpl_vars['d']->value['type'];?>
: <?php echo $_smarty_tpl->tpl_vars['d']->value['msg'];?>
  in <?php echo $_smarty_tpl->tpl_vars['d']->value['file'];?>
  on <?php echo $_smarty_tpl->tpl_vars['d']->value['line'];?>

            </dt>
            <!-- trace -->
            <?php  $_smarty_tpl->tpl_vars['tr'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['d']->value['track']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['tr']->key => $_smarty_tpl->tpl_vars['tr']->value){
?>
                <dd><?php echo $_smarty_tpl->tpl_vars['tr']->value;?>
</dd>
            <?php }} ?>
        </dl>
    <?php }} ?>
</div>
<a href='javascript:;' id='debug_show'>调试信息</a>
<script>
    document.getElementById('debug_show').onclick = function(){
        var debug_div = document.getElementById('debug_4399');

        if(debug_div.style.display=="none")
        {
            debug_div.style.display = "block";
        }
        else
        {
            debug_div.style.display = "none";
        }
    }; 

    
</script>