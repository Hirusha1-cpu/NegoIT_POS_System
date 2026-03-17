<?php
if(passwordExpire()) header('Location: index.php?components=authenticate&action=change_pw&message=Your%20Password%20Has%20Expired.%20Please%20Change%20it%20NOW&re=fail');

switch ($_REQUEST['action']){
   case "set_district" :
      include_once  'components/billing/modle/billingModule.php';
      setDistrict();
      header('Location: index.php?components=supervisor&action=quotation');
   break;

   case "unlocked" :
      include_once  'components/supervisor/modle/supervisorModule.php';
      getUnlockedBills();
      if(isMobile())
            include_once  'components/supervisor/view/m_unlocked.php';
      else
            include_once  'components/supervisor/view/unlocked.php';
   break;

   // added by nirmal 11_07_2023
   case "temporary_bills":
      include_once  'components/supervisor/modle/supervisorModule.php';
      getFilter($sub_system);
      getTemporaryBills();
      if (isMobile())
         include_once  'components/supervisor/view/m_temporary_bills.php';
      else
         include_once  'components/supervisor/view/temporary_bills.php';
   break;

   case "sale" :
      include_once  'components/supervisor/modle/supervisorModule.php';
      if($systemid==1 || ($systemid==13 && $_COOKIE['user_id']==22)){
            dailySale($_GET['store']);
            $storedisable='';
            $userdisable='';
      }else{
         dailySale($_COOKIE['store']);
         $storedisable='disabled';
         $userdisable='';
      }
      getFilter($sub_system);
      if(isMobile())
         include_once  'components/supervisor/view/m_sale_view.php';
      else
         include_once  'components/supervisor/view/sale_view.php';
   break;

   case "repair_income" :
      include_once  'components/manager/modle/managerModule.php';
      getRepairIncome($sub_system);
      if(isMobile())
         include_once  'components/manager/view/m_repair_income.php';
      else
         include_once  'components/manager/view/repair_income.php';
   break;

   case "repair_income_one" :
      include_once  'components/manager/modle/managerModule.php';
      getRepairIncomeOne($sub_system);
      if(isMobile())
         include_once  'components/manager/view/m_repair_income_one.php';
      else
         include_once  'components/manager/view/repair_income_one.php';
   break;

   case "credit" :
      include_once  'components/supervisor/modle/supervisorModule.php';
      getCreditData($sub_system);
      getFilter($sub_system);
      if(isMobile())
         include_once  'components/supervisor/view/m_credit_view.php';
      else
         include_once  'components/supervisor/view/credit_view.php';
   break;

   case "sales_byrep" :
      include_once  'components/manager/modle/managerModule.php';
      getCategory();
      getStore($sub_system);
      salesByRep($sub_system);
      if(isMobile())
         include_once  'components/manager/view/m_sales_by_rep.php';
      else
         include_once  'components/manager/view/sales_by_rep.php';
   break;

   //---------------------------Customer MGMT------------------------------------//
   case "disabledcust" :
      include_once  'components/manager/modle/managerModule.php';
      getCust($sub_system,'0');
      getSalesman($sub_system);
      getStore($sub_system);
      getCustGroups($sub_system);
      getTown();
      getCust2('1');
      getOneCust('name','all');
      if(isMobile())
         include_once  'components/manager/view/m_manageCust.php';
      else
         include_once  'components/manager/view/manageCust.php';
   break;

   case "newcust" :
      include_once  'components/manager/modle/managerModule.php';
      getCust($sub_system,'1,3');
      getSalesman($sub_system);
      getStore($sub_system);
      getCustGroups($sub_system);
      getTown();
      getCust2('1');
      getOneCust('name','all');
      if(isMobile())
         include_once  'components/manager/view/m_manageCust.php';
      else
         include_once  'components/manager/view/manageCust.php';
   break;

   case "editcust" :
      include_once  'components/manager/modle/managerModule.php';
      getCust($sub_system,'1,3');
      getSalesman($sub_system);
      getStore($sub_system);
      getCustGroups($sub_system);
      getTown();
      getOneCust('id',$sub_system);
      if(isMobile())
         include_once  'components/manager/view/m_manageCust.php';
      else
         include_once  'components/manager/view/manageCust.php';
   break;

   case "cust-list" :
      include_once  'template/common.php';
      listCust($sub_system);
      include_once  'template/ajax_list.php';
   break;

   case "cust-list2":
      include_once  'template/common.php';
      listCust($sub_system);
      include_once  'template/ajax_list.php';
   break;

   case "cust-list2-get-one-cust-ajax":
      include_once  'components/manager/modle/managerModule.php';
      print cust2Ajax('name', 'all');
   break;

   case "nick-list" :
      include_once  'template/common.php';
      listCust($sub_system);
      include_once  'template/ajax_list.php';
   break;

   case "mob-list" :
      include_once  'template/common.php';
      listCust($sub_system);
      include_once  'template/ajax_list.php';
   break;

   case "nic-list":
      include_once  'template/common.php';
      listCust($sub_system);
      include_once  'template/ajax_list.php';
   break;

   case "more_cust" :
      include_once  'template/common.php';
      print moreCust($sub_system);
   break;

   case "starchiest" :
      include_once  'components/manager/modle/managerModule.php';
      getCustSearchList($sub_system);
      getSalesman($sub_system);
      getStore($sub_system);
      getCustGroups($sub_system);
      getTown();
      getOneCust('name',$sub_system);
      if(isMobile())
         include_once  'components/manager/view/m_manageCust.php';
      else
         include_once  'components/manager/view/manageCust.php';
   break;

   // added by nirmal 29_04_2022
   case "nic-check":
      include_once  'components/manager/modle/managerModule.php';
      print nicCheckAjax($sub_system);
      break;

   // added by nirmal 29_04_2022
   case "mobile-check":
      include_once  'components/manager/modle/managerModule.php';
      print mobileCheckAjax($sub_system);
   break;

   // added by nirmal 29_04_2022
   case "cust-check":
      include_once  'components/manager/modle/managerModule.php';
      print custCheckAjax($sub_system);
   break;

   case "add_cust" :
      include_once  'components/manager/modle/managerModule.php';
      print addCust($systemid);
   break;

   // added by nirmal 29_04_2022
   case "add_cust_image":
      include_once  'components/manager/modle/managerModule.php';
      if (addCustImage($systemid))
         header('Location: index.php?components=supervisor&action=newcust&message='.$message.'&re=success');
      else
         header('Location: index.php?components=supervisor&action=newcust&message='.$message.'&re=fail');
   break;

   case "update_cust" :
      include_once  'components/manager/modle/managerModule.php';
      if(updateCust())
            header('Location: index.php?components=supervisor&action=newcust&message='.$message.'&re=success');
      else
         header('Location: index.php?components=supervisor&action=newcust&message='.$message.'&re=fail');
   break;

   case "disable_cust" :
      include_once  'components/manager/modle/managerModule.php';
      if(setStatusCust(0))
            header('Location: index.php?components=supervisor&action=newcust&message='.$message.'&re=success');
      else
         header('Location: index.php?components=supervisor&action=newcust&message='.$message.'&re=fail');
   break;

   case "enbale_cust" :
      include_once  'components/manager/modle/managerModule.php';
      if(setStatusCust(1))
            header('Location: index.php?components=supervisor&action=newcust&message='.$message.'&re=success');
      else
         header('Location: index.php?components=supervisor&action=newcust&message='.$message.'&re=fail');
   break;

   case "show_custgroup" :
      include_once  'components/manager/modle/managerModule.php';
      getCustGroups($sub_system);
      if(isMobile())
         include_once  'components/manager/view/m_custGroup.php';
      else
         include_once  'components/manager/view/custGroup.php';
   break;

   case "edit_custgroup" :
      include_once  'components/manager/modle/managerModule.php';
      getCustGroups($sub_system);
      if(isMobile())
         include_once  'components/manager/view/m_custGroup.php';
      else
         include_once  'components/manager/view/custGroup.php';
   break;

   case "add_custgroup" :
      include_once  'components/manager/modle/managerModule.php';
      if(addCustGroup())
            header('Location: index.php?components=supervisor&action=show_custgroup&message='.$message.'&re=success');
      else
         header('Location: index.php?components=supervisor&action=show_custgroup&message='.$message.'&re=fail');
   break;

   case "update_custgroup" :
      include_once  'components/manager/modle/managerModule.php';
      if(updateCustGroup())
            header('Location: index.php?components=supervisor&action=show_custgroup&message='.$message.'&re=success');
      else
         header('Location: index.php?components=supervisor&action=show_custgroup&message='.$message.'&re=fail');
   break;

   case "delete_custgroup" :
      include_once  'components/manager/modle/managerModule.php';
      if(deleteCustGroup())
            header('Location: index.php?components=supervisor&action=show_custgroup&message='.$message.'&re=success');
      else
         header('Location: index.php?components=supervisor&action=show_custgroup&message='.$message.'&re=fail');
   break;

   case "show_custtown" :
      include_once  'components/manager/modle/managerModule.php';
      getTown();
      if(isMobile())
            include_once  'components/manager/view/m_custTown.php';
      else
            include_once  'components/manager/view/custTown.php';
   break;

   case "edit_custtown" :
      include_once  'components/manager/modle/managerModule.php';
      getTown();
      if(isMobile())
            include_once  'components/manager/view/m_custTown.php';
      else
            include_once  'components/manager/view/custTown.php';
   break;

   case "add_custtown" :
      include_once  'components/manager/modle/managerModule.php';
      if(addCustTown())
            header('Location: index.php?components=supervisor&action=show_custtown&message='.$message.'&re=success');
      else
         header('Location: index.php?components=supervisor&action=show_custtown&message='.$message.'&re=fail');
   break;

   case "update_custtown" :
      include_once  'components/manager/modle/managerModule.php';
      if(updateCustTown())
            header('Location: index.php?components=supervisor&action=show_custtown&message='.$message.'&re=success');
      else
         header('Location: index.php?components=supervisor&action=show_custtown&message='.$message.'&re=fail');
   break;

   case "delete_custtown" :
      include_once  'components/manager/modle/managerModule.php';
      if(deleteCustTown())
            header('Location: index.php?components=supervisor&action=show_custtown&message='.$message.'&re=success');
      else
         header('Location: index.php?components=supervisor&action=show_custtown&message='.$message.'&re=fail');
   break;

   //-----------------------------Unic Item-------------------------------------//
   case "sn_lookup" :
      include_once  'components/manager/modle/managerModule.php';
      getStore($sub_system);
      if(isMobile())
         include_once  'components/manager/view/m_sn_lookup.php';
      else
         include_once  'components/manager/view/sn_lookup.php';
   break;

   case "sn_lookup_list" :
      include_once  'components/manager/modle/managerModule.php';
      print snLookupList();
   break;

   case "desc-list" :
      include_once  'template/common.php';
      listItem($sub_system);
      include_once  'template/ajax_list.php';
   break;

   case "sn-list-all" :
      include_once  'components/manager/modle/managerModule.php';
      snListAll();
      include_once  'template/ajax_list.php';
   break;

   //-----------------------------Delete Invoices-------------------------------------//
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

   // added by nirmal 21_11_1
   case "ackdeleted3" :
      include_once  'components/report/modle/reportModule.php';
      print ackDeleted3();
   break;

   //-----------------------------Return Items-------------------------------------//
   case "show_return_summary" :
      include_once  'components/manager/modle/managerModule.php';
      getSalesman($sub_system);
      getCust2(1);
      getReturnSummary();
      include_once  'components/manager/view/return_summary.php';
   break;

   //-------------------Cheque Return-----------------------------------//
   case "chque":
      include_once  'components/supervisor/modle/supervisorModule.php';
      getCheques($sub_system);
      if(isMobile())
         include_once  'components/supervisor/view/m_chque_issues.php';
      else
         include_once  'components/supervisor/view/chque_issues.php';
   break;

   //-------------------Quotation-----------------------------------//
   case "quotation" :
      include_once  'components/billing/modle/billingModule.php';
      include_once  'components/supervisor/modle/supervisorModule.php';
      getDistrict();
      getQuotationItems();
      if(isset($_COOKIE['district'])){
         getItems($item_filter,$sub_system,$systemid);
         getCust(1,'1');
         if(isset($_GET['cust'])){
               if(validateQuotNo()) header('Location: index.php?components=supervisor&action=new_quot&cust_id='.$_GET['cust'].'&att=');
         }
      }
      if(isMobile())
         include_once  'components/supervisor/view/m_quotation.php';
      else
         include_once  'components/supervisor/view/quotation.php';
   break;

   case "new_quot" :
      include_once  'components/supervisor/modle/supervisorModule.php';
      if(newQuot($_REQUEST['cust_id']))
            header('Location: index.php?components=supervisor&action=quotation&id='.$quot_no.'&s='.$salesman.'&cust='.$cust);
         else
            header('Location: index.php?components=supervisor&action=quotation&message='.$message.'&re=fail');
   break;

   case "apend_quot" :
      include_once  'components/billing/modle/billingModule.php';
      include_once  'components/supervisor/modle/supervisorModule.php';
      if(apendQuot()){
            header('Location: index.php?components=supervisor&action=quotation&id='.$quot_no.'&s='.$salesman.'&cust='.$cust.'&message='.$message.'&re=success');
         }else{
            header('Location: index.php?components=supervisor&action=quotation&id='.$quot_no.'&s='.$salesman.'&cust='.$cust.'&message='.$message.'&re=fail');
         }
   break;

   case "qo_item_gpdate" :
      include_once  'components/supervisor/modle/supervisorModule.php';
      if(updateQuot()){
            header('Location: index.php?components=supervisor&action=quotation&id='.$quot_no.'&s='.$salesman.'&cust='.$cust.'&message='.$message.'&re=success');
         }else{
            header('Location: index.php?components=supervisor&action=quotation&id='.$quot_no.'&s='.$salesman.'&cust='.$cust.'&message='.$message.'&re=fail');
         }
   break;

   case "qo_item_remove" :
      include_once  'components/supervisor/modle/supervisorModule.php';
      if(removeQuot()){
            header('Location: index.php?components=supervisor&action=quotation&id='.$quot_no.'&s='.$salesman.'&cust='.$cust.'&message='.$message.'&re=success');
         }else{
            header('Location: index.php?components=supervisor&action=quotation&id='.$quot_no.'&s='.$salesman.'&cust='.$cust.'&message='.$message.'&re=fail');
         }
   break;

   // added by nirmal 25_10_2023
   case "qo_item_update_unit_price":
      include_once  'components/supervisor/modle/supervisorModule.php';
      if (updateQuotItemUnitPrice()) {
         header('Location: index.php?components=supervisor&action=quotation&id=' . $quot_no . '&s=' . $salesman . '&cust=' . $cust . '&message=' . $message . '&re=success');
      } else {
         header('Location: index.php?components=supervisor&action=quotation&id=' . $quot_no . '&s=' . $salesman . '&cust=' . $cust . '&message=' . $message . '&re=fail');
      }
   break;

   // added by nirmal 25_10_2023
   case "qo_update_discount":
      include_once  'components/supervisor/modle/supervisorModule.php';
      if (updateQuotDiscount()) {
         header('Location: index.php?components=supervisor&action=quotation&id=' . $quot_no . '&s=' . $salesman . '&cust=' . $cust . '&message=' . $message . '&re=success');
      } else {
         header('Location: index.php?components=supervisor&action=quotation&id=' . $quot_no . '&s=' . $salesman . '&cust=' . $cust . '&message=' . $message . '&re=fail');
      }
   break;

   // added by nirmal 25_10_2023
   case "qo_remove_discount":
      include_once  'components/supervisor/modle/supervisorModule.php';
      if (removeQuotDiscount()) {
         header('Location: index.php?components=supervisor&action=quotation&id=' . $quot_no . '&s=' . $salesman . '&cust=' . $cust . '&message=' . $message . '&re=success');
      } else {
         header('Location: index.php?components=supervisor&action=quotation&id=' . $quot_no . '&s=' . $salesman . '&cust=' . $cust . '&message=' . $message . '&re=fail');
      }
   break;

   // added by nirmal 29_07_2024
   case "qo_update_comment":
      include_once  'components/supervisor/modle/supervisorModule.php';
      print updateQuotComment();
   break;

   case "qo_terms" :
      include_once  'components/supervisor/modle/supervisorModule.php';
      getQOTerms();
      getDetaultTerms();
      if (isMobile())
         include_once  'components/supervisor/view/m_quotation_terms.php';
      else
         include_once  'components/supervisor/view/quotation_terms.php';
   break;

   case "set_qo_terms" :
      include_once  'components/supervisor/modle/supervisorModule.php';
      if(setQuotTerms())
            header('Location: index.php?components=supervisor&action=set_quot_status&id='.$quot_no.'&new_status=2');
      else
         header('Location: index.php?components=supervisor&action=qo_terms&id='.$quot_no.'&message='.$message.'&re=fail');
   break;

   case "set_quot_status" :
      include_once  'components/supervisor/modle/supervisorModule.php';
      if(setQuotStatus($_GET['new_status']))
            header('Location: index.php?components=supervisor&action=qo_finish&id='.$quot_no.'&message='.$message.'&re=success');
      else
         header('Location: index.php?components=supervisor&action=qo_finish&id='.$quot_no.'&message='.$message.'&re=fail');
   break;

   case "qo_revise" :
      include_once  'components/supervisor/modle/supervisorModule.php';
      if(qoRevise())
            header('Location: index.php?components=supervisor&action=qo_finish&id='.$quot_no);
      else
         header('Location: index.php?components=supervisor&action=qo_finish&id='.$quot_no.'&message='.$message.'&re=fail');
   break;

   case "set_submit" :
      include_once  'components/supervisor/modle/supervisorModule.php';
      setQuotStatus(5);
   break;

   case "qo_finish" :
      include_once  'components/supervisor/modle/supervisorModule.php';
      qoPermission();
      qoDetails();
      qoNote();
      qoTemplate();
      if(isMobile())
            include_once  'components/supervisor/view/m_qo_print.php';
      else
            include_once  'components/supervisor/view/qo_print.php';
   break;

   case "qo_one" :
      include_once  'components/supervisor/modle/supervisorModule.php';
      qoPermission();
      qoDetails();
      qoNote();
      qoTemplate();
      generateQuot(2);
      include_once  'components/supervisor/view/qo_one.php';
   break;

   case "qo_com_inv" :
      include_once  'components/supervisor/modle/supervisorModule.php';
      qoPermission();
      qoDetails();
      qoNote();
      qoTemplate();
      include_once  'components/supervisor/view/qo_print.php';
   break;

   case "quotation_list" :
      include_once  'components/supervisor/modle/supervisorModule.php';
      getQuotList($sub_system);
      getFilter($sub_system);
      getCustSup($sub_system);
      if (isMobile())
         include_once  'components/supervisor/view/m_quotation_list.php';
      else
         include_once  'components/supervisor/view/quotation_list.php';
   break;

   case "qo_complete_check" :
      include_once  'components/supervisor/modle/supervisorModule.php';
      print qoCompleteCheck();
   break;

   case "quotation_ongoing" :
      include_once  'components/supervisor/modle/supervisorModule.php';
      getOnGoing($sub_system);
      if (isMobile())
         include_once  'components/supervisor/view/m_quotation_ongoing.php';
      else
         include_once  'components/supervisor/view/quotation_ongoing.php';
   break;

   case "search_quot" :
      include_once  'components/supervisor/modle/supervisorModule.php';
         if(searchQuot($_POST['search1']))
            header('Location: index.php?components=supervisor&action=qo_finish&id='.$_POST['search1']);
         else
            header('Location: index.php?components=supervisor&action=quotation&message=Invalid%20Quotation%20Number&re=fail');
   break;

   case "qo_add_image" :
      include_once  'components/supervisor/modle/supervisorModule.php';
         if(qoAddImage())
            header('Location: index.php?components=supervisor&action=qo_finish&id='.$_GET['id'].'&message='.$message.'&re=success');
         else
            header('Location: index.php?components=supervisor&action=qo_finish&id='.$_GET['id'].'&message='.$message.'&re=fail');
   break;

   case "qo_delete_image" :
      include_once  'components/supervisor/modle/supervisorModule.php';
         if(qoDeleteImage())
            header('Location: index.php?components=supervisor&action=qo_finish&id='.$_GET['id'].'&message='.$message.'&re=success');
         else
            header('Location: index.php?components=supervisor&action=qo_finish&id='.$_GET['id'].'&message='.$message.'&re=fail');
   break;

   case "qo_img_height" :
      include_once  'components/supervisor/modle/supervisorModule.php';
         if(qoImgHeight())
            header('Location: index.php?components=supervisor&action=qo_finish&id='.$_GET['id'].'&message='.$message.'&re=success');
         else
            header('Location: index.php?components=supervisor&action=qo_finish&id='.$_GET['id'].'&message='.$message.'&re=fail');
   break;

   case "qo_add_note" :
      include_once  'components/supervisor/modle/supervisorModule.php';
      if(qoAddNote())
            header('Location: index.php?components=supervisor&action=qo_finish&id='.$quot_no);
         else
            header('Location: index.php?components=supervisor&action=qo_finish&id='.$quot_no.'&message='.$message.'&re=fail');
   break;

   case "qo_update_note" :
      include_once  'components/supervisor/modle/supervisorModule.php';
      if(qoUpdateNote())
            header('Location: index.php?components=supervisor&action=qo_finish&id='.$quot_no.'&message='.$message.'&re=success');
         else
            header('Location: index.php?components=supervisor&action=qo_finish&id='.$quot_no.'&message='.$message.'&re=fail');
   break;

   case "quotation_report" :
      include_once  'components/supervisor/modle/supervisorModule.php';
      getFilter($sub_system);
      getCustSup($sub_system);
      getReportNote($sub_system);
      include_once  'components/supervisor/view/quotation_report.php';
   break;

   case "quotation_sent_with_tax":
      include_once  'components/supervisor/modle/supervisorModule.php';
      if(qoUpdateSentWithTax())
         header('Location: index.php?components=supervisor&action=qo_finish&id='.$quot_no.'&message='.$message.'&re=success');
      else
         header('Location: index.php?components=supervisor&action=qo_finish&id='.$quot_no.'&message='.$message.'&re=fail');
   break;

   //-----------------------------Payment Deposits------------------------------------//
   case "payment_deposits":
      include_once  'components/manager/modle/managerModule.php';
      getStoreSettings($sub_system);
      include_once  'components/manager/view/payment_deposits.php';
   break;

   case "pending_payment_cash_deposits":
      include_once  'components/manager/modle/managerModule.php';
      getPendingCashDeposits($sub_system);
      getBankAccounts();
      getSalesman($sub_system);
      include_once  'components/manager/view/pending_payment_cash_deposits.php';
   break;

   case "change_payment_deposit_status_ajax":
      include_once  'components/manager/modle/managerModule.php';
      print changePaymentDepositStatusAjax($sub_system);
   break;

   case "pending_payment_bank_deposits":
      include_once  'components/manager/modle/managerModule.php';
      getPendingBankDeposits($sub_system);
      getBankAccounts();
      getSalesman($sub_system);
      include_once  'components/manager/view/pending_payment_bank_deposits.php';
   break;

   case "cash_transfer_deposits_report":
      include_once  'components/manager/modle/managerModule.php';
      getCashDepositsReport($sub_system);
      getBankAccounts();
      getSalesman4($sub_system);
      getSalesman($sub_system);
      include_once  'components/manager/view/cash_transfer_deposits_report.php';
   break;

   case "bank_transfer_deposits_report":
      include_once  'components/manager/modle/managerModule.php';
      getBankDepositsReport($sub_system);
      getBankAccounts();
      getSalesman4($sub_system);
      getSalesman($sub_system);
      include_once  'components/manager/view/bank_transfer_deposits_report.php';
   break;

   //new
   case "pending_cheque_transfers":
      include_once  'components/manager/modle/managerModule.php';
      getSalesman($sub_system);
      getSalesman4($sub_system);
      getPendingChequeTransfers($sub_system);
      include_once  'components/manager/view/pending_cheque_transfers.php';
   break;

   case "add_cheque_transfer_ajax":
      include_once  'components/manager/modle/managerModule.php';
      print addChequeTransferAjax($sub_system);
   break;

   case "cheque_transfer":
      include_once  'components/manager/modle/managerModule.php';
      getChequeApprovedByUser($sub_system);
      getSalesman4($sub_system);
      include_once  'components/manager/view/cheque_transfer.php';
   break;

   case "add_cheque_transfer_to_user_ajax":
      include_once  'components/manager/modle/managerModule.php';
      print addChequeTransferToUserAjax($sub_system);
   break;

   case "approved_cheque_transfers":
      include_once  'components/manager/modle/managerModule.php';
      getApprovedChequeTransfers($sub_system);
      getSalesman4($sub_system);
      include_once  'components/manager/view/approved_cheque_transfers.php';
   break;

   case "change_cheque_transfer_status_ajax":
      include_once  'components/manager/modle/managerModule.php';
      print changeChequeTransferStatusAjax($sub_system);
   break;

   case "trans_return_cheque":
      include_once  'components/manager/modle/managerModule.php';
      getTransReturnMarkedCheques($sub_system);
      getSalesman($sub_system);
      include_once  'components/manager/view/trans_return_cheques.php';
   break;

   case "add_cheque_transfer_return_to_user_ajax":
      include_once  'components/manager/modle/managerModule.php';
      print addChequeTransferReturnToUserAjax($sub_system);
   break;

   default:
         print '<p><strong>Bad Request</strong></p>';
   break;
}

?>