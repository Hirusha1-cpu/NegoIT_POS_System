<?php
if(isset($_COOKIE['inventory'])){
	if($_COOKIE['inventory']==$_COOKIE['user_id']){
                include_once  'components/inventory/control/inventoryController.php';
    }else header('Location: index.php?components=authenticate&action=show');
}else header('Location: index.php?components=authenticate&action=show');
?>