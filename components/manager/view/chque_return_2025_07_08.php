<?php
include_once 'template/header.php';
$menu_components = $_GET['components'];
if (isset($_GET['sm'])) {
	$sm = $_GET['sm'];
} else
	$sm = '-all-';
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
		var availableTags1 = [<?php for ($x = 0; $x < sizeof($py_chqnofull); $x++) {
			print '"' . $py_chqnofull[$x] . '",';
		} ?>];
		$("#tags1").autocomplete({
			source: availableTags1
		});
	});

	function pendingReturnChq(id) {
		var check = confirm("Do you want Mark this Chque as Pending?");
		if (check == true)
			window.location = 'index.php?components=<?php print $menu_components; ?>&action=rtnchque_pending&id=' + id;
	}

	function deleteReturnChq(id) {
		var check = confirm("Do you want to Delete this Return Chque?");
		if (check == true)
			window.location = 'index.php?components=<?php print $menu_components; ?>&action=rtnchque_delete&id=' + id;
	}

	function filterSalesman() {
		var sm = document.getElementById('sm').value.toLowerCase();
		window.location = 'index.php?components=<?php print $menu_components; ?>&action=chque_return&sm=' + sm;
	}

	function confirmAction() {
		<?php if (isSalesmanPaymentDepositActive()) { ?>
			var reason = document.getElementById('status').value;
			if (reason != '') {
				if (confirm('Are you sure you want to mark this as return cheque?')) {
					window.location = 'index.php?components=<?php echo $menu_components; ?>&action=chque_setreturn&reason=' + reason + '&id=<?php echo $chq_id; ?>';
				}
			} else {
				alert('Please select reason!');
			}
		<?php } else { ?>
			if (confirm('Are you sure you want to mark this as return cheque?')) {
				window.location = 'index.php?components=<?php echo $menu_components; ?>&action=chque_setreturn&id=<?php echo $chq_id; ?>';
			}
		<?php } ?>
	}
</script>
<style>
	.cheque-design {
		width: 800px;
		padding: 20px;
		border: 2px solid #ccc;
		border-radius: 10px;
		background-color: #f9f9f9;
		box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
		margin: 20px auto;
		position: relative;
	}

	.cheque-header {
		display: flex;
		justify-content: space-between;
		align-items: center;
		margin-bottom: 20px;
	}

	.bank-name {
		font-size: 18px;
		font-weight: bold;
		color: #333;
	}

	.date {
		font-size: 14px;
		color: #666;
	}

	.bank-details,
	.payment-info,
	.payee-info {
		display: flex;
		justify-content: space-between;
		margin-bottom: 15px;
	}

	.deposited-bank {
		margin-bottom: 15px;
	}

	label {
		font-weight: bold;
		color: #333;
		margin-right: 10px;
	}

	span {
		font-size: 14px;
		color: #555;
	}

	.amount-section span {
		font-size: 16px;
		font-weight: bold;
		color: #000;
	}

	.status-section {
		margin-top: 20px;
	}

	.status-section select {
		width: 100%;
		padding: 8px;
		border: 1px solid #ccc;
		border-radius: 4px;
		background-color: #f9f9f9;
	}

	.return-button {
		margin-top: 20px;
		text-align: center;
	}

	.return-button button {
		padding: 10px 20px;
		background-color: #d9534f;
		color: white;
		border: none;
		border-radius: 4px;
		cursor: pointer;
		font-weight: bold;
	}

	.return-button button:hover {
		background-color: #c9302c;
	}
</style>

<?php
if (isset($_REQUEST['message'])) {
	if ($_REQUEST['re'] == 'success')
		$color = 'green';
	else
		$color = 'red';
	print '			<table align="center" style="font-size:11pt"><tr><td><span style="color:' . $color . '; font-weight:bold;">' . $_REQUEST['message'] . '</span><br /><br /></td></tr></table>';
}
?>

<form action="index.php?components=<?php print $menu_components; ?>&action=chque_return" method="post">
	<table align="center" height="100%" cellspacing="0" style="font-size:10pt">
		<tr>
			<td>Search Cheque</td>
			<td width="20px"></td>
			<td><input type="text" id="tags1" name="chque_no" /><input type="submit" value="Submit" /></td>
		</tr>
		<tr>
			<td colspan="3" align="center" style="font-size:9pt; color:silver; padding-top: 10px;" class="bold">You can query
				cheques from last 30 days</td>
		</tr>
	</table>
