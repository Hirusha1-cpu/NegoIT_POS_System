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

function getGTNStatus($status_id){
	switch ($status_id){
		case "0" :
		$gtn_status_temp='Pending';
		break;
		case "1" :
		$gtn_status_temp='Accepted';
		break;
		case "2" :
		$gtn_status_temp='Rejected';
		break;
		case "3" :
		$gtn_status_temp='Canceled';
		break;
		case "4" :
		$gtn_status_temp='Transfering';
		break;
	}
	return $gtn_status_temp;
}

function ackDeleted1(){
	$id=$_GET['id'];
	if($_GET['components']=='report'||$_COOKIE['user']=='nadeeshani') $out=true; else $out=false;
	if($out){
		include('config.php');
		$query="UPDATE bill_main SET delete_ack='0' WHERE invoice_no='$id'";
		$result=mysqli_query($conn,$query);
		if($result) $out=true; else $out=false;
	}
	if($out)
		return 'done';
	else
		return 'error';
}

function ackDeleted2(){
	$id=$_GET['id'];
	if($_GET['components']=='report'||$_COOKIE['user']=='nadeeshani') $out=true; else $out=false;
	if($out){
		include('config.php');
		$query="UPDATE payment SET delete_ack='0' WHERE id='$id'";
		$result=mysqli_query($conn,$query);
		if($result) $out=true; else $out=false;
	}
	if($out)
		return 'done';
	else
		return 'error';
}

// added by nirmal 21_11_1
function ackDeleted3(){
	$invoice_no=$_GET['invoice_no'];
	if($_GET['components']=='report'||$_COOKIE['user']=='nadeeshani') $out=true; else $out=false;
	if($out){
		include('config.php');
		$query="UPDATE return_main SET delete_ack='0' WHERE invoice_no='$invoice_no'";
		$result=mysqli_query($conn,$query);
		if($result) $out=true; else $out=false;
	}
	if($out)
		return 'done';
	else
		return 'error';
}

function getCust(){
	global $cust_id,$cust_name;
	include('config.php');
	$query="SELECT id,name FROM cust WHERE `status`='1'";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$cust_id[]=$row[0];
		$cust_name[]=$row[1];
	}
}

function getUsers(){
	global $user_id,$user_name;
	include('config.php');
		$query="SELECT id,username FROM userprofile WHERE `status`=0 ORDER BY username";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$user_id[]=$row[0];
			$user_name[]=ucfirst($row[1]);
	}
}

function getCustGroups(){
	global $gp_id,$gp_name;
	include('config.php');
		$query="SELECT id,name FROM cust_group ORDER BY name";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$gp_id[]=$row[0];
			$gp_name[]=$row[1];
	}
}

// update by nirmal change store variable name to stores
function salesReport(){
	global $invoice_no,$time,$invoice_total,$invoice_profit,$date1,$date2,$item_req,$type_req,$salesman_req,$store_req,$cust_req,$invoice_type,$color,$total_discount,$invo_salesman,$cust,$stores,$graph_user,$graph_total,$bi_discount,$rtn_no,$rtn_time,$rtn_pay,$rtn_salesman,$rtn_store,$rtn_cust,$group,$invoice_cost,$cat_id,$cat_name,$cat_select,$sub_system0,$hp;
	$sub_system0=$group=$store_req=$cust_req=$cust_qry=$stores_qry=$salesman_req=$salesman_qry=$item_req=$item_qry=$type_req=$pr_sr=$type_qry=$hp_qry=$group_qry=$sub_system_qry=$cat_list='';
	$cat_id=$invoice_no=$invoice_total=$invoice_profit=$invo_salesman=$invoice_type=$cust=$stores=$graph_user=$graph_total=$rtn_pay=$rtn_no=array();
	$total=0;
	$tags_qry=$usr='';
	$k=0;
	$j= -1;
	include('config.php');
	$store_qry = '';

	if(isset($_REQUEST['filter_item'])){
		$item_req=$_REQUEST['filter_item'];
		if($item_req!='') $item_qry="itm.description='$item_req' AND";
	}
	if(isset($_REQUEST['filter_salesman'])){
		$salesman_req=$_REQUEST['filter_salesman'];
		if($salesman_req!='') $salesman_qry="up.username='$salesman_req' AND";
	}
	if(isset($_REQUEST['filter_store'])){
		$store_req=$_REQUEST['filter_store'];
		if($store_req!='') $store_qry="st.name='$store_req' AND";
	}
	if(isset($_REQUEST['filter_cust'])){ $cust_req=$_REQUEST['filter_cust']; if($cust_req!='') $cust_qry="cu.name='$cust_req' AND"; }
	if(isset($_REQUEST['filter_type'])){
		$type_req=$_REQUEST['filter_type'];
		if($type_req=='Product') $pr_sr=1;
		if($type_req=='Service') $pr_sr=2;
		if($type_req=='Return Invoice') $pr_sr=3;
		if($pr_sr==1 || $pr_sr==2) $type_qry="itm.pr_sr='$pr_sr' AND";
	}
	if(isset($_REQUEST['date1'])){
		$date1=$_REQUEST['date1'];
		$date_qry="date(bm.billed_timestamp)='$date1'";
		$date_qry2="date(rm.return_date)='$date1'";
	}else{
		$date1=date("Y-m-d",time());
		$date_qry="date(bm.billed_timestamp)='$date1'";
		$date_qry2="date(rm.return_date)='$date1'";
	}
	if((isset($_REQUEST['date1']))&&(isset($_REQUEST['date2']))){
		$date1=$_REQUEST['date1'];
		$date2=$_REQUEST['date2'];
		$date_qry="date(bm.billed_timestamp) BETWEEN  '$date1' AND '$date2' ";
		$date_qry2="date(rm.return_date) BETWEEN  '$date1' AND '$date2'";
	}
	if(isset($_REQUEST['hp'])){
		$hp=$_REQUEST['hp'];
		if($hp=='yes') $hp_qry="bm.recovery_agent!='' AND";
		if($hp=='no') $hp_qry="bm.recovery_agent is NULL AND";
	}
	if(isset($_REQUEST['group'])){
		$group=$_REQUEST['group'];
		if($group!='') $group_qry="gp.id='".$group."' AND";
	}
	if(isset($_REQUEST['sub_system0'])){
		$sub_system0=$_REQUEST['sub_system0'];
		if($sub_system0!='') $sub_system_qry="ss.id='".$sub_system0."' AND";
	}
	if(isset($_REQUEST['tags'])){
		$tag_selection=$_REQUEST['tag_selection'];
		$tags_req=$_REQUEST['tags'];
		if($tags_req!=''){
			$tags_list="tn.tag IN ('".str_replace("|","','",$tags_req)."')";
			$tags_list2='';
			$tmp_arr=array();
			$tmp_arr=explode("|",$tags_req);
			$match_count=sizeof($tmp_arr);
			if($tag_selection==1) $query="SELECT ta.item FROM tag_name tn, tag_assignment ta WHERE tn.id=ta.tag AND $tags_list";
			if($tag_selection==2) $query="SELECT ta.item,count(ta.tag) FROM tag_assignment ta, tag_name tn WHERE ta.tag=tn.id AND $tags_list GROUP BY ta.item HAVING count(ta.tag)>=$match_count";
			$result=mysqli_query($conn2,$query);
			while($row=mysqli_fetch_array($result)){
				$tags_list2.=$row[0].',';
			}
			if($tags_list2!=''){
				$tags_list2=substr($tags_list2, 0, -1);
				$tags_qry='itm.id IN ('.$tags_list2.') AND';
			}else{
				$tags_qry="itm.id IN ('') AND";
			}
		}
	}

	$query="SELECT id,name FROM item_category";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$cat_id[]=$row[0];
		$cat_name[]=$row[1];
		$cat_select[$row[0]]='checked="checked"';
		if(isset($_POST['cat'.$row[0]])){ if($_POST['cat'.$row[0]]=='yes') $cat_list=$cat_list.','.$row[0]; else $cat_select[$row[0]]=''; }else{ $cat_list=$cat_list.','.$row[0]; }
	}
	if($cat_list=='') $cat_list="''";
	$cat_list=ltrim($cat_list,",");
	if($pr_sr==1 || $pr_sr==2 || $pr_sr==''){
		$query="SELECT bi.invoice_no,date(bm.billed_timestamp),SUM(bi.qty*bi.unit_price),SUM(bi.qty*bi.cost),bm.`type`,SUM(bi.qty*bi.discount),up.username,cu.name,st.name,SUM(bi.discount) FROM bill_main bm, bill bi, inventory_items itm, userprofile up, cust cu, stores st, cust_group gp, sub_system ss WHERE $item_qry $type_qry $salesman_qry $store_qry $cust_qry $group_qry $hp_qry $sub_system_qry $tags_qry bm.invoice_no=bi.invoice_no AND bm.store=st.id AND itm.id=bi.item AND cu.id=bm.`cust` AND cu.`associated_group`=gp.id AND bm.billed_by=up.id AND bm.sub_system=ss.id AND bm.`status` NOT IN (0,7) AND bm.`lock`=1 AND bm.exclude=0 AND itm.category IN ($cat_list) AND $date_qry GROUP BY bi.invoice_no";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$invoice_no[]=$row[0];
			$time[]=$row[1];
			$invoice_total[]=$row[2];
			$invoice_cost[]=$row[3];
			$invoice_profit[]=$row[2]-$row[3];
			if($row[4]==1 || $row[4]==4){ $invoice_type[]='Product'; $color[$k]='black'; }else
			if($row[4]==2 || $row[4]==5){ $invoice_type[]='Service'; $color[$k]='green'; }else
			if($row[4]==3){ $invoice_type[]='Repair'; $color[$k]='maroon'; }
			if($row[5]>0){ $color[$k]='red; font-weight:900'; }
			$total_discount[]=$row[5];
			$invo_salesman[]=$row[6];
			$cust[]=$row[7];
			$stores[]=$row[8];
			$bi_discount[]=$row[9];
			$k++;
			if(!in_array($row[6], $graph_user) ){
				$graph_user[]=$row[6];
			}
			$key=array_search($row[6],$graph_user);
			if(isset($graph_total[$key])) $total=$graph_total[$key]; else $total=0;
			$graph_total[$key]=$total+$row[2];
		}
	}

	if(($pr_sr==3)||($pr_sr=='')){
		$query3="SELECT rm.invoice_no,time(rm.return_date),SUM(rt.extra_pay),up.username,st.name,cu.name FROM return_main rm, `return` rt, stores st, userprofile up, cust cu, sub_system ss WHERE rm.invoice_no=rt.invoice_no AND rm.store=st.id AND rm.return_by=up.id AND rm.`cust`=cu.id AND rm.sub_system=ss.id AND $date_qry2 AND $store_qry $salesman_qry $cust_qry $sub_system_qry rt.extra_pay!=0 GROUP BY rm.invoice_no ORDER BY st.name";
		$result3=mysqli_query($conn2,$query3);
		while($row3=mysqli_fetch_array($result3)){
			$rtn_no[]=$row3[0];
			$rtn_time[]=$row3[1];
			$rtn_pay[]=$row3[2];
			$rtn_salesman[]=$row3[3];
			$rtn_store[]=$row3[4];
			$rtn_cust[]=$row3[5];
			$invoice_type[]='Return Invoice';
			$key=array_search($row3[3],$graph_user);
			if($key>-1){
				$graph_total[$key]=$graph_total[$key]+$row3[2];
			}else{
				$graph_user[]=$row3[3];
				$graph_total[]=$row3[2];
			}
		}
	}
}

// updated by nirmal 21_11_1
function deletedList(){
	global $bi_inv_no,$bi_date,$bi_inv_total,$bi_inv_profit,$bi_deleted_by,$bi_asso_pay,$bm_delete_ack,$py_inv_no,$py_date,$py_amount,$py_deleted_by,$py_delete_ack,$rt_inv_no,$rt_date,$rt_deleted_by,$rt_delete_ack;
	$total=0;
	$bi_inv_no=$py_inv_no=$rt_inv_no=$rt_date=$rt_deleted_by=array();
	include('config.php');

	$query="SELECT bi.invoice_no,date(bi.date),SUM(bi.qty*bi.unit_price),SUM((bi.qty*bi.unit_price)-(bi.qty*bi.cost)),up.username,bm.delete_ack FROM bill_main bm, bill bi, userprofile up WHERE bm.invoice_no=bi.invoice_no AND bm.deleted_by=up.id AND bm.`status`=0 GROUP BY bi.invoice_no ORDER BY bm.`deleted_timestamp` DESC LIMIT 100";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$tmp_inv_no=$row[0];
		$bi_inv_no[]=$row[0];
		$bi_date[]=$row[1];
		$bi_inv_total[]=$row[2];
		$bi_inv_profit[]=$row[3];
		$bi_deleted_by[]=$row[4];
		$bm_delete_ack[]=$row[5];

		$result1 = mysqli_query($conn2,"SELECT id FROM payment WHERE bill_pay=1 AND invoice_no=$tmp_inv_no");
		$row1 = mysqli_fetch_assoc($result1);
		$bi_asso_pay0=$row1['id'];
		if($bi_asso_pay0=='') $bi_asso_pay[]=''; else $bi_asso_pay[]=$bi_asso_pay0;
	}

	$query="SELECT py.id,date(py.payment_date),py.amount,up.username,py.delete_ack FROM payment py, userprofile up WHERE py.deleted_by=up.id AND py.`status`=1 ORDER BY py.deleted_date DESC LIMIT 100";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$py_inv_no[]=$row[0];
		$py_date[]=$row[1];
		$py_amount[]=$row[2];
		$py_deleted_by[]=$row[3];
		$py_delete_ack[]=$row[4];
	}

	// for return bills
	$query="SELECT rm.`invoice_no`, DATE(rm.`return_date`), up.`username`,rm.`delete_ack` FROM `return_main` rm, `userprofile` up WHERE up.`id`=rm.`deleted_by` AND rm.`status`=0 ORDER BY rm.`deleted_date` DESC LIMIT 100";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$rt_inv_no[]=$row[0];
		$rt_date[]=$row[1];
		$rt_deleted_by[]=$row[2];
		$rt_delete_ack[]=$row[3];
	}
}

function salesman(){
	global $salesman_id,$salesman_name,$invoice_count,$commision,$date;
	if(isset($_REQUEST['date'])){
	$date=$_REQUEST['date'];
	}else{
	$date=date("Y-m",time());
	}

	$total=0;
	$salesman_id=array();
	include('config.php');
		$query="SELECT bm.billed_by,COUNT(DISTINCT (bi.invoice_no)),SUM((bi.unit_price*bi.qty*inv.commision)/100),up.username FROM bill_main bm, bill bi, inventory_items inv, userprofile up WHERE bm.invoice_no=bi.invoice_no AND bm.billed_by=up.id AND bi.item=inv.id AND bm.`status` NOT IN (0,7) AND bm.`lock`=1 AND bm.billed_timestamp LIKE '$date%' GROUP BY bm.billed_by";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$salesman_id[]=$row[0];
			$invoice_count[]=$row[1];
			$commision[]=$row[2];
			$salesman_name[]=$row[3];
	}
}

