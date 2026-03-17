<?php
                include_once  'template/header.php';
?>

<table align="center" style="font-size:12pt"><tr><td>
<?php 
	if(isset($_REQUEST['message'])){
		if($_REQUEST['re']=='success') $color='green'; else $color='red';
	print '<span style="color:'.$color.'; font-weight:bold;">'.$_REQUEST['message'].'</span>'; 
	}
?></td></tr></table>
<div id="printheader" style="display:none;" >
	<h1 style="color:navy; font-family:Calibri"><?php print $inf_company; ?></h1>
	<h2 align="center" style="color:#3333FF; text-decoration:underline; font-family:Calibri">Payroll</h2>
	<table style="font-family:Calibri">
	<tr><td width="150px"><strong>Payroll Month</strong></td><td><?php print $payroll_month;?></td></tr>
	<tr><td width="150px"><strong>Generated Date</strong></td><td><?php print $generated_date;?></td></tr>
	</table>
	<hr />
	<hr />
	<br />
</div>


<table align="center" width="900px" cellspacing="0" ><tr style="background-color:#EEEEEE; font-family:Calibri; font-weight:bold; color:#467898"><td align="center" width="100px">Payroll No:<br /><?php print str_pad($_GET['id'], 7, "0", STR_PAD_LEFT); ?></td><td align="center">Month of Payroll  &nbsp;&nbsp;&nbsp;<span style="color:black"><?php print $payroll_month; ?></span></td><td width="180px" align="right"><input type="button" value="List of Payrolls" style="width:150px; height:40px" onclick="window.location = 'index.php?components=fin&action=payroll_list'" /></td></tr></table>
<br/>
<div id="print">
<table align="center" style="font-family:Calibri">
<tr style="background-color:#467898;color :white;"><th class="shipmentTB3">Employee</th>
<?php for($i=0;$i<sizeof($type_id);$i++){
		if(($type_id[$i]!=111)&&($type_id[$i]!=112)&&($type_id[$i]!=113)){
			print '<th class="shipmentTB3" width="80px">'.$type_name[$i].'</th>';
		}
} ?>
<th class="shipmentTB3" width="80px">Gross Salary</th>
<th class="shipmentTB3" width="80px">EPF Emp</th>
<th class="shipmentTB3" width="80px">Tax</th>
<th class="shipmentTB3" width="80px">Total<br />Deductions</th>
<th class="shipmentTB3" width="80px">Net Salary</th>
</tr>
<?php
$gross_total=$net_total=$epfemp_total=$taxemp_total=$deduction_total=0;
	for($i=0;$i<sizeof($emp_id);$i++){
		$gross_salary=$net_salary=0;
		if(($i%2)==0) $color='#FAFAFA'; else $color='#EEEEEE';
		print '<tr style="background-color:'.$color.'"><td class="shipmentTB3"><a href="index.php?components=fin&action=payroll_user_view&payroll_no='.$_GET['id'].'&emp='.$emp_id[$i].'" style="text-decoration:none"><strong>'.ucfirst($emp_name[$i]).'</strong></a></td>';
		for($j=0;$j<sizeof($type_id);$j++){
			if(isset($payroll_arr[$emp_id[$i]][$type_id[$j]])){
				if(($type_id[$j]!=111)&&($type_id[$j]!=112)&&($type_id[$j]!=113)){
				print '<td class="shipmentTB3" align="right">'.number_format($payroll_arr[$emp_id[$i]][$type_id[$j]]).'</td>';
				$gross_salary+=$payroll_arr[$emp_id[$i]][$type_id[$j]];
				}
			}else{
				print '<td class="shipmentTB3" align="right">0</td>';
			}
		}
		$net_salary=$gross_salary+$payroll_arr[$emp_id[$i]][111]+$payroll_arr[$emp_id[$i]][112]+$payroll_arr[$emp_id[$i]][113];
		print '<td class="shipmentTB3" align="right">'.number_format($gross_salary).'</td>';
		print '<td class="shipmentTB3" align="right">'.number_format(-$payroll_arr[$emp_id[$i]][111],2).'</td>';
		print '<td class="shipmentTB3" align="right">'.number_format(-$payroll_arr[$emp_id[$i]][112],2).'</td>';
		print '<td class="shipmentTB3" align="right">'.number_format(-$payroll_arr[$emp_id[$i]][113],2).'</td>';
		print '<td class="shipmentTB3" align="right">'.number_format($net_salary,2).'</td></tr>';
	$epfemp_total+=-$payroll_arr[$emp_id[$i]][111];
	$taxemp_total+=-$payroll_arr[$emp_id[$i]][112];
	$deduction_total+=-$payroll_arr[$emp_id[$i]][113];
	$gross_total+=$gross_salary;
	$net_total+=$net_salary;
	}
	$salaray_sheet_total=$gross_total+$etf_employer_payble+$epf_employer_payble;
		print '<tr style="background-color:#DDDDDD"><td colspan="'.(sizeof($type_id)-2).'" align="right" class="shipmentTB3"><strong>Total Salary</strong></td><td class="shipmentTB3" align="right"><strong>'.number_format($gross_total).'</strong></td><td class="shipmentTB3" align="right">'.number_format($epfemp_total,2).'</td><td class="shipmentTB3" align="right">'.number_format($taxemp_total,2).'</td><td class="shipmentTB3" align="right">'.number_format($deduction_total,2).'</td><td class="shipmentTB3" align="right"><strong>'.number_format($net_total,2).'</strong></td></tr>';
		print '<tr style="background-color:#DDDDDD"><td colspan="'.(sizeof($type_id)+2).'" align="right" class="shipmentTB3">ETF Employer</td><td class="shipmentTB3" align="right">'.number_format($etf_employer_payble).'</td></tr>';
		print '<tr style="background-color:#DDDDDD"><td colspan="'.(sizeof($type_id)+2).'" align="right" class="shipmentTB3">EPF Employer</td><td class="shipmentTB3" align="right">'.number_format($epf_employer_payble).'</td></tr>';
		print '<tr style="background-color:#DDDDDD"><td colspan="'.(sizeof($type_id)+2).'" align="right" class="shipmentTB3"><strong>Salary Sheet Total</strong></td><td class="shipmentTB3" align="right"><strong>'.number_format($salaray_sheet_total).'</strong></td></tr>';
 ?>
	</table>
	<br />
</div>
<table align="center"><tr><td align="center">
	<div style="background-color:#6699FF; border:medium; border-color:black; width:80px;">
	<a class="shortcut-button" onclick="printdiv('print','printheader')" href="#"><span style="text-decoration:none; font-family:Arial; color:navy;">
	<img src="images/print.png" alt="icon" /><br />
	Print
	</span></a>
	</div>
	</td><td><?php if(deleteAuthurization()) print '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onclick="deletePayroll('.$_GET['id'].')" style="text-decoration:none" ><img src="images/cancel.png" /></a>'; ?></td></tr>
</table>
<?php
                include_once  'template/footer.php';
?>