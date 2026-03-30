<?php
function getUserID($username){
	include('config.php');
	$query="SELECT id FROM userprofile WHERE username='$username'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	return $row[0]; 					
}

function getOrder($method){
global 		$bi_invoice_no,$bi_cust,$bi_district,$bi_billed_by,$bi_billed_date,$bi_billed_time,$bi_seen_by,$bi_seen_date, 
		$bi_seen_time,$bi_picked_by,$bi_picked_date,$bi_picked_time,$bi_repaired_by,$bi_repaired_date,$bi_repaired_time,
		$bi_deliverd_by,$bi_deliverd_date,$bi_deliverd_time,$menu_date,$bi_status,$filter_inv,$filter_cust,$filter_sm,$filter_tec; 
	$filter_inv=$filter_cust=$filter_sm=$filter_tec=$filter1=$filter2=$filter3=$filter41=$filter42="";
	
	$store=$_COOKIE['store'];	
	$user=$_COOKIE['user_id'];		
	if(isset($_POST['filter_inv'])){ 
		$filter_inv=preg_replace("/[^0-9]/",'',$_POST['filter_inv']);  
		if($filter_inv!='') $filter1="AND bm.invoice_no='$filter_inv'";
	}
	if(isset($_POST['filter_cust'])){ 
		$filter_cust=preg_replace("/[^A-Za-z0-9+-,. ]/",'',$_POST['filter_cust']);  
		if($filter_cust!='') $filter2="AND cu.name LIKE '%$filter_cust%'";	
	}
	if(isset($_POST['filter_sm'])){ 
		$filter_sm=preg_replace("/[^A-Za-z0-9+-,. ]/",'',$_POST['filter_sm']);  
		$sm_id=getUserID($filter_sm);
		if($sm_id!='') $filter3="AND bm.billed_by='$sm_id'";	
	}
	if(isset($_POST['filter_tec'])){ 
		$filter_tec=preg_replace("/[^A-Za-z0-9+-,. ]/",'',$_POST['filter_tec']);  
		$tec_id=getUserID($filter_tec);
		if($tec_id!=''){
			$filter41="AND bm.packed_by='$tec_id'";	
			$filter42="AND bm.shipped_by='$tec_id'";	
		}
	}

	if($method=='pending'){ $status='1,2,3'; $menu_date='Picked Date';  $orderby='ORDER BY bm.billed_timestamp'; $filter4=$filter41; }
	if($method=='picked'){ $status='3'; $menu_date='Picked Date'; $orderby="AND bm.packed_by='$user' ORDER BY bm.packed_timestamp DESC"; $filter4=$filter41; }
	if($method=='finished'){ $status='4'; $menu_date='Repaired Date'; $orderby="AND bm.packed_by!='' ORDER BY bm.packed_timestamp DESC"; $filter4=$filter42; }
	if($method=='rejected'){ $status='6'; $menu_date='Rejected Date'; $orderby="AND bm.packed_by!='' ORDER BY bm.packed_timestamp DESC"; $filter4=$filter42; }
	
	$bi_invoice_no=array();
	include('config.php');
	
	$query1="SELECT id,username FROM userprofile";
	$result1=mysqli_query($conn,$query1);
	while($row1=mysqli_fetch_array($result1)){	$salesman[$row1[0]]=$row1[1]; 	} 

	$query="SELECT DISTINCT bm.invoice_no,cu.name,di.name,bm.`status`,bm.billed_by,date(bm.billed_timestamp),time(bm.billed_timestamp),bm.seen_by
,date(bm.seen_timestamp),time(bm.seen_timestamp),bm.packed_by,date(bm.packed_timestamp),time(bm.packed_timestamp),
bm.shipped_by,date(bm.shipped_timestamp),time(bm.shipped_timestamp),bm.deliverd_by,date(bm.deliverd_timestamp),time(bm.deliverd_timestamp)
FROM bill_main bm, bill bi, district di, cust cu WHERE bm.invoice_no=bi.invoice_no AND bm.billed_district=di.id AND bm.cust=cu.id AND bm.`lock`='2' AND bm.`type`='3' AND bm.store='$store' AND bm.`status` IN ($status) $filter1 $filter2 $filter3 $filter4 $orderby";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){	
		$bi_invoice_no[]=$row[0]; 
		$bi_cust[]=$row[1]; 
		$bi_district[]=$row[2]; 
		$bi_status[]=$row[3]; 
		$bi_billed_by[]=$salesman[$row[4]]; 
		$bi_billed_date[]=$row[5]; 
		$bi_billed_time[]=$row[6]; 
		if($row[7]!='') $bi_seen_by[]=$salesman[$row[7]];  else $bi_seen_by[]='';
		$bi_seen_date[]=$row[8]; 
		$bi_seen_time[]=$row[9]; 
		if($row[10]!='') $bi_picked_by[]=$salesman[$row[10]]; else $bi_picked_by[]='';
		$bi_picked_date[]=$row[11]; 
		$bi_picked_time[]=$row[12]; 
		if($row[13]!='') $bi_repaired_by[]=$salesman[$row[13]]; else $bi_repaired_by[]='';
		$bi_repaired_date[]=$row[14]; 
		$bi_repaired_time[]=$row[15]; 
		if($row[16]!='') $bi_deliverd_by[]=$salesman[$row[16]]; else $bi_deliverd_by[]='';
		$bi_deliverd_date[]=$row[17]; 
		$bi_deliverd_time[]=$row[18]; 
	} 
}

