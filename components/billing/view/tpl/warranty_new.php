	<table width="100%" border="0" style="font-size:10pt">
	<tr><td align="center" colspan="2">&nbsp;</td></tr>
	<tr><td align="right" width="350px">SN <input type="text" name="sn" id="sn" value="" /> </td><td><div id="validate_div"><input type="button" value="Validate" style="width:100px; height:30px" onclick="validateWarranty()" /></div></td></tr>
	<tr><td align="center" colspan="2">&nbsp;</td></tr>
	<tr><td align="center" colspan="2" height="300px" valign="top">
	<form action="index.php?components=billing&action=warranty_submit" method="post">
	<input type="hidden" id="sn_send" name="sn" value="" />
	<table width="90%" align="center" >
		<tr><td class="shipmentTB3" bgcolor="EDEDED" style="color:#4678bb; font-weight:bold">Customer</td><td class="shipmentTB3" bgcolor="F5F5F5" style="color:maroon"><div id="div_cust"></div></td><td width="25px"></td><td class="shipmentTB3" bgcolor="EDEDED" style="color:#4678bb; font-weight:bold">Item</td><td class="shipmentTB3" bgcolor="F5F5F5" style="color:maroon"><div id="div_item"></div></td></tr>
		<tr><td class="shipmentTB3" bgcolor="EDEDED" style="color:#4678bb; font-weight:bold">Supplier</td><td class="shipmentTB3" bgcolor="F5F5F5" style="color:maroon"><div id="div_sup"></div></td><td width="25px"></td><td class="shipmentTB3" bgcolor="EDEDED" style="color:#4678bb; font-weight:bold">Purchased Date</td><td class="shipmentTB3" bgcolor="F5F5F5" style="color:maroon"><div id="div_bmdate"></div></td></tr>
		<tr><td class="shipmentTB3" bgcolor="EDEDED" style="color:#4678bb; font-weight:bold">Invoice No</td><td class="shipmentTB3" bgcolor="F5F5F5" style="color:maroon"><div id="div_inv"></div></td><td></td><td class="shipmentTB3" bgcolor="EDEDED" style="color:#4678bb; font-weight:bold">Months Up to Now</td><td class="shipmentTB3" bgcolor="F5F5F5" style="color:maroon"><div id="div_uptonow"></div></td></tr>
		<tr><td class="shipmentTB3" bgcolor="EDEDED" style="color:#4678bb; font-weight:bold"><div id="div_price">Sold Price</div></td><td class="shipmentTB3" bgcolor="F5F5F5" style="color:maroon"><div id="div_price"></div></td><td></td><td class="shipmentTB3" bgcolor="EDEDED" style="color:#4678bb; font-weight:bold">Previous Claims</td><td class="shipmentTB3" bgcolor="F5F5F5" style="color:maroon" ><div id="div_claim"></div></td></tr>
		<tr><td colspan="5" align="center">&nbsp;</td></tr>
		<tr><td colspan="5" align="center"><textarea name="issue" placeholder="Please Describe the Issue" cols="70"></textarea></td></tr>
		<tr><td colspan="5" align="center">&nbsp;</td></tr>
		<tr><td colspan="5" align="center"><div id="submit_div"></div></td></tr>
	</table>
	</form>
	</td></tr>
	</table>