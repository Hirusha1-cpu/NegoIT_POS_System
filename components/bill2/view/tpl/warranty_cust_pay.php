	<form action="index.php?components=bill2&action=set_warranty_cust_pay" method="post" >
	<input type="hidden" name="id" value="<?php print $_GET['id']; ?>" />
	<input type="hidden" name="itemid" id="itemid" value="0" />
	<table border="0" style="font-size:10pt" align="center">
	<tr><td align="center" colspan="2">&nbsp;</td></tr>
	<tr bgcolor="#EFEFEF"><td align="left" class="shipmentTB4">Suplier Paid Amount</td><td class="shipmentTB4"><input type="text" disabled="disabled" value="<?php print $sup_paid; ?>" style="padding-right:10px; text-align:right; width:80px" /> </td></tr>
	<tr bgcolor="#EFEFEF"><td align="left" class="shipmentTB4">Customer Payment Amount</td><td class="shipmentTB4"><input type="text" name="pay" value="" style="padding-right:10px; text-align:right; width:80px"  /> </td></tr>
	<tr><td align="center" colspan="2">&nbsp;</td></tr>
	<tr><td align="center" colspan="2"><div id="validate_div"><input type="submit" value="Submit" style="width:100px; height:30px" /></div></td></tr>
	<tr><td align="center" colspan="2" height="200px" valign="top">
	</td></tr>
	</table>
	</form>