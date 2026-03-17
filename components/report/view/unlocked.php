<?php
                include_once  'template/header.php';
?>
<!-- ------------------Item List----------------------- -->

<div id="printheader" style="display:none" >
	<h1 style="color:navy"><?php print $inf_company; ?></h1>
	<h2 align="center" style="color:#3333FF; text-decoration:underline">Unlocked Bills</h2>
</div>

<table align="center"><tr><td><h3 style="font-family:Calibri; color:navy">List Of Unlocked Bills</h3></td></tr></table>
<br>
<div id="print">
	<table align="center" bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0" style="font-size:10pt; font-family:Calibri">
		<tr><th width="100px">Invoice No</th><th width="100px">Date</th><th width="100px">Time</th><th>&nbsp;&nbsp;Salesman&nbsp;&nbsp;</th><th>&nbsp;&nbsp;Store&nbsp;&nbsp;</th></tr>
	<?php for($i=0;$i<sizeof($invoice_no);$i++){
		if($lock[$i]==2){ $color='blue'; $comment='Un-Packed Cust Order'; }else{ $color='black'; $comment=''; }
		print '<tr style="color:'.$color.'" ><td align="center"><a title="'.$comment.'" href="index.php?components=billing&action=finish_bill&id='.$invoice_no[$i].'" >'.str_pad($invoice_no[$i], 7, "0", STR_PAD_LEFT).'</a></td><td align="center">'.$date[$i].'</td><td align="center">'.$time[$i].'</td><td>&nbsp;&nbsp;'.ucfirst($billed_by[$i]).'&nbsp;&nbsp;</td><td>&nbsp;&nbsp;'.$billed_store[$i].'&nbsp;&nbsp;</td></tr>';
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