function getOneOrder(){
global $button,$bill_id,$bi_desc,$bi_qty,$bi_price,$total,$ledc2,$pay_id,$cash_amount,$chque_amount,$bi_cust,$bi_date,$bi_salesman,$bi_seen_by,$bi_seen_date, 
		$bi_seen_time,$bi_picked_by,$bi_picked_date,$bi_picked_time,$bi_repaired_by,$bi_repaired_date,$bi_repaired_time,
		$bi_deliverd_by,$bi_deliverd_date,$bi_deliverd_time,$order_rin_id,$order_part_name,$order_part_drawer,$order_part_qty,$order_part_uprice,$bi_comment,$job_owner,$gpm_exceed,$cogs,$bm_bocom_type,$bm_bocom,$bm_bocom2,$bm_force_accept,$bi_repair_sn,$history_inv,$history_repair;
	$invoice_no=$_REQUEST['id'];
	$cash_amount=$chque_amount=$total=$cogs=0;
	$history_inv=$history_repair=$order_part_name=array();
	include('config.php');
	
	$query1="SELECT id,username FROM userprofile";
	$result1=mysqli_query($conn,$query1);
	while($row1=mysqli_fetch_array($result1)){	$salesman[$row1[0]]=$row1[1]; 	} 
	
		$query="SELECT bi.id,inv.description,bi.qty,bi.unit_price,bi.`comment`,cu.name,bi.`date`,up.username,bm.seen_by
,date(bm.seen_timestamp),time(bm.seen_timestamp),bm.packed_by,date(bm.packed_timestamp),time(bm.packed_timestamp),
bm.shipped_by,date(bm.shipped_timestamp),time(bm.shipped_timestamp),bm.deliverd_by,date(bm.deliverd_timestamp),time(bm.deliverd_timestamp),bm.`status`,bm.back_off_com_type,bm.back_off_comment,bm.back_off_comment2,bm.repair_force_accept,bi.repair_model,bi.repair_sn
 FROM bill_main bm, bill bi, inventory_items inv, cust cu, userprofile up 
WHERE bi.invoice_no=bm.invoice_no AND bm.billed_by=up.id AND bm.`cust`=cu.id AND bi.item=inv.id AND  bi.invoice_no='$invoice_no'";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$bill_id[]=$row[0];
			$bi_desc[]=$row[1];
			$bi_qty[]=$row[2];
			$bi_price[]=round($row[3]);
			$bi_comment[]=$row[4];
			$total+=$row[2]*$row[3];
			$ledc2[]=str_repeat('_',(12-strlen(number_format($row[2]*$row[3]))));
			$bi_cust=$row[5];
			$bi_date=$row[6];
			$bi_salesman=$row[7];
		$job_owner=$row[11];	
		if($row[8]!='') $bi_seen_by=$salesman[$row[8]]; else $bi_seen_by='';
		$bi_seen_date=$row[9]; 
		$bi_seen_time=$row[10]; 
		if($row[11]!='') $bi_picked_by=$salesman[$row[11]];  else $bi_picked_by='';
		$bi_picked_date=$row[12]; 
		$bi_picked_time=$row[13]; 
		if($row[14]!='') $bi_repaired_by=$salesman[$row[14]];  else $bi_repaired_by='';
		$bi_repaired_date=$row[15]; 
		$bi_repaired_time=$row[16]; 
		if($row[17]!='') $bi_deliverd_by=$salesman[$row[17]];  else $bi_deliverd_by='';
		$bi_deliverd_date=$row[18]; 
		$bi_deliverd_time=$row[18]; 
		$bm_status=$row[20]; 
		$bm_bocom_type=$row[21]; 
		$bm_bocom=$row[22]; 
		$bm_bocom2=$row[23]; 
		$bm_force_accept=$row[24]; 
		$bi_repair_sn[]='<hr />Model- '.$row[25].'<br />SN- '.$row[26]; 
		if($bm_status==1 || $bm_status==2) $button='Pick';
		if($bm_status==3) $button='Repair';
		if($bm_status==4 || $bm_status==5 ||$bm_status==6 || $bm_status==7) $button='Print';
		
		if($row[25]!=''){
			$query1="SELECT DISTINCT invoice_no FROM bill WHERE `comment` LIKE '%$row[25]%'";
			$result1=mysqli_query($conn,$query1);
			while($row1=mysqli_fetch_array($result1)){
				$history_inv[]=$row1[0];
			}
			$query1="SELECT DISTINCT invoice_no FROM bill WHERE repair_sn='$row[25]'";
			$result1=mysqli_query($conn,$query1);
			while($row1=mysqli_fetch_array($result1)){
				$history_repair[]=$row1[0];
			}
		}
	}
	
	$query1="SELECT id,payment_type,amount FROM payment WHERE invoice_no='$invoice_no'";
	$result1=mysqli_query($conn,$query1);
	while($row1=mysqli_fetch_array($result1)){
		$pay_id[]=$row1[0];
		if($row1[1]==1) $cash_amount=$row1[2];
		if($row1[1]==2) $chque_amount=$row1[2];
	}
	
	//----------------Seen---------------//
	if($bi_seen_by==''){
		if($bm_status==1)	setStatus('seen');
	}
	//--------------------------------------------------------//
	$query="SELECT rin.id,rp.name,rp.drawer_no,rin.qty,rin.unit_price FROM repair_invoice rin, repair_parts rp WHERE rin.parts=rp.id AND rin.invoice_no='$invoice_no'";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$order_rin_id[]=$row[0];
		$order_part_name[]=$row[1];
		$order_part_drawer[]=$row[2];
		$order_part_qty[]=$row[3];
		$order_part_uprice[]=$row[4];
		$cogs+=$row[3]*$row[4];
	}
	
	$result = mysqli_query($conn,"SELECT value FROM settings WHERE setting='repair_profit'");
	$row = mysqli_fetch_assoc($result);
	$gpm=$row['value'];  //Gross profit margin
	
	if($total==0) $gpm2=0; else	$gpm2=(($total-$cogs)/$total)*100;
	if($gpm>$gpm2) $gpm_exceed=true; else $gpm_exceed=false;
}

