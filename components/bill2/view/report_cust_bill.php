<?php
                include_once  'template/header.php';
?>
<!-- ------------------Item List----------------------- -->
<?php
if(isset($_REQUEST['message'])){
	if($_REQUEST['re']=='success') $color0='green'; else $color0='red';
print '<table align="center" cellspacing="0" style="font-size:11pt"><tr><td><span style="color:'.$color0.'; font-weight:bold;">'.$_REQUEST['message'].'</span></td></tr></table>'; 
}
?>
<table align="center" style="font-family:Calibri; color:#2B68CE; font-size:14pt"><tr><th>Customer Bill Search</th></tr></table>

<table align="center" style="font-family:Calibri" border="1" cellspacing="0" bordercolor="silver" height="400px">
<tr><td width="300px" valign="top">
	<form action="index.php" method="get" id="search_form" >
	<input type="hidden" name="components" value="<?php print $components; ?>" />
	<input type="hidden" name="action" value="cust_bill" />
	<input type="hidden" name="cu_id" value="" />
	<table border="0" width="100%">
	<tr><td><table><tr><td align="right"><input type="text" name="search_name" id="search_name" value="<?php print $search_name; ?>" placeholder="Customer Name"  /><a onclick="document.getElementById('search_form').submit();" style="cursor:pointer"><img src="images/search.png" style="width:30px; vertical-align:middle" /></a></td></tr></table></td></tr>
	<tr><td>
		<table align="center" width="100%">
		<?php for($i=0;$i<sizeof($cu_id);$i++){
			print '<tr><td><a style="text-decoration:none" href="index.php?components='.$components.'&action=cust_bill&search_name='.$cu_name[$i].'&cu_id='.$cu_id[$i].'">'.$cu_name[$i].'</a></td></tr>';
		} ?>
		</table>
	</td></tr>
	</table>
	</form>
</td><td width="3px" bgcolor="silver"></td><td width="600px" valign="top">
	<table border="0" width="100%" cellspacing="0">
	<tr><td>
		<table><tr>
			<td><strong>From Date </strong>: </td><td><input type="date" id="datefrom" name="datefrom" style="width:130px" value="<?php print $fromdate; ?>" /></td >
			<td><strong>To Date </strong>: </td><td><input type="date" id="dateto" name="dateto" style="width:130px" value="<?php print $todate; ?>" /></td>
			<td></td><td><input type="button" value="Set Duration" onclick="window.location = 'index.php?components=<?php print  $components; ?>&action=cust_bill&search_name=<?php print $_GET['search_name'] ?>&cu_id=<?php print $_GET['cu_id'] ?>&datefrom='+document.getElementById('datefrom').value+'&dateto='+document.getElementById('dateto').value" /></td>
		</tr></table>
	</td></tr>
	<tr><td>
		<table align="center" width="100%">
		<tr><th>Invoice No</th><th align="right" class="shipmentTB4">Amount</th><th>Status</th><th>Store</th></tr>
		<?php for($i=0;$i<sizeof($bm_no);$i++){
			if(($i%2)==0) $color='#CCCCCC'; else $color='#DDDDDD';
			print '<tr bgcolor="'.$color.'"><td align="center"><a style="text-decoration:none" href="index.php?components=bill2&action=finish_bill&id='.$bm_no[$i].'">'.str_pad($bm_no[$i], 7, "0", STR_PAD_LEFT).'</a></td><td align="right" class="shipmentTB4">'.$bm_total[$i].'</td><td align="center" style="color:'.$status_color[$i].'">'.$status_out[$i].'</td><td align="center">'.$bm_store[$i].'</td></tr>';
		} ?>
		</table>
	</td></tr>
	</table>
</td></tr>
</table>

<?php

                include_once  'template/footer.php';
?>