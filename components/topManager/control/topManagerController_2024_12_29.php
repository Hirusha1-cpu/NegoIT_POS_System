<?php
if(passwordExpire()) header('Location: index.php?components=authenticate&action=change_pw&message=Your%20Password%20Has%20Expired.%20Please%20Change%20it%20NOW&re=fail');
   switch ($_REQUEST['action']){
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
         getCust('all','0,1,2');
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

      case "sales_report2" :
         include_once  'components/manager/modle/managerModule.php';
         getCust('all','1');
         getStore('all');
         getSalesReport2('all');
         getCategory();
         getFilter('all');
         if(isMobile())
            include_once  'components/manager/view/m_sales_report2.php';
         else
            include_once  'components/manager/view/sales_report2.php';
      break;

      case "sales_summary" :
         include_once  'components/manager/modle/managerModule.php';
         getStore('all');
         getCategory();
         getSalesSummary('all');
         if(isMobile())
            include_once  'components/manager/view/m_sales_summary.php';
         else
            include_once  'components/manager/view/sales_summary.php';
      break;

      case "sales_summary_detail" :
         include_once  'components/manager/modle/managerModule.php';
         getStore('all');
         getCategory();
         getSalesSummaryDetail('all');
         if(isMobile())
            include_once  'components/manager/view/m_sales_summary_detail.php';
         else
            include_once  'components/manager/view/sales_summary_detail.php';
      break;

      case "sales_bycategory" :
         include_once  'components/manager/modle/managerModule.php';
         salesByCategory('all');
         if(isMobile())
            include_once  'components/manager/view/m_sales_by_category.php';
         else
            include_once  'components/manager/view/sales_by_category.php';
      break;

      case "sales_byrep" :
         include_once  'components/manager/modle/managerModule.php';
         getCategory();
         getStore("all");
         salesByRep("all");
         if(isMobile())
            include_once  'components/manager/view/m_sales_by_rep.php';
         else
            include_once  'components/manager/view/sales_by_rep.php';
      break;

      case "sales_report3" :
         include_once  'components/manager/modle/managerModule.php';
         getSalesReport3('all');
         getCategory();
         if(isMobile())
            include_once  'components/manager/view/m_sales_report3.php';
        else
            include_once  'components/manager/view/sales_report3.php';
      break;

      case "repair_income" :
         include_once  'components/manager/modle/managerModule.php';
         getRepairIncome('all');
         if(isMobile())
            include_once  'components/manager/view/m_repair_income.php';
        else
            include_once  'components/manager/view/repair_income.php';
      break;

      case "repair_income_one" :
         include_once  'components/manager/modle/managerModule.php';
         getRepairIncomeOne('all');
         if(isMobile())
            include_once  'components/manager/view/m_repair_income_one.php';
        else
            include_once  'components/manager/view/repair_income_one.php';
      break;
      case "export_unic_list" :
         include_once  'components/manager/modle/managerModule.php';
         getSalesReport4('all');
      break;

      case "unvisited" :
         include_once  'components/manager/modle/managerModule.php';
         getSalesman('all');
         getUnvisited('all');
         if(isMobile())
            include_once  'components/manager/view/m_unvisited.php';
        else
            include_once  'components/manager/view/unvisited.php';
      break;

      case "sold_qty" :
         include_once  'components/manager/modle/managerModule.php';
         getStore('all');
         getSoldQty('all', $_GET['components']);
         getCategory();
         if(isMobile())
            include_once  'components/manager/view/m_sold_qty.php';
        else
            include_once  'components/manager/view/sold_qty.php';
      break;

      case "credit" :
         include_once  'components/supervisor/modle/supervisorModule.php';
         getCreditData($_GET['sub_system']);
         getFilter('all');
         getSubSystems();
         if(isMobile())
         		include_once  'components/supervisor/view/m_credit_view.php';
         else
         		include_once  'components/supervisor/view/credit_view.php';
      break;

      case "disabledcust" :
         include_once  'components/manager/modle/managerModule.php';
        getCust('all','0');
        getSalesman($sub_system);
        getStore($sub_system);
        getCustGroups($sub_system);
        getTown();
         getCust2('1');
         getOneCust('name','all');
         getSubSystems2();
         if(isMobile())
         		include_once  'components/manager/view/m_manageCust.php';
         else
         		include_once  'components/manager/view/manageCust.php';
      break;

      case "newcust" :
         include_once  'components/manager/modle/managerModule.php';
        getCust('all','1,3');
        getSalesman('all');
        getStore('all');
        getCustGroups('all');
        getTown();
         getCust2('1');
         getOneCust('name','all');
         getSubSystems2();
         if(isMobile())
         		include_once  'components/manager/view/m_manageCust.php';
         else
         		include_once  'components/manager/view/manageCust.php';
      break;

      case "editcust" :
         include_once  'components/manager/modle/managerModule.php';
        getCust('all','1,3');
        getSalesman('all');
        getStore('all');
        getCustGroups('all');
        getTown();
        getOneCust('id','all');
         getSubSystems2();
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
	      print cust2Ajax('name', $sub_system);
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

      case "more_cust" :
         include_once  'template/common.php';
         print moreCust($sub_system);
      break;

      case "searchcust" :
         include_once  'components/manager/modle/managerModule.php';
        getCustSearchList($sub_system);
        getSalesman('all');
        getStore('all');
        getCustGroups('all');
        getTown();
        getOneCust('name','all');
         getSubSystems2();
         if(isMobile())
         		include_once  'components/manager/view/m_manageCust.php';
         else
         		include_once  'components/manager/view/manageCust.php';
      break;

      case "cust_details" :
         include_once  'components/manager/modle/managerModule.php';
        getSalesman('all');
        getStore('all');
        getOneCust('id','all');
         if(isMobile())
         		include_once  'components/billing/view/m_cust.php';
         	else
         		include_once  'components/billing/view/cust.php';
      break;

   	case "add_cust":
      include_once  'components/manager/modle/managerModule.php';
      print addCust($systemid);
      break;

      // added by E.S.P Nirmal 2021_06_14
   	case "nic-check":
      include_once  'components/manager/modle/managerModule.php';
      print nicCheckAjax($sub_system);
      break;

      // added by E.S.P Nirmal 2021_06_14
   	case "mobile-check":
      include_once  'components/manager/modle/managerModule.php';
      print mobileCheckAjax($sub_system);
   	break;

      // added by nirmal 28_04_2022
      case "cust-check":
         include_once  'components/manager/modle/managerModule.php';
         print custCheckAjax($sub_system);
      break;

      // added by E.S.P Nirmal 2021_06_02
   	case "add_cust_image":
      include_once  'components/manager/modle/managerModule.php';
      if (addCustImage($systemid))
         header('Location: index.php?components=topmanager&action=newcust&message='.$message.'&re=success');
      else
         header('Location: index.php?components=topmanager&action=newcust&message='.$message.'&re=fail');
   	break;

      case "update_cust" :
         include_once  'components/manager/modle/managerModule.php';
         if(updateCust())
         		header('Location: index.php?components=topmanager&action=newcust&message='.$message.'&re=success');
         	else
         		header('Location: index.php?components=topmanager&action=newcust&message='.$message.'&re=fail');
      break;

      case "delete_cust" :
         include_once  'components/manager/modle/managerModule.php';
         if(deleteCust())
         		header('Location: index.php?components=topmanager&action=newcust&message='.$message.'&re=success');
         	else
         		header('Location: index.php?components=topmanager&action=newcust&message='.$message.'&re=fail');
      break;

      case "disable_cust" :
         include_once  'components/manager/modle/managerModule.php';
         if(setStatusCust(0))
         		header('Location: index.php?components=topmanager&action=newcust&message='.$message.'&re=success');
         	else
         		header('Location: index.php?components=topmanager&action=newcust&message='.$message.'&re=fail');
      break;

      case "enbale_cust" :
         include_once  'components/manager/modle/managerModule.php';
         if(setStatusCust(1))
         		header('Location: index.php?components=topmanager&action=newcust&message='.$message.'&re=success');
         	else
         		header('Location: index.php?components=topmanager&action=newcust&message='.$message.'&re=fail');
      break;

      case "show_custgroup" :
         include_once  'components/manager/modle/managerModule.php';
        getCustGroups('all');
         if(isMobile())
         		include_once  'components/manager/view/m_custGroup.php';
         else
         		include_once  'components/manager/view/custGroup.php';
      break;

      case "edit_custgroup" :
         include_once  'components/manager/modle/managerModule.php';
        getCustGroups('all');
         if(isMobile())
         		include_once  'components/manager/view/m_custGroup.php';
         else
         		include_once  'components/manager/view/custGroup.php';
      break;

      case "add_custgroup" :
         include_once  'components/manager/modle/managerModule.php';
         if(addCustGroup())
         		header('Location: index.php?components=topmanager&action=show_custgroup&message='.$message.'&re=success');
         	else
         		header('Location: index.php?components=topmanager&action=show_custgroup&message='.$message.'&re=fail');
      break;

      case "update_custgroup" :
         include_once  'components/manager/modle/managerModule.php';
         if(updateCustGroup())
         		header('Location: index.php?components=topmanager&action=show_custgroup&message='.$message.'&re=success');
         	else
         		header('Location: index.php?components=topmanager&action=show_custgroup&message='.$message.'&re=fail');
      break;

      case "delete_custgroup" :
         include_once  'components/manager/modle/managerModule.php';
         if(deleteCustGroup())
         		header('Location: index.php?components=topmanager&action=show_custgroup&message='.$message.'&re=success');
         	else
         		header('Location: index.php?components=topmanager&action=show_custgroup&message='.$message.'&re=fail');
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
         		header('Location: index.php?components=topmanager&action=show_custtown&message='.$message.'&re=success');
         	else
         		header('Location: index.php?components=topmanager&action=show_custtown&message='.$message.'&re=fail');
      break;

      case "update_custtown" :
         include_once  'components/manager/modle/managerModule.php';
         if(updateCustTown())
         		header('Location: index.php?components=topmanager&action=show_custtown&message='.$message.'&re=success');
         	else
         		header('Location: index.php?components=topmanager&action=show_custtown&message='.$message.'&re=fail');
      break;

      case "delete_custtown" :
         include_once  'components/manager/modle/managerModule.php';
         if(deleteCustTown())
         		header('Location: index.php?components=topmanager&action=show_custtown&message='.$message.'&re=success');
         	else
         		header('Location: index.php?components=topmanager&action=show_custtown&message='.$message.'&re=fail');
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
         		header('Location: index.php?components=topmanager&action=chque_return&message='.$message.'&re=success');
         	else
         		header('Location: index.php?components=topmanager&action=chque_return&message='.$message.'&re=fail');
      break;

      case "rtnchque_pending" :
         include_once  'components/manager/modle/managerModule.php';
         if(setChqRtnSts(0))
         		header('Location: index.php?components=topmanager&action=chque_return&message='.$message.'&re=success');
         	else
         		header('Location: index.php?components=topmanager&action=chque_return&message='.$message.'&re=fail');
      break;

      case "rtnchque_delete" :
         include_once  'components/manager/modle/managerModule.php';
         if(setChqRtnSts(2))
         		header('Location: index.php?components=topmanager&action=chque_return&message='.$message.'&re=success');
         	else
         		header('Location: index.php?components=topmanager&action=chque_return&message='.$message.'&re=fail');
      break;

      //-----------------------------Chque_Postpone------------------------------------//
      case "chque_postpone" :
         include_once  'components/manager/modle/managerModule.php';
        getChqueNo('all',1);
        getPostponedChque('all');
        getChqueOne();
         include_once  'components/manager/view/chque_postpone.php';
      break;

      case "chque_set_postpone" :
         include_once  'components/manager/modle/managerModule.php';
         if(setChquePostpone($_POST['case']))
         		header('Location: index.php?components=topmanager&action=chque_postpone&chque_no='.$py_chqnofull.'&message='.$message.'&re=success');
         	else
         		header('Location: index.php?components=topmanager&action=chque_postpone&chque_no='.$py_chqnofull.'&message='.$message.'&re=fail');
      break;

      case "moveto_postpone" :
         include_once  'components/manager/modle/managerModule.php';
         if(moveToPostpone())
         		header('Location: index.php?components=topmanager&action=chque_postpone&message='.$message.'&re=success');
         	else
         		header('Location: index.php?components=topmanager&action=chque_postpone&message='.$message.'&re=fail');
      break;

      case "fullclear_postpone" :
         include_once  'components/manager/modle/managerModule.php';
         if(fullClearPostpone())
         		header('Location: index.php?components=topmanager&action=chque_postpone&message='.$message.'&re=success');
         	else
         		header('Location: index.php?components=topmanager&action=chque_postpone&message='.$message.'&re=fail');
      break;

      //-----------------------------Return-------------------------------------//
      case "show_return_summary" :
         include_once  'components/manager/modle/managerModule.php';
         getSalesman('all');
         getCust2(1);
         getReturnSummary();
         include_once  'components/manager/view/return_summary.php';
      break;

      case "show_return" :
         include_once  'components/manager/modle/managerModule.php';
         getSalesman('all');
         getCust2(1);
        getReturn('all');
         include_once  'components/manager/view/return.php';
      break;

      //-----------------------------Disposal---------------------------------------//
      case "show_disposal" :
         include_once  'components/manager/modle/managerModule.php';
        getDisposal();
         include_once  'components/manager/view/disposal.php';
      break;

      case "move_disposal" :
         include_once  'components/manager/modle/managerModule.php';
         if(moveDisposal())
         		header('Location: index.php?components=topmanager&action=show_disposal&year='.$_GET['year'].'&message='.$message.'&re=success');
         	else
         		header('Location: index.php?components=topmanager&action=show_disposal&year='.$_GET['year'].'&message='.$message.'&re=fail');
      break;
      //-----------------------------Device MGMT-------------------------------------//
      case "device_mgmt" :
         include_once  'components/manager/modle/managerModule.php';
        getDevices('all');
         if(isMobile())
         		include_once  'components/manager/view/m_device_mgmt.php';
         else
         		include_once  'components/manager/view/device_mgmt.php';
      break;

      case "device_register" :
         include_once  'components/manager/modle/managerModule.php';
         if(registerDevice())
         		header('Location: index.php?components=topmanager&action=device_mgmt&message='.$message.'&re=success');
         	else
         		header('Location: index.php?components=topmanager&action=device_mgmt&message='.$message.'&re=fail');
      break;

      case "device_unregister" :
         include_once  'components/manager/modle/managerModule.php';
         if(unregisterDevice())
         		header('Location: index.php?components=topmanager&action=device_mgmt&message='.$message.'&re=success');
         	else
         		header('Location: index.php?components=topmanager&action=device_mgmt&message='.$message.'&re=fail');
      break;

      case "unlocked" :
         include_once  'components/manager/modle/managerModule.php';
         getUnlockedBills('all');
         if(isMobile())
         		include_once  'components/manager/view/m_unlocked.php';
         else
         		include_once  'components/manager/view/unlocked.php';
      break;

      // added by nirmal 11_07_2023
      case "temporary_bills":
         include_once  'components/manager/modle/managerModule.php';
         getTemporaryBills('all');
         if (isMobile())
            include_once  'components/manager/view/m_temporary_bills.php';
         else
            include_once  'components/manager/view/temporary_bills.php';
      break;

      //-----------------------------Unic Item-------------------------------------//
	   case "unic_items":
	      include_once  'components/manager/modle/managerModule.php';
	      getUnicItems();
	      include_once  'components/manager/view/unic_item.php';
	   break;

      case "sn_lookup":
      include_once  'components/manager/modle/managerModule.php';
      getStore($sub_system);
      if (isMobile())
         include_once  'components/manager/view/m_sn_lookup.php';
      else
         include_once  'components/manager/view/sn_lookup.php';
      break;

	   case "sn_lookup_list":
	      include_once  'components/manager/modle/managerModule.php';
	      print snLookupList();
	   break;

	   case "desc-list":
	      include_once  'template/common.php';
	      listItem($sub_system);
	      include_once  'template/ajax_list.php';
	   break;

	   case "sn-list-all":
	      include_once  'components/manager/modle/managerModule.php';
	      snListAll();
	      include_once  'template/ajax_list.php';
	   break;

      //-----------------------------Shipment------------------------------------//
      case "shipment" :
         include_once  'components/manager/modle/managerModule.php';
         getShipmentList();
         getShipmentOne();
   	   include_once  'components/manager/view/shipment.php';
      break;

      case "add_ship_payment" :
         include_once  'components/manager/modle/managerModule.php';
         if(addShipPayment($_GET['case']))
         		header('Location: index.php?components=topmanager&action=shipment&id='.$shipment_no.'&message='.$message.'&re=success');
         	else
         		header('Location: index.php?components=topmanager&action=shipment&id='.$shipment_no.'&message='.$message.'&re=fail');
      break;

      case "delete_ship_payment" :
         include_once  'components/manager/modle/managerModule.php';
         if(deleteShipPayment())
         		header('Location: index.php?components=topmanager&action=shipment&id='.$shipment_no.'&message='.$message.'&re=success');
         	else
         		header('Location: index.php?components=topmanager&action=shipment&id='.$shipment_no.'&message='.$message.'&re=fail');
      break;

      //-----------------------------Chque------------------------------------//
      case "chque_pending_finalyze" :
         include_once  'components/manager/modle/managerModule.php';
         getStore('all');
         getSalesman('all');
         getChqueData('all');
         getBankAccounts();
         if (isMobile())
            include_once  'components/manager/view/m_chque_pending_finalyze.php';
         else
            include_once  'components/manager/view/chque_pending_finalyze.php';
      break;

      case "chque_realize_report_onedate" :
         include_once  'components/manager/modle/managerModule.php';
         getStore('all');
         getSalesman('all');
         getSubSystems3('all');
         getChqueData('all');
         getBankAccounts();
         if (isMobile())
            include_once  'components/manager/view/m_chque_realize_report_onedate.php';
         else
            include_once  'components/manager/view/chque_realize_report_onedate.php';
      break;

      case "chque_realize_report_daterange":
         include_once  'components/manager/modle/managerModule.php';
         getStore('all');
         getSalesman('all');
         getSubSystems3('all');
         getChqueRange('all');
         if (isMobile())
            include_once  'components/manager/view/m_chque_realize_report_daterange.php';
         else
            include_once  'components/manager/view/chque_realize_report_daterange.php';
      break;

      // updated by nirmal 21_12_2
      case "clear_chque_list" :
         include_once  'components/manager/modle/managerModule.php';
         getClearedChques();
         getBankAccounts();
         include_once  'components/manager/view/chque_clear.php';
      break;

      case "clear_chque" :
         include_once  'components/manager/modle/managerModule.php';
         if(clearChque())
         		header('Location: index.php?components=topmanager&action=chque&message='.$message.'&re=success');
         	else
         		header('Location: index.php?components=topmanager&action=chque&message='.$message.'&re=fail');
      break;
      //-----------------------------Authorize Code------------------------------------//
      case "authorize_code" :
         include_once  'components/manager/modle/managerModule.php';
         getAuthorizeCodelist();
         if(isMobile())
         		include_once  'components/manager/view/m_authorize.php';
         else
         		include_once  'components/manager/view/authorize.php';
      break;

      case "get_authorize" :
         include_once  'components/billing/modle/billingModule.php';
         print getAuthorize();
      break;
      //-----------------------------TOP MANAGER------------------------------------//

      case "manage_user" :
         include_once  'components/settings/modle/settingsModule.php';
         getUsers();
         getBanks();
         include_once  'components/settings/view/manageUsers.php';
      break;

      case "edit_user" :
         include_once  'components/settings/modle/settingsModule.php';
         getUsers();
         getBanks();
         getOneUser();
         include_once  'components/settings/view/manageUsers.php';
      break;

      case "add_user" :
         include_once  'components/settings/modle/settingsModule.php';
         if(addUser())
         		header('Location: index.php?components=topmanager&action=manage_user&message='.$message.'&re=success');
         	else
         		header('Location: index.php?components=topmanager&action=manage_user&message='.$message.'&re=fail');
      break;

      case "update_user" :
         include_once  'components/settings/modle/settingsModule.php';
         if(updateUser())
         		header('Location: index.php?components=topmanager&action=manage_user&message='.$message.'&re=success');
         	else
         		header('Location: index.php?components=topmanager&action=manage_user&message='.$message.'&re=fail');
      break;

      case "update_permission" :
         include_once  'components/settings/modle/settingsModule.php';
         if(updatePermission())
         		header('Location: index.php?components=topmanager&action=manage_user&message='.$message.'&re=success');
         	else
         		header('Location: index.php?components=topmanager&action=manage_user&message='.$message.'&re=fail');
      break;

      case "update_timecheck" :
         include_once  'components/settings/modle/settingsModule.php';
         if(updateTimecheck())
         		header('Location: index.php?components=topmanager&action=manage_user&message='.$message.'&re=success');
         	else
         		header('Location: index.php?components=topmanager&action=manage_user&message='.$message.'&re=fail');
      break;

      case "update_devicecheck" :
         include_once  'components/settings/modle/settingsModule.php';
         if(updateDevicecheck())
         		header('Location: index.php?components=topmanager&action=manage_user&message='.$message.'&re=success');
         	else
         		header('Location: index.php?components=topmanager&action=manage_user&message='.$message.'&re=fail');
      break;

      case "update_storeaso" :
         include_once  'components/settings/modle/settingsModule.php';
         if(updateStoreaso())
         		header('Location: index.php?components=topmanager&action=manage_user&message='.$message.'&re=success');
         	else
         		header('Location: index.php?components=topmanager&action=manage_user&message='.$message.'&re=fail');
      break;

      case "update_mapinv" :
         include_once  'components/settings/modle/settingsModule.php';
         if(updateMapInv())
         		header('Location: index.php?components=topmanager&action=manage_user&message='.$message.'&re=success');
         	else
         		header('Location: index.php?components=topmanager&action=manage_user&message='.$message.'&re=fail');
      break;

      case "disable_user" :
         include_once  'components/settings/modle/settingsModule.php';
         if(setStatusUser(1))
         		header('Location: index.php?components=topmanager&action=manage_user&message='.$message.'&re=success');
         	else
         		header('Location: index.php?components=topmanager&action=manage_user&message='.$message.'&re=fail');
      break;

      case "enbale_user" :
         include_once  'components/settings/modle/settingsModule.php';
         if(setStatusUser(0))
         		header('Location: index.php?components=topmanager&action=manage_user&message='.$message.'&re=success');
         	else
         		header('Location: index.php?components=topmanager&action=manage_user&message='.$message.'&re=fail');
      break;
      //-----------------------------Payment------------------------------------//
      case "payment" :
         include_once  'components/topManager/modle/topManagerModule.php';
         getPaymentData();
         getBank2();
         getOnePayment();
         include_once  'components/topManager/view/payment.php';
      break;

      case "payment_history" :
         include_once  'components/topManager/modle/topManagerModule.php';
	   getSubSystems();
         getPaymentHistory();
         include_once  'components/topManager/view/payment_history.php';
      break;

      case "set_status_payment" :
         include_once  'components/topManager/modle/topManagerModule.php';
         if(setStatusPayment())
         		header('Location: index.php?components=topmanager&action=payment&message='.$message.'&re=success');
         	else
         		header('Location: index.php?components=topmanager&action=payment&message='.$message.'&re=fail');
      break;
   //-------------------------INV MGMT----------------------------------------------//
      case "inv_mgmt" :
         include_once  'components/manager/modle/managerModule.php';
         searchInv('all');
         getSalesman('all');
         include_once  'components/manager/view/inv_mgmt.php';
      break;

      case "inv_mgmt_changesm" :
         include_once  'components/manager/modle/managerModule.php';
         if(changeSalesman())
         		header('Location: index.php?components=manager&action=inv_mgmt&type='.$type.'&id='.$id.'&message='.$message.'&re=success');
         	else
         		header('Location: index.php?components=manager&action=inv_mgmt&type='.$type.'&id='.$id.'&message='.$message.'&re=fail');
      break;
   //-------------------------Quotation----------------------------------------------//
      case "set_district" :
         include_once  'components/billing/modle/billingModule.php';
         setDistrict();
         header('Location: index.php?components=topmanager&action=quotation');
      break;

      case "quotation_approve" :
         include_once  'components/manager/modle/managerModule.php';
         pendingQuot($sub_system);
         include_once  'components/manager/view/quotation_app.php';
      break;

      case "quotation" :
         include_once  'components/billing/modle/billingModule.php';
         include_once  'components/supervisor/modle/supervisorModule.php';
         getDistrict();
         getQuotationItems();
         if(isset($_COOKIE['district'])){
            getItems($item_filter,$sub_system,$systemid);
            getCust(1,'1');
            if(isset($_GET['cust'])){
            		if(validateQuotNo()) header('Location: index.php?components=topmanager&action=new_quot&cust_id='.$_GET['cust'].'&validity=30');
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
         		header('Location: index.php?components=topmanager&action=quotation&id='.$quot_no.'&s='.$salesman.'&cust='.$cust);
         	else
         		header('Location: index.php?components=topmanager&action=quotation&message='.$message.'&re=fail');
      break;

      case "apend_quot" :
         include_once  'components/billing/modle/billingModule.php';
         include_once  'components/supervisor/modle/supervisorModule.php';
         if(apendQuot()){
         		header('Location: index.php?components=topmanager&action=quotation&id='.$quot_no.'&s='.$salesman.'&cust='.$cust.'&message='.$message.'&re=success');
         	}else{
         		header('Location: index.php?components=topmanager&action=quotation&id='.$quot_no.'&s='.$salesman.'&cust='.$cust.'&message='.$message.'&re=fail');
         	}
      break;

      case "qo_item_gpdate" :
         include_once  'components/supervisor/modle/supervisorModule.php';
         if(updateQuot()){
         		header('Location: index.php?components=topmanager&action=quotation&id='.$quot_no.'&s='.$salesman.'&cust='.$cust.'&message='.$message.'&re=success');
         	}else{
         		header('Location: index.php?components=topmanager&action=quotation&id='.$quot_no.'&s='.$salesman.'&cust='.$cust.'&message='.$message.'&re=fail');
         	}
      break;

      case "qo_item_remove" :
         include_once  'components/supervisor/modle/supervisorModule.php';
         if(removeQuot()){
         		header('Location: index.php?components=topmanager&action=quotation&id='.$quot_no.'&s='.$salesman.'&cust='.$cust.'&message='.$message.'&re=success');
         	}else{
         		header('Location: index.php?components=topmanager&action=quotation&id='.$quot_no.'&s='.$salesman.'&cust='.$cust.'&message='.$message.'&re=fail');
         	}
      break;

      // added by nirmal 25_10_2023
      case "qo_item_update_unit_price":
         include_once  'components/supervisor/modle/supervisorModule.php';
         if (updateQuotItemUnitPrice()) {
            header('Location: index.php?components=topmanager&action=quotation&id=' . $quot_no . '&s=' . $salesman . '&cust=' . $cust . '&message=' . $message . '&re=success');
         } else {
            header('Location: index.php?components=topmanager&action=quotation&id=' . $quot_no . '&s=' . $salesman . '&cust=' . $cust . '&message=' . $message . '&re=fail');
         }
      break;

      // added by nirmal 25_10_2023
      case "qo_update_discount":
         include_once  'components/supervisor/modle/supervisorModule.php';
         if (updateQuotDiscount()) {
            header('Location: index.php?components=topmanager&action=quotation&id=' . $quot_no . '&s=' . $salesman . '&cust=' . $cust . '&message=' . $message . '&re=success');
         } else {
            header('Location: index.php?components=topmanager&action=quotation&id=' . $quot_no . '&s=' . $salesman . '&cust=' . $cust . '&message=' . $message . '&re=fail');
         }
      break;

      // added by nirmal 25_10_2023
      case "qo_remove_discount":
         include_once  'components/supervisor/modle/supervisorModule.php';
         if (removeQuotDiscount()) {
            header('Location: index.php?components=topmanager&action=quotation&id=' . $quot_no . '&s=' . $salesman . '&cust=' . $cust . '&message=' . $message . '&re=success');
         } else {
            header('Location: index.php?components=topmanager&action=quotation&id=' . $quot_no . '&s=' . $salesman . '&cust=' . $cust . '&message=' . $message . '&re=fail');
         }
      break;

      // added by nirmal 29_07_2024
      case "qo_update_comment":
         include_once  'components/supervisor/modle/supervisorModule.php';
         print updateQuotComment();
      break;

      case "qo_terms" :
         include_once  'components/supervisor/modle/supervisorModule.php';
         getDetaultTerms();
         getQOTerms();
         if (isMobile())
            include_once  'components/supervisor/view/m_quotation_terms.php';
         else
            include_once  'components/supervisor/view/quotation_terms.php';
      break;

      case "set_qo_terms" :
         include_once  'components/supervisor/modle/supervisorModule.php';
         if(setQuotTerms())
         		header('Location: index.php?components=topmanager&action=set_quot_status&id='.$quot_no.'&new_status=2');
         	else
         		header('Location: index.php?components=topmanager&action=qo_terms&id='.$quot_no.'&message='.$message.'&re=fail');
      break;

      case "set_quot_status" :
         include_once  'components/supervisor/modle/supervisorModule.php';
         if(setQuotStatus($_GET['new_status']))
         		header('Location: index.php?components=topmanager&action=qo_finish&id='.$quot_no);
         	else
         		header('Location: index.php?components=topmanager&action=qo_finish&id='.$quot_no.'&message='.$message.'&re=fail');
      break;

      case "qo_revise" :
         include_once  'components/supervisor/modle/supervisorModule.php';
         if(qoRevise())
         		header('Location: index.php?components=topmanager&action=qo_finish&id='.$quot_no);
         	else
         		header('Location: index.php?components=topmanager&action=qo_finish&id='.$quot_no.'&message='.$message.'&re=fail');
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
         getQuotList('all');
         getFilter('all');
         getCustSup('all');
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
         getOnGoing('all');
         if (isMobile())
            include_once  'components/supervisor/view/m_quotation_ongoing.php';
         else
            include_once  'components/supervisor/view/quotation_ongoing.php';
      break;

      case "search_quot" :
         include_once  'components/supervisor/modle/supervisorModule.php';
          if(searchQuot($_POST['search1']))
         		header('Location: index.php?components=topmanager&action=qo_finish&id='.$_POST['search1']);
         	else
         		header('Location: index.php?components=topmanager&action=quotation&message=Invalid%20Quotation%20Number&re=fail');
      break;

      case "qo_add_image" :
         include_once  'components/supervisor/modle/supervisorModule.php';
          if(qoAddImage())
         		header('Location: index.php?components=topmanager&action=qo_finish&id='.$_GET['id'].'&message='.$message.'&re=success');
         	else
         		header('Location: index.php?components=topmanager&action=qo_finish&id='.$_GET['id'].'&message='.$message.'&re=fail');
      break;

      case "qo_delete_image" :
         include_once  'components/supervisor/modle/supervisorModule.php';
          if(qoDeleteImage())
         		header('Location: index.php?components=topmanager&action=qo_finish&id='.$_GET['id'].'&message='.$message.'&re=success');
         	else
         		header('Location: index.php?components=topmanager&action=qo_finish&id='.$_GET['id'].'&message='.$message.'&re=fail');
      break;

      case "qo_img_height" :
         include_once  'components/supervisor/modle/supervisorModule.php';
          if(qoImgHeight())
         		header('Location: index.php?components=topmanager&action=qo_finish&id='.$_GET['id'].'&message='.$message.'&re=success');
         	else
         		header('Location: index.php?components=topmanager&action=qo_finish&id='.$_GET['id'].'&message='.$message.'&re=fail');
      break;

      case "qo_add_note" :
         include_once  'components/supervisor/modle/supervisorModule.php';
         if(qoAddNote())
         		header('Location: index.php?components=topmanager&action=qo_finish&id='.$quot_no);
         	else
         		header('Location: index.php?components=topmanager&action=qo_finish&id='.$quot_no.'&message='.$message.'&re=fail');
      break;

      case "qo_update_note" :
         include_once  'components/supervisor/modle/supervisorModule.php';
         if(qoUpdateNote())
         		header('Location: index.php?components=topmanager&action=qo_finish&id='.$quot_no.'&message='.$message.'&re=success');
         	else
         		header('Location: index.php?components=topmanager&action=qo_finish&id='.$quot_no.'&message='.$message.'&re=fail');
      break;

      case "quotation_report" :
         include_once  'components/supervisor/modle/supervisorModule.php';
	   getFilter('all');
	   getCustSup('all');
         getReportNote('all');
         include_once  'components/supervisor/view/quotation_report.php';
      break;

      case "quotation_sent_with_tax":
         include_once  'components/supervisor/modle/supervisorModule.php';
         if(qoUpdateSentWithTax())
            header('Location: index.php?components=supervisor&action=qo_finish&id='.$quot_no.'&message='.$message.'&re=success');
         else
            header('Location: index.php?components=supervisor&action=qo_finish&id='.$quot_no.'&message='.$message.'&re=fail');
      break;

      default:
          print '<p><srtong>Bad Request</strong></p>';
      break;
   }
?>