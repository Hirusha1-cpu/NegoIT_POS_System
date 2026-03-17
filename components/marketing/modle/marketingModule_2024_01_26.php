<?php
function getGPSLocations(){
	global $map_api;
	include('config.php');
	$result = mysqli_query($conn2,"SELECT value FROM settings WHERE setting='api_map'");
	$row = mysqli_fetch_assoc($result);
	$map_api=$row['value'];
}
// updated by nirmal 21_02_2022
function getSubSystem(){
	global $subsys_id,$subsys_name;
	$subsys_id=array();
	include('config.php');
	$component=$_REQUEST['components'];
	if($component == 'billing' || $component == 'bill2'){
		$user_id=$_COOKIE['user_id'];
		$query="SELECT ss.`id`,ss.`name` FROM sub_system ss, userprofile up WHERE up.`sub_system` = ss.`id` AND  ss.`status`='1' AND up.`id` = '$user_id'";
	}else{
		$query="SELECT id,`name` FROM sub_system WHERE `status`='1'";
	}
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$subsys_id[]=$row[0];
		$subsys_name[]=$row[1];
	}
}

// updated by nirmal 22_02_2022
function getActiveTown(){
	global $town_id,$town_name,$town_cust_count;
	if(!isset($_REQUEST['town'])){
		global $map_cust,$map_x,$map_y,$x_center,$y_center;
		$x_center=$y_center=$count=0;
		$map_cust=$map_x=$map_y=array();
	}
	$qry_sub_sys=$qry_st=$qry_sm=$qry_gp='';
	$sub_system=$set_store=$set_salesman=$set_group='all';
	$town_id=$town_cust_count=array();
	$component=$_REQUEST['components'];
	if(isset($_GET['sub_sys'])) $sub_system=$_GET['sub_sys'];
	if(isset($_GET['st']))	 $set_store=$_GET['st'];
	if(isset($_GET['sm']))	 $set_salesman=$_GET['sm'];
	if(isset($_GET['gp']))	 $set_group=$_GET['gp'];
	$user_id=$_COOKIE['user_id'];
	include('config.php');

	if($sub_system!='all') $qry_sub_sys="AND cu.`sub_system`='$sub_system'";
	if($set_store!='all') $qry_st="AND cu.`associated_store`='$set_store'";
	if($set_salesman!='all') $qry_sm="AND cu.`associated_salesman`='$set_salesman'";
	if($set_group!='all') $qry_gp="AND cu.`associated_group`='$set_group'";

	if($component == 'billing' || $component == 'bill2'){
		$query="SELECT `sub_system`,`store` FROM userprofile WHERE id = '$user_id'";
		$result=mysqli_query($conn,$query);
		$row=mysqli_fetch_row($result);
		$sub_system=$row[0];
		$set_store=$row[1];

		$qry_sub_sys="AND cu.`sub_system`='$sub_system'";
		$qry_st="AND cu.`associated_store`='$set_store'";
	}

	$query="SELECT tw.`id`,tw.`name`,COUNT(cu.`id`) FROM town tw, cust cu, user_to_group ug WHERE cu.`associated_town`=tw.`id` AND cu.`associated_group`=ug.`group` AND ug.`user`='$user_id' AND cu.`status`='1' $qry_sub_sys $qry_st $qry_sm $qry_gp GROUP BY tw.`id` ORDER BY tw.`name`";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$town_id[]=$row[0];
		$town_name[]=$row[1];
		$town_cust_count[]=$row[2];
	}

	if(!isset($_REQUEST['town'])){
		$query="SELECT cu.`name`,cu.`gps_x`,cu.`gps_y` FROM cust cu, user_to_group ug WHERE cu.`associated_group`=ug.`group` AND ug.`user`='$user_id' AND cu.`status`='1' $qry_sub_sys $qry_st $qry_sm $qry_gp ORDER BY cu.`name`";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){

			if(($row[1]!=0)&&($row[2]!=0)){
				$map_cust[]=ucfirst($row[0]);
				$map_x[]=$row[1];
				$map_y[]=$row[2];
				$x_center+=$row[1];
				$y_center+=$row[2];
				$count++;
			}
		}
		if($count>0){
			$x_center=$x_center/$count;
			$y_center=$y_center/$count;
		}
		getGPSLocations();
	}
}

