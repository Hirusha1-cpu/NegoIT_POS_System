<td width="100px"></td>
<td></td>
<td width="30px"></td>
<?php  if(!isMobile()){ ?>
<td rowspan="3"><input type="submit" value="Generate" style="height:60px" /></td>
<?php } ?>
</tr>
<tr><td>From Date: <input type="date" name="from_date" value="<?php print $from_date; ?>" /></td><td></td><td>To Date: <input type="date" name="to_date" value="<?php print $to_date; ?>" /></td><td></td></tr>
<?php  if(isMobile()){ ?>
<tr><td align="center" colspan="4"><input type="submit" value="Generate" style="height:60px" /></td></tr>
<?php } ?>
</table>
</form>
<div id="printheader" style="display:none"  >
	<h1 style="color:navy"><?php print $inf_company; ?></h1>
	<h2 align="center" style="color:#3333FF; text-decoration:underline">Credit Limit Change Audit</h2>
	<table style="font-size:12pt" border="1" cellspacing="0">
		<tr><td style="background-color:#C0C0C0; padding-left:10px; padding-right:10px" >From Date</td><td style="background-color:#EEEEEE; padding-left:10px">&nbsp;&nbsp;<?php print $from_date; ?>&nbsp;&nbsp;</td><td style="background-color:#C0C0C0; padding-left:10px; padding-right:10px" >To Date</td><td style="background-color:#EEEEEE; padding-left:10px">&nbsp;&nbsp;<?php print $to_date; ?>&nbsp;&nbsp;</td></tr>
	</table><br />
	<hr>
</div>
<hr><br>

<div id="print">
	<table id="data_table1" align="center" style="font-size:12pt">
	<tr><td colspan="8" bgcolor="#467898" style="color:white" align="center">Credit Limit Audit</td></tr>
	<tr bgcolor="#CCCCCC"><th width="200px">Date Time</th><th width="300px">Customer</th><th>Old Limit</th><th>New Limit</th><th>Changed By</th></tr>
	<?php
	for($i=0;$i<sizeof($cu_id);$i++){
		print '<tr bgcolor="#EEEEEE"><td align="center">'.substr($changed_date[$i],0,16).'</td><td>&nbsp;&nbsp;<a href="index.php?components=manager&action=editcust&id='.$cu_id[$i].'">'.$cu_name[$i].'</a></td><td align="right">&nbsp;&nbsp;'.number_format($cla_old_limit[$i]).'&nbsp;&nbsp;</td><td align="right">&nbsp;&nbsp;'.number_format($cla_new_limit[$i]).'&nbsp;&nbsp;</td><td>&nbsp;&nbsp;'.ucfirst($changed_by[$i]).'&nbsp;&nbsp;</td></tr>';
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