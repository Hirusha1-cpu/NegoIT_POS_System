<?php
	include_once  '../../modle/billingModule.php';
	include_once  '../../../../template/common.php';
	generateInvoice('bi.id');
	generalPrint();
	generateReturnList();
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

	if($paper_size=='A4'){
		$page_height=650;
		if($chq0_date!='')$chequedate='[ Cheque Date: '.$chq0_date.' ]&nbsp;&nbsp;&nbsp;&nbsp;'; else $chequedate='';
	}
	if($paper_size=='A5'){
		$page_height=480;
		$chequedate='';
	}
	if($bm_quotation_no!=0) $page_height-=70;
	if($bi_type==1 || $bi_type==2){
        if(($tax != '') && ($tax != 0)) $bill_title='TAX INVOICE';
		else $bill_title='INVOICE';
		$sub_title='INVOICE NO';
		$advance='';
	}else if($bi_type==3){
        if(($tax != '') && ($tax != 0)) $bill_title='TAX INVOICE';
		else $bill_title='INVOICE';
		$sub_title='REPAIR NO';
		$advance='Advance';
	}else if($bi_type==4 || $bi_type==5){
		if($bm_status<3){
		$bill_title='CUST ORDER';
		$sub_title='ORDER NO';
		$advance='Advance';
		}else{
            if(($tax != '') && ($tax != 0)) $bill_title='TAX INVOICE';
            else $bill_title='INVOICE';
            $sub_title='INVOICE NO';
            $advance='';
		}
	}
	$dn=$_GET['dn'];
	if($dn=='yes'){
		$bill_title='DELIVERY NOTE';
		$sub_title='DELIVERY NOTE NO';
		$by_title='Order Packed By';
		$by_name=$bm_packed_by;
		$page_height-=250;
	}else{
		$by_title='Salesman';
		$by_name=$up_salesman;
	}
	if(($return_odr) || (sizeof($removed_code)>0) || (sizeof($removed_code)>0) || (sizeof($rt_code)>0) || (sizeof($rt_pending_code)>0) || (sizeof($removed_code)>0)){
		$page_height = '';
	}
?>
<style>
.header, .header-space{
    height: 180px;
}
.footer, .footer-space {
    height: 20px;
}
.header {
    width: 100%;
    padding-left:-15px;
    padding-right:-15px;
    position: fixed;
    top: 0;
}
.footer {
    width: 100%;
    position: fixed;
    bottom: 0;
}
</style>
<div id="print_top"></div>
<div class="footer">
    <!-- --------------------------------------------   COMPANY FOOTER SECTION  -------------------------------------------- -->
    <table align="center" width="100%" border="0" cellspacing="0"  style="font-family:Arial; font-size:9pt;">
        <tr>
            <td height="3px" bgcolor="black" style="-webkit-print-color-adjust: exact;"></td>
        </tr>
        <tr>
            <td align="center"><?php print str_replace("<br />",", ",$tm_address); ?></td>
        </tr>
    </table>
    <!-- --------------------------------------------   END COMPANY FOOTER SECTION  -------------------------------------------- -->
