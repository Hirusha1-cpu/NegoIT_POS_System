<script type="text/javascript">
	$(document).ready(function() {
		$("#shop_name").keyup(function() {
			if (document.getElementById('shop_name').value.length > 1) {
				$.ajax({
					type: "POST",
					url: "index.php?components=<?php print $components; ?>&action=cust-list2",
					data: 'keyword=' + $(this).val(),
					beforeSend: function() {
						$("#shop_name").css("background", "#FFF url(images/LoaderIcon.gif) no-repeat 165px");
					},
					success: function(data) {
						$("#suggesstion-cust2").show();
						$("#suggesstion-cust2").html(data);
						$("#shop_name").css("background", "#FFF");
					}
				});
			}

			if (document.getElementById('shop_name').value.length > 1) {
				$.ajax({
					type: "POST",
					url: "index.php?components=<?php print $components; ?>&action=cust-check",
					data: 'keyword=' + $(this).val(),
					success: function(data) {
						let obj = JSON.parse(data);
						if (obj.cust_error) {
							document.getElementById('div_shop_name').innerHTML = obj.cust_error;
							document.getElementById('shop_name').style.border = '1px solid red';
							document.getElementById('div_shop_name').style.fontWeight = 'bold';
							document.getElementById('div_shop_name').style.color = 'red';
						} else {
							document.getElementById('shop_name').style.border = '';
							document.getElementById('div_shop_name').innerHTML = '';
						}
					}
				});
			}
		});

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

	function selectCust2(val) {
		$("#shop_name").val(val);
		$("#suggesstion-cust2").hide();
		getCustData2('name', val);
	}

	function getCustData2($case, $val) {
		document.getElementById('div_cust_list2').innerHTML = document.getElementById('loading').innerHTML;
		var $components = document.getElementById('components').value;
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				var myObj = JSON.parse(xmlhttp.responseText);
					document.getElementById('shop_name').value = myObj.cu_name1 ? myObj.cu_name1 : "";
					document.getElementById('nic').value = myObj.cu_nic1 ? myObj.cu_nic1 : "";
					document.getElementById('mobile').value = myObj.cu_mobile1 ? myObj.cu_mobile1 : "";
					document.getElementById('cr_limit').value = myObj.cu_crlimit1 ? myObj.cu_crlimit1 : "";
					document.getElementById('customer').value = myObj.cu_custname1 ? myObj.cu_custname1 : "";
					document.getElementById('dob').value = myObj.cu_dob1 ? myObj.cu_dob1 : "";
					document.getElementById('nickname').value = myObj.cu_nickname1 ? myObj.cu_nickname1 : "";
					document.getElementById('shop_address').value = myObj.cu_shop_add1 ? myObj.cu_shop_add1 : "";
					document.getElementById('shop_tel').value = myObj.cu_shop_tel1 ? myObj.cu_shop_tel1 : "";
					document.getElementById('home_address').value = myObj.cu_home_add1 ? myObj.cu_home_add1 : "";
					document.getElementById('home_tel').value = myObj.cu_home_tel1 ? myObj.cu_home_tel1 : "";
					document.getElementById('email_add').value = myObj.cu_email_add ? myObj.cu_email_add : "";
					document.getElementById('master_cust').value = myObj.cu_id1 ? myObj.cu_id1 : "";

					if (myObj.cu_email_alert == 1) document.getElementById('email_alert').checked = true;
					else document.getElementById('email_alert').checked = false;

					if (myObj.cu_sms == 1) document.getElementById('sms').checked = true;
					else document.getElementById('sms').checked = false;
					
					// select associated store
					let selectedStore = myObj.cu_store ? myObj.cu_store : "";
					Array.from(document.querySelector("#store").options).forEach(function(option_element) {
						let option_text = option_element.text;
						let option_value = option_element.value;

						if (option_value == selectedStore) {
							option_element.selected = true;
						}
					});
					
					<?php if (($systemid == 1) ||($systemid == 17) || ($systemid == 13) || ($systemid == 15)) { ?>
						// select associated sales ref
						let selectedSalesRef = myObj.cu_sa ? myObj.cu_sa : "";
						Array.from(document.querySelector("#salesref").options).forEach(function(option_element) {
							let option_text = option_element.text;
							let option_value = option_element.value;

							if (option_value == selectedSalesRef) {
								option_element.selected = true;
							}
						});
					<?php } ?>

					// select associated group
					let selectedGroup = myObj.cu_group ? myObj.cu_group : "";
					Array.from(document.querySelector("#cu_group").options).forEach(function(option_element) {
						let option_text = option_element.text;
						let option_value = option_element.value;

						if (option_value == selectedGroup) {
							option_element.selected = true;
						}
					});

					// select associated town
					let selectedTown = myObj.cu_town ? myObj.cu_town : "";
					Array.from(document.querySelector("#cu_town").options).forEach(function(option_element) {
						let option_text = option_element.text;
						let option_value = option_element.value;

						if (option_value == selectedTown) {
							option_element.selected = true;
						}
					});

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
				if (!validateMobileNo("<?php print inf_country(1); ?>", mobile)) {
					$count++;
					$msg = "Invalid mobile number";
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
		if($count != 0) {
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
			let email_add = document.getElementById('email_add').value;
			let email_alert = document.getElementById('email_alert').value;
			let sms = document.getElementById('sms').value;
			let store = document.getElementById('store').value;
			let salesref = document.getElementById('salesref').value;
			let cu_group = document.getElementById('cu_group').value;
			let cu_town = document.getElementById('cu_town').value;
			let cu_type = document.querySelector('input[name = cu_type]:checked').value;

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
					email_add: email_add,
					email_alert: email_alert,
					sms: sms,
					store: store,
					salesref: salesref,
					cu_group: cu_group,
					cu_town: cu_town,
					cu_type: cu_type,
					status: status,
					master_cust: master_cust,
					sub_systemc: sub_systemc
				},
				beforeSend: function() {
					document.getElementById('div_submit').innerHTML = document.getElementById('loading').innerHTML;
				},
				success: function(data) {

					var obj = JSON.parse(data);
					var button = '<input type="submit" name="submit" id="submit" value="Add Customer" style="width:130px; height:50px" onclick="addCustomerAjax()" />';

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

					// user created successfully
					if (obj.user_create) {
						document.getElementById("notifications").innerHTML = '<span style="color: green">Customer created successfully!</span>';

						document.getElementById('cust_id').value = obj.cust_id;

						// upload images
						document.getElementById("image_form").submit();
					}

					if (obj.cust_error) {
						toastr.options = toasterOptions;
						toastr.error(obj.cust_error, 'Error!');
						document.getElementById("notifications").innerHTML = '<span style="color: #DD3333">Customer could not be created!</span>';
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError);
				}

			});

		}
	}

	function clearFeilds() {
		document.getElementById('shop_name').value = "";
		document.getElementById('nic').value = "";
		document.getElementById('mobile').value = "";
		document.getElementById('cr_limit').value = "";
		document.getElementById('customer').value = "";
		document.getElementById('dob').value = "";
		document.getElementById('nickname').value = "";
		document.getElementById('shop_address').value = "";
		document.getElementById('shop_tel').value = "";
		document.getElementById('home_address').value = "";
		document.getElementById('home_tel').value = "";
		document.getElementById('email_add').value = "";
		document.getElementById('email_alert').value = false;
		document.getElementById('sms').value = "";
		document.getElementById('store').value = "";
		document.getElementById('salesref').value = "";
		document.getElementById('cu_group').value = "";
		document.getElementById('cu_town').value = "";
	}
