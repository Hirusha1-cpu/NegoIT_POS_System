<?php
if(isset($_COOKIE['order_process'])){
	if($_COOKIE['order_process']==$_COOKIE['user_id']){
                include_once  'components/orderProcess/control/orderProcessController.php';
    }else header('Location: index.php?components=authenticate&action=show');
}else header('Location: index.php?components=authenticate&action=show');
?>