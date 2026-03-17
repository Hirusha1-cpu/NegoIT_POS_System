<?php

function isMobile() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}

function getUnlockBills(){
	global $bm_bill,$bm_time,$bm_user;
	$today=dateNow();
	$bm_bill=array();
	include('../config.php');
		$query="SELECT bm.invoice_no,time(bm.billed_timestamp),up.username FROM bill bi, bill_main bm, userprofile up WHERE bi.invoice_no=bm.invoice_no AND bm.billed_by=up.id AND bm.`status`!='0' AND bm.`lock`=0 AND date(bm.billed_timestamp)='$today' GROUP BY bm.invoice_no ORDER BY bm.invoice_no DESC";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$bm_bill[]=$row[0];
			$bm_time[]=$row[1];
			$bm_user[]=$row[2];
	} 
}

function getOneLockSt(){
	global $lockstatus,$py_inv,$py_type,$py_amount;
	if(isset($_REQUEST['lockinvid'])){
		include('../config.php');
		$bill_id=$_REQUEST['lockinvid'];
		$result = mysqli_query($conn,"SELECT `lock` FROM bill_main WHERE invoice_no='$bill_id'");
		$row = mysqli_fetch_row($result);
		$lockstatus=$row[0];
		
		$query="SELECT id,payment_type,amount FROM payment WHERE `status`=0 AND invoice_no='$bill_id'";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$py_inv[]=$row[0];
			if($row[1]==1) $py_type[]='Cash';
			if($row[1]==2) $py_type[]='Chque';
			$py_amount[]=$row[2];
		} 
	}
}

function changeLock(){
	global $message,$bill_id; 
	
	include('../config.php');
	$bill_id=$_REQUEST['lockinvid'];
	$result = mysqli_query($conn,"SELECT `lock` FROM bill_main WHERE invoice_no='$bill_id'");
	$row = mysqli_fetch_row($result);
	$lockstatus=$row[0];
	if($lockstatus==0) $newlock=1;
	if($lockstatus==1) $newlock=0;
	
	$result = mysqli_query($conn,"UPDATE bill_main SET `lock`='$newlock' WHERE invoice_no='$bill_id'");
	
	if($result){
		$message='Lock was Updated Successfully!';
		return true;
	}else{
		$message='Lock could not be Updated!';
		return false;
	}
}

function searchDelete(){
	global $message,$type,$id;
	$type=$_GET['type'];
	include('../config.php');
	if($type=='bill'){
		$message='Invalid Invoice Number';
		$id=ltrim($_POST['search1'], '0');
		$query="SELECT count(invoice_no) FROM bill_main WHERE invoice_no='$id'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		if($row[0]==1) return true; else return false;
	} else
	if($type=='pay'){
		$message='Invalid Payment Number';
		$id=ltrim($_POST['search2'], '0');
		$query="SELECT count(id) FROM payment WHERE id='$id'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		if($row[0]==1) return true; else return false;
	}else
	if($type=='commission'){
		$message='Invalid Commission Number';
		$id=ltrim($_POST['search3'], '0');
		$query="SELECT count(id) FROM hp_commission_main WHERE id='$id'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		if($row[0]==1) return true; else return false;
	}else{
		$message='Invalid Operation';
		return false;
	}
}

function searchInv(){
	global $inv_found,$id,$inv_billed_by,$inv_type_id,$inv_store,$inv_sms,$inv_date,$inv_total,$status_out,$inv_status,$status_color;
	$inv_found=false;
	include('../config.php');
	if(isset($_GET['bill_no'])){
		$id=$_GET['bill_no'];
		$query="SELECT bm.billed_by,st.name,date(bm.order_timestamp),(bm.`invoice_+total` + bm.`invoice_-total`),bm.`type`,bm.sms,bm.`status` FROM bill_main bm, stores st WHERE bm.store=st.id AND bm.invoice_no='$id'";
		$row=mysqli_fetch_row(mysqli_query($conn2,$query));
		$inv_billed_by=$row[0];
		$inv_store=$row[1];
		$inv_date=$row[2];
		$inv_total=$row[3];
		$inv_type=$row[4];
		$inv_type_id=$row[4];
		$inv_sms=$row[5];
		$inv_status=$row[6];
		if($row[0]!=''){
		switch($inv_status){
			case 0: $status_out='Deleted'; $status_color='#FF3300'; break;
			case 1: $status_out='Billed (Pending)'; $status_color='yellow'; break;
			case 2: $status_out='Billed (Picked)'; $status_color='yellow'; break;
			case 3: if($inv_type==3){ $status_out='Billed (Picked)'; } else { $status_out='Billed (Packed)'; } $status_color='yellow'; break;
			case 4: if($inv_type==3){ $status_out='Repaired'; }else{ $status_out='Billed (Shipped)'; } $status_color='yellow'; break;
			case 5: if($inv_type==3){ $status_out='Repaired | Delivered'; }else{ $status_out='Billed (Delivered)'; } $status_color='white'; break;
			case 6: $status_out='Rejected'; $status_color='orange'; break;
			case 7: $status_out='Rejected | Delivered'; $status_color='orange'; break;
		}
		}
		if($row[0]!='') $inv_found=true;
	}
}

