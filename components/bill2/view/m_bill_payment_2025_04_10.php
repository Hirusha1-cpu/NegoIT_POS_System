<?php
include_once 'template/m_header.php';
if ($cust_odr == 'yes')
	$main_tale_color = '#DDDDFF';
else
	$main_tale_color = '#E5E5E5';
$decimal = getDecimalPlaces(1);
?>
<link rel="stylesheet" href="plugin/jquery/css/jquery.ui.all.css" />
<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
<script src="plugin/jquery/ui/jquery.ui.core.js"></script>
<script src="plugin/jquery/ui/jquery.ui.widget.js"></script>
<script src="plugin/jquery/ui/jquery.ui.position.js"></script>
<script src="plugin/jquery/ui/jquery.ui.autocomplete.js"></script>
<link rel="stylesheet" href="plugin/jquery/css/demos.css" />

<script type="text/javascript">
	$(function () {
		var availableTags4 = [<?php for ($x = 0; $x < sizeof($bank_code); $x++) {
			print '"' . $bank_code[$x] . '",';
		} ?>];
		$("#chque_bank").autocomplete({
			source: availableTags4
		});
	});

	function thousandsSeparatorsWithRounding(num) {
		const roundedNumber = parseFloat(num).toFixed(<?php echo $decimal; ?>);
		const parts = roundedNumber.toString().split(".");
		parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",")
		return parts.join(".");
	}

	function validateBank() {
		var bank_code = [<?php for ($x = 0; $x < sizeof($bank_code); $x++) {
			print '"' . $bank_code[$x] . '",';
		} ?>];
		var bank_name = [<?php for ($x = 0; $x < sizeof($bank_name); $x++) {
			print '"' . $bank_name[$x] . '",';
		} ?>];
		var id = document.getElementById('chque_bank').value;
		var bank_code = bank_code.indexOf(id);
		if (bank_code == -1) {
			document.getElementById('bk_name').innerHTML = bank_name[bank_code] = '<span style="color:red">Invalid Bank Code</span>';
		} else {
			document.getElementById('bk_name').innerHTML = '<span style="color:green">' + bank_name[bank_code] + '</span>';
		}
	}

	function setBillPayment() {
		var bm_type = document.getElementById('bm_type').value;
		var invoicetotal = parseFloat(document.getElementById('invoicetotal').value).toFixed(<?php echo $decimal; ?>);
		var crlimitbalance = parseFloat(document.getElementById('crlimitbalance').value).toFixed(<?php echo $decimal; ?>);
		if (document.getElementById('amount_cash').value != '') var amount_cash = document.getElementById('amount_cash').value; else { var amount_cash = 0; document.getElementById('amount_cash').value = 0; }
		<?php if ($systemid == 10 || $systemid == 15) { ?> if (document.getElementById('amount_card').value != '') var amount_card = document.getElementById('amount_card').value; else { var amount_card = 0; document.getElementById('amount_card').value = 0; } <?php } ?>
		if (document.getElementById('amount_bank').value != '') var amount_bank = document.getElementById('amount_bank').value; else { var amount_bank = 0; document.getElementById('amount_bank').value = 0; }
		if (document.getElementById('amount_chque').value != '') var amount_chque = document.getElementById('amount_chque').value; else { var amount_chque = 0; document.getElementById('amount_chque').value = 0; }
		if (document.getElementById('amount_credit').value != '') var amount_credit = document.getElementById('amount_credit').value; else { var amount_credit = 0; document.getElementById('amount_credit').value = 0; }
		if (document.getElementById('fu_down_pay').value != '') var fu_down_pay = document.getElementById('fu_down_pay').value; else { var fu_down_pay = 0; document.getElementById('fu_down_pay').value = 0; }
		<?php if ($systemid == 10 || $systemid == 15) { ?>
			var balance = ((invoicetotal * 10) - (amount_cash * 10) - (amount_card * 10) - (amount_bank * 10) - (amount_credit * 10) - (fu_down_pay * 10) - (amount_chque * 10)) / 10;
		<?php } else { ?>
			var balance = ((invoicetotal * 10) - (amount_cash * 10) - (amount_bank * 10) - (amount_credit * 10) - (fu_down_pay * 10) - (amount_chque * 10)) / 10;
		<?php } ?>
		document.getElementById('payment_balance_div').innerHTML = thousandsSeparatorsWithRounding(balance);
		document.getElementById('payment_cash_div').innerHTML = thousandsSeparatorsWithRounding(amount_cash);
		<?php if ($systemid == 10 || $systemid == 15) { ?> document.getElementById('payment_card_div').innerHTML = thousandsSeparatorsWithRounding(amount_card); <?php } ?>
		document.getElementById('payment_bank_div').innerHTML = thousandsSeparatorsWithRounding(amount_bank);
		document.getElementById('payment_chque_div').innerHTML = thousandsSeparatorsWithRounding(amount_chque);
		document.getElementById('payment_fu_down_pay_div').innerHTML = thousandsSeparatorsWithRounding(fu_down_pay);
		document.getElementById('payment_credit_div').innerHTML = thousandsSeparatorsWithRounding(amount_credit);
		document.getElementById('balance').value = balance;
		<?php if ($systemid == 10 || $systemid == 15) { ?>
			var newcrlimitbalance = parseFloat(crlimitbalance) + parseFloat(amount_cash) + parseFloat(amount_bank) + parseFloat(amount_card);
		<?php } else { ?>
			var newcrlimitbalance = parseFloat(crlimitbalance) + parseFloat(amount_cash) + parseFloat(amount_bank);
		<?php } ?>
		document.getElementById('cr_balance_div').innerHTML = thousandsSeparatorsWithRounding(newcrlimitbalance);
		if (bm_type != 3) document.getElementById('crlimitbalance2').value = newcrlimitbalance;
		if (balance == 0) progressBar(20);

		if (fu_down_pay == 0) {
			document.getElementById('div_tile_fudopy').style.display = "none";
			document.getElementById('payment_fu_down_pay_div').style.display = "none";
		} else {
			document.getElementById('div_tile_fudopy').style.display = "block";
			document.getElementById('payment_fu_down_pay_div').style.display = "block";
		}
	}


	//-----------------------Progress Bar-------------------------------------------//
	function progressBar($new_progress) {
		var i = 0;
		$current_progress = document.getElementById("current_progress").value;
		if (i == 0) {
			i = 1;
			var elem = document.getElementById("myBar");
			var width = $current_progress;
			var id = setInterval(frame, 5);
			function frame() {
				if (width >= $new_progress) {
					clearInterval(id);
					i = 0;
				} else {
					width++;
					elem.style.width = width + "%";
					elem.innerHTML = width + "%";
				}
			}
		}
		document.getElementById("current_progress").value = $new_progress;
	}

	function finalyze() {
		validateBillPayment();
	}

	function bankPayValidate() {
		$auth_code = document.getElementById('auth_code').value;
		$bm_no = document.getElementById("bm_no").value;
		document.getElementById('div_bk_validate').innerHTML = document.getElementById('loading').innerHTML;
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function () {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				var returntext = xmlhttp.responseText;
				if (returntext != '') {
					if (returntext == 'validated') {
						document.getElementById('bank_auth_validity').value = 1;
						document.getElementById('div_bk_validate').innerHTML = '<span style="color:green">Validated</span>';
					} else {
						document.getElementById('bank_auth_validity').value = 0;
						document.getElementById('div_bk_validate').innerHTML = '<span style="color:red">Invalid Code</span>';
					}
				}
			}
		};
		xmlhttp.open("POST", "index.php?components=bill2&action=temp_auth_code_validate", true);
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xmlhttp.send('bm_no=' + $bm_no + '&auth_code=' + $auth_code);
	}

	function validateBillPayment() {
		var $count = 0;
		document.getElementById('div_process').innerHTML = document.getElementById('loading').innerHTML;
		setBillPayment();

		$balance = document.getElementById('balance').value;
		$amount_cash = document.getElementById('amount_cash').value;
		<?php if ($systemid == 10 || $systemid == 15) { ?>
			$amount_card = document.getElementById('amount_card').value;
			$card_bank = document.getElementById('card_bank').value;
			$card_no = document.getElementById('card_no').value;
		<?php } ?>
		$amount_bank = document.getElementById('amount_bank').value;
		$tr_bank = document.getElementById('tr_bank').value;
		$amount_chque = document.getElementById('amount_chque').value;
		$chque_no = document.getElementById('chque_no').value;
		$chque_bank = document.getElementById('chque_bank').value;
		$chque_branch = document.getElementById('chque_branch').value;
		$chque_date = document.getElementById('chque_date').value;
		$chque_name = document.getElementById('chque_name').value;
		$amount_credit = document.getElementById('amount_credit').value;
		$crlimitbalance2 = document.getElementById('crlimitbalance2').value

		if ($balance != 0) { $count++; $msg = 'Balance Must Be 0. Please add Cash, Card, Bank, Chque, Credit'; }
		if ($count == 0) { if ($crlimitbalance2 < 0) { $count++; $msg = 'Credit Limit Exceed'; } }

		if ($count == 0) {
			if (isNaN($amount_cash)) { $count++; $msg = "Invalid input for Cash Payment"; }
			   <?php if ($systemid == 10 || $systemid == 15) { ?> if (isNaN($amount_card)) { $count++; $msg = "Invalid input for Card Payment"; } <?php } ?>
			if (isNaN($amount_bank)) { $count++; $msg = "Invalid input for Bank Payment"; }
			if (isNaN($amount_chque)) { $count++; $msg = "Invalid input for Cheque Payment"; }
			if (isNaN($amount_credit)) { $count++; $msg = "Invalid input for Credit Payment"; }
		}

		if (($count == 0) && ($amount_bank > 0)) {
			if ($tr_bank == '') { $count++; $msg = "Please Select the Money Transferred Bank"; }
			if (document.getElementById('bank_auth_validity').value == 0) { $count++; $msg = "Auth Code Validation Failed"; }
		}
		<?php if ($systemid == 10 || $systemid == 15) { ?>
			if (($count == 0) && ($amount_card > 0)) {
				if ($card_bank == '') { $count++; $msg = "Please Select the Card Terminal's Bank"; }
				if ($card_no.length == '') { $count++; $msg = "Please Enter Valid Card Number"; }
				else if ($card_no.length == 4 || $card_no.length == 16 || $card_no.length == 19) { }
				else {
					$count++; $msg = "Please Enter Valid Card Number";
				}
			}
		<?php } ?>
		if (($count == 0) && ($amount_chque > 0)) {
			if ($chque_no == '') $count++;
			if ($chque_bank == '') $count++;
			if ($chque_branch == '') $count++;
			if ($chque_date == '') $count++;
			if ($chque_name == '') $count++;
			if ($count > 0) {
				$msg = 'Amount, Chque Bank, Branch, Chque No, Chque Date Must be filled';
			} else {
				if (validateDate($chque_date) == false) { $count++; $msg = 'Date Must Be in 2010-11-28 Format'; }
			}
		}


		if ($count == 0) {
			document.getElementById('notifications').innerHTML = '<span style="color:green; font-weight:bold; font-size:12pt;">Payment Validated</span>';
			document.getElementById('div_process').innerHTML = '';
			progressBar(30);
			validateHP();
		} else {
			alert($msg);
			document.getElementById('finalize').innerHTML = '<input type="button" value="Create Invoice & Finalize" style="width:100%; height:60px; background-color:orange" onclick="finalyze()" />';
			document.getElementById('notifications').innerHTML = '<span style="color:red; font-weight:bold; font-size:12pt;">Payment Validation Failed</span>';
			document.getElementById('div_process').innerHTML = '';
		}
	}

	function validateHP() {
		document.getElementById('div_process').innerHTML = document.getElementById('loading').innerHTML;
		$hire_purchase = document.getElementById("hire_purchase").value;
		if ($hire_purchase == 1) {
			HPcal();
			$hp_validation = document.getElementById("hp_validation").value;
			$hp_cal = document.getElementById('hp_cal').value;
			if (($hp_validation == 1) && ($hp_cal == 'pass')) {
				document.getElementById('notifications').innerHTML = '<span style="color:green; font-weight:bold; font-size:12pt;">Hire Purchase Validated</span>';
				document.getElementById('div_process').innerHTML = '';
				progressBar(40);
				checkItemAvailability();
			} else {
				document.getElementById('notifications').innerHTML = '<span style="color:red; font-weight:bold; font-size:12pt;">Hire Purchase Validation Failed</span>';
				document.getElementById('div_process').innerHTML = '';
			}
		} else {
			progressBar(40);
			checkItemAvailability();
		}
	}

	function checkItemAvailability() {
		$bm_no = document.getElementById("bm_no").value;
		document.getElementById('div_process').innerHTML = document.getElementById('loading').innerHTML;
		document.getElementById('finalize').innerHTML = '';
		$count = 0;

		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var returntext = this.responseText;
				if (returntext == 'ok') {
					progressBar(50);
					document.getElementById('notifications').innerHTML = '<span style="color:green; font-weight:bold; font-size:12pt;">Quantity Available</span>';
					document.getElementById('div_process').innerHTML = '';
					createInvoice();
				} else {
					document.getElementById('finalize').innerHTML = '<input type="button" value="Create Invoice & Finalize" style="width:100%; height:60px; background-color:orange" onclick="finalyze()" />';
					document.getElementById('notifications').innerHTML = '<span style="color:red; font-weight:bold; font-size:12pt;">' + returntext + '</span>';
					document.getElementById('div_process').innerHTML = '';
				}
			}
		};
		xhttp.open("GET", 'index.php?components=bill2&action=validate_tmp_bill&bm_no=' + $bm_no + '&case=summery', true);
		xhttp.send();
	}

	function createInvoice() {
		$bm_no = document.getElementById("bm_no").value;
		document.getElementById('div_process').innerHTML = document.getElementById('loading').innerHTML;
		$count = 0;

		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var returntext = this.responseText;
				$out = returntext.split('|');
				$msg = $out[0];
				$invoice_no = $out[1];
				if ($msg == 'Done') {
					document.getElementById('notifications').innerHTML = '<span style="color:green; font-weight:bold; font-size:12pt;">Invoice Created</span>';
					document.getElementById('div_process').innerHTML = '';
					progressBar(70);
					addHP($invoice_no);
				} else {
					document.getElementById('finalize').innerHTML = '<input type="button" value="Create Invoice & Finalize" style="width:100%; height:60px; background-color:orange" onclick="finalyze()" />';
					document.getElementById('notifications').innerHTML = '<span style="color:red; font-weight:bold; font-size:12pt;">' + $msg + '</span>';
					document.getElementById('div_process').innerHTML = '';
				}
			}
		};
		//	window.location = 'index.php?components=bill2&action=create_invoice&bm_no='+$bm_no;
		xhttp.open("GET", 'index.php?components=bill2&action=create_invoice&bm_no=' + $bm_no, true);
		xhttp.send();
	}

	function addHP($invoice_no) {
		document.getElementById('div_process').innerHTML = document.getElementById('loading').innerHTML;
		$hire_purchase = document.getElementById("hire_purchase").value;
		if ($hire_purchase == 1) {
			$hp_type = document.getElementById("hp_type").value;
			$hp_date = document.getElementById("hp_date").value;
			$hp_amount = document.getElementById('hp_amount').value;
			$hp_count = document.getElementById('hp_count').value;
			$hp_cal_start = document.getElementById('cal_start_date').value;

			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function () {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					var returntext = xmlhttp.responseText;
					if (returntext != '') {
						if (returntext == 'Done') {
							progressBar(80);
							document.getElementById('div_process').innerHTML = '';
							document.getElementById('notifications').innerHTML = '<span style="color:green; font-weight:bold; font-size:12pt;">Hire Purchase was Addedd to the Invoice</span>';
							addPayment($invoice_no);
						} else {
							document.getElementById('notifications').innerHTML = '<span style="color:red; font-weight:bold; font-size:12pt;">' + returntext + '</span>';
							document.getElementById('div_process').innerHTML = '';
							document.getElementById('finalize').innerHTML = '<input type="button" value="Create Invoice & Finalize" style="width:100%; height:60px; background-color:orange" onclick="finalyze()" />';
						}
					} else {
						document.getElementById('div_process').innerHTML = '';
						document.getElementById('finalize').innerHTML = '<input type="button" value="Create Invoice & Finalize" style="width:100%; height:60px; background-color:orange" onclick="finalyze()" />';
					}
				}
			};

			xmlhttp.open("POST", "index.php?components=bill2&action=add_hire_purchase", true);
			xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xmlhttp.send('invoice_no=' + $invoice_no + '&hp_cal_start=' + $hp_cal_start + '&hp_type=' + $hp_type + '&hp_date=' + $hp_date + '&hp_amount=' + $hp_amount + '&hp_count=' + $hp_count);
		} else {
			progressBar(80);
			addPayment($invoice_no);
		}
	}

	function addPayment($invoice_no) {
		$amount_cash = document.getElementById('amount_cash').value;
		<?php if ($systemid == 10 || $systemid == 15) { ?>
			$amount_card = document.getElementById('amount_card').value;
			$card_bank = document.getElementById('card_bank').value;
			$card_no = document.getElementById('card_no').value;
		<?php } ?>
		$amount_bank = document.getElementById('amount_bank').value;
		$tr_bank = document.getElementById('tr_bank').value;
		$amount_chque = document.getElementById('amount_chque').value;
		$chque_no = document.getElementById('chque_no').value;
		$chque_bank = document.getElementById('chque_bank').value;
		$chque_branch = document.getElementById('chque_branch').value;
		$chque_date = document.getElementById('chque_date').value;
		$chque_name = document.getElementById('chque_name').value;
		$amount_credit = document.getElementById('amount_credit').value;
		$comment = document.getElementById('comment').value;
		<?php if ($systemid == 25) { ?>
			$custom_bill_date = document.getElementById('custom_bill_date').value;
		<?php } ?>


		document.getElementById('div_process').innerHTML = document.getElementById('loading').innerHTML;

		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function () {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				var returntext = xmlhttp.responseText;
				var returntext = xmlhttp.responseText;
				$out = returntext.split('|');
				$msg = $out[0];
				$qb_msg = $out[1];
				if (returntext != '') {
					if ($msg == 'Done') {
						progressBar(100);
						document.getElementById('notifications').innerHTML = '<span style="color:green; font-weight:bold; font-size:12pt;">Payment was added to the invoice. ' + $qb_msg + '</span>';
						setTimeout(function () {
							setFinal($invoice_no);
						}, 2000);
					} else {
						document.getElementById('notifications').innerHTML = '<span style="color:red; font-weight:bold; font-size:12pt;">' + returntext + '</span>';
						document.getElementById('div_process').innerHTML = '';
						document.getElementById('finalize').innerHTML = '<input type="button" value="Create Invoice & Finalize" style="width:100%; height:60px; background-color:orange" onclick="finalyze()" />';
					}
				} else {
					document.getElementById('div_process').innerHTML = '';
					document.getElementById('finalize').innerHTML = '<input type="button" value="Create Invoice & Finalize" style="width:100%; height:60px; background-color:orange" onclick="finalyze()" />';
				}
			}
		};

		xmlhttp.open("POST", "index.php?components=bill2&action=add_bill_payment", true);
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		<?php if ($systemid == 25) { ?>
			xmlhttp.send('invoice_no=' + $invoice_no + '&amount_cash=' + $amount_cash + '&amount_bank=' + $amount_bank + '&tr_bank=' + $tr_bank + '&amount_chque=' + $amount_chque + '&chque_no=' + $chque_no + '&chque_bank=' + $chque_bank + '&chque_branch=' + $chque_branch + '&chque_date=' + $chque_date + '&chque_name=' + $chque_name + '&comment=' + $comment + '&custom_bill_date=' + $custom_bill_date);
		<?php } elseif ($systemid == 10 || $systemid == 15) { ?>
			xmlhttp.send('invoice_no=' + $invoice_no + '&amount_cash=' + $amount_cash + '&amount_card=' + $amount_card + '&card_bank=' + $card_bank + '&card_no=' + $card_no + '&amount_bank=' + $amount_bank + '&tr_bank=' + $tr_bank + '&amount_chque=' + $amount_chque + '&chque_no=' + $chque_no + '&chque_bank=' + $chque_bank + '&chque_branch=' + $chque_branch + '&chque_date=' + $chque_date + '&chque_name=' + $chque_name + '&comment=' + $comment);
		<?php } else { ?>
			xmlhttp.send('invoice_no=' + $invoice_no + '&amount_cash=' + $amount_cash + '&amount_bank=' + $amount_bank + '&tr_bank=' + $tr_bank + '&amount_chque=' + $amount_chque + '&chque_no=' + $chque_no + '&chque_bank=' + $chque_bank + '&chque_branch=' + $chque_branch + '&chque_date=' + $chque_date + '&chque_name=' + $chque_name + '&comment=' + $comment);
		<?php } ?>
	}

	function setFinal($invoice_no) {
		document.getElementById('div_process').innerHTML = document.getElementById('loading').innerHTML;
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function () {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				var returntext = xmlhttp.responseText;
				document.getElementById('div_process').innerHTML = '';
				window.location = 'index.php?components=bill2&action=finish_bill&id=' + $invoice_no;
			}
		};
		xmlhttp.open("POST", "index.php?components=bill2&action=sms", true);
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xmlhttp.send('id=' + $invoice_no);
	}
	//----------------------------------------------------------//
	function HPcal() {
		$amount_credit = document.getElementById('amount_credit').value;
		$hp_amount = document.getElementById('hp_amount').value;
		$hp_count = document.getElementById('hp_count').value;
		$msg1 = '<span style="color:red">Fail</span>';
		$msg2 = 'fail';
		if (($hp_amount > 0) && ($hp_count > 0)) {
			$hp_total = $hp_amount * $hp_count;
			document.getElementById('hp_total').value = $hp_total;
			if ($amount_credit == $hp_total) {
				$msg1 = '<span style="color:#4CAF50">Pass</span>';
				$msg2 = 'pass';
			}
		}
		document.getElementById('hp_status').innerHTML = $msg1;
		document.getElementById('hp_cal').value = $msg2;
	}

	function setHPType($type) {
		if ($type == '') {
			document.getElementById('hp_validation').value = 0;
			document.getElementById('hp_date_list1').value = '';
			document.getElementById('hp_date_list2').value = '';
		}
		if ($type == 1) {
			document.getElementById('div_hp_date1').style.display = "block";
			document.getElementById('div_hp_date2').style.display = "none";
			document.getElementById('hp_validation').value = 0;
			document.getElementById('hp_date_list1').value = '';
		}
		if ($type == 2) {
			document.getElementById('div_hp_date1').style.display = "none";
			document.getElementById('div_hp_date2').style.display = "block";
			document.getElementById('hp_validation').value = 0;
			document.getElementById('hp_date_list2').value = '';
		}
		if ($type == 3) {
			document.getElementById('div_hp_date1').style.display = "none";
			document.getElementById('div_hp_date2').style.display = "none";
			document.getElementById('hp_validation').value = 1;
			document.getElementById('hp_date').value = 0;
		}
	}

	function setHPDate($date) {
		document.getElementById('hp_date').value = $date;
		if (document.getElementById('hp_type').value != '') {
			if ($date != '') {
				document.getElementById('hp_validation').value = 1;
			} else {
				document.getElementById('hp_validation').value = 0;
			}
		} else {
			document.getElementById('hp_validation').value = 0;
		}
	}
