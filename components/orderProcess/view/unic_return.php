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
<?php 
	if(isset($_REQUEST['id'])) $id=$_REQUEST['id']; else $id=0;
?>

<table width="100%">
<tr><td align="center"><?php 
	if(isset($_REQUEST['message'])){
		if($_REQUEST['re']=='success') $color='green'; else $color='red';
	print '<span style="color:'.$color.'; font-weight:bold;font-size:12pt;">'.$_REQUEST['message'].'</span>'; 
	}
	?></td></tr>
</table>

<form action="#" method="post" >
	<table align="center" style="font-size:12pt">
	<tr style="background-color:#C0C0C0"><td style="padding-left:20px; color:navy" colspan="3"><strong>Returned Item :</strong> &nbsp;&nbsp;&nbsp;<?php print $rt_itmdesc; ?></td></tr>
	<tr><td colspan="3" height="10px"></td></tr>
	<tr style="background-color:#C0C0C0"><th style="padding-left:20px; padding-right:20px" width="300px">Unic ID</th><th></th><th></th></tr>
	<?php
	$item=$_GET['item'];
	for($i=0;$i<sizeof($rt_id);$i++){
		print '<tr style="background-color:#F0F0F0"><td style="padding-left:20px; padding-right:20px;">'.$itu_sn[$i].'</td><td><input  type="button" value="Moved Inventory" onclick="window.location = '."'index.php?components=".$components."&action=move_unic_inv&item=$item&id=$rt_id[$i]'".'" /></td><td><input  type="button" value="Moved Disposal" onclick="window.location = '."'index.php?components=".$components."&action=move_unic_dis&item=$item&id=$rt_id[$i]'".'" /></td></tr>';
	}
	?>
	</table>
</form>

<?php
                include_once  'template/footer.php';
?>