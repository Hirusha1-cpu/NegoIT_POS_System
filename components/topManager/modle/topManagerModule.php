<?php

function getSubSystems(){
	global $sub_system_list,$sub_system_names, $conn;
	include('config.php');
	$query="SELECT id,name FROM sub_system WHERE `status`=1 AND id!=0";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$sub_system_list[]=$row[0];
		$sub_system_names[]=$row[1];
	} 
}

function getPaySubStatus($status_id){
	$status=$color='';
		switch ($status_id){
			case 0 : $status='Deleted'; $color='orange'; break;
			case 1 : $status='Pending'; $color='blue'; break;
			case 2 : $status='Accepted'; $color='green'; break;
			case 3 : $status='Rejected'; $color='orange'; break;
			case 4 : $status='Cheque Return'; $color='red'; break;
		}
	return $status.'|'.$color;
}

function getPaymentData(){
	global $sub_sys_id,$sub_sys_name,$sub_sys_pending,$last_id,$last_amount,$last_submited_by,$last_submited_date,$last_processed_by,$last_processed_date,$last_status,$last_system,$pending_id,$pending_amount,$pending_submited_by,$pending_submited_date,$pending_submited_time,$pending_submited_system,$cheque_py_id,$cheque_no,$cheque_amount, $conn;
	
	include('config.php');
	$query1="SELECT id,name FROM sub_system WHERE `status`=1 AND id!=0";
	$result1=mysqli_query($conn,$query1);
	while($row1=mysqli_fetch_array($result1)){
		$sub_sys_tmp=$row1[0];
		$sub_sys_id[]=$row1[0];
		$sub_sys_name[]=$row1[1];
		$query="SELECT SUM(bi.qty * bi.unit_price) FROM bill_main bm, bill bi WHERE bm.invoice_no=bi.invoice_no AND bm.`status`!=0 AND bm.`lock`=1 AND bm.exclude=0 AND `sub_system`='$sub_sys_tmp'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		$bm_total=$row[0];
		$query="SELECT SUM(amount) FROM payment_subsys WHERE `status`=2 AND `sub_system`='$sub_sys_tmp'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		$py_total=$row[0];
		$sub_sys_pending[]=$bm_total-$py_total;
	}
	
	$query="SELECT py.id,py.chque_no,bk.bank_code,py.chque_branch,py.amount FROM payment py, bank bk WHERE py.chque_bank=bk.id AND py.payment_type=2 AND py.`status`=0 AND py.chque_return=0 AND py.chque_clear=0 AND py.chque_submit=0 ORDER BY chque_no";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$cheque_py_id[]=$row[0];
		$cheque_no[]=$row[1].' : '.$row[2].' : '.$row[3];
		$cheque_amount[]=$row[4];
	}
	
	$query="SELECT ps.id,ps.amount,up1.username,ps.submited_date,up2.username,ps.processed_date,ps.`status`,ss.name FROM userprofile up1, sub_system ss, payment_subsys ps LEFT JOIN userprofile up2 ON ps.processed_by=up2.id WHERE ps.submited_by=up1.id AND ps.`sub_system`=ss.id AND ps.`status` IN (0,2,3,4) ORDER BY ps.processed_date DESC LIMIT 10";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$last_id[]=$row[0];
		$last_amount[]=$row[1];
		$last_submited_by[]=$row[2];
		$last_submited_date[]=$row[3];
		$last_processed_by[]=$row[4];
		$last_processed_date[]=$row[5];
		$last_status[]=getPaySubStatus($row[6]);
		$last_system[]=$row[7];
	}
	
	$query="SELECT ps.id,ps.amount,up.username,date(ps.submited_date),time(ps.submited_date),ss.name FROM payment_subsys ps, userprofile up, sub_system ss WHERE ps.submited_by=up.id AND ps.`sub_system`=ss.id AND ps.`status`='1' ORDER BY ss.id,ps.submited_date";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$pending_id[]=$row[0];
		$pending_amount[]=$row[1];
		$pending_submited_by[]=$row[2];
		$pending_submited_date[]=$row[3];
		$pending_submited_time[]=$row[4];
		$pending_submited_system[]=$row[5];
	}
}

function getOnePayment(){
global $payment_id,$ps_amount,$ps_type,$ps_chque_no,$ps_chque_bank,$ps_chque_branch,$ps_chque_date,$ps_chque_return_date,$ps_submited_by,$ps_submited_date,$ps_processed_by,$ps_processed_date,$ps_status,$ps_cust_chq,$ps_sub_system;
	if(isset($_GET['pay_id'])){
		$payment_id=$_GET['pay_id'];
		include('config.php');		
		$query="SELECT ps.amount,ps.`type`,ps.chque_no,ps.chque_bank,ps.chque_branch,ps.chque_date,ps.chque_return_date,up1.username,ps.submited_date,up2.username,ps.processed_date,ps.`status`,ps.cust_chq,ss.name FROM userprofile up1, sub_system ss, payment_subsys ps LEFT JOIN userprofile up2 ON ps.processed_by=up2.id WHERE ps.submited_by=up1.id AND ps.`sub_system`=ss.id AND ps.id='$payment_id'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		$ps_amount=$row[0];
		$ps_type=$row[1];
		$ps_chque_no=$row[2];
		$ps_chque_bank=$row[3];
		$ps_chque_branch=$row[4];
		$ps_chque_date=$row[5];
		$ps_chque_return_date=$row[6];
		$ps_submited_by=$row[7];
		$ps_submited_date=$row[8];
		$ps_processed_by=$row[9];
		$ps_processed_date=$row[10];
		$ps_status=getPaySubStatus($row[11]);
		$ps_cust_chq=$row[12];
		$ps_sub_system=$row[13];
	}
}
	
