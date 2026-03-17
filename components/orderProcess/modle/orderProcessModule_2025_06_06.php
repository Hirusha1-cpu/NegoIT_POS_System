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
	global $button, $button_action, $odr_bill_id, $odr_bi_desc, $odr_bi_qty, $odr_bi_price, $odr_total, $odr_ledc2, $odr_bi_drawer, $odr_bi_no_update,
	$pay_id, $cash_amount, $chque_amount, $bi_cust, $odr_date, $bi_salesman, $bi_seen_by, $bi_seen_date,
	$bi_seen_time, $bi_packed_by, $bi_packed_date, $bi_packed_time, $bi_shipped_by, $bi_shipped_date, $bi_shipped_time,
	$bi_deliverd_by, $bi_deliverd_date, $bi_deliverd_time, $cu_id, $bm_type, $bm_status, $tm_template, $bm_district, $cu_id, $odr_bi_order, $user_name;
	$invoice_no = $_REQUEST['id'];
	$user_name = $_COOKIE['user'];
	$cash_amount = $chque_amount = 0;
	$bm_status = $bm_store = '';
	$systemid = inf_systemid(1);
	$sub_system = $_COOKIE['sub_system'];
	include('config.php');

	if ($_GET['action'] == 'list_one_custodr' || $_GET['action'] == 'list_one') {
		$order_by_qry = 'inq.drawer_no, bi.id';
	} else {
		$order_by_qry = 'inv.description';
	}

	$query1 = "SELECT `id`,`username` FROM userprofile";
	$result1 = mysqli_query($conn2, $query1);
	while ($row1 = mysqli_fetch_array($result1)) {
		$salesman[$row1[0]] = $row1[1];
	}

	$query = "SELECT cu.name,bm.billed_by,bm.seen_by,date(bm.seen_timestamp),time(bm.seen_timestamp),bm.packed_by,
	date(bm.packed_timestamp),time(bm.packed_timestamp),bm.shipped_by,date(bm.shipped_timestamp),
	time(bm.shipped_timestamp),bm.deliverd_by,date(bm.deliverd_timestamp),time(bm.deliverd_timestamp),
	bm.`status`,cu.id,bm.`type`,bm.`order_timestamp`,bm.billed_district,cu.id,bm.mapped_inventory,bm.store
	FROM bill_main bm, cust cu WHERE bm.`cust`=cu.id AND  bm.invoice_no='$invoice_no'";
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

		$query2 = "SELECT bi.id,inv.description,bi.qty,bi.unit_price,inq.drawer_no,bi.no_update,bi.return_odr_replace
		FROM bill bi, inventory_items inv, inventory_qty inq
		WHERE inq.item=inv.id AND bi.item=inv.id AND bi.invoice_no='$invoice_no' AND inq.location='$mapped_inventory' ORDER BY $order_by_qry";
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

function generateInvoiceOdr($order_by)
{
	global $systemid, $tm_company, $tm_address, $tm_tel, $chq0_fullNo, $bill_id, $bi_desc, $bi_code, $bi_discount, $bi_qty, $bi_price, $total, $ledc2, $bi_drawer, $bi_type, $pay_id, $cash_amount, $chque_amount, $chq0_date, $bi_cust0, $bi_cust0_address, $bi_cust, $bi_salesman_id, $up_salesman, $bi_date, $bi_time, $cu_id, $cu_details, $cu_nickname, $up_mobile, $bm_status, $bm_quotation_no, $qm_warranty, $qm_terms, $qm_po, $bm_packed_by, $tm_shop, $bm_print_st, $bm_bocom_type, $bm_bocom, $bi_repair_sn, $up_packedby, $pay_type, $bi_return_odr, $return_odr, $bill_cross_tr, $decimal, $tax, $tax_amount, $cust_tax_no, $tax_added_value;

	$invoice_no = $_REQUEST['id'];
	$chq0_no = $chq0_bnk = $chq0_branch = $bm_packed_by = '';
	$cash_amount = $chque_amount = 0;
	$username_array = $usermobile_array = $sn_list = $bill_cross_tr = $bill_id = array();
	$return_odr = false;
	$username_array[''] = '';
	$usermobile_array[''] = '';

	$isMobile = isMobile();
	if ($isMobile) {
		include('config.php');
		$systemid = inf_systemid(1);
	} else {
		include('../../../../config.php');
		$systemid = inf_systemid(2);
	}

	$result = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='decimal'");
	$row = mysqli_fetch_assoc($result);
	$decimal = $row['value'];

	$result = mysqli_query($conn, "SELECT value FROM settings WHERE setting='tax'");
	$row = mysqli_fetch_assoc($result);
	$tax = $row['value'];

	$result = mysqli_query($conn, "SELECT value FROM settings WHERE setting='paper_size'");
	$row = mysqli_fetch_assoc($result);
	$paper_size = $row['value'];
	if ($paper_size == 'A4')
		$break_point = 3;
	if ($paper_size == 'A5')
		$break_point = 2;
	if ($isMobile)
		$break_point = 1;

	$query = "SELECT id,username,mobile FROM userprofile";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$username_array[$row[0]] = $row[1];
		$usermobile_array[$row[0]] = $row[2];
	}

	$query = "SELECT bm.`type`,cu.name,cu.shop_address,bm.billed_by,bm.packed_by,date(bm.billed_timestamp),time(bm.billed_timestamp),bm.`store`,cu.id,cu.nic,cu.mobile,cu.`status`,bm.`status`,bm.mapped_inventory,bm.quotation_no,bm.packed_by,bm.print_st,bm.back_off_com_type,bm.back_off_comment,cu.nickname FROM bill_main bm, cust cu WHERE  bm.`cust`=cu.id AND bm.invoice_no='$invoice_no'";
	$row = mysqli_fetch_row(mysqli_query($conn, $query));

	$bi_type = $row[0];
	if ($row[11] == 2)
		$bi_cust = 'Customer : ' . $row[1] . '<br />NIC: ' . $row[9] . ' &nbsp;&nbsp; Mobile: ' . $row[10];
	else
		$bi_cust = 'Customer : ' . $row[1];
	$bi_cust0 = $row[1];
	$bi_cust0_address = $row[2];
	$bi_salesman_id = $row[3];
	$up_salesman = $username_array[$row[3]];
	$up_packedby = $username_array[$row[4]];
	$bi_date = $row[5];
	$bi_time = $row[6];
	$store = $row[7];
	$cu_id = $row[8];
	$cu_details = 'NIC        : ' . $row[9] . '&#13;Mobile  : ' . $row[10];
	$up_mobile = $usermobile_array[$row[3]];
	$bm_status = $row[12];
	$bm_mapped_inventory = $row[13];
	$bm_quotation_no = $row[14];
	$bm_packed_by0 = $row[15];
	$bm_print_st = $row[16];
	$bm_bocom_type = $row[17];
	$bm_bocom = $row[18];
	$cu_nickname = $row[19];

	if (($bi_type == 1) || ($bi_type == 4)) {
		$query = "SELECT bi.id,itm.description,bi.qty,bi.unit_price,itm.id,bi.`comment`,itm.`code`,bi.discount,itm.unic,bi.repair_model,bi.repair_sn,bi.return_odr_replace,bi.cross_trans,itq.drawer_no FROM bill bi, inventory_items itm, inventory_qty itq WHERE bi.item=itm.id AND itm.id=itq.item AND itq.location='$bm_mapped_inventory' AND bi.invoice_no='$invoice_no' ORDER BY $order_by";
	} else {
		$query = "SELECT bi.id,itm.description,bi.qty,bi.unit_price,itm.id,bi.`comment`,itm.`code`,bi.discount,itm.unic,bi.repair_model,bi.repair_sn,bi.return_odr_replace,bi.cross_trans FROM bill bi, inventory_items itm WHERE bi.item=itm.id AND bi.invoice_no='$invoice_no' ORDER BY bi.id";
	}
	// added by nirmal 27_07_2023
	$query2 = "SELECT `tax` FROM bill_main WHERE `invoice_no`='$invoice_no'";
	$row2 = mysqli_fetch_row(mysqli_query($conn2, $query2));
	$tax_amount = $row2[0];

	// added by nirmal 18_08_2023
	$query3 = "SELECT `tax_no` FROM cust WHERE `id`='$cu_id'";
	$row3 = mysqli_fetch_row(mysqli_query($conn2, $query3));
	$cust_tax_no = $row3[0];

	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$bill_id_tmp = $row[0];
		$bill_id[] = $row[0];
		if ($row[9] != '' || $row[10] != '')
			$bi_repair_sn = "Model- $row[9]<br />SN- $row[10]<br />";
		else
			$bi_repair_sn = "";
		if (($bi_type == 1) && ($row[8] == 1)) {
			$unic_sn = '';
			$k = 1;
			$sn_list = explode(",", $row[5]);
			for ($i = 0; $i < sizeof($sn_list); $i++) {
				if ($k == $break_point) {
					$break_unic = '<br />';
					$k = 0;
				} else {
					$break_unic = '&nbsp;&nbsp;';
				}
				$unic_sn = $unic_sn . '[' . $sn_list[$i] . ']' . $break_unic;
				$k++;
			}
			if ($unic_sn != '') {
				$bi_desc[] = $row[1] . '<br />' . $unic_sn;
			} else {
				$bi_desc[] = $row[1] . '<br /><br />';
			}
		} else if (($bi_type == 2) || ($bi_type == 3) || ($bi_type == 5))
			$bi_desc[] = '[' . $row[1] . ']<br />' . $row[5] . '<br />' . $bi_repair_sn;
		else if ((($bi_type == 1) || ($bi_type == 4)) && ($row[8] == 0))
			$bi_desc[] = $row[1] . '<br />';
		$bi_qty[] = $row[2];
		$bi_price[] = $row[3];
		if ($row[11] == 0)
			$total += (($row[2] * $row[3]) + $tax_amount);
		else
			$return_odr = true;
		$ledc2[] = str_repeat('_', (12 - strlen(number_format($row[2] * $row[3]))));
		$bi_code[] = $row[6];
		$bi_discount[] = $row[7];
		$bi_return_odr[] = $row[11];
		$bill_cross_tr[] = $row[12];
		$item_id = $row[4];
		if (($bi_type == 1) || ($bi_type == 4))
			$bi_drawer[] = $row[13];
		else
			$bi_drawer[] = '<br /><br /><br />';
	}

	$rep_comment = '';
	$query1 = "SELECT rc.`comment` FROM repair_comment rc WHERE rc.private_public='2' AND rc.bill_no='$invoice_no'";
	$result1 = mysqli_query($conn, $query1);
	while ($row1 = mysqli_fetch_array($result1)) {
		$rep_comment .= $row1[0];
	}
	$bm_bocom .= '<br />' . $rep_comment;

	$query1 = "SELECT id,payment_type,SUM(amount),chque_no,chque_bank,chque_branch,chque_date FROM payment WHERE bill_pay=1 AND invoice_no='$invoice_no' AND `status`=0 GROUP BY payment_type";
	$result1 = mysqli_query($conn, $query1);

	while ($row1 = mysqli_fetch_array($result1)) {
		$pay_id[] = $row1[0];
		$pay_type = $row1[1];
		if ($row1[1] == 1)
			$cash_amount = $row1[2];
		if ($row1[1] == 2)
			$chque_amount = $row1[2];
		if ($row1[1] == 3)
			$cash_amount = $row1[2];
		$chq0_no = $row1[3];
		$chq0_bnk = $row1[4];
		$chq0_branch = $row1[5];
		$chq0_date = $row1[6];
	}

	if ($chq0_bnk > 0) {
		$query2 = "SELECT bank_code FROM bank WHERE id=$chq0_bnk";
		$result2 = mysqli_query($conn, $query2);
		while ($row2 = mysqli_fetch_array($result2)) {
			$chq0_bnk = $row2[0];
		}
		$chq0_fullNo = '[ Cheque No: ' . $chq0_no . '-' . $chq0_bnk . '-' . $chq0_branch . ' ]';
	} else
		$chq0_fullNo = '';

	if ($bm_quotation_no != 0) {
		$result = mysqli_query($conn, "SELECT warranty,terms2,cust_po FROM quotation_main WHERE id='$bm_quotation_no'");
		$row = mysqli_fetch_assoc($result);
		$qm_warranty = $row['warranty'];
		$qm_terms = $row['terms2'];
		$qm_po = $row['cust_po'];
	}

	if ($bm_packed_by0 != '') {
		$result = mysqli_query($conn, "SELECT username FROM userprofile WHERE id='$bm_packed_by0'");
		$row = mysqli_fetch_assoc($result);
		$bm_packed_by = $row['username'];
	}

	$result = mysqli_query($conn, "SELECT name,shop_name,address,tel FROM stores WHERE id='$bm_mapped_inventory'");
	$row = mysqli_fetch_assoc($result);
	$tm_shop = $row['name'];
	$tm_company = $row['shop_name'];
	$tm_address = $row['address'];
	$tm_tel = $row['tel'];
}

