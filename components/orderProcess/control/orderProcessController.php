<?php
if (passwordExpire())
   header('Location: index.php?components=authenticate&action=change_pw&message=Your%20Password%20Has%20Expired.%20Please%20Change%20it%20NOW&re=fail');

switch ($_REQUEST['action']) {
   case "list_custodr":
      include_once 'components/orderProcess/modle/orderProcessModule.php';
      getOrder('cust_odr');
      if (isMobile())
         include_once 'components/orderProcess/view/m_home.php';
      else
         include_once 'components/orderProcess/view/home.php';
      break;

   case "list_pending":
      include_once 'components/orderProcess/modle/orderProcessModule.php';
      getOrder('pending');
      if (isMobile())
         include_once 'components/orderProcess/view/m_home.php';
      else
         include_once 'components/orderProcess/view/home.php';
      break;

   case "list_my":
      include_once 'components/orderProcess/modle/orderProcessModule.php';
      getOrder('picked');
      if (isMobile())
         include_once 'components/orderProcess/view/m_home.php';
      else
         include_once 'components/orderProcess/view/home.php';
      break;

   case "list_my_cross_check":
      include_once 'components/orderProcess/modle/orderProcessModule.php';
      getOrder('list_my_cross_check');
      if (isMobile())
         include_once 'components/orderProcess/view/m_home.php';
      else
         include_once 'components/orderProcess/view/home.php';
      break;

   case "list_cross_check":
      include_once 'components/orderProcess/modle/orderProcessModule.php';
      getOrder('list_cross_check');
      if (isMobile())
         include_once 'components/orderProcess/view/m_home.php';
      else
         include_once 'components/orderProcess/view/home.php';
      break;

   case "list_packed":
      include_once 'components/orderProcess/modle/orderProcessModule.php';
      getOrder('packed');
      if (isMobile())
         include_once 'components/orderProcess/view/m_home.php';
      else
         include_once 'components/orderProcess/view/home.php';
      break;

   case "list_shipped":
      include_once 'components/orderProcess/modle/orderProcessModule.php';
      getOrder('shipped');
      if (isMobile())
         include_once 'components/orderProcess/view/m_home.php';
      else
         include_once 'components/orderProcess/view/home.php';
      break;

   case "list_delivered":
      include_once 'components/orderProcess/modle/orderProcessModule.php';
      getOrder('delivered');
      if (isMobile())
         include_once 'components/orderProcess/view/m_home.php';
      else
         include_once 'components/orderProcess/view/home.php';
      break;

   case "list_one":
      include_once 'components/orderProcess/modle/orderProcessModule.php';
      getOneOrder();
      if (isMobile())
         include_once 'components/orderProcess/view/m_one.php';
      else
         include_once 'components/orderProcess/view/one.php';
      break;

   case "list_one_custodr":
      include_once 'components/orderProcess/modle/orderProcessModule.php';
      getOneOrder();
      getUnpackedReturn($cu_id);
      getPackedReturn();
      getCancelRerunCRBalance();
      generateAddressTag();
      $searchableItemsJSON = getAllCustomerItems();
      if (isMobile()) {
         if (isOdrCrossCheckActive()) {
            include_once 'components/orderProcess/view/m_one_custodr2.php';
         } else {
            include_once 'components/orderProcess/view/m_one_custodr.php';
         }
      } else
         if (isOdrCrossCheckActive()) {
            include_once 'components/orderProcess/view/one_custodr2.php';
         } else {
            include_once 'components/orderProcess/view/one_custodr.php';
         }
      break;

   case "search_order_items":
      include_once 'components/orderProcess/modle/orderProcessModule.php';
      print searchOrderItems();
      break;

   case "show_one_return_item":
      include_once 'components/orderProcess/modle/orderProcessModule.php';
      getOneReturnItem();
      if (isMobile())
         include_once 'components/orderProcess/view/m_one_rtn_itm.php';
      else
         include_once 'components/orderProcess/view/one_rtn_itm.php';
      break;

   case "remove_one_return_item":
      include_once 'components/orderProcess/modle/orderProcessModule.php';
      if (removeOneRetunItem()) {
         header('Location: index.php?components=order_process&action=list_one_custodr&id=' . $_GET['odr_id'] . '&message=' . $message . '&re=success');
      } else {
         header('Location: index.php?components=order_process&action=show_one_return_item&odr_id=' . $_GET['odr_id'] . '&rtn_id=' . $_GET['rtn_id'] . '&message=' . $message . '&re=fail');
      }
      break;

   case "return_packed":
      include_once 'components/orderProcess/modle/orderProcessModule.php';
      print returnPacked();
      break;

   case "remove_return_packed":
      include_once 'components/orderProcess/modle/orderProcessModule.php';
      print removeReturnPacked();
      break;

   case "setdistrict_custodr":
      include_once 'components/checkAvailability/modle/availabilityModule.php';
      setDistrict();
      header('Location: index.php?components=order_process&action=showadd_custodr&id=' . $_GET['bill_no'] . '&return=' . $_GET['return']);
      break;

   case "showadd_custodr":
      include_once 'components/orderProcess/modle/orderProcessModule.php';
      getCustOdrItem();
      getItemsOdr($item_filter, $sub_system, $systemid);
      if (isMobile())
         include_once 'components/orderProcess/view/m_edit_custodr.php';
      else
         include_once 'components/orderProcess/view/edit_custodr.php';
      break;

   case "bill_item_remove":
      include_once 'components/orderProcess/modle/orderProcessModule.php';
      if (removeBillitemOdr()) {
         header('Location: index.php?components=order_process&action=list_one_custodr&id=' . $invoice_no . '&message=' . $message . '&re=success');
      } else {
         header('Location: index.php?components=order_process&action=list_one_custodr&id=' . $invoice_no . '&message=' . $message . '&re=fail');
      }
      break;

   case "bill_item_gpdate":
      include_once 'components/orderProcess/modle/orderProcessModule.php';
      $debug_id = debugStart(0, 0);
      if (updateBillitemOdr()) {
         calculateTotalOdr();
         debugEnd($debug_id, 'success');
         header('Location: index.php?components=order_process&action=list_one_custodr&id=' . $invoice_no . '&message=' . $message . '&re=success');
      } else {
         debugEnd($debug_id, 'fail');
         header('Location: index.php?components=order_process&action=list_one_custodr&id=' . $invoice_no . '&message=' . $message . '&re=fail');
      }
      break;

   case "apend_bill":
      include_once 'components/orderProcess/modle/orderProcessModule.php';
      if (apendBillOdr(2, $systemid, 0, 0)) {
         header('Location: index.php?components=order_process&action=list_one_custodr&id=' . $invoice_no . '&message=' . $message . '&re=success');
      } else {
         header('Location: index.php?components=order_process&action=list_one_custodr&id=' . $invoice_no . '&message=' . $message . '&re=fail');
      }
      break;

   case "set_picked":
      include_once 'components/orderProcess/modle/orderProcessModule.php';
      if (setStatus('picked')) {
         if ($type == 4 || $type == 5)
            header('Location: index.php?components=order_process&action=list_one_custodr&id=' . $invoice_no . '&message=' . $message . '&re=success');
         else
            header('Location: index.php?components=order_process&action=list_one&id=' . $invoice_no . '&message=' . $message . '&re=success');
      } else {
         header('Location: index.php?components=order_process&action=list_pending&message=' . $message . '&re=fail');
      }
      break;

   case "move_to_cross_check":
      include_once 'components/orderProcess/modle/orderProcessModule.php';
      if (setStatus('move_to_cross_check')) {
         if ($type == 4 || $type == 5)
            header('Location: index.php?components=order_process&action=list_custodr&message=' . $message . '&re=success');
         else
            header('Location: index.php?components=order_process&action=list_one&id=' . $invoice_no . '&message=' . $message . '&re=success');
      } else {
         header('Location: index.php?components=order_process&action=list_pending&message=' . $message . '&re=fail');
      }
      break;

   case "set_cross_check_start":
      include_once 'components/orderProcess/modle/orderProcessModule.php';
      if (setStatus('set_cross_check_start')) {
         if ($type == 4 || $type == 5)
            header('Location: index.php?components=order_process&action=list_one_custodr&id=' . $invoice_no . '&message=' . $message . '&re=success');
         else
            header('Location: index.php?components=order_process&action=list_one&id=' . $invoice_no . '&message=' . $message . '&re=success');
      } else {
         header('Location: index.php?components=order_process&action=list_pending&message=' . $message . '&re=fail');
      }
      break;

   case "set_packed":
      include_once 'components/orderProcess/modle/orderProcessModule.php';
      if (setStatus('packed')) {
         if ($type == 4 || $type == 5)
            header('Location: index.php?components=order_process&action=list_one&id=' . $_GET['id'] . '&link=yes&message=' . $message . '&re=success');
         else
            header('Location: index.php?components=order_process&action=list_pending&message=' . $message . '&re=success');
      } else {
         header('Location: index.php?components=order_process&action=list_one_custodr&id=' . $_GET['id'] . '&message=' . $message . '&re=fail');
      }
      break;

   case "set_shipped":
      include_once 'components/orderProcess/modle/orderProcessModule.php';
      if (setStatus('shipped'))
         header('Location: index.php?components=order_process&action=list_packed&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=order_process&action=list_packed&message=' . $message . '&re=fail');
      break;

   // updated by nirmal 21_12_2023
   case "set_delivered":
      include_once 'components/orderProcess/modle/orderProcessModule.php';
      if (setStatus('delivered'))
         if ($systemid == 13 && $sub_system == 1)
            header('Location: index.php?components=order_process&action=list_delivered&message=' . $message . '&re=success');
         else
            header('Location: index.php?components=order_process&action=list_shipped&message=' . $message . '&re=success');
      else
         if ($systemid == 13 && $sub_system == 1)
            header('Location: index.php?components=order_process&action=list_delivered&message=' . $message . '&re=fail');
         else
            header('Location: index.php?components=order_process&action=list_shipped&message=' . $message . '&re=fail');
      break;

   // added by nirmal 07_06_2023
   case "ajax_set_delivered":
      include_once 'components/orderProcess/modle/orderProcessModule.php';
      print ajaxSetStatus();
      break;

   case "set_unassign":
      include_once 'components/orderProcess/modle/orderProcessModule.php';
      if (orderUnassign())
         header('Location: index.php?components=order_process&action=' . $_GET['next_action'] . '&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=order_process&action=list_one&id=' . $_GET['id'] . '&message=' . $message . '&re=fail');
      break;

   case "tag_list":
      include_once 'components/orderProcess/modle/orderProcessModule.php';
      generateTag();
      include_once 'components/orderProcess/view/tag_list.php';
      break;

   case "set_orderby":
      include_once 'components/orderProcess/modle/orderProcessModule.php';
      if (setOrderBy())
         header('Location: index.php?components=order_process&action=' . $_GET['action2']);
      else
         header('Location: index.php?components=order_process&action=' . $_GET['action2'] . '&message=failed to order&re=fail');
      break;
   //--------------------------Final Check---------------------------//
   case "show_check":
      include_once 'components/orderProcess/view/show_check.php';
      break;

   case "search_order":
      include_once 'components/orderProcess/modle/orderProcessModule.php';
      if (searchOrder())
         header('Location: index.php?components=order_process&action=one_odr_check&id=' . $order_no . '&invoice_no=' . $order_no);
      else
         header('Location: index.php?components=order_process&action=show_check&message=' . $message . '&re=fail');
      break;

   case "one_odr_check":
      include_once 'components/orderProcess/modle/orderProcessModule.php';
      getOneOrder();
      getPackedReturn();
      include_once 'components/orderProcess/view/show_checkorder.php';
      break;

   case "apend_courier":
      include_once 'components/orderProcess/modle/orderProcessModule.php';
      if (apendCourier())
         header('Location: index.php?components=order_process&action=show_check&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=order_process&action=one_odr_check&id=' . $order_no . '&invoice_no=' . $order_no . '&message=' . $message . '&re=fail');
      break;

   case "report_trackingid":
      include_once 'components/orderProcess/modle/orderProcessModule.php';
      generateTrackingID();
      include_once 'components/orderProcess/view/report_trackingid.php';
      break;

   //--------------------------Return---------------------------//

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
         header('Location: index.php?components=order_process&action=list_return&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=order_process&action=list_return&message=' . $message . '&re=fail');
      break;

   case "move_unic_inv":
      include_once 'components/orderProcess/modle/orderProcessModule.php';
      $debug_id = debugStart(0, 0);
      if (moveUnicInv()) {
         debugEnd($debug_id, 'success');
         header('Location: index.php?components=order_process&action=list_unic_return&item=' . $item . '&message=' . $message . '&re=success');
      } else {
         debugEnd($debug_id, 'fail');
         header('Location: index.php?components=order_process&action=list_unic_return&item=' . $item . '&message=' . $message . '&re=fail');
      }
      break;

   case "move_unic_dis":
      include_once 'components/orderProcess/modle/orderProcessModule.php';
      if (moveUnicDis())
         header('Location: index.php?components=order_process&action=list_unic_return&item=' . $item . '&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=order_process&action=list_unic_return&item=' . $item . '&message=' . $message . '&re=fail');
      break;
   //---------------------------------------------------------------------------------------
   case "move_cust_odr":
      include_once 'components/orderProcess/modle/orderProcessModule.php';
      if (moveCustOdr())
         header('Location: index.php?components=order_process&action=list_one&id=' . $id . '&message=' . $message . '&re=success');
      else
         header('Location: index.php?components=order_process&action=list_one&id=' . $id . '&message=' . $message . '&re=fail');
      break;


   case "report_commision":
      include_once 'components/orderProcess/modle/orderProcessModule.php';
      getStore();
      getCommisionReport();
      if (isMobile())
         include_once 'components/orderProcess/view/m_commision_report.php';
      else
         include_once 'components/orderProcess/view/commision_report.php';
      break;

   case "report_tracking":
      include_once 'components/orderProcess/modle/orderProcessModule.php';
      getTrackingReport();
      include_once 'components/orderProcess/view/tracking_report.php';
      break;
   //--------------------------Ring Alert---------------------------//
   case "get_alert":
      include_once 'components/orderProcess/modle/orderProcessModule.php';
      print ringAlert();
      break;

   case "ring_alert":
      include_once 'components/orderProcess/view/ring_alert.php';
      break;

   default:
      print '<p><srtong>Bad Request</strong></p>';
      break;
}
?>