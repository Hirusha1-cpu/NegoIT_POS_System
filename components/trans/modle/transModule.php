<?php
function trnsStatus($status_id)
{
	switch ($status_id) {
		case "0":
			$gtn_status = 'Pending';
			$gtn_color = 'blue';
			break;
		case "1":
			$gtn_status = 'Accepted';
			$gtn_color = 'green';
			break;
		case "2":
			$gtn_status = 'Rejected';
			$gtn_color = 'red';
			break;
		case "3":
			$gtn_status = 'Canceled';
			$gtn_color = 'maroon';
			break;
		case "4":
			$gtn_status = 'Transfering';
			$gtn_color = 'blue';
			break;
		case "5":
			$gtn_status = 'Cross Transfer';
			$gtn_color = 'purple';
			break;
	}
	$jasonArray["status"] = $gtn_status;
	$jasonArray["color"] = $gtn_color;
	$myJSON = json_encode($jasonArray);
	return $myJSON;
}

// update by nirmal 21_11_2023
function getItems()
{
	global $id, $code, $description, $w_price, $r_price, $cost, $drawer, $qty, $tt_item, $tt_qty, $unic, $unic_item_code, $unic_item_list, $unic_qty;
	$store = $_COOKIE['store'];
	$unic_cal = unicCal();
	$unic_item_code = '';
	$unic_qty = 0;
	$unic_item_list = $id = $code = $description = $unic = $drawer = $qty = $w_price = $r_price = $cost = $tt_item = $tt_qty = array();
	include('config.php');
	if (isset($_GET['unic'])) {
		$unicitemid = $_GET['itemid'];
		if ($_GET['unic'] == 'yes') {
			$query1 = "SELECT itm.code,itu.sn,itq.qty FROM inventory_items itm, inventory_qty itq, inventory_unic_item itu WHERE itm.id=itq.item AND itu.itq_id=itq.id AND itq.`location`='$store' AND itu.`status`=0 AND itm.id='$unicitemid'";
			$result1 = mysqli_query($conn, $query1);
			while ($row1 = mysqli_fetch_array($result1)) {
				$unic_item_code = $row1[0];
				$unic_item_list[] = $row1[1];
				if ($unic_cal) {
					$unic_qty++;
				} else {
					$unic_qty = $row1[2];
				}
			}
		}
		if ($_GET['unic'] == 'no') {
			$result1 = mysqli_query($conn, "SELECT itm.code FROM inventory_items itm WHERE itm.id='$unicitemid'");
			$row1 = mysqli_fetch_assoc($result1);
			$unic_item_code = $row1['code'];
		}
	}
	$query = "SELECT inv.id,inv.code,inv.description,inq.w_price,inq.r_price,inq.c_price,inq.drawer_no,inq.qty,inv.unic,inq.id FROM inventory_items inv, inventory_qty inq WHERE inv.id=inq.item AND inq.location='$store' AND inv.`status`=1";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$itq_id_tmp = $row[9];
		$id[] = $row[0];
		$code[] = $row[1];
		$description[] = $row[2];
		$drawer[] = $row[6];
		$unic[] = $row[8];
		if (($unic_cal) && ($row[8] == 1)) {
			$query1 = "SELECT COUNT(id),SUM(w_price),SUM(r_price),SUM(c_price) FROM inventory_unic_item WHERE itq_id='$itq_id_tmp' AND `status`='0'";
			$row1 = mysqli_fetch_row(mysqli_query($conn2, $query1));
			$qty[] = $row1[0];
			if ($row1[0] != 0) {
				$w_price[] = $row1[1] / $row1[0];
				$r_price[] = $row1[2] / $row1[0];
				$cost[] = $row1[3] / $row1[0];
			} else {
				$w_price[] = 0;
				$r_price[] = 0;
				$cost[] = 0;
			}
		} else {
			$w_price[] = $row[3];
			$r_price[] = $row[4];
			$cost[] = $row[5];
			$qty[] = $row[7];
		}
	}
	$query3 = "SELECT itm.code,itn.qty FROM inventory_new itn, inventory_items itm WHERE itn.item=itm.id AND store='$store'";
	$result3 = mysqli_query($conn, $query3);
	while ($row3 = mysqli_fetch_array($result3)) {
		$tt_item[] = $row3[0];
		$tt_qty[] = $row3[1];
	}
}

// updated by nirmal 15_10_2023
// update by nirmal 05_07_2024 (added systemid 24 to omit subsystem filtering stores)
// updated by nirmal 23_12_2024 (if request module is top mgr, show all stores)

function getStores($systemid, $sub_system)
{
	global $store_id, $store_name;
	$store = $_COOKIE['store'];
	$user_id = $_COOKIE['user_id'];
	$sub_sys_qry = "AND sub_system='0'";
	if ($sub_system == 0 || $systemid == 24)
		$sub_sys_qry = "";
	if ($systemid == 14)
		$sub_sys_qry = "AND sub_system IN ('0','$sub_system')";
	if ($systemid == 20)
		$sub_sys_qry = "AND sub_system ='$sub_system'";
	$store_id = $store_name = array();
	include('config.php');

	if ($systemid == 13) {
		$result = mysqli_query($conn2, "SELECT `store_group` FROM userprofile WHERE `id` = '$user_id'");
		$row = mysqli_fetch_assoc($result);
		$group = $row['store_group'];
		if ($group == 0) {
			$result = mysqli_query($conn2, "SELECT `group` FROM store_group WHERE store='$store'");
			$row = mysqli_fetch_assoc($result);
			$group = $row['group'];
		}

		$query = "SELECT st.id,st.name FROM stores st, store_group sg WHERE st.id=sg.store AND sg.`group`='$group'";
		$result = mysqli_query($conn2, $query);
		while ($row = mysqli_fetch_array($result)) {
			$store_id[] = $row[0];
			$store_name[] = $row[1];
		}
	} else {
		$query = "SELECT id,name FROM stores WHERE `status`='1' $sub_sys_qry";
		$result = mysqli_query($conn, $query);
		while ($row = mysqli_fetch_array($result)) {
			if (isset($_REQUEST['components']) && $_REQUEST['components'] == 'topmanager') {
				$store_id[] = $row[0];
				$store_name[] = $row[1];
			} else {
				if ($row[0] != $store) {
					$store_id[] = $row[0];
					$store_name[] = $row[1];
				}
			}

		}
	}
}

function processInventoryNew($item, $lastitem)
{
	$store = $_COOKIE['store'];
	$nt_id = '';

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
		$query3 = "UPDATE `inventory_qty` SET `w_price`='$nt_wprice',`r_price`='$nt_rprice',`c_price`='$nt_cprice',`qty`='$nt_qty' WHERE `id`='$itq_itqid'";
		$result3 = mysqli_query($conn, $query3);
		if ($result3) {
			$query3 = "DELETE FROM `inventory_new` WHERE `id` = '$nt_id'";
			$result3 = mysqli_query($conn, $query3);
		}
		$query4 = "UPDATE `transfer` SET `no_update`='1' WHERE `id`='$lastitem'";
		mysqli_query($conn, $query4);
	}
}

