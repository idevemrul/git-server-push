<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ledger_controller extends CI_Controller {
	public function __construct()
	{
		date_default_timezone_set("Asia/Dhaka");
		parent::__construct();
		$this ->load-> model('Ledger_model');
		$this->load->library('tank_auth');
		if(!$this->tank_auth->is_logged_in()) {
			redirect("auth/login");
		}
	}

	public function transactionList(){
		$this ->load-> model('Ledger_model');
		$data=$this->Ledger_model->transactionList();
		// pre($data);
		$this->load->view('ledger/transaction_list.php',$data);
	}

	public function transactionFile(){
		$data=$this->Ledger_model->transactionFile();
		// pre($data);
		$this->load->view('ledger/transaction_file.php',$data);
		
	}
	public function transactionFileLedger(){
		$file_data=$this->input->post();

		$file_id=$file_data['file_id'];
		// pre($file_data);
		$data['transaction_file_id']=$this->Ledger_model->transactionFileSelection();
		$data['transaction_file']=$this->Ledger_model->transactionFileLedger($file_id);
		$this->load->view('ledger/transaction_file.php',$data);
		// pre($data);
	}

	public function transactionCash(){
		$data=$this->Ledger_model->transactionCash();
		// pre($data);
		$this->load->view('ledger/transaction_cash.php',$data);

	}
	public function transactionBank(){
		$data['transaction_bank']=$this->Ledger_model->transactionBank();
		$this->load->view('ledger/transaction_bank.php',$data);

	}

}