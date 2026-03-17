<?php
// updated by nirmal 20_10_2023, 21_12_2023
function getOrder($method)
{
	global $bi_invoice_no, $bi_cust, $bi_district, $bi_billed_by, $bi_billed_date, $bi_billed_time, $bi_seen_by, $bi_seen_date,
	$bi_seen_time, $bi_packed_by, $bi_packed_date, $bi_packed_time, $bi_shipped_by, $bi_shipped_date, $bi_shipped_time,
	$bi_deliverd_by, $bi_deliverd_date, $bi_deliverd_time, $menu_by, $menu_date, $type, $bi_status, $bi_type, $bm_store, $month_filter, $bi_item_desc, $bi_item_qty, $bi_rt_item_desc, $bi_rt_item_qty, $bi_item_id, $bi_rt_item_id;
	;

	$bi_item_desc = $bi_item_qty = $bi_rt_item_desc = $bi_rt_item_qty = $bi_item_desc = $bi_item_qty = $bi_rt_item_desc = $bi_rt_item_qty = $bi_item_id = $bi_rt_item_id = array();
	$mfilter_qry = '';
	$store = $_COOKIE['store'];
	$user = $_COOKIE['user_id'];
	$bi_invoice_no = array();
	$systemid = inf_systemid(1);
	$sub_system = $_COOKIE['sub_system'];
	if ($method == 'delivered') {
		if (isset($_POST['month_filter'])) {
			$month_filter = $_POST['month_filter'];
		} else {
			$month_filter = date("Y-m", time());
		}
		$mfilter_qry = "AND bm.deliverd_timestamp LIKE '$month_filter%'";
	}
	$orderby0 = 'cu.`name`';
	if (isset($_COOKIE['odr_odrby'])) {
		if ($_COOKIE['odr_odrby'] == 'date')
			$orderby0 = 'bm.billed_timestamp';
	}

	if ($method == 'cust_odr') {
		$type = '4,5';
		$status = '1,2';
		$lock_qry = "bm.`lock`='2'";
		$menu_by = 'Picked By';
		$menu_date = 'Picked Date';
		$orderby = 'ORDER BY ' . $orderby0;
	}
	if ($method == 'pending') {
		$type = '1,2';
		$status = '1,2';
		$lock_qry = "bm.`lock`='1'";
		;
		$menu_by = 'Picked By';
		$menu_date = 'Picked Date';
		$orderby = 'ORDER BY ' . $orderby0;
	}
	if ($method == 'picked') {
		$type = '1,2,4,5';
		$status = '2';
		$lock_qry = "bm.`lock` IN (1,2)";
		$menu_by = 'Picked By';
		$menu_date = 'Picked Date';
		$orderby = "AND bm.seen_by='$user' ORDER BY bm.seen_timestamp DESC";
	}
	if ($method == 'packed') {
		$type = '1,2,4,5';
		$status = '3';
		$lock_qry = "bm.`lock`='1'";
		$menu_by = 'Packed By';
		$menu_date = 'Packed Date';
		$orderby = "AND bm.seen_by!='' ORDER BY " . $orderby0;
		if ($systemid == 13 && $sub_system == 1) {
			$menu_by = 'Started By';
			$menu_date = 'Started Date';
		}
	}
	if ($method == 'shipped') {
		$type = '1,2,4,5';
		$status = '4';
		$lock_qry = "bm.`lock`='1'";
		$menu_by = 'Shipped By';
		$menu_date = 'Shipped Date';
		$orderby = "AND bm.seen_by!='' ORDER BY bm.seen_timestamp DESC";
	}
	if ($method == 'delivered') {
		$type = '1,2,4,5';
		$status = '5';
		$lock_qry = "bm.`lock`='1'";
		$menu_by = 'Delivered By';
		$menu_date = 'Delivered Date';
		$orderby = "AND bm.seen_by!='' ORDER BY bm.seen_timestamp DESC";
		if ($systemid == 13 && $sub_system == 1) {
			$menu_by = 'Finished By';
			$menu_date = 'Finished Date';
		}
	}
	include('config.php');

	$query1 = "SELECT id,username FROM userprofile";
	$result1 = mysqli_query($conn2, $query1);
	while ($row1 = mysqli_fetch_array($result1)) {
		$salesman[$row1[0]] = $row1[1];
	}

	$query = "SELECT bm.invoice_no,cu.name,di.name,bm.billed_by,date(bm.order_timestamp),time(bm.order_timestamp),bm.seen_by
	,date(bm.seen_timestamp),time(bm.seen_timestamp),bm.packed_by,date(bm.packed_timestamp),time(bm.packed_timestamp),
	bm.shipped_by,date(bm.shipped_timestamp),time(bm.shipped_timestamp),bm.deliverd_by,date(bm.deliverd_timestamp),time(bm.deliverd_timestamp),bm.`status`,bm.`type`,st.name
	FROM bill_main bm, district di, cust cu, stores st WHERE bm.billed_district=di.id AND bm.cust=cu.id AND bm.`store`=st.id AND $lock_qry AND bm.mapped_inventory='$store' AND bm.`status` IN ($status) AND bm.`type` IN ($type) $mfilter_qry $orderby";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$bi_invoice_no[] = $row[0];
		$bi_cust[] = $row[1];
		$bi_district[] = $row[2];
		$bi_billed_by[] = $salesman[$row[3]];
		$bi_billed_date[] = $row[4];
		$bi_billed_time[] = $row[5];
		if ($row[6] != '')
			$bi_seen_by[] = $salesman[$row[6]];
		else
			$bi_seen_by[] = '';
		$bi_seen_date[] = $row[7];
		$bi_seen_time[] = $row[8];
		if ($row[9] != '')
			$bi_packed_by[] = $salesman[$row[9]];
		else
			$bi_packed_by[] = '';
		$bi_packed_date[] = $row[10];
		$bi_packed_time[] = $row[11];
		if ($row[12] != '')
			$bi_shipped_by[] = $salesman[$row[12]];
		else
			$bi_shipped_by[] = '';
		$bi_shipped_date[] = $row[13];
		$bi_shipped_time[] = $row[14];
		if ($row[15] != '')
			$bi_deliverd_by[] = $salesman[$row[15]];
		else
			$bi_deliverd_by[] = '';
		$bi_deliverd_date[] = $row[16];
		$bi_deliverd_time[] = $row[17];
		$bi_status[] = $row[18];
		$bi_type[] = $row[19];
		$bm_store[] = $row[20];
	}

	if (($method == 'picked')) {
		// get quantity wise result
		$query = "SELECT itm.`id`,itm.`description`, SUM(bi.`qty`) FROM inventory_items itm, bill_main bm, bill bi WHERE
		bm.`invoice_no` = bi.`invoice_no` AND itm.`id`=bi.`item` AND bm.`lock` IN (1,2) AND bm.`mapped_inventory`='$store' AND bm.`status` IN (2) AND bm.`type` IN (1,2,4,5) AND bm.`seen_by`='$user' GROUP BY itm.`id`";
		$result = mysqli_query($conn2, $query);
		while ($row = mysqli_fetch_array($result)) {
			$bi_item_id[] = $row[0];
			$bi_item_desc[] = $row[1];
			$bi_item_qty[] = $row[2];
		}

		$query = "SELECT on_place_replace FROM stores WHERE id='$store'";
		$result = mysqli_query($conn2, $query);
		$row = mysqli_fetch_row($result);
		if ($row[0] == 0) {
			// get returned items
			$query = "SELECT itm.`id`,itm.`description`,SUM(rt.`qty`) FROM return_main rm, `return` rt, inventory_items itm WHERE rm.`invoice_no`=rt.`invoice_no`  AND rt.`replace_item`=itm.`id` AND rm.`status`='2' AND rt.`odr_packed`='0' AND rm.`cust` IN (SELECT DISTINCT bm.`cust` FROM bill_main bm WHERE bm.`seen_by`='$user' AND bm.`status` IN ($status) AND bm.`type` IN ($type)) GROUP BY rt.`replace_item`";
			$result = mysqli_query($conn2, $query);
			while ($row = mysqli_fetch_array($result)) {
				$bi_rt_item_id[] = $row[0];
				$bi_rt_item_desc[] = $row[1];
				$bi_rt_item_qty[] = $row[2];
			}
		}
	}

}

// update by nirmal 20_10_2023, 21_12_2023, 26_01_2024 (items sort oder by drawer no before picked)
function getOneOrder()
{
	global $button, $button_action, $odr_bill_id, $odr_bi_desc, $odr_bi_qty, $odr_bi_price, $odr_total, $odr_ledc2, $odr_bi_drawer, $odr_bi_no_update, $pay_id, $cash_amount, $chque_amount, $bi_cust, $odr_date, $bi_salesman, $bi_seen_by, $bi_seen_date,
	$bi_seen_time, $bi_packed_by, $bi_packed_date, $bi_packed_time, $bi_shipped_by, $bi_shipped_date, $bi_shipped_time,
	$bi_deliverd_by, $bi_deliverd_date, $bi_deliverd_time, $cu_id, $bm_type, $bm_status, $tm_template, $bm_district, $cu_id, $odr_bi_order, $user_name;
	$invoice_no = $_REQUEST['id'];
	$user_name = $_COOKIE['user'];
	$cash_amount = $chque_amount = 0;
	$bm_status = '';
	if ($_GET['action'] == 'list_one_custodr' || $_GET['action'] == 'list_one')
		$order_by_qry = 'inq.drawer_no, bi.id';
	else
		$order_by_qry = 'inv.description';
	$systemid = inf_systemid(1);
	$sub_system = $_COOKIE['sub_system'];
	include('config.php');

	$query1 = "SELECT `id`,`username` FROM userprofile";
	$result1 = mysqli_query($conn2, $query1);
	while ($row1 = mysqli_fetch_array($result1)) {
		$salesman[$row1[0]] = $row1[1];
	}

	$query = "SELECT cu.name,bm.billed_by,bm.seen_by,date(bm.seen_timestamp),time(bm.seen_timestamp),bm.packed_by,date(bm.packed_timestamp),time(bm.packed_timestamp),bm.shipped_by,date(bm.shipped_timestamp),time(bm.shipped_timestamp),bm.deliverd_by,date(bm.deliverd_timestamp),time(bm.deliverd_timestamp),bm.`status`,cu.id,bm.`type`,bm.`order_timestamp`,bm.billed_district,cu.id,bm.mapped_inventory,bm.store FROM bill_main bm, cust cu WHERE bm.`cust`=cu.id AND  bm.invoice_no='$invoice_no'";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$bi_cust = $row[0];
		$bi_salesman = $salesman[$row[1]];
		if ($row[2] != '')
			$bi_seen_by = $salesman[$row[2]];
		$bi_seen_date = $row[3];
		$bi_seen_time = $row[4];
		if ($row[5] != '')
			$bi_packed_by = $salesman[$row[5]];
		$bi_packed_date = $row[6];
		$bi_packed_time = $row[7];
		if ($row[8] != '')
			$bi_shipped_by = $salesman[$row[8]];
		$bi_shipped_date = $row[9];
		$bi_shipped_time = $row[10];
		if ($row[11] != '')
			$bi_deliverd_by = $salesman[$row[11]];
		$bi_deliverd_date = $row[12];
		$bi_deliverd_time = $row[13];
		$bm_status = $row[14];
		$cu_id = $row[15];
		$bm_type = $row[16];
		$odr_date = $row[17];
		$bm_district = $row[18];
		$cu_id = $row[19];
		$mapped_inventory = $row[20];
		$bm_store = $row[21];
		if ($bm_status == 1) {
			$button = 'Pick';
			$button_action = 'set_picked';
		}
		if ($bm_status == 2) {
			$button = 'Packed';
			$button_action = 'set_packed';
		}
		if ($bm_status == 3) {
			$button = 'Shipped';
			$button_action = 'set_shipped';
			if ($systemid == 13 && $sub_system == 1) {
				$button = 'Finished';
				$button_action = 'set_delivered';
			}
		}
		if ($bm_status == 4) {
			$button = 'Delivered';
			$button_action = 'set_delivered';
			if ($systemid == 13 && $sub_system == 1) {
				$button = 'Finished';
				$button_action = 'set_delivered';
			}
		}
		if ($bm_status == 5) {
			$button = '';
			$button_action = '';
		}

		$query2 = "SELECT bi.id,inv.description,bi.qty,bi.unit_price,inq.drawer_no,bi.no_update,bi.return_odr_replace FROM bill bi, inventory_items inv, inventory_qty inq WHERE inq.item=inv.id AND bi.item=inv.id AND bi.invoice_no='$invoice_no' AND inq.location='$mapped_inventory' ORDER BY $order_by_qry";
		$result2 = mysqli_query($conn2, $query2);
		while ($row2 = mysqli_fetch_array($result2)) {
			$odr_bill_id[] = $row2[0];
			$odr_bi_desc[] = $row2[1];
			$odr_bi_qty[] = $row2[2];
			$odr_bi_price[] = $row2[3];
			$odr_bi_drawer[] = $row2[4];
			$odr_bi_no_update[] = $row2[5];
			$odr_bi_order[] = $row2[6];
			$odr_total += $row2[2] * $row2[3];
			$odr_ledc2[] = str_repeat('_', (12 - strlen(number_format($row2[2] * $row2[3]))));
		}
	}

	$query1 = "SELECT id,payment_type,amount FROM payment WHERE invoice_no='$invoice_no'";
	$result1 = mysqli_query($conn2, $query1);
	while ($row1 = mysqli_fetch_array($result1)) {
		$pay_id[] = $row1[0];
		if ($row1[1] == 1)
			$cash_amount = $row1[2];
		if ($row1[1] == 2)
			$chque_amount = $row1[2];
	}
	$result = mysqli_query($conn2, "SELECT st.billing_template FROM stores st WHERE st.id='$bm_store'");
	$row = mysqli_fetch_assoc($result);
	$tm_template = $row['billing_template'];
}

