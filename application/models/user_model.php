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

    public function validate(){
        $username = $this->security->xss_clean($this->input->post('username'));
        $password = $this->security->xss_clean($this->input->post('password'));
        
        // query to check user in database
        $query = $this->db->get_where('user', array('email' => $username, 'password' => $password));

        if($query->num_rows == 1)
        {
            $row = $query->row;
            $userdata = array(
                    'id' => $row->email,
                    //'token' => $row->token,
                    'validated' => true
                    );
            $this->session->set_userdata($userdata);
            return true;
        }

        return false;
    }
}

?>
