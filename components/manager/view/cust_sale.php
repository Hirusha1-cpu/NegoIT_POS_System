<?php
include_once 'template/header.php';
for ($x = 0; $x < sizeof($cu_name0); $x++) {
	$customerData[] = [
		'label' => $cu_name0[$x],
		'value' => $cu_id0[$x]
	];
}
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
	$(function () {
		var availableTags1 = <?php echo json_encode($customerData); ?>; // Encode the PHP array as JSON
		$("#tags1").autocomplete({
			source: availableTags1,
			select: function (event, ui) {
				// When an item is selected, ui.item contains the selected object
				$("#customer_id").val(ui.item.value);
				// You might want to update the display text as well
				$("#tags1").val(ui.item.label);
				return false; // Prevent the default behavior (setting text input value to ui.item.value)
			}
		});
	});

	function setCustID() {
		// This function will now be called on form submission (onclick="setCustID()" on the submit button)
		var customerName = $("#tags1").val();
		var customerId = $("#customer_id").val();
		if (customerName === "") {
			alert("Please enter a customer name.");
			return false; // Prevent form submission
		}
		if (customerId === "") {
			alert("Please select a valid customer from the suggestions or ensure the name is correct.");
			// Optionally, you could try to auto-match here if names are unique enough
			// For duplicate names, this still won't be perfect without a selection.
			return false; // Prevent form submission
		}
		// If customerId is set, allow form submission
		return true;
	}

	function paymentCorrelate() {
		var inv_no = [<?php for ($x = 0; $x < sizeof($invoice_no); $x++) {
			print '"' . $invoice_no[$x] . '",';
		} ?>];
		var last_inv = inv_no[(inv_no.length) - 1];
		if (document.getElementById("correlate").checked) {
			document.getElementById('loading_correlate').innerHTML = document.getElementById('loading').innerHTML;
			for (var i = 0; i < inv_no.length; i++) {
				(paymentCorrelatePHP(inv_no[i], last_inv));
			}
		} else {
			for (var i = 0; i < inv_no.length; i++) {
				document.getElementById('col1_' + inv_no[i]).style.background = '';
				document.getElementById('col2_' + inv_no[i]).style.background = '';
				document.getElementById('col3_' + inv_no[i]).style.background = '';
				document.getElementById('col4_' + inv_no[i]).style.background = '';
				document.getElementById('col5_' + inv_no[i]).style.background = '';
				document.getElementById('col6_' + inv_no[i]).style.background = '';
				document.getElementById('col7_' + inv_no[i]).style.background = '';
				document.getElementById('col8_' + inv_no[i]).style.background = '';
				document.getElementById('col9_' + inv_no[i]).style.background = '';
				document.getElementById('col10_' + inv_no[i]).style.background = '';
				document.getElementById('col11_' + inv_no[i]).style.background = '';
				document.getElementById('col12_' + inv_no[i]).style.background = '';
				document.getElementById('col13_' + inv_no[i]).style.background = '';
			}
		}
	}

	function paymentCorrelatePHP(inv_no, last_inv) {
		var color = '#FF9999'
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var returntext = this.responseText;
				if (returntext == 'yes') {
					document.getElementById('col1_' + inv_no).style.background = color;
					document.getElementById('col2_' + inv_no).style.background = color;
					document.getElementById('col3_' + inv_no).style.background = color;
					document.getElementById('col4_' + inv_no).style.background = color;
					document.getElementById('col5_' + inv_no).style.background = color;
					document.getElementById('col6_' + inv_no).style.background = color;
					document.getElementById('col7_' + inv_no).style.background = color;
					document.getElementById('col8_' + inv_no).style.background = color;
					document.getElementById('col9_' + inv_no).style.background = color;
					document.getElementById('col10_' + inv_no).style.background = color;
					document.getElementById('col11_' + inv_no).style.background = color;
					document.getElementById('col12_' + inv_no).style.background = color;
					document.getElementById('col13_' + inv_no).style.background = color;
				}
				if (inv_no = last_inv) document.getElementById('loading_correlate').innerHTML = '';
			}
		};
		xhttp.open("GET", 'index.php?components=<?php print $_GET['components']; ?>&action=check_payment_correlate&inv_no=' + inv_no, true);
		xhttp.send();
	}
</script>

<style type="text/css">
	@media print {

		.no-print,
		.no-print * {
			display: none !important;
		}
	}
</style>


<div id="loading" style="display:none"><img src="images/loading.gif" style="width:30px" /></div>

