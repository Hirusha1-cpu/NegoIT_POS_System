<?php

include_once  'template/common.php';
$api_access=false;
if(isset($_GET['components'])) if($_GET['components']=='api'){
	$api_access=true;
	include_once  'components/api/api.php';
}
if(!$api_access){
	if(!isset($_SESSION['userkey'])) session_start();
	include_once  'template/debug.php';
	$systemid=inf_systemid(1);
	if(isset($_COOKIE['sub_system'])) $sub_system=$_COOKIE['sub_system']; else $sub_system=0;
	if(isset($_COOKIE['manager']) || isset($_COOKIE['top_manager']) || isset($_COOKIE['report'])) $approver1=true; else $approver1=false;
	if(isset($_COOKIE['supervisor']) || isset($_COOKIE['manager']) || isset($_COOKIE['top_manager']) || isset($_COOKIE['report'])) $approver2=true; else $approver2=false;
	if(isset($_COOKIE['back1'])) $back1=$_COOKIE['back1']; else $back1='';
	$back_proceed=true;
		if(isset($_GET['components'])){
		if((($_GET['components']=='inventory')&&($_GET['action']=='add_repair_map'))) $back_proceed=false;
		if((($_GET['components']=='inventory')&&($_GET['action']=='remove_repair_map'))) $back_proceed=false;
		if((($_GET['components']=='inventory')&&($_GET['action']=='show_repair_map'))) $back_proceed=false;
		}
	$new_back=$_SERVER['REQUEST_URI'];
	if($back1!=$new_back){
		if($back_proceed){
			setcookie('back1',$new_back, time()+3600);
			setcookie('back2', $back1, time()+3600);
		}
	}else{
		if($back_proceed){
			if(isset($_COOKIE['back2'])) $back1=$_COOKIE['back2']; else $back1='';
		}
	}

	if(isset($_COOKIE['user_id'])){
		timeCheck($_COOKIE['user_id']);
		deviceCheck($_COOKIE['user_id']);
	}

	if((isset($_COOKIE['userkey']))&&(isset($_SESSION['userkey']))){
		if($_SESSION['userkey']==$_COOKIE['userkey']){
			if(isset($_REQUEST['components'])){
				switch ($_REQUEST['components']){
					case "inventory" :
						include_once  'components/inventory/inventory.php';
					break;
					case "billing" :
						include_once  'components/billing/billing.php';
					break;
					case "bill2" :
						include_once  'components/bill2/bill2.php';
					break;
					case "order_process" :
						include_once  'components/orderProcess/orderProcess.php';
					break;
					case "repair" :
						include_once  'components/repair/repair.php';
					break;
					case "trans" :
						include_once  'components/trans/trans.php';
					break;
					case "availability" :
						include_once  'components/checkAvailability/checkAvailability.php';
					break;
					case "supervisor" :
						include_once  'components/supervisor/supervisor.php';
					break;
					case "manager" :
						include_once  'components/manager/manager.php';
					break;
					case "topmanager" :
						include_once  'components/topManager/topManager.php';
					break;
					case "purchase_order" :
						include_once  'components/purchaseOrder/purchaseOrder.php';
					break;
					case "hire_purchase" :
						include_once  'components/hirePurchase/hirePurchase.php';
					break;
					case "marketing" :
						include_once  'components/marketing/marketing.php';
					break;
					case "accounts" :
						include_once  'components/accounts/accounts.php';
					break;
					case "fin" :
						include_once  'components/fin/fin.php';
					break;
					case "hr" :
						include_once  'components/hr/hr.php';
					break;
					case "to" :
						include_once  'components/to/to.php';
					break;
					case "report" :
						include_once  'components/report/report.php';
					break;
					case "settings" :
						include_once  'components/settings/settings.php';
					break;
					case "authenticate" :
						include_once  'components/authenticate/authenticate.php';
					break;
					case "portalsup" :
						include_once  'components/portalSupplier/portalSupplier.php';
					break;
					default:
						if(bill_module(1) == 'bill2'){
							header('Location: index.php?components=bill2&action=home&s='.$_COOKIE['user_id'].'&cust_odr=no');
						}else{
							header('Location: index.php?components=billing&action=home&s='.$_COOKIE['user_id'].'&cust_odr=no');
						}
					break;
				}
			}else{
				if(isset($_COOKIE['to'])){
					header('Location: index.php?components=to&action=home');
				}else{
					if(bill_module(1) == 'bill2'){
						header('Location: index.php?components=bill2&action=home&s='.$_COOKIE['user_id'].'&cust_odr=no');
					}else{
						header('Location: index.php?components=billing&action=home&s='.$_COOKIE['user_id'].'&cust_odr=no');
					}
				}
			}
		}else{
			if(isset($_REQUEST['components'])){
					switch ($_REQUEST['components'])
					{
						case "authenticate" :
							include_once  'components/authenticate/authenticate.php';
						break;
						default:
							header('Location: index.php?components=authenticate&action=logout');
						break;
					}
			}else header('Location: index.php?components=authenticate&action=logout');
		}
	}
	if((!isset($_SESSION['userkey']))||(!isset($_COOKIE['userkey']))){
		if(isset($_REQUEST['components'])){
			switch ($_REQUEST['components']){
				case "authenticate" :
					include_once  'components/authenticate/authenticate.php';
				break;
				default:
					header('Location: index.php?components=authenticate&action=logout');
				break;
			}
		}else header('Location: index.php?components=authenticate&action=logout');
	}
}
if(!isset($_COOKIE['notification'])){
	dailyCreditEmail();
	notificationDelay();
}
?>