<?php
    include_once  'template/header.php';
?>

<script type="text/javascript">
	function printdivBorderx($x,$y){
		document.getElementById('data_table').border="1";
		document.getElementById('data_table').cellSpacing="0";
		printdiv($x,$y);
		document.getElementById('data_table').border="0";
		document.getElementById('data_table').cellSpacing="2";

	}
</script>
<!-- -------------------------------------------------------------------------------------------------------------------- -->
<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px" /></div>

<table align="center" style="font-size:12pt"><tr><td>
<?php 
	if(isset($_REQUEST['message'])){
		if($_REQUEST['re']=='success') $color='green'; else $color='#DD3333';
		print '<script type="text/javascript">document.getElementById("notifications").innerHTML='."'".'<span style="color:'.$color.'; font-weight:bold;font-size:12pt;">'.$_REQUEST['message'].'</span>'."'".';</script>';
	}
?></td></tr></table>
<div id="print">
	<div id="printheader" style="display:none"  >
		<h1 style="color:navy"><?php print $inf_company; ?></h1>
		<h2 align="center" style="color:#3333FF; text-decoration:underline">Items in Pending Transfers</h2>
		<hr>
	</div>

	<table align="center" style="font-family:Calibri; font-size:12pt" id="data_table">
	<tr style="background-color:#467898; color:white;">
	<th class="shipmentTB3"></th>
	<th class="shipmentTB3">Transfer No</th>
	<th class="shipmentTB3">Invoice No</th>
	<th class="shipmentTB3">From Store</th>
	<th class="shipmentTB3">To Store</th>
	<th class="shipmentTB3">Description</th>
	<th class="shipmentTB3">Qty</th>
	<th class="shipmentTB3">Created By</th>
	<th class="shipmentTB3">Date</th>
	<th class="shipmentTB3">Time</th>
	<th class="shipmentTB3">Status</th></tr>
	<tbody>
			<?php 
		for($i=0;$i<sizeof($gtn_no);$i++){
		if(($i%2)==0) $color='#FAFAFA'; else $color='#EEEEEE';
			$invoice_no = $invoice_no[$i] == null ? $invoice_no = "" : $invoice_no[$i];
			print '<tr style="background-color:'.$color.'">
				<td class="shipmentTB3"><input type="checkbox" /></td>
				<td class="shipmentTB3" align="center"><a href="index.php?components=trans&action=print_gtn&id='.$gtn_no[$i].'&approve_permission=0" style="text-decoration:none; color:blue; cursor:pointer" >'.str_pad($gtn_no[$i], 7, "0", STR_PAD_LEFT).'</a></td>';?>

		<?php 
				if($invoice_no[$i] != null)
				print '<td class="shipmentTB3" align="center"><a href="index.php?components=billing&action=finish_bill&id='.$invoice_no[$i].'" style="text-decoration:none; color:blue; cursor:pointer" >' .str_pad($invoice_no[$i], 7, "0", STR_PAD_LEFT).'</a></td>'; 
				else 
				print '<td class="shipmentTB3" align="center">'.$invoice_no[$i].'</td>';?>
		<?php
				print '<td class="shipmentTB3">'.$from_store[$i].'</td>
				<td class="shipmentTB3">'.$to_store[$i].'</td>
				<td class="shipmentTB3">'.$description[$i].'</td>
				<td class="shipmentTB3" align="right">'.$qty[$i].'</td>
				<td class="shipmentTB3">'.ucfirst($username[$i]).'</td>
				<td class="shipmentTB3" align="center">'.$date[$i].'</td>
				<td class="shipmentTB3" align="center">'.$time[$i].'</td>
				<td style="color:'.$gtn_color[$i].'" class="shipmentTB3">'.$gtn_status[$i].'</td>
				<tr>';
			}
		?>
	</tbody>
	</table>
</div>
<br>
<table align="center"><tr><td align="center">
<div style="background-color:#6699FF; border:medium; border-color:black; width:80px;">
	<a class="shortcut-button" onclick="printdivBorderx('print','printheader')" href="#"><span style="text-decoration:none; font-family:Arial; color:navy;">
	<img src="images/print.png" alt="icon" /><br />
	Print
	</span></a>
</div>
<?php
    include_once  'template/footer.php';
?>