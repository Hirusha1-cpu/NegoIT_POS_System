<?php
                include_once  'template/header.php';
                
?>
<script type="text/javascript">
	function printdivhr($x,$y){
		document.getElementById('table1').border="1"
		document.getElementById('table2').border="1"
		document.getElementById('table3').border="1"
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
		<input type="hidden" name="action" value="report_commision" />
		<table style="font-family:Calibri; border-radius:5px" bgcolor="silver" align="center">
		<tr><td class="shipmentTB4">From Date <input type="date" id="from_date" name="from_date" value="<?php print $from_date; ?>" /></td>
		<td class="shipmentTB4">To Date <input type="date" id="to_date" name="to_date" value="<?php print $to_date; ?>" /></td><td width="50px"></td>
		<td>Store
			<select id="store" name="store" onchange="filterStore()">
				<option value="">-SELECT-</option>
				<?php 
				$header_store='';
				for($i=0;$i<sizeof($store_id);$i++){
					if($store_id[$i]==$store){ $select='selected="selected"'; $header_store=$store_name[$i]; }else $select='';
					print '<option value="'.$store_id[$i].'" '.$select.'>'.$store_name[$i].'</option>';
				}	?>
			</select>
		</td>
		<td class="shipmentTB4"><a onclick="document.getElementById('report_form').submit();" style="cursor:pointer"><img src="images/search.png" style="width:30px; vertical-align:middle" /></a></td></tr>
		</table>
	</form>
	
	
<!-- -----------------------------------Data------------------------------------------- -->
<div id="printheader" style="display:none" >
	<h1 style="color:navy"><?php print $inf_company; ?></h1>
	<h2 align="center" style="color:#3333FF; text-decoration:underline; font-family:Calibri">Oder Process Commission Report</h2>
	<hr />
	<table border="1" align="center" width="600px">
	<tr><td>&nbsp;&nbsp;From Date&nbsp;&nbsp;</td><td>&nbsp;&nbsp;<?php print $from_date; ?>&nbsp;&nbsp;</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;To Date&nbsp;&nbsp;</td><td>&nbsp;&nbsp;<?php print $from_date; ?>&nbsp;&nbsp;</td></tr>
	<tr><td>&nbsp;&nbsp;Store&nbsp;&nbsp;</td><td colspan="3">&nbsp;&nbsp;<?php print $header_store; ?>&nbsp;&nbsp;</td></tr>
	</table>
	<hr />
</div>

<table align="center"><tr><td>
	<div id="print">
	<div>
	<table align="center" bgcolor="gray" style="border-radius: 10px; font-family:Calibri"><tr><td>
		<table border="0" id="table1" cellspacing="1" bgcolor="white" style="border-radius: 10px;" align="center" border="0"  style="font-size:12pt; font-family:Calibri">
			<tr bgcolor="#467898" style="color:#F5F5F5"><th class="shipmentTB4" align="center" colspan="5">Same Day Finalyzed Orders (Order Date=Picked Date=Packed Date)</th></tr>
			<tr bgcolor="#E5E5E5" ><td class="shipmentTB4">Order Total Amount</td><td class="shipmentTB4" align="right"><?php print number_format(array_sum($r1_amount)); ?></td></tr>
		</table>
	</td></tr></table>
	</div>
	<br />
	<table align="center" bgcolor="gray" style="border-radius: 10px; font-family:Calibri"><tr><td>
		<table border="0" id="table2" cellspacing="1" bgcolor="white" style="border-radius: 10px;" align="center" border="0"  style="font-size:12pt; font-family:Calibri">
			<tr bgcolor="#467898" style="color:#F5F5F5"><th class="shipmentTB4" align="center" colspan="5">Picked By |Same Day Finalyzed Orders (Picked Date=Packed Date)</th></tr>
			<tr bgcolor="#467898" style="color:#F5F5F5"><th class="shipmentTB4">Picked By</th><th class="shipmentTB4">Order Total</th></tr>
			<?php 
			for($j=0;$j<sizeof($r2_pick_uniq);$j++){
				$r2_total=0;
				if(($j%2)==0) $color='#E5E5E5'; else $color='#F5F5F5';
				for($i=0;$i<sizeof($r2_odr_no);$i++){
					if($r2_pick_by[$i]==$r2_pick_uniq[$j]){
						$r2_total+=$r2_amount[$i];
					}
				}
				print '<tr bgcolor="'.$color.'" ><td class="shipmentTB4">'.$user_arr[$r2_pick_uniq[$j]].'</td><td class="shipmentTB4" align="right">'.number_format($r2_total).'</td></tr>';
			}
			?>
		</table>
	</td></tr></table>
	
	
	<br />
	<table align="center" bgcolor="gray" style="border-radius: 10px; font-family:Calibri"><tr><td>
		<table border="0" id="table3" cellspacing="1" bgcolor="white" style="border-radius: 10px;" align="center" border="0"  style="font-size:12pt; font-family:Calibri">
			<tr bgcolor="#467898" style="color:#F5F5F5"><th class="shipmentTB4" align="center" colspan="5">Packed By |Same Day Finalyzed Orders (Picked Date=Packed Date)</th></tr>
			<tr bgcolor="#467898" style="color:#F5F5F5"><th class="shipmentTB4">Packed By</th><th class="shipmentTB4">Order Total</th></tr>
			<?php 
			for($j=0;$j<sizeof($r2_pack_uniq);$j++){
				$r2_total=0;
				if(($j%2)==0) $color='#E5E5E5'; else $color='#F5F5F5';
				for($i=0;$i<sizeof($r2_odr_no);$i++){
					if($r2_pack_by[$i]==$r2_pack_uniq[$j]){
						$r2_total+=$r2_amount[$i];
					}
				}
				print '<tr bgcolor="'.$color.'" ><td class="shipmentTB4">'.$user_arr[$r2_pack_uniq[$j]].'</td><td class="shipmentTB4" align="right">'.number_format($r2_total).'</td></tr>';
			}
			?>
		</table>
	</td></tr></table>
	</div>
</td></tr>
<tr><td style="font-family:Calibri">
	<input type="hidden" id="details_key" value="hide" />
	<div id="details_link"><a style="cursor:pointer; color:blue;" onclick="showHideDetails()"><strong>+ Show Details</strong></a></div>	
	<div id="details_div" style="display:none" >
		<table align="center" bgcolor="gray" style="border-radius: 10px; font-family:Calibri"><tr><td>
			<table border="0" id="table0" cellspacing="1" bgcolor="white" style="border-radius: 10px;" align="center" border="0"  style="font-size:12pt; font-family:Calibri">
				<tr bgcolor="#467898" style="color:#F5F5F5"><th class="shipmentTB4" align="center" colspan="5">Same Day Finalyzed Orders (Order Date=Picked Date=Packed Date)</th></tr>
				<tr bgcolor="#467898" style="color:#F5F5F5"><th class="shipmentTB4">Order Number</th><th class="shipmentTB4">Order Date</th><th class="shipmentTB4">Picked Date</th><th class="shipmentTB4">Packed Date</th><th class="shipmentTB4">Amount</th></tr>
				<?php 
				$r1_total=0;
				// for($i=0;$i<sizeof($r1_odr_no);$i++){
				for($i = 0; $i < sizeof($r1_odr_no ?? []); $i++){
					$r1_total+=$r1_amount[$i];
					if(($i%2)==0) $color='#E5E5E5'; else $color='#F5F5F5';
					print '<tr bgcolor="'.$color.'" ><td class="shipmentTB4" align="center"><a style="text-decoration:none" href="index.php?components=order_process&action=list_one&id='.$r1_odr_no[$i].'">'.str_pad($r1_odr_no[$i], 7, "0", STR_PAD_LEFT).'</a></td><td class="shipmentTB4" align="center">'.$r1_odr_date[$i].'</td><td class="shipmentTB4" align="center">'.$r1_pick_date[$i].'</td><td class="shipmentTB4" align="center">'.$r1_pack_date[$i].'</td><td class="shipmentTB4" align="right">'.number_format($r1_amount[$i]).'</td></tr>';
				}
					print '<tr bgcolor="#CCCCCC" ><td class="shipmentTB4" align="right" colspan="4"><strong>Total</strong></td><td class="shipmentTB4" align="right">'.number_format($r1_total).'</td></tr>';
				?>
			</table>
		</td></tr></table>
		<br />
		<table align="center" bgcolor="gray" style="border-radius: 10px; font-family:Calibri"><tr><td>
			<table border="0" id="table0" cellspacing="1" bgcolor="white" style="border-radius: 10px;" align="center" border="0"  style="font-size:12pt; font-family:Calibri">
				<tr bgcolor="#467898" style="color:#F5F5F5"><th class="shipmentTB4" align="center" colspan="6">Same Day Finalyzed Orders (Picked Date=Packed Date)</th></tr>
				<tr bgcolor="#467898" style="color:#F5F5F5"><th class="shipmentTB4">Order Number</th><th class="shipmentTB4">Picked By</th><th class="shipmentTB4">Picked Date</th><th class="shipmentTB4">Packed By</th><th class="shipmentTB4">Packed Date</th><th class="shipmentTB4">Amount</th></tr>
				<?php 
				$r2_total=0;
				for($i=0;$i<sizeof($r2_odr_no);$i++){
					$r2_total+=$r2_amount[$i];
					if(($i%2)==0) $color='#E5E5E5'; else $color='#F5F5F5';
					print '<tr bgcolor="'.$color.'" ><td class="shipmentTB4" align="center"><a style="text-decoration:none" href="index.php?components=order_process&action=list_one&id='.$r2_odr_no[$i].'">'.str_pad($r2_odr_no[$i], 7, "0", STR_PAD_LEFT).'</a></td><td class="shipmentTB4" align="center">'.$user_arr[$r2_pick_by[$i]].'</td><td class="shipmentTB4" align="center">'.$r2_pick_date[$i].'</td><td class="shipmentTB4" align="center">'.$user_arr[$r2_pack_by[$i]].'</td><td class="shipmentTB4" align="center">'.$r2_pack_date[$i].'</td><td class="shipmentTB4" align="right">'.number_format($r2_amount[$i]).'</td></tr>';
				}
					print '<tr bgcolor="#CCCCCC" ><td class="shipmentTB4" align="right" colspan="5"><strong>Total</strong></td><td class="shipmentTB4" align="right">'.number_format($r2_total).'</td></tr>';
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