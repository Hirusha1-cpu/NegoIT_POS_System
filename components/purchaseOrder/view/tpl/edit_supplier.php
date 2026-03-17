	<form action="index.php?components=purchase_order&action=update_supplier" onsubmit="return validateSup()" method="post" >
	<input type="hidden" name="sup_id" value="<?php print $sup_id; ?>" />
	<table align="center" bgcolor="#E5E5E5" style="font-size:11pt;">
	<tr><td colspan="4"><br /></td></tr>
	<tr><td width="50px"></td><td>Supplier Name&nbsp;&nbsp;</td><td><input type="text" name="sup_name" id="sup_name" value="<?php print $sup_name; ?>" /></td><td width="50px"></td></tr>
	<tr><td width="50px"></td><td>Email</td><td><input type="text" name="email" id="email" value="<?php print $sup_email; ?>" /></td><td width="50px"></td></tr>
	<tr><td width="50px"></td><td>Tel 1</td><td><input type="text" name="tel1" id="tel1" value="<?php print $sup_tel1; ?>" /></td><td width="50px"></td></tr>
	<tr><td width="50px"></td><td>Tel 2</td><td><input type="text" name="tel2" id="tel2" value="<?php print $sup_tel1; ?>" /></td><td width="50px"></td></tr>
	<tr><td width="50px"></td><td>Address</td><td><textarea name="address" id="address" value="<?php print $sup_address; ?>" style="width:97%"></textarea></td><td width="50px"></td></tr>
	<tr><td width="50px"></td><td>Country</td><td>
		<select id="country" name="country">
		<option <?php if($sup_country=='Sri Lanka') print 'selected="selected"'; ?> value="Sri Lanka">Sri Lanka</option>
		<option <?php if($sup_country=='India') print 'selected="selected"'; ?> value="India">India</option>
		<option <?php if($sup_country=='China') print 'selected="selected"'; ?> value="China">China</option>
		</select>
	</td></tr>
	<tr><td width="50px"></td><td colspan="2"><hr /></td><td width="50px"></td></tr>
	<tr><td colspan="4" align="center"><br />
	<?php if($sup_status==1){ ?><input type="submit" value="Update Supplier" style="width:130px; height:50px" /><input type="button" onclick="disableSup(<?php print $sup_id; ?>)" value="Deactivate" style="width:80px; height:50px; background-color:maroon; color:white; font-weight:bold" /><?php } ?>
	<?php if($sup_status==0){ ?><input type="button" onclick="enableSup(<?php print $sup_id; ?>)" value="Activate" style="width:80px; height:50px; background-color:green; color:white; font-weight:bold" /><?php } ?>
	<br /><br /></td></tr>
	</table>
	</form>