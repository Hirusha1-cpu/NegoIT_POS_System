<?php
    include_once  'template/header.php';
    $store_report='ALL';
    $menu_components=$_GET['components'];
?>

<link rel="stylesheet" href="plugin/jquery/css/jquery.ui.all.css" />
<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
<script src="plugin/jquery/ui/jquery.ui.core.js"></script>
<script src="plugin/jquery/ui/jquery.ui.widget.js"></script>
<script src="plugin/jquery/ui/jquery.ui.position.js"></script>
<script src="plugin/jquery/ui/jquery.ui.autocomplete.js"></script>
<link rel="stylesheet" href="plugin/jquery/css/demos.css" />

<!-- Scripts -->
<script type="text/javascript">
	function filterChqueRealizeReport(){
		$components=document.getElementById("components").value;
		$dateto=document.getElementById("dateto").value;
		$st=document.getElementById("st").value;
		$sb=document.getElementById("sb").value;
		$sm=document.getElementById("sm").value;
		$bnk=document.getElementById("bnk").value;
		window.location = 'index.php?components='+$components+'&action=chque_realize_report_onedate&dateto='+$dateto+'&st='+$st+'&sm='+$sm+'&sb='+$sb+'&bnk='+$bnk;	
	}
</script>
<!--// Scripts -->

<!-- Notifications -->
<table align="center" style="font-size:11pt">
	<tr><td>
		<?php 
			if(isset($_REQUEST['message'])){
				if($_REQUEST['re']=='success') $color='green'; else $color='red';
			print '<span style="color:'.$color.'; font-weight:bold;">'.$_REQUEST['message'].'</span><br /><br />'; 
			}
		?></td>
	</tr>
</table>
<!--// Notifications -->
<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px; padding-top:6px;"/></div>
<form action="index.php" method="get">
	<input type="hidden" id="components" name="components" value="<?php print $menu_components; ?>" />
	<input type="hidden" name="action" value="chque_realize_report_onedate" />
	<table bgcolor="#F0F0F0" align="center" height="100%" cellspacing="0"  style="font-size:10pt; font-family:Calibri">
	<tr>
		<td align="center" bgcolor="#DDDDDD">
			Date Range<br>
			<input type="checkbox" onchange="window.location = 'index.php?components=<?php print $menu_components; ?>&action=chque_realize_report_daterange'" />
		</td>
		<td class="td"><strong style="padding-left: 10px;">Bank Status</strong></td>
		<td class="td" width="130px">
			<select id="bnk" name="bnk" onchange="filterChqueRealizeReport()">
				<option value="0" <?php if((isset($_GET['bnk'])) && ($_GET['bnk'] ==0)) { print 'selected'; }?>>Not Banked</option>
				<option value="1" <?php if((isset($_GET['bnk'])) && ($_GET['bnk'] ==1)) { print 'selected'; }?>>Banked</option>
			</select>
		</td>
		<td width="40px" align="left"  class="shipmentTB3">
			<strong>Up To : </strong>
		</td>
		<td width="220px">
			<input type="date" id="dateto" name="dateto" style="width:130px" value="<?php print $todate; ?>" />
			<input style="width:60px; height:40px" type="submit" value="GET" />
		</td>
		<td>
			<strong>Associated <br/>Shop/Store</strong>
		</td>
		<td>
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
		<td width="50px"></td>
		<td>
			<strong>Sub System</strong>
		</td>
		<td>
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
		<td width="50px"></td><td><strong>Collected<br />Salesman</strong></td><td>
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
	<tr><td colspan="10"></td><td colspan="3" align="right"><input type="button" onclick="window.location = 'index.php?components=<?php print $menu_components; ?>&action=clear_chque_list&year=<?php print date("Y",time()); ?>'" value="List of Cleared Cheques" /></td></tr>
	<tr><td colspan="13"><hr /></td></tr>
	</table>
</form>

<!-- Print -->
<div id="printheader" style="display:none" >
	<h1 style="color:navy"><?php print $inf_company; ?></h1>
	<h2 align="center" style="color:#3333FF; text-decoration:underline">Cheque Report</h2>
	<table style="font-size:12pt" border="1" cellspacing="0" style="font-family:Calibri">
		<tr><td style="background-color:#C0C0C0; padding-left:10px" >Up To</td><td style="background-color:#EEEEEE; padding-left:10px">&nbsp;&nbsp;<?php print $todate; ?>&nbsp;&nbsp;</td></tr>
		<tr><td style="background-color:#C0C0C0; padding-left:10px">&nbsp;&nbsp;Associated Shop / Store &nbsp;&nbsp;&nbsp;</td><td style="background-color:#EEEEEE; padding-left:10px">&nbsp;&nbsp;<?php print $store_report; ?>&nbsp;&nbsp;</td></tr>
	</table><br />
	<p>Note: This report shows the amount of Cheques which are not deposited yet</p><hr>
</div>
<!--// Print -->
<br />

<!-- Cheques -->
<div id="print">
	<table align="center" bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0" style="font-size:10pt; font-family:Calibri">
		<tr bgcolor="#AAAAAA">
			<td colspan="9" style="color:white; font-weight:bold">&nbsp;&nbsp;List of Cheques to be Realized for the Duration</td>
		</tr>
		<tr>
			<th>#</th>
			<th>Related<br>Invoice</th>
			<th width="150px">Cheque No</th>
			<th width="200px">Customer</th>
			<th width="80px">Date</th>
			<th width="100px">Amount</th>
			<th width="100px">Salesman</th>
			<th width="100px">Associated Shop</th>
			<th>#</th>
		</tr>
		<?php
			for($i=0;$i<sizeof($chq_date);$i++){
				print '<tr>
					<td class="shipmentTB3" align="center">'.($i+1).'</td>
					<td class="shipmentTB3"><a href="index.php?components=billing&action=finish_bill&id='.$invoice[$i].'">'.str_pad($invoice[$i], 7, "0", STR_PAD_LEFT).'</a></td>
					<td align="center" class="shipmentTB3"><a href="#" style="text-decoration:none;">'.$chque_no[$i].' | '.$chque_bnk_code[$i].' | '.$chque_bnk_brn[$i].'</a></td>
					<td class="shipmentTB3">'.ucfirst($customer[$i]).'</td>
					<td align="center"><a title="Payment was done on '.$payment_date[$i].'" href="#">'.$chq_date[$i].'</a></td>
					<td align="right" class="shipmentTB3">'.number_format($payment_amount[$i]).'</td>
					<td  class="shipmentTB3">'.ucfirst($payment_salesman[$i]).'</td>
					<td  class="shipmentTB3">'.$payment_store[$i].'</td>
					<td class="shipmentTB3" align="center">'.($i+1).'</td>
				</tr>';
			}
		?>
		<tr>
			<th colspan="5" align="center">Total Amount</th>
			<th align="right" style="padding-right:10px">
				<?php print number_format($chque_total); ?>
			</th>
			<td colspan="3"></td>
		</tr>
	</table>
</div>	
<!--// Cheques -->

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