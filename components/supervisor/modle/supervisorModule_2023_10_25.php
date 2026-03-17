<?php
function getSubSystems(){
	global $sub_system_list,$sub_system_names;
	include('config.php');
	$query="SELECT id,name FROM sub_system WHERE `status`=1";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$sub_system_list[]=$row[0];
		$sub_system_names[]=$row[1];
	}
}

function getCustSup($sub_system){
	global $cu_id,$cu_id0,$cu_name,$cu_name0,$cu_nic,$cu_mobile,$cu_status,$cu_sub_sys;
	if($sub_system=='all') $sub_sys_qry=''; else  $sub_sys_qry="AND cu.`sub_system`='$sub_system'";
	include('config.php');
		$query="SELECT cu.id,cu.name FROM cust cu WHERE cu.`status`=1 $sub_sys_qry";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$cu_id[]=$row[0];
			$cu_name[]=$row[1];
		}
}

function getUnlockedBills(){
	global $invoice_no,$billed_by,$billed_cust,$date,$time;
	$store=$_COOKIE['store'];
	$invoice_no=array();
	include('config.php');
	$query="SELECT DISTINCT bm.invoice_no,up.username,cu.name,DATE(bm.billed_timestamp),TIME(bm.billed_timestamp) FROM bill bi ,bill_main bm, userprofile up, cust cu WHERE bi.invoice_no=bm.invoice_no AND bm.billed_by=up.id AND bm.`cust`=cu.id AND bm.store='$store' AND bm.`lock`=0 AND bm.`status` NOT IN (0,7)";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$invoice_no[]=$row[0];
		$billed_by[]=$row[1];
		$billed_cust[]=$row[2];
		$date[]=$row[3];
		$time[]=$row[4];
	}
}

// added by nirmal 11_07_2023
function getTemporaryBills(){
	global $invoice_no,$billed_by,$billed_cust,$date,$time;
	$store=$_COOKIE['store'];
	$invoice_no=array();
	include('config.php');

	$query = "SELECT DISTINCT bm.`bm_no`,up.`username`,cu.`name`,DATE(bi.`date`),TIME(bi.`date`),st.`name`
	FROM bill_tmp bi ,bill_main_tmp bm, userprofile up, cust cu, stores st
	WHERE bi.`bm_no`=bm.`bm_no` AND bm.`billed_by`=up.`id` AND bm.`cust`=cu.`id` AND bm.`store`=st.`id` AND bm.`status` NOT IN (0,7) AND bm.`store`='$store' ORDER BY st.`name`, bi.`date` DESC";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$invoice_no[]=$row[0];
		$billed_by[]=$row[1];
		$billed_cust[]=$row[2];
		$date[]=$row[3];
		$time[]=$row[4];
	}
}

function validateGroupPermission($user_id,$group_id){
	include('config.php');
	$query="SELECT COUNT(id) FROM user_to_group WHERE `group`='$group_id' AND `user`='$user_id'";
	$row=mysqli_fetch_row(mysqli_query($conn2,$query));
	if($row[0]>0)
		return true;
	else
		return false;
}

//---------------------------------------SALES REPORT--------------------------------------------------------//
function groupStores(){
	include('config.php');

    $result = mysqli_query($conn2,"SELECT value FROM settings WHERE setting='systemid'");
	$row = mysqli_fetch_assoc($result);
	$systemid=$row['value'];

	if(isset($_COOKIE['store'])&&($_GET['components']!='fin')&&((($_GET['components']!='manager') || ($systemid != 14)))){
		$salesman_store=$_COOKIE['store'];
		$result = mysqli_query($conn2,"SELECT `group` FROM store_group WHERE store='$salesman_store'");
		$row = mysqli_fetch_assoc($result);
		$group=$row['group'];

		$query="SELECT store FROM store_group WHERE `group` ='$group'";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){		$stores[]=$row[0];	}
		$store_list=implode(',',$stores);
	}else{
		$query="SELECT id FROM stores WHERE `status` ='1'";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){		$stores[]=$row[0];	}
		$store_list=implode(',',$stores);
	}

	return $store_list;
}

// update by nirmal 13_10_2023
function getFilter($sub_system){
	global $store_id,$store_name,$up_id,$up_name,$gp_id,$gp_name,$town_id,$town_name,$item_id,$item_desc;
	$user_id=$_COOKIE['user_id'];

	$userfiler='';
	if($_REQUEST['components']=='report' || $_REQUEST['components']=='topmanager'){
		$storefilter2=$storefilter3='';
	}else{
		$store_list=groupStores();
		$storefilter2="AND id IN ($store_list)";
		$storefilter3="AND `store` IN ($store_list)";
	}
	if($sub_system=='all'){
		$sub_system_qry1=$sub_system_qry2=$sub_system_qry3='';
	}else{
		$sub_system_qry1="AND `sub_system`='$sub_system'";
		$sub_system_qry2="WHERE `sub_system`='$sub_system'";
		$sub_system_qry3="AND cg.`sub_system`='$sub_system'";
	}
	if($_REQUEST['components']=='to'){
		$userfiler="AND `id`='$user_id'";
	}
	include('config.php');
	$query="SELECT id,name FROM stores WHERE `status`='1' $storefilter2 $sub_system_qry1";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$store_id[]=$row[0];
		$store_name[]=$row[1];
	}
	$query="SELECT id,username FROM userprofile WHERE `status`=0 $userfiler $storefilter3 $sub_system_qry1";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$up_id[]=$row[0];
		$up_name[]=$row[1];
	}

	if($_GET['components']=='marketing')	$query="SELECT cg.id,cg.`name` FROM cust_group cg, user_to_group ug WHERE cg.id=ug.`group` AND ug.`user`='$user_id' $sub_system_qry3 ORDER BY cg.`name`";
	else $query="SELECT id,name FROM cust_group $sub_system_qry2 ORDER BY name";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$gp_id[]=$row[0];
		$gp_name[]=$row[1];
	}
	$query="SELECT id,description FROM inventory_items WHERE `status`='1'";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$item_id[]=$row[0];
		$item_desc[]=$row[1];
	}
	$query="SELECT id,`name` FROM town";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$town_id[]=$row[0];
		$town_name[]=$row[1];
	}
}

// updated by nirmal 01_09_2023
function dailySale($store){
	global $lock_req,$type_req,$graph_user,$graph_total,$date1,$date2,$invoice_no,$invoice_Total,$billed_district,
	$billed_by,$billed_cust,$billed_time,$billed_store,$payment_cash,$payment_chque,$payment_card, $payment_bank,$payment_id,
	$payment_amount,$payment_type,$payment_salesman,$payment_cust,$payment_time,$payment_store,$bi_discount,
	$rtn_no,$rtn_time,$rtn_pay,$rtn_salesman,$rtn_store,$rtn_cust,$wa_no,$wa_time,$wa_pay,$wa_salesman,$wa_entity,$wa_store;
	$invoice_no=$payment_id=$rtn_no=$wa_no=$graph_user=$graph_total=array();
	$group_qry=$lock_qry=$type_qry=$type_req='';
	$store_list=groupStores();
	$user_id=$_COOKIE['user_id'];

	if($store=='all') $storesearch="AND st.id IN (".$store_list.")"; else $storesearch="AND st.id='".$store."'";
	if($_REQUEST['salesman']=='all') $salesmansearch=''; else $salesmansearch="AND up.`id`='".$_REQUEST['salesman']."'";
	if($_REQUEST['processby']=='all') $processbysearch=''; else $processbysearch="AND bm.`packed_by`='".$_REQUEST['processby']."'";
	if(isset($_REQUEST['type'])){
		$type_req=$_REQUEST['type'];
		if($type_req==1) $type_qry="AND bm.`type` IN (1,4)";
		if($type_req==2) $type_qry="AND bm.`type` IN (2,5)";
		if($type_req==4) $type_qry="AND bm.`type`='3'";
	}
	if(isset($_REQUEST['date1'])){
		$date1=$_REQUEST['date1'];
	}else{
		$date1=dateNow();
	}
	$date_qry1="AND date(bm.billed_timestamp)='$date1'";
	$date_qry2="AND date(py.payment_date)='$date1'";
	$date_qry3="AND date(rm.return_date)='$date1'";
	$date_qry4="AND date(wa.cust_pay_date)='$date1'";
	$date_qry5="AND date(wa.suplier_pay_date)='$date1'";

	if((isset($_REQUEST['date1']))&&(isset($_REQUEST['date2']))){
		$date1=$_REQUEST['date1'];
		$date2=$_REQUEST['date2'];
		if($date1!='' && $date2!=''){
		$date_qry1="AND date(bm.billed_timestamp) BETWEEN  '$date1' AND '$date2'";
		$date_qry2="AND date(py.payment_date) BETWEEN  '$date1' AND '$date2'";
		$date_qry3="AND date(rm.return_date) BETWEEN  '$date1' AND '$date2'";
		$date_qry4="AND date(wa.cust_pay_date) BETWEEN  '$date1' AND '$date2'";
		$date_qry5="AND date(wa.suplier_pay_date) BETWEEN  '$date1' AND '$date2'";
		}
	}

	if(isset($_REQUEST['lock'])){
		$lock_req=$_REQUEST['lock'];
		if($lock_req=='all'){
			$lock_qry='';
		}else{
			if($lock_req==0) $lock_qry="AND bm.`lock` IN (0,2)"; else $lock_qry="AND bm.`lock`=$lock_req";
		}
	}else{
		$lock_req=1;
		$lock_qry="AND bm.`lock`=$lock_req";
	}

	include('config.php');
	if(isset($_REQUEST['group'])){
		$group=$_REQUEST['group'];
		if(($group!='')&&($group!='all')){
			$group_qry="AND cu.associated_group='$group'";
		}else{
			if($_GET['components']=='marketing'){
				$gp_id='';
				$query="SELECT cg.id FROM cust_group cg, user_to_group ug WHERE cg.id=ug.`group` AND ug.`user`='$user_id'";
				$result=mysqli_query($conn2,$query);
				while($row=mysqli_fetch_array($result)){
					$gp_id.=$row[0].',';
				}
				$gp_id=rtrim($gp_id,',');
				$group_qry="AND cu.associated_group IN ($gp_id)";
			}
		}
	}

	if($type_req!=3&&$type_req!=5){
		if($_GET['components']=='marketing')
			$query="SELECT bm.`invoice_no`,round((bm.`invoice_+total` + bm.`invoice_-total`),2),di.name,up.username,cu.name,date(bm.billed_timestamp),time(bm.billed_timestamp),st.name FROM bill_main bm, userprofile up, district di, cust cu, stores st WHERE bm.store=st.id AND bm.`cust`=cu.id AND bm.billed_by=up.id AND bm.billed_district=di.id AND bm.`status` NOT IN (0,7) $lock_qry $type_qry $date_qry1 $group_qry $storesearch $salesmansearch $processbysearch GROUP BY bm.invoice_no ORDER BY st.name, bm.billed_timestamp";
		else
			$query="SELECT bm.`invoice_no`,round(SUM(bi.qty*bi.unit_price),2),di.name,up.username,cu.name,date(bm.billed_timestamp),time(bm.billed_timestamp),st.name,SUM(bi.discount) FROM bill bi, bill_main bm, userprofile up, district di, cust cu, stores st WHERE bm.store=st.id AND bm.`cust`=cu.id AND bi.invoice_no=bm.invoice_no AND bm.billed_by=up.id AND bm.billed_district=di.id AND bm.`status` NOT IN (0,7) $lock_qry $type_qry $date_qry1 $group_qry $storesearch $salesmansearch $processbysearch GROUP BY bm.invoice_no ORDER BY st.name, bm.billed_timestamp";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$invoice_no_id=$row[0];
			$invoice_no[]=$row[0];
			$invoice_Total[]=$row[1];
			$billed_district[]=$row[2];
			$billed_by[]=$row[3];
			$billed_cust[]=$row[4];
			$billed_time[]=$row[5].' - '.substr($row[6],0,5);
			$billed_store[]=$row[7];
			if($_GET['components']!='marketing') $bi_discount[]=$row[8];
			$query1="SELECT payment_type,amount FROM payment WHERE bill_pay=1 AND `status`='0' AND `invoice_no`='$invoice_no_id'";
			$result1=mysqli_query($conn2,$query1);
			while($row1=mysqli_fetch_array($result1)){
				if($row1[0]==1)	$payment_cash[$invoice_no_id]=$row1[1];
				if($row1[0]==2)	$payment_chque[$invoice_no_id]=$row1[1];
				if($row1[0]==3)	$payment_bank[$invoice_no_id]=$row1[1];
				if($row1[0]==4)	$payment_card[$invoice_no_id]=$row1[1];
			}
			if(!isset($payment_cash[$invoice_no_id])) $payment_cash[$invoice_no_id]=0;
			if(!isset($payment_chque[$invoice_no_id])) $payment_chque[$invoice_no_id]=0;
			if(!isset($payment_card[$invoice_no_id])) $payment_card[$invoice_no_id]=0;
			if(!isset($payment_bank[$invoice_no_id])) $payment_bank[$invoice_no_id]=0;

			if(!in_array($row[3], $graph_user) ){
				$graph_user[]=$row[3];
			}
			$key=array_search($row[3],$graph_user);
			if(isset($graph_total[$key])) $total=$graph_total[$key]; else $total=0;
			$graph_total[$key]=$total+$row[1];
		}
	}

	$query2="SELECT py.id,round(py.amount),py.payment_type,up.username,cu.name,date(py.payment_date),time(py.payment_date), st.name FROM payment py, userprofile up, cust cu, stores st WHERE py.salesman=up.id AND py.cust=cu.id AND py.store=st.id AND py.bill_pay=2 AND py.`status`=0 $date_qry2 $storesearch $group_qry $salesmansearch ORDER BY st.name, py.payment_date";
	$result2=mysqli_query($conn2,$query2);
	while($row2=mysqli_fetch_array($result2)){
		if($row2[0]>0){
		$payment_id[]=$row2[0];
		$payment_amount[]=$row2[1];
		if($row2[2]==1) $payment_type[]='Cash';
		if($row2[2]==2) $payment_type[]='Chque';
		if($row2[2]==3) $payment_type[]='Bank';
		if($row2[2]==4) $payment_type[]='Card';
		$payment_salesman[]=$row2[3];
		$payment_cust[]=$row2[4];
		$payment_time[]=$row2[5].' - '.substr($row2[6],0,5);
		$payment_store[]=$row2[7];
		}else $payment_id=$payment_amount=$payment_type=$payment_salesman=$payment_cust=$payment_time=$payment_store=array();
	}

	if($type_req==3 || $type_req==''){
		$query3="SELECT rm.invoice_no,time(rm.return_date),SUM(rt.extra_pay),up.username,st.name,cu.name FROM return_main rm, `return` rt, stores st, userprofile up, cust cu WHERE rm.invoice_no=rt.invoice_no AND rm.store=st.id AND rm.return_by=up.id AND rm.`cust`=cu.id AND rt.extra_pay!=0 AND rm.`status`=2 $date_qry3 $storesearch $group_qry $salesmansearch GROUP BY rm.invoice_no ORDER BY st.name";
		$result3=mysqli_query($conn2,$query3);
		while($row3=mysqli_fetch_array($result3)){
			$rtn_no[]=$row3[0];
			$rtn_time[]=$row3[1];
			$rtn_pay[]=$row3[2];
			$rtn_salesman[]=$row3[3];
			$rtn_store[]=$row3[4];
			$rtn_cust[]=$row3[5];
			$key=array_search($row3[3],$graph_user);
			if($key>-1){
				$graph_total[$key]=$graph_total[$key]+$row3[2];
			}else{
				$graph_user[]=$row3[3];
				$graph_total[]=$row3[2];
			}
		}
	}
	if($type_req==5 || $type_req==''){
		$query3="SELECT wa.id,wa.cust_pay_date,wa.cust_pay_amount,up.username,st.name FROM warranty wa, userprofile up, stores st WHERE wa.store=st.id AND wa.cust_pay_by=up.id AND wa.`status`!=0 AND wa.cust_pay_amount!=0 $date_qry4 $storesearch $salesmansearch";
		$result3=mysqli_query($conn2,$query3);
		while($row3=mysqli_fetch_array($result3)){
			$wa_no[]=$row3[0];
			$wa_time[]=$row3[1];
			$wa_pay[]=$row3[2];
			$wa_salesman[]=$row3[3];
			$wa_entity[]='<span style="color:#0000CC">Paid by</span> Customer';
			$wa_store[]=$row3[4];
		}
		$query3="SELECT wa.id,wa.suplier_pay_date,wa.suplier_pay,up.username,st.name FROM warranty wa, userprofile up, stores st WHERE wa.store=st.id AND wa.suplier_pay_by=up.id AND wa.`status`!=0 AND wa.suplier_pay!=0 $date_qry5 $storesearch $salesmansearch";
		$result3=mysqli_query($conn2,$query3);
		while($row3=mysqli_fetch_array($result3)){
			$wa_no[]=$row3[0];
			$wa_time[]=$row3[1];
			$wa_pay[]=-$row3[2];
			$wa_salesman[]=$row3[3];
			$wa_entity[]='<span style="color:#00CC00">Paid to</span> Supplier';
			$wa_store[]=$row3[4];
		}
	}
}