function salesmanInvoices(){
	global $invoice_no,$time,$invoice_total,$invoice_profit,$salesman_name;
	$month=$_REQUEST['month'];
	$id=$_REQUEST['id'];

	$total=0;
	include('config.php');
		$query="SELECT bm.invoice_no,date(bm.billed_timestamp),SUM(bi.qty*bi.unit_price),SUM((bi.qty*bi.unit_price)-(bi.qty*bi.cost)) FROM bill_main bm, bill bi WHERE bm.invoice_no=bi.invoice_no AND bm.`billed_by`='$id' AND bm.`billed_timestamp` LIKE '$month%' GROUP BY bm.invoice_no";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$invoice_no[]=$row[0];
			$time[]=$row[1];
			$invoice_total[]=$row[2];
			$invoice_profit[]=$row[3];
	}
		$query="SELECT username FROM userprofile WHERE id='$id'";
		$result=mysqli_query($conn2,$query);
		while($row1=mysqli_fetch_array($result)){
			$salesman_name=$row1[0];
	}
}

// added by nirmal 07_08_2023
function getProfitReport(){
	global $invoice_no, $date, $invoice_total, $invoice_profit, $invoice_type, $cust, $invoice_cost, $payments, $hp, $from_date,
	$to_date, $advance_payment, $hp, $group, $sub_system0;

	$invoice_no=$invoice_total=$invoice_profit=$invoice_cost=$date=$invoice_type=$cust=$payments=$advance_payment=array();
	$today = $from_date = $to_date = $hp = $group = $hp_qry = $sub_system0 = $date_qry = $sub_system_qry = $group_qry = '';
	include('config.php');

	// date filer
	$today = date("Y-m-d", time());
	if (isset($_POST['from_date'])) $from_date = $_POST['from_date'];
	else $from_date = date("Y-m-d", time() - (60 * 60 * 24 * 30));
	if (isset($_POST['to_date'])) $to_date = $_POST['to_date'];
	else $to_date = $today;
	$date_qry=" AND DATE(bm.billed_timestamp) BETWEEN  '$from_date' AND '$to_date'";

	// HP filter
	if(isset($_POST['hp'])){
		$hp=$_POST['hp'];
		if($hp=='yes') $hp_qry=" AND bm.recovery_agent!=''";
		if($hp=='no') $hp_qry=" AND bm.recovery_agent is NULL";
	}

	// Group filer
	if(isset($_POST['group'])){
		$group=$_POST['group'];
		if($group!='') $group_qry=" AND gp.id='".$group."'";
	}

	// Subsystem filter
	if(isset($_POST['sub_system0'])){
		$sub_system0=$_POST['sub_system0'];
		if($sub_system0 !='') $sub_system_qry=" AND ss.id='".$sub_system0."'";
	}

	$query = "SELECT bi.invoice_no, DATE(bm.billed_timestamp), SUM(bi.qty * bi.unit_price), SUM(bi.qty * bi.cost), cu.name, bm.`recovery_agent` FROM bill_main bm INNER JOIN bill bi ON bm.invoice_no = bi.invoice_no INNER JOIN inventory_items itm ON itm.id = bi.item INNER JOIN cust cu ON cu.id = bm.`cust` INNER JOIN cust_group gp ON cu.`associated_group` = gp.id INNER JOIN sub_system ss ON bm.sub_system = ss.id WHERE bm.`status` NOT IN (0, 7) AND bm.`lock` = 1 AND bm.exclude = 0 $date_qry $hp_qry $group_qry $sub_system_qry GROUP BY bi.invoice_no ORDER BY bm.`invoice_no`";

	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$query1 = "SELECT `amount`, `bill_pay` FROM payment WHERE `invoice_no` = '$row[0]' AND `status` = 0 AND `chque_return` = 0";
		$result1=mysqli_query($conn2,$query1);
		$total_paid = 0;
		$advance = 0;
		if((mysqli_num_rows($result1)) > 0){
			while($row1=mysqli_fetch_array($result1)){
				if($row1[1] == 1){
					$advance = $row1[0];
				}
				$total_paid += $row1[0];
			}
		}

		if($advance != 0) $advance_payment[] = $advance;
		else $advance_payment[] = 0;

		$invoice_no[]=$row[0];
		$date[]=$row[1];
		$invoice_total[]=$row[2];
		$invoice_cost[]=$row[3];
		$invoice_profit[]=$row[2]-$row[3];
		$cust[]=$row[4];
		$invoice_type[]=$row[5];
		$payments[]=$total_paid;
	}
}

// added by nirmal 11_08_2023
// update by nirmal 15_01_2024 (sysid 21 to new excel report template)
function exportExcelProfitReport(){
	global $message, $invoice_no, $date, $invoice_total, $invoice_profit, $invoice_type, $cust, $invoice_cost, $payments, $hp, $from_date, $to_date, $advance_payment, $hp, $group, $sub_system0, $item_code, $item_description;
	$user = $_COOKIE['user'];
	$inf_company = inf_company(1);
	$systemid = inf_systemid(1);
	$from_date = $to_date = $type = $group = $group_id = $sub_system = $sub_system0 = $date_qry = $hp_qry = $group_qry = $sub_system_qry = "";
	$invoice_no=$invoice_total=$invoice_profit=$invoice_cost=$date=$invoice_type=$cust=$payments=$advance_payment=$item_code=$item_description=array();
	$out = true;
	include('config.php');

	// Date filter
	if(($out) && (isset($_GET['from_date'])) && (validateDate($_GET['from_date'], "Y-m-d"))){
		$from_date = $_GET['from_date'];
	}else{
		$message = "Error: From date is required and it should be in YYYY-MM-DD format!";
		$out = false;
	}

	if(($out) && (isset($_GET['to_date'])) && (validateDate($_GET['to_date'], "Y-m-d"))){
		$to_date = $_GET['to_date'];
	}else{
		$message = "Error: To date is required and it should be in YYYY-MM-DD format!";
		$out = false;
	}

	if($out){
		$date_qry=" AND DATE(bm.billed_timestamp) BETWEEN  '$from_date' AND '$to_date'";
	}

	// HP filter
	if(($out) && (isset($_GET['hp']))){
		$hp=$_GET['hp'];
		if($hp=='yes'){
			$hp_qry=" AND bm.recovery_agent!=''";
			$type = "HP";
		}
		if($hp=='no'){
			$hp_qry=" AND bm.recovery_agent is NULL";
			$type = "CASH";
		}else{
			$type = "CASH & HP";
		}
	}

	// Group filer
	if(($out) && (isset($_GET['group']))){
		$group_id=$_GET['group'];
		if($group_id!=''){
			$group_qry=" AND gp.id='".$group_id."'";
		}else{
			$group = "ALL";
		}
	}

	// Subsystem filter
	if(($out) && (isset($_GET['sub_system0']))){
		$sub_system0=$_GET['sub_system0'];
		if($sub_system0 !=''){
			$sub_system_qry=" AND ss.id='".$sub_system0."'";
		}else{
			$sub_system = "ALL";
		}
	}

	// Cust group name
	if(($out) && ($group_id != '')){
		$query = "SELECT `name` FROM cust_group WHERE `id` = '$group_id'";
		$result=mysqli_query($conn2,$query);
		if (!$result || mysqli_num_rows($result) === 0) {
			$message = "Error : No matching group found";
			$out = false;
		} else {
			while ($row = mysqli_fetch_array($result)) {
				$group = $row[0];
			}
		}
	}

	// Subsystem name
	if(($out) && ($sub_system0 != '')){
		$query = "SELECT `name` FROM sub_system WHERE `id` = '$sub_system0'";
		$result=mysqli_query($conn2,$query);
		if (!$result || mysqli_num_rows($result) === 0) {
			$message = "Error : No matching sub system found";
			$out = false;
		} else {
			while ($row = mysqli_fetch_array($result)) {
				$sub_system = $row[0];
			}
		}
	}

	if($out){
		if($systemid != 21){
			$query = "SELECT bi.invoice_no, DATE(bm.billed_timestamp), SUM(bi.qty * bi.unit_price), SUM(bi.qty * bi.cost), cu.name, bm.`recovery_agent` FROM bill_main bm INNER JOIN bill bi ON bm.invoice_no = bi.invoice_no INNER JOIN inventory_items itm ON itm.id = bi.item INNER JOIN cust cu ON cu.id = bm.`cust` INNER JOIN cust_group gp ON cu.`associated_group` = gp.id INNER JOIN sub_system ss ON bm.sub_system = ss.id WHERE bm.`status` NOT IN (0, 7) AND bm.`lock` = 1 AND bm.exclude = 0 $date_qry $hp_qry $group_qry $sub_system_qry GROUP BY bi.invoice_no ORDER BY bm.`invoice_no`";
		}else{
			$query = "SELECT bm.invoice_no, DATE(bm.billed_timestamp), bi.qty * bi.unit_price, bi.qty * bi.cost, cu.name, bm.recovery_agent, itm.code, itm.description FROM bill_main bm INNER JOIN bill bi ON bm.invoice_no = bi.invoice_no INNER JOIN inventory_items itm ON itm.id = bi.item INNER JOIN cust cu ON cu.id = bm.cust INNER JOIN cust_group gp ON cu.associated_group = gp.id INNER JOIN sub_system ss ON bm.sub_system = ss.id WHERE bm.status NOT IN (0, 7) AND bm.lock = 1 AND bm.exclude = 0 $date_qry $hp_qry $group_qry $sub_system_qry ORDER BY bm.invoice_no, bi.item";
		}

		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$query1 = "SELECT `amount`, `bill_pay` FROM payment WHERE `invoice_no` = '$row[0]' AND `status` = 0 AND `chque_return` = 0";
			$result1=mysqli_query($conn2,$query1);
			$total_paid = 0;
			$advance = 0;
			if((mysqli_num_rows($result1)) > 0){
				while($row1=mysqli_fetch_array($result1)){
					if($row1[1] == 1){
						$advance = $row1[0];
					}
					$total_paid += $row1[0];
				}
			}

			if($advance != 0) $advance_payment[] = $advance;
			else $advance_payment[] = 0;

			$invoice_no[]=$row[0];
			$date[]=$row[1];
			$invoice_total[]=$row[2];
			$invoice_cost[]=$row[3];
			$invoice_profit[]=$row[2]-$row[3];
			$cust[]=$row[4];
			$invoice_type[]=$row[5];
			$payments[]=$total_paid;
			$item_code[]=$row[6];
			$item_description[]=$row[7];
		}
	}
	if($out){
		$excel_title = "FROM : $from_date - TO : $to_date,  TYPE : $type,  GROUP : $group,  SUB SYSTEM : $sub_system | $inf_company";
		if($systemid != 21){
			require_once('components/report/view/excel_profit_report.php');
		}else{
			require_once('components/report/view/excel_profit_report_21.php');
		}
	}
}

//---------------------------------Credit-------------------------------------//
function getStore(){
	global $st_id,$st_name;
	include('config.php');
	$query="SELECT id,name FROM stores WHERE `status`=1 ORDER BY name";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$st_id[]=$row[0];
		$st_name[]=$row[1];
	}
}

function getPOitems(){
	global $po_item,$po_item_occerence,$po_avg_old,$po_avg_new,$po_now_qty,$po_last_qty;
	include('config.php');
	$query="SELECT ins.inv_item,itm.description,COUNT(ins.inv_item),ROUND(SUM(ins.old_qty)/COUNT(ins.inv_item)) as `AVG_OLD`,ROUND(SUM(ins.added_qty)/COUNT(ins.inv_item)) as `AVG_NEW` FROM inventory_shipment ins, inventory_items itm WHERE ins.inv_item=itm.id AND ins.shipment_no!=0 AND ins.old_qty>0 AND ins.added_qty>0 GROUP BY ins.inv_item";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$po_item_id=$row[0];
		$result1 = mysqli_query($conn2,"SELECT SUM(qty) as `current_qty` FROM inventory_qty WHERE item='$po_item_id'");
		$row1 = mysqli_fetch_assoc($result1);
		$po_now_qty1=$row1['current_qty'];

		if($po_now_qty1<($row[3]*1.2)){
			$po_now_qty[]=$row1['current_qty'];
			$po_item[]=$row[1];
			$po_item_occerence[]=$row[2];
			$po_avg_old[]=$row[3];
			$po_avg_new[]=$row[4];

			$result1 = mysqli_query($conn2,"SELECT added_qty FROM inventory_shipment WHERE added_qty>0 AND inv_item='$po_item_id' ORDER BY id DESC LIMIT 1");
			$row1 = mysqli_fetch_assoc($result1);
			$po_last_qty[]=$row1['added_qty'];
		}
	}
}

function getItems(){
global $itm_code,$itm_description;
	include('config.php');
		$query="SELECT code,description FROM inventory_items WHERE `status`=1";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$itm_code[]=$row[0];
			$itm_description[]=$row[1];
	}
}

function getCategory(){
global $itc_id,$itc_name;
	include('config.php');
		$query="SELECT id,`name` FROM item_category ORDER BY `name`";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$itc_id[]=$row[0];
			$itc_name[]=$row[1];
	}
}

function getCustomerSS(){
global $cust_id,$cust_name;
	$store=$_COOKIE['store'];
	include('config.php');
		$query="SELECT cu.id,cu.`name`,ss.`name` FROM cust cu, sub_system ss WHERE cu.`sub_system`=ss.id AND cu.`status`=1";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$cust_id[]=$row[0];
			$cust_name[]=$row[1].' | '.$row[2];
	}
}

