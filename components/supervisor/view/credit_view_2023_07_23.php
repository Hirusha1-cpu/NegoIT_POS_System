<?php
    include_once  'template/header.php';
    $store_report=$group_report=$salesman_report='ALL';
    $display_cr=$_GET['display'];

	if($message!=''){
		$color='#DD3333';
		print '<script type="text/javascript">document.getElementById("notifications").innerHTML='."'".'<span style="color:'.$color.'; font-weight:bold;font-size:12pt;">'.$message.'</span>'."'".';</script>';
	//print '<table align="center"><tr><td><span style="color:'.$color.'; font-weight:bold;font-size:12pt;">'.$_REQUEST['message'].'</span></td></tr></table>';
	}
?>
<script type="text/javascript">
	function filter(){
		$store =  document.getElementById('store').value;
		$group = document.getElementById('group').value;
		$town = document.getElementById('town').value;
		$salesman = document.getElementById('salesman').value;
		$display = document.getElementById('display').value;
		$sub_system = document.getElementById('sub_system').value;
		$as_of = document.getElementById('as_of').value;

		window.location = 'index.php?components=<?php print $components; ?>&action=credit&st='+$store+'&tw='+$town+'&gp='+$group+'&up='+$salesman+'&display='+$display+'&sub_system='+$sub_system+'&as_of='+$as_of;
	}
</script>

<form action="index.php?components=<?php print $components; ?>&action=credit" method="post">
	<table border="0" width="900px" align="center" height="100%" cellspacing="0" style="font-size:10pt; font-family:Calibri; border-radius: 5px;" bgcolor="#F0F0F0">
	<tr><td align="center"><strong>Display</strong>&nbsp;&nbsp;&nbsp;
			<select id="display" onchange="filter();">
			<option value="1" <?php if($display_cr==1) print 'selected="selected"'; ?> >-ALL-</option>
			<option value="2" <?php if($display_cr==2) print 'selected="selected"'; ?> >Outstanding Cust</option>
		</select>
	<?php if($components=='topmanager' || $components=='report'){ ?>
	</td><td width="30px"><td align="center"><strong>Sub System</strong>&nbsp;&nbsp;&nbsp;
			<select id="sub_system" onchange="filter();">
			<option value="all" >-ALL-</option>
			<?php for($i=0;$i<sizeof($sub_system_list);$i++){
				if(isset($_GET['sub_system'])){
					if($_GET['sub_system']==$sub_system_list[$i]){
						$select='selected="selected"';
						$subsys_report=$sub_system_names[$i];
					}else $select='';
				}else $select='';
				print '<option value="'.$sub_system_list[$i].'" '.$select.'>'.$sub_system_names[$i].'</option>';
			}
			?>
		</select>
	<?php }else{ print '<input type="hidden" id="sub_system" value="'.$sub_system.'" />'; } ?>
	</td><td width="30px"></td><td align="center"><strong>Asso. Shop/Store</strong>&nbsp;&nbsp;&nbsp;
			<select id="store" onchange="filter();">
			<option value="" >-ALL-</option>
			<?php for($i=0;$i<sizeof($store_id);$i++){
				if(isset($_GET['st'])){
					if($_GET['st']==$store_id[$i]){
						$select='selected="selected"';
						$store_report=$store_name[$i];
					}else $select='';
				}else $select='';
				print '<option value="'.$store_id[$i].'" '.$select.'>'.$store_name[$i].'</option>';
			}
			?>
		</select>
	</td><td width="30px"></td>
	<td align="center"><strong>Town</strong>&nbsp;&nbsp;&nbsp;
			<select id="town" onchange="filter();">
			<option value="" >-ALL-</option>
			<?php for($i=0;$i<sizeof($town_id);$i++){
				if(isset($_GET['tw'])){
					if($_GET['tw']==$town_id[$i]){
						$select='selected="selected"';
						$group_report=$town_name[$i];
					}else $select='';
				}else $select='';
				print '<option value="'.$town_id[$i].'" '.$select.'>'.$town_name[$i].'</option>';
			}
			?>
		</select>
	</td><td width="30px"></td>
	<td align="center"><strong>Group</strong>&nbsp;&nbsp;&nbsp;
			<select id="group" onchange="filter();">
			<option value="" >-ALL-</option>
			<?php for($i=0;$i<sizeof($gp_id);$i++){
				if(isset($_GET['gp'])){
					if($_GET['gp']==$gp_id[$i]){
						$select='selected="selected"';
						$group_report=$gp_name[$i];
					}else $select='';
				}else $select='';
				print '<option value="'.$gp_id[$i].'" '.$select.'>'.$gp_name[$i].'</option>';
			}
			?>
		</select>
	</td><td width="30px"></td>
	<?php if($components=='report' || $components=='supervisor'){ ?>
	<td align="center"><strong title="Associated Salesman">Salesman</strong>&nbsp;&nbsp;&nbsp;
			<select id="salesman" onchange="filter();">
			<option value="" >-ALL-</option>
			<?php for($i=0;$i<sizeof($up_id);$i++){
				if(isset($_GET['up'])){
					if($_GET['up']==$up_id[$i]){
						$select='selected="selected"';
						$salesman_report=$up_name[$i];
					}else $select='';
				}else $select='';
				print '<option value="'.$up_id[$i].'" '.$select.'>'.ucfirst($up_name[$i]).'</option>';
			}
			?>
		</select>
	</td>
	<?php }else{ ?>
		<input type="hidden" id="salesman" />
	<?php } ?>
	</tr>
	<tr><td colspan="8"><br> &nbsp;&nbsp;&nbsp;Note: This report shows the amount of Credit per Customer (Unrealized cheques are excluded) <hr /></td>
	<td colspan="3"><strong>As of</strong>&nbsp;&nbsp;&nbsp;<input type="date" name="as_of" id="as_of" value="<?php print $as_of; ?>" onchange="filter();"/></td></tr>
	</table>
