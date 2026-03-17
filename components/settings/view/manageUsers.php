<?php
include_once 'template/header.php';
?>
<script src="js/md5.min.js"></script>
<link rel="stylesheet" href="plugin/jquery/css/jquery.ui.all.css" />
<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
<script src="plugin/jquery/ui/jquery.ui.core.js"></script>
<script src="plugin/jquery/ui/jquery.ui.widget.js"></script>
<script src="plugin/jquery/ui/jquery.ui.position.js"></script>
<script src="plugin/jquery/ui/jquery.ui.autocomplete.js"></script>
<link rel="stylesheet" href="plugin/jquery/css/demos.css" />

<script type="text/javascript">
	$(function () {
		var availableTags1 = [<?php for ($x = 0; $x < sizeof($bank_name); $x++) {
			print '"' . $bank_name[$x] . '",';
		} ?>];
		$("#bank_name").autocomplete({
			source: availableTags1
		});
	});

	function updatePermission(user, fn, action) {
		var component = document.getElementById('component').value;
		var id = user + '|' + fn;
		document.getElementById(user + '|action').innerHTML = document.getElementById('loading').innerHTML;
		if (action == 'update_permission') {
			if (document.getElementById(id).checked == true) $task_val = 'add'; else $task_val = 'del';
			$task_name = 'permission';
		}
		if (action == 'update_devicecheck') {
			if (document.getElementById(id).checked == true) $task_val = 'add'; else $task_val = 'del';
			$task_name = 'devicecheck';
		}
		if (action == 'update_timecheck') {
			if (document.getElementById(id).checked == true) $task_val = 'add'; else $task_val = 'del';
			$task_name = 'timecheck';
		}
		if (action == 'update_mobilerep') {
			if (document.getElementById(id).checked == true) $task_val = 'add'; else $task_val = 'del';
			$task_name = 'mobilerep';
		}

		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				var returntext = this.responseText;
				if (returntext == 'Done') {
					document.getElementById(user + '|action').innerHTML = '<span style="color:green">Done</span>';
				} else {
					document.getElementById(user + '|action').innerHTML = '<span style="color:red">Error</span>';
				}
			}
		};
		xhttp.open("GET", 'index.php?components=' + component + '&action=' + action + '&id=' + id + '&' + $task_name + '=' + $task_val, true);
		xhttp.send();
	}

	function updateStoreAso(id) {
		var store = document.getElementById(id).value;
		window.location = 'index.php?components=<?php print $components; ?>&action=update_storeaso&id=' + id + '&store=' + store;
	}

	function updateMapInvAso(id) {
		var store = document.getElementById(id).value;
		window.location = 'index.php?components=<?php print $components; ?>&action=update_mapinv&id=' + id + '&store=' + store;
	}

	function updateMapInvAso(id) {
		var mapinv = document.getElementById(id + 'map_inv').value;
		window.location = 'index.php?components=<?php print $components; ?>&action=update_mapinv&id=' + id + '&mapinv=' + mapinv;
	}

	function disableUser(id) {
		var check = confirm("Do you really want Deactivate this User?");
		if (check == true)
			window.location = 'index.php?components=<?php print $components; ?>&action=disable_user&id=' + id;
	}

	function enableUser(id) {
		var check = confirm("Do you really want Activate this User?");
		if (check == true)
			window.location = 'index.php?components=<?php print $components; ?>&action=enbale_user&id=' + id;
	}
</script>
<style>
	.truncate-text {
		display: block;
		width: 120px;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
		padding: 5px;
	}
</style>

<input type="hidden" id="component" value="<?php print $components; ?>" />

<div id="loading" style="display:none"><img src="images/loading.gif" style="width:25px" /></div>

<table align="center" border="0">
	<tr>
		<td>
			<?php
			if (isset($_REQUEST['message'])) {
				if ($_REQUEST['re'] == 'success')
					$color = 'green';
				else
					$color = '#DD3333';
				print '<script type="text/javascript">document.getElementById("notifications").innerHTML=' . "'" . '<span style="color:' . $color . '; font-weight:bold;font-size:12pt;">' . $_REQUEST['message'] . '</span>' . "'" . ';</script>';
			}
			?>
		</td>
	</tr>
</table>

