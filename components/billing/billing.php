<?php
if(isset($_COOKIE['billing'])){
	if($_COOKIE['billing']==$_COOKIE['user_id']){
		commissionOnBilling();
		custDetailsonOnBilling();
        include_once  'components/billing/control/billingController.php';
    }else header('Location: index.php?components=authenticate&action=show');
}else{
	if(isset($_COOKIE['bill2'])){
		switch ($_REQUEST['action']){
			case "finish_bill" :
				header('Location: index.php?components=bill2&action=finish_bill&id='.$_GET['id']);
            break;
			case "finish_payment" :
				header('Location: index.php?components=bill2&action=finish_payment&id='.$_GET['id']);
            break;
            default:
				header('Location: index.php?components=authenticate&action=show');
            break;
		}
	}else{
		header('Location: index.php?components=authenticate&action=show');
	}
}
?>