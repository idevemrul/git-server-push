<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Party_controller extends CI_Controller {

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

	// --------- Open file oppening form submision---------
		public function partyAdd(){
			$this->load->view('form/party_add.php');
		}

		public function partyAddProcess(){
			// echo "This is party add process";
			$config=[
				'upload_path'=>'./asset/img/party',
				'allowed_types'=>'jpg|jpeg|png|gif|JPG|JPEG|PNG|GIF',
				'max_size'=>'300000',
				'max_width'=>'1024',
				'max_height'=>'768',
			];

			$this->load->library('upload',$config); //-- upload furles set up--

			$image='party_mem_image';
			if ($this->form_validation->run('party_add_rules') && $this->upload->do_upload($image)) {
				$userData=$this->input->post();
				$data=$this->upload->data();
				$image_path=base_url("asset/img/party/".$data['raw_name'].$data['file_ext']);
				$userData['party_mem_image']=$image_path;

				// === ID GENERATOR======
				$this->load->model('Party_model');
				$bb=$this->Party_model->partyIdCollection();
				foreach ($bb as $a) {
					echo $max_id = $a->party_mem_id; //== DISPLAY VALUE FROM ARRAY==
					// exit();
				}
				$hot_id_max = preg_replace("/[^0-9]/", '', $max_id); //== STRING TO NUMBER ==
				    if ($hot_id_max < 1) {
				    	$hot_id_max = '00000';
				    }
				    $hot_id_new = $hot_id_max + 1; // == INCREASE VALuE 1==
				    $hot_id_new = sprintf("%09d", $hot_id_new); // ==MAKE 8 DECIMAL NUMBER==
				    $user_prefix = "PME"; //== ID PREFIX==
				    $party_mem_id = $user_prefix . $hot_id_new; //==READY NEW ID FOR USE
				    $userData['party_mem_id']=$party_mem_id;
				    // ---defult time jone selection-------
				    date_default_timezone_set("Asia/Dhaka");
				    $userData['party_mem_date']=date('Y-m-d');
				    $userData['party_mem_time']=date('h:i:s');
				    $userData['party_mem_by']=$this->session->userdata('user_id');

				// ---data inserstion-----------
				$this->Party_model->partyAddProcess($userData);
				$this->session->set_flashdata('msg_class','alert-success');
				$this->session->set_flashdata('message','Party add successfully.'); //--set seession data---
				// echo $this->session->flashdata('message');
				$this->session->flashdata('message');
				redirect('Party_controller/partyAdd');
			}else{
				$userData=$this->input->post();
				$a=validation_errors('party_mem_email');
				$b=form_error('party_mem_email','party_mem_name');
				// echo form_error('party_mem_passport','<i class="text-danger">','</i>');
				// exit();
					
				$this->session->set_flashdata('msg_class','alert-danger');
				$this->session->set_flashdata('message','Party add unsuccessfull.'); //--set seession data---
				$userData=$this->input->post();
				$upload_error=$this->upload->display_errors();
				$this->load->view('form/party_add.php'); //-- load view to display error message
			}
			// ------ end party add---------
		}

		public function partyProfileView($id){
			// echo "string".$id;
			$this->load->model('Party_model');
			$data=$this->Party_model->partyProfileView($id);
			$this->load->view('form/party_profile_view.php',$data);
		}

		public function partyList(){
			$this->load->model('Party_model');
			$data=$this->Party_model->partyList();
			$this->load->view('form/party_list.php',$data);
		}

		public function partyProfileDelete($id){
			$this->load->model('Party_model');
			$data=$this->Party_model->partyProfileDelete($id);
			redirect('Party_controller/partyList');
			// $this->load->view('form/party_list.php',$data);
		}
		// ------end party profile view----------


// ############################################
	
}   //----- end class----------