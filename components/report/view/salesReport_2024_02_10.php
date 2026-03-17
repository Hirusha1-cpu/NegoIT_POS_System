<?php
                include_once  'template/header.php';
		
		if($systemid==1 && $systemid==4) $profit_title="Total Cash"; else $profit_title="Total Profit";
?>
	<link rel="stylesheet" href="plugin/jquery/css/jquery.ui.all.css" />
	<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.core.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.widget.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.position.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.autocomplete.js"></script>
	<link rel="stylesheet" href="plugin/jquery/css/demos.css" />

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawVisualization);


      function drawVisualization() {
        // Some raw data (not necessarily accurate)
        var data = google.visualization.arrayToDataTable([['Salesman','Sale'],
        <?php for($i=0;$i<sizeof($graph_user);$i++){
        	print "['".ucfirst($graph_user[$i])."',$graph_total[$i]],";
        }
        ?>
      ]);

    var options = {
      vAxis: {title: 'Income'},
      hAxis: { 
        direction: -1, 
        slantedText: true, 
        slantedTextAngle: 45 // here you can even use 180 
   	 } ,
   	 legend: { position: "none" },
      seriesType: 'bars',
      series: {5: {type: 'line'}}
    };

    var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
    chart.draw(data, options);
  }

	$(function() {
		var availableTags1 = [<?php for ($x=0;$x<sizeof($itm_description);$x++){ print '"'.$itm_description[$x].'",'; } ?>	];
		$( "#tags1" ).autocomplete({
			source: availableTags1
		});
	});
	
	function setCategory($id){
		if(document.getElementById("cat0"+$id).checked){
			document.getElementById("cat"+$id).value='yes';
		}else{
			document.getElementById("cat"+$id).value='no';
		}
	}
	
	function setUnsetAll(source) {
	  checkboxes = document.getElementsByName('cat0');
	  for(var i=0, n=checkboxes.length;i<n;i++) {
	    checkboxes[i].checked = source.checked;
	    var id_txt=checkboxes[i].id;
	    id_txt=id_txt.slice(4);
	    setCategory(id_txt);
	  }
	}
	
	function nextTagAction(){
		document.getElementById("rep_form1").submit();
	}


	function printdivBorderx($table_id,$x,$y){
		document.getElementById($table_id).border="1"
		document.getElementById($table_id).cellSpacing="0"
		printdiv($x,$y);
		document.getElementById($table_id).border="0"
		document.getElementById($table_id).cellSpacing="2"
	}
