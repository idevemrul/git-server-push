<?php
$config=[
       'user_registration_rules'=>[
                         [
                          'field' => 'user_name',
                          'label' => 'Full name',
                          'rules' => 'required|min_length[3]|max_length[30]'
                          ],
                          [
                          'field' => 'user_email',
                          'label' => 'Email',
                          'rules' => 'required|valid_email|min_length[3]|max_length[30]|is_unique[dbs_user.user_email]'
                          ],
                          [
                          'field' => 'user_password',
                          'label' => 'Password',
                          'rules' => 'required|min_length[3]|max_length[30]'
                          ],
                          /*[
                          'field' => 'user_re_password',
                          'label' => 'Retype Password',
                          'rules' => 'required'
                          ],*/
                          [
                          'field' => 'user_cell',
                          'label' => 'Mobile No',
                          'rules' => 'required|min_length[9]|max_length[20]'
                          ]
                     ],

        'file_open_validation'=>[
                          [
                          'field' => 'file_title',
                          'label' => 'Form title',
                          'rules' => 'required|min_length[3]|max_length[150]|is_unique[dbs_file.file_title]'

                          ],
                         [
                          'field' => 'file_description',
                          'label' => 'File description',
                          'rules' => 'required'
                          ]
                     ],
        'party_add_rules'=>[
                          [
                          'field' => 'party_mem_name',
                          'label' => 'Name',
                          'rules' => 'required|is_unique[dbs_party_member.party_mem_name]'

                          ],
                         [
                          'field' => 'party_mem_designation',
                          'label' => 'Designation',
                          ],
                         [
                          'field' => 'party_mem_email',
                          'label' => 'Email',
                          'rules' => 'required|is_unique[dbs_party_member.party_mem_email]'
                          ],
                         [
                          'field' => 'party_mem_cell',
                          'label' => 'Cell',
                          'rules' => 'required|is_unique[dbs_party_member.party_mem_cell]'
                          ],
                         [
                          'field' => 'party_mem_nid',
                          'label' => 'NID No',
                          'rules' => ''
                          ],
                         [
                          'field' => 'Passport',
                          'label' => 'party_mem_passport',
                          'rules' => ''
                          ]
                     ],
    'service_add_rules'=>[
                          [
                          'field' => 'serv_title',
                          'label' => 'Name',
                          'rules' => 'required|is_unique[dbs_service.serv_title]'

                          ],
                          [
                          'field' => 'serv_rate',
                          'label' => 'Name',
                          'rules' => 'required'

                          ],
                         [
                          'field' => 'serv_description',
                          'label' => 'Name',
                          'rules' => 'required'
                          ]
                     ],
    'addServiceToFile_validation'=>[
                          [
                          'field' => 'file_id',
                          'label' => 'Name',
                          'rules' => 'required'

                          ],
                          [
                          'field' => 'serv_id',
                          'label' => 'Name',
                          'rules' => 'required'

                          ]
                     ],

    'acc_head_validation'=>[
                          [
                          'field' => 'ach_title',
                          'label' => 'Name',
                          'rules' => 'required|is_unique[dbs_account_head.ach_title]'
                          ],
                     ],
    'acc_sub_head_validation'=>[
                          [
                          'field' => 'acsh_title',
                          'label' => 'Name',
                          'rules' => 'required|is_unique[dbs_account_sub_head.acsh_title]'
                          ],
                     ],
    'acc_coo_sub_head_validation'=>[
                          [
                          'field' => 'accsh_title',
                          'label' => 'Name',
                          'rules' => 'required|is_unique[dbs_account_coo_sub_head.accsh_title]'
                          ],
                     ],
    'due_payment_validation'=>[
                          [
                          'field' => 'old_pay_balance',
                          'label' => 'Name',
                          'rules' => 'required'
                          ],
                          [
                          'field' => 'pay_paid',
                          'label' => 'Name',
                          'rules' => 'required'
                          ],
                          [
                          'field' => 'new_pay_balance',
                          'label' => 'Name',
                          'rules' => 'required'
                          ],
                     ],
    'invoice_validation'=>[
                          [
                          'field' => 'inv_balance_amount',
                          'label' => 'Name',
                          'rules' => 'required'
                          ],
                          [
                          'field' => 'inv_amount',
                          'label' => 'Name',
                          'rules' => 'required'
                          ],
                     ],

    'exp_add_validation'=>[
                          [
                          'field' => 'expAcHead',
                          'label' => 'Name',
                          'rules' => 'required'
                          ],
                          [
                          'field' => 'expAcSubHead',
                          'label' => 'Name',
                          'rules' => 'required'
                          ],
                          [
                          'field' => 'expCustomerParty',
                          'label' => 'Name',
                          'rules' => 'required'
                          ],
                          [
                          'field' => 'expVoucherAmount',
                          'label' => 'Name',
                          'rules' => 'required'
                          ],
                          [
                          'field' => 'expPayAmount',
                          'label' => 'Name',
                          'rules' => 'required'
                          ],
                     ],
    'party_dueP_validation'=>[
                          [
                          'field' => 'partyNamePayable',
                          'label' => 'Name',
                          'rules' => 'required'
                          ],
                          [
                          'field' => 'partyDuePayment',
                          'label' => 'Name',
                          'rules' => 'required'
                          ],
                          [
                          'field' => 'partyDueAmount',
                          'label' => 'Name',
                          'rules' => 'required'
                          ],
                     ],
     'assesment_validation'=>[
                          [
                          'field' => 'ass_title',
                          'label' => 'Ass title',
                          'rules' => 'required|min_length[9]|max_length[9]|is_unique[dbs_assesment_year.ass_title]'
                          ],
                        ],
      'bank_validation'=>[
                          [
                          'field' => 'bank_name',
                          'label' => 'Bank name',
                          'rules' => 'required'

                          ],
                         [
                          'field' => 'bank_branchAddress',
                          'label' => 'Bank address',
                          ],
                         [
                          'field' => 'bank_branchCode',
                          'label' => 'Branch code',
                          'rules' => 'required|is_unique[dbs_bank_ac.bank_branchCode]'
                          ],
                         [
                          'field' => 'bank_accountNo',
                          'label' => 'A/C number',
                          'rules' => 'required|is_unique[dbs_bank_ac.bank_accountNo]'
                          ],
                         [
                          'field' => 'bank_accountTitle',
                          'label' => 'A/C title',
                          'rules' => ''
                          ],
                         [
                          'field' => 'bank_acccountType',
                          'label' => 'A/C type',
                          'rules' => ''
                          ]
                     ],


  //====================================================
  //----------------START MOTO SOFTWARE VALIDATION------
    'timeZone_validation'=>[
                          [
                          'field' => 'time_zone_title',
                          'label' => 'Time zone',
                          'rules' => 'required|is_unique[am_setup_time_zone.time_zone_title]'
                          ],
                     ],
    'currency_validation'=>[
                          [
                          'field' => 'currency_title',
                          'label' => 'Time zone',
                          'rules' => 'required'
                          ],
                     ],
    'company_validation'=>[
                          [
                          'field' => 'com_name',
                          'label' => 'Time zone',
                          'rules' => 'required'
                          ],
                          [
                          'field' => 'com_location',
                          'label' => 'Time zone',
                          'rules' => 'required'
                          ],
                          [
                          'field' => 'com_address',
                          'label' => 'Time zone',
                          'rules' => 'required'
                          ],
                          [
                          'field' => 'currency_id',
                          'label' => 'Time zone',
                          'rules' => 'required'
                          ],
                     ],
    'branch_validation'=>[
                          [
                          'field' => 'com_id',
                          'label' => 'Time zone',
                          'rules' => 'required'
                          ],
                          [
                          'field' => 'branch_name',
                          'label' => 'Time zone',
                          'rules' => 'required'
                          ],
                          [
                          'field' => 'branch_location',
                          'label' => 'Time zone',
                          'rules' => 'required'
                          ],
                          [
                          'field' => 'branch_address',
                          'label' => 'Time zone',
                          'rules' => 'required'
                          ],
                     ],
    'user_validation'=>[
                          [
                          'field' => 'com_id',
                          'label' => 'com_id',
                          'rules' => 'required'
                          ],
                          [
                          'field' => 'user_name',
                          'label' => 'user name',
                          'rules' => 'required'
                          ],
                          [
                          'field' => 'user_designation',
                          'label' => 'user designation',
                          'rules' => 'required'
                          ],
                          [
                          'field' => 'user_email',
                          'label' => 'user email',
                          'rules' => 'required|valid_email|min_length[3]|max_length[30]|is_unique[am_setup_users.user_email]'
                          ],
                          [
                          'field' => 'user_mobile',
                          'label' => 'Mobile number',
                          'rules' => 'required'
                          ],
                          [
                          'field' => 'user_password',
                          'label' => 'Password',
                          'rules' => 'required|min_length[3]|max_length[30]'
                          ],
                          [
                          'field' => 'user_address',
                          'label' => 'Address',
                          'rules' => 'required'
                          ],
                     ],
    'userRole_validation'=>[
                          [
                          'field' => 'user_role',
                          'label' => 'com_id',
                          'rules' => 'required'
                          ],
                        ],
    'category_validation'=>[
                          [
                          'field' => 'cat_title',
                          'label' => 'category title',
                          'rules' => 'required'
                          ],
                          [
                          'field' => 'cat_type',
                          'label' => 'category type',
                          'rules' => 'required'
                          ],
                        ],
    'commission_validation'=>[
                          [
                          'field' => 'comm_title',
                          'label' => 'category title',
                          'rules' => 'required'
                          ],
                          [
                          'field' => 'comm_type',
                          'label' => 'category type',
                          'rules' => 'required'
                          ],
                        ],
    'customer_validation'=>[
                          [
                          'field' => 'cust_name',
                          'label' => 'category title',
                          'rules' => 'required'
                          ],
                          [
                          'field' => 'cust_fname',
                          'label' => 'category type',
                          'rules' => 'required'
                          ],
                          [
                          'field' => 'cust_mobile',
                          'label' => 'category type',
                          'rules' => 'required'
                          ],
                          [
                          'field' => 'cust_nid',
                          'label' => 'category type',
                          'rules' => 'required'
                          ],
                        ],
     'exp_party_validation'=>[
                         [
                         'field' => 'party_name',
                         'label' => 'category title',
                         'rules' => 'required'
                         ],
                         [
                         'field' => 'party_fname',
                         'label' => 'category type',
                         'rules' => 'required'
                         ],
                         [
                         'field' => 'party_mobile',
                         'label' => 'category type',
                         'rules' => 'required'
                         ],
                         [
                         'field' => 'party_district',
                         'label' => 'District',
                         'rules' => 'required'
                         ],
                       ],
    'reference_validation'=>[
                          [
                          'field' => 'refe_mobile',
                          'label' => 'category title',
                          'rules' => 'required'
                          ],
                          [
                          'field' => 'refe_name',
                          'label' => 'category type',
                          'rules' => 'required'
                          ],
                        ],
    'supplier_validation'=>[
                          [
                          'field' => 'supp_name',
                          'label' => 'category title',
                          'rules' => 'required'
                          ],
                          [
                          'field' => 'supp_company',
                          'label' => 'category type',
                          'rules' => 'required'
                          ],
                          [
                          'field' => 'supp_mobile',
                          'label' => 'category type',
                          'rules' => 'required'
                          ],
                        ],
    'employee_validation'=>[
                          [
                          'field' => 'emp_name',
                          'label' => 'category title',
                          'rules' => 'required'
                          ],
                          [
                          'field' => 'emp_designation',
                          'label' => 'category type',
                          'rules' => 'required'
                          ],
                          [
                          'field' => 'emp_mobile',
                          'label' => 'category type',
                          'rules' => 'required'
                          ],
                          [
                          'field' => 'emp_nid',
                          'label' => 'category type',
                          'rules' => 'required'
                          ],
                        ],
    'interest_validation'=>[
                          [
                          'field' => 'interest_yearly',
                          'label' => 'interest_yearly',
                          'rules' => 'required'
                          ],
                          [
                          'field' => 'latefine_yearly',
                          'label' => 'latefine_yearly',
                          'rules' => 'required'
                          ],
                          [
                          'field' => 'week_in_year',
                          'label' => 'weekInYear ',
                          'rules' => 'required'
                          ],
                          [
                          'field' => 'interest_package',
                          'label' => 'interestPackage type',
                          'rules' => 'required'
                          ],
                        ],
                        
    'unit_validation'=>[
                          [
                          'field' => 'unit_title',
                          'label' => 'Unit title',
                          'rules' => 'required'
                          ],
                          [
                          'field' => 'unit_type',
                          'label' => 'category type',
                          'rules' => 'required'
                          ]
                        ],
    'manufacturer_validation'=>[
                          [
                          'field' => 'manuf_name',
                          'label' => 'manufacturer name',
                          'rules' => 'required'
                          ],
                          [
                          'field' => 'manuf_origin',
                          'label' => 'Manufacturer origin',
                          'rules' => 'required'
                          ]
                        ],
    'product_validation'=>[
                          [
                          'field' => 'product_name',
                          'label' => 'Product name',
                          'rules' => 'required'
                          ],
                          [
                          'field' => 'product_category_id',
                          'label' => 'Product category',
                          'rules' => 'required'
                          ],
                          [
                              'field' => 'product_color',
                              'label' => 'Product color',
                              'rules' => 'required'
                              ],
                          [
                          'field' => 'product_manuf_id',
                          'label' => 'Product category',
                          'rules' => 'required'
                          ],
                          [
                          'field' => 'product_model',
                          'label' => 'Product model',
                          'rules' => 'required'
                          ],
                          [
                          'field' => 'product_unit_id',
                          'label' => 'Product Unit',
                          'rules' => 'required'
                          ],
                        ],
  'purchase_invoice_validation'=>[
                          [
                          'field' => 'supp_id',
                          'label' => 'supp_id ',
                          'rules' => 'required'
                          ],
                          [
                          'field' => 'purc_chalan_no',
                          'label' => 'purc_chalan_no',
                          'rules' => 'required|is_unique[am_purchase_invoices.purc_chalan_no]'
                          ],
                          [
                          'field' => 'purc_inv_amount',
                          'label' => 'purc_inv_amount',
                          'rules' => 'required'
                          ],
                          [
                          'field' => 'purc_payment_method',
                          'label' => 'purc_payment_method',
                          'rules' => 'required'
                          ],
                        ],
     'purchase_invoice_validation'=>[
                          [
                          'field' => 'supp_id',
                          'label' => 'supp_id ',
                          'rules' => 'required'
                          ],
                          [
                          'field' => 'purc_chalan_no',
                          'label' => 'purc_chalan_no',
                          'rules' => 'required|is_unique[am_purchase_invoices.purc_chalan_no]'
                          ],
                          [
                          'field' => 'purc_inv_amount',
                          'label' => 'purc_inv_amount',
                          'rules' => 'required'
                          ],
                          [
                          'field' => 'purc_payment_method',
                          'label' => 'purc_payment_method',
                          'rules' => 'required'
                          ],
                        ],
     'purchase_invoice__search_validation'=>[
                         [
                         'field' => 'purc_id_search',
                         'label' => 'purc_chalan_no',
                         'rules' => 'trim'
                         ],
                         [
                         'field' => 'purc_supplier_search',
                         'label' => 'purc_inv_amount',
                         'rules' => 'trim'
                         ]
                       ],



  'purchase_invoice_items_validation'=>[
                          [
                          'field' => 'purc_id',
                          'label' => 'purc_id ',
                          'rules' => 'required'
                          ],
                          [
                          'field' => 'purc_item_purchase_price',
                          'label' => 'purc_item_purchase_price',
                          'rules' => 'required'
                          ],
                          [
                          'field' => 'purhcase_item_quantity',
                          'label' => 'purhcase_item_quantity',
                          'rules' => 'required'
                          ],
                        ],
      'sale_product'=>[
                        [
                        'field' => 'purc_item_chassis_no',
                        'label' => 'purc_item_chassis_no',
                        'rules' => 'required|is_unique[am_sale_invoice.purc_item_chassis_no]'
                        ],
                        [
                        'field' => 'cust_id',
                        'label' => 'cust_id',
                        'rules' => 'required'
                        ],
                        [
                        'field' => 'sale_price',
                        'label' => 'sale_price',
                        'rules' => 'required'
                        ],
                      ],
          'invoice_setup_validation'=>[
                          [
                          'field' => 'company_id',
                          'label' => 'Name',
                          'rules' => 'required'
                          ],
                          [
                          'field' => 'branch_id',
                          'label' => 'Name',
                          'rules' => 'required'
                          ],
                          [
                          'field' => 'invoice_type',
                          'label' => 'Name',
                          'rules' => 'required'
                          ],
                     ],
          'account_add_date_validation'=>[
                          [
                          'field' => 'com_id',
                          'label' => 'com_id',
                          'rules' => 'required'
                          ],
                          [
                          'field' => 'branch_id',
                          'label' => 'branch_id',
                          'rules' => 'required'
                          ],
                          [
                          'field' => 'business_open_date',
                          'label' => 'business_open_date',
                          'rules' => 'required'
                          ],
                          [
                          'field' => 'business_year_type',
                          'label' => 'business_year_type',
                          'rules' => 'required'
                          ],
                     ],
          'voucher_validation'=>[
                         [
                         'field' => 'vou_party_id',
                         'label' => 'vou_party_id',
                         'rules' => 'required'
                         ],
                         [
                         'field' => 'vou_note',
                         'label' => 'vou_note',
                         'rules' => 'required'
                         ],
                         [
                         'field' => 'ach_id',
                         'label' => 'ach_id',
                         'rules' => 'required'
                         ],
                         [
                         'field' => 'acsh_id',
                         'label' => 'acsh_id',
                         'rules' => 'required'
                         ],
                         [
                         'field' => 'accsh_id',
                         'label' => 'accsh_id',
                         'rules' => 'required'
                         ],
                         [
                         'field' => 'vou_date',
                         'label' => 'vou_date',
                         'rules' => 'required'
                         ],
                         [
                         'field' => 'vou_amount',
                         'label' => 'vou_amount',
                         'rules' => 'required'
                         ],
                         [
                         'field' => 'vou_paid',
                         'label' => 'vou_paid',
                         'rules' => 'required'
                         ],
                    ],
          'voucher_payment_validation'=>[
                              [
                              'field' => 'vou_id',
                              'label' => 'vou_id',
                              'rules' => 'required'
                              ],
                              [
                              'field' => 'vou_pay_date',
                              'label' => 'vou_pay_date',
                              'rules' => 'required'
                              ],
                              [
                              'field' => 'vou_pay_date',
                              'label' => 'vou_pay_date',
                              'rules' => 'required'
                              ],
                              [
                              'field' => 'vou_pay_by',
                              'label' => 'vou_pay_by',
                              'rules' => 'required'
                              ],
                              [
                              'field' => 'vou_pay_amount',
                              'label' => 'vou_pay_amount',
                              'rules' => 'required'
                              ],
                    ],
];


?>