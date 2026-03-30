<?php
    include_once  '../../modle/bill2Module.php';
    include_once  '../../../../template/common.php';
	generateInvoice('bi.id');
	generalPrint();
    $paper_size=paper_size(2);
    if($paper_size=='A4'){
    	$page_height=820;
    	if($chq0_date!='')$chequedate='[ Cheque Date: '.$chq0_date.' ]&nbsp;&nbsp;&nbsp;&nbsp;'; else $chequedate='';
    }
    if($paper_size=='A5'){
    	$page_height=400;
    	$chequedate='';
    }
    if($bi_type==1 || $bi_type==2){
    	$bill_title='INVOICE';
    	$sub_title='INVOICE NO';
    	$advance='';
    }else if($bi_type==3){
    	$bill_title='INVOICE';
    	$sub_title='REPAIR NO';
    	$advance='Advance';
    }else if($bi_type==4 || $bi_type==5){
    	if($bm_status<3){
    	$bill_title='CUST ORDER';
    	$sub_title='ORDER NO';
    	$advance='Advance';
    	}else{
    	$bill_title='INVOICE';
    	$sub_title='INVOICE NO';
    	$advance='';
    	}
    }
    $dn=$_GET['dn'];
    if($dn=='yes'){
    	$bill_title='DELIVERY NOTE';
    	$sub_title='DELIVERY NOTE NO';
    	$page_height-=110;
    }
	$systemid=inf_systemid(2);
	$currency = getCurrency(2);
    $decimal = getDecimalPlaces(2);
?>
<div id="print_top"></div>
<table width="100%" border="0">
	<tr>
		<td rowspan="2" style="font-family:Arial; font-size:11pt" width="230px">
			<span style="font-size:13pt"><strong><?php print $tm_company; ?></strong></span><br />
			<?php print $tm_address; ?><br />
			Tel: <?php print $tm_tel; ?>
		</td>
		<td></td>
		<td align="right">
			<span style="font-family:'Arial Black'; font-size:18pt"><?php print $bill_title; ?></span>
			<br />
			<span style="font-size:12pt; font-family:Arial">
				<?php
					if($bi_type==2 || $bi_type==5) print '<strong>Service Invoice</strong>';
					else if($bi_type==3) print '<strong>Repair Invoice</strong>';
				?>
			</span>
			<br />
		</td>
	</tr>
	<tr>
		<td></td>
		<td align="right">
			<table style="font-family:Arial; font-size:9pt">
				<tr>
					<td style="font-size:11pt">
						<?php print $sub_title; ?>
					</td>
					<td style="font-size:11pt">: [<?php print  str_pad($_GET['id'], 7, "0", STR_PAD_LEFT); ?>]</td>
				</tr>
				<?php if($bm_quotation_no!=0){
					print '<tr><td>Ref Quotation No</td><td>: '.str_pad($bm_quotation_no, 7, "0", STR_PAD_LEFT).'</td></tr>';
					print '<tr><td>Ref PO No</td><td>: '.$qm_po.'</td></tr>';
				}
				?>
				<tr><td>DATE</td><td>: <?php print $bi_date; ?></td></tr>
				<tr><td>TIME</td><td>: <?php print substr($bi_time,0,5); ?></td></tr>
				<tr><td>PRINT DATE</td><td>: <?php print substr($print_time,0,10); ?></td></tr>
				<tr><td>PRINT TYPE</td><td>: <?php if($bm_print_st==0) print 'Original'; else print 'Re-Print'; ?></td></tr>
				<tr><td></td><td></td></tr>
			</table>
		</td>
	</tr>
</table>

