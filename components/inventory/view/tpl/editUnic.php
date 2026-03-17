	<div id="loading" style="display:none"><img src="images/loading.gif" style="width:70px" /><br><span style="color:maroon; vertical-align:middle">Please Wait</span></div>
	<form action="index.php?components=inventory&action=edit_unic&shipment_no=<?php print $_GET['shipment_no']; ?>" onsubmit="return validateUnic()" method="post" >
	<input type="hidden" name="ins_id" value="<?php print $_GET['ins_id']; ?>" />
	<input type="hidden" name="oldsn" value="<?php print $_GET['sn']; ?>" />
	<table align="center" bgcolor="#E5E5E5" style="font-size:11pt; font-family:Calibri">
	<tr><td colspan="4" height="20px"></td></tr>
	<tr><td width="30px" height="30px"></td><td><strong>Item SN</strong></td><td><input type="text" name="newsn" id="newsn" style="width:200px" value="<?php print $_GET['sn']; ?>" /></td><td width="30px"></td></tr>
	<tr><td colspan="4" height="20px" align="center"><table><tr>
	<td><div id="div_update"><input type="submit" value="Update" style="width:80px; height:40px" /> &nbsp;&nbsp;&nbsp;</div></td>
	<td><div id="div_delete"><input type="Button" value="Delete" style="width:55px; height:40px; color:white; background-color:maroon;" onclick="deleteUnic('<?php print $_GET['shipment_no']; ?>','<?php print $_GET['ins_id']; ?>','<?php print $_GET['sn']; ?>')" /></div></td>
	</tr></table></td></tr>
	<tr><td colspan="4" height="20px"></td></tr>
	</table>
	</form>