// update by nirmal 06_02_2024 changed itq.location=rm.store query to rm.mapped_inventory
function getUnpackedReturn($cu)
{
	global $rtn_inv, $rtn_id, $rtn_date, $rtn_by, $rtn_itm_code, $rtn_itm_desc, $rtn_qty, $dis_date, $rtn_st, $rtn_drawer;
	$rtn_id = array();
	include('config.php');
	$query = "SELECT DISTINCT rm.invoice_no,rt.id,date(rm.return_date),up.username,itm.`code`,itm.description,rt.qty,date(rt.process_date),rt.`status`,itq.drawer_no FROM return_main rm, `return` rt, inventory_items itm, inventory_qty itq, userprofile up WHERE rm.invoice_no=rt.invoice_no AND rt.replace_item=itm.id AND itm.id=itq.item AND itq.location=rm.mapped_inventory AND up.id=rm.return_by AND rt.odr_packed='0' AND rm.`cust`='$cu' AND rm.`status`='2' ORDER BY itq.drawer_no";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$rtn_inv[] = $row[0];
		$rtn_id[] = $row[1];
		$rtn_date[] = $row[2];
		$rtn_by[] = $row[3];
		$rtn_itm_code[] = $row[4];
		$rtn_itm_desc[] = $row[5];
		$rtn_qty[] = $row[6];
		$dis_date[] = $row[7];
		if ($row[8] == 0)
			$rtn_st[] = 'Pending';
		else
			$rtn_st[] = 'Processed';
		$rtn_drawer[] = $row[9];
	}
}

function getCancelRerunCRBalance()
{
	global $return_cr_bal;
	$odr_no = $_GET['id'];
	include('config.php');
	$query = "SELECT SUM(py.amount) FROM return_remove_job rj, payment py WHERE rj.payment_inv=py.id AND rj.odr_no='$odr_no'";
	$row = mysqli_fetch_row(mysqli_query($conn2, $query));
	$return_cr_bal = -1 * $row[0];
	$query = "SELECT SUM(unit_price*qty) FROM bill WHERE return_odr_replace='1' AND invoice_no='$odr_no'";
	$row = mysqli_fetch_row(mysqli_query($conn2, $query));
	$return_cr_bal = $return_cr_bal - $row[0];
}

function getOneReturnItem()
{
	global $rtn_id, $itm_desc, $rtn_qty;
	$rtn_id = $_GET['rtn_id'];
	include('config.php');
	$query = "SELECT itm.description,rt.qty FROM `return` rt, return_main rm, inventory_items itm WHERE rm.invoice_no=rt.invoice_no AND rt.replace_item=itm.id AND rm.`status`='2' AND rt.odr_packed='0' AND rt.id='$rtn_id'";
	$row = mysqli_fetch_row(mysqli_query($conn2, $query));
	$itm_desc = $row[0];
	$rtn_qty = $row[1];
}

function removeOneRetunItem()
{
	global $message;
	$out = false;
	$rtn_id = $_GET['rtn_id'];
	$odr_id = $_GET['odr_id'];
	$user_id = $_COOKIE['user_id'];
	$store = $_COOKIE['store'];
	$sub_system = $_COOKIE['sub_system'];
	$time_now = timeNow();
	$credit_value = 0;

	include('config.php');
	$query = "SELECT count(rt.id),rm.invoice_no FROM `return` rt, return_main rm WHERE rm.invoice_no=rt.invoice_no AND rm.`status`='2' AND rt.odr_packed='0' AND rt.`status`='0' AND rt.id='$rtn_id'";
	$row = mysqli_fetch_row(mysqli_query($conn, $query));
	$rt_count = $row[0];
	$rm_inv = $row[1];
	if ($rt_count > 0) {
		$query = "SELECT bi.unit_price,rt.qty,rm.cust,rt.replace_item FROM `return` rt, return_main rm, bill bi, bill_main bm WHERE rt.invoice_no=rm.invoice_no AND bi.invoice_no=bm.invoice_no AND rm.cust=bm.cust AND rt.return_item=bi.item AND rm.`status`=2 AND bm.`status`!=0 AND rt.id='$rtn_id' ORDER BY bi.id  LIMIT 1";
		$row = mysqli_fetch_row(mysqli_query($conn, $query));
		$credit_value = $row[0] * $row[1];
		$rt_qty = $row[1];
		$cust = $row[2];
		$replace_item = $row[3];
		if ($credit_value > 0) {
			$credit_value = -1 * $credit_value;
			$query = "UPDATE `return` SET replace_item='1', extra_pay='$credit_value',odr_no='$odr_id',odr_packed='1',`odr_packed_date`='$time_now',	`odr_packed_by`='$user_id'  WHERE id='$rtn_id'";
			$result = mysqli_query($conn, $query);
			if ($result) {
				$pay_comment = 'Return Item - Credit Return - RTN_INV:' . str_pad($rm_inv, 7, "0", STR_PAD_LEFT);
				$query = "INSERT INTO `payment` (`invoice_no`,`bill_pay`,`cust`,`payment_type`,`amount`,`salesman`,`sys_user`,`payment_date`,`comment`,`store`,`gps_x`,`gps_y`,`sub_system`) VALUES ('0','2','$cust','1','$credit_value','$user_id','$user_id','$time_now','$pay_comment','$store','0','0','$sub_system')";
				$result = mysqli_query($conn, $query);
				$lastid = mysqli_insert_id($conn);
				if ($result) {
					$query = "INSERT INTO `return_remove_job` (`odr_no`,`replace_item_rm`,`qty`,`payment_inv`) VALUES ('$odr_id','$replace_item','$rt_qty','$lastid')";
					$result = mysqli_query($conn, $query);
					if ($result)
						$out = true;
				}
			}
		}
	}
	if ($out) {
		$message = "Item was Removed from Return Invoice";
		return true;
	} else {
		$message = "Error: Item Could not be Removed";
		return false;
	}
}

function getPackedReturn()
{
	global $rtn2_inv, $rtn2_id, $rtn2_date, $rtn2_by, $rtn2_itm_code, $rtn2_itm_desc, $rtn2_qty, $dis2_date, $rtn2_st;
	$odr_id = $_GET['id'];
	$rtn2_id = array();

	include('config.php');
	$query = "SELECT rm.invoice_no,rt.id,date(rm.return_date),up.username,itm.code,itm.description,rt.qty,date(rt.process_date),rt.`status` FROM return_main rm, `return` rt, inventory_items itm, userprofile up WHERE rm.invoice_no=rt.invoice_no AND rt.replace_item=itm.id AND up.id=rm.return_by AND rt.odr_packed='1' AND rt.odr_no='$odr_id' AND rm.`status`='2' ORDER BY rm.invoice_no DESC";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$rtn2_inv[] = $row[0];
		$rtn2_id[] = $row[1];
		$rtn2_date[] = $row[2];
		$rtn2_by[] = $row[3];
		$rtn2_itm_code[] = $row[4];
		$rtn2_itm_desc[] = $row[5];
		$rtn2_qty[] = $row[6];
		$dis2_date[] = $row[7];
		if ($row[8] == 0)
			$rtn2_st[] = 'Pending';
		else
			$rtn2_st[] = 'Processed';
	}
}

// update by nirmal 06_02_2024 changed rm.store query to rm.mapped_inventory
// updated by nirmal 27_01_2025 (changed return pack logic with -invoice)
function returnPacked()
{
	include('config.php');
	$id = $_GET['id'];
	$odr_no = $_GET['odr_no'];
	$user_id = $_COOKIE['user_id'];
	$time_now = timeNow();
	$itq_id = $itq_qty = $itn_id = $itn_qty = '';
	$on_place = true;

	$query = "SELECT `cust` FROM bill_main WHERE invoice_no='$odr_no'";
	$row = mysqli_fetch_row(mysqli_query($conn2, $query));
	$bm_cust = $row[0];

	$query = "SELECT rm.`cust`, rt.odr_packed, rt.replace_item, rt.qty, rm.mapped_inventory
			  FROM return_main rm, `return` rt
			  WHERE rm.invoice_no = rt.invoice_no AND rt.id='$id'";
	$row = mysqli_fetch_row(mysqli_query($conn2, $query));
	$rm_cust = $row[0];
	$rt_packed = $row[1];
	$replace_item = $row[2];
	$rt_qty = $row[3];
	$store = $row[4];

	$result = mysqli_query($conn2, "SELECT on_place_replace FROM stores WHERE id='$store'");
	$row = mysqli_fetch_assoc($result);
	$on_place_replace = $row['on_place_replace'];

	// Check if customers match and return item isn't packed
	if ($bm_cust != $rm_cust) {
		print 'error: bill cust and return cust not same';
		exit;
	}

	if ($rt_packed != 0) {
		print 'error: this item already packed';
		exit;
	}

	$query = "SELECT COALESCE(SUM(rt.`qty`),0) FROM `return` rt WHERE rt.odr_no='$odr_no' AND rt.`odr_packed` = 1 AND rt.`replace_item` = '$replace_item'";
	$row = mysqli_fetch_row(mysqli_query($conn2, $query));
	$total_packed = $row[0];

	// Check if replace_item exists in the bill and sum its quantity
	$query = "SELECT COALESCE(SUM(qty), 0) AS total_billed, COUNT(*) AS cnt FROM bill WHERE invoice_no = '$odr_no' AND item = '$replace_item' AND qty < 0";
	$result = mysqli_query($conn2, $query);
	$row = mysqli_fetch_assoc($result);
	$total_billed = $row['total_billed'];
	$item_exists = $row['cnt'] > 0;

	if ($on_place_replace == 0) {
		if ($item_exists) {
			$query5 = "SELECT id,qty FROM inventory_qty WHERE item='$replace_item' AND `location`='$store'";
			$row5 = mysqli_fetch_row(mysqli_query($conn2, $query5));
			$itq_id = $row5[0];
			$itq_qty = $row5[1];

			if ($total_billed > 0) {
				$temp_qty = $itq_qty;
			} else {
				$temp_qty = $itq_qty - ($total_packed + $total_billed);
				// $temp_qty = $itq_qty - $total_billed;
			}

			if ($temp_qty >= $rt_qty) {
				$query6 = "UPDATE `return` SET `odr_no`='$odr_no', `odr_packed`='1', `odr_packed_date`='$time_now', `odr_packed_by`='$user_id' WHERE id='$id'";
				$result6 = mysqli_query($conn, $query6);
				if ($result6) {
					print 'done';
				} else {
					print 'error';
				}
			} else {
				print 'error: no enough qty or cashback invoices';
			}
		} else {
			$query = "SELECT id,qty FROM inventory_qty WHERE item='$replace_item' AND `location`='$store'";
			$row = mysqli_fetch_row(mysqli_query($conn2, $query));
			$itq_id = $row[0];
			$itq_qty = $row[1];

			if (($itq_qty >= $rt_qty) && ($rt_packed == 0)) {
				$query4 = "UPDATE `inventory_qty` SET `qty`=qty-$rt_qty WHERE `id`='$itq_id'";
				$result4 = mysqli_query($conn, $query4);
				if (!$result4) {
					$on_place = false;
					print 'error: query error';
				}

			} else {
				print 'error: no enough qty';
				$on_place = false;
			}

			if (($bm_cust == $rm_cust) && ($rt_packed == 0) && ($on_place)) {
				$query7 = "UPDATE `return` SET `odr_no`='$odr_no', `odr_packed`='1', `odr_packed_date`='$time_now', `odr_packed_by`='$user_id' WHERE id='$id'";
				$result7 = mysqli_query($conn, $query7);
				if ($result7)
					print 'done';
				else
					print 'error';
			}
		}
	} else {
		print 'error: on place replace not activated';
	}

}

