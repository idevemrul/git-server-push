<?php
defined('BASEPATH') or exit('No direct script access allowed');
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Am_sale_controller extends CI_Controller
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
		$this->load->model('Am_sale_model');
		$this->load->model('Am_sale_product_model');
		$this->load->library('pagination');
	}

	// ========start dashboard feed data =========

	// ========start dashboard feed data =========

	// -------------------------------------------
	// ======START PURCHASE PRODUCT LISTING=======
	public function saleView()
	{
		// $data['salableProducts']=$this->Am_sale_model->salableProductList();
		$data['customers'] = $this->Am_sale_model->customerList();
		$data['customerMobiles'] = $this->Am_sale_model->customerMobileList();
		$data['references'] = $this->Am_sale_model->referenceList();
		$data['items'] = $this->Am_sale_model->distinctSoldProduct();

		$data['soldProducts'] = $this->Am_sale_model->soldProductList();
		// pre($data['items']);
		//---------START PAGINATION -----------
		// $config = [
		// 	'base_url' => base_url('Am_sale_controller/saleView'),
		// 	'per_page' => 5,
		// 	'total_rows' => $this->Am_sale_model->num_rows(),
		// ];
		// $config['num_links'] = 7;
		// $config['full_tag_open'] = '<div><ul class="pagination pagination-small pagination-centered">';
		// $config['full_tag_close'] = '</ul></div>';
		// $config['num_tag_open'] = '<li class="page-item">';
		// $config['num_tag_close'] = '</li>';
		// $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
		// $config['cur_tag_close'] = '</a></li>';
		// $config['next_tag_open'] = '<li class="page-item">';
		// $config['next_tagl_close'] = '</a></li>';
		// $config['prev_tag_open'] = '<li class="page-item">';
		// $config['prev_tagl_close'] = '</li>';
		// $config['first_tag_open'] = '<li class="page-item">';
		// $config['first_tagl_close'] = '</li>';
		// $config['last_tag_open'] = '<li class="page-item">';
		// $config['last_tagl_close'] = '</a></li>';
		// $config['attributes'] = array('class' => 'page-link');
		// $this->pagination->initialize($config); // model function
		// $data['soldProducts'] = $this->Am_sale_model->seekerlist($config['per_page'], $this->uri->segment(3)); // list of seeker
		//---------END PAGINATION--------------
		$this->load->view('am_sale/saleView', $data);
	}

	public function saleViewSearch()
	{

		$form_data = $this->input->post();
		$searched_by = '';
		// pre($form_data);
		// =======START SEARCH DATA COLLECTION AND WHERE CONDITION PREPARATIN========
		if (!empty($form_data['sale_inv_id'])) {
			$sale_inv_id = $form_data['sale_inv_id'];
			$searched_by .= 'Invoice=' . number_format($form_data['sale_inv_id'], 6) . ' || ';
		}
		if (!empty($form_data['sale_inv_chassis_no'])) {
			$sale_inv_chassis_no = $form_data['sale_inv_chassis_no'];
			$searched_by .= 'Chassis=' . $form_data['sale_inv_chassis_no'] . ' || ';
		}
		if (!empty($form_data['cust_id'])) {
			$cust_id = $form_data['cust_id'];
			$searched_by .= 'Customer=' . $this->Am_sale_model->customerName($form_data['cust_id']) . ' || ';
		}
		if (!empty($form_data['cust_mobile'])) {
			$cust_mobile = $form_data['cust_mobile'];
			$searched_by .= 'Mobile' . $form_data['cust_mobile'] . ' || ';
		}
		if (!empty($form_data['product_name'])) {
			$product_name = $form_data['product_name'];
			$searched_by .= 'Product=' . $form_data['product_name'] . ' || ';
		}

		if (isset($form_data['search_purchase_date_range'])) {
			$date_range = explode('-', $form_data['search_purchase_date_range']);
			$date_start = date("Y-m-d", strtotime(trim($date_range[0])));
			$date_end = date("Y-m-d", strtotime(trim($date_range[1])));
		}

		//---start empty search--------
		if (empty($form_data)) {
			$this->session->unset_userdata('searched_by');
			$this->session->set_flashdata('msg', 'Sorry ! You have search an empty tag! Try again.');
			$this->session->set_flashdata('msg_class', 'text-danger');
			redirect('Am_purchase_controller/purchaseProductView');
		} elseif (isset($date_start) and ($date_start != '1970-01-01')) {
			$searched_by .= " Date= " . $date_start . " to " . $date_end;
			$this->session->set_userdata('searched_by', $searched_by);
		} else {
			$this->session->unset_userdata('searched_by');
		}
		//---end empty search---------

		//---start prepare query whare condition-------
		$where = '';
		isset($sale_inv_id) ? $where .= "saleInv.sale_inv_id='$sale_inv_id' AND " : " AND ";
		isset($sale_inv_chassis_no) ? $where .= "purcItem.purc_item_chassis_no='$sale_inv_chassis_no' AND " : " AND ";
		isset($cust_id) ? $where .= "customer.cust_id='$cust_id' AND " : " AND ";
		isset($cust_mobile) ? $where .= "customer.cust_mobile='$cust_mobile' AND " : " AND ";
		isset($product_name) ? $where .= "product.product_name='$product_name' AND " : " AND ";

		isset($date_start) and ($date_start != '1970-01-01') ? $where .= " saleInv.sale_date BETWEEN '$date_start' AND '$date_end' AND " : " AND ";

		$where .= '
		saleInv.purc_item_id=purcItem.purc_item_id AND
		saleInv.cust_id=customer.cust_id AND 
		purcItem.product_id=product.product_id AND
		purcItem.purc_id=purchase.purc_id AND
		purchase.supp_id=supplier.supp_id AND
		product.product_manuf_id=manufacturer.manuf_id AND
		saleInv.add_by=user.auth_user_id
		';
		// =======END SEARCH DATA COLLECTION AND WHERE CONDITION PREPARATIN==========

		// pre($where);
		$data['customers'] = $this->Am_sale_model->customerList();
		$data['customerMobiles'] = $this->Am_sale_model->customerMobileList();
		$data['references'] = $this->Am_sale_model->referenceList();
		$data['items'] = $this->Am_sale_model->distinctSoldProduct();

		$data['soldProducts'] = $this->Am_sale_model->soldProductList();
		$data['soldProductsSearched'] = $this->Am_sale_model->soldProductListSearched($where);
		// pre($data['soldProductsSearched']);
		$this->load->view('am_sale/saleView', $data);
	}

	// ------- ajax data collection by jQuery-----
	public function saleItemDetails()
	{
		$purc_item_id = $_POST['purc_item_id'];
		$data['items'] = $this->Am_sale_model->itemDetails($purc_item_id);
		echo json_encode($data['items']);
	}

	// ------- ajax data collection by jQuery-----
	public function customerDetails()
	{
		$cust_id = $_POST['cust_id'];
		$data['customers'] = $this->Am_sale_model->customerDetails($cust_id);
		echo json_encode($data['customers']);
	}

	// ------- ajax data collection by jQuery-----
	public function referenceDetails()
	{
		$refe_id = $_POST['refe_id'];
		$data['references'] = $this->Am_sale_model->referenceDetails($refe_id);
		echo json_encode($data['references']);
	}

	// ----- START SALE INVOICE AND OTHERES OPERATIONS ------
	public function saleProduct()
	{
		if ($this->form_validation->run('sale_product')) {
			$form_data = $this->input->post();
			// pre($form_data);
			// --- set as lical variable--------
			$manual_sale_inv_no 	= $form_data['manual_sale_inv_no'];
			$purc_item_id 			= $form_data['purc_item_id'];
			$cust_id 				= $form_data['cust_id'];
			$product_id 			= $form_data['product_id'];
			$refe_id 				= $form_data['refe_id'] ?? 0;
			$purc_item_engine_no 	= $form_data['purc_item_engine_no'];
			$purc_item_chassis_no 	= $form_data['purc_item_chassis_no'];
			$purc_item_battery_no 	= $form_data['pro_battry_no'];
			$sale_price 			= $form_data['sale_price'] ?? 0;
			$sale_discount 			= $form_data['sale_discount'] ?: 0;
			$sale_scrach_card 		= $form_data['sale_scrach_card'] ?: 0;
			$sale_paid 				= $form_data['sale_paid'] ?: 0;
			$sale_processing_fee 	= $form_data['sale_inst_processing_fee'] ?? 0;
			$sale_interest 			= $form_data['sale_inst_interest'] ?? 0;
			$sale_inst_cycle 		= $form_data['sale_inst_cycle'] ?? 'None';
			$sale_inst_cycle_interest = $form_data['interest_per_cycle'] ?: 0;
			$sale_inst_quantity 	= $form_data['sale_inst_quantity'] ? $form_data['sale_inst_quantity'] : 0;
			$sale_date 				= $form_data['sale_date'] ?: date('Y-m-d');
			$sale_key_qtt			= $form_data['sale_key_qtt'];

			$sale_ac_receivable = round(((int)$sale_price + (int)$sale_processing_fee + (int)$sale_interest) - ((int)$sale_discount + (int)$sale_scrach_card + (int)$sale_paid));

			// ---1------Data submit to sale_invoce table------
			$data = array(
				'manual_sale_inv_no' => $manual_sale_inv_no,
				'purc_item_id' 		=> $purc_item_id,
				'cust_id' 			=> $cust_id,
				'refe_id' 			=> $refe_id,
				'product_id' 		=> $product_id,
				'purc_item_engine_no' 	=> $purc_item_engine_no,
				'purc_item_chassis_no' 	=> $purc_item_chassis_no,
				'sale_price' 			=> $sale_price,
				'sale_discount' 		=> $sale_discount,
				'sale_scrach_card' 		=> $sale_scrach_card,
				'sale_paid' 			=> $sale_paid,
				'sale_processing_fee' 	=> $sale_processing_fee,
				'sale_interest' 		=> $sale_interest,
				'sale_inst_cycle' 		=> $sale_inst_cycle,
				'sale_inst_cycle_interest' 	=> $sale_inst_cycle_interest,
				'sale_inst_quantity' 	=> $sale_inst_quantity,
				'sale_date' 			=> $sale_date,
				'sale_key_qtt' 			=> $sale_key_qtt,
				'add_date' 	=> date('Y-m-d'),
				'add_time' 	=> date('h:i:s'),
				'add_by' 	=> $this->session->userdata('user_id'),
				'status' 	=> 0
			);


			// if ($this->db->insert('am_sale_invoice', $data)) {
			// 	$insert = 'success';
			// 	$this_inv_id = $this->db->insert_id();
			// } else {
			// 	$insert = 'fail';
			// }


			// ----1.1 update purchase product table for battery update.......
			// if ($purc_item_battery_no != 'No Battery' or $purc_item_battery_no != '') {
			// 	$product_update_data['pro_battry_no'] = $purc_item_battery_no;
			// 	if ($this->db->where('purc_item_id', $purc_item_id)->update('am_purchase_invoice_items', $product_update_data)) {
			// 		$insert = 'success';
			// 	} else {
			// 		$insert = 'fail';
			// 	}
			// }

			// --2----update am_purchase_invoice_items status=13 as sold out product----
			// $purc_item_id = $form_data['purc_item_id'];
			// if ($this->Am_sale_model->salePurchaseItemStatusUpdate($purc_item_id, $sale_date)) {
			// 	$insert = 'success';
			// } else {
			// 	$insert = 'fail';
			// }

			//---3----insert data to sale_invoice_due---------
			$saleInvId = $this->Am_sale_model->saleInvLastId();
			$sale_inv_id = $saleInvId[0]->sale_inv_id;

			$sale_amount = (int)$sale_price + (int)$sale_processing_fee + (int)$sale_interest ?: 0;
			$sale_payment = $sale_discount + $sale_scrach_card + $sale_paid ?: 0;
			$sale_due = $sale_amount - $sale_payment ?: 0;


			$sale_inv_due = array(
				'sale_inv_id' 		=> $sale_inv_id,
				'sale_install_id' 	=> 0,
				'install_amount' 	=> 0,
				'previous_install_due' => 0,
				'late_day' 			=> 0,
				'late_fine' 		=> 0,
				'late_fine_waiv' 	=> 0,
				'total_paid' 		=> 0,
				'install_due' 		=> 0,
				'invoice_due' 		=> $sale_due,
				'pay_install_date'	=> $sale_date,
				'add_note' 			=> 'Sale time Due',
				'add_date' 			=> date('Y-m-d'),
				'add_time' 			=> date('h:i:s'),
				'add_by' 			=> $this->session->userdata('user_id'),
				'status' 			=> 0
			);


			// if ($this->db->insert('am_payment_sale_invoice_installments', $sale_inv_due)) {
			// 	$insert = 'success';
			// } else {
			// 	$insert = 'fail';
			// }






			//---4---- data submit to sale_installments as per sale installment view----
			// pre($sale_inst_quantity);
			if ($sale_inst_quantity > 0) {
				$count = count($form_data['inst_date']);

				for ($i = 0; $i < $count; $i++) {
					$inst_date = $form_data['inst_date'][$i];
					$sale_inst_date = date("Y-m-d", strtotime($inst_date));
					$sale_inst_amount = $form_data['inst_amount'][$i] ?: 0;
					$sale_inst_number = $i + 1;

					$sale_installment[] = array(
						'sale_inv_id' => $sale_inv_id,
						'sale_inst_date' => $sale_inst_date,
						'sale_inst_amount' => $sale_inst_amount,
						'sale_inst_number' => $sale_inst_number,
						'sale_inst_quantity' => $sale_inst_quantity,
						'sale_inst_schedule' => 0,
						'add_date' => date('Y-m-d'),
						'add_time' => date('h:i:s'),
						'add_by' => $this->session->userdata('user_id'),
						'status' => 0
					);

					// if ($this->db->insert('am_sale_invoice_installments', $sale_installment)) {
					// 	$insert = 'success';
					// } else {
					// 	$insert = 'fail';
					// }
				}
			} //---end if of installment quantity--------

			// pre($sale_installment);

			//----5---sale transaction inventory--------
			if ($sale_price > 0) {
				$ach_id = 2;
				$acsh_id = 6;
				$accsh_id = 5;

				$sale_inventory_transaction = array(
					'ach_id' 			=> $ach_id,
					'acsh_id' 			=> $acsh_id,
					'accsh_id' 			=> $accsh_id,
					'tran_reference' 	=> 'sale_invoice',
					'tran_reference_id' => $sale_inv_id,
					'tran_for' 			=> 'customer',
					'tran_for_id' 		=> $cust_id,
					'tran_mode' 		=> 'Cr',
					'tran_amount' 		=> $sale_price,
					'tran_dateE' 		=> $sale_date,
					'tran_details' 		=> 'Sold product price',
					'add_date' 	=> date('Y-m-d'),
					'add_time' 	=> date('h:i:s'),
					'add_by' 	=> $this->session->userdata('user_id'),
					'status' 	=> 0
				);
				// ---data submission to table-------
				// if ($this->db->insert('am_transaction', $sale_inventory_transaction)) {
				// 	$insert = 'success';
				// } else {
				// 	$insert = 'fail';
				// }
			}



			//----6---sale transaction Processing Fee--------
			if ($sale_processing_fee > 0) {
				$ach_id = 4;
				$acsh_id = 38;
				$accsh_id = 10;

				$sale_processing_fee_transaction = array(
					'ach_id' 			=> $ach_id,
					'acsh_id' 			=> $acsh_id,
					'accsh_id' 			=> $accsh_id,
					'tran_reference' 	=> 'sale_invoice',
					'tran_reference_id' => $sale_inv_id,
					'tran_for' 			=> 'customer',
					'tran_for_id' 		=> $cust_id,
					'tran_mode' 		=> 'Cr',
					'tran_amount' 		=> $sale_processing_fee,
					'tran_dateE' 		=> $sale_date,
					'tran_details' 		=> 'Sale Installment Processing Fee',
					'add_date' 	=> date('Y-m-d'),
					'add_time' 	=> date('h:i:s'),
					'add_by' 	=> $this->session->userdata('user_id'),
					'status' 	=> 0
				);
				// ---data submission to table-------
				// if ($this->db->insert('am_transaction', $sale_processing_fee_transaction)) {
				// 	$insert = 'success';
				// } else {
				// 	$insert = 'fail';
				// }
			}




			//----7---sale transaction Interest Fee--------
			if ($sale_interest > 0) {
				$ach_id = 4;
				$acsh_id = 9;
				$accsh_id = 11;

				$sale_interest_transaction = array(
					'ach_id' 			=> $ach_id,
					'acsh_id' 			=> $acsh_id,
					'accsh_id' 			=> $accsh_id,
					'tran_reference' 	=> 'sale_invoice',
					'tran_reference_id' => $sale_inv_id,
					'tran_for' 			=> 'customer',
					'tran_for_id' 		=> $cust_id,
					'tran_mode' 		=> 'Cr',
					'tran_amount' 		=> $sale_interest,
					'tran_dateE' 		=> $sale_date,
					'tran_details' 		=> 'Sale Installment Interest',
					'add_date' 	=> date('Y-m-d'),
					'add_time' 	=> date('h:i:s'),
					'add_by' 	=> $this->session->userdata('user_id'),
					'status' 	=> 0
				);
				// ---data submission to table-------
				// if ($this->db->insert('am_transaction', $sale_interest_transaction)) {
				// 	$insert = 'success';
				// } else {
				// 	$insert = 'fail';
				// }
			}





			//----8---sale transaction sale Discount Fee--------
			if ($sale_discount > 0) {
				$ach_id = 5;
				$acsh_id = 29;
				$accsh_id = 7;

				$sale_discount_transaction = array(
					'ach_id' 			=> $ach_id,
					'acsh_id' 			=> $acsh_id,
					'accsh_id' 			=> $accsh_id,
					'tran_reference' 	=> 'sale_invoice',
					'tran_reference_id' => $sale_inv_id,
					'tran_for' 			=> 'customer',
					'tran_for_id' 		=> $cust_id,
					'tran_mode' 		=> 'Dr',
					'tran_amount' 		=> $sale_discount,
					'tran_dateE' 		=> $sale_date,
					'tran_details' 		=> 'Sale Discount',
					'add_date' 	=> date('Y-m-d'),
					'add_time' 	=> date('h:i:s'),
					'add_by' 	=> $this->session->userdata('user_id'),
					'status' 	=> 0
				);
				// ---data submission to table-------
				// if ($this->db->insert('am_transaction', $sale_discount_transaction)) {
				// 	$insert = 'success';
				// } else {
				// 	$insert = 'fail';
				// }
			}





			//----9---sale transaction sale Scracth Card Fee--------
			if ($sale_scrach_card > 0) {
				$ach_id = 5;
				$acsh_id = 29;
				$accsh_id = 9;

				$sale_scratch_card_transaction = array(
					'ach_id' 			=> $ach_id,
					'acsh_id' 			=> $acsh_id,
					'accsh_id' 			=> $accsh_id,
					'tran_reference' 	=> 'sale_invoice',
					'tran_reference_id' => $sale_inv_id,
					'tran_for' 			=> 'customer',
					'tran_for_id' 		=> $cust_id,
					'tran_mode' 		=> 'Dr',
					'tran_amount' 		=> $sale_scrach_card,
					'tran_dateE' 		=> $sale_date,
					'tran_details' 		=> 'Sale Scratch Card',
					'add_date' 	=> date('Y-m-d'),
					'add_time' 	=> date('h:i:s'),
					'add_by' 	=> $this->session->userdata('user_id'),
					'status' 	=> 0
				);
				// ---data submission to table-------
				// if ($this->db->insert('am_transaction', $sale_scratch_card_transaction)) {
				// 	$insert = 'success';
				// } else {
				// 	$insert = 'fail';
				// }
			}




			//----10---sale transaction sale Payment Card Fee--------
			if ($sale_paid > 0) {
				$payment_mode = 'cash';

				if ($payment_mode == 'cash') {
					$ach_id = 2;
					$acsh_id = 5;
					$accsh_id = 17;
				} elseif ($payment_mode == 'bank') {
					$ach_id = 2;
					$acsh_id = 4;
					$accsh_id = 18;
				} elseif ($payment_mode == 'card') {
					$ach_id = 2;
					$acsh_id = 37;
					$accsh_id = 19;
				}

				$sale_payment_transaction = array(
					'ach_id' 			=> $ach_id,
					'acsh_id' 			=> $acsh_id,
					'accsh_id' 			=> $accsh_id,
					'tran_reference' 	=> 'sale_invoice',
					'tran_reference_id' => $sale_inv_id,
					'tran_for' 			=> 'customer',
					'tran_for_id' 		=> $cust_id,
					'tran_mode' 		=> 'Dr',
					'tran_amount' 		=> $sale_paid,
					'tran_dateE' 		=> $sale_date,
					'tran_details' 		=> 'Sale time collection',
					'add_date' 	=> date('Y-m-d'),
					'add_time' 	=> date('h:i:s'),
					'add_by' 	=> $this->session->userdata('user_id'),
					'status' 	=> 0
				);

				// pre($sale_payment_transaction);
				// if ($this->db->insert('am_transaction', $sale_payment_transaction)) {
				// 	$insert = 'success';
				// } else {
				// 	$insert = 'fail';
				// }


				// ------ start cash_transaction -------
				$main_cash = 1;
				$cheque_mode = 'in';

				$pay_cash_transaction_data = array(
					'cash_id' 					=> $main_cash,
					'cash_tran_reference_type' 	=> 'sale_invoice',
					'cash_tran_reference_id'	=> $sale_inv_id,
					'cash_tran_for' 			=> 'customer',
					'cash_tran_for_id'			=> $cust_id,
					'cash_tran_dateE' 			=> $sale_date,
					'cash_mode'					=> $cheque_mode,
					'cash_cheque_amount' 		=> $sale_paid,
					'cash_note'					=> 'Sale time collection',
					'add_date' 					=> date('Y-m-d'),
					'add_time' 					=> date('h:i:s'),
					'add_by' 					=> $this->session->userdata('user_id'),
					'status' 					=> 0,
				);
				// if ($this->db->insert('am_cash_transaction', $pay_cash_transaction_data)) {
				// 	$insert = 'success';
				// } else {
				// 	$insert = 'fail';
				// }
			} //----end if(sale_paid)-----





			//----11---sale transaction sale_A/C_receivable Card Fee--------
			if ($sale_ac_receivable > 0) {
				$ach_id = 2;
				$acsh_id = 3;
				$accsh_id = 12;
				$sale_ac_receivable_transaction = array(
					'ach_id' 			=> $ach_id,
					'acsh_id' 			=> $acsh_id,
					'accsh_id' 			=> $accsh_id,
					'tran_reference' 	=> 'sale_invoice',
					'tran_reference_id' => $sale_inv_id,
					'tran_for' 			=> 'customer',
					'tran_for_id' 		=> $cust_id,
					'tran_mode' 		=> 'Dr',
					'tran_amount' 		=> $sale_ac_receivable,
					'tran_dateE' 		=> $sale_date,
					'tran_details' 		=> 'Sale time invoice due',
					'add_date' 	=> date('Y-m-d'),
					'add_time' 	=> date('h:i:s'),
					'add_by' 	=> $this->session->userdata('user_id'),
					'status' 	=> 0
				);

				// pre($sale_ac_receivable_transaction);
				// if ($this->db->insert('am_transaction', $sale_ac_receivable_transaction)) {
				// 	$insert = 'success';
				// } else {
				// 	$insert = 'fail';
				// }
			} //----end if(sale_paid)-----
			if ($purc_item_battery_no != 'No Battery' or $purc_item_battery_no != '') {
				$product_update_data['purc_item_battery_no'] = $purc_item_battery_no;
			}

			// $this->db->trans_start();
			$this->db->trans_begin();
			$this->db->insert('am_sale_invoice', $data);

			// -----get inserted id and set to all relative transaction entry----------
			$sale_inv_due['sale_inv_id'] = $sale_inventory_transaction['tran_reference_id'] = $sale_processing_fee_transaction['tran_reference_id'] = $sale_interest_transaction['tran_reference_id'] = $sale_discount_transaction['tran_reference_id'] = $sale_scratch_card_transaction['tran_reference_id'] = $sale_payment_transaction['tran_reference_id'] = $pay_cash_transaction_data['cash_tran_reference_id'] = $sale_ac_receivable_transaction['tran_reference_id'] = $this_inv_id = $this->db->insert_id();

			// ----set sale last invoice value to sale installment-------
			isset($sale_installment) ? $n = count($sale_installment) : $n = 0;
			for ($i = 0; $i < $n; $i++) {
				$sale_installment[$i]['sale_inv_id'] = $this_inv_id;
			}

			if ($purc_item_battery_no != 'No Battery' or $purc_item_battery_no != '') {
				$this->db->where('purc_item_id', $purc_item_id)->update('am_purchase_invoice_items', $product_update_data);
			}
			$purc_item_id = $form_data['purc_item_id'];
			$this->Am_sale_model->salePurchaseItemStatusUpdate($purc_item_id, $sale_date);
			$this->db->insert('am_payment_sale_invoice_installments', $sale_inv_due);
			if ($sale_inst_quantity > 0) {
				$this->db->insert_batch('am_sale_invoice_installments', $sale_installment);
			}
			if ($sale_price > 0) {
				$this->db->insert('am_transaction', $sale_inventory_transaction);
			}
			if ($sale_processing_fee > 0) {
				$this->db->insert('am_transaction', $sale_processing_fee_transaction);
			}
			if ($sale_interest > 0) {
				$this->db->insert('am_transaction', $sale_interest_transaction);
			}
			if ($sale_discount > 0) {
				$this->db->insert('am_transaction', $sale_discount_transaction);
			}
			if ($sale_scrach_card > 0) {
				$this->db->insert('am_transaction', $sale_scratch_card_transaction);
			}
			if ($sale_paid > 0) {
				$this->db->insert('am_cash_transaction', $pay_cash_transaction_data);
				$sale_payment_transaction['bank_or_cash_book_id'] = $this->db->insert_id();
				$this->db->insert('am_transaction', $sale_payment_transaction);
			}
			if ($sale_ac_receivable > 0) {
				$this->db->insert('am_transaction', $sale_ac_receivable_transaction);
			}
			$this->db->trans_complete();

			// ---- end data insertion to am_sale_invoice ----

			// ----- method execution status -------
			if ($this->db->trans_status() === TRUE) {
				$this->session->set_flashdata('msg', 'Sale created successfully');
				$this->session->set_flashdata('msg_class', 'text-success');
				redirect('Am_sale_controller/saleInvoiceView/' . $this_inv_id);
			} else {
				$this->session->set_flashdata('msg', 'Sorry ! Unit add unsuccessfull.');
				$this->session->set_flashdata('msg_class', 'text-danger');
				redirect('Am_sale_controller/saleView');
			}
		} else {
			$this->session->set_flashdata('msg', 'Sorry ! You should select unique product.');
			$this->session->set_flashdata('msg_class', 'text-danger');
			redirect('Am_sale_controller/saleView');
		}
	}
	// ------END SALE PRODUCT --------------------
	// ------------------------------------------- 

	// -----START SALE INVOICE VIEW-----------
	// =======================================
	public function saleInvoiceView($id)
	{
		$sale_inv_id = $id;
		$data['saleInvoiceViews'] = $this->Am_sale_model->soldInvoiceDetails($id);
		// pre($data['saleInvoiceViews']);

		$user_id = $_SESSION['user_id'];
		$data['user_infos'] = $this->Am_sale_model->userInfo($user_id);

		foreach ($data['user_infos'] as $user_info) {
			$com_id = $user_info->com_id ? $user_info->com_id : 1;
			$branch_id = $user_info->branch_id ? $user_info->branch_id : 1;
		}
		$data['company_infos'] = $this->Am_sale_model->companyInfos($com_id, $branch_id);
		$data['installmentLists'] = $this->Am_sale_model->installmentList($sale_inv_id);
		$data['invoiceSetupImgs'] = $this->Am_sale_model->inviceSetupImgs($com_id, $branch_id);

		// pre($data);

		$this->load->view('am_sale/saleInvoiceView', $data);
	}
	// -----END SALE INVOICE VIEW-----------
	// =======================================

	// ========================================
	// ------SOLD PRODUCT VIEW PAGIANTION======



}   //----- end class----------