// updated by nirmal 21_02_2022
function getStore($sub_system){
	global $st_id,$st_name;
	if(isset($_GET['sub_sys'])) $sub_system=$_GET['sub_sys'];
	if($sub_system=='all') $sub_system_qry=''; else $sub_system_qry="AND `sub_system`='$sub_system'";
	$st_id=array();
	$component=$_REQUEST['components'];

	include('config.php');
	if($component == 'billing' || $component == 'bill2'){
		$user_id=$_COOKIE['user_id'];
		$query="SELECT st.`id`,st.`name` FROM stores st, userprofile up WHERE up.`store` = st.`id` AND  st.`status`='1' AND up.`id` = '$user_id' ORDER BY st.`name`";
	}else{
		$query="SELECT `id`,`name` FROM stores WHERE `status`=1 $sub_system_qry ORDER BY name";
	}
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$st_id[]=$row[0];
		$st_name[]=$row[1];
	}
}

function getGroup($sub_system){
	global $gp_id,$gp_name;
	if(isset($_GET['sub_sys'])) $sub_system=$_GET['sub_sys'];
	if($sub_system=='all') $sub_system_qry=''; else $sub_system_qry="AND cg.`sub_system`='$sub_system'";
	$user_id=$_COOKIE['user_id'];
	$gp_id=array();

	include('config.php');
	$query="SELECT cg.id,cg.`name` FROM cust_group cg, user_to_group ug WHERE cg.`id`=ug.`group` AND ug.`user`='$user_id' $sub_system_qry ORDER BY cg.`name`";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$gp_id[]=$row[0];
		$gp_name[]=$row[1];
	}
}

// updated by nirmal 21_02_2022
function setActiveSalesman($sub_system){
	global $sm_id,$sm_name;
	if(isset($_GET['sub_sys'])) $sub_system=$_GET['sub_sys'];
	if($sub_system=='all') $sub_system_qry=''; else $sub_system_qry="AND cu.`sub_system`='$sub_system'";
	$sm_id=array();

	include('config.php');
	$query="SELECT up.id,up.username FROM userprofile up, cust cu WHERE cu.associated_salesman=up.id AND up.`status`=0 AND cu.`status`='1' $sub_system_qry GROUP BY up.id ORDER BY up.username";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$sm_id[]=$row[0];
		$sm_name[]=ucfirst($row[1]);
	}
}

function validateTownPermission($town_name){
	$user_id=$_COOKIE['user_id'];

	include('config.php');
	$query="SELECT COUNT(cu.id) FROM town tw, cust cu, user_to_group ug WHERE cu.associated_town=tw.id AND cu.associated_group=ug.`group` AND ug.`user`='$user_id' AND cu.`status`='1' AND tw.`name`='$town_name'";
	$row=mysqli_fetch_row(mysqli_query($conn2,$query));
	if($row[0]>0)
		return true;
	else
		return false;
}

