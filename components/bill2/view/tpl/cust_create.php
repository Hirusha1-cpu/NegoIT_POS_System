<link rel="stylesheet" href="plugin/jquery/css/jquery.ui.all.css" />
<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
<script src="plugin/jquery/ui/jquery.ui.core.js"></script>
<script src="plugin/jquery/ui/jquery.ui.widget.js"></script>
<script src="plugin/jquery/ui/jquery.ui.position.js"></script>
<script src="plugin/jquery/ui/jquery.ui.autocomplete.js"></script>
<?php if (!isMobile()) print '<link rel="stylesheet" href="plugin/jquery/css/demos.css" />'; ?>

<style>
	#cust-list-wholesale {
		float: left;
		list-style: none;
		margin-top: -3px;
		padding: 0;
		width: 190px;
		position: absolute;
	}

	#cust-list-one_time {
		float: left;
		list-style: none;
		margin-top: -3px;
		padding: 0;
		width: 190px;
		position: absolute;
	}

	#cust-list-wholesale li {
		padding: 10px;
		background: #F8F8F8;
		border-bottom: #bbb9b9 1px solid;
	}

	#cust-list-one_time li {
		padding: 10px;
		background: #F8F8F8;
		border-bottom: #bbb9b9 1px solid;
	}

	#cust-list-wholesale li:hover {
		background: #ece3d2;
		cursor: pointer;
	}

	#cust-list-one_time li:hover {
		background: #ece3d2;
		cursor: pointer;
	}

	.table-customer-details{
		font-size: 12pt;
		font-family:Calibri;
    	box-shadow: 0 0 10px rgb(0 0 0 / 10%);
    	padding: 10px 10px;
		border-radius: 20px 20px 0px 0px;
	}
</style>

