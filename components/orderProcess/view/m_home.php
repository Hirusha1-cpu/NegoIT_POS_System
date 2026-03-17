<?php
include_once 'template/m_header.php';
$action = $_GET['action'];
$username = $_COOKIE['user'];
if (isset($_COOKIE['store_name'])) {
	$currentst_name = $_COOKIE['store_name'];
} else {
	$currentst_name = '';
}
$orderby = 'cust';
if (isset($_COOKIE['odr_odrby'])) {
	if ($_COOKIE['odr_odrby'] == 'date') {
		$orderby = 'date';
	}
}
?>

<?php
if (isset($_REQUEST['id'])) {
	$id = $_REQUEST['id'];
} else {
	$id = 0;
}
?>
<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
<script type="text/javascript">
	// --- Auto-run function to clear old localStorage data ---
	(function clearOldLocalStorageData() {
		console.log('Running automatic cleanup of old order data...');
		const NINETY_DAYS_IN_MS = 90 * 24 * 60 * 60 * 1000;
		const cutoffTimestamp = new Date().getTime() - NINETY_DAYS_IN_MS;
		const keyPrefix = 'crossCheckState_';
		const keysToRemove = [];

		for (let i = 0; i < localStorage.length; i++) {
			const key = localStorage.key(i);
			if (key && key.startsWith(keyPrefix)) {
				try {
					const value = localStorage.getItem(key);
					const data = JSON.parse(value);
					if (data && data.lastUpdated && data.lastUpdated < cutoffTimestamp) {
						keysToRemove.push(key);
					}
				} catch (e) {
					console.error(`Could not parse localStorage item with key: ${key}. Flagging for removal.`, e);
					keysToRemove.push(key);
				}
			}
		}

		if (keysToRemove.length > 0) {
			console.log('Cleaning up old localStorage data for keys:', keysToRemove);
			keysToRemove.forEach(key => {
				localStorage.removeItem(key);
			});
		}
	})();

	function setOrderBy() {
		window.location = 'index.php?components=order_process&action=set_orderby&action2=<?php print $action; ?>';
	}
	function showQty() {
		var style = document.getElementById("div_total_qty").style.display;
		if (style == "none") document.getElementById("div_total_qty").style.display = "block";
		else document.getElementById("div_total_qty").style.display = "none";
	}
	function orderProcess(id) {
		// Show a confirmation dialog
		if (confirm("Are you sure you want to mark this order as delivered?")) {
			var data = {
				id: id,
				method: "delivered",
			};
			$.ajax({
				type: "POST",
				url: "index.php?components=order_process&action=ajax_set_delivered",
				data: data,
				beforeSend: function () {
					$("#td_" + id).html('<img src="images/LoaderIcon.gif" alt="Loading" style="width:11px;">');
				},
				success: function (data) {
					var res = JSON.parse(data);
					if (res.message) {
						var messageElement = document.getElementById('messageDisplay');
						var color = res.status == 1 ? 'green' : 'red';
						messageElement.style.color = color;
						messageElement.style.fontWeight = 'bold';
						messageElement.style.fontSize = '12pt';
						messageElement.textContent = res.message;
					}
					if (res.status == 1) {
						$("#td_" + id).html('<p style="display:inline; font-weight:bold;">Done</p>');
					} else {
						alert(res.message);
					}
				}
			});
		}
	}
</script>
<style type="text/css">
	.style2 {
		color: navy;
		font-weight: bold;
		background-color: #EEEEEE;
	}

	@media only screen and (min-device-width: 350px) and (max-device-width: 768px) {
		.tbl {
			display: block;
			overflow-x: auto;
		}
	}

	.responsive-wrapper {
		width: 100%;
		overflow-x: auto;
		-webkit-overflow-scrolling: touch;
		/* smooth scroll on iOS */
	}

	.responsive-table {
		width: 100%;
		border-collapse: collapse;
		min-width: 600px;
		/* ensures scrolling instead of squashing too much */
	}

	.responsive-table th,
	.responsive-table td {
		white-space: nowrap;
		/* prevent text wrapping */
		padding: 6px 10px;
		font-size: 12px;
	}
</style>
</head>

