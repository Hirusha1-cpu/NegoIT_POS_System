<?php
if(isset($_COOKIE['marketing'])){
	if($_COOKIE['marketing']==$_COOKIE['user_id']){
              include_once  'components/marketing/control/marketingController.php';
    }else header('Location: index.php?components=authenticate&action=show');
}else header('Location: index.php?components=authenticate&action=show');
?>