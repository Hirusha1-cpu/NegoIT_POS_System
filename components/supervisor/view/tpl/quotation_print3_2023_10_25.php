<?php
	include_once  '../../modle/supervisorModule.php';
	include_once  '../../../../template/common.php';
	generateQuot(1);
	$logo = getStoreLogo(2);
	$bill_module = bill_module(2);
	$decimal = getDecimalPlaces(2);
	$action=$_GET['action'];
	$components=$_GET['sub_components'];
	if($_GET['action']=='qo_com_inv'){
		$q_title='INVOICE';
		$q_number='Invoice No';
		$q_inv_code='';
		$page_height=690;
	}else{
		$q_title='QUOTATION';
		$q_number='Quotation No';
		$q_inv_code='QTN';
		if($qo_note==''){
			$page_height=590;
		}else{
			$note_len=strlen($qo_note);
			if($logo === '13_3') $page_height=550-(round($note_len/88)*20);
			else $page_height=590-(round($note_len/88)*20);
		}
	}
?>
<input type="hidden" id="item_count" value="<?php print sizeof($qi_item_des); ?>" />

<table width="100%" >
	<tr>
		<td rowspan="2" style="font-family:Calibri; font-size:11pt; vertical-align:top">
			<img src="../../../../images/cplogo<?php print $logo; ?>.png" height="<?php if($logo === '13_3') echo "100px;"; else echo "33px;"; ?>" />
			<table style="font-size:12pt;" cellspacing="0">
				<tr><td>Tel </td><td>: <?php print $qo_st_tel; ?></td></tr>
				<tr><td>Email </td><td>: <?php print $email; ?></td></tr>
				<tr><td>Web </td><td>: <?php print $web; ?></td></tr>
			</table>
			<br />
			<table width="100%" style="font-family:Calibri; font-size:12pt" cellspacing="0">
				<tr><td width="50px"><?php print $qo_att; ?></td></tr>
				<tr>
					<td width="50px">

						<?php
							if(($components != 'to')){
								print '<a href="../../../../index.php?components='.$bill_module.'&action=cust_details&id='.$qo_cust_id.'&action2=qo_finish&id2='.$_GET['id'].'" target="_parent" title="'.$cu_details.'" style="text-decoration:none; color:black" >'.ucfirst($qo_cust_name).'</a>,<br />'.$qo_cust_address;
							}else{
								print '<span style="text-decoration:none; color:black">'.ucfirst($qo_cust_name).'</span><br />'.$qo_cust_address;
							}

						?>
				</td>
				</tr>
			</table>
		</td>
		<td></td>
		<td align="right">
			<br /><br />
			<span style="font-family:'Arial Black'; font-size:18pt"><em><?php print $q_title; ?></em></span><br />
		</td>
	</tr>
	<tr>
		<td></td>
		<td align="right" style="font-family:Calibri; font-size:12pt">
			<table style="font-family:Calibri; font-size:12pt" cellspacing="">
				<tr>
					<td>DATE</td>
					<td> : </td>
					<td align="right"><?php print $qo_date; ?></td>
				</tr>
				<tr>
					<td><?php print $q_number; ?></td>
					<td> : </td>
					<td align="right">
						<?php print $q_inv_code.str_pad($quot_no, 7, "0", STR_PAD_LEFT); if($qo_v>1) print 'R'.($qo_v-1); ?>
					</td>
					</tr>
					<tr>
						<td>Created By</td>
						<td> : </td>
						<td align="right"><?php print ucfirst($qo_salesman); ?></td>
					</tr>
			</table>
		</td>
	</tr>
</table>

<table width="100%" style="font-family:Arial; font-size:11pt" cellspacing="0">
	<tr>
		<td align="center"  style="font-weight:bold; text-decoration:underline"><?php print $qo_heading; ?></td>
	</tr>
</table>

<br />

