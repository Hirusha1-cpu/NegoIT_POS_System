<?php
	include_once  'template/header.php';
	$menu_components=$_GET['components'];
	$decimal = getDecimalPlaces(1);
?>

<table align="center" height="100%" cellspacing="0" style="font-family:Calibri" bgcolor="#F0F0F0" border="0">
	<tr>
		<td width="50px"></td>
		<td>
			<form id="search_form" action="index.php" method="get">
				<input type="hidden" name="components" value="<?php print  $menu_components; ?>" />
				<input type="hidden" name="action" value="repair_income" />
				<table>
					<tr>
						<td colspan="4" align="center" style="color:#467898;">
							<strong>Repair Income Summary Report</strong>
							<br />
							<span style="font-size:10pt;"><i>Report by Delivered Date</i></span>
							<hr />
						</td>
					</tr>
					<tr>
						<td>From Date <input type="date" id="datefrom" name="datefrom"
								value="<?php print $fromdate; ?>" /></td>
						<td width="100px"></td>
						<td>To Date <input type="date" id="dateto" name="dateto" value="<?php print $todate; ?>" /></td>
						<td>
							<a onclick="document.getElementById('search_form').submit();" style="cursor:pointer">
								<img src="images/search.png" style="width:30px; vertical-align:middle" />
							</a>
						</td>
					</tr>
				</table>
			</form>
		</td>
		<td width="50px"></td>
	</tr>
</table>

<div id="printheader" style="display:none">
	<h2 align="center" style="color:navy"><?php print $inf_company; ?></h2>
	<h3 align="center" style="color:#333399; text-decoration:underline">Repair Income Summary Report</h3>
	<table>
		<tr>
			<td>
				<table style="font-size:12pt" border="1" cellspacing="0">
					<tr>
						<td style="background-color:#C0C0C0; padding-left:10px">From Date</td>
						<td style="background-color:#EEEEEE; padding-left:10px; padding-right:10px;">
							<?php print $fromdate; ?></td>
					</tr>
					<tr>
						<td style="background-color:#C0C0C0; padding-left:10px">To Date</td>
						<td style="background-color:#EEEEEE; padding-left:10px; padding-right:10px;">
							<?php print $todate; ?></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<hr />
</div>

<br /><br />

<div id="print">
	<table align="center" style="font-family:Calibri" border="1" bordercolor="silver" cellspacing="0">
		<tr style="background-color:#467898; color:white">
			<th class="shipmentTB4">Technician</th>
			<th class="shipmentTB4">Completed Paid Jobs</th>
			<th class="shipmentTB4">Total Paid Amount</th>
		</tr>
		<?php
		for($i=0;$i<sizeof($re_uid);$i++){
			if($i%2==0) $color1='#F9F9F9' ; else $color1='#EEEEEE' ;
			print '<tr style="background-color:' .$color1.'">
			<td>
				&nbsp;&nbsp; <a href="index.php?components='.$menu_components.'&action=repair_income_one&type=tech&user='.$re_uid[$i].'&from='.$fromdate.'&to='.$todate.'" style="text-decoration:none;">'.ucfirst($re_uname[$i]).'</a>&nbsp;&nbsp;
			</td>
			<td align="right">'.number_format($re_count[$i], $decimal).'&nbsp;&nbsp;</td>
			<td align="right">'.number_format($re_amount[$i], $decimal).'&nbsp;&nbsp;</td>
			</tr>';
		}
		if (is_array($re_count)) {
			$total_re_count = number_format(array_sum($re_count), $decimal);
		} else {
			$total_re_count = 'N/A'; // or some default value
		}
		if (is_array($re_amount)) {
			$total_re_amount = number_format(array_sum($re_amount), $decimal);
		} else {
			$total_re_amount = 'N/A'; // or some default value
		}
		print '<tr style="background-color:#CCCCCC">
			<td>&nbsp;&nbsp;Total</td>
			<td align="right">'.$total_re_count.'&nbsp;&nbsp;</td>
			<td align="right">'.$total_re_amount.'&nbsp;&nbsp;</td>
		</tr>';
	?>
	</table>
	<br />
	<table align="center" style="font-family:Calibri" border="1" bordercolor="silver" cellspacing="0">
		<tr style="background-color:#467898; color:white">
			<th class="shipmentTB4">Delivered By</th>
			<th class="shipmentTB4">Completed Paid Jobs</th>
			<th class="shipmentTB4">Total Paid Amount</th>
		</tr>
		<?php
			for($i=0;$i<sizeof($del_uid);$i++){ if($i%2==0) $color1='#F9F9F9' ; else $color1='#EEEEEE' ;
				print '<tr style="background-color:' .$color1.'">
				<td>
					&nbsp;&nbsp;
					<a href="index.php?components='.$menu_components.'&action=repair_income_one&type=delivery&user='.$del_uid[$i].'&from='.$fromdate.'&to='.$todate.'" style="text-decoration:none;">'.ucfirst($del_uname[$i]).'</a>&nbsp;&nbsp;
				</td>
				<td align="right">'.number_format($del_count[$i], $decimal).'&nbsp;&nbsp;</td>
				<td align="right">'.number_format($del_amount[$i], $decimal).'&nbsp;&nbsp;</td>
				</tr>';
			}
			print '<tr style="background-color:#CCCCCC">
				<td>&nbsp;&nbsp;Total</td>
				<td align="right">'.(is_array($del_count) ? number_format(array_sum($del_count), $decimal) : 'N/A').'&nbsp;&nbsp;</td>
				<td align="right">'.(is_array($del_amount) ? number_format(array_sum($del_amount), $decimal) : 'N/A').'&nbsp;&nbsp;</td>
			</tr>';
		?>
	</table>
</div>

<br />
<table align="center">
	<tr>
		<td align="center">
			<div style="background-color:#6699FF; border:medium; border-color:black; width:80px;">
				<a class="shortcut-button" onclick="printdiv('print','printheader')" href="#">
					<span style="text-decoration:none; font-family:Arial; color:navy;">
						<img src="images/print.png" alt="icon" /><br />Print
					</span>
				</a>
			</div>
		</td>
	</tr>
</table>

<?php
    include_once  'template/footer.php';
?>