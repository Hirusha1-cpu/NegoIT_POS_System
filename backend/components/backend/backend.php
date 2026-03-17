<?php
if(isset($_COOKIE['backend'])){
	if($_COOKIE['userkey']==$_SESSION["userkey"]){
                include_once  'components/backend/control/backendController.php';
    }else header('Location: index.php?components=authenticate&action=show');
}else header('Location: index.php?components=authenticate&action=show');
?>