function newGTN()
{
	global $message, $gtn_no;
	$salesman = $_COOKIE['user_id'];
	$fromstore = $_COOKIE['store'];
	$gtn_no = '';

	include('config.php');

	$result = mysqli_query($conn, "SELECT MAX(gtn_no) as trmainmax FROM transfer_main");
	$row = mysqli_fetch_assoc($result);
	$gtn_no = $row['trmainmax'];
	if ($gtn_no == '') {
		$result = mysqli_query($conn, "SELECT MAX(gtn_no) as transmax FROM transfer");
		$row = mysqli_fetch_assoc($result);
		$gtn_no = $row['transmax'];
	}
	if ($gtn_no == '') {
		$gtn_no = 1;
	}

	$result = mysqli_query($conn, "SELECT COUNT(id) as `count` FROM transfer WHERE gtn_no='$gtn_no'");
	$row = mysqli_fetch_assoc($result);
	$tr_count = $row['count'];
	if ($tr_count == 0) {
		$query2 = "DELETE FROM `transfer_main` WHERE `gtn_no` = '$gtn_no'";
		$result2 = mysqli_query($conn, $query2);
	} else {
		$gtn_no = $gtn_no + 1;
	}
	$query = "INSERT INTO `transfer_main` (`gtn_no`,`from_store`,`user`,`status`) VALUES ('$gtn_no','$fromstore','$salesman','4')";
	$result3 = mysqli_query($conn, $query);
	if ($result3) {
		$message = '';
		return true;
	} else {
		$message = 'Bill could not be Created!';
		return false;
	}
}

// updated by nirmal 21_04_2025 (added new transfer price column for system id 26)
function apendGTN()
{
	global $message, $tostore, $gtn_no;
	$gtn_no = $_POST['id'];
	$itemid = $_REQUEST['itemid'];
	$qty = $_REQUEST['qty'];
	$salesman = $_REQUEST['salesman'];
	$fromstore = $_COOKIE['store'];
	$tostore = $_REQUEST['remotestore'];
	$unic_item = $_REQUEST['unic_item'];
	$trans_price_value = NULL;

	$time_now = timeNow();
	$unic_cal = unicCal();
	$systemid = inf_systemid(1);
	$sn_list = $msg = '';
	$proceed = true;
	include('config.php');
	$result = mysqli_query($conn, "SELECT COUNT(gtn_no) as `count` FROM transfer_main WHERE user='$salesman' AND gtn_no='$gtn_no'");
	$row = mysqli_fetch_assoc($result);
	$tm_exist = $row['count'];
	if ($tm_exist == 0) {
		newGTN();
	}
	$query = "SELECT to_store,`status` FROM transfer_main WHERE user='$salesman' AND gtn_no='$gtn_no'";
	$result = mysqli_query($conn, $query);
	$row = mysqli_fetch_assoc($result);
	$tm_to_store = $row['to_store'];
	$tm_status = $row['status'];

	$query = "SELECT  itq.id,itq.qty,itq.w_price,itq.r_price,itq.c_price FROM inventory_qty itq WHERE itq.location='$fromstore' AND itq.item='$itemid'";
	$row = mysqli_fetch_row(mysqli_query($conn, $query));
	$inventory_id = $row[0];
	$inventory_qty = $row[1];
	$itq_w_price = $row[2];
	$itq_r_price = $row[3];
	$itq_c_price = $row[4];

	if (isset($_POST['new_c_price']) && $_POST['new_c_price'] !== '') {
		$trans_price_value = $_POST['new_c_price'];
	} else {
		if ($systemid == 26) {
			$trans_price_value = $itq_c_price;
		}
	}
	if ($trans_price_value === NULL) {
		$sql_trans_price = 'NULL';
	} else {
		if (is_numeric($trans_price_value)) {
			$sql_trans_price = "'" . (float) $trans_price_value . "'";
		} else {
			$sql_trans_price = 'NULL';
		}
	}

	if (($proceed) && ((!$unic_cal) || (($unic_cal) && ($unic_item == 0)))) {
		if (mismatch($inventory_id)) {
			$proceed = true;
		} else {
			$proceed = false;
			$msg = 'Error 107. Please contact Support !';
		}
	}
	if (($proceed) && ((!$unic_cal) || (($unic_cal) && ($unic_item == 0)))) {
		if ($inventory_qty >= $qty) {
			$proceed = true;
		} else {
			$proceed = false;
			$msg = 'Insufficient Quantity in the Inventory!';
		}
	}
	if ($proceed) {
		if ($tm_status == 4) {
			$proceed = true;
		} else {
			$proceed = false;
			$msg = 'Cannot Add a Item to a Finalyze Transfer !';
		}
	}

	if ($proceed) {
		if ($tm_to_store == 0) {
			$query = "UPDATE `transfer_main` SET `to_store`='$tostore',`date`='$time_now' WHERE gtn_no='$gtn_no'";
			$result = mysqli_query($conn, $query);
		}
		if ($systemid == 26) {
			$query = "INSERT INTO `transfer` (`gtn_no`,`item`,`w_price`,`r_price`,`c_price`,`qty`,`trans_price`) VALUES ('$gtn_no','$itemid','$itq_w_price','$itq_r_price','$itq_c_price','$qty', $sql_trans_price)";
		} else {
			$query = "INSERT INTO `transfer` (`gtn_no`,`item`,`w_price`,`r_price`,`c_price`,`qty`) VALUES ('$gtn_no','$itemid','$itq_w_price','$itq_r_price','$itq_c_price','$qty')";
		}
		$result = mysqli_query($conn, $query);
		$lastitem = mysqli_insert_id($conn);

		if ($unic_item == 0)
			$debug_id = debugStart($itemid, $qty);
		if ($result) {
			$result2 = mysqli_query($conn, "SELECT count(id) as `count` FROM transfer WHERE item='$itemid' AND gtn_no='$gtn_no'");
			$row2 = mysqli_fetch_assoc($result2);
			$duplicate_item = $row2['count'];
			if ($duplicate_item > 1) {
				$query2 = "UPDATE `transfer` SET `no_update`='2' WHERE item='$itemid' AND gtn_no='$gtn_no' AND no_update='0'";
				$result2 = mysqli_query($conn, $query2);
			}

			if ($unic_item == 1) {
				$qty = $u_w_price = $u_r_price = $u_c_price = 0;
				for ($i = 1; $i <= 10; $i++) {
					if ($_REQUEST["unic_item$i"] != '') {
						$unic_item0 = $_REQUEST["unic_item$i"];
						$query = "SELECT COUNT(id),w_price,r_price,c_price FROM inventory_unic_item WHERE sn='$unic_item0' AND itq_id='$inventory_id' AND `status`=0";
						$row = mysqli_fetch_row(mysqli_query($conn, $query));
						$count_u = $row[0];
						if ($count_u == 1) {
							$sn_list = $sn_list . ',' . $unic_item0;
							$u_w_price += $row[1];
							$u_r_price += $row[2];
							$u_c_price += $row[3];
							$qty++;
							$query = "UPDATE `inventory_unic_item` SET `status`='3',`trans_no`='$gtn_no',`trans_id`='$lastitem' WHERE `sn`='$unic_item0' AND `status`=0";
							$result = mysqli_query($conn, $query);
							$query = "UPDATE `transfer` SET `no_update`='2' WHERE `id`='$lastitem'";
							$result = mysqli_query($conn, $query);
						}
					}
				}
				if ($qty > 0) {
					$u_w_price = $u_w_price / $qty;
					$u_r_price = $u_r_price / $qty;
					$u_c_price = $u_c_price / $qty;
				} else {
					$u_w_price = 0;
					$u_r_price = 0;
					$u_c_price = 0;
				}
				$sn_list = ltrim($sn_list, ',');
				if ($unic_cal) {
					$query = "UPDATE `transfer` SET `qty`='$qty',`w_price`='$u_w_price',`r_price`='$u_r_price',`c_price`='$u_c_price',`sn_list`='$sn_list' WHERE `id`='$lastitem'";
				} else {
					$query = "UPDATE `transfer` SET `qty`='$qty',`sn_list`='$sn_list' WHERE `id`='$lastitem'";
				}
				$result = mysqli_query($conn, $query);
				$debug_id = debugStart($itemid, $qty);
			}
			if ($qty > 0) {
				if ((!$unic_cal) || (($unic_cal) && ($unic_item == 0))) {
					$new_qty = $inventory_qty - $qty;
					$query2 = "UPDATE `inventory_qty` SET `qty`='$new_qty' WHERE `id`=$inventory_id";
					$result2 = mysqli_query($conn, $query2);

					processInventoryNew($itemid, $lastitem);
					debugEnd($debug_id, 'success');
					$message = 'Item was Added to the GTN!';
					return true;
				} else {
					return true;
				}
			} else {
				$query2 = "DELETE FROM `transfer` WHERE id='$lastitem'";
				$result2 = mysqli_query($conn, $query2);
				debugEnd($debug_id, 'fail');
				$message = 'Invalid Item!';
				return false;
			}
		} else {
			$debug_id = debugStart($itemid, $qty);
			debugEnd($debug_id, 'fail');
			$message = 'Item could not be added!';
			return false;
		}
	} else {
		$message = $msg;
		return false;
	}
}

