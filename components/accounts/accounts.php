<?php
if(isset($_COOKIE['accounts'])){
	if($_COOKIE['accounts']==$_COOKIE['user_id']){
              include_once  'components/accounts/control/accountsController.php';
    }else header('Location: index.php?components=authenticate&action=show');
}else header('Location: index.php?components=authenticate&action=show');
?>