<td width="100px"></td>
<td>

	User : 
	<select id="user" name="user">
		<option value="" >-ALL-</option>
		<?php 
		$user_hname='All';
		for($i=0;$i<sizeof($user_id);$i++){
			if($user_id[$i]==$user){ $select='selected="selected"'; $user_hname=$user_name[$i]; }else $select="";
			print '<option value="'.$user_id[$i].'" '.$select.'>'.$user_name[$i].'</option>';
		} ?>
	</select>
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
	<h2 align="center" style="color:#3333FF; text-decoration:underline">Bill Edit Audit</h2>
	<table style="font-size:12pt" border="1" cellspacing="0">
		<tr><td style="background-color:#C0C0C0; padding-left:10px; padding-right:10px" >User</td><td style="background-color:#EEEEEE; padding-left:10px">&nbsp;&nbsp;<?php print $user_hname; ?>&nbsp;&nbsp;</td></tr>
		<tr><td style="background-color:#C0C0C0; padding-left:10px; padding-right:10px" >From Date</td><td style="background-color:#EEEEEE; padding-left:10px">&nbsp;&nbsp;<?php print $from_date; ?>&nbsp;&nbsp;</td></tr>
		<tr><td style="background-color:#C0C0C0; padding-left:10px; padding-right:10px" >To Date</td><td style="background-color:#EEEEEE; padding-left:10px">&nbsp;&nbsp;<?php print $to_date; ?>&nbsp;&nbsp;</td></tr>
	</table><br />
	<hr>
</div>

<hr><br>

<div id="print">
	<table id="data_table1" align="center" style="font-size:12pt">
	<tr bgcolor="#CCCCCC"><th>Changed By</th><th>&nbsp;&nbsp;Action Date&nbsp;&nbsp;</th><th>Invoice No</th><th>&nbsp;&nbsp;Old Date&nbsp;&nbsp;</th><th>&nbsp;&nbsp;New Date&nbsp;&nbsp;</th><th>&nbsp;&nbsp;Old Salesman&nbsp;&nbsp;</th><th>&nbsp;&nbsp;New Salesman&nbsp;&nbsp;</th><th>&nbsp;&nbsp;Old<br />Recovery Agent&nbsp;&nbsp;</th><th>&nbsp;&nbsp;New<br />Recovery Agent&nbsp;&nbsp;</th></tr>
	<?php
	for($i=0;$i<sizeof($ie_user);$i++){
		print '<tr bgcolor="#EEEEEE"><td>&nbsp;&nbsp;'.ucfirst($ie_user[$i]).'&nbsp;&nbsp;</td><td>&nbsp;&nbsp;'.$ie_act_date[$i].'&nbsp;&nbsp;</td><td align="center"><a href="index.php?components=billing&action=finish_bill&id='.$ie_invoice[$i].'">'.str_pad($ie_invoice[$i], 7, "0", STR_PAD_LEFT).'</a></td><td align="center">&nbsp;&nbsp;'.$ie_ori_date[$i].'&nbsp;&nbsp;</td><td align="center">&nbsp;&nbsp;'.$ie_new_date[$i].'&nbsp;&nbsp;</td><td>&nbsp;&nbsp;'.ucfirst($ie_ori_sm[$i]).'&nbsp;&nbsp;</td><td>&nbsp;&nbsp;'.ucfirst($ie_new_sm[$i]).'&nbsp;&nbsp;</td><td>&nbsp;&nbsp;'.ucfirst($ie_ori_rg[$i]).'&nbsp;&nbsp;</td><td>&nbsp;&nbsp;'.ucfirst($ie_new_rg[$i]).'&nbsp;&nbsp;</td></tr>';
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