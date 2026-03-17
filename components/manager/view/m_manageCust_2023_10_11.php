<?php
include_once  'template/m_header.php';
?>
<!-- ------------------------------------------------------------------------------------ -->
<?php
if (isset($_REQUEST['id'])) $id = $_REQUEST['id'];
else $id = 0;
?>
<link rel="stylesheet" href="plugin/jquery/css/jquery.ui.all.css" />
<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
<script src="plugin/jquery/ui/jquery.ui.core.js"></script>
<script src="plugin/jquery/ui/jquery.ui.widget.js"></script>
<script src="plugin/jquery/ui/jquery.ui.position.js"></script>
<script src="plugin/jquery/ui/jquery.ui.autocomplete.js"></script>
<link rel="stylesheet" href="plugin/jquery/css/demos.css" />
<script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>

<script type="text/javascript">
	$(document).ready(function() {
			$("#search-cust").keyup(function() {
				if (document.getElementById('search-cust').value.length > 2) {
					$.ajax({
						type: "POST",
						url: "index.php?components=<?php print $components; ?>&action=cust-list",
						data: 'keyword=' + $(this).val(),
						beforeSend: function() {
							$("#search-cust").css("background", "#FFF url(images/LoaderIcon.gif) no-repeat 165px");
						},
						success: function(data) {
							$("#suggesstion-cust").show();
							$("#suggesstion-cust").html(data);
							$("#search-cust").css("background", "#FFF");
						}
					});
				}
			});

			$("#search-nick").keyup(function() {
				if (document.getElementById('search-nick').value.length > 3) {
					$.ajax({
						type: "POST",
						url: "index.php?components=<?php print $components; ?>&action=nick-list",
						data: 'keyword=' + $(this).val(),
						beforeSend: function() {
							// $("#search-nick").css("background", "#FFF url(images/LoaderIcon.gif) no-repeat 165px");
						},
						success: function(data) {
							$("#suggesstion-nick").show();
							$("#suggesstion-nick").html(data);
							$("#search-nick").css("background", "#FFF");
						}
					});
				}
			});

			$("#search-mob").keyup(function() {
				if (document.getElementById('search-mob').value.length > 2) {
					$.ajax({
						type: "POST",
						url: "index.php?components=<?php print $components; ?>&action=mob-list",
						data: 'keyword=' + $(this).val(),
						beforeSend: function() {
							 $("#search-mob").css("background", "#FFF url(images/LoaderIcon.gif) no-repeat 165px");
						},
						success: function(data) {
							$("#suggesstion-mob").show();
							$("#suggesstion-mob").html(data);
							$("#search-mob").css("background", "#FFF");
						}
					});
				}
			});

			$("#search-nic").keyup(function() {
				if (document.getElementById('search-nic').value.length > 2) {
					$.ajax({
						type: "POST",
						url: "index.php?components=<?php print $components; ?>&action=nic-list",
						data: 'keyword=' + $(this).val(),
						beforeSend: function() {
							 $("#search-nic").css("background", "#FFF url(images/LoaderIcon.gif) no-repeat 165px");
						},
						success: function(data) {
							$("#suggesstion-nic").show();
							$("#suggesstion-nic").html(data);
							$("#search-nic").css("background", "#FFF");
						}
					});
				}
			});
	});

		function selectCust(val) {
			$("#search-cust").val(val);
			$("#suggesstion-cust").hide();
			getCustData('name', val);
		}

		function selectNick(val) {
			$("#search-nick").val(val);
			$("#suggesstion-nick").hide();
			getCustData('nick', val);
		}

		function selectMob(val) {
			$("#search-mob").val(val);
			$("#suggesstion-mob").hide();
			getCustData('mob', val);
		}

		function selectNic(val) {
			$("#search-nic").val(val);
			$("#suggesstion-nic").hide();
			getCustData('nic', val);
		}
		
		function getCustData($case, $val) {
			document.getElementById('div_search_btn').innerHTML = document.getElementById('loading').innerHTML;
			var $components = document.getElementById('components').value;
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					var myObj = JSON.parse(xmlhttp.responseText);
					var $cust_id = myObj.cust_id;
					window.location = 'index.php?components=' + $components + '&action=editcust&id=' + $cust_id + '&show_map=no';
				}
			};
			xmlhttp.open("POST", 'index.php?components=' + $components + '&action=more_cust', true);
			xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xmlhttp.send('case=' + $case + '&val=' + $val);
		}
		

	function showImage($source) {
		document.getElementById('imageframe0').innerHTML = document.getElementById($source).innerHTML;
	}