</script>

<form enctype="multipart/form-data" onsubmit="return validateCust2(event)" method="post">

	<div id="loading" style="display:none"><img src="images/loading.gif" style="width:70px" /><br><span style="color:maroon; vertical-align:middle">Please Wait</span></div>

	<input type="hidden" name="status" id="status" value="1" />
	<input type="hidden" name="master_cust" id="master_cust" value="<?php print $cu_id1; ?>" />
	<?php if ($components != 'topmanager') print '<input type="hidden" name="sub_systemc" id="sub_systemc" value="' . $sub_system . '" />'; ?>

	<table align="center" bgcolor="#E5E5E5" style="font-size:11pt; z-index:0; font-family:Calibri; border-radius: 15px 15px 0px 0px;">
		<tr>
			<td colspan="4"><br /></td>
		</tr>
		<tr>
			<td width="50px"></td>
			<td width="130px">Shop Name <span style="color:red;">*</span></td>
			<td width="250px">
				<input type="text" name="shop_name" id="shop_name" <?php print $systemid == 1 ? 'required' : 'required' ?> />
				<small>
					<div id="div_shop_name"></div>
				</small>
				<div id="suggesstion-cust2"></div>
			</td>
			<td width="50px">
				<div id="div_cust_list2"></div>
			</td>
		</tr>
		<tr>
			<td width="50px"></td>
			<td>NIC <span style="color:red;">*</span></td>
			<td><input type="text" name="nic" id="nic" <?php print $systemid == 1 ? 'required' : 'required' ?> />
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
				<input type="tel" name="mobile" id="mobile" <?php print $systemid == 1 ? 'required' : 'required' ?> />
				<small>
					<div id="div_mobile"></div>
				</small>
			</td>

			<td width="50px">
			</td>
		</tr>
		<tr>
			<td width="50px"></td>
			<td>Credit Limit <span style="color:red;">*</span></td>
			<td><input type="number" name="cr_limit" id="cr_limit" <?php print $systemid == 1 ? 'required' : 'required' ?> /></td>
			<td width="50px"></td>
		</tr>
		<tr>
			<td width="50px"></td>
			<td>Associated Shop <span style="color:red;">*</span></td>
			<td>
				<select id="store" name="store" <?php print $systemid == 1 ? 'required' : 'required' ?>>
					<option value="">-SELECT-</option>
					<?php for ($i = 0; $i < sizeof($st_id); $i++) {
						print '<option value="' . $st_id[$i] . '">' . $st_name[$i] . '</option>';
					} ?>
				</select>
			</td>
		</tr>
		<?php if (($systemid == 1) || ($systemid == 13) || ($systemid == 15)) { ?>
			<tr>
				<td width="50px"></td>
				<td>Associated <a title="Associated Salesman" href="#">SM</a> <span style="color:red;">*</span></td>
				<td>
					<select id="salesref" name="salesref" required>
						<option value="">-SELECT-</option>
						<?php for ($i = 0; $i < sizeof($sm_id); $i++) {
							print '<option value="' . $sm_id[$i] . '">' . ucfirst($sm_name[$i]) . '</option>';
						} ?>
					</select>
				</td>
			</tr>
		<?php } else print '<input type="hidden" id="salesref" name="salesref" value="' . $_COOKIE['user_id'] . '" />'; ?>
		<tr>
			<td width="50px"></td>
			<td>Associated Group <span style="color:red;">*</span></td>
			<td>
				<select name="cu_group" id="cu_group" <?php print $systemid == 1 ? 'required' : 'required' ?>>
					<option value="">-SELECT-</option>
					<?php for ($i = 0; $i < sizeof($gp_id); $i++) {
						print '<option value="' . $gp_id[$i] . '" >' . $gp_name[$i] . '</option>';
					} ?>
				</select>
				<input type="button" value="Manage Groups" onclick="window.location='index.php?components=<?php print $components; ?>&action=show_custgroup'" />
			</td>
		</tr>
		<tr>
			<td width="50px"></td>
			<td>Associated Town <span style="color:red;">*</span></td>
			<td>
				<select name="cu_town" id="cu_town" <?php print $systemid == 1 ? 'required' : 'required' ?>>
					<option value="">-SELECT-</option>
					<?php
					for ($i = 0; $i < sizeof($town_id); $i++) {
						//	if($town_id[$i]==$town_default) $select='selected="selected"'; else $select='';
						$select = '';
						print '<option value="' . $town_id[$i] . '" ' . $select . ' >' . $town_name[$i] . '</option>';
					} ?>
				</select>
				<input type="button" value="Manage Town" onclick="window.location='index.php?components=<?php print $components; ?>&action=show_custtown'" />
			</td>
		</tr>
		<tr>
			<td width="50px"></td>
			<td colspan="2">
				<hr />
			</td>
			<td width="50px"></td>
		</tr>
		<tr>
			<td width="50px"></td>
			<td>Customer Name <?php print $systemid == 1 ? "<span style='color:red;'>*</span>" : '' ?></td>
			<td><input type="text" name="customer" id="customer" <?php print $systemid == 1 ? 'required' : '' ?> /></td>
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
			<td>Nickname</td>
			<td><input type="text" name="nickname" id="nickname" /></td>
			<td width="50px"></td>
		</tr>
		<tr>
			<td width="50px"></td>
			<td>Shop Address <?php print $systemid == 1 ? '<span style="color:red;">*</span>' : '' ?></td>
			<td><textarea name="shop_address" id="shop_address" style="width:97%" <?php print $systemid == 1 ? 'required' : '' ?>></textarea></td>
			<td width="50px"></td>
		</tr>
		<tr>
			<td width="50px"></td>
			<td>Shop Tel <?php print $systemid == 1 ? '<span style="color:red;">*</span>' : '' ?></td>
			<td><input type="tel" name="shop_tel" id="shop_tel" <?php print $systemid == 1 ? 'required' : '' ?> /></td>
			<td width="50px"></td>
		</tr>
		<tr>
			<td width="50px"></td>
			<td>Home Address</td>
			<td><textarea name="home_address" id="home_address" style="width:97%"></textarea></td>
			<td width="50px"></td>
		</tr>
		<tr>
			<td width="50px"></td>
			<td>Home Tel</td>
			<td><input type="tel" name="home_tel" id="home_tel" /></td>
			<td width="50px"></td>
		</tr>
		<tr>
			<td width="50px"></td>
			<td>Email Address</td>
			<td><input type="text" name="email_add" id="email_add" style="width:100%" /></td>
			<td width="50px"></td>
		</tr>
		<tr>
			<td width="50px"></td>
			<td>Email Notifications </td>
			<td>&nbsp;&nbsp;&nbsp;<input type="checkbox" name="email_alert" id="email_alert" /></td>
			<td width="50px"></td>
		</tr>
		<tr>
			<td width="50px"></td>
			<td>SMS Notifications</td>
			<td>&nbsp;&nbsp;&nbsp;<input type="checkbox" name="sms" id="sms" checked="checked" /></td>
			<td width="50px"></td>
		</tr>
		<tr>
			<td width="50px"></td>
			<td>Customer Type</td>
			<td>&nbsp;&nbsp;
				<input type="radio" name="cu_type" value="1" checked> Wholesale
				<input type="radio" name="cu_type" value="2"> Retail<br>
			</td>
			<td width="50px"></td>
		</tr>

		<?php if ($_GET['components'] == 'topmanager') { ?>
			<tr>
				<td width="50px"></td>
				<td>System</td>
				<td>
					<select name="sub_systemc" id="sub_systemc">
						<?php for ($i = 0; $i < sizeof($sub_system_list); $i++) {
							print '<option value="' . $sub_system_list[$i] . '">' . $sub_system_names[$i] . '</option>';
						} ?>
					</select>
				</td>
				<td width="50px"></td>
			</tr>
		<?php } ?>
	</table>
