<?php
                include_once  'template/header.php';
                $menu_components=$_GET['components'];
                $userwrd="";
                if($_GET['type']=='tech') $userwrd="Technicient";
                if($_GET['type']=='delivery') $userwrd="Delivered By";
?>
    <script type="text/javascript">

    </script>

<!-- ------------------Item List----------------------- -->
	<table align="center" height="100%" cellspacing="0" style="font-family:Calibri" bgcolor="#F0F0F0" border="0" >
	<tr><td width="50px"></td><td>
		<form id="search_form" action="index.php" method="get" >
			<input type="hidden" name="components" value="<?php print  $menu_components; ?>" />
			<input type="hidden" name="action" value="repair_income_one" />
			<input type="hidden" name="type" value="<?php print $_GET['type']; ?>" />
			<input type="hidden" name="user" value="<?php print $_GET['user']; ?>" />
			<table>
			<tr><td colspan="4" align="center" style="color:#467898;"><strong>Repair Income Detailed Report</strong><hr /></td></tr>
			<tr><td colspan="2" style="color:#467898;"><strong><?php print $userwrd; ?>: <span style="color:maroon"><?php print $tech_name; ?></span></strong></td><td align="right" colspan="2"><input type="button" value="Back" style="width:80px" onclick="window.location = 'index.php?components=<?php print  $menu_components; ?>&action=repair_income&datefrom=<?php print  $_GET['from']; ?>&dateto=<?php print  $_GET['to']; ?>'" /></td></tr>
			<tr><td>From Date <input type="date" id="from" name="from" value="<?php print $_GET['from']; ?>" /></td><td width="100px"></td>
			    <td>To Date <input type="date" id="to" name="to" value="<?php print $_GET['to']; ?>" /></td><td>
			<a onclick="document.getElementById('search_form').submit();" style="cursor:pointer"><img src="images/search.png" style="width:30px; vertical-align:middle" /></a>
			</td></tr></table>
		</form>
	</td><td width="50px"></td>
	</tr>
	</table>
	
<div id="printheader" style="display:none" >
	<h1 style="color:navy"><?php print $inf_company; ?></h1>
	<h2 align="center" style="color:#3333FF; text-decoration:underline; font-family:Calibri">Repair Income Detailed Report</h2>
	<table><tr><td>
	<table style="font-size:12pt" border="1" cellspacing="0">
		<tr><td style="background-color:#C0C0C0; padding-left:10px; padding-right:10px"><?php print $userwrd; ?></td><td style="background-color:#EEEEEE; padding-left:10px; padding-right:10px;"><?php print $tech_name; ?></td></tr>
		<tr><td style="background-color:#C0C0C0; padding-left:10px">From Date</td><td style="background-color:#EEEEEE; padding-left:10px; padding-right:10px;"><?php print $fromdate; ?></td></tr>
		<tr><td style="background-color:#C0C0C0; padding-left:10px">To Date</td><td style="background-color:#EEEEEE; padding-left:10px; padding-right:10px;"><?php print $todate; ?></td></tr>
	</table>
	</td></tr></table>
	<hr />
</div>

<br /><br />
<div id="print">
	<table align="center" style="font-family:Calibri" border="1" bordercolor="silver" cellspacing="0">
	<tr style="background-color:#467898; color:white"><th class="shipmentTB4">Invoice No</th><th class="shipmentTB4">Delivered Date</th><th class="shipmentTB4">Billed By</th><th class="shipmentTB4">Customer</th><th class="shipmentTB4">Amount</th></tr>
	<?php 
	for($i=0;$i<sizeof($ro_inv);$i++){
		if($i%2==0) $color1='#F9F9F9'; else $color1='#EEEEEE';
		print '<tr style="background-color:'.$color1.'"><td align="center">&nbsp;&nbsp;<a href="index.php?components=billing&action=finish_bill&id='.$ro_inv[$i].'" style="text-decoration:none;">'.str_pad($ro_inv[$i], 7, "0", STR_PAD_LEFT).'</a>&nbsp;&nbsp;</td><td align="center">&nbsp;&nbsp;'.$ro_deliver_date[$i].'&nbsp;&nbsp;</td><td>&nbsp;&nbsp;'.$ro_billed_by[$i].'&nbsp;&nbsp;</td><td>&nbsp;&nbsp;'.$ro_cust[$i].'&nbsp;&nbsp;</td><td align="right">'.number_format($ro_amount[$i]).'&nbsp;&nbsp;</td></tr>';
	}
		print '<tr style="background-color:#CCCCCC"><td colspan="4">&nbsp;&nbsp;Total</td><td align="right">&nbsp;&nbsp;'.number_format(array_sum($ro_amount)).'&nbsp;&nbsp;</td></tr>';
	?>
	</table>
</div>

<br />
<table align="center" ><tr><td align="center">
<div style="background-color:#6699FF; border:medium; border-color:black; width:80px;">
	<a class="shortcut-button" onclick="printdiv('print','printheader')" href="#"><span style="text-decoration:none; font-family:Arial; color:navy;">
	<img src="images/print.png" alt="icon" /><br />
	Print
	</span></a>
</div>
</td></tr></table>

<?php
                include_once  'template/footer.php';
?>