<?php
if(isset($_COOKIE['report'])){
	if($_COOKIE['report']==$_COOKIE['user_id']){
              include_once  'components/report/control/reportController.php';
    }else header('Location: index.php?components=authenticate&action=show');
}else header('Location: index.php?components=authenticate&action=show');
?>