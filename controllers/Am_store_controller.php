<?php
defined('BASEPATH') or exit('No direct script access allowed');
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Am_store_controller extends CI_Controller
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
		$this->load->model('Am_store_model');
		$this->load->model('Am_sale_model');
	}



	// -------------------------------------------
	function inviceInfo()
	{
		$user_id = $_SESSION['user_id'];
		$data['user_infos'] = $this->Am_sale_model->userInfo($user_id);
		foreach ($data['user_infos'] as $user_info) {
			$com_id = $user_info->com_id ? $user_info->com_id : 1;
			$branch_id = $user_info->branch_id ? $user_info->branch_id : 1;
		}
		$status = 1;
		$data['invoiceSetupImgs'] = $this->Am_sale_model->inviceSetupImgs($com_id, $branch_id, $status);
	}
	// ======START STORE PRODUCT SUMMARY==========
	public function storeView()
	{
		$data['purchaseProductListsDistinct'] = $this->Am_store_model->purchaseInvoiceItemsListDistinct();
		$data['chalanLists'] = $this->Am_store_model->chalanList();
		$data['categoryLists'] = $this->Am_store_model->categoryList();
		$data['productLists'] = $this->Am_store_model->productList();

		$data['purchaseProductLists'] = $this->Am_store_model->purchaseInvoiceItemsList();

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

		$this->load->view('am_store/storeView', $data);
	}

	public function storeViewSearch()
	{
		$form_data = $this->input->post();
		$purc_id_search = $form_data['purc_id_search'];
		$prod_cat_search = $form_data['prod_cat_search'];
		$prod_stock_search = $form_data['prod_stock_search'];

		$data['purchaseProductListsDistinct'] = $this->Am_store_model->purchaseInvoiceItemsListDistinct();
		// $data['purchaseProductLists']=$this->Am_store_model->purchaseInvoiceItemsList();
		$data['chalanLists'] = $this->Am_store_model->chalanList();
		$data['categoryLists'] = $this->Am_store_model->categoryList();
		// $data['productLists']=$this->Am_store_model->productList();


		$where = '';
		$where .= ("a.product_id=x.product_id AND x.product_category_id=y.cat_id");

		!empty($purc_id_search) ? $where .= " AND a.product_id='$purc_id_search'" : '';
		!empty($prod_cat_search) ? $where .= " AND x.product_category_id='$prod_cat_search'" : '';

		if ($prod_stock_search == 'stock-out') {
			$where .= " AND a.product_id NOT IN (SELECT DISTINCT(w.product_id) FROM am_purchase_invoice_items w WHERE w.status=0)" . '';
		} elseif ($prod_stock_search == 'available') {
			$where .= " AND a.product_id IN (SELECT DISTINCT(w.product_id) FROM am_purchase_invoice_items w WHERE w.status=0)" . '';
		}

		$leftJoin = "LEFT JOIN (
								SELECT *,
										COUNT(b.product_id) as sold,
										b.purc_item_purchase_price as sold_purchase_price_total
								FROM `am_purchase_invoice_items` AS b	
								WHERE b.status!=0 GROUP BY b.product_id) AS c 
								ON a.status=c.status AND a.product_id=c.product_id
							LEFT JOIN (
								SELECT *,
										COUNT(d.product_id) as un_sold,
										d.purc_item_purchase_price as un_sold_purchase_price_total
								FROM `am_purchase_invoice_items` AS d	
								WHERE d.status=0 GROUP BY d.product_id) AS e 
								ON a.status=e.status AND a.product_id=e.product_id
							LEFT JOIN (
								SELECT *,
										r.product_sale_price as pro_sale_price
								FROM `am_setup_products` AS r) AS s 
								ON a.product_id=s.product_id";

		// if($prod_stock_search=='stock-out'){
		// 	$data['purchaseProductListsSearched']=$this->Am_store_model->purchaseInvoiceItemsListSearchedAvailable($where);
		// 	}else{
		$data['purchaseProductListsSearched'] = $this->Am_store_model->purchaseInvoiceItemsListSearched($where, $leftJoin);
		// 	}


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
		$this->load->view('am_store/storeView', $data);
	}


	// ------END START STORE PRODUCT SUMMARY--------
	// ------------------------------------------- 

	// -------------------------------------------
	// ======START STORE PRODUCT LISTING =======
	public function storeProductView()
	{
		$data['purchaseProductListsDistincts'] = $this->Am_store_model->purchaseInvoiceItemsListDistinct();
		$data['storeProductLists'] = $this->Am_store_model->storeProductView();
		$data['chalanLists'] = $this->Am_store_model->chalanList();
		$data['productLists'] = $this->Am_store_model->productList();
		$data['chassisLists'] = $this->Am_store_model->chassisList();

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
		$this->load->view('am_store/storeProductView', $data);
	}

	public function storeProductViewSearch()
	{
		$form_data = $this->input->post();
		$product_id_search = $form_data['product_id_search'];
		$product_chassis_search = $form_data['product_chassis_search'];

		$data['purchaseProductListsDistincts'] = $this->Am_store_model->purchaseInvoiceItemsListDistinct();
		$data['storeProductLists'] = $this->Am_store_model->storeProductView();
		$data['chalanLists'] = $this->Am_store_model->chalanList();
		$data['chassisLists'] = $this->Am_store_model->chassisList();

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

		$where = '';
		!empty($product_id_search) ? $where .= "purInv_itm.product_id='$product_id_search' AND " : '';
		!empty($product_chassis_search) ? $where .= "purInv_itm.purc_item_chassis_no='$product_chassis_search' AND " : '';

		$data['storeProductListsSearcheds'] = $this->Am_store_model->storeProductViewSearch($where);
		$this->load->view('am_store/storeProductView', $data);
	}
	// ------END STORE PRODUCT LISTING------------
	// ------------------------------------------- 

}   //----- end class----------
