<?php
    include_once  'template/m_header.php';
	$cust_odr=$_GET['cust_odr'];
    $bill_salesman=$_COOKIE['user_id'];
    if(isset($_GET['s'])){ if($_GET['s']!='')  $bill_salesman=$_GET['s']; }
	if($cust_odr=='yes') $main_tale_color='#C6DEFE'; else $main_tale_color='#E5E5E5';
?>
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
	.main-search-items{
		box-shadow: 0 0 10px rgb(0 0 0 / 10%);
		border-radius: 15px;
		background-color:#EEEEEF; border-radius: 15px; padding:15px;
	}
	.search-items{
		box-shadow: 0 0 10px rgb(0 0 0 / 10%);
		background-color:#EEEEEF;
		border-radius: 15px;
		padding:15px;
	}
	.search-results{
		margin-top: 15px;
		margin-bottom: 70px;
	}
	iframe{
		width: 100%;
	}
	@media only screen and (min-width: 600px) {
		#landscape{
			margin-left: 15px;
		}
	}
	table{
		font-size:12pt;
		font-family:Calibri;
	}
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
		// if($case=='name') {$out='div_cmob';
		// if($case=='mob') $out='div_cname';
		// document.getElementById($out).innerHTML=document.getElementById('loading').innerHTML;
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
			if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				var myObj = JSON.parse(xmlhttp.responseText);
				// document.getElementById($out).innerHTML='';
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

	function createTMPBill(){
		$cust_odr=document.getElementById('cust_odr').value;
		$sm_id=document.getElementById('sm_id').value;
		$cust_id=document.getElementById('cust_id').value;
		$gps_x=document.getElementById('gps_x').value;
		$gps_y=document.getElementById('gps_y').value;
		if($cust_id==''){
			alert('Please Select the Customer or Phone Number');
			return false;
		}else{
			document.getElementById('div_submit').innerHTML=document.getElementById('loading').innerHTML;
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
				if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					var returntext=this.responseText;
					if(returntext!=''){
						var myObj = JSON.parse(this.responseText);
							if(myObj.msg=='Done'){
								document.getElementById('notifications').innerHTML='<span style="color:green; font-weight:bold; font-size:12pt;">Bill Created</span>';
								window.location = 'index.php?components=bill2&action=bill_item&cust_odr='+$cust_odr+'&bill_no='+myObj.bm_no;
							}else{
								document.getElementById('notifications').innerHTML='<span style="color:red; font-weight:bold; font-size:12pt;">'+myObj.msg+'</span>';
								document.getElementById('div_submit').innerHTML='<input type="button" value="Submit" onclick="createTMPBill()" style="width:100px; height:50px;" />';
							}
						}
					}
				};
			xmlhttp.open("POST", "index.php?components=bill2&action=new_tmp_bill", true);
			xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xmlhttp.send('cust_odr='+$cust_odr+'&cust_id='+$cust_id+'&sm_id='+$sm_id+'&gps_x='+$gps_x+'&gps_y='+$gps_y);
		}
	}

	//----------------------------------------------------------------//
	function BMCreateCust($case){
		$cust_odr=document.getElementById('cust_odr').value;
		$sm_id=document.getElementById('sm_id').value;
		if($case === 'onetime_cust'){
		$mob=document.getElementById('search-mob').value;
		}else{
			$mob=0;
		}
		window.location = 'index.php?components=bill2&action='+$case+'&sm_id='+$sm_id+'&cust_odr='+$cust_odr+'&mob='+$mob;
	}

	//----------------------------GPS-------------------------------------------------------//
	function billLocation() {
	    if (navigator.geolocation) {
	        navigator.geolocation.getCurrentPosition(showPosition2);
	    }
	}

	function showPosition2(position) {
	    document.getElementById('gps_x').value=position.coords.latitude;
	    document.getElementById('gps_y').value=position.coords.longitude;
	}

</script>

<?php
	if(isset($_REQUEST['id'])) $id=$_REQUEST['id']; else $id=0;
?>
<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px" /></div>

