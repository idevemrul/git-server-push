<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Am_internal_transfer_controller extends CI_Controller {

	public function __construct()
	{
		date_default_timezone_set("Asia/Dhaka");
		parent::__construct();
		//$this -> load -> model('application_model');
		$this->load->library('tank_auth');
		if(!$this->tank_auth->is_logged_in()) {
			redirect("auth/login");
		}
		$this->load->model('Am_internal_transfer_model');
		$this->load->model('Am_ledger_model');
		$this->load->library('pagination');
	}


	// -------start feed data----------
	public function bankWithBalanceAjax(){
		$bank_available_balance=$this->db->select('b.bank_id,
												b.bank_name,
												b.bank_accountNo,
												SUM(btran.bank_cheque_amount) in_amount,
												c.out_amount out_amount,
												(SUM(btran.bank_cheque_amount) - c.out_amount) balance_amount
												')
								->from('am_bank_transaction btran')
								->where(' btran.cheque_mode','in')
								->join('(SELECT bank_id,bank_name,bank_accountNo FROM am_setup_bank_ac) b','btran.bank_id=b.bank_id','left')
								->join('(SELECT bank_id,cheque_mode,SUM(bank_cheque_amount) out_amount FROM am_bank_transaction  WHERE cheque_mode="out" GROUP BY bank_id) c',' btran.bank_id=c.bank_id','left')
								->group_by('btran.bank_id','asc')
								->get()->result();
		echo json_encode($bank_available_balance);
	}
	public function bankBalanceCollectionAjax(){
		$bank_id=$this->input->post('bank_id');
	
		$bank_available_balance=$this->db->select('
												SUM(btran.bank_cheque_amount) in_amount,
												c.out_amount out_amount,
												(SUM(btran.bank_cheque_amount) - c.out_amount) balance_amount
												')
								->from('am_bank_transaction btran')
								->where(' btran.bank_id',$bank_id)
								->where('btran.cheque_mode','in')
								->join('(SELECT bank_id,cheque_mode,SUM(bank_cheque_amount) out_amount FROM am_bank_transaction  WHERE cheque_mode="out" GROUP BY bank_id) c',' btran.bank_id=c.bank_id','left')
								->group_by('btran.cheque_mode')
								->get()->result();
		echo json_encode($bank_available_balance);
	}

	public function cashBalanceCollectionAjax(){
		$cash_id=$this->input->post('cash_id');
	
		$cash_available_balance=$this->db->select('
												SUM(ctran.cash_cheque_amount) in_amount,
												c.out_amount out_amount,
												(SUM(ctran.cash_cheque_amount) - c.out_amount) balance_amount
												')
								->from('am_cash_transaction ctran')
								->where(' ctran.cash_id',$cash_id)
								->where('ctran.cash_mode','in')
								->join('(SELECT cash_id,cash_mode,SUM(cash_cheque_amount) out_amount FROM am_cash_transaction  WHERE cash_mode="out" GROUP BY cash_id) c',' ctran.cash_id=c.cash_id','left')
								->group_by('ctran.cash_mode')
								->get()->result();
		echo json_encode($cash_available_balance);
	}
	// -------end feed data -----------

	public function internalTransferView()
	{
		$form_data = $this->input->post();
		// pre($form_data);
		if (empty($form_data)) {
			isset($form_data['inte_tran_id']) ? $inte_tran_id = $form_data['inte_tran_id'] : '';
			$search_date_start = date("Y-m-d",strtotime("-7 days"));
			$search_date_end = date("Y-m-d");

			$data['searchBetweens'] = [
				'search_date_start' => $search_date_start,
				'search_date_end'	=> $search_date_end
			];
		} else {
			isset($form_data['inte_tran_id']) ? $inte_tran_id = $form_data['inte_tran_id'] : '';

			if (isset($form_data['search_purchase_date_range']) and $form_data['search_purchase_date_range'] != "01/01/1970 - 01/01/1970") {
				$date_range = explode('-', $form_data['search_purchase_date_range']);
				$search_date_start = date("Y-m-d", strtotime(trim($date_range[0])));
				$search_date_end = date("Y-m-d", strtotime(trim($date_range[1])));

				$data['searchBetweens'] = [
					'search_date_start' => $search_date_start,
					'search_date_end'	=> $search_date_end
				];
			}
		}

		//---start prepare query whare condition-------
			$where = '';
			if(isset($inte_tran_id) AND !empty($inte_tran_id)){
				$where .= 'trn.tran_reference_id=' . $inte_tran_id . ' AND ';
			}
			if(isset($search_date_start) AND ($search_date_start != '1970-01-01')){
				$where .= " trn.tran_dateE BETWEEN '$search_date_start' AND '$search_date_end' AND ";
			}

		$where .= "trn.tran_reference='internal_transfer' AND 
					trn.ach_id=ach.ach_id AND 
					trn.acsh_id=acsh.acsh_id AND 
					trn.accsh_id=accsh.accsh_id 
					";

		$data['inteTranTypeLists']=$this->Am_internal_transfer_model->internalTransferTypeList();
		$data['journals']=$this->Am_internal_transfer_model->internalTransferList($where);
		// pre($data['inteTranTypeLists']);
		$this->load->view('am_transfer/internalTransferView',$data);
	}


	public function internalTransferAdd(){
		$form_data=$this->input->post();
		$insert=1;
		
		// -----set internal transfer type------
			$senderBankArray = [2, 4];
			$senderCashArray = [1, 3];
			$receiverCashArray = [2, 3];
			$receiverBankArray = [1, 4];

		//----start  ----
		
		if(in_array($form_data['inte_tran_type'],$senderBankArray)){
			if($form_data['inte_tran_type']==4){
				$bank_note='Internal transfer Bank to Bank';
				$bank_tran_for='Bank';
			}else{
				$bank_note='Internal transfer Bank to Cash';
				$bank_tran_for='Cash';
			}
			$pay_bank_transaction_sender = array(
				'bank_id' 						=> $form_data['inte_tran_sender'],
				'bank_tran_reference_id' 		=> $form_data['inte_tran_type'],
				'bank_tran_reference_type' 		=> 'internal_transfer',
				'bank_tran_for_id' 				=> $form_data['inte_tran_receiver'],
				'bank_tran_for' 				=> $bank_tran_for,
				'bank_tran_method' 				=> 'Cash Deposit',
				'bank_tran_cheque_no' 			=> '',
				'bank_tran_cheque_date' 		=> '',
				'bank_tran_cheque_action' 		=> 'honored',
				'bank_tran_cheque_action_date' => $form_data['inte_tran_dateE'],
				'cheque_mode'					=> 'out',
				'bank_cheque_amount' 			=> $form_data['inte_tran_amount'],
				'bank_note'						=> $bank_note,
				'add_date' 			=> date('Y-m-d'),
				'add_time' 			=> date('h:i:s'),
				'add_by' 			=> $this->session->userdata('user_id'),
				'status' 			=> 1,
			);
			if($this->db->insert('am_bank_transaction',$pay_bank_transaction_sender)){
				$insert=1;
			}else{$insert=0;}

			$asset=2;
			$cash_at_bank=4;
			$main_bank=18;
			$transaction_bank_sender =array(	
				'ach_id' 			=> $asset,
				'acsh_id' 			=> $cash_at_bank,
				'accsh_id' 			=> $main_bank,
				'tran_reference' 	=> 'internal_transfer',
				'tran_reference_id' => $form_data['inte_tran_type'],
				'tran_for' 			=> 'Bank',
				'tran_for_id' 		=> $form_data['inte_tran_sender'],
				'tran_mode' 		=> 'Cr',
				'tran_amount' 		=> $form_data['inte_tran_amount'],
				'tran_dateE' 		=> $form_data['inte_tran_dateE'],
				'tran_details' 		=> $bank_note,
				'add_date' 	=> date('Y-m-d'),
				'add_time' 	=> date('h:i:s'),
				'add_by' 	=> $this->session->userdata('user_id'),
				'status' 	=> 0
			);

			if($this->db->insert('am_transaction',$transaction_bank_sender)){
				$insert=1;
			}else{$insert=0;}
		}
		
		if(in_array($form_data['inte_tran_type'],$receiverCashArray)){
			if($form_data['inte_tran_type']==3){
				$bank_note='Internal transfer Cash to Cash';
				$bank_tran_for="Cash";
			}else{
				$bank_note='Internal transfer Bank to Cash';
				$bank_tran_for="Bank";
			}
			$cash_transaction_receiver=[
				'cash_id' 					=> $form_data['inte_tran_receiver'],
				'cash_tran_reference_type' 	=> 'internal_transfer',
				'cash_tran_reference_id'	=> $form_data['inte_tran_type'],
				'cash_tran_for' 			=> $bank_tran_for,
				'cash_tran_for_id'			=> $form_data['inte_tran_sender'],
				'cash_tran_dateE' 			=> $form_data['inte_tran_dateE'],
				'cash_mode'					=> 'in',
				'cash_cheque_amount' 		=> $form_data['inte_tran_amount'],
				'cash_note'					=> $bank_note,
				'add_date' 					=> date('Y-m-d'),
				'add_time' 					=> date('h:i:s'),
				'add_by' 					=> $this->session->userdata('user_id'),
				'status' 					=> 0,
			];

			if($this->db->insert('am_cash_transaction',$cash_transaction_receiver)){
				$insert=1;
			}else{$insert=0;}

			$asset=2;
			$cash_at_hand=5;
			$main_cash=17;
			$transaction_cash_receiver =array(	
				'ach_id' 			=> $asset,
				'acsh_id' 			=> $cash_at_hand,
				'accsh_id' 			=> $main_cash,
				'tran_reference' 	=> 'internal_transfer',
				'tran_reference_id' => $form_data['inte_tran_type'],
				'tran_for' 			=> 'Cash',
				'tran_for_id' 		=> $form_data['inte_tran_receiver'],
				'tran_mode' 		=> 'Dr',
				'tran_amount' 		=> $form_data['inte_tran_amount'],
				'tran_dateE' 		=> $form_data['inte_tran_dateE'],
				'tran_details' 		=> $bank_note,
				'add_date' 	=> date('Y-m-d'),
				'add_time' 	=> date('h:i:s'),
				'add_by' 	=> $this->session->userdata('user_id'),
				'status' 	=> 0
			);
			if($this->db->insert('am_transaction',$transaction_cash_receiver)){
				$insert=1;
			}else{$insert=0;}
		}
		

		if(in_array($form_data['inte_tran_type'],$senderCashArray)){
			if($form_data['inte_tran_type']==3){
				$bank_note='Internal transfer Cash to Cash';
				$bank_tran_for="Cash";
			}else{
				$bank_note='Internal transfer Cash to Bank';
				$bank_tran_for="Bank";
			}
			$cash_transaction_sender=[
				'cash_id' 					=> $form_data['inte_tran_sender'],
				'cash_tran_reference_type' 	=> 'internal_transfer',
				'cash_tran_reference_id'	=> $form_data['inte_tran_type'],
				'cash_tran_for' 			=> $bank_tran_for,
				'cash_tran_for_id'			=> $form_data['inte_tran_receiver'],
				'cash_tran_dateE' 			=> $form_data['inte_tran_dateE'],
				'cash_mode'					=> 'out',
				'cash_cheque_amount' 		=> $form_data['inte_tran_amount'],
				'cash_note'					=> $bank_note,
				'add_date' 					=> date('Y-m-d'),
				'add_time' 					=> date('h:i:s'),
				'add_by' 					=> $this->session->userdata('user_id'),
				'status' 					=> 0,
			];

			if($this->db->insert('am_cash_transaction',$cash_transaction_sender)){
				$insert=1;
			}else{$insert=0;}
			
			$asset=2;
			$cash_at_hand=5;
			$main_cash=17;
			$transaction_cash_sender =array(	
				'ach_id' 			=> $asset,
				'acsh_id' 			=> $cash_at_hand,
				'accsh_id' 			=> $main_cash,
				'tran_reference' 	=> 'internal_transfer',
				'tran_reference_id' => $form_data['inte_tran_type'],
				'tran_for' 			=> 'Cash',
				'tran_for_id' 		=> $form_data['inte_tran_sender'],
				'tran_mode' 		=> 'Cr',
				'tran_amount' 		=> $form_data['inte_tran_amount'],
				'tran_dateE' 		=> $form_data['inte_tran_dateE'],
				'tran_details' 		=> $bank_note,
				'add_date' 	=> date('Y-m-d'),
				'add_time' 	=> date('h:i:s'),
				'add_by' 	=> $this->session->userdata('user_id'),
				'status' 	=> 0
			);
			if($this->db->insert('am_transaction',$transaction_cash_sender)){
				$insert=1;
			}else{$insert=0;}
		}
		
		if(in_array($form_data['inte_tran_type'],$receiverBankArray)){
			if($form_data['inte_tran_type']==4){
				$bank_note='Internal transfer Bank to Bank';
				$bank_tran_for="Bank";
			}else{
				$bank_note='Internal transfer Cash to Bank';
				$bank_tran_for="Cash";
			}
			$pay_bank_transaction_receiver = array(
				'bank_id' => $form_data['inte_tran_receiver'],
				'bank_tran_reference_id' 		=> $form_data['inte_tran_type'],
				'bank_tran_reference_type' 		=> 'internal_transfer',
				'bank_tran_for_id' 				=> $form_data['inte_tran_sender'],
				'bank_tran_for' 				=> $bank_tran_for,
				'bank_tran_method' 				=> 'Cash Deposit',
				'bank_tran_cheque_no' 			=> '',
				'bank_tran_cheque_date' 		=> '',
				'bank_tran_cheque_action' 		=> 'honored',
				'bank_tran_cheque_action_date' 	=> $form_data['inte_tran_dateE'],
				'cheque_mode'					=> 'in',
				'bank_cheque_amount' 			=> $form_data['inte_tran_amount'],
				'bank_note'			=> $bank_note,
				'add_date' 			=> date('Y-m-d'),
				'add_time' 			=> date('h:i:s'),
				'add_by' 			=> $this->session->userdata('user_id'),
				'status' 			=> 1,
			);

			if($this->db->insert('am_bank_transaction',$pay_bank_transaction_receiver)){
				$insert=1;
			}else{$insert=0;}

			$asset=2;
			$cash_at_bank=4;
			$main_bank=18;
			$transaction_bank_receiver =array(	
				'ach_id' 			=> $asset,
				'acsh_id' 			=> $cash_at_bank,
				'accsh_id' 			=> $main_bank,
				'tran_reference' 	=> 'internal_transfer',
				'tran_reference_id' => $form_data['inte_tran_type'],
				'tran_for' 			=> 'Bank',
				'tran_for_id' 		=> $form_data['inte_tran_receiver'],
				'tran_mode' 		=> 'Dr',
				'tran_amount' 		=> $form_data['inte_tran_amount'],
				'tran_dateE' 		=> $form_data['inte_tran_dateE'],
				'tran_details' 		=> $bank_note,
				'add_date' 	=> date('Y-m-d'),
				'add_time' 	=> date('h:i:s'),
				'add_by' 	=> $this->session->userdata('user_id'),
				'status' 	=> 0
			);
			if($this->db->insert('am_transaction',$transaction_bank_receiver)){
				$insert=1;
			}else{$insert=0;}
		}

		if($insert==1){

		}else{

		}

		if ($insert==1) {
			$this->session->set_flashdata('msg', 'Internal transaction successfull');
			$this->session->set_flashdata('msg_class', 'text-success');
			redirect('Am_internal_transfer_controller/internalTransferView');
		} else {
			$this->session->set_flashdata('msg', 'Sorry Internal transaction failed !!');
			$this->session->set_flashdata('msg_class', 'text-danger');
			redirect('Am_internal_transfer_controller/internalTransferView');
		}
	}
    


		


}   //----- end class----------
