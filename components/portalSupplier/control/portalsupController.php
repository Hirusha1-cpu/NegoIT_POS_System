<?php
if(passwordExpire()) header('Location: index.php?components=authenticate&action=change_pw&message=Your%20Password%20Has%20Expired.%20Please%20Change%20it%20NOW&re=fail');

        switch ($_REQUEST['action'])
        {
            case "dashboard" :
               include_once  'components/portalSupplier/modle/portalsupModule.php';
               getDashboard();
               getSalesReport();
               if(isMobile())
               		include_once  'components/portalSupplier/view/m_dashboard.php';
               else          
               		include_once  'components/portalSupplier/view/dashboard.php';
            break;

            case "sales_report" :
               include_once  'components/portalSupplier/modle/portalsupModule.php';
               getSalesReport();
               if(isMobile())
               		include_once  'components/portalSupplier/view/m_sales_report.php';
               else          
               		include_once  'components/portalSupplier/view/sales_report.php';
            break;

            case "monthly_sales" :
               include_once  'components/portalSupplier/modle/portalsupModule.php';
               getMonthlySale();
               if(isMobile())
               		include_once  'components/portalSupplier/view/m_monthly_sales.php';
               else          
               		include_once  'components/portalSupplier/view/monthly_sales.php';
            break;

            case "monthly_return" :
               include_once  'components/portalSupplier/modle/portalsupModule.php';
               getMonthlyReturn();
               if(isMobile())
               		include_once  'components/portalSupplier/view/m_monthly_return.php';
               else          
               		include_once  'components/portalSupplier/view/monthly_return.php';
            break;

            default:
                print '<p><srtong>Bad Request</strong></p>';
            break;
        }
?>