<?php
                include_once  'template/header.php';
?>
	<link rel="stylesheet" href="plugin/jquery/css/demos.css" />
	<link rel="stylesheet" href="plugin/jquery/css/jquery.ui.all.css" />
	<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.core.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.widget.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.position.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.autocomplete2.js"></script>
	<script type="text/javascript">
		$(function() {
			var availableTags = [<?php for ($x=0;$x<sizeof($cust_name);$x++){ print '"'.$cust_name[$x].'",'; } ?>	];
			$( "#cust" ).autocomplete({
				source: availableTags
			});
		});
		
		function changeSM(){
			var from_date=document.getElementById('from_date').value;
			var to_date=document.getElementById('to_date').value;
			var salesman=document.getElementById('sm').value;
			window.location = 'index.php?components=<?php print $_GET['components']; ?>&action=show_return&from_date='+from_date+'&to_date='+to_date+'&sm='+salesman+'&cu=<?php print $_GET['cu']; ?>';
		}
		
		function validateRTForm(){
			$count=0;
			var cu_id_arr = [<?php for ($x=0;$x<sizeof($cust_id);$x++){ print '"'.$cust_id[$x].'",'; } ?>	];
			var cu_name_arr = [<?php for ($x=0;$x<sizeof($cust_name);$x++){ print '"'.$cust_name[$x].'",'; } ?>	];
			var cu_name=document.getElementById('cust').value;
			if(cu_name!=''){
				var b=cu_name_arr.indexOf(cu_name);
				if(b==-1){
					$count++;
					window.alert("Invalid Customer");
				}else{
					document.getElementById('cu').value=cu_id_arr[b];
				}
			}
			if(validateDateRange()==false)	$count++;
			
			if($count>0)
				return false;
			else
				return true;
				
		}
	</script>
<!-- ------------------Item List----------------------- -->
<table align="center" style="font-size:11pt"><tr><td>
<?php 
	if(isset($_REQUEST['message'])){
		if($_REQUEST['re']=='success') $color='green'; else $color='red';
	print '<span style="color:'.$color.'; font-weight:bold;">'.$_REQUEST['message'].'</span><br /><br />'; 
	}
?></td></tr></table>
<div id="printheader" style="display:none" >
	<h1 style="color:navy"><?php print $inf_company; ?></h1>
	<h2 align="center" style="color:#3333FF; text-decoration:underline">Return Items Report</h2>
	<table style="font-size:12pt" border="1" cellspacing="0">
		<tr><td style="background-color:#C0C0C0; padding-left:10px; padding-right:10px;">From</td><td style="background-color:#EEEEEE; padding-left:10px; padding-right:10px;"><?php print $from_date; ?></td></tr>
		<tr><td style="background-color:#C0C0C0; padding-left:10px; padding-right:10px;">To</td><td style="background-color:#EEEEEE; padding-left:10px; padding-right:10px;"><?php print $to_date; ?></td></tr>
	</table><br />
</div>


<h2 align="center" style="color:#0158C2">List Of Return Items</h2>
<form action="index.php" method="get" onsubmit="return validateRTForm()" >
<input type="hidden" name="components" value="<?php print $_GET['components']; ?>" />
<input type="hidden" name="action" value="show_return" />
<input type="hidden" id="cu" name="cu" value="" />
<table align="center" style="font-family:Calibri; font-size:12pt" bgcolor="#EEEEEE">
<tr><td width="50px"></td><td>From Date</td><td><input type="date" name="from_date" id="from_date" value="<?php print $from_date; ?>" style="width:130px" /></td><td width="80px"></td><td>Salesman</td><td>
<select id="sm" name="sm" onchange="changeSM()" >
	<option value="all" >-ALL-</option>
	<?php 
	$sm=$_GET['sm'];
	for($i=0;$i<sizeof($sm_id);$i++){
	if($sm==$sm_id[$i]) $select='selected="selected"'; else $select='';
	print '<option value="'.$sm_id[$i].'" '.$select.'>'.ucfirst($sm_name[$i]).'</option>';
	} ?>
</select>
</td><td width="50px"></td><td rowspan="2"><input type="submit" value="Get" style="height:60px; width:70px" /></td></tr>
<tr><td></td><td>To Date</td><td><input type="date" name="to_date" id="to_date" value="<?php print $to_date; ?>" style="width:130px" /></td><td></td>
<td>Customer</td><td><input type="text" id="cust" style="width:150px" value="<?php print $cust; ?>" onclick="this.value=''" /></td><td></td>
</tr>
</table>
</form>
<br>
<div id="print">
	<table align="center" bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0"  style="font-size:10pt" >
	<tr style="font-weight:bold; background-color:#0066AA; color:white"><th>Return Invoice</th><th>Return Date</th><th>Customer</th><th>Item</th><th>Return Qty</th><th>Salesman</th><th>Disposal ID</th><th>Disposed Date</th><th>Disposed By</th><th>Status</th></tr>
	<?php for($i=0;$i<sizeof($dis_id);$i++){
			if((strlen($rtn_cust[$i]))>20) $cust=substr($rtn_cust[$i],0,19).'...'; else $cust=$rtn_cust[$i];
			if($rtn_st[$i]=='Processed') $color='green'; else $color='';
			print '<tr style="color:'.$color.'"><td align="center"><a href="index.php?components=billing&action=finish_return&id='.$rtn_inv[$i].'">'.str_pad($rtn_inv[$i], 7, "0", STR_PAD_LEFT).'</a></td><td align="center">'.$rtn_date[$i].'</td><td style="padding-left:10px; padding-right:10px"><a href="" style="text-decoration:none" title="'.$rtn_cust[$i].'">'.$cust.'</a></td><td style="padding-left:10px; padding-right:10px">'.$rtn_itm[$i].'</td><td align="right">&nbsp;&nbsp;'.$rtn_qty[$i].'&nbsp;&nbsp;</td><td>&nbsp;&nbsp;'.ucfirst($rtn_by[$i]).'</td><td align="center">'.$dis_id[$i].'</td><td align="center">'.$dis_date[$i].'</td><td>&nbsp;&nbsp;'.ucfirst($dis_by[$i]).'&nbsp;&nbsp;</td><td>&nbsp;&nbsp;'.$rtn_st[$i].'&nbsp;&nbsp;</td></tr>';
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

<?php
                include_once  'template/footer.php';
?>