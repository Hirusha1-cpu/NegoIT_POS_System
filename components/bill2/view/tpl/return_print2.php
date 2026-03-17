<?php
                include_once  '../../modle/bill2Module.php';
                include_once  '../../../../template/common.php';
				generateRtnInvoice();
?>
		  <table border="0" cellspacing="0" ><tr><td>
		  <table width="90%" align="center" style="font-family:Arial, Helvetica, sans-serif">
		  <tr><td><span style="font-family:'Arial'; font-size:20pt">RETURN INVOICE</span></td></tr>
		  <tr><td style="font-size:8pt"><strong><?php print $tm_company; ?></strong>.<br />
			<?php print $tm_address; ?><br />
			Tel: <?php print $tm_tel; ?>
			</td></tr>
			<tr><td height="10px"></td></tr>
			<tr><td style="font-size:8pt">
			INVOICE # [<?php print  str_pad($_REQUEST['id'], 7, "0", STR_PAD_LEFT); ?> ]<br />
			<span  style="font-family:Arial; font-size:8pt">
			DATE: <?php print $bill_date; ?>
			</span>
			</td></tr>
		  </table>
		  
			<table align="center" width="210px" border="0" cellspacing="0" style="font-size:8pt; font-family:Arial, Helvetica, sans-serif" >
			<tr><td colspan="2"><hr></td></tr>
			<tr style="font-family:Arial; font-size:8pt; text-align:center"><td>ITEM</td><td>QTY</td></tr>
			<tr><td colspan="2"><hr></td></tr>
<?php
	for($i=0;$i<sizeof($bill_id);$i++){
		print '<tr height="30px"><td align="left">'.$bill_item[$i].'</td><td align="right">'.$bill_qty[$i].'&nbsp;&nbsp;</td></tr>';
		print '<tr><td height="10px"></td><td></td></tr>';
	}
?>
			</table>
	<br />
			<table align="center" width="210px" border="0" cellspacing="0" style="font-size:8pt; font-family:Arial, Helvetica, sans-serif" >
			<tr><td colspan="2"><hr></td></tr>
			<tr><td width="60px">Salesman :</td><td><?php print ucfirst($bill_salesman); ?></td></tr>
			<tr><td height="30px">Signature :</td><td>...............................................</td></tr>			
			<tr><td>Customer :</td><td><?php print ucfirst($bill_cust); ?></td></tr>
			<tr><td height="30px">Signature :</td><td>...............................................</td></tr>
			<tr><td height="30px">Name :</td><td>...............................................</td></tr>
			<tr><td colspan="2"><hr></td></tr>
			<tr><td colspan="2" align="center">Note: By Signing this, Customer confirms<br>that he/she received replacement items for <br>above listed <br><br>THANK YOU</td></tr>
			<tr><td colspan="2"><br></td></tr>
			</table>
<br>