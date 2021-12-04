<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if (!defined('BASEPATH')) exit('No direct script access allowed');

class D_Ledger_controller extends CI_Controller {
	public function __construct()
	{
		date_default_timezone_set("Asia/Dhaka");
		parent::__construct();
		$this ->load-> model('D_Ledger_model');
		$this->load->library('tank_auth');
		if(!$this->tank_auth->is_logged_in()) {
			redirect("auth/login");
		}
	}
	public function index(){
		redirect('D_Ledger_controller/transactionList');
	}

	public function transactionList(){
		$data['transaction_list']=$this->D_Ledger_model->transactionList();
		$data['file_ledger']=$this->D_Ledger_model->transactionFileMemberSelection();
		// pre($data);
		$this->load->view('D_ledger/transaction_list.php',$data);
	}

	public function transactionListTypeSearch(){
		$file_data=$this->input->post();
		
		$type=$file_data['transaction_type'];
		$id=$file_data['file_id'];
		$date=explode(' - ',$file_data['date_range']);

		if($date[0]=='01/01/1970' && $date[1]=='01/01/1970'){
			$date_from=$date[0];
			$date_to=date('Y-m-d');
			$date_r='';
		}else{
			$date_from=$date[0];
			$date_to=$date[1];
			$date_r=1;
		}
		
		if ($date_r==1) {
			$start_date=date('Y-m-d',strtotime('2000-01-01'));
			$closing_date=date("Y-m-d", strtotime($date_from."-1 day"));
			// pre($start_date);
		}

		if($date_r==1 && $type=='ddd' && $id==''){
			$data['opening_balance']=$this->D_Ledger_model->transactionListDateSearchOpeningBalance($start_date,$closing_date);
			$data['transaction_list']=$this->D_Ledger_model->transactionListDateSearch($date_from,$date_to);
			// pre($data);
			}elseif($date_r==1 && $id!=''){
				$id_type=substr($id,0,3);
					if($id_type=='FIL'){
						$data['opening_balance']=$this->D_Ledger_model->transactionListTypeSearchFileDateFILOpeningBalance($id,$start_date,$closing_date);
						$data['transaction_list']=$this->D_Ledger_model->transactionListTypeSearchFileDateFIL($id,$date_from,$date_to);
						}elseif($id_type=='PME'){
							$data['opening_balance']=$this->D_Ledger_model->transactionListTypeSearchFileDatePMEOpeningBalance($id,$start_date,$closing_date);
							$data['transaction_list']=$this->D_Ledger_model->transactionListTypeSearchFileDatePME($id,$date_from,$date_to);
							}
				}elseif($id!=''){
					$data['transaction_list']=$this->D_Ledger_model->transactionListTypeSearchFile($id);
					// pre('id');
					}
					elseif($file_data['transaction_type']=='Income'){
						$data['opening_balance']=$this->D_Ledger_model->transactionListTypeSearchIncomeOpeningBalance($start_date,$closing_date);
						$data['transaction_list']=$this->D_Ledger_model->transactionListTypeSearchIncome($date_from,$date_to);
						// pre('income');
						}elseif($file_data['transaction_type']=='Expenditure'){
							$data['opening_balance']=$this->D_Ledger_model->transactionListTypeSearchExpenditureOpeningBalance($start_date,$closing_date);
							$data['transaction_list']=$this->D_Ledger_model->transactionListTypeSearchExpenditure($date_from,$date_to);
							// pre('Expenditure');
							}else{
								$data['transaction_list']=$this->D_Ledger_model->transactionList();
								// pre('All');
								}
		$data['file_ledger']=$this->D_Ledger_model->transactionFileMemberSelection();
		$this->load->view('D_ledger/transaction_list.php',$data);
	}

	################################################
	######### start ledger for file only ###########
	################################################

	public function transactionFile(){
		$data=$this->D_Ledger_model->transactionFile();
		// pre($data);
		$this->load->view('D_ledger/transaction_file.php',$data);
	}

	public function transactionFileSortByGroup(){
		$type=$this->input->post('group_type');
		$file_data=$this->D_Ledger_model->transactionFileSortByGroup($type);
		if ($type=='Company') {
			$file_sorted = '<option value="">Select Company File</option>';
			}elseif($type=='Individual'){
				$file_sorted = '<option value="">Select Individual File</option>';
			}else{
				$file_sorted = '<option value="">Select Group File</option>';
			}
		// $file_sorted='';
		foreach ($file_data as $file_d) {
			$file_sorted .= '<option value="'.$file_d->file_id.'">'.$file_d->file_title.'</option>';
		}
		echo json_encode($file_sorted);
	}

	public function transactionFileLedger(){
		$file_data=$this->input->post();
		$file_id=$file_data['file_id'];

		$date=explode(' - ',$file_data['date_range']);

		if($date[0]=='01/01/1970' && $date[1]=='01/01/1970'){
			$date_from=$date[0];
			$date_to=date('Y-m-d');
			$date_r='';
		}else{
			$date_from=$date[0];
			$date_to=$date[1];
			$date_r=1;
		}

		if ($date_r==1) {
			$start_date=date('Y-m-d',strtotime('2000-01-01'));
			$closing_date=date("Y-m-d", strtotime($date_from."-1 day"));
			// pre($start_date);
		}
		// pre($file_data);
		// transactionFileLedgerDate
		if ($date_r==1 && $file_id!='') {
			$data['opening_balance']=$this->D_Ledger_model->transactionFileLedgerDateOpeningBalance($file_id,$start_date,$closing_date);
			$data['transaction_file']=$this->D_Ledger_model->transactionFileLedgerDate($file_id,$date_from,$date_to);
			// pre($data);
			}else{
				$data['transaction_file']=$this->D_Ledger_model->transactionFileLedger($file_id);
				}
		$data['transaction_file_id']=$this->D_Ledger_model->transactionFileSelection();
		$this->load->view('D_ledger/transaction_file.php',$data);
		// pre($data);
	}

	public function transactionCash(){
		$data=$this->D_Ledger_model->transactionCash();
		// pre($data);
		$this->load->view('D_ledger/transaction_cash.php',$data);
	}

	public function transactionDiscount(){
		$data=$this->D_Ledger_model->transactionDiscount();
		// pre($data);
		$this->load->view('D_ledger/transaction_discount.php',$data);
	}
	public function transactionBank(){
		$data['transaction_bank']=$this->D_Ledger_model->transactionBank();
		$this->load->view('D_ledger/transaction_bank.php',$data);

	}
}