function updateGTNitem()
{
	global $message, $tostore, $gtn_no;
	$itemid = $_REQUEST['id'];
	$qty = $_REQUEST['qty'];

	include('config.php');
	$query = "SELECT ivq.id,ivq.qty,tr.qty,tr.gtn_no,tm.to_store,tm.`status` FROM inventory_qty ivq, transfer_main tm, transfer tr WHERE tm.gtn_no=tr.gtn_no AND ivq.item=tr.item AND ivq.location=tm.from_store AND tr.id='$itemid'";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$ivq_id = $row[0];
		$ivq_qty = $row[1];
		$tr_qty = $row[2];
		$gtn_no = $row[3];
		$tostore = $row[4];
		$tm_status = $row[5];
	}

	if ($tm_status == 4) {
		$new_ivq_qty = $ivq_qty + $tr_qty - $qty;
		if (($ivq_qty + $tr_qty) >= $qty) {
			$query = "UPDATE `transfer` SET `qty`='$qty' WHERE `id`=$itemid";
			$result = mysqli_query($conn, $query);
			if ($result) {
				$query2 = "UPDATE `inventory_qty` SET `qty`='$new_ivq_qty' WHERE `id`=$ivq_id";
				$result2 = mysqli_query($conn, $query2);

				$message = 'Item QTY was Updated!';
				return true;
			} else {
				$message = 'Item could not be Updated!';
				return false;
			}
		} else {
			$message = 'Insufficient Quantity in the Inventory!';
			return false;
		}
	} else {
		$message = 'Cannot Update a Item from Finalyze Transfer!';
		return false;
	}
}

function removeGTNitem()
{
	global $message, $tostore, $gtn_no;
	$itemid = $_REQUEST['id'];
	$store = $_COOKIE['store'];
	$unic_cal = unicCal();
	$proceed = true;
	$inventory_id = '';

	include('config.php');
	$query = "SELECT ivq.id,ivq.qty,tr.qty,tm.gtn_no,tm.to_store,tr.w_price,tr.r_price,tr.c_price,ivq.w_price,ivq.r_price,ivq.c_price,ivq.item,tr.no_update,tm.`status`,itm.unic
	FROM inventory_items itm, inventory_qty ivq, transfer_main tm, transfer tr
	WHERE itm.id=ivq.item AND tm.gtn_no=tr.gtn_no AND ivq.item=tr.item AND ivq.location=tm.from_store AND tr.id='$itemid'";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$ivq_id = $row[0];
		$ivq_qty = $row[1];
		$tr_qty = $row[2];
		$gtn_no = $row[3];
		$tostore = $row[4];
		$tr_wprice = $row[5];
		$tr_rprice = $row[6];
		$tr_cprice = $row[7];
		$ivq_wprice = $row[8];
		$ivq_rprice = $row[9];
		$ivq_cprice = $row[10];
		$ivq_item = $row[11];
		$bi_noupdate = $row[12];
		$tr_status = $row[13];
		$unic = $row[14];
		$inventory_id = $row[0];
	}

	if (($proceed) && ((!$unic_cal) || (($unic_cal) && ($unic == 0)))) {
		if (mismatch($inventory_id)) {
			$proceed = true;
		} else {
			$proceed = false;
			$msg = 'Error 107. Please contact Support !';
		}
	}
	if ($proceed) {
		if ($tr_status == 4) {
			$proceed = true;
		} else {
			$proceed = false;
			$msg = 'Cannot Remove a Item from Finalyze Transfer!';
		}
	}

	if ($proceed) {
		if ((!$unic_cal) || (($unic_cal) && ($unic == 0))) {
			if (($bi_noupdate == 0) || ($bi_noupdate == 2)) {
				$new_ivq_qty = $ivq_qty + $tr_qty;
				$query = "UPDATE `inventory_qty` SET `qty`='$new_ivq_qty' WHERE `id`=$ivq_id";
				$result = mysqli_query($conn, $query);
			} else {
				$query = "INSERT INTO `inventory_new` (`item`,`w_price`,`r_price`,`c_price`,`qty`,`store`) VALUES ('$ivq_item','$ivq_wprice','$ivq_rprice','$ivq_cprice','$ivq_qty','$store')";
				$result2 = mysqli_query($conn, $query);
				$query = "UPDATE `inventory_qty` SET `w_price`='$tr_wprice',`r_price`='$tr_rprice',`c_price`='$tr_cprice',`qty`='$tr_qty' WHERE `id`=$ivq_id";
				$result = mysqli_query($conn, $query);
			}
		} else {
			$result = true;
		}

		$query2 = "DELETE FROM `transfer` WHERE `id` = '$itemid'";
		$result2 = mysqli_query($conn, $query2);
		if ($result && $result2) {
			$query2 = "UPDATE `inventory_unic_item` SET `trans_no`='0',`trans_id`='0',`status`='0' WHERE `trans_id`='$itemid' AND `status`='3'";
			$result2 = mysqli_query($conn, $query2);
			$message = 'Item was Removed from GTN!';
			return true;
		} else {
			$message = 'Item could not be Removed!';
			return false;
		}
	} else {
		$message = $msg;
		return false;
	}
}

