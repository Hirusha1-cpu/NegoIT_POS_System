<?php
include_once 'template/header.php';
$store_report = 'ALL';
$menu_components = $_GET['components'];
$decimal = getDecimalPlaces(1);
?>
<link rel="stylesheet" href="plugin/jquery/css/jquery.ui.all.css" />
<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
<script src="plugin/jquery/ui/jquery.ui.core.js"></script>
<script src="plugin/jquery/ui/jquery.ui.widget.js"></script>
<script src="plugin/jquery/ui/jquery.ui.position.js"></script>
<script src="plugin/jquery/ui/jquery.ui.autocomplete.js"></script>
<link rel="stylesheet" href="plugin/jquery/css/demos.css" />
<style>
	.bold-span{
		font-weight: bold;
   	 	color: green;
	}
    tr {
        transition: background-color 0.3s ease;
    }
</style>
<!-- Scripts -->
<script type="text/javascript">
	$(function () {
		var availableTags1 = [<?php for ($x = 0; $x < sizeof($bnk_name); $x++) {
			print '"' . $bnk_name[$x] . '",';
		} ?>];
		<?php
		for ($i = 0; $i < sizeof($py_id2); $i++) {
			print '$( "#' . $py_id2[$i] . '_bnk' . '" ).autocomplete({	source: availableTags1	});';
		}
		?>
	});

	function updateTotal() {
		const allCheques = document.querySelectorAll('.cheque-checkbox'); // Select all checkboxes
		const checkedCheques = document.querySelectorAll('.cheque-checkbox:checked'); // Select checked checkboxes
		document.getElementById('checkedCount').value;
		let totalAmount = 0;

		// Calculate total and count for checked checkboxes
		checkedCheques.forEach(checkbox => {
			totalAmount += parseFloat(checkbox.getAttribute('data-amount'));
		});

		document.getElementById('total-amount').innerText = totalAmount.toFixed(2);
		document.getElementById('cheque_count').innerText = checkedCheques.length;
		// Update row highlighting for all checkboxes
		allCheques.forEach(checkbox => {
			const row = checkbox.closest("tr");
			if (checkbox.checked) {
				row.style.backgroundColor = "#BFED9B"; // Highlight for checked
			} else {
				row.style.backgroundColor = ""; // Reset to default
			}
		});
	}

	function filterChquePendingFinalyze() {
		$components = document.getElementById("components").value;
		$dateto = document.getElementById("dateto").value;
		$st = document.getElementById("st").value;
		$sm = document.getElementById("sm").value;
		window.location = 'index.php?components=' + $components + '&action=chque_pending_finalyze2&dateto=' + $dateto + '&st=' + $st + '&sm=' + $sm;
	}

	// Function to update the total amount of selected cheques
	function bulkDeposit() {
		const bankId = document.getElementById('bulk-bank-select').value;
		const depositDate = document.getElementById('bulk-deposit-date').value;

		// Check for bank and date selection
		if (!bankId || !depositDate) {
			alert("Please select both a bank and a date.");
			return;
		}

		const checkedCheques = document.querySelectorAll('.cheque-checkbox:checked');
		if (checkedCheques.length === 0) {
			alert("Please select at least one cheque.");
			return;
		}

		const bankName = document.getElementById('bulk-bank-select').options[document.getElementById('bulk-bank-select').selectedIndex].text;
		if (!confirm(`Are you sure you want to deposit all checked cheques to ${bankName} on ${depositDate}?`)) {
			return;
		}

		const depositData = [];
		checkedCheques.forEach(checkbox => {
			const chequeId = checkbox.getAttribute('data-id');
			const amount = checkbox.getAttribute('data-amount');

			depositData.push({
				id: chequeId,
				bnk: bankName,
				pydate: depositDate,
				amount: amount
			});

			// Replace the checkbox with a loading image
			const row = checkbox.closest('tr');
			const checkboxCell = row.querySelector('td:first-child');
			if (checkboxCell) {
				checkboxCell.innerHTML = '<img src="images/loading.gif" style="width:40px" />';
			}
		});

		// Send AJAX request
		const xhr = new XMLHttpRequest();
		xhr.open("POST", "index.php?components=<?php echo $components; ?>&action=clear_chque_2", true);
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

		xhr.onreadystatechange = function () {
			if (xhr.readyState === 4) {
				if (xhr.status === 200) {
					try {
						const response = JSON.parse(xhr.responseText);

						// Iterate through each response object
						response.forEach(result => {
							const chequeId = result.chequeId;
							const success = result.success;
							const message = result.message;
							// Locate the corresponding row using the data attribute
							const row = document.querySelector(`tr[data-cheque-id="${chequeId}"]`);
							const checkboxCell = row.querySelector('td:first-child');

							if (checkboxCell) {
								if (success) {
									checkboxCell.innerHTML = '<span style="color: green; font-weight: bold;">DONE</span>';
									alert(message);
								} else {
									checkboxCell.innerHTML = '<input type="checkbox" class="cheque-checkbox" data-id="' + chequeId + '" data-amount="' + checkbox.getAttribute('data-amount') + '" onclick="updateTotal()"/>';
									alert(`Error for cheque ID ${chequeId}: ${message}`);
								}
							} else {
								// console.log(`Could not locate checkbox cell for chequeId ${chequeId}`);
							}
						});
						// Reset bank and date fields
						document.getElementById('bulk-bank-select').value = ""; // Reset to default (-ALL-)
						document.getElementById('bulk-deposit-date').value = ""; // Clear date input
					} catch (e) {
						alert("Server response could not be parsed: " + e.message);
					}
				} else {
					alert("Request failed. Please try again.");
				}
			}
		};
		xhr.send("data=" + encodeURIComponent(JSON.stringify(depositData)));
	}
