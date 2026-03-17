<?php
	include_once  '../../modle/bill2Module.php';
	include_once  '../../../../template/common.php';
	generatePayment();
?>
<div id="print_top"></div>
<table border="0" cellspacing="0">
	<tr>
		<td>
		  	<table width="90%" align="center" style="font-family:Arial, Helvetica, sans-serif">
		  		<tr>
					<td><span style="font-family:'Arial'; font-size:20pt">PAYMENT</span></td>
				</tr>
				<tr>
					<td style="font-size:8pt">
						<?php
							if($tm_company!='OMS'){
								print '<strong>'.$tm_company.'</strong>';
							}else{
								print '<span style="font-size:20pt; font-weight:bold">&#937;MS</span><br />';
								print '<span style="font-size:4pt">Zigo Mobile (pvt) Ltd</span>';
							}
						?>
						<br />
						<?php print $tm_address; ?><br />
						Tel: <?php print $tm_tel; ?>
					</td>
				</tr>
				<tr><td height="10px"></td></tr>
				<tr>
					<td style="font-size:8pt">
						PAYMENT # [<?php print  str_pad($payment_id, 7, "0", STR_PAD_LEFT); ?>]<br />
						<span  style="font-family:Arial; font-size:8pt">DATE: <?php print $payment_date; ?><br /><br /></span>
					</td>
				</tr>
				<tr><td height="8px"></td></tr>
		  	</table>

			<hr />

			<table align="center" width="180px" border="0" cellspacing="0" style="font-size:8pt; font-family:Arial, Helvetica, sans-serif" >
				<?php if($invoice_no!=0)
				print '<tr><td height="30px">For Invoice </td><td width="5px">:</td><td align="right" style="padding-right:15px"><strong>'.str_pad($invoice_no, 7, "0", STR_PAD_LEFT).'</strong></td></tr>';
				?>
				<tr>
					<td height="30px">PAID AMOUNT</td>
					<td width="5px">:</td>
					<td align="right" style="padding-right:15px"><strong><?php print 'Rs. '.number_format($amount); ?></strong></td>
				</tr>
				<tr>
					<td height="30px">Payment Type</td>
					<td width="5px">:</td>
					<td align="right" style="padding-right:15px"><?php print $payment_type_n; ?></td>
				</tr>
				<?php if(isCustomerTotalOutstandingShowInBill(2)) { ?>
				<tr>
					<td height="30px">Total Outstanding Amount</td>
					<td width="5px">:</td>
					<td align="right" style="padding-right:15px"><?php print $credit_balance; ?></td>
				</tr>
				<?php } ?>
				<?php if($payment_type==2){ ?>
					<tr><td colspan="3"><br></td></tr>
					<tr><td colspan="3"><strong>Cheque Details</strong></td></tr>
					<tr><td height="30px">Bank</td><td width="5px">:</td><td align="right" style="padding-right:15px"><?php print $chque_bank; ?></td></tr>
					<tr><td height="30px">Branch</td><td width="5px">:</td><td align="right" style="padding-right:15px"><?php print $chque_branch; ?></td></tr>
					<tr><td height="30px">Cheque No</td><td width="5px">:</td><td align="right" style="padding-right:15px"><?php print $chque_no; ?></td></tr>
					<tr><td height="30px">Cheque Date</td><td width="5px">:</td><td align="right" style="padding-right:15px"><?php print $chque_date; ?></td></tr>
				<?php }else if($payment_type==3){ ?>
					<tr>
						<td height="30px">Bank</td>
						<td width="5px">:</td>
						<td align="right" style="padding-right:15px"><?php print $bank_trans; ?></td>
					</tr>
				<?php } ?>
			</table>

			<br />

			<table align="center" width="210px" border="0" cellspacing="0" style="font-size:8pt; font-family:Arial, Helvetica, sans-serif" >
				<tr><td colspan="2"><?php if($comment!='')print 'Comment: '.$comment; ?></td></tr>
				<tr><td colspan="2"><hr></td></tr>
				<tr><td width="60px">Salesman :</td><td><?php print ucfirst($salesman); ?></td></tr>
				<tr><td height="30px">Signature :</td><td>...............................................</td></tr>
				<tr><td>Customer :</td><td><?php print ucfirst($cust_name); ?></td></tr>
				<tr><td height="30px">Signature :</td><td>...............................................</td></tr>
				<tr><td height="30px">Name :</td><td>...............................................</td></tr>
				<tr><td colspan="2"><hr></td></tr>
				<tr><td colspan="2" align="center">IT WAS A PLEASURE TO SERVE YOU<br>THANK YOU</td></tr>
				<tr><td colspan="2"><br></td></tr>
			</table>
			<br />