</script>
<style>
	#pano {
		float: left;
		height: 300px;
		width: 100%;
	}
</style>

<style>
	#cust-list {
		float: left;
		list-style: none;
		margin-top: -3px;
		padding: 0;
		width: 190px;
		position: absolute;
	}

	#cust-list li {
		padding: 10px;
		background: #F8F8F8;
		border-bottom: #bbb9b9 1px solid;
	}

	#cust-list li:hover {
		background: #ece3d2;
		cursor: pointer;
	}

	#cust-list2 {
		float: left;
		list-style: none;
		margin-top: -3px;
		padding: 0;
		width: 190px;
		position: absolute;
		z-index: 10;
	}

	#cust-list2 li {
		padding: 10px;
		background: #F8F8F8;
		border-bottom: #bbb9b9 1px solid;
	}

	#cust-list2 li:hover {
		background: #ece3d2;
		cursor: pointer;
	}

	#search-cust {
		padding: 10px;
		border: #a8d4b1 1px solid;
		border-radius: 4px;
	}

	#nick-list {
		float: left;
		list-style: none;
		margin-top: -3px;
		padding: 0;
		width: 190px;
		position: absolute;
	}

	#nick-list li {
		padding: 10px;
		background: #F8F8F8;
		border-bottom: #bbb9b9 1px solid;
	}

	#nick-list li:hover {
		background: #ece3d2;
		cursor: pointer;
	}

	#search-nick {
		padding: 10px;
		border: #a8d4b1 1px solid;
		border-radius: 4px;
	}

	#mob-list {
		float: left;
		list-style: none;
		margin-top: -3px;
		padding: 0;
		width: 190px;
		position: absolute;
	}

	#mob-list li {
		padding: 10px;
		background: #F8F8F8;
		border-bottom: #bbb9b9 1px solid;
	}

	#mob-list li:hover {
		background: #ece3d2;
		cursor: pointer;
	}

	#search-mob {
		padding: 10px;
		border: #a8d4b1 1px solid;
		border-radius: 4px;
	}
	#nic-list {
		float: left;
		list-style: none;
		margin-top: -3px;
		padding: 0;
		width: 190px;
		position: absolute;
	}

	#nic-list li {
		padding: 10px;
		background: #F8F8F8;
		border-bottom: #bbb9b9 1px solid;
	}

	#nic-list li:hover {
		background: #ece3d2;
		cursor: pointer;
	}

	#search-nic {
		padding: 10px;
		border: #a8d4b1 1px solid;
		border-radius: 4px;
	}
</style>
<script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
</head>

<input type="hidden" id="components" value="<?php print $components; ?>" />

