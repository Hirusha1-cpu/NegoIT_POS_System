<?php
                include_once  'template/header.php';
?>

	<link rel="stylesheet" href="plugin/jquery/css/jquery.ui.all.css" />
	<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.core.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.widget.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.position.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.autocomplete.js"></script>
	<link rel="stylesheet" href="plugin/jquery/css/demos.css" />
<!-- ------------------------------------------------------------------------------------------------------------------ -->

<table align="center" style="font-size:12pt"><tr><td>
<?php 
	if(isset($_REQUEST['message'])){
		if($_REQUEST['re']=='success') $color='green'; else $color='red';
	print '<span style="color:'.$color.'; font-weight:bold;">'.$_REQUEST['message'].'</span>'; 
	}
?></td></tr></table>
<div id="printheader" style="display:none;" >
	<h1 style="color:navy; font-family:Calibri"><?php print $inf_company; ?></h1>
	<h2 align="center" style="color:#3333FF; text-decoration:underline; font-family:Calibri">Journal Entry</h2>
	<hr />
	<table align="center" width="95%" style="font-family:Calibri"><tr><td width="60%">
		<table>
		<tr><td width="130px"><strong>Journal No</strong></td><td style="color:navy"><strong><?php print str_pad($id, 7, "0", STR_PAD_LEFT); ?></strong></td></tr>
		<tr><td><strong>Date</strong></td><td><?php print $journal_date; ?></td></tr>
		<tr><td><strong>Store</strong></td><td><?php print ucfirst($store); ?></td></tr>
		<tr><td><strong>Ref No</strong></td><td><?php print $ref_no; ?></td></tr>
		</table>
	</td></tr></table>
	<hr />
	<br />
</div>
<div id="print" style="display:none;" >
	<table align="center" style="font-family:Calibri; font-size:10pt;" >
	<tr><td colspan="7"><table width="100%" border="1" cellspacing="0">
	<tr bgcolor="#CCCCEE" style="font-size:12pt; color:navy; font-weight:bold;"><td></td><td width="180px">&nbsp;&nbsp;Account</td><td align="center">Debits</td><td align="center">Credits</td><td width="350px">&nbsp;&nbsp;Description</td><td align="center">Type</td><td align="center">Name</td></tr>
	<?php for($i=0;$i<sizeof($ji_account);$i++){
		if($ji_cr_dr[$i]=='cr'){ $cr_amount=number_format(-$ji_amount[$i]); $dr_amount=''; }else{ $cr_amount=''; $dr_amount=number_format($ji_amount[$i]); }
		if(($i%2)==0) $color='#DDDDDD'; else $color='#EEEEEE';
	print '<tr bgcolor="'.$color.'"><td align="right" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.($i+1).'&nbsp;&nbsp;&nbsp;&nbsp;</td><td>&nbsp;&nbsp;&nbsp;'.$ji_account[$i].'&nbsp;&nbsp;&nbsp;</td><td align="right">&nbsp;&nbsp;&nbsp;'.$dr_amount.'&nbsp;&nbsp;&nbsp;</td><td align="right">&nbsp;&nbsp;&nbsp;'.$cr_amount.'&nbsp;&nbsp;&nbsp;</td><td>&nbsp;&nbsp;&nbsp;'.$ji_description[$i].'&nbsp;&nbsp;&nbsp;</td><td>&nbsp;&nbsp;&nbsp;'.$ji_payee_type[$i].'&nbsp;&nbsp;&nbsp;</td><td>&nbsp;&nbsp;&nbsp;'.$ji_payee_name[$i].'&nbsp;&nbsp;&nbsp;</td></tr>';
	} ?>
	</table></td></tr>
	<tr bgcolor="#EEEEEE"><td colspan="7" align="center"><br /><textarea placeholder="Memo" name="memo" style="width:90%; background-color:#FAFAFA;" readonly="readonly"><?php print $memo; ?></textarea></td></tr>
	<tr bgcolor="#EEEEEE" style="font-size:10pt"><td colspan="7" align="center"><table width="100%"><tr><td><strong>Placed By: </strong><span style="color:navy"><?php print ucfirst($placed_by); ?></span></td><td></td><td align="right"><strong>Placed Date: </strong><span style="color:navy"><?php print $placed_date; ?></span></td></tr></table></td></tr>
</table>
</div>