// updated by nirmal 21_02_2022
function getCustomerList(){
	global $set_sub_sys,$set_store,$set_salesman,$set_group,$set_town,$cu_id,$cu_name,$cu_mobile,$cu_shop_tel,$cu_credit_limit;
	if(isset($_REQUEST['town'])){
		global $map_cust,$map_x,$map_y,$x_center,$y_center,$map_api;
		$x_center=$y_center=$count=0;
		$map_cust=array();
	}
	$set_sub_sys=$set_store=$set_salesman=$set_group='all';
	$qry_subsys=$qry_st=$qry_sm=$qry_gp=$qry_bill_gp_tbl=$qry_bill_gp='';
	$cu_id=array();
	$component=$_REQUEST['components'];
	$user_id=$_COOKIE['user_id'];

	include('config.php');

	if(isset($_GET['sub_sys']))	 $set_sub_sys=$_GET['sub_sys'];
	if(isset($_GET['st']))	 $set_store=$_GET['st'];
	if(isset($_GET['sm']))	 $set_salesman=$_GET['sm'];
	if(isset($_GET['gp']))	 $set_group=$_GET['gp'];
	if(isset($_GET['town']))	 $set_town=$_GET['town'];
	if($set_sub_sys!='all') $qry_subsys="AND cu.`sub_system`='$set_sub_sys'";
	if($set_store!='all') $qry_st="AND cu.`associated_store`='$set_store'";
	if($set_salesman!='all') $qry_sm="AND cu.`associated_salesman`='$set_salesman'";
	if($set_group!='all') $qry_gp="AND cu.`associated_group`='$set_group'";
	$qry_bill_gp_tbl = ", user_to_group ug";
	$qry_bill_gp = "AND cu.`associated_group`=ug.`group` AND ug.`user`='$user_id'";

	if($component == 'billing' || $component == 'bill2'){
		$query="SELECT `sub_system`,`store` FROM userprofile WHERE id = '$user_id'";
		$result=mysqli_query($conn,$query);
		$row=mysqli_fetch_row($result);
		$sub_system=$row[0];
		$set_store=$row[1];

		$qry_sub_sys="AND cu.`sub_system`='$sub_system'";
		$qry_st="AND cu.`associated_store`='$set_store'";
	}

	if(validateTownPermission($set_town)){
		$query="SELECT cu.id,cu.`name`,cu.mobile,cu.shop_tel,cu.credit_limit,cu.gps_x,cu.gps_y FROM cust cu, town tw $qry_bill_gp_tbl WHERE cu.`associated_town`=tw.id AND cu.`status`='1' AND tw.`name`='$set_town' $qry_subsys $qry_st $qry_sm $qry_gp $qry_bill_gp ORDER BY cu.`name`";
		$result=mysqli_query($conn2,$query);

		while($row=mysqli_fetch_array($result)){
			$cu_id[]=$row[0];
			$cu_name[]=ucfirst($row[1]);
			$cu_mobile[]=$row[2];
			$cu_shop_tel[]=$row[3];
			$cu_credit_limit[]=$row[4];
			if(($row[5]!=0)&&($row[6]!=0)){
				$map_cust[]=ucfirst($row[1]);
				$map_x[]=$row[5];
				$map_y[]=$row[6];
				$x_center+=$row[5];
				$y_center+=$row[6];
				$count++;
			}
		}
		if($count>0){
			$x_center=$x_center/$count;
			$y_center=$y_center/$count;
		}
		getGPSLocations();
	}
}

function getCustomerList2(){
	global $from_date,$to_date,$set_town,$order,$cu_id,$cu_name,$cu_mobile,$cu_shop_tel,$cu_credit_limit,$bm_total;
	$cu_id=array();

	if(isset($_GET['from_date'])) $from_date=$_GET['from_date']; else $from_date=date("Y-m-d",time()-365*24*60*60);
	if(isset($_GET['to_date'])) $to_date=$_GET['to_date']; else $to_date=dateNow();
	if(isset($_GET['order'])) $order=$_GET['order']; else $order='DESC';
	if(isset($_GET['town'])){
		$set_town=$_GET['town'];

		include('config.php');
		$query="SELECT cu.id,cu.`name`,cu.mobile,cu.shop_tel,cu.credit_limit,SUM(bm.`invoice_+total` + bm.`invoice_-total`) AS `total` FROM cust cu, town tw, bill_main bm WHERE cu.`associated_town`=tw.id AND bm.`cust`=cu.id AND cu.`status`='1' AND bm.`status`!='0' AND bm.`lock`='1' AND tw.`name`='$set_town' AND (date(bm.billed_timestamp) BETWEEN '$from_date' AND '$to_date') GROUP BY cu.id ORDER BY `total` $order";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$cu_id[]=$row[0];
			$cu_name[]=ucfirst($row[1]);
			$cu_mobile[]=$row[2];
			$cu_shop_tel[]=$row[3];
			$cu_credit_limit[]=$row[4];
			$bm_total[]=$row[5];
		}
	}
}

