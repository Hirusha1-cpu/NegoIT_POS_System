<?php
if (passwordExpire()) header('Location: index.php?components=authenticate&action=change_pw&message=Your%20Password%20Has%20Expired.%20Please%20Change%20it%20NOW&re=fail');

switch ($_REQUEST['action']) {
   case "set_district":
      include_once  'components/billing/modle/billingModule.php';
      setDistrict();
      header('Location: index.php?components=billing&action=home&s=' . $_GET['s'] . '&cust_odr=' . $_GET['cust_odr']);
      break;

   case "home":
      include_once  'components/billing/modle/billingModule.php';
      $remaining_cr_limit = 0;
      getDistrict();
      getInvoiceItems();
      getMasterCust();
      if (isset($_COOKIE['district'])) {
         getItems($item_filter, $sub_system, $systemid);
         if (isset($_GET['cust'])) {
            if (validateBillNo()) header('Location: index.php?components=billing&action=new_bill&cust_odr=' . $_REQUEST['cust_odr'] . '&salesman=' . $_GET['s'] . '&cust_id=' . $_GET['cust'] . '&quotation=0');
            $remaining_cr_limit = getCreditStatus2($_REQUEST['id']);
            getCust('1,2');
         } else {
            if ($_COOKIE['direct_mkt'] == 1) {
               getCust(2);
            } else
	               		if ($_COOKIE['retail'] == 0) {
               if ($systemid == 13) getCust('1,2');
               else getCust(1);
            } else
	               		if ($_COOKIE['retail'] == 1) getCust('1,2');
         }
      }
      if ($systemid == 1 || $systemid == 4 || $systemid == 10 || $systemid == 15 || $systemid == 17) getSalesman2();
      billTemplate();
      getCreditStatus($sub_system);
      getReturnChque('all');
      getTechnicient();
      releaseCrossTrans($sub_system, $systemid);
      getRepView($sub_system, $systemid);
      if ($_COOKIE['fastprint'] == 'on') billTemplate();
      if (isMobile())
         include_once  'components/billing/view/m_billing.php';
      else
         include_once  'components/billing/view/home.php';
      break;

   case "tag_gps":
      include_once  'components/billing/modle/billingModule.php';
      print tag_gps();
      break;

   case "get_discount":
      include_once  'components/billing/modle/billingModule.php';
      print getDiscount($sub_system);
      break;

   case "get_authorize":
      include_once  'components/billing/modle/billingModule.php';
      print getAuthorize();
      break;

   case "wholesale_cust":
      include_once  'components/manager/modle/managerModule.php';
      getCustGroups($sub_system);
      getTown();
      getCust2('1');
      getOneCust('name', 'all');
      if (isMobile())
         include_once  'components/billing/view/m_cust.php';
      else
         include_once  'components/billing/view/cust.php';
      break;

   case "onetime_cust":
      include_once  'components/manager/modle/managerModule.php';
      getTown();
      getCust2('1,2');
      getOneCust('name', 'all');
      if (isMobile())
         include_once  'components/billing/view/m_cust.php';
      else
         include_once  'components/billing/view/cust.php';
      break;

      // added by E.S.P Nirmal 2021_06_02
   case "cust-list-one_time":
      include_once  'template/common.php';
      listCust($sub_system);
      include_once  'template/ajax_list.php';
      break;

      // added by E.S.P Nirmal 2021_06_03
   case "cust-list-wholesale":
      include_once  'template/common.php';
      listCust('all');
      include_once  'template/ajax_list.php';
      break;

      // added by E.S.P Nirmal 2021_06_02
   case "cust-list2-get-one-cust-ajax":
      include_once  'components/manager/modle/managerModule.php';
      print cust2Ajax('name', 'all');
      break;

      // added by E.S.P Nirmal 2021_06_25
   case "nic-check":
      include_once  'components/manager/modle/managerModule.php';
      print nicCheckAjax($sub_system);
      break;

      // added by E.S.P Nirmal 2021_06_25
   case "mobile-check": 
      include_once  'components/manager/modle/managerModule.php';
      print mobileCheckAjax($sub_system);
   break;


   case "cust_details":
      include_once  'components/manager/modle/managerModule.php';
      getSalesman($sub_system);
      getStore($sub_system);
      getOneCust('id', $sub_system);
      if (isMobile())
         include_once  'components/billing/view/m_cust.php';
      else
         include_once  'components/billing/view/cust.php';
      break;

   case "sales_report2":
      include_once  'components/manager/modle/managerModule.php';
      getCust($sub_system, '1');
      getStore($sub_system);
      getSalesReport2($sub_system);
      getCategory();
      if (isMobile())
         include_once  'components/manager/view/m_sales_report2.php';
      else
         include_once  'components/manager/view/sales_report2.php';
      break;

   case "sales_report3":
      include_once  'components/manager/modle/managerModule.php';
      getSalesReport3($sub_system);
      getCategory();
      if (isMobile())
         include_once  'components/manager/view/m_sales_report3.php';
      else
         include_once  'components/manager/view/sales_report3.php';
      break;

   case "unvisited":
      include_once  'components/manager/modle/managerModule.php';
      getSalesman($sub_system);
      getUnvisited($sub_system);
      if (isMobile())
         include_once  'components/manager/view/m_unvisited.php';
      else
         include_once  'components/manager/view/unvisited.php';
      break;

      // added by E.S.P Nirmal 2021_06_02
   case "add_cust":
      include_once  'components/manager/modle/managerModule.php';
      print addCust($systemid);
      break;

      // added by E.S.P Nirmal 2021_06_02
   case "add_cust_image1":
      include_once  'components/manager/modle/managerModule.php';
      if (addCustImage($systemid))
          header('Location: index.php?components=billing&action=home&s='.$_GET['s'].'&cust_odr='.$_GET['cust_odr'].'&message='.$message.' | Pending Approval&re=success');
      else
          header('Location: index.php?components=billing&action=wholesale_cust&s='.$_GET['s'].'&cust_odr='.$_GET['cust_odr'].'&message='.$message.'&re=fail');
      break;

      // case "add_cust1":
      //    include_once  'components/manager/modle/managerModule.php';
      //    if (addCust($systemid))
      //       header('Location: index.php?components=billing&action=home&s=' . $_GET['s'] . '&cust_odr=' . $_GET['cust_odr'] . '&message=' . $message . '&re=success');
      //    else
      //       header('Location: index.php?components=billing&action=wholesale_cust&s=' . $_GET['s'] . '&cust_odr=' . $_GET['cust_odr'] . '&message=' . $message . '&re=fail');
      //    break;

      // case "add_cust2":
      //    include_once  'components/manager/modle/managerModule.php';
      //    if (addCust($systemid))
      //       header('Location: index.php?components=billing&action=new_bill&quotation=0&salesman=' . $_GET['s'] . '&cust_odr=' . $_GET['cust_odr'] . '&cust_id=' . $cust_id);
      //    else
      //       header('Location: index.php?components=billing&action=onetime_cust&s=' . $_GET['s'] . '&cust_odr=' . $_GET['cust_odr'] . '&message=' . $message . '&re=fail');
      //    break;
      /*           
            case "repair" :
               include_once  'components/billing/modle/billingModule.php';
               getItems('service');
               getInvoiceItems();               
               include_once  'components/billing/view/repair.php';
            break;
  */
   case "new_bill":
      include_once  'components/billing/modle/billingModule.php';
      if (newBill($_REQUEST['cust_id'], $_REQUEST['cust_odr'], $_REQUEST['salesman'], $_REQUEST['quotation']))
         header('Location: index.php?components=billing&action=home&cust_odr=' . $_GET['cust_odr'] . '&id=' . $invoice_no . '&s=' . $salesman . '&cust=' . $cust);
      else
         header('Location: index.php?components=billing&action=home&s=' . $salesman . '&cust_odr=' . $_GET['cust_odr'] . '&message=' . $message . '&re=fail');
      break;

   case "apend_bill":
      include_once  'components/billing/modle/billingModule.php';
      $debug_id = debugStart(0, 0);
      if (isset($_POST['storecrossst'])) {
         if (apendBill(1, $systemid, $_POST['storecrossitm'], $_POST['storecrossst'])) {
            debugEnd($debug_id, 'success');
            header('Location: index.php?components=billing&action=home&cust_odr=' . $_GET['cust_odr'] . '&id=' . $invoice_no . '&s=' . $salesman . '&cust=' . $cust . '&message=' . $message . '&re=success');
         } else {
            debugEnd($debug_id, 'fail');
            header('Location: index.php?components=billing&action=home&cust_odr=' . $_GET['cust_odr'] . '&id=' . $invoice_no . '&s=' . $salesman . '&cust=' . $cust . '&message=' . $message . '&re=fail');
         }
      } else {
         header('Location: index.php?components=authenticate&action=logout');
      }
      break;
      /*            
            case "apend_bill2" :
               include_once  'components/billing/modle/billingModule.php';
               if(apendBill())
               		header('Location: index.php?components=billing&action=repair&id='.$invoice_no.'&s='.$salesman.'&message='.$message.'&re=success');
               	else
               		header('Location: index.php?components=billing&action=repair&id='.$invoice_no.'&s='.$salesman.'&message='.$message.'&re=fail');
            break;
*/
   case "get_qo_price":
      include_once  'components/billing/modle/billingModule.php';
      print getQOPrice();
      break;

   case "bill_item_gpdate":
      include_once  'components/billing/modle/billingModule.php';
      $debug_id = debugStart(0, 0);
      if (updateBillitem()) {
         debugEnd($debug_id, 'success');
         header('Location: index.php?components=billing&action=home&cust_odr=' . $_GET['cust_odr'] . '&id=' . $invoice_no . '&s=' . $salesman . '&cust=' . $cust . '&message=' . $message . '&re=success');
      } else {
         debugEnd($debug_id, 'fail');
         header('Location: index.php?components=billing&action=home&cust_odr=' . $_GET['cust_odr'] . '&id=' . $invoice_no . '&s=' . $salesman . '&cust=' . $cust . '&message=' . $message . '&re=fail');
      }
      break;

   case "bill_item_remove":
      include_once  'components/billing/modle/billingModule.php';
      $debug_id = debugStart(0, 0);
      if (removeBillitem()) {
         debugEnd($debug_id, 'success');
         header('Location: index.php?components=billing&action=home&cust_odr=' . $_GET['cust_odr'] . '&id=' . $invoice_no . '&s=' . $salesman . '&cust=' . $cust . '&message=' . $message . '&re=success');
      } else {
         debugEnd($debug_id, 'fail');
         header('Location: index.php?components=billing&action=home&cust_odr=' . $_GET['cust_odr'] . '&id=' . $invoice_no . '&s=' . $salesman . '&cust=' . $cust . '&message=' . $message . '&re=fail');
      }
      break;

   case "finish_bill":
      include_once  'components/billing/modle/billingModule.php';
      include_once  'components/repair/modle/repairModule.php';
      billPermission();
      billDetails();
      billTemplate();
      getBank();
      getSalesman2();
      getRepairComments();
      if ($_COOKIE['fastprint'] == 'on') generateInvoiceFast();
      if (isMobile())
         include_once  'components/billing/view/m_bill_print.php';
      else
         include_once  'components/billing/view/finish.php';
      break;

   case "finish_dn":
      include_once  'components/billing/modle/billingModule.php';
      billPermission();
      billDetails();
      billTemplate();
      getBank();
      if ($_COOKIE['fastprint'] == 'on') generateInvoiceFast();
      if (isMobile())
         include_once  'components/billing/view/m_bill_print.php';
      else
         include_once  'components/billing/view/finish.php';
      break;

   case "pos_print_bill":
      include_once  'components/billing/modle/billingModule.php';
      generateInvoice();
      include_once  'components/billing/view/pos_print_bill.php';
      break;

   case "today":
      include_once  'components/billing/modle/billingModule.php';
      today($systemid, $sub_system);
      if (isMobile())
         include_once  'components/billing/view/m_today.php';
      else
         include_once  'components/billing/view/today.php';
      break;

   case "delete":
      include_once  'components/billing/modle/billingModule.php';
      if (deleteBill(1, 0))
         header('Location: index.php?components=billing&action=today&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=billing&action=today&message=' . $message . '&re=fail');
      break;

   case "pay_bill":
      include_once  'components/billing/modle/billingModule.php';
      getBank();
      getBillTotal();
      $remaining_cr_limit = getCreditStatus2($_REQUEST['id']);
      getBillCust();
      setStatusCrossTrans($_GET['id'], '5');
      if (isMobile())
         include_once  'components/billing/view/m_bill_payment.php';
      else
         include_once  'components/billing/view/bill_payment.php';
      break;

   case "add_billpayment":
      include_once  'components/billing/modle/billingModule.php';
      if (addPayment('bill'))
         header('Location: index.php?components=billing&action=finish_bill&id=' . $invoice_no);
      else
         header('Location: index.php?components=billing&action=pay_bill&cust_odr=' . $_GET['cust_odr'] . '&id=' . $invoice_no . '&s=' . $_GET['s'] . '&message=' . $message . '&re=fail');
      break;

   case "search_bill":
      include_once  'components/billing/modle/billingModule.php';
      if (searchBill($_POST['search1']))
         header('Location: index.php?components=billing&action=finish_bill&id=' . $_POST['search1']);
      else
         header('Location: index.php?components=billing&action=home&s=' . $_GET['s'] . '&cust_odr=' . $_GET['cust_odr'] . '&message=Invalid Invoice Number&re=fail');
      break;

   case "search_pay":
      include_once  'components/billing/modle/billingModule.php';
      if (searchPay($_POST['search1']))
         header('Location: index.php?components=billing&action=finish_payment&id=' . $_POST['search1']);
      else
         header('Location: index.php?components=billing&action=payment&message=Invalid Invoice Number&re=fail');
      break;

   case "sms":
      include_once  'components/billing/modle/billingModule.php';
      printST();
      sms($sub_system);
      break;

   case "sms_resend":
      print smsResend($_GET['smsid']);
      break;

   case "setfastprint":
      include_once  'components/billing/modle/billingModule.php';
      if (setFastPrint())
         header('Location: index.php?components=billing&action=home&id=' . $invoice_no . '&s=' . $salesman . '&cust=' . $cust . '&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=billing&action=home&id=' . $invoice_no . '&s=' . $salesman . '&cust=' . $cust . '&message=' . $message . '&re=fail');
      break;

   case "set_delivered":
      include_once  'components/billing/modle/billingModule.php';
      if (setDelivered())
         header('Location: index.php?components=billing&action=finish_bill&id=' . $invoice_no . '&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=billing&action=finish_bill&id=' . $invoice_no . '&message=' . $message . '&re=fail');
      break;

   case "change_job_total":
      include_once  'components/billing/modle/billingModule.php';
      if (changeJobTotal())
         header('Location: index.php?components=billing&action=finish_bill&id=' . $invoice_no . '&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=billing&action=finish_bill&id=' . $invoice_no . '&message=' . $message . '&re=fail');
      break;

   case "get_store_stock":
      include_once  'components/billing/modle/billingModule.php';
      print storeCrossCheck($sub_system, $systemid, $_GET['itmid']);
      break;

   case "add_repair_comment":
      include_once  'components/repair/modle/repairModule.php';
      if (addRepairComment($_POST['repcom_type']))
         header('Location: index.php?components=billing&action=finish_bill&id=' . $bm_inv . '&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=billing&action=finish_bill&id=' . $bm_inv . '&message=' . $message . '&re=fail');
      break;

   case "del_repair_comment":
      include_once  'components/repair/modle/repairModule.php';
      if (delRepairComment())
         header('Location: index.php?components=billing&action=finish_bill&id=' . $bm_inv . '&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=billing&action=finish_bill&id=' . $bm_inv . '&message=' . $message . '&re=fail');
      break;

   case "add_repair_comment":
      include_once  'components/repair/modle/repairModule.php';
      if (addRepairComment(3))
         header('Location: index.php?components=billing&action=finish_bill&id=' . $bm_inv . '&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=billing&action=finish_bill&id=' . $bm_inv . '&message=' . $message . '&re=fail');
      break;
      //-------------------------Payment----------------------------------//

   case "payment":
      include_once  'components/billing/modle/billingModule.php';
      if (isset($_GET['cust'])) getCustPayments();
      getBank();
      if ($_COOKIE['retail'] == 0) getCust(1);
      if ($_COOKIE['retail'] == 1) getCust('1,2');
      getSalesman2();
      getCreditStatus($sub_system);
      searchPayments();
      if (isMobile())
         include_once  'components/billing/view/m_payment.php';
      else
         include_once  'components/billing/view/payment.php';
      break;

   case "add_payment":
      include_once  'components/billing/modle/billingModule.php';
      if (addPayment('pay')) {
         if ($bm_type == 3)
            header('Location: index.php?components=billing&action=finish_bill&id=' . $invoice_no . '&message=' . $message . '&re=success');
         else
            header('Location: index.php?components=billing&action=finish_payment&id=' . $payment_id);
      } else
         header('Location: index.php?components=billing&action=payment&cust=' . $cust . '&message=' . $message . '&re=fail');
      break;

   case "finish_payment":
      include_once  'components/billing/modle/billingModule.php';
      paymentPermission();
      billTemplate();
      payDetails();
      if (isMobile())
         include_once  'components/billing/view/m_payment_print.php';
      else
         include_once  'components/billing/view/payment_finish.php';
      break;

   case "delete_payment":
      include_once  'components/billing/modle/billingModule.php';
      if (deletePayment(1, 0))
         header('Location: index.php?components=billing&action=payment&cust=' . $cust . '&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=billing&action=payment&cust=' . $cust . '&message=' . $message . '&re=fail');
      break;

   case "cust_sale":
      include_once  'components/manager/modle/managerModule.php';
      getCust($sub_system, '1,2');
      getCustSale($systemid);
      if (isMobile())
         include_once  'components/manager/view/m_cust_sale.php';
      else
         include_once  'components/manager/view/cust_sale.php';
      break;

   case "check_payment_correlate":
      include_once  'components/manager/modle/managerModule.php';
      print checkPaymentCorrelate();
      break;

   case "validate_invoice":
      include_once  'components/billing/modle/billingModule.php';
      print validateInvoice();
      break;
      //--------------------------------------Chque Return----------------------------//
   case "chque_return":
      include_once  'components/billing/modle/billingModule.php';
      getReturnChque($_COOKIE['user_id']);
      if (isMobile())
         include_once  'components/billing/view/m_chque_return.php';
      else
         include_once  'components/billing/view/chque_return.php';
      break;

   case "rtnchque_clear":
      include_once  'components/billing/modle/billingModule.php';
      if (setChqRtnClear())
         header('Location: index.php?components=billing&action=chque_return&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=billing&action=chque_return&message=' . $message . '&re=fail');
      break;
      //--------------------------------------Item Return----------------------------//
   case "item_return":
      include_once  'components/billing/modle/billingModule.php';
      getItems(1, $sub_system, $systemid);
      if ($_COOKIE['retail'] == 0) getCust(1);
      if ($_COOKIE['retail'] == 1) getCust('1,2');
      getReturnItems();
      if (isMobile())
         include_once  'components/billing/view/m_item_return.php';
      else
         include_once  'components/billing/view/item_return.php';
      break;

   case "new_return":
      include_once  'components/billing/modle/billingModule.php';
      if (newReturn($_GET['cust'], $_GET['gps_x'], $_GET['gps_y']))
         header('Location: index.php?components=billing&action=item_return&id=' . $invoice_no . '&cust=' . $cust . '&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=billing&action=item_return&message=Invoice Could Not be Created&re=fail');
      break;

   case "apend_return":
      include_once  'components/billing/modle/billingModule.php';
      $debug_id = debugStart(0, 0);
      if (apendReturn()) {
         debugEnd($debug_id, 'success');
         header('Location: index.php?components=billing&action=item_return&id=' . $return_invoice_no . '&cust=' . $cust . '&message=' . $message . '&re=success');
      } else {
         debugEnd($debug_id, 'fail');
         header('Location: index.php?components=billing&action=item_return&id=' . $return_invoice_no . '&cust=' . $cust . '&message=' . $message . '&re=fail');
      }
      break;
      /*            
            case "return_item_gpdate" :
               include_once  'components/billing/modle/billingModule.php';
               $debug_id=debugStart(0,0);
               if(updateReturnitem()){
               		debugEnd($debug_id,'success');
               		header('Location: index.php?components=billing&action=item_return&id='.$return_invoice_no.'&cust='.$cust.'&message='.$message.'&re=success');
               	}else{
               		debugEnd($debug_id,'fail');
               		header('Location: index.php?components=billing&action=item_return&id='.$return_invoice_no.'&cust='.$cust.'&message='.$message.'&re=fail');
            	}
            break;
*/
   case "return_item_remove":
      include_once  'components/billing/modle/billingModule.php';
      $debug_id = debugStart(0, 0);
      if (removeReturnitem()) {
         debugEnd($debug_id, 'success');
         header('Location: index.php?components=billing&action=item_return&id=' . $return_invoice_no . '&cust=' . $cust . '&message=' . $message . '&re=success');
      } else {
         debugEnd($debug_id, 'fail');
         header('Location: index.php?components=billing&action=item_return&id=' . $return_invoice_no . '&cust=' . $cust . '&message=' . $message . '&re=fail');
      }
      break;

   case "finish_return":
      include_once  'components/billing/modle/billingModule.php';
      returnDetails();
      billTemplate();
      if (isMobile())
         include_once  'components/billing/view/m_return_finish.php';
      else
         include_once  'components/billing/view/return_finish.php';
      break;

   case "finalize_return":
      include_once  'components/billing/modle/billingModule.php';
      if (finalizeReturn())
         header('Location: index.php?components=billing&action=finish_return&id=' . $_GET['id']);
      else
         header('Location: index.php?components=billing&action=item_return&id=' . $_GET['id'] . '&message=' . $message . '&re=fail');
      break;

   case "delete_return":
      include_once  'components/billing/modle/billingModule.php';
      if (deleteReturn())
         header('Location: index.php?components=billing&action=item_return&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=billing&action=item_return&message=' . $message . '&re=fail');
      break;

   case "get_replacementsn":
      include_once  'components/billing/modle/billingModule.php';
      print getReplacementsn();
      break;

   case "get_pricediff":
      include_once  'components/billing/modle/billingModule.php';
      print getPricediff();
      break;

   case "search_return":
      include_once  'components/billing/modle/billingModule.php';
      if (searchReturn($_POST['search1']))
         header('Location: index.php?components=billing&action=finish_return&id=' . $_POST['search1']);
      else
         header('Location: index.php?components=billing&action=item_return');
      break;

   case "credit":
      include_once  'components/supervisor/modle/supervisorModule.php';
      getCreditData($sub_system);
      getFilter($sub_system);
      if (isMobile())
         include_once  'components/supervisor/view/m_credit_view.php';
      else
         include_once  'components/supervisor/view/credit_view.php';
      break;

   case "warranty":
      include_once  'components/billing/modle/billingModule.php';
      getWarrantyOngoingList();
      include_once  'components/billing/view/warranty.php';
      break;

   case "warranty_show":
      include_once  'components/billing/modle/billingModule.php';
      getWarrantyOngoingList();
      getWarrantyOne();
      include_once  'components/billing/view/warranty.php';
      break;

   case "warranty_repair":
      include_once  'components/billing/modle/billingModule.php';
      getWarrantyOngoingList();
      include_once  'components/billing/view/warranty.php';
      break;

   case "warranty_replace":
      include_once  'components/billing/modle/billingModule.php';
      getWarrantyOngoingList();
      getWarrantyReplace();
      getItems2();
      include_once  'components/billing/view/warranty.php';
      break;

   case "warranty_inventory":
      include_once  'components/billing/modle/billingModule.php';
      getWarrantyOngoingList();
      getWarrantyInv();
      getItems(1, $sub_system, $systemid);
      include_once  'components/billing/view/warranty.php';
      break;

   case "warranty_pay":
      include_once  'components/billing/modle/billingModule.php';
      getWarrantyOngoingList();
      getWarrantyPay();
      include_once  'components/billing/view/warranty.php';
      break;

   case "warranty_cust_pay":
      include_once  'components/billing/modle/billingModule.php';
      getWarrantyOngoingList();
      getWarrantyCustPay();
      include_once  'components/billing/view/warranty.php';
      break;

   case "warranty_print":
      include_once  'components/billing/modle/billingModule.php';
      getWarrantyPrintTemplate();
      include_once  'components/billing/view/warranty.php';
      break;

   case "warranty_validate":
      include_once  'components/billing/modle/billingModule.php';
      print validateWarranty($_GET['sn']);
      break;

   case "warranty_search":
      include_once  'components/billing/modle/billingModule.php';
      if (warrantySearch($sub_system))
         header('Location: index.php?components=billing&action=warranty_show&id=' . $id);
      else {
         include_once  'components/billing/view/warranty.php';
      }
      break;

   case "warranty_submit":
      include_once  'components/billing/modle/billingModule.php';
      if (warrantySubmit($sub_system))
         header('Location: index.php?components=billing&action=warranty_show&id=' . $id . '&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=billing&action=warranty&message=' . $message . '&re=fail');
      break;

   case "set_warranty_status":
      include_once  'components/billing/modle/billingModule.php';
      if (setWarrantyStatus())
         header('Location: index.php?components=billing&action=warranty_show&id=' . $_GET['id'] . '&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=billing&action=warranty&message=' . $message . '&re=fail');
      break;

   case "set_warranty_handover":
      include_once  'components/billing/modle/billingModule.php';
      if (setWarrantyHandover())
         header('Location: index.php?components=billing&action=warranty_show&id=' . $_GET['id'] . '&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=billing&action=warranty&message=' . $message . '&re=fail');
      break;

   case "set_warranty_repair":
      include_once  'components/billing/modle/billingModule.php';
      if (setWarrantyRepair())
         header('Location: index.php?components=billing&action=warranty_show&id=' . $_GET['id'] . '&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=billing&action=warranty&message=' . $message . '&re=fail');
      break;

   case "set_warranty_replace":
      include_once  'components/billing/modle/billingModule.php';
      if (setWarrantyReplace())
         header('Location: index.php?components=billing&action=warranty_show&id=' . $id . '&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=billing&action=warranty_show&id=' . $id . '&message=' . $message . '&re=fail');
      break;

   case "set_warranty_pay":
      include_once  'components/billing/modle/billingModule.php';
      if (setWarrantyPay())
         header('Location: index.php?components=billing&action=warranty_show&id=' . $id . '&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=billing&action=warranty_show&id=' . $id . '&message=' . $message . '&re=fail');
      break;

   case "set_warranty_cust_pay":
      include_once  'components/billing/modle/billingModule.php';
      if (setWarrantyCustPay())
         header('Location: index.php?components=billing&action=warranty_show&id=' . $id . '&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=billing&action=warranty_show&id=' . $id . '&message=' . $message . '&re=fail');
      break;

   case "delete_warranty":
      include_once  'components/billing/modle/billingModule.php';
      if (deleteWarranty())
         header('Location: index.php?components=billing&action=warranty&id=' . $id . '&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=billing&action=warranty_show&id=' . $id . '&message=' . $message . '&re=fail');
      break;

   case "add_warranty_inv":
      include_once  'components/billing/modle/billingModule.php';
      $debug_id = debugStart(0, 0);
      if (addWarrantyInv()) {
         debugEnd($debug_id, 'success');
         header('Location: index.php?components=billing&action=warranty_show&id=' . $id . '&message=' . $message . '&re=success');
      } else {
         debugEnd($debug_id, 'fail');
         header('Location: index.php?components=billing&action=warranty_show&id=' . $id . '&message=' . $message . '&re=fail');
      }
      break;

   case "return_warranty_inv":
      include_once  'components/billing/modle/billingModule.php';
      $debug_id = debugStart(0, 0);
      if (returnWarrantyInv($_GET['st'])) {
         debugEnd($debug_id, 'success');
         header('Location: index.php?components=billing&action=warranty_show&id=' . $id . '&message=' . $message . '&re=success');
      } else {
         debugEnd($debug_id, 'fail');
         header('Location: index.php?components=billing&action=warranty_show&id=' . $id . '&message=' . $message . '&re=fail');
      }
      break;

   case "return_warranty_inv2":
      include_once  'components/billing/modle/billingModule.php';
      if (returnWarrantyInv2($_GET['st'])) {
         header('Location: index.php?components=billing&action=warranty_show&id=' . $id . '&message=' . $message . '&re=success');
      } else {
         header('Location: index.php?components=billing&action=warranty_show&id=' . $id . '&message=' . $message . '&re=fail');
      }
      break;

      //-------------------Quotation-----------------------------------//
   case "quotation":
      include_once  'components/billing/modle/billingModule.php';
      include_once  'components/supervisor/modle/supervisorModule.php';
      getDistrict();
      getQuotationItems();
      if (isset($_COOKIE['district'])) {
         getItems($item_filter, $sub_system, $systemid);
         getCust(1);
         if (isset($_GET['cust'])) {
            if (validateQuotNo()) header('Location: index.php?components=billing&action=new_quot&cust_id=' . $_GET['cust'] . '&att=');
         }
      }
      include_once  'components/supervisor/view/quotation.php';
      break;

   case "new_quot":
      include_once  'components/supervisor/modle/supervisorModule.php';
      if (newQuot($_REQUEST['cust_id']))
         header('Location: index.php?components=billing&action=quotation&id=' . $quot_no . '&s=' . $salesman . '&cust=' . $cust);
      else
         header('Location: index.php?components=billing&action=quotation&message=' . $message . '&re=fail');
      break;

   case "apend_quot":
      include_once  'components/billing/modle/billingModule.php';
      include_once  'components/supervisor/modle/supervisorModule.php';
      if (apendQuot()) {
         header('Location: index.php?components=billing&action=quotation&id=' . $quot_no . '&s=' . $salesman . '&cust=' . $cust . '&message=' . $message . '&re=success');
      } else {
         header('Location: index.php?components=billing&action=quotation&id=' . $quot_no . '&s=' . $salesman . '&cust=' . $cust . '&message=' . $message . '&re=fail');
      }
      break;

   case "qo_item_gpdate":
      include_once  'components/supervisor/modle/supervisorModule.php';
      if (updateQuot()) {
         header('Location: index.php?components=billing&action=quotation&id=' . $quot_no . '&s=' . $salesman . '&cust=' . $cust . '&message=' . $message . '&re=success');
      } else {
         header('Location: index.php?components=billing&action=quotation&id=' . $quot_no . '&s=' . $salesman . '&cust=' . $cust . '&message=' . $message . '&re=fail');
      }
      break;

   case "qo_item_remove":
      include_once  'components/supervisor/modle/supervisorModule.php';
      if (removeQuot()) {
         header('Location: index.php?components=billing&action=quotation&id=' . $quot_no . '&s=' . $salesman . '&cust=' . $cust . '&message=' . $message . '&re=success');
      } else {
         header('Location: index.php?components=billing&action=quotation&id=' . $quot_no . '&s=' . $salesman . '&cust=' . $cust . '&message=' . $message . '&re=fail');
      }
      break;

   case "qo_terms":
      include_once  'components/supervisor/modle/supervisorModule.php';
      getQOTerms();
      getDetaultTerms();
      include_once  'components/supervisor/view/quotation_terms.php';
      break;

   case "set_qo_terms":
      include_once  'components/supervisor/modle/supervisorModule.php';
      if (setQuotTerms())
         header('Location: index.php?components=billing&action=set_quot_status&id=' . $quot_no . '&new_status=2');
      else
         header('Location: index.php?components=billing&action=qo_terms&id=' . $quot_no . '&message=' . $message . '&re=fail');
      break;

   case "set_quot_status":
      include_once  'components/supervisor/modle/supervisorModule.php';
      if (setQuotStatus($_GET['new_status']))
         header('Location: index.php?components=billing&action=qo_finish&id=' . $quot_no);
      else
         header('Location: index.php?components=billing&action=qo_finish&id=' . $quot_no . '&message=' . $message . '&re=fail');
      break;

   case "qo_revise":
      include_once  'components/supervisor/modle/supervisorModule.php';
      if (qoRevise())
         header('Location: index.php?components=billing&action=qo_finish&id=' . $quot_no);
      else
         header('Location: index.php?components=billing&action=qo_finish&id=' . $quot_no . '&message=' . $message . '&re=fail');
      break;

   case "set_submit":
      include_once  'components/supervisor/modle/supervisorModule.php';
      setQuotStatus(5);
      break;

   case "qo_finish":
      include_once  'components/supervisor/modle/supervisorModule.php';
      qoPermission();
      qoDetails();
      qoNote();
      qoTemplate();
      if (isMobile())
         include_once  'components/supervisor/view/m_qo_print.php';
      else
         include_once  'components/supervisor/view/qo_print.php';
      break;

   case "qo_one":
      include_once  'components/supervisor/modle/supervisorModule.php';
      qoPermission();
      qoDetails();
      qoNote();
      qoTemplate();
      generateQuot(2);
      include_once  'components/supervisor/view/qo_one.php';
      break;

   case "qo_com_inv":
      include_once  'components/supervisor/modle/supervisorModule.php';
      qoPermission();
      qoDetails();
      qoNote();
      qoTemplate();
      include_once  'components/supervisor/view/qo_print.php';
      break;

   case "quotation_list":
      include_once  'components/supervisor/modle/supervisorModule.php';
      getQuotList($sub_system);
      getFilter($sub_system);
      getCustSup($sub_system);
      include_once  'components/supervisor/view/quotation_list.php';
      break;

   case "qo_complete_check":
      include_once  'components/supervisor/modle/supervisorModule.php';
      print qoCompleteCheck();
      break;

   case "quotation_ongoing":
      include_once  'components/supervisor/modle/supervisorModule.php';
      getOnGoing();
      include_once  'components/supervisor/view/quotation_ongoing.php';
      break;

   case "search_quot":
      include_once  'components/supervisor/modle/supervisorModule.php';
      if (searchQuot($_POST['search1']))
         header('Location: index.php?components=billing&action=qo_finish&id=' . $_POST['search1']);
      else
         header('Location: index.php?components=billing&action=quotation&message=Invalid%20Quotation%20Number&re=fail');
      break;

   case "qo_add_image":
      include_once  'components/supervisor/modle/supervisorModule.php';
      if (qoAddImage())
         header('Location: index.php?components=billing&action=qo_finish&id=' . $_GET['id'] . '&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=billing&action=qo_finish&id=' . $_GET['id'] . '&message=' . $message . '&re=fail');
      break;

   case "qo_delete_image":
      include_once  'components/supervisor/modle/supervisorModule.php';
      if (qoDeleteImage())
         header('Location: index.php?components=billing&action=qo_finish&id=' . $_GET['id'] . '&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=billing&action=qo_finish&id=' . $_GET['id'] . '&message=' . $message . '&re=fail');
      break;

   case "qo_img_height":
      include_once  'components/supervisor/modle/supervisorModule.php';
      if (qoImgHeight())
         header('Location: index.php?components=billing&action=qo_finish&id=' . $_GET['id'] . '&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=billing&action=qo_finish&id=' . $_GET['id'] . '&message=' . $message . '&re=fail');
      break;

   case "qo_add_note":
      include_once  'components/supervisor/modle/supervisorModule.php';
      if (qoAddNote())
         header('Location: index.php?components=billing&action=qo_finish&id=' . $quot_no);
      else
         header('Location: index.php?components=billing&action=qo_finish&id=' . $quot_no . '&message=' . $message . '&re=fail');
      break;

   case "qo_update_note":
      include_once  'components/supervisor/modle/supervisorModule.php';
      if (qoUpdateNote())
         header('Location: index.php?components=billing&action=qo_finish&id=' . $quot_no . '&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=billing&action=qo_finish&id=' . $quot_no . '&message=' . $message . '&re=fail');
      break;

   case "quotation_report":
      include_once  'components/supervisor/modle/supervisorModule.php';
      getFilter($sub_system);
      getCustSup($sub_system);
      getReportNote($sub_system);
      include_once  'components/supervisor/view/quotation_report.php';
      break;

      //----------------------Cheque OPS---------------------------------------------------------------//
   case "chque_ops": 
      include_once  'components/billing/modle/billingModule.php';
      checkOPS();
      if (isMobile())
         include_once  'components/billing/view/m_cheque_ops.php';
      else 
         include_once  'components/billing/view/cheque_ops.php';
      break;

      //----------------------Drawer---------------------------------------------------------------//
   case "drawer_search":
      include_once  'components/inventory/modle/inventoryModule.php';
      getStores($sub_system);
      drawerSearch();
      if (isMobile())
         include_once  'components/inventory/view/m_drawerSearch.php';
      else
         include_once  'components/inventory/view/drawerSearch.php';
      break;

      //----------------------Reports---------------------------------------------------------------//
   case "cust_bill":
      include_once  'components/billing/modle/billingModule.php';
      searchCust();
      include_once  'components/billing/view/report_cust_bill.php';
      break;

   case "sale":
      if ($systemid != 1 && $systemid != 4) {
         include_once  'components/supervisor/modle/supervisorModule.php';
         dailySale($_GET['store']);
         $storedisable = '';
         $userdisable = 'disabled';
         getFilter($sub_system);
         if (isMobile())
            include_once  'components/supervisor/view/m_sale_view.php';
         else
            include_once  'components/supervisor/view/sale_view.php';
      }
      break;

	case "sold_qty":
      include_once  'components/manager/modle/managerModule.php';
      getStore($sub_system);
      getSoldQty($sub_system, $_REQUEST['components']);
      if (isMobile())
         include_once  'components/manager/view/m_sold_qty.php';
      else
         include_once  'components/manager/view/sold_qty.php';
      break;
   
   // added by nirmal 11_02_2022
   case "salesman_commission_new" :
      include_once  'components/report/modle/reportModule.php';
      getSMCommission();
      if(isMobile())           
         include_once  'components/report/view/m_salesman_commission_new.php';
      else
         include_once  'components/report/view/salesman_commission_new.php';
   break;

   // added by nirmal 11_02_2022
   case "salesman_commission_old":
      include_once  'components/report/modle/reportModule.php';
      getSMCommissionList();
      if(isMobile())           
            include_once  'components/report/view/m_salesman_commission_list.php';
      else
            include_once  'components/report/view/salesman_commission_list.php';
   break;

   // added by nirmal 11_02_2022
   case "salesman_commission_one":
      include_once  'components/report/modle/reportModule.php';
      getSMCommissionOne();
      if(isMobile())           
         include_once  'components/report/view/m_salesman_commission_one.php';
      else
         include_once  'components/report/view/salesman_commission_one.php';
   break;

   // added by nirmal 05_04_2022
   case "salesman_commission_incomplete_one":
      include_once  'components/report/modle/reportModule.php';
      getSMCommissionIncomplete();
      if (isMobile())
         include_once  'components/report/view/m_salesman_commission_incomplete_one.php';
      else
         include_once  'components/report/view/salesman_commission_incomplete_one.php';
   break;

   // added by nirmal 18_02_2022
   case "mk_home":
      include_once  'components/marketing/modle/marketingModule.php';
      getSubSystem();
      getStore($sub_system);
      getGroup($sub_system);
      setActiveSalesman($sub_system);
      getCustomerList();
      getActiveTown();
      if(isMobile())
         include_once  'components/marketing/view/m_home.php';
      else 
      include_once  'components/marketing/view/home.php';
   break;

   case "get_cust_more" :
      include_once  'components/marketing/modle/marketingModule.php';
      print getCustMore();
   break;
      
   default:
      print '<p><srtong>Bad Request</strong></p>';
      break;
}
