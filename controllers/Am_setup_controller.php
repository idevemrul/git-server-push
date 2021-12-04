<?php
defined('BASEPATH') or exit('No direct script access allowed');
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Am_setup_controller extends CI_Controller
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
		$this->load->model('Am_setup_model');
		$this->load->model('Am_feed_data_model');
	}

	//----------------------------------------------
	// ===============start ACCOUTN START DATE =====
	public function accStartDateView()
	{
		$data['acStartDates'] = $this->Am_setup_model->accStartDateList();
		$data['currencys'] = $this->Am_setup_model->currencyList();
		$data['companys'] = $this->Am_setup_model->companyList();
		// pre($data);
		$this->load->view('am_setup/accStartDateView', $data);
	}

	public function branchsForCompany()
	{
		$com_id = $this->input->post('comId');
		$data['branchs'] = $this->Am_setup_model->branchForCompany($com_id);
		echo json_encode($data);
	}

	public function acStartDateAdd()
	{
		if ($this->form_validation->run('account_add_date_validation')) {
			$form_data = $this->input->post();
			date_default_timezone_set("Asia/Dhaka");
			$form_data['add_date'] = date('Y-m-d');
			$form_data['add_time'] = date('h:i:s');
			$form_data['add_by'] = $this->session->userdata('user_id');
			$form_data['status'] = 0;
			// pre($form_data);
			if ($this->db->insert('am_setup_acc_start_date', $form_data)) {
				$this->session->set_flashdata('msg', 'Business opening date added successfully');
				$this->session->set_flashdata('msg_class', 'text-success');
				redirect('Am_setup_controller/accStartDateView');
			} else {
				$this->session->set_flashdata('msg', 'Sorry ! business opening date add unsuccessfull.');
				$this->session->set_flashdata('msg_class', 'text-danger');
				redirect('Am_setup_controller/accStartDateView');
			}
		} else {
			$this->session->set_flashdata('msg', 'Sorry ! Please tyr again with valid data.');
			$this->session->set_flashdata('msg_class', 'text-danger');
			redirect('Am_setup_controller/accStartDateView');
		}
	}

	public function acStartDateSearch()
	{
		$id = $this->input->post('id');
		if (empty($id)) {
			redirect('Am_setup_controller/accStartDateView');
		}
		$data['acStartDates'] = $this->Am_setup_model->accStartDateList();
		$data['acStartDatesSearched'] = $this->Am_setup_model->accStartDateSearch($id);
		// pre($data);
		$this->load->view('am_setup/accStartDateView', $data);
	}

	public function acStartDateStatusUpdate()
	{
		$id = $this->uri->segment(3);
		if ($this->Am_setup_model->acStartDateStatusUpdate($id)) {
			$this->session->set_flashdata('msg', 'Add date status updated successfully');
			$this->session->set_flashdata('msg_class', 'text-success');
			redirect('Am_setup_controller/accStartDateView');
		} else {
			$this->session->set_flashdata('msg', 'Sorry ! Add date status updated unsuccessfully.');
			$this->session->set_flashdata('msg_class', 'text-danger');
			redirect('Am_setup_controller/accStartDateView');
		}
	}

	// =============END ACCOUTN START DATE=========
	//---------------------------------------------

	//----------------------------------------------
	// ===============start bank account add =====
	public function bankListView()
	{
		$data['companys'] = $this->Am_setup_model->companyList();
		$data['banks'] = $this->Am_setup_model->bankList();
		$this->load->view('am_setup/bankListView.php', $data);
	}
	public function bankAdd()
	{
		$form_data = $this->input->post();
		// pre($form_data);
		if ($this->form_validation->run('bank_validation')) {
			$form_data = $this->input->post();
			$form_data['bank_by'] = $this->session->userdata('user_id');
			date_default_timezone_set("Asia/Dhaka");
			$form_data['bank_date'] = date('Y-m-d');
			$form_data['bank_time'] = date('h:i:s');
			$form_data['bank_status'] = 0;

			// -------data for bank transaction----------
			$opening_balance_bank_transaction_data_in = array(
				'bank_id' => '',
				'bank_tran_reference_id' => '',
				'bank_tran_reference_type' => 'opening balance',
				'bank_tran_for_id' => '',
				'bank_tran_for' => 'opening_balance',
				'bank_tran_method' => 'opening',
				'bank_tran_cheque_no' => '',
				'bank_tran_cheque_date' => '',
				'bank_tran_cheque_action' => 'honored',
				'bank_tran_cheque_action_date' => null,
				'cheque_mode'	=> 'in',
				'bank_cheque_amount' => $form_data['bank_opening_balance'],
				'add_date' => date('Y-m-d'),
				'add_time' => date('h:i:s'),
				'add_by' => $this->session->userdata('user_id'),
				'status' => 1,
			);
			// $opening_balance_bank_transaction_data_out = array(
			// 	'bank_id' => '',
			// 	'bank_tran_reference_id' => '',
			// 	'bank_tran_reference_type' => 'opening balance',
			// 	'bank_tran_for_id' => '',
			// 	'bank_tran_for' => 'opening_balance',
			// 	'bank_tran_method' => 'opening',
			// 	'bank_tran_cheque_no' => '',
			// 	'bank_tran_cheque_date' => '',
			// 	'bank_tran_cheque_action' => 'honored',
			// 	'bank_tran_cheque_action_date' => null,
			// 	'cheque_mode'	=> 'out',
			// 	'bank_cheque_amount' => 0,
			// 	'add_date' => date('Y-m-d'),
			// 	'add_time' => date('h:i:s'),
			// 	'add_by' => $this->session->userdata('user_id'),
			// 	'status' => 1,
			// );

			// $transaction_bank_transaction_ =array(	
			// 	'ach_id' 			=> $asset,
			// 	'acsh_id' 			=> $cash_at_bank,
			// 	'accsh_id' 			=> $main_bank,
			// 	'tran_reference' 	=> 'Opening Balance',
			// 	'tran_reference_id' => $form_data['inte_tran_type'],
			// 	'tran_for' 			=> 'Bank',
			// 	'tran_for_id' 		=> $form_data['inte_tran_receiver'],
			// 	'tran_mode' 		=> 'Dr',
			// 	'tran_amount' 		=> $form_data['inte_tran_amount'],
			// 	'tran_dateE' 		=> $form_data['inte_tran_dateE'],
			// 	'tran_details' 		=> $bank_note,
			// 	'add_date' 	=> date('Y-m-d'),
			// 	'add_time' 	=> date('h:i:s'),
			// 	'add_by' 	=> $this->session->userdata('user_id'),
			// 	'status' 	=> 0
			// );

			$this->db->trans_start();
				$this->db->insert('am_setup_bank_ac', $form_data);
				$opening_balance_bank_transaction_data_in['bank_id']=$opening_balance_bank_transaction_data_out['bank_id']=$this->db->insert_id();
				$this->db->insert('am_bank_transaction', $opening_balance_bank_transaction_data_in);
				// $this->db->insert('am_transaction_cr', $opening_balance_bank_transaction_data_in);
				// $this->db->insert('am_transaction_dr', $opening_balance_bank_transaction_data_in);
				// $this->db->insert('am_bank_transaction', $opening_balance_bank_transaction_data_out);
			$this->db->trans_complete();
			if ($this->db->trans_status() === TRUE) {
				$this->session->set_flashdata('msg', 'Account added successfully');
				$this->session->set_flashdata('msg_class', 'alert-success');
				redirect('Am_setup_controller/bankListView');
			} else {
				$this->session->set_flashdata('msg', 'Sorry ! Account add unsuccessfull.');
				$this->session->set_flashdata('msg_class', 'alert-danger');
				redirect('Am_setup_controller/bankListView');
			}
		} else {
			$this->session->set_flashdata('msg', 'Sorry ! Please tyr again with valid data.');
			$this->session->set_flashdata('msg_class', 'alert-danger');
			redirect('Am_setup_controller/bankListView');
		}
	}

	public function bankSearch()
	{
		$form_data = $this->input->post();
		$bank_id = $form_data['bank_id'];
		// pre($form_data);
		if (empty($bank_id)) {
			redirect('Am_setup_controller/bankListView');
		}
		$data['banks'] = $this->Am_setup_model->bankList();
		$data['banksSearch'] = $this->Am_setup_model->bankSearch($bank_id);
		$this->load->view('am_setup/bankListView.php', $data);
	}

	public function bankStatusUpdate()
	{
		$bank_id = $this->uri->segment(3);
		if ($this->Am_setup_model->bankStatusUpdate($bank_id)) {
			$this->session->set_flashdata('msg', 'bank A/C status updated successfully');
			$this->session->set_flashdata('msg_class', 'text-success');
			redirect('Am_setup_controller/bankListView');
		} else {
			$this->session->set_flashdata('msg', 'Sorry ! bank A/C status updated unsuccessfully.');
			$this->session->set_flashdata('msg_class', 'text-danger');
			redirect('Am_setup_controller/bankListView');
		}
	}
	// =============END bank account add=========
	//---------------------------------------------

	//------------------------------------------
	// ===============start TIME ZONE===========
	public function timeZoneView()
	{
		$data['timeZones'] = $this->Am_setup_model->timeZoneList();
		$this->load->view('am_setup/timeZoneView', $data);
	}

	public function timeZoneAdd()
	{
		if ($this->form_validation->run('timeZone_validation')) {
			$form_data = $this->input->post();
			date_default_timezone_set("Asia/Dhaka");
			$form_data['add_date'] = date('Y-m-d');
			$form_data['add_time'] = date('h:i:s');
			$form_data['add_by'] = $this->session->userdata('user_id');
			$form_data['status'] = 0;

			if ($this->db->insert('am_setup_time_zone', $form_data)) {
				$this->session->set_flashdata('msg', 'Time Zone added successfully');
				$this->session->set_flashdata('msg_class', 'text-success');
				redirect('Am_setup_controller/timeZoneView');
			} else {
				$this->session->set_flashdata('msg', 'Sorry ! Account add unsuccessfull.');
				$this->session->set_flashdata('msg_class', 'text-danger');
				redirect('Am_setup_controller/timeZoneView');
			}
		} else {
			$this->session->set_flashdata('msg', 'Sorry ! Please tyr again with valid data.');
			$this->session->set_flashdata('msg_class', 'text-danger');
			redirect('Am_setup_controller/timeZoneView');
		}
	}

	public function timeZoneSearch()
	{
		$form_data = $this->input->post();
		$time_zone = $form_data['time_zone_id'];
		if (empty($time_zone)) {
			redirect('Am_setup_controller/timeZoneView');
		}
		$data['timeZones'] = $this->Am_setup_model->timeZoneList();
		$data['timeZonesSearched'] = $this->Am_setup_model->timeZoneSearch($time_zone);
		// pre($data);
		$this->load->view('am_setup/timeZoneView', $data);
	}

	public function timeZoneStatusUpdate()
	{
		$time_zone_id = $this->uri->segment(3);
		if ($this->Am_setup_model->timeZoneStatusUpdate($time_zone_id)) {
			$this->session->set_flashdata('msg', 'TimeZone status updated successfully');
			$this->session->set_flashdata('msg_class', 'text-success');
			redirect('Am_setup_controller/timeZoneView');
		} else {
			$this->session->set_flashdata('msg', 'Sorry ! Currency status updated unsuccessfully.');
			$this->session->set_flashdata('msg_class', 'text-danger');
			redirect('Am_setup_controller/timeZoneView');
		}
	}
	// =============END TIME ZONE================
	//-------------------------------------------

	//-------------------------------------------------
	// ===============START CURRENCY SETUP=============
	public function currencyView()
	{
		$data['currencys'] = $this->Am_setup_model->currencyList();
		$this->load->view('am_setup/currencyView', $data);
	}

	public function currencyAdd()
	{
		if ($this->form_validation->run('currency_validation')) {
			$form_data = $this->input->post();
			date_default_timezone_set("Asia/Dhaka");
			$form_data['add_date'] = date('Y-m-d');
			$form_data['add_time'] = date('h:i:s');
			$form_data['add_by'] = $this->session->userdata('user_id');
			$form_data['status'] = 0;

			if ($this->db->insert('am_setup_currencys', $form_data)) {
				$this->session->set_flashdata('msg', 'Time Zone added successfully');
				$this->session->set_flashdata('msg_class', 'text-success');
				redirect('Am_setup_controller/currencyView');
			} else {
				$this->session->set_flashdata('msg', 'Sorry ! Account add unsuccessfull.');
				$this->session->set_flashdata('msg_class', 'text-danger');
				redirect('Am_setup_controller/currencyView');
			}
		} else {
			$this->session->set_flashdata('msg', 'Sorry ! Please tyr again with valid data.');
			$this->session->set_flashdata('msg_class', 'text-danger');
			redirect('Am_setup_controller/currencyView');
		}
	}

	public function currencySearch()
	{
		$form_data = $this->input->post();
		// pre($form_data);
		$currency_id = $form_data['currency_id'];
		if (empty($currency_id)) {
			$this->session->set_flashdata('msg', 'Sorry ! Please try again.');
			redirect('Am_setup_controller/currencyView');
		}
		$data['currencys'] = $this->Am_setup_model->currencyList();
		$data['currencysSearched'] = $this->Am_setup_model->currencySearch($currency_id);
		// pre($data);
		$this->load->view('am_setup/currencyView', $data);
	}

	public function currencyStatusUpdate()
	{
		$currency_id = $this->uri->segment(3);
		// pre($currency_id);
		if ($this->Am_setup_model->currencyStatusUpdate($currency_id)) {
			$this->session->set_flashdata('msg', 'Currency status updated successfully');
			$this->session->set_flashdata('msg_class', 'text-success');
			redirect('Am_setup_controller/currencyView');
		} else {
			$this->session->set_flashdata('msg', 'Sorry ! Currency status updated unsuccessfull.');
			$this->session->set_flashdata('msg_class', 'text-danger');
			redirect('Am_setup_controller/currencyView');
		}
	}
	// =============END CURRENCY SETUP===========
	//-------------------------------------------


	//-----------------------------------------
	// ==========START COMPANY SETUP===========

	public function companyView()
	{
		$data['companys'] = $this->Am_setup_model->companyList();
		$data['currencys'] = $this->Am_setup_model->currencyList();
		$this->load->view('am_setup/companyView', $data);
	}

	public function companyAdd()
	{
		if ($this->form_validation->run('company_validation')) {
			$form_data = $this->input->post();
			date_default_timezone_set("Asia/Dhaka");
			$form_data['add_date'] = date('Y-m-d');
			$form_data['add_time'] = date('h:i:s');
			$form_data['add_by'] = $this->session->userdata('user_id');
			$form_data['status'] = 0;

			if ($this->db->insert('am_setup_company', $form_data)) {
				$this->session->set_flashdata('msg', 'Company added successfully');
				$this->session->set_flashdata('msg_class', 'text-success');
				redirect('Am_setup_controller/companyView');
			} else {
				$this->session->set_flashdata('msg', 'Sorry ! Company add unsuccessfull.');
				$this->session->set_flashdata('msg_class', 'text-danger');
				redirect('Am_setup_controller/companyView');
			}
		} else {
			$this->session->set_flashdata('msg', 'Sorry ! Please tyr again with valid data.');
			$this->session->set_flashdata('msg_class', 'text-danger');
			redirect('Am_setup_controller/companyView');
		}
	}

	public function companyAddAjax()
	{
		$form_data = $this->input->post();
		// pre($form_data);
		if ($this->form_validation->run('company_validation')) {
			$form_data = $this->input->post();
			date_default_timezone_set("Asia/Dhaka");
			$form_data['add_date'] = date('Y-m-d');
			$form_data['add_time'] = date('h:i:s');
			$form_data['add_by'] = $this->session->userdata('user_id');
			$form_data['status'] = 0;

			if ($this->db->insert('am_setup_company', $form_data)) {
				$message['msg_title'] = "Company added successfully";
				$message['msg_class'] = "text-success";

				$message['com_id'] = $this->db->insert_id();
				$message['com_name'] = $form_data['com_name'];
				$message['com_licence'] = $form_data['com_licence'];
			} else {
				$message['msg_title'] = "Company add failed";
				$message['msg_class'] = "text-danger";
			}
		} else {
			$message['msg_title'] = "Try with valid data";
			$message['msg_class'] = "text-danger";
		}
		echo json_encode($message);
	}

	public function companySearch()
	{
		$form_data = $this->input->post();
		$com_id = $form_data['com_id'];
		if (empty($com_id)) {
			$this->session->set_flashdata('msg', 'Sorry ! Please try again.');
			redirect('Am_setup_controller/companyView');
		}
		$data['companys'] = $this->Am_setup_model->companyList();
		$data['companysSearched'] = $this->Am_setup_model->companySearch($com_id);
		// pre($data);
		$this->load->view('am_setup/companyView', $data);
	}

	public function companyStatusUpdate()
	{
		$com_id = $this->uri->segment(3);
		if ($this->Am_setup_model->companyStatusUpdate($com_id)) {
			$this->session->set_flashdata('msg', 'Company status updated successfully');
			$this->session->set_flashdata('msg_class', 'text-success');
			redirect('Am_setup_controller/companyView');
		} else {
			$this->session->set_flashdata('msg', 'Sorry ! Company status updated unsuccessfull.');
			$this->session->set_flashdata('msg_class', 'text-danger');
			redirect('Am_setup_controller/companyView');
		}
	}
	// ==========END COMPANY SETUP===========
	// --------------------------------------

	// =====START EXP-PARTY SETUP============
	// --------------------------------------
	public function expPartyView()
	{
		$form_data = $this->input->post();
		// pre($form_data);
		if (empty($form_data)) {
			isset($form_data['party_id']) ? $party_id = $form_data['party_id'] : '';
			$search_date_start = date("Y-m-d", strtotime("-7 days"));
			$search_date_end = date("Y-m-d");

			$data['searchBetweens'] = [
				'search_date_start' => $search_date_start,
				'search_date_end'	=> $search_date_end
			];
		} else {
			isset($form_data['party_id']) ? $party_id = $form_data['party_id'] : '';

			if (isset($form_data['search_purchase_date_range']) and $form_data['search_purchase_date_range'] != "01/01/1970 - 01/01/1970") {
				$date_range = explode('-', $form_data['search_purchase_date_range']);
				$search_date_start = date("Y-m-d", strtotime(trim($date_range[0])));
				$search_date_end = date("Y-m-d", strtotime(trim($date_range[1])));

				$data['searchBetweens'] = [
					'search_date_start' => $search_date_start,
					'search_date_end'	=> $search_date_end
				];
			}
		}

		//---start prepare query whare condition-------
		$where = '';
		if (isset($party_id) and !empty($party_id)) {
			$where .= 'party.party_id=' . $party_id . ' AND ';
		}
		if (isset($search_date_start) and ($search_date_start != '1970-01-01')) {
			$where .= " party.add_date BETWEEN '$search_date_start' AND '$search_date_end' AND ";
		}

		$where .= "party.status IN (0,1) 
					";

		$data['partyLists'] = $this->Am_feed_data_model->partyList();
		$data['partys'] = $this->Am_setup_model->partyRecordList($where);
		$this->load->view('am_setup/expPartyView', $data);
	}

	public function expPartyAdd()
	{
		$form_data = $this->input->post();
		// pre($form_data);
		if ($this->form_validation->run('exp_party_validation')) {
			date_default_timezone_set("Asia/Dhaka");
			$form_data['add_date'] = date('Y-m-d');
			$form_data['add_time'] = date('h:i:s');
			$form_data['add_by'] = $this->session->userdata('user_id');
			$form_data['status'] = 0;


			$this->db->trans_start();
			$this->db->insert('am_setup_partys', $form_data);
			$this->db->trans_complete();

			// ------transaction status check and action----
			if ($this->db->trans_status() === TRUE) {
				$this->session->set_flashdata('msg', 'Party added successfully');
				$this->session->set_flashdata('msg_class', 'text-success');
				redirect('Am_setup_controller/expPartyView');
			} else {
				$this->session->set_flashdata('msg', 'Sorry ! Party add unsuccessfull !!');
				$this->session->set_flashdata('msg_class', 'text-danger');
				redirect('Am_setup_controller/expPartyView');
			}
		} else {
			$this->session->set_flashdata('msg', 'Sorry ! Party add faild.');
			$this->session->set_flashdata('msg_class', 'text-danger');
			redirect('Am_setup_controller/expPartyView');
		}
	}
	// ------------------------------------
	// =====END EXP-PARTY SETUP============

	//-----------------------------------------
	// ==========START BRANCH SETUP===========
	public function branchView()
	{
		$data['companys'] = $this->Am_setup_model->companyList();
		$data['branchs'] = $this->Am_setup_model->branchList();
		$this->load->view('am_setup/branchView', $data);
	}

	public function branchAdd()
	{
		if ($this->form_validation->run('branch_validation')) {
			$form_data = $this->input->post();
			date_default_timezone_set("Asia/Dhaka");
			$form_data['add_date'] = date('Y-m-d');
			$form_data['add_time'] = date('h:i:s');
			$form_data['add_by'] = $this->session->userdata('user_id');
			$form_data['status'] = 0;

			if ($this->db->insert('am_setup_branch', $form_data)) {
				$this->session->set_flashdata('msg', 'Company added successfully');
				$this->session->set_flashdata('msg_class', 'text-success');
				redirect('Am_setup_controller/branchView');
			} else {
				$this->session->set_flashdata('msg', 'Sorry ! Company add unsuccessfull.');
				$this->session->set_flashdata('msg_class', 'text-danger');
				redirect('Am_setup_controller/branchView');
			}
		} else {
			$this->session->set_flashdata('msg', 'Sorry ! Please tyr again with valid data. ddd');
			$this->session->set_flashdata('msg_class', 'text-danger');
			redirect('Am_setup_controller/branchView');
		}
	}


	public function branchAddAjax()
	{
		$form_data = $this->input->post();
		// pre($form_data);
		if ($this->form_validation->run('branch_validation')) {
			$form_data = $this->input->post();
			date_default_timezone_set("Asia/Dhaka");
			$form_data['add_date'] = date('Y-m-d');
			$form_data['add_time'] = date('h:i:s');
			$form_data['add_by'] = $this->session->userdata('user_id');
			$form_data['status'] = 0;

			if ($this->db->insert('am_setup_branch', $form_data)) {
				$message['msg_title'] = "Branch added successfully";
				$message['msg_class'] = "text-success";

				$message['branch_id'] = $this->db->insert_id();
				$message['branch_name'] = $form_data['branch_name'];
				$message['branch_code'] = $form_data['branch_code'];
			} else {
				$message['msg_title'] = "Branch add failed";
				$message['msg_class'] = "text-danger";
			}
		} else {
			$message['msg_title'] = "Try with valid data";
			$message['msg_class'] = "text-danger";
		}
		echo json_encode($message);
	}

	public function branchSearch()
	{
		$form_data = $this->input->post();
		$branch_id = $form_data['branch_id'];
		if (empty($branch_id)) {
			$this->session->set_flashdata('msg', 'Sorry ! Please try again.');
			$this->session->set_flashdata('msg_class', 'text-danger');
			redirect('Am_setup_controller/branchView');
		}
		$data['branchs'] = $this->Am_setup_model->branchList();
		$data['branchsSearched'] = $this->Am_setup_model->branchSearch($branch_id);
		// pre($data);
		$this->load->view('am_setup/branchView', $data);
	}

	public function branchStatusUpdate()
	{
		$branch_id = $this->uri->segment(3);
		if ($this->Am_setup_model->branchStatusUpdate($branch_id)) {
			$this->session->set_flashdata('msg', 'Company status updated successfully');
			$this->session->set_flashdata('msg_class', 'text-success');
			redirect('Am_setup_controller/branchView');
		} else {
			$this->session->set_flashdata('msg', 'Sorry ! Company status updated unsuccessfull.');
			$this->session->set_flashdata('msg_class', 'text-danger');
			redirect('Am_setup_controller/branchView');
		}
	}

	// ==========END BRANCH SETUP===========
	// --------------------------------------


	//-------------------------------------------
	// ==========START USER ROLE SETUP===========

	public function userView()
	{
		$data['companys'] = $this->Am_setup_model->companyList();
		$data['users'] = $this->Am_setup_model->userList();
		$data['currencys'] = $this->Am_setup_model->currencyList();
		$this->load->view('am_setup/userView', $data);
	}

	public function userAdd()
	{
		if ($this->form_validation->run('user_validation')) {
			$form_data = $this->input->post();
			$form_data['user_role'] = 'No Role';
			date_default_timezone_set("Asia/Dhaka");
			$form_data['add_date'] = date('Y-m-d');
			$form_data['add_time'] = date('h:i:s');
			$form_data['add_by'] = $this->session->userdata('user_id');
			$form_data['status'] = 0;

			if ($this->db->insert('am_setup_users', $form_data)) {
				$this->session->set_flashdata('msg', 'Company added successfully');
				$this->session->set_flashdata('msg_class', 'text-success');
				redirect('Am_setup_controller/userView');
			} else {
				$this->session->set_flashdata('msg', 'Sorry ! Company add unsuccessfull.');
				$this->session->set_flashdata('msg_class', 'text-danger');
				redirect('Am_setup_controller/userView');
			}
		} else {
			$this->session->set_flashdata('msg', 'Sorry ! Please tyr again with valid data. ddd');
			$this->session->set_flashdata('msg_class', 'text-danger');
			redirect('Am_setup_controller/userView');
		}
	}

	public function userSearch()
	{
		$form_data = $this->input->post();
		$user_id = $form_data['user_id'];
		if (empty($user_id)) {
			$this->session->set_flashdata('msg', 'Sorry ! Please try again.');
			$this->session->set_flashdata('msg_class', 'text-danger');
			redirect('Am_setup_controller/userView');
		}
		$data['users'] = $this->Am_setup_model->userList();
		$data['usersSearched'] = $this->Am_setup_model->userSearch($user_id);
		// pre($data);
		$this->load->view('am_setup/userView', $data);
	}

	public function userStatusUpdate()
	{
		$user_id = $this->uri->segment(3);
		if ($this->Am_setup_model->userStatusUpdate($user_id)) {
			$this->session->set_flashdata('msg', 'User status updated successfully');
			$this->session->set_flashdata('msg_class', 'text-success');
			redirect('Am_setup_controller/userView');
		} else {
			$this->session->set_flashdata('msg', 'Sorry ! User status updated unsuccessfull.');
			$this->session->set_flashdata('msg_class', 'text-danger');
			redirect('Am_setup_controller/userView');
		}
	}

	public function userRoleUpdate()
	{
		if ($this->form_validation->run('userRole_validation')) {
			$user_id = $this->input->post('user_id');
			$data['user_role'] = $this->input->post('user_role');
			// pre($form_data);

			$query = $this->db->where('user_id', $user_id)
				->update('am_setup_users', $data);
			if ($query) {
				$this->session->set_flashdata('msg', 'User status updated successfully');
				$this->session->set_flashdata('msg_class', 'text-success');
				redirect('Am_setup_controller/userView');
			} else {
				$this->session->set_flashdata('msg', 'Sorry ! User status updated unsuccessfull.');
				$this->session->set_flashdata('msg_class', 'text-danger');
				redirect('Am_setup_controller/userView');
			}
		}
	}

	// ==========END USER ROLE SETUP=============
	// ------------------------------------------

	//-------------------------------------------------
	// ===============START CATEGORY SETUP=============
	public function categoryView()
	{
		$data['categorys'] = $this->Am_setup_model->categoryList();
		$this->load->view('am_setup/categoryView', $data);
	}

	public function categoryAdd()
	{
		// $form_data=$this->input->post();
		// pre($form_data);
		if ($this->form_validation->run('category_validation')) {
			$form_data = $this->input->post();
			date_default_timezone_set("Asia/Dhaka");
			$form_data['add_date'] = date('Y-m-d');
			$form_data['add_time'] = date('h:i:s');
			$form_data['add_by'] = $this->session->userdata('user_id');
			$form_data['status'] = 0;



			if ($this->db->insert('am_setup_categories', $form_data)) {
				$this->session->set_flashdata('msg', 'Category added successfully');
				$this->session->set_flashdata('msg_class', 'text-success');
				redirect('Am_setup_controller/categoryView');
			} else {
				$this->session->set_flashdata('msg', 'Sorry ! Account add unsuccessfull.');
				$this->session->set_flashdata('msg_class', 'text-danger');
				redirect('Am_setup_controller/categoryView');
			}
		} else {
			$this->session->set_flashdata('msg', 'Sorry ! Please tyr again with valid data.');
			$this->session->set_flashdata('msg_class', 'text-danger');
			redirect('Am_setup_controller/categoryView');
		}
	}

	public function categorySearch()
	{
		$form_data = $this->input->post();
		// pre($form_data);
		$cat_id = $form_data['cat_id'];
		if (empty($cat_id)) {
			$this->session->set_flashdata('msg', 'Sorry !! Please try again.');
			redirect('Am_setup_controller/categoryView');
		}
		$data['categorys'] = $this->Am_setup_model->categoryList();
		$data['categorysSearched'] = $this->Am_setup_model->categorySearch($cat_id);
		// pre($data);
		$this->load->view('am_setup/categoryView', $data);
	}

	public function categoryStatusUpdate()
	{
		$cat_id = $this->uri->segment(3);
		if ($this->Am_setup_model->categoryStatusUpdate($cat_id)) {
			$this->session->set_flashdata('msg', 'Category status updated successfully');
			$this->session->set_flashdata('msg_class', 'text-success');
			redirect('Am_setup_controller/categoryView');
		} else {
			$this->session->set_flashdata('msg', 'Sorry ! Category status updated unsuccessfull.');
			$this->session->set_flashdata('msg_class', 'text-danger');
			redirect('Am_setup_controller/categoryView');
		}
	}
	// =============END CATEGORY SETUP===========
	//-------------------------------------------

	//-------------------------------------------------
	// ===============START COMMISSION SETUP=============
	public function commissionView()
	{
		$data['commissions'] = $this->Am_setup_model->commissionList();
		// pre($data);
		$this->load->view('am_setup/commissionView', $data);
	}

	public function commissionAdd()
	{
		// $form_data=$this->input->post();
		// pre($form_data);
		if ($this->form_validation->run('commission_validation')) {
			$form_data = $this->input->post();
			date_default_timezone_set("Asia/Dhaka");
			$form_data['add_date'] = date('Y-m-d');
			$form_data['add_time'] = date('h:i:s');
			$form_data['add_by'] = $this->session->userdata('user_id');
			$form_data['status'] = 0;



			if ($this->db->insert('am_setup_commissions', $form_data)) {
				$this->session->set_flashdata('msg', 'Comission added successfully');
				$this->session->set_flashdata('msg_class', 'text-success');
				redirect('Am_setup_controller/commissionView');
			} else {
				$this->session->set_flashdata('msg', 'Sorry ! Commission add unsuccessfull.');
				$this->session->set_flashdata('msg_class', 'text-danger');
				redirect('Am_setup_controller/commissionView');
			}
		} else {
			$this->session->set_flashdata('msg', 'Sorry ! Please tyr again with valid data.');
			$this->session->set_flashdata('msg_class', 'text-danger');
			redirect('Am_setup_controller/commissionView');
		}
	}

	public function commissionSearch()
	{
		$form_data = $this->input->post();
		// pre($form_data);
		$comm_id = $form_data['comm_id'];
		if (empty($comm_id)) {
			$this->session->set_flashdata('msg', 'Sorry !! Please try again.');
			redirect('Am_setup_controller/commissionView');
		}
		$data['commissions'] = $this->Am_setup_model->commissionList();
		$data['commissionsSearched'] = $this->Am_setup_model->commissionSearch($comm_id);
		// pre($data);
		$this->load->view('am_setup/commissionView', $data);
	}

	public function commissionStatusUpdate()
	{
		$comm_id = $this->uri->segment(3);
		if ($this->Am_setup_model->commissionStatusUpdate($comm_id)) {
			$this->session->set_flashdata('msg', 'Commission status updated successfully');
			$this->session->set_flashdata('msg_class', 'text-success');
			redirect('Am_setup_controller/commissionView');
		} else {
			$this->session->set_flashdata('msg', 'Sorry ! Commission status updated unsuccessfull.');
			$this->session->set_flashdata('msg_class', 'text-danger');
			redirect('Am_setup_controller/commissionView');
		}
	}
	// =============END COMMISSION SETUP===========
	//-------------------------------------------

	//-------------------------------------------------
	// ===============START CUSTOMER SETUP=============
	public function customerView()
	{
		$data['customers'] = $this->Am_setup_model->customerList();
		// pre($data);
		$this->load->view('am_setup/customerView', $data);
	}

	public function customerAdd()
	{
		// $form_data=$this->input->post();
		// pre($form_data);
		if ($this->form_validation->run('customer_validation')) {
			$form_data = $this->input->post();
			date_default_timezone_set("Asia/Dhaka");
			$form_data['add_date'] = date('Y-m-d');
			$form_data['add_time'] = date('h:i:s');
			$form_data['add_by'] = $this->session->userdata('user_id');
			$form_data['status'] = 0;


			if ($this->db->insert('am_setup_customers', $form_data)) {
				$this->session->set_flashdata('msg', 'Category added successfully');
				$this->session->set_flashdata('msg_class', 'text-success');
				redirect('Am_setup_controller/customerView');
			} else {
				$this->session->set_flashdata('msg', 'Sorry ! Account add unsuccessfull.');
				$this->session->set_flashdata('msg_class', 'text-danger');
				redirect('Am_setup_controller/customerView');
			}
		} else {
			$this->session->set_flashdata('msg', 'Sorry ! Please tyr again with valid data.');
			$this->session->set_flashdata('msg_class', 'text-danger');
			redirect('Am_setup_controller/customerView');
		}
	}
	public function customerAddAjax()
	{
		$form_data = $this->input->post();
		// pre($form_data);
		if ($this->form_validation->run('customer_validation')) {
			$form_data = $this->input->post();
			date_default_timezone_set("Asia/Dhaka");
			$form_data['add_date'] = date('Y-m-d');
			$form_data['add_time'] = date('h:i:s');
			$form_data['add_by'] = $this->session->userdata('user_id');
			$form_data['status'] = 0;

			if ($this->db->insert('am_setup_customers', $form_data)) {
				$message['msg_title'] = "Customer added successfully";
				$message['msg_class'] = "text-success";

				$message['cust_id'] = $this->db->insert_id();
				$message['cust_name'] = $form_data['cust_name'];
				$message['cust_mobile'] = $form_data['cust_mobile'];
			} else {
				$message['msg_title'] = "Customer add failed";
				$message['msg_class'] = "text-danger";
			}
		} else {
			$message['msg_title'] = "Try with valid data";
			$message['msg_class'] = "text-danger";
		}
		echo json_encode($message);
	}

	public function customerSearch()
	{
		$form_data = $this->input->post();
		// pre($form_data);
		$cust_id = $form_data['cust_id'];
		if (empty($cust_id)) {
			$this->session->set_flashdata('msg', 'Sorry !! Please try again.');
			redirect('Am_setup_controller/customerView');
		}
		$data['customers'] = $this->Am_setup_model->customerList();
		$data['customersSearched'] = $this->Am_setup_model->customerSearch($cust_id);
		// pre($data);
		$this->load->view('am_setup/customerView', $data);
	}

	public function customerStatusUpdate()
	{
		$cust_id = $this->uri->segment(3);
		if ($this->Am_setup_model->customerStatusUpdate($cust_id)) {
			$this->session->set_flashdata('msg', 'Customer status updated successfully');
			$this->session->set_flashdata('msg_class', 'text-success');
			redirect('Am_setup_controller/customerView');
		} else {
			$this->session->set_flashdata('msg', 'Sorry ! Customer status updated unsuccessfull.');
			$this->session->set_flashdata('msg_class', 'text-danger');
			redirect('Am_setup_controller/customerView');
		}
	}
	// =============END CUSTOMER SETUP===========
	//-------------------------------------------

	//-------------------------------------------------
	// ===============START EMPLOYEE SETUP=============
	public function employeeView()
	{
		$data['employees'] = $this->Am_setup_model->employeeList();
		// pre($data);
		$this->load->view('am_setup/employeeView', $data);
	}

	public function employeeAdd()
	{
		// $form_data=$this->input->post();
		// pre($form_data);
		if ($this->form_validation->run('employee_validation')) {
			$form_data = $this->input->post();
			date_default_timezone_set("Asia/Dhaka");
			$form_data['add_date'] = date('Y-m-d');
			$form_data['add_time'] = date('h:i:s');
			$form_data['add_by'] = $this->session->userdata('user_id');
			$form_data['status'] = 0;

			if ($this->db->insert('am_setup_employees', $form_data)) {
				$this->session->set_flashdata('msg', 'Category added successfully');
				$this->session->set_flashdata('msg_class', 'text-success');
				redirect('Am_setup_controller/employeeView');
			} else {
				$this->session->set_flashdata('msg', 'Sorry ! Account add unsuccessfull.');
				$this->session->set_flashdata('msg_class', 'text-danger');
				redirect('Am_setup_controller/employeeView');
			}
		} else {
			$this->session->set_flashdata('msg', 'Sorry ! Please tyr again with valid data.');
			$this->session->set_flashdata('msg_class', 'text-danger');
			redirect('Am_setup_controller/employeeView');
		}
	}

	public function employeeSearch()
	{
		$form_data = $this->input->post();
		// pre($form_data);
		$emp_id = $form_data['emp_id'];
		if (empty($emp_id)) {
			$this->session->set_flashdata('msg', 'Sorry !! Please try again.');
			redirect('Am_setup_controller/supplierView');
		}
		$data['employees'] = $this->Am_setup_model->employeeList();
		$data['employeesSearched'] = $this->Am_setup_model->employeeSearch($emp_id);
		// pre($data);
		$this->load->view('am_setup/employeeView', $data);
	}

	public function employeeStatusUpdate()
	{
		$emp_id = $this->uri->segment(3);
		if ($this->Am_setup_model->employeeStatusUpdate($emp_id)) {
			$this->session->set_flashdata('msg', 'employee status updated successfully');
			$this->session->set_flashdata('msg_class', 'text-success');
			redirect('Am_setup_controller/employeeView');
		} else {
			$this->session->set_flashdata('msg', 'Sorry ! employee status updated unsuccessfull.');
			$this->session->set_flashdata('msg_class', 'text-danger');
			redirect('Am_setup_controller/employeeView');
		}
	}
	// =============END EMPLOYEE SETUP===========
	//-------------------------------------------


	//-------------------------------------------------
	// ===============START INTEREST SETUP=============
	public function interestView()
	{
		$data['interests'] = $this->Am_setup_model->interestList();
		$data['catLists'] = $this->Am_setup_model->categoryList();
		$this->load->view('am_setup/interestView', $data);
	}

	public function interestAdd()
	{
		// $form_data=$this->input->post();
		// pre($form_data);
		if ($this->form_validation->run('interest_validation')) {
			$form_data = $this->input->post();
			date_default_timezone_set("Asia/Dhaka");
			$form_data['add_date'] = date('Y-m-d');
			$form_data['add_time'] = date('h:i:s');
			$form_data['add_by'] = $this->session->userdata('user_id');
			$form_data['status'] = 0;

			if ($this->db->insert('am_setup_interests', $form_data)) {
				$this->session->set_flashdata('msg', 'Interest added successfully');
				$this->session->set_flashdata('msg_class', 'text-success');
				redirect('Am_setup_controller/interestView');
			} else {
				$this->session->set_flashdata('msg', 'Sorry ! Interest add unsuccessfull.');
				$this->session->set_flashdata('msg_class', 'text-danger');
				redirect('Am_setup_controller/interestView');
			}
		} else {
			$this->session->set_flashdata('msg', 'Sorry ! Please tyr again with valid data.');
			$this->session->set_flashdata('msg_class', 'text-danger');
			redirect('Am_setup_controller/interestView');
		}
	}

	public function interestSearch()
	{
		$form_data = $this->input->post();
		// pre($form_data);
		$cat_id = $form_data['cat_id'];
		if (empty($cat_id)) {
			$this->session->set_flashdata('msg', 'Sorry !! Please try again.');
			redirect('Am_setup_controller/interestView');
		}
		$data['interests'] = $this->Am_setup_model->interestList();
		$data['catLists'] = $this->Am_setup_model->categoryList();
		$data['interestsSearched'] = $this->Am_setup_model->interestSearch($cat_id);
		// pre($data);
		$this->load->view('am_setup/interestView', $data);
	}

	public function interestStatusUpdate()
	{
		$interest_id = $this->uri->segment(3);
		$cat_id = $this->uri->segment(4);
		if ($this->Am_setup_model->interestStatusUpdate($interest_id,$cat_id)) {
			$this->session->set_flashdata('msg', 'Interest status updated successfully');
			$this->session->set_flashdata('msg_class', 'text-success');
			redirect('Am_setup_controller/interestView');
		} else {
			$this->session->set_flashdata('msg', 'Sorry ! Interest status updated unsuccessfull.');
			$this->session->set_flashdata('msg_class', 'text-danger');
			redirect('Am_setup_controller/interestView');
		}
	}
	// =============END INTEREST SETUP===========
	//-------------------------------------------

	//-------------------------------------------------
	// ===============START INVOICE SETUP=============
	public function invoiceView()
	{
		$data['invoices'] = $this->Am_setup_model->invoiceList();
		$data['companys'] = $this->Am_setup_model->invCompanyList();
		// pre($data);
		$this->load->view('am_setup/invoiceView', $data);
	}

	// -----start ajax data return-------
	public function invCompanyBranch()
	{
		$form_data = $this->input->post();
		$com_id = $form_data['comId'];
		$data['branchs'] = $this->Am_setup_model->invCompanyBranch($com_id);
		echo json_encode($data);
	}
	// -----end ajax data return---------

	// ------start image upload to file and form data to table----

	public function invoiceAdd()
	{
		$form_data = $this->input->post();
		$this->load->library('upload'); //-- upload furles set up--

		// ---Image upload to directory and make image name for database insert------
		$ImageCount = count($_FILES['header_img']['name']);

		for ($i = 0; $i < $ImageCount; $i++) {
			$_FILES['file']['name']       = $_FILES['header_img']['name'][$i];
			$_FILES['file']['type']       = $_FILES['header_img']['type'][$i];
			$_FILES['file']['tmp_name']   = $_FILES['header_img']['tmp_name'][$i];
			$_FILES['file']['error']      = $_FILES['header_img']['error'][$i];
			$_FILES['file']['size']       = $_FILES['header_img']['size'][$i];

			// File upload configuration
			$config = [
				'upload_path'	=> './asset/img/am_invoice',
				'allowed_types'	=> 'jpg|jpeg|png|gif|JPG|JPEG|PNG|GIF',
				'max_size'		=> '300000',
				'max_width'		=> '5024',
				'max_height'	=> '3068',
				'overwrite' 	=> FALSE,
			];

			// Load and initialize upload library
			$this->load->library('upload', $config);
			$this->upload->initialize($config);

			// Upload file to server
			if ($this->upload->do_upload('file')) {
				//--- collect Uploaded file data---------
				$imageData = $this->upload->data();
				$uploadImgData[$i]['image_name'] = $imageData['file_name'];
			}
		}
		// ----submit data to table as record----------
		if ($this->form_validation->run('invoice_setup_validation') && !empty($uploadImgData)) {
			$form_data['header_img']	= "asset/img/am_invoice/" . $uploadImgData[0]['image_name'];
			$form_data['body_img']		= "asset/img/am_invoice/" . $uploadImgData[1]['image_name'];
			$form_data['footer_img']	= "asset/img/am_invoice/" . $uploadImgData[2]['image_name'];
			// ----default value-----------
			$form_data['add_date'] = date('Y-m-d');
			$form_data['add_time'] = date('h:i:s');
			$form_data['add_by'] = $this->session->userdata('user_id');
			$form_data['status'] = 0;

			// Insert files data into the database
			if ($this->db->insert('am_setup_invoices', $form_data)) {
				$this->session->set_flashdata('msg', 'Invoice added successfully');
				$this->session->set_flashdata('msg_class', 'text-success');
				redirect('Am_setup_controller/invoiceView');
			} else {
				$this->session->set_flashdata('msg', 'Sorry ! Invoice add unsuccessfull.');
				$this->session->set_flashdata('msg_class', 'text-danger');
				redirect('Am_setup_controller/invoiceView');
			}
		} else {
			$this->session->set_flashdata('msg', 'Sorry ! Please tyr again with valid data.');
			$this->session->set_flashdata('msg_class', 'text-danger');
			redirect('Am_setup_controller/invoiceView');
		}
	}

	public function invoiceUpdate()
	{
		$form_data = $this->input->post();
		$invoice_id = $form_data['invoice_id'];

		unset($form_data['invoice_id']);

		$this->load->library('upload'); //-- upload furles set up--

		// ---Image upload to directory and make image name for database insert------
		$ImageCount = count($_FILES['header_img']['name']);

		for ($i = 0; $i < $ImageCount; $i++) {
			$_FILES['file']['name']       = $_FILES['header_img']['name'][$i];
			$_FILES['file']['type']       = $_FILES['header_img']['type'][$i];
			$_FILES['file']['tmp_name']   = $_FILES['header_img']['tmp_name'][$i];
			$_FILES['file']['error']      = $_FILES['header_img']['error'][$i];
			$_FILES['file']['size']       = $_FILES['header_img']['size'][$i];

			// File upload configuration
			$config = [
				'upload_path'	=> './asset/img/am_invoice',
				'allowed_types'	=> 'jpg|jpeg|png|gif|JPG|JPEG|PNG|GIF',
				'max_size'		=> '300000',
				'max_width'		=> '5024',
				'max_height'	=> '3068',
				'overwrite' 	=> FALSE,
			];

			// Load and initialize upload library
			$this->load->library('upload', $config);
			$this->upload->initialize($config);

			// Upload file to server
			if ($this->upload->do_upload('file')) {
				//--- collect Uploaded file data---------
				$imageData = $this->upload->data();
				$uploadImgData[$i]['image_name'] = $imageData['file_name'];
			}
		}
		// ----submit data to table as record----------
		if ($this->form_validation->run('invoice_setup_validation') && !empty($uploadImgData)) {
			$form_data['header_img']	= "asset/img/am_invoice/" . $uploadImgData[0]['image_name'];
			$form_data['body_img']		= "asset/img/am_invoice/" . $uploadImgData[1]['image_name'];
			$form_data['footer_img']	= "asset/img/am_invoice/" . $uploadImgData[2]['image_name'];
			// ----default value-----------
			$form_data['add_date'] = date('Y-m-d');
			$form_data['add_time'] = date('h:i:s');
			$form_data['add_by'] = $this->session->userdata('user_id');
			$form_data['status'] = 0;


			// pre($form_data);
			// Insert files data into the database
			if ($this->db->update('am_setup_invoices', $form_data, array('invoice_id' => $invoice_id))) {
				$this->session->set_flashdata('msg', 'Invoice Layout Updated Successfully');
				$this->session->set_flashdata('msg_class', 'text-success');
				redirect('Am_setup_controller/invoiceView');
			} else {
				$this->session->set_flashdata('msg', 'Sorry ! Invoice Layout Update Failed.');
				$this->session->set_flashdata('msg_class', 'text-danger');
				redirect('Am_setup_controller/invoiceView');
			}
		} else {
			$this->session->set_flashdata('msg', 'Sorry ! Please tyr again with valid data.');
			$this->session->set_flashdata('msg_class', 'text-danger');
			redirect('Am_setup_controller/invoiceView');
		}
	}

	public function invoiceSearch()
	{
		$form_data = $this->input->post();
		// pre($form_data);
		$invoice_id = $form_data['invoice_id'];
		if (empty($invoice_id)) {
			$this->session->set_flashdata('msg', 'Sorry !! Please try again.');
			redirect('Am_setup_controller/invoiceView');
		}
		$data['invoices'] = $this->Am_setup_model->invoiceList();
		$data['invoicesSearched'] = $this->Am_setup_model->invoiceSearch($invoice_id);
		// pre($data);
		$this->load->view('am_setup/invoiceView', $data);
	}

	public function invoiceStatusUpdate()
	{
		$interest_id = $this->uri->segment(3);
		if ($this->Am_setup_model->invoiceStatusUpdate($interest_id)) {
			$this->session->set_flashdata('msg', 'Invoice setup status updated successfully');
			$this->session->set_flashdata('msg_class', 'text-success');
			redirect('Am_setup_controller/invoiceView');
		} else {
			$this->session->set_flashdata('msg', 'Sorry ! Invoice setup status updated unsuccessfull.');
			$this->session->set_flashdata('msg_class', 'text-danger');
			redirect('Am_setup_controller/invoiceView');
		}
	}
	// =============END INVOICE SETUP===========
	//-------------------------------------------

	//-------------------------------------------------
	// ===============START REFERENCE SETUP=============
	public function referenceView()
	{
		$data['references'] = $this->Am_setup_model->referenceList();
		// pre($data);
		$this->load->view('am_setup/referenceView', $data);
	}

	public function referenceAdd()
	{
		// $form_data=$this->input->post();
		// pre($form_data);
		if ($this->form_validation->run('reference_validation')) {
			$form_data = $this->input->post();
			date_default_timezone_set("Asia/Dhaka");
			$form_data['add_date'] = date('Y-m-d');
			$form_data['add_time'] = date('h:i:s');
			$form_data['add_by'] = $this->session->userdata('user_id');
			$form_data['status'] = 0;


			if ($this->db->insert('am_setup_references', $form_data)) {
				$this->session->set_flashdata('msg', 'Reference added successfully');
				$this->session->set_flashdata('msg_class', 'text-success');
				redirect('Am_setup_controller/referenceView');
			} else {
				$this->session->set_flashdata('msg', 'Sorry ! Account add unsuccessfull.');
				$this->session->set_flashdata('msg_class', 'text-danger');
				redirect('Am_setup_controller/referenceView');
			}
		} else {
			$this->session->set_flashdata('msg', 'Sorry ! Please tyr again with valid data.');
			$this->session->set_flashdata('msg_class', 'text-danger');
			redirect('Am_setup_controller/referenceView');
		}
	}

	public function referenceAddAjax()
	{
		$form_data = $this->input->post();
		// pre($form_data);
		if ($this->form_validation->run('reference_validation')) {
			$form_data = $this->input->post();
			date_default_timezone_set("Asia/Dhaka");
			$form_data['add_date'] = date('Y-m-d');
			$form_data['add_time'] = date('h:i:s');
			$form_data['add_by'] = $this->session->userdata('user_id');
			$form_data['status'] = 0;

			if ($this->db->insert('am_setup_references', $form_data)) {
				$message['msg_title'] = "Reference added successfully";
				$message['msg_class'] = "text-success";

				$message['refe_id'] = $this->db->insert_id();
				$message['refe_name'] = $form_data['refe_name'];
				$message['refe_mobile'] = $form_data['refe_mobile'];
			} else {
				$message['msg_title'] = "Reference add failed";
				$message['msg_class'] = "text-danger";
			}
		} else {
			$message['msg_title'] = "Try with valid data";
			$message['msg_class'] = "text-danger";
		}
		echo json_encode($message);
	}

	public function referenceSearch()
	{
		$form_data = $this->input->post();
		// pre($form_data);
		$refe_id = $form_data['refe_id'];
		if (empty($refe_id)) {
			$this->session->set_flashdata('msg', 'Sorry !! Please try again.');
			$this->session->set_flashdata('msg_class', 'text-danger');
			redirect('Am_setup_controller/referenceView');
		}
		$data['references'] = $this->Am_setup_model->referenceList();
		$data['referencesSearched'] = $this->Am_setup_model->referenceSearch($refe_id);
		// pre($data);
		$this->load->view('am_setup/referenceView', $data);
	}

	public function referenceStatusUpdate()
	{
		$cust_id = $this->uri->segment(3);
		if ($this->Am_setup_model->referenceStatusUpdate($cust_id)) {
			$this->session->set_flashdata('msg', 'Reference status updated successfully');
			$this->session->set_flashdata('msg_class', 'text-success');
			redirect('Am_setup_controller/referenceView');
		} else {
			$this->session->set_flashdata('msg', 'Sorry ! Reference status updated unsuccessfull.');
			$this->session->set_flashdata('msg_class', 'text-danger');
			redirect('Am_setup_controller/referenceView');
		}
	}
	// =============END REFERENCE SETUP===========
	//-------------------------------------------


	//-------------------------------------------------
	// ===============START SUPPLIER SETUP=============
	public function supplierView()
	{
		$data['suppliers'] = $this->Am_setup_model->supplierList();
		// pre($data);
		$this->load->view('am_setup/supplierView', $data);
	}

	public function supplierAdd()
	{
		// $form_data=$this->input->post();
		// pre($form_data);
		if ($this->form_validation->run('supplier_validation')) {
			$form_data = $this->input->post();
			date_default_timezone_set("Asia/Dhaka");
			$form_data['add_date'] = date('Y-m-d');
			$form_data['add_time'] = date('h:i:s');
			$form_data['add_by'] = $this->session->userdata('user_id');
			$form_data['status'] = 0;


			if ($this->db->insert('am_setup_suppliers', $form_data)) {
				$this->session->set_flashdata('msg', 'Supplier added successfully');
				$this->session->set_flashdata('msg_class', 'text-success');
				redirect('Am_setup_controller/supplierView');
			} else {
				$this->session->set_flashdata('msg', 'Sorry ! Supplier add unsuccessfull.');
				$this->session->set_flashdata('msg_class', 'text-danger');
				redirect('Am_setup_controller/supplierView');
			}
		} else {
			$this->session->set_flashdata('msg', 'Sorry ! Please tyr again with valid data.');
			$this->session->set_flashdata('msg_class', 'text-danger');
			redirect('Am_setup_controller/supplierView');
		}
	}


	public function supplierAddAjax()
	{
		$form_data = $this->input->post();
		// pre($form_data);
		if ($this->form_validation->run('supplier_validation')) {
			$form_data = $this->input->post();
			date_default_timezone_set("Asia/Dhaka");
			$form_data['add_date'] = date('Y-m-d');
			$form_data['add_time'] = date('h:i:s');
			$form_data['add_by'] = $this->session->userdata('user_id');
			$form_data['status'] = 0;

			if ($this->db->insert('am_setup_suppliers', $form_data)) {
				$message['msg_title'] = "Supplier added successfully";
				$message['msg_class'] = "text-success";

				$message['supp_id'] = $this->db->insert_id();
				$message['supp_name'] = $form_data['supp_name'];
				$message['supp_mobile'] = $form_data['supp_mobile'];
			} else {
				$message['msg_title'] = "Supplier add failed";
				$message['msg_class'] = "text-danger";
			}
		} else {
			$message['msg_title'] = "Try with valid data";
			$message['msg_class'] = "text-danger";
		}
		echo json_encode($message);
	}

	public function supplierSearch()
	{
		$form_data = $this->input->post();
		// pre($form_data);
		$supp_id = $form_data['supp_id'];
		if (empty($supp_id)) {
			$this->session->set_flashdata('msg', 'Sorry !! Please try again.');
			redirect('Am_setup_controller/supplierView');
		}
		$data['suppliers'] = $this->Am_setup_model->supplierList();
		$data['suppliersSearched'] = $this->Am_setup_model->supplierSearch($supp_id);
		// pre($data);
		$this->load->view('am_setup/supplierView', $data);
	}

	public function supplierStatusUpdate()
	{
		$supp_id = $this->uri->segment(3);
		if ($this->Am_setup_model->supplierStatusUpdate($supp_id)) {
			$this->session->set_flashdata('msg', 'Customer status updated successfully');
			$this->session->set_flashdata('msg_class', 'text-success');
			redirect('Am_setup_controller/supplierView');
		} else {
			$this->session->set_flashdata('msg', 'Sorry ! Customer status updated unsuccessfull.');
			$this->session->set_flashdata('msg_class', 'text-danger');
			redirect('Am_setup_controller/supplierView');
		}
	}
	// =============END SUPPLIER SETUP===========
	//-------------------------------------------

	//-------------------------------------------------
	// ===============START UNIT SETUP=============
	public function unitView()
	{
		$data['units'] = $this->Am_setup_model->unitList();
		// pre($data);
		$this->load->view('am_setup/unitView', $data);
	}

	public function unitAdd()
	{
		// $form_data=$this->input->post();
		// pre($form_data);
		if ($this->form_validation->run('unit_validation')) {
			$form_data = $this->input->post();
			date_default_timezone_set("Asia/Dhaka");
			$form_data['add_date'] = date('Y-m-d');
			$form_data['add_time'] = date('h:i:s');
			$form_data['add_by'] = $this->session->userdata('user_id');
			$form_data['status'] = 0;


			if ($this->db->insert('am_setup_units', $form_data)) {
				$this->session->set_flashdata('msg', 'Unit added successfully');
				$this->session->set_flashdata('msg_class', 'text-success');
				redirect('Am_setup_controller/unitView');
			} else {
				$this->session->set_flashdata('msg', 'Sorry ! Unit add unsuccessfull.');
				$this->session->set_flashdata('msg_class', 'text-danger');
				redirect('Am_setup_controller/unitView');
			}
		} else {
			$this->session->set_flashdata('msg', 'Sorry ! Please tyr again with valid data.');
			$this->session->set_flashdata('msg_class', 'text-danger');
			redirect('Am_setup_controller/unitView');
		}
	}

	public function unitSearch()
	{
		$form_data = $this->input->post();
		// pre($form_data);
		$unit_id = $form_data['unit_id'];
		if (empty($unit_id)) {
			$this->session->set_flashdata('msg', 'Sorry !! Please try again.');
			redirect('Am_setup_controller/unitView');
		}
		$data['units'] = $this->Am_setup_model->unitList();
		$data['unitsSearched'] = $this->Am_setup_model->unitSearch($unit_id);
		// pre($data);
		$this->load->view('am_setup/unitView', $data);
	}

	// public function unitStatusUpdate(){
	// 	$unit_id =$this->uri->segment(3); 
	// 	if($this->Am_setup_model->unitStatusUpdate($unit_id)){
	// 		$this->session->set_flashdata('msg','Customer status updated successfully');
	// 		$this->session->set_flashdata('msg_class','text-success');
	// 		redirect('Am_setup_controller/unitView');
	//     	}else{
	//     		$this->session->set_flashdata('msg','Sorry ! Customer status updated unsuccessfull.');
	// 			$this->session->set_flashdata('msg_class','text-danger');
	// 			redirect('Am_setup_controller/unitView');
	// 	    	}
	// }
	// =============END UNIT SETUP===========
	//-------------------------------------------

	//-------------------------------------------------
	// ===============START MANUFACTURER SETUP=============
	public function manufacturerView()
	{
		$data['manufacturers'] = $this->Am_setup_model->manufacturerList();
		// pre($data);
		$this->load->view('am_setup/manufacturerView', $data);
	}

	public function manufacturerAdd()
	{
		// $form_data=$this->input->post();
		// pre($form_data);
		if ($this->form_validation->run('manufacturer_validation')) {
			$form_data = $this->input->post();
			date_default_timezone_set("Asia/Dhaka");
			$form_data['add_date'] = date('Y-m-d');
			$form_data['add_time'] = date('h:i:s');
			$form_data['add_by'] = $this->session->userdata('user_id');
			$form_data['status'] = 0;


			if ($this->db->insert('am_setup_manufacturers', $form_data)) {
				$this->session->set_flashdata('msg', 'Manufacturer added successfully');
				$this->session->set_flashdata('msg_class', 'text-success');
				redirect('Am_setup_controller/manufacturerView');
			} else {
				$this->session->set_flashdata('msg', 'Sorry ! Manufacturer add unsuccessfull.');
				$this->session->set_flashdata('msg_class', 'text-danger');
				redirect('Am_setup_controller/manufacturerView');
			}
		} else {
			$this->session->set_flashdata('msg', 'Sorry ! Please tyr again with valid data.');
			$this->session->set_flashdata('msg_class', 'text-danger');
			redirect('Am_setup_controller/manufacturerView');
		}
	}

	public function manufacturerSearch()
	{
		$form_data = $this->input->post();
		// pre($form_data);
		$manuf_id = $form_data['manuf_id'];
		if (empty($manuf_id)) {
			$this->session->set_flashdata('msg', 'Sorry !! Please try again.');
			redirect('Am_setup_controller/manufacturerView');
		}
		$data['manufacturers'] = $this->Am_setup_model->manufacturerList();
		$data['manufacturersSearched'] = $this->Am_setup_model->manufacturerSearch($manuf_id);
		// pre($data);
		$this->load->view('am_setup/manufacturerView', $data);
	}

	public function manufacturerStatusUpdate()
	{
		$manuf_id = $this->uri->segment(3);
		if ($this->Am_setup_model->manufacturerStatusUpdate($manuf_id)) {
			$this->session->set_flashdata('msg', 'Manufacturer status updated successfully');
			$this->session->set_flashdata('msg_class', 'text-success');
			redirect('Am_setup_controller/manufacturerView');
		} else {
			$this->session->set_flashdata('msg', 'Sorry ! Manufacturer status updated unsuccessfull.');
			$this->session->set_flashdata('msg_class', 'text-danger');
			redirect('Am_setup_controller/manufacturerView');
		}
	}
	// =============END MANUFACTURER SETUP===========
	//-------------------------------------------

	//-------------------------------------------------
	// ===============START PRODUCT SETUP=============
	public function productView()
	{
		$data['manufacturers'] = $this->Am_setup_model->manufacturerList();
		$data['categorys'] = $this->Am_setup_model->categoryList();
		$data['units'] = $this->Am_setup_model->unitList();
		$data['products'] = $this->Am_setup_model->productList();
		// pre($data);
		$this->load->view('am_setup/productView', $data);
	}

	public function productAdd()
	{
		$form_data = $this->input->post();
		// pre($form_data);
		if ($this->form_validation->run('product_validation')) {
			$form_data = $this->input->post();
			date_default_timezone_set("Asia/Dhaka");
			$form_data['add_date'] = date('Y-m-d');
			$form_data['add_time'] = date('h:i:s');
			$form_data['add_by'] = $this->session->userdata('user_id');
			$form_data['status'] = 0;

			if ($this->db->insert('am_setup_products', $form_data)) {
				$this->session->set_flashdata('msg', 'Manufacturer added successfully');
				$this->session->set_flashdata('msg_class', 'text-success');
				redirect('Am_setup_controller/productView');
			} else {
				$this->session->set_flashdata('msg', 'Sorry ! Manufacturer add unsuccessfull.');
				$this->session->set_flashdata('msg_class', 'text-danger');
				redirect('Am_setup_controller/productView');
			}
		} else {
			$this->session->set_flashdata('msg', 'Sorry ! Please tyr again with valid data.');
			$this->session->set_flashdata('msg_class', 'text-danger');
			redirect('Am_setup_controller/productView');
		}
	}

	public function productSearch()
	{
		$form_data = $this->input->post();
		// pre($form_data);
		$product_id = $form_data['product_id'];
		if (empty($product_id)) {
			$this->session->set_flashdata('msg', 'Sorry !! Please try again.');
			redirect('Am_setup_controller/productView');
		}
		$data['products'] = $this->Am_setup_model->productList();
		$data['productsSearched'] = $this->Am_setup_model->productSearch($product_id);
		// pre($data);
		$this->load->view('am_setup/productView', $data);
	}

	public function productStatusUpdate()
	{
		$product_id = $this->uri->segment(3);
		if ($this->Am_setup_model->productStatusUpdate($product_id)) {
			$this->session->set_flashdata('msg', 'Product status updated successfully');
			$this->session->set_flashdata('msg_class', 'text-success');
			redirect('Am_setup_controller/productView');
		} else {
			$this->session->set_flashdata('msg', 'Sorry ! Product status updated unsuccessfull.');
			$this->session->set_flashdata('msg_class', 'text-danger');
			redirect('Am_setup_controller/productView');
		}
	}

	public function productUpdate()
	{
		$form_data = $this->input->post();
		$product_id = $form_data['product_id'];
		unset($form_data['product_id']);
		// pre($form_data);
		if ($this->form_validation->run('product_validation')) {
			if ($this->db->where('product_id', $product_id)->update('am_setup_products', $form_data)) {
				$this->session->set_flashdata('msg', 'Product update successfully');
				$this->session->set_flashdata('msg_class', 'text-success');
				redirect('Am_setup_controller/productView');
			} else {
				$this->session->set_flashdata('msg', 'Sorry ! Product update failed.');
				$this->session->set_flashdata('msg_class', 'text-danger');
				redirect('Am_setup_controller/productView');
			}
		} else {
			$this->session->set_flashdata('msg', 'Sorry ! Please tyr again with valid data.');
			$this->session->set_flashdata('msg_class', 'text-danger');
			redirect('Am_setup_controller/productView');
		}
	}
	// =============END PRODUCT SETUP===========
	//-------------------------------------------



}   //----- end class----------
