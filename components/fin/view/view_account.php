<?php
    include_once  'template/header.php';
?>
<!-- Notifications -->
<table align="center" style="font-size:12pt">
	<tr>
		<td>
			<?php
				if(isset($_REQUEST['message'])){
					if($_REQUEST['re']=='success') $color='green'; else $color='red';
				print '<span style="color:'.$color.'; font-weight:bold;">'.$_REQUEST['message'].'</span>';
				}
			?>
		</td>
	</tr>
</table>
<!--// Notifications -->

<form action="index.php" method="get">
	<input type="hidden" name="components" value="<?php print $components; ?>" />
	<input type="hidden" name="action" value="acount_history" />
	<input type="hidden" name="id" value="<?php print $_GET['id']; ?>" />
	<table align="center" border="0"  style="font-size:12pt; font-family:Calibri;" width="800px" bgcolor="#EEEEEE">
		<tr>
			<td align="center" colspan="6">
				<span style="font-weight:bold; color:navy">Account History :</span><span style="color:navy"> <?php print $account_name; ?> </span>
			</td>
		</tr>
		<tr><td align="center" colspan="6" height="5px"></td></tr>
		<tr>
			<td width="50px"></td>
			<td align="center">From Date <input type="date" name="from_date" value="<?php print $from_date; ?>" /></td>
			<td width="50px"></td>
			<td align="center">To Date <input type="date" name="to_date" value="<?php print $to_date; ?>" /></td>
			<td width="50px"></td>
			<td align="center">
				<label for="">Group</label>
				<select id="group0" name="group">
					<option value="all">--ALL--</option>
					<?php
					$gpname='ALL Groups';
					$select='';
					for($i=0;$i<sizeof($gp_id);$i++){
						if(isset($_GET['group'])){
							if($gp_id[$i]==$_GET['group']){
								$select='selected="selected"'; $gpname=ucfirst($gp_name[$i]);
							}else{
								$select='';
							}
						}
						print '<option value="'.$gp_id[$i].'" '.$select.'>'.ucfirst($gp_name[$i]).'</option>';
					}
					?>
				</select>
			</td>
			<td width="50px"></td>
			<td align="center">
				<label for="">Sub System</label>
				<select id="sub_system" name="sub_system">
					<option value="all">--ALL--</option>
					<?php for($i=0;$i<sizeof($sb_id);$i++){
						if(isset($_GET['sub_system'])){
							if($sb_id[$i] == $_GET['sub_system']) $select='selected="selected"'; else $select='';
						}else{
							$select='';
						}
						print '<option value="'.$sb_id[$i].'" '.$select.'>'.$sb_name[$i].'</option>';
					} ?>
				</select>
			</td>
			<td width="50px"></td>
			<td><input type="submit" value="Submit" style="widows:50px; height:50px" /></td>
			<td width="50px"></td>
		</tr>
		<tr>
			<td align="center" colspan="6" height="5px"></td>
		</tr>
	</table>
</form>

<div id="printheader" style="display:none;" >
	<h1 style="color:navy; font-family:Calibri"><?php print $inf_company; ?></h1>
	<h2 align="center" style="color:#3333FF; text-decoration:underline; font-family:Calibri">Account History</h2>
	<table style="font-family:Calibri" width="95%">
		<tr>
			<td width="150px"><strong>Account Name</strong></td>
			<td><?php print $account_name; ?></td>
			<td></td>
			<td width="100px"><strong>From Date</strong></td>
			<td width="100px"><?php print $from_date; ?></td>
		</tr>
		<tr>
			<td width="150px"><strong>Generated Date</strong></td>
			<td><?php print dateNow(); ?></td>
			<td></td><td width="100px"><strong>To Date</strong></td>
			<td width="100px"><?php print $to_date; ?></td>
		</tr>
	</table>
	<hr/>
	<br/>
</div>

<br />

<div id="print">
	<table align="center" style="font-family:Calibri">
		<tr bgcolor="#8898A1" style="color:white">
			<td align="center">Date</td>
			<td align="center">Type</td>
			<td class="shipmentTB3">Payee Account</td>
			<td width="100px" class="shipmentTB3" align="right">Debit</td>
			<td width="100px" class="shipmentTB3" align="right">Credit</td>
			<td width="100px" class="shipmentTB3" align="right">Balance</td>
		</tr>
