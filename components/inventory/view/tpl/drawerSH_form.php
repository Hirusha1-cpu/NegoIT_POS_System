<form action="index.php" method="get"  >
<input type="hidden" name="components" value="<?php print $_GET['components']; ?>" />
<input type="hidden" name="action" value="drawer_search" />
<table align="center" bgcolor="#E5E5E5" style="font-size:11pt; font-family:Calibri">
<tr><td colspan="4"><?php 
if(isset($_REQUEST['message'])){
	if($_REQUEST['re']=='success') $color='green'; else $color='red';
print '<span style="color:'.$color.'; font-weight:bold;font-size:12pt;">'.$_REQUEST['message'].'</span>'; 
}
if(isset($_GET['shipment_no'])) $shipment_no=$_GET['shipment_no']; else $shipment_no=0;

?>
<br /></td></tr>
<tr><td width="50px"></td><td style="font-size:12pt"><strong>Store</strong></td><td style="font-size:12pt">
	<select name="st" name="st">
	<?php 
		$st_name='';
		for($i=0;$i<sizeof($stores_id);$i++){
		if($store==$stores_id[$i]){
			$select='selected="selected"'; 
			$st_name=$stores_name[$i];
		}else{ $select=''; }
		print '<option value="'.$stores_id[$i].'" '.$select.'>'.$stores_name[$i].'</option>';
	} ?>
	</select>
</td><td width="50px"><br /><br /><br /></td></tr>
<tr><td width="50px"></td><td style="font-size:12pt"><strong>Drawer Code</strong></td><td><input type="text" name="drawer" id="drawer" style="width:100px" value="<?php print $drawer; ?>" /></td><td width="50px"></td></tr>
<tr><td colspan="4" align="center"><br /><input type="submit" value="Search" style="width:130px; height:50px" /><br /><br /></td></tr>
</table>
</form>