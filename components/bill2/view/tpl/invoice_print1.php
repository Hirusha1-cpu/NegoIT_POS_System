<?php
	include_once  '../../modle/bill2Module.php';
	include_once  '../../../../template/common.php';
	generateInvoice('itq.drawer_no, bi.id');
	$paper_size=paper_size(2);
	if($paper_size=='A4'){
		$page_height=820;
		if($chq0_date!='')$chequedate='[Cheque Date: '.$chq0_date.' ]&nbsp;&nbsp;&nbsp;&nbsp;'; else $chequedate='';
	}
	if($paper_size=='A5'){
		$page_height=520;
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
	$decimal = getDecimalPlaces(2);
?>

<div id="print_top"></div>
<table width="100%">
	<tr>
		<td rowspan="2" style="font-family:Arial; font-size:11pt">
			<strong><?php print $tm_company; ?></strong><br />
			<?php print $tm_address; ?><br />
			Tel: <?php print $tm_tel; ?>
		</td>
		<td></td>
		<td align="right"><span
				style="font-family:'Arial Black'; font-size:18pt"><?php print $bill_title; ?></span><br /><span
				style="font-size:12pt; font-family:Arial"><?php if($bi_type==2 || $bi_type==5) print '<strong>Service Invoice</strong>'; else if($bi_type==3) print '<strong>Repair Invoice</strong>'; ?></span><br />
		</td>
	</tr>
	<tr>
		<td></td>
		<td align="right">
			<?php print $sub_title; ?> # [<?php print  str_pad($_GET['id'], 7, "0", STR_PAD_LEFT); ?>]<br />
			<span style="font-family:Arial; font-size:11pt">
				TIME: <?php print substr($bi_time,0,5); ?> &nbsp;&nbsp;&nbsp;&nbsp;DATE:
				<?php print $bi_date; ?><br /><br />
			</span></td>
	</tr>
</table>

	<table align="center" height="<?php print $page_height; ?>px" width="100%" border="1" cellspacing="0" border="1">
		<tr style="font-family:Arial; font-size:10pt">
			<th width="40px" height="20px">Code</th>
			<th width="40px">QTY</th>
			<th>DESCRIPTION</th><?php if($dn=='no'){ ?><th width="40px">UNIT <br />PRICE</th>
			<th width="60px">TOTAL</th><?php } ?>
		</tr>
		<?php
			for($i=0;$i<sizeof($bill_id);$i++){
				if($bill_cross_tr[$i]>0) $color1="#CCCCCC"; else 	$color1="auto";
				if($bi_return_odr[$i]==0){
					print '<tr style="font-size:10pt; background-color:'.$color1.'" height="20px">
						<td align="center" style="border-bottom:0; border-top:0;">'.$bi_drawer[$i].'</td>
						<td align="right" style="border-bottom:0; border-top:0; padding: 0 10px;">'.number_format($bi_qty[$i]).'</td>
						<td style="border-bottom:0; border-top:0; padding: 0 10px;">'.$bi_desc[$i].'</td>';
						if($dn=='no') print '<td width="50px" style="border-bottom:0; border-top:0; padding: 0 10px;" align="right">
							'.number_format($bi_price[$i],$decimal).'</td>
						<td align="right" style="border-bottom:0; border-top:0; padding: 0 10px;">
							'.number_format(($bi_qty[$i]*$bi_price[$i]),$decimal).'</td>';
						print '
					</tr>';
				}
			}
			print '<tr style="font-size:10pt">
				<td style="border-bottom:0; border-top:0;"></td>
				<td style="border-bottom:0; border-top:0;"></td>
				<td style="border-bottom:0; border-top:0;"></td>';
				if($dn=='no') print '<td width="50px" style="border-bottom:0; border-top:0;"></td>
				<td align="right" style="border-bottom:0; border-top:0;"></td>';
				print '
			</tr>';

			if($dn=='no'){
				print '<tr style="font-size:10pt; font-weight:900;">
					<td colspan="4" align="right" height="20px" style="padding:0 5px; border-right:1; border-bottom:0;">Total
						Amount</td>
					<td align="right" style="padding:0 10px;">'.number_format($total,$decimal).'</td>
				</tr>';
				print '<tr style="font-size:10pt; font-weight:900;">
					<td colspan="4" align="right" height="20px"
						style="padding:0 5px; border-right:1; border-bottom:0; border-top:0;">'.$advance.' Payment: Cash</td>
					<td align="right" style="padding:0 10px;">'.number_format($cash_amount,$decimal).'</td>
				</tr>';
				print '<tr style="font-size:10pt; font-weight:900;">
					<td colspan="4" align="right" height="20px"
						style="padding:0 5px; border-right:1; border-bottom:0; border-top:0;">'.$advance.' Payment: Bank Transfer
					</td>
					<td align="right" style="padding:0 10px;">'.number_format($bank_amount,$decimal).'</td>
				</tr>';
				print '<tr style="font-size:10pt; font-weight:900;">
					<td colspan="4" align="right" height="20px"
						style="padding:0 5px; border-right:1; border-bottom:0; border-top:0;">'.$chequedate.'<span
							style="padding-right:30px">'.$chq0_fullNo.'</span>'.$advance.' Payment: Cheque</td>
					<td align="right" style="padding:0 10px;">'.number_format($chque_amount,$decimal).'</td>
				</tr>';
				print '<tr style="font-size:10pt; font-weight:900;">
					<td colspan="4" align="right" height="20px"
						style="padding:0 5px; border-right:1; border-bottom:0; border-top:0;">Remaining Balance</td>
					<td align="right" style="padding:0 10px;">'.number_format(($total-$cash_amount-$chque_amount),$decimal).'</td>
				</tr>';
				if(isCustomerTotalOutstandingShowInBill(2)){
					print '<tr style="font-size:10pt; font-weight:900;" id="total_outstanding_row">
						<td colspan="4" align="right" height="20px"
							style="padding:0 5px; border-right:1; border-bottom:0; border-top:0;">Total Outstanding Amount</td>
						<td align="right" id="credit_balance_cell" style="padding:0 10px;"><span id="credit_balance_span">'.number_format($credit_balance, $decimal).'</span></td>
					</tr>';
				}
			}
		?>
	</table>
	<table align="center" width="100%" border="1" cellspacing="0">
		<tr style="font-size:8pt;">
			<td>
				<?php if($dn=='no'){ ?>
				<table align="center" width="100%">
					<tr>
						<td width="65px" style="font-family:Arial; font-size:9pt">Salesman : </td>
						<td style="font-family:Arial; font-size:9pt"> <?php print ucfirst($up_salesman); ?></td>
						<td></td>
						<td width="80px" style="font-family:Arial; font-size:9pt">Name</td>
						<td width="130px"> ..............................</td>
					</tr>
					<tr>
						<td style="font-family:Arial; font-size:9pt" colspan="2">
							<?php print '<a href="../../../../index.php?components=bill2&action=cust_details&id='.$cu_id.'&action2=finish_bill&id2='.$_GET['id'].'" target="_parent" title="'.$cu_details.'" style="text-decoration:none" >'.ucfirst($bi_cust).'</a>'; ?>
						</td>
						<td></td>
						<td style="font-family:Arial; font-size:9pt">Signature</td>
						<td> ..............................</td>
					</tr>
				</table>
				<?php }else{ ?>
				<table align="center" width="100%">
					<tr>
						<td width="65px" style="font-family:Arial; font-size:9pt">Order Packed By : </td>
						<td style="font-family:Arial; font-size:9pt">
							<?php print ucfirst($bm_packed_by).'&nbsp;&nbsp;&nbsp;&nbsp;: '.$up_mobile; ?></td>
						<td></td>
						<td width="80px" style="font-family:Arial; font-size:9pt">Customer : </td>
						<td width="130px" style="font-family:Arial; font-size:9pt">
							<?php print '<a href="../../../../index.php?components=bill2&action=cust_details&id='.$cu_id.'&action2=finish_bill&id2='.$_GET['id'].'" target="_parent" title="'.$cu_details.'" >'.ucfirst($bi_cust0).'</a>'; ?>
						<td>
					</tr>
					<tr height="50px">
						<td style="font-family:Arial; font-size:9pt">Name</td>
						<td style="font-family:Arial; font-size:9pt">............................................</td>
						<td></td>
						<td style="font-family:Arial; font-size:9pt">Name</td>
						<td> ............................................</td>
					</tr>
					<tr height="50px">
						<td style="font-family:Arial; font-size:9pt">Signature</td>
						<td style="font-family:Arial; font-size:9pt">............................................</td>
						<td></td>
						<td style="font-family:Arial; font-size:9pt">Signature</td>
						<td> ............................................</td>
					</tr>
					<tr height="50px">
						<td style="font-family:Arial; font-size:9pt">Date</td>
						<td style="font-family:Arial; font-size:9pt">............................................</td>
						<td></td>
						<td style="font-family:Arial; font-size:9pt">Date</td>
						<td> ............................................</td>
					</tr>
				</table>
				<?php } ?>
			</td>
		</tr>
	</table>