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
	<h2 align="center" style="color:#3333FF; text-decoration:underline">Login Audit</h2>
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
	<tr bgcolor="#CCCCCC"><th>Salesman</th><th>&nbsp;&nbsp;Date&nbsp;&nbsp;</th><th>&nbsp;&nbsp;Time&nbsp;&nbsp;</th><th>&nbsp;&nbsp;Device&nbsp;&nbsp;</th></tr>
	<?php
	for($i=0;$i<sizeof($lo_date);$i++){
		print '<tr bgcolor="#EEEEEE"><td>&nbsp;&nbsp;'.ucfirst($lo_user[$i]).'&nbsp;&nbsp;</td><td align="center">&nbsp;&nbsp;'.$lo_date[$i].'&nbsp;&nbsp;</td><td align="center">&nbsp;&nbsp;'.$lo_time[$i].'&nbsp;&nbsp;</td><td>&nbsp;&nbsp;'.$lo_device[$i].'&nbsp;&nbsp;</td></tr>';
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