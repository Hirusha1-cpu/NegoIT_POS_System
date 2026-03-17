<?php
    include_once  'template/m_header.php';
?>
<!-- Scripts -->
<link rel="stylesheet" href="plugin/jquery/css/jquery.ui.all.css" />
<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
<script src="plugin/jquery/ui/jquery.ui.core.js"></script>
<script src="plugin/jquery/ui/jquery.ui.widget.js"></script>
<script src="plugin/jquery/ui/jquery.ui.position.js"></script>
<script src="plugin/jquery/ui/jquery.ui.autocomplete.js"></script>
<link rel="stylesheet" href="plugin/jquery/css/demos.css" />

<script type="text/javascript">
	$(function() {
		var availableTags1 = [<?php for ($x=0;$x<sizeof($bnk_name);$x++){ print '"'.$bnk_name[$x].'",'; } ?>	];
		<?php
		for($i=0;$i<sizeof($py_id2);$i++){
			print '$( "#'.$py_id2[$i].'_bnk'.'" ).autocomplete({	source: availableTags1	});';
		}
		?>
	});
	// 2021_11_11 - By N
	function clearChqueAjax($id){
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
						if(returntext=='Done'){
							document.getElementById($id+'_div').innerHTML='<span style="color:green; font-weight:bold; display: inherit; padding-top:3px; text-align: center;font-size: 14px;">Done</span>';
							document.getElementById('notifications').innerHTML='<span style="color:green; font-weight:bold;">The Chque was Cleared Successfully!</span>';
						}else{
							document.getElementById($id+'_div').innerHTML=$btn;
							document.getElementById('notifications').innerHTML='<span style="color:red; text-align: center; font-weight:bold;">'+returntext+'</span>';
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
    function filterChquePendingFinalyze(){
		$components=document.getElementById("components").value;
		$dateto=document.getElementById("dateto").value;
		$st=document.getElementById("st").value;
		$sm=document.getElementById("sm").value;
		window.location = 'index.php?components='+$components+'&action=chque_pending_finalyze&dateto='+$dateto+'&st='+$st+'&sm='+$sm;	
	}
</script>
<!--// Scripts -->

<!--// Start of Check Management  -->
<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px" /></div>
<div class="w3-container" style="margin-top:75px">
	<div id="notifications"></div>
	<hr>
	<div class="w3-row">
	  	<div class="w3-col s3"></div>
	  	<div class="w3-col">
			<form>
				<input type="hidden" id="components" name="components" value="<?php print $_GET['components']; ?>" />
                <input type="hidden" name="action" value="chque_pending_finalyze" />
				<table border="0" bgcolor="#F0F0F0" align="center" width="100%" cellspacing="0"  style="font-size:10pt; font-family:Calibri; padding:10px">
					<tbody>
						<tr>
							<td width="100px" align="left" class="shipmentTB3"><strong>Up To : </strong></td>
							<td>
								<input type="date" id="dateto" name="dateto" style="width:130px" value="<?php print $todate; ?>" />
							</td>
						</tr>
						<tr>
							<td class="shipmentTB3">
								<strong>Collected Salesman</strong>
							</td>
							<td>
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
							</td>
						</tr>
						<tr>
							<td class="shipmentTB3"><strong>Shop/Store : </strong></td>
							<td>
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
							</td>
						</tr>
						<tr>
							<td colspan="3" align="center">
								<input style="width:60px; height:30px; margin-top:10px" type="submit" value="GET"/>
							</td>
						</tr>
					</tbody>
				</table>
			</form>
		</div>
	  	<div class="w3-col">
			<hr />
			<?php if(sizeof($py_id2)>0){ ?>
				<table align="center" height="100%" width="100%" style="font-size:10pt; font-family:Calibri;max-width: fit-content;overflow-x: auto;display: block;">
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
						<th width="100px">Shop</th>
						<?php if($_GET['components']=='manager'){ ?><th>Deposited Bank</th><th>Deposited Date</th><th></th><?php } ?>
						<th>#</th>
					</tr>
					<?php
					for($i=0;$i<sizeof($py_id2);$i++){
						print '<tr bgcolor="#EEEEEE">
								<td class="shipmentTB3" align="center">'.($i+1).'</td>
								<td class="shipmentTB3">'.ucfirst($customer2[$i]).'</td>
								<td class="shipmentTB3">'.$cheque_no2[$i].'</td>
								<td align="center"><a title="Payment was done on '.$payment_date2[$i].'" href="#">'.$chq_date2[$i].'</a></td>
								<td align="right"  class="shipmentTB3">'.number_format($payment_amount2[$i]).'</td>
								<td  class="shipmentTB3">'.ucfirst($payment_salesman2[$i]).'</td>
								<td  class="shipmentTB3">'.$payment_store2[$i].'</td>';
							if($_GET['components']=='manager'){
								print "<td><input type='text' name='$py_id2[$i]_bnk' id='$py_id2[$i]_bnk' placeholder='Bank Name'/></td>";
								print "<td><input type='date' name='$py_id2[$i]_pydate' id='$py_id2[$i]_pydate' /></td><td>
								<div id='$py_id2[$i]_div'><input type='button' value='Submit' onclick=".'"clearChqueAjax('.$py_id2[$i].')"'." /></div></td>";
							}
						print '<td class="shipmentTB3" align="center">'.($i+1).'</td>';
						print '</tr>';
					}
					?>
				</table>
				<br />
				<hr />
			<?php } ?>
		</div>	
	</div>
</div>
<!--// End of Check Management  -->

</div>
<hr>
<br />
<?php
    include_once  'template/m_footer.php';
?>
