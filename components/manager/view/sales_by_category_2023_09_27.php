<?php
                include_once  'template/header.php';
                
                $total_sale=array_sum($cat_sale);
?>
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
<form action="index.php?components=<?php print $_GET['components']; ?>&action=sales_bycategory" method="post" onsubmit="return validateDateRange()" >
	<table align="center" height="100%" cellspacing="0" style="font-size:10pt; font-family:Calibri">
	<tr>
	<td width="100px" align="right"><strong>From Date : </strong></td><td>
	<input type="date" id="datefrom" name="datefrom" style="width:130px" value="<?php print $fromdate; ?>" />
	</td><td width="100px" align="right"><strong>To Date : </strong></td><td>
	<input type="date" id="dateto" name="dateto" style="width:130px" value="<?php print $todate; ?>" />
	<input type="submit" value="GET" />
	</td></tr>
	</table>
</form>
<div id="printheader" style="display:none" >
	<h1 style="color:navy"><?php print $inf_company; ?></h1>
	<h2 align="center" style="color:#3333FF; text-decoration:underline">Sales By Category</h2>
	<table style="font-size:12pt" border="1" cellspacing="0" >
		<tr><td style="background-color:#C0C0C0; padding-left:10px">From</td><td style="background-color:#EEEEEE; padding-left:10px"><?php print $fromdate; ?></td></tr>
		<tr><td style="background-color:#C0C0C0; padding-left:10px">To</td><td style="background-color:#EEEEEE; padding-left:10px"><?php print $todate; ?></td></tr>
	</table><br />
</div>
<?php if(sizeof($cat_name)>0) print '<table align="center" width="600px"><tr><td><div id="graph1" style="width: 100%; height: 350px;"></div></td></tr></table>'; ?>

<br /><br />
<div id="print" style="display:none">
	<table align="center" style="font-size:12pt; font-family:Calibri" cellspacing="0" border="1">
	<tr><th>Category</th><th width="100px">Sale</th></tr>
	<?php for($i=0;$i<sizeof($cat_name);$i++){
		print '<tr><td>&nbsp;&nbsp;&nbsp;'.$cat_name[$i].'&nbsp;&nbsp;&nbsp;</td><td align="right">&nbsp;&nbsp;&nbsp;'.number_format($cat_sale[$i]).'&nbsp;&nbsp;&nbsp;</td></tr>';
	} ?>
	</table>
</div>

	<table align="center" style="font-size:12pt; font-family:Calibri">
	<tr bgcolor="#467898" style="color:white"><th>Category</th><th width="100px">Sale</th></tr>
	<?php for($i=0;$i<sizeof($cat_name);$i++){
		if(($i%2)==0) $color='#FAFAFA'; else $color='#EEEEEE';
		print '<tr style="background-color:'.$color.'"><td class="shipmentTB3">'.$cat_name[$i].'</td><td class="shipmentTB3" align="right">'.number_format($cat_sale[$i]).'</td></tr>';
	} 
		print '<tr style="background-color:#DDDDDD"><td class="shipmentTB3"><strong>Total</strong></td><td class="shipmentTB3" align="right"><strong>'.number_format($total_sale).'</strong></td></tr>';
	?>
	</table>

	<br />
	
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