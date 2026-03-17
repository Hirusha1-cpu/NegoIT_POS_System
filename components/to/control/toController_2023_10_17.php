<?php
        switch ($_REQUEST['action'])
        {
            case "home" :
               include_once  'components/to/modle/toModule.php';
               getTest();
               if(isMobile())           
	               include_once  'components/to/view/m_home.php';
	           else
	               include_once  'components/to/view/home.php';
            break;
            
   

            default:
                print '<p><srtong>Bad Request</strong></p>';
            break;
        }
?>