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
<!-- -----------------------------------------------------Fro Printing------------------------------------------------- -->
<div id="printheader" style="display:none;" >
	<h1 style="color:navy; font-family:Calibri"><?php print $inf_company; ?></h1>
	<h2 align="center" style="color:#3333FF; text-decoration:underline; font-family:Calibri">Employee Pay Sheet</h2>
	<table style="font-family:Calibri">
	<tr><td width="150px"><strong>Payroll Month</strong></td><td><?php print $payroll_month;?></td></tr>
	<tr><td width="150px"><strong>Generated Date</strong></td><td><?php print $generated_date;?></td></tr>
	</table>
	<hr />
	<br />
</div>


<br/>
<div id="print" style="display:none;">
<table align="center" style="font-family:Calibri" width="400px" border="1" cellspacing="0">
<tr><td><strong>&nbsp;&nbsp;&nbsp;&nbsp;Employee Name</strong></td><td>&nbsp;&nbsp;&nbsp;&nbsp;<?php print $emp_fullname; ?></td></tr>
<tr><td><strong>&nbsp;&nbsp;&nbsp;&nbsp;Associate Shop</strong></td><td>&nbsp;&nbsp;&nbsp;&nbsp;<?php print $emp_store; ?></td></tr>
<tr><td><strong>&nbsp;&nbsp;&nbsp;&nbsp;NIC</strong></td><td>&nbsp;&nbsp;&nbsp;&nbsp;<?php print $emp_nic; ?></td></tr>
<tr><td><strong>&nbsp;&nbsp;&nbsp;&nbsp;Employee's Bank</strong></td><td>&nbsp;&nbsp;&nbsp;&nbsp;<?php print $emp_bank; ?></td></tr>
<tr><td><strong>&nbsp;&nbsp;&nbsp;&nbsp;Bank Branch</strong></td><td>&nbsp;&nbsp;&nbsp;&nbsp;<?php print $emp_bankbranch; ?></td></tr>
<tr><td><strong>&nbsp;&nbsp;&nbsp;&nbsp;Bank AC</strong></td><td>&nbsp;&nbsp;&nbsp;&nbsp;<?php print $emp_bankac; ?></td></tr>
<tr><td colspan="2" height="3px" ></td></tr>
<?php
$gross_salary=$net_salary=0;
	for($i=0;$i<sizeof($type_id);$i++){
		if(isset($payroll_arr[$type_id[$i]])){
			if(($type_id[$i]!=111)&&($type_id[$i]!=112)){
				print '<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;<strong>'.$type_name[$i].'</strong></td><td align="right">'.number_format($payroll_arr[$type_id[$i]]).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>';
				$gross_salary+=$payroll_arr[$type_id[$i]];
			}
		}
	}
	$net_salary=$gross_salary+$payroll_arr[111]+$payroll_arr[112];
	print '<tr><td><strong>&nbsp;&nbsp;&nbsp;&nbsp;Gross Salary</strong></td><td align="right">'.number_format($gross_salary).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>';
	print '<tr><td><strong>&nbsp;&nbsp;&nbsp;&nbsp;EPF (Employee)</strong></td><td align="right">'.number_format(-$payroll_arr[111]).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>';
	print '<tr><td><strong>&nbsp;&nbsp;&nbsp;&nbsp;Tax</strong></td><td align="right">'.number_format(-$payroll_arr[112]).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>';
	print '<tr><td><strong>&nbsp;&nbsp;&nbsp;&nbsp;Net Salary</strong></td><td align="right"><strong>'.number_format($net_salary).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></td></tr>';
	print '<tr><td colspan="2"><br /></tr>';
	print '<tr><td><strong>&nbsp;&nbsp;&nbsp;&nbsp;ETF </strong></td><td align="right">'.number_format($etf_rate * $payroll_arr[1]).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>';
	print '<tr><td><strong>&nbsp;&nbsp;&nbsp;&nbsp;EPF (Employer)</strong></td><td align="right">'.number_format($epf_employer_rate * $payroll_arr[1]).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>';
?>
</table>
</div>



