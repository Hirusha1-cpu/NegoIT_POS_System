<?php
if(isset($_COOKIE['top_manager'])){
	if($_COOKIE['top_manager']==$_COOKIE['user_id']){
              include_once  'components/topManager/control/topManagerController.php';
    }else header('Location: index.php?components=authenticate&action=show');
}else header('Location: index.php?components=authenticate&action=show');
?>