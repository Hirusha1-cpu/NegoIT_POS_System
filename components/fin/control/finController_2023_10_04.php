<?php
if(passwordExpire()) header('Location: index.php?components=authenticate&action=change_pw&message=Your%20Password%20Has%20Expired.%20Please%20Change%20it%20NOW&re=fail');
   switch ($_REQUEST['action']){
      case "home" :
         include_once  'components/fin/modle/finModule.php';
         getAccountHistory();
         dashboard();
         getExpenseFormData();
         include_once  'components/fin/view/home.php';
      break;
      //----------------------------------EXPENSES-------------------------------------//  
      case "expense" :
         include_once  'components/fin/modle/finModule.php';
         getExpenseFormData();
         include_once  'components/fin/view/expense_add.php';
      break;
      
      case "add_expense" :
         include_once  'components/fin/modle/finModule.php';
         if(addExpense())
         		header('Location: index.php?components=fin&action=one_expense&id='.$expense_id.'&message='.$message.'&re=success');
         	else
         		header('Location: index.php?components=fin&action=expense&message='.$message.'&re=fail');
      break;
      
      case "delete_expense" :
         include_once  'components/fin/modle/finModule.php';
         if(deleteExpense())
         		header('Location: index.php?components=fin&action=list_expense&year='.date("Y",time()).'&message='.$message.'&re=success');
         	else
         		header('Location: index.php?components=fin&action=one_expense&id='.$expense_id.'&message='.$message.'&re=fail');
      break;
      
      case "list_expense" :
         include_once  'components/fin/modle/finModule.php';
         listExpenseYears();
         listExpense();
         include_once  'components/fin/view/expense_list.php';
      break;
      
      case "one_expense" :
         include_once  'components/fin/modle/finModule.php';
         getOneExpense();
         include_once  'components/fin/view/expense_one.php';
      break;
    	//----------------------------------JOURNAL ENTRY-------------------------------------//  
      case "journal_entry" :
         include_once  'components/fin/modle/finModule.php';
         getExpenseFormData();
         include_once  'components/fin/view/journal_add.php';
      break;
      
      case "add_journal" :
         include_once  'components/fin/modle/finModule.php';
         if(addJournal())
         		header('Location: index.php?components=fin&action=one_journal&id='.$journal_id.'&message='.$message.'&re=success');
         	else
         		header('Location: index.php?components=fin&action=journal_entry&message='.$message.'&re=fail');
      break;
      
      case "delete_journal" :
         include_once  'components/fin/modle/finModule.php';
         if(deleteJournal())
         		header('Location: index.php?components=fin&action=list_journal&year='.date("Y",time()).'&message='.$message.'&re=success');
         	else
         		header('Location: index.php?components=fin&action=one_journal&id='.$expense_id.'&message='.$message.'&re=fail');
      break;
      
      case "list_journal" :
         include_once  'components/fin/modle/finModule.php';
         listJournalYears();
         listJournal();
         include_once  'components/fin/view/journal_list.php';
      break;
      
      case "one_journal" :
         include_once  'components/fin/modle/finModule.php';
         getOneJournal();
         include_once  'components/fin/view/journal_one.php';
      break;
      //----------------------------------Salary & PayRoll-------------------------------------//  
      case "salary" :
         include_once  'components/fin/modle/finModule.php';
         getSalaryType();
         getEmp();
         include_once  'components/fin/view/salary.php';
      break;
      
      case "one_salary" :
         include_once  'components/fin/modle/finModule.php';
         getSalaryType();
         oneSalary();
         getEmp();
         include_once  'components/fin/view/salary.php';
      break;
      
      case "update_salary" :
         include_once  'components/fin/modle/finModule.php';
         if(updateSalary())
         		header('Location: index.php?components=fin&action=one_salary&id='.$emp_id.'&message='.$message.'&re=success');
         	else
         		header('Location: index.php?components=fin&action=one_salary&id='.$emp_id.'&message='.$message.'&re=fail');
      break;
      
      case "payroll" :
         include_once  'components/fin/modle/finModule.php';
         getPayrollForm();
         include_once  'components/fin/view/payroll.php';
      break;
      
      case "generate_payroll" :
         include_once  'components/fin/modle/finModule.php';
         if(generatePayroll())
         		header('Location: index.php?components=fin&action=payroll_one&id='.$payroll_no.'&message='.$message.'&re=success');
         	else
         		header('Location: index.php?components=fin&action=payroll&message='.$message.'&re=fail');
      break;
      
      case "payroll_delete" :
         include_once  'components/fin/modle/finModule.php';
         if(deletePayroll())
         		header('Location: index.php?components=fin&action=payroll_list&message='.$message.'&re=success');
         	else
         		header('Location: index.php?components=fin&action=payroll_list&message='.$message.'&re=fail');
      break;
      
      case "payroll_list" :
         include_once  'components/fin/modle/finModule.php';
         getPayrollList();
         include_once  'components/fin/view/payroll_list.php';
      break;
      
      case "payroll_one" :
         include_once  'components/fin/modle/finModule.php';
         getPayrollOne();
         include_once  'components/fin/view/payroll_one.php';
      break;
      
      case "payroll_user_view" :
         include_once  'components/fin/modle/finModule.php';
         getPayrollUserView();
         include_once  'components/fin/view/payroll_user.php';
      break;
	   //----------------------------------------LOAN--------------------------------------------//
      case "loan" :
         include_once  'components/fin/modle/finModule.php';
         getLoanList();
         getEmp();
         include_once  'components/fin/view/loan.php';
      break;
      
      case "loan_one" :
         include_once  'components/fin/modle/finModule.php';
         getLoanList();
         getLoanOne();
         getPayAccounts();
         include_once  'components/fin/view/loan.php';
      break;
      
      case "new_loan" :
         include_once  'components/fin/modle/finModule.php';
         if(createLoan())
         		header('Location: index.php?components=fin&action=loan&message='.$message.'&re=success');
         	else
         		header('Location: index.php?components=fin&action=loan&message='.$message.'&re=fail');
      break;
      
      case "edit_loan" :
         include_once  'components/fin/modle/finModule.php';
         if(editLoan())
         		header('Location: index.php?components=fin&action=loan_one&id='.$id.'&message='.$message.'&re=success');
         	else
         		header('Location: index.php?components=fin&action=loan&message='.$message.'&re=fail');
      break;
      
      case "pay_loan" :
         include_once  'components/fin/modle/finModule.php';
         if(payLoan())
         		header('Location: index.php?components=fin&action=loan_one&id='.$id.'&message='.$message.'&re=success');
         	else
         		header('Location: index.php?components=fin&action=loan&message='.$message.'&re=fail');
      break;
      
      case "grant_loan" :
         include_once  'components/fin/modle/finModule.php';
         if(grantLoan())
         		header('Location: index.php?components=fin&action=loan_one&id='.$id.'&message='.$message.'&re=success');
         	else
         		header('Location: index.php?components=fin&action=loan&message='.$message.'&re=fail');
      break;
      
      case "delete_loan" :
         include_once  'components/fin/modle/finModule.php';
         if(deleteLoan())
         		header('Location: index.php?components=fin&action=loan&message='.$message.'&re=success');
         	else
         		header('Location: index.php?components=fin&action=loan&message='.$message.'&re=fail');
      break;
      //----------------------------------CHART OF ACCOUNTS-------------------------------------//  
      case "chart_of_accounts" :
         include_once  'components/fin/modle/finModule.php';
         getCahrtOfAccounts();
         getAccountFormData();
         include_once  'components/fin/view/chart_of_accounts.php';
      break;
      
      case "one_chart_of_accounts" :
         include_once  'components/fin/modle/finModule.php';
         getCahrtOfAccounts();
         getAccountFormData();
         getOneAccount();
         include_once  'components/fin/view/chart_of_accounts.php';
      break;
      
      case "add_chart_of_accounts" :
         include_once  'components/fin/modle/finModule.php';
         if(addCahrtOfAccounts())
         		header('Location: index.php?components=fin&action=chart_of_accounts&message='.$message.'&re=success');
         	else
         		header('Location: index.php?components=fin&action=chart_of_accounts&message='.$message.'&re=fail');
      break;
      
      case "edit_chart_of_accounts" :
         include_once  'components/fin/modle/finModule.php';
         if(editCahrtOfAccounts())
         	header('Location: index.php?components=fin&action=chart_of_accounts&message='.$message.'&re=success');
         else
            header('Location: index.php?components=fin&action=chart_of_accounts&message='.$message.'&re=fail');
      break;
      
      case "setst_chart_of_accounts" :
         include_once  'components/fin/modle/finModule.php';
         if(setStCahrtOfAccounts())
         		header('Location: index.php?components=fin&action=chart_of_accounts&message='.$message.'&re=success');
         	else
         		header('Location: index.php?components=fin&action=chart_of_accounts&message='.$message.'&re=fail');
      break;
      
      case "acount_history" :
         include_once  'components/fin/modle/finModule.php';
         getAccountHistory();
         include_once  'components/fin/view/view_account.php';
      break;

      case "account_balance" :
         include_once  'components/fin/modle/finModule.php';
         print getAccountHistory();
      break;
	
	   //---------------------------------REPORT-----------------------------------------//
	
      case "report" :
         include_once  'components/fin/view/report.php';
      break;
      
      case "rep_balance_sheet" :
         include_once  'components/fin/modle/finModule.php';
         generateBalanceSheet();
         generateProfitandLoss();
         include_once  'components/fin/view/report.php';
      break;
      
      case "rep_profit_and_loss" :
         include_once  'components/fin/modle/finModule.php';
         generateProfitandLoss();
         include_once  'components/fin/view/report.php';
      break;
      
      case "rep_trial_balance" :
         include_once  'components/fin/modle/finModule.php';
         generateBalanceSheet();
         generateProfitandLoss();
         include_once  'components/fin/view/report.php';
      break;
      //---------------------Linked From Manager Module-----------------------------------------//
      case "daily_sale" :
         include_once  'components/manager/modle/managerModule.php';
         dailySale($_GET['store'],'all');
         getFilter('all');
         getCustGroups('all');
         if(isMobile())
            include_once  'components/manager/view/m_daily_sale.php';
        else
            include_once  'components/manager/view/daily_sale.php';
      break;
      
      case "daily_sale_detail" :
         include_once  'components/manager/modle/managerModule.php';
         dailySale2($_GET['store'],'all');
         getFilter('all');
         getCustGroups('all');
         include_once  'components/manager/view/daily_sale_detail.php';
      break;
      
      case "cust_sale" :
         include_once  'components/manager/modle/managerModule.php';
	   getCust($sub_system, '0,1,2');
         getCustSale($systemid);
         if(isMobile())
         		include_once  'components/manager/view/m_cust_sale.php';
         else       
         		include_once  'components/manager/view/cust_sale.php';
      break;

      case "check_payment_correlate" :
         include_once  'components/manager/modle/managerModule.php';
         print checkPaymentCorrelate();
      break;
      
      case "credit" :
         include_once  'components/supervisor/modle/supervisorModule.php';
         getCreditData('all');
         getFilter('all');
         if(isMobile())           
         		include_once  'components/supervisor/view/m_credit_view.php';
         else
         		include_once  'components/supervisor/view/credit_view.php';
      break;
      //-----------------------------Chque_return------------------------------------//
      case "chque_return" :
         include_once  'components/manager/modle/managerModule.php';
        getChqueNo('all',0);
		   getReturnedChque('all');
        getChqueOne();
         include_once  'components/manager/view/chque_return.php';
      break;
      
      case "chque_setreturn" :
         include_once  'components/manager/modle/managerModule.php';
         if(setChqueStatus(1))
         		header('Location: index.php?components=fin&action=chque_return&message='.$message.'&re=success');
         	else
         		header('Location: index.php?components=fin&action=chque_return&message='.$message.'&re=fail');
      break;
      
      case "rtnchque_pending" :
         include_once  'components/manager/modle/managerModule.php';
         if(setChqRtnSts(0))
         		header('Location: index.php?components=fin&action=chque_return&message='.$message.'&re=success');
         	else
         		header('Location: index.php?components=fin&action=chque_return&message='.$message.'&re=fail');
      break;
      
      case "rtnchque_delete" :
         include_once  'components/manager/modle/managerModule.php';
         if(setChqRtnSts(2))
         		header('Location: index.php?components=fin&action=chque_return&message='.$message.'&re=success');
         	else
         		header('Location: index.php?components=fin&action=chque_return&message='.$message.'&re=fail');
      break;
      
      //-----------------------------Chque_Postpone------------------------------------//
      case "chque_postpone":
         include_once  'components/manager/modle/managerModule.php';
         getChqueNo('all', 1);
         getPostponedChque('all');
         getChqueOne();
         include_once  'components/manager/view/chque_postpone.php';
      break;

      case "chque_set_postpone":
         include_once  'components/manager/modle/managerModule.php';
         if (setChquePostpone($_POST['case']))
            header('Location: index.php?components=fin&action=chque_postpone&chque_no=' . $py_chqnofull . '&message=' . $message . '&re=success');
         else
            header('Location: index.php?components=fin&action=chque_postpone&chque_no=' . $py_chqnofull . '&message=' . $message . '&re=fail');
      break;

      case "moveto_postpone":
         include_once  'components/manager/modle/managerModule.php';
         if (moveToPostpone())
            header('Location: index.php?components=fin&action=chque_postpone&message=' . $message . '&re=success');
         else
            header('Location: index.php?components=fin&action=chque_postpone&message=' . $message . '&re=fail');
      break;

      case "fullclear_postpone":
         include_once  'components/manager/modle/managerModule.php';
         if (fullClearPostpone())
            header('Location: index.php?components=fin&action=chque_postpone&message=' . $message . '&re=success');
         else
            header('Location: index.php?components=fin&action=chque_postpone&message=' . $message . '&re=fail');
      break;
      //--------------------------------------------------------------------------//
      // case "chque" :
      //    include_once  'components/manager/modle/managerModule.php';
      //    getStore('all');
      //    getSalesman('all');
      //    getChqueData();
      //    getBankAccounts();
      //    include_once  'components/manager/view/chque.php';
      // break;
      case "chque_pending_finalyze" :
         include_once  'components/manager/modle/managerModule.php';
         getStore('all');
         getSalesman('all');
         getChqueData('all');
         getBankAccounts();
         include_once  'components/manager/view/chque_pending_finalyze.php';
      break;
      
      // case "chque_range" :
      //    include_once  'components/manager/modle/managerModule.php';
      //    getStore('all');
      //    getSalesman('all');
      //    getChqueRange();
      //    include_once  'components/manager/view/chque_range.php';
      // break;

      case "chque_realize_report_onedate":
         include_once  'components/manager/modle/managerModule.php';
         getStore('all');
         getSalesman('all');
         getSubSystems3('all');
         getChqueData('all');
         getBankAccounts();
            include_once  'components/manager/view/chque_realize_report_onedate.php';
      break;

      case "chque_realize_report_daterange":
         include_once  'components/manager/modle/managerModule.php';
         getStore('all');
         getSalesman('all');
         getSubSystems3('all');
         getChqueRange('all');
         include_once  'components/manager/view/chque_realize_report_daterange.php';
      break;
      
      // update by nirmal 21_12_2
      case "clear_chque_list" :
         include_once  'components/manager/modle/managerModule.php';
         getClearedChques();
         getBankAccounts();
         include_once  'components/manager/view/chque_clear.php';
      break;
      
      // updated by nirmal 21_12_16
      case "clear_chque" :
         include_once  'components/manager/modle/managerModule.php';
            print clearChque();
      break;
      
      //-------------------Quotation-----------------------------------//
      case "set_district" :
         include_once  'components/billing/modle/billingModule.php';
         setDistrict();
         header('Location: index.php?components=fin&action=quotation');
      break;
      
      case "quotation" :
         include_once  'components/billing/modle/billingModule.php';
         include_once  'components/supervisor/modle/supervisorModule.php';
         getDistrict();
         getQuotationItems();    
         if(isset($_COOKIE['district'])){
            getItems($item_filter,$sub_system,$systemid);
            getCust(1); 
            if(isset($_GET['cust'])){
            		if(validateQuotNo()) header('Location: index.php?components=fin&action=new_quot&cust_id='.$_GET['cust'].'&att=');
            }
         }
         include_once  'components/supervisor/view/quotation.php';
      break;
      
      case "new_quot" :
         include_once  'components/supervisor/modle/supervisorModule.php';
         if(newQuot($_REQUEST['cust_id'],$_REQUEST['att']))
         		header('Location: index.php?components=fin&action=quotation&id='.$quot_no.'&s='.$salesman.'&cust='.$cust);
         	else
         		header('Location: index.php?components=fin&action=quotation&message='.$message.'&re=fail');
      break;
      
      case "apend_quot" :
         include_once  'components/billing/modle/billingModule.php';
         include_once  'components/supervisor/modle/supervisorModule.php';
         if(apendQuot()){
         		header('Location: index.php?components=fin&action=quotation&id='.$quot_no.'&s='.$salesman.'&cust='.$cust.'&message='.$message.'&re=success');
         	}else{
         		header('Location: index.php?components=fin&action=quotation&id='.$quot_no.'&s='.$salesman.'&cust='.$cust.'&message='.$message.'&re=fail');
         	}
      break;
      
      case "qo_item_gpdate" :
         include_once  'components/supervisor/modle/supervisorModule.php';
         if(updateQuot()){
         		header('Location: index.php?components=fin&action=quotation&id='.$quot_no.'&s='.$salesman.'&cust='.$cust.'&message='.$message.'&re=success');
         	}else{
         		header('Location: index.php?components=fin&action=quotation&id='.$quot_no.'&s='.$salesman.'&cust='.$cust.'&message='.$message.'&re=fail');
         	}
      break;
      
      case "qo_item_remove" :
         include_once  'components/supervisor/modle/supervisorModule.php';
         if(removeQuot()){
         		header('Location: index.php?components=fin&action=quotation&id='.$quot_no.'&s='.$salesman.'&cust='.$cust.'&message='.$message.'&re=success');
         	}else{
         		header('Location: index.php?components=fin&action=quotation&id='.$quot_no.'&s='.$salesman.'&cust='.$cust.'&message='.$message.'&re=fail');
         	}
      break;
      
      case "qo_terms" :
         include_once  'components/supervisor/modle/supervisorModule.php';
         getQOTerms();
         getDetaultTerms();
         include_once  'components/supervisor/view/quotation_terms.php';
      break;
      
      case "set_qo_terms" :
         include_once  'components/supervisor/modle/supervisorModule.php';
         if(setQuotTerms())
         		header('Location: index.php?components=fin&action=set_quot_status&id='.$quot_no.'&new_status=2');
         	else
         		header('Location: index.php?components=fin&action=qo_terms&id='.$quot_no.'&message='.$message.'&re=fail');
      break;
      
      case "set_quot_status" :
         include_once  'components/supervisor/modle/supervisorModule.php';
         if(setQuotStatus($_GET['new_status']))
         		header('Location: index.php?components=fin&action=qo_finish&id='.$quot_no);
         	else
         		header('Location: index.php?components=fin&action=qo_finish&id='.$quot_no.'&message='.$message.'&re=fail');
      break;
      
      case "qo_revise" :
         include_once  'components/supervisor/modle/supervisorModule.php';
         if(qoRevise())
         		header('Location: index.php?components=fin&action=qo_finish&id='.$quot_no);
         	else
         		header('Location: index.php?components=fin&action=qo_finish&id='.$quot_no.'&message='.$message.'&re=fail');
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
         include_once  'components/supervisor/view/quotation_list.php';
      break;
      
      case "qo_complete_check" :
         include_once  'components/supervisor/modle/supervisorModule.php';
         print qoCompleteCheck();
      break;
      
      case "quotation_ongoing" :
         include_once  'components/supervisor/modle/supervisorModule.php';
         getOnGoing();
         include_once  'components/supervisor/view/quotation_ongoing.php';
      break;
      
      case "search_quot" :
         include_once  'components/supervisor/modle/supervisorModule.php';
          if(searchQuot($_POST['search1']))
         		header('Location: index.php?components=fin&action=qo_finish&id='.$_POST['search1']);
         	else
         		header('Location: index.php?components=fin&action=quotation&message=Invalid%20Quotation%20Number&re=fail');
      break;
      
      case "qo_add_image" :
         include_once  'components/supervisor/modle/supervisorModule.php';
          if(qoAddImage())
         		header('Location: index.php?components=fin&action=qo_finish&id='.$_GET['id'].'&message='.$message.'&re=success');
         	else
         		header('Location: index.php?components=fin&action=qo_finish&id='.$_GET['id'].'&message='.$message.'&re=fail');
      break;
      
      case "qo_delete_image" :
         include_once  'components/supervisor/modle/supervisorModule.php';
          if(qoDeleteImage())
         		header('Location: index.php?components=fin&action=qo_finish&id='.$_GET['id'].'&message='.$message.'&re=success');
         	else
         		header('Location: index.php?components=fin&action=qo_finish&id='.$_GET['id'].'&message='.$message.'&re=fail');
      break;
      
      case "qo_img_height" :
         include_once  'components/supervisor/modle/supervisorModule.php';
          if(qoImgHeight())
         		header('Location: index.php?components=fin&action=qo_finish&id='.$_GET['id'].'&message='.$message.'&re=success');
         	else
         		header('Location: index.php?components=fin&action=qo_finish&id='.$_GET['id'].'&message='.$message.'&re=fail');
      break;

      case "qo_add_note" :
         include_once  'components/supervisor/modle/supervisorModule.php';
         if(qoAddNote())
         		header('Location: index.php?components=fin&action=qo_finish&id='.$quot_no);
         	else
         		header('Location: index.php?components=fin&action=qo_finish&id='.$quot_no.'&message='.$message.'&re=fail');
      break;
      
      case "qo_update_note" :
         include_once  'components/supervisor/modle/supervisorModule.php';
         if(qoUpdateNote())
         		header('Location: index.php?components=fin&action=qo_finish&id='.$quot_no.'&message='.$message.'&re=success');
         	else
         		header('Location: index.php?components=fin&action=qo_finish&id='.$quot_no.'&message='.$message.'&re=fail');
      break;

      case "quotation_report" :
         include_once  'components/supervisor/modle/supervisorModule.php';
	   getFilter($sub_system);
	   getCustSup($sub_system);
         getReportNote($sub_system);
         include_once  'components/supervisor/view/quotation_report.php';
      break;

      default:
          print '<p><srtong>Bad Request</strong></p>';
      break;
   }
?>