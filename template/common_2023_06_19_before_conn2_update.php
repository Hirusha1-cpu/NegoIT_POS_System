<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function isMobile()
{
	return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}

// added by nirmal 10_03_2023
function getCurrency($method){
	if ($method == 1)	include('config.php');
	if ($method == 2)	include('../../../../config.php');
	$result = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='currency'");
	$row = mysqli_fetch_assoc($result);
	return $row['value'];
}

// added by nirmal 17_02_2023
function getDistrictName($method){
	$district_name = '';
	if ($method == 1)	include('config.php');
	if ($method == 2)	include('../../../../config.php');
	if(isset($_COOKIE['district'])){
		$current_district=$_COOKIE['district'];
		$query="SELECT `name` FROM `district` WHERE `id` = '$current_district'";
		$row = mysqli_fetch_row(mysqli_query($conn2, $query));
		$district_name = $row[0];
	}
	return $district_name;
}

// added by nirmal 21_12_17
function stringAndNumberValidation($str){
 	if (preg_match('/^[a-zA-Z0-9]+$/', $str)) {
   		return true;
 	} else {
   		return false;
	}
}

// added by nirmal 14_02_2022
function commissionOnBilling(){
	// Products wise commission calculation
	if(!isset($_COOKIE['commission_on_billing'])){
		include('config.php');
		$result = mysqli_query($conn,"SELECT `value` FROM settings WHERE setting='commission_on_billing'");
		$row = mysqli_fetch_assoc($result);
		setcookie("commission_on_billing",$row['value'], time()+3600*10);
	}
}

// added by nirmal 02_03_2022
function custDetailsonOnBilling(){
	// Customer basic details showing to salesman, but some restrictions are there, filter quering limited
	if(!isset($_COOKIE['cus_details_on_billing'])){
		include('config.php');
		$result = mysqli_query($conn,"SELECT `value` FROM settings WHERE setting='cus_details_on_billing'");
		$row = mysqli_fetch_assoc($result);
		setcookie("cus_details_on_billing",$row['value'], time()+3600*10);
	}
}

// added by nirmal 25_05_2022
function custDOBonOnManager(){
	// Customer basic details + dob showing to manager
	if(!isset($_COOKIE['cust_dob_on_manager'])){
		include('config.php');
		$result = mysqli_query($conn,"SELECT `value` FROM settings WHERE setting='cust_dob_on_manager'");
		$row = mysqli_fetch_assoc($result);
		setcookie("cust_dob_on_manager",$row['value'], time()+3600*10);
	}
}

// added by nirmal 26_05_2022
function custDOBSMSsent(){

	include('config.php');
	$result = mysqli_query($conn,"SELECT `value` FROM settings WHERE setting='cust_dob_on_manager'");
	$row = mysqli_fetch_assoc($result);

	if(($row['value'] == 1) && ($_SERVER['SERVER_NAME']==inf_url_primary())){
		// sms content
		$result = mysqli_query($conn,"SELECT `value` FROM settings WHERE setting='cust_dob_sms_content'");
		$row = mysqli_fetch_assoc($result);
		$sms_content = $row['value'];

		// current date
		$date = substr(dateNow(), 5);

		// select sub system
		$query = "SELECT `id` FROM sub_system WHERE `status`='1'";
		$result = mysqli_query($conn2, $query);
		while ($row = mysqli_fetch_array($result)) {
			// sms credentials
			$sub_system=$row[0];
			$sms_data=json_decode(sms_credential($sub_system));
			$sms_user=$sms_data->{"user"};
			$sms_pass=$sms_data->{"pass"};
			$sms_balance=$sms_data->{"balance"};
			$sms_device=$sms_data->{"device"};

			if($sms_balance > 0){
				$query = "SELECT cu.`name`, cu.`mobile`, s.`shop_name_sms` FROM cust cu, stores s WHERE cu.`associated_store` = s.`id` AND date_format(cu.`dob`, '%m-%d') = '$date' AND cu.`status`='1' AND s.`status`='1'";
				$result = mysqli_query($conn2, $query);
				while ($row1 = mysqli_fetch_array($result)) {
					// sms body
					$cust_name = $row1[0];
					$cust_mobile = $row1[1];
					$store_name = $row1[2];
					$message = str_replace("[Client Name]",$cust_name,$sms_content).'-NLC-'.'-NLC-'.'-NLC-'.$store_name;

					// sms send
					$text = urlencode($message);
					if($sms_device==""){
						$url = "http://www.textit.biz/sendmsg/?id=$sms_user&pw=$sms_pass&eco=Y&to=$cust_mobile&text=$text";
						$ret = file($url);
						$res= explode(":",$ret[0]);
						if (trim($res[0])=="OK") $mailstatus=true; else $mailstatus=false;
					}else{
						//$url = "http://mqtt.negoit.info/sms_gw.php?dev=$sms_device&ref1=bill&ref2=$invoice_no&u=$sms_user&p=$sms_pass&to=$cust_mobile&text=$text";
						setcookie("sms_balance",$sms_balance, time()+3600*10);
						file($url);
						$mailstatus=false;
					}
					if($mailstatus){
						$sms_balance--;
						if(set_sms_balance($sub_system,$sms_balance))	$msg='SMS Sent<hr />';
						else $msg='Database Cound Not be Updated<hr />';
					}else $msg='SMS Could not be Sent<hr />';
				}
			}
		}
	}
}