function generalPrintOdr()
{
	global $print_time, $key_dev_name, $tm_web, $tm_email, $trn_no;
	$key_dev_name = '';

	$isMobile = isMobile();
	if ($isMobile)
		include('config.php');
	else
		include('../../../../config.php');

	$result = mysqli_query($conn, "SELECT value FROM settings WHERE setting='timezone'");
	$row = mysqli_fetch_assoc($result);
	$timezone = $row['value'];
	$print_time = date("Y-m-d H:i:s", time() + (60 * 60 * $timezone));

	if (isset($_COOKIE['rsaid'])) {
		$key = $_COOKIE['rsaid'];
		$result = mysqli_query($conn, "SELECT `name` FROM devices WHERE `key`='$key'");
		$row = mysqli_fetch_assoc($result);
		$key_dev_name = $row['name'];
	}

	$result = mysqli_query($conn, "SELECT value FROM settings WHERE setting='web'");
	$row = mysqli_fetch_assoc($result);
	$tm_web = $row['value'];

	$store = $_COOKIE['store'];
	$result = mysqli_query($conn, "SELECT `email` FROM stores WHERE `id`='$store'");
	// $result = mysqli_query($conn,"SELECT value FROM settings WHERE setting='email'");
	$row = mysqli_fetch_assoc($result);
	$tm_email = $row['email'];

	$result = mysqli_query($conn, "SELECT value FROM settings WHERE setting='trn_no'");
	$row = mysqli_fetch_assoc($result);
	$trn_no = $row['value'];
}

function generateReturnListOdr()
{
	global $rt_code, $rt_desc, $rt_qty, $rt_pending_code, $rt_pending_desc, $rt_pending_qty, $return_cr_bal, $removed_code, $removed_desc, $removed_qty;
	$invoice_no = $_GET['id'];
	$rt_code = $rt_desc = $rt_qty = $rt_pending_code = $removed_code = array();
	$isMobile = isMobile();
	if ($isMobile)
		include('config.php');
	else
		include('../../../../config.php');
	$query = "SELECT `cust` FROM bill_main WHERE invoice_no='$invoice_no'";
	$row = mysqli_fetch_row(mysqli_query($conn, $query));
	$rm_cust = $row[0];

	$query = "SELECT itm.code,itm.description,rt.qty FROM return_main rm, `return` rt, inventory_items itm WHERE rm.invoice_no=rt.invoice_no AND rt.replace_item=itm.id AND rm.`status`='2' AND rt.odr_packed='1' AND rt.odr_no='$invoice_no' ORDER BY itm.description";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		if ($row[0] != 'CREDIT RTN') {
			$rt_code[] = $row[0];
			$rt_desc[] = $row[1];
			$rt_qty[] = $row[2];
		}
	}
	$query = "SELECT itm.code,itm.description,rt.qty FROM return_main rm, `return` rt, inventory_items itm WHERE rm.invoice_no=rt.invoice_no AND rt.replace_item=itm.id AND rm.`status`='2' AND rt.odr_packed='0' AND rm.`cust`='$rm_cust' ORDER BY itm.description";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$rt_pending_code[] = $row[0];
		$rt_pending_desc[] = $row[1];
		$rt_pending_qty[] = $row[2];
	}
	$query = "SELECT itm.code,itm.description,rj.qty FROM return_remove_job rj, inventory_items itm WHERE rj.replace_item_rm=itm.id AND rj.odr_no='$invoice_no' ORDER BY itm.description";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$removed_code[] = $row[0];
		$removed_desc[] = $row[1];
		$removed_qty[] = $row[2];
	}
	$query = "SELECT SUM(py.amount) FROM return_remove_job rj, payment py WHERE rj.payment_inv=py.id AND rj.odr_no='$invoice_no'";
	$row = mysqli_fetch_row(mysqli_query($conn, $query));
	$return_cr_bal = -1 * $row[0];
	$query = "SELECT SUM(unit_price*qty) FROM bill WHERE return_odr_replace='1' AND invoice_no='$invoice_no'";
	$row = mysqli_fetch_row(mysqli_query($conn, $query));
	$return_cr_bal = $return_cr_bal - $row[0];

}

// update by nirmal 06_02_2024 changed itq.location=rm.store query to rm.mapped_inventory
function getUnpackedReturn($cu)
{
	global $rtn_inv, $rtn_id, $rtn_date, $rtn_by, $rtn_itm_code, $rtn_itm_desc, $rtn_qty, $dis_date, $rtn_st, $rtn_drawer;
	$rtn_id = array();
	include('config.php');

	$query = "SELECT DISTINCT rm.`invoice_no`,rt.`id`,date(rm.`return_date`),up.`username`,itm.`code`,itm.description,
	rt.`qty`,date(rt.`process_date`),rt.`status`,itq.`drawer_no`
	FROM return_main rm, `return` rt, inventory_items itm, inventory_qty itq, userprofile up
	WHERE rm.invoice_no=rt.invoice_no AND rt.replace_item=itm.id AND itm.id=itq.item AND itq.location=rm.mapped_inventory
	AND up.id=rm.return_by AND rt.odr_packed='0' AND rm.`cust`='$cu' AND rm.`status`='2' ORDER BY itq.drawer_no";
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
		if ($row[8] == 0) {
			$rtn_st[] = 'Pending';
		} else {
			$rtn_st[] = 'Processed';
		}
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
	$query = "SELECT itm.`description`, rt.`qty` FROM `return` rt, return_main rm, inventory_items itm
	WHERE rm.`invoice_no`=rt.`invoice_no` AND rt.`replace_item`=itm.`id` AND rm.`status`='2' AND rt.`odr_packed`='0' AND rt.`id`='$rtn_id'";
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
		$message = "Item was removed from return invoice";
		return true;
	} else {
		$message = "Error: Item could not be removed";
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
function returnPacked1()
{
	include('config.php');
	$id = $_GET['id'];
	$odr_no = $_GET['odr_no'];
	$user_id = $_COOKIE['user_id'];
	$time_now = timeNow();
	$itq_id = $itq_qty = $itn_id = $itn_qty = '';
	$on_place = true;

	try {
		// Check if database connection is available
		if (!$conn) {
			throw new Exception('Database connection failed');
		}

		// Begin transaction
		if (!mysqli_begin_transaction($conn)) {
			throw new Exception('Failed to begin transaction');
		}

		// Escape user input to prevent SQL injection
		$id = mysqli_real_escape_string($conn, $id);
		$odr_no = mysqli_real_escape_string($conn, $odr_no);
		$user_id = mysqli_real_escape_string($conn, $user_id);

		$query = "SELECT `cust` FROM bill_main WHERE invoice_no='$odr_no'";
		$result = mysqli_query($conn, $query);
		if (!$result) {
			throw new Exception('Database error: ' . mysqli_error($conn));
		}
		$row = mysqli_fetch_row($result);
		$bm_cust = $row[0];

		$query = "SELECT rm.`cust`, rt.odr_packed, rt.replace_item, rt.qty, rm.mapped_inventory
			  FROM return_main rm, `return` rt
			  WHERE rm.invoice_no = rt.invoice_no AND rt.id='$id'";
		$result = mysqli_query($conn, $query);
		if (!$result) {
			throw new Exception('Database error: ' . mysqli_error($conn));
		}
		$row = mysqli_fetch_row($result);
		$rm_cust = $row[0];
		$rt_packed = $row[1];
		$replace_item = $row[2];
		$rt_qty = $row[3];
		$store = $row[4];

		$result = mysqli_query($conn, "SELECT on_place_replace FROM stores WHERE id='$store'");
		if (!$result) {
			throw new Exception('Database error: ' . mysqli_error($conn));
		}
		$row = mysqli_fetch_assoc($result);
		$on_place_replace = $row['on_place_replace'];

		// Check if customers match and return item isn't packed
		if ($bm_cust != $rm_cust) {
			throw new Exception('error: bill cust and return cust not same');
		}

		if ($rt_packed != 0) {
			throw new Exception('error: this item already packed');
		}

		$query = "SELECT COALESCE(SUM(rt.`qty`),0) FROM `return` rt WHERE rt.odr_no='$odr_no' AND rt.`odr_packed` = 1 AND rt.`replace_item` = '$replace_item'";
		$result = mysqli_query($conn, $query);
		if (!$result) {
			throw new Exception('Database error: ' . mysqli_error($conn));
		}
		$row = mysqli_fetch_row($result);
		$total_packed = $row[0];

		// Check if replace_item exists in the bill and sum its quantity
		$query = "SELECT COALESCE(SUM(qty), 0) AS total_billed, COUNT(*) AS cnt FROM bill WHERE invoice_no = '$odr_no' AND item = '$replace_item' AND qty < 0";
		$result = mysqli_query($conn, $query);
		if (!$result) {
			throw new Exception('Database error: ' . mysqli_error($conn));
		}
		$row = mysqli_fetch_assoc($result);
		$total_billed = $row['total_billed'];
		$item_exists = $row['cnt'] > 0;

		if ($on_place_replace == 0) {
			if ($item_exists) {
				$query5 = "SELECT id,qty FROM inventory_qty WHERE item='$replace_item' AND `location`='$store'";
				$result5 = mysqli_query($conn, $query5);
				if (!$result5) {
					throw new Exception('Database error: ' . mysqli_error($conn));
				}
				$row5 = mysqli_fetch_row($result5);
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
					if (!$result6) {
						throw new Exception('Database error: ' . mysqli_error($conn));
					}
					print 'done';
				} else {
					throw new Exception('error: no enough qty or cashback invoices');
				}
			} else {
				$query = "SELECT id,qty FROM inventory_qty WHERE item='$replace_item' AND `location`='$store'";
				$result = mysqli_query($conn, $query);
				if (!$result) {
					throw new Exception('Database error: ' . mysqli_error($conn));
				}
				$row = mysqli_fetch_row($result);
				$itq_id = $row[0];
				$itq_qty = $row[1];

				if (($itq_qty >= $rt_qty) && ($rt_packed == 0)) {
					$query4 = "UPDATE `inventory_qty` SET `qty`=qty-$rt_qty WHERE `id`='$itq_id'";
					$result4 = mysqli_query($conn, $query4);
					if (!$result4) {
						$on_place = false;
						throw new Exception('Database error: ' . mysqli_error($conn));
					}
				} else {
					$on_place = false;
					throw new Exception('error: no enough qty');
				}

				if (($bm_cust == $rm_cust) && ($rt_packed == 0) && ($on_place)) {
					$query7 = "UPDATE `return` SET `odr_no`='$odr_no', `odr_packed`='1', `odr_packed_date`='$time_now', `odr_packed_by`='$user_id' WHERE id='$id'";
					$result7 = mysqli_query($conn, $query7);
					if (!$result7) {
						throw new Exception('Database error: ' . mysqli_error($conn));
					}
					print 'done';
				}
			}
		} else {
			throw new Exception('error: on place replace not activated');
		}
		// Commit transaction
		if (!mysqli_commit($conn)) {
			throw new Exception('Failed to commit transaction');
		}
	} catch (Exception $e) {
		// Rollback transaction
		mysqli_rollback($conn);
		// Return appropriate error message
		print $e->getMessage();
	}
}

