<?php
if(isset($_COOKIE['stk'])){
	if($_COOKIE['stk']==$_COOKIE['user_id']){
              include_once  'components/stk/control/stkController.php';
    }else header('Location: index.php?components=authenticate&action=show');
}else header('Location: index.php?components=authenticate&action=show');
?>