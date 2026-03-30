<?php
if(isset($_COOKIE['settings'])){
	if($_COOKIE['settings']==$_COOKIE['user_id']){
                include_once  'components/settings/control/settingsController.php';
    }else header('Location: index.php?components=authenticate&action=show');
}else header('Location: index.php?components=authenticate&action=show');
?>