function getLock($type,$status){
	$lock=0;
	switch ($status){
		case 1: 
			if($type==1) $lock=1; 
			if($type==4) $lock=2; 
		break;
		case 2: 
			if($type==1) $lock=1; 
			if($type==4) $lock=2; 
		break;
		case 3: 
			if($type==1) $lock=1; 
			if($type==4) $lock=1; 
		break;
		case 4: 
			if($type==1) $lock=1; 
			if($type==4) $lock=1; 
		break;
		case 5: 
			if($type==1) $lock=1; 
			if($type==4) $lock=1; 
		break;
	}
	return $lock;
}

function setInvMain(){
	global $message,$bill_no;
	$bill_no=$_POST['bill_no'];
	$type=$_POST['type'];
	$status=$_POST['status'];
	$sms=$_POST['sms'];
	$lock=getLock($type,$status);
	$out=true;
	$message='Nothing is Changed';
	include('../config.php');
	
	$query="SELECT `type`,sms,`status` FROM bill_main WHERE invoice_no='$bill_no'";
	$row=mysqli_fetch_row(mysqli_query($conn2,$query));
	$old_type=$row[0];
	$old_sms=$row[1];
	$old_status=$row[2];
	
	if($out && ($old_type!=$type)){
		$query="UPDATE bill_main SET `type`='$type',`lock`='$lock' WHERE invoice_no='$bill_no'";
		$result=mysqli_query($conn,$query);
		if($result) $message='Invoice Details were Changed Successfully'; else{ $message='Error: Invoice Details could not be Changed !'; $out=false; }
	}
	if($out && ($old_status!=$status)){
		$query="UPDATE bill_main SET `status`='$status',`lock`='$lock' WHERE invoice_no='$bill_no'";
		$result=mysqli_query($conn,$query);
		if($result) $message='Invoice Details were Changed Successfully'; else{ $message='Error: Invoice Details could not be Changed !'; $out=false; }
	}
	if($out && ($old_sms!=$sms)){
		$query="UPDATE bill_main SET `sms`='$sms' WHERE invoice_no='$bill_no'";
		$result=mysqli_query($conn,$query);
		if($result) $message='Invoice Details were Changed Successfully'; else{ $message='Error: Invoice Details could not be Changed !'; $out=false; }
	}
	
	if($out) return true; else return false;
}

function billStatus(){
	global $bm_status,$bm_lock,$bm_type,$bm_module,$bm_cust,$status_out,$status_color;
	$invoice_no=$_REQUEST['id'];
	$today=dateNow();
	include('../config.php');
	$query="SELECT billed_by,date(`billed_timestamp`),`status`,`lock`,`store`,`type`,`module`,`cust` FROM bill_main WHERE invoice_no='$invoice_no'";
	$result=mysqli_query($conn,$query);
	$row = mysqli_fetch_row($result);
		$salesman=$row[0];
		$date=$row[1];
		$bm_status=$row[2];
		$bm_lock=$row[3];
		$bm_store=$row[4];
		$bm_type=$row[5];
		$bm_module=$row[6];
		$bm_cust=$row[7];
			
	switch($bm_status){
		case 0: $status_out='Deleted'; $status_color='#FF3300'; break;
		case 1: $status_out='Billed (Pending)'; $status_color='yellow'; break;
		case 2: $status_out='Billed (Picked)'; $status_color='yellow'; break;
		case 3: if($bm_type==3){ $status_out='Billed (Picked)'; } else { $status_out='Billed (Packed)'; } $status_color='yellow'; break;
		case 4: if($bm_type==3){ $status_out='Repaired'; }else{ $status_out='Billed (Shipped)'; } $status_color='yellow'; break;
		case 5: if($bm_type==3){ $status_out='Repaired | Delivered'; }else{ $status_out='Billed (Delivered)'; } $status_color='white'; break;
		case 6: $status_out='Rejected'; $status_color='orange'; break;
		case 7: $status_out='Rejected | Delivered'; $status_color='orange'; break;
	}
	if($bm_lock==0 && $bm_status!=0){ $status_out='Unlocked Bill';  $status_color='yellow'; }
}

