	<table align="center" width="400px">
	<tr style="background-color:#467898;color :white;"><th colspan="2">Employee Loan</th></tr>
	<tr><td colspan="2" align="center"><br /></td></tr>
	<form action="index.php?components=fin&action=edit_loan" method="post" onsubmit="return validateLoan()">
		<tr bgcolor="#EEEEEE"><td class="shipmentTB4">Loan ID</td><td><input type="text" style="width:170px" value="<?php print str_pad($_GET['id'], 7, "0", STR_PAD_LEFT); ?>" disabled="disabled" /><input type="hidden" name="loan_id" value="<?php print $_GET['id']; ?>" /></td></tr>
		<tr bgcolor="#EEEEEE"><td class="shipmentTB4">Employee</td><td><input type="text" name="emp_name" style="width:170px" value="<?php print ucfirst($one_emp); ?>" disabled="disabled" /></td></tr>
		<tr bgcolor="#EEEEEE"><td class="shipmentTB4">Loan Amount</td><td><input type="text" name="amount" id="amount" style="width:170px; text-align:right" value="<?php print number_format($one_amount,2); ?>" disabled="disabled" /></td></tr>
		<tr bgcolor="#EEEEEE"><td class="shipmentTB4">Rate</td><td><input type="number" name="rate" id="rate" style="width:170px; text-align:right" value="<?php print $one_rate; ?>" disabled="disabled" /></td></tr>
		<tr bgcolor="#EEEEEE"><td class="shipmentTB4">Start Date</td><td><input type="date" name="start_date" id="start_date" style="width:170px" value="<?php print $one_start; ?>" disabled="disabled" /></td></tr>
		<tr bgcolor="#EEEEEE"><td class="shipmentTB4">Duration (months)</td><td><input type="number" name="duration" id="duration" style="width:170px; text-align:right" value="<?php print $one_duration; ?>" /></td></tr>
		<tr bgcolor="#EEEEEE"><td class="shipmentTB4">End Date</td><td><input type="date" name="end_date" id="end_date" style="width:170px" value="<?php print $one_end; ?>" disabled="disabled" /></td></tr>
		<tr bgcolor="#EEEEEE"><td class="shipmentTB4">Total Return</td><td><input type="text" id="totalreturn" style="width:170px; text-align:right" disabled="disabled" value="<?php print number_format($one_total_return,2); ?>" /></td></tr>
		<tr bgcolor="#EEEEEE"><td class="shipmentTB4">Installment</td><td><input type="text" id="installment" style="width:170px; text-align:right" disabled="disabled" value="<?php print number_format($one_installment,2); ?>" /></td></tr>
		<tr bgcolor="#EEEEEE"><td class="shipmentTB4">Remaining Capital</td><td><input type="text" id="remaining_cap" style="width:170px; text-align:right" disabled="disabled" value="<?php print number_format($one_amount-$one_pay_capital,2); ?>" /></td></tr>
		<tr bgcolor="#EEEEEE"><td class="shipmentTB4">Remaining Total</td><td><input type="text" id="remaining" style="width:170px; text-align:right" disabled="disabled" value="<?php print number_format($one_total_return-array_sum($one_pay_amount),2); ?>" /></td></tr>
		<tr bgcolor="#EEEEEE"><td class="shipmentTB4">Loan Status</td><td><input type="text" id="remaining" style="width:170px; text-align:right" disabled="disabled" value="<?php print $one_status; ?>" /></td></tr>
		<tr bgcolor="#EEEEEE"><td class="shipmentTB4">From Account</td><td><input type="text" id="remaining" style="width:170px; text-align:right" disabled="disabled" value="<?php print $one_from_account; ?>" /></td></tr>
		<?php if($one_status=='Pending'){	?>
		<tr bgcolor="#EEEEEE"><td colspan="2" align="center"><input type="button" value="Calculate" style="height:50px" onclick="calculate()" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" value="Update Loan" style="height:50px" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="Delete" style="height:50px; background-color:maroon; color:white" onclick="deleteLoan(<?php print $_GET['id']; ?>)" /></td></tr>
	<?php } ?>
	</form>
	<?php if($one_status=='Approved'){	?>
	<form action="index.php?components=fin&action=grant_loan" method="post" onsubmit="return validateGrantLoan()">
		<input type="hidden" name="id" value="<?php print $_GET['id']; ?>" />
		<tr bgcolor="#EEEEEE"><td colspan="2" align="center"><span style="font-size:12; font-weight:bold">Pay Account</span>
		<select name="from_account" id="from_account">
			<option value="">-SELECT-</option>
			<?php for($i=0;$i<sizeof($fromac_id);$i++){
			print '<option value='.$fromac_id[$i].'>'.$fromac_name[$i].'</option>';
			} ?>
		</select>
		<input type="Submit" value="Grant Loan" style="height:50px; background-color:green; color:white" onclick="" /></td></tr>
	</form>
	<?php } ?>
	</table>
<br />
	<table align="center" width="400px">
	<tr style="background-color:#467898;color :white;"><th class="shipmentTB3">Payment Date</th><th class="shipmentTB3">Related Payroll</th><th class="shipmentTB3">Amount</th></tr>
	<?php for($i=0;$i<sizeof($one_pay_date);$i++){
		if(($i%2)==0) $color='#FAFAFA'; else $color='#EEEEEE';
		if($one_pay_payroll[$i]!=0) $payroll_id=str_pad($one_pay_payroll[$i], 7, "0", STR_PAD_LEFT); else $payroll_id='';
		print '<tr bgcolor="'.$color.'"><td class="shipmentTB3" align="center">'.$one_pay_date[$i].'</td><td class="shipmentTB3" align="center">'.$payroll_id.'</td><td class="shipmentTB3" align="right">'.number_format($one_pay_amount[$i]).'</td></tr>';
	} 
	if($one_status=='Granted'){	?>
		<form action="index.php?components=fin&action=pay_loan" method="post">
		<input type="hidden" name="loan_id" value="<?php print $_GET['id']; ?>" />
		<tr bgcolor="#DDDDCA"><td class="shipmentTB3" align="center">Settle<br>Loan</td><td align="center"><input type="number" name="custom_pay" style="width:100px; text-align:right" readonly="readonly" value="<?php print $payoff_value; ?>" /></td><td align="center"><input type="submit" value="Pay" style="width:100%; height:40px" /></td></tr>
		</form>
	<?php } ?>
	</table>