<?php
                include_once  'template/header.php';
?>
	
	<script type="text/javascript">
		function calculateSalary($id){
			var $gross_total=$net_total=0;
			$etf_employer=parseInt(document.getElementById('etf_employer').value);
			$epf_employer=parseInt(document.getElementById('epf_employer').value);
			$basic_grosa=document.getElementById('basic_grosa_'+$id).value;
			$basic_net=document.getElementById('basic_net_'+$id).value;
			$commission=document.getElementById('commission_'+$id).value;
			$special=document.getElementById('special_'+$id).value;
			$ot_hours=document.getElementById('ot_'+$id).value;
			$ot_rate=document.getElementById('ot_rate_'+$id).value;
			$ot_amount=$ot_hours * $ot_rate;
			$gross_salary=parseInt($basic_grosa)+parseInt($commission)+parseInt($special)+$ot_amount;
			$net_salary=parseInt($basic_net)+parseInt($commission)+parseInt($special)+$ot_amount;
			document.getElementById('gross_'+$id).innerHTML=$gross_salary;
			document.getElementById('basic_gross_cal_'+$id).value=$gross_salary;
			document.getElementById('net_'+$id).innerHTML=$net_salary;
			document.getElementById('basic_net_cal_'+$id).value=$net_salary;
			$arr_size=document.getElementById('arr_size').value;
			for($i=0;$i<$arr_size;$i++){
				var $gross_total=parseInt($gross_total)+parseInt(document.getElementById('basic_gross_cal_'+$i).value);
				var $net_total=parseInt($net_total)+parseInt(document.getElementById('basic_net_cal_'+$i).value);
			}
			document.getElementById('sub_total').innerHTML=$gross_total;
			document.getElementById('net_sub_total').innerHTML=$net_total;
			document.getElementById('salaray_sheet_total').innerHTML=$gross_total+$etf_employer+$epf_employer;			
		}
	</script>

<table align="center" style="font-size:12pt"><tr><td>
<?php 
	if(isset($_REQUEST['message'])){
		if($_REQUEST['re']=='success') $color='green'; else $color='red';
	print '<span style="color:'.$color.'; font-weight:bold;">'.$_REQUEST['message'].'</span>'; 
	}