function generateInvoice(){
global $print_time,$tm_company,$tm_address,$tm_tel,$tm_web,$tm_email,$chq0_fullNo,$bill_id,$bi_desc,$bi_code,$bi_discount,$bi_qty,$bi_price,$total,$ledc2,$bi_drawer,$bi_type,$pay_id,$cash_amount,$chque_amount,$chq0_date,$bi_cust0,$bi_cust,$bi_salesman_id,$up_salesman,$bi_date,$bi_time,$cu_id,$cu_details,$up_mobile,$bm_status,$bm_quotation_no,$qm_warranty,$qm_terms,$qm_po,$bm_packed_by;
	$invoice_no=$_REQUEST['id'];
	$chq0_no=$chq0_bnk=$chq0_branch=$bm_packed_by='';
	$cash_amount=$chque_amount=0;
	$sn_list=array();
	include('../config.php');
		$result = mysqli_query($conn,"SELECT value FROM settings WHERE setting='timezone'");
		$row = mysqli_fetch_assoc($result);
		$timezone=$row['value'];
		$print_time=date("Y-m-d H:i:s",time()+(60*60*$timezone));
	
		$break_point=1;
		
		$query="SELECT bm.`type`,cu.name,bm.billed_by,up.username,date(bm.billed_timestamp),time(bm.billed_timestamp),bm.`store`,cu.id,cu.nic,cu.mobile,cu.`status`,up.mobile,bm.`status`,bm.mapped_inventory,bm.quotation_no,bm.packed_by FROM bill_main bm, cust cu, userprofile up WHERE  up.id=bm.billed_by AND bm.`cust`=cu.id AND bm.invoice_no='$invoice_no'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		$bi_type=$row[0];
		if($row[10]==2) $bi_cust='Customer : '.$row[1].'<br />NIC: '.$row[8].' &nbsp;&nbsp; Mobile: '.$row[9]; else $bi_cust='Customer : '.$row[1];
		$bi_cust0=$row[1];
		$bi_salesman_id=$row[2];
		$up_salesman=$row[3];
		$bi_date=$row[4];
		$bi_time=$row[5];
		$store=$row[6];
		$cu_id=$row[7];
		$cu_details='NIC        : '.$row[8].'&#13;Mobile  : '.$row[9];
		$up_mobile=$row[11];
		$bm_status=$row[12];
		$bm_mapped_inventory=$row[13];
		$bm_quotation_no=$row[14];
		$bm_packed_by0=$row[15];

//		$query="SELECT bi.id,inv.description,bi.qty,bi.unit_price,inv.id,bm.`type`,bi.`comment`,cu.name,bm.billed_by,up.username, date(bm.billed_timestamp),time(bm.billed_timestamp),inv.code,bi.discount,bm.`store`,cu.id,cu.nic,cu.mobile,cu.`status`,up.mobile,inv.unic,bm.`status` FROM bill_main bm ,bill bi, inventory_items inv, cust cu, userprofile up WHERE bm.invoice_no=bi.invoice_no AND up.id=bm.billed_by AND bm.`cust`=cu.id AND bi.item=inv.id AND bi.invoice_no='$invoice_no' ORDER BY bi.id";
		$query="SELECT bi.id,inv.description,bi.qty,bi.unit_price,inv.id,bi.`comment`,inv.code,bi.discount,inv.unic FROM bill_main bm ,bill bi, inventory_items inv, cust cu, userprofile up WHERE bm.invoice_no=bi.invoice_no AND up.id=bm.billed_by AND bm.`cust`=cu.id AND bi.item=inv.id AND bi.invoice_no='$invoice_no' ORDER BY bi.id";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$bill_id_tmp=$row[0];
			$bill_id[]=$row[0];
			if(($bi_type==1)&&($row[8]==1)){
				$unic_sn='';
				$k=1;
			    $sn_list=explode(",",$row[5]);
				for($i=0;$i<sizeof($sn_list);$i++){
					if($k==$break_point){ $break_unic='<br />'; $k=0; }else{ $break_unic='&nbsp;&nbsp;'; }
					$unic_sn=$unic_sn.'['.$sn_list[$i].']'.$break_unic;
					$k++;
				}
				if($unic_sn!=''){
					$bi_desc[]=$row[1].'<br />'.$unic_sn;
				}else{
					$bi_desc[]=$row[1].'<br /><br />';
				}
			}else if(($bi_type==2)||($bi_type==3)||($bi_type==5)) $bi_desc[]=$row[5].'<br />&nbsp;&nbsp;&nbsp;&nbsp;[ '.$row[1].' ]'.'<br />';
			else if((($bi_type==1)||($bi_type==4))&&($row[8]==0)) $bi_desc[]=$row[1].'<br />';
			$bi_qty[]=$row[2];
			$bi_price[]=$row[3];
			$item_id=$row[4];
			$total+=$row[2]*$row[3];
			$ledc2[]=str_repeat('_',(12-strlen(number_format($row[2]*$row[3]))));
			$bi_code[]=$row[6];
			$bi_discount[]=$row[7];
		$result1 = mysqli_query($conn,"SELECT drawer_no FROM inventory_qty WHERE item='$item_id' AND location='$store'");
		$row1 = mysqli_fetch_assoc($result1);
			if(($bi_type==1)||($bi_type==4)) $bi_drawer[]=$row1['drawer_no']; else $bi_drawer[]='<br /><br /><br />';
			
	}
		$query1="SELECT id,payment_type,SUM(amount),chque_no,chque_bank,chque_branch,chque_date FROM payment WHERE bill_pay=1 AND invoice_no='$invoice_no' AND `status`=0 GROUP BY payment_type";
		$result1=mysqli_query($conn,$query1);
		while($row1=mysqli_fetch_array($result1)){
			$pay_id[]=$row1[0];
			if($row1[1]==1) $cash_amount=$row1[2];
			if($row1[1]==2) $chque_amount=$row1[2];
			$chq0_no=$row1[3];
			$chq0_bnk=$row1[4];
			$chq0_branch=$row1[5];
			$chq0_date=$row1[6];
	}
	if($chq0_bnk>0){
		$query2="SELECT bank_code FROM bank WHERE id=$chq0_bnk";
		$result2=mysqli_query($conn,$query2);
		while($row2=mysqli_fetch_array($result2)){ $chq0_bnk=$row2[0]; }
		$chq0_fullNo='[ Cheque No: '.$chq0_no.'-'.$chq0_bnk.'-'.$chq0_branch.' ]';
	}else $chq0_fullNo='';

	if($bm_quotation_no!=0){
		$result = mysqli_query($conn,"SELECT warranty,terms2,cust_po FROM quotation_main WHERE id='$bm_quotation_no'");
		$row = mysqli_fetch_assoc($result);
		$qm_warranty=$row['warranty'];
		$qm_terms=$row['terms2'];
		$qm_po=$row['cust_po'];
	}
	if($bm_packed_by0!=''){
		$result = mysqli_query($conn,"SELECT username FROM userprofile WHERE id='$bm_packed_by0'");
		$row = mysqli_fetch_assoc($result);
		$bm_packed_by=$row['username'];
	}
	
	$result = mysqli_query($conn,"SELECT shop_name,address,tel FROM stores WHERE id='$bm_mapped_inventory'");
	$row = mysqli_fetch_assoc($result);
	$tm_company=$row['shop_name'];
	$tm_address=$row['address'];
	$tm_tel=$row['tel'];
	$result = mysqli_query($conn,"SELECT value FROM settings WHERE setting='web'");
	$row = mysqli_fetch_assoc($result);
	$tm_web=$row['value'];
	$result = mysqli_query($conn,"SELECT value FROM settings WHERE setting='email'");
	$row = mysqli_fetch_assoc($result);
	$tm_email=$row['value'];
}