// pending return item pack
function returnPacked()
{
	include('config.php');
	$id = $_GET['id'];
	$odr_no = $_GET['odr_no'];
	$user_id = $_COOKIE['user_id'];
	$time_now = timeNow();
	$itq_id = $itq_qty = $itn_id = $itn_qty = '';
	$on_place = true;

	try {
		// Check if database connection is available
		if (!$conn) {
			throw new Exception('Database connection failed');
		}

		// Begin transaction
		if (!mysqli_begin_transaction($conn)) {
			throw new Exception('Failed to begin transaction');
		}

		// Escape user input to prevent SQL injection
		$id = mysqli_real_escape_string($conn, $id);
		$odr_no = mysqli_real_escape_string($conn, $odr_no);
		$user_id = mysqli_real_escape_string($conn, $user_id);

		$query = "SELECT `cust` FROM bill_main WHERE invoice_no='$odr_no'";
		$result = mysqli_query($conn, $query);
		if (!$result) {
			throw new Exception('Database error: ' . mysqli_error($conn));
		}
		$row = mysqli_fetch_row($result);
		$bm_cust = $row[0];

		$query = "SELECT rm.`cust`, rt.odr_packed, rt.replace_item, rt.qty, rm.mapped_inventory
			  FROM return_main rm, `return` rt
			  WHERE rm.invoice_no = rt.invoice_no AND rt.id='$id'";
		$result = mysqli_query($conn, $query);
		if (!$result) {
			throw new Exception('Database error: ' . mysqli_error($conn));
		}
		$row = mysqli_fetch_row($result);
		$rm_cust = $row[0];
		$rt_packed = $row[1];
		$replace_item = $row[2];
		$rt_qty = $row[3];
		$store = $row[4];

		$result = mysqli_query($conn, "SELECT on_place_replace FROM stores WHERE id='$store'");
		if (!$result) {
			throw new Exception('Database error: ' . mysqli_error($conn));
		}
		$row = mysqli_fetch_assoc($result);
		$on_place_replace = $row['on_place_replace'];

		if ($on_place_replace != 0) {
			throw new Exception('error: on place replace not activated');
		}

		// Check if customers match and return item isn't packed
		if ($bm_cust != $rm_cust) {
			throw new Exception('error: bill cust and return cust not same');
		}

		if ($rt_packed != 0) {
			throw new Exception('error: this item already packed');
		}

		// get stock for replace item
		$query = "SELECT id,qty FROM inventory_qty WHERE item='$replace_item' AND `location`='$store'";
		$result = mysqli_query($conn, $query);
		if (!$result) {
			throw new Exception('Database error: ' . mysqli_error($conn));
		}
		$row = mysqli_fetch_row($result);
		$itq_id = $row[0];
		$itq_qty = $row[1];

		$query = "SELECT COALESCE(SUM(qty), 0) AS total_billed, COUNT(*) AS cnt FROM bill WHERE invoice_no = '$odr_no' AND item = '$replace_item' AND qty < 0";
		$result = mysqli_query($conn, $query);
		if (!$result) {
			throw new Exception('Database error: ' . mysqli_error($conn));
		}
		$row = mysqli_fetch_assoc($result);
		$bill_cash_back_balance = abs($row['total_billed']);

		// pending return pack - inventory qty
		$return_remaining_balance = $rt_qty - $itq_qty;

		if ($return_remaining_balance > 0) {
			if (($return_remaining_balance - $bill_cash_back_balance) > 0) {
				throw new Exception('error: no enough qty');
			} else {
				// update as return pack
				$pack_query = "UPDATE `return` SET `odr_no`='$odr_no', `odr_packed`='1', `odr_packed_date`='$time_now', `odr_packed_by`='$user_id', `cash_back_qty` = '$return_remaining_balance' WHERE id='$id'";
				$pack_result = mysqli_query($conn, $pack_query);
				if (!$pack_result) {
					throw new Exception('Database error: ' . mysqli_error($conn));
				}
				// update inventory qty to 0
				$inventory_query = "UPDATE `inventory_qty` SET `qty`=0 WHERE `id`='$itq_id'";
				$inventory_result = mysqli_query($conn, $inventory_query);
				if (!$inventory_result) {
					throw new Exception('Database error: ' . mysqli_error($conn));
				}
				print 'done';
			}
		} else {
			// pack allow
			if (($itq_qty >= $rt_qty) && ($rt_packed == 0)) {
				$inventory_query = "UPDATE `inventory_qty` SET `qty`=qty-$rt_qty WHERE `id`='$itq_id'";
				$inventory_result = mysqli_query($conn, $inventory_query);
				if (!$inventory_result) {
					throw new Exception('Database error: ' . mysqli_error($conn));
				}
			} else {
				throw new Exception('error: no enough qty');
			}

			$pack_query = "UPDATE `return` SET `odr_no`='$odr_no', `odr_packed`='1', `odr_packed_date`='$time_now', `odr_packed_by`='$user_id' WHERE id='$id'";
			$pack_result = mysqli_query($conn, $pack_query);
			if (!$pack_result) {
				throw new Exception('Database error: ' . mysqli_error($conn));
			}
			print 'done';
		}
		// Commit transaction
		if (!mysqli_commit($conn)) {
			throw new Exception('Failed to commit transaction');
		}
	} catch (Exception $e) {
		// Rollback transaction
		mysqli_rollback($conn);
		// Return appropriate error message
		print $e->getMessage();
	} finally {
		// Close the database connection
		if (isset($conn)) {
			mysqli_close($conn);
		}
	}
}

// update by nirmal 07_02_2024 (get mapped store instead of store)
// updated by nirmal 27_01_2025 (if inventory 0 qty addition skip)
// remove item from packed status to pending
function removeReturnPacked()
{
	include('config.php');
	$id = $_GET['id'];
	$odr_no = $_GET['odr_no'];
	$user_id = $_COOKIE['user_id'];
	$time_now = timeNow();
	$itq_id = $itq_qty = $itn_id = $itn_qty = '';
	$on_place = true;

	try {
		if (!$conn) {
			throw new Exception('Database connection failed');
		}

		// Begin transaction
		if (!mysqli_begin_transaction($conn)) {
			throw new Exception('Failed to begin transaction');
		}

		// Escape user inputs
		$id = mysqli_real_escape_string($conn, $id);
		$odr_no = mysqli_real_escape_string($conn, $odr_no);
		$user_id = mysqli_real_escape_string($conn, $user_id);

		// Get bill main details: customer and lock status
		$query = "SELECT `cust`,`lock` FROM bill_main WHERE invoice_no='$odr_no'";
		$result = mysqli_query($conn, $query);
		if (!$result) {
			throw new Exception('Database error: ' . mysqli_error($conn));
		}
		$row = mysqli_fetch_row($result);
		$bm_cust = $row[0];
		$bm_lock = $row[1];

		// Get return details along with mapped inventory
		$query = "SELECT rm.`cust`, rt.odr_packed, rt.replace_item, rt.qty, rm.store, rm.mapped_inventory
              FROM return_main rm, `return` rt
              WHERE rm.invoice_no = rt.invoice_no AND rt.id='$id'";
		$result = mysqli_query($conn, $query);
		if (!$result) {
			throw new Exception('Database error: ' . mysqli_error($conn));
		}
		$row = mysqli_fetch_row($result);
		$rm_cust = $row[0];
		$rt_packed = $row[1];
		$replace_item = $row[2];
		$rt_qty = $row[3];
		$store = $row[4];
		$mapped_inventory = $row[5];

		// Validate customer match and ensure the return item is packed
		if ($bm_cust != $rm_cust) {
			throw new Exception('error: bill cust and return cust not same');
		}
		if ($rt_packed != 1) {
			throw new Exception('error: this item not packed');
		}

		// Get on_place_replace flag from the stores table via mapped_inventory
		$query = "SELECT on_place_replace FROM stores WHERE id='$mapped_inventory'";
		$result = mysqli_query($conn, $query);
		if (!$result) {
			throw new Exception('Database error: ' . mysqli_error($conn));
		}
		$row = mysqli_fetch_assoc($result);
		$on_place_replace = $row['on_place_replace'];

		if ($on_place_replace != 0) {
			throw new Exception('error: on place replace not activated');
		}

		// Get the total returned cash back quantity for packed returns
		$query = "SELECT qty, cash_back_qty FROM `return` WHERE id='$id'";
		$result = mysqli_query($conn, $query);
		if (!$result) {
			throw new Exception('Database error: ' . mysqli_error($conn));
		}
		$row = mysqli_fetch_row($result);
		$return_qty = $row[0];
		$return_cash_back_qty = $row[1];

		$remaining_qty = $return_qty - $return_cash_back_qty;

		// Proceed only if bill_main is partially locked (lock = 2)
		if ($bm_lock == 2) {
			// Get inventory record for the replace item at the mapped inventory location
			$query = "SELECT id FROM inventory_qty WHERE item='$replace_item' AND `location`='$mapped_inventory'";
			$result = mysqli_query($conn, $query);
			if (!$result) {
				throw new Exception('Database error: ' . mysqli_error($conn));
			}
			$row = mysqli_fetch_row($result);
			$itq_id = $row[0];

			$query = "UPDATE `inventory_qty` SET `qty` = `qty` + $remaining_qty WHERE `id` = '$itq_id'";
			$result = mysqli_query($conn, $query);
			if (!$result) {
				throw new Exception('error: updating inventory quantity failed');
			}

			// Update the return entry to mark it as not packed
			$query = "UPDATE `return` SET `odr_no` = NULL, `odr_packed` = '0', `odr_packed_date` = NULL, `odr_packed_by` = NULL, `cash_back_qty` = NULL
                WHERE id='$id'";
			$result = mysqli_query($conn, $query);
			if (!$result) {
				throw new Exception('error: updating return record failed');
			}
			print 'done';

		} else {
			throw new Exception('error: bill not partially locked');
		}

		// Commit transaction
		if (!mysqli_commit($conn)) {
			throw new Exception('Failed to commit transaction');
		}
	} catch (Exception $e) {
		mysqli_rollback($conn);
		print $e->getMessage();
	} finally {
		if (isset($conn)) {
			mysqli_close($conn);
		}
	}
}

function removeReturnPacked1()
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

function calculateDiscountOdr($cust, $itemid, $price, $discount_value, $discount_type)
{
	include('config.php');
	$query = "SELECT `status` FROM cust WHERE id='$cust'";
	$row = mysqli_fetch_row(mysqli_query($conn, $query));
	$cust_type = $row[0];

	$query = "SELECT min_w_rate,max_w_rate,max_r_rate,pr_sr FROM inventory_items WHERE id='$itemid'";
	$row = mysqli_fetch_row(mysqli_query($conn, $query));
	$min_w_rate = $row[0];
	$max_w_rate = $row[1];
	$max_r_rate = $row[2];
	$pr_sr = $row[3];

	if ($pr_sr == 1) {
		if ($cust_type == 1) {
			if ($discount_type == 'percentage') {
				if ($max_w_rate >= $discount_value) {
					if ($min_w_rate <= $discount_value) {
						$discount = round(($price / 100) * $discount_value);
					} else {
						$discount = round(($price / 100) * $min_w_rate);
					}
				} else {
					$discount = 'error';
				}
			} else {
				if ((($price * $max_w_rate) / 100) >= $discount_value) {
					if ((($price * $min_w_rate) / 100) <= $discount_value) {
						$discount = $discount_value;
					} else {
						$discount = round((($price * $min_w_rate) / 100));
					}
				} else {
					$discount = 'error';
				}
			}
		} else
			if ($cust_type == 2) {
				if ($discount_type == 'percentage') {
					if ($max_r_rate >= $discount_value)
						$discount = round(($price / 100) * $discount_value);
					else
						$discount = 'error';
				} else {
					if ((($price * $max_r_rate) / 100) >= $discount_value)
						$discount = $discount_value;
					else
						$discount = 'error';
				}

			}
	} else {
		if ($discount_type == 'percentage') {
			$discount = round(($price / 100) * $discount_value);
		} else {
			$discount = $discount_value;
		}
	}
	return $discount;
}

