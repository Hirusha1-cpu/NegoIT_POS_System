<td width="100px"></td>
<td></td>
<td width="30px"></td>
<?php  if(!isMobile()){ ?>
<td rowspan="3"><input type="submit" value="Generate" style="height:60px" /></td>
<?php } ?>
</tr>
<tr><td>From Store: 
	<select name="from_store">
	<option value="all" >-ALL-</option>
	<?php for($i=0;$i<sizeof($st_id);$i++){
		if($from_store==$st_id[$i]) $select='selected="selected"'; else $select='';
		print '<option value="'.$st_id[$i].'" '.$select.'>'.$st_name[$i].'</option>';
	} ?>
	</select>
</td><td></td><td>To Store: 
	<select name="to_store">
	<option value="all">-ALL-</option>
	<?php for($i=0;$i<sizeof($st_id);$i++){
		if($to_store==$st_id[$i]) $select='selected="selected"'; else $select='';
		print '<option value="'.$st_id[$i].'" '.$select.'>'.$st_name[$i].'</option>';
	} ?>
	</select>
</td><td></td></tr>
<tr><td></td><td>From Date: <input type="date" name="from_date" value="<?php print $from_date; ?>" /></td><td></td><td>To Date: <input type="date" name="to_date" value="<?php print $to_date; ?>" /></td><td></td></tr>
<?php  if(isMobile()){ ?>
<tr><td align="center" colspan="4"><input type="submit" value="Generate" style="height:60px" /></td></tr>
<?php } ?>
</table>
</form>
<div id="printheader" style="display:none"  >
	<h1 style="color:navy"><?php print $inf_company; ?></h1>
	<h2 align="center" style="color:#3333FF; text-decoration:underline">Item Sale By Salesman</h2>
	<table style="font-size:12pt" border="1" cellspacing="0">
		<tr><td style="background-color:#C0C0C0; padding-left:10px; padding-right:10px" >From Store</td><td style="background-color:#EEEEEE; padding-left:10px">&nbsp;&nbsp;<?php print ucfirst($from_store); ?>&nbsp;&nbsp;</td><td style="background-color:#C0C0C0; padding-left:10px; padding-right:10px" >To Store</td><td style="background-color:#EEEEEE; padding-left:10px">&nbsp;&nbsp;<?php print ucfirst($to_store); ?>&nbsp;&nbsp;</td></tr>
		<tr><td style="background-color:#C0C0C0; padding-left:10px; padding-right:10px" >From Date</td><td style="background-color:#EEEEEE; padding-left:10px">&nbsp;&nbsp;<?php print $from_date; ?>&nbsp;&nbsp;</td><td style="background-color:#C0C0C0; padding-left:10px; padding-right:10px" >To Date</td><td style="background-color:#EEEEEE; padding-left:10px">&nbsp;&nbsp;<?php print $to_date; ?>&nbsp;&nbsp;</td></tr>
	</table><br />
	<hr>
</div>
<hr>
<br>


<div id="print">
	<table id="data_table1" align="center" style="font-size:12pt" width="1000px">
	<tr><td colspan="8" bgcolor="#467898" style="color:white" align="center">Transfer Audit</td></tr>
	<tr bgcolor="#CCCCCC"><th width="200px">Date Time</th><th width="100px">Transfer No</th><th>From Store</th><th>To Store</th><th>From User</th><th>Remote User</th><th>Transfer Cost</th><th>Status</th></tr>
	<?php
	for($i=0;$i<sizeof($tr_no);$i++){
		print '<tr bgcolor="#EEEEEE"><td align="center">'.$tr_date[$i].'</td><td align="center"><a href="index.php?components=trans&action=print_gtn&id='.$tr_no[$i].'&approve_permission=0">'.str_pad($tr_no[$i], 7, "0", STR_PAD_LEFT).'</a></td><td>&nbsp;&nbsp;'.ucfirst($tr_from_store[$i]).'&nbsp;&nbsp;</td><td>&nbsp;&nbsp;'.ucfirst($tr_to_store[$i]).'&nbsp;&nbsp;</td><td>&nbsp;&nbsp;'.ucfirst($tr_from_user[$i]).'&nbsp;&nbsp;</td><td>&nbsp;&nbsp;'.ucfirst($tr_remote_user[$i]).'&nbsp;&nbsp;</td><td align="right">&nbsp;&nbsp;'.number_format($tr_total[$i]).'&nbsp;&nbsp;</td><td>&nbsp;&nbsp;'.$tr_status[$i].'&nbsp;&nbsp;</td></tr>';
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