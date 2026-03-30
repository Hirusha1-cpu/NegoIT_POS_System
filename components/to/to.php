<?php
if(isset($_COOKIE['to'])){
	if($_COOKIE['to']==$_COOKIE['user_id']){
              include_once  'components/to/control/toController.php';
    }else header('Location: index.php?components=authenticate&action=show');
}else header('Location: index.php?components=authenticate&action=show');
?>