</form>
<form enctype="multipart/form-data" action="index.php?components=<?php print $components ?>&action=add_cust_image" id="image_form" method="POST" id="image_form">
	<table align="center" bgcolor="#E5E5E5" style="z-index:0; font-family:Calibri; border-radius: 0px 0px 15px 15px;">
		<tr>
			<td colspan="4"><br /></td>
		</tr>
		<tr>
			<td width="50px"></td>
			<td width="130px" style="font-size: 16px;">Documents</td>
			<td width="250px" style="font-size: 12px;">
				<input type="file" name="fileToUpload1" /><br />
				<input type="file" name="fileToUpload2" /><br />
				<input type="file" name="fileToUpload3" /><br />
				<input type="file" name="fileToUpload4" /><br />
				<input type="hidden" name="cust_id" id="cust_id" value="" />
				<small>
					<div id="image_error"></div>
				</small>
			</td>
			<td width="48px"></td>
		</tr>
		<tr>
			<td colspan="4" align="center"><br />
				<div id="div_submit">
					<input type="submit" name="submit" id="submit" value="Add Customer" style="width:130px; height:50px" onclick="addCustomerAjax()" />
				</div>
				<br />
			</td>
		</tr>
		<tr>
			<td width="50px"></td>
			<td width="50px" style="font-size: 16px;"><small><span style="color:red;">*</span> Required fields </small><br /><br /></td>
		</tr>
	</table>
</form>