<?php
                include_once  'template/m_header.php';
?>
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
   <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart1);
      function drawChart1() {

        var data = google.visualization.arrayToDataTable([
          ['Category','Profit'],
        <?php for($i=0;$i<sizeof($category);$i++){
          print "['$category[$i]',$iprofit[$i] ],";
        } ?>
        ]);

        var options = {
          pieHole: 0.4,
        };
        var chart = new google.visualization.PieChart(document.getElementById('order_profit'));

        chart.draw(data, options);
      }

	</script>

<!-- ------------------------------------------------------------------------------------ -->
</head>

<div class="w3-container" style="margin-top:75px">
<?php 
	if(isset($_REQUEST['message'])){
		if($_REQUEST['re']=='success') $color='green'; else $color='red';
	print '<span style="color:'.$color.'; font-weight:bold;font-size:large;">'.$_REQUEST['message'].'</span>'; 
	}
?>	

<div class="w3-row">
  <div class="w3-col s3">
  </div>
  <div class="w3-col">
		
	<form action="index.php" method="get" onsubmit="return validateDateRange()" >
		<input type="hidden" name="components" value="report" />
		<input type="hidden" name="action" value="category_profit" />
		<table align="center"><tr><td>
		<div style="border-radius:10px; background-color:#DDDDDD;padding-left :20px; padding-right:20px;">
			<table align="center" style="font-family:Calibri; font-size:12pt">
			<tr>
			<td><strong>Sub System</strong></td><td> : 
				<select name="subsys">
					<option value="all" >-ALL-</option>
					<?php
						for($i=0;$i<sizeof($sub_system_list);$i++){
							if($subsys==$sub_system_list[$i]) $select='selected="selected"'; else $select='';
							print '<option value="'.$sub_system_list[$i].'" '.$select.'>'.$sub_system_names[$i].'</option>';
						}
					?>
				</select>
			</td></tr>
			<tr><td><strong>From Date:</strong></td><td> : <input type="date" name="from_date" id="from_date" value="<?php print $from_date; ?>" style="width:130px" /></td></tr>
			<tr><td><strong>To Date:</strong></td><td> : <input type="date" name="to_date" id="to_date" value="<?php print $to_date; ?>" style="width:130px" /></td></tr>
			<tr><td colspan="2" align="center"><input type="submit" value="Get" style="height:35px; width:70px" /></td></tr>
			</table>
		</div>
		</td></tr></table>
	</form>
	
	<br />
	<table><tr><td><div id="order_profit" style="width:100%"></div></td></tr></table>
	<br />
	<table align="center" style="font-size:x-small">
	<tr bgcolor="#AAAAAA"><th>Category</th><th>Cost</th><th>Income</th><th>Profit</th><th>Profit %</th></tr>
	<?php
		for($i=0;$i<sizeof($category);$i++){
			if($s_price[$i]>0)	$profitP=round((($iprofit[$i]/$s_price[$i])*100),1); else $profitP='';
			if($i%2 ==0) $color1='#F5F5F5'; else $color1='#E1E1E1';
			print '<tr bgcolor="'.$color1.'"><td class="tb2">'.$category[$i].'</td><td class="tb2" align="right">'.number_format($c_price[$i]).'</td><td class="tb2" align="right">'.number_format($s_price[$i]).'</td><td class="tb2" align="right">'.number_format($iprofit[$i]).'</td><td class="tb2" align="right">'.$profitP.' %</td></tr>';
		}
	?>
	</table>
  </div>
</div>
</div>
<hr>


<?php
                include_once  'template/m_footer.php';
?>