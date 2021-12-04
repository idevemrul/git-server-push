<?php
defined('BASEPATH') or exit('No direct script access allowed');
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Am_expenditure_controller extends CI_Controller
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
		$this->load->model('Am_expenditure_model');
		$this->load->model('Am_feed_data_model');
	}

	// ========START FORM FEED DATA========
	public function accountSubheadAjax()
	{
		$form_data = $this->input->post();
		// pre($form_data);
		$ach_id = $form_data['ach_id'];
		$account_sub_head = $this->Am_feed_data_model->accountSubheadAjax($ach_id);
		echo json_encode($account_sub_head);
	}

	public function accountCooSubheadAjax()
	{
		$form_data = $this->input->post();
		// pre($form_data);
		$acsh_id = $form_data['acsh_id'];
		$account_coo_sub_head = $this->Am_feed_data_model->accountCooSubheadAjax($acsh_id);
		echo json_encode($account_coo_sub_head);
	}

	public function voucherDueAjax()
	{
		$form_data = $this->input->post();
		$vou_id = $form_data['vou_id'];

		$voucher_due = $this->db->select('expVou.vou_amount,vou_pay_amount,vou_party_id')
			->from('am_expenditure_vouchers AS expVou
										')
			->where('expVou.vou_id', $vou_id)
			->join('(SELECT vou_id,SUM(vou_pay_amount) vou_pay_amount FROM am_expenditure_voucher_payments GROUP BY vou_id) c', 'expVou.vou_id=c.vou_id', 'left')
			->get()->result();
		echo json_encode($voucher_due);
	}

	public function bankBalanceAjax()
	{
		$form_data = $this->input->post();
		// pre($form_data);
		$bank_id = $form_data['bank_id'];
		$bank_available_balance = $this->db->select('cheque_mode,SUM(bank_cheque_amount) total_amount')
			->from('am_bank_transaction btran')
			->where('btran.bank_id', $bank_id)
			->group_by('btran.cheque_mode')
			->get()->result();
		echo json_encode($bank_available_balance);
	}
	// ========END FORM FEED DATA==========

	//-------------------------------------------------
	// ===============START VOUCHER SETUP=============
	public function voucherView()
	{
		$form_data = $this->input->post();
		// pre($form_data);
		if (empty($form_data)) {
			isset($form_data['vou_id']) ? $vou_id = $form_data['vou_id'] : '';
			$search_date_start = date("Y-m-d", strtotime("-7 days"));
			$search_date_end = date("Y-m-d");

			$data['searchBetweens'] = [
				'search_date_start' => $search_date_start,
				'search_date_end'	=> $search_date_end
			];
		} else {
			isset($form_data['vou_id']) ? $vou_id = $form_data['vou_id'] : '';

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
		if (isset($vou_id) and !empty($vou_id)) {
			$where .= 'expVou.vou_id=' . $vou_id . ' AND ';
		}
		if (isset($search_date_start) and ($search_date_start != '1970-01-01')) {
			$where .= " expVou.vou_date BETWEEN '$search_date_start' AND '$search_date_end' AND ";
		}

		$where .= "expVou.ach_id=ach.ach_id AND
					expVou.acsh_id=acsh.acsh_id AND
					expVou.accsh_id=accsh.accsh_id 
					";

		$data['accountExpHeads'] = $this->Am_feed_data_model->accountExpHead();
		$data['voucherLists'] = $this->Am_feed_data_model->voucherList();
		$data['partyLists'] = $this->Am_feed_data_model->partyList();
		$data['vouchers'] = $this->Am_expenditure_model->voucherList($where);
		$this->load->view('am_expenditure/expenditureVoucherView', $data);
	}

	public function voucherAdd()
	{
		$form_data = $this->input->post();
		// pre($form_data);


		if ($this->form_validation->run('voucher_validation')) {
			date_default_timezone_set("Asia/Dhaka");
			$form_data['add_date'] = date('Y-m-d');
			$form_data['add_time'] = date('h:i:s');
			$form_data['add_by'] = $this->session->userdata('user_id');
			$form_data['status'] = 0;

			$vou_paid = $form_data['vou_paid'];
			unset($form_data['vou_paid']);

			if ($vou_paid > 0) {
				$voucher_paid = [
					'vou_id'   			=> '',
					'vou_pay_by' 		=> 'cash',
					'vou_pay_date' 		=> $form_data['vou_date'],
					'vou_pay_note'		=> $form_data['vou_note'],
					'vou_pay_amount' 	=> $vou_paid,
					'add_date' 			=> date('Y-m-d'),
					'add_time' 			=> date('h:i:s'),
					'add_by' 			=> $this->session->userdata('user_id'),
					'status' 			=> 0,
				];

				// ------ start cash_transaction -------
				$main_cash = 1;
				$cash_mode = 'out';
				$exp_vou_id = '';
				$pay_cash_transaction_data = array(
					'cash_id' 					=> $main_cash,
					'cash_tran_reference_type' 	=> 'exp_voucher',
					'cash_tran_reference_id'	=> $exp_vou_id,
					'cash_tran_for' 			=> 'party',
					'cash_tran_for_id'			=> $form_data['vou_party_id'],
					'cash_tran_dateE' 			=> $form_data['vou_date'],
					'cash_mode'					=> $cash_mode,
					'cash_cheque_amount' 		=> $vou_paid,
					'cash_note'					=> $form_data['vou_note'],
					'add_date' 					=> date('Y-m-d'),
					'add_time' 					=> date('h:i:s'),
					'add_by' 					=> $this->session->userdata('user_id'),
					'status' 					=> 0,
				);
			}

			// ------exp voucher amount add-----------
			$liabilities 	= 3;
			$ac_payable 	= 7;
			$exp_ac_payable = 26;
			$cash_book_id	= '';
			$exp_vou_id		= '';
			$transaction_exp_voucher_amount = [
				'ach_id'				=> $form_data['ach_id'],
				'acsh_id'				=> $form_data['acsh_id'],
				'accsh_id'				=> $form_data['accsh_id'],
				'bank_or_cash_book_id'	=> $cash_book_id,
				'tran_reference'		=> 'exp_voucher',
				'tran_reference_id'		=> $exp_vou_id,
				'tran_for'				=> 'party',
				'tran_for_id'			=> $form_data['vou_party_id'],
				'tran_mode'				=> 'Dr',
				'tran_amount'			=> $form_data['vou_amount'],
				'tran_dateE'			=> date('Y-m-d'),
				'tran_details'			=> $form_data['vou_note'],
				'add_date' 				=> date('Y-m-d'),
				'add_time' 				=> date('h:i:s'),
				'add_by' 				=> $this->session->userdata('user_id'),
				'status' 				=> 0,
			];

			// ------paid voucher amount by cash-----------
			if ($vou_paid > 0) {
				$asset = 2;
				$cash_at_hand = 5;
				$main_cash = 17;
				$cash_book_id = '';

				$transaction_exp_voucher_payment_cash = [
					'ach_id'				=> $asset,
					'acsh_id'				=> $cash_at_hand,
					'accsh_id'				=> $main_cash,
					'bank_or_cash_book_id'	=> $cash_book_id,
					'tran_reference'		=> 'exp_voucher',
					'tran_reference_id'		=> $exp_vou_id,
					'tran_for'				=> 'party',
					'tran_for_id'			=> $form_data['vou_party_id'],
					'tran_mode'				=> 'Cr',
					'tran_amount'			=> $vou_paid,
					'tran_dateE'			=> date('Y-m-d'),
					'tran_details'			=> $form_data['vou_note'],
					'add_date' 				=> date('Y-m-d'),
					'add_time' 				=> date('h:i:s'),
					'add_by' 				=> $this->session->userdata('user_id'),
					'status' 				=> 0,
				];
			}

			// ------voucher payment due or A/C payable amount-----------
			$liabilities = 3;
			$ac_payable = 7;
			$exp_ac_payable = 13;
			$payable_amount = $form_data['vou_amount'] - $vou_paid;
			$transaction_exp_voucher_payment_payable = [
				'ach_id'				=> $liabilities,
				'acsh_id'				=> $ac_payable,
				'accsh_id'				=> $exp_ac_payable,
				'bank_or_cash_book_id'	=> $cash_book_id,
				'tran_reference'		=> 'exp_voucher',
				'tran_reference_id'		=> $exp_vou_id,
				'tran_for'				=> 'party',
				'tran_for_id'			=> $form_data['vou_party_id'],
				'tran_mode'				=> 'Cr',
				'tran_amount'			=> $payable_amount,
				'tran_dateE'			=> date('Y-m-d'),
				'tran_details'			=> $form_data['vou_note'],
				'add_date' 				=> date('Y-m-d'),
				'add_time' 				=> date('h:i:s'),
				'add_by' 				=> $this->session->userdata('user_id'),
				'status' 				=> 0,
			];
			// pre($voucher_paid);
			$this->db->trans_start();
			$this->db->insert('am_expenditure_vouchers', $form_data);
			//---collect last inserted id-----
			$new_exp_vou_id = $voucher_paid['vou_id'] = $this->db->insert_id();
			$transaction_exp_voucher_amount['tran_reference_id']			= $new_exp_vou_id;
			$transaction_exp_voucher_payment_cash['tran_reference_id']		= $new_exp_vou_id;
			$transaction_exp_voucher_payment_payable['tran_reference_id']	= $new_exp_vou_id;
			$pay_cash_transaction_data['cash_tran_reference_id']			= $new_exp_vou_id;

			$this->db->insert('am_transaction', $transaction_exp_voucher_amount);
			if ($vou_paid > 0) {
				$this->db->insert('am_expenditure_voucher_payments', $voucher_paid);
				$this->db->insert('am_cash_transaction', $pay_cash_transaction_data);

				$new_cash_tran_id = $this->db->insert_id();
				$transaction_exp_voucher_payment_cash['bank_or_cash_book_id'] = $new_cash_tran_id;
				$this->db->insert('am_transaction', $transaction_exp_voucher_payment_cash);
			}
			$this->db->insert('am_transaction', $transaction_exp_voucher_payment_payable);
			$this->db->trans_complete();

			// ------transaction status check and action----
			if ($this->db->trans_status() === TRUE) {
				$this->session->set_flashdata('msg', 'Voucher added successfully');
				$this->session->set_flashdata('msg_class', 'text-success');
				redirect('Am_expenditure_controller/voucherView');
			} else {
				$this->session->set_flashdata('msg', 'Sorry ! Voucher add unsuccessfull !!');
				$this->session->set_flashdata('msg_class', 'text-danger');
				redirect('Am_expenditure_controller/voucherView');
			}
		} else {
			$this->session->set_flashdata('msg', 'Sorry ! voucher add faild.');
			$this->session->set_flashdata('msg_class', 'text-danger');
			redirect('Am_expenditure_controller/voucherView');
		}
	}
	//-------------------------------------------------
	// ===============END VOUCHER SETUP=============

	//-------------------------------------------------
	// ===============START VOUCHER PAYMENT SETUP=============
	public function voucherPaymentView()
	{
		$form_data = $this->input->post();
		// pre($form_data);
		if (empty($form_data)) {
			isset($form_data['vou_id']) ? $vou_id = $form_data['vou_id'] : '';
			$search_date_start = date("Y-m-d", strtotime("-7 days"));
			$search_date_end = date("Y-m-d");

			$data['searchBetweens'] = [
				'search_date_start' => $search_date_start,
				'search_date_end'	=> $search_date_end
			];
		} else {
			isset($form_data['vou_id']) ? $vou_id = $form_data['vou_id'] : '';

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
		if (isset($vou_id) and !empty($vou_id)) {
			$where .= 'expVou.vou_id=' . $vou_id . ' AND ';
		}
		if (isset($search_date_start) and ($search_date_start != '1970-01-01')) {
			$where .= " expVou.vou_date BETWEEN '$search_date_start' AND '$search_date_end' AND ";
		}

		$where .= "expVouPay.vou_id=expVou.vou_id AND 
					expVou.ach_id=ach.ach_id AND
					expVou.acsh_id=acsh.acsh_id AND
					expVou.accsh_id=accsh.accsh_id 
					";

		$data['bankLists'] = $this->Am_feed_data_model->bankList();
		// pre($data['bankLists']);
		$data['accountExpHeads'] = $this->Am_feed_data_model->accountExpHead();
		$data['voucherLists'] = $this->Am_feed_data_model->voucherList();
		$data['partyLists'] = $this->Am_feed_data_model->partyList();
		$data['voucherPayments'] = $this->Am_expenditure_model->voucherPaymentList($where);
		// pre($data['voucherPayments']);
		$this->load->view('am_expenditure/expenditureVoucherPaymentView', $data);
	}

	public function voucherPaymentAdd()
	{
		$form_data = $this->input->post();
		// pre($form_data);
		$bank_payment = '';
		if ($this->form_validation->run('voucher_payment_validation') && $form_data['vou_pay_amount'] > 0) {
			if ($form_data['vou_pay_by'] == 'cash') {

				// ------Exp voucher Due paid amount by cash-----------
				$asset = 2;
				$cash_at_hand = 5;
				$main_cash = 17;
				$cash_book_id = '';

				$transaction_exp_voucher_due_payment_cash = [
					'ach_id'				=> $asset,
					'acsh_id'				=> $cash_at_hand,
					'accsh_id'				=> $main_cash,
					'bank_or_cash_book_id'	=> $cash_book_id,
					'tran_reference'		=> 'exp_voucher',
					'tran_reference_id'		=> $form_data['vou_id'],
					'tran_for'				=> 'party',
					'tran_for_id'			=> $form_data['party_id'],
					'tran_mode'				=> 'Cr',
					'tran_amount'			=> $form_data['vou_pay_amount'],
					'tran_dateE'			=> $form_data['vou_pay_date'],
					'tran_details'			=> $form_data['vou_pay_note'],
					'add_date' 				=> date('Y-m-d'),
					'add_time' 				=> date('h:i:s'),
					'add_by' 				=> $this->session->userdata('user_id'),
					'status' 				=> 0,
				];

				// ------Exp voucher due or A/C payable amount reduce-----------
				$liabilities = 3;
				$ac_payable = 7;
				$exp_ac_payable = 13;

				$transaction_exp_voucher_payment_payable_dr = [
					'ach_id'				=> $liabilities,
					'acsh_id'				=> $ac_payable,
					'accsh_id'				=> $exp_ac_payable,
					'bank_or_cash_book_id'	=> $cash_book_id,
					'tran_reference'		=> 'exp_voucher',
					'tran_reference_id'		=> $form_data['vou_id'],
					'tran_for'				=> 'party',
					'tran_for_id'			=> $form_data['party_id'],
					'tran_mode'				=> 'Dr',
					'tran_amount'			=> $form_data['vou_pay_amount'],
					'tran_dateE'			=> $form_data['vou_pay_date'],
					'tran_details'			=> $form_data['vou_pay_note'],
					'add_date' 				=> date('Y-m-d'),
					'add_time' 				=> date('h:i:s'),
					'add_by' 				=> $this->session->userdata('user_id'),
					'status' 				=> 0,
				];

				// ------ start cash_transaction -------
				$main_cash = 1;
				$cash_mode = 'out';
				$exp_vou_id = '';
				$pay_cash_transaction_data = array(
					'cash_id' 					=> $main_cash,
					'cash_tran_reference_type' 	=> 'exp_voucher',
					'cash_tran_reference_id'	=> $form_data['vou_id'],
					'cash_tran_for' 			=> 'party',
					'cash_tran_for_id'			=> $form_data['party_id'],
					'cash_tran_dateE' 			=> $form_data['vou_pay_date'],
					'cash_mode'					=> $cash_mode,
					'cash_cheque_amount' 		=> $form_data['vou_pay_amount'],
					'cash_note'					=> $form_data['vou_pay_note'],
					'add_date' 					=> date('Y-m-d'),
					'add_time' 					=> date('h:i:s'),
					'add_by' 					=> $this->session->userdata('user_id'),
					'status' 					=> 0,
				);

				unset($form_data['party_id']);
				date_default_timezone_set("Asia/Dhaka");
				$form_data['add_date'] = date('Y-m-d');
				$form_data['add_time'] = date('h:i:s');
				$form_data['add_by'] = $this->session->userdata('user_id');
				$form_data['status'] = 0;
			} else {
				date_default_timezone_set("Asia/Dhaka");
				$bank_payment = [
					'bank_id' => $form_data['bank_id'],
					'bank_tran_reference_id' => $form_data['vou_id'],
					'bank_tran_reference_type' => 'exp_voucher',
					'bank_tran_for_id' => $form_data['party_id'],
					'bank_tran_for' => 'party',
					'bank_tran_method' => $form_data['vou_pay_by'],
					'bank_client_bank' => '',
					'bank_tran_cheque_no' => $form_data['cheque_number'],
					'bank_tran_cheque_date' => $form_data['cheque_date'],
					'bank_tran_cheque_action' => 'entry',
					'bank_tran_cheque_action_date' => '',
					'cheque_mode' => 'out',
					'bank_cheque_amount' => $form_data['vou_pay_amount'],
					'bank_note' => $form_data['vou_pay_note'],
					'add_date' => date('Y-m-d'),
					'add_time' => date('h:i:s'),
					'add_by' => $this->session->userdata('user_id'),
					'status' => 0,
				];
				// pre($bank_payment);
			}

			$this->db->trans_start();
			if ($form_data['vou_pay_by'] == 'cash') {
				$this->db->insert('am_expenditure_voucher_payments', $form_data);
				$this->db->insert('am_cash_transaction', $pay_cash_transaction_data);

				$transaction_exp_voucher_due_payment_cash['bank_or_cash_book_id']=$this->db->insert_id();
				$this->db->insert('am_transaction',$transaction_exp_voucher_payment_payable_dr);
				$this->db->insert('am_transaction',$transaction_exp_voucher_due_payment_cash);
			} else {
				$this->db->insert('am_bank_transaction', $bank_payment);
			}
			$this->db->trans_complete();

			// ------transaction status check and action----
			if ($this->db->trans_status() === TRUE) {
				$this->session->set_flashdata('msg', 'Voucher payment added successfully');
				$this->session->set_flashdata('msg_class', 'text-success');
				redirect('Am_expenditure_controller/voucherPaymentView');
			} else {
				$this->session->set_flashdata('msg', 'Sorry ! Voucher payment unsuccessfull !!');
				$this->session->set_flashdata('msg_class', 'text-danger');
				redirect('Am_expenditure_controller/voucherPaymentView');
			}
		} else {
			$this->session->set_flashdata('msg', 'Sorry ! voucher add faild.');
			$this->session->set_flashdata('msg_class', 'text-danger');
			redirect('Am_expenditure_controller/voucherPaymentView');
		}
	}
	//-------------------------------------------------
	// ===============END VOUCHER PAYMENT SETUP=============

}   //----- end class----------