function getCustDetails(){
global $cu_name,$cu_nic,$cu_mobile,$cu_address;
	$invoice_no=$_REQUEST['id'];
	include('config.php');
	$result = mysqli_query($conn,"SELECT cu.name,cu.nic,cu.mobile,cu.home_address FROM bill_main bm, cust cu WHERE bm.`cust`=cu.id AND bm.invoice_no=$invoice_no");
	$row = mysqli_fetch_assoc($result);
	$cu_name=$row['name'];
	$cu_nic=$row['nic'];
	$cu_mobile=$row['mobile'];
	$cu_address=$row['home_address'];
}

function getParts(){
	global $part_id,$part_name;
	include('config.php');
	$query="SELECT id,name FROM repair_parts WHERE `status`=1";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$part_id[]=$row[0];
		$part_name[]=$row[1];
	}
}

function getPartDrawer(){
	$part_name=$_GET['part_name'];
	include('config.php');
	$result = mysqli_query($conn,"SELECT drawer_no FROM repair_parts WHERE name='$part_name'");
	$row = mysqli_fetch_assoc($result);
	return $row['drawer_no'];
}

function setStatus($method){
	global $message;
	$out=true;
	$result1=false;
	$invoice_no=$_REQUEST['id'];
	$salesman=$_COOKIE['user_id'];	
	$time_now=timeNow();
	$cost_default=0;
	
	include('config.php');
	
	$result = mysqli_query($conn,"SELECT `status` FROM bill_main WHERE invoice_no='$invoice_no'");
	$row = mysqli_fetch_assoc($result);
	$status=$row['status'];

	if($method=='seen'){ $query1="UPDATE `bill_main` SET `seen_by`='$salesman',`seen_timestamp`='$time_now',`status`='2' WHERE `invoice_no`='$invoice_no'"; }
	if($method=='pending'){ $query1="UPDATE `bill_main` SET `seen_by`='',`seen_timestamp`='',`packed_by`='',`packed_timestamp`='',`shipped_by`='',`shipped_timestamp`='',`deliverd_by`='',`deviverd_timestamp`='', `status`='1' WHERE `invoice_no`='$invoice_no'"; }
	if($method=='pick'){  $query1="UPDATE `bill_main` SET `packed_by`='$salesman',`packed_timestamp`='$time_now', `status`='3' WHERE `invoice_no`='$invoice_no'"; }
	if($method=='reject'){ $query1="UPDATE `bill_main` SET `shipped_by`='$salesman',`shipped_timestamp`='$time_now',`status`='6' WHERE `invoice_no`='$invoice_no'"; }
	if($method=='finish'){ $query1="UPDATE `bill_main` SET `shipped_by`='$salesman',`shipped_timestamp`='$time_now',`status`='4' WHERE `invoice_no`='$invoice_no'"; }
	if(($status==1 && $method=='seen')||($status==2 && $method=='pick')||($status==3 && $method=='reject')||($status==3 && $method=='finish')){	$result1=mysqli_query($conn,$query1);  }
	if(!$result1){ $msg='Job Status Could Not be Changed!'; $out=false; }
	
	if($out && ($method=='finish')){
		$query2="SELECT SUM(qty * unit_price) as `total` FROM repair_invoice WHERE invoice_no='$invoice_no'";
		$result2=mysqli_query($conn,$query2);
		$row2 = mysqli_fetch_assoc($result2);
		$job_total_cost=$row2['total'];
		
		$query="SELECT id,cost FROM bill WHERE `invoice_no`='$invoice_no'";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$bi_id=$row[0];
			if($row[1]>0) $cost_default++;
		}
		if($cost_default==0){ //if a default cost is not set for repair item, then job cost will be updated based on assign repair parts
			$query2="UPDATE bill SET `cost`='$job_total_cost' WHERE `id`='$bi_id'";
			$result2=mysqli_query($conn,$query2);
		}
	}
		
	if($out){
		if($method=='finish') sms3($invoice_no,'finish');
		if($method=='reject') sms3($invoice_no,'reject');
		$message='Job Status Updated Successfully!';
		return true;
	}else{
		$message=$msg;
		return false;
	}
}

