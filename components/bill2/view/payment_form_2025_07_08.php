<?php
include_once 'template/header.php';
$bill_salesman = $_COOKIE['user_id'];
if (isset($_GET['s'])) {
	if ($_GET['s'] != '')
		$bill_salesman = $_GET['s'];
}
$decimal = getDecimalPlaces(1);
?>

<link rel="stylesheet" href="plugin/jquery/css/jquery.ui.all.css" />
<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
<script src="plugin/jquery/ui/jquery.ui.core.js"></script>
<script src="plugin/jquery/ui/jquery.ui.widget.js"></script>
<script src="plugin/jquery/ui/jquery.ui.position.js"></script>
<script src="plugin/jquery/ui/jquery.ui.autocomplete2.js"></script>
<link rel="stylesheet" href="plugin/jquery/css/demos.css" />

<script type="text/javascript">
	$(function () {
		<?php
		if (isset($_GET['cust'])) { ?>
			var availableTags4 = [<?php for ($x = 0; $x < sizeof($bank_code); $x++) {
				print '"' . $bank_code[$x] . '",';
			} ?>];
			$("#tags4").autocomplete({
				source: availableTags4
			});
		<?php } ?>
	});
	<?php if (isset($_GET['cust'])) { ?>
		function validateBank() {
			var bank_code = [<?php for ($x = 0; $x < sizeof($bank_code); $x++) {
				print '"' . $bank_code[$x] . '",';
			} ?>];
			var bank_name = [<?php for ($x = 0; $x < sizeof($bank_name); $x++) {
				print '"' . $bank_name[$x] . '",';
			} ?>];
			var id = document.getElementById('tags4').value;
			var bank_code = bank_code.indexOf(id);
			if (bank_code == -1) {
				document.getElementById('bk_name').innerHTML = bank_name[bank_code] = '<span style="color:red">Invalid Bank Code</span>';
			} else {
				document.getElementById('bk_name').innerHTML = '<span style="color:green">' + bank_name[bank_code] + '</span>';
			}
		}
	<?php } ?>

	function validateInvoice() {
		document.getElementById('invoice_div').innerHTML = document.getElementById('loading').innerHTML;
		var $invoice_no = document.getElementById('invoice_no').value;
		var $cust = document.getElementById('cust').value;
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var returntext = this.responseText;
				document.getElementById('invoice_div').innerHTML = returntext;
				if (returntext == 'Invalid') {
					document.getElementById("invoice_div").style.color = "red";
					document.getElementById('payment_validity').value = 0;
				} else {
					document.getElementById("invoice_div").style.color = "blue";
					document.getElementById('payment_validity').value = 1;
				}
			}
		};
		xhttp.open("GET", 'index.php?components=bill2&action=validate_invoice&cust=' + $cust + '&invoice_no=' + $invoice_no, true);
		xhttp.send();
	}

	function switchPayment() {
		var type = document.forms[0];
		var type_val = "";
		var i;
		for (i = 0; i < type.length; i++) {
			if (type[i].checked) {
				type_val = type[i].value;
			}
		}
		if (type_val == 1) {
			document.getElementById("sw_cheque").style.display = "none";
			document.getElementById("sw_bank").style.display = "none";
			document.getElementById("sw_card").style.display = "none";
			document.getElementById("div_bank_sw").innerHTML = '';
		}
		if (type_val == 2) {
			document.getElementById("sw_cheque").style.display = "block";
			document.getElementById("sw_card").style.display = "none";
			document.getElementById("sw_bank").style.display = "none";
			document.getElementById("div_bank_sw").innerHTML = '';
		}
		if (type_val == 3) {
			document.getElementById("sw_cheque").style.display = "none";
			document.getElementById("sw_card").style.display = "none";
			document.getElementById("sw_bank").style.display = "block";
			document.getElementById("div_bank_sw").innerHTML = '<input type="hidden" name="cash_bank_switch" value="1" />';
			if (document.getElementById("invoice_no").value != '') {
				document.getElementById("auth_tr").style.display = "table-row";
			}
		}
		if (type_val == 4) {
			document.getElementById("sw_cheque").style.display = "none";
			document.getElementById("sw_bank").style.display = "none";
			document.getElementById("sw_card").style.display = "block";
			document.getElementById("div_card_sw").innerHTML = '<input type="hidden" name="cash_bank_switch" value="2" />';
			document.getElementById("card_tr").style.display = "table-row";
		}
	}

	function bankPayValidate() {
		$auth_code = document.getElementById('auth_code').value;
		$inv_no = document.getElementById("invoice_no").value;

		if ($inv_no != '') {
			document.getElementById('div_bk_validate').innerHTML = document.getElementById('loading').innerHTML;
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function () {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					var returntext = xmlhttp.responseText;
					if (returntext != '') {
						if (returntext == 'validated') {
							document.getElementById('bank_auth_validity').value = 1;
							document.getElementById('div_bk_validate').innerHTML = '<span style="color:green; font-size:9pt;">Validated</span>';
						} else {
							document.getElementById('bank_auth_validity').value = 0;
							document.getElementById('div_bk_validate').innerHTML = '<span style="color:red; font-size:9pt;">Invalid</span>';
						}
					}
				}
			};
			xmlhttp.open("POST", "index.php?components=bill2&action=auth_code_validate", true);
			xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xmlhttp.send('invoice_no=' + $inv_no + '&auth_code=' + $auth_code);
		}
	}

	function validatePayment() {
		var $count = 0;
		var txt = "";
		var i;
		var $option, $inv_no, $auth_validity;
		var payment_type = document.forms[0];
		$card_no = document.forms["payForm"]["card_no"].value;

		for (i = 0; i < payment_type.length; i++) {
			if (payment_type[i].checked) {
				txt = txt + payment_type[i].value + " ";
			}
		}

		<?php if ($systemid == 15) { ?>
			if (document.querySelector('input[name = payment_type]:checked') != null) {
				$option = document.querySelector('input[name = payment_type]:checked').value;
				$auth_validity = document.getElementById('bank_auth_validity').value;
				$inv_no = document.getElementById('invoice_no').value;
			}
			if ($inv_no != '' && ($option == '3')) {
				if ($auth_validity == '0') {
					$count++;
					$msg = "Auth Code validation failed";
				}
			}
		<?php } ?>

		if (txt == 1) {
			if (document.getElementById('amount_chque').value == '') { $count++; $msg = "Amount shouldn't be empty"; }
		}
		if (txt == 3) {
			if (document.getElementById('amount_chque').value == '') { $count++; $msg = "Amount shouldn't be empty"; }
			if (document.forms["payForm"]["tr_bank"].value == '') { $count++; $msg = "Please select the money transferred bank"; }
		}
		if (txt == 4) {
			if (document.getElementById('amount_chque').value == '') { $count++; $msg = "Amount shouldn't be empty"; }
			if (document.forms["payForm"]["card_bank"].value == '') { $count++; $msg = "Please select the card terminal"; }
			if ($card_no.length == '') { $count++; $msg = "Please enter valid card number"; }
			else if ($card_no.length == 4) { }
			else if ($card_no.length == 16) { }
			else if ($card_no.length == 19) { }
			else {
				$count++; $msg = "Please enter valid card number";
			}
		}
		if (($count == 0) && (txt == 2)) {
			$msg = 'Amount, Chque Bank, Branch, Chque No, Chque Date, Cheque Name must be filled';
			if (!(document.getElementById('amount_chque').value > 0)) $count++;
			if (document.forms["payForm"]["chque_no"].value == '') $count++;
			if (document.forms["payForm"]["chque_bank"].value == '') $count++;
			if (document.forms["payForm"]["chque_branch"].value == '') $count++;
			if (document.forms["payForm"]["chque_date"].value == '') $count++;
			if (document.forms["payForm"]["chque_name"].value == '') $count++;
			if (validateDate(document.forms["payForm"]["chque_date"].value) == false) { $count++; $msg = 'Date must be in yyyy-mm-dd format'; }
			if (isNaN(document.forms["payForm"]["chque_branch"].value)) { $count++; $msg = 'Branch must be a number (branch code)'; }
		}
		if (txt == '') { $count++; $msg = 'Payment type must be selected'; }
		if (document.getElementById('payment_validity').value == 0) {
			if (document.getElementById('invoice_no').value != '') {
				$count++; $msg = 'Please validate invoice number first (check button)';
			}
		}

		if ($count != 0) {
			alert($msg);
			return false;
		} else {
			document.getElementById('addpayment').innerHTML = '';
			return true;
		}
	}

	function invoiceNumberChange() {
		if (document.querySelector('input[name = payment_type]:checked') != null) {
			$option = document.querySelector('input[name = payment_type]:checked').value;
			if ($option == '3') {
				document.getElementById("auth_tr").style.display = "table-row";
			}
			if (document.getElementById('invoice_no').value == '') {
				document.getElementById("auth_tr").style.display = "none";
			}
		}
		document.getElementById('bank_auth_validity').value = 0;
	}