/*
function getCreditData($sub_system){
	global $message,$decimal,$as_of,$cust_id,$cust_name,$cust_cr_balance0,$cust_cr_balance7,$cust_cr_balance14,$cust_cr_balance30,$cust_cr_balance45,$cust_cr_balance60,$cust_cr_balance90,$credit_total0,$credit_total7,$credit_total14,$credit_total30,$credit_total45,$credit_total60,$credit_total90;

	$bm_cust_id=$bm_cust_name=$bm_total=$py_cust_id=$py_total=$bm_cust90=$bm_total90=$bm_cust60=$bm_total60=$bm_cust45=$bm_total45=$bm_cust30=$bm_total30=$bm_cust14=$bm_total14=$bm_cust7=$bm_total7=array();
	$cashback_bm_cust=$cashback_bm_total=$py_upto_cust90=$py_upto_total90=$py_upto_cust60=$py_upto_total60=$py_upto_cust45=$py_upto_total45=$py_upto_cust30=$py_upto_total30=$py_upto_cust14=$py_upto_total14=$py_upto_cust7=$py_upto_total7=array();
	$cust_id=$py_after_cust90=$py_after_total90=$py_after_cust60=$py_after_total60=$py_after_cust45=$py_after_total45=$py_after_cust30=$py_after_total30=$py_after_cust14=$py_after_total14=$py_after_cust7=$py_after_total7=array();
	$overpy_after_cust90=$overpy_after_total90=$overpy_after_cust60=$overpy_after_total60=$overpy_after_cust45=$overpy_after_total45=$overpy_after_cust30=$overpy_after_total30=$overpy_after_cust14=$overpy_after_total14=$overpy_after_cust7=$overpy_after_total7=array();

	$datenow=dateNow();
	$user_id=$_COOKIE['user_id'];
	$group_list='';
	$out=true;

	if($sub_system=='all') $sub_system_qry=''; else $sub_system_qry="AND cu.`sub_system`='$sub_system'";
	if($_REQUEST['components']=='report' || $_REQUEST['components']=='topmanager'){
		$storefilter2='';
	}else{
		$store_list=groupStores();
		$storefilter2="AND cu.associated_store IN (".$store_list.")";
	}
	if(isset($_GET['as_of'])){
		$as_of=$_GET['as_of'];
		if($as_of=='') $as_of=$datenow;
	}else{
		$as_of=$datenow;
	}
	if(isset($_GET['st'])){
		if($_GET['st']!=''){
			$st_id=$_GET['st'];
			$storefilter='AND cu.associated_store='.$st_id;
		}else $storefilter=$storefilter2;
	}else $storefilter=$storefilter2;
	if(isset($_GET['gp'])){
		if($_GET['gp']!=''){
			$gp_id=$_GET['gp'];
			$groupfilter="AND cu.associated_group='$gp_id'";
		}else $groupfilter='';
	}else $groupfilter='';
	if(isset($_GET['tw'])){
		if($_GET['tw']!=''){
			$town_id=$_GET['tw'];
			$townfilter="AND cu.associated_town='$town_id'";
		}else $townfilter='';
	}else $townfilter='';
	if(isset($_GET['up'])){
		if($_GET['up']!=''){
			$sm_id=$_GET['up'];
			$salemanfilter="AND cu.associated_salesman='$sm_id'";
		}else $salemanfilter='';
	}else $salemanfilter='';

		$date1=date_create($as_of);
		$date2=date_create($datenow);
		$diff=date_diff($date1,$date2);
		$diff1=$diff->format("%R");
		$diff2=$diff->format("%a");
		if($diff1=="-") $message='Invalid "As of" Date';
		$diff3=$diff2*24*60*60;

		$backdate90=date("Y-m-d",(time()-(90*24*60*60)-$diff3));
		$backdate60=date("Y-m-d",(time()-(60*24*60*60)-$diff3));
		$backdate45=date("Y-m-d",(time()-(45*24*60*60)-$diff3));
		$backdate30=date("Y-m-d",(time()-(30*24*60*60)-$diff3));
		$backdate14=date("Y-m-d",(time()-(14*24*60*60)-$diff3));
		$backdate7=date("Y-m-d",(time()-(7*24*60*60)-$diff3));

	include('config.php');

	$result = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='decimal'");
	$row = mysqli_fetch_assoc($result);
	$decimal=$row['value'];

	if($_GET['components']=='marketing'){
		if($groupfilter==''){
			$query="SELECT ug.`group` FROM user_to_group ug WHERE ug.`user`='$user_id'";
			$result=mysqli_query($conn2,$query);
			while($row=mysqli_fetch_array($result)){
				$group_list.=$row[0].',';
			}
			if($group_list!=''){
				$group_list=rtrim($group_list,',');
				$groupfilter="AND cu.associated_group IN ($group_list)";
			}
		}else{
			if(!validateGroupPermission($user_id,$gp_id)) $out=false;
		}
	}

	if($out){
		$query="SELECT cu.id,cu.name,SUM(bm.`invoice_+total`)+SUM(bm.`invoice_-total`) FROM bill_main bm, cust cu WHERE bm.`cust`=cu.id AND bm.exclude=0 AND bm.`status` NOT IN (0,7) AND bm.`lock`=1 AND cu.`status`!=0 AND date(bm.billed_timestamp)<='$as_of' $sub_system_qry $storefilter $townfilter $groupfilter $salemanfilter GROUP BY bm.`cust` ORDER BY cu.name";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$cust_id[]=$row[0];
			$cust_name[]=$row[1];
			$bm_total[]=$row[2];
		}
		$query="SELECT py.`cust`,SUM(py.amount) FROM payment py WHERE py.status=0 AND py.chque_return=0 AND date(py.payment_date)<='$as_of' GROUP BY py.`cust`";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$py_cust_id[]=$row[0];
			$py_total[]=$row[1];
		}
		$query="SELECT bm.`cust`,SUM(bm.`invoice_+total`) FROM bill_main bm WHERE bm.`status` NOT IN (0,7) AND bm.exclude=0 AND bm.`lock`=1 AND date(bm.billed_timestamp)<='$backdate90' GROUP BY bm.`cust`";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$bm_cust90[]=$row[0];
			$bm_total90[]=$row[1];
		}
		$query="SELECT bm.`cust`,SUM(bm.`invoice_+total`) FROM bill_main bm WHERE bm.`status` NOT IN (0,7) AND bm.exclude=0 AND bm.`lock`=1 AND date(bm.billed_timestamp)<='$backdate60' GROUP BY bm.`cust`";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$bm_cust60[]=$row[0];
			$bm_total60[]=$row[1];
		}
		$query="SELECT bm.`cust`,SUM(bm.`invoice_+total`) FROM bill_main bm WHERE bm.`status` NOT IN (0,7) AND bm.exclude=0 AND bm.`lock`=1 AND date(bm.billed_timestamp)<='$backdate45' GROUP BY bm.`cust`";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$bm_cust45[]=$row[0];
			$bm_total45[]=$row[1];
		}
		$query="SELECT bm.`cust`,SUM(bm.`invoice_+total`) FROM bill_main bm WHERE bm.`status` NOT IN (0,7) AND bm.exclude=0 AND bm.`lock`=1 AND date(bm.billed_timestamp)<='$backdate30' GROUP BY bm.`cust`";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$bm_cust30[]=$row[0];
			$bm_total30[]=$row[1];
		}
		$query="SELECT bm.`cust`,SUM(bm.`invoice_+total`) FROM bill_main bm WHERE bm.`status` NOT IN (0,7) AND bm.exclude=0 AND bm.`lock`=1 AND date(bm.billed_timestamp)<='$backdate14' GROUP BY bm.`cust`";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$bm_cust14[]=$row[0];
			$bm_total14[]=$row[1];
		}
		$query="SELECT bm.`cust`,SUM(bm.`invoice_+total`) FROM bill_main bm WHERE bm.`status` NOT IN (0,7) AND bm.exclude=0 AND bm.`lock`=1 AND date(bm.billed_timestamp)<='$backdate7' GROUP BY bm.`cust`";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$bm_cust7[]=$row[0];
			$bm_total7[]=$row[1];
		}
		$query="SELECT bm.`cust`,SUM(bm.`invoice_-total`) FROM bill_main bm WHERE bm.`status` NOT IN (0,7) AND bm.exclude=0 AND bm.`lock`=1 AND date(bm.billed_timestamp)<='$as_of' GROUP BY bm.`cust`";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$cashback_bm_cust[]=$row[0];
			$cashback_bm_total[]=$row[1];
		}
		$query="SELECT py.`cust`,SUM(py.amount) FROM payment py WHERE py.status=0 AND py.chque_return=0 AND date(py.payment_date) <= '$backdate90' GROUP BY py.`cust`";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$py_upto_cust90[]=$row[0];
			$py_upto_total90[]=$row[1];
		}
		$query="SELECT py.`cust`,SUM(py.amount) FROM payment py WHERE py.status=0 AND py.chque_return=0 AND date(py.payment_date) <= '$backdate60' GROUP BY py.`cust`";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$py_upto_cust60[]=$row[0];
			$py_upto_total60[]=$row[1];
		}
		$query="SELECT py.`cust`,SUM(py.amount) FROM payment py WHERE py.status=0 AND py.chque_return=0 AND date(py.payment_date) <= '$backdate45' GROUP BY py.`cust`";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$py_upto_cust45[]=$row[0];
			$py_upto_total45[]=$row[1];
		}
		$query="SELECT py.`cust`,SUM(py.amount) FROM payment py WHERE py.status=0 AND py.chque_return=0 AND date(py.payment_date) <= '$backdate30' GROUP BY py.`cust`";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$py_upto_cust30[]=$row[0];
			$py_upto_total30[]=$row[1];
		}
		$query="SELECT py.`cust`,SUM(py.amount) FROM payment py WHERE py.status=0 AND py.chque_return=0 AND date(py.payment_date) <= '$backdate14' GROUP BY py.`cust`";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$py_upto_cust14[]=$row[0];
			$py_upto_total14[]=$row[1];
		}
		$query="SELECT py.`cust`,SUM(py.amount) FROM payment py WHERE py.status=0 AND py.chque_return=0 AND date(py.payment_date) <= '$backdate7' GROUP BY py.`cust`";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$py_upto_cust7[]=$row[0];
			$py_upto_total7[]=$row[1];
		}
		$query="SELECT py.`cust`,SUM(py.amount) FROM payment py WHERE py.status=0 AND py.chque_return=0 AND py.bill_pay=2 AND date(py.payment_date) > '$backdate90' AND date(py.payment_date)<='$as_of' GROUP BY py.`cust`";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$py_after_cust90[]=$row[0];
			$py_after_total90[]=$row[1];
		}
		$query="SELECT py.`cust`,SUM(py.amount) FROM payment py WHERE py.status=0 AND py.chque_return=0 AND py.bill_pay=2 AND date(py.payment_date) > '$backdate60' AND date(py.payment_date)<='$as_of' GROUP BY py.`cust`";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$py_after_cust60[]=$row[0];
			$py_after_total60[]=$row[1];
		}
		$query="SELECT py.`cust`,SUM(py.amount) FROM payment py WHERE py.status=0 AND py.chque_return=0 AND py.bill_pay=2 AND date(py.payment_date) > '$backdate45' AND date(py.payment_date)<='$as_of' GROUP BY py.`cust`";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$py_after_cust45[]=$row[0];
			$py_after_total45[]=$row[1];
		}
		$query="SELECT py.`cust`,SUM(py.amount) FROM payment py WHERE py.status=0 AND py.chque_return=0 AND py.bill_pay=2 AND date(py.payment_date) > '$backdate30' AND date(py.payment_date)<='$as_of' GROUP BY py.`cust`";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$py_after_cust30[]=$row[0];
			$py_after_total30[]=$row[1];
		}
		$query="SELECT py.`cust`,SUM(py.amount) FROM payment py WHERE py.status=0 AND py.chque_return=0 AND py.bill_pay=2 AND date(py.payment_date) > '$backdate14' AND date(py.payment_date)<='$as_of' GROUP BY py.`cust`";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$py_after_cust14[]=$row[0];
			$py_after_total14[]=$row[1];
		}
		$query="SELECT py.`cust`,SUM(py.amount) FROM payment py WHERE py.status=0 AND py.chque_return=0 AND py.bill_pay=2 AND date(py.payment_date) > '$backdate7' AND date(py.payment_date)<='$as_of' GROUP BY py.`cust`";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$py_after_cust7[]=$row[0];
			$py_after_total7[]=$row[1];
		}
		//---------------------Overpay for invoice after 7,14,30,45,60,90 days-----------------------------------//
		$query="SELECT py.`cust`,SUM(py.amount)-SUM(bm.`invoice_+total` + bm.`invoice_-total`) FROM bill_main bm, payment py WHERE bm.invoice_no=py.invoice_no AND py.`status`=0 AND bm.`status` NOT IN (0,7) AND py.chque_return=0 AND py.bill_pay=1 AND py.amount>(bm.`invoice_+total` + bm.`invoice_-total`) AND date(py.payment_date) > '$backdate90' AND date(py.payment_date)<='$as_of' GROUP BY py.`cust`";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$overpy_after_cust90[]=$row[0];
			$overpy_after_total90[]=$row[1];
		}
		$query="SELECT py.`cust`,SUM(py.amount)-SUM(bm.`invoice_+total` + bm.`invoice_-total`) FROM bill_main bm, payment py WHERE bm.invoice_no=py.invoice_no AND py.`status`=0 AND bm.`status` NOT IN (0,7) AND py.chque_return=0 AND py.bill_pay=1 AND py.amount>(bm.`invoice_+total` + bm.`invoice_-total`) AND date(py.payment_date) > '$backdate60' AND date(py.payment_date)<='$as_of' GROUP BY py.`cust`";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$overpy_after_cust60[]=$row[0];
			$overpy_after_total60[]=$row[1];
		}
		$query="SELECT py.`cust`,SUM(py.amount)-SUM(bm.`invoice_+total` + bm.`invoice_-total`) FROM bill_main bm, payment py WHERE bm.invoice_no=py.invoice_no AND py.`status`=0 AND bm.`status` NOT IN (0,7) AND py.chque_return=0 AND py.bill_pay=1 AND py.amount>(bm.`invoice_+total` + bm.`invoice_-total`) AND date(py.payment_date) > '$backdate45' AND date(py.payment_date)<='$as_of' GROUP BY py.`cust`";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$overpy_after_cust45[]=$row[0];
			$overpy_after_total45[]=$row[1];
		}
		$query="SELECT py.`cust`,SUM(py.amount)-SUM(bm.`invoice_+total` + bm.`invoice_-total`) FROM bill_main bm, payment py WHERE bm.invoice_no=py.invoice_no AND py.`status`=0 AND bm.`status` NOT IN (0,7) AND py.chque_return=0 AND py.bill_pay=1 AND py.amount>(bm.`invoice_+total` + bm.`invoice_-total`) AND date(py.payment_date) > '$backdate30' AND date(py.payment_date)<='$as_of' GROUP BY py.`cust`";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$overpy_after_cust30[]=$row[0];
			$overpy_after_total30[]=$row[1];
		}
		$query="SELECT py.`cust`,SUM(py.amount)-SUM(bm.`invoice_+total` + bm.`invoice_-total`) FROM bill_main bm, payment py WHERE bm.invoice_no=py.invoice_no AND py.`status`=0 AND bm.`status` NOT IN (0,7) AND py.chque_return=0 AND py.bill_pay=1 AND py.amount>(bm.`invoice_+total` + bm.`invoice_-total`) AND date(py.payment_date) > '$backdate14' AND date(py.payment_date)<='$as_of' GROUP BY py.`cust`";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$overpy_after_cust14[]=$row[0];
			$overpy_after_total14[]=$row[1];
		}
		$query="SELECT py.`cust`,SUM(py.amount)-SUM(bm.`invoice_+total` + bm.`invoice_-total`) FROM bill_main bm, payment py WHERE bm.invoice_no=py.invoice_no AND py.`status`=0 AND bm.`status` NOT IN (0,7) AND py.chque_return=0 AND py.bill_pay=1 AND py.amount>(bm.`invoice_+total` + bm.`invoice_-total`) AND date(py.payment_date) > '$backdate7' AND date(py.payment_date)<='$as_of' GROUP BY py.`cust`";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$overpy_after_cust7[]=$row[0];
			$overpy_after_total7[]=$row[1];
		}

		//-------------------------------------Over Pay end------------------------------------------------------//

		for($i=0;$i<sizeof($cust_id);$i++){
			$cust_id0=$cust_id[$i];

			$key=array_search($cust_id0,$cashback_bm_cust);
			if($key>-1) $return=$cashback_bm_total[$key]; else $return=0;

			//upto 0 days//
			$key=array_search($cust_id0,$py_cust_id);
			if($key>-1) $pay0=$py_total[$key]; else $pay0=0;
			$cust_cr_balance0[]=$bm_total[$i]-$pay0;
			$credit_total0+=$bm_total[$i]-$pay0;

			//upto 90 days//
			$key=array_search($cust_id0,$bm_cust90);
			if($key>-1) $bill90=$bm_total90[$key]; else $bill90=0;
			$key=array_search($cust_id0,$py_upto_cust90);
			if($key>-1) $pay90upto=$py_upto_total90[$key]; else $pay90upto=0;
			$key=array_search($cust_id0,$py_after_cust90);
			if($key>-1) $pay90after=$py_after_total90[$key]; else $pay90after=0;
			$key=array_search($cust_id0,$overpy_after_cust90);
			if($key>-1) $overpay90after=$overpy_after_total90[$key]; else $overpay90after=0;
			$balance90=$bill90+$return-$pay90upto-$pay90after-$overpay90after;
			if($balance90<0) $balance90=0;
			$cust_cr_balance90[]=$balance90;
			$credit_total90+=$balance90;

			//upto 60 days//
			$key=array_search($cust_id0,$bm_cust60);
			if($key>-1) $bill60=$bm_total60[$key]; else $bill60=0;
			$key=array_search($cust_id0,$py_upto_cust60);
			if($key>-1) $pay60upto=$py_upto_total60[$key]; else $pay60upto=0;
			$key=array_search($cust_id0,$py_after_cust60);
			if($key>-1) $pay60after=$py_after_total60[$key]; else $pay60after=0;
			$key=array_search($cust_id0,$overpy_after_cust60);
			if($key>-1) $overpay60after=$overpy_after_total60[$key]; else $overpay60after=0;
			$balance60=$bill60+$return-$pay60upto-$pay60after-$overpay60after;
			if($balance60<0) $balance60=0;
			$cust_cr_balance60[]=$balance60;
			$credit_total60+=$balance60;

			//upto 45 days//
			$key=array_search($cust_id0,$bm_cust45);
			if($key>-1) $bill45=$bm_total45[$key]; else $bill45=0;
			$key=array_search($cust_id0,$py_upto_cust45);
			if($key>-1) $pay45upto=$py_upto_total45[$key]; else $pay45upto=0;
			$key=array_search($cust_id0,$py_after_cust45);
			if($key>-1) $pay45after=$py_after_total45[$key]; else $pay45after=0;
			$key=array_search($cust_id0,$overpy_after_cust45);
			if($key>-1) $overpay45after=$overpy_after_total45[$key]; else $overpay45after=0;
			$balance45=$bill45+$return-$pay45upto-$pay45after-$overpay45after;
			if($balance45<0) $balance45=0;
			$cust_cr_balance45[]=$balance45;
			$credit_total45+=$balance45;

			//upto 30 days//
			$key=array_search($cust_id0,$bm_cust30);
			if($key>-1) $bill30=$bm_total30[$key]; else $bill30=0;
			$key=array_search($cust_id0,$py_upto_cust30);
			if($key>-1) $pay30upto=$py_upto_total30[$key]; else $pay30upto=0;
			$key=array_search($cust_id0,$py_after_cust30);
			if($key>-1) $pay30after=$py_after_total30[$key]; else $pay30after=0;
			$key=array_search($cust_id0,$overpy_after_cust30);
			if($key>-1) $overpay30after=$overpy_after_total30[$key]; else $overpay30after=0;
			$balance30=$bill30+$return-$pay30upto-$pay30after-$overpay30after;
			if($balance30<0) $balance30=0;
			$cust_cr_balance30[]=$balance30;
			$credit_total30+=$balance30;

			//upto 14 days//
			$key=array_search($cust_id0,$bm_cust14);
			if($key>-1) $bill14=$bm_total14[$key]; else $bill14=0;
			$key=array_search($cust_id0,$py_upto_cust14);
			if($key>-1) $pay14upto=$py_upto_total14[$key]; else $pay14upto=0;
			$key=array_search($cust_id0,$py_after_cust14);
			if($key>-1) $pay14after=$py_after_total14[$key]; else $pay14after=0;
			$key=array_search($cust_id0,$overpy_after_cust14);
			if($key>-1) $overpay14after=$overpy_after_total14[$key]; else $overpay14after=0;
			$balance14=$bill14+$return-$pay14upto-$pay14after-$overpay14after;
			if($balance14<0) $balance14=0;
			$cust_cr_balance14[]=$balance14;
			$credit_total14+=$balance14;

			//upto 7 days//
			$key=array_search($cust_id0,$bm_cust7);
			if($key>-1) $bill7=$bm_total7[$key]; else $bill7=0;
			$key=array_search($cust_id0,$py_upto_cust7);
			if($key>-1) $pay7upto=$py_upto_total7[$key]; else $pay7upto=0;
			$key=array_search($cust_id0,$py_after_cust7);
			if($key>-1) $pay7after=$py_after_total7[$key]; else $pay7after=0;
			$key=array_search($cust_id0,$overpy_after_cust7);
			if($key>-1) $overpay7after=$overpy_after_total7[$key]; else $overpay7after=0;
			$balance7=$bill7+$return-$pay7upto-$pay7after-$overpay7after;
			if($balance7<0) $balance7=0;
			$cust_cr_balance7[]=$balance7;
			$credit_total7+=$balance7;
		}
	}
}
*/

