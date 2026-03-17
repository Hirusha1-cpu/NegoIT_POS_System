<?php
switch ($_REQUEST['action']){
   // updated by nirmal 18_02_2022
   case "mk_home" :
      include_once  'components/marketing/modle/marketingModule.php';
      getSubSystem();
      getStore($sub_system);
      getGroup($sub_system);
      setActiveSalesman($sub_system);
      getActiveTown();
      getCustomerList();
      if(isMobile())
         include_once  'components/marketing/view/m_home.php';
      else 
      include_once  'components/marketing/view/home.php';
   break;
   
   case "by_sale" :
      include_once  'components/marketing/modle/marketingModule.php';
      getActiveTown();
      getCustomerList2();
      include_once  'components/marketing/view/by_sale.php';
   break;
   
   case "get_cust_more" :
      include_once  'components/marketing/modle/marketingModule.php';
      print getCustMore();
   break;
   
   case "outstanding_sms" :
      include_once  'components/marketing/modle/marketingModule.php';
      print outstandingSMS($sub_system);
   break;

   case "item_check" :
      include_once  'components/marketing/modle/marketingModule.php';

      include_once  'components/marketing/view/item_check.php';
   break;

   case "get_item_id":
      include_once  'components/marketing/modle/marketingModule.php';
      print getItemId($sub_system);
   break;  
 
   case "get_pending_return_items":
      include_once  'components/marketing/modle/marketingModule.php';
      print getPendingReturnItems($sub_system);
   break;  

   case "get_item_history":
      include_once  'components/marketing/modle/marketingModule.php';
      print getItemHistory($sub_system);
   break;    
   //-------------------------External Reports----------------------------------//
   case "cust-list":
      include_once  'template/common.php';
      listCust($sub_system);
      include_once  'template/ajax_list.php';
   break;

   case "more_cust":
      include_once  'template/common.php';
      print moreCust($sub_system);
   break;     

   case "desc-list":
      include_once  'template/common.php';
      listItem($sub_system);
      include_once  'template/ajax_list.php';
   break;

   case "sale" :
      include_once  'components/supervisor/modle/supervisorModule.php';
      dailySale($_GET['store']); 
      $storedisable='';
      $userdisable='';
      getFilter($sub_system);
      if(isMobile())
         include_once  'components/supervisor/view/m_sale_view.php';
      else           
         include_once  'components/supervisor/view/sale_view.php';
   break;
   
   case "cust_sale" :
      include_once  'components/manager/modle/managerModule.php';
      getCust('all','1');
      getCustSale($systemid);
      if(isMobile())
            include_once  'components/manager/view/m_cust_sale.php';
      else       
            include_once  'components/manager/view/cust_sale.php';
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
   
   case "sales_report2" :
      include_once  'components/manager/modle/managerModule.php';
      getCust('all','1');
      getStore('all');
      getSalesReport2('all');
      getCategory();
      if(isMobile())
         include_once  'components/manager/view/m_sales_report2.php';
      else       
         include_once  'components/manager/view/sales_report2.php';
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
   
   case "credit_trend" :
      include_once  'components/report/modle/reportModule.php';
      getSubSystems();
      creditTrend();
      if(isMobile())
            include_once  'components/report/view/m_creditTrend.php';
      else          
            include_once  'components/report/view/creditTrend.php';
   break;
   
   case "catalog" :
      include_once  'components/checkAvailability/modle/availabilityModule.php';
      getDistrict();
      getCust(1,$sub_system);
      getItmes2();
      getCatalog();
      if(isMobile())           
            include_once  'components/checkAvailability/view/m_catalog_view.php';
      else
            include_once  'components/checkAvailability/view/catalog_view.php';
   break;
   
   case "get_discount" :
      include_once  'components/checkAvailability/modle/availabilityModule.php';
      print getDiscount();           
   break;   

   case "sales_by_salesman" :
      include_once  'components/supervisor/modle/supervisorModule.php';
      getSalesBySalesman($_GET['store']);
      $storedisable='';
      $userdisable='';
      getFilter($sub_system);
      include_once  'components/supervisor/view/sales_by_salesman.php';      
   break;

   default:
         print '<p><srtong>Bad Request</strong></p>';
   break;
}
?>