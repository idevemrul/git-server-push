<?php
defined('BASEPATH') or exit('No direct script access allowed');
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Am_bank_ledger_controller extends CI_Controller
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
		$this->load->model('Am_bank_ledger_model');
		$this->load->model('Am_ledger_model');
		$this->load->model('Am_feed_data_model');
	}

    //-------------------------------------------------------
	// ===============START CUSTOMER LEDGER VIEW=============
	public function bankLedgerView()
	{
		$form_data = $this->input->post();
        // pre($form_data);
		if (empty($form_data)) {
			isset($form_data['bank_id']) ? $bank_id = $form_data['bank_id'] : '';
			$search_date_start = date("Y-m-d",strtotime("-7 days"));
			$search_date_end = date("Y-m-d");
			$search_date_opening_balance_start=date('Y-m-d', strtotime("1970-01-01"));
			$search_date_opening_balance_end=date('Y-m-d', strtotime("$search_date_start -1 day"));

			$data['searchBetweens'] = [
				'search_date_start' => $search_date_start,
				'search_date_end'	=> $search_date_end
			];
		} else {
			isset($form_data['bank_id']) ? $bank_id = $form_data['bank_id'] : '';

			if (isset($form_data['search_purchase_date_range']) and $form_data['search_purchase_date_range'] != "01/01/1970 - 01/01/1970") {
				$date_range = explode('-', $form_data['search_purchase_date_range']);
				$search_date_start = date("Y-m-d", strtotime(trim($date_range[0])));
				$search_date_end = date("Y-m-d", strtotime(trim($date_range[1])));
				$search_date_opening_balance_start=date('Y-m-d', strtotime("1970-01-01"));
				$search_date_opening_balance_end=date('Y-m-d', strtotime("$search_date_start -1 day"));

				$data['searchBetweens'] = [
					'search_date_start' => $search_date_start,
					'search_date_end'	=> $search_date_end
				];
			}else{
				$search_date_opening_balance_start=date('Y-m-d', strtotime("1970-01-01"));
				$search_date_opening_balance_end=date('Y-m-d', strtotime("1970-01-01"));
			}
		}

		//---start prepare query whare condition-------
			$where = '';
			$whereOpeningBlance='';
			if(isset($bank_id) AND !empty($bank_id)){
				$where .= "btran.bank_id='$bank_id' AND ";
				$whereOpeningBlance .= "btran.bank_id='$bank_id' AND ";
			}
			if(isset($search_date_start) AND ($search_date_start != '1970-01-01')){
				$where .= " btran.bank_tran_cheque_action_date BETWEEN '$search_date_start' AND '$search_date_end' AND ";
				$whereOpeningBlance .= " btran.bank_tran_cheque_action_date BETWEEN '$search_date_opening_balance_start' AND '$search_date_opening_balance_end' AND ";
			} else{
				$whereOpeningBlance .= " btran.bank_tran_cheque_action_date BETWEEN '$search_date_opening_balance_start' AND '$search_date_opening_balance_end' AND ";
			}


            $bankIdLists=$this->Am_feed_data_model->bankList();
            $bankIds=$comma='';
            foreach($bankIdLists as $key=>$bankIdList){
                if($key>0){$comma=',';}
                $bankIds.=$comma.$bankIdList->bank_id;
            }
            // pre($bankIds);
			$where .= "btran.bank_id IN ($bankIds) AND 
                        btran.bank_id=bank.bank_id
                        ";
			$whereOpeningBlance .="btran.bank_id IN ($bankIds) AND 
                                    btran.bank_id=bank.bank_id
                                    ";
		// ---end prepare query------------
		$data['bankLists'] = $this->Am_feed_data_model->bankList();
		$data['openingBalances']=$this->Am_bank_ledger_model->bankOpeningBalance($whereOpeningBlance);
		$data['bankLedgers'] = $this->Am_bank_ledger_model->bankLedgerList($where);
		// pre($data['openingBalances']);
		$this->load->view('am_ledger/bankLedgerView', $data);
	}
   
    // public function customerNameX(){
    //     // $a=10;
    //     $test=$this->Am_feed_data_model->customerNameX(10);
    //     pre($test);
    // }
	// =============END CUSTOMER LEDGER VIEW================
	//------------------------------------------------------



}   //----- end class----------