<div style="background-color:#EEEEEE; border-radius:10px">
	<form action="index.php" method="get" onsubmit="return validateDateRange2()">
		<input type="hidden" name="components" value="<?php print $_GET['components']; ?>" />
		<input type="hidden" name="action" value="cust_sale" />
		<input type="hidden" id="customer_id" name="customer_id" value="" />
		<table align="center" height="100%" cellspacing="0" style="font-size:10pt">
			<tr>
				<td colspan="2" align="right">
					<table>
						<tr>
							<td>
								<strong>Customer </strong>:
							</td>
							<td align="right">
								<input type="text" id="tags1" value="<?php print $customer; ?>" onclick="this.value=''"
									style="width: 250px;" />
							</td>
						</tr>
					</table>
				</td>
				<td width="50px"></td>
				<td width="100px" align="right"><strong>From Date : </strong></td>
				<td>
					<input type="date" id="datefrom" name="datefrom" style="width:130px" value="<?php print $fromdate; ?>" />
				</td>
				<td width="100px" align="right"><strong>To Date : </strong></td>
				<td>
					<input type="date" id="dateto" name="dateto" style="width:130px" value="<?php print $todate; ?>" />
					<input type="submit" onclick="setCustID()" value="GET" />
				</td>
			</tr>
			<tr>
				<td style="color:maroon"><input type="checkbox" id="correlate" onclick="paymentCorrelate()" /> Fully Paid Bills
				</td>
				<td colspan="2">
					<div id="loading_correlate"></div>
				</td>
				<td colspan="2"></td>
				<td style="font-size:9pt">Customer M Type</td>
				<!-- <td style="color:red"><?php print ucfirst($cust_mtype); ?></td> -->
				 <td style="color:red"><?php print ucfirst($cust_mtype ?? ''); ?></td>
			</tr>
		</table>
	</form>
</div>

<div id="printheader" style="display:none">
	<h1 style="color:navy"><?php print $inf_company; ?></h1>
	<h2 align="center" style="color:#3333FF; text-decoration:underline">Customer Statement</h2>
	<table style="font-size:12pt" border="1" cellspacing="0">
		<tr>
			<td width="100px" style="background-color:#C0C0C0; padding-left:10px">Customer</td>
			<td width="250px" style="background-color:#EEEEEE; padding-left:10px"><?php print ucfirst($customer); ?></td>
		</tr>
		<tr>
			<td style="background-color:#C0C0C0; padding-left:10px">From</td>
			<td style="background-color:#EEEEEE; padding-left:10px"><?php print $fromdate; ?></td>
		</tr>
		<tr>
			<td style="background-color:#C0C0C0; padding-left:10px">To</td>
			<td style="background-color:#EEEEEE; padding-left:10px"><?php print $todate; ?></td>
		</tr>
	</table>
	<br />
</div>

<?php if ($customer != '')
	print '<div id="chart_div" style="width: 100%; height: 500px;"></div>'; ?>

<br /><br />