// add item to invoice
function apendBillOdr($case, $systemid, $storecrossitm, $storecrossst)
{
	global $message, $invoice_no, $salesman, $cust;
	$invoice_no = $_REQUEST['id'];
	$itemid = $_REQUEST['itemid'];
	$qty = $qty0 = $_REQUEST['qty'];
	$price0 = $_REQUEST['price'];
	$item_type = "NULL";
	$unic_cal = unicCal();

	if (strpos($price0, 'r_') === 0) {
		$price0 = substr($price0, 2);
		$item_type = "'1'";
	} elseif (strpos($price0, 'w_') === 0) {
		$price0 = substr($price0, 2);
		$item_type = "'2'";
	}
	$discount0 = $_REQUEST['discount'];
	$salesman = $_REQUEST['salesman'];
	$sys_user = $_COOKIE['user_id'];
	$comment0 = $_REQUEST['comment'];
	$comment = preg_replace("/[^A-Za-z0-9+-,. ]/", '', $comment0);
	$cust = $_REQUEST['cust'];
	$store = $_COOKIE['store'];
	$unic_item = $_REQUEST['unic_item'];
	$cust_odr = $_REQUEST['cust_odr'];
	$discount_type = $_REQUEST['discount_type'];

	if (isset($_REQUEST['repair_sn'])) {
		$repair_sn = $_REQUEST['repair_sn'];
	} else {
		$repair_sn = '';
	}
	if (isset($_REQUEST['repair_model'])) {
		$repair_model = $_REQUEST['repair_model'];
	} else {
		$repair_model = '';
	}
	if (isset($_REQUEST['return'])) {
		$return = $_REQUEST['return'];
	} else {
		$return = 0;
	}
	$sub_system = $_COOKIE['sub_system'];
	$time_now = timeNow();
	$force_permit = false;
	$bill_new_status = $technicient = '';
	$discount = calculateDiscountOdr($cust, $itemid, $price0, $discount0, $discount_type);
	if ($discount == '') {
		$discount = 0;
	}
	$sn_list = $bm_cust = '';
	$cross_bill = $qty_cannot_update = false;
	$proceed = true;
	$out = true;
	include('config.php');

	if ($out) {
		// Query to fetch existing items in the invoice
		$query = "SELECT `status`,`type`,`cust` FROM bill_main WHERE  invoice_no='$invoice_no'";
		$result = mysqli_query($conn2, $query);

		// Check for the first item in the invoice
		if ($result && mysqli_num_rows($result) > 0) {
			$row = mysqli_fetch_array($result, MYSQLI_ASSOC); // Fetch as associative array
			if (isset($row['status']) && isset($row['type']) && isset($row['cust'])) {
				$bm_cust = $row['cust'];
				if (($row['status'] == 2) && ($row['type'] = 4)) {
					if ($qty < 0) {
						$qty_cannot_update = true;
					}
				}
			}
		}
	}

	if ($qty_cannot_update) {
		$query1 = "SELECT SUM(bi.qty) FROM bill_main bm, bill bi WHERE bm.invoice_no=bi.invoice_no AND bm.`cust`='$bm_cust' AND bi.item='$itemid' AND bm.`status`!='0' AND bm.`lock`='1'";
		$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
		if (!(($row1[0] !== null) && ($row1[0] >= -$qty))) {
			$out = false;
			$$message = 'Error: This customer did not purchase this item or purchased less quantity';
		}
	}


	if ($out) {
		if ($storecrossitm != 0) {
			$val = storeCrossCheck($sub_system, $systemid, $itemid);
			$vals = explode(",", $val);
			if ($vals[2] >= $qty)
				$cross_bill = true;
		}

		if (is_numeric($discount)) {
			$price = round($price0 - $discount, 2);
			if ($qty > 0) {
				$itu_status1 = 0;
				$itu_status2 = 1;
			} else {
				$itu_status1 = 1;
				$itu_status2 = 0;
			}
			$query = "SELECT COUNT(invoice_no) as `count` FROM bill_main WHERE sys_user='$sys_user' AND billed_by='$salesman' AND `cust`='$cust' AND invoice_no='$invoice_no'";
			$result = mysqli_query($conn, $query);
			$row = mysqli_fetch_assoc($result);
			$bm_exist = $row['count'];
			if ($bm_exist == 0) {
				if ($case == 1) {
					if (newBill($cust, $cust_odr, $salesman, 0)) {
						if (isCustomInvoiceNoActive(1)) {
							$query = "SELECT invoice_no FROM bill_main ORDER BY order_timestamp DESC LIMIT 1";
							$row1 = mysqli_fetch_row(mysqli_query($conn, $query));
							$invoice_no = $row1[0];
						} else {
							$query = "SELECT MAX(invoice_no) FROM bill_main";
							$row1 = mysqli_fetch_row(mysqli_query($conn, $query));
							$invoice_no = $row1[0];
						}
					}
				}
			}

			if (isCustomInvoiceNoActive(1)) {
				if ($invoice_no === 0) {
					$invoice_no = generateBillNumber($store, 'bill_main');
				}
			} else {
				if ($invoice_no == 0) {
					$query = "SELECT MAX(invoice_no) FROM bill_main";
					$result = mysqli_query($conn, $query);
					while ($row = mysqli_fetch_array($result)) {
						$invoice_no = $row[0] + 1;
					}
				}
			}

			$result = mysqli_query($conn, "SELECT mapped_inventory FROM bill_main WHERE invoice_no='$invoice_no'");
			$row = mysqli_fetch_assoc($result);
			$store = $row['mapped_inventory'];

			$result = mysqli_query($conn, "SELECT `lock`,`status` FROM bill_main WHERE invoice_no='$invoice_no'");
			$row = mysqli_fetch_assoc($result);
			$bm_lock = $row['lock'];
			$bm_status = $row['status'];

			$result = mysqli_query($conn, "SELECT `pr_sr`,`default_cost` FROM inventory_items WHERE id='$itemid'");
			$row = mysqli_fetch_assoc($result);
			$pr_sr = $row['pr_sr'];
			$d_cost = $row['default_cost'];

			if ($cust_odr == 'yes') {
				if ($pr_sr == 1) {
					$type = 4;
					$bill_new_status = '';
				}
				if ($pr_sr == 2) {
					$type = 5;
					$bill_new_status = '';
				}
				if ($pr_sr == 3) {
					$type = 3;
					$bill_new_status = ',`status`=1';
				}
				if ($bm_status == 2)
					$force_permit = true;
			} else {
				if ($pr_sr == 1) {
					$type = 1;
					$bill_new_status = '';
				}
				if ($pr_sr == 2) {
					$type = 2;
					$bill_new_status = '';
				}
				if ($pr_sr == 3) {
					$type = 3;
					$bill_new_status = ',`status`=1';
				}
			}
			$query = "UPDATE `bill_main` SET `type`='$type' $bill_new_status WHERE `invoice_no`='$invoice_no'";
			$result = mysqli_query($conn, $query);

			$query = "SELECT ivq.qty,ivq.c_price,ivq.w_price,ivq.r_price,ivq.id FROM inventory_qty ivq WHERE ivq.location='$store' AND ivq.item='$itemid'";
			$row = mysqli_fetch_row(mysqli_query($conn, $query));
			$old_qty = $row[0];
			$cost = $row[1];
			$wholesale_price = $row[2];
			$retail_price = $row[3];
			$ivq_id = $row[4];

			if (($pr_sr == 3) && ($systemid == 4)) {
				if ($_REQUEST['technicient'] != '')
					$technicient = $_REQUEST['technicient'];
				else
					$technicient = getNextTechnicient();
				if ($technicient != '') {
					$query = "UPDATE `bill_main` SET `packed_by`='$technicient',`packed_timestamp`='$time_now',`status`='3' WHERE `invoice_no`='$invoice_no'";
					$result = mysqli_query($conn, $query);
				}
			}

			if ($pr_sr == 2 || $pr_sr == 3) {
				$cost = $d_cost;
				$wholesale_price = 0;
				$retail_price = 0;
			}
			// 2025_01_27
			if ($qty_cannot_update) {
				$new_qty = $old_qty;
			} else {
				$new_qty = $old_qty - $qty;
			}
			if ($proceed) {
				if (($ivq_id != '') || ($pr_sr == '2') || ($pr_sr == '3')) {
					$proceed = true;
				} else {
					$proceed = false;
					$msg = 'Error: Store mismatch';
				}
			}

			if ($proceed) {
				if ($bm_lock == 0) {
					$proceed = true;
				} else {
					$proceed = false;
					$msg = 'You cannot add items to a finalized invoice';
				}
			}
			if ($proceed) {
				if (mismatch($ivq_id)) {
					$proceed = true;
				} else {
					$proceed = false;
					$msg = 'Error 107. Please contact support!';
				}
			}
			if ($proceed || $force_permit) {
				if ((($new_qty >= 0) || ($cross_bill)) || ($pr_sr == 2 || $pr_sr == 3)) {
					$query = "INSERT INTO `bill` (`invoice_no`,`item`,`qty`,`unit_price`,`cost`,`discount`,`w_price`,`r_price`,`comment`,`repair_model`,`repair_sn`,`date`,`return_odr_replace`,`cross_trans`,`item_type`) VALUES ('$invoice_no','$itemid','$qty','$price','$cost','$discount','$wholesale_price','$retail_price','$comment','$repair_model','$repair_sn','$time_now','$return','$storecrossst', $item_type)";
					$result = mysqli_query($conn, $query);
					$lastitem = mysqli_insert_id($conn);
					if (($unic_item != '0') && ($unic_item != '')) {
						$qty = 0;
						for ($i = 1; $i <= 10; $i++) {
							if ($_POST["unic_item$i"] != '') {
								$unic_item0 = $_POST["unic_item$i"];
								$result = mysqli_query($conn, "SELECT count(id) as `count` FROM inventory_unic_item WHERE sn='$unic_item0' AND itq_id='$ivq_id' AND `status`='$itu_status1'");
								$row = mysqli_fetch_assoc($result);
								if ($row['count'] == 1) {
									$sn_list = $sn_list . ',' . $unic_item0;
									$query = "UPDATE `inventory_unic_item` SET `status`='$itu_status2',`invoice_no`='$invoice_no',`bill_id`='$lastitem' WHERE `sn`='$unic_item0' AND `status`='$itu_status1'";
									$result = mysqli_query($conn, $query);
									if ($result) {
										if ($qty0 > 0)
											$qty++;
										else
											$qty--;
									}
								}
							}
						}

						$sn_list = ltrim($sn_list, ',');
						$query = "UPDATE `bill` SET `qty`='$qty',`comment`='$sn_list',`no_update`='999999999' WHERE `id`='$lastitem'";
						$result = mysqli_query($conn, $query);
						// 2025_01_27
						if ($qty_cannot_update) {
							$new_qty = $old_qty;
						} else {
							$new_qty = $old_qty - $qty;
						}
						if ($qty != 0) {
							if ($result) {
								$query = "UPDATE `inventory_qty` SET `qty`='$new_qty' WHERE `id`=$ivq_id";
								mysqli_query($conn, $query);
							}
						} else {
							$query = "DELETE FROM `bill` WHERE `id`='$lastitem'";
							mysqli_query($conn, $query);
						}
					} else {
						if ($result) {
							if ($pr_sr == 1) {
								$query = "UPDATE `inventory_qty` SET `qty`='$new_qty' WHERE `id`=$ivq_id";
								mysqli_query($conn, $query);
							}
						}
					}

					if ($pr_sr == 1) {
						$result2 = mysqli_query($conn, "SELECT count(id) as `count` FROM bill WHERE item='$itemid' AND invoice_no='$invoice_no'");
						$row2 = mysqli_fetch_assoc($result2);
						$duplicate_item = $row2['count'];
						if ($duplicate_item > 1) {
							$query = "UPDATE `bill` SET `no_update`='999999999' WHERE item='$itemid' AND invoice_no='$invoice_no' AND no_update='0'";
							$result = mysqli_query($conn, $query);
						}
					}

					if ($result) {
						if ($qty != 0) {
							processInventoryNewOdr($itemid, $lastitem, $store, 'bill');
							if (($pr_sr == 1) && ($itemid == $storecrossitm))
								crossTransferOdr($invoice_no, $storecrossitm, $storecrossst, $qty);
							$message = 'Item was Added to the Invoice!';
							return true;
						} else {
							$message = 'Item could not be Added1!';
							return false;
						}
					} else {
						$message = 'Item could not be Added2!';
						return false;
					}
				} else {
					$message = 'Error: Insufficient quantity!';
					return false;
				}
			} else {
				$message = $msg;
				return false;
			}
		} else {
			$message = 'Error : Invalid discount';
			return false;
		}
	}
}

// remove item from invoice
function removeBillitemOdr()
{
	global $message, $salesman, $cust, $invoice_no;
	$itemid = $_REQUEST['id'];
	$salesman = $_REQUEST['s'];
	$cust = $_REQUEST['cust'];
	$user_id = $_COOKIE['user_id'];
	$output = $result7 = $force_permit = false;
	$sn_remove = '';
	include('config.php');

	$result = mysqli_query($conn, "SELECT bm.mapped_inventory FROM bill bi, bill_main bm WHERE bm.invoice_no=bi.invoice_no AND bi.id='$itemid'");
	$row = mysqli_fetch_assoc($result);
	$store = $row['mapped_inventory'];

	$query = "SELECT itm.pr_sr,bi.invoice_no FROM inventory_items itm, bill bi WHERE itm.id=bi.item AND bi.id='$itemid'";
	$row = mysqli_fetch_row(mysqli_query($conn, $query));
	$pr_sr = $row[0];
	$invoice_no = $row[1];

	$query = "SELECT ivq.id,ivq.qty,bi.qty,(bi.unit_price+bi.discount),bi.cost,ivq.w_price,ivq.r_price,ivq.c_price,ivq.item,bi.no_update,
	bm.`lock`,bm.`type`,bm.`status`,bm.invoice_no
	FROM inventory_qty ivq, bill bi, bill_main bm
	 WHERE bm.invoice_no=bi.invoice_no AND ivq.item=bi.item AND ivq.location='$store' AND bi.id='$itemid'";
	$row = mysqli_fetch_row(mysqli_query($conn, $query));
	$ivq_id = $row[0];
	$ivq_qty = $row[1];
	$bi_qty = $row[2];
	$bi_price = $row[3];
	$bi_cprice = $row[4];
	$ivq_wprice = $row[5];
	$ivq_rprice = $row[6];
	$ivq_cprice = $row[7];
	$ivq_item = $row[8];
	$bi_noupdate = $row[9];
	$bm_lock = $row[10];
	$bm_type = $row[11];
	$bm_status = $row[12];
	$invoice_no = $row[13];

	$new_ivq_qty = $ivq_qty + $bi_qty;

	$query = "SELECT tm.`status`,tm.`user`,tm.from_store,tr.id FROM transfer_main tm, transfer tr WHERE tm.gtn_no=tr.gtn_no AND tm.invoice_no='$invoice_no' AND tr.item='$ivq_item'";
	$row = mysqli_fetch_row(mysqli_query($conn, $query));
	$tr_status = $row[0];
	$tr_user = $row[1];
	$tr_fromstore = $row[2];
	$tr_id = $row[3];
	if ($tr_status != '') {
		if (($tr_status == '4') && ($tr_user == $user_id)) {
			$query = "SELECT ivq.id,ivq.qty FROM inventory_qty ivq WHERE ivq.location='$tr_fromstore' AND ivq.item='$ivq_item'";
			$row = mysqli_fetch_row(mysqli_query($conn, $query));
			$ivq_id2 = $row[0];
			$ivq_qty2 = $row[1];
			$new_ivq_qty2 = $ivq_qty2 + $bi_qty;
			$query1 = "DELETE FROM `transfer` WHERE `id`='$tr_id'";
			$result1 = mysqli_query($conn, $query1);
			if (!$result1) {
				$out = false;
				$msg = 'Related GTN Quantity cannot be modified [112]';
			} else {
				$query = "UPDATE `inventory_qty` SET `qty`='$new_ivq_qty' WHERE `id`='$ivq_id'";
				$result2 = mysqli_query($conn, $query);
				if (!$result2) {
					$out = false;
					$msg = 'Related GTN Quantity cannot be modified [113]';
				} else {
					$out = true;
					$ivq_id = $ivq_id2;
					$ivq_qty = $ivq_qty2;
					$new_ivq_qty = $new_ivq_qty2;
				}
			}
		} else {
			$out = false;
			$msg = 'Please contract related GTN owner to Modify QTY!';
		}
	} else {
		$out = true;
	}

	if ($bi_qty <= 0) {
		$bi_qty = 0;
	}
	$new_ivq_qty = $ivq_qty + $bi_qty;

	$query = "SELECT cash_back_qty FROM `return` WHERE replace_item='$ivq_item' AND odr_no='$invoice_no' AND odr_packed='1'";
	$row = mysqli_fetch_row(mysqli_query($conn, $query));
	$return_cash_back_qty = $row[0];

	if ($return_cash_back_qty > 0) {
		$out = false;
	}

	if (($bm_type == 4 || $bm_type == 5) && ($bm_status == 2) && ($out)) {
		$force_permit = true;
	}
	if (($bm_lock == 0) || ($force_permit)) {
		if ($pr_sr == 1) {
			if (($bi_noupdate == 0) || ($bi_noupdate == 999999999)) {
				$query = "UPDATE `inventory_qty` SET `qty`='$new_ivq_qty' WHERE `id`=$ivq_id";
				$result7 = mysqli_query($conn, $query);
			} else {
				$query = "INSERT INTO `inventory_new` (`item`,`w_price`,`r_price`,`c_price`,`qty`,`store`) VALUES ('$ivq_item','$ivq_wprice','$ivq_rprice','$ivq_cprice','$ivq_qty','$store')";
				$result2 = mysqli_query($conn, $query);
				$query = "SELECT w_price,r_price,c_price FROM inventory_temp WHERE id='$bi_noupdate'";
				$result = mysqli_query($conn, $query);
				while ($row = mysqli_fetch_array($result)) {
					$tt_wprice = $row[0];
					$tt_rprice = $row[1];
					$tt_cprice = $row[2];
				}
				$query = "UPDATE `inventory_qty` SET `w_price`='$tt_wprice',`r_price`='$tt_rprice',`c_price`='$tt_cprice',`qty`='$bi_qty' WHERE `id`=$ivq_id";
				$result7 = mysqli_query($conn, $query);
			}
		}
		if (($result7) || ($pr_sr == 2) || ($pr_sr == 3)) {
			$query = "SELECT qty,`comment` FROM bill WHERE id='$itemid'";
			$row = mysqli_fetch_row(mysqli_query($conn, $query));
			$bill_qty = $row[0];
			$sn_remove = $row[1];

			$query6 = "DELETE FROM `bill` WHERE `id` = '$itemid'";
			$result6 = mysqli_query($conn, $query6);
			if ((($pr_sr == 2) || ($pr_sr == 3)) & ($result6))
				$output = true;
		}

		if ($pr_sr == 1) {
			if ($result6) {
				$output = true;
				$old_bill_id = $old_invo_no = '';
				if ($bill_qty < 0) {
					$query = "SELECT id,invoice_no FROM bill WHERE qty>0 AND `comment` LIKE '%$sn_remove%'";
					$row = mysqli_fetch_row(mysqli_query($conn, $query));
					$old_bill_id = $row[0];
					$old_invo_no = $row[1];
					$query2 = "UPDATE `inventory_unic_item` SET `invoice_no`='$old_invo_no',`bill_id`='$old_bill_id',`status`='1' WHERE `bill_id`='$itemid'";
					$result2 = mysqli_query($conn, $query2);
				} else {
					$query2 = "UPDATE `inventory_unic_item` SET `invoice_no`='0',`bill_id`='0',`status`='0' WHERE `bill_id`='$itemid'";
					$result2 = mysqli_query($conn, $query2);
				}
			}
		}
		if ($output) {
			$message = 'Item was Removed from Invoice!';
			return true;

		} else {
			$message = 'Item could not be Removed!';
			return false;
		}
	} else {
		if (!$out) {
			$message = 'Item cannot be remove, Please remove the return item(s) packed for this item.';
			return false;
		} else {
			$message = 'You cannot remove items from a finalize invoice';
			return false;
		}
	}
}

