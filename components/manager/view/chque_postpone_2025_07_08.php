<?php
include_once 'template/header.php';
$menu_components = $_GET['components'];
$decimal = getDecimalPlaces(1);
if (isset($_GET['sm'])) {
	$sm = $_GET['sm'];
} else
	$sm = '-all-';
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

	function moveToPostpone(id) {
		var check = confirm("Do you want move this cheque to postpone?");
		if (check == true)
			window.location = 'index.php?components=<?php print $menu_components; ?>&action=moveto_postpone&id=' + id;
	}

	function fullClearPostpone(id) {
		var check = confirm("Do you want to clear this cheque record from the list?");
		if (check == true)
			window.location = 'index.php?components=<?php print $menu_components; ?>&action=fullclear_postpone&id=' + id;
	}

	function filterSalesman() {
		var sm = document.getElementById('sm').value.toLowerCase();
		window.location = 'index.php?components=<?php print $menu_components; ?>&action=chque_postpone&sm=' + sm;
	}

	function setAction($case) {
		var check = confirm("Are you sure you want to perform this action?");
		if (check == true) {
			document.getElementById('case').value = $case;
			document.getElementById("chque_postpone").submit();
		}
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
		font-family: Arial, sans-serif;
	}

	.cheque-header {
		display: flex;
		justify-content: space-between;
		align-items: center;
		margin-bottom: 20px;
	}

	.cheque-header .bank-name {
		font-size: 18px;
		font-weight: bold;
		color: #333;
	}

	.cheque-header .date {
		font-size: 14px;
		color: #666;
	}

	.cheque-row {
		display: flex;
		justify-content: space-between;
		margin-bottom: 15px;
	}

	.cheque-row .field {
		display: block;
		flex-direction: column;
	}

	.cheque-row .field label {
		font-weight: bold;
		color: #333;
		margin-bottom: 5px;
		margin-right: 10px;
	}

	.cheque-row .field span {
		font-size: 14px;
		color: #555;
	}

	.cheque-footer {
		margin-top: 40px;
		/* Increased margin-top for more space */
		text-align: center;
	}

	.cheque-footer button {
		padding: 10px 20px;
		background-color: #d9534f;
		color: white;
		border: none;
		border-radius: 4px;
		cursor: pointer;
		font-weight: bold;
	}

	.cheque-footer button:hover {
		background-color: #c9302c;
	}

	/* New Footer Row Styling */
	.footer-row {
		display: flex;
		justify-content: space-between;
		align-items: center;
		margin-top: 20px;
		gap: 20px;
	}

	.footer-row .field {
		display: flex;
		flex-direction: column;
		align-items: flex-start;
	}

	.footer-row .field label {
		font-weight: bold;
		color: #333;
		margin-bottom: 5px;
	}

	.footer-row .field input {
		width: 200px;
		padding: 8px;
		border: 1px solid #ccc;
		border-radius: 4px;
		background-color: #f9f9f9;
		font-size: 14px;
		color: #555;
	}

	/* .footer-row button {
		padding: 10px 20px;
		background-color: #5cb85c;
		color: white;
		border: none;
		border-radius: 4px;
		cursor: pointer;
		font-weight: bold;
	}

	.footer-row button:hover {
		background-color: #4cae4c;
	} */
</style>

<table align="center" style="font-size:11pt">
	<tr>
		<td>
			<?php
			if (isset($_REQUEST['message'])) {
				if ($_REQUEST['re'] == 'success')
					$color = 'green';
				else
					$color = 'red';
				print '<span style="color:' . $color . '; font-weight:bold;">' . $_REQUEST['message'] . '</span><br /><br />';
			}
			?>
		</td>
	</tr>
</table>

<form action="index.php?components=<?php print $menu_components; ?>&action=chque_postpone" method="post">
	<table align="center" height="100%" cellspacing="0" style="font-size:10pt">
		<tr>
			<td>Search Cheque</td>
			<td width="20px"></td>
			<td><input type="text" id="tags1" name="chque_no" style="width:200px;" /><input type="submit" value="Submit" />
			</td>
		</tr>
		<tr>
			<td colspan="3" align="center" style="font-size:9pt; color:silver; padding-top: 10px;">You can query cheques from
				last 30 days</td>
		</tr>
	</table>