<div class="w3-container" style="margin-top:75px">
	<div id="notifications">
		<?php
			if (isset($_REQUEST['message'])){
				if ($_REQUEST['re'] == 'success') $color = 'green';	else $color = '#DD3333';
				if(strpos($_REQUEST['message'],'|')==false){
					$message='<span style="color:'.$color.'; font-weight:bold;font-size:12pt;">'.$_REQUEST['message'].'</span>';
				}else{
					$messages=explode("|",$_REQUEST['message']);
					$message='<span style="color:green; font-weight:bold;font-size:12pt;">'.$messages[0].'</span> | <span style="color:#DD3333; font-weight:bold;font-size:12pt;">'.$messages[1].'</span>';
				}
				print '<p>'.$message.'</p>';
			}
		?>
	</div>


	<hr>
	<div class="w3-row">
		<div class="w3-col s3">
		</div>
		<div class="w3-col">
			<?php
			if ($_GET['action'] == 'newcust') include_once  'components/manager/view/tpl/add_cust.php';
			if ($_GET['action'] == 'editcust') include_once  'components/manager/view/tpl/edit_cust.php';
			?>
			<div id="imageframe0"></div>
			<?php
			if (($_GET['action'] == 'editcust') || ($_GET['action'] == 'searchcust')) {
				if ($forign_image) $img_path = $cu_master_cust;
				else $img_path = $cu_id1;
				if ($cu_image1 == 1) print '<div id="imageframe1" style="display:none"><hr /><img src="images/customerdata/' . $systemid . '/' . str_pad($img_path, 8, "0", STR_PAD_LEFT) . '_1.jpg?t=' . time() . '" style="width:95%" /></div>';
				if ($cu_image2 == 1) print '<div id="imageframe2" style="display:none"><hr /><img src="images/customerdata/' . $systemid . '/' . str_pad($img_path, 8, "0", STR_PAD_LEFT) . '_2.jpg?t=' . time() . '" style="width:95%" /></div>';
				if ($cu_image3 == 1) print '<div id="imageframe3" style="display:none"><hr /><img src="images/customerdata/' . $systemid . '/' . str_pad($img_path, 8, "0", STR_PAD_LEFT) . '_3.jpg?t=' . time() . '" style="width:95%" /></div>';
				if ($cu_image4 == 1) print '<div id="imageframe4" style="display:none"><hr /><img src="images/customerdata/' . $systemid . '/' . str_pad($img_path, 8, "0", STR_PAD_LEFT) . '_4.jpg?t=' . time() . '" style="width:95%" /></div>';
			}
			?>
			<br />
			<?php if ($cu_gps_x != 0 && $cu_gps_y != 0) {
				if ($_GET['show_map'] == 'yes') {
			?>
					<div id="pano"></div>
					<script>
						function initialize() {
							var fenway = {
								lat: <?php print $cu_gps_x; ?>,
								lng: <?php print $cu_gps_y; ?>
							};
							var panorama = new google.maps.StreetViewPanorama(
								document.getElementById('pano'), {
									position: fenway,
									pov: {
										heading: 34,
										pitch: 10
									}
								});
							map.setStreetView(panorama);
						}
					</script>
					<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php print $map_api; ?>&callback=initialize">
					</script>
			<?php
					print '<br /><a href="https://maps.google.com/?q=' . $cu_gps_x . ',' . $cu_gps_y . '" target="_blank">Open on Map</a><br />';
				} else 	print '<input type="button" value="Show Shop View" onclick="window.location = \'index.php?components=' . $components . '&action=editcust&id=' . $cu_id1 . '&show_map=yes\'" /><br />';
			} ?>

			<hr>
			<!--
		<form method="post" action="index.php?components=<?php print $_GET['components']; ?>&action=searchcust&show_map=no">
			<table>
			<tr><td><input type="text" name="namesearch" id="namesearch" placeholder="Search by Shop Name" /></td><td rowspan="2"><input type="submit" value="Search" style="height:50px" /></td></tr>
			<tr><td><input type="text" name="mobsearch" id="mobsearch" placeholder="Search by Mobile Number" /></td></tr>
			</table>
		</form>
	-->

			<table align="center" style="font-size:10pt">
				<tr>
					<td>
						<div class="frmSearch">
							<input type="text" name="namesearch" id="search-cust" placeholder="Shop Name" style="width:200px" />
							<div id="suggesstion-cust"></div>
						</div>
					</td>
					<td rowspan="3">
						<div id="div_search_btn"></div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="frmSearch">
							<input type="text" name="nicknamesearch" id="search-nick" placeholder="Nickname" style="width:200px" />
							<div id="suggesstion-nick"></div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="frmSearch">
							<input type="text" name="mobsearch" id="search-mob" placeholder="Mobile Number" style="width:200px" />
							<div id="suggesstion-mob"></div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="frmSearch">
							<input type="text" name="nicsearch" id="search-nic" placeholder="NIC" style="width:200px" />
							<div id="suggesstion-nic"></div>
						</div>
					</td>
				</tr>
			</table>
			<br />
			<table align="center" style="font-size:10pt">
				<tr style="font-size:12pt; background-color:#CCCCCC">
					<th>Shop Name</th><?php if ($components != 'topmanager') print '<th>NIC</th>'; ?><th>Mobile</th>
					<th>Credit Limit</th><?php if ($components == 'topmanager') print '<th width="100px">SYS ID</th>'; ?>
				</tr>
				<?php
				$custstatus = '';
				for ($i = 0; $i < sizeof($cu_id); $i++) {
					if (($cu_status[$i] == 1) || ($cu_status[$i] == 2) || ($cu_status[$i] == 3)) {
						if ($cu_status[$i] == 3) {
							$color = '';
							$stname = 'Pending Customers';
						}
						if ($cu_status[$i] == 1) {
							$color = '';
							$stname = 'Wholesale Customers';
						}
						if ($cu_status[$i] == 2) {
							$color = '';
							$stname = 'Retail Customers';
						}
						if ($components == 'topmanager') {
							$nic_td = '';
							$sys_td = '<td align="center">' . $cu_sub_sys[$i] . '</td>';
						} else {
							$nic_td = '<td ' . $color . ' class="shipmentTB3">' . $cu_nic[$i] . '</td>';
							$sys_td = '';
						}
						if ($custstatus != $cu_status[$i])
							print '<tr style="background-color:#467898; color:white;"><td colspan="4" class="shipmentTB3">' . $stname . '</td></tr>';
						print '<tr style="background-color:#F1F1F1"><td ' . $color . ' class="shipmentTB3"><a ' . $color . ' href="index.php?components=' . $_GET['components'] . '&action=editcust&id=' . $cu_id[$i] . '&show_map=no">' . $cu_name[$i] . '</a></td>' . $nic_td . '<td ' . $color . ' class="shipmentTB3">' . $cu_mobile[$i] . '</td><td ' . $color . ' class="shipmentTB3" align="right">' . number_format($cu_cr_limit[$i]) . '</td>' . $sys_td . '</tr>';
						$custstatus = $cu_status[$i];
					}
				}
				$color = 'style="color:#AAAAAA"';
				$stname = 'Disabled Accounts';
				if ($_GET['action'] == 'disabledcust') $dis_button = '<input type="button" value="hide" onclick="window.location = \'index.php?components=' . $components . '&action=newcust\'" />';
				else $dis_button = '<input type="button" value="show" onclick="window.location = \'index.php?components=' . $components . '&action=disabledcust\'" />';

				print '<tr style="background-color:#467898; color:white;"><td colspan="3" class="shipmentTB3">' . $stname . '</td><td align="center">' . $dis_button . '</td></tr>';
				for ($i = 0; $i < sizeof($cu_id); $i++) {
					if ($cu_status[$i] == 0) {
						if ($components == 'topmanager') {
							$nic_td = '';
							$sys_td = '<td align="center">' . $cu_sub_sys[$i] . '</td>';
						} else {
							$nic_td = '<td ' . $color . ' class="shipmentTB3">' . $cu_nic[$i] . '</td>';
							$sys_td = '';
						}
						print '<tr style="background-color:#F1F1F1"><td ' . $color . ' class="shipmentTB3"><a ' . $color . ' href="index.php?components=' . $_GET['components'] . '&action=editcust&id=' . $cu_id[$i] . '&show_map=no">' . $cu_name[$i] . '</a></td>' . $nic_td . '<td ' . $color . ' class="shipmentTB3">' . $cu_mobile[$i] . '</td><td ' . $color . ' class="shipmentTB3" align="right">' . number_format($cu_cr_limit[$i]) . '</td>' . $sys_td . '</tr>';
					}
				}

				?>
			</table>
		</div>
	</div>
</div>
<hr>

<?php
include_once  'template/m_footer.php';
?>

<?php
if(isset($_GET['message'])) {
	$message2 = $_GET['message'];
	if (isset($_GET['message'])) {
		print "<script>
              toastr.options = {
				'closeButton': true,
				'debug': false,
				'newestOnTop': false,
				'progressBar': true,
				'positionClass': 'toast-top-right',
				'preventDuplicates': true,
				'onclick': null,
				'showDuration': '300',
				'hideDuration': '3000',
				'timeOut': '7000',
				'extendedTimeOut': '4000',
				'showEasing': 'swing',
				'hideEasing': 'linear',
				'showMethod': 'fadeIn',
				'hideMethod': 'fadeOut'
              }
              ";
           if(strpos($_GET['message'],'|')==false){
           		if($_GET['re']=='success'){
			   		print "toastr.success('$message2','Success!');";
			   	}else{
			   		print "toastr.error('$message2','Error!');";
			   	}
		   }else{
		   		print "toastr2=toastr;";
			   	print "toastr.success('$messages[0]','Success!');";
			   	print "toastr2.error('$messages[1]','Error!');";
		   }
         print "</script>";
	}
}
?>