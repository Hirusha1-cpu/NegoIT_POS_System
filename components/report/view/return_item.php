<?php
                include_once  'template/header.php';
?>
	<link rel="stylesheet" href="plugin/jquery/css/jquery.ui.all.css" />
	<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.core.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.widget.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.position.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.autocomplete2.js"></script>
	<link rel="stylesheet" href="plugin/jquery/css/demos.css" />

  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
	$(function() {
		var availableTags0 = [<?php for ($x=0;$x<sizeof($itm_description);$x++){ print '"'.$itm_description[$x].'",'; } ?>	];
		$( "#item" ).autocomplete({
			source: availableTags0
		});
		var availableTags1 = [<?php for ($x=0;$x<sizeof($cust_name);$x++){ print '"'.$cust_name[$x].'",'; } ?>	];
		$( "#cust" ).autocomplete({
			source: availableTags1
		});
	});
	
    
    
      //-------------------------------------------------//
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

<!-- ------------------Item List----------------------- -->

<div id="printheader" style="display:none" >
	<h1 style="color:navy"><?php print $inf_company; ?>.</h1>
	<h2 align="center" style="color:#3333FF; text-decoration:underline">Return Items</h2>
	<table style="font-size:12pt" border="1" cellspacing="0">
		<tr><td style="background-color:#C0C0C0; padding-left:10px; padding-right:10px" >Date</td><td style="background-color:#EEEEEE; padding-left:10px">&nbsp;&nbsp;<?php print date("Y-m-d",time()); ?>&nbsp;&nbsp;</td></tr>
	</table><br />
	<p>Note: <?php print $inf_company; ?> Return Items</p><hr>
</div>

<h2 align="center" style="color:#0158C2">List Of Return Items</h2>
<form action="index.php" method="get" onsubmit="return validateDateRange()" >
<input type="hidden" name="components" value="report" />
<input type="hidden" name="action" value="return_items" />
<div style="background-color:#EEEEEE; border-radius:10px">
	<table align="center" style="font-family:Calibri; font-size:12pt" border="0" bgcolor="#EEEEEE">
	<tr><td>From Date</td><td>: <input type="date" name="from_date" id="from_date" value="<?php print $from_date; ?>" style="width:130px" /></td><td width="80px"></td>
	<td>From Date</td><td>: <input type="date" name="to_date" id="to_date" value="<?php print $to_date; ?>" style="width:130px" /></td><td width="80px"></td>
	<td>Salesman</td><td>: <select name="sm" id="sm">
		<option value="">-ALL-</option>
		<?php for($i=0;$i<sizeof($salesman_idlist);$i++){
			if($_GET['sm']==$salesman_idlist[$i]) $select='selected="selected"'; else $select='';
			print '<option value="'.$salesman_idlist[$i].'" '.$select.'>'.ucfirst($salesman_list[$i]).'</option>';
		} ?>
	</select></td>
	<td rowspan="2"><input type="submit" value="Get" style="height:60px; width:70px" /></td></tr>
	<tr><td>Category</td><td>: 
		<select id="category" name="category" >
		<option value="" >-ALL-</option>
		<?php for($i=0;$i<sizeof($itc_id);$i++){
			if($category==$itc_id[$i]) $select='selected="selected"'; else $select='';
			print '<option value="'.$itc_id[$i].'" '.$select.'>'.ucfirst($itc_name[$i]).'</option>';
		} ?>
		</select>
	</td><td></td><td>Item</td><td>: <input type="text" id="item" name="item" value="<?php print $item; ?>" /></td><td></td><td>Customer</td><td>: <input type="text" id="cust" name="cust" value="<?php print $cust; ?>" /></td></tr>
	</table>
</div>
</form>
<br />

<div id="print">
<table align="center">
<tr><td><div id="most_disposal_items" style="width: 500px; height: 300px;"></div></td><td><div id="most_return_items" style="width: 500px; height: 300px;"></div></td></tr>
<tr><td><div id="most_return_cust" style="width: 500px; height: 300px;"></div></td><td><div id="most_return_salesman" style="width: 500px; height: 300px;"></div></td></tr>
</table>
<hr>
<table align="center" bgcolor="#EEEEEE" height="100%" border="1" cellspacing="0"  style="font-size:10pt" >
<tr style="font-weight:bold; background-color:#0066AA; color:white"><th>Disposal ID</th><th>Processed Date</th><th>Item</th><th>Return<br>Invoice | Qty</th><th>Disposal<br>Qty</th><th>Estimated<br>Cost</th><th>Store</th></tr>
<?php for($i=0;$i<sizeof($disp_id);$i++){
		print '<tr><td align="right" style="padding-left:10px; padding-right:10px">'.$disp_id[$i].'</td><td style="padding-left:10px; padding-right:10px">'.$disp_date[$i].'</td><td style="padding-left:10px; padding-right:10px">'.$disp_description[$i].'</td><td align="center"><table>';
//		if(isset($drtn_inv[$i])){
		for($j=0;$j<sizeof($drtn_inv[$i]);$j++){
			print '<tr><td><a href="index.php?components=billing&action=finish_return&id='.$drtn_inv[$i][$j].'">'.str_pad($drtn_inv[$i][$j], 7, "0", STR_PAD_LEFT).'</a></td><td><input type="text" value="'.$drtn_qty[$i][$j].'" style="width:30px; padding-right:5px; text-align:right" disabled="disabled" /></td></tr>';
		}
//		}
		print '</table></td><td style="padding-left:10px; padding-right:10px" align="right">'.$disp_qty[$i].'</td><td style="padding-left:10px; padding-right:10px" align="right">'.number_format($disp_cost[$i]).'</td><td style="padding-left:10px; padding-right:10px">'.$disp_store[$i].'</td></tr>';
	} 
	print '<tr style="font-weight:bold; background-color:#DDDDDD; padding-left:10px; padding-right:10px" align="right"><td colspan="4">Total</td><td>'.number_format(array_sum($disp_qty)).'</td><td>'.number_format(array_sum($disp_cost)).'</td><td></td></tr>';
?>
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
<table align="center"><tr><td align="center">
<div style="background-color:#6699FF; border:medium; border-color:black; width:80px;">
	<a class="shortcut-button" onclick="printdiv('print','printheader')" href="#"><span style="text-decoration:none; font-family:Arial; color:navy;">
	<img src="images/print.png" alt="icon" /><br />
	Print
	</span></a>
</div>

</td></tr></table>
<br />
<?php
                include_once  'template/footer.php';
?>