</form>

<div id="printheader" style="display:none" >
	<h2 align="center" style="color:navy"><?php print $inf_company; ?></h2>
	<h3 align="center" style="color:#333399; text-decoration:underline">Credit Report</h3>
	<table style="font-size:12pt" border="1" cellspacing="0">
		<tr><td style="background-color:#C0C0C0; padding-left:10px">&nbsp;&nbsp;Shop / Store &nbsp;&nbsp;&nbsp;</td><td style="background-color:#EEEEEE; padding-left:10px">&nbsp;&nbsp;<?php print $store_report; ?>&nbsp;&nbsp;</td></tr>
		<tr><td style="background-color:#C0C0C0; padding-left:10px">&nbsp;&nbsp;Group &nbsp;&nbsp;&nbsp;</td><td style="background-color:#EEEEEE; padding-left:10px">&nbsp;&nbsp;<?php print $group_report; ?>&nbsp;&nbsp;</td></tr>
		<tr><td style="background-color:#C0C0C0; padding-left:10px">&nbsp;&nbsp;Associated Salesman &nbsp;&nbsp;&nbsp;</td><td style="background-color:#EEEEEE; padding-left:10px">&nbsp;&nbsp;<?php print $salesman_report; ?>&nbsp;&nbsp;</td></tr>
	</table><br />
	<p>Note: This report shows the amount of Credit per Customer</p><hr>
</div>

<br />
<div id="print">
	<table align="center" height="100%" border="1" cellspacing="0" style="font-size:10pt; font-family:Calibri">
		<tr>
			<?php
				$size = 2;
				$size += sizeof($days);
			?>
			<td colspan="<?php echo $size; ?>" style="border:0; background-color:black; color:white; font-weight:bold"></td>
		</tr>
		<tr bgcolor="#E5E5E5">
			<th>Customer</th>
			<?php
				for($j=0;$j<sizeof(array_reverse($days));$j++){
					print '<th width="100px">Up to '.$days[$j].'+</th>';

				}
			?>
			<th width="100px" title="Calculation of 'Up to Now'&#13;Invoice Total - Cash Payments - All Chque Payments">Up to Now</th>
		</tr>
		<?php
		// updated by nirmal 12_07_2023
		for($i=0;$i<sizeof($cust_id);$i++){
			if($display_cr==2){
				$out = false;
				for($j=0;$j<sizeof($days);$j++){
					$day = $days[$j];
					if($cust_cr_balance0[$i] != 0){
						$out = true;
					}
				}
				if($out){
					print '<tr bgcolor="#F5F5F5"><td style="padding-right:10px; padding-left:10px">'.ucfirst($cust_name[$i]).'</td>';
					for($k=0;$k<sizeof($days);$k++){
						$day = $days[$k];
						print '<td align="right" style="padding-right:10px">'.number_format(${'cust_cr_balance' . $day}[$i],$decimal).'</td>';
					}
					print '<td align="right" style="padding-right:10px">'.number_format($cust_cr_balance0[$i],$decimal).'</td></tr>';
				}
			}else{
				print '<tr bgcolor="#F5F5F5"><td style="padding-right:10px; padding-left:10px">'.ucfirst($cust_name[$i]).'</td>';
				for($k=0;$k<sizeof($days);$k++){
					$day = $days[$k];
					print '<td align="right" style="padding-right:10px">'.number_format(${'cust_cr_balance' . $day}[$i],$decimal).'</td>';
				}
				print '<td align="right" style="padding-right:10px">'.number_format($cust_cr_balance0[$i],$decimal).'</td></tr>';
			}
		}
		print '<tr>
				<th align="left" style="padding-right:10px; padding-left:10px">Total Amount</th>';
				for($k=0;$k<sizeof($days);$k++){
					$day = $days[$k];
					print '<th align="right" style="padding-right:10px">'.number_format(${'credit_total' . $day},$decimal).'</th>';
				}
				print '<th align="right" style="padding-right:10px">'.number_format($credit_total0,$decimal).'</th></tr>';
		?>
	</table>
</div>

<table align="center">
	<tr>
		<td align="center">
			<div style="background-color:#6699FF; border:medium; border-color:black; width:80px; border-radius: 15px;">
				<a class="shortcut-button" onclick="printdiv('print','printheader')" href="#">
				<span style="text-decoration:none; font-family:Arial; color:navy;">
					<img src="images/print.png" alt="icon" /><br />Print
				</span></a>
			</div>
		</td>
	</tr>
</table>
<br />

<?php
    include_once  'template/footer.php';
?>