// update by nirmal 07_02_2024 (get mapped store instead of store)
// updated by nirmal 27_01_2025 (if inventory 0 qty addition skip)
function removeReturnPacked()
{
	include('config.php');
	$id = $_GET['id'];
	$odr_no = $_GET['odr_no'];
	$user_id = $_COOKIE['user_id'];
	$time_now = timeNow();
	$itq_id = $itq_qty = $itn_id = $itn_qty = '';
	$on_place = true;

	$query = "SELECT `cust`,`lock` FROM bill_main WHERE invoice_no='$odr_no'";
	$row = mysqli_fetch_row(mysqli_query($conn, $query));
	$bm_cust = $row[0];
	$bm_lock = $row[1];

	$query = "SELECT rm.`cust`,rt.odr_packed,rt.replace_item,rt.qty,rm.store,rm.mapped_inventory FROM return_main rm, `return` rt WHERE rm.invoice_no=rt.invoice_no AND rt.id='$id'";
	$row = mysqli_fetch_row(mysqli_query($conn, $query));
	$rm_cust = $row[0];
	$rt_packed = $row[1];
	$replace_item = $row[2];
	$rt_qty = $row[3];
	$store = $row[4];
	$mapped_inventory = $row[5];

	// Check if customers match and return item is packed
	if ($bm_cust != $rm_cust) {
		print 'error: bill cust and return cust not same';
		exit;
	}

	if ($rt_packed != 1) {
		print 'error: this item not packed';
		exit;
	}

	$result = mysqli_query($conn, "SELECT on_place_replace FROM stores WHERE id='$mapped_inventory'");
	$row = mysqli_fetch_assoc($result);
	$on_place_replace = $row['on_place_replace'];

	// Check if replace_item exists in the bill and sum its quantity
	$query = "SELECT COALESCE(SUM(qty), 0) AS total_billed, COUNT(*) AS cnt
			  FROM bill
			  WHERE invoice_no = '$odr_no'
			  AND item = '$replace_item' AND qty < 0";
	$result = mysqli_query($conn, $query);
	$row = mysqli_fetch_assoc($result);
	$total_billed = $row['total_billed'];
	$item_exists = $row['cnt'] > 0;

	if ($on_place_replace == 0) {
		if ($bm_lock == 2) {
			if ($item_exists) {
				$query5 = "SELECT id,qty FROM inventory_qty WHERE item='$replace_item' AND `location`='$store'";
				$row5 = mysqli_fetch_row(mysqli_query($conn2, $query5));
				$itq_id = $row5[0];
				$itq_qty = $row5[1];

				if ($total_billed > 0) {
					$temp_qty = $itq_qty;
				} else {
					$temp_qty = $itq_qty - $total_billed;
				}

				if ($temp_qty >= $rt_qty) {
					$query5 = "UPDATE `return` SET `odr_no`=null, `odr_packed`='0', `odr_packed_date`=null, `odr_packed_by`=null WHERE id='$id'";
					$result5 = mysqli_query($conn, $query5);
					if ($result5) {
						print 'done';
					} else {
						print 'error';
					}
				}
			} else {
				$query = "SELECT id,qty FROM inventory_qty WHERE item='$replace_item' AND `location`='$mapped_inventory'";
				$row = mysqli_fetch_row(mysqli_query($conn, $query));
				$itq_id = $row[0];
				$itq_qty = $row[1];

				if (($rt_qty > 0)) {
					$query4 = "UPDATE `inventory_qty` SET `qty`=`qty`+$rt_qty WHERE `id`='$itq_id'";
					$result4 = mysqli_query($conn, $query4);
					if (!$result4) {
						$on_place = false;
					}
				} else {
					$on_place = false;
				}

				if ($on_place) {
					$query5 = "UPDATE `return` SET `odr_no`=null, `odr_packed`='0', `odr_packed_date`=null, `odr_packed_by`=null WHERE id='$id'";
					$result5 = mysqli_query($conn, $query5);
					if ($result5)
						print 'done';
					else
						print 'error';
				} else {
					print 'error';
				}
			}
		} else {
			print 'error: bill not partially locked';
		}
	} else {
		print 'error: on place replace not activated';
	}
}

function getCustOdrItem()
{
	global $bi_desc, $bi_qty, $bi_price, $bi_discount, $item_filter, $bm_cust, $bm_salesman;
	$bm_id = $_GET['id'];
	include('config.php');

	$result = mysqli_query($conn2, "SELECT bm.`cust`,bm.billed_by FROM bill_main bm WHERE bm.invoice_no='$bm_id'");
	$row = mysqli_fetch_assoc($result);
	$bm_cust = $row['cust'];
	$bm_salesman = $row['billed_by'];

	$result = mysqli_query($conn2, "SELECT itm.pr_sr FROM bill bi, inventory_items itm WHERE bi.item=itm.id AND bi.invoice_no='$bm_id'");
	$row = mysqli_fetch_assoc($result);
	$item_filter = $row['pr_sr'];
}

