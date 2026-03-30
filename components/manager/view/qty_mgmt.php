	<link rel="stylesheet" href="plugin/jquery/css/demos.css" />

<?php 
                include_once  'template/header.php';
                if(isset($_GET['item_id'])) $item_id0=$_GET['item_id'];
?>
	<link rel="stylesheet" href="plugin/jquery/css/jquery.ui.all.css" />
	<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.core.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.widget.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.position.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.autocomplete.js"></script>
	<script>
	$(function() {
		var availableTags1 = [<?php for ($x=0;$x<sizeof($code);$x++){ print '"'.$code[$x].'",'; } ?>	];
		$( "#tags1" ).autocomplete({
			source: availableTags1
		});
		var availableTags2 = [<?php for ($x=0;$x<sizeof($description);$x++){ print '"'.$description[$x].'",'; } ?>	];
		$( "#tags2" ).autocomplete({
			source: availableTags2
		});
	});
	
	function setCode(){
		var id_arr = [<?php for ($x=0;$x<sizeof($item_id);$x++){ print '"'.$item_id[$x].'",'; } ?>	];
		var code_arr = [<?php for ($x=0;$x<sizeof($code);$x++){ print '"'.$code[$x].'",'; } ?>	];
		var desc_arr = [<?php for ($x=0;$x<sizeof($description);$x++){ print '"'.$description[$x].'",'; } ?>	];
		var code=document.getElementById('tags1').value;
		var desc=document.getElementById('tags2').value;
		if(code==''){
			var a=desc_arr.indexOf(desc);
			document.getElementById('tags1').value=code_arr[a];
			document.getElementById('item_id').value=id_arr[a];
		}
		if(desc==''){
			var a=code_arr.indexOf(code);
			document.getElementById('tags2').value=desc_arr[a];
			document.getElementById('item_id').value=id_arr[a];
		}
	}
	
	function clearFields(){
		document.getElementById('tags1').value='';
		document.getElementById('tags2').value='';
	}
	
	function adjustQty($tag,$id){
		var qty_adj=document.getElementById($tag+'_adj'+$id).value;
		var item_id=document.getElementById('item_id').value;
		var comment=document.getElementById('comment').value;
		if(qty_adj!='' && qty_adj!=0){
			document.getElementById($tag+'_div'+$id).innerHTML=document.getElementById('loading').innerHTML;
			window.location = 'index.php?components=manager&action=adjust_qty&tag='+$tag+'&id='+$id+'&qty_adj='+qty_adj+'&item_id='+item_id+'&comment='+comment;
		}else{
			window.alert("Invalid Data");
		}
	}
	</script>

<?php
	if(isset($_REQUEST['message'])){
		if($_REQUEST['re']=='success') $color='green'; else $color='#DD3333';
		print '<script type="text/javascript">document.getElementById("notifications").innerHTML='."'".'<span style="color:'.$color.'; font-weight:bold;font-size:12pt;">'.$_REQUEST['message'].'</span>'."'".';</script>';
	}
?>
<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px" /></div>

