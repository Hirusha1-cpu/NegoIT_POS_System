<?php
    include_once  'template/m_header.php';
    $store_report='ALL';
    if($storedisable!=''){ if($_GET['store']!= $global_store_id){ print '<script>window.location = '."'index.php?components=$components&action=sale&store=$global_store_id&salesman=all&processby=all&lock=1&type='".';</sctript>'; } }
    if($userdisable!=''){ if($_GET['salesman']!=$global_user_id){ print'aaa'; print '<script type="text/javascript">window.location = '."'index.php?components=$components&action=sale&store=$global_store_id&salesman=$global_user_id&processby=all&lock=1&type='".';</sctript>'; }}
?>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawVisualization);

	function drawVisualization() {
	    // Some raw data (not necessarily accurate)
        var data = google.visualization.arrayToDataTable([['Salesman','Sale'],
        	<?php for($i=0;$i<sizeof($graph_user);$i++){
        	print "['".ucfirst($graph_user[$i])."',$graph_total[$i]],";
       	 }
        	?>
      	]);

	    var options = {
	      vAxis: {title: 'Income'},
	      hAxis: { 
	        direction: -1, 
	        slantedText: true, 
	        slantedTextAngle: 45 // here you can even use 180 
	   	 } ,
	   	 legend: { position: "none" },
	      seriesType: 'bars',
	      series: {5: {type: 'line'}}
	    };

	    var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
	    chart.draw(data, options);
	}

	function getSalesReport(){
		document.getElementById('div_submit').innerHTML=document.getElementById('loading').innerHTML;
		var date1=document.getElementById('date1').value;
		var date2=document.getElementById('date2').value;
		var store0=document.getElementById('store0').value;
		var group0=document.getElementById('group0').value;
		var salesman0=document.getElementById('salesman0').value;
		var processby0=document.getElementById('processby0').value;
		var lock=document.getElementById('lock').value;
		var type=document.getElementById('type').value;
		window.location='index.php?components=<?php print $components; ?>&action=sale&store='+store0+'&group='+group0+'&salesman='+salesman0+'&processby='+processby0+'&lock='+lock+'&type='+type+'&date1='+date1+'&date2='+date2;
	}
</script>
<style>
	.table-responsive{
		overflow-x: auto;
    	white-space: nowrap;
	}
</style>

<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px" /></div>