// updated by nirmal 12_07_2023
function getCreditData($sub_system){
	global $message,$decimal,$as_of,$cust_id,$cust_name,$cust_cr_balance0, $credit_total0, $days;

	$bm_cust_id=$bm_cust_name=$bm_total=$py_cust_id=$py_total = $days = array();
	$cashback_bm_cust=$cashback_bm_total= array();
	$cust_id= array();
	$return = '';
	$datenow=dateNow();
	$user_id=$_COOKIE['user_id'];
	$group_list='';
	$out=true;
	include('config.php');

	if($sub_system=='all') $sub_system_qry=''; else $sub_system_qry="AND cu.`sub_system`='$sub_system'";
	if($_REQUEST['components']=='report' || $_REQUEST['components']=='topmanager'){
		$storefilter2='';
	}else{
		$store_list=groupStores();
		$storefilter2="AND cu.associated_store IN (".$store_list.")";
	}
	if(isset($_GET['as_of'])){
		$as_of=$_GET['as_of'];
		if($as_of=='') $as_of=$datenow;
	}else{
		$as_of=$datenow;
	}
	if(isset($_GET['st'])){
		if($_GET['st']!=''){
			$st_id=$_GET['st'];
			$storefilter='AND cu.associated_store='.$st_id;
		}else $storefilter=$storefilter2;
	}else $storefilter=$storefilter2;
	if(isset($_GET['gp'])){
		if($_GET['gp']!=''){
			$gp_id=$_GET['gp'];
			$groupfilter="AND cu.associated_group='$gp_id'";
		}else $groupfilter='';
	}else $groupfilter='';
	if(isset($_GET['tw'])){
		if($_GET['tw']!=''){
			$town_id=$_GET['tw'];
			$townfilter="AND cu.associated_town='$town_id'";
		}else $townfilter='';
	}else $townfilter='';
	if(isset($_GET['up'])){
		if($_GET['up']!=''){
			$sm_id=$_GET['up'];
			$salemanfilter="AND cu.associated_salesman='$sm_id'";
		}else $salemanfilter='';
	}else $salemanfilter='';

	$date1=date_create($as_of);
	$date2=date_create($datenow);
	$diff=date_diff($date1,$date2);
	$diff1=$diff->format("%R");
	$diff2=$diff->format("%a");
	if($diff1=="-") $message='Invalid "As of" Date';
	$diff3=$diff2*24*60*60;

	$query="SELECT `day` FROM credit_report_days";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$days[] = $row[0];
	}
	$days = array_reverse($days);

	// Create global and normal variables dynamically
	foreach ($days as $day) {
		global ${'backdate' . $day};
		global ${'cust_cr_balance' . $day};
		global ${'credit_total' . $day};

		${'backdate' . $day} = date("Y-m-d",(time()-($day*24*60*60)-$diff3));
		${'bm_cust' . $day} = array();
		${'bm_total' . $day} = array();
		${'py_upto_cust' . $day} = array();
		${'py_upto_total' . $day} = array();
		${'py_after_cust' . $day} = array();
		${'py_after_total' . $day} = array();
		${'overpy_after_cust' . $day} = array();
		${'overpy_after_total' . $day} = array();
		${'cust_cr_balance' . $day} = array();

		// ${'bill' . $day} = '';
		// ${'pay' . $day . 'upto'} = '';
		// ${'pay' . $day . 'after'} = '';
		// ${'balance' . $day} = '';
		// ${'overpay' . $day . 'after'} = '';
	}

	$result = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='decimal'");
	$row = mysqli_fetch_assoc($result);
	$decimal=$row['value'];

	if($_GET['components']=='marketing'){
		if($groupfilter==''){
			$query="SELECT ug.`group` FROM user_to_group ug WHERE ug.`user`='$user_id'";
			$result=mysqli_query($conn2,$query);
			while($row=mysqli_fetch_array($result)){
				$group_list.=$row[0].',';
			}
			if($group_list!=''){
				$group_list=rtrim($group_list,',');
				$groupfilter="AND cu.associated_group IN ($group_list)";
			}
		}else{
			if(!validateGroupPermission($user_id,$gp_id)) $out=false;
		}
	}

	if($out){
		$query="SELECT cu.id,cu.name,SUM(bm.`invoice_+total`)+SUM(bm.`invoice_-total`) FROM bill_main bm, cust cu WHERE bm.`cust`=cu.id AND bm.exclude=0 AND bm.`status` NOT IN (0,7) AND bm.`lock`=1 AND cu.`status`!=0 AND date(bm.billed_timestamp)<='$as_of' $sub_system_qry $storefilter $townfilter $groupfilter $salemanfilter GROUP BY bm.`cust` ORDER BY cu.name";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$cust_id[]=$row[0];
			$cust_name[]=$row[1];
			$bm_total[]=$row[2];
		}
		$query="SELECT py.`cust`,SUM(py.amount) FROM payment py WHERE py.status=0 AND py.chque_return=0 AND date(py.payment_date)<='$as_of' GROUP BY py.`cust`";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$py_cust_id[]=$row[0];
			$py_total[]=$row[1];
		}
		$query="SELECT bm.`cust`,SUM(bm.`invoice_-total`) FROM bill_main bm WHERE bm.`status` NOT IN (0,7) AND bm.exclude=0 AND bm.`lock`=1 AND date(bm.billed_timestamp)<='$as_of' GROUP BY bm.`cust`";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$cashback_bm_cust[]=$row[0];
			$cashback_bm_total[]=$row[1];
		}

		foreach ($days as $day) {
			$bkdate = ${'backdate' . $day};

			$query="SELECT bm.`cust`,SUM(bm.`invoice_+total`) FROM bill_main bm WHERE bm.`status` NOT IN (0,7) AND bm.exclude=0 AND bm.`lock`=1 AND date(bm.billed_timestamp)<='$bkdate' GROUP BY bm.`cust`";
			$result=mysqli_query($conn2,$query);
			while($row=mysqli_fetch_array($result)){
				${'bm_cust' . $day}[] = $row[0];
        		${'bm_total' . $day}[] = $row[1];
			}

			$query="SELECT py.`cust`,SUM(py.amount) FROM payment py WHERE py.status=0 AND py.chque_return=0 AND date(py.payment_date) <= '$bkdate' GROUP BY py.`cust`";
			$result=mysqli_query($conn2,$query);
			while($row=mysqli_fetch_array($result)){
				${'py_upto_cust' . $day}[] = $row[0];
        		${'py_upto_total' . $day}[] = $row[1];
			}

			$query="SELECT py.`cust`,SUM(py.amount) FROM payment py WHERE py.status=0 AND py.chque_return=0 AND py.bill_pay=2 AND date(py.payment_date) > '$bkdate' AND date(py.payment_date)<='$as_of' GROUP BY py.`cust`";
			$result=mysqli_query($conn2,$query);
			while($row=mysqli_fetch_array($result)){
				${'py_after_cust' . $day}[] = $row[0];
        		${'py_after_total' . $day}[] = $row[1];
			}

			//---------------------Overpay for invoice after database days-----------------------------------//
			$query="SELECT py.`cust`,SUM(py.amount)-SUM(bm.`invoice_+total` + bm.`invoice_-total`) FROM bill_main bm, payment py WHERE bm.invoice_no=py.invoice_no AND py.`status`=0 AND bm.`status` NOT IN (0,7) AND py.chque_return=0 AND py.bill_pay=1 AND py.amount>(bm.`invoice_+total` + bm.`invoice_-total`) AND date(py.payment_date) > '$bkdate' AND date(py.payment_date)<='$as_of' GROUP BY py.`cust`";
			$result=mysqli_query($conn2,$query);

			while($row=mysqli_fetch_array($result)){
				${'overpy_after_cust' . $day}[] = $row[0];
        		${'overpy_after_total' . $day}[] = $row[1];
			}
		}

		//-------------------------------------Over Pay end------------------------------------------------------//

		for($i=0;$i<sizeof($cust_id);$i++){
			$cust_id0=$cust_id[$i];

			$key=array_search($cust_id0,$cashback_bm_cust);
			if($key>-1) $return=$cashback_bm_total[$key]; else $return=0;

			//upto 0 days//
			$key=array_search($cust_id0,$py_cust_id);
			if($key>-1) $pay0=$py_total[$key]; else $pay0=0;
			// $cust_cr_balance0[]=$bm_total[$i]-$pay0;
            $value = round(($bm_total[$i]-$pay0), $decimal);
			if($value == 0){
				$cust_cr_balance0[]=0;
			}else{
				$cust_cr_balance0[]=$value;
			}
			$credit_total0+=$bm_total[$i]-$pay0;



			foreach ($days as $day) {
				$key=array_search($cust_id0,${'bm_cust' . $day});
				if($key>-1) ${'bill' . $day}=${'bm_total' . $day}[$key]; else ${'bill' . $day} = 0;
				$key=array_search($cust_id0,${'py_upto_cust' . $day});
				if($key>-1) ${'pay' . $day . 'upto'}=${'py_upto_total' . $day}[$key]; else ${'pay' . $day . 'upto'} = 0;
				$key=array_search($cust_id0,${'py_after_cust' . $day});
				if($key>-1) ${'pay' . $day . 'after'}=${'py_after_total' . $day}[$key]; else ${'pay' . $day . 'after'} = 0;
				$key=array_search($cust_id0,${'overpy_after_cust' . $day});
				if($key>-1) ${'overpay' . $day . 'after'} = ${'overpy_after_total' . $day}[$key]; else ${'overpay' . $day . 'after'} = 0;
				${'balance' . $day} = ${'bill' . $day}+$return-${'pay' . $day . 'upto'}-${'pay' . $day . 'after'}-${'overpay' . $day . 'after'};
				if(${'balance' . $day}<0) ${'balance' . $day} = 0;
				${'cust_cr_balance' . $day}[] = ${'balance' . $day};
				${'credit_total' . $day} += ${'balance' . $day};
			}
		}

	}
}

