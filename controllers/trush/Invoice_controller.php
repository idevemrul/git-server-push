<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Invoice_controller extends CI_Controller {

	public function __construct()
	{
		date_default_timezone_set("Asia/Dhaka");
		parent::__construct();
		//$this -> load -> model('application_model');
		$this->load->library('tank_auth');
		if(!$this->tank_auth->is_logged_in()) {
			redirect("auth/login");
		}
		$this->load->model('Invoice_model');
	}

	public function invoiceList(){
		$this->load->model('Service_model');
		$data['invoice']=$this->Service_model->invoiceList();
		// pre($invoice);
		$this->load->view('invoice/invoice_list.php',$data);
	}

	public function invoiceView($id){
		// $id=$this->input->post();
		// pre($id);
		$this->load->model('Invoice_model');
		$data=$this->Invoice_model->invoiceView($id);
		// pre($data);
		$this->load->view('invoice/invoice_view.php',$data);
	}

	public function paymentCollectionList(){
		$this->load->model('Invoice_model');
		$data['dueCollectionFile']=$this->Invoice_model->paymentCollectionFile();
		$data['dueCollection']=$this->Invoice_model->paymentCollectionList();
		$this->load->view('invoice/due_pay_collection_list.php',$data);
	}
	public function paymentCollectionSearchList(){
		$form_data=$this->input->post();
		$date=explode(' - ',$form_data['date_range']);
		$date_from=$date_to='';
		if($date[0]=='01/01/1970' && $date[1]=='01/01/1970'){
			$form_data['date_range']='';
			}else{
				$date_from=$date[0];
				$date_to=$date[1];
				}

		$file_id=$form_data['paymentCollectionFile'];
		$date_r=$form_data['date_range'];
		if (!empty($file_id)) {
			$file=$this->Invoice_model->fileTitle($file_id);
			foreach ($file as $f) {}
			$file_title=$f->file_title;
			}else{
				$file_title='';
			}
		
		$data['dueCollectionFile']=$this->Invoice_model->paymentCollectionFile(); //-- collect file list--

		if (!empty($file_id) && !empty($date_r)) {
			$data['searchBy']='Search by: File [ '.$file_title.'], Date [ '.date('d-M-Y',strtotime($date_from)).' to '.date('d-M-Y',strtotime($date_to)).' ].';
			$data['dueCollection']=$this->Invoice_model->paymentCollectionAllSearch($file_id,$date_from,$date_to);
			}
			elseif (empty($file_id) && !empty($date_r)) {
				$data['searchBy']='Search by: Date [ '.date('d-M-Y',strtotime($date_from)).' to '.date('d-M-Y',strtotime($date_to)).' ].';
				$data['dueCollection']=$this->Invoice_model->paymentCollectionDateSearch($date_from,$date_to);
				}
				elseif (!empty($file_id) && empty($date_r)) {
					$data['searchBy']='Search by: File [ '.$file_title.' ].';
					$data['dueCollection']=$this->Invoice_model->paymentCollectionFileSearch($file_id);
					}
					elseif (empty($file_id) && empty($date_r)) {
						redirect('Invoice_controller/paymentCollectionList');
						}
		$this->load->view('invoice/due_pay_collection_list.php',$data);
	}

	public function paymentDueCollectionView($id){
		$this->load->model('Invoice_model');

		$data['payment_info']=$this->Invoice_model->paymentDueCollectionView($id);
		$file_id=$data['payment_info'][0]->file_id;
		// pre($file_id);
		$data['inv_due_bf']=$this->Invoice_model->payment_info($file_id,$id);
		// pre($data['inv_due_bf']);
		$this->load->view('invoice/due_collection_receipt.php',$data);
	}
}   //----- end class----------