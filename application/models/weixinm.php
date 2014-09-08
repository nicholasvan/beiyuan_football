<?php
class Weixinm extends CI_Model {
    private $tablename = 'static';

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * 记录助攻进球数据
     */
    public function recordStatic($type, $name){
        $type_hash = array('助攻'=>'assis', '进球'=>'goal');
        $type = $type_hash[$type];
        $query = $this->db->get_where($this->tablename, array('name' => $name));
        $result = $query->result();
        if (empty($result)) {
            $this->db->set('name', $name);
            $this->db->set($type, $type+1);
            $this->db->insert($this->tablename);
            $r_num = 0;
        } else {
            $r_num = $result[0]->$type;
            $sql = "update {$this->tablename} set $type=$type+1 where name='$name'";
            $this->db->query($sql);
        }
        return $r_num + 1;
    }

    /**
     * 数据总数
     */
    public function total()
    {
        $query = $this->db->get($this->tablename);
        $result = $query->result();
        $str = "";
        foreach ($result as $row) {
            if($str){
                $str .="\n";
            }
            //$ret[] = array('name' => $row->name, 'goal' => $row->goal, 'assis' => $row->assis) ;
            $goal = intval($row->goal);
            $assis = intval($row->assis);
            $str .= "{$row->name} 助攻: $assis\t进球: $goal";
        }
        if (empty($str)) {
            $str = "暂无数据";
        }
        return $str;
    }
}
