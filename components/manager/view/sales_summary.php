<?php
    include_once  'template/header.php';
?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
	google.charts.load('current', {'packages':['corechart']});
	google.charts.setOnLoadCallback(drawVisualization);

      function drawVisualization() {
        // Some raw data (not necessarily accurate)
        var data = google.visualization.arrayToDataTable([['Date','Sale'],
        	<?php for($i=0;$i<sizeof($sum_date);$i++){
					print "['".$sum_date[$i]."',$sum_totalsale[$i]],";
				}
			?>
		]);

		var options = {
		vAxis: {title: 'Sales by Date'},
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

  	function validateForm(){
  		if(validateDateRange()){
  			document.getElementById('div_submit').innerHTML=document.getElementById('loading').innerHTML;
  			return true;
  		}else{
			return false;
  		}
  	}
</script>
<!-- ------------------Item List----------------------- -->
<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px" /></div>

<form action="index.php" method="get" onsubmit="return validateForm()" >
	<input type="hidden" name="components" value="<?php print $components; ?>" />
	<input type="hidden" name="action" value="sales_summary" />
	<table align="center"><tr><td>
		<div style="background-color:#DFDFDF; border-radius:10px; font-family:Calibri">
			<table align="center" height="100%" cellspacing="0" style="font-size:10pt">
			<tr><td width="30px"></td>
			<td align="right"><strong>From Date : </strong></td><td>
			<input type="date" id="from_date" name="from_date" style="width:130px" value="<?php print $from_date; ?>" />
			<td width="50px"></td>
			<td align="right"><strong>To Date : </strong></td><td>
			<input type="date" id="to_date" name="to_date" style="width:130px" value="<?php print $to_date; ?>" />
			<td width="50px"></td>
			<td align="right"><strong>Category : </strong></td><td>
			<select name="category" id="category">
				<option value="all" >-ALL-</option>
				<?php
				$cname='ALL';
				for($i=0;$i<sizeof($cat_id);$i++){
					if($category==$cat_id[$i]){ $select='selected="selected"'; $cname=$cat_name[$i]; }else{ $select=''; }
					print '<option value="'.$cat_id[$i].'" '.$select.'>'.$cat_name[$i].'</option>';
				} ?>
			</select>
			</td><td>
			<td width="50px"></td>
			<td align="right"><strong>Store : </strong></td><td>
			<select name="store" id="store">
				<option value="all" >-ALL-</option>
				<?php
				$sname='ALL';
				for($i=0;$i<sizeof($st_id);$i++){
					if($store==$st_id[$i]){ $select='selected="selected"'; $sname=$st_name[$i]; }else{ $select=''; }
					print '<option value="'.$st_id[$i].'" '.$select.'>'.$st_name[$i].'</option>';
				} ?>
			</select>
			</td><td>
				<div id="div_submit"><input type="submit" value="GET" style="width:50px; height:40px" /></div>
			</td><td width="30px"></td></tr>
			</table>
		</div>
	</td></tr></table>
</form>

<div id="printheader" style="display:none" >
	<h2 align="center" style="color:navy"><?php print $inf_company; ?></h2>
	<h3 align="center" style="color:#333399; text-decoration:underline">Sales Summary Report</h3>
	<table style="font-size:12pt" border="1" cellspacing="0">
		<tr><td style="background-color:#C0C0C0; padding-left:10px">From</td><td style="background-color:#EEEEEE; padding-left:10px"><?php print $from_date; ?></td></tr>
		<tr><td style="background-color:#C0C0C0; padding-left:10px">To</td><td style="background-color:#EEEEEE; padding-left:10px"><?php print $to_date; ?></td></tr>
		<tr><td width="100px" style="background-color:#C0C0C0; padding-left:10px">Category</td><td width="250px" style="background-color:#EEEEEE; padding-left:10px"><?php print ucfirst($cname); ?></td></tr>
		<tr><td width="100px" style="background-color:#C0C0C0; padding-left:10px">Store</td><td width="250px" style="background-color:#EEEEEE; padding-left:10px"><?php print ucfirst($sname); ?></td></tr>
	</table><br />
</div>
<?php if(isset($_REQUEST['category'])) print '<div id="chart_div" style="width: 100%; height: 500px;"></div>'; ?>

<br /><br />
<div id="print">
	<table align="center" bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0" style="font-size:12pt; font-family:Calibri">
		<tr>
			<td colspan="11" style="border:0; background-color:black; color:white; font-weight:bold"></td>
		</tr>
		<tr bgcolor="#BBBBBB">
			<th>Date</th>
			<th width="100px">Sale</th>
			<th width="100px">Cash Back</th>
			<th width="100px">Total Sale</th>
		</tr>
			<?php
				for($j=0;$j<sizeof($sum_date);$j++){
					print '<tr><td style="padding-left:20px; padding-right:20px;"><a style="text-decoration:none" id="'.$sum_date[$j].'" href="index.php?components='.$components.'&action=sales_summary_detail&date='.$sum_date[$j].'&from_date='.$from_date.'&to_date='.$to_date.'&category='.$category.'&store='.$store.'">'.$sum_date[$j].'</a></td><td align="right" style="padding-left:40px; padding-right:20px;">'.number_format($sum_sale[$sum_date[$j]]).'</td><td align="right" style="padding-left:40px; padding-right:20px;">'.number_format($sum_cashback[$sum_date[$j]]).'</td><td align="right" style="padding-left:40px; padding-right:20px;">'.number_format($sum_totalsale[$j]).'</td></tr>';
				}
					print '<tr bgcolor="#CCCCCC" style="font-weight:bold"><td style="padding-left:20px; padding-right:20px;">Total</td><td align="right" style="padding-left:40px; padding-right:20px;">'.number_format(array_sum($sum_sale)).'</td><td align="right" style="padding-left:40px; padding-right:20px;">'.number_format(array_sum($sum_cashback)).'</td><td align="right" style="padding-left:40px; padding-right:20px;">'.number_format(array_sum($sum_totalsale)).'</td></tr>';
			?>
	</table>
</div>
<table align="center">
	<tr>
		<td align="center">
			<div style="background-color:#6699FF; border:medium; border-color:black; width:80px;">
				<a class="shortcut-button" onclick="printdiv('print','printheader')" href="#"><span style="text-decoration:none; font-family:Arial; color:navy;">
				<img src="images/print.png" alt="icon" /><br />
				</span></a>
			</div>
		</td>
	</tr>
</table>
<?php
    include_once  'template/footer.php';
?>