function updateBillitemOdr()
{
	global $message, $salesman, $cust, $invoice_no;
	$itemid = $_REQUEST['id'];
	$qty = $_REQUEST['qty'];
	$salesman = $_REQUEST['s'];
	$cust = $_REQUEST['cust'];
	$user_id = $_COOKIE['user_id'];
	$out = $qty_cannot_update = false;
	$out2 = true;
	$check_qty = '';
	$msg = 'Item could not be updated!';
	include('config.php');

	$result = mysqli_query($conn, "SELECT bm.mapped_inventory,bm.`cust` FROM bill bi, bill_main bm WHERE bm.invoice_no=bi.invoice_no AND bi.id='$itemid'");
	$row = mysqli_fetch_assoc($result);
	$st_qry = $row['mapped_inventory'];
	$bm_cust = $row['cust'];

	$query = "SELECT itm.pr_sr,bi.invoice_no FROM inventory_items itm, bill bi WHERE itm.id=bi.item AND bi.id='$itemid'";
	$row = mysqli_fetch_row(mysqli_query($conn, $query));
	$pr_sr = $row[0];
	$invoice_no = $row[1];

	$query = "SELECT ivq.id,ivq.qty,bi.qty,ivq.item FROM inventory_qty ivq, bill_main bm,bill bi
	WHERE bm.invoice_no=bi.invoice_no AND ivq.item=bi.item AND ivq.location=$st_qry AND bi.id='$itemid'";
	$row = mysqli_fetch_row(mysqli_query($conn, $query));
	$ivq_id = $row[0];
	$ivq_qty = $row[1];
	$bi_qty = $row[2];
	$ivq_item = $row[3];

	$new_ivq_qty = $ivq_qty + $bi_qty - $qty;

	$query = "SELECT tm.`status`,tm.`user`,tm.from_store,tr.id FROM transfer_main tm, transfer tr
	WHERE tm.gtn_no=tr.gtn_no AND tm.invoice_no='$invoice_no' AND tr.item='$ivq_item'";
	$row = mysqli_fetch_row(mysqli_query($conn, $query));
	$tr_status = isset($row[0]) ? $row[0] : '';
	$tr_user = isset($row[1]) ? $row[1] : '';
	$tr_fromstore = isset($row[2]) ? $row[2] : '';
	$tr_id = isset($row[3]) ? $row[3] : '';


	if ($tr_status != '') {
		if (($tr_status == '4') && ($tr_user == $user_id)) {
			$query = "SELECT ivq.id,ivq.qty FROM inventory_qty ivq WHERE ivq.location='$tr_fromstore' AND ivq.item='$ivq_item'";
			$row = mysqli_fetch_row(mysqli_query($conn, $query));
			$ivq_id2 = $row[0];
			$ivq_qty2 = $row[1];
			$new_ivq_qty2 = $ivq_qty2 + $bi_qty - $qty;
			if (($ivq_qty2 + $bi_qty) >= $qty) {
				$query = "UPDATE `transfer` SET `qty`='$qty' WHERE `id`='$tr_id'";
				$result1 = mysqli_query($conn, $query);
				if (!$result1) {
					$out = false;
					$msg = 'Related GTN quantity cannot be modified [112]';
				} else {
					$query = "UPDATE `inventory_qty` SET `qty`='$new_ivq_qty' WHERE `id`=$ivq_id";
					$result2 = mysqli_query($conn, $query);
					if (!$result2) {
						$out = false;
						$msg = 'Related GTN quantity cannot be modified [113]';
					} else {
						$out = true;
						$ivq_id = $ivq_id2;
						$ivq_qty = $ivq_qty2;
						$new_ivq_qty = $new_ivq_qty2;
					}
				}
			}
		} else {
			$out = false;
			$msg = 'Please contract related GTN owner to modify QTY.';
		}
	} else {
		$out = true;
	}

	if ($out) {
		$query = "SELECT cash_back_qty FROM `return` WHERE replace_item='$ivq_item' AND odr_no='$invoice_no' AND odr_packed='1'";
		$row = mysqli_fetch_row(mysqli_query($conn, $query));
		$return_cash_back_qty = $row[0];

		if ($return_cash_back_qty > 0) {
			$out = false;
			$msg = 'Item cannot be update, Please remove the return item(s) packed for this item.';
		}
	}

	if ($out) {
		if ($bi_qty < 0) {
			$query1 = "SELECT SUM(bi.qty) FROM bill_main bm, bill bi WHERE bm.invoice_no=bi.invoice_no AND bm.`cust`='$bm_cust' AND bi.item='$ivq_item' AND bm.`status`!='0' AND bm.`lock`='1'";
			$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
			if (!(($row1[0] !== null) && ($row1[0] >= -$qty))) {
				$out = false;
				$msg = 'Error: This Customer Did Not Purchase This Item OR Purchased Less Quantity';
			}
		}
	}

	if ($out && $out2) {
		$qty2 = $qty;
		if ($bi_qty < 0) {
			$bi_qty = 0;
		}
		if ($qty < 0) {
			$qty = 0;
		}
		$new_ivq_qty = $ivq_qty + $bi_qty - $qty;

		if ((($ivq_qty + $bi_qty) >= $qty) || ($pr_sr == 2)) {
			$query = "UPDATE `bill` SET `qty`='$qty2' WHERE `id`=$itemid";
			$result1 = mysqli_query($conn, $query);
			if ($result1) {
				if ($pr_sr == 1) {
					$query = "UPDATE `inventory_qty` SET `qty`='$new_ivq_qty' WHERE `id`=$ivq_id";
					$result2 = mysqli_query($conn, $query);
					processInventoryNewOdr($ivq_item, $itemid, $st_qry, 'bill');
					if ($result2) {
						$out = true;
						$msg = 'Item QTY was updated!';
					}
				} else {
					$out = true;
					$msg = 'Item QTY was updated!';
				}
			}
		} else {
			$msg = 'Error : Insufficient quantity!';
		}
	}
	$message = $msg;
	return $out;
}

function calculateTotalOdr()
{
	$item_id = $_GET['id'];
	include('config.php');
	$query = "SELECT invoice_no FROM bill WHERE id='$item_id'";
	$row = mysqli_fetch_row(mysqli_query($conn, $query));
	$invoice_no = $row[0];
	$result2 = mysqli_query($conn, "SELECT SUM(qty*unit_price) AS `total` FROM bill WHERE qty>0 AND invoice_no='$invoice_no'");
	$row2 = mysqli_fetch_assoc($result2);
	$up_total = $row2['total'];
	if ($up_total == '')
		$up_total = 0;
	$result2 = mysqli_query($conn, "SELECT SUM(qty*unit_price) AS `total` FROM bill WHERE qty<0 AND invoice_no='$invoice_no'");
	$row2 = mysqli_fetch_assoc($result2);
	$down_total = $row2['total'];
	if ($down_total == '') {
		$down_total = 0;
	}
	$out = true;
	$qb_result = "";

	// ------------ added by nirmal 26_07_2023
	$result = mysqli_query($conn, "SELECT `value` FROM settings WHERE setting='tax'");
	$row = mysqli_fetch_assoc($result);
	$tax_rate = $row['value'];

	// ------------ added by nirmal 21_08_2023
	$total = $up_total + $down_total;
	$tax_added_value = ((($total) / ((100 + $tax_rate) / 100)) * ($tax_rate / 100));

	$query3 = "UPDATE bill_main SET `invoice_+total`='$up_total', `invoice_-total`='$down_total', `tax` = '$tax_added_value' WHERE `invoice_no`='$invoice_no'";
	// ------------
	// $query3="UPDATE bill_main SET `invoice_+total`='$up_total', `invoice_-total`='$down_total' WHERE `invoice_no`='$invoice_no'";
	$result3 = mysqli_query($conn, $query3);
	if (!$result3) {
		$out = false;
		$message = 'Error: Pre calculation could not be done!';
	}
	return $out;
}

