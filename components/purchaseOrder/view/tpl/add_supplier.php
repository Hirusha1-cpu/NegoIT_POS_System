	<form action="index.php?components=purchase_order&action=add_supplier" onsubmit="return validateSup()" method="post" >
	<table align="center" bgcolor="#E5E5E5" style="font-size:11pt;">
	<tr><td colspan="4"><br /></td></tr>
	<tr><td width="50px"></td><td>Supplier Name&nbsp;&nbsp;</td><td><input type="text" name="sup_name" id="sup_name" /></td><td width="50px"></td></tr>
	<tr><td width="50px"></td><td>Email</td><td><input type="text" name="email" id="email" /></td><td width="50px"></td></tr>
	<tr><td width="50px"></td><td>Tel 1</td><td><input type="text" name="tel1" id="tel1" /></td><td width="50px"></td></tr>
	<tr><td width="50px"></td><td>Tel 2</td><td><input type="text" name="tel2" id="tel2" /></td><td width="50px"></td></tr>
	<tr><td width="50px"></td><td>Address</td><td><textarea name="address" id="address" style="width:97%"></textarea></td><td width="50px"></td></tr>
	<tr><td width="50px"></td><td>Country</td><td>
		<select id="country" name="country">
		<option value="">-SELECT-</option>
		<option value="Sri Lanka">Sri Lanka</option>
		<option value="India">India</option>
		<option value="China">China</option>
		</select>
	</td></tr>
	<tr><td width="50px"></td><td colspan="2">Post Discount/Commission Fasility : <input type="checkbox" name="dis" /></td></tr>
	<tr><td width="50px"></td><td colspan="2"><hr /></td><td width="50px"></td></tr>
	<tr><td colspan="4" align="center"><br /><input type="submit" value="Add Supplier" style="width:130px; height:50px" /><br /><br /></td></tr>
	</table>
	</form>
