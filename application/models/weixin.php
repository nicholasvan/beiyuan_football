<?php
class Personal extends CI_Model {
    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function recordStatic($type, $name){
    }
}