/*
function getCreditData3($sub_system){
	global $message,$decimal,$as_of,$cust_id,$cust_name,$cust_cr_balance0,$cust_cr_balance8,$cust_cr_balance15,$cust_cr_balance31,$cust_cr_balance46,$cust_cr_balance61,$cust_cr_balance100,$credit_total0,$credit_total8,$credit_total15,$credit_total31,$credit_total46,$credit_total61,$credit_total100;

	$bm_cust_id=$bm_cust_name=$bm_total=$py_cust_id=$py_total=$bm_cust100=$bm_total100=$bm_cust61=$bm_total61=$bm_cust46=$bm_total46=$bm_cust31=$bm_total31=$bm_cust15=$bm_total15=$bm_cust8=$bm_total8=array();
	$cashback_bm_cust=$cashback_bm_total=$py_upto_cust100=$py_upto_total100=$py_upto_cust61=$py_upto_total61=$py_upto_cust46=$py_upto_total46=$py_upto_cust31=$py_upto_total31=$py_upto_cust15=$py_upto_total15=$py_upto_cust8=$py_upto_total8=array();
	$cust_id=$py_after_cust100=$py_after_total100=$py_after_cust61=$py_after_total61=$py_after_cust46=$py_after_total46=$py_after_cust31=$py_after_total31=$py_after_cust15=$py_after_total15=$py_after_cust8=$py_after_total8=array();
	$overpy_after_cust100=$overpy_after_total100=$overpy_after_cust61=$overpy_after_total61=$overpy_after_cust46=$overpy_after_total46=$overpy_after_cust31=$overpy_after_total31=$overpy_after_cust15=$overpy_after_total15=$overpy_after_cust8=$overpy_after_total8=array();

	$datenow=dateNow();
	$user_id=$_COOKIE['user_id'];
	$group_list='';
	$out=true;

	if($sub_system=='all') $sub_system_qry=''; else $sub_system_qry="AND cu.`sub_system`='$sub_system'";
	if($_REQUEST['components']=='report' || $_REQUEST['components']=='topmanager'){
		$storefilter2='';
	}else{
		$store_list=groupStores();
		$storefilter2="AND cu.associated_store IN (".$store_list.")";
	}
	if(isset($_GET['as_of'])){
		$as_of=$_GET['as_of'];
		if($as_of=='') $as_of=$datenow;
	}else{
		$as_of=$datenow;
	}
	if(isset($_GET['st'])){
		if($_GET['st']!=''){
			$st_id=$_GET['st'];
			$storefilter='AND cu.associated_store='.$st_id;
		}else $storefilter=$storefilter2;
	}else $storefilter=$storefilter2;
	if(isset($_GET['gp'])){
		if($_GET['gp']!=''){
			$gp_id=$_GET['gp'];
			$groupfilter="AND cu.associated_group='$gp_id'";
		}else $groupfilter='';
	}else $groupfilter='';
	if(isset($_GET['tw'])){
		if($_GET['tw']!=''){
			$town_id=$_GET['tw'];
			$townfilter="AND cu.associated_town='$town_id'";
		}else $townfilter='';
	}else $townfilter='';
	if(isset($_GET['up'])){
		if($_GET['up']!=''){
			$sm_id=$_GET['up'];
			$salemanfilter="AND cu.associated_salesman='$sm_id'";
		}else $salemanfilter='';
	}else $salemanfilter='';

		$date1=date_create($as_of);
		$date2=date_create($datenow);
		$diff=date_diff($date1,$date2);
		$diff1=$diff->format("%R");
		$diff2=$diff->format("%a");
		if($diff1=="-") $message='Invalid "As of" Date';
		$diff3=$diff2*24*60*60;

		$backdate100=date("Y-m-d",(time()-(100*24*60*60)-$diff3));
		$backdate61=date("Y-m-d",(time()-(61*24*60*60)-$diff3));
		$backdate46=date("Y-m-d",(time()-(46*24*60*60)-$diff3));
		$backdate31=date("Y-m-d",(time()-(31*24*60*60)-$diff3));
		$backdate15=date("Y-m-d",(time()-(15*24*60*60)-$diff3));
		$backdate8=date("Y-m-d",(time()-(8*24*60*60)-$diff3));

	include('config.php');

	$result = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='decimal'");
	$row = mysqli_fetch_assoc($result);
	$decimal=$row['value'];

	if($_GET['components']=='marketing'){
		if($groupfilter==''){
			$query="SELECT ug.`group` FROM user_to_group ug WHERE ug.`user`='$user_id'";
			$result=mysqli_query($conn2,$query);
			while($row=mysqli_fetch_array($result)){
				$group_list.=$row[0].',';
			}
			if($group_list!=''){
				$group_list=rtrim($group_list,',');
				$groupfilter="AND cu.associated_group IN ($group_list)";
			}
		}else{
			if(!validateGroupPermission($user_id,$gp_id)) $out=false;
		}
	}

	if($out){
		$query="SELECT cu.id,cu.name,SUM(bm.`invoice_+total`)+SUM(bm.`invoice_-total`) FROM bill_main bm, cust cu WHERE bm.`cust`=cu.id AND bm.exclude=0 AND bm.`status` NOT IN (0,7) AND bm.`lock`=1 AND cu.`status`!=0 AND date(bm.billed_timestamp)<='$as_of' $sub_system_qry $storefilter $townfilter $groupfilter $salemanfilter GROUP BY bm.`cust` ORDER BY cu.name";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$cust_id[]=$row[0];
			$cust_name[]=$row[1];
			$bm_total[]=$row[2];
		}
		$query="SELECT py.`cust`,SUM(py.amount) FROM payment py WHERE py.status=0 AND py.chque_return=0 AND date(py.payment_date)<='$as_of' GROUP BY py.`cust`";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$py_cust_id[]=$row[0];
			$py_total[]=$row[1];
		}
		$query="SELECT bm.`cust`,SUM(bm.`invoice_+total`) FROM bill_main bm WHERE bm.`status` NOT IN (0,7) AND bm.exclude=0 AND bm.`lock`=1 AND date(bm.billed_timestamp)<='$backdate100' GROUP BY bm.`cust`";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$bm_cust100[]=$row[0];
			$bm_total100[]=$row[1];
		}
		$query="SELECT bm.`cust`,SUM(bm.`invoice_+total`) FROM bill_main bm WHERE bm.`status` NOT IN (0,7) AND bm.exclude=0 AND bm.`lock`=1 AND date(bm.billed_timestamp)<='$backdate61' GROUP BY bm.`cust`";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$bm_cust61[]=$row[0];
			$bm_total61[]=$row[1];
		}
		$query="SELECT bm.`cust`,SUM(bm.`invoice_+total`) FROM bill_main bm WHERE bm.`status` NOT IN (0,7) AND bm.exclude=0 AND bm.`lock`=1 AND date(bm.billed_timestamp)<='$backdate46' GROUP BY bm.`cust`";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$bm_cust46[]=$row[0];
			$bm_total46[]=$row[1];
		}
		$query="SELECT bm.`cust`,SUM(bm.`invoice_+total`) FROM bill_main bm WHERE bm.`status` NOT IN (0,7) AND bm.exclude=0 AND bm.`lock`=1 AND date(bm.billed_timestamp)<='$backdate31' GROUP BY bm.`cust`";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$bm_cust31[]=$row[0];
			$bm_total31[]=$row[1];
		}
		$query="SELECT bm.`cust`,SUM(bm.`invoice_+total`) FROM bill_main bm WHERE bm.`status` NOT IN (0,7) AND bm.exclude=0 AND bm.`lock`=1 AND date(bm.billed_timestamp)<='$backdate15' GROUP BY bm.`cust`";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$bm_cust15[]=$row[0];
			$bm_total15[]=$row[1];
		}
		$query="SELECT bm.`cust`,SUM(bm.`invoice_+total`) FROM bill_main bm WHERE bm.`status` NOT IN (0,7) AND bm.exclude=0 AND bm.`lock`=1 AND date(bm.billed_timestamp)<='$backdate8' GROUP BY bm.`cust`";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$bm_cust8[]=$row[0];
			$bm_total8[]=$row[1];
		}
		$query="SELECT bm.`cust`,SUM(bm.`invoice_-total`) FROM bill_main bm WHERE bm.`status` NOT IN (0,7) AND bm.exclude=0 AND bm.`lock`=1 AND date(bm.billed_timestamp)<='$as_of' GROUP BY bm.`cust`";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$cashback_bm_cust[]=$row[0];
			$cashback_bm_total[]=$row[1];
		}
		$query="SELECT py.`cust`,SUM(py.amount) FROM payment py WHERE py.status=0 AND py.chque_return=0 AND date(py.payment_date) <= '$backdate100' GROUP BY py.`cust`";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$py_upto_cust100[]=$row[0];
			$py_upto_total100[]=$row[1];
		}
		$query="SELECT py.`cust`,SUM(py.amount) FROM payment py WHERE py.status=0 AND py.chque_return=0 AND date(py.payment_date) <= '$backdate61' GROUP BY py.`cust`";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$py_upto_cust61[]=$row[0];
			$py_upto_total61[]=$row[1];
		}
		$query="SELECT py.`cust`,SUM(py.amount) FROM payment py WHERE py.status=0 AND py.chque_return=0 AND date(py.payment_date) <= '$backdate46' GROUP BY py.`cust`";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$py_upto_cust46[]=$row[0];
			$py_upto_total46[]=$row[1];
		}
		$query="SELECT py.`cust`,SUM(py.amount) FROM payment py WHERE py.status=0 AND py.chque_return=0 AND date(py.payment_date) <= '$backdate31' GROUP BY py.`cust`";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$py_upto_cust31[]=$row[0];
			$py_upto_total31[]=$row[1];
		}
		$query="SELECT py.`cust`,SUM(py.amount) FROM payment py WHERE py.status=0 AND py.chque_return=0 AND date(py.payment_date) <= '$backdate15' GROUP BY py.`cust`";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$py_upto_cust15[]=$row[0];
			$py_upto_total15[]=$row[1];
		}
		$query="SELECT py.`cust`,SUM(py.amount) FROM payment py WHERE py.status=0 AND py.chque_return=0 AND date(py.payment_date) <= '$backdate8' GROUP BY py.`cust`";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$py_upto_cust8[]=$row[0];
			$py_upto_total8[]=$row[1];
		}
		$query="SELECT py.`cust`,SUM(py.amount) FROM payment py WHERE py.status=0 AND py.chque_return=0 AND py.bill_pay=2 AND date(py.payment_date) > '$backdate100' AND date(py.payment_date)<='$as_of' GROUP BY py.`cust`";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$py_after_cust100[]=$row[0];
			$py_after_total100[]=$row[1];
		}
		$query="SELECT py.`cust`,SUM(py.amount) FROM payment py WHERE py.status=0 AND py.chque_return=0 AND py.bill_pay=2 AND date(py.payment_date) > '$backdate61' AND date(py.payment_date)<='$as_of' GROUP BY py.`cust`";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$py_after_cust61[]=$row[0];
			$py_after_total61[]=$row[1];
		}
		$query="SELECT py.`cust`,SUM(py.amount) FROM payment py WHERE py.status=0 AND py.chque_return=0 AND py.bill_pay=2 AND date(py.payment_date) > '$backdate46' AND date(py.payment_date)<='$as_of' GROUP BY py.`cust`";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$py_after_cust46[]=$row[0];
			$py_after_total46[]=$row[1];
		}
		$query="SELECT py.`cust`,SUM(py.amount) FROM payment py WHERE py.status=0 AND py.chque_return=0 AND py.bill_pay=2 AND date(py.payment_date) > '$backdate31' AND date(py.payment_date)<='$as_of' GROUP BY py.`cust`";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$py_after_cust31[]=$row[0];
			$py_after_total31[]=$row[1];
		}
		$query="SELECT py.`cust`,SUM(py.amount) FROM payment py WHERE py.status=0 AND py.chque_return=0 AND py.bill_pay=2 AND date(py.payment_date) > '$backdate15' AND date(py.payment_date)<='$as_of' GROUP BY py.`cust`";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$py_after_cust15[]=$row[0];
			$py_after_total15[]=$row[1];
		}
		$query="SELECT py.`cust`,SUM(py.amount) FROM payment py WHERE py.status=0 AND py.chque_return=0 AND py.bill_pay=2 AND date(py.payment_date) > '$backdate8' AND date(py.payment_date)<='$as_of' GROUP BY py.`cust`";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$py_after_cust8[]=$row[0];
			$py_after_total8[]=$row[1];
		}
		//---------------------Overpay for invoice after 8,15,31,46,61,100 days-----------------------------------//
		$query="SELECT py.`cust`,SUM(py.amount)-SUM(bm.`invoice_+total` + bm.`invoice_-total`) FROM bill_main bm, payment py WHERE bm.invoice_no=py.invoice_no AND py.`status`=0 AND bm.`status` NOT IN (0,7) AND py.chque_return=0 AND py.bill_pay=1 AND py.amount>(bm.`invoice_+total` + bm.`invoice_-total`) AND date(py.payment_date) > '$backdate100' AND date(py.payment_date)<='$as_of' GROUP BY py.`cust`";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$overpy_after_cust100[]=$row[0];
			$overpy_after_total100[]=$row[1];
		}
		$query="SELECT py.`cust`,SUM(py.amount)-SUM(bm.`invoice_+total` + bm.`invoice_-total`) FROM bill_main bm, payment py WHERE bm.invoice_no=py.invoice_no AND py.`status`=0 AND bm.`status` NOT IN (0,7) AND py.chque_return=0 AND py.bill_pay=1 AND py.amount>(bm.`invoice_+total` + bm.`invoice_-total`) AND date(py.payment_date) > '$backdate61' AND date(py.payment_date)<='$as_of' GROUP BY py.`cust`";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$overpy_after_cust61[]=$row[0];
			$overpy_after_total61[]=$row[1];
		}
		$query="SELECT py.`cust`,SUM(py.amount)-SUM(bm.`invoice_+total` + bm.`invoice_-total`) FROM bill_main bm, payment py WHERE bm.invoice_no=py.invoice_no AND py.`status`=0 AND bm.`status` NOT IN (0,7) AND py.chque_return=0 AND py.bill_pay=1 AND py.amount>(bm.`invoice_+total` + bm.`invoice_-total`) AND date(py.payment_date) > '$backdate46' AND date(py.payment_date)<='$as_of' GROUP BY py.`cust`";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$overpy_after_cust46[]=$row[0];
			$overpy_after_total46[]=$row[1];
		}
		$query="SELECT py.`cust`,SUM(py.amount)-SUM(bm.`invoice_+total` + bm.`invoice_-total`) FROM bill_main bm, payment py WHERE bm.invoice_no=py.invoice_no AND py.`status`=0 AND bm.`status` NOT IN (0,7) AND py.chque_return=0 AND py.bill_pay=1 AND py.amount>(bm.`invoice_+total` + bm.`invoice_-total`) AND date(py.payment_date) > '$backdate31' AND date(py.payment_date)<='$as_of' GROUP BY py.`cust`";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$overpy_after_cust31[]=$row[0];
			$overpy_after_total31[]=$row[1];
		}
		$query="SELECT py.`cust`,SUM(py.amount)-SUM(bm.`invoice_+total` + bm.`invoice_-total`) FROM bill_main bm, payment py WHERE bm.invoice_no=py.invoice_no AND py.`status`=0 AND bm.`status` NOT IN (0,7) AND py.chque_return=0 AND py.bill_pay=1 AND py.amount>(bm.`invoice_+total` + bm.`invoice_-total`) AND date(py.payment_date) > '$backdate15' AND date(py.payment_date)<='$as_of' GROUP BY py.`cust`";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$overpy_after_cust15[]=$row[0];
			$overpy_after_total15[]=$row[1];
		}
		$query="SELECT py.`cust`,SUM(py.amount)-SUM(bm.`invoice_+total` + bm.`invoice_-total`) FROM bill_main bm, payment py WHERE bm.invoice_no=py.invoice_no AND py.`status`=0 AND bm.`status` NOT IN (0,7) AND py.chque_return=0 AND py.bill_pay=1 AND py.amount>(bm.`invoice_+total` + bm.`invoice_-total`) AND date(py.payment_date) > '$backdate8' AND date(py.payment_date)<='$as_of' GROUP BY py.`cust`";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$overpy_after_cust8[]=$row[0];
			$overpy_after_total8[]=$row[1];
		}

		//-------------------------------------Over Pay end------------------------------------------------------//

		for($i=0;$i<sizeof($cust_id);$i++){
			$cust_id0=$cust_id[$i];

			$key=array_search($cust_id0,$cashback_bm_cust);
			if($key>-1) $return=$cashback_bm_total[$key]; else $return=0;

			//upto 0 days//
			$key=array_search($cust_id0,$py_cust_id);
			if($key>-1) $pay0=$py_total[$key]; else $pay0=0;
			$cust_cr_balance0[]=$bm_total[$i]-$pay0;
			$credit_total0+=$bm_total[$i]-$pay0;

			//upto 100 days//
			$key=array_search($cust_id0,$bm_cust100);
			if($key>-1) $bill100=$bm_total100[$key]; else $bill100=0;
			$key=array_search($cust_id0,$py_upto_cust100);
			if($key>-1) $pay100upto=$py_upto_total100[$key]; else $pay100upto=0;
			$key=array_search($cust_id0,$py_after_cust100);
			if($key>-1) $pay100after=$py_after_total100[$key]; else $pay100after=0;
			$key=array_search($cust_id0,$overpy_after_cust100);
			if($key>-1) $overpay100after=$overpy_after_total100[$key]; else $overpay100after=0;
			$balance100=$bill100+$return-$pay100upto-$pay100after-$overpay100after;
			if($balance100<0) $balance100=0;
			$cust_cr_balance100[]=$balance100;
			$credit_total100+=$balance100;

			//upto 61 days//
			$key=array_search($cust_id0,$bm_cust61);
			if($key>-1) $bill61=$bm_total61[$key]; else $bill61=0;
			$key=array_search($cust_id0,$py_upto_cust61);
			if($key>-1) $pay61upto=$py_upto_total61[$key]; else $pay61upto=0;
			$key=array_search($cust_id0,$py_after_cust61);
			if($key>-1) $pay61after=$py_after_total61[$key]; else $pay61after=0;
			$key=array_search($cust_id0,$overpy_after_cust61);
			if($key>-1) $overpay61after=$overpy_after_total61[$key]; else $overpay61after=0;
			$balance61=$bill61+$return-$pay61upto-$pay61after-$overpay61after;
			if($balance61<0) $balance61=0;
			$cust_cr_balance61[]=$balance61;
			$credit_total61+=$balance61;

			//upto 46 days//
			$key=array_search($cust_id0,$bm_cust46);
			if($key>-1) $bill46=$bm_total46[$key]; else $bill46=0;
			$key=array_search($cust_id0,$py_upto_cust46);
			if($key>-1) $pay46upto=$py_upto_total46[$key]; else $pay46upto=0;
			$key=array_search($cust_id0,$py_after_cust46);
			if($key>-1) $pay46after=$py_after_total46[$key]; else $pay46after=0;
			$key=array_search($cust_id0,$overpy_after_cust46);
			if($key>-1) $overpay46after=$overpy_after_total46[$key]; else $overpay46after=0;
			$balance46=$bill46+$return-$pay46upto-$pay46after-$overpay46after;
			if($balance46<0) $balance46=0;
			$cust_cr_balance46[]=$balance46;
			$credit_total46+=$balance46;

			//upto 31 days//
			$key=array_search($cust_id0,$bm_cust31);
			if($key>-1) $bill31=$bm_total31[$key]; else $bill31=0;
			$key=array_search($cust_id0,$py_upto_cust31);
			if($key>-1) $pay31upto=$py_upto_total31[$key]; else $pay31upto=0;
			$key=array_search($cust_id0,$py_after_cust31);
			if($key>-1) $pay31after=$py_after_total31[$key]; else $pay31after=0;
			$key=array_search($cust_id0,$overpy_after_cust31);
			if($key>-1) $overpay31after=$overpy_after_total31[$key]; else $overpay31after=0;
			$balance31=$bill31+$return-$pay31upto-$pay31after-$overpay31after;
			if($balance31<0) $balance31=0;
			$cust_cr_balance31[]=$balance31;
			$credit_total31+=$balance31;

			//upto 15 days//
			$key=array_search($cust_id0,$bm_cust15);
			if($key>-1) $bill15=$bm_total15[$key]; else $bill15=0;
			$key=array_search($cust_id0,$py_upto_cust15);
			if($key>-1) $pay15upto=$py_upto_total15[$key]; else $pay15upto=0;
			$key=array_search($cust_id0,$py_after_cust15);
			if($key>-1) $pay15after=$py_after_total15[$key]; else $pay15after=0;
			$key=array_search($cust_id0,$overpy_after_cust15);
			if($key>-1) $overpay15after=$overpy_after_total15[$key]; else $overpay15after=0;
			$balance15=$bill15+$return-$pay15upto-$pay15after-$overpay15after;
			if($balance15<0) $balance15=0;
			$cust_cr_balance15[]=$balance15;
			$credit_total15+=$balance15;

			//upto 8 days//
			$key=array_search($cust_id0,$bm_cust8);
			if($key>-1) $bill8=$bm_total8[$key]; else $bill8=0;
			$key=array_search($cust_id0,$py_upto_cust8);
			if($key>-1) $pay8upto=$py_upto_total8[$key]; else $pay8upto=0;
			$key=array_search($cust_id0,$py_after_cust8);
			if($key>-1) $pay8after=$py_after_total8[$key]; else $pay8after=0;
			$key=array_search($cust_id0,$overpy_after_cust8);
			if($key>-1) $overpay8after=$overpy_after_total8[$key]; else $overpay8after=0;
			$balance8=$bill8+$return-$pay8upto-$pay8after-$overpay8after;
			if($balance8<0) $balance8=0;
			$cust_cr_balance8[]=$balance8;
			$credit_total8+=$balance8;
		}
	}
}
*/

