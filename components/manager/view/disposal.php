<?php
                include_once  'template/header.php';
?>
	<link rel="stylesheet" href="plugin/jquery/css/jquery.ui.all.css" />
	<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.core.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.widget.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.position.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.autocomplete.js"></script>
	<link rel="stylesheet" href="plugin/jquery/css/demos.css" />
	<script type="text/javascript">
	$(function() {
		var availableTags1 = [<?php for ($x=0;$x<sizeof($py_chqnofull);$x++){ print '"'.$py_chqnofull[$x].'",'; } ?>	];
		$( "#tags1" ).autocomplete({
			source: availableTags1
		});
	});
</script>
<!-- ------------------Item List----------------------- -->
<table align="center" style="font-size:11pt"><tr><td>
<?php 
	if(isset($_REQUEST['message'])){
		if($_REQUEST['re']=='success') $color='green'; else $color='red';
	print '<span style="color:'.$color.'; font-weight:bold;">'.$_REQUEST['message'].'</span><br /><br />'; 
	}
?></td></tr></table>
<h2 align="center" style="color:#0158C2">List Of Disposal Items</h2>
<form action="index.php" method="get" onsubmit="return validateDateRange()" >
<input type="hidden" name="components" value="manager" />
<input type="hidden" name="action" value="show_disposal" />
<table align="center" style="font-family:Calibri; font-size:12pt">
<tr><td>From Date: <input type="date" name="from_date" id="from_date" value="<?php print $from_date; ?>" style="width:130px" /></td><td width="80px"></td>
<td>From Date: <input type="date" name="to_date" id="to_date" value="<?php print $to_date; ?>" style="width:130px" /></td>
<td><input type="submit" value="Get" style="height:60px; width:70px" /></td></tr>
</table>
</form>
<br>
<table align="center" bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0"  style="font-size:10pt" >
<tr style="font-weight:bold; background-color:#0066AA; color:white"><th>Disposal ID</th><th>Processed Date</th><th>Item</th><th>Return<br>Invoice | Qty</th><th>Disposal<br>Qty</th><th>Store</th><th></th></tr>
<?php for($i=0;$i<sizeof($dis_id);$i++){
		if($permission[$i]) $button='<input type="button" value="Move to Pending" onclick="moveDisposal('.$dis_id[$i].')" />'; else $button='';
		print '<tr><td align="right" style="padding-left:10px; padding-right:10px">'.$dis_id[$i].'</td><td style="padding-left:10px; padding-right:10px">'.$dis_date[$i].'</td><td style="padding-left:10px; padding-right:10px">'.$dis_description[$i].'</td><td align="center"><table>';
		if(isset($rtn_inv[$i])){
		for($j=0;$j<sizeof($rtn_inv[$i]);$j++){
			print '<tr><td><a href="index.php?components=billing&action=finish_return&id='.$rtn_inv[$i][$j].'">'.str_pad($rtn_inv[$i][$j], 7, "0", STR_PAD_LEFT).'</a></td><td><input type="text" value="'.$rtn_qty[$i][$j].'" style="width:30px; padding-right:5px; text-align:right" disabled="disabled" /></td></tr>';
		}
		}
		print '</table></td><td style="padding-left:10px; padding-right:10px" align="right">'.$dis_qty[$i].'</td><td style="padding-left:10px; padding-right:10px">'.$dis_store[$i].'</td><td>'.$button.'</td></tr>';
} ?>
</table>
<?php
                include_once  'template/footer.php';
?>