function timeNow()
{
	include('config.php');
	$result = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='timezone'");
	$row = mysqli_fetch_assoc($result);
	$timezone = $row['value'];
	$time_now = date("Y-m-d H:i:s", time() + (60 * 60 * $timezone));
	return $time_now;
}

function dateNow()
{
	include('config.php');
	$result = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='timezone'");
	$row = mysqli_fetch_assoc($result);
	$timezone = $row['value'];
	$date_now = date("Y-m-d", time() + (60 * 60 * $timezone));
	return $date_now;
}

function passwordExpire()
{
	include('config.php');
	$user_id = $_COOKIE['user_id'];
	$result = mysqli_query($conn2, "SELECT change_pw FROM userprofile WHERE `id`='$user_id'");
	$row = mysqli_fetch_assoc($result);
	if ($row['change_pw'] == 1) $change_pw = true;
	else $change_pw = false;
	return $change_pw;
}

function unicCal()
{
	include('config.php');
	$result = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='uniq_item_cal'");
	$row = mysqli_fetch_assoc($result);
	if ($row['value'] == 'True') $out = true;
	else $out = false;
	return $out;
}

function weekday($action)
{
	switch ($action) {
		case 1:
			$action_out = 'Sunday';
			break;
		case 2:
			$action_out = 'Monday';
			break;
		case 3:
			$action_out = 'Tuesday';
			break;
		case 4:
			$action_out = 'Wednesday';
			break;
		case 5:
			$action_out = 'Thursday';
			break;
		case 6:
			$action_out = 'Friday';
			break;
		case 7:
			$action_out = 'Saturday';
			break;
	}
	return $action_out;
}

function paymentType($type_id){
	switch ($type_id){
		case 1:
			$jasonArray["name"]='Cash';
			$jasonArray["color"]='#009900';
		break;
		case 2:
			$jasonArray["name"]='Bank';
			$jasonArray["color"]='#00AAAA';
		break;
		case 3:
			$jasonArray["name"]='Cheque';
			$jasonArray["color"]='blue';
		break;
		case 4:
			$jasonArray["name"]='Credit Card';
			$jasonArray["color"]='#FF9900';
		break;
	}
	$myJSON = json_encode($jasonArray);
	return $myJSON;
}

function hpsPaySchedule($cal_start_date, $hp_type, $hp_date, $hp_count)
{
	$hp_schedule = array();
	$start_date = substr($cal_start_date, 0, -2) . $hp_date;

	if ($hp_type == 'Monthly') {
		for ($i = 1; $i <= $hp_count; $i++) {
			$hp_schedule[$i] = date('Y-m-d', strtotime('+' . $i . ' month', strtotime($start_date)));
		}
	}
	if ($hp_type == 'Weekly') {
		$day = weekday($hp_date);
		for ($i = 1; $i <= $hp_count; $i++) {
			$hp_schedule[$i] = date('Y-m-d', strtotime('+' . $i . ' week ' . $day, strtotime($cal_start_date)));
		}
	}
	if ($hp_type == 'Daily') {
		for ($i = 1; $i <= $hp_count; $i++) {
			$hp_schedule[$i] = date('Y-m-d', strtotime('+' . $i . ' day', strtotime($cal_start_date)));
		}
	}
	return $hp_schedule;
}

function listCust($sub_system)
{
	global $data_list, $fn;
	$data_list = array();

	if ($_POST['keyword']) {

		$keyword = $_POST['keyword'];

		if ($_GET['action'] == 'cust-list-one_time') {
			$qry = "`name` LIKE '%$keyword%'";
			$fn = 'selectCust';
			$in = '1,2';
		}
		if ($_GET['action'] == 'cust-list-wholesale') {
			$qry = "`name` LIKE '%$keyword%'";
			$fn = 'selectCust';
			$in = "1,3";
		}
		if ($_GET['action'] == 'cust-list') {
			$qry = "`name` LIKE '%$keyword%'";
			$fn = 'selectCust';
			$in = "1,2";
		}
		if ($_GET['action'] == 'cust-list2') {
			$qry = "`name` LIKE '%$keyword%'";
			$fn = 'selectCust2';
			$in = "1,2";
		}
		if ($_GET['action'] == 'nick-list') {
			$qry = "`nickname` LIKE '%$keyword%'";
			$fn = 'selectNick';
			$in = "1,2";
		}
		if ($_GET['action'] == 'mob-list') {
			$qry = "`mobile` LIKE '$keyword%'";
			$fn = 'selectMob';
			$in = "1,2";
		}
		if ($_GET['action'] == 'nic-list') {
			$qry = "`nic` LIKE '%$keyword%'";
			$fn = 'selectNic';
			$in = "1,2";
		}

		include('config.php');

		$query = "SELECT `name`,`nickname`,`mobile`,`nic` FROM cust WHERE `sub_system`='$sub_system' AND `status` IN ($in) AND $qry LIMIT 20";


		$result = mysqli_query($conn, $query);

		while ($row = mysqli_fetch_array($result)) {
			if ($_GET['action'] == 'cust-list') $data_list[] = $row[0];
			if ($_GET['action'] == 'cust-list-one_time') $data_list[] = $row[0];
			if ($_GET['action'] == 'cust-list-wholesale') $data_list[] = $row[0];
			if ($_GET['action'] == 'cust-list2') $data_list[] = $row[0];
			if ($_GET['action'] == 'nick-list') $data_list[] = $row[1];
			if ($_GET['action'] == 'mob-list') $data_list[] = $row[2];
			if ($_GET['action'] == 'nic-list') $data_list[] = $row[3];
		}
	}
}

