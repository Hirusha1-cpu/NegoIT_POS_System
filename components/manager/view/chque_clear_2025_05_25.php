<?php
    include_once  'template/header.php';
    $store_report='ALL';
	$decimal = getDecimalPlaces(1);
?>
<style>
	.td{
		padding:10px
	}
	tr {
        transition: background-color 0.3s ease;
    }
</style>
<script type="text/javascript">
	function filterChques(){
		$components=document.getElementById("components").value;
		$bnk=document.getElementById("bnk").value;
		$from_date=document.getElementById("from_date").value;
		$to_date=document.getElementById("to_date").value;
		window.location = 'index.php?components='+$components+'&action=clear_chque_list&bnk='+$bnk+'&from_date='+$from_date+'&to_date='+$to_date;
	}
</script>

<form action="index.php?components=<?php print $_GET['components']; ?>&action=clear_chque_list" method="post" onsubmit="return validateDateRange()">
	<input type="hidden" id="components" name="components" value="<?php print $components; ?>" />
	<table align="center" border="0" height="100%" cellspacing="0" style="font-size:10pt; font-family:Calibri" bgcolor="#CCCCCC">
		<tr>
			<td class="td">From Date</td>
			<td class="td"><input type="date" id="from_date" name="from_date" style="width:130px" value="<?php if(isset($from_date))print $from_date; ?>" required/></td>
			<td class="td">To Date</td>
			<td class="td"><input type="date" id="to_date" name="to_date" style="width:130px" value="<?php if(isset($to_date)) print $to_date; ?>" required/></td>
			<td class="td"><input style="width:60px; height:40px" type="submit" name="submit" value="GET"/></td>
			<td class="td"><strong>Deposited Bank</strong></td>
			<td class="td" width="130px">
				<select id="bnk" name="bnk" onchange="filterChques()">
					<option value="">-ALL-</option>
					<?php for($i=0;$i<sizeof($bnk_id);$i++){
						if(isset($_REQUEST['bnk'])){
							if($_REQUEST['bnk']==$bnk_id[$i]){
								$select='selected="selected"';
							}else $select='';
						}else $select='';
						print '<option value="'.$bnk_id[$i].'" '.$select.'>'.$bnk_name[$i].'</option>';
					} ?>
				</select>
			</td>
			<td class="shipmentTB3"  align="right" bgcolor="">
				<input type="button" style="width:80px" onclick="window.location = 'index.php?components=<?php print $_GET['components']; ?>&action=chque_pending_finalyze'" value="Back" />
			</td>
		</tr>
	</table>
</form>

<div id="printheader" style="display:none">
	<h1 style="color:navy"><?php print $inf_company; ?></h1>
	<h2 align="center" style="color:#3333FF; text-decoration:underline">Cheque Report</h2>
	<table style="font-size:12pt" border="1" cellspacing="0" style="font-family:Calibri">
		<tr>
			<td style="background-color:#C0C0C0; padding-left:10px">To</td>
			<td style="background-color:#EEEEEE; padding-left:10px">&nbsp;&nbsp;<?php if(isset($todate)){ print $todate; } ?>&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td style="background-color:#C0C0C0; padding-left:10px">&nbsp;&nbsp;Shop / Store &nbsp;&nbsp;&nbsp;</td>
			<td style="background-color:#EEEEEE; padding-left:10px">
				&nbsp;&nbsp;<?php print $store_report; ?>&nbsp;&nbsp;</td>
		</tr>
	</table><br />
	<p>Note: This report shows the amount of Cheques which are not deposited yet</p>
	<hr>
</div>

<br />
<table align="center" height="100%" style="font-size:10pt; font-family:Calibri">
	<tr bgcolor="#CCCCCC">
		<th><span id="checkedCount"></span></th>
		<th>#</th>
		<th>Payment No</th>
		<th>Related<br>Invoice</th>
		<th>Cheque No</th>
		<th>Cheque Bank</th>
		<th>Cheque Date</th>
		<th>Realized Date</th>
		<th>Amount</th>
		<th>Deposited<br>Bank</th>
		<th width="200px">Customer</th>
	</tr>
	<?php
	for($i=0;$i<sizeof($py_date);$i++){
		$payment_link = '<a target="_blank" href="index.php?components=billing&action=finish_payment&id=' . $payment_id[$i] . '">' . str_pad($payment_id[$i], 7, "0", STR_PAD_LEFT) . '</a>';
		print '<tr bgcolor="#EEEEEE">
			<td style="padding-right:10px; padding-left:10px; text-align:center;"><input type="checkbox" class="checkRow" onclick="updateCheckedCount()"/></td>
			<td class="shipmentTB3" align="center">' . sprintf('%02d',($i+1)) . '</td>
			<td class="shipmentTB3" align="center">' . $payment_link . '</td>
			<td class="shipmentTB3">';
			if ($invoice[$i] != 0) {
				print '<a href="index.php?components=billing&action=finish_bill&id='.$invoice[$i].'">'.str_pad($invoice[$i], 7, "0", STR_PAD_LEFT).'</a></td>';
			}else{
				echo "";
			}
			print '<td align="left"  class="shipmentTB3"><a href="#" title="Collected By : '.ucfirst($salesman[$i]).'" style="text-decoration:none">'.$chque_no[$i].' | '.$chque_bnk_code[$i].' | '.$chque_bnk_brn[$i].'</a></td>
			<td class="shipmentTB3">'.$chque_bnk_name[$i].'</td>
			<td class="shipmentTB3"><a href="#" title="Collected on    : '.$py_date[$i].'" style="text-decoration:none">'.$chque_date[$i].'</a></td>
			<td class="shipmentTB3"><a href="#" title="Deposited By   : '.ucfirst($deposit_by[$i]).'" style="text-decoration:none">'.$deposit_date[$i].'</a></td>
			<td class="shipmentTB3" align="right">'.number_format($amount[$i],$decimal).'</td>
			<td class="shipmentTB3">'.$deposit_bnk[$i].'</td>
			<td class="shipmentTB3">'.$cust[$i].'</td>
		</tr>';
	}
	?>
</table>
<br />

<script>
    function updateCheckedCount() {
        const checkboxes = document.querySelectorAll(".checkRow");
        const checkedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
        const checkedCountDisplay = document.getElementById("checkedCount");
        checkedCountDisplay.textContent = checkedCount > 0 ? checkedCount : ""; // Display "" if no checkboxes are checked

        // Highlight rows for checked checkboxes
        checkboxes.forEach(checkbox => {
            const row = checkbox.closest("tr");
            if (checkbox.checked) {
                row.style.backgroundColor = "#BFED9B"; // Highlight color
            }else{
                row.style.backgroundColor = "#F5F5F5"; // Highlight color
            }
        });
    }

    updateCheckedCount();
</script>

<br />
<?php
	include_once  'template/footer.php';
?>