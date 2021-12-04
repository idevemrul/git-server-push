<?php
defined('BASEPATH') or exit('No direct script access allowed');
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Am_purchase_controller extends CI_Controller
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
		$this->load->model('Am_purchase_model');
		$this->load->model('Am_sale_model');
		$this->load->model('Am_feed_data_model');
	}

	// ========start ajax support==========
	public function challanDuplicateSearchAjax()
	{
		$getChalan = $this->input->post('purc_supp_chalan_no');
		$data['duplicateChalans'] = $this->Am_purchase_model->challanDuplicateSearchAjax($getChalan);
		echo json_encode($data['duplicateChalans']);
	}

	public function engineDuplicateSearchAjax()
	{
		$getEngine = $this->input->post('purc_item_engine_no');
		$data['duplicateEngine'] = $this->Am_purchase_model->engineDuplicateSearchAjax($getEngine);
		echo json_encode($data['duplicateEngine']);
	}

	public function chassisDuplicateSearchAjax()
	{
		$getChassis = $this->input->post('purc_item_chassis_no');
		$data['duplicateChassis'] = $this->Am_purchase_model->chassisDuplicateSearchAjax($getChassis);
		echo json_encode($data['duplicateChassis']);
	}
	// ========end ajax support==========

	//-------------------------------------------------
	// ===============START PURCHASE SETUP=============
	public function purchaseView()
	{
		$data['suppliers'] = $this->Am_purchase_model->supplierListX();
		$data['banks'] = $this->Am_feed_data_model->bankList();
		// pre($data['banks']);
		$data['purchaseInvoiceLists'] = $this->Am_purchase_model->purchaseInvoiceList();
		$this->load->view('am_purchase/purchaseView', $data);
	}

	public function purchaseInvoiceAdd()
	{
		if ($this->form_validation->run('purchase_invoice_validation')) {
			$form_data = $this->input->post();

			//--- form data re-initialization-------
			$supp_id = $form_data['supp_id'];
			$purc_chalan_no = $form_data['purc_chalan_no'];
			$purc_chalan_date = $form_data['purc_chalan_date'];
			$purc_inv_amount = $form_data['purc_inv_amount'];
			$purc_inv_discount = $form_data['purc_inv_discount'];
			$purc_inv_payable = $form_data['purc_inv_payable'];
			$purc_inv_transportation = $form_data['purc_inv_transportation'];
			$purc_payment_method = $form_data['purc_payment_method'];
			$purc_payment_bank = !empty($form_data['purc_payment_bank']) ? $form_data['purc_payment_bank'] : 0;
			$purc_payment_cheque_no = $form_data['purc_payment_cheque_no'];
			$purc_payment_cheque_date = $form_data['purc_payment_cheque_date'];
			$purc_payment_paid = $form_data['purc_payment_paid'];
			$purc_date = $form_data['purc_date'];
			$purc_about = $form_data['purc_about'];
			$purc_id='';

			if (!isset($purc_date) or empty($purc_date)) {
				$purc_date = date('Y-m-d');
			}
			// --- purchase paid amount setup-------
			//--- set payment method----
			if ($purc_payment_paid || $purc_payment_method == 'Cash') {
				$purchase_payment_by_cash = $purc_payment_paid;
				$purchase_payment_by_bank = 0;
			} else {
				$purchase_payment_by_cash = 0;
				$purchase_payment_by_bank = $purc_payment_paid;
			}

			// -----start purchase invoice setup--------
			date_default_timezone_set("Asia/Dhaka");
			$inv_data = array(
				'supp_id' => $supp_id,
				'purc_chalan_no' => $purc_chalan_no,
				'purc_chalan_date' => $purc_chalan_date,
				'purc_inv_amount' => $purc_inv_amount,
				'purc_inv_discount' => $purc_inv_discount,
				'purc_inv_transportation' => $purc_inv_transportation,
				'purc_date' => $purc_date,
				'purc_about' => $purc_about,
				'add_date' => date('Y-m-d'),
				'add_time' => date('h:i:s'),
				'add_by' => $this->session->userdata('user_id'),
				'status' => 0,
			);

			// if($insert=$this->db->insert('am_purchase_invoices',$inv_data)){
			// 	$insert='success';
			//     }else{
			// 	    $insert='fail';
			// 	    }



			// ------ start payment purchase invoice-------
			// $collect_data['purc_id']=$this->Am_purchase_model->purcIdLast();
			// $purc_id=$collect_data['purc_id'][0]->purc_id;

			$due = $form_data['purc_inv_payable'] - $purchase_payment_by_cash;

			$previous_suppplier_due = 0;
			$previous_suppplier_due = $this->Am_purchase_model->supplierPreviousDue($supp_id);
			$suplier_due = $due + isset($previous_suppplier_due[0]->purc_supp_due)?:0;
			// pre($suplier_due);
			$pay_data = array(
				'purc_id'=> $purc_id,
				'supp_id' => $supp_id,
				'purc_inv_amount' => $purc_inv_payable,
				'purc_inv_paid' => $purchase_payment_by_cash,
				'purc_inv_due' => $due,
				'purc_supp_due' => $suplier_due,
				'purc_inv_date' => $purc_date,
				'pay_purc_remarks' => $purc_about,
				'add_date' => date('Y-m-d'),
				'add_time' => date('h:i:s'),
				'add_by' => $this->session->userdata('user_id'),
				'status' => 0,
			);

			// if($this->db->insert('am_payment_purchase_invoices',$pay_data)){
			// 	$insert='success';
			//     }else{
			// 	    $insert='fail';
			// 	    }

			
			// ------ start cash_transaction -------
			if ($purchase_payment_by_cash > 0.01) {
				$main_cash=1;
				$cheque_mode='out';
				$purc_id='';
				$pay_cash_transaction_data = array(
					'cash_id' 					=> $main_cash,
					'cash_tran_reference_type' 	=> 'purchase',
					'cash_tran_reference_id'	=> $purc_id,
					'cash_tran_for' 			=> 'supplier',
					'cash_tran_for_id'			=> $supp_id,
					'cash_tran_dateE' 			=> $purc_date,
					'cash_mode'					=> $cheque_mode,
					'cash_cheque_amount' 		=> $purchase_payment_by_cash,
					'cash_note'					=> $purc_about,
					'add_date' 					=> date('Y-m-d'),
					'add_time' 					=> date('h:i:s'),
					'add_by' 					=> $this->session->userdata('user_id'),
					'status' 					=> 0,
				);
			}

			// ------ start bank_transaction_entry CHEQUE LISTING invoice-------
			if ($purchase_payment_by_bank > 0.01) {
				$cheque_mode='Out';
				$pay_bank_transaction_data = array(
					'bank_id' => $purc_payment_bank,
					'bank_tran_reference_id'	=>$purc_id,
					'bank_tran_reference_type' 	=> 'purchase',
					'bank_tran_for_id'			=> $supp_id,
					'bank_tran_for' 			=> 'supplier',
					'bank_tran_method' 			=> $purc_payment_method,
					'bank_tran_cheque_no' 		=> $purc_payment_cheque_no,
					'bank_tran_cheque_date' 	=> $purc_payment_cheque_date,
					'bank_tran_cheque_action' 	=> 'entry',
					'bank_tran_cheque_action_date' => null,
					'cheque_mode'				=> $cheque_mode,
					'bank_cheque_amount' 		=> $purchase_payment_by_bank,
					'add_date' 					=> date('Y-m-d'),
					'add_time' 					=> date('h:i:s'),
					'add_by' 					=> $this->session->userdata('user_id'),
					'status' 					=> 0,
				);

				// if($this->db->insert('am_bank_transaction',$pay_bank_transaction_data)){
				// 	$insert='success';
				//     }else{
				// 	    $insert='fail';
				// 	    }
			}

			// ------ start EXPENDITURE VOUCHER PAYMENT FOR invoice-------
			if ($purc_inv_transportation > 0.01) {
				$expenduture = 5;
				$transportation = 34;
				$purchase_transportation = 23;
				$party_id=0;
				
				// ---data set for am_expenditure_vouchers table----
				$expenditure_voucher_data = array(
					'ach_id' => $expenduture,
					'acsh_id' => $transportation,
					'accsh_id' => $purchase_transportation,
					'vou_party_id' => $party_id,
					'vou_date' => $purc_date,
					'vou_note' => $purc_about,
					'vou_amount' => $purc_inv_transportation,
					'add_date' => date('Y-m-d'),
					'add_time' => date('h:i:s'),
					'add_by' => $this->session->userdata('user_id'),
					'status' => 0,
				);

				$last_vou_id=0;
				// ---data set for am_expenditure_voucher_payments table----
				
				$expenditure_voucher_pay_data = array(
					'vou_id' => 0,
					'vou_pay_by' => 'cash',
					'vou_pay_date' => $purc_date,
					'vou_pay_note' => $purc_about,
					'vou_pay_amount' => $purc_inv_transportation,
					'add_date' => date('Y-m-d'),
					'add_time' => date('h:i:s'),
					'add_by' => $this->session->userdata('user_id'),
					'status' => 0,
				);

				// ---data set for am_expenditure_vouchers table----
				
				$transaction_expenditure_voucher_data = array(
					'ach_id' => $expenduture,
					'acsh_id' => $transportation,
					'accsh_id' => $purchase_transportation,
					'tran_reference' => 'exp_voucher',
					'tran_reference_id' => $last_vou_id,
					'tran_for' => 'party',
					'tran_for_id' => $party_id,
					'tran_mode' => 'Dr',
					'tran_amount' => $purc_inv_transportation,
					'tran_dateE' => $purc_date,
					'tran_details' => $purc_about,

					'add_date' => date('Y-m-d'),
					'add_time' => date('h:i:s'),
					'add_by' => $this->session->userdata('user_id'),
					'status' => 0,
				);

				// ---data set for am_expenditure_voucher_payments table----
				$asset=2;
				$cash_at_hand=5;
				$main_cash=17;
				
				$transaction_expenditure_voucher_payment_data = array(
					'ach_id' => $asset,
					'acsh_id' => $cash_at_hand,
					'accsh_id' => $main_cash,
					'tran_reference' => 'exp_voucher',
					'tran_reference_id' => $last_vou_id,
					'tran_for' => 'party',
					'tran_for_id' => $party_id,
					'tran_mode' => 'Cr',
					'tran_amount' => $purc_inv_transportation,
					'tran_dateE' => $purc_date,
					'tran_details' => $purc_about,

					'add_date' => date('Y-m-d'),
					'add_time' => date('h:i:s'),
					'add_by' => $this->session->userdata('user_id'),
					'status' => 0,
				);

				// if($this->db->insert('am_expenditure_voucher',$vou_paid_for_id)){
				// 	$insert='success';
				//     }else{
				// 	    $insert='fail';
				// 	    }
			}



			// ########## START TRANSACTION METHOD FOR DOUBLE ENTRY ACCOUNTING #######
			// ----- start transaction_purchase_value-------- 
			$transaction_data_purchase_amount = array(
				'ach_id' => 2, //--Asset--
				'acsh_id' => 6, //--Inventory--
				'accsh_id' => 1,
				'tran_reference' => 'purc_invoice',
				// 'tran_reference_id' => $purc_id,
				'tran_for' => 'supplier',
				'tran_for_id' => $supp_id,
				'tran_mode' => 'Dr',
				'tran_amount' => $purc_inv_amount,
				'tran_dateE' => $purc_date,
				'tran_details' => $purc_about,

				'add_date' => date('Y-m-d'),
				'add_time' => date('h:i:s'),
				'add_by' => $this->session->userdata('user_id'),
				'status' => 0,
			);


			// if($this->db->insert('am_transaction',$transaction_data_purchase_amount)){
			// 	$insert='success';
			//     }else{
			// 	    $insert='fail';
			// 	    }



			// ----- start transaction_duscount-------- 
			if ($purc_inv_discount > 0.01) {
				$transaction_data_discount_amount = array(
					'ach_id' => 4, //--Liability--
					'acsh_id' => 10, //--A/C payable--
					'accsh_id' => 1,
					'tran_reference' => 'purc_invoice',
					// 'tran_reference_id' => $purc_id,
					'tran_for' => 'supplier',
					'tran_for_id' => $supp_id,
					'tran_mode' => 'Cr',
					'tran_amount' => $purc_inv_discount,
					'tran_dateE' => $purc_date,
					'tran_details' => $purc_about,

					'add_date' => date('Y-m-d'),
					'add_time' => date('h:i:s'),
					'add_by' => $this->session->userdata('user_id'),
					'status' => 0,
				);



				// if($this->db->insert('am_transaction',$transaction_data_discount_amount)){
				// 	$insert='success';
				//     }else{
				// 	    $insert='fail';
				// 	    }
			}
			// ----- start transaction_payment-------- 
			if ($purchase_payment_by_cash > 0.01) {
				//--- set payment method----
				if ($purc_payment_method == 'Cash') {
					$ach_id = 2;
					$acsh_id = 5;
					$accsh_id = 17;
				} elseif ($purc_payment_method == 'Cheque') {
					$ach_id = 2;
					$acsh_id = 4;
					$accsh_id = 18;
				} else {
					$ach_id = 2;
					$acsh_id = 37;
					$accsh_id = 19;
				}

				$transaction_data_paid_amount = array(
					'ach_id' => $ach_id, //--Asset--
					'acsh_id' => $acsh_id, //--Cash/Bank/Card payment--
					'accsh_id' => $accsh_id,
					'tran_reference' => 'purc_invoice',
					// 'tran_reference_id' => $purc_id,
					'tran_for' => 'supplier',
					'tran_for_id' => $supp_id,
					'tran_mode' => 'Cr',
					'tran_amount' => $purchase_payment_by_cash,
					'tran_dateE' => $purc_date,
					'tran_details' => $purc_about,
					'add_date' => date('Y-m-d'),
					'add_time' => date('h:i:s'),
					'add_by' => $this->session->userdata('user_id'),
					'status' => 0,
				);


				// if($this->db->insert('am_transaction',$transaction_data_paid_amount)){
				// 	$insert='success';
				//     }else{
				// 	    $insert='fail';
				// 	    }
			}

			// ----- start transaction_due PAYABLE-------- 
			if ($due > 0.01) {
				$transaction_data_due_amount = array(
					'ach_id' => 3, //--Liabilities--
					'acsh_id' => 7, //--A/C Payable--
					'accsh_id' => 13,
					'tran_reference' => 'purc_invoice',
					// 'tran_reference_id' => $purc_id,
					'tran_for' => 'supplier',
					'tran_for_id' => $supp_id,
					'tran_mode' => 'Cr',
					'tran_amount' => $due,
					'tran_dateE' => $purc_date,
					'tran_details' => $purc_about,
					'add_date' => date('Y-m-d'),
					'add_time' => date('h:i:s'),
					'add_by' => $this->session->userdata('user_id'),
					'status' => 0,
				);
				
				// if($this->db->insert('am_transaction',$transaction_data_due_amount)){
				// 	$insert='success';
				//     }else{
				// 	    $insert='fail';
				// 	    }
			}
			// ----- method execution status -------
			// ---- start execution all query by transaction------
			$this->db->trans_start();
				$this->db->insert('am_purchase_invoices', $inv_data);
				// --collect inserted purchase id--------
				$pay_cash_transaction_data['cash_tran_reference_id']=$pay_data['purc_id'] = $pay_bank_transaction_data['bank_tran_reference_id'] = $pay_expenditure_voucher_data['vou_paid_for_id'] = $transaction_data_purchase_amount['tran_reference_id'] = $transaction_data_discount_amount['tran_reference_id'] = $transaction_data_paid_amount['tran_reference_id'] = $transaction_data_due_amount['tran_reference_id'] = $this->db->insert_id();

				//---for paid amount------
				if ($purc_payment_method == 'Cash') {
					$this->db->insert('am_payment_purchase_invoices', $pay_data);
					$this->db->insert('am_cash_transaction', $pay_cash_transaction_data);
				} else if ($purchase_payment_by_bank > 0) {
					$this->db->insert('am_bank_transaction', $pay_bank_transaction_data);
				} else {
					$this->db->insert('am_payment_purchase_invoices', $pay_data);
				}

				//---for transaction table-------
				$this->db->insert('am_transaction', $transaction_data_purchase_amount);
				if ($purc_inv_discount > 0.01) {
					$this->db->insert('am_transaction', $transaction_data_discount_amount);
				}
				if ($purchase_payment_by_cash > 0) {
					$this->db->insert('am_transaction', $transaction_data_paid_amount);
				}
				if ($due > 0.01){
					$this->db->insert('am_transaction', $transaction_data_due_amount);
				}
	
				// -----transportation data submission------
				if($purc_inv_transportation > 0.01){
					$this->db->insert('am_expenditure_vouchers',$expenditure_voucher_data);
					$last_vou_id= $this->db->insert_id();

						$expenditure_voucher_pay_data['vou_id']=$last_vou_id;
						$transaction_expenditure_voucher_data['tran_reference_id']=$last_vou_id;
						$transaction_expenditure_voucher_payment_data['tran_reference_id']=$last_vou_id;

						// --------cash_transaction data setup------
						$pay_cash_transaction_data['cash_tran_reference_type']='expenditure';
						$pay_cash_transaction_data['cash_tran_reference_id']=$last_vou_id;
						$pay_cash_transaction_data['cash_tran_for']='party';
						$pay_cash_transaction_data['cash_tran_for_id']='0';
						$pay_cash_transaction_data['cash_cheque_amount']=$purc_inv_transportation;

					$this->db->insert('am_expenditure_voucher_payments',$expenditure_voucher_pay_data);
					$this->db->insert('am_cash_transaction', $pay_cash_transaction_data);
					$this->db->insert('am_transaction',$transaction_expenditure_voucher_data);
					$this->db->insert('am_transaction',$transaction_expenditure_voucher_payment_data);
				}

			$this->db->trans_complete();

			if ($this->db->trans_status() === TRUE) {
				$this->session->set_flashdata('msg', 'Purchase created successfully');
				$this->session->set_flashdata('msg_class', 'text-success');
				redirect('Am_purchase_controller/purchaseView');
			} else {
				$this->session->set_flashdata('msg', 'Sorry ! Unit add unsuccessfull.');
				$this->session->set_flashdata('msg_class', 'text-danger');
				redirect('Am_purchase_controller/purchaseView');
			}
			// ---- end execution all query by transaction------

		} else {
			$user_id = $_SESSION['user_id'];
			$company_id = $this->db->select('com_id')->where('auth_user_id', $user_id)->get('am_setup_users')->result()[0]->com_id;
			$branch_id = $this->db->select('branch_id')->where('auth_user_id', $user_id)->get('am_setup_users')->result()[0]->branch_id;

			$data['suppliers'] = $this->Am_purchase_model->supplierList();
			$data['banks'] = $this->Am_purchase_model->bankList($company_id, $branch_id);
			$data['purchaseInvoiceLists'] = $this->Am_purchase_model->purchaseInvoiceList();
			$this->load->view('am_purchase/purchaseView', $data);
		}
	}

	public function purchaseInvoiceSearch()
	{
		$form_data = $this->input->post();

		isset($form_data['purc_id_search']) ? $purc_id_search = $form_data['purc_id_search'] : '';
		isset($form_data['purc_supplier_search']) ? $purc_supplier_search = $form_data['purc_supplier_search'] : '';

		if (isset($form_data['purchase_date_range'])) {
			$date_range = explode('-', $form_data['purchase_date_range']);
			$purchase_date_start = date("Y-m-d", strtotime(trim($date_range[0])));
			$purchase_date_end = date("Y-m-d", strtotime(trim($date_range[1])));
		}

		if (empty($form_data)) {
			$this->session->set_flashdata('msg', 'Sorry ! You have search an empty tag! Try again.');
			$this->session->set_flashdata('msg_class', 'text-danger');
			redirect('Am_purchase_controller/purchaseView');
		}
		if ($this->form_validation->run('purchase_invoice__search_validation')) {
			//---start prepare query whare condition-------
			$where = '';
			isset($purc_id_search) ? $where .= "purInv.purc_id=$purc_id_search" : '';
			isset($purc_supplier_search) ? $where .= "purInv.supp_id=$purc_supplier_search" : '';
			isset($purchase_date_start) and ($purchase_date_start != '1970-01-01') ? $where .= " AND purInv.purc_date BETWEEN '$purchase_date_start' AND '$purchase_date_end'" : '';
			//---end prepare query whare condition-----

			$user_id = $_SESSION['user_id'];
			$company_id = $this->db->select('com_id')->where('auth_user_id', $user_id)->get('am_setup_users')->result()[0]->com_id;
			$branch_id = $this->db->select('branch_id')->where('auth_user_id', $user_id)->get('am_setup_users')->result()[0]->branch_id;
			$data['banks'] = $this->Am_purchase_model->bankList($company_id, $branch_id);

			// pre($where);
			$data['suppliers'] = $this->Am_purchase_model->supplierList();
			$data['purchaseInvoiceLists'] = $this->Am_purchase_model->purchaseInvoiceList();
			$data['purchaseInvoiceListsSearched'] = $this->Am_purchase_model->purchaseInvoiceListSearched($where);
			$this->load->view('am_purchase/purchaseView', $data);
		}
	}

	public function unitStatusUpdate()
	{
		$unit_id = $this->uri->segment(3);
		if ($this->Am_setup_model->unitStatusUpdate($unit_id)) {
			$this->session->set_flashdata('msg', 'Customer status updated successfully');
			$this->session->set_flashdata('msg_class', 'text-success');
			redirect('Am_setup_controller/unitView');
		} else {
			$this->session->set_flashdata('msg', 'Sorry ! Customer status updated unsuccessfull.');
			$this->session->set_flashdata('msg_class', 'text-danger');
			redirect('Am_setup_controller/unitView');
		}
	}
	// =============END PURCHASE SETUP===========
	//-------------------------------------------

	// ------------------------------------------
	// ============START PURCHASE PAYMENT========
	public function paymentPruchaseInvoiceView()
	{
		$user_id = $_SESSION['user_id'];
		$company_id = $this->db->select('com_id')->where('auth_user_id', $user_id)->get('am_setup_users')->result()[0]->com_id;
		$branch_id = $this->db->select('branch_id')->where('auth_user_id', $user_id)->get('am_setup_users')->result()[0]->branch_id;
		$data['banks'] = $this->Am_purchase_model->bankList($company_id, $branch_id);
		
		$data['paymentPruchaseInvoiceLists'] = $this->Am_purchase_model->paymentPruchaseInvoiceList();
		$data['distinctPaymentPruchaseInvoiceLists'] = $this->Am_purchase_model->distinctPaymentPruchaseInvoiceList();

		$this->load->view('am_purchase/paymentPruchaseInvoiceView', $data);
	}

	public function paymentPruchaseInvoiceSearch()
	{
		$form_data = $this->input->post();
		$supp_id = $form_data['supp_id'];

		$data['paymentPruchaseInvoiceListsSearched'] = $this->Am_purchase_model->paymentPruchaseInvoiceListSearched($supp_id);

		$data['distinctPaymentPruchaseInvoiceLists'] = $this->Am_purchase_model->distinctPaymentPruchaseInvoiceList();
		$this->load->view('am_purchase/paymentPruchaseInvoiceView', $data);
	}

	// =============END PURCHASE PAYMENT===========
	//-------------------------------------------

	// -------------------------------------------
	// ======START PURCHASE PRODUCT LISTING=======
	public function purchaseProductView()
	{
		$data['purchaseProductListsDistinct'] = $this->Am_purchase_model->purchaseInvoiceItemsListDistinct();
		$data['purchaseProductLists'] = $this->Am_purchase_model->purchaseInvoiceItemsList();
		$data['chalanLists'] = $this->Am_purchase_model->chalanList();
		$data['productLists'] = $this->Am_purchase_model->productList();
		$data['productCategoryLists'] = $this->Am_purchase_model->productCategoryList();
		$data['supplierListXs'] = $this->Am_purchase_model->supplierListX();
		// pre($data['chalanLists']);
		$this->load->view('am_purchase/purchaseProductView', $data);
	}

	public function purchaseProductSearch()
	{
		$form_data = $this->input->post();
		// pre($form_data);
		isset($form_data['search_purc_item_chassis_no']) ? $search_purc_item_chassis_no = $form_data['search_purc_item_chassis_no'] : '';
		isset($form_data['search_purc_chalan_no']) ? $search_purc_chalan_no = $form_data['search_purc_chalan_no'] : '';
		isset($form_data['search_product_id']) ? $search_product_id = $form_data['search_product_id'] : '';
		isset($form_data['search_product_category_id']) ? $search_product_category_id = $form_data['search_product_category_id'] : '';
		isset($form_data['search_supp_id']) ? $search_supp_id = $form_data['search_supp_id'] : '';

		if (isset($form_data['search_purchase_date_range'])) {
			$date_range = explode('-', $form_data['search_purchase_date_range']);
			$product_date_start = date("Y-m-d", strtotime(trim($date_range[0])));
			$product_date_end = date("Y-m-d", strtotime(trim($date_range[1])));
		}
		//---start empty search--------
		if (empty($form_data)) {
			$this->session->set_flashdata('msg', 'Sorry ! You have search an empty tag! Try again.');
			$this->session->set_flashdata('msg_class', 'text-danger');
			redirect('Am_purchase_controller/purchaseProductView');
		}
		//---end empty search---------

		//---start prepare query whare condition-------
		$where = '';
		isset($search_purc_item_chassis_no) ? $where .= "purInv_itm.purc_item_chassis_no='$search_purc_item_chassis_no'" : '';
		isset($search_purc_chalan_no) ? $where .= "purInv.purc_chalan_no='$search_purc_chalan_no'" : '';
		isset($search_product_id) ? $where .= "setProd.product_id='$search_product_id'" : '';
		isset($search_product_category_id) ? $where .= "setProd.product_category_id='$search_product_category_id'" : '';
		isset($search_supp_id) ? $where .= "purInv.supp_id='$search_supp_id'" : '';

		isset($product_date_start) and ($product_date_start != '1970-01-01') ? $where .= " AND purInv.purc_chalan_date BETWEEN '$product_date_start' AND '$product_date_end'" : '';
		// pre($where);
		//---end prepare query whare condition-----

		$data['purchaseProductListsDistinct'] = $this->Am_purchase_model->purchaseInvoiceItemsListDistinct();
		$data['purchaseProductLists'] = $this->Am_purchase_model->purchaseInvoiceItemsList();
		$data['chalanLists'] = $this->Am_purchase_model->chalanList();
		$data['productLists'] = $this->Am_purchase_model->productList();
		$data['productCategoryLists'] = $this->Am_purchase_model->productCategoryList();
		$data['supplierListXs'] = $this->Am_purchase_model->supplierListX();

		$data['purchaseProductListsSearched'] = $this->Am_purchase_model->purchaseInvoiceItemsListSearched($where);

		$this->load->view('am_purchase/purchaseProductView', $data);
	}

	public function purchaseInvoiceProductListReportView($id)
	{
		//---start current user and invoice setup details collection---------
		$user_id = $_SESSION['user_id'];
		$data['user_infos'] = $this->Am_sale_model->userInfo($user_id);
		foreach ($data['user_infos'] as $user_info) {
			$com_id = $user_info->com_id ? $user_info->com_id : 1;
			$branch_id = $user_info->branch_id ? $user_info->branch_id : 1;
		}
		$status = 1;
		$data['invoiceSetupImgs'] = $this->Am_sale_model->inviceSetupImgs($com_id, $branch_id, $status);
		//---end current user details collection---------
		$where = "purInv.purc_id='$id'";
		$data['purchaseInvItemLists'] = $this->Am_purchase_model->purchaseInvoiceItemsListSearchedReport($where);
		// pre($data);
		$this->load->view('am_purchase/purchaseInvoiceProductListReportView', $data);
	}

	// ------- ajax data collection by jQuery-----
	public function purchaseIdRecordCollection()
	{
		$purc_id = $_POST['purc_id'];
		$data['purchaseIdDetails'] = $this->Am_purchase_model->purchaseIdDetails($purc_id);
		echo json_encode($data['purchaseIdDetails']);
	}

	// ----- add item to purchase invoice OR chalan------
	public function purchaseInvoiceItems()
	{
		if ($this->form_validation->run('purchase_invoice_items_validation')) {
			$form_data = $this->input->post();
			$item_count = count($form_data['purc_item_engine_no']);

			// ---field value add------
			$data = array(
				'purc_id' => $form_data['purc_id'],
				'product_id' => $form_data['prod_id'],
				'purc_item_purchase_price' => $form_data['purc_item_purchase_price'],
				'add_date' => date('Y-m-d'),
				'add_time' => date('h:i:s'),
				'add_by' => $this->session->userdata('user_id'),
				'status' => 0
			);

			for ($i = 0; $i < $item_count; $i++) {
				$data['purc_item_engine_no '] = $form_data['purc_item_engine_no'][$i];
				$data['purc_item_chassis_no '] = $form_data['purc_item_chassis_no'][$i];
				$data['purc_item_battery_no '] = $form_data['purc_item_battery_no'][$i];

				if ($this->db->insert('am_purchase_invoice_items', $data)) {
					$insert = 'success';
				} else {
					$insert = 'fail';
				}
			}
			// ---- data insertion to am_purchase_invoice_items ----


			// ----- method execution status -------
			if ($insert == 'success') {
				$this->session->set_flashdata('msg', 'Purchase created successfully');
				$this->session->set_flashdata('msg_class', 'text-success');
				redirect('Am_purchase_controller/purchaseProductView');
			} else {
				$this->session->set_flashdata('msg', 'Sorry ! Unit add unsuccessfull.');
				$this->session->set_flashdata('msg_class', 'text-danger');
				redirect('Am_purchase_controller/purchaseProductView');
			}
		}
	}

	// ------END PURCHASE PRODUCT LISTING---------
	// ------------------------------------------- 

	// ------START SUPPLIER DUE PAYMENT-----------
	//============================================
	// ----SUPPLIER DUE COLLECT-------
	public function suppDueCollect()
	{
		$supp_id = $this->input->post('supp_id');
		// pre($supp_id);
		$suppDueAMounts = $this->Am_purchase_model->suppDueCollect($supp_id);
		echo json_encode($suppDueAMounts);
	}

	// ----- DUE PAYMENT PAID---------
	public function supplierDuePayment()
	{
		$form_data = $this->input->post();

		$purc_payment_method = $form_data['purc_payment_method'];
		$purc_payment_bank = isset($form_data['purc_payment_bank']) ? $form_data['purc_payment_bank']: 0;
		$purc_payment_cheque_no = $form_data['purc_payment_cheque_no'];
		$purc_payment_cheque_date = $form_data['purc_payment_cheque_date'];
		$purc_id 			= 0;
		$supp_id			= $form_data['due_supp_id'];
		$purc_supp_paying	= $form_data['purc_supp_paying'];
		$purc_supp_due		= $form_data['purc_supp_due'];
		$new_purc_supp_due	= $form_data['new_purc_supp_due'];
		$due_payemnt_date	= $form_data['due_payemnt_date'];
		$due_payment_note	= $form_data['due_payment_note'];

		// pre($form_data);

		// -----start payment collection of payment purchase invoice--------
		if ($purc_payment_method == 'Cash' && $purc_supp_paying > 0) {

			$supp_due_payment = array(
				'purc_id'			=> $purc_id,
				'supp_id'			=> $supp_id,
				'purc_inv_amount'	=> 0,
				'purc_inv_paid'		=> $purc_supp_paying,
				'purc_inv_due'		=> 0,
				'purc_supp_due'		=> $new_purc_supp_due,
				'purc_inv_date'		=> $due_payemnt_date,
				'pay_purc_remarks'	=> $due_payment_note,
				'add_date'			=> date('Y-m-d'),
				'add_time'			=> date('h:i:s'),
				'add_by'			=> $this->session->userdata('user_id'),
				'status'			=> 0,
			);

			// ------ start cash_transaction -------
				$main_cash=1;
				$cheque_mode='out';
				$pay_cash_transaction_data = array(
					'cash_id' 					=> $main_cash,
					'cash_tran_reference_type' 	=> 'purchase',
					'cash_tran_reference_id'	=> $purc_id,
					'cash_tran_for' 			=> 'supplier',
					'cash_tran_for_id'			=> $supp_id,
					'cash_tran_dateE' 			=> $due_payemnt_date,
					'cash_mode'					=> $cheque_mode,
					'cash_cheque_amount' 		=> $purc_supp_paying,
					'cash_note'					=> $due_payment_note,
					'add_date' 					=> date('Y-m-d'),
					'add_time' 					=> date('h:i:s'),
					'add_by' 					=> $this->session->userdata('user_id'),
					'status' 					=> 0,
				);


			// ----- start transaction_payment----------------------
			if ($purc_payment_method == 'Cash') {
				$ach_id = 2;
				$acsh_id = 5;
				$accsh_id = 17;
			} elseif ($purc_payment_method == 'Cheque') {
				$ach_id = 2;
				$acsh_id = 4;
				$accsh_id = 18;
			} else {
				$ach_id = 2;
				$acsh_id = 37;
				$accsh_id = 19;
			}

			$trans_purc_due_paid_amount = array(
				'ach_id' => $ach_id, //--Asset--
				'acsh_id' => $acsh_id, //--Cash/Bank/Card payment--
				'accsh_id' => $accsh_id,
				'tran_reference' => 'purc_invoice',
				'tran_reference_id' => $purc_id,
				'tran_for' => 'supplier',
				'tran_for_id' => $supp_id,
				'tran_mode' => 'Cr',
				'tran_amount' => $purc_supp_paying,
				'tran_dateE' => $due_payemnt_date,
				'tran_details' => $due_payment_note,
				'add_date' => date('Y-m-d'),
				'add_time' => date('h:i:s'),
				'add_by' => $this->session->userdata('user_id'),
				'status' => 0,
			);

			// ----- start transaction_due PAYABLE-------- 
			$trans_payable_reduce = array(
				'ach_id' => 3, //--Liabilities--
				'acsh_id' => 7, //--A/C Payable--
				'accsh_id' => 13,
				'tran_reference' => 'purc_invoice',
				'tran_reference_id' => $purc_id,
				'tran_for' => 'supplier',
				'tran_for_id' => $supp_id,
				'tran_mode' => 'Dr',
				'tran_amount' => $purc_supp_paying,
				'tran_dateE' => $due_payemnt_date,
				'tran_details' => $due_payment_note,
				'add_date' => date('Y-m-d'),
				'add_time' => date('h:i:s'),
				'add_by' => $this->session->userdata('user_id'),
				'status' => 0,
			);

			// ---transaction method for data submission---------
			$this->db->trans_start();
				$this->db->insert('am_payment_purchase_invoices', $supp_due_payment);
				$this->db->insert('am_cash_transaction', $pay_cash_transaction_data);
				$this->db->insert('am_transaction', $trans_purc_due_paid_amount);
				$this->db->insert('am_transaction', $trans_payable_reduce);
			$this->db->trans_complete();

			if ($this->db->trans_status() === TRUE) {
				$this->session->set_flashdata('msg', 'Due collection successfully');
				$this->session->set_flashdata('msg_class', 'text-success');
				redirect('Am_purchase_controller/paymentPruchaseInvoiceView');
			} else {
				$this->session->set_flashdata('msg', 'Sorry due collection unsuccessfull.');
				$this->session->set_flashdata('msg_class', 'text-danger');
				redirect('Am_purchase_controller/paymentPruchaseInvoiceView');
			}
		} //--- end if('Cash' && payment>0)
		// ------ start bank_transaction_entry CHEQUE LISTING invoice-------
		elseif (($purc_payment_method == 'Cheque' || $purc_payment_method == 'Card') && $purc_supp_paying > 0) {
			// pre($form_data);
			$pay_bank_transaction_data = array(
				'bank_id' => $purc_payment_bank,
				'bank_tran_reference_id' => $purc_id,
				'bank_tran_reference_type' => 'purchase',
				'bank_tran_for_id' => $supp_id,
				'bank_tran_for' => 'supplier',
				'bank_tran_method' => $purc_payment_method,
				'bank_tran_cheque_no' => $purc_payment_cheque_no,
				'bank_tran_cheque_date' => $purc_payment_cheque_date,
				'bank_tran_cheque_action' => 'entry',
				'bank_tran_cheque_action_date' => null,
				'cheque_mode'	=> 'out',
				'bank_cheque_amount' => $purc_supp_paying,
				'add_date' => date('Y-m-d'),
				'add_time' => date('h:i:s'),
				'add_by' => $this->session->userdata('user_id'),
				'status' => 0,
			);

			if ($this->db->insert('am_bank_transaction', $pay_bank_transaction_data)) {
				$this->session->set_flashdata('msg', 'Due collection by ' . $purc_payment_method . ' successfull');
				$this->session->set_flashdata('msg_class', 'text-success');
				redirect('Am_purchase_controller/paymentPruchaseInvoiceView');
			} else {
				$this->session->set_flashdata('msg', 'Sorry due collection by ' . $purc_payment_method . ' unsuccessfull.');
				$this->session->set_flashdata('msg_class', 'text-danger');
				redirect('Am_purchase_controller/paymentPruchaseInvoiceView');
			}
		}
	} //---end method--------

	public function purchaseInvoiceReceiptView($id)
	{
		// ---- start invoice design information-------
		$user_id = $_SESSION['user_id'];
		$data['user_infos'] = $this->Am_sale_model->userInfo($user_id);
		foreach ($data['user_infos'] as $user_info) {
			$com_id = $user_info->com_id ? $user_info->com_id : 1;
			$branch_id = $user_info->branch_id ? $user_info->branch_id : 1;
		}
		$status = 1;
		$data['invoiceSetupImgs'] = $this->Am_sale_model->inviceSetupImgs($com_id, $branch_id, $status);
		// ---end invoice design information------
		$data['purchase_inv_details'] = $this->Am_purchase_model->purchaseInvoiceReceiptView($id);
		// pre($data);
		$this->load->view('am_purchase/purchaseInvoiceReceiptView', $data);
	}

	public function purchaseInvoiceProductDelete()
	{
		$purc_inv_id = $this->uri->segment(3);
		$purc_item_id = $this->uri->segment(4);
		if ($this->db->where('purc_item_id', $purc_item_id)->delete('am_purchase_invoice_items') === TRUE) {
			$data = [
				'status' 		=> 'Deleted successfully',
				'status_text' 	=> 'Invoice item deleted successfully',
				'status_icon' 	=> 'success',
				'purc_inv_id' 	=> $purc_inv_id
			];
			echo json_encode($data);
		} else {
			$data = [
				'status'	 	=> 'Failed !!',
				'status_text' 	=> 'Item not deleted. Try again.',
				'status_icon' 	=> 'error',
				'purc_inv_id' 	=> $purc_inv_id
			];
			echo json_encode($data);
		}
	}

	// ------END SUPPLIER DUE PAYMENT-------------
	// -------------------------------------------
}   //----- end class----------
