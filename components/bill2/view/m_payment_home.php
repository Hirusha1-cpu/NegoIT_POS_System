<?php
	include_once  'template/m_header.php';
	$bill_salesman=$_COOKIE['user_id'];
	if(isset($_GET['s'])){ if($_GET['s']!='')  $bill_salesman=$_GET['s']; }
?>
<head>
<style>
#cust-list{float:left;list-style:none;margin-top:-3px;padding:0;width:190px;position: absolute;}
#cust-list li{padding: 10px; background: #F8F8F8; border-bottom: #bbb9b9 1px solid;}
#cust-list li:hover{background:#ece3d2;cursor: pointer;}
#search-cust{padding: 10px;border: #a8d4b1 1px solid;border-radius:4px;}
#mob-list{float:left;list-style:none;margin-top:-3px;padding:0;width:190px;position: absolute;}
#mob-list li{padding: 10px; background: #F8F8F8; border-bottom: #bbb9b9 1px solid;}
#mob-list li:hover{background:#ece3d2;cursor: pointer;}
#search-mob{padding: 10px;border: #a8d4b1 1px solid;border-radius:4px;}
#sm-list{float:left;list-style:none;margin-top:-3px;padding:0;width:190px;position: absolute;}
#sm-list li{padding: 10px; background: #F8F8F8; border-bottom: #bbb9b9 1px solid;}
#sm-list li:hover{background:#ece3d2;cursor: pointer;}
#search-sm{padding: 10px;border: #a8d4b1 1px solid;border-radius:4px;}
</style>
	
<script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
<script>
	$(document).ready(function(){
		$("#search-cust").keyup(function(){
			if(document.getElementById('search-cust').value.length>2){
				$.ajax({
				type: "POST",
				url: "index.php?components=bill2&action=cust-list",
				data:'keyword='+$(this).val(),
				beforeSend: function(){
					$("#search-cust").css("background","#FFF url(images/LoaderIcon.gif) no-repeat 165px");
				},
				success: function(data){
					$("#suggesstion-cust").show();
					$("#suggesstion-cust").html(data);
					$("#search-cust").css("background","#FFF");
				}
				});
			}
		});
		$("#search-mob").keyup(function(){
			if(document.getElementById('search-mob').value.length>3){
				$.ajax({
				type: "POST",
				url: "index.php?components=bill2&action=mob-list",
				data:'keyword='+$(this).val(),
				beforeSend: function(){
					$("#search-mob").css("background","#FFF url(images/LoaderIcon.gif) no-repeat 165px");
				},
				success: function(data){
					$("#suggesstion-mob").show();
					$("#suggesstion-mob").html(data);
					$("#search-mob").css("background","#FFF");
				}
				});
			}
		});
		$("#search-sm").keyup(function(){
			if(document.getElementById('search-sm').value.length>2){
				$.ajax({
				type: "POST",
				url: "index.php?components=bill2&action=sm-list",
				data:'keyword='+$(this).val(),
				beforeSend: function(){
					$("#search-sm").css("background","#FFF url(images/LoaderIcon.gif) no-repeat 165px");
				},
				success: function(data){
					$("#suggesstion-sm").show();
					$("#suggesstion-sm").html(data);
					$("#search-sm").css("background","#FFF");
				}
				});
			}
		});
	});

	function selectCust(val) {
		$("#search-cust").val(val);
		$("#suggesstion-cust").hide();
		getCustData('name',val);
	}

	function selectMob(val) {
		$("#search-mob").val(val);
		$("#suggesstion-mob").hide();
		getCustData('mob',val);
	}

	function selectSM(val) {
		$("#search-sm").val(val);
		$("#suggesstion-sm").hide();
		getSM(val);
	}

	function getCustData($case,$val){
		if($case=='name') $out='div_cmob';
		if($case=='mob') $out='div_cname';
		document.getElementById($out).innerHTML=document.getElementById('loading').innerHTML;
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
			if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				var myObj = JSON.parse(xmlhttp.responseText);
				document.getElementById($out).innerHTML='';
				document.getElementById('cust_id').value=myObj.cust_id;
				if($case=='name') document.getElementById('search-mob').value=myObj.cust_mobile;
				if($case=='mob') document.getElementById('search-cust').value=myObj.cust_name;
			}
		};
		xmlhttp.open("POST", "index.php?components=bill2&action=more_cust", true);
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xmlhttp.send('case='+$case+'&val='+$val);
	}

	function getSM($val){
		document.getElementById('div_sm').innerHTML=document.getElementById('loading').innerHTML;
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
			if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					var returntext=this.responseText;
					document.getElementById('div_sm').innerHTML='';
					document.getElementById('sm_id').value=returntext;
				}
			};
		xmlhttp.open("POST", "index.php?components=bill2&action=more_sm", true);
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xmlhttp.send('val='+$val);
	}

	function GotoPaymentForm(){
		$cust_id=document.getElementById('cust_id').value;
		$sm_id=document.getElementById('sm_id').value;
		if($cust_id==''){
			alert('Please Select the Customer or Phone Number');
		}else{
			document.getElementById('div_submit').innerHTML=document.getElementById('loading').innerHTML;
			window.location = 'index.php?components=bill2&action=payment_form&cust='+$cust_id+'&s='+$sm_id;
		}
	}
