<?php

/**
 * 建议Ctl类以Ctl+单个单词组成,以免url出现驼峰,不好书写
 *
 * @author dvinci
 */
class CtlIndex extends Controller{
    //put your code here
    
    
    function index(){
        //throw new RuntimeException('禁止访问',100);
        $value = "
<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<callbackReq>
<orderid>2015052010201410693008lt</orderid>
</callbackReq>
";
        var_dump($this->xml_to_array($value));
        exit;
    }
    private function xml_to_array( $xml ){
        $reg = "/<(\w+)[^>]*>([\\x00-\\xFF]*)<\\/\\1>/";
        if(preg_match_all($reg, $xml, $matches))
        {
            $count = count($matches[0]);
            for($i = 0; $i < $count; $i++)
            {
                $subxml= $matches[2][$i];
                $key = $matches[1][$i];
                if(preg_match( $reg, $subxml ))
                {
                    $arr[$key] = $this->xml_to_array( $subxml );
                }else{
                    $arr[$key] = $subxml;
                }
            }
        }
        return $arr;
    }
    
    function login(){
        // for user logining
    }
    
    
    function logout(){
        
    }
    
}