function sms3($invoice_no,$method){
	$msg='';
	$sub_system=$_COOKIE['sub_system'];
	$inf_company=inf_company(1);
	$inf_from_email=inf_from_email();
	$inf_to_email=inf_to_email();
	$sms_data=json_decode(sms_credential($sub_system));
	$sms_user=$sms_data->{"user"};
	$sms_pass=$sms_data->{"pass"};
	$sms_balance=$sms_data->{"balance"};
	if($method=='finish') $txt1='Your+Phone+Was+Repaired+Successfully.\nPlease+Come+and+Collect+the+Phone';
	if($method=='reject') $txt1='Sorry!+Your+Phone+Could+Not+be+Repaired.+Please+Come+and+Collect+the+Phone';
	
	include('config.php');
		$result = mysqli_query($conn,"SELECT cu.sms, SUM(bi.qty * bi.unit_price) AS total, cu.mobile, bm.`type` FROM bill_main bm, bill bi, cust cu WHERE bm.invoice_no=bi.invoice_no AND bm.`cust`=cu.id AND bm.invoice_no='$invoice_no'");
		$row = mysqli_fetch_assoc($result);
		$sms_cust=$row['sms'];
		$bill_total=$row['total'];
		$mobile=$row['mobile'];
		$bm_type=$row['type'];
		
		if(($sms_cust==1)&&($sms_balance>0)&&($_SERVER['SERVER_NAME']==inf_url_primary())&&($bm_type==3)&&(strpos($mobile,"7")==1)){
			$message =str_replace(" ","+",$inf_company).'+\nInvoice+no:+'.str_pad($invoice_no, 7, "0", STR_PAD_LEFT).'+\n'.$txt1.'\n+++++++Thank+you!';
			/*
			$to      = 'netdefine@gmail.com';
			$subject = $mobile;
			$headers = 'From: '.$inf_from_email. "\r\n" .
			    'Reply-To: '.$inf_from_email. "\r\n" .
			    'X-Mailer: PHP/' . phpversion();
			$mailstatus=mail($to, $subject, $message, $headers);
			*/
			//---------------------------------------------------------------//
			$text = urlencode($message);
			
			$baseurl ="http://www.textit.biz/sendmsg";
			$url = "$baseurl/?id=$sms_user&pw=$sms_pass&to=$mobile&text=$text";
			$ret = file($url);
			$res= explode(":",$ret[0]);
			
			if (trim($res[0])=="OK") $mailstatus=true; else $mailstatus=false;
			//----------------------------------------------------------------//
			if($mailstatus){
				$sms_balance--;
				$query="UPDATE `bill_main` SET `sms`='1' WHERE `invoice_no`='$invoice_no'";
				$result=mysqli_query($conn,$query);
				if(set_sms_balance($sub_system,$sms_balance))	$msg='SMS Sent<hr />'; 	else $msg='Database Cound Not be Updated<hr />'; 
			}else $msg='Mail Could not be Sent<hr />';
		}else $msg='SMS Disabled on Customer';
//	print $msg;
}