<script type="text/javascript">
	$(document).ready(function() {

		// Updated:- E.S.P.N 2021_06_03

		let reqUrl = "";
		let flag = 0;

		flag = "<?php if (isset($_GET['action']))
					print $_GET['action']; ?>";
		flag = "<?php if (isset($_GET['action']))
					print $_GET['action']; ?>";

		if (flag == "wholesale_cust") {
			reqUrl = 'cust-list-wholesale';
		}
		if (flag == "onetime_cust") {
			reqUrl = 'cust-list-one_time';
		}


		// 	Updated:- E.S.P.N 2021_06_02
		$("#shop_name").keyup(function() {
			if (document.getElementById('shop_name').value.length > 1) {
				// console.log('keyup');
				let val = $(this).val();
				$.ajax({
					type: "POST",
					url: "index.php?components=<?php print $components; ?>&action=" + reqUrl,
					data: 'keyword=' + $(this).val(),
					beforeSend: function() {
						$("#shop_name").css("background", "#FFF url(images/LoaderIcon.gif) no-repeat 165px");
						// console.log(val);
					},
					success: function(data) {
						$("#suggesstion-cust").show();
						$("#suggesstion-cust").html(data);
						$("#shop_name").css("background", "#FFF");
					}
				});
			}
		});

		// 	Added :- E.S.P.N 2021_06_25
		$("#nic").focusout(function() {
			if (document.getElementById('nic').value.length > 1) {
				$.ajax({
					type: "POST",
					url: "index.php?components=<?php print $components; ?>&action=nic-check",
					data: 'keyword=' + $(this).val(),
					success: function(data) {
						let obj = JSON.parse(data);

						if (obj.nic_error) {
							document.getElementById('div_nic').innerHTML = obj.nic_error;
							document.getElementById('nic').style.border = '1px solid red';
							document.getElementById('div_nic').style.fontWeight = 'bold';
							document.getElementById('div_nic').style.color = 'red';
						} else {
							document.getElementById('nic').style.border = '';
							document.getElementById('div_nic').innerHTML = '';
						}
					}
				});
			}
		});

		// 	Added :- E.S.P.N 2021_06_25
		$("#mobile").focusout(function() {
			if (document.getElementById('mobile').value.length > 1) {
				$.ajax({
					type: "POST",
					url: "index.php?components=<?php print $components; ?>&action=mobile-check",
					data: 'keyword=' + $(this).val(),
					success: function(data) {
						let obj = JSON.parse(data);

						if (obj.mobile_error) {
							document.getElementById('div_mobile').innerHTML = obj.mobile_error;
							document.getElementById('mobile').style.border = '1px solid red';
							document.getElementById('div_mobile').style.fontWeight = 'bold';
							document.getElementById('div_mobile').style.color = 'red';
						} else {
							document.getElementById('mobile').style.border = '';
							document.getElementById('div_mobile').innerHTML = '';
						}
					}
				});
			}
		});

	});

	function selectCust(val) {
		$("#shop_name").val(val);
		$("#suggesstion-cust").hide();
		getCustData('name', val);
	}

	function getCustData($case, $val) {
		document.getElementById('div_cust_list2').innerHTML = document.getElementById('loading').innerHTML;
		var $components = document.getElementById('components').value;
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				var myObj = JSON.parse(xmlhttp.responseText);
					document.getElementById('shop_name').value = myObj.cu_name1 ? myObj.cu_name1 : "";
					document.getElementById('nic').value = myObj.cu_nic1 ? myObj.cu_nic1 : "";
					document.getElementById('mobile').value = myObj.cu_mobile1 ? myObj.cu_mobile1 : "";
					document.getElementById('home_address').value = myObj.cu_home_add1 ? myObj.cu_home_add1 : "";
					<?php if($_REQUEST["action"] != "onetime_cust"){ ?>
						document.getElementById('master_cust').value = myObj.cu_id1 ? myObj.cu_id1 : "";
						document.getElementById('cr_limit').value = myObj.cu_crlimit1 ? myObj.cu_crlimit1 : "";
						document.getElementById('customer').value = myObj.cu_custname1 ? myObj.cu_custname1 : "";
						document.getElementById('dob').value = myObj.cu_dob1 ? myObj.cu_dob1 : "";
						document.getElementById('shop_address').value = myObj.cu_shop_add1 ? myObj.cu_shop_add1 : "";
						document.getElementById('shop_tel').value = myObj.cu_shop_tel1 ? myObj.cu_shop_tel1 : "";
						document.getElementById('home_address').value = myObj.cu_home_add1 ? myObj.cu_home_add1 : "";
						document.getElementById('tax_no').value = myObj.cu_tax_no ? myObj.cu_tax_no : "";
						document.getElementById('email_add').value = myObj.cu_email_add ? myObj.cu_email_add : "";
						document.getElementById('div_master_sys').value = myObj.master_sys ? myObj.master_sys : "";
					<?php } ?>
					document.getElementById('div_cust_list2').innerHTML = '';
			}
		};
		xmlhttp.open("POST", 'index.php?components=' + $components + '&action=cust-list2-get-one-cust-ajax', true);
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xmlhttp.send('case=' + $case + '&val=' + $val);
	}

	function validateCust2() {

		event.preventDefault();
		var $count = 0;
		var system = "<?php print $systemid; ?>";

		var $msg = "Please check all the required fields before submit the data.";
		if (document.getElementById('shop_name').value == '') $count++;
		if (document.getElementById('nic').value == '') $count++;
		if (document.getElementById('cu_group').value == '') $count++;
		if (document.getElementById('cu_town').value == '') $count++;
		if (document.getElementById('cr_limit').value == '') $count++;
		if (document.getElementById('store').value == '') $count++;
		if (document.getElementById('salesref').value == '') $count++;

		if (system == 1) {
			if (document.getElementById('customer').value == '') $count++;
			if (document.getElementById('shop_address').value == '') $count++;
			if (document.getElementById('shop_tel').value == '') $count++;
		}

		if ($count == 0) {
			if (document.getElementById('status').value != 2) {
				var mobile = document.getElementById('mobile').value;
				if (document.getElementById('mobile').value == '') $count++;
				if (mobile.indexOf("07") == -1) {
					$count++;
					$msg = "Invalid mobile number";
				}
				if (mobile.length != 10) {
					$count++;
					$msg = "Error: mobile number has " + mobile.length + " letters";
				}
			}
		}
		if($count == 0){
			if (document.getElementById('dob').value != '') {
				var date_regex = /^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/;
				if (!(date_regex.test(document.getElementById('dob').value))) {
					$count++;
					$msg = "Invalid DOB format";
				}
			}
		}
		if ($count != 0) {
			toastr.options = {
				'closeButton': true,
				'debug': false,
				'newestOnTop': false,
				'progressBar': true,
				'positionClass': 'toast-top-right',
				'preventDuplicates': true,
				'onclick': null,
				'showDuration': '300',
				'hideDuration': '1000',
				'timeOut': '4000',
				'extendedTimeOut': '2000',
				'showEasing': 'swing',
				'hideEasing': 'linear',
				'showMethod': 'fadeIn',
				'hideMethod': 'fadeOut'
			};
			toastr.error($msg, 'Error!');
			return false;
		} else {
			document.getElementById('div_submit').innerHTML = document.getElementById('loading').innerHTML;
			return true;
		}
	}

	function addCustomerAjax() {
		if (validateCust2()) {
			let shop_name = document.getElementById('shop_name').value;
			let nic = document.getElementById('nic').value;
			let mobile = document.getElementById('mobile').value;
			let cr_limit = document.getElementById('cr_limit').value;
			let customer = document.getElementById('customer').value;
			let dob = document.getElementById('dob').value;
			let nickname = document.getElementById('nickname').value;
			let shop_address = document.getElementById('shop_address').value;
			let shop_tel = document.getElementById('shop_tel').value;
			let home_address = document.getElementById('home_address').value;
			let home_tel = document.getElementById('home_tel').value;
			let tax_no = document.getElementById('tax_no').value;
			let email_add = document.getElementById('email_add').value;
			// let email_alert = document.getElementById('email_alert').value;

			let sms = document.getElementById('sms');
			if (typeof(sm) != 'undefined' && sm != null) {
				sms = document.getElementById('sms').value
			} else {
				sms = 0;
			}

			let store = document.getElementById('store').value;
			let salesref = document.getElementById('salesref').value;
			let cu_group = document.getElementById('cu_group').value;
			let cu_town = document.getElementById('cu_town').value;

			// let cu_type = document.querySelector('input[name = cu_type]:checked').value;

			let status = document.getElementById('status').value;
			let master_cust = document.getElementById('master_cust').value;
			let sub_systemc = document.getElementById('sub_systemc').value;

			let toasterOptions = {
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
			};

			let flag = 0;
			let ws = "";

			flag = "<?php if (isset($_GET['action']))
						print $_GET['action']; ?>";

			if (flag == "wholesale_cust") {
				ws = 'wholesale_cust';
			}
			if (flag == "onetime_cust") {
				ws = 'onetime_cust';
			}
			$.ajax({
				type: "POST",
				url: "index.php?components=<?php print $components ?>&action=add_cust",
				data: {
					shop_name: shop_name,
					nic: nic,
					mobile: mobile,
					cr_limit: cr_limit,
					customer: customer,
					dob: dob,
					nickname: nickname,
					shop_address: shop_address,
					shop_tel: shop_tel,
					home_address: home_address,
					home_tel: home_tel,
					tax_no: tax_no,
					email_add: email_add,
					// email_alert: email_alert,
					sms: sms,
					store: store,
					salesref: salesref,
					cu_group: cu_group,
					cu_town: cu_town,
					// cu_type: cu_type,
					status: status,
					master_cust: master_cust,
					sub_systemc: sub_systemc

				},
				beforeSend: function() {
					document.getElementById('div_submit').innerHTML = document.getElementById('loading').innerHTML;
				},
				success: function(data) {
					var obj = JSON.parse(data);
					var button = '<input type="button" value="Add Customer" style="width:130px; height:50px" onclick="addCustomerAjax()" />';

					if (obj.shop_name) {
						document.getElementById('div_shop_name').innerHTML = obj.shop_name;
						document.getElementById('shop_name').style.border = '1px solid red';
						document.getElementById('div_shop_name').style.fontWeight = 'bold';
						document.getElementById('div_shop_name').style.color = 'red';
						document.getElementById('div_submit').innerHTML = button;

						toastr.options = toasterOptions;
						toastr.error(obj.shop_name, 'Error!');
					} else {
						document.getElementById('shop_name').style.border = '';
						document.getElementById('div_shop_name').innerHTML = '';
					}

					if (obj.mobile) {
						document.getElementById('div_mobile').innerHTML = obj.mobile;
						document.getElementById('mobile').style.border = '1px solid red';
						document.getElementById('div_mobile').style.fontWeight = 'bold';
						document.getElementById('div_mobile').style.color = 'red';
						document.getElementById('div_submit').innerHTML = button;

						toastr.options = toasterOptions;
						toastr.error(obj.mobile, 'Error!');
					} else {
						document.getElementById('mobile').style.border = '';
						document.getElementById('div_mobile').innerHTML = '';
					}

					if (obj.nic) {
						document.getElementById('div_nic').innerHTML = obj.nic;
						document.getElementById('nic').style.border = '1px solid red';
						document.getElementById('div_nic').style.fontWeight = 'bold';
						document.getElementById('div_nic').style.color = 'red';
						document.getElementById('div_submit').innerHTML = button;

						toastr.options = toasterOptions;
						toastr.error(obj.mobile, 'Error!');
					} else {
						document.getElementById('nic').style.border = '';
						document.getElementById('div_nic').innerHTML = '';
					}
					// user created successfully
					if (obj.user_create) {
						document.getElementById("notifications").innerHTML = '<span style="color: green; font-weight:bold; font-size:12pt;">'+obj.user_create+'</span>';
						// document.getElementById('div_submit').innerHTML = button;
						if (ws == "wholesale_cust") {
							// upload images
							document.getElementById('cust_id').value = obj.cust_id;
							setTimeout(function () {
								$("#image_form").submit();
							}, 1000);
						}else{
							toastr.options = toasterOptions;
							toastr.success(obj.user_create, 'Success!');
							window.location ='index.php?components=bill2&action=new_tmp_bill2&quotation=0&sm_id=<?php print $_GET["sm_id"]; ?>&cust_odr=<?php print $_GET["cust_odr"]; ?>&cust_id=' + obj.cust_id;
						}

					}
					if (obj.cust_error) {
						toastr.options = toasterOptions;
						toastr.error(obj.cust_error, 'Error!');
						document.getElementById("notifications").innerHTML = '<span style="color: #DD3333; font-weight:bold; font-size:12pt;">Customer could not be created!</span>';

						document.getElementById('div_submit').innerHTML = button;

						document.getElementById('shop_name').value = '';
						document.getElementById('nic').value = '';
						document.getElementById('mobile').value = '';
						document.getElementById('home_address').value = '';

						if (ws == "wholesale_cust") {
							document.getElementById('customer').value = "";
							document.getElementById('dob').value = "";
							document.getElementById('shop_address').value = "";
							document.getElementById('shop_tel').value = "";
							document.getElementById('home_address').value = "";
							document.getElementById('home_tel').value = "";
							document.getElementById('tax_no').value = "";
							document.getElementById('email_add').value = "";
							document.getElementById('store').value = "";
							document.getElementById('cu_group').value = "";
						}
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError);
				}

			});
		}
	}

	<?php if (isMobile()) { ?>
		//get GPS coordinates-------------------------------------------//
		function getLocation() {
			if (navigator.geolocation) {
				navigator.geolocation.getCurrentPosition(showPosition);
			} else {
				document.getElementById('gps_x').value = "Geolocation is not supported by this browser.";
			}
		}

		function showPosition(position) {
			document.getElementById('gps_x').value = position.coords.latitude;
			document.getElementById('gps_y').value = position.coords.longitude;
		}
	<?php } ?>
