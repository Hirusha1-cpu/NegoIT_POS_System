	<script src="js/zigo.js"></script>

	<link rel="stylesheet" href="plugin/jquery/css/jquery.ui.all.css" />
	<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.core.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.widget.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.position.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.autocomplete.js"></script>
	<script>
	$(function() {
		var availableTags1 = [<?php for ($x=0;$x<sizeof($description);$x++){ print '"'.$description[$x].'",'; } ?>	];
		$( "#tags1" ).autocomplete({
			source: availableTags1
		});
	});
	
	</script>
	
	
<table align="center" cellspacing="0" style="font-size:12pt" ><tr><td valign="top">
<?php
if(isset($_REQUEST['message'])){
	if($_REQUEST['re']=='success') $color0='green'; else $color0='red';
print '<span style="color:'.$color0.'; font-weight:bold;">'.$_REQUEST['message'].'</span><br /><br />'; 
}
?>
</td></tr></table>
<?php
if(isset($_GET['id'])){
	$j=array_search($_GET['id'],$di_id);
	$district_t=$di_name[$j];
	$increment_t=$di_increment[$j];
}else{
	$district_t=$increment_t='';
}
?>
	<form action="index.php?components=inventory&action=update_districtprice" onsubmit="return validateDisSpecial()" method="post" >
	<?php if(isset($_GET['id'])) print '<input type="hidden" name="id" value="'.$_GET['id'].'" />'; ?>
	<table align="center" bgcolor="#E5E5E5" style="font-size:11pt;">
	<tr><td colspan="4" height="20px"></td></tr>
	<tr><td width="30px" height="30px"></td><td><strong>District</strong></td><td><input type="text" disabled="disabled"  style="width:200px" value="<?php print $district_t; ?>" /></td><td width="30px"></td></tr>
	<tr><td width="30px"  height="30px"></td><td><strong>Increment</strong></td><td><input type="text" id="increment" name="increment"  style="width:70px" value="<?php print $increment_t; ?>" /></td><td width="30px"></td></tr>
	<tr><td colspan="4" height="20px" align="center">
	<?php
	if($district_t!='') print '<input type="submit" value="Update" style="width:80px; height:40px" /> &nbsp;&nbsp;&nbsp;';
	?>
	</td></tr>
	<tr><td colspan="4" height="20px"></td></tr>
	</table>
	</form>