<?php

require(__DIR__ . '/../plugin/Quickbooks/vendor/autoload.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Facades\Customer;
use QuickBooksOnline\API\Facades\Payment;
use QuickBooksOnline\API\Facades\Invoice;
use QuickBooksOnline\API\Facades\Account;
use QuickBooksOnline\API\Facades\JournalEntry;
use QuickBooksOnline\API\ReportService\ReportService;
use QuickBooksOnline\API\ReportService\ReportName;
use QuickBooksOnline\API\Facades\Vendor;
use QuickBooksOnline\API\Facades\Bill;
use QuickBooksOnline\API\Facades\BillPayment;
use QuickBooksOnline\API\Facades\Purchase;
use QuickBooksOnline\API\Facades\Item;
use QuickBooksOnline\API\Facades\Employee;

// update by nirmal 13_01_2025 add more devices and check user agent is set
function isMobile()
{
	// return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
	if (isset($_SERVER['HTTP_USER_AGENT'])) {
		return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos|iphone|ipod|ipad|windows phone|silk|kindle|opera mini|opera mobi|mobile safari|samsungbrowser)/i", $_SERVER["HTTP_USER_AGENT"]);
	}
	return false;
}

// added by nirmal 10_03_2023
function getCurrency($method)
{
	if ($method == 1)
		include('config.php');
	if ($method == 2)
		include('../../../../config.php');
	$result = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='currency'");
	$row = mysqli_fetch_assoc($result);
	return $row['value'];
}

// added by nirmal 17_02_2023
function getDistrictName($method)
{
	$district_name = '';
	if ($method == 1)
		include('config.php');
	if ($method == 2)
		include('../../../../config.php');
	if (isset($_COOKIE['district'])) {
		$current_district = $_COOKIE['district'];
		$query = "SELECT `name` FROM `district` WHERE `id` = '$current_district'";
		$row = mysqli_fetch_row(mysqli_query($conn2, $query));
		$district_name = $row[0];
	}
	return $district_name;
}

// added by nirmal 21_12_17
function stringAndNumberValidation($str)
{
	if (preg_match('/^[a-zA-Z0-9]+$/', $str)) {
		return true;
	} else {
		return false;
	}
}

// added by nirmal 14_02_2022
function commissionOnBilling()
{
	// Products wise commission calculation
	if (!isset($_COOKIE['commission_on_billing'])) {
		include('config.php');
		$result = mysqli_query($conn2, "SELECT `value` FROM settings WHERE setting='commission_on_billing'");
		$row = mysqli_fetch_assoc($result);
		setcookie("commission_on_billing", $row['value'], time() + 3600 * 10);
	}
}

// added by nirmal 02_03_2022
function custDetailsonOnBilling()
{
	// Customer basic details showing to salesman, but some restrictions are there, filter queuing limited
	if (!isset($_COOKIE['cus_details_on_billing'])) {
		include('config.php');
		$result = mysqli_query($conn2, "SELECT `value` FROM settings WHERE setting='cus_details_on_billing'");
		$row = mysqli_fetch_assoc($result);
		setcookie("cus_details_on_billing", $row['value'], time() + 3600 * 10);
	}
}

// added by nirmal 25_05_2022
function custDOBonOnManager()
{
	// Customer basic details + dob showing to manager
	if (!isset($_COOKIE['cust_dob_on_manager'])) {
		include('config.php');
		$result = mysqli_query($conn2, "SELECT `value` FROM settings WHERE setting='cust_dob_on_manager'");
		$row = mysqli_fetch_assoc($result);
		setcookie("cust_dob_on_manager", $row['value'], time() + 3600 * 10);
	}
}

