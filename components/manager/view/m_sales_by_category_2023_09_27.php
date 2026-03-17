<?php
                include_once  'template/m_header.php';
                
                $total_sale=array_sum($cat_sale);
?>
<!-- ------------------------------------------------------------------------------------ -->

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart1);
      function drawChart1() {

        var data = google.visualization.arrayToDataTable([
          ['Category', 'Sales Rate'],
          <?php for($i=0;$i<sizeof($cat_name);$i++){
          	$rate=round(($cat_sale[$i]/$total_sale)*100,2);
          	if($rate<0) $rate=0;
          	print "['$cat_name[$i]',$rate ],";
          } ?>
        ]);

        var options = {
          title: 'Sales By Category',
          pieHole: 0.4,
        };
        var chart = new google.visualization.PieChart(document.getElementById('graph1'));

        chart.draw(data, options);
      }
	</script>
<!-- ------------------Item List----------------------- -->
<div class="w3-container" style="margin-top:75px">
<hr>
<div class="w3-row">
  <div class="w3-col s3">
  </div>
  <div class="w3-col">

	<form action="index.php?components=<?php print $_GET['components']; ?>&action=sales_bycategory" method="post" onsubmit="return validateDateRange()" >
		<table align="center" height="100%" style="font-size:10pt">
		<tr bgcolor="#EEEEEE"><td width="100px" ><strong>From Date </strong></td><td >
		<input type="date" id="datefrom" name="datefrom" style="width:130px" value="<?php print $fromdate; ?>" />
		</td><td align="right">&nbsp;&nbsp;&nbsp;&nbsp;<strong>To Date </strong></td><td>
		<input type="date" id="dateto" name="dateto" style="width:130px" value="<?php print $todate; ?>" />
		<input type="submit" value="GET" style="width:50px; height:40px" />
		</td></tr>
		</table>
	</form>
	
	<br />
<?php if(sizeof($cat_name)>0) print '<table width="600px"><tr><td><div id="graph1" style="width: 100%; height: 350px;"></div></td></tr></table>'; ?>

	<table align="center" style="font-size:12pt; font-family:Calibri">
	<tr bgcolor="#467898" style="color:white"><th>Category</th><th width="100px">Sale</th></tr>
	<?php for($i=0;$i<sizeof($cat_name);$i++){
		if(($i%2)==0) $color='#FAFAFA'; else $color='#EEEEEE';
		print '<tr style="background-color:'.$color.'"><td class="shipmentTB3">'.$cat_name[$i].'</td><td class="shipmentTB3" align="right">'.number_format($cat_sale[$i]).'</td></tr>';
	} 
		print '<tr style="background-color:#DDDDDD"><td class="shipmentTB3"><strong>Total</strong></td><td class="shipmentTB3" align="right"><strong>'.number_format($total_sale).'</strong></td></tr>';
	?>
	</table>


  </div>
</div>
</div>
<hr>
<br />
<?php
                include_once  'template/m_footer.php';
?>
