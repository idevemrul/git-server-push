<?php
defined('BASEPATH') or exit('No direct script access allowed');
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Am_payment_installment_controller extends CI_Controller
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
		$this->load->model('Am_feed_data_model');
		$this->load->model('Am_payment_installment_model');
		$this->load->model('Am_installment_model');
		$this->load->model('Am_purchase_model');
		$this->load->model('Am_bank_transaction_model');
		$this->load->model('Am_sale_model');
	}
	// ------------------------------------------------------
	// ======START INSTALLEMNT PAYMENT AND RE-SCHEDULE=======
	public function paymentInstallmentView()
	{
		$data['saleInvLists'] = $this->Am_payment_installment_model->saleInvoiceList();
		// $data['customers']=$this->Am_payment_installment_model->customerList();
		// $data['references']=$this->Am_sale_model->referenceList();
		$this->load->view('am_payment/paymentInstallmentView', $data);
	}

	// ------- ajax data collection by jQuery-----
	public function saleInvoiceDetails()
	{
		$sale_inv_id = $_POST['sale_inv_id'];
		$data['items'] = $this->Am_payment_installment_model->saleInvoiceDetails($sale_inv_id);
		$data['installmentsPast'] = $this->Am_payment_installment_model->saleInvoiceInstallmentPast($sale_inv_id);
		echo json_encode($data);
	}

	// ----- START INSTALLMENT AND LATE FEE-COLLECTION  ------
	public function installmentPayment()
	{
		$this->db->trans_strict(FALSE);
		$form_data = $this->input->post();
		// pre($form_data);
		//====START SOURCE VALID OR NOT=========
		if (!isset($form_data['re_schedule_cypher']) || isset($form_data['payment_cypher'])) {
			$data['saleInvLists'] = $this->Am_payment_installment_model->saleInvoiceList();
			$this->session->set_flashdata('msg', 'Sorry ! Data form not valid source.');
			$this->session->set_flashdata('msg_class', 'text-danger');
			$this->load->view('am_payment/paymentInstallmentView', $data);
		}
		//====END SOURCE VALID OR NOT=========

		// =====START PAYMENT OF INSTALLMENT ========
		if (isset($form_data['payment_cypher']) && $form_data['payment_cypher'] == 'paymentCypher') {
			$num = count($form_data['late_fine_pay']);
			if ($num > 0) {
				// $form_data=$this->input->post();

				// --- set as lical variable--------
				$sale_inv_id 			= $form_data['sale_inv_id'];
				$customer_id			= $form_data['customer_id'];
				$install_pay_date 		= $form_data['install_pay_date'];
				$install_pay_amount 	= $form_data['install_pay_amount'];
				$bank_or_cash_book_id 	= isset($_SESSION['bank_or_cash_book_id']) ? $_SESSION['bank_or_cash_book_id'] : 0;
				$total_late_day_input	= 0;
				$total_late_fine_input	= 0;
				$total_late_fine_pay	= 0;
				$total_total_pay_input	= 0;

				// ----- clossing data collection------
				$install_clossing_payment_cypher = '';
				$clossing_note = '';

				// pre($num);

				if (isset($form_data['install_clossing_payment_cypher'])) {
					$install_clossing_payment_cypher 	= isset($form_data['install_clossing_payment_cypher']) ? $form_data['install_clossing_payment_cypher'] : 0;
					$install_clossing_invoice_due		= $form_data['install_clossing_invoice_due'];
					$install_clossing_interst_waiver	= $form_data['install_clossing_interst_waiver'];
					$install_clossing_discount			= $form_data['install_clossing_discount'];
					$install_clossing_due				= $form_data['install_clossing_due'];
					$install_clossing_payment			= $form_data['install_clossing_payment'];
					$clossing_note						= 'Clossing payment';
				}

				// pre($form_data);
				// =======START DATA COLLECTION BLOCK========
				// ---collect payemnt batch number------
				$pay_total_batch_nos = $this->db->select('MAX(pay_total_batch_no) pay_total_batch_no')
					->get('am_payment_sale_invoice_installments_new')->result();
				foreach ($pay_total_batch_nos as $pay_total_batch_no) {
					$pay_total_batch_no = $pay_total_batch_no->pay_total_batch_no + 1;
				}
				// pre($pay_total_batch_no);

				for ($i = 0; $i < $num; $i++) {
					$total_late_day_input	+= (int)$form_data['late_day_input'] ? (int)$form_data['late_day_input'] : 0;
					$total_late_fine_input	+= $form_data['late_fine_input'][$i] ? $form_data['late_fine_input'][$i] : 0;
					$total_late_fine_pay	+= $form_data['late_fine_pay'][$i] ? $form_data['late_fine_pay'][$i] : 0;

					// ------p[1] data making for insert am_payment_sale_invoice_installments_new --------
					$late_fine_waiv = $form_data['late_fine_input'][$i] - $form_data['late_fine_pay'][$i];
					$install_paying[] = array(
						'pay_total_batch_no' 	=> $pay_total_batch_no,
						'sale_inv_id' 			=> $form_data['sale_inv_id'],
						'sale_install_id' 		=> $form_data['sale_install_id'][$i],
						'bank_or_cash_book_id' 	=> $bank_or_cash_book_id,
						'late_day' 				=> $form_data['late_day_input'][$i],
						'late_fine' 			=> $form_data['late_fine_input'][$i],
						'late_fine_waiv' 		=> $late_fine_waiv,
						'total_paid' 			=> $form_data['total_pay_input'][$i],
						'pay_install_date' 		=> $install_pay_date,
						'add_note'				=> $clossing_note,
						'add_date' 				=> date('Y-m-d'),
						'add_time' 				=> date('h:i:s'),
						'add_by' 				=> $this->session->userdata('user_id'),
						'status' 				=> 0,
					);

					// ------p[2] data making for update am_sale_invoice_installments------
					if ($form_data['paying_installDue'][$i] == 0) {
						$installment_full_paid[] = [
							'sale_install_id' 		=> $form_data['sale_install_id'][$i],
							'status' 				=> 1,
						];
					}
				}


				// ----p[3] data making for transation table late fine collection--------

				/*// p[3.1]==========================================
								// head:: income->interest->installment_late_fine
								// ach_id 	=income 				=id(4)
								// acsh_id 	=interest 			 	=id(9)
								// accsh_id =installment_late_fine 	=id(20)
							// ==========================================*/

				$late_fine_amount[] = array(
					'ach_id ' 				=> 4,
					'acsh_id ' 				=> 9,
					'accsh_id' 				=> 20,
					'bank_or_cash_book_id'	=> $bank_or_cash_book_id,
					'tran_reference' 		=> 'sale_invoice',
					'tran_reference_id' 	=> $sale_inv_id,
					'tran_for' 				=> 'customer',
					'tran_for_id' 			=> $customer_id,
					'tran_mode' 			=> 'Cr',
					'tran_amount' 			=> $total_late_fine_input,
					'tran_dateE' 			=> $install_pay_date,
					'tran_details' 			=> 'Installment Late fine',
					'add_date' 				=> date('Y-m-d'),
					'add_time' 				=> date('h:i:s'),
					'add_by' 				=> $this->session->userdata('user_id'),
					'status' 				=> 0,
				);
				/*// p[3.2]==========================================
								//-- late fine collection cash---------
								// head:: asset->cash_at_hand->main_cash
								// ach_id 	=asset 				=id(2)
								// acsh_id 	=cash_at_hand	 	=id(5)
								// accsh_id =main_cash 		 	=id(17)
							// ==========================================*/
				if ($bank_or_cash_book_id > 0) {
					$ach_id = 2;
					$acsh_id = 4;
					$accsh_id = 18;
					$tran_details = "Late fine collection by bank";
				} else {
					$ach_id = 2;
					$acsh_id = 5;
					$accsh_id = 17;
					$tran_details = "Late fine collection by cash";
				}
				$status = 0;
				if ($install_clossing_payment_cypher == "installClossingPaymentChpher") {
					$tran_details = "Late fine closing collection by cash";
					$status = 7;
				}
				$late_fine_cash_paid[] = array(
					'ach_id ' 				=> $ach_id,
					'acsh_id ' 				=> $acsh_id,
					'accsh_id' 				=> $accsh_id,
					'bank_or_cash_book_id'	=> $bank_or_cash_book_id,
					'tran_reference' 		=> 'sale_invoice',
					'tran_reference_id' 	=> $sale_inv_id,
					'tran_for' 				=> 'customer',
					'tran_for_id' 			=> $customer_id,
					'tran_mode' 			=> 'Dr',
					'tran_amount' 			=> $total_late_fine_pay,
					'tran_dateE' 			=> $install_pay_date,
					'tran_details' 			=> $tran_details,
					'add_date' 				=> date('Y-m-d'),
					'add_time' 				=> date('h:i:s'),
					'add_by' 				=> $this->session->userdata('user_id'),
					'status' 				=> $status,
				);

				// ------ start cash_transaction -------
				$main_cash = 1;
				$cheque_mode = 'in';

				$pay_cash_transaction_data_latefine_collect_amount[] = array(
					'cash_id' 					=> $main_cash,
					'cash_tran_reference_type' 	=> 'sale_invoice',
					'cash_tran_reference_id'	=> $sale_inv_id,
					'cash_tran_for' 			=> 'customer',
					'cash_tran_for_id'			=> $customer_id,
					'cash_tran_dateE' 			=> $install_pay_date,
					'cash_mode'					=> $cheque_mode,
					'cash_cheque_amount' 		=> $total_late_fine_pay,
					'cash_note'					=> $tran_details,
					'add_date' 					=> date('Y-m-d'),
					'add_time' 					=> date('h:i:s'),
					'add_by' 					=> $this->session->userdata('user_id'),
					'status' 					=> 0,
				);


				/*// p[3.3]==========================================
								// head:: expenditure->waiver->Late_fine_Waiver
								// ach_id 	=expenditure		=id(5)
								// acsh_id 	=waiver 		 	=id(39)
								// accsh_id =Late_fine_Waiver 	=id(21)
							// ==========================================*/
				$total_latefine_waiver = ($total_late_fine_input - $total_late_fine_pay);
				$late_fine_waiver[] = array(
					'ach_id ' 				=> 5,
					'acsh_id ' 				=> 39,
					'accsh_id' 				=> 21,
					'bank_or_cash_book_id'	=> $bank_or_cash_book_id,
					'tran_reference' 		=> 'sale_invoice',
					'tran_reference_id' 	=> $sale_inv_id,
					'tran_for' 				=> 'customer',
					'tran_for_id' 			=> $customer_id,
					'tran_mode' 			=> 'Dr',
					'tran_amount' 			=> $total_latefine_waiver,
					'tran_dateE' 			=> $install_pay_date,
					'tran_details' 			=> 'Late_fine_Waiver',
					'add_date' 				=> date('Y-m-d'),
					'add_time' 				=> date('h:i:s'),
					'add_by' 				=> $this->session->userdata('user_id'),
					'status' 				=> 0,
				);

				if ($install_clossing_payment_cypher == "installClossingPaymentChpher") {
					$transaction['late_fee_collection'] = array_merge($late_fine_amount, $late_fine_waiver);
				} else {
					$transaction['late_fee_collection'] = array_merge($late_fine_amount, $late_fine_cash_paid, $late_fine_waiver);
				}

				// -----p[4----data making for cash payment in instllment payment=======
				/*// p[4.1]==========================================
								//-- installment due collection----
								// head:: asset->account_receivable->sale A/C receivable
								// ach_id 	=Asset					=id(2)
								// acsh_id 	=account_receivable 	=id(3)
								// accsh_id =sale A/C receivable 	=id(12)
							// ==============================================*/
				$total_installment_paid = $install_pay_amount - $total_late_fine_pay;
				$account_receivable_cr[] = array(
					'ach_id ' 				=> 2,
					'acsh_id ' 				=> 3,
					'accsh_id' 				=> 12,
					'bank_or_cash_book_id'	=> $bank_or_cash_book_id,
					'tran_reference' 		=> 'sale_invoice',
					'tran_reference_id' 	=> $sale_inv_id,
					'tran_for' 				=> 'customer',
					'tran_for_id' 			=> $customer_id,
					'tran_mode' 			=> 'Cr',
					'tran_amount' 			=> $total_installment_paid,
					'tran_dateE' 			=> $install_pay_date,
					'tran_details' 			=> 'Account Receivable',
					'add_date' 				=> date('Y-m-d'),
					'add_time' 				=> date('h:i:s'),
					'add_by' 				=> $this->session->userdata('user_id'),
					'status' 				=> 0,
				);
				//--- head:: asset(2)->cash_at_hand(5)->main_cash(17)---
				if ($bank_or_cash_book_id > 0) {
					$tran_details = "Due installent collection by bank";
				} else {
					$tran_details = "Due installent collection by cash";
				}
				$status = 0;
				if ($install_clossing_payment_cypher == "installClossingPaymentChpher") {
					$total_installment_paid = $install_clossing_payment;
					$tran_details = "Due installent clossing collection by cash";
					$status = 7;
				}
				$cash_collection_ac_recv_dr[] = array(
					'ach_id ' 				=> $ach_id,
					'acsh_id ' 				=> $acsh_id,
					'accsh_id' 				=> $accsh_id,
					'bank_or_cash_book_id'	=> $bank_or_cash_book_id,
					'tran_reference' 		=> 'sale_invoice',
					'tran_reference_id' 	=> $sale_inv_id,
					'tran_for' 				=> 'customer',
					'tran_for_id' 			=> $customer_id,
					'tran_mode' 			=> 'Dr',
					'tran_amount' 			=> $total_installment_paid,
					'tran_dateE' 			=> $install_pay_date,
					'tran_details' 			=> $tran_details,
					'add_date' 				=> date('Y-m-d'),
					'add_time' 				=> date('h:i:s'),
					'add_by' 				=> $this->session->userdata('user_id'),
					'status' 				=> $status,
				);
				$transaction['due_collection'] = array_merge($account_receivable_cr, $cash_collection_ac_recv_dr);
				// pre($transaction['due_collection']);
				$status = 0;
				if ($install_clossing_payment_cypher == "installClossingPaymentChpher") {
					$status = 7;
					$install_clossing_interst_waiver_dr[] = array(
						'ach_id ' 				=> 5,
						'acsh_id ' 				=> 39,
						'accsh_id' 				=> 27,
						'bank_or_cash_book_id'	=> $bank_or_cash_book_id,
						'tran_reference' 		=> 'sale_invoice',
						'tran_reference_id' 	=> $sale_inv_id,
						'tran_for' 				=> 'customer',
						'tran_for_id' 			=> $customer_id,
						'tran_mode' 			=> 'Dr',
						'tran_amount' 			=> $install_clossing_interst_waiver,
						'tran_dateE' 			=> $install_pay_date,
						'tran_details' 			=> 'Installment clossing interst waiver',
						'add_date' 				=> date('Y-m-d'),
						'add_time' 				=> date('h:i:s'),
						'add_by' 				=> $this->session->userdata('user_id'),
						'status' 				=> $status,
					);
					$install_clossing_discount_dr[] = array(
						'ach_id ' 				=> 5,
						'acsh_id ' 				=> 40,
						'accsh_id' 				=> 28,
						'bank_or_cash_book_id'	=> $bank_or_cash_book_id,
						'tran_reference' 		=> 'sale_invoice',
						'tran_reference_id' 	=> $sale_inv_id,
						'tran_for' 				=> 'customer',
						'tran_for_id' 			=> $customer_id,
						'tran_mode' 			=> 'Dr',
						'tran_amount' 			=> $install_clossing_discount,
						'tran_dateE' 			=> $install_pay_date,
						'tran_details' 			=> 'Installment clossing discount',
						'add_date' 				=> date('Y-m-d'),
						'add_time' 				=> date('h:i:s'),
						'add_by' 				=> $this->session->userdata('user_id'),
						'status' 				=> $status,
					);
				}

				// ------ start cash_transaction -------
				$main_cash = 1;
				$cash_mode = 'in';

				$pay_cash_transaction_data_install_collect_amount[] = array(
					'cash_id' 					=> $main_cash,
					'cash_tran_reference_type' 	=> 'sale_invoice',
					'cash_tran_reference_id'	=> $sale_inv_id,
					'cash_tran_for' 			=> 'customer',
					'cash_tran_for_id'			=> $customer_id,
					'cash_tran_dateE' 			=> $install_pay_date,
					'cash_mode'					=> $cash_mode,
					'cash_cheque_amount' 		=> $total_installment_paid,
					'cash_note'					=> 'Due Invoice Cash collection',
					'add_date' 					=> date('Y-m-d'),
					'add_time' 					=> date('h:i:s'),
					'add_by' 					=> $this->session->userdata('user_id'),
					'status' 					=> 0,
				);


				// =======END DATA COLLECTION BLOCK========

				// =======START DATA EXECUTION BLOCK======
				$last_installment_pay_id = 0;
				$this->db->trans_start();
				// ----[1] insert installment payments to am_payment_sale_invoice_installments_new-----
				if (isset($install_paying)) {
					$this->db->insert_batch('am_payment_sale_invoice_installments_new', $install_paying);
					$last_installment_pay_id = $this->db->insert_id();
				}

				// ----[2]update fully paid installment status=1 tbl am_sale_invoice_installments-----
				if (isset($installment_full_paid)) {
					$this->db->update_batch('am_sale_invoice_installments', $installment_full_paid, 'sale_install_id');
				}

				// ----[3] insert late fince & collection on tbl_transaction-----
				if ($total_late_fine_input > 0) {
					if ($bank_or_cash_book_id <= 0 && $install_clossing_payment_cypher != "installClossingPaymentChpher") {
						$this->db->insert_batch('am_cash_transaction', $pay_cash_transaction_data_latefine_collect_amount);
						$am_cash_transaction_id = $this->db->insert_id();
						$transaction['late_fee_collection'][0]['bank_or_cash_book_id'] = $am_cash_transaction_id;
						$transaction['late_fee_collection'][1]['bank_or_cash_book_id'] = $am_cash_transaction_id;
					}
					$this->db->insert_batch('am_transaction', $transaction['late_fee_collection']);
				}

				// ----[4] insert due collection on tbl_transaction-----
				if ($total_installment_paid > 0) {
					if ($bank_or_cash_book_id <= 0) {
						$this->db->insert_batch('am_cash_transaction', $pay_cash_transaction_data_install_collect_amount);
						$am_cash_transaction_id = $this->db->insert_id();
						$transaction['due_collection'][0]['bank_or_cash_book_id'] = $am_cash_transaction_id;
						$transaction['due_collection'][1]['bank_or_cash_book_id'] = $am_cash_transaction_id;
					}
					$this->db->insert_batch('am_transaction', $transaction['due_collection']);
					if ($install_clossing_payment_cypher == "installClossingPaymentChpher") {
						$this->db->insert_batch('am_transaction', $install_clossing_interst_waiver_dr);
						$this->db->insert_batch('am_transaction', $install_clossing_discount_dr);
					}
				}
				$this->db->trans_complete();
				unset($_SESSION['bank_or_cash_book_id']);

				// =======END DATA EXECUTION BLOCK========


				// ----- method execution status -------
				if ($this->db->trans_status() != false) {
					if (isset($_SESSION['bank_or_cash_book_id']) && $_SESSION['bank_or_cash_book_id'] != '') {
						$this->session->set_flashdata('msg', 'Installment collection by cheque/card successfully');
						$this->session->set_flashdata('msg_class', 'text-success');
						unset($_SESSION['bank_or_cash_book_id']);
						redirect('Am_bank_transaction_controller/bankChequeView');
					} else {
						$this->session->set_flashdata('msg', 'Installment collection successfully');
						$this->session->set_flashdata('msg_class', 'text-success');
						if ($last_installment_pay_id != 0) {
							$pay_total_batch_nos = $this->db->select('pay_total_batch_no')->where('pay_sale_inv_due_id', $last_installment_pay_id)->get('am_payment_sale_invoice_installments_new')->result();
							foreach ($pay_total_batch_nos as $pay_total_batch_no) {
								$last_pay_total_batch_no = $pay_total_batch_no->pay_total_batch_no;
							}
							redirect('Am_payment_installment_controller/installmentCollectionBatchReceipt/' . $last_pay_total_batch_no);
						} else {
							redirect('Am_payment_installment_controller/installmentCollectionReceiptList');
						}
					}
				} else {
					$this->session->set_flashdata('msg', 'Sorry ! Unit add unsuccessfull.');
					$this->session->set_flashdata('msg_class', 'text-danger');
					$this->load->view('am_payment/paymentInstallmentView', $data);
				}
			} else {
				$this->session->set_flashdata('msg', 'Sorry ! You not paing anything.');
				$this->session->set_flashdata('msg_class', 'text-danger');
				$this->load->view('am_payment/paymentInstallmentView', $data);
			}
		}
		// =====END PAYMENT OF INSTALLMENT ========
		// ========================================


		// ========START INCREASE INSTALLMENT==========
		if (isset($form_data['re_schedule_cypher']) && $form_data['re_schedule_cypher'] == 'reScheduleCypher') {
			$sale_inv_id 	= $form_data['sale_inv_id'];
			$customer_id	= $form_data['customer_id'];
			// ------DATA COLLECTION-----------
			// ----P[1]re-schedule interest insert to installment pay table------
			$re_schedule_interest[] = [
				'sale_inv_id' 			=> $form_data['sale_inv_id'],
				're_schedule_interest' 	=> $form_data['reschedule_interest'],
				're_schedule_date' 		=> $form_data['reschedule_date'],
				'add_note' 				=> 'Re_schedule_interest',
				'add_date' 				=> date('Y-m-d'),
				'add_time' 				=> date('h:i:s'),
				'add_by' 				=> $this->session->userdata('user_id'),
				'status'				 => 0,
			];

			$reschedule_date = $form_data['reschedule_date'];

			// -----p[2]----data making for cash payment in instllment payment=======
			/*// p[5.1]==========================================
							//-- installment re-schedule interest ----
							// head:: income->interest->Reschedule_interest
							// ach_id 	=income					=id(4)
							// acsh_id 	=interest 				=id(9)
							// accsh_id =Reschedule_interest 	=id(22)
						// ==============================================*/

			$re_schedule_interest_cr[] = array(
				'ach_id ' 				=> 4,
				'acsh_id ' 				=> 9,
				'accsh_id' 				=> 22,
				'tran_reference' 		=> 'sale_invoice',
				'tran_reference_id' 	=> $sale_inv_id,
				'tran_for' 				=> 'customer',
				'tran_for_id' 			=> $customer_id,
				'tran_mode' 			=> 'Cr',
				'tran_amount' 			=> $form_data['reschedule_interest'],
				'tran_dateE' 			=> $reschedule_date,
				'tran_details' 			=> 'Reschedule interest',
				'add_date' 				=> date('Y-m-d'),
				'add_time' 				=> date('h:i:s'),
				'add_by' 				=> $this->session->userdata('user_id'),
				'status' 				=> 0,
			);
			// head:: asset(2)->A/C_receivable(3)->sale_AC_receivable(12)
			$re_schedule_interest_Dr[] = array(
				'ach_id ' 				=> 2,
				'acsh_id ' 				=> 3,
				'accsh_id' 				=> 12,
				'tran_reference' 		=> 'sale_invoice',
				'tran_reference_id' 	=> $sale_inv_id,
				'tran_for' 				=> 'customer',
				'tran_for_id' 			=> $customer_id,
				'tran_mode' 			=> 'Dr',
				'tran_amount' 			=> $form_data['reschedule_interest'],
				'tran_dateE' 			=> $reschedule_date,
				'tran_details' 			=> 'Reschedule interest Due',
				'add_date' 				=> date('Y-m-d'),
				'add_time' 				=> date('h:i:s'),
				'add_by' 				=> $this->session->userdata('user_id'),
				'status' 				=> 0,
			);
			$transaction['reschedule_interest'] = array_merge($re_schedule_interest_cr, $re_schedule_interest_Dr);
			// ----p[3] partial payment installment value update as per paid amount-------
			if (isset($form_data['partial_paid_install_id'])) {
				$partial_paid_id_update[] = [
					'sale_install_id' 		=> $form_data['partial_paid_install_id'],
					'sale_inst_amount' 		=> $form_data['partial_re_inst_amount'],
					'status' 				=> 2,
				];
			}

			// ----P[4]fully due installment update for show as cancell by status change--------
			if (isset($form_data['full_due_id'])) {
				// --- extract full_due_id as array-----	
				$a = $form_data['full_due_id'];
				foreach ($a as $b) {
					(string)$b;
				}

				$full_due_id_all = explode(",", $b);
				// print_r($full_due_id_all);
				$full_due_id_count = count($full_due_id_all);
				for ($i = 0; $i < $full_due_id_count; $i++) {
					$full_due_id = $full_due_id_all[$i];
					if ($full_due_id == 0) {
						break;
					}
					$full_due_id_update[] = [
						'sale_install_id' 	=> $full_due_id,
						'status' 			=> 13,
					];
				}
			}

			// ------P[5] new installment insert-----------
			$new_inst_count = count($form_data['re_inst_date']);
			if ($new_inst_count > 0) {
				for ($i = 0; $i < $new_inst_count; $i++) {
					$sale_inst_number = $i + 1;
					$re_inst_date = $form_data['re_inst_date'][$i];
					$re_inst_date = date('Y-m-d', strtotime($re_inst_date));
					$sale_inst_schedule = $form_data['installment_schedule'] + 1;

					$new_installment_insert[] = [
						'sale_inv_id' 			=> $form_data['sale_inv_id'],
						'sale_inst_date' 		=> $re_inst_date,
						'sale_inst_amount' 		=> $form_data['re_inst_amount'][$i],
						'sale_inst_number' 		=> $sale_inst_number,
						'sale_inst_quantity' 	=> $form_data['reschedule_inst_quantity'],
						'sale_inst_schedule' 	=> $sale_inst_schedule,
						'add_date' 				=> date('Y-m-d'),
						'add_time' 				=> date('h:i:s'),
						'add_by' 				=> $this->session->userdata('user_id'),
						'status' 				=> 0,
					];
				}
			}
			// -----END DATA COLLECTION-----------

			// -----START DATA EXECUTION----------
			$this->db->trans_start();
			if (isset($re_schedule_interest)) {
				$this->db->insert_batch('am_re_installment_interest', $re_schedule_interest);
				$this->db->insert_batch('am_transaction', $transaction['reschedule_interest']);
			}

			if (isset($partial_paid_id_update)) {
				$this->db->update_batch('am_sale_invoice_installments', $partial_paid_id_update, 'sale_install_id');
			}

			if (isset($full_due_id_update)) {
				$this->db->update_batch('am_sale_invoice_installments', $full_due_id_update, 'sale_install_id');
			}

			if (isset($new_installment_insert)) {
				$this->db->insert_batch('am_sale_invoice_installments', $new_installment_insert);
			}
			$this->db->trans_complete();

			// ----message forwording on transaction status-------
			if ($this->db->trans_start() == true) {
				$this->session->set_flashdata('msg', 'Your installment re-scheduled successfully');
				$this->session->set_flashdata('msg_class', 'text-success');
				redirect('Am_installment_controller/installmentView');
			} else {
				$this->session->set_flashdata('msg', 'Sorry ! re-schedule unsuccessfull.');
				$this->session->set_flashdata('msg_class', 'text-danger');
				$this->load->view('am_payment/paymentInstallmentView', $data);
			}
			// -----END START DATA EXECUTION----------
		} //---end re_schedule_cypher--------
		// ========END INCREASE INSTALLMENT==========
	} //----public function installmentPayment(){---------	
	// ======END INSTALLEMNT PAYMENT AND RE-SCHEDULE=======
	// ----------------------------------------------------

	// ----------START INSTALLMENT DUE COLLECTION-----------------
	// ===========================================================
	public function installmentDueCollectingView()
	{
		// $data['installments']=$this->Am_installment_model->installmentPaidList();
		$data['bankCheques'] = $this->Am_bank_transaction_model->bankChequeListCustomer();

		$data['saleInvLists'] = $this->Am_payment_installment_model->saleInvoiceList();
		$data['bankAcLists'] = $this->Am_feed_data_model->bankList();
		// $data['distinctPaymentPruchaseInvoiceLists']=$this->Am_purchase_model->distinctPaymentPruchaseInvoiceList();
		$this->load->view('am_installment/installmentDueCollectingView', $data);
	}

	public function installmentDueCollectingSearch()
	{
		$form_data = $this->input->post();

		// pre($form_data);
		$bank_id = $form_data['bank_id_search'];
		$cheque_status = $form_data['cheque_status'];

		if (!empty($bank_id) and !empty($cheque_status)) {
			$data['bankChequesSearched'] = $this->Am_bank_transaction_model->bankTransactionListSearched($bank_id, $cheque_status);
		} elseif (empty($bank_id) and empty($cheque_status)) {
			$data['bankChequesSearched'] = $this->Am_bank_transaction_model->bankChequeList();
		} elseif (empty($cheque_status) and !empty($bank_id)) {
			$data['bankChequesSearched'] = $this->Am_bank_transaction_model->bankTransactionListSearchedA($bank_id);
		} elseif (empty($bank_id) and !empty($cheque_status)) {
			$data['bankChequesSearched'] = $this->Am_bank_transaction_model->bankTransactionListSearchedB($cheque_status);
		}

		$data['bankCheques'] = $this->Am_bank_transaction_model->bankChequeList();
		$data['saleInvLists'] = $this->Am_payment_installment_model->saleInvoiceList();
		$data['bankAcLists'] = $this->Am_purchase_model->bankAcLists();
		$this->load->view('am_installment/installmentDueCollectingView', $data);
	}

	// ----- DUE PAYMENT PAID---------
	public function customerDueCollection()
	{
		$form_data = $this->input->post();
		$due_coll_method = $form_data['due_coll_method'];
		$due_coll_amount = $form_data['due_coll_amount'];
		// pre($form_data);

		// ------ start bank_transaction_entry CHEQUE LISTING invoice-------
		if (($due_coll_method == 'Cheque' || $due_coll_method == 'Card') && $due_coll_amount > 0) {
			// pre($form_data);
			$collect_cheque_to_bank = array(
				'bank_id'						=> $form_data['due_coll_bank_id'],
				'bank_tran_reference_id'		=> $form_data['sale_inv_id'],
				'bank_tran_reference_type'		=> 'sale_invoice',
				'bank_tran_for_id'				=> $form_data['inv_customer_id'],
				'bank_tran_for'					=> 'customer',
				'bank_tran_method'				=> $form_data['due_coll_method'],
				'bank_client_bank'				=> $form_data['client_bank_name'],
				'bank_tran_cheque_no'			=> $form_data['due_coll_chq_number'],
				'bank_tran_cheque_date'			=> $form_data['due_coll_chq_mature_date'],
				'bank_tran_cheque_action'		=> 'entry',
				'bank_tran_cheque_action_date'	=> null,
				'cheque_mode'					=> 'in',
				'bank_cheque_amount'			=> $form_data['due_coll_amount'],
				'add_date'						=> date('Y-m-d'),
				'add_time'						=> date('h:i:s'),
				'add_by'						=> $this->session->userdata('user_id'),
				'status'						=> 0,
			);

			if ($this->db->insert('am_bank_transaction', $collect_cheque_to_bank)) {
				$this->session->set_flashdata('msg', 'Due collection by ' . $due_coll_method . ' successfull');
				$this->session->set_flashdata('msg_class', 'text-success');
				redirect('Am_payment_installment_controller/installmentDueCollectingView');
			} else {
				$this->session->set_flashdata('msg', 'Sorry due collection by ' . $due_coll_method . ' unsuccessfull.');
				$this->session->set_flashdata('msg_class', 'text-danger');
				redirect('Am_payment_installment_controller/installmentDueCollectingView');
			}
		} else {
			$this->session->set_flashdata('msg', 'Sorry due collection by ' . $due_coll_method . ' unsuccessfull.');
			$this->session->set_flashdata('msg_class', 'text-danger');
			redirect('Am_payment_installment_controller/installmentDueCollectingView');
		}
	} //---end method--------

	// ----------END INSTALLMENT DUE COLLECTION-------------------
	// ===========================================================

	//---------------------------------------------------------------
	// ========START INSTALLMENT COLLECTION RECEIPT LIST=============
	public function installmentCollectionReceiptList()
	{
		$data['saleIds'] = $this->Am_installment_model->payInstallmentIdAndChasisNoCollection();
		$data['collectionReceipts'] = $this->Am_payment_installment_model->installmentCollectionReceiptList();
		$this->load->view('am_installment/installmentCollectionReceiptListView.php', $data);
	}
	// ========END INSTALLMENT COLLECTION RECEIPT LIST=============
	//-------------------------------------------------------------

	//---------------------------------------------------------------
	// ========START INSTALLMENT COLLECTION RECEIPT SEARCH=============
	public function installmentCollectionReceiptListSearch()
	{
		$sale_inv_id = $this->input->post('sale_inv_id');
		$data['saleIds'] = $this->Am_installment_model->payInstallmentIdAndChasisNoCollection();
		$data['collectionReceiptsSearch'] = $this->Am_payment_installment_model->installmentCollectionReceiptListSearch($sale_inv_id);
		// pre($data['collectionReceiptsSearch']);

		$this->load->view('am_installment/installmentCollectionReceiptListView.php', $data);
	}
	// ========END INSTALLMENT COLLECTION RECEIPT SEARCH=============
	//-------------------------------------------------------------

	//---------------------------------------------------------------
	// ========START INSTALLMENT COLLECTION RECEIPT VIEW=============
	public function installmentCollectionReceipt()
	{
		$collection_id = $this->uri->segment(3);
		$data['collectionReceipts'] = $this->Am_payment_installment_model->installmentCollectionReceipt($collection_id);
		if (empty($data['collectionReceipts'])) {
			redirect('Am_payment_installment_controller/installmentCollectionReceiptList');
		}
		$inst_id = $data['collectionReceipts'][0]->sale_install_id;
		$data['installmentTotalCollections'] = $this->Am_payment_installment_model->installmentTotalCollection($inst_id, $collection_id);

		$inv_id = $data['collectionReceipts'][0]->sale_inv_id;
		$data['invoiceTotalCollections'] = $this->Am_payment_installment_model->invoiceTotalCollection($inv_id, $collection_id);
		// pre($data['invoiceTotalCollections']);
		// ---- invoice design information-------
		$user_id = $_SESSION['user_id'];
		$data['user_infos'] = $this->Am_sale_model->userInfo($user_id);
		foreach ($data['user_infos'] as $user_info) {
			$com_id = $user_info->com_id ? $user_info->com_id : 1;
			$branch_id = $user_info->branch_id ? $user_info->branch_id : 1;
		}

		$data['company_infos'] = $this->Am_sale_model->companyInfos($com_id, $branch_id);
		$status = 1;
		$data['installmentLists'] = $this->Am_sale_model->installmentList($collection_id);
		$data['invoiceSetupImgs'] = $this->Am_sale_model->inviceSetupImgs($com_id, $branch_id, $status);

		//---data pass to view page-----------
		// pre($data);
		$this->load->view('am_installment/installmentCollectionReceiptView.php', $data);
	}
	// ========END INSTALLMENT COLLECTION RECEIPT VIEW=============
	//---------------------------------------------------------------

	//---------------------------------------------------------------
	// ========START INSTALLMENT COLLECTION RECEIPT VIEW=============
	public function installmentCollectionBatchReceipt()
	{
		$pay_total_batch_no = $this->uri->segment(3);
		$data['collectionBatchReceipts'] = $this->Am_payment_installment_model->installmentCollectionBatchReceipt($pay_total_batch_no);
		// 		$sale_inv_id=$data['collectionBatchReceipts'][0]->sale_inv_id;
		// pre($data['collectionBatchReceipts']);
		if (empty($data['collectionBatchReceipts'])) {
			redirect('Am_payment_installment_controller/installmentCollectionReceiptList');
		}
		$data['productSaleInfos'] = $this->Am_payment_installment_model->productSaleInfo($pay_total_batch_no);

		// pre($data['productSaleInfos'] );

		// $data['installmentLists'] = $this->Am_sale_model->installmentList($collection_id);
		// ---- invoice design information-------
		$user_id = $_SESSION['user_id'];
		$data['user_infos'] = $this->Am_sale_model->userInfo($user_id);
		foreach ($data['user_infos'] as $user_info) {
			$com_id = $user_info->com_id ? $user_info->com_id : 1;
			$branch_id = $user_info->branch_id ? $user_info->branch_id : 1;
		}
		$data['company_infos'] = $this->Am_sale_model->companyInfos($com_id, $branch_id);
		$status = 1;
		$data['invoiceSetupImgs'] = $this->Am_sale_model->inviceSetupImgs($com_id, $branch_id, $status);

		//---data pass to view page-----------
		// pre($data);
		$this->load->view('am_installment/installmentCollectionBatchReceiptView.php', $data);
	}
	// ========END INSTALLMENT COLLECTION RECEIPT VIEW=============
	//---------------------------------------------------------------

}//----- end class----------