/*
function getCreditData11($sub_system){
	global $cust_id,$cust_name,$cust_cr_balance0,$cust_cr_balance7,$cust_cr_balance14,$cust_cr_balance30,$credit_total0,$credit_total7,$credit_total14,$credit_total30;
	if($sub_system=='all') $sub_system_qry=''; else $sub_system_qry="AND cu.`sub_system`='$sub_system'";
	if($_REQUEST['components']=='report' || $_REQUEST['components']=='topmanager'){
		$storefilter2='';
	}else{
		$store_list=groupStores();
		$storefilter2="AND cu.associated_store IN (".$store_list.")";
	}
	if(isset($_GET['st'])){
		if($_GET['st']!=''){
			$st_id=$_GET['st'];
			$storefilter='AND cu.associated_store='.$st_id;
		}else $storefilter=$storefilter2;
	}else $storefilter=$storefilter2;
	if(isset($_GET['gp'])){
		if($_GET['gp']!=''){
			$gp_id=$_GET['gp'];
			$groupfilter="AND cu.associated_group='$gp_id'";
		}else $groupfilter='';
	}else $groupfilter='';
	if(isset($_GET['up'])){
		if($_GET['up']!=''){
			$sm_id=$_GET['up'];
			$salemanfilter="AND cu.associated_salesman='$sm_id'";
		}else $salemanfilter='';
	}else $salemanfilter='';
		$backdate30=date("Y-m-d",(time()-(30*24*60*60)));
		$backdate14=date("Y-m-d",(time()-(14*24*60*60)));
		$backdate7=date("Y-m-d",(time()-(7*24*60*60)));

	include('config.php');
	$query="SELECT DISTINCT cu.id,cu.name FROM bill bi, bill_main bm, cust cu WHERE bm.invoice_no=bi.invoice_no AND bm.`cust`=cu.id AND bm.exclude=0 AND cu.`status`!=0 $sub_system_qry $storefilter $groupfilter $salemanfilter";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$cust_id[]=$row[0];
		$cust_name[]=$row[1];
		$result1 = mysqli_query($conn,"SELECT SUM(bm.`invoice_+total`)+SUM(bm.`invoice_-total`) FROM bill_main bm WHERE bm.`status` NOT IN (0,7) AND bm.`lock`=1 AND bm.exclude=0 AND bm.`cust`='$row[0]'");
		$row1=mysqli_fetch_row($result1);
		$bill_total=$row1[0];
		$result2 = mysqli_query($conn,"SELECT SUM(py.amount) FROM payment py WHERE py.status=0 AND py.chque_return=0 AND py.cust='$row[0]'");
		$row2 = mysqli_fetch_row($result2);
		$payment_total=$row2[0];
		$cust_cr_balance0[]=$bill_total-$payment_total;
		$credit_total0+=$bill_total-$payment_total;

		$query1="SELECT SUM(bm.`invoice_+total`) FROM bill_main bm WHERE bm.`status` NOT IN (0,7) AND bm.exclude=0 AND bm.`lock`=1 AND bm.`cust`='$row[0]' AND bm.billed_timestamp<= '$backdate30 23:59:59'";
		$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
		$totalbill30=$row1[0];

		$query1="SELECT SUM(bm.`invoice_+total`) FROM bill_main bm WHERE bm.`status` NOT IN (0,7) AND bm.exclude=0 AND bm.`lock`=1 AND bm.`cust`='$row[0]' AND bm.billed_timestamp<= '$backdate14 23:59:59'";
		$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
		$totalbill14=$row1[0];

		$query1="SELECT SUM(bm.`invoice_+total`) FROM bill_main bm WHERE bm.`status` NOT IN (0,7) AND bm.exclude=0 AND bm.`lock`=1 AND bm.`cust`='$row[0]' AND bm.billed_timestamp<= '$backdate7 23:59:59'";
		$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
		$totalbill7=$row1[0];

		$query1="SELECT SUM(bm.`invoice_-total`) FROM bill_main bm WHERE bm.`status` NOT IN (0,7) AND bm.exclude=0 AND bm.`lock`=1 AND bm.`cust`='$row[0]'";
		$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
		$returnbill=$row1[0];

		$query1="SELECT SUM(py.amount) FROM payment py WHERE py.status=0 AND py.cust='$row[0]' AND py.chque_return=0 AND py.payment_date <= '$backdate30 23:59:59'";
		$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
		$payment_upto30=$row1[0];

		$query1="SELECT SUM(py.amount) FROM payment py WHERE py.status=0 AND py.cust='$row[0]' AND py.chque_return=0 AND py.payment_date <= '$backdate14 23:59:59'";
		$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
		$payment_upto14=$row1[0];

		$query1="SELECT SUM(py.amount) FROM payment py WHERE py.status=0 AND py.cust='$row[0]' AND py.chque_return=0 AND py.payment_date <= '$backdate7 23:59:59'";
		$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
		$payment_upto7=$row1[0];

		$query1="SELECT SUM(py.amount) FROM payment py WHERE py.status=0 AND py.cust='$row[0]' AND py.chque_return=0 AND py.bill_pay=2 AND py.payment_date > '$backdate30 23:59:59'";
		if($row[0]==9) print $query1.'<br />';
		$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
		$payment_after30=$row1[0];

		$query1="SELECT SUM(py.amount) FROM payment py WHERE py.status=0 AND py.cust='$row[0]' AND py.chque_return=0 AND py.bill_pay=2 AND py.payment_date > '$backdate14 23:59:59'";
		$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
		$payment_after14=$row1[0];

		$query1="SELECT SUM(py.amount) FROM payment py WHERE py.status=0 AND py.cust='$row[0]' AND py.chque_return=0 AND py.bill_pay=2 AND py.payment_date > '$backdate7 23:59:59'";
		$row1=mysqli_fetch_row(mysqli_query($conn,$query1));
		$payment_after7=$row1[0];

		$balance30=$totalbill30+$returnbill-$payment_upto30-$payment_after30;
		$balance14=$totalbill14+$returnbill-$payment_upto14-$payment_after14;
		$balance7=$totalbill7+$returnbill-$payment_upto7-$payment_after7;
		if($balance30<0) $balance30=0;
		if($balance14<0) $balance14=0;
		if($balance7<0) $balance7=0;
		$cust_cr_balance30[]=$balance30;
		$cust_cr_balance14[]=$balance14;
		$cust_cr_balance7[]=$balance7;
		$credit_total30+=$balance30;
		$credit_total14+=$balance14;
		$credit_total7+=$balance7;
	}
}
*/

//------------------------------ Chque return and postponded for supervisor ---------------------------------//
// added by E.S.P Nirmal 21_06_30
function getCheques($sub_system){
	global $chq0_id,$chq0_invno,$chq0_cuname,$chq0_amount,$chq0_no,$chq0_bank,$chq0_branch,$chq0_date,$chq0_paymentdate,$chq0_returndate,$chq0_code,$chq0_salesman,$salesman_filter,$chq2_banked,$chq2_retuned,
	$chq0_group_id, $chq0_group_name, $chq0_user_id, $chq0_username,$chq2_postpone,$salesman,$group,
	$chq0_postpond_id, $chq0_postpond_invno, $chq0_postpond_cuname, $chq0_postpond_amount, $chq0_postpond_no, $chq0_postpond_bank, $chq0_postpond_branch, $chq0_postponed_date, $chq0_postpond_paymentdate, $chq0_postpond_salesman, $chq0_postpond_date, $chq0_postpone, $chq0_postpond_code, $salesman_postpond_filter;

	$chq0_user_id=$chq0_group_id=$chq0_id=$chq0_postpond_id=$chq0_code=$salesman_filter=$chq0_salesman=array();

	$group =  $_GET['group'];
	$salesman =  $_GET['salesman'];
	$store=$_COOKIE['store'];

	if($group == 'all') {
		$groupfilter = "";
	} else {
		$groupfilter = "AND cu.associated_group = '".$group."'";
	}

	if($salesman== "all"){
		$userfilter='';
	}else{
		$userfilter="AND up.`id`='".$salesman."'";
	}

	if($_GET['components']=='supervisor') $storefilter="AND py.`store`='".$store."'"; else $storefilter='';

	include('config.php');

	// to get group name and group id to dropdown list
	$query="SELECT id,`name` FROM cust_group WHERE `sub_system`='$sub_system'";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$chq0_group_id[] = $row[0];
		$chq0_group_name[] = $row[1];
	}

	// to get active users
	$query = "SELECT id,username FROM userprofile WHERE status=0 ORDER BY username";
	$result = mysqli_query($conn2,$query);
	while($row = mysqli_fetch_array($result)){
		$chq0_user_id[] = $row[0];
		$chq0_username[] = $row[1];
	}

	// return chques
	$query="SELECT py.id,py.invoice_no,cu.name,py.amount,py.chque_no,ba.name,ba.bank_code,py.chque_branch,py.chque_date,py.payment_date,date(py.chque_return_date),up.username FROM payment py, bank ba, userprofile up, cust cu WHERE py.cust=cu.id AND py.salesman=up.id AND py.chque_bank=ba.id AND py.`status`=0 AND py.chque_return=1 AND py.chque_rtn_clear=0 AND py.`sub_system`='$sub_system' $groupfilter $userfilter $storefilter ORDER BY py.chque_return_date";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$chq0_id[]=$row[0];
		if($row[1]!=0) $chq0_invno[]=str_pad($row[1], 7, "0", STR_PAD_LEFT); else $chq0_invno[]='';
		$chq0_cuname[]=$row[2];
		$chq0_amount[]=$row[3];
		$chq0_no[]=$row[4];
		$chq0_bank[]=$row[5];
		$chq0_branch[]=$row[7];
		$chq0_date[]=$row[8];
		$chq0_paymentdate[]=$row[9];
		$chq0_returndate[]=$row[10];
		$chq0_code[]=$row[4].'-'.$row[6].'-'.str_pad($row[7], 3, "0", STR_PAD_LEFT);
		$chq0_salesman[]=$row[11];
	}

	// postponded cheques
	$query = "SELECT py.id,py.invoice_no,cu.name,py.amount,py.chque_no,ba.name,ba.bank_code,py.chque_branch,py.chque_date,py.payment_date,up.username,date(py.chque_date2),py.chque_postpone FROM payment py, bank ba, userprofile up, cust cu WHERE py.cust=cu.id AND py.salesman=up.id AND py.chque_bank=ba.id AND py.`status`=0 AND py.chque_postpone='1' AND py.`sub_system`='$sub_system' $groupfilter $userfilter $storefilter ORDER BY py.chque_postpone,py.chque_date,py.chque_date2 DESC";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$chq0_postpond_id[] = $row[0];
		if ($row[1] != 0) $chq0_postpond_invno[] = str_pad($row[1], 7, "0", STR_PAD_LEFT);
		else $chq0_postpond_invno[] = '';
		$chq0_postpond_cuname[] = $row[2];
		$chq0_postpond_amount[] = $row[3];
		$chq0_postpond_no[] = $row[4];
		$chq0_postpond_bank[] = $row[5];
		$chq0_postpond_branch[] = $row[7];
		$chq0_postponed_date[] = $row[8];
		$chq0_postpond_paymentdate[] = $row[9];
		$chq0_postpond_salesman[] = $row[10];
		$chq0_postpond_date[] = $row[11];
		if ($row[12] == 1) $chq0_postpone[] = 'Postponed';
		if ($row[12] == 2) $chq0_postpone[] = 'Postpone-Clear';
		$chq0_postpond_code[] = $row[4] . '-' . $row[6] . '-' . str_pad($row[7], 3, "0", STR_PAD_LEFT);
	}
}

//---------------------------------------------Quotation--------------------------------------------//
function getQuotationItems(){
	global $qi_id,$qo_itm_des,$qo_itm_qty,$qo_itm_uprice,$total,$item_filter;
	$pr_sr='';
	$total=0;
	$qo_itm_des=array();
	if(isset($_REQUEST['id'])){
	$quot_no=$_REQUEST['id'];
	include('config.php');
		$query="SELECT qi.id,itm.description,qi.qty,qi.unit_price,itm.pr_sr FROM quotation qi, inventory_items itm WHERE qi.item=itm.id AND qi.quot_no='$quot_no'";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$qi_id[]=$row[0];
			$qo_itm_des[]=$row[1];
			$qo_itm_qty[]=$row[2];
			$qo_itm_uprice[]=$row[3];
			$pr_sr=$row[4];
			$total+=$row[2]*$row[3];
		}

	if($pr_sr=='') $item_filter='all';
	if($pr_sr==1) $item_filter=1;
	if($pr_sr==2) $item_filter=2;
	if($pr_sr==3) $item_filter=3;
	}
}

function validateQuotNo(){
	include('config.php');
	$quote_no=$_REQUEST['id'];
	$salesman=$_GET['s'];
	$result = mysqli_query($conn2,"SELECT COUNT(id) as `count` FROM quotation_main WHERE created_by='$salesman' AND id='$quote_no'");
	$row = mysqli_fetch_assoc($result);
	$qo_exist=$row['count'];
	$result = mysqli_query($conn2,"SELECT COUNT(id) as `count` FROM quotation WHERE quot_no=$quote_no");
	$row = mysqli_fetch_assoc($result);
	$qo_count=$row['count'];

	if(($qo_exist==0)&&($qo_count>0))
		return true;
	else
		return false;
}

function newQuot($cust0){
	global $message,$quot_no,$salesman,$cust;
	$district=$_COOKIE['district'];
	$salesman=$_COOKIE['user_id'];
	$store=$_COOKIE['store'];
	$sub_system=$_COOKIE['sub_system'];
	$time_now=timeNow();
	$quot_no='';
	$cust=$cust0;
	$qo_type=1;

	include('config.php');
	$result = mysqli_query($conn,"SELECT up.mapped_inventory FROM userprofile up WHERE up.id='$salesman'");
	$row = mysqli_fetch_assoc($result);
	if($row['mapped_inventory']!=0) $mapped_inventory=$row['mapped_inventory']; else $mapped_inventory=$store;

	if($cust!=''){
			$query="SELECT MAX(id) FROM quotation_main";
			$result1=mysqli_query($conn,$query);
			while($row1=mysqli_fetch_array($result1)){	$quot_no=$row1[0];	}
		if($quot_no==''){
			$quot_no=1;
		}

		$result = mysqli_query($conn,"SELECT COUNT(id) as `count` FROM quotation WHERE quot_no=$quot_no");
		$row = mysqli_fetch_assoc($result);
		$qo_count=$row['count'];
		if($qo_count==0){
			$query2="DELETE FROM `quotation_main` WHERE `id` = '$quot_no'";
			$result2=mysqli_query($conn,$query2);
		}else{
			$quot_no=$quot_no+1;
		}

		$query="INSERT INTO `quotation_main` (`id`,`billed_district`,`cust`,`created_by`,`quo_timestamp`,`type`,`v`,`store`,`mapped_inventory`,`sub_system`,`status`) VALUES ('$quot_no','$district','$cust','$salesman','$time_now','$qo_type','1','$store','$mapped_inventory','$sub_system','1')";
		$result3=mysqli_query($conn,$query);
		if($result3){
			$message='';
			return true;
		}else{
			$message='Quotation could not be Created!';
			return false;
		}
	}else{
			$message='Invalid Customer!';
			return false;
	}
}