// updated by nirmal 20_10_2023, 21_12_2023, 12_03_2024 (add quickbooks integration)
// updated by nirmal 23_12_2024 (added if bill is cust order and qty is - then qty deduct at packed time)
function setStatus($method)
{
	global $message, $type, $invoice_no;

	$invoice_no = $_REQUEST['id'];
	$salesman = $_COOKIE['user_id'];
	$result = $delivered_updated = false;
	$time_now = timeNow();
	$systemid = inf_systemid(1);
	$unic_cal = unicCal();
	$sub_system = $_COOKIE['sub_system'];
	$out = $qty_update_prevent = true;
	$new_item_ref = $bill_minus_qty = 0;
	$qb_msg = $qb_cust_id = $qb_status = $qb_temp_cost = '';
	$message = 'Item status updated successfully! ';
	include('config.php');

	try {
		// Start the transaction
		mysqli_begin_transaction($conn);

		$billMainQuery = "SELECT `status`,`type`,`mapped_inventory` FROM bill_main WHERE invoice_no='$invoice_no'";
		$billMainQueryResult = mysqli_query($conn, $billMainQuery);
		$billMainQueryResultRow = mysqli_fetch_assoc($billMainQueryResult);
		$status = $billMainQueryResultRow['status'];
		$type = $billMainQueryResultRow['type'];
		$bm_mapped_inventory = $billMainQueryResultRow['mapped_inventory'];

		$returnItemsQuery = "SELECT rt.`replace_item`, rt.`qty` FROM `return` rt JOIN inventory_items itm ON itm.id = rt.`replace_item`
		WHERE rt.`odr_no` = '$invoice_no' AND rt.`odr_packed` = 1";
		$returnItemsQueryResult = mysqli_query($conn, $returnItemsQuery);

		if ($returnItemsQueryResult) {
			if (mysqli_num_rows($returnItemsQueryResult) > 0) {
				while ($returnItemsQueryResultRow = mysqli_fetch_array($returnItemsQueryResult)) {
					$replaceItem = $returnItemsQueryResultRow[0];
					$qty = $returnItemsQueryResultRow[1];

					$billMinusQuery = "SELECT COALESCE(SUM(bi.`qty`), 0) AS total FROM bill bi WHERE bi.invoice_no = '$invoice_no' AND bi.`item` = '$replaceItem' AND bi.`qty` < 0";
					$resultSet = mysqli_query($conn, $billMinusQuery);
					if ($resultSet) {
						$row = mysqli_fetch_assoc($resultSet);
						$bill_minus_qty = $row['total'];
					} else {
						$bill_minus_qty = 0;
					}

					$inventoryQtyQuery = "SELECT qty FROM inventory_qty WHERE item='$replaceItem' AND `location`='$bm_mapped_inventory'";
					$inventoryQtyQueryResult = mysqli_query($conn, $inventoryQtyQuery);

					if ($inventoryQtyQueryResult && mysqli_num_rows($inventoryQtyQueryResult) > 0) {
						$inventoryQtyQueryResultRow = mysqli_fetch_row($inventoryQtyQueryResult);
						$itq_qty = $inventoryQtyQueryResultRow[0];
						if ($bill_minus_qty != 0) {
							if ($qty > ($itq_qty - $bill_minus_qty)) {
								$qty_update_prevent = false;
								$returnUpdateQuery = "UPDATE `return` SET `odr_no` = NULL, `odr_packed` = '0', `odr_packed_date` = NULL, `odr_packed_by` = NULL WHERE `odr_no` = '$invoice_no'";
								$returnUpdateQueryResult = mysqli_query($conn, $returnUpdateQuery);
								if (!$returnUpdateQueryResult) {
									// Handle query error
									$out = false;
									$message = 'Error: Database query error';
									throw new Exception($message);
								}
							}
						}
					} else {
						$out = false;
						$message = 'Error: Database query error';
						throw new Exception($message);
					}
				}
			}
		} else {
			// Handle query error
			$out = false;
			$message = 'Error: Database query error';
			throw new Exception($message);
		}

		// Only show this error if at least one item failed the quantity check.
		if (!$qty_update_prevent) {
			$message = 'Error: Not enough quantity in the inventory to pack the return items.';
			$out = false;
		}

		// QuickBooks user check
		if ($out && (isQuickBooksActive(1))) {
			// Sanitize the input to prevent SQL injection
			$invoice_no = mysqli_real_escape_string($conn2, $invoice_no);

			// Construct and execute the query
			$userQry = "SELECT c.`qb_cust_id`, bm.`qb_status`, bm.`qb_temp_cost` FROM bill_main bm INNER JOIN cust c ON c.`id` = bm.`cust` WHERE bm.`invoice_no` = '$invoice_no'";
			$userResult = mysqli_query($conn, $userQry);

			if ($userResult && mysqli_num_rows($userResult) > 0) {
				$userRow = mysqli_fetch_assoc($userResult);
				$qb_cust_id = $userRow['qb_cust_id'];
				$qb_status = $userRow['qb_status'];
				$qb_temp_cost = $userRow['qb_temp_cost'];

				if (empty($qb_cust_id)) {
					$out = false;
					$qb_msg = 'QuickBooks error: This user is not registered in QuickBooks.';
					throw new Exception($qb_msg);
				}
			} else {
				$out = false;
				$qb_msg = 'QuickBooks error: This user is not registered in QuickBooks.';
				throw new Exception($qb_msg);
			}
		}

		if ($out) {
			if ($type == 4 || $type == 5) {
				$lock_qry = '`lock`=1,';
			} else {
				$lock_qry = '';
			}
			if (($method == 'picked') && ($status == 1)) {
				$query = "UPDATE `bill_main` SET `seen_by`='$salesman',`seen_timestamp`='$time_now',status='2' WHERE `invoice_no`='$invoice_no'";
				$result = mysqli_query($conn, $query);
			}
			if (($method == 'packed') && ($status == 2)) {
				$query = "UPDATE `bill_main` SET $lock_qry `packed_by`='$salesman',`billed_timestamp`='$time_now',`packed_timestamp`='$time_now',status='3' WHERE `invoice_no`='$invoice_no'";
				$result = mysqli_query($conn, $query);
			}
			if (($method == 'shipped') && ($status == 3)) {
				$query = "UPDATE `bill_main` SET `shipped_by`='$salesman',`shipped_timestamp`='$time_now',status='4' WHERE `invoice_no`='$invoice_no'";
				$result = mysqli_query($conn, $query);
			}
			if ($systemid == 13 && $sub_system == 1) {
				if (($method == 'delivered') && ($status == 3)) {
					$query = "UPDATE `bill_main` SET `deliverd_by`='$salesman',`deliverd_timestamp`='$time_now',status='5' WHERE `invoice_no`='$invoice_no'";
					$result = mysqli_query($conn, $query);
				}
			}
			if (($method == 'delivered') && ($status == 4)) {
				$query = "UPDATE `bill_main` SET `deliverd_by`='$salesman',`deliverd_timestamp`='$time_now',status='5' WHERE `invoice_no`='$invoice_no'";
				$result = mysqli_query($conn, $query);
			}

			if (!$result) {
				$message = 'Error: Item status could not be changed!';
				$out = false;
				throw new Exception($message);
			}

			if ($method == 'packed' && $result) {
				$query2 = "SELECT SUM(qty*unit_price) FROM bill WHERE qty>0 AND invoice_no='$invoice_no'";
				$row2 = mysqli_fetch_row(mysqli_query($conn, $query2));
				$payment1 = $row2[0];

				$query2 = "SELECT SUM(qty*unit_price) FROM bill WHERE qty<0 AND invoice_no='$invoice_no'";
				$row2 = mysqli_fetch_row(mysqli_query($conn, $query2));
				$payment2 = $row2[0];

				// ------------ added by nirmal 26_07_2023
				$result = mysqli_query($conn, "SELECT `value` FROM settings WHERE setting='tax'");
				$row = mysqli_fetch_assoc($result);
				$tax_rate = $row['value'];

				$total = $payment1 + $payment2;
				$tax_added_value = (((($total) / ((100 + $tax_rate) / 100)) * ($tax_rate / 100)));

				$query2 = "UPDATE bill_main SET `invoice_+total`='$payment1', `invoice_-total`='$payment2', `tax` = '$tax_added_value'  WHERE invoice_no='$invoice_no'";
				$result2 = mysqli_query($conn, $query2);
				$delivered_updated = true;

				if (!$result2) {
					$message = "Error: Bill main could not be updated. " . mysqli_error($conn);
					$out = false;
					throw new Exception($message);
				}

				if ($type == 4) {
					// Invoice bill item (-qty) update to inventory
					$queryBillItems = "SELECT bi.item, bi.qty, itm.pr_sr, itm.unic
                   	FROM bill bi
                   	JOIN inventory_items itm ON bi.item = itm.id
                   	WHERE bi.invoice_no = '$invoice_no'";
					$resultBillItems = mysqli_query($conn, $queryBillItems);
					$uniqueCalculation = unicCal();
					$queryUpdateInventory = $queryUpdateInventory1 = '';

					while ($billItem = mysqli_fetch_array($resultBillItems)) {
						$inventoryId = '';
						$itemId = $billItem[0];
						$itemQuantity = $billItem[1];
						$itemPriceStatus = $billItem[2];
						$itemUniqueStatus = $billItem[3];

						if ($itemPriceStatus == 1) {
							if ((($itemUniqueStatus == 1) && (!$uniqueCalculation)) || ($itemUniqueStatus == 0)) {
								$queryInventoryId = "SELECT id FROM inventory_qty WHERE item = '$itemId' AND `location` = '$bm_mapped_inventory'";
								$inventoryRow = mysqli_fetch_row(mysqli_query($conn, $queryInventoryId));
								$inventoryId = $inventoryRow[0];

								if ($itemQuantity < 0) {
									$queryUpdateInventory = "UPDATE `inventory_qty` SET `qty` = `qty` - $itemQuantity WHERE `id` = '$inventoryId'";
									$resultUpdateInventory = mysqli_query($conn, $queryUpdateInventory);
									if (!$resultUpdateInventory) {
										$errorMessage = "Error: Inventory could not be updated. " . mysqli_error($conn);
										$out = false;
										throw new Exception($errorMessage);
									}
								}
							}
						}
					}

					// Return items with bill (-qty) update to inventory
					$queryReturnItems = "SELECT rt.`replace_item`, SUM(rt.`qty`), itm.pr_sr, itm.unic
					FROM `return` rt
                    JOIN inventory_items itm ON itm.id = rt.`replace_item`
                    WHERE rt.`odr_no` = '$invoice_no' AND rt.`odr_packed` = 1
                    GROUP BY rt.`replace_item`";
					$resultReturnItems = mysqli_query($conn, $queryReturnItems);
					$uniqueCalculation = unicCal();

					while ($returnItem = mysqli_fetch_array($resultReturnItems)) {
						$inventoryId = '';
						$replaceItemId = $returnItem[0];
						$replaceItemQty = $returnItem[1];
						$itm_pr_sr = $returnItem[2];
						$itemUniqueStatus = $returnItem[3];

						if ($itm_pr_sr == 1) {
							if ((($itemUniqueStatus == 1) && (!$uniqueCalculation)) || ($itemUniqueStatus == 0)) {
								$queryInventoryId = "SELECT id FROM inventory_qty WHERE item='$replaceItemId' AND `location`='$bm_mapped_inventory'";
								$inventoryRow = mysqli_fetch_row(mysqli_query($conn, $queryInventoryId));
								$inventoryId = $inventoryRow[0];

								$queryBillItems = "SELECT bi.item, bi.qty AS total_qty, itm.pr_sr, itm.unic
												FROM bill bi
												JOIN inventory_items itm ON bi.item = itm.id
												WHERE bi.invoice_no = '$invoice_no' AND bi.item = '$replaceItemId'";
								$resultBillItems = mysqli_query($conn, $queryBillItems);
								while ($billItem = mysqli_fetch_array($resultBillItems)) {
									$billItemId = $billItem[0];
									$billItemQty = $billItem[1];
									if (($replaceItemQty > 0) && ($billItemQty < 0)) {
										$queryUpdateInventory1 = "UPDATE `inventory_qty` SET `qty` = `qty` - $replaceItemQty WHERE `id` = '$inventoryId'";
										$resultUpdateInventory = mysqli_query($conn, $queryUpdateInventory1);
										if (!$resultUpdateInventory) {
											$errorMessage = "Error: Inventory could not be updated. " . mysqli_error($conn);
											$out = false;
											throw new Exception($errorMessage);
										}
									}
								}
							}
						}
					}
				}
			}

			// $qb_status has to be 0: pending, 1:sent, 2:could not be sent. default it is null,
			// so if qb started then that times on this status will record as 0,1 or 2, then we can only push those invoice to qb,
			// we can escape invoice before those not push to qb enable
			if (isQuickBooksActive(1)) {
				if ($qb_status !== null && $qb_status != '') {
					if ($qb_temp_cost != '') { // temp cost must need to be present in db
						$invoiceCost = $invoiceTotal = 0;
						$custName = '';
						$journal_entry_result = $journal_entry_result1 = array();

						if ($delivered_updated) {
							$invoiceCostQuery = "SELECT SUM(b.qty * b.cost), c.name, (bm.`invoice_+total` + bm.`invoice_-total`) FROM bill_main bm, bill b, cust c WHERE c.id = bm.cust AND bm.invoice_no = b.invoice_no AND bm.`invoice_no`='$invoice_no'";
							$invoiceCostRow = mysqli_fetch_row(mysqli_query($conn, $invoiceCostQuery));
							if (!empty($invoiceCostRow)) {
								$invoiceCost = $invoiceCostRow[0];
								$custName = $invoiceCostRow[1];
								$bill_total = $invoiceCostRow[2];

								// minus (cashback items)
								if (($invoiceCost < 0)) {
									// invoice total journal entry
									$debitAccountName = "Sales";
									$creditAccountName = "Accounts Receivable (A/R)";
									$description = "[RETURN INVOICE] - Invoice No: $invoice_no, Customer : $custName";
									$debitEntityType = "";
									$debitEntityID = "";
									$creditEntityType = "Customer";
									$creditEntityID = $qb_cust_id;

									$journalEntryForInvoiceTotal = buildJournalEntry($conn, abs($bill_total), $debitAccountName, $creditAccountName, $description, $debitEntityType, $debitEntityID, $creditEntityType, $creditEntityID);
									if (isset($journalEntryForInvoiceTotal['error'])) {
										$qb_msg = mysqli_real_escape_string($conn, $journalEntryForInvoiceTotal['error']);
										throw new Exception("QuickBooks error: " . $qb_msg);
									} else {
										$batch_id = generateBatchID();
										$action_name = 'return_invoice_insert_through_odr_insert';
										foreach ($journalEntryForInvoiceTotal as $entry) {
											$posting_type = mysqli_real_escape_string($conn, $entry['posting_type']);
											$account_id = mysqli_real_escape_string($conn, $entry['account_id']);
											$account_name = mysqli_real_escape_string($conn, $entry['account_name']);
											$amount = mysqli_real_escape_string($conn, $entry['amount']);
											$description = mysqli_real_escape_string($conn, $entry['description']);
											$entity_type = isset($entry['entity_type']) ? mysqli_real_escape_string($conn, $entry['entity_type']) : null;
											$entity_id = isset($entry['entity_id']) ? mysqli_real_escape_string($conn, $entry['entity_id']) : null;

											$query = "INSERT INTO qb_queue (`batch_id`, `action`, `invoice_no`, `posting_type`, `account_id`, `account_name`, `invoice_total`, `description`, `entity_type`, `entity_id`)
												VALUES ('$batch_id','$action_name', '$invoice_no', '$posting_type', '$account_id', '$account_name', '$amount', '$description',
													" . ($entity_type !== null ? "'$entity_type'" : "NULL") . ",
													" . ($entity_id !== null ? "'$entity_id'" : "NULL") . ")";
											$result = mysqli_query($conn, $query);
											if (!$result) {
												$message = "MySQL Error while inserting into qb_queue: " . mysqli_error($conn);
												throw new Exception($message);
											}
										}
									}

									// invoice cost journal entry
									$debitAccountName = "Inventory Asset";
									$creditAccountName = "Cost of Goods Sold";
									$description = "[RETURN INVOICE] - Invoice No: $invoice_no, Customer : $custName";
									$debitEntityType = "";
									$debitEntityID = "";
									$creditEntityType = "";
									$creditEntityID = "";

									$journalEntryForInvoiceCost = buildJournalEntry($conn, abs($invoiceCost), $debitAccountName, $creditAccountName, $description, $debitEntityType, $debitEntityID, $creditEntityType, $creditEntityID);
									if (isset($journalEntryForInvoiceCost['error'])) {
										$qb_msg = mysqli_real_escape_string($conn, $journalEntryForInvoiceCost['error']);
										throw new Exception("QuickBooks error: " . $qb_msg);
									} else {
										$batch_id = generateBatchID();
										$action_name = 'return_invoice_insert_through_odr_insert';

										foreach ($journalEntryForInvoiceCost as $entry) {
											$posting_type = mysqli_real_escape_string($conn, $entry['posting_type']);
											$account_id = mysqli_real_escape_string($conn, $entry['account_id']);
											$account_name = mysqli_real_escape_string($conn, $entry['account_name']);
											$amount = mysqli_real_escape_string($conn, $entry['amount']);
											$description = mysqli_real_escape_string($conn, $entry['description']);
											$entity_type = isset($entry['entity_type']) ? mysqli_real_escape_string($conn, $entry['entity_type']) : null;
											$entity_id = isset($entry['entity_id']) ? mysqli_real_escape_string($conn, $entry['entity_id']) : null;

											$query = "INSERT INTO qb_queue (`batch_id`, `action`, `invoice_no`, `posting_type`, `account_id`, `account_name`, `invoice_cost`, `description`, `entity_type`, `entity_id`)
												VALUES ('$batch_id','$action_name', '$invoice_no', '$posting_type', '$account_id', '$account_name', '$amount', '$description',
													" . ($entity_type !== null ? "'$entity_type'" : "NULL") . ",
													" . ($entity_id !== null ? "'$entity_id'" : "NULL") . ")";
											$result = mysqli_query($conn, $query);
											if (!$result) {
												$message = "MySQL Error while inserting into qb_queue: " . mysqli_error($conn);
												throw new Exception($message);
											}
										}
									}
									// invoice total journal entry inserting to qb
									// try {
									// 	$journal_entry_result = QBAddJournalEntry($journalEntryForInvoiceTotal);
									// 	$qb_msg = $journal_entry_result['message'];
									// 	if ((isset($journal_entry_result['status'])) && ($journal_entry_result['status'] == 'success')) {
									// 		$qb_journal_entry_id = $journal_entry_result['qb_journal_entry_id'];
									// 		$query = "UPDATE `bill_main` SET `qb_value_id`='$qb_journal_entry_id' WHERE `invoice_no`='$invoice_no'";
									// 		$result1 = mysqli_query($conn, $query);
									// 		if (!$result1) {
									// 			$flag = false;
									// 			$qb_msg = 'Quickbooks bill main id update error.';

									// 			$qb_msg = mysqli_real_escape_string($conn, $qb_msg);
									// 			$action = "UPDATE BILL MAIN INVOICE COST ID (Invoice Total) : Invoice No: $invoice_no";
									// 			$functionName = "setStatus";

									// 			$error_log_query = "INSERT INTO `qb_debug_log` (`action`,`function`,`qb_message`,`action_time`)
									// 			VALUES ('$action','$functionName','$qb_msg','$time_now')";
									// 			$result = mysqli_query($conn, $error_log_query);
									// 		}
									// 	} else {
									// 		$qb_msg = mysqli_real_escape_string($conn, $qb_msg);
									// 		$action = "CUST ODR RETURN INVOICE, JOURNAL ENTRY INSERT (Invoice Total) : Invoice No: $invoice_no";
									// 		$functionName = "setStatus";
									// 		$error_log_query = "INSERT INTO `qb_debug_log` (`action`,`function`,`qb_message`,`action_time`)
									// 		VALUES ('$action','$functionName','$qb_msg','$time_now')";
									// 		$result = mysqli_query($conn, $error_log_query);
									// 	}
									// } catch (Exception $e) {
									// 	$flag = false;
									// 	$qb_msg = "<br>QuickBooks error: " . $e->getMessage();

									// 	$qb_msg = mysqli_real_escape_string($conn, $qb_msg);
									// 	$action = "CUST ODR RETURN INVOICE, JOURNAL ENTRY INSERT (Invoice Total) : Invoice No: $invoice_no";
									// 	$functionName = "setStatus";

									// 	$error_log_query = "INSERT INTO `qb_debug_log` (`action`,`function`,`qb_message`,`action_time`)
									// 	VALUES ('$action','$functionName','$qb_msg','$time_now')";
									// 	$result = mysqli_query($conn, $error_log_query);
									// }

									// invoice cost journal entry inserting to qb
									// try {
									// 	$journal_entry_result1 = QBAddJournalEntry($journalEntryForInvoiceCost);
									// 	$qb_msg = $journal_entry_result1['message'];

									// 	if ((isset($journal_entry_result1['status'])) && ($journal_entry_result1['status'] == 'success')) {
									// 		$qb_journal_entry_id = $journal_entry_result1['qb_journal_entry_id'];
									// 		$query = "UPDATE `bill_main` SET `qb_cost_id`='$qb_journal_entry_id' WHERE `invoice_no`='$invoice_no'";
									// 		$result1 = mysqli_query($conn, $query);
									// 		if (!$result1) {
									// 			$qb_msg = 'Quickbooks bill main qb_cost_id update error.';

									// 			$qb_msg = mysqli_real_escape_string($conn, $qb_msg);
									// 			$action = "UPDATE BILL MAIN INVOICE COST ID (Invoice Cost) : Invoice No: $invoice_no";
									// 			$functionName = "setStatus";

									// 			$error_log_query = "INSERT INTO `qb_debug_log` (`action`,`function`,`qb_message`,`action_time`)
									// 			VALUES ('$action','$functionName','$qb_msg','$time_now')";
									// 			$result = mysqli_query($conn, $error_log_query);
									// 		}
									// 		$query = "UPDATE `bill_main` SET `qb_status`=1 WHERE `invoice_no`='$invoice_no'";
									// 		$result1 = mysqli_query($conn, $query);
									// 		if (!$result1) {
									// 			$qb_msg = 'Quickbooks bill main qb status update error.';

									// 			$qb_msg = mysqli_real_escape_string($conn, $qb_msg);
									// 			$action = "UPDATE BILL MAIN INVOICE Status (Invoice Cost) : Invoice No: $invoice_no";
									// 			$functionName = "setStatus";

									// 			$error_log_query = "INSERT INTO `qb_debug_log` (`action`,`function`,`qb_message`,`action_time`)
									// 			VALUES ('$action','$functionName','$qb_msg','$time_now')";
									// 			$result = mysqli_query($conn, $error_log_query);
									// 		}
									// 	} else {
									// 		$qb_msg = mysqli_real_escape_string($conn, $qb_msg);
									// 		$action = "CUST ODR RETURN INVOICE, JOURNAL ENTRY INSERT (Invoice Cost) : Invoice No: $invoice_no";
									// 		$functionName = "setStatus";
									// 		$error_log_query = "INSERT INTO `qb_debug_log` (`action`,`function`,`qb_message`,`action_time`)
									// 		VALUES ('$action','$functionName','$qb_msg','$time_now')";
									// 		$result = mysqli_query($conn, $error_log_query);
									// 	}
									// } catch (Exception $e) {
									// 	$qb_msg = "<br>QuickBooks error: " . $e->getMessage();
									// 	$qb_msg = mysqli_real_escape_string($conn, $qb_msg);
									// 	$action = "CUST ODR RETURN INVOICE, JOURNAL ENTRY INSERT (Invoice Cost) : Invoice No: $invoice_no";
									// 	$functionName = "setStatus";

									// 	$error_log_query = "INSERT INTO `qb_debug_log` (`action`,`function`,`qb_message`,`action_time`)
									// 	VALUES ('$action','$functionName','$qb_msg','$time_now')";
									// 	$result = mysqli_query($conn, $error_log_query);
									// }
								}

								// positive (normal) invoice
								if (($invoiceCost > 0)) {
									// delete journal entry
									$debitAccountName = "Inventory Asset";
									$creditAccountName = "Cost of Goods Sold";
									$description = "[INVOICE] - Invoice No: $invoice_no, Customer : $custName";
									$debitEntityType = "";
									$debitEntityID = "";
									$creditEntityType = "";
									$creditEntityID = "";

									$journalEntryForInvoiceCost = buildJournalEntry($conn, $qb_temp_cost, $debitAccountName, $creditAccountName, $description, $debitEntityType, $debitEntityID, $creditEntityType, $creditEntityID);
									if (isset($journalEntryForInvoiceCost['error'])) {
										$qb_msg = $journalEntryForInvoiceCost['error'];
										throw new Exception("QuickBooks error: " . $qb_msg);
									} else {
										$batch_id = generateBatchID();
										$action_name = 'delete_invoice_cost_through_odr_insert';
										foreach ($journalEntryForInvoiceCost as $entry) {
											$posting_type = mysqli_real_escape_string($conn, $entry['posting_type']);
											$account_id = mysqli_real_escape_string($conn, $entry['account_id']);
											$account_name = mysqli_real_escape_string($conn, $entry['account_name']);
											$amount = mysqli_real_escape_string($conn, $entry['amount']);
											$description = mysqli_real_escape_string($conn, $entry['description']);
											$entity_type = isset($entry['entity_type']) ? mysqli_real_escape_string($conn, $entry['entity_type']) : null;
											$entity_id = isset($entry['entity_id']) ? mysqli_real_escape_string($conn, $entry['entity_id']) : null;

											$query = "INSERT INTO qb_queue (`batch_id`, `action`, `invoice_no`, `posting_type`, `account_id`, `account_name`, `invoice_cost`, `description`, `entity_type`, `entity_id`)
												VALUES ('$batch_id','$action_name', '$invoice_no', '$posting_type', '$account_id', '$account_name', '$amount', '$description',
													" . ($entity_type !== null ? "'$entity_type'" : "NULL") . ",
													" . ($entity_id !== null ? "'$entity_id'" : "NULL") . ")";
											$result = mysqli_query($conn, $query);
											if (!$result) {
												$message = "MySQL Error while inserting into qb_queue: " . mysqli_error($conn);
												throw new Exception($message);
											}
										}
									}
									// $journal_entry_array = array('id' => $qb_cost_id);
									// try {
									// 	$qb_result = QBDeleteJournalEntry($journal_entry_array);
									// 	$qb_msg = $qb_result['message'];
									// 	if (isset($qb_result['status']) && ($qb_result['status'] != 'success')) {
									// 		$qb_msg = mysqli_real_escape_string($conn, $qb_msg);
									// 		$action = "CUST ODR INVOICE, JOURNAL ENTRY DELETE (Invoice Cost) : Invoice No: $invoice_no";
									// 		$functionName = "setStatus";
									// 		$error_log_query = "INSERT INTO `qb_debug_log` (`action`,`function`,`qb_message`,`action_time`)
									// 		VALUES ('$action','$functionName','$qb_msg','$time_now')";
									// 		$result = mysqli_query($conn, $error_log_query);
									// 	}
									// } catch (Exception $e) {
									// 	$flag = false;
									// 	$qb_msg = "<br>QuickBooks error: " . $e->getMessage();
									// 	$qb_msg = mysqli_real_escape_string($conn, $qb_msg);

									// 	$action = "CUST ODR INVOICE, JOURNAL ENTRY DELETE (Invoice Cost) : Invoice No: $invoice_no";
									// 	$functionName = "setStatus";
									// 	$error_log_query = "INSERT INTO `qb_debug_log` (`action`,`function`,`qb_message`,`action_time`)
									// 	VALUES ('$action','$functionName','$qb_msg','$time_now')";
									// 	$result = mysqli_query($conn, $error_log_query);
									// }

									// invoice total journal entry constructing
									$debitAccountName = "Accounts Receivable (A/R)";
									$creditAccountName = "Sales";
									$description = "[INVOICE] - Invoice No: $invoice_no, Customer : $custName";
									$debitEntityType = "Customer";
									$debitEntityID = $qb_cust_id;
									$creditEntityType = "";
									$creditEntityID = "";

									$journalEntryForInvoiceTotal = buildJournalEntry($conn, $bill_total, $debitAccountName, $creditAccountName, $description, $debitEntityType, $debitEntityID, $creditEntityType, $creditEntityID);
									if (isset($journalEntryForInvoiceTotal['error'])) {
										$qb_msg = $journalEntryForInvoiceTotal['error'];
										throw new Exception("QuickBooks error: " . $qb_msg);
									} else {
										$batch_id = generateBatchID();
										$action_name = 'invoice_insert_through_odr_insert';
										foreach ($journalEntryForInvoiceTotal as $entry) {
											$posting_type = mysqli_real_escape_string($conn, $entry['posting_type']);
											$account_id = mysqli_real_escape_string($conn, $entry['account_id']);
											$account_name = mysqli_real_escape_string($conn, $entry['account_name']);
											$amount = mysqli_real_escape_string($conn, $entry['amount']);
											$description = mysqli_real_escape_string($conn, $entry['description']);
											$entity_type = isset($entry['entity_type']) ? mysqli_real_escape_string($conn, $entry['entity_type']) : null;
											$entity_id = isset($entry['entity_id']) ? mysqli_real_escape_string($conn, $entry['entity_id']) : null;

											$query = "INSERT INTO qb_queue (`batch_id`, `action`, `invoice_no`, `posting_type`, `account_id`, `account_name`, `invoice_total`, `description`, `entity_type`, `entity_id`)
												VALUES ('$batch_id','$action_name', '$invoice_no', '$posting_type', '$account_id', '$account_name', '$amount', '$description',
													" . ($entity_type !== null ? "'$entity_type'" : "NULL") . ",
													" . ($entity_id !== null ? "'$entity_id'" : "NULL") . ")";
											$result = mysqli_query($conn, $query);
											if (!$result) {
												$message = "MySQL Error while inserting into qb_queue: " . mysqli_error($conn);
												throw new Exception($message);
											}
										}
									}

									// invoice cost journal entry constructing
									$debitAccountName = "Cost of Goods Sold";
									$creditAccountName = "Inventory Asset";
									$description = "[INVOICE] - Invoice No: $invoice_no, Customer : $custName";
									$debitEntityType = "";
									$debitEntityID = "";
									$creditEntityType = "";
									$creditEntityID = "";

									$journalEntryForInvoiceCost = buildJournalEntry($conn, $invoiceCost, $debitAccountName, $creditAccountName, $description, $debitEntityType, $debitEntityID, $creditEntityType, $creditEntityID);
									if (isset($journalEntryForInvoiceCost['error'])) {
										$qb_msg = $journalEntryForInvoiceCost['error'];
										throw new Exception("QuickBooks error: " . $qb_msg);
									} else {
										$batch_id = generateBatchID();
										$action_name = 'invoice_insert_through_odr_insert';

										foreach ($journalEntryForInvoiceCost as $entry) {
											$posting_type = mysqli_real_escape_string($conn, $entry['posting_type']);
											$account_id = mysqli_real_escape_string($conn, $entry['account_id']);
											$account_name = mysqli_real_escape_string($conn, $entry['account_name']);
											$amount = mysqli_real_escape_string($conn, $entry['amount']);
											$description = mysqli_real_escape_string($conn, $entry['description']);
											$entity_type = isset($entry['entity_type']) ? mysqli_real_escape_string($conn, $entry['entity_type']) : null;
											$entity_id = isset($entry['entity_id']) ? mysqli_real_escape_string($conn, $entry['entity_id']) : null;

											$query = "INSERT INTO qb_queue (`batch_id`, `action`, `invoice_no`, `posting_type`, `account_id`, `account_name`, `invoice_cost`, `description`, `entity_type`, `entity_id`)
												VALUES ('$batch_id','$action_name', '$invoice_no', '$posting_type', '$account_id', '$account_name', '$amount', '$description',
													" . ($entity_type !== null ? "'$entity_type'" : "NULL") . ",
													" . ($entity_id !== null ? "'$entity_id'" : "NULL") . ")";
											$result = mysqli_query($conn, $query);
											if (!$result) {
												$message = "MySQL Error while inserting into qb_queue: " . mysqli_error($conn);
												throw new Exception($message);
											}
										}
									}

									// invoice total journal entry inserting to qb
									// try {
									// 	$journal_entry_result = QBAddJournalEntry($journalEntryForInvoiceTotal);
									// 	$qb_msg = $journal_entry_result['message'];

									// 	if (isset($journal_entry_result['status']) && ($journal_entry_result['status'] == 'success')) {
									// 		$qb_journal_entry_id = $journal_entry_result['qb_journal_entry_id'];
									// 		$query = "UPDATE `bill_main` SET `qb_value_id`='$qb_journal_entry_id' WHERE `invoice_no`='$invoice_no'";
									// 		$result1 = mysqli_query($conn, $query);
									// 		if (!$result1) {
									// 			$flag = false;
									// 			$qb_msg = 'Quickbooks bill main id update error.';

									// 			$qb_msg = mysqli_real_escape_string($conn, $qb_msg);
									// 			$action = "UPDATE BILL MAIN INVOICE COST ID (Invoice Total) : Invoice No: $invoice_no";
									// 			$functionName = "setStatus";

									// 			$error_log_query = "INSERT INTO `qb_debug_log` (`action`,`function`,`qb_message`,`action_time`)
									// 			VALUES ('$action','$functionName','$qb_msg','$time_now')";
									// 			$result = mysqli_query($conn, $error_log_query);
									// 		}
									// 	} else {
									// 		$qb_msg = mysqli_real_escape_string($conn, $qb_msg);
									// 		$action = "CUST ODR INVOICE, JOURNAL ENTRY INSERT (Invoice Total) : Invoice No: $invoice_no";
									// 		$functionName = "setStatus";
									// 		$error_log_query = "INSERT INTO `qb_debug_log` (`action`,`function`,`qb_message`,`action_time`)
									// 		VALUES ('$action','$functionName','$qb_msg','$time_now')";
									// 		$result = mysqli_query($conn, $error_log_query);
									// 	}
									// } catch (Exception $e) {
									// 	$flag = false;
									// 	$qb_msg = "<br>QuickBooks error: " . $e->getMessage();

									// 	$qb_msg = mysqli_real_escape_string($conn, $qb_msg);
									// 	$action = "CUST ODR INVOICE, JOURNAL ENTRY INSERT (Invoice Total) : Invoice No: $invoice_no";
									// 	$functionName = "setStatus";

									// 	$error_log_query = "INSERT INTO `qb_debug_log` (`action`,`function`,`qb_message`,`action_time`)
									// 	VALUES ('$action','$functionName','$qb_msg','$time_now')";
									// 	$result = mysqli_query($conn, $error_log_query);
									// }

									// invoice cost journal entry inserting to qb
									// try {
									// 	$journal_entry_result1 = QBAddJournalEntry($journalEntryForInvoiceCost);
									// 	$qb_msg = $journal_entry_result1['message'];

									// 	if (isset($journal_entry_result1['status']) && ($journal_entry_result1['status'] == 'success')) {
									// 		$qb_journal_entry_id = $journal_entry_result1['qb_journal_entry_id'];
									// 		$query = "UPDATE `bill_main` SET `qb_cost_id`='$qb_journal_entry_id' WHERE `invoice_no`='$invoice_no'";
									// 		$result1 = mysqli_query($conn, $query);
									// 		if (!$result1) {
									// 			$flag = false;
									// 			$qb_msg = 'Quickbooks shipment main id update error.';

									// 			$qb_msg = mysqli_real_escape_string($conn, $qb_msg);
									// 			$action = "UPDATE BILL MAIN INVOICE COST ID (Invoice Cost) : Invoice No: $invoice_no";
									// 			$functionName = "setStatus";

									// 			$error_log_query = "INSERT INTO `qb_debug_log` (`action`,`function`,`qb_message`,`action_time`)
									// 			VALUES ('$action','$functionName','$qb_msg','$time_now')";
									// 			$result = mysqli_query($conn, $error_log_query);
									// 		}
									// 	} else {
									// 		$qb_msg = mysqli_real_escape_string($conn, $qb_msg);
									// 		$action = "CUST ODR INVOICE, JOURNAL ENTRY INSERT (Invoice Cost) : Invoice No: $invoice_no";
									// 		$functionName = "setStatus";
									// 		$error_log_query = "INSERT INTO `qb_debug_log` (`action`,`function`,`qb_message`,`action_time`)
									// 		VALUES ('$action','$functionName','$qb_msg','$time_now')";
									// 		$result = mysqli_query($conn, $error_log_query);
									// 	}
									// } catch (Exception $e) {
									// 	$flag = false;
									// 	$qb_msg = "<br>QuickBooks error: " . $e->getMessage();

									// 	$qb_msg = mysqli_real_escape_string($conn, $qb_msg);
									// 	$action = "CUST ODR INVOICE, JOURNAL ENTRY INSERT (Invoice Cost) : Invoice No: $invoice_no";
									// 	$functionName = "setStatus";

									// 	$error_log_query = "INSERT INTO `qb_debug_log` (`action`,`function`,`qb_message`,`action_time`)
									// 	VALUES ('$action','$functionName','$qb_msg','$time_now')";
									// 	$result = mysqli_query($conn, $error_log_query);
									// }
								}

								// Retun items (packaged items journal entires)
								$query = "SELECT rt.replace_item, rt.qty, rm.mapped_inventory, rt.extra_pay, rt.id
								FROM return_main rm
								JOIN `return` rt ON rm.invoice_no = rt.invoice_no
								JOIN cust c ON c.id = rm.cust
								WHERE rt.odr_no = '$invoice_no'
								AND rt.odr_packed = '1'";
								$result = mysqli_query($conn, $query);
								if (mysqli_num_rows($result) > 0) {
									$item_cost = 0;
									while ($returnItemRow = mysqli_fetch_array($result)) {
										$replace_item = $returnItemRow[0];
										$rt_qty = $returnItemRow[1];
										$mapped_inventory = $returnItemRow[2];
										$extra_pay = $returnItemRow[3];
										$return_id = $returnItemRow[4];

										// Get the cost of the item from the inventory
										$cost_query = "SELECT c_price FROM inventory_qty WHERE item='$replace_item' AND `location`='$mapped_inventory'";
										$row = mysqli_fetch_row(mysqli_query($conn, $cost_query));

										// Ensure the cost query returns a result
										if ($row) {
											$cost = $row[0];
											// Add the cost of this returned item to the total cost
											$item_cost += (($cost * $rt_qty) + $extra_pay);
										}
									}

									// Proceed only if return items are found
									$debitAccountName = "Return Item";
									$creditAccountName = "Inventory Asset";
									$description = "[RETURN ITEMS] - Invoice No: $invoice_no, Customer: $custName";
									$debitEntityType = "";
									$debitEntityID = "";
									$creditEntityType = "";
									$creditEntityID = "";

									$journalEntryForReturnItems = buildJournalEntry($conn, $item_cost, $debitAccountName, $creditAccountName, $description, $debitEntityType, $debitEntityID, $creditEntityType, $creditEntityID);
									if (isset($journalEntryForReturnItems['error'])) {
										$qb_msg = $journalEntryForReturnItems['error'];
										throw new Exception($qb_msg);
									} else {
										$batch_id = generateBatchID();
										$action_name = 'return_item_invoice_insert_through_odr_insert';

										foreach ($journalEntryForInvoiceCost as $entry) {
											$posting_type = mysqli_real_escape_string($conn, $entry['posting_type']);
											$account_id = mysqli_real_escape_string($conn, $entry['account_id']);
											$account_name = mysqli_real_escape_string($conn, $entry['account_name']);
											$amount = mysqli_real_escape_string($conn, $entry['amount']);
											$description = mysqli_real_escape_string($conn, $entry['description']);
											$entity_type = isset($entry['entity_type']) ? mysqli_real_escape_string($conn, $entry['entity_type']) : null;
											$entity_id = isset($entry['entity_id']) ? mysqli_real_escape_string($conn, $entry['entity_id']) : null;

											$query = "INSERT INTO qb_queue (`batch_id`, `action`, `invoice_no`, `posting_type`, `account_id`, `account_name`, `invoice_cost`, `description`, `entity_type`, `entity_id`)
												VALUES ('$batch_id','$action_name', '$invoice_no', '$posting_type', '$account_id', '$account_name', '$amount', '$description',
													" . ($entity_type !== null ? "'$entity_type'" : "NULL") . ",
													" . ($entity_id !== null ? "'$entity_id'" : "NULL") . ")";
											$result = mysqli_query($conn, $query);
											if (!$result) {
												$message = "MySQL Error while inserting into qb_queue: " . mysqli_error($conn);
												throw new Exception($message);
											}
										}
									}

									// try {
									// 	$journal_entry_result = QBAddJournalEntry($journalEntryForReturnItems);
									// 	$qb_msg = $journal_entry_result['message'];

									// 	if (isset($journal_entry_result['status']) && ($journal_entry_result['status'] == 'success')) {
									// 		$qb_journal_entry_id = $journal_entry_result['qb_journal_entry_id'];
									// 		$query = "UPDATE `return` SET `qb_id`='$qb_journal_entry_id' WHERE `odr_no`='$invoice_no'";
									// 		$result1 = mysqli_query($conn, $query);
									// 		if (!$result1) {
									// 			$flag = false;
									// 			$qb_msg = 'QuickBooks return qb_id update error.';

									// 			$qb_msg = mysqli_real_escape_string($conn, $qb_msg);
									// 			$action = "UPDATE RETURN QB ID : Return Id : $return_id,  Invoice No: $invoice_no";
									// 			$functionName = "setStatus";

									// 			$error_log_query = "INSERT INTO `qb_debug_log` (`action`,`function`,`qb_message`,`action_time`)
									// 			VALUES ('$action','$functionName','$qb_msg','$time_now')";
									// 			$result = mysqli_query($conn, $error_log_query);
									// 		}
									// 	} else {
									// 		$qb_msg = mysqli_real_escape_string($conn, $qb_msg);
									// 		$action = "CUST ODR RETURN ITEMS, JOURNAL ENTRY INSERT (Item Cost) : Return Id : $return_id, Invoice No: $invoice_no";
									// 		$functionName = "setStatus";

									// 		$error_log_query = "INSERT INTO `qb_debug_log` (`action`,`function`,`qb_message`,`action_time`)
									// 		VALUES ('$action','$functionName','$qb_msg','$time_now')";
									// 		$result = mysqli_query($conn, $error_log_query);
									// 	}
									// } catch (Exception $e) {
									// 	$flag = false;
									// 	$qb_msg = "<br>QuickBooks error: " . $e->getMessage();

									// 	$qb_msg = mysqli_real_escape_string($conn, $qb_msg);
									// 	$action = "CUST ODR RETURN ITEMS, JOURNAL ENTRY INSERT (Item Cost) : Return Id : $return_id, Invoice No: $invoice_no";
									// 	$functionName = "setStatus";

									// 	$error_log_query = "INSERT INTO `qb_debug_log` (`action`,`function`,`qb_message`,`action_time`)
									// 	VALUES ('$action','$functionName','$qb_msg','$time_now')";
									// 	$result = mysqli_query($conn, $error_log_query);
									// }
								}
							} else {
								$qb_msg = 'Error : SQL Error: ' . mysqli_error($conn);
								throw new Exception("QuickBooks error: " . $qb_msg);
							}
						}
					} else {
						$qb_msg = "Temp Invoice cost cannot be zero, Cost not updated for this invoice before";
						throw new Exception("QuickBooks error: " . $qb_msg);
					}
				}
			}
		}
		// Commit moved AFTER all operations including QB
		mysqli_commit($conn);
		if ($method == 'shipped') {
			sms3($invoice_no);
		}
		$message = $message . ' ' . $qb_msg;
	} catch (Exception $e) {
		mysqli_rollback($conn);
		$message = $e->getMessage();
		$out = false;
	}
	return $out;
}

