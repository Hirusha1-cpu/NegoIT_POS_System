<?php
                include_once  'template/m_header.php';
                $component=$_GET['components'];
?>
<!-- ------------------------------------------------------------------------------------ -->

<!-- ------------------Item List----------------------- -->
<div class="w3-container" style="margin-top:75px">
<hr>
<div class="w3-row">
  <div class="w3-col s3">
  </div>
  <div class="w3-col">
	<table align="center" height="100%" border="1" cellspacing="0" style="font-size:10pt; font-family:Calibri">
	<tr><td colspan="5" style="border:0; background-color:#F8F8F8; color:maroon; font-weight:bold" align="center">Approval Pending Quotations<br /><br /></td></tr>
	<tr><td colspan="5" style="border:0; background-color:black; color:white; font-weight:bold"></td></tr>
	<tr bgcolor="#E5E5E5"><th>Quotation No</th><th width="100px">Created Date</th><th width="100px">Store</th><th width="350px">Customer</th><th width="100px">Amount</th></tr>
<?php
	for($i=0;$i<sizeof($qm_id);$i++){
		print '<tr bgcolor="#F5F5F5"><td align="center"><a href="index.php?components='.$component.'&action=qo_finish&id='.$qm_id[$i].'">'.str_pad($qm_id[$i], 7, "0", STR_PAD_LEFT).'</a></td><td align="center"><a style="cursor:pointer; color:blue" title="Time: '.substr($qm_created_date[$i],11,5).'" >'.substr($qm_created_date[$i],0,10).'</a></td><td class="shipmentTB3">'.$qm_store[$i].'</td><td class="shipmentTB3">'.$qm_cust[$i].'</td><td class="shipmentTB3" align="right">'.number_format($qm_amount[$i]).'</td></tr>';
	}
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
