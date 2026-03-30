<?php
                include_once  'template/header.php';
                $recovery_agent=$_GET['rag_id'];
				$selected_recovery_agent=$selected_instalment_type=$selected_cust_group=$selected_cust_town=""

?>
<script type="text/javascript">
const zeroPad = (num, places) => String(num).padStart(places, '0');

function setFilter($cu_id){
	$hp_rag=document.getElementById('hp_rag').value;
	$hp_type=document.getElementById('hp_type').value;
	$hp_group=document.getElementById('hp_group').value;
	$hp_town=document.getElementById('hp_town').value;
	window.location = 'index.php?components=hire_purchase&action=invoice_outstanding&type='+$hp_type+'&group='+$hp_group+'&town='+$hp_town+'&cu_id='+$cu_id+'&rag_id='+$hp_rag;
}
</script>
<script type="text/javascript">
	function printdivBorderx($table_id,$x,$y){
		document.getElementById($table_id).border="1"
		document.getElementById($table_id).cellSpacing="0"
		printdiv($x,$y);
		document.getElementById($table_id).border="0"
		document.getElementById($table_id).cellSpacing="2"
	}
		
	function printdivBorder2(){
		document.getElementById('data_table').border="1";
		document.getElementById('data_table').cellSpacing="0";
		document.getElementById("data_table").style.fontSize = "9pt";
		
		var headstr = "<html><head><title></title></head><body>";
		var footstr = "</body></html>";
		var headerstr = document.all.item('printheader').innerHTML;
		var newstr = document.all.item('print').innerHTML;
		var oldstr = document.body.innerHTML;
		document.body.innerHTML = headstr+headerstr+newstr+footstr;
		window.print();
		document.body.innerHTML = oldstr;
		
		document.getElementById('data_table').border="0";
		document.getElementById('data_table').cellSpacing="2";
		document.getElementById("data_table").style.fontSize = "12pt";
		return true;
	}
</script>
<!-- -------------------------------------------------------------------------------------------------------------------- -->
<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px" /></div>
<table align="center" style="font-size:12pt"><tr><td>
<?php 
	if(isset($_REQUEST['message'])){
		if($_REQUEST['re']=='success') $color='green'; else $color='#DD3333';
		print '<script type="text/javascript">document.getElementById("notifications").innerHTML='."'".'<span style="color:'.$color.'; font-weight:bold;font-size:12pt;">'.$_REQUEST['message'].'</span>'."'".';</script>';
	}
?></td></tr></table>

<table style="font-family:Calibri; font-size:12pt; border-radius: 10px; background-color:#EEEEEE;" align="center">
<tr height="40px"><td width="150"></td><td><div id="cust_filter" ></div></td>
	<td align="center">Recovery Agent</td><td>
	<select id="hp_rag" onchange="setFilter('')" style="font-size:16" >
		<option value="all">-ALL-</option>
		<?php 
		for($i=0;$i<sizeof($rag_id);$i++){
			$select='';
			if($rag_id[$i]==$recovery_agent){
				$select='selected="selected"';
				$selected_recovery_agent=$rag_name[$i];
			}
			print '<option value="'.$rag_id[$i].'" '.$select.' >'.ucfirst($rag_name[$i]).'</option>';
		} ?>
		</select>
	</td><td width="50px" bgcolor="#E2E2E2"></td>
	<td>Instalment Type</td><td>
	<select id="hp_type" onchange="setFilter('')">
		<option value="" >-SELECT-</option>
		<?php
		for($i=0;$i<sizeof($sch_type_id);$i++){
			$select='';
			if($type==$sch_type_id[$i]){
				$select='selected="selected"';
				$selected_instalment_type=$sch_type_name[$i];
			} 
			print '<option value="'.$sch_type_id[$i].'" '.$select.'>'.$sch_type_name[$i].'</option>';
		}
		?>
	</select>
	</td>
	<td width="50px" bgcolor="#E2E2E2"></td>
	<td>Group</td><td>
	<select id="hp_group" onchange="setFilter('')">
		<option value="all" >-SELECT-</option>
		<?php
		for($i=0;$i<sizeof($cu_gp_id);$i++){
			$select='';
			if($group==$cu_gp_id[$i]){
				$select='selected="selected"'; 
				$selected_cust_group=$cu_gp_name[$i];
			} 
			print '<option value="'.$cu_gp_id[$i].'" '.$select.'>'.$cu_gp_name[$i].'</option>';
		}
		?>
	</select>
	</td>
	<td width="50px" bgcolor="#E2E2E2"></td>
	<td>Town</td><td>
	<select id="hp_town" onchange="setFilter('')">
		<option value="all" >-SELECT-</option>
		<?php
		for($i=0;$i<sizeof($cu_tw_id);$i++){
			$select='';
			if($town==$cu_tw_id[$i]){
				$select='selected="selected"';
				$selected_cust_town = $cu_tw_name[$i];
			}
			print '<option value="'.$cu_tw_id[$i].'" '.$select.'>'.$cu_tw_name[$i].'</option>';
		}
		?>
	</select>
	</td>
	<td width="150"></td></tr>
