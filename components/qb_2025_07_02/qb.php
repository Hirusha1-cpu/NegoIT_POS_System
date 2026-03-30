<?php
if(isset($_COOKIE['qb'])){
	if($_COOKIE['qb']==$_COOKIE['user_id']){
        include_once  'components/qb/control/qbController.php';
    }else header('Location: index.php?components=authenticate&action=show');
}else header('Location: index.php?components=authenticate&action=show');
?>