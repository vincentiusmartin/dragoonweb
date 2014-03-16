<?php

class User extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('user_model');
    }

    public function index() {
        $validated = $this->isvalidated();

        if ($validated)
        {
            $this->load->view('templates/login_successful');
        }
        else
        {
            $this->session->set_flashdata('login_message', '<font color=red>Please login to access the main page.</font><br />');
            redirect('/');
        }
    }

    public function login() {
        $result = $this->user_model->validate();

        if (!$result) {
            $this->session->set_flashdata('login_message', '<font color=red>Wrong username and/or password.</font><br />');
            redirect('/');
        } else {
            redirect('/user/');
        }
    }

    public function logout() {
        $this->session->sess_destroy();

        redirect('/');
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

    private function isvalidated()
    {
        if($this->session->userdata('validated')) {
            return true;
        }
        return false;
    }
}

?>