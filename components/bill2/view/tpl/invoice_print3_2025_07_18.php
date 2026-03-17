<?php
include_once '../../modle/bill2Module.php';
include_once '../../../../template/common.php';
generateInvoice('bi.id');
generalPrint();
$systemid = inf_systemid(2);
$currency = getCurrency(2);
$sub_system = $_COOKIE['sub_system'];
$paper_size = paper_size(2);
if ($paper_size == 'A4') {
	if ($logo === '13_3') {
		$page_height = 720;
	} else {
		$page_height = 760;
	}

	if ($chq0_date != '') {
		$chequedate = '[Cheque Date: ' . $chq0_date . ' ]&nbsp;&nbsp;&nbsp;&nbsp;';
	} else {
		$chequedate = '';
	}
}
if ($paper_size == 'A5') {
	$page_height = 480;
	$chequedate = '';
}
if ($bm_quotation_no != 0) {
	$page_height -= 70;
}
if ($bi_type == 1 || $bi_type == 2) {
	$bill_title = 'INVOICE';
	$sub_title = 'INVOICE NO';
	$advance = '';
} else if ($bi_type == 3) {
	$bill_title = 'INVOICE';
	$sub_title = 'REPAIR NO';
	$advance = 'Advance';
} else if ($bi_type == 4 || $bi_type == 5) {
	if ($bm_status < 3) {
		$bill_title = 'CUST ORDER';
		$sub_title = 'ORDER NO';
		$advance = 'Advance';
	} else {
		$bill_title = 'INVOICE';
		$sub_title = 'INVOICE NO';
		$advance = '';
	}
}
$dn = $_GET['dn'];
if ($dn == 'yes') {
	$bill_title = 'DELIVERY NOTE';
	$sub_title = 'DELIVERY NOTE NO';
	$by_title = 'Order Packed By';
	$by_name = $bm_packed_by;
	$page_height -= 250;
} else {
	$by_title = 'Salesman';
	$by_name = $up_salesman;
}
?>

<div id="print_top"></div>