</form>
<br /><br />
<?php if (isset($_REQUEST['chque_no'])) {
	if ($_REQUEST['chque_no'] != '') {
		?>
		<!-- <table align="center">
			<tr>
				<td>
					<table align="center" bgcolor="#E5E5E5" style="font-size:10pt" cellspacing="0">
						<tr>
							<td colspan="7" height="10px"></td>
						</tr>
						<tr>
							<td width="10px" rowspan="8"></td>
							<td>Code</td>
							<td>Bank</td>
							<td>Branch</td>
							<td width="150px"></td>
							<td>Date</td>
							<td width="10px" rowspan="8"></td>
						</tr>
						<tr>
							<td><input type="text" disabled="disabled" value="<?php print $chq_no; ?>" style="width:150px" /></td>
							<td><input type="text" disabled="disabled" value="<?php print $chq_bank; ?>" style="width:100px" /></td>
							<td><input type="text" disabled="disabled" value="<?php print $chq_branch; ?>" style="width:60px" /></td>
							<td></td>
							<td><input type="text" disabled="disabled" value="<?php print $chq_date; ?>"
									style="width:90px; text-align:center" /></td>
						</tr>
						<tr>
							<td colspan="5" height="30px"></td>
						</tr>
						<?php for ($i = 0; $i < sizeof($chq_salesman); $i++) {
							print '<tr><td align="right">Related Invoice No </td><td><input type="text" disabled="disabled" value="' . $chq_invno[$i] . '" style="width:60px; text-align:right; padding-right:10px" /></td><td></td><td colspan="2" align="right">Amount <input type="text" disabled="disabled" value="' . number_format($chq_amount[$i], $decimal) . '" style="width:80px; text-align:right; padding-right:10px" /></td></tr>';
						} ?>
						<tr>
							<td colspan="5" height="30px"></td>
						</tr>
						<?php for ($i = 0; $i < sizeof($chq_salesman); $i++) {
							print '<tr><td><input type="text" disabled="disabled" value="' . ucfirst($chq_salesman[$i]) . '" style="width:150px" /></td><td colspan="4" align="right"><input type="text" disabled="disabled" value="' . ucfirst($chq_cuname[$i]) . '" style="width:250px; text-align:right; padding-right:10px" /></td></tr>';
						} ?>
						<tr>
							<td align="center">Salesman</td>
							<td></td>
							<td></td>
							<td></td>
							<td align="center">Customer</td>
						</tr>
						<tr>
							<td colspan="5" height="20px"></td>
						</tr>
						<tr>
							<td colspan="7" height="10px" style="background-color:white"></td>
						</tr>
					</table>
				</td>
				<td width="10px"></td>
				<td style="vertical-align:top">
					<?php if ($chq_postpone == 1)
						print '<div style="width:100%; font-size:14pt; background-color:purple; color:white; text-align:center">Cheque Postponed</div>'; ?>
					<?php if ($chq_postpone > 1)
						print '<div style="width:100%; font-size:14pt; background-color:green; color:white; text-align:center">Cheque Postpone-Pass</div>'; ?>
					<form id="chque_postpone" method="post"
						action="index.php?components=<?php print $menu_components; ?>&action=chque_set_postpone">
						<?php
						if ($chq_postpone == 0)
							print '<input type="hidden" id="case" name="case" value="add" />';
						else
							print '<input type="hidden" id="case" name="case" value="" />';
						?>
						<input type="hidden" name="chque_no" value="<?php print $_REQUEST['chque_no']; ?>" />
						<p style="font-size:10pt">Cheque Postpone Date</p>
						<table>
							<tr>
								<td>Date</td>
								<td><input type="date" name="postpone_date" style="width:130px" value="<?php print $chq_date2; ?>" /></td>
								<td rowspan="2">
									<?php
									if ($chq_postpone == 0)
										print '<input type="button" onclick="setAction(\'add\')" value="SET" style="height:48px" />';
									if ($chq_postpone == 1)
										print '<input type="button" onclick="setAction(\'edit\')" value="Update" /><br />
										<input type="button" onclick="setAction(\'remove\')" value="Clear" style="background-color:green; width:100%; color:white;" />';
									?>
									<input type="submit" style="display:none" />
								</td>
							</tr>
							<tr>
								<td>Master PW 3</td>
								<td><input type="password" name="master_pw3" style="width:130px" /></td>
							</tr>
						</table>
					</form>
				</td>
			</tr>
		</table> -->
		<div class="cheque-design">
			<!-- Header: Bank Name and Date -->
			<div class="cheque-header">
				<span class="bank-name"><?php print $chq_bank; ?></span>
				<span class="date">Cheque Date: <?php print $chq_date; ?></span>
			</div>

			<!-- Row 2: Related Invoice and Amount -->
			<?php for ($i = 0; $i < sizeof($chq_salesman); $i++) { ?>
				<div class="cheque-row">
					<div class="field">
						<label>Payee:</label>
						<span><?php print ucfirst($chq_cuname[$i]); ?></span>
					</div>
					<div class="field">
						<label>Amount:</label>
						<span><?php print number_format($chq_amount[$i], $decimal); ?></span>
					</div>
				</div>
			<?php } ?>

			<!-- Row 1: Cheque No, Bank Code, Branch -->
			<div class="cheque-row">
				<div class="field">
					<label>Cheque No:</label>
					<span><?php print $chq_no; ?></span>
				</div>
				<div class="field">
					<label>Bank Code:</label>
					<span><?php print $chq_bank_code; ?></span>
				</div>
				<div class="field">
					<label>Branch:</label>
					<span><?php print $chq_branch; ?></span>
				</div>
			</div>

			<!-- Row 3: Salesman and Customer -->
			<?php for ($i = 0; $i < sizeof($chq_salesman); $i++) { ?>
				<div class="cheque-row">
					<div class="field">
						<label>Payment ID:</label>
						<span><?php if (isset($chq_id))
							echo $chq_id; ?></span>
					</div>
					<div class="field">
						<label>Salesman:</label>
						<span><?php print ucfirst($chq_salesman[$i]); ?></span>
					</div>
					<div class="field">
						<label>Related Invoice:</label>
						<span><?php print $chq_invno[$i]; ?></span>
					</div>
				</div>
			<?php } ?>


			<!-- Footer: Cheque Postpone Section -->
			<div class="cheque-footer">
				<?php if ($chq_postpone == 1) { ?>
					<div style="width:100%; font-size:14pt; background-color:purple; color:white; text-align:center">
						Cheque Postponed
					</div>
				<?php } ?>
				<?php if ($chq_postpone > 1) { ?>
					<div style="width:100%; font-size:14pt; background-color:green; color:white; text-align:center">
						Cheque Postpone-Pass
					</div>
				<?php } ?>
				<form id="chque_postpone" method="post"
					action="index.php?components=<?php print $menu_components; ?>&action=chque_set_postpone">
					<input type="hidden" name="chque_no" value="<?php print $chq_id; ?>" />
					<?php
					if ($chq_postpone == 0)
						print '<input type="hidden" id="case" name="case" value="add" />';
					else
						print '<input type="hidden" id="case" name="case" value="" />';
					?>
					<!-- Redesigned Footer Row -->
					<div class="footer-row">
						<div class="field">
							<label for="postpone_date">Cheque Postpone Date:</label>
							<input type="date" id="postpone_date" name="postpone_date" value="<?php print $chq_date2; ?>" />
						</div>
						<div class="field">
							<label for="master_pw3">Master Password (3):</label>
							<input type="password" id="master_pw3" name="master_pw3" />
						</div>
						<div class="field">
							<?php if ($chq_postpone == 0) { ?>
								<button type="button" onclick="setAction('add')">SET</button>
							<?php } ?>
							<?php if ($chq_postpone == 1) { ?>
								<button type="button" onclick="setAction('edit')">Update</button>
								<button type="button" onclick="setAction('remove')" style="background-color:green;">Clear</button>
							<?php } ?>
						</div>
					</div>
					<input type="submit" style="display:none" />
				</form>
			</div>

		</div>
	<?php }
} ?>
<hr /><br />
<div id="printheader" style="display:none">
	<h1 style="color:navy"><?php print $inf_company; ?></h1>
	<h2 align="center" style="color:#3333FF; text-decoration:underline; font-family:Calibri">List Of Postponed Cheques
	</h2>
	<hr />
