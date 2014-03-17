<?php

class User extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('user_model');
    }

    public function index() {
        $validated = $this->isvalidated();

        if ($validated) {
            $this->load->helper(array('form', 'url'));

            $data['title'] = 'User Page';
            $data['username'] = $this->session->userdata('id');
            $data['notification'] = '';

            $this->load->view('templates/header', $data);
            $this->load->view('pages/profile', $data);
            $this->load->view('templates/footer');
        } else {
            $this->session->set_flashdata('login_message', '<font color=red>Please login to access the main page.</font><br />');
            redirect('/');
        }
    }

    function do_upload() {
        $pathToUpload = './uploads/'.$this->session->userdata('id');
        
        if (!file_exists($pathToUpload)) {
            $create = mkdir($pathToUpload, 0777);
        }

        $config['upload_path'] = $pathToUpload;
        $config['allowed_types'] = '*';
        $config['max_size'] = '100';
        //$config['max_width'] = '1024';
        //$config['max_height'] = '768';

        $this->load->library('upload', $config);

        $data['notification'] = '';

        $this->load->helper(array('form', 'url'));
        if (!$this->upload->do_upload()) {
            $data['notification'] = $this->upload->display_errors();
        } else {
            $data['upload_data'] = $this->upload->data();
        }

        $data['title'] = 'User Page';
        $data['username'] = $this->session->userdata('id');

        $this->load->view('templates/header', $data);
        $this->load->view('pages/profile', $data);
        $this->load->view('templates/footer');
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

        redirect(base_url());
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

    public function resetpassword() {
        $this->load->view('pages/reset');
    }

    public function processreset() {
        $this->load->library('email');
        $this->load->helper('email');

        $email = $this->security->xss_clean($this->input->post('email'));

        if (valid_email($email)) {
            // $this->email->from('dragoonweb@gmail.com', 'Dragoon Web');
            // $this->email->to($email);
            // $this->email->subject('Dragoon Web Reset Password Confirmation');
            // $this->email->message('To reset your password, please follow this link: </br></br>');
            // $this->email->send();
            mail($email, 'dragoonweb@gmail.com', 'Dragoon Web Reset Password Confirmation', 'To reset your password, please follow this link: </br></br>', 'From: Dragoon Web <dragoonweb@gmail.com>' . '\r\n');

            $data['message'] = "<font color=green>Reset password confirmation has been sent to your email</font>";

            $this->load->view('templates/blank', $data);
        } else {
            $data['message'] = "<font color=red>Email is not valid</font>";

            $this->load->view('templates/blank', $data);
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

    private function isvalidated() {
        if ($this->session->userdata('validated')) {
            return true;
        }
        return false;
    }

}

?>