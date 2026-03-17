<!DOCTYPE html>
<html>
<?php
if (isset($_COOKIE['user_id']))
	$global_user_id = $_COOKIE['user_id'];
else
	$global_user_id = '';
if (isset($_COOKIE['store']))
	$global_store_id = $_COOKIE['store'];
else
	$global_store_id = '';

$inf_url_primary = inf_url_primary();
$inf_url_backup = inf_url_backup();
$subscription_endin = subscription();
if ($subscription_endin < 10)
	$subscription_color = 'red';
else
	$subscription_color = 'black';
if (($subscription_endin < 1) && (isset($_GET['action']))) {
	if ($_GET['action'] != 'expire')
		print '<script type="text/javascript">window.location = \'index.php?components=authenticate&action=expire\';</script>';
}

$fqdn = $_SERVER['SERVER_NAME'];
if ($fqdn == $inf_url_primary) {
	$apptitle = '';
	$mailtheme = 'w3-theme';
	$navtheme = 'w3-theme-d2';
} else if ($fqdn == $inf_url_backup) {
	$apptitle = ' [Test]';
	$mailtheme = 'w3-theme-dark';
	$navtheme = 'w3-theme-d5';
} else {
	$apptitle = ' [Local]';
	$mailtheme = 'w3-theme-dark';
	$navtheme = 'w3-theme-d5';
}
$inf_company = inf_company(1);

$ssl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
if (($fqdn == $inf_url_primary) && ($fqdn != 'test.negoit.info')) {
	//  if($ssl=='http') header('Location: https://'.$inf_url_primary);
}
$components = $_REQUEST['components'];
if ($systemid == 13 && $sub_system == 1)
	$shipment = 'Cost of Operations';
else
	$shipment = 'Shipments';
?>
<title><?php print $inf_company; ?> <?php print $apptitle; ?></title>
<?php
if ($systemid == 14)
	print '<link rel="manifest" href="js/manifest14.json">';
elseif ($systemid == 15)
	print '<link rel="manifest" href="js/manifest15.json">';
else
	print '<link rel="manifest" href="js/manifest.json">';
?>
<meta name="viewport" content="width=device-width, height=device-height, user-scalable=no">
<meta name="HandheldFriendly" content="true" />
<meta name="mobile-web-app-capable" content="yes">
<link rel="stylesheet" href="css/mobile1.css">
<link rel="stylesheet" href="css/mobile2.css">
<link rel="stylesheet" href="css/mobile3.css">
<link rel="stylesheet" href="css/billing_v1.5.css" type="text/css" media="screen" />
<script src="js/billing_v2.8.js"></script>
<!-- Toaster css -->
<link rel="stylesheet" href="css/toastr.min.css">
<script type="text/javascript">
	// Detect whether device supports orientationchange event, otherwise fall back to
	// the resize event.
	var supportsOrientationChange = "onorientationchange" in window,
		orientationEvent = supportsOrientationChange ? "orientationchange" : "resize";

	window.addEventListener(orientationEvent, function () {
		var portrait = document.getElementById('portrait').innerHTML;
		var landscape = document.getElementById('landscape').innerHTML;
		if (window.orientation == 0) {
			if (landscape != '') document.getElementById('portrait').innerHTML = landscape;
			document.getElementById('landscape').innerHTML = '';
		} else if (window.orientation == 90) {
			if (portrait != '') document.getElementById('landscape').innerHTML = portrait;
			document.getElementById('portrait').innerHTML = '';
		} else if (window.orientation == 180) {
			if (landscape != '') document.getElementById('portrait').innerHTML = landscape;
			document.getElementById('landscape').innerHTML = '';
		} else if (window.orientation == -90) {
			if (portrait != '') document.getElementById('landscape').innerHTML = portrait;
			document.getElementById('portrait').innerHTML = '';
		}
	}, false);
</script>
<style type="text/css">
	<?php
	if ($fqdn == $inf_url_primary) {
		$json_array = json_decode(subsystemTheme($sub_system, $global_store_id, false));
		?>
		.w3-theme {
			color: #fff !important;
			background-color:
			<?php print $json_array->{"theme_color_m1"}; ?>
			!important
		}

		.w3-theme-d2 {
			color: #fff !important;
			background-color:
			<?php print $json_array->{"theme_color_m2"}; ?>
			!important
		}

	<?php } ?>
</style>

