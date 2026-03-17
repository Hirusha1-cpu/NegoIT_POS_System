<script type="text/javascript">
    $(document).ready(function() {
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

	function validateCust2(){
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
        if (document.getElementById('customer').value == '') $count++;
        if (document.getElementById('shop_address').value == '') $count++;
        if (document.getElementById('designation').value == '') $count++;

        const noRadio = document.getElementById("no");
        if (noRadio.checked) {
            if (document.getElementById('goods_delivery_contact_person').value == '') $count++;
		    if (document.getElementById('goods_delivery_mobile').value == '') $count++;
		    if (document.getElementById('goods_delivery_address').value == '') $count++;
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
			document.getElementById("cust_update").submit();
			return true;
		}
	}
</script>
<div id="loading" style="display:none"><img src="images/loading.gif" style="width:70px" /><br><span style="color:maroon; vertical-align:middle">Please Wait</span></div>

<form enctype="multipart/form-data" action="index.php?components=<?php print $_GET['components'] ?>&action=update_cust" onsubmit="validateCust2()" method="post" id="cust_update">
	<input type="hidden" name="cust_id" value="<?php print $cu_id1; ?>" />
    <input type="hidden" name="nikname" id="nikname" value="">
    <input type="hidden" name="nic" id="nic" value="0" />
    <?php
        if($cu_status1==1 || $cu_status1==3) $type =  1;
        if($cu_status1==2) $type =  2;
    ?>
    <input type="hidden" name="cust_type" id="cust_type" value="<?php echo $type; ?>" />

	<?php if($_GET['components']!='topmanager') print '<input type="hidden" name="sub_systemc" id="sub_systemc" value="'.$cu_sub_system.'" />'; ?>
	<table align="center" bgcolor="#E5E5E5" style="font-size:11pt; font-family:Calibri; border-radius: 15px;">
		<tr>
			<td colspan="4"><br/></td>
		</tr>
		<!-- Customer Name -->
		<tr>
			<td width="50px"></td>
			<td width="130px">Customer Name <span style="color:red;">*</span></td>
			<td width="250px">
				<input type="text" name="shop_name" id="shop_name" value="<?php print $cu_name1; ?>" <?php print $systemid == 13 ? 'required' : '' ?>/>
			</td>
			<td width="50px"></td>
		</tr>
        <!-- Designation-->
		<tr>
			<td width="50px"></td>
			<td>Designation <span style="color:red;">*</span></td>
			<td>
                <select name="designation" id="designation">
                    <option value="">-SELECT-</option>
                    <option value="owner" <?php if((isset($designation) && ($designation == 'owner'))) echo 'selected'; ?>>Owner</option>
                    <option value="general_manager" <?php if((isset($designation) && ($designation == 'general_manager'))) echo 'selected'; ?>>General Manager</option>
                    <option value="maintenance_manager" <?php if((isset($designation) && ($designation == 'maintenance_manager'))) echo 'selected'; ?>>Maintenance Manager</option>
                    <option value="purchasing_manager" <?php if((isset($designation) && ($designation == 'purchasing_manager'))) echo 'selected'; ?>>Purchasing Manager</option>
                    <option value="engineer" <?php if((isset($designation) && ($designation == 'engineer'))) echo 'selected'; ?>>Engineer</option>
                    <option value="other" <?php if((isset($designation) && ($designation == 'other'))) echo 'selected'; ?>>Other</option>
				</select>
			</td>
			<td width="50px"></td>
		</tr>
        <!-- Contact Person -->
		<tr>
			<td width="50px"></td>
			<td>Contact Person <span style="color:red;">*</span></td>
			<td>
				<input type="text" name="customer" id="customer" value="<?php print $cu_custname1; ?>" <?php print $systemid == 13 ? 'required' : '' ?>/>
			</td>
			<td width="50px"></td>
		</tr>
		<!-- Contact Number -->
		<tr>
			<td width="50px"></td>
			<td>Contact Number <span style="color:red;">*</span></td>
			<td>
				<input type="tel" name="mobile" id="mobile" value="<?php print $cu_mobile1; ?>" <?php print $systemid == 13 ? 'required' : 'required' ?>/>
			</td>
			<td width="50px"></td>
		</tr>
        <!-- Official General Number -->
		<tr>
			<td width="50px"></td>
			<td>Official General Number</td>
			<td>
				<input type="tel" name="shop_tel" id="shop_tel" value="<?php print $cu_shop_tel1; ?>" <?php print $systemid == 13 ? 'required' : '' ?>/>
			</td>
			<td width="50px"></td>
		</tr>
        <!-- Email Address -->
		<tr>
			<td width="50px"></td>
			<td>Email Address</td>
			<td>
				<input type="text" name="email_add" id="email_add" value="<?php print $cu_email_add; ?>" style="width:100%" />
			</td>
			<td width="50px"></td>
		</tr>
        <!-- Tax No -->
		<tr>
			<td width="50px"></td>
			<td>Tax No</td>
			<td>
				<input type="text" name="tax_no" id="tax_no" value="<?php print $cu_tax_no; ?>"/>
			</td>
			<td width="50px"></td>
		</tr>
		<!-- Credit Limit -->
		<tr>
			<td width="50px"></td>
			<td>Credit Limit <span style="color:red;">*</span></td>
			<td>
				<input type="number" name="cr_limit" id="cr_limit" value="<?php print $cu_crlimit1; ?>" <?php print $systemid == 13 ? 'required' : 'required' ?>/>
			</td>
			<td width="50px"></td>
		</tr>
		<!-- Associated Shop -->
		<tr>
			<td width="50px"></td>
			<td>Associated Shop <span style="color:red;">*</span></td>
			<td>
				<select id="store" name="cu_store">
				<?php for($i=0;$i<sizeof($st_id);$i++){
					if($cu_store==$st_id[$i]) $select='selected="selected"'; else $select='';
					print '<option value="'.$st_id[$i].'" '.$select.'>'.$st_name[$i].'</option>';
				}?>
				</select>
			</td>
		</tr>
		<!-- Associated SM -->
		<?php if(($systemid==1)||($systemid==13)||($systemid==15)){ ?>
		<tr>
			<td width="50px"></td>
			<td>Associated <a title="Associated Salesman" href="#">SM</a> <span style="color:red;">*</span></td>
			<td>
				<select id="salesref" name="salesref">
				<?php for($i=0;$i<sizeof($sm_id);$i++){
					if($cu_sa==$sm_id[$i]) $select='selected="selected"'; else $select='';
					print '<option value="'.$sm_id[$i].'" '.$select.'>'.ucfirst($sm_name[$i]).'</option>';
				}?>
				</select>
			</td>
		</tr>
		<?php }else print '<input type="hidden" id="salesref" name="salesref" value="'.$cu_sa.'" />'; ?>
		<!-- Associated Group -->
		<tr>
			<td width="50px"></td>
			<td>Associated Group <span style="color:red;">*</span></td>
			<td>
				<select name="cu_group" id="cu_group">
				<?php for($i=0;$i<sizeof($gp_id);$i++){
					if($cu_group==$gp_id[$i]) $select='selected="selected"'; else $select='';
					print '<option value="'.$gp_id[$i].'" '.$select.'>'.$gp_name[$i].'</option>';
				}?>
				</select>
				<input type="button" value="Manage Groups" onclick="window.location='index.php?components=<?php print $components; ?>&action=show_custgroup'" />
			</td>
		</tr>
        <!-- Associated Town -->
		<tr>
			<td width="50px"></td>
			<td>Associated Town <span style="color:red;">*</span></td>
			<td>
				<select name="cu_town" id="cu_town">
				<option value="">-SELECT-</option>
				<?php for($i=0;$i<sizeof($town_id);$i++){
					if($cu_town==$town_id[$i]) $select='selected="selected"'; else $select='';
					print '<option value="'.$town_id[$i].'" '.$select.'>'.$town_name[$i].'</option>';
				}?>
				</select>
				<input type="button" value="Manage Town" onclick="window.location='index.php?components=<?php print $components; ?>&action=show_custtown'" />
			</td>
		</tr>
        <!-- Company Address -->
		<tr>
			<td width="50px"></td>
			<td>Company Address <?php print $systemid == 13 ? '<span style="color:red;">*</span>' : '' ?></td>
			<td>
				<textarea name="shop_address" id="shop_address" style="width:97%" <?php print $systemid == 13 ? 'required' : '' ?>><?php print $cu_shop_add1; ?></textarea>
			</td>
			<td width="50px"></td>
		</tr>

        <!-- Delivery Details are same  -->
        <tr>
            <td width="50px"></td>
            <td>Delivery details are same?</td>
            <td>
            <label>
                <input type="radio" name="delivery_details_same" id="yes" value="1" <?php if((isset($delivery_details_same)) && ($delivery_details_same === '1')) echo 'checked'?>> Yes
            </label>
            <label>
                <input type="radio" name="delivery_details_same" id="no" value="0" <?php if((isset($delivery_details_same)) && ($delivery_details_same === '0')) echo 'checked'?>> No
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
                <input type="text" name="goods_delivery_contact_person" id="goods_delivery_contact_person" value="<?php if((isset($goods_delivery_contact_person)) && ($goods_delivery_contact_person !== '')) print $goods_delivery_contact_person; ?>"/>
            </td>
            <td width="50px"></td>
        </tr>
        <!-- Contact Number -->
        <tr id="contact_number_row" style="display:none;">
            <td width="50px"></td>
            <td>Contact Number <span style="color:red;">*</span></td>
            <td>
                <input type="tel" name="goods_delivery_mobile" id="goods_delivery_mobile" value="<?php if((isset($goods_delivery_mobile)) && ($goods_delivery_mobile !== '')) print $goods_delivery_mobile; ?>"/>
            </td>
            <td width="50px"></td>
        </tr>
        <!-- Delivery Address -->
        <tr id="delivery_address_row" style="display:none;">
            <td width="50px"></td>
            <td>Delivery Address <span style="color:red;">*</span></td>
            <td>
                <textarea name="goods_delivery_address" id="goods_delivery_address" style="width:97%"><?php if((isset($goods_delivery_address)) && ($goods_delivery_address !== '')) print $goods_delivery_address; ?></textarea>
            </td>
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
				<input type="tel" name="home_tel" id="home_tel" value="<?php print $cu_home_tel1; ?>"/>
			</td>
			<td width="50px"></td>
		</tr>
        <!-- Personal Address -->
		<tr>
			<td width="50px"></td>
			<td>Personal Address</td>
			<td>
				<textarea name="home_address" id="home_address" style="width:97%"><?php print $cu_home_add1; ?></textarea>
			</td>
			<td width="50px"></td>
		</tr>
		<!-- Customer DOB -->
		<tr>
			<td width="50px"></td>
			<td>Customer DOB</td>
			<td>
				<input type="date" name="dob" id="dob" value="<?php print $cu_dob1; ?>"/>
			</td>
			<td width="50px"></td>
		</tr>
		<!-- Email Notifications -->
		<tr>
			<td width="50px"></td>
			<td>Email Notifications</td>
			<td>
				&nbsp;&nbsp;&nbsp;<input type="checkbox" name="email_alert" id="email_alert" <?php if($cu_email_alert==1) print 'checked="checked"'; ?> />
			</td>
			<td width="50px"></td>
		</tr>
		<!-- SMS Notifications -->
		<tr>
			<td width="50px"></td>
			<td>SMS Notifications</td>
			<td>
				&nbsp;&nbsp;&nbsp;<input type="checkbox" name="sms" id="sms" <?php if($cu_sms==1) print 'checked="checked"'; ?> />
			</td>
			<td width="50px"></td>
		</tr>
		<!-- Documents -->
		<?php if(($cu_image1==0)||($cu_image2==0)||($cu_image3==0)||($cu_image4==0)){ ?>
			<tr>
				<td width="50px"></td>
				<td>Documents</td>
				<td>
					<?php
						if($cu_image1==0) print '<input type="file" name="fileToUpload1" /><br />'; else print '<div style="display:none"><input type="file" name="fileToUpload1" ></div>';
						if($cu_image2==0) print '<input type="file" name="fileToUpload2" /><br />'; else print '<div style="display:none"><input type="file" name="fileToUpload2" ></div>';
						if($cu_image3==0) print '<input type="file" name="fileToUpload3" /><br />'; else print '<div style="display:none"><input type="file" name="fileToUpload3" ></div>';
						if($cu_image4==0) print '<input type="file" name="fileToUpload4" /><br />'; else print '<div style="display:none"><input type="file" name="fileToUpload4" ></div>';
					?>
				</td>
				<td width="50px"></td>
			</tr>
		<?php } ?>
		<tr>
			<td width="50px"></td>
			<td colspan="2">
				<table width="100%">
					<tr>
						<?php
							if($cu_image1==1) print '<td><input type="button" value="Image1" onclick="showImage('."'imageframe1'".')" /></td>';
							if($cu_image2==1) print '<td><input type="button" value="Image2" onclick="showImage('."'imageframe2'".')" /></td>';
							if($cu_image3==1) print '<td><input type="button" value="Image3" onclick="showImage('."'imageframe3'".')" /></td>';
							if($cu_image4==1) print '<td><input type="button" value="Image4" onclick="showImage('."'imageframe4'".')" /></td>';
						?>
					</tr>
				</table>
			</td>
			<td width="50px"></td>
		</tr>
		<?php if($_GET['components']=='topmanager'){ ?>
		<!-- System -->
		<tr>
			<td width="50px"></td>
			<td>System</td>
			<td>
				<select name="sub_systemc" id="sub_systemc" >
				<?php for($i=0;$i<sizeof($sub_system_list);$i++){
					if($cu_sub_system==$sub_system_list[$i]) $select='selected="selected"'; else $select='';
					print '<option value="'.$sub_system_list[$i].'" '.$select.'>'.$sub_system_names[$i].'</option>';
				} ?>
				</select>
			</td>
			<td width="50px"></td>
		</tr>
		<!-- Cross Reference -->
		<tr>
			<td width="50px"></td>
			<td>
				<a title="If [another/this] customer was refered by [this/another] customer, it will show here" style="cursor:pointer; color:blue">Cross Reference</a>
			</td>
			<td width="250px">
				<?php for($i=0;$i<sizeof($refer_cust);$i++){
					print '<a href="index.php?components=topmanager&action=editcust&id='.$refer_cust[$i].'">'.$refer_cust[$i].'</a>, ';
				} ?>
			</td>
			<td width="50px"></td>
		</tr>
		<?php } ?>
		<!-- Created By -->
		<tr>
			<td width="50px"></td>
			<td>
				<i>Created By</i>
			</td>
			<td>
				<a  style="cursor:pointer; color:blue;" title="<?php print $cu_cre_time; ?>"><i><?php print $cu_cre_by; ?></i></a>
			</td>
			<td width="50px"></td>
		</tr>
		<?php if($cu_status1==1){
			print '<tr><td width="50px"></td><td><i>Approved By</i></td><td><a  style="cursor:pointer; color:blue;" title="'.$cu_app_time.'"><i>'.$cu_app_by.'</i></a></td><td width="50px"></td></tr>';
		} ?>
		<tr>
			<td colspan="4" align="center"><br />
				<?php if($cu_status1==0){ ?><div id="div_submit"><input type="button" onclick="enableCust(<?php print $cu_id1; ?>)" value="Activate" style="width:80px; height:50px; background-color:green; color:white; font-weight:bold" /></div>
				<?php }else if($cu_status1==3){ ?><input type="hidden" name="approved_by" value="<?php print $_COOKIE['user_id']; ?>" /><div id="div_submit"><input type="submit" value="Approve Customer" style="width:130px; height:50px" /><input type="button" onclick="deleteCust(<?php print $cu_id1; ?>)" value="Reject" style="width:90px; height:50px; background-color:maroon; color:white; font-weight:bold" /></div>
				<?php }else{ ?><div id="div_submit"><input type="submit" value="Update Customer" style="width:130px; height:50px" /><input type="button" onclick="disableCust(<?php print $cu_id1; ?>)" value="Deactivate" style="width:90px; height:50px; background-color:maroon; color:white; font-weight:bold" /></div><?php } ?>
				<br /><br />
			</td>
		</tr>
	</table>
</form>
