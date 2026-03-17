<?php

        switch ($_REQUEST['action'])
        {
            case "sms_ststus" :
               include_once  'components/api/modle/apiModule.php';
               smsStstusUpdate();
            break;
            
            case "sms_pending" :
               include_once  'components/api/modle/apiModule.php';
               smsPending();
               include_once  'components/api/view/sms.php';
            break;

            default:
                print '<p><srtong>Bad Request</strong></p>';
            break;
        }
?>