</div>
<table class="report-container">
    <tbody class="report-content">
		<!-- --------------------------------------------   HEADER SECTION  -------------------------------------------- -->
		<table width="100%" border="0" style="font-family:Calibri; font-size:11pt;">
			<tr>
				<td colspan="3" style="vertical-align:top">
					<img src="../../../../images/cplogo<?php print $logo; ?>.png" height="30px" />
				</td>
			</tr>
			<tr>
				<td>
					<table style="font-family:Calibri; font-size:11pt;" cellspacing="0">
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
                        <?php if($trn_no != ''){ ?>
                        <tr>
							<td>TRN No </td>
							<td>: <?php print $trn_no; ?></td>
						</tr>
                        <?php } ?>
					</table>
				</td>
				<td></td>
				<td align="right">
					<span style="font-family:'Arial Black'; font-size:18pt"><?php print $bill_title; ?></span>
					<span style="font-size:11pt; font-family:Calibri"><?php if($bi_type==2 || $bi_type==5) print '<br /><strong>Service Invoice</strong>'; else if($bi_type==3) print '<br /><strong>Repair Invoice</strong>'; ?></span>
				</td>
			</tr>
			<tr>
				<td>
					<?php print '<a href="../../../../index.php?components=billing&action=cust_details&id='.$cu_id.'&action2=finish_bill&id2='.$_GET['id'].'" target="_parent" title="Customer: '.$cu_details.'" style="text-decoration:none; color:black" >Customer: '.ucfirst($bi_cust0).'</a>,<br />Address: '.wordwrap($bi_cust0_address, 50, "<br>").'<br />TRN No: '.$cust_tax_no; ?>
				</td>
				<td align="right" colspan="2" style="font-family:Calibri; font-size:11pt">
					<table cellspacing="0" border="0">
						<tr>
							<td width="50px"></td>
							<td><?php print $sub_title; ?></td>
							<td> : </td>
							<td align="right"><?php print  str_pad($_GET['id'], 7, "0", STR_PAD_LEFT); ?></td>
						</tr>
						<?php if($bm_quotation_no!=0){
							print '<tr><td></td><td>Ref Quotation No</td><td> : </td><td align="right">'.str_pad($bm_quotation_no, 7, "0", STR_PAD_LEFT).'</td></tr>';
							print '<tr><td></td><td>Ref PO No</td><td> : </td><td align="right">'.$qm_po.'</td></tr>';
						} ?>
						<tr>
							<td width="50px"></td>
							<td><?php print $by_title; ?></td>
							<td> : </td>
							<td align="right"><?php print ucfirst($by_name); ?></td>
						</tr>
						<tr>
							<td width="50px"></td>
							<td>Place of Supply </td>
							<td> : </td>
							<td align="right"><?php print ucfirst($district_name); ?></td>
						</tr>
						<tr>
							<td colspan="2">TIME: <?php print substr($bi_time,0,5); ?> &nbsp;&nbsp;&nbsp;&nbsp;DATE</td>
							<td> : </td>
							<td align="right"><?php print $bi_date; ?></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<!-- --------------------------------------------   END HEADER SECTION  -------------------------------------------- -->
        <table align="center" height="<?php print $page_height; ?>px" width="100%" border="1" cellspacing="0" >
            <tr style="font-family:Arial; font-size:10pt; color:black; background-color:white; -webkit-print-color-adjust: exact;">
                <th width="50px" height="20px">#</th>
                <th align="">DESCRIPTION</th>
                <th width="50px" height="20px">QTY</th>
                <?php if($dn=='no'){
                        if($sub_system==0){?>
                            <th width="80px">TAG<br />PRICE</th>
                            <!-- <th width="80px">DISCOUNT%</th>
                            <th width="80px">Dis.<br />PRICE</th> -->
                        <?php }else{ ?>
                            <th width="80px">UNIT PRICE</th>
                        <?php } ?>
                    <th width="60px" >TOTAL (<?php print $currency; ?>)</th>
                <?php }else{ ?>
                    <th width="200px" >REMARKS</th>
                <?php } ?>
            </tr>
			<?php
				for($i=0;$i<sizeof($bill_id);$i++){
					if($bi_return_odr[$i]==0){
						print '<tr style="font-size:9pt" height="20px">
                                    <td style="border-bottom:0; border-top:0;" align="center">'.($i+1).'</td>
									<td style="border-bottom:0; border-top:0; padding-left:10px;">'.$bi_desc[$i].'</td>
									<td style="border-bottom:0; border-top:0;" align="right">'.number_format($bi_qty[$i]).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
						if($dn=='no'){
							print '<td style="border-bottom:0; border-top:0;" align="right">'.number_format($bi_price[$i],2).'&nbsp;&nbsp;</td>
							<td align="right" style="border-bottom:0; border-top:0;">'.number_format(($bi_qty[$i]*$bi_price[$i]),2).'&nbsp;&nbsp;</td>';
						}else{
							print '<td style="border-bottom:0; border-top:0;"></td>';
						}
						print '</tr>';
					}
				}
                print '<tr style="font-size:9pt">
                <td style="border-bottom:0; border-top:0;"></td>
                <td style="border-bottom:0; border-top:0;"></td>
                <td style="border-bottom:0; border-top:0;"></td>';
                if($dn=='no'){
                    if($sub_system==0)
                    print
                    '<td align="right" style="border-bottom:0; border-top:0;"></td>
                    <td align="right" style="border-bottom:0; border-top:0;"></td>';
                }else{
                    print '<td style="border-bottom:0; border-top:0;"></td>';
                }
                print '</tr>';

				if($dn=='no'){
					// NOTE: before remove discount% and dis column : sub_system==0 => $colspan=5;
                    $colspan=4;

                    // subtotal
                    print '<tr style="font-family:Calibri; font-size:11pt; font-weight:900; color:black; background-color:white; -webkit-print-color-adjust: exact;"><td colspan="'.$colspan.'" align="right" height="20px" style="padding-right:5px; border-right:1; border-bottom:0;">Subtotal Amount</td><td align="right">'.number_format($sub_total,$decimal).'&nbsp;&nbsp;</td></tr>';

                    if(($tax != '') && ($tax != 0)) {
                        // vat
                        print '<tr style="font-family:Calibri; font-size:11pt; font-weight:900; color:black; background-color:white; -webkit-print-color-adjust: exact;"><td colspan="'.$colspan.'" align="right" height="20px" style="padding-right:5px; border-right:1; border-bottom:0;">Value Added Tax '.$tax.'%</td><td align="right">'.number_format($tax_added_value,$decimal).'&nbsp;&nbsp;</td></tr>';
                    }
                    // total
					print '<tr style="font-family:Calibri; font-size:11pt; font-weight:900; color:black; background-color:white; -webkit-print-color-adjust: exact;"><td colspan="'.$colspan.'" align="right" height="20px" style="padding-right:5px; border-right:1; border-bottom:1; border-top:1">Total Amount</td><td align="right">'.number_format($total,$decimal).'&nbsp;&nbsp;</td></tr>';

                    // cash
					print '<tr style="font-family:Calibri; font-size:11pt; font-weight:900; color:black; background-color:white; -webkit-print-color-adjust: exact;"><td colspan="'.$colspan.'" align="right" height="20px" style="padding-right:5px; border-right:1; border-bottom:1; border-top:0; color:black;">'.$advance.' Payment: Cash</td><td align="right">'.number_format($cash_amount,$decimal).'&nbsp;&nbsp;</td></tr>';

                    // bank
					print '<tr style="font-family:Calibri; font-size:11pt; font-weight:900; color:black; background-color:white; -webkit-print-color-adjust: exact;"><td colspan="'.$colspan.'" align="right" height="20px" style="padding-right:5px; border-right:1; border-bottom:1; border-top:0; color:black;">'.$advance.' Payment: Bank</td><td align="right">'.number_format($bank_amount,$decimal).'&nbsp;&nbsp;</td></tr>';

                    // cheque
					print '<tr style="font-family:Calibri; font-size:11pt; font-weight:900; color:black; background-color:white; -webkit-print-color-adjust: exact;"><td colspan="'.$colspan.'" align="right" height="20px" style="padding-right:5px; border-right:1; border-bottom:1; border-top:0; color:black;">'.$chequedate.'<span style="padding-right:30px">'.$chq0_fullNo.'</span>'.$advance.' Payment: Cheque</td><td align="right">'.number_format($chque_amount,$decimal).'&nbsp;&nbsp;</td></tr>';

                    // remaining balance
					print '<tr style="font-family:Calibri; font-size:11pt; font-weight:900; color:black; background-color:white; -webkit-print-color-adjust: exact;"><td colspan="'.$colspan.'" align="right" height="20px" style="padding-right:5px; border-right:1; border-bottom:1; border-top:0; color:black;">Remaining Balance</td><td align="right">'.number_format(($total-$cash_amount-$chque_amount-$bank_amount),$decimal).'&nbsp;&nbsp;</td></tr>';
				}
        	?>
        </table>

        <?php if(sizeof($bill_id) > 30){ ?>
		    <div style="page-break-inside:avoid;">
                <!-- --------------------------------------------   HEADER SECTION  -------------------------------------------- -->
                <table width="100%" border="0" style="font-family:Calibri; font-size:11pt;">
                    <tr>
                        <td colspan="3" style="vertical-align:top">
                            <img src="../../../../images/cplogo<?php print $logo; ?>.png" height="30px" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table style="font-family:Calibri; font-size:11pt;" cellspacing="0">
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
                            <span style="font-family:'Arial Black'; font-size:18pt"><?php print $bill_title; ?></span>
                            <span style="font-size:11pt; font-family:Calibri"><?php if($bi_type==2 || $bi_type==5) print '<br /><strong>Service Invoice</strong>'; else if($bi_type==3) print '<br /><strong>Repair Invoice</strong>'; ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php print '<a href="../../../../index.php?components=billing&action=cust_details&id='.$cu_id.'&action2=finish_bill&id2='.$_GET['id'].'" target="_parent" title="Customer: '.$cu_details.'" style="text-decoration:none; color:black" >Customer: '.ucfirst($bi_cust0).'</a>,<br />Address: '.wordwrap($bi_cust0_address, 50, "<br>"); ?>
                        </td>
                        <td align="right" colspan="2" style="font-family:Calibri; font-size:11pt">
                            <table cellspacing="0" border="0">
                                <tr>
                                    <td width="50px"></td>
                                    <td><?php print $sub_title; ?></td>
                                    <td> : </td>
                                    <td align="right"><?php print  str_pad($_GET['id'], 7, "0", STR_PAD_LEFT); ?></td>
                                </tr>
                                <?php if($bm_quotation_no!=0){
                                    print '<tr><td></td><td>Ref Quotation No</td><td> : </td><td align="right">'.str_pad($bm_quotation_no, 7, "0", STR_PAD_LEFT).'</td></tr>';
                                    print '<tr><td></td><td>Ref PO No</td><td> : </td><td align="right">'.$qm_po.'</td></tr>';
                                } ?>
                                <tr>
                                    <td width="50px"></td>
                                    <td><?php print $by_title; ?></td>
                                    <td> : </td>
                                    <td align="right"><?php print ucfirst($by_name); ?></td>
                                </tr>
                                <tr>
                                    <td width="50px"></td>
                                    <td>Place of Supply </td>
                                    <td> : </td>
                                    <td align="right"><?php print ucfirst($district_name); ?></td>
                                </tr>
                                <tr>
                                    <td colspan="2">TIME: <?php print substr($bi_time,0,5); ?> &nbsp;&nbsp;&nbsp;&nbsp;DATE</td>
                                    <td> : </td>
                                    <td align="right"><?php print $bi_date; ?></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <!-- --------------------------------------------   END HEADER SECTION  -------------------------------------------- -->
                <table align="center" width="100%" border="0" cellspacing="0" style="font-size:9pt; font-family:Arial, Helvetica, sans-serif">
                        <tr><td colspan="2" style="padding-top:8px;"></td></tr>
                        <tr>
                            <td height="30px">Signature</td>
                            <td height="30px">............................................................</td>
                            <td height="30px" width="10px"></td>
                            <td height="30px">Name</td>
                            <td height="30px">............................................................</td>
                        </tr>
                        <tr><td colspan="5" style="padding-top:8px;"></td></tr>
                        <tr><td colspan="5" align="center">IT WAS A PLEASURE TO SERVE YOU<br>THANK YOU</td></tr>
                        <tr><td colspan="5"><br></td></tr>
                </table>
	    <?php }else { ?>
            <table align="center" width="100%" border="0" cellspacing="0" style="font-size:9pt; font-family:Arial, Helvetica, sans-serif">
                <tr><td colspan="2" style="padding-top:8px;"></td></tr>
                <tr>
                    <td height="30px">Signature</td>
                    <td height="30px">............................................................</td>
                    <td height="30px" width="10px"></td>
                    <td height="30px">Name</td>
                    <td height="30px">............................................................</td>
                </tr>
                <tr><td colspan="5" style="padding-top:8px;"></td></tr>
                <tr><td colspan="5" align="center">IT WAS A PLEASURE TO SERVE YOU<br>THANK YOU</td></tr>
                <tr><td colspan="5"><br></td></tr>
            </table>
	    <?php } ?>

        <!-- ------------- WARRANTY SECTION ------------- -->
        <?php if($dn=='no'){
            if($bm_quotation_no!=0){ ?>
                <table cellspacing="0" width="100%"  style="font-size:11pt; font-family:Calibri" >
                    <?php if($qm_warranty!='0')
                        print '<tr>
                                <td style="vertical-align:top" width="120px">
                                    <strong>Warranty</strong>
                                </td>
                                <td>'.$qm_warranty.' Months Warranty</td>
                            </tr>'; ?>
                    <tr><td colspan="2" height="3px"></td></tr>
                    <!-- <tr><td style="vertical-align:top"><strong>Payment Terms</strong></td><td><?php print $qm_terms; ?></td></tr> -->
                    <tr><td colspan="2" height="3px"></td></tr>
                </table>
            <?php }
        } ?>
	    <!-- ------------- END WARRANTY SECTION ------------- -->

	    <!-- ------------- DELIVERY NOTE SECTION ------------- -->
        <table align="center" width="100%" border="0" cellspacing="0" >
            <tr style="font-size:8pt;">
                <td>
                <?php if($dn=='yes'){ ?>
                    <table align="center" width="100%" style="font-family:Arial; font-size:9pt">
                        <tr height="50px">
                            <td width="100px">Delivered By </td>
                            <td>: ................................</td>
                            <td>Signature ................................</td>
                            <td width="10px"></td>
                            <td>Date ................................</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="font-family:Arial; font-size:9pt">
                    <table align="center" width="100%" style="font-family:Arial; font-size:9pt">
                        <tr height="35px">
                            <td width="100px" valign="bottom">Contact Person</td>
                            <td valign="bottom">: .......................................................... &nbsp;&nbsp;&nbsp;&nbsp;Contact Number : ..........................................................</td></tr>
                        <tr height="50px">
                            <td width="100px">Delivery Address</td>
                            <td>: .......................................................................................................................................................</td>
                        </tr>
                    </table>
                <?php } ?>
                </td>
            </tr>
        </table>

        <?php if($dn=='yes'){ ?>
            <table align="center" width="100%" style="font-family:Arial; font-size:9pt">
                <tr>
                    <td>
                        <br /><br />
                        <strong>I hereby confirm that goods are received in good condition.</strong>
                        <br /><br /><br /><br />
                        <table align="center" width="100%" style="font-family:Arial; font-size:9pt">
                            <tr>
                                <td align="center">..................................................</td>
                                <td align="center">..................................................</td>
                                <td align="center">..................................................</td>
                            </tr>
                            <tr>
                                <td align="center">Name</td>
                                <td align="center">NIC</td>
                                <td align="center">Signature and Rubber Stamp</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        <?php } ?>
	    <!-- ------------- END DELIVERY NOTE SECTION ------------- -->

        <!-- --------------------------------------------   FOOTER SECTION  (RETURNS) -------------------------------------------- -->
        <?php
		if((!sizeof($bill_id) > 30) && ($return_odr) || (sizeof($removed_code)>0) || (sizeof($removed_code)>0) || (sizeof($rt_code)>0) || (sizeof($rt_pending_code)>0) || (sizeof($removed_code)>0)){
			print '<div style="page-break-inside:avoid;">';
		}
        if($return_odr){
            print '<table align="center" width="100% !important;" border="0" cellspacing="0" style="font-size:9pt; font-family:Arial, Helvetica, sans-serif" >';
            print '<tr><td colspan="4"><hr></td></tr>';
            print '<tr><td colspan="4" align="center"><strong>NEW REPLACEMENTS FOR RETURN ITEMS</strong></td></tr>';
            print '<tr><td colspan="4"><hr></td></tr>';
            print '<tr><td>DESCRIPTION</td><td>U\PRICE</td><td>QTY</td><td>TOTAL</td></tr>';
            print '<tr><td colspan="4"><hr></td></tr>';
            $total2=0;
            for($i=0;$i<sizeof($bill_id);$i++){
                if($bi_return_odr[$i]==1){
                $total2+=$bi_qty[$i]*$bi_price[$i];
                print '<tr><td colspan="4">'.$bi_desc[$i].'</td></tr>';
                print '<tr><td>'.$bi_code[$i].'</td><td>'.number_format($bi_price[$i]).'</td><td align="right">'.number_format($bi_qty[$i]).'&nbsp;</td><td align="right">'.number_format($bi_qty[$i]*$bi_price[$i]).'&nbsp;</td></tr>';
                print '<tr><td colspan="4"><br></td></tr>';
                }
            }
            print '<tr><td colspan="4"><hr></td></tr>';
            print '<tr><td colspan="3">Total New Replacement Amount:</td><td>'.number_format($total2, $decimal).'</td></tr>';
            print '<tr><td colspan="4"><hr></td></tr>';
            print '</table>';
        }
        if(sizeof($removed_code)>0){
            print '<br><br><table align="center" width="100% !important;"border="0" cellspacing="0" style="font-size:8pt; font-family:Arial, Helvetica, sans-serif" >';
            print '<tr><td>Remaining Return Item Credit:</td><td>'.number_format($return_cr_bal).'</td></tr>';
            print '<tr><td colspan="2"><hr></td></tr>';
            print '</table>';
        }
        if(sizeof($rt_code)>0){
            print '<br><br><table align="center" width="100% !important;" border="0" cellspacing="0" style="font-size:8pt; font-family:Arial, Helvetica, sans-serif" >';
            print '<tr><td colspan="2"><hr></td></tr>';
            print '<tr><td colspan="2" align="center"><strong>REPLACEMENTS FOR RETURN ITEMS</strong></td></tr>';
            print '<tr><td colspan="2"><hr></td></tr>';
            print '<tr><td>DESCRIPTION</td><td>QTY</td></tr>';
            print '<tr><td colspan="2"><hr></td></tr>';
            for($i=0;$i<sizeof($rt_code);$i++){
                print '<tr><td colspan="2">'.$rt_desc[$i].'</td></tr>';
                print '<tr><td>'.$rt_code[$i].'</td><td align="right">'.number_format($rt_qty[$i]).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>';
                print '<tr><td colspan="2"><br></td></tr>';
            }
            print '<tr><td colspan="2"><hr></td></tr>';
            print '</table>';
        }
        if(sizeof($rt_pending_code)>0){
            print '<br><br><table align="center" width="100% !important;" border="0" cellspacing="0" style="font-size:8pt; font-family:Arial, Helvetica, sans-serif" >';
            print '<tr><td colspan="2"><hr></td></tr>';
            print '<tr><td colspan="2" align="center"><strong>PENDING RETURN ITEMS</strong></td></tr>';
            print '<tr><td colspan="2"><hr></td></tr>';
            print '<tr><td>DESCRIPTION</td><td>QTY</td></tr>';
            print '<tr><td colspan="2"><hr></td></tr>';
            for($i=0;$i<sizeof($rt_pending_code);$i++){
                print '<tr><td colspan="2">'.$rt_pending_desc[$i].'</td></tr>';
                print '<tr><td>'.$rt_pending_code[$i].'</td><td align="right">'.number_format($rt_pending_qty[$i]).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>';
                print '<tr><td colspan="2"><br></td></tr>';
            }
            print '</table>';
        }
        if(sizeof($removed_code)>0){
            print '<br><br><table align="center" width="100% !important;" border="0" cellspacing="0" style="font-size:8pt; font-family:Arial, Helvetica, sans-serif" >';
            print '<tr><td colspan="2"><hr></td></tr>';
            print '<tr><td colspan="2" align="center"><strong>CANCELED RETURN ITEMS</strong></td></tr>';
            print '<tr><td colspan="2"><hr></td></tr>';
            for($i=0;$i<sizeof($removed_code);$i++){
                print '<tr><td colspan="2">'.$removed_desc[$i].'</td></tr>';
                print '<tr><td>'.$removed_code[$i].'</td><td align="right">'.number_format($removed_qty[$i]).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>';
                print '<tr><td colspan="2"><br></td></tr>';
            }
            print '<tr><td colspan="2"><hr></td></tr>';
            print '</table>';
        }
        ?>
	    <br />
        <!-- --------------------------------------------   END FOOTER SECTION  (RETURNS) -------------------------------------------- -->
    </tbody>
    <tfoot>
        <tr>
            <td>
                <div class="footer-space">&nbsp;</div>
            </td>
        </tr>
    </tfoot>
</table>