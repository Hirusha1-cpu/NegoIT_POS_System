<?php
if(isset($_COOKIE['stores_transfer'])){
	if($_COOKIE['stores_transfer']==$_COOKIE['user_id']){
                include_once  'components/trans/control/transController.php';
    }else header('Location: index.php?components=authenticate&action=show');
}else header('Location: index.php?components=authenticate&action=show');
?>