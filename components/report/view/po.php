<?php
                include_once  'template/header.php';
?>
<!-- ------------------Item List----------------------- -->

<div id="printheader" style="display:none" >
	<h1 style="color:navy">Zigo Technology PVT Ltd.</h1>
	<h2 align="center" style="color:#3333FF; text-decoration:underline">Purchase Order</h2>
	<table style="font-size:12pt" border="1" cellspacing="0">
		<tr><td style="background-color:#C0C0C0; padding-left:10px; padding-right:10px" >Date</td><td style="background-color:#EEEEEE; padding-left:10px">&nbsp;&nbsp;<?php print date("Y-m-d",time()); ?>&nbsp;&nbsp;</td></tr>
	</table><br />
	<p>Note: Zigo Technology Purchase Order</p><hr>
</div>
<br /><br />
<div id="print">
	<table align="center" bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0" style="font-size:10pt">
		<tr><th>Item</th><th>Available QTY</th><th>Last Order QTY</th><th>New Order QTY</th></tr>
	<?php for($i=0;$i<sizeof($po_item);$i++){
		print '<tr><td>&nbsp;&nbsp;'.$po_item[$i].'</td><td align="right">'.number_format($po_now_qty[$i]).'&nbsp;&nbsp;</td><td align="right">'.number_format($po_last_qty[$i]).'&nbsp;&nbsp;</td><td><input type="text" value="'.number_format($po_avg_new[$i]).'" width="20px" style="text-align:right; width:100px; padding-right:10px;" /></td></tr>';
	}	?>
	</table>
</div>	
<table align="center"><tr><td align="center">
<div style="background-color:#6699FF; border:medium; border-color:black; width:80px;">
	<a class="shortcut-button" onclick="printdiv('print','printheader')" href="#"><span style="text-decoration:none; font-family:Arial; color:navy;">
	<img src="images/print.png" alt="icon" /><br />
	Print
	</span></a>
</div>

</td></tr></table>
<br />
<?php
                include_once  'template/footer.php';
?>