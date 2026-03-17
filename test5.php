<?php
if(isset($_COOKIE['user_id'])){	

$pay_id=$_POST['pay_id'];
$date=$_POST['date'].' 10:00:00';
$salesman=$_POST['salesman'];
$schedule=$_POST['schedule'];
$msg='Error';
$out=true;

if(($pay_id!='')&&($date!='')&&($salesman!='')&&($schedule!='')){
	include('config.php');

	$query="UPDATE `payment` SET `payment_date`='$date',`salesman`='$salesman' WHERE id='$pay_id'";
	$result=mysqli_query($conn,$query);
	
	$query="SELECT invoice_no FROM payment WHERE id='$pay_id'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$invoice_no=$row[0];
	if($invoice_no==''){ $out=false; $msg='Error: No Invoice Number Found'; }
	
	$query="SELECT id FROM hp_inv_schedule WHERE invoice_no='$invoice_no'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$his_id=$row[0];
	if($his_id==''){ $out=false; $msg='Error: No Payment Schedule Found'; }
	
	if($out){
		$query="SELECT COUNT(id) FROM hp_payments WHERE invoice_no='$invoice_no' AND payment_no='$pay_id'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		if($row[0]>0){ $out=false; $msg='Error: This Payment is Alredy Mapped'; }
	}
	
	if($out){
		$query="INSERT INTO `hp_payments` (`invoice_no`,`payment_no`,`schedule`,`instalment_date`) VALUES ('$invoice_no','$pay_id','$his_id','$schedule')";
		$result=mysqli_query($conn,$query);
		if($result) $msg='Done';
	
	}
	
	print $msg;
}

}
?>