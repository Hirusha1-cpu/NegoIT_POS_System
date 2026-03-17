<?php
    include_once  '../../modle/billingModule.php';
    include_once  '../../../../template/common.php';
    generatePayment();
    generalPrint();
    $systemid=inf_systemid(2);
	$sub_system=$_COOKIE['sub_system'];
	$paper_size=paper_size(2);
	$district_name = getDistrictName(2);
    $currency = getCurrency(2);

	if(($district_name == 'Dubai') || ($district_name == 'Abu Dhabi')){
		$district_name = 'Dubai';
	}else{
		$district_name = 'Sharjah';
	}

    if($paper_size=='A4'){
		$page_height=750;
	}
    if($paper_size=='A5'){
		$page_height=480;
	}
?>
    <div id="print_top"></div>
	<!-- --------------------------------------------   HEADER SECTION  -------------------------------------------- -->
	<table width="100%" border="0" style="font-family:Calibri; font-size:12pt;">
		<tr>
			<td colspan="3" style="vertical-align:top">
				<img src="../../../../images/cplogo<?php print $systemid; ?>.png" height="33px" />
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
				<span style="font-family:'Arial Black'; font-size:20pt">PAYMENT</span>
			</td>
		</tr>
		<tr>
			<td>
				<?php print 'Customer: '. ucfirst($cust_name); ?>
                <br />
				<?php print 'Address: '. wordwrap($payment_cust_address, 50, "<br>"); ?>
			</td>
			<td align="right" colspan="2" style="font-family:Calibri; font-size:11pt">
				<table cellspacing="0" border="0">
					<tr>
						<td width="50px"></td>
						<td>PAYMENT</td>
						<td> : </td>
						<td align="right"><?php print  str_pad($_GET['id'], 7, "0", STR_PAD_LEFT); ?></td>
					</tr>
					<tr>
						<td width="50px"></td>
						<td>Salesman</td>
						<td> : </td>
						<td align="right"><?php print ucfirst($salesman); ?></td>
					</tr>
					<tr>
						<td width="50px"></td>
						<td>Place of Supply </td>
						<td> : </td>
						<td align="right"><?php print ucfirst($district_name); ?></td>
					</tr>
					<tr>
						<td colspan="2">TIME: <?php print substr($payment_time,0,5); ?> &nbsp;&nbsp;&nbsp;&nbsp;DATE</td>
						<td> : </td>
						<td align="right"><?php print $payment_date; ?></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<!-- --------------------------------------------   END HEADER SECTION  -------------------------------------------- -->

    <!-- --------------------------------------------   BODY SECTION  -------------------------------------------- -->
    <table align="center" height="<?php print $page_height; ?>px" width="100%" border="1" cellspacing="0">

        <table align="center" width="100%" style="font-family:Arial; font-size:10pt; color:black; background-color:white; -webkit-print-color-adjust: exact;">
            <tr><td colspan="2" style="padding-top:10px;"></td></tr>
            <?php if($invoice_no!=0)
                print '<tr>
                        <td height="30px" style="padding-left:200px">For Invoice </td>
                        <td width="5px">:</td>
                        <td align="right" style="padding-right:200px"><strong>'.str_pad($invoice_no, 7, "0", STR_PAD_LEFT).'</strong>
                        </td>
                    </tr>';
            ?>
            <tr>
                <td height="30px" style="padding-left:200px">Payment Type</td>
                <td width="5px">:</td>
                <td align="right" style="padding-right:200px"><strong><?php print $payment_type_n; ?></strong></td>
            </tr>
            <tr>
                <td height="30px" style="padding-left:200px">AMOUNT</td>
                <td width="5px">:</td>
                <td align="right" style="padding-right:200px"><strong><?php print $currency.' '.number_format($amount,2); ?></strong></td>
            </tr>
            <?php if($payment_type==2){ ?>
                <tr><td colspan="3"><br></td></tr>
                <tr><td colspan="3" style="padding-left:200px;"><strong>Cheque Details</strong></td></tr>
                <tr><td height="30px" style="padding-left:200px;">Bank</td><td width="5px">:</td><td align="right" style="padding-right:200px"><?php print $chque_bank; ?></td></tr>
                <tr><td height="30px" style="padding-left:200px;">Branch</td><td width="5px">:</td><td align="right" style="padding-right:200px"><?php print $chque_branch; ?></td></tr>
                <tr><td height="30px" style="padding-left:200px;">Cheque No</td><td width="5px">:</td><td align="right" style="padding-right:200px"><?php print $chque_no; ?></td></tr>
                <tr><td height="30px" style="padding-left:200px;">Cheque Date</td><td width="5px">:</td><td align="right" style="padding-right:200px"><?php print $chque_date; ?></td></tr>
            <?php }else if($payment_type==3){ ?>
                <tr>
                    <td height="30px" style="padding-left:200px;">Bank</td>
                    <td width="5px">:</td>
                    <td align="right" style="padding-right:200px;"><strong><?php print $bank_trans; ?></strong></td>
                </tr>
            <?php } ?>
        </table>
	    <br />
        <table align="center" width="100%" border="0" cellspacing="0" style="font-size:10pt; font-family:Arial, Helvetica, sans-serif">
            <tr><td colspan="5"><?php if($comment!='')print 'Comment: '.$comment; ?></td></tr>
            <tr><td colspan="5"><hr></td></tr>
            <tr><td colspan="2" style="padding-top:10px;"></td></tr>
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
            <tr><td colspan="5" align="center">IT WAS A PLEASURE TO SERVE YOU<br>THANK YOU</td></tr>
            <tr><td colspan="5"><br></td></tr>
        </table>
    <!-- --------------------------------------------   END BODY SECTION  -------------------------------------------- -->

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
    </table>