<!-------------------------------- Manage user, Edit user Form -------------------------------->
<?php if ($_REQUEST['action'] == 'manage_user') { ?>
	<form autocomplete="off" action="index.php?components=<?php print $components; ?>&action=add_user"
		onsubmit="return validateUser()" method="post">
		<input type="hidden" id="action" value="create" />
		<input type="hidden" id="passhash" name="passhash" />
		<table align="center" bgcolor="#E5E5E5"
			style="font-size:12pt; font-family:Calibri; font-weight:bold; border-radius: 15px;">
			<tr>
				<td colspan="7" align="center" style="color:navy;"><strong>Create an New Employee</strong><br /><br /></td>
			</tr>
			<!-- Row 1: Employee Full Name, Employee Payment Bank -->
			<tr>
				<td width="50px"></td>
				<td>Employee Full Name</td>
				<td>
					<input type="text" name="emp_name" id="emp_name" autocomplete="off" />
				</td>
				<td width="100px"></td>
				<td>Employee Payment Bank</td>
				<td>
					<select id="bank" name="bank">
						<option value="all">--ALL--</option>
						<?php
						for ($i = 0; $i < sizeof($bank_id); $i++) {
							print '<option value="' . $bank_id[$i] . '">' . ucfirst($bank_name[$i]) . '</option>';
						}
						?>
					</select>
				</td>
				<td width="50px"></td>
			</tr>
			<!-- Row 2: Username, Bank Branch Name -->
			<tr>
				<td width="50px"></td>
				<td>Username</td>
				<td>
					<input type="text" name="user_name" id="user_name" autocomplete="off" />
				</td>
				<td width="100px"></td>
				<td>Bank Branch Name</td>
				<td>
					<input type="text" name="bank_branch" id="bank_branch" />
				</td>
				<td width="50px"></td>
			</tr>
			<!-- Row 3: Employee Number (ETF), Bank Branch Code -->
			<tr>
				<td width="50px"></td>
				<td>Employee Number (ETF)</td>
				<td>
					<input type="text" name="employee_no" id="employee_no" />
				</td>
				<td width="100px"></td>
				<td>Bank Branch Code</td>
				<td>
					<input type="text" name="branch_code" id="branch_code" />
				</td>
				<td width="50px"></td>
			</tr>
			<!-- Row 4: NIC, Bank A/C No -->
			<tr>
				<td width="50px"></td>
				<td>NIC</td>
				<td>
					<input type="text" name="nic" id="nic" />
				</td>
				<td width="100px"></td>
				<td>Bank A/C No</td>
				<td>
					<input type="text" name="bank_ac" id="bank_ac" />
				</td>
				<td width="50px"></td>
			</tr>
			<!-- Row 5: Password, Designation -->
			<tr>
				<td width="50px"></td>
				<td>Password</td>
				<td>
					<input type="password" name="user_pass1" id="user_pass1" autocomplete="off" />
				</td>
				<td width="100px"></td>
				<td>Designation</td>
				<td>
					<input type="text" name="designation" id="designation" />
				</td>
				<td width="50px"></td>
			</tr>
			<!-- Row 6: Confirm Password, Mobile No -->
			<tr>
				<td width="50px"></td>
				<td>Confirm Password</td>
				<td>
					<input type="password" name="user_pass2" id="user_pass2" autocomplete="off" />
				</td>
				<td width="100px"></td>
				<td>Mobile No</td>
				<td>
					<input type="text" name="mobile" id="mobile" />
				</td>
				<td width="50px"></td>
			</tr>
			<!-- Row 7: Sub System -->
			<tr>
				<td width="50px"></td>
				<td>Sub System</td>
				<td>
					<select name="sub_sys" id="sub_sys">
						<?php for ($i = 0; $i < sizeof($sub_sysid); $i++) {
							print '<option value="' . $sub_sysid[$i] . '">' . $sub_sysname[$i] . '</option>';
						} ?>
					</select>
				</td>
				<td width="100px"></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td width="50px"></td>
			</tr>
			<!-- Row 8: Change Password on Next Login -->
			<tr>
				<td width="50px"></td>
				<td colspan="2">
					Change Password on Next Login &nbsp;<input type="checkbox" name="change_pw" checked="checked"
						value="y" />
				</td>
				<td width="100px"></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td width="50px"></td>
			</tr>
			<!-- Row 9: Direct Marketing (if applicable) -->
			<?php if ($systemid == 13) { ?>
				<tr>
					<td width="50px"></td>
					<td colspan="2">
						Direct Marketing &nbsp;<input type="checkbox" name="direct_mkt" value="y" />
					</td>
					<td width="100px"></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td width="50px"></td>
				</tr>
			<?php } ?>
			<!-- Row 10: Submit Button -->
			<tr>
				<td colspan="7" align="center">
					<br /><input type="submit" value="Create Employee" style="width:150px; height:50px" /><br />
				</td>
			</tr>
		</table>
	</form>
<?php }
if ($_REQUEST['action'] == 'edit_user') { ?>
	<form action="index.php?components=<?php print $components; ?>&action=update_user" onsubmit="return validateUser()"
		method="post">
		<input type="hidden" id="action" value="edit" />
		<input type="hidden" id="passhash" name="passhash" />
		<input type="hidden" name="user_id" value="<?php print $usr_id1; ?>" />
		<table align="center" bgcolor="#E5E5E5" style="font-size:12pt; font-family:Calibri; font-weight:bold">
			<tr>
				<td colspan="7" align="center" style="color:navy;"><strong>Edit an Employee</strong><br /><br /></td>
			</tr>
			<tr>
				<td width="50px"></td>
				<td>Employee Full Name</td>
				<td><input type="text" name="emp_name" id="emp_name" value="<?php print $usr_emp_name; ?>" /></td>
				<td width="100px"></td>
				<td>Employee Payment Bank</td>
				<td>
					<select id="bank" name="bank">
						<option value="all">--ALL--</option>
						<?php
						for ($i = 0; $i < sizeof($bank_id); $i++) {
							if ($bank_id[$i] == $usr_bank_id) {
								$select = 'selected="selected"';
							} else {
								$select = '';
							}
							print '<option value="' . $bank_id[$i] . '" ' . $select . '>' . ucfirst($bank_name[$i]) . '</option>';
						}
						?>
					</select>
				</td>
				<td width="50px"></td>
			</tr>
			<tr>
				<td width="50px"></td>
				<td>Username</td>
				<td><input type="text" name="user_name" id="user_name" value="<?php print $usr_uname1; ?>" /></td>
				<td width="100px"></td>
				<td>Bank Branch Name</td>
				<td><input type="text" name="bank_branch" id="bank_branch" value="<?php print $usr_bank_branch; ?>" /></td>
				<td width="50px"></td>
			</tr>
			<tr>
				<td width="50px"></td>
				<td>Employee Number (ETF)</td>
				<td><input type="text" name="employee_no" id="employee_no" value="<?php print $usr_employee_no; ?>" /></td>
				<td width="100px"></td>
				<td>Bank Branch Code</td>
				<td><input type="text" name="branch_code" id="branch_code" value="<?php print $usr_branch_code; ?>" /></td>
				<td width="50px"></td>
			</tr>
			<tr>
				<td width="50px"></td>
				<td>NIC</td>
				<td><input type="text" name="nic" id="nic" value="<?php print $usr_nic; ?>" /></td>
				<td width="100px"></td>
				<td>Bank A/C No</td>
				<td><input type="text" name="bank_ac" id="bank_ac" value="<?php print $usr_bank_ac; ?>" /></td>
				<td width="50px"></td>
			</tr>
			<tr>
				<td width="50px"></td>
				<td>Password</td>
				<td><input type="password" name="user_pass1" id="user_pass1" /></td>
				<td width="100px"></td>
				<td>Designation</td>
				<td><input type="text" name="designation" id="designation"
						value="<?php print $usr_employee_designation; ?>" /></td>
				<td width="50px"></td>
			</tr>
			<tr>
				<td width="50px"></td>
				<td>Confirm Password</td>
				<td><input type="password" name="user_pass2" id="user_pass2" /></td>
				<td width="100px"></td>
				<td>Mobile No</td>
				<td><input type="text" name="mobile" id="mobile" value="<?php print $usr_mobile; ?>" /></td>
				<td width="50px"></td>
			</tr>
			<tr>
				<td width="50px"></td>
				<td colspan="2">Change Password on Next Login &nbsp;<input type="checkbox" name="change_pw"
						checked="checked" value="y" /></td>
				<td width="100px"></td>
				<td></td>
				<td></td>
				<td width="50px"></td>
			</tr>
			<?php if ($systemid == 13) { ?>
				<tr>
					<td width="50px"></td>
					<td colspan="2">Direct Marketing &nbsp;<input type="checkbox" name="direct_mkt" value="y" <?php print $usr_direct_mkt; ?> /></td>
					<td width="100px"></td>
					<td></td>
					<td></td>
					<td width="50px"></td>
				</tr>
			<?php } ?>
			<tr>
				<td colspan="7" align="center">
					<br />
					<?php if ($usr_status1 == 0) { ?>
						<input type="submit" value="Update Employee" style="width:150px; height:50px" /><input type="button"
							onclick="disableUser(<?php print $usr_id1; ?>)" value="Deactivate"
							style="width:80px; height:50px; background-color:maroon; color:white; font-weight:bold" />
					<?php } ?>
					<?php if ($usr_status1 == 1) { ?>
						<input type="button" onclick="enableUser(<?php print $usr_id1; ?>)" value="Activate"
							style="width:80px; height:50px; background-color:green; color:white; font-weight:bold" />
					<?php } ?>
					<br />
				</td>
			</tr>
		</table>
	</form>
<?php } ?>
<br />

