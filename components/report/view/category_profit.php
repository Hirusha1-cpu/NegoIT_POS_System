<?php
                include_once  'template/header.php';
?>
	<link rel="stylesheet" href="plugin/jquery/css/demos.css" />
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
      
		function nextTagAction(){
			document.getElementById("cat_form1").submit();
		}
	</script>

<!-- ------------------Item List----------------------- -->

<form id="cat_form1" action="index.php" method="get" onsubmit="return validateDateRange()" >
	<input type="hidden" name="components" value="report" />
	<input type="hidden" name="action" value="category_profit" />
	<table align="center"><tr><td>
	<div style="border-radius:10px; background-color:#DDDDDD;padding-left :20px; padding-right:20px;">
		<table align="center" style="font-family:Calibri; font-size:12pt" border="0">
		<tr>
		<td><strong>Sub System:</strong> 
			<select name="subsys">
				<option value="all" >-ALL-</option>
				<?php
					for($i=0;$i<sizeof($sub_system_list);$i++){
						if($subsys==$sub_system_list[$i]) $select='selected="selected"'; else $select='';
						print '<option value="'.$sub_system_list[$i].'" '.$select.'>'.$sub_system_names[$i].'</option>';
					}
				?>
			</select>
		</td><td width="30px"></td>
		<td><strong>From Date:</strong> <input type="date" name="from_date" id="from_date" value="<?php print $from_date; ?>" style="width:130px" /></td><td width="30px"></td>
		<td><strong>To Date:</strong> <input type="date" name="to_date" id="to_date" value="<?php print $to_date; ?>" style="width:130px" /></td>
		<td><input type="submit" value="Get" style="height:60px; width:70px" /></td></tr>
		<tr><td colspan="6">
			<?php  include_once  'template/tag.php'; ?>
		</td></tr>
		</table>
	</div>
	</td></tr></table>
</form>
<br />

<table align="center"><tr><td><div id="order_profit" style="height:300px; width:600px"></div></td></tr></table>
<br />
<div id="print">
<table align="center" style="font-size:10pt">
<tr bgcolor="#AAAAAA"><th>Category</th><th>Cost</th><th>Income</th><th><?php if($systemid==1) print 'Cash'; else 'Profit'; ?></th><th><?php if($systemid==1) print 'Cash'; else 'Profit'; ?>%</th></tr>
<?php
	for($i=0;$i<sizeof($category);$i++){
		if($s_price[$i]>0)	$profitP=round((($iprofit[$i]/$s_price[$i])*100),1); else $profitP='';
		if($i%2 ==0) $color1='#EEEEEE'; else $color1='#DDDDDD';
		print '<tr bgcolor="'.$color1.'"><td class="shipmentTB4">'.$category[$i].'</td><td class="shipmentTB4" align="right">'.number_format($c_price[$i]).'</td><td class="shipmentTB4" align="right">'.number_format($s_price[$i]).'</td><td class="shipmentTB4" align="right">'.number_format($iprofit[$i]).'</td><td class="shipmentTB4" align="right">'.$profitP.' %</td></tr>';
	}
?>
</table>
</div>	

<br />
<?php
                include_once  'template/footer.php';
?>