function generatePayment(){
global $tm_company,$tm_address,$tm_tel,$payment_id,$cust_name,$payment_type,$amount,$chque_no,$chque_bank,$chque_branch,$chque_date,$salesman,$payment_date,$invoice_no;
	$payment_id=$_REQUEST['id'];

	include('../config.php');
		$query="SELECT cu.name,py.payment_type,py.amount,py.chque_no,py.chque_bank,py.chque_branch,py.chque_date,up.username,date(py.payment_date),py.invoice_no,py.store FROM payment py, cust cu, userprofile up WHERE py.cust=cu.id AND py.salesman=up.id AND py.id='$payment_id'";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$cust_name=$row[0];
			$payment_type=$row[1];
			$amount=$row[2];
			$chque_no=$row[3];
			$chque_bank_id=$row[4];
			$chque_branch=$row[5];
			$chque_date=$row[6];
			$salesman=$row[7];
			$payment_date=$row[8];
			$invoice_no=$row[9];
			$store=$row[10];
	}
	
		$query="SELECT name FROM bank WHERE id='$chque_bank_id'";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){	$chque_bank=$row[0];	} 
		
	$result = mysqli_query($conn,"SELECT address,tel FROM stores WHERE id='$store'");
	$row = mysqli_fetch_assoc($result);
	$tm_address=$row['address'];
	$tm_tel=$row['tel'];

	$result = mysqli_query($conn,"SELECT value FROM settings WHERE setting='company_name'");
	$row = mysqli_fetch_assoc($result);
	$tm_company=$row['value'];
}

function payStatus(){
	global $paymentpermission,$py_status,$status_out,$status_color;
	$payment_no=$_REQUEST['id'];
	$user=$_COOKIE['user_id'];	
	$today=date("Y-m-d",time());
	include('..\config.php');
	$query="SELECT DISTINCT salesman,date(`payment_date`),`status` FROM payment WHERE id='$payment_no'";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$salesman=$row[0];
		$date=$row[1];
		$py_status=$row[2];
	} 
		
	switch($py_status){
		case 0: $status_out='Paid'; $status_color='white'; break;
		case 1: $status_out='Deleted'; $status_color='#FF3300'; break;
	}
}

function comStatus(){
	global $com_status,$status_out,$status_color;
	$com_no=$_REQUEST['id'];
	include('..\config.php');
	$query="SELECT count(id) FROM hp_commission_main WHERE id='$com_no'";
	$row=mysqli_fetch_row(mysqli_query($conn2,$query));
	$count=$row[0];
		
	if($count==1){
		$status_out='Active'; $status_color='white'; 
	}else{
		$status_out='Deleted'; $status_color='#FF3300'; 
	}
}

function getCategory(){
	global $cat_id,$cat_name;
	include('../config.php');
		$query="SELECT id,name FROM item_category";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$cat_id[]=$row[0];
			$cat_name[]=$row[1];
	} 
}

