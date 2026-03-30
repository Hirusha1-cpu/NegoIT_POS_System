<?php
                include_once  'template/header.php';
?>
	<link rel="stylesheet" href="plugin/jquery/css/demos.css" />
<!-- ------------------------------------------------------------------------------------------------------ -->
<div id="loading" style="display:none"><img src="images/loading.gif" style="width:25px" /></div>

<table align="center" style="font-size:12pt"><tr><td>
<?php 
	if(isset($_REQUEST['message'])){
		if($_REQUEST['re']=='success') $color='green'; else $color='red';
	print '<span style="color:'.$color.'; font-weight:bold;">'.$_REQUEST['message'].'</span>'; 
	}
?></td></tr></table>
<p align="center" style="color:#467898; font-size:14pt">Create New Purchase Order</p>
<form action="index.php?components=purchase_order&action=create_po" method="post">
	<table align="center" border="0"  style="font-size:12pt" bgcolor="#EEEEEE">
	<tr><td width="100px"></td><td>Suplier</td><td>
	<select name="supplier" id="supplier">
		<option value="" >-SELECT-</option>
	<?php for($i=0;$i<sizeof($su_id);$i++){
		if($su_status[$i]==1)
		print '<option value="'.$su_id[$i].'" >'.$su_name[$i].'</option>';
	}?>
	</select>
	</td><td width="50px"></td><td><input type="submit" value="Create PO" style="height:40px; width:100px;" /></td><td width="100px"></td></tr>
	</table>
</form>
<table><tr height="300px"><td></td></tr></table>

<?php
                include_once  'template/footer.php';
?>