</form>
<br />
<?php
if (isset($_REQUEST['chque_no'])) {
	if ($_REQUEST['chque_no'] != '') { ?>
		<div class="cheque-design">
			<!-- Header: Bank Name and Date -->
			<div class="cheque-header">
				<span class="bank-name"><?php echo $chq_bank; ?></span>
				<span class="date">Cheque Date : <?php echo $chq_date; ?></span>
			</div>

			<!-- Row 1: Payee and Amount -->
			<div class="payee-info">
				<div class="field">
					<label>Payee:</label>
					<span><?php echo ucfirst($chq_cuname[0]); ?></span>
				</div>
				<div class="field">
					<label>Amount:</label>
					<span><?php echo number_format($chq_amount[0], $decimal); ?></span>
				</div>
			</div>

			<!-- Row 2: Branch, Bank Code, Cheque No -->
			<div class="bank-details">
				<div class="field">
					<label>Cheque No:</label>
					<span><?php echo $chq_no; ?></span>
				</div>
				<div class="field">
					<label>Bank Code:</label>
					<span><?php echo $chq_bank_code; ?></span>
				</div>
				<div class="field">
					<label>Branch:</label>
					<span><?php echo $chq_branch; ?></span>
				</div>
			</div>

			<!-- Row 3: Payment ID, Salesman, Related Invoice -->
			<div class="payment-info">
				<div class="field">
					<label>Payment ID:</label>
					<span><?php echo $chq_id; ?></span>
				</div>
				<div class="field">
					<label>Salesman:</label>
					<span><?php echo ucfirst($chq_salesman[0]); ?></span>
				</div>
				<div class="field">
					<label>Related Invoice:</label>
					<span><?php echo $chq_invno[0]; ?></span>
				</div>
			</div>

			<!-- Row 4: Deposited Bank -->
			<div class="deposited-bank">
				<label>Deposited Bank:</label>
				<span><?php echo $chq_deposited_bank; ?></span>
			</div>

			<!-- Optional: Status Dropdown -->
			<?php if (($chq_return == 0) && isSalesmanPaymentDepositActive()): ?>
				<div class="status-section">
					<label>Status:</label>
					<select name="status" id="status">
						<option value="">-SELECT REASON-</option>
						<option value="7">Bank Return</option>
						<option value="8">Cash Receive</option>
						<option value="9">Issue New Cheque</option>
					</select>
				</div>
			<?php endif; ?>

			<!-- Optional: Return Cheque Button -->
			<?php if ($chq_return == 0): ?>
				<div class="return-button">
					<button onclick="confirmAction(<?php echo $chq_id; ?>)">
						Mark as Return Cheque
					</button>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}
}
?>
<hr /><br />
<div id="printheader" style="display:none">
	<h1 style="color:navy"><?php print $inf_company; ?></h1>
	<h2 align="center" style="color:#3333FF; text-decoration:underline; font-family:Calibri">List Of Returned Cheques
	</h2>
	<hr />
</div>

<h2 align="center" style="color:#0158C2">List Of Returned Cheques</h2>
<div id="print">
	<table align="center" bgcolor="#EEEEEE" height="100%" border="1" cellspacing="0" style="font-size:10pt">
		<tr style="font-weight:bold; background-color:#467898; color:white; -webkit-print-color-adjust: exact;">
			<th width="60px">#</th>
			<th>Cheque No</th>
			<th>Bank</th>
			<th>Cheque Date</th>
			<th>Returned Date</th>
			<th>Amount</th>
			<th>Customer</th>
			<th>Related Invoice</th>
			<th>Collected By</th>
			<th>Status</th>
			<th></th>
			<th></th>
		</tr>
		<tr style="font-weight:bold; background-color:#467898; color:white; -webkit-print-color-adjust: exact;">
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th>
				<select id="sm" onchange="filterSalesman()">
					<option id="">-ALL-</option>
					<?php for ($i = 0; $i < sizeof($salesman_filter); $i++) {
						if ($salesman_filter[$i] == $sm)
							$select = 'selected="selected"';
						else
							$select = '';
						print '<option id="' . $salesman_filter[$i] . '" ' . $select . ' >' . ucfirst($salesman_filter[$i]) . '</option>';
					}
					?>
				</select>
			</th>
			<th></th>
			<th></th>
		</tr>
		<?php
		$total = 0;
		for ($i = 0; $i < sizeof($chq0_id); $i++) {
			if ($chq0_rtn_clear[$i] == 'Pending') {
				$style = 'style="background-color:#EE2222; color:white;"';
				$clear_btt = '';
			} else {
				$style = 'style="background-color:green; color:white;"';
				$clear_btt = '<input type="button" value="Move to Pending"
						onclick="pendingReturnChq(' . $chq0_id[$i] . ')" />&nbsp&nbsp<a href="#"
						onclick="deleteReturnChq(' . $chq0_id[$i] . ')"
						style="font-family:' . "'Courier New'" . ', Courier, monospace; color:red; font-weight:bold; font-size:14pt; text-decoration:none"
						title="Hide Record"><img src="images/action_delete.gif" style="vertical-align:middle" /></a>&nbsp;&nbsp;';
			}
			if (($sm == '-all-') || ($sm == $chq0_salesman[$i])) {
				print '<tr height="25px">
						<td align="center">
								' . sprintf('%02d', ($i + 1)) . '
						</td>
						<td style="padding-left:10px; padding-right:10px">
							<a style="text-decoration: none;" href="index.php?components=' . $menu_components . '&action=chque_return&chque_no=' . $chq0_code[$i] . '&sm=' . $sm . '">' . $chq0_code[$i] . '</a>
						</td>
						<td style="padding-left:10px; padding-right:10px">' . $chq0_bank[$i] . '</td>
						<td style="padding-left:10px; padding-right:10px">' . $chq0_date[$i] . '</td>
						<td style="padding-left:10px; padding-right:10px">' . $chq0_returndate[$i] . '</td>
						<td style="padding-left:10px; padding-right:10px" align="right">' . number_format($chq0_amount[$i], $decimal) . '</td>
						<td style="padding-left:10px; padding-right:10px">' . ucfirst($chq0_cuname[$i]) . '</td>
						<td align="center">' . $chq0_invno[$i] . '</td>
						<td style="padding-left:10px; padding-right:10px">' . ucfirst($chq0_salesman[$i]) . '</td>
						<td ' . $style . ' style="padding-left:10px; padding-right:10px"><a title="' . $chq0_rtn_cle_date[$i] . '">' . $chq0_rtn_clear[$i] . '</a></td>
						<td>' . $clear_btt . '</td>
					</tr>';
				$total += $chq0_amount[$i];
			}
		}
		print '<tr>
				<td colspan="5" align="right" style="padding-right:10px"><strong>Total</strong></td>
				<td style="padding-left:10px; padding-right:10px">' . number_format($total, $decimal) . '</td>
				<td colspan="5"></td>
			</tr>';
		?>
	</table>
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