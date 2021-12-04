<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Due_party_controller extends CI_Controller {
	public function __construct()
	{
		date_default_timezone_set("Asia/Dhaka");
		parent::__construct();
		$this ->load-> model('Due_party_model');
		$this->load->library('tank_auth');
		if(!$this->tank_auth->is_logged_in()) {
			redirect("auth/login");
		}
	}
	public function index(){
		redirect('Due_party_controller/duePartyList');
	}

	public function duePartyList(){
		$this->load->model('Invoice_model');
		$data['dueCollectionFile']=$this->Invoice_model->paymentCollectionFile();
		$data['dueCollection']=$this->Due_party_model->duePartyList();
		$this->load->view('due_party/due_party_list.php',$data);
	}
	public function duePartyListSearch(){
		$form_data=$this->input->post();
		$file_id=$form_data['paymentCollectionFile'];
		$date=explode(' - ',$form_data['date_range']);
		$date_from=$date_to='';
		if($date[0]=='01/01/1970' && $date[1]=='01/01/1970'){
			$form_data['date_range']='';
			}else{
				$date_from=$date[0];
				$date_to=$date[1];
				$date_r=1;
				}
		$this->load->model('Invoice_model');
		if (!empty($file_id)) {
			$file=$this->Invoice_model->fileTitle($file_id);
			foreach ($file as $f) {}
			$file_title=$f->file_title;
			}else{
				$file_title='';
			}
		// pre($form_data);
		
		$data['dueCollectionFile']=$this->Invoice_model->paymentCollectionFile();

		if (!empty($file_id) && !empty($date_r)) {
			// pre('date file');
			$data['searchBy']='Search by: File [ '.$file_title.'], Date [ '.date('d-M-Y',strtotime($date_from)).' to '.date('d-M-Y',strtotime($date_to)).' ].';
			$data['dueCollection']=$this->Due_party_model->duePartyListSearchFileDate($file_id,$date_from,$date_to);
			}
			elseif (empty($file_id) && !empty($date_r)) {
				// pre('date');
				$data['searchBy']='Search by: Date [ '.date('d-M-Y',strtotime($date_from)).' to '.date('d-M-Y',strtotime($date_to)).' ].';
				$data['dueCollection']=$this->Due_party_model->duePartyListSearchDate($date_from,$date_to);
				// pre($data['dueCollection']);
				}
				elseif (!empty($file_id) && empty($date_r)) {
					// pre('file');
					$data['searchBy']='Search by: File [ '.$file_title.' ].';
					$data['dueCollection']=$this->Due_party_model->duePartyListSearchFile($file_id);
					}
					elseif (empty($file_id) && empty($date_r)) {
						redirect('Due_party_controller/duePartyList');
						}
		$this->load->view('due_party/due_party_list.php',$data);
	}
}