// updated by nirmal 26_11_2025 (added gtn comment select)
function getGTNItems()
{
	global $gtn_itemid, $gtn_desc, $gtn_desc2, $gtn_qty, $gtn_drawer, $gtn_no_update, $dups, $dups_count,
	$gtn_comment;
	$gtn_itemid = array();
	if (isset($_REQUEST['id'])) {
		$gtn_no = $_REQUEST['id'];
		$salesman = $_COOKIE['user_id'];
		$total = 0;
		$dups = $dups_count = $gtn_desc = $gtn_desc2 = array();
		include('config.php');

		$query = "SELECT tr.id, itm.description, tr.qty, itq.drawer_no, tr.no_update, tm.`comment`
		FROM transfer_main tm, transfer tr, inventory_items itm, inventory_qty itq, userprofile up
		WHERE tm.gtn_no=tr.gtn_no AND tr.item=itm.id AND itm.id=itq.item AND up.store=itq.location
		AND up.id='$salesman' AND tm.`user`='$salesman' AND tm.gtn_no='$gtn_no'";

		$result = mysqli_query($conn, $query);
		while ($row = mysqli_fetch_array($result)) {
			$trn_id_tmp = $row[0];
			$gtn_itemid[] = $row[0];
			$gtn_desc[] = $row[1];
			$gtn_desc2[] = $row[1];
			$gtn_qty[] = $row[2];
			$gtn_drawer[] = $row[3];
			$gtn_no_update[] = $row[4];
			$gtn_comment = $row[5];
		}
		foreach (array_count_values($gtn_desc2) as $val => $c)
			if ($c > 1) {
				$dups[] = $val;
				$dups_count[] = $c;
			}
	}
}

// added by nirmal 26_11_2025
function updateGtnComment()
{
	global $message, $tostore, $gtn_no;
	include('config.php');
	$out = true;
	$comment = '';

	if (isset($_POST['gtn_no'])) {
		$gtn_no = $_POST['gtn_no'];
	} else {
		$out = false;
		$message = "Error: GTN no cannot be null";
	}

	if (isset($_POST['remotestore'])) {
		$tostore = $_POST['remotestore'];
	} else {
		$out = false;
		$message = "Error: Remote store cannot be null";
	}

	if (isset($_POST['comment'])) {
		$comment = $_POST['comment'];
	} else {
		$out = false;
		$message = "Error: Comment cannot be null";
	}

	if ($out) {
		$query = "UPDATE `transfer_main` SET `comment`='$comment' WHERE gtn_no='$gtn_no'";
		$result = mysqli_query($conn, $query);
		if (!$result) {
			$out = false;
			$message = "Error: GTN comment could not be updated";
		} else {
			$message = 'GTN comment updated succesfully';
		}
	}

	return $out;
}

// update by nirmal 21_11_23, 14_12_2023 (added emp_name instead username for sysid = 13)
// updated by nirmal 21_04_25 (added trans_price select for system id 26, instead of c_price)
// update by nirmal 26_11_2025 (added show gtn comment)
function generateGTN()
{
	global $gtn_no, $gtn_item_id, $gtn_item_des, $gtn_item_qty, $gtn_item_draw, $gtn_item_from, $gtn_item_to, $gtn_date, $gtn_from_user,
	$gtn_to_user, $gtn_c_price, $gtn_cross_invoice, $total_cost, $gtn_status, $gtn_from_shop_name, $gtn_item_unit, $gtn_comment;
	$gtn_no = $_REQUEST['id'];
	$user_id = $_COOKIE['user_id'];
	$gtn_from = $gtn_to = '';
	$total_cost = 0;
	$sn_list = $gtn_item_unit = array();
	$isMobile = isMobile();
	$systemid = inf_systemid(2);
	if ($isMobile) {
		include('config.php');
	} else {
		include('../../../../config.php');
	}
	$result = mysqli_query($conn, "SELECT `value` FROM settings WHERE setting='paper_size'");
	$row = mysqli_fetch_assoc($result);
	$paper_size = $row['value'];
	if ($paper_size == 'A4')
		$break_point = 3;
	if ($paper_size == 'A5')
		$break_point = 2;
	if ($isMobile)
		$break_point = 1;

	$user_name = 'up.username';
	if ($systemid == 13) {
		$user_name = 'up.emp_name';
	}

	$query = "SELECT up.`id`,$user_name FROM userprofile up";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$user_idlist[] = $row[0];
		$user_namelist[] = $row[1];
	}

	$query = "SELECT `id`, `name` FROM stores";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$store_id[] = $row[0];
		$store_name[] = $row[1];
	}

	// Determine the column name to select for the price based on systemid
	$price_column_name = 'tr.c_price'; // Default price column name
	if ($systemid == 26) {
		$price_column_name = 'tr.trans_price'; // Use trans_price if systemid is 26
	}

	$query = "SELECT tr.`id`, inv.`description`, tr.`qty`, itq.`drawer_no`, tm.`from_store`, tm.`to_store`, DATE(tm.`date`), $user_name, tm.`remote_user`,
	 $price_column_name, tm.`status`, tr.`sn_list`, inv.`unic`, tm.`invoice_no`, inv.`unit`, tm.`comment`
	FROM transfer_main tm, transfer tr, inventory_items inv, inventory_qty itq, userprofile up
	WHERE tm.`gtn_no`=tr.`gtn_no` AND tm.`user`=up.`id` AND tr.`item`=inv.`id` AND inv.`id`=itq.`item` AND itq.`location`=tm.`from_store` AND tm.`gtn_no`='$gtn_no'";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$trn_id_tmp = $row[0];
		$gtn_item_id[] = $row[0];
		if ($row[12] == 1) {
			$unic_sn = '';
			$k = 1;
			$sn_list = explode(",", $row[11]);
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
			$gtn_item_des[] = $row[1] . '<br />' . $unic_sn . '<br /><br />';
		} else {
			$gtn_item_des[] = $row[1] . '<br />';
		}
		$gtn_item_qty[] = $row[2];
		$gtn_item_draw[] = $row[3];
		$gtn_from = $row[4];
		$gtn_to = $row[5];
		$gtn_date = $row[6];
		$gtn_from_user = $row[7];
		$gtn_remote_user = $row[8];
		$gtn_c_price[] = $row[9];
		$gtn_cross_invoice = $row[13];
		$total_cost += $row[2] * $row[9];
		$status = json_decode(trnsStatus($row[10]));
		$gtn_status = $status->{"status"};
		if ($row[14] != '') {
			$query1 = "SELECT `type` FROM unit_types WHERE `id`=$row[14]";
			$row1 = mysqli_fetch_row(mysqli_query($conn2, $query1));
			if (!empty($row1)) {
				$gtn_item_unit[] = $row1[0];
			} else {
				$gtn_item_unit[] = '';
			}
		} else {
			$gtn_item_unit[] = '';
		}
		$gtn_comment = $row[15];
	}

	$query = "SELECT `shop_name` FROM stores WHERE `id`='$gtn_from'";
	$result = mysqli_query($conn, $query);
	$row = mysqli_fetch_assoc($result);
	$gtn_from_shop_name = $row['shop_name'];

	if (($gtn_status == 'Accepted') || ($gtn_status == 'Rejected')) {
		$key = array_search($gtn_remote_user, $user_idlist);
		$gtn_to_user = $user_namelist[$key];
	} else if ($gtn_status == 'Pending') {
		$key = array_search($user_id, $user_idlist);
		$gtn_to_user = $user_namelist[$key];
	} else {
		$gtn_to_user = '..............................';
	}

	$key = array_search($gtn_from, $store_id);
	$gtn_item_from = $store_name[$key];

	$key = array_search($gtn_to, $store_id);
	$gtn_item_to = $store_name[$key];
}