// added by nirmal 07_06_2023
// update by nirmal 12_09_2024 (added quickbooks integration)
function ajaxSetStatus()
{
	global $message, $type, $invoice_no;
	$invoice_no = $_REQUEST['id'];
	$salesman = $_COOKIE['user_id'];
	$method = $_REQUEST['method'];
	$out = true;
	$bill_status = $error = $qb_msg = '';
	$status = 1;
	$jsonArray = array();
	$time_now = timeNow();
	include('config.php');

	$result = mysqli_query($conn, "SELECT `status`,`type` FROM bill_main WHERE invoice_no='$invoice_no'");
	$row = mysqli_fetch_assoc($result);
	$bill_status = $row['status'];

	if ($out) {
		if (!$result) {
			$error = mysqli_error($conn);
			$message = "Error: " . $error;
			$status = 0;
			$out = false;
		}
	}
	if ($out) {
		if ($bill_status != 4) {
			$message = "Error: This invoice is (" . $invoice_no . ") not in shipped stage.";
			$status = 0;
			$out = false;
		}
	}

	if (($method == 'delivered') && ($bill_status == 4)) {
		$query = "UPDATE `bill_main` SET `deliverd_by`='$salesman', `deliverd_timestamp`='$time_now', `status`='5' WHERE `invoice_no`='$invoice_no'";
		$result = mysqli_query($conn, $query);

		if (!$result) {
			$error = mysqli_error($conn);
			$message = "Error: " . $error;
			$status = 0;
			$out = false;
		}
	}

	$jsonArray["message"] = $message;
	$jsonArray["status"] = $status;
	$myJSON = json_encode($jsonArray);

	return $myJSON;
}

