<?php
        switch ($_REQUEST['action'])
        {
            case "home" :
               include_once  'components/hirePurchase/modle/hpModule.php';
               myActiveInvoices($_COOKIE['user_id']);
               if(isset($_GET['mismatch'])) paymentDateIssueList($_COOKIE['user_id']);
               if(isMobile())           
	               include_once  'components/hirePurchase/view/m_home.php';
	           else
	               include_once  'components/hirePurchase/view/home.php';
            break;
            
            case "hp_get_invoice_data" :
               include_once  'components/hirePurchase/modle/hpModule.php';
               print getInvoiceData();
            break;
            
            case "get_cust_name" :
               include_once  'components/hirePurchase/modle/hpModule.php';
               print getCustName();
            break;
           
            case "show_invoice_pay" :
               include_once  'components/bill2/modle/bill2Module.php';
               getInvoicePay();
               if(isMobile())           
               		include_once  'components/bill2/view/m_invoice_pay.php';
               else
               		include_once  'components/bill2/view/invoice_pay.php';
            break;
            
            case "finish_bill" :
               include_once  'components/bill2/modle/bill2Module.php';
               include_once  'components/repair/modle/repairModule.php';
            	billPermission();
                billDetails(); 
            	billTemplate();
            	getBank();
            	getSalesman2();
            	getRepairComments();
            	if($hire_purchase) hpInstalmentFormData();
            	if($_COOKIE['fastprint']=='on') generateInvoiceFast();
               if(isMobile())
               		include_once  'components/bill2/view/m_bill_print.php';
               else
               		include_once  'components/bill2/view/finish.php';
            break;
            
            case "finish_payment" :
               include_once  'components/bill2/modle/bill2Module.php';
            	paymentPermission();
            	billTemplate();
                payDetails(); 
               if(isMobile())
               		include_once  'components/bill2/view/m_payment_print.php';
               else
               		include_once  'components/bill2/view/payment_finish.php';
            break;
            
            case "cust_list" :
               include_once  'components/hirePurchase/modle/hpModule.php';
               custList($sub_system,$_COOKIE['user_id']);
               if(isMobile())
	               include_once  'components/hirePurchase/view/m_customer_list.php';
	           else
	               include_once  'components/hirePurchase/view/customer_list.php';
            break;
            
            case "hp_get_invoice_list" :
               include_once  'components/hirePurchase/modle/hpModule.php';
               print getHPInvoiceList($_COOKIE['user_id']);
            break;
            
            case "collection" :
               include_once  'components/hirePurchase/modle/hpModule.php';
               getRecoveryAgent($sub_system);
               upCommingCollection($_GET['rag_id']);
               if(isMobile())
	               include_once  'components/hirePurchase/view/m_collection.php';
	           else
	               include_once  'components/hirePurchase/view/collection.php';
            break;
            
            case "invoice_outstanding" :
               include_once  'components/hirePurchase/modle/hpModule.php';
               getRecoveryAgent($sub_system);
               getInvoiceOutstanding($_GET['rag_id'],$sub_system);
               if(isMobile())
	               include_once  'components/hirePurchase/view/m_invoice_outstanding.php';
	           else
	               include_once  'components/hirePurchase/view/invoice_outstanding.php';
            break;

            default:
                print '<p><srtong>Bad Request</strong></p>';
            break;
        }
?>