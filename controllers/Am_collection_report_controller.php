<?php
defined('BASEPATH') or exit('No direct script access allowed');
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Am_collection_report_controller extends CI_Controller
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
        $this->load->model('Am_collection_report_model');
    }

    //-------------------------------------------------------
    // ===============START CUSTOMER LEDGER VIEW=============
    public function collectionReportView()
    {
        $form_data = $this->input->post();
        $where_cash = '';
        $where_bank = '';
        $searched_by = '';
        $this->session->unset_userdata('searched_by');

        // pre($form_data);

        // =======START SEARCH QUERY SETUP===========
        if (empty($form_data)) {
            isset($form_data['cash_id']) ? $cash_id = $form_data['cash_id'] : '';
            $search_date_start = date("Y-m-d", strtotime("-7 days"));
            $search_date_end = date("Y-m-d");
            $search_date_opening_balance_start = date('Y-m-d', strtotime("1970-01-01"));
            $search_date_opening_balance_end = date('Y-m-d', strtotime("$search_date_start -1 day"));

            $data['searchBetweens'] = [
                'search_date_start' => $search_date_start,
                'search_date_end'    => $search_date_end
            ];

            $where_cash .= " cash_tran_dateE BETWEEN '$search_date_start' AND '$search_date_end' AND ";
            $where_bank .= " bank_tran_cheque_action_date BETWEEN '$search_date_start' AND '$search_date_end' AND ";
            $where_cash .= "cash_mode='in' ";
            $where_bank .= "cheque_mode='in' ";
            $searched_by .= 'Collection Mode= All || ';
            $searched_by .= " Date= " . date('d-M-Y', strtotime($search_date_start)) . " to " . date('d-M-Y', strtotime($search_date_end));
            $this->session->set_userdata('searched_by', $searched_by);
        } else {

            // =========income mode==========
            if (!empty($form_data['collection_mode'])) {
                $collection_mode = $form_data['collection_mode'];
                if ($collection_mode == 'Cash') {
                    $where_cash .= "cash_mode='in' AND ";
                    $where_bank .= "cheque_mode='NOTHING' AND ";
                    $searched_by .= 'Collection Mode=' . $collection_mode . ' || ';
                } else {
                    $where_cash .= "cash_mode='NOTHING' AND ";
                    $where_bank .= "cheque_mode='in' AND ";
                    $searched_by .= 'Collection Mode=' . $collection_mode . ' || ';
                }
            } else {
                $where_cash .= "cash_mode='in' AND ";
                $where_bank .= "cheque_mode='in' AND ";
                $searched_by .= 'Collection Mode= All || ';
            }

            // ====income type=======
            if (!empty($form_data['income_type'])) {
                $income_type = $form_data['income_type'];
                $where_cash .= "cash_tran_reference_type='$income_type' AND ";
                $where_bank .= "bank_tran_reference_type='$income_type' AND ";
                $searched_by .= 'Income Type=' . $income_type . ' || ';
            }

            // =====date range=====
            if (isset($form_data['search_purchase_date_range']) and $form_data['search_purchase_date_range'] != "01/01/1970 - 01/01/1970") {
                $date_range = explode('-', $form_data['search_purchase_date_range']);
                $search_date_start = date("Y-m-d", strtotime(trim($date_range[0])));
                $search_date_end = date("Y-m-d", strtotime(trim($date_range[1])));
                $search_date_opening_balance_start = date('Y-m-d', strtotime("1970-01-01"));
                $search_date_opening_balance_end = date('Y-m-d', strtotime("$search_date_start -1 day"));

                $where_cash .= " cash_tran_dateE BETWEEN '$search_date_start' AND '$search_date_end' AND ";
                $where_bank .= " bank_tran_cheque_action_date BETWEEN '$search_date_start' AND '$search_date_end' AND ";
                $searched_by .= " Date= " . date('d-M-Y', strtotime($search_date_start)) . " to " . date('d-M-Y', strtotime($search_date_end));
            } else {
                $search_date_opening_balance_start = date('Y-m-d', strtotime("1970-01-01"));
                $search_date_opening_balance_end = date('Y-m-d', strtotime("1970-01-01"));

                // $where_cash .= " cash_tran_dateE BETWEEN '$search_date_opening_balance_start' AND '$search_date_opening_balance_end' AND ";
                // $where_bank .= " bank_tran_cheque_action_date BETWEEN '$search_date_opening_balance_start' AND '$search_date_opening_balance_end' AND ";
            }

            // ==== DEFAULT=======
            // ====income type=======
            $where_cash .= "1=1";
            $where_bank .= "1=1";
            $this->session->set_userdata('searched_by', $searched_by);
            // =======END SEARCH QUERY SETUP===========


        }

        // pre($where_cash);
        // pre($where_bank);
        // ---end prepare query------------
        $data['incomeTypes'] = $this->Am_collection_report_model->distinctIncomeTypes();
        $data['collection_lists'] = $this->Am_collection_report_model->collectionList($where_cash, $where_bank);
        // pre($data['incomeTypes']);
        $this->load->view('am_ledger/collectionReportView', $data);
    }

    public function customerNameX()
    {
        // $a=10;
        $test = $this->Am_feed_data_model->customerNameX(10);
        // pre($test);
    }
    // =============END CUSTOMER LEDGER VIEW================
    //------------------------------------------------------

    // public function collectionReportView()
    // {
    //     $data['collection_lists'] = $this->Am_collection_report_model->collectionList();
    //     $this->load->view('am_ledger/collectionReportView', $data);
    // }
}   //----- end class----------
