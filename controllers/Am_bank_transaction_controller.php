<?php
defined('BASEPATH') or exit('No direct script access allowed');
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Am_bank_transaction_controller extends CI_Controller
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
		$this->load->model('Am_bank_transaction_model');
	}

	//-------------------------------------------------
	// ===============START BANK BOOK SETUP=============
	public function bankChequeView()
	{
		$data['bankLists'] = $this->Am_bank_transaction_model->bankList();
		$data['bankCheques'] = $this->Am_bank_transaction_model->bankChequeList();
		// pre($data['bankCheques']);
		$this->load->view('am_bank_transaction/bankChequeView', $data);
	}

	public function bankTransactionSearch()
	{
		$form_data = $this->input->post();

		// pre($form_data);
		//---- start search condition set------
		$where = '';
		$bank_id = $form_data['bank_id_search'];
		$cheque_status = $form_data['cheque_status'];

		!empty($bank_id) ? $where .= 'btrn.bank_id = ' . $bank_id . ' AND ' : '';
		!empty($cheque_status) ? $where .= ' btrn.bank_tran_cheque_action = "' . $cheque_status . '" AND ' : '';
		$where .= 'btrn.bank_id=bank.bank_id';
		//---- end search condition set------

		// pre($where);
		$data['bankLists'] = $this->Am_bank_transaction_model->bankList();
		$data['bankCheques'] = $this->Am_bank_transaction_model->bankChequeList();
		$data['bankChequesSearched'] = $this->Am_bank_transaction_model->bankTransactionListSearched($where);
		$this->load->view('am_bank_transaction/bankChequeView', $data);
	}


	public function bankChequeStatusUpdate()
	{
		$form_data = $this->input->post();
		$bank_tran_id 				= $form_data['bank_tran_id'];
		$bank_tran_reference_id 	= $form_data['bank_tran_reference_id'];
		$cheque_action 				= $form_data['cheque_action'];
		$cheque_action_date 		= $form_data['cheque_action_date'];
		// pre($form_data);

		if ($form_data['cheque_action'] == 'entry') {
			$new_status = 0;
		} elseif ($form_data['cheque_action'] == 'honored') {
			$new_status = 1;
		} elseif ($form_data['cheque_action'] == 'dishonored') {
			$new_status = 2;
		} elseif ($form_data['cheque_action'] == 'cancelled') {
			$new_status = 13;
		}

		$bank_data_update['bank_tran_cheque_action'] = $form_data['cheque_action'];
		$bank_data_update['bank_tran_cheque_action_date'] = $cheque_action_date;
		$bank_data_update['status'] = $new_status;


		// -----start transaction for honored cheque amount---------
		if ($cheque_action == 'honored') {
			//---cheque data collection by id---------
			$data['honored'] = $this->Am_bank_transaction_model->honoredChequeDetails($bank_tran_id, $bank_tran_reference_id);
			foreach ($data['honored'] as $honored) {
				$purc_id 					= $honored->bank_tran_reference_id;
				$reference_type				= $honored->bank_tran_reference_type;
				$bank_tran_for 				= $honored->bank_tran_for;
				$supp_id 					= $honored->bank_tran_for_id;
				$bank_tran_method			= $honored->bank_tran_method;
				$purchase_payment_by_bank	= $honored->bank_cheque_amount;
				$transaction_about			= 'Cheque Honord by bank';
				$old_due					= $honored->purc_inv_due;
			}

			if ($purc_id != 0) {
				$inv_new_due = $old_due - $purchase_payment_by_bank;
			} else {
				$inv_new_due = 0;
			}

			// ---- start if cheque collected from customer--------
			if ($bank_tran_for == 'customer') {
				$_SESSION['sale_inv_id']				= $purc_id;
				$_SESSION['cheque_collection_amount']	= $purchase_payment_by_bank;
				$_SESSION['bank_or_cash_book_id']		= $bank_tran_id;
				if ($this->db->where('bank_tran_id', $bank_tran_id)->update('am_bank_transaction', $bank_data_update)) {
					redirect(base_url('Am_payment_installment_controller/paymentInstallmentView'));
				}
			}
			// ---- end if cheque collected from customer--------


			$supp_due = $this->Am_bank_transaction_model->supplierLastDue($supp_id);
			$supp_old_due 	= !empty($supp_due)?$supp_due[0]->purc_supp_due:0;
			$supp_new_dew 	= $supp_old_due - $purchase_payment_by_bank;

			// pre($supp_new_dew);

			//--- set payment method----
			if ($bank_tran_method == 'Cheque') {
				$ach_id = 2;
				$acsh_id = 4;
				$accsh_id = 18;
			} else {
				$ach_id = 2;
				$acsh_id = 37;
				$accsh_id = 19;
			}
			if ($bank_tran_for == 'supplier') {

				$transaction_data_paid_amount = array(
					'ach_id' => $ach_id, //--Asset--
					'acsh_id' => $acsh_id, //--Cash/Bank/Card payment--
					'accsh_id' => 1,
					'bank_or_cash_book_id'	=> $bank_tran_id,
					'tran_reference' => 'purc_invoice',
					'tran_reference_id' => $purc_id,
					'tran_for' => 'supplier',
					'tran_for_id' => $supp_id,
					'tran_mode' => 'Cr',
					'tran_amount' => $purchase_payment_by_bank,
					'tran_dateE' => $cheque_action_date,
					'tran_details' => $transaction_about,
					'add_date' => date('Y-m-d'),
					'add_time' => date('h:i:s'),
					'add_by' => $this->session->userdata('user_id'),
					'status' => 0,
				);

				$transaction_payable_amount_reduce = array(
					'ach_id' => 3, //--Liabilities--
					'acsh_id' => 7, //--A/C Payable--
					'accsh_id' => 1,
					'bank_or_cash_book_id'	=> $bank_tran_id,
					'tran_reference' => 'purc_invoice',
					'tran_reference_id' => $purc_id,
					'tran_for' => 'supplier',
					'tran_for_id' => $supp_id,
					'tran_mode' => 'Dr',
					'tran_amount' => $purchase_payment_by_bank,
					'tran_dateE' => $cheque_action_date,
					'tran_details' => $transaction_about,
					'add_date' => date('Y-m-d'),
					'add_time' => date('h:i:s'),
					'add_by' => $this->session->userdata('user_id'),
					'status' => 0,
				);

				$payment_purchase_invoice = array(
					'purc_id' => $purc_id,
					'supp_id' => $supp_id,
					'purc_inv_amount' => 0,
					'purc_inv_paid' => $purchase_payment_by_bank,
					'purc_inv_due' => $inv_new_due,
					'purc_supp_due' => $supp_new_dew,
					'purc_inv_date' => $cheque_action_date,
					'pay_purc_remarks' => $transaction_about,
					'add_date' => date('Y-m-d'),
					'add_time' => date('h:i:s'),
					'add_by' => $this->session->userdata('user_id'),
					'status' => 0,
				);
			} elseif ($bank_tran_for == 'party' && $reference_type == 'exp_voucher') {
				// --------voucher payment by bank----------
				$exp_vou_payment = [
					'vou_id'			=> $purc_id,
					'vou_pay_by'		=> 'bank',
					'vou_pay_date'		=> date('Y-m-d'),
					'vou_pay_note'		=> $transaction_about,
					'vou_pay_amount'	=> $purchase_payment_by_bank,
					'add_date' 			=> date('Y-m-d'),
					'add_time' 			=> date('h:i:s'),
					'add_by' 			=> $this->session->userdata('user_id'),
					'status' 			=> 0,
				];

				// ------paid amount by bank-----------
				$asset = 2;
				$cash_ad_bank = 4;
				$main_bank = 18;
				$bank_book_id = '';

				$transaction_exp_voucher_payment_bank = [
					'ach_id'				=> $asset,
					'acsh_id'				=> $cash_ad_bank,
					'accsh_id'				=> $main_bank,
					'bank_or_cash_book_id'	=> $bank_tran_id,
					'tran_reference'		=> $reference_type,
					'tran_reference_id'		=> $purc_id,
					'tran_for'				=> $bank_tran_for,
					'tran_for_id'			=> $supp_id,
					'tran_mode'				=> 'Cr',
					'tran_amount'			=> $purchase_payment_by_bank,
					'tran_dateE'			=> date('Y-m-d'),
					'tran_details'			=> $transaction_about,
					'add_date' 				=> date('Y-m-d'),
					'add_time' 				=> date('h:i:s'),
					'add_by' 				=> $this->session->userdata('user_id'),
					'status' 				=> 0,
				];

				// ------reduce payable amount-----------
				$liabilities = 3;
				$ac_payable = 7;
				$exp_ac_payable = 26;
				$transaction_exp_voucher_payment_payable = [
					'ach_id'				=> $liabilities,
					'acsh_id'				=> $ac_payable,
					'accsh_id'				=> $exp_ac_payable,
					'bank_or_cash_book_id'	=> $bank_tran_id,
					'tran_reference'		=> $reference_type,
					'tran_reference_id'		=> $purc_id,
					'tran_for'				=> $bank_tran_for,
					'tran_for_id'			=> $supp_id,
					'tran_mode'				=> 'Dr',
					'tran_amount'			=> $purchase_payment_by_bank,
					'tran_dateE'			=> date('Y-m-d'),
					'tran_details'			=> $transaction_about,
					'add_date' 				=> date('Y-m-d'),
					'add_time' 				=> date('h:i:s'),
					'add_by' 				=> $this->session->userdata('user_id'),
					'status' 				=> 0,
				];
			} elseif ($bank_tran_for == 'party' && $reference_type == 'inc_voucher') {
				// --------voucher payment by bank----------
				$inc_vou_payment = [
					'vou_id'			=> $purc_id,
					'vou_pay_by'		=> 'bank',
					'vou_pay_date'		=> date('Y-m-d'),
					'vou_pay_note'		=> $transaction_about,
					'vou_pay_amount'	=> $purchase_payment_by_bank,
					'add_date' 			=> date('Y-m-d'),
					'add_time' 			=> date('h:i:s'),
					'add_by' 			=> $this->session->userdata('user_id'),
					'status' 			=> 0,
				];

				// ------paid amount by bank-----------
				$asset = 2;
				$cash_at_bank = 4;
				$main_bank = 18;
				$bank_book_id = '';

				$transaction_inc_voucher_payment_bank = [
					'ach_id'				=> $asset,
					'acsh_id'				=> $cash_at_bank,
					'accsh_id'				=> $main_bank,
					'bank_or_cash_book_id'	=> $bank_tran_id,
					'tran_reference'		=> $reference_type,
					'tran_reference_id'		=> $purc_id,
					'tran_for'				=> $bank_tran_for,
					'tran_for_id'			=> $supp_id,
					'tran_mode'				=> 'Dr',
					'tran_amount'			=> $purchase_payment_by_bank,
					'tran_dateE'			=> date('Y-m-d'),
					'tran_details'			=> $transaction_about,
					'add_date' 				=> date('Y-m-d'),
					'add_time' 				=> date('h:i:s'),
					'add_by' 				=> $this->session->userdata('user_id'),
					'status' 				=> 0,
				];

				// ------reduce payable amount-----------
				$asset = 2;
				$ac_receivable = 3;
				$inc_ac_receivable = 26;
				$transaction_inc_voucher_payment_payable = [
					'ach_id'				=> $asset,
					'acsh_id'				=> $ac_receivable,
					'accsh_id'				=> $inc_ac_receivable,
					'bank_or_cash_book_id'	=> $bank_tran_id,
					'tran_reference'		=> $reference_type,
					'tran_reference_id'		=> $purc_id,
					'tran_for'				=> $bank_tran_for,
					'tran_for_id'			=> $supp_id,
					'tran_mode'				=> 'Cr',
					'tran_amount'			=> $purchase_payment_by_bank,
					'tran_dateE'			=> date('Y-m-d'),
					'tran_details'			=> $transaction_about,
					'add_date' 				=> date('Y-m-d'),
					'add_time' 				=> date('h:i:s'),
					'add_by' 				=> $this->session->userdata('user_id'),
					'status' 				=> 0,
				];
			}

			$this->db->trans_start();
				if ($bank_tran_for == 'supplier') {
					$this->db->where('bank_tran_id', $bank_tran_id)->update('am_bank_transaction', $bank_data_update);
					$this->db->insert('am_payment_purchase_invoices', $payment_purchase_invoice);
					$this->db->insert('am_transaction', $transaction_data_paid_amount);
					$this->db->insert('am_transaction', $transaction_payable_amount_reduce);
				} elseif ($bank_tran_for == 'party' && $reference_type == 'exp_voucher') {
					$this->db->where('bank_tran_id', $bank_tran_id)->update('am_bank_transaction', $bank_data_update);
					$this->db->insert('am_expenditure_voucher_payments', $exp_vou_payment);
					$this->db->insert('am_transaction', $transaction_exp_voucher_payment_bank);
					$this->db->insert('am_transaction', $transaction_exp_voucher_payment_payable);
				} elseif ($bank_tran_for == 'party' && $reference_type == 'inc_voucher') {
					$this->db->where('bank_tran_id', $bank_tran_id)->update('am_bank_transaction', $bank_data_update);
					$this->db->insert('am_income_voucher_payments', $inc_vou_payment);
					$this->db->insert('am_transaction', $transaction_inc_voucher_payment_bank);
					$this->db->insert('am_transaction', $transaction_inc_voucher_payment_payable);
				}
			$this->db->trans_complete();

			if ($this->db->trans_status() == TRUE) {
				$this->session->set_flashdata('msg', 'Customer current status successfully');
				$this->session->set_flashdata('msg_class', 'text-success');
				redirect('Am_bank_transaction_controller/bankChequeView');
			} else {
				$this->session->set_flashdata('msg', 'Sorry ! Customer current status updated unsuccessfull.');
				$this->session->set_flashdata('msg_class', 'text-danger');
				redirect('Am_bank_transaction_controller/bankChequeView');
			}
		} else {
			if ($this->db->where('bank_tran_id', $bank_tran_id)->update('am_bank_transaction', $bank_data_update)) {
				$this->session->set_flashdata('msg', 'Customer current status successfully');
				$this->session->set_flashdata('msg_class', 'text-success');
				redirect('Am_bank_transaction_controller/bankChequeView');
			} else {
				$this->session->set_flashdata('msg', 'Sorry ! Customer current status updated unsuccessfull.');
				$this->session->set_flashdata('msg_class', 'text-danger');
				redirect('Am_bank_transaction_controller/bankChequeView');
			}
		}
	}

	// -----end transaction for honored cheque amount---------

	// =============END BANK BOOK SETUP===========
	//-------------------------------------------


}   //----- end class----------