function apendPart(){
	global $message;
	$invoice_no=$_REQUEST['id'];
	$store=$_COOKIE['store'];
	$part_id=$_POST['rpitm_id'];
	$qty=$_POST['qty'];
	$out=true;
	if($part_id==''){ $msg='Please Select a Part'; $out=false; }
	if($qty==''){ $msg='Please set a Qty'; $out=false; }
	
	include('config.php');
	$result = mysqli_query($conn,"SELECT rpi.part,rpi.qty,rpi.c_price FROM repair_parts_inventory rpi, repair_parts rp WHERE rpi.part=rp.id AND rp.id='$part_id' AND rpi.location='$store'");
	$row = mysqli_fetch_assoc($result);
	$part_id=$row['part'];
	$rin_qty=$row['qty'];
	$c_price=$row['c_price'];
	$rin_qty=$rin_qty-$qty;
	if($rin_qty<0){ $msg='Insufficient Qty'; $out=false; }
	
	if($out){
		$query1="INSERT INTO `repair_invoice` (`invoice_no`,`parts`,`qty`,`unit_price`) VALUES ('$invoice_no','$part_id','$qty','$c_price')";
		$result1=mysqli_query($conn,$query1);
		if($result1){
		$query2="UPDATE `repair_parts_inventory` SET `qty`='$rin_qty' WHERE `part`='$part_id' AND `location`='$store'";
		$result2=mysqli_query($conn,$query2);
		if(!$result2){ $msg='Error: Repair Part Inventory Mismatch. Please contact IT Support'; $out=false; }
		}else{ $msg='Error: Selected Part Could Not be Added to the Job'; $out=false; }
	}
	
	if($out){
		$message='Part was Added to the Job';
		return true;
	}else{
		$message=$msg;
		return false;
	}
}

