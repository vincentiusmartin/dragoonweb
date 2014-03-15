<?php

class User extends CI_Controller {

		function __construct(){
        parent::__construct();
    		$this->load->model('user_model');
    }

    public function index(){
        $this->load->view('templates/login_successful');
    }

    public function process()
    {
        $result = $this->user_model->validate();

        if(! $result){
            redirect('/');
        }else{
            redirect('/user/');
        } 
    }
}

?>