</script>
<!--// Scripts -->

<!-- Notifications -->
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
<!--// Notifications -->

<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px; padding-top:6px;" /></div>

<form action="index.php" method="get" onsubmit="return validateDateRange()">
	<input type="hidden" id="components" name="components" value="<?php print $menu_components; ?>" />
	<input type="hidden" name="action" value="chque_pending_finalyze2" />
	<table bgcolor="#F0F0F0" align="center" height="100%" cellspacing="0" style="font-size:10pt; font-family:Calibri">
		<tr>
			<td width="80px" align="left" class="shipmentTB3"><strong>Up To : </strong></td>
			<td width="220px">
				<input type="date" id="dateto" name="dateto" style="width:130px" value="<?php print $todate; ?>" />
				<input style="width:60px; height:40px" type="submit" value="GET" />
			</td>
			<td><strong>Associated Store</strong></td>
			<td>
				<select id="st" name="st" onchange="filterChquePendingFinalyze()">
					<option value="">-ALL-</option>
					<?php for ($i = 0; $i < sizeof($st_id); $i++) {
						if (isset($_GET['st'])) {
							if ($_GET['st'] == $st_id[$i]) {
								$select = 'selected="selected"';
								$store_report = $st_name[$i];
							} else
								$select = '';
						} else
							$select = '';
						print '<option value="' . $st_id[$i] . '" ' . $select . '>' . $st_name[$i] . '</option>';
					}
					?>
				</select>
			</td>
			<td width="50px"></td>
			<td><strong>Collected Salesman</strong></td>
			<td>
				<select id="sm" name="sm" onchange="filterChquePendingFinalyze()">
					<option value="">-ALL-</option>
					<?php for ($i = 0; $i < sizeof($sm_id); $i++) {
						if (isset($_GET['sm'])) {
							if ($_GET['sm'] == $sm_id[$i]) {
								$select = 'selected="selected"';
								$salesman_report = $sm_name[$i];
							} else
								$select = '';
						} else
							$select = '';
						print '<option value="' . $sm_id[$i] . '" ' . $select . '>' . ucfirst($sm_name[$i]) . '</option>';
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="5"></td>
			<td colspan="3" align="right"><input type="button"
					onclick="window.location = 'index.php?components=<?php print $menu_components; ?>&action=clear_chque_list&year=<?php print date("Y", time()); ?>'"
					value="List of Cleared Cheques" /></td>
		</tr>
		<tr>
			<td colspan="8">
				<hr />
			</td>
		</tr>
	</table>
</form>

<br />

<?php if (sizeof($py_id2) > 0) { ?>
	<table align="center" height="100%" style="font-size:10pt; font-family:Calibri">
		<tr bgcolor="#AAAAAA">
			<td colspan="11" style="color:white; font-weight:bold">&nbsp;&nbsp;List of Realized cheques as of
				<?php print $todate; ?></td>
		</tr>
		<?php if ($menu_components == 'manager' || $menu_components == 'fin') { ?>
			<tr>
				<td colspan="3"><strong>Total of Selected Cheques: </strong><span id="total-amount" class="bold-span">0.00</span> (<span id="cheque_count" class="bold-span">0</span>)</td>
				<td class="td" align="right"><strong>Deposited Bank</strong></td>
				<td class="td">
					<select id="bulk-bank-select" name="bulk-bank-select">
						<option value="">-ALL-</option>
						<?php for ($i = 0; $i < sizeof($bnk_id); $i++) {
							if (isset($_REQUEST['bnk'])) {
								if ($_REQUEST['bnk'] == $bnk_id[$i]) {
									$select = 'selected="selected"';
								} else
									$select = '';
							} else
								$select = '';
							print '<option value="' . $bnk_id[$i] . '" ' . $select . '>' . $bnk_name[$i] . '</option>';
						} ?>
					</select>
				</td>
				<td class="td" align="right" width="100px"><strong>Deposited Date</strong></td>
				<td class="td">
					<input type='date' name='bulk-deposit-date' id='bulk-deposit-date' />
				</td>
				<td></td>
				<td colspan="4" class="td" align="right">
					<button onclick="bulkDeposit()" style="height: 30px; font-weight: 600;" class="bold-span">Deposit Selected Cheques</button>
				</td>
			</tr>
        <?php } ?>
		<tr bgcolor="#CCCCCC">
			<th><span id="checkedCount"></span></th>
			<th>#</th>
			<th>Payment No</th>
			<th width="200px">Customer</th>
			<th>Cheque</th>
			<th>Cheque Name</th>
			<th>Date</th>
			<th width="100px">Amount</th>
			<th>Salesman</th>
			<th width="100px">Associated Shop</th>
			<th>#</th>
		</tr>
		<?php
		for ($i = 0; $i < sizeof($py_id2); $i++) {
			print '<tr bgcolor="#EEEEEE" data-cheque-id="' . $py_id2[$i] . '">
					<td class="shipmentTB3" align="center">
						<input type="checkbox" class="cheque-checkbox" data-id="' . $py_id2[$i] . '" data-amount="' . $payment_amount2[$i] . '" onclick="updateTotal()"/>
					</td>
					<td class="shipmentTB3" align="center">' . sprintf('%02d',($i+1)) . '</td>
					<td class="shipmentTB3" align="center">' . $py_id2[$i] . '</td>
					<td class="shipmentTB3">' . ucfirst($customer2[$i]) . '</td>
					<td class="shipmentTB3">' . $cheque_no2[$i] . '</td>
					<td class="shipmentTB3">' . $cheque_name2[$i] . '</td>
					<td align="center"><a title="Payment was done on ' . $payment_date2[$i] . '" href="#">' . $chq_date2[$i] . '</a></td>
					<td align="right"  class="shipmentTB3">' . number_format($payment_amount2[$i], $decimal) . '</td>
					<td  class="shipmentTB3">' . ucfirst($payment_salesman2[$i]) . '</td>
					<td  class="shipmentTB3">' . $payment_store2[$i] . '</td>';
			print '<td class="shipmentTB3" align="center">' . sprintf('%02d',($i+1)). '</td>';
			print '</tr>';
		}
		?>
	</table>
	<br />
<?php } ?>

<?php
	include_once 'template/footer.php';
?>