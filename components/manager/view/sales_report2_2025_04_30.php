<?php
	include_once 'template/header.php';
	$decimal = getDecimalPlaces(1);
?>
<link rel="stylesheet" href="plugin/jquery/css/jquery.ui.all.css" />
<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
<script src="plugin/jquery/ui/jquery.ui.core.js"></script>
<script src="plugin/jquery/ui/jquery.ui.widget.js"></script>
<script src="plugin/jquery/ui/jquery.ui.position.js"></script>
<script src="plugin/jquery/ui/jquery.ui.autocomplete.js"></script>
<link rel="stylesheet" href="plugin/jquery/css/demos.css" />
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
	<?php if ($selection == 'store') { ?>
		$(function () {
			var availableTags2 = [<?php for ($x = 0; $x < sizeof($st_name); $x++) {
				print '"' . $st_name[$x] . '",';
			} ?>];
			$("#tags2").autocomplete({ source: availableTags2 });
		});
	<?php } else { ?>
		$(function () {
			var availableTags1 = [<?php for ($x = 0; $x < sizeof($cu_name0); $x++) {
				print '"' . $cu_name0[$x] . '",';
			} ?>];
			$("#tags1").autocomplete({ source: availableTags1 });
		});
	<?php } ?>

	function changeSelection() {
		var availableTags1 = [<?php for ($x = 0; $x < sizeof($cu_name0); $x++) {
			print '"' . $cu_name0[$x] . '",';
		} ?>];
		var availableTags2 = [<?php for ($x = 0; $x < sizeof($st_name); $x++) {
			print '"' . $st_name[$x] . '",';
		} ?>];
		var selection = document.getElementById('selection').value;
		if (selection == 'customer') {
			document.getElementById('div_sel').innerHTML = '<input type="text" name="customer" id="tags1" required/>';
			$("#tags1").autocomplete({ source: availableTags1 });
		}
		if (selection == 'store') {
			document.getElementById('div_sel').innerHTML = '<input type="text" name="store1" id="tags2" required/>';
			$("#tags2").autocomplete({ source: availableTags2 });
		}
	}

	function setCustID() {
		var id_arr = [<?php for ($x = 0; $x < sizeof($cu_id0); $x++) {
			print '"' . $cu_id0[$x] . '",';
		} ?>];
		var name_arr = [<?php for ($x = 0; $x < sizeof($cu_name0); $x++) {
			print '"' . $cu_name0[$x] . '",';
		} ?>];
		var name = document.getElementById('tags1').value;
		if (name != '') {
			var a = name_arr.indexOf(name);
			document.getElementById('customer_id').value = id_arr[a];
		}
	}
