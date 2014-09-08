<?php
class Weixinm extends CI_Model {
    private $tablename = 'static';

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function recordStatic($type, $name){
        $query = $this->db->get_where($this->tablename, array('name' => $name));
        $result = $query->result();
        if (empty($result)) {
            $this->db->set('name', $name);
            $this->db->set($type, $type+1);
            $this->db->insert($this->tablename);
        } else {
            $this->db->query("update {$this->tablename} set $type=$type+1 where name='$name'");
        }
    }
}