function salesTrend(){
global $from,$to,$sys,$tr1_month,$tr1_sale,$tr1_profit,$tr3_item,$tr3_sale,$tr4_category,$tr4_sale,$tr5_item,$tr5_sale,$tr6_category,$tr6_sale,$st_id,$st_name,$tr2_store_sale;
	if(isset($_REQUEST['from_date']))	$from=$_REQUEST['from_date']; else $from=$date=date("Y-m-d",time()- 60*60*24*365 );
	if(isset($_REQUEST['to_date']))	$to=$_REQUEST['to_date']; else $to=$date=date("Y-m-d",time());
	$sys=$_REQUEST['sys'];
	if($sys=='all'){
		$sys_qry1=$sys_qry2="";
	}else{
		$sys_qry1="AND bm.`sub_system`='$sys'";
		$sys_qry2="AND `sub_system`='$sys'";
	}
	include('config.php');

	$query="SELECT id,name FROM stores WHERE `status`=1 $sys_qry2";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$st_id[]=$row[0];
		$st_name[]=$row[1];
	}

	$query="SELECT year(bm.billed_timestamp),monthname(bm.billed_timestamp),month(bm.billed_timestamp),round(SUM(bi.unit_price * bi.qty)),round(SUM((bi.unit_price - bi.cost)*bi.qty)) FROM bill_main bm, bill bi WHERE bm.invoice_no=bi.invoice_no AND bm.`status` NOT IN (0,6,7) AND bm.`lock`=1 AND date(bm.billed_timestamp) BETWEEN  '$from' AND '$to' $sys_qry1 GROUP BY year(bm.billed_timestamp), month(bm.billed_timestamp) ORDER BY bm.billed_timestamp LIMIT 12";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$tmp_monthname=$row[0].' - '.substr($row[1],0,3);
		$tr1_month[]=$tmp_monthname;
		$tr1_sale[]=$row[3];
		$tr1_profit[]=$row[4];
		$tmp_month=$row[0].'-'.str_pad($row[2], 2, "0", STR_PAD_LEFT);
		for($i=0;$i<sizeof($st_id);$i++){
			$tmp_store=$st_id[$i];
		//	$query1="SELECT round(SUM(bi.unit_price*bi.qty)) FROM bill_main bm, bill bi, stores st WHERE bm.invoice_no=bi.invoice_no AND bm.store=st.id AND bm.`status` NOT IN (0,6,7) AND bm.`lock`=1 AND bm.billed_timestamp LIKE '$tmp_month%' AND bm.`store`='$tmp_store' $sys_qry1";
			$query1="SELECT round(SUM(bm.`invoice_+total` + bm.`invoice_-total`)) FROM bill_main bm, stores st WHERE bm.store=st.id AND bm.`status` NOT IN (0,6,7) AND bm.`lock`=1 AND bm.billed_timestamp LIKE '$tmp_month%' AND bm.`store`='$tmp_store' $sys_qry1";
			$result1=mysqli_query($conn2,$query1);
			while($row1=mysqli_fetch_array($result1)){
				if($row1[0]=='') $tmp_sale=0; else $tmp_sale=$row1[0];
				$tr2_store_sale[$tmp_monthname][$tmp_store]=$tmp_sale;
			}
		}
	}
	$query="SELECT itm.description,SUM(bi.unit_price * bi.qty) FROM bill_main bm, bill bi, inventory_items itm WHERE bm.invoice_no=bi.invoice_no AND bi.item=itm.id AND bm.`status` NOT IN (0,7) AND bm.`lock`=1 AND itm.description!='CHQUE' AND itm.description!='CREDIT' AND date(bi.`date`) BETWEEN  '$from' AND '$to' $sys_qry1 GROUP By bi.item ORDER BY SUM(bi.unit_price * bi.qty) DESC LIMIT 10";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$tr3_item[]=$row[0];
		$tr3_sale[]=$row[1];
	}
	$query="SELECT itc.name,SUM(bi.unit_price * bi.qty) FROM bill_main bm, bill bi, inventory_items itm, item_category itc WHERE bm.invoice_no=bi.invoice_no AND bi.item=itm.id AND itm.category=itc.id AND bm.`status` NOT IN (0,7) AND bm.`lock`=1 AND date(bi.`date`) BETWEEN  '$from' AND '$to' $sys_qry1 GROUP By itm.category ORDER BY SUM(bi.unit_price * bi.qty) DESC LIMIT 10";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$tr4_category[]=$row[0];
		$tr4_sale[]=$row[1];
	}
	$query="SELECT itm.description,SUM(bi.qty) FROM bill_main bm, bill bi, inventory_items itm WHERE bm.invoice_no=bi.invoice_no AND bi.item=itm.id AND itm.description!='CHQUE' AND itm.description!='CREDIT' AND bm.`status` NOT IN (0,7) AND bm.`lock`=1 AND date(bi.`date`) BETWEEN  '$from' AND '$to' $sys_qry1 GROUP By bi.item ORDER BY SUM(bi.qty) DESC LIMIT 10";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$tr5_item[]=$row[0];
		$tr5_sale[]=$row[1];
	}
	$query="SELECT itc.name,SUM(bi.qty) FROM bill_main bm, bill bi, inventory_items itm, item_category itc WHERE bm.invoice_no=bi.invoice_no AND bi.item=itm.id AND itm.category=itc.id AND bm.`status` NOT IN (0,7) AND bm.`lock`=1 AND date(bi.`date`) BETWEEN  '$from' AND '$to' $sys_qry1 GROUP By itm.category ORDER BY SUM(bi.qty) DESC LIMIT 10";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$tr6_category[]=$row[0];
		$tr6_sale[]=$row[1];
	}
}

function creditTrend(){
global $from,$to,$sys,$group,$store,$tr1_month,$tr1_sale,$tr1_credit,$tr3_item,$tr3_sale,$tr4_category,$tr4_sale,$tr5_item,$tr5_sale,$tr6_category,$tr6_sale,$gp_id,$gp_name,$st_id,$st_name,$tr2_store_credit;
	if(isset($_REQUEST['from_date']))	$from=$_REQUEST['from_date']; else $from=$date=date("Y-m-d",time()- 60*60*24*365 );
	if(isset($_REQUEST['to_date']))	$to=$_REQUEST['to_date']; else $to=$date=date("Y-m-d",time());
	if(isset($_REQUEST['sys'])) $sys=$_REQUEST['sys']; else $sys='all';
	if(isset($_REQUEST['group'])) $group=$_REQUEST['group']; else $group='all';
	if(isset($_REQUEST['store'])) $store=$_REQUEST['store']; else $store='all';
	$user_id=$_COOKIE['user_id'];
	if($sys=='all'){
		$sys_qry1=$sys_qry2=$sys_qry3=$sys_qry4=$sys_qry5="";
	}else{
		$sys_qry1="AND `sub_system`='$sys'";
		$sys_qry2="AND bm.`sub_system`='$sys'";
		$sys_qry3="AND py.`sub_system`='$sys'";
		$sys_qry4="AND cg.`sub_system`='$sys'";
		$sys_qry5="WHERE `sub_system`='$sys'";
	}

	$group_list='';

	if($store=='all'){ $st_qry=""; }else{ $st_qry="AND bm.`store`='$store'"; }

	include('config.php');

	if($_GET['components']=='report')	$query="SELECT id,name FROM cust_group $sys_qry5 ORDER BY name";
	if($_GET['components']=='marketing')	$query="SELECT cg.id,cg.`name` FROM cust_group cg, user_to_group ug WHERE cg.id=ug.`group` AND ug.`user`='$user_id' $sys_qry4 ORDER BY cg.`name`";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$gp_id[]=$row[0];
		$gp_name[]=$row[1];
		$group_list.=$row[0].',';
	}
	$group_list=rtrim($group_list,',');

	if($group=='all'){
		$gp_qry="";
		if($_GET['components']=='marketing') $gp_qry="AND cu.`associated_group` IN ($group_list)";
	}else{
		$gp_qry="AND cu.`associated_group`='$group'";
	}

	$query="SELECT id,name FROM stores WHERE `status`=1 $sys_qry1";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$st_id[]=$row[0];
		$st_name[]=$row[1];
	}

	$query="SELECT year(bm.billed_timestamp),monthname(bm.billed_timestamp),month(bm.billed_timestamp),round(SUM(bm.`invoice_+total` + bm.`invoice_-total`)) FROM bill_main bm, cust cu WHERE bm.`cust`=cu.id AND bm.`status` NOT IN (0,6,7) AND bm.`lock`=1 AND date(bm.billed_timestamp) BETWEEN  '$from' AND '$to' $sys_qry2 $gp_qry $st_qry GROUP BY year(bm.billed_timestamp), month(bm.billed_timestamp) ORDER BY bm.billed_timestamp LIMIT 12";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$tmp_monthname=$row[0].' - '.substr($row[1],0,3);
		$tr1_month[]=$tmp_monthname;
		$tr1_sale[]=$row[3];
		//$tr1_credit[]=$row[4];
		$tmp_month=$row[0].'-'.str_pad($row[2], 2, "0", STR_PAD_LEFT);
		$tmp_month2=$row[0].'-'.str_pad($row[2], 2, "0", STR_PAD_LEFT).'-28 23:59:00';
		$month_allstore_cr_total=0;
		for($i=0;$i<sizeof($st_id);$i++){
			if(($store=='all')||($store==$st_id[$i])){
				$tmp_store=$st_id[$i];
				$query1="SELECT SUM(bm.`invoice_+total`)+SUM(bm.`invoice_-total`) FROM bill_main bm, stores st, cust cu WHERE bm.`store`=st.id AND bm.`cust`=cu.id AND bm.exclude=0 AND bm.`status` NOT IN (0,6,7) AND bm.`lock`=1 AND date(bm.billed_timestamp)<='$tmp_month2' AND bm.`store`='$tmp_store' $gp_qry $sys_qry2";
				$row1=mysqli_fetch_row(mysqli_query($conn2,$query1));
				$bill_total=$row1[0];
				$query1="SELECT SUM(py.amount) FROM payment py, stores st, cust cu WHERE py.`store`=st.id AND py.`cust`=cu.id AND py.status=0 AND py.chque_return=0 AND date(py.payment_date)<='$tmp_month2' AND py.`store`='$tmp_store' $gp_qry $sys_qry3";
				$row1=mysqli_fetch_row(mysqli_query($conn2,$query1));
				$pay_total=$row1[0];
				$credit_bal=round($bill_total-$pay_total);
				$month_allstore_cr_total+=$credit_bal;
				$tr2_store_credit[$tmp_monthname][$tmp_store]=$credit_bal;
			}
		}
//		print $month_allstore_cr_total.'<br />';
		$tr1_credit[]=$month_allstore_cr_total;

	}
}

function getSalesman(){
	global $salesman_list;
	include('config.php');
	$query="SELECT username FROM userprofile WHERE `status`=0";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$salesman_list[]=$row[0];
	}
}

function getPcommision(){
	global $txt_color,$salesman,$from_date,$to_date,$com_date,$com_pay_type,$com_pay_amount,$com_cust,$com_balance_before,$com_balance_after,$com_bill_inv,$com_pay_inv,$com_total_before,$com_com;
	$com_date=$com_pay_type=$com_pay_amount=$com_cust=$com_bill_inv=$com_pay_inv=$com_balance_before=$com_balance_after=$com_com=array();	if(isset($_REQUEST['salesman']) && isset($_REQUEST['from_date']) && isset($_REQUEST['to_date'])){
		$salesman=$_REQUEST['salesman'];
		$from_date=$_REQUEST['from_date'];
		$to_date=$_REQUEST['to_date'];
		$j=0;
	include('config.php');
		$result = mysqli_query($conn2,"SELECT id FROM userprofile WHERE username='$salesman'");
		$row = mysqli_fetch_assoc($result);
		$salesman_id=$row['id'];

		if($from_date<=$to_date){
			$timestamp=strtotime($from_date);
			for($selected_date=$from_date;$selected_date<=$to_date;$selected_date=date("Y-m-d",$timestamp+(24*60*60))){
				$timestamp=strtotime($selected_date);
				$query="SELECT py.payment_type,py.amount,py.payment_date,py.cust,cu.name,py.id,py.invoice_no FROM payment py, cust cu WHERE py.`cust`=cu.id AND py.salesman='$salesman_id' AND py.`status`=0 AND date(py.payment_date)='$selected_date'";
				$result=mysqli_query($conn2,$query);
				while($row=mysqli_fetch_array($result)){
					$pay_time=$row[2];
					$cust_tmp=$row[3];
					$invno_tmp=$row[6];
					$query1="SELECT SUM(bi.qty*bi.unit_price) as `total` FROM bill bi, bill_main bm WHERE bm.invoice_no=bi.invoice_no AND bm.`status` NOT IN (0,7) AND bm.`cust`='$cust_tmp' AND bi.date <= '$pay_time'";
					$result1 = mysqli_query($conn2,$query1);
					$row1 = mysqli_fetch_assoc($result1);
					$totalinv=$row1['total'];
					$query1="SELECT SUM(py.amount) as `pay` FROM payment py WHERE py.status=0 AND py.`cust`='$cust_tmp' AND py.chque_return=0 AND py.payment_date < '$pay_time'";
					$result1 = mysqli_query($conn2,$query1);
					$row1 = mysqli_fetch_assoc($result1);
					$totalpay=$row1['pay'];
					$before_balance_tmp=$totalinv-$totalpay;
					$after_balance_tmp=$totalinv-$totalpay-$row[1];
	//				print $invno_tmp.' - '.$after_balance_tmp.'<br>';
					if($after_balance_tmp<=0){
						if($invno_tmp==0){
							$bill_invar=$bill_sumar=array();
							$query1="SELECT invoice_no,(`invoice_+total` + `invoice_-total`) FROM bill_main bm WHERE date(bm.billed_timestamp)='$selected_date' AND bm.`billed_by`='$salesman_id' AND bm.cust='$cust_tmp'";
							$result1=mysqli_query($conn2,$query1);
							while($row1=mysqli_fetch_array($result1)){
								$bill_invar[]=$row1[0];
								$bill_sumar[]=$row1[1];
								$invno_tmp1=$row1[0];
								$query2="SELECT COUNT(id) as `count` FROM payment WHERE invoice_no=$invno_tmp1";
								$result2 = mysqli_query($conn2,$query2);
								$row2 = mysqli_fetch_assoc($result2);
								if($row2['count']==0){
									$invno_tmp=$invno_tmp1;
									$txt_color[]='red';
								}
							}

							$bill_sum=array_sum($bill_sumar);
						}else{
							$txt_color[]='black';
							$query1="SELECT SUM(qty*unit_price) as `bisum` FROM bill WHERE invoice_no='$invno_tmp'";
							$result1 = mysqli_query($conn2,$query1);
							$row1 = mysqli_fetch_assoc($result1);
							$bill_sum=$row1['bisum'];
						}
						if($invno_tmp!=0){
							if($row[0]==1){ $com_pay_type[]='CASH'; $com=1/100; }
							if($row[0]==2){ $com_pay_type[]='CHQUE'; $com=0.5/100; }
							$com_bill_inv[$j][]=$invno_tmp;
							$com_date[]=$pay_time;
							$com_pay_amount[]=$row[1];
							$com_cust[]=$row[4];
							$com_pay_inv[]=$row[5];
							$com_balance_before[]=$before_balance_tmp;
							$com_balance_after[]=$after_balance_tmp;
							$com_com[]=round(($row[1]-($before_balance_tmp-$bill_sum))*$com);
							$j++;
						}
					}

//					print $cust_tmp.' - '.$totalinv.' - '.$totalpay.'<br>';
				}
			}

		}else{
			print 'error';
		}
	}
}

function returnItems(){
	global $category,$item,$cust,$salesman_idlist,$salesman_list,$from_date,$to_date,$rtn_inv,$rtn_date,$rtn_by,$rtn_cust,$rtn_st,$rtn_store,$disp_id,$disp_description,$disp_qty,$disp_store,$disp_date,$disp_cost,$drtn_inv,$drtn_qty,$graph1_item,$graph1_qty,$graph2_item,$graph2_qty,$graph3_cust,$graph3_qty,$graph4_salesman,$graph4_qty;
	$j=$disp_id_tmp=0;
	$k=-1;
	$sm_qry=$cust_qry=$cat_qry=$item_qry="";
	$disp_id=$rtn_inv=$rtn_qty=$dis_id=$disp_qty=$disp_cost=array();
	$sm=$_GET['sm'];
	if(isset($_REQUEST['from_date'])) $from_date=$_REQUEST['from_date']; else $from_date=date("Y-m-d",time()-(60*60*24*30));
	if(isset($_REQUEST['to_date'])) $to_date=$_REQUEST['to_date']; else $to_date=date("Y-m-d",time());
	if(isset($_GET['cust']))$cust=$_GET['cust']; else $cust='';
	if(isset($_GET['category']))$category=$_GET['category']; else $category='';
	if(isset($_GET['item']))$item=$_GET['item']; else $item='';
	if($sm!='') $sm_qry="AND rm.return_by='$sm'";
	include('config.php');

	if($cust!=''){
		$p=strrpos($cust,'|');
		$s1=substr($cust,0,($p-1));
		$s2=substr($cust,($p+2));
		$query="SELECT cu.id FROM cust cu, sub_system ss WHERE cu.`sub_system`=ss.id AND cu.`status`=1 AND cu.`name`='$s1' AND ss.`name`='$s2'";
		$row=mysqli_fetch_row(mysqli_query($conn2,$query));
		$cust_id=$row[0];
		if($cust_id!=''){
			$cust_qry="AND rm.`cust`='$cust_id'";
		}
	}
	if($category!=''){
		$cat_qry="AND itm.`category`='$category'";
	}
	if($item!=''){
		$query="SELECT id FROM inventory_items WHERE `status`='1' AND description='$item'";
		$row=mysqli_fetch_row(mysqli_query($conn2,$query));
		$item_id=$row[0];
		if($item_id!=''){
			$item_qry="AND rt.`return_item`='$item_id'";
		}
	}

		$query="SELECT id,username FROM userprofile WHERE `status`=0";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$salesman_idlist[]=$row[0];
			$salesman_list[]=$row[1];
		}

		$query="SELECT rm.invoice_no,date(rm.return_date),up.username,cu.name,st.name,COUNT(rt.id),SUM(rt.`status`) FROM return_main rm, `return` rt, inventory_items itm, userprofile up, cust cu, stores st WHERE rm.invoice_no=rt.invoice_no AND rt.return_item=itm.id AND rm.store=st.id AND rm.return_by=up.id AND rm.cust=cu.id AND date(rm.return_date) BETWEEN '$from_date' AND '$to_date' $sm_qry $cust_qry $cat_qry $item_qry GROUP BY rm.invoice_no ORDER BY st.name, rm.invoice_no DESC";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$rtn_inv[]=$row[0];
			$rtn_date[]=$row[1];
			$rtn_by[]=$row[2];
			$rtn_cust[]=$row[3];
			$rtn_store[]=$row[4];
			if($row[5]==$row[6]) $rtn_st[]='Processed'; else $rtn_st[]='Pending';
		}

