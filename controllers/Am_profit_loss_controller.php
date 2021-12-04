<?php
defined('BASEPATH') or exit('No direct script access allowed');
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Am_profit_loss_controller extends CI_Controller
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
		$this->load->model('Am_profit_loss_model');
		$this->load->model('Am_feed_data_model');
	}

    //-------------------------------------------------------
	// ===============START CUSTOMER LEDGER VIEW=============
    // public function profitLossStatementView(){
    //     echo 'This page is under construction <a href="'.base_url().'">Home</a>';
    // }
	public function profitLossStatementView()
	{
		$form_data = $this->input->post();
        // pre($form_data);
		if (empty($form_data)) {
			isset($form_data['cash_id']) ? $cash_id = $form_data['cash_id'] : '';
			$search_date_start = date("Y-m-d",strtotime("-7 days"));
			$search_date_end = date("Y-m-d");
			$search_date_opening_balance_start=date('Y-m-d', strtotime("1970-01-01"));
			$search_date_opening_balance_end=date('Y-m-d', strtotime("$search_date_start -1 day"));

			$data['searchBetweens'] = [
				'search_date_start' => $search_date_start,
				'search_date_end'	=> $search_date_end
			];
		} else {
			isset($form_data['cash_id']) ? $cash_id = $form_data['cash_id'] : '';

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
		// print_r($form_data);
		print_r($_SESSION);
		// pre($data);
		//---start prepare query whare condition-------
			$where = '';
			$whereOpeningBlance='';
			if(isset($cash_id) AND !empty($cash_id)){
				$where .= "ctran.cash_id='$cash_id' AND ";
				$whereOpeningBlance .= "ctran.cash_id='$cash_id' AND ";
			}
			if(isset($search_date_start) AND ($search_date_start != '1970-01-01')){
				$where .= " tran_dateE BETWEEN '$search_date_start' AND '$search_date_end' AND ";
				$whereOpeningBlance .= " tran_dateE BETWEEN '$search_date_opening_balance_start' AND '$search_date_opening_balance_end' AND ";
			} else{
				$whereOpeningBlance .= " tran_dateE BETWEEN '$search_date_opening_balance_start' AND '$search_date_opening_balance_end' AND ";
			}
            
			$where .= "ach_id=2 AND 
						acsh_id=6 AND 
						accsh_id=5
						";
			$whereOpeningBlance .="ctran.cash_id=1";

            // print_r($data['searchBetweens']);
		// ---end prepare query------------
		// $data['openingBalances']=$this->Am_cash_ledger_model->cashOpeningBalance($whereOpeningBlance);
		// -----sale amount------
		// -----feed data 
		$data['companyLists'] = $this->Am_profit_loss_model->companyList($_SESSION['user_id']);
		// pre($data['companyLists']);
		// -------
		$data['totalSale'] = $this->Am_profit_loss_model->totalSale($where);

		$data['totalCostOfSoldGoods'] = $this->Am_profit_loss_model->totalCostOfSoldGoods($search_date_start,$search_date_end);
		$this->load->view('am_report/profitLossStatementView', $data);
	}
   
    // public function customerNameX(){
    //     // $a=10;
    //     $test=$this->Am_feed_data_model->customerNameX(10);
    //     pre($test);
    // }
	// =============END CUSTOMER LEDGER VIEW================
	//------------------------------------------------------



}   //----- end class----------
