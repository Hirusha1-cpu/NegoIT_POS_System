<?php
function setSMSBalance($sub_system){
	include('config.php');
	$result = mysqli_query($conn,"SELECT value FROM settings WHERE setting='sms_data'");
	$row = mysqli_fetch_assoc($result);
	$sms_data=$row['value'];
	if($sms_data=='setting'){
		$query="UPDATE `settings` SET `value`=`value`-1 WHERE `setting`='sms_balance'";
		$result=mysqli_query($conn,$query);
	}else{
		$query="UPDATE `sub_system` SET `sms_balance`=`sms_balance`-1 WHERE `id`='$sub_system'";
		$result=mysqli_query($conn,$query);
	}
}

function smsStstusUpdate(){
	$case=$_GET['ref1'];	
	$ref2=$_GET['ref2'];
	$st=$_GET['st'];
	if(($case!='')&&($ref2!='')&&($st==1)){
		include('config.php');
        switch ($case){
            case "bill" :
				$query="UPDATE `bill_main` SET `sms`='1' WHERE `invoice_no`='$ref2'";
				mysqli_query($conn,$query);
				$query="SELECT sub_system FROM bill_main WHERE invoice_no='$ref2'";
				$row=mysqli_fetch_row(mysqli_query($conn,$query));
				setSMSBalance($row[0]);
            break;
            case "pay" :
				$query="UPDATE `payment` SET `sms`='1' WHERE `id`='$ref2'";
				mysqli_query($conn,$query);
				$query="SELECT sub_system FROM payment WHERE id='$ref2'";
				$row=mysqli_fetch_row(mysqli_query($conn,$query));
				setSMSBalance($row[0]);
            break;
        }
	}
}

function smsPending(){
	global $sms_pcount,$sms0_ref1,$sms0_ref2,$sms0_to,$sms0_text;
	$api_key=$_GET['api'];
	$today=dateNow();
	$sms_pcount=0;
	$get_sms=true;
	$show_data=false;
	$sms0_ref1=$sms0_ref2=$sms0_to=$sms0_text="";
	include('config.php');
	$query="SELECT `value` FROM settings WHERE setting='sms_data'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$sms_data=$row[0];	
	if($sms_data=='setting'){
		$sub_system_qry1=$sub_system_qry2="";
		$query="SELECT `value` FROM settings WHERE setting='sms_dev'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		if($api_key==$row[0]) $show_data=true;
	}else{
		$query="SELECT id FROM sub_system WHERE sms_dev='$api_key'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		$sub_system=$row[0];
		$sub_system_qry1="AND bm.sub_system='$sub_system'";
		$sub_system_qry2="AND py.sub_system='$sub_system'";
		if($row[0]!="") $show_data=true;
	}
	
	if($show_data){
		$query="SELECT count(sm.id) FROM sms sm, bill_main bm WHERE sm.ref=bm.invoice_no AND sm.`case`='1' AND bm.`sms`='0' AND date(bm.billed_timestamp)='$today' $sub_system_qry1";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		$sms_pcount=$row[0];
		if($row[0]>0){
			$get_sms=false;
			$query="SELECT bm.invoice_no,cu.mobile,sm.`text` FROM sms sm, bill_main bm, cust cu WHERE sm.ref=bm.invoice_no AND cu.id=bm.`cust` AND sm.`case`='1' AND bm.`sms`='0' $sub_system_qry1 LIMIT 1";
			$row=mysqli_fetch_row(mysqli_query($conn,$query));
			$sms0_ref1='bill';
			$sms0_ref2=$row[0];
			$sms0_to=$row[1];
			$sms0_text=$row[2];
		}
		$query="SELECT COUNT(sm.id) FROM sms sm, payment py WHERE sm.ref=py.id AND sm.`case`='2' AND py.`sms`='0' AND date(py.payment_date)='$today' $sub_system_qry2";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		$sms_pcount+=$row[0];
		if(($row[0]>0)&&($get_sms)){
			$query="SELECT py.id,cu.mobile,sm.`text` FROM sms sm, payment py, cust cu WHERE sm.ref=py.id AND cu.id=py.`cust` AND sm.`case`='2' AND py.`sms`='0' $sub_system_qry2 LIMIT 1";
			$row=mysqli_fetch_row(mysqli_query($conn,$query));
			$sms0_ref1='bill';
			$sms0_ref2=$row[0];
			$sms0_to=$row[1];
			$sms0_text=$row[2];
		}	
	}	
	
}

?>