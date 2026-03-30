<?php
function insertSpacesBeforeCapital($string) {
    return preg_replace('/(?<=[a-z])(?=[A-Z])/', ' ', $string);
}
$decimal = getDecimalPlaces(1);
?>

<div id="printheader" style="display:none;">
	<h1 style="color:navy; font-family:Calibri"><?php print $inf_company; ?></h1>
	<h2 align="center" style="color:#3333FF; text-decoration:underline; font-family:Calibri">Balance Sheet</h2>
	<table style="font-family:Calibri">
		<tr>
			<td width="150px"><strong>Generated Date</strong></td>
			<td><?php print dateNow();?></td>
		</tr>
		<tr>
			<td width="150px"><strong>As Of</strong></td>
			<td><?php print $_GET['to_date'];?></td>
		</tr>
	</table>
	<hr />
	<hr />
	<br />
</div>

<div id="print">
	<table align="center" width="600px" style="font-family:Calibri">
		<tr>
			<td colspan="2" style="color:#467898; font-weight:bold; font-size:14pt">Income</td>
		</tr>
		<tr bgcolor="#8898A1" style="color:white">
			<td class="shipmentTB3">Account</td>
			<td width="150px" class="shipmentTB3" align="right">Balance</td>
		</tr>
		<?php
			$l2=$l3='';
			for($i=0;$i<sizeof($inc_ac_id);$i++){
				if($l2!=$inc_ac_catL2[$i]) print '<tr bgcolor="#F5F5F5">
					<td class="shipmentTB3" colspan="2">&nbsp;+ '.insertSpacesBeforeCapital($inc_ac_catL2[$i]).'</td>
				</tr>';
				if($l3!=$inc_ac_catL3[$i]) print '<tr bgcolor="#F5F5F5">
					<td class="shipmentTB3" colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.insertSpacesBeforeCapital($inc_ac_catL3[$i]).'</td>
				</tr>';
				print '<tr>
					<td class="shipmentTB3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a
							style="text-decoration:none; color:inherit"
							href="index.php?components=fin&action=acount_history&id='.$inc_ac_id[$i].'">'.insertSpacesBeforeCapital($inc_ac_name[$i]).'</a>
					</td>
					<td class="shipmentTB3" align="right">'.number_format($inc_ac_amount[$i],$decimal).'</td>
				</tr>';
				$l2=$inc_ac_catL2[$i];
				$l3=$inc_ac_catL3[$i];
			}
		?>
		<tr>
			<td class="shipmentTB3"><strong>Total Income</strong></td>
			<td width="150px" class="shipmentTB3" align="right">
				<strong>
					<?php
						if ($inc_ac_amount !== null) {
							print number_format(array_sum($inc_ac_amount), $decimal);
						} else {
							print "0";
						}
					?>
				</strong>
			</td>
		</tr>
		<tr>
			<td colspan="2"><br /></td>
		</tr>

		<tr>
			<td colspan="2" style="color:#467898; font-weight:bold; font-size:14pt">Cost of Goods Sold (COGS)</td>
		</tr>
		<tr bgcolor="#8898A1" style="color:white">
			<td class="shipmentTB3">Account</td>
			<td width="150px" class="shipmentTB3" align="right">Balance</td>
		</tr>
		<?php
			print '<tr>
				<td class="shipmentTB3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total Cost of Goods Sold
					(COGS)</td>
				<td class="shipmentTB3" align="right">'.number_format($cogs,$decimal).'</td>
			</tr>';
		?>
		<tr>
			<td class="shipmentTB3"><strong>Gross Profit</strong></td>
			<td width="150px" class="shipmentTB3" align="right">
				<strong>
					<?php
						if ($inc_ac_amount !== null) {
							print number_format(array_sum($inc_ac_amount)+$cogs,$decimal);
						} else {
							print "0";
						}
					?>
				</strong>
			</td>
		</tr>
		<tr>
			<td colspan="2"><br /></td>
		</tr>

		<tr>
			<td colspan="2" style="color:#467898; font-weight:bold; font-size:14pt">Expense</td>
		</tr>
		<tr bgcolor="#8898A1" style="color:white">
			<td class="shipmentTB3">Account</td>
			<td width="150px" class="shipmentTB3" align="right">Balance</td>
		</tr>
		<?php
			$l2=$l3='';
			for($i=0;$i<sizeof($exp_ac_id);$i++){
				if($l2!=$exp_ac_catL2[$i]) print '<tr bgcolor="#F5F5F5">
					<td class="shipmentTB3" colspan="2">&nbsp;+ '.insertSpacesBeforeCapital($exp_ac_catL2[$i]).'</td>
				</tr>';
				if($l3!=$exp_ac_catL3[$i]) print '<tr bgcolor="#F5F5F5">
					<td class="shipmentTB3" colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.insertSpacesBeforeCapital($exp_ac_catL3[$i]).'</td>
				</tr>';
				print '<tr>
					<td class="shipmentTB3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a
							style="text-decoration:none; color:inherit"
							href="index.php?components=fin&action=acount_history&id='.$exp_ac_id[$i].'">'.insertSpacesBeforeCapital($exp_ac_name[$i]).'</a>
					</td>
					<td class="shipmentTB3" align="right">'.number_format($exp_ac_amount[$i],$decimal).'</td>
				</tr>';
				$l2=$exp_ac_catL2[$i];
				$l3=$exp_ac_catL3[$i];
			}
		?>
		<tr>
			<td class="shipmentTB3"><strong>Total Expenses</strong></td>
			<td width="150px" class="shipmentTB3" align="right">
				<strong>
					<?php
						if ($exp_ac_amount !== null) {
							print number_format(array_sum($exp_ac_amount),$decimal);
						} else {
							print "0";
						}
					?>
				</strong>
			</td>
		</tr>
		<tr>
			<td colspan="2"><br /></td>
		</tr>
		<tr bgcolor="#DDDDDD">
			<td class="shipmentTB3"><strong>Net Profit</strong></td>
			<td class="shipmentTB3" align="right">
				<strong>
					<?php
						$final_total = 0;

						if ($inc_ac_amount !== null) {
							$final_total += array_sum($inc_ac_amount);
						}

						if ($exp_ac_amount !== null) {
							$final_total += array_sum($exp_ac_amount);
						}

						if ($cogs !== null) {
							$final_total += $cogs;
						}

						echo number_format($final_total, $decimal);
						// print number_format(array_sum($inc_ac_amount)+array_sum($exp_ac_amount)+$cogs,$decimal);
					?>
				</strong>
			</td>
		</tr>
	</table>
</div>