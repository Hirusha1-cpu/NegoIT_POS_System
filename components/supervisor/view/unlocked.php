<?php
                include_once  'template/header.php';
?>
<!-- ------------------Item List----------------------- -->

<div id="printheader" style="display:none;" >
	<h1 style="color:navy"><?php print $inf_company; ?></h1>
	<h2 align="center" style="color:#3333FF; text-decoration:underline">Unlocked Bills [Store: <?php print $_COOKIE['store_name']; ?>]</h2>
</div>

<table align="center" bgcolor="#EEEEEF" style="border-radius: 15px;" width="600px"><tr><td align="center" style="font-family:Calibri; color:#0158C2; font-size:14pt" height="40px"><strong>List Of Unlocked Bills [Current Store]</strong></td></tr></table>
<br />
<table align="center"><tr><td>
	<div style="background-color:#EEEEEF; border-radius: 15px; padding-left:10px; padding-right:10px">
	<br />
		<div id="print">
			<table align="center" bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0" style="font-size:10pt; font-family:Calibri">
				<tr><th width="100px">Invoice No</th><th width="100px">Date</th><th width="100px">Time</th><th>&nbsp;&nbsp;Salesman&nbsp;&nbsp;</th><th>&nbsp;&nbsp;Customer&nbsp;&nbsp;</th></tr>
			<?php for($i=0;$i<sizeof($invoice_no);$i++){
				print '<tr><td align="center"><a href="index.php?components=billing&action=finish_bill&id='.$invoice_no[$i].'" >'.str_pad($invoice_no[$i], 7, "0", STR_PAD_LEFT).'</a></td><td align="center">'.$date[$i].'</td><td align="center">'.$time[$i].'</td><td>&nbsp;&nbsp;'.ucfirst($billed_by[$i]).'&nbsp;&nbsp;</td><td>&nbsp;&nbsp;'.$billed_cust[$i].'&nbsp;&nbsp;</td></tr>';
			}	?>
			</table>
		</div>	
	<br />
	</div>
</td></tr></table>
<table align="center"><tr><td align="center">
<div style="background-color:#6699FF; border:medium; border-color:black; width:80px; border-radius: 15px;">
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