<?php
	include_once  'template/header.php';
	$total_sale=array_sum($cat_sale);
	$decimal = getDecimalPlaces(1);
	$components = $_GET['components'];
?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<link rel="stylesheet" href="plugin/jquery/css/jquery.ui.all.css" />
<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
<script src="plugin/jquery/ui/jquery.ui.core.js"></script>
<script src="plugin/jquery/ui/jquery.ui.widget.js"></script>
<script src="plugin/jquery/ui/jquery.ui.position.js"></script>
<script src="plugin/jquery/ui/jquery.ui.autocomplete.js"></script>
<link rel="stylesheet" href="plugin/jquery/css/demos.css" />

<script type="text/javascript">
	$(function() {
		var availableTags1 = [<?php for ($x=0;$x<sizeof($cu_name0);$x++){ print '"'.$cu_name0[$x].'",'; } ?>	];
		$( "#tags1" ).autocomplete({
			source: availableTags1
		});
	});

	function setCustID(){
		var id_arr = [<?php for ($x=0;$x<sizeof($cu_id0);$x++){ print '"'.$cu_id0[$x].'",'; } ?>	];
		var name_arr = [<?php for ($x=0;$x<sizeof($cu_name0);$x++){ print '"'.$cu_name0[$x].'",'; } ?>	];
		var name=document.getElementById('tags1').value;
		if(name!=''){
			var a=name_arr.indexOf(name);
			document.getElementById('customer_id').value=id_arr[a];
		}
		document.getElementById('search_form').submit();
	}
</script>

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

<form action="index.php?components=<?php print $_GET['components']; ?>&action=sales_bycategory" method="POST" onsubmit="return validateDateRange();" id="search_form">
	<input type="hidden" id="customer_id" name="customer_id" value="" />
	<table align="center">
		<tr>
            <td>
                <div style="background-color:#DFDFDF; border-radius:10px; font-family:Calibri">
					<table align="center" height="100%" cellspacing="0" style="font-size:10pt; font-family:Calibri">
						<tr style="height:40px;">
							<td width="50px"></td>
							<td width="100px" align="left"><strong>From Date : </strong></td>
							<td>
								<input type="date" id="datefrom" name="datefrom" style="width:130px" value="<?php print $fromdate; ?>" />
							</td>
							<td width="50px"></td>
							<td width="100px" align="left"><strong>To Date : </strong></td><td>
								<input type="date" id="dateto" name="dateto" style="width:130px" value="<?php print $todate; ?>" />
							</td>
							<td width="50px"></td>
							<td width="100px" rowspan="2"><input type="button" value="GET" onclick="setCustID()" style="height: 40px; width: 60px;"/></td>
						</tr>
						<tr style="height:40px;">
							<td width="50px"></td>
							<td width="100px" align="left"><strong>Salesman : </strong></td>
							<td>
								<select id="salesman" name="salesman">
                                <option value="all">--ALL--</option>
                                <?php
                                    $salesmanname='ALL';
                                    for($i=0;$i<sizeof($up_id);$i++){
										if(isset($_REQUEST['salesman'])){
											if($up_id[$i]==$_REQUEST['salesman']){
												$select='selected="selected"'; $salesmanname=ucfirst($up_name[$i]);
											}else{
												$select='';
											}
										}else{
                                            $select='';
                                        }
                                        print '<option value="'.$up_id[$i].'" '.$select.'>'.ucfirst($up_name[$i]).'</option>';
                                    }
                                ?>
                                </select>
							</td>
							<td width="50px"></td>
							<td width="80px" align="left"><strong>Customer : </strong></td>
							<td width="250px">
								<input type="text" id="tags1" value="<?php print $customer; ?>" onclick="this.value=''" width="250px"/>
							</td>
						</tr>
					</table>
				</div>
			</td>
		</tr>
	</table>
</form>

<div id="printheader" style="display:none" >
	<h1 style="color:navy"><?php print $inf_company; ?></h1>
	<h2 align="center" style="color:#3333FF; text-decoration:underline">Sales By Category</h2>
	<table style="font-size:12pt" border="1" cellspacing="0" >
		<tr>
			<td style="background-color:#C0C0C0; padding-left:10px">From</td>
			<td style="background-color:#EEEEEE; padding-left:10px"><?php print $fromdate; ?></td>
		</tr>
		<tr>
			<td style="background-color:#C0C0C0; padding-left:10px">To</td>
			<td style="background-color:#EEEEEE; padding-left:10px"><?php print $todate; ?></td>
		</tr>
	</table>
	<br />
</div>

<?php if(sizeof($cat_name)>0)
	print '<table align="center" width="600px">
			<tr>
				<td>
					<div id="graph1" style="width: 100%; height: 350px;">
					</div>
				</td>
			</tr>
		</table>';
?>

<br />
<br />

<div id="print" style="display:none">
	<table align="center" style="font-size:12pt; font-family:Calibri" cellspacing="0" border="1">
		<tr>
			<th width="50px">#</th>
			<th>Category</th>
			<th width="100px">Sale</th>
		</tr>
		<?php for($i=0;$i<sizeof($cat_name);$i++){
			print '<tr>
					<td align="center">
						'.($i+1).'
					</td>
					<td>&nbsp;&nbsp;&nbsp;'.$cat_name[$i].'&nbsp;&nbsp;&nbsp;</td>
					<td align="right">&nbsp;&nbsp;&nbsp;'.number_format($cat_sale[$i]).'&nbsp;&nbsp;&nbsp;</td>
				</tr>';
		} ?>
	</table>
</div>

<table align="center" style="font-size:12pt; font-family:Calibri">
	<tr bgcolor="#467898" style="color:white">
		<th width="50px">#</th>
		<th>Category</th>
		<th width="100px">Sale</th>
	</tr>
	<?php for($i=0;$i<sizeof($cat_name);$i++){
		if(($i%2)==0) $color='#FAFAFA'; else $color='#EEEEEE';
		print '<tr style="background-color:'.$color.'">
				<td align="center">
					'.($i+1).'
				</td>
				<td class="shipmentTB3">'.$cat_name[$i].'</td>
				<td class="shipmentTB3" align="right">'.number_format($cat_sale[$i], $decimal).'</td>
			</tr>';
	}
	print '<tr style="background-color:#DDDDDD">
			<td></td>
			<td class="shipmentTB3"><strong>Total</strong></td>
			<td class="shipmentTB3" align="right"><strong>'.number_format($total_sale, $decimal).'</strong></td>
		</tr>';
	?>
</table>

<br />

<table align="center">
	<tr>
		<td align="center">
			<div style="background-color:#6699FF; border:medium; border-color:black; width:80px;">
				<a class="shortcut-button" onclick="printdiv('print','printheader')" href="#"><span style="text-decoration:none; font-family:Arial; color:navy;">
				<img src="images/print.png" alt="icon" /><br />
				Print
				</span></a>
			</div>
		</td>
	</tr>
</table>

<br />

<?php
    include_once  'template/footer.php';
?>