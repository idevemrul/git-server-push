<?php
defined('BASEPATH') or exit('No direct script access allowed');
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Am_income_controller extends CI_Controller
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
		$this->load->model('Am_income_model');
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

	public function incVoucherDueAjax()
	{
		$form_data = $this->input->post();
		$vou_id = $form_data['vou_id'];

		$voucher_due = $this->db->select('expVou.vou_amount,vou_pay_amount,vou_party_id')
			->from('am_income_vouchers AS expVou
										')
			->where('expVou.vou_id', $vou_id)
			->join('(SELECT vou_id,SUM(vou_pay_amount) vou_pay_amount FROM am_income_voucher_payments GROUP BY vou_id) c', 'expVou.vou_id=c.vou_id', 'left')
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
	public function incomeVoucherView()
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

		$data['accountIncHeads'] = $this->Am_feed_data_model->accountIncHead();
		$data['voucherLists'] = $this->Am_feed_data_model->incVoucherList();
		$data['partyLists'] = $this->Am_feed_data_model->partyList();
		$data['vouchers'] = $this->Am_income_model->incomeVoucherList($where);
		$this->load->view('am_income/incomeVoucherView', $data);
	}

	public function incomeVoucherAdd()
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
				$cash_mode = 'in';
				$inc_vou_id = '';

				$pay_cash_transaction_data = array(
					'cash_id' 					=> $main_cash,
					'cash_tran_reference_type' 	=> 'inc_voucher',
					'cash_tran_reference_id'	=> $inc_vou_id,
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
			$cash_book_id	= '';
			$inc_vou_id		= '';

			$transaction_exp_voucher_amount = [
				'ach_id'				=> $form_data['ach_id'],
				'acsh_id'				=> $form_data['acsh_id'],
				'accsh_id'				=> $form_data['accsh_id'],
				'bank_or_cash_book_id'	=> $cash_book_id,
				'tran_reference'		=> 'inc_voucher',
				'tran_reference_id'		=> $inc_vou_id,
				'tran_for'				=> 'party',
				'tran_for_id'			=> $form_data['vou_party_id'],
				'tran_mode'				=> 'Cr',
				'tran_amount'			=> $form_data['vou_amount'],
				'tran_dateE'			=> date('Y-m-d'),
				'tran_details'			=> $form_data['vou_note'],
				'add_date' 				=> date('Y-m-d'),
				'add_time' 				=> date('h:i:s'),
				'add_by' 				=> $this->session->userdata('user_id'),
				'status' 				=> 0,
			];

			// ------paid inc-voucher amount by cash-----------
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
					'tran_reference'		=> 'inc_voucher',
					'tran_reference_id'		=> $inc_vou_id,
					'tran_for'				=> 'party',
					'tran_for_id'			=> $form_data['vou_party_id'],
					'tran_mode'				=> 'Dr',
					'tran_amount'			=> $vou_paid,
					'tran_dateE'			=> date('Y-m-d'),
					'tran_details'			=> $form_data['vou_note'],
					'add_date' 				=> date('Y-m-d'),
					'add_time' 				=> date('h:i:s'),
					'add_by' 				=> $this->session->userdata('user_id'),
					'status' 				=> 0,
				];
			}

			// ------due inc-voucher amount A/C Receivable Dr.-----------
			$asset = 2;
			$ac_receivable = 3;
			$inc_ac_receivable = 26;
			$payable_amount = $form_data['vou_amount'] - $vou_paid;

			$transaction_exp_voucher_payment_payable = [
				'ach_id'				=> $asset,
				'acsh_id'				=> $ac_receivable,
				'accsh_id'				=> $inc_ac_receivable,
				'bank_or_cash_book_id'	=> $cash_book_id,
				'tran_reference'		=> 'inc_voucher',
				'tran_reference_id'		=> $inc_vou_id,
				'tran_for'				=> 'party',
				'tran_for_id'			=> $form_data['vou_party_id'],
				'tran_mode'				=> 'Dr',
				'tran_amount'			=> $payable_amount,
				'tran_dateE'			=> date('Y-m-d'),
				'tran_details'			=> $form_data['vou_note'],
				'add_date' 				=> date('Y-m-d'),
				'add_time' 				=> date('h:i:s'),
				'add_by' 				=> $this->session->userdata('user_id'),
				'status' 				=> 0,
			];
			$this->db->trans_start();
			$this->db->insert('am_income_vouchers', $form_data);
			// ----last id collection----------
			$new_vou_id = $this->db->insert_id();
			$voucher_paid['vou_id']		= $new_vou_id;
			$transaction_exp_voucher_payment_payable['tran_reference_id']	= $new_vou_id;
			$transaction_exp_voucher_payment_cash['tran_reference_id']		= $new_vou_id;
			$transaction_exp_voucher_amount['tran_reference_id']			= $new_vou_id;
			$pay_cash_transaction_data['cash_tran_reference_id']			= $new_vou_id;
			// pre($pay_cash_transaction_data['cash_tran_reference_id']);

			if ($vou_paid > 0) {
				$this->db->insert('am_income_voucher_payments', $voucher_paid);
				$this->db->insert('am_cash_transaction', $pay_cash_transaction_data);
				$new_cash_tran_id = $this->db->insert_id();

				$transaction_exp_voucher_payment_cash['bank_or_cash_book_id'] = $new_cash_tran_id;
				$this->db->insert('am_transaction', $transaction_exp_voucher_payment_cash);
			}
			$this->db->insert('am_transaction', $transaction_exp_voucher_payment_payable);
			$this->db->insert('am_transaction', $transaction_exp_voucher_amount);
			$this->db->trans_complete();

			// ------transaction status check and action----
			if ($this->db->trans_status() === TRUE) {
				$this->session->set_flashdata('msg', 'Voucher added successfully');
				$this->session->set_flashdata('msg_class', 'text-success');
				redirect('Am_income_controller/incomeVoucherView');
			} else {
				$this->session->set_flashdata('msg', 'Sorry ! Voucher add unsuccessfull !!');
				$this->session->set_flashdata('msg_class', 'text-danger');
				redirect('Am_expenditure_controller/incomeVoucherView');
			}
		} else {
			$this->session->set_flashdata('msg', 'Sorry ! voucher add faild.');
			$this->session->set_flashdata('msg_class', 'text-danger');
			redirect('Am_income_controller/incomeVoucherView');
		}
	}
	//-------------------------------------------------
	// ===============END VOUCHER SETUP=============

	//-------------------------------------------------
	// ===============START VOUCHER PAYMENT SETUP=============
	public function incomeVoucherCollectionView()
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
		$data['accountIncHeads'] = $this->Am_feed_data_model->accountIncHead();
		$data['voucherLists'] = $this->Am_feed_data_model->incVoucherList();
		$data['partyLists'] = $this->Am_feed_data_model->partyList();
		$data['voucherPayments'] = $this->Am_income_model->incomeVoucherCollectionList($where);
		// pre($data['voucherPayments']);
		$this->load->view('am_income/incomeVoucherCollectionView', $data);
	}

	public function incomeVoucherCollectionAdd()
	{
		$form_data = $this->input->post();
		// pre($form_data);
		$bank_payment = '';
		if ($this->form_validation->run('voucher_payment_validation') && $form_data['vou_pay_amount'] > 0) {
			if ($form_data['vou_pay_by'] == 'cash') {

				// ------due collection inc-voucher amount by cash-----------
				$asset = 2;
				$cash_at_hand = 5;
				$main_cash = 17;
				$cash_book_id = '';

				$transaction_inc_voucher_due_collection_cash = [
					'ach_id'				=> $asset,
					'acsh_id'				=> $cash_at_hand,
					'accsh_id'				=> $main_cash,
					'bank_or_cash_book_id'	=> $cash_book_id,
					'tran_reference'		=> 'inc_voucher',
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
				// ------- due inc-voucehr A/C Receivable reduce Cr----------
				$asset = 2;
				$ac_receivable = 3;
				$inc_ac_receivable = 26;

				$transaction_inc_voucher_payment_receivable_cr = [
					'ach_id'				=> $asset,
					'acsh_id'				=> $ac_receivable,
					'accsh_id'				=> $inc_ac_receivable,
					'bank_or_cash_book_id'	=> $cash_book_id,
					'tran_reference'		=> 'inc_voucher',
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

				// ------ start cash_transaction -------
				$main_cash = 1;
				$cash_mode = 'in';
				$exp_vou_id = '';
				$pay_cash_transaction_data = array(
					'cash_id' 					=> $main_cash,
					'cash_tran_reference_type' 	=> 'inc_voucher',
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
					'bank_tran_reference_type' => 'inc_voucher',
					'bank_tran_for_id' => $form_data['party_id'],
					'bank_tran_for' => 'party',
					'bank_tran_method' => $form_data['vou_pay_by'],
					'bank_client_bank' => '',
					'bank_tran_cheque_no' => $form_data['cheque_number'],
					'bank_tran_cheque_date' => $form_data['cheque_date'],
					'bank_tran_cheque_action' => 'entry',
					'bank_tran_cheque_action_date' => '',
					'cheque_mode' => 'in',
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
				$this->db->insert('am_income_voucher_payments', $form_data);
				$this->db->insert('am_cash_transaction', $pay_cash_transaction_data);

				$transaction_inc_voucher_due_collection_cash['bank_or_cash_book_id']=$this->db->insert_id();
				$this->db->insert('am_transaction',$transaction_inc_voucher_due_collection_cash);
				$this->db->insert('am_transaction',$transaction_inc_voucher_payment_receivable_cr);
			} else {
				$this->db->insert('am_bank_transaction', $bank_payment);
			}
			$this->db->trans_complete();

			// ------transaction status check and action----
			if ($this->db->trans_status() === TRUE) {
				$this->session->set_flashdata('msg', 'Voucher payment added successfully');
				$this->session->set_flashdata('msg_class', 'text-success');
				redirect('Am_income_controller/incomeVoucherCollectionView');
			} else {
				$this->session->set_flashdata('msg', 'Sorry ! Voucher payment unsuccessfull !!');
				$this->session->set_flashdata('msg_class', 'text-danger');
				redirect('Am_income_controller/incomeVoucherCollectionView');
			}
		} else {
			$this->session->set_flashdata('msg', 'Sorry ! voucher add faild.');
			$this->session->set_flashdata('msg_class', 'text-danger');
			redirect('Am_income_controller/incomeVoucherCollectionView');
		}
	}
	//-------------------------------------------------
	// ===============END VOUCHER PAYMENT SETUP=============


}   //----- end class----------