</table>
<br />
	<table style="font-family:Calibri; font-size:10pt;" align="center">
	<tr><td width="15px" bgcolor="#EE3333"></td><td style="color:gray">If Instalment Outstanding > Invoice Due. This happens due to data entry issues.</td></tr>
	</table>
<br />
<div id="print">
	<table id="data_table" style="font-family:Calibri; font-size:12pt;" align="center">
	<tr style="background-color:#467898; color:white;"><th></th><th>Invoice No</th><th>Customer</th><th>Tel</th><th width="100px">Befor</th><th width="100px"><?php print $monthb3L; ?></th><th width="100px"><?php print $monthb2L; ?></th><th width="100px"><?php print $monthb1L; ?></th><th width="100px"><?php print $monthb0L; ?></th><th>Instalment<br />Outstanding</th><th>Invoice Due</th></tr>
	<?php 
	$inv_no=$total0=$total1=$total2=$total3=$total4=$inst_outtotal=$inv_outtotal=0;
	for($i=0;$i<sizeof($downcol_inv);$i++){
		if(($i%2)==0) $color='#FAFAFA'; else $color='#EEEEEE';
			$inst_outstanding=$downcol_amo[$downcol_inv[$i]]['before']+$downcol_amo[$downcol_inv[$i]][$monthb3N]+$downcol_amo[$downcol_inv[$i]][$monthb2N]+$downcol_amo[$downcol_inv[$i]][$monthb1N]+$downcol_amo[$downcol_inv[$i]][$monthb0N];
			$inst_outtotal+=$inst_outstanding;
			$inv_outtotal+=$downcol_inv_outstanding[$downcol_inv[$i]];
			$total0+=$downcol_amo[$downcol_inv[$i]][$monthb0N];
			$total1+=$downcol_amo[$downcol_inv[$i]][$monthb1N];
			$total2+=$downcol_amo[$downcol_inv[$i]][$monthb2N];
			$total3+=$downcol_amo[$downcol_inv[$i]][$monthb3N];
			$total4+=$downcol_amo[$downcol_inv[$i]]['before'];
			if($inst_outstanding>$downcol_inv_outstanding[$downcol_inv[$i]]) $color='#EE3333'; 

			print '<tr style="background-color:'.$color.'">';
			print '<td class="shipmentTB3"><input type="checkbox" /></td><td class="shipmentTB3"><a href="index.php?components=hire_purchase&action=home&invoice_no='.$downcol_inv[$i].'" style="text-decoration:none">'.str_pad($downcol_inv[$i], 7, "0", STR_PAD_LEFT).'</a></td>';
			print '<td class="shipmentTB3" ><a style="cursor:pointer; color:green" onclick="setFilter('.$downcol_cust_id[$downcol_inv[$i]].')">'.$downcol_cust_name[$downcol_inv[$i]].'</a></td>';
			print '<td class="shipmentTB3" >'.$downcol_tel[$downcol_inv[$i]].'</td>';
			print '<td class="shipmentTB3" align="right"><a title="Instalment Date: '.$downcol_inst[$downcol_inv[$i]]['before'].' or before" style="cursor:pointer">'.number_format($downcol_amo[$downcol_inv[$i]]['before']).'</a></td>';
			print '<td class="shipmentTB3" align="right"><a title="Instalment Date: '.$downcol_inst[$downcol_inv[$i]][$monthb3N].'" style="cursor:pointer">'.number_format($downcol_amo[$downcol_inv[$i]][$monthb3N]).'</a></td>';
			print '<td class="shipmentTB3" align="right"><a title="Instalment Date: '.$downcol_inst[$downcol_inv[$i]][$monthb2N].'" style="cursor:pointer">'.number_format($downcol_amo[$downcol_inv[$i]][$monthb2N]).'</a></td>';
			print '<td class="shipmentTB3" align="right"><a title="Instalment Date: '.$downcol_inst[$downcol_inv[$i]][$monthb1N].'" style="cursor:pointer">'.number_format($downcol_amo[$downcol_inv[$i]][$monthb1N]).'</a></td>';
			print '<td class="shipmentTB3" align="right"><a title="Instalment Date: '.$downcol_inst[$downcol_inv[$i]][$monthb0N].'" style="cursor:pointer">'.number_format($downcol_amo[$downcol_inv[$i]][$monthb0N]).'</a></td>';
			print '<td class="shipmentTB3" align="right">'.number_format($inst_outstanding).'</td>';
			print '<td class="shipmentTB3" align="right">'.number_format($downcol_inv_outstanding[$downcol_inv[$i]]).'</td>';
			print '</tr>';
	}
	print '<tr bgcolor="DDDDDD" style="font-weight:bold"><td colspan="4"></td><td class="shipmentTB3" align="right">'.number_format($total4).'</td><td class="shipmentTB3" align="right">'.number_format($total3).'</td><td class="shipmentTB3" align="right">'.number_format($total2).'</td><td class="shipmentTB3" align="right">'.number_format($total1).'</td><td class="shipmentTB3" align="right">'.number_format($total0).'</td><td class="shipmentTB3" align="right">'.number_format($inst_outtotal).'</td><td class="shipmentTB3" align="right">'.number_format($inv_outtotal).'</td></tr>';
	?>
	</table>
