<?php
    include_once  'template/header.php';
	$decimal = getDecimalPlaces(1);
?>

<table align="center" height="100%" border="1" cellspacing="0" style="font-size:10pt; font-family:Calibri">
	<tr>
		<td colspan="<?php if($components != 'to') echo "6"; else echo "5"; ?>" style="border:0; background-color:#F8F8F8; color:maroon; font-weight:bold" align="center">
			All Go-Going (Not Finalyze Quotatios) by Current User <br /><br />
		</td>
	</tr>
	<tr>
		<td colspan="<?php if($components != 'to') echo "6"; else echo "5"; ?>" style="border:0; background-color:black; color:white; font-weight:bold"></td>
	</tr>
	<tr bgcolor="#E5E5E5">
		<th width="60px">#</th>
		<th>Quotation No</th>
		<th width="100px">Created Date</th>
		<th width="100px">Store</th>
		<th width="350px">Customer</th>
		<?php if($components != 'to'){ ?> <th width="100px">Amount</th> <?php } ?>
	</tr>
<?php
	for($i=0;$i<sizeof($qm_id);$i++){
		print
		'<tr bgcolor="#F5F5F5">
			<td align="center">
				<span>'.($i+1).'</span>
			</td>
			<td align="center">
				<a href="index.php?components='.$components.'&action=quotation&id='.$qm_id[$i].'&s='.$qm_created_by[$i].'&cust='.$qm_custid[$i].'" target="_blank">'.str_pad($qm_id[$i], 7, "0", STR_PAD_LEFT).'</a>
			</td>
			<td align="center">
				<a style="cursor:pointer; color:blue" title="Time: '.substr($qm_created_date[$i],11,5).'" >'.substr($qm_created_date[$i],0,10).'</a>
			</td>
			<td class="shipmentTB3">'.$qm_store[$i].'</td>
			<td class="shipmentTB3">'.$qm_cust[$i].'</td>';
			if($components != 'to'){
				print '<td class="shipmentTB3" align="right">'.number_format($qm_amount[$i], $decimal).'</td>';
			}
		print '</tr>';
	}
?>
	</table>

<br />
<?php
                include_once  'template/footer.php';
?>