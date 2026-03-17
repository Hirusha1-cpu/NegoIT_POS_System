<form action="index.php?components=fin&action=new_loan" method="post" onsubmit="return validateLoan()">
	<table align="center" width="400px">
	<tr style="background-color:#467898;color :white;"><th colspan="2">Submit a New Loan</th></tr>
	<tr><td colspan="2" align="center"><br /></td></tr>
	<tr bgcolor="#EEEEEE"><td class="shipmentTB4">Employee</td><td><input type="text" name="emp_name" id="emp_name" style="width:170px" /></td></tr>
	<tr bgcolor="#EEEEEE"><td class="shipmentTB4">Loan Amount</td><td><input type="number" name="amount" id="amount" style="width:170px; text-align:right" /></td></tr>
	<tr bgcolor="#EEEEEE"><td class="shipmentTB4">Rate</td><td><input type="text" name="rate" id="rate" style="width:160px; text-align:right; padding-right:10px" /></td></tr>
	<tr bgcolor="#EEEEEE"><td class="shipmentTB4">Start Date</td><td><input type="date" name="start_date" id="start_date" style="width:170px" /></td></tr>
	<tr bgcolor="#EEEEEE"><td class="shipmentTB4">Duration (months)</td><td><input type="number" name="duration" id="duration" style="width:170px; text-align:right" /></td></tr>
	<tr bgcolor="#EEEEEE"><td class="shipmentTB4">Total Return</td><td><input type="number" id="totalreturn" style="width:170px; text-align:right" disabled="disabled" /></td></tr>
	<tr bgcolor="#EEEEEE"><td class="shipmentTB4">Installment</td><td><input type="number" id="installment" style="width:170px; text-align:right" disabled="disabled" /></td></tr>
	<tr bgcolor="#EEEEEE"><td colspan="2" align="center"><input type="button" value="Calculate" style="height:50px" onclick="calculate()" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" value="Submit Loan" style="height:50px" /></td></tr>
	</table>
</form>