// updated by nirmal 25_12_2023
function getCustMore(){
	$id=$_GET['id'];
	$today=dateNow();
	$from_date=date("Y-m-d",time()-(60*60*24*365));
	$out='';
	include('config.php');
	$is_custom_invoice_no_active = isCustomInvoiceNoActive(1);

	$query="SELECT cu.`name`,cu.mobile,cu.shop_tel,st.`name`,up.username,gp.`name`,ss.`name`,cu.cust_name,cu.shop_address,cu.email,cu.credit_limit,master_cust,cu.gps_x,cu.gps_y FROM cust cu, stores st, userprofile up, sub_system ss, cust_group gp WHERE cu.associated_store=st.id AND cu.associated_salesman=up.id AND cu.associated_group=gp.id AND cu.`sub_system`=ss.id AND cu.id='$id'";
	$row=mysqli_fetch_row(mysqli_query($conn2,$query));
	$co_name=$row[0];
	$co_mobile=$row[1];
	$co_tel=$row[2];
	$co_shop=$row[3];
	$co_salesman=ucfirst($row[4]);
	$co_group=$row[5];
	$co_subsys=$row[6];
	$co_custname=$row[7];
	$co_address=$row[8];
	$co_email=$row[9];
	$co_crlimit=$row[10];
	$co_masterco=$row[11];
	if(($row[12]==0)||($row[13]==0)) $co_gps='no'; else $co_gps=$row[12].','.$row[13];


	$co_link='';

	$query="SELECT credit_limit FROM cust WHERE id='$id'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$cust_cr_limit=$row[0];
	$query="SELECT SUM(bi.qty*bi.unit_price) FROM bill bi, bill_main bm WHERE bm.invoice_no=bi.invoice_no AND bm.`status`!=0 AND bm.exclude=0 AND bm.`type`!=3 AND bm.`cust`='$id' ";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$totalbill0=$row[0];
	$query="SELECT SUM(amount) FROM payment WHERE `status`=0 AND `cust`='$id' AND payment_type IN (1,3)";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$payment_cash=$row[0];
	$query="SELECT SUM(amount) FROM payment WHERE `status`=0 AND `cust`='$id' AND payment_type=2 AND chque_return=0 AND chque_date <= '$today'";
	$row=mysqli_fetch_row(mysqli_query($conn,$query));
	$payment_chque=$row[0];
	$co_crbalance=$cust_cr_limit-$totalbill0+$payment_cash+$payment_chque;

	$query="SELECT SUM(bm.`invoice_+total`+bm.`invoice_-total`) FROM bill_main bm WHERE bm.exclude=0 AND bm.`status` NOT IN (0,7) AND bm.`lock`=1 AND date(bm.billed_timestamp)<='$today' AND bm.`cust`='$id'";
	$row=mysqli_fetch_row(mysqli_query($conn2,$query));
	$bm_total=$row[0];
	$query="SELECT SUM(py.amount) FROM payment py WHERE py.status=0 AND py.chque_return=0 AND date(py.payment_date)<='$today' AND py.`cust`='$id'";
	$row=mysqli_fetch_row(mysqli_query($conn2,$query));
	$py_total=$row[0];
	$co_outstanding=$bm_total-$py_total;

	$query="SELECT SUM(bm.`invoice_+total`+bm.`invoice_-total`) FROM bill_main bm WHERE bm.exclude=0 AND bm.`status` NOT IN (0,7) AND bm.`lock`=1 AND (date(bm.billed_timestamp) BETWEEN '$from_date' AND '$today' ) AND bm.`cust`='$id'";
	$row=mysqli_fetch_row(mysqli_query($conn2,$query));
	$last1y_sale=$row[0];

	if($is_custom_invoice_no_active){
		$query="SELECT date(billed_timestamp),`invoice_+total`+`invoice_-total` FROM bill_main WHERE `cust`='$id' ORDER BY CAST(invoice_no AS SIGNED) DESC LIMIT 1";
	}else{
		$query="SELECT date(billed_timestamp),`invoice_+total`+`invoice_-total` FROM bill_main WHERE `cust`='$id' ORDER BY invoice_no DESC LIMIT 1";
	}
	$row=mysqli_fetch_row(mysqli_query($conn2,$query));
	$co_inv_date=$row[0];
	$co_inv_amo=$row[1];

	$query="SELECT date(payment_date),amount FROM payment WHERE `cust`='$id' ORDER BY id DESC LIMIT 1";
	$row=mysqli_fetch_row(mysqli_query($conn2,$query));
	$co_py_date=$row[0];
	$co_py_amo=$row[1];

	$result = mysqli_query($conn,"SELECT COUNT(id) AS `count` FROM payment WHERE payment_type=2 AND cust='$id' AND `status`=0 AND chque_return=1");
	$row = mysqli_fetch_assoc($result);
	$chq2_retuned=$row['count'];
	$result = mysqli_query($conn,"SELECT COUNT(id) AS `count` FROM payment WHERE payment_type=2 AND cust='$id' AND `status`=0 AND chque_postpone=1");
	$row = mysqli_fetch_assoc($result);
	$chq2_postpone=$row['count'];
	$result = mysqli_query($conn,"SELECT COUNT(id) AS `count` FROM payment WHERE payment_type=2 AND cust='$id' AND `status`=0 AND chque_clear=1");
	$row = mysqli_fetch_assoc($result);
	$chq2_banked=$row['count'];

	$out=$co_name.'|'.$co_mobile.'|'.$co_tel.'|'.$co_shop.'|'.$co_salesman.'|'.$co_group.'|'.$co_subsys.'|'.$co_masterco.'|'.$co_custname.'|'.$co_address.'|'.$co_email.'|'.$co_crlimit.'|'.$co_gps.'|'.$co_crbalance.'|'.$co_outstanding.'|'.$last1y_sale.'|'.$co_inv_date.'|'.$co_inv_amo.'|'.$co_py_date.'|'.$co_py_amo.'|'.$chq2_retuned.'|'.$chq2_postpone.'|'.$chq2_banked.'|'.$from_date.'|'.$today;
	return $out;

}

