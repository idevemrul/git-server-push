<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account_controller extends CI_Controller {

	public function __construct()
	{	
		date_default_timezone_set("Asia/Dhaka");
		parent::__construct();
		$this->load->model('Account_model');
		$this->load->library('tank_auth');
		if(!$this->tank_auth->is_logged_in()) {
			redirect("auth/login");
		}
	}
	public function addAccountHead(){
		if ($this->form_validation->run('acc_head_validation')){
			$input=$this->input->post();
			$input['ach_by']=$this->session->userdata('user_id');
			date_default_timezone_set('Asia/Dhaka');
			$input['ach_date']=date('Y-d-m');
			$input['ach_time']=date('h:i:s');
			$input['ach_by']=$this->session->userdata('user_id');

			if ($this->Account_model->addAccountHead($input)) {
				$this->session->set_flashdata('message','Account head added successfully.');
				$this->session->set_flashdata('message-class','text-success');
				redirect('Account_controller/accountHeadView');
			}else{
				$this->session->set_flashdata('message','Account head creation failed !!!.');
				$this->session->set_flashdata('message-class','text-danger');
				redirect('Account_controller/accountHeadView');
			}
		}else{
			$this->session->set_flashdata('message','Duplicate head not allowed !!!.');
			$this->session->set_flashdata('message-class','text-danger');
			redirect('Account_controller/accountHeadView');
		}
	}

	public function accountHeadView(){
		$data=$this->Account_model->accountHeadView();
		$this->load->view('am_account/account_head.php',$data);
	}

	public function acshCompare(){
		$form_data=$this->input->post();
		$acsh_title=$form_data['acshTitle'];
		$output = '<ul class="searchOption">';
		if ($acsh_title !='') {
			$data=$this->Account_model->acshCompare($acsh_title);
			if (count($data)>0) {
				foreach ($data as $a) {
					$output .= '<li class="form-controll">'.$a->acsh_title.'</li>';
				}
			}
		}else{
			$output .='<li class="form-controll">No data match</li>';
		}
		$output .='</ul>';
		echo $output;
	}

	public function addAccountSubHead(){
		if ($this->form_validation->run('acc_sub_head_validation')){
			$form_data=$this->input->post();
			// pre($form_data);

			$form_data['acsh_by']=$this->session->userdata('user_id');
			date_default_timezone_set('Asia/Dhaka');
			$form_data['acsh_date']=date('Y-d-m');
			$form_data['acsh_time']=date('h:i:s');

			// pre($form_data);
			if ($this->Account_model->addAccountSubHead($form_data)) {
				$this->session->set_flashdata('message','Account Sub-head added successfully.');
				$this->session->set_flashdata('message-class','text-success');
				// $this->load->view('form/account_head.php');
				redirect('Account_controller/accountHeadView');
			}else{
				$this->session->set_flashdata('message','Account Sub-head creation failed !!!.');
				$this->session->set_flashdata('message-class','text-danger');
				redirect('Account_controller/accountHeadView');
			}
		}else{
			$this->session->set_flashdata('message','Duplicate Sub-head not allowed !!!.');
			$this->session->set_flashdata('message-class','text-danger');
			redirect('Account_controller/accountHeadView');
		}
	}

	public function accshCompare(){
		$form_data=$this->input->post();
		$accsh_title=$form_data['accshTitle'];
		$output = '<ul class="searchOption">';
		
		if ($accsh_title !='') {
			$data=$this->Account_model->accshCompare($accsh_title);
			if (count($data)>0) {
				foreach ($data as $a) {
					$output .= '<li class="form-controll">'.$a->accsh_title.'</li>';
				}
			}
		}else{
			$output .='<li class="form-controll">No data match</li>';
		}
		$output .='</ul>';
		echo $output;
	}

	public function addAccountCooSubHead(){
		if ($this->form_validation->run('acc_coo_sub_head_validation')){
			$this->load->model('Account_model');
			$form_data=$this->input->post();
			// pre($form_data);
			$id=$form_data['acsh_id'];
			$g=$this->Account_model->getAccSubHead($id);
			$form_data['ach_id']=$g[0]->ach_id;
			$form_data['accsh_by']=$this->session->userdata('user_id');
			date_default_timezone_set('Asia/Dhaka');
			$form_data['accsh_date']=date('Y-d-m');
			$form_data['accsh_time']=date('h:i:s');

			// pre($form_data);
			if ($this->Account_model->addAccountCooSubHead($form_data)) {
				$this->session->set_flashdata('message','Account Coo-Sub-head added successfully.');
				$this->session->set_flashdata('message-class','text-success');
				// $this->load->view('form/account_head.php');
				redirect('Account_controller/accountHeadView');
			}else{
				$this->session->set_flashdata('message','Account Coo-Sub-head creation failed !!!.');
				$this->session->set_flashdata('message-class','text-danger');
				redirect('Account_controller/accountHeadView');
			}
		}else{
			$this->session->set_flashdata('message','Duplicate Coo-Sub-head not allowed !!!.');
			$this->session->set_flashdata('message-class','text-danger');
			redirect('Account_controller/accountHeadView');
		}
	}
}   //----- end class----------