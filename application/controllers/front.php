<?php

class Front extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('user_model');
    }

    public function index() {
        $data['user'] = $this->user_model->get_user();
    }
    
    public function view() {

        $data['user'] = $this->user_model->get_user();
        $data['title'] = 'Home '; // Capitalize the first letter

        $this->load->view('templates/header', $data);
        $this->load->view('pages/home', $data);
        $this->load->view('templates/footer', $data);
    }

}

?>
