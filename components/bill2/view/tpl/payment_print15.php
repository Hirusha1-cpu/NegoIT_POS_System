<?php
    include_once  '../../modle/bill2Module.php';
    include_once  '../../../../template/common.php';
	generatePayment();
?>
<div id="print_top"></div>

<table width="100%">
	<tr>
		<td rowspan="2" style="font-family:Arial; font-size:11pt" width="230px">
			<span style="font-size:13pt"><strong><?php print $tm_company; ?></strong></span><br />
			<?php print $tm_address; ?><br />
			Tel: <?php print $tm_tel; ?>
		</td>
		<td></td>
		<td align="right">
			<span style="font-family:'Arial Black'; font-size:20pt">PAYMENT</span>
			<br/>
			<span style="font-size:12pt; font-family:Arial"></span>
			<br/>
		</td>
	</tr>
	<tr>
		<td></td>
		<td align="right">
			<table style="font-family:Arial; font-size:9pt">
				<tr>
					<td style="font-size:11pt">PAYMENT NO</td>
					<td style="font-size:11pt">: [<?php print  str_pad($payment_id, 7, "0", STR_PAD_LEFT); ?> ]</td>
				</tr>
				<tr>
					<td>PAYMENT DATE</td>
					<td>: <?php print $payment_date; ?></td>
				</tr>
				<tr>
					<td>PRINT DATE</td>
					<td>: <?php print substr($print_time,0,10); ?></td>
				</tr>
				<tr>
					<td></td><td></td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<table border="1" cellspacing="0" width="100%">
	<tr>
		<td>
			<table align="center" width="450px" cellspacing="0" border="0" >
				<tr height="350px">
					<td style="vertical-align:top">
						<br />
						<table align="center" width="350px" border="1" cellspacing="0" style="font-family:Arial; font-size:10pt">
							<?php if($invoice_no!=0)
								print '<tr>
										<td height="30px" style="padding-left:15px" width="200px">For Invoice</td>
										<td align="right" style="padding-right:15px">
											<strong>'.str_pad($invoice_no, 7, "0", STR_PAD_LEFT).'</strong>
										</td>
									</tr>';
							?>
							<tr>
								<td height="30px" style="padding-left:15px">Payment : <?php print $payment_type_n; ?></td>
								<?php if(($pay_pro_fee != '') && ($pay_pro_fee > 0)){?>
									<td align="right" style="padding-right:15px"><strong><?php print 'Rs. '.number_format((($pay_pro_fee / 100) * $amount) + $amount); ?></strong></td>
								<?php }else{ ?>
									<td align="right" style="padding-right:15px"><strong><?php print 'Rs. '.number_format($amount); ?></strong></td>
								<?php } ?>
							</tr>
						</table>
						<br />
						<?php if($payment_type==2){ ?>
						<table align="center" width="350px" border="1" cellspacing="0" >
							<tr style="font-family:Arial; font-size:10pt"><td colspan="2"><strong>Cheque Details</strong></td></tr>
							<tr style="font-family:Arial; font-size:10pt"><td width="150px" height="30px" style="padding-left:15px" >Bank</td><td align="right" style="padding-right:15px"><?php print $chque_bank; ?></td></tr>
							<tr style="font-family:Arial; font-size:10pt"><td height="30px" style="padding-left:15px" >Branch</td><td align="right" style="padding-right:15px"><?php print $chque_branch; ?></td></tr>
							<tr style="font-family:Arial; font-size:10pt"><td height="30px" style="padding-left:15px" >Cheque No</td><td align="right" style="padding-right:15px"><strong><?php print $chque_no; ?></strong></td></tr>
							<tr style="font-family:Arial; font-size:10pt"><td height="30px" style="padding-left:15px" >Cheque Date</td><td align="right" style="padding-right:15px"><?php print $chque_date; ?></td></tr>
						</table>
						<?php }else if($payment_type==3){ ?>
						<table align="center" width="350px" border="1" cellspacing="0" >
							<tr style="font-family:Arial; font-size:10pt"><td width="150px" height="30px" style="padding-left:15px" >Bank</td><td align="right" style="padding-right:15px"><?php print $bank_trans; ?></td></tr>
						</table>
						<?php }else if($payment_type==4){ ?>
						<table align="center" width="350px" border="1" cellspacing="0" >
							<tr style="font-family:Arial; font-size:10pt">
								<td width="150px" height="30px" style="padding-left:15px">Card Terminal</td>
								<td align="right" style="padding-right:15px"><?php print $bank_trans; ?></td>
							</tr>
							<tr style="font-family:Arial; font-size:10pt">
								<td width="150px" height="30px" style="padding-left:15px">Card No</td>
								<td align="right" style="padding-right:15px"><?php print $card_no; ?></td>
							</tr>
							<tr style="font-family:Arial; font-size:10pt">
								<td width="150px" height="30px" style="padding-left:15px">Payament Prossing Fee</td>
								<td align="right" style="padding-right:15px"><?php print $pay_pro_fee; ?>%</td>
							</tr>
						</table>
						<?php } ?>
						<!-- ---------------------------------------------------------------------------------------------------------------------- -->
						<?php
						if(($cust_st==1)&&($invoice_no!=0)){
							print '<br />';
							print '<table align="center" width="350px" border="1" cellspacing="0"style="font-family:Arial; font-size:10pt" >';
							print '<tr><td height="30px" style="padding-left:15px" colspan="2"><strong>Outstanding As of:</strong> '.substr($print_time,0,10).' </td></tr>';
							print '<tr ><td height="30px" style="padding-left:15px" width="200px" >This Invoice Outstanding </td><td align="right" style="padding-right:15px"><strong>Rs. '.number_format($invoice_oust).'</strong></td></tr>';
							print '<tr ><td height="30px" style="padding-left:15px" >Total Outstanding </td><td align="right" style="padding-right:15px"><strong>Rs. '.number_format($total_oust).'</strong></td></tr>';
							print '</table>';
						}
						?>
						<!-- ---------------------------------------------------------------------------------------------------------------------- -->
					</td>
				</tr>
				<tr>
					<td style="font-family:Arial; font-size:10pt"><?php if($comment!='')print '<hr />Comment: '.$comment; ?></td>
				</tr>
			</table>
			<br />
		</td>
	</tr>
	<tr>
		<td>
			<table align="center" width="100%" border="0" cellspacing="0" >
				<tr style="font-size:8pt;">
					<td>
						Make all checks payable to <strong><?php print $tm_company; ?></strong><br />
						If you have and questions concerning this invoice, please contact <?php print $tm_tel; ?> <br />
						<br/>
						<table align="center">
							<tr><td style="font-family:Arial; font-size:9pt">Issued By</td><td>  : <?php print ucfirst($salesman); ?></td><td width="45px"></td><td style="font-family:Arial; font-size:9pt">Paied By</td><td>  : <?php print ucfirst($cust_name); ?><td></tr>
							<tr><td style="font-family:Arial; font-size:9pt">Name</td><td>  ..............................</td><td></td><td style="font-family:Arial; font-size:9pt">Name</td><td>  ..............................</td></tr>
							<tr><td style="font-family:Arial; font-size:9pt">Signature</td><td>  ..............................</td><td></td><td style="font-family:Arial; font-size:9pt">Signature</td><td>  ..............................</td></tr>
							<tr><td style="font-family:Arial; font-size:9pt">Date</td><td>  ..............................</td><td></td><td style="font-family:Arial; font-size:9pt">Date</td><td>  ..............................</td></tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>