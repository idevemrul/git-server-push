<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Userauth extends CI_Controller {

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
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index(){
		$this->load->view('authentication/login');
	}
	public function login()	{
		$this->load->view('authentication/login');
	}

	public function signup()		{
			$this->load->view('authentication/signup');
		}

	public function signupProcess()		{
		$config=[
			'upload_path'=>'./asset/img/',
			'allowed_types'=>'jpg|jpeg|png|gif|JPG|JPEG|PNG|GIF',
			'max_size'=>'30000',
			'max_width'=>'1024',
			'max_height'=>'768',
		];

		$this->load->library('upload',$config); //-- upload furles set up--

		$user_images='user_image';
		if ($this->form_validation->run('user_registration_rules') && $this->upload->do_upload($user_images)) {
			$userData=$this->input->post();
			$data=$this->upload->data();
			// print_r($data);
			$image_path=base_url("asset/img/".$data['raw_name'].$data['file_ext']);
			$userData['user_image']=$image_path;
			foreach ($userData as $key => $value) {
					// echo $key . ' => '.$value .'<br>';
					$key . ' => '.$value .'<br>';
				}
			// === ID GENERATOR======
			$this->load->model('Auth_model');
			$bb=$this->Auth_model->userIdCollection();
			foreach ($bb as $a) {
				$max_id = $a->user_id; //== DISPLAY VALUE FROM ARRAY==
			}
			    $hot_id_max = preg_replace("/[^0-9]/", '', $max_id); //== STRING TO NUMBER ==
			    $hot_id_new = $hot_id_max + 1; // == INCREASE VALuE 1==
			    $hot_id_new = sprintf("%09d", $hot_id_new); // ==MAKE 8 DECIMAL NUMBER==
			    $user_prefix = "ADM"; //== ID PREFIX==
			    $user_id = $user_prefix . $hot_id_new; //==READY NEW ID FOR USE
			    $userData['user_id']=$user_id;
			    $userData['user_type']='New';

			    // ---defult time jone selection-------
			    date_default_timezone_set("Asia/Dhaka");
			    $userData['user_date']=date('Y-m-d');
			    $userData['user_time']=date('h:i:s');

			// ---data inserstion-----------
			$this->Auth_model->userRegistrationProcess($userData);
			$this->session->set_flashdata('message','Registration successful.'); //--set seession data---
			// return redirect('Auth/signup');
			return redirect('Userauth/signup');
		}else{
			// echo "string";
			$upload_error=$this->upload->display_errors();
			// $this->load->view('authentication/signup',compact('upload_error'));
			$this->load->view('Userauth/signup',compact('upload_error'));
		}
	}


	public function forgotPass()
	{
		$this->load->view('authentication/forgot_password');
	}
	public function recoverPass(){
		$this->load->view('authentication/recover_password');
	}
	public function dashboard(){
		$this->load->view('index');
	}
	// public function logout(){
	// 	$this->load->view('Auth/logout');
	// }

	public function fileOpen(){
		$this->load->view('form/file_open.php');
	}
	public function fileList(){
		$this->load->view('form/file_list.php');
	}

}