function listTag($sub_system){
	global $data_list, $fn;
	$data_list = array();
	$fn='selectTag';
	if ($_POST['keyword']) {
		$keyword = $_POST['keyword'];
		include('config.php');
		$query = "SELECT `tag` FROM tag_name WHERE `tag` LIKE '%$keyword%' LIMIT 20";
		$result = mysqli_query($conn, $query);
		while ($row = mysqli_fetch_array($result)) {
			$data_list[] = $row[0];
		}
	}
}

function getRecoveryAgent($sub_system){
global $rag_id,$rag_name;
	$rag_id=array();
	include('config.php');
	$query="SELECT DISTINCT up.id,up.username FROM userprofile up, permission pe, `function` fn WHERE up.id=pe.`user` AND pe.`function`=fn.id AND up.`sub_system`='$sub_system' AND up.`status`='0' AND fn.`status`=1 AND fn.`name`='Hire Purchase' ORDER BY up.username";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$rag_id[]=$row[0];
		$rag_name[]=$row[1];
	}
}

function listSM($sub_system)
{
	global $data_list, $fn;
	$data_list = array();
	$fn = 'selectSM';
	if ($_POST['keyword']) {
		$keyword = $_POST['keyword'];
		include('config.php');
		$query = "SELECT DISTINCT up.username FROM userprofile up, permission pe, `function` fn WHERE up.id=pe.`user` AND pe.`function`=fn.id AND up.`sub_system`='$sub_system' AND up.`status`='0' AND fn.`status`=1 AND (fn.`name`='Bill2' OR fn.`name`='Billing') AND up.username LIKE '%$keyword%' LIMIT 20";
		$result = mysqli_query($conn, $query);
		while ($row = mysqli_fetch_array($result)) {
			$data_list[] = ucfirst($row[0]);
		}
	}
}

// function moreCust($sub_system)
// {
// 	if (isset($_POST['case'])) {
// 		$case = $_POST['case'];
// 		$val = $_POST['val'];
// 		$jasonArray = array();
// 		if ($case == 'name') $qry = "`name`='$val'";
// 		if ($case == 'nick') $qry = "`nickname`='$val'";
// 		if ($case == 'mob') $qry = "`mobile`='$val'";
// 		include('config.php');
// 		$query = "SELECT id,`name`,`mobile` FROM cust WHERE `sub_system`='$sub_system' AND `status` IN (1,2) AND $qry LIMIT 1";
// 		$row = mysqli_fetch_row(mysqli_query($conn, $query));
// 		$jasonArray["cust_id"] = $row[0];
// 		$jasonArray["cust_name"] = $row[1];
// 		$jasonArray["cust_mobile"] = $row[2];
// 		$myJSON = json_encode($jasonArray);

// 		return $myJSON;
// 	}
// }

function moreCust($sub_system)
{
	if (isset($_POST['case'])) {
		$case = $_POST['case'];
		$val = $_POST['val'];
		$jasonArray = array();
		if ($case == 'name') $qry = "`name`='$val'";
		if ($case == 'nick') $qry = "`nickname`='$val'";
		if ($case == 'mob') $qry = "`mobile`='$val'";
		if ($case == 'nic') $qry = "`nic`='$val'";
		include('config.php');
		$query = "SELECT id,`name`,`mobile` FROM cust WHERE `sub_system`='$sub_system' AND `status` IN (1,2) AND $qry LIMIT 1";
		$row = mysqli_fetch_row(mysqli_query($conn, $query));
		$jasonArray["cust_id"] = $row[0];
		$jasonArray["cust_name"] = $row[1];
		$jasonArray["cust_mobile"] = $row[2];
		$myJSON = json_encode($jasonArray);

		return $myJSON;
	}
}

function moreSm($sub_system)
{
	$val = $_POST['val'];
	include('config.php');
	$query = "SELECT id FROM userprofile WHERE username='$val'";
	$row = mysqli_fetch_row(mysqli_query($conn, $query));
	$data = $row[0];
	return $data;
}

function listItem($sub_system){
	global $data_list, $fn;
	$data_list = array();
	if ($_POST['keyword']) {
		$keyword = str_replace("'", "", $_POST['keyword']);
		$item_filter = $_GET['item_filter'];
		$item_type = $_GET['item_type'];
		$inf_systemid = inf_systemid(1);
		if ($_GET['action'] == 'code-list') {
			$qry = "`code` LIKE '%$keyword%'";
			$fn = 'selectCode';
		}
		if ($_GET['action'] == 'desc-list') {
			$qry = "`description` LIKE '%$keyword%'";
			$fn = 'selectDesc';
		}

		if ($item_filter == '') {
			$item_filter_qry = '';
		}
		if ($item_filter == 1) {
			$item_filter_qry = "AND pr_sr IN (1,2)";
		}
		if ($item_filter == 2) {
			$item_filter_qry = "AND pr_sr IN (1,2)";
		}
		if ($item_filter == 3) {
			$item_filter_qry = "AND pr_sr='3'";
		}

		if ($item_type == 'all') {
			$item_type_qry = "";
		} else {
			$item_type_qry = "AND `unic`='$item_type'";
		}

		include('config.php');
		if ($inf_systemid == 1 || $inf_systemid == 17)
			$query = "SELECT DISTINCT `code`,`description` FROM inventory_items WHERE `status`='1' AND $qry $item_filter_qry $item_type_qry LIMIT 20";
		else
			$query = "SELECT DISTINCT `code`,`description` FROM inventory_items WHERE `sub_system`='$sub_system' AND `status`='1' AND $qry $item_filter_qry $item_type_qry LIMIT 20";
		$result = mysqli_query($conn, $query);
		while ($row = mysqli_fetch_array($result)) {
			if ($_GET['action'] == 'code-list') $data_list[] = $row[0];
			if ($_GET['action'] == 'desc-list') $data_list[] = $row[1];
		}
	}
}

