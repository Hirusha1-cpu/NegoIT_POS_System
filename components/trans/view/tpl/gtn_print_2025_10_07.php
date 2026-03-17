<?php
include_once '../../modle/transModule.php';
include_once '../../../../template/common.php';
generateGTN();
$paper_size = paper_size(2);
$decimal = getDecimalPlaces(2);
$systemid = inf_systemid(2);
if ($paper_size == 'A4') {
	$page_height = 800;
}
if ($paper_size == 'A5') {
	$page_height = 410;
}

if ($gtn_status == 'Pending') {
	if ($_GET['approve_permission'] == 1)
		$remote_user = ucfirst($gtn_to_user);
	else
		$remote_user = '..............................';
} else {
	$remote_user = ucfirst($gtn_to_user);
}
?>
<div id="print_top"></div>
<table width="100%">
	<tr>
		<td rowspan="2" style="font-family:Arial; font-size:11pt">
			<strong><?php print $gtn_from_shop_name; ?></strong><br />
			<br />
			<table>
				<tr>
					<td><strong>FROM </strong></td>
					<td>: <?php print $gtn_item_from; ?></td>
				</tr>
				<tr>
					<td><strong>TO </strong></td>
					<td>: <?php print $gtn_item_to; ?></td>
				</tr>
				<tr>
					<td><strong>Status </strong></td>
					<td>: <?php print $gtn_status; ?></td>
				</tr>
			</table>
		</td>
		<td></td>
		<td align="right"><span style="font-family:'Arial Black'; font-size:20pt">GTN</span><br /><br /></td>
	</tr>
	<tr>
		<td></td>
		<td align="right"><span style="font-family:Arial; font-size:11pt">
				GTN # [<?php print str_pad($_REQUEST['id'], 7, "0", STR_PAD_LEFT); ?>]<br />
				DATE: <?php print $gtn_date; ?><br />
				<?php if ($gtn_cross_invoice != 0)
					print 'BILL NO: ' . str_pad($gtn_cross_invoice, 7, "0", STR_PAD_LEFT); ?>
				<br />
			</span>
		</td>
	</tr>
</table>

<table align="center" height="<?php print $page_height; ?>px" width="100%" border="1" cellspacing="0">
	<tr style="font-family:Arial; font-size:10pt; height: 20px;">
		<?php
		// --- CHANGE 1: Conditionally show the DRAWER header ---
		if ($systemid != 13) {
			print '<th width="40px" height="20px">DRAWER</th>';
		}
		?>
		<th>DESCRIPTION</th>
		<?php if (($paper_size == 'A4') && ($systemid != 13)) {
			print '<th width="70px">Unit Cost</th>';
		} ?>
		<th width="50px">QTY</th>
		<?php if (($paper_size == 'A4') && ($systemid != 13)) {
			print '<th width="80px">Total</th>';
		} ?>
	</tr>

	<?php
	$table_u_cost = $table_total_cost = $table_expand = '';
	// --- CHANGE 2: Adjust the initial colspan based on the system ID ---
	$colspan = ($systemid != 13) ? 2 : 1;
	$systemid == 13 ? $systemid13Styles = 'style="border-bottom:1px dashed #ccc;"' : $systemid13Styles = 'border-bottom:0; ';

	for ($i = 0; $i < sizeof($gtn_item_id); $i++) {
		if (($paper_size == 'A4') && ($systemid != 13)) {
			$table_u_cost = '<td style="' . $systemid13Styles . ' border-top:0; padding-right:10px" align="right">
							' . number_format($gtn_c_price[$i], $decimal) . '</td>';
			$table_total_cost = '<td style="' . $systemid13Styles . ' border-top:0; padding-right:10px" align="right">
							' . number_format($gtn_item_qty[$i] * $gtn_c_price[$i], $decimal) . '</td>';
			$table_expand = '<td style="' . $systemid13Styles . ' border-top:0;"></td>
						<td style="' . $systemid13Styles . ' border-top:0;"></td>';
			// --- CHANGE 3: Adjust the A4 colspan based on the system ID ---
			$colspan = ($systemid != 13) ? 4 : 3;
		}
		print '<tr style="font-size:10pt" height="20px">';

		// --- CHANGE 4: Conditionally show the DRAWER data cell ---
		if ($systemid != 13) {
			print '<td align="center" style="' . $systemid13Styles . ' border-top:0;">' . $gtn_item_draw[$i] . '</td>';
		}
		print '<td style="' . $systemid13Styles . ' border-top:0; padding:0 10px; ">' . sprintf('%02d', ($i + 1)) . '. ' . $gtn_item_des[$i] . '</td>' . $table_u_cost . '<td
							style="' . $systemid13Styles . ' border-top:0; padding-right:10px" align="right">' . number_format($gtn_item_qty[$i]) . '&nbsp;' . $gtn_item_unit[$i] . '
						</td>' . $table_total_cost . '</td>
					</tr>';
	}
	print '<tr style="font-size:10pt">';
	// --- CHANGE 5: Conditionally show the empty spacer cell for the DRAWER column ---
	if ($systemid != 13) {
		print '<td style="border-bottom:0; border-top:0;"></td>';
	}

	print '<td style="border-bottom:0; border-top:0;"></td>
					<td style="border-bottom:0; border-top:0;"></td>' . $table_expand . '
				</tr>';

	if ($paper_size == 'A4') {
		if ($systemid != 13) {
			print '<tr style="font-size:10pt" height="20px">
						<td align="right" style="border-bottom:0; border-top:1; padding-right:20px" colspan="' . $colspan . '"><strong>Total
								Cost</strong></td>
						<td style="border-bottom:0; border-top:1; padding-right:10px" align="right"><strong>' . number_format(
				$total_cost,
				$decimal
			) . '</strong></td>
						</td>
						</tr>';
		}
	}
	?>
</table>
<br />

<table align="center" width="100%" border="1" cellspacing="0">
	<tr style="font-size:8pt;">
		<td>
			<table align="center">
				<tr>
					<td style="font-family:Arial; font-size:9pt">Issued By</td>
					<td> : <?php print ucfirst($gtn_from_user); ?></td>
					<td width="45px"></td>
					<td style="font-family:Arial; font-size:9pt">Received By</td>
					<td> : <?php print $remote_user; ?></td>
				</tr>
				<tr>
					<td style="font-family:Arial; font-size:9pt">Name</td>
					<td> ..............................</td>
					<td></td>
					<td style="font-family:Arial; font-size:9pt">Name</td>
					<td> ..............................</td>
				</tr>
				<tr>
					<td style="font-family:Arial; font-size:9pt">Signature</td>
					<td> ..............................</td>
					<td></td>
					<td style="font-family:Arial; font-size:9pt">Signature</td>
					<td> ..............................</td>
				</tr>
				<tr>
					<td style="font-family:Arial; font-size:9pt">Location</td>
					<td> : <?php print $gtn_item_from; ?></td>
					<td></td>
					<td style="font-family:Arial; font-size:9pt">Location</td>
					<td> :
						<?php if ($systemid == 13)
							print $gtn_item_to;
						else if ($_GET['approve_permission'] == 1)
							print $gtn_item_to;
						else
							print '..............................';
						?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>