<div class="w3-container" style="margin-top:75px">
	<hr>
	<div class="w3-row">
	  	<div class="w3-col s3"></div>
	  	<div class="w3-col">

		  	<div class="table-responsive">
			<table align="center" height="100%" cellspacing="0" width="98%" style="font-size:x-small;">
				<tr bgcolor="#F0F0F0">
					<td colspan="4">
						<table style="font-family:'Courier New', Courier, monospace">
							<tr><td align="center" bgcolor="silver" style="color:white">&nbsp;Date Range&nbsp;<br><input type="checkbox" id="date_range" onchange="setDateRange('<?php print $date1; ?>','<?php print $date2; ?>')" <?php if(($date1!='')&&($date2!='')) print 'checked="checked"'; ?> /></td>
							<td>
								<div id="datediv">
								<?php
								if(($date1!='')&&($date2!='')){
									print '<strong>From </strong>: &nbsp;<input type="date" id="date1" name="date1" style="width:130px" value="'.$date1.'" />&nbsp;&nbsp;&nbsp;<strong>To </strong>: &nbsp;<input type="date" id="date2" name="date2" style="width:130px" value="'.$date2.'" />';
								}else{
									print '<strong>Date</strong>: &nbsp;<input type="date" id="date1" name="date1" style="width:130px" value="'.$date1.'" /> <input type="hidden" id="date2" name="date2" value="" />';
								}
								?>
								</div>
							</td><td>
								<div id="div_submit"><a onclick="getSalesReport()" style="cursor:pointer"><img src="images/search.png" style="width:30px; vertical-align:middle" /></a></div>
							</td></tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="100px"><strong>Store : </strong></td>
					<td width="150px">
						<select id="store0" <?php print $storedisable; ?> onchange="getSalesReport()">
						<option value="all" >--ALL--</option>
						<?php
						$selectedstore='ALL';
						 for($i=0;$i<sizeof($store_id);$i++){
						 	if($store_id[$i]==$_GET['store']){ $select='selected="selected"'; $selectedstore=ucfirst($store_name[$i]); }else $select='';
						 	if($systemid==13 && $_COOKIE['user_id']==22 ){
						 		if($store_id[$i]==3 || $store_id[$i]==5)
						 		print '<option value="'.$store_id[$i].'" '.$select.'>'.ucfirst($store_name[$i]).'</option>';
						 	}else{
						 		print '<option value="'.$store_id[$i].'" '.$select.'>'.ucfirst($store_name[$i]).'</option>';
						 	}
						 }
						?>
						</select>
					</td>
					<td width="100px" align="right"></td>
					<td></td>
				</tr>
				<?php
				$selectedgroup='ALL';
				if($components=='supervisor'){
				?>
				<tr><td width="100px"><strong>Group : </strong></td><td width="150px">
						<select id="group0" onchange="getSalesReport()">
						<option value="all" >--ALL--</option>
						<?php
						 for($i=0;$i<sizeof($gp_id);$i++){
						 	if($gp_id[$i]==$_GET['group']){ $select='selected="selected"'; $selectedgroup=ucfirst($gp_name[$i]); }else $select='';
						 		print '<option value="'.$gp_id[$i].'" '.$select.'>'.ucfirst($gp_name[$i]).'</option>';
						 }
						?>
						</select>
				</td><td width="100px" align="right"></td><td></td></tr>
				<?php
				}else{
					print '<tr><td></td><td><input type="hidden" id="group0" value="all" /></td><td></td><td></td></tr>';
				}
				?>
				<tr><td><strong>Bill Status : </strong></td><td>
					<select id="lock" onchange="getSalesReport()">
					<option value="1" <?php if($lock_req==1) print 'selected="selected"'; ?> >Lock</option>
					<option value="0" <?php if($lock_req==0) print 'selected="selected"'; ?> >Unlock</option>
					<option value="all" <?php if($lock_req=='all') print 'selected="selected"'; ?> >--ALL--</option>
					</select>
				</td><td align="right"><strong>Type : </strong></td><td>
					<select id="type" onchange="getSalesReport()">
					<option value="" <?php if($type_req==''){ print 'selected="selected"'; $typename='ALL'; } ?> >--ALL--</option>
					<option value="1" <?php if($type_req==1){ print 'selected="selected"'; $typename='Product'; } ?> >Product</option>
					<option value="2" <?php if($type_req==2){ print 'selected="selected"'; $typename='Service'; } ?> >Service</option>
					<option value="3" <?php if($type_req==3){ print 'selected="selected"'; $typename='Return'; } ?> >Return</option>
					<option value="4" <?php if($type_req==4){ print 'selected="selected"'; $typename='Repair'; } ?> >Repair</option>
					<option value="5" <?php if($type_req==5){ print 'selected="selected"'; $typename='Warranty'; } ?> >Warranty</option>
					</select>
				</td></tr>
				<tr><td width="100px" ><strong>Salesman : </strong></td><td>
					<select id="salesman0" <?php print $userdisable; ?> onchange="getSalesReport()">
					<option value="all" >--ALL--</option>
					<?php
					$selectedsalesman='ALL';
					 for($i=0;$i<sizeof($up_id);$i++){
					 	if($up_id[$i]==$_GET['salesman']){ $select='selected="selected"'; $selectedsalesman=ucfirst($up_name[$i]); }else $select='';
					 	print '<option value="'.$up_id[$i].'" '.$select.'>'.ucfirst($up_name[$i]).'</option>';
					 }
					?>
					</select>
				</td><td width="100px" align="right"><strong>Process By : </strong></td><td>
					<select id="processby0" onchange="getSalesReport()">
					<option value="all" >--ALL--</option>
					<?php
					$processbyname='ALL';
					 for($i=0;$i<sizeof($up_id);$i++){
					 	if($up_id[$i]==$_GET['processby']){ $select='selected="selected"'; $processbyname=ucfirst($up_name[$i]); }else $select='';
					 	print '<option value="'.$up_id[$i].'" '.$select.'>'.ucfirst($up_name[$i]).'</option>';
					 }
					?>
					</select>
				</td></tr>
			</table>
			</div>

			<hr />

			<div class="table-responsive">
				<table align="center">
					<tr>
						<td>
							<div id="chart_div" style="width: 900px; height: 300px;"></div>
						</td>
					</tr>
				</table>
			</div>

			<?php if($type_req!=3 && $type_req!=5){ ?>
				<div class="table-responsive">
					<table bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0" style="font-size:x-small;" align="center">
						<tr>
							<td colspan="10" style="border:0; background-color:black; color:white; font-weight:bold">Invoiced Collection</td>
						</tr>
						<tr>
							<th width="100px">Invoice No</th>
							<th width="120px">Time</th>
							<th width="80px">Invoice Total</th>
							<th width="60px">Cash</th>
							<th width="60px">Card</th>
							<th width="60px">Chque</th>
							<th width="60px">Credit</th>
							<th width="100px">Salesman</th>
							<th width="200px">Customer</th>
						</tr>
						<?php
							$inv=0;
							$store0='';
						for($i=0;$i<sizeof($invoice_no);$i++){
							if($bi_discount[$i]>0){ $color='style="color:red"'; $title='title="Discounted Invoice"'; }else{ $color=''; $title=''; }
							if($store0!=$billed_store[$i]){
								$total_cash=$total_chque=$total_credit=$total_card=0;
								print '<tr>
										<td colspan="10" style="padding-left:20px; font-weight:bold; background-color:#0066AA; color:white">'.$billed_store[$i].'</td>
									</tr>';
							}
								$bill_credit=$invoice_Total[$i]-$payment_cash[$invoice_no[$i]]-$payment_card[$invoice_no[$i]]-$payment_chque[$invoice_no[$i]];
								print '<tr '.$color.'>
										<td align="center">
											<a '.$title.' href="index.php?components=billing&action=finish_bill&id='.$invoice_no[$i].'">'.str_pad($invoice_no[$i], 7, "0", STR_PAD_LEFT).'</a>
										</td>
										<td align="center" width="50px">'.$billed_time[$i].'</td>
										<td align="right" style="padding-right:10px;">'.number_format($invoice_Total[$i]).'</td>
										<td align="right" style="padding-right:10px;">'.number_format($payment_cash[$invoice_no[$i]]).'</td>
										<td align="right" style="padding-right:10px;">'.number_format($payment_card[$invoice_no[$i]]).'</td>
										<td  align="right" style="padding-right:10px;">'.number_format($payment_chque[$invoice_no[$i]]).'</td>
										<td  align="right" style="padding-right:10px;">'.number_format($bill_credit).'</td><td align="center">'.ucfirst($billed_by[$i]).'</td>
										<td align="left" style="padding-left:10px">'.ucfirst($billed_cust[$i]).'</td>
									</tr>';
								$total_cash+=$payment_cash[$invoice_no[$i]];
								$total_card+=$payment_card[$invoice_no[$i]];
								$total_chque+=$payment_chque[$invoice_no[$i]];
								$total_credit+=$bill_credit;
							if(sizeof($invoice_no)==($i+1)){
								print '<tr style="font-weight:bold; background-color:gray; color:white">
										<td colspan="3" align="right"  style="padding-right:10px;">Total</td>
										<td align="right" style="padding-right:10px;">'.number_format($total_cash).'</td>
										<td align="right" style="padding-right:10px;">'.number_format($total_card).'</td>
										<td align="right" style="padding-right:10px;">'.number_format($total_chque).'</td>
										<td align="right" style="padding-right:10px;">'.number_format($total_credit).'</td>
										<td colspan="2"></td></tr>';
								print '</table><br /><table align="center" bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0">';
							}
								$store0=$billed_store[$i];
						}
						?>	
					</table>
				</div>
			<?php } ?>

			<br />

			<?php if($type_req!=3 && $type_req!=5){ ?>
				<div class="table-responsive">
					<table bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0" style="font-size:x-small;" align="center">
						<tr>
							<td colspan="7" style="border:0; background-color:black; color:white; font-weight:bold">Payment Collection</td></tr>
						<tr>
							<th width="100px">Payment No</th>
							<th width="120px">Time</th>
							<th width="80px">Type</th>
							<th width="80px">Amount</th>
							<th width="100px">Salesman</th>
							<th width="300px">Customer</th>
						</tr>
						<?php
							$inv=$total_payment=0;
							$store0='';
							for($i=0;$i<sizeof($payment_id);$i++){
								if($store0!=$payment_store[$i]){
									$total_payment=0;
									print '<tr>
											<td colspan="7" style="padding-left:20px; font-weight:bold; background-color:#0066AA; color:white">'.$payment_store[$i].'</td>
										</tr>';
								}
								print '<tr>
										<td align="center">
											<a href="index.php?components=billing&action=finish_payment&id='.$payment_id[$i].'">'.str_pad($payment_id[$i], 7, "0", STR_PAD_LEFT).'</a>
										</td>
										<td align="center" width="50px">'.$payment_time[$i].'</td>
										<td align="center">'.$payment_type[$i].'</td>
										<td align="right" style="padding-right:10px;">'.number_format($payment_amount[$i]).'</td>
										<td align="center">'.ucfirst($payment_salesman[$i]).'</td>
										<td style="padding-left:10px; padding-right:10px;">'.ucfirst($payment_cust[$i]).'</td>
									</tr>';
								$total_payment+=$payment_amount[$i];
								if(sizeof($payment_id)==($i+1)){
									print '<tr style="font-weight:bold; background-color:gray; color:white">
											<td colspan="3" align="right"  style="padding-right:10px;">Total</td>
											<td align="right" style="padding-right:10px;">'.number_format($total_payment).'</td>
											<td colspan="2"></td>
										</tr>';
									print '</table><br /><table align="center" bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0">';
								}
								$store0=$payment_store[$i];
							}
						?>	
					</table>
				</div>
			<?php } ?>

			<br/>

			<?php if($type_req==3 || $type_req==''){ ?>
				<div class="table-responsive">
					<table bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0" style="font-size:x-small;" align="center">
						<tr><td colspan="7" style="border:0; background-color:black; color:white; font-weight:bold">Return Extra Pay Collection</td></tr>
						<tr><th width="100px">Return No</th><th width="100px" >Time</th><th width="150px" >Extra Pay</th><th width="100px">Salesman</th><th width="250px">Customer</th></tr>
						<?php
							$inv=$total_payment=0;
							$store0='';
							for($i=0;$i<sizeof($rtn_no);$i++){
								if($store0!=$rtn_store[$i]){
									print '<tr><td colspan="7" style="padding-left:20px; font-weight:bold; background-color:#0066AA; color:white">'.$rtn_store[$i].'</td></tr>';
								}
									print '<tr><td align="center"><a href="index.php?components=billing&action=finish_return&id='.$rtn_no[$i].'">'.str_pad($rtn_no[$i], 7, "0", STR_PAD_LEFT).'</a></td><td align="center" width="50px">'.$rtn_time[$i].'</td>
									<td align="right" style="padding-right:10px;">'.number_format($rtn_pay[$i]).'</td>
									<td class="shipmentTB3">'.ucfirst($rtn_salesman[$i]).'</td><td class="shipmentTB3">'.ucfirst($rtn_cust[$i]).'</td></tr>';
									$total_payment+=$rtn_pay[$i];
								if(sizeof($rtn_no)==($i+1)){
									print '<tr style="font-weight:bold; background-color:gray; color:white"><td colspan="2" align="right"  style="padding-right:10px;" >Total</td><td align="right" style="padding-right:10px;">'.number_format($total_payment).'</td><td colspan="2"></td></tr>';
									print '</table><br /><table align="center" bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0">';
								}
									$store0=$rtn_store[$i];
							}
						?>	
					</table>
				</div>
			<?php } ?>

			<br />

			<?php if($type_req==5 || $type_req==''){ ?>
				<div class="table-responsive">
					<table bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0" style="font-family:Calibri" align="center">
						<tr><td colspan="7" style="border:0; background-color:black; color:white; font-weight:bold">Warranty Pay Collection</td></tr>
						<tr><th width="100px">Job No</th><th>Time</th><th width="150px" >Extra Pay</th><th width="100px">Salesman</th><th>Entity</th></tr>
						<?php
						$inv=$total_payment=0;
						$store0='';
							for($i=0;$i<sizeof($wa_no);$i++){
								if($store0!=$wa_store[$i]){
									print '<tr><td colspan="7" style="padding-left:20px; font-weight:bold; background-color:#0066AA; color:white">'.$wa_store[$i].'</td></tr>';
								}
									print '<tr><td align="center">'.str_pad($wa_no[$i], 7, "0", STR_PAD_LEFT).'</td><td align="center" class="shipmentTB3">'.substr($wa_time[$i],0,16).'</td>
									<td align="right" style="padding-right:10px;">'.number_format($wa_pay[$i]).'</td>
									<td class="shipmentTB3">'.ucfirst($wa_salesman[$i]).'</td><td class="shipmentTB3">'.$wa_entity[$i].'</td></tr>';
									$total_payment+=$wa_pay[$i];
								if(sizeof($wa_no)==($i+1)){
									print '<tr style="font-weight:bold; background-color:gray; color:white"><td colspan="2" align="right"  style="padding-right:10px;" >Total</td><td align="right" style="padding-right:10px;">'.number_format($total_payment).'</td><td colspan="2"></td></tr>';
									print '</table><br /><table align="center" bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0">';
								}
									$store0=$wa_store[$i];
							}
						?>	
					</table>
				</div>
			<?php } ?>

		</div>
	</div>
</div>

<hr>
<br/>

<?php
    include_once  'template/m_footer.php';
?>
