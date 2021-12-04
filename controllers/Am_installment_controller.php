<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Am_installment_controller extends CI_Controller {

	public function __construct()
	{
		date_default_timezone_set("Asia/Dhaka");
		parent::__construct();
		//$this -> load -> model('application_model');
		$this->load->library('tank_auth');
		if(!$this->tank_auth->is_logged_in()) {
			redirect("auth/login");
		}
		$this->load->model('Am_installment_model');
	}

	//-------------------------------------------------
	// ===============START INSTALLMENT LIST SETUP=============
	public function installmentView(){
		$data['installments']=$this->Am_installment_model->installmentList();
		$data['saleIds']=$this->Am_installment_model->payInstallmentIdAndChasisNoCollection();
		$this->load->view('am_installment/installmentView',$data);
	}

	public function installmentSearch(){
		$form_data=$this->input->post();

		$sale_inv_id=$form_data['sale_inv_id'];

		$data['saleIds']=$this->Am_installment_model->payInstallmentIdAndChasisNoCollection();
		$data['installmentsSearched']=$this->Am_installment_model->installmentSearched($sale_inv_id);
		$this->load->view('am_installment/installmentView',$data);
	}


	public function invoiceInstallmentList(){
		$sale_inv_id=$_POST['sale_inv_id'];
		$data['installs']=$this->Am_installment_model->invoiceInstallmentList($sale_inv_id);
		// $install='test';
		foreach($data['installs'] as $install){
			$install.='<tr>';
				$install.='<td></td>';
				$install.='<td></td>';
			$install.='</td>';
		}
		echo json_encode($data['installs']);
	}

	// =============END INSTALLMENT LIST SETUP===========
	//-------------------------------------------

	// ------------------------------------------
	// ============START INSTALLMENT PAID LIST PAYMENT========
		public function installmentPaidView(){
			$data['installments']=$this->Am_installment_model->installmentPaidList();
			$data['invoices']=$this->Am_installment_model->installmentPaidListByInvoice();
			$this->load->view('am_installment/installmentPaidView',$data);
		}

		public function installmentPaidSearch(){
			$form_data=$this->input->post();

			$sale_inv_id=$form_data['sale_inv_id'];

			$data['installments']=$this->Am_installment_model->installmentPaidList();
			$data['invoices']=$this->Am_installment_model->installmentPaidListByInvoice();
			$data['installmentsSearched']=$this->Am_installment_model->installmentPaidSearched($sale_inv_id);
			$this->load->view('am_installment/installmentPaidView',$data);
		}

	// ====END INSTALLMENT PAID LIST PAYMENT===========
	//-------------------------------------------

	



}   //----- end class----------
