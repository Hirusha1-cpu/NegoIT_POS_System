<?php
include_once 'template/header.php';
$menu_components = $_GET['components'];
$decimal = getDecimalPlaces(1);
?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
	google.charts.load('current', { 'packages': ['corechart'] });
	google.charts.setOnLoadCallback(drawVisualization);

	function drawVisualization() {
		// Some raw data (not necessarily accurate)
		var data = google.visualization.arrayToDataTable([['Salesman', 'Sale'],
			<?php for ($i = 0; $i < sizeof($graph_user); $i++) {
				print "['" . ucfirst($graph_user[$i]) . "',$graph_total[$i]],";
			}
			?>
		]);

		var options = {
			vAxis: { title: 'Sales (Invoiced Amount)' },
			hAxis: {
				direction: -1,
				slantedText: true,
				slantedTextAngle: 45 // here you can even use 180
			},
			legend: { position: "none" },
			seriesType: 'bars',
			series: { 5: { type: 'line' } }
		};

		var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
		chart.draw(data, options);
	}
</script>
<style>
	.padding {
		padding-left: 10px;
		padding-right: 10px;
	}
</style>

<!-- ------------------Item List----------------------- -->
<div style="background-color:#EEEEEE; border-radius:10px">
	<table align="center" height="100%" cellspacing="0" style="font-family:Calibri" bgcolor="#F0F0F0" border="0">
		<tr>
			<td width="50px"></td>
			<td colspan="4">
				<form id="search_form" action="index.php" method="get">
					<input type="hidden" name="components" value="<?php print $menu_components; ?>" />
					<input type="hidden" name="action" value="daily_sale" />
					<input type="hidden" name="store" value="<?php echo isset($_GET['store'])
						? htmlspecialchars($_GET['store'], ENT_QUOTES, 'UTF-8')
						: ''; ?>" />

					<input type="hidden" name="group" value="<?php echo isset($_GET['group'])
						? htmlspecialchars($_GET['group'], ENT_QUOTES, 'UTF-8')
						: ''; ?>" />

					<input type="hidden" name="salesman" value="<?php echo isset($_GET['salesman'])
						? htmlspecialchars($_GET['salesman'], ENT_QUOTES, 'UTF-8')
						: ''; ?>" />

					<input type="hidden" name="processby" value="<?php echo isset($_GET['processby'])
						? htmlspecialchars($_GET['processby'], ENT_QUOTES, 'UTF-8')
						: ''; ?>" />

					<input type="hidden" name="lock" value="<?php echo isset($_GET['lock'])
						? htmlspecialchars($_GET['lock'], ENT_QUOTES, 'UTF-8')
						: ''; ?>" />

					<input type="hidden" name="type" value="<?php echo isset($_GET['type'])
						? htmlspecialchars($_GET['type'], ENT_QUOTES, 'UTF-8')
						: ''; ?>" />

					<input type="hidden" name="cashback" value="<?php echo isset($_GET['cashback'])
						? htmlspecialchars($_GET['cashback'], ENT_QUOTES, 'UTF-8')
						: ''; ?>" />
					<table>
						<tr>
							<td align="center" bgcolor="silver" style="color:white">&nbsp;Date Range&nbsp;<br><input
									type="checkbox" id="date_range"
									onchange="setDateRange('<?php print $date1; ?>','<?php print $date2; ?>')" <?php if (($date1 != '') && ($date2 != ''))
											  print 'checked="checked"'; ?> /></td>
							<td>
								<div id="datediv">
									<?php
									if (($date1 != '') && ($date2 != '')) {
										print '<strong>From </strong>: &nbsp;<input type="date" name="date1" style="width:130px" value="' . $date1 . '" />&nbsp;&nbsp;&nbsp;<strong>To </strong>: &nbsp;<input type="date" name="date2" style="width:130px" value="' . $date2 . '" />';
									} else {
										print '<strong>Date</strong>: &nbsp;<input type="date" name="date1" style="width:130px" value="' . $date1 . '" />';
									}
									?>
								</div>
							</td>
							<td>
								<a onclick="document.getElementById('search_form').submit();"
									style="cursor:pointer"><img src="images/search.png"
										style="width:30px; vertical-align:middle" /></a>
							</td>
						</tr>
					</table>
				</form>
			</td>
			<td align="right"><strong>Process By : </strong></td>
			<td>
				<select id="processby0" <?php if (isset($userdisable))
					print $userdisable; ?>
					onchange="window.location = 'index.php?components=<?php print $menu_components; ?>&action=daily_sale&store='+document.getElementById('store0').value+'&group='+document.getElementById('group0').value+'&salesman='+document.getElementById('salesman0').value+'&processby='+document.getElementById('processby0').value+'&lock='+document.getElementById('lock').value+'&type='+document.getElementById('type').value+'&cashback='+document.getElementById('cashback').value+'&date1=<?php print $date1; ?>&date2=<?php print $date2; ?>'">
					<option value="all">--ALL--</option>
					<?php
					$processbyname = 'ALL';
					for ($i = 0; $i < sizeof($up_id); $i++) {
						if ($up_id[$i] == $_GET['processby']) {
							$select = 'selected="selected"';
							$processbyname = ucfirst($up_name[$i]);
						} else {
							$select = '';
						}
						print '<option value="' . $up_id[$i] . '" ' . $select . '>' . ucfirst($up_name[$i]) . '</option>';
					}
					?>
				</select>
			</td>

				<td align="right" <?php if ($menu_components != 'manager' && $menu_components != 'topmanager') { print 'style=display:none'; ?> <?php } ?>><strong>Cash Back :</strong></td>
				<td <?php if ($menu_components != 'manager' && $menu_components != 'topmanager') { print 'style=display:none'; ?> <?php } ?>>
					<select id="cashback"
					<?php if ($menu_components != 'manager' && $menu_components != 'topmanager') { print 'style=display:none'; ?> <?php } ?>
						onchange="window.location = 'index.php?components=<?php print $menu_components; ?>&action=daily_sale&store='+document.getElementById('store0').value+'&group='+document.getElementById('group0').value+'&salesman='+document.getElementById('salesman0').value+'&processby='+document.getElementById('processby0').value+'&lock='+document.getElementById('lock').value+'&type='+document.getElementById('type').value+'&cashback='+document.getElementById('cashback').value+'&date1=<?php print $date1; ?>&date2=<?php print $date2; ?>'">
						<option value="yes" <?php if (isset($_GET['cashback']) && $_GET['cashback'] == 'yes')
							print 'selected="selected"'; ?>>-YES-
						</option>
						<option value="no" <?php if (isset($_GET['cashback']) && $_GET['cashback'] == 'no')
							print 'selected="selected"'; ?>>-NO-</option>
					</select>
				</td>

			<td colspan="2" width="200px" align="right">
				<?php if ($menu_components == 'manager' || $menu_components == 'topmanager') { ?>
					<input style="height:30px" type="button" value="Detail Report"
						onclick="window.location = 'index.php?components=<?php print $menu_components; ?>&action=daily_sale_detail&store='+document.getElementById('store0').value+'&group='+document.getElementById('group0').value+'&salesman='+document.getElementById('salesman0').value+'&processby='+document.getElementById('processby0').value+'&lock='+document.getElementById('lock').value+'&type='+document.getElementById('type').value+'&cashback='+document.getElementById('cashback').value+'&date=<?php print $date1; ?>'" />
				<?php } ?>
			</td>
			<td width="50px" rowspan="2" valign="middle">
				<?php if ($menu_components == 'manager' || $menu_components == 'topmanager') { ?>
					<?php if ($gps != '')
						print '<form id="map_form" method="post" action="index.php?components='.$menu_components.'&action=show_map" target="_blank" style="cursor:pointer"><input type="hidden" name="gps" value="' . $gps . '" /><a onclick="document.getElementById(' . "'map_form'" . ').submit();"><img src="images/map.png" width="90%" /></a></form>'; ?>
				<?php } ?>
			</td>
		</tr>
		<tr>
			<td width="50px"></td>
			<td align="right"><strong> Store : </strong></td>
			<td>
				<select id="store0" <?php if (isset($storedisable))
					print $storedisable; ?>
					onchange="window.location = 'index.php?components=<?php print $menu_components; ?>&action=daily_sale&store='+document.getElementById('store0').value+'&group='+document.getElementById('group0').value+'&salesman='+document.getElementById('salesman0').value+'&processby='+document.getElementById('processby0').value+'&lock='+document.getElementById('lock').value+'&type='+document.getElementById('type').value+'&cashback='+document.getElementById('cashback').value+'&date1=<?php print $date1; ?>&date2=<?php print $date2; ?>'">
					<option value="all">--ALL--</option>
					<?php
					$stname = 'ALL Stores';
					for ($i = 0; $i < sizeof($store_id); $i++) {
						if ($store_id[$i] == $_GET['store']) {
							$select = 'selected="selected"';
							$stname = ucfirst($store_name[$i]);
						} else {
							$select = '';
						}
						print '<option value="' . $store_id[$i] . '" ' . $select . '>' . ucfirst($store_name[$i]) . '</option>';
					}
					?>
				</select>
			</td>
			<?php
				$gpname = 'ALL Groups';
				if(($menu_components!='billing')&&($menu_components!='bill2')){ ?>
					<td width="80px" align="right"><strong>Group : </strong></td>
					<td>
						<select id="group0"
							onchange="window.location = 'index.php?components=<?php print $menu_components; ?>&action=daily_sale&store='+document.getElementById('store0').value+'&group='+document.getElementById('group0').value+'&salesman='+document.getElementById('salesman0').value+'&processby='+document.getElementById('processby0').value+'&lock='+document.getElementById('lock').value+'&type='+document.getElementById('type').value+'&cashback='+document.getElementById('cashback').value+'&date1=<?php print $date1; ?>&date2=<?php print $date2; ?>'">
							<option value="all">--ALL--</option>
							<?php
							$gpname = 'ALL Groups';
							for ($i = 0; $i < sizeof($gp_id); $i++) {
								if ($gp_id[$i] == $_GET['group']) {
									$select = 'selected="selected"';
									$gpname = ucfirst($gp_name[$i]);
								} else {
									$select = '';
								}
								print '<option value="' . $gp_id[$i] . '" ' . $select . '>' . ucfirst($gp_name[$i]) . '</option>';
							}
							?>
						</select>
					</td>
				<?php
			}else{
				print '<td></td><td>';
				print '<input type="hidden" id="group0" value="all" />';
			}
			?>
			<td width="100px" align="right"><strong>Salesman : </strong></td>
			<td>
				<select id="salesman0"
					<?php if (isset($userdisable)) print $userdisable; ?>
					onchange="window.location = 'index.php?components=<?php print $menu_components; ?>&action=daily_sale&store='+document.getElementById('store0').value+'&group='+document.getElementById('group0').value+'&salesman='+document.getElementById('salesman0').value+'&processby='+document.getElementById('processby0').value+'&lock='+document.getElementById('lock').value+'&type='+document.getElementById('type').value+'&cashback='+document.getElementById('cashback').value+'&date1=<?php print $date1; ?>&date2=<?php print $date2; ?>'">
					<option value="all">--ALL--</option>
					<?php
					$salesmanname = 'ALL';
					for ($i = 0; $i < sizeof($up_id); $i++) {
						if ($up_id[$i] == $_GET['salesman']) {
							$select = 'selected="selected"';
							$salesmanname = ucfirst($up_name[$i]);
						} else {
							$select = '';
						}
						print '<option value="' . $up_id[$i] . '" ' . $select . '>' . ucfirst($up_name[$i]) . '</option>';
					}
					?>
				</select>
			</td>
			<td width="100px" align="right"><strong>Bill Status : </strong></td>
			<td align="right">
				<select id="lock"
					onchange="window.location = 'index.php?components=<?php print $menu_components; ?>&action=daily_sale&store='+document.getElementById('store0').value+'&group='+document.getElementById('group0').value+'&salesman='+document.getElementById('salesman0').value+'&processby='+document.getElementById('processby0').value+'&lock='+document.getElementById('lock').value+'&type='+document.getElementById('type').value+'&cashback='+document.getElementById('cashback').value+'&date1=<?php print $date1; ?>&date2=<?php print $date2; ?>'">
					<option value="1" <?php if ($lock_req == 1) {
						print 'selected="selected"';
						$lockname = 'Lock';
					} ?>>Lock
					</option>
					<option value="0" <?php if ($lock_req == 0) {
						print 'selected="selected"';
						$lockname = 'Unlock';
					} ?>>
						Unlock</option>
					<option value="all" <?php if ($lock_req == 'all') {
						print 'selected="selected"';
						$lockname = 'ALL';
					} ?>>
						--ALL--</option>
				</select>
			</td>
			<td width="100px" align="right"><strong>Type : </strong></td>
			<td align="right">
				<select id="type"
					onchange="window.location = 'index.php?components=<?php print $menu_components; ?>&action=daily_sale&store='+document.getElementById('store0').value+'&group='+document.getElementById('group0').value+'&salesman='+document.getElementById('salesman0').value+'&processby='+document.getElementById('processby0').value+'&lock='+document.getElementById('lock').value+'&type='+document.getElementById('type').value+'&cashback='+document.getElementById('cashback').value+'&date1=<?php print $date1; ?>&date2=<?php print $date2; ?>'">
					<option value="" <?php if ($type_req == '') {
						print 'selected="selected"';
						$typename = 'ALL';
					} ?>>--ALL--
					</option>
					<option value="1" <?php if ($type_req == 1) {
						print 'selected="selected"';
						$typename = 'Product';
					} ?>>
						Product</option>
					<option value="2" <?php if ($type_req == 2) {
						print 'selected="selected"';
						$typename = 'Service';
					} ?>>
						Service</option>
					<option value="3" <?php if ($type_req == 3) {
						print 'selected="selected"';
						$typename = 'Return';
					} ?>>
						Return</option>
					<option value="4" <?php if ($type_req == 4) {
						print 'selected="selected"';
						$typename = 'Repair';
					} ?>>
						Repair</option>
					<option value="5" <?php if ($type_req == 5) {
						print 'selected="selected"';
						$typename = 'Warranty';
					} ?>>
						Warranty</option>
				</select>
			</td>
		</tr>
	</table>
	<?php if ($systemid == 14) { ?>
		<table align="center" style="font-size: 10pt; margin-top: 10px;">
			<tr>
				<td width="20px" bgcolor="#65B741"></td>
				<td style="padding-right:20px;"> - Retail Invoice</td>
				<td width="20px" bgcolor="#0000EE"></td>
				<td style="padding-right:20px;"> - Wholesale Invoice</td>
				<td width="20px" bgcolor="#F57D1F"></td>
				<td style="padding-right:20px;"> - Retail + Wholesale Invoice</td>
				<td width="20px" bgcolor="#FF0000"></td>
				<td style="padding-right:20px;"> - Discounted Invoice</td>
			</tr>
		</table>
	<?php } else { ?>
		<table align="center" style="font-size: 10pt; margin-top: 10px;">
			<tr>
				<td width="20px" bgcolor="#FF0000"></td>
				<td style="padding-right:20px;"> - Discounted Invoice</td>
			</tr>
		</table>
	<?php } ?>
