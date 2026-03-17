<style type="text/css">
	.st-div{
		border-radius: 3px;
    	padding: 2px 10px 2px 10px;
    	color: white;
    	font-size: 11pt;
	}
</style>
<script>
function updateShipment($id){
	document.getElementById('div-update'+$id).innerHTML = document.getElementById('loading').innerHTML
	var new_qty_id="new_qty"+$id;
	var qty_new=document.getElementById(new_qty_id).value;
	window.location = 'index.php?components=inventory&action=shipment_item_update_tmp&id='+$id+'&qty_new='+qty_new;
}

function removeShipment($id){
	document.getElementById('div-update'+$id).innerHTML = document.getElementById('loading').innerHTML
	window.location = 'index.php?components=inventory&action=shipment_qty_item_remove_tmp&id='+$id;
}
</script>

<?php if(isset($_GET['shipment_no'])){ ?>
	<table align="center" bgcolor="#E5E5E5" height="100%">
<?php

	for($i=0;$i<sizeof($ship_inv_id);$i++){

		print '<tr style="font-size:12pt"><td width="30px" style="color:blue"><strong>'.($i+1).'</strong></td><td>'.$ship_itm_desc[$i].'</td><td width="5px"></td><td align="center"><div class="st-div" style="background-color:'.$ship_itm_st_color[$i].'">'.$ship_itm_st_name[$i].'</div></td><td width="5px"></td><td align="right">';

		if($ship_itm_st_name[$i] != 'Saved'){
			print 
			'<input style="width:50px; type="text" id="new_qty'.$ship_inv_id[$i].'" value="'.$ship_item_qty[$i].'" />
			<input type="hidden" id="old_qty'.$ship_inv_id[$i].'" value="'.$ship_item_qty[$i].'" /> ';
			if($editable) print
			'<div id="div-update'.$ship_inv_id[$i].'" style="display:inline-block;"><input type="Button" id="btn-update" value="Update"  onclick="updateShipment('.$ship_inv_id[$i].')" /> 
			<input type="Button" id="btn-remove" value="Remove"  onclick="removeShipment('.$ship_inv_id[$i].')" style="background-color:maroon; color:white"/></div>';
		}
		print '</td></tr>';
	}
?>	
	</table>
<?php } ?>