//		$query="SELECT dis.id,itm.description,dis.qty,st.name,date(dis.`date`) FROM return_disposal dis, inventory_items itm, stores st WHERE dis.store=st.id AND dis.item=itm.id AND date(dis.`date`) BETWEEN '$from_date' AND '$to_date' ORDER BY dis.id DESC";
		$query="SELECT dis.id,itm.description,dis.qty,st.name,date(dis.`date`),rm.invoice_no,rt.qty,itq.c_price FROM return_main rm, `return` rt, return_disposal dis, inventory_items itm, inventory_qty itq, stores st WHERE rm.invoice_no=rt.invoice_no AND rt.disposal_id=dis.id AND dis.store=st.id AND dis.item=itm.id AND itq.item=itm.id AND itq.location=st.id AND date(dis.`date`) BETWEEN '$from_date' AND '$to_date' $sm_qry $cust_qry $cat_qry $item_qry ORDER BY dis.id DESC";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			if($disp_id_tmp!=$row[0]){ $j=0; $k++;}
			$drtn_inv[$k][$j]=$row[5];
			$drtn_qty[$k][$j]=$row[6];
			if($disp_id_tmp!=$row[0]){
				$disp_id[$k]=$row[0];
				$disp_description[$k]=$row[1];
				$disp_qty[$k]=$row[2];
				$disp_store[$k]=$row[3];
				$disp_date[$k]=$row[4];
				$disp_cost[$k]=$row[2]*$row[7];
			}
			$j++;
			$disp_id_tmp=$row[0];
		}

		$query="SELECT itm.description,SUM(dis.qty) FROM return_main rm, `return` rt, return_disposal dis, inventory_items itm WHERE rm.invoice_no=rt.invoice_no AND rt.disposal_id=dis.id AND dis.item=itm.id AND date(dis.`date`) BETWEEN '$from_date' AND '$to_date' $sm_qry $cust_qry $cat_qry $item_qry GROUP BY dis.item ORDER BY SUM(dis.qty) DESC LIMIT 10";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$graph1_item[]=$row[0];
			$graph1_qty[]=$row[1];
		}

		$query="SELECT itm.description,SUM(rt.qty) FROM `return_main` rm, `return` rt, inventory_items itm WHERE rm.invoice_no=rt.invoice_no AND rt.return_item=itm.id AND date(rm.return_date) BETWEEN '$from_date' AND '$to_date' $sm_qry $cust_qry $item_qry GROUP BY rt.return_item $cat_qry $item_qry  ORDER BY SUM(rt.qty) DESC LIMIT 10";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$graph2_item[]=$row[0];
			$graph2_qty[]=$row[1];
		}

		$query="SELECT cu.name,SUM(rt.qty) FROM `return_main` rm, `return` rt, inventory_items itm , cust cu WHERE rm.invoice_no=rt.invoice_no AND rt.return_item=itm.id AND rm.cust=cu.id AND date(rm.return_date) BETWEEN '$from_date' AND '$to_date' $sm_qry $cust_qry $cat_qry $item_qry GROUP BY rm.cust ORDER BY SUM(rt.qty) DESC LIMIT 10";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$graph3_cust[]=$row[0];
			$graph3_qty[]=$row[1];
		}

		$query="SELECT up.username,SUM(rt.qty) FROM `return_main` rm, `return` rt, inventory_items itm , userprofile up WHERE rm.invoice_no=rt.invoice_no AND rt.return_item=itm.id AND rm.return_by=up.id AND date(rm.return_date) BETWEEN '$from_date' AND '$to_date' $sm_qry $cust_qry $cat_qry $item_qry GROUP BY rm.return_by ORDER BY SUM(rt.qty) DESC LIMIT 10";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$graph4_salesman[]=$row[0];
			$graph4_qty[]=$row[1];
		}

		$query="SELECT DISTINCT year(`return_date`) FROM `return_main`";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$rtn_yearlist[]=$row[0];
		}
}

function returnOne(){
	global $id,$rtn_date,$rtn_by,$rtn_itm,$rtn_qty,$rtn_cust,$rtn_store,$rtn_status;
	$id=$_REQUEST['id'];
	include('config.php');
	$query="SELECT rt.return_date,up.username,itm.description,rt.qty,cu.name,st.name,rt.`status` FROM `return` rt, cust cu, userprofile up, stores st, inventory_items itm WHERE rt.return_by=up.id AND rt.return_item=itm.id AND rt.`cust`=cu.id AND rt.location=st.id AND rt.return_invoice_no='$id'";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$rtn_date=$row[0];
		$rtn_by=$row[1];
		$rtn_itm[]=$row[2];
		$rtn_qty[]=$row[3];
		$rtn_cust=$row[4];
		$rtn_store=$row[5];
		if($row[6]==0) $rtn_status[]='Pending'; else  $rtn_status[]='Processed';
	}
}

function getCost(){
	global $st_name,$store_c_total,$store_w_total,$store_wmin_total,$disposal_total,$from_date,$to_date,$itc_id,$itc_name,$itc_total,$pending_tr_no,$pending_tr_date,$pending_tr_amount,$pending_bm_no,$pending_bm_date,$pending_bm_amount;
	if(isset($_REQUEST['from_date'])) $from_date=$_REQUEST['from_date']; else $from_date=date("Y-m-d",time()-(60*60*24*30));
	if(isset($_REQUEST['to_date'])) $to_date=$_REQUEST['to_date']; else $to_date=date("Y-m-d",time());
	$store_c_total=$store_w_total=$store_wmin_total=$itc_name=$pending_tr_no=$pending_tr_date=$pending_tr_amount=$pending_bm_no=$pending_bm_date=$pending_bm_amount=array();
	$unic_cal=unicCal();

	if($unic_cal) $qry1="AND itm.unic='0'"; else $qry1="";

	include('config.php');
	$query="SELECT id,name FROM stores WHERE `status`='1'";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$st_id=$row[0];
		$st_name[]=$row[1];

		$query1="SELECT SUM(itq.c_price*itq.qty) as `c_total`,SUM(itq.w_price*itq.qty) as `w_total`,SUM(itq.w_price*itq.qty* ((100-itm.min_w_rate)/100)) as `wmin_total`  FROM inventory_qty itq, inventory_items itm WHERE itm.id=itq.item AND itm.pr_sr=1 AND itq.location='$st_id' $qry1";
		$row1=mysqli_fetch_row(mysqli_query($conn2,$query1));
		$inv_c_total=round($row1[0]);
		$inv_w_total=round($row1[1]);
		$inv_wmin_total=round($row1[2]);
		if($unic_cal){
			$query1="SELECT SUM(itu.c_price),SUM(itu.w_price),SUM(itu.w_price * ((100-itm.min_w_rate)/100)) FROM inventory_items itm, inventory_qty itq, inventory_unic_item itu WHERE itm.id=itq.item AND itq.id=itu.itq_id AND itq.location='$st_id' AND itu.`status`='0' ";
			$row1=mysqli_fetch_row(mysqli_query($conn2,$query1));
			$inv_c_total+=round($row1[0]);
			$inv_w_total+=round($row1[1]);
			$inv_wmin_total+=round($row1[2]);
		}

		$query1="SELECT SUM(itn.c_price*itn.qty) as `c_total`,SUM(itn.w_price*itn.qty) as `w_total`,SUM(itn.w_price*itn.qty* ((100-itm.min_w_rate)/100)) as `wmin_total`  FROM inventory_new itn, inventory_items itm WHERE itm.id=itn.item AND itm.pr_sr=1 AND itn.store='$st_id' $qry1";
		$row1=mysqli_fetch_row(mysqli_query($conn2,$query1));
		$newinv_c_total=round($row1[0]);
		$newinv_w_total=round($row1[1]);
		$newinv_wmin_total=round($row1[2]);

		$store_c_total[$row[1]]=$inv_c_total+$newinv_c_total;
		$store_w_total[$row[1]]=$inv_w_total+$newinv_w_total;
		$store_wmin_total[$row[1]]=$inv_wmin_total+$newinv_wmin_total;

		$result1 = mysqli_query($conn2,"SELECT SUM(c_price*qty) as `total` FROM return_disposal WHERE store='$st_id' AND date(`date`) BETWEEN '$from_date' AND '$to_date'");
		$row1 = mysqli_fetch_assoc($result1);
		$disposal_total[$row[1]]=round($row1['total']);
	}
		$query="SELECT itc.id,itc.`name` FROM item_category itc ORDER BY itc.`name`";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$itc_tmp=$row[0];
			$itc_name[]=$row[1];

			$query1="SELECT SUM(itq.c_price * itq.qty) FROM inventory_qty itq, inventory_items itm WHERE itm.id=itq.item AND itm.category='$itc_tmp' AND itm.`status`='1' $qry1";
			$row1=mysqli_fetch_row(mysqli_query($conn2,$query1));
			$itq_total=$row1[0];
			if($unic_cal){
				$query1="SELECT SUM(itu.c_price) FROM inventory_items itm, inventory_qty itq, inventory_unic_item itu WHERE itm.id=itq.item AND itq.id=itu.itq_id AND itm.category='$itc_tmp' AND itu.`status`='0' AND itm.`status`='1'";
				$row1=mysqli_fetch_row(mysqli_query($conn2,$query1));
				$itq_total+=$row1[0];
			}
			$query1="SELECT SUM(itn.c_price * itn.qty) FROM inventory_new itn, inventory_items itm WHERE itm.id=itn.item AND itm.category='$itc_tmp' $qry1";
			$row1=mysqli_fetch_row(mysqli_query($conn2,$query1));
			$itn_total=$row1[0];

			$itc_total[]=$itq_total+$itn_total;
		}

	$query="SELECT tm.gtn_no,date(tm.`date`),SUM(tr.c_price * tr.qty) FROM transfer_main tm, transfer tr WHERE tm.gtn_no=tr.gtn_no AND tm.`status` IN (0,4) GROUP BY tm.gtn_no ORDER BY tm.`date`";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$pending_tr_no[]=$row[0];
		$pending_tr_date[]=$row[1];
		$pending_tr_amount[]=$row[2];
	}
	$query="SELECT bm.invoice_no,date(bm.billed_timestamp),SUM(bi.`qty`*bi.`cost`) FROM bill_main bm, bill bi WHERE bm.invoice_no=bi.invoice_no AND bm.exclude=0 AND bm.`status`!=0 AND bm.`lock`=0 GROUP BY bm.invoice_no ORDER BY bm.billed_timestamp";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$pending_bm_no[]=$row[0];
		$pending_bm_date[]=$row[1];
		$pending_bm_amount[]=$row[2];
	}
}

function getUnlockedBills2(){
	global $invoice_no,$billed_by,$billed_store,$date,$time,$lock;
	$invoice_no=array();
	include('config.php');
	$query="SELECT DISTINCT bm.invoice_no,up.username,st.name,DATE(bm.billed_timestamp),TIME(bm.billed_timestamp),bm.`lock` FROM bill bi ,bill_main bm, userprofile up, stores st WHERE bi.invoice_no=bm.invoice_no AND bm.billed_by=up.id AND bm.store=st.id AND bm.`lock`!=1 AND bm.`status` NOT IN (0,7)";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$invoice_no[]=$row[0];
		$billed_by[]=$row[1];
		$billed_store[]=$row[2];
		$date[]=$row[3];
		$time[]=$row[4];
		$lock[]=$row[5];
	}
}

function getItembySalesman(){
	global $from_date,$to_date,$item,$salesman,$soldqty;
	$salesman=array();
	$item=$_GET['item'];
	$from_date=$_GET['from_date'];
	$to_date=$_GET['to_date'];
	if(isset($_GET['item'])){
		if(($_GET['item']!='')&&($_GET['from_date']!='')&&($_GET['to_date']!='')){
			include('config.php');
			$query="SELECT up.username,SUM(bi.qty) FROM bill_main bm, bill bi, inventory_items itm, userprofile up WHERE bm.invoice_no=bi.invoice_no AND bi.item=itm.id AND bm.billed_by=up.id AND bm.`status` NOT IN (0,7) AND bm.`lock`=1 AND (bi.`date` BETWEEN '$from_date' AND '$to_date') AND itm.description='$item' GROUP BY up.id ORDER BY up.username";
			$result=mysqli_query($conn2,$query);
			while($row=mysqli_fetch_array($result)){
				$salesman[]=$row[0];
				$soldqty[]=$row[1];
			}
		}
	}
}

function getPendingApproval(){
	global $loan_id,$loan_amount,$loan_emp,$loan_paidoff,$loan_start,$loan_end,$loan_duration,$ship_id,$ship_date,$ship_submit_by;
	$loan_id=$ship_id=array();
	include('config.php');
	$query="SELECT lm.id,lm.amount,lm.rate,lm.duration,up.username,date(lm.start_date) FROM userprofile up, loan_main lm LEFT JOIN loan_pay lp ON lm.id=lp.loan_id WHERE lm.emp_id=up.id AND lm.`status`=1 GROUP BY lm.id ORDER BY lm.id";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$loan_id[]=$row[0];
		$loan_amount[]=$row[1];
		$loan_amount_tmp=$row[1];
		$loan_rate=$row[2];
		$loan_duration[]=$row[3];
		$loan_duration_tmp=$row[3];
		$loan_emp[]=$row[4];
		$loan_paidoff[]=round(($loan_amount_tmp+(($loan_amount_tmp*(($loan_rate/100))/12)*$loan_duration_tmp)),2);
		$loan_start[]=$row[5];
		$date = new DateTime($row[5]);
		$date->modify("+$row[3] month");
		$loan_end[]=$date->format('Y-m-d');
	}
	$query="SELECT sm.id,sm.shipment_date,up.username FROM shipment_main sm, userprofile up WHERE sm.added_by=up.id AND sm.`status`=1";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$ship_id[]=$row[0];
		$ship_date[]=$row[1];
		$ship_submit_by[]=$row[2];
	}
}