function outstandingSMS($sub_system){
	$cust=$_GET['cust'];
	$outstanding=$_GET['outstanding'];
	$timenow=timeNow();
	$date_now=substr($timenow,0,10);
	$sms_sent=1;
	$msg='Error';
	$sms_data=json_decode(sms_credential($sub_system));
	$sms_user=$sms_data->{"user"};
	$sms_pass=$sms_data->{"pass"};
	$sms_balance=$sms_data->{"balance"};
	$sms_device=$sms_data->{"device"};
	include('config.php');
	$query="SELECT cu.mobile,cu.`sms`,st.shop_name_sms FROM cust cu, stores st WHERE cu.associated_store=st.id AND cu.id='$cust'";
	$row=mysqli_fetch_row(mysqli_query($conn2,$query));
	$mobile=$row[0];
	$sms_allow=$row[1];
	$inf_company=$row[2];

	if(($sms_allow==1)&&($sms_balance>0)&&($_SERVER['SERVER_NAME']==inf_url_primary())&&(strpos($mobile,"7")==1)){
		$message=str_replace(" ","+",$inf_company).'-NLC-Total+Outstanding+=++'.number_format($outstanding).'+-NLC--NLC-Thank+you!';
		$sms_balance--;
		//---------------------------------------------------------------//
		$text = urlencode($message);
		if($sms_device==""){
			$url = "http://www.textit.biz/sendmsg/?id=$sms_user&pw=$sms_pass&eco=Y&to=$mobile&text=$text";
			$ret = file($url);
			$res= explode(":",$ret[0]);
			if (trim($res[0])=="OK") $mailstatus=true; else $mailstatus=false;
		}else{
			$url = "http://mqtt.negoit.info/sms_gw.php?dev=$sms_device&ref1=bill&ref2=$invoice_no&u=$sms_user&p=$sms_pass&to=$mobile&text=$text";
			setcookie("sms_balance",$sms_balance, time()+3600*10);
			file($url);
			$mailstatus=false;
		}
		//----------------------------------------------------------------//
		$query="SELECT MAX(id) FROM sms";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		$max_id=$row[0];
		$query="SELECT MIN(id) FROM sms";
		$row=mysqli_fetch_row(mysqli_query($conn,$query));
		$min_id=$row[0];
		$next_id=$max_id+1;
		$query="UPDATE `sms` SET `id`='$next_id',`timestamp`='$timenow',`case`='1',`ref`='0',`text`='$message' WHERE id='$min_id'";
		mysqli_query($conn,$query);

		if($mailstatus){
			if(set_sms_balance($sub_system,$sms_balance))	$msg='SMS Sent';
		}
	}
	return $msg;
}

