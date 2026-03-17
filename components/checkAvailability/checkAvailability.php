<?php
if(isset($_COOKIE['check_availability'])){
	if($_COOKIE['check_availability']==$_COOKIE['user_id']){
                include_once  'components/checkAvailability/control/availabilityController.php';
    }else header('Location: index.php?components=authenticate&action=show');
}else header('Location: index.php?components=authenticate&action=show');
?>