<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Expenditure_controller extends CI_Controller {

	public function __construct(){
		date_default_timezone_set("Asia/Dhaka");
		parent::__construct();
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

	public function expenditure(){
		$data['ach']=$this->Expenditure_model->expenditureAch();
		$data['acshX']=$this->Expenditure_model->expenditureAcshX();
		$data['party']=$this->Expenditure_model->expenditureParty();
		$data['partyList']=$this->Expenditure_model->expenditurePartyList();
		$data['partyPayable']=$this->Expenditure_model->partyPayable();
		// pre($data['party']);
		$this->load->view('expenditure/voucher.php',$data);
	}

	public function expenditureAcsh(){
		$ach=$this->input->post('expAcHead');
		$acsh=$this->Expenditure_model->expenditureAcsh($ach);
		$options='';
		$options.='<option selected="selected" value="" disabled="" >Select Sub-head</option>';
		foreach ($acsh as $sh) {
			$options.='<option value="'.$sh->acsh_id.'">'.$sh->acsh_title.'</option>';
		}
		echo $options;
	}

	public function expenditureAccsh(){
		$acsh=$this->input->post('expAcSubHead');
		// echo $acsh;
		$accsh=$this->Expenditure_model->expenditureAccsh($acsh);
		// pre($acsh);
		$optionsX='<option selected="selected" value="" disabled="" >Select Sub-head</option>';
		foreach ($accsh as $sch) {
			$optionsX.='<option value="'.$sch->accsh_id.'">'.$sch->accsh_title.'</option>';
		}
		echo $optionsX;
	}

	public function expenditureAdd(){
		$file_data=$this->input->post();
		if (!isset($file_data['expAcCooSubHead'])) {
			$file_data['expAcCooSubHead']=0;
		}
		$file_id=$file_data['expCustomerParty'];
		// pre($file_id);
		$serv_data['ach_id']=$file_data['expAcHead'];
		$serv_data['acsh_id']=$file_data['expAcSubHead'];
		$serv_data['accsh_id']=$file_data['expAcCooSubHead'];
		$serv_data['party_mem_id']=$file_data['expCustomerParty'];
		$serv_data['vou_reference']=$file_data['expReference'];
		$serv_data['vou_amount']=$file_data['expVoucherAmount'];
		$serv_data['vou_pay_amount']=$file_data['expPayAmount'];
		$serv_data['vou_details']=$file_data['expDetails'];
		// $serv_data['exp_dateX']=$file_data['expDate'];
		// pre($serv_data);
		$serv_data['vou_by']=$this->session->userdata('user_id');
	    date_default_timezone_set("Asia/Dhaka");
	    $serv_data['vou_date']=date('Y-m-d');
	    $serv_data['vou_time']=date('h:i:s');

		if ($this->form_validation->run('exp_add_validation')) {
			$id=$this->Expenditure_model->newExpenditureID();
			$idX=$id[0]->vou_id+1;
			$expSuccess=$this->Expenditure_model->expenditureAdd($serv_data);
			if($expSuccess){
				$balanceAmount=$this->Expenditure_model->expPayBalanceAmount($file_id);
				// pre($balanceAmount);
				$old_pay_balance=round($balanceAmount[0]->vou_pay_balance,2);
				if ($old_pay_balance<1) {
					$old_pay_balance=0;
				}
				// pre($old_pay_balance);
				$pay_due=round(+$file_data['expVoucherAmount']-$file_data['expPayAmount'],2);
				$pay_balance=round($old_pay_balance+$file_data['expVoucherAmount']-$file_data['expPayAmount'],2);

				$vou_pay_data['vou_party_mem_id']=$file_data['expCustomerParty'];
				$vou_pay_data['vou_id']=$idX;
				$vou_pay_data['vou_pay_amount']=$file_data['expVoucherAmount'];
				$vou_pay_data['vou_pay_paid']=$file_data['expPayAmount'];
				$balancePayment=$pay_balance;
				$vou_pay_data['vou_pay_balance']=$balancePayment;
				$vou_pay_data['vou_pay_remarks']=$file_data['expDetails'];
				$vou_pay_data['vou_pay_dateE']=$file_data['expDate'];
				$vou_pay_data['vou_pay_by']=$this->session->userdata('user_id');
			    date_default_timezone_set("Asia/Dhaka");
			    $vou_pay_data['vou_pay_date']=date('Y-m-d');
			    $vou_pay_data['vou_pay_time']=date('h:i:s');
			    $vou_pay_data['vou_pay_status']=0;
			    $expSuccess2=$this->Expenditure_model->vouPayAdd($vou_pay_data);
			}
			// pre($expSuccess2);
			if ($expSuccess2) {
				$vou_pay_id_collect=$this->Expenditure_model->lastVouPayId();
				$vou_pay_id=$vou_pay_id_collect[0]->vou_pay_id;

				$transaction_sale=array(
							'ach_id'=>$file_data['expAcHead'],
							'acsh_id'=>$file_data['expAcSubHead'],
							'accsh_id'=>$file_data['expAcCooSubHead'],
							'file_id'=>$file_data['expCustomerParty'],
							'tran_mode'=>'Dr',
							'tran_reference'=>$vou_pay_id,
							'tran_dateE'=>$file_data['expDate'],
							'tran_dr'=>$file_data['expVoucherAmount'],
							'tran_by'=>$this->session->userdata('user_id'),
							'tran_date'=>date('Y-m-d'),
							'tran_time'=>date('h:i:s'),						
							'tran_status'=>0,
						);
				// pre($transaction_sale);
					$expSuccess3= $this->Forms_model->transactionForInvoice($transaction_sale);

				if ($file_data['expPayAmount'] >= 0.01) {
						$transaction_paid=array(
							'ach_id'=>'2',
							'acsh_id'=>'5',
							'accsh_id'=>'0',
							'file_id'=>$file_data['expCustomerParty'],
							'tran_mode'=>'Cr',
							'tran_reference'=>$vou_pay_id,
							'tran_dateE'=>$file_data['expDate'],
							'tran_cr'=>$file_data['expPayAmount'],
							'tran_by'=>$this->session->userdata('user_id'),
							'tran_date'=>date('Y-m-d'),
							'tran_time'=>date('h:i:s'),						
							'tran_status'=>0,
						);
						$expSuccess3= $this->Forms_model->transactionForInvoice($transaction_paid);
					}
					
					if ($pay_due >= 0.01) {
						$transaction_due=array(
							'ach_id'=>'3',
							'acsh_id'=>'7',
							'accsh_id'=>'0',
							'file_id'=>$file_data['expCustomerParty'],
							'tran_mode'=>'Cr',
							'tran_reference'=>$vou_pay_id,
							'tran_dateE'=>$file_data['expDate'],
							'tran_cr'=>$pay_due,
							'tran_by'=>$this->session->userdata('user_id'),
							'tran_date'=>date('Y-m-d'),
							'tran_time'=>date('h:i:s'),						
							'tran_status'=>0,
						);
						$expSuccess3= $this->Forms_model->transactionForInvoice($transaction_due);
					}
			}
		}
		// pre($expSuccess3);
		if($expSuccess3){
			$this->session->set_flashdata('mes','Payment added to file successfully.');
			$this->session->set_flashdata('mes_class_success','alert-success');
			
			redirect('Expenditure_controller/expenditureView/'.$idX);
		}else{
			$this->session->set_flashdata('message','Payment unsuccessfull !!!.');
			$this->session->set_flashdata('message-class','text-danger');
			redirect('Expenditure_controller/expenditure/');
			}
	}

	public function expenditureView($id){
		$data['expenditure']=$this->Expenditure_model->expenditureView($id);
		$partyId=$data['expenditure'][0]->party_mem_id;
		$data['partyPay']=$this->Expenditure_model->partyPay($partyId);
		// pre($data['partyPay']);
		$this->load->view('expenditure/payment_voucher.php',$data);
	}


	// ========= party payment paid paymentDueSummary==========
	public function paymentDueSummary(){
		$ajaxData=$this->input->post();
		$party_id=$ajaxData['partyNamePayable'];
		$partyDue=$this->Expenditure_model->expPayBalanceAmount($party_id);
		$duePayment=$partyDue[0]->vou_pay_balance;
		// print_r($partyDue);
		echo $duePayment;
		// pre($partyDue);
	}

	public function partyDuePayAdd(){
		$form_data=$this->input->post();
		if ($this->form_validation->run('party_dueP_validation')) {
			// pre($form_data);

		    $vou_pay_data['vou_party_mem_id']=$form_data['partyNamePayable'];
			$vou_pay_data['vou_id']='0';
			$vou_pay_data['vou_pay_amount']=0;
			$vou_pay_data['vou_pay_paid']=$form_data['partyDuePayment'];
			// $balancePayment=$pay_balance;
			$vou_pay_data['vou_pay_balance']=$form_data['partyDueAmount'];
			$vou_pay_data['vou_pay_remarks']=$form_data['partyDuePayemntDetails'];
			$vou_pay_data['vou_pay_dateE']=date('Y-m-d');
			$vou_pay_data['vou_pay_by']=$this->session->userdata('user_id');
		    date_default_timezone_set("Asia/Dhaka");
		    $vou_pay_data['vou_pay_date']=date('Y-m-d');
		    $vou_pay_data['vou_pay_time']=date('h:i:s');
		    $vou_pay_data['vou_pay_status']=0;

		    $success=$this->Expenditure_model->vouPayAdd($vou_pay_data);
		    // pre($success);
		}
		if($success){

			$form_data['last_payment_id']=$this->Expenditure_model->lastPaymentID();
			$pay_id=$form_data['last_payment_id'][0]->vou_pay_id;

			// ------- account receivable debit----------
			$transaction_due=array(
				'ach_id'=>'3',
				'acsh_id'=>'7',
				'accsh_id'=>'0',
				'file_id'=>$form_data['partyNamePayable'],
				'tran_mode'=>'Dr',
				'tran_reference'=>$pay_id,
				'tran_dateE'=>date('Y-m-d'),
				'tran_dr'=>$form_data['partyDuePayment'],
				'tran_by'=>$this->session->userdata('user_id'),
				'tran_date'=>date('Y-m-d'),
				'tran_time'=>date('h:i:s'),						
				'tran_status'=>0,
				);
				$expSuccess3= $this->Forms_model->transactionForInvoice($transaction_due);
			// ----cash receive credit------------
			$transaction_paid=array(
					'ach_id'=>'2',
					'acsh_id'=>'5',
					'accsh_id'=>'0',
					'file_id'=>$form_data['partyNamePayable'],
					'tran_mode'=>'Cr',
					'tran_reference'=>$pay_id,
					'tran_dateE'=>date('Y-m-d'),
					'tran_dateE'=>date('Y-m-d'),
					'tran_cr'=>$form_data['partyDuePayment'],
					'tran_by'=>$this->session->userdata('user_id'),
					'tran_date'=>date('Y-m-d'),
					'tran_time'=>date('h:i:s'),						
					'tran_status'=>0,
				);
				$expSuccess3= $this->Forms_model->transactionForInvoice($transaction_paid);
			
			// pre($expSuccess3);

			if($expSuccess3){
				$this->session->set_flashdata('mes','Party due payment paid successfully.');
				$this->session->set_flashdata('mes_class_success','alert-success');
				redirect('Expenditure_controller/duePayView/'.$pay_id);
			}else{
				$this->session->set_flashdata('message','Payment unsuccessfull !!!.');
				$this->session->set_flashdata('message-class','text-danger');
				redirect('Expenditure_controller/expenditure/');
				}
		}
	}
	
	public function duePayView($id){
		$data['expenditure']=$this->Expenditure_model->duePayView($id);
		$partyId=$data['expenditure'][0]->vou_party_mem_id;
		$data['partyPay']=$this->Expenditure_model->partyPay($partyId);
		// pre($data['expenditure']);
		$this->load->view('expenditure/voucher_duePay.php',$data);
	}

	public function paymentListDuePay(){
		$data['ach']=$this->Expenditure_model->expenditureAch();
		$data['acshX']=$this->Expenditure_model->expenditureAcshX();
		$data['party']=$this->Expenditure_model->expenditureParty();
		$data['partyPayable']=$this->Expenditure_model->partyPayable();

		$data['duePay']=$this->Expenditure_model->paymentListDuePay();
		$this->load->view('expenditure/payemnt_list_duePay.php',$data);
	}

	public function vouPaySearch(){
		$form_data=$this->input->post();
		$search_id=$form_data['voucherSearchByPartyId'];
		// pre($form_data);
		$data['ach']=$this->Expenditure_model->expenditureAch();
		$data['acshX']=$this->Expenditure_model->expenditureAcshX();
		$data['party']=$this->Expenditure_model->expenditureParty();
		$data['partyList']=$this->Expenditure_model->expenditurePartyList();
		$data['partyPayable']=$this->Expenditure_model->partyPayable();
		// ---search data-----
		$data['vouPaySearch']=$this->Expenditure_model->vouPaySearch($search_id);
		// pre($form_data);
		$this->load->view('expenditure/voucher.php',$data);
	}
	public function vouDuePaySearch(){
		$form_data=$this->input->post();
		$search_id=$form_data['voucherSearchByPartyId'];
		// pre($form_data);
		$data['ach']=$this->Expenditure_model->expenditureAch();
		$data['acshX']=$this->Expenditure_model->expenditureAcshX();
		$data['party']=$this->Expenditure_model->expenditureParty();
		$data['partyPayable']=$this->Expenditure_model->partyPayable();
		
		$data['duePaySearch']=$this->Expenditure_model->vouDuePaySearch($search_id);
		$this->load->view('expenditure/payemnt_list_duePay.php',$data);
	}
}   //----- end class----------