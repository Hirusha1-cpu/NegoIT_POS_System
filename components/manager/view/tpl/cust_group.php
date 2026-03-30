	<table align="center" bgcolor="#E5E5E5" style="font-size:11pt; font-family:Calibri" border="0">
	<?php if($_GET['action']=='show_custgroup'){ ?>
	<form method="post" action="index.php?components=<?php print $components; ?>&action=add_custgroup">
	<tr height="50px"><td width="50px"></td><td>New Group</td><td><input type="text" name="name" id="name" /></td><td><input type="submit" value="Add" /></td><td width="50px"></td></tr>
	</form>
	<?php }if($_GET['action']=='edit_custgroup'){ 
		$a=array_search($_GET['id'],$gp_id);
	?>
	<form method="post" action="index.php?components=<?php print $components; ?>&action=update_custgroup">
	<input type="hidden" name="group_id" value="<?php print $_GET['id']; ?>" />
	<tr height="50px"><td width="50px"></td><td>Group Name</td><td><input type="text" name="name" id="name" value="<?php print $gp_name[$a]; ?>" /></td><td><input type="submit" value="Update" /></td><td width="50px"></td></tr>
	</form>
	<?php } ?>
	<?php for($i=0;$i<sizeof($gp_id);$i++){
		print '<tr><td bgcolor="#F1F1F1" colspan="3" style="padding-left:50px; padding-right:10px;">'.$gp_name[$i].'</td><td bgcolor="#F1F1F1" style="padding-left:20px; padding-right:40px;" colspan="2"><a href="index.php?components='.$components.'&action=edit_custgroup&id='.$gp_id[$i].'"><img src="images/edit.gif" /></a><a href="index.php?components='.$components.'&action=delete_custgroup&id='.$gp_id[$i].'"><img src="images/action_delete.gif" /></a></td></tr>';
	} ?>
	</table>