function pickGTN()
{
	global $message, $gtn_no, $tostore;
	$gtn_no = $_GET['id'];
	$fromstore = $_COOKIE['store'];
	$user = $_COOKIE['user_id'];
	$gtn_no2 = '';
	include('config.php');
	$query = "SELECT gtn_no,to_store FROM transfer_main WHERE gtn_no='$gtn_no' AND `status`='5' AND `from_store`='$fromstore'";
	$result = mysqli_query($conn, $query);
	$row = mysqli_fetch_assoc($result);
	$gtn_no2 = $row['gtn_no'];
	$tostore = $row['to_store'];
	if ($gtn_no2 > 0) {
		$query2 = "UPDATE `transfer_main` SET `user`='$user',`status`='4' WHERE gtn_no='$gtn_no2'";
		$result2 = mysqli_query($conn, $query2);
	}
	if ($result2) {
		$message = '';
		return true;
	} else {
		$message = 'GTN could not be Picked!';
		return false;
	}
}

// updated by nirmal 20_01_2025 (added, if user has top_manager permission show all sub systems today transfers)
function getGTNlist($type)
{
	global $gtn_no, $date, $time, $gtn_status, $gtn_color, $user, $salesman, $gtn_total_w_price, $gtn_total_c_price, $gtn_to_storeid,
	$gtn_from_store, $gtn_to_store, $approve_permission, $gtn_remote_user, $gtn_cross_invoice, $cross_inv;
	$gtn_no = array();
	if (isset($_REQUEST['date'])) {
		$todaydate = $_REQUEST['date'];
	} else {
		$todaydate = dateNow();
	}
	$user = $_COOKIE['user_id'];
	$up_name[''] = '';
	$total = 0;
	$cross_inv = false;
	include('config.php');

	$query = "SELECT id,name FROM stores";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$st_name[$row[0]] = $row[1];
	}
	$query = "SELECT id,username FROM userprofile";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$up_name[$row[0]] = $row[1];
	}

	if ($type == 'today') {
		if (isset($_COOKIE['top_manager'])) {
			$query = "SELECT tm.gtn_no,DATE(tm.`date`) AS transfer_date,TIME(tm.`date`) AS transfer_time,tm.`status`,tm.`user`,SUM(tr.qty * tr.w_price) AS total_weighted_price,SUM(tr.qty * tr.c_price) AS total_cost_price,tm.to_store,tm.from_store,tm.remote_user,tm.invoice_no,
			ss_to.name AS to_store_subsystem, ss_from.name AS from_store_subsystem
    				FROM transfer_main tm INNER JOIN transfer tr ON tm.gtn_no = tr.gtn_no
    				INNER JOIN inventory_qty itq ON tr.item = itq.item INNER JOIN inventory_items itm ON itm.id = itq.item
					INNER JOIN userprofile up ON up.store = tm.from_store AND up.store = itq.location
					INNER JOIN stores s_to ON tm.to_store = s_to.id INNER JOIN stores s_from ON tm.from_store = s_from.id
    				INNER JOIN sub_system ss_to ON s_to.sub_system = ss_to.id INNER JOIN sub_system ss_from ON s_from.sub_system = ss_from.id
    				WHERE up.id = '$user' AND tm.`status` IN (0, 1, 2, 3, 4) AND tm.`date` LIKE '$todaydate%'
    				GROUP BY tm.gtn_no ORDER BY tm.gtn_no DESC";
		} else {
			$query = "SELECT tm.gtn_no, date(tm.`date`), time(tm.`date`),tm.`status`,tm.`user`,SUM(tr.qty*tr.w_price),SUM(tr.qty*tr.c_price),tm.to_store,tm.from_store,tm.remote_user,tm.invoice_no
			FROM transfer_main tm, transfer tr, inventory_qty itq, inventory_items itm, userprofile up
			WHERE tm.gtn_no=tr.gtn_no AND itm.id=itq.item AND up.store=tm.from_store AND up.store=itq.location
			AND tr.item=itq.item AND up.id='$user' AND tm.`status` IN (0,1,2,3,4)
			AND  tm.`date` LIKE '$todaydate%' GROUP BY tm.gtn_no ORDER BY tm.gtn_no DESC";
		}
	}
	if ($type == 'last100') {
		$query = "SELECT tm.gtn_no, date(tm.`date`), time(tm.`date`),tm.`status`,tm.`user`,SUM(tr.qty*tr.w_price),SUM(tr.qty*tr.c_price),tm.to_store,tm.from_store,tm.remote_user,tm.invoice_no
		FROM transfer_main tm, transfer tr, inventory_qty itq, inventory_items itm, userprofile up
		WHERE tm.gtn_no=tr.gtn_no AND itm.id=itq.item AND up.store=tm.from_store AND up.store=itq.location AND tr.item=itq.item AND up.id='$user' AND tm.`status` IN (0,1,2,3,4)
		GROUP BY tm.gtn_no ORDER BY tm.gtn_no DESC LIMIT 100";
	}
	if ($type == 'approval') {
		$query = "SELECT tm.gtn_no, date(tm.`date`), time(tm.`date`),tm.`status`,tm.`user`,SUM(tr.qty*tr.w_price),SUM(tr.qty*tr.c_price),tm.to_store,tm.from_store,tm.remote_user,tm.invoice_no
		FROM transfer_main tm, transfer tr, inventory_items itm, userprofile up
		WHERE tm.gtn_no=tr.gtn_no AND up.store=tm.to_store AND tr.item=itm.id AND up.id='$user' AND tm.`status`='0'
		GROUP BY tm.gtn_no ORDER BY tm.gtn_no DESC";
	}

	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$gtn_no[] = $row[0];
		$date[] = $row[1];
		$time[] = substr($row[2], 0, 5);
		$salesman[] = $row[4];
		$gtn_total_w_price[] = $row[5];
		$gtn_total_c_price[] = $row[6];
		$gtn_to_storeid[] = $row[7];
		$gtn_to_store[] = $st_name[$row[7]];
		$gtn_from_store[] = $st_name[$row[8]];
		$gtn_remote_user[] = $up_name[$row[9]];
		$gtn_cross_invoice[] = $row[10];
		if ($row[10] > 0)
			$cross_inv = true;
		$status = json_decode(trnsStatus($row[3]));
		$gtn_status_temp = $status->{"status"};
		$gtn_status[] = $status->{"status"};
		$gtn_color[] = $status->{"color"};
		if ($type == 'approval')
			$approve_permission[] = 1;
		else
			$approve_permission[] = 0;
	}

	if ($type == 'approval') {
		$query = "SELECT tm.gtn_no, date(tm.`date`), time(tm.`date`),tm.`status`,tm.`user`,SUM(tr.qty*tr.w_price),SUM(tr.qty*tr.c_price),tm.to_store,tm.from_store,tm.remote_user,tm.invoice_no
		FROM transfer_main tm, transfer tr, inventory_items itm, userprofile up
		WHERE tm.gtn_no=tr.gtn_no AND up.store=tm.from_store AND tr.item=itm.id AND up.id='$user' AND tm.`status`='5' GROUP BY tm.gtn_no ORDER BY tm.gtn_no DESC";
		$result = mysqli_query($conn, $query);
		while ($row = mysqli_fetch_array($result)) {
			$gtn_no[] = $row[0];
			$date[] = $row[1];
			$time[] = substr($row[2], 0, 5);
			$salesman[] = $row[4];
			$gtn_total_w_price[] = $row[5];
			$gtn_total_c_price[] = $row[6];
			$gtn_to_storeid[] = $row[7];
			$gtn_to_store[] = $st_name[$row[7]];
			$gtn_from_store[] = $st_name[$row[8]];
			$gtn_remote_user[] = $up_name[$row[9]];
			$gtn_cross_invoice[] = $row[10];
			if ($row[10] > 0)
				$cross_inv = true;
			$status = json_decode(trnsStatus($row[3]));
			$gtn_status_temp = $status->{"status"};
			$gtn_status[] = $status->{"status"};
			$gtn_color[] = $status->{"color"};
			$approve_permission[] = 0;
		}
	}
}

