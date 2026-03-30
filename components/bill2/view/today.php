<?php
include_once 'template/header.php';
$decimal = getDecimalPlaces(1);
$user_id = $_COOKIE['user_id'];
?>
<style>
	table {
		font-family: Calibri;
	}

	.tbl-header {
		font-family: Calibri;
		color: maroon;
		font-weight: bold;
		background: #EEEEEE;
		width: 800px;
	}

	.td-style {
		background-color: silver;
		color: navy;
		font-family: Calibri;
		font-size: 14pt;
	}

	.styled-table {
		border-collapse: collapse;
		margin-top: 30px;
		font-family: Calibri;
		min-width: 400px;
		box-shadow: 0 0 12px rgba(0, 0, 0, 0.15);
	}

	.styled-table thead tr {
		/* background-color: #009879; */
		background-color: #3f83d7;
		color: #ffffff;
		text-align: left;
	}

	.styled-table th,
	.styled-table td {
		padding: 5px 15px;
	}

	.styled-table tbody tr {
		border-bottom: thin solid #dddddd;
	}

	.styled-table tbody tr:nth-of-type(even) {
		/* background-color: #f3f3f3; */
	}

	.styled-table tbody tr:last-of-type {
		border-bottom: 2px solid #205081;
	}

	.styled-table tbody tr:hover {
		background-color: #f3f3f3;
	}

	.wrap {
		margin-top: 20px;
		display: flex;
		flex-direction: column;
	}
</style>

<!-- Notifications -->
<table align="center" cellspacing="0">
	<tr>
		<td>
			<?php
			if (isset($_REQUEST['message'])) {
				if ($_REQUEST['re'] == 'success')
					$color = 'green';
				else
					$color = 'red';
				print '<script type="text/javascript">document.getElementById("notifications").innerHTML=' . "'" . '<span style="color:' . $color . '; font-weight:bold;font-size:12pt;">' . $_REQUEST['message'] . '</span>' . "'" . ';</script>';
			}
			?>
		</td>
	</tr>
</table>
<!--// Notifications -->

<!-- Header -->
<table align="center" cellspacing="0" class="tbl-header" width="720px">
	<tr>
		<td align="center" style="padding: 10px; font-size: 13pt;">
			<?php
			if ($systemid == 4)
				print 'List of ALL Invoices, Payments and Return Invoices by This Shop for : ' . $date;
			else
				print 'List of Invoices, Payments and Return Invoices by ' . ucfirst($_COOKIE['user']) . ' for : ' . $date;
			?>
		</td>
	</tr>
</table>
<!--/ Header -->