<table align="center" width="900px" cellspacing="0" ><tr style="background-color:#EEEEEE; font-family:Calibri; font-weight:bold; color:#467898"><td align="center" width="100px">Payroll No:<br /><?php print str_pad($_GET['payroll_no'], 7, "0", STR_PAD_LEFT); ?></td><td align="center">Month of Payroll  &nbsp;&nbsp;&nbsp;<span style="color:black"><?php print $payroll_month; ?></span></td><td width="180px" align="right"><input type="button" value="Back to Payroll" style="width:150px; height:40px" onclick="window.location = 'index.php?components=fin&action=payroll_one&id=<?php print $_GET['payroll_no'];?>'" /></td></tr></table>
<!-- ------------------------------------------------------------------------------------------------------------------------ -->
<table align="center" style="font-family:Calibri" width="400px">
<tr><td bgcolor="#EEEEEE" class="shipmentTB3"><strong>Employee Name</strong></td><td bgcolor="#FAFAFA" class="shipmentTB3"><?php print $emp_fullname; ?></td></tr>
<tr><td bgcolor="#EEEEEE" class="shipmentTB3"><strong>Associate Shop</strong></td><td bgcolor="#FAFAFA" class="shipmentTB3"><?php print $emp_store; ?></td></tr>
<tr><td bgcolor="#EEEEEE" class="shipmentTB3"><strong>NIC</strong></td><td bgcolor="#FAFAFA" class="shipmentTB3"><?php print $emp_nic; ?></td></tr>
<tr><td bgcolor="#EEEEEE" class="shipmentTB3"><strong>Employee's Bank</strong></td><td bgcolor="#FAFAFA" class="shipmentTB3"><?php print $emp_bank; ?></td></tr>
<tr><td bgcolor="#EEEEEE" class="shipmentTB3"><strong>Bank Branch</strong></td><td bgcolor="#FAFAFA" class="shipmentTB3"><?php print $emp_bankbranch; ?></td></tr>
<tr><td bgcolor="#EEEEEE" class="shipmentTB3"><strong>Bank AC</strong></td><td bgcolor="#FAFAFA" class="shipmentTB3"><?php print $emp_bankac; ?></td></tr>
<tr><td colspan="2" height="3px" bgcolor="#467898"></td></tr>
<?php
$gross_salary=$net_salary=0;
	for($i=0;$i<sizeof($type_id);$i++){
		if(isset($payroll_arr[$type_id[$i]])){
			if(($type_id[$i]!=111)&&($type_id[$i]!=112)){
				print '<tr><td bgcolor="#EEEEEE" class="shipmentTB3"><strong>'.$type_name[$i].'</strong></td><td bgcolor="#FAFAFA" class="shipmentTB5" align="right">'.number_format($payroll_arr[$type_id[$i]]).'</td></tr>';
				$gross_salary+=$payroll_arr[$type_id[$i]];
			}
		}
	}
	$net_salary=$gross_salary+$payroll_arr[111]+$payroll_arr[112];
	print '<tr><td bgcolor="#CCCCCC" class="shipmentTB3"><strong>Gross Salary</strong></td><td bgcolor="#CCCCCC" class="shipmentTB5" align="right">'.number_format($gross_salary).'</td></tr>';
	print '<tr><td bgcolor="#EEEEEE" class="shipmentTB3"><strong>EPF (Employee)</strong></td><td bgcolor="#FAFAFA" class="shipmentTB5" align="right">'.number_format(-$payroll_arr[111]).'</td></tr>';
	print '<tr><td bgcolor="#EEEEEE" class="shipmentTB3"><strong>Tax</strong></td><td bgcolor="#FAFAFA" class="shipmentTB5" align="right">'.number_format(-$payroll_arr[112]).'</td></tr>';
	print '<tr><td bgcolor="#EEEEEE" class="shipmentTB3"><strong>Deductions</strong></td><td bgcolor="#FAFAFA" class="shipmentTB5" align="right">'.number_format(-$payroll_arr[113]).'</td></tr>';
	print '<tr><td style="background-color:#467898;color :white;" class="shipmentTB3"><strong>Net Salary</strong></td><td style="background-color:#467898;color :white;" class="shipmentTB5" align="right"><strong>'.number_format($net_salary).'</strong></td></tr>';
	print '<tr><td colspan="2"><br /></tr>';
	print '<tr><td bgcolor="#EEEEEE" class="shipmentTB3"><strong>ETF </strong></td><td bgcolor="#FAFAFA" class="shipmentTB5" align="right">'.number_format($etf_rate * $payroll_arr[1]).'</td></tr>';
	print '<tr><td bgcolor="#EEEEEE" class="shipmentTB3"><strong>EPF (Employer)</strong></td><td bgcolor="#FAFAFA" class="shipmentTB5" align="right">'.number_format($epf_employer_rate * $payroll_arr[1]).'</td></tr>';
?>
</table>
	<br />
<table align="center"><tr><td align="center">
	<div style="background-color:#6699FF; border:medium; border-color:black; width:80px;">
	<a class="shortcut-button" onclick="printdiv('print','printheader')" href="#"><span style="text-decoration:none; font-family:Arial; color:navy;">
	<img src="images/print.png" alt="icon" /><br />
	Print
	</span></a>
	</div>
	</td></tr>
</table>
<?php
                include_once  'template/footer.php';
?>