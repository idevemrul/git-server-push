<?php
defined('BASEPATH') or exit('No direct script access allowed');
if (!defined('BASEPATH')) exit('No direct script access allowed');

class test_controller extends CI_Controller
{

	public function __construct()
	{
		date_default_timezone_set("Asia/Dhaka");
		parent::__construct();
		//$this -> load -> model('application_model');
		$this->load->library('tank_auth');
		if (!$this->tank_auth->is_logged_in()) {
			redirect("auth/login");
		}
		$this->load->model('Am_profit_loss_model');
		$this->load->model('Am_feed_data_model');
	}

	public function index(){
		echo 'this is test controller';
	}

	public function test_invoice(){
		$this->load->view('test/test_invoice');
	}



}   //----- end class----------
