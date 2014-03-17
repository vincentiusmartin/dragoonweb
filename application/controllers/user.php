<?php

class User extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('user_model');
    }

    public function index() {
        $this->load->view('templates/login_successful');
    }

    public function process() {
        $result = $this->user_model->validate();

        if (!$result) {
            redirect('/');
        } else {
            redirect('/user/');
        }
    }

    public function register() {
        $this->load->helper('form');
        $this->load->library('form_validation');

        $data['title'] = 'Register';

        $this->form_validation->set_rules('email', 'email', 'trim|required|valid_email|callback_unique_username_check|xss_clean');
        $this->form_validation->set_rules('password', 'password', 'trim|required|matches[passconf]|sha1|min_length[6]');
        $this->form_validation->set_rules('passconf', 'password confirmation', 'trim|required');

        if ($this->form_validation->run() === FALSE) { //fail to register
            $data['password_error'] = form_error('password');
            $data['email_error'] = form_error('email');
            $data['passconf_error'] = form_error('passconf');

            $this->load->view('templates/header', $data);
            $this->load->view('pages/register', $data);
            $this->load->view('templates/footer');
        } else { //success registered
            $data['username'] = $this->input->post('username');
            $this->user_model->set_user();

            $this->load->view('templates/header', $data);
            $this->load->view('pages/home', $data);
            $this->load->view('templates/footer');
        }
    }

    public function unique_username_check($str) {
        $query = $this->db->get_where('user', array('email' => $str));
        
        $count_row = $query->num_rows();
        
        if ($count_row > 0) {
            $this->form_validation->set_message('unique_username_check', 'Email has already registered, choose different email');
            return FALSE;
        } else {
            return TRUE;
        }
    }

}

?>