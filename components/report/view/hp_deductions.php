<?php
                include_once  'template/header.php';
?>
<script type="text/javascript">
function addDeduct($i,$schedule,$inst_date){
		document.getElementById('div_btn_'+$i).innerHTML=document.getElementById('loading').innerHTML;
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
			if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				var returntext = xmlhttp.responseText;
				if(returntext=='done'){
					document.getElementById('div_btn_'+$i).innerHTML='<span style="color:green">Added</span> <a style="cursor:pointer" title="Remove Deduction Record" onclick="removeDeduct(\''+$i+'\',\''+$schedule+'\',\''+$inst_date+'\')"><img src="images/action_delete.gif" /></a>';
				}else{
					document.getElementById('div_btn_'+$i).innerHTML='<span style="color:red">'+returntext+'</span>';
				}
			}
		};
		xmlhttp.open("POST", "index.php?components=<?php print $components; ?>&action=add_deduction", true);
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xmlhttp.send('schedule='+$schedule+'&inst_date='+$inst_date);
}

function removeDeduct($i,$schedule,$inst_date){
		document.getElementById('div_btn_'+$i).innerHTML=document.getElementById('loading').innerHTML;
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
			if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				var returntext = xmlhttp.responseText;
				if(returntext=='done'){
					document.getElementById('div_btn_'+$i).innerHTML='<input type="button" value="Add to Commission Report" onclick="addDeduct(\''+$i+'\',\''+$schedule+'\',\''+$inst_date+'\')" style="background-color:maroon; color:white; cursor:pointer" />';
				}else{
					document.getElementById('div_btn_'+$i).innerHTML='<span style="color:red">'+returntext+'</span>';
				}
			}
		};
		xmlhttp.open("POST", "index.php?components=<?php print $components; ?>&action=remove_deduction", true);
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xmlhttp.send('schedule='+$schedule+'&inst_date='+$inst_date);
}

function setFilter(){
	var rec_agent=document.getElementById('filter_rec_agent').value;
	var type=document.getElementById('filter_type').value;
	window.location = 'index.php?components=<?php print $components; ?>&action=hp_deductions&rec_agent='+rec_agent+'&type='+type;
}
</script>
<!-- ------------------Item List----------------------- -->
<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px"  /></div>
	<div style="text-align:center">
	<h3 style="font-family:Calibri; color:maroon">Warning Date Exceeded Delayed Payments</h3>
	</div>
	<table align="center" style="font-family:Calibri">
	<tr><td class="shipmentTB3" bgcolor="#DDDDDD">Warning Date (Monthly)</td><td class="shipmentTB3" bgcolor="#EEEEEE"><?php print $warning_date1; ?></td></tr>
	<tr><td class="shipmentTB3" bgcolor="#DDDDDD">Warning Date (Weekly)</td><td class="shipmentTB3" bgcolor="#EEEEEE"><?php print $warning_date2; ?></td></tr>
	<tr><td class="shipmentTB3" bgcolor="#DDDDDD">Warning Date (Daily)</td><td class="shipmentTB3" bgcolor="#EEEEEE"><?php print $warning_date3; ?></td></tr>
	</table>
	<br />
	<table align="center" style="font-family:Calibri">
	<tr style="background-color:#467898; color:white;"><th class="shipmentTB3">Recovery Agent</th><th class="shipmentTB3">Invoice No</th><th class="shipmentTB3">Pay Due Date</th><th class="shipmentTB3">Schedule Type</th><th class="shipmentTB3">Amount</th><th></th></tr>
	<tr style="background-color:#467898; color:white;"><th class="shipmentTB3">
		<select onchange="setFilter()" id="filter_rec_agent">
			<option value="all" >-ALL-</option>
			<?php
			for($i=0;$i<sizeof($salesman_filter);$i++){
				if($salesman_filter[$i]==$filter_rec_agent) $select1='selected="selected"'; else $select1='';
				print '<option value="'.$salesman_filter[$i].'" '.$select1.'>'.ucfirst($salesman_filter[$i]).'</option>';
			}
			?>
		</select>
	</th><th class="shipmentTB3"></th><th class="shipmentTB3"></th><th class="shipmentTB3">
		<select onchange="setFilter()" id="filter_type">
			<option value="all">-ALL-</option>
			<option value="Monthly" <?php if($filter_type=='Monthly') print 'selected="selected"'; ?> >Monthly</option>
			<option value="Weekly" <?php if($filter_type=='Weekly') print 'selected="selected"'; ?> >Weekly</option>
			<option value="Daily" <?php if($filter_type=='Daily') print 'selected="selected"'; ?> >Daily</option>
		</select>
	</th><th class="shipmentTB3"></th><th></th></tr>
	<?php
	for($i=0;$i<sizeof($dd_inv);$i++){
		if(($i%2)==0) $color='#EEEEEE'; else $color='#F9F9F9';
		$added=false;
		for($j=0;$j<sizeof($de_rec_sch);$j++){
			if(($de_rec_sch[$j]==$dd_hp_schedule[$i])&&($de_rec_instdate[$j]==$dd_py_schedule_date[$i])) $added=true;
		}
		print '<tr bgcolor="'.$color.'"><td class="shipmentTB3">'.ucfirst($dd_rec_ag_name[$i]).'</td><td class="shipmentTB3">'.str_pad($dd_inv[$i], 7, "0", STR_PAD_LEFT).'</td><td class="shipmentTB3" align="right">'.$dd_py_schedule_date[$i].'</td><td class="shipmentTB3">'.$dd_hp_type[$i].'</td><td class="shipmentTB3" align="right">'.number_format($dd_instalment[$i]).'</td><td class="shipmentTB3" ><div id="div_btn_'.$i.'">';
		if($added){
			print '<span style="color:green">Added</span> <a style="cursor:pointer" title="Remove Deduction Record" onclick="removeDeduct(\''.$i.'\',\''.$dd_hp_schedule[$i].'\',\''.$dd_py_schedule_date[$i].'\')"><img src="images/action_delete.gif" /></a>';
		}else{
			print '<input type="button" value="Add to Commission Report" onclick="addDeduct(\''.$i.'\',\''.$dd_hp_schedule[$i].'\',\''.$dd_py_schedule_date[$i].'\')" style="background-color:maroon; color:white; cursor:pointer" />';
		}
		print '</div></td></tr>';
	}
	?>
	</table>

<?php
                include_once  'template/footer.php';
?>