<?php
	print '<tr bgcolor="#EEEEEE">
				<td></td>
				<td class="shipmentTB3" colspan="4">Balance As of '.date("Y-m-d",(strtotime($from_date)-24*60*60)).'</td>
				<td class="shipmentTB3" align="right">'.number_format($statting_balance,2).'</td>
			</tr>';
			$balance=$statting_balance;
		for($i=0;$i<sizeof($date);$i++){
			$action='';
			$component='fin';
			$balance+=$dr[$i]-$cr[$i];
			if($type[$i]=='Journal'){ $action="one_journal&id=$id_list[$i]"; $component='fin'; }
			if($type[$i]=='Expense'){ $action="one_expense&id=$id_list[$i]"; $component='fin'; }
			if($type[$i]=='Cust Payment (Cash)'){ $action="finish_payment&id=$id_list[$i]"; $component='billing'; }
			if($type[$i]=='Cust Payment (Card)'){ $action="finish_payment&id=$id_list[$i]"; $component='billing'; }
			if($type[$i]=='Cust Payment (Bank)'){ $action="finish_payment&id=$id_list[$i]"; $component='billing'; }
			if($type[$i]=='Cust Payment (Cheque)'){ $action="finish_payment&id=$id_list[$i]"; $component='billing'; }
			if($type[$i]=='Account Receivable'){ $action="acount_history&id=35"; $component='fin'; }
			if($type[$i]=='Unearned Revenue'){ $action="acount_history&id=36"; $component='fin'; }
			if($type[$i]=='Shipment'){ $action="one_shipment&shipment_no=$id_list[$i]"; $component='inventory'; }
			if($type[$i]=='Bill'){ $action="finish_bill&id=$id_list[$i]"; $component='billing'; }
			if($type[$i]=='Payroll Expenses'){ $action="payroll_one&id=$id_list[$i]"; $component='fin'; }
			if($type[$i]=='Payroll Payble'){ $action="payroll_one&id=$id_list[$i]"; $component='fin'; }
			if($type[$i]=='ETF Expense (Employer)'){ $action="payroll_one&id=$id_list[$i]"; $component='fin'; }
			if($type[$i]=='EPF Expense (Employer)'){ $action="payroll_one&id=$id_list[$i]"; $component='fin'; }
			if($type[$i]=='ETF Payble'){ $action="payroll_one&id=$id_list[$i]"; $component='fin'; }
			if($type[$i]=='EPF Payble'){ $action="payroll_one&id=$id_list[$i]"; $component='fin'; }
			if($type[$i]=='Payroll Tax Payble'){ $action="payroll_one&id=$id_list[$i]"; $component='fin'; }
			if($type[$i]=='Payroll Withholdings'){ $action="payroll_one&id=$id_list[$i]"; $component='fin'; }
			if($type[$i]=='Payroll Settlement'){ $action="loan_one&id=$id_list[$i]"; $component='fin'; }
			if($type[$i]=='Direct Settlement'){ $action="loan_one&id=$id_list[$i]"; $component='fin'; }
			if($type[$i]=='EMP Loan'){ $action="loan_one&id=$id_list[$i]"; $component='fin'; }
			if($type[$i]=='EMP Loan'){ $action="loan_one&id=$id_list[$i]"; $component='fin'; }
			if($type[$i]=='Inventory Asset'){ $action="$id_list[$i]"; $component='inventory'; }
			if(($i%2)==0) $color='#FAFAFA'; else $color='#EEEEEE';
			print '<tr bgcolor="'.$color.'">
					<td class="shipmentTB3">'.$date[$i].'</td>';
					if($components == 'fin')
						print '<td class="shipmentTB3"><a href="index.php?components='.$component.'&action='.$action.'">'.$type[$i].'</a></td>';
					else
						print '<td class="shipmentTB3"><a href="#">'.$type[$i].'</a></td>';
					print '
					<td class="shipmentTB3">'.$payee[$i].'</td>
					<td class="shipmentTB3" align="right">'.number_format($dr[$i],2).'</td>
					<td class="shipmentTB3" align="right">'.number_format($cr[$i],2).'</td>
					<td class="shipmentTB3" align="right">'.number_format($balance,2).'</td>
				</tr>';
		} ?>
<tr>
	<td colspan="2" ><br /></td>
</tr>
</table>
</div>
<br />
<br />
	<table align="center"><tr><td align="center">
	<div style="background-color:#6699FF; border:medium; border-color:black; width:80px;">
	<a class="shortcut-button" onclick="printdiv('print','printheader')" href="#"><span style="text-decoration:none; font-family:Arial; color:navy;">
	<img src="images/print.png" alt="icon" /><br />
	Print
	</span></a>
	</div>
	</td></tr></table>

<?php
   	include_once  'template/footer.php';
?>