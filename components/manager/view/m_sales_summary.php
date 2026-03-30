<?php
                include_once  'template/m_header.php';
?>
<!-- ------------------------------------------------------------------------------------ -->
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
<div class="w3-container" style="margin-top:75px">
<div class="w3-row">
  <div class="w3-col s3">
  </div>
  <div class="w3-col">
  <div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px" /></div>
<form action="index.php" method="get" onsubmit="return validateForm()" >
	<input type="hidden" name="components" value="<?php print $components; ?>" />
	<input type="hidden" name="action" value="sales_summary" />
	<table ><tr><td>
		<div style="background-color:#F0F0F0; border-radius:10px; font-family:Calibri">
			<br />
			<table height="100%" cellspacing="0" style="font-size:10pt">
			<tr>
				<td width="30px"></td>
				<td><strong>From Date </strong></td><td>: 
				<input type="date" id="from_date" name="from_date" style="width:130px" value="<?php print $from_date; ?>" />
				<td width="30px"></td>
			</tr>
			<tr>
				<td></td>
				<td><strong>To Date </strong></td><td>: 
				<input type="date" id="to_date" name="to_date" style="width:130px" value="<?php print $to_date; ?>" />
				<td></td>
			</tr>
			<tr>	
				<td></td>
				<td><strong>Category </strong></td><td>: 
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
			</tr>
			<tr>	
				<td></td>
				<td><strong>Store </strong></td><td>: 
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
			<tr>	
				<td></td>
				<td align="center" colspan="2"><div id="div_submit"><input type="submit" value="GET" style="width:100px; height:40px" /></div></td>
				</td><td></td>
			</tr>
			</table>
			<br />
		</div>
	</td></tr></table>
</form>
</div>
  <div class="w3-col">
  <hr />
<?php if(isset($_REQUEST['category'])) print '<div id="chart_div" style="width: 100%; height: 200px;"></div><br />'; ?>
	<table align="center" height="100%"  style="font-size:10pt; font-family:Calibri">
	<tr bgcolor="#BBBBBB"><th>Date</th><th>Sale</th><th>Cash Back</th><th>Total Sale</th></tr>
	<?php
		for($j=0;$j<sizeof($sum_date);$j++){
			if(($j%2)==0) $color='#EEEEEE'; else $color='#DDDDDD';
			print '<tr bgcolor="'.$color.'"><td style="padding-left:20px; padding-right:20px;"><a style="text-decoration:none; color:blue;" id="'.$sum_date[$j].'" href="index.php?components='.$components.'&action=sales_summary_detail&date='.$sum_date[$j].'&from_date='.$from_date.'&to_date='.$to_date.'&category='.$category.'&store='.$store.'">'.$sum_date[$j].'</a></td><td align="right" style="padding-left:20px; padding-right:10px;">'.number_format($sum_sale[$sum_date[$j]]).'</td><td align="right" style="padding-left:20px; padding-right:10px;">'.number_format($sum_cashback[$sum_date[$j]]).'</td><td align="right" style="padding-left:20px; padding-right:10px;">'.number_format($sum_totalsale[$j]).'</td></tr>';
		}
			print '<tr bgcolor="#CCCCCC" style="font-weight:bold"><td style="padding-left:20px; padding-right:20px;">Total</td><td align="right" style="padding-left:20px; padding-right:10px;">'.number_format(array_sum($sum_sale)).'</td><td align="right" style="padding-left:20px; padding-right:10px;">'.number_format(array_sum($sum_cashback)).'</td><td align="right" style="padding-left:20px; padding-right:10px;">'.number_format(array_sum($sum_totalsale)).'</td></tr>';
	?>
	</table>
  
  
  
</div>	
  </div>
</div>
</div>
<hr>
<br />
<?php
                include_once  'template/m_footer.php';
?>