function removePart(){
	global $message;
	$invoice_no=$_REQUEST['id'];
	$rin_id=$_GET['rin_id'];
	$store=$_COOKIE['store'];
	$out=true;

	include('config.php');
	$result = mysqli_query($conn,"SELECT parts,qty FROM repair_invoice WHERE id='$rin_id'");
	$row = mysqli_fetch_assoc($result);
	$part_id=$row['parts'];
	$rin_qty=$row['qty'];
	
	if($out){
		$query1="DELETE FROM `repair_invoice` WHERE id='$rin_id'";
		$result1=mysqli_query($conn,$query1);
		if($result1){
		$query2="UPDATE `repair_parts_inventory` SET `qty`=`qty`+$rin_qty WHERE `part`='$part_id' AND `location`='$store'";
		$result2=mysqli_query($conn,$query2);
		if(!$result2){ $msg='Error: Repair Part Inventory Mismatch. Please contact IT Support'; $out=false; }
		}else{ $msg='Error: The Part Could Not be Remove from the Job'; $out=false; }
	}
	
	if($out){
		$message='Part was Removed from the Job Successfully';
		return true;
	}else{
		$message=$msg;
		return false;
	}
}
/*
function orderPick(){
	global $message;
	$invoice_no=$_REQUEST['id'];
	$user=$_COOKIE['user_id'];
	$datetime=timeNow();
	$out=true;

	include('config.php');
	$result = mysqli_query($conn,"SELECT `status` FROM bill_main WHERE invoice_no='$invoice_no'");
	$row = mysqli_fetch_assoc($result);
	$bm_status=$row['status'];
	
	if($bm_status==2){
		$query1="UPDATE bill_main SET packed_by='$user', packed_timestamp='$datetime', `status`='3' WHERE invoice_no='$invoice_no'";
		$result1=mysqli_query($conn,$query1);
		if(!$result1){ $msg='Error: The Job Could Not be Picked'; $out=false; }
	}else{ $msg='Error: Unauthorize Request'; $out=false; }
	
	if($out){
		$message='The Job was Picked Successfully';
		return true;
	}else{
		$message=$msg;
		return false;
	}
}
*/
function orderUnassign(){
	global $message;
	$authorization=false;
	if(isset($_COOKIE['report'])){
		if($_COOKIE['report']==$_COOKIE['user_id']){
			$authorization=true;
		}
	}
	if(isset($_COOKIE['manager'])){
		if($_COOKIE['manager']==$_COOKIE['user_id']){
			$authorization=true;
		}
	}
	$invoice_no=$_REQUEST['id'];
	$out=true;

	include('config.php');
	$result = mysqli_query($conn,"SELECT `status` FROM bill_main WHERE invoice_no='$invoice_no'");
	$row = mysqli_fetch_assoc($result);
	$bm_status=$row['status'];
	
	if($bm_status==3 && $authorization){
		$query1="UPDATE bill_main SET packed_by=null, packed_timestamp=null, `status`='2' WHERE invoice_no='$invoice_no'";
		$result1=mysqli_query($conn,$query1);
		if(!$result1){ $msg='Error: The Job Could Not be Unassigned'; $out=false; }
	}else{ $msg='Error: Unauthorize Request'; $out=false; }
	
	if($out){
		$message='The Job was Unassigned Successfully';
		return true;
	}else{
		$message=$msg;
		return false;
	}
}

function updatePrice(){
	global $message,$bm_inv;
	$bi_id=$_GET['id'];
	$new_price=$_GET['new_price'];
	$out=false;
	include('config.php');
	$result = mysqli_query($conn,"SELECT bm.invoice_no,bm.`status`,bi.qty FROM bill_main bm, bill bi WHERE bm.invoice_no=bi.invoice_no AND bi.id='$bi_id'");
	$row = mysqli_fetch_assoc($result);
	$bm_inv=$row['invoice_no'];
	$bm_status=$row['status'];
	$bi_qty=$row['qty'];
	if($bm_status==3){
		$new_unit_price=$new_price/$bi_qty;
		$query1="UPDATE bill SET `unit_price`='$new_unit_price' WHERE id='$bi_id'";
		$result1=mysqli_query($conn,$query1);
		if($result1){ $out=true;
			$query="SELECT qty*unit_price FROM bill WHERE invoice_no='$bm_inv'";
			$row=mysqli_fetch_row(mysqli_query($conn,$query));
			$bm_total=$row[0];
			$query="UPDATE bill_main SET `invoice_+total`='$bm_total',`invoice_-total`=0 WHERE `invoice_no`='$bm_inv'";
			$result=mysqli_query($conn,$query);
		}
	}
	
	if($out){
		$message='Job Price Updated Successfully';
		return true;;
	}else{
		$message='Error: Job Price Could not be Updated';
		return false;
	}
}

function getRepairComments(){
	global $rc_id,$rc_type,$rc_private_public,$rc_time,$rc_user,$rc_comment,$rc_user_id, $conn;
	$bm_inv=$_GET['id'];
	$rc_id=array();
	include('config.php');
	$query="SELECT rc.id,rc.`type`,rc.private_public,rc.`datetime`,up.username,rc.`comment`,rc.`user` FROM repair_comment rc, userprofile up WHERE rc.`user`=up.id AND rc.bill_no='$bm_inv'";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$rc_id[]=$row[0];
		if($row[1]==1) $rc_type[]='By Technician'; elseif($row[1]==2) $rc_type[]='By Manager'; elseif($row[1]==3) $rc_type[]='By Salesman';
		if($row[2]==1) $rc_private_public[]='Private'; else $rc_private_public[]='Public';
		$rc_time[]=$row[3];
		$rc_user[]=ucfirst($row[4]);
		$rc_comment[]=$row[5];
		$rc_user_id[]=$row[6];
	}
}