function getItemsOdr($item_filter, $sub_system, $systemid)
{
	global $discount, $unic_qty, $id, $code, $description, $w_price, $r_price, $cost, $drawer, $qty, $tt_item, $tt_qty, $unic, $pr_sr, $unic_item_code, $unic_item_list, $unic_item_list2, $is_unic_item;
	$unic_item_code = $qry_filter = '';
	$unic_item_list = $unic = $tt_item = $tt_qty = $drawer = $qty = $r_price = $w_price = $description = $code = $id = $unic_item_list2 = $pr_sr = array();
	$store = $_COOKIE['store'];
	$user_id = $_COOKIE['user_id'];
	$increment = '';
	$decimal = getDecimalPlaces(1);
	include('config.php');

	$result = mysqli_query($conn, "SELECT value FROM settings WHERE setting='discount'");
	$row = mysqli_fetch_assoc($result);
	$discount = $row['value'];

	$result = mysqli_query($conn, "SELECT mapped_inventory FROM userprofile WHERE id='$user_id'");
	$row = mysqli_fetch_assoc($result);
	if ($row['mapped_inventory'] != 0)
		$store = $row['mapped_inventory'];
	if (isset($_COOKIE['district']))
		$district = $_COOKIE['district'];
	else
		$district = 1;
	if ($_COOKIE['direct_mkt'] == 1)
		$qry_filter = 'AND inq.qty>0';
	$sp_item = $sp_increment = $sp_category = $sp_catincrement = array();
	if ($item_filter == 'all') {
		$filter_product = true;
		$filter_service = true;
	}
	if ($item_filter == 1) {
		$filter_product = true;
		$filter_service = false;
	}
	if ($item_filter == 2) {
		$filter_product = false;
		$filter_service = true;
	}
	if ($item_filter == 3) {
		$filter_product = false;
		$filter_service = true;
	}
	if ($item_filter == '') {
		$filter_product = true;
		$filter_service = true;
	}
	$category_filer = '';
	if (($systemid == 13) && ($_REQUEST['action'] == 'quotation')) {
		$category_filer = " AND itm.`category` NOT IN (SELECT `id` FROM item_category WHERE `status` IN (0))";
	}

	if (isset($_GET['unic'])) {
		$unicitemid = $_GET['itemid'];
		if ($_GET['unic'] == 'yes') {
			if (isset($_GET['cashback']))
				$itu_status = $_GET['cashback'];
			else
				$itu_status = 0;
			$query1 = "SELECT itm.code,itm.unic FROM inventory_items itm WHERE itm.id='$unicitemid'";
			$row1 = mysqli_fetch_row(mysqli_query($conn, $query1));
			$unic_item_code = $row1[0];
			$is_unic_item = $row1[1];
			$query1 = "SELECT itu.sn,itq.qty FROM inventory_items itm, inventory_qty itq, inventory_unic_item itu WHERE itm.id=itq.item AND itu.itq_id=itq.id AND itq.`location`='$store' AND itu.`status`='$itu_status' AND itm.id='$unicitemid' $category_filer";
			$result1 = mysqli_query($conn, $query1);
			while ($row1 = mysqli_fetch_array($result1)) {
				$unic_item_list[] = $row1[0];
				$unic_qty = $row1[1];
			}
			$query1 = "SELECT itu.sn FROM inventory_items itm, inventory_qty itq, inventory_unic_item itu WHERE itm.id=itq.item AND itu.itq_id=itq.id AND itu.`status`=1 AND itm.id='$unicitemid' $category_filer";
			$result1 = mysqli_query($conn, $query1);
			while ($row1 = mysqli_fetch_array($result1)) {
				$unic_item_list2[] = $row1[0];
			}
		}
		if ($_GET['unic'] == 'no') {
			$result1 = mysqli_query($conn, "SELECT itm.code FROM inventory_items itm WHERE itm.id='$unicitemid' $category_filer");
			$row1 = mysqli_fetch_assoc($result1);
			$unic_item_code = $row1['code'];
		}
	}

	$query = "SELECT increment FROM district_rate WHERE `district`='$district' AND `sub_system`='$sub_system'";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$increment = (100 + $row[0]) / 100;
	}
	if ($increment == '')
		$increment = 1;

	$query1 = "SELECT item,increment FROM special_rate WHERE district IN ($district,0) AND `sub_system`='$sub_system'";
	$result1 = mysqli_query($conn, $query1);
	while ($row1 = mysqli_fetch_array($result1)) {
		$sp_item[] = $row1[0];
		$sp_increment[] = $row1[1];
	}
	$query1 = "SELECT category,increment FROM category_rate WHERE district IN ($district,0) AND `sub_system`='$sub_system'";
	$result1 = mysqli_query($conn, $query1);
	while ($row1 = mysqli_fetch_array($result1)) {
		$sp_category[] = $row1[0];
		$sp_catincrement[] = $row1[1];
	}

	if ($filter_product) {
		$query = "SELECT itm.id,itm.code,itm.description,inq.w_price,inq.r_price,inq.c_price,inq.drawer_no,inq.qty,itm.`category`,itm.`unic`,itm.pr_sr FROM inventory_items itm, inventory_qty inq WHERE itm.id=inq.item AND inq.location='$store' AND itm.`status`=1 AND itm.pr_sr=1 $qry_filter $category_filer";
		$result = mysqli_query($conn, $query);
		while ($row = mysqli_fetch_array($result)) {
			$no_specialrate = $no_catspecialrate = true;
			$id[] = $row[0];
			$code[] = $row[1];
			$description[] = $row[2];
			$cost[] = $row[5];
			$drawer[] = $row[6];
			if (($systemid == 1) && ($sub_system != 0) && ($row[7] > 100))
				$qty[] = '100+';
			else
				$qty[] = $row[7];
			$unic[] = $row[9];
			$pr_sr[] = $row[10];
			for ($i = 0; $i < sizeof($sp_item); $i++) {
				if ($sp_item[$i] == $row[0]) {
					$no_specialrate = false;
					$w_price[] = round($row[3] * ((100 + $sp_increment[$i]) / 100), $decimal);
					$r_price[] = round($row[4] * ((100 + $sp_increment[$i]) / 100), $decimal);
				}
			}
			if ($no_specialrate) {
				for ($i = 0; $i < sizeof($sp_category); $i++) {
					if ($sp_category[$i] == $row[8]) {
						$no_catspecialrate = false;
						$w_price[] = round($row[3] * ((100 + $sp_catincrement[$i]) / 100), $decimal);
						$r_price[] = round($row[4] * ((100 + $sp_catincrement[$i]) / 100), $decimal);
					}
				}
				if ($no_catspecialrate) {
					$w_price[] = round(($row[3] * $increment), $decimal);
					$r_price[] = round(($row[4] * $increment), $decimal);
				}
			}
		}
		$query3 = "SELECT itm.id,itn.qty FROM inventory_new itn, inventory_items itm WHERE itn.item=itm.id AND store='$store' $category_filer";
		$result3 = mysqli_query($conn, $query3);
		while ($row3 = mysqli_fetch_array($result3)) {
			$tt_item[] = $row3[0];
			if (($systemid == 1) && ($sub_system != 0) && ($row3[1] > 100))
				$tt_qty[] = '100+';
			else
				$tt_qty[] = $row3[1];
		}
	}
	if ($filter_service) {
		if ($item_filter == 2)
			$qry_pr_sr = "AND itm.pr_sr='2'";
		else
			if ($item_filter == 3)
				$qry_pr_sr = "AND itm.pr_sr='3'";
			else
				$qry_pr_sr = "AND itm.pr_sr IN (2,3)";
		$query = "SELECT itm.id,itm.code,itm.description,itm.default_price,itm.pr_sr FROM inventory_items itm WHERE itm.`status`=1 $qry_pr_sr $category_filer";
		$result = mysqli_query($conn, $query);
		while ($row = mysqli_fetch_array($result)) {
			$id[] = $row[0];
			$code[] = $row[1];
			$description[] = $row[2];
			$cost[] = 0;
			$drawer[] = 0;
			$qty[] = 0;
			$unic[] = 0;
			$w_price[] = $row[3];
			$r_price[] = 0;
			$pr_sr[] = $row[4];
		}
	}
}

function processInventoryNewOdr($item, $lastitem, $store, $table)
{
	$nt_id = $itq_qty = '';

	include('config.php');

	$query = "SELECT id,item,w_price,r_price,c_price,qty FROM inventory_qty WHERE location='$store' AND item='$item'";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$itq_itqid = $row[0];
		$itq_itmid = $row[1];
		$itq_wprice = $row[2];
		$itq_rprice = $row[3];
		$itq_cprice = $row[4];
		$itq_qty = $row[5];
	}
	$query2 = "SELECT id,w_price,r_price,c_price,qty FROM inventory_new WHERE store='$store' AND item='$item' ORDER BY id DESC";
	$result2 = mysqli_query($conn, $query2);
	while ($row2 = mysqli_fetch_array($result2)) {
		$nt_id = $row2[0];
		$nt_wprice = $row2[1];
		$nt_rprice = $row2[2];
		$nt_cprice = $row2[3];
		$nt_qty = $row2[4];
	}
	if (($itq_qty <= 0) && ($nt_id != '')) {
		$query3 = "INSERT INTO `inventory_temp` (`itq_id`,`item`,`location`,`w_price`,`r_price`,`c_price`,`qty`) VALUES ('$itq_itqid','$itq_itmid','$store','$itq_wprice','$itq_rprice','$itq_cprice','$itq_qty')";
		$result3 = mysqli_query($conn, $query3);
		$lastid_temp = mysqli_insert_id($conn);

		$query3 = "UPDATE `inventory_qty` SET `w_price`='$nt_wprice',`r_price`='$nt_rprice',`c_price`='$nt_cprice',`qty`='$nt_qty' WHERE `id`='$itq_itqid'";
		$result3 = mysqli_query($conn, $query3);
		if ($result3) {
			$query3 = "DELETE FROM `inventory_new` WHERE `id` = '$nt_id'";
			$result3 = mysqli_query($conn, $query3);
		}
		if ($lastitem != 0) {
			$query4 = "UPDATE `$table` SET `no_update`='$lastid_temp' WHERE `id`='$lastitem'";
			mysqli_query($conn, $query4);
		}
	}
}

