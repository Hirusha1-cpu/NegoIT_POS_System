<?php
    include_once  '../../modle/bill2Module.php';
    include_once  '../../../../template/common.php';
    generateRtnInvoice();
    generalPrint();
    $systemid=inf_systemid(2);
	$sub_system=$_COOKIE['sub_system'];
	$paper_size=paper_size(2);
	$district_name = getDistrictName(2);
    $currency = getCurrency(2);
	$logo = getStoreLogo(2);

	if(($district_name == 'Dubai') || ($district_name == 'Abu Dhabi')){
		$district_name = 'Dubai';
	}else{
		$district_name = 'Sharjah';
	}
?>

	<!-- --------------------------------------------   HEADER SECTION  -------------------------------------------- -->
	<table width="100%" border="0" style="font-family:Calibri; font-size:12pt;">
		<tr>
			<td colspan="3" style="vertical-align:top">
				<img src="../../../../images/cplogo<?php print $logo; ?>.png" height="33px" />
			</td>
		</tr>
		<tr>
			<td>
				<table style="font-family:Calibri; font-size:12pt;" cellspacing="0">
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
				<span style="font-family:'Arial Black'; font-size:20pt">RETURN INVOICE</span>
			</td>
		</tr>
		<tr>
			<td>
				<?php print 'Customer: ' .ucfirst($bill_cust); ?>
                <br />
				<?php print 'Address: ' .wordwrap($bill_cust_address, 50, "<br>"); ?>
			</td>
			<td align="right" colspan="2" style="font-family:Calibri; font-size:11pt">
				<table cellspacing="0" border="0">
					<tr>
						<td width="50px"></td>
						<td>INVOICE</td>
						<td> : </td>
						<td align="right"><?php print  str_pad($_GET['id'], 7, "0", STR_PAD_LEFT); ?></td>
					</tr>
					<tr>
						<td width="50px"></td>
						<td>Salesman</td>
						<td> : </td>
						<td align="right"><?php print ucfirst($bill_salesman); ?></td>
					</tr>
					<tr>
						<td width="50px"></td>
						<td>Place of Supply </td>
						<td> : </td>
						<td align="right"><?php print ucfirst($district_name); ?></td>
					</tr>
					<tr>
						<td colspan="2">TIME: <?php print substr($bill_time,0,5); ?> &nbsp;&nbsp;&nbsp;&nbsp;DATE</td>
						<td> : </td>
						<td align="right"><?php print $bill_date; ?></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<!-- --------------------------------------------   END HEADER SECTION  -------------------------------------------- -->

    <!-- --------------------------------------------   BODY SECTION  -------------------------------------------- -->
    <table align="center" width="100%" border="0" cellspacing="0">
		<table  align="center" width="100%" style="font-family:Arial; font-size:10pt; color:black; background-color:white; -webkit-print-color-adjust: exact;" border="0">
			<tr>
                <td colspan="2"><hr></td>
            </tr>
			<tr style="font-family:Arial; font-size:8pt;">
                <td align="left">ITEM</td>
                <td align="right">QTY</td>
            </tr>
			<tr><td colspan="2"><hr></td></tr>
            <tr><td colspan="5" style="padding-top:10px;"></td></tr>
            <?php
                for($i=0;$i<sizeof($bill_id);$i++){
                    print '<tr height="30px"><td align="left">'.$bill_item[$i].'</td><td align="right">'.$bill_qty[$i].'&nbsp;&nbsp;</td></tr>';
                    print '<tr><td height="10px"></td><td></td></tr>';
                }
            ?>
		</table>
		<br />
		<table align="center" width="100%"  border="0" cellspacing="0" style="font-size:8pt; font-family:Arial, Helvetica, sans-serif">
			<tr><td colspan="5"><hr></td></tr>
			<tr><td colspan="5" style="padding-top:10px;"></td></tr>
			<tr>
				<td height="30px">Salesman Name</td>
				<td height="30px">............................................................</td>
				<td height="30px" width="10px"></td>
				<td height="30px">Customer Name</td>
				<td height="30px">............................................................</td>
			</tr>
			<tr>
				<td>Salesman Signature</td>
				<td>............................................................</td>
				<td height="30px" width="10px"></td>
				<td>Customer Signature</td>
				<td>............................................................</td>
			</tr>
			<tr>
				<td>Date</td>
				<td>............................................................</td>
				<td height="30px" width="10px"></td>
				<td>Date</td>
				<td>............................................................</td>
			</tr>
			<tr><td colspan="5" style="padding-top:10px;"></td></tr>
			<tr><td colspan="5"><hr></td></tr>
			<tr><td colspan="5" style="padding-top:10px;"></td></tr>
			<tr>
				<td colspan="5" align="center">Note: By Signing this, Customer confirms that he/she received replacement items for <br>above listed <br><br>THANK YOU</td>
			</tr>
		</table>
	</table>

	<!-- --------------------------------------------   COMPANY FOOTER SECTION  -------------------------------------------- -->
	<table align="center" width="100%" border="0" cellspacing="0"  style="font-family:Arial; font-size:9pt; position: absolute;bottom: 0;">
		<tr>
			<td height="3px" bgcolor="black" style="-webkit-print-color-adjust: exact;"></td>
		</tr>
		<tr>
			<td align="center"><?php print str_replace("<br />",", ",$tm_address); ?></td>
		</tr>
	</table>
	<!-- --------------------------------------------   END COMPANY FOOTER SECTION  -------------------------------------------- -->