<table width="100%" border="0" style="font-family:Calibri; font-size:12pt;">
	<tr>
		<td>
			<table style="font-family:Calibri; font-size:12pt;" cellspacing="0">
				<tr>
					<td colspan="2">
						<?php if ($systemid == 13) { ?>
							<span
								style="font-family:'Arial Black'; font-size:12pt; text-transform: uppercase;"><?php print $tm_company; ?></span>
							<br>
							<span
								style="font-family:'Arial'; font-size:11pt; font-weight:700;"><?php print $bill_title; ?></span>
						<?php } else { ?>
							<span style="font-family:'Arial Black'; font-size:20pt"><?php print $bill_title; ?></span>
						<?php } ?>
						<span style="font-size:12pt; font-family:Calibri"><?php if ($bi_type == 2 || $bi_type == 5)
							print '<br /><strong>Service Invoice</strong>';
						else if ($bi_type == 3)
							print '<br /><strong>Repair Invoice</strong>'; ?></span>
					</td>
				</tr>
				<tr>
					<td>Tel </td>
					<td>: <?php print $tm_tel; ?></td>
				</tr>
				<tr>
					<td>Email </td>
					<td>: <?php print $tm_email; ?></td>
				</tr>
				<tr>
					<td>Web </td>
					<td>: <?php print $tm_web; ?></td>
				</tr>
			</table>
		</td>
		<td></td>
		<td align="right">
			<img src="../../../../images/cplogo<?php print $logo; ?>.png" height="<?php if ($logo === '13_3')
				   echo "150px;";
			   else
				   echo "33px;"; ?>" />
		</td>
	</tr>
	<tr>
		<td>
			<?php print '<a href="../../../../index.php?components=bill2&action=cust_details&id=' . $cu_id . '&action2=finish_bill&id2=' . $_GET['id'] . '" target="_parent" title="' . $cu_details . '" style="text-decoration:none; color:black" >' . ucfirst($bi_cust0) . '</a>,<br />' . $bi_cust0_address; ?>
		</td>
		<td align="right" colspan="2" style="font-family:Calibri; font-size:11pt">
			<table cellspacing="0" border="0">
				<tr>
					<td width="50px"></td>
					<td><?php print $sub_title; ?></td>
					<td> : </td>
					<td align="right"><?php print str_pad($_GET['id'], 7, "0", STR_PAD_LEFT); ?></td>
				</tr>
				<?php if ($bm_quotation_no != 0) {
					print '<tr>
						<td></td>
						<td>Ref Quotation No</td>
						<td> : </td>
						<td align="right">' . str_pad($bm_quotation_no, 7, "0", STR_PAD_LEFT) . '</td>
					</tr>';
					print '<tr>
						<td></td>
						<td>Ref PO No</td>
						<td> : </td>
						<td align="right">' . $qm_po . '</td>
					</tr>';
				} ?>
				<tr>
					<td width="50px"></td>
					<td><?php print $by_title; ?></td>
					<td> : </td>
					<td align="right"><?php print ucfirst($by_name); ?></td>
				</tr>
				<tr>
					<td colspan="2">TIME: <?php print substr($bi_time, 0, 5); ?> &nbsp;&nbsp;&nbsp;&nbsp;DATE</td>
					<td> : </td>
					<td align="right"><?php print $bi_date; ?></td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<table align="center" height="<?php print $page_height; ?>px" width="100%" border="1" cellspacing="0">
	<tr
		style="font-family:Arial; font-size:10pt; color:white; background-color:black; -webkit-print-color-adjust: exact;">
		<th width="60px" height="20px">QTY</th>
		<th>DESCRIPTION</th><?php if ($dn == 'no') {
			if ($sub_system == 0 && $systemid != 13) { ?>
				<th width="80px">TAG<br />PRICE</th>
				<th width="80px">DISCOUNT%</th>
				<th width="80px">Dis.<br />PRICE</th><?php } else { ?>
				<th width="80px">UNIT PRICE</th><?php } ?>
			<th width="60px">
				TOTAL <?php print $currency; ?></th><?php } else { ?>
			<th width="200px">REMARKS</th><?php } ?>
	</tr>
	<?php
	for ($i = 0; $i < sizeof($bill_id); $i++) {
		if ($bi_return_odr[$i] == 0) {
			print '<tr style="font-size:10pt" height="20px">
					<td style="border-bottom:0; border-top:0;" align="right">
						' . number_format($bi_qty[$i]) . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
					<td style="border-bottom:0; border-top:0;">&nbsp;&nbsp;&nbsp;&nbsp;' . $bi_desc[$i] . '</td>';
			if ($dn == 'no') {
				if ($sub_system == 0 && $systemid != 13)
					print '<td style="border-bottom:0; border-top:0;" align="right">
						' . number_format(($bi_price[$i] + $bi_discount[$i]), 2) . '&nbsp;&nbsp;</td>
					<td style="border-bottom:0; border-top:0;" align="right">
						' . number_format(($bi_discount[$i] / ($bi_price[$i] + $bi_discount[$i]) * 100), 2) . '%&nbsp;&nbsp;</td>';
				print '<td style="border-bottom:0; border-top:0;" align="right">' . number_format($bi_price[$i], 2) . '&nbsp;&nbsp;
					</td>
					<td align="right" style="border-bottom:0; border-top:0;">
						' . number_format(($bi_qty[$i] * $bi_price[$i]), 2) . '&nbsp;&nbsp;</td>';
			} else {
				print '<td style="border-bottom:0; border-top:0;"></td>';
			}
			print '
				</tr>';
		}
	}
	print '<tr style="font-size:10pt">
			<td style="border-bottom:0; border-top:0;"></td>
			<td style="border-bottom:0; border-top:0;"></td>';
	if ($dn == 'no') {
		if ($sub_system == 0 && $systemid != 13)
			print '<td style="border-bottom:0; border-top:0;"></td>
			<td width="50px" style="border-bottom:0; border-top:0;"></td>';
		print '<td align="right" style="border-bottom:0; border-top:0;"></td>
			<td align="right" style="border-bottom:0; border-top:0;"></td>';
	} else {
		print '<td style="border-bottom:0; border-top:0;"></td>';
	}
	print '
		</tr>';

	if ($dn == 'no') {
		if ($sub_system == 0 && $systemid != 13)
			$colspan = 5;
		else
			$colspan = 3;
		if ($systemid != 13) {
			print '<tr
				style="font-family:Calibri; font-size:11pt; font-weight:900; color:white; background-color:black; -webkit-print-color-adjust: exact;">
				<td colspan="' . $colspan . '" align="right" height="20px"
					style="padding-right:5px; border-right:1; border-bottom:1;">Total Amount</td>
				<td align="right">' . number_format($total - $bm_discount, 2) . '&nbsp;&nbsp;</td>
			</tr>';
			// Discount
			if ($bm_discount > 0) {
				print '<tr
				style="font-family:Calibri; font-size:11pt; font-weight:900; color:white; background-color:black; -webkit-print-color-adjust: exact;">
				<td colspan="' . $colspan . '" align="right" height="20px"
					style="padding-right:5px; border-right:1; border-bottom:1;">Discount</td>
				<td align="right">' . number_format($bm_discount, 2) . '&nbsp;&nbsp;</td>
			</tr>';
			}
			print '<tr
				style="font-family:Calibri; font-size:11pt; font-weight:900; color:white; background-color:black; -webkit-print-color-adjust: exact;">
				<td colspan="' . $colspan . '" align="right" height="20px"
					style="padding-right:5px; border-right:1; border-bottom:1; border-top:0; color:white;">' . $advance . ' Payment:
					Cash</td>
				<td align="right">' . number_format($cash_amount, 2) . '&nbsp;&nbsp;</td>
			</tr>';
			print '<tr
				style="font-family:Calibri; font-size:11pt; font-weight:900; color:white; background-color:black; -webkit-print-color-adjust: exact;">
				<td colspan="' . $colspan . '" align="right" height="20px"
					style="padding-right:5px; border-right:1; border-bottom:1; border-top:0; color:white;"> Payment: Bank
					Transfer</td>
				<td align="right">' . number_format($bank_amount, 2) . '&nbsp;&nbsp;</td>
			</tr>';
			print '<tr
				style="font-family:Calibri; font-size:11pt; font-weight:900; color:white; background-color:black; -webkit-print-color-adjust: exact;">
				<td colspan="' . $colspan . '" align="right" height="20px"
					style="padding-right:5px; border-right:1; border-bottom:1; border-top:0; color:white;">' . $chequedate . '<span
						style="padding-right:30px">' . $chq0_fullNo . '</span>' . $advance . ' Payment: Cheque</td>
				<td align="right">' . number_format($chque_amount, 2) . '&nbsp;&nbsp;</td>
			</tr>';
			print '<tr
				style="font-family:Calibri; font-size:11pt; font-weight:900; color:white; background-color:black; -webkit-print-color-adjust: exact;">
				<td colspan="' . $colspan . '" align="right" height="20px"
					style="padding-right:5px; border-right:1; border-bottom:0; border-top:0; color:white;">Remaining Balance
				</td>
				<td align="right">' . number_format(($total - $cash_amount - $chque_amount - $bm_discount), 2) . '&nbsp;&nbsp;</td>
			</tr>';
		} else {
			// Discount
			if ($bm_discount > 0) {
				print '<tr
								style="font-family:Calibri; font-size:11pt; font-weight:900; color:white; background-color:black; -webkit-print-color-adjust: exact;">
								<td colspan="' . $colspan . '" align="right" height="20px"
									style="padding-right:5px; border-right:1; border-bottom:1;">Discount</td>
								<td align="right">' . number_format($bm_discount, 2) . '&nbsp;&nbsp;</td>
							</tr>';
			}

			// total
			$sub_total = $total - $bm_discount;
			print '<tr
				style="font-family:Calibri; font-size:11pt; font-weight:900; color:white; background-color:black; -webkit-print-color-adjust: exact;">
				<td colspan="' . $colspan . '" align="right" height="20px"
					style="padding-right:5px; border-right:1; border-bottom:1;">Total</td>
				<td align="right">' . number_format($sub_total, 2) . '&nbsp;&nbsp;</td>
			</tr>';

			// grand total
			// print '<tr
			// 	style="font-family:Calibri; font-size:11pt; font-weight:900; color:white; background-color:black; -webkit-print-color-adjust: exact;">
			// 	<td colspan="' . $colspan . '" align="right" height="20px"
			// 		style="padding-right:5px; border-right:1; border-bottom:1;">Grand Total</td>
			// 	<td align="right">' . number_format($sub_total, 2) . '&nbsp;&nbsp;</td>
			// </tr>';
	
			// advance payments
			print '<tr
				style="font-family:Calibri; font-size:11pt; font-weight:900; color:white; background-color:black; -webkit-print-color-adjust: exact;">
				<td colspan="' . $colspan . '" align="right" height="20px"
					style="padding-right:5px; border-right:1; border-bottom:1; border-top:0; color:white;">Received Advance Payment
				</td>
				<td align="right">' . number_format(($cash_amount + $chque_amount + $bank_amount), 2) . '&nbsp;&nbsp;</td>
			</tr>';

			// due payment
			print '<tr
				style="font-family:Calibri; font-size:11pt; font-weight:900; color:white; background-color:black; -webkit-print-color-adjust: exact;">
				<td colspan="' . $colspan . '" align="right" height="20px"
					style="padding-right:5px; border-right:1; border-bottom:0; border-top:1; color:white;">Due Payment
				</td>
				<td align="right">' . number_format(($sub_total - $cash_amount - $bank_amount - $chque_amount), 2) . '&nbsp;&nbsp;</td>
			</tr>';
		}
	}
	?>
