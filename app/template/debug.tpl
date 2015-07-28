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
    <{foreach from=$debug_info key=dk item=d}>
        <dl>
            <dt>
            <{$d.type}>: <{$d.msg}>  in <{$d.file}>  on <{$d.line}>
            </dt>
            <!-- trace -->
            <{foreach from=$d.track item=tr}>
                <dd><{$tr}></dd>
            <{/foreach}>
        </dl>
    <{/foreach}>
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