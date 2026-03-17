<?php
if(isset($_COOKIE['manager'])){
	if($_COOKIE['manager']==$_COOKIE['user_id']){
        custDOBonOnManager(); // added by nirmal 25_05_2022
        include_once  'components/manager/control/managerController.php';
    }else header('Location: index.php?components=authenticate&action=show');
}else header('Location: index.php?components=authenticate&action=show');
?>