function getStore(){
	global $st_id,$st_name;
	include('../config.php');
		$query="SELECT id,name FROM stores WHERE `status`=1";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$st_id[]=$row[0];
			$st_name[]=$row[1];
	} 
}

function setClear(){
	global $message; 
	$category=$_REQUEST['category'];
	$store=$_REQUEST['store'];
	if(md5($_REQUEST['password'])=='c3f1396a463205b149f7559b01fea607'){
		include('../config.php');
	
		$result = mysqli_query($conn,"SELECT name FROM item_category WHERE `id`='$category'");
		$row = mysqli_fetch_row($result);
		$cat_name=$row[0];
		$result = mysqli_query($conn,"SELECT name FROM stores WHERE `id`='$store'");
		$row = mysqli_fetch_row($result);
		$st_name=$row[0];
		
		$result = mysqli_query($conn,"SELECT MAX(job_id) FROM backup_cat_qty1");
		$row = mysqli_fetch_row($result);
		$max_job_id=$row[0];
		if($max_job_id=='') $max_job_id=0;
		
		$next_job_id=$max_job_id+1;
		
		$query="SELECT itq.item,itq.id,itq.qty,itq.drawer_no FROM inventory_qty itq, inventory_items itm WHERE itm.id=itq.item AND itm.category='$category' AND itq.location='$store'";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$itq_item=$row[0];
			$itq_id=$row[1];
			$itq_qty=$row[2];
			$itq_drawer_no=$row[3];
			mysqli_query($conn,"INSERT INTO `backup_cat_qty1` (`job_id`,`category`,`store`,`itq_id`,`qty`,`drawer_no`) VALUES ('$next_job_id','$cat_name','$st_name','$itq_id','$itq_qty','$itq_drawer_no')");
		} 
		
		$query="SELECT itn.item,itn.id,itn.w_price,itn.r_price,itn.c_price,itn.qty,itn.shipment_no FROM inventory_new itn, inventory_items itm WHERE itm.id=itn.item AND itm.category='$category' AND itn.store='$store'";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$itn_item=$row[0];
			$itn_id=$row[1];
			$itn_w_price=$row[2];
			$itn_r_price=$row[3];
			$itn_c_price=$row[4];
			$itn_qty=$row[5];
			$itn_shipment_no=$row[6];
			mysqli_query($conn,"INSERT INTO `backup_cat_qty2` (`job_id`,`category`,`store`,`itn_id`,`itn_item`,`itn_w_price`,`itn_r_price`,`itn_c_price`,`itn_qty`,`shipment_no`) VALUES ('$next_job_id','$cat_name','$st_name','$itn_id','$itn_item','$itn_w_price','$itn_r_price','$itn_c_price','$itn_qty','$itn_shipment_no')");
			mysqli_query($conn,"DELETE FROM inventory_new WHERE id='$itn_id'");
		} 
		$result = mysqli_query($conn,"UPDATE inventory_qty itq, inventory_items itm SET itq.qty=0 WHERE itm.id=itq.item AND itm.category='$category' AND itq.location='$store'");
		
		if($result){
			$message='Item Qty of '.$cat_name.' in '.$st_name.' set to 0 Successfully!';
			return true;
		}else{
			$message='Category could not be Cleared!';
			return false;
		}
	}else{
		$message='Invalid Password!';
		return false;
	}
}

function getJobId(){
	global $last_job_id; 
	include('../config.php');
	$result = mysqli_query($conn,"SELECT MAX(job_id) FROM backup_cat_qty1");
	$row = mysqli_fetch_row($result);
	$last_job_id=$row[0];
}

function restoreClearCat(){
	global $message; 
	$last_job_id=$_REQUEST['last_job_id'];
	if(md5($_REQUEST['password'])=='c3f1396a463205b149f7559b01fea607'){
		include('../config.php');
	
		$result = mysqli_query($conn,"SELECT DISTINCT category,store FROM backup_cat_qty1 WHERE job_id='$last_job_id'");
		$row = mysqli_fetch_row($result);
		$cat_name=$row[0];
		$st_name=$row[1];
		
		$query="SELECT itq_id,qty,drawer_no FROM backup_cat_qty1 WHERE job_id='$last_job_id'";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$itq_id=$row[0];
			$itq_qty=$row[1];
			$itq_drawer=$row[2];
			mysqli_query($conn,"UPDATE inventory_qty SET `qty`='$itq_qty',`drawer_no`='$itq_drawer' WHERE id='$itq_id'");
		} 
		$query="SELECT bc2.itn_id,bc2.itn_item,bc2.itn_w_price,bc2.itn_r_price,bc2.itn_c_price,bc2.itn_qty,st.id,bc2.shipment_no FROM backup_cat_qty2 bc2, stores st WHERE bc2.store=st.name AND bc2.job_id='$last_job_id'";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$itn_id=$row[0];
			$itn_item=$row[1];
			$itn_w_price=$row[2];
			$itn_r_price=$row[3];
			$itn_c_price=$row[4];
			$itn_qty=$row[5];
			$itn_store=$row[6];
			$itn_shipment_no=$row[7];
			print $itn_id.'<br>';
			if($itn_id!=''){
				$query2="INSERT INTO `inventory_new` (`item`,`w_price`,`r_price`,`c_price`,`qty`,`store`,`shipment_no`) VALUES ('$itn_item','$itn_w_price','$itn_r_price','$itn_c_price','$itn_qty','$itn_store','$itn_shipment_no')";
				$result2=mysqli_query($conn,$query2);
				print $query2.'<br>';
				}
		} 
		
		if($result){
			$message='Item Qty of '.$cat_name.' in '.$st_name.' was Restored!';
			return true;
		}else{
			$message='Job could not be Restored!';
			return false;
		}
	}else{
		$message='Invalid Password!';
		return false;
	}
}

