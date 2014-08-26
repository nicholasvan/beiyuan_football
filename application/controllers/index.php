<?php
/**
 * 默认登陆界面 
 * @author wangkun <wangkun@wangkundeMacBook-Pro.local>
 */
class Index extends CI_Controller {	
    function __construct() {
        parent::__construct();
        session_start();
        $this->load->database();
    }

    public function index() {
        if (isset($_SESSION['AUTH_USER'])) {
            $this->showActivity();
        } else {
            $this->load->view('login', array('title' => '登录北苑伙伴报名系统'));
        }
    }

    public function login() {
        $name = $this->input->post('name');
        $pass = $this->input->post('pass');
        $query = $this->db->get_where('users', array('name' => $name, 'pass' => $pass));
        $result = $query->result();
        if (empty($result)) {
            $this->load->view('login', array('title' => '登录北苑伙伴报名系统', 'invalid' => true));
        } else {
            $_SESSION['AUTH_USER'] = $name;
            $this->showActivity();
        }
    }

    public function logout() {
        unset($_SESSION['AUTH_USER']);
        $this->load->view('login', array('title' => '登录北苑伙伴报名系统'));
    }

    public function register() {
        $this->load->view('register', array('title' => '注册为球队成员'));
        $this->load->model('Personal');
        $submit = $this->input->post('submit');
        if ($submit) {
            $data['name'] = $this->input->post('name');
            $data['pass'] = $this->input->post('pass');
            $data['pri_pos'] = $this->input->post('pri_pos');
            $data['sec_pos'] = $this->input->post('sec_pos');
            $this->Personal->add($data);
            $this->show();
        }
    }

    /**
     * 显示活动
     */
    public function showActivity() {
        if (!isset($_SESSION['AUTH_USER'])) {
            $this->load->view('login', array('title' => '登录北苑伙伴报名系统'));
        } else {
            $this->load->model('Activity');
            $result = $this->Activity->getRecent();
            $this->load->view('activity', array('title' => '活动详情', 'acts' => $result));
        }
    }

    public function createActivity() {
        if (!isset($_SESSION['AUTH_USER'])) {
            $this->load->view('login', array('title' => '登录北苑伙伴报名系统'));
        } else {
            $this->load->view('create_activity', array('title' => '发起活动'));
            if (isset($_POST['submit'])) {
                $data['time'] = $this->input->post('time');
                $data['limit'] = $this->input->post('limit');
                $data['date'] = $this->input->post('date');
                $data['pos'] = $this->input->post('pos');
                $this->db->insert('activity', $data);
            }
        }
    }

    public function join() {
        $date = $this->input->post('date');
        $type = $this->input->post('type');
        $this->load->model('Activity');
        $ret  = $this->Activity->joinAct($date, $type);
        return $ret;
    }

    public function grouping() {
        $this->load->model('Personal');
        $this->load->model('Activity');
        $date = '08-14';
        $players = $this->Activity->joinPlayers($date);
        echo "<pre>";
        print_r($players);
        $query = $this->Personal->get();
        $rows  = $query->result();
        $count = 0;
        $apply_members = array(
            "王定坤", "雷宇", "老韩", "文捷", "云鹏", "海玉", "远飞", "潮思", "小李", "小贾", 
            "若潇",   "一杰", "松",   "老刘", "周玺", "施丹", "叮当", "亚鹏", "天宇", "王炜", "富强");
        foreach ($rows as $row) {
            $name = $row->name;
            if (!in_array($name, $apply_members)) {
                continue;
            }
            $p    = $row->pri_pos;
            $s    = $row->sec_pos;
            $pri[$p][$name] = $p;
            $sel[$p.$s][$name] = $p.$s;
        }
        $total_player = count($apply_members);
        $teams = 3;
        $position = array('f', 'm', 'b');
        $pos_num  = array('b' => 3, 'f'=> 1, 'm' => 3); //每队最少人数

        $team = intval($total_player / $teams);
        echo "每队人数： $team\n";
        $backward_num = $pos_num['b'] * $teams;
        $forward_num  = $pos_num['f'] * $teams;
        $midfield_num = $pos_num['m'] * $teams;

        $select_peoples = array('b'=>array(), 'f'=>array(), 'm'=>array());

        /*
        print_r($pri);
        print_r($sel);
         */

        $avaliable = array();
        foreach ($pos_num as $pos => $num) {
            $min_num = $num * $teams;
            $anum = count($pri[$pos]) - $min_num;
            //echo "$pos -- $anum\n";
            if ($anum > 0) {
                //echo "$pos > 0\n";
                $can_sel_pos = array_diff($position, array($pos));
                //print_r($can_sel_pos);
                foreach ($can_sel_pos as $cpos) {
                    //echo "cpos = $cpos\n";
                    $avaliable[$cpos] = @array_merge((array)$sel[$pos.$cpos], (array)$avaliable[$cpos]);
                }
            } else {
                $need_extra[$pos] = $anum;
            }
        }

        /*
        print_r($avaliable);
        print_r($need_extra);
         */

        foreach ($need_extra as $pos => $num) {
            if ($num < 0 && isset($avaliable[$pos])) {
                $can_sel_num = min(abs($num), count($avaliable[$pos])); 
                $sel_player = array_rand($avaliable[$pos], $can_sel_num);
                //echo "$can_sel_num --- sel_player : \n";
                //print_r($sel_player);
                foreach ($sel_player as $p) {
                    $type = $avaliable[$pos][$p];
                    //echo "$p type = $type\n";
                    unset($pri[$type[0]][$p]);
                    $pri[$pos][$p] = $pos;
                }
            }
        }
        print_r($pri);

        $final_group = array(0=>array(), 1=>array(), 2=>array());
        foreach ($pri as $pos => $final_players) {
            $final_players = array_keys($final_players);
            shuffle($final_players);
            $total_num = count($final_players);
            $num_per_team = (int)$total_num / $teams;
            $chunk = array_chunk($final_players, $num_per_team);
            foreach ($chunk as $index => $members) {
                $final_group[$index] = @array_merge($final_group[$index], $members);
            }
        }
        print_r($final_group);
        foreach ($final_group as $t_num => $members) {
            echo "分组{$t_num}:\n";
            foreach ($members as $m) {
                echo "$m; ";
            }
            echo "\n";
        }
        die();
        echo "</pre>";
    }

