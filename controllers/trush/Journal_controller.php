<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Journal_controller extends CI_Controller {
	public function __construct()
	{	
		date_default_timezone_set("Asia/Dhaka");
		parent::__construct();
		$this ->load-> model('Journal_model');
		$this->load->library('tank_auth');
		if(!$this->tank_auth->is_logged_in()) {
			redirect("auth/login");
		}
	}

	public function transactionList(){
		$this ->load-> model('Journal_model');
		$data=$this->Journal_model->transactionList();
		// pre($data);
		$this->load->view('journal/transaction_list.php',$data);
	}

	public function transactionFile(){
		$data['transaction_file_id']=$this->Journal_model->transactionFile();
// pre($data);
		$this->load->view('journal/transaction_file.php',$data);
		
	}
	public function transactionFileJournal(){
		$file_data=$this->input->post();

		$file_id=$file_data['file_id'];
		// pre($file_data);
		$data['transaction_file_id']=$this->Journal_model->transactionFile();
		$data['transaction_file']=$this->Journal_model->transactionFileJournal($file_id);
		$this->load->view('journal/transaction_file.php',$data);
		// pre($data);
	}

	public function transactionCash(){
		$data['transaction_cash']=$this->Journal_model->transactionCash();
		$this->load->view('journal/transaction_cash.php',$data);

	}
	public function transactionBank(){
		$data['transaction_bank']=$this->Journal_model->transactionBank();
		$this->load->view('journal/transaction_bank.php',$data);

	}

}