</div>
<div id="printheader" style="display:none" >
	<h2 align="center" style="color:navy"><?php print $inf_company; ?></h2>
	<h3 align="center" style="color:#333399; text-decoration:underline">Hire Purchase Outstanding Report</h3>
	<table style="font-size:12pt" border="1" cellspacing="0">
		<tr>
			<td width="100px" style="background-color:#C0C0C0; padding-left:10px">Recovery Agent</td><td width="250px" style="background-color:#EEEEEE; padding-left:10px"><?php print $selected_recovery_agent; ?></td>
			<td width="100px" ></td>
			<td width="100px" style="background-color:#C0C0C0; padding-left:10px">Group</td><td width="250px" style="background-color:#EEEEEE; padding-left:10px"><?php print $selected_cust_group; ?></td>
		</tr>
		<tr>
			<td width="100px" style="background-color:#C0C0C0; padding-left:10px">Instalment Type</td><td width="250px" style="background-color:#EEEEEE; padding-left:10px"><?php print $selected_instalment_type; ?></td>
			<td width="100px" ></td>
			<td width="100px" style="background-color:#C0C0C0; padding-left:10px">Town</td><td width="250px" style="background-color:#EEEEEE; padding-left:10px"><?php print $selected_cust_town; ?></td>
		</tr></table><br />
</div>


<table align="center">
<tr><td align="center">
	<div style="background-color:#6699FF; border:medium; border-color:black; width:80px;">
	<a class="shortcut-button" onclick="printdivBorder2()" href="#"><span style="text-decoration:none; font-family:Arial; color:navy;">
	<img src="images/print.png" alt="icon" /><br />
	Print
	</span></a>
	</div>
</td></tr>
</table>

<?php if($cu_id!=''){ ?>

<script type="text/javascript">
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			    var returntext=this.responseText;
				document.getElementById('cust_filter').innerHTML='<span style="color:blue;">'+returntext+' <a style="color:red; cursor:pointer;" onclick="setFilter(\'\')"><sup>x</sup></a></span>&nbsp;&nbsp;&nbsp;&nbsp;';
			}
		};
	xmlhttp.open("POST", "index.php?components=<?php print $components; ?>&action=get_cust_name", true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send('id=<?php print $cu_id; ?>');
</script>

<?php } ?>

<?php
                include_once  'template/footer.php';
?>