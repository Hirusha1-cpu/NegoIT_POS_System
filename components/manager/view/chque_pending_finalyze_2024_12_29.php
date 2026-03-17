<?php
    include_once  'template/header.php';
    $store_report='ALL';
    $menu_components=$_GET['components'];
	$decimal = getDecimalPlaces(1);
?>

<link rel="stylesheet" href="plugin/jquery/css/jquery.ui.all.css" />
<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
<script src="plugin/jquery/ui/jquery.ui.core.js"></script>
<script src="plugin/jquery/ui/jquery.ui.widget.js"></script>
<script src="plugin/jquery/ui/jquery.ui.position.js"></script>
<script src="plugin/jquery/ui/jquery.ui.autocomplete.js"></script>
<link rel="stylesheet" href="plugin/jquery/css/demos.css" />

<!-- Scripts -->
<script type="text/javascript">
	$(function() {
		var availableTags1 = [<?php for ($x=0;$x<sizeof($bnk_name);$x++){ print '"'.$bnk_name[$x].'",'; } ?>	];
		<?php
		for($i=0;$i<sizeof($py_id2);$i++){
			print '$( "#'.$py_id2[$i].'_bnk'.'" ).autocomplete({	source: availableTags1	});';
		}
		?>
	});
	// 2021_11_10 - By Nirmal
	function clearChqueAjax($id){
		var check= confirm("Do you really want to deposit this cheque?");
		if (check== true){
			$bnk=document.getElementById($id+'_bnk').value;
			$pydate=document.getElementById($id+'_pydate').value;
			$btn=document.getElementById($id+'_div').innerHTML;
			$components=document.getElementById("components").value;
			if(($bnk!='')&&($pydate!='')){
					document.getElementById($id+'_div').innerHTML=document.getElementById('loading').innerHTML;
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() {
						if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
							var returntext=this.responseText;
							// Check if returntext contains '|' character
							if (returntext.includes('|')) {
								var parts = returntext.split('|');
								var $msg = parts[0];
								var $qb_msg = parts[1];

								// Check if $msg is 'Done'
								if ($msg === 'Done') {
									document.getElementById($id+'_div').innerHTML='<span style="color:green; font-weight:bold; display: inherit; padding-top:3px; text-align: center;font-size: 14px;">Done</span>';
									document.getElementById('notifications').innerHTML='<span style="color:green; font-weight:bold;">The Chque was Cleared Successfully! '+$qb_msg+'</span>';
								} else {
									document.getElementById($id+'_div').innerHTML=$btn;
									document.getElementById('notifications').innerHTML='<span style="color:red; font-weight:bold;">'+$msg+' '+$$qb_msg+'</span>';
								}
							}
						}
					};
					xmlhttp.open("POST", "index.php?components="+$components+"&action=clear_chque", true);
					xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
					xmlhttp.send('id='+$id+'&bnk='+$bnk+'&pydate='+$pydate);
			}else{
				alert('Please fill the Bank name and Deposited date');
			}
		}
	}

	function filterChquePendingFinalyze(){
		$components=document.getElementById("components").value;
		$dateto=document.getElementById("dateto").value;
		$st=document.getElementById("st").value;
		$sm=document.getElementById("sm").value;
		window.location = 'index.php?components='+$components+'&action=chque_pending_finalyze&dateto='+$dateto+'&st='+$st+'&sm='+$sm;
	}
</script>
<!--// Scripts -->

<!-- Notifications -->
<table align="center" style="font-size:11pt">
	<tr><td>
		<?php
			if(isset($_REQUEST['message'])){
				if($_REQUEST['re']=='success') $color='green'; else $color='red';
			print '<span style="color:'.$color.'; font-weight:bold;">'.$_REQUEST['message'].'</span><br /><br />';
			}
		?></td>
	</tr>