function getItemId($sub_system){
	if (isset($_POST['val'])) {
		$val = $_POST['val'];
		$jasonArray = array();
		include('config.php');
		$query = "SELECT id FROM inventory_items WHERE `description` LIKE '%$val%' AND sub_system='$sub_system' LIMIT 1";
		$row = mysqli_fetch_row(mysqli_query($conn2, $query));
		$jasonArray["item_id"] = $row[0];
		$myJSON = json_encode($jasonArray);
		return $myJSON;
	}
}

function getPendingReturnItems($sub_system){
	if (isset($_POST['cust_id'])) {
		$cust = $_POST['cust_id'];
		$data_list='';
		include('config.php');
		$query = "SELECT rm.invoice_no,date(rm.return_date),rt.return_item,itm1.`description`,rt.replace_item,itm2.`description` FROM return_main rm, `return` rt, inventory_items itm1, inventory_items itm2 WHERE rm.invoice_no=rt.invoice_no AND rt.return_item=itm1.id AND rt.replace_item=itm2.id AND rm.`cust`='$cust' AND rm.`sub_system`='$sub_system' AND rt.odr_packed=0 AND rm.`status`!='0'";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$data_list.=$row[0].',';
			$data_list.=$row[1].',';
			$data_list.=$row[2].',';
			$data_list.=$row[3].',';
			$data_list.=$row[4].',';
			$data_list.=$row[5].'|';
		}
		$data_list=rtrim($data_list,'|');
		return $data_list;
	}
}

function getItemHistory($sub_system){
	if (isset($_POST['cust_id']) & isset($_POST['item_id'])) {
		$cust_id = $_POST['cust_id'];
		$item_id = $_POST['item_id'];
		$data_list='';
		include('config.php');
		$query = "SELECT bm.invoice_no,date(bm.billed_timestamp),bi.cost,bi.unit_price FROM bill_main bm, bill bi WHERE bm.invoice_no=bi.invoice_no AND bm.`cust`='$cust_id' AND bi.item='$item_id' AND bm.`sub_system`='$sub_system' AND bm.`status`!='0' ORDER BY bm.billed_timestamp DESC";
		$result=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($result)){
			$data_list.=$row[0].',';
			$data_list.=$row[1].',';
			$data_list.=$row[2].',';
			$data_list.=$row[3].'|';
		}
		$data_list=rtrim($data_list,'|');
		return $data_list;
	}
}
?>