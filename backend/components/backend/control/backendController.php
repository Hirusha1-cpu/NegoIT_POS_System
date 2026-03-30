<?php
switch ($_REQUEST['action']) {
   case "lock":
      include_once 'components/backend/modle/backendModule.php';
      getUnlockBills();
      getOneLockSt();
      include_once 'components/backend/view/m_lock.php';
      break;

   case "changelock":
      include_once 'components/backend/modle/backendModule.php';
      if (changeLock())
         header('Location: index.php?components=backend&action=lock&lockinvid=' . $bill_id . '&message=' . urlencode($message) . '&re=success');
      else
         header('Location: index.php?components=backend&action=lock&lockinvid=' . $bill_id . '&message=' . urlencode($message) . '&re=fail');
      break;

   case "delete":
      include_once 'components/backend/modle/backendModule.php';
      if ($_GET['type'] == 'bill') {
         billStatus();
         generateInvoice();
      }
      if ($_GET['type'] == 'pay') {
         payStatus();
         generatePayment();
      }
      if ($_GET['type'] == 'commission') {
         comStatus();
      }
      include_once 'components/backend/view/m_delete.php';
      break;

   case "delete_search":
      include_once 'components/backend/modle/backendModule.php';
      if (searchDelete())
         header('Location: index.php?components=backend&action=delete&type=' . $type . '&id=' . $id);
      else
         header('Location: index.php?components=backend&action=delete&type&message=' . urlencode($message) . '&re=fail');
      break;

   case "delete_bill":
      include_once 'template/debug.php';
      include_once '../components/billing/modle/billingModule.php';
      if (deleteBill(2, 1))
         header('Location: index.php?components=backend&action=delete&type=bill&id=' . $_GET['id'] . '&message=' . urlencode($message) . '&re=success');
      else
         header('Location: index.php?components=backend&action=delete&type&message=' . urlencode($message) . '&re=fail');
      break;

   case "delete_bill2":
      include_once 'template/debug.php';
      include_once '../components/bill2/modle/bill2Module.php';
      if (deleteInvoice(2, 1))
         header('Location: index.php?components=backend&action=delete&type=bill&id=' . $_GET['id'] . '&message=' . urlencode($message) . '&re=success');
      else
         header('Location: index.php?components=backend&action=delete&type&message=' . urlencode($message) . '&re=fail');
      break;

   case "delete_pay":
      include_once 'template/debug.php';
      include_once '../components/billing/modle/billingModule.php';
      if (deletePayment(2, 1))
         header('Location: index.php?components=backend&action=delete&type=pay&id=' . $_GET['id'] . '&message=' . urlencode($message) . '&re=success');
      else
         header('Location: index.php?components=backend&action=delete&type&message=' . urlencode($message) . '&re=fail');
      break;

   case "delete_commission_report":
      include_once '../components/manager/modle/managerModule.php';
      if (hpDeleteCommission(1))
         header('Location: index.php?components=backend&action=delete&type=commission&id=' . $_GET['id'] . '&message=' . urlencode($message) . '&re=success');
      else
         header('Location: index.php?components=backend&action=delete&type&message=' . urlencode($message) . '&re=fail');
      break;

   case "change_cust":
      include_once 'components/backend/modle/backendModule.php';
      getInvoice();
      getPayment();
      getCustomers();
      include_once 'components/backend/view/m_change_cust.php';
      break;

   case "change_cust_invoice":
      include_once 'components/backend/modle/backendModule.php';
      if (changeInvoiceCust()) {
         header('Location: index.php?components=backend&action=change_cust&message=' . urlencode($message) . '&re=success');
      } else {
         header('Location: index.php?components=backend&action=change_cust&message=' . urlencode($message) . '&re=fail');
      }
      break;

   case "change_cust_payment":
      include_once 'components/backend/modle/backendModule.php';
      if (changePaymentCust()) {
         header('Location: index.php?components=backend&action=change_cust&message=' . urlencode($message) . '&re=success');
      } else {
         header('Location: index.php?components=backend&action=change_cust&message=' . urlencode($message) . '&re=fail');
      }
      break;

   case "inv_mgmt":
      include_once 'components/backend/modle/backendModule.php';
      searchInv();
      include_once 'components/backend/view/m_invmgmt.php';
      break;

   case "set_inv_main":
      include_once 'components/backend/modle/backendModule.php';
      if (setInvMain())
         header('Location: index.php?components=backend&action=inv_mgmt&bill_no=' . $bill_no . '&message=' . urlencode($message) . '&re=success');
      else
         header('Location: index.php?components=backend&action=inv_mgmt&bill_no=' . $bill_no . '&message=' . urlencode($message) . '&re=fail');
      break;

   case "clear_cat":
      include_once 'components/backend/modle/backendModule.php';
      getCategory();
      getStore();
      getJobId();
      include_once 'components/backend/view/m_clearCat.php';
      break;

   case "set_clear":
      include_once 'components/backend/modle/backendModule.php';
      if (setClear())
         header('Location: index.php?components=backend&action=clear_cat&message=' . urlencode($message) . '&re=success');
      else
         header('Location: index.php?components=backend&action=clear_cat&message=' . urlencode($message) . '&re=fail');
      break;

   case "restore_clear_cat":
      include_once 'components/backend/modle/backendModule.php';
      if (restoreClearCat())
         header('Location: index.php?components=backend&action=clear_cat&message=' . urlencode($message) . '&re=success');
      else
         header('Location: index.php?components=backend&action=clear_cat&message=' . urlencode($message) . '&re=fail');
      break;

   case "inv_order":
      include_once 'components/backend/view/m_order.php';
      break;

   case "inv_setorder":
      include_once 'components/backend/modle/backendModule.php';
      invSetOrder();
      break;
   //---------------------------debug--------------------------------//

   case "debug":
      include_once 'components/backend/modle/backendModule.php';
      getDebug();
      include_once 'components/backend/view/m_debug.php';
      break;

   case "debug_ack":
      include_once 'components/backend/modle/backendModule.php';
      if (debugAck())
         header('Location: index.php?components=backend&action=debug&message=' . urlencode($message) . '&re=success');
      else
         header('Location: index.php?components=backend&action=debug&message=' . urlencode($message) . '&re=fail');
      break;

   //---------------------------mismatch--------------------------------//
   case "mismatch":
      include_once 'components/backend/modle/backendModule.php';
      getInvMismatch();
      include_once 'components/backend/view/m_mismatch.php';
      break;

   case "validate_error":
      include_once 'components/backend/modle/backendModule.php';
      print validateError();
      break;

   case "mismatch_one":
      include_once 'components/backend/modle/backendModule.php';
      getOneMismatch();
      include_once 'components/backend/view/m_mismatch_one.php';
      break;

   case "mismatch_up":
      include_once 'components/backend/modle/backendModule.php';
      if (updateItqQty(+1))
         header('Location: index.php?components=backend&action=mismatch&list=err&message=' . urlencode($message) . '&re=success');
      else
         header('Location: index.php?components=backend&action=mismatch&list=err&message=' . urlencode($message) . '&re=fail');
      break;

   case "mismatch_down":
      include_once 'components/backend/modle/backendModule.php';
      if (updateItqQty(-1))
         header('Location: index.php?components=backend&action=mismatch&list=err&message=' . urlencode($message) . '&re=success');
      else
         header('Location: index.php?components=backend&action=mismatch&list=err&message=' . urlencode($message) . '&re=fail');
      break;

   //---------------------------subscription--------------------------------//
   case "show_sub":
      include_once 'components/backend/modle/backendModule.php';
      getSubscription();
      include_once 'components/backend/view/m_subscription.php';
      break;

   case "sub_up":
      include_once 'components/backend/modle/backendModule.php';
      if (incrementSub(1))
         header('Location: index.php?components=backend&action=show_sub&message=' . urlencode($message) . '&re=success');
      else
         header('Location: index.php?components=backend&action=show_sub&message=' . urlencode($message) . '&re=fail');
      break;

   case "sub_down":
      include_once 'components/backend/modle/backendModule.php';
      if (incrementSub(-1))
         header('Location: index.php?components=backend&action=show_sub&message=' . urlencode($message) . '&re=success');
      else
         header('Location: index.php?components=backend&action=show_sub&message=' . urlencode($message) . '&re=fail');
      break;

   //---------------------------stores--------------------------------//
   case "stores":
      include_once 'components/backend/modle/backendModule.php';
      getStores();
      include_once 'components/backend/view/m_stores.php';
      break;

   case "deactive_store":
      include_once 'components/backend/modle/backendModule.php';
      if (updateStoreStatus()) {
         header('Location: index.php?components=backend&action=stores&message=' . urlencode($message) . '&re=success');
      } else {
         header('Location: index.php?components=backend&action=stores&message=' . urlencode($message) . '&re=fail');
      }
      include_once 'components/backend/view/m_stores.php';
      break;

   case "active_store":
      include_once 'components/backend/modle/backendModule.php';
      if (updateStoreStatus()) {
         header('Location: index.php?components=backend&action=stores&message=' . urlencode($message) . '&re=success');
      } else {
         header('Location: index.php?components=backend&action=stores&message=' . urlencode($message) . '&re=fail');
      }
      include_once 'components/backend/view/m_stores.php';
      break;

   case "show_add_store":
      include_once 'components/backend/modle/backendModule.php';
      getDistricts();
      getSubSystems();
      include_once 'components/backend/view/m_manage_store.php';
      break;

   case "add_store":
      include_once 'components/backend/modle/backendModule.php';
      if (addStore()) {
         header('Location: index.php?components=backend&action=stores&message=' . urlencode($message) . '&re=success');
      } else {
         header('Location: index.php?components=backend&action=show_add_store&message=' . urlencode($message) . '&re=fail');
      }
      break;

   case "edit_store":
      include_once 'components/backend/modle/backendModule.php';
      getOneStore();
      getDistricts();
      getSubSystems();
      include_once 'components/backend/view/m_manage_store.php';
      break;

   case "update_store":
      include_once 'components/backend/modle/backendModule.php';
      if (updateStore()) {
         header('Location: index.php?components=backend&action=stores&message=' . urlencode($message) . '&re=success');
      } else {
         header('Location: index.php?components=backend&action=edit_store&store_id=' . $store_id . '&message=' . urlencode($message) . '&re=fail');
      }
      break;

   //---------------------------shipment delete--------------------------------//
   case "show_last_shipment":
      include_once 'components/backend/modle/backendModule.php';
      getLastShipment();
      include_once 'components/backend/view/m_last_shipment.php';
      break;

   //---------------------------Payments MGMT--------------------------------//
   // added by nirmal 15_03_2024
   case "payment_mgmt":
      include_once 'components/backend/modle/backendModule.php';
      searchPayment();
      getBanks();
      getSalesman();
      getStore();
      include_once 'components/backend/view/m_payment_mgmt.php';
      break;

   case "update_payment":
      include_once 'components/backend/modle/backendModule.php';
      if (updatePayment()) {
         header('Location: index.php?components=backend&action=payment_mgmt&payment_no=' . $payment_id . '&type=' . $type . '&message=' . urlencode($message) . '&re=success');
      } else {
         header('Location: index.php?components=backend&action=payment_mgmt&payment_no=' . $payment_id . '&type=' . $type . '&message=' . urlencode($message) . '&re=fail');
      }
      break;

   //---------------------------Return Items MGMT--------------------------------//
   // added by nirmal 09_04_2024
   case "item_return_mgmt":
      include_once 'components/backend/modle/backendModule.php';
      getReturnItems();
      searchReturnItem();
      getCustomers();
      include_once 'components/backend/view/m_item_return_mgmt.php';
      break;

   case "update_item_return":
      include_once 'components/backend/modle/backendModule.php';
      if (updateReturnItem()) {
         header('Location: index.php?components=backend&action=item_return_mgmt&invoice_id=' . $invoice_id . '&message=' . urlencode($message) . '&re=success');
      } else {
         header('Location: index.php?components=backend&action=item_return_mgmt&invoice_id=' . $invoice_id . '&message=' . urlencode($message) . '&re=fail');
      }

   //---------------------------Cust Order MGMT--------------------------------//
   // added by nirmal 21_04_2025
   case "cust_order_mgmt":
      include_once 'components/backend/modle/backendModule.php';
      searchCustOrderItem();
      include_once 'components/backend/view/m_cust_order_mgmt.php';
      break;

   case "set_cust_order_main":
      include_once 'components/backend/modle/backendModule.php';
      if (setCustOrderMain()) {
         header('Location: index.php?components=backend&action=cust_order_mgmt&bill_no=' . $bill_no . '&message=' . urlencode($message) . '&re=success');
      } else {
         header('Location: index.php?components=backend&action=cust_order_mgmt&bill_no=' . $bill_no . '&message=' . urlencode($message) . '&re=fail');
      }
      break;

   //---------------------------QB journal entry Delete--------------------------------//
   // added by nirmal 06_05_2025
   case "qb_delete_journal_entries":
      include_once 'components/backend/view/m_qb_journal_entry_delete.php';
      break;

   // added by nirmal 06_05_2025
   case "qb_delete_journal_entry":
      include_once 'components/backend/modle/backendModule.php';
      if (QBDeleteJournalEntries()) {
         header('Location: index.php?components=backend&action=qb_delete_journal_entries&message=' . urlencode($message) . '&re=success');
      } else {
         header('Location: index.php?components=backend&action=qb_delete_journal_entries&message=' . urlencode($message) . '&re=fail');
      }
      break;

   //---------------------------Salesman payment deposit - Cash payment to bank delete--------------------------------//
   case "cash_payment_deposit":
      include_once 'components/backend/modle/backendModule.php';
      searchCashPaymentDeposit();
      include_once 'components/backend/view/m_cash_payment_delete.php';
      break;

   case "delete_cash_payment_deposit":
      include_once 'components/backend/modle/backendModule.php';
      if (deleteCashPaymentDeposit()) {
         header('Location: index.php?components=backend&action=cash_payment_deposit&message=' . urlencode($message) . '&re=success');
      } else {
         header('Location: index.php?components=backend&action=cash_payment_deposit&message=' . urlencode($message) . '&re=fail');
      }
      break;

   default:
      print '<p><strong>Bad Request</strong></p>';
      break;
}
?>