<body>
	<nav class="w3-sidenav w3-card-2 w3-white w3-top" style="width:30%;display:none;z-index:2" id="mySidenav">
		<div class="w3-container <?php print $navtheme; ?>">
			<span onclick="w3_close()" class="w3-closenav w3-right w3-xlarge">x</span>
			<br>
			<div class="w3-padding w3-center">
				<img class="w3-circle" src="images/icon<?php print $systemid; ?>.png" alt="avatar" style="width:75%" />
			</div>
		</div>
		<br>
		<?php
		//--------------------------------Common Checks---------------------------------------------//
		$localstyle3 = $localstyle1 = $localstyle11 = '';

		if (checkPendingCust($sub_system))
			$localstyle1 = 'style="color:red"';
		if (deleteAck())
			$localstyle11 = 'style="color:red"';


		//--------------------------------Sub Menus---------------------------------------------//
		if (isset($_COOKIE['check_availability'])) {
			if ($_COOKIE['direct_mkt'] == 0)
				$url_ava = 'index.php?components=availability&action=home&category=all';
			else
				$url_ava = 'index.php?components=availability&action=home&action=catalog';
			?>
			<a href="<?php print $url_ava; ?>"><strong>Availability</strong></a>
		<?php }

		// STK
		if (isset($_COOKIE['stk'])) { ?>
			<a href="index.php?components=stk&action=show_add_shipment_tmp&sub=show_add_qty_tmp"><strong>STK</strong></a>
		<?php }

		if (isset($_COOKIE['billing'])) { ?>
			<a
				href="index.php?components=billing&action=home&s=<?php print $_COOKIE['user_id']; ?>&cust_odr=no"><strong>Billing</strong></a>
		<?php }

		// Bill2
		if (isset($_COOKIE['bill2'])) { ?>
			<a
				href="index.php?components=bill2&action=home&s=<?php print $_COOKIE['user_id']; ?>&cust_odr=no"><strong>Bill2</strong></a>
		<?php }

		// Order Process
		if (isset($_COOKIE['order_process'])) { ?>
			<a href="index.php?components=order_process&action=list_custodr"><strong>Order Process</strong></a>
		<?php }

		// Repair
		if (isset($_COOKIE['repair'])) { ?>
			<a href="index.php?components=repair&action=list_pending"><strong>Repair</strong></a>
		<?php }

		// Inventory
		if (isset($_COOKIE['inventory'])) {
			if ($systemid == 14)
				$menu_cat = 'all';
			else
				$menu_cat = '1';
			?>
			<a
				href="index.php?components=inventory&action=show_all_item&category=<?php print $menu_cat; ?>&store=<?php print $_COOKIE['store']; ?>&type=1"><strong>Inventory</strong></a>
		<?php }

		// Trans
		if (isset($_COOKIE['stores_transfer'])) {
			if (checkPendingGTN())
				$localstyle2 = 'style="color:red"';
			else
				$localstyle2 = '';
			?>
			<a href="index.php?components=trans&action=approval" <?php print $localstyle2; ?>><strong>Transfer</strong></a>
		<?php }

		// SUP
		if (isset($_COOKIE['supervisor'])) { ?>
			<a href="index.php?components=supervisor&action=daily_sale&store=<?php print $_COOKIE['store']; ?>&group=all&salesman=all&processby=all&lock=1&cashback=no&type="
				<?php print $localstyle1; ?>><strong>Supervisor</strong></a>
		<?php }

		if (isset($_COOKIE['hire_purchase'])) { ?>
			<a href="index.php?components=hire_purchase&action=home" <?php print $localstyle1; ?>><strong>Hire
					Purchase</strong></a>
		<?php }
		if (isset($_COOKIE['hr'])) {
			if (checkPendingHR($sub_system))
				$localstyle9 = 'style="color:red"';
			else
				$localstyle9 = ''; ?>
			<a href="index.php?components=hr&action=home" <?php print $localstyle9; ?>><strong>HR</strong></a>
			<!-- HR -->
		<?php }
		if (isset($_COOKIE['to'])) {
			if (checkPendingGTN())
				$localstyle2 = 'style="color:red"';
			else
				$localstyle2 = '';
			?>
			<a href="index.php?components=to&action=home" <?php print $localstyle2; ?>><strong>TO</strong></a>
		<?php }
		if (isset($_COOKIE['manager'])) {
			if ($systemid == 13 && $sub_system == 1) {
				?>
				<a href="index.php?components=manager&action=quotation_approve" <?php print $localstyle1; ?>><strong>Manager</strong></a>
			<?php } else { ?>
				<a href="index.php?components=manager&action=daily_sale&store=all&group=all&salesman=all&processby=all&lock=1&type=&cashback=no"
					<?php print $localstyle1; ?>><strong>Manager</strong></a>
			<?php } ?>

			<!-- Top Manager -->
		<?php }
		if (isset($_COOKIE['top_manager'])) { ?>
			<a href="index.php?components=topmanager&action=daily_sale&store=all&group=all&salesman=all&processby=all&lock=1&type=&cashback=no"
				<?php print $localstyle3; ?>><strong>Top Manager</strong></a>

			<!-- Report -->
		<?php }
		if (isset($_COOKIE['report'])) {
			if (requestApproval())
				$localstyle7 = 'style="color:red"';
			else
				$localstyle7 = '';
			$localstyle_rep = '';
			if ($localstyle7 != '')
				$localstyle_rep = $localstyle7;
			if ($localstyle11 != '')
				$localstyle_rep = $localstyle11; ?>
			<a href="index.php?components=report&action=sales_report" <?php print $localstyle_rep; ?>><strong>Reports</strong></a>
		<?php } ?>

		<!-- Marketing -->
		<?php if (isset($_COOKIE['marketing'])) { ?>
			<a href="index.php?components=marketing&action=mk_home"><strong>Marketing</strong></a>
		<?php } ?>
		<hr />

		<!-- Availability Menu Items -->
		<?php if ($_REQUEST['components'] == 'availability') { ?>
			<?php if ($_COOKIE['direct_mkt'] == 0)
				print '<a href="index.php?components=availability&action=home&category=all">Check Availability</a>'; ?>
			<?php if (($systemid == 14) || ($systemid == 15) || ($systemid == 16) || ($systemid == 17) || ($systemid == 13 && $sub_system == 0))
				print '<a href="index.php?components=availability&action=catalog">Catalog</a>'; ?>
			<?php if (($systemid == 10) || ($systemid == 13) || ($systemid == 14) || ($systemid == 15) || ($systemid == 16))
				print '<a href="index.php?components=availability&action=stock">Stock</a>'; ?>
		<?php } ?>

		<!-- Billing Menu Items -->
		<?php if ($_REQUEST['components'] == 'billing') { ?>
			<a href="index.php?components=billing&action=home&s=<?php print $_COOKIE['user_id']; ?>&cust_odr=no">Sales
				Billing</a>
			<a href="index.php?components=billing&action=payment">Payment</a>
			<a href="index.php?components=billing&action=today">Today Invoices</a>
			<?php if ($systemid != 24) { ?>
				<a href="index.php?components=billing&action=credit&st=<?php print $_COOKIE['store']; ?>&gp=&display=2">Credit
					Report</a>
			<?php } else { ?>
				<a href="index.php?components=billing&action=credit&gp=&display=2">Credit Report</a>
			<?php } ?>
			<a href="index.php?components=billing&action=cust_sale">Customer Report</a>
			<?php if ((isset($_COOKIE['cus_details_on_billing'])) && ($_COOKIE['cus_details_on_billing'] == 1)) { ?>
				<a href="index.php?components=billing&action=mk_home">Customer Details</a>
			<?php } ?>
			<a href="index.php?components=billing&action=sales_report2">Sales Report2</a>
			<a
				href="index.php?components=billing&action=unvisited&type=unvisited&asso_salesman=<?php print $_COOKIE['user_id']; ?>">Unvisited
				Customers</a>
			<?php
			if ($systemid != 1 && $systemid != 4)
				print '<a href="index.php?components=billing&action=daily_sale&store=all&group=all&salesman=' . $_COOKIE['user_id'] . '&processby=all&lock=1&type=">My Sales</a>';
			if (($systemid != 15) && ($systemid != 14) && ($systemid != 24))
				print '<a href="index.php?components=billing&action=item_return">Item Return</a>';
			?>
			<a href="index.php?components=billing&action=sold_qty">Today Sold Qty</a>
			<a href="index.php?components=billing&action=chque_return">Cheque Return</a>
			<a href="index.php?components=billing&action=chque_ops&group=all&salesman=all">Cheque OPS</a>
			<?php if ((isset($_COOKIE['commission_on_billing'])) && ($_COOKIE['commission_on_billing'] == 1)) { ?>
				<a href="index.php?components=billing&action=salesman_commission_new">Commission Report</a>
				<a href="index.php?components=billing&action=salesman_commission_old">Old Salesman Commission Report</a>
				<a href="index.php?components=billing&action=salesman_commission_incomplete_one">Incomplete Salesman Commission
					Report</a>
			<?php } ?>
			<?php if (($systemid == 1) || ($systemid == 10) || ($systemid == 13) || ($systemid == 16) || ($systemid == 17)) { ?>
				<a href="index.php?components=billing&action=home&s=<?php print $_COOKIE['user_id']; ?>&cust_odr=yes">Cust
					Order</a><?php } ?>
		<?php } ?>

		<!-- Bill2 Menu Items -->
		<?php if ($_REQUEST['components'] == 'bill2') { ?>
			<a href="index.php?components=bill2&action=home&s=<?php print $_COOKIE['user_id']; ?>&cust_odr=no">Sales
				Billing</a>
			<?php if (($systemid == 1) || ($systemid == 10) || ($systemid == 13) || ($systemid == 16) || ($systemid == 17)) { ?>
				<a href="index.php?components=bill2&action=home&s=<?php print $_COOKIE['user_id']; ?>&cust_odr=yes">Cust
					Order</a>
			<?php } ?>
			<a href="index.php?components=bill2&action=payment_home">Payment</a>
			<a href="index.php?components=bill2&action=today">Today Invoices</a>
			<a href="index.php?components=bill2&action=credit&gp=&display=2">Credit Report</a>
			<a href="index.php?components=bill2&action=cust_sale">Customer Report</a>
			<?php if ((isset($_COOKIE['cus_details_on_billing'])) && ($_COOKIE['cus_details_on_billing'] == 1)) { ?>
				<a href="index.php?components=bill2&action=mk_home">Customer Details</a>
			<?php } ?>
			<a href="index.php?components=bill2&action=sales_report2">Sales Report2</a>
			<a
				href="index.php?components=bill2&action=unvisited&type=unvisited&asso_salesman=<?php print $_COOKIE['user_id']; ?>">Unvisited
				Customers</a>
			<?php
			if ($systemid != 1 && $systemid != 4)
				print '<a href="index.php?components=bill2&action=sale&store=all&group=all&salesman=' . $_COOKIE['user_id'] . '&processby=all&lock=1&type=">My Sales</a>';
			if ($systemid != 15)
				print '<a href="index.php?components=bill2&action=item_return">Item Return</a>';
			?>
			<a href="index.php?components=bill2&action=sold_qty&date=<?php print dateNow(); ?>">Daily Sold Qty</a>
			<a href="index.php?components=bill2&action=chque_return">Cheque Return</a>
			<a href="index.php?components=bill2&action=chque_ops&group=all&salesman=all">Cheque OPS</a>
			<a href="index.php?components=bill2&action=chque_ops&group=all&salesman=all">Cheque OPS</a>
			<?php if ($systemid == 13) { ?>
				<a href="index.php?components=bill2&action=quotation">New Quotation</a>
				<a href="index.php?components=bill2&action=quotation_ongoing">On-Going Quotationn</a>
				<a href="index.php?components=bill2&action=quotation_list&cust=&item=&st=&sm=&status=all">Quotation List</a>
			<?php } ?>
			<?php if ((isset($_COOKIE['commission_on_billing'])) && ($_COOKIE['commission_on_billing'] == 1)) { ?>
				<a href="index.php?components=bill2&action=salesman_commission_new">Commission Report</a>
				<a href="index.php?components=bill2&action=salesman_commission_old">Old Salesman Commission Report</a>
				<a href="index.php?components=bill2&action=salesman_commission_incomplete_one">Incomplete Salesman Commission
					Report</a>
			<?php } ?>
			<?php if (isSalesmanPaymentDepositActive()) { ?>
				<a href="index.php?components=bill2&action=cash_payment_deposit">Cash Payment Deposit</a>
				<a href="index.php?components=bill2&action=bank_payment_deposit">Bank Payment Deposit</a>
				<a href="index.php?components=bill2&action=cheque_transfer">Add Cheque Trans</a>
				<a href="index.php?components=bill2&action=cash_sent_report">Cash Payments Sent Report</a>
				<a href="index.php?components=bill2&action=bank_payments_sent_report">Bank Payments Sent Report</a>
				<a href="index.php?components=bill2&action=cheque_transfer_summery">Cheque Trans Summery</a>
				<a href="index.php?components=bill2&action=cheque_transfer_status_summery">Cheque Trans Status Summery</a>
				<a href="index.php?components=bill2&action=cheque_transfer_returns">Cheque Trans Return</a>
			<?php } ?>
		<?php } ?>

		<!-- Order Process Menu Items -->
		<?php if ($_REQUEST['components'] == 'order_process') { ?>
			<a href="index.php?components=order_process&action=list_custodr">Cust Order</a>
			<a href="index.php?components=order_process&action=list_pending">Pending</a>
			<a href="index.php?components=order_process&action=list_my">My List</a>
			<a href="index.php?components=order_process&action=list_packed"><?php if ($systemid == 13 && $sub_system == 1)
				echo 'Started';
			else
				print 'Packed'; ?></a>
			<?php if ($systemid != 13 || $sub_system != 1) { ?> <a
					href="index.php?components=order_process&action=list_shipped">Shipped</a> <?php } ?>
			<a href="index.php?components=order_process&action=list_delivered"><?php if ($systemid == 13 && $sub_system == 1)
				echo 'Finished';
			else
				print 'Delivered'; ?></a>
			<a href="index.php?components=order_process&action=list_return">Return</a>
			<?php if ($approver2)
				print '<a href="index.php?components=order_process&action=report_commision">Report</a>'; ?>
		<?php } ?>

		<!-- Repair Menu Items -->
		<?php if ($_REQUEST['components'] == 'repair') { ?>
			<a href="index.php?components=repair&action=list_pending">Pending</a>
			<a href="index.php?components=repair&action=list_my">My List</a>
			<a href="index.php?components=repair&action=list_rejected">Rejected</a>
			<a href="index.php?components=repair&action=list_finished">Finished</a>
			<?php
			if (isset($_COOKIE['manager']))
				print '<a href="index.php?components=repair&action=change_st">MGR</a>';
			?>
		<?php } ?>

		<!-- STK Menu Items -->
		<?php if ($_REQUEST['components'] == 'stk') { ?>
			<a href="index.php?components=stk&action=show_add_item&type=1">Add Item</a>
			<a href="index.php?components=stk&action=show_add_shipment&sub=show_add_qty">Add Qty</a>
		<?php } ?>

		<!-- Inventory Menu Items -->
		<?php if ($_REQUEST['components'] == 'inventory') {
			if (($systemid != 1) || ($systemid == 1 || $sub_system == 0) || ($systemid == 1 || $sub_system == 1)) { ?>
				<a href="index.php?components=inventory&action=show_add_item&type=1">Add Item</a>
				<a href="index.php?components=inventory&action=show_add_shipment&sub=show_add_qty">Add Qty</a>
				<a href="index.php?components=inventory&action=show_add_shipment_tmp&sub=show_add_qty_tmp">Add Qty Tmp</a>
				<a href="index.php?components=inventory&action=show_add_shipment&sub=show_add_unic">Add Unique</a>
				<a href="index.php?components=inventory&action=show_add_shipment_tmp&sub=show_add_unic_tmp">Add Unique Tmp</a>
				<a href="index.php?components=inventory&action=show_edit_item">Edit Item</a>
				<a href="index.php?components=inventory&action=show_specialprice">Special Rate</a>
				<a href="index.php?components=inventory&action=show_districtprice">District Rate</a>
				<?php
			}
			if ($_COOKIE['user'] != 'dataentry') {
				if ($systemid == 2) {
					print '<a href="index.php?components=inventory&action=show_all_item&category=1&store=' . $_COOKIE['store'] . '&type=5">All Items</a>';
				} else {
					print '<a href="index.php?components=inventory&action=show_all_item&category=1&store=' . $_COOKIE['store'] . '&type=1">All Items</a>';
				}
			}
			if ($sub_system == 0) {
				print '<a href="index.php?components=inventory&action=show_temp">New Items</a>';
				print '<a href="index.php?components=inventory&action=drawer_search&st=' . $_COOKIE['store'] . '">Drawer Search</a>';
				print '<a href="index.php?components=inventory&action=shipmentlist&month=' . date("Y-m", time()) . '">' . $shipment . '</a>';
			}
			if ($systemid == 13 && $sub_system == 1) {
				print '<a href="index.php?components=inventory&action=shipmentlist&month=' . date("Y-m", time()) . '">' . $shipment . '</a>';
			}
		} ?>

		<!-- Transfer Menu Items -->
		<?php if ($_REQUEST['components'] == 'trans') { ?>
			<a href="index.php?components=trans&action=home">New Transfer</a>
			<a href="index.php?components=trans&action=approval" <?php print $localstyle2; ?>>Approval Pending</a>
			<a href="index.php?components=trans&action=today">Today</a>
			<a href="index.php?components=trans&action=last100">Last100</a>
			<a href="index.php?components=trans&action=drawer_search&st=<?php print $_COOKIE['store']; ?>">Drawer Search</a>
		<?php }
		if ($_REQUEST['components'] == 'supervisor') { ?>
			<a
				href="index.php?components=supervisor&action=daily_sale&store=<?php print $_COOKIE['store']; ?>&group=all&salesman=all&processby=all&lock=1&type=">Sales
				Report</a>
			<a href="index.php?components=supervisor&action=repair_income">Repair Income</a>
			<a href="index.php?components=supervisor&action=credit&gp=&display=2">Credit Report</a>
			<a href="index.php?components=supervisor&action=sales_byrep">Sales By Rep</a>
			<?php if ($_COOKIE['user'] == 'nimesha')
				print '<a href="index.php?components=supervisor&action=deleted" ' . $localstyle11 . '>Deleted</a>'; ?>
			<a href="index.php?components=supervisor&action=chque&group=all&salesman=all">Cheque</a>
			<a href="index.php?components=supervisor&action=newcust" <?php print $localstyle1; ?>>Customer MGMT</a>
			<a href="index.php?components=supervisor&action=unlocked">Unlocked</a>
			<?php if (bill_module(1) == 'bill2') { ?> <a href="index.php?components=supervisor&action=temporary_bills">Temporary
					Bills</a> <?php } ?>
			<a href="index.php?components=supervisor&action=sn_lookup&item_id=">S/N Lookup</a>
			<?php if ($systemid == 13) { ?>
				<a href="index.php?components=supervisor&action=quotation">New Quotation</a>
				<a href="index.php?components=supervisor&action=quotation_ongoing">On-Going Quotation</a>
				<a href="index.php?components=supervisor&action=quotation_list&cust=&item=&st=&sm=&status=all">Quotation
					List</a>
			<?php } ?>
			<a href="index.php?components=supervisor&action=cash_on_hand_report">Cash On Hand Report</a>
		<?php }
		if ($_REQUEST['components'] == 'hire_purchase') { ?>
			<a href="index.php?components=hire_purchase&action=home">Home</a>
			<a href="index.php?components=hire_purchase&action=collection">Collection</a>
			<a href="index.php?components=hire_purchase&action=cust_list">Customer List</a>
		<?php }
		if ($_REQUEST['components'] == 'hr') { ?>
			<a href="index.php?components=hr&action=home">Apply Leave</a>
			<a href="index.php?components=hr&action=my_leave">My Leave</a>
			<?php if ($approver1) { ?>
				<a href="index.php?components=hr&action=leave_list" <?php print $localstyle9; ?>>Leave List</a>
			<?php }
			if ($approver2) { ?>
				<a href="index.php?components=hr&action=shop_staff">Shop Staff</a>
			<?php }
		} ?>

		<!-- Manager Menu Items -->
		<?php if ($_REQUEST['components'] == 'manager') { ?>
			<a href="index.php?components=manager&action=newcust" <?php print $localstyle1; ?>>Customer MGMT</a>
			<?php if ((isset($_COOKIE['cust_dob_on_manager'])) && ($_COOKIE['cust_dob_on_manager'] == 1)) { ?>
				<a href="index.php?components=manager&action=cust_dob">Customer Birthday</a>
			<?php } ?>
			<a href="index.php?components=manager&action=device_mgmt">Device MGMT</a>
			<a
				href="index.php?components=manager&action=daily_sale&store=all&group=all&salesman=all&processby=all&lock=1&type=&cashback=no">Daily
				Sales</a>
			<a href="index.php?components=manager&action=cust_sale">Customer Report</a>
			<a href="index.php?components=manager&action=sales_report2">Sales Report2</a>
			<a href="index.php?components=manager&action=sales_summary">Sales Summary</a>
			<a href="index.php?components=manager&action=repair_income">Repair Income</a>
			<a href="index.php?components=manager&action=sales_bycategory">Sales by Category</a>
			<?php
			if ($systemid == 24) {
				if ($_COOKIE['user_id'] == 6 || $_COOKIE['user_id'] == 15 || $_COOKIE['user_id'] == 17) { ?>
					<a href="index.php?components=manager&action=chque_pending_finalyze">Tobe Deposit Cheque</a>
				<?php }
			} else { ?>
				<a href="index.php?components=manager&action=chque_pending_finalyze">Tobe Deposit Cheque</a>
			<?php } ?>
			<a href="index.php?components=manager&action=chque_realize_report_onedate">Cheques to be Realized</a>
			<a href="index.php?components=manager&action=sales_byrep">Sales by Rep</a>
			<a href="index.php?components=manager&action=unvisited&type=unvisited">Unvisited Customers</a>
			<a href="index.php?components=manager&action=unlocked">Unlocked Bills</a>
			<?php if (bill_module(1) == 'bill2') { ?> <a href="index.php?components=manager&action=temporary_bills">Temporary
					Bills</a> <?php } ?>
			<a href="index.php?components=manager&action=sold_qty&store=all">Daily Sold Qty</a>
			<a href="index.php?components=manager&action=sn_lookup&item_id=">S/N Lookup</a>
			<?php if ($systemid == 13) { ?>
				<a href="index.php?components=manager&action=quotation">New Quotation</a>
				<a href="index.php?components=manager&action=quotation_ongoing">On-Going Quotationn</a>
				<a href="index.php?components=manager&action=quotation_list&cust=&item=&st=&sm=&status=all">Quotation List</a>
				<a href="index.php?components=manager&action=quotation_approve">Quotation Approve</a>
			<?php } ?>
			<a href="index.php?components=manager&action=tax_report">Tax Report</a>
			<?php if ($systemid != 15)
				print '<a href="index.php?components=manager&action=authorize_code">Authorize Code</a>'; ?>
		<?php } ?>

		<?php if ($_REQUEST['components'] == 'topmanager') { ?>
			<a href="index.php?components=topmanager&action=newcust" <?php print $localstyle1; ?>>Customer MGMT</a>
			<a href="index.php?components=topmanager&action=device_mgmt">Device MGMT</a>
			<a
				href="index.php?components=topmanager&action=daily_sale&store=all&group=all&salesman=all&processby=all&lock=1&type=&cashback=no">Daily
				Sales</a>
			<a href="index.php?components=topmanager&action=cust_sale">Customer Report</a>
			<a href="index.php?components=topmanager&action=sales_report2">Sales Report2</a>
			<a href="index.php?components=topmanager&action=sales_summary">Sales Summary</a>
			<a href="index.php?components=topmanager&action=repair_income">Repair Income</a>
			<a href="index.php?components=topmanager&action=sales_bycategory">Sales by Category</a>
			<a href="index.php?components=topmanager&action=chque_pending_finalyze">Cheque Pending Finalyze</a>
			<a href="index.php?components=topmanager&action=chque_realize_report_onedate">Cheque Realize Report</a>
			<a href="index.php?components=topmanager&action=sales_byrep">Sales by Rep</a>
			<a href="index.php?components=topmanager&action=credit&display=2&sub_system=0">Credit Report</a>
			<a href="index.php?components=topmanager&action=unvisited&type=unvisited">Unvisited Customers</a>
			<a href="index.php?components=topmanager&action=unlocked">Unlocked Bills</a>
			<?php if (bill_module(1) == 'bill2') { ?> <a href="index.php?components=topmanager&action=temporary_bills">Temporary
					Bills</a> <?php } ?>
			<a href="index.php?components=topmanager&action=sold_qty&store=all">Daily Sold Qty</a>
			<a href="index.php?components=topmanager&action=sn_lookup&item_id=">S/N Lookup</a>
			<a href="index.php?components=topmanager&action=authorize_code">Authorize Code</a>
			<?php if ($systemid == 13) { ?>
				<a href="index.php?components=topmanager&action=quotation">New Quotation</a>
				<a href="index.php?components=topmanager&action=quotation_ongoing">On-Going Quotationn</a>
				<a href="index.php?components=topmanager&action=quotation_list&cust=&item=&st=&sm=&status=all">Quotation
					List</a>
				<a href="index.php?components=topmanager&action=quotation_approve">Approve Quotation</a>
			<?php } ?>
		<?php }
		if ($_REQUEST['components'] == 'report') { ?>
			<a href="index.php?components=report&action=sales_report">Sales Report</a>
			<a href="index.php?components=report&action=category_profit&subsys=all">Category Profit</a>
			<?php if (isProfitReportActive()) { ?><a href="index.php?components=report&action=profit_report">Profit
					Report</a>
			<?php } ?>
			<a href="index.php?components=report&action=sales_trend&sys=0">Sales Trend</a>
			<a href="index.php?components=report&action=credit_trend">Credit Trend</a>
			<a href="index.php?components=report&action=deleted" <?php print $localstyle11; ?>>Deleted</a>
			<a href="index.php?components=report&action=salesman">Salesman</a>
			<a href="index.php?components=report&action=chque_pending_finalyze">Cheque Pending Finalyze</a>
			<a href="index.php?components=report&action=clear_chque_list&year=<?php print date("Y", time()); ?>">Cheque
				Report</a>
			<a href="index.php?components=report&action=credit">Credit</a>
			<a href="index.php?components=report&action=payment_commision">Commission</a>
			<a href="index.php?components=report&action=salesman_commission_new">New Salesman Commission Report</a>
			<a href="index.php?components=report&action=salesman_commission_old">Old Salesman Commission Report</a>
			<a href="index.php?components=report&action=salesman_commission_incomplete">Incomplete Salesman Commission
				Report</a>
			<a href="index.php?components=report&action=unlocked">Unlocked</a>
			<a href="index.php?components=report&action=return_items&sm=">Return Items</a>
			<a href="index.php?components=report&action=cost">Cost Report</a>
			<a href="index.php?components=report&action=sub&report_type=">Sub Report</a>
			<a href="index.php?components=report&action=authorize_code">Authorize Code</a>
			<a href="index.php?components=report&action=approval" <?php print $localstyle7; ?>>Approval</a>
		<?php }

		if ($_REQUEST['components'] == 'portalsup') { ?>
			<a href="index.php?components=portalsup&action=dashboard">Dashboard</a>
			<a href="index.php?components=portalsup&action=sales_report">Sales Report</a>
			<a href="index.php?components=portalsup&action=monthly_sales">Monthly Sales</a>
			<a href="index.php?components=portalsup&action=monthly_return">Monthly Return</a>
		<?php }
		if ($_REQUEST['components'] == 'marketing') { ?>
			<a href="index.php?components=marketing&action=mk_home">Customer Details</a>
		<?php }

		if ($_REQUEST['components'] == 'to') { ?>
			<a href="index.php?components=to&action=quotation">New Quotation</a>
			<a href="index.php?components=to&action=quotation_ongoing">On-Going Quotationn</a>
			<a href="index.php?components=to&action=quotation_list&cust=&item=&st=&sm=&status=all">Quotation List</a>
		<?php } ?>
		<br />
		<br />
		<hr /><a href="index.php?components=authenticate&action=change_pw"
			style="text-decoration:none; color:blue; font-weight:bold;"><?php if (isset($_COOKIE['user']))
				print ucfirst($_COOKIE['user']); ?></a>

		<a href="index.php?components=authenticate&action=logout">Logout</a>
	</nav>

	<header class="w3-container w3-card-4 <?php print $mailtheme; ?> w3-top">
		<h3>
			<table width="100%">
				<tr>
					<td width="40px">
						<i class="w3-opennav fa fa-bars" onclick="w3_open()"></i>
					</td>
					<?php
					switch ($_REQUEST['components']) {
						case "availability":
							if ($_REQUEST['action'] == 'home') {
								print '<td>Check Availability</td><td align="right">';
								include_once 'components/checkAvailability/view/tpl/district.php';
								print '</td>';
							} else if ($_REQUEST['action'] == 'catalog') {
								print '<td>Check Availability</td><td align="right">';
								print '</td>';
							} else if ($_REQUEST['action'] == 'stock') {
								print '<td>Stock</td><td align="right">';
								print '</td>';
							}
							break;

						case "billing":
							if ($_REQUEST['action'] == 'home') {
								if ($_GET['cust_odr'] == 'no')
									print '<td>Sales Billing</td><td align="right">';
								else
									print '<td>Cust Order</td><td align="right">';
								include_once 'components/billing/view/tpl/district.php';
								print '</td>';
							} else if ($_REQUEST['action'] == 'pay_bill') {
								if ($_GET['cust_odr'] == 'no')
									print '<td>Bill Payment</td><td align="right">';
								else
									print '<td>Advance Payment</td><td align="right">';
								print '<input type="button" value="Back" style="width:100px; font-size:medium" onclick="window.location = ' . "'index.php?components=billing&action=home&cust_odr=" . $_GET['cust_odr'] . "&id=" . $_GET['id'] . "&s=" . $_GET['s'] . "&cust=" . $bmcust_id . "'" . '" />';
								print '</td>';
							} else if ($_REQUEST['action'] == 'finish_bill') {
								print '<td>Invoice</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'payment') {
								print '<td>Payment</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'finish_payment') {
								print '<td>Billing</td><td align="right">';
								print '<input type="button" value="New Payment" style="font-size:medium" onclick="window.location = ' . "'index.php?components=billing&action=payment'" . '" />';
								print '</td>';
							} else if ($_REQUEST['action'] == 'credit') {
								print '<td>Credit Report</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'cust_sale') {
								print '<td>Customer Report</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'sales_report2') {
								print '<td>Sales Report2</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'sales_report3') {
								print '<td>Sales Report2</td><td align="right">';
								print '<input type="button" value="Back" style="width:100px; font-size:medium" onclick="window.location = ' . "'$back1'" . '" />';
								print '</td>';
							} else if ($_REQUEST['action'] == 'sale') {
								print '<td>Billing</td><td align="right">';
								print 'My Sales';
								print '</td>';
							} else if ($_REQUEST['action'] == 'sold_qty') {
								print '<td>Billing</td><td align="right">';
								print 'Sold Qty';
								print '</td>';
							} else if ($_REQUEST['action'] == 'chque_ops') {
								print '<td>Billing</td><td align="right">';
								print 'Cheque Ops';
								print '</td>';
							} else if ($_REQUEST['action'] == 'today') {
								print '<td>Today</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'item_return') {
								print '<td>Item Return</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'finish_return') {
								print '<td>Return Invoice</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'salesman_commission_new') {
								print '<td>Billing</td><td align="right">Commission</td>';
							} else if ($_REQUEST['action'] == 'salesman_commission_old') {
								print '<td>Billing</td><td align="right">Old Salesman Commission Report</td>';
							} else if ($_REQUEST['action'] == 'salesman_commission_one') {
								print '<td>Billing</td><td align="right">Old Salesman Commission Report</td>';
							} else if ($_REQUEST['action'] == 'salesman_commission_incomplete_one') {
								print '<td>Billing</td><td align="right">Incomplete Salesman Commission Report</td>';
							} else if ($_REQUEST['action'] == 'mk_home') {
								print '<td>Billing</td><td align="right">Customer Details</td>';
							} else {
								print '<td>Billing</td><td align="right"></td>';
							}
							break;

						case "bill2":
							if ($_REQUEST['action'] == 'home') {
								if ($_GET['cust_odr'] == 'no')
									print '<td>Sales Billing</td><td align="right">';
								else
									print '<td>Cust Order</td><td align="right">';
								include_once 'components/bill2/view/tpl/district.php';
								print '</td>';
							} else if ($_REQUEST['action'] == 'bill_item') {
								if ($_GET['cust_odr'] == 'no')
									print '<td>Sales Billing</td><td align="right">';
								else
									print '<td>Cust Order</td><td align="right">';
								include_once 'components/bill2/view/tpl/district.php';
								print '</td>';
							} else if ($_REQUEST['action'] == 'bill_item') {
								if ($_GET['cust_odr'] == 'no')
									print '<td>Sales Billing</td><td align="right">';
								else
									print '<td>Cust Order</td><td align="right">';
								include_once 'components/bill2/view/tpl/district.php';
								print '</td>';
							} else if ($_REQUEST['action'] == 'pay_bill') {
								if ($_GET['cust_odr'] == 'no')
									print '<td>Bill Payment</td><td align="right">';
								else
									print '<td>Advance Payment</td><td align="right">Bill2 ';
								print '<div id="div_process"><input type="button" value="Back" style="width:100px; font-size:medium" onclick="window.location = ' . "'index.php?components=bill2&action=bill_item&cust_odr=" . $_GET['cust_odr'] . "&bill_no=" . $_GET['bill_no'] . "'" . '" /></div>';
								print '</td>';
							} else if ($_REQUEST['action'] == 'finish_bill') {
								print '<td>Invoice</td><td align="right">Bill2</td>';
							} else if ($_REQUEST['action'] == 'payment_home') {
								print '<td>Payment</td><td align="right">Bill2</td>';
							} else if ($_REQUEST['action'] == 'payment_form') {
								print '<td>Payment</td><td align="right">Bill2</td>';
							} else if ($_REQUEST['action'] == 'finish_payment') {
								print '<td>Billing</td><td align="right">Bill2 ';
								print '<input type="button" value="New Payment" style="font-size:medium" onclick="window.location = ' . "'index.php?components=bill2&action=payment_home'" . '" />';
								print '</td>';
							} else if ($_REQUEST['action'] == 'credit') {
								print '<td>Credit Report</td><td align="right">Bill2</td>';
							} else if ($_REQUEST['action'] == 'cust_sale') {
								print '<td>Customer Report</td><td align="right">Bill2</td>';
							} else if ($_REQUEST['action'] == 'sales_report2') {
								print '<td>Sales Report2</td><td align="right">Bill2</td>';
							} else if ($_REQUEST['action'] == 'sales_report3') {
								print '<td>Sales Report2</td><td align="right">Bill2 ';
								print '<input type="button" value="Back" style="width:100px; font-size:medium" onclick="window.location = ' . "'$back1'" . '" />';
								print '</td>';
							} else if ($_REQUEST['action'] == 'sale') {
								print '<td>My Sales</td><td align="right">Bill2</td>';
							} else if ($_REQUEST['action'] == 'chque_return') {
								print '<td>Cheque Return</td><td align="right">Bill2</td>';
							} else if ($_REQUEST['action'] == 'today') {
								print '<td>Today Invoices</td><td align="right">Bill2</td>';
							} else if ($_REQUEST['action'] == 'item_return') {
								print '<td>Item Return</td><td align="right">Bill2</td>';
							} else if ($_REQUEST['action'] == 'finish_return') {
								print '<td>Return Invoice</td><td align="right">Bill2</td>';
							} else if ($_REQUEST['action'] == 'salesman_commission_new') {
								print '<td>Commission</td><td align="right">Bill2</td>';
							} else if ($_REQUEST['action'] == 'salesman_commission_old') {
								print '<td>Old Salesman Commission Report</td><td align="right">Bill2</td>';
							} else if ($_REQUEST['action'] == 'salesman_commission_one') {
								print '<td>Old Salesman Commission Report</td><td align="right">Bill2</td>';
							} else if ($_REQUEST['action'] == 'salesman_commission_incomplete_one') {
								print '<td>Commission</td><td align="right">Incomplete Salesman Commission Report</td>';
							} else if ($_REQUEST['action'] == 'mk_home') {
								print '<td>Customer Details</td><td align="right">Bill2</td>';
							} else if ($_REQUEST['action'] == 'sold_qty') {
								print '<td>Daily Sold Qty</td><td align="right">Bill2</td>';
							} else if ($_REQUEST['action'] == 'quotation') {
								print '<td>Quotation</td><td align="right">';
								include_once 'components/bill2/view/tpl/district.php';
								print '</td>';
							} else if ($_REQUEST['action'] == 'quotation_ongoing') {
								print '<td>On-Going Quotation</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'quotation_list') {
								print '<td>Quotation List</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'qo_terms') {
								print '<td>Terms and Condition for the Quotation</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'qo_finish') {
								print '<td>Quotation Finish</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'cash_payment_deposit') {
								print '<td>Add Cash Payment Deposit</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'bank_payment_deposit') {
								print '<td>Add Bank Payment Deposit</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'cheque_payment_deposit') {
								print '<td>Add Cheque Payment Deposit</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'cash_sent_report') {
								print '<td>Cash Payments Sent Report</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'bank_payments_sent_report') {
								print '<td>Bank Payments Sent Report</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'cheque_payments_sent_report') {
								print '<td>Cheque Payments Sent Report</td><td align="right"></td>';
							} else {
								print '<td>Bill2</td><td align="right"></td>';
							}
							break;
						case "order_process":
							if ($_REQUEST['action'] == 'list_custodr') {
								print '<td>Cust Orders</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'list_pending') {
								print '<td>Pending Orders</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'list_my') {
								print '<td>My Orders</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'list_packed') {
								if ($systemid == 13 && $sub_system == 1)
									$packed = 'Started';
								else
									$packed = 'Packed';
								print '<td>' . $packed . ' Orders</td><td align="right"></td>';
							} else if (($_REQUEST['action'] == 'list_shipped') && ($systemid != 13)) {
								print '<td>Shipped Orders</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'list_delivered') {
								if ($systemid == 13 && $sub_system == 1)
									$delivered = 'Finished';
								else
									$delivered = 'Delivered';
								print '<td>' . $delivered . ' Orders</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'list_one_custodr') {
								print '<td>Cust Order</td><td align="right">';
								print '<input type="button" value="Back" style="width:100px; font-size:medium" onclick="window.location = ' . "'index.php?components=order_process&action=list_custodr'" . '" />';
								print '</td>';
							} else if ($_REQUEST['action'] == 'showadd_custodr') {
								print '<td>Cust Order</td><td align="right">';
								print '<input type="button" value="Back" style="width:100px; font-size:medium" onclick="window.location = ' . "'" . $back1 . "'" . '" />';
								print '</td>';
							} else if ($_REQUEST['action'] == 'list_return') {
								print '<td>Order</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'list_return') {
								print '<td>Return Items</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'list_unic_return') {
								print '<td>Return Items</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'report_commision') {
								print '<td>Commision Report</td><td align="right"></td>';
							} else {
								print '<td>One Order</td><td align="right">';
								print '<input type="button" value="Back" style="width:100px; font-size:medium" onclick="window.location = ' . "'" . $back1 . "'" . '" />';
								print '</td>';
							}
							break;

						case "repair":
							if ($_REQUEST['action'] == 'list_pending') {
								print '<td>Repair | Pending</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'list_my') {
								print '<td>Repair | My List</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'list_rejected') {
								print '<td>Repair | Rejected</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'list_finished') {
								print '<td>Repair | Finished</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'change_st') {
								print '<td>Repair | MGR</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'list_one') {
								print '<td>Repair Job</td><td align="right">';
								print '<input type="button" value="Back" style="width:100px; font-size:medium" onclick="window.location = ' . "'index.php?components=repair&action=list_pending'" . '" />';
								print '</td>';
							}
							break;

						case "inventory":
							if ($_REQUEST['action'] == 'show_add_item') {
								print '<td>Inventory</td><td align="right">Add Item</td>';
							} else if ($_REQUEST['action'] == 'show_add_qty') {
								print '<td>Inventory</td><td align="right">Add Qty</td>';
							} else if ($_REQUEST['action'] == 'show_add_unic') {
								print '<td>Inventory</td><td align="right">Add Unique</td>';
							} else if ($_REQUEST['action'] == 'show_edit_item') {
								print '<td>Inventory</td><td align="right">Edit Item</td>';
							} else if ($_REQUEST['action'] == 'show_one_item') {
								print '<td>Inventory</td><td align="right">Edit Item</td>';
							} else if ($_REQUEST['action'] == 'show_specialprice') {
								print '<td>Inventory</td><td align="right">Special Rate</td>';
							} else if ($_REQUEST['action'] == 'show_districtprice') {
								print '<td>Inventory</td><td align="right">District Rate</td>';
							} else if ($_REQUEST['action'] == 'show_all_item') {
								print '<td>Inventory</td><td align="right">';
								?>
															<button onclick="billingMenu22()" style="background-color:maroon; color:white" class="dropbtn2">All
																Items</button>
															<div id="myDropdown" class="dropdown-content">
																<a onmouseover="billingMenu2()" onmouseleave="billingMenu2()" style="font-size:11pt"
																	href="index.php?components=inventory&action=show_all_item&category=1&store=<?php print $_COOKIE['store']; ?>&type=1">Product
																	INV</a>
																<a onmouseover="billingMenu2()" onmouseleave="billingMenu2()" style="font-size:11pt"
																	href="index.php?components=inventory&action=show_all_item&category=1&store=<?php print $_COOKIE['store']; ?>&type=5">Unallocated
																	Product INV</a>
									<?php if ($sub_system == 0) { ?> <a onmouseover="billingMenu2()" onmouseleave="billingMenu2()"
																		style="font-size:11pt"
																		href="index.php?components=inventory&action=show_all_item&category=1&store=<?php print $_COOKIE['store']; ?>&type=2">Service
																		INV</a><?php } ?>
									<?php if ($sub_system == 0) { ?> <a onmouseover="billingMenu2()" onmouseleave="billingMenu2()"
																		style="font-size:11pt"
																		href="index.php?components=inventory&action=show_all_item&category=1&store=<?php print $_COOKIE['store']; ?>&type=3">Repair
																		INV</a><?php } ?>
									<?php if ($sub_system == 0) { ?> <a onmouseover="billingMenu2()" onmouseleave="billingMenu2()"
																		style="font-size:11pt"
																		href="index.php?components=inventory&action=show_all_item&category=1&store=<?php print $_COOKIE['store']; ?>&type=4">Repair
																		Parts INV</a><?php } ?>
															</div>
									<?php
									print '</td>';
							} else if ($_REQUEST['action'] == 'show_temp') {
								print '<td>Inventory</td><td align="right">New Items</td>';
							} else if ($_REQUEST['action'] == 'shipmentlist') {
								print '<td>Inventory</td><td align="right">' . $shipment . '</td>';
							} else if ($_REQUEST['action'] == 'one_shipment') {
								print '<td>Inventory</td><td align="right">' . $shipment . '</td>';
							} else if ($_REQUEST['action'] == 'show_unic') {
								print '<td>Inventory</td><td align="right">' . $shipment . '</td>';
							} else if ($_REQUEST['action'] == 'show_edit_unic') {
								print '<td>Inventory</td><td align="right">' . $shipment . '</td>';
							} else if ($_REQUEST['action'] == 'drawer_search') {
								print '<td>Inventory</td><td align="right">Drawer Search</td>';
							} else if ($_REQUEST['action'] == 'show_add_shipment') {
								if ($_REQUEST['sub'] == 'show_add_qty')
									print '<td>Inventory</td><td align="right">Add Qty</td>';
								if ($_REQUEST['sub'] == 'show_add_unic')
									print '<td>Inventory</td><td align="right">Add Unique</td>';
							}
							break;

						case "trans":
							if ($_REQUEST['action'] == 'home') {
								print '<td>Transfer</td><td align="right">New Transfer</td>';
							} else if ($_REQUEST['action'] == 'approval') {
								print '<td>Transfer</td><td align="right">Approval Pending</td>';
							} else if ($_REQUEST['action'] == 'today') {
								print '<td>Transfer</td><td align="right">Today</td>';
							} else if ($_REQUEST['action'] == 'last100') {
								print '<td>Transfer</td><td align="right">Last100</td>';
							} else if ($_REQUEST['action'] == 'print_gtn') {
								print '<td>Transfer</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'drawer_search') {
								print '<td>Transfer</td><td align="right">Drawer Search</td>';
							}
							break;

						case "authenticate":
							print '<td>Log In</td><td align="right"></td>';
							break;
						case "manager":
							if ($_REQUEST['action'] == 'device_mgmt') {
								print '<td>Device Management</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'newcust') {
								print '<td> Customer Management</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'cust_dob') {
								print '<td> Customer Birthday</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'editcust') {
								print '<td> Customer Management</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'disabledcust') {
								print '<td> Customer Management</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'chque_pending_finalyze') {
								print '<td> Cheque Pending Finalyze</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'chque_realize_report_onedate') {
								print '<td> Cheque Realize Report</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'chque_realize_report_daterange') {
								print '<td> Cheque Realize Report</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'searchcust') {
								print '<td> Customer Management</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'show_custgroup') {
								print '<td>Customer Grouping</td><td align="right">';
								print '<input type="button" value="Back" style="width:100px; font-size:medium" onclick="window.location = ' . "'index.php?components=manager&action=newcust'" . '" />';
								print '</td>';
							} else if ($_REQUEST['action'] == 'edit_custgroup') {
								print '<td>Customer Grouping</td><td align="right">';
								print '<input type="button" value="Back" style="width:100px; font-size:medium" onclick="window.location = ' . "'index.php?components=manager&action=newcust'" . '" />';
								print '</td>';
							} else if ($_REQUEST['action'] == 'show_custtown') {
								print '<td>Customer Grouping</td><td align="right">';
								print '<input type="button" value="Back" style="width:100px; font-size:medium" onclick="window.location = ' . "'index.php?components=manager&action=newcust'" . '" />';
								print '</td>';
							} else if ($_REQUEST['action'] == 'edit_custtown') {
								print '<td>Customer Grouping</td><td align="right">';
								print '<input type="button" value="Back" style="width:100px; font-size:medium" onclick="window.location = ' . "'index.php?components=manager&action=newcust'" . '" />';
								print '</td>';
							} else if ($_REQUEST['action'] == 'unlocked') {
								print '<td> Unlocked Bills</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'daily_sale') {
								print '<td>Daily Sales</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'show_map') {
								print '<td>Sales Map</td><td align="right">';
								print '<input type="button" value="Back" style="width:100px; font-size:medium" onclick="window.history.back();" />';
								print '</td>';
							} else if ($_REQUEST['action'] == 'sales_report2') {
								print '<td>Sales Report2</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'sales_report3') {
								print '<td>Sales Report2</td><td align="right">';
								print '<input type="button" value="Back" style="width:100px; font-size:medium" onclick="window.location = ' . "'$back1'" . '" />';
								print '</td>';
							} else if ($_REQUEST['action'] == 'sales_summary') {
								print '<td>Sales Summary</td><td align="right">';
								print '</td>';
							} else if ($_REQUEST['action'] == 'sales_summary_detail') {
								print '<td>Sales Summary</td><td align="right">';
								print '<div id="div_back"><input type="button" value="Back" style="width:100px; font-size:medium" onclick="backPage()" /></div>';
								print '</td>';
							} else if ($_REQUEST['action'] == 'repair_income') {
								print '<td>Repair Income</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'repair_income_one') {
								print '<td>Repair Income</td><td align="right">';
								print '<input type="button" value="Back" style="width:100px; font-size:medium" onclick="window.location = \'index.php?components=manager&action=repair_income&datefrom=' . $_GET["from"] . '&dateto=' . $_GET["to"] . '\'" />';
								print '</td>';
							} else if ($_REQUEST['action'] == 'unvisited') {
								print '<td>Visited/Unvisited Customers</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'sales_bycategory') {
								print '<td>Sales by Category</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'sales_byrep') {
								print '<td>Sales by Rep</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'sold_qty') {
								print '<td>Sold QTY</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'authorize_code') {
								print '<td>Authorize Code</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'quotation_approve') {
								print '<td>Quotation Approve</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'sn_lookup') {
								print '<td>S/N Lookup</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'quotation_list') {
								print '<td>Quotation list</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'qo_finish') {
								print '<td>Quotation</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'cust_sale') {
								print '<td>Customer Report</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'tax_report') {
								print '<td>Tax Report</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'tax_report_detail') {
								print '<td>Tax Report</td><td align="right">Tax Detail Report</td>';
							} else if ($_REQUEST['action'] == 'quotation') {
								print '<td>Quotation</td><td align="right">';
								include_once 'components/bill2/view/tpl/district.php';
								print '</td>';
							} else if ($_REQUEST['action'] == 'quotation_ongoing') {
								print '<td>On-Going Quotation</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'quotation_list') {
								print '<td>Quotation List</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'qo_terms') {
								print '<td>Terms and Condition for the Quotation</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'qo_finish') {
								print '<td>Quotation Finish</td><td align="right"></td>';
							} else {
								print '<td>Manager</td><td align="right"></td>';
							}
							break;

						case "topmanager":
							if ($_REQUEST['action'] == 'device_mgmt') {
								print '<td>Device Management</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'newcust') {
								print '<td> Customer Management</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'chque_pending_finalyze') {
								print '<td> Cheque Pending Finalyze</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'chque_realize_report_onedate') {
								print '<td> Cheque Realize Report</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'chque_realize_report_daterange') {
								print '<td> Cheque Realize Report</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'editcust') {
								print '<td> Customer Management</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'disabledcust') {
								print '<td> Customer Management</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'searchcust') {
								print '<td> Customer Management</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'show_custgroup') {
								print '<td>Customer Grouping</td><td align="right">';
								print '<input type="button" value="Back" style="width:100px; font-size:medium" onclick="window.location = ' . "'index.php?components=topmanager&action=newcust'" . '" />';
								print '</td>';
							} else if ($_REQUEST['action'] == 'edit_custgroup') {
								print '<td>Customer Grouping</td><td align="right">';
								print '<input type="button" value="Back" style="width:100px; font-size:medium" onclick="window.location = ' . "'index.php?components=topmanager&action=newcust'" . '" />';
								print '</td>';
							} else if ($_REQUEST['action'] == 'show_custtown') {
								print '<td>Customer Grouping</td><td align="right">';
								print '<input type="button" value="Back" style="width:100px; font-size:medium" onclick="window.location = ' . "'index.php?components=topmanager&action=newcust'" . '" />';
								print '</td>';
							} else if ($_REQUEST['action'] == 'edit_custtown') {
								print '<td>Customer Grouping</td><td align="right">';
								print '<input type="button" value="Back" style="width:100px; font-size:medium" onclick="window.location = ' . "'index.php?components=topmanager&action=newcust'" . '" />';
								print '</td>';
							} else if ($_REQUEST['action'] == 'unlocked') {
								print '<td> Unlocked Bills</td><td align="right"></td>';
							} else if ((bill_module(1) == 'bill2') && ($_REQUEST['action'] == 'temporary_bills')) {
								print '<td> Temporary Bills</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'daily_sale') {
								print '<td>Daily Sales</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'sales_report2') {
								print '<td>Sales Report2</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'sales_report3') {
								print '<td>Sales Report2</td><td align="right">';
								print '<input type="button" value="Back" style="width:100px; font-size:medium" onclick="window.location = ' . "'$back1'" . '" />';
								print '</td>';
							} else if ($_REQUEST['action'] == 'sales_summary') {
								print '<td>Sales Summary</td><td align="right">';
								print '</td>';
							} else if ($_REQUEST['action'] == 'sales_summary_detail') {
								print '<td>Sales Summary</td><td align="right">';
								print '<div id="div_back"><input type="button" value="Back" style="width:100px; font-size:medium" onclick="backPage()" /></div>';
								print '</td>';
							} else if ($_REQUEST['action'] == 'repair_income') {
								print '<td>Repair Income</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'repair_income_one') {
								print '<td>Repair Income</td><td align="right">';
								print '<input type="button" value="Back" style="width:100px; font-size:medium" onclick="window.location = \'index.php?components=topmanager&action=repair_income&datefrom=' . $_GET["from"] . '&dateto=' . $_GET["to"] . '\'" />';
								print '</td>';
							} else if ($_REQUEST['action'] == 'unvisited') {
								print '<td>Visited/Unvisited Customers</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'sales_bycategory') {
								print '<td>Sales by Category</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'sales_byrep') {
								print '<td>Sales by Rep</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'sold_qty') {
								print '<td>Sold QTY</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'credit') {
								print '<td>Credit Report</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'authorize_code') {
								print '<td>Authorize Code</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'cust_sale') {
								print '<td>Customer Report</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'quotation') {
								print '<td>Quotation</td><td align="right">';
								include_once 'components/bill2/view/tpl/district.php';
								print '</td>';
							} else if ($_REQUEST['action'] == 'quotation_ongoing') {
								print '<td>On-Going Quotation</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'quotation_list') {
								print '<td>Quotation List</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'qo_terms') {
								print '<td>Terms and Condition for the Quotation</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'qo_finish') {
								print '<td>Quotation Finish</td><td align="right"></td>';
							} else {
								print '<td>Top Manager</td><td align="right"></td>';
							}
							break;

						case "supervisor":
							if ($_REQUEST['action'] == 'sale') {
								print '<td>Sales Report</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'credit') {
								print '<td>Credit Report</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'sales_byrep') {
								print '<td>Sales by Rep</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'deleted') {
								print '<td>Deleted Invoices Report</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'chque') {
								print '<td>Cheque Issues</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'newcust') {
								print '<td>Customer Management</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'editcust') {
								print '<td>Customer Management</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'disabledcust') {
								print '<td> Customer Management</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'searchcust') {
								print '<td> Customer Management</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'unlocked') {
								print '<td>Unlocked Bills</td><td align="right"></td>';
							} else if ((bill_module(1) == 'bill2') && ($_REQUEST['action'] == 'temporary_bills')) {
								print '<td> Temporary Bills</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'sn_lookup') {
								print '<td>SN Lookup</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'show_custgroup') {
								print '<td>Customer Grouping</td><td align="right">';
								print '<input type="button" value="Back" style="width:100px; font-size:medium" onclick="window.location = ' . "'index.php?components=supervisor&action=newcust'" . '" />';
								print '</td>';
							} else if ($_REQUEST['action'] == 'edit_custgroup') {
								print '<td>Customer Grouping</td><td align="right">';
								print '<input type="button" value="Back" style="width:100px; font-size:medium" onclick="window.location = ' . "'index.php?components=supervisor&action=newcust'" . '" />';
								print '</td>';
							} else if ($_REQUEST['action'] == 'show_custtown') {
								print '<td>Customer Grouping</td><td align="right">';
								print '<input type="button" value="Back" style="width:100px; font-size:medium" onclick="window.location = ' . "'index.php?components=supervisor&action=newcust'" . '" />';
								print '</td>';
							} else if ($_REQUEST['action'] == 'edit_custtown') {
								print '<td>Customer Grouping</td><td align="right">';
								print '<input type="button" value="Back" style="width:100px; font-size:medium" onclick="window.location = ' . "'index.php?components=supervisor&action=newcust'" . '" />';
								print '</td>';
							} else if ($_REQUEST['action'] == 'quotation') {
								print '<td>Quotation</td><td align="right">';
								include_once 'components/bill2/view/tpl/district.php';
								print '</td>';
							} else if ($_REQUEST['action'] == 'quotation_ongoing') {
								print '<td>On-Going Quotation</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'quotation_list') {
								print '<td>Quotation List</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'qo_terms') {
								print '<td>Terms and Condition for the Quotation</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'qo_finish') {
								print '<td>Quotation Finish</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'cash_on_hand_report') {
								print '<td>Cash On Hand Report</td><td align="right"></td>';
							} else {
								print '<td>Supervisor</td><td align="right"></td>';
							}

							break;

						case "hire_purchase":
							if ($_REQUEST['action'] == 'home') {
								print '<td>Home</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'collection') {
								print '<td>Collection</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'cust_list') {
								print '<td>Customer List</td><td align="right"></td>';
							}
							break;

						case "hr":
							if ($_REQUEST['action'] == 'home') {
								print '<td>HR</td><td align="right">Apply Leave</td>';
							} else if ($_REQUEST['action'] == 'my_leave') {
								print '<td>HR</td><td align="right">My Leave</td>';
							} else if ($_REQUEST['action'] == 'leave_list') {
								print '<td>HR</td><td align="right">Leave List</td>';
							} else if ($_REQUEST['action'] == 'shop_staff') {
								print '<td>HR</td><td align="right">Shop Staff</td>';
							}
							break;

						case "to":
							if ($_REQUEST['action'] == 'quotation') {
								print '<td>Quotation</td><td align="right">';
								include_once 'components/bill2/view/tpl/district.php';
								print '</td>';
							} else if ($_REQUEST['action'] == 'quotation_ongoing') {
								print '<td>On-Going Quotation</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'quotation_list') {
								print '<td>Quotation List</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'qo_terms') {
								print '<td>Terms and Condition for the Quotation</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'qo_finish') {
								print '<td>Quotation Finish</td><td align="right"></td>';
							} else {
								print '<td>TO</td><td align="right"></td>';
							}
							break;

						case "report":
							if ($_REQUEST['action'] == 'sales_report') {
								print '<td>Sales Report</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'category_profit') {
								print '<td>Category Profit</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'sales_trend') {
								print '<td>Sales Trend</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'credit_trend') {
								print '<td>Credit Trend</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'deleted') {
								print '<td>Deleted Invoices Report</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'salesman') {
								print '<td>Salesman Report</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'salesman_invoices') {
								print '<td>Salesman Invoices</td><td align="right">';
								print '<input type="button" value="Back" style="font-size:medium; width:100px;" onclick="window.location = ' . "'index.php?components=report&action=salesman'" . '" />';
								print '</td>';
							} else if ($_REQUEST['action'] == 'chque_pending_finalyze') {
								print '<td>Cheque Pending Finalyze</td><td align="right">';
								// print '<input type="button" style="font-size:medium; width:180px;" onclick="window.location = ' . "'" . 'index.php?components=report&action=clear_chque_list&year=' . date("Y", time()) . "'" . '" value="List of Cleared Cheques" />';
								print '</td>';
							}
							// else if ($_REQUEST['action'] == 'clear_chque_list') {
							// 	print '<td>Cheque Cleared Report</td><td align="right">';
							// 	print '<input type="button" style="font-size:medium; width:180px;" onclick="window.location = ' . "'" . 'index.php?components=report&action=clear_chque_list&year=' . date("Y", time()) . "'" . '" value="List of Cleared Cheques" />';
							// 	print '</td>';
							// }
							else if ($_REQUEST['action'] == 'clear_chque_list') {
								print '<td>Cheque Report</td><td align="right">';
								print '<input type="button" style="font-size:medium; width:100px;" onclick="window.location = ' . "'" . 'index.php?components=report&action=chque_pending_finalyze' . "'" . '" value="Back" />';
								print '</td>';
							} else if ($_REQUEST['action'] == 'credit') {
								print '<td>Credit Report</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'payment_commision') {
								print '<td>Commision</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'unlocked') {
								print '<td>Unlocked Bills</td><td align="right"></td>';
							} else if ((bill_module(1) == 'bill2') && ($_REQUEST['action'] == 'temporary_bills')) {
								print '<td> Temporary Bills</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'return_items') {
								print '<td>Return Items</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'return_one') {
								print '<td>Return Invoice</td><td align="right">';
								print '<input type="button" value="Back" style="font-size:medium; width:100px;" onclick="window.location = ' . "'index.php?components=report&action=return_items&from_date=" . $_REQUEST['from_date'] . '&to_date=' . $_REQUEST['to_date'] . "'" . '" />';
								print '</td>';
							} else if ($_REQUEST['action'] == 'cost') {
								print '<td>Cost Report</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'sub') {
								print '<td>Sub Report</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'approval') {
								print '<td>Request Approval</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'authorize_code') {
								print '<td>Auth Code</td><td align="right"></td>';
							} else if ($_REQUEST['action'] == 'salesman_commission_new') {
								print '<td>Commission</td><td align="right">Commission</td>';
							} else if ($_REQUEST['action'] == 'salesman_commission_old') {
								print '<td>Commission</td><td align="right">Old Salesman Commission Report</td>';
							} else if ($_REQUEST['action'] == 'salesman_commission_one') {
								print '<td>Commission</td><td align="right">Old Salesman Commission Report</td>';
							} else if ($_REQUEST['action'] == 'salesman_commission_incomplete') {
								print '<td>Commission</td><td align="right">Incompleted Salesman Commission Report</td>';
							} else if ($_REQUEST['action'] == 'salesman_commission_incomplete_one') {
								print '<td>Commission</td><td align="right">Incompleted Salesman Commission Report</td>';
							} else if ($_REQUEST['action'] == 'profit_report') {
								print '<td>Report</td><td align="right">Profit Report</td>';
							}

							break;

						case "portalsup":
							if ($_REQUEST['action'] == 'dashboard') {
								print '<td>Supplier Portal</td><td align="right"><span style="font-size:small;">Dashboard</span></td>';
							} elseif ($_REQUEST['action'] == 'sales_report') {
								print '<td>Supplier Portal</td><td align="right"><span style="font-size:small;">Sales Report</span></td>';
							} elseif ($_REQUEST['action'] == 'monthly_sales') {
								print '<td>Supplier Portal</td><td align="right"><span style="font-size:small;">Monthly Sales</span></td>';
							} elseif ($_REQUEST['action'] == 'monthly_return') {
								print '<td>Supplier Portal</td><td align="right"><span style="font-size:small;">Monthly Return</span></td>';
							}
							break;
						case "marketing":
							if ($_REQUEST['action'] == 'mk_home') {
								// print '<td>Marketing</td><td align="right">Customer Details</td>';
								print '<td>Marketing</td><td align="right"><span style="font-size:small;">Customer Details</span></td>';
							}
							break;
					}
					?>
				</tr>
			</table>
		</h3>
	</header>