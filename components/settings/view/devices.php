<?php
                include_once  'template/header.php';
?>

<table align="center" border="0"><tr><td>
<?php 
if(isset($_REQUEST['message'])){
	if($_REQUEST['re']=='success') $color='green'; else $color='red';
print '<span style="color:'.$color.'; font-weight:bold;">'.$_REQUEST['message'].'</span><br />'; 
}
?>
</td></tr></table>
<table align="center" width="900px" cellspacing="0" ><tr style="background-color:#EEEEEE; font-family:Calibri; font-weight:bold; color:#467898;"><td align="center"></td><td width="180px" align="right"><input type="button" value="Permission Grid" style="width:150px; height:40px" onclick="window.location = 'index.php?components=settings&action=device_grid'" /></td></tr></table>
<br />
<table align="center">
<tr style="font-family:Calibri; font-weight:bold; color:white"><td align="center" bgcolor="#AAAAAA">Device Management</td><td></td><td align="center" bgcolor="#AAAAAA">Permission Management</td></tr>
<tr><td style="vertical-align:top">
<?php if(isset($_GET['dev_id'])){ ?>
	<form method="post" action="index.php?components=settings&action=rename_device"  onsubmit="return validateDevice()" >
	<input type="hidden" name="dev_id" value="<?php print $_GET['dev_id']; ?>" />
	<table bgcolor="#DDDDDD" style="font-family:Calibri">
	<tr><td colspan="4" height="30px"></td></tr>
	<tr><td width="20px"></td><td><strong>Device Name</strong></td><td><input type="text" name="device_name" id="device_name" value="<?php print $one_dev_name; ?>" /> </td><td width="20px"></td></tr>
	<tr><td colspan="4" align="center"><input type="submit" value="Rename" style="width:100px; height:40px" /></td></tr>
	<tr><td colspan="4" height="30px"></td></tr>
	</table>
	</form>
<?php }else{ ?>
	<form method="post" action="index.php?components=settings&action=add_device"  onsubmit="return validateDevice()" >
	<table bgcolor="#DDDDDD" style="font-family:Calibri">
	<tr><td colspan="4" height="30px"></td></tr>
	<tr><td width="20px"></td><td><strong>Device Name</strong></td><td><input type="text" name="device_name" id="device_name" /> </td><td width="20px"></td></tr>
	<tr><td colspan="4" align="center"><input type="submit" value="Create" style="width:100px; height:40px" /></td></tr>
	<tr><td colspan="4" height="30px"></td></tr>
	</table>
	</form>
<?php } ?>
	<hr>
	
	<table width="100%" style="font-family:Calibri">
	<tr style="background-color:#DDDDDD"><th>Device</th><th>Key Expiration</th><th>Action</th></tr>
	<?php for($i=0;$i<sizeof($dev_id);$i++){
	if($dev_status[$i]==1){
		$button='<input type="button" value="Deactivate" onclick="window.location = '."'index.php?components=settings&action=change_device&stat=off&id=$dev_id[$i]'".'" />';
		$color='navy';
	}else{
		$button='<input type="button" value="Activate" onclick="window.location = '."'index.php?components=settings&action=change_device&stat=on&id=$dev_id[$i]'".'" />';
		$color='silver';
	}
	print '<tr style="background-color:#EEEEEE; color:'.$color.';"><td>&nbsp;&nbsp;<a href="index.php?components=settings&action=devices&dev_id='.$dev_id[$i].'">'.$dev_name[$i].'</a></td><td align="center">'.$dev_exp[$i].'</td><td align="center">'.$button.' <input type="button" value="ReKey" style="background-color:#EE5555;" onclick="window.location = '."'index.php?components=settings&action=rekey_device&id=".$dev_id[$i]."'".'" /></td></tr>';
	} ?>
	</table>

</td><td width="50px"></td><td style="vertical-align:top">
	<form method="post" action="index.php?components=settings&action=addpermission_device"  onsubmit="return validatePerDevice()" >
	<table bgcolor="#DDDDDD" style="font-family:Calibri" width="100%">
	<tr><td colspan="6" height="10px"></td></tr>
	<tr><td width="20px"></td><td><strong>Device</strong></td><td width="20px"></td><td><strong>User</strong></td><td rowspan="3" width="20px"><input type="submit" value="Add" style="width:50px; height:40px" /></td><td rowspan="3" width="20px"></td></tr>
	<tr><td width="20px"></td><td><strong>
	<select name="per_dev" id="per_dev">
		<option value="">-SELECT-</option>
	<?php for($i=0;$i<sizeof($dev_id);$i++){
		print '<option value="'.$dev_id[$i].'">'.$dev_name[$i].'</option>';
	} ?>
	</select>
	</strong></td><td></td><td><strong>
	<select name="per_usr" id="per_usr">
		<option value="">-SELECT-</option>
	<?php for($i=0;$i<sizeof($usr_id);$i++){
		print '<option value="'.$usr_id[$i].'">'.$usr_name[$i].'</option>';
	} ?>
	</select>
	</strong></td></tr>
	<tr><td colspan="4" height="10px"></td></tr>
	</table>
	</form>
	
	<hr>	
	
	<table  style="font-family:Calibri">
	<tr style="background-color:#DDDDDD"><th width="150px">Device</th><th width="150px">User</th><th width="50px"></th></tr>
	<?php for($i=0;$i<sizeof($per_id);$i++){
	print '<tr style="background-color:#EEEEEE;"><td>'.$per_dev[$i].'</td><td>'.$per_user[$i].'</td><td align="center"><a href="#" style="color:red; text-decoration:none;" title="Remove Permission" onclick="delPerDevice('."'$per_id[$i]','$per_dev[$i]','$per_user[$i]'".')" ><strong>X</strong></a></td></tr>';
	} ?>
	</table>
</td></tr>
</table>

<?php
                include_once  'template/footer.php';
?>