<?php
                include_once  'template/m_header.php';
?>
<!-- ------------------------------------------------------------------------------------ -->
<?php 
	if(isset($_REQUEST['id'])) $id=$_REQUEST['id']; else $id=0;
?>
	<style type="text/css">
	.style2 {
		color: navy;
		font-weight: bold;
		background-color:#EEEEEE;
	}
	</style>
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
	<table align="center" bgcolor="#E5E5E5" border="1" cellspacing="0">
	<tr><td colspan="4" style="background-color:silver; color:navy; font-family:Calibri; font-size:15pt">&nbsp;&nbsp;&nbsp;<strong>Today Invoices [Completed]</strong></td></tr>
	<tr><th>Invoice No</th><th width="90px">Time</th><th width="90px">Amount</th><th width="200px">Customer</th></tr>
<?php
$inv=0;
	for($i=0;$i<sizeof($invoice_no);$i++){
		if($bi_discount[$i]>0){ $color='style="color:red"'; $title='title="Discounted Invoice"'; }else{ $color=''; $title=''; }
			print '<tr '.$color.'><td>&nbsp;&nbsp;<a '.$title.' href="index.php?components=bill2&action=finish_bill&id='.$invoice_no[$i].'">'.str_pad($invoice_no[$i], 7, "0", STR_PAD_LEFT).'</a>&nbsp;&nbsp;&nbsp;&nbsp;</td><td width="50px"  align="center">'.$time[$i].'</td><td width="50px" align="right">'.$invoice_total[$i].'&nbsp;&nbsp;</td><td>&nbsp;&nbsp;'.$cust[$i].'</td></tr>';
	}
?>	
	</table>
	<br>
	<table align="center" bgcolor="#E5E5E5" border="1" cellspacing="0">
	<tr><td colspan="4" style="background-color:silver; color:navy; font-family:Calibri; font-size:15pt">&nbsp;&nbsp;&nbsp;<strong>Pending Invoices</strong></td></tr>
	<tr><th>Invoice No</th><th width="90px">Date</th><th width="90px">Amount</th><th width="200px">Customer</th></tr>
<?php
$inv=0;
	for($i=0;$i<sizeof($linvoice_no);$i++){
			$edit='<input type="Button" value="Edit"  onclick="window.location = '."'".'index.php?components=bill2&action=home&id='.$linvoice_no[$i].'&s=1&cust='.$lcust[$i]."'".'" />';
			print '<tr><td>&nbsp;&nbsp;<a href="index.php?components=bill2&action=finish_bill&id='.$linvoice_no[$i].'">'.str_pad($linvoice_no[$i], 7, "0", STR_PAD_LEFT).'</a>&nbsp;&nbsp;&nbsp;&nbsp;'.$edit.'</td><td width="50px"  align="center">'.$ldate[$i].'</td><td width="50px" align="right">'.$linvoice_total[$i].'&nbsp;&nbsp;</td><td>&nbsp;&nbsp;'.$lcust[$i].'</td></tr>';
	}
?>	
	</table>
	<br>
	<table align="center" bgcolor="#E5E5E5" border="1" cellspacing="0">
	<tr><td colspan="4" style="background-color:silver; color:navy; font-family:Calibri; font-size:15pt">&nbsp;&nbsp;&nbsp;<strong>Payment Invoices</strong></td></tr>
	<tr><th>Invoice No</th><th width="90px">Time</th><th width="90px">Amount</th><th width="200px">Customer</th></tr>
<?php
$inv=0;
	for($i=0;$i<sizeof($py_invno);$i++){
			print '<tr><td align="center"><a href="index.php?components=bill2&action=finish_payment&id='.$py_invno[$i].'">'.str_pad($py_invno[$i], 7, "0", STR_PAD_LEFT).'</a></td><td width="50px" align="center">'.$py_time[$i].'</td><td align="right">'.number_format($py_amount[$i]).'&nbsp;&nbsp;</td><td>&nbsp;&nbsp;'.$py_cust[$i].'</td></tr>';
	}
?>	
	</table>
	<br>
	<table align="center" bgcolor="#E5E5E5" border="1" cellspacing="0" style="font-family:Calibri">
	<tr><td colspan="4" style="background-color:silver; color:navy; font-family:Calibri; font-size:15pt">&nbsp;&nbsp;&nbsp;<strong>Return Invoices</strong></td></tr>
	<tr><th>Invoice No</th><th width="90px">Time</th><th width="290px">Customer</th></tr>
<?php
$inv=0;
	for($i=0;$i<sizeof($rt_invno);$i++){
			print '<tr><td align="center"><a href="index.php?components=bill2&action=finish_return&id='.$rt_invno[$i].'">'.str_pad($rt_invno[$i], 7, "0", STR_PAD_LEFT).'</a></td><td width="50px" align="center">'.$rt_time[$i].'</td><td>&nbsp;&nbsp;'.$rt_cust[$i].'</td></tr>';
	}
?>	
	</table>
  </div>
</div>
</div>
<hr>


<?php
                include_once  'template/m_footer.php';
?>