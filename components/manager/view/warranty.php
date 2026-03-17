<?php
                include_once  'template/header.php';
                $components=$_GET['components'];
?>

  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart1);
      function drawChart1() {

        var data = google.visualization.arrayToDataTable([
          ['Status', 'Count'],
          <?php for($i=0;$i<sizeof($war_st_type);$i++){
          	print "['$war_st_type[$i]',$inv_st_count[$i] ],";
          } ?>
        ]);

        var options = {
          pieHole: 0.4,
        };
        var chart = new google.visualization.PieChart(document.getElementById('warranty_graph'));

        chart.draw(data, options);
      }
      </script>


<!-- ------------------Item List----------------------- -->
		<form action="index.php" method="get" id="sold_form">
		<input type="hidden" name="components" value="<?php print $components; ?>" />
		<input type="hidden" name="action" value="warranty" />
		<table align="center" cellspacing="0" style="font-family:Calibri" bgcolor="#F0F0F0">
			<tr><td colspan="11" ><br /></td></tr>
			<tr><td width="50px"></td><td>From Date</td><td><input type="date" id="from_date" name="from_date" value="<?php print $from_date; ?>" /></td>
			<td width="50px"></td><td>To Date</td><td><input type="date" id="to_date" name="to_date" value="<?php print $to_date; ?>" /></td>
			<td width="50px"></td><td>Store</td><td>
			<select id="store" name="store" onchange="window.location = 'index.php?components=<?php print $components; ?>&action=warranty&store='+document.getElementById('store').value+'&from_date='+document.getElementById('from_date').value+'&to_date='+document.getElementById('to_date').value">
				<option value="all" >--ALL--</option>
				<?php
				$stname='ALL Stores';
				 for($i=0;$i<sizeof($st_id);$i++){
				 	if($st_id[$i]==$store){ $select='selected="selected"'; $stname=ucfirst($st_name[$i]); }else{ $select=''; }
				 	print '<option value="'.$st_id[$i].'" '.$select.'>'.ucfirst($st_name[$i]).'</option>';
				 }
				?>
			</select>
			</td><td><a onclick="document.getElementById('sold_form').submit();" style="cursor:pointer; height:50px"><img src="images/search.png" style="width:30px; padding-top:-20px" /></a></td><td width="50px"></td></tr>
			<tr><td colspan="11" ><br /></td></tr>
		</table>
		</form>

<br />
<div align="center" id="warranty_graph"></div>
<br />

	<table align="center" style="font-family:Calibri">
	<tr style="background-color:#467898; color:white;"><td colspan="6" style="padding-left:10px">Completed Warranty Jobs</td></tr>
	<tr style="background-color:#467898; color:white;"><th class="shipmentTB3" width="50px">Clim ID</th><th class="shipmentTB3" width="70px">Claim Date</th><th class="shipmentTB3" width="200px">Claim Item</th><th class="shipmentTB3" width="50px">Handover</th><th class="shipmentTB3" width="200px">Customer</th><th class="shipmentTB3" width="200px">Suplier</th></tr>
	<?php
		for($i=0;$i<sizeof($wac_id);$i++){
			if(($i%2)==0) $color='#FAFAFA'; else $color='#EEEEEE';
			if($wac_ho_item[$i]!='') $ho='<span style="color:green" title="Handover Date: '.$wac_ho_date[$i].'&#13;Handover Item: '.$wac_ho_item[$i].'"><strong>Yes</strong></span>'; else $ho='<span style="color:red"><strong>No</strong></span>';
			print '<tr style="background-color:'.$color.'"><td class="shipmentTB3" align="center"><a href="index.php?components=billing&action=warranty_show&id='.$wac_id[$i].'">'.str_pad($wac_id[$i], 7, "0", STR_PAD_LEFT).'</a></td><td align="center">'.$wac_cl_date[$i].'</td><td class="shipmentTB3">'.$wac_cl_item[$i].'</td><td align="center">'.$ho.'</td><td class="shipmentTB3">'.$wac_cust[$i].'</td><td class="shipmentTB3">'.$wac_suplier[$i].'</td></tr>';
		}
	?>
	</table>
<br />
	<table align="center" style="font-family:Calibri">
	<tr style="background-color:#467898; color:white;"><td colspan="7" style="padding-left:10px">On-Going Warranty Jobs</td></tr>
	<tr style="background-color:#467898; color:white;"><th class="shipmentTB3" width="50px">Clim ID</th><th class="shipmentTB3" width="70px">Claim Date</th><th class="shipmentTB3" width="200px">Claim Item</th><th class="shipmentTB3" width="50px">Handover</th><th class="shipmentTB3" width="200px">Customer</th><th class="shipmentTB3" width="200px">Suplier</th><th class="shipmentTB3" width="100px" >Status</th></tr>
	<?php
		for($i=0;$i<sizeof($wa_id);$i++){
			if(($i%2)==0) $color='#FAFAFA'; else $color='#EEEEEE';
			if($wa_ho_item[$i]!='') $ho='<span style="color:green" title="Handover Date: '.$wa_ho_date[$i].'&#13;Handover Item: '.$wa_ho_item[$i].'"><strong>Yes</strong></span>'; else $ho='<span style="color:red"><strong>No</strong></span>';
			print '<tr style="background-color:'.$color.'"><td class="shipmentTB3" align="center"><a href="index.php?components=billing&action=warranty_show&id='.$wa_id[$i].'">'.str_pad($wa_id[$i], 7, "0", STR_PAD_LEFT).'</a></td><td align="center">'.$wa_cl_date[$i].'</td><td class="shipmentTB3">'.$wa_cl_item[$i].'</td><td align="center">'.$ho.'</td><td class="shipmentTB3">'.$wa_cust[$i].'</td><td class="shipmentTB3">'.$wa_suplier[$i].'</td><td class="shipmentTB3" style="color:'.$war_status_color[$i].'">'.$war_status_name[$i].'</td></tr>';
		}
	?>
	</table>
<?php
                include_once  'template/footer.php';
?>