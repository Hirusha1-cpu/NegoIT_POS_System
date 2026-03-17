<?php
	$basic_salary=$epf_employee_contribution=$tax=$commision=$special=$ot=$deduct=$total_earnings=$total_deduction=$epf_employer_contribution=$etf_contribution=0;
	for($i=0;$i<sizeof($type_id);$i++){
		if(isset($payroll_arr[$type_id[$i]])){
			if($type_id[$i]==111){
				$epf_employee_contribution = abs($payroll_arr[$type_id[$i]]);
			}
			if($type_id[$i]==112){
				$tax = abs($payroll_arr[$type_id[$i]]);
			}
			if($type_id[$i]==101){
				$commision = abs($payroll_arr[$type_id[$i]]);
			}
			if($type_id[$i]==102){
				$special = abs($payroll_arr[$type_id[$i]]);
			}
			if($type_id[$i]==103){
				$ot = abs($payroll_arr[$type_id[$i]]);
			}
			if($type_id[$i]==113){
				$deduct = abs($payroll_arr[$type_id[$i]]);
			}
		}
	}
	if(isset($payroll_arr) && isset($payroll_arr[1])){
		$epf_employer_contribution = $epf_employer_rate * $payroll_arr[1];
		$etf_contribution = $etf_rate * $payroll_arr[1];
		$basic_salary = $payroll_arr[1];
	}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>Salary Slip - <?php print $emp_fullname; ?> - <?php print $payroll_month; ?>/<?php print $payroll_year; ?></title>

	<style>
		body {
			font-family: 'Courier New', Courier, monospace;
			margin: 30px;
			font-size: 14px;
		}

		.header {
			text-align: center;
			margin-bottom: 15px;
		}

		.company-name {
			font-size: 20px;
			font-weight: bold;
			letter-spacing: 1px;
			text-transform: uppercase;
			margin-bottom: 5px;
		}

		.company-address {
			font-size: 16px;
			font-weight: bold;
			letter-spacing: 1px;
			text-transform: uppercase;
		}

		.pay-advice {
			font-size: 16px;
			margin: 15px 0;
			text-transform: uppercase;
		}

		.employee-details {
			display: grid;
			grid-template-columns: 1fr 1fr;
			margin-bottom: 15px;
			gap: 10px;
			text-transform: uppercase;
			line-height: 20px;
		}

		.section {
			margin: 5px 0;
		}

		table {
			width: 100%;
			border-collapse: collapse;
			margin: 5px 0;
		}

		td {
			padding: 6px 0;
			text-align: left;
			vertical-align: top;
		}

		.amount {
			text-align: right;
			white-space: nowrap;
			width: 90px;
			padding-left: 10px;
		}

		td:first-child {
			width: calc(100% - 100px);
		}

		.total-cell {
			border-top: 2px solid #000;
			border-bottom: 2px solid #000;
			display: inline-block;
			padding: 4px 0;
			margin: -4px 0;
		}

		.summary-table td {
			font-weight: bold;
		}

		.dashed-line {
			border-top: 2px dashed #000;
			margin: 15px 0;
		}

		h3,
		h4 {
			margin: 15px 0;
			letter-spacing: 2px;
		}

		/* Previous styles remain same except these changes */
		.total-amount {
			border-top: 2px solid #000;
			border-bottom: 2px solid #000;
			padding: 6px 0;
			display: block;
			margin: -6px 0;
		}

		/* Add this new rule */
		.amount.total-highlight {
			border-top: 2px solid #000;
			border-bottom: 2px solid #000;
			padding: 6px 0px 6px 0;
			margin: -1px 0;
		}

		/* Add this new style */
		.generated-note {
			text-align: left;
			margin-top: 25px;
			font-size: 16px;
			color: #555;
			font-weight: bold;
		}

		/* Add heading underline styles */
		h3,
		h4 {
			display: inline-block;
			position: relative;
			margin: 10px 0 5px;
			padding-bottom: 3px;
		}

		h3::after,
		h4::after {
			content: '';
			position: absolute;
			left: 0;
			bottom: 0;
			width: 100%;
			border-bottom: 1px solid #000;
		}

		.salary-name {
			text-transform: uppercase;
		}
	</style>
</head>

