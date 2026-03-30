<?php
                include_once  'template/m_header.php';
?>
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Month', 'Sale', 'Credit'],
        <?php for($i=0;$i<sizeof($tr1_month);$i++){
          print "['$tr1_month[$i]',  $tr1_sale[$i], $tr1_credit[$i]],";
          } ?>
        ]);

        var options = {
          title: 'Company Credit',
          curveType: 'function',
        };
        var chart = new google.visualization.LineChart(document.getElementById('company_credit'));

        chart.draw(data, options);
      }
//-----------------------------------------------------------------------//
      google.charts.setOnLoadCallback(drawChart2);
      function drawChart2() {
        var data = google.visualization.arrayToDataTable([
        ['Month', 
        <?php for($i=0;$i<sizeof($st_name);$i++){
        	if(($store=='all')||($store==$st_id[$i])){
	        	print "'$st_name[$i]',";
        	}
        } 
        print "],";
        
        for($i=0;$i<sizeof($tr1_month);$i++){
        $tmp_monthname=$tr1_month[$i];
		print "['$tmp_monthname',";
		if($store=='all'){
			for($j=0;$j<sizeof($st_id);$j++){
				$tmp_stid=$st_id[$j];
				$tmp_sale=$tr2_store_credit[$tmp_monthname][$tmp_stid];
				print "$tmp_sale,";
			}
		}else{
				$tmp_sale=$tr2_store_credit[$tmp_monthname][$store];
				print "$tmp_sale,";		
		}
		print "],";
        }
        ?>
        ]);;

        var options = {
          title: 'Store Credit',
          curveType: 'function',
          <?php if($store!='all' || sizeof($st_id)==1) print 'colors: [\'#e2431e\']'; ?>
        };
        var chart = new google.visualization.LineChart(document.getElementById('store_credit'));

        chart.draw(data, options);
      }
         
      function setFilter(){
      	var from_date=document.getElementById('from_date').value;
      	var to_date=document.getElementById('to_date').value;
      	var sys_filter=document.getElementById('sys_filter').value;
      	var group_filter=document.getElementById('group_filter').value;
      	var store_filter=document.getElementById('store_filter').value;
      	window.location = 'index.php?components=<?php print $components; ?>&action=credit_trend&sys='+sys_filter+'&group='+group_filter+'&store='+store_filter+'&from_date='+from_date+'&to_date='+to_date;
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
	<form action="index.php?components=<?php print $components; ?>&action=credit_trend" method="post">
	<div style="border-radius:10px; background-color:EEEEEE;">
	<table align="center" style="font-family:Calibri; font-size:12pt" bgcolor="#EEEEEE">
	<tr><td>From Date</td><td>: <input type="date" name="from_date" value="<?php print $from; ?>" style="width:120px" /></td><td width="80px"></td>
	<td>To Date </td><td>: <input type="date" name="to_date" value="<?php print $to; ?>" style="width:120px" /></td>
	<td rowspan="2"><input type="submit" value="Get" style="height:60px; width:70px" /></td></tr>
	<tr><td>System</td><td>: <select id="sys_filter" name="sys" onchange="setFilter()" >
		<option value="all">-ALL Systems-</option>
		<?php 
		for($i=0;$i<sizeof($sub_system_list);$i++){
			if($sys==$sub_system_list[$i]) $select='selected="selected"'; else $select='';
			print '<option value="'.$sub_system_list[$i].'" '.$select.'>'.$sub_system_names[$i].'</option>';
		} ?>
	</select></td><td width="80px"></td>
	<td>Group</td><td>: <select id="group_filter" name="group" onchange="setFilter()" >
		<option value="all">-ALL-</option>
		<?php 
		for($i=0;$i<sizeof($gp_id);$i++){
			if($group==$gp_id[$i]) $select='selected="selected"'; else $select='';
			print '<option value="'.$gp_id[$i].'" '.$select.'>'.$gp_name[$i].'</option>';
		} ?>
	</select></td>
	<td>Store</td><td>: <select id="store_filter" name="store" onchange="setFilter()" >
		<option value="all">-ALL-</option>
		<?php 
		for($i=0;$i<sizeof($st_id);$i++){
			if($store==$st_id[$i]) $select='selected="selected"'; else $select='';
			print '<option value="'.$st_id[$i].'" '.$select.'>'.$st_name[$i].'</option>';
		} ?>
	</select></td>
	</tr>
	</table>
	</div>
	
	<table align="center">
	<tr><td><div id="company_credit" style="width: 500px; height: 300px;"></div></td></tr>
	<tr><td><div id="store_credit" style="width: 500px; height: 300px;"></div></td></tr>
	</table>
	</form>

  </div>
</div>
</div>
<hr>


<?php
                include_once  'template/m_footer.php';
?>