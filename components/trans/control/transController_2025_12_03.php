<?php
if(passwordExpire()){
	header('Location: index.php?components=authenticate&action=change_pw&message=Your%20Password%20Has%20Expired.%20Please%20Change%20it%20NOW&re=fail');
}
switch ($_REQUEST['action']){
	case "home" :
		include_once  'components/trans/modle/transModule.php';
		getItems();
		getGTNItems();
		getStores($systemid,$sub_system);
		if(!isset($_GET['id'])) newGTN();
		if(isMobile())
			include_once  'components/trans/view/m_home.php';
		else
			include_once  'components/trans/view/home.php';
	break;

	case "pick_gtn" :
		include_once  'components/trans/modle/transModule.php';
		if(pickGTN())
			header('Location: index.php?components=trans&action=home&id='.$gtn_no.'&remotestore='.$tostore.'&message='.$message.'&re=success');
		else
			header('Location: index.php?components=trans&action=home&id='.$gtn_no.'&remotestore='.$tostore.'&message='.$message.'&re=fail');
	break;

	case "apend_gtn" :
		include_once  'components/trans/modle/transModule.php';
		if(apendGTN())
			header('Location: index.php?components=trans&action=home&id='.$gtn_no.'&remotestore='.$tostore.'&message='.$message.'&re=success');
		else
			header('Location: index.php?components=trans&action=home&id='.$gtn_no.'&remotestore='.$tostore.'&message='.$message.'&re=fail');
	break;

	case "gtn_item_gpdate" :
		include_once  'components/trans/modle/transModule.php';
		$debug_id=debugStart(0,0);
		if(updateGTNitem()){
			debugEnd($debug_id,'success');
			header('Location: index.php?components=trans&action=home&id='.$gtn_no.'&remotestore='.$tostore.'&message='.$message.'&re=success');
		}else{
			debugEnd($debug_id,'fail');
			header('Location: index.php?components=trans&action=home&id='.$gtn_no.'&remotestore='.$tostore.'&message='.$message.'&re=fail');
		}
	break;

	case "gtn_item_remove" :
		include_once  'components/trans/modle/transModule.php';
		$debug_id=debugStart(0,0);
		if(removeGTNitem()){
			debugEnd($debug_id,'success');
			header('Location: index.php?components=trans&action=home&id='.$gtn_no.'&remotestore='.$tostore.'&message='.$message.'&re=success');
		}else{
			debugEnd($debug_id,'fail');
			header('Location: index.php?components=trans&action=home&id='.$gtn_no.'&remotestore='.$tostore.'&message='.$message.'&re=fail');
		}
	break;

	case "finish_gtn" :
		include_once  'components/trans/modle/transModule.php';
		if(finalizeGTN())
			header('Location: index.php?components=trans&action=print_gtn&id='.$_GET['id'].'&approve_permission=0');
		else
			header('Location: index.php?components=trans&action=home&id='.$gtn_no.'&remotestore='.$tostore.'&message='.$message.'&re=fail');
	break;

	case "print_gtn" :
		include_once  'components/trans/modle/transModule.php';
		gtnOwner();
		if(isMobile())
			include_once  'components/trans/view/m_finish.php';
		else
			include_once  'components/trans/view/finish.php';
	break;

	case "today" :
		include_once  'components/trans/modle/transModule.php';
		getGTNlist('today');
		if(isMobile())
			include_once  'components/trans/view/m_listGTN.php';
		else
			include_once  'components/trans/view/listGTN.php';
	break;

	case "last100" :
		include_once  'components/trans/modle/transModule.php';
		getGTNlist('last100');
		if(isMobile())
			include_once  'components/trans/view/m_listGTN.php';
		else
			include_once  'components/trans/view/listGTN.php';
	break;

	case "edit_gtn" :
		include_once  'components/trans/modle/transModule.php';
		if(setStatusGTN())
			header('Location: index.php?components=trans&action=home&id='.$gtn_no.'&remotestore='.$tostore);
		else
			header('Location: index.php?components=trans&action=last100&message='.$message.'&re=fail');
	break;

	case "delete" :
		include_once  'components/trans/modle/transModule.php';
		if(deleteRejectGTN('delete'))
			header('Location: index.php?components=trans&action=today&message='.$message.'&re=success');
		else
			header('Location: index.php?components=trans&action=today&message='.$message.'&re=fail');
	break;

	case "approval" :
		include_once  'components/trans/modle/transModule.php';
		getGTNlist('approval');
		if(isMobile())
			include_once  'components/trans/view/m_listGTN.php';
		else
			include_once  'components/trans/view/listGTN.php';
	break;


	case "approve" :
		include_once  'components/trans/modle/transModule.php';
		if(approveGTN())
			header('Location: index.php?components=trans&action=today&message='.$message.'&re=success');
		else
			header('Location: index.php?components=trans&action=today&message='.$message.'&re=fail');
	break;

	case "reject" :
		include_once  'components/trans/modle/transModule.php';
		if(deleteRejectGTN('reject'))
			header('Location: index.php?components=trans&action=approval&message='.$message.'&re=success');
		else
			header('Location: index.php?components=trans&action=approval&message='.$message.'&re=fail');
	break;

	case "cross_submit" :
		include_once  'components/trans/modle/transModule.php';
		if(crossSubmitGTN())
			header('Location: index.php?components=trans&action=approval&message='.$message.'&re=success');
		else
			header('Location: index.php?components=trans&action=approval&message='.$message.'&re=fail');
	break;

	case "drawer_search" :
		include_once  'components/inventory/modle/inventoryModule.php';
		getStores($systemid,$sub_system);
		drawerSearch();
		if(isMobile())
			include_once  'components/inventory/view/m_drawerSearch.php';
		else
			include_once  'components/inventory/view/drawerSearch.php';
	break;

	// added by E.S.P Nirmal 2021_06_09
	case "items_in_transfer":
		include_once  'components/trans/modle/transModule.php';
		getItemInTransfer();
		include_once  'components/trans/view/list_item_in_trans.php';
	break;

	default:
		print '<p><srtong>Bad Request</strong></p>';
	break;
}
?>