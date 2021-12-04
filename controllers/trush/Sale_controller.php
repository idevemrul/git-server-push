<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Sale_controller extends CI_Controller {
	public function __construct(){
		date_default_timezone_set("Asia/Dhaka");
		parent::__construct();
		//$this -> load -> model('application_model');
		$this->load->library('tank_auth');
		if(!$this->tank_auth->is_logged_in()) {
			redirect("auth/login");
		}
	}

	// --------- Open file oppening form submision---------
	// public function saleInvoice(){
	// 	echo "Service add";
	// 	$this->load->model('Sale_model');
	// 	$data['service'] = $this->Sale_model->serviceList();
	// 	$this->load->view('sale/file_view',$data);
	// }

}   //----- end class----------