// update by nirmal 18_12_2024 (error log bug fix)
function orderUnassign()
{
	global $message;
	$authorization = false;
	// old code
	// if(isset($_COOKIE['report']) || isset($_COOKIE['manager'])){
	// 	if(($_COOKIE['report']==$_COOKIE['user_id'])||($_COOKIE['manager']==$_COOKIE['user_id'])){
	// 		$authorization=true;
	// 	}
	// }
	if ((isset($_COOKIE['report']) && isset($_COOKIE['user_id'])) || (isset($_COOKIE['manager']) && isset($_COOKIE['user_id']))) {
		if (
			(isset($_COOKIE['report']) && $_COOKIE['report'] == $_COOKIE['user_id']) ||
			(isset($_COOKIE['manager']) && $_COOKIE['manager'] == $_COOKIE['user_id'])
		) {
			$authorization = true;
		}
	}
	$invoice_no = $_REQUEST['id'];
	$out = true;

	include('config.php');
	$result = mysqli_query($conn, "SELECT `status` FROM bill_main WHERE invoice_no='$invoice_no'");
	$row = mysqli_fetch_assoc($result);
	$bm_status = $row['status'];

	if ($bm_status == 2 && $authorization) {
		$query1 = "UPDATE bill_main SET seen_by=null, seen_timestamp=null, `status`='1' WHERE invoice_no='$invoice_no'";
		$result1 = mysqli_query($conn, $query1);
		if (!$result1) {
			$msg = 'Error: The Order Could Not be Unassigned';
			$out = false;
		}
	} else {
		$msg = 'Error: Unauthorize Request';
		$out = false;
	}

	if ($out) {
		$message = 'The order was unassigned successfully';
		return true;
	} else {
		$message = $msg;
		return false;
	}
}

function setOrderBy()
{
	$orderby = 'cust';
	if (isset($_COOKIE['odr_odrby'])) {
		if ($_COOKIE['odr_odrby'] == 'date')
			$orderby = 'date';
	}
	if ($orderby == 'cust')
		$orderby = 'date';
	else if ($orderby == 'date')
		$orderby = 'cust';
	if (setcookie("odr_odrby", $orderby, time() + 3600 * 10))
		return true;
	else
		return false;
}

