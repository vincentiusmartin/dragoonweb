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
    
    public function register(){
        $this->load->helper('form');
        $this->load->library('form_validation');
        
        $data['title'] = 'Register';
        
        $this->form_validation->set_rules('email', 'email', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'password', 'trim|required|matches[passconf]|md5');
        $this->form_validation->set_rules('passconf', 'password confirmation', 'trim|required');
        
        if ($this->form_validation->run() === FALSE) { //fail to register
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

}

?>