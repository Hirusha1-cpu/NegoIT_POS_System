<?php
        switch ($_REQUEST['action'])
        {
            
            case "manage_category" :
               include_once  'components/settings/modle/settingsModule.php';
	            getCategory();
               include_once  'components/settings/view/manageCategory.php';
            break;
            
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
               		header('Location: index.php?components=settings&action=manage_user&message='.$message.'&re=success');
               	else
               		header('Location: index.php?components=settings&action=manage_user&message='.$message.'&re=fail');
            break;
            
            case "update_user" :
               include_once  'components/settings/modle/settingsModule.php';
               if(updateUser())
               		header('Location: index.php?components=settings&action=manage_user&message='.$message.'&re=success');
               	else
               		header('Location: index.php?components=settings&action=manage_user&message='.$message.'&re=fail');
            break;
            
            case "update_permission" :
               include_once  'components/settings/modle/settingsModule.php';
               print updatePermission();
            break;

            case "update_devicecheck" :
               include_once  'components/settings/modle/settingsModule.php';
               print updateDevicecheck();
            break;
            
            case "update_timecheck" :
               include_once  'components/settings/modle/settingsModule.php';
               print updateTimecheck();
            break;
            
            case "update_mobilerep" :
               include_once  'components/settings/modle/settingsModule.php';
               print updateMobileRep();
            break;
            
            case "update_storeaso" :
               include_once  'components/settings/modle/settingsModule.php';
               if(updateStoreaso())
               		header('Location: index.php?components=settings&action=manage_user&message='.$message.'&re=success');
               	else
               		header('Location: index.php?components=settings&action=manage_user&message='.$message.'&re=fail');
            break;
            
            case "update_mapinv" :
               include_once  'components/settings/modle/settingsModule.php';
               if(updateMapInv())
               		header('Location: index.php?components=settings&action=manage_user&message='.$message.'&re=success');
               	else
               		header('Location: index.php?components=settings&action=manage_user&message='.$message.'&re=fail');
            break;

            case "disable_user" :
               include_once  'components/settings/modle/settingsModule.php';
               if(setStatusUser(1))
               		header('Location: index.php?components=settings&action=manage_user&message='.$message.'&re=success');
               	else
               		header('Location: index.php?components=settings&action=manage_user&message='.$message.'&re=fail');
            break;
            
            case "enbale_user" :
               include_once  'components/settings/modle/settingsModule.php';
               if(setStatusUser(0))
               		header('Location: index.php?components=settings&action=manage_user&message='.$message.'&re=success');
               	else
               		header('Location: index.php?components=settings&action=manage_user&message='.$message.'&re=fail');
            break;
            
            case "add_category" :
               include_once  'components/settings/modle/settingsModule.php';
               if(addCategory())
               		header('Location: index.php?components=settings&action=manage_category&message='.$message.'&re=success');
               	else
               		header('Location: index.php?components=settings&action=manage_category&message='.$message.'&re=fail');
            break;
            
            case "delete_category" :
               include_once  'components/settings/modle/settingsModule.php';
               if(deleteCategory())
               		header('Location: index.php?components=settings&action=manage_category&message='.$message.'&re=success');
               	else
               		header('Location: index.php?components=settings&action=manage_category&message='.$message.'&re=fail');
            break;
			//------------------------System Settings-------------------//
            case "system_settings" :
               include_once  'components/settings/modle/settingsModule.php';
	            getSettings();
               include_once  'components/settings/view/systemSettings.php';
            break;
            
            case "pre_cal_bill" :
               include_once  'components/settings/modle/settingsModule.php';
               if(preCalBill())
               		header('Location: index.php?components=settings&action=system_settings&message='.$message.'&re=success');
               	else
               		header('Location: index.php?components=settings&action=system_settings&message='.$message.'&re=fail');
            break;
            
            case "clear_invtemp" :
               include_once  'components/settings/modle/settingsModule.php';
               if(clearInvtemp())
               		header('Location: index.php?components=settings&action=system_settings&message='.$message.'&re=success');
               	else
               		header('Location: index.php?components=settings&action=system_settings&message='.$message.'&re=fail');
            break;
            
            case "update_time" :
               include_once  'components/settings/modle/settingsModule.php';
               if(updateTime())
               		header('Location: index.php?components=settings&action=system_settings&message='.$message.'&re=success');
               	else
               		header('Location: index.php?components=settings&action=system_settings&message='.$message.'&re=fail');
            break;
			//----------------------------Devices-----------------------//
            case "devices" :
               include_once  'components/settings/modle/settingsModule.php';
	            getDevices();
	            getDevicePermission();
               include_once  'components/settings/view/devices.php';
            break;
            
            case "device_grid" :
               include_once  'components/settings/modle/settingsModule.php';
	            getDevices();
	            getPermissionGrid();
               include_once  'components/settings/view/device_grid.php';
            break;
            
            case "add_device" :
               include_once  'components/settings/modle/settingsModule.php';
               if(addDevice($sub_system))
               		header('Location: index.php?components=settings&action=devices&message='.$message.'&re=success');
               	else
               		header('Location: index.php?components=settings&action=devices&message='.$message.'&re=fail');
            break;
            
            case "change_device" :
               include_once  'components/settings/modle/settingsModule.php';
               if(changeDevice())
               		header('Location: index.php?components=settings&action=devices&message='.$message.'&re=success');
               	else
               		header('Location: index.php?components=settings&action=devices&message='.$message.'&re=fail');
            break;
            
            case "rekey_device" :
               include_once  'components/settings/modle/settingsModule.php';
               if(reKeyDevice())
               		header('Location: index.php?components=settings&action=devices&message='.$message.'&re=success');
               	else
               		header('Location: index.php?components=settings&action=devices&message='.$message.'&re=fail');
            break;
            
            case "rename_device" :
               include_once  'components/settings/modle/settingsModule.php';
               if(renameDevice())
               		header('Location: index.php?components=settings&action=devices&message='.$message.'&re=success');
               	else
               		header('Location: index.php?components=settings&action=devices&message='.$message.'&re=fail');
            break;
            
            case "addpermission_device" :
               include_once  'components/settings/modle/settingsModule.php';
               if(addDevicePermission())
               		header('Location: index.php?components=settings&action=devices&message='.$message.'&re=success');
               	else
               		header('Location: index.php?components=settings&action=devices&message='.$message.'&re=fail');
            break;
            
            case "addpermission_grid" :
               include_once  'components/settings/modle/settingsModule.php';
               if(addGridPermission())
               		header('Location: index.php?components=settings&action=device_grid&user_name='.$user_name.'&message='.$message.'&re=success');
               	else
               		header('Location: index.php?components=settings&action=device_grid&message='.$message.'&re=fail');
            break;
            
            case "delpermission_device" :
               include_once  'components/settings/modle/settingsModule.php';
               if(delDevicePermission())
               		header('Location: index.php?components=settings&action=devices&message='.$message.'&re=success');
               	else
               		header('Location: index.php?components=settings&action=devices&message='.$message.'&re=fail');
            break;
            //-----------------------------Group Allocation------------------------------------//
            case "group_allocation" :
               include_once  'components/settings/modle/settingsModule.php';
	            getAllocation();
               include_once  'components/settings/view/group_allocation.php';
            break;
            
            case "add_group_allocation" :
               include_once  'components/settings/modle/settingsModule.php';
               if(addGroupAllocation())
               		header('Location: index.php?components=settings&action=group_allocation&user_id='.$_POST['user_id'].'&message='.$message.'&re=success');
               	else
               		header('Location: index.php?components=settings&action=group_allocation&message='.$message.'&re=fail');
            break;
            
            case "remove_group_allocation" :
               include_once  'components/settings/modle/settingsModule.php';
               if(removeGroupAllocation())
               		header('Location: index.php?components=settings&action=group_allocation&user_id='.$_GET['user_id'].'&message='.$message.'&re=success');
               	else
               		header('Location: index.php?components=settings&action=group_allocation&message='.$message.'&re=fail');
            break;
            //-----------------------------BILL EDIT------------------------------------//
            case "bill_edit" :
               include_once  'components/settings/modle/settingsModule.php';
               searchBill();
               include_once  'components/settings/view/bill_edit.php';
            break;
            
            case "bill_update" :
               include_once  'components/settings/modle/settingsModule.php';
               if(updateBill($systemid))
               		header('Location: index.php?components=settings&action=bill_edit&bill_no='.$_GET['bill_no'].'&message='.$message.'&re=success');
               	else
               		header('Location: index.php?components=settings&action=bill_edit&bill_no='.$_GET['bill_no'].'&message='.$message.'&re=fail');
            break;
            
            default:
                print '<p><srtong>Bad Request</strong></p>';
            break;
        }
?>