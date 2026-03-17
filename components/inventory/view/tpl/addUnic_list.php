<?php 
if(isset($_GET['shipment_no'])){
	print '<table align="center" bgcolor="#E5E5E5" height="100%">';
	for($i=0;$i<sizeof($ship_inv_id);$i++){
		print '<tr style="font-size:12pt"><td width="30px" style="color:blue"><strong>'.($i+1).'</strong></td><td>'.$ship_itm_desc[$i].'</td><td width="50px"></td><td align="right"><input style="width:50px; type="text" id="shipitemid'.$ship_inv_id[$i].'" value="'.$ship_item_qty[$i].'" /> 
		</td></tr>';
	}
	print '</table>';
}else{ 
	print '<table align="center" style="font-size:12pt" height="100%">';
	print '<tr bgcolor="#CCCCCC"><th height="30px" style="padding-left:20px; padding-right:20px; color:navy">List of Unic Items For the Selection</th></tr>';
	for($i=0;$i<sizeof($itu_sn);$i++){
		print '<tr bgcolor="#EEEEEE"><td style="padding-left:40px; padding-right:20px; color:navy"><a href="index.php?components=inventory&action=show_edit_unic&item='.$_GET['item'].'&sn='.$itu_sn[$i].'">'.$itu_sn[$i].'</a></td></tr>';
	}
	print '</table>';

} 

?>