<table align="center" height="<?php print $page_height; ?>px" width="100%" border="1" cellspacing="0" border="1" >
	<tr style="font-family:Arial; font-size:10pt; color:white; background-color:black; -webkit-print-color-adjust: exact;" >
		<th width="30px">#</th>
		<th height="20px">DESCRIPTION</th>
		<th width="60px">QTY</th>
		<?php if(($components != 'to')){ ?>
		<th width="80px">UNIT <br />PRICE</th>
		<th width="100px" >TOTAL</th>
		<?php } ?>
	</tr>
	<?php
		$total_discount=0;
		for($i=0;$i<sizeof($qi_item_des);$i++){
			print
				'<tr style="font-size:10pt; vertical-align:top" height="20px">
					<td align="center" style="border-bottom:0; border-top:0;">
						<div id="id_'.$i.'">'.($i+1).'</div>
					</td>
					<td style="border-bottom:0; border-top:0;">
						<div id="des_'.$i.'">&nbsp;&nbsp;&nbsp;&nbsp;'.$qi_item_des[$i].'<br />&nbsp;&nbsp;&nbsp;&nbsp;'.$qi_item_code[$i].'</div>
						<div id="comm_'.$i.'">';
							if($qi_comment[$i]!='')
								print '&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-family:Calibri; font-weight:900;">'.$qi_comment[$i].'</span>';
					print '</div>
					<br />
					</td>
					<td style="border-bottom:0; border-top:0;" align="right">
						<div id="qty_'.$i.'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.number_format($qi_qty[$i]).'&nbsp;&nbsp;</div>
					</td>';
					if(($components != 'to')){
						print '<td width="50px" style="border-bottom:0; border-top:0;" align="right">
								<div id="uprice_'.$i.'">'.number_format($qi_uprice[$i],$decimal).'&nbsp;&nbsp;</div>
								</td>
								<td align="right" style="border-bottom:0; border-top:0;">
									<div id="tprice_'.$i.'">'.number_format($qi_qty[$i]*$qi_uprice[$i],$decimal).'&nbsp;&nbsp;</div>
							</td>';
					}
			print '</tr>';
			$total_discount+=$qi_qty[$i]*$qi_discount[$i];
		}
		if($qo_image==1){
			print '<tr style="font-size:10pt">
					<td style="border-bottom:0; border-top:0;"></td>
					<td style="border-bottom:0; border-top:0;" align="center">
						<img style="height:'.$qo_image_hei.'px" src="../../../../images/customerdata/'.$systemid.'/quotation/'.str_pad($_GET['id'], 10, "0", STR_PAD_LEFT).'.jpg" /></td>
					<td style="border-bottom:0; border-top:0;"></td>
					<td width="50px" style="border-bottom:0; border-top:0;"></td>
					<td align="right" style="border-bottom:0; border-top:0;"></td>
				</tr>';
		}else{
			print '<tr style="font-size:10pt">
					<td style="border-bottom:0; border-top:0;"></td>
					<td style="border-bottom:0; border-top:0;"></td>
					<td style="border-bottom:0; border-top:0;"></td>';
					if(($components != 'to')){
						print '<td width="50px" style="border-bottom:0; border-top:0;"></td>
						<td align="right" style="border-bottom:0; border-top:0;"></td>';
					}
			print '</tr>';
		}
		if(($components != 'to')){
			print '<tr style="font-family:Arial; font-size:10pt; font-weight:600; color:white; background-color:black; -webkit-print-color-adjust: exact;">
					<td colspan="4" align="right" height="20px" style="padding-right:5px; border-right:1; border-bottom:0; ">Total Amount &nbsp;&nbsp; Rs. </td>
					<td align="right">
						<div id="total">'.number_format($total,$decimal).'&nbsp;&nbsp;</div>
					</td>
				</tr>';
		}
			//		print '<tr style="font-family:Arial; font-size:10pt; font-weight:600;"><td colspan="4" align="right" height="20px" style="padding-right:5px; border-right:1; border-bottom:0; border-top:0;">Discounts &nbsp;&nbsp; Rs. </td><td align="right">'.number_format($total_discount,2).'&nbsp;&nbsp;</td></tr>';
			//		print '<tr style="font-family:Arial; font-size:10pt; font-weight:600;"><td colspan="4" align="right" height="20px" style="padding-right:5px; border-right:1; border-bottom:0; ">Sub Total &nbsp;&nbsp; Rs. </td><td align="right">'.number_format($total-$total_discount,2).'&nbsp;&nbsp;</td></tr>';
		?>
</table>

<br /><br />

<table cellspacing="0"  style="font-size:11pt; font-family:Calibri" >
	<?php if($qo_warranty!='0')
		print '<tr>
				<td style="vertical-align:top" width="120px"><strong>Warranty</strong></td>
				<td>'.$qo_warranty.' Months Warranty</td>
			</tr>';
	?>
	<tr>
		<td colspan="2" height="3px"></td>
	</tr>
	<?php if($action=='qo_finish'){ ?>
		<tr>
			<td style="vertical-align:top"><strong>Payment Terms</strong></td>
			<td><?php print $qo_terms; ?></td>
		</tr>
		<tr><td colspan="2" height="3px"></td></tr>
		<tr>
			<td style="vertical-align:top"><strong>Validity</strong></td>
			<td>This Quotation is valid for <strong><?php print $qo_validity; ?></strong> days only</td>
		</tr>
		<tr><td colspan="2" height="3px"></td></tr>
		<?php if($qo_leadtime!='')
				print '<tr>
							<td style="vertical-align:top"><strong>Lead Time</strong></td>
							<td>'.$qo_leadtime.'</td>
						</tr>';
		?>
		<tr><td colspan="2" height="3px"></td></tr>
		<?php if($qo_note!='')
			print '<tr>
						<td style="vertical-align:top"><strong>Note</strong></td>
						<td>'.$qo_note.'</td>
					</tr>';
			?>
	<?php } ?>
</table>

<br />

<table align="center" width="100%" border="0" cellspacing="0"  style="font-family:Arial; font-size:9pt">
	<tr>
		<td height="3px" bgcolor="black" style="-webkit-print-color-adjust: exact;"></td>
	</tr>
	<tr>
		<td align="center"><?php print  $qo_st_name.', '.str_replace(",<br />",", ",$qo_st_add); ?></td>
	</tr>
</table>