function salesCommission()
{
	include('config.php');
	$user_id = $_COOKIE['user_id'];
	$factor = 'itm.commision';
	$from_date = date("Y-m", time());
	if (isset($_COOKIE['direct_mkt'])) {
		if ($_COOKIE['direct_mkt'] == 1) $factor = 'itm.min_w_rate';
	}
	$result = mysqli_query($conn2, "SELECT SUM((bi.qty * bi.unit_price * $factor )/100) as `commision` FROM inventory_items itm, bill_main bm, bill bi WHERE bm.invoice_no=bi.invoice_no AND bi.item=itm.id AND itm.commision>0 AND bm.`status`=5 AND bm.`lock`=1 AND bm.billed_timestamp LIKE '$from_date%' AND bm.billed_by='$user_id'");
	$row = mysqli_fetch_assoc($result);
	return round($row['commision']);
}

function subscription(){
	if(isset($_SESSION['subscription'])){
		return $_SESSION['subscription'];
	}else{
		include('config.php');
		$result = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='subscription_start'");
		$row = mysqli_fetch_assoc($result);
		$subscription_start = $row['value'];
		$result = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='subscription_duration'");
		$row = mysqli_fetch_assoc($result);
		$subscription_duration = $row['value'];
		$timestamp_start = strtotime($subscription_start);
		$timestamp_nest = $timestamp_start + $subscription_duration * 24 * 60 * 60;
		$timestamp_gap = $timestamp_nest - time();
		$subscription_end = round($timestamp_gap / 60 / 60 / 24);
		$_SESSION['subscription'] = $subscription_end;
		return $subscription_end;
	}

}

function subsystemTheme($sub_system, $store, $force){
	if(isset($_SESSION['subsystemTheme'])){
		return $_SESSION['subsystemTheme'];
	}else{
		$jasonArray = array();
		$theme_color = $theme_color_m1 = $theme_color_m2 = '';
		if (isset($_COOKIE['theme_color']) && isset($_COOKIE['theme_color_m1']) && isset($_COOKIE['theme_color_m2']) && (!$force)) {
			$jasonArray["theme_color"] = $_COOKIE['theme_color'];
			$jasonArray["theme_color_m1"] = $_COOKIE['theme_color_m1'];
			$jasonArray["theme_color_m2"] = $_COOKIE['theme_color_m2'];
		} else {
			include('config.php');
			if ($store != 0 || $store != '') {
				$query = "SELECT theme_color,theme_color_m1,theme_color_m2 FROM stores WHERE id='$store'";
				$result = mysqli_query($conn2, $query);
				$row = mysqli_fetch_assoc($result);
				$theme_color = $row['theme_color'];
				$theme_color_m1 = $row['theme_color_m1'];
				$theme_color_m2 = $row['theme_color_m2'];
				//	print $theme_color;
			}
			if ($theme_color == '') {
				$result = mysqli_query($conn2, "SELECT theme_color,theme_color_m1,theme_color_m2 FROM sub_system WHERE id='$sub_system'");
				$row = mysqli_fetch_assoc($result);
				$theme_color = $row['theme_color'];
				$theme_color_m1 = $row['theme_color_m1'];
				$theme_color_m2 = $row['theme_color_m2'];
			}
			$jasonArray["theme_color"] = $theme_color;
			$jasonArray["theme_color_m1"] = $theme_color_m1;
			$jasonArray["theme_color_m2"] = $theme_color_m2;
			setcookie("theme_color", $theme_color, time() + 3600 * 10);
			setcookie("theme_color_m1", $theme_color_m1, time() + 3600 * 10);
			setcookie("theme_color_m2", $theme_color_m2, time() + 3600 * 10);
		}
		$myJSON = json_encode($jasonArray);
		$_SESSION['subsystemTheme'] = $myJSON;
		return $myJSON;
	}
}