</script>
<!-- ------------------Item List----------------------- -->
<form action="index.php" method="get" onsubmit="return validateDateRange()">
	<input type="hidden" name="components" value="<?php print $components; ?>" />
	<input type="hidden" name="action" value="sales_report2" />
	<input type="hidden" id="customer_id" name="customer_id" value="" />
	<input type="hidden" id="salesman" name="salesman"
		value="<?php echo isset($_GET['salesman']) ? $_GET['salesman'] : ''; ?>" />
	<input type="hidden" id="group_by" name="group_by"
		value="<?php echo isset($_GET['group_by']) ? $_GET['group_by'] : ''; ?>" />
	<table align="center" height="100%" cellspacing="0" style="font-size:10pt">
		<tr>
			<td colspan="2" align="right">
				<table>
					<tr>
						<td>
							<?php if ($_GET['components'] == 'manager') { ?>
								<select name="selection" id="selection" style="font-weight:bold"
									onchange="changeSelection()">
									<option value="customer" <?php if ($selection == 'customer')
										print 'selected="selected"'; ?>>Customer</option>
									<option value="store" <?php if ($selection == 'store')
										print 'selected="selected"'; ?>>
										Store</option>
								</select>
							<?php } else { ?>
								<input type="hidden" name="selection" value="customer" />
								<strong>Customer</strong>
							<?php } ?>
						</td>
						<td align="right">
							<div id="div_sel">
								<?php if ($selection == 'store')
									print '<input type="text" name="store1" id="tags2" value="' . $store1 . '" onclick="this.value=' . "''" . '" />';
								else
									print '<input type="text" name="customer" id="tags1" value="' . $customer . '" onclick="this.value=' . "''" . '" />';
								?>
							</div>
						</td>
					</tr>
				</table>
			</td>
			<td width="20px"></td>
			<td width="70px" align="right"><strong>Category : </strong></td>
			<td>
				<select name="category" id="category">
					<option value="all">-ALL-</option>
					<?php for ($i = 0; $i < sizeof($cat_id); $i++) {
						if ($category == $cat_id[$i])
							$select = 'selected="selected"';
						else
							$select = '';
						print '<option value="' . $cat_id[$i] . '" ' . $select . '>' . $cat_name[$i] . '</option>';
					} ?>
				</select>
			</td>
			<td width="20px"></td>
			<td width="100px" align="right"><strong>Salesman : </strong></td>
			<td>
				<select id="salesman0" onchange="updateHiddenField()">
					<option value="all">--ALL--</option>
					<?php
						// Ensure $up_id and $up_name are initialized before use
						$up_id = isset($up_id) ? $up_id : [];
						$up_name = isset($up_name) ? $up_name : [];

						$selectedsalesman = 'ALL';
						$salesman_id = isset($_GET['salesman']) ? $_GET['salesman'] : '';
						for ($i = 0; $i < sizeof($up_id); $i++) {
							if ($up_id[$i] == $salesman_id) {
								$select = 'selected="selected"';
								$selectedsalesman = ucfirst($up_name[$i]);
							} else {
								$select = '';
							}
							print '<option value="' . $up_id[$i] . '" ' . $select . '>' . ucfirst($up_name[$i]) . '</option>';
						}
					?>
				</select>
			</td>
			<td width="20px"></td>
			<td width="100px" align="right"><strong>Group By : </strong></td>
			<td>
				<select id="sold_price_select" onchange="updateHiddenField()">
					<option value="all">--ALL--</option>
					<option value="sold_price" <?php if((isset($_GET['group_by'])) && ($_GET['group_by'] == 'sold_price')) print "selected"; else print ''; ?>>Sold Price</option>
				</select>
			</td>
			<td width="20px"></td>
			<td width="100px" align="right"><strong>From Date : </strong></td>
			<td>
				<input type="date" id="datefrom" name="datefrom" style="width:130px"
					value="<?php print $fromdate; ?>" />
			</td>
			<td width="100px" align="right"><strong>To Date : </strong></td>
			<td>
				<input type="date" id="dateto" name="dateto" style="width:130px" value="<?php print $todate; ?>" />
				<input type="submit" value="GET" onclick="setCustID()" style="width:50px; height:40px" />
			</td>
		</tr>
	</table>
</form>

<div id="printheader" style="display:none">
	<h2 align="center" style="color:navy"><?php print $inf_company; ?></h2>
	<h3 align="center" style="color:#333399; text-decoration:underline">Item Sales Report</h3>

	<table style="font-size:12pt" border="1" cellspacing="0">
		<tr>
			<td width="100px" style="background-color:#C0C0C0; padding-left:10px">Customer</td>
			<td width="250px" style="background-color:#EEEEEE; padding-left:10px"><?php print ucfirst($customer); ?>
			</td>
		</tr>
		<tr>
			<td style="background-color:#C0C0C0; padding-left:10px">From</td>
			<td style="background-color:#EEEEEE; padding-left:10px"><?php print $fromdate; ?></td>
		</tr>
		<tr>
			<td style="background-color:#C0C0C0; padding-left:10px">To</td>
			<td style="background-color:#EEEEEE; padding-left:10px"><?php print $todate; ?></td>
		</tr>
	</table><br />
