<?php
                include_once  'template/header.php';
                
?>
<script type="text/javascript">
	function printdivhr($x,$y){
		document.getElementById('table1').border="1"
		printdiv($x,$y);
	}
	
	function showHideDetails(){
		var details_key=document.getElementById('details_key').value; 
		if(details_key=='hide'){
		document.getElementById('details_key').value='show';
		document.getElementById('details_link').innerHTML='<a style="cursor:pointer; color:blue" onclick="showHideDetails()"><strong>- Hide Details</strong></a>'; 
		document.getElementById('details_div').style.display='block'; 
		}else if(details_key=='show'){
		document.getElementById('details_key').value='hide';
		document.getElementById('details_link').innerHTML='<a style="cursor:pointer; color:blue" onclick="showHideDetails()"><strong>+ Show Details</strong></a>'; 
		document.getElementById('details_div').style.display='none'; 
		}
	}
</script>
<!-- --------------------------------------------------------------------------------------------------------- -->

	<form id="report_form" method="get" action="index.php" >
		<input type="hidden" name="components" value="order_process" />
		<input type="hidden" name="action" value="report_tracking" />
		<table style="font-family:Calibri; border-radius:5px" bgcolor="silver" align="center">
		<tr><td class="shipmentTB4">From Date <input type="date" id="from_date" name="from_date" value="<?php print $from_date; ?>" /></td>
		<td class="shipmentTB4">To Date <input type="date" id="to_date" name="to_date" value="<?php print $to_date; ?>" /></td><td width="50px"></td>
		<td class="shipmentTB4"><a onclick="document.getElementById('report_form').submit();" style="cursor:pointer"><img src="images/search.png" style="width:30px; vertical-align:middle" /></a></td></tr>
		</table>
	</form>
	
	
<!-- -----------------------------------Data------------------------------------------- -->
<div id="printheader" style="display:none" >
	<h1 style="color:navy"><?php print $inf_company; ?></h1>
	<h2 align="center" style="color:#3333FF; text-decoration:underline; font-family:Calibri">Courier Invoice Report</h2>
	<hr />
	<table border="1" align="center" width="600px">
	<tr><td>&nbsp;&nbsp;From Date&nbsp;&nbsp;</td><td>&nbsp;&nbsp;<?php print $from_date; ?>&nbsp;&nbsp;</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;To Date&nbsp;&nbsp;</td><td>&nbsp;&nbsp;<?php print $from_date; ?>&nbsp;&nbsp;</td></tr>
	</table>
	<hr />
</div>

<table align="center"><tr><td>
	<div id="print">
	<br />
	<table align="center" bgcolor="gray" style="border-radius: 10px; font-family:Calibri"><tr><td>
		<table border="0" id="table1" cellspacing="1" bgcolor="white" style="border-radius: 10px;" align="center" border="0"  style="font-size:12pt; font-family:Calibri">
			<tr bgcolor="#467898" style="color:#F5F5F5"><th class="shipmentTB4">Date</th><th class="shipmentTB4">Tracking ID</th><th class="shipmentTB4">Customer Name</th><th class="shipmentTB4">Kg</th><th class="shipmentTB4">Kg (Round)</th><th class="shipmentTB4">Amount</th><th class="shipmentTB4">Amount (10% off)</th></tr>
			<?php 
			for($j=0;$j<sizeof($shp_date);$j++){
				if(($j%2)==0) $color='#E5E5E5'; else $color='#F5F5F5';
				print '<tr bgcolor="'.$color.'" ><td class="shipmentTB4">'.$shp_date[$j].'</td><td class="shipmentTB4">'.$tracking_id[$j].'</td><td class="shipmentTB4">'.$cust_name[$j].'</td><td class="shipmentTB4" align="right">'.$weight[$j].'</td><td class="shipmentTB4" align="right">'.$weight_ro[$j].'</td><td class="shipmentTB4" align="right">'.number_format($amount[$j]).'</td><td class="shipmentTB4" align="right">'.number_format($amount_dis[$j]).'</td></tr>';
			}
			print '<tr bgcolor="#AAAAAA" ><th class="shipmentTB4" colspan="5"><strong>Total</strong></th><th class="shipmentTB4" align="right"><strong>'.number_format(array_sum($amount)).'</strong></th><th class="shipmentTB4" align="right"><strong>'.number_format(array_sum($amount_dis)).'</strong></th></tr>';
			?>
		</table>
	</td></tr></table>
	
	</div>
</td></tr>
<tr><td align="center">
	<br />
	<table align="center"><tr><td>
	<div class="prtbutton1" style="text-align:center">
	<a class="shortcut-button" style="text-decoration:none; font-family:Arial; color:white;" onclick="printdivhr('print','printheader')" href="#">
		<img src="images/print.png" alt="icon" /><br />
		Print
	</a>
	</div>
	</td></tr></table>
</td></tr>
</table>	

<?php
                include_once  'template/footer.php';
?>