function inf_country($method)
{
	if ($method == 1)	include('config.php');
	if ($method == 2)	include('../../../../config.php');
	$result = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='country'");
	$row = mysqli_fetch_assoc($result);
	return $row['value'];
}
function inf_systemid($method){
	if(isset($_SESSION['inf_systemid'])){
		return $_SESSION['inf_systemid'];
	}else{
		if ($method == 1)	include('config.php');
		if ($method == 2)	include('../../../../config.php');
		$result = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='systemid'");
		$row = mysqli_fetch_assoc($result);
		$_SESSION['inf_systemid'] = $row['value'];
		return $row['value'];
	}
}
function inf_company($method){
	if(isset($_SESSION['inf_company'])){
		return $_SESSION['inf_company'];
	}else{
		if ($method == 1)	include('config.php');
		if ($method == 2)	include('../../../../config.php');
		$result = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='company_name'");
		$row = mysqli_fetch_assoc($result);
		$_SESSION['inf_company'] = $row['value'];
		return $row['value'];
	}
}
function inf_from_email()
{
	include('config.php');
	$result = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='from_email'");
	$row = mysqli_fetch_assoc($result);
	return $row['value'];
}
function inf_replyto_email()
{
	include('config.php');
	$result = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='reply_to_email'");
	$row = mysqli_fetch_assoc($result);
	return $row['value'];
}
function inf_to_email()
{
	include('config.php');
	$result = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='to_email'");
	$row = mysqli_fetch_assoc($result);
	return $row['value'];
}
function inf_web(){
	include('config.php');
	$result = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='web'");
	$row = mysqli_fetch_assoc($result);
	return $row['value'];
}
function inf_url_primary(){
	if(isset($_SESSION['inf_url_primary'])){
		return $_SESSION['inf_url_primary'];
	}else{
		include('config.php');
		$result = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='url_primary'");
		$row = mysqli_fetch_assoc($result);
		$_SESSION["inf_url_primary"] = $row['value'];
		return $row['value'];
	}
}
function inf_url_backup(){
	if(isset($_SESSION['inf_url_backup'])){
		return $_SESSION['inf_url_backup'];
	}else{
		include('config.php');
		$result = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='url_backup'");
		$row = mysqli_fetch_assoc($result);
		$_SESSION["inf_url_backup"] = $row['value'];
		return $row['value'];
	}

}
function decimal_paces(){
	if(isset($_SESSION['decimal_paces'])){
		return $_SESSION['decimal_paces'];
	}else{
		include('config.php');
		$result = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='decimal'");
		$row = mysqli_fetch_assoc($result);
		$_SESSION["decimal_paces"] = $row['value'];
		return $row['value'];
	}
}
function sms_credential($sub_system){
	$jasonArray = array();
	include('config.php');
	$result = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='sms_data'");
	$row = mysqli_fetch_assoc($result);
	$sms_data = $row['value'];
	if ($sms_data == 'setting') {
		$result = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='sms_user'");
		$row = mysqli_fetch_assoc($result);
		$jasonArray["user"] = $row['value'];
		$result = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='sms_pass'");
		$row = mysqli_fetch_assoc($result);
		$jasonArray["pass"] = $row['value'];
		$result = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='sms_balance'");
		$row = mysqli_fetch_assoc($result);
		$jasonArray["balance"] = $row['value'];
		$result = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='sms_dev'");
		$row = mysqli_fetch_assoc($result);
		$jasonArray["device"] = $row['value'];
	} else {
		$result = mysqli_query($conn2, "SELECT sms_user,sms_pass,sms_balance,sms_dev FROM sub_system WHERE id='$sub_system'");
		$row = mysqli_fetch_assoc($result);
		$jasonArray["user"] = $row['sms_user'];
		$jasonArray["pass"] = $row['sms_pass'];
		$jasonArray["balance"] = $row['sms_balance'];
		$jasonArray["device"] = $row['sms_dev'];
	}
	$myJSON = json_encode($jasonArray);
	return $myJSON;
}
function set_sms_balance($sub_system, $new_balance)
{
	include('config.php');
	$result = mysqli_query($conn, "SELECT value FROM settings WHERE setting='sms_data'");
	$row = mysqli_fetch_assoc($result);
	$sms_data = $row['value'];
	if ($sms_data == 'setting') {
		$query = "UPDATE `settings` SET `value`='$new_balance' WHERE `setting`='sms_balance'";
		$result = mysqli_query($conn, $query);
	} else {
		$query = "UPDATE `sub_system` SET `sms_balance`='$new_balance' WHERE `id`='$sub_system'";
		$result = mysqli_query($conn, $query);
	}
	setcookie("sms_balance", $new_balance, time() + 3600 * 10);
	if ($result) return true;
	else return false;
}
function paper_size($method)
{
	if ($method == 1)	include('config.php');
	if ($method == 2)	include('../../../../config.php');
	$result = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='paper_size'");
	$row = mysqli_fetch_assoc($result);
	return $row['value'];
}

function checkPendingCust($sub_system)
{
	if (isset($_COOKIE['user_id'])) {
		$user = $_COOKIE['user_id'];
		if ($sub_system == 'all') $sub_sys_qry = '';
		else  $sub_sys_qry = "AND `sub_system`='$sub_system'";
		include('config.php');
		$result = mysqli_query($conn2, "SELECT count(id) as `count` FROM cust WHERE `status`='3' $sub_sys_qry");
		$row = mysqli_fetch_assoc($result);
		$pending_count = $row['count'];
		if ($pending_count > 0) return true;
		else return false;
	} else return false;
}
function checkPendingGTN()
{
	if (isset($_COOKIE['user_id'])) {
		$user = $_COOKIE['user_id'];
		include('config.php');
		$result = mysqli_query($conn2, "SELECT COUNT(tm.gtn_no) as `count` FROM transfer_main tm, userprofile up WHERE tm.to_store=up.store AND tm.`status`='0' AND up.id='$user'");
		$row = mysqli_fetch_assoc($result);
		$gtn_count = $row['count'];
		$result = mysqli_query($conn2, "SELECT COUNT(tm.gtn_no) as `count` FROM transfer_main tm, userprofile up WHERE tm.from_store=up.store AND tm.`status`='5' AND up.id='$user'");
		$row = mysqli_fetch_assoc($result);
		$gtn_count += $row['count'];
		if ($gtn_count > 0) return true;
		else return false;
	} else return false;
}
function checkPendingHR($sub_system)
{
	if (isset($_COOKIE['user_id'])) {
		$user = $_COOKIE['user_id'];
		if ($sub_system == 'all') $sub_sys_qry = '';
		else  $sub_sys_qry = "AND `sub_system`='$sub_system'";
		include('config.php');
		$query = "SELECT count(id) as `count` FROM hr_leave_take WHERE `status`='1' $sub_sys_qry";
		$result = mysqli_query($conn2, $query);
		$row = mysqli_fetch_assoc($result);
		$pending_count = $row['count'];
		if ($pending_count > 0) return true;
		else return false;
	} else return false;
}
function timeCheck($userid)
{
	include('config.php');

	$result1 = mysqli_query($conn2, "SELECT time_restrict FROM userprofile WHERE id='$userid'");
	$row = mysqli_fetch_assoc($result1);
	$timecheck = $row['time_restrict'];
	if ($timecheck == 1) {
		$result = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='timezone'");
		$row = mysqli_fetch_assoc($result);
		$timezone = $row['value'];

		$result = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='time_from'");
		$row = mysqli_fetch_assoc($result);
		$time_from = $row['value'];

		$result = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='time_to'");
		$row = mysqli_fetch_assoc($result);
		$time_to = $row['value'];

		$hour_now = date("H", time() + (60 * 60 * $timezone));
		if (($time_from < $hour_now) && ($hour_now < $time_to))	$time_permit = true;
		else $time_permit = false;
	} else $time_permit = true;
	if (!$time_permit) header('Location: index.php?components=authenticate&action=logout&type=1');
}

