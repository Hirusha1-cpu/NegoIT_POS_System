<script type="text/javascript">
	$(function() {
		var availableTags1 = [<?php for ($x=0;$x<sizeof($description);$x++){ print '"'.$description[$x].'",'; } ?>	];
		$( "#r_model" ).autocomplete({
			source: availableTags1
		});
	});
	
	function setItemID(){
		var itemid = [<?php for ($x=0;$x<sizeof($id);$x++){ print '"'.$id[$x].'",'; } ?>	];
		var description = [<?php for ($x=0;$x<sizeof($description);$x++){ print '"'.$description[$x].'",'; } ?>	];
		var itemdesc=document.getElementById('r_model').value;
		var a=description.indexOf(itemdesc);
		if(a>-1){
			document.getElementById('itemid').value=itemid[a];	
			return true;		
		}else{
			document.getElementById('itemid').value=0;	
			window.alert('Invalid Item');
			return false;		
		}
	}
</script>
	<form action="index.php?components=billing&action=set_warranty_replace" method="post" onsubmit="return setItemID()">
	<input type="hidden" name="id" value="<?php print $_GET['id']; ?>" />
	<input type="hidden" name="itemid" id="itemid" value="0" />
	<table width="400px" border="0" style="font-size:10pt" align="center">
	<tr><td align="center" colspan="2">&nbsp;</td></tr>
	<tr bgcolor="#EFEFEF"><td align="left" width="350px" class="shipmentTB4">Replace Model</td><td class="shipmentTB4"><input type="text" name="r_model" id="r_model" value="<?php print $replace_itm; ?>" /> </td></tr>
	<tr bgcolor="#FAFAFA"><td align="left" width="350px" class="shipmentTB4">Replace SN</td><td class="shipmentTB4"><input type="text" name="r_sn" id="r_sn" value="<?php print $replace_sn; ?>" /> </td></tr>
	<tr bgcolor="#EFEFEF"><td align="left" width="350px" class="shipmentTB4">Extra Pay to the Supplier</td><td class="shipmentTB4"><input type="number" name="r_pay" id="r_pay" value="<?php print $replace_pay; ?>" style="text-align:right" /> </td></tr>
	<tr><td align="center" colspan="2">&nbsp;</td></tr>
	<tr><td align="center" colspan="2"><div id="validate_div"><input type="submit" value="Submit" style="width:100px; height:30px" onclick="validateWarranty()" /></div></td></tr>
	<tr><td align="center" colspan="2" height="200px" valign="top">
	</td></tr>
	</table>
	</form>