function setLoanStatus($new_status){
	global $message;
	$id=$_GET['id'];
	$user=$_COOKIE['user_id'];
	$today1=timeNow();
	if($new_status==2) $wording='Approved';
	if($new_status==3) $wording='Rejected';
	include('config.php');
	$query="UPDATE `loan_main` SET `status`='$new_status',`approved_by`='$user',`approved_date`='$today1' WHERE id='$id'";
	$result=mysqli_query($conn,$query);

	if($result){
		$message="The Loan was $wording Successfully";
		return true;
	}else{
		$message="Error: The Loan could not be $wording !";
		return false;
	}
}

function setShipmentStatus($new_status){
	global $message;
	$id=$_GET['id'];
	$user=$_COOKIE['user_id'];
	$today1=timeNow();
	if($new_status==2) $wording='Approved';
	if($new_status==0) $wording='Rejected';
	include('config.php');
	$query="UPDATE `shipment_main` SET `status`='$new_status',`delete_approve_by`='$user',`delete_approve_date`='$today1' WHERE id='$id'";
	$result=mysqli_query($conn,$query);

	if($result){
		$message="The Shipment was $wording for Deletion Successfully";
		return true;
	}else{
		$message="Error: The Shipment could not be $wording for Deletion !";
		return false;
	}
}

function getUserAuditLog(){
	global $username,$from_date,$to_date,$bill_no,$bill_lock,$bill_del_date,$bill_deleted_by,$tr1_no,$tr1_del_date,$tr1_deleted_by,$tr2_no,$tr2_del_date,$tr2_deleted_by,$sm_no,$sm_del_approve_by,$sm_del_approve_date,$sm_del_by,$sm_del_date,$itu_sm_no,$itu_sn,$itu_del_by,$itu_del_date;
	$bill_no=$tr1_no=$tr2_no=$sm_no=$itu_sm_no=array();
	if(isset($_GET['username'])){
	$username=$_GET['username'];
	$from_date=$_GET['from_date'];
	$to_date=$_GET['to_date'];
	if(($from_date!='')&&($to_date!='')){
		include('config.php');

		if($username=='') $usr_qty=''; else $usr_qty="AND up.username='$username'";
		$query="SELECT bm.invoice_no,bm.deleted_timestamp,bm.`lock`,up.username FROM bill_main bm, userprofile up WHERE bm.deleted_by=up.id AND DATE(bm.deleted_timestamp) BETWEEN '$from_date' AND '$to_date' AND bm.`status`='0' $usr_qty";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$bill_no[]=$row[0];
			$bill_del_date[]=$row[1];
			if($row[2]==0) $bill_lock[]='Unlocked'; else $bill_lock[]='Locked';
			$bill_deleted_by[]=ucfirst($row[3]);
		}

		$query="SELECT tm.gtn_no,tm.action_date,up.username FROM transfer_main tm, userprofile up WHERE tm.remote_user=up.id AND tm.`status`='2' AND DATE(tm.action_date) BETWEEN '$from_date' AND '$to_date' $usr_qty";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$tr1_no[]=$row[0];
			$tr1_del_date[]=$row[1];
			$tr1_deleted_by[]=ucfirst($row[2]);
		}

		$query="SELECT tm.gtn_no,tm.action_date,up.username FROM transfer_main tm, userprofile up WHERE tm.`user`=up.id AND tm.`status`='3' AND date(tm.action_date) BETWEEN '$from_date' AND '$to_date' $usr_qty";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$tr2_no[]=$row[0];
			$tr2_del_date[]=$row[1];
			$tr2_deleted_by[]=ucfirst($row[2]);
		}

		$query="SELECT sm.id,up2.username,sm.delete_approve_date,up.username,sm.delete_date FROM shipment_main sm, userprofile up, userprofile up2 WHERE sm.delete_by=up.id AND sm.delete_approve_by=up2.id AND sm.`status`='3' AND date(sm.delete_date) BETWEEN '$from_date' AND '$to_date' $usr_qty";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$sm_no[]=$row[0];
			$sm_del_approve_by[]=ucfirst($row[1]);
			$sm_del_approve_date[]=$row[2];
			$sm_del_by[]=ucfirst($row[3]);
			$sm_del_date[]=$row[4];
		}
		$query="SELECT sm.id,itu.sn,up.username,itu.deleted_date FROM inventory_unic_item itu, shipment_main sm, userprofile up WHERE itu.deleted_by=up.id AND itu.shipment_no=sm.id AND sm.`status`!='3' AND itu.`status`=2 AND itu.deleted_date BETWEEN '$from_date' AND '$to_date' $usr_qty";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$itu_sm_no[]=$row[0];
			$itu_sn[]=$row[1];
			$itu_del_by[]=ucfirst($row[2]);
			$itu_del_date[]=$row[3];
		}


	}
	}
}

function getTransAuditLog(){
	global $from_store,$to_store,$from_date,$to_date,$tr_no,$tr_date,$tr_status,$tr_total,$tr_from_user,$tr_remote_user,$tr_from_store,$tr_to_store;
	$from_store=$to_store='';
	$tr_no=array();
	if(isset($_GET['from_date'])){
		$from_date=$_GET['from_date'];
		$to_date=$_GET['to_date'];
	}else{
		$from_date=date("Y-m-d",time()-(60*60*24*30));
		$to_date=dateNow();
	}
	if(isset($_GET['from_store'])){
	$from_store=$_GET['from_store'];
	$to_store=$_GET['to_store'];
	if($from_store!='all') $from_st_qry="tm.from_store='$from_store' AND"; else $from_st_qry="";
	if($to_store!='all') $to_st_qry="tm.to_store='$to_store' AND"; else $to_st_qry="";
		include('config.php');
		$query1="SELECT id,username FROM userprofile";
		$result1=mysqli_query($conn2,$query1);
		while($row1=mysqli_fetch_array($result1)){	$salesman[$row1[0]]=$row1[1]; 	}

		$query="SELECT tm.gtn_no,tm.`date`,tm.`status`,SUM(tr.c_price * tr.qty),tm.`user`,tm.remote_user,st1.name,st2.name FROM transfer_main tm, transfer tr, stores st1, stores st2 WHERE $from_st_qry $to_st_qry tm.gtn_no=tr.gtn_no AND tm.from_store=st1.id AND tm.to_store=st2.id AND date(tm.`date`) BETWEEN '$from_date' AND '$to_date' GROUP BY tm.gtn_no ORDER BY tm.`date`";
		$result=mysqli_query($conn2,$query);
		while($row=mysqli_fetch_array($result)){
			$tr_no[]=$row[0];
			$tr_date[]=$row[1];
			$tr_status[]=getGTNStatus($row[2]);
			$tr_total[]=$row[3];
			if($row[4]!=0) $tr_from_user[]=$salesman[$row[4]];  else $tr_from_user[]='';
			if($row[5]!=0) $tr_remote_user[]=$salesman[$row[5]];  else $tr_remote_user[]='';
			$tr_from_store[]=$row[6];
			$tr_to_store[]=$row[7];
		}
	}
}

function getNewCust(){
	global $from_date,$to_date,$st_id,$st_name,$salesman_id,$up_salesman,$new_cust_count,$new_activecust_count,$store;
	if(isset($_GET['from_date']) && isset($_GET['to_date'])){
		$from_date=$_GET['from_date'];
		$to_date=$_GET['to_date'];
	}else{
		$from_date=date("Y-m-d",time()-(60*60*24*30));
		$to_date=dateNow();
	}

	if(isset($_GET['store'])){
		if($_GET['store']!='all'){
			$store=$_GET['store'];
			$storefilter='AND store='.$store;
		}else $storefilter='';
	}else $storefilter='';

	include('config.php');
	$query="SELECT id,name FROM stores WHERE `status`=1";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$st_id[]=$row[0];
		$st_name[]=$row[1];
	}

	$query1="SELECT id,username FROM userprofile WHERE `status`=0 $storefilter ORDER BY username";
	$result1=mysqli_query($conn2,$query1);
	while($row1=mysqli_fetch_array($result1)){
		$cust_list=array();
		$itemp_up=$row1[0];
		$salesman_id[]=$row1[0];
		$up_salesman[]=$row1[1];
		$result2 = mysqli_query($conn2,"SELECT count(id) as `count` FROM cust WHERE associated_salesman='$itemp_up' AND date(created_timestamp) BETWEEN '$from_date' AND '$to_date'");
		$row2 = mysqli_fetch_assoc($result2);
		$new_cust_count[]=$row2['count'];
		$query2="SELECT DISTINCT cu.id FROM cust cu, bill_main bm WHERE bm.`cust`=cu.id AND cu.associated_salesman='$itemp_up' AND date(cu.created_timestamp) BETWEEN '$from_date' AND '$to_date'";
		$result2=mysqli_query($conn2,$query2);
		while($row2=mysqli_fetch_array($result2)){
			$cust_list[]=$row2[0];
		}
		$new_activecust_count[]=sizeof($cust_list);
	}
}

function getCrLimitAudit(){
	global $from_date,$to_date,$cla_old_limit,$cla_new_limit,$cu_name,$cu_id,$changed_by,$changed_date;
	if(isset($_GET['from_date']) && isset($_GET['to_date'])){
		$from_date=$_GET['from_date'];
		$to_date=$_GET['to_date'];
	}else{
		$from_date=date("Y-m-d",time()-(60*60*24*90));
		$to_date=dateNow();
	}
	$cu_id=array();
	include('config.php');
	$query1="SELECT cla.old_limit,cla.new_limit,cu.name,cu.id,up.username,cla.`timestamp` FROM cust_crlimit_audit cla, cust cu, userprofile up WHERE cla.`cust`=cu.id AND cla.changed_by=up.id AND date(cla.`timestamp`) BETWEEN '$from_date' AND '$to_date'";
	$result1=mysqli_query($conn2,$query1);
	while($row1=mysqli_fetch_array($result1)){
		$cla_old_limit[]=$row1[0];
		$cla_new_limit[]=$row1[1];
		$cu_name[]=$row1[2];
		$cu_id[]=$row1[3];
		$changed_by[]=$row1[4];
		$changed_date[]=$row1[5];
	}
}

// update by nirmal 02_11_2023
function getEditQtyAudit(){
	global $item,$from_date,$to_date,$ie_date,$ie_store,$ie_item,$ie_item_cost,$ie_old_qty,$ie_action_qty,$ie_user,$ie_comment;
	$qry_itm='';
	if(isset($_GET['from_date']) && isset($_GET['to_date'])){
		$from_date=$_GET['from_date'];
		$to_date=$_GET['to_date'];
	}else{
		$from_date=date("Y-m-d",time()-(60*60*24*90));
		$to_date=dateNow();
	}
	if(isset($_GET['item'])){
		$item=$_GET['item'];
		if($item!='') $qry_itm="AND itm.description='$item'";
	}
	$qry_order = "ORDER BY ie.`datetime` DESC";
	$ie_date=array();
	include('config.php');
	$query1="SELECT ie.`datetime`,itm.description,st.name,ie.item_cost,ie.old_qty,ie.action_qty,up.username,ie.`comment` FROM inventory_edit ie, inventory_items itm, userprofile up, stores st WHERE ie.item=itm.id AND ie.edit_by=up.id AND ie.store=st.id AND date(ie.`datetime`) BETWEEN '$from_date' AND '$to_date' $qry_itm $qry_order";
	$result1=mysqli_query($conn2,$query1);
	while($row1=mysqli_fetch_array($result1)){
		$ie_date[]=$row1[0];
		$ie_item[]=$row1[1];
		$ie_store[]=$row1[2];
		$ie_item_cost[]=$row1[3];
		$ie_old_qty[]=$row1[4];
		$ie_action_qty[]=$row1[5];
		$ie_user[]=$row1[6];
		$ie_comment[]=$row1[7];
	}
}

function getLoginAudit(){
	global $user,$from_date,$to_date,$lo_user,$lo_date,$lo_time,$lo_device;
	$qry_user='';
	$lo_user=$lo_date=array();
	if(isset($_GET['from_date']) && isset($_GET['to_date'])){
		$from_date=$_GET['from_date'];
		$to_date=$_GET['to_date'];
	}else{
		$from_date=dateNow();
		$to_date=$from_date;
	}
	if(isset($_GET['user'])){
		$user=$_GET['user'];
		if($user!='') $qry_user="AND up.id='$user'";
	}
	$ie_date=array();
	include('config.php');
	$query1="SELECT up.username,la.login_time,dv.`name` FROM userprofile up, login_audit la LEFT JOIN devices dv ON la.device=dv.id WHERE la.`user`=up.id AND (date(la.login_time) BETWEEN '$from_date' AND '$to_date') $qry_user";
	$result1=mysqli_query($conn2,$query1);
	while($row1=mysqli_fetch_array($result1)){
		$lo_user[]=$row1[0];
		$lo_date[]=substr($row1[1],0,10);
		$lo_time[]=substr($row1[1],11,5);
		$lo_device[]=$row1[2];
	}
}

function getBillEditAudit(){
	global $user,$from_date,$to_date,$ie_user,$ie_act_date,$ie_invoice,$ie_ori_date,$ie_new_date,$ie_ori_sm,$ie_new_sm,$ie_ori_rg,$ie_new_rg;
	$qry_user='';
	$user_arr=$ie_user=$lo_user=$lo_date=array();
	if(isset($_GET['from_date']) && isset($_GET['to_date'])){
		$from_date=$_GET['from_date'];
		$to_date=$_GET['to_date'];
	}else{
		$from_date=dateNow();
		$to_date=$from_date;
	}
	if(isset($_GET['user'])){
		$user=$_GET['user'];
		if($user!='') $qry_user="AND ie.changed_by='$user'";
	}
	$ie_date=array();
	include('config.php');
	$user_arr['']='';
	$query1="SELECT id,username FROM userprofile";
	$result1=mysqli_query($conn2,$query1);
	while($row1=mysqli_fetch_array($result1)){
		$user_arr[$row1[0]]=$row1[1];
	}

	$query1="SELECT ie.changed_by,ie.changed_time,ie.invoice_no,ie.original_date,date(ie.changed_date),ie.original_salesman,ie.changed_salesman,ie.original_rec_agent,ie.changed_rec_agent FROM audit_inv_date ie WHERE DATE(ie.changed_time) BETWEEN '$from_date' AND '$to_date' $qry_user";
	$result1=mysqli_query($conn2,$query1);
	while($row1=mysqli_fetch_array($result1)){
		$ie_user[]=$user_arr[$row1[0]];
		$ie_act_date[]=$row1[1];
		$ie_invoice[]=$row1[2];
		$ie_ori_date[]=$row1[3];
		$ie_new_date[]=$row1[4];
		$ie_ori_sm[]=$user_arr[$row1[5]];
		$ie_new_sm[]=$user_arr[$row1[6]];
		$ie_ori_rg[]=$user_arr[$row1[7]];
		$ie_new_rg[]=$user_arr[$row1[8]];
	}
}

