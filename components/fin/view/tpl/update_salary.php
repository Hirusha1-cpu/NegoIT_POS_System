<form action="index.php?components=fin&action=update_salary" method="post"  onsubmit="return validateSalary()">
	<input type="hidden" name="emp_id" value="<?php print $_GET['id']; ?>" />
	<table align="center" border="0" width="400px" style="font-family:Calibri">
	<tr style="background-color:#DDDDDD; height:80px"><td colspan="2" align="center" style="font-size:14pt; color:navy; font-weight:bold">Employee Salary Form</td></tr>
	<tr style="background-color:#DDDDDD; color:navy;"><td align="center"><strong>Employee</strong></td><td align="center"><?php print ucfirst($one_emp_name); ?></td></tr>
	<tr style="background-color:#467898;color :white;"><td align="center"><strong>Salary Type</strong></td><td align="center">Amount</td></tr>
	<tr><td bgcolor="#FAFAFA" colspan="2" height="1px"></td></tr>
	<?php 
	$color='#EEEEEE';
	for($i=0;$i<sizeof($one_sa_id);$i++){
		$said=$one_sa_id[$i];
		print '<tr style="background-color:'.$color.'"><td align="center">';
		print '<select name="satype_'.$said.'" id="satype_'.$said.'" style="width:180px">';
		print '<option value="" >-SELECT-</option>';
		for($j=0;$j<sizeof($sa_type_id);$j++){
			if($sa_type_id[$j]==$one_st_id[$i]) $select='selected="selected"'; else $select='';
		print '<option value="'.$sa_type_id[$j].'" '.$select.'>'.$sa_type_name[$j].'</option>';
		}
		if(($i%2)==0) $color='#FAFAFA'; else $color='#EEEEEE';
		print '</select>';
		print  '</td><td align="center"><input type="text" name="amount_'.$said.'" id="amount_'.$said.'"  style="width:100px; text-align:right; padding-right:10px" value="'.$one_sa_amount[$i].'"/></td></tr>';
	 } ?>
		<tr style="background-color:<?php print $color; ?>;"><td align="center">
		<select name="satype_new" id="satype_new" style="width:180px">
		<option value="" >-SELECT-</option>
		<?php for($j=0;$j<sizeof($sa_type_id);$j++){
		print '<option value="'.$sa_type_id[$j].'" >'.$sa_type_name[$j].'</option>';
		} ?>		
		</select>
	</td><td align="center"><input type="text" name="amount_new" id="amount_new"  style="width:100px; text-align:right; padding-right:10px" /></td></tr>
		<tr style="background-color:<?php print $color; ?>;"><td align="center">Over Time (OT) Rate</td><td align="center"><input type="text" name="ot_rate" id="ot_rate" value="<?php print $one_ot_rate; ?>"  style="width:100px; text-align:right; padding-right:10px" /></td></tr>
	<tr style="background-color:#DDDDDD; height:80px"><td colspan="2" align="center"><input type="submit" value="Update Salary Sheet" style="width:150px; height:50px" /></td></tr>
	</table>
</form>