<?php
                include_once  'template/header.php';
?>
	<link rel="stylesheet" href="plugin/jquery/css/demos.css" />
<!-- ------------------Item List----------------------- -->
	<table align="center" height="100%" cellspacing="0" style="font-size:10pt" bgcolor="#EEEEEE">
	<tr><td colspan="8" height="10px"></td></tr>
	<tr><td width="50px"></td><td colspan="7" >
		<?php if($selection=='store'){?>
		<table><tr><td><strong>Store </strong>: </td><td align="right"><?php print $store1; ?> </td></tr></table>
		<?php }else{ ?>
		<table><tr><td><strong>Customer </strong>: </td><td align="right"><?php print $_GET['customer']; ?> </td></tr></table>
		<?php } ?>
	</td></tr>
	<tr><td width="50px"></td><td width="70px" align="right"><strong>Category : </strong></td><td>
	<select name="category" id="category" disabled="disabled" >
		<option value="all" >-ALL-</option>
		<?php for($i=0;$i<sizeof($cat_id);$i++){
			if($category==$cat_id[$i]) $select='selected="selected"'; else $select='';
			print '<option value="'.$cat_id[$i].'" '.$select.'>'.$cat_name[$i].'</option>';
		} ?>
	</select>
	</td>
	<td width="100px" align="right"><strong>From Date : </strong></td><td>
	<input type="date" id="datefrom" name="datefrom" style="width:130px" disabled="disabled" value="<?php print $fromdate; ?>" />
	</td><td width="100px" align="right"><strong>To Date : </strong></td><td>
	<input type="date" id="dateto" name="dateto" style="width:130px" disabled="disabled" value="<?php print $todate; ?>" />
	</td><td width="50px"></td></tr>
	<tr><td colspan="8" height="10px"></td></tr>
	</table><br />

<div id="printheader" style="display:none" >
	<h1 style="color:navy"><?php print $inf_company; ?></h1>
	<h2 align="center" style="color:#3333FF; text-decoration:underline">Item Sales Report</h2>
	<table style="font-size:12pt" border="1" cellspacing="0">
		<tr><td width="100px" style="background-color:#C0C0C0; padding-left:10px">Customer</td><td width="250px" style="background-color:#EEEEEE; padding-left:10px"><?php print ucfirst($customer); ?></td></tr>
		<tr><td style="background-color:#C0C0C0; padding-left:10px">From</td><td style="background-color:#EEEEEE; padding-left:10px"><?php print $fromdate; ?></td></tr>
		<tr><td style="background-color:#C0C0C0; padding-left:10px">To</td><td style="background-color:#EEEEEE; padding-left:10px"><?php print $todate; ?></td></tr>
	</table><br />
</div>
<div id="print">
	<table align="center" height="100%" style="font-size:10pt">
	<tr><td colspan="11" style="border:0; background-color:black; color:white; font-weight:bold;"></td></tr>
	<tr bgcolor="#CCCCCC"><th width="200px">Item Description</th><th width="100px">Status of Selling<br />to the Customer</th></tr>
	<?php
		if(($customer_id!='')||($store1!='')){
		for($i=0;$i<sizeof($itm2_id);$i++){
			if($itm2_sold[$i]){ $img='<img src="images/action_check.gif" />'; $color1='green'; $color2='white'; }else{ $img=''; $color1='#EEEEEE'; $color2=''; }
			print '<tr bgcolor="'.$color1.'"><td style="padding-left:30px; color:'.$color2.';">'.$itm2_name[$i].'</td><td align="center">'.$img.'</td></tr>';
		}
	}
	?>
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