<?php if($hire_purchase) $table_hight=''; else  $table_hight='height="'.$page_height.'px"'; ?>
	<table align="center" <?php print $table_hight; ?> width="100%" border="1" cellspacing="0" >
		<tr style="font-family:Arial; font-size:10pt">
			<th width="40px" height="20px">Code</th>
			<th width="40px">QTY</th>
			<th>DESCRIPTION</th>
			<?php if($dn=='no'){ ?>
				<th width="40px">
					UNIT<br />
					PRICE
				</th>
				<th width="60px">TOTAL</th>
			<?php } ?>
		</tr>
		<?php
			for($i=0;$i<sizeof($bill_id);$i++){
				if($bill_cross_tr[$i]>0) $color1="#CCCCCC"; else 	$color1="auto";
				if($bi_return_odr[$i]==0){
					print '<tr style="font-size:10pt; background-color:'.$color1.'" height="20px">
							<td align="center" style="border-bottom:0; border-top:0;">'.$bi_drawer[$i].'</td>
							<td style="border-bottom:0; border-top:0;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.number_format($bi_qty[$i]).'</td>
							<td style="border-bottom:0; border-top:0;">&nbsp;&nbsp;&nbsp;&nbsp;'.$bi_desc[$i].'</td>';
					if($dn=='no')
						print '<td width="50px" style="border-bottom:0; border-top:0;" align="right">'.number_format($bi_price[$i],$decimal).'&nbsp;&nbsp;</td>
						<td align="right" style="border-bottom:0; border-top:0;">'.number_format(($bi_qty[$i]*$bi_price[$i]),$decimal).'&nbsp;&nbsp;</td>';
					print '</tr>';
				}
			}
			print '<tr style="font-size:10pt"><td style="border-bottom:0; border-top:0;"></td><td style="border-bottom:0; border-top:0;"></td><td style="border-bottom:0; border-top:0;"></td>';
			if($dn=='no') print '<td width="50px" style="border-bottom:0; border-top:0;"></td><td align="right" style="border-bottom:0; border-top:0;"></td>';
			print '</tr>';

			if($dn=='no'){
				if(($pay_pro_fee > 0) && ($card_amount > 0)){
					$total1 = ((($pay_pro_fee / 100) * $card_amount) + $total);
				}else{
					$total1 = $total;
				}
				print '<tr style="font-size:10pt; font-weight:900;"><td colspan="4" align="right" height="20px" style="padding-right:5px; border-right:1; border-bottom:0; ">Total Amount</td><td align="right">'.number_format($total1,$decimal).'&nbsp;&nbsp;</td></tr>';
				print '<tr style="font-size:10pt; font-weight:900;"><td colspan="4" align="right" height="20px" style="padding-right:5px; border-right:1; border-bottom:0; border-top:0;">'.$advance.' Payment: Cash</td><td align="right">'.number_format($cash_amount,$decimal).'&nbsp;&nbsp;</td></tr>';
				print '<tr style="font-size:10pt; font-weight:900;"><td colspan="4" align="right" height="20px" style="padding-right:5px; border-right:1; border-bottom:0; border-top:0;"><span style="padding-right:30px">'.$card_full_data.'</span>'.$advance.' Payment: Card</td><td align="right">'.number_format($card_amount,$decimal).'&nbsp;&nbsp;</td></tr>';
				print '<tr style="font-size:10pt; font-weight:900;"><td colspan="4" align="right" height="20px" style="padding-right:5px; border-right:1; border-bottom:0; border-top:0;">'.$advance.' Payment Processing Fee</td><td align="right">'.$pay_pro_fee.'%&nbsp;&nbsp;</td></tr>';
				print '<tr style="font-size:10pt; font-weight:900;"><td colspan="4" align="right" height="20px" style="padding-right:5px; border-right:1; border-bottom:0; border-top:0;">'.$advance.' Payment: Bank Transfer</td><td align="right">'.number_format($bank_amount,$decimal).'&nbsp;&nbsp;</td></tr>';
				print '<tr style="font-size:10pt; font-weight:900;"><td colspan="4" align="right" height="20px" style="padding-right:5px; border-right:1; border-bottom:0; border-top:0;">'.$chequedate.'<span style="padding-right:30px">'.$chq0_fullNo.'</span>'.$advance.' Payment: Cheque</td><td align="right">'.number_format($chque_amount,$decimal).'&nbsp;&nbsp;</td></tr>';
				print '<tr style="font-size:10pt; font-weight:900;"><td colspan="4" align="right" height="20px" style="padding-right:5px; border-right:1; border-bottom:0; border-top:0;">Remaining Balance</td><td align="right">'.number_format(($total-$cash_amount-$card_amount-$bank_amount-$chque_amount),$decimal).'&nbsp;&nbsp;</td></tr>';
			}
		?>
	</table>
	<table align="center" width="100%" border="1" cellspacing="0"  >
		<tr>
			<td style="padding-left:10pt">
				<span style="font-family:Calibri; font-size:10pt"><strong>Terms & Conditions:</strong> </span>
				<table align="left" width="100%" style="font-family:Calibri; font-size:9pt">
					<tr><td>Warranty for Mobile Phones: 12Months/365 days only from the invoice date.</td></tr>
					<tr><td>Warranty for Selected Items: Warranty applicable for 6 months for selected items.</td></tr>
					<tr><td>Note: No Returns Accepted.</td></tr>
				</table>
			</td>
		</tr>
		<!-- ------------------------------Quotation Data------------------------------------------- -->
		<?php if($dn=='no'){
			if($bm_quotation_no!=0){ ?>
			<table cellspacing="0"  style="font-size:11pt; font-family:Calibri" >
			<?php if($qm_warranty!='0') print '<tr><td style="vertical-align:top" width="120px"><strong>Warranty</strong></td><td>'.$qm_warranty.' Months Warranty</td></tr>'; ?>
			<tr><td colspan="2" height="3px"></td></tr>
			<tr><td style="vertical-align:top"><strong>Terms</strong></td><td><?php print $qm_terms; ?></td></tr>
			<tr><td colspan="2" height="3px"></td></tr>
			</table>
		<?php }
		}
		?>
		<!-- ------------------------------------------------------------------------- -->
		<tr style="font-size:8pt;">
			<td>
			<?php if($dn=='no'){ ?>
			<table align="center" width="100%">
				<tr><td width="65px" style="font-family:Arial; font-size:9pt">Salesman : </td><td style="font-family:Arial; font-size:9pt">  <?php print ucfirst($up_salesman); ?></td><td></td><td width="80px" style="font-family:Arial; font-size:9pt">Name</td><td width="130px">  ..............................</td></tr>
				<tr><td style="font-family:Arial; font-size:9pt" colspan="2"><?php print '<a href="../../../../index.php?components=bill2&action=cust_details&id='.$cu_id.'&action2=finish_bill&id2='.$_GET['id'].'" target="_parent" title="'.$cu_details.'" style="text-decoration:none" >'.ucfirst($bi_cust).'</a>'; ?></td><td></td><td style="font-family:Arial; font-size:9pt">Signature</td><td>  ..............................</td></tr>
			</table>
			<?php }else{ ?>
			<table align="center" width="100%">
				<tr><td width="65px" style="font-family:Arial; font-size:9pt">Order Packed By : </td><td style="font-family:Arial; font-size:9pt">  <?php print ucfirst($bm_packed_by).'&nbsp;&nbsp;&nbsp;&nbsp;: '.$up_mobile; ?></td><td></td><td width="80px" style="font-family:Arial; font-size:9pt">Customer : </td><td width="130px" style="font-family:Arial; font-size:9pt"><?php print '<a href="../../../../index.php?components=bill2&action=cust_details&id='.$cu_id.'&action2=finish_bill&id2='.$_GET['id'].'" target="_parent" title="'.$cu_details.'" >'.ucfirst($bi_cust0).'</a>'; ?><td></tr>
				<tr height="50px"><td style="font-family:Arial; font-size:9pt">Name</td><td style="font-family:Arial; font-size:9pt">............................................</td><td></td><td style="font-family:Arial; font-size:9pt">Name</td><td>  ............................................</td></tr>
				<tr height="50px"><td style="font-family:Arial; font-size:9pt">Signature </td><td style="font-family:Arial; font-size:9pt">............................................</td><td></td><td style="font-family:Arial; font-size:9pt">Signature</td><td>  ............................................</td></tr>
				<tr height="50px"><td style="font-family:Arial; font-size:9pt;">Date</td><td style="font-family:Arial; font-size:9pt">............................................</td><td></td><td style="font-family:Arial; font-size:9pt">Date</td><td>  ............................................</td></tr>
			</table>
			<?php } ?>
			</td>
		</tr>
	</table>
