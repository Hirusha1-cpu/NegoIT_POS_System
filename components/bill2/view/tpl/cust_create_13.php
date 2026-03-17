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
		reqUrl = 'cust-list-wholesale';
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
						setTimeout(function() {
    						$("#suggesstion-cust").hide();
						}, 3000);
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

        // Get references to the radio buttons and the rows
        const yesRadio = document.getElementById("yes");
        const noRadio = document.getElementById("no");
        const deliveryDetailsHeader = document.getElementById("delivery_details_header");
        const contactPersonRow = document.getElementById("contact_person_row");
        const contactNumberRow = document.getElementById("contact_number_row");
        const deliveryAddressRow = document.getElementById("delivery_address_row");

        // Function to toggle the rows' visibility based on radio button selection
        function toggleRowsVisibility() {
            if (yesRadio.checked) {
                deliveryDetailsHeader.style.display = "none"; // Hide the header row
                contactPersonRow.style.display = "none"; // Hide the contact person row
                contactNumberRow.style.display = "none"; // Hide the contact number row
                deliveryAddressRow.style.display = "none"; // Hide the delivery address row
            } else {
                deliveryDetailsHeader.style.display = "table-row"; // Show the header row
                contactPersonRow.style.display = "table-row"; // Show the contact person row
                contactNumberRow.style.display = "table-row"; // Show the contact number row
                deliveryAddressRow.style.display = "table-row"; // Show the delivery address row
            }
        }

        // Add event listeners to the radio buttons
        yesRadio.addEventListener("change", toggleRowsVisibility);
        noRadio.addEventListener("change", toggleRowsVisibility);

        // Call the function initially to set the initial state
        toggleRowsVisibility();
	});

	function selectCust(val) {
		$("#shop_name").val(val);
		$("#suggesstion-cust").hide();
		// getCustData('name', val);
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
		if (document.getElementById('shop_address').value == '') $count++;
		if (document.getElementById('designation').value == '') $count++;
		if (document.getElementById('customer').value == '') $count++;
		if (document.getElementById('cu_group').value == '') $count++;
		if (document.getElementById('cu_town').value == '') $count++;
		if (document.getElementById('cr_limit').value == '') $count++;
		if (document.getElementById('store').value == '') $count++;
		if (document.getElementById('salesref').value == '') $count++;

        const yesRadio = document.getElementById("no");
        if (yesRadio.checked) {
            if (document.getElementById('goods_delivery_contact_person').value == '') $count++;
		    if (document.getElementById('goods_delivery_mobile').value == '') $count++;
		    if (document.getElementById('goods_delivery_address').value == '') $count++;
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
			let designation = document.getElementById('designation').value;
			let customer = document.getElementById('customer').value;
			let mobile = document.getElementById('mobile').value;
			let shop_tel = document.getElementById('shop_tel').value;
			let email_add = document.getElementById('email_add').value;
			let tax_no = document.getElementById('tax_no').value;
			let shop_address = document.getElementById('shop_address').value;

			let goods_delivery_contact_person = document.getElementById('goods_delivery_contact_person').value;
			let goods_delivery_mobile = document.getElementById('goods_delivery_mobile').value;
			let goods_delivery_address = document.getElementById('goods_delivery_address').value;
			let home_address = document.getElementById('home_address').value;
			let home_tel = document.getElementById('home_tel').value;
			let dob = document.getElementById('dob').value;

			let nic = document.getElementById('nic').value;
			let cr_limit = document.getElementById('cr_limit').value;
			let nickname = document.getElementById('nickname').value;
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

			let status = document.getElementById('status').value;
			let master_cust = document.getElementById('master_cust').value;
			let sub_systemc = document.getElementById('sub_systemc').value;

            let delivery_details_same = '';
            if(document.getElementById("yes").checked) delivery_details_same = '1';
            else delivery_details_same = '0';

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
					tax_no: tax_no,
					email_add: email_add,
					sms: sms,
					store: store,
					salesref: salesref,
					cu_group: cu_group,
					cu_town: cu_town,
					status: status,
					master_cust: master_cust,
					sub_systemc: sub_systemc,
                    designation:designation,
                    delivery_details_same:delivery_details_same,
                    goods_delivery_contact_person:goods_delivery_contact_person,
                    goods_delivery_mobile:goods_delivery_mobile,
                    goods_delivery_address:goods_delivery_address
				},
				beforeSend: function() {
					document.getElementById('div_submit').innerHTML = document.getElementById('loading').innerHTML;
				},
				success: function(data) {
					var obj = JSON.parse(data);
                    console.log(obj);
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
					// user created successfully
					if (obj.user_create) {
						document.getElementById("notifications").innerHTML = '<span style="color: green; font-weight:bold; font-size:12pt;">'+obj.user_create+'</span>';
                        // upload images
                        document.getElementById('cust_id').value = obj.cust_id;
						setTimeout(function () {
							$("#image_form").submit();
						}, 2000);
					}
					if (obj.cust_error) {
						toastr.options = toasterOptions;
						toastr.error(obj.cust_error, 'Error!');
						document.getElementById("notifications").innerHTML = '<span style="color: #DD3333; font-weight:bold; font-size:12pt;">Customer could not be created!</span>';

						document.getElementById('div_submit').innerHTML = button;
						document.getElementById('shop_name').value = '';
						document.getElementById('shop_address').value = '';
                        document.getElementById('designation').value = '';
                        document.getElementById('customer').value = '';
						document.getElementById('mobile').value = '';
                        document.getElementById('dob').value = "";
                        document.getElementById('tax_no').value = "";
                        document.getElementById('email_add').value = "";
                        document.getElementById('shop_tel').value = "";
                        document.getElementById('home_tel').value = "";
                        document.getElementById('home_address').value = ''
                        document.getElementById('goods_delivery_contact_person').value = ''
                        document.getElementById('goods_delivery_mobile').value = ''
                        document.getElementById('goods_delivery_address').value = ''
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
    if ($_GET['action'] == 'wholesale_cust') $form_action = 'add_cust_image1';
    else $form_action = 'add_cust_image2';
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
    <input type="hidden" name="cu_town" id="cu_town" value="<?php print $town_default; ?>" />
    <input type="hidden" name="sms" id="sms" value="1" />
    <input type="hidden" name="status" id="status" value="3" />
    <input type="hidden" name="nickname" id="nickname" value="">
    <input type="hidden" name="cu_group" id="cu_group" value="1" />
    <input type="hidden" name="nic" id="nic" value="0" />

	<?php if ($_GET['components'] != 'topmanager') print '<input type="hidden" name="sub_systemc" id="sub_systemc" value="' . $sub_system . '" />'; ?>


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
        <!-- ----------------- Customer Details ------------------- -->
        <!-- Customer Name -->
		<tr>
			<td width="50px"></td>
			<td width="130px">
                Customer Name<span style="color:red;">*</span>
			</td>
			<td width="250px">
				<input type="text" name="shop_name" id="shop_name" />
				<small>
					<div id="div_shop_name"></div>
				</small>
				<div id="suggesstion-cust"></div>
			</td>
			<td width="50px">
				<div id="div_cust_list2"></div>
				<div style="color:red" id="div_master_sys"></div>
			</td>
		</tr>
        <!-- Designation-->
		<tr>
			<td width="50px"></td>
			<td>Designation <span style="color:red;">*</span></td>
			<td>
                <select name="designation" id="designation">
                    <option value="">-SELECT-</option>
                    <option value="owner">Owner</option>
                    <option value="general_manager">General Manager</option>
                    <option value="maintenance_manager">Maintenance Manager</option>
                    <option value="purchasing_manager">Purchasing Manager</option>
                    <option value="engineer">Engineer</option>
                    <option value="other">Other</option>
				</select>
			</td>
			<td width="50px"></td>
		</tr>
        <!-- Contact Person -->
        <tr>
			<td width="50px"></td>
			<td>Contact Person <span style="color:red;">*</span></td>
			<td>
				<input type="text" name="customer" id="customer" />
			</td>
			<td width="50px"></td>
		</tr>
        <!-- Contact Number -->
		<tr>
			<td width="50px"></td>
			<td>Contact Number <span style="color:red;">*</span></td>
			<td>
				<input type="tel" name="mobile" id="mobile" />
				<small>
					<div id="div_mobile"></div>
				</small>
			</td>
			<td width="50px"></td>
		</tr>
        <!-- Official General Number -->
        <tr>
            <td width="50px"></td>
            <td>Official General Number</td>
            <td><input type="tel" name="shop_tel" id="shop_tel" /></td>
            <td width="50px"></td>
		</tr>
        <!-- Email -->
		<tr>
			<td width="50px"></td>
			<td>Email</td>
			<td>
				<input type="text" name="email_add" id="email_add" />
			</td>
			<td width=" 50px"></td>
		</tr>
        <!-- Tax No -->
        <tr>
            <td width="50px"></td>
            <td>Tax No</td>
            <td><input type="text" name="tax_no" id="tax_no" value="<?php print $cu_tax_no; ?>" /></td>
            <td width="50px"></td>
		</tr>
        <!-- Delivery Address -->
        <tr>
            <td width="50px"></td>
            <td>Company Address <span style="color:red;">*</span></td>
            <td><textarea name="shop_address" id="shop_address" style="width:97%"></textarea></td>
            <td width="50px"></td>
        </tr>

        <!-- Delivery Details are same  -->
        <tr>
            <td width="50px"></td>
            <td>Delivery details are same?</td>
            <td>
            <label>
                <input type="radio" name="delivery_details_same" id="yes" value="yes" checked> Yes
            </label>
            <label>
                <input type="radio" name="delivery_details_same" id="no" value="no"> No
            </label>
            </td>
            <td width="50px"></td>
        </tr>

        <tr>
            <td width="50px"></td>
            <td colspan="2">
                <hr />
            </td>
            <td width="50px"></td>
		</tr>

        <!-- ----------------- Goods Delivery Details ------------------- -->
        <tr id="delivery_details_header" style="display:none;">
            <td colspan="4" align="center">
                <p>Goods Delivery Details</p>
            </td>
        </tr>
        <!-- Contact Person -->
        <tr id="contact_person_row" style="display:none;">
            <td width="50px"></td>
            <td>Contact Person <span style="color:red;">*</span></td>
            <td>
                <input type="text" name="goods_delivery_contact_person" id="goods_delivery_contact_person" />
            </td>
            <td width="50px"></td>
        </tr>
        <!-- Contact Number -->
        <tr id="contact_number_row" style="display:none;">
            <td width="50px"></td>
            <td>Contact Number <span style="color:red;">*</span></td>
            <td>
                <input type="tel" name="goods_delivery_mobile" id="goods_delivery_mobile" />
            </td>
            <td width="50px"></td>
        </tr>
        <!-- Delivery Address -->
        <tr id="delivery_address_row" style="display:none;">
            <td width="50px"></td>
            <td>Delivery Address <span style="color:red;">*</span></td>
            <td><textarea name="goods_delivery_address" id="goods_delivery_address" style="width:97%"></textarea></td>
            <td width="50px"></td>
        </tr>

        <!-- ----------------- Aditional Details ------------------- -->
        <tr>
            <td colspan="4" align="center">
                <p>Additional Details</p>
            </td>
        </tr>
        <!-- Personal Contact Number -->
		<tr>
			<td width="50px"></td>
			<td>Personal Contact Number</td>
			<td>
				<input type="tel" name="home_tel" id="home_tel" />
			</td>
			<td width="50px"></td>
		</tr>
        <!-- Personal Address -->
        <tr>
            <td width="50px"></td>
            <td>Personal Address</td>
            <td><textarea name="home_address" id="home_address" style="width:97%"></textarea></td>
            <td width="50px"></td>
        </tr>
        <tr>
            <td width="50px"></td>
            <td>Customer DOB</td>
            <td><input type="date" name="dob" id="dob"/></td>
            <td width="50px"></td>
        </tr>
    </table>
</form>
<form enctype="multipart/form-data" action="index.php?components=<?php print $components; ?>&action=<?php print $form_action; ?>&s=<?php print $_GET['sm_id']; ?>&cust_odr=<?php print $_GET['cust_odr']; ?>" id="image_form" method="POST" name="image_form">
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