// update by nirmal 07_10_2024 (added system id 17 sms sending code)
function sms3($invoice_no)
{
	$sub_system = $_COOKIE['sub_system'];
	$timenow = timeNow();
	$msg = $cr_balance_txt = '';
	$inf_from_email = inf_from_email();
	$inf_to_email = inf_to_email();
	$sms_data = json_decode(sms_credential($sub_system));
	$sms_user = $sms_data->{"user"};
	$sms_pass = $sms_data->{"pass"};
	$sms_balance = $sms_data->{"balance"};
	$sms_sender_id = $sms_data->{"sms_sender_id"};
	include('config.php');

	$result = mysqli_query($conn2, "SELECT cu.sms as `cu_sms`, SUM(bi.qty * bi.unit_price) AS total, cu.mobile,bm.`type`,bm.sms as `bm_sms`,cu.id as `cu_id`,st.shop_name_sms FROM bill_main bm ,bill bi, cust cu, stores st WHERE bm.invoice_no=bi.invoice_no AND bm.`cust`=cu.id AND bm.store=st.id AND bm.invoice_no='$invoice_no'");
	$row = mysqli_fetch_assoc($result);
	$sms_cust = $row['cu_sms'];
	$bill_total = $row['total'];
	$mobile = $row['mobile'];
	$bm_type = $row['type'];
	$sms_sent = $row['bm_sms'];
	$cust_tmp = $row['cu_id'];
	$inf_company = $row['shop_name_sms'];

	$result = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='systemid'");
	$row = mysqli_fetch_assoc($result);
	$systemid = $row['value'];

	$result = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='decimal'");
	$row = mysqli_fetch_assoc($result);
	$decimal = $row['value'];

	$result = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='currency'");
	$row = mysqli_fetch_assoc($result);
	$currency = $row['value'];

	if (($sms_cust == 1) && ($sms_balance > 0) && ($_SERVER['SERVER_NAME'] == inf_url_primary()) && ($bm_type != 3) && ($systemid == 17)) {
		if ($bm_type == 4 || $bm_type == 5) {
			$query1 = "SELECT SUM(bi.qty*bi.unit_price) as `total` FROM bill bi, bill_main bm WHERE bm.invoice_no=bi.invoice_no AND bm.`status`!=0 AND bm.exclude=0 AND bm.`lock`=1 AND bm.`cust`='$cust_tmp'";
			$result1 = mysqli_query($conn2, $query1);
			$row1 = mysqli_fetch_assoc($result1);
			$totalinv = $row1['total'];

			$query1 = "SELECT SUM(py.amount) as `pay` FROM payment py WHERE py.status=0 AND py.`cust`='$cust_tmp' AND py.chque_return=0";
			$result1 = mysqli_query($conn2, $query1);
			$row1 = mysqli_fetch_assoc($result1);
			$totalpay = $row1['pay'];

			$credit_balance = $totalinv - $totalpay;
			$cr_balance_txt = "\n\nAmount = " . $currency . " " . number_format($bill_total, $decimal) . "\n\nTotal Outstanding = " . $currency . " " . number_format($credit_balance, $decimal);
		}
		$message = $inf_company . "\n\nInv no: " . str_pad($invoice_no, 7, "0", STR_PAD_LEFT) . " " . $cr_balance_txt . "\n\nYour order has been dispatched. You will receive it shortly.";

		$text = $message;
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
		} else {
			$mailstatus = false;
		}
		if ($mailstatus) {
			$sms_balance--;
			if (set_sms_balance($sub_system, $sms_balance)) {
				$msg = 'SMS Sent<hr />';
			} else {
				$msg = 'Database could not be updated<hr />';
			}
			$query = "SELECT MAX(id) FROM sms";
			$row = mysqli_fetch_row(mysqli_query($conn, $query));
			$max_id = $row[0];
			$query = "SELECT MIN(id) FROM sms";
			$row = mysqli_fetch_row(mysqli_query($conn, $query));
			$min_id = $row[0];
			$next_id = $max_id + 1;
			$query = "UPDATE `sms` SET `id`='$next_id',`timestamp`='$timenow',`case`='4',`ref`='$invoice_no',`text`='$message' WHERE id='$min_id'";
			mysqli_query($conn, $query);
		} else
			$msg = 'SMS could not be sent<hr />';
	} else if (($sms_cust == 1) && ($sms_balance > 0) && ($_SERVER['SERVER_NAME'] == inf_url_primary()) && ($bm_type != 3) && (strpos($mobile, "7") == 1)) {
		if ($bm_type == 4 || $bm_type == 5) {
			$query1 = "SELECT SUM(bi.qty*bi.unit_price) as `total` FROM bill bi, bill_main bm WHERE bm.invoice_no=bi.invoice_no AND bm.`status`!=0 AND bm.exclude=0 AND bm.`lock`=1 AND bm.`cust`='$cust_tmp'";
			$result1 = mysqli_query($conn2, $query1);
			$row1 = mysqli_fetch_assoc($result1);
			$totalinv = $row1['total'];
			$query1 = "SELECT SUM(py.amount) as `pay` FROM payment py WHERE py.status=0 AND py.`cust`='$cust_tmp' AND py.chque_return=0";
			$result1 = mysqli_query($conn2, $query1);
			$row1 = mysqli_fetch_assoc($result1);
			$totalpay = $row1['pay'];
			$credit_balance = $totalinv - $totalpay;
			$cr_balance_txt = '+++Amount+=+' . number_format($bill_total) . '-NLC-Total+Outstanding+=++' . number_format($credit_balance);
		}
		$message = str_replace(" ", "+", $inf_company) . '-NLC-Inv+no:+' . str_pad($invoice_no, 7, "0", STR_PAD_LEFT) . '+' . $cr_balance_txt . '-NLC-Your+order+has+been+dispatched.+You+will+receive+it+shortly.';
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

		$baseurl = "http://www.textit.biz/sendmsg";
		$url = "$baseurl/?id=$sms_user&pw=$sms_pass&eco=Y&to=$mobile&text=$text";
		$ret = file($url);
		$res = explode(":", $ret[0]);

		if (trim($res[0]) == "OK")
			$mailstatus = true;
		else
			$mailstatus = false;
		//----------------------------------------------------------------//
		if ($mailstatus) {
			$sms_balance--;
			if (set_sms_balance($sub_system, $sms_balance))
				$msg = 'SMS Sent<hr />';
			else
				$msg = 'Database Cound Not be Updated<hr />';

			$query = "SELECT MAX(id) FROM sms";
			$row = mysqli_fetch_row(mysqli_query($conn, $query));
			$max_id = $row[0];
			$query = "SELECT MIN(id) FROM sms";
			$row = mysqli_fetch_row(mysqli_query($conn, $query));
			$min_id = $row[0];
			$next_id = $max_id + 1;
			$query = "UPDATE `sms` SET `id`='$next_id',`timestamp`='$timenow',`case`='4',`ref`='$invoice_no',`text`='$message' WHERE id='$min_id'";
			mysqli_query($conn, $query);

		} else
			$msg = 'SMS could not be sent<hr />';
	} else
		$msg = 'SMS disabled on customer';
}

//--------------------------------Return-----------------------------------//
function getReturn()
{
	global $rt_item, $rt_itmdesc, $rt_qty, $rt_unic;
	$store = $_COOKIE['store'];
	$rt_item = array();
	include('config.php');
	$query = "SELECT rt.return_item,itm.description,SUM(rt.qty),itm.unic FROM return_main rm, `return` rt, inventory_items itm WHERE rm.invoice_no=rt.invoice_no AND rt.return_item=itm.id AND rt.`status`=0 AND rm.`status`=2 AND rm.store='$store' GROUP BY rt.return_item";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$rt_item[] = $row[0];
		$rt_itmdesc[] = $row[1];
		$rt_qty[] = $row[2];
		$rt_unic[] = $row[3];
	}
}

function getUnicReturn()
{
	global $rt_id, $rt_itmdesc, $itu_sn;
	$store = $_COOKIE['store'];
	$item = $_GET['item'];
	include('config.php');
	$query = "SELECT rt.id,itm.description,itu.sn FROM return_main rm, `return` rt, inventory_items itm, inventory_unic_item itu WHERE rm.invoice_no=rt.invoice_no AND rt.return_item=itm.id AND rt.id=itu.return_id AND rt.`status`=0 AND itu.`status`=4 AND rm.store='$store' AND rt.return_item='$item'";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$rt_id[] = $row[0];
		$rt_itmdesc = $row[1];
		$itu_sn[] = $row[2];
	}
}

function processReturn()
{
	global $message;
	$item = $_REQUEST['item'];
	$invrtn = $_REQUEST['invrtn'];
	$disrtn = $_REQUEST['disrtn'];
	$store = $_COOKIE['store'];
	$user = $_COOKIE['user_id'];
	$time_now = timeNow();
	$output = false;

	include('config.php');
	$result0 = mysqli_query($conn, "SELECT id,qty,w_price,r_price,c_price FROM inventory_qty WHERE location='$store' AND item='$item'");
	$row0 = mysqli_fetch_assoc($result0);
	$itq_id = $row0['id'];
	$itq_qty = $row0['qty'];
	$w_price = $row0['w_price'];
	$r_price = $row0['r_price'];
	$c_price = $row0['c_price'];

	$result = mysqli_query($conn, "SELECT SUM(rt.qty) as qty FROM return_main rm, `return` rt WHERE rm.invoice_no=rt.invoice_no AND rm.`store`='$store' AND rt.`status`='0' AND rm.`status`='2' AND rt.return_item='$item'");
	$row = mysqli_fetch_assoc($result);
	$totalqty = $row['qty'];

	$new_qty = $itq_qty + $invrtn;

	if ($totalqty == ($invrtn + $disrtn)) {
		if ($disrtn > 0) {
			$query2 = "INSERT INTO `return_disposal` (`item`,`w_price`,`r_price`,`c_price`,`qty`,`store`,`date`) VALUES ('$item','$w_price','$r_price','$c_price','$disrtn','$store','$time_now')";
			$result2 = mysqli_query($conn, $query2);
			$lastid_temp = mysqli_insert_id($conn);
			if ($result2) {
				$query1 = "UPDATE `return` rt, return_main rm SET rt.`disposal_id`='$lastid_temp',rt.`status`='1',rt.`process_date`='$time_now',rt.`process_by`='$user' WHERE rm.invoice_no=rt.invoice_no AND rm.`store`='$store' AND rt.`status`='0' AND rt.`return_item`='$item'";
				$result1 = mysqli_query($conn, $query1);
			}
			if ($result1)
				$output = true;
		}
		if ($invrtn > 0) {
			$debug_id = debugStart($itq_id, $invrtn);
			$query1 = "UPDATE `return` rt, return_main rm SET rt.`status`='1',rt.`process_date`='$time_now',rt.`process_by`='$user' WHERE rm.invoice_no=rt.invoice_no AND rm.`store`='$store' AND rt.`status`='0' AND rt.`return_item`='$item'";
			$result1 = mysqli_query($conn, $query1);
			if ($result1) {
				$query2 = "UPDATE `inventory_qty` SET `qty`='$new_qty' WHERE `id`='$itq_id'";
				$result2 = mysqli_query($conn, $query2);
				if ($result2)
					$output = true;
			}
		}
	}
	if ($output) {
		if ($invrtn > 0)
			debugEnd($debug_id, 'success');
		$message = 'Item was processed successfully!';
		return true;
	} else {
		if ($invrtn > 0)
			debugEnd($debug_id, 'fail');
		$message = 'Item could not be processed !';
		return false;
	}
}

function moveUnicInv()
{
	global $message, $item;
	$rtn_id = $_GET['id'];
	$item = $_GET['item'];
	$user = $_COOKIE['user_id'];
	$store = $_COOKIE['store'];
	$time_now = timeNow();
	$output = false;
	$ivq_id = '';

	include('config.php');
	$query = "SELECT id,qty,w_price,r_price,c_price FROM inventory_qty WHERE item='$item' AND location='$store'";
	$result = mysqli_query($conn, $query);
	$row = mysqli_fetch_row($result);
	$ivq_id = $row[0];
	$ivq_qty = $row[1];
	$ivq_to_wprice = $row[2];
	$ivq_to_rprice = $row[3];
	$ivq_to_cprice = $row[4];


	if ($ivq_id == '') {
		$query = "SELECT qty,w_price,r_price,c_price FROM inventory_qty WHERE item='$item' LIMIT 1";
		$result = mysqli_query($conn, $query);
		$row = mysqli_fetch_row($result);
		$ivq_qty = $row[1];
		$ivq_to_wprice = $row[2];
		$ivq_to_rprice = $row[3];
		$ivq_to_cprice = $row[4];

		$query = "INSERT INTO `inventory_qty` (`item`,`location`,`w_price`,`r_price`,`c_price`,`qty`) VALUES ('$item','$store','$ivq_to_wprice','$ivq_to_rprice','$ivq_to_cprice','0')";
		$result = mysqli_query($conn, $query);
		$ivq_id = mysqli_insert_id($conn);
	}

	if ($ivq_id != '') {
		$query1 = "UPDATE `return` SET `status`='1',`process_date`='$time_now',`process_by`='$user' WHERE `id`='$rtn_id'";
		$result1 = mysqli_query($conn, $query1);
		if ($result1) {
			$query2 = "UPDATE `inventory_unic_item` SET `status`='0',`itq_id`='$ivq_id' WHERE `return_id`='$rtn_id' AND `status`='4'";
			$result2 = mysqli_query($conn, $query2);
			if ($result2) {
				$query3 = "UPDATE `inventory_qty` SET `qty`=qty+1 WHERE `id`='$ivq_id'";
				$result3 = mysqli_query($conn, $query3);
			}
			if ($result3) {
				$output = true;
			}
		}
	}

	if ($output) {
		$message = 'Item was Processed Successfully!';
		return true;
	} else {
		$message = 'Item Could Not be Processed !';
		return false;
	}
}

