<?php
                include_once  'template/m_header.php';
?>
<!-- ------------------------------------------------------------------------------------ -->
<!-- ------------------Item List----------------------- -->
<div class="w3-container" style="margin-top:75px">
<hr>
<div class="w3-row">
  <div class="w3-col s3">
  </div>
  <div class="w3-col">

	<table align="center" bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0" style="font-size:10pt">
		<tr><th>Item</th><th>Available QTY</th><th>Last Order QTY</th><th>New Order QTY</th></tr>
	<?php for($i=0;$i<sizeof($po_item);$i++){
		print '<tr><td>&nbsp;&nbsp;'.$po_item[$i].'</td><td align="right">'.number_format($po_now_qty[$i]).'&nbsp;&nbsp;</td><td align="right">'.number_format($po_last_qty[$i]).'&nbsp;&nbsp;</td><td><input type="text" value="'.number_format($po_avg_new[$i]).'" width="20px" style="text-align:right; width:100px; padding-right:10px;" /></td></tr>';
	}	?>
	</table>

  </div>
</div>
</div>
<hr>
<br />
<?php
                include_once  'template/m_footer.php';
?>