<div id="print">
	<table align="center" style="font-size:10pt; -webkit-print-color-adjust: exact;">
		<tr>
			<td width="20px" height="20px" bgcolor="red"></td>
			<td>Cheque Return</td>
			<td width="100px"></td>
			<td width="20px" height="20px" bgcolor="#AA4477"></td>
			<td>Cheque Postpone</td>
			<td width="100px"></td>
			<td width="20px" height="20px" bgcolor="green"></td>
			<td>Postpone-Cleared</td>
		</tr>
	</table>

	<table align="center" bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0"
		style="font-size:10pt; -webkit-print-color-adjust: exact;">
		<tr>
			<td colspan="13" style="border:0; background-color:black; color:white; font-weight:bold"></td>
		</tr>
		<tr>
			<th width="100px">Type of Payment</th>
			<th width="100px">Invoice No</th>
			<th width="100px">Related Bill</th>
			<th width="100px">Date</th>
			<th width="90px">Invoice Total</th>
			<th width="90px">Cash</th>
			<th width="90px">Card</th>
			<th width="90px">Bank</th>
			<th width="90px">Chque</th>
			<th width="90px">Credit</th>
			<th width="100px">Outstanding</th>
			<th width="100px">Salesman</th>
			<th width="100px">Shop</th>
		</tr>
		<?php
		if ($customer != '') {
			$inv = $total_credit = 0;
			$store0 = '';
			$invoice_sub_total = array_sum($invoice_Total);
			$total_chque = 0;
			$total_card = 0;
			$total_bank = 0;
			$total_cash = 0;

			$all_dates = array_unique(array_merge($billed_date, $payment_date, $chqrtn_date));
			sort($all_dates);
			$all_dates = array_values($all_dates);
			$gr_date = $gr_credit = array();

			if (sizeof($invoice_no) > sizeof($payment_id))
				$sizeofloop = sizeof($invoice_no);
			if (sizeof($invoice_no) < sizeof($payment_id))
				$sizeofloop = sizeof($payment_id);

			for ($j = 0; $j < sizeof($all_dates); $j++) {
				for ($i = 0; $i < sizeof($invoice_no); $i++) {
					if ($billed_date[$i] == $all_dates[$j]) {
						$style = $style1 = $style2 = '';
						if ($billed_chq_postpone[$invoice_no[$i]] == 1) {
							$style = 'style="color:white; background-color:#AA4477;"';
							$style1 = 'style="color:white;"';
							$style2 = 'color:white;';
						}
						if ($billed_chq_postpone[$invoice_no[$i]] > 1) {
							$style = 'style="color:white; background-color:green;"';
							$style1 = 'style="color:white;"';
							$style2 = 'color:white;';
						}
						if ($billed_chq_return[$invoice_no[$i]] == 1) {
							$style = 'style="color:white; background-color:red;"';
							$style1 = 'style="color:white;"';
							$style2 = 'color:white;';
						}
						$bill_credit = $invoice_Total[$i] - $payment_cash[$invoice_no[$i]] - $payment_card[$invoice_no[$i]] - $payment_bank[$invoice_no[$i]] - $payment_chque[$invoice_no[$i]];
						$total_credit += $bill_credit;
						$credit_balance = $credit_balance - $payment_cash[$invoice_no[$i]] - $payment_card[$invoice_no[$i]] - $payment_bank[$invoice_no[$i]] - $payment_chque[$invoice_no[$i]] + $invoice_Total[$i];
						$gr_date[] = $billed_date[$i];
						$gr_credit[] = $credit_balance;
						print '<tr ' . $style . '>
								<td align="center">
									<div id="col1_' . $invoice_no[$i] . '">Bill</div>
								</td>
								<td align="center">
									<a ' . $style1 . ' href="index.php?components=billing&action=finish_bill&id=' . $invoice_no[$i] . '">
										<div id="col2_' . $invoice_no[$i] . '">' . str_pad($invoice_no[$i], 7, "0", STR_PAD_LEFT) . '</div>
									</a>
								</td>
								<td>
									<div id="col3_' . $invoice_no[$i] . '">&nbsp;</div>
								</td>
								<td align="center" width="50px" title="Time : ' . $billed_time[$i] . '">
									<div id="col4_' . $invoice_no[$i] . '">' . $billed_date[$i] . '</div>
								</td>
								<td align="right" style="padding-right:10px;">
									<div id="col5_' . $invoice_no[$i] . '">' . number_format($invoice_Total[$i], $decimal) . '</div>
								</td>
								<td align="right" style="padding-right:10px;">
									<div id="col6_' . $invoice_no[$i] . '">' . number_format($payment_cash[$invoice_no[$i]], $decimal) . '</div>
								</td>
								<td align="right" style="padding-right:10px;">
									<a href="#" style="text-decoration:none" title="' . $payment_card_tr[$invoice_no[$i]] . '">
										<div id="col13_' . $invoice_no[$i] . '">' . number_format($payment_card[$invoice_no[$i]], $decimal) . '</div>
									</a>
								</td>
								<td align="right" style="padding-right:10px;">
									<a href="#" style="text-decoration:none" title="' . $payment_bank_tr[$invoice_no[$i]] . '">
										<div id="col7_' . $invoice_no[$i] . '">' . number_format($payment_bank[$invoice_no[$i]], $decimal) . '</div>
									</a>
								</td>
								<td align="right" style="padding-right:10px;">
									<a href="#" style="text-decoration:none; ' . $style2 . '" title="' . $chq_details[$invoice_no[$i]] . '">
										<div id="col8_' . $invoice_no[$i] . '">' . number_format($payment_chque[$invoice_no[$i]], $decimal) . '</div>
									</a>
								</td>
								<td  align="right" style="padding-right:10px;">
									<div id="col9_' . $invoice_no[$i] . '">' . number_format($bill_credit, $decimal) . '</div>
								</td>
								<td  align="right" style="padding-right:10px;">
									<div id="col10_' . $invoice_no[$i] . '">' . number_format($credit_balance, $decimal) . '</div>
								</td>
								<td class="shipmentTB3">
									<div id="col11_' . $invoice_no[$i] . '">' . ucfirst($billed_by[$i]) . '</div>
								</td>
								<td class="shipmentTB3">
									<div id="col12_' . $invoice_no[$i] . '">' . ucfirst($billed_store[$i]) . '</div>
								</td>
							</tr>';
						$total_chque += $payment_chque[$invoice_no[$i]];
						$total_card += $payment_card[$invoice_no[$i]];
						$total_bank += $payment_bank[$invoice_no[$i]];
						$total_cash += $payment_cash[$invoice_no[$i]];
					}
				}

				for ($i = 0; $i < sizeof($chqpayment_id); $i++) {
					if ($chqrtn_date[$i] == $all_dates[$j]) {
						$credit_balance = $credit_balance - $chqpayment_amount[$i];
						if ($chqpayment_invno[$i] == 0) {
							$printid = $chqpayment_id[$i];
							$url = 'index.php?components=billing&action=finish_payment&id=' . $chqpayment_id[$i];
						} else {
							$printid = $chqpayment_invno[$i];
							$url = 'index.php?components=billing&action=finish_bill&id=' . $chqpayment_invno[$i];
						}
						$gr_date[] = $chqrtn_date[$i];
						$gr_credit[] = $credit_balance;
						print '<tr style="color:white; background-color:red;">
							<td align="center">Chq Return</td>
							<td align="center">
								<a href="' . $url . '">' . str_pad($printid, 7, "0", STR_PAD_LEFT) . '</a>
							</td>
							<td></td>
							<td align="center" width="50px" title="Time : ' . $chqrtn_time[$i] . '">' . $chqrtn_date[$i] . '</td>
							<td align="right" style="padding-right:10px;"></td>
							<td align="right" style="padding-right:10px;">0</td>
							<td align="right" style="padding-right:10px;">0</td>
							<td align="right" style="padding-right:10px;">0</td>
							<td  align="right" style="padding-right:10px;">
								<a href="#" style="text-decoration:none; color:white" title="' . $chqrtn_chq_details[$i] . '">' . number_format($chqpayment_amount[$i], $decimal) . '</a>
							</td>
							<td align="right" style="padding-right:10px;">0</td>
							<td align="right" style="padding-right:10px;">' . number_format($credit_balance, $decimal) . '</td>
							<td class="shipmentTB3">' . ucfirst($chqpayment_salesman[$i]) . '</td>
							<td class="shipmentTB3">' . ucfirst($chqpayment_store[$i]) . '</td>
						</tr>';
						$total_chque += $chqpayment_amount[$i];
					}
				}

				for ($i = 0; $i < sizeof($payment_id); $i++) {
					if ($payment_date[$i] == $all_dates[$j]) {
						$style = $style1 = $style2 = '';
						$credit_balance = $credit_balance - $payment_amount[$i];
						if ($payment_chq_postpone[$i] == 1) {
							$style = 'style="color:white; background-color:#AA4477;"';
							$style1 = 'style="color:white;"';
							$style2 = 'color:white;';
						}
						if ($payment_chq_postpone[$i] > 1) {
							$style = 'style="color:white; background-color:green;"';
							$style1 = 'style="color:white;"';
							$style2 = 'color:white;';
						}
						if ($payment_chq_return[$i] == 1) {
							$style = 'style="color:white; background-color:red;"';
							$style1 = 'style="color:white;"';
							$style2 = 'color:white;';
						}
						if ($payment_type[$i] == 'Cash') {
							$payment_cash2 = $payment_amount[$i];
							$payment_card2 = 0;
							$payment_bank2 = 0;
							$payment_chque2 = 0;
							$total_cash += $payment_amount[$i];
						}
						if ($payment_type[$i] == 'Card') {
							$payment_cash2 = 0;
							$payment_card2 = $payment_amount[$i];
							$payment_bank2 = 0;
							$payment_chque2 = 0;
							$total_card += $payment_amount[$i];
						}
						if ($payment_type[$i] == 'Bank') {
							$payment_cash2 = 0;
							$payment_card2 = 0;
							$payment_bank2 = $payment_amount[$i];
							$payment_chque2 = 0;
							$total_bank += $payment_amount[$i];
						}
						if ($payment_type[$i] == 'Chque') {
							$payment_cash2 = 0;
							$payment_card2 = 0;
							$payment_bank2 = 0;
							$payment_chque2 = $payment_amount[$i];
							$total_chque += $payment_amount[$i];
						}
						if ($payment_invoice[$i] == 0)
							$payment_inv = '';
						else
							$payment_inv = str_pad($payment_invoice[$i], 7, "0", STR_PAD_LEFT);
						$gr_date[] = $payment_date[$i];
						$gr_credit[] = $credit_balance;
						print '<tr ' . $style . '>
							<td align="center">Payment</td>
							<td align="center">
								<a ' . $style1 . ' href="index.php?components=billing&action=finish_payment&id=' . $payment_id[$i] . '">' . str_pad($payment_id[$i], 7, "0", STR_PAD_LEFT) . '</a>
							</td>
							<td align="center">' . $payment_inv . '</td>
							<td align="center" width="50px" title="Time : ' . $payment_time[$i] . '">' . $payment_date[$i] . '</td>
							<td align="right" style="padding-right:10px;"></td>
							<td align="right" style="padding-right:10px;">' . number_format($payment_cash2, $decimal) . '</td>
							<td  align="right" style="padding-right:10px;">
								<a href="#" style="text-decoration:none;" title="' . $payment_card_details[$i] . '">' . number_format($payment_card2, $decimal) . '</a>
							</td>
							<td  align="right" style="padding-right:10px;">
								<a href="#" style="text-decoration:none;" title="' . $bank_trans[$i] . '">' . number_format($payment_bank2, $decimal) . '</a>
							</td>
							<td  align="right" style="padding-right:10px;">
								<a href="#" style="text-decoration:none; ' . $style2 . '" title="' . $payment_chq_details[$i] . '">' . number_format($payment_chque2, $decimal) . '</a>
							</td>
							<td align="right" style="padding-right:10px;">0</td>
							<td  align="right" style="padding-right:10px;">' . number_format($credit_balance, $decimal) . '</td>
							<td class="shipmentTB3">' . ucfirst($payment_salesman[$i]) . '</td>
							<td class="shipmentTB3">' . ucfirst($payment_store[$i]) . '</td>
						</tr>';
					}
				}

				if (sizeof($all_dates) == ($j + 1)) {
					print '<tr style="font-weight:bold; background-color:gray; color:white">
						<td colspan="4" align="right"  style="padding-right:10px;">
							<div id="div1">Total</div>
						</td>
						<td align="right" style="padding-right:10px;">
							<div id="div2">' . number_format($invoice_sub_total, $decimal) . '</div>
						</td>
						<td align="right" style="padding-right:10px;">
							<div id="div3">' . number_format($total_cash, $decimal) . '</div>
						</td>
						<td align="right" style="padding-right:10px;">
							<div id="div7">' . number_format($total_card, $decimal) . '</div>
						</td>
						<td align="right" style="padding-right:10px;">
							<div id="div4">' . number_format($total_bank, $decimal) . '</div>
						</td>
						<td align="right" style="padding-right:10px;">
							<div id="div5">' . number_format($total_chque, $decimal) . '</div>
						</td>
						<td align="right" style="padding-right:10px;">
							<div id="div6">' . number_format($total_credit, $decimal) . '</div>
						</td>
						<td colspan="3"></td>
					</tr>';
					print '</table>
				<br/>
				<table align="center" bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0">';
				}
			}
		}
		?>
	</table>
</div>

<table align="center">
	<tr>
		<td align="center">
			<div style="background-color:#6699FF; border:medium; border-color:black; width:80px;">
				<a class="shortcut-button" onclick="printdiv('print','printheader')" href="#">
					<span style="text-decoration:none; font-family:Arial; color:navy;">
						<img src="images/print.png" alt="icon" /><br />Print
					</span>
				</a>
			</div>
		</td>
	</tr>
</table>

<?php if ($customer != '') { ?>
	<script type="text/javascript">
		google.charts.load('current', { 'packages': ['corechart'] });
		google.charts.setOnLoadCallback(drawChart);

		function drawChart() {
			var data = google.visualization.arrayToDataTable([
				['Year', ''],
				<?php for ($i = 0; $i < sizeof($gr_date); $i++) {
					print "['$gr_date[$i]',  $gr_credit[$i]],";
				} ?>
			]);

			var options = {
				title: 'Outstanding',
				hAxis: { title: 'Date', titleTextStyle: { color: '#333' } },
				vAxis: { minValue: 0 }
			};

			var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
			chart.draw(data, options);
		}
	</script>
<?php } ?>

<br />
<?php
include_once 'template/footer.php';
?>