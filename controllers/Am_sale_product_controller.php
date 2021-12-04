<?php
defined('BASEPATH') or exit('No direct script access allowed');
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Am_sale_product_controller extends CI_Controller
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
		$this->load->model('Am_sale_product_model');
	}
	// -------------------------------------------
	// ======START PURCHASE PRODUCT LISTING=======
	public function saleProductView()
	{
		$data['salableProducts'] = $this->Am_sale_product_model->salableProductList();
		$data['customers'] = $this->Am_sale_product_model->customerList();
		$data['references'] = $this->Am_sale_product_model->referenceList();

		$data['soldProducts'] = $this->Am_sale_product_model->soldProductList();
		// pre($data['soldProducts']);
		// pre($data['salableProducts']);
		$this->load->view('am_sale/saleProduct', $data);
	}

	public function saleViewSearch()
	{
		$form_data = $this->input->post();
		$sale_inv_id = $form_data['sale_inv_id'];

		// pre($sale_inv_id);
		$data['soldProducts'] = $this->Am_sale_product_model->soldProductList();
		$data['soldProductsSearched'] = $this->Am_sale_product_model->soldProductListSearched($sale_inv_id);
		// pre($data);
		$this->load->view('am_sale/saleView', $data);
	}

	// ------- ajax data collection by jQuery-----
	public function saleItemDetails()
	{
		$purc_item_id = $_POST['purc_item_id'];
		$data['items'] = $this->Am_sale_product_model->itemDetails($purc_item_id);
		echo json_encode($data['items']);
	}

	// ------- ajax data collection by jQuery-----
	public function customerDetails()
	{
		$cust_id = $_POST['cust_id'];
		$data['customers'] = $this->Am_sale_product_model->customerDetails($cust_id);
		echo json_encode($data['customers']);
	}

	public function interestRateCollection()
	{
		$sale_product_category_id = $_POST['sale_product_category_id'];
		// pre($form_data);
		$data['interestByCategory'] = $this->Am_sale_product_model->interestRateCollection($sale_product_category_id);
		echo json_encode($data);
	}

	// ------- ajax data collection by jQuery-----
	public function referenceDetails()
	{
		$refe_id = $_POST['refe_id'];
		$data['references'] = $this->Am_sale_product_model->referenceDetails($refe_id);
		echo json_encode($data['references']);
	}


	// ------END PURCHASE PRODUCT LISTING---------
	// ------------------------------------------- 

}   //----- end class----------