</table>

<br />

<?php if ($dn == 'no') {
	if ($bm_quotation_no != 0) { ?>
		<table cellspacing="0" style="font-size:11pt; font-family:Calibri">
			<?php if ($qm_warranty != '0')
				print '<tr><td style="vertical-align:top" width="120px"><strong>Warranty</strong></td><td>' . $qm_warranty . ' Months Warranty</td></tr>'; ?>
			<tr>
				<td colspan="2" height="3px"></td>
			</tr>
			<?php if ($qm_terms != '') { ?>
				<tr>
					<td style="vertical-align:top"><strong>Terms</strong></td>
					<td><?php print $qm_terms; ?></td>
				</tr>
				<tr>
					<td colspan="2" height="3px"></td>
				</tr>
			<?php } ?>
		</table>
	<?php }
} ?>

<table align="center" width="100%" border="0" cellspacing="0">
	<tr style="font-size:8pt;">
		<td>
			<?php if ($dn == 'yes') { ?>
				<table align="center" width="100%" style="font-family:Arial; font-size:9pt">
					<tr height="50px">
						<td width="100px">Delivered By </td>
						<td>: ................................</td>
						<td>Signature ................................</td>
						<td width="10px"></td>
						<td>Date ................................</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td style="font-family:Arial; font-size:9pt">
				<table align="center" width="100%" style="font-family:Arial; font-size:9pt">
					<tr height="35px">
						<td width="100px" valign="bottom">Contact Person</td>
						<td valign="bottom">: ..........................................................
							&nbsp;&nbsp;&nbsp;&nbsp;Contact Number :
							..........................................................</td>
					</tr>
					<tr height="50px">
						<td width="100px">Delivery Address</td>
						<td>:
							.......................................................................................................................................................
						</td>
					</tr>
				</table>
			<?php } ?>
		</td>
	</tr>
</table>

<?php if ($dn == 'yes') { ?>
	<table align="center" width="100%" style="font-family:Arial; font-size:9pt">
		<tr>
			<td>
				<br />
				<br />
				<strong>I hereby confirm that goods are received in good condition.</strong>
				<br />
				<br />
				<br />
				<br />
				<table align="center" width="100%" style="font-family:Arial; font-size:9pt">
					<tr>
						<td align="center">..................................................</td>
						<td align="center">..................................................</td>
						<td align="center">..................................................</td>
					</tr>
					<tr>
						<td align="center">Name</td>
						<td align="center">NIC</td>
						<td align="center">Signature and Rubber Stamp</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
<?php } ?>

<br />

<table align="center" width="100%" border="0" cellspacing="0" style="font-family:Arial; font-size:9pt">
	<tr>
		<td height="3px" bgcolor="black" style="-webkit-print-color-adjust: exact;"></td>
	</tr>
	<tr>
		<td align="center"><?php print $tm_company . ', ' . str_replace(",<br />", ", ", $tm_address); ?></td>
	</tr>
</table>