function invSetOrder(){
	global $message; 
	$result2=true;
	include('../config.php');
	$query="SELECT id,drawer_no FROM inventory_qty";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$itq_id=$row[0];
		$itq_drawer=$row[1];
		if(strlen($itq_drawer)>3){ $itq_drawer2=substr($itq_drawer,0,strpos($itq_drawer,',')); }else $itq_drawer2=$itq_drawer;
		$query2="UPDATE inventory_qty SET `drawer_no_odr`='$itq_drawer2' WHERE id='$itq_id'";
		$result1=mysqli_query($conn,$query2);
		if(!$result1) $result2=false;
	} 
	
		if($result2){
			$message='Inventory Drawer Order was created Successfully';
			return true;
		}else{
			$message='Order Could be Arranged!';
			return false;
		}
}
//---------------------------Debug---------------------------------------------------------//
function getDebug(){
	global $debug_id,$debug_itq,$debug_store,$debug_item,$debug_action,$debug_actionresult,$debug_start_qty,$debug_action_qty,$debug_end_qty,$debug_itq_qty; 
	include('../config.php');
	$query="SELECT dg.id,dg.itq_id,st.name,itm.description,dg.`action`,dg.action_result,dg.start_qty,dg.action_qty,dg.end_qty,itq.qty FROM debug dg, inventory_items itm, inventory_qty itq, stores st WHERE dg.item=itm.id AND dg.itq_id=itq.id AND itq.location=st.id AND dg.ack=0 AND dg.end_qty!=(dg.start_qty + dg.action_qty)";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$debug_id[]=$row[0];
		$debug_itq[]=$row[1];
		$debug_store[]=$row[2];
		$debug_item[]=$row[3];
		$debug_action[]=$row[4];
		$debug_actionresult[]=$row[5];
		$debug_start_qty[]=$row[6];
		$debug_action_qty[]=$row[7];
		$debug_end_qty[]=$row[8];
		$debug_itq_qty[]='';
	} 
	$query="SELECT dg.id,dg.itq_id,st.name,itm.description,dg.`action`,dg.action_result,dg.start_qty,dg.action_qty,dg.end_qty,itq.qty,itq.item,itq.location FROM debug dg, inventory_items itm, inventory_qty itq, stores st WHERE dg.item=itm.id AND dg.itq_id=itq.id AND itq.location=st.id AND dg.id IN ( SELECT MAX(id) FROM debug GROUP BY itq_id )";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$query1="SELECT SUM(qty) as `total` FROM inventory_new WHERE item='$row[10]' AND store='$row[11]'";
		$result1 = mysqli_query($conn,$query1);
		$row1 = mysqli_fetch_assoc($result1);
		if($row[8]!=$row1['total']+$row[9]){
		$debug_id[]=$row[0];
		$debug_itq[]=$row[1];
		$debug_store[]=$row[2];
		$debug_item[]=$row[3];
		$debug_action[]=$row[4];
		$debug_actionresult[]=$row[5];
		$debug_start_qty[]=$row[6];
		$debug_action_qty[]=$row[7];
		$debug_end_qty[]=$row[8];
		$debug_itq_qty[]=$row1['total']+$row[9];
		}
	} 
}

function debugAck(){
	global $message; 
	$id=$_GET['id'];
	include('../config.php');
	$query="UPDATE debug SET `ack`='1' WHERE id='$id'";
	$result=mysqli_query($conn,$query);
	
	if($result){
		$message='Debug was Acknowledged';
		return true;
	}else{
		$message='Debug Could be Acknowledged!';
		return false;
	}
}

