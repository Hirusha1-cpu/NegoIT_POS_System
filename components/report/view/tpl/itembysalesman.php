<td width="100px"></td>
<td>
	<script>
	$(function() {
		var availableTags1 = [<?php for ($x=0;$x<sizeof($itm_description);$x++){ print '"'.$itm_description[$x].'",'; } ?>	];
		$( "#tags1" ).autocomplete({
			source: availableTags1
		});
	});
	</script>

	Item : <input type="text" id="tags1" name="item" value="<?php print $item; ?>" onclick="this.value=''" />
</td>
<td width="30px"></td>
<?php  if(!isMobile()){ ?>
<td rowspan="2"><input type="submit" value="Generate" style="height:60px" /></td>
<?php } ?>
</tr>
<tr><td>From Date: <input type="date" name="from_date" value="<?php print $from_date; ?>" /></td><td></td><td>To Date: <input type="date" name="to_date" value="<?php print $to_date; ?>" /></td><td></td></tr>
<?php  if(isMobile()){ ?>
<tr><td align="center" colspan="4"><input type="submit" value="Generate" style="height:60px" /></td></tr>
<?php } ?>
</table>
</form>
<div id="printheader" style="display:none" >
	<h1 style="color:navy"><?php print $inf_company; ?></h1>
	<h2 align="center" style="color:#3333FF; text-decoration:underline">Item Sale By Salesman</h2>
	<table style="font-size:12pt" border="1" cellspacing="0">
		<tr><td style="background-color:#C0C0C0; padding-left:10px; padding-right:10px" >Item</td><td style="background-color:#EEEEEE; padding-left:10px">&nbsp;&nbsp;<?php print $item; ?>&nbsp;&nbsp;</td></tr>
		<tr><td style="background-color:#C0C0C0; padding-left:10px; padding-right:10px" >From Date</td><td style="background-color:#EEEEEE; padding-left:10px">&nbsp;&nbsp;<?php print $from_date; ?>&nbsp;&nbsp;</td></tr>
		<tr><td style="background-color:#C0C0C0; padding-left:10px; padding-right:10px" >To Date</td><td style="background-color:#EEEEEE; padding-left:10px">&nbsp;&nbsp;<?php print $to_date; ?>&nbsp;&nbsp;</td></tr>
	</table><br />
	<hr>
</div>
<hr><br>

<div id="print">
	<table id="data_table1" align="center" style="font-size:12pt">
	<tr bgcolor="#CCCCCC"><th width="150px">Salesman</th><th>&nbsp;&nbsp;Sold Item Qty&nbsp;&nbsp;</th></tr>
	<?php
	for($i=0;$i<sizeof($salesman);$i++){
		print '<tr bgcolor="#EEEEEE"><td>&nbsp;&nbsp;'.ucfirst($salesman[$i]).'</td><td align="right">'.$soldqty[$i].'&nbsp;&nbsp;</td></tr>';
	}
	?>
	</table>
</div>

<div style="display:none">
	<table id="data_table2"></table>
	<table id="data_table3"></table>
	<table id="data_table4"></table>
	<table id="data_table5"></table>
</div>

<table><tr><td></td>