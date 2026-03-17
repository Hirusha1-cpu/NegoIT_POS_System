<?php
                include_once  'template/header.php';
?>
<script type="text/javascript">
	function filterInOut($case){
		$from_date=document.getElementById('from_date').value;
		$to_date=document.getElementById('to_date').value;
		$inout_user=document.getElementById('inout_user').value;
		window.location = 'index.php?components=hr&action=inout_report&from_date='+$from_date+'&to_date='+$to_date+'&inout_user='+$inout_user;
	}
	
</script>
<!-- -------------------------------------------------------------------------------------------------------------------- -->
<div id="loading" style="display:none"><img src="images/loading.gif" style="width:70px" /><br><span style="color:maroon; vertical-align:middle">Please Wait</span></div>

<table align="center" style="font-size:12pt"><tr><td>
<?php 
	if(isset($_REQUEST['message'])){
		if($_REQUEST['re']=='success') $color='green'; else $color='#DD3333';
		print '<script type="text/javascript">document.getElementById("notifications").innerHTML='."'".'<span style="color:'.$color.'; font-weight:bold;font-size:12pt;">'.$_REQUEST['message'].'</span>'."'".';</script>';
	}
?></td></tr></table>



<table align="center"><tr><td valign="top">
<tr><td height="10px" class="shipmentTB4" style="font-family:Calibri; border-radius:5px" bgcolor="silver">
	<form id="report_form" method="get" action="index.php" >
		<input type="hidden" name="components" value="hr" />
		<input type="hidden" name="action" value="inout_report" />
		<table width="100%"><tr><td>From Date <input type="date" id="from_date" name="from_date" value="<?php print $from_date; ?>" /></td>
		<td>To Date <input type="date" id="to_date" name="to_date" value="<?php print $to_date; ?>" /></td><td width="50px"></td>
		<td width="160px">&nbsp;Employee
			<select id="inout_user" name="inout_user" onchange="filterInOut()">
				<option value="all" >-SELECT-</option>
				<?php 
				$header_le_user='';
				for($i=0;$i<sizeof($check_userid);$i++){
						if($check_userid[$i]==$inout_user){ $select='selected="selected"'; $header_le_user=$check_username[$i]; }else $select='';
						print '<option value="'.$check_userid[$i].'" '.$select.'>'.$check_username[$i].'</option>';
					}	?>
			</select>
		</td><td><input type="submit" value="Search" /></td></tr>
		</table>
	</form>
</td></tr>
<tr><td>
<!-- -----------------------------------LEAVE LOG------------------------------------------- -->


	<table align="center" bgcolor="gray" style="border-radius: 10px; font-family:Calibri"><tr><td>
		<table border="0" id="table1" cellspacing="1" bgcolor="white"  align="center" border="0"  style="font-size:12pt; font-family:Calibri">
			<tr bgcolor="#467898" style="color:#F5F5F5"><th class="shipmentTB4">Employee</th><th class="shipmentTB4">Date</th><th class="shipmentTB4">In Time</th><th class="shipmentTB4">In City</th><th class="shipmentTB4">Out Time</th><th class="shipmentTB4">Out City</th></tr>
			<?php 
			for($i=0;$i<sizeof($cio_id);$i++){
				if(($i%2)==0) $color='#DDDDDD'; else $color='#EEEEEE';
				print '<tr bgcolor="'.$color.'" ><td class="shipmentTB4"><a href="index.php?components=hr&action=show_map1&id='.$cio_id[$i].'" target="_blank" style="text-decoration:none">'.$cio_user[$i].'</a></td><td class="shipmentTB4">'.substr($cio_in_date[$i],0,10).'</td><td class="shipmentTB4">'.substr($cio_in_date[$i],11,5).'</td><td class="shipmentTB4">'.$cio_in_city[$i].'</td><td class="shipmentTB4">'.substr($cio_out_date[$i],11,5).'</td><td class="shipmentTB4">'.$cio_out_city[$i].'</td></tr>';
			}
			?>
		</table>
	</td></tr></table>
</td></tr>
<!-- -----------------------------------Print LOG------------------------------------------- -->
<tr><td>
	<div id="printheader" style="display:none" >
		<div style="color:navy; font-size:16pt; font-family:Calibri; font-weight:bold"><?php print $inf_company; ?></div>
		<div align="center" style="color:#3333FF; text-decoration:underline; font-family:Calibri; font-size:14pt">Sales Rep In-Out Report</div>
		<table border="1" align="center" cellspacing="0" width="600px" style="font-family:Calibri; font-size:12pt">
		<tr><td>&nbsp;&nbsp;<strong>From Date</strong>&nbsp;&nbsp;</td><td>&nbsp;&nbsp;<?php print $from_date; ?>&nbsp;&nbsp;</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>To Date</strong>&nbsp;&nbsp;</td><td>&nbsp;&nbsp;<?php print $to_date; ?>&nbsp;&nbsp;</td></tr>
		<tr><td>&nbsp;&nbsp;<strong>Employee</strong>&nbsp;&nbsp;</td><td>&nbsp;&nbsp;<?php print $header_le_user; ?>&nbsp;&nbsp;</td><td></td><td></td></tr>
		</table>
		<hr />
	</div>
	<div id="print" style="display:none">
		<table id="table1" cellspacing="0" bgcolor="white" align="center" border="1"  style="font-size:10pt; font-family:Calibri">
			<tr><?php if($inout_user=='all') print '<th>Employee</th>'; ?><th width="75px">Date</th><th>In Time</th><th>In City</th><th>Out Time</th><th>Out City</th></tr>
			<?php 
			for($i=0;$i<sizeof($cio_id);$i++){
				if(($i%2)==0) $color='#DDDDDD'; else $color='#EEEEEE';
				print '<tr bgcolor="'.$color.'" >';
				if($inout_user=='all') print '<td><a href="index.php?components=hr&action=show_map1&id='.$cio_id[$i].'" target="_blank" style="text-decoration:none">'.$cio_user[$i].'</a></td>';
				print '<td align="center">'.substr($cio_in_date[$i],0,10).'</td><td align="center">'.substr($cio_in_date[$i],11,5).'</td><td class="shipmentTB4" style="font-size:8pt">'.$cio_in_city[$i].'</td><td align="center">'.substr($cio_out_date[$i],11,5).'</td><td class="shipmentTB4" style="font-size:8pt">'.$cio_out_city[$i].'</td></tr>';
			}
			?>
		</table>
	</div>
</td></tr>
<tr><td align="center">
	<br />
	<div class="prtbutton1">
	<a class="shortcut-button" style="text-decoration:none; font-family:Arial; color:white;" onclick="printdiv('print','printheader')" href="#">
		<img src="images/print.png" alt="icon" /><br />
		Print
	</a>
	</div>

</td></tr>
</table>	

<?php
                include_once  'template/footer.php';
?>