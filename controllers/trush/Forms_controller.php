<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Forms_controller extends CI_Controller {

	public function __construct()
	{
		date_default_timezone_set("Asia/Dhaka");
		parent::__construct();
		//$this -> load -> model('application_model');
		$this->load->library('tank_auth');
		if(!$this->tank_auth->is_logged_in()) {
			redirect("auth/login");
		}
		$this->load->model('Forms_model');
	}

	// --------- Open file oppening form submision---------
		public function fileOpen(){
			$this->load->view('form/file_open');
		}

		public function fileOpenProcess(){
			// $this->form_validation->set_rules('file_tile','User Name','max_length[2]');
			if ($this->form_validation->run('file_open_validation')) {
				$serv_data=$this->input->post();
				$this->load->model('Forms_model');

				// === NEW ID GENERATOR======
					$this->load->model('Forms_model');
					$file_id=$this->Forms_model->fileIdCollection();
					foreach ($file_id as $a) {
						$max_id = $a->file_id; //== DISPLAY VALUE FROM ARRAY==
					}
					    $hot_id_max = preg_replace("/[^0-9]/", '', $max_id); //== STRING TO NUMBER ==
					    if ($hot_id_max < 1) {
					    	$hot_id_max = 'FIL00000';
					    }
					    $hot_id_new = $hot_id_max + 1; // == INCREASE VALuE 1==
					    $hot_id_new = sprintf("%09d", $hot_id_new); // ==MAKE 8 DECIMAL NUMBER==
					    $user_prefix = "FIL"; //== ID PREFIX==
					    $file_id = $user_prefix . $hot_id_new; //==READY NEW ID FOR USE
					    $serv_data['file_id']=$file_id;
				// ---defult time jone selection-------
						$serv_data['file_by']=$this->session->userdata('user_id');
					    date_default_timezone_set("Asia/Dhaka");
					    $serv_data['file_date']=date('Y-m-d');
					    $serv_data['file_time']=date('h:i:s');

					    $openingBalance=array(
						   'file_id'=>$file_id,
						   'inv_id'=>0,
						   'pay_inv_amount'=>0,
						   'pay_paid'=>0,
						   'pay_balance'=>$serv_data['file_opening_balance'],
						   'pay_remarks'=>'File opening balance',
						   'pay_by'=>$this->session->userdata('user_id'),
						   'pay_dateE'=>date('Y-m-d'),
						   'pay_date'=>date('Y-m-d'),
						   'pay_time'=>date('h:i:s'),
						   'pay_status'=>0,
						);
					  // pre($openingBalance);
					// ---data inserstion-----------
					$this->Forms_model->fileOpenProcess($serv_data);
					$this->Forms_model->fileOpeningBalanceProcess($openingBalance);

					if ($serv_data['file_opening_balance'] >= 0.01) {
						$pay_id_collect=$this->Forms_model->lastPaymentId();
						$pay_id=$pay_id_collect[0]->pay_id;

						$fileOpeningBalanceDue=array(
							'ach_id'=>'2',
							'acsh_id'=>'3',
							'accsh_id'=>'0',
							'file_id'=>$file_id,
							'tran_mode'=>'Dr',
							'tran_reference'=>$pay_id,
							'tran_dateE'=>date('Y-m-d'),
							'tran_dr'=>$serv_data['file_opening_balance'],
							'tran_by'=>$this->session->userdata('user_id'),
							'tran_date'=>date('Y-m-d'),
							'tran_time'=>date('h:i:s'),						
							'tran_status'=>0,
						);
						$this->Forms_model->transactionForInvoice($fileOpeningBalanceDue);
					}
					if ($serv_data['file_opening_balance'] >= 0.01) {
						$pay_id_collect=$this->Forms_model->lastPaymentId();
						$pay_id=$pay_id_collect[0]->pay_id;

						$fileOpeningBalance=array(
							'ach_id'=>'4',
							'acsh_id'=>'12',
							'accsh_id'=>'0',
							'file_id'=>$file_id,
							'tran_mode'=>'Cr',
							'tran_reference'=>$pay_id,
							'tran_dateE'=>date('Y-m-d'),
							'tran_cr'=>$serv_data['file_opening_balance'],
							'tran_by'=>$this->session->userdata('user_id'),
							'tran_date'=>date('Y-m-d'),
							'tran_time'=>date('h:i:s'),						
							'tran_status'=>0,
						);
						$this->Forms_model->transactionForInvoice($fileOpeningBalance);
					}

					$this->session->set_flashdata('msg','File opend successfully');
					$this->session->set_flashdata('msg_class','alert-success');
					redirect('Forms_controller/fileOpen');
			}else{
				$this->session->set_flashdata('msg','Fiel open failed');
				$this->session->set_flashdata('msg_class','alert-danger');
				$this->load->view('form/file_open.php');
			}
			
		}
	// ---- close file opening close---------

	// ---Open File listing-----------
		public function fileList(){
			$this->load->model('Forms_model');
			$file_record=$this->Forms_model->fileList();

			$this->load->view('form/file_list.php',['file_record'=>$file_record]);
		}

		public function fileListView($file_id){
			$this->load->model('Forms_model');
			$file_record=$this->Forms_model->fileView($file_id);
			$file_record['assesment_year']=$this->Forms_model->assesmentYear();
			// pre($file_record);
			$this->load->view('form/file_view.php',$file_record);
		}

		public function fileListUpdate($id){
			$this->load->model('Forms_model');
			$serv_data=$this->Forms_model->fileListUpdateCollection($id);
			$this->load->view('form/file_update.php',['serv_data'=>$serv_data]);
		}
		public function fileListUpdateProcess(){
			$flud=$this->input->post();
			// pre($ud);
			$this->load->model('Forms_model');
			$file_record=$this->Forms_model->fileListUpdateProcess($flud);
			redirect('Forms_controller/fileList');
		}


		public function fileListDelete($file_id){
			$this->load->model('Forms_model');
			$file_record=$this->Forms_model->fileDelete($file_id);
			redirect('Forms_controller/fileList');
		}
		public function fileListRestore($file_id){
			$this->load->model('Forms_model');
			$file_record=$this->Forms_model->fileRestore($file_id);
			redirect('Forms_controller/fileList');
		}

		public function addPartyToFile(){
			// if ($this->form_validation->run('file_open_validation')) {
				$serv_data=$this->input->post();
				$this->load->model('Forms_model');
				// $data=$this->Forms_model->addPartyToFile($serv_data);
				// ---defult time jone selection-------
					$serv_data['file_party_by']=$this->session->userdata('user_id');
				    date_default_timezone_set("Asia/Dhaka");
				    $serv_data['file_party_date']=date('Y-m-d');
				    $serv_data['file_party_time']=date('h:i:s');
				    $file_id=$serv_data['file_id'];
				    $data=$this->Forms_model->addPartyToFile($serv_data);

					// ---data inserstion-----------
					
					$this->session->set_flashdata('msg','Party assigned successfully');
					$this->session->set_flashdata('msg_class','alert-success');

					redirect('Forms_controller/fileListView/'.$file_id);
			/*}else{
				$serv_data=$this->input->post();
				$file_id=$serv_data['file_id'];
				redirect('Forms_controller/fileListView/'.$file_id);
			}*/
		}

		public function removePartyToFile(){
			$file_party=$this->input->post();
			$party=array(
				'file_id' => $file_party['file_id'],
				'party_mem_id' => $file_party['party_mem_id']
				);
			// echo $party['file_id'];
			// pre($party);
			$this->load->model('Forms_model');
			$this->Forms_model->removePartyToFile($party);
			$this->session->set_flashdata('mes','Party deleted form this file successfully.');
			$this->session->set_flashdata('mes_class_success','alert-success');
			redirect('Forms_controller/fileListView/'.$party['file_id']);
			// pre($file_party);
		}

		public function addServiceToFile(){
			if ($this->form_validation->run('addServiceToFile_validation')) {
				// pre('service form valid');
				$serv_data=$this->input->post();
				$this->load->model('Forms_model');
				// $data=$this->Forms_model->addPartyToFile($serv_data);
				// ---defult time jone selection-------
					$serv_data['file_serv_by']=$this->session->userdata('user_id');
				    date_default_timezone_set("Asia/Dhaka");
				    $serv_data['file_serv_date']=date('Y-m-d');
				    $serv_data['file_serv_time']=date('h:i:s');
				    $file_id=$serv_data['file_id'];
				    $data=$this->Forms_model->addServiceToFile($serv_data);

					// ---data inserstion-----------
					
					$this->session->set_flashdata('msg','Service added successfully');
					$this->session->set_flashdata('msg_class','alert-success');

					redirect('Forms_controller/fileListView/'.$file_id);
			}else{
				$serv_data=$this->input->post();
				$file_id=$serv_data['file_id'];
				$this->session->set_flashdata('msg','Service add Failed !!');
				$this->session->set_flashdata('msg_class','alert-danger');
				redirect('Forms_controller/fileListView/'.$file_id);
			}
		}

		public function removeServiceToFile(){
			$file_service=$this->input->post();
			$service=array(
				'file_serv_id' => $file_service['file_serv_id'],
				'file_id' => $file_service['file_id'],
				'serv_id' => $file_service['serv_id']
				);
			$this->load->model('Forms_model');
			$this->Forms_model->removeServiceToFile($service);
			$this->session->set_flashdata('mes','Service deleted form this file successfully.');
			$this->session->set_flashdata('mes_class','alert-success');
			redirect('Forms_controller/fileListView/'.$service['file_id']);
			// pre($file_party);
		}
	// ---Close File listing----------

	// =====START INVOICE SECTION=========
		public function invServiceSelect(){
			$serv_idN=$this->input->post('serv_id');
			$this->load->model('Forms_model');
			$serv_data=$this->Forms_model->invServiceSelect($serv_idN);
			// pre($serv_rateN);
			foreach ($serv_data as $sd) {
			}
			$s_rate=$sd->serv_rate;
			$s_title=$sd->serv_title;
			$data = array(
					'success' => true,
					'serv_rate'=>$s_rate,
					'serv_title'=>$s_title,
					'abc'=>20,
				);
			echo json_encode($data);
		}

		public function serviceAddToRow(){
			$party_mem_id=$this->input->post('party_mem_id');
			$serv_id=$this->input->post('serv_id');
			$serv_title=$this->input->post('serv_title');
			$serv_rate=$this->input->post('serv_rate');
			$serv_quantity=$this->input->post('serv_quantity');
			$serv_sub_total=$this->input->post('serv_sub_total');

			$html='
					<tr>
						<td id="serialN"> </td>
						<td> <input type="hidden" name="party_mem_idX[]" value="'.$party_mem_id.'">'.$party_mem_id.'</td>
						<td> <input type="hidden" name="serv_idX[]" value="'.$serv_id.'">'.$serv_id.'</td>
						<td><input type="hidden" name="serv_titleX[]" value="'.$serv_title.'">'.$serv_title.'</td>
						<td><input type="hidden" name="serv_rateX[]" value="'.$serv_rate.'">'.$serv_rate.'</td>
						<td><input type="hidden" name="serv_quantityX[]" value="'.$serv_quantity.'">'.$serv_quantity.'</td>
						<td id="sub_totalN">'.$serv_sub_total.'</td>
						<td class="text-right"><button id="removeRow" type="button" class="btn btn-danger" style="padding:0 5px;">Remove</button></td>
					</tr>
				';
			// $sn=$ud;
			$data = array(
				'test'=>'test',
				'html'=>$html,
				);
			echo json_encode($data);
		}

		// ------ INVOICE SUBMISSION--------
		public function invFormSubmission(){
			$dataX=$this->input->post();
			$file_id=$dataX['file_id'];
			// pre($dataX);
			if ($this->form_validation->run('invoice_validation')){
					$this->load->model('Forms_model');
				// ---- insert data to file_service table------
					$data=$this->Forms_model->addServiceToFileFromInv();

				//==== data submission to invoice table=======
					$assesment_year=$dataX['assesment_year'];
					$n= count($dataX['serv_idX']);
					$inv_amount=round($dataX['inv_amount'],2);
					$inv_discount_amount=round($dataX['inv_discount_amount'],2);
					$inv_paid_amount=round($dataX['inv_paid_amount'],2);
					$inv_dateE=$dataX['inv_date'];
					$inv_remarks=$dataX['inv_remarks'];

					if (empty($inv_remarks)) {
						$inv_remarks='Sale invoice';
					}

					$invoice=array(
							'file_id'=>$file_id,
							'ass_id'=>$assesment_year,
							'inv_amount'=>$inv_amount,
							'inv_discount_amount'=>$inv_discount_amount,
							'inv_paid_amount'=>$inv_paid_amount,
							'inv_remarks'=>$inv_remarks,
							'inv_by'=>$this->session->userdata('user_id'),
							'inv_date'=>date('Y-m-d'),
							'inv_time'=>date('h:i:s'),
							'inv_status'=>0,
						);
					$invoice_insert=$this->Forms_model->insertInvoice($invoice);
				// --- END DATA SUBMISSION TO INVOICE TABLE----

				// ===== start data submission to invoiceDetails table======
					$invoice_id=$this->Forms_model->lastInvoiceId();
					$inv_id=$invoice_id[0]->inv_id;

					$dataK=$this->input->post();
					$n= count($dataK['serv_idX']);
					for ($k=0; $k <$n ; $k++) { 
						$party_mem_id=$dataK['party_mem_idX'][$k];
						$serv_id=$dataK['serv_idX'][$k];
						$serv_quantity=$dataK['serv_quantityX'][$k];
						$serv_rate=round($dataK['serv_rateX'][$k],2);

						$invoice_details=array(
								'inv_id'=>$inv_id,
								'party_mem_id'=>$party_mem_id,
								'serv_id'=>$serv_id,
								'inv_det_quantity'=>$serv_quantity,
								'inv_det_price'=>$serv_rate,
								'inv_det_status'=>0
							);
						$invDetails=$this->Forms_model->addInvoiceDetails($invoice_details);
					}
				// -----END INVOICE DETAILS DATA SUBMISSION--------

				// =====start invoice payemnt add to payment table for due balance======
					$balanceAmount=$this->Forms_model->payBalanceAmount($file_id);
					$old_pay_balance=round($balanceAmount[0]->pay_balance,2);
					// pre($old_pay_balance);
					$pay_paid=round($inv_discount_amount+$inv_paid_amount,2);
					$pay_balance=round($old_pay_balance+$inv_amount-$pay_paid,2);
					$payment=array(
							'file_id'=>$file_id,
							'inv_id'=>$inv_id,
							'pay_inv_amount'=>$inv_amount,
							'pay_paid'=>$pay_paid,
							'pay_balance'=>$pay_balance,
							'pay_remarks'=>$inv_remarks,
							'pay_by'=>$this->session->userdata('user_id'),
							'pay_dateE'=>$inv_dateE,
							'pay_date'=>date('Y-m-d'),
							'pay_time'=>date('h:i:s'),
							'pay_status'=>0,
						);
					$invoice_insert=$this->Forms_model->insertPayment($payment);
				// -----END INVOICE PAYMENT ADD TO PAYMENT TABLE FOR DUE BALANCE--------

				// =====START TRANSACTIN DATA SUBMISSION=========
					$dataL=$this->input->post();
					$n= count($dataL['serv_idX']);
					$file_id=$dataL['file_id'];
					$inv_amount=round($dataL['inv_amount'],2);
					$inv_discount_amount=round($dataL['inv_discount_amount'],2);
					$inv_balance_amount=round($dataL['inv_balance_amount'],2);
					$inv_date=$dataL['inv_date'];
					$inv_paid_amount=round($dataL['inv_paid_amount'],2);
					$inv_remarks=$dataL['inv_remarks'];
					$inv_remarks=$dataL['inv_remarks'];

					if ($inv_paid_amount >= 0.01) {
						$pay_id_collect=$this->Forms_model->lastPaymentId();
						$pay_id=$pay_id_collect[0]->pay_id;

						$transaction_paid=array(
							'ach_id'=>'2',
							'acsh_id'=>'5',
							'accsh_id'=>'0',
							'file_id'=>$file_id,
							'tran_mode'=>'Dr',
							'tran_reference'=>$pay_id,
							'tran_dateE'=>$inv_date,
							'tran_dr'=>$inv_paid_amount,
							'tran_by'=>$this->session->userdata('user_id'),
							'tran_date'=>date('Y-m-d'),
							'tran_time'=>date('h:i:s'),						
							'tran_status'=>0,
						);
						$this->Forms_model->transactionForInvoice($transaction_paid);
					}
					
					if ($inv_discount_amount >= 0.01) {
						$pay_id_collect=$this->Forms_model->lastPaymentId();
						$pay_id=$pay_id_collect[0]->pay_id;

						$transaction_discount=array(
							'ach_id'=>'5', 		
							'acsh_id'=>'29',  	
							'accsh_id'=>'0',	
							'file_id'=>$file_id,
							'tran_mode'=>'Dr',
							'tran_reference'=>$pay_id,
							'tran_dateE'=>$inv_date,
							'tran_dr'=>$inv_discount_amount,
							'tran_by'=>$this->session->userdata('user_id'),
							'tran_date'=>date('Y-m-d'),
							'tran_time'=>date('h:i:s'),						
							'tran_status'=>0,
						);
						$this->Forms_model->transactionForInvoice($transaction_discount);
					}
					
					if ($inv_balance_amount >= 0.01) {
						$pay_id_collect=$this->Forms_model->lastPaymentId();
						$pay_id=$pay_id_collect[0]->pay_id;

						$transaction_due=array(
							'ach_id'=>'2',
							'acsh_id'=>'3',
							'accsh_id'=>'0',
							'file_id'=>$file_id,
							'tran_mode'=>'Dr',
							'tran_reference'=>$pay_id,
							'tran_dateE'=>$inv_date,
							'tran_dr'=>$inv_balance_amount,
							'tran_by'=>$this->session->userdata('user_id'),
							'tran_date'=>date('Y-m-d'),
							'tran_time'=>date('h:i:s'),						
							'tran_status'=>0,
						);
						$this->Forms_model->transactionForInvoice($transaction_due);
					}

					$pay_id_collect=$this->Forms_model->lastPaymentId();
					$pay_id=$pay_id_collect[0]->pay_id;
					$transaction_sale=array(
							'ach_id'=>'4',
							'acsh_id'=>'12',
							'accsh_id'=>'0',
							'file_id'=>$file_id,
							'tran_mode'=>'Cr',
							'tran_reference'=>$pay_id,
							'tran_dateE'=>$inv_date,
							'tran_cr'=>$inv_amount,
							'tran_by'=>$this->session->userdata('user_id'),
							'tran_date'=>date('Y-m-d'),
							'tran_time'=>date('h:i:s'),						
							'tran_status'=>0,
						);
					$this->Forms_model->transactionForInvoice($transaction_sale);
						
				// -----end transaction data submission----------

				$this->session->set_flashdata('mes','Service added to file successfully.');
				$this->session->set_flashdata('mes_class_success','alert-success');
				$file_id=$dataX['file_id'];
				redirect('Forms_controller/fileListView/'.$file_id);
			}else{
				$this->session->set_flashdata('message','Please use valid data !!!.');
				$this->session->set_flashdata('message-class','text-danger');
				redirect('Forms_controller/fileListView/'.$file_id);
				}
		}
	// --------END INVOICE SECTION--------
		public function snUserDataReset(){
			$this->session->set_userdata('service','');
			$sn= 'sn destroy success';
			$data=array(
					'sn'=>$sn,
				);
			echo json_encode($data);
		}
	// -------start due payment------
		public function duePayment(){
			// pre('due payment');
			if ($this->form_validation->run('due_payment_validation')){
				$form_data=$this->input->post();
				$file_id=trim($form_data['file_id']);
				$pay_paid=round($form_data['pay_paid'],2);
				$pay_dateE=$form_data['pay_date'];
				$duePayment=array(
						'file_id'=>$file_id,
						'pay_inv_amount'=>0,
						'pay_paid'=>$pay_paid,
						'pay_balance'=>$form_data['new_pay_balance'],
						'pay_remarks'=>$form_data['pay_remarks'],
						'pay_by'=>$this->session->userdata('user_id'),
						'pay_dateE'=>$pay_dateE,
						'pay_date'=>date('Y-m-d'),
						'pay_time'=>date('h:i:s'),
						'pay_status'=>0,
					);
				$this->load->model('Forms_model');
				$this->Forms_model->insertPayment($duePayment);
				// --------- due payment transaction table-------
				$pay_id_collect=$this->Forms_model->lastPaymentId();
				$pay_id=$pay_id_collect[0]->pay_id;
				// pre($pay_id);
				if ($pay_paid >= 0.01) {
					$transaction_paid=array(
						'ach_id'=>'2',
						'acsh_id'=>'5',
						'accsh_id'=>'0',
						'file_id'=>$file_id,
						'tran_mode'=>'Dr',
						'tran_reference'=>$pay_id,
						'tran_dateE'=>$pay_dateE,
						'tran_dr'=>$pay_paid,
						'tran_by'=>$this->session->userdata('user_id'),
						'tran_date'=>date('Y-m-d'),
						'tran_time'=>date('h:i:s'),						
						'tran_status'=>0,
					);
					 $this->Forms_model->transactionForInvoice($transaction_paid);
				}
				if ($pay_paid >= 0.01) {
					$transaction_due=array(
						'ach_id'=>'2',
						'acsh_id'=>'3',
						'accsh_id'=>'0',
						'file_id'=>$file_id,
						'tran_mode'=>'Cr',
						'tran_reference'=>$pay_id,
						'tran_dateE'=>$pay_dateE,
						'tran_cr'=>$pay_paid,
						'tran_by'=>$this->session->userdata('user_id'),
						'tran_date'=>date('Y-m-d'),
						'tran_time'=>date('h:i:s'),						
						'tran_status'=>0,
					);
					if($this->Forms_model->transactionForInvoice($transaction_due)){
						$this->session->set_flashdata('mes','Payment successfull.');
						$this->session->set_flashdata('mes_class_success','alert-success');
						redirect('Forms_controller/fileListView/'.$file_id);
					}else{
						$this->session->set_flashdata('mes','Payment failed!!!');
						$this->session->set_flashdata('mes_class_success','alert-success');
						redirect('Forms_controller/fileListView/'.$file_id);
					}
				}
			}else{
				$this->session->set_flashdata('message','Please use valid data !!!.');
				$this->session->set_flashdata('msg_class','alert-danger');
				redirect('Forms_controller/fileListView/'.$file_id);
				}
			// ------- transaction table-----
		}
	//======== START DUE PAYMENT===== 
	// ===add assesment year======
		public function assesmentYearAdd(){
			$form_data=$this->input->post();
			$file_id=$form_data['file_id'];
			$data=array(
				'ass_title'=>$form_data['ass_title'],
				'ass_by'=>$this->session->userdata('user_id'),
				'ass_date'=>date('Y-m-d'),
				'ass_time'=>date('h:i:s'),
				'ass_status'=>0,
				);
			// pre($data);
			if ($this->form_validation->run('assesment_validation')) {
				if($this->Forms_model->assesmentYearAdd($data)){
					$this->session->set_flashdata('mes','Assesment year added successfully.');
					$this->session->set_flashdata('mes_class_success','alert-success');
					redirect('Forms_controller/fileListView/'.$file_id);
				}else{
					$this->session->set_flashdata('message','Try again !!!.');
					$this->session->set_flashdata('msg_class','alert-danger');
					redirect('Forms_controller/fileListView/'.$file_id);
				}
			}else{
				$this->session->set_flashdata('message','Try again with valid data !!!.');
				$this->session->set_flashdata('msg_class','alert-danger');
				redirect('Forms_controller/fileListView/'.$file_id);
			}
		}
	// ---end assesment year------

}   //----- end class----------