function deviceCheck($userid)
{
	include('config.php');
	$result1 = mysqli_query($conn2, "SELECT device_restrict FROM userprofile WHERE id='$userid'");
	$row = mysqli_fetch_assoc($result1);
	$devicecheck = $row['device_restrict'];
	if ($devicecheck == 1) {
		$today = date("Y-m-d", time());
		if (isset($_COOKIE['rsaid']))	$rsaid = $_COOKIE['rsaid'];
		else $rsaid = 'hhdjdhdaa44hd';
		$result = mysqli_query($conn2, "SELECT count(dp.id) as `count` FROM devices dv, device_permission dp WHERE dp.device=dv.id AND dv.`key`='$rsaid' AND dv.expiration>'$today' AND dp.`user`='$userid'");
		$row = mysqli_fetch_assoc($result);
		$count = $row['count'];
		if ($count == 0) header('Location: index.php?components=authenticate&action=logout&type=2');
	}
}

function dailyCreditEmail(){
	$inf_url_primary = inf_url_primary();
	if ($inf_url_primary == $_SERVER['SERVER_NAME']) {
		include('config.php');
		$result = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='daily_credit_report'");
		$row = mysqli_fetch_assoc($result);
		$report_sent_on = $row['value'];
		$today = dateNow();
		$inf_company = inf_company(1);
		$inf_from_email = inf_from_email();
		$inf_to_email = inf_to_email();
		$credit_total0 = 0;

		if ($today != $report_sent_on) {
			$result = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='smtp_server'");
			$row = mysqli_fetch_assoc($result);
			$smtp_server = $row['value'];
			$result = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='smtp_port'");
			$row = mysqli_fetch_assoc($result);
			$smtp_port = $row['value'];
			$result = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='smtp_username'");
			$row = mysqli_fetch_assoc($result);
			$smtp_username = $row['value'];
			$result = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='smtp_password'");
			$row = mysqli_fetch_assoc($result);
			$smtp_password = $row['value'];

			dailyInventory();

			$query = "UPDATE settings SET `value`='$today'  WHERE setting='daily_credit_report'";
			$result = mysqli_query($conn, $query);
			if($result) custDOBSMSsent();

			$query = "SELECT DISTINCT bm.`cust`,cu.`name` FROM bill bi, bill_main bm, cust cu WHERE bm.invoice_no=bi.invoice_no AND bm.`cust`=cu.id AND cu.`status`!=0 ORDER BY cu.`name`";
			$result = mysqli_query($conn, $query);
			while ($row = mysqli_fetch_array($result)) {
				$result1 = mysqli_query($conn, "SELECT SUM(bm.`invoice_+total`)+SUM(bm.`invoice_-total`) FROM bill_main bm WHERE bm.`status` NOT IN (0,7) AND bm.`lock`=1 AND bm.exclude=0 AND bm.`cust`='$row[0]'");
				$row1 = mysqli_fetch_row($result1);
				$bill_total = $row1[0];
				$result2 = mysqli_query($conn, "SELECT SUM(py.amount) as `sum` FROM payment py WHERE py.status=0 AND py.chque_return=0 AND py.cust='$row[0]'");
				$row2 = mysqli_fetch_row($result2);
				$payment_total = $row2[0];
				$cr_balance = $bill_total - $payment_total;
				if ($cr_balance != 0) {
					$cust_id[] = $row[0];
					$cust_name[] = $row[1];
					$cust_cr_balance0[] = $cr_balance;
					$credit_total0 += $cr_balance;
				}
			}


			$body = '<tr><td>Date</td><td>' . timeNow() . '</td></tr>
						<tr><td colspan="2"><br/></td></tr>
						</table>
						<table align="center" height="100%" border="1" cellspacing="0" style="font-size:10pt">
						<tr bgcolor="#000000"><td><br/></td><td></td></tr>
						<tr bgcolor="#E5E5E5"><th>Customer</th><th width="100px">Credit Balance</th></tr>';

			for ($i = 0; $i < sizeof($cust_name); $i++) {
				$body .= '<tr bgcolor="#F5F5F5"><td style="padding-right:10px; padding-left:10px;">' . preg_replace('/[^A-Za-z0-9 \-]/', '', ucfirst($cust_name[$i])) . '</td><td align="right" style="padding-right:10px;">' . number_format($cust_cr_balance0[$i]) . '</td></tr>
						';
			}
			$body .= '<tr><th align="left" style="padding-right:10px; padding-left:10px;">Total Credit As of Now</th><th align="right" style="padding-right:10px;">' . number_format($credit_total0) . '</th></tr>';
			//			print '<table>'.$body.'</table>';
			$to = $inf_to_email;
			$sent = false;
			$titlebody = 'Credit Balances';
			$heading = 'Customer Credit Balances as Of Now';

			include  'template/email_template1.php';
			$subject = $inf_company . '| Daily Credit Report';
			/*
					$from = str_replace("billing","dailycreditreport",$inf_from_email);
					$headers = "MIME-Version: 1.0" . "\r\n";
					$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
					$headers .= 'From: '.$from. "\r\n";
					$sent=mail($to,$subject,$message,$headers);
	//				print $message;
	*/
			require 'plugin/vendor/autoload.php';
			$mail = new PHPMailer(true);
			try {
				// Specify the SMTP settings.
				$mail->isSMTP();
				$mail->setFrom($inf_from_email, 'Billing System');
				$mail->Username   = $smtp_username;
				$mail->Password   = $smtp_password;
				$mail->Host       = $smtp_server;
				$mail->Port       = $smtp_port;
				$mail->SMTPAuth   = true;
				$mail->SMTPSecure = 'tls';
				$mail->addCustomHeader('X-SES-CONFIGURATION-SET', '');

				// Specify the message recipients.
				$mail->addAddress($inf_to_email);
				// You can also add CC, BCC, and additional To recipients here.

				// Specify the content of the message.
				$mail->isHTML(true);
				$mail->Subject    = $subject;
				$mail->Body       = $message;
				$mail->AltBody    = '';
				$mail->Send();

				echo "Credit Report Email sent!", PHP_EOL;
			} catch (phpmailerException $e) {
				echo "An error occurred. {$e->errorMessage()}", PHP_EOL; //Catch errors from PHPMailer.
			} catch (Exception $e) {
				echo "Email not sent. {$mail->ErrorInfo}", PHP_EOL; //Catch errors from Amazon SES.
			}
		}
	}
}