<body>
	<div class="header">
		<div class="company-name"><?php print $emp_store; ?></div>
		<div class="company-address"><?php print $store_address; ?></div>
		<div class="pay-advice">*** PAY ADVICE FOR THE MONTH OF <?php print $payroll_month; ?> /
			<?php print $payroll_year; ?> ***
		</div>
	</div>
	<div class="employee-details">
        <div>
            EMPLOYEE NO: <?php print $emp_no; ?><br>
            EMPLOYEE NAME: <?php print $emp_fullname; ?><br>
        </div>
		<div style="padding-left:25px;">
			NIC NO: <?php print $emp_nic; ?><br>
			DESIGNATION: <?php print $emp_designation; ?><br>
        </div>
    </div>


	<div class="section">
        <h3>E A R N I N G S</h3>
        <table>
			<?php for($i=0;$i<sizeof($salary_type);$i++){
				if($salary_type[$i] == 1){
					$total_earnings = $total_earnings + $salary_amount[$i];
					print '<tr><td class="salary-name">'.strtoupper($salary_name[$i]).'</td><td class="amount">'.number_format($salary_amount[$i],2).'</td></tr>';
				}
			?>
			<?php } ?>
			<?php if(isset($ot) && $ot != 0){ ?>
				<tr><td>O.T</td><td class="amount"><?php print number_format($ot, 2); ?></td></tr>
			<?php } ?>
			<?php if(isset($commision) && $commision != 0){ ?>
				<tr><td>COMMISION</td><td class="amount"><?php print number_format($commision, 2); ?></td></tr>
			<?php } ?>
			<?php if(isset($special) && $commision != 0){ ?>
				<tr><td>SPECIAL</td><td class="amount"><?php print number_format($special, 2); ?></td></tr>
			<?php } ?>
            <tr>
                <td></td>
                <td class="amount total-highlight">
					<span>
						<?php
							$total_earnings = $total_earnings + $ot + $commision + $special;
							print number_format($total_earnings,2); ?>
					</span>
				</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h3>D E D U C T I O N S</h3>
        <table>
			<?php if(isset($epf_employee_contribution) && $epf_employee_contribution != 0){ ?>
				<tr>
					<td>E.P.F. EMPLOYEE CONTRIBUTION.</td>
					<td class="amount"><?php print number_format($epf_employee_contribution, 2); ?></td>
				</tr>
			<?php } ?>
			<?php for($i=0;$i<sizeof($salary_type);$i++){
				if($salary_type[$i] == 2){
					$total_deduction = $total_deduction + $salary_amount[$i];
					print '<tr><td class="salary-name">'.strtoupper($salary_name[$i]).'</td><td class="amount">'.number_format($salary_amount[$i],2).'</td></tr>';
				}
			?>
			<?php } ?>
			<?php if(isset($deduct) && $deduct != 0){ ?>
				<tr><td>OTHER DEDUCTIONS</td><td class="amount"><?php print number_format($deduct, 2); ?></td></tr>
			<?php } ?>
            <tr>
                <td></td>
                <td class="amount total-highlight">
					<span>
					<?php
						$total_deduction = $total_deduction+$epf_employee_contribution;
						print number_format($total_deduction,2);
					?>
					</span>
				</td>
            </tr>
        </table>
    </div>
	<div class="section">
        <h3>SUMMARY</h3>
        <table>
            <tr><td>GROSS EARNING</td><td class="amount"><?php print number_format($total_earnings,2);?></td></tr>
            <tr><td>TOTAL DEDUCTION</td><td class="amount"><?php print number_format($total_deduction,2);?></td></tr>
            <tr><td>NET SALARY</td><td class="amount"><?php print number_format($total_earnings - $total_deduction,2);?></td></tr>
        </table>
    </div>

    <div class="section">
        <!-- <h4>NON CASH / TAX PURPOSE</h4>
        <div>MEDICAL PREMIUM - [00,000.00]</div>

        <h4>TAX SUMMARY</h4>
        <div>TOTAL FOR A.P.I.T.(TAX PAID BY EMPLOYEE) - [00,000.00]</div> -->
        <h3>P/FUND SUMMARY</h3>
		<table>
			<tr><td>TOTAL FOR E.P.F.</td><td class="amount"><?php print number_format($basic_salary,2);?></td></tr>
			<tr><td>E.P.F. EMPLOYEE CONTRIBUTION. (<?php print $epf_employee_rate * 100; ?>%)</td><td class="amount"><?php print number_format($epf_employee_contribution, 2); ?></td></tr>
			<tr><td>E.P.F. EMPLOYERS CONTRIBUTION. (<?php print $epf_employer_rate * 100; ?>%)</td><td class="amount"><?php print number_format($epf_employer_contribution, 2); ?></td></tr>
			<tr><td>E.T.F. CONTRIBUTION (<?php print $etf_rate * 100; ?>%)</td><td class="amount"><?php print number_format($etf_contribution, 2); ?></td></tr>
		</table>
    </div>

    <div class="dashed-line"></div>

    <div class="bank-details">
        <div style="margin: 5px 0px; font-size: 14px;">YOUR NET PAYMENT HAS BEEN CREDITED TO THE FOLLOWING BANK A/C.</div>
		<table>
			<tr><td>BANK Name: <span style="font-weight:bold;"><?php print $emp_bank; ?> - <?php print $emp_bankbranch; ?></span> (<span><?php print $emp_bank_code; ?></span> - <span><?php print $emp_branch_code; ?></span>)</td><td></td></tr>
			<tr><td>A/C. NO:  <span style="font-weight:bold;"><?php print $emp_bankac; ?></span> </td><td style="text-align:right;">AMOUNT: <span style="font-weight:bold;"><?php print number_format($total_earnings-$total_deduction, 2) ?></span></td></tr>
		</table>
    </div>

    <div class="generated-note">
        This is a computer-generated document and require no signature
    </div>

</body>