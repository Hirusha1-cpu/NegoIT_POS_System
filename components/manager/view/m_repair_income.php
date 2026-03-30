<?php
                include_once  'template/m_header.php';
                $menu_components=$_GET['components'];
?>
<!-- ------------------------------------------------------------------------------------ -->
<!-- ------------------Item List----------------------- -->
<div class="w3-container" style="margin-top:75px">
<hr>
<div class="w3-row">
  <div class="w3-col s3">
  </div>
  <div class="w3-col">
	<table align="center"cellspacing="0" style="font-family:Calibri" bgcolor="#F0F0F0" border="0" >
	<tr><td width="10px"></td><td>
		<form id="search_form" action="index.php" method="get" >
			<input type="hidden" name="components" value="<?php print  $menu_components; ?>" />
			<input type="hidden" name="action" value="repair_income" />
			<table>
			<tr><td colspan="3" align="center" style="color:#467898;"><strong>Repair Income Summary Report</strong></td></tr>
			<tr><td>From Date </td><td><input type="date" id="datefrom" name="datefrom" value="<?php print $fromdate; ?>" /></td><td rowspan="2" width="50px">
				<a onclick="document.getElementById('search_form').submit();" style="cursor:pointer"><img src="images/search.png" style="width:30px; vertical-align:middle" /></a>
			</td></tr>
			<tr><td>To Date </td><td><input type="date" id="dateto" name="dateto" value="<?php print $todate; ?>" /></td></tr>
			</table>
		</form>
	</td><td width="10px"></td>
	</tr>
	</table>
	<br />
</div>
  <div class="w3-col">
	<table align="center" style="font-family:Calibri" border="1" bordercolor="silver" cellspacing="0">
	<tr style="background-color:#467898; color:white"><th class="shipmentTB4">Technicient</th><th class="shipmentTB4">Completed Paid Jobs</th><th class="shipmentTB4">Total Paid Amount</th></tr>
	<?php 
	for($i=0;$i<sizeof($re_uid);$i++){
		if($i%2==0) $color1='#F9F9F9'; else $color1='#EEEEEE';
		print '<tr style="background-color:'.$color1.'"><td>&nbsp;&nbsp;<a href="index.php?components='.$menu_components.'&action=repair_income_one&type=tech&user='.$re_uid[$i].'&from='.$fromdate.'&to='.$todate.'" style="text-decoration:none;">'.ucfirst($re_uname[$i]).'</a>&nbsp;&nbsp;</td><td align="right">'.number_format($re_count[$i]).'&nbsp;&nbsp;</td><td align="right">'.number_format($re_amount[$i]).'&nbsp;&nbsp;</td></tr>';
	}
		print '<tr style="background-color:#CCCCCC"><td>&nbsp;&nbsp;Total</td><td align="right">'.number_format(array_sum($re_count)).'&nbsp;&nbsp;</td><td align="right">'.number_format(array_sum($re_amount)).'&nbsp;&nbsp;</td></tr>';
	?>
	</table>
	<br />
	<table align="center" style="font-family:Calibri" border="1" bordercolor="silver" cellspacing="0">
	<tr style="background-color:#467898; color:white"><th class="shipmentTB4">Delivered By</th><th class="shipmentTB4">Completed Paid Jobs</th><th class="shipmentTB4">Total Paid Amount</th></tr>
	<?php 
	for($i=0;$i<sizeof($del_uid);$i++){
		if($i%2==0) $color1='#F9F9F9'; else $color1='#EEEEEE';
		print '<tr style="background-color:'.$color1.'"><td>&nbsp;&nbsp;<a href="index.php?components='.$menu_components.'&action=repair_income_one&type=delivery&user='.$del_uid[$i].'&from='.$fromdate.'&to='.$todate.'" style="text-decoration:none;">'.ucfirst($del_uname[$i]).'</a>&nbsp;&nbsp;</td><td align="right">'.number_format($del_count[$i]).'&nbsp;&nbsp;</td><td align="right">'.number_format($del_amount[$i]).'&nbsp;&nbsp;</td></tr>';
	}
		print '<tr style="background-color:#CCCCCC"><td>&nbsp;&nbsp;Total</td><td align="right">'.number_format(array_sum($del_count)).'&nbsp;&nbsp;</td><td align="right">'.number_format(array_sum($del_amount)).'&nbsp;&nbsp;</td></tr>';
	?>
	</table>
</div>	
  </div>
</div>
</div>
<hr>
<br />
<?php
                include_once  'template/m_footer.php';
?>
