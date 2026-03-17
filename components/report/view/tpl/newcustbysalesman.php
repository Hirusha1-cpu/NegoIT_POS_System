<td width="100px"></td>
<td>Store : 
				<select id="store0" name="store" onchange="window.location = 'index.php?components=report&action=sub&report_type=newcust_salesman&store='+document.getElementById('store0').value+'&from_date=<?php print $from_date; ?>&to_date=<?php print $to_date; ?>'">
				<option value="all" >--ALL--</option>
				<?php
				$stname='ALL Stores';
				 for($i=0;$i<sizeof($st_id);$i++){
				 	if($st_id[$i]==$store){ $select='selected="selected"'; $stname=ucfirst($st_name[$i]); }else{ $select=''; }
				 	print '<option value="'.$st_id[$i].'" '.$select.'>'.ucfirst($st_name[$i]).'</option>';
				 }
				?>
				</select>
</td>
<td width="30px"></td>
<?php  if(!isMobile()){ ?>
<td rowspan="2"><input type="submit" value="Generate" style="height:60px" /></td>
<?php } ?>
</tr>
<tr><td>From Date: <input type="date" name="from_date" id="from_date" value="<?php print $from_date; ?>" /></td><td></td><td>To Date: <input type="date" name="to_date" id="to_date" value="<?php print $to_date; ?>" /></td><td></td></tr>
<?php  if(isMobile()){ ?>
<tr><td align="center" colspan="4"><input type="submit" value="Generate" style="height:60px" /></td></tr>
<?php } ?>
</table>
</form>
<div id="printheader" style="display:none" >
	<h1 style="color:navy"><?php print $inf_company; ?></h1>
	<h2 align="center" style="color:#3333FF; text-decoration:underline; font-family:Calibri">Newly Created Customers by Salesman</h2>
	<table style="font-size:12pt" border="1" cellspacing="0">
		<tr><td style="background-color:#C0C0C0; padding-left:10px">Date</td><td style="background-color:#EEEEEE; padding-left:10px"><?php print 'From : '.$from_date.'&nbsp;&nbsp;&nbsp; To : '.$to_date; ?></td></tr>
		<tr><td width="100px" style="background-color:#C0C0C0; padding-left:10px">Store</td><td width="250px" style="background-color:#EEEEEE; padding-left:10px"><?php print $stname; ?></td></tr>
	</table>
	<hr />
</div>
<hr />
<div id="print" >
<table id="data_table1" align="center" style="font-family:Calibri; font-size:11pt" >
 <tr style="background-color:#467898;color :white;"><th>Salesman</th><th>Newly Created<br>Customers</th><th>Newly Created<br>& Order Placed<br>Customers</th></tr>
 <?php
 	for($i=0;$i<sizeof($salesman_id);$i++){
 		if(($i%2)==0) $color='#FAFAFA'; else $color='#EEEEEE';
 		print '<tr style="background-color:'.$color.'"><td>&nbsp;&nbsp;&nbsp;'.ucfirst($up_salesman[$i]).'&nbsp;&nbsp;&nbsp;</td><td align="right">'.$new_cust_count[$i].'&nbsp;&nbsp;&nbsp;</td><td align="right">'.$new_activecust_count[$i].'&nbsp;&nbsp;&nbsp;</td></tr>';
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