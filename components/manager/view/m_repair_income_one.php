<?php
                include_once  'template/m_header.php';
                $menu_components=$_GET['components'];
                $userwrd="";
                if($_GET['type']=='tech') $userwrd="Technicient";
                if($_GET['type']=='delivery') $userwrd="Delivered By";
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
			<input type="hidden" name="action" value="repair_income_one" />
			<input type="hidden" name="type" value="<?php print $_GET['type']; ?>" />
			<input type="hidden" name="user" value="<?php print $_GET['user']; ?>" />
			<table>
			<tr><td colspan="3" align="center" style="color:#467898;"><strong>Repair Income Summary Report</strong></td></tr>
			<tr><td colspan="3" align="center" bgcolor="#AAAAAA" height="2px"></td></tr>
			<tr><td colspan="3" style="color:#467898;"><strong><?php print $userwrd; ?> : <span style="color:maroon"><?php print $tech_name; ?></span></strong></td></tr>
			<tr><td>From Date </td><td><input type="date" id="from" name="from" value="<?php print $_GET['from']; ?>" /></td><td rowspan="2" width="50px">
				<a onclick="document.getElementById('search_form').submit();" style="cursor:pointer"><img src="images/search.png" style="width:30px; vertical-align:middle" /></a>
			</td></tr>
			<tr><td>To Date </td><td><input type="date" id="to" name="to" value="<?php print $_GET['to']; ?>" /></td></tr>
			</table>
		</form>
	</td><td width="10px"></td>
	</tr>
	</table>
	<br />
</div>
  <div class="w3-col">
	<table align="center" style="font-family:Calibri; font-size:x-small" border="1" bordercolor="silver" cellspacing="0">
	<tr style="background-color:#467898; color:white"><th class="shipmentTB3">Invoice No</th><th class="shipmentTB3">Delivered Date</th><th class="shipmentTB3">Billed By</th><th class="shipmentTB3" width="200px">Customer</th><th class="shipmentTB3">Amount</th></tr>
	<?php 
	for($i=0;$i<sizeof($ro_inv);$i++){
		if($i%2==0) $color1='#F9F9F9'; else $color1='#EEEEEE';
		print '<tr style="background-color:'.$color1.'"><td align="center">&nbsp;<a href="index.php?components=billing&action=finish_bill&id='.$ro_inv[$i].'" style="text-decoration:none;">'.str_pad($ro_inv[$i], 7, "0", STR_PAD_LEFT).'</a>&nbsp;</td><td align="center">&nbsp;'.$ro_deliver_date[$i].'&nbsp;</td><td>&nbsp;'.$ro_billed_by[$i].'&nbsp;</td><td>&nbsp;'.$ro_cust[$i].'&nbsp;</td><td align="right">'.number_format($ro_amount[$i]).'&nbsp;</td></tr>';
	}
		print '<tr style="background-color:#CCCCCC"><td colspan="4">&nbsp;Total</td><td align="right">&nbsp;'.number_format(array_sum($ro_amount)).'&nbsp;</td></tr>';
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