</script>

<style>
	#myProgress {
		width: 100%;
		background-color: #ddd;
		border-radius: 10px;
		margin: auto;
		box-shadow: 0 0 10px #4caf50;
	}

	#myBar {
		width: 1%;
		height: 30px;
		background-color: #4CAF50;
		border-radius: 10px;
		text-align: center;
		/* To center it horizontally (if you want) */
		line-height: 30px;
		/* To center it vertically */
		color: white;
		font-family: Calibri;
		font-size: 12pt;
	}

	@media only screen and (min-width: 600px) {
		#landscape {
			margin-right: 15px;
		}
	}

	table {
		font-size: 12pt;
		font-family: Calibri;
	}

	.table-bill,
	.table-balance {
		box-shadow: 0 0 10px rgb(0 0 0 / 10%);
	}

	#portrait {
		margin-top: 15px;
	}
</style>

<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px" /></div>
<div id="tmp_backbtn" style="display:none">
	<input type="button" value="Back" style="width:100px; font-size:medium"
		onclick="window.location = 'index.php?components=bill2&action=bill_item&cust_odr=<?php print $_GET['cust_odr']; ?>&bill_no=<?php print $_GET['bill_no']; ?>'" />
</div>

<input type="hidden" id="bm_no" value="<?php print $_GET['bill_no']; ?>" />
<input type="hidden" id="hire_purchase" value="<?php if ($bm_hire_purchase)
	print '1';
