<?php
if(isset($_COOKIE['fin'])){
	if($_COOKIE['fin']==$_COOKIE['user_id']){
              include_once  'components/fin/control/finController.php';
    }else header('Location: index.php?components=authenticate&action=show');
}else header('Location: index.php?components=authenticate&action=show');
?>