<?php
if(isset($_COOKIE['hire_purchase'])){
	if($_COOKIE['hire_purchase']==$_COOKIE['user_id']){
              include_once  'components/hirePurchase/control/hpControler.php';
    }else header('Location: index.php?components=authenticate&action=show');
}else header('Location: index.php?components=authenticate&action=show');
?>