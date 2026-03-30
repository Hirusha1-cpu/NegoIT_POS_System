<?php
                include_once  'template/m_header.php';
?>
<!-- ------------------------------------------------------------------------------------ -->
</head>

<div class="w3-container" style="margin-top:75px">
<?php 
	if(isset($_REQUEST['message'])){
		if($_REQUEST['re']=='success') $color='green'; else $color='red';
	print '<span style="color:'.$color.'; font-weight:bold;font-size:large;">'.$_REQUEST['message'].'</span>'; 
	}
?>	

<hr>
<div class="w3-row">
  <div class="w3-col s3">
  </div>
  <div class="w3-col">

	<h2 align="center" style="color:#0158C2">Return Invoice Items</h2>
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

  </div>
</div>
</div>
<hr>


<?php
                include_once  'template/m_footer.php';
?>