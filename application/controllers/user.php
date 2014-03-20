<?php

class User extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('user_model');
    }

    //--used functions--
    function userDirContent() {
        $this->load->helper('directory');

        $fileslist = array();

        $userpath = './uploads/' . $this->session->userdata('id');

        if (file_exists($userpath)) {
            $fileslist = directory_map($userpath);
        }
        
        return $fileslist;
    }
    //--end of user functions--
    
    public function index() {
        $validated = $this->isvalidated();

        if ($validated) {
            $this->load->helper(array('form', 'url'));

            $data['title'] = 'User Page';
            $data['username'] = $this->session->userdata('id');
            $data['notification'] = '';

            //read from directory
            $data['fileslist'] = $this->userDirContent();

            $this->load->view('templates/header', $data);
            $this->load->view('pages/profile', $data);
            $this->load->view('templates/footer');
        } else {
            $this->session->set_flashdata('login_message', '<font color=red>Please login to access the main page.</font><br />');
            redirect('/');
        }
    }

    function upload() {
        $pathToUpload = './uploads/' . $this->session->userdata('id');

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

        //read from directory
        $data['fileslist'] = $this->userDirContent();

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
        if ($validated) {
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
        } else {
            redirect('/');
        }
    }

    public function resetpassword() {
        $data['title'] = 'Reset Password';

        $this->load->view('templates/header', $data);
        $this->load->view('pages/reset');
        $this->load->view('templates/footer');
    }

    public function newpassword() {
        $data['title'] = 'Enter New Password';
        $data['code'] = $this->security->xss_clean($this->input->get('t'));

        $this->load->view('templates/header', $data);
        $this->load->view('pages/newpassword', $data);
        $this->load->view('templates/footer');
    }

    public function newpassprocess() {
        $password = sha1($this->input->post('password'));
        $repassword = sha1($this->input->post('repassword'));
        $token = $this->security->xss_clean($this->input->post('t'));

        if ($password === $repassword && isset($token)) {
            $query = $this->db->simple_query("UPDATE `dragoon`.`user` SET `password` = '" . $password . "' WHERE  `user`.`reset_code` = '" . $token . "'");
            if ($query) {
                $this->db->simple_query("UPDATE `dragoon`.`user` SET `reset_code` = NULL WHERE  `user`.`reset_code` = '" . $token . "'");
                $data['message'] = "<font color=green>Password has been reseted.</font>";
                $this->load->view('templates/blank', $data);
            } else {
                $data['message'] = "<font color=red>Reset password has failed.</font>";
                $this->load->view('templates/blank', $data);
            }
        } else {
            $data['message'] = "<font color=red>Password and password confirmation don't match.</font>";
            $this->load->view('templates/blank', $data);
        }
    }

    public function processreset() {
        $this->load->helper('email');

        $email = $this->security->xss_clean($this->input->post('email'));

        if (valid_email($email)) {
            // $this->email->from('dragoonweb@gmail.com', 'Dragoon Web');
            // $this->email->to($email);
            // $this->email->subject('Dragoon Web Reset Password Confirmation');
            // $this->email->message('To reset your password, please follow this link: </br></br>');
            // $this->email->send();

            $resetcode = random_string('sha1', 16);

            $this->user_model->set_resetcode($email, $resetcode);

            $config = Array(
                'protocol' => "smtp",
                'smtp_host' => "ssl://smtp.gmail.com",
                'smtp_port' => "465",
                'smtp_user' => "noreply.dragoonweb@gmail.com",
                'smtp_pass' => "otengcoolbanget",
                'charset' => "utf-8",
                'mailtype' => "html",
                'newline' => "\r\n",
                'validation' => TRUE
            );

            $this->load->library('email', $config);

            $this->email->from('noreply.dragoonweb@gmail.com', 'Dragoon Web');
            $this->email->to($email);
            $this->email->subject('Dragoon Web Reset Password Confirmation');
            $this->email->message('To reset your password, please follow this link: </br></br> <a href=' . base_url() . 'index.php/user/newpassword?t=' . $resetcode . ' >Reset Password</a>');
            $this->email->send();

            $data['message'] = "<font color=green>Reset password confirmation has been sent to your email </font>";

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