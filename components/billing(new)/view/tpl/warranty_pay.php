	<form action="index.php?components=billing&action=set_warranty_pay" method="post" >
	<input type="hidden" name="id" value="<?php print $_GET['id']; ?>" />
	<input type="hidden" name="itemid" id="itemid" value="0" />
	<input type="hidden" name="pay_amount" id="pay_amount" value="<?php print $pay_extra; ?>" />
	<table border="0" style="font-size:10pt" align="center">
	<tr><td align="center" colspan="2">&nbsp;</td></tr>
	<tr bgcolor="#EFEFEF"><td align="left" class="shipmentTB4">Payment Amount</td><td class="shipmentTB4"><input type="text" disabled="disabled" value="<?php print $pay_extra; ?>" style="padding-right:10px; text-align:right; width:80px" /> </td></tr>
	<tr bgcolor="#FAFAFA"><td align="left" class="shipmentTB4">New Warranty Duration</td><td class="shipmentTB4"><input type="text" name="w_duration" id="w_duration" value="" style="padding-right:10px; text-align:right; width:80px" /> Days</td></tr>
	<tr><td align="center" colspan="2">&nbsp;</td></tr>
	<tr><td align="center" colspan="2"><div id="validate_div"><input type="submit" value="Submit" style="width:100px; height:30px" /></div></td></tr>
	<tr><td align="center" colspan="2" height="200px" valign="top">
	</td></tr>
	</table>
	</form>