</script>

<?php
// if ($_GET['action'] == 'wholesale_cust') $form_action = 'add_cust1';
// else $form_action = 'add_cust2';
if ($_GET['action'] == 'wholesale_cust') $form_action = 'add_cust_image1';
else $form_action = 'add_cust_image2';

// form attribue old (before) 2021_06_03
?>

<form enctype="multipart/form-data" action="index.php?components=<?php print $_GET['components'] ?>&action=<?php print $form_action; ?>&sm_id=<?php print $_GET['sm_id']; ?>&cust_odr=<?php print $_GET['cust_odr']; ?>" onsubmit="return validateCust()" method="post" style="font-family:Calibri" >
	<div id="loading" style="display:none"><img src="images/loading.gif" style="width:70px" /><br><span style="color:maroon; vertical-align:middle">Please Wait</span></div>

	<input type="hidden" id="components" value="<?php print $components; ?>" />
	<input type="hidden" id="gps_x" name="gps_x" value="" />
	<input type="hidden" id="gps_y" name="gps_y" value="" />
	<input type="hidden" id="store" name="store" value="<?php print $_COOKIE['store']; ?>" />
	<input type="hidden" id="salesref" name="salesref" value="<?php print $_COOKIE['user_id']; ?>" />
	<input type="hidden" name="cr_limit" id="cr_limit" value="0" />
	<input type="hidden" name="master_cust" id="master_cust" value="<?php print $cu_id1; ?>" />

	<?php if ($_GET['components'] != 'topmanager') print '<input type="hidden" name="sub_systemc" id="sub_systemc" value="' . $sub_system . '" />'; ?>

	<?php if ($_GET['action'] == 'onetime_cust') { ?>
		<input type="hidden" name="status" id="status" value="2" />
		<input type="hidden" name="customer" id="customer" value="0" />
		<input type="hidden" name="dob" id="dob" value="" />
		<input type="hidden" name="shop_address" id="shop_address" value="0" />
		<input type="hidden" name="shop_tel" id="shop_tel" value="0" />
		<input type="hidden" name="home_tel" id="home_tel" value="0" />
		<input type="hidden" name="tax_no" id="tax_no" value="" />
		<input type="hidden" name="nickname" id="nickname" value="">
		<input type="hidden" name="cu_town" id="cu_town" value="<?php print $town_default; ?>" />
		<?php if ($systemid == 4) print '<input type="hidden" name="sms" id="sms" value="1" />'; ?>
	<?php } else { ?>
		<input type="hidden" name="sms" id="sms" value="1" />
		<input type="hidden" name="status" id="status" value="3" />
		<input type="hidden" id="cust_type" value="onetime_cust" />
		<input type="hidden" name="nickname" id="nickname" value="">
	<?php } ?>

	<table align="center" bgcolor="#E5E5E5" class="table-customer-details">
		<tr>
			<td colspan="4" align="center">
				<table width="100%">
					<td><input type="button" value="Back" style="width:100px; height:30px" onclick="window.location = 'index.php?components=bill2&action=home&s=<?php print $_GET['sm_id']; ?>&cust_odr=<?php print $_GET['cust_odr']; ?>'" /></td>
					<td style="font-size:16pt; color:navy; font-weight:bold">Customer Detail</td>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="4"><br /></td>
		</tr>
		<tr>
			<td width="50px"></td>
			<td width="130px">
				<?php if ($_GET['action'] == 'onetime_cust') print 'Customer Name';
				else print 'Shop Name'; ?> <span style="color:red;">*</span>
			</td>
			<td width="250px">
				<input type="text" name="shop_name" id="shop_name" />
				<small>
					<div id="div_shop_name"></div>
				</small>
				<div id="suggesstion-cust"></div>
				<?php
				// old auto fill
				// <?php print '<input type="button" value="Auto Fill" onclick="window.location = ' . "'index.php?components=bill2&action=" . $_GET['action'] . "&id='+document.getElementById('shop_name').value+'&s=" . $_GET['sm_id'] . '&cust_odr=' . $_GET['cust_odr'] . "'" . '" />';
				?>
			</td>
			<td width="50px">
				<div id="div_cust_list2"></div>
				<div style="color:red" id="div_master_sys"></div>
			</td>
		</tr>
		<tr>
			<td width="50px"></td>
			<td>NIC <span style="color:red;">*</span></td>
			<td>
				<input type="text" name="nic" id="nic" />
				<small>
					<div id="div_nic"></div>
				</small>
			</td>
			<td width="50px"></td>
		</tr>
		<tr>
			<td width="50px"></td>
			<td>Mobile <span style="color:red;">*</span></td>
			<td>
				<input type="tel" name="mobile" id="mobile" />
				<small>
					<div id="div_mobile"></div>
				</small>
			</td>
			<td width="50px"></td>
		</tr>
		<tr>
			<td width="50px"></td>
			<td>Email</td>
			<td>
				<input type="text" name="email_add" id="email_add" />
			</td>
			<td width=" 50px"></td>
		</tr>
		<?php if ($_GET['action'] == 'onetime_cust') { ?>
			<input type="hidden" name="cu_group" id="cu_group" value="1" />
			<tr>
				<td width="50px"></td>
				<td colspan="2">
					<hr />
				</td>
				<td width="50px"></td>
			</tr>
			<tr>
				<td width="50px"></td>
				<td>Home Address</td>
				<td><textarea name="home_address" id="home_address" style="width:97%"></textarea></td>
				<td width="50px"></td>
			</tr>
			<tr>
				<td colspan="4" align="center"><br />
					<div id="div_submit">
						<input type="submit" id="submit" value="Add Customer" style="width:130px; height:50px" onclick="addCustomerAjax()" />
					</div>
					<br /><br />
				</td>
			</tr>
		<?php } else { ?>
			<tr>
				<td width="50px"></td>
				<td colspan="2">
					<hr />
				</td>
				<td width="50px"></td>
			</tr>
			<tr>
				<td width="50px"></td>
				<td>Associated Group <span style="color:red;">*</span></td>
				<td>
					<select name="cu_group" id="cu_group">
						<option value="">-SELECT-</option>
						<?php for ($i = 0; $i < sizeof($gp_id); $i++) {
							print '<option value="' . $gp_id[$i] . '" >' . $gp_name[$i] . '</option>';
						} ?>
					</select>
				</td>
			</tr>
			<tr>
				<td width="50px"></td>
				<td>Associated Town <span style="color:red;">*</span></td>
				<td>
					<select name="cu_town" id="cu_town">
						<option value="">-SELECT-</option>
						<?php for ($i = 0; $i < sizeof($town_id); $i++) {
							if ($town_id[$i] == $town_default) $select = 'selected="selected"';
							else $select = '';
							print '<option value="' . $town_id[$i] . '" ' . $select . '>' . $town_name[$i] . '</option>';
						} ?>
					</select>
				</td>
			</tr>
			<tr>
				<td width="50px"></td>
				<td>Customer Name <?php if($systemid ==1) print '<span style="color:red;">*</span>'; ?></td>
				<td><input type="text" name="customer" id="customer" value="<?php print $cu_custname1; ?>" /></td>
				<td width="50px"></td>
			</tr>
			<tr>
				<td width="50px"></td>
				<td>Customer DOB</td>
				<td><input type="date" name="dob" id="dob"/></td>
				<td width="50px"></td>
			</tr>
			<tr>
				<td width="50px"></td>
				<td>Shop Address <?php if($systemid ==1) print '<span style="color:red;">*</span>'; ?></td>
				<td><textarea name="shop_address" id="shop_address" style="width:97%"><?php print $cu_shop_add1; ?></textarea></td>
				<td width="50px"></td>
			</tr>
			<tr>
				<td width="50px"></td>
				<td>Shop Tel <?php if($systemid ==1) print '<span style="color:red;">*</span>'; ?></td>
				<td><input type="tel" name="shop_tel" id="shop_tel" value="<?php print $cu_shop_tel1; ?>" /></td>
				<td width="50px"></td>
			</tr>
			<tr>
				<td width="50px"></td>
				<td>Home Address</td>
				<td><textarea name="home_address" id="home_address" style="width:97%"><?php print $cu_home_add1; ?></textarea></td>
				<td width="50px"></td>
			</tr>
			<tr>
				<td width="50px"></td>
				<td>Home Tel</td>
				<td><input type="tel" name="home_tel" id="home_tel" value="<?php print $cu_home_tel1; ?>" /></td>
				<td width="50px"></td>
			</tr>
			<tr>
				<td width="50px"></td>
				<td>Tax No</td>
				<td><input type="text" name="tax_no" id="tax_no" value="<?php print $cu_tax_no; ?>" /></td>
				<td width="50px"></td>
			</tr>
		<?php } ?>
	</table>