</div>

<div id="printheader" style="display:none">
	<h2 align="center" style="color:navy"><?php print $inf_company; ?></h2>
	<h3 align="center" style="color:#333399; text-decoration:underline">Daily Sales Statement</h3>
	<table>
		<tr>
			<td>
				<table style="font-size:12pt" border="1" cellspacing="0">
					<tr>
						<td style="background-color:#C0C0C0; padding-left:10px">Date</td>
						<td style="background-color:#EEEEEE; padding-left:10px">
							<?php if ($date2 != '')
								print 'From ';
							print $date1;
							if ($date2 != '')
								print '<br />To ' . $date2; ?>
						</td>
					</tr>
					<tr>
						<td width="100px" style="background-color:#C0C0C0; padding-left:10px">Store</td>
						<td width="250px" style="background-color:#EEEEEE; padding-left:10px"><?php print $stname; ?>
						</td>
					</tr>
					<tr>
						<td style="background-color:#C0C0C0; padding-left:10px">Salesman</td>
						<td style="background-color:#EEEEEE; padding-left:10px"><?php print $salesmanname; ?></td>
					</tr>
				</table>
			</td>
			<td width="100px"></td>
			<td>
				<table style="font-size:12pt" border="1" cellspacing="0">
					<tr>
						<td width="100px" style="background-color:#C0C0C0; padding-left:10px">Group</td>
						<td width="250px" style="background-color:#EEEEEE; padding-left:10px"><?php print $gpname; ?>
						</td>
					</tr>
					<tr>
						<td style="background-color:#C0C0C0; padding-left:10px">Bill Status</td>
						<td style="background-color:#EEEEEE; padding-left:10px"><?php print $lockname; ?></td>
					</tr>
					<tr>
						<td style="background-color:#C0C0C0; padding-left:10px">Bill Type</td>
						<td style="background-color:#EEEEEE; padding-left:10px"><?php print $typename; ?></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<hr />
