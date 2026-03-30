<?php
                include_once  'template/header.php';
?>
<!-- ------------------Item List----------------------- -->
<table align="center" style="font-size:11pt"><tr><td>
<?php 
	if(isset($_REQUEST['message'])){
		if($_REQUEST['re']=='success') $color='green'; else $color='red';
	print '<span style="color:'.$color.'; font-weight:bold;">'.$_REQUEST['message'].'</span><br /><br />'; 
	}
?></td></tr></table>

<table align="center" style="font-family:Calibri; border-radius: 15px;" bgcolor="#EEEEEE" width="400px">
<tr><td height="30px"></td></tr>
<tr><td align="center">
<select name="dev_id" id="dev_id" style="font-size:12pt">
<option value="">-SELECT-</option>
<?php for($i=0;$i<sizeof($dev_id);$i++){
	print '<option value="'.$dev_id[$i].'">'.$dev_name[$i].'</option>';
} ?>
</select>
</td></tr>
<tr><td align="center"><input type="button" onclick="registerDevice('add')" value="Register Device" style="width:200px; height:70px; background-color:green; color:white; font-weight:bold; border-radius: 15px;" /></td></tr>
<tr><td align="center"><input type="button" onclick="registerDevice('remove')" value="Unregister Device" style="width:200px; height:70px; background-color:maroon; color:white; font-weight:bold; border-radius: 15px;" /></td></tr>
<tr><td height="30px"></td></tr>
<?php if(isset($_COOKIE['rsaid'])){ ?>
<tr><td height="30px" align="center" style="color:navy">Currently This device is registered as "<strong><?php print $key_dev_name; ?></strong>"</td></tr>
<tr><td height="30px"></td></tr>
<?php } ?>
</table>
<?php
                include_once  'template/footer.php';
?>