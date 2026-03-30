<?php
                include_once  'template/header.php';
?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawVisualization);


      function drawVisualization() {
        // Some raw data (not necessarily accurate)
        var data = google.visualization.arrayToDataTable([['Item', 'In-Stock', 'Sold'],
          <?php for($i=0;$i<sizeof($itm_id);$i++){ 
           print "['$itm_desc[$i]', $inv_all_qty[$i], $sold_all_qty[$i]],";
        } ?>
      ]);

    var options = {
      vAxis: {title: 'Quantity'},
      hAxis: { 
        slantedText: true, 
        slantedTextAngle: 45 // here you can even use 180 
   	 } ,
      seriesType: 'bars',
      series: {5: {type: 'line'}}
    };

    var chart = new google.visualization.ColumnChart(document.getElementById('columnchart_material'));
    chart.draw(data, options);
  }
  </script>
<!-- ------------------------------------------------------------------------------------------------------ -->
<div id="loading" style="display:none"><img src="images/loading.gif" style="width:25px" /></div>

<table align="center" style="font-size:12pt"><tr><td>
<?php 
	if(isset($_REQUEST['message'])){
		if($_REQUEST['re']=='success') $color='green'; else $color='red';
	print '<span style="color:'.$color.'; font-weight:bold;">'.$_REQUEST['message'].'</span>'; 
	}
?></td></tr></table>
<form action="index.php?components=portalsup&action=sales_report" method="post">
	<table align="center" border="0" cellspacing="0" style="font-size:12pt; font-family:Calibri; border-radius: 15px; padding-left:20px; padding-right:20px; background-color:#F0F0F0">
	<tr ><td>From Date</td><td><input type="date" id="from_date" name="from_date" <?php print 'value="'.$from_date.'"'; ?> /></td><td></td>
	<td>To Date</td><td><input type="date" id="to_date" name="to_date" <?php print 'value="'.$to_date.'"'; ?> /></td><td rowspan="2"><input type="submit" value="Get" style="width:60px; height:50px; font-weight:bold; font-size:14pt" /></td>
	</tr>
	</table>
</form>

	<br><br>
	<table align="center"><tr><td><div id="columnchart_material" style="width: 800px; height: 500px;"></div></td></tr></table>
	<br />
	<table align="center" style="font-size:12pt; font-family:Calibri;">
	<tr style="background-color:#467898; color:white;"><th>Item</th><th class="shipmentTB3">All Store<br>Inventory Qty</th><th class="shipmentTB3">All Store<br>Sold Qty</th></tr>
	<?php 
	for($i=0;$i<sizeof($itm_id);$i++){
		if(($i%2)==0) $color='#FAFAFA'; else $color='#EEEEEE';
		print '<tr style="background-color:'.$color.'"><td>&nbsp;&nbsp;'.$itm_desc[$i].'&nbsp;&nbsp;</td><td align="right" title="';
		for($j=0;$j<sizeof($store_id2);$j++){
			print $store_name2[$j].' - '.$inv_store2_qty[$i][$store_id2[$j]].'&#xA;';
		}
		print '">'.$inv_all_qty[$i].'&nbsp;&nbsp;</td><td align="right">&nbsp;&nbsp;'.$sold_all_qty[$i].'&nbsp;&nbsp;</td>';
		print '</tr>';
	}?>
	</table>
	

<?php
                include_once  'template/footer.php';
?>