    public function editJoinList()
    {
        $players = $this->input->post('players');
        $date    = $this->input->post('date');
        $this->load->model('Activity');
        $this->Activity->editJoinList($date, $players);
    }

    public function personal()
    {
        if (isset($_SESSION['AUTH_USER'])) {
            $this->load->view('personal');
            $this->load->model('Personal');
            $submit = $this->input->post('submit');
            if ($submit) {
                $data['name'] = $this->input->post('name');
                $data['pass'] = $this->input->post('pass');
                $data['pri_pos'] = $this->input->post('pri_pos');
                $data['sec_pos'] = $this->input->post('sec_pos');
                $this->Personal->edit($data);
                echo "<script>";
                echo "alert('修改成功');";
                echo "document.location='index'";
                echo "</script>";
            }
        } else {
            $this->load->view('login', array('title' => '登录北苑伙伴报名系统', 'invalid' => true));
        }
    }

    public function test()
    {
        $this->load->model('Personal');
        $name = array(
            "雷宇" => array('p'=>'f', 's'=>'m'),
            "一杰" => array('p'=>'m', 's'=>'b'),
            //"文捷" => array('p'=>'b', 's'=>'f'),
            "海玉" => array('p'=>'b', 's'=>'m'),
            "富强" => array('p'=>'b', 's'=>'m'),
            "大薛" => array('p'=>'b', 's'=>'f'),
            "云鹏" => array('p'=>'b', 's'=>'m'),
            "亚鹏" => array('p'=>'b', 's'=>'f'),
            "老韩" => array('p'=>'f', 's'=>'b'),
            "陈路" => array('p'=>'b', 's'=>'f'),
            "天宇" => array('p'=>'m', 's'=>'b'),
            "中原" => array('p'=>'f', 's'=>'m'),
            "小贾" => array('p'=>'m', 's'=>'f'),
            "潮思" => array('p'=>'b', 's'=>'m'),
            "远飞" => array('p'=>'m', 's'=>'f'),
            "小韩" => array('p'=>'f', 's'=>'m'),
            "咔X" => array('p'=>'b', 's'=>'m'),
            "郑超" => array('p'=>'f', 's'=>'m'),
            //"王定坤" => array('p'=>'m', 's'=>'f'),
            "小李" => array('p'=>'m', 's'=>'b'),
            "ying" => array('p'=>'f', 's'=>'m'),
            "叮当" => array('p'=>'f', 's'=>'m'),
            "若潇" => array('p'=>'m', 's'=>'b'),
            "新星" => array('p'=>'m', 's'=>'b'),
            "松" => array('p'=>'b', 's'=>'m'),
            "阿东" => array('p'=>'b', 's'=>'m'),
            "王炜" => array('p'=>'b', 's'=>'m'),
            "老刘" => array('p'=>'m', 's'=>'b'),
            "大山" => array('p'=>'b', 's'=>'m'),
            "若飞" => array('p'=>'b', 's'=>'m'),
            "周玺" => array('p'=>'b', 's'=>'m'),
            "施丹" => array('p'=>'m', 's'=>'b'),
        );
        foreach ($name as $n => $detail) {
            $data['name']    = $n;
            $data['pri_pos'] = $detail['p'];
            $data['sec_pos'] = $detail['s'];
            $data['pass']    = 123;
            $this->Personal->add($data);
        }
    }

    public function viewGroup() {
        $group = array(
            0 => array('老刘', '小李', '天宇', '亚鹏', '潮思',   '海玉', '叮当'),
            1 => array('小贾', '施丹', '若潇', '松',   '小徐',   '云鹏', '老韩'),
            2 => array('一杰', '远飞', '王炜', '周玺', '王定坤', '雷宇', '文捷'),
        );
        //$this->load->model('Group'); todo
        $this->load->view('view_grouping', array('group' => $group));
    }
}
