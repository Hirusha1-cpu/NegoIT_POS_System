<?php
include_once 'template/header.php';
?>
<!-- Scripts -->
<script type="text/javascript">
	function L2CategorySelection($selectedArray) {
		<?php for ($i = 0; $i < sizeof($category_l1); $i++) { ?>
			var <?php print $category_l1[$i]; ?> = [<?php for ($x = 0; $x < sizeof($category_l2[$category_l1[$i]]); $x++) {
					print '"' . $category_l2[$category_l1[$i]][$x] . '",';
				} ?>];
			if ($selectedArray == '<?php print $category_l1[$i]; ?>') $array_selected = <?php print $category_l1[$i]; ?>;
		<?php } ?>

		var $combo = '<select name="category_l2" id="category_l2"  onchange="L3CategorySelection()" style="width:150px"><option value="">-SELECT-</option>';
		for (i = 0; i < $array_selected.length; i++) {
			if ($array_selected[i] != '')
				$combo = $combo + '<option value="' + $array_selected[i] + '">' + $array_selected[i] + '</option>';
		}
		$combo = $combo + '</select>';
		document.getElementById('category_l2_div').innerHTML = $combo;
		document.getElementById('category_l3_div').innerHTML = '';
	}

	function L3CategorySelection() {
		var $L1selectedArray = document.getElementById('category_l1').value;
		var $selectedArray = document.getElementById('category_l2').value;
		subAccountSelection($selectedArray);
		var $selectedArray = $selectedArray.replace(/ /g, "_");
		var $L1selectedArray = $L1selectedArray.concat('_');
		var $selectedArray = $L1selectedArray.concat($selectedArray);
		<?php for ($i = 0; $i < sizeof($category_l2_list); $i++) { ?>
			var <?php print $category_l2_list[$i]; ?> = [<?php for ($x = 0; $x < sizeof($category_l3[$category_l2_list[$i]]); $x++) {
					print '"' . $category_l3[$category_l2_list[$i]][$x] . '",';
				} ?>];
			if ($selectedArray == '<?php print $category_l2_list[$i]; ?>') $array_selected = <?php print $category_l2_list[$i]; ?>;
		<?php } ?>
		var $combo = '<select name="category_l3" id="category_l3" style="width:150px"><option value="">-SELECT-</option>';
		for (i = 0; i < $array_selected.length; i++) {
			if ($array_selected[i] != '')
				$combo = $combo + '<option value="' + $array_selected[i] + '">' + $array_selected[i] + '</option>';
		}
		$combo = $combo + '</select>';
		document.getElementById('category_l3_div').innerHTML = $combo;

	}

	function subAccountSelection($selectedArray) {
		if ($selectedArray) {
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function () {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					var returntext = xmlhttp.responseText;
					if (returntext != null) {
						populateDropdown(returntext);
					} else {
						console.error("Response text is empty");
					}
				}
			};
			xmlhttp.open("POST", "index.php?components=<?php print $components; ?>&action=get_parent_accounts", true);
			xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xmlhttp.send('category=' + $selectedArray);
		} else {
			clearDropdown();
		}
	}
	function populateDropdown(jsonData) {
		try {
			var accounts = JSON.parse(jsonData);

			// Get the existing dropdown
			var dropdown = document.getElementById('parent_account_select');

			// Clear existing options except the first one
			dropdown.innerHTML = '<option value="">Select Parent Account</option>';

			// Check if accounts is an array
			if (accounts == null) {
				return;
			}
			// Add new options to the dropdown
			accounts.forEach(function (account) {
				var option = document.createElement("option");
				option.value = account.parent_account_id; // Set value as account ID
				option.text = account.parent_account_name; // Set text as account name
				dropdown.appendChild(option);
			});
		} catch (error) {
			console.error("Error parsing JSON data:", error);
		}
	}
	function clearDropdown() {
		var dropdown = document.getElementById('parent_account_select');
		dropdown.innerHTML = '<option value="">Select Parent Account</option>';
	}
</script>
<!--// Scripts -->

<!-- Notifications -->
<table align="center" style="font-size:12pt">
	<tr>
		<td>
			<?php
			if (isset($_REQUEST['message'])) {
				if ($_REQUEST['re'] == 'success')
					$color = 'green';
				else
					$color = 'red';
				print '<span style="color:' . $color . '; font-weight:bold;">' . $_REQUEST['message'] . '</span>';
			}
			?>
		</td>
	</tr>
</table>
<!--// Notifications -->

<table align="center">
	<tr>
		<td valign="top">
			<?php
			if (($_GET['action'] == 'chart_of_accounts') && ($components == 'fin'))
				include_once 'components/fin/view/tpl/add_acount.php';
			if (($_GET['action'] == 'one_chart_of_accounts') && ($components == 'fin'))
				include_once 'components/fin/view/tpl/edit_acount.php';
			?>
		</td>
		<td width="50px"></td>
		<td valign="top">

			<table align="center">
				<tr>
					<td valign="top">
						<?php
						if (($_GET['action'] == 'chart_of_accounts') && ($components == 'fin'))
							include_once 'components/fin/view/tpl/add_acount.php';
						if (($_GET['action'] == 'one_chart_of_accounts') && ($components == 'fin'))
							include_once 'components/fin/view/tpl/edit_acount.php';
						?>
					</td>
					<td width="50px"></td>
					<td valign="top">
						<table align="center" border="0" width="450px" style="font-family:Calibri">
							<tr bgcolor="#7e9099">
								<th align="left" style="padding-left:20px; color:white">Name</th>
								<th style="color:white" width="120px">Action</th>
							</tr>
							<?php
							$category_L1_tmp = '';
							for ($i = 0; $i < sizeof($ac_id); $i++) {
								if (($ac_system[$i] == 0) && ($components == 'fin'))
									$delete_ac = '<a href="index.php?components=' . $components . '&action=one_chart_of_accounts&id=' . $ac_id[$i] . '" style="text-decoration::none; color:red; font-weight:bold"><img src="images/edit.gif" /></a>&nbsp;&nbsp;&nbsp;<a href="#" onclick="deleteAC(' . $ac_id[$i] . ')" style="text-decoration::none; color:red; font-weight:bold"><img src="images/action_delete.gif" /></a>';
								else if (($ac_system[$i] == 0) && ($components == 'accounts'))
									$delete_ac = '<span style="font-style:italic; color:gray"></span>';
								else
									$delete_ac = '<span style="font-style:italic; color:gray">system account</span>';

								if (($ac_status[$i] == 0) && ($components == 'fin')) {
									$color = 'silver';
									$delete_ac = '';
								} else
									$color = 'black';
								if ($category_L1_tmp != $category_L1[$i])
									print '<tr bgcolor="#DDDDDD"><td style="padding-left:20px; font-weight:bold">' . $category_L1[$i] . '</td><td></td></tr>';
								print '<tr bgcolor="#f9f9f9"><td style="padding-left:40px;"><a href="index.php?components=' . $components . '&action=acount_history&id=' . $ac_id[$i] . '" style="color:' . $color . '; text-decoration:none;">' . $ac_name[$i] . '</a></td><td align="center">' . $delete_ac . '</td></tr>';
								$category_L1_tmp = $category_L1[$i];
							} ?>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<?php
include_once 'template/footer.php';
?>