function getPayEditAudit(){
	global $user,$from_date,$to_date,$ie_user,$ie_invoice,$ie_ori_date,$ie_new_date,$ie_ori_sm,$ie_new_sm,$ie_act_date;
	$qry_user='';
	$ie_user=$lo_user=$lo_date=array();
	if(isset($_GET['from_date']) && isset($_GET['to_date'])){
		$from_date=$_GET['from_date'];
		$to_date=$_GET['to_date'];
	}else{
		$from_date=dateNow();
		$to_date=$from_date;
	}
	if(isset($_GET['user'])){
		$user=$_GET['user'];
		if($user!='') $qry_user="AND ie.changed_by='$user'";
	}
	$ie_date=array();
	include('config.php');
	$user_arr['']='';
	$query1="SELECT id,username FROM userprofile";
	$result1=mysqli_query($conn2,$query1);
	while($row1=mysqli_fetch_array($result1)){
		$user_arr[$row1[0]]=$row1[1];
	}

	$query1="SELECT ie.changed_by,ie.payment_no,ie.original_date,ie.original_salesman,ie.changed_salesman,date(ie.changed_date),ie.changed_time FROM audit_pay_date ie WHERE DATE(ie.changed_time) BETWEEN '$from_date' AND '$to_date' $qry_user";
	$result1=mysqli_query($conn2,$query1);
	while($row1=mysqli_fetch_array($result1)){
		$ie_user[]=$user_arr[$row1[0]];
		$ie_invoice[]=$row1[1];
		$ie_ori_date[]=$row1[2];
		$ie_ori_sm[]=$user_arr[$row1[3]];
		$ie_new_sm[]=$user_arr[$row1[4]];
		$ie_new_date[]=$row1[5];
		$ie_act_date[]=$row1[6];
	}
}

function getCategoryProfit(){
	global $subsys,$from_date,$to_date,$category,$c_price,$s_price,$iprofit;
	$subsys_qry=$tags_qry="";
	$category=array();
	include('config.php');

	$subsys=$_GET['subsys'];
	if($subsys!="all") $subsys_qry="AND bm.sub_system='$subsys'";
	if(isset($_GET['from_date']) && isset($_GET['to_date'])){
		$from_date=$_GET['from_date'];
		$to_date=$_GET['to_date'];
	}else{
		$from_date=date("Y-m-d",time()-(60*60*24*30));
		$to_date=dateNow();
	}
	if(isset($_GET['tags'])){
		$tag_selection=$_GET['tag_selection'];
		$tags_req=$_GET['tags'];
		if($tags_req!=''){
			$tags_list="tn.tag IN ('".str_replace("|","','",$tags_req)."')";
			$tags_list2='';
			$tmp_arr=array();
			$tmp_arr=explode("|",$tags_req);
			$match_count=sizeof($tmp_arr);
			if($tag_selection==1) $query="SELECT ta.item FROM tag_name tn, tag_assignment ta WHERE tn.id=ta.tag AND $tags_list";
			if($tag_selection==2) $query="SELECT ta.item,count(ta.tag) FROM tag_assignment ta, tag_name tn WHERE ta.tag=tn.id AND $tags_list GROUP BY ta.item HAVING count(ta.tag)>=$match_count";
			$result=mysqli_query($conn2,$query);
			while($row=mysqli_fetch_array($result)){
				$tags_list2.=$row[0].',';
			}
			if($tags_list2!=''){
				$tags_list2=substr($tags_list2, 0, -1);
				$tags_qry='AND itm.id IN ('.$tags_list2.')';
			}else{
				$tags_qry="AND itm.id IN ('')";
			}
		}
	}

	$query="SELECT itc.`name`,SUM(bi.qty * bi.cost),SUM(bi.qty * bi.unit_price) FROM bill_main bm, bill bi, inventory_items itm, item_category itc WHERE bm.invoice_no=bi.invoice_no AND bi.item=itm.id AND itm.category=itc.id AND bm.`status` NOT IN (0,7) AND bm.`lock`=1 AND date(bm.billed_timestamp) BETWEEN '$from_date' AND '$to_date' $tags_qry $subsys_qry GROUP BY itc.id ORDER BY itc.`name`";
	$result=mysqli_query($conn2,$query);
	while($row=mysqli_fetch_array($result)){
		$category[]=$row[0];
		$c_price[]=$row[1];
		$s_price[]=$row[2];
		$iprofit[]=$row[2]-$row[1];
	}
}

//-------------------------------------------- Salesman Commissions --------------------------------------------//

// added by nirmal 08_02_2022
function getSMCommission(){
	global $inv_no,$inv_total, $cust, $salesman, $salesman_id, $sm_commission, $comission_total, $sm_commission_arr, $sm_list;
	$inv_no = $inv_total = $cust = $salesman = $sm_commission = $comission_total = $salesman_id = $sm_commission_arr = $sm_list =  array();

	$order_qry = '';
	$invoice_total = $pay_amount = 0.0;
	$user_qry= '';
	if (isset($_GET['sm_order'])) $order_qry = "ORDER BY up.username";
	$components = $_REQUEST['components'];
	if($components == 'billing' || $components == 'bill2'){
		$user=$_COOKIE['user_id'];
		$user_qry="AND up.`id`='$user'";
	}
	include('config.php');

	$query = "SELECT bm.`invoice_no`, bm.`invoice_+total` + bm.`invoice_-total`, cust.`name`,up.`username` FROM bill_main bm, cust, userprofile up WHERE bm.`com_repo` is null AND cust.`id` = bm.`cust`  AND bm.`billed_by` = up.`id` AND bm.`status` != '0' $order_qry $user_qry";
	$result = mysqli_query($conn2, $query);

	while ($row = mysqli_fetch_array($result)) {
		if($row[1] != '0'){
			$invoice_total = (double) $row[1];
			$query1 = "SELECT  py.`payment_type`, py.`chque_clear`, py.`amount`, py.`chque_return` FROM payment py, bill_main bm WHERE bm.`invoice_no` = py.`invoice_no`  AND py.`invoice_no` = '$row[0]' AND py.`status`='0'";
			$result1 = mysqli_query($conn2, $query1);

			$pay_amount = 0.0;
			while ($row1 = mysqli_fetch_array($result1)) {
				if($row1[0] == 2){ // cheque
					if($row1[1] == 1 && $row1[3] != 1){ // deposited, not returned
						$pay_amount += (double) $row1[2];
					}
				}else{
					$pay_amount += (double) $row1[2];
				}
			}
			if($invoice_total == $pay_amount){
				$query2 = "SELECT DISTINCT ROUND(SUM((bi.`unit_price` * itm.`commision` * bi.`qty`)/100),2) FROM bill bi, bill_main bm, inventory_items itm WHERE itm.`id` = bi.`item` AND bi.`invoice_no` = bm.`invoice_no`  AND bm.`invoice_no` = '$row[0]' GROUP BY bm.`invoice_no`";
				$result2 = mysqli_query($conn2, $query2);
				$row2 = mysqli_fetch_row($result2);
				$inv_no[] = $row[0];
				$inv_total[] = $invoice_total;
				$cust[] = $row[2];
				$salesman[] = $row[3];
				$sm_commission[] = $row2[0];
			}
		}
	}
	$sm_list = array_values(array_unique($salesman));
	for ($i = 0; $i < sizeof($salesman); $i++) {
		$sm_commission_arr[$salesman[$i]] = 0;
	}
}

// added by nirmal 08_02_2022
function smGenerateCommission(){
	global $message;
	$month = $_POST['month'] . '-01';
	$sm_selected = $_POST['sm_selected'];
	$user_id = $_COOKIE['user_id'];
	$store = $_COOKIE['store'];
	$today = dateNow();
	$time_now = timeNow();
	$invoice_total = $pay_amount = 0.0;
	$sm_data_arr = $inv_no = array();
	if ($sm_selected != '')	$sm_data_arr = explode("|", substr($sm_selected, 0, -1));
	$message = 'Commission Report was Generated Successfully';
	$out = true;
	include('config.php');

	$query = "INSERT INTO `commission_main` (`month`,`generated_by`,`generated_date`) VALUES ('$month','$user_id','$today')";
	$result = mysqli_query($conn, $query);
	$com_no = mysqli_insert_id($conn);
	if (!$result) {
		$out = false;
		$message = 'Error: Failed to Create the Report';
	}

	if($out){
		for ($i = 0; $i < sizeof($sm_data_arr); $i++) {
			$sm_arr = explode(",", $sm_data_arr[$i]);
			$sm = $sm_arr[0];
			$total = $sm_arr[1];
			$query = "SELECT up.id FROM userprofile up WHERE up.username='$sm'";
			$row = mysqli_fetch_row(mysqli_query($conn2, $query));
			$sm_id = $row[0];
			$query = "INSERT INTO `commission_pay` (`com_main`,`salesman`,`amount`) VALUES ('$com_no','$sm_id','$total')";
			$result = mysqli_query($conn, $query);
			if (!$result) {
				$out = false;
				$message = 'Error: Failed to Insert SM Commission Data';
			}
		}
	}
	if($out){
		for ($i = 0; $i < sizeof($sm_data_arr); $i++) {
			$sm_arr = explode(",", $sm_data_arr[$i]);
			$query = "SELECT up.id FROM userprofile up WHERE up.`username`='$sm_arr[0]'";
			$row = mysqli_fetch_row(mysqli_query($conn2, $query));

			$query1 = "SELECT bm.`invoice_no`, bm.`invoice_+total` + bm.`invoice_-total`, cust.`name`,up.`username` FROM bill_main bm, cust, userprofile up WHERE bm.`com_repo` is null AND cust.`id` = bm.`cust`  AND bm.`billed_by` = '$row[0]' AND bm.`status` != '0'";
			$result1 = mysqli_query($conn2, $query1);

			while ($row1 = mysqli_fetch_array($result1)) {
				if(($row[1] != '0') && (substr($row[1], 0, 1) != '-')){
					$invoice_total = (double) $row1[1];
					$query2 = "SELECT  py.`payment_type`, py.`chque_clear`, py.`amount`, py.`chque_return` FROM payment py, bill_main bm WHERE bm.`invoice_no` = py.`invoice_no`  AND py.`invoice_no` = '$row1[0]' AND py.`status`='0'";
					$result2 = mysqli_query($conn2, $query2);

					$pay_amount = 0.0;
					while ($row2 = mysqli_fetch_array($result2)) {
						if($row2[0] == 2){ // cheque
							if($row2[1] == 1 && $row2[3] != 1){ // deposited, not returned
								$pay_amount += (double) $row2[2];
							}
						}else{
							$pay_amount += (double) $row2[2];
						}
					}

					if($invoice_total == $pay_amount){
						$query = "UPDATE bill_main bm SET bm.`com_repo`='$com_no' WHERE bm.`invoice_no` = '$row1[0]'";
						$result = mysqli_query($conn, $query);
						if (!$result) {
							$out = false;
							$message = 'Error: Failed to Update Salesman Commission Data';
							break;
						}
					}
				}
			}

		}
	}
	return $out;
}

// added by nirmal 08_02_2022
function getSMCommissionList(){
	global $com_id, $com_month, $com_gen_date, $com_gen_by;
	$com_id = $com_month = $com_gen_date = $com_gen_by = array();
	$user_qry = $user_tbl = $tbl_qry = '';
	$components = $_REQUEST['components'];
	if($components == 'billing' || $components == 'bill2'){
		$user=$_COOKIE['user_id'];
		$user_qry="AND cp.`salesman`='$user'";
		$user_tbl = ", commission_pay cp";
		$tbl_qry = "AND cm.`id` = cp.`com_main`";
	}
	include('config.php');
	$query = "SELECT cm.`id`,cm.`month`,cm.`generated_date`,up.`username` FROM commission_main cm, userprofile up $user_tbl WHERE cm.`generated_by`=up.`id` $tbl_qry $user_qry ORDER BY cm.`month` DESC, cm.`id` DESC";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$com_id[] = $row[0];
		$com_month[] = substr($row[1], 0, 7);
		$com_gen_date[] = $row[2];
		$com_gen_by[] = $row[3];
	}
}

// added by nirmal 08_02_2022
function getSMCommissionOne(){
	global $invoice_no, $sm_commission, $salesman, $commission, $cust, $sm_commission_arr, $commission_amount, $cm_salesman, $cm_salesman_id, $cm_id, $com_sm_amo, $cm_month, $delete_permission, $sm_commission_arr1;
	$invoice_no = $sm_commission = $salesman = $commission = $cust = $sm_commission_arr = $commission_amount  = $cm_salesman = $cm_salesman_id = $sm_commission_arr1 = array();

	$rp_id = $_GET['id'];
	$user_qry = $co_user = '';
	$components = $_REQUEST['components'];
	if(isset($_GET['user'])) $co_user=$_GET['user'];
	if($co_user!=''){ $user_qry="AND up.`id`='$co_user'"; }
	if($components == 'billing' || $components == 'bill2'){
		$user=$_COOKIE['user_id'];
		$user_qry="AND up.`id`='$user'";
	}
	$today = dateNow();

	include('config.php');
	$query = "SELECT DISTINCT bm.`invoice_no`, cust.`name`, up.`username`, ROUND(SUM((bi.`unit_price` * itm.`commision` * bi.`qty`)/100),2) FROM bill bi, bill_main bm, inventory_items itm, userprofile up, cust WHERE itm.`id` = bi.`item` AND bi.`invoice_no` = bm.`invoice_no` AND cust.`id` = bm.`cust` AND up.`id` = bm.`billed_by` AND bm.`invoice_no` IN(SELECT bm.`invoice_no` FROM bill_main bm WHERE bm.`com_repo` = '$rp_id' $user_qry) GROUP BY bm.`invoice_no`";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$invoice_no[] = $row[0];
		$cust[] = $row[1];
		$salesman[] = $row[2];
		$commission[] = $row[3];
	}

	$query = "SELECT cp.`amount`, cp.`salesman`, up.`username`, cm.`id`, cm.`month`, cm.`generated_date` FROM commission_main cm, commission_pay cp, userprofile up WHERE cp.`com_main` = cm.`id` AND up.`id` = cp.`salesman` AND cm.`id` = '$rp_id' $user_qry";
	$result = mysqli_query($conn2, $query);
	$com_sm_amo;
	while ($row = mysqli_fetch_array($result)) {
		$commission_amount[] = $row[0];
		$com_sm_amo += $row[0];
		$cm_salesman_id[] = $row[1];
		$cm_salesman[] = $row[2];
		$cm_id = $row[3];
		$cm_month = substr($row[4], 0, 7);
		$cm_gen_date = $row[5];
	}
	if ($cm_gen_date == $today) $delete_permission = true;
	else $delete_permission = false;

	for ($i = 0; $i < sizeof($salesman); $i++) {
		$sm_commission_arr[$salesman[$i]] = 0;
	}

	for ($i = 0; $i < sizeof($cm_salesman); $i++) {
		$sm_commission_arr1[$cm_salesman[$i]] = $commission_amount[$i];
	}

}

// added by nirmal 10_02_2022
function smDeleteCommission($force){
	global $message;
	$com_no = $_GET['id'];
	$user_id = $_COOKIE['user_id'];
	$today = dateNow();
	$components = $_REQUEST['components'];
	$out = true;
	$message = 'Commission Report was Deleted Successfully';
	include('config.php');

	$query = "SELECT cm.`month`,cm.`generated_date`,cm.`generated_by` FROM commission_main cm WHERE cm.id='$com_no'";
	$row = mysqli_fetch_row(mysqli_query($conn2, $query));
	$cm_month = substr($row[0], 0, 7);
	$cm_gen_date = $row[1];
	$cm_user = $row[2];

	if($components == 'report'){
		if ($force == 0) {
			if ($cm_gen_date != $today) {
				$out = false;
				$message = 'Error: Report can only be Deleted within the day';
			}
			if ($user_id != $cm_user) {
				$out = false;
				$message = 'Error: Report can only be Deleted by the Creator of the Report';
			}
		}

		//Salesman Record Delete --------------------------------------------

		if ($out) {
			$query = "UPDATE bill_main bm SET bm.`com_repo`=null WHERE bm.`com_repo`='$com_no'";
			$result = mysqli_query($conn, $query);
			if (!$result) {
				$out = false;
				$message = 'Error: Failed to Update Salesman Commission Data';
			}
		}

		// Commission Table Data Delete --------------------------------------------
		if ($out) {
			$query = "DELETE FROM commission_pay WHERE com_main = '$com_no'";
			$result = mysqli_query($conn, $query);
			if (!$result) {
				$out = false;
				$message = 'Error: Failed to Delete Commission Pay Records';
			}
			if($out){
				$query = "DELETE FROM commission_main WHERE id='$com_no'";
				$result = mysqli_query($conn, $query);
				if (!$result) {
					$out = false;
					$message = 'Error: Failed to Delete Commission Main Records';
				}
			}
		}
	}
	return $out;
}

