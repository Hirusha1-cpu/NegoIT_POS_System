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
<table align="center"><tr><td><h3 style="font-family:Calibri; color:navy">List Of Unlocked Bills [Current Store]</h3></td></tr></table>
	<table align="center" bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0" style="font-size:10pt; font-family:Calibri">
		<tr><th width="100px">Invoice No</th><th width="100px">Date</th><th width="100px">Time</th><th>&nbsp;&nbsp;Salesman&nbsp;&nbsp;</th><th>&nbsp;&nbsp;Customer&nbsp;&nbsp;</th></tr>
	<?php for($i=0;$i<sizeof($invoice_no);$i++){
		print '<tr><td align="center"><a href="index.php?components=billing&action=finish_bill&id='.$invoice_no[$i].'" >'.str_pad($invoice_no[$i], 7, "0", STR_PAD_LEFT).'</a></td><td align="center">'.$date[$i].'</td><td align="center">'.$time[$i].'</td><td>&nbsp;&nbsp;'.ucfirst($billed_by[$i]).'&nbsp;&nbsp;</td><td>&nbsp;&nbsp;'.$billed_cust[$i].'&nbsp;&nbsp;</td></tr>';
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
