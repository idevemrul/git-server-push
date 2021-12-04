<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Service_controller extends CI_Controller {

	public function __construct()
	{
		date_default_timezone_set("Asia/Dhaka");
		parent::__construct();
		//$this -> load -> model('application_model');
		$this->load->library('tank_auth');
		if(!$this->tank_auth->is_logged_in()) {
			redirect("auth/login");
		}

	}

	public function index(){
		redirect('Service_controller/serviceAdd');
	}

	// --------- Open file oppening form submision---------
		public function serviceAdd(){
			// echo "Service add";
			$this->load->view('form/service_add');
		}

		public function serviceAddProcess(){
			// echo "This is party add process";
			if ($this->form_validation->run('service_add_rules')) {
				// pre('form valided successfully');
				$serviceData=$this->input->post();

				// pre($serviceData);

				// === ID GENERATOR======
				$this->load->model('Service_model');
				$bb=$this->Service_model->serviceIdCollection();
				foreach ($bb as $a) {
					$max_id = $a->serv_id; //== DISPLAY VALUE FROM ARRAY==
					// exit();
				}
				$hot_id_max = preg_replace("/[^0-9]/", '', $max_id); //== STRING TO NUMBER ==
				    if ($hot_id_max < 1) {
				    	$hot_id_max = '00000';
				    }
				    $hot_id_new = $hot_id_max + 1; // == INCREASE VALuE 1==
				    $hot_id_new = sprintf("%09d", $hot_id_new); // ==MAKE 8 DECIMAL NUMBER==
				    $user_prefix = "SER"; //== ID PREFIX==
				    $serv_id = $user_prefix . $hot_id_new; //==READY NEW ID FOR USE
				    $serviceData['serv_id']=$serv_id;
				    // $id=$serviceData['file_id'];
				    // pre($id);
				    // ---defult time jone selection-------
				    date_default_timezone_set("Asia/Dhaka");
				    $serviceData['serv_date']=date('Y-m-d');
				    $serviceData['serv_time']=date('h:i:s');
				    $serviceData['serv_by']=$this->session->userData('user_id');

				// ---data inserstion-----------
				$this->Service_model->serviceAddProcess($serviceData);
				$this->session->set_flashdata('message','Service added successfully.'); //--set seession data---
				$this->session->set_flashdata('msg_class','alert-success pl-2');
				// echo $serviceData['party_mem_id'];
				// $aks='test hadup';
				// $this->load->view('form/file_view.php',['file_record'=>$file_record]);
				redirect('Service_controller/serviceAdd');
			}else{
				$this->session->set_flashdata('message','Service add unsuccessfull.'); //--set seession data---
				$this->session->set_flashdata('msg_class','alert-danger');
				$serviceData=$this->input->post();
				// $id=$serviceData['file_id'];
				// $upload_error=$this->upload->display_errors();
				redirect('Service_controller/serviceAdd');
			}
			// ------ end party add---------
		}

		public function serviceList(){
			// echo "string".$id;
			$this->load->model('Service_model');
			$data=$this->Service_model->serviceList();
			// $this->load->view('form/serci_profile_view.php',$data);
			$this->load->view('form/service_list.php',$data);
		}
		// ------end party profile view----------

		public function serviceListDelete($id){
			$this->load->model('Service_model');
			$data=$this->Service_model->serviceListDelete($id);
			redirect('Service_controller/serviceList');
		}
		public function serviceListReactive($id){
			// pre($id);
			$this->load->model('Service_model');
			$data=$this->Service_model->serviceListReactive($id);
			redirect('Service_controller/serviceList');
		}

		public function serviceListUpdate($id){
			$this->load->model('Service_model');
			$serv_data=$this->Service_model->serviceListUpdate($id);
			$this->load->view('form/service_update.php',['serv_data'=>$serv_data]);
		}
		public function serviceListUpdateProcess(){
			$serv=$this->input->post();
			// pre($ud);
			$this->load->model('Service_model');
			$file_record=$this->Service_model->serviceListUpdateProcess($serv);
			redirect('Service_controller/serviceList');
		}

		public function serviceListView($serv_id){
			$this->load->model('Service_model');
			$service=$this->Service_model->serviceListView($serv_id);
			$this->load->view('form/service_view.php',$service);
		}

		public function invoiceList(){
			$this->load->model('Service_model');
			$data['invoice']=$this->Service_model->invoiceList();
			// pre($invoice);
			$this->load->view('form/invoice_list.php',$data);
		}


// ############################################
	


}   //----- end class----------
?>