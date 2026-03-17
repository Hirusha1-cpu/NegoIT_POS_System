<script>
	function showBankFeeField() {
		$bank_ac = document.getElementById("bank_ac");
		$b_p_tr = document.getElementById("b_p_tr");
		$b_p_up_tr = document.getElementById("b_p_up_tr");
		if ($bank_ac.checked == true) {
			$b_p_tr.style.display = "table-row";
			$b_p_up_tr.style.display = "table-row";
		} else {
			$b_p_tr.style.display = "none";
			$b_p_up_tr.style.display = "none";
		}
	}
	function validateCAccount() {
		var count = 0;
		var msg = "All fields must be filled out";
		var subAcChecked = document.getElementById('sub_ac').checked;
		var categoryL1Value = document.getElementById('category_l1').value;
		var acNameValue = document.getElementById('ac_name').value;
		var parentAccountSelectValue = document.getElementById('parent_account_select').value;

		// Check if required fields are empty
		if (categoryL1Value == '') {
			count++;
			msg = "Category Level 1 must be select out."
		}
		if (acNameValue == '') {
			msg = "Account name must be filled out."
			count++;
		}

		// Additional check if checkbox is checked
		if (subAcChecked && parentAccountSelectValue == '') {
			count++;
			msg = "Parent account must be selected when Sub Account checkbox is checked";
		}

		// Display alert if there are errors
		if (count != 0) {
			alert(msg);
			return false;
		}

		// Form is valid
		return true;
	}
</script>

<form action="index.php?components=fin&action=add_chart_of_accounts" method="post" onsubmit="return validateCAccount()">
	<table align="center" border="0" bgcolor="#EEEEEE" width="500px" style="font-family:Calibri">
		<tr>
			<td width="50px" height="30px"></td>
			<td></td>
			<td></td>
			<td width="50px"></td>
		</tr>
		<tr>
			<td height="40px"></td>
			<td colspan="2" align="center" style="font-size:14pt; color:navy; font-weight:bold">New Account Creation
			</td>
			<td></td>
		</tr>
		<tr>
			<td width="50px" height="20px"></td>
			<td></td>
			<td></td>
			<td width="50px"></td>
		</tr>
		<tr>
			<td></td>
			<td><strong>Main Category</strong></td>
			<td>
				<select name="category_l1" id="category_l1" onchange="L2CategorySelection(this.value)"
					style="width:150px">
					<option value="">-SELECT-</option>
					<?php for ($i = 0; $i < sizeof($category_l1); $i++) {
						print '<option value="' . $category_l1[$i] . '">' . $category_l1[$i] . '</option>';
					} ?>
				</select>
			</td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td bgcolor="#FAFAFA" colspan="2" height="1px"></td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td><strong>Category Level 2</strong></td>
			<td>
				<div id="category_l2_div"></div>
			</td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td bgcolor="#FAFAFA" colspan="2" height="1px"></td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td><strong>Category Level 3</strong></td>
			<td>
				<div id="category_l3_div"></div>
			</td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td bgcolor="#FAFAFA" colspan="2" height="1px"></td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td><strong>Account Name</strong></td>
			<td><input type="text" name="ac_name" id="ac_name" style="width:150px" /></td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td bgcolor="#FAFAFA" colspan="2" height="1px"></td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td><strong>Payment Account</strong></td>
			<td><input type="checkbox" name="payment_ac" /></td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td bgcolor="#FAFAFA" colspan="2" height="1px"></td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td><strong>Bank Account</strong></td>
			<td><input type="checkbox" name="bank_ac" id="bank_ac" onclick="showBankFeeField()" /></td>
			<td></td>
		</tr>
		<!-- Subaccount Checkbox -->
		<tr>
			<td></td>
			<td><strong>Make this a Subaccount</strong></td>
			<td><input type="checkbox" name="sub_ac" id="sub_ac" /></td>
			<td></td>
		</tr>
		<!-- Parent Account -->
		<tr id="parentAccountRow" style="display:none;">
			<td></td>
			<td><strong>Parent Account</strong></td>
			<td><select id="parent_account_select" name="parent_account">
					<option value="">Select Parent Account</option>
				</select>
			</td>
			<td></td>
		</tr>
		<tr id="b_p_up_tr" style="display:none;">
			<td></td>
			<td bgcolor="#FAFAFA" colspan="2" height="1px"></td>
			<td></td>
		</tr>
		<tr id="b_p_tr" style="display:none;">
			<td></td>
			<td><strong>Bank Processing Fee</strong></td>
			<td><input type="text" name="bank_fee" id="bank_fee" style="width:130px" /><span
					style="padding-left:7px;">%</span></td>
			<td></td>
		</tr>
		<tr>
			<td width="50px" height="20px"></td>
			<td></td>
			<td></td>
			<td width="50px"></td>
		</tr>
		<tr>
			<td height="40px"></td>
			<td colspan="2" align="center"><input type="submit" value="Create Account"
					style="width:150px; height:50px" /></td>
			<td></td>
		</tr>
		<tr>
			<td height="40px"></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
	</table>
</form>
<script>
	// JavaScript to toggle the display of the row based on checkbox
	document.getElementById("sub_ac").addEventListener("change", function () {
		var parentRow = document.getElementById("parentAccountRow");
		if (this.checked) {
			parentRow.style.display = ""; // Show row
		} else {
			parentRow.style.display = "none"; // Hide row
		}
	});
</script>