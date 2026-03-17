<?php
	include_once  'template/header.php';
	$menu_components=$_GET['components'];
	if(isset($_GET['sm'])){ $sm=$_GET['sm']; }else $sm='-all-';
	$decimal = getDecimalPlaces(1);
?>
<link rel="stylesheet" href="plugin/jquery/css/jquery.ui.all.css" />
<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
<script src="plugin/jquery/ui/jquery.ui.core.js"></script>
<script src="plugin/jquery/ui/jquery.ui.widget.js"></script>
<script src="plugin/jquery/ui/jquery.ui.position.js"></script>
<script src="plugin/jquery/ui/jquery.ui.autocomplete.js"></script>
<link rel="stylesheet" href="plugin/jquery/css/demos.css" />
<script type="text/javascript">
	$(function() {
		var availableTags1 = [<?php for ($x=0;$x<sizeof($py_chqnofull);$x++){ print '"'.$py_chqnofull[$x].'",'; } ?>	];
		$( "#tags1" ).autocomplete({
			source: availableTags1
		});
	});

	function pendingReturnChq(id){
		var check= confirm("Do you want Mark this Chque as Pending?");
	 if (check== true)
		window.location = 'index.php?components=<?php print $menu_components; ?>&action=rtnchque_pending&id='+id;
	}

	function deleteReturnChq(id){
		var check= confirm("Do you want to Delete this Return Chque?");
	 if (check== true)
		window.location = 'index.php?components=<?php print $menu_components; ?>&action=rtnchque_delete&id='+id;
	}

	function filterSalesman(){
		var sm=document.getElementById('sm').value.toLowerCase();
		window.location = 'index.php?components=<?php print $menu_components; ?>&action=chque_return&sm='+sm;
	}

	function confirmAction() {
		<?php if(isSalesmanPaymentDepositActive()){  ?>
			var reason=document.getElementById('status').value;
			if(reason != ''){
				if (confirm('Are you sure you want to mark this as return cheque?')) {
            		window.location = 'index.php?components=<?php echo $menu_components; ?>&action=chque_setreturn&reason='+reason+'&id=<?php echo $chq_id; ?>';
        		}
			}else{
				alert('Please select reason!');
			}
		<?php }else{ ?>
			if (confirm('Are you sure you want to mark this as return cheque?')) {
				window.location = 'index.php?components=<?php echo $menu_components; ?>&action=chque_setreturn&id=<?php echo $chq_id; ?>';
			}
		<?php } ?>
    }
</script>

<table align="center" style="font-size:11pt">
	<tr>
		<td>
			<?php
				if(isset($_REQUEST['message'])){
					if($_REQUEST['re']=='success') $color='green'; else $color='red';
				print '<span style="color:'.$color.'; font-weight:bold;">'.$_REQUEST['message'].'</span><br /><br />';
				}
			?>
		</td>
	</tr>
</table>

<form action="index.php?components=<?php print $menu_components; ?>&action=chque_return" method="post">
	<table align="center" height="100%" cellspacing="0" style="font-size:10pt">
		<tr>
			<td>Search Cheque</td>
			<td width="20px"></td>
			<td><input type="text" id="tags1" name="chque_no" /><input type="submit" value="Submit" /></td>
		</tr>
		<tr>
			<td colspan="3" align="center" style="font-size:9pt; color:silver">You can query Cheques for last 30 days
			</td>
		</tr>
	</table>
