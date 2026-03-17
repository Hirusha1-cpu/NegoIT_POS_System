<div id="print">
	<table align="center" bgcolor="#E5E5E5" border="1" cellspacing="0" style="font-family:Calibri">
		<tr>
			<th width="50px">#</th>
			<th>Item</th>
			<th width="100px">Old Qty</th>
			<th width="100px">Added Qty</th>
			<th class="shipmentTB3">Unit Price</th>
			<th>Total</th>
		</tr>
		<?php
		$subtotal = 0;
		for ($i = 0; $i < sizeof($ins_id); $i++) {
			if ($itm_unic[$i] == 1)
				$url = 'index.php?components=' . $components . '&action=show_unic&shipment_no=' . $_GET['shipment_no'] . '&ins_id=' . $ins_id[$i];
			else
				$url = '';
			$totalcost = 0;
			if ($unicCal) {
				$unitcost = '-';
				$totalcost = $ins_cost_total[$i];
			} else {
				$unitcost = number_format($ins_cost[$i], $decimal);
				$totalcost = $ins_cost[$i] * $ins_added_qty[$i];
			}
			$subtotal += $totalcost;
			$totalcost = number_format($totalcost, $decimal);
			print '<tr>
					<td align="center">
						<span>' . sprintf('%02d', ($i + 1)) . '</span>
					</td>
					<td style="padding-left:10px; padding-right:10px"><a style="text-decoration:none;" href="' . $url . '">' . $ins_item[$i] . '</a></td>
					<td width="50px" align="center">' . $ins_old_qty[$i] . '</td>
					<td width="50px" align="center">' . $ins_added_qty[$i] . '</td>';
			if ($systemid == 26) {
				print '<td align="right" class="shipmentTB3">' . number_format($ins_cost[$i], $decimal) . '</td>';
			} else {
				print '<td align="right" class="shipmentTB3">' . $unitcost . '</td>';
			}
			print '<td align="right" class="shipmentTB3">' . $totalcost . '</td>
				</tr>';
		}
		print '<tr>
				<td colspan="5" class="shipmentTB3"><strong>SUB TOTAL</strong></td>
				<td align="right" class="shipmentTB3"><strong>' . number_format($subtotal, $decimal) . '</strong></td>
			</tr>';
		?>
	</table>
</div>