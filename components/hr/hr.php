<?php
if(isset($_COOKIE['hr'])){
	if($_COOKIE['hr']==$_COOKIE['user_id']){
              include_once  'components/hr/control/hrController.php';
    }else header('Location: index.php?components=authenticate&action=show');
}else header('Location: index.php?components=authenticate&action=show');
?>