</form>
<br /><br />
<?php
	if(isset($_REQUEST['chque_no'])){
		if($_REQUEST['chque_no']!=''){ ?>
			<table align="center" bgcolor="#E5E5E5" style="font-size:10pt" cellspacing="0" border="0">
				<tr>
					<td colspan="7" height="10px"></td>
				</tr>
				<tr>
					<td width="10px" rowspan="8"></td>
					<td>Code</td>
					<td>Bank</td>
					<td>Branch</td>
					<td width="150px"></td>
					<td>Date</td>
					<td width="10px" rowspan="8"></td>
				</tr>
				<tr>
					<td><input type="text" disabled="disabled" value="<?php print $chq_no; ?>" style="width:150px" /></td>
					<td><input type="text" disabled="disabled" value="<?php print $chq_bank_code; ?>" style="width:100px" /></td>
					<td><input type="text" disabled="disabled" value="<?php print $chq_branch; ?>" style="width:60px" /></td>
					<td></td>
					<td><input type="text" disabled="disabled" value="<?php print $chq_date; ?>"
							style="width:90px; text-align:center" /></td>
				</tr>
				<tr>
					<td height="30px">Cheque Bank: </td>
					<td style="color:green"><?php print $chq_bank; ?></td>
					<td height="30px"></td>
					<td height="30px">Deposited Bank: </td>
					<td style="color:blue"><?php print $chq_deposited_bank; ?></td>
					<td height="30px"></td>
				</tr>
				<?php for($i=0;$i<sizeof($chq_salesman);$i++){
					print '<tr>
						<td align="right">Related Invoice</td>
						<td><input type="text" disabled="disabled" value="'.$chq_invno[$i].'"
								style="width:60px; text-align:right; padding-right:10px" /></td>
						<td></td>
						<td colspan="4" align="right">Amount <input type="text" disabled="disabled"
								value="'.number_format($chq_amount[$i],$decimal).'" style="width:80px; text-align:right; padding-right:10px" />
						</td>
					</tr>';
				} ?>
				<tr>
					<td colspan="5" height="30px"></td>
				</tr>
				<?php for($i=0;$i<sizeof($chq_salesman);$i++){
					print '<tr>
						<td><input type="text" disabled="disabled" value="'.ucfirst($chq_salesman[$i]).'" style="width:150px" /></td>
						<td colspan="4" align="right"><input type="text" disabled="disabled" value="'.ucfirst($chq_cuname[$i]).'"
								style="width:250px; text-align:right; padding-right:10px" /></td>
					</tr>';
				} ?>
				<tr>
					<td align="center">Salesman</td>
					<td></td>
					<td></td>
					<td></td>
					<td align="center">Customer</td>
				</tr>
				<tr>
					<td colspan="5" height="20px"></td>
				</tr>
				<tr>
					<td colspan="8" height="10px" style="background-color:white"></td>
				</tr>
				<?php if(($chq_return==0) && (isSalesmanPaymentDepositActive())){ ?>
					<tr height="50">
						<td colspan="8" height="10px" style="background-color:white" align="center">
							<select name="status" id="status" style="color:maroon; font-weight:bold">
								<option value="">-SELECT REASON-</option>
								<option value="7">Bank Return</option>
								<option value="8">Cash Receive</option>
								<option value="9">Issue New Cheque</option>
							</select>
						</td>
					</tr>
				<?php } ?>
				<?php if($chq_return==0){ ?>
					<tr>
						<td colspan="8" style="background-color:white" align="center">
							<input type="button" onclick="confirmAction()" value="Mark as Return Cheque" style="color:white; background:maroon; width:200px; height:30px; font-weight:bold" onclick="window.location = 'index.php?components=<?php print $menu_components; ?>&action=chque_setreturn&id=<?php print $chq_id; ?>'" />
						</td>
					</tr>
				<?php } ?>
			</table>
<?php
	}}
?>
<hr /><br />
<div id="printheader" style="display:none">
	<h1 style="color:navy"><?php print $inf_company; ?></h1>
	<h2 align="center" style="color:#3333FF; text-decoration:underline; font-family:Calibri">List Of Returned Cheques
	</h2>
	<hr />
</div>

