<?php
	include_once  'template/header.php';
	$store_report='ALL';
?>
<script type="text/javascript">
	function filterChqueRealizeReport(){
		$components=document.getElementById("components").value;
		$from_date=document.getElementById("from_date").value;
		$to_date=document.getElementById("to_date").value;
		$st=document.getElementById("st").value;
		$sb=document.getElementById("sb").value;
		$sm=document.getElementById("sm").value;
		$bnk=document.getElementById("bnk").value;
		window.location = 'index.php?components='+$components+'&action=chque_realize_report_daterange&from_date='+$from_date+'&to_date='+$to_date+'&st='+$st+'&sm='+$sm+'&sb='+$sb+'&bnk='+$bnk;	
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

<!-- Filters -->
<form action="index.php" method="get" onsubmit="return validateDateRange()" >
	<input type="hidden" id="components" name="components" value="<?php print $_GET['components']; ?>" />
	<input type="hidden" name="action" value="chque_realize_report_daterange" />
	<table width="900px" align="center" height="100%" cellspacing="0"  style="font-size:10pt; font-family:Calibri; border-radius:5px" bgcolor="#EEEEEE">
	<tr>
		<td class="shipmentTB3" style="text-align:right;">
			<span><strong>From Date : </strong></span>
			<input type="date" id="from_date" name="from_date" style="width:140px" value="<?php print $from_date; ?>" />
		</td>
		<td class="shipmentTB3" style="text-align:center;">
			<span><strong>To Date : </strong></span>
			<input type="date" id="to_date" name="to_date" style="width:140px" value="<?php print $to_date; ?>" />
		</td>
		<td class="shipmentTB3" style="text-align:left;">
			<span><strong>Bank Status : </strong></span>
			<select id="bnk" name="bnk" onchange="filterChqueRealizeReport()">
				<option value="0" <?php if((isset($_GET['bnk'])) && ($_GET['bnk'] ==0)) { print 'selected'; }?>>Not Banked</option>
				<option value="1" <?php if((isset($_GET['bnk'])) && ($_GET['bnk'] ==1)) { print 'selected'; }?>>Banked</option>
				<option value="2" <?php if((isset($_GET['bnk'])) && ($_GET['bnk'] ==2)) { print 'selected'; }?>>All</option>
			</select>
		</td>
		<td class="shipmentTB3" style="text-align:right; padding-top:10px;">
			<input style="width:80px; height:40px" type="submit" value="GET" />		
		</td>
	</tr>
	<tr>
		<td class="shipmentTB3" style="text-align:right">
			<span><strong>Sub System : </strong></span>
			<select id="sb" name="sb" onchange="filterChqueRealizeReport()">
				<?php if($_REQUEST['components'] == 'topmanager' || $_REQUEST['components'] == 'fin'){  ?>
					<option value="">-ALL-</option>
				<?php } ?>
				<?php for($i=0;$i<sizeof($sb_id);$i++){
					if(isset($_GET['sb'])){
						if($_GET['sb']==$sb_id[$i]){
							$select='selected="selected"';
							$sub_system_report=$sb_name[$i];
						}else $select='';
					}else $select='';
					print '<option value="'.$sb_id[$i].'" '.$select.'>'.$sb_name[$i].'</option>';
				}
				?>
			</select>
		</td>
		<td class="shipmentTB3" style="text-align:center">
			<span><strong>Associated Shop/Store : </strong></span>
			<select id="st" name="st" onchange="filterChqueRealizeReport()">
				<option value="" >-ALL-</option>
				<?php for($i=0;$i<sizeof($st_id);$i++){
					if(isset($_GET['st'])){
						if($_GET['st']==$st_id[$i]){
							$select='selected="selected"';
							$store_report=$st_name[$i];
						}else $select='';
					}else $select='';
					print '<option value="'.$st_id[$i].'" '.$select.'>'.$st_name[$i].'</option>';
				}
				?>
			</select>
		</td>
		<td class="shipmentTB3" style="text-align:left">
			<span><strong>Collected Salesman : </strong></span>
			<select id="sm" name="sm" onchange="filterChqueRealizeReport()">
				<option value="" >-ALL-</option>
				<?php for($i=0;$i<sizeof($sm_id);$i++){
					if(isset($_GET['sm'])){
						if($_GET['sm']==$sm_id[$i]){
							$select='selected="selected"';
							$salesman_report=$sm_name[$i];
						}else $select='';
					}else $select='';
					print '<option value="'.$sm_id[$i].'" '.$select.'>'.ucfirst($sm_name[$i]).'</option>';
				}
				?>
			</select>
		</td>
	</tr>
	<tr>
		<td align="left">
			<input type="checkbox" style="margin-left:10px;" checked="checked" onchange="window.location = 'index.php?components=<?php print $_GET['components']; ?>&action=chque_realize_report_onedate'" /><span> Date Range</span>
		</td>
		<td align="right" colspan="14">
			<input type="button" style="margin-right:10px" onclick="window.location = 'index.php?components=<?php print $_GET['components']; ?>&action=clear_chque_list&year=<?php print date("Y",time()); ?>'" value="List of Cleared Cheques" />
		</td>
	</tr>
	<tr>
		<td colspan="15" align="center">Note: Deposited Cheques will be marked in <span style="color:green; font-weight:bold">GREEN</span> color AND Returned Cheques will be marked in <span style="color:red; font-weight:bold">RED</span> color</td>
	</tr>
	</table>
</form>
<!--// Filter -->

<!-- Print -->
<div id="printheader" style="display:none" >
	<h1 style="color:navy"><?php print $inf_company; ?></h1>
	<h2 align="center" style="color:#3333FF; text-decoration:underline">Cheque Report</h2>
	<table style="font-size:12pt" border="1" cellspacing="0" style="font-family:Calibri">
		<tr><td style="background-color:#C0C0C0; padding-left:10px" >From Date</td><td style="background-color:#EEEEEE; padding-left:10px">&nbsp;&nbsp;<?php print $from_date; ?>&nbsp;&nbsp;</td></tr>
		<tr><td style="background-color:#C0C0C0; padding-left:10px" >To Date</td><td style="background-color:#EEEEEE; padding-left:10px">&nbsp;&nbsp;<?php print $to_date; ?>&nbsp;&nbsp;</td></tr>
	</table><br />
	<p>Note: This report shows List of Cheques for the selected period</p><hr>
</div>
<!--// Print -->

<!-- Cheque Data -->
<br />
<table align="center" height="100%" style="font-size:10pt; font-family:Calibri">
	<tr bgcolor="#CCCCCC">
		<th>#</th>
		<th>Related<br>Invoice</th>
		<th>Cheque No</th>
		<th>Cheque Bank</th>
		<th>Cheque Date</th>
		<th>Amount</th>
		<th>Deposited<br>Bank</th>
		<th width="200px">Custommer</th>
		<th>#</th>
	</tr>
	<?php
		$total=$dep_total=$undep_total=$rtn_total=0;
		for($i=0;$i<sizeof($py_date);$i++){
			if($chque_clear[$i]==1){ $color='color:green'; $dep_total+=$amount[$i]; }else{ $color=''; }
			if($chque_return[$i]==1){ $color='color:red'; $rtn_total+=$amount[$i]; }
			if($color==''){ $undep_total+=$amount[$i]; }
			print '<tr bgcolor="#EEEEEE">
					<td class="shipmentTB3" align="center">'.($i+1).'</td>
					<td class="shipmentTB3"><a href="index.php?components=billing&action=finish_bill&id='.$invoice[$i].'">'.str_pad($invoice[$i], 7, "0", STR_PAD_LEFT).'</a></td>
					<td align="center" class="shipmentTB3"><a href="#" title="Collected By : '.ucfirst($salesman[$i]).'" style="text-decoration:none; '.$color.'">'.$chque_no[$i].' | '.$chque_bnk_code[$i].' | '.$chque_bnk_brn[$i].'</a></td>
					<td class="shipmentTB3">'.$chque_bnk_name[$i].'</td>
					<td class="shipmentTB3"><a href="#" title="Collected on    : '.$py_date[$i].'&#13;Deposisted on : '.$deposit_date[$i].'&#13;Deposited By   : '.ucfirst($deposit_by[$i]).'" style="text-decoration:none">'.$chque_date[$i].'</a></td>
					<td class="shipmentTB3" align="right">'.number_format($amount[$i]).'</td>
					<td class="shipmentTB3">'.$deposit_bnk[$i].'</td>
					<td class="shipmentTB3">'.$cust[$i].'</td>
					<td class="shipmentTB3" align="center">'.($i+1).'</td>
				</tr>';
			$total+=$amount[$i];
		}
		print '<tr bgcolor="#CCCCCC"><td colspan="5" align="center"><strong>All Cheque Total (Including return)</strong></td><td class="shipmentTB3" align="right"><strong>'.number_format($total).'</strong></td><td colspan="3"></td></tr>';
		print '<tr bgcolor="#CCCCCC"><td colspan="5" align="center"><strong>Deposited Cheques Total</strong></td><td class="shipmentTB3" align="right"><strong>'.number_format($dep_total).'</strong></td><td colspan="3"></td></tr>';
		print '<tr bgcolor="#CCCCCC"><td colspan="5" align="center"><strong>Undeposited Cheques Total</strong></td><td class="shipmentTB3" align="right"><strong>'.number_format($undep_total).'</strong></td><td colspan="3"></td></tr>';
		print '<tr bgcolor="#CCCCCC"><td colspan="5" align="center"><strong>Return Cheques Total</strong></td><td class="shipmentTB3" align="right"><strong>'.number_format($rtn_total).'</strong></td><td colspan="3"></td></tr>';
	?>
</table>
<!--// Cheque Data -->

<!-- Print -->
<div id="print" style="display:none">
	<table align="center" height="100%" style="font-size:10pt; font-family:Calibri" border="1" cellspacing="0">
	<tr bgcolor="#CCCCCC"><th>Related<br>Invoice</th><th>Cheque No</th><th>Cheque Bank</th><th>Cheque Date</th><th>Amount</th><th>Deposited<br>Bank</th><th width="200px">Custommer</th></tr>
	<?php
		$total=$dep_total=$undep_total=$rtn_total=0;
		for($i=0;$i<sizeof($py_date);$i++){
			if($chque_clear[$i]==1){ $color='color:green'; $dep_total+=$amount[$i]; }else{ $color=''; }
			if($chque_return[$i]==1){ $color='color:red'; $rtn_total+=$amount[$i]; }
			if($color==''){ $undep_total+=$amount[$i]; }
			print '<tr bgcolor="#EEEEEE"><td class="shipmentTB3"><a href="index.php?components=billing&action=finish_bill&id='.$invoice[$i].'">'.str_pad($invoice[$i], 7, "0", STR_PAD_LEFT).'</a></td>
			<td align="center"  class="shipmentTB3"><a href="#" title="Collected By : '.ucfirst($salesman[$i]).'" style="text-decoration:none; '.$color.'">'.$chque_no[$i].' | '.$chque_bnk_code[$i].' | '.$chque_bnk_brn[$i].'</a></td>
			<td class="shipmentTB3">'.$chque_bnk_name[$i].'</td>
			<td class="shipmentTB3"><a href="#" title="Collected on    : '.$py_date[$i].'&#13;Deposisted on : '.$deposit_date[$i].'&#13;Deposited By   : '.ucfirst($deposit_by[$i]).'" style="text-decoration:none">'.$chque_date[$i].'</a></td>
			<td class="shipmentTB3" align="right">'.number_format($amount[$i]).'</td>
			<td class="shipmentTB3">'.$deposit_bnk[$i].'</td>
			<td class="shipmentTB3">'.$cust[$i].'</td></tr>';
			$total+=$amount[$i];
		}
		print '<tr bgcolor="#CCCCCC"><td colspan="4" align="center"><strong>All Cheque Total (Including return)</strong></td><td class="shipmentTB3" align="right"><strong>'.number_format($total).'</strong></td><td colspan="2"></td></tr>';
		print '<tr bgcolor="#CCCCCC"><td colspan="4" align="center"><strong>Deposited Cheques Total</strong></td><td class="shipmentTB3" align="right"><strong>'.number_format($dep_total).'</strong></td><td colspan="2"></td></tr>';
		print '<tr bgcolor="#CCCCCC"><td colspan="4" align="center"><strong>Undeposited Cheques Total</strong></td><td class="shipmentTB3" align="right"><strong>'.number_format($undep_total).'</strong></td><td colspan="2"></td></tr>';
		print '<tr bgcolor="#CCCCCC"><td colspan="4" align="center"><strong>Return Cheques Total</strong></td><td class="shipmentTB3" align="right"><strong>'.number_format($rtn_total).'</strong></td><td colspan="2"></td></tr>';
	?>
	</table>
</div>	
<!--// Print -->

<br />
<hr />

<table align="center">
	<tr>
		<td align="center">
			<div style="background-color:#6699FF; border:medium; border-color:black; width:80px;">
				<a class="shortcut-button" onclick="printdiv('print','printheader')" href="#"><span style="text-decoration:none; font-family:Arial; color:navy;">
				<img src="images/print.png" alt="icon" /><br />
				Print
				</span></a>
			</div>
		</td>
	</tr>
</table>

<br />
<?php
    include_once  'template/footer.php';
?>