?></td></tr></table>
<form action="index.php?components=fin&action=generate_payroll" method="post" onsubmit="return validatePayroll()">
<table align="center" width="900px" cellspacing="0" ><tr style="background-color:#EEEEEE; font-family:Calibri; font-weight:bold; color:#467898"><td align="center">Month of Payroll <input type="month" name="month" id="month"  /></td><td width="180px" align="right"><input type="button" value="List of Payrolls" style="width:150px; height:40px" onclick="window.location = 'index.php?components=fin&action=payroll_list'" /></td></tr></table>
<br/>
<table align="center" style="font-family:Calibri">
<tr style="background-color:#467898;color :white;"><th class="shipmentTB3">Employee</th>
<?php for($i=0;$i<sizeof($type_id);$i++){
	print '<th class="shipmentTB3" width="80px">'.$type_name[$i].'</th>';
} ?>
<th class="shipmentTB3" width="100px">Commission</th><th class="shipmentTB3" width="100px">Special</th><th class="shipmentTB3" width="80px">OT Hours</th><th class="shipmentTB3" width="100px">Gross Salary</th><th class="shipmentTB3" width="50px">EPF</th><th class="shipmentTB3" width="50px">TAX</th><th class="shipmentTB3" width="50px">Total<br>Deductions</th><th class="shipmentTB3" width="100px">Net Salary</th></tr>
<?php
$gross_total=$net_total=$total_basic=$epfemp_total=$taxemp_total=$deduction_total=0;
	for($i=0;$i<sizeof($emp_id);$i++){
		$gross_salary=0;
		if(($i%2)==0) $color='#FAFAFA'; else $color='#EEEEEE';
		print '<tr style="background-color:'.$color.'"><td class="shipmentTB3"><strong>'.ucfirst($emp_name[$i]).'</strong></td>';
		for($j=0;$j<sizeof($type_id);$j++){
			if(isset($payroll_arr[$emp_id[$i]][$type_id[$j]])){
				print '<td class="shipmentTB3" align="right">'.number_format($payroll_arr[$emp_id[$i]][$type_id[$j]]).'</td>';
				$gross_salary+=$payroll_arr[$emp_id[$i]][$type_id[$j]];
				if($type_id[$j]==1) $total_basic+=$payroll_arr[$emp_id[$i]][$type_id[$j]];
			}else{
				print '<td class="shipmentTB3" align="right">0</td>';
			}
		}
		$net_salary=$gross_salary-$sa_epf_employee[$emp_id[$i]]-$sa_tax_employee[$emp_id[$i]]-$payroll_loan[$emp_id[$i]];
		print '<td class="shipmentTB3" align="center"><input type="text" name="commission_'.$emp_id[$i].'" id="commission_'.$i.'" style="width:45px; text-align:right; padding-right:5px" value="0" /><input type="button" value="Add" onclick="calculateSalary('."'$i'".')" /></td>';
		print '<td class="shipmentTB3" align="center"><input type="text" name="special_'.$emp_id[$i].'" id="special_'.$i.'" style="width:45px; text-align:right; padding-right:5px" value="0" /><input type="button" value="Add" onclick="calculateSalary('."'$i'".')" /></td>';
		print '<td class="shipmentTB3" align="center"><input type="text" name="ot_'.$emp_id[$i].'" id="ot_'.$i.'" style="width:30px; text-align:right; padding-right:5px" value="0" /><input type="button" value="Cal" onclick="calculateSalary('."'$i'".')" /><input type="hidden" value="'.$emp_ot_rate[$i].'" id="ot_rate_'.$i.'" /></td>';
		print '<td class="shipmentTB3" align="right"><div id="gross_'.$i.'">'.number_format($gross_salary).'</div><input type="hidden" id="basic_grosa_'.$i.'" value="'.$gross_salary.'" /><input type="hidden" name="basic_gross_cal_'.$emp_id[$i].'" id="basic_gross_cal_'.$i.'" value="'.$gross_salary.'" /></td>';
		print '<td class="shipmentTB3" align="right">'.number_format($sa_epf_employee[$emp_id[$i]],2).'<input type="hidden" name="epf_emp_'.$emp_id[$i].'" value="'.$sa_epf_employee[$emp_id[$i]].'" /></td>';
		print '<td class="shipmentTB3" align="right">'.number_format($sa_tax_employee[$emp_id[$i]],2).'<input type="hidden" name="tax_'.$emp_id[$i].'" value="'.$sa_tax_employee[$emp_id[$i]].'" /></td>';
		print '<td class="shipmentTB3" align="right">'.number_format($payroll_loan[$emp_id[$i]],2).'<input type="hidden" name="loan_'.$emp_id[$i].'" value="'.$payroll_loan[$emp_id[$i]].'" /></td>';
		print '<td class="shipmentTB3" align="right"><div id="net_'.$i.'">'.number_format($net_salary,2).'</div><input type="hidden" id="basic_net_'.$i.'" value="'.$net_salary.'" /><input type="hidden" name="basic_net_cal_'.$emp_id[$i].'" id="basic_net_cal_'.$i.'" value="'.$net_salary.'" /></td></tr>';
	$epfemp_total+=$sa_epf_employee[$emp_id[$i]];
	$taxemp_total+=$sa_tax_employee[$emp_id[$i]];
	$deduction_total+=$payroll_loan[$emp_id[$i]];
	$gross_total+=$gross_salary;
	$net_total+=$net_salary;
	}
	$etf_employer_payble=$etf_rate * $total_basic;
	$epf_employer_payble=$epf_employer_rate * $total_basic;
	$salaray_sheet_total=$gross_total+$etf_employer_payble+$epf_employer_payble;
		print '<tr style="background-color:#DDDDDD"><td colspan="'.(sizeof($type_id)+4).'" align="right" class="shipmentTB3"><strong>Total Salary</strong></td><td class="shipmentTB3" align="right"><strong><div id="sub_total">'.number_format($gross_total).'</div><input type="hidden" id="arr_size" value="'.sizeof($emp_id).'" /></strong></td>
				<td class="shipmentTB3" align="right">'.number_format($epfemp_total,2).'</td><td class="shipmentTB3" align="right">'.number_format($taxemp_total,2).'</td><td class="shipmentTB3" align="right">'.number_format($deduction_total,2).'</td><td class="shipmentTB3" align="right"><strong><div id="net_sub_total">'.number_format($net_total,2).'</div></strong></td></tr>';
		print '<tr style="background-color:#DDDDDD"><td colspan="'.(sizeof($type_id)+8).'" align="right" class="shipmentTB3">ETF Employer</td><td class="shipmentTB3" align="right">'.number_format($etf_employer_payble,2).'<input type="hidden" id="etf_employer" name="etf_employer" value="'.$etf_employer_payble.'" /></td></tr>';
		print '<tr style="background-color:#DDDDDD"><td colspan="'.(sizeof($type_id)+8).'" align="right" class="shipmentTB3">EPF Employer</td><td class="shipmentTB3" align="right">'.number_format($epf_employer_payble,2).'<input type="hidden" id="epf_employer" name="epf_employer" value="'.$epf_employer_payble.'" /></td></tr>';
		print '<tr style="background-color:#DDDDDD"><td colspan="'.(sizeof($type_id)+8).'" align="right" class="shipmentTB3"><strong>Salary Sheet Total</strong></td><td class="shipmentTB3" align="right"><div id="salaray_sheet_total">'.number_format($salaray_sheet_total,2).'</div></td></tr>';
		print '<tr style="background-color:#EEEEEE"><td colspan="'.(sizeof($type_id)+9).'" align="center"><br /><input type="submit" value="Generate Payroll" style="width:150px; height:50px" /><br /><br /></td></tr>';
?>
</table>
</form>
<?php
                include_once  'template/footer.php';
?>