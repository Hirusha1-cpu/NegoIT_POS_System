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
		var availableTags1 = [<?php for ($x=0;$x<sizeof($usr_name);$x++){ print '"'.$usr_name[$x].'",'; } ?>	];
		$( "#user" ).autocomplete({
			source: availableTags1
		});
	});
	</script>

<table align="center" border="0"><tr><td>
<?php 
if(isset($_REQUEST['message'])){
	if($_REQUEST['re']=='success') $color='green'; else $color='red';
print '<span style="color:'.$color.'; font-weight:bold; font-size:12pt">'.$_REQUEST['message'].'</span><br />'; 
}
?>
</td></tr></table>
<table align="center" width="900px" cellspacing="0" ><tr style="background-color:#EEEEEE; font-family:Calibri; font-weight:bold; color:#467898; font-size:12pt"><td align="center"><form action="index.php?components=settings&action=device_grid" method="post">User <input type="text" name="user_name" id="user" /> <input type="submit" value="Search" /></form></td><td width="180px" align="right"><input type="button" value="Permission List" style="width:150px; height:40px" onclick="window.location = 'index.php?components=settings&action=devices'" /></td></tr></table>
<br />

<?php if($user_id!=''){ ?>
<form method="post" action="index.php?components=settings&action=addpermission_grid">
<input type="hidden" name="grid_user_id" value="<?php print $user_id; ?>" />
	<table align="center" style="font-family:Calibri; font-size:12pt" >
	<tr style="background-color:#467898; font-family:Calibri; font-weight:bold; color:white; font-size:12pt"><td align="center" colspan="6"><span style="color:#DDDDDD">User:</span> &nbsp;&nbsp;&nbsp; <?php print ucfirst($user_name); ?> </td></tr>
	<?php 
	$j=0;
	$color='#FAFAFA';
	$count=1;
	print '<tr style="background-color:'.$color.'">';
	for($i=0;$i<sizeof($dev_id);$i++){
		$key=array_search($dev_id[$i],$dp_id);
		if($key>-1) $enable='checked="checked"'; else  $enable='';
		print '<td class="shipmentTB3"><table width="100%"><tr><td><strong>'.$dev_name[$i].' &nbsp;&nbsp;</strong></td><td width="10px"><input type="checkbox" name="per_'.$dev_id[$i].'"  '.$enable.' /></td></tr></table></td>';
		$count++;
		if($count==6){
			$j++;
			if(($j%2)==0) $color='#FAFAFA'; else $color='#EEEEEE';
			print '</tr><tr style="background-color:'.$color.'">';
			$count=1;
		}
	} 
	print '</tr>';
	?>
	<tr></tr>
	<tr><td align="center" colspan="6"><br /><input type="submit" value="Submit" style="width:100px; height:40px" /></td></tr>
	</table>
</form>
<?php } ?>
<?php
                include_once  'template/footer.php';
?>