<div class="w3-container" style="margin-top:75px">
	<table align="center">
		<tr>
			<td>
				<div id="notifications"></div>
			</td>
		</tr>
	</table>
	<hr>
	<div class="w3-row">
 	 	<div class="w3-col s3"></div>
		<div class="w3-col">
			<?php if($systemid==13) $val='yes'; else $val='no';
			print '<input type="hidden" id="system_tmp" value="'.$val.'" />';
			?>

			<table align="center">
				<tr>
					<td style="vertical-align:top;">
						<input type="hidden" id="gps_x" name="gps_x" value="0" />
						<input type="hidden" id="gps_y" name="gps_y" value="0" />
						<input type="hidden" id="sm_id" name="bill_sm" value="" />
						<input type="hidden" id="cust_odr" value="<?php print $cust_odr; ?>" />
						<input type="hidden" id="cust_id" name="cust_id" value="" />

						<?php if($current_district!=''){ ?>
							<table align="center" bgcolor="<?php print $main_tale_color; ?>"  border="0" class="main-search-items">
								<tr>
									<td colspan="5"><br /></td>
								</tr>
								<?php
								print '<tr><td width="10px"></td><td>Customer</td><td colspan="2">
									<div class="frmSearch">
									<input type="text" id="search-cust" placeholder="Customer Name" autocomplete="nope" />
									<div id="suggesstion-cust"></div>
									</div>
								</td><td width="10px"><div id="div_cname"></div></td></tr>';
								if($_COOKIE['retail']==1)
									print '<tr><td width="10px"></td><td>Mobile</td><td colspan="2">
									<div class="frmSearch">
									<input type="text" id="search-mob" autocomplete="nope" />
									<div id="suggesstion-mob"></div>
									</div>
									</td><td width="10px"><div id="div_cmob"></div></td></tr>';
								else
									print '<tr><td colspan="4"><input type="hidden" name="mob" id="mob" value="0" /></td><td width="10px"></td></tr>';
								if($systemid==1 || $systemid==4 || $systemid==10 || $systemid==15)
									print '<tr><td width="10px"></td><td>Salesman</td><td colspan="2">
									<div class="frmSearch">
									<input type="text" id="search-sm" autocomplete="nope" />
									<div id="suggesstion-sm"></div>
									</div>
									</td><td width="10px"><div id="div_sm"></div></td></tr>';
								else print '<tr><td colspan="5"><input type="hidden" id="search-sm" name="bill_sm" /></td></tr>';
								?>
								<tr>
									<td width="10px"></td>
									<td></td>
									<td colspan="2">
										<table>
											<tr>
												<td>
													<div id="div_submit">
														<input type="button" value="Submit" onclick="createTMPBill()" style="width:100px; height:50px;" /></div>
												</td>
												<td>
													<?php if(!isset($_GET['cust'])){
														if($_COOKIE['retail']==0){ ?><input type="button" value="Create Cust" onclick="BMCreateCust('wholesale_cust')"  style="width:100px; height:50px;" /> <?php }
														if($_COOKIE['retail']==1){ ?><input type="button" value="Create Cust" onclick="BMCreateCust('onetime_cust')"  style="width:100px; height:50px;" /> <?php }
													} ?>
												</td>
											</tr>
										</table>
										<br/>
									</td>
									<td width="10px"></td>
								</tr>
							</table>
						<?php } ?>
						<hr />

					</td>
					<!-- <td width="10px"></td> -->
					<td style="vertical-align:top;">
						<div id="landscape" style="vertical-align:top" ></div>
					</td>
				</tr>
			</table>
		</div>
	</div>

	<div class="w3-row">
		<div class="w3-col s3"></div>
		<div class="w3-col" style="vertical-align:top">
			<div id="portrait">
				<div class="search-items">
					<table align="center" height="100%">
						<!-- Search for Invoice Number -->
						<tr>
							<td>
								<form id="searchinv" action="index.php?components=bill2&action=search_bill&s=<?php print $bill_salesman; ?>&cust_odr=<?php print $_GET['cust_odr']; ?>" method="post">
									<input type="text" style="width:148px;" name="search1" id="search1" placeholder="Invoice Number" />
									<input type="Submit" value="Search" />
								</form>
							</td>
						</tr>
						<!-- Search for Customer ID -->
						<tr>
							<td>
								<form action="index.php">
									<input type="hidden" name="components" value="bill2" />
									<input type="hidden" name="action" value="home" />
									<input type="hidden" name="s" value="<?php print $bill_salesman; ?>" />
									<input type="hidden" name="cust_odr" value="<?php print $_GET['cust_odr']; ?>" />
									<input type="text" style="width:148px" name="searchcustid" placeholder="Customer ID" value="<?php if(isset($_GET['searchcustid']))print $_GET['searchcustid']; ?>" onclick="this.value=''" />
									<input type="Submit" value="Search" />
								</form>
							</td>
						</tr>
						<!-- Search for Customer Name -->
						<tr>
							<td>
								<form action="index.php">
									<input type="hidden" name="components" value="bill2" />
									<input type="hidden" name="action" value="home" />
									<input type="hidden" name="s" value="<?php print $bill_salesman; ?>" />
									<input type="hidden" name="cust_odr" value="<?php print $_GET['cust_odr']; ?>" />
									<input type="text" style="width:148px" name="searchcustname" placeholder="Customer Name" value="<?php if(isset($_GET['searchcustname']))print $_GET['searchcustname']; ?>" onclick="this.value=''" />
									<input type="Submit" value="Search" />
								</form>
							</td>
						</tr>
						<!-- Search for Mobile Number -->
						<tr>
							<td>
								<form action="index.php">
									<input type="hidden" name="components" value="bill2" />
									<input type="hidden" name="action" value="home" />
									<input type="hidden" name="s" value="<?php print $bill_salesman; ?>" />
									<input type="hidden" name="cust_odr" value="<?php print $_GET['cust_odr']; ?>" />
									<input type="text" style="width:148px" name="searchmob" placeholder="Mobile Number" value="<?php if(isset($_GET['searchmob']))print $_GET['searchmob']; ?>" onclick="this.value=''" />
									<input type="Submit" value="Search" />
								</form>
							</td>
						</tr>
						<!-- Search for Unic Id -->
						<tr>
							<td>
								<form action="index.php">
									<input type="hidden" name="components" value="bill2" />
									<input type="hidden" name="action" value="home" />
									<input type="hidden" name="s" value="<?php print $bill_salesman; ?>" />
									<input type="hidden" name="cust_odr" value="<?php print $_GET['cust_odr']; ?>" />
									<input type="text" style="width:148px" name="searchunic" placeholder="Unique ID" value="<?php if(isset($_GET['searchunic']))print $_GET['searchunic']; ?>" onclick="this.value=''" />
									<input type="Submit" value="Search" />
								</form>
							</td>
						</tr>
					</table>
				</div>
				<div class="search-results">
					<?php
						if(isset($_GET['searchcustid'])){
							print '<iframe id="search_frm" width="260px" height="350px" src="components/bill2/view/tpl/search_bill.php?searchcustid='.$_GET['searchcustid'].'"></iframe>';
						}elseif(isset($_GET['searchcustname'])){
							print '<iframe id="search_frm" width="260px" height="350px" src="components/bill2/view/tpl/search_bill.php?searchcustname='.$_GET['searchcustname'].'"></iframe>';
						}elseif(isset($_GET['searchmob'])){
							print '<iframe id="search_frm" width="260px" height="350px" src="components/bill2/view/tpl/search_bill.php?searchmob='.$_GET['searchmob'].'"></iframe>';
						}elseif(isset($_GET['searchunic'])){
							print '<iframe id="search_frm" width="260px" height="350px" src="components/bill2/view/tpl/search_bill.php?searchunic='.$_GET['searchunic'].'"></iframe>';
						}
					?>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	billLocation();
</script>
<?php
	if($current_district==''){
		if($static_district!=0)
			print '<script type="text/javascript">
					document.getElementById("district").value='.$static_district.'; setDistrict2("bill2");
				</script>';
	}
	include_once  'template/m_footer.php';
?>