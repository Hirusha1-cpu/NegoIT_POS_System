<?php if(isset($_GET['shipment_no'])){ ?>
	<table align="center" bgcolor="#E5E5E5" height="100%">
<?php
	for($i=0;$i<sizeof($ship_inv_id);$i++){
		print '<tr style="font-size:12pt"><td width="30px" style="color:blue"><strong>'.($i+1).'</strong></td><td>'.$ship_itm_desc[$i].'</td><td width="50px"></td><td align="right"><input style="width:50px; type="text" id="new_qty'.$ship_inv_id[$i].'" value="'.$ship_item_qty[$i].'" /> 
		<input type="hidden" id="old_qty'.$ship_inv_id[$i].'" value="'.$ship_item_qty[$i].'" />
		<input type="Button" value="Update"  onclick="updateShipment('.$ship_inv_id[$i].')" /> 
		<input type="Button" value="Remove"  onclick="removeShipment('.$ship_inv_id[$i].')" style="background-color:maroon; color:white"/> 
		</td></tr>';
	}
?>	
	</table>
<?php } ?>