function setStatusGTN()
{
	global $gtn_no, $tostore;
	$gtn_no = $_REQUEST['id'];
	$tostore = $_REQUEST['remotestore'];
	$user = $_COOKIE['user_id'];
	include('config.php');
	$query = "UPDATE `transfer_main` SET `status`='4' WHERE `user`='$user' AND `status`='0' AND `gtn_no`='$gtn_no'";
	$result = mysqli_query($conn, $query);
	if ($result) {
		$message = '';
		return true;
	} else {
		$message = 'GTN could not be Edited!';
		return false;
	}
}

function finalizeGTN()
{
	global $message, $gtn_no, $tostore;
	$gtn_no = $_REQUEST['id'];
	$user = $_COOKIE['user_id'];
	$result2 = false;
	include('config.php');

	$result = mysqli_query($conn, "SELECT `status`,to_store FROM transfer_main WHERE gtn_no='$gtn_no'");
	$row = mysqli_fetch_assoc($result);
	$tm_status = $row['status'];
	$tostore = $row['to_store'];

	if ($tm_status == 4) {
		$query2 = "UPDATE `transfer_main` SET `status`='0' WHERE `user`='$user' AND `gtn_no`='$gtn_no'";
		$result2 = mysqli_query($conn, $query2);
	}
	if ($result2) {
		$message = '';
		return true;
	} else {
		$message = 'GTN could not be Finalize!';
		return false;
	}
}

function gtnOwner()
{
	global $gtnowner_id, $gtnowner_name, $gtnremote_name, $gtnowner_status, $gtnowner_crossinv, $trans_delete;
	$gtn_no = $_REQUEST['id'];
	$user_id = $_COOKIE['user_id'];
	$user_arr = array();
	$user_arr[''] = '';
	$st_delete = false;

	include('config.php');
	$query = "SELECT id,username FROM userprofile";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$user_arr[$row[0]] = $row[1];
	}
	$query = "SELECT tm.`user`,tm.remote_user,tm.`status`,tm.invoice_no FROM transfer_main tm WHERE tm.gtn_no='$gtn_no'";
	$row = mysqli_fetch_row(mysqli_query($conn, $query));
	if (!empty($row)) {
		$gtnowner_id = $row[0];
		$gtnowner_name = $user_arr[$row[0]];
		$gtnremote_name = $user_arr[$row[1]];
		$gtnowner_status = $row[2];
		if (($row[2] == 5) && ($row[3] != 0))
			$gtnowner_crossinv = true;
		else
			$gtnowner_crossinv = false;
	}

	switch ($gtnowner_status) {
		case 0:
			$st_delete = true;
			break;
		case 4:
			$st_delete = true;
			break;
		case 5:
			$st_delete = true;
			break;
		default:
			$st_delete = false;
			break;
	}

	if (($gtnowner_id == $user_id) && ($st_delete)) {
		$trans_delete = true;
	} else {
		$trans_delete = false;
	}

}

function authorizeGTN($gtn_no, $user)
{
	include('config.php');
	$query1 = "SELECT `to_store`,`status` FROM transfer_main WHERE gtn_no='$gtn_no'";
	$result1 = mysqli_query($conn, $query1);
	while ($row1 = mysqli_fetch_array($result1)) {
		$to_store = $row1[0];
		$gtn_status = $row1[1];
	}

	$query1 = "SELECT store FROM userprofile WHERE id='$user'";
	$result1 = mysqli_query($conn, $query1);
	while ($row1 = mysqli_fetch_array($result1)) {
		$user_store = $row1[0];
	}

	if (($to_store == $user_store) && ($gtn_status == 0))
		return true;
	else
		return false;
}

function approveGTN()
{
	global $message;
	$gtn_no = $_REQUEST['id'];
	$salesman = $_COOKIE['user_id'];
	$unic_cal = unicCal();
	$ivq_id = $ivq_qty = 0;
	$result4 = false;
	$datetime = timeNow();
	if (authorizeGTN($gtn_no, $salesman)) {
		include('config.php');
		$query1 = "SELECT tm.from_store,tm.to_store,tr.item,tr.qty,tr.w_price,tr.r_price,tr.c_price,tr.id,itm.unic FROM transfer_main tm, transfer tr, inventory_items itm WHERE tm.gtn_no=tr.gtn_no AND tr.item=itm.id AND tm.gtn_no='$gtn_no'";
		$result1 = mysqli_query($conn, $query1);
		while ($row1 = mysqli_fetch_array($result1)) {
			$gtn_from_store = $row1[0];
			$gtn_to_store = $row1[1];
			$gtn_item = $row1[2];
			$gtn_qty = $row1[3];
			$ivq_from_wprice = $row1[4];
			$ivq_from_rprice = $row1[5];
			$ivq_from_cprice = $row1[6];
			$tr_id = $row1[7];
			$unic = $row1[8];
			$ivq_id = $ivq_qty = 0;

			$query2 = "SELECT id,qty,w_price,r_price,c_price FROM inventory_qty WHERE item='$gtn_item' AND location='$gtn_to_store'";
			$result2 = mysqli_query($conn, $query2);
			while ($row2 = mysqli_fetch_array($result2)) {
				$ivq_id = $row2[0];
				$ivq_qty = $row2[1];
				$ivq_to_wprice = $row2[2];
				$ivq_to_rprice = $row2[3];
				$ivq_to_cprice = $row2[4];
			}
			$debug_id = debugStart($tr_id, $gtn_to_store);
			if ($ivq_id != 0) {
				$new_qty = $gtn_qty + $ivq_qty;
				if ((!$unic_cal) || (($unic_cal) && ($unic == 0))) {
					if (($ivq_qty <= 0) || (($ivq_from_wprice == $ivq_to_wprice) && ($ivq_from_rprice == $ivq_to_rprice) && ($ivq_from_cprice == $ivq_to_cprice))) {
						$query3 = "UPDATE `inventory_qty` SET `w_price`='$ivq_from_wprice',`r_price`='$ivq_from_rprice',`c_price`='$ivq_from_cprice',`qty`='$new_qty' WHERE `id`='$ivq_id'";
						$result3 = mysqli_query($conn, $query3);
					} else {
						$query3 = "SELECT id,qty FROM `inventory_new` WHERE `item`='$gtn_item' AND `w_price`='$ivq_from_wprice' AND `r_price`='$ivq_from_rprice' AND `c_price`='$ivq_from_cprice' AND `store`='$gtn_to_store' LIMIT 1";
						$result3 = mysqli_query($conn, $query3);
						$row3 = mysqli_fetch_assoc($result3);
						$newint_id = $row3['id'];
						$newint_qty = $row3['qty'] + $gtn_qty;

						if ($newint_id != '') {
							$query3 = "UPDATE `inventory_new` SET `qty`='$newint_qty' WHERE id='$newint_id'";
							$result3 = mysqli_query($conn, $query3);
						} else {
							$query3 = "INSERT INTO `inventory_new` (`item`,`w_price`,`r_price`,`c_price`,`qty`,`store`) VALUES ('$gtn_item','$ivq_from_wprice','$ivq_from_rprice','$ivq_from_cprice','$gtn_qty','$gtn_to_store') ";
							$result3 = mysqli_query($conn, $query3);
						}
					}
				} else {
					$result3 = true;
				}
			} else {
				$query3 = "INSERT INTO `inventory_qty` (`item`,`location`,`w_price`,`r_price`,`c_price`,`qty`,`drawer_no`) VALUES ('$gtn_item','$gtn_to_store','$ivq_from_wprice','$ivq_from_rprice','$ivq_from_cprice','$gtn_qty','0')";
				$result3 = mysqli_query($conn, $query3);
			}
			if ($result3) {
				$query2 = "SELECT id FROM inventory_qty WHERE item='$gtn_item' AND location='$gtn_to_store'";
				$result2 = mysqli_query($conn, $query2);
				while ($row2 = mysqli_fetch_array($result2)) {
					$itq_id = $row2[0];
				}
				$query2 = "UPDATE `inventory_unic_item` SET `itq_id`='$itq_id',`status`='0' WHERE `trans_id`='$tr_id' AND `status`='3'";
				$result2 = mysqli_query($conn, $query2);
				debugEnd($debug_id, 'success');
			} else {
				debugEnd($debug_id, 'fail');
			}
		}

		if ($result3) {
			$query4 = "UPDATE `transfer_main` SET `status`='1',`remote_user`='$salesman' ,`action_date`='$datetime' WHERE `gtn_no`='$gtn_no'";
			$result4 = mysqli_query($conn, $query4);
		}
		if ($result4) {
			$message = 'GTN was Accepted Successfully!';
			return true;
		} else {
			$message = 'GTN could not be Accepted!';
			return false;
		}
	} else {
		$message = 'Unauthorize Request';
		return false;
	}
}

