<?php
/**
 * Short description for Personal.php
 * @author wangkun <wangkun@wangkundeMacBook-Pro.local>
 */
class Personal extends CI_Model {
    private $tablename = 'users';

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function edit($data)
    {
        $name = $data['name'];
        $this->db->update($this->tablename, $data, array('name' => $name));
    }

    public function add($data)
    {
        $this->db->insert($this->tablename, $data);
    }

    public function get($name = null)
    {
        if ($name) {
            return $query = $this->db->get_where($this->tablename, array('name' => $name));
        }
        return $query = $this->db->get($this->tablename);
    }
}
