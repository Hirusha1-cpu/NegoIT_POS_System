<?php
include_once 'template/header.php';
?>

<div id="printheader" style="display:none">
	<h1 style="color:navy"><?php print $inf_company; ?></h1>
	<h2 align="center" style="color:#3333FF; text-decoration:underline">Unlocked Bills [All Stores]</h2>
</div>

<table align="center" style="font-family:Calibri; border-radius: 15px; padding-left:20px; padding-right:20px"
	bgcolor="#EEEEEE" width="600px">
	<tr>
		<td colspan="2" style="color:navy; font-size:14pt; font-weight:bold" align="center">List Of Unlocked Bills [All
			Stores]</td>
	</tr>
	<tr>
		<td width="20px" bgcolor="blue"></td>
		<td style="color:gray; font-size:11pt;"> Partially Locked Bills by "Customer Order"</td>
	</tr>
</table>
<br />
<table align="center">
	<tr>
		<td>
			<div style="background-color:#EEEEEF; border-radius: 15px; padding-left:10px; padding-right:10px">
				<br />
				<div id="print">
					<table align="center" bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0"
						style="font-size:10pt; font-family:Calibri">
						<tr>
							<th width="60px" class="shipmentTB3">#</th>
							<th width="100px" class="shipmentTB3">Invoice No</th>
							<th width="100px" class="shipmentTB3">Date</th>
							<th width="100px" class="shipmentTB3">Time</th>
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
									<td  class="shipmentTB3"align="center">
										<a title="' . $comment . '" href="index.php?components=billing&action=finish_bill&id=' . $invoice_no[$i] . '" >' . str_pad($invoice_no[$i], 7, "0", STR_PAD_LEFT) . '</a>
									</td>
									<td  class="shipmentTB3"align="center">' . $date[$i] . '</td>
									<td  class="shipmentTB3"align="center">' . $time[$i] . '</td>
									<td class="shipmentTB3">&nbsp;&nbsp;' . ucfirst($billed_by[$i]) . '&nbsp;&nbsp;</td>
									<td class="shipmentTB3">&nbsp;&nbsp;' . $billed_cust[$i] . '&nbsp;&nbsp;</td>
									<td class="shipmentTB3">&nbsp;&nbsp;' . $bill_store[$i] . '&nbsp;&nbsp;</td>
								</tr>';
						} ?>
					</table>
				</div>
				<br />
			</div>
		</td>
	</tr>
</table>
<table align="center">
	<tr>
		<td align="center">
			<div style="background-color:#6699FF; border:medium; border-color:black; width:80px; border-radius: 15px;">
				<a class="shortcut-button" onclick="printdiv('print','printheader')" href="#"><span
						style="text-decoration:none; font-family:Arial; color:navy;">
						<img src="images/print.png" alt="icon" /><br />
						Print
					</span></a>
			</div>
		</td>
	</tr>
</table>
<br />
<?php
include_once 'template/footer.php';
?>