</script>
<!-- ------------------Item List----------------------- -->
	<form id="rep_form1" action="index.php?components=report&action=sales_report" method="post" >
		<?php	for($i=0;$i<sizeof($cat_id);$i++){
				if($cat_select[$cat_id[$i]]=='checked="checked"') $val='yes'; else $val='no';
				print '<input type="hidden" id="cat'.$cat_id[$i].'" name="cat'.$cat_id[$i].'" value="'.$val.'"/>';
			}	
		?>
		
		<table align="center" border="0"><tr><td style="vertical-align:top">
		<div style="border-radius:10px; background-color:#DDDDDD">
			<table align="center" height="100%" border="0" cellspacing="0" style="font-family:Calibri; font-size:12pt">
			<tr><td width="150px">Date Range<input type="checkbox" id="date_range" onchange="setDateRange('<?php print $date1; ?>','<?php print $date2; ?>')" <?php if(($date1!='')&&($date2!='')) print 'checked="checked"'; ?> /></td><td align="right" colspan="2">
				<div id="datediv">
				<?php
				if(($date1!='')&&($date2!='')){
					print '<strong>From </strong>: &nbsp;<input type="date" name="date1" style="width:130px" value="'.$date1.'" />&nbsp;&nbsp;&nbsp;<strong>To </strong>: &nbsp;<input type="date" name="date2" style="width:130px" value="'.$date2.'" /><input type="submit" value="GET" />';
				}else{
					print '<strong>Date</strong>: &nbsp;<input type="date" name="date1" style="width:130px" value="'.$date1.'" /><input type="submit" value="GET" />';
				}
				?>
				</div>
				</td>
			<td width="50px"></td><td><strong>Filter by Item </strong></td><td><input type="text" id="tags1" name="filter_item" value="<?php print $item_req; ?>" /> <input type="submit" value="Apply" />
			</td></tr>
			<tr><td>
			<strong>HP </strong>
				<select name="hp" >
				<option value="" >--ALL--</option>
				<option value="yes" <?php if($hp=='yes') print 'selected="selected"'; ?> >YES</option>
				<option value="no" <?php if($hp=='no') print 'selected="selected"'; ?> >NO</option>
				</select>
			</td><td colspan="2">
			<strong>Group </strong>
				<select name="group" >
				<option value="" >--ALL--</option>
				<?php
				$group_print='ALL';
				 for($i=0;$i<sizeof($gp_id);$i++){
				 	if($gp_id[$i]==$group){ $select='selected="selected"'; $group_print=ucfirst($gp_name[$i]); }else{ $select=''; }
				 	print '<option value="'.$gp_id[$i].'" '.$select.'>'.ucfirst($gp_name[$i]).'</option>';
				 }
				?>
				</select>
			</td><td></td><td><strong>Sub System</strong></td><td>
				<select name="sub_system0" >
				<option value="" >--ALL--</option>
				<?php
				$sub_system_print='ALL';
				 for($i=0;$i<sizeof($sub_system_list);$i++){
				 	if($sub_system_list[$i]==$sub_system0){ $select='selected="selected"'; $sub_system_print=ucfirst($sub_system_names[$i]); }else{ $select=''; }
				 	print '<option value="'.$sub_system_list[$i].'" '.$select.'>'.ucfirst($sub_system_names[$i]).'</option>';
				 }
				?>
				</select>
			</td></tr>
			<tr><td colspan="6">
				<?php  include_once  'template/tag.php'; ?>
			</td></tr>
			</table>
			</div>
		<table align="center"><tr><td><div id="chart_div" style="width: 900px; height: 300px;"></div></td></tr></table>
			<table align="center" cellspacing="0" border="1" style="font-size:12pt">
			<tr><th>Total Sale</th><th><?php print $profit_title; ?></th></tr>
			<tr style="color:orange"><th width="200px" align="center"><?php print number_format(array_sum($invoice_total)+array_sum($rtn_pay)); ?></th><th width="200px" align="center"><?php print number_format(array_sum($invoice_profit)); ?></th></tr>
			</table>
		<br /><br />
			<div id="print" align="center">
			<table align="center" id="sales_tb" height="100%" style="font-family:Calibri; font-size:10pt">
			<tr bgcolor="#BBBBBB"><th width="100px">Invoice No</th><th width="100px">Date</th><th width="80px">Invoice Price</th><th width="80px">Invoice Profit</th><th width="60px">Discount</th><th width="50px">Gross<br />Profit %</th><th width="100px">Type</th><th width="100px">Salesman</th><th width="100px">Store</th><th width="100px">Customer</th></tr>
			<tr><td colspan="6"></td><td>
			<select name="filter_type" style="width:100%" onchange="this.form.submit()">
			<option value="">-ALL-</option>
			<?php
			$filter_type=array_unique($invoice_type);
			$filter_type=array_values($filter_type);
			for($i=0;$i<sizeof($filter_type);$i++){
			 	if($filter_type[$i]==$type_req) $select='selected="selected"'; else $select='';
				print '<option value="'.$filter_type[$i].'" '.$select.'>'.$filter_type[$i].'</option>';
			}
			?>
			</select>
			</td><td>
			<select name="filter_salesman" style="width:100%" onchange="this.form.submit()">
			<option value="">-ALL-</option>
			<?php
			$filter_salesman=array();
			$filter_salesman=array_unique($invo_salesman);
			$filter_salesman=array_values($filter_salesman);
			for($i=0;$i<sizeof($filter_salesman);$i++){
			 	if($filter_salesman[$i]==$salesman_req) $select='selected="selected"'; else $select='';
				print '<option value="'.$filter_salesman[$i].'" '.$select.'>'.$filter_salesman[$i].'</option>';
			}
			?>
			</select>
			</td><td>
			<select name="filter_store" style="width:100%" onchange="this.form.submit()">
			<option value="">-ALL-</option>
			<?php
			$filter_store=array_unique($store);
			$filter_store=array_values($filter_store);
			for($i=0;$i<sizeof($filter_store);$i++){
			 	if($filter_store[$i]==$store_req) $select='selected="selected"'; else $select='';
				print '<option value="'.$filter_store[$i].'" '.$select.'>'.$filter_store[$i].'</option>';
			}
			?>
			</select>
			</td><td>
			<select name="filter_cust" onchange="this.form.submit()">
			<option value="" >--ALL--</option>
			<?php
			$filter_cust=array_unique($cust);
			$filter_cust=array_values($filter_cust);
			 for($i=0;$i<sizeof($filter_cust);$i++){
			 	if($filter_cust[$i]==$cust_req) $select='selected="selected"'; else $select='';
			 	print '<option value="'.$filter_cust[$i].'" '.$select.'>'.$filter_cust[$i].'</option>';
			 }
			?>
			</select>
			</td></tr>
		<?php
		$inv=0;
			for($i=0;$i<sizeof($invoice_no);$i++){
				if($bi_discount[$i]>0){ $color0='red'; $title='title="Discounted Invoice"'; }else{ $color0=$color[$i]; $title=''; }
				if($i%2 ==0) $color1='#EEEEEE'; else $color1='#DDDDDD';
				$profit='';
				if($invoice_total[$i]>0) $profit=round((($invoice_profit[$i]/$invoice_total[$i])*100),1);
				if($invoice_total[$i]<0) $profit=-round((($invoice_profit[$i]/$invoice_total[$i])*100),1);
					print '<tr bgcolor="'.$color1.'" style="color:'.$color0.'"><td align="center"><a '.$title.' href="index.php?components=billing&action=finish_bill&id='.$invoice_no[$i].'">'.str_pad($invoice_no[$i], 7, "0", STR_PAD_LEFT).'</a></td>
					<td width="50px" align="center">'.$time[$i].'</td><td align="right">'.number_format($invoice_total[$i]).'&nbsp;&nbsp;</td>
					<td align="right">'.number_format($invoice_profit[$i]).'&nbsp;&nbsp;</td><td align="right">'.number_format($total_discount[$i]).'</td><td align="center">'.$profit.'%</td>
					<td>&nbsp;&nbsp;'.$invoice_type[$i].'&nbsp;&nbsp;</td>
					<td>&nbsp;&nbsp;'.ucfirst($invo_salesman[$i]).'</td><td>&nbsp;&nbsp;'.$store[$i].'</td>
					<td>'.ucfirst($cust[$i]).'</td></tr>';
			}
		?>	
		<?php
		if(sizeof($rtn_no)>0) print '<tr bgcolor="#BBBBBB"><td colspan="10" style="padding-left:20px">Return Invoice Extra Pay Collection</td></tr>';
			for($i=0;$i<sizeof($rtn_no);$i++){
				if($i%2 ==0) $color1='#EEEEEE'; else $color1='#DDDDDD';
					print '<tr bgcolor="'.$color1.'" ><td align="center"><a href="index.php?components=billing&action=finish_return&id='.$rtn_no[$i].'">'.str_pad($rtn_no[$i], 7, "0", STR_PAD_LEFT).'</a></td><td width="50px" align="center">'.$rtn_time[$i].'</td><td align="right">'.number_format($rtn_pay[$i]).'&nbsp;&nbsp;</td>	<td align="right">0&nbsp;&nbsp;</td><td align="right">0</td><td align="right"></td><td>&nbsp;&nbsp;Return Invoice&nbsp;&nbsp;</td><td>&nbsp;&nbsp;'.ucfirst($rtn_salesman[$i]).'</td><td>&nbsp;&nbsp;'.$rtn_store[$i].'</td><td>'.ucfirst($rtn_cust[$i]).'</td></tr>';
			}
		?>	
			</table>
			</div>
		</td><td style="vertical-align:top">
			<table style="font-family:Calibri; font-size:10pt;">
			<tr><th style="background-color:#DDDDDD" colspan="2">Category Filter</th></tr>
			<tr style="background-color:#DDDDDD"><th><input type="checkbox" onclick="setUnsetAll(this)" /></th><th>Set/Unset All</th></tr>
			<?php
			for($i=0;$i<sizeof($cat_id);$i++){
				print '<tr style="background-color:#EFEFEF"><td><input type="checkbox" name="cat0" id="cat0'.$cat_id[$i].'" '.$cat_select[$cat_id[$i]].' onchange="setCategory('.$cat_id[$i].')" /></td><td>'.$cat_name[$i].'</td></tr>';
			}
			?>
			</table>
		</td></tr></table>
	</form>
	<table align="center" style="font-size:12pt;"><tr><td><?php 
	$down=array_sum($invoice_total)+array_sum($rtn_pay);
	if($down>0) print round(((array_sum($invoice_profit))/$down)*100).'%'; 
	?></td></tr></table>

