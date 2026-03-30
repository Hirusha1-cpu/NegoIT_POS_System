	<!-- ---------------------------------------------------------------------Show Bill-------------------------------------------------------------------- -->
  	<table width="90%">
	<tr><td bgcolor="#467898" style="font-family:Calibri; color:white; padding-left:10px">Invoice Status : <span <?php if($status_out=='Deleted') print 'class="blink"'; ?> style="color:<?php print $status_color; ?>;"><strong><?php print $status_out; ?></strong></span></td><td></td></tr>
  	<tr><td style="vertical-align:top;">
		  <table border="1" cellspacing="0" align="center"><tr><td>
		  <table width="90%" align="center">
		  <tr><td><span style="font-family:'Arial Black'; font-size:20pt"><?php print $bill_title; ?></span></td></tr>
		  <tr><td><?php print $tm_company; ?><br />
			<?php print $tm_address; ?><br />
			Tel: <?php print $tm_tel; ?>
			</td></tr>
			<tr><td height="10px"></td></tr>
			<tr><td>
			INVOICE # [<?php print  str_pad($_REQUEST['id'], 7, "0", STR_PAD_LEFT); ?> ]<br />
			<span  style="font-family:Arial; font-size:11pt">
			DATE: <?php print $bi_date; ?><br /><br />
			</span>
			</td></tr>
			<tr><td height="10px"></td></tr>
		  </table>

			<table align="center" width="300px" border="0" cellspacing="0" >
			<tr><td colspan="4">------------------------------------------------------------------------</td></tr>
			<tr style="font-family:Arial; font-size:10pt"><th>DESCRIPTION</th><th>UNIT<br />PRICE</th><th>QTY</th><th>TOTAL</th></tr>
			<tr><td colspan="4">------------------------------------------------------------------------</td></tr>
		<?php
			for($i=0;$i<sizeof($bill_id);$i++){
				print '<tr style="font-size:10pt" height="20px"><td style="border-bottom:0; border-top:0; padding-left:5px; padding-right:5px">'.$bi_desc[$i].'</td><td align="right" style="border-bottom:0; border-top:0; ">'.number_format($bi_price[$i]+$bi_discount[$i]).'&nbsp;&nbsp;</td><td width="25px" style="border-bottom:0; border-top:0;" align="right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.number_format($bi_qty[$i]).'</td><td align="right" style="border-bottom:0; border-top:0;">'.number_format($bi_qty[$i]*$bi_price[$i]).'&nbsp;&nbsp;</td></tr>';
				if($bi_discount[$i]!=0)
				print '<tr style="font-size:10pt" height="20px"><td style="border-bottom:0; border-top:0; padding-left:5px; padding-right:5px">Discount: '.number_format($bi_discount[$i]/($bi_price[$i]+$bi_discount[$i])*100).'%</td><td align="right">'.number_format($bi_price[$i]).'&nbsp;&nbsp;</td><td></td><td></td></tr>';
				print '<tr style="font-size:10pt" height="20px"><td style="border-bottom:0; border-top:0; padding-left:5px; padding-right:5px" colspan="4"></td></tr>';
			}
				print '	<tr><td colspan="4">------------------------------------------------------------------------</td></tr>';
				print '<tr style="font-size:10pt; font-weight:900;"><td colspan="3" align="right" height="20px" style="padding-right:5px; border-right:1; border-bottom:0; ">Total Amount</td><td align="right">'.number_format($total).'&nbsp;&nbsp;</td></tr>';
				print '<tr style="font-size:10pt; font-weight:900;"><td colspan="3" align="right" height="20px" style="padding-right:5px; border-right:1; border-bottom:0; border-top:0;">'.$advance.' Payment: Cash</td><td align="right">'.number_format($cash_amount).'&nbsp;&nbsp;</td></tr>';
				print '<tr style="font-size:10pt; font-weight:900;"><td colspan="3" align="right" height="20px" style="padding-right:5px; border-right:1; border-bottom:0; border-top:0;">'.$advance.' Payment: Chque</td><td align="right">'.number_format($chque_amount).'&nbsp;&nbsp;</td></tr>';
				print '<tr style="font-size:10pt; font-weight:900;"><td colspan="3" align="right" height="20px" style="padding-right:5px; border-right:1; border-bottom:0; border-top:0;">Remaining Balance</td><td align="right">'.number_format($total-$cash_amount-$chque_amount).'&nbsp;&nbsp;</td></tr>';
				print '	<tr><td colspan="4">------------------------------------------------------------------------</td></tr>';
				if($chq0_fullNo!='')
				print '	<tr><td colspan="4" align="center"><span style="padding-right:30px">'.$chq0_fullNo.'</span></td></tr>';
				print '	<tr><td colspan="4">------------------------------------------------------------------------</td></tr>';
				print '	<tr><td colspan="4">Salesman : '.ucfirst($up_salesman).'</td></tr>';
				print '	<tr><td colspan="4"><a href="index.php?components=billing&action=cust_details&id='.$cu_id.'&action2=finish_bill&id2='.$_GET['id'].'" title="'.$cu_details.'" >'.ucfirst($bi_cust).'</a>'.'</td></tr>';
				print '	<tr><td colspan="4">&nbsp;</td></tr>';
				print '	<tr><td colspan="4">Signature : _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ </td></tr>';
				print '	<tr><td colspan="4">------------------------------------------------------------------------</td></tr>';
				print '	<tr><td colspan="4" align="center">IT WAS A PLEASURE TO SERVE YOU</td></tr>';
				print '	<tr><td colspan="4" align="center">THANK YOU</td></tr>';
		?>
			</table>
			<br />
		</td></tr></table>
</td><td style="vertical-align:top" align="right">
		<table align="right"><tr><td><br />
	<?php if($bm_status!=0){
			if($bm_module==1){
		?>
			<div style="background-color:#FF9191; border:medium; border-color:black; width:80px; text-align:center">
				<a class="shortcut-button" onclick="deleteBill('<?php print $_GET['id']; ?>')" href="#"><span style="text-decoration:none; font-family:Arial; color:navy;">
					<img src="../images/cancel.png" alt="icon" /><br />
					Cancel Bill
				</span></a>
			</div>
		<?php
		}
		if($bm_module==2){
		?>
			<div style="background-color:#FF9191; border:medium; border-color:black; width:80px; text-align:center">
				<a class="shortcut-button" onclick="deleteBill2('<?php print $_GET['id']; ?>')" href="#"><span style="text-decoration:none; font-family:Arial; color:navy;">
					<img src="../images/cancel.png" alt="icon" /><br />
					Cancel <strong>Bill2 </strong>
				</span></a>
			</div>
	<?php
		}
	 }
	 ?>
		</td></tr></table>
  	</td></tr></table>