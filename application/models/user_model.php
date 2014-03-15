<?php

class User_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function get_user($email = FALSE) {
        if ($email === FALSE) {
            $query = $this->db->get('user');
            return $query->result_array();
        }

        $query = $this->db->get_where('user', array('email' => $email));
        return $query->row_array();
    }

    public function set_user() {
        $data = array(
            'email' => $this->input->post('email'),
            'password' => $this->input->post('password')
        );

        return $this->db->insert('user', $data);
    }

}

?>
