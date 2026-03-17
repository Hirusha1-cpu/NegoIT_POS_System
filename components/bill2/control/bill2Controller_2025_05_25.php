<?php
if (passwordExpire())
   header('Location: index.php?components=authenticate&action=change_pw&message=Your%20Password%20Has%20Expired.%20Please%20Change%20it%20NOW&re=fail');

switch ($_REQUEST['action']) {
   case "set_district":
      include_once 'components/bill2/modle/bill2Module.php';
      setDistrict();
      header('Location: index.php?components=bill2&action=home&s=' . $_GET['s'] . '&cust_odr=' . $_GET['cust_odr']);
      break;

   case "home":
      include_once 'components/bill2/modle/bill2Module.php';
      getDistrict();
      if ($systemid == 1 || $systemid == 4 || $systemid == 10 || $systemid == 15)
         getSalesman2();
      if (isMobile())
         include_once 'components/bill2/view/m_billing.php';
      else
         include_once 'components/bill2/view/billing.php';
      break;

   case "cust-list":
      include_once 'template/common.php';
      listCust($sub_system);
      include_once 'template/ajax_list.php';
      break;

   case "mob-list":
      include_once 'template/common.php';
      listCust($sub_system);
      include_once 'template/ajax_list.php';
      break;

   case "sm-list":
      include_once 'template/common.php';
      listSM($sub_system);
      include_once 'template/ajax_list.php';
      break;

   case "more_cust":
      include_once 'template/common.php';
      print moreCust($sub_system);
      break;

   case "more_sm":
      include_once 'template/common.php';
      print moreSm($sub_system);
      break;

   case "code-list":
      include_once 'template/common.php';
      listItem($sub_system);
      include_once 'template/ajax_list.php';
      break;

   case "desc-list":
      include_once 'template/common.php';
      listItem($sub_system);
      include_once 'template/ajax_list.php';
      break;

   case "sn-list":
      include_once 'components/bill2/modle/bill2Module.php';
      listSN($sub_system);
      include_once 'template/ajax_list.php';
      break;

   case "get_unic_cashback_data":
      include_once 'components/bill2/modle/bill2Module.php';
      print getUnicCashbackData($sub_system);
      break;

   case "more_item":
      include_once 'components/bill2/modle/bill2Module.php';
      print moreItem($sub_system, $systemid);
      break;

   case "get_sn_list":
      include_once 'components/bill2/modle/bill2Module.php';
      print getSNList($sub_system, $systemid);
      break;

   case "add_to_bill":
      include_once 'components/bill2/modle/bill2Module.php';
      print addToBill($sub_system);
      break;

   case "get_tmp_bill_items":
      include_once 'components/bill2/modle/bill2Module.php';
      print getTMPBillItems($sub_system);
      break;

   case "remove_tmp_bill_item":
      include_once 'components/bill2/modle/bill2Module.php';
      print removeTMPBillItem($sub_system);
      break;

   case "update_tmp_bill_item":
      include_once 'components/bill2/modle/bill2Module.php';
      print updateTMPBillItem($sub_system);
      break;

   case "update_invoice_discount_ajax":
      include_once 'components/bill2/modle/bill2Module.php';
      print updateInvoiceDiscount($sub_system);
      break;

   case "update_due_date_ajax":
      include_once 'components/bill2/modle/bill2Module.php';
      print updateDueDate($sub_system);
      break;

   case "validate_item":
      include_once 'components/bill2/modle/bill2Module.php';
      print validateBillItem($sub_system);
      break;

   case "validate_tmp_bill":
      include_once 'components/bill2/modle/bill2Module.php';
      print validateTMPBill($_GET['case']);
      break;

   case "new_tmp_bill":
      include_once 'components/bill2/modle/bill2Module.php';
      print newTMPBill($sub_system, $_POST['cust_odr'], $_POST['sm_id'], '0');
      break;

   case "new_tmp_bill2":
      include_once 'components/bill2/modle/bill2Module.php';
      $obj = json_decode(newTMPBill($sub_system, $_GET['cust_odr'], $_GET['sm_id'], '0'));
      $bm_no = $obj->bm_no;
      $msg = $obj->msg;
      $qb_msg = $obj->qb_msg;
      if ($msg == 'Done') {
         header('Location: index.php?components=bill2&action=bill_item&cust_odr=' . $_GET['cust_odr'] . '&bill_no=' . $bm_no . '&message=Customer and Bill created successfully!' . $qb_msg . '&re=success');
      } else {
         header('Location: index.php?components=bill2&action=home&cust_odr=' . $_GET['cust_odr'] . '&s=' . $_GET['sm_id'] . '&message=' . $msg . '&re=fail');
      }
      break;

   //----------For create via quotations-----------------//
   case "new_tmp_bill3":
      include_once 'components/bill2/modle/bill2Module.php';
      $obj = json_decode(newTMPBill($sub_system, $_GET['cust_odr'], $_GET['sm_id'], $_GET['quotation']));
      $bm_no = $obj->bm_no;
      $msg = $obj->msg;
      if ($msg == 'Done') {
         header('Location: index.php?components=bill2&action=bill_item&cust_odr=' . $_GET['cust_odr'] . '&bill_no=' . $bm_no . '&message=Customer and Bill Created Successfully&re=success');
      } else {
         header('Location: index.php?components=bill2&action=home&cust_odr=' . $_GET['cust_odr'] . '&s=' . $_GET['sm_id'] . '&message=' . $msg . '&re=fail');
      }
      break;

   case "create_invoice":
      include_once 'components/bill2/modle/bill2Module.php';
      print createInvoice($sub_system);
      break;

   case "bill_item":
      include_once 'components/bill2/modle/bill2Module.php';
      getDistrict();
      getBillMain($_GET['bill_no']);
      getCreditStatus($sub_system, $cu_id);
      getReturnChque('all');
      getTechnicient();
      if ($systemid == 1 || $systemid == 4 || $systemid == 10 || $systemid == 13 || $systemid == 15 || $systemid == 20 || $systemid == 24)
         getSalesman3($sub_system);
      if ($systemid == 10 | $systemid == 15 || $systemid == 20)
         getRecoveryAgent($sub_system);
      if (isMobile())
         include_once 'components/bill2/view/m_billing2.php';
      else
         include_once 'components/bill2/view/billing2.php';
      break;

   case "change_salesman":
      include_once 'components/bill2/modle/bill2Module.php';
      print changeSalesman();
      break;

   case "change_recovery_agent":
      include_once 'components/bill2/modle/bill2Module.php';
      print changeRecoveryAgent();
      break;

   case "home2":
      include_once 'components/bill2/modle/bill2Module.php';
      $remaining_cr_limit = 0;
      getDistrict();
      getInvoiceItems();

      if (isset($_COOKIE['district'])) {
         getItems($item_filter, $sub_system, $systemid);
         if (isset($_GET['cust'])) {
            if (validateBillNo())
               header('Location: index.php?components=bill2&action=new_bill&cust_odr=' . $_REQUEST['cust_odr'] . '&salesman=' . $_GET['s'] . '&cust_id=' . $_GET['cust'] . '&quotation=0');
            $remaining_cr_limit = getCreditStatus2($_GET['cust']);
            getCust('1,2');
            $cust_mtype = getMasterCust($_GET['cust']);
            getCreditStatus($sub_system, $_GET['cust']);
         } else {
            if ($_COOKIE['direct_mkt'] == 1) {
               getCust(2);
            } else
               if ($_COOKIE['retail'] == 0) {
                  if ($systemid == 13)
                     getCust('1,2');
                  else
                     getCust(1);
               } else
                  if ($_COOKIE['retail'] == 1)
                     getCust('1,2');
         }
      }
      if ($systemid == 1 || $systemid == 4 || $systemid == 10)
         getSalesman2();
      billTemplate();
      getReturnChque('all');
      getTechnicient();
      releaseCrossTrans($sub_system, $systemid);
      getRepView($sub_system, $systemid);
      if ($_COOKIE['fastprint'] == 'on')
         billTemplate();
      if (isMobile())
         include_once 'components/bill2/view/m_billing.php';
      else
         include_once 'components/bill2/view/home.php';
      break;

   case "tag_gps":
      include_once 'components/bill2/modle/bill2Module.php';
      print tag_gps();
      break;

   case "get_discount":
      include_once 'components/bill2/modle/bill2Module.php';
      print getDiscount($sub_system);
      break;

   case "get_authorize":
      include_once 'components/bill2/modle/bill2Module.php';
      print getAuthorize();
      break;

   case "temp_auth_code_validate":
      include_once 'components/bill2/modle/bill2Module.php';
      print tempAuthCodeValidate();
      break;

   case "auth_code_validate":
      include_once 'components/bill2/modle/bill2Module.php';
      print authCodeValidate();
      break;

   case "wholesale_cust":
      include_once 'components/manager/modle/managerModule.php';
      getCustGroups($sub_system);
      getTown();
      getCust2('1');
      getOneCust('name', 'all');
      if (isMobile())
         include_once 'components/bill2/view/m_cust.php';
      else
         include_once 'components/bill2/view/cust.php';
      break;

   case "onetime_cust":
      include_once 'components/manager/modle/managerModule.php';
      getTown();
      getCust2('1,2');
      getOneCust('name', 'all');
      if (isMobile())
         include_once 'components/bill2/view/m_cust.php';
      else
         include_once 'components/bill2/view/cust.php';
      break;

   case "cust_details":
      include_once 'components/manager/modle/managerModule.php';
      getSalesman($sub_system);
      getStore($sub_system);
      getOneCust('id', $sub_system);
      if (isMobile())
         include_once 'components/bill2/view/m_cust.php';
      else
         include_once 'components/bill2/view/cust.php';
      break;

   case "sales_report2":
      include_once 'components/manager/modle/managerModule.php';
      getCust($sub_system, '1');
      getStore($sub_system);
      getSalesReport2($sub_system);
      getCategory();
      if (isMobile())
         include_once 'components/manager/view/m_sales_report2.php';
      else
         include_once 'components/manager/view/sales_report2.php';
      break;

   case "sales_report3":
      include_once 'components/manager/modle/managerModule.php';
      getSalesReport3($sub_system);
      getCategory();
      if (isMobile())
         include_once 'components/manager/view/m_sales_report3.php';
      else
         include_once 'components/manager/view/sales_report3.php';
      break;

   case "unvisited":
      include_once 'components/manager/modle/managerModule.php';
      getSalesman($sub_system);
      getUnvisited($sub_system);
      if (isMobile())
         include_once 'components/manager/view/m_unvisited.php';
      else
         include_once 'components/manager/view/unvisited.php';
      break;

   // added by E.S.P Nirmal 2021_06_04
   case "cust-list-one_time":
      include_once 'template/common.php';
      listCust($sub_system);
      include_once 'template/ajax_list.php';
      break;

   // added by E.S.P Nirmal 2021_06_04
   case "cust-list-wholesale":
      include_once 'template/common.php';
      listCust($sub_system);
      include_once 'template/ajax_list.php';
      break;

   // added by E.S.P Nirmal 2021_06_04
   case "cust-list2-get-one-cust-ajax":
      include_once 'components/manager/modle/managerModule.php';
      print cust2Ajax('name', $sub_system);
      break;

   // added by E.S.P Nirmal 2021_06_25
   case "nic-check":
      include_once 'components/manager/modle/managerModule.php';
      print nicCheckAjax($sub_system);
      break;

   // added by E.S.P Nirmal 2021_06_25
   case "mobile-check":
      include_once 'components/manager/modle/managerModule.php';
      print mobileCheckAjax($sub_system);
      break;

   // added by E.S.P Nirmal 2021_06_04
   case "add_cust":
      include_once 'components/manager/modle/managerModule.php';
      print addCust($systemid);
      break;

   // added by E.S.P Nirmal 2021_06_04
   case "add_cust_image1":
      include_once 'components/manager/modle/managerModule.php';
      if (addCustImage($systemid))
         header('Location: index.php?components=bill2&action=home&s=' . $_GET['s'] . '&cust_odr=' . $_GET['cust_odr'] . '&message=' . $message . ' | Pending Approval&re=success');
      else
         header('Location: index.php?components=bill2&action=wholesale_cust&sm_id=' . $_GET['s'] . '&cust_odr=' . $_GET['cust_odr'] . '&message=' . $message . '&re=fail');
      break;

   // added by E.S.P Nirmal 2021_06_04
   case "add_cust_image2":
      include_once 'components/manager/modle/managerModule.php';
      if (addCustImage($systemid))
         header('Location: index.php?components=bill2&action=new_tmp_bill2&cust_odr=' . $_GET['cust_odr'] . '&cust_id=' . $cust_id . '&sm_id=' . $_GET['sm_id'] . '&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=bill2&action=onetime_cust&s=' . $_GET['sm_id'] . '&cust_odr=' . $_GET['cust_odr'] . '&message=' . $message . '&re=fail');
      break;

   case "add_cust1":
      include_once 'components/manager/modle/managerModule.php';
      if (addCust($systemid))
         header('Location: index.php?components=bill2&action=home&s=' . $_GET['sm_id'] . '&cust_odr=' . $_GET['cust_odr'] . '&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=bill2&action=wholesale_cust&s=' . $_GET['sm_id'] . '&cust_odr=' . $_GET['cust_odr'] . '&message=' . $message . '&re=fail');
      break;

   case "add_cust2":
      include_once 'components/manager/modle/managerModule.php';
      if (addCust($systemid))
         header('Location: index.php?components=bill2&action=new_tmp_bill2&cust_odr=' . $_GET['cust_odr'] . '&cust_id=' . $cust_id . '&sm_id=' . $_GET['sm_id'] . '&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=bill2&action=onetime_cust&s=' . $_GET['sm_id'] . '&cust_odr=' . $_GET['cust_odr'] . '&message=' . $message . '&re=fail');
      break;

   case "get_qo_price":
      include_once 'components/bill2/modle/bill2Module.php';
      print getQOPrice();
      break;

   case "finish_bill":
      include_once 'components/bill2/modle/bill2Module.php';
      include_once 'components/repair/modle/repairModule.php';
      billPermission();
      billDetails();
      billTemplate();
      getBank();
      getSalesman2();
      getRepairComments();
      if ($hire_purchase)
         hpInstalmentFormData();
      if ($_COOKIE['fastprint'] == 'on')
         generateInvoiceFast();
      if (isMobile())
         include_once 'components/bill2/view/m_bill_print.php';
      else
         include_once 'components/bill2/view/finish.php';
      break;

   case "finish_dn":
      include_once 'components/bill2/modle/bill2Module.php';
      billPermission();
      billDetails();
      billTemplate();
      getBank();
      if ($_COOKIE['fastprint'] == 'on')
         generateInvoiceFast();
      if (isMobile())
         include_once 'components/bill2/view/m_bill_print.php';
      else
         include_once 'components/bill2/view/finish.php';
      break;

   case "pos_print_bill":
      include_once 'components/bill2/modle/bill2Module.php';
      generateInvoice();
      include_once 'components/bill2/view/pos_print_bill.php';
      break;

   case "today":
      include_once 'components/bill2/modle/bill2Module.php';
      today($systemid, $sub_system);
      if (isMobile())
         include_once 'components/bill2/view/m_today.php';
      else
         include_once 'components/bill2/view/today.php';
      break;

   case "delete_tmp_bill":
      include_once 'components/bill2/modle/bill2Module.php';
      if (deleteTmpBill())
         header('Location: index.php?components=bill2&action=today&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=bill2&action=bill_item&cust_odr=' . $_GET['cust_odr'] . '&bill_no=' . $_GET['bm_no'] . '&message=' . $message . '&re=fail');
      break;

   case "delete":
      include_once 'components/bill2/modle/bill2Module.php';
      if (deleteInvoice(1, 0))
         header('Location: index.php?components=bill2&action=today&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=bill2&action=today&message=' . $message . '&re=fail');
      break;

   case "pay_bill":
      include_once 'components/bill2/modle/bill2Module.php';
      getBank();
      getChequeNames();
      getBillMain($_GET['bill_no']);
      if ($bm_type == 3)
         $remaining_cr_limit = getCreditStatus2($cu_id);
      else
         $remaining_cr_limit = getCreditStatus2($cu_id) - $bill_total;
      setStatusCrossTrans($_GET['bill_no'], '5');
      if ($bm_hire_purchase) {
         hpFormData();
      }
      if (isMobile())
         include_once 'components/bill2/view/m_bill_payment.php';
      else
         include_once 'components/bill2/view/bill_payment.php';
      break;

   case "hp_paid_instalment":
      include_once 'components/bill2/modle/bill2Module.php';
      hpGetPaidInstalment();
      if (isMobile())
         include_once 'components/bill2/view/m_hp_paid_instalment.php';
      else
         include_once 'components/bill2/view/hp_paid_instalment.php';
      break;

   case "add_hire_purchase":
      include_once 'components/bill2/modle/bill2Module.php';
      print addHirePurchase($sub_system);
      break;

   case "hp_get_pending_amount":
      include_once 'components/bill2/modle/bill2Module.php';
      print hpGetPendingAmount();
      break;

   case "add_hp_payment":
      include_once 'components/bill2/modle/bill2Module.php';
      if (addHPpayment())
         header('Location: index.php?components=bill2&action=finish_bill&id=' . $invoice_no . '&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=bill2&action=finish_bill&id=' . $invoice_no . '&message=' . $message . '&re=fail');
      break;

   case "add_bill_payment":
      include_once 'components/bill2/modle/bill2Module.php';
      print addBillPayment($sub_system);
      break;

   case "search_bill":
      include_once 'components/bill2/modle/bill2Module.php';
      if (searchBill($_POST['search1']))
         header('Location: index.php?components=bill2&action=finish_bill&id=' . $_POST['search1']);
      else
         header('Location: index.php?components=bill2&action=home&s=' . $_GET['s'] . '&cust_odr=' . $_GET['cust_odr'] . '&message=Invalid Invoice Number&re=fail');
      break;

   case "search_pay":
      include_once 'components/bill2/modle/bill2Module.php';
      if (searchPay($_POST['search1']))
         header('Location: index.php?components=bill2&action=finish_payment&id=' . $_POST['search1']);
      else
         header('Location: index.php?components=bill2&action=payment_home&message=Invalid Invoice Number&re=fail');
      break;

   case "show_invoice_pay":
      include_once 'components/bill2/modle/bill2Module.php';
      getInvoicePay();
      if (isMobile())
         include_once 'components/bill2/view/m_invoice_pay.php';
      else
         include_once 'components/bill2/view/invoice_pay.php';
      break;

   case "sms":
      include_once 'components/bill2/modle/bill2Module.php';
      printST();
      sms();
      break;

   case "sms_resend":
      print smsResend($_GET['smsid']);
      break;

   case "setfastprint":
      include_once 'components/bill2/modle/bill2Module.php';
      if (setFastPrint())
         header('Location: index.php?components=bill2&action=home&id=' . $invoice_no . '&s=' . $salesman . '&cust=' . $cust . '&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=bill2&action=home&id=' . $invoice_no . '&s=' . $salesman . '&cust=' . $cust . '&message=' . $message . '&re=fail');
      break;

   case "set_delivered":
      include_once 'components/bill2/modle/bill2Module.php';
      if (setDelivered())
         header('Location: index.php?components=bill2&action=finish_bill&id=' . $invoice_no . '&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=bill2&action=finish_bill&id=' . $invoice_no . '&message=' . $message . '&re=fail');
      break;

   case "change_job_total":
      include_once 'components/bill2/modle/bill2Module.php';
      if (changeJobTotal())
         header('Location: index.php?components=bill2&action=finish_bill&id=' . $invoice_no . '&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=bill2&action=finish_bill&id=' . $invoice_no . '&message=' . $message . '&re=fail');
      break;

   case "get_store_stock":
      include_once 'components/bill2/modle/bill2Module.php';
      print storeCrossCheck($sub_system, $systemid, $_GET['itmid']);
      break;

   case "del_repair_comment":
      include_once 'components/repair/modle/repairModule.php';
      if (delRepairComment())
         header('Location: index.php?components=bill2&action=finish_bill&id=' . $bm_inv . '&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=bill2&action=finish_bill&id=' . $bm_inv . '&message=' . $message . '&re=fail');
      break;

   case "add_repair_comment":
      include_once 'components/repair/modle/repairModule.php';
      if (addRepairComment(3))
         header('Location: index.php?components=bill2&action=finish_bill&id=' . $bm_inv . '&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=bill2&action=finish_bill&id=' . $bm_inv . '&message=' . $message . '&re=fail');
      break;

   //-------------------------Payment----------------------------------//
   case "payment_home":
      include_once 'components/bill2/modle/bill2Module.php';
      if (isset($_GET['cust'])) {
         getCustPayments();
         getCreditStatus($sub_system, $_GET['cust']);
      }
      getBank();
      if ($_COOKIE['retail'] == 0)
         getCust(1);
      if ($_COOKIE['retail'] == 1)
         getCust('1,2');
      getSalesman2();
      searchPayments();
      if (isMobile())
         include_once 'components/bill2/view/m_payment_home.php';
      else
         include_once 'components/bill2/view/payment_home.php';
      break;

   case "payment_form":
      include_once 'components/bill2/modle/bill2Module.php';
      if (isset($_GET['cust'])) {
         getCustPayments();
         getCreditStatus($sub_system, $_GET['cust']);
      }
      getBank();
      getChequeNames();
      if ($_COOKIE['retail'] == 0)
         getCust(1);
      if ($_COOKIE['retail'] == 1)
         getCust('1,2');
      getSalesman2();
      searchPayments();
      if (isMobile())
         include_once 'components/bill2/view/m_payment_form.php';
      else
         include_once 'components/bill2/view/payment_form.php';
      break;

   case "add_payment":
      include_once 'components/bill2/modle/bill2Module.php';
      if (addPayment('pay')) {
         if ($bm_type == 3)
            header('Location: index.php?components=bill2&action=finish_bill&id=' . $invoice_no . '&message=' . $message . '&re=success');
         else
            header('Location: index.php?components=bill2&action=finish_payment&id=' . $payment_id . '&message=' . $message . '&re=success');
      } else
         header('Location: index.php?components=bill2&action=payment_form&cust=' . $cust . '&message=' . $message . '&re=fail');
      break;

   case "finish_payment":
      include_once 'components/bill2/modle/bill2Module.php';
      paymentPermission();
      billTemplate();
      payDetails();
      if (isMobile())
         include_once 'components/bill2/view/m_payment_print.php';
      else
         include_once 'components/bill2/view/payment_finish.php';
      break;

   case "delete_payment":
      include_once 'components/bill2/modle/bill2Module.php';
      if (deletePayment(1, 0))
         header('Location: index.php?components=bill2&action=payment_home&cust=' . $cust . '&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=bill2&action=payment_home&cust=' . $cust . '&message=' . $message . '&re=fail');
      break;

   case "cust_sale":
      include_once 'components/manager/modle/managerModule.php';
      getCust($sub_system, '1,2');
      getCustSale($systemid);
      if (isMobile())
         include_once 'components/manager/view/m_cust_sale.php';
      else
         include_once 'components/manager/view/cust_sale.php';
      break;

   case "check_payment_correlate":
      include_once 'components/manager/modle/managerModule.php';
      print checkPaymentCorrelate();
      break;

   case "validate_invoice":
      include_once 'components/bill2/modle/bill2Module.php';
      print validateInvoice();
      break;

   //--------------------------------------Chque Return----------------------------//
   case "chque_return":
      include_once 'components/bill2/modle/bill2Module.php';
      getReturnChque($_COOKIE['user_id']);
      if (isMobile())
         include_once 'components/bill2/view/m_chque_return.php';
      else
         include_once 'components/bill2/view/chque_return.php';
      break;

   case "rtnchque_clear":
      include_once 'components/bill2/modle/bill2Module.php';
      if (setChqRtnClear())
         header('Location: index.php?components=bill2&action=chque_return&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=bill2&action=chque_return&message=' . $message . '&re=fail');
      break;

   //--------------------------------------Item Return----------------------------//
   case "item_return":
      include_once 'components/bill2/modle/bill2Module.php';
      getItems(1, $sub_system, $systemid);
      if ($_COOKIE['retail'] == 0)
         getCust(1);
      if ($_COOKIE['retail'] == 1)
         getCust('1,2');
      getReturnItems();
      if (isMobile())
         include_once 'components/bill2/view/m_item_return.php';
      else
         include_once 'components/bill2/view/item_return.php';
      break;

   case "new_return":
      include_once 'components/bill2/modle/bill2Module.php';
      if (newReturn($_GET['cust'], $_GET['gps_x'], $_GET['gps_y']))
         header('Location: index.php?components=bill2&action=item_return&id=' . $invoice_no . '&cust=' . $cust . '&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=bill2&action=item_return&message=Invoice Could Not be Created&re=fail');
      break;

   case "apend_return":
      include_once 'components/bill2/modle/bill2Module.php';
      $debug_id = debugStart(0, 0);
      if (apendReturn()) {
         debugEnd($debug_id, 'success');
         header('Location: index.php?components=bill2&action=item_return&id=' . $return_invoice_no . '&cust=' . $cust . '&message=' . $message . '&re=success');
      } else {
         debugEnd($debug_id, 'fail');
         header('Location: index.php?components=bill2&action=item_return&id=' . $return_invoice_no . '&cust=' . $cust . '&message=' . $message . '&re=fail');
      }
      break;

   case "return_item_remove":
      include_once 'components/bill2/modle/bill2Module.php';
      $debug_id = debugStart(0, 0);
      if (removeReturnitem()) {
         debugEnd($debug_id, 'success');
         header('Location: index.php?components=bill2&action=item_return&id=' . $return_invoice_no . '&cust=' . $cust . '&message=' . $message . '&re=success');
      } else {
         debugEnd($debug_id, 'fail');
         header('Location: index.php?components=bill2&action=item_return&id=' . $return_invoice_no . '&cust=' . $cust . '&message=' . $message . '&re=fail');
      }
      break;

   case "finish_return":
      include_once 'components/bill2/modle/bill2Module.php';
      returnDetails();
      billTemplate();
      if (isMobile())
         include_once 'components/bill2/view/m_return_finish.php';
      else
         include_once 'components/bill2/view/return_finish.php';
      break;

   case "finalize_return":
      include_once 'components/bill2/modle/bill2Module.php';
      if (finalizeReturn())
         header('Location: index.php?components=bill2&action=finish_return&id=' . $_GET['id'] . '&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=bill2&action=item_return&id=' . $_GET['id'] . '&message=' . $message . '&re=fail');
      break;

   case "delete_return":
      include_once 'components/bill2/modle/bill2Module.php';
      if (deleteReturn())
         header('Location: index.php?components=bill2&action=item_return&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=bill2&action=item_return&message=' . $message . '&re=fail');
      break;

   case "get_replacementsn":
      include_once 'components/bill2/modle/bill2Module.php';
      print getReplacementsn();
      break;

   case "get_pricediff":
      include_once 'components/bill2/modle/bill2Module.php';
      print getPricediff();
      break;

   case "search_return":
      include_once 'components/bill2/modle/bill2Module.php';
      if (searchReturn($_POST['search1']))
         header('Location: index.php?components=bill2&action=finish_return&id=' . $_POST['search1']);
      else
         header('Location: index.php?components=bill2&action=item_return');
      break;

   case "credit":
      include_once 'components/supervisor/modle/supervisorModule.php';
      getCreditData($sub_system);
      getFilter($sub_system);
      if (isMobile())
         include_once 'components/supervisor/view/m_credit_view.php';
      else
         include_once 'components/supervisor/view/credit_view.php';
      break;

   case "warranty":
      include_once 'components/bill2/modle/bill2Module.php';
      getWarrantyOngoingList();
      include_once 'components/bill2/view/warranty.php';
      break;

   case "warranty_show":
      include_once 'components/bill2/modle/bill2Module.php';
      getWarrantyOngoingList();
      getWarrantyOne();
      include_once 'components/bill2/view/warranty.php';
      break;

   case "warranty_repair":
      include_once 'components/bill2/modle/bill2Module.php';
      getWarrantyOngoingList();
      include_once 'components/bill2/view/warranty.php';
      break;

   case "warranty_replace":
      include_once 'components/bill2/modle/bill2Module.php';
      getWarrantyOngoingList();
      getWarrantyReplace();
      getItems2();
      include_once 'components/bill2/view/warranty.php';
      break;

   case "warranty_inventory":
      include_once 'components/bill2/modle/bill2Module.php';
      getWarrantyOngoingList();
      getWarrantyInv();
      getItems(1, $sub_system, $systemid);
      include_once 'components/bill2/view/warranty.php';
      break;

   case "warranty_pay":
      include_once 'components/bill2/modle/bill2Module.php';
      getWarrantyOngoingList();
      getWarrantyPay();
      include_once 'components/bill2/view/warranty.php';
      break;

   case "warranty_cust_pay":
      include_once 'components/bill2/modle/bill2Module.php';
      getWarrantyOngoingList();
      getWarrantyCustPay();
      include_once 'components/bill2/view/warranty.php';
      break;

   case "warranty_print":
      include_once 'components/bill2/modle/bill2Module.php';
      getWarrantyPrintTemplate();
      include_once 'components/bill2/view/warranty.php';
      break;

   case "warranty_validate":
      include_once 'components/bill2/modle/bill2Module.php';
      print validateWarranty($_GET['sn']);
      break;

   case "warranty_search":
      include_once 'components/bill2/modle/bill2Module.php';
      if (warrantySearch())
         header('Location: index.php?components=bill2&action=warranty_show&id=' . $id);
      else {
         include_once 'components/bill2/view/warranty.php';
      }
      break;

   case "warranty_submit":
      include_once 'components/bill2/modle/bill2Module.php';
      if (warrantySubmit($sub_system))
         header('Location: index.php?components=bill2&action=warranty_show&id=' . $id . '&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=bill2&action=warranty&message=' . $message . '&re=fail');
      break;

   case "set_warranty_status":
      include_once 'components/bill2/modle/bill2Module.php';
      if (setWarrantyStatus())
         header('Location: index.php?components=bill2&action=warranty_show&id=' . $_GET['id'] . '&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=bill2&action=warranty&message=' . $message . '&re=fail');
      break;

   case "set_warranty_handover":
      include_once 'components/bill2/modle/bill2Module.php';
      if (setWarrantyHandover())
         header('Location: index.php?components=bill2&action=warranty_show&id=' . $_GET['id'] . '&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=bill2&action=warranty&message=' . $message . '&re=fail');
      break;

   case "set_warranty_repair":
      include_once 'components/bill2/modle/bill2Module.php';
      if (setWarrantyRepair())
         header('Location: index.php?components=bill2&action=warranty_show&id=' . $_GET['id'] . '&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=bill2&action=warranty&message=' . $message . '&re=fail');
      break;

   case "set_warranty_replace":
      include_once 'components/bill2/modle/bill2Module.php';
      if (setWarrantyReplace())
         header('Location: index.php?components=bill2&action=warranty_show&id=' . $id . '&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=bill2&action=warranty_show&id=' . $id . '&message=' . $message . '&re=fail');
      break;

   case "set_warranty_pay":
      include_once 'components/bill2/modle/bill2Module.php';
      if (setWarrantyPay())
         header('Location: index.php?components=bill2&action=warranty_show&id=' . $id . '&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=bill2&action=warranty_show&id=' . $id . '&message=' . $message . '&re=fail');
      break;

   case "set_warranty_cust_pay":
      include_once 'components/bill2/modle/bill2Module.php';
      if (setWarrantyCustPay())
         header('Location: index.php?components=bill2&action=warranty_show&id=' . $id . '&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=bill2&action=warranty_show&id=' . $id . '&message=' . $message . '&re=fail');
      break;

   case "delete_warranty":
      include_once 'components/bill2/modle/bill2Module.php';
      if (deleteWarranty())
         header('Location: index.php?components=bill2&action=warranty&id=' . $id . '&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=bill2&action=warranty_show&id=' . $id . '&message=' . $message . '&re=fail');
      break;

   case "add_warranty_inv":
      include_once 'components/bill2/modle/bill2Module.php';
      $debug_id = debugStart(0, 0);
      if (addWarrantyInv()) {
         debugEnd($debug_id, 'success');
         header('Location: index.php?components=bill2&action=warranty_show&id=' . $id . '&message=' . $message . '&re=success');
      } else {
         debugEnd($debug_id, 'fail');
         header('Location: index.php?components=bill2&action=warranty_show&id=' . $id . '&message=' . $message . '&re=fail');
      }
      break;

   case "return_warranty_inv":
      include_once 'components/bill2/modle/bill2Module.php';
      $debug_id = debugStart(0, 0);
      if (returnWarrantyInv($_GET['st'])) {
         debugEnd($debug_id, 'success');
         header('Location: index.php?components=bill2&action=warranty_show&id=' . $id . '&message=' . $message . '&re=success');
      } else {
         debugEnd($debug_id, 'fail');
         header('Location: index.php?components=bill2&action=warranty_show&id=' . $id . '&message=' . $message . '&re=fail');
      }
      break;

   case "return_warranty_inv2":
      include_once 'components/bill2/modle/bill2Module.php';
      if (returnWarrantyInv2($_GET['st'])) {
         header('Location: index.php?components=bill2&action=warranty_show&id=' . $id . '&message=' . $message . '&re=success');
      } else {
         header('Location: index.php?components=bill2&action=warranty_show&id=' . $id . '&message=' . $message . '&re=fail');
      }
      break;

   //-------------------Quotation-----------------------------------//
   case "quotation":
      include_once 'components/bill2/modle/bill2Module.php';
      include_once 'components/supervisor/modle/supervisorModule.php';
      getDistrict();
      getQuotationItems();
      if (isset($_COOKIE['district'])) {
         getItems($item_filter, $sub_system, $systemid);
         getCust(1);
         if (isset($_GET['cust'])) {
            if (validateQuotNo())
               header('Location: index.php?components=bill2&action=new_quot&cust_id=' . $_GET['cust'] . '&att=');
         }
      }
      if (isMobile())
         include_once 'components/supervisor/view/m_quotation.php';
      else
         include_once 'components/supervisor/view/quotation.php';
      break;

   case "new_quot":
      include_once 'components/supervisor/modle/supervisorModule.php';
      if (newQuot($_REQUEST['cust_id']))
         header('Location: index.php?components=bill2&action=quotation&id=' . $quot_no . '&s=' . $salesman . '&cust=' . $cust);
      else
         header('Location: index.php?components=bill2&action=quotation&message=' . $message . '&re=fail');
      break;

   case "apend_quot":
      include_once 'components/bill2/modle/bill2Module.php';
      include_once 'components/supervisor/modle/supervisorModule.php';
      if (apendQuot()) {
         header('Location: index.php?components=bill2&action=quotation&id=' . $quot_no . '&s=' . $salesman . '&cust=' . $cust . '&message=' . $message . '&re=success');
      } else {
         header('Location: index.php?components=bill2&action=quotation&id=' . $quot_no . '&s=' . $salesman . '&cust=' . $cust . '&message=' . $message . '&re=fail');
      }
      break;

   case "qo_item_gpdate":
      include_once 'components/supervisor/modle/supervisorModule.php';
      if (updateQuot()) {
         header('Location: index.php?components=bill2&action=quotation&id=' . $quot_no . '&s=' . $salesman . '&cust=' . $cust . '&message=' . $message . '&re=success');
      } else {
         header('Location: index.php?components=bill2&action=quotation&id=' . $quot_no . '&s=' . $salesman . '&cust=' . $cust . '&message=' . $message . '&re=fail');
      }
      break;

   case "qo_item_remove":
      include_once 'components/supervisor/modle/supervisorModule.php';
      if (removeQuot()) {
         header('Location: index.php?components=bill2&action=quotation&id=' . $quot_no . '&s=' . $salesman . '&cust=' . $cust . '&message=' . $message . '&re=success');
      } else {
         header('Location: index.php?components=bill2&action=quotation&id=' . $quot_no . '&s=' . $salesman . '&cust=' . $cust . '&message=' . $message . '&re=fail');
      }
      break;

   // added by nirmal 25_10_2023
   case "qo_item_update_unit_price":
      include_once 'components/supervisor/modle/supervisorModule.php';
      if (updateQuotItemUnitPrice()) {
         header('Location: index.php?components=bill2&action=quotation&id=' . $quot_no . '&s=' . $salesman . '&cust=' . $cust . '&message=' . $message . '&re=success');
      } else {
         header('Location: index.php?components=bill2&action=quotation&id=' . $quot_no . '&s=' . $salesman . '&cust=' . $cust . '&message=' . $message . '&re=fail');
      }
      break;

   // added by nirmal 25_10_2023
   case "qo_update_discount":
      include_once 'components/supervisor/modle/supervisorModule.php';
      if (updateQuotDiscount()) {
         header('Location: index.php?components=bill2&action=quotation&id=' . $quot_no . '&s=' . $salesman . '&cust=' . $cust . '&message=' . $message . '&re=success');
      } else {
         header('Location: index.php?components=bill2&action=quotation&id=' . $quot_no . '&s=' . $salesman . '&cust=' . $cust . '&message=' . $message . '&re=fail');
      }
      break;

   // added by nirmal 25_10_2023
   case "qo_remove_discount":
      include_once 'components/supervisor/modle/supervisorModule.php';
      if (removeQuotDiscount()) {
         header('Location: index.php?components=bill2&action=quotation&id=' . $quot_no . '&s=' . $salesman . '&cust=' . $cust . '&message=' . $message . '&re=success');
      } else {
         header('Location: index.php?components=bill2&action=quotation&id=' . $quot_no . '&s=' . $salesman . '&cust=' . $cust . '&message=' . $message . '&re=fail');
      }
      break;

   // added by nirmal 29_07_2024
   case "qo_update_comment":
      include_once 'components/supervisor/modle/supervisorModule.php';
      print updateQuotComment();
      break;

   case "qo_terms":
      include_once 'components/supervisor/modle/supervisorModule.php';
      getQOTerms();
      getDetaultTerms();
      if (isMobile())
         include_once 'components/supervisor/view/m_quotation_terms.php';
      else
         include_once 'components/supervisor/view/quotation_terms.php';
      break;

   // added by nirmal 17_02_2025
   case "qo_insert_customer_address_in_quotation_main":
      include_once 'components/supervisor/modle/supervisorModule.php';
      print setQuotCustomerAddress();
      break;

   case "set_qo_terms":
      include_once 'components/supervisor/modle/supervisorModule.php';
      if (setQuotTerms())
         header('Location: index.php?components=bill2&action=set_quot_status&id=' . $quot_no . '&new_status=2');
      else
         header('Location: index.php?components=bill2&action=qo_terms&id=' . $quot_no . '&message=' . $message . '&re=fail');
      break;

   case "set_quot_status":
      include_once 'components/supervisor/modle/supervisorModule.php';
      if (setQuotStatus($_GET['new_status']))
         header('Location: index.php?components=bill2&action=qo_finish&id=' . $quot_no);
      else
         header('Location: index.php?components=bill2&action=qo_finish&id=' . $quot_no . '&message=' . $message . '&re=fail');
      break;

   case "qo_revise":
      include_once 'components/supervisor/modle/supervisorModule.php';
      if (qoRevise())
         header('Location: index.php?components=bill2&action=qo_finish&id=' . $quot_no);
      else
         header('Location: index.php?components=bill2&action=qo_finish&id=' . $quot_no . '&message=' . $message . '&re=fail');
      break;

   case "set_submit":
      include_once 'components/supervisor/modle/supervisorModule.php';
      setQuotStatus(5);
      break;

   case "qo_finish":
      include_once 'components/supervisor/modle/supervisorModule.php';
      qoPermission();
      qoDetails();
      qoNote();
      qoTemplate();
      if (isMobile())
         include_once 'components/supervisor/view/m_qo_print.php';
      else
         include_once 'components/supervisor/view/qo_print.php';
      break;

   case "qo_one":
      include_once 'components/supervisor/modle/supervisorModule.php';
      qoPermission();
      qoDetails();
      qoNote();
      qoTemplate();
      generateQuot(2);
      include_once 'components/supervisor/view/qo_one.php';
      break;

   case "qo_com_inv":
      include_once 'components/supervisor/modle/supervisorModule.php';
      qoPermission();
      qoDetails();
      qoNote();
      qoTemplate();
      include_once 'components/supervisor/view/qo_print.php';
      break;

   case "quotation_list":
      include_once 'components/supervisor/modle/supervisorModule.php';
      getQuotList($sub_system);
      getFilter($sub_system);
      getCustSup($sub_system);
      if (isMobile())
         include_once 'components/supervisor/view/m_quotation_list.php';
      else
         include_once 'components/supervisor/view/quotation_list.php';
      break;

   case "qo_complete_check":
      include_once 'components/supervisor/modle/supervisorModule.php';
      print qoCompleteCheck();
      break;

   case "quotation_ongoing":
      include_once 'components/supervisor/modle/supervisorModule.php';
      getOnGoing($sub_system);
      if (isMobile())
         include_once 'components/supervisor/view/m_quotation_ongoing.php';
      else
         include_once 'components/supervisor/view/quotation_ongoing.php';
      break;

   case "search_quot":
      include_once 'components/supervisor/modle/supervisorModule.php';
      if (searchQuot($_POST['search1']))
         header('Location: index.php?components=bill2&action=qo_finish&id=' . $_POST['search1']);
      else
         header('Location: index.php?components=bill2&action=quotation&message=Invalid%20Quotation%20Number&re=fail');
      break;

   case "qo_add_image":
      include_once 'components/supervisor/modle/supervisorModule.php';
      if (qoAddImage())
         header('Location: index.php?components=bill2&action=qo_finish&id=' . $_GET['id'] . '&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=bill2&action=qo_finish&id=' . $_GET['id'] . '&message=' . $message . '&re=fail');
      break;

   case "qo_delete_image":
      include_once 'components/supervisor/modle/supervisorModule.php';
      if (qoDeleteImage())
         header('Location: index.php?components=bill2&action=qo_finish&id=' . $_GET['id'] . '&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=bill2&action=qo_finish&id=' . $_GET['id'] . '&message=' . $message . '&re=fail');
      break;

   case "qo_img_height":
      include_once 'components/supervisor/modle/supervisorModule.php';
      if (qoImgHeight())
         header('Location: index.php?components=bill2&action=qo_finish&id=' . $_GET['id'] . '&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=bill2&action=qo_finish&id=' . $_GET['id'] . '&message=' . $message . '&re=fail');
      break;

   case "qo_add_note":
      include_once 'components/supervisor/modle/supervisorModule.php';
      if (qoAddNote())
         header('Location: index.php?components=bill2&action=qo_finish&id=' . $quot_no);
      else
         header('Location: index.php?components=bill2&action=qo_finish&id=' . $quot_no . '&message=' . $message . '&re=fail');
      break;

   case "qo_update_note":
      include_once 'components/supervisor/modle/supervisorModule.php';
      if (qoUpdateNote())
         header('Location: index.php?components=bill2&action=qo_finish&id=' . $quot_no . '&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=bill2&action=qo_finish&id=' . $quot_no . '&message=' . $message . '&re=fail');
      break;

   case "quotation_report":
      include_once 'components/supervisor/modle/supervisorModule.php';
      getFilter($sub_system);
      getCustSup($sub_system);
      getReportNote($sub_system);
      include_once 'components/supervisor/view/quotation_report.php';
      break;

   case "quotation_sent_with_tax":
      include_once 'components/supervisor/modle/supervisorModule.php';
      if (qoUpdateSentWithTax())
         header('Location: index.php?components=supervisor&action=qo_finish&id=' . $quot_no . '&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=supervisor&action=qo_finish&id=' . $quot_no . '&message=' . $message . '&re=fail');
      break;

   //----------------------Cheque OPS---------------------------------------------------------------//
   case "chque_ops":
      include_once 'components/bill2/modle/bill2Module.php';
      checkOPS();
      if (isMobile())
         include_once 'components/bill2/view/m_cheque_ops.php';
      else
         include_once 'components/bill2/view/cheque_ops.php';
      break;

   //----------------------Drawer---------------------------------------------------------------//
   case "drawer_search":
      include_once 'components/inventory/modle/inventoryModule.php';
      getStores($sub_system);
      drawerSearch();
      if (isMobile())
         include_once 'components/inventory/view/m_drawerSearch.php';
      else
         include_once 'components/inventory/view/drawerSearch.php';
      break;

   //----------------------Reports---------------------------------------------------------------//
   case "cust_bill":
      include_once 'components/bill2/modle/bill2Module.php';
      searchCust();
      include_once 'components/bill2/view/report_cust_bill.php';
      break;

   case "sale":
      if ($systemid != 1 && $systemid != 4) {
         include_once 'components/supervisor/modle/supervisorModule.php';
         dailySale($_GET['store']);
         $storedisable = '';
         $userdisable = 'disabled';
         getFilter($sub_system);
         if (isMobile())
            include_once 'components/supervisor/view/m_sale_view.php';
         else
            include_once 'components/supervisor/view/sale_view.php';
      }
      break;

   case "sold_qty":
      include_once 'components/manager/modle/managerModule.php';
      getStore($sub_system);
      getSoldQty($sub_system, $_REQUEST['components']);
      if (isMobile())
         include_once 'components/manager/view/m_sold_qty.php';
      else
         include_once 'components/manager/view/sold_qty.php';
      break;

   // added by nirmal 11_02_2022
   case "salesman_commission_new":
      include_once 'components/report/modle/reportModule.php';
      getSMCommission();
      if (isMobile())
         include_once 'components/report/view/m_salesman_commission_new.php';
      else
         include_once 'components/report/view/salesman_commission_new.php';
      break;

   // added by nirmal 11_02_2022
   case "salesman_commission_old":
      include_once 'components/report/modle/reportModule.php';
      getSMCommissionList();
      if (isMobile())
         include_once 'components/report/view/m_salesman_commission_list.php';
      else
         include_once 'components/report/view/salesman_commission_list.php';
      break;

   // added by nirmal 11_02_2022
   case "salesman_commission_one":
      include_once 'components/report/modle/reportModule.php';
      getSMCommissionOne();
      if (isMobile())
         include_once 'components/report/view/m_salesman_commission_one.php';
      else
         include_once 'components/report/view/salesman_commission_one.php';
      break;

   // added by nirmal 05_04_2022
   case "salesman_commission_incomplete_one":
      include_once 'components/report/modle/reportModule.php';
      getSMCommissionIncomplete();
      if (isMobile())
         include_once 'components/report/view/m_salesman_commission_incomplete_one.php';
      else
         include_once 'components/report/view/salesman_commission_incomplete_one.php';
      break;

   // added by nirmal 03_03_2022
   case "mk_home":
      include_once 'components/marketing/modle/marketingModule.php';
      getSubSystem();
      getStore($sub_system);
      getGroup($sub_system);
      setActiveSalesman($sub_system);
      getCustomerList();
      getActiveTown();
      if (isMobile())
         include_once 'components/marketing/view/m_home.php';
      else
         include_once 'components/marketing/view/home.php';
      break;

   //----------------------Payment Deposit---------------------------------------------------------------//
   case "cash_payment_deposit":
      include_once 'components/bill2/modle/bill2Module.php';
      getBank();
      getSalesman4($sub_system);
      getPaymentTotalToBeSettle('cash');
      if (isMobile())
         include_once 'components/bill2/view/m_cash_payment_deposit.php';
      else
         include_once 'components/bill2/view/cash_payment_deposit.php';
      break;

   case "bank_payment_deposit":
      include_once 'components/bill2/modle/bill2Module.php';
      getBank();
      getSalesman4($sub_system);
      getBankPaymentsCollectedBySalesman($sub_system);
      if (isMobile())
         include_once 'components/bill2/view/m_bank_payment_deposit.php';
      else
         include_once 'components/bill2/view/bank_payment_deposit.php';
      break;

   case "add_bank_transfer_ajax":
      include_once 'components/bill2/modle/bill2Module.php';
      print addBankPaymentDepositAjax();
      break;

   case "add_cash_payment_deposit":
      include_once 'components/bill2/modle/bill2Module.php';
      if (addCashPaymentDeposit($sub_system, $systemid)) {
         header('Location: index.php?components=bill2&action=cash_payment_deposit&message=' . $message . '&re=success');
      } else {
         header('Location: index.php?components=bill2&action=cash_payment_deposit&message=' . $message . '&re=fail');
      }
      break;

   case "cash_sent_report":
      include_once 'components/bill2/modle/bill2Module.php';
      getCashDepositsReport();
      getSalesman4($sub_system);
      getBank();
      if (isMobile())
         include_once 'components/bill2/view/m_cash_payments_sent_report.php';
      else
         include_once 'components/bill2/view/cash_payments_sent_report.php';
      break;

   case "delete_payment_transfer_ajax":
      include_once 'components/bill2/modle/bill2Module.php';
      print deletePaymentTransferAjax($systemid);
      break;

   case "bank_payments_sent_report":
      include_once 'components/bill2/modle/bill2Module.php';
      getBank();
      getBankDepositsReport($sub_system);
      if (isMobile())
         include_once 'components/bill2/view/m_bank_payments_sent_report.php';
      else
         include_once 'components/bill2/view/bank_payments_sent_report.php';
      break;

   // NEW
   case "cheque_transfer":
      include_once 'components/bill2/modle/bill2Module.php';
      getBank();
      getSalesman4($sub_system);
      getChequePaymentsCollectedBySalesman($sub_system);
      if (isMobile())
         include_once 'components/bill2/view/m_cheque_transfer.php';
      else
         include_once 'components/bill2/view/cheque_transfer.php';
      break;

   case "add_cheque_transfer_ajax":
      include_once 'components/bill2/modle/bill2Module.php';
      print addChequeTransferAjax($sub_system);
      break;

   case "cheque_transfer_summery":
      include_once 'components/bill2/modle/bill2Module.php';
      getChequeTransfersSummery($sub_system);
      if (isMobile())
         include_once 'components/bill2/view/m_cheque_transfers_summery.php';
      else
         include_once 'components/bill2/view/cheque_transfers_summery.php';
      break;

   case "delete_cheque_transfer_ajax":
      include_once 'components/bill2/modle/bill2Module.php';
      print deleteChequeTransferAjax($sub_system);
      break;

   case "cheque_transfer_status_summery":
      include_once 'components/bill2/modle/bill2Module.php';
      getChequeTransfersStatusSummery($sub_system);
      if (isMobile())
         include_once 'components/bill2/view/m_cheque_transfer_status_summery.php';
      else
         include_once 'components/bill2/view/cheque_transfer_status_summery.php';
      break;

   case "cheque_transfer_returns":
      include_once 'components/bill2/modle/bill2Module.php';
      getReturnCheques($sub_system);
      if (isMobile())
         include_once 'components/bill2/view/m_cheque_returns.php';
      else
         include_once 'components/bill2/view/cheque_returns.php';
      break;

   case "add_return_cheque_transfer_ajax":
      include_once 'components/bill2/modle/bill2Module.php';
      print addReturnChequeTransferAjax($sub_system);
      break;

   default:
      print '<p><strong>Bad Request</strong></p>';
      break;
}
