<script>
	window.addEventListener("load", function(){
		$bank_ac = document.getElementById("bank_ac");
		$b_p_tr = document.getElementById("b_p_tr");
		$b_p_up_tr = document.getElementById("b_p_up_tr");
		if($bank_ac.checked == true){
			$b_p_tr.style.display = "table-row";
			$b_p_up_tr.style.display = "table-row";
		}else{
			$b_p_tr.style.display = "none";
			$b_p_up_tr.style.display = "none";
		}
	});

	function showBankFeeField(){
		$bank_ac = document.getElementById("bank_ac");
		$b_p_tr = document.getElementById("b_p_tr");
		$b_p_up_tr = document.getElementById("b_p_up_tr");
		if($bank_ac.checked == true){
    		$b_p_tr.style.display = "table-row";
    		$b_p_up_tr.style.display = "table-row";
  		}else{
    		$b_p_tr.style.display = "none";
    		$b_p_up_tr.style.display = "none";
  		}
	}
</script>

<form action="index.php?components=fin&action=edit_chart_of_accounts" method="post"  onsubmit="return validateCAccount()">
	<input type="hidden" name="id" value="<?php print $_GET['id'];?>" />
	<table align="center" border="0" bgcolor="#EEEEEE" width="400px" style="font-family:Calibri">
	<tr><td width="50px" height="30px"></td><td></td><td></td><td width="50px"></td></tr>
	<tr><td height="40px"></td><td colspan="2" align="center" style="font-size:14pt; color:navy; font-weight:bold">New Account Creation</td><td></td></tr>
	<tr><td width="50px" height="20px"></td><td></td><td></td><td width="50px"></td></tr>
	<tr><td></td><td><strong>Main Category</strong></td><td>
		<select name="category_l1" id="category_l1" onchange="L2CategorySelection(this.value)" style="width:150px">
		<option value="" >-SELECT-</option>
		<?php for($i=0;$i<sizeof($category_l1);$i++){
		if($category_l1[$i]==$one_L1) $select1='selected="selected"'; else $select1='';
		print '<option value="'.$category_l1[$i].'" '.$select1.'>'.$category_l1[$i].'</option>';
		} ?>		
		</select>
	</td><td></td></tr>
	<tr><td></td><td  bgcolor="#FAFAFA" colspan="2" height="1px"></td><td></td></tr>
	<tr><td></td><td><strong>Category Level 2</strong></td><td><div id="category_l2_div"></div></td><td></td></tr>
	<tr><td></td><td  bgcolor="#FAFAFA" colspan="2" height="1px"></td><td></td></tr>
	<tr><td></td><td><strong>Category Level 3</strong></td><td><div id="category_l3_div"></div></td><td></td></tr>
	<tr><td></td><td  bgcolor="#FAFAFA" colspan="2" height="1px"></td><td></td></tr>
	<tr><td></td><td><strong>Account Name</strong></td><td><input type="text" name="ac_name" id="ac_name"  style="width:150px" value="<?php print $one_name; ?>" /></td><td></td></tr>
	<tr><td></td><td  bgcolor="#FAFAFA" colspan="2" height="1px"></td><td></td></tr>
	<tr><td></td><td><strong>Payment Account</strong></td><td><input type="checkbox" name="payment_ac" <?php if($one_pay==1) print 'checked="checked"'; ?> /></td><td></td></tr>
	<tr><td></td><td  bgcolor="#FAFAFA" colspan="2" height="1px"></td><td></td></tr>
	<tr><td></td><td><strong>Bank Account</strong></td><td><input type="checkbox" name="bank_ac" id="bank_ac" <?php if($one_bank==1) print 'checked="checked"'; ?> onclick="showBankFeeField()"/></td><td></td></tr>
	<tr id="b_p_up_tr" style="display:none;"><td></td><td  bgcolor="#FAFAFA" colspan="2" height="1px"></td><td></td></tr>
	<tr id="b_p_tr" style="display:none;"><td></td><td><strong>Bank Processing Fee</strong></td><td><input type="text" name="bank_fee" id="bank_fee" value="<?php if($one_bank_fee == 0) echo ''; else echo $one_bank_fee; ?>"  style="width:130px"/><span style="padding-left:7px;">%</span></td><td></td></tr>
	<tr><td width="50px" height="20px"></td><td></td><td></td><td width="50px"></td></tr>
	<tr><td height="40px"></td><td colspan="2" align="center"><input type="submit" value="Update Account" style="width:150px; height:50px" /> 
	<?php if($one_status==0){ ?>
	<input type="button" value="Enable" onclick="enableAC(<?php print $_GET['id']; ?>)" style="height:50px; background-color:green; color:white" />
	<?php } ?>
	</td><td></td></tr>
	<tr><td height="40px"></td><td></td><td></td><td></td></tr>
	</table>
</form>
<script type="text/javascript">
	L2CategorySelection(<?php print "'".$one_L1."'"; ?>);
	document.getElementById('category_l2').value=<?php print "'".$one_L2."'"; ?>;
	L3CategorySelection();
	document.getElementById('category_l3').value=<?php print "'".$one_L3."'"; ?>;
</script>