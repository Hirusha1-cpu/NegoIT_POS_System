<?php
    include_once  'template/m_header.php';
?>

<!-- Scripts -->
<script type="text/javascript">
	function filterChqueRealizeReport(){
		$components=document.getElementById("components").value;
		$dateto=document.getElementById("dateto").value;
		$st=document.getElementById("st").value;
		$sm=document.getElementById("sm").value;
		$sb=document.getElementById("sb").value;
		$bnk=document.getElementById("bnk").value;
		window.location = 'index.php?components='+$components+'&action=chque_realize_report_onedate&dateto='+$dateto+'&st='+$st+'&sm='+$sm+'&sb='+$sb+'&bnk='+$bnk;	
	}
</script>
<!--// Scripts -->

<!--// Start of Check Pending List  -->
<div class="w3-container" style="margin-top:75px">
    <hr>
    <div class="w3-row">
        <div class="w3-col s3"></div>

        <div class="w3-col">
            <form action="index.php" method="get" onsubmit="return validateDateRange()">
				<input type="hidden" id="components" name="components" value="<?php print $_GET['components']; ?>" />
				<input type="hidden" name="action" value="chque_realize_report_onedate" />
				<table bgcolor="#F0F0F0" align="center" height="100%" cellspacing="0"  style="font-size:10pt; font-family:Calibri; width:100%; padding:10px;">
					<!-- From Date -->
					<tr>
						<td align="right" colspan="3"  class="shipmentTB3">
							<span>Date Range:  </span><input type="checkbox" onchange="window.location = 'index.php?components=<?php print $_GET['components']; ?>&action=chque_realize_report_daterange'" style="margin-top:10px"/>
						</td>
					</tr>
					<!-- Banked -->
					<tr>
						<td class="td"><strong style="padding-left: 10px;">Bank Status</strong></td>
						<td class="td" width="130px">
							<select id="bnk" name="bnk" onchange="filterChqueRealizeReport()">
								<option value="0" <?php if((isset($_GET['bnk'])) && ($_GET['bnk'] ==0)) { print 'selected'; }?>>Not Banked</option>
								<option value="1" <?php if((isset($_GET['bnk'])) && ($_GET['bnk'] ==1)) { print 'selected'; }?>>Banked</option>
							</select>
						</td>
					</tr>
					<!-- To Date -->
					<tr>
						<td align="left" class="shipmentTB3"><strong>Up To : </strong></td>
						<td>
							<input type="date" id="dateto" name="dateto" value="<?php print $todate; ?>" />
						</td>
					</tr>
					<!-- Associated Shop/Store -->
					<tr>
						<td class="shipmentTB3"><strong>Associated Shop/Store</strong></td>
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
					<tr/>
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
						<td class="shipmentTB3">
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
					<tr style="padding-bottom: 10px; padding-top: 10px;">
						<td colspan="3" class="shipmentTB3" align="center" style="padding-bottom:10px;"><input style="width:60px; height:30px; margin-top:10px;" type="submit" value="GET" /></td>
					</tr>
				</table>
            </form>
        </div>

        <div class="w3-col">
            <hr />
            <table align="center" bgcolor="#E5E5E5" width="100%" height="100%" border="1" cellspacing="0" style="font-size:10pt; font-family:Calibri;max-width: fit-content;overflow-x: auto;display: block;">
				<tr bgcolor="#AAAAAA">
					<td colspan="6" style="color:white; font-weight:bold">&nbsp;&nbsp;List of Cheques to be Realized for the Duration</td>
				</tr>
				<tr>
					<th width="150px">Cheque No</th>
					<th width="200px">Customer</th>
					<th width="80px">Date</th>
					<th width="100px">Amount</th>
					<th width="100px">Salesman</th>
					<th width="100px">Associated Shop</th>
				</tr>
				<?php
					for($i=0;$i<sizeof($chq_date);$i++){
						print '<tr>
							<td align="center" class="shipmentTB3">'.$chque_no[$i].' | '.$chque_bnk_code[$i].' | '.$chque_bnk_brn[$i].'</td>
							<td class="shipmentTB3">'.ucfirst($customer[$i]).'</td>
							<td align="center"><a title="Payment was done on '.$payment_date[$i].'" href="#">'.$chq_date[$i].'</a></td>
							<td align="right"  class="shipmentTB3">'.number_format($payment_amount[$i]).'</td>
							<td  class="shipmentTB3">'.ucfirst($payment_salesman[$i]).'</td>
							<td  class="shipmentTB3">'.$payment_store[$i].'</td>
						</tr>';
					}
				?>
				<tr>
					<th colspan="3" align="center">Total Amount</th>
					<th align="right" style="padding-right:10px"><?php print number_format($chque_total); ?></th><td colspan="2"></td>
				</tr>
			</table>
            <br />
        </div>	
    </div>
</div>
<!--// End of Check Pending List  -->

</div>
<hr>
<br />
<?php
    include_once  'template/m_footer.php';
?>
