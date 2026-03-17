<?php
$mailtheme = 'w3-theme-dark';
$navtheme = 'w3-theme-d5';
$inf_company = inf_company();
?>
<!DOCTYPE html>
<html>
<title><?php print substr($inf_company, 0, 6); ?> - BackEnd</title>
<meta name="viewport" content="width=device-width, height=device-height, user-scalable=no">
<meta name="HandheldFriendly" content="true" />
<meta name="mobile-web-app-capable" content="yes">
<link rel="stylesheet" href="css/mobile1.css">
<link rel="stylesheet" href="css/mobile2.css">
<link rel="stylesheet" href="css/mobile3.css">
<script src="js/md5.min.js"></script>
<script src="js/billing.js"></script>
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
		}
		else if (window.orientation == 90) {
			if (portrait != '') document.getElementById('landscape').innerHTML = portrait;
			document.getElementById('portrait').innerHTML = '';
		}
		else if (window.orientation == 180) {
			if (landscape != '') document.getElementById('portrait').innerHTML = landscape;
			document.getElementById('landscape').innerHTML = '';
		}
		else if (window.orientation == -90) {
			if (portrait != '') document.getElementById('landscape').innerHTML = portrait;
			document.getElementById('portrait').innerHTML = '';
		}
	}, false);
</script>
<header class="w3-container w3-card-4 <?php print $mailtheme; ?> w3-top">
	<h3>
		<table width="100%">
			<tr>
				<td width="40px">
					<i class="w3-opennav fa fa-bars" onclick="w3_open()"></i>
				</td>
				<?php
				switch ($_REQUEST['components']) {
					case "authenticate":
						print '<td>' . substr($inf_company, 0, 6) . ' Backend</td><td align="right"></td>';
						break;
					case "backend":
						if ($_REQUEST['action'] == 'lock') {
							print '<td>BackEnd</td><td align="right">Lock';
							print '</td>';
						} else if ($_REQUEST['action'] == 'delete') {
							print '<td>BackEnd</td><td align="right">Delete';
							print '</td>';
						} else if ($_REQUEST['action'] == 'inv_mgmt') {
							print '<td>BackEnd</td><td align="right">INV MGMT';
							print '</td>';
						} else if ($_REQUEST['action'] == 'clear_cat') {
							print '<td>BackEnd</td><td align="right">Clear CAT';
							print '</td>';
						} else if ($_REQUEST['action'] == 'inv_order') {
							print '<td>Order Inventory</td><td align="right">';
							print '</td>';
						} else if ($_REQUEST['action'] == 'debug') {
							print '<td>BackEnd</td><td align="right">Debug';
							print '</td>';
						} else if ($_REQUEST['action'] == 'mismatch') {
							print '<td>BackEnd</td><td align="right">Mismatch';
							print '</td>';
						} else if ($_REQUEST['action'] == 'mismatch_one') {
							print '<td>BackEnd</td><td align="right">Mismatch';
							print '</td>';
						} else if ($_REQUEST['action'] == 'show_sub') {
							print '<td>BackEnd</td><td align="right">Subscription';
							print '</td>';
						} else if ($_REQUEST['action'] == 'stores') {
							print '<td>BackEnd</td><td align="right">Stores';
							print '</td>';
						} else if ($_REQUEST['action'] == 'edit_store') {
							print '<td>BackEnd</td><td align="right">Edit Store';
							print '</td>';
						} else if ($_REQUEST['action'] == 'show_add_store') {
							print '<td>BackEnd</td><td align="right">Add Store';
							print '</td>';
						} else if ($_REQUEST['action'] == 'show_last_shipment') {
							print '<td>BackEnd</td><td align="right">Delete Shipment';
							print '</td>';
						} else if ($_REQUEST['action'] == 'payment_mgmt') {
							print '<td>BackEnd</td><td align="right">Payment MGMT';
							print '</td>';
						} else if ($_REQUEST['action'] == 'change_cust') {
							print '<td>BackEnd</td><td align="right">Change Payment Cust';
							print '</td>';
						} else if ($_REQUEST['action'] == 'item_return_mgmt') {
							print '<td>BackEnd</td><td align="right">Item Return MGMT';
							print '</td>';
						} else if ($_REQUEST['action'] == 'cust_order_mgmt') {
							print '<td>BackEnd</td><td align="right">CUST ODR MGMT';
							print '</td>';
						} else if (isQuickBooksActive(1) && $_REQUEST['action'] == 'qb_delete_journal_entries') {
							print '<td>BackEnd</td><td align="right">Delete QB Journal Entries';
							print '</td>';
						} else if (isQuickBooksActive(1) && $_REQUEST['action'] == 'cash_payment_deposit') {
							print '<td>BackEnd</td><td align="right">Delete Cash Payment Deposit';
							print '</td>';
						}
						break;
				}
				?>
			</tr>
		</table>
	</h3>
</header>

<body>
	<nav class="w3-sidenav w3-card-2 w3-white w3-top" style="width:30%;display:none;z-index:2" id="mySidenav">
		<div class="w3-container <?php print $navtheme; ?>">
			<span onclick="w3_close()" class="w3-closenav w3-right w3-xlarge">x</span>
			<br>
			<div class="w3-padding w3-center">
				<img class="w3-circle" src="images/icon.png" alt="avatar" style="width:75%" />
			</div>
		</div>
		<br>
		<?php if (isset($_COOKIE['userkey'])) { ?>
			<a href="index.php?components=backend&action=lock"><strong>BackEnd</strong></a>
		<?php } ?>

		<hr />

		<?php if ($_REQUEST['components'] == 'backend') { ?>
			<a href="index.php?components=backend&action=lock">Lock</a>
			<a href="index.php?components=backend&action=delete&type">Delete</a>
			<a href="index.php?components=backend&action=inv_mgmt">INV MGMT</a>
			<a href="index.php?components=backend&action=payment_mgmt">Payment MGMT</a>
			<a href="index.php?components=backend&action=change_cust">Change Cust</a>
			<a href="index.php?components=backend&action=clear_cat">Clear CAT</a>
			<a href="index.php?components=backend&action=inv_order">Order Inv</a>
			<a href="index.php?components=backend&action=debug">Debug</a>
			<a href="index.php?components=backend&action=mismatch&list=err">Mismatch</a>
			<a href="index.php?components=backend&action=show_sub">Subscription</a>
			<a href="index.php?components=backend&action=stores">Stores</a>
			<a href="index.php?components=backend&action=show_last_shipment">Delete Shipment</a>
			<a href="index.php?components=backend&action=item_return_mgmt">Item Return MGMT</a>
			<a href="index.php?components=backend&action=cust_order_mgmt">Cust Order MGMT</a>
			<?php if (isQuickBooksActive(1)) { ?>
				<a href="index.php?components=backend&action=qb_delete_journal_entries">Delete Journal Entries</a>
			<?php } ?>
			<a href="index.php?components=backend&action=cash_payment_deposit">Delete Cash Payment Deposit</a>
		<?php } ?>
		<br />
		<br />
		<hr />
		<a href="index.php?components=authenticate&action=logout">Logout</a>

	</nav>