// added by nirmal 04_04_2022
function getSMCommissionIncomplete(){
	global $inv_no,$inv_total, $cust, $salesman, $salesman_id, $sm_commission, $comission_total, $sm_commission_arr, $sm_list, $reason, $paid_amount;
	$inv_no = $inv_total = $cust = $salesman = $sm_commission = $comission_total = $salesman_id = $sm_commission_arr = $sm_list = $reason = $paid_amount =  array();

	$order_qry = $result1 = $user = $user_qry= '';
	$invoice_total = $pay_amount = 0.0;
	$flag=0;

	$components = $_REQUEST['components'];
	if (isset($_GET['sm_order'])) $order_qry = "ORDER BY up.username";
	if (isset($_GET['user'])){
		$user = $_GET['user'];
		$user_qry = "AND up.`id`='$user'";
	}
	if($components == 'billing' || $components == 'bill2'){
		$user=$_COOKIE['user_id'];
		$user_qry="AND up.`id`='$user'";
	}
	include('config.php');

	$query = "SELECT bm.`invoice_no`, bm.`invoice_+total` + bm.`invoice_-total`, cust.`name`,up.`username`, up.`id` FROM bill_main bm, cust, userprofile up WHERE bm.`com_repo` is null AND cust.`id` = bm.`cust`  AND bm.`billed_by` = up.`id` AND bm.`status` != '0' AND bm.`com_repo` is null $order_qry $user_qry";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$invoice_total = (double) $row[1];
		$pay_amount = 0.0;
		$flag=0;

		if(($row[1] != '0') && (substr($row[1], 0, 1) != '-')){

			$query1 = "SELECT  py.`payment_type`, py.`chque_clear`, py.`amount`, py.`chque_return` FROM payment py, bill_main bm WHERE bm.`invoice_no` = py.`invoice_no`  AND py.`invoice_no` = '$row[0]' AND py.`status`='0'";
			$result1 = mysqli_query($conn2, $query1);

			while ($row1 = mysqli_fetch_array($result1)) {
				if($row1[0] == 2){ // cheque
					if(($row1[1] == 1) && ($row1[3] != 1)){ // deposited, not returned
						$pay_amount += (double) $row1[2];
					}else if(($row1[1] == 0) && ($row1[3] != 1)){ // not deposited, not returned
						$pay_amount += (double) $row1[2];
						$flag=1;
					}else if(($row1[1] == 1) && ($row1[3] != 1)){ // deposited, return
						$pay_amount += (double) $row1[2];
						$flag=2;
					}
				}else{
					$pay_amount += (double) $row1[2];
				}
			}

			if($pay_amount == 0.00){
				$reason[] = 'Not Paid';
			}else if($flag == 1){
				$reason[] = 'Cheque Pending';
			}else if($flag == 2){
				$reason[] = 'Cheque Return';
			}else if($invoice_total == $pay_amount){
				$reason[] = 'Commission Not Paid';
			}else if($invoice_total < $pay_amount){
				$reason[] = 'Over Paid';
			}else{
				$reason[] = 'Partially Paid';
			}
			$paid_amount[] = $pay_amount;
			$inv_no[] = $row[0];
			$inv_total[] = $invoice_total;
			$cust[] = $row[2];
			$salesman[] = $row[3];
			$salesman_id[] = $row[4];
		}
	}
}

//--------------------------------------------Hirepurchase--------------------------------------------------------------//
function getExceededPendingPayments(){
	global $warning_date1, $warning_date2, $warning_date3, $dd_hp_schedule, $dd_rec_ag_id, $dd_rec_ag_name, $dd_inv, $dd_instalment, $dd_py_schedule_date, $dd_hp_type, $salesman_filter, $filter_rec_agent, $filter_type, $de_rec_sch, $de_rec_instdate;
	$dd_hp_schedule = $dd_rec_ag_id = $dd_rec_ag_name = $dd_inv = $dd_instalment = $dd_py_schedule_date = $dd_hp_type = $de_rec_sch = $de_rec_instdate = array();
	$filter_rec_agent = $_GET['rec_agent'];
	$filter_type = $_GET['type'];
	$qry_fil_rec = $qry_fil_type = '';
	if ($filter_rec_agent != 'all') $qry_fil_rec = "AND up.username='$filter_rec_agent'";
	if ($filter_type != 'all') $qry_fil_type = "AND hst.`name`='$filter_type'";

	include('config.php');
	$result = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='timezone'");
	$row = mysqli_fetch_assoc($result);
	$timezone = $row['value'];
	$today = date("Y-m-d", time() + (60 * 60 * $timezone));

	$warning_date1 = date("Y-m-d", time() + (60 * 60 * $timezone) - (90 * 24 * 60 * 60));
	$warning_date2 = date("Y-m-d", time() + (60 * 60 * $timezone) - (21 * 24 * 60 * 60));
	$warning_date3 = date("Y-m-d", time() + (60 * 60 * $timezone) - (3 * 24 * 60 * 60));


	$query = "SELECT his.id,his.invoice_no,his.payment_amount,up.id,up.username,his.cal_start_date,hst.`name`,his.`day`,his.payment_count FROM hp_inv_schedule his, hp_schedule_type hst, bill_main bm, userprofile up WHERE his.`type`=hst.id AND his.invoice_no=bm.invoice_no AND bm.recovery_agent=up.id AND his.`status`='1' $qry_fil_rec $qry_fil_type";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$hs_id = $row[0];
		$hs_invoice_no = $row[1];
		$hs_instalment_amount = $row[2];
		$rec_arent_id = $row[3];
		$rec_arent_name = $row[4];
		$hp_cal_start = $row[5];
		$hp_type = $row[6];
		$hp_date = $row[7];
		$hp_count = $row[8];

		$hp_schedule = array();
		$hp_schedule = hpsPaySchedule($hp_cal_start, $hp_type, $hp_date, $hp_count);

		$hp_pay_arr = array();
		$query2 = "SELECT instalment_date FROM hp_payments WHERE `schedule`='$hs_id'";
		$result2 = mysqli_query($conn2, $query2);
		while ($row2 = mysqli_fetch_array($result2)) {
			$hp_pay_arr[] = $row2[0];
		}

		for ($i = 1; $i <= sizeof($hp_schedule); $i++) {
			$hp_schedule_date = $hp_schedule[$i];
			if ($hp_schedule_date < $today) {
				$key = array_search($hp_schedule[$i], $hp_pay_arr);
				if ($key > -1) {
					//	print $key;
				} else {
					if ($hp_type == 'Monthly') $warning_date = $warning_date1;
					if ($hp_type == 'Weekly') $warning_date = $warning_date2;
					if ($hp_type == 'Daily') $warning_date = $warning_date3;

					if ($hp_schedule_date < $warning_date) {
						$dd_rec_ag_id[] = $rec_arent_id;
						$dd_rec_ag_name[] = $rec_arent_name;
						$dd_inv[] = $hs_invoice_no;
						$dd_instalment[] = $hs_instalment_amount;
						$dd_py_schedule_date[] = $hp_schedule_date;
						$dd_hp_type[] = $hp_type;
						$dd_hp_schedule[] = $hs_id;

						//	print $hs_invoice_no.' - '.$hp_schedule_date.' - '.$warning_date.' - '.$hp_type.'<br />';
					}
				}
			}
		}
	}
	$rec_agent_filter = array_unique($dd_rec_ag_name);
	$salesman_filter = array_values($rec_agent_filter);

	$query = "SELECT schdule_id,instalment_date FROM hp_rec_ag_deduct WHERE `status`='1'";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$de_rec_sch[] = $row[0];
		$de_rec_instdate[] = $row[1];
	}
}

function addDeduction(){
	$schedule = $_POST['schedule'];
	$inst_date = $_POST['inst_date'];
	$user_id = $_COOKIE['user_id'];
	$today = dateNow();
	$msg = 'done';
	$out = true;

	include('config.php');
	$query = "SELECT COUNT(id) FROM hp_rec_ag_deduct WHERE schdule_id='$schedule' AND `instalment_date`='$inst_date'";
	$row = mysqli_fetch_row(mysqli_query($conn2, $query));
	if ($row[0] > 0) {
		$out = false;
		$msg = 'Error: Record is Already Added';
	}

	if ($out) {
		$query = "INSERT INTO `hp_rec_ag_deduct` (`schdule_id`,`instalment_date`,`added_date`,`added_by`,`status`) VALUES ('$schedule','$inst_date','$today','$user_id','1')";
		$result = mysqli_query($conn, $query);
		if (!$result) {
			$out = false;
			$msg = 'Error: Failed to Add to Database';
		}
	}

	return $msg;
}

function removeDeduction(){
	$schedule = $_POST['schedule'];
	$inst_date = $_POST['inst_date'];
	$msg = 'done';
	$out = true;

	include('config.php');
	$query = "SELECT COUNT(id) FROM hp_rec_ag_deduct WHERE schdule_id='$schedule' AND `instalment_date`='$inst_date'";
	$row = mysqli_fetch_row(mysqli_query($conn2, $query));
	if ($row == 0) {
		$out = false;
		$msg = 'Error: Record Not Found';
	}

	if ($out) {
		$query = "DELETE FROM `hp_rec_ag_deduct` WHERE `schdule_id`='$schedule' AND `instalment_date`='$inst_date' AND `status`='1'";
		$result = mysqli_query($conn, $query);
		if (!$result) {
			$out = false;
			$msg = 'Error: Failed to Remove from Database';
		}
	}

	return $msg;
}

function getHPCommission(){
	global $sm_rate, $rg_rate, $sm_order, $rg_order, $his_id, $sm_late_deduct, $rg_late_deduct, $his_inv, $his_cust, $his_sm, $his_rg, $his_bill_total, $his_hp_total, $his_sm_pay, $his_rg_pay, $deduct_his_id, $deduct_his_inv, $deduct_his_cust, $deduct_his_sm, $deduct_his_sm, $deduct_his_rg, $deduct_inst_date, $deduct_py_amount, $sm_list, $rg_list, $sm_commission_arr, $rg_commission_arr;
	$sm_order = $rg_order = false;
	$his_sm = $his_rg = $sm_list = $rg_list = $sm_commission_arr = $rg_commission_arr = $deduct_his_id = $deduct_his_sm = $deduct_his_rg = array();
	$order_qry = '';
	if (isset($_GET['sm_order'])) {
		$sm_order = true;
		$order_qry = "ORDER BY up1.username";
	}
	if (isset($_GET['rg_order'])) {
		$rg_order = true;
		$order_qry = "ORDER BY up2.username";
	}

	$sm_late_deduct = -100;
	$rg_late_deduct = -150;

	include('config.php');
	$query = "SELECT `value` FROM settings WHERE setting='hp_sm_commission'";
	$row = mysqli_fetch_row(mysqli_query($conn2, $query));
	$sm_rate = $row[0];
	$query = "SELECT `value` FROM settings WHERE setting='hp_rg_commission'";
	$row = mysqli_fetch_row(mysqli_query($conn2, $query));
	$rg_rate = $row[0];

	$query = "SELECT his.id,his.invoice_no,cu.`name`,up1.username,up2.username,(bm.`invoice_+total` + bm.`invoice_-total`), (his.payment_amount * his.payment_count),his.sm_pay,his.rg_pay FROM hp_inv_schedule his, bill_main bm, cust cu, userprofile up1, userprofile up2 WHERE his.invoice_no=bm.invoice_no AND bm.`cust`=cu.id AND bm.billed_by=up1.id AND bm.recovery_agent=up2.id AND his.`status`='0' AND bm.`status`!=0 AND ( his.rg_pay='0' OR his.sm_pay='0' ) $order_qry";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$his_id[] = $row[0];
		$his_inv[] = $row[1];
		$his_cust[] = $row[2];
		$his_sm[] = $row[3];
		$his_rg[] = $row[4];
		$his_bill_total[] = $row[5];
		$his_hp_total[] = $row[6];
		$his_sm_pay[] = $row[7];
		$his_rg_pay[] = $row[8];
	}
	$query = "SELECT his.id,bm.invoice_no,cu.`name`,up1.username,up2.username,hra.instalment_date,his.payment_amount FROM hp_rec_ag_deduct hra, hp_inv_schedule his, bill_main bm, userprofile up1, userprofile up2, cust cu WHERE hra.schdule_id=his.id AND his.invoice_no=bm.invoice_no AND bm.billed_by=up1.id AND bm.recovery_agent=up2.id AND bm.`cust`=cu.id AND hra.`status`='1' AND bm.`status`!='0' $order_qry";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$deduct_his_id[] = $row[0];
		$deduct_his_inv[] = $row[1];
		$deduct_his_cust[] = $row[2];
		$deduct_his_sm[] = $row[3];
		$deduct_his_rg[] = $row[4];
		$deduct_inst_date[] = $row[5];
		$deduct_py_amount[] = $row[6];
	}

	$sm_list = array_values(array_unique(array_merge($his_sm, $deduct_his_sm)));
	$rg_list = array_values(array_unique(array_merge($his_rg, $deduct_his_rg)));

	for ($i = 0; $i < sizeof($sm_list); $i++) {
		$sm_commission_arr[$sm_list[$i]] = 0;
	}
	for ($i = 0; $i < sizeof($rg_list); $i++) {
		$rg_commission_arr[$rg_list[$i]] = 0;
	}
}