</div>

<h2 align="center" style="color:#0158C2">List Of Postponed Cheques</h2>
<div id="print">
	<table align="center" bgcolor="#EEEEEE" height="100%" border="1" cellspacing="0" style="font-size:10pt">
		<tr style="font-weight:bold; background-color:#467898; color:white; -webkit-print-color-adjust: exact;">
			<th width="60px">#</th>
			<th>Cheque No</th>
			<th>Bank</th>
			<th>Cheque Date</th>
			<th>Postponed Date</th>
			<th>Amount</th>
			<th>Customer</th>
			<th>Related Invoice</th>
			<th>Collected By</th>
			<th>Status</th>
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
			<th>
				<select id="sm" onchange="filterSalesman()">
					<option id="">-ALL-</option>
					<?php for ($i = 0; $i < sizeof($salesman_filter); $i++) {
						if ($salesman_filter[$i] == $sm)
							$select = 'selected="selected"';
						else
							$select = '';
						print '<option id="' . $salesman_filter[$i] . '" ' . $select . ' >' . ucfirst($salesman_filter[$i]) . '</option>';
					} ?>
				</select>
			</th>
			<th></th>
			<th></th>
		</tr>
		<?php
		$total = 0;
		for ($i = 0; $i < sizeof($chq0_id); $i++) {
			if ($chq0_postpone[$i] == 'Postponed') {
				$style = 'style="background-color:#AA4477; color:white;"';
				$clear_btt = '';
			} elseif ($chq0_postpone[$i] == 'Postpone-Clear') {
				$style = 'style="background-color:green; color:white;"';
				$clear_btt = '<input type="button" value="Move to Postpone" onclick="moveToPostpone(' . $chq0_id[$i] . ')" />&nbsp&nbsp<a href="#" onclick="fullClearPostpone(' . $chq0_id[$i] . ')"  style="font-family:' . "'Courier New'" . ', Courier, monospace; color:red; font-weight:bold; font-size:14pt; text-decoration:none" title="Hide Record"><img src="images/action_delete.gif" style="vertical-align:middle" /></a>&nbsp;&nbsp;';
			}
			if (($sm == '-all-') || ($sm == $chq0_salesman[$i])) {
				print '<tr height="25px">
					<td align="center">
						' . sprintf('%02d', ($i + 1)) . '
					</td>
					<td style="padding-left:10px; padding-right:10px"><a style="text-decoration: none;" href="index.php?components=' . $menu_components . '&action=chque_postpone&chque_no=' . $chq0_code[$i] . '&sm=' . $sm . '">' . $chq0_code[$i] . '</a></td>
					<td style="padding-left:10px; padding-right:10px">' . $chq0_bank[$i] . '</td>
					<td style="padding-left:10px; padding-right:10px">' . $chq0_date[$i] . '</td>
					<td style="padding-left:10px; padding-right:10px">' . $chq0_postponed_date[$i] . '</td>
					<td style="padding-left:10px; padding-right:10px" align="right">' . number_format($chq0_amount[$i], $decimal) . '</td>
					<td style="padding-left:10px; padding-right:10px">' . ucfirst($chq0_cuname[$i]) . '</td>
					<td align="center">' . $chq0_invno[$i] . '</td>
					<td  style="padding-left:10px; padding-right:10px">' . ucfirst($chq0_salesman[$i]) . '</td>
					<td ' . $style . ' style="padding-left:10px; padding-right:10px">' . $chq0_postpone[$i] . '</td>
					<td>' . $clear_btt . '</td>
				</tr>';
				$total += $chq0_amount[$i];
			}
		}
		print '<tr>
			<td colspan="5" align="right" style="padding-right: 10px"><strong>Total</strong></td>
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