<div class="w3-container" style="margin-top:75px">
	<!-- Notifications -->
	<span id="messageDisplay"></span> <!-- Placeholder for the message -->
	<!--// Notifications -->
	<?php
	if (isset($_REQUEST['message'])) {
		if ($_REQUEST['re'] == 'success')
			$color = 'green';
		else
			$color = 'red';
		print '<span style="color:' . $color . '; font-weight:bold;font-size:large;">' . $_REQUEST['message'] . '</span>';
	}
	if (($action == 'list_custodr') || ($action == 'list_pending')) {
		$menu_status = '<th style="padding-left:20px; padding-right:20px">Status</th>';
	} else {
		$menu_status = $td_status = '';
	}
	$display_qty = $rtn_exclude = array();

	for ($i = 0; $i < sizeof($bi_rt_item_id); $i++) {
		$rtn_exclude[$bi_rt_item_id[$i]] = 0; //display all
	}

	for ($i = 0; $i < sizeof($bi_item_id); $i++) {
		$key = array_search($bi_item_id[$i], $bi_rt_item_id);
		if ($key > -1) {
			$display_qty[$i] = $bi_item_qty[$i] . ' + <span style="color:red">' . $bi_rt_item_qty[$key] . '</span>';
			$rtn_exclude[$bi_item_id[$i]] = 1;
		} else {
			$display_qty[$i] = $bi_item_qty[$i];
		}
	}
	?>

	<hr>
	<div class="w3-row">
		<div class="w3-col s3"></div>
		<div class="w3-col">
			<?php if ($action == 'list_delivered') { ?>
				<form action="index.php?components=order_process&action=list_delivered" id="filterForm" method="post"
					style="font-size:12pt">
					<table align="center" bgcolor="#EEEEEE" style="overflow-x: auto;display: block;">
						<tr>
							<td width="200px"></td>
							<td align="center">
								Filter By Month <input type="month" name="month_filter" value="<?php print $month_filter; ?>"
									onchange="document.getElementById('filterForm').submit()" />
							</td>
							<td width="200px"></td>
						</tr>
					</table>
				</form>
			<?php } ?>

			<?php if ($_REQUEST['action'] == 'list_my') { ?>
				<!-- Show Item by quantity and return -->
				<table width="100%">
					<tr>
						<td align="center" style="font-size:12pt">
							<i>* Returned items marked in <span style="color:red;">Red<span> color</i>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							SHOW QTY <input type="checkbox" onclick="showQty()" />
						</td>
					</tr>
				</table>
				<div id="div_total_qty" style="display:none">
					<table align="center" width="100%" style="font-size:12pt">
						<tr style="background-color:#CCCCCC">
							<th></th>
							<th>#</th>
							<th>Item</th>
							<th>Qty</th>
						</tr>
						<?php
						for ($i = 0; $i < sizeof($bi_item_desc); $i++) {
							if (($i % 2) == 0)
								$color = '#EEEEEE';
							else
								$color = '#FAFAFA';
							print '<tr style="background-color:' . $color . '">';
							print '<td align="center" width="20px"><input type="checkbox" /></td>';
							print '<td class="shipmentTB4" align="center" width="50px">' . sprintf('%02d', ($i + 1)) . '</td>';
							print '<td class="shipmentTB4">' . $bi_item_desc[$i] . '</td>';
							print '<td class="shipmentTB4" align="right" width="100px">' . $display_qty[$i] . '</td>';
							print '</tr>';
						}
						?>
					</table>
					<table align="center" width="100%" style="font-size:12pt">
						<?php
						$j = 0;
						for ($i = 0; $i < sizeof($bi_rt_item_desc); $i++) {
							if (($i % 2) == 0)
								$color = '#EEEEEE';
							else
								$color = '#FAFAFA';
							if ($rtn_exclude[$bi_rt_item_id[$i]] == 0) {
								print '<tr style="background-color:' . $color . '; color:red;">';
								print '<td align="center" width="20px"><input type="checkbox" /></td>';
								print '<td class="shipmentTB4" align="center" width="50px">' . ($j + 1) . '</td>';
								print '<td class="shipmentTB4">' . $bi_rt_item_desc[$i] . '</td>';
								print '<td class="shipmentTB4" align="right" width="100px">' . $bi_rt_item_qty[$i] . '</td>';
								print '</tr>';
								$j++;
							}
						}
						?>
					</table>
				</div>
				<br>
			<?php } ?>

			<!-- Picked item list -->
			<table align="center" style="font-size:10pt;" class="tbl">
				<tr style="background-color:#C0C0C0">
					<?php if (
						$action == 'list_shipped' || $action == 'list_delivered' || $action == 'list_packed' || $action == 'list_my' ||
						$action == 'list_custodr' || $action == 'list_pending' || $action == 'list_cross_check' || $action == 'list_my_cross_check'
					)
						print '<th width="60px;">#</th>'; ?>
					<?php if ($action == 'list_shipped')
						print '<th style="padding-left:20px; padding-right:20px;">Deliver</th>'; ?>
					<th style="padding-left:20px; padding-right:20px">Invoice No</th>
					<th style="padding-left:20px; padding-right:20px">Billed District</th>
					<th style="padding-left:20px; padding-right:20px">
						Customer <input type="checkbox" onchange="setOrderBy()" <?php if ($orderby == 'cust')
							print 'checked="checked"'; ?> />
					</th>
					<th style="padding-left:20px; padding-right:20px">Billed Store</th>
					<th style="padding-left:20px; padding-right:20px">Salesman</th>
					<th style="padding-left:20px; padding-right:20px">
						Billed Date<input type="checkbox" onchange="setOrderBy()" <?php if ($orderby == 'date')
							print 'checked="checked"'; ?> />
					</th>
					<th style="padding-left:20px; padding-right:20px"><?php print $menu_by; ?></th>
					<th style="padding-left:20px; padding-right:20px"><?php print $menu_date; ?></th>
					<?php print $menu_status; ?>
				</tr>
				<?php
				if ($action == 'list_custodr' || $action == 'list_pending') {
					for ($i = 0; $i < sizeof($bi_invoice_no); $i++) {
						$action0 = 'list_one';
						if ($bi_status[$i] == 1) {
							$td_status = '<td align="center" style="color:green">Pending</td>';
						} else if ($bi_status[$i] == 11) {
							$td_status = '<td align="center" style="color:blue">Cross Check Picked</td>';
						} else if ($bi_status[$i] == 2) {
							$td_status = '<td align="center" style="color:orange">Picked</td>';
							if (($type == '4,5') && ($bi_seen_by[$i] == $username))
								$action0 = 'list_one_custodr';
						}
						if ($currentst_name != $bm_store[$i])
							$color2 = 'red';
						else
							$color2 = 'black';
						print '<tr style="background-color:#F0F0F0">
									<td align="center">' . sprintf('%02d', ($i + 1)) . '</td>
									<td align="center">
										<a href="index.php?components=order_process&action=' . $action0 . '&id=' . $bi_invoice_no[$i] . '">' . str_pad($bi_invoice_no[$i], 7, "0", STR_PAD_LEFT) . '</a>
									</td>
									<td style="padding-left:20px;">' . $bi_district[$i] . '</td>
									<td style="padding-left:20px;">' . $bi_cust[$i] . '</td>
									<td style="padding-left:20px; color:' . $color2 . '">' . $bm_store[$i] . '</td>
									<td style="padding-left:20px;">' . ucfirst($bi_billed_by[$i]) . '</td>
									<td align="center">
										<a style="cursor:pointer; color:blue;" title="Time: ' . substr($bi_billed_time[$i], 0, 5) . '">' . $bi_billed_date[$i] . '</a>
									</td>
									<td style="padding-left:20px;">' . ucfirst($bi_seen_by[$i]) . '</td>
									<td align="center">
										<a style="cursor:pointer; color:blue;" title="Time: ' . substr($bi_seen_time[$i], 0, 5) . '">' . $bi_seen_date[$i] . '</a>
									</td>' . $td_status . '</tr>';
					}
				} elseif ($action == 'list_my') {
					for ($i = 0; $i < sizeof($bi_invoice_no); $i++) {
						if ($bi_type[$i] == 4 || $bi_type[$i] == 5)
							$action0 = 'list_one_custodr';
						else
							$action0 = 'list_one';
						if ($currentst_name != $bm_store[$i])
							$color2 = 'red';
						else
							$color2 = 'black';
						print '<tr style="background-color:#F0F0F0">
									<td align="center">' . sprintf('%02d', ($i + 1)) . '</td>
									<td align="center"><a href="index.php?components=order_process&action=' . $action0 . '&id=' . $bi_invoice_no[$i] . '">' . str_pad($bi_invoice_no[$i], 7, "0", STR_PAD_LEFT) . '</a></td>
									<td style="padding-left:20px;">' . $bi_district[$i] . '</td>
									<td style="padding-left:20px;">' . $bi_cust[$i] . '</td>
									<td style="padding-left:20px; color:' . $color2 . '">' . $bm_store[$i] . '</td>
									<td style="padding-left:20px;">' . ucfirst($bi_billed_by[$i]) . '</td>
									<td align="center"><a style="cursor:pointer; color:blue;" title="Time: ' . substr($bi_billed_time[$i], 0, 5) . '">' . $bi_billed_date[$i] . '</a></td>
									<td style="padding-left:20px;">' . ucfirst($bi_seen_by[$i]) . '</td>
									<td align="center"><a style="cursor:pointer; color:blue;" title="Time: ' . substr($bi_seen_time[$i], 0, 5) . '">' . $bi_seen_date[$i] . '</a></td></tr>';
					}
				} else if ($action == 'list_cross_check') {
					for ($i = 0; $i < sizeof($bi_invoice_no); $i++) {
						if ($bi_type[$i] == 4 || $bi_type[$i] == 5) {
							$action0 = 'list_one';
							$td_type = '<td align="center" style="color:green">Cust Order</td>';
						} else {
							$action0 = 'list_one';
							$td_type = '<td align="center" style="color:orange">Bill</td>';
						}
						if ($currentst_name != $bm_store[$i])
							$color2 = 'red';
						else
							$color2 = 'black';
						print '<tr style="background-color:#F0F0F0">
						<td align="center">' . sprintf('%02d', ($i + 1)) . '</td>
						<td align="center">
							<a href="index.php?components=order_process&action=' . $action0 . '&id=' . $bi_invoice_no[$i] . '">' . str_pad($bi_invoice_no[$i], 7, "0", STR_PAD_LEFT) . '</a>
						</td>
						<td style="padding: 0px 10px;">' . $bi_district[$i] . '</td>
						<td style="padding: 0px 10px;">' . $bi_cust[$i] . '</td>
						<td style="padding: 0px 10px; color:' . $color2 . '">' . $bm_store[$i] . '</td>
						<td style="padding: 0px 10px;">' . ucfirst($bi_billed_by[$i]) . '</td>
						<td align="center">
							<a style="cursor:pointer; color:blue;" title="Time: ' . substr($bi_billed_time[$i], 0, 5) . '">' . $bi_billed_date[$i] . '</a>
						</td>
						<td style="padding: 0px 10px;">' . ucfirst($bi_seen_by[$i]) . '</td>
						<td align="center">
							<a style="cursor:pointer; color:blue;" title="Time: ' . substr($bi_seen_time[$i], 0, 5) . '">' . $bi_seen_date[$i] . '</a>
						</td></tr>';
					}
				} else if ($action == 'list_my_cross_check') {
					for ($i = 0; $i < sizeof($bi_invoice_no); $i++) {
						if ($bi_type[$i] == 4 || $bi_type[$i] == 5) {
							$action0 = 'list_one_custodr';
							$td_type = '<td align="center" style="color:green">Cust Order</td>';
						} else {
							$action0 = 'list_one';
							$td_type = '<td align="center" style="color:orange">Bill</td>';
						}
						if ($currentst_name != $bm_store[$i])
							$color2 = 'red';
						else
							$color2 = 'black';
						print '<tr style="background-color:#F0F0F0">
						<td align="center">' . sprintf('%02d', ($i + 1)) . '</td>
						<td align="center">
							<a href="index.php?components=order_process&action=' . $action0 . '&id=' . $bi_invoice_no[$i] . '">' . str_pad($bi_invoice_no[$i], 7, "0", STR_PAD_LEFT) . '</a>
						</td>
						<td style="padding: 0px 10px;">' . $bi_district[$i] . '</td>
						<td style="padding: 0px 10px;">' . $bi_cust[$i] . '</td>
						<td style="padding: 0px 10px; color:' . $color2 . '">' . $bm_store[$i] . '</td>
						<td style="padding: 0px 10px;">' . ucfirst($bi_billed_by[$i]) . '</td>
						<td align="center">
							<a style="cursor:pointer; color:blue;" title="Time: ' . substr($bi_billed_time[$i], 0, 5) . '">' . $bi_billed_date[$i] . '</a>
						</td>
						<td style="padding: 0px 10px;">' . ucfirst($bi_seen_by[$i]) . '</td>
						<td align="center">
							<a style="cursor:pointer; color:blue;" title="Time: ' . substr($bi_seen_time[$i], 0, 5) . '">' . $bi_seen_date[$i] . '</a>
						</td></tr>';
					}
				} else if ($action == 'list_packed') {
					for ($i = 0; $i < sizeof($bi_invoice_no); $i++) {
						if ($currentst_name != $bm_store[$i])
							$color2 = 'red';
						else
							$color2 = 'black';
						print '<tr style="background-color:#F0F0F0">
									<td align="center">' . sprintf('%02d', ($i + 1)) . '</td>
									<td align="center">
										<a href="index.php?components=order_process&action=list_one&id=' . $bi_invoice_no[$i] . '">' . str_pad($bi_invoice_no[$i], 7, "0", STR_PAD_LEFT) . '</a>
									</td>
									<td style="padding-left:20px;">' . $bi_district[$i] . '</td>
									<td style="padding-left:20px;">' . $bi_cust[$i] . '</td>
									<td style="padding-left:20px; color:' . $color2 . '">' . $bm_store[$i] . '</td>
									<td style="padding-left:20px;">' . ucfirst($bi_billed_by[$i]) . '</td>
									<td align="center">
										<a style="cursor:pointer; color:blue;" title="Time: ' . substr($bi_billed_time[$i], 0, 5) . '">' . $bi_billed_date[$i] . '</a>
									</td>
									<td style="padding-left:20px;">' . ucfirst($bi_packed_by[$i]) . '</td>
									<td align="center">
										<a style="cursor:pointer; color:blue;" title="Time: ' . substr($bi_packed_time[$i], 0, 5) . '">' . $bi_packed_date[$i] . '</a>
									</td></tr>';
					}
				} else if ($action == 'list_shipped') {
					for ($i = 0; $i < sizeof($bi_invoice_no); $i++) {
						if ($currentst_name != $bm_store[$i])
							$color2 = 'red';
						else
							$color2 = 'black';
						print '<tr style="background-color:#F0F0F0">
									<td align="center">' . sprintf('%02d', ($i + 1)) . '</td>
									<td id="td_' . $bi_invoice_no[$i] . '" align="center">
										<input type="checkbox" id="id_' . $bi_invoice_no[$i] . '" onclick="orderProcess(\'' . $bi_invoice_no[$i] . '\')"/>
									</td>
									<td align="center">
										<a href="index.php?components=order_process&action=list_one&id=' . $bi_invoice_no[$i] . '">' . str_pad($bi_invoice_no[$i], 7, "0", STR_PAD_LEFT) . '</a>
									</td>
									<td style="padding-left:20px;">' . $bi_district[$i] . '</td>
									<td style="padding-left:20px;">' . $bi_cust[$i] . '</td>
									<td style="padding-left:20px; color:' . $color2 . '">' . $bm_store[$i] . '</td>
									<td style="padding-left:20px;">' . ucfirst($bi_billed_by[$i]) . '</td>
									<td align="center"><a style="cursor:pointer; color:blue;" title="Time: ' . substr($bi_billed_time[$i], 0, 5) . '">' . $bi_billed_date[$i] . '</a></td>
									<td style="padding-left:20px;">' . ucfirst($bi_shipped_by[$i]) . '</td>
									<td align="center"><a style="cursor:pointer; color:blue;" title="Time: ' . substr($bi_shipped_time[$i], 0, 5) . '">' . $bi_shipped_date[$i] . '</a></td>
								</tr>';
					}
				} else if ($action == 'list_delivered') {
					for ($i = 0; $i < sizeof($bi_invoice_no); $i++) {
						if ($currentst_name != $bm_store[$i])
							$color2 = 'red';
						else
							$color2 = 'black';
						if ($bi_deliverd_by[$i] != '')
							print '<tr style="background-color:#F0F0F0">
									<td align="center">' . sprintf('%02d', ($i + 1)) . '</td>
									<td align="center">
										<a href="index.php?components=order_process&action=list_one&id=' . $bi_invoice_no[$i] . '">' . str_pad($bi_invoice_no[$i], 7, "0", STR_PAD_LEFT) . '</a>
									</td>
									<td style="padding-left:20px;">' . $bi_district[$i] . '</td>
									<td style="padding-left:20px;">' . $bi_cust[$i] . '</td>
									<td style="padding-left:20px; color:' . $color2 . '">' . $bm_store[$i] . '</td>
									<td style="padding-left:20px;">' . ucfirst($bi_billed_by[$i]) . '</td>
									<td align="center">
										<a style="cursor:pointer; color:blue;" title="Time: ' . substr($bi_billed_time[$i], 0, 5) . '">' . $bi_billed_date[$i] . '</a>
									</td>
									<td style="padding-left:20px;">' . ucfirst($bi_deliverd_by[$i]) . '</td>
									<td align="center">
										<a style="cursor:pointer; color:blue;" title="Time: ' . substr($bi_deliverd_time[$i], 0, 5) . '">' . $bi_deliverd_date[$i] . '</a>
									</td>
								</tr>';
					}
				}
				?>
			</table>
		</div>
	</div>
</div>

<hr>

<?php
include_once 'template/m_footer.php';
?>