function moveUnicDis()
{
	global $message, $item;
	$rtn_id = $_GET['id'];
	$item = $_GET['item'];
	$user = $_COOKIE['user_id'];
	$store = $_COOKIE['store'];
	$time_now = timeNow();
	$result3 = false;

	include('config.php');
	$result0 = mysqli_query($conn, "SELECT id,w_price,r_price,c_price FROM inventory_qty WHERE location='$store' AND item='$item'");
	$row0 = mysqli_fetch_assoc($result0);
	$itq_id = $row0['id'];
	$w_price = $row0['w_price'];
	$r_price = $row0['r_price'];
	$c_price = $row0['c_price'];

	$query1 = "INSERT INTO `return_disposal` (`item`,`w_price`,`r_price`,`c_price`,`qty`,`store`,`date`) VALUES ('$item','$w_price','$r_price','$c_price','1','$store','$time_now')";
	$result1 = mysqli_query($conn, $query1);
	$lastid_temp = mysqli_insert_id($conn);
	if ($result1) {
		$query2 = "UPDATE `return` SET `disposal_id`='$lastid_temp',`status`='1',`process_date`='$time_now',`process_by`='$user' WHERE `id`='$rtn_id'";
		$result2 = mysqli_query($conn, $query2);
		$query2 = "UPDATE `inventory_unic_item` SET `status`='6' WHERE `return_id`='$rtn_id' AND `status`='4'";
		$result2 = mysqli_query($conn, $query2);
	}

	if ($result2) {
		$message = 'Item was Processed Successfully!';
		return true;
	} else {
		$message = 'Item Could Not be Processed !';
		return false;
	}
}

function generateAddressTag()
{
	global $from_name, $from_address, $from_mob, $to_name, $to_address, $to_mob;
	$id = $_GET['id'];
	$store = $_COOKIE['store'];
	include('config.php');
	$query = "SELECT shop_name,address,tel FROM stores WHERE id='$store'";
	$result = mysqli_query($conn2, $query);
	$row = mysqli_fetch_row($result);
	$from_name = $row[0];
	$from_address = $row[1];
	$from_mob = $row[2];
	$query = "SELECT cu.name,cu.shop_address,cu.mobile FROM bill_main bm, cust cu WHERE bm.`cust`=cu.id AND bm.invoice_no='$id'";
	$result = mysqli_query($conn2, $query);
	$row = mysqli_fetch_row($result);
	$to_name = $row[0];
	$to_address = $row[1];
	$to_mob = $row[2];
}

// update by nirmal 02_01_2024 (added new custom number system)
// update by nirmal 14_02_2024 (get customer name to print address)
function generateTag()
{
	global $from_name, $from_address, $from_mob, $order_no, $to_name, $to_address, $to_mob, $to_cust_name;
	$id = $_GET['id'];
	$store = $_COOKIE['store'];
	if ($id != '') {
		$id = implode("','", explode(',', $id));
		include('config.php');
		$query = "SELECT shop_name,address,tel FROM stores WHERE id='$store'";
		$result = mysqli_query($conn2, $query);
		$row = mysqli_fetch_row($result);
		$from_name = $row[0];
		$from_address = $row[1];
		$from_mob = $row[2];
		$query = "SELECT bm.invoice_no,cu.name,cu.shop_address,cu.mobile,cu.cust_name FROM bill_main bm, cust cu WHERE bm.`cust`=cu.id AND bm.invoice_no IN ('$id')";
		$result = mysqli_query($conn2, $query);
		while ($row = mysqli_fetch_array($result)) {
			$order_no[] = $row[0];
			$to_name[] = $row[1];
			$to_address[] = $row[2];
			$to_mob[] = $row[3];
			$to_cust_name[] = $row[4];
		}
	}
}

function moveCustOdr()
{
	global $id, $message;
	$id = $_GET['id'];
	include('config.php');
	$query = "UPDATE bill_main SET `type`='4',`lock`='2' WHERE invoice_no='$id' AND `status`='1'";
	$result = mysqli_query($conn, $query);
	if ($result) {
		$message = 'Order was Changed Successfully!';
		return true;
	} else {
		$message = 'Order Could Not be Changed !';
		return false;
	}
}

function getStore()
{
	global $store_id, $store_name;
	include('config.php');
	$query = "SELECT id,name FROM stores WHERE `status`='1'";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$store_id[] = $row[0];
		$store_name[] = $row[1];
	}
}

function searchOrder()
{
	global $order_no, $message;
	$out = true;
	$order_no = ltrim($_POST['order_no'], '0');
	include('config.php');
	$query = "SELECT COUNT(invoice_no),`lock`,`status` FROM bill_main WHERE invoice_no='$order_no'";
	$result = mysqli_query($conn2, $query);
	$row = mysqli_fetch_row($result);
	if ($row[2] != 3) {
		$message = 'Invalid Status';
		$out = false;
	} else
		if ($row[1] != 1) {
			$message = 'Unlocked Invoice';
			$out = false;
		} else
			if ($row[0] == 0) {
				$message = 'Invalid Invoice No';
				$out = false;
			}

	if ($out)
		return true;
	else
		return false;
}

function apendCourier()
{
	global $order_no, $message;
	$message = 'Tracking ID was Added Successfully';
	$out = true;
	$order_no = $_POST['order_no'];
	$tracking_id = $_POST['tracking_id'];
	$weight = $_POST['weight'];
	$user_id = $_COOKIE['user_id'];
	$time_now = timeNow();

	include('config.php');
	$query = "SELECT COUNT(invoice_no) FROM bill_main WHERE `lock`='1' AND `status`='3' AND invoice_no='$order_no'";
	$result = mysqli_query($conn, $query);
	$row = mysqli_fetch_row($result);
	if ($row[0] == 0) {
		$message = 'Unauthorize Request';
		$out = false;
	} else
		if ($tracking_id == '') {
			$message = 'Tracking ID Cannot be Empty';
			$out = false;
		}
	/*
																																																																	  if($out){
																																																																		  $query="SELECT COUNT(invoice_no) FROM bill_main WHERE `tracking_id`='$tracking_id'";
																																																																		  $result=mysqli_query($conn,$query);
																																																																		  $row=mysqli_fetch_row($result);
																																																																		  if($row[0]>0){ $message='This Tracking ID is Alredy Allocated'; $out=false; }
																																																																	  }
																																																																	  */

	if ($out) {
		$query = "UPDATE `bill_main` SET `tracking_id`='$tracking_id',`weight`='$weight',`shipped_by`='$user_id',`shipped_timestamp`='$time_now',status='4' WHERE `invoice_no`='$order_no'";
		// print $query;
		$result = mysqli_query($conn, $query);
		if (!$result) {
			$message = 'Error: Failed to Apend the Tracking ID';
			$out = false;
		}
	}

	if ($out)
		return true;
	else
		return false;
}

function generateTrackingID()
{
	global $invoice_no, $tracking_id, $weight, $inv_date, $cust_name;
	$date = $_POST['rep_date'];
	$cust = '';
	$i = -1;

	include('config.php');
	$query = "SELECT bm.invoice_no,bm.tracking_id,bm.weight,date(bm.billed_timestamp),cu.`name` FROM bill_main bm, cust cu WHERE bm.`cust`=cu.id AND bm.tracking_id!='' AND date(bm.shipped_timestamp)='$date' ORDER BY bm.`cust`";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		if ($cust != $row[4]) {
			$i++;
			$invoice_no[$i][] = $row[0];
			$tracking_id[$i] = $row[1];
			$weight[$i] = $row[2];
			$inv_date[$i] = $row[3];
			$cust_name[$i] = $row[4];
		} else {
			$invoice_no[$i][] = $row[0];
		}
		$cust = $row[4];
	}
}

function getTrackingReport()
{
	global $from_date, $to_date, $invoice_no, $tracking_id, $weight, $shp_date, $cust_name, $weight_ro, $amount, $amount_dis;
	$invoice_no = $tracking_id = $weight = $shp_date = $cust_name = $weight_ro = $amount = $amount_dis = array();

	if (isset($_GET['from_date']) && isset($_GET['to_date'])) {
		$from_date = $_GET['from_date'];
		$to_date = $_GET['to_date'];
		if (($from_date != '') && ($to_date != '')) {

			$cust = '';
			$i = -1;

			include('config.php');
			$result = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='courier_1kg'");
			$row = mysqli_fetch_assoc($result);
			$courier_1kg = $row['value'];
			$result = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='courier_kg'");
			$row = mysqli_fetch_assoc($result);
			$courier_kg = $row['value'];
			$result = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='courier_discount'");
			$row = mysqli_fetch_assoc($result);
			$courier_discount = $row['value'];


			$query = "SELECT bm.invoice_no,bm.tracking_id,bm.weight,date(bm.shipped_timestamp),cu.`name` FROM bill_main bm, cust cu WHERE bm.`cust`=cu.id AND bm.tracking_id!='' AND date(bm.shipped_timestamp) BETWEEN '$from_date' AND '$to_date' ORDER BY date(bm.`shipped_timestamp`),bm.tracking_id";
			$result = mysqli_query($conn2, $query);
			while ($row = mysqli_fetch_array($result)) {
				if ($cust != $row[4]) {
					$i++;
					$invoice_no[$i][] = $row[0];
					$tracking_id[$i] = $row[1];
					$weight[$i] = $row[2];
					$shp_date[$i] = $row[3];
					$cust_name[$i] = $row[4];

					if ($row[2] > 0)
						$weight_ro1 = (int) $row[2];
					else
						$weight_ro1 = 0;
					$weight_ro[$i] = $weight_ro1;
					if ($weight_ro1 > 1)
						$more_kg = $weight_ro1 - 1;
					else
						$more_kg = 0;
					$amount1 = $courier_1kg + ($more_kg * $courier_kg);
					$amount2 = $amount1 * ((100 - $courier_discount) / 100);
					$amount[$i] = $amount1;
					$amount_dis[$i] = $amount2;
				} else {
					$invoice_no[$i][] = $row[0];
				}
				$cust = $row[4];
			}
		}
	}
}


function getCommisionReport()
{
	global $from_date, $to_date, $store, $r1_odr_no, $r1_odr_date, $r1_pick_date, $r1_pack_date, $r1_amount, $r2_odr_no, $r2_pick_by, $r2_pack_by, $r2_amount, $r2_pick_date, $r2_pack_date, $user_arr, $r2_pick_uniq, $r2_pack_uniq;
	$date_list = $user_arr = $r2_pick_by = $r2_pack_by = $r2_pick_uniq = $r2_pack_uniq = $r1_amount = $r2_odr_no = array();
	if (isset($_GET['from_date']) && isset($_GET['to_date']) && isset($_GET['store'])) {
		$from_date = $_GET['from_date'];
		$to_date = $_GET['to_date'];
		$store = $_GET['store'];
		if (($from_date != '') && ($to_date != '') && ($store != '')) {
			$user_arr[''] = '';
			include('config.php');
			$query = "SELECT id,username FROM userprofile";
			$result = mysqli_query($conn2, $query);
			while ($row = mysqli_fetch_array($result)) {
				$user_arr[$row[0]] = ucfirst($row[1]);
			}

			$query = "SELECT bm.invoice_no,date(bm.order_timestamp),date(bm.seen_timestamp),date(bm.packed_timestamp),bm.`invoice_+total`+bm.`invoice_-total` FROM bill_main bm WHERE date(bm.order_timestamp) BETWEEN '$from_date' AND '$to_date' AND bm.store='$store' AND date(bm.order_timestamp)=date(bm.seen_timestamp) AND date(bm.seen_timestamp)=date(bm.packed_timestamp) AND bm.`status`='5'";
			$result = mysqli_query($conn2, $query);
			while ($row = mysqli_fetch_array($result)) {
				$r1_odr_no[] = $row[0];
				$r1_odr_date[] = $row[1];
				$r1_pick_date[] = $row[2];
				$r1_pack_date[] = $row[3];
				$r1_amount[] = $row[4];
			}
			$query = "SELECT bm.invoice_no,bm.seen_by,date(bm.seen_timestamp),bm.packed_by,date(bm.packed_timestamp),bm.`invoice_+total`+bm.`invoice_-total` FROM bill_main bm WHERE date(bm.order_timestamp) BETWEEN '$from_date' AND '$to_date' AND bm.store='$store' AND date(bm.seen_timestamp)=date(bm.packed_timestamp) AND bm.`status`='5'";
			$result = mysqli_query($conn2, $query);
			while ($row = mysqli_fetch_array($result)) {
				$r2_odr_no[] = $row[0];
				$r2_pick_by[] = $row[1];
				$r2_pick_date[] = $row[2];
				$r2_pack_by[] = $row[3];
				$r2_pack_date[] = $row[4];
				$r2_amount[] = $row[5];
			}
			$r2_pick_uniq = array_unique($r2_pick_by);
			$r2_pick_uniq = array_values($r2_pick_uniq);
			$r2_pack_uniq = array_unique($r2_pack_by);
			$r2_pack_uniq = array_values($r2_pack_uniq);
		}
	}
}

// updated by nirmal 25_12_2023
function ringAlert()
{
	$store = $_COOKIE['store'];
	$id_list = '';
	include('config.php');
	$is_custom_invoice_no_active = isCustomInvoiceNoActive(1);
	if ($is_custom_invoice_no_active) {
		$query = "SELECT invoice_no FROM bill_main WHERE store='$store' AND `lock`>0 AND `status`!=0 ORDER BY CAST(invoice_no AS SIGNED) DESC LIMIT 20";
	} else {
		$query = "SELECT invoice_no FROM bill_main WHERE store='$store' AND `lock`>0 AND `status`!=0 ORDER BY invoice_no DESC LIMIT 20";
	}
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$id_list = ',' . $row[0];
	}
	return $id_list;
}

?>