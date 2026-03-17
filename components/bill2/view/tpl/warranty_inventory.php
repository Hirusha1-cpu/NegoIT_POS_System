<script type="text/javascript">
	$(function() {
		var availableTags2 = [<?php for ($x=0;$x<sizeof($description);$x++){ print '"'.$description[$x].'",'; } ?>	];
		$( "#i_model" ).autocomplete({
			source: availableTags2
		});
		var availableTags4 = [<?php for ($x=0;$x<sizeof($unic_item_list);$x++){ print '"'.$unic_item_list[$x].'",'; } ?>	];
		$( "#i_sn" ).autocomplete({
			source: availableTags4
		});
	});
	
	function setItemID(){
		var itemid = [<?php for ($x=0;$x<sizeof($id);$x++){ print '"'.$id[$x].'",'; } ?>	];
		var description = [<?php for ($x=0;$x<sizeof($description);$x++){ print '"'.$description[$x].'",'; } ?>	];
		var unic = [<?php for ($x=0;$x<sizeof($unic);$x++){ print '"'.$unic[$x].'",'; } ?>	];	
		var claim=document.getElementById('claim').value;
		var repair=document.getElementById('repair').value;
		var replace=document.getElementById('replace').value;
		var inv=document.getElementById('inv').value;
		var st=document.getElementById('st').value;
		var id=document.getElementById('id').value;
		var itemid0=document.getElementById('itemid').value;
		var itemdesc=document.getElementById('i_model').value;
		var a=description.indexOf(itemdesc);
		var out=true;
		if(itemdesc!=''){
		if(a>-1){
			if(unic[a]==1){
				if(itemid[a]!=itemid0){
					document.getElementById('submit_div').innerHTML=document.getElementById('loading').innerHTML;
					window.location = 'index.php?components=bill2&action=warranty_inventory&id='+id+'&itemid='+itemid[a]+'&unic=yes&cashback=no&claim='+claim+'&repair='+repair+'&replace='+replace+'&inv='+inv+'&st='+st;
					document.getElementById('itemid').value=itemid[a];	
				}
			}else out=false;
		}else out=false;
		
		if(out==false){
			document.getElementById('itemid').value=0;	
			window.alert('Invalid Item');
			document.getElementById('i_model').value='';
		}
		}
	}
</script>
	<form action="index.php?components=bill2&action=add_warranty_inv" method="post">
	<input type="hidden" id="claim" name="claim" value="<?php print $_GET['claim']; ?>" />
	<input type="hidden" id="repair" name="repair" value="<?php print $_GET['repair']; ?>" />
	<input type="hidden" id="replace" name="replace" value="<?php print $_GET['replace']; ?>" />
	<input type="hidden" id="inv" name="inv" value="<?php print $_GET['inv']; ?>" />
	<input type="hidden" id="st" name="st" value="<?php print $_GET['st']; ?>" />
	<input type="hidden" name="id" id="id" value="<?php print $_GET['id']; ?>" />
	<input type="hidden" name="itemid" id="itemid" value="<?php if(isset($_GET['itemid'])) print $_GET['itemid']; else print '0'; ?>" />
	<table width="400px" border="0" style="font-size:10pt" align="center">
	<tr><td align="center" colspan="3">&nbsp;</td></tr>
	<tr bgcolor="#EFEFEF"><td align="left" width="350px" class="shipmentTB4">Inventory Replace Model</td><td class="shipmentTB4"><input type="text" name="i_model" id="i_model" value="<?php print $selected_itm; ?>" onclick="this.value=''" /> </td><td></td></tr>
	<tr bgcolor="#FAFAFA"><td align="left" width="350px" class="shipmentTB4">Inventory Inv Replace SN</td><td class="shipmentTB4"><input type="text" name="i_sn" id="i_sn" value="" onfocus="setItemID()" /></td><td style="color:blue"><?php if(isset($_GET['unic'])) print sizeof($unic_item_list); ?></td></tr>
	<tr bgcolor="#EFEFEF"><td align="left" width="350px" class="shipmentTB4">Inventory Extra Pay</td><td class="shipmentTB4"><input type="number" name="i_pay" id="i_pay" value="0" style="text-align:right" /> </td><td></td></tr>
	<tr><td align="center" colspan="3">&nbsp;</td></tr>
	<tr><td align="center" colspan="3"><div id="submit_div"><input type="submit" value="Submit" style="width:100px; height:30px" onclick="validateWarranty()" /></div></td></tr>
	<tr><td align="center" colspan="3" height="200px" valign="top">
	</td></tr>
	</table>
	</form>