</script>
</head>

<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px" /></div>

<div class="w3-container" style="margin-top:75px">
	<table align="center">
		<tr>
			<td>
				<div id="notifications"></div>
			</td>
		</tr>
	</table>
	<?php
		if(isset($_REQUEST['message'])){
			if($_REQUEST['re']=='success') $color='green'; else $color='#DD3333';
			print '<script type="text/javascript">document.getElementById("notifications").innerHTML='."'".'<span style="color:'.$color.'; font-weight:bold;font-size:12pt;">'.$_REQUEST['message'].'</span>'."'".';</script>';
		}
	?>
	<hr>
	<div class="w3-row">
		<div class="w3-col s3">
		</div>
		<div class="w3-col">
			<table align="center" style="font-size:11pt">
				<tr>
					<td style="vertical-align:top;" align="center">
						<input type="hidden" id="cust_id" name="cust_id" value="" />
						<input type="hidden" id="sm_id" name="sm_id" value="" />
						<input type="hidden" name="salesman" id="salesman" value="<?php print $bill_salesman; ?>" />
						<input type="hidden" id="gps_x" name="gps_x" value="0" />
						<input type="hidden" id="gps_y" name="gps_y" value="0" />
						<table align="center" bgcolor="#E5E5E5" style="border-radius: 15px;">
							<tr>
								<td colspan="5">
									<br />
								</td>
							</tr>
							<tr><td width="50px"></td><td style="font-size:12pt">Customer</td><td colspan="2">
								<div class="frmSearch">
								<input type="text" id="search-cust" name="cust_name" placeholder="Customer Name" autocomplete="off" />
								<div id="suggesstion-cust"></div>
								</div>
							</td><td width="50px"><div id="div_cname"></div></td></tr>
							<tr><td width="50px"></td><td style="font-size:12pt">Mobile</td><td colspan="2">
								<div class="frmSearch">
								<input type="text" id="search-mob" name="cust_mob" autocomplete="off" />
								<div id="suggesstion-mob"></div>
								</div>
							</td><td width="50px"><div id="div_cmob"></div></td></tr>
							<?php if($systemid==1 || $systemid==4 || $systemid==10 || $systemid==15){
								print '<tr><td width="50px"></td><td style="font-size:12pt">Salesman</td><td colspan="2">
											<div class="frmSearch">
											<input type="text" id="search-sm" name="bill_sm" />
											<div id="suggesstion-sm"></div>
											</div>
									</td><td width="50px"><div id="div_sm"></div></td></tr>';
							}else{
								print '<tr><td colspan="5"><input type="hidden" id="search-sm" name="bill_sm" value="" /></td></tr>';
							} ?>
							<tr><td colspan="5" height="10px" align="center"><div id="div_submit"><input type="button" value="Get Data" style="width:100px; height:50px" onclick="GotoPaymentForm()" /></div></td></tr>
							<tr><td colspan="5" height="10px"></td></tr>
						</table>
					</td>
					<td width="10px"></td>
					<td style="vertical-align:top;">
						<div id="landscape" style="vertical-align:top" ></div>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<hr>
	<div id="portrait">
		<div class="w3-row">
			<div class="w3-col s3"></div>
				<div style="background-color:#EEEEEF; border-radius: 15px; padding-left:10px; padding-right:10px">
					<br />
					<table align="center" style="font-family:Calibri; font-size:10pt" >
						<tr>
							<td width="18px" bgcolor="#009900"></td>
							<td>Cash</td>
							<td width="20px"></td>
							<td width="18px" bgcolor="#CC3399"></td>
							<td>Card</td>
							<td width="20px"></td>
							<td width="18px" bgcolor="#00AAAA"></td>
							<td>Bank</td>
							<td width="20px"></td>
							<td width="18px" bgcolor="blue"></td>
							<td>Cheque</td>
						</tr>
					</table>
					<?php
					if(isset($_GET['cust'])){
						print '<table align="center" height="100%" style="font-family:Calibri; font-size:11pt">';
						print '<tr><td colspan="3"><h3 class="style2" align="center">Latest Payments</h3></td></tr>';
						print '<tr style="background-color:#467898;color :white;"><th>Date</th><th>Payment ID</th><th>Amount</th></tr>';
						for($i=0;$i<sizeof($payment_date);$i++){
							if(($i%2)==0) $color='#FAFAFA'; else $color='#DDDDDD';
							print '<tr style="font-size:10pt; color:'.$pay_color[$i].'; background-color:'.$color.';"><td width="100px" height:20px" title="'.$full_data[$i].'">&nbsp;&nbsp;'.$payment_date[$i].'&nbsp;&nbsp;</td><td title="'.$full_data[$i].'">&nbsp;&nbsp;<a style="color:'.$pay_color[$i].'" href="index.php?components=bill2&action=finish_payment&id='.$payment_id[$i].'">'.str_pad($payment_id[$i], 7, "0", STR_PAD_LEFT).'</a>&nbsp;&nbsp;</td><td align="right" title="'.$full_data[$i].'">&nbsp;&nbsp;'.number_format($payment_amount[$i]).'&nbsp;&nbsp;</td></tr>';
						}
						print '</table>';
					}else{ ?>
						<table style="font-family:Calibri; font-size:11pt" align="center" width="300px">
							<tr>
								<td colspan="3">
									<form id="search_form1" method="post" action="index.php?components=bill2&action=search_pay">
										<input type="number" style="width:200px" name="search1" id="search1" placeholder="Payment Number" />
										<a onclick="document.getElementById('search_form1').submit();" style="cursor:pointer; float:right">
										<img src="images/search.png" style="width:30px; vertical-align:middle" /></a>
									</form>
								</td>
							</tr>
							<tr>
								<td colspan="3">
									<form id="search_form2" method="post" action="#">
										<input type="number" style="width:200px" name="invoice_id" placeholder="Cheque Payments by Invoice No" />
										<a onclick="document.getElementById('search_form2').submit();" style="cursor:pointer; float:right">
										<img src="images/search.png" style="width:30px; vertical-align:middle" /></a>
									</form>
									<br>
								</td>
							</tr>
							<tr style="background-color:#467898;color:white;">
								<th>Date</th>
								<th>Payment ID</th>
								<th>Amount</th>
							</tr>
								<?php 
								$total=0;
								for($i=0;$i<sizeof($sh_payid);$i++){
									if(($i%2)==0) $color='#FAFAFA'; else $color='#DDDDDD'; 
									if($chq_return[$i]==0)$total+=$sh_amount[$i];
									print '<tr style="color:'.$sh_color[$i].'; background-color:'.$color.'"><td>&nbsp;&nbsp;'.$sh_date[$i].'&nbsp;&nbsp;</td><td>&nbsp;&nbsp;<a style="color:'.$sh_color[$i].'" href="index.php?components=bill2&action=finish_payment&id='.$sh_payid[$i].'">'.str_pad($sh_payid[$i], 7, "0", STR_PAD_LEFT).'</a>&nbsp;&nbsp;</td><td align="right">&nbsp;&nbsp;'.number_format($sh_amount[$i]).'&nbsp;&nbsp;</td></tr>';
								} 
									print '<tr style="background-color:#DDDDDD"><td colspan="2">&nbsp;&nbsp;Invoice Amount</td><td align="right">&nbsp;&nbsp;'.number_format($bill_total).'&nbsp;&nbsp;</td></tr>';;
									print '<tr style="background-color:#DDDDDD"><td colspan="2">&nbsp;&nbsp;Remaining Amount</td><td align="right">&nbsp;&nbsp;'.number_format($bill_total-$total).'&nbsp;&nbsp;</td></tr>';;
								?>
						</table>
					<?php }	?>	
					<br/>
				</div>
			</div>
		</div>
	</div>
</div>
<hr/>

<script type="text/javascript">
	payLocation();
</script>

<?php
    include_once  'template/m_footer.php';
?>