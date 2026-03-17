<?php
                include_once  'template/m_header.php';
?>
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Month', 'Sale', 'Profit'],
        <?php for($i=0;$i<sizeof($tr1_month);$i++){
          print "['$tr1_month[$i]',  $tr1_sale[$i], $tr1_profit[$i]],";
          } ?>
        ]);

        var options = {
          title: 'Company Performance',
          curveType: 'function',
        };
        var chart = new google.visualization.LineChart(document.getElementById('company_perfomance'));

        chart.draw(data, options);
      }
//-----------------------------------------------------------------------//
      google.charts.setOnLoadCallback(drawChart2);
      function drawChart2() {
        var data = google.visualization.arrayToDataTable([
        ['Month', 
        <?php for($i=0;$i<sizeof($st_name);$i++){
        	print "'$st_name[$i]',";
        } 
        print "],";
        
        for($i=0;$i<sizeof($tr1_month);$i++){
        $tmp_monthname=$tr1_month[$i];
		print "['$tmp_monthname',";
		
			for($j=0;$j<sizeof($st_id);$j++){
			$tmp_stid=$st_id[$j];
			$tmp_sale=$tr2_store_sale[$tmp_monthname][$tmp_stid];
			print "$tmp_sale,";
			}
		print "],";
        }
        ?>
        ]);;

        var options = {
          title: 'Store Performance',
          curveType: 'function',
        };
        var chart = new google.visualization.LineChart(document.getElementById('store_perfomance'));

        chart.draw(data, options);
      }
//-----------------------------------------------------------------------//
      google.charts.setOnLoadCallback(drawChart3);
      function drawChart3() {

        var data = google.visualization.arrayToDataTable([
          ['Category', 'Sale'],
          <?php for($i=0;$i<sizeof($tr4_category);$i++){
          	print "['$tr4_category[$i]',$tr4_sale[$i] ],";
          } ?>
        ]);

        var options = {
          title: 'Highest Income Category',
          pieHole: 0.4,
        };
        var chart = new google.visualization.PieChart(document.getElementById('sales_category'));

        chart.draw(data, options);
      }
      //-----------------------------------------------------------------------//
      google.charts.setOnLoadCallback(drawChart4);
      function drawChart4() {

        var data = google.visualization.arrayToDataTable([
          ['Item', 'Sale'],
          <?php for($i=0;$i<sizeof($tr3_item);$i++){
          	print "['$tr3_item[$i]',$tr3_sale[$i] ],";
          } ?>
        ]);

        var options = {
          title: 'Highest Income Item'
        };

        var chart = new google.visualization.PieChart(document.getElementById('sales_item'));

        chart.draw(data, options);
      }    
      
//-----------------------------------------------------------------------//
      google.charts.setOnLoadCallback(drawChart5);
      function drawChart5() {

        var data = google.visualization.arrayToDataTable([
          ['Category', 'Sale'],
          <?php for($i=0;$i<sizeof($tr5_item);$i++){
          	print "['$tr5_item[$i]',$tr5_sale[$i] ],";
          } ?>
        ]);

        var options = {
          title: 'Most Selling Item'
        };
        var chart = new google.visualization.PieChart(document.getElementById('sales2_item'));

        chart.draw(data, options);
      }
      //-----------------------------------------------------------------------//
      google.charts.setOnLoadCallback(drawChart6);
      function drawChart6() {

        var data = google.visualization.arrayToDataTable([
          ['Item', 'Sale'],
          <?php for($i=0;$i<sizeof($tr6_category);$i++){
          	print "['$tr6_category[$i]',$tr6_sale[$i] ],";
          } ?>
        ]);

        var options = {
          title: 'Most Selling Category',
          pieHole: 0.4,
        };

        var chart = new google.visualization.PieChart(document.getElementById('sales2_category'));

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
	<form action="index.php?components=report&action=sales_trend" method="post">
	<div style="border-radius:10px; background-color:EEEEEE;">
	<table align="center" style="font-family:Calibri; font-size:12pt" bgcolor="#EEEEEE">
	<tr><td>From Date</td><td>: <input type="date" name="from_date" value="<?php print $from; ?>" style="width:120px" /></td><td width="80px"></td>
	<td>To Date : <input type="date" name="to_date" value="<?php print $to; ?>" style="width:120px" /></td>
	<td rowspan="2"><input type="submit" value="Get" style="height:60px; width:70px" /></td></tr>
	<tr><td>System</td><td colspan="3">: <select id="sys_filter" name="sys" onchange="setSysFilter()" >
		<option value="all">-ALL Systems-</option>
		<?php 
		for($i=0;$i<sizeof($sub_system_list);$i++){
			if($sys==$sub_system_list[$i]) $select='selected="selected"'; else $select='';
			print '<option value="'.$sub_system_list[$i].'" '.$select.'>'.$sub_system_names[$i].'</option>';
		} ?>
	</select></td></tr>
	</table>
	</div>
	
	<table align="center">
	<tr><td><div id="company_perfomance" style="width: 500px; height: 300px;"></div><br><div id="store_perfomance" style="width: 500px; height: 300px;"></div><br><div id="sales2_category" style="width: 500px; height: 300px;"></div></td><td><div id="landscape" style="vertical-align:top" ></div></td></tr>
	<tr><td colspan="2"><div id="portrait"><div id="sales_category" style="width: 450px; height: 300px;"></div><br><div id="sales_item" style="width: 450px; height: 300px;"></div><br><div id="sales2_item" style="width: 500px; height: 300px;"></div></div></td></tr>
	</table>
	</form>

  </div>
</div>
</div>
<hr>


<?php
                include_once  'template/m_footer.php';
?>