// update by nirmal 09_10_2023
function apendQuot(){
	global $message,$quot_no,$salesman,$cust;
	$quot_no=$_REQUEST['id'];
	$itemid=$_REQUEST['itemid'];
	$qty=$_REQUEST['qty1'];
	$price0=$_REQUEST['price'];
	$discount0=$_REQUEST['discount'];
	$salesman=$_REQUEST['salesman'];
	$comment=$_REQUEST['comment'];
	$cust=$_REQUEST['cust'];
	$discount_type=$_REQUEST['discount_type'];
	$time_now=timeNow();
	$bill_new_status='';
	$out=$qty_proceed=true;
	$message='Item was Added Successfully';
	$discount=calculateDiscount($cust,$itemid,$price0,$discount0,$discount_type);
	if($discount==='error'){
		$out=false;
		$message='Invalid Discount';
	}
	$price=round(($price0-$discount),2);

	include('config.php');
	$result = mysqli_query($conn,"SELECT COUNT(id) as `count` FROM quotation_main WHERE created_by='$salesman' AND id='$quot_no'");
	$row = mysqli_fetch_assoc($result);
	$qo_exist=$row['count'];
	if($qo_exist==0){
		newQuot($cust,'');
	}

	if($quot_no==0){
		$query="SELECT MAX(id) FROM quotation_main";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$quot_no=$row[0]+1;
		}
	}

	$result = mysqli_query($conn,"SELECT mapped_inventory,`status` FROM quotation_main WHERE id='$quot_no'");
	$row = mysqli_fetch_assoc($result);
	$store=$row['mapped_inventory'];
	$qo_status=$row['status'];

	$result = mysqli_query($conn,"SELECT `pr_sr`,`default_cost` FROM inventory_items WHERE id='$itemid'");
	$row = mysqli_fetch_assoc($result);
	$pr_sr=$row['pr_sr'];
	$d_cost=$row['default_cost'];

	$query="UPDATE `quotation_main` SET `type`='$pr_sr' WHERE `id`='$quot_no'";
	$result=mysqli_query($conn,$query);
	if(!$result){
		$out=false;
		$message='Error: Table Update Failed. Please Contact NegoIT';
	}
	if($pr_sr==1){
		$query="SELECT itq.c_price FROM inventory_qty itq WHERE itq.location='$store' AND itq.item='$itemid'";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		$cost=$row[0];
	}else if($pr_sr==2 || $pr_sr==3){
		$cost=$d_cost;
	}
	if($out){
		// Check if the item already exists in the quotation for this quotation number
		$existingItemQuery = "SELECT `qty` FROM `quotation` WHERE `quot_no`='$quot_no' AND `item`='$itemid'";
		$existingItemResult = mysqli_query($conn, $existingItemQuery);

		if (mysqli_num_rows($existingItemResult) > 0) {
			// Item already exists, increment the quantity
			$existingQty = mysqli_fetch_assoc($existingItemResult)['qty'];
			$newQty = $existingQty + $qty;

			// Update the quantity for the existing item
			$updateQtyQuery = "UPDATE `quotation` SET `qty`='$newQty' WHERE `quot_no`='$quot_no' AND `item`='$itemid'";
			$updateQtyResult = mysqli_query($conn, $updateQtyQuery);

			if (!$updateQtyResult) {
				$out = false;
				$message = 'Error: Failed to update quantity for the existing item';
			} else {
				$message = 'Quantity updated successfully';
			}
		}else{
			$query="INSERT INTO `quotation` (`quot_no`,`item`,`qty`,`unit_price`,`cost`,`discount`,`comment`) VALUES ('$quot_no','$itemid','$qty','$price','$cost','$discount','$comment')";
			$result=mysqli_query($conn,$query);
			if(!$result){
				$out=false;
				$message='Error: Item Could not be Added to Quotation';
			}
		}
	}
	return $out;
}

// update by nirmal 09_10_2023
function removeQuot(){
	global $message,$quot_no,$salesman,$cust;
	$quot_itm_id=$_GET['id'];
	if(isset($_COOKIE['manager']) || isset($_COOKIE['top_manager'])) $approver=true; else $approver=false;
	if($approver){
		$salesman=$_REQUEST['s'];
	}else{
		$salesman=$_COOKIE['user_id'];
	}

	$out=true;
	$message='Item removed successfully';

	include('config.php');
	$result = mysqli_query($conn,"SELECT qm.id,qm.created_by,qm.cust,qm.`status` FROM quotation_main qm, quotation qi WHERE qm.id=qi.quot_no AND qi.id='$quot_itm_id'");
	$row = mysqli_fetch_assoc($result);
	$quot_no=$row['id'];
	$qm_created_by=$row['created_by'];
	$cust=$row['cust'];
	$qm_status=$row['status'];

	if($approver){
		if($qm_status==2){
			$query="DELETE FROM quotation WHERE id='$quot_itm_id'";
			$result=mysqli_query($conn,$query);
			if(!$result){
				$out=false;
				$message='Error: Item Could not be Removed';
			}
		}
	}else{
		if(($salesman==$qm_created_by)&&($qm_status==1)){
			$query="DELETE FROM quotation WHERE id='$quot_itm_id'";
			$result=mysqli_query($conn,$query);
			if(!$result){
				$out=false;
				$message='Error: Item Could not be Removed';
			}
		}else{
			$out=false;
			$message='Unauthorize Request';
		}
	}
	return $out;
}

// update by nirmal 09_10_2023
function updateQuot(){
	global $message,$quot_no,$salesman,$cust;
	$quot_itm_id=$_GET['id'];
	$qty=$_GET['qty'];
	if(isset($_COOKIE['manager']) || isset($_COOKIE['top_manager'])) $approver=true; else $approver=false;
	if($approver){
		$salesman=$_REQUEST['s'];
	}else{
		$salesman=$_COOKIE['user_id'];
	}
	$out=true;
	$message='Item updated successfully';

	include('config.php');
	$result = mysqli_query($conn,"SELECT qm.id,qm.created_by,qm.cust,qm.`status` FROM quotation_main qm, quotation qi WHERE qm.id=qi.quot_no AND qi.id='$quot_itm_id'");
	$row = mysqli_fetch_assoc($result);
	$quot_no=$row['id'];
	$qm_created_by=$row['created_by'];
	$cust=$row['cust'];
	$qm_status=$row['status'];

	if($approver){
		// Update pending state qty (manager, top manager)
		if($qm_status==2){
			$query="UPDATE `quotation` SET `qty`='$qty' WHERE id='$quot_itm_id'";
			$result=mysqli_query($conn,$query);
			if(!$result){
				$out=false;
				$message='Error: Item could not be updated';
			}
		}
	}else{
		if(($salesman==$qm_created_by)&&($qm_status==1)){
			$query="UPDATE `quotation` SET `qty`='$qty' WHERE id='$quot_itm_id'";
			$result=mysqli_query($conn,$query);
			if(!$result){
				$out=false;
				$message='Error: Item could not be updated';
			}
		}else{
			$out=false;
			$message='Unauthorize Request';
		}
	}
	return $out;
}

function getDetaultTerms(){
	global $qo_warranty,$qo_validity,$qo_leadtime,$address1,$address2,$sys_comp_name,$st_comp_name;
	$qo_number=$_GET['id'];
	$store=$_COOKIE['store'];
	include('config.php');
	global $message,$quot_no,$salesman,$cust;
	$query="SELECT value FROM settings WHERE setting='qo_warranty'";
	$row=mysqli_fetch_row(mysqli_query($conn2,$query));
	$qo_warranty=$row[0];
	$query="SELECT value FROM settings WHERE setting='qo_validity'";
	$row=mysqli_fetch_row(mysqli_query($conn2,$query));
	$qo_validity=$row[0];
	$query="SELECT value FROM settings WHERE setting='qo_lead_time'";
	$row=mysqli_fetch_row(mysqli_query($conn2,$query));
	$qo_leadtime=$row[0];
	$query="SELECT value FROM settings WHERE setting='company_name'";
	$row=mysqli_fetch_row(mysqli_query($conn2,$query));
	$sys_comp_name=$row[0];
	$query="SELECT shop_name,address FROM stores WHERE id='$store'";
	$row=mysqli_fetch_row(mysqli_query($conn2,$query));
	$st_comp_name=$row[0];
	$st_address=$row[1];
	$address1=$st_comp_name.', '.$st_address;
	$address2=$sys_comp_name.', '.$st_address;
}

function getQOTerms(){
	global $tm_att,$tm_heading,$tm_warranty,$tm_validity,$tm_leadtime,$tm_terms1,$tm_terms2,$tm_note,$tm_address;
	$quot_no=$_GET['id'];
	include('config.php');
	$query="SELECT att,heading,warranty,validity,leadtime,terms1,terms2,note,address FROM quotation_main WHERE id='$quot_no'";
	$row=mysqli_fetch_row(mysqli_query($conn2,$query));
	$tm_att=$row[0];
	$tm_heading=$row[1];
	$tm_warranty=$row[2];
	$tm_validity=$row[3];
	$tm_leadtime=$row[4];
	$tm_terms1=$row[5];
	$tm_terms2=$row[6];
	$tm_note=$row[7];
	$tm_address=$row[8];
}

// update by nirmal 16_10_2023
function setQuotTerms(){
	global $message,$quot_no;
	$quot_no=$_GET['id'];
	$qo_heading=$_POST['heading'];
	$qo_attention=$_POST['att'];
	$qo_warranty=$_POST['warranty'];
	$qo_terms1=$_POST['terms1'];
	$qo_terms2=$_POST['terms2'];
	$qo_validity=$_POST['validity'];
	$qo_leadtime=$_POST['leadtime'];
	$qo_note=$_POST['note'];
	$qo_address=$_POST['from_add'];
	$message='The Terms & Conditions were Appended Successfully';
	$out=true;
	if(isset($_COOKIE['manager']) || isset($_COOKIE['top_manager'])) $approver=true; else $approver=false;
	include('config.php');
	$result = mysqli_query($conn,"SELECT `status` FROM quotation_main WHERE id='$quot_no'");
	$row = mysqli_fetch_assoc($result);
	$quo_status=$row['status'];
	if($approver || $quo_status==1){
		$query1="UPDATE `quotation_main` SET `heading`='$qo_heading',`att`='$qo_attention',`warranty`='$qo_warranty',`validity`='$qo_validity',`leadtime`='$qo_leadtime',`terms1`='$qo_terms1',`terms2`='$qo_terms2',`note`='$qo_note',`address`='$qo_address' WHERE id='$quot_no'";
		$result1=mysqli_query($conn,$query1);
		if(!$result1){
			$out=false;
			$message='Error: Terms & Conditions Could not be Appended';
		}
	}else{
		$out=false;
		$message="Error: Unauthorize Request";
	}
	return $out;
}

// update by nirmal 16_10_2023
function setQuotStatus($new_status){
	global $message,$quot_no,$salesman,$cust;
	$quot_no=$_GET['id'];
	$salesman=$_COOKIE['user_id'];
	$datetime=timeNow();
	$datenow=dateNow();
	$out=true;
	$message='Quotation was Finalized Successfully';

	include('config.php');
	$result = mysqli_query($conn,"SELECT qm.created_by,date(qm.quo_timestamp) as `qdate`,qm.cust,qm.`status` FROM quotation_main qm WHERE qm.id='$quot_no'");
	$row = mysqli_fetch_assoc($result);
	$qm_created_by=$row['created_by'];
	$qm_created_date=$row['qdate'];
	$cust=$row['cust'];
	$qm_status=$row['status'];

	if(isset($_COOKIE['manager']) || isset($_COOKIE['top_manager']) || isset($_COOKIE['report']))
		$approver=true; else $approver=false;
	if($salesman==$qm_created_by) $qo_owner=true; else $qo_owner=false;

	/* 1. Auto Approve 			*/
	if($qm_status==1 && $new_status==2 && $approver) $query1="UPDATE `quotation_main` SET `status`='3',`quo_timestamp`='$datetime',`approved_by`='$salesman',`approve_timestamp`='$datetime' WHERE id='$quot_no'";
	else
	/* 2. Submit for Approval 	*/
	if($qm_status==1 && $new_status==2 && $qo_owner) $query1="UPDATE `quotation_main` SET `status`='2',`quo_timestamp`='$datetime' WHERE id='$quot_no'"; else
	/* 2. Edited item Approval 	*/
	if($qm_status==2 && $new_status==2 && $approver) $query1="UPDATE `quotation_main` SET `status`='2',`quo_timestamp`='$datetime' WHERE id='$quot_no'"; else
	/* 3. Approve the Quot	 	*/
	if($qm_status==2 && $new_status==3 && $approver) $query1="UPDATE `quotation_main` SET `status`='3',`approved_by`='$salesman',`approve_timestamp`='$datetime' WHERE id='$quot_no'";
	else
	/* 4. Reject the Quot	 	*/
	if($qm_status==2 && $new_status==4 && $approver) $query1="UPDATE `quotation_main` SET `status`='4',`approved_by`='$salesman',`approve_timestamp`='$datetime' WHERE id='$quot_no'";
	else
	/* 5. Delete the Quot	 	*/
	if(($qm_status==1 || $qm_status==2 || $qm_status==3) && $new_status==0 && $qo_owner) $query1="UPDATE `quotation_main` SET `status`='0',`deleted_by`='$salesman',`deleted_timestamp`='$datetime' WHERE id='$quot_no'";
	else
	/* 6. Send to Cust	 		*/
	if($qm_status==3 && $new_status==5) $query1="UPDATE `quotation_main` SET `status`='5',`submited_by`='$salesman',`submited_timestamp`='$datetime' WHERE id='$quot_no'";
	else
	/* 7. Customer Accepted		*/
	if($qm_status==5 && $new_status==6) $query1="UPDATE `quotation_main` SET `status`='6',`custacc_by`='$salesman',`custacc_timestamp`='".$_GET['accdate']."',`cust_po`='".$_GET['custpo']."' WHERE id='$quot_no'";
	else
	/* 8. Customer Accepted		*/
	if($qm_status==5 && $new_status==7) $query1="UPDATE `quotation_main` SET `status`='7',`custacc_by`='$salesman',`custacc_timestamp`='$datenow',`rejected_com`='".$_GET['custreject']."' WHERE id='$quot_no'";
	else
	/* 9. Order Completed		*/
	if($qm_status==6 && $new_status==8) $query1="UPDATE `quotation_main` SET `status`='8',`completed_by`='$salesman',`completed_timestamp`='$datetime' WHERE id='$quot_no'";
	else{
		$out=false;
		$message='Error: Undefine Action';
	}
	if($out){
		$result1=mysqli_query($conn,$query1);
		if(!$result1){
			$out=false;
			$message='Error: Quotation Status Could not be Changed';
		}
	}
	return $out;
}

function qoStatus($type,$qm_status){
	switch ($qm_status){
		case 0 : $status='Deleted'; $color='red'; break;
		case 1 : $status='On Going'; $color='yellow'; break;
		case 2 : $status='Pending'; $color='yellow'; break;
		case 3 : $status='Approved'; $color='white'; break;
		case 4 : $status='Rejected'; $color='red'; break;
		case 5 : $status='Sent to Customer'; $color='white'; break;
		case 6 : $status='Customer Accepted'; $color='white'; break;
		case 7 : $status='Customer Rejected'; $color='red'; break;
		case 8 : $status='Completed'; $color='#00FF00'; break;
	}
	if($type=='name')	return $status;
	if($type=='color')	return $color;
}

function billType($type){
	switch ($type){
		case 1 : $type_name='Product'; break;
		case 2 : $type_name='Service'; break;
		case 3 : $type_name='Repair'; break;
		case 4 : $type_name='Product'; break;
		case 5 : $type_name='Service'; break;
	}
	return $type_name;
}

