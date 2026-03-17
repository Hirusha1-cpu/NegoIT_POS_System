<?php
                include_once  'template/header.php';
?>
	<link rel="stylesheet" href="plugin/jquery/css/demos.css" />

<!-- ------------------Item List----------------------- -->

<div id="printheader" style="display:none" >
	<p align="center"><strong>Return Invoice</strong></p>
</div>
<br />
<table align="center" width="300px"><tr>
<td><input type="button" value="Back" style="font-size:medium; width:50px; font-size:10pt" onclick="window.location = 'index.php?components=report&action=return_items&from_date=<?php print $_REQUEST['from_date']; ?>&to_date=<?php print $_REQUEST['to_date']; ?>'" /></td>
<td><h2 align="center" style="color:#0158C2">Return Invoice Items</h2></td>
</tr></table>
<div id="print">
<table align="center" border="0" cellspacing="0"><tr><td>
<table align="center" width="100%"  style="font-size:10pt" >
	<tr><td colspan="2"><hr></td></tr>
	<tr><td><strong>Return Invoice No: </strong> </td><td><?php print str_pad($id, 7, "0", STR_PAD_LEFT); ?></td></tr>
	<tr><td><strong>Date: </strong> </td><td><?php print $rtn_date; ?></td></tr>
	<tr><td><strong>Salesman: </strong> </td><td><?php print ucfirst($rtn_by); ?></td></tr>
	<tr><td><strong>Customer: </strong> </td><td><?php print $rtn_cust; ?></td></tr>
	<tr><td><strong>Store: </strong> </td><td><?php print $rtn_store; ?></td></tr>
	</table>
</td></tr><tr><td>
<hr>
	<table width="100%" style="font-size:10pt">
	<tr bgcolor="#99CCFF"><th>ITEM</th><th>QTY</th><th>STATUS</th></tr>
	<?php
		for($i=0;$i<sizeof($rtn_itm);$i++){
		print '<tr bgcolor="#EEEEEE"><td>&nbsp;&nbsp;'.$rtn_itm[$i].'&nbsp;&nbsp;</td><td align="right">&nbsp;&nbsp;'.$rtn_qty[$i].'&nbsp;&nbsp;</td><td align="center">&nbsp;&nbsp;'.$rtn_status[$i].'&nbsp;&nbsp;</td></tr>';
		}
	?>
	</table>
	<br>
</td></tr></table>
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