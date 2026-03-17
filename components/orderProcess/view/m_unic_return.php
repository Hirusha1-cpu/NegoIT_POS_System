<?php
                include_once  'template/m_header.php';
?>
<!-- ------------------------------------------------------------------------------------ -->
<?php 
	if(isset($_REQUEST['id'])) $id=$_REQUEST['id']; else $id=0;
?>
	<style type="text/css">
	.style2 {
		color: navy;
		font-weight: bold;
		background-color:#EEEEEE;
	}
	</style>
</head>

<div class="w3-container" style="margin-top:75px">
<?php 
	if(isset($_REQUEST['message'])){
		if($_REQUEST['re']=='success') $color='green'; else $color='red';
	print '<span style="color:'.$color.'; font-weight:bold;font-size:large;">'.$_REQUEST['message'].'</span>'; 
	}
?>	

<hr>
<div class="w3-row">
  <div class="w3-col s3">
  </div>
  <div class="w3-col">
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
  </div>
</div>
</div>
<hr>


<?php
                include_once  'template/m_footer.php';
?>