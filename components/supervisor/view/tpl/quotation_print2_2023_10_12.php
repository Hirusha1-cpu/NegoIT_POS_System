<?php
	include_once  '../../modle/supervisorModule.php';
	include_once  '../../../../template/common.php';
	generateQuot(1);
	if($qo_note==''){
		$page_height=570;
	}else{
		$note_len=strlen($qo_note);
		$page_height=570-(round($note_len/88)*20);
	}

?>
<input type="hidden" id="item_count" value="<?php print sizeof($qi_item_des); ?>" />
<table width="100%">
<tr><td rowspan="2" style="font-family:Arial; font-size:11pt; vertical-align:top"><img src="../../../../images/cplogo<?php print $systemid; ?>.png" height="33px" />
<br />
<?php print $qo_st_add; ?><br />
<table style="font-size:10pt">
<tr><td>Tel </td><td>: <?php print $qo_st_tel; ?></td></tr>
<tr><td>Email </td><td>: <?php print $email; ?></td></tr>
<tr><td>Web </td><td>: <?php print $web; ?></td></tr>
</table>
</td><td></td><td align="right"><br /><br /><span style="font-family:'Arial Black'; font-size:18pt">QUOTATION</span><br /></td></tr>
<tr><td></td><td align="right" style="font-family:Arial; font-size:11pt">
<span style="font-family:Arial; font-size:11pt">DATE: <?php print $qo_date; ?><br />
Quotation No # [<?php print  str_pad($quot_no, 7, "0", STR_PAD_LEFT).'V'.$qo_v; ?> ]<br /><br />
</span></td></tr>
</table>
<br />
<table width="100%" style="font-family:Arial; font-size:11pt" cellspacing="0">
<tr><td width="50px">&nbsp;&nbsp;&nbsp;&nbsp;To</td><td>:&nbsp;&nbsp;&nbsp;&nbsp;<?php print '<a href="../../../../index.php?components=billing&action=cust_details&id='.$qo_cust_id.'&action2=qo_finish&id2='.$_GET['id'].'" target="_parent" title="'.$cu_details.'" style="text-decoration:none" >'.ucfirst($qo_cust_name).'</a>'; ?></td></tr>
<tr><td width="50px">&nbsp;&nbsp;&nbsp;&nbsp;Att</td><td>:&nbsp;&nbsp;&nbsp;&nbsp;<?php print $qo_att; ?></td></tr>
</table>
<table width="100%" style="font-family:Arial; font-size:11pt" cellspacing="0"><tr><td align="center"  style="font-weight:bold; text-decoration:underline"><?php print $qo_heading; ?></td></tr></table>
<br />
	<table align="center" height="<?php print $page_height; ?>px" width="100%" border="1" cellspacing="0" border="1" >
	<tr style="font-family:Arial; font-size:10pt; color:white; background-color:black; -webkit-print-color-adjust: exact;" ><th width="30px">#</th><th height="20px">DESCRIPTION</th><th width="40px">QTY</th><th width="40px">UNIT <br />PRICE</th><th width="60px" >TOTAL</th></tr>
<?php
	for($i=0;$i<sizeof($qi_item_des);$i++){
		print '<tr style="font-size:10pt; vertical-align:top" height="20px"><td align="center" style="border-bottom:0; border-top:0;"><div id="id_'.$i.'">'.($i+1).'</div></td><td style="border-bottom:0; border-top:0;"><div id="des_'.$i.'">&nbsp;&nbsp;&nbsp;&nbsp;'.$qi_item_des[$i].'<br />&nbsp;&nbsp;&nbsp;&nbsp;'.$qi_item_code[$i].'</div><div id="comm_'.$i.'">';
		if($qi_comment[$i]!='') print '&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-family:Calibri; font-weight:900;">'.$qi_comment[$i].'</span>';
		print '</div><br /></td><td style="border-bottom:0; border-top:0;" align="right"><div id="qty_'.$i.'">&nbsp;&nbsp;'.number_format($qi_qty[$i]).'&nbsp;&nbsp;</div></td><td width="50px" style="border-bottom:0; border-top:0;" align="right"><div id="uprice_'.$i.'">'.number_format($qi_uprice[$i]).'&nbsp;&nbsp;</div></td><td align="right" style="border-bottom:0; border-top:0;"><div id="tprice_'.$i.'">'.number_format($qi_qty[$i]*$qi_uprice[$i]).'&nbsp;&nbsp;</div></td></tr>';
	}
	if($qo_image==1){
		print '<tr style="font-size:10pt"><td style="border-bottom:0; border-top:0;"></td><td style="border-bottom:0; border-top:0;" align="center"><img style="height:'.$qo_image_hei.'px" src="../../../../images/customerdata/'.$systemid.'/quotation/'.str_pad($_GET['id'], 10, "0", STR_PAD_LEFT).'.jpg" /></td><td style="border-bottom:0; border-top:0;"></td><td width="50px" style="border-bottom:0; border-top:0;"></td><td align="right" style="border-bottom:0; border-top:0;"></td></tr>';
	}else{
		print '<tr style="font-size:10pt"><td style="border-bottom:0; border-top:0;"></td><td style="border-bottom:0; border-top:0;"></td><td style="border-bottom:0; border-top:0;"></td><td width="50px" style="border-bottom:0; border-top:0;"></td><td align="right" style="border-bottom:0; border-top:0;"></td></tr>';
	}
		print '<tr style="font-family:Arial; font-size:10pt; font-weight:600; color:white; background-color:black; -webkit-print-color-adjust: exact;"><td colspan="4" align="right" height="20px" style="padding-right:5px; border-right:1; border-bottom:0; ">Total Amount &nbsp;&nbsp; Rs. </td><td align="right"><div id="total">'.number_format($total).'&nbsp;&nbsp;</div></td></tr>';	
?>	
	</table>
	<br /><br />
	<table cellspacing="0"  style="font-size:11pt; font-family:Calibri" >
	<tr><td style="vertical-align:top" width="120px"><strong>Warranty</strong></td><td><?php print $qo_warranty; ?> Months Warranty</td></tr>
	<tr><td colspan="2" height="3px"></td></tr>
	<tr><td style="vertical-align:top"><strong>Payment Terms</strong></td><td><?php print $qo_terms; ?></td></tr>
	<tr><td colspan="2" height="3px"></td></tr>
	<tr><td style="vertical-align:top"><strong>Validity</strong></td><td>This Quotation is valid for <strong><?php print $qo_validity; ?></strong> days only</td></tr>
	<tr><td colspan="2" height="3px"></td></tr>
	<tr><td style="vertical-align:top"><strong>Lead Time</strong></td><td><?php print $qo_leadtime; ?></td></tr>
	<tr><td colspan="2" height="3px"></td></tr>
	<?php if($qo_note!='') print '<tr><td style="vertical-align:top"><strong>Note</strong></td><td>'.$qo_note.'</td></tr>'; ?>
	</table>
	<br />
	<table align="center" width="100%" border="1" cellspacing="0" >
	<tr style="font-size:8pt;"><td>
		<table align="center" width="100%">
			<tr><td width="65px" style="font-family:Arial; font-size:9pt">Salesman : </td><td style="font-family:Arial; font-size:9pt">  <?php print ucfirst($qo_salesman); ?></td><td></td><td width="80px" style="font-family:Arial; font-size:9pt">Name</td><td width="130px">  ..............................</td></tr>
			<tr><td style="font-family:Arial; font-size:9pt" colspan="2"></td><td></td><td style="font-family:Arial; font-size:9pt">Signature</td><td>  ..............................</td></tr>
		</table>
	</td></tr>
	</table>
