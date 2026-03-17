<div id="printheader" style="display:none" >
	<h1 style="color:navy"><?php print $inf_company; ?></h1>
	<h2 align="center" style="color:#3333FF; text-decoration:underline; font-family:Calibri">Drawer Search</h2>
	<table style="font-size:12pt" border="1" cellspacing="0">
		<tr><td width="100px" style="background-color:#C0C0C0; padding-left:10px">Store</td><td width="250px" style="background-color:#EEEEEE; padding-left:10px"><?php print $st_name; ?></td></tr>
		<tr><td style="background-color:#C0C0C0; padding-left:10px">Report Date</td><td style="background-color:#EEEEEE; padding-left:10px"><?php print $report_date; ?></td></tr>
	</table>
	<hr />
</div>

<div id="print">
	<table align="center" style="font-family:Calibri; font-size:12pt">
	<tr style="background-color:#467898;color :white; -webkit-print-color-adjust: exact;"><th width="100px">Drawer No</th><th width="200px">Item</th><th width="70px">Qty</th></tr>
<?php
	for($i=0;$i<sizeof($dr_no);$i++){
		if(($i%2)==0) $color='#FAFAFA'; else $color='#DEDEDE';
		print '<tr bgcolor="'.$color.'" style="-webkit-print-color-adjust: exact;"><td>&nbsp;&nbsp;'.$dr_no[$i].'&nbsp;&nbsp;</td><td>&nbsp;&nbsp;<a style="cursor:pointer; color:blue;" title="Category : '.$dr_category[$i].'">'.$dr_item[$i].'</a>&nbsp;&nbsp;</td><td align="right">&nbsp;&nbsp;'.number_format($dr_qty[$i]).'&nbsp;&nbsp;</td></tr>';
	}
?>	
	</table>
</div>