function hpGenerateCommission($sub_system){
	global $message;
	$month = $_POST['month'] . '-01';
	$sm_selected = $_POST['sm_selected'];
	$rg_selected = $_POST['rg_selected'];
	$user_id = $_COOKIE['user_id'];
	$store = $_COOKIE['store'];
	$today = dateNow();
	$time_now = timeNow();
	$sm_data_arr = $rg_data_arr = array();
	if ($sm_selected != '')	$sm_data_arr = explode("|", substr($sm_selected, 0, -1));
	if ($rg_selected != '')	$rg_data_arr = explode("|", substr($rg_selected, 0, -1));
	$message = 'Commission Report was Generated Successfully';
	$out = true;
	include('config.php');

	$query = "SELECT `value` FROM settings WHERE setting='hp_sm_commission'";
	$row = mysqli_fetch_row(mysqli_query($conn2, $query));
	$sm_rate = $row[0];
	$query = "SELECT `value` FROM settings WHERE setting='hp_rg_commission'";
	$row = mysqli_fetch_row(mysqli_query($conn2, $query));
	$rg_rate = $row[0];

	$query = "INSERT INTO `hp_commission_main` (`month`,`generated_by`,`generated_date`) VALUES ('$month','$user_id','$today')";
	$result = mysqli_query($conn, $query);
	$com_no = mysqli_insert_id($conn);
	if (!$result) {
		$out = false;
		$message = 'Error: Failed to Create the Report';
	}

	for ($i = 0; $i < sizeof($sm_data_arr); $i++) {
		$sm_arr = explode(",", $sm_data_arr[$i]);
		$sm = $sm_arr[0];
		$total = $sm_arr[1];
		$query = "SELECT count(his.id),up.id FROM hp_inv_schedule his, bill_main bm, userprofile up WHERE his.invoice_no=bm.invoice_no AND bm.billed_by=up.id AND his.`status`='0' AND bm.`status`!=0 AND his.sm_pay='0' AND up.username='$sm'";
		$row = mysqli_fetch_row(mysqli_query($conn2, $query));
		$count = $row[0];
		$sm_id = $row[1];
		if ($total != ($count * $sm_rate)) {
			$out = false;
			$message = 'Error: Salesman Commission Calculation Failed';
		}

		if ($out) {
			$query = "UPDATE hp_inv_schedule his, bill_main bm SET his.sm_pay='$com_no' WHERE his.invoice_no=bm.invoice_no AND his.`status`='0' AND bm.`status`!=0 AND his.sm_pay='0' AND bm.billed_by='$sm_id'";
			$result = mysqli_query($conn, $query);
			if (!$result) {
				$out = false;
				$message = 'Error: Failed to Update Salesman Commission Data';
			}
		}
		if ($out) {
			$query = "INSERT INTO `hp_commission_data` (`com_main`,`sm_ag`,`type`,`amount`) VALUES ('$com_no','$sm_id','1','$total')";
			$result = mysqli_query($conn, $query);
			if (!$result) {
				$out = false;
				$message = 'Error: Failed to Insert SM Commission Data';
			}
		}
	}

	for ($i = 0; $i < sizeof($rg_data_arr); $i++) {
		$rg_arr = explode(",", $rg_data_arr[$i]);
		$rg = $rg_arr[0];
		$total = $rg_arr[1];
		$query = "SELECT count(his.id),up.id FROM hp_inv_schedule his, bill_main bm, userprofile up WHERE his.invoice_no=bm.invoice_no AND bm.recovery_agent=up.id AND his.`status`='0' AND bm.`status`!=0 AND his.rg_pay='0' AND up.username='$rg'";
		$row = mysqli_fetch_row(mysqli_query($conn2, $query));
		$count = $row[0];
		$rg_id = $row[1];
		$query = "SELECT SUM(his.payment_amount) FROM hp_rec_ag_deduct hra, hp_inv_schedule his, bill_main bm WHERE hra.schdule_id=his.id AND his.invoice_no=bm.invoice_no AND hra.`status`='1' AND bm.recovery_agent='$rg_id'";
		$row = mysqli_fetch_row(mysqli_query($conn2, $query));
		$deduct = $row[0];
		$commission = ($count * $rg_rate) - $deduct;

		if ($total != $commission) {
			$out = false;
			$message = 'Error: Recovery Agent Commission Calculation Failed';
		}

		if ($out) {
			$query1 = "SELECT hra.id,his.id,bm.invoice_no,his.payment_amount,hra.instalment_date FROM hp_rec_ag_deduct hra, hp_inv_schedule his, bill_main bm WHERE hra.schdule_id=his.id AND his.invoice_no=bm.invoice_no AND hra.`status`='1' AND bm.recovery_agent='$rg_id'";
			$result1 = mysqli_query($conn2, $query1);
			while ($row1 = mysqli_fetch_array($result1)) {
				$hra_id = $row1[0];
				$his_id = $row1[1];
				$invoice_no = $row1[2];
				$pay_amount = $row1[3];
				$inst_date = $row1[4];

				if ($out) {
					$comment = "Recovery commission deduction from " . ucfirst($rg) . " due to late payment for the instalment date of " . $inst_date;
					$query2 = "INSERT INTO `payment` (`invoice_no`,`bill_pay`,`cust`,`payment_type`,`amount`,`salesman`,`sys_user`,`payment_date`,`comment`,`store`,`gps_x`,`gps_y`,`sub_system`) VALUES ('$invoice_no','2','1','1','$pay_amount','$user_id','$user_id','$time_now','$comment','$store','0','0','$sub_system')";
					$result2 = mysqli_query($conn, $query2);
					$pay_id = mysqli_insert_id($conn);
					if (!$result2) {
						$out = false;
						$message = 'Error: Failed to Add the Payment';
					}
				}

				if ($out) {
					$query2 = "INSERT INTO `hp_payments` (`invoice_no`,`payment_no`,`schedule`,`instalment_date`,`late_pay`) VALUES ('$invoice_no','$pay_id','$his_id','$inst_date','1')";
					$result2 = mysqli_query($conn, $query2);
					if (!$result2) {
						$out = false;
						$message = 'Error: Failed to Add The Instalment Payment';
					}
				}
				if ($out) {
					$query2 = "UPDATE hp_rec_ag_deduct hra SET hra.processed_date='$today', hra.`status`='2', hra.process_id='$com_no' WHERE hra.`status`='1' AND hra.id='$hra_id'";
					$result2 = mysqli_query($conn, $query2);
					if (!$result2) {
						$out = false;
						$message = 'Error: Failed to Update Recovery Agent Commission Deduction Data';
					}
				}
			}
		}
		if ($out) {
			$query = "UPDATE hp_inv_schedule his, bill_main bm SET his.rg_pay='$com_no' WHERE his.invoice_no=bm.invoice_no AND his.`status`='0' AND bm.`status`!=0 AND his.rg_pay='0' AND bm.recovery_agent='$rg_id'";
			$result = mysqli_query($conn, $query);
			if (!$result) {
				$out = false;
				$message = 'Error: Failed to Update Recovery Agent Commission Data';
			}
		}
		if ($out) {
			$query = "INSERT INTO `hp_commission_data` (`com_main`,`sm_ag`,`type`,`amount`) VALUES ('$com_no','$rg_id','2','$total')";
			$result = mysqli_query($conn, $query);
			if (!$result) {
				$out = false;
				$message = 'Error: Failed to Insert RG Commission Data';
			}
		}
	}

	return $out;
}

function getHPCommissionList(){
	global $hc_id, $hc_month, $hc_gen_date, $hc_gen_by;
	$hc_id = array();
	include('config.php');
	$query = "SELECT hc.id,hc.`month`,hc.generated_date,up.username FROM hp_commission_main hc, userprofile up WHERE hc.generated_by=up.id ORDER BY hc.`month` DESC, hc.id DESC";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$hc_id[] = $row[0];
		$hc_month[] = substr($row[1], 0, 7);
		$hc_gen_date[] = $row[2];
		$hc_gen_by[] = $row[3];
	}
}

function getHPCommissionOne(){
	global $hc_id, $delete_permission, $hc_month, $sm_rate, $rg_rate, $his_sm_id, $his_sm_invoice, $his_sm_status, $his_sm_sm, $his_sm_cust, $his_rg_id, $his_rg_invoice, $his_rg_status, $his_rg_rg, $his_rg_cust, $hra_hra_id, $hra_his_id, $hra_invoice, $hra_his_status, $hra_rg_rg, $hra_rg_cust, $hra_rg_amo, $sm_commission_arr, $rg_commission_arr, $com_sm_did, $com_sm_id, $com_sm_name, $hra_sm_amo, $com_rg_did, $com_rg_id, $com_rg_name, $com_sm_amo, $com_rg_amo;
	$his_sm_id = $his_rg_id = $his_sm_sm = $his_rg_rg = $hra_hra_id = $hra_rg_rg = $sm_commission_arr = $rg_commission_arr = $com_sm_did = $com_rg_did = array();
	$com_sm_amo = $com_rg_amo = array();
	$co_user=$user_qry='';

	$hc_id = $_GET['id'];
	if(isset($_GET['user'])) $co_user=$_GET['user'];
	if($co_user!=''){ $user_qry="AND up.id='$co_user'"; }
	$today = dateNow();

	include('config.php');

	$query = "SELECT `value` FROM settings WHERE setting='hp_sm_commission'";
	$row = mysqli_fetch_row(mysqli_query($conn2, $query));
	$sm_rate = $row[0];
	$query = "SELECT `value` FROM settings WHERE setting='hp_rg_commission'";
	$row = mysqli_fetch_row(mysqli_query($conn2, $query));
	$rg_rate = $row[0];

	$query = "SELECT hc.`month`,hc.generated_date,up.username FROM hp_commission_main hc, userprofile up WHERE hc.generated_by=up.id AND hc.id='$hc_id'";
	$row = mysqli_fetch_row(mysqli_query($conn2, $query));
	$hc_month = substr($row[0], 0, 7);
	$hc_gen_date = $row[1];
	$hc_gen_by = $row[2];

	if ($hc_gen_date == $today) $delete_permission = true;
	else $delete_permission = false;

	$query = "SELECT his.id,bm.invoice_no,his.`status`,up.username,cu.`name` FROM hp_inv_schedule his, bill_main bm, userprofile up, cust cu WHERE his.invoice_no=bm.invoice_no AND bm.billed_by=up.id AND bm.`cust`=cu.id AND his.sm_pay='$hc_id' $user_qry ORDER BY up.username";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$his_sm_id[] = $row[0];
		$his_sm_invoice[] = $row[1];
		$his_sm_status[] = $row[2];
		$his_sm_sm[] = $row[3];
		$his_sm_cust[] = $row[4];
	}

	$query = "SELECT his.id,bm.invoice_no,his.`status`,up.username,cu.name FROM hp_inv_schedule his, bill_main bm, userprofile up, cust cu WHERE his.invoice_no=bm.invoice_no AND bm.recovery_agent=up.id AND bm.`cust`=cu.id AND his.rg_pay='$hc_id' $user_qry ORDER BY up.username";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$his_rg_id[] = $row[0];
		$his_rg_invoice[] = $row[1];
		$his_rg_status[] = $row[2];
		$his_rg_rg[] = $row[3];
		$his_rg_cust[] = $row[4];
	}
	$query = "SELECT hra.id,his.id,bm.invoice_no,his.`status`,up.username,cu.`name`,his.payment_amount FROM hp_rec_ag_deduct hra, hp_inv_schedule his, bill_main bm, cust cu, userprofile up WHERE hra.schdule_id=his.id AND his.invoice_no=bm.invoice_no AND bm.`cust`=cu.id AND bm.recovery_agent=up.id AND bm.`status`!=0 AND hra.process_id='$hc_id' $user_qry";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$hra_hra_id[] = $row[0];
		$hra_his_id[] = $row[1];
		$hra_invoice[] = $row[2];
		$hra_his_status[] = $row[3];
		$hra_rg_rg[] = $row[4];
		$hra_rg_cust[] = $row[5];
		$hra_rg_amo[] = $row[6];
	}

	for ($i = 0; $i < sizeof($his_sm_sm); $i++) {
		$sm_commission_arr[$his_sm_sm[$i]] = 0;
	}
	for ($i = 0; $i < sizeof($his_rg_rg); $i++) {
		$rg_commission_arr[$his_rg_rg[$i]] = 0;
	}

	$query = "SELECT hd.id,up.id,up.username,hd.amount FROM hp_commission_main hm, hp_commission_data hd, userprofile up WHERE hm.id=hd.com_main AND hd.sm_ag=up.id AND hd.`type`='1' AND hm.id='$hc_id' $user_qry";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$com_sm_did[] = $row[0];
		$com_sm_id[] = $row[1];
		$com_sm_name[] = $row[2];
		$com_sm_amo[] = $row[3];
	}

	$query = "SELECT hd.id,up.id,up.username,hd.amount FROM hp_commission_main hm, hp_commission_data hd, userprofile up WHERE hm.id=hd.com_main AND hd.sm_ag=up.id AND hd.`type`='2' AND hm.id='$hc_id' $user_qry";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$com_rg_did[] = $row[0];
		$com_rg_id[] = $row[1];
		$com_rg_name[] = $row[2];
		$com_rg_amo[] = $row[3];
	}
}

function hpDeleteCommission($force)
{
	global $message;
	$com_no = $_GET['id'];
	$user_id = $_COOKIE['user_id'];
	$today = dateNow();
	$out = true;
	$message = 'Commission Report was Deleted Successfully';

	if ($force == 0) include('config.php');
	if ($force == 1) include('../config.php');

	$query = "SELECT hc.`month`,hc.generated_date,hc.generated_by FROM hp_commission_main hc WHERE hc.id='$com_no'";
	$row = mysqli_fetch_row(mysqli_query($conn2, $query));
	$hc_month = substr($row[0], 0, 7);
	$hc_gen_date = $row[1];
	$hc_user = $row[2];

	if ($force == 0) {
		if ($hc_gen_date != $today) {
			$out = false;
			$message = 'Error: Report can only be Deleted within the day';
		}
		if ($user_id != $hc_user) {
			$out = false;
			$message = 'Error: Report can only be Deleted by the Creator of the Report';
		}
	}

	//Salesman Record Delete--------------------------------------------
	if ($out) {
		$query = "UPDATE hp_inv_schedule his SET his.sm_pay='0' WHERE his.sm_pay='$com_no'";
		$result = mysqli_query($conn, $query);
		if (!$result) {
			$out = false;
			$message = 'Error: Failed to Update Salesman Commission Data';
		}
	}

	//Recovery Agent Record Delete--------------------------------------
	if ($out) {
		$query = "UPDATE hp_inv_schedule his SET his.rg_pay='0' WHERE his.rg_pay='$com_no'";
		$result = mysqli_query($conn, $query);
		if (!$result) {
			$out = false;
			$message = 'Error: Failed to Update Salesman Commission Data';
		}
	}

	if ($out) {
		$query = "SELECT hra.id,his.id,hra.instalment_date FROM hp_rec_ag_deduct hra, hp_inv_schedule his WHERE hra.schdule_id=his.id AND hra.`status`='2' AND hra.process_id='$com_no'";
		$result = mysqli_query($conn2, $query);
		while ($row = mysqli_fetch_array($result)) {
			$hra_id = $row[0];
			$his_id = $row[1];
			$his_inst_date = $row[2];

			$query2 = "SELECT id,payment_no FROM hp_payments WHERE `schedule`='$his_id' AND instalment_date='$his_inst_date'";
			$row2 = mysqli_fetch_row(mysqli_query($conn2, $query2));
			$hpy_id = $row2[0];
			$py_id = $row2[1];

			if ($out) {
				$query2 = "DELETE FROM hp_payments WHERE id='$hpy_id'";
				$result2 = mysqli_query($conn, $query2);
				if (!$result2) {
					$out = false;
					$message = 'Error: Failed to Delete A Payment Instalment Record';
				}
			}
			if ($out) {
				$query2 = "DELETE FROM payment WHERE id='$py_id'";
				$result2 = mysqli_query($conn, $query2);
				if (!$result2) {
					$out = false;
					$message = 'Error: Failed to Delete A Payment Record';
				}
			}
			if ($out) {
				$query2 = "UPDATE `hp_rec_ag_deduct` SET `process_id`=NULL , `processed_date`=NULL , `status`='1' WHERE id='$hra_id'";
				$result2 = mysqli_query($conn, $query2);
				if (!$result2) {
					$out = false;
					$message = 'Error: Failed to cleanup a deduction record';
				}
			}
		}
	}


	if ($out) {
		$query = "DELETE FROM `hp_commission_data` WHERE com_main='$com_no'";
		$result = mysqli_query($conn, $query);
		if (!$result) {
			$out = false;
			$message = 'Error: Failed to Delete Commission Data';
		}
	}
	if ($out) {
		$query = "DELETE FROM `hp_commission_main` WHERE id='$com_no'";
		$result = mysqli_query($conn, $query);
		if (!$result) {
			$out = false;
			$message = 'Error: Failed to Delete Commission Main';
		}
	}
	return $out;
}

?>