</div>

<table align="center">
	<tr>
		<td>
			<div id="chart_div" style="width: 900px; height: 300px;"></div>
		</td>
	</tr>
</table>

<br /><br />
<div id="print">
	<?php if ($type_req != 3 && $type_req != 5) { ?>
		<table align="center" bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0" style="font-family:Calibri">
			<tr>
				<td colspan="11" style="border:0; background-color:black; color:white; font-weight:bold">Invoiced Collection
				</td>
			</tr>
			<tr>
				<th width="60px">#</th>
				<th width="100px">Invoice No</th>
				<th width="140px">Date/Time</th>
				<th width="100px">Invoice Total</th>
				<th width="100px">Cash</th>
				<th width="100px">Card</th>
				<th width="100px">Bank</th>
				<th width="100px">Cheque</th>
				<th width="100px">Credit</th>
				<th width="100px">Salesman</th>
				<th width="300px">Customer</th>
			</tr>
			<?php
			$inv = 0;
			$store0 = '';
			$total_cash = $total_bank = $total_chque = $total_credit = $total_card = 0;

			for ($i = 0; $i < sizeof($invoice_no); $i++) {
				if ($systemid == 14) {
					$inv_color = '#000000';
					if ($item_type[$invoice_no[$i]] == '') {
						$inv_color = '#000000';
					} else if ($item_type[$invoice_no[$i]] == 1) {
						$inv_color = '#65B741';
					} else if ($item_type[$invoice_no[$i]] == 2) {
						$inv_color = '#0000EE';
					} else if ($item_type[$invoice_no[$i]] == 3) {
						$inv_color = '#F57D1F';
					}
				} else {
					$inv_color = '#0000EE';
				}
				if ($bi_discount[$i] > 0) {
					$color = 'style="color:red"';
					$title = 'title="Discounted Invoice"';
				} else {
					$color = '';
					$title = '';
				}
				if ($store0 != $billed_store[$i]) {
					print '<tr>
								<td colspan="11" style="padding-left:20px; font-weight:bold; background-color:#0066AA; color:white">' . $billed_store[$i] . '</td>
							</tr>';
				}
				$bill_credit = $invoice_Total[$i] - $payment_cash[$invoice_no[$i]] - $payment_card[$invoice_no[$i]] - $payment_bank[$invoice_no[$i]] - $payment_chque[$invoice_no[$i]];
				print '<tr ' . $color . '>
							<td align="center">
								' . sprintf('%02d', ($i + 1)) . '
							</td>
							<td align="center">
								<a ' . $title . ' href="index.php?components=billing&action=finish_bill&id=' . $invoice_no[$i] . '" style="color:' . $inv_color . '">' . str_pad($invoice_no[$i], 7, "0", STR_PAD_LEFT) . '</a>
							</td>
							<td align="center" width="50px">' . $billed_time[$i] . '</td>
							<td align="right" class="padding">' . number_format($invoice_Total[$i], $decimal) . '</td>
							<td align="right" class="padding">' . number_format($payment_cash[$invoice_no[$i]], $decimal) . '</td>
							<td align="right" class="padding"><a href="#" style="text-decoration:none" title="' . $payment_card_tr[$invoice_no[$i]] . '">' . number_format($payment_card[$invoice_no[$i]], $decimal) . '</a></td>
							<td align="right" class="padding"><a href="#" style="text-decoration:none" title="' . $payment_bank_tr[$invoice_no[$i]] . '">' . number_format($payment_bank[$invoice_no[$i]], $decimal) . '</a></td>
							<td align="right" class="padding"><a href="#" style="text-decoration:none" title="' . $chq_details[$invoice_no[$i]] . '">' . number_format($payment_chque[$invoice_no[$i]], $decimal) . '</a></td>
							<td  align="right" class="padding">' . number_format($bill_credit, $decimal) . '</td>
							<td  class="shipmentTB3">' . ucfirst($billed_by[$i]) . '</td>
							<td class="shipmentTB3">' . ucfirst($billed_cust[$i]) . '</td></tr>';
				$total_cash += $payment_cash[$invoice_no[$i]];
				$total_card += $payment_card[$invoice_no[$i]];
				$total_bank += $payment_bank[$invoice_no[$i]];
				$total_chque += $payment_chque[$invoice_no[$i]];
				$total_credit += $bill_credit;
				if (sizeof($invoice_no) == ($i + 1)) {
					print '<tr style="font-weight:bold; background-color:gray; color:white">
								<td colspan="3" align="right"  class="padding">Total</td>
								<td align="right" class="padding">' . number_format(array_sum($invoice_Total), $decimal) . '</td>
								<td align="right" class="padding">' . number_format($total_cash, $decimal) . '</td>
								<td align="right" class="padding">' . number_format($total_card, $decimal) . '</td>
								<td align="right" class="padding">' . number_format($total_bank, $decimal) . '</td>
								<td align="right" class="padding">' . number_format($total_chque, $decimal) . '</td>
								<td align="right" class="padding">' . number_format($total_credit, $decimal) . '</td>
								<td colspan="2"></td></tr>';
					print '</table><br /><table align="center" bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0">';
				}
				$store0 = $billed_store[$i];
			}
			?>
		</table>
	<?php } ?>
	<br />
	<?php if ($type_req != 3 && $type_req != 5) { ?>
		<table align="center" bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0" style="font-family:Calibri">
			<tr>
				<td colspan="11" style="border:0; background-color:black; color:white; font-weight:bold">Payment Collection
				</td>
			</tr>
			<tr>
				<th width="60px">#</th>
				<th width="100px">Payment No</th>
				<th width="140px">Date/Time</th>
				<th width="100px">Cash</th>
				<th width="100px">Card</th>
				<th width="100px">Bank</th>
				<th width="100px">Cheque</th>
				<th width="100px">Cheque No</th>
				<th width="100px">Cheque Date</th>
				<th width="100px">Salesman</th>
				<th width="300px">Customer</th>
			</tr>
			<?php
			$inv = $pay_total_cash = $pay_total_card = $pay_total_bank = $pay_total_cheque = 0;
			$store0 = '';
			for ($i = 0; $i < sizeof($payment_id); $i++) {
				$pay_cash = $pay_card = $pay_bank = $pay_cheque = '';
				if ($payment_type[$i] == 'Cash') {
					$pay_cash = number_format($payment_amount[$i], $decimal);
					$pay_total_cash += $payment_amount[$i];
				}
				if ($payment_type[$i] == 'Card') {
					$pay_card = number_format($payment_amount[$i], $decimal);
					$pay_total_card += $payment_amount[$i];
				}
				if ($payment_type[$i] == 'Bank') {
					$pay_bank = number_format($payment_amount[$i], $decimal);
					$pay_total_bank += $payment_amount[$i];
				}
				if ($payment_type[$i] == 'Chque') {
					$pay_cheque = number_format($payment_amount[$i], $decimal);
					$pay_total_cheque += $payment_amount[$i];
				}

				if ($store0 != $payment_store[$i]) {
					print '<tr><td colspan="11" style="padding-left:20px; font-weight:bold; background-color:#0066AA; color:white">' . $payment_store[$i] . '</td></tr>';
				}
				print '<tr>
						<td align="center">
							' . sprintf('%02d', ($i + 1)) . '
						</td>
						<td align="center">
							<a href="index.php?components=billing&action=finish_payment&id=' . $payment_id[$i] . '">' . str_pad($payment_id[$i], 7, "0", STR_PAD_LEFT) . '</a>
						</td>
						<td align="center" width="50px">' . $payment_time[$i] . '</td>
						<td align="right" class="padding"><a ' . $payment_details[$i] . ' >' . $pay_cash . '</a></td>
						<td align="right" class="padding"><a ' . $payment_details[$i] . ' >' . $pay_card . '</a></td>
						<td align="right" class="padding"><a ' . $payment_details[$i] . ' >' . $pay_bank . '</a></td>
						<td align="right" class="padding"><a ' . $payment_details[$i] . ' >' . $pay_cheque . '</a></td>
						<td align="right" class="padding"><a href="" style="text-decoration:none;">' . $payment_cheque_no[$i] . '</a></td>
						<td align="center" class="padding"><a href="" style="text-decoration:none;">' . $payment_cheque_date[$i] . '</a></td>
						<td class="shipmentTB3">' . ucfirst($payment_salesman[$i]) . '</td><td class="shipmentTB3">' . ucfirst($payment_cust[$i]) . '</td></tr>';

				if (sizeof($payment_id) == ($i + 1)) {
					print '<tr style="font-weight:bold; background-color:gray; color:white">
							<td colspan="3" align="right"  class="padding">Total</td>
							<td align="right" class="padding">' . number_format($pay_total_cash, $decimal) . '</td>
							<td align="right" class="padding">' . number_format($pay_total_card, $decimal) . '</td>
							<td align="right" class="padding">' . number_format($pay_total_bank, $decimal) . '</td>
							<td align="right" class="padding">' . number_format($pay_total_cheque, $decimal) . '</td>
							<td colspan="5"></td>
						</tr>';
					print '<tr style="font-weight:bold; background-color:#BBBBBB; color:white">
								<td colspan="10" align="right"  class="padding">Total Payments</td><td align="right" class="padding">' . number_format(($pay_total_cash + $pay_total_card + $pay_total_bank + $pay_total_cheque), $decimal) . '</td>';
					print '</table><br /><table align="center" bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0">';
				}
				$store0 = $payment_store[$i];
			}
			?>
		</table>
	<?php } ?>
	<br />
	<?php if ($type_req == 3 || $type_req == '') { ?>
		<table align="center" bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0" style="font-family:Calibri">
			<tr>
				<td colspan="8" style="border:0; background-color:black; color:white; font-weight:bold">Return Extra Pay
					Collection</td>
			</tr>
			<tr>
				<th width="60px">#</th>
				<th width="100px">Return No</th>
				<th width="100px">Time</th>
				<th width="150px">Extra Pay</th>
				<th width="100px">Salesman</th>
				<th width="100px">Customer</th>
			</tr>
			<?php
			$inv = $total_payment = 0;
			$store0 = '';
			for ($i = 0; $i < sizeof($rtn_no); $i++) {
				if ($store0 != $rtn_store[$i]) {
					print '<tr>
								<td colspan="8" style="padding-left:20px; font-weight:bold; background-color:#0066AA; color:white">' . $rtn_store[$i] . '</td>
							</tr>';
				}
				print '<tr>
						<td align="center">
							' . sprintf('%02d', ($i + 1)) . '
						</td>
						<td align="center">
							<a href="index.php?components=billing&action=finish_return&id=' . $rtn_no[$i] . '">' . str_pad($rtn_no[$i], 7, "0", STR_PAD_LEFT) . '</a>
						</td>
						<td align="center" width="50px">' . $rtn_time[$i] . '</td>
						<td align="right" class="padding">' . number_format($rtn_pay[$i], $decimal) . '</td>
						<td class="shipmentTB3">' . ucfirst($rtn_salesman[$i]) . '</td>
						<td class="shipmentTB3">' . ucfirst($rtn_cust[$i]) . '</td></tr>';
				$total_payment += $rtn_pay[$i];
				if (sizeof($rtn_no) == ($i + 1)) {
					print '<tr style="font-weight:bold; background-color:gray; color:white">
									<td colspan="3" align="right"  class="padding" >Total</td>
									<td align="right" class="padding">' . number_format($total_payment, $decimal) . '</td>
									<td colspan="2"></td>
								</tr>';
					print '</table><br /><table align="center" bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0">';
				}
				$store0 = $rtn_store[$i];
			}
			?>
		</table>
	<?php } ?>
	<br />
	<?php if ($type_req == 5 || $type_req == '') { ?>
		<table align="center" bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0" style="font-family:Calibri">
			<tr>
				<td colspan="8" style="border:0; background-color:black; color:white; font-weight:bold">Warranty Pay
					Collection</td>
			</tr>
			<tr>
				<th width="60px">#</th>
				<th width="100px">Job No</th>
				<th width="200px">Time</th>
				<th width="150px">Extra Pay</th>
				<th width="100px">Salesman</th>
				<th width="200px">Entity</th>
			</tr>
			<?php
			$inv = $total_payment = 0;
			$store0 = '';
			for ($i = 0; $i < sizeof($wa_no); $i++) {
				if ($store0 != $wa_store[$i]) {
					print '<tr>
								<td colspan="8" style="padding-left:20px; font-weight:bold; background-color:#0066AA; color:white">' . $wa_store[$i] . '</td>
							</tr>';
				}
				print '<tr>
							<td align="center">
								' . sprintf('%02d', ($i + 1)) . '
							</td>
							<td align="center">
								<a href="index.php?components=billing&action=warranty_show&id=' . $wa_no[$i] . '">' . str_pad($wa_no[$i], 7, "0", STR_PAD_LEFT) . '</a>
							</td>
							<td align="center" width="50px">' . substr($wa_time[$i], 0, 16) . '</td>
							<td align="right" class="padding">' . number_format($wa_pay[$i], $decimal) . '</td>
							<td class="shipmentTB3">' . ucfirst($wa_salesman[$i]) . '</td><td class="shipmentTB3">' . $wa_entity[$i] . '</td></tr>';
				$total_payment += $wa_pay[$i];
				if (sizeof($wa_no) == ($i + 1)) {
					print '<tr style="font-weight:bold; background-color:gray; color:white">
								<td colspan="3" align="right"  class="padding">Total</td>
								<td align="right" class="padding">' . number_format($total_payment, $decimal) . '</td><td colspan="2"></td>
							</tr>';
					print '</table><br /><table align="center" bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0">';
				}
				$store0 = $wa_store[$i];
			}
			?>
		</table>
	<?php } ?>
</div>
<br />
<table align="center">
	<tr>
		<td align="center">
			<div style="background-color:#6699FF; border:medium; border-color:black; width:80px;">
				<a class="shortcut-button" onclick="printdiv('print','printheader')" href="#"><span
						style="text-decoration:none; font-family:Arial; color:navy;">
						<img src="images/print.png" alt="icon" /><br />
						Print
					</span></a>
			</div>
		</td>
	</tr>
</table>

<?php
include_once 'template/footer.php';
?>