// added by nirmal 26_05_2022
function custDOBSMSsent()
{
	include('config.php');
	$result = mysqli_query($conn2, "SELECT `value` FROM settings WHERE setting='cust_dob_on_manager'");
	$row = mysqli_fetch_assoc($result);

	if (($row['value'] == 1) && ($_SERVER['SERVER_NAME'] == inf_url_primary())) {
		// sms content
		$result = mysqli_query($conn2, "SELECT `value` FROM settings WHERE setting='cust_dob_sms_content'");
		$row = mysqli_fetch_assoc($result);
		$sms_content = $row['value'];

		// current date
		$date = substr(dateNow(), 5);

		// select sub system
		$query = "SELECT `id` FROM sub_system WHERE `status`='1'";
		$result = mysqli_query($conn2, $query);
		while ($row = mysqli_fetch_array($result)) {
			// sms credentials
			$sub_system = $row[0];
			$sms_data = json_decode(sms_credential($sub_system));
			$sms_user = $sms_data->{"user"};
			$sms_pass = $sms_data->{"pass"};
			$sms_balance = $sms_data->{"balance"};
			$sms_device = $sms_data->{"device"};

			if ($sms_balance > 0) {
				$query = "SELECT cu.`name`, cu.`mobile`, s.`shop_name_sms` FROM cust cu, stores s WHERE cu.`associated_store` = s.`id` AND date_format(cu.`dob`, '%m-%d') = '$date' AND cu.`status`='1' AND s.`status`='1'";
				$result = mysqli_query($conn2, $query);
				while ($row1 = mysqli_fetch_array($result)) {
					// sms body
					$cust_name = $row1[0];
					$cust_mobile = $row1[1];
					$store_name = $row1[2];
					$message = str_replace("[Client Name]", $cust_name, $sms_content) . '-NLC-' . '-NLC-' . '-NLC-' . $store_name;

					// sms send
					$text = urlencode($message);
					if ($sms_device == "") {
						$url = "http://www.textit.biz/sendmsg/?id=$sms_user&pw=$sms_pass&eco=Y&to=$cust_mobile&text=$text";
						$ret = file($url);
						$res = explode(":", $ret[0]);
						if (trim($res[0]) == "OK")
							$mailstatus = true;
						else
							$mailstatus = false;
					} else {
						//$url = "http://mqtt.negoit.info/sms_gw.php?dev=$sms_device&ref1=bill&ref2=$invoice_no&u=$sms_user&p=$sms_pass&to=$cust_mobile&text=$text";
						setcookie("sms_balance", $sms_balance, time() + 3600 * 10);
						file($url);
						$mailstatus = false;
					}
					if ($mailstatus) {
						$sms_balance--;
						if (set_sms_balance($sub_system, $sms_balance))
							$msg = 'SMS Sent<hr />';
						else
							$msg = 'Database Cound Not be Updated<hr />';
					} else
						$msg = 'SMS Could not be Sent<hr />';
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
	if ($row['change_pw'] == 1)
		$change_pw = true;
	else
		$change_pw = false;
	return $change_pw;
}

function unicCal()
{
	include('config.php');
	$result = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='uniq_item_cal'");
	$row = mysqli_fetch_assoc($result);
	if ($row['value'] == 'True')
		$out = true;
	else
		$out = false;
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

function paymentType($type_id)
{
	switch ($type_id) {
		case 1:
			$jasonArray["name"] = 'Cash';
			$jasonArray["color"] = '#009900';
			break;
		case 2:
			$jasonArray["name"] = 'Bank';
			$jasonArray["color"] = '#00AAAA';
			break;
		case 3:
			$jasonArray["name"] = 'Cheque';
			$jasonArray["color"] = 'blue';
			break;
		case 4:
			$jasonArray["name"] = 'Credit Card';
			$jasonArray["color"] = '#FF9900';
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

// update by nirmal 02_02_2024
// updated by nirmal 13_07_2024 (added system id 24 to get subsystem customers data)
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
		$sub_system = mapToSubSystemId(1, $sub_system);
		if ((isset($_REQUEST['components'])) && ($_REQUEST['components'] == 'topmanager')) {
			$query = "SELECT `name`,`nickname`,`mobile`,`nic` FROM cust WHERE `status` IN ($in) AND $qry LIMIT 20";
		} else {
			$query = "SELECT `name`,`nickname`,`mobile`,`nic` FROM cust WHERE `sub_system`='$sub_system' AND `status` IN ($in) AND $qry LIMIT 20";
		}
		$result = mysqli_query($conn2, $query);
		while ($row = mysqli_fetch_array($result)) {
			if ($_GET['action'] == 'cust-list')
				$data_list[] = $row[0];
			if ($_GET['action'] == 'cust-list-one_time')
				$data_list[] = $row[0];
			if ($_GET['action'] == 'cust-list-wholesale')
				$data_list[] = $row[0];
			if ($_GET['action'] == 'cust-list2')
				$data_list[] = $row[0];
			if ($_GET['action'] == 'nick-list')
				$data_list[] = $row[1];
			if ($_GET['action'] == 'mob-list')
				$data_list[] = $row[2];
			if ($_GET['action'] == 'nic-list')
				$data_list[] = $row[3];
		}
	}
}

function listTag($sub_system)
{
	global $data_list, $fn;
	$data_list = array();
	$fn = 'selectTag';
	if ($_POST['keyword']) {
		$keyword = $_POST['keyword'];
		include('config.php');
		$query = "SELECT `tag` FROM tag_name WHERE `tag` LIKE '%$keyword%' LIMIT 20";
		$result = mysqli_query($conn2, $query);
		while ($row = mysqli_fetch_array($result)) {
			$data_list[] = $row[0];
		}
	}
}

function getRecoveryAgent($sub_system)
{
	global $rag_id, $rag_name;
	$rag_id = array();
	include('config.php');
	$query = "SELECT DISTINCT up.id,up.username FROM userprofile up, permission pe, `function` fn WHERE up.id=pe.`user` AND pe.`function`=fn.id AND up.`sub_system`='$sub_system' AND up.`status`='0' AND fn.`status`=1 AND fn.`name`='Hire Purchase' ORDER BY up.username";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$rag_id[] = $row[0];
		$rag_name[] = $row[1];
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
		$result = mysqli_query($conn2, $query);
		while ($row = mysqli_fetch_array($result)) {
			$data_list[] = ucfirst($row[0]);
		}
	}
}

// updated by nirmal 02_02_2024
// updated by nirmal 13_07_2024 (added system id 24 to get subsystem customers data)
function moreCust($sub_system)
{
	if (isset($_POST['case'])) {
		$case = $_POST['case'];
		$val = $_POST['val'];
		$jasonArray = array();
		if ($case == 'name')
			$qry = "`name`='$val'";
		if ($case == 'nick')
			$qry = "`nickname`='$val'";
		if ($case == 'mob')
			$qry = "`mobile`='$val'";
		if ($case == 'nic')
			$qry = "`nic`='$val'";
		include('config.php');
		$sub_system = mapToSubSystemId(1, $sub_system);
		if ((isset($_REQUEST['components'])) && ($_REQUEST['components'] == 'topmanager')) {
			$query = "SELECT id,`name`,`mobile` FROM cust WHERE `status` IN (1,2) AND $qry LIMIT 1";
		} else {
			$query = "SELECT id,`name`,`mobile` FROM cust WHERE `sub_system`='$sub_system' AND `status` IN (1,2) AND $qry LIMIT 1";
		}
		$row = mysqli_fetch_row(mysqli_query($conn2, $query));
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
	$row = mysqli_fetch_row(mysqli_query($conn2, $query));
	$data = $row[0];
	return $data;
}

// update by nirmal 18_10_2023
function listItem($sub_system)
{
	global $data_list, $fn;
	$data_list = array();

	if ($_POST['keyword']) {
		$keyword = str_replace("'", "", $_POST['keyword']);
		$item_filter = $_GET['item_filter'];
		$item_type = $_GET['item_type'];
		$components = $_REQUEST['components'];
		$inf_systemid = inf_systemid(1);

		if ($_GET['action'] == 'code-list') {
			if (($inf_systemid == 13) && ($components == 'bill2'))
				$qry = "ii.`code` LIKE '%$keyword%'";
			else
				$qry = "`code` LIKE '%$keyword%'";
			$fn = 'selectCode';
		}
		if ($_GET['action'] == 'desc-list') {
			if (($inf_systemid == 13) && ($components == 'bill2'))
				$qry = "ii.`description` LIKE '%$keyword%'";
			else
				$qry = "`description` LIKE '%$keyword%'";
			$fn = 'selectDesc';
		}

		if ($item_filter == '') {
			$item_filter_qry = '';
		}
		if ($item_filter == 1) {
			if (($inf_systemid == 13) && ($components == 'bill2'))
				$item_filter_qry = "AND ii.`pr_sr` IN (1,2)";
			else
				$item_filter_qry = "AND pr_sr IN (1,2)";
		}
		if ($item_filter == 2) {
			if (($inf_systemid == 13) && ($components == 'bill2'))
				$item_filter_qry = "AND ii.`pr_sr` IN (1,2)";
			else
				$item_filter_qry = "AND pr_sr IN (1,2)";
		}
		if ($item_filter == 3) {
			if (($inf_systemid == 13) && ($components == 'bill2'))
				$item_filter_qry = "AND ii.`pr_sr`='3'";
			else
				$item_filter_qry = "AND pr_sr='3'";
		}

		if ($item_type == 'all') {
			$item_type_qry = "";
		} else {
			if (($inf_systemid == 13) && ($components == 'bill2'))
				"AND ii.`unic`='$item_type'";
			else
				$item_type_qry = "AND `unic`='$item_type'";
		}

		include('config.php');
		if ($inf_systemid == 1 || $inf_systemid == 17 || $inf_systemid == 24) {
			$query = "SELECT DISTINCT `code`,`description` FROM inventory_items WHERE `status`='1' AND $qry $item_filter_qry $item_type_qry LIMIT 20";
		} else if (($inf_systemid == 13) && ($components == 'bill2')) {
			$query = "SELECT DISTINCT ii.`code`, ii.`description` FROM inventory_items ii, item_category ic WHERE ii.`category` = ic.`id` AND ii.`sub_system`='$sub_system' AND ii.`status`='1' AND ic.`status` != '0' AND $qry $item_filter_qry $item_type_qry LIMIT 20";
		} else {
			$query = "SELECT DISTINCT `code`,`description` FROM inventory_items WHERE `sub_system`='$sub_system' AND `status`='1' AND $qry $item_filter_qry $item_type_qry LIMIT 20";
		}
		$result = mysqli_query($conn2, $query);
		while ($row = mysqli_fetch_array($result)) {
			if ($_GET['action'] == 'code-list')
				$data_list[] = $row[0];
			if ($_GET['action'] == 'desc-list')
				$data_list[] = $row[1];
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
		if ($_COOKIE['direct_mkt'] == 1)
			$factor = 'itm.min_w_rate';
	}
	$result = mysqli_query($conn2, "SELECT SUM((bi.qty * bi.unit_price * $factor )/100) as `commision` FROM inventory_items itm, bill_main bm, bill bi WHERE bm.invoice_no=bi.invoice_no AND bi.item=itm.id AND itm.commision>0 AND bm.`status`=5 AND bm.`lock`=1 AND bm.billed_timestamp LIKE '$from_date%' AND bm.billed_by='$user_id'");
	$row = mysqli_fetch_assoc($result);
	return round($row['commision']);
}

function subscription()
{
	if (isset($_SESSION['subscription'])) {
		return $_SESSION['subscription'];
	} else {
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

function subsystemTheme($sub_system, $store, $force)
{
	if (isset($_SESSION['subsystemTheme'])) {
		return $_SESSION['subsystemTheme'];
	} else {
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
	if ($method == 1)
		include('config.php');
	if ($method == 2)
		include('../../../../config.php');
	$result = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='country'");
	$row = mysqli_fetch_assoc($result);
	return $row['value'];
}

function inf_systemid($method)
{
	if (isset($_SESSION['inf_systemid'])) {
		return $_SESSION['inf_systemid'];
	} else {
		if ($method == 1)
			include('config.php');
		if ($method == 2)
			include('../../../../config.php');
		$result = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='systemid'");
		$row = mysqli_fetch_assoc($result);
		$_SESSION['inf_systemid'] = $row['value'];
		return $row['value'];
	}
}

function inf_company($method)
{
	if (isset($_SESSION['inf_company'])) {
		return $_SESSION['inf_company'];
	} else {
		if ($method == 1)
			include('config.php');
		if ($method == 2)
			include('../../../../config.php');
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

function inf_web()
{
	include('config.php');
	$result = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='web'");
	$row = mysqli_fetch_assoc($result);
	return $row['value'];
}

function inf_url_primary()
{
	if (isset($_SESSION['inf_url_primary'])) {
		return $_SESSION['inf_url_primary'];
	} else {
		include('config.php');
		$result = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='url_primary'");
		$row = mysqli_fetch_assoc($result);
		$_SESSION["inf_url_primary"] = $row['value'];
		return $row['value'];
	}
}

function inf_url_backup()
{
	if (isset($_SESSION['inf_url_backup'])) {
		return $_SESSION['inf_url_backup'];
	} else {
		include('config.php');
		$result = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='url_backup'");
		$row = mysqli_fetch_assoc($result);
		$_SESSION["inf_url_backup"] = $row['value'];
		return $row['value'];
	}

}

function decimal_paces()
{
	if (isset($_SESSION['decimal_paces'])) {
		return $_SESSION['decimal_paces'];
	} else {
		include('config.php');
		$result = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='decimal'");
		$row = mysqli_fetch_assoc($result);
		$_SESSION["decimal_paces"] = $row['value'];
		return $row['value'];
	}
}

// update by nirmal 12_10_2023
function bill_module($method)
{
	if (isset($_SESSION['bill_module'])) {
		return $_SESSION['bill_module'];
	} else {
		if ($method == 1)
			include('config.php');
		if ($method == 2)
			include('../../../../config.php');

		$result = mysqli_query($conn2, "SELECT `status` FROM `function` WHERE `name`='Bill2'");
		$row = mysqli_fetch_assoc($result);
		if ($row['status'] == 0)
			$md = 'billing';
		else
			$md = 'bill2';
		$_SESSION["bill_module"] = $md;
		return $md;
	}
}

// updated by nirmal 04_10_2024
function sms_credential($sub_system)
{
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
		$result = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='sms_sender_id'");
		$row = mysqli_fetch_assoc($result);
		$jasonArray["sms_sender_id"] = $row['value'];
	} else {
		$result = mysqli_query($conn2, "SELECT sms_user,sms_pass,sms_balance,sms_dev FROM sub_system WHERE id='$sub_system'");
		$row = mysqli_fetch_assoc($result);
		$jasonArray["user"] = $row['sms_user'];
		$jasonArray["pass"] = $row['sms_pass'];
		$jasonArray["balance"] = $row['sms_balance'];
		$jasonArray["device"] = $row['sms_dev'];
		$jasonArray["sms_sender_id"] = "";
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
	if ($result)
		return true;
	else
		return false;
}

function paper_size($method)
{
	if ($method == 1)
		include('config.php');
	if ($method == 2)
		include('../../../../config.php');
	$result = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='paper_size'");
	$row = mysqli_fetch_assoc($result);
	return $row['value'];
}

function checkPendingCust($sub_system)
{
	if (isset($_COOKIE['user_id'])) {
		$user = $_COOKIE['user_id'];
		if ($sub_system == 'all')
			$sub_sys_qry = '';
		else
			$sub_sys_qry = "AND `sub_system`='$sub_system'";
		include('config.php');
		$result = mysqli_query($conn2, "SELECT count(id) as `count` FROM cust WHERE `status`='3' $sub_sys_qry");
		$row = mysqli_fetch_assoc($result);
		$pending_count = $row['count'];
		if ($pending_count > 0)
			return true;
		else
			return false;
	} else
		return false;
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
		if ($gtn_count > 0)
			return true;
		else
			return false;
	} else
		return false;
}

function checkPendingHR($sub_system)
{
	if (isset($_COOKIE['user_id'])) {
		$user = $_COOKIE['user_id'];
		if ($sub_system == 'all')
			$sub_sys_qry = '';
		else
			$sub_sys_qry = "AND `sub_system`='$sub_system'";
		include('config.php');
		$query = "SELECT count(id) as `count` FROM hr_leave_take WHERE `status`='1' $sub_sys_qry";
		$result = mysqli_query($conn2, $query);
		$row = mysqli_fetch_assoc($result);
		$pending_count = $row['count'];
		if ($pending_count > 0)
			return true;
		else
			return false;
	} else
		return false;
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
		if (($time_from < $hour_now) && ($hour_now < $time_to))
			$time_permit = true;
		else
			$time_permit = false;
	} else
		$time_permit = true;
	if (!$time_permit)
		header('Location: index.php?components=authenticate&action=logout&type=1');
}

function deviceCheck($userid)
{
	include('config.php');
	$result1 = mysqli_query($conn2, "SELECT device_restrict FROM userprofile WHERE id='$userid'");
	$row = mysqli_fetch_assoc($result1);
	$devicecheck = $row['device_restrict'];
	if ($devicecheck == 1) {
		$today = date("Y-m-d", time());
		if (isset($_COOKIE['rsaid']))
			$rsaid = $_COOKIE['rsaid'];
		else
			$rsaid = 'hhdjdhdaa44hd';
		$result = mysqli_query($conn2, "SELECT count(dp.id) as `count` FROM devices dv, device_permission dp WHERE dp.device=dv.id AND dv.`key`='$rsaid' AND dv.expiration>'$today' AND dp.`user`='$userid'");
		$row = mysqli_fetch_assoc($result);
		$count = $row['count'];
		if ($count == 0)
			header('Location: index.php?components=authenticate&action=logout&type=2');
	}
}

function dailyCreditEmail()
{
	if (isset($_SESSION['daily_credit_email'])) {
		return $_SESSION['daily_credit_email'];
	} else {
		$cust_name = array();
		$_SESSION['daily_credit_email'] = 1;
		$inf_url_primary = inf_url_primary();
		if ($inf_url_primary == $_SERVER['SERVER_NAME']) {
			include('config.php');
			$result = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='daily_credit_report'");
			$row = mysqli_fetch_assoc($result);
			$report_sent_on = $row['value'];
			$today = dateNow();
			if ($today != $report_sent_on) {
				$inf_company = inf_company(1);
				$inf_from_email = inf_from_email();
				$inf_to_email = inf_to_email();
				$credit_total0 = 0;

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
				if ($result)
					custDOBSMSsent();

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

				include 'template/email_template1.php';
				$subject = $inf_company . '| Daily Credit Report';
				/*
																																																																																																			$from = str_replace("billing","dailycreditreport",$inf_from_email);
																																																																																																			$headers = "MIME-Version: 1.0" . "\r\n";
																																																																																																			$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
																																																																																																			$headers .= 'From: '.$from. "\r\n";
																																																																																																			$sent=mail($to,$subject,$message,$headers);
																																																																																																	//	print $message;
																																																																																																	*/
				require 'plugin/vendor/autoload.php';
				$mail = new PHPMailer(true);
				try {
					// Specify the SMTP settings.
					$mail->isSMTP();
					$mail->setFrom($inf_from_email, 'Billing System');
					$mail->Username = $smtp_username;
					$mail->Password = $smtp_password;
					$mail->Host = $smtp_server;
					$mail->Port = $smtp_port;
					$mail->SMTPAuth = true;
					$mail->SMTPSecure = 'tls';
					$mail->addCustomHeader('X-SES-CONFIGURATION-SET', '');

					// Specify the message recipients.
					$mail->addAddress($inf_to_email);
					// You can also add CC, BCC, and additional To recipients here.

					// Specify the content of the message.
					$mail->isHTML(true);
					$mail->Subject = $subject;
					$mail->Body = $message;
					$mail->AltBody = '';
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
		if ($itq_count == $itu_count)
			return true;
		else
			return false;
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
	if ($row[0] > 0)
		$req_count += 1;
	$query = "SELECT count(id) FROM shipment_main WHERE `status`=1";
	$row = mysqli_fetch_row(mysqli_query($conn2, $query));
	if ($row[0] > 0)
		$req_count += 1;
	if ($req_count > 0)
		return true;
	else
		return false;
}

function deleteAck()
{
	include('config.php');
	$delete_ack = 0;
	$query = "SELECT delete_ack FROM bill_main bm, bill bi WHERE bm.invoice_no=bi.invoice_no AND bm.`status`=0 GROUP BY bi.invoice_no ORDER BY bm.deleted_timestamp DESC LIMIT 100";
	$result = mysqli_query($conn2, $query);
	if ($result === false) {
		error_log("SQL Error: " . mysqli_error($conn2));
		$delete_ack = 0;
	} else {
		while ($row = mysqli_fetch_array($result)) {
			$delete_ack += $row[0];
		}
	}
	$query = "SELECT delete_ack FROM payment WHERE `status`=1 ORDER BY deleted_date DESC LIMIT 100";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$delete_ack += $row[0];
	}
	if ($delete_ack > 0)
		return true;
	else
		return false;
}

function repairPartReorder()
{
	$store = $_COOKIE['store'];
	include('config.php');
	$query = "SELECT count(ri.id) FROM repair_parts_inventory ri, repair_parts rp WHERE rp.id=ri.part AND ri.qty<ri.reorder_level AND rp.`status`=1 AND ri.location='$store'";
	$row = mysqli_fetch_row(mysqli_query($conn2, $query));
	if ($row[0] > 0)
		return true;
	else
		return false;
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

// update by nirmal 28_01_2025 (added system id 17 support codes and after payment, invoice sms resent, update sms as 1 in tables)
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

	$result = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='systemid'");
	$row = mysqli_fetch_assoc($result);
	$systemid = $row['value'];

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
		$sms_sender_id = $sms_data->{"sms_sender_id"};

		$text = urlencode($message);
		if ($sms_device == "") {
			if ($systemid == 17) {
				$text = [
					"Text" => $message,
					"Number" => $mobile,
					"SenderId" => $sms_sender_id,
					"DRNotifyUrl" => "https://www.domainname.com/notifyurl",
					"DRNotifyHttpMethod" => "POST",
					"Tool" => "API"
				];
				$res = systemID17SMSSend($text);
				if ($res['status'] == 'success') {
					// $res_message = $res['message'];
					$sms_balance--;
					set_sms_balance($sub_system, $sms_balance);
					$out == 'done';
					if ($case == 1) {
						$query = "UPDATE `bill_main` SET `sms`='1' WHERE `invoice_no`='$ref2'";
						mysqli_query($conn, $query);
					}
					if ($case == 2) {
						$query = "UPDATE `payment` SET `sms`='1' WHERE `id`='$ref2'";
						mysqli_query($conn, $query);
					}
				}
			} else {
				$url = "http://www.textit.biz/sendmsg/?id=$sms_user&pw=$sms_pass&eco=Y&to=$mobile&text=$text";
				$ret = file($url);
				$res = explode(":", $ret[0]);
				if (trim($res[0]) == "OK")
					$out = 'done';
				if ($out == 'done') {
					$sms_balance--;
					set_sms_balance($sub_system, $sms_balance);

					if ($case == 1) {
						$query = "UPDATE `bill_main` SET `sms`='1' WHERE `invoice_no`='$ref2'";
						mysqli_query($conn, $query);
					}
					if ($case == 2) {
						$query = "UPDATE `payment` SET `sms`='1' WHERE `id`='$ref2'";
						mysqli_query($conn, $query);
					}
				}
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

// added by nirmal 04_10_2024
function systemID17SMSSend($requestData)
{
	if (isset($_COOKIE['sub_system']))
		$sub_system = $_COOKIE['sub_system'];
	else
		$sub_system = 0;
	$sms_data = json_decode(sms_credential($sub_system));
	$sms_user = $sms_data->{"user"};
	$sms_pass = $sms_data->{"pass"};
	$message = $message_uuid = $api_id = $queue_message = $status = '';

	$curl = curl_init();
	$apiEndpoint = 'https://restapi.smscountry.com/v0.1/Accounts/' . $sms_user . '/SMSes/';

	curl_setopt_array($curl, [
		CURLOPT_URL => $apiEndpoint,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => json_encode($requestData),
		CURLOPT_HTTPHEADER => [
			'Content-Type: application/json',
			"Authorization: Basic $sms_pass",
		],
	]);

	$response = curl_exec($curl);
	if (curl_errno($curl)) {
		$message .= 'cURL Error: ' . curl_error($curl);
	} else {
		// Decode the JSON response
		$decodedResponse = json_decode($response, true);

		if ($decodedResponse !== null) {
			// Check if the response indicates success
			if (isset($decodedResponse['Success']) && $decodedResponse['Success'] === "True") {
				$status = 'success';
				$message = 'Message successfully queued.';
				$api_id = $decodedResponse['ApiId'];
				$message_uuid = $decodedResponse['MessageUUID'];
				$queue_message = $decodedResponse['Message'];
			} else {
				// Handle error when 'Success' is not true
				$status = 'error';
				$message = 'Error in message queuing. Response: ' . $response;
			}
		} else {
			// Handle JSON decoding failure
			$status = 'error';
			$message = 'Failed to decode JSON response.';
		}
	}
	return array('status' => $status, 'message' => $queue_message, 'uuid' => $message_uuid);
}

function systemID17getStatusOfSentSMS($requestData)
{

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

function getTags()
{
	global $tag_id, $tag_name;
	$tag_id = $tag_name = array();
	include('config.php');
	$query = "SELECT id,tag FROM tag_name ORDER BY tag";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$tag_id[] = $row[0];
		$tag_name[] = $row[1];
	}
}

function country()
{
}

// updated by nirmal 07_10_2024 (update number check for UAE system)
function validateMobileNo($mobile)
{
	$country = inf_country(1);
	$count = 0;
	if ($country == 'AE') {
		if (strpos($mobile, '05') > 0)
			$count++;
		if (strlen($mobile) != 10)
			$count++;
	}
	if ($country == 'SE') {
		if (strlen($mobile) != 10)
			$count++;
	}
	if ($country == 'LK') {
		if (strpos($mobile, '07') > 0)
			$count++;
		if (strlen($mobile) != 10)
			$count++;
	}
	if ($count > 0)
		return False;
	else
		return True;
}

// updated by nirmal 13_10_2023
function notificationDelay()
{
	ob_start();
	if (!isset($_COOKIE['notification'])) {
		setcookie("notification", 1, time() + 3600);
	}
	ob_end_flush();
}

// added by nirmal 04_08_2023
function getDecimalPlaces($method)
{
	if (isset($_SESSION['decimal_paces'])) {
		return $_SESSION['decimal_paces'];
	} else {
		if ($method == 1)
			include('config.php');
		if ($method == 2)
			include('../../../../config.php');

		$result = mysqli_query($conn2, "SELECT `value` FROM settings WHERE setting='decimal'");
		$row = mysqli_fetch_assoc($result);
		$_SESSION["decimal_paces"] = $row['value'];
		return $row['value'];
	}
}

// added by nirmal 14_08_2023
function isProfitReportActive()
{
	if (isset($_SESSION['profit_report_active'])) {
		return $_SESSION['profit_report_active'];
	} else {
		include('config.php');
		$result = mysqli_query($conn2, "SELECT `value` FROM settings WHERE setting='profit_report'");
		$row = mysqli_fetch_assoc($result);
		$_SESSION["profit_report_active"] = $row['value'];
		return $row['value'];
	}
}

// added by nirmal 07_08_2023
function validateDate($dateString, $expectedFormat)
{
	$isValidFormat = \DateTime::createFromFormat($expectedFormat, $dateString) !== false;
	$isValidDate = ($isValidFormat)
		&& (\DateTime::createFromFormat($expectedFormat, $dateString)->format($expectedFormat) === $dateString);

	if ($isValidDate) {
		return true;
	} else {
		return false;
	}
}

// added by nirmal 13_09_2023
function isDeletedMenuActive()
{
	if (isset($_SESSION['deleted_menu_active'])) {
		return $_SESSION['deleted_menu_active'];
	} else {
		include('config.php');
		$user = $_COOKIE['user_id'];
		$result = mysqli_query($conn2, "SELECT `deleted` FROM userprofile WHERE `id`='$user'");
		$row = mysqli_fetch_assoc($result);
		$_SESSION["deleted_menu_active"] = $row['deleted'];
		return $row['deleted'];
	}
}

// added by nirmal 12_10_2023
function getStoreLogo($method)
{
	if (isset($_SESSION['store_logo'])) {
		return $_SESSION['store_logo'];
	} else {
		if ($method == 1)
			include('config.php');
		if ($method == 2)
			include('../../../../config.php');
		$store = $_COOKIE['store'];

		$result = mysqli_query($conn2, "SELECT `logo` FROM stores WHERE `id`='$store'");
		$row = mysqli_fetch_assoc($result);
		$_SESSION["store_logo"] = $row['logo'];
		return $row['logo'];
	}
}

// updated by nirmal 20_11_2023
function getUnitTypes($method)
{
	global $unit_type_name, $unit_type_id;
	if ($method == 1)
		include('config.php');
	if ($method == 2)
		include('../../../../config.php');
	$unit_type_name = $unit_type_id = array();

	$query = "SELECT `id`,`type` FROM unit_types WHERE `status`=1";
	$result = mysqli_query($conn2, $query);
	if (!empty($result)) {
		while ($row = mysqli_fetch_array($result)) {
			$unit_type_id[] = $row[0];
			$unit_type_name[] = $row[1];
		}
	}
}

// added by nirmal 15_12_2023
function getStoreName($method)
{
	if ($method == 1)
		include('config.php');
	if ($method == 2)
		include('../../../../config.php');
	$store = $_COOKIE['store'];

	$result = mysqli_query($conn2, "SELECT `name` FROM stores WHERE `id`='$store'");
	$row = mysqli_fetch_assoc($result);
	return $row['name'];
}

// added by nirmal 22_12_2023
function sendCustomSMS($method, $sub_system, $id, $mobile, $text)
{
	if ($method == 1)
		include('config.php');
	if ($method == 2)
		include('../../../../config.php');
	$out = true;
	$sms_status = false;

	$sms_data = json_decode(sms_credential($sub_system));
	$sms_user = $sms_data->{"user"};
	$sms_pass = $sms_data->{"pass"};
	$sms_balance = $sms_data->{"balance"};
	$sms_device = $sms_data->{"device"};

	if (($sms_balance > 0) && ($_SERVER['SERVER_NAME'] == inf_url_primary()) && (strpos($mobile, "7") == 1)) {
		$sms_balance--;
		if ($sms_device == "") {
			$url = "http://www.textit.biz/sendmsg/?id=$sms_user&pw=$sms_pass&eco=Y&to=$mobile&text=$text";
			$ret = file($url);
			$res = explode(":", $ret[0]);
			if (trim($res[0]) == "OK") {
				$sms_status = true;
			}
		}
		if ($sms_status) {
			$query = "UPDATE `special_event_sms` SET `status`='1' WHERE `id`='$id'";
			mysqli_query($conn, $query);
			if (set_sms_balance($sub_system, $sms_balance)) {
				$message = 'SMS sent';
			} else {
				$message = 'Database could not be updated';
				$out = false;
			}
		} else {
			$message = $res_text;
			$out = false;
			$sms_status = false;
		}
	}
	return array('sms_status' => $sms_status, 'message' => $message, 'status' => $out);
}

// added by nirmal 22_12_2023
function getSMSShopName($method)
{
	if ($method == 1)
		include('config.php');
	if ($method == 2)
		include('../../../../config.php');
	$store = $_COOKIE['store'];

	$result = mysqli_query($conn2, "SELECT `shop_name_sms` FROM stores WHERE `id`='$store'");
	$row = mysqli_fetch_assoc($result);
	return $row['shop_name_sms'];
}

// added by nirmal 27_12_2023
function isCustomInvoiceNoActive($method)
{
	if (isset($_SESSION['is_custom_invoice_no_active'])) {
		return $_SESSION['is_custom_invoice_no_active'];
	} else {
		if ($method == 1)
			include('config.php');
		if ($method == 2)
			include('../../../../config.php');

		$result = mysqli_query($conn2, "SELECT `value` FROM settings WHERE `setting`='custom_invoice_no_active'");
		$row = mysqli_fetch_assoc($result);
		if (!empty($row)) {
			if ($row['value'] == 1) {
				$_SESSION["is_custom_invoice_no_active"] = 1;
			} else {
				$_SESSION["is_custom_invoice_no_active"] = 0;
			}
		} else {
			$_SESSION["is_custom_invoice_no_active"] = 0;
		}
		return $_SESSION['is_custom_invoice_no_active'];
	}
}

// added by nirmal 23_12_2023
function generateBillNumber($store_id, $type)
{
	include('config.php');

	$getStoreInfoQuery = "SELECT `invoice_prefix`, `invoice_start_number` FROM stores WHERE `id` = '$store_id'";
	$row = mysqli_fetch_row(mysqli_query($conn2, $getStoreInfoQuery));
	$invoice_prefix = $row[0];
	$invoice_start_number = $row[1];
	$substringLength = ($invoice_prefix == '0' || $invoice_prefix == '') ? 0 : strlen($invoice_prefix);

	if ($type == 'temp_bill') {
		$column = 'bm_no';
		$table = 'bill_main_tmp';
	} else {
		$column = 'invoice_no';
		$table = 'bill_main';
	}

	if ($substringLength === 0) {
		$getLastBillQuery = "SELECT COALESCE(MAX(CAST($column AS UNSIGNED)), NULL) AS max_bm_no FROM $table WHERE $column REGEXP '^[0-9]+$'";
		$invoice_number = '';
	} else {
		$getLastBillQuery = "SELECT MAX(CAST(SUBSTRING($column, LENGTH('$invoice_prefix')+1) AS UNSIGNED)) FROM $table WHERE $column LIKE '$invoice_prefix%'";
		$getLastBillResult = mysqli_query($conn2, $getLastBillQuery);
		$row = mysqli_fetch_row($getLastBillResult);
		$invoice_number = $row[0];
		if ($invoice_number == '' || $invoice_number == '0' || $invoice_number == 0) {
			$getLastBillQuery = "SELECT MAX(SUBSTRING($column, LENGTH('$invoice_prefix')+1)) FROM $table WHERE store = '$store_id' AND $column LIKE '$invoice_prefix%'";
		}
	}
	if ($invoice_number == '' || $invoice_number == '0' || $invoice_number == 0) {
		$getLastBillResult = mysqli_query($conn2, $getLastBillQuery);
		$row = mysqli_fetch_row($getLastBillResult);
		$invoice_number = $row[0];
	}

	if ($invoice_number == '') {
		$invoice_number = $invoice_start_number;
	} else {
		$invoice_number = str_pad($invoice_number + 1, strlen($invoice_start_number), '0', STR_PAD_LEFT);
	}
	$new_invoice_number = ($invoice_prefix != '') ? $invoice_prefix . $invoice_number : $invoice_number;
	return $new_invoice_number;
}

// added by nirmal 25_12_2023
function emptyBillDelete($salesman, $module)
{
	include('config.php');
	if ($module == 'bill2') {
		$result = mysqli_query($conn2, "SELECT `bm_no` FROM bill_main_tmp WHERE `billed_by` = '$salesman' ORDER BY `order_timestamp` DESC LIMIT 1");
		$row = mysqli_fetch_assoc($result);
		if (($row != null) || ($row != '')) {
			$bill_no = $row['bm_no'];
		} else {
			$bill_no = '';
		}

		$result = mysqli_query($conn2, "SELECT COUNT(*) as `count` FROM bill_tmp WHERE `bm_no`='$bill_no'");
		$row = mysqli_fetch_assoc($result);
		$bill_data = $row['count'];
		if (($bill_no != '') && ($bill_data == 0)) {
			mysqli_query($conn, "DELETE FROM bill_main_tmp WHERE `bm_no`='$bill_no'");
		}
	}
	if ($module == 'billing') {
		$result = mysqli_query($conn2, "SELECT `invoice_no` FROM bill_main WHERE `billed_by` = '$salesman' ORDER BY `order_timestamp` DESC LIMIT 1");
		$row = mysqli_fetch_assoc($result);
		if (($row != null) || ($row != '')) {
			$invoice_no = $row['invoice_no'];
		} else {
			$invoice_no = '';
		}

		$result = mysqli_query($conn2, "SELECT COUNT(*) as `count` FROM bill WHERE `invoice_no`='$invoice_no' AND `status` = '1'");
		$row = mysqli_fetch_assoc($result);
		$bill_data = $row['count'];
		if (($invoice_no != '') && ($bill_data == 0)) {
			mysqli_query($conn, "DELETE FROM bill_main WHERE `invoice_no`='$invoice_no'");
		}
	}

}

// added by nirmal 29_12_2023
// function isBillingPriceUnderValueActive($method){
// 	if(isset($_SESSION['is_billing_price_under_value_active'])){
// 		return $_SESSION['is_billing_price_under_value_active'];
// 	}else{
// 		if ($method == 1)	include('config.php');
// 		if ($method == 2)	include('../../../../config.php');

// 		$result = mysqli_query($conn2, "SELECT `value` FROM settings WHERE `setting`='billing_price_under_value'");
// 		$row = mysqli_fetch_assoc($result);
// 		if (!empty($row)) {
// 			if ($row['value'] == 1) {
// 				$_SESSION["is_billing_price_under_value_active"] = 1;
// 			} else {
// 				$_SESSION["is_billing_price_under_value_active"] = 0;
// 			}
// 		} else {
// 			$_SESSION["is_billing_price_under_value_active"] = 0;
// 		}
// 		return $_SESSION['is_billing_price_under_value_active'];
// 	}
// }

// updated by nirmal 08_07_2024
function isBillingPriceUnderValueActive($method)
{
	if ($method == 1)
		include('config.php');
	if ($method == 2)
		include('../../../../config.php');
	$store = $_COOKIE['store'];

	$result = mysqli_query($conn2, "SELECT `billing_price_under_value` FROM stores WHERE `id`='$store'");
	$row = mysqli_fetch_assoc($result);
	if (!empty($row)) {
		return $row['billing_price_under_value'];
	} else {
		return 0;
	}
}

// added by nirmal 03_01_2024
function isCustomerTotalOutstandingShowInBill($method)
{
	if (isset($_SESSION['is_customer_total_outstanding_show_in_bill'])) {
		return $_SESSION['is_customer_total_outstanding_show_in_bill'];
	} else {
		$isMobile = isMobile();
		if ($isMobile)
			include('config.php');
		else
			include('../../../../config.php');
		$result = mysqli_query($conn2, "SELECT `value` FROM settings WHERE `setting`='customer_total_outstanding_show_in_bill'");
		$row = mysqli_fetch_assoc($result);
		if (!empty($row)) {
			if ($row['value'] == 1) {
				$_SESSION["is_customer_total_outstanding_show_in_bill"] = 1;
			} else {
				$_SESSION["is_customer_total_outstanding_show_in_bill"] = 0;
			}
		} else {
			$_SESSION["is_customer_total_outstanding_show_in_bill"] = 0;
		}
		return $_SESSION['is_customer_total_outstanding_show_in_bill'];
	}
}

// added by nirmal 29_01_2024
function getCustomerTotalOutstandingBalance($method, $cust_id)
{
	$credit_balance = 0;
	$isMobile = isMobile();
	if ($isMobile)
		include('config.php');
	else
		include('../../../../config.php');
	$query1 = "SELECT SUM(bm.`invoice_+total`) + SUM(bm.`invoice_-total`) AS `total` FROM bill_main bm WHERE bm.`status` != 0 AND bm.exclude = 0 AND bm.`lock` = 1 AND bm.`cust` = '$cust_id'";
	$result1 = mysqli_query($conn, $query1);
	$row1 = mysqli_fetch_assoc($result1);
	$totalinv = $row1['total'];

	$query1 = "SELECT SUM(py.amount) as `pay` FROM payment py WHERE py.`status`=0 AND py.`cust`='$cust_id' AND py.chque_return=0";
	$result1 = mysqli_query($conn, $query1);
	$row1 = mysqli_fetch_assoc($result1);
	$totalpay = $row1['pay'];
	$credit_balance = $totalinv - $totalpay;
	return $credit_balance;
}

// added by nirmal 02_02_2024
function mapToSubSystemId($method, $id)
{
	if (isset($_SESSION['map_to_subsystem_id'])) {
		return $_SESSION['map_to_subsystem_id'];
	} else {
		if ($method == 1)
			include('config.php');
		if ($method == 2)
			include('../../../../config.php');
		$result = mysqli_query($conn2, "SELECT `mapped_sub_system` FROM sub_system WHERE `id`='$id'");
		$row = mysqli_fetch_assoc($result);
		if (!empty($row)) {
			$_SESSION["map_to_subsystem_id"] = $row['mapped_sub_system'];
		}
		return $_SESSION['map_to_subsystem_id'];
	}
}

// added by nirmal 15_02_2024
function isCustNameShowInTag()
{
	if (isset($_SESSION['is_customer_name_show_in_tag'])) {
		return $_SESSION['is_customer_name_show_in_tag'];
	} else {
		include('config.php');
		$result = mysqli_query($conn2, "SELECT `value` FROM settings WHERE `setting`='customer_name_show_in_tag'");
		$row = mysqli_fetch_assoc($result);
		if (!empty($row)) {
			if ($row['value'] == 1) {
				$_SESSION["is_customer_name_show_in_tag"] = 1;
			} else {
				$_SESSION["is_customer_name_show_in_tag"] = 0;
			}
		} else {
			$_SESSION["is_customer_name_show_in_tag"] = 0;
		}
		return $_SESSION['is_customer_name_show_in_tag'];
	}
}

// added by nirmal 14_03_2024
function isReturnItemHandlingActive($method)
{
	if (isset($_SESSION['is_return_item_handling_active'])) {
		return $_SESSION['is_return_item_handling_active'];
	} else {
		if ($method == 1)
			include('config.php');
		if ($method == 2)
			include('../../../../config.php');

		$result = mysqli_query($conn2, "SELECT `value` FROM settings WHERE setting='return_item_handling'");
		$row = mysqli_fetch_assoc($result);
		if (!empty($row)) {
			if ($row['value'] == 1) {
				$_SESSION["is_return_item_handling_active"] = 1;
			} else {
				$_SESSION["is_return_item_handling_active"] = 0;
			}
		} else {
			$_SESSION["is_return_item_handling_active"] = 0;
		}
		return $_SESSION['is_return_item_handling_active'];
	}
}
// --------------------------- QUICKBOOKS FUNCTIONS START --------------------------- //
// added by nirmal 29_11_2023

function isQuickBooksActive($method)
{
	if (isset($_SESSION['is_quickbooks_active'])) {
		return $_SESSION['is_quickbooks_active'];
	} else {
		if ($method == 1)
			include('config.php');
		if ($method == 2)
			include('../../../../config.php');

		$result = mysqli_query($conn2, "SELECT `value` FROM settings WHERE setting='quickbooks'");
		$row = mysqli_fetch_assoc($result);
		if (!empty($row)) {
			if ($row['value'] == 1) {
				$_SESSION["is_quickbooks_active"] = 1;
			} else {
				$_SESSION["is_quickbooks_active"] = 0;
			}
		} else {
			$_SESSION["is_quickbooks_active"] = 0;
		}
		return $_SESSION['is_quickbooks_active'];
	}
}

// added by nirmal 29_11_2023
function getQuickBooksRefreshToken($method)
{
	if ($method == 1)
		include('config.php');
	if ($method == 2)
		include('../../../../config.php');

	if (isset($_SESSION['quickbooks_client_id'])) {
		$client_id = $_SESSION['quickbooks_client_id'];
	} else {
		$result = mysqli_query($conn2, "SELECT `value` FROM settings WHERE `setting`='quickbooks_client_id'");
		$row = mysqli_fetch_assoc($result);
		$client_id = $row['value'];
		$_SESSION['quickbooks_client_id'] = $client_id;
	}

	if (isset($_SESSION['quickbooks_client_secret'])) {
		$client_secret = $_SESSION['quickbooks_client_secret'];
	} else {
		$result = mysqli_query($conn2, "SELECT `value` FROM settings WHERE `setting`='quickbooks_client_secret'");
		$row = mysqli_fetch_assoc($result);
		$client_secret = $row['value'];
		$_SESSION['quickbooks_client_secret'] = $client_secret;
	}

	if (!isset($_SESSION['quickbooks_realmid'])) {
		$result = mysqli_query($conn2, "SELECT `value` FROM settings WHERE `setting`='quickbooks_realmid'");
		$row = mysqli_fetch_assoc($result);
		$_SESSION['quickbooks_realmid'] = $row['value'];
	}

	if (!isset($_SESSION['quickbooks_base_url'])) {
		$result = mysqli_query($conn2, "SELECT `value` FROM settings WHERE `setting`='quickbooks_base_url'");
		$row = mysqli_fetch_assoc($result);
		$_SESSION['quickbooks_base_url'] = $row['value'];
	}

	if (!isset($_SESSION['quickbooks_url'])) {
		$result = mysqli_query($conn2, "SELECT `value` FROM settings WHERE `setting`='quickbooks_url'");
		$row = mysqli_fetch_assoc($result);
		$_SESSION['quickbooks_url'] = $row['value'];
	}

	$result = mysqli_query($conn2, "SELECT `value` FROM settings WHERE `setting`='quickbooks_refresh_token'");
	$row = mysqli_fetch_assoc($result);
	$refresh_token = $row['value'];

	$oauth2LoginHelper = new OAuth2LoginHelper($client_id, $client_secret);
	$accessTokenObj = $oauth2LoginHelper->refreshAccessTokenWithRefreshToken($refresh_token);
	$accessTokenValue = $accessTokenObj->getAccessToken();
	$refreshTokenValue = $accessTokenObj->getRefreshToken();

	$_SESSION['quickbooks_access_token'] = $accessTokenValue;
	$_SESSION['quickbooks_refresh_token'] = $refreshTokenValue;
	$result = mysqli_query($conn, "UPDATE settings SET `value` = '$refreshTokenValue' WHERE `setting` = 'quickbooks_refresh_token'");
}

// added by nirmal 04_12_2023
function getQuickBooksDataServiceObj()
{
	$dataService = DataService::Configure(array(
		'auth_mode' => 'oauth2',
		'ClientID' => $_SESSION['quickbooks_client_id'],
		'ClientSecret' => $_SESSION['quickbooks_client_secret'],
		'accessTokenKey' => $_SESSION['quickbooks_access_token'],
		'refreshTokenKey' => $_SESSION['quickbooks_refresh_token'],
		'QBORealmID' => $_SESSION['quickbooks_realmid'],
		'baseUrl' => $_SESSION['quickbooks_base_url']
	));
	return $dataService;
}

// added by nirmal 13_12_2023
function getCustArray($cust)
{
	include('config.php');

	$result = mysqli_query($conn2, "SELECT `qb_cust_id`,`associated_town`,`shop_address`,`name`,`cust_name`,`mobile`,`email` FROM `cust` WHERE `id`='$cust'");
	$row = mysqli_fetch_assoc($result);

	$qb_cust_id = $row['qb_cust_id'];
	$cu_town = $row['associated_town'];
	$shop_name = $row['name'];
	$custname = $row['cust_name'];
	$mobile = $row['mobile'];
	$email_add = $row['email'];
	$shop_address = $row['shop_address'];
	$country = inf_country(1);

	$result1 = mysqli_query($conn2, "SELECT `name` FROM `town` WHERE `id`='$cu_town'");
	$row1 = mysqli_fetch_assoc($result1);
	$town = $row1['name'];

	$shop_name = str_replace(array(':', "\t", "\n"), '', $shop_name);
	$custname = str_replace(array(':', "\t", "\n"), '', $custname);
	if (!validateEmail($email_add)) {
		$email_add = "";
	}
	$custArray = array(
		'DisplayName' => $shop_name,
		'CompanyName' => $shop_name,
		'FullyQualifiedName' => $shop_name,
		'GivenName' => $custname,
		'City' => $town,
		'Line1' => $shop_address,
		'PrimaryEmailAddr' => $email_add,
		'PrimaryPhone' => $mobile,
		'Country' => $country,
		'qb_cust_id' => $qb_cust_id
	);
	return $custArray;
}

// added by nirmal 04_11_2023
function QBCustomerAdd($cust)
{
	getQuickBooksRefreshToken(1);
	$dataService = getQuickBooksDataServiceObj(); // Prep Data Services (common file function)
	$status = 'error';
	$message = 'Error: ';
	$cust_id = '';
	$cust_array = getCustArray($cust);

	// Add a customer
	$customerObj = Customer::create([
		"BillAddr" => [
			"Line1" => $cust_array['Line1'],
			"City" => $cust_array['City'],
			"Country" => $cust_array['Country'],
		],
		"GivenName" => $cust_array['GivenName'],
		"FullyQualifiedName" => $cust_array['FullyQualifiedName'],
		"CompanyName" => $cust_array['CompanyName'],
		"DisplayName" => $cust_array['DisplayName'],
		"PrimaryPhone" => [
			"FreeFormNumber" => $cust_array['PrimaryPhone']
		],
		"PrimaryEmailAddr" => [
			"Address" => $cust_array['PrimaryEmailAddr']
		]
	]);
	$company_name = $cust_array['CompanyName'];
	$customerArray = $dataService->Query("select * from Customer where CompanyName='" . $company_name . "'");
	$error = $dataService->getLastError();
	if ($error) {
		$message = $error->getOAuthHelperError();
	} else {
		if (is_array($customerArray) && sizeof($customerArray) > 0) {
			$message = "Error: Quickbooks (Customer already exist)";
		} else {
			// Create Customer
			$customerResponseObj = $dataService->Add($customerObj);
			$error = $dataService->getLastError();
			if ($error) {
				$message .= $error->getResponseBody();
			} else {
				$status = 'success';
				$message = "Customer insert successfully recorded in QuickBooks.";
				$cust_id = $customerResponseObj->Id;
			}
		}
	}
	return array('status' => $status, 'message' => $message, 'qb_cust_id' => $cust_id);
}

function QBCustomerAdd1($cust_array)
{
	getQuickBooksRefreshToken(1);
	$dataService = getQuickBooksDataServiceObj(); // Prep Data Services (common file function)
	$status = 'error';
	$message = 'Error: ';
	$cust_id = '';

	// Add a customer
	$customerObj = Customer::create([
		"BillAddr" => [
			"Line1" => $cust_array['Line1'],
			"City" => $cust_array['City'],
			"Country" => $cust_array['Country'],
		],
		"GivenName" => $cust_array['GivenName'],
		"FullyQualifiedName" => $cust_array['FullyQualifiedName'],
		"CompanyName" => $cust_array['CompanyName'],
		"DisplayName" => $cust_array['DisplayName'],
		"PrimaryPhone" => [
			"FreeFormNumber" => $cust_array['PrimaryPhone']
		],
		"PrimaryEmailAddr" => [
			"Address" => $cust_array['PrimaryEmailAddr']
		]
	]);
	$company_name = $cust_array['CompanyName'];
	$customerArray = $dataService->Query("select * from Customer where CompanyName='" . $company_name . "'");
	$error = $dataService->getLastError();
	if ($error) {
		$message = $error->getOAuthHelperError();
	} else {
		if (is_array($customerArray) && sizeof($customerArray) > 0) {
			$message = "Error: Quickbooks (Customer already exist)";
		} else {
			// Create Customer
			$customerResponseObj = $dataService->Add($customerObj);
			$error = $dataService->getLastError();
			if ($error) {
				$message .= $error->getResponseBody();
			} else {
				$status = 'success';
				$message = "Customer insert successfully recorded in QuickBooks.";
				$cust_id = $customerResponseObj->Id;
			}
		}
	}
	return array('status' => $status, 'message' => $message, 'qb_cust_id' => $cust_id);
}

// added by nirmal 29_11_2023

function QBCustomerUpdate($cust)
{
	getQuickBooksRefreshToken(1);
	$dataService = getQuickBooksDataServiceObj(); // Prep Data Services (common file function)
	$status = 'error';
	$message = 'Error: ';
	$cust_array = getCustArray($cust);
	$cust_id = $cust_array['qb_cust_id'];

	$entities = $dataService->Query("SELECT * FROM Customer where Id='$cust_id'");
	$error = $dataService->getLastError();
	if ($error) {
		$message = $error->getResponseBody();
	}

	if (empty($entities)) {
		$message = "Error: Quickbooks customer not found";
	} else {
		//Get the first element
		$theCustomer = reset($entities);
		$updateCustomer = Customer::update($theCustomer, [
			"BillAddr" => [
				"Line1" => $cust_array['Line1'],
				"City" => $cust_array['City'],
				"Country" => $cust_array['Country'],
			],
			"GivenName" => $cust_array['GivenName'],
			"FullyQualifiedName" => $cust_array['FullyQualifiedName'],
			"CompanyName" => $cust_array['CompanyName'],
			"DisplayName" => $cust_array['DisplayName'],
			"PrimaryPhone" => [
				"FreeFormNumber" => $cust_array['PrimaryPhone']
			],
			"PrimaryEmailAddr" => [
				"Address" => $cust_array['PrimaryEmailAddr']
			]
		]);
		$customerResponseObj = $dataService->Update($updateCustomer);
		$error = $dataService->getLastError();
		if ($error) {
			$message .= $error->getResponseBody();
		} else {
			$status = 'success';
			$message = "Customer update successfully recorded in QuickBooks.";
			$cust_id = $customerResponseObj->Id;
		}
	}
	return array('status' => $status, 'message' => $message, 'cust_id' => $cust_id);
}

// added by nirmal 28_08_2023
function QBGetServiceItemID()
{
	include('config.php');
	$result = mysqli_query($conn2, "SELECT `value` FROM settings WHERE `setting`='quickbooks_service_item_id'");
	$row = mysqli_fetch_assoc($result);
	return $row['value'];
}

// added by nirmal 28_08_2023
function QBGetCustomFieldID()
{
	include('config.php');
	$result = mysqli_query($conn2, "SELECT `value` FROM settings WHERE `setting`='quickbooks_custom_field_id'");
	$row = mysqli_fetch_assoc($result);
	return $row['value'];
}

function QBInvoiceCreate($bill_array)
{
	getQuickBooksRefreshToken(1);
	$dataService = getQuickBooksDataServiceObj(); // Prep Data Services (common file function)
	$status = 'error';
	$message = 'Error: ';
	$qb_txnid = $qb_doc_id = '';

	$amount = $bill_array['Amount'];
	$qb_cust_id = $bill_array['qb_cust_id'];
	$bill_invoice_no = $bill_array['bill_invoice_no'];
	$total_amount = $bill_array['total_amount'];
	$item_ref = QBGetServiceItemID();
	$custom_field_id = QBGetCustomFieldID();

	$theResourceObj = Invoice::create([
		"TotalAmt" => $total_amount,
		"Line" => [
			[
				"Amount" => $amount,
				"DetailType" => "SalesItemLineDetail",
				"SalesItemLineDetail" => [
					"ItemRef" => [
						"value" => $item_ref,
						"name" => "INVOICE TOTAL"
					]
				]
			]
		],
		"CustomerRef" => [
			"value" => $qb_cust_id
		],
		"CustomField" => [
			[
				"DefinitionId" => "$custom_field_id",
				"StringValue" => "$bill_invoice_no",
				"Type" => "StringType",
				"Name" => "INVOICE NO"
			],
		]
	]);
	$resultingObj = $dataService->Add($theResourceObj);
	$error = $dataService->getLastError();
	if ($error) {
		$message .= $error->getResponseBody();
	} else {
		$status = 'success';
		$message = "Invoice insert successfully recorded in QuickBooks.";
		$qb_txnid = $resultingObj->Id;
		$qb_doc_id = $resultingObj->DocNumber;
	}
	return array('status' => $status, 'message' => $message, 'qb_txnid' => $qb_txnid, 'qb_doc_id' => $qb_doc_id);
}

// added by nirmal 12_03_2024
function QBInvoiceUpdate($bill_array)
{
	getQuickBooksRefreshToken(1);
	$dataService = getQuickBooksDataServiceObj(); // Prep Data Services (common file function)
	$status = 'error';
	$message = 'Error: ';
	$qb_txnid = $qb_doc_id = $qb_txnid_result = $qb_doc_id_result = '';

	$item_ref = QBGetServiceItemID();
	$amount = $bill_array['Amount'];
	$qb_txnid = $bill_array['qb_txnid'];

	$invoice = $dataService->FindbyId('invoice', $qb_txnid);
	$theResourceObj = Invoice::update($invoice, [
		"Line" => [
			[
				"Amount" => $amount,
				"DetailType" => "SalesItemLineDetail",
				"SalesItemLineDetail" => [
					"ItemRef" => [
						"value" => $item_ref,
						"name" => "INVOICE TOTAL"
					]
				]
			]
		],
	]);
	$resultingObj = $dataService->Add($theResourceObj);
	$error = $dataService->getLastError();
	if ($error) {
		$message .= $error->getResponseBody();
	} else {
		$status = 'success';
		$message = "Invoice update successfully recorded in QuickBooks.";
		$qb_txnid_result = $resultingObj->Id;
		$qb_doc_id_result = $resultingObj->DocNumber;
	}
	return array('status' => $status, 'message' => $message, 'qb_txnid' => $qb_txnid_result, 'qb_doc_id' => $qb_doc_id_result);
}

// added by nirmal 12_12_2023
function QBInvoiceDelete($qb_invoice_id)
{
	getQuickBooksRefreshToken(1);
	$dataService = getQuickBooksDataServiceObj();
	$status = 'error';
	$message = 'Error: ';

	$invoice = $dataService->FindbyId('invoice', $qb_invoice_id);
	$resultingObj = $dataService->Delete($invoice);
	$error = $dataService->getLastError();
	if ($error) {
		$message .= $error->getResponseBody();
	} else {
		$status = 'success';
		$message = "Invoice delete successfully recorded in QuickBooks.";
	}
	return array('status' => $status, 'message' => $message);
}

// added by nirmal 13_03_2024
function QBRefundReceipt($refund_array)
{
	getQuickBooksRefreshToken(1);
	$status = 'error';
	$message = 'Error: ';
	$item_ref = QBGetServiceItemID();
	$custom_field_id = QBGetCustomFieldID();
	$qb_refund_id = $qb_doc_id = '';
	$companyId = $_SESSION['quickbooks_realmid'];
	$baseUrl = $_SESSION['quickbooks_url'];
	$bearerToken = $_SESSION['quickbooks_access_token'];
	$curl = curl_init();
	$apiEndpoint = 'https://' . $baseUrl . '/v3/company/' . $companyId . '/refundreceipt';

	if (isset($refund_array['amount'])) {
		$amount = $refund_array['amount'];
	} else {
		$amount = "";
	}

	if (isset($refund_array['qb_cust_id'])) {
		$qb_cust_id = $refund_array['qb_cust_id'];
	} else {
		$qb_cust_id = "";
	}

	if (isset($refund_array['qb_account'])) {
		$qb_account = $refund_array['qb_account'];
	} else {
		$qb_account = "";
	}

	if (isset($refund_array['qb_payment_method_id'])) {
		$qb_payment_method_id = $refund_array['qb_payment_method_id'];
	} else {
		$qb_payment_method_id = "";
	}

	if (isset($refund_array['bill_invoice_no'])) {
		$bill_invoice_no = $refund_array['bill_invoice_no'];
	} else {
		$bill_invoice_no = "";
	}

	if (isset($refund_array['qb_payment_ref_number'])) {
		$qb_payment_ref_number = $refund_array['qb_payment_ref_number'];
	} else {
		$qb_payment_ref_number = "";
	}

	// DepositToAccountRef, Line, mandatory
	$requestData = [
		"Line" => [
			[
				"Amount" => $amount,
				"DetailType" => "SalesItemLineDetail",
				"SalesItemLineDetail" => [
					"ItemRef" => [
						"value" => $item_ref,
					]
				]
			]
		],
		"CustomerRef" => [
			"value" => $qb_cust_id
		],
		"DepositToAccountRef" => [
			"value" => $qb_account
		],
		"PaymentMethodRef" => [
			"value" => $qb_payment_method_id
		],
		"CustomField" => [
			[
				"DefinitionId" => "$custom_field_id",
				"StringValue" => "$bill_invoice_no",
				"Type" => "StringType",
				"Name" => "INVOICE NO"
			],
		],
		"PaymentRefNum" => $qb_payment_ref_number
	];

	// Remove parts if certain variables are empty
	if ($amount === '') {
		unset($requestData['Line']);
	}
	if ($qb_cust_id === '') {
		unset($requestData['CustomerRef']);
	}
	if ($qb_account === '') {
		unset($requestData['DepositToAccountRef']);
	}
	if ($qb_payment_method_id === '') {
		unset($requestData['PaymentMethodRef']);
	}
	if ($qb_payment_ref_number === '') {
		unset($requestData['PaymentRefNum']);
	}
	if ($bill_invoice_no === '') {
		unset($requestData['CustomField']);
	}

	curl_setopt_array($curl, [
		CURLOPT_URL => $apiEndpoint,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => json_encode($requestData),
		CURLOPT_HTTPHEADER => [
			'Accept: application/json',
			'Content-Type: application/json',
			"Authorization: Bearer $bearerToken",
		],
	]);
	$response = curl_exec($curl);
	if (curl_errno($curl)) {
		$message .= 'cURL Error: ' . curl_error($curl);
	} else {
		$decodedResponse = json_decode($response, true);
		if ($decodedResponse !== null) {
			if (isset($decodedResponse['Fault'])) {
				$status = 'error';
				$message = 'Quickbooks error: ' . $decodedResponse['Fault']['Error'][0]['Detail'];
			} else {
				$status = 'success';
				$message = "Refund Receipt insert successfully recorded in QuickBooks.";
				$qb_refund_id = $decodedResponse['RefundReceipt']['Id'];
				$qb_doc_id = $decodedResponse['RefundReceipt']['DocNumber'];
			}
		} else {
			$status = 'error';
			$message = 'Quickbooks error: Failed to decode JSON response.';
		}
	}
	return array('status' => $status, 'message' => $message, 'qb_doc_id' => $qb_doc_id, 'qb_refund_id' => $qb_refund_id);
}

// added by nirmal 07_03_2024
function QBAddPayment($payment_array)
{
	getQuickBooksRefreshToken(1);
	$dataService = getQuickBooksDataServiceObj();
	$status = 'error';
	$message = 'Error: ';
	$qb_payment_id = '';

	if (isset($payment_array['qb_cust_id'])) {
		$qb_cust_id = $payment_array['qb_cust_id'];
	} else {
		$qb_cust_id = '';
	}

	if (isset($payment_array['amount'])) {
		$amount = $payment_array['amount'];
	} else {
		$amount = '';
	}

	if (isset($payment_array['total_amount'])) {
		$total_amount = $payment_array['total_amount'];
	} else {
		$total_amount = '';
	}

	if (isset($payment_array['qb_invoice_id'])) {
		$qb_invoice_id = $payment_array['qb_invoice_id'];
	} else {
		$qb_invoice_id = '';
	}

	if (isset($payment_array['qb_account_id'])) {
		$qb_account_id = $payment_array['qb_account_id'];
	} else {
		$qb_account_id = '';
	}

	if (isset($payment_array['qb_payment_method_id'])) {
		$qb_payment_method_id = $payment_array['qb_payment_method_id'];
	} else {
		$qb_payment_method_id = '';
	}

	if (isset($payment_array['qb_payment_ref_number'])) {
		$qb_payment_ref_number = $payment_array['qb_payment_ref_number'];
	} else {
		$qb_payment_ref_number = '';
	}

	$paymentData = [
		"CustomerRef" => [
			"value" => $qb_cust_id
		],
		"TotalAmt" => $total_amount,
		"Line" => [
			[
				"Amount" => $amount,
				"LinkedTxn" => [
					[
						"TxnId" => $qb_invoice_id,
						"TxnType" => "Invoice"
					]
				]
			]
		],
		"DepositToAccountRef" => [
			"value" => $qb_account_id
		],
		"PaymentMethodRef" => [
			"value" => $qb_payment_method_id
		],
		"PaymentRefNum" => $qb_payment_ref_number
	];

	// Remove parts if certain variables are empty
	if ($qb_cust_id === '') {
		unset($paymentData['CustomerRef']);
	}
	if ($total_amount === '') {
		unset($paymentData['TotalAmt']);
	}
	if ($amount === '') {
		unset($paymentData['Line']);
	}
	if ($qb_invoice_id === '') {
		unset($paymentData['Line']);
	}
	if ($qb_account_id === '') {
		unset($paymentData['DepositToAccountRef']);
	}
	if ($qb_payment_method_id === '') {
		unset($paymentData['PaymentMethodRef']);
	}
	if ($qb_payment_ref_number === '') {
		unset($paymentData['PaymentRefNum']);
	}

	$theResourceObj = Payment::create($paymentData);
	$resultingObj = $dataService->Add($theResourceObj);
	$error = $dataService->getLastError();
	if ($error) {
		$message .= $error->getResponseBody();
	} else {
		$status = 'success';
		$message = "Payment insert successfully recorded in QuickBooks.";
		$qb_payment_id = $resultingObj->Id;
	}
	return array('status' => $status, 'message' => $message, 'qb_payment_id' => $qb_payment_id);
}

// added by nirmal 12_12_2023
function QBPaymentDelete($qb_payment_id)
{
	getQuickBooksRefreshToken(1);
	$dataService = getQuickBooksDataServiceObj(); // Prep Data Services (common file function)
	$status = 'error';
	$message = 'Error: ';
	$qb_result_payment_id = '';

	$payment = $dataService->FindbyId('payment', $qb_payment_id);
	$resultingObj = $dataService->Delete($payment);
	$error = $dataService->getLastError();
	if ($error) {
		$message .= $error->getResponseBody();
	} else {
		$status = 'success';
		$message = "Payment delete successfully recorded in QuickBooks.";
		$qb_result_payment_id = $resultingObj->Id;
	}
	return array('status' => $status, 'message' => $message, 'qb_result_payment_id' => $qb_result_payment_id);
}

// added by nirmal 12_12_2023
function getCustomerQBId($cust)
{
	include('config.php');
	$result = mysqli_query($conn2, "SELECT `qb_cust_id` FROM `cust` WHERE `id`='$cust'");
	$row = mysqli_fetch_assoc($result);
	if ($row['qb_cust_id'] != '')
		return $row['qb_cust_id'];
	else
		return false;
}

// added by nirmal 30_11_2023
function validateEmail($email)
{
	// Remove all illegal characters from email
	$email = filter_var($email, FILTER_SANITIZE_EMAIL);
	// Validate email
	if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
		return true;
	} else {
		return false;
	}
}

// added by nirmal 05_03_2024
function QBAddAccount($account_array)
{
	getQuickBooksRefreshToken(1);
	$dataService = getQuickBooksDataServiceObj();

	// Initialize variables
	$status = 'error';
	$message = 'Error: ';
	$qb_account_id = '';
	$account_name = $account_array['account_name'];
	$account_type = $account_array['account_type'];
	$account_sub_type = $account_array['account_sub_type'];
	$account_classification = $account_array['account_classification'];
	$parent_account_id = isset($account_array['parent_account_id']) ? $account_array['parent_account_id'] : '';

	// Create the account object
	$account_data = [
		"AccountType" => $account_type,
		"Name" => $account_name,
		"Classification" => $account_classification,
		"AccountSubType" => $account_sub_type,
	];

	//Add parent account if provided
	if (!empty($parent_account_id)) {
		$account_data["ParentRef"] = [
			"value" => $parent_account_id
		];
	}

	$theResourceObj = Account::create($account_data);

	// Add the account to QuickBooks
	$resultingObj = $dataService->Add($theResourceObj);
	$error = $dataService->getLastError();

	// Check for errors
	if ($error) {
		$message .= $error->getResponseBody();
	} else {
		$status = 'success';
		$message = "Account insert successfully recorded in QuickBooks.";
		$qb_account_id = $resultingObj->Id;
	}

	// Return result array
	return array(
		'status' => $status,
		'message' => $message,
		'qb_account_id' => $qb_account_id
	);
}

// added by nirmal 26_11_2024
function QBGetAccountGeneralLedger($accountId)
{
	getQuickBooksRefreshToken(1);
	$dataService = getQuickBooksDataServiceObj();
	$status = 'error';
	$message = '';
	$serviceContext = $dataService->getServiceContext();

	// Initialize ReportService
	$reportService = new ReportService($serviceContext);
	if (!$reportService) {
		$message .= 'Problem while initializing ReportService.';
		return array('status' => $status, 'message' => $message);
	}

	// Set report parameters
	// $reportService->setStartDate($startDate);
	// $reportService->setEndDate($endDate);
	$reportService->setSortOrder('descend');
	$reportService->setAccount($accountId);
	$reportService->setAccountingMethod("Accrual");

	// Fetch the Profit and Loss report
	$generalLedger = $reportService->executeReport(ReportName::GENERALLEDGER);
	if (!$generalLedger) {
		$message .= 'GENERALLEDGER report is null.';
		return array('status' => $status, 'message' => $message);
	} else {
		$status = 'success';
		return array('status' => $status, 'data' => $generalLedger);
	}
}

// added by nirmal 14_05_2024
function QBAccountUpdate($account_array)
{
	getQuickBooksRefreshToken(1);
	$dataService = getQuickBooksDataServiceObj(); // Prep Data Services (common file function)
	$status = 'error';
	$message = 'Error: ';
	$qb_account_id = '';
	$account_id = $account_array['account_id'];
	$account_name = $account_array['account_name'];
	$account_type = $account_array['account_type'];
	$account_sub_type = $account_array['account_sub_type'];
	$account_classification = $account_array['account_classification'];

	$account = $dataService->FindbyId('account', $account_id);
	$theResourceObj = Account::update($account, [
		"AccountType" => $account_type,
		"Name" => $account_name,
		"Classification" => $account_classification,
		"AccountSubType" => $account_sub_type,
	]);

	$resultingObj = $dataService->Update($theResourceObj);
	$error = $dataService->getLastError();
	if ($error) {
		$message .= $error->getResponseBody();
	} else {
		$status = 'success';
		$message = "Account update successfully recorded in QuickBooks.";
		$qb_account_id = $resultingObj->Id;
	}
	return array('status' => $status, 'message' => $message, 'qb_account_id' => $qb_account_id);
}

// added by nirmal 05_03_2024
function QBAccountStatusChange($account_array)
{
	getQuickBooksRefreshToken(1);
	$dataService = getQuickBooksDataServiceObj(); // Prep Data Services (common file function)
	$status = 'error';
	$message = 'Error: ';
	$qb_account_id = '';
	$account_id = $account_array['account_id'];
	$account_status = $account_array['account_status'];

	$account = $dataService->FindbyId('account', $account_id);
	$theResourceObj = Account::update($account, [
		"Active" => $account_status
	]);

	$resultingObj = $dataService->Update($theResourceObj);
	$error = $dataService->getLastError();
	if ($error) {
		$message .= $error->getResponseBody();
	} else {
		$status = 'success';
		$message = "Account status change successfully recorded in QuickBooks.";
		$qb_account_id = $resultingObj->Id;
	}
	return array('status' => $status, 'message' => $message, 'qb_account_id' => $qb_account_id);
}

// added by nirmal 09_08_2024
function QBAddVendor($vendor_array)
{
	getQuickBooksRefreshToken(1);
	$dataService = getQuickBooksDataServiceObj(); // Prep Data Services (common file function)
	$status = 'error';
	$message = 'Error: ';
	$qb_vendor_id = '';

	$given_name = $vendor_array['given_name'];
	$company_name = $vendor_array['company_name'];
	$display_name = $vendor_array['display_name'];
	$address = $vendor_array['address'];
	$email = $vendor_array['email'];
	$primary_phone = $vendor_array['primary_phone'];
	$mobile = $vendor_array['mobile'];

	$theResourceObj = Vendor::create([
		"BillAddr" => [
			"Line1" => $address
		],
		"PrimaryEmailAddr" => [
			"Address" => $email
		],
		"PrimaryPhone" => [
			"FreeFormNumber" => $primary_phone
		],
		"Mobile" => [
			"FreeFormNumber" => $mobile
		],
		"GivenName" => $given_name,
		"CompanyName" => $company_name,
		"DisplayName" => $display_name
	]);

	$resultingObj = $dataService->Add($theResourceObj);
	$error = $dataService->getLastError();
	if ($error) {
		$message .= $error->getResponseBody();
	} else {
		$status = 'success';
		$message = "Vendor insert successfully recorded in QuickBooks.";
		$qb_vendor_id = $resultingObj->Id;
	}

	return array('status' => $status, 'message' => $message, 'qb_vendor_id' => $qb_vendor_id);
}

// added by nirmal 02_08_2024
function QBAddJournalEntry($journal_entries_array)
{
	getQuickBooksRefreshToken(1);
	$dataService = getQuickBooksDataServiceObj();
	$status = 'error';
	$message = 'Error: ';
	$qb_journal_entry_id = '';
	$dataService->throwExceptionOnError(true);

	// Create the journal entry object
	$lines = [];
	$line_id = 1; // Start with a dynamic ID value

	foreach ($journal_entries_array as $entry) {
		$lineDetail = [
			"PostingType" => $entry['posting_type'],
			"AccountRef" => [
				"value" => $entry['account_id']
			]
		];

		// Include the account name if provided
		if (!empty($entry['account_name'])) {
			$lineDetail["AccountRef"]["name"] = $entry['account_name'];
		}

		// Add the vendor reference if it's a vendor payment (i.e., if 'entity_type' and 'entity_id' are provided)
		if (!empty($entry['entity_type']) && !empty($entry['entity_id'])) {
			$lineDetail["Entity"] = [
				"Type" => $entry['entity_type'], // 'Vendor' in case of vendor payment
				"EntityRef" => [
					"value" => $entry['entity_id']
				]
			];

			// Include the entity name if provided
			if (!empty($entry['entity_name'])) {
				$lineDetail["Entity"]["EntityRef"]["name"] = $entry['entity_name'];
			}
		}

		// ***** ADD CLASS REFERENCE HERE *****
		if (!empty($entry['class_id'])) {
			$lineDetail["ClassRef"] = [
				"value" => $entry['class_id']
			];
			// Optionally include the class name if available and your QBO SDK/API version supports it well
			// or if it's useful for your own logging/debugging before sending.
			// QBO primarily relies on the 'value' (ID).
			if (!empty($entry['class_name'])) {
				$lineDetail["ClassRef"]["name"] = $entry['class_name'];
			}
		}
		// ***********************************

		$lines[] = [
			"Id" => (string) $line_id, // Assign a unique dynamic ID
			"Description" => $entry['description'],
			"Amount" => $entry['amount'],
			"DetailType" => "JournalEntryLineDetail",
			"JournalEntryLineDetail" => $lineDetail
		];

		$line_id++; // Increment the line ID for each new line
	}

	$theResourceObj = JournalEntry::create([
		"Line" => $lines
	]);

	$resultingObj = $dataService->Add($theResourceObj);
	$error = $dataService->getLastError();
	if ($error) {
		$message .= $error->getResponseBody();
	} else {
		$status = 'success';
		$message = "Journal Entry insert successfully recorded in QuickBooks.";
		$qb_journal_entry_id = $resultingObj->Id;
	}

	return array('status' => $status, 'message' => $message, 'qb_journal_entry_id' => $qb_journal_entry_id);
}

// added by nirmal 13_08_2024
function QBDeleteJournalEntry($journal_entry_array)
{
	getQuickBooksRefreshToken(1);
	$dataService = getQuickBooksDataServiceObj(); // Prep Data Services (common file function)
	$status = 'error';
	$message = 'Error: ';
	$qb_result_journal_entry_id = '';

	$id = $journal_entry_array['id'];

	$journayentry = $dataService->FindbyId('journalentry', $id);
	$resultingObj = $dataService->Delete($journayentry);
	$error = $dataService->getLastError();
	if ($error) {
		$message .= $error->getResponseBody();
	} else {
		$status = 'success';
		$message = "Journal Entry delete successfully recorded in QuickBooks.";
		$qb_result_journal_entry_id = $resultingObj->Id;
	}
	return array('status' => $status, 'message' => $message, 'qb_result_journal_entry_id' => $qb_result_journal_entry_id);
}

// added by nirmal 05_09_2024
function QBUpdateJournalEntry($journal_entry_array)
{
	getQuickBooksRefreshToken(1);
	$dataService = getQuickBooksDataServiceObj();

	$status = 'error';
	$message = 'Error: ';
	$qb_result_journal_entry_id = '';

	$id = $journal_entry_array['id'];
	$new_amount = $journal_entry_array['amount'];
	$new_description = $journal_entry_array['description'];

	// Fetch the existing journal entry by ID
	$journal_entry = $dataService->FindbyId('journalentry', $id);

	if ($journal_entry) {
		// Update the amount and description for each journal line (if applicable)
		if (isset($journal_entry->Line) && is_array($journal_entry->Line)) {
			foreach ($journal_entry->Line as $line) {
				if (isset($line->Amount)) {
					$line->Amount = $new_amount; // Update the amount
				}
				if (isset($line->Description)) {
					$line->Description = $new_description; // Update the description
				}
			}
		}
		$resultingObj = $dataService->Update($journal_entry);
		$error = $dataService->getLastError();
		if ($error) {
			$message .= $error->getResponseBody();
		} else {
			$status = 'success';
			$message = "Journal Entry updated successfully in QuickBooks.";
			$qb_result_journal_entry_id = $resultingObj->Id;
		}
	} else {
		$message .= "Journal Entry with ID $id not found.";
	}

	return array(
		'status' => $status,
		'message' => $message,
		'qb_result_journal_entry_id' => $qb_result_journal_entry_id
	);
}

// added by nirmal 06_08_2024
function QBgetAccounts($sortOrder = 'positive_first')
{
	getQuickBooksRefreshToken(1);
	$dataService = getQuickBooksDataServiceObj();
	$status = 'error';
	$message = 'Error: ';

	// Fetch all accounts
	$accounts = $dataService->Query("SELECT * FROM Account");

	// Check for errors
	$error = $dataService->getLastError();
	if ($error) {
		$message .= $error->getHttpStatusCode() . "\n";
		$message .= $error->getOAuthHelperError() . "\n";
		$message .= $error->getResponseBody() . "\n";
		return array('status' => 'error', 'message' => $message);
	}

	// Extract account balances and sort accounts by balance
	$sortedAccounts = array();
	foreach ($accounts as $account) {
		$currentBalance = isset($account->CurrentBalance) ? $account->CurrentBalance : 0;
		$accountType = isset($account->AccountType) ? $account->AccountType : 'Unknown';
		$accountSubType = isset($account->AccountSubType) ? $account->AccountSubType : 'Unknown';
		$active = isset($account->Active) ? ($account->Active ? 'Yes' : 'No') : 'Unknown';
		$subAccount = isset($account->SubAccount) ? $account->SubAccount : 'No';
		$parentRef = isset($account->ParentRef) ? $account->ParentRef : 'None';
		$description = isset($account->Description) ? $account->Description : 'No Description';
		$sortedAccounts[] = array(
			'id' => $account->Id,
			'name' => $account->Name,
			'current_balance' => $currentBalance,
			'account_type' => $accountType,
			'account_sub_type' => $accountSubType,
			'active' => $active,
			'sub_account' => $subAccount,
			'parent_ref' => $parentRef,
			'description' => $description
		);
	}

	// Sorting accounts: positive balances first, then negative balances, and zero balances last
	usort($sortedAccounts, function ($a, $b) {
		// Compare positive balances first
		if ($a['current_balance'] > 0 && $b['current_balance'] > 0) {
			return $b['current_balance'] - $a['current_balance']; // Descending order for positive balances
		}

		// If one account has a positive balance, it comes first
		if ($a['current_balance'] > 0)
			return -1;
		if ($b['current_balance'] > 0)
			return 1;

		// Compare negative balances after positive
		if ($a['current_balance'] < 0 && $b['current_balance'] < 0) {
			return $a['current_balance'] - $b['current_balance']; // Ascending order for negative balances
		}

		// If one account has a negative balance, it comes next
		if ($a['current_balance'] < 0)
			return -1;
		if ($b['current_balance'] < 0)
			return 1;

		// If both balances are zero, maintain original order
		return 0;
	});

	return array('status' => 'success', 'data' => $sortedAccounts);
}

// added by nirmal 06_08_2024
function QBgetProfitAndLoss($startDate, $endDate)
{
	getQuickBooksRefreshToken(1); // Ensure you have a valid OAuth token
	$dataService = getQuickBooksDataServiceObj();
	$status = 'error';
	$message = 'Error: ';

	$serviceContext = $dataService->getServiceContext();

	// Initialize ReportService
	$reportService = new ReportService($serviceContext);
	if (!$reportService) {
		$message .= 'Problem while initializing ReportService.';
		return array('status' => $status, 'message' => $message);
	}

	// Set report parameters
	$reportService->setStartDate($startDate);
	$reportService->setEndDate($endDate);
	$reportService->setAccountingMethod("Accrual");

	// Fetch the Profit and Loss report
	$profitAndLossReport = $reportService->executeReport(ReportName::PROFITANDLOSS);
	if (!$profitAndLossReport) {
		$message .= 'ProfitAndLossReport is null.';
		return array('status' => $status, 'message' => $message);
	} else {
		$status = 'success';
		return array('status' => $status, 'data' => $profitAndLossReport);
	}
}

// added by nirmal 07_08_2024
function QBgetTrialBalance($startDate, $endDate)
{
	getQuickBooksRefreshToken(1); // Ensure you have a valid OAuth token
	$dataService = getQuickBooksDataServiceObj();
	$status = 'error';
	$message = 'Error: ';

	$serviceContext = $dataService->getServiceContext();

	// Initialize ReportService
	$reportService = new ReportService($serviceContext);
	if (!$reportService) {
		$message .= 'Problem while initializing ReportService.';
		return array('status' => $status, 'message' => $message);
	}

	// Set report parameters
	$reportService->setStartDate($startDate);
	$reportService->setEndDate($endDate);
	$reportService->setAccountingMethod("Accrual");

	// Fetch the Trial Balance report
	$trialBalanceReport = $reportService->executeReport(ReportName::TRIALBALANCE);
	if (!$trialBalanceReport) {
		$message .= 'TrialBalanceReport is null.';
		return array('status' => $status, 'message' => $message);
	} else {
		$status = 'success';
		return array('status' => $status, 'data' => $trialBalanceReport);
	}
}

// added by nirmal 07_08_2024
function QBgetBalanceSheet($startDate, $endDate)
{
	getQuickBooksRefreshToken(1); // Ensure you have a valid OAuth token
	$dataService = getQuickBooksDataServiceObj();
	$status = 'error';
	$message = 'Error: ';

	$serviceContext = $dataService->getServiceContext();

	// Initialize ReportService
	$reportService = new ReportService($serviceContext);
	if (!$reportService) {
		$message .= 'Problem while initializing ReportService.';
		return array('status' => $status, 'message' => $message);
	}

	// Set report parameters
	// $reportService->setStartDate($startDate);
	$reportService->setEndDate($endDate);
	$reportService->setAccountingMethod("Accrual");

	// Fetch the Balance Sheet report
	$balanceSheetReport = $reportService->executeReport(ReportName::BALANCESHEET);
	if (!$balanceSheetReport) {
		$message .= 'BalanceSheetReport is null.';
		return array('status' => $status, 'message' => $message);
	} else {
		// $balanceSheetReport->DownloadPDF();
		$status = 'success';
		return array('status' => $status, 'data' => $balanceSheetReport);
	}
}

// added by nirmal 07_08_2024
function QBgetJournalReport($startDate, $endDate)
{
	getQuickBooksRefreshToken(1);
	$dataService = getQuickBooksDataServiceObj();
	$status = 'error';
	$message = 'Error: ';

	$serviceContext = $dataService->getServiceContext();

	// Initialize ReportService
	$reportService = new ReportService($serviceContext);
	if (!$reportService) {
		$message .= 'Problem while initializing ReportService.';
		return array('status' => $status, 'message' => $message);
	}

	// Set report parameters

	// $reportService->setSortOrder('Num');
	$reportService->setSortBy('create_date'); // Sort by transaction date
	$reportService->setSortOrder('descend');
	$reportService->setStartDate($startDate);
	$reportService->setEndDate($endDate);
	$reportService->setAccountingMethod("Accrual");

	// Fetch the Journal Report
	$journalReport = $reportService->executeReport(ReportName::JOURNALREPORT);
	if (!$journalReport) {
		$message .= 'JournalReport is null.';
		return array('status' => $status, 'message' => $message);
	} else {
		$status = 'success';
		return array('status' => $status, 'data' => $journalReport);
	}
}

function QBGetJournalEntry($journalEntryID)
{
	getQuickBooksRefreshToken(1);
	$dataService = getQuickBooksDataServiceObj(); // Prep Data Services (common file function)
	$status = 'error';
	$message = 'Error: ';

	$journalEntry = $dataService->FindbyId('journalentry', $journalEntryID);
	$error = $dataService->getLastError();
	if ($error) {
		$message .= 'JournalEntry is null.';
		return array('status' => $status, 'message' => $message);
	} else {
		$status = 'success';
		return array('status' => $status, 'data' => $journalEntry);
	}
}

// added by nirmal 13_08_2024
function QBgetAllVendors()
{
	getQuickBooksRefreshToken(1);
	$dataService = getQuickBooksDataServiceObj();
	$status = 'error';
	$message = 'Error: ';

	try {
		$vendors = $dataService->Query("SELECT * FROM Vendor");
		if (!$vendors) {
			$message .= 'No vendors found.';
			return array('status' => $status, 'message' => $message);
		} else {
			$status = 'success';
			return array('status' => $status, 'data' => $vendors);
		}
	} catch (Exception $e) {
		$message .= 'Exception: ' . $e->getMessage();
		return array('status' => $status, 'message' => $message);
	}
}

// added by nirmal 15_08_2024
function QBAddBill($bill_array)
{
	getQuickBooksRefreshToken(1);
	$dataService = getQuickBooksDataServiceObj(); // Prep Data Services (common file function)
	$status = 'error';
	$message = 'Error: ';
	$qb_bill_id = '';

	$amount = $bill_array['amount'];
	$from_account_id = $bill_array['from_account_id'];
	$vendor_id = $bill_array['vendor_id'];

	$theResourceObj = Bill::create([
		"Line" =>
			[
				[
					"Id" => "1",
					"Amount" => $amount,
					"DetailType" => "AccountBasedExpenseLineDetail",
					"AccountBasedExpenseLineDetail" =>
						[
							"AccountRef" =>
								[
									"value" => $from_account_id
								]
						]
				]
			],
		"VendorRef" =>
			[
				"value" => $vendor_id
			]
	]);

	$resultingObj = $dataService->Add($theResourceObj);
	$error = $dataService->getLastError();
	if ($error) {
		$message .= $error->getResponseBody();
	} else {
		$status = 'success';
		$message = "Bill insert successfully recorded in QuickBooks.";
		$qb_bill_id = $resultingObj->Id;
	}

	return array('status' => $status, 'message' => $message, 'qb_bill_id' => $qb_bill_id);
}

// added by nirmal 16_08_2024
function QBDeleteBill($bill_array)
{
	getQuickBooksRefreshToken(1);
	$dataService = getQuickBooksDataServiceObj(); // Prep Data Services (common file function)
	$status = 'error';
	$message = 'Error: ';
	$qb_bill_id = '';
	$bill_id = $bill_array['bill_id'];

	$bill = $dataService->FindbyId('bill', $bill_id);
	$resultingObj = $dataService->Delete($bill);
	$error = $dataService->getLastError();
	if ($error) {
		$message .= $error->getResponseBody();
	} else {
		$status = 'success';
		$message = "Bill delete successfully recorded in QuickBooks.";
		$qb_bill_id = $resultingObj->Id;
	}

	return array('status' => $status, 'message' => $message, 'qb_bill_id' => $qb_bill_id);
}

// added by nirmal 15_08_2024
function QBAddBillPayment($bill_payment_array)
{
	getQuickBooksRefreshToken(1);
	$dataService = getQuickBooksDataServiceObj(); // Prep Data Services (common file function)
	$status = 'error';
	$message = 'Error: ';
	$qb_bill_payment_id = '';

	$amount = $bill_payment_array['amount'];
	$bank_account_id = $bill_payment_array['bank_account_id'];
	$vendor_id = $bill_payment_array['vendor_id'];
	$bill_id = $bill_payment_array['bill_id']; // ID of the bill you're paying
	$payment_method = $bill_payment_array['payment_method']; // e.g., "Check", "CreditCard"
	$ref = $bill_payment_array['ref']; // e.g., "Check", "CreditCard"

	$theResourceObj = BillPayment::create([
		"VendorRef" => [
			"value" => $vendor_id
		],
		"TotalAmt" => $amount,
		"PayType" => $payment_method,
		"PrivateNote" => $ref,
		"DocNumber" => $ref,
		"Line" => [
			[
				"Amount" => $amount,
				"LinkedTxn" => [
					[
						"TxnId" => $bill_id,
						"TxnType" => "Bill"
					]
				]
			]
		],
		"CheckPayment" => [
			"BankAccountRef" => [
				"value" => $bank_account_id
			]
		]
	]);

	$resultingObj = $dataService->Add($theResourceObj);
	$error = $dataService->getLastError();
	if ($error) {
		$message .= $error->getResponseBody();
	} else {
		$status = 'success';
		$message = "Bill payment insert successfully recorded in QuickBooks.";
		$qb_bill_payment_id = $resultingObj->Id;
	}

	return array('status' => $status, 'message' => $message, 'qb_bill_payment_id' => $qb_bill_payment_id);
}

// added by nirmal 16_08_2024
function QBDeleteBillPayment($bill_payment_array)
{
	getQuickBooksRefreshToken(1);
	$dataService = getQuickBooksDataServiceObj(); // Prep Data Services (common file function)
	$status = 'error';
	$message = 'Error: ';
	$qb_bill_payment_id = '';
	$bill_payment_id = $bill_payment_array['bill_payment_id'];

	$billpayment = $dataService->FindbyId('billpayment', $bill_payment_id);
	$resultingObj = $dataService->Delete($billpayment);
	$error = $dataService->getLastError();
	if ($error) {
		$message .= $error->getResponseBody();
	} else {
		$status = 'success';
		$message = "Bill payment delete successfully recorded in QuickBooks.";
		$qb_bill_payment_id = $resultingObj->Id;
	}
	return array('status' => $status, 'message' => $message, 'qb_bill_payment_id' => $qb_bill_payment_id);
}

function QBAddExpenses($expenses_array, $payment_method, $account_ref, $payee_type, $payee_ref, $ref_no)
{
	getQuickBooksRefreshToken(1);
	$dataService = getQuickBooksDataServiceObj(); // Prep Data Services (common file function)
	$status = 'error';
	$message = 'Error: ';
	$qb_expense_id = '';

	$lineItems = [];
	foreach ($expenses_array as $expense) {
		// Build the AccountBasedExpenseLineDetail array
		$accountDetails = [
			"AccountRef" => [
				"value" => $expense['expense_account_ref']
			]
		];
		// Conditionally add CustomerRef inside AccountBasedExpenseLineDetail if the payee type is customer
		if ($payee_type == 'customer') {
			$accountDetails['CustomerRef'] = [
				"value" => $payee_ref
			];
		}
		// Build the line item array
		$lineItems[] = [
			"Amount" => $expense['amount'],
			"DetailType" => "AccountBasedExpenseLineDetail",
			"AccountBasedExpenseLineDetail" => $accountDetails,
			"Description" => $expense['description'] // Ensure Description is correctly added
		];
	}

	// Prepare the base data array for the Purchase object
	$purchaseData = [
		"PaymentType" => $payment_method,
		"AccountRef" => [
			"value" => $account_ref
		],
		"Line" => $lineItems,
		"DocNumber" => $ref_no
	];

	// Add EntityRef based on payee type before creating the object
	if ($payee_type == 'vendor') {
		$purchaseData['EntityRef'] = [
			"type" => "Vendor",
			"value" => $payee_ref
		];
	} elseif ($payee_type == 'customer') {
		$purchaseData['EntityRef'] = [
			"type" => "Customer",
			"value" => $payee_ref
		];
	} elseif ($payee_type == 'employee') {
		$purchaseData['EntityRef'] = [
			"type" => "Employee",
			"value" => $payee_ref
		];
	}

	// Create the Purchase object with all the necessary data
	$theResourceObj = Purchase::create($purchaseData);
	$resultingObj = $dataService->Add($theResourceObj);
	$error = $dataService->getLastError();
	if ($error) {
		$message .= $error->getResponseBody();
	} else {
		$status = 'success';
		$message = "Expenses insert successfully recorded in QuickBooks.";
		$qb_expense_id = $resultingObj->Id;
	}

	return array('status' => $status, 'message' => $message, 'qb_expense_id' => $qb_expense_id);
}

// added by nirmal 26_08_2024
function QBAddItem($item_array)
{
	getQuickBooksRefreshToken(1);
	$dataService = getQuickBooksDataServiceObj();
	$status = 'error';
	$message = 'Error: ';
	$qb_item_id = '';

	$name = $item_array['name'];
	$unit_price = $item_array['unit_price'];
	$desc = $item_array['desc'];
	$income_account_id = $item_array['income_account_id'];
	$expense_account_id = $item_array['expense_account_id'];
	$asset_account_id = $item_array['asset_account_id'];
	$quantity_on_hand = $item_array['quantity_on_hand'];
	$inv_start_date = $item_array['inv_start_date'];

	$theResourceObj = Item::create([
		"Name" => $name,
		"PurchaseCost" => $unit_price,
		"Description" => $desc,
		"IncomeAccountRef" => [ // Sales of Product Income
			"value" => $income_account_id
		],
		"ExpenseAccountRef" => [ // Cost of Goods Sold
			"value" => $expense_account_id
		],
		"AssetAccountRef" => [ // Inventory Asset
			"value" => $asset_account_id
		],
		"Type" => "Inventory",
		"TrackQtyOnHand" => true, // If item type if Inventory then TrackQtyOnHand has to be true
		"QtyOnHand" => $quantity_on_hand, // system inventory item total of all sub_system & stores
		"InvStartDate" => $inv_start_date // date of adding
	]);

	$resultingObj = $dataService->Add($theResourceObj);
	$error = $dataService->getLastError();

	if ($error) {
		$message .= $error->getResponseBody();
	} else {
		$status = 'success';
		$message = "Item insert successfully recorded in QuickBooks.";
		$qb_item_id = $resultingObj->Id;
	}

	return [
		'status' => $status,
		'message' => $message,
		'qb_item_id' => $qb_item_id
	];
}

// added by nirmal 28_08_2024
function QBCreateServiceItem($item_array)
{
	getQuickBooksRefreshToken(1);
	$dataService = getQuickBooksDataServiceObj();
	$status = 'error';
	$message = 'Error: ';
	$qb_item_id = '';

	$name = $item_array['name'];
	$desc = $item_array['desc'];
	$income_account_id = $item_array['income_account_id'];

	$theResourceObj = Item::create([
		"Name" => $name,
		"Description" => $desc,
		"IncomeAccountRef" => [
			"value" => $income_account_id
		],
		"Type" => "Service"
	]);

	$resultingObj = $dataService->Add($theResourceObj);
	$error = $dataService->getLastError();

	if ($error) {
		$message .= $error->getResponseBody();
	} else {
		$status = 'success';
		$message = "Service item insert successfully recorded in QuickBooks and id saved in settings table.";
		$qb_item_id = $resultingObj->Id;
	}

	return [
		'status' => $status,
		'message' => $message,
		'qb_item_id' => $qb_item_id
	];
}

function fetchAccountID($conn, $accountName)
{
	$query = "SELECT `qb_account_id` FROM `accounts` WHERE `name` = '" . $accountName . "' AND `status` = 1";
	$result = mysqli_query($conn, $query);

	if ($result && ($row = mysqli_fetch_row($result))) {
		if ($row[0] !== null && $row[0] !== '') {
			return $row[0];
		} else {
			return "Error: `qb_account_id` is NULL for the account: $accountName";
		}
	} else {
		return "Error: Account '$accountName' not found or query failed.";
	}
}

function buildJournalEntry($conn, $amount, $debitAccountName, $creditAccountName, $description, $debitEntityType = "", $debitEntityID = null, $creditEntityType = "", $creditEntityID = null)
{
	// Fetch Debit Account ID
	$debitAccountID = fetchAccountID($conn, $debitAccountName);
	if (strpos($debitAccountID, "Error") !== false) {
		return array("error" => $debitAccountID);
	}

	// Fetch Credit Account ID
	$creditAccountID = fetchAccountID($conn, $creditAccountName);
	if (strpos($creditAccountID, "Error") !== false) {
		return array("error" => $creditAccountID);
	}

	// Build the journal entry array
	$journalEntry = array(
		array(
			"posting_type" => "Debit",
			"account_id" => $debitAccountID,
			"account_name" => $debitAccountName,
			"amount" => $amount,
			"description" => $description,
			"entity_type" => $debitEntityType,
			"entity_id" => $debitEntityID
		),
		array(
			"posting_type" => "Credit",
			"account_id" => $creditAccountID,
			"account_name" => $creditAccountName,
			"amount" => $amount,
			"description" => $description,
			"entity_type" => $creditEntityType,
			"entity_id" => $creditEntityID
		)
	);

	return $journalEntry;
}

function processQBPayment($creditAccountName, $debitAccountName, $conn, $paymentAmount, $qb_cust_id, $description)
{
	// Fetch the Credit Account ID using the fetchAccountID function
	$creditAccountID = fetchAccountID($conn, $creditAccountName);
	if (strpos($creditAccountID, "Error") !== false) {
		return $creditAccountID; // Return the error message directly
	}

	// Fetch the Debit Account ID using the fetchAccountID function
	$debitAccountID = fetchAccountID($conn, $debitAccountName);
	if (strpos($debitAccountID, "Error") !== false) {
		return $debitAccountID; // Return the error message directly
	}

	// Create the journal entry array
	$invoice_payment_journal_entry = [
		[
			"posting_type" => "Debit",
			"account_id" => $debitAccountID,
			"account_name" => $debitAccountName,
			"amount" => $paymentAmount,
			"description" => $description
		],
		[
			"posting_type" => "Credit",
			"account_id" => $creditAccountID,
			"account_name" => $creditAccountName,
			"amount" => $paymentAmount,
			"description" => $description,
			"entity_type" => "Customer",
			"entity_id" => $qb_cust_id
		]
	];

	return $invoice_payment_journal_entry;
}

// added by nirmal 09_09_2024
function QBAddEmployee($employeeArray)
{
	getQuickBooksRefreshToken(1);
	$dataService = getQuickBooksDataServiceObj();
	$mobile = '';

	$givenName = $employeeArray['given_name']; // mandatory
	$familyName = $employeeArray['family_name']; // mandatory
	$mobile = $employeeArray['mobile'];

	// Check if employee already exists in QuickBooks
	$existingEmployee = findEmployeeInQB($dataService, $givenName, $familyName);
	if ($existingEmployee) {
		return [
			'status' => 'error',
			'message' => "Employee '$givenName $familyName' already exists in QuickBooks.",
			'qb_employee_id' => $existingEmployee->Id
		];
	}

	$employee = Employee::create([
		"GivenName" => $givenName,
		"FamilyName" => $familyName,
		"PrimaryPhone" => [
			"FreeFormNumber" => $mobile,
		],
	]);
	$resultingEmployeeObj = $dataService->Add($employee);

	$error = $dataService->getLastError();
	if ($error) {
		return [
			'status' => 'error',
			'message' => 'Error: ' . $error->getResponseBody()
		];
	} else {
		return [
			'status' => 'success',
			'message' => 'Employee created successfully in QuickBooks.',
			'qb_employee_id' => $resultingEmployeeObj->Id
		];
	}
}

// added by nirmal 11_09_2024
function findEmployeeInQB($dataService, $givenName, $familyName)
{
	// Query QuickBooks to check if an employee with the given name and family name exists
	$query = "SELECT * FROM Employee WHERE GivenName = '$givenName' AND FamilyName = '$familyName' AND Active = true";
	$employees = $dataService->Query($query);
	if (!empty($employees)) {
		return $employees[0]; // Return the first matching employee
	}
	return null; // No employee found
}

// added by nirmal 11_09_2024
function QBUpdateEmployee($employeeArray)
{
	getQuickBooksRefreshToken(1);
	$dataService = getQuickBooksDataServiceObj();

	$qbEmployeeId = $employeeArray['qb_employee_id']; // QuickBooks Employee ID is mandatory for updating
	$givenName = $employeeArray['given_name']; // mandatory
	$familyName = $employeeArray['family_name']; // mandatory
	$mobile = $employeeArray['mobile']; // optional (can be empty)

	// Attempt to find the existing employee in QuickBooks
	try {
		$existingEmployee = $dataService->FindById('Employee', $qbEmployeeId);
	} catch (Exception $e) {
		return [
			'status' => 'error',
			'message' => "Error retrieving employee with ID '$qbEmployeeId': " . $e->getMessage()
		];
	}

	// If the employee is not found, return an error
	if (!$existingEmployee) {
		return [
			'status' => 'error',
			'message' => "Employee with ID '$qbEmployeeId' not found in QuickBooks."
		];
	}

	// Update employee details
	$existingEmployee->GivenName = $givenName;
	$existingEmployee->FamilyName = $familyName;
	if (!empty($mobile)) {
		$existingEmployee->PrimaryPhone = [
			"FreeFormNumber" => $mobile,
		];
	}
	// Attempt to update the employee in QuickBooks
	try {
		$updatedEmployeeObj = $dataService->Update($existingEmployee);
	} catch (Exception $e) {
		return [
			'status' => 'error',
			'message' => "Error updating employee: " . $e->getMessage()
		];
	}

	// Check for any errors returned by QuickBooks
	$error = $dataService->getLastError();
	if ($error) {
		return [
			'status' => 'error',
			'message' => 'QuickBooks Error: ' . $error->getResponseBody()
		];
	} else {
		return [
			'status' => 'success',
			'message' => 'Employee updated successfully in QuickBooks.',
			'qb_employee_id' => $updatedEmployeeObj->Id
		];
	}
}

// added by nirmal 21_04_2025
function QBGetAllCustomers()
{
	getQuickBooksRefreshToken(1);
	$dataService = getQuickBooksDataServiceObj();

	$allCustomers = []; // Array to store all fetched customers
	$startPosition = 1;
	$maxResults = 100; // Number of records to fetch per page (can be adjusted)

	$status = 'error'; // Default status
	$message = 'Unknown Error.'; // Default error message

	try {
		do {
			// Construct the query with pagination
			$query = "SELECT * FROM Customer STARTPOSITION $startPosition MAXRESULTS $maxResults";

			// Execute the query for the current page
			$customersPage = $dataService->Query($query);

			// Check for API errors after the query
			$error = $dataService->getLastError();
			if ($error) {
				// If an API error occurs, return the error details immediately
				$message = "QuickBooks API Error: " . $error->getResponseBody();
				return ['status' => 'error', 'message' => $message];
			}

			// If results were returned for this page
			if (!empty($customersPage) && is_array($customersPage)) {
				// Add the customers from the current page to the main array
				$allCustomers = array_merge($allCustomers, $customersPage);

				// If the number of results in this page is less than MAXRESULTS,
				// it means we've reached the last page
				if (count($customersPage) < $maxResults) {
					break; // Exit the do-while loop
				} else {
					// Otherwise, update the start position for the next page
					$startPosition += $maxResults;
				}
			} else {
				// If no customers were returned on the first page or a subsequent page, stop
				// This handles the case of 0 customers or the last page having exactly maxResults records
				break; // Exit the do-while loop
			}

		} while (true); // Loop continues until explicitly broken

		// If we successfully exited the loop (fetched all data or found no customers)
		$status = 'success';
		// The 'data' key will contain all fetched customers (can be an empty array)
		return ['status' => $status, 'data' => $allCustomers];

	} catch (Exception $e) {
		// Catch any unexpected PHP exceptions
		$message = 'Exception: ' . $e->getMessage();
		return array('status' => 'error', 'message' => $message);
	}
}

function QBErrorCount()
{
	include('config.php');
	$result = mysqli_query($conn2, "SELECT COUNT(`id`) AS count FROM qb_queue_error_log");
	$row = mysqli_fetch_assoc($result);
	if (!empty($row)) {
		return $row['count'];
	} else {
		return 0;
	}
}

function QBQueueCount()
{
	include('config.php');
	$result = mysqli_query($conn2, "SELECT COUNT(`id`) AS count FROM qb_queue");
	$row = mysqli_fetch_assoc($result);
	if (!empty($row)) {
		return $row['count'];
	} else {
		return 0;
	}
}

// --------------------------- QUICKBOOKS FUNCTIONS END --------------------------- //

// added by nirmal 05_11_2024

function isSalesmanPaymentDepositActive()
{
	if (isset($_SESSION['is_salesman_payment_deposits_active'])) {
		return $_SESSION['is_salesman_payment_deposits_active'];
	} else {
		include('config.php');
		$result = mysqli_query($conn2, "SELECT `value` FROM settings WHERE `setting`='salesman_payment_deposits'");
		$row = mysqli_fetch_assoc($result);
		if (!empty($row)) {
			if ($row['value'] == 1) {
				$_SESSION["is_salesman_payment_deposits_active"] = 1;
			} else {
				$_SESSION["is_salesman_payment_deposits_active"] = 0;
			}
		} else {
			$_SESSION["is_salesman_payment_deposits_active"] = 0;
		}
		return $_SESSION['is_salesman_payment_deposits_active'];
	}
}

// added by nirmal 10_12_2024

function isMaintenanceModeActive()
{
	include('config.php');
	$result = mysqli_query($conn2, "SELECT `value` FROM settings WHERE `setting`='maintenance_mode'");
	$row = mysqli_fetch_assoc($result);
	if (!empty($row)) {
		if ($row['value'] == 1) {
			return 1;
		} else {
			return 0;
		}
	} else {
		return 0;
	}
}

// added by nirmal 26_12_2024
function salesmanPaymentDepositStartDate()
{
	if (isset($_SESSION['salesman_payment_deposits_start_date'])) {
		return $_SESSION['salesman_payment_deposits_start_date'];
	} else {
		include('config.php');
		$result = mysqli_query($conn2, "SELECT `value` FROM settings WHERE `setting`='salesman_payment_deposits_start_date'");
		$row = mysqli_fetch_assoc($result);
		if (!empty($row)) {
			if ($row['value'] != 0) {
				$_SESSION["salesman_payment_deposits_start_date"] = $row['value'];
			} else {
				$_SESSION["salesman_payment_deposits_start_date"] = 0;
			}
		} else {
			$_SESSION["salesman_payment_deposits_start_date"] = 0;
		}
		return $_SESSION['salesman_payment_deposits_start_date'];
	}
}

// added by nirmal 28_01_2025
function sendPaymentSMS($payment_id, $cust, $amount)
{
	$timenow = timeNow();
	$date_now = substr($timenow, 0, 10);
	$sub_system = $_COOKIE['sub_system'];
	$msg = '';
	$bm_type = false;
	$decimal = 0;
	$inf_from_email = inf_from_email();
	$sms_data = json_decode(sms_credential($sub_system));
	$sms_user = $sms_data->{"user"};
	$sms_pass = $sms_data->{"pass"};
	$sms_balance = $sms_data->{"balance"};
	$sms_device = $sms_data->{"device"};
	$sms_sender_id = $sms_data->{"sms_sender_id"};
	include('config.php');

	$result = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='systemid'");
	$row = mysqli_fetch_assoc($result);
	$systemid = $row['value'];

	$result = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='decimal'");
	$row = mysqli_fetch_assoc($result);
	$decimal = $row['value'];

	$result = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='currency'");
	$row = mysqli_fetch_assoc($result);
	$currency = $row['value'];

	$result = mysqli_query($conn2, "SELECT cu.sms,cu.mobile FROM cust cu WHERE cu.id='$cust'");
	$row = mysqli_fetch_assoc($result);
	$sms_cust = $row['sms'];
	$mobile = $row['mobile'];

	$result = mysqli_query($conn, "SELECT py.invoice_no,st.shop_name_sms FROM payment py, stores st WHERE py.store=st.id AND py.id='$payment_id'");
	$row = mysqli_fetch_assoc($result);
	$invoice_no = $row['invoice_no'];
	$inf_company = $row['shop_name_sms'];

	if ($invoice_no == 0) {
		$bm_type = true;
	} else {
		$result = mysqli_query($conn, "SELECT `type` FROM bill_main WHERE invoice_no='$invoice_no'");
		$row = mysqli_fetch_assoc($result);
		$bm_type = $row['type'];
		if ($bm_type != 3)
			$bm_type = true;
	}
	if (($sms_cust == 1) && ($sms_balance > 0) && ($_SERVER['SERVER_NAME'] == inf_url_primary()) && ($bm_type) && ($systemid == 17)) {
		$query1 = "SELECT SUM(bm.`invoice_+total`) + SUM(bm.`invoice_-total`) as `total`, bm.`type` FROM bill_main bm WHERE bm.`status`!=0 AND bm.exclude=0 AND bm.`lock`=1 AND bm.`cust`='$cust'";
		$result1 = mysqli_query($conn, $query1);
		$row1 = mysqli_fetch_assoc($result1);
		$totalinv = $row1['total'];
		$bm_type = $row1['type'];
		$query1 = "SELECT SUM(py.`amount`) as `pay` FROM payment py WHERE py.`status`='0' AND py.`cust`='$cust' AND py.chque_return='0'";
		$result1 = mysqli_query($conn, $query1);
		$row1 = mysqli_fetch_assoc($result1);
		$totalpay = $row1['pay'];
		$credit_balance = $totalinv - $totalpay;

		// send total outstanding details only for stores, which has sms_outstanding = 1;
		$query = "SELECT s.`sms_outstanding` FROM stores s, cust c WHERE c.`associated_store`=s.`id` AND c.`id`='$cust'";
		$result = mysqli_query($conn, $query);
		$row = mysqli_fetch_row($result);
		$outstanding = $row[0];

		if ($outstanding == 1) {
			$message_header = $inf_company . "\n\nPayment Inv: " . str_pad($payment_id, 7, "0", STR_PAD_LEFT) . " Amount = " . $currency . " " . number_format($amount, $decimal) . "\n\nTotal Outstanding = " . $currency . " " . number_format($credit_balance, $decimal) . "\n\n";
		} else {
			$message_header = $inf_company . "\n\nPayment Inv: " . str_pad($payment_id, 7, "0", STR_PAD_LEFT) . " Amount = " . $currency . " " . number_format($amount, $decimal) . "\n\n";
		}
		$message = $message_header . "Your payment has been received.\n\nThank you!";
		$text = $message;

		if ($sms_device == "") {
			$text = [
				"Text" => $message,
				"Number" => $mobile,
				"SenderId" => $sms_sender_id,
				"DRNotifyUrl" => "https://www.domainname.com/notifyurl",
				"DRNotifyHttpMethod" => "POST",
				"Tool" => "API"
			];
			$res = systemID17SMSSend($text);
			if ($res['status'] == 'success') {
				$res_message = $res['message'];
				$mailstatus = true;
				$sms_balance--;
			} else {
				$mailstatus = false;
			}
		} else {
			$url = "http://mqtt.negoit.info/sms_gw.php?dev=$sms_device&ref1=pay&ref2=$payment_id&u=$sms_user&p=$sms_pass&to=$mobile&text=$text";
			setcookie("sms_balance", $sms_balance, time() + 3600 * 10);
			file($url);
			$mailstatus = false;
		}

		$query = "SELECT MAX(id) FROM sms";
		$row = mysqli_fetch_row(mysqli_query($conn, $query));
		$max_id = $row[0];
		$query = "SELECT MIN(id) FROM sms";
		$row = mysqli_fetch_row(mysqli_query($conn, $query));
		$min_id = $row[0];
		$next_id = $max_id + 1;
		$query = "UPDATE `sms` SET `id`='$next_id',`timestamp`='$timenow',`case`='2',`ref`='$payment_id',`text`='$message' WHERE id='$min_id'";
		mysqli_query($conn, $query);

		if ($mailstatus) {
			$query = "UPDATE `payment` SET `sms`='1' WHERE `id`='$payment_id'";
			mysqli_query($conn, $query);
			if (set_sms_balance($sub_system, $sms_balance))
				$msg = 'SMS Sent<hr />';
			else
				$msg = 'Database cound not be updated<hr />';
		} else {
			$msg = 'SMS could not be sent<hr />';
		}
	} else if (($sms_cust == 1) && ($sms_balance > 0) && ($_SERVER['SERVER_NAME'] == inf_url_primary()) && ($bm_type) && (strpos($mobile, "7") == 1)) {
		$query1 = "SELECT SUM(bm.`invoice_+total`) + SUM(bm.`invoice_-total`) as `total`, bm.`type` FROM bill_main bm WHERE bm.`status`!=0 AND bm.exclude=0 AND bm.`lock`=1 AND bm.`cust`='$cust'";
		$result1 = mysqli_query($conn, $query1);
		$row1 = mysqli_fetch_assoc($result1);
		$totalinv = $row1['total'];
		$bm_type = $row1['type'];
		$query1 = "SELECT SUM(py.`amount`) as `pay` FROM payment py WHERE py.`status`='0' AND py.`cust`='$cust' AND py.chque_return='0'";
		$result1 = mysqli_query($conn, $query1);
		$row1 = mysqli_fetch_assoc($result1);
		$totalpay = $row1['pay'];
		$credit_balance = $totalinv - $totalpay;

		// send total outstanding details only for stores, which has sms_outstanding = 1;
		$query = "SELECT s.`sms_outstanding` FROM stores s, cust c WHERE c.`associated_store`=s.`id` AND c.`id`='$cust'";
		$result = mysqli_query($conn, $query);
		$row = mysqli_fetch_row($result);
		$outstanding = $row[0];

		if ($outstanding == 1) {
			$message_header = str_replace(" ", "+", $inf_company) . '+-NLC-Payment+Inv:+' . str_pad($payment_id, 7, "0", STR_PAD_LEFT) . '++++Amount+=+' . number_format($amount, $decimal) . '+-NLC-Total+Outstanding+=++' . number_format($credit_balance, $decimal) . '+-NLC-';
		} else {
			$message_header = str_replace(" ", "+", $inf_company) . '+-NLC-Payment+Inv:+' . str_pad($payment_id, 7, "0", STR_PAD_LEFT) . '++++Amount+=+' . number_format($amount, $decimal) . '+-NLC-';
		}
		$message = $message_header . 'Your+payment+has+been+received+-NLC-Thank+you!';
		$sms_balance--;
		$text = urlencode($message);

		if ($sms_device == "") {
			$url = "http://www.textit.biz/sendmsg/?id=$sms_user&pw=$sms_pass&eco=Y&to=$mobile&text=$text";
			$ret = file($url);
			$res = explode(":", $ret[0]);
			if (trim($res[0]) == "OK")
				$mailstatus = true;
			else
				$mailstatus = false;
		} else {
			$url = "http://mqtt.negoit.info/sms_gw.php?dev=$sms_device&ref1=pay&ref2=$payment_id&u=$sms_user&p=$sms_pass&to=$mobile&text=$text";
			setcookie("sms_balance", $sms_balance, time() + 3600 * 10);
			file($url);
			$mailstatus = false;
		}

		$query = "SELECT MAX(id) FROM sms";
		$row = mysqli_fetch_row(mysqli_query($conn, $query));
		$max_id = $row[0];
		$query = "SELECT MIN(id) FROM sms";
		$row = mysqli_fetch_row(mysqli_query($conn, $query));
		$min_id = $row[0];
		$next_id = $max_id + 1;
		$query = "UPDATE `sms` SET `id`='$next_id',`timestamp`='$timenow',`case`='2',`ref`='$payment_id',`text`='$message' WHERE id='$min_id'";
		mysqli_query($conn, $query);

		if ($mailstatus) {
			$query = "UPDATE `payment` SET `sms`='1' WHERE `id`='$payment_id'";
			mysqli_query($conn, $query);
			if (set_sms_balance($sub_system, $sms_balance))
				$msg = 'SMS Sent<hr />';
			else
				$msg = 'Database Cound Not be Updated<hr />';
		} else {
			$msg = 'SMS Could not be Sent<hr />';
		}
	}
}

// added by nirmal 17_02_2025
function getTaxRate()
{
	if (isset($_SESSION['tax_rate'])) {
		return $_SESSION['tax_rate'];
	} else {
		include('config.php');
		$result = mysqli_query($conn2, "SELECT `value` FROM settings WHERE `setting`='tax'");
		$row = mysqli_fetch_assoc($result);
		if (!empty($row)) {
			if ($row['value'] != 0) {
				$_SESSION["tax_rate"] = $row['value'];
			} else {
				$_SESSION["tax_rate"] = 0;
			}
		} else {
			$_SESSION["tax_rate"] = 0;
		}
		return $_SESSION['tax_rate'];
	}
}

// added by nirmal 18_02_2025
function generateBatchID()
{
	return 'batch_' . substr(md5(microtime() . mt_rand()), 0, 16); // 16 characters
}

// added by nirmal 17_03_2025
function isTimeShow()
{
	if (isset($_SESSION['time_show'])) {
		return $_SESSION['time_show'];
	} else {
		include('config.php');
		$result = mysqli_query($conn2, "SELECT `value` FROM settings WHERE `setting`='time_show'");
		$row = mysqli_fetch_assoc($result);
		if (!empty($row)) {
			if ($row['value'] != 0) {
				$_SESSION["time_show"] = $row['value'];
			} else {
				$_SESSION["time_show"] = 0;
			}
		} else {
			$_SESSION["time_show"] = 0;
		}
		return $_SESSION['time_show'];
	}
}
?>