<div class="wrap">
	<!-- Today Invoices [Completed] -->
	<div>
		<table align="center" width="720px" style="background:dddddd">
			<tr>
				<td bgcolor="red" width="15px" height="15px"></td>
				<td>Discounted Inv</td>
				<td width="250px"></td>
				<td bgcolor="#009900" width="15px" height="15px"></td>
				<td>Cash</td>
				<td width="20px"></td>
				<td bgcolor="#00AAAA" width="15px" height="15px"></td>
				<td>Bank</td>
				<td width="20px"></td>
				<td bgcolor="blue" width="15px" height="15px"></td>
				<td>Cheque</td>
				<td width="20px"></td>
				<td bgcolor="black" width="15px" height="15px"></td>
				<td>Credit</td>
				<td width="20px"></td>
			</tr>
		</table>
		<table align="center" class="styled-table" style="margin-top:0px" width="720px">
			<thead>
				<tr>
					<td colspan="5" style="color: black; background: #dddddd;" class="td-style"><strong>Today Invoices
							[Completed]</strong></td>
				</tr>
				<tr>
					<th width="20px">#</th>
					<th width="120px" align="center">Invoice No</th>
					<th width="120px" align="center">Time</th>
					<th width="100px" align="center">Amount</th>
					<th align="center">Customer</th>
				</tr>
				<thead>
				<tbody>
					<?php
					$inv = 0;
					for ($i = 0; $i < sizeof($invoice_no); $i++) {
						if ($bi_discount[$i] > 0) {
							$color1 = 'style="color:red"';
							$title = 'title="Discounted Invoice"';
						} else {
							$color1 = '';
							$title = '';
						}
						$key = array_search($invoice_no[$i], $py_invno);
						$color2 = 'black';
						if ($bm_sys_user[$i] == $user_id)
							$color = '#F2F3F4';
						else
							$color = '';
						if ($key > -1) {
							if ($py_type[$key] == 1)
								$color2 = '#009900';
							if ($py_type[$key] == 2)
								$color2 = 'blue';
							if ($py_type[$key] == 3)
								$color2 = '#00AAAA';
						}
						print '<tr bgcolor="' . $color . '" ' . $color1 . '>
								<td>' . ($i + 1) . '</td>
								<td align="center"><a target="_blank" ' . $title . ' href="index.php?components=bill2&action=finish_bill&id=' . $invoice_no[$i] . '">' . str_pad($invoice_no[$i], 7, "0", STR_PAD_LEFT) . '</a></td>
								<td align="center">' . (isTimeShow() ? $time[$i] : '') . '</td>
								<td align="right" style="color:' . $color2 . '"><strong>' . number_format($invoice_total[$i], $decimal) . '</strong></td>
								<td>' . $cust[$i] . '</td>
							</tr>';
					}
					?>
				</tbody>
		</table>
	</div>
	<!--/ Today Invoices [Completed] -->

	<!-- Pending Invoices -->
	<div>
		<table align="center" border="0" class="styled-table" width="720px">
			<thead>
				<tr>
					<td colspan="5" style="color: black; background: #dddddd;" class="td-style"><strong
							style="padding-left: 10px">Pending Invoices</strong></td>
				</tr>
				<tr>
					<th width="20px">#</th>
					<th width="120px" align="center">Invoice No</th>
					<th width="120px" align="center">Date</th>
					<th width="100px" align="center">Amount</th>
					<th align="center">Customer</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$inv = 0;
				for ($i = 0; $i < sizeof($linvoice_no); $i++) {
					if ($lbm_type[$i] == 4 || $lbm_type[$i] == 5)
						$cust_odr = 'yes';
					else
						$cust_odr = 'no';
					if ($lbm_sys_user[$i] == $user_id) {
						$color = '#F2F3F4';
						$edit = '&nbsp;&nbsp;&nbsp;&nbsp;<input type="Button" value="Edit"  onclick="window.location = ' . "'" . 'index.php?components=bill2&action=home&cust_odr=' . $cust_odr . '&id=' . $linvoice_no[$i] . '&s=' . $lbm_salesman[$i] . '&cust=' . $lcustid[$i] . "'" . '" />';
					} else {
						$edit = '';
						$color = '#EEDDDD';
					}

					print '<tr>
							<td>' . ($i + 1) . '</td>
							<td align="center"><a target="_blank" href="index.php?components=bill2&action=finish_bill&id=' . $linvoice_no[$i] . '">' . str_pad($linvoice_no[$i], 7, "0", STR_PAD_LEFT) . '</a>' . $edit . '</td>
							<td align="center">' . $ldate[$i] . '</td>
							<td align="right">' . number_format($linvoice_total[$i], $decimal) . '&nbsp;&nbsp;</td>
							<td>' . $lcust[$i] . '</td>
						</tr>';
				}
				?>
			</tbody>
		</table>
	</div>
	<!--/ Pending Invoices  -->

	<!-- Temporary Invoices -->
	<div>
		<table align="center" border="0" class="styled-table" width="720px">
			<thead>
				<tr>
					<td colspan="5" style="color: black; background: #dddddd;" class="td-style"><strong
							style="padding-left: 10px">Temporary Invoices</strong></td>
				</tr>
				<tr>
					<th width="20px">#</th>
					<th width="120px" align="center">Temp Bill No</th>
					<th width="120px" align="center">Date</th>
					<th width="100px" align="center">Amount</th>
					<th align="center">Customer</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$inv = 0;
				for ($i = 0; $i < sizeof($tmp_bm_no); $i++) {
					if ($tmp_bm_type[$i] == 4 || $tmp_bm_type[$i] == 5)
						$cust_odr = 'yes';
					else
						$cust_odr = 'no';
					if (($tmp_bm_sys_user[$i] == $user_id) || ($tmp_bm_salesman[$i] == $user_id)) {
						$color = '#F2F3F4';
						$edit = '&nbsp;&nbsp;&nbsp;&nbsp;<input type="Button" value="Edit"  onclick="window.location = ' . "'" . 'index.php?components=bill2&action=bill_item&cust_odr=' . $cust_odr . '&bill_no=' . $tmp_bm_no[$i] . "'" . '" />';
					} else {
						$edit = '';
						$color = '#EEDDDD';
					}
					print '<tr bgcolor="' . $color . '">
								<td>' . ($i + 1) . '</td>
								<td align="center">' . str_pad($tmp_bm_no[$i], 7, "0", STR_PAD_LEFT) . $edit . '</td>
								<td align="center">' . $tmp_date[$i] . '</td>
								<td align="right">' . number_format($tmp_total[$i], $decimal) . '</td>
								<td>' . $tmp_cust[$i] . '</td>
							</tr>';
				}
				?>
			</tbody>
		</table>
	</div>
	<!--/ Temporary Invoices -->

	<!-- Payment Invoices -->
	<div>
		<table align="center" class="styled-table" width="720px">
			<thead>
				<tr>
					<td colspan="5" style="color: black; background: #dddddd;" class="td-style"><strong
							style="padding-left: 10px">Payment Invoices</strong></td>
				</tr>
				<tr bgcolor="#E5E5E5">
					<th width="20px">#</th>
					<th width="120px" align="center">Invoice No</th>
					<th width="100px" align="center">Time</th>
					<th width="100px" align="center">Amount</th>
					<th width="200px" align="center">Customer</th>
				</tr>
			</thead>
			<?php
			$inv = 0;
			for ($i = 0; $i < sizeof($py_id); $i++) {
				if ($py_bill_pay[$i] == 2) {
					if ($py_sys_user[$i] == $user_id)
						$color = '#F2F3F4';
					else
						$color = '';
					if ($py_type[$i] == 1)
						$color2 = '#009900';
					if ($py_type[$i] == 2)
						$color2 = 'blue';
					if ($py_type[$i] == 3)
						$color2 = '#00AAAA';
					print '<tr bgcolor="' . $color . '">
								<td>' . ($i + 1) . '</td>
								<td align="center"><a target="_blank" href="index.php?components=bill2&action=finish_payment&id=' . $py_id[$i] . '">' . str_pad($py_id[$i], 7, "0", STR_PAD_LEFT) . '</a></td>
								<td align="center">' . (isTimeShow() ? $py_time[$i] : '') . '</td>
								<td align="right" style="color:' . $color2 . '"><strong>' . number_format($py_amount[$i], $decimal) . '&nbsp;&nbsp;</strong></td>
								<td>' . $py_cust[$i] . '</td>
							</tr>';
				}
			}
			?>
		</table>
	</div>
	<!--/ Payment Invoices -->

	<!-- Return Invoices -->
	<div>
		<table align="center" class="styled-table" width="720px">
			<thead>
				<tr>
					<td colspan="4" style="color: black; background: #dddddd;" class="td-style"><strong
							style="padding-left: 10px">Return Invoices</strong></td>
				</tr>
				<tr>
					<th width="20px">#</th>
					<th width="120px" align="center">Invoice No</th>
					<th width="100px" align="center">Time</th>
					<th align="left">Customer</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$inv = 0;
				for ($i = 0; $i < sizeof($rt_invno); $i++) {
					if ($rt_status[$i] == 1)
						$edit = '<input type="Button" value="Edit"  onclick="window.location = ' . "'" . 'index.php?components=bill2&action=item_return&id=' . $rt_invno[$i] . '&cust=15&cust=' . $rt_cust_id[$i] . "'" . '" />';
					else
						$edit = '';
					if ($rt_return_by[$i] == $user_id)
						$color = '#F2F3F4';
					else
						$color = '';

					print '<tr bgcolor="' . $color . '">
								<td>' . ($i + 1) . '</td>
								<td align="center"><a target="_blank" href="index.php?components=bill2&action=finish_return&id=' . $rt_invno[$i] . '">' . str_pad($rt_invno[$i], 7, "0", STR_PAD_LEFT) . '</a>&nbsp;&nbsp;&nbsp;&nbsp;' . $edit . '</td>
								<td align="center">' . (isTimeShow() ? $rt_time[$i] : '') . '</td>
								<td align="left">' . $rt_cust_name[$i] . '</td>
							</tr>';
				}
				?>
			</tbody>
		</table>
	</div>
	<!--/ Return Invoices -->

	<!-- Warranty Invoices -->
	<div>
		<table align="center" class="styled-table" width="720px">
			<thead>
				<tr>
					<td colspan="5" style="color: black; background: #dddddd;" class="td-style"><strong
							style="padding-left: 10px">Warranty Invoices</strong></td>
				</tr>
				<tr>
					<th width="20px">#</th>
					<th width="120px" align="center">Invoice No</th>
					<th width="120px" align="center">Warranty No</th>
					<th width="100px" align="center">Time</th>
					<th align="center">Customer</th>
				</tr>
			</thead>
			<tbody>
				<?php
				for ($i = 0; $i < sizeof($w_invno); $i++) {
					print '
						<tr>
							<td>' . ($i + 1) . '</td>
							<td align="center"><a target="_blank" href="index.php?components=bill2&action=finish_bill&id=' . $w_invno[$i] . '">' . str_pad($w_invno[$i], 7, "0", STR_PAD_LEFT) . '</a></td>
							<td align="center"><a target="_blank" href="index.php?components=bill2&action=warranty_show&id=' . $w_no[$i] . '">' . str_pad($w_no[$i], 7, "0", STR_PAD_LEFT) . '</a></td>
							<td align="center">' . (isTimeShow() ? $w_time[$i] : '') . '</td>
							<td>' . $w_cust_name[$i] . '</td>
						</tr>
					';
				}
				?>
			</tbody>
		</table>
	</div>
	<!--/ Warranty Invoices -->
</div>

<?php
include_once 'template/footer.php';
?>