function dailyInventory()
{
	include('config.php');
	$result = mysqli_query($conn, "SELECT value FROM settings WHERE setting='timezone'");
	$row = mysqli_fetch_assoc($result);
	$timezone = $row['value'];
	$yesterday = date("Y-m-d", time() + (3600 * $timezone) - 86400);
	$query1 = "SELECT SUM(c_price*qty) FROM inventory_qty";
	$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
	$total = $row1[0];
	$query1 = "SELECT SUM(c_price*qty) FROM inventory_new";
	$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
	$total += $row1[0];
	$query1 = "SELECT SUM(tr.c_price * tr.qty) FROM transfer_main tm, transfer tr WHERE tm.gtn_no=tr.gtn_no AND tm.`status` IN (0,4)";
	$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
	$total += $row1[0];
	$query1 = "SELECT SUM(bi.qty*bi.cost) FROM bill_main bm, bill bi WHERE bm.invoice_no=bi.invoice_no AND bm.exclude=0 AND bm.`status`!=0 AND bm.`lock`=0";
	$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
	$total += $row1[0];
	$query1 = "INSERT INTO `inventory_history` (`date`,`total`) VALUE ('$yesterday','$total')";
	mysqli_query($conn, $query1);
}

function mismatch($itq_id)
{
	include('config.php');
	$query1 = "SELECT itq.qty,itm.unic,itm.id,itq.location FROM inventory_items itm, inventory_qty itq WHERE itm.id=itq.item AND itq.id='$itq_id'";
	$row1 = mysqli_fetch_row(mysqli_query($conn2, $query1));
	$itq_count = $row1[0];
	$itq_unic = $row1[1];
	$itq_item = $row1[2];
	$itq_store = $row1[3];
	$query1 = "SELECT SUM(qty) FROM inventory_new WHERE item='$itq_item' AND store='$itq_store'";
	$row1 = mysqli_fetch_row(mysqli_query($conn2, $query1));
	$itq_count += $row1[0];
	$query1 = "SELECT COUNT(id) FROM inventory_unic_item itu WHERE itu.itq_id='$itq_id' AND itu.`status`=0";
	$row1 = mysqli_fetch_row(mysqli_query($conn2, $query1));
	$itu_count = $row1[0];
	if ($itq_unic == 1) {
		if ($itq_count == $itu_count) return true;
		else return false;
	} else {
		return true;
	}
}

function requestApproval()
{
	$req_count = 0;
	include('config.php');
	$query = "SELECT count(id) FROM loan_main WHERE `status`=1";
	$row = mysqli_fetch_row(mysqli_query($conn2, $query));
	if ($row[0] > 0) $req_count += 1;
	$query = "SELECT count(id) FROM shipment_main WHERE `status`=1";
	$row = mysqli_fetch_row(mysqli_query($conn2, $query));
	if ($row[0] > 0) $req_count += 1;
	if ($req_count > 0) return true;
	else	return false;
}