function deleteRejectGTN($method)
{
	global $message;
	$gtn_no = $_REQUEST['id'];
	$salesman = $_COOKIE['user_id'];
	$result2 = false;
	$datetime = timeNow();
	$unic_cal = unicCal();
	$out = true;
	include('config.php');

	if ($method == 'reject') {
		$authorization = authorizeGTN($gtn_no, $salesman);
		$qry1 = '';
		$qry2 = "`status`='2',`remote_user`='$salesman'";
		$msg1 = 'Rejected';
	}
	if ($method == 'delete') {
		$authorization = true;
		$qry1 = "AND tr.user='$salesman'";
		$qry2 = "`status`='3'";
		$msg1 = 'Canceled';
	}
	$query = "SELECT `status` FROM transfer_main WHERE gtn_no='$gtn_no'";
	$row = mysqli_fetch_assoc(mysqli_query($conn, $query));
	if ($row['status'] != 0)
		$authorization = false;

	if (!$authorization) {
		$out = false;
		$message = 'Unauthorize Request';
	}
	if ($out) {
		$query1 = "SELECT ivq.id,tr.qty,tm.gtn_no,tm.from_store,tm.to_store,tr.w_price,tr.r_price,tr.c_price,ivq.w_price,ivq.r_price,ivq.c_price,ivq.item,tr.no_update,tr.id,itm.unic FROM inventory_items itm, inventory_qty ivq, transfer_main tm, transfer tr WHERE itm.id=ivq.item AND tm.gtn_no=tr.gtn_no AND ivq.item=tr.item AND ivq.location=tm.from_store AND tm.`gtn_no`='$gtn_no' ORDER BY tr.id DESC";
		$result1 = mysqli_query($conn, $query1);
		while ($row1 = mysqli_fetch_array($result1)) {
			$ivq_id = $row1[0];
			$tr_qty = $row1[1];
			$gtn_no = $row1[2];
			$fromstore = $row1[3];
			$tostore = $row1[4];
			$tr_wprice = $row1[5];
			$tr_rprice = $row1[6];
			$tr_cprice = $row1[7];
			$ivq_wprice = $row1[8];
			$ivq_rprice = $row1[9];
			$ivq_cprice = $row1[10];
			$ivq_item = $row1[11];
			$bi_noupdate = $row1[12];
			$tr_id = $row1[13];
			$unic = $row1[14];

			$debug_id = debugStart($tr_id, $fromstore);
			if ((!$unic_cal) || (($unic_cal) && ($unic == 0))) {
				if (($bi_noupdate == 0) || ($bi_noupdate == 2)) {
					$query7 = "UPDATE `inventory_qty` SET `qty`=qty+$tr_qty WHERE `id`='$ivq_id'";
					$result7 = mysqli_query($conn, $query7);
					if (!$result7) {
						$out = false;
					}
				} else {
					$result = mysqli_query($conn, "SELECT qty FROM inventory_qty WHERE `id`=$ivq_id");
					$row = mysqli_fetch_assoc($result);
					$ivq_qty = $row['qty'];
					$query = "INSERT INTO `inventory_new` (`item`,`w_price`,`r_price`,`c_price`,`qty`,`store`) VALUES ('$ivq_item','$ivq_wprice','$ivq_rprice','$ivq_cprice','$ivq_qty','$fromstore')";
					$result2 = mysqli_query($conn, $query);
					$query7 = "UPDATE `inventory_qty` SET `w_price`='$tr_wprice',`r_price`='$tr_rprice',`c_price`='$tr_cprice',`qty`='$tr_qty' WHERE `id`=$ivq_id";
					$result7 = mysqli_query($conn, $query7);
					if (!$result7) {
						$out = false;
					}
				}
			}

			if (($out) && ($unic == 1)) {
				$query2 = "UPDATE `inventory_unic_item` SET `trans_no`='0',`trans_id`='0',`status`='0' WHERE `trans_id`='$tr_id' AND `status`='3'";
				$result2 = mysqli_query($conn, $query2);
				if (!$result2) {
					$out = false;
				}
			}

			if ($out) {
				debugEnd($debug_id, 'success');
			} else {
				debugEnd($debug_id, 'fail');
			}
		}

		if ($out) {
			$query = "UPDATE `transfer_main` SET $qry2 ,`action_date`='$datetime' WHERE `gtn_no`=$gtn_no";
			$result = mysqli_query($conn, $query);
			if (!$result) {
				$out = false;
			}
		}

		if ($out) {
			$out = true;
			$message = 'GTN was ' . $msg1 . ' Successfully!';
		} else {
			$message = 'GTN could not be ' . $msg1 . '!';
			$out = false;
		}
	}

	return $out;
}

function crossSubmitGTN()
{
	global $message;
	$gtn_no = $_GET['id'];
	$salesman = $_COOKIE['user_id'];
	$time_now = timeNow();
	$out = true;
	include('config.php');
	$message = 'The Item Transfer was Submitted Successfully';

	$query = "SELECT count(gtn_no) AS `count` FROM transfer_main tm, userprofile up WHERE up.store=tm.from_store AND up.id='$salesman' AND tm.`status`='5' AND gtn_no='$gtn_no'";
	$row = mysqli_fetch_assoc(mysqli_query($conn, $query));
	if ($row['count'] == 0) {
		$out = false;
		$message = 'Unauthorized Request !';
	} else {
		$query1 = "UPDATE `transfer_main` SET `date`='$time_now',`status`='0' WHERE `gtn_no`=$gtn_no";
		$result1 = mysqli_query($conn, $query1);
		if (!$result1) {
			$out = false;
			$message = 'Transfer Submission Failed';
		}
	}
	return $out;
}

