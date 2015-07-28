<?php
/**
 *
 * User: XKJ
 * Date: 14-10-9
 * Time: 下午4:55
 */
class PubAutoCreateEnt{
    public $field;//字段信息
    public $db;//db
    public $table;
    public $entDemo;
    public $classname;
    public $date;
    public $time;
    public $item;
    public $arr;
    public $user;
    public static function init($table,$classname,$entDemo='EntDemo'){
        return new PubAutoCreateEnt($table,$classname,$entDemo);
    }
    private function __construct($table,$classname,$entDemo)
    {
        // TODO: Implement __construct() method.
        $m = new Model();
        $this->db = $m->dbWeixin;
        $this->table = $table;
        $this->entDemo = $entDemo;
        $this->classname = $classname;
        $this->date = date("Y-m-d");
        $this->time = date('H:i:s');
        $this->user = "AutoCreate";
    }
    public function getField(){
        $select="desc {$this->table}";
        $re = $this->db->getAll($select);
        if(!$re) new RuntimeException('table null');
        $this->field = $re;
        $this->item="";
        $this->arr="";
        foreach($this->field as $v){
            $this->item.="    public $".$v['Field'].";\r\n";
            $this->arr.="            '".$v['Field']."'=>\$this->".$v['Field'].",\r\n";
        }
    }
    public function create(){
        $this->getField();
        ob_start();
        include PATH_APP."/manage/entity/EntDemo.php"; //引入模板文件
//        LibTemplate::displayAll($sourceFile);
        $_html = ob_get_clean();
        $_res = file_put_contents(PATH_APP."/manage/entity/".$this->classname.".php", $_html);
    }
}