<?php
if(isset($_COOKIE['supervisor'])){
	if($_COOKIE['supervisor']==$_COOKIE['user_id']){
              include_once  'components/supervisor/control/supervisorController.php';
    }else header('Location: index.php?components=authenticate&action=show');
}else header('Location: index.php?components=authenticate&action=show');
?>