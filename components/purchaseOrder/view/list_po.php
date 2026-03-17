<?php
    include_once  'template/header.php';
?>

<table align="center" style="font-size:12pt">
	<tr>
		<td>
			<?php
				if(isset($_REQUEST['message'])){
					if($_REQUEST['re']=='success') $color='green'; else $color='red';
				print '<span style="color:'.$color.'; font-weight:bold;">'.$_REQUEST['message'].'</span>';
				}
			?>
		</td>
	</tr>
</table>

<table align="center" style="font-family:Calibri; font-size:12pt">
	<tr bgcolor="#DDDDDD">
		<th width="50px;">#</th>
		<th>&nbsp;&nbsp;PO Number&nbsp;&nbsp;</th>
		<th>PO Date</th>
		<th>Submited Date</th>
		<th>Supplier</th>
		<th>&nbsp;&nbsp;Estimated Cost&nbsp;&nbsp;</th>
		<th>Status</th>
	</tr>
	<?php for($i=0;$i<sizeof($po_number);$i++){
 	print '<tr bgcolor="#EEEEEE">
	 	<td align="center">'.($i+1).'</td>
 		<td align="center"><strong>&nbsp;&nbsp;<a
 					href="index.php?components=purchase_order&action=one_po&id='.$po_number[$i].'">'.str_pad($po_number[$i],
 					7, "0", STR_PAD_LEFT).'</a>&nbsp;&nbsp;</strong></td>
 		<td>&nbsp;&nbsp;'.$po_date[$i].'&nbsp;&nbsp;</td>
 		<td>&nbsp;&nbsp;'.$po_submited_date[$i].'&nbsp;&nbsp;</td>
 		<td>&nbsp;&nbsp;'.$po_sup[$i].'&nbsp;&nbsp;</td>
 		<td align="right">&nbsp;&nbsp;'.number_format($po_cost[$i]).'&nbsp;&nbsp;</td>
 		<td align="center" style="color:'.$po_color[$i].'">&nbsp;&nbsp;'.$po_status1[$i].'&nbsp;&nbsp;</td>
 	</tr>';
 } ?>
</table>

<?php
    include_once  'template/footer.php';
?>