//---------------------------Debug---------------------------------------------------------//
/*
function getInvMismatch(){
	global $itm_id,$itu_item,$store_id,$store_name,$itu_qty,$itu_itq,$itq_qty_arr,$issue;
	include('../config.php');
	$query1="SELECT itq_id,count(id) FROM inventory_unic_item WHERE `status`=0 GROUP BY itq_id";
	$result1=mysqli_query($conn,$query1);
	while($row1=mysqli_fetch_array($result1)){
		$itq_qty=0;
		$itu_itq_tmp=$row1[0];
		$itu_qty_tmp=$row1[1];
		$itu_itq[]=$row1[0];
		$itu_qty[]=$row1[1];
		
		$result2 = mysqli_query($conn,"SELECT itm.id,itm.description,itq.location,st.name FROM inventory_items itm, inventory_qty itq, stores st WHERE itm.id=itq.item AND itq.location=st.id AND itq.id='$itu_itq_tmp'");
		$row2 = mysqli_fetch_assoc($result2);
		$itm_id_tmp=$row2['id'];
		$store_id_tmp=$row2['location'];
		$itm_id[]=$row2['id'];
		$itu_item[]=$row2['description'];
		$store_id[]=$row2['location'];
		$store_name[]=$row2['name'];
		
		$query2="SELECT qty FROM inventory_qty WHERE id='$itu_itq_tmp'";
		$result2 = mysqli_query($conn,$query2);
		$row2 = mysqli_fetch_assoc($result2);
		$itq_qty=$row2['qty'];
		
		$query2="SELECT SUM(qty) AS `qty` FROM inventory_new WHERE item='$itm_id_tmp' AND store='$store_id_tmp'";
		$result2 = mysqli_query($conn,$query2);
		$row2 = mysqli_fetch_assoc($result2);
		$itq_qty+=$row2['qty'];
		
		$itq_qty_arr[]=$itq_qty;

		if($itu_qty_tmp!=$itq_qty) $issue[]='Error'; else $issue[]='';
	//	print '<tr><td><a title="'.$itm_id.'">'.$itu_item.'</a></td><td><a title="'.$store_id.'">'.$store_name.'</a></td><td>'.$itu_qty.'</td><td><a title="ITQ ID = '.$itu_itq.'">'.$itq_qty.'</a></td><td>'.$issue.'</td></tr>';
	}
}		
*/
function getInvMismatch(){
	global $litq_itm_id,$litq_itm_desc,$litq_itq_id,$litq_itq_qty,$litq_st_id,$litq_st_name,$litq_itu_qty,$issue;
	$litu_itq_id=$litu_qty=$litq_itm_id=$litq_itm_desc=$litu_qty=$litq_itu_qty=array();
	include('../config.php');
	
	$query1="SELECT itq_id,count(id) FROM inventory_unic_item WHERE `status`=0 GROUP BY itq_id";
	$result1=mysqli_query($conn,$query1);
	while($row1=mysqli_fetch_array($result1)){
		$litu_itq_id[]=$row1[0];
		$litu_qty[]=$row1[1];
	}
	

	$query1="SELECT itm.id,itm.description,itq.id,itq.qty,itq.location,st.name FROM inventory_items itm, inventory_qty itq, stores st WHERE itm.id=itq.item AND itq.location=st.id AND itm.unic=1 AND itm.`status`=1";
	$result1=mysqli_query($conn,$query1);
	while($row1=mysqli_fetch_array($result1)){
		$itm_id_tmp=$row1[0];
		$litq_itm_id[]=$row1[0];
		$litq_itm_desc[]=$row1[1];
		$itq_id=$row1[2];
		$litq_itq_id[]=$row1[2];
		$itq_qty=$row1[3];
		$st_id_tmp=$row1[4];
		$litq_st_id[]=$row1[4];
		$litq_st_name[]=$row1[5];
		
		$itn_qty=0;
		$query2="SELECT id,SUM(qty) as `qty` FROM inventory_new WHERE item='$itm_id_tmp' AND store='$st_id_tmp'";
		$result2 = mysqli_query($conn,$query2);
		$row2 = mysqli_fetch_assoc($result2);
		$itn_qty=$row2['qty'];

		$total_itq_qty=$itq_qty+$itn_qty;
		$litq_itq_qty[]=$total_itq_qty;
		
		$arrsearch_itu_id=array_search($itq_id,$litu_itq_id);
		if($arrsearch_itu_id===false){
			$itu_qty=0;
			$litq_itu_qty[]=$itu_qty;
		}else{
			$itu_qty=$litu_qty[$arrsearch_itu_id];
			$litq_itu_qty[]=$itu_qty;
		}

		if($itu_qty==$total_itq_qty){
			$issue[]='';
		}else{
			$issue[]='Error';
		}
	}
}

function cashBackInvCheck($sn){
	include('../config.php');
	$i=1;
	$val1=$val2=$cust1=$error=0;
	$query="SELECT bm.`invoice_+total`,bm.`invoice_-total`,bm.`cust` FROM bill bi, bill_main bm WHERE bm.invoice_no=bi.invoice_no AND bm.`status`!=0 AND bi.`comment` LIKE '%$sn%' GROUP BY bm.invoice_no";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$bm_total1=$row[0];
		$bm_total2=$row[1];
		$bm_cust=$row[2];
		if(($i%2)!=0){
			$val1=$bm_total1;
			$val2=$bm_total2;
			$cust1=$bm_cust;
		}else{
			if((($bm_total1+$val2)!=0)||(($bm_total2+$val1)!=0)||($bm_cust!=$cust1)) $error++;
		}
		$i++;
	}
//	if($error>0)print $sn.'<br />';
	if($error==0) return false; else return true;  
}