function getBank2(){
global $bank_id,$bank_code,$bank_name, $conn;
	include('config.php');
		$query="SELECT id,bank_code,name FROM bank WHERE `status`='1'";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$bank_id[]=$row[0];
			$bank_code[]=$row[1];
			$bank_name[]=$row[2];
	}
}

function setStatusPayment(){
	global $message;
	$id=$_GET['id'];
	$user_id=$_COOKIE['user_id'];
	$datetime=timeNow();
	$out=false;
	$msg='';
	include('config.php');	
	$query="SELECT `status`,cust_chq FROM payment_subsys WHERE id='$id'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$py_status=$row[0];
	$py_cust_chq=$row[1];

	if($_GET['newstatus']=='accept'){ $newstatus=2; $msg='The Payment was Accepted Successfully!'; $qry=",`processed_by`='$user_id',`processed_date`='$datetime'"; if($py_status==1) $out=true;	}
	if($_GET['newstatus']=='reject'){ $newstatus=3; $msg='The Payment was Rejected Successfully!'; $qry=",`processed_by`='$user_id',`processed_date`='$datetime'"; if($py_status==1) $out=true; }
	if($_GET['newstatus']=='chqrtn'){ $newstatus=4; $msg='The Cheque was Maked as Return Successfully!'; $qry=""; if($py_status==2) $out=true;	}
	
	if($out){
		$query1="UPDATE `payment_subsys` SET `status`='$newstatus' $qry WHERE `id`='$id'";
		$result1=mysqli_query($conn,$query1);
		if(!$result1){ $out=false; $msg='Error: Status Could Not Be Chnaged'; }
	}else $msg='Unauthorized Request';
	
	if(($out)&&($py_cust_chq!='')){
		if($_GET['newstatus']=='reject'){
			$query1="UPDATE `payment` SET `chque_submit`='0' WHERE `id`='$py_cust_chq' ";
			$result1=mysqli_query($conn,$query1);
			if(!$result1){ $out=false; $msg='Error: Cust payment table could not be updated. Please contact NegoIT !'; }
		}
		if($_GET['newstatus']=='chqrtn'){
			$query1="UPDATE `payment` SET `chque_return`='1',`chque_return_date`='$datetime' WHERE `id`='$py_cust_chq' ";
			$result1=mysqli_query($conn,$query1);
			if(!$result1){ $out=false; $msg='Error: Cust payment table could not be updated. Please contact NegoIT !'; }
		}
	}
	if($out){
		$message=$msg;
		return true;
	}else{
		$message=$msg;
		return false;
	}
}

function getPaymentHistory(){
global $sub_system1,$from_date,$to_date,$payment_id,$ps_amount,$ps_type,$ps_chque_no,$ps_chque_bank,$ps_chque_branch,$ps_chque_date,$ps_chque_return_date,$ps_submited_by,$ps_submited_date,$ps_processed_by,$ps_processed_date,$ps_status,$ps_status_code,$ps_cust_chq,$ps_sub_system, $conn;
	$sub_system_qry="";
	if(isset($_GET['from_date'])&&isset($_GET['to_date'])&&isset($_GET['sub_system'])){
		$from_date=$_GET['from_date'];
		$to_date=$_GET['to_date'];
		$sub_system1=$_GET['sub_system'];
		if($sub_system1!='all') $sub_system_qry="AND ps.`sub_system`='$sub_system1'";
	}else{
		$from_date=date("Y-m-d",time()-(60*60*24*30));
		$to_date=dateNow();
		$sub_system1='all';
	}
		include('config.php');		
		$query="SELECT ps.amount,ps.`type`,ps.chque_no,ps.chque_bank,ps.chque_branch,ps.chque_date,ps.chque_return_date,up1.username,ps.submited_date,up2.username,ps.processed_date,ps.`status`,ps.cust_chq,ss.name FROM userprofile up1, sub_system ss, payment_subsys ps LEFT JOIN userprofile up2 ON ps.processed_by=up2.id WHERE ps.submited_by=up1.id AND ps.`sub_system`=ss.id AND ps.`status` IN (2,4) AND date(ps.processed_date) BETWEEN '$from_date' AND '$to_date' $sub_system_qry ORDER BY ss.name,ps.processed_date DESC";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$ps_amount[]=$row[0];
			$ps_type[]=$row[1];
			$ps_chque_no[]=$row[2];
			$ps_chque_bank[]=$row[3];
			$ps_chque_branch[]=$row[4];
			$ps_chque_date[]=$row[5];
			$ps_chque_return_date[]=$row[6];
			$ps_submited_by[]=$row[7];
			$ps_submited_date[]=$row[8];
			$ps_processed_by[]=$row[9];
			$ps_processed_date[]=$row[10];
			$ps_status[]=getPaySubStatus($row[11]);
			$ps_cust_chq[]=$row[12];
			$ps_sub_system[]=$row[13];
			$ps_status_code[]=$row[11];
		}
}


?>