else
	print '0'; ?>" />
<input type="hidden" id="hp_validation" value="0" />
<input type="hidden" id="hp_date" value="0" />
<input type="hidden" id="hp_cal" value="fail" />
<input type="hidden" id="current_progress" value="0" />
<input type="hidden" id="crlimitbalance2" value="" />
<input type="hidden" id="bm_type" value="<?php print $bm_type; ?>" />
<input type="hidden" id="payment_type" name="payment_type" />
<input type="hidden" id="payment_validity" value="1" />
<input type="hidden" id="bank_auth_validity" value="<?php if ($systemid == 15)
	print '0';
else
	print '1'; ?>" />

<div class="w3-container" style="margin-top:75px">
	<table align="center">
		<tr>
			<td>
				<div id="notifications">
					<?php
					if (isset($_REQUEST['message'])) {
						if ($_REQUEST['re'] == 'success')
							$color = 'green';
						else
							$color = 'red';
						print '<span style="color:' . $color . '; font-weight:bold;font-size:12pt;">' . $_REQUEST['message'] . '</span><br />';
					}
					?>
				</div>
			</td>
		</tr>
	</table>
	<hr>
	<!---------------------------------- Bill Amount & Balance ---------------------------------->
	<div class="w3-row">
		<div class="w3-col s3"></div>
		<div class="w3-col">
			<table align="center" width="100%">
				<tr>
					<td style="vertical-align:top;">
						<div id="landscape" style="vertical-align:top"></div>
					</td>
					<td style="vertical-align:top;">
						<table align="center" width="100%" class="table-balance">
							<tr>
								<td class="sidetable1" width="40%">Customer</td>
								<td width="60%" align="right" class="sidetable2">
									<?php
									print '<span>' . $cu_name . '</span>';
									print '<input type="hidden" name="cust" id="cust" value="' . $cu_id . '" />';
									?>
								</td>
							</tr>
							<tr>
								<td class="sidetable1">Temp Bill No</td>
								<td align="right" class="sidetable2">
									T<?php print str_pad($_GET['bill_no'], 7, "0", STR_PAD_LEFT); ?>
									<input type="hidden" name="invoice_no" value="<?php print $_GET['bill_no']; ?>" />
								</td>
							</tr>
							<tr>
								<td colspan="2" height="30px"></td>
							</tr>
							<tr>
								<td class="sidetable1">Bill Amount</td>
								<td align="right" class="sidetable2">
									<?php print number_format($bill_total, $decimal); ?>
									<input type="hidden" id="invoicetotal" value="<?php print $bill_total; ?>" />
								</td>
							</tr>
							<tr>
								<td class="sidetable1">Cash</td>
								<td align="right" class="sidetable2">
									<div id="payment_cash_div"></div>
								</td>
							</tr>
							<?php if ($systemid == 10 || $systemid == 15) { ?>
								<tr>
									<td class="sidetable1">Card</td>
									<td align="right" class="sidetable2">
										<div id="payment_card_div"></div>
									</td>
								</tr>
							<?php } ?>
							<tr>
								<td class="sidetable1">Bank</td>
								<td align="right" class="sidetable2">
									<div id="payment_bank_div"></div>
								</td>
							</tr>
							<tr>
								<td class="sidetable1">Chque</td>
								<td align="right" class="sidetable2">
									<div id="payment_chque_div"></div>
								</td>
							</tr>
							<tr>
								<td class="sidetable1" style="background-color:#DDDDEE">
									<div id="div_tile_fudopy" style="display:none">Future Down Pay</div>
								</td>
								<td align="right" class="sidetable2" style="background-color:#DDDDEE">
									<div id="payment_fu_down_pay_div" style="display:none"></div>
								</td>
							</tr>
							<tr>
								<td class="sidetable1" style="background-color:#E2DDCC">Credit Payment</td>
								<td align="right" class="sidetable2" style="background-color:#F2EDDC">
									<div id="payment_credit_div"></div>
								</td>
							</tr>
							<tr>
								<td class="sidetable1">Balance</td>
								<td align="right" class="sidetable2">
									<div id="payment_balance_div">
										<?php print number_format($bill_total, $decimal); ?>
									</div>
									<input type="hidden" id="balance" value="<?php print $bill_total; ?>" />
								</td>
							</tr>
							<tr>
								<td colspan="2" height="30px"></td>
							</tr>
							<tr>
								<td class="sidetable1"
									title="Calculation of Remaining Credit Limit&#13;Customer Credit Limit - Invoice Total + Cash Payments + Bank Payments + Deposited Cheque Payments">
									Remaining of<br />Credit Limit
								</td>
								<td align="right" class="sidetable2">
									<div id="cr_balance_div">
										<?php print number_format($remaining_cr_limit, $decimal); ?>
									</div>
									<input type="hidden" id="crlimitbalance"
										value="<?php print $remaining_cr_limit; ?>" />
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<input type="hidden" id="crlimitbalance2" value="" />
		</div>
	</div>
	<!---------------------------------- Add Payment ---------------------------------->
	<div class="w3-row">
		<div class="w3-col s3"></div>
		<div class="w3-col " align="center">
			<div id="portrait">
				<table align="center" bgcolor="<?php print $main_tale_color; ?>" width="100%" class="table-bill">
					<tr>
						<td colspan="3" style="color:navy; font-weight:bold;" align="center">Add Payment</td>
					</tr>
					<tr>
						<td height="30px" colspan="3"></td>
					</tr>
					<!-- Cash -->
					<tr>
						<td>&nbsp;&nbsp;Cash</td>
						<td width="20px"></td>
						<td>
							<input type="text" name="amount_cash" id="amount_cash" value="0"
								style="width:100px; text-align:right; padding-right:10px" onclick="this.value=''" />
							<input type="button" value="Add" style="width:47px; height:33px; color:navy"
								onclick="setBillPayment()" />
						</td>
					</tr>
					<tr>
						<td height="5px" colspan="3"></td>
					</tr>
					<tr>
						<td height="2px" colspan="3" bgcolor="#FAFAFA"></td>
					</tr>
					<tr>
						<td height="5px" colspan="3"></td>
					</tr>
					<!-- Card -->
					<?php if ($systemid == 10 || $systemid == 15) { ?>
						<tr>
							<td>&nbsp;&nbsp;Card</td>
							<td width="20px"></td>
							<td>
								<select name="card_bank" id="card_bank" style="display:block:float:left;">
									<option value="">-SELECT BANK-</option>
									<?php for ($i = 0; $i < sizeof($ac_bank_id); $i++) {
										print '<option value="' . $ac_bank_id[$i] . '">' . $ac_bank_name[$i] . '</option>';
									} ?>
								</select>
								<br />
								<input type="text" placeholder="4000-0566-5566-5556" name="card_no" id="card_no"
									style="margin-bottom: 2px;" /><br />
								<input type="text" name="amount_card" id="amount_card" value="0"
									style="width:100px; text-align:right; display:block; float:left; padding-right:10px; margin: 3px 3px 0 0;"
									onclick="this.value=''" />
								<input type="button" value="Add" style="width:47px; height:33px; color:navy"
									onclick="setBillPayment()" />
							</td>
						</tr>
						<tr>
							<td height="5px" colspan="3"></td>
						</tr>
						<tr>
							<td height="2px" colspan="3" bgcolor="#FAFAFA"></td>
						</tr>
						<tr>
							<td height="5px" colspan="3"></td>
						</tr>
					<?php } ?>
					<!-- Bank -->
					<tr>
						<td>&nbsp;&nbsp;Bank</td>
						<td width="20px"></td>
						<td>
							<select name="tr_bank" id="tr_bank">
								<option value="">-SELECT BANK-</option>
								<?php for ($i = 0; $i < sizeof($ac_bank_id); $i++) {
									print '<option value="' . $ac_bank_id[$i] . '">' . $ac_bank_name[$i] . '</option>';
								} ?>
							</select>
							<br />
							<input type="text" name="comment" id="comment" style="width:100px;"
								placeholder="References" /><br />
							<?php if ($systemid == 15) { ?>
								<table>
									<tr>
										<td><input type="password" id="auth_code" style="width:80px"
												placeholder="Auth Code" /><input type="button" onclick="bankPayValidate()"
												value="validate" /></td>
										<td>
											<div id="div_bk_validate"></div>
										</td>
									</tr>
								</table>
							<?php } ?>
							<input type="text" name="amount_bank" id="amount_bank" value="0"
								style="width:100px; text-align:right; padding-right:10px" onclick="this.value=''" />
							<input type="button" value="Add" style="width:47px; height:33px; color:navy"
								onclick="setBillPayment()" />
						</td>
					</tr>
					<tr>
						<td height="5px" colspan="3"></td>
					</tr>
					<tr>
						<td height="2px" colspan="3" bgcolor="#FAFAFA"></td>
					</tr>
					<tr>
						<td height="5px" colspan="3"></td>
					</tr>
					<!-- Cheque -->
					<tr>
						<td>&nbsp;&nbsp;Cheque</td>
						<td width="20px"></td>
						<td>
							<input type="text" name="amount_chque" id="amount_chque" value="0"
								style="width:100px; text-align:right; padding-right:10px" onclick="this.value=''" /> Rs
							<br>
							<input type="number" name="chque_no" id="chque_no" value="" style=" width:80px"
								placeholder="Code" />
							<input type="number" name="chque_bank" id="chque_bank" value="" style=" width:55px"
								placeholder="Bank" />
							<input type="number" name="chque_branch" id="chque_branch" value="" onfocus="validateBank()"
								style=" width:45px" placeholder="Brn" />
							<div id="bk_name" align="center"></div>
							<input type="date" name="chque_date" id="chque_date" value="" width:120px"
								placeholder="Cheque Date" />
							<select name="chque_name" id="chque_name">
								<option value="">-SELECT CHEQUE NAME-</option>
								<?php for ($i = 0; $i < sizeof($cheque_name_id); $i++) {
									print '<option value="' . $cheque_name_id[$i] . '">' . $cheque_name[$i] . '</option>';
								} ?>
							</select>
							<input type="button" value="Add" style="width:47px; height:33px; color:navy"
								onclick="setBillPayment()" />
						</td>
					</tr>
					<tr>
						<td height="5px" colspan="3"></td>
					</tr>
					<tr>
						<td height="2px" colspan="3" bgcolor="#FAFAFA"></td>
					</tr>
					<tr>
						<td height="5px" colspan="3"></td>
					</tr>
					<!-- Credit -->
					<tr bgcolor="#E2DDCC">
						<td>&nbsp;&nbsp;Credit</td>
						<td></td>
						<td>
							<input type="text" name="amount_credit" id="amount_credit" value="0"
								style="width:100px; text-align:right; padding-right:10px" onclick="this.value=''" />
							<input type="button" value="Add" style="width:47px; height:33px; color:navy"
								onclick="setBillPayment()" />
						</td>
					</tr>
					<!-- Down Payment -->
					<tr>
						<td height="8px" colspan="3">
							<!-- ------------------Future Down Payment----------------------- -->
							<?php if ($bm_hire_purchase) { ?>
								<table align="center" bgcolor="#DDDDEE" width="100%">
									<tr>
										<td width="30px"></td>
										<td colspan="2" style="color:navy; font-weight:bold; height:40px; width:230px">
											Future Down Payment</td>
										<td width="30px"></td>
									</tr>
									<tr>
										<td></td>
										<td colspan="2">
											<input type="text" name="fu_down_pay" id="fu_down_pay" value="0"
												style="width:80px; text-align:right" onclick="this.value=''" />
											<input type="button" value="Add Payment" style="width:100px; height:40px"
												onclick="setBillPayment()" />
										</td>
										<td></td>
									</tr>
									<tr>
										<td height="10px" colspan="4"></td>
									</tr>
								</table>
								<br />
							<?php } else {
								print '<input type="hidden" name="fu_down_pay" id="fu_down_pay" value="0" />';
							} ?>
						</td>
					</tr>
					<?php if ($systemid == 25) { ?>
						<tr>
							<td align="center" colspan="3">
								<div style="margin: 10px 0px; text-align: center;">
									<label for="custom_bill_date" style="font-size: 12pt;">Custom Bill Date</label>
									<input type="date" name="custom_bill_date" id="custom_bill_date"
										style="font-size: 12pt;">
								</div>
							</td>
						</tr>
					<?php } ?>

					<!-- Finalize -->
					<tr>
						<td align="center" colspan="3">
							<div id="finalize">
								<input type="button" value="Create Invoice & Finalize"
									style="width:95%; height:60px; background-color:maroon; color:white"
									onclick="finalyze()" />
							</div>
						</td>
					</tr>
					<tr>
						<td height="30px" colspan="3"></td>
					</tr>
				</table>
			</div>
		</div>
	</div>

	<hr>

	<!---------------------------------- ProgressBar ---------------------------------->
	<div id="myProgress" align="left">
		<div id="myBar">0%</div>
	</div>

	<hr>
</div>
</form>

<?php
include_once 'template/m_footer.php';
?>