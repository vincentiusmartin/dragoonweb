<?php

class User extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
	}

	public function index()
	{
		$data['user'] = $this->user_model->get_user();
	}

	public function view($email)
	{
		$data['user'] = $this->news_model->get_user($email);
	}
}
?>