<table align="center"><tr><td valign="top">
	<form action="index.php" onsubmit="return setCode()">
	<input type="hidden" name="components" value="manager" />
	<input type="hidden" name="action" value="qty_mgmt" />
	<input type="hidden" id="item_id" name="item_id" value="<?php print $item_id0; ?>" />
	<table align="center" bgcolor="#E5E5E5" style="font-size:12pt">
	<tr><td colspan="4" height="10px"></td></tr>
	<tr><td width="50px"></td><td>Item Code</td><td><input type="text" id="tags1" value="<?php print $code1; ?>" onclick="clearFields()" /></td><td width="50px"></td></tr>
	<tr><td width="50px"></td><td>Item Description</td><td><input type="text" id="tags2" value="<?php print $description1; ?>" onclick="clearFields()" /></td><td width="50px"></td></tr>
	<tr><td colspan="4" align="center"><br /><input type="submit" value="Search Item" style="width:100px; height:40px" onclick="setCode()" /><br /><br /></td></tr>
	
	<?php if($approve_edit){ ?>
	<tr><td></td><td>Store</td><td><input type="text" disabled="disabled"  value="<?php print $_COOKIE['store_name']; ?>" /></td><td></td></tr>
	<tr><td></td><td>Comment</td><td><textarea id="comment"></textarea></td><td></td></tr>
	<tr><td></td><td colspan="2">
		<table>
		<tr style="font-size:8pt; background-color:#CCCCCC" ><td></td><td align="center">Wholesale</td><td align="center">Retail</td><td align="center">Cost</td><td align="center">QTY</td><td align="center">Adjustment</td><td></td></tr>
		<tr style="background-color:#EEEEEE"><td>Current Inventory</td><td align="center"><input type="text" disabled="disabled" style="text-align:right; width:50px" value="<?php print $inv_wprice; ?>" /></td><td align="center"><input type="text" disabled="disabled" style="text-align:right; width:50px" value="<?php print $inv_rprice; ?>" /></td><td align="center"><input type="text" disabled="disabled" style="text-align:right; width:50px" value="<?php print $inv_cprice; ?>" /></td><td align="center"><input type="text" disabled="disabled" style="text-align:right; width:50px" value="<?php print $inv_qty; ?>" /></td><td align="center"><input type="number" id="inv_adj<?php print $inv_id; ?>" style="text-align:right; width:50px" /></td><td><div id="inv_div<?php print $inv_id; ?>"><input type="button" onclick="adjustQty('inv','<?php print $inv_id; ?>')" value="Adjust Qty" /></div></td></tr>
		<?php 
		$total_qty=$inv_qty;
		for($i=0;$i<sizeof($inn_id);$i++){
				if(($i%2)==0) $color='#DDDDDD'; else $color='#EEEEEE';
				$total_qty+=$inn_qty[$i];
		?>
		<tr style="background-color:<?php print $color; ?>"><td>New Inventory</td><td align="center"><input type="text" disabled="disabled" style="text-align:right; width:50px" value="<?php print $inn_wprice[$i]; ?>" /></td><td align="center"><input type="text" disabled="disabled" style="text-align:right; width:50px" value="<?php print $inn_rprice[$i]; ?>" /></td><td align="center"><input type="text" disabled="disabled" style="text-align:right; width:50px" value="<?php print $inn_cprice[$i]; ?>" /></td><td align="center"><input type="text" disabled="disabled" style="text-align:right; width:50px" value="<?php print $inn_qty[$i]; ?>" /></td><td align="center"><input type="number" id="inn_adj<?php print $inn_id[$i]; ?>" style="text-align:right; width:50px" /></td><td><div id="inn_div<?php print $inn_id[$i]; ?>"><input type="button" onclick="adjustQty('inn','<?php print $inn_id[$i]; ?>')" value="Adjust Qty" /></div></td></tr>
		<?php } ?>
		<tr style="background-color:#CCCCCC"><td>Total</td><td align="center"></td><td align="center"></td><td align="center"></td><td align="right"><?php print $total_qty; ?></td><td align="center"></td><td></td></tr>
		</table>
	</td><td></td></tr>
	<tr><td colspan="4" align="center"><br /><br /><br /></td></tr>
	</table>
	</form>
</td><td width="50px"></td><td valign="top">
	<table style="font-size:12pt">
	<tr style="background-color:silver"><th class="shipmentTB3">Date</th><th class="shipmentTB3" >Adjust Qty</th><th class="shipmentTB3">User</th><th class="shipmentTB3">Comment</th></tr>
	<?php
	for($i=0;$i<sizeof($ie_date);$i++){
		if(($i%2)==0) $color='#F5F5F5'; else $color='#EEEEEE';
		print '<tr style="background-color:'.$color.'"><td class="shipmentTB3" style="color:#333399; cursor:pointer" title="Time: '.substr($ie_date[$i],11,5).'">'.substr($ie_date[$i],0,10).'</td><td class="shipmentTB3" align="right">'.number_format($ie_action_qty[$i]).'</td><td class="shipmentTB3">'.ucfirst($ie_user[$i]).'</td><td class="shipmentTB3">'.$comment[$i].'</td></tr>';
	}
	?>
	<tr></tr>
	</table>
</td></tr></table>


<?php } ?>
<?php 
                include_once  'template/footer.php';

?>