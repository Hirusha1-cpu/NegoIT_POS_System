<?php
                include_once  'template/m_header.php';
?>
<!-- ------------------------------------------------------------------------------------ -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(salesvsstock);
      function salesvsstock() {
        // Some raw data (not necessarily accurate)
        var data = google.visualization.arrayToDataTable([['Item', 'In-Stock', 'Sold', 'Returned'],
        <?php for($i=0;$i<sizeof($itm_id);$i++){ 
           print "['$itm_desc[$i]', $inv_all_qty[$i], $sold_all_qty[$i], $rtn_all_qty[$i]],";
        } ?>
      ]);

    var options = {
      title: 'Stock Vs Sales Vs Returned',
      vAxis: {title: 'Quantity'},
      hAxis: { 
        slantedText: true, 
        slantedTextAngle: 45 // here you can even use 180 
   	 } ,
      seriesType: 'bars',
      series: {5: {type: 'line'}}
    };

    var chart = new google.visualization.ColumnChart(document.getElementById('sales_vs_stock'));
    chart.draw(data, options);
  }
  
	//--------------------------------------------------------------------------------------------------------//
      google.charts.setOnLoadCallback(stockChart);
      function stockChart() {

        var data = google.visualization.arrayToDataTable([
          ['Item', 'Quantity'],
        <?php for($i=0;$i<sizeof($itm_id);$i++){ 
           print "['$itm_desc[$i]', $inv_all_qty[$i]],";
        } ?>
        ]);

        var options = {
          title: 'Stock Availability'
        };

        var chart = new google.visualization.PieChart(document.getElementById('stock_pie_chart'));

        chart.draw(data, options);
      }  
	//--------------------------------------------------------------------------------------------------------//
      google.charts.setOnLoadCallback(returnitems);
      function returnitems() {
        // Some raw data (not necessarily accurate)
        var data = google.visualization.arrayToDataTable([['Item', 'Returned', { role: 'style' } ],
        <?php for($i=0;$i<sizeof($rtn_itm_id);$i++){ 
           print "['$rtn_itm_desc[$i]', $rtn_itm_qty[$i], 'color: orange'],";
        } ?>
      ]);

    var options = {
      title: 'Return Items',
      vAxis: {title: 'Quantity'},
      hAxis: { 
        slantedText: true, 
        slantedTextAngle: 45 // here you can even use 180 
   	 } ,
   	 legend: { position: "none" },
      seriesType: 'bars',
      series: {5: {type: 'line'}}
    };

    var chart = new google.visualization.ColumnChart(document.getElementById('return_items'));
    chart.draw(data, options);
  }
  //--------------------------------------------------------------------------------------------------------//
      google.charts.setOnLoadCallback(mostSelling);
      function mostSelling() {

        var data = google.visualization.arrayToDataTable([
          ['Item', 'Quantity'],
        <?php for($i=0;$i<sizeof($itm_id);$i++){ 
           if($sold_all_qty[$i]>0){
           	print "['$itm_desc[$i]', $sold_all_qty[$i]],";
           }
        } ?>
        ]);

        var options = {
          title: 'Most Selling Items'
        };

        var chart = new google.visualization.PieChart(document.getElementById('most_selling'));

        chart.draw(data, options);
      } 
     
      
      </script>

<!-- ------------------Item List----------------------- -->
<div class="w3-container" style="margin-top:75px">
<div class="w3-row">
  <div class="w3-col s3">
  </div>
  <div class="w3-col">
<form action="index.php?components=portalsup&action=dashboard" method="post">
	<table align="center" border="0" width="90%" cellspacing="0" style="font-size:10pt; font-family:Calibri; border-radius: 15px; padding-left:20px; padding-right:20px; background-color:#F0F0F0">
	<tr ><td>From Date</td><td><input type="date" id="from_date" name="from_date" <?php print 'value="'.$from_date.'"'; ?> /></td><td rowspan="2"><input type="submit" value="Get" style="width:45px; height:50px; font-weight:bold; font-size:12pt" /></td></tr>
	<tr><td>To Date</td><td><input type="date" id="to_date" name="to_date" <?php print 'value="'.$to_date.'"'; ?> /></td></tr>
	</table>
</form>
<hr />

<div style="border-radius: 15px; padding-left:20px; padding-right:20px; padding-top:20px; padding-bottom:20px; background-color:#F0F0F0">
	<table align="center" width="100%">
		<tr><td width="50%"><div id="sales_vs_stock" style="width: 100%; height: 500px;"></div></td></tr>
		<tr><td width="50%"><div id="stock_pie_chart" style="width: 100%; height: 500px;"></div></td></tr>
		<tr><td width="50%"><div id="most_selling" style="width: 100%; height: 500px;"></div></td></tr>
		<tr><td width="50%"><div id="return_items" style="width: 100%; height: 500px;"></div></td></tr>
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