function deleteAck()
{
	$delete_ack = 0;
	include('config.php');
	$query = "SELECT delete_ack FROM bill_main bm, bill bi WHERE bm.invoice_no=bi.invoice_no AND bm.`status`=0 GROUP BY bi.invoice_no ORDER BY bm.deleted_timestamp DESC LIMIT 100";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$delete_ack += $row[0];
	}
	$query = "SELECT delete_ack FROM payment WHERE `status`=1 ORDER BY deleted_date DESC LIMIT 100";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$delete_ack += $row[0];
	}
	if ($delete_ack > 0) return true;
	else	return false;
}

function repairPartReorder()
{
	$store = $_COOKIE['store'];
	include('config.php');
	$query = "SELECT count(ri.id) FROM repair_parts_inventory ri, repair_parts rp WHERE rp.id=ri.part AND ri.qty<ri.reorder_level AND rp.`status`=1 AND ri.location='$store'";
	$row = mysqli_fetch_row(mysqli_query($conn2, $query));
	if ($row[0] > 0) return true;
	else return false;
}

function getGoogleAPI1()
{
	include('config.php');
	$result = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='api_map'");
	$row = mysqli_fetch_assoc($result);
	return $row['value'];
}

function getGoogleAPI2()
{
	include('config.php');
	$result = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='api_geocoding'");
	$row = mysqli_fetch_assoc($result);
	return $row['value'];
}

function smsResend($smsid)
{
	$out = 'error';
	$process = false;
	include('config.php');
	$query = "SELECT `case`,ref,`text` FROM sms WHERE id='$smsid'";
	$result = mysqli_query($conn2, $query);
	$row = mysqli_fetch_row($result);
	$case = $row[0];
	$ref2 = $row[1];
	$message = $row[2];
	if ($case == 1) {
		$process = true;
		$ref1 = 'bill';
		$query = "SELECT cu.mobile,cu.`sub_system` FROM bill_main bm, cust cu WHERE bm.`cust`=cu.id AND bm.invoice_no='$ref2'";
		$result = mysqli_query($conn2, $query);
		$row = mysqli_fetch_row($result);
		$mobile = $row[0];
		$sub_system = $row[1];
	}
	if ($case == 2) {
		$process = true;
		$ref1 = 'pay';
		$query = "SELECT cu.mobile,cu.`sub_system` FROM payment py, cust cu WHERE py.`cust`=cu.id AND py.id='$ref2'";
		$result = mysqli_query($conn2, $query);
		$row = mysqli_fetch_row($result);
		$mobile = $row[0];
		$sub_system = $row[1];
	}
	if ($case == 3) {
		$process = true;
		$ref1 = 'cust';
		$query = "SELECT cu.mobile,cu.`sub_system` FROM cust cu WHERE cu.id='$ref2'";
		$result = mysqli_query($conn2, $query);
		$row = mysqli_fetch_row($result);
		$mobile = $row[0];
		$sub_system = $row[1];
	}

	if ($process) {
		$sms_data = json_decode(sms_credential($sub_system));
		$sms_user = $sms_data->{"user"};
		$sms_pass = $sms_data->{"pass"};
		$sms_balance = $sms_data->{"balance"};
		$sms_device = $sms_data->{"device"};

		$text = urlencode($message);
		if ($sms_device == "") {
			$url = "http://www.textit.biz/sendmsg/?id=$sms_user&pw=$sms_pass&eco=Y&to=$mobile&text=$text";
			$ret = file($url);
			$res = explode(":", $ret[0]);
			if (trim($res[0]) == "OK") $out = 'done';
			if ($out == 'done') {
				$sms_balance--;
				set_sms_balance($sub_system, $sms_balance);
			}
		} else {
			$url = "http://mqtt.negoit.info/sms_gw.php?dev=$sms_device&ref1=$ref1&ref2=$ref2&u=$sms_user&p=$sms_pass&to=$mobile&text=$text";
			file($url);
			$out = 'done';
			setcookie("sms_balance", $sms_balance, time() + 3600 * 10);
		}
	}

	return $out;
}

function monthCount($year1, $month1, $year2, $month2)
{
	$month1 += 0;
	$month2 += 0;
	$month_n = $month1 + 1;
	if ($year1 == $year2) {
		if ($month_n <= $month2) {
			$month_new = $year1 . '-' . str_pad($month_n, 2, "0", STR_PAD_LEFT);
		} else {
			$month_new = '0000-00';
		}
	} else {
		if ($month_n < 13) {
			$month_new = $year1 . '-' . str_pad($month_n, 2, "0", STR_PAD_LEFT);
		} else {
			$year1++;
			$month_new = $year1 . '-01';
		}
	}
	return $month_new;
}

function getTags(){
	global $tag_id,$tag_name;
	$tag_id=$tag_name=array();
	include('config.php');
	$query="SELECT id,tag FROM tag_name ORDER BY tag";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_array($result)){
		$tag_id[]=$row[0];
		$tag_name[]=$row[1];
	}
}

function country(){


}

function validateMobileNo($mobile){
	$country=inf_country(1);
	$count=0;
	if ($country=='AE'){
		$count=0;
	}
	if ($country=='SE'){
		if (strlen($mobile) != 10) $count++;
	}
	if ($country=='LK'){
		if (strpos($mobile, '07') > 0) $count++;
		if (strlen($mobile) != 10) $count++;
	}
	if($count>0)
		return False;
	else
		return True;
}

function notificationDelay(){
	if(!isset($_COOKIE['notification'])){
		setcookie("notification",1, time()+3600);
	}
}
?>