<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Bank_controller extends CI_Controller {

	public function __construct()
	{
		date_default_timezone_set("Asia/Dhaka");
		parent::__construct();
		//$this -> load -> model('application_model');
		$this->load->library('tank_auth');
		if(!$this->tank_auth->is_logged_in()) {
			redirect("auth/login");
		}
		$this->load->model('Bank_model');
	}
	public function bankList(){
		$data['bank_list']=$this->Bank_model->bankList();
		$data['bank']=$this->Bank_model->bankList();
		$this->load->view('bank/bank_list.php',$data);
	}
	public function bankAdd(){
		if ($this->form_validation->run('bank_validation')) {
			$form_data=$this->input->post();
			$form_data['bank_by']=$this->session->userdata('user_id');
		    date_default_timezone_set("Asia/Dhaka");
		    $form_data['bank_date']=date('Y-m-d');
		    $form_data['bank_time']=date('h:i:s');
		    $form_data['bank_status']=0;

		    if($this->db->insert('dbs_bank_ac',$form_data)){
		    	$this->session->set_flashdata('msg','Account added successfully');
				$this->session->set_flashdata('msg_class','alert-success');
				redirect('Bank_controller/bankList');
		    	}else{
		    		$this->session->set_flashdata('msg','Sorry ! Account add unsuccessfull.');
					$this->session->set_flashdata('msg_class','alert-danger');
					redirect('Bank_controller/bankList');
			    	}
		}else{
			$this->session->set_flashdata('msg','Sorry ! Please tyr again with valid data.');
			$this->session->set_flashdata('msg_class','alert-danger');
			redirect('Bank_controller/bankList');
		}
	}

	public function bankSearch(){
		$form_data=$this->input->post();
		$bank_name=$form_data['bank_name'];
		if (empty($bank_name)) {
			redirect('Bank_controller/bankList');
		}
		$data['bank_list']=$this->Bank_model->bankList();
		$data['bank']=$this->Bank_model->bankSearch($bank_name);
		$this->load->view('bank/bank_list.php',$data);
	}


}   //----- end class----------
