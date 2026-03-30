<?php
        switch ($_REQUEST['action'])
        {
            case "home" :
               include_once  'components/hr/modle/hrModule.php';
               getLeaveFormData($systemid,$sub_system);
               hrUsers();
               getUserLeaves('private');
               if(isMobile())           
	               include_once  'components/hr/view/m_home.php';
	           else
	               include_once  'components/hr/view/home.php';
            break;
            
            case "apply_leave" :
               include_once  'components/hr/modle/hrModule.php';
               if(applyLeave($sub_system))
               		header('Location: index.php?components=hr&action=home&leave_user='.$user.'&message='.$message.'&re=success');
               	else
               		header('Location: index.php?components=hr&action=home&leave_user='.$user.'&message='.$message.'&re=fail');
            break;
            
            case "allocate" :
               include_once  'components/hr/modle/hrModule.php';
               getLeaveType();
               getLeaveTypeData();
               include_once  'components/hr/view/allocate.php';
            break;
            
            case "allocate_update" :
               include_once  'components/hr/modle/hrModule.php';
               print allocateUpdate();
            break;
            
            case "my_leave" :
               include_once  'components/hr/modle/hrModule.php';
               getOneLeave('private');
               getUserLeaves('private');
               if(isMobile())           
	               include_once  'components/hr/view/m_my_leave.php';
	           else
	               include_once  'components/hr/view/my_leave.php';
            break;
            
            case "set_leave_status" :
               include_once  'components/hr/modle/hrModule.php';
               if(setLeaveStatus($_GET['new_status']))
               		header('Location: index.php?components=hr&action=my_leave&id='.$leave_no.'&message='.$message.'&re=success');
               	else
               		header('Location: index.php?components=hr&action=my_leave&id='.$leave_no.'&message='.$message.'&re=fail');
            break;
            
            case "set_leave_status2" :
               include_once  'components/hr/modle/hrModule.php';
               if(setLeaveStatus($_GET['new_status']))
               		header('Location: index.php?components=hr&action=leave_list&id='.$leave_no.'&message='.$message.'&re=success');
               	else
               		header('Location: index.php?components=hr&action=leave_list&id='.$leave_no.'&message='.$message.'&re=fail');
            break;
            
            case "delete_leave" :
               include_once  'components/hr/modle/hrModule.php';
               if(deleteLeave($approver1))
               		header('Location: index.php?components=hr&action=leave_list&id='.$leave_no.'&message='.$message.'&re=success');
               	else
               		header('Location: index.php?components=hr&action=leave_list&id='.$leave_no.'&message='.$message.'&re=fail');
            break;
            
            case "leave_list" :
               include_once  'components/hr/modle/hrModule.php';
               getOneLeave('public');
               getUserLeaves('public');
               hrUsers();
               getLeaveType();
               if($approver1){
               if(isMobile())           
	               include_once  'components/hr/view/m_leave_list.php';
	           else
	               include_once  'components/hr/view/leave_list.php';
	           }
            break;
            
            case "shop_staff" :
               include_once  'components/hr/modle/hrModule.php';
               getShopStaff();
               if($approver1){
               if(isMobile())           
               		include_once  'components/hr/view/m_shop_staff.php';
               	else
              		include_once  'components/hr/view/shop_staff.php';
               }
            break;
            
            case "leave_report" :
               include_once  'components/hr/modle/hrModule.php';
               getLeaveType();
               hrUsers();
               getLeaveReport();
               if($approver1){
               include_once  'components/hr/view/leave_report.php';
               }
            break;
            
            case "inout_report" :
               include_once  'components/hr/modle/hrModule.php';
               inoutReport();
               if($approver1){
               include_once  'components/hr/view/inout_report.php';
               }
            break;

            case "set_check_out" :
               include_once  'components/hr/modle/hrModule.php';
                if(setCheckOut())
                	header('Location: index.php?components=hr&action=home&message='.$message.'&re=success');
                else
                	header('Location: index.php?components=hr&action=home&message='.$message.'&re=fail');
            break;

            case "show_map1" :
               include_once  'components/hr/modle/hrModule.php';
         	   decodeMapData1();
               include_once  'components/hr/view/mapview1.php';
            break;

            default:
                print '<p><srtong>Bad Request</strong></p>';
            break;
        }
?>