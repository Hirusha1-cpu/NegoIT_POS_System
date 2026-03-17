<?php
if(passwordExpire()) header('Location: index.php?components=authenticate&action=change_pw&message=Your%20Password%20Has%20Expired.%20Please%20Change%20it%20NOW&re=fail');

        switch ($_REQUEST['action'])
        {
            case "set_district" :
               include_once  'components/checkAvailability/modle/availabilityModule.php';
               setDistrict();
               header('Location: index.php?components=availability&action=home&category=all');
            break;

            case "home" :
               include_once  'components/checkAvailability/modle/availabilityModule.php';
               getDistrict();
               if(isset($_COOKIE['district'])){
               getItems($sub_system,$systemid);
               checkItem($sub_system);
               }
               getCategory();
               if(isMobile())           
               		include_once  'components/checkAvailability/view/m_home.php';
               else
               		include_once  'components/checkAvailability/view/home.php';
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
            
            case "check" :
               include_once  'components/checkAvailability/modle/availabilityModule.php';
               checkItem();
            break;
            
            case "get_discount" :
               include_once  'components/checkAvailability/modle/availabilityModule.php';
               print getDiscount();           
            break;
            
            case "stock" :
               include_once  'components/checkAvailability/modle/availabilityModule.php';
               getCategory();
               getStock();
               if(isMobile())           
               		include_once  'components/checkAvailability/view/m_stock.php';
               else
               		include_once  'components/checkAvailability/view/stock.php';
            break;
		//-----------------------------SN Search--------------------------------------------------//
		
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
		      snListAll($sub_system);
		      include_once  'template/ajax_list.php';
		   break;
		   
		   case "sn_lookup_price":
		      include_once  'components/manager/modle/managerModule.php';
		      getStore($sub_system);
		      if (isMobile())
		         include_once  'components/manager/view/m_sn_lookup_price.php';
		      else
		         include_once  'components/manager/view/sn_lookup_price.php';
		   break;
		   
		   case "sn_lookup_price_list":
		      include_once  'components/manager/modle/managerModule.php';
		      print snLookupPriceList();
		   break;
		   
            default:
                print '<p><srtong>Bad Request</strong></p>';
            break;
        }
?>