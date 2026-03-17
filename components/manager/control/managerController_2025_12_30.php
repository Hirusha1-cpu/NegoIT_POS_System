<?php
$components = $_GET['components'];
if (passwordExpire()) {
   header('Location: index.php?components=authenticate&action=change_pw&message=Your%20Password%20Has%20Expired.%20Please%20Change%20it%20NOW&re=fail');
}

if (($sub_system != 0 && $_REQUEST['action'] == 'shipment') && ($systemid != 13)) {
   header('Location: index.php?components=manager&action=daily_sale&store=all&group=all&salesman=all&processby=all&lock=1&cashback=no&type=');
}

switch ($_REQUEST['action']) {
   case "daily_sale":
      include_once 'components/manager/modle/managerModule.php';
      dailySale($_GET['store'], $sub_system);
      getFilter($sub_system);
      getCustGroups($sub_system);
      if (isMobile())
         include_once 'components/manager/view/m_daily_sale.php';
      else
         include_once 'components/manager/view/daily_sale.php';
      break;

   case "daily_sale_detail":
      include_once 'components/manager/modle/managerModule.php';
      dailySale2($_GET['store'], $sub_system);
      getFilter($sub_system);
      getCustGroups($sub_system);
      include_once 'components/manager/view/daily_sale_detail.php';
      break;

   case "cust_sale":
      include_once 'components/manager/modle/managerModule.php';
      getCust($sub_system, '0,1,2');
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

   case "sales_report2":
      include_once 'components/manager/modle/managerModule.php';
      getCust($sub_system, '1');
      getStore($sub_system);
      getSalesReport2($sub_system);
      getCategory();
      getFilter($sub_system);
      if (isMobile())
         include_once 'components/manager/view/m_sales_report2.php';
      else
         include_once 'components/manager/view/sales_report2.php';
      break;

   case "sales_summary":
      include_once 'components/manager/modle/managerModule.php';
      getStore($sub_system);
      getCategory();
      getSalesSummary($sub_system);
      if (isMobile())
         include_once 'components/manager/view/m_sales_summary.php';
      else
         include_once 'components/manager/view/sales_summary.php';
      break;

   case "sales_summary_detail":
      include_once 'components/manager/modle/managerModule.php';
      getStore($sub_system);
      getCategory();
      getSalesSummaryDetail($sub_system);
      if (isMobile())
         include_once 'components/manager/view/m_sales_summary_detail.php';
      else
         include_once 'components/manager/view/sales_summary_detail.php';
      break;

   // updated by nirmal 26_09_2022
   case "sales_bycategory":
      include_once 'components/manager/modle/managerModule.php';
      salesByCategory($sub_system);
      getFilter($sub_system);
      getCust($sub_system, '0,1,2');
      if (isMobile())
         include_once 'components/manager/view/m_sales_by_category.php';
      else
         include_once 'components/manager/view/sales_by_category.php';
      break;

   case "sales_byrep":
      include_once 'components/manager/modle/managerModule.php';
      getCategory();
      getStore($sub_system);
      salesByRep($sub_system);
      getSalesman($sub_system);
      if (isMobile())
         include_once 'components/manager/view/m_sales_by_rep.php';
      else
         include_once 'components/manager/view/sales_by_rep.php';
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

   case "repair_income":
      include_once 'components/manager/modle/managerModule.php';
      getRepairIncome($sub_system);
      if (isMobile())
         include_once 'components/manager/view/m_repair_income.php';
      else
         include_once 'components/manager/view/repair_income.php';
      break;

   case "repair_income_one":
      include_once 'components/manager/modle/managerModule.php';
      getRepairIncomeOne($sub_system);
      if (isMobile())
         include_once 'components/manager/view/m_repair_income_one.php';
      else
         include_once 'components/manager/view/repair_income_one.php';
      break;

   case "export_unic_list":
      include_once 'components/manager/modle/managerModule.php';
      getSalesReport4($sub_system);
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

   case "sold_qty":
      include_once 'components/manager/modle/managerModule.php';
      getStore($sub_system);
      getCategory();
      getSoldQty($sub_system, $_GET['components']);
      if (isMobile())
         include_once 'components/manager/view/m_sold_qty.php';
      else
         include_once 'components/manager/view/sold_qty.php';
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

   //added by nirmal 17_07_2024
   case "category_based_credit":
      include_once 'components/supervisor/modle/supervisorModule.php';
      getCreditReportBasedOnItemCategory($sub_system);
      getFilter($sub_system);
      // if (isMobile())
      //    include_once  'components/supervisor/view/m_credit_view.php';
      // else
      include_once 'components/supervisor/view/item_category_credit_report.php';
      break;

   case "disabledcust":
      include_once 'components/manager/modle/managerModule.php';
      getCust($sub_system, '0');
      getSalesman($sub_system);
      getStore($sub_system);
      getCustGroups($sub_system);
      getTown();
      getCust2('1');
      getOneCust('name', 'all');
      if (isMobile())
         include_once 'components/manager/view/m_manageCust.php';
      else
         include_once 'components/manager/view/manageCust.php';
      break;

   case "newcust":
      include_once 'components/manager/modle/managerModule.php';
      getCust($sub_system, '1,3');
      getSalesman($sub_system);
      getStore($sub_system);
      getCustGroups($sub_system);
      getTown();
      getCust2('1');
      getOneCust('name', 'all');
      if (isMobile())
         include_once 'components/manager/view/m_manageCust.php';
      else
         include_once 'components/manager/view/manageCust.php';
      break;

   case "editcust":
      include_once 'components/manager/modle/managerModule.php';
      getCust($sub_system, '1,3');
      getSalesman($sub_system);
      getStore($sub_system);
      getCustGroups($sub_system);
      getTown();
      getOneCust('id', $sub_system);
      if (isMobile())
         include_once 'components/manager/view/m_manageCust.php';
      else
         include_once 'components/manager/view/manageCust.php';
      break;

   case "cust-list":
      include_once 'template/common.php';
      listCust($sub_system);
      include_once 'template/ajax_list.php';
      break;

   // added by nirmal 2021_06_02
   case "cust-list2":
      include_once 'template/common.php';
      if (($systemid == 1) && ($sub_system == 1) || ($systemid == 1) && ($sub_system == 2)) {
         listCust(0);
      } else {
         listCust($sub_system);
      }
      include_once 'template/ajax_list.php';
      break;

   // added by nirmal 2021_06_02
   case "cust-list2-get-one-cust-ajax":
      include_once 'components/manager/modle/managerModule.php';
      print cust2Ajax('name', 'all');
      break;

   case "nick-list":
      include_once 'template/common.php';
      listCust($sub_system);
      include_once 'template/ajax_list.php';
      break;

   case "mob-list":
      include_once 'template/common.php';
      listCust($sub_system);
      include_once 'template/ajax_list.php';
      break;

   case "nic-list":
      include_once 'template/common.php';
      listCust($sub_system);
      include_once 'template/ajax_list.php';
      break;

   case "more_cust":
      include_once 'template/common.php';
      print moreCust($sub_system);
      break;

   case "searchcust":
      include_once 'components/manager/modle/managerModule.php';
      getCustSearchList($sub_system);
      getSalesman($sub_system);
      getStore($sub_system);
      getCustGroups($sub_system);
      getTown();
      getOneCust('name', 'all');
      if (isMobile())
         include_once 'components/manager/view/m_manageCust.php';
      else
         include_once 'components/manager/view/manageCust.php';
      break;

   case "cust_details":
      include_once 'components/manager/modle/managerModule.php';
      getSalesman($sub_system);
      getStore($sub_system);
      getOneCust('id', $sub_system);
      if (isMobile())
         include_once 'components/billing/view/m_cust.php';
      else
         include_once 'components/billing/view/cust.php';
      break;

   // added by E.S.P Nirmal 2021_06_02
   case "add_cust":
      include_once 'components/manager/modle/managerModule.php';
      print addCust($systemid);
      break;

   // added by E.S.P Nirmal 2021_06_14
   case "nic-check":
      include_once 'components/manager/modle/managerModule.php';
      print nicCheckAjax($sub_system);
      break;

   // added by E.S.P Nirmal 2021_06_14
   case "mobile-check":
      include_once 'components/manager/modle/managerModule.php';
      print mobileCheckAjax($sub_system);
      break;

   // added by nirmal 28_04_2022
   case "cust-check":
      include_once 'components/manager/modle/managerModule.php';
      print custCheckAjax($sub_system);
      break;

   // added by E.S.P Nirmal 2021_06_02
   case "add_cust_image":
      include_once 'components/manager/modle/managerModule.php';
      if (addCustImage($systemid))
         header('Location: index.php?components=manager&action=newcust&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=manager&action=newcust&message=' . $message . '&re=fail');
      break;

   case "update_cust":
      include_once 'components/manager/modle/managerModule.php';
      if (updateCust())
         header('Location: index.php?components=manager&action=newcust&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=manager&action=newcust&message=' . $message . '&re=fail');
      break;

   case "delete_cust":
      include_once 'components/manager/modle/managerModule.php';
      if (deleteCust())
         header('Location: index.php?components=manager&action=newcust&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=manager&action=newcust&message=' . $message . '&re=fail');
      break;

   case "disable_cust":
      include_once 'components/manager/modle/managerModule.php';
      if (setStatusCust(0))
         header('Location: index.php?components=manager&action=newcust&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=manager&action=newcust&message=' . $message . '&re=fail');
      break;

   case "enbale_cust":
      include_once 'components/manager/modle/managerModule.php';
      if (setStatusCust(1))
         header('Location: index.php?components=manager&action=newcust&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=manager&action=newcust&message=' . $message . '&re=fail');
      break;

   case "show_custgroup":
      include_once 'components/manager/modle/managerModule.php';
      getCustGroups($sub_system);
      if (isMobile())
         include_once 'components/manager/view/m_custGroup.php';
      else
         include_once 'components/manager/view/custGroup.php';
      break;

   case "edit_custgroup":
      include_once 'components/manager/modle/managerModule.php';
      getCustGroups($sub_system);
      if (isMobile())
         include_once 'components/manager/view/m_custGroup.php';
      else
         include_once 'components/manager/view/custGroup.php';
      break;

   case "add_custgroup":
      include_once 'components/manager/modle/managerModule.php';
      if (addCustGroup())
         header('Location: index.php?components=manager&action=show_custgroup&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=manager&action=show_custgroup&message=' . $message . '&re=fail');
      break;

   case "update_custgroup":
      include_once 'components/manager/modle/managerModule.php';
      if (updateCustGroup())
         header('Location: index.php?components=manager&action=show_custgroup&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=manager&action=show_custgroup&message=' . $message . '&re=fail');
      break;

   case "delete_custgroup":
      include_once 'components/manager/modle/managerModule.php';
      if (deleteCustGroup())
         header('Location: index.php?components=manager&action=show_custgroup&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=manager&action=show_custgroup&message=' . $message . '&re=fail');
      break;

   case "show_custtown":
      include_once 'components/manager/modle/managerModule.php';
      getTown();
      if (isMobile())
         include_once 'components/manager/view/m_custTown.php';
      else
         include_once 'components/manager/view/custTown.php';
      break;

   case "edit_custtown":
      include_once 'components/manager/modle/managerModule.php';
      getTown();
      if (isMobile())
         include_once 'components/manager/view/m_custTown.php';
      else
         include_once 'components/manager/view/custTown.php';
      break;

   case "add_custtown":
      include_once 'components/manager/modle/managerModule.php';
      if (addCustTown())
         header('Location: index.php?components=manager&action=show_custtown&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=manager&action=show_custtown&message=' . $message . '&re=fail');
      break;

   case "update_custtown":
      include_once 'components/manager/modle/managerModule.php';
      if (updateCustTown())
         header('Location: index.php?components=manager&action=show_custtown&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=manager&action=show_custtown&message=' . $message . '&re=fail');
      break;

   case "delete_custtown":
      include_once 'components/manager/modle/managerModule.php';
      if (deleteCustTown())
         header('Location: index.php?components=manager&action=show_custtown&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=manager&action=show_custtown&message=' . $message . '&re=fail');
      break;

   case "set_town_default":
      include_once 'components/manager/modle/managerModule.php';
      print setTownDefault();
      break;

   // added by nirmal 25_05_2022
   case "cust_dob":
      include_once 'components/manager/modle/managerModule.php';
      getCustDOB();
      if (isMobile())
         include_once 'components/manager/view/m_cust_dob.php';
      else
         include_once 'components/manager/view/cust_dob.php';
      break;

   // added by nirmal 26_05_2022
   case "tag_list":
      include_once 'components/manager/modle/managerModule.php';
      generateTag();
      include_once 'components/manager/view/cust_address_list.php';
      break;

   // // added by nirmal 21_12_2023
   // case "special_event_sms":
   //    include_once  'components/manager/modle/managerModule.php';
   //    getCustGroups('all');
   //    getSpecialEventSMSData();
   //    include_once  'components/manager/view/special_event_sms.php';
   // break;

   // // added by nirmal 22_12_2023
   // case "send_special_event_sms":
   //    include_once  'components/manager/modle/managerModule.php';
   //    if(sendBulkSMS()){
   //       header('Location: index.php?components=manager&action=special_event_sms&message=' . $message . '&re=success');
   //    }else{
   //       header('Location: index.php?components=manager&action=special_event_sms&message=' . $message . '&re=fail');
   //    }
   // break;

   //-----------------------------Chque_return------------------------------------//
   case "chque_return":
      include_once 'components/manager/modle/managerModule.php';
      getChqueNo($sub_system, 0);
      getReturnedChque($sub_system);
      getChqueOne();
      include_once 'components/manager/view/chque_return.php';
      break;

   case "chque_setreturn":
      include_once 'components/manager/modle/managerModule.php';
      if (setChqueStatus(1))
         header('Location: index.php?components=manager&action=chque_return&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=manager&action=chque_return&message=' . $message . '&re=fail');
      break;

   case "rtnchque_pending":
      include_once 'components/manager/modle/managerModule.php';
      if (setChqRtnSts(0))
         header('Location: index.php?components=manager&action=chque_return&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=manager&action=chque_return&message=' . $message . '&re=fail');
      break;

   case "rtnchque_delete":
      include_once 'components/manager/modle/managerModule.php';
      if (setChqRtnSts(2))
         header('Location: index.php?components=manager&action=chque_return&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=manager&action=chque_return&message=' . $message . '&re=fail');
      break;

   //-----------------------------Chque_Postpone------------------------------------//
   case "chque_postpone":
      include_once 'components/manager/modle/managerModule.php';
      getChqueNo($sub_system, 1);
      getPostponedChque($sub_system);
      getChqueOne();
      include_once 'components/manager/view/chque_postpone.php';
      break;

   case "chque_set_postpone":
      include_once 'components/manager/modle/managerModule.php';
      if (setChquePostpone($_POST['case']))
         header('Location: index.php?components=manager&action=chque_postpone&chque_no=' . $py_chqnofull . '&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=manager&action=chque_postpone&chque_no=' . $py_chqnofull . '&message=' . $message . '&re=fail');
      break;

   case "moveto_postpone":
      include_once 'components/manager/modle/managerModule.php';
      if (moveToPostpone())
         header('Location: index.php?components=manager&action=chque_postpone&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=manager&action=chque_postpone&message=' . $message . '&re=fail');
      break;

   case "fullclear_postpone":
      include_once 'components/manager/modle/managerModule.php';
      if (fullClearPostpone())
         header('Location: index.php?components=manager&action=chque_postpone&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=manager&action=chque_postpone&message=' . $message . '&re=fail');
      break;

   //-----------------------------Return-------------------------------------//
   case "show_return_summary":
      include_once 'components/manager/modle/managerModule.php';
      getSalesman($sub_system);
      getCust2(1);
      getReturnSummary();
      include_once 'components/manager/view/return_summary.php';
      break;

   case "show_return":
      include_once 'components/manager/modle/managerModule.php';
      getSalesman($sub_system);
      getCust2(1);
      getReturn('all');
      include_once 'components/manager/view/return.php';
      break;

   //-----------------------------Disposal---------------------------------------//
   case "show_disposal":
      include_once 'components/manager/modle/managerModule.php';
      getDisposal();
      include_once 'components/manager/view/disposal.php';
      break;

   case "move_disposal":
      include_once 'components/manager/modle/managerModule.php';
      if (moveDisposal())
         header('Location: index.php?components=manager&action=show_disposal&year=' . $_GET['year'] . '&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=manager&action=show_disposal&year=' . $_GET['year'] . '&message=' . $message . '&re=fail');
      break;
   //-----------------------------Device MGMT-------------------------------------//
   case "device_mgmt":
      include_once 'components/manager/modle/managerModule.php';
      getDevices($sub_system);
      if (isMobile())
         include_once 'components/manager/view/m_device_mgmt.php';
      else
         include_once 'components/manager/view/device_mgmt.php';
      break;

   case "device_register":
      include_once 'components/manager/modle/managerModule.php';
      if (registerDevice())
         header('Location: index.php?components=manager&action=device_mgmt&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=manager&action=device_mgmt&message=' . $message . '&re=fail');
      break;

   case "device_unregister":
      include_once 'components/manager/modle/managerModule.php';
      if (unregisterDevice())
         header('Location: index.php?components=manager&action=device_mgmt&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=manager&action=device_mgmt&message=' . $message . '&re=fail');
      break;

   case "unlocked":
      include_once 'components/manager/modle/managerModule.php';
      getUnlockedBills($_COOKIE['sub_system']);
      if (isMobile())
         include_once 'components/manager/view/m_unlocked.php';
      else
         include_once 'components/manager/view/unlocked.php';
      break;

   // added by nirmal 11_07_2023
   case "temporary_bills":
      include_once 'components/manager/modle/managerModule.php';
      getFilter($sub_system);
      getTemporaryBills($_COOKIE['sub_system']);
      if (isMobile())
         include_once 'components/manager/view/m_temporary_bills.php';
      else
         include_once 'components/manager/view/temporary_bills.php';
      break;

   //-----------------------------Unic Item-------------------------------------//
   case "unic_items":
      include_once 'components/manager/modle/managerModule.php';
      getUnicItems();
      include_once 'components/manager/view/unic_item.php';
      break;

   case "sn_lookup":
      include_once 'components/manager/modle/managerModule.php';
      getStore($sub_system);
      if (isMobile())
         include_once 'components/manager/view/m_sn_lookup.php';
      else
         include_once 'components/manager/view/sn_lookup.php';
      break;

   case "sn_lookup_list":
      include_once 'components/manager/modle/managerModule.php';
      print snLookupList();
      break;

   case "desc-list":
      include_once 'template/common.php';
      listItem($sub_system);
      include_once 'template/ajax_list.php';
      break;

   case "sn-list-all":
      include_once 'components/manager/modle/managerModule.php';
      snListAll();
      include_once 'template/ajax_list.php';
      break;

   //-----------------------------Shipment------------------------------------//

   case "shipment":
      include_once 'components/manager/modle/managerModule.php';
      getShipmentList();
      getShipmentOne();
      include_once 'components/manager/view/shipment.php';
      break;

   case "add_ship_payment":
      include_once 'components/manager/modle/managerModule.php';
      if (addShipPayment($_GET['case']))
         header('Location: index.php?components=manager&action=shipment&id=' . $shipment_no . '&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=manager&action=shipment&id=' . $shipment_no . '&message=' . $message . '&re=fail');
      break;

   case "delete_ship_payment":
      include_once 'components/manager/modle/managerModule.php';
      if (deleteShipPayment())
         header('Location: index.php?components=manager&action=shipment&id=' . $shipment_no . '&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=manager&action=shipment&id=' . $shipment_no . '&message=' . $message . '&re=fail');
      break;

   //-----------------------------Chque------------------------------------//
   case "chque_pending_finalyze":
      include_once 'components/manager/modle/managerModule.php';
      getStore($sub_system);
      getSalesman($sub_system);
      chquePendingFinalyze();
      getBankAccounts();
      getChequeNames();
      if (isMobile())
         include_once 'components/manager/view/m_chque_pending_finalyze.php';
      else
         include_once 'components/manager/view/chque_pending_finalyze.php';
      break;

   case "chque_pending_finalyze2":
      include_once 'components/manager/modle/managerModule.php';
      getStore($sub_system);
      getSalesman($sub_system);
      chquePendingFinalyze2();
      getBankAccounts();
      getChequeNames();
      if (isMobile())
         include_once 'components/manager/view/m_chque_pending_finalyze_2.php';
      else
         include_once 'components/manager/view/chque_pending_finalyze_2.php';
      break;

   case "chque_realize_report_onedate":
      include_once 'components/manager/modle/managerModule.php';
      getStore($sub_system);
      getSubSystems3($sub_system);
      getSalesman($sub_system);
      getChqueData($sub_system);
      getBankAccounts();
      if (isMobile())
         include_once 'components/manager/view/m_chque_realize_report_onedate.php';
      else
         include_once 'components/manager/view/chque_realize_report_onedate.php';
      break;

   case "chque_realize_report_daterange":
      include_once 'components/manager/modle/managerModule.php';
      getStore($sub_system);
      getSubSystems3($sub_system);
      getSalesman($sub_system);
      getChqueRange($sub_system);
      if (isMobile())
         include_once 'components/manager/view/m_chque_realize_report_daterange.php';
      else
         include_once 'components/manager/view/chque_realize_report_daterange.php';
      break;

   // updated by nirmal 21_12_02
   case "clear_chque_list":
      include_once 'components/manager/modle/managerModule.php';
      getClearedChques();
      getBankAccounts();
      include_once 'components/manager/view/chque_clear.php';

      break;

   // update by nirmal 21_11_11
   case "clear_chque":
      include_once 'components/manager/modle/managerModule.php';
      print clearChque();
      break;

   // added by nirmal 05_11_2024
   case "clear_chque_2":
      include_once 'components/manager/modle/managerModule.php';
      print clearChque2();
      break;

   //-----------------------------Authorize Code------------------------------------//
   case "authorize_code":
      include_once 'components/manager/modle/managerModule.php';
      getAuthorizeCodelist();
      if (isMobile())
         include_once 'components/manager/view/m_authorize.php';
      else
         include_once 'components/manager/view/authorize.php';
      break;

   case "get_authorize":
      include_once 'components/manager/modle/managerModule.php';
      print getAuthorize2();
      break;
   //-----------------------------Payment------------------------------------//
   case "payment":
      include_once 'components/manager/modle/managerModule.php';
      getPaymentData($sub_system);
      getBank2();
      getOnePayment();
      include_once 'components/manager/view/payment.php';
      break;

   case "add_payment":
      include_once 'components/manager/modle/managerModule.php';
      if (addPayment($sub_system))
         header('Location: index.php?components=manager&action=payment&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=manager&action=payment&message=' . $message . '&re=fail');
      break;

   case "delete_payment":
      include_once 'components/manager/modle/managerModule.php';
      if (deletePayment($sub_system))
         header('Location: index.php?components=manager&action=payment&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=manager&action=payment&message=' . $message . '&re=fail');
      break;

   case "payment_history":
      include_once 'components/manager/modle/managerModule.php';
      getPaymentHistory($sub_system);
      include_once 'components/manager/view/payment_history.php';
      break;
   //-------------------------INV MGMT----------------------------------------------//
   case "inv_mgmt":
      include_once 'components/manager/modle/managerModule.php';
      searchInv($sub_system);
      getSalesman($sub_system);
      include_once 'components/manager/view/inv_mgmt.php';
      break;

   case "inv_mgmt_changesm":
      include_once 'components/manager/modle/managerModule.php';
      print changeSalesman();
      break;

   // added by nirmal 21_10_22
   case "inv_mgmt_change_recovery_agent":
      include_once 'components/manager/modle/managerModule.php';
      print changeRecoveryAgent();
      break;

   case "set_inv_main":
      include_once 'components/manager/modle/managerModule.php';
      print setInvMain();
      break;

   case "set_inv_date":
      include_once 'components/manager/modle/managerModule.php';
      print setInvDate();
      break;

   case "set_pay_date":
      include_once 'components/manager/modle/managerModule.php';
      print setPayDate();
      break;

   case "delete_bill_payment":
      include_once 'components/manager/modle/managerModule.php';
      print deleteBillPayment();
      break;
   //-------------------------Qty MGMT----------------------------------------------//
   case "qty_mgmt":
      include_once 'components/manager/modle/managerModule.php';
      getQtyMgmt();
      getQtyAudit(20);
      include_once 'components/manager/view/qty_mgmt.php';
      break;

   case "adjust_qty":
      include_once 'components/manager/modle/managerModule.php';
      if (adjustQty())
         header('Location: index.php?components=manager&action=qty_mgmt&item_id=' . $_GET['item_id'] . '&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=manager&action=qty_mgmt&item_id=' . $_GET['item_id'] . '&message=' . $message . '&re=fail');
      break;
   //-------------------------Quotation----------------------------------------------//
   case "set_district":
      include_once 'components/billing/modle/billingModule.php';
      setDistrict();
      header('Location: index.php?components=manager&action=quotation');
      break;

   case "quotation_approve":
      include_once 'components/manager/modle/managerModule.php';
      pendingQuot($sub_system);
      if (isMobile())
         include_once 'components/manager/view/m_quotation_app.php';
      else
         include_once 'components/manager/view/quotation_app.php';
      break;

   case "quotation":
      include_once 'components/billing/modle/billingModule.php';
      include_once 'components/supervisor/modle/supervisorModule.php';
      getDistrict();
      getQuotationItems();
      if (isset($_COOKIE['district'])) {
         getItems($item_filter, $sub_system, $systemid);
         getCust(1, '1');
         if (isset($_GET['cust'])) {
            if (validateQuotNo())
               header('Location: index.php?components=manager&action=new_quot&cust_id=' . $_GET['cust'] . '&validity=30');
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
         header('Location: index.php?components=manager&action=quotation&id=' . $quot_no . '&s=' . $salesman . '&cust=' . $cust);
      else
         header('Location: index.php?components=manager&action=quotation&message=' . $message . '&re=fail');
      break;

   case "apend_quot":
      include_once 'components/billing/modle/billingModule.php';
      include_once 'components/supervisor/modle/supervisorModule.php';
      if (apendQuot()) {
         header('Location: index.php?components=manager&action=quotation&id=' . $quot_no . '&s=' . $salesman . '&cust=' . $cust . '&message=' . $message . '&re=success');
      } else {
         header('Location: index.php?components=manager&action=quotation&id=' . $quot_no . '&s=' . $salesman . '&cust=' . $cust . '&message=' . $message . '&re=fail');
      }
      break;

   case "qo_item_gpdate":
      include_once 'components/supervisor/modle/supervisorModule.php';
      if (updateQuot()) {
         header('Location: index.php?components=manager&action=quotation&id=' . $quot_no . '&s=' . $salesman . '&cust=' . $cust . '&message=' . $message . '&re=success');
      } else {
         header('Location: index.php?components=manager&action=quotation&id=' . $quot_no . '&s=' . $salesman . '&cust=' . $cust . '&message=' . $message . '&re=fail');
      }
      break;

   case "qo_item_remove":
      include_once 'components/supervisor/modle/supervisorModule.php';
      if (removeQuot()) {
         header('Location: index.php?components=manager&action=quotation&id=' . $quot_no . '&s=' . $salesman . '&cust=' . $cust . '&message=' . $message . '&re=success');
      } else {
         header('Location: index.php?components=manager&action=quotation&id=' . $quot_no . '&s=' . $salesman . '&cust=' . $cust . '&message=' . $message . '&re=fail');
      }
      break;

   // added by nirmal 25_10_2023
   case "qo_item_update_unit_price":
      include_once 'components/supervisor/modle/supervisorModule.php';
      if (updateQuotItemUnitPrice()) {
         header('Location: index.php?components=manager&action=quotation&id=' . $quot_no . '&s=' . $salesman . '&cust=' . $cust . '&message=' . $message . '&re=success');
      } else {
         header('Location: index.php?components=manager&action=quotation&id=' . $quot_no . '&s=' . $salesman . '&cust=' . $cust . '&message=' . $message . '&re=fail');
      }
      break;

   // added by nirmal 25_10_2023
   case "qo_update_discount":
      include_once 'components/supervisor/modle/supervisorModule.php';
      if (updateQuotDiscount()) {
         header('Location: index.php?components=manager&action=quotation&id=' . $quot_no . '&s=' . $salesman . '&cust=' . $cust . '&message=' . $message . '&re=success');
      } else {
         header('Location: index.php?components=manager&action=quotation&id=' . $quot_no . '&s=' . $salesman . '&cust=' . $cust . '&message=' . $message . '&re=fail');
      }
      break;

   // added by nirmal 25_10_2023
   case "qo_remove_discount":
      include_once 'components/supervisor/modle/supervisorModule.php';
      if (removeQuotDiscount()) {
         header('Location: index.php?components=manager&action=quotation&id=' . $quot_no . '&s=' . $salesman . '&cust=' . $cust . '&message=' . $message . '&re=success');
      } else {
         header('Location: index.php?components=manager&action=quotation&id=' . $quot_no . '&s=' . $salesman . '&cust=' . $cust . '&message=' . $message . '&re=fail');
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
         header('Location: index.php?components=manager&action=set_quot_status&id=' . $quot_no . '&new_status=2');
      else
         header('Location: index.php?components=manager&action=qo_terms&id=' . $quot_no . '&message=' . $message . '&re=fail');
      break;

   case "set_quot_status":
      include_once 'components/supervisor/modle/supervisorModule.php';
      if (setQuotStatus($_GET['new_status']))
         header('Location: index.php?components=manager&action=qo_finish&id=' . $quot_no);
      else
         header('Location: index.php?components=manager&action=qo_finish&id=' . $quot_no . '&message=' . $message . '&re=fail');
      break;

   case "qo_revise":
      include_once 'components/supervisor/modle/supervisorModule.php';
      if (qoRevise())
         header('Location: index.php?components=manager&action=qo_finish&id=' . $quot_no);
      else
         header('Location: index.php?components=manager&action=qo_finish&id=' . $quot_no . '&message=' . $message . '&re=fail');
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

   case "update_qo_main_comment":
      include_once 'components/supervisor/modle/supervisorModule.php';
      if (updateQuotMainComment()) {
         header('Location: index.php?components=' . $components . '&action=qo_finish&id=' . $id . '&message=' . $message . '&re=success');
      } else {
         header('Location: index.php?components=' . $components . '&action=qo_finish&id=' . $id . '&message=' . $message . '&re=fail');
      }
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
         header('Location: index.php?components=manager&action=qo_finish&id=' . $_POST['search1']);
      else
         header('Location: index.php?components=manager&action=quotation&message=Invalid%20Quotation%20Number&re=fail');
      break;

   case "qo_add_image":
      include_once 'components/supervisor/modle/supervisorModule.php';
      if (qoAddImage())
         header('Location: index.php?components=manager&action=qo_finish&id=' . $_GET['id'] . '&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=manager&action=qo_finish&id=' . $_GET['id'] . '&message=' . $message . '&re=fail');
      break;

   case "qo_delete_image":
      include_once 'components/supervisor/modle/supervisorModule.php';
      if (qoDeleteImage())
         header('Location: index.php?components=manager&action=qo_finish&id=' . $_GET['id'] . '&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=manager&action=qo_finish&id=' . $_GET['id'] . '&message=' . $message . '&re=fail');
      break;

   case "qo_img_height":
      include_once 'components/supervisor/modle/supervisorModule.php';
      if (qoImgHeight())
         header('Location: index.php?components=manager&action=qo_finish&id=' . $_GET['id'] . '&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=manager&action=qo_finish&id=' . $_GET['id'] . '&message=' . $message . '&re=fail');
      break;

   case "qo_add_note":
      include_once 'components/supervisor/modle/supervisorModule.php';
      if (qoAddNote())
         header('Location: index.php?components=manager&action=qo_finish&id=' . $quot_no);
      else
         header('Location: index.php?components=manager&action=qo_finish&id=' . $quot_no . '&message=' . $message . '&re=fail');
      break;

   case "qo_update_note":
      include_once 'components/supervisor/modle/supervisorModule.php';
      if (qoUpdateNote())
         header('Location: index.php?components=manager&action=qo_finish&id=' . $quot_no . '&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=manager&action=qo_finish&id=' . $quot_no . '&message=' . $message . '&re=fail');
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
         header('Location: index.php?components=manager&action=qo_finish&id=' . $quot_no . '&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=manager&action=qo_finish&id=' . $quot_no . '&message=' . $message . '&re=fail');
      break;

   case "quotation_sent_with_tax_format":
      include_once 'components/supervisor/modle/supervisorModule.php';
      if (qoUpdateTaxFormat())
         header('Location: index.php?components=manager&action=qo_finish&id=' . $quot_no . '&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=manager&action=qo_finish&id=' . $quot_no . '&message=' . $message . '&re=fail');
      break;

   //--------------------------Return MGMT---------------------------//
   case "list_return":
      include_once 'components/orderProcess/modle/orderProcessModule.php';
      getReturn();
      if (isMobile())
         include_once 'components/orderProcess/view/m_return.php';
      else
         include_once 'components/orderProcess/view/return.php';
      break;

   case "list_unic_return":
      include_once 'components/orderProcess/modle/orderProcessModule.php';
      getUnicReturn();
      if (isMobile())
         include_once 'components/orderProcess/view/m_unic_return.php';
      else
         include_once 'components/orderProcess/view/unic_return.php';
      break;

   case "process_return":
      include_once 'components/orderProcess/modle/orderProcessModule.php';
      if (processReturn())
         header('Location: index.php?components=manager&action=list_return&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=manager&action=list_return&message=' . $message . '&re=fail');
      break;

   case "move_unic_inv":
      include_once 'components/orderProcess/modle/orderProcessModule.php';
      $debug_id = debugStart(0, 0);
      if (moveUnicInv()) {
         debugEnd($debug_id, 'success');
         header('Location: index.php?components=manager&action=list_unic_return&item=' . $item . '&message=' . $message . '&re=success');
      } else {
         debugEnd($debug_id, 'fail');
         header('Location: index.php?components=manager&action=list_unic_return&item=' . $item . '&message=' . $message . '&re=fail');
      }
      break;

   case "move_unic_dis":
      include_once 'components/orderProcess/modle/orderProcessModule.php';
      if (moveUnicDis())
         header('Location: index.php?components=manager&action=list_unic_return&item=' . $item . '&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=manager&action=list_unic_return&item=' . $item . '&message=' . $message . '&re=fail');
      break;

   //-----------------------------MAP------------------------------------//
   case "show_map":
      include_once 'components/manager/modle/managerModule.php';
      decodeMapData();
      if (isMobile())
         include_once 'components/manager/view/m_mapview.php';
      else
         include_once 'components/manager/view/mapview.php';
      break;
   //-----------------------------Hire Purchase------------------------------------//
   case "hp_active_list":
      include_once 'components/hirePurchase/modle/hpModule.php';
      myActiveInvoices('all');
      if (isset($_GET['mismatch']))
         paymentDateIssueList($_COOKIE['user_id']);
      if (isMobile())
         include_once 'components/hirePurchase/view/m_home.php';
      else
         include_once 'components/hirePurchase/view/home.php';
      break;

   case "hp_deductions":
      include_once 'components/manager/modle/managerModule.php';
      getExceededPendingPayments();
      include_once 'components/manager/view/hp_deductions.php';
      break;

   case "add_deduction":
      include_once 'components/manager/modle/managerModule.php';
      print addDeduction();
      break;

   case "remove_deduction":
      include_once 'components/manager/modle/managerModule.php';
      print removeDeduction();
      break;

   case "hp_commission_new":
      include_once 'components/manager/modle/managerModule.php';
      getHPCommission();
      if (isMobile())
         include_once 'components/manager/view/m_hp_commission_new.php';
      else
         include_once 'components/manager/view/hp_commission_new.php';
      break;

   case "hp_generate_commission":
      include_once 'components/manager/modle/managerModule.php';
      if (hpGenerateCommission($sub_system))
         header('Location: index.php?components=manager&action=hp_commission_new&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=manager&action=hp_commission_new&message=' . $message . '&re=fail');
      break;

   case "hp_commission_old":
      include_once 'components/manager/modle/managerModule.php';
      getHPCommissionList();
      if (isMobile())
         include_once 'components/manager/view/m_hp_commission_list.php';
      else
         include_once 'components/manager/view/hp_commission_list.php';
      break;

   case "hp_commission_one":
      include_once 'components/manager/modle/managerModule.php';
      getHPCommissionOne();
      if (isMobile())
         include_once 'components/manager/view/m_hp_commission_one.php';
      else
         include_once 'components/manager/view/hp_commission_one.php';
      break;

   case "hp_commission_one_user":
      include_once 'components/manager/modle/managerModule.php';
      getHPCommissionOne();
      if (isMobile())
         include_once 'components/manager/view/m_hp_commission_one.php';
      else
         include_once 'components/manager/view/hp_commission_one_user.php';
      break;

   case "hp_commission_delete":
      include_once 'components/manager/modle/managerModule.php';
      if (hpDeleteCommission(0))
         header('Location: index.php?components=manager&action=hp_commission_old&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=manager&action=hp_commission_one&id=' . $_GET['id'] . '&message=' . $message . '&re=fail');
      break;
   //-----------------------------Warranty------------------------------------//
   case "warranty":
      include_once 'components/manager/modle/managerModule.php';
      getStore($sub_system);
      getWarranty();
      include_once 'components/manager/view/warranty.php';
      break;

   // added by nirmal 26_07_2023
   case "tax_report":
      include_once 'components/manager/modle/managerModule.php';
      getTaxReport($sub_system);
      getFilter($sub_system);
      getCustGroups($sub_system);
      if (isMobile())
         include_once 'components/manager/view/m_tax_report.php';
      else
         include_once 'components/manager/view/tax_report.php';
      break;

   // updated by nirmal 18_09_2023
   case "tax_report_detail":
      include_once 'components/manager/modle/managerModule.php';
      getDetailTaxReport($sub_system);
      getFilter($sub_system);
      getCustGroups($sub_system);
      if (isMobile())
         include_once 'components/manager/view/m_tax_report_detail.php';
      else
         include_once 'components/manager/view/tax_report_detail.php';
      break;

   //-----------------------------Stores Settings------------------------------------//

   // added by nirmal 08_07_2024
   case "stores_settings":
      include_once 'components/manager/modle/managerModule.php';
      getStoreSettings($sub_system);
      include_once 'components/manager/view/stores_settings.php';
      break;

   case "update_store_billing_p_u_setting":
      include_once 'components/manager/modle/managerModule.php';
      if (updateStoreBillingPU($sub_system))
         header('Location: index.php?components=manager&action=stores_settings&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=manager&action=stores_settings&message=' . $message . '&re=fail');
      break;

   //-----------------------------Payment Deposits------------------------------------//
   case "pending_payment_cash_deposits":
      include_once 'components/manager/modle/managerModule.php';
      getPendingCashDeposits($sub_system);
      getBankAccounts();
      getSalesman($sub_system);
      include_once 'components/manager/view/pending_payment_cash_deposits.php';
      break;

   case "change_payment_deposit_status_ajax":
      include_once 'components/manager/modle/managerModule.php';
      print changePaymentDepositStatusAjax($sub_system);
      break;

   case "pending_payment_bank_deposits":
      include_once 'components/manager/modle/managerModule.php';
      getPendingBankDeposits($sub_system);
      getBankAccounts();
      getSalesman($sub_system);
      include_once 'components/manager/view/pending_payment_bank_deposits.php';
      break;

   case "cash_transfer_deposits_report":
      include_once 'components/manager/modle/managerModule.php';
      getCashDepositsReport($sub_system);
      getBankAccounts();
      getSalesman5($sub_system);
      getSalesman($sub_system);
      include_once 'components/manager/view/cash_transfer_deposits_report.php';
      break;

   case "bank_transfer_deposits_report":
      include_once 'components/manager/modle/managerModule.php';
      getBankDepositsReport($sub_system);
      getBankAccounts();
      getSalesman($sub_system);
      getSalesman5($sub_system);
      include_once 'components/manager/view/bank_transfer_deposits_report.php';
      break;

   case "update_checked_status_ajax":
      include_once 'components/manager/modle/managerModule.php';
      print updatePaymentAsChecked();
      break;

   case "cash_on_hand_report":
      include_once 'components/manager/modle/managerModule.php';
      getCashOnHandReport($sub_system);
      if (isMobile()) {
         include_once 'components/manager/view/m_cash_on_hand_report.php';
      } else {
         include_once 'components/manager/view/cash_on_hand_report.php';
      }
      break;

   // NEW
   case "pending_cheque_transfers":
      include_once 'components/manager/modle/managerModule.php';
      getSalesman($sub_system);
      getSalesman5($sub_system);
      getPendingChequeTransfers($sub_system);
      include_once 'components/manager/view/pending_cheque_transfers.php';
      break;

   case "add_cheque_transfer_ajax":
      include_once 'components/manager/modle/managerModule.php';
      print addChequeTransferAjax($sub_system);
      break;

   case "cheque_transfer":
      include_once 'components/manager/modle/managerModule.php';
      getChequeApprovedByUser($sub_system);
      getSalesman5($sub_system);
      include_once 'components/manager/view/cheque_transfer.php';
      break;

   case "add_cheque_transfer_to_user_ajax":
      include_once 'components/manager/modle/managerModule.php';
      print addChequeTransferToUserAjax($sub_system);
      break;

   case "approved_cheque_transfers":
      include_once 'components/manager/modle/managerModule.php';
      getApprovedChequeTransfers($sub_system);
      getSalesman5($sub_system);
      include_once 'components/manager/view/approved_cheque_transfers.php';
      break;

   case "change_cheque_transfer_status_ajax":
      include_once 'components/manager/modle/managerModule.php';
      print changeChequeTransferStatusAjax($sub_system);
      break;

   case "cheque_trans_summery":
      include_once 'components/manager/modle/managerModule.php';
      getSalesman($sub_system);
      getChequeTransSummery($sub_system);
      include_once 'components/manager/view/cheque_transfers_summery.php';
      break;

   case "cheque_transfer_status_summery":
      include_once 'components/manager/modle/managerModule.php';
      getChequeTransfersStatusSummery($sub_system);
      include_once 'components/manager/view/cheque_transfer_status_summery.php';
      break;

   case "cheque_on_hand":
      include_once 'components/manager/modle/managerModule.php';
      getChequeOnHandReport($sub_system);
      getSalesmanChequeOnHandReport($sub_system);
      getSalesman($sub_system);
      include_once 'components/manager/view/cheque_on_hand_report.php';
      break;

   case "trans_return_cheque":
      include_once 'components/manager/modle/managerModule.php';
      getTransReturnMarkedCheques($sub_system);
      getSalesman($sub_system);
      include_once 'components/manager/view/trans_return_cheques.php';
      break;

   case "add_cheque_transfer_return_to_user_ajax":
      include_once 'components/manager/modle/managerModule.php';
      print addChequeTransferReturnToUserAjax($sub_system);
      break;

   ///
   case "return_availability":
      include_once 'components/manager/modle/managerModule.php';
      getCust($sub_system, '0,1,2');
      getReturnAvailability($sub_system);
      if (isMobile())
         include_once 'components/manager/view/m_return_availability.php';
      else
         include_once 'components/manager/view/return_availability.php';
      break;

   default:
      print '<p><strong>Bad Request</strong></p>';
      break;
}