<h2 align="center" style="color:#0158C2">List Of Returned Cheques</h2>
<div id="print">
	<table align="center" bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0" style="font-size:10pt">
		<tr style="font-weight:bold; background-color:#467898; color:white; -webkit-print-color-adjust: exact;">
			<th>Cheque No</th>
			<th>Bank</th>
			<th>Cheque Date</th>
			<th>Returned Date</th>
			<th>Amount</th>
			<th>Customer</th>
			<th>Related Invoice</th>
			<th>Collected By</th>
			<th>Status</th>
			<th></th>
		</tr>
		<tr style="font-weight:bold; background-color:#467898; color:white; -webkit-print-color-adjust: exact;">
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th>
				<select id="sm" onchange="filterSalesman()">
					<option id="">-ALL-</option>
					<?php for($i=0;$i<sizeof($salesman_filter);$i++){
						if($salesman_filter[$i]==$sm) $select='selected="selected"'; else $select='';
						print '<option id="'.$salesman_filter[$i].'" '.$select.' >'.ucfirst($salesman_filter[$i]).'</option>';
						}
					?>
				</select>
			</th>
			<th></th>
			<th></th>
		</tr>
		<?php
			$total=0;
			for($i=0;$i<sizeof($chq0_id);$i++){
				if($chq0_rtn_clear[$i]=='Pending'){
					$style='style="background-color:#EE2222; color:white;"';
					$clear_btt='';
				}else{
					$style='style="background-color:green; color:white;"';
					$clear_btt='<input type="button" value="Move to Pending"
						onclick="pendingReturnChq('.$chq0_id[$i].')" />&nbsp&nbsp<a href="#"
						onclick="deleteReturnChq('.$chq0_id[$i].')"
						style="font-family:'."'Courier New'".', Courier, monospace; color:red; font-weight:bold; font-size:14pt; text-decoration:none"
						title="Hide Record"><img src="images/action_delete.gif" style="vertical-align:middle" /></a>&nbsp;&nbsp;';
				}
				if(($sm=='-all-')||($sm==$chq0_salesman[$i])){
					print '<tr>
						<td style="padding-left:10px; padding-right:10px">
							<a href="index.php?components='.$menu_components.'&action=chque_return&chque_no='.$chq0_code[$i].'&sm='.$sm.'">'.$chq0_code[$i].'</a>
						</td>
						<td style="padding-left:10px; padding-right:10px">'.$chq0_bank[$i].'</td>
						<td style="padding-left:10px; padding-right:10px">'.$chq0_date[$i].'</td>
						<td style="padding-left:10px; padding-right:10px">'.$chq0_returndate[$i].'</td>
						<td style="padding-left:10px; padding-right:10px" align="right">'.number_format($chq0_amount[$i],$decimal).'</td>
						<td style="padding-left:10px; padding-right:10px">'.ucfirst($chq0_cuname[$i]).'</td>
						<td align="center">'.$chq0_invno[$i].'</td>
						<td style="padding-left:10px; padding-right:10px">'.ucfirst($chq0_salesman[$i]).'</td>
						<td '.$style.' class="shipmentTB3"><a title="'.$chq0_rtn_cle_date[$i].'">'.$chq0_rtn_clear[$i].'</a></td>
						<td>'.$clear_btt.'</td>
					</tr>';
					$total+=$chq0_amount[$i];
				}
			}
			print '<tr>
				<td colspan="4"></td>
				<td><strong>Total</strong></td>
				<td>'.number_format($total,$decimal).'</td>
				<td colspan="5"></td>
			</tr>';
		?>
	</table>
</div>

<br />
<table align="center">
	<tr>
		<td align="center">
			<div style="background-color:#6699FF; border:medium; border-color:black; width:80px;">
				<a class="shortcut-button" onclick="printdiv('print','printheader')" href="#"><span
						style="text-decoration:none; font-family:Arial; color:navy;">
						<img src="images/print.png" alt="icon" /><br />
						Print
					</span></a>
			</div>
		</td>
	</tr>
</table>
<?php
    include_once  'template/footer.php';
?>