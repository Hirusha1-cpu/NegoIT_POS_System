<?php
if(passwordExpire()) header('Location: index.php?components=authenticate&action=change_pw&message=Your%20Password%20Has%20Expired.%20Please%20Change%20it%20NOW&re=fail');

switch ($_REQUEST['action']){
   case "sales_report" :
      include_once  'components/report/modle/reportModule.php';
      salesReport();
      getItems();
      getCustGroups();
      getSubSystems();
      if(isMobile())
            include_once  'components/report/view/m_salesReport.php';
      else
            include_once  'components/report/view/salesReport.php';
   break;

   case "category_profit" :
      include_once  'components/report/modle/reportModule.php';
      getCategoryProfit();
      getSubSystems();
      if(isMobile())
            include_once  'components/report/view/m_category_profit.php';
      else
            include_once  'components/report/view/category_profit.php';
   break;

   // added by nirmal 07_08_2023
   case "profit_report" :
      include_once  'components/report/modle/reportModule.php';
      getProfitReport();
      getCustGroups();
      getSubSystems();
      if(isMobile())
            include_once  'components/report/view/m_profit_report.php';
      else
            include_once  'components/report/view/profit_report.php';
   break;

   // added by nirmal 11_08_2023
   case "export_profit_report":
      include_once  'components/report/modle/reportModule.php';
      if(!exportExcelProfitReport())
         header('Location: index.php?components=report&action=profit_report&message='.$message.'&re=fail');
   break;

   case "sales_trend" :
      include_once  'components/report/modle/reportModule.php';
      getSubSystems();
      salesTrend();
      if(isMobile())
            include_once  'components/report/view/m_salesTrend.php';
      else
            include_once  'components/report/view/salesTrend.php';
   break;

   case "credit_trend" :
      include_once  'components/report/modle/reportModule.php';
      getSubSystems();
      creditTrend();
      if(isMobile())
            include_once  'components/report/view/m_creditTrend.php';
      else
            include_once  'components/report/view/creditTrend.php';
   break;

   case "deleted" :
      include_once  'components/report/modle/reportModule.php';
      deletedList();
      if(isMobile())
            include_once  'components/report/view/m_deleted.php';
      else
            include_once  'components/report/view/deleted.php';
   break;

   case "ackdeleted1" :
      include_once  'components/report/modle/reportModule.php';
      print ackDeleted1();
   break;

   case "ackdeleted2" :
      include_once  'components/report/modle/reportModule.php';
      print ackDeleted2();
   break;

   // adeed by nirmal 21_11_1
   case "ackdeleted3" :
      include_once  'components/report/modle/reportModule.php';
      print ackDeleted3();
   break;

   case "salesman" :
      include_once  'components/report/modle/reportModule.php';
      salesman();
      if(isMobile())
            include_once  'components/report/view/m_salesman.php';
      else
            include_once  'components/report/view/salesman.php';
   break;

   case "salesman_invoices" :
      include_once  'components/report/modle/reportModule.php';
      salesmanInvoices();
      if(isMobile())
            include_once  'components/report/view/m_salesmanInvoices.php';
      else
            include_once  'components/report/view/salesmanInvoices.php';
   break;

   case "chque_pending_finalyze" :
      include_once  'components/manager/modle/managerModule.php';
      getStore('all');
      getSalesman('all');
      getChqueData('all');
      getBankAccounts();
      if(isMobile())
         include_once  'components/manager/view/m_chque_pending_finalyze.php';
      else
         include_once  'components/manager/view/chque_pending_finalyze.php';
   break;

   // case "chque_realize_report_onedate":
   //    include_once  'components/manager/modle/managerModule.php';
   //    getStore($sub_system);
   //    getSalesman($sub_system);
   //    getChqueData();
   //    getBankAccounts();
   //    if(isMobile()){
   //       include_once  'components/manager/view/m_chque_realize_report_onedate.php';
   //    }else{
   //       include_once  'components/manager/view/chque_realize_report_onedate.php';
   //    }
   // break;

   // case "chque_realize_report_daterange" :
   //    include_once  'components/manager/modle/managerModule.php';
   //    getChqueRange();
   //    if(isMobile())
   //    	// include_once  'components/manager/view/m_chque_range.php';
   //       include_once  'components/manager/view/m_chque_realize_report_daterange.php';
   //    else
   //    	include_once  'components/manager/view/chque_realize_report_daterange.php';
   // break;

   // update by nirmal 21_12_2
   case "clear_chque_list" :
      include_once  'components/manager/modle/managerModule.php';
      getClearedChques();
      getBankAccounts();
      if(isMobile())
            include_once  'components/manager/view/m_chque_clear.php';
      else
            include_once  'components/manager/view/chque_clear.php';
   break;

   case "credit" :
      include_once  'components/supervisor/modle/supervisorModule.php';
      getCreditData(isset($_GET['sub_system']));
      getFilter('all');
      getSubSystems();
      if(isMobile())
            include_once  'components/supervisor/view/m_credit_view.php';
      else
            include_once  'components/supervisor/view/credit_view.php';
   break;

   case "purchase_order" :
      include_once  'components/report/modle/reportModule.php';
      getPOitems();
      if(isMobile())
            include_once  'components/report/view/m_po.php';
      else
            include_once  'components/report/view/po.php';
   break;

   case "payment_commision" :
      include_once  'components/report/modle/reportModule.php';
      getSalesman();
      getPcommision();
      if(isMobile())
            include_once  'components/report/view/m_p_commision.php';
      else
            include_once  'components/report/view/p_commision.php';
   break;

   // ------------------------   Salesman Commission Reports  ------------------------ //
   // added by nirmal 08_02_2022
   case "salesman_commission_new" :
      include_once  'components/report/modle/reportModule.php';
      getSMCommission();
      if(isMobile())
            include_once  'components/report/view/m_salesman_commission_new.php';
      else
            include_once  'components/report/view/salesman_commission_new.php';
   break;

   // added by nirmal 08_02_2022
   case "salesman_commission_old":
      include_once  'components/report/modle/reportModule.php';
      getSMCommissionList();
      if(isMobile())
            include_once  'components/report/view/m_salesman_commission_list.php';
      else
            include_once  'components/report/view/salesman_commission_list.php';
   break;

   // added by nirmal 08_02_2022
   case "salesman_generate_commission" :
      include_once  'components/report/modle/reportModule.php';
      if(smGenerateCommission($sub_system))
            header('Location: index.php?components=report&action=salesman_commission_new&message='.$message.'&re=success');
         else
            header('Location: index.php?components=report&action=salesman_commission_new&message='.$message.'&re=fail');
   break;

   // added by nirmal 08_02_2022
   case "salesman_commission_one":
      include_once  'components/report/modle/reportModule.php';
      getSMCommissionOne();
      if(isMobile())
         include_once  'components/report/view/m_salesman_commission_one.php';
      else
         include_once  'components/report/view/salesman_commission_one.php';
   break;

   // added by nirmal 10_02_2022
   case "salesman_commission_delete":
      include_once  'components/report/modle/reportModule.php';
      if(smDeleteCommission(0))
         header('Location: index.php?components=report&action=salesman_commission_old&message='.$message.'&re=success');
      else
         header('Location: index.php?components=report&action=salesman_commission_old&id='.$_GET['id'].'&message='.$message.'&re=fail');
   break;

   // added by nirmal 10_02_2022
   case "salesman_commission_one_user":
      include_once  'components/report/modle/reportModule.php';
      getSMCommissionOne();
      if (isMobile())
         include_once  'components/report/view/m_salesman_commission_one.php';
      else
         include_once  'components/report/view/salesman_commission_one.php';
   break;

   // added by nirmal 04_04_2022
   case "salesman_commission_incomplete":
      include_once  'components/report/modle/reportModule.php';
      getSMCommissionIncomplete();
      if (isMobile())
         include_once  'components/report/view/m_salesman_commission_incomplete.php';
      else
         include_once  'components/report/view/salesman_commission_incomplete.php';
   break;

   // added by nirmal 04_04_2022
   case "salesman_commission_incomplete_one":
      include_once  'components/report/modle/reportModule.php';
      getSMCommissionIncomplete();
      if (isMobile())
         include_once  'components/report/view/m_salesman_commission_incomplete_one.php';
      else
         include_once  'components/report/view/salesman_commission_incomplete_one.php';
   break;

   case "return_items" :
      include_once  'components/report/modle/reportModule.php';
      getItems();
      getCategory();
      getCustomerSS();
      returnItems();
      if(isMobile())
            include_once  'components/report/view/m_return_item.php';
      else
            include_once  'components/report/view/return_item.php';
   break;

   case "return_one" :
      include_once  'components/report/modle/reportModule.php';
      returnOne();
      if(isMobile())
            include_once  'components/report/view/m_return_one.php';
      else
            include_once  'components/report/view/return_one.php';
   break;

   case "cost" :
      include_once  'components/report/modle/reportModule.php';
      getCost();
      if(isMobile())
            include_once  'components/report/view/m_cost.php';
      else
            include_once  'components/report/view/cost.php';
   break;

   case "unlocked" :
      include_once  'components/report/modle/reportModule.php';
      getUnlockedBills2();
      if(isMobile())
            include_once  'components/report/view/m_unlocked.php';
      else
            include_once  'components/report/view/unlocked.php';
   break;

   case "sub" :
      include_once  'components/report/modle/reportModule.php';
      if($_GET['report_type']=='itembysalesman'){
            getItems();
            getItembySalesman();
         }else if($_GET['report_type']=='useraudit'){
            getUsers();
            getUserAuditLog();
         }else if($_GET['report_type']=='transaudit'){
            getStore();
            getTransAuditLog();
         }else if($_GET['report_type']=='newcust_salesman'){
            getNewCust();
         }else if($_GET['report_type']=='crlimitaudit'){
            getCrLimitAudit();
         }else if($_GET['report_type']=='editqtyaudit'){
            getItems();
            getEditQtyAudit();
         }else if($_GET['report_type']=='loginaudit'){
            getUsers();
            getLoginAudit();
         }else if($_GET['report_type']=='billeditaudit'){
            getUsers();
            getBillEditAudit();
         }else if($_GET['report_type']=='payeditaudit'){
            getUsers();
            getPayEditAudit();
         }
      if(isMobile())
            include_once  'components/report/view/m_sub.php';
      else
            include_once  'components/report/view/sub.php';
   break;

   case "approval" :
      include_once  'components/report/modle/reportModule.php';
      getPendingApproval();
      if(isMobile())
            include_once  'components/report/view/m_approval.php';
      else
            include_once  'components/report/view/approval.php';
   break;

   case "set_loan_status" :
      include_once  'components/report/modle/reportModule.php';
      if(setLoanStatus($_GET['new_status']))
            header('Location: index.php?components=report&action=approval&message='.$message.'&re=success');
         else
            header('Location: index.php?components=report&action=approval&message='.$message.'&re=fail');
   break;

   case "set_shipment_status" :
      include_once  'components/report/modle/reportModule.php';
      if(setShipmentStatus($_GET['new_status']))
            header('Location: index.php?components=report&action=approval&message='.$message.'&re=success');
         else
            header('Location: index.php?components=report&action=approval&message='.$message.'&re=fail');
   break;

   //-----------------------------Hire Purchase------------------------------------//
   case "hp_active_list" :
      include_once  'components/hirePurchase/modle/hpModule.php';
      myActiveInvoices('all');
      if(isset($_GET['mismatch'])) paymentDateIssueList($_COOKIE['user_id']);
      if(isMobile())
         include_once  'components/hirePurchase/view/m_home.php';
      else
         include_once  'components/hirePurchase/view/home.php';
   break;

   case "hp_deductions" :
      include_once  'components/report/modle/reportModule.php';
      getExceededPendingPayments();
      include_once  'components/report/view/hp_deductions.php';
   break;

   case "add_deduction" :
      include_once  'components/report/modle/reportModule.php';
      print addDeduction();
   break;

   case "remove_deduction" :
      include_once  'components/report/modle/reportModule.php';
      print removeDeduction();
   break;

   case "hp_commission_new" :
      include_once  'components/report/modle/reportModule.php';
      getHPCommission();
      if(isMobile())
            include_once  'components/report/view/m_hp_commission_new.php';
      else
            include_once  'components/report/view/hp_commission_new.php';
   break;

   case "hp_generate_commission" :
      include_once  'components/report/modle/reportModule.php';
      if(hpGenerateCommission($sub_system))
            header('Location: index.php?components=report&action=hp_commission_new&message='.$message.'&re=success');
         else
            header('Location: index.php?components=report&action=hp_commission_new&message='.$message.'&re=fail');
   break;

   case "hp_commission_old" :
      include_once  'components/report/modle/reportModule.php';
      getHPCommissionList();
      if(isMobile())
            include_once  'components/report/view/m_hp_commission_list.php';
      else
            include_once  'components/report/view/hp_commission_list.php';
   break;

   case "hp_commission_one" :
      include_once  'components/report/modle/reportModule.php';
      getHPCommissionOne();
      if(isMobile())
            include_once  'components/report/view/m_hp_commission_one.php';
      else
            include_once  'components/report/view/hp_commission_one.php';
   break;

   case "hp_commission_one_user":
         include_once  'components/report/modle/reportModule.php';
      getHPCommissionOne();
      if (isMobile())
         include_once  'components/report/view/m_hp_commission_one.php';
      else
         include_once  'components/report/view/hp_commission_one_user.php';
   break;

   case "hp_commission_delete" :
      include_once  'components/report/modle/reportModule.php';
      if(hpDeleteCommission(0))
            header('Location: index.php?components=report&action=hp_commission_old&message='.$message.'&re=success');
         else
            header('Location: index.php?components=report&action=hp_commission_one&id='.$_GET['id'].'&message='.$message.'&re=fail');
   break;
   //-----------------------------Authorize Code------------------------------------//
   case "authorize_code":
      include_once  'components/manager/modle/managerModule.php';
      getAuthorizeCodelist();
      if(isMobile())
         include_once  'components/manager/view/m_authorize.php';
      else
         include_once  'components/manager/view/authorize.php';
   break;

   case "get_authorize":
      include_once  'components/manager/modle/managerModule.php';
      print getAuthorize2();
   break;

   default:
         print '<p><srtong>Bad Request</strong></p>';
   break;
}
?>