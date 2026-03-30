<?php
                include_once  'template/m_header.php';
?>
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart1);
      function drawChart1() {

        var data = google.visualization.arrayToDataTable([
          ['Item', 'Qty'],
          <?php for($i=0;$i<sizeof($graph1_item);$i++){
          	print "['$graph1_item[$i]',$graph1_qty[$i] ],";
          } ?>
        ]);

        var options = {
          title: 'Most Disposal Items',
          pieHole: 0.4,
        };
        var chart = new google.visualization.PieChart(document.getElementById('most_disposal_items'));

        chart.draw(data, options);
      }
      //-------------------------------------------------//
      google.charts.setOnLoadCallback(drawChart2);
      function drawChart2() {

        var data = google.visualization.arrayToDataTable([
          ['Item', 'Qty'],
          <?php for($i=0;$i<sizeof($graph2_item);$i++){
          	print "['$graph2_item[$i]',$graph2_qty[$i] ],";
          } ?>
        ]);

        var options = {
          title: 'Most Returned Items',
          pieHole: 0.4,
        };
        var chart = new google.visualization.PieChart(document.getElementById('most_return_items'));

        chart.draw(data, options);
      }
      //-------------------------------------------------//
      google.charts.setOnLoadCallback(drawChart3);
      function drawChart3() {

        var data = google.visualization.arrayToDataTable([
          ['Customer', 'Qty'],
          <?php for($i=0;$i<sizeof($graph3_cust);$i++){
          	print "['$graph3_cust[$i]',$graph3_qty[$i] ],";
          } ?>
        ]);

        var options = {
          title: 'Most Returned Customer',
        };
        var chart = new google.visualization.PieChart(document.getElementById('most_return_cust'));

        chart.draw(data, options);
      }
      //-------------------------------------------------//
      google.charts.setOnLoadCallback(drawChart4);
      function drawChart4() {

        var data = google.visualization.arrayToDataTable([
          ['Salesman', 'Qty'],
          <?php for($i=0;$i<sizeof($graph4_salesman);$i++){
          	print "['".ucfirst($graph4_salesman[$i])."',$graph4_qty[$i] ],";
          } ?>
        ]);

        var options = {
          title: 'Returned By Salesmans',
        };
        var chart = new google.visualization.PieChart(document.getElementById('most_return_salesman'));

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

<hr>
<div class="w3-row">
  <div class="w3-col s3">
  </div>
  <div class="w3-col">

	<form action="index.php" method="get" onsubmit="return validateDateRange()" >
<input type="hidden" name="components" value="report" />
<input type="hidden" name="action" value="return_items" />
	<table align="center" style="font-family:Calibri; font-size:small" border="0">
	<tr><td style="background-color:#DDDDDD">From Date</td><td><input type="date" name="from_date" id="from_date" value="<?php print $from_date; ?>" style="width:120px" /></td><td width="20px"></td>
	<td rowspan="3"><input type="submit" value="Get" style="height:60px; width:70px" /></td></tr>
	<tr><td style="background-color:#DDDDDD">To Date</td><td><input type="date" name="to_date" id="to_date" value="<?php print $to_date; ?>" style="width:120px" /></td><td></td></tr>
	<tr><td style="background-color:#DDDDDD">Salesman</td><td>
	<select name="sm" id="sm">
	<option value="">-ALL-</option>
	<?php for($i=0;$i<sizeof($salesman_idlist);$i++){
		if($_GET['sm']==$salesman_idlist[$i]) $select='selected="selected"'; else $select='';
		print '<option value="'.$salesman_idlist[$i].'" '.$select.'>'.ucfirst($salesman_list[$i]).'</option>';
	} ?>
	</select>
	</td><td></td></tr>
	</table>
	
	<table align="center">
	<tr><td><div id="most_disposal_items" style="width: 500px; height: 300px;"></div><br><div id="most_return_items" style="width: 500px; height: 300px;"></div></td><td><div id="landscape" style="vertical-align:top" ></div></td></tr>
	<tr><td colspan="2"><div id="portrait"><div id="most_return_cust" style="width: 500px; height: 300px;"></div><br><div id="most_return_salesman" style="width: 500px; height: 300px;"></div></div></td></tr>
	</table>
	</form>
  </div>
</div>

<div class="w3-row">
	<div class="w3-col s3">
	</div>
	 <div class="w3-col" style="vertical-align:top">
		<table align="center" bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0"  style="font-size:small" >
		<tr style="font-weight:bold; background-color:#0066AA; color:white"><th>Disposal ID</th><th>Processed Date</th><th>Item</th><th>Return<br>Invoice | Qty</th><th>Disposal<br>Qty</th><th>Store</th></tr>
		<?php for($i=0;$i<sizeof($disp_id);$i++){
				print '<tr><td align="right" style="padding-left:10px; padding-right:10px">'.$disp_id[$i].'</td><td style="padding-left:10px; padding-right:10px">'.$disp_date[$i].'</td><td style="padding-left:10px; padding-right:10px">'.$disp_description[$i].'</td><td align="center"><table>';
				for($j=0;$j<sizeof($drtn_inv[$i]);$j++){
					print '<tr><td><a href="index.php?components=billing&action=finish_return&id='.$drtn_inv[$i][$j].'">'.str_pad($drtn_inv[$i][$j], 7, "0", STR_PAD_LEFT).'</a></td><td><input type="text" value="'.$drtn_qty[$i][$j].'" style="width:30px; padding-right:5px; text-align:right" disabled="disabled" /></td></tr>';
				}
				print '</table></td><td style="padding-left:10px; padding-right:10px" align="right">'.$disp_qty[$i].'</td><td style="padding-left:10px; padding-right:10px">'.$disp_store[$i].'</td></tr>';
		} ?>
		</table>
		<hr>
		<table align="center" bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0"  style="font-size:10pt" >
		<tr style="font-weight:bold; background-color:#0066AA; color:white"><th>Return Invoice</th><th>Return Date</th><th>Customer</th><th>Salesman</th><th>Status</th><th>Store</th></tr>
		<?php for($i=0;$i<sizeof($rtn_inv);$i++){
				if((strlen($rtn_cust[$i]))>20) $cust=substr($rtn_cust[$i],0,19).'...'; else $cust=$rtn_cust[$i];
				if($rtn_st[$i]=='Processed') $color='green'; else $color='';
				print '<tr style="color:'.$color.'"><td align="center"><a href="index.php?components=report&action=return_one&id='.$rtn_inv[$i].'&from_date='.$from_date.'&to_date='.$to_date.'">'.str_pad($rtn_inv[$i], 7, "0", STR_PAD_LEFT).'</a></td><td align="center">'.$rtn_date[$i].'</td><td style="padding-left:10px; padding-right:10px"><a href="" style="text-decoration:none" title="'.$rtn_cust[$i].'">'.$cust.'</a></td><td>&nbsp;&nbsp;'.ucfirst($rtn_by[$i]).'</td><td>&nbsp;&nbsp;'.$rtn_st[$i].'&nbsp;&nbsp;</td><td>&nbsp;&nbsp;'.$rtn_store[$i].'&nbsp;&nbsp;</td></tr>';
		} ?>
		</table>
	 </div>
</div>
</div>
<hr>


<?php
                include_once  'template/m_footer.php';
?>