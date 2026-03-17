	<!-- ---------------------------------------------------------------------Show Pay-------------------------------------------------------------------- -->
  	<table width="90%">
	<tr><td bgcolor="#467898" style="font-family:Calibri; color:white; padding-left:10px">Payment Invoice Status : <span <?php if($status_out=='Deleted') print 'class="blink"'; ?> style="color:<?php print $status_color; ?>;"><strong><?php print $status_out; ?></strong></span></td><td></td></tr>
  	<tr><td style="vertical-align:top;">
		  <table border="1" cellspacing="0" align="center"><tr><td>
		  <table width="90%" align="center">
		  <tr><td><span style="font-family:'Arial Black'; font-size:20pt">PAYMENT</span></td></tr>
		  <tr><td><?php print $tm_company; ?><br />
			<?php print $tm_address; ?><br />
			Tel: <?php print $tm_tel; ?>
			</td></tr>
			<tr><td height="10px"></td></tr>
			<tr><td>
			PAYMENT # [<?php print  str_pad($payment_id, 7, "0", STR_PAD_LEFT); ?> ]<br />
			<span  style="font-family:Arial; font-size:11pt">
			DATE: <?php print $payment_date; ?><br /><br />
			</span>
			</td></tr>
			<tr><td height="10px"></td></tr>
		  </table>
		
			<table align="center" width="300px" border="0" cellspacing="0" >
			<tr><td colspan="2">------------------------------------------------------------------------</td></tr>
			<?php if($invoice_no!=0)
			print '<tr><td>&nbsp;&nbsp;For Invoice</td><td>'.str_pad($invoice_no, 7, "0", STR_PAD_LEFT).'</td></tr>';
			?>
			<tr><td>&nbsp;&nbsp;Amount</td><td><?php print 'Rs. '.number_format($amount); ?></td></tr>
			<tr><td colspan="2"><br /><br /></td></tr>
	<?php if($payment_type==2){ ?>
			<tr><td>&nbsp;&nbsp;Bank</td><td><?php print $chque_bank; ?></td></tr>
			<tr><td>&nbsp;&nbsp;Branch</td><td><?php print $chque_branch; ?></td></tr>
			<tr><td>&nbsp;&nbsp;Chque No</td><td><?php print $chque_no; ?></td></tr>
			<tr><td>&nbsp;&nbsp;Chque Date</td><td><?php print $chque_date; ?></td></tr>
	<?php } ?>
			<tr><td colspan="2">------------------------------------------------------------------------</td></tr>
			</table>
			<table width="300px">
			<tr><td width="80px">&nbsp;&nbsp;Salesman</td><td><?php print ucfirst($salesman); ?></td></tr>
			<tr><td>&nbsp;&nbsp;Signature</td><td></td></tr>
			<tr><td>&nbsp;&nbsp;Paied By</td><td ><?php print ucfirst($cust_name); ?></td></tr>
			<tr><td>&nbsp;&nbsp;Signature</td><td></td></tr>
			<tr><td colspan="4">------------------------------------------------------------------------</td></tr>
			</table>
			<br />
		</td></tr></table>
</td><td style="vertical-align:top" align="right">
		<table align="right"><tr><td><br />
<?php if($py_status==0){ ?>
			<div style="background-color:#FF9191; border:medium; border-color:black; width:80px; text-align:center">
				<a class="shortcut-button" onclick="deletePayment(<?php print $_GET['id']; ?>)" href="#"><span style="text-decoration:none; font-family:Arial; color:navy;">
					<img src="../images/cancel.png" alt="icon" /><br />
					Cancel
				</span></a>
			</div>
<?php } ?>
		</td></tr></table>
  	</td></tr></table>