<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Remainder_controller extends CI_Controller {
	public function __construct(){
		date_default_timezone_set("Asia/Dhaka");
		parent::__construct();
		$this ->load-> model('Remainder_model');
		$this ->load-> model('Expenditure_model');
		$this ->load-> model('Forms_model');
		$this->load->library('tank_auth');
		if(!$this->tank_auth->is_logged_in()) {
			redirect("auth/login");
		}
	}

	public function index(){
		redirect('Userauth/dashboard');
	}

	// ############ remainder Add/view ###############
		public function remainder(){
			$data['remainderPartyList']=$this->Remainder_model->remainderPartyList();
			$data['remainderList']=$this->Remainder_model->remainderList();
			$data['filePartyMem']=$this->Remainder_model->filePartyForRemainder();
			// pre($data['filePartyMem']);
			$this->load->view('remainder/remainder.php',$data);
		}

		public function remainderAdd(){
			$form_data=$this->input->post();
			$form_data['rem_by']=$this->session->userdata('user_id');
		    date_default_timezone_set("Asia/Dhaka");
		    $form_data['rem_date']=date('Y-m-d');
		    $form_data['rem_time']=date('h:i:s');
		    $form_data['rem_status']=0;

			if($this->Remainder_model->remainderAdd($form_data)){
				$this->session->set_flashdata('mes','Remainder added successfully.');
				$this->session->set_flashdata('mes_class_success','alert-success');
				redirect('Remainder_controller/remainder/');
			}else{
				$this->session->set_flashdata('message','Remainder add unsuccessfull !!!.');
				$this->session->set_flashdata('message-class','text-danger');
				redirect('Remainder_controller/remainder/');
				}
		}
		public function remainderActionAdd($id){
			$this->Remainder_model->remainderActionAdd($id);
			redirect('Remainder_controller/remainder/');
		}

		public function remainderPartySearch(){
			$form_data=$this->input->post();
			// pre($form_data);
			$rem_party=$form_data['remSearchByPartyId'];
			$data['remainderPartyList']=$this->Remainder_model->remainderPartyList();
			$data['remainderList']=$this->Remainder_model->remainderPartySearch($rem_party);
			$this->load->view('remainder/remainder.php',$data);
		}

	// ########### remainder list view######
		public function remainderListAll(){
			$data['remainderPartyList']=$this->Remainder_model->remainderPartyList();
			$data['remainderListAll']=$this->Remainder_model->remainderListAll();
			$this->load->view('remainder/remainder_list_all.php',$data);
		}

		public function remainderStatusSearch(){
			$form_data=$this->input->post();
			$rem_status=$form_data['remainder_status_sarch'];
			$data['remainderPartyList']=$this->Remainder_model->remainderPartyList();
			$data['remainderListAll']=$this->Remainder_model->remainderStatusSearch($rem_status);
			$this->load->view('remainder/remainder_list_all.php',$data);
		}

		public function remainderPartySearchAll(){
			$form_data=$this->input->post();
			// pre($form_data);
			$rem_party=$form_data['remSearchByPartyId'];
			$data['remainderPartyList']=$this->Remainder_model->remainderPartyList();
			$data['remainderListAll']=$this->Remainder_model->remainderPartySearchAll($rem_party);
			$this->load->view('remainder/remainder_list_all.php',$data);
		}

		public function remainderDateSearch(){
			$form_data=$this->input->post();
			$date=explode(' - ',$form_data['date_range']);
			$date_from=$date[0];
			$date_to=$date[1];

			$data['remainderPartyList']=$this->Remainder_model->remainderPartyList();
			$data['remainderListAll']=$this->Remainder_model->remainderDateSearch($date_from,$date_to);
			$this->load->view('remainder/remainder_list_all.php',$data);
		}

		public function remainderAllSearchAll(){
			$date_from='';
			$date_to='';
			$form_data=$this->input->post();

			$date=explode(' - ',$form_data['date_range']);

			if($date[0]=='01/01/1970' && $date[1]=='01/01/1970'){
				$form_data['date_range']='';
			}else{
				$date_from=$date[0];
				$date_to=$date[1];
			}
			$rem_party=$form_data['remSearchByPartyId'];
			$rem_status=$form_data['remainder_status_sarch'];
			$date_r=$form_data['date_range'];

			// ----sratt display search party name-----
			if ($rem_party=='General') {
				$party_name='General';
			}elseif(!empty($rem_party)) {
				$party=$this->Remainder_model->remainderPartyName($rem_party);
				// pre($party);
				if(!empty($party)){
					$party_name=$party[0]->party_mem_name;
					}else{
						$party=$this->Remainder_model->remainderFileName($rem_party);
						if(!empty($party)){
							$party_name=$party[0]->file_title;
							}else{
								$party_name='';
							}
					}
				}else{
					$party_name='';
				}
			// ====END DISPLAY SEARCH PARTY NAME======

			if($rem_status==0){
				$rem_name='Pending';
			}else{
				$rem_name='Done';
			}

			if(empty($rem_party) && $rem_status=='' && !empty($date_r)){
				$data['searchBy']='Search by: Date ['.date('d-M-y',strtotime($date_from)) .' - '.date('d-M-y',strtotime($date_to)).' ].';
				$data['remainderListAll']=$this->Remainder_model->remainderDateSearch($date_from,$date_to);
				}elseif(empty($rem_party) && $rem_status=='' && empty($date_r)){
					$data['remainderListAll']=$this->Remainder_model->remainderListAll();
					}
					elseif(!empty($rem_party) && $rem_status=='' && empty($date_r)){
						$data['searchBy']='Search by: Party [ '.$party_name.' ].';
						$data['remainderListAll']=$this->Remainder_model->remainderPartySearchAll($rem_party);
						}elseif(!empty($rem_party) && $rem_status!='' && empty($date_r)){
							$data['searchBy']='Search by: Party [ '.$party_name.' ], Status [ '.$rem_name.' ].';
							$data['remainderListAll']=$this->Remainder_model->remainderPartyStatusSearchAll($rem_party,$rem_status);
							}elseif(!empty($rem_party) && $rem_status=='' && !empty($date_r)){
								$data['searchBy']='Search by: Party [ '.$party_name.' ], Date ['.date('d-M-y',strtotime($date_from)) .' - '.date('d-M-y',strtotime($date_to)).' ].';
								$data['remainderListAll']=$this->Remainder_model->remainderPartyDateSearchAll($rem_party,$date_from,$date_to);
								}elseif(!empty($rem_party) && $rem_status!='' && !empty($date_r)){
									$data['searchBy']='Search by: Party [ '.$party_name.' ], Status [ '.$rem_name.' ], Date ['.date('d-M-y',strtotime($date_from)) .' - '.date('d-M-y',strtotime($date_to)).' ].';
									$data['remainderListAll']=$this->Remainder_model->remainderPartyStatusDateSearchAll($rem_party,$rem_status,$date_from,$date_to);
									}elseif(empty($rem_party) && $rem_status!='' && empty($date_r)){
										$data['searchBy']='Search by: Status [ '.$rem_name.' ].';
										$data['remainderListAll']=$this->Remainder_model->remainderStatusSearch($rem_status);
										}elseif(empty($rem_party) && $rem_status!='' && !empty($date_r)){
											$data['searchBy']='Search by: Status [ '.$rem_name.' ], Date ['.date('d-M-y',strtotime($date_from)) .' - '.date('d-M-y',strtotime($date_to)).' ].';
											$data['remainderListAll']=$this->Remainder_model->remainderStatusDateSearchAll($rem_status,$date_from,$date_to);
											}

			$data['remainderPartyList']=$this->Remainder_model->remainderPartyList();
			$this->load->view('remainder/remainder_list_all.php',$data);
		}
}   //----- end class----------