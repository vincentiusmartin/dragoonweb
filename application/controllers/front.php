<?php

class Front extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('user_model');
    }

    public function index($message = NULL) {
        $data['user'] = $this->user_model->get_user();
    }
    
    private function isvalidated() {
        if ($this->session->userdata('validated')) {
            return true;
        }
        return false;
    }

    public function view() {
        $validated = $this->isvalidated();

        if (!$validated) {

            $login_message = $this->session->flashdata('login_message');
            if (isset($login_message)) {
                $message = $login_message;
            }

            $data['message'] = $message;
            $data['user'] = $this->user_model->get_user();
            $data['title'] = 'Home '; // Capitalize the first letter

            $this->load->view('templates/header', $data);
            $this->load->view('pages/home', $data);
            $this->load->view('templates/footer', $data);
        }else{
            redirect(base_url().'index.php/user');
        }
    }

}

?>