function qoPermission(){
	global $authorize,$approver,$qm_status,$status,$color,$bm_no,$qm_cust,$qm_created_by;
	$quot_no=$_GET['id'];
	$salesman=$_COOKIE['user_id'];
	$datenow=dateNow();
	$bm_no=array();
	include('config.php');
	$result = mysqli_query($conn2,"SELECT qm.created_by,date(qm.quo_timestamp) as `qdate`,qm.`cust`,qm.`status` FROM quotation_main qm WHERE qm.id='$quot_no'");
	$row = mysqli_fetch_assoc($result);
	$qm_created_by=$row['created_by'];
	$qm_created_date=$row['qdate'];
	$qm_cust=$row['cust'];
	$qm_status=$row['status'];
	$status=qoStatus('name',$qm_status);
	$color=qoStatus('color',$qm_status);
	if(($salesman==$qm_created_by)&&($datenow==$qm_created_date)) $authorize=true; else $authorize=false;
	if(isset($_COOKIE['manager']) || isset($_COOKIE['top_manager']) || isset($_COOKIE['report'])) $approver=true; else $approver=false;

	$query1="SELECT bm.invoice_no FROM bill_main bm, bill bi WHERE bm.invoice_no=bi.invoice_no AND bm.`status` NOT IN (0,7) AND bm.quotation_no='$quot_no' GROUP BY bm.invoice_no";
	$result1=mysqli_query($conn2,$query1);
	while($row1=mysqli_fetch_array($result1)){
		$bm_no[]=$row1[0];
	}
}

// update by nirmal 05_10_2023
function generateQuot($case){
	global $quot_no,$qo_date,$qo_time,$qo_salesman,$qo_cust_id,$qo_cust_name,$qo_cust_nic,$qo_cust_mob,$qo_cust_address,$cu_details,$qo_st_name,$qo_st_add,$qo_st_tel,$qo_att,$qo_warranty,$qo_validity,$qo_leadtime,$qo_terms,$qo_note,$qo_image,$qo_image_hei,$qi_item_des,$qi_item_code,$qi_qty,$qi_uprice,$qi_discount,$qi_comment,$qi_filled_qty,$total,$systemid,$web,$email,$qo_heading,$qo_v, $qi_invoiced_qty, $qi_tmp_invoiced_qty;
	$quot_no=$_REQUEST['id'];
	$total=0;
	$qi_item_des=$qi_item_code=$qi_qty=$qi_uprice=$qi_discount=$qi_item_id=$qi_invoiced_qty=array();

	if($case==1) include('../../../../config.php');
	else include('config.php');

	$result = mysqli_query($conn2,"SELECT `value` FROM settings WHERE setting='timezone'");
	$row = mysqli_fetch_assoc($result);
	$timezone=$row['value'];
	$print_time=date("Y-m-d H:i:s",time()+(60*60*$timezone));

	$query="SELECT date(qm.quo_timestamp),time(qm.quo_timestamp),up.username,cu.id,cu.name,cu.nic,cu.mobile,cu.shop_address,st.shop_name,st.address,st.tel,qm.att,qm.warranty,qm.validity,qm.leadtime,qm.terms1,qm.terms2,qm.note,qm.image,qm.image_height,qm.address,qm.heading,qm.v FROM quotation_main qm, cust cu, stores st, userprofile up WHERE qm.`cust`=cu.id AND qm.created_by=up.id AND qm.mapped_inventory=st.id AND qm.id='$quot_no'";

	$row=mysqli_fetch_row(mysqli_query($conn2,$query));
	$qo_date=$row[0];
	$qo_time=$row[1];
	$qo_salesman=$row[2];
	$qo_cust_id=$row[3];
	$qo_cust_name=$row[4];
	$qo_cust_nic=$row[5];
	$qo_cust_mob=$row[6];
	$qo_cust_address=$row[7];
	$qo_st_name=$row[8];
	$qo_st_add=$row[9];
	$qo_st_tel=$row[10];
	$qo_att=$row[11];
	$qo_warranty=$row[12];
	$qo_validity=$row[13];
	$qo_leadtime=$row[14];
	$qo_terms=$row[15].'<br />'.$row[16];
	$qo_note=$row[17];
	$qo_image=$row[18];
	$qo_image_hei=$row[19];
	$qo_add=$row[20];
	$qo_heading=$row[21];
	$qo_v=$row[22];
	$cu_details='NIC        : '.$qo_cust_nic.'&#13;Mobile  : '.$qo_cust_mob;

	$query="SELECT itm.description,itm.code,qi.qty,qi.unit_price,qi.discount,qi.`comment`,qi.item FROM quotation qi, inventory_items itm WHERE qi.item=itm.id AND qi.quot_no='$quot_no'";
	$result=mysqli_query($conn2,$query);

	while($row=mysqli_fetch_array($result)){
		$qi_item_des[]=$row[0];
		$qi_item_code[]=$row[1];
		$qi_qty[]=$row[2];
		$qi_uprice[]=$row[3];
		$qi_discount[]=$row[4];
		$qi_comment[]=$row[5];
		$qi_item=$row[6];
		$total+=$row[2]*$row[3];

		$query1="SELECT SUM(bi.qty) FROM bill_main bm, bill bi WHERE bm.invoice_no=bi.invoice_no AND bm.`status`='5' AND bm.quotation_no='$quot_no' AND bi.item='$qi_item'";
		$row1=mysqli_fetch_row(mysqli_query($conn2,$query1));
		$qi_filled_qty[]=$row1[0];

		$query2="SELECT SUM(bi.qty) FROM bill_main bm, bill bi WHERE bm.invoice_no=bi.invoice_no AND bm.`status` NOT IN (0,6,7) AND bm.quotation_no='$quot_no' AND bi.item='$qi_item'";
		$row2=mysqli_fetch_row(mysqli_query($conn2,$query2));
		$qi_invoiced_qty[]=$row2[0];

		$query3="SELECT SUM(bi.qty) FROM bill_main_tmp bm, bill_tmp bi WHERE bm.bm_no=bi.bm_no AND bm.`status` NOT IN (0,6,7) AND bm.quotation_no='$quot_no' AND bi.item='$qi_item'";
		$row3=mysqli_fetch_row(mysqli_query($conn2,$query3));
		$qi_tmp_invoiced_qty[]=$row3[0];
	}
	if($qo_add==2){
		$result = mysqli_query($conn2,"SELECT value FROM settings WHERE setting='company_name'");
		$row = mysqli_fetch_assoc($result);
		$qo_st_name=$row['value'];
	}

	$result = mysqli_query($conn2,"SELECT value FROM settings WHERE setting='systemid'");
	$row = mysqli_fetch_assoc($result);
	$systemid=$row['value'];

	$result = mysqli_query($conn2,"SELECT value FROM settings WHERE setting='web'");
	$row = mysqli_fetch_assoc($result);
	$web=$row['value'];

	$result = mysqli_query($conn2,"SELECT value FROM settings WHERE setting='email'");
	$row = mysqli_fetch_assoc($result);
	$email=$row['value'];
}

function checkItemAvailability($qo_number){
	$qty_avalability=true;
	include('config.php');
	$query="SELECT qo.qty,SUM(itq.qty),SUM(itn.qty) FROM inventory_qty itq, quotation qo LEFT JOIN inventory_new itn ON qo.item=itn.item WHERE qo.item=itq.item AND qo.quot_no='$qo_number' GROUP BY qo.item";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		if($row[0]>($row[1]+$row[2])) $qty_avalability=false;
	}
	return $qty_avalability;
}

// update by nirmal 12_10_2023
function getQuotList($sub_system){
	global $from_date,$to_date,$cust,$item0,$store,$salesman,$status,$qm_id,$qm_created_by,$qm_created_date,$qm_validity,$qm_store,$qm_cust,$qm_heading,$qm_amount,$qm_status,$qm_status_name,$qm_status_color,$qm_qty_avalability,$qm_rejected_com,$qm_custname,$qm_mobile,$qm_tel,$qm_inv_check;
	$compnents = $_REQUEST['components'];

	if(isset($_GET['from_date'])){
		if($_GET['from_date']!=''){
			$from_date=$_GET['from_date'];
		}else{
			$from_date=date("Y-m-d",time()-(60*60*24*30*3));
		}
	}else{
		$from_date=date("Y-m-d",time()-(60*60*24*30*3));
	}

	if(isset($_GET['to_date'])){
		if($_GET['to_date']!=''){
			$to_date=$_GET['to_date'];
		}else{
			$to_date=dateNow();
		}
	}else{
		$to_date=dateNow();
	}

	$qry_cust=$qry_item=$qry_store=$qry_salesman=$qry_status=$qry_subsys='';
	if($sub_system!='all') $qry_subsys="AND qm.`sub_system`='$sub_system'";
	$cust=$_GET['cust']; if($cust!='') $qry_cust="AND cu.name='$cust'";
	$item=$_GET['item']; if($item!='') $qry_item="AND qi.item='$item'";
	if($compnents == 'to'){
		$store=$_COOKIE['store'];
		$qry_store="AND st.id='$store'";
	}else{
		$store=$_GET['st']; if($store!='') $qry_store="AND st.id='$store'";
	}

	if($compnents == 'to'){
		$user_id = $_COOKIE['user_id'];
		$qry_salesman="AND qm.created_by='$user_id'";
	}else{
		$salesman=$_GET['sm']; if($salesman!='') $qry_salesman="AND qm.created_by='$salesman'";
	}

	$status=$_GET['status']; if($status!='all') $qry_status="AND qm.`status`='$status'";

	if(($store=='')&&($_GET['components']!='topmanager')){
		$store_list=groupStores();
		$qry_store="AND st.id IN (".$store_list.")";
 	}
	$qm_id=$qm_inv_check=array();

	include('config.php');
	if($item != ''){
		$query="SELECT description FROM inventory_items WHERE id='$item'";
		$row=mysqli_fetch_row(mysqli_query($conn2,$query));
		$item0=$row[0];
	}

	$query="SELECT qm.id,up.username,qm.quo_timestamp,qm.validity,st.name,cu.name,qm.heading,SUM(qi.qty * qi.unit_price),qm.`status`,qm.rejected_com,cu.cust_name,cu.mobile,cu.shop_tel FROM quotation_main qm, quotation qi, cust cu, stores st, userprofile up WHERE qm.id=qi.quot_no AND qm.`cust`=cu.id AND qm.created_by=up.id AND qm.store=st.id AND qm.`status`!=0 AND date(qm.quo_timestamp) BETWEEN '$from_date' AND '$to_date' $qry_subsys $qry_cust $qry_item $qry_store $qry_salesman $qry_status GROUP BY qm.id";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$qm_id[]=$row[0];
		$qm_created_by[]=$row[1];
		$qm_created_date[]=$row[2];
		$qm_validity[]=$row[3];
		$qm_store[]=$row[4];
		$qm_cust[]=$row[5];
		$qm_heading[]=$row[6];
		$qm_amount[]=$row[7];
		$qm_status[]=$row[8];
		$qm_rejected_com[]=$row[9];
		$qm_status_name[]=qoStatus('name',$row[8]);
		$qm_status_color[]=qoStatus('color',$row[8]);
		if($row[8]==6) $qm_qty_avalability[]=checkItemAvailability($row[0]); else $qm_qty_avalability[]=false;
		$qm_custname[]=$row[10];
		$qm_mobile[]=$row[11];
		$qm_tel[]=$row[12];
		if($status==8){ if($row[8]==8) $qm_inv_check[]=$row[0]; }
		else{ if($row[8]==6) $qm_inv_check[]=$row[0]; }
	}
}

function qoCompleteCheck(){
	$qo_no=$_GET['qo_no'];
	$count=$total_qo_item=$total_bi_item=$completed=0;
	include('config.php');
	$query="SELECT item,qty FROM quotation WHERE quot_no='$qo_no'";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$item=$row[0];
		$qo_qty=$row[1];
		$total_qo_item+=$row[1];

		$query1="SELECT SUM(bi.qty) FROM bill_main bm, bill bi WHERE bm.invoice_no=bi.invoice_no AND bm.`status`='5' AND bm.quotation_no='$qo_no' AND bi.item='$item'";
		$row1=mysqli_fetch_row(mysqli_query($conn2,$query1));
		if($qo_qty!=$row1[0]) $count++;
		$total_bi_item+=$row1[0];
	}

	if($total_qo_item!=0) $completed=round(($total_bi_item/$total_qo_item)*100);

	if($count==0)
		return 'yes,'.$completed;
	else
		return 'no,'.$completed;
}

function qoTemplate(){
	global $tm_template;
	$store=$_COOKIE['store'];
	include('config.php');
	$result = mysqli_query($conn2,"SELECT billing_template FROM stores WHERE id='$store'");
	$row = mysqli_fetch_assoc($result);
	$tm_template=$row['billing_template'];
}

function qoDetails(){
	global $main_district,$main_store,$main_refinv,$main_type,$main_sub_system,$main_created_date,$main_created_by,$main_approved_date,$main_approved_by,$main_submited_date,$main_submited_by,$main_deleted_date,$main_deleted_by,$main_custacc_date,$main_custacc_by,$main_reject_comm,$main_completed_date,$main_completed_by,$main_cust_po,$main_image,$main_image_hei,$qitem_list,$qqty_list;
	include('config.php');
	$quot_no=$_GET['id'];
	$query="SELECT id,username FROM userprofile";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$usercheck[$row[0]]=$row[1];
	}
	$usercheck['']='-';

		$query="SELECT di.name,st1.name,st2.name,qm.`type`,ss.name,qm.quo_timestamp,qm.created_by,qm.approve_timestamp,qm.approved_by,qm.submited_timestamp,qm.submited_by,qm.deleted_timestamp,qm.deleted_by,qm.custacc_timestamp,qm.custacc_by,qm.rejected_com,qm.completed_timestamp,qm.completed_by,qm.cust_po,qm.image,qm.image_height  FROM quotation_main qm, cust cu, stores st1, stores st2, district di, sub_system ss WHERE qm.`cust`=cu.id AND qm.mapped_inventory=st2.id AND qm.store=st1.id AND qm.billed_district=di.id AND qm.`sub_system`=ss.id AND qm.id='$quot_no'";
		$row=mysqli_fetch_row(mysqli_query($conn2,$query));
		$main_district=$row[0];
		$main_store=$row[1];
		$main_refinv=$row[2];
		$main_type=billType($row[3]);
		$main_sub_system=$row[4];
		$main_created_date=$row[5];
		$main_created_by=$usercheck[$row[6]];
		$main_approved_date=$row[7];
		$main_approved_by=$usercheck[$row[8]];
		$main_submited_date=$row[9];
		$main_submited_by=$usercheck[$row[10]];
		$main_deleted_date=$row[11];
		$main_deleted_by=$usercheck[$row[12]];
		$main_custacc_date=$row[13];
		$main_custacc_by=$usercheck[$row[14]];
		$main_reject_comm=$row[15];
		$main_completed_date=$row[16];
		$main_completed_by=$usercheck[$row[17]];
		$main_cust_po=$row[18];
		$main_image=$row[19];
		$main_image_hei=$row[20];

	include('config.php');
	$query="SELECT GROUP_CONCAT(item),GROUP_CONCAT(qty) FROM quotation WHERE quot_no='$quot_no'";
	$row=mysqli_fetch_row(mysqli_query($conn2,$query));
		$qitem_list=$row[0];
		$qqty_list=$row[1];
}

function getOnGoing(){
	global $qm_id,$qm_created_date,$qm_validity,$qm_store,$qm_cust,$qm_amount,$qm_created_by,$qm_custid;
	$user_id=$_COOKIE['user_id'];
	$qm_id=$qm_amount=array();
	include('config.php');
	$query="SELECT qm.id,qm.quo_timestamp,qm.validity,st.name,cu.name,SUM(qi.qty * qi.unit_price),qm.created_by,qm.`cust` FROM quotation_main qm, quotation qi, cust cu, stores st WHERE qm.id=qi.quot_no AND qm.`cust`=cu.id AND qm.store=st.id AND qm.`status`=1 AND qm.created_by='$user_id' GROUP BY qm.id";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$qm_id[]=$row[0];
		$qm_created_date[]=$row[1];
		$qm_validity[]=$row[2];
		$qm_store[]=$row[3];
		$qm_cust[]=$row[4];
		$qm_amount[]=$row[5];
		$qm_created_by[]=$row[6];
		$qm_custid[]=$row[7];
	}
}

function searchQuot($id){
	$id=ltrim($id, '0');
	include('config.php');
	$query="SELECT count(id) FROM quotation_main WHERE id='$id'";
	$row=mysqli_fetch_row(mysqli_query($conn2,$query));

	if($row[0]==1) return true;
	else return false;
}

function imageresize($systemid,$image1){
	$out=false;
	$dest_image1= 'images/customerdata/'.$systemid.'/quotation/'.$image1; // make sure the directory is writeable
	$image1='images/customerdata/'.$systemid.'/quotation/uploads/'.$image1;
	$image_info = getimagesize($image1);
	$oriwidth=$image_info[0];
	$oriheight=$image_info[1];
	$width = 1000;
	$ratio = $width / $oriwidth;
	$height = $oriheight * $ratio;
	$new_image = imagecreatetruecolor($width, $height);

		   $image_type = $image_info[2];
		   if($image_type == IMAGETYPE_JPEG ) {
		   	  ini_set('memory_limit', '-1');
		      $org_img= imagecreatefromjpeg($image1);
		   } elseif(
		   		$image_type == IMAGETYPE_GIF ) {
		   		  $org_img= imagecreatefromgif($image1);
		   		   } elseif( $image_type == IMAGETYPE_PNG )
		   		   {  $org_img= imagecreatefrompng($image1); }

	if(imagecopyresampled($new_image,$org_img, 0, 0, 0, 0, $width, $height, $oriwidth, $oriheight)){
	$new_image=imagerotate($new_image, 0, 0);
	if(imagejpeg($new_image,$dest_image1,90))
	if(imagedestroy($new_image)) $out=true;
	}
	if($out) return true; else return false;
}