<!-------------------------------- Scroll Table Header -------------------------------->
<div class="headerscrol" id="myHeader">
	<table id="myHeaderTB" align="center" style="font-family:Calibri; font-size:11pt;" border="1" bordercolor="white">
		<tr bgcolor="#467898" style="color:white; height:130px">
			<th width="150px"></th>
			<th class="rotate">
				<div>Device Check</div>
			</th>
			<th class="rotate">
				<div>Time Check</div>
			</th>
			<th class="rotate">
				<div>Mobile Rep</div>
			</th>
			<?php
			$list_sub_system = 0;
			for ($i = 0; $i < sizeof($function_id); $i++) {
				print '<th class="rotate"><div>' . $function_name[$i] . '</div></th>';
			}
			print '<th width="150px;"><div>Store</div></th>
					<th width="150px"><div>Inventory Mapping</div></th>
					<th style="width: 2.5em;" class="rotate"><div>Action</div></th>
					</tr>';
			?>
	</table>
</div>

<!-------------------------------- Scroll Table Body -------------------------------->
<div class="content">
	<table align="center" style="font-family:Calibri; font-size:11pt" border="1" bordercolor="white">
		<?php
		$list_sub_system = 0;
		for ($i = 0; $i < sizeof($uprof_id); $i++) {
			if (($_GET['action'] == 'manage_user') || (($_GET['action'] == 'edit_user') && ($uprof_id[$i] == $usr_id1))) {
				if ($uprof_status[$i] == 0) {
					$color = '';
					$disable = '';
				} else {
					$color = 'style="color:silver"';
					$disable = 'disabled="disabled"';
				}
				if ($uprof_timecheck[$i] == 1)
					$timecheck = 'checked="checked"';
				else
					$timecheck = '';
				if ($uprof_devicecheck[$i] == 1)
					$devicecheck = 'checked="checked"';
				else
					$devicecheck = '';
				if ($uprof_mobrep[$i] == 1)
					$mobilerep = 'checked="checked"';
				else
					$mobilerep = '';
				if ($list_sub_system != $uprof_sub_system[$i])
					print '<tr>
								<td colspan="' . (sizeof($function_id) + 7) . '" bgcolor="maroon" style="color:white"><strong>&nbsp;&nbsp;Sub System : ' . $uprof_sub_sysnm[$i] . '</strong></td>
							</tr>';
				print '<tr bgcolor="#EAEAEA">
						<th align="left"  width="150px"><a ' . $color . ' href="index.php?components=' . $components . '&action=edit_user&id=' . $uprof_id[$i] . '" class="truncate-text">' . ucfirst($uprof_name[$i]) . '</a>
						</th>
						<td align="center" bgcolor="#DDEAFF" class="rotate"><input style="cursor:pointer" type="checkbox" ' . $devicecheck . ' id="' . $uprof_id[$i] . '|devicecheck" onchange="updatePermission(' . "'" . $uprof_id[$i] . "','devicecheck','update_devicecheck'" . ')"  /></td>
						<td align="center" bgcolor="#DDEAFF" class="rotate"><input style="cursor:pointer" type="checkbox" ' . $timecheck . ' id="' . $uprof_id[$i] . '|timecheck" onchange="updatePermission(' . "'" . $uprof_id[$i] . "','timecheck','update_timecheck'" . ')"  /></td>
						<td align="center" bgcolor="#DDEAFF" class="rotate"><input style="cursor:pointer" type="checkbox" ' . $mobilerep . ' id="' . $uprof_id[$i] . '|mobilerep" onchange="updatePermission(' . "'" . $uprof_id[$i] . "','mobilerep','update_mobilerep'" . ')"  /></td>';

				$g = 0;
				for ($j = 0; $j < sizeof($function_id); $j++) {
					$emptybox = true;
					if ($g == 0) {
						$color3 = '#EAEAEA';
						$g = 1;
					} else {
						$color3 = '#CCCCCC';
						$g = 0;
					}
					for ($k = 0; $k < sizeof($per_id); $k++) {
						if (($uprof_name[$i] == $usr_name[$k]) && ($function_name[$j] == $usr_function[$k])) {
							print '<td align="center" bgcolor="' . $color3 . '" class="rotate"><input style="cursor:pointer" ' . $disable . ' type="checkbox" id="' . $uprof_id[$i] . '|' . $function_id[$j] . '" checked="checked"  onchange="updatePermission(' . "'" . $uprof_id[$i] . "','" . $function_id[$j] . "','" . 'update_permission' . "'" . ')" /></td>';
							$emptybox = false;
						}
					}
					if ($emptybox)
						print '<td align="center" bgcolor="' . $color3 . '" class="rotate"><input style="cursor:pointer" ' . $disable . ' type="checkbox" id="' . $uprof_id[$i] . '|' . $function_id[$j] . '" onchange="updatePermission(' . "'" . $uprof_id[$i] . "','" . $function_id[$j] . "','" . 'update_permission' . "'" . ')" /></td>';
				}
				print '<td width="150px" align="center">';
				if ($storeavailable[$uprof_id[$i]] > 0) {
					print '<select ' . $disable . ' name="' . $uprof_id[$i] . 'store" id="' . $uprof_id[$i] . 'store" onchange="updateStoreAso(' . "'" . $uprof_id[$i] . 'store' . "'" . ')">';
					print '<option value="0">--SELECT--</option>';
					for ($j = 0; $j < sizeof($st_id); $j++) {
						if ($uprof_store[$i] == $st_id[$j])
							$select = 'selected="selected"';
						else
							$select = '';
						if ($st_sub_sys[$j] == $uprof_sub_system[$i]) {
							print '<option value="' . $st_id[$j] . '" ' . $select . '>' . $st_name[$j] . '</option>';
						}
					}
				}
				print '</td><td width="150px" align="center">';
				if ($uprof_sub_system[$i] != 0) {
					if ($storeavailable[$uprof_id[$i]] > 0) {
						print '<select ' . $disable . ' name="' . $uprof_id[$i] . 'map_inv" id="' . $uprof_id[$i] . 'map_inv" onchange="updateMapInvAso(' . "'" . $uprof_id[$i] . "'" . ')">';
						print '<option value="0">--SELECT--</option>';
						for ($j = 0; $j < sizeof($st_id); $j++) {
							if ($uprof_map_inv[$i] == $st_id[$j])
								$select = 'selected="selected"';
							else
								$select = '';
							if ($st_sub_sys[$j] == 0) {
								print '<option value="' . $st_id[$j] . '" ' . $select . '>' . $st_name[$j] . '</option>';
							}
						}
					}
				}
				print '</td><td align="center" style="width: 2.5em;" ><div id="' . $uprof_id[$i] . '|action"></div></td></tr>';
				$list_sub_system = $uprof_sub_system[$i];
			}
		}
		?>
		</tr>
	</table>
</div>

<script>
	window.onscroll = function () { myFunction() };

	var header = document.getElementById("myHeader");
	var sticky = header.offsetTop;

	function myFunction() {
		if (window.pageYOffset >= sticky) {
			header.classList.add("sticky");
		} else {
			header.classList.remove("sticky");
		}
	}
</script>

<?php
include_once 'template/footer.php';
?>