<!-- ------------------------------------------------------------------------------------------------------------------ -->
<form action="#" method="post">
	<table align="center" border="0" cellspacing="0"  style="font-size:12pt" bgcolor="#EEEEEE">
	<tr bgcolor="#DDDDDD"><td width="30px"></td><td><strong>Journal No</strong></td><td align="center" style="color:navy"><strong><?php print str_pad($id, 7, "0", STR_PAD_LEFT); ?></strong></td><td colspan="7"></td>
	<td rowspan="2"  bgcolor="#EEEEEE">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="List Of Journals" style="width:150px; height:50px" onclick="window.location = 'index.php?components=fin&action=list_journal&year=<?php print date("Y",time()); ?>'" /></td></tr>
	<tr><td width="30px"></td><td><strong>Date </strong></td><td><input type="date" name="date" id="date" style="width:140px; background-color:#FAFAFA;" readonly="readonly" value="<?php print $journal_date; ?>" />
		</td><td width="50px"></td><td>	<strong>Store </strong></td><td><input type="text" style="width:140px; background-color:#FAFAFA;" readonly="readonly" value="<?php print ucfirst($store); ?>" /></td><td width="50px"></td><td>
		<strong>Ref No </strong></td><td><input type="text" name="ref" id="ref" style="width:140px; background-color:#FAFAFA;" readonly="readonly" value="<?php print $ref_no; ?>" /></td><td width="30px"></td></tr>
	</table>
	<br /><br />
	<table align="center" border="0" style="font-family:Calibri; font-size:10pt;">
	<tr  bgcolor="#CCCCEE" style="font-size:12pt; color:navy; font-weight:bold"><td></td><td width="180px">&nbsp;&nbsp;Account</td><td align="center">Debits</td><td align="center">Credits</td><td width="350px">&nbsp;&nbsp;Description</td><td align="center">Type</td><td align="center">Name</td></tr>
	<?php for($i=0;$i<sizeof($ji_account);$i++){
		if($ji_cr_dr[$i]=='cr'){ $cr_amount=number_format(-$ji_amount[$i]); $dr_amount=''; }else{ $cr_amount=''; $dr_amount=number_format($ji_amount[$i]); }
		if(($i%2)==0) $color='#DDDDDD'; else $color='#EEEEEE';
	print '<tr bgcolor="'.$color.'"><td align="right" class="shipmentTB4">'.($i+1).'</td><td class="shipmentTB3">'.$ji_account[$i].'</td><td align="right" class="shipmentTB3">'.$dr_amount.'</td><td align="right" class="shipmentTB3">'.$cr_amount.'</td><td class="shipmentTB3">'.$ji_description[$i].'</td><td class="shipmentTB3">'.$ji_payee_type[$i].'</td><td class="shipmentTB3">'.$ji_payee_name[$i].'</td></tr>';
	} ?>
	<tr bgcolor="#EEEEEE"><td colspan="7" align="center"><br /><textarea placeholder="Memo" name="memo" style="width:90%; background-color:#FAFAFA;" readonly="readonly"><?php print $memo; ?></textarea></td></tr>
	<tr bgcolor="#EEEEEE" style="font-size:10pt"><td colspan="7" align="center"><table width="100%"><tr><td><strong>Placed By: </strong><span style="color:navy"><?php print ucfirst($placed_by); ?></span></td><td></td><td align="right"><strong>Placed Date: </strong><span style="color:navy"><?php print $placed_date; ?></span></td></tr></table></td></tr>
</table>
<table align="center" border="0">
	<tr bgcolor="#EEEEEE"><td colspan="4" align="center">
	<table><tr><td align="center">
	<div style="background-color:#6699FF; border:medium; border-color:black; width:80px;">
	<a class="shortcut-button" onclick="printdiv('print','printheader')" href="#"><span style="text-decoration:none; font-family:Arial; color:navy;">
	<img src="images/print.png" alt="icon" /><br />
	Print
	</span></a>
	</div>
	<?php if($no_delete==0){ ?>
		</td><td>
		<input type="Button" value="Delete" onclick="deleteJournal(<?php print $_GET['id']; ?>)" style="width:60px; height:50px; background-color:#CC0000; color:white" />
	<?php } ?>
	</td></tr></table>
	</td></tr>
	</table>
</form>
	

<?php
                include_once  'template/footer.php';
?>