function validateError(){
	$itq_id=$_GET['itq_id'];
	$history_date='2017-10-12';
	$error=0;
	$error_code='';
	include('../config.php');
	$result = mysqli_query($conn,"SELECT item,location FROM inventory_qty WHERE id='$itq_id'");
	$row = mysqli_fetch_assoc($result);
	$item_id=$row['item'];
	$store_id=$row['location'];
	
	//---------------------Test Case 1--------------------------------------------//
	$query="SELECT sn FROM inventory_unic_item WHERE itq_id='$itq_id' AND `status`=0";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$itu_sn=$row[0];
		
		$query1="SELECT count(bi.id) as `count` FROM bill bi, bill_main bm WHERE bm.invoice_no=bi.invoice_no AND bm.`status`!=0 AND bi.`comment` LIKE '%$itu_sn%'";
		$result1 = mysqli_query($conn,$query1);
		$row1 = mysqli_fetch_assoc($result1);
		$bill_found=$row1['count'];
		if($bill_found>0){
			if(cashBackInvCheck($itu_sn)){
				$error++;
				print $itu_sn.'<br />';
			}
		} 
	}
	if($error>0) $error_code='1';
	//---------------------Test Case 2--------------------------------------------//
		$query="SELECT count(bi.id) as `count` FROM bill bi, bill_main bm WHERE bm.invoice_no=bi.invoice_no AND bm.`status`!=0 AND bi.`comment`='' AND bi.item='$item_id' AND date(bm.billed_timestamp)>'$history_date'";
		$result = mysqli_query($conn,$query);
		$row = mysqli_fetch_assoc($result);
		$empty_found=$row['count'];
		if($empty_found>0){ 
			$error++;
			$error_code=$error_code.',2';
		}
	if($error>0) print 'Case : '.$error_code; else print 'Good';
}

function getOneMismatch(){
	global $itq_id,$itu_item,$store_id,$store_name,$itu_qty,$itu_itq,$itq_qty,$issue;
	$itq_id=$_GET['itq_id'];
	$itq_qty=0;
	include('../config.php');
	
	$result = mysqli_query($conn,"SELECT itm.id,itm.description,itq.location,st.name,itq.qty FROM inventory_items itm, inventory_qty itq, stores st WHERE itm.id=itq.item AND itq.location=st.id AND itq.id='$itq_id'");
	$row = mysqli_fetch_assoc($result);
	$itm_id_tmp=$row['id'];
	$itu_item=$row['description'];
	$store_id=$row['location'];
	$store_name=$row['name'];
	$itq_qty=$row['qty'];
	
	$query="SELECT SUM(qty) AS `qty` FROM inventory_new WHERE item='$itm_id_tmp' AND store='$store_id'";
	$result = mysqli_query($conn,$query);
	$row = mysqli_fetch_assoc($result);
	$itq_qty+=$row['qty'];
	
	$query="SELECT count(id) as `count` FROM inventory_unic_item WHERE itq_id='$itq_id' AND `status`=0";
	$result = mysqli_query($conn,$query);
	$row = mysqli_fetch_assoc($result);
	$itu_qty=$row['count'];
			
	if($itu_qty!=$itq_qty) $issue='Error'; else $issue='';
}

function updateItqQty($increment){
	global $message;
	$itq_id=$_GET['itq_id'];
	include('../config.php');
	$query="UPDATE inventory_qty SET `qty`=qty+$increment WHERE id='$itq_id'";
	$result = mysqli_query($conn,$query);
	
	if($result){
		$message='ITQ QTY was Updated Succesfully';
		return true;
	}else{
		$message='ITQ QTY Could not be Updated!';
		return false;
	}
}

//---------------------------------------subscription----------------------------------------------------------------//
function getSubscription(){
	global $subscription_end;
	include('../config.php');
	$result = mysqli_query($conn,"SELECT value FROM settings WHERE setting='subscription_start'");
	$row = mysqli_fetch_assoc($result);
	$subscription_start=$row['value'];
	$result = mysqli_query($conn,"SELECT value FROM settings WHERE setting='subscription_duration'");
	$row = mysqli_fetch_assoc($result);
	$subscription_duration=$row['value'];
	$timestamp_start=strtotime($subscription_start);
	$timestamp_nest=$timestamp_start+$subscription_duration*24*60*60;
	$timestamp_gap=$timestamp_nest-time();
	$subscription_end=round($timestamp_gap / 60 / 60 / 24);
}


function incrementSub($increment){
	global $message;
	$itq_id=$_GET['itq_id'];
	include('../config.php');
	$result = mysqli_query($conn,"SELECT value FROM settings WHERE setting='subscription_duration'");
	$row = mysqli_fetch_assoc($result);
	$sub_duration=$row['value'];
	$new_sub_duration=$sub_duration+$increment;
	$query="UPDATE settings SET `value`='$new_sub_duration' WHERE setting='subscription_duration'";
	$result1 = mysqli_query($conn,$query);
	if($result1){
		$message='Subscription was Updated Succesfully';
		return true;
	}else{
		$message='Subscription Could not be Updated!';
		return false;
	}
}



?>