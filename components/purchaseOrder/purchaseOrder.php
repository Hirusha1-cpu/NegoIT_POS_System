<?php
if(isset($_COOKIE['purchase_order'])){
	if($_COOKIE['purchase_order']==$_COOKIE['user_id']){
              include_once  'components/purchaseOrder/control/poController.php';
    }else header('Location: index.php?components=authenticate&action=show');
}else header('Location: index.php?components=authenticate&action=show');
?>