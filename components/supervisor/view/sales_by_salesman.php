<?php
    include_once  'template/header.php';
    if($storedisable!=''){ if($_GET['store']!= $global_store_id){ print '<script>window.location = '."'index.php?components=$components&action=sale&store=$global_store_id&salesman=all&processby=all&lock=1&type='".';</sctript>'; } }
    if($userdisable!=''){ if($_GET['salesman']!=$global_user_id){ print'aaa'; print '<script type="text/javascript">window.location = '."'index.php?components=$components&action=sale&store=$global_store_id&salesman=$global_user_id&processby=all&lock=1&type='".';</sctript>'; }}
    $decimal=0;
    if($systemid==13) $decimal=2; 
    if($systemid==14) $decimal=2; 
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
		window.location='index.php?components=<?php print $components; ?>&action=sales_by_salesman&store='+store0+'&group='+group0+'&salesman='+salesman0+'&processby='+processby0+'&lock='+lock+'&type='+type+'&date1='+date1+'&date2='+date2;
	}
</script>


<div id="loading" style="display:none">
	<img src="images/loading.gif" style="width:40px" />
</div>

<table align="center" height="100%" cellspacing="0" border="0" style="font-family:Calibri; font-size:10pt; border-radius: 15px; padding-left:10px; padding-right:10px" bgcolor="#F0F0F0" border="0">
	<tr>
		<td colspan="8" >
			<table style="font-family:'Courier New', Courier, monospace">
			<tr><td colspan="3">Report by Invoice Date</td></tr>
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
		<td width="100px" align="right"><strong>Process By : </strong>
			<td>
				<select id="processby0" <?php print $userdisable; ?> onchange="getSalesReport()">
				<option value="all" >--ALL--</option>
				<?php
				$processbyname='ALL';
				 for($i=0;$i<sizeof($up_id);$i++){
				 	if($up_id[$i]==$_GET['processby']){ $select='selected="selected"'; $processbyname=ucfirst($up_name[$i]); }else $select='';
				 	print '<option value="'.$up_id[$i].'" '.$select.'>'.ucfirst($up_name[$i]).'</option>';
				 }
				?>
				</select>
			</td>
		<td></td>
	</tr>
	<tr>
		<td width="100px" align="right"><strong>Store : </strong></td>
		<td>
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
		<?php
		$selectedgroup='ALL';
		if(($components=='supervisor')||($components=='marketing')){
		?>
		<td width="100px" align="right"><strong>Group : </strong></td>
		<td>
				<select id="group0" onchange="getSalesReport()">
				<option value="all" >--ALL--</option>
				<?php
				 for($i=0;$i<sizeof($gp_id);$i++){
				 	if($gp_id[$i]==$_GET['group']){ $select='selected="selected"'; $selectedgroup=ucfirst($gp_name[$i]); }else $select='';
				 		print '<option value="'.$gp_id[$i].'" '.$select.'>'.ucfirst($gp_name[$i]).'</option>';
				 }
				?>
				</select>
			<?php
			}else{
				print '<td>
				</td><td>';
				print '<input type="hidden" id="group0" value="all" />';
			}
			?>
		</td>
		<td width="100px" align="right"><strong>Bill Status : </strong></td>
		<td>
			<select id="lock" onchange="getSalesReport()">
			<option value="1" <?php if($lock_req==1) print 'selected="selected"'; ?> >Lock</option>
			<option value="0" <?php if($lock_req==0) print 'selected="selected"'; ?> >Unlock</option>
			<option value="all" <?php if($lock_req=='all') print 'selected="selected"'; ?> >--ALL--</option>
			</select>
		</td>
		<td width="100px" align="right"><strong>Type : </strong></td>
		<td align="right">
			<select id="type" onchange="getSalesReport()">
			<option value="" <?php if($type_req==''){ print 'selected="selected"'; $typename='ALL'; } ?> >--ALL--</option>
			<option value="1" <?php if($type_req==1){ print 'selected="selected"'; $typename='Product'; } ?> >Product</option>
			<option value="2" <?php if($type_req==2){ print 'selected="selected"'; $typename='Service'; } ?> >Service</option>
			<option value="3" <?php if($type_req==3){ print 'selected="selected"'; $typename='Return'; } ?> >Return</option>
			<option value="4" <?php if($type_req==4){ print 'selected="selected"'; $typename='Repair'; } ?> >Repair</option>
			<option value="5" <?php if($type_req==5){ print 'selected="selected"'; $typename='Warranty'; } ?> >Warranty</option>
			</select>
		</td>
		<td width="100px" align="right"><strong>Salesman : </strong></td>
		<td>
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
		</td>
		<td width="50px"></td>
	</tr>