<?php
	//--------------------------Hire_Purchase--------------------------------------------------------//
	if($hire_purchase){
		print '<br />';
			print '<table width="100%" border="1" cellspacing="0" style="font-family:Calibri; font-size:10pt;">';
			print '<tr><td colspan="4"><strong>Hire Purchase Payment Schedule</strong></td></tr>';
			print '<tr><td colspan="4">Schedule - <strong>'.$hp_type.'</strong></td></tr>';
			print '<tr><th width="40px">No.</th><th width="120px">Instalment Date</th><th width="100px">Amount</th><th>Remark</th></tr>';
			for($i=1;$i<=sizeof($hp_schedule);$i++){
				//print '<tr><td align="right" style="padding-right:5px">'.$i.'</td><td align="center">'.$hp_schedule[$i].'</td><td align="right" style="padding-right:10px">'.number_format($hp_amount).'</td><td style="padding-left:10px"><a target="_parent" href="../../../../index.php?components=bill2&action=finish_payment&id='.$hp_pay_id[$i].'">'.$hp_schedule_remark[$i].'</a></td></tr>';
				print '<tr><td align="right" style="padding-right:5px">'.$i.'</td><td align="center">'.$hp_schedule[$i].'</td><td align="right" style="padding-right:10px">'.number_format($hp_amount).'</td><td style="padding-left:10px"><a target="_parent" style="text-decoration:none" href="../../../../index.php?components=bill2&action=hp_paid_instalment&invoice_no='.$_GET['id'].'&inst_date='.$hp_schedule[$i].'" >'.$hp_schedule_remark[$i].'</a></td></tr>';
			}
			print '</table>';
	}
	//-----------------------------------------------------------------------------------------------//
?>