function qoAddImage(){
	global $message;
	$id=$_GET['id'];
	$systemid=inf_systemid(1);
	$image1=$file_upload=$upload1=0;
	$out=$result1=false;
	$msg="Error: Image Upload Failed.";
	include('config.php');
	$result = mysqli_query($conn,"SELECT `status` FROM quotation_main WHERE id='$id'");
	$row = mysqli_fetch_assoc($result);
	$quo_status=$row['status'];

	if($quo_status==2 || $quo_status==3){
		$target_dir = "images/customerdata/$systemid/quotation/uploads/";
		if(isset($_FILES["fileToUpload1"]))
		if($_FILES["fileToUpload1"]["name"]!=''){
			print("ok 1");
			$upload1=1;
			$target_file = $target_dir . basename($_FILES["fileToUpload1"]["name"]);
			$tmp_name=str_pad($id, 10, "0", STR_PAD_LEFT).'.jpg';
			$destination_file = $target_dir . $tmp_name;
			$uploadOk = 1;
			$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
			print("ok 4");
			// Check if image file is a actual image or fake image
			if(isset($_POST["submit"])) {
			    $check = getimagesize($_FILES["fileToUpload1"]["tmp_name"]);
				print("ok 3");
			    if($check !== false) {
			        //echo "File is an image - " . $check["mime"] . ".";
			        $uploadOk = 1;
					print("ok 2");
			    } else {
			        $msg="File is not an image.";
			        $uploadOk = 0;
					print("err 2");
			    }
			}else{
				print("Validation failed");
			}
			// Check if file already exists
			if (file_exists($destination_file)) {
			    $msg="Sorry, file already exists.";
			    $uploadOk = 0;
			}
			// Check file size
			if ($_FILES["fileToUpload1"]["size"] > 10000000) {
			    echo "Sorry, your file is too large.";
			    $uploadOk = 0;
			}
			// Allow certain file formats
			if($imageFileType != "jpg" && $imageFileType != "jpeg" ) {
			    $msg="Sorry, only JPG, JPEG files are allowed.";
			    $uploadOk = 0;
			}
			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 0) {
			    $file_upload++;
			// if everything is ok, try to upload file
			} else {
			    if (move_uploaded_file($_FILES["fileToUpload1"]["tmp_name"], $destination_file)) {
					if(imageresize($systemid,$tmp_name)){
				    	$image1=1;
						unlink($destination_file);
					}else{
						unlink($destination_file);
					}
			     //   echo "The file ". basename( $_FILES["fileToUpload1"]["name"]). " has been uploaded.";
			    } else {
			    	$file_upload++;
			        $msg="Sorry, there was an error uploading your file.";
			    }
			}
		}

		if(($file_upload==0)&&($upload1==1)&&($image1==1)){
			$query="UPDATE `quotation_main` SET `image`='$image1' WHERE id='$id'";
			$result=mysqli_query($conn,$query);
			if($result){
				$out=true;
			}
		}


		if($out){
			$message="Image was Appended to Quotation Successfully";
			return true;
		}else{
			unlink('images/customerdata/'.$systemid.'/quotation/'.str_pad($id, 10, "0", STR_PAD_LEFT).'.jpg');
			$message=$msg;
			return false;
		}
	}else{
		$message="Error: Unauthorize Request";
		return false;
	}
}

function qoDeleteImage(){
	global $message;
	$id=$_GET['id'];
	$systemid=inf_systemid(1);
	$result1=false;
	$msg="Error: Image Could Not be Removed";
	include('config.php');
	$result = mysqli_query($conn,"SELECT `status` FROM quotation_main WHERE id='$id'");
	$row = mysqli_fetch_assoc($result);
	$quo_status=$row['status'];

	if($quo_status==2 || $quo_status==3){
		$query1="UPDATE `quotation_main` SET `image`='0' WHERE id='$id'";
		$result1=mysqli_query($conn,$query1);
	}else{
		$msg="Error: Unauthorize Request";
	}

	if($result1){
		unlink('images/customerdata/'.$systemid.'/quotation/'.str_pad($id, 10, "0", STR_PAD_LEFT).'.jpg');
		$message="Image was Removed Successfully";
		return true;
	}else{
		$message=$msg;
		return false;
	}
}

function qoImgHeight(){
	global $message;
	$id=$_GET['id'];
	$height=$_POST['height'];
	$result1=false;
	$msg="Error: Image Height Could Not be Updated";
	include('config.php');
	$result = mysqli_query($conn,"SELECT `status` FROM quotation_main WHERE id='$id'");
	$row = mysqli_fetch_assoc($result);
	$quo_status=$row['status'];

	if($quo_status==2 || $quo_status==3){
		$query1="UPDATE `quotation_main` SET `image_height`='$height' WHERE id='$id'";
		$result1=mysqli_query($conn,$query1);
	}else{
		$msg="Error: Unauthorize Request";
	}
	if($result1){
		$message="Image Height was Updated Successfully";
		return true;
	}else{
		$message=$msg;
		return false;
	}
}

function qoRevise(){
	global $message,$quot_no;
	$quot_no=$_GET['id'];
	$result1=false;
	$msg="Error: Quotation Could Not be Modified";
	include('config.php');
	$result = mysqli_query($conn,"SELECT `status`,`v` FROM quotation_main WHERE id='$quot_no'");
	$row = mysqli_fetch_assoc($result);
	$quo_status=$row['status'];
	$quo_v=$row['v']+1;

	if($quo_status==2 || $quo_status==3 || $quo_status==5){
		$query1="UPDATE `quotation_main` SET `v`='$quo_v',`status`='1' WHERE id='$quot_no'";
		$result1=mysqli_query($conn,$query1);
	}else{
		$msg="Error: Unauthorize Request";
	}
	if($result1){
		$message="Image Height was Updated Successfully";
		return true;
	}else{
		$message=$msg;
		return false;
	}
}

function qoAddNote(){
	global $message,$quot_no;
	$quot_no=$_GET['id'];
	$user_id=$_COOKIE['user_id'];
	$textnote= preg_replace("/[^A-Za-z0-9+-,. ]/",'',$_POST['textnote']);
	$time_now=timeNow();
	$result1=false;
	$msg="Error: Note Could Not be Added";
	include('config.php');

	$query1="INSERT INTO `quotation_note` (`qo_no`,`timestamp`,`user`,`note`) VALUES ('$quot_no','$time_now','$user_id','$textnote')";
	$result1=mysqli_query($conn,$query1);

	if($result1){
		$message="Note was Added Successfully";
		return true;
	}else{
		$message=$msg;
		return false;
	}
}

function qoUpdateNote(){
	global $message,$quot_no;
	$quot_no=$_GET['id'];
	print $_POST['comid'];
	$comid=$_POST['comid'];
	$user_id=$_COOKIE['user_id'];
	$textnote= preg_replace("/[^A-Za-z0-9+-,. ]/",'',$_POST['textnote']);
	$time_now=timeNow();
	$result1=false;
	$msg="Error: Note Could Not be Updated";
	include('config.php');

	$query1="UPDATE `quotation_note` SET `timestamp`='$time_now',`user`='$user_id',`note`='$textnote' WHERE id='$comid'";
	$result1=mysqli_query($conn,$query1);

	if($result1){
		$message="Note was Updated Successfully";
		return true;
	}else{
		$message=$msg;
		return false;
	}
}

function qoNote(){
	global $qn_noid,$qn_timestamp,$qn_user,$qn_note,$edit_note,$edit_note0;
	$quot_no=$_GET['id'];
	$user_id=$_COOKIE['user_id'];
	$date_now=dateNow();
	$qn_noid=array();
	$edit_note0='';
	if(isset($_GET['comid'])) $comid=$_GET['comid']; else $comid=0;

	include('config.php');
	$query="SELECT qn.id,qn.timestamp,up.username,qn.note,up.id  FROM quotation_note qn, userprofile up WHERE qn.`user`=up.id AND qn.qo_no='$quot_no'";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		if($row[0]!=$comid){
			$qn_noid[]=$row[0];
			$qn_timestamp[]=substr($row[1],0,16);
			$qn_user[]=ucfirst($row[2]);
			$qn_note[]=$row[3];
			if((substr($row[1],0,10)==$date_now)&&($row[4]==$user_id)){ $edit_note[]=true; }else{ $edit_note[]=false; }
		}else{
			if((substr($row[1],0,10)==$date_now)&&($row[4]==$user_id)) $edit_note0=$row[3];
		}
	}
}

function getReportNote($sub_system){
	global $from_date,$to_date,$cust,$qo_no0,$store,$user,$status,$qn_qo_id,$qn_time,$qn_user,$qn_note,$qn_store,$qn_cust,$qn_heading,$qn_status_name,$qn_status_color,$qm_custname,$qm_mobile,$qm_tel;
	if(isset($_GET['from_date'])){ if($_GET['from_date']!=''){  $from_date=$_GET['from_date']; }else{ $from_date=date("Y-m-d",time()-(60*60*24*30*3)); } }else{ $from_date=date("Y-m-d",time()-(60*60*24*30*3)); }
	if(isset($_GET['to_date'])){ if($_GET['to_date']!=''){  $to_date=$_GET['to_date']; }else{ $to_date=dateNow(); } }else{ $to_date=dateNow(); }
	$qry_cust=$qry_store=$qry_qono=$qry_user=$qry_status=$qry_subsys='';
	if($sub_system!='all') $qry_subsys="AND qm.`sub_system`='$sub_system'";
	$cust=$_GET['cust']; if($cust!='') $qry_cust="AND cu.name='$cust'";
	$qo_no0=$_GET['qo_no']; if($qo_no0!='') $qry_qono="AND qn.qo_no='$qo_no0'";
	$store=$_GET['st']; if($store!='') $qry_store="AND st.id='$store'";
	$user=$_GET['sm']; if($user!='') $qry_user="AND qn.`user`='$user'";
	$status=$_GET['status']; if($status!='all') $qry_status="AND qm.`status`='$status'";

	if(($store=='')&&($_GET['components']!='topmanager')){
		$store_list=groupStores();
		$qry_store="AND st.id IN (".$store_list.")";
 	}
	$qn_qo_id=array();

	include('config.php');
	$query="SELECT qm.id,qn.`timestamp`,up.username,qn.note,st.name,cu.name,qm.heading,qm.`status`,cu.cust_name,cu.mobile,cu.shop_tel FROM quotation_main qm, quotation_note qn, cust cu, stores st, userprofile up WHERE qm.id=qn.qo_no AND qm.`cust`=cu.id AND qn.`user`=up.id AND qm.store=st.id AND qm.`status`!=0 AND date(qn.timestamp) BETWEEN '$from_date' AND '$to_date' $qry_subsys $qry_cust $qry_qono $qry_store $qry_user $qry_status ORDER BY qn.`timestamp`";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$qn_qo_id[]=$row[0];
		$qn_time[]=substr($row[1],0,16);
		$qn_user[]=$row[2];
		$qn_note[]=$row[3];
		$qn_store[]=$row[4];
		$qn_cust[]=$row[5];
		$qn_heading[]=$row[6];
		$qn_status_name[]=qoStatus('name',$row[7]);
		$qn_status_color[]=qoStatus('color',$row[7]);
		$qm_custname[]=$row[8];
		$qm_mobile[]=$row[9];
		$qm_tel[]=$row[10];
	}
}

function getSalesBySalesman($store){
	global $lock_req,$type_req,$graph_user,$graph_total,$date1,$date2,$invoice_no,$invoice_Total,$billed_district,
	$billed_by,$billed_cust,$billed_time,$billed_store,$payment_cash,$payment_chque,$payment_card, $payment_bank,$payment_id,
	$payment_amount,$payment_type,$payment_salesman,$payment_cust,$payment_time,$payment_store,$bi_discount,
	$rtn_no,$rtn_time,$rtn_pay,$rtn_salesman,$rtn_store,$rtn_cust,$wa_no,$wa_time,$wa_pay,$wa_salesman,$wa_entity,$wa_store;
	$invoice_no=$payment_id=$rtn_no=$wa_no=$graph_user=$graph_total=array();
	$group_qry=$lock_qry=$type_qry=$type_req='';
	$store_list=groupStores();
	$user_id=$_COOKIE['user_id'];

	if($store=='all') $storesearch="AND st.id IN (".$store_list.")"; else $storesearch="AND st.id='".$store."'";
	if($_REQUEST['salesman']=='all') $salesmansearch=''; else $salesmansearch="AND up.`id`='".$_REQUEST['salesman']."'";
	if($_REQUEST['processby']=='all') $processbysearch=''; else $processbysearch="AND bm.`packed_by`='".$_REQUEST['processby']."'";
	if(isset($_REQUEST['type'])){
		$type_req=$_REQUEST['type'];
		if($type_req==1) $type_qry="AND bm.`type` IN (1,4)";
		if($type_req==2) $type_qry="AND bm.`type` IN (2,5)";
		if($type_req==4) $type_qry="AND bm.`type`='3'";
	}
	if(isset($_REQUEST['date1'])){
		$date1=$_REQUEST['date1'];
	}else{
		$date1=dateNow();
	}
	$date_qry1="AND date(bm.billed_timestamp)='$date1'";
	$date_qry2="AND date(py.payment_date)='$date1'";
	$date_qry3="AND date(rm.return_date)='$date1'";
	$date_qry4="AND date(wa.cust_pay_date)='$date1'";
	$date_qry5="AND date(wa.suplier_pay_date)='$date1'";

	if((isset($_REQUEST['date1']))&&(isset($_REQUEST['date2']))){
		$date1=$_REQUEST['date1'];
		$date2=$_REQUEST['date2'];
		if($date1!='' && $date2!=''){
		$date_qry1="AND date(bm.billed_timestamp) BETWEEN  '$date1' AND '$date2'";
		$date_qry2="AND date(py.payment_date) BETWEEN  '$date1' AND '$date2'";
		$date_qry3="AND date(rm.return_date) BETWEEN  '$date1' AND '$date2'";
		$date_qry4="AND date(wa.cust_pay_date) BETWEEN  '$date1' AND '$date2'";
		$date_qry5="AND date(wa.suplier_pay_date) BETWEEN  '$date1' AND '$date2'";
		}
	}

	if(isset($_REQUEST['lock'])){
		$lock_req=$_REQUEST['lock'];
		if($lock_req=='all'){
			$lock_qry='';
		}else{
			if($lock_req==0) $lock_qry="AND bm.`lock` IN (0,2)"; else $lock_qry="AND bm.`lock`=$lock_req";
		}
	}else{
		$lock_req=1;
		$lock_qry="AND bm.`lock`=$lock_req";
	}

	include('config.php');
	if(isset($_REQUEST['group'])){
		$group=$_REQUEST['group'];
		if(($group!='')&&($group!='all')){
			$group_qry="AND cu.associated_group='$group'";
		}else{
			if($_GET['components']=='marketing'){
				$gp_id='';
				$query="SELECT cg.id FROM cust_group cg, user_to_group ug WHERE cg.id=ug.`group` AND ug.`user`='$user_id'";
				$result=mysqli_query($conn2,$query);
				while($row=mysqli_fetch_array($result)){
					$gp_id.=$row[0].',';
				}
				$gp_id=rtrim($gp_id,',');
				$group_qry="AND cu.associated_group IN ($gp_id)";
			}
		}
	}

	if($type_req!=3&&$type_req!=5){
		$query="SELECT up.username,SUM(bm.`invoice_+total` + bm.`invoice_-total`) FROM bill_main bm, userprofile up, district di, cust cu, stores st WHERE bm.store=st.id AND bm.`cust`=cu.id AND bm.billed_by=up.id AND bm.billed_district=di.id AND bm.`status` NOT IN (0,7) $lock_qry $type_qry $date_qry1 $group_qry $storesearch $salesmansearch $processbysearch GROUP BY bm.billed_by ORDER BY bm.billed_by";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$graph_user[]=$row[0];
			$graph_total[]=$row[1];
		}
	}


	if($type_req==3 || $type_req==''){
		$query3="SELECT up.username,SUM(rt.extra_pay) FROM return_main rm, `return` rt, stores st, userprofile up, cust cu WHERE rm.invoice_no=rt.invoice_no AND rm.store=st.id AND rm.return_by=up.id AND rm.`cust`=cu.id AND rt.extra_pay!=0 AND rm.`status`=2 $date_qry3 $storesearch $group_qry $salesmansearch GROUP BY rm.return_by ORDER BY rm.return_by";
		$result3=mysqli_query($conn2,$query3);
		while($row3=mysqli_fetch_array($result3)){
			$key=array_search($row3[0],$graph_user);
			if($key>-1){
				$graph_total[$key]=$graph_total[$key]+$row3[1];
			}else{
				$graph_user[]=$row3[0];
				$graph_total[]=$row3[1];
			}
		}
	}
}
?>