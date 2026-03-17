<?php
include_once 'template/m_header.php';
?>

<div class="w3-container" style="margin-top:75px">
	<hr>
	<div class="w3-row">
		<div class="w3-col s3">
		</div>
		<div class="w3-col">
			<table style="font-family:Calibri;">
				<tr>
					<td colspan="2" style="color:navy; font-size:14pt; font-weight:bold">List Of Unlocked Bills [All
						Stores]</td>
				</tr>
				<tr>
					<td width="20px" bgcolor="blue"></td>
					<td style="color:gray; font-size:11pt;"> Partially Locked Bills by "Customer Order"</td>
				</tr>
			</table>
			<table align="center" bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0"
				style="font-size:10pt; font-family:Calibri">
				<tr>
					<th class="shipmentTB3" width="60px">#</th>
					<th class="shipmentTB3" width="100px">Invoice No</th>
					<th class="shipmentTB3" width="100px">Date</th>
					<th class="shipmentTB3" width="100px">Time</th>
					<th class="shipmentTB3">&nbsp;&nbsp;Salesman&nbsp;&nbsp;</th>
					<th class="shipmentTB3">&nbsp;&nbsp;Customer&nbsp;&nbsp;</th>
					<th class="shipmentTB3">&nbsp;&nbsp;Store&nbsp;&nbsp;</th>
				</tr>
				<?php for ($i = 0; $i < sizeof($invoice_no); $i++) {
					if ($lock[$i] == 2) {
						$color = 'blue';
						$comment = 'Un-Packed Cust Order';
					} else {
						$color = 'black';
						$comment = '';
					}
					print '<tr style="color:' . $color . '">
								<td class="shipmentTB3" align="center">'.sprintf('%02d',($i+1)).'</td>
								<td  class="shipmentTB3" align="center">
									<a title="' . $comment . '" href="index.php?components=billing&action=finish_bill&id=' . $invoice_no[$i] . '" >' . str_pad($invoice_no[$i], 7, "0", STR_PAD_LEFT) . '</a>
								</td>
								<td  class="shipmentTB3" align="center">' . $date[$i] . '</td>
								<td  class="shipmentTB3" align="center">' . $time[$i] . '</td>
								<td class="shipmentTB3">&nbsp;&nbsp;' . ucfirst($billed_by[$i]) . '&nbsp;&nbsp;</td>
								<td class="shipmentTB3">&nbsp;&nbsp;' . $billed_cust[$i] . '&nbsp;&nbsp;</td>
								<td class="shipmentTB3">&nbsp;&nbsp;' . $bill_store[$i] . '&nbsp;&nbsp;</td>
							</tr>';
				} ?>
			</table>

		</div>
	</div>
</div>
<hr>
<br />
<?php
include_once 'template/m_footer.php';
?>