// updated by nirmal 16_11_23
function getItemInTransfer()
{
	global $gtn_no, $from_store, $to_store, $username, $invoice_no, $description, $qty, $date, $time, $status, $gtn_status_temp, $gtn_status, $gtn_color;
	$gtn_no = $from_store = $to_store = $username = $invoice_no = $description = $qty = $date = $time = $gtn_status = $gtn_color = array();
	include('config.php');
	$query = "SELECT tm.`gtn_no`, st1.`name`, st2.`name`, us.`username`, tm.`invoice_no`, ii.`description`, t.`qty`, DATE(tm.`date`), TIME(tm.`date`), tm.`status`
			FROM `transfer_main` AS tm, `stores` AS st1, `stores` AS st2, `userprofile` AS us, `inventory_items` AS ii, transfer AS t
			WHERE st1.`id` = tm.`to_store` AND st2.`id` = tm.`from_store` AND us.`id` = tm.`USER` AND t.`item` = ii.`id` AND t.`gtn_no` = tm.`gtn_no`
			AND tm.`status` IN ('0','4','5')";

	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$gtn_no[] = $row[0];
		$to_store[] = $row[1];
		$from_store[] = $row[2];
		$username[] = $row[3];
		$invoice_no[] = $row[4];
		$description[] = $row[5];
		$qty[] = $row[6];
		$date[] = $row[7];
		$time[] = substr($row[8], 0, 5);
		$status = json_decode(trnsStatus($row[9]));
		$gtn_status_temp = $status->{"status"};
		$gtn_status[] = $status->{"status"};
		$gtn_color[] = $status->{"color"};
	}

}

// added by nirmal 23_12_2024
function getTransReport($sub_system)
{
	global $gtn_no, $from_store, $to_store, $username, $invoice_no, $description, $qty, $date, $time, $status, $gtn_status_temp, $gtn_status, $gtn_color, $fromdate, $todate;
	$gtn_no = $from_store = $to_store = $username = $invoice_no = $description = $qty = $date = $time = $gtn_status = $gtn_color = array();
	include('config.php');
	$conditions = []; // Array to hold individual conditions

	// Handle sub-system filter
	if (isset($_REQUEST['sub_system']) && $_REQUEST['sub_system'] != '') {
		$sub_system = $_REQUEST['sub_system'];
		$conditions[] = "st1.`sub_system` = '$sub_system'";
		$conditions[] = "st2.`sub_system` = '$sub_system'";
	} elseif ($sub_system != 'all') {
		$sub_system = $_COOKIE['sub_system'];
		$conditions[] = "st1.`sub_system` = '$sub_system'";
		$conditions[] = "st2.`sub_system` = '$sub_system'";
	}

	// Handle from store filter
	if (isset($_REQUEST['from_store']) && $_REQUEST['from_store'] != '') {
		$from_store_id = $_REQUEST['from_store'];
		$conditions[] = "tm.`from_store` = '$from_store_id'";
	}

	// Handle to store filter
	if (isset($_REQUEST['to_store']) && $_REQUEST['to_store'] != '') {
		$to_store_id = $_REQUEST['to_store'];
		$conditions[] = "tm.`to_store` = '$to_store_id'";
	}

	// Handle salesman filter
	if (isset($_REQUEST['salesman']) && $_REQUEST['salesman'] != '') {
		$salesman = $_REQUEST['salesman'];
		$conditions[] = "tm.`user` = '$salesman'";
	}

	// Set date filters
	if (isset($_GET['datefrom']) && $_GET['datefrom'] != '') {
		$fromdate = $_GET['datefrom'];
	} else {
		$fromdate = date('Y-m-d', strtotime('-1 month')); // Default to one month back
	}

	if (isset($_GET['dateto']) && $_GET['dateto'] != '') {
		$todate = $_GET['dateto'];
	} else {
		$todate = date('Y-m-d'); // Default to today's date
	}

	if (!empty($fromdate) && !empty($todate)) {
		$conditions[] = "DATE(tm.date) BETWEEN '$fromdate' AND '$todate'";
	} elseif (!empty($fromdate)) {
		$conditions[] = "DATE(tm.date) >= '$fromdate'";
	}

	// Handle status filter
	if (isset($_REQUEST['status']) && $_REQUEST['status'] != '') {
		$status = $_REQUEST['status'];
		$conditions[] = "tm.`status` = '$status'";
	}

	// Combine all conditions into a single WHERE clause
	$whereClause = '';
	if (!empty($conditions)) {
		$whereClause = 'WHERE ' . implode(' AND ', $conditions);
	}

	$query = "SELECT tm.`gtn_no`, st1.`name` AS to_store_name, st2.`name` AS from_store_name, us.`username`, tm.`invoice_no`, ii.`description`, t.`qty`, DATE(tm.`date`) AS transfer_date, TIME(tm.`date`) AS transfer_time, tm.`status`
			FROM `transfer_main` AS tm
			LEFT JOIN `stores` AS st1 ON st1.`id` = tm.`to_store`
			LEFT JOIN `stores` AS st2 ON st2.`id` = tm.`from_store`
			LEFT JOIN `userprofile` AS us ON us.`id` = tm.`USER`
			LEFT JOIN `transfer` AS t ON t.`gtn_no` = tm.`gtn_no`
			LEFT JOIN `inventory_items` AS ii ON t.`item` = ii.`id`
			LEFT JOIN `sub_system` AS ss ON ss.`id` = st1.`sub_system`
			$whereClause ORDER BY tm.`gtn_no` DESC";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$gtn_no[] = $row[0];
		$to_store[] = $row[1];
		$from_store[] = $row[2];
		$username[] = $row[3];
		$invoice_no[] = $row[4];
		$description[] = $row[5];
		$qty[] = $row[6];
		$date[] = $row[7];
		$time[] = substr($row[8], 0, 5);
		$status = json_decode(trnsStatus($row[9]));
		$gtn_status_temp = $status->{"status"};
		$gtn_status[] = $status->{"status"};
		$gtn_color[] = $status->{"color"};
	}
}

// added by nirmal 23_12_2024
function getTransSalesman($sub_system)
{
	global $sm_id, $sm_name;
	if ($sub_system == 'all')
		$sub_system_qry = '';
	else
		$sub_system_qry = "AND `sub_system`='$sub_system'";
	include('config.php');
	$query = "SELECT id,username FROM userprofile WHERE `status`=0 $sub_system_qry ORDER BY username";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$sm_id[] = $row[0];
		$sm_name[] = $row[1];
	}
}

// added by nirmal 23_12_2024
function getTransSubSystem($sub_system)
{
	global $sb_id, $sb_name;
	include('config.php');
	$user_id = $_COOKIE['user_id'];

	if ($sub_system == 'all') {
		$query = "SELECT `id`,`name` FROM sub_system WHERE `status`='1'";
	} else {
		$query = "SELECT s.`id`,s.`name` FROM sub_system s, userprofile u WHERE s.`status`='1' AND s.`id`=u.`sub_system` AND u.`id`='$user_id'";
	}
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$sb_id[] = $row[0];
		$sb_name[] = $row[1];
	}
}
?>