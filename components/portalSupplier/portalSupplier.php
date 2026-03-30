<?php
if(isset($_COOKIE['portal_supplier'])){
	if($_COOKIE['portal_supplier']==$_COOKIE['user_id']){
              include_once  'components/portalSupplier/control/portalsupController.php';
    }else header('Location: index.php?components=authenticate&action=show');
}else header('Location: index.php?components=authenticate&action=show');
?>