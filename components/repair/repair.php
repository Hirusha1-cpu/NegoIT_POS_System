<?php
if(isset($_COOKIE['repair'])){
	if($_COOKIE['repair']==$_COOKIE['user_id']){
                include_once  'components/repair/control/repairController.php';
    }else header('Location: index.php?components=authenticate&action=show');
}else header('Location: index.php?components=authenticate&action=show');
?>