</form>
<!-- Image upload form -->
<?php if (!($_GET['action'] == 'onetime_cust')) { ?>

	<form enctype="multipart/form-data" action="index.php?components=<?php print $components; ?>&action=<?php print $form_action; ?>&s=<?php print $_GET['sm_id']; ?>&cust_odr=<?php print $_GET['cust_odr']; ?>" id="image_form" method="POST" id="image_form" name="image_form">


		<table align="center" bgcolor="#E5E5E5" style="font-size:11pt; border-radius: 0px 0px 15px 15px; box-shadow: 0px 10px 10px rgb(0 0 0 / 10%); padding: 0px 10px;">
			<tr>
				<td colspan="4"><br /></td>
			</tr>
			<tr>
				<td></td>
				<td>Documents</td>
				<td colspan="2">
					<input type="file" name="fileToUpload1"><br />
					<input type="file" name="fileToUpload2" /><br />

					<input type="file" name="fileToUpload3" /><br />
					<input type="file" name="fileToUpload4" /><br />
					<input type="hidden" name="cust_id" id="cust_id" value="" />
				</td>
			</tr>
			<tr>
				<td colspan="4" align="center"><br />
					<div id="div_submit">
						<input type="button" value="Add Customer" style="width:130px; height:50px" onclick="addCustomerAjax()" />
					</div>
					<br /><br />
				</td>
			</tr>
			<tr>
				<td width="50px"></td><td width="130px"><small><span style="color:red;">*</span> Required fields </small><br /><br /></td><td width="250px"></td><td width="50px"></td>
			</tr>
		</table>

	</form>

<?php } ?>

<script type="text/javascript">
	getLocation();
</script>

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