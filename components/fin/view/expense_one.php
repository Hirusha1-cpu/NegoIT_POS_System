<?php
    include_once  'template/header.php';
?>

<link rel="stylesheet" href="plugin/jquery/css/jquery.ui.all.css" />
<link rel="stylesheet" href="plugin/jquery/css/demos.css" />
<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
<script src="plugin/jquery/ui/jquery.ui.core.js"></script>
<script src="plugin/jquery/ui/jquery.ui.widget.js"></script>
<script src="plugin/jquery/ui/jquery.ui.position.js"></script>
<script src="plugin/jquery/ui/jquery.ui.autocomplete.js"></script>

<!-- Scripts -->
<script type="text/javascript">
	function deleteExpense(id){
		var check= confirm("Do you want Delete this Expense?");
	 	if (check== true)
		window.location = 'index.php?components=<?php print $components; ?>&action=delete_expense&id='+id;
	}
</script>
<!--// Scripts -->

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

<div id="printheader" style="display:none;" >
	<h1 style="color:navy; font-family:Calibri"><?php print $inf_company; ?></h1>
	<h2 align="center" style="color:#3333FF; text-decoration:underline; font-family:Calibri">Expense Voucher</h2>
	<hr />
	<table align="center" width="95%" style="font-family:Calibri">
		<tr>
			<td width="60%">
				<table>
				<tr><td width="130px"><strong>Payee Type</strong></td><td><?php print ucfirst($payee_type); ?></td></tr>
				<tr><td><strong>Payee</strong></td><td><?php print ucfirst($payee_name); ?></td></tr>
				<tr><td><strong>From Account</strong></td><td><?php print $from_account; ?></td></tr>
				<tr><td><strong>Method</strong></td><td><?php print $payment_method; ?></td></tr>
				</table>
			</td>
			<td valign="top" width="40%">
				<table>
				<tr><td width="100px"><strong>Expense No</strong></td><td><span style="color:navy; font-weight:bold"><?php print str_pad($_GET['id'], 7, "0", STR_PAD_LEFT); ?></span></td></tr>
				<tr><td><strong>Store</strong></td><td><?php print $store; ?></td></tr>
				<tr><td><strong>Date</strong></td><td><?php print $expense_date; ?></td></tr>
				<tr><td><strong>Ref No</strong></td><td><?php print $ref_no; ?></td></tr>
				</table>
			</td>
		</tr>
	</table>
	<hr/>
	<br/>
</div>

<form action="#" method="post">
	<table align="center" border="0"  style="font-size:12pt" bgcolor="#EEEEEE">
		<tr bgcolor="#DDDDDD"><td colspan="6"><strong>Expense No&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:navy"><?php print str_pad($_GET['id'], 7, "0", STR_PAD_LEFT); ?></span></strong></td></tr>
		<tr><td><table>
			<tr><td><strong>Date  </strong></td><td><input type="date" name="date" id="date" style="width:140px; background-color:#FAFAFA;" readonly="readonly" value="<?php print $expense_date; ?>" /></td></tr>
			<tr><td><strong>Store  </strong></td><td><input type="text" style="width:140px; background-color:#FAFAFA;" value="<?php print $store; ?>" readonly="readonly" /></td></tr>
			<tr><td><strong>Ref No </strong></td><td><input type="text" name="ref" id="ref" style="width:140px; background-color:#FAFAFA;" value="<?php print $ref_no; ?>" readonly="readonly" /></td></tr>
		</table></td><td width="40px"></td><td><table>
			<tr><td><strong>Payee Type  </strong></td><td><input type="text" style="width:140px; background-color:#FAFAFA;" value="<?php print ucfirst($payee_type); ?>" readonly="readonly" /></td></tr>
			<tr><td><strong>Payee  </strong></td><td><input type="text" style="width:140px; background-color:#FAFAFA;" value="<?php print ucfirst($payee_name); ?>" readonly="readonly" /></td></tr>
			<tr><td><br /></td></tr>
		</table></td><td width="40px"></td><td><table>
			<tr><td><strong>From Account </strong></td><td><input type="text" style="width:140px; background-color:#FAFAFA;" value="<?php print $from_account; ?>" readonly="readonly" /></td></tr>
			<tr><td><strong><a title="Payment Method">Method  <a/></strong></td><td><input type="text" style="width:140px; background-color:#FAFAFA;" value="<?php print $payment_method; ?>" readonly="readonly" /></td></tr>
			<tr><td><br /></td></tr>
		</table></td><td>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="List Of Expenses" style="width:150px; height:50px" onclick="window.location = 'index.php?components=<?php print $components; ?>&action=list_expense&year=<?php print date("Y",time()); ?>'" />
		</td></tr>
	</table>

	<br/><br/>

	<div id="print">
		<table align="center" border="0" style="font-family:Calibri; font-size:10pt;">
			<tr  bgcolor="#CCCCEE" style="font-size:12pt; color:navy; font-weight:bold">
				<td></td>
				<td width="180px">&nbsp;&nbsp;Account</td>
				<td width="350px">&nbsp;&nbsp;Description</td>
				<td align="center" width="100px">Amount</td>
			</tr>
			<?php for($i=0;$i<sizeof($ei_account);$i++){
				if(($i%2)==0) $color='#DDDDDD'; else $color='#EEEEEE';
				print '<tr bgcolor="'.$color.'">
						<td align="right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.($i+1).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
						<td>&nbsp;&nbsp;&nbsp;'.$ei_account[$i].'&nbsp;&nbsp;&nbsp;</td>
						<td>&nbsp;&nbsp;&nbsp;'.$ei_description[$i].'&nbsp;&nbsp;&nbsp;</td>
						<td align="right">&nbsp;&nbsp;&nbsp;'.number_format($ei_amount[$i]).'&nbsp;&nbsp;&nbsp;</td>
					</tr>';
			} ?>
			<tr bgcolor="#EEEEEE">
				<td colspan="4" align="center"><br /><textarea placeholder="Memo" name="memo" style="width:90%; background-color:#FAFAFA;" readonly="readonly"><?php print $memo; ?></textarea></td>
			</tr>
			<tr bgcolor="#EEEEEE" style="font-size:10pt">
				<td colspan="4" align="center">
					<table width="100%">
						<tr>
							<td><strong>Placed By: </strong><span style="color:navy"><?php print ucfirst($placed_by); ?></span></td>
							<td></td>
							<td align="right"><strong>Placed Date: </strong><span style="color:navy"><?php print $placed_date; ?></span></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</div>

	<table align="center" border="0">
		<tr bgcolor="#EEEEEE">
			<td colspan="4" align="center">
				<table>
					<tr>
						<td align="center">
							<div style="background-color:#6699FF; border:medium; border-color:black; width:80px;">
									<a class="shortcut-button" onclick="printdiv('print','printheader')" href="#">
										<span style="text-decoration:none; font-family:Arial; color:navy;">
											<img src="images/print.png" alt="icon" /><br />Print
										</span>
									</a>
							</div>
						</td>
						<td>
							<input type="Button" value="Delete" onclick="deleteExpense(<?php print $_GET['id']; ?>)" style="width:60px; height:50px; background-color:#CC0000; color:white" />
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>

</form>
	

<?php
    include_once  'template/footer.php';
?>