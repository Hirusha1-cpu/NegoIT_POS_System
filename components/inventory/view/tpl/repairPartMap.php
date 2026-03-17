	<link rel="stylesheet" href="plugin/jquery/css/jquery.ui.all.css" />
	<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.core.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.widget.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.position.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.autocomplete2.js"></script>
	<link rel="stylesheet" href="plugin/jquery/css/demos.css" />
	<script type="text/javascript">
	$(function() {
		var availableTags0 = [<?php for ($x=0;$x<sizeof($rep_itm_name);$x++){ print '"'.$rep_itm_name[$x].'",'; } ?>	];
		$( "#part" ).autocomplete({
			source: availableTags0 
		});
	});
	
	
	function mapPart(){
		var part_name = [<?php for ($x=0;$x<sizeof($rep_itm_name);$x++){ print '"'.$rep_itm_name[$x].'",'; } ?>	];
		var part_id = [<?php for ($x=0;$x<sizeof($rep_itm_id);$x++){ print '"'.$rep_itm_id[$x].'",'; } ?>	];		
		var part=document.getElementById('part').value;
		var item_id=document.getElementById('item_id').value;
		if(part!=''){
			var a=part_name.indexOf(part);
			window.location = 'index.php?components=inventory&action=add_repair_map&item_id='+item_id+'&part_id='+part_id[a];
		}
	}

	
	function removePart(id){
		var check= confirm("Do you want to Unassign this Part?");
		var item_id=document.getElementById('item_id').value;
		if (check==true){
			document.getElementById('div_'+id).innerHTML=document.getElementById('loading').innerHTML; 
			window.location = 'index.php?components=inventory&action=remove_repair_map&item_id='+item_id+'&id='+id;
		}
	}
	</script>
<input type="hidden" id="item_id" value="<?php print $_GET['item']; ?>" />
<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px" /></div>

<table align="center" style="font-size:11pt" bgcolor="#EEEEEE">
<tr><td class="shipmentTB4"><strong>Repair Job Item :</strong></td><td><?php print $itm_desc; ?></td><td width="60px" align="center"><img src="images/arrow_left.png" width="20px" /><img src="images/arrow_right.png" width="20px" /></td><td><input type="text" id="part" placeholder="Repair Part" /></td><td><input type="button" value="Map" onclick="mapPart()" /></td></tr>
</table>
<br />
<table align="center" style="font-size:11pt" >
<tr><td colspan="2"  class="shipmentTB4" bgcolor="navy" style="color:white; font-size:12pt; font-weight:bold" >List of Associated Parts</td></tr>
<?php for($i=0;$i<sizeof($repair_part_id);$i++){
		if(($i%2)==0) $color='#DDDDDD'; else $color='#EEEEEE';
	print '<tr bgcolor="'.$color.'" ><td class="shipmentTB4">'.$repair_part_name[$i].'</td><td align="center"><div id="div_'.$repair_part_id[$i].'" ><a style="cursor:pointer" onclick="removePart('.$repair_part_id[$i].')"><img src="images/action_delete.gif" /></a></div></td></tr>';
} ?>
</table>