</script>

<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px" /></div>
<input type="hidden" id="bank_auth_validity" value="<?php if ($systemid == 15)
	print '0';
else
	print '1'; ?>" />

<table align="center">
	<tr>
		<td valign="top">
			<div
				style="margin:0 auto; background-color:#EEEEEF; border-radius: 15px; padding-left:10px; padding-right:10px; height:50px; ">
				<table height="100%" style="color:#0158C2; font-family:Calibri; font-size:16pt; vertical-align:middle">
					<tr>
						<td><strong>Customer Payment</strong></td>
					</tr>
				</table>
			</div>
			<form name="payForm" action="index.php?components=bill2&action=add_payment"
				onsubmit="return validatePayment()" method="post">
				<input type="hidden" id="payment_validity" value="0" />
				<input type="hidden" name="salesman" id="salesman" value="<?php print $bill_salesman; ?>" />
				<table align="center" bgcolor="#E5E5E5" style="border-radius: 15px;">
					<tr>
						<td colspan="5"><?php
						if (isset($_REQUEST['message'])) {
							if ($_REQUEST['re'] == 'success')
								$color = 'green';
							else
								$color = '#DD3333';
							print '<script type="text/javascript">document.getElementById("notifications").innerHTML=' . "'" . '<span style="color:' . $color . '; font-weight:bold;font-size:12pt;">' . $_REQUEST['message'] . '</span>' . "'" . ';</script>';
						}
						?>
							<br />
						</td>
					</tr>
					<tr>
						<td width="50px"></td>
						<td style="font-size:12pt">Customer</td>
						<td colspan="2">
							<?php
							if (isset($_GET['cust'])) {
								$cid = array_search($_GET['cust'], $cust_id);
								print '<span style="font-size:12pt">' . $cust_name[$cid] . '</span>';
								print '<input type="hidden" name="cust" id="cust" value="' . $_GET['cust'] . '" />';
							}
							?>
							<?php if (isset($_GET['cust'])) { ?>
						<tr>
							<td></td>
							<td style="font-size:12pt">Invoice No</td>
							<td><input type="text" name="invoice_no" id="invoice_no" onchange="invoiceNumberChange()" />
							</td>
							<td><input type="button" value="check" onclick="validateInvoice()" /></td>
							<td>
								<div id="invoice_div"></div>
							</td>
						</tr>
						<tr>
							<td width="50px"></td>
							<td style="font-size:12pt">Payment Type</td>
							<td colspan="2">
								<input type="radio" name="payment_type" id="payment_type" value="1"
									onchange="switchPayment()"> Cash &nbsp;&nbsp;
								<input type="radio" name="payment_type" id="payment_type" value="4"
									onchange="switchPayment()"> Card &nbsp;&nbsp;
								<input type="radio" name="payment_type" id="payment_type" value="3"
									onchange="switchPayment()"> Bank &nbsp;&nbsp;
								<input type="radio" name="payment_type" id="payment_type" value="2"
									onchange="switchPayment()"> Chque &nbsp;&nbsp;
								<br /><br />
							</td>
							<td width="50px"></td>
						</tr>
						<tr>
							<td></td>
							<td style="font-size:12pt">Amount</td>
							<td>
								<input type="number" name="amount" id="amount_chque" step="0.01" />
							</td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td></td>
							<td colspan="3">
								<div id="sw_card" style="display:none">
									<table width="100%" style="font-size:12pt" cellspacing="0">
										<tr>
											<td style="font-size:12pt" width="100px">Card Terminal</td>
											<td>
												<select name="card_bank" id=card_bank">
													<option value="">-SELECT BANK-</option>
													<?php for ($i = 0; $i < sizeof($ac_bank_id); $i++) {
														print '<option value="' . $ac_bank_id[$i] . '">' . $ac_bank_name[$i] . '</option>';
													} ?>
												</select>
											</td>
											<td></td>
										</tr>
										<tr id="card_tr" style="display:none">
											<td style="font-size:12pt; padding-top:8px;" width="100px">Card Number</td>
											<td style="font-size:12pt; padding-top:8px;">
												<input type="text" id="card_no" name="card_no" style="width:148px"
													placeholder="1400-4400-0000-1000" />
											</td>
										</tr>
									</table>
									<div id="div_card_sw"></div>
								</div>
							</td>
							<td></td>
						</tr>
						<tr>
							<td></td>
							<td colspan="3">
								<div id="sw_bank" style="display:none">
									<table width="100%" style="font-size:12pt" cellspacing="0">
										<tr>
											<td style="font-size:12pt" width="100px">Bank</td>
											<td>
												<select name="tr_bank" id="tr_bank">
													<option value="">-SELECT BANK-</option>
													<?php for ($i = 0; $i < sizeof($ac_bank_id); $i++) {
														print '<option value="' . $ac_bank_id[$i] . '">' . $ac_bank_name[$i] . '</option>';
													} ?>
												</select>
											</td>
											<td></td>
										</tr>
										<?php if ($systemid == 15) { ?>
											<tr id="auth_tr" style="display:none">
												<td style="font-size:12pt; padding-top:8px;" width="100px">Auth Code</td>
												<td style="font-size:12pt; padding-top:8px;">
													<input type="password" id="auth_code" style="width:80px"
														placeholder="Auth Code" />
													<input type="button" onclick="bankPayValidate()" value="validate" />
												<td width="10px"></td>
												<td>
													<div id="div_bk_validate"></div>
												</td>
								</td>
							</tr>
						<?php } ?>
					</table>
					<div id="div_bank_sw"></div>
					</div>
			</td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td colspan="3">
				<div id="sw_cheque" style="display:none">
					<table width="100%" style="font-size:12pt" cellspacing="0">
						<tr>
							<td width="100px"></td>
							<td>Code &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Bank &nbsp;&nbsp;&nbsp; Branch
							</td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td>Chque </td>
							<td>
								<table cellspacing="0">
									<tr>
										<td><input type="number" name="chque_no" id="chque_no"
												style="width:60px" />&nbsp;&nbsp;&nbsp;&nbsp;</td>
										<td><input type="text" name="chque_bank" id="tags4"
												style="width:35px" />&nbsp;&nbsp;&nbsp;&nbsp;</td>
										<td><input type="text" name="chque_branch" id="chque_branch" style="width:35px"
												onfocus="validateBank()" /></td>
									</tr>
								</table>
							</td>
							<td></td>
						</tr>
						<tr>
							<td height="20px"></td>
							<td>
								<div style="font-size:12pt" id="bk_name" align="right"></div>
							</td>
							<td>
								<div style="font-size:12pt" id="av_qty" align="right"></div>
							</td>
						</tr>
						<tr>
							<td>Chque Name</td>
							<td>
								<select name="chque_name" id="chque_name">
									<option value="">-SELECT CHEQUE NAME-</option>
									<?php for ($i = 0; $i < sizeof($cheque_name_id); $i++) {
										print '<option value="' . $cheque_name_id[$i] . '">' . $cheque_name[$i] . '</option>';
									} ?>
								</select>
							</td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td height="20px"></td>
							<td>
								<div style="font-size:12pt" id="bk_name" align="right"></div>
							</td>
							<td>
								<div style="font-size:12pt" id="av_qty" align="right"></div>
							</td>
						</tr>
						<tr>
							<td>Chque Date</td>
							<td><input type="date" name="chque_date" id="chque_date" /></td>
							<td>
								<div style="font-size:12pt" id="av_qty" align="right"></div>
							</td>
						</tr>
						<tr>
							<td height="20px"></td>
							<td>
								<div style="font-size:12pt" id="bk_name" align="right"></div>
							</td>
							<td>
								<div style="font-size:12pt" id="av_qty" align="right"></div>
							</td>
						</tr>
					</table>
				</div>
			</td>
			<td></td>
		</tr>
		<!-- Comment field -->
		<tr>
			<td></td>
			<td style="font-size:12pt">Comment</td>
			<td><textarea name="comment" style="width:100%"></textarea></td>
			<td>
				<div style="font-size:12pt" id="av_qty" align="right"></div>
			</td>
			<td></td>
		</tr>

		<!-- Custom Payment Date - Place it here -->
		<?php if ($systemid == 25) { ?>
			<tr>
				<td></td>
				<td style="font-size:12pt">Custom Date</td>
				<td colspan="2"><input type="date" name="custom_payment_date" id="custom_payment_date" /></td>
				<td></td>
			</tr>
		<?php } ?>

		<!-- Add Payment button -->
		<tr>
			<td></td>
			<td></td>
			<td colspan="3" height="10px">
				<div id="addpayment">
					<input type="submit" value="Add Payment" style="width:100px; height:50px" />
				</div>
			</td>
		</tr>
	<?php } ?>
	<tr>
		<td colspan="5" height="10px"></td>
	</tr>
