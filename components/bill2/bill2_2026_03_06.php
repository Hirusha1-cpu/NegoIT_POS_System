<?php
if(isset($_COOKIE['bill2'])){
	if($_COOKIE['bill2']==$_COOKIE['user_id']){
        commissionOnBilling();
        custDetailsonOnBilling();
        include_once  'components/bill2/control/bill2Controller.php';
    }else header('Location: index.php?components=authenticate&action=show');
}else header('Location: index.php?components=authenticate&action=show');
?>