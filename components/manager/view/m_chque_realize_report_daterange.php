<?php
    include_once  'template/m_header.php';
?>

<!-- Scripts -->
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
<!--// Scripts -->

<!--// Start of Check Cleared Chques  -->
<div class="w3-container" style="margin-top:75px">
    <hr>
    <div class="w3-row">

        <div class="w3-col s3"></div>
        <div class="w3-col">
            <form action="index.php" method="get" onsubmit="return validateDateRange()">
            	<input type="hidden" name="components" id="components" value="<?php print $_GET['components']; ?>" />
            	<input type="hidden" name="action" value="chque_realize_report_daterange" />
            	<table  bgcolor="#F0F0F0" border="0" width="100%" align="center" height="100%" cellspacing="0"  style="font-size:10pt; font-family:Calibri; padding:10px;"">
            		<!-- From Date -->
					<tr>
            			<td width="100px" align="left" class="shipmentTB3"><strong>From Date : </strong></td>
						<td>
							<input type="date" id="from_date" name="from_date" style="width:130px" value="<?php print $from_date; ?>" />
						</td>
					</tr>
					<!-- To Date -->
            		<tr>
						<td width="100px" align="left" class="shipmentTB3"><strong>To Date : </strong></td>
						<td>
							<input type="date" id="to_date" name="to_date" style="width:130px" value="<?php print $to_date; ?>" />
						</td>
            		</tr>
					<!-- Banked -->
					<tr>
						<td class="td"><strong style="padding-left: 10px;">Bank Status</strong></td>
						<td class="td" width="130px">
							<select id="bnk" name="bnk" onchange="filterChqueRealizeReport()">
								<option value="0" <?php if((isset($_GET['bnk'])) && ($_GET['bnk'] ==0)) { print 'selected'; }?>>Not Banked</option>
								<option value="1" <?php if((isset($_GET['bnk'])) && ($_GET['bnk'] ==1)) { print 'selected'; }?>>Banked</option>
								<option value="2" <?php if((isset($_GET['bnk'])) && ($_GET['bnk'] ==2)) { print 'selected'; }?>>All</option>
							</select>
						</td>
					</tr>
					<!-- To Date -->
					<!-- Associated Shop/Store -->
					<tr>
						<td width="100px" align="left" class="shipmentTB3">
							<strong>Associated Shop/Store</strong>
						</td>
						<td>
	            			<select id="st" name="st" onchange="filterChqueRealizeReport()">
	            			<option value="">-ALL-</option>
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
            		</tr>
					<!-- Sub System-->
					<tr>
						<td width="100px" align="left" class="shipmentTB3">
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
            		</tr>
					<!-- Collected Salesman -->
					<tr>
						<td width="100px" align="left" class="shipmentTB3">
							<strong>Collected Salesman</strong>
						</td>
						<td>
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
						<td colspan="3" class="shipmentTB3" align="center"><input style="width:60px; height:30px;margin-bottom: 10px;" type="submit" value="GET" /></td>
					</tr>
					<tr>
						<td colspan="4">Note: Deposited Cheques will be marked in <span style="color:green; font-weight:bold">GREEN</span> color</td>
					</tr>

            	</table>
            </form>
        </div>

        <div class="w3-col">
            <hr />
            <table align="center" height="100%" width="100%" style="font-size:10pt; font-family:Calibri; max-width: fit-content;overflow-x: auto;display: block;">
	            <tr bgcolor="#CCCCCC">
					<th>Related<br>Invoice</th>
					<th>Cheque No</th>
					<th>Cheque Bank</th>
					<th>Cheque Date</th>
					<th>Amount</th>
					<th>Deposited<br>Bank</th>
					<th width="200px">Custommer</th>
				</tr>
	            <?php
	            	$total=$dep_total=$undep_total=$rtn_total=0;
	            	for($i=0;$i<sizeof($py_date);$i++){
	            		if($chque_clear[$i]==1){ $color='color:green'; $dep_total+=$amount[$i]; }else{ $color=''; }
	            		if($chque_return[$i]==1){ $color='color:red'; $rtn_total+=$amount[$i]; }
	            		if($color==''){ $undep_total+=$amount[$i]; }
	            		print '<tr bgcolor="#EEEEEE">
								<td class="shipmentTB3">
									<a href="index.php?components=billing&action=finish_bill&id='.$invoice[$i].'">'.str_pad($invoice[$i], 7, "0", STR_PAD_LEFT).'</a>
								</td>
	            				<td align="center"  class="shipmentTB3">
									<a href="#" title="Collected By : '.ucfirst($salesman[$i]).'" style="text-decoration:none; '.$color.'">'.$chque_no[$i].' | '.$chque_bnk_code[$i].' | '.$chque_bnk_brn[$i].'</a>
								</td>
	            				<td class="shipmentTB3">'.$chque_bnk_name[$i].'</td>
	            				<td class="shipmentTB3">
									<a href="#" title="Collected on    : '.$py_date[$i].'&#13;Deposisted on : '.$deposit_date[$i].'&#13;Deposited By   : '.ucfirst($deposit_by[$i]).'" style="text-decoration:none">'.$chque_date[$i].'</a>
								</td>
	            				<td class="shipmentTB3" allign="right">'.number_format($amount[$i]).'</td>
	            				<td class="shipmentTB3">'.$deposit_bnk[$i].'</td>
	            				<td class="shipmentTB3">'.$cust[$i].'</td></tr>';
	            				$total+=$amount[$i];
	            	}
	            	print '<tr bgcolor="#CCCCCC">
								<td colspan="4" align="center"><strong>All Cheque Total (Including return)</strong></td>
								<td class="shipmentTB3" align="right"><strong>'.number_format($total).'</strong></td><td colspan="2"></td>
							</tr>';
	            	print '<tr bgcolor="#CCCCCC">
								<td colspan="4" align="center"><strong>Deposited Cheques Total</strong></td>
								<td class="shipmentTB3" align="right"><strong>'.number_format($dep_total).'</strong></td>
								<td colspan="2"></td>
							</tr>';
	            	print '<tr bgcolor="#CCCCCC">
								<td colspan="4" align="center"><strong>Undeposited Cheques Total</strong></td>
								<td class="shipmentTB3" align="right"><strong>'.number_format($undep_total).'</strong></td>
								<td colspan="2"></td>
							</tr>';
	            	print '<tr bgcolor="#CCCCCC">
								<td colspan="4" align="center"><strong>Return Cheques Total</strong></td>
								<td class="shipmentTB3" align="right"><strong>'.number_format($rtn_total).'</strong></td>
								<td colspan="2"></td>
							</tr>';
	            ?>
            </table>
            <br />
        </div>	
    </div>
</div>

</div>
<hr>
<br />
<?php
    include_once  'template/m_footer.php';
?>
