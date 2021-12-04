<?php
defined('BASEPATH') or exit('No direct script access allowed');
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Am_ledger_controller extends CI_Controller
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
		$this->load->model('Am_ledger_model');
		$this->load->model('Am_feed_data_model');
	}

	//-------------------------------------------------
	// ===============START JOURNAL REPORT=============
	public function journalView()
	{
		$data['journalsDistinct'] = $this->Am_ledger_model->journalListDistinct();
		$data['journals'] = $this->Am_ledger_model->journalList();
		$this->load->view('am_ledger/journalView', $data);
	}

	public function journalViewSearch()
	{
		$form_data = $this->input->post();
		$supp_id = $form_data['supp_id'];
		if (empty($supp_id)) {
			$this->session->set_flashdata('msg', 'Sorry !! Please try again.');
			redirect('Am_ledger_controller/journalView');
		}
		$data['journalsDistinct'] = $this->Am_ledger_model->journalListDistinct();
		$data['journalsSearched'] = $this->Am_ledger_model->journalListSearched($supp_id);
		$this->load->view('am_ledger/journalView', $data);
	}
	// =============END JOURNAL REPORT ===========
	//-------------------------------------------

	//-------------------------------------------------------
	// ===============START INVOICE LEDGER VIEW=============
	public function invoiceLedgerView()
	{
		$data['invoiceLists'] = $this->Am_ledger_model->invoiceListDistinct();
		$data['invoiceLedgerViews'] = $this->Am_ledger_model->invoiceLedgerView();
		// pre($data['invoiceLists']);
		$this->load->view('am_ledger/invoiceLedgerView', $data);
	}

	public function invoiceLedgerViewSearch()
	{
		$form_data = $this->input->post();

		if (empty($form_data)) {
			isset($form_data['sale_inv_id']) ? $sale_inv_id = $form_data['sale_inv_id'] : '';
			$search_date_start = date("Y-m-d", strtotime("-7 days"));
			$search_date_end = date("Y-m-d");
			$search_date_opening_balance_start = date('Y-m-d', strtotime("1970-01-01"));
			$search_date_opening_balance_end = date('Y-m-d', strtotime("$search_date_start -1 day"));

			$data['searchBetweens'] = [
				'search_date_start' => $search_date_start,
				'search_date_end'	=> $search_date_end
			];
		} else {
			isset($form_data['sale_inv_id']) ? $sale_inv_id = $form_data['sale_inv_id'] : '';

			if (isset($form_data['search_purchase_date_range']) and $form_data['search_purchase_date_range'] != "01/01/1970 - 01/01/1970") {
				$date_range = explode('-', $form_data['search_purchase_date_range']);
				$search_date_start = date("Y-m-d", strtotime(trim($date_range[0])));
				$search_date_end = date("Y-m-d", strtotime(trim($date_range[1])));
				$search_date_opening_balance_start = date('Y-m-d', strtotime("1970-01-01"));
				$search_date_opening_balance_end = date('Y-m-d', strtotime("$search_date_start -1 day"));

				$data['searchBetweens'] = [
					'search_date_start' => $search_date_start,
					'search_date_end'	=> $search_date_end
				];
			} else {
				$search_date_opening_balance_start = date('Y-m-d', strtotime("1970-01-01"));
				$search_date_opening_balance_end = date('Y-m-d', strtotime("1970-01-01"));
			}
		}

		//---start prepare query whare condition-------
		$where = '';
		$whereOpeningBlance = '';
		if (isset($sale_inv_id) and !empty($sale_inv_id)) {
			$where .= "trn.tran_reference_id='$sale_inv_id' AND ";
			$whereOpeningBlance .= "trn.tran_reference_id='$sale_inv_id' AND ";
		}
		if (isset($search_date_start) and ($search_date_start != '1970-01-01')) {
			$where .= " trn.tran_dateE BETWEEN '$search_date_start' AND '$search_date_end' AND ";
			$whereOpeningBlance .= " trn.tran_dateE BETWEEN '$search_date_opening_balance_start' AND '$search_date_opening_balance_end' AND ";
		} else {
			$whereOpeningBlance .= " trn.tran_dateE BETWEEN '$search_date_opening_balance_start' AND '$search_date_opening_balance_end' AND ";
		}

		$where .= "trn.ach_id=ach.ach_id AND 
					trn.acsh_id=acsh.acsh_id AND 
					trn.accsh_id=accsh.accsh_id AND 
					trn.tran_reference = 'sale_invoice' AND 
					trn.acsh_id !=3 AND 
					trn.accsh_id != 12
					";
		$whereOpeningBlance .= "trn.ach_id=ach.ach_id AND 
									trn.acsh_id=acsh.acsh_id AND 
									trn.accsh_id=accsh.accsh_id AND 
									trn.tran_reference = 'sale_invoice' AND 
									trn.acsh_id !=3 AND 
									trn.accsh_id != 12
									";
		// ---end prepare query------------

		// echo $where;
		// pre($whereOpeningBlance);
		$data['invoiceLists'] = $this->Am_ledger_model->invoiceListDistinct();
		$data['openingBalances'] = $this->Am_ledger_model->invoiceOpeningBalance($whereOpeningBlance);
		// pre($data['openingBalances']);
		$data['invoiceLedgerViewsSearch'] = $this->Am_ledger_model->invoiceLedgerViewSearch($where);
		$this->load->view('am_ledger/invoiceLedgerView', $data);
	}
	// =============END INVOICE LEDGER VIEW================
	//------------------------------------------------------



	//-------------------------------------------------------
	// ===============START SUPPLIER LEDGER VIEW=============
	public function supplierLedgerView()
	{
		$form_data = $this->input->post();
		if (empty($form_data)) {
			isset($form_data['supp_id']) ? $supp_id = $form_data['supp_id'] : '';
			$search_date_start = date("Y-m-d", strtotime("-7 days"));
			$search_date_end = date("Y-m-d");
			$search_date_opening_balance_start = date('Y-m-d', strtotime("1970-01-01"));
			$search_date_opening_balance_end = date('Y-m-d', strtotime("$search_date_start -1 day"));

			$data['searchBetweens'] = [
				'search_date_start' => $search_date_start,
				'search_date_end'	=> $search_date_end
			];
		} else {
			isset($form_data['supp_id']) ? $supp_id = $form_data['supp_id'] : '';

			if (isset($form_data['search_purchase_date_range']) and $form_data['search_purchase_date_range'] != "01/01/1970 - 01/01/1970") {
				$date_range = explode('-', $form_data['search_purchase_date_range']);
				$search_date_start = date("Y-m-d", strtotime(trim($date_range[0])));
				$search_date_end = date("Y-m-d", strtotime(trim($date_range[1])));
				$search_date_opening_balance_start = date('Y-m-d', strtotime("1970-01-01"));
				$search_date_opening_balance_end = date('Y-m-d', strtotime("$search_date_start -1 day"));

				$data['searchBetweens'] = [
					'search_date_start' => $search_date_start,
					'search_date_end'	=> $search_date_end
				];
			} else {
				$search_date_opening_balance_start = date('Y-m-d', strtotime("1970-01-01"));
				$search_date_opening_balance_end = date('Y-m-d', strtotime("1970-01-01"));
			}
		}

		//---start prepare query whare condition-------
		$where = '';
		$whereOpeningBlance = '';
		if (isset($supp_id) and !empty($supp_id)) {
			$where .= "trn.tran_for_id='$supp_id' AND ";
			$whereOpeningBlance .= "trn.tran_for_id='$supp_id' AND ";
		}
		if (isset($search_date_start) and ($search_date_start != '1970-01-01')) {
			$where .= " trn.tran_dateE BETWEEN '$search_date_start' AND '$search_date_end' AND ";
			$whereOpeningBlance .= " trn.tran_dateE BETWEEN '$search_date_opening_balance_start' AND '$search_date_opening_balance_end' AND ";
		} else {
			$whereOpeningBlance .= " trn.tran_dateE BETWEEN '$search_date_opening_balance_start' AND '$search_date_opening_balance_end' AND ";
		}

		$where .= "trn.ach_id=ach.ach_id AND 
						trn.acsh_id=acsh.acsh_id AND 
						trn.accsh_id=accsh.accsh_id AND 
						trn.tran_for = 'supplier' AND 
						trn.acsh_id !=7 AND 
						trn.accsh_id != 13
						ORDER BY tran_id ASC
						";
		$whereOpeningBlance .= "trn.ach_id=ach.ach_id AND 
									trn.acsh_id=acsh.acsh_id AND 
									trn.accsh_id=accsh.accsh_id AND 
									trn.tran_for = 'supplier' AND 
									trn.acsh_id !=7 AND 
									trn.accsh_id != 13
									GROUP BY trn.tran_mode";
		// ---end prepare query------------
		$data['supplierLists'] = $this->Am_feed_data_model->supplierList();
		$data['openingBalances'] = $this->Am_ledger_model->supplierOpeningBalance($whereOpeningBlance);
		$data['supplierLedgers'] = $this->Am_ledger_model->supplierLedgerList($where);
		// pre($data['openingBalances']);
		$this->load->view('am_ledger/supplierLedgerView', $data);
	}
	// =============END SUPPLIER LEDGER VIEW================
	//------------------------------------------------------

	//-------------------------------------------------------
	// ===============START CUSTOMER LEDGER VIEW=============
	public function customerLedgerView()
	{
		$form_data = $this->input->post();
		if (empty($form_data)) {
			isset($form_data['cust_id']) ? $cust_id = $form_data['cust_id'] : '';
			$search_date_start = date("Y-m-d", strtotime("-7 days"));
			$search_date_end = date("Y-m-d");
			$search_date_opening_balance_start = date('Y-m-d', strtotime("1970-01-01"));
			$search_date_opening_balance_end = date('Y-m-d', strtotime("$search_date_start -1 day"));

			$data['searchBetweens'] = [
				'search_date_start' => $search_date_start,
				'search_date_end'	=> $search_date_end
			];
		} else {
			isset($form_data['cust_id']) ? $cust_id = $form_data['cust_id'] : '';

			if (isset($form_data['search_purchase_date_range']) and $form_data['search_purchase_date_range'] != "01/01/1970 - 01/01/1970") {
				$date_range = explode('-', $form_data['search_purchase_date_range']);
				$search_date_start = date("Y-m-d", strtotime(trim($date_range[0])));
				$search_date_end = date("Y-m-d", strtotime(trim($date_range[1])));
				$search_date_opening_balance_start = date('Y-m-d', strtotime("1970-01-01"));
				$search_date_opening_balance_end = date('Y-m-d', strtotime("$search_date_start -1 day"));

				$data['searchBetweens'] = [
					'search_date_start' => $search_date_start,
					'search_date_end'	=> $search_date_end
				];
			} else {
				$search_date_opening_balance_start = date('Y-m-d', strtotime("1970-01-01"));
				$search_date_opening_balance_end = date('Y-m-d', strtotime("1970-01-01"));
			}
		}

		//---start prepare query whare condition-------
		$where = '';
		$whereOpeningBlance = '';
		if (isset($cust_id) and !empty($cust_id)) {
			$where .= "trn.tran_for_id='$cust_id' AND ";
			$whereOpeningBlance .= "trn.tran_for_id='$cust_id' AND ";
		}
		if (isset($search_date_start) and ($search_date_start != '1970-01-01')) {
			$where .= " trn.tran_dateE BETWEEN '$search_date_start' AND '$search_date_end' AND ";
			$whereOpeningBlance .= " trn.tran_dateE BETWEEN '$search_date_opening_balance_start' AND '$search_date_opening_balance_end' AND ";
		} else {
			$whereOpeningBlance .= " trn.tran_dateE BETWEEN '$search_date_opening_balance_start' AND '$search_date_opening_balance_end' AND ";
		}

		$where .= "trn.ach_id=ach.ach_id AND 
						trn.acsh_id=acsh.acsh_id AND 
						trn.accsh_id=accsh.accsh_id AND 
						trn.tran_for = 'customer' AND 
						trn.acsh_id !=3 AND 
						trn.accsh_id != 12 AND 
						trn.tran_for_id=cust.cust_id
						ORDER BY tran_dateE ASC
						";
		$whereOpeningBlance .= "trn.ach_id=ach.ach_id AND 
									trn.acsh_id=acsh.acsh_id AND 
									trn.accsh_id=accsh.accsh_id AND 
									trn.tran_for = 'customer' AND 
									trn.acsh_id !=3 AND 
									trn.accsh_id != 12 AND 
									trn.tran_for_id=cust.cust_id
									GROUP BY trn.tran_mode";
		// ---end prepare query------------
		$data['customerLists'] = $this->Am_feed_data_model->customerList();
		$data['openingBalances'] = $this->Am_ledger_model->customerOpeningBalance($whereOpeningBlance);
		$data['customerLedgers'] = $this->Am_ledger_model->customerLedgerList($where);
		// pre($data['customerLists']);
		$this->load->view('am_ledger/customerLedgerView', $data);
	}
	// =============END CUSTOMER LEDGER VIEW================
	//------------------------------------------------------



}   //----- end class----------