</table>

<table align="center"><tr><td><div id="chart_div" style="width: 900px; height: 300px;"></div></td></tr></table>

<br/><br/>

<div id="printheader" style="display:none" >
	<h1 style="color:navy"><?php print $inf_company; ?></h1>
	<h2 align="center" style="color:#3333FF; text-decoration:underline">Sales Report</h2>
	<table style="font-size:12pt" border="1" cellspacing="0">
	<?php if(($date1!='')&&($date2!='')){ ?>
		<tr><td style="background-color:#C0C0C0; padding-left:10px">&nbsp;&nbsp;From &nbsp;&nbsp;&nbsp;</td><td style="background-color:#EEEEEE; padding-left:10px">&nbsp;&nbsp;<?php print $date1; ?>&nbsp;&nbsp;</td></tr>
		<tr><td style="background-color:#C0C0C0; padding-left:10px">&nbsp;&nbsp;To &nbsp;&nbsp;&nbsp;</td><td style="background-color:#EEEEEE; padding-left:10px">&nbsp;&nbsp;<?php print $date2; ?>&nbsp;&nbsp;</td></tr>
	<?php }else{ ?>
		<tr><td style="background-color:#C0C0C0; padding-left:10px">&nbsp;&nbsp;Date &nbsp;&nbsp;&nbsp;</td><td style="background-color:#EEEEEE; padding-left:10px">&nbsp;&nbsp;<?php print $date1; ?>&nbsp;&nbsp;</td></tr>
	<?php } ?>
		<tr><td style="background-color:#C0C0C0; padding-left:10px">&nbsp;&nbsp;Store &nbsp;&nbsp;&nbsp;</td><td style="background-color:#EEEEEE; padding-left:10px">&nbsp;&nbsp;<?php print $selectedstore; ?>&nbsp;&nbsp;</td></tr>
		<tr><td style="background-color:#C0C0C0; padding-left:10px">&nbsp;&nbsp;Group &nbsp;&nbsp;&nbsp;</td><td style="background-color:#EEEEEE; padding-left:10px">&nbsp;&nbsp;<?php print $selectedgroup; ?>&nbsp;&nbsp;</td></tr>
		<tr><td style="background-color:#C0C0C0; padding-left:10px">&nbsp;&nbsp;Salesman &nbsp;&nbsp;&nbsp;</td><td style="background-color:#EEEEEE; padding-left:10px">&nbsp;&nbsp;<?php print $selectedsalesman; ?>&nbsp;&nbsp;</td></tr>
	</table><br />
	<p>Note: This report shows the total Sales of Supervisor's Group	</p><hr>
</div>

<div id="print">
		<table align="center" bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0" style="font-family:Calibri">
		<tr><th width="100px">salesman</th><th width="200px">Total Sale</th></tr>
		<?php 
		$total_sale=0;
		for($i=0;$i<sizeof($graph_user);$i++){
				print '<tr><td class="shipmentTB3">'.ucfirst($graph_user[$i]).'</td><td align="right" style="padding-right:10px;">'.number_format($graph_total[$i],$decimal).'</td></tr>';
				$total_sale+=$graph_total[$i];
		}
		print '<tr><td class="shipmentTB3"><strong>Total sale</strong></td><td align="right" style="padding-right:10px;"><strong>'.number_format($total_sale,$decimal).'</strong></td></tr>';
		?>	
		</table>
</div>

<table align="center">
	<tr>
		<td align="center">
			<div style="background-color:#6699FF; border:medium; border-color:black; width:80px; border-radius: 15px;">
				<a class="shortcut-button" onclick="printdiv('print','printheader')" href="#"><span style="text-decoration:none; font-family:Arial; color:navy;">
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