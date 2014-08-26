<?php
/**
 * Short description for activity.php
 * @author wangkun <wangkun@wangkundeMacBook-Pro.local>
 */
class Activity extends CI_Model {
    private $tablename = 'activity';

    function __construct() {
        parent::__construct();
        $this->load->database();
    }


    function getRecent($cond = array()) {
        if (empty($cond)) {
            $query = $this->db->get($this->tablename);
        }

        return $query;
    }

    /**
     * @param string $date 参加某天活动
     */
    function joinAct($date, $type) {
        $query = $this->db->get_where($this->tablename, array('date' => $date));
        $ret = $query->result();
        if (isset($ret[0])) {
            $members = $ret[0]->members;
            $members = json_decode($members, true);
            $user    = $_SESSION['AUTH_USER'];
            if ($type == 'quit'){
                unset($members[$user]);
            } else {
                $time    = time();
                $members[$user] = $time;
            }
            $this->db->update($this->tablename, array('members' => json_encode($members)), array('date' => $date));
            echo 'ok';
        }
        return false;
    }

    /**
     * @param string $date
     * @param array  $players
     */
    public function editJoinList($date, $players) {
        $query = $this->db->get_where($this->tablename, array('date' => $date));
        $ret = $query->result();
        $players = str_replace('；', ';', $players);
        $players = rtrim($players, ';');
        $new_join = explode(';', $players);
        $now = time();
        if (isset($ret[0])) {
            $members = $ret[0]->members;
            $members = json_decode($members, true);

            $new_list = array();
            foreach ($new_join as $new_name) {
                if (isset($members[$new_name])) {
                    error_log("new_name = $new_name");
                    $new_list[$new_name] = $members[$new_name];
                } else {
                    $new_list[$new_name] = $now;
                }
            }
            $this->db->update($this->tablename, array('members' => json_encode($new_list)), array('date' => $date));
            die('ok');
        }
    }

    public function joinPlayers($date)
    {
        $query = $this->db->get_where($this->tablename, array('date' => $date));
        $ret = $query->result();
        if (isset($ret[0])) {
            $members = $ret[0]->members;
            $members = json_decode($members, true);
            return $members;
        }
    }
}