</div>
<?php if (isset($_REQUEST['category'])) print '<div id="chart_div" style="width: 100%; height: 500px;"></div>'; ?>
<br /><br />
<div id="print">
	<table align="center">
		<tr>
			<td valign="top" align="center">
			<?php if ((isset($_REQUEST['group_by'])) && ($_REQUEST['group_by'] == 'sold_price')) { ?>
					<table width="100%" style="margin-bottom: 10px;">
						<tr>
							<td width="20px" bgcolor="wheat"></td>
							<td> - Same item with sold by different prices</td>
						</tr>
					</table>
				<?php } ?>
				<table align="center" bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0" style="font-size:10pt">
					<tr>
						<td colspan="12" style="border:0; background-color:black; color:white; font-weight:bold"></td>
					</tr>
					<tr bgcolor="#CCCCCC">
						<th width="50px">#</th>
						<th width="300px">Item Description</th>
						<th width="100px">Sold Qty</th>
						<?php if ((isset($_REQUEST['group_by'])) && ($_REQUEST['group_by'] == 'sold_price')) { ?>
							<th width="100px">Sold Price</th>
							<th width="120px">Total</th>
						<?php } ?>
					</tr>
					<?php
					if (isset($_REQUEST['category'])) {
						for ($j = 0; $j < sizeof($item_des); $j++) {
							$color='';
							if($item_is_duplicate[$j] == 1){
								$color = 'wheat';
							}
							print '<tr style="background-color: '.$color.'">
									<td align="center">' . sprintf('%02d', ($j + 1)) . '</td>
									<td style="padding-left:10px">' . $item_des[$j] . '</td>
									<td align="right" style="padding-right:10px">' . $item_qty[$j] . '</td>';
									if ((isset($_REQUEST['group_by'])) && ($_REQUEST['group_by'] == 'sold_price')) {
										print '<td align="right" style="padding-right:10px">' . number_format($item_price[$j], $decimal) . '</td>';
										print '<td align="right" style="padding-right:10px">' . number_format($item_price[$j] * $item_qty[$j], $decimal) . '</td>';
									}
							print '</tr>';
						}
					}
					?>
				</table>
			</td>
			<td width="80px"></td>
			<td valign="top" align="center">
				<table align="center" bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0"
					style="font-size:10pt;">
					<tr>
						<td colspan="12" style="border:0; background-color:black; color:white; font-weight:bold"></td>
					</tr>
					<tr bgcolor="#CCCCCC">
						<th width="50px">#</th>
						<th width="300px">Item Category</th>
						<th width="100px">Sold/Total Items<br>In the category</th>
					</tr>
					<?php
					if ($selection == 'store')
						$cus_st = 'store1=' . $store1;
					else
						$cus_st = 'customer=' . $customer . '&customer_id=' . $customer_id;
					if (isset($_REQUEST['category'])) {
						for ($i = 0; $i < sizeof($cat2_id); $i++) {
							if ($cat2_sold_total[$i] != 0) {
								$color = "color:blue;";
							} else {
								$color = "color:black;";
							}
							$url = 'href="index.php?components=' . $_GET['components'] . '&action=sales_report3&selection=' . $selection . '&category=' . $cat2_id[$i] . '&' . $cus_st . '&datefrom=' . $fromdate . '&dateto=' . $todate . '"';
							print '<tr>
									<td align="center">'.sprintf('%02d',($i+1)).'</td>
									<td style="padding-left:10px">
										<a style="text-decoration:none; ' . $color . '" ' . $url . ' target="_blank">' . $cat2_name[$i] . '</a>
									</td>
									<td align="right" style="padding-right:10px">' . $cat2_sold_total[$i] . ' / ' . $cat2_total_count[$i] . '</td>
								</tr>';
						}
					}
					?>
				</table>
			</td>
		</tr>
	</table>
</div>
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
		<?php if ($_GET['components'] == 'manager') { ?>
			<td align="center">
				<div style="background-color:#6699FF; border:medium; border-color:black; width:80px;">
					<?php print '<a class="shortcut-button" href="index.php?components=manager&action=export_unic_list&selection=' . $selection . '&category=' . $category . '&' . $cus_st . '&datefrom=' . $fromdate . '&dateto=' . $todate . '"><span style="text-decoration:none; font-family:Arial; color:navy;">'; ?>
					<img src="images/excel.jpg" style="width:50px" alt="icon" /><br />
					Export Unique List
					</span></a>
				</div>
			</td>
		<?php } ?>
	</tr>
</table>
<script type="text/javascript">
	google.charts.load('current', { 'packages': ['corechart'] });
	google.charts.setOnLoadCallback(drawChart);

	function drawChart() {
		var data = google.visualization.arrayToDataTable([
			['Item', ''],
			<?php for ($i = 0; $i < sizeof($item_des); $i++) {
				print "['$item_des[$i]',  $item_qty[$i]],";
			} ?>
		]);

		var options = {
			title: 'Item Sales',
			hAxis: { title: 'Item', titleTextStyle: { color: '#333' } },
			vAxis: { minValue: 0 }
		};

		var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
		chart.draw(data, options);
	}
	function updateHiddenField() {
		var selectedSalesman = document.getElementById('salesman0').value;
		var selectedGroupBy = document.getElementById('sold_price_select').value;
		document.getElementById('salesman').value = selectedSalesman;
		document.getElementById('group_by').value = selectedGroupBy;
	}
	// Initially set the hidden input value based on the initial selected value of the select element
	updateHiddenField();
</script> <br />
<?php
include_once 'template/footer.php';
?>