</table>
</form>
<br />
<?php if (isset($_GET['cust'])) { ?>
	<table align="center" style="font-size:12pt" width="100%">
		<tr style="height:35px">
			<th bgcolor="#C5C5C5">Up to 30+</th>
			<th bgcolor="#C5C5C5">Up to 14+</th>
			<th bgcolor="#C5C5C5">Up to 7+</th>
			<th bgcolor="#C5C5C5">Up to Now</th>
		</tr>
		<tr>
			<td bgcolor="#E5E5E5" align="right"><?php print number_format($balance30, $decimal); ?></td>
			<td bgcolor="#E5E5E5" align="right"><?php print number_format($balance14, $decimal); ?></td>
			<td bgcolor="#E5E5E5" align="right"><?php print number_format($balance7, $decimal); ?></td>
			<td bgcolor="#E5E5E5" align="right"><?php print number_format($balance0, $decimal); ?></td>
		</tr>
		<tr>
			<td colspan="4" height="5px"></td>
		</tr>
		<tr>
			<td colspan="3" bgcolor="#E5E5E5" align="right" style="padding-right:10px">Chque to be Credited</td>
			<td bgcolor="#E5E5E5" align="right"><?php print number_format($pending_chque, $decimal); ?></td>
		</tr>
	</table>
<?php } ?>
</td>
<td width="50px"></td>
<td valign="top">
	<!-- ------------------Item List----------------------- -->
	<div style="background-color:#EEEEEF; border-radius: 15px; padding-left:10px; padding-right:10px">
		<br />
		<table align="center" style="font-family:Calibri; font-size:11pt">
			<tr>
				<td width="18px" bgcolor="#009900"></td>
				<td>Cash</td>
				<td width="20px"></td>
				<td width="18px" bgcolor="#CC3399"></td>
				<td>Card</td>
				<td width="20px"></td>
				<td width="18px" bgcolor="#00AAAA"></td>
				<td>Bank</td>
				<td width="20px"></td>
				<td width="18px" bgcolor="blue"></td>
				<td>Cheque</td>
			</tr>
		</table>
		<?php
		if (isset($_GET['cust'])) {
			print '<table align="center" height="100%" style="font-family:Calibri; font-size:11pt">';
			print '<tr>
						<td colspan="3">
							<h3 class="style2" align="center">Latest Payments</h3>
						</td>
					</tr>';
			print '<tr style="background-color:#467898;color :white;">
						<th>#</th>
						<th>Date</th>
						<th>Payment ID</th>
						<th>Amount</th>
					</tr>';
			for ($i = 0; $i < sizeof($payment_date); $i++) {
				if (($i % 2) == 0)
					$color = '#FAFAFA';
				else
					$color = '#DDDDDD';
				print '<tr style="font-size:10pt; color:' . $pay_color[$i] . '; background-color:' . $color . ';">
						<td>&nbsp;&nbsp;' . sprintf('%02d', ($i + 1)) . '&nbsp;&nbsp;</td>
						<td width="100px" height:20px" title="' . $full_data[$i] . '">&nbsp;&nbsp;' . $payment_date[$i] . '&nbsp;&nbsp;</td>
						<td title="' . $full_data[$i] . '">&nbsp;&nbsp;<a style="color:' . $pay_color[$i] . '"
								href="index.php?components=bill2&action=finish_payment&id=' . $payment_id[$i] . '">' . str_pad(
					$payment_id[$i],
					7,
					"0",
					STR_PAD_LEFT
				) . '</a>&nbsp;&nbsp;</td>
						<td align="right" title="' . $full_data[$i] . '">&nbsp;&nbsp;' . number_format(
					$payment_amount[$i],
					$decimal
				) . '&nbsp;&nbsp;</td>
						</tr>';
			}
			print '</table>';
		} else { ?>
			<table style="font-family:Calibri; font-size:11pt" align="center" width="300px">
				<tr>
					<td colspan="3">
						<form id="search_form1" method="post" action="index.php?components=bill2&action=search_pay"><input
								type="number" style="width:200px" name="search1" id="search1"
								placeholder="Payment Number" /><a
								onclick="document.getElementById('search_form1').submit();" style="cursor:pointer"><img
									src="images/search.png" style="width:30px; vertical-align:middle" /></a></form>
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<form id="search_form2" method="post" action="#"><input type="number" style="width:200px"
								name="invoice_id" placeholder="Cheque Payments by Invoice No" /><a
								onclick="document.getElementById('search_form2').submit();" style="cursor:pointer"><img
									src="images/search.png" style="width:30px; vertical-align:middle" /></a></form>
					</td>
				</tr>
				<tr style="background-color:#467898;color :white;">
					<th>#</th>
					<th>Date</th>
					<th>Payment ID</th>
					<th>Amount</th>
				</tr>
				<?php
				$total = 0;
				for ($i = 0; $i < sizeof($sh_payid); $i++) {
					if (($i % 2) == 0)
						$color = '#FAFAFA';
					else
						$color = '#DDDDDD';
					if ($chq_return[$i] == 0)
						$total += $sh_amount[$i];
					print '<tr style="color:' . $sh_color[$i] . '; background-color:' . $color . '">
						<td>&nbsp;&nbsp;' . sprintf('%02d', ($i + 1)) . '&nbsp;&nbsp;</td>
						<td>&nbsp;&nbsp;' . $sh_date[$i] . '&nbsp;&nbsp;</td>
						<td>&nbsp;&nbsp;<a style="color:' . $sh_color[$i] . '"
								href="index.php?components=bill2&action=finish_payment&id=' . $sh_payid[$i] . '">' . str_pad(
						$sh_payid[$i],
						7,
						"0",
						STR_PAD_LEFT
					) . '</a>&nbsp;&nbsp;</td>
						<td align="right">&nbsp;&nbsp;' . number_format($sh_amount[$i], $decimal) . '&nbsp;&nbsp;</td>
					</tr>';
				}
				print '<tr style="background-color:#DDDDDD">
					<td colspan="3">&nbsp;&nbsp;Invoice Amount</td>
					<td align="right">&nbsp;&nbsp;' . number_format($bill_total, $decimal) . '&nbsp;&nbsp;</td>
				</tr>';
				print '<tr style="background-color:#DDDDDD">
					<td colspan="3">&nbsp;&nbsp;Remaining Amount</td>
					<td align="right">&nbsp;&nbsp;' . number_format($bill_total - $total, $decimal) . '&nbsp;&nbsp;</td>
				</tr>';
				?>
			</table>
		<?php } ?>
		<br /><br />
	</div>
</td>
</tr>
</table>

<?php
include_once 'template/footer.php';
?>