<div id="printheader" style="display:none" >
	<h1 style="color:navy"><?php print $inf_company; ?></h1>
	<h2 align="center" style="color:#3333FF; text-decoration:underline">Sales Report</h2>
	<table style="font-size:12pt" border="1" cellspacing="0">
		<tr>
			<td width="100px" style="background-color:#C0C0C0; padding-left:10px">From Date</td><td width="250px" style="background-color:#EEEEEE; padding-left:10px"><?php print $date1; ?></td>
			<td width="100px" style="background-color:#C0C0C0; padding-left:10px">To Date</td><td width="250px" style="background-color:#EEEEEE; padding-left:10px"><?php print $date2; ?></td>
		</tr>
		<tr>
			<td width="100px" style="background-color:#C0C0C0; padding-left:10px">Sub System</td><td width="250px" style="background-color:#EEEEEE; padding-left:10px"><?php print $sub_system_print; ?></td>
			<td width="100px" style="background-color:#C0C0C0; padding-left:10px">Group</td><td width="250px" style="background-color:#EEEEEE; padding-left:10px"><?php print $group_print; ?></td>
		</tr>
		<tr>
			<td width="100px" style="background-color:#C0C0C0; padding-left:10px">HP</td><td width="250px" style="background-color:#EEEEEE; padding-left:10px"><?php print strtoupper($hp); ?></td>
			<td width="100px" style="background-color:#C0C0C0; padding-left:10px"></td><td width="250px" style="background-color:#EEEEEE; padding-left:10px"></td>
		</tr>
	</table><br />
</div>

<table align="center" style="font-size:8pt"><tr>
<td align="center">
<div style="background-color:#6699FF; border:medium; border-color:black; width:80px;">
	<a class="shortcut-button" onclick="printdivBorderx('sales_tb','print','printheader')" href="#"><span style="text-decoration:none; font-family:Arial; color:navy;">
	<img src="images/print.png" alt="icon" /><br />
	Print
	</span></a>
</div>
</td><td align="center">
<div style="background-color:#6699FF; border:medium; border-color:black; width:90px;">
	<?php print '<a class="shortcut-button" style="cursor:pointer" onclick="setFilter(\'export_unic_list\')" ><span style="text-decoration:none; font-family:Arial; color:navy;">'; ?>
	<img src="images/excel.jpg" style="width:50px" alt="icon" /><br />
	Export Unique List
	</span></a>
</div>
</td></tr></table>

<?php
                include_once  'template/footer.php';
?>