</table>
<!--// Notifications -->
<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px; padding-top:6px;"/></div>
<form action="index.php" method="get" onsubmit="return validateDateRange()" >
	<input type="hidden" id="components" name="components" value="<?php print $menu_components; ?>" />
	<input type="hidden" name="action" value="chque_pending_finalyze" />
	<table bgcolor="#F0F0F0" align="center" height="100%" cellspacing="0"  style="font-size:10pt; font-family:Calibri">
	<tr>
	<td width="80px" align="left"  class="shipmentTB3"><strong>Up To : </strong></td><td width="220px">
	<input type="date" id="dateto" name="dateto" style="width:130px" value="<?php print $todate; ?>" />
	<input style="width:60px; height:40px" type="submit" value="GET" />
	</td>
	<td><strong>Associated <br/>Shop/Store</strong></td><td>
			<select id="st" name="st" onchange="filterChquePendingFinalyze()">
			<option value="" >-ALL-</option>
			<?php for($i=0;$i<sizeof($st_id);$i++){
				if(isset($_GET['st'])){
					if($_GET['st']==$st_id[$i]){
						$select='selected="selected"';
						$store_report=$st_name[$i];
					}else $select='';
				}else $select='';
				print '<option value="'.$st_id[$i].'" '.$select.'>'.$st_name[$i].'</option>';
			}
			?>
		</select>
	</td><td width="50px"></td><td><strong>Collected<br />Salesman</strong></td><td>
			<select id="sm" name="sm" onchange="filterChquePendingFinalyze()">
			<option value="" >-ALL-</option>
			<?php for($i=0;$i<sizeof($sm_id);$i++){
				if(isset($_GET['sm'])){
					if($_GET['sm']==$sm_id[$i]){
						$select='selected="selected"';
						$salesman_report=$sm_name[$i];
					}else $select='';
				}else $select='';
				print '<option value="'.$sm_id[$i].'" '.$select.'>'.ucfirst($sm_name[$i]).'</option>';
			}
			?>
		</select>
	</td></tr>
	<tr><td colspan="5"></td><td colspan="3" align="right"><input type="button" onclick="window.location = 'index.php?components=<?php print $menu_components; ?>&action=clear_chque_list&year=<?php print date("Y",time()); ?>'" value="List of Cleared Cheques" /></td></tr>
	<tr><td colspan="8"><hr /></td></tr>
	</table>
</form>

<br />

<?php if(sizeof($py_id2)>0){ ?>
<table align="center" height="100%" style="font-size:10pt; font-family:Calibri">
	<tr bgcolor="#AAAAAA">
		<td colspan="11" style="color:white; font-weight:bold">&nbsp;&nbsp;List of Realized cheques as of <?php print $todate; ?></td>
	</tr>
	<tr bgcolor="#CCCCCC">
		<th>#</th>
		<th width="200px">Customer</th>
		<th width="120px">Cheque</th>
		<th width="80px">Date</th>
		<th width="100px">Amount</th>
		<th width="100px">Salesman</th>
		<th width="100px">Associated Shop</th>
		<?php if($menu_components=='manager' || $menu_components=='fin'){ ?><th>Deposited Bank</th><th>Deposited Date</th><th></th><?php } ?>
		<th>#</th>
	</tr>
	<?php
		for($i=0;$i<sizeof($py_id2);$i++){
			print '<tr bgcolor="#EEEEEE">
					<td class="shipmentTB3" align="center">'.($i+1).'</td>
					<td class="shipmentTB3">'.ucfirst($customer2[$i]).'</td>
					<td class="shipmentTB3">'.$cheque_no2[$i].'</td>
					<td align="center"><a title="Payment was done on '.$payment_date2[$i].'" href="#">'.$chq_date2[$i].'</a></td>
					<td align="right"  class="shipmentTB3">'.number_format($payment_amount2[$i],$decimal).'</td>
					<td  class="shipmentTB3">'.ucfirst($payment_salesman2[$i]).'</td>
					<td  class="shipmentTB3">'.$payment_store2[$i].'</td>';
					if($menu_components=='manager' || $menu_components=='fin'){
						print "<td><input type='text' name='$py_id2[$i]_bnk' id='$py_id2[$i]_bnk' placeholder='Bank Name'/></td>";
						print "<td><input type='date' name='$py_id2[$i]_pydate' id='$py_id2[$i]_pydate' /></td>
						<td><div id='$py_id2[$i]_div'><input type='button' value='Submit' id='$py_id2[$i]_btn' onclick=".'"clearChqueAjax('.$py_id2[$i].')"'." /></div></td>";
					}
			print '<td class="shipmentTB3" align="center">'.($i+1).'</td>';
			print '</tr>';
		}
	?>
</table>
<br />
<?php } ?>


<?php
    include_once  'template/footer.php';
?>