function crossTransferOdr($invoice_no, $itemid, $fromstore, $qty)
{
	$tostore = $_COOKIE['store'];
	$salesman = $_COOKIE['user_id'];
	$time_now = timeNow();
	$gtn_no = $inventory_qty = $inventory_id = $ivq_w_price = $ivq_r_price = $ivq_c_price = '';

	include('config.php');
	$query = "SELECT gtn_no FROM transfer_main WHERE invoice_no='$invoice_no' AND `status`='4' AND `user`='$salesman' AND `from_store`='$fromstore' AND `to_store`='$tostore'";
	$result = mysqli_query($conn, $query);
	$row = mysqli_fetch_assoc($result);
	$gtn_no = $row['gtn_no'];
	if ($gtn_no == '') {
		$result = mysqli_query($conn, "SELECT MAX(gtn_no) as trmainmax FROM transfer_main");
		$row = mysqli_fetch_assoc($result);
		$gtn_no = $row['trmainmax'] + 1;

		$query = "INSERT INTO `transfer_main` (`gtn_no`,`invoice_no`,`from_store`,`to_store`,`user`,`date`,`status`) VALUES ('$gtn_no','$invoice_no','$fromstore','$tostore','$salesman','$time_now','4')";
		$result3 = mysqli_query($conn, $query);
	}

	$query = "SELECT ivq.qty,ivq.id,ivq.w_price,ivq.r_price,ivq.c_price FROM inventory_qty ivq WHERE ivq.location='$fromstore' AND ivq.item='$itemid'";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$inventory_qty = $row[0];
		$inventory_id = $row[1];
		$ivq_w_price = $row[2];
		$ivq_r_price = $row[3];
		$ivq_c_price = $row[4];
	}

	if ($inventory_qty >= $qty) {
		$query2 = "INSERT INTO `transfer` (`gtn_no`,`item`,`w_price`,`r_price`,`c_price`,`qty`) VALUES ('$gtn_no','$itemid','$ivq_w_price','$ivq_r_price','$ivq_c_price','$qty')";
		$result2 = mysqli_query($conn, $query2);
		if ($result2) {
			$new_qty = $inventory_qty - $qty;
			$query3 = "UPDATE `inventory_qty` SET `qty`='$new_qty' WHERE `id`=$inventory_id";
			$result3 = mysqli_query($conn, $query3);
		}
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

		// QuickBooks user check
		if ($out && (isQuickBooksActive(1))) {
			// Sanitize the input to prevent SQL injection
			$invoice_no = mysqli_real_escape_string($conn, $invoice_no);

			// Construct and execute the query
			$userQry = "SELECT c.`qb_cust_id`, bm.`qb_status`, bm.`qb_temp_cost`
					FROM bill_main bm INNER JOIN cust c ON c.`id` = bm.`cust` WHERE bm.`invoice_no` = '$invoice_no'";
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

		$billMainQuery = "SELECT `status`,`type`,`mapped_inventory` FROM bill_main WHERE invoice_no='$invoice_no'";
		$billMainQueryResult = mysqli_query($conn, $billMainQuery);
		$billMainQueryResultRow = mysqli_fetch_assoc($billMainQueryResult);
		$status = $billMainQueryResultRow['status'];
		$type = $billMainQueryResultRow['type'];
		$bm_mapped_inventory = $billMainQueryResultRow['mapped_inventory'];

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
					throw new Exception($message);
				}

				if ($type == 4) {
					$query = "SELECT SUM(qty), item FROM bill WHERE invoice_no = '$invoice_no' AND qty < 0 GROUP BY item";
					$result = mysqli_query($conn, $query);
					if (!$result) {
						throw new Exception('Database error: ' . mysqli_error($conn));
					} else {
						while ($billItem = mysqli_fetch_array($result)) {
							$bill_cash_back_qty = abs($billItem[0]);
							$item = $billItem[1];

							$returnQuery = "SELECT SUM(`cash_back_qty`) FROM `return` WHERE `odr_no` = '$invoice_no' AND `replace_item` = '$item' AND `odr_packed` = 1 GROUP BY `replace_item`";
							$returnResult = mysqli_query($conn, $returnQuery);
							if (!$returnResult) {
								throw new Exception('Database error: ' . mysqli_error($conn));
							}
							$returnRow = mysqli_fetch_row($returnResult);
							$return_cash_back_qty = $returnRow[0];

							$queryInventoryId = "SELECT `id` FROM inventory_qty WHERE `item`='$item' AND `location`='$bm_mapped_inventory'";
							$queryInventoryResult = mysqli_query($conn, $queryInventoryId);
							if (!$queryInventoryResult) {
								throw new Exception('Database error: ' . mysqli_error($conn));
							}
							$inventoryRow = mysqli_fetch_row($queryInventoryResult);
							$inventoryId = $inventoryRow[0];

							$temp_qty = $bill_cash_back_qty - $return_cash_back_qty;

							$queryUpdateInventoryQty = "UPDATE inventory_qty SET qty = qty + $temp_qty WHERE id='$inventoryId'";
							$updateResult = mysqli_query($conn, $queryUpdateInventoryQty);
							if (!$updateResult) {
								throw new Exception('Database error: ' . mysqli_error($conn));
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
							$invoiceCostQuery = "SELECT SUM(b.`qty` * b.`cost`), c.`name`, (bm.`invoice_+total` + bm.`invoice_-total`)
								FROM bill_main bm, bill b, cust c
								WHERE c.`id` = bm.`cust` AND bm.`invoice_no` = b.`invoice_no` AND bm.`invoice_no`='$invoice_no' AND bm.`qb_status` IS NOT NULL";
							$invoiceCostRow = mysqli_fetch_row(mysqli_query($conn, $invoiceCostQuery));
							if (!empty($invoiceCostRow)) {
								$invoiceCost = $invoiceCostRow[0];
								$custName = $invoiceCostRow[1];
								$bill_total = $invoiceCostRow[2];

								// minus (cashback items)
								if (($bill_total < 0)) {
									if ($qb_temp_cost > 0) {
										// delete journal entry
										$debitAccountName = "Inventory Asset";
										$creditAccountName = "Cost of Goods Sold";
										$description = "[REVERT BACK TEMPORARY INVOICE] - Invoice No: $invoice_no, Customer : $custName";
										$debitEntityType = "";
										$debitEntityID = "";
										$creditEntityType = "";
										$creditEntityID = "";

										$journalEntryForInvoiceCost = buildJournalEntry($conn, abs($qb_temp_cost), $debitAccountName, $creditAccountName, $description, $debitEntityType, $debitEntityID, $creditEntityType, $creditEntityID);
										if (isset($journalEntryForInvoiceCost['error'])) {
											$qb_msg = $journalEntryForInvoiceCost['error'];
											throw new Exception("QuickBooks error: " . $qb_msg);
										} else {
											$batch_id = generateBatchID();
											if (!$batch_id) {
												throw new Exception("Error: Failed to generate a valid batch ID.");
											}
											$action_name = 'delete_invoice_cost_through_odr_insert';
											foreach ($journalEntryForInvoiceCost as $entry) {
												$posting_type = mysqli_real_escape_string($conn, $entry['posting_type']);
												$account_id = mysqli_real_escape_string($conn, $entry['account_id']);
												$account_name = mysqli_real_escape_string($conn, $entry['account_name']);
												$amount = mysqli_real_escape_string($conn, $entry['amount']);
												$description = mysqli_real_escape_string($conn, $entry['description']);
												$entity_type = isset($entry['entity_type']) ? mysqli_real_escape_string($conn, $entry['entity_type']) : null;
												$entity_id = isset($entry['entity_id']) ? mysqli_real_escape_string($conn, $entry['entity_id']) : null;

												$query = "INSERT INTO qb_queue (`batch_id`, `action`, `invoice_no`, `posting_type`, `account_id`, `account_name`, `invoice_cost`, `description`, `created_at`, `entity_type`, `entity_id`)
													VALUES ('$batch_id','$action_name', '$invoice_no', '$posting_type', '$account_id', '$account_name', '$amount', '$description', '$time_now',
														" . ($entity_type !== null ? "'$entity_type'" : "NULL") . ",
														" . ($entity_id !== null ? "'$entity_id'" : "NULL") . ")";
												$result = mysqli_query($conn, $query);
												if (!$result) {
													$message = "MySQL Error while inserting into qb_queue: " . mysqli_error($conn);
													throw new Exception($message);
												}
											}
										}
									}
									// invoice total journal entry
									if ($bill_total > 0) {
										$debitAccountName = "Accounts Receivable (A/R)";
										$creditAccountName = "Sales";
										$description = "[CASH BACK INVOICE] - Invoice No: $invoice_no, Customer : $custName";
										$debitEntityType = "Customer";
										$debitEntityID = $qb_cust_id;
										$creditEntityType = "";
										$creditEntityID = "";

										$journalEntryForInvoiceTotal = buildJournalEntry($conn, abs($bill_total), $debitAccountName, $creditAccountName, $description, $debitEntityType, $debitEntityID, $creditEntityType, $creditEntityID);
										if (isset($journalEntryForInvoiceTotal['error'])) {
											$qb_msg = mysqli_real_escape_string($conn, $journalEntryForInvoiceTotal['error']);
											throw new Exception("QuickBooks error: " . $qb_msg);
										} else {
											$batch_id = generateBatchID();
											if (!$batch_id) {
												throw new Exception("Error: Failed to generate a valid batch ID.");
											}
											$action_name = 'return_invoice_insert_through_odr_insert';
											foreach ($journalEntryForInvoiceTotal as $entry) {
												$posting_type = mysqli_real_escape_string($conn, $entry['posting_type']);
												$account_id = mysqli_real_escape_string($conn, $entry['account_id']);
												$account_name = mysqli_real_escape_string($conn, $entry['account_name']);
												$amount = mysqli_real_escape_string($conn, $entry['amount']);
												$description = mysqli_real_escape_string($conn, $entry['description']);
												$entity_type = isset($entry['entity_type']) ? mysqli_real_escape_string($conn, $entry['entity_type']) : null;
												$entity_id = isset($entry['entity_id']) ? mysqli_real_escape_string($conn, $entry['entity_id']) : null;

												$query = "INSERT INTO qb_queue (`batch_id`, `action`, `invoice_no`, `posting_type`, `account_id`, `account_name`, `invoice_total`, `description`, `created_at`, `entity_type`, `entity_id`)
													VALUES ('$batch_id','$action_name', '$invoice_no', '$posting_type', '$account_id', '$account_name', '$amount', '$description','$time_now',
														" . ($entity_type !== null ? "'$entity_type'" : "NULL") . ",
														" . ($entity_id !== null ? "'$entity_id'" : "NULL") . ")";
												$result = mysqli_query($conn, $query);
												if (!$result) {
													$message = "MySQL Error while inserting into qb_queue: " . mysqli_error($conn);
													throw new Exception($message);
												}
											}
										}
									} else {
										// invoice total journal entry
										$debitAccountName = "Sales";
										$creditAccountName = "Accounts Receivable (A/R)";
										$description = "[CASH BACK INVOICE] - Invoice No: $invoice_no, Customer : $custName";
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
											if (!$batch_id) {
												throw new Exception("Error: Failed to generate a valid batch ID.");
											}
											$action_name = 'return_invoice_insert_through_odr_insert';
											foreach ($journalEntryForInvoiceTotal as $entry) {
												$posting_type = mysqli_real_escape_string($conn, $entry['posting_type']);
												$account_id = mysqli_real_escape_string($conn, $entry['account_id']);
												$account_name = mysqli_real_escape_string($conn, $entry['account_name']);
												$amount = mysqli_real_escape_string($conn, $entry['amount']);
												$description = mysqli_real_escape_string($conn, $entry['description']);
												$entity_type = isset($entry['entity_type']) ? mysqli_real_escape_string($conn, $entry['entity_type']) : null;
												$entity_id = isset($entry['entity_id']) ? mysqli_real_escape_string($conn, $entry['entity_id']) : null;

												$query = "INSERT INTO qb_queue (`batch_id`, `action`, `invoice_no`, `posting_type`, `account_id`, `account_name`, `invoice_total`, `description`, `created_at`, `entity_type`, `entity_id`)
													VALUES ('$batch_id','$action_name', '$invoice_no', '$posting_type', '$account_id', '$account_name', '$amount', '$description','$time_now',
														" . ($entity_type !== null ? "'$entity_type'" : "NULL") . ",
														" . ($entity_id !== null ? "'$entity_id'" : "NULL") . ")";
												$result = mysqli_query($conn, $query);
												if (!$result) {
													$message = "MySQL Error while inserting into qb_queue: " . mysqli_error($conn);
													throw new Exception($message);
												}
											}
										}
									}

									// invoice cost journal entry
									$debitAccountName = "Inventory Asset";
									$creditAccountName = "Cost of Goods Sold";
									$description = "[CASH BACK INVOICE] - Invoice No: $invoice_no, Customer : $custName";
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
										if (!$batch_id) {
											throw new Exception("Error: Failed to generate a valid batch ID.");
										}
										$action_name = 'return_invoice_insert_through_odr_insert';

										foreach ($journalEntryForInvoiceCost as $entry) {
											$posting_type = mysqli_real_escape_string($conn, $entry['posting_type']);
											$account_id = mysqli_real_escape_string($conn, $entry['account_id']);
											$account_name = mysqli_real_escape_string($conn, $entry['account_name']);
											$amount = mysqli_real_escape_string($conn, $entry['amount']);
											$description = mysqli_real_escape_string($conn, $entry['description']);
											$entity_type = isset($entry['entity_type']) ? mysqli_real_escape_string($conn, $entry['entity_type']) : null;
											$entity_id = isset($entry['entity_id']) ? mysqli_real_escape_string($conn, $entry['entity_id']) : null;

											$query = "INSERT INTO qb_queue (`batch_id`, `action`, `invoice_no`, `posting_type`, `account_id`, `account_name`, `invoice_cost`, `description`, `created_at`, `entity_type`, `entity_id`)
													VALUES ('$batch_id','$action_name', '$invoice_no', '$posting_type', '$account_id', '$account_name', '$amount', '$description','$time_now',
														" . ($entity_type !== null ? "'$entity_type'" : "NULL") . ",
														" . ($entity_id !== null ? "'$entity_id'" : "NULL") . ")";
											$result = mysqli_query($conn, $query);
											if (!$result) {
												$message = "MySQL Error while inserting into qb_queue: " . mysqli_error($conn);
												throw new Exception($message);
											}
										}
									}
								}

								// positive (normal) invoice
								if (($bill_total > 0)) {
									if ($qb_temp_cost < 0) {

									} else {
										// delete journal entry
										$debitAccountName = "Inventory Asset";
										$creditAccountName = "Cost of Goods Sold";
										$description = "[REVERT BACK TEMPORARY INVOICE] - Invoice No: $invoice_no, Customer : $custName";
										$debitEntityType = "";
										$debitEntityID = "";
										$creditEntityType = "";
										$creditEntityID = "";

										$journalEntryForInvoiceCost = buildJournalEntry($conn, abs($qb_temp_cost), $debitAccountName, $creditAccountName, $description, $debitEntityType, $debitEntityID, $creditEntityType, $creditEntityID);
										if (isset($journalEntryForInvoiceCost['error'])) {
											$qb_msg = $journalEntryForInvoiceCost['error'];
											throw new Exception("QuickBooks error: " . $qb_msg);
										} else {
											$batch_id = generateBatchID();
											if (!$batch_id) {
												throw new Exception("Error: Failed to generate a valid batch ID.");
											}
											$action_name = 'delete_invoice_cost_through_odr_insert';
											foreach ($journalEntryForInvoiceCost as $entry) {
												$posting_type = mysqli_real_escape_string($conn, $entry['posting_type']);
												$account_id = mysqli_real_escape_string($conn, $entry['account_id']);
												$account_name = mysqli_real_escape_string($conn, $entry['account_name']);
												$amount = mysqli_real_escape_string($conn, $entry['amount']);
												$description = mysqli_real_escape_string($conn, $entry['description']);
												$entity_type = isset($entry['entity_type']) ? mysqli_real_escape_string($conn, $entry['entity_type']) : null;
												$entity_id = isset($entry['entity_id']) ? mysqli_real_escape_string($conn, $entry['entity_id']) : null;

												$query = "INSERT INTO qb_queue (`batch_id`, `action`, `invoice_no`, `posting_type`, `account_id`, `account_name`, `invoice_cost`, `description`, `created_at`, `entity_type`, `entity_id`)
													VALUES ('$batch_id','$action_name', '$invoice_no', '$posting_type', '$account_id', '$account_name', '$amount', '$description', '$time_now',
														" . ($entity_type !== null ? "'$entity_type'" : "NULL") . ",
														" . ($entity_id !== null ? "'$entity_id'" : "NULL") . ")";
												$result = mysqli_query($conn, $query);
												if (!$result) {
													$message = "MySQL Error while inserting into qb_queue: " . mysqli_error($conn);
													throw new Exception($message);
												}
											}
										}
									}

									// invoice total journal entry constructing
									if ($bill_total > 0) {
										$debitAccountName = "Accounts Receivable (A/R)";
										$creditAccountName = "Sales";
										$description = "[INVOICE] - Invoice No: $invoice_no, Customer : $custName";
										$debitEntityType = "Customer";
										$debitEntityID = $qb_cust_id;
										$creditEntityType = "";
										$creditEntityID = "";

										$journalEntryForInvoiceTotal = buildJournalEntry($conn, abs($bill_total), $debitAccountName, $creditAccountName, $description, $debitEntityType, $debitEntityID, $creditEntityType, $creditEntityID);
										if (isset($journalEntryForInvoiceTotal['error'])) {
											$qb_msg = $journalEntryForInvoiceTotal['error'];
											throw new Exception("QuickBooks error: " . $qb_msg);
										} else {
											$batch_id = generateBatchID();
											if (!$batch_id) {
												throw new Exception("Error: Failed to generate a valid batch ID.");
											}
											$action_name = 'invoice_insert_through_odr_insert';
											foreach ($journalEntryForInvoiceTotal as $entry) {
												$posting_type = mysqli_real_escape_string($conn, $entry['posting_type']);
												$account_id = mysqli_real_escape_string($conn, $entry['account_id']);
												$account_name = mysqli_real_escape_string($conn, $entry['account_name']);
												$amount = mysqli_real_escape_string($conn, $entry['amount']);
												$description = mysqli_real_escape_string($conn, $entry['description']);
												$entity_type = isset($entry['entity_type']) ? mysqli_real_escape_string($conn, $entry['entity_type']) : null;
												$entity_id = isset($entry['entity_id']) ? mysqli_real_escape_string($conn, $entry['entity_id']) : null;

												$query = "INSERT INTO qb_queue (`batch_id`, `action`, `invoice_no`, `posting_type`, `account_id`, `account_name`, `invoice_total`, `description`, `created_at`, `entity_type`, `entity_id`)
													VALUES ('$batch_id','$action_name', '$invoice_no', '$posting_type', '$account_id', '$account_name', '$amount', '$description', '$time_now',
														" . ($entity_type !== null ? "'$entity_type'" : "NULL") . ",
														" . ($entity_id !== null ? "'$entity_id'" : "NULL") . ")";
												$result = mysqli_query($conn, $query);
												if (!$result) {
													$message = "MySQL Error while inserting into qb_queue: " . mysqli_error($conn);
													throw new Exception($message);
												}
											}
										}
									} else {
										$debitAccountName = "Sales";
										$creditAccountName = "Accounts Receivable (A/R)";
										$description = "[INVOICE] - Invoice No: $invoice_no, Customer : $custName";
										$debitEntityType = "";
										$debitEntityID = "";
										$creditEntityType = "Customer";
										$creditEntityID = $qb_cust_id;

										$journalEntryForInvoiceTotal = buildJournalEntry($conn, $bill_total, $debitAccountName, $creditAccountName, $description, $debitEntityType, $debitEntityID, $creditEntityType, $creditEntityID);
										if (isset($journalEntryForInvoiceTotal['error'])) {
											$qb_msg = $journalEntryForInvoiceTotal['error'];
											throw new Exception("QuickBooks error: " . $qb_msg);
										} else {
											$batch_id = generateBatchID();
											if (!$batch_id) {
												throw new Exception("Error: Failed to generate a valid batch ID.");
											}
											$action_name = 'invoice_insert_through_odr_insert';
											foreach ($journalEntryForInvoiceTotal as $entry) {
												$posting_type = mysqli_real_escape_string($conn, $entry['posting_type']);
												$account_id = mysqli_real_escape_string($conn, $entry['account_id']);
												$account_name = mysqli_real_escape_string($conn, $entry['account_name']);
												$amount = mysqli_real_escape_string($conn, $entry['amount']);
												$description = mysqli_real_escape_string($conn, $entry['description']);
												$entity_type = isset($entry['entity_type']) ? mysqli_real_escape_string($conn, $entry['entity_type']) : null;
												$entity_id = isset($entry['entity_id']) ? mysqli_real_escape_string($conn, $entry['entity_id']) : null;

												$query = "INSERT INTO qb_queue (`batch_id`, `action`, `invoice_no`, `posting_type`, `account_id`, `account_name`, `invoice_total`, `description`, `created_at`, `entity_type`, `entity_id`)
													VALUES ('$batch_id','$action_name', '$invoice_no', '$posting_type', '$account_id', '$account_name', '$amount', '$description', '$time_now',
														" . ($entity_type !== null ? "'$entity_type'" : "NULL") . ",
														" . ($entity_id !== null ? "'$entity_id'" : "NULL") . ")";
												$result = mysqli_query($conn, $query);
												if (!$result) {
													$message = "MySQL Error while inserting into qb_queue: " . mysqli_error($conn);
													throw new Exception($message);
												}
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

									$journalEntryForInvoiceCost = buildJournalEntry($conn, abs($invoiceCost), $debitAccountName, $creditAccountName, $description, $debitEntityType, $debitEntityID, $creditEntityType, $creditEntityID);
									if (isset($journalEntryForInvoiceCost['error'])) {
										$qb_msg = $journalEntryForInvoiceCost['error'];
										throw new Exception("QuickBooks error: " . $qb_msg);
									} else {
										$batch_id = generateBatchID();
										if (!$batch_id) {
											throw new Exception("Error: Failed to generate a valid batch ID.");
										}
										$action_name = 'invoice_insert_through_odr_insert';

										foreach ($journalEntryForInvoiceCost as $entry) {
											$posting_type = mysqli_real_escape_string($conn, $entry['posting_type']);
											$account_id = mysqli_real_escape_string($conn, $entry['account_id']);
											$account_name = mysqli_real_escape_string($conn, $entry['account_name']);
											$amount = mysqli_real_escape_string($conn, $entry['amount']);
											$description = mysqli_real_escape_string($conn, $entry['description']);
											$entity_type = isset($entry['entity_type']) ? mysqli_real_escape_string($conn, $entry['entity_type']) : null;
											$entity_id = isset($entry['entity_id']) ? mysqli_real_escape_string($conn, $entry['entity_id']) : null;

											$query = "INSERT INTO qb_queue (`batch_id`, `action`, `invoice_no`, `posting_type`, `account_id`, `account_name`, `invoice_cost`, `description`, `created_at`, `entity_type`, `entity_id`)
													VALUES ('$batch_id','$action_name', '$invoice_no', '$posting_type', '$account_id', '$account_name', '$amount', '$description','$time_now',
														" . ($entity_type !== null ? "'$entity_type'" : "NULL") . ",
														" . ($entity_id !== null ? "'$entity_id'" : "NULL") . ")";
											$result = mysqli_query($conn, $query);
											if (!$result) {
												$message = "MySQL Error while inserting into qb_queue: " . mysqli_error($conn);
												throw new Exception($message);
											}
										}
									}
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
										if (!$batch_id) {
											throw new Exception("Error: Failed to generate a valid batch ID.");
										}
										$action_name = 'return_item_invoice_insert_through_odr_insert';

										foreach ($journalEntryForReturnItems as $entry) {
											$posting_type = mysqli_real_escape_string($conn, $entry['posting_type']);
											$account_id = mysqli_real_escape_string($conn, $entry['account_id']);
											$account_name = mysqli_real_escape_string($conn, $entry['account_name']);
											$amount = mysqli_real_escape_string($conn, $entry['amount']);
											$description = mysqli_real_escape_string($conn, $entry['description']);
											$entity_type = isset($entry['entity_type']) ? mysqli_real_escape_string($conn, $entry['entity_type']) : null;
											$entity_id = isset($entry['entity_id']) ? mysqli_real_escape_string($conn, $entry['entity_id']) : null;

											$query = "INSERT INTO qb_queue (`batch_id`, `action`, `invoice_no`, `posting_type`, `account_id`, `account_name`, `amount`, `description`, `created_at`, `entity_type`, `entity_id`)
													VALUES ('$batch_id','$action_name', '$invoice_no', '$posting_type', '$account_id', '$account_name', '$amount', '$description','$time_now',
														" . ($entity_type !== null ? "'$entity_type'" : "NULL") . ",
														" . ($entity_id !== null ? "'$entity_id'" : "NULL") . ")";
											$result = mysqli_query($conn, $query);
											if (!$result) {
												$message = "MySQL Error while inserting into qb_queue: " . mysqli_error($conn);
												throw new Exception($message);
											}
										}
									}
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

// updated by nirmal 06_05_2025 (added qb integration)
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

	try {
		// Begin transaction
		mysqli_begin_transaction($conn);

		// Check that quantities are non-negative
		if ($invrtn < 0) {
			throw new Exception('Invalid return quantity!');
		}
		if ($disrtn < 0) {
			throw new Exception('Invalid disposal quantity!');
		}

		// Get current inventory info
		$result0 = mysqli_query($conn, "SELECT id,qty,w_price,r_price,c_price FROM inventory_qty WHERE `location`='$store' AND item='$item'");
		if (!$result0) {
			throw new Exception(mysqli_error($conn));
		}
		$row0 = mysqli_fetch_assoc($result0);
		if (!$row0) {
			throw new Exception("Inventory record not found!");
		}

		$itq_id = $row0['id'];
		$itq_qty = $row0['qty'];
		$w_price = $row0['w_price'];
		$r_price = $row0['r_price'];
		$c_price = $row0['c_price'];

		// Get total returned quantity for the current item
		$result = mysqli_query($conn, "SELECT SUM(rt.qty) as qty FROM return_main rm, `return` rt WHERE rm.invoice_no=rt.invoice_no AND rm.`store`='$store' AND rt.`status`='0' AND rm.`status`='2' AND rt.return_item='$item'");
		if (!$result) {
			throw new Exception(mysqli_error($conn));
		}
		$row = mysqli_fetch_assoc($result);
		$totalqty = $row['qty'];

		if ($totalqty != ($invrtn + $disrtn)) {
			throw new Exception("Total quantity mismatch between return and disposal!");
		}
		$new_qty = $itq_qty + $invrtn;

		if ($disrtn > 0) {
			$query2 = "INSERT INTO `return_disposal` (`item`,`w_price`,`r_price`,`c_price`,`qty`,`store`,`date`) VALUES ('$item','$w_price','$r_price','$c_price','$disrtn','$store','$time_now')";
			$result2 = mysqli_query($conn, $query2);
			if (!$result2) {
				throw new Exception(mysqli_error($conn));
			}

			$lastid_temp = mysqli_insert_id($conn);
			$query1 = "UPDATE `return` rt, return_main rm SET rt.`disposal_id`='$lastid_temp',rt.`status`='1',rt.`process_date`='$time_now',rt.`process_by`='$user' WHERE rm.invoice_no=rt.invoice_no AND rm.`store`='$store' AND rt.`status`='0' AND rt.`return_item`='$item'";
			$result1 = mysqli_query($conn, $query1);
			if (!$result1) {
				throw new Exception(mysqli_error($conn));
			}
			$output = true;
		}

		if ($invrtn > 0) {
			$debug_id = debugStart($itq_id, $invrtn);
			$query1 = "UPDATE `return` rt, return_main rm SET rt.`status`='1',rt.`process_date`='$time_now',rt.`process_by`='$user' WHERE rm.invoice_no=rt.invoice_no AND rm.`store`='$store' AND rt.`status`='0' AND rt.`return_item`='$item'";
			$result1 = mysqli_query($conn, $query1);
			if (!$result1) {
				throw new Exception(mysqli_error($conn));
			}
			$query2 = "UPDATE `inventory_qty` SET `qty`='$new_qty' WHERE `id`='$itq_id'";
			$result2 = mysqli_query($conn, $query2);
			if (!$result2) {
				throw new Exception(mysqli_error($conn));
			}
			$output = true;
		}


		// if ($output) {
		// 	if ($invrtn > 0)
		// 		debugEnd($debug_id, 'success');
		// 	$message = 'Item was processed successfully!';
		// 	return true;
		// } else {
		// 	if ($invrtn > 0)
		// 		debugEnd($debug_id, 'fail');
		// 	$message = 'Item could not be processed !';
		// 	return false;
		// }

		// If no valid operations were performed, throw exception.
		if (!$output) {
			throw new Exception('No operations were performed.');
		}

		if (isQuickBooksActive(1) && $invrtn > 0) {
			// invoice total journal entry
			$total = $c_price * $invrtn; // cost

			$debitAccountName = "Inventory Asset";
			$creditAccountName = "Return Item";
			$description = "RETURN ITEMS MOVE TO INVENTORY";
			$debitEntityType = "";
			$debitEntityID = "";
			$creditEntityType = "";
			$creditEntityID = "";

			$journalEntry = buildJournalEntry($conn, $total, $debitAccountName, $creditAccountName, $description, $debitEntityType, $debitEntityID, $creditEntityType, $creditEntityID);
			if (isset($journalEntry['error'])) {
				$qb_msg = $journalEntry['error'];
				throw new Exception("QuickBooks error: " . $qb_msg);
			} else {
				$batch_id = generateBatchID();
				if (!$batch_id) {
					throw new Exception("Error: Failed to generate a valid batch ID.");
				}
				$action_name = 'return_items_move_to_inventory_insert';
				foreach ($journalEntry as $entry) {
					$posting_type = mysqli_real_escape_string($conn, $entry['posting_type']);
					$account_id = mysqli_real_escape_string($conn, $entry['account_id']);
					$account_name = mysqli_real_escape_string($conn, $entry['account_name']);
					$amount = mysqli_real_escape_string($conn, $entry['amount']);
					$description = mysqli_real_escape_string($conn, $entry['description']);
					$entity_type = isset($entry['entity_type']) ? mysqli_real_escape_string($conn, $entry['entity_type']) : null;
					$entity_id = isset($entry['entity_id']) ? mysqli_real_escape_string($conn, $entry['entity_id']) : null;

					$query = "INSERT INTO qb_queue (`batch_id`, `action`, `posting_type`, `account_id`, `account_name`, `amount`, `description`, `created_at`, `entity_type`, `entity_id`)
											VALUES ('$batch_id','$action_name', '$posting_type', '$account_id', '$account_name', '$amount', '$description','$time_now',
												" . ($entity_type !== null ? "'$entity_type'" : "NULL") . ",
												" . ($entity_id !== null ? "'$entity_id'" : "NULL") . ")";
					$result = mysqli_query($conn, $query);
					if (!$result) {
						$message = "MySQL Error while inserting into qb_queue: " . mysqli_error($conn);
						throw new Exception($message);
					}
				}
			}
		}

		// Commit transaction:
		mysqli_commit($conn);

		if ($invrtn > 0) {
			debugEnd($debug_id, 'success');
		}
		$message = 'Item was processed successfully!';
		return true;
	} catch (Exception $e) {
		// Roll back transaction on error
		mysqli_rollback($conn);
		if ($invrtn > 0 && isset($debug_id)) {
			debugEnd($debug_id, 'fail');
		}
		$message = 'Item could not be processed: ' . $e->getMessage();
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