function addRepairComment($type){
	global $message,$bm_inv;
	$bm_inv=$_GET['id'];
	$user=$_COOKIE['user_id'];
	$comment=$_POST['comment'];
	if(isset($_POST['publiccomment'])&&$_POST['publiccomment']=='yes') $private_public=2; else $private_public=1;
	$datetime=timeNow();
	$message='Error: Comment Could Not Be Added!';
	$out=false;
	include('config.php');
	$query1="INSERT INTO repair_comment (`bill_no`,`type`,`private_public`,`datetime`,`user`,`comment`) VALUES ('$bm_inv','$type','$private_public','$datetime','$user','$comment')";
	$result1=mysqli_query($conn,$query1);
	if($result1) $out=true;

	if($out){
		$message='Comment Was Added Successfully';
		return true;;
	}else{
		return false;
	}
}

function delRepairComment(){
	global $message,$bm_inv;
	$id=$_GET['id'];
	$user=$_COOKIE['user_id'];
	$message='Error: Comment Could Not Be Deleted!';
	$out=false;
	include('config.php');
	$result = mysqli_query($conn,"SELECT bill_no,`user` FROM repair_comment WHERE id='$id'");
	$row = mysqli_fetch_assoc($result);
	$bm_inv=$row['bill_no'];
	$rep_user=$row['user'];
	if($rep_user==$user) $out=true; else $message='Error: Unauthorize Request!';
	
	if($out){
		$query1="DELETE FROM repair_comment WHERE id='$id'";
		$result1=mysqli_query($conn,$query1);
		if($result1) $out=true;
	}
	if($out){
		$message='Comment Was Deleted Successfully';
		return true;;
	}else{
		return false;
	}
}

function addBOComment($co){
	global $message,$bm_inv;
	$bm_inv=$_GET['id'];
	$bo_comment=$_POST['bo_comment'];
	if($_POST['pre_more']!=''){
		$bo_comment=$bo_comment.$_POST['pre_more'].';';
	}
	$user=$_COOKIE['user_id'];		
	$message='Error: '.$co.'-Check Could not be Updated';
	$out=false;
	include('config.php');
	$result = mysqli_query($conn,"SELECT packed_by FROM bill_main WHERE invoice_no='$bm_inv'");
	$row = mysqli_fetch_assoc($result);
	$job_owner=$row['packed_by'];
	if($job_owner==$user){
		if($co=='Pre'){
			$query1="UPDATE bill_main SET `back_off_com_type`='2',`back_off_comment`='$bo_comment' WHERE invoice_no='$bm_inv'";
		}else{
			$query1="UPDATE bill_main SET `back_off_comment2`='$bo_comment' WHERE invoice_no='$bm_inv'";
		}
		$result1=mysqli_query($conn,$query1);
		if($result1) $out=true;
	}else $message='Error: Unauthorize Request !';
	
	if($out){
		$message=$co.'-Check was Updated Successfully';
		return true;;
	}else{
		return false;
	}
}

function removeBOComment($co){
	global $message,$bm_inv;
	$bm_inv=$_GET['id'];
	$auth_code=$_GET['auth_code'];
	$message='Error: '.$co.'-Check Could not be Updated';
	$authorize_code=rand(1000,9999);
	$out=false;
	include('config.php');
	$result = mysqli_query($conn,"SELECT authorize_code FROM bill_main WHERE invoice_no='$bm_inv'");
	$row = mysqli_fetch_assoc($result);
	$bm_auth_code=$row['authorize_code'];
	if($bm_auth_code==$auth_code){
		if($co=='Pre'){
			$query1="UPDATE bill_main SET `back_off_com_type`='0',`back_off_comment`=NULL,`authorize_code`='$authorize_code' WHERE invoice_no='$bm_inv'";
		}else{
			$query1="UPDATE bill_main SET `back_off_comment2`=NULL,`authorize_code`='$authorize_code' WHERE invoice_no='$bm_inv'";
		}
		$result1=mysqli_query($conn,$query1);
		if($result1) $out=true;
	}else $message='Error: Invalid Auth Code';
	
	if($out){
		$message=$co.'-Check was Updated Successfully';
		return true;;
	}else{
		return false;
	}
}

function repairForceAccept(){
	global $message,$bm_inv;
	$bm_inv=$_GET['id'];
	$message='Error: Job Could not be Force Accepted';
	$out=false;
	include('config.php');
		$query1="UPDATE bill_main SET `repair_force_accept`=`invoice_+total` WHERE invoice_no='$bm_inv'";
		$result1=mysqli_query($conn,$query1);
		if($result1) $out=true;

	if($out){
		$message='Job was Force Accepted Successfully';
		return true;;
	}else{
		return false;
	}
}

function repairStatus($st_id){
	switch($st_id){
		case 0: 
			$st_name='deleted';     
		break;
		case 1: 
			$st_name='Pending';     
		break;
		case 2: 
			$st_name='Seen';     
		break;
		case 3: 
			$st_name='Picked';     
		break;
		case 4: 
			$st_name='Repaired';     
		break;
		case 5: 
			$st_name='Repaired - Delivered';     
		break;
		case 6: 
			$st_name='Rejected';     
		break;
		case 7: 
			$st_name='Rejected - Delivered';     
		break;
		default:
			$st_name='Unknown'; 
		break;
	}
	return $st_name;
}

function searchJob(){
	global $invoice_no,$cu_status,$cu_status_name,$cu_payment,$cu_tech,$tech_id,$tech_name;
	if(isset($_REQUEST['invoice_no'])){
		$invoice_no=$_REQUEST['invoice_no'];
	
		include('config.php');
		$result = mysqli_query($conn,"SELECT bm.`status`,up.username FROM bill_main bm LEFT JOIN userprofile up ON bm.packed_by=up.id WHERE bm.invoice_no='$invoice_no'");
		$row = mysqli_fetch_assoc($result);
		$cu_status=$row['status'];
		$cu_tech=$row['username'];
		$cu_status_name=repairStatus($cu_status);
		$result = mysqli_query($conn,"SELECT count(id) as `count` FROM payment WHERE invoice_no='$invoice_no' AND `status`=0");
		$row = mysqli_fetch_assoc($result);
		$cu_payment=$row['count'];
	
		$query="SELECT up.id,up.username FROM userprofile up, permission pe, `function` fn WHERE up.id=pe.`user` AND pe.`function`=fn.id AND fn.name='Repair' AND up.`status`='0'";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$tech_id[]=$row[0];
			$tech_name[]=$row[1];
		}
	}
}

function updateStatus(){
	global $message;
	$invoice_no=$_POST['invoice_no'];
	$new_st=$_POST['new_st'];
	$new_tech=$_POST['new_tech'];
	if($new_st!='') $st_qry="`status`='$new_st',"; else $st_qry="";
	if($new_tech!='') $tech_qry="`packed_by`='$new_tech',"; else $tech_qry="";
	include('config.php');
	$result = mysqli_query($conn,"SELECT `status` FROM bill_main WHERE invoice_no='$invoice_no'");
	$row = mysqli_fetch_assoc($result);
	$cu_status=$row['status'];
	if(($cu_status==4 || $cu_status==5 || $cu_status==6 || $cu_status==7)&&(($new_st!='')||($new_tech!=''))){
		$query1="UPDATE bill_main SET $st_qry $tech_qry `lock`='2' WHERE invoice_no='$invoice_no'";
		$result1=mysqli_query($conn,$query1);
		if($result1) $out=true;
	}

	if($out){
		$message='Job Status was Changed Successfully';
		return true;;
	}else{
		$message='Error: Job Status Could Not be Changed';
	}
}

function listRepItem($sub_system){
	global $data_list, $fn;
	$fn = 'selectRepPart';
	$data_list = array();
	if ($_POST['keyword']) {
		$keyword = $_POST['keyword'];
		$qry = "`name` LIKE '%$keyword%'";
		include('config.php');
		$query = "SELECT `name` FROM repair_parts WHERE `status`=1 AND `name` LIKE '%$keyword%' LIMIT 20";
		$result = mysqli_query($conn2, $query);
		while ($row = mysqli_fetch_array($result)) {
			$data_list[] = $row[0];
		}
	}
}

function moreRepItem($sub_system){
	if (isset($_POST['val'])) {
		$val = $_POST['val'];
		$store=$_COOKIE['store'];	
		$jasonArray = array();
		include('config.php');
		$query = "SELECT rp.id,rp.drawer_no,rpi.qty FROM repair_parts rp, repair_parts_inventory rpi WHERE rp.id=rpi.part AND rpi.location='$store' AND rp.`status`=1 AND rp.`name`='$val' LIMIT 1";
		$row = mysqli_fetch_row(mysqli_query($conn2, $query));
		$jasonArray["rpitm_id"] = $row[0];
		$jasonArray["rpitm_drawer"] = $row[1];
		$jasonArray["rpitm_qty"] = $row[2];
		$myJSON = json_encode($jasonArray);

		return $myJSON;
	}
}
?>