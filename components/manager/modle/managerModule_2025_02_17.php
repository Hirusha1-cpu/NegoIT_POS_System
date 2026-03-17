<?php

function warrantyStatus2($st_id)
{
	switch ($st_id) {
		case 0:
			$jasonArray["st_name"] = 'Deleted';
			$jasonArray["st_color"] = 'red';
			break;
		case 1:
			$jasonArray["st_name"] = 'Initiated';
			$jasonArray["st_color"] = 'orange';
			break;
		case 2:
			$jasonArray["st_name"] = 'Ongoin';
			$jasonArray["st_color"] = '#9900CC';
			break;
		case 3:
			$jasonArray["st_name"] = 'Handed Over';
			$jasonArray["st_color"] = '#00DDEE';
			break;
		case 4:
			$jasonArray["st_name"] = 'Completed';
			$jasonArray["st_color"] = 'white';
			break;
	}
	$myJSON = json_encode($jasonArray);
	return $myJSON;
}

// updated by nirmal 28_04_2022
function getCust($sub_system, $status)
{
	global $cu_id, $cu_id0, $cu_name, $cu_name0, $cu_nic, $cu_mobile, $cu_cr_limit, $cu_status, $cu_sub_sys;
	if ($sub_system == 'all')
		$sub_sys_qry = '';
	else
		$sub_sys_qry = "AND cu.`sub_system`='$sub_system'";
	$cu_id = $cu_id0 = $cu_name = array();
	include('config.php');
	$query = "SELECT cu.id,cu.name,cu.nic,cu.mobile,cu.credit_limit,cu.`status`,ss.name FROM cust cu, sub_system ss WHERE cu.`sub_system`=ss.id AND cu.`status` IN ($status) $sub_sys_qry ORDER BY FIELD(cu.`status`,'3','1','2','0'),cu.sub_system,cu.id DESC";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$cu_id[] = $row[0];
		if ($row[5] == 1)
			$cu_id0[] = $row[0];
		$cu_name[] = wordwrap($row[1], 25, "<br />\n");
		if ($row[5] == 1)
			$cu_name0[] = $row[1];
		$cu_nic[] = $row[2];
		$cu_mobile[] = $row[3];
		$cu_cr_limit[] = $row[4];
		$cu_status[] = $row[5];
		$cu_sub_sys[] = $row[6];
	}
}

function getCustSearchList($sub_system)
{
	global $cu_id, $cu_id0, $cu_name, $cu_name0, $cu_nic, $cu_mobile, $cu_cr_limit, $cu_status, $cu_sub_sys;
	$namesearch = $_POST['namesearch'];
	$mobsearch = $_POST['mobsearch'];
	if ($sub_system == 'all')
		$sub_sys_qry = '';
	else
		$sub_sys_qry = "AND cu.`sub_system`='$sub_system'";
	$cu_id = $cu_id0 = $cu_name = array();
	$proceed = false;
	$search_qry = '';

	if ($namesearch != '') {
		$search_qry = "AND cu.`name` LIKE '%$namesearch%'";
		$proceed = true;
	} elseif ($mobsearch != '') {
		$search_qry = "AND cu.`mobile` LIKE '%$mobsearch%'";
		$proceed = true;
	}
	if ($proceed) {
		include('config.php');
		$query = "SELECT cu.id,cu.name,cu.nic,cu.mobile,cu.credit_limit,cu.`status`,ss.name FROM cust cu, sub_system ss WHERE cu.`sub_system`=ss.id $search_qry $sub_sys_qry ORDER BY FIELD(cu.`status`,'3','1','2','0'),cu.sub_system,cu.id";
		$result = mysqli_query($conn2, $query);
		while ($row = mysqli_fetch_array($result)) {
			$cu_id[] = $row[0];
			if ($row[5] == 1)
				$cu_id0[] = $row[0];
			$cu_name[] = wordwrap($row[1], 25, "<br />\n");
			if ($row[5] == 1)
				$cu_name0[] = $row[1];
			$cu_nic[] = $row[2];
			$cu_mobile[] = $row[3];
			$cu_cr_limit[] = $row[4];
			$cu_status[] = $row[5];
			$cu_sub_sys[] = $row[6];
		}
	}
}

// added by E.S.P Nirmal 2021_06_02
function getCust2($status)
{
	global $cust_id, $cust_name, $cust_nic, $cust_mobile, $cust_asso_sman;
	include('config.php');
	$query = "SELECT cu.id,cu.name,cu.nic,cu.mobile,up.username FROM cust cu, userprofile up WHERE cu.associated_salesman=up.id AND cu.`status` IN ($status)";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$cust_id[] = $row[0];
		$cust_name[] = $row[1];
		$cust_nic[] = $row[2];
		$cust_mobile[] = $row[3];
		$cust_asso_sman[] = $row[4];
	}
}

// added by E.S.P Nirmal 2021_06_02 for billing module action=onetime_cust
function getCust2Ajax($sub_system)
{
	global $data_list, $fn;
	$data_list = array();

	if ($_POST['keyword']) {
		$keyword = $_POST['keyword'];
		$qry = "`name` LIKE '%$keyword%'";
		$fn = 'selectCust';
		include('config.php');
		$query = "SELECT `name`,`nickname`,mobile FROM cust WHERE `sub_system`='$sub_system' AND `status` IN (1,2) AND $qry LIMIT 20";
		$result = mysqli_query($conn, $query);
		while ($row = mysqli_fetch_array($result)) {
			$data_list[] = $row[0];
		}
	}
}

function getSalesman($sub_system)
{
	global $sm_id, $sm_name;
	if ($sub_system == 'all')
		$sub_system_qry = '';
	else
		$sub_system_qry = "AND `sub_system`='$sub_system'";
	include('config.php');
	$query = "SELECT id,username FROM userprofile WHERE `status`=0 $sub_system_qry ORDER BY username";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$sm_id[] = $row[0];
		$sm_name[] = $row[1];
	}
}

function getStore($sub_system)
{
	global $st_id, $st_name;
	if ($sub_system == 'all')
		$sub_system_qry = '';
	else
		$sub_system_qry = "AND `sub_system`='$sub_system'";
	include('config.php');
	$query = "SELECT id,name FROM stores WHERE `status`=1 $sub_system_qry ORDER BY name";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$st_id[] = $row[0];
		$st_name[] = $row[1];
	}
}

function getCategory()
{
	global $cat_id, $cat_name;
	$cat_id = $cat_name = array();
	include('config.php');
	$query = "SELECT id,name FROM item_category ORDER BY name";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$cat_id[] = $row[0];
		$cat_name[] = $row[1];
	}
}

// updated by nirmal 30_01_2025 added mk module cust group filters
function getCustGroups($sub_system)
{
	global $gp_id, $gp_name;
	$user_id = $_COOKIE['user_id'];
	$gp_id = array();
	include('config.php');

	if ($sub_system == 'all') {
		$sub_system_qry = $sub_system_qry3 = '';
	} else {
		$sub_system_qry = "WHERE `sub_system`='$sub_system'";
		$sub_system_qry3 = "AND cg.`sub_system`='$sub_system'";
	}

	if ($_GET['components'] == 'marketing') {
		$query = "SELECT cg.id,cg.`name` FROM cust_group cg, user_to_group ug WHERE cg.id=ug.`group` AND ug.`user`='$user_id' $sub_system_qry3 ORDER BY cg.`name`";
	} else { // sup, mgr
		$query = "SELECT id,`name` FROM cust_group $sub_system_qry ORDER BY name";
	}
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$gp_id[] = $row[0];
		$gp_name[] = $row[1];
	}
}

function getTown()
{
	global $town_id, $town_name, $town_default;
	$town_id = array();
	$town_default = '';
	include('config.php');
	$query = "SELECT id,`name`,`default` FROM town ORDER BY `name`";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$town_id[] = $row[0];
		$town_name[] = $row[1];
		if ($row[2] == 1)
			$town_default = $row[0];
	}
}

function itemCodetoDesc($item_id)
{
	include('config.php');
	$query = "SELECT description FROM inventory_items WHERE id='$item_id'";
	$row = mysqli_fetch_row(mysqli_query($conn2, $query));
	$item_desc = $row[0];
	return $item_desc;
}

// update by nirmal 02_02_2022
// update by nirmal 09_10_2024 (fixed incorrect data fetching based on stores, added group by st id)
function getSoldQty($sub_system, $component)
{
	global $date, $store, $category, $itm_category, $itm_code, $itm_description, $sold_qty, $itm_store;
	$category_qry = $store_qry = $user_query = '';
	if (isset($_GET['category'])) {
		$category = $_GET['category'];
		if ($category != 'all')
			$category_qry = "AND itc.id='$category'";
	}
	if (isset($_GET['store'])) {
		$store = $_GET['store'];
		if ($store != 'all')
			$store_qry = "AND st.id='$store'";
	}
	if (isset($_GET['date']))
		$date = $_GET['date'];
	else
		$date = dateNow();

	if ($component == 'billing' || $component == 'bill2') {
		$user = $_COOKIE['user_id'];
		$store = $_COOKIE['store'];
		$user_query = "AND bm.`billed_by`='$user'";
		$store_qry = "AND st.id='$store'";
	}

	if ($sub_system == 'all')
		$sub_system_qry = '';
	else
		$sub_system_qry = "AND bm.`sub_system`='$sub_system'";
	$itm_code = array();

	include('config.php');

	$query = "SELECT itc.name,itm.code,itm.description,SUM(bi.qty),st.name FROM bill_main bm, bill bi, inventory_items itm, stores st, item_category itc WHERE bm.invoice_no=bi.invoice_no AND bi.item=itm.id AND st.id=bm.`store` AND itc.id=itm.category AND bm.`status` NOT IN (0,7) AND bm.`lock`!=0 AND date(bm.billed_timestamp)='$date' $category_qry $store_qry $sub_system_qry $user_query GROUP BY bi.item, st.id ORDER BY st.name, itm.description";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$itm_category[] = $row[0];
		$itm_code[] = $row[1];
		$itm_description[] = $row[2];
		$sold_qty[] = $row[3];
		$itm_store[] = $row[4];
	}
}

function sms4($systemid, $cust_id)
{
	$date_now = dateNow();
	$sub_system = $_COOKIE['sub_system'];
	$msg = '';
	$inf_from_email = inf_from_email();
	$sms_data = json_decode(sms_credential($sub_system));
	$sms_user = $sms_data->{"user"};
	$sms_pass = $sms_data->{"pass"};
	$sms_balance = $sms_data->{"balance"};
	include('config.php');
	$result = mysqli_query($conn2, "SELECT cu.name,cu.mobile,cu.sms,date(cu.approved_timestamp) as `approved_date`, st.shop_name_sms FROM cust cu, stores st WHERE cu.associated_store=st.id AND cu.id='$cust_id'");
	$row = mysqli_fetch_assoc($result);
	$cu_name = $row['name'];
	$mobile = $row['mobile'];
	$sms_cust = $row['sms'];
	$approved_date = $row['approved_date'];
	$inf_company = $row['shop_name_sms'];

	if (($sms_cust == 1) && ($sms_balance > 0) && ($_SERVER['SERVER_NAME'] == inf_url_primary()) && ($approved_date == $date_now) && (strpos($mobile, "7") == 1)) {
		if ($systemid == 15) {
			$message = str_replace(" ", "+", $inf_company) . '-NLC-ID+:+' . $cust_id . '-NLC-NAME+:+' . str_replace(" ", "+", $cu_name) . '-NLC-Thank+You+for+Registering+with+us!';
		} else {
			$message = str_replace(" ", "+", $inf_company) . '-NLC-SHOP+NAME+:+' . str_replace(" ", "+", $cu_name) . '-NLC-Thank+You+for+Registering+with+us!';
		}
		//---------------------------------------------------------------//
		$text = urlencode($message);

		$baseurl = "http://www.textit.biz/sendmsg";
		$url = "$baseurl/?id=$sms_user&pw=$sms_pass&eco=Y&to=$mobile&text=$text";
		$ret = file($url);
		$res = explode(":", $ret[0]);

		if (trim($res[0]) == "OK")
			$mailstatus = true;
		else
			$mailstatus = false;
		//----------------------------------------------------------------//
		if ($mailstatus) {
			$sms_balance--;
			if (set_sms_balance($sub_system, $sms_balance))
				$msg = 'SMS Sent<hr />';
			else
				$msg = 'Database Cound Not be Updated<hr />';
		} else
			$msg = 'SMS Could not be Sent<hr />';
	}
}

// updated by nirmal 10_10_2023
function getOneCust($mode, $sub_system)
{
	global $map_api, $cu_id1, $cu_name1, $cu_dob1, $cu_nic1, $cu_mobile1, $cu_crlimit1, $cu_custname1, $cu_nickname1, $cu_shop_add1, $cu_shop_tel1, $cu_home_add1, $cu_home_tel1, $cu_email_add, $cu_email_alert, $cu_sms, $cu_status1, $cu_store, $cu_sa, $cu_group, $cu_town, $cu_image1, $cu_image2, $cu_image3, $cu_image4, $cu_cre_by, $cu_cre_time, $cu_app_by, $cu_app_time, $cu_sub_system, $refer_cust, $cu_gps_x, $cu_gps_y, $cu_master_cust, $forign_image, $cu_tax_no, $designation, $delivery_details_same, $goods_delivery_mobile, $goods_delivery_address, $goods_delivery_contact_person;
	$note = '';
	if (isset($_REQUEST['id'])) {
		$id = $_REQUEST['id'];
		if ($sub_system == 'all')
			$sub_sys_qry = '';
		else
			$sub_sys_qry = "AND `sub_system`='$sub_system'";
		$cu_image1 = $cu_image2 = $cu_image3 = $cu_image4 = $cu_master_cust = 0;
		$refer_cust = array();
		if ($mode == 'id')
			$mode1 = "id='$id'";
		if ($mode == 'name')
			$mode1 = "name='$id'";
		include('config.php');
		$result = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='api_map'");
		$row = mysqli_fetch_assoc($result);
		$map_api = $row['value'];

		$query = "SELECT id,name,nic,mobile,credit_limit,cust_name,nickname,shop_address,shop_tel,home_address,home_tel,email,email_enable,sms,`status`,associated_store,associated_salesman,associated_group,associated_town,image1,image2,image3,image4,created_by,created_timestamp,approved_by,approved_timestamp,sub_system,master_cust,gps_x,gps_y,dob,tax_no,note FROM cust WHERE $mode1 $sub_sys_qry ORDER BY sub_system DESC";
		$result = mysqli_query($conn2, $query);
		while ($row = mysqli_fetch_array($result)) {
			$cu_id1 = $row[0];
			$cu_name1 = $row[1];
			$cu_nic1 = $row[2];
			$cu_mobile1 = $row[3];
			$cu_crlimit1 = $row[4];
			$cu_custname1 = $row[5];
			$cu_nickname1 = $row[6];
			$cu_shop_add1 = $row[7];
			$cu_shop_tel1 = $row[8];
			$cu_home_add1 = $row[9];
			$cu_home_tel1 = $row[10];
			$cu_email_add = $row[11];
			$cu_email_alert = $row[12];
			$cu_sms = $row[13];
			$cu_status1 = $row[14];
			$cu_store = $row[15];
			$cu_sa = $row[16];
			$cu_group = $row[17];
			$cu_town = $row[18];
			$cu_image1 = $row[19];
			$cu_image2 = $row[20];
			$cu_image3 = $row[21];
			$cu_image4 = $row[22];
			$cu_cre_by = $row[23];
			$cu_cre_time = $row[24];
			$cu_app_by = $row[25];
			$cu_app_time = $row[26];
			$cu_sub_system = $row[27];
			$cu_master_cust = $row[28];
			$cu_gps_x = $row[29];
			$cu_gps_y = $row[30];
			$cu_dob1 = $row[31];
			$cu_tax_no = $row[32];
			$note = $row[33];
			$forign_image = false;
			if ($cu_cre_by != '') {
				$query1 = "SELECT username FROM userprofile WHERE id='$cu_cre_by'";
				$row1 = mysqli_fetch_row(mysqli_query($conn2, $query1));
				$cu_cre_by = ucfirst($row1[0]);
			}
			if ($cu_app_by != '') {
				$query1 = "SELECT username FROM userprofile WHERE id='$cu_app_by'";
				$row1 = mysqli_fetch_row(mysqli_query($conn2, $query1));
				$cu_app_by = ucfirst($row1[0]);
			}
			if (($cu_sub_system != 0) && ($cu_master_cust != 0) && ($cu_image1 == 0) && ($cu_image2 == 0) && ($cu_image3 == 0) && ($cu_image4 == 0)) {
				$forign_image = true;
				$query1 = "SELECT image1,image2,image3,image4 FROM cust WHERE id='$cu_master_cust'";
				$row1 = mysqli_fetch_row(mysqli_query($conn2, $query1));
				$cu_image1 = $row1[0];
				$cu_image2 = $row1[1];
				$cu_image3 = $row1[2];
				$cu_image4 = $row1[3];
			}
		}

		//-----------------------------Check for Customer Cross Referanse--------------------------------------//
		if ($cu_master_cust != 0)
			$refer_cust[] = $cu_master_cust;
		$query2 = "SELECT id FROM cust WHERE master_cust='$cu_id1'";
		$result2 = mysqli_query($conn2, $query2);
		while ($row2 = mysqli_fetch_array($result2)) {
			$refer_cust[] = $row2[0];
		}

		// Parse the JSON string into a PHP associative array
		$dataArray = json_decode($note, true);

		// Access the values
		if ((isset($dataArray['designation'])) && ($dataArray['designation'] != ''))
			$designation = $dataArray['designation'];
		if ((isset($dataArray['delivery_details_same'])) && ($dataArray['delivery_details_same'] != ''))
			$delivery_details_same = $dataArray['delivery_details_same'];
		if ((isset($dataArray['goods_delivery_mobile'])) && ($dataArray['goods_delivery_mobile'] != ''))
			$goods_delivery_mobile = $dataArray['goods_delivery_mobile'];
		if ((isset($dataArray['goods_delivery_address'])) && ($dataArray['goods_delivery_address'] != ''))
			$goods_delivery_address = $dataArray['goods_delivery_address'];
		if ((isset($dataArray['goods_delivery_contact_person'])) && ($dataArray['goods_delivery_contact_person'] != ''))
			$goods_delivery_contact_person = $dataArray['goods_delivery_contact_person'];
	}
	//	$refer_cust=array(12,44,555,33,222,2121,3312,112,22,444,1123,1112,444,12,45,221,122,634,888,666);
}

function cust2Ajax($mode, $sub_system)
{
	$jasonArray = array();
	if (isset($_POST['val'])) {
		$cust2_name = $_POST['val'];
		$master_sys = '';

		if ($sub_system == 'all')
			$sub_sys_qry = '';
		else
			$sub_sys_qry = "AND `sub_system`='$sub_system'";
		include('config.php');
		$query = "SELECT id,name,nic,mobile,credit_limit,cust_name,nickname,shop_address,shop_tel,home_address,home_tel,email,email_enable,sms,`status`,associated_store,associated_salesman,associated_group,associated_town,image1,image2,image3,image4,created_by,created_timestamp,approved_by,approved_timestamp,sub_system,master_cust,gps_x,gps_y,dob,tax_no FROM cust WHERE `name`='$cust2_name' $sub_sys_qry ORDER BY sub_system DESC";
		$result = mysqli_query($conn2, $query);
		while ($row = mysqli_fetch_array($result)) {
			$jasonArray["cu_id1"] = $row[0];
			$jasonArray["cu_name1"] = $row[1];
			$jasonArray["cu_nic1"] = $row[2];
			$jasonArray["cu_mobile1"] = $row[3];
			$jasonArray["cu_crlimit1"] = $row[4];
			$jasonArray["cu_custname1"] = $row[5];
			$jasonArray["cu_nickname1"] = $row[6];
			$jasonArray["cu_shop_add1"] = $row[7];
			$jasonArray["cu_shop_tel1"] = $row[8];
			$jasonArray["cu_home_add1"] = $row[9];
			$jasonArray["cu_home_tel1"] = $row[10];
			$jasonArray["cu_email_add"] = $row[11];
			$jasonArray["cu_email_alert"] = $row[12];
			$jasonArray["cu_sms"] = $row[13];
			$jasonArray["cu_status1"] = $row[14];
			$jasonArray["cu_store"] = $row[15];
			$jasonArray["cu_sa"] = $row[16];
			$jasonArray["cu_group"] = $row[17];
			$jasonArray["cu_town"] = $row[18];
			$jasonArray["cu_cre_by"] = $row[23];
			$jasonArray["cu_cre_time"] = $row[24];
			$jasonArray["cu_app_by"] = $row[25];
			$jasonArray["cu_app_time"] = $row[26];
			$jasonArray["cu_sub_system"] = $row[27];
			$jasonArray["cu_master_cust"] = $row[28];
			$jasonArray["cu_gps_x"] = $row[29];
			$jasonArray["cu_gps_y"] = $row[30];
			$master_cu_sys = $row[27];
			$jasonArray["cu_dob1"] = $row[31];
			$jasonArray["cu_tax_no"] = $row[32];
		}

		if ($_COOKIE['sub_system'] != $master_cu_sys) {
			$query = "SELECT `name` FROM sub_system WHERE id='$master_cu_sys'";
			$row = mysqli_fetch_row(mysqli_query($conn, $query));
			$master_sys = $row[0];
		}
		$jasonArray["master_sys"] = $master_sys;
	}
	$myJSON = json_encode($jasonArray);

	return $myJSON;
}

function imageresize($systemid, $image1)
{
	$out = false;
	$dest_image1 = 'images/customerdata/' . $systemid . '/' . $image1; // make sure the directory is writeable
	$image1 = 'images/customerdata/' . $systemid . '/uploads/' . $image1;
	$image_info = getimagesize($image1);
	$oriwidth = $image_info[0];
	$oriheight = $image_info[1];
	$width = 1000;
	$ratio = $width / $oriwidth;
	$height = $oriheight * $ratio;
	$new_image = imagecreatetruecolor($width, $height);

	$image_type = $image_info[2];
	if ($image_type == IMAGETYPE_JPEG) {
		ini_set('memory_limit', '-1');
		$org_img = imagecreatefromjpeg($image1);
	} elseif (
		$image_type == IMAGETYPE_GIF
	) {
		$org_img = imagecreatefromgif($image1);
	} elseif ($image_type == IMAGETYPE_PNG) {
		$org_img = imagecreatefrompng($image1);
	}

	if (imagecopyresampled($new_image, $org_img, 0, 0, 0, 0, $width, $height, $oriwidth, $oriheight)) {
		//-------------Auto Rotate-------------//
		if (function_exists('exif_read_data')) {
			$exif = exif_read_data($image1);
			if ($exif && isset($exif['Orientation'])) {
				$orientation = $exif['Orientation'];
				if ($orientation != 1) {
					$deg = 0;
					switch ($orientation) {
						case 3:
							$deg = 180;
							break;
						case 6:
							$deg = 270;
							break;
						case 8:
							$deg = 90;
							break;
					}
					if ($deg) {
						$new_image = imagerotate($new_image, $deg, 0);
					}
				} // if there is some rotation necessary
			} // if have the exif orientation info
		} // if function exists
		//--------------------------//

		if (imagejpeg($new_image, $dest_image1, 90))
			if (imagedestroy($new_image))
				$out = true;
	}
	if ($out)
		return true;
	else
		return false;
}

// added by nirmal 28_04_2022
// update by nirmal 29_11_2023 (add quickbooks customer creating)
// update by nirmal 07_10_2024 (added mobile number error more specific to system id 17)

function addCust($systemid)
{
	include('config.php');
	global $message, $cust_id;
	$message = "";
	$out = true;
	$systemid = inf_systemid(1);

	$jasonArray = array();
	$timenow = timeNow();
	$shop_name = mysqli_real_escape_string($conn, ucwords(strtolower($_POST['shop_name'])));
	$shop_name = preg_replace("/[^A-Za-z0-9-+,. ]/", '', $shop_name);
	$shop_name = ucwords($shop_name);
	$nic = $_POST['nic'];
	$mobile = $_POST['mobile'];
	$cr_limit = $_POST['cr_limit'];
	$cu_store = $_POST['store'];
	$sm_id = $_POST['salesref'];
	$cu_group = $_POST['cu_group'];
	$cu_town = $_POST['cu_town'];
	$custname0 = mysqli_real_escape_string($conn, ucwords(strtolower($_POST['customer'])));
	$dob = mysqli_real_escape_string($conn, $_POST['dob']);  // added by nirmal 29_04_2022
	$custname = mysqli_real_escape_string($conn, preg_replace("/[^A-Za-z0-9-+,. ]/", '', $custname0));
	$custname = ucwords($custname);
	$nickname0 = mysqli_real_escape_string($conn, ucwords(strtolower($_POST['nickname'])));
	$nickname = mysqli_real_escape_string($conn, preg_replace("/[^A-Za-z0-9-+,. ]/", '', $nickname0));
	$shop_address = mysqli_real_escape_string($conn, $_POST['shop_address']);
	$shop_address = ucwords($shop_address);
	$shop_tel = $_POST['shop_tel'];
	$home_address = mysqli_real_escape_string($conn, $_POST['home_address']);
	$home_address = ucwords($home_address);
	$home_tel = $_POST['home_tel'];
	$cu_tax_no = $_POST['tax_no'];
	$email_add = $_POST['email_add'];
	$qb_msg = $qb_result = '';

	/* system id 13
		   Customer Name = shop_name
		   Designation = JSON
		   Contact Person = cust_name
		   Contact Number  = mobile
		   Official General Number = shop_tel
		   Email = email
		   Tax No = tax_no
		   Company Address = shop_address
		   Delivery details are same? = JSON
		   Goods Delivery Contact Person = JSON
		   Goods Delivery Contact Number = JSON
		   Goods Delivery Address = JSON
		   Personal Contact Number = home_tel
		   Personal Address = home_address
		   Customer DOB = dob
	   */

	// Initialize an empty associative array
	$data = array();
	if ((isset($_POST['designation'])) && ($_POST['designation'] != '')) {
		$data['designation'] = $_POST['designation'];
	}

	if ((isset($_POST['delivery_details_same'])) && ($_POST['delivery_details_same'] != '')) {
		$data['delivery_details_same'] = $_POST['delivery_details_same'];
	}
	if ((isset($_POST['delivery_details_same'])) && (($_POST['delivery_details_same'] == 0) || ($_POST['delivery_details_same'] == '0'))) {
		if ((isset($_POST['goods_delivery_contact_person'])) && ($_POST['goods_delivery_contact_person'] != '')) {
			$data['goods_delivery_contact_person'] = trim($_POST['goods_delivery_contact_person']);
		}

		if (isset($_POST['goods_delivery_mobile']) && ($_POST['goods_delivery_mobile'] != '')) {
			$data['goods_delivery_mobile'] = trim($_POST['goods_delivery_mobile']);
		}

		if (isset($_POST['goods_delivery_address']) && ($_POST['goods_delivery_address'] != '')) {
			$data['goods_delivery_address'] = trim($_POST['goods_delivery_address']);
		}
	}
	// Encode the associative array as JSON
	$jsonData = json_encode($data);


	if (isset($_POST['email_alert']))
		$email_alert = 1;
	else
		$email_alert = 0;

	if (isset($_POST['sms']))
		$sms0 = 1;
	else
		$sms0 = 0;

	if (isset($_POST['gps_x']))
		$gps_x = $_POST['gps_x'];
	else
		$gps_x = 0;

	if (isset($_POST['gps_y']))
		$gps_y = $_POST['gps_y'];
	else
		$gps_y = 0;

	if ($gps_x == '')
		$gps_x = 0;
	if ($gps_y == '')
		$gps_y = 0;

	$status = $_POST['status'];
	$created_by = $_COOKIE['user_id'];
	$master_cust = $_POST['master_cust'];

	if ($master_cust == '')
		$master_cust = 0;

	$sub_system = $_POST['sub_systemc'];
	$created_timestamp = $timenow;

	if ($_POST['status'] == 1) {
		$approved_by = $_COOKIE['user_id'];
		$approved_timestamp = $timenow;
		$approve_qry1 = ',`approved_by`,`approved_timestamp`';
		$approve_qry2 = ",'$approved_by','$approved_timestamp'";
	} else
		$approve_qry1 = $approve_qry2 = '';

	if (validateMobileNo($mobile)) {
		$sms = $sms0;
	} else {
		$sms = 0;
	}

	if ($status == 3) {
		$msg = 'Customer was created and the approval is pending';
	} else {
		$msg = 'Customer was added successfully!';
	}

	try {
		// Start the transaction
		mysqli_begin_transaction($conn);

		// 01. name validation
		$query = "SELECT COUNT(id) FROM `cust` WHERE `name`='$shop_name' AND `sub_system`='$sub_system'";
		$row = mysqli_fetch_row(mysqli_query($conn, $query));
		$id_count = $row[0];

		if ($id_count > 0) {
			$out = false;
			if ($systemid == 13)
				$message = 'Error: customer name already exist in the system';
			else
				$message = 'Error: name (shop name) already exist in the system';
			$jasonArray["shop_name"] = $message;
			throw new Exception($message);
		}

		// 02. nic validation
		// added by nirmal 21_06_25
		$query = "SELECT COUNT(id) FROM `cust` WHERE `nic`='$nic' AND `sub_system`='$sub_system'";
		$row = mysqli_fetch_row(mysqli_query($conn, $query));
		$id_count = $row[0];

		if (($id_count > 0) && ($systemid == 15)) {
			$out = false;
			$message = 'Error: NIC number already exist in the system';
			$jasonArray["nic"] = $message;
			throw new Exception($message);
		}

		// 03. mobile number validation
		$query = "SELECT COUNT(id) FROM `cust` WHERE `mobile`='$mobile' AND `sub_system`='$sub_system'";
		$row = mysqli_fetch_row(mysqli_query($conn, $query));
		$id_count = $row[0];

		if ($id_count > 0) {
			$out = false;
			$message = 'Error: mobile number already exist in the system';
			$jasonArray["mobile"] = $message;
			throw new Exception($message);
		}

		if (!validateMobileNo($mobile)) {
			$out = false;
			if ($systemid == 13) {
				$message = 'Error: Invalid contact number';
			} else if ($systemid == 17) {
				$message = 'Error: Invalid mobile number. (number must be start with 05 and length must be 10 digits)';
			} else {
				$message = 'Error: Invalid mobile number';
			}
			$jasonArray["mobile"] = $message;
			throw new Exception($message);
		}

		// 04. dob validation
		if ($out) { // added by nirmal 29_04_2022
			if ($dob != '') {
				$dt = DateTime::createFromFormat('Y-m-d', $dob);
				if (!($dt && $dt->format('Y-m-d') === $dob)) {
					$out = false;
					$message = 'Error: date of birth is not in yyyy-mm-dd format';
					throw new Exception($message);
				}
			}
		}

		// insert cust data
		if ($out) {
			$shop_name = trim($shop_name);
			$nickname = trim($nickname);
			$shop_address = trim($shop_address);
			$home_address = trim($home_address);

			if ($dob != '') {  // added by nirmal 29_04_2022
				$query = "INSERT INTO `cust` (`associated_store`,`associated_salesman`,`associated_group`,`associated_town`,`name`,`nic`,`mobile`,`credit_limit`,`cust_name`,`nickname`,`shop_address`,`shop_tel`,`home_address`,`home_tel`,`tax_no`,`email`,`email_enable`,`sms`,`gps_x`,`gps_y`,`created_by`,`created_timestamp`,`sub_system`,`master_cust`,`dob`,`status`,`note` $approve_qry1) VALUES ('$cu_store','$sm_id','$cu_group','$cu_town','$shop_name','$nic','$mobile','$cr_limit','$custname','$nickname','$shop_address','$shop_tel','$home_address','$home_tel','$cu_tax_no','$email_add','$email_alert','$sms','$gps_x','$gps_y','$created_by','$created_timestamp','$sub_system','$master_cust','$dob','$status','$jsonData' $approve_qry2)";
			} else {
				$query = "INSERT INTO `cust` (`associated_store`,`associated_salesman`,`associated_group`,`associated_town`,`name`,`nic`,`mobile`,`credit_limit`,`cust_name`,`nickname`,`shop_address`,`shop_tel`,`home_address`,`home_tel`,`tax_no`,`email`,`email_enable`,`sms`,`gps_x`,`gps_y`,`created_by`,`created_timestamp`,`sub_system`,`master_cust`,`status`,`note` $approve_qry1) VALUES ('$cu_store','$sm_id','$cu_group','$cu_town','$shop_name','$nic','$mobile','$cr_limit','$custname','$nickname','$shop_address','$shop_tel','$home_address','$home_tel','$cu_tax_no','$email_add','$email_alert','$sms','$gps_x','$gps_y','$created_by','$created_timestamp','$sub_system','$master_cust','$status','$jsonData' $approve_qry2)";
			}
			$result = mysqli_query($conn, $query);
			$cust_id = mysqli_insert_id($conn);

			if ($result) {
				if ($approve_qry1 != '') {
					$query7 = "INSERT INTO `cust_crlimit_audit` (`cust`,`old_limit`,`new_limit`,`changed_by`,`timestamp`) VALUES ('$cust_id','0','$cr_limit','$approved_by','$approved_timestamp')";
					$result7 = mysqli_query($conn, $query7);
					if (!$result7) {
						throw new Exception('Error : failed to create credit limit audit record');
					}
				}
				if ($approve_qry2 != '') {
					if ($systemid == 1)
						sms4($systemid, $cust_id);
					if ($systemid == 2)
						sms4($systemid, $cust_id);
					if ($systemid == 14)
						sms4($systemid, $cust_id);
				}
				// QuickBooks customer creating
				if (isQuickBooksActive(1)) {
					try {
						$result1 = mysqli_query($conn2, "SELECT `name` FROM `town` WHERE `id`='$cu_town'");
						$row1 = mysqli_fetch_assoc($result1);
						$town = $row1['name'];

						$country = inf_country(1);
						$shop_name = str_replace(array(':', "\t", "\n"), '', $shop_name);
						$custname = str_replace(array(':', "\t", "\n"), '', $custname);
						if (!validateEmail($email_add)) {
							$email_add = "";
						}
						$custArray = array(
							'DisplayName' => $shop_name,
							'CompanyName' => $shop_name,
							'FullyQualifiedName' => $shop_name,
							'GivenName' => $custname,
							'City' => $town,
							'Line1' => $shop_address,
							'PrimaryEmailAddr' => $email_add,
							'PrimaryPhone' => $mobile,
							'Country' => $country
						);

						$qb_result = QBCustomerAdd1($custArray); // Add new customer
						$qb_msg = $qb_result['message'];

						if ($qb_result['status'] == 'success') {
							$qb_cust_id = $qb_result['qb_cust_id'];
							$query = "UPDATE `cust` SET `qb_cust_id`='$qb_cust_id' WHERE `id`='$cust_id'";
							$result1 = mysqli_query($conn, $query);
							if (!$result1) {
								$qb_msg = 'Quickbooks error: Quickbooks cust id update error.';
								$jasonArray["cust_error"] = $qb_msg;
								throw new Exception($qb_msg);
							}
						} else {
							throw new Exception($qb_msg);
						}
					} catch (Exception $e) {
						$qb_msg = "<br>QuickBooks error: " . $e->getMessage();
						$qb_result['status'] = 'error';
						$jasonArray["cust_error"] = $qb_msg;
						throw new Exception($qb_msg);
					}
				}
				$out = true;
				$message = 'Customer created successfully. ' . $qb_msg;
				$jasonArray["user_create"] = $message;
				$jasonArray["cust_id"] = $cust_id;
				$jasonArray["email_add2"] = $email_add;

				// Commit moved AFTER all operations including QB
				mysqli_commit($conn);
			}
			if (!$result) {
				$out = false;
				$msg = 'Error: customer could not be created';
				$jasonArray["cust_error"] = $msg;
				throw new Exception($msg);
			}
		}
	} catch (Exception $e) {
		mysqli_rollback($conn);
		$message = $e->getMessage();
		error_log("Error in addCust(): " . $message); // Log the error
		if (empty(array_intersect(["shop_name", "mobile", "nic", "dob"], array_keys($jasonArray)))) {
			$jasonArray["cust_error"] = $e->getMessage();
		}
	}

	$myJSON = json_encode($jasonArray);
	return $myJSON;
}

// added by nirmal 2021_06_11
function nicCheckAjax($sub_system)
{
	include('config.php');
	global $message;
	$message = "";
	$jsonArray = array();
	$nic = $_POST['keyword'];

	$query = "SELECT COUNT(id) FROM `cust` WHERE `nic`='$nic' AND `sub_system`='$sub_system'";
	$row = mysqli_fetch_row(mysqli_query($conn, $query));
	$id_count = $row[0];
	$message = 'Error: This NIC is already exist in the system!';
	if ($id_count > 0) {
		$jsonArray["nic_error"] = $message;
	}
	$myJSON = json_encode($jsonArray);
	return $myJSON;
}

// added by nirmal 2021_06_11
function mobileCheckAjax($sub_system)
{
	include('config.php');
	global $message;
	$message = "";
	$jsonArray = array();
	$mobile = $_POST['keyword'];

	$query = "SELECT COUNT(id) FROM `cust` WHERE `mobile`='$mobile' AND `sub_system`='$sub_system'";
	$row = mysqli_fetch_row(mysqli_query($conn, $query));
	$id_count = $row[0];
	$message = 'Error: Mobile number already exist in the system!';
	if ($id_count > 0) {
		$jsonArray["mobile_error"] = $message;
	}
	$myJSON = json_encode($jsonArray);
	return $myJSON;
}

// added by nirmal 28_04_2022
function custCheckAjax($sub_system)
{
	include('config.php');
	global $message;
	$message = "";
	$jsonArray = array();
	$cust = $_POST['keyword'];
	$query = "SELECT COUNT(id) FROM `cust` WHERE `name`='%$cust' AND `sub_system`='$sub_system'";
	$row = mysqli_fetch_row(mysqli_query($conn, $query));
	$id_count = $row[0];

	if ($id_count > 0) {
		$message = 'Error: Customer name already exist in the system!';
		$jsonArray["cust_error"] = $message;
	} else {
		$jsonArray["ok"] = "ok";
	}
	$myJSON = json_encode($jsonArray);
	return $myJSON;
}

// added by nirmal 29_11_2023 (added quickbooks customer created message)
function addCustImage($systemid)
{
	global $message;
	include('config.php');
	$msg = $qb_msg = "";
	$imgqry1 = $imgqry2 = $imgqry3 = $imgqry4 = '';

	$cust_id = $_POST['cust_id'];
	$id_next = $cust_id;
	$image1 = $image2 = $image3 = $image4 = $file_upload = $upload1 = $upload2 = $upload3 = $upload4 = 0;
	$imagesNotChoosed = 0;
	$target_dir = "images/customerdata/$systemid/uploads/";

	if (isQuickBooksActive(1)) {
		$result = mysqli_query($conn2, "SELECT `qb_cust_id` FROM `cust` WHERE `id`='$cust_id'");
		$row = mysqli_fetch_assoc($result);
		if (!empty($row)) {
			if ($row['qb_cust_id'] != '') {
				$qb_msg = 'Quickbooks updated.';
			} else {
				$qb_msg = 'Quickbooks error.';
			}
		}
	}
	$message = 'Customer created successfully! ' . $qb_msg;

	for ($i = 1; $i <= 4; $i++) {
		if (isset($_FILES["fileToUpload" . $i])) {
			if ($_FILES["fileToUpload" . $i]["name"] != '') {

				if (!is_dir($target_dir)) {
					mkdir($target_dir, 0777, true);
				}
				$imagesNotChoosed++;

				if ($i == 1)
					$upload1 = 1;
				if ($i == 2)
					$upload2 = 1;
				if ($i == 3)
					$upload3 = 1;
				if ($i == 4)
					$upload4 = 1;
				$target_file = $target_dir . basename($_FILES["fileToUpload" . $i]["name"]);
				$tmp_name = str_pad($id_next . '_' . $i, 10, "0", STR_PAD_LEFT) . '.jpg';
				$destination_file = $target_dir . $tmp_name;
				$uploadOk = 1;
				$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
				// Check if image file is a actual image or fake image
				if (isset($_POST["submit"])) {
					$check = getimagesize($_FILES["fileToUpload" . $i]["tmp_name"]);
					if ($check !== false) {
						//echo "File is an image - " . $check["mime"] . ".";
						$uploadOk = 1;
					} else {
						$msg = "Error: Sorry, File is not an image.";
						$uploadOk = 0;
					}
				}
				// Check if file already exists
				if (file_exists($destination_file)) {
					$msg = "Error: Sorry, file already exists.";
					$uploadOk = 0;
				}
				// Check file size
				// if ($_FILES["fileToUpload" . $i]["size"] > 10000000) {
				// 	$msg = "Error: Sorry, your file is too large.";
				// 	$uploadOk = 0;
				// }
				$maxFileSize = 10000000; // File size limit in bytes (10 MB)
				$maxFileSizeMB = $maxFileSize / (1024 * 1024); // Convert bytes to MB
				if ($_FILES["fileToUpload" . $i]["size"] > $maxFileSize) {
					$msg = "Error: Your file is too large. Maximum allowed size is " . number_format($maxFileSizeMB, 2) . " MB.";
					$uploadOk = 0;
				}
				// Allow certain file formats
				if ($imageFileType != "jpg" && $imageFileType != "jpeg") {
					$msg = "Error: Sorry, only JPG, JPEG files are allowed.";
					$uploadOk = 0;
				}
				// Check if $uploadOk is set to 0 by an error
				if ($uploadOk == 0) {
					$file_upload++;
					// if everything is ok, try to upload file
				} else {
					if (move_uploaded_file($_FILES["fileToUpload" . $i]["tmp_name"], $destination_file)) {
						if (imageresize($systemid, $tmp_name)) {
							if ($i == 1)
								$image1 = 1;
							if ($i == 2)
								$image2 = 1;
							if ($i == 3)
								$image3 = 1;
							if ($i == 4)
								$image4 = 1;
							unlink($destination_file);
						} else {
							unlink($destination_file);
						}
					} else {
						$file_upload++;
						$msg = "Error: Sorry, there was an error while uploading your file.";
						$message = 'Customer is created ' . $qb_msg . ' | ' . $msg;
						return false;
					}
				}
			}
		}
	}

	if ($file_upload == 0) {
		if ($imagesNotChoosed == 0 && $file_upload == 0) {
			// if not choose any image, show default customer created successfully
			return true;
		} else {
			if (($upload1 == 1) && ($image1 == 1))
				$imgqry1 = ",`image1`='1'";
			if (($upload2 == 1) && ($image2 == 1))
				$imgqry2 = ",`image2`='1'";
			if (($upload3 == 1) && ($image3 == 1))
				$imgqry3 = ",`image3`='1'";
			if (($upload4 == 1) && ($image4 == 1))
				$imgqry4 = ",`image4`='1'";

			$query = "UPDATE `cust` SET `status`= `status` $imgqry1 $imgqry2 $imgqry3 $imgqry4 WHERE `id`='$cust_id' ";
			$result = mysqli_query($conn, $query);
			if ($result) {
				return true;
			} else {
				$message = 'Customer is created ' . $qb_msg . ' | ' . 'Error: Sorry, there was an error while uploading your file';
				return false;
			}
		}
	} else {
		/*
			  OLD CODE
				  if (($upload1 == 1) && ($image1 == 1)) unlink('images/customerdata/' . $systemid . '/' . str_pad($id_next . '_1', 10, "0", STR_PAD_LEFT) . '.jpg');
				  if (($upload2 == 1) && ($image2 == 1)) unlink('images/customerdata/' . $systemid . '/' . str_pad($id_next . '_2', 10, "0", STR_PAD_LEFT) . '.jpg');
				  if (($upload3 == 1) && ($image3 == 1)) unlink('images/customerdata/' . $systemid . '/' . str_pad($id_next . '_3', 10, "0", STR_PAD_LEFT) . '.jpg');
				  if (($upload4 == 1) && ($image4 == 1)) unlink('images/customerdata/' . $systemid . '/' . str_pad($id_next . '_4', 10, "0", STR_PAD_LEFT) . '.jpg');
				  if (($upload1 == 1) && ($image1 == 0)) unlink('images/customerdata/' . $systemid . '/uploads/' . str_pad($id_next . '_1', 10, "0", STR_PAD_LEFT) . '.jpg');
				  if (($upload2 == 1) && ($image2 == 0)) unlink('images/customerdata/' . $systemid . '/uploads/' . str_pad($id_next . '_2', 10, "0", STR_PAD_LEFT) . '.jpg');
				  if (($upload3 == 1) && ($image3 == 0)) unlink('images/customerdata/' . $systemid . '/uploads/' . str_pad($id_next . '_3', 10, "0", STR_PAD_LEFT) . '.jpg');
				  if (($upload4 == 1) && ($image4 == 0)) unlink('images/customerdata/' . $systemid . '/uploads/' . str_pad($id_next . '_4', 10, "0", STR_PAD_LEFT) . '.jpg');
			  */
		// Define file paths for the uploaded and resized images
		$file1_original = 'images/customerdata/' . $systemid . '/' . str_pad($id_next . '_1', 10, "0", STR_PAD_LEFT) . '.jpg';
		$file1_upload = 'images/customerdata/' . $systemid . '/uploads/' . str_pad($id_next . '_1', 10, "0", STR_PAD_LEFT) . '.jpg';

		$file2_original = 'images/customerdata/' . $systemid . '/' . str_pad($id_next . '_2', 10, "0", STR_PAD_LEFT) . '.jpg';
		$file2_upload = 'images/customerdata/' . $systemid . '/uploads/' . str_pad($id_next . '_2', 10, "0", STR_PAD_LEFT) . '.jpg';

		$file3_original = 'images/customerdata/' . $systemid . '/' . str_pad($id_next . '_3', 10, "0", STR_PAD_LEFT) . '.jpg';
		$file3_upload = 'images/customerdata/' . $systemid . '/uploads/' . str_pad($id_next . '_3', 10, "0", STR_PAD_LEFT) . '.jpg';

		$file4_original = 'images/customerdata/' . $systemid . '/' . str_pad($id_next . '_4', 10, "0", STR_PAD_LEFT) . '.jpg';
		$file4_upload = 'images/customerdata/' . $systemid . '/uploads/' . str_pad($id_next . '_4', 10, "0", STR_PAD_LEFT) . '.jpg';

		// File 1 cleanup
		if ($upload1 == 1 && $image1 == 0) { // If upload succeeded but resizing failed
			if (file_exists($file1_upload)) {
				unlink($file1_upload);
			}
		}

		if ($upload1 == 1 && $image1 == 1) { // If resizing succeeded but another error occurred
			if (file_exists($file1_original)) {
				unlink($file1_original);
			}
		}

		// File 2 cleanup
		if ($upload2 == 1 && $image2 == 0) {
			if (file_exists($file2_upload)) {
				unlink($file2_upload);
			}
		}

		if ($upload2 == 1 && $image2 == 1) {
			if (file_exists($file2_original)) {
				unlink($file2_original);
			}
		}

		// File 3 cleanup
		if ($upload3 == 1 && $image3 == 0) {
			if (file_exists($file3_upload)) {
				unlink($file3_upload);
			}
		}

		if ($upload3 == 1 && $image3 == 1) {
			if (file_exists($file3_original)) {
				unlink($file3_original);
			}
		}

		// File 4 cleanup
		if ($upload4 == 1 && $image4 == 0) {
			if (file_exists($file4_upload)) {
				unlink($file4_upload);
			}
		}

		if ($upload4 == 1 && $image4 == 1) {
			if (file_exists($file4_original)) {
				unlink($file4_original);
			}
		}
		$message = 'Customer is created ' . $qb_msg . ' | ' . $msg;
		return false;
	}
}

// update by nirmal 29_11_2023 (quickbooks customer creating and updating)
// updated by nirmal (added mobile number error more specific to system id 17)
function updateCust()
{
	global $message;
	$cust_id = $_POST['cust_id'];
	$shop_name = preg_replace("/[^A-Za-z0-9-+,. ]/", '', ucwords(trim($_POST['shop_name'])));
	$nic = trim($_POST['nic']);
	$mobile = trim($_POST['mobile']);
	$cr_limit = trim($_POST['cr_limit']);
	$cu_store = $_POST['cu_store'];
	$sm_id = $_POST['salesref'];
	$cu_group = $_POST['cu_group'];
	$cu_town = $_POST['cu_town'];
	$dob = $_POST['dob'];
	$custname = trim(ucwords(preg_replace("/[^A-Za-z0-9-+,. ]/", '', strtolower($_POST['customer']))));
	$nikname = trim(preg_replace("/[^A-Za-z0-9-+,. ]/", '', ucwords(strtolower($_POST['nikname']))));
	$shop_address = trim($_POST['shop_address']);
	$shop_tel = trim($_POST['shop_tel']);
	$cu_tax_no = trim($_POST['tax_no']);
	$home_address = trim(ucwords($_POST['home_address']));
	$home_tel = trim($_POST['home_tel']);
	$cust_type = $_POST['cust_type'];
	$email_add = trim($_POST['email_add']);
	$sms_alert = false;
	$user_id = $_COOKIE['user_id'];
	$sub_system = $_POST['sub_systemc'];
	$datetime = timeNow();
	$qb_msg = $qb_result = '';
	$systemid = inf_systemid(1);
	$file_upload = true;
	$imgqry1 = $imgqry2 = $imgqry3 = $imgqry4 = '';
	$image1 = $image2 = $image3 = $image4 = $file_upload = $upload1 = $upload2 = $upload3 = $upload4 = 0;

	$out = true;
	include('config.php');

	try {
		// Start MySQL transaction
		mysqli_begin_transaction($conn);

		if (($out) && isQuickBooksActive(1)) {
			$qb_cust_id = getCustomerQBId($cust_id);
			if ($qb_cust_id == '') {
				$out = false;
				$msg = 'Error: This customer is not registered in Quickbooks';
				throw new Exception($msg);
			}
		}

		if (isset($_POST['email_alert'])) {
			$email_alert = 1;
		} else {
			$email_alert = 0;
		}
		if (isset($_POST['sms'])) {
			$sms = 1;
		} else {
			$sms = 0;
		}
		if (isset($_POST['approved_by'])) {
			$approved_by = $_POST['approved_by'];
			$approved_timestamp = timeNow();
			$approved_qry = ", `approved_by`='$approved_by', `approved_timestamp`='$approved_timestamp'";
			$sms_alert = true;
		} else
			$approved_qry = '';

		// Initialize an empty associative array
		$data = array();
		if ((isset($_POST['designation'])) && ($_POST['designation'] != '')) {
			$data['designation'] = $_POST['designation'];
		}

		if ((isset($_POST['delivery_details_same'])) && ($_POST['delivery_details_same'] != '')) {
			$data['delivery_details_same'] = $_POST['delivery_details_same'];
		}
		if ((isset($_POST['delivery_details_same'])) && (($_POST['delivery_details_same'] == 0) || ($_POST['delivery_details_same'] == '0'))) {
			if ((isset($_POST['goods_delivery_contact_person'])) && ($_POST['goods_delivery_contact_person'] != '')) {
				$data['goods_delivery_contact_person'] = trim($_POST['goods_delivery_contact_person']);
			}

			if (isset($_POST['goods_delivery_mobile']) && ($_POST['goods_delivery_mobile'] != '')) {
				$data['goods_delivery_mobile'] = trim($_POST['goods_delivery_mobile']);
			}

			if (isset($_POST['goods_delivery_address']) && ($_POST['goods_delivery_address'] != '')) {
				$data['goods_delivery_address'] = trim($_POST['goods_delivery_address']);
			}
		}
		// Encode the associative array as JSON
		$jsonData = json_encode($data);

		if ($out) {
			$result = mysqli_query($conn, "SELECT `credit_limit`  FROM `cust` WHERE `id`='$cust_id'");
			$row = mysqli_fetch_assoc($result);
			$old_cr_limit = $row['credit_limit'];
		}

		if ($out) {
			$query = "SELECT count(id) FROM `cust` WHERE `name`='$shop_name' AND id!='$cust_id' AND `sub_system`='$sub_system'";
			$row = mysqli_fetch_row(mysqli_query($conn, $query));
			$id_count = $row[0];

			if ($id_count > 0) {
				$message = 'Error: this customer is already exist!';
				throw new Exception($message);
			}
		}

		if ($out) {
			if (!validateMobileNo($mobile)) {
				$out = false;
				if ($systemid == 17) {
					$message = 'Error: Invalid mobile number! (number must be start with 05 and length must be 10 digits)';
					throw new Exception($message);
				} else {
					$message = 'Error: Invalid mobile number!';
					throw new Exception($message);
				}
			}
		}

		if ($out) {
			if ($dob != '') {
				$dt = DateTime::createFromFormat('Y-m-d', $dob);
				if (!($dt && $dt->format('Y-m-d') === $dob)) {
					$out = false;
					$message = 'Error: date of birth is not in yyyy-mm-dd format';
					throw new Exception($message);
				}
			}
		}

		if ($out) {
			//-------------------------File Upload-------------------------------//
			$target_dir = "images/customerdata/$systemid/uploads/";
			for ($i = 1; $i <= 4; $i++) {
				if ($_FILES["fileToUpload" . $i]["name"] != '') {
					if (!is_dir($target_dir)) {
						mkdir($target_dir, 0777, true);
					}
					if ($i == 1)
						$upload1 = 1;
					if ($i == 2)
						$upload2 = 1;
					if ($i == 3)
						$upload3 = 1;
					if ($i == 4)
						$upload4 = 1;
					$target_file = $target_dir . basename($_FILES["fileToUpload" . $i]["name"]);
					$tmp_name = str_pad($cust_id . '_' . $i, 10, "0", STR_PAD_LEFT) . '.jpg';
					$destination_file = $target_dir . $tmp_name;
					$uploadOk = 1;
					$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
					// Check if image file is a actual image or fake image
					if (isset($_POST["submit"])) {
						$check = getimagesize($_FILES["fileToUpload" . $i]["tmp_name"]);
						if ($check !== false) {
							//echo "File is an image - " . $check["mime"] . ".";
							$uploadOk = 1;
						} else {
							$msg = "Error: file is not an image.";
							$uploadOk = 0;
							throw new Exception($msg);
						}
					}
					// Check if file already exists
					if (file_exists($destination_file)) {
						$msg = "Error: file already exists.";
						$uploadOk = 0;
						throw new Exception($msg);
					}
					// Check file size
					// if ($_FILES["fileToUpload" . $i]["size"] > 10000000) {
					// 	$msg = "Error: your file is too large.";
					// 	$uploadOk = 0;
					// 	throw new Exception($msg);
					// }
					$maxFileSize = 10000000; // File size limit in bytes (10 MB)
					$maxFileSizeMB = $maxFileSize / (1024 * 1024); // Convert bytes to MB

					if ($_FILES["fileToUpload" . $i]["size"] > $maxFileSize) {
						$msg = "Error: Your file is too large. Maximum allowed size is " . number_format($maxFileSizeMB, 2) . " MB.";
						$uploadOk = 0;
						throw new Exception($msg);
					}
					// Allow certain file formats
					if ($imageFileType != "jpg" && $imageFileType != "jpeg") {
						$msg = "Error: only JPEG files are allowed.";
						$uploadOk = 0;
						throw new Exception($msg);
					}
					// Check if $uploadOk is set to 0 by an error
					if ($uploadOk == 0) {
						$file_upload++;
						// if everything is ok, try to upload file
					} else {
						if (move_uploaded_file($_FILES["fileToUpload" . $i]["tmp_name"], $destination_file)) {
							if ($i == 1)
								$image1 = 1;
							if ($i == 2)
								$image2 = 1;
							if ($i == 3)
								$image3 = 1;
							if ($i == 4)
								$image4 = 1;
							imageresize($systemid, $tmp_name);
							unlink($destination_file);
						} else {
							$file_upload++;
							$msg = "Sorry, there was an error while uploading your file.";
							throw new Exception($msg);
						}
					}
				}
			}

			//------------------------File Upload End---------------------------//
			if ($file_upload == 0) {
				if (($upload1 == 1) && ($image1 == 1))
					$imgqry1 = ",`image1`='1'";
				if (($upload2 == 1) && ($image2 == 1))
					$imgqry2 = ",`image2`='1'";
				if (($upload3 == 1) && ($image3 == 1))
					$imgqry3 = ",`image3`='1'";
				if (($upload4 == 1) && ($image4 == 1))
					$imgqry4 = ",`image4`='1'";

				if ($_POST['dob'] == '') {
					$query = "UPDATE `cust` SET `name`='$shop_name',`nic`='$nic',`mobile`='$mobile',`credit_limit`='$cr_limit',`associated_store`='$cu_store',`associated_salesman`='$sm_id',`associated_group`='$cu_group',`associated_town`='$cu_town',`cust_name`='$custname',`nickname`='$nikname',`shop_address`='$shop_address',`shop_tel`='$shop_tel',`home_address`='$home_address',`home_tel`='$home_tel',`tax_no`='$cu_tax_no',`email`='$email_add',`email_enable`='$email_alert',`sms`='$sms',`sub_system`='$sub_system',`status`='$cust_type',`note`='$jsonData' $approved_qry $imgqry1 $imgqry2 $imgqry3 $imgqry4 WHERE `id`='$cust_id' ";
				} else {
					$query = "UPDATE `cust` SET `name`='$shop_name',`nic`='$nic',`mobile`='$mobile',`credit_limit`='$cr_limit',`associated_store`='$cu_store',`associated_salesman`='$sm_id',`associated_group`='$cu_group',`associated_town`='$cu_town',`cust_name`='$custname',`nickname`='$nikname',`shop_address`='$shop_address',`shop_tel`='$shop_tel',`home_address`='$home_address',`home_tel`='$home_tel',`tax_no`='$cu_tax_no',`email`='$email_add',`email_enable`='$email_alert',`sms`='$sms',`sub_system`='$sub_system',`dob`='$dob',`status`='$cust_type',`note`='$jsonData'  $approved_qry $imgqry1 $imgqry2 $imgqry3 $imgqry4 WHERE `id`='$cust_id' ";
				}
				$result = mysqli_query($conn, $query);
				if ($result) {
					//	if($sms_alert) sms4($cust_id);
					if ($old_cr_limit != $cr_limit) {
						$query = "INSERT INTO `cust_crlimit_audit` (`cust`,`old_limit`,`new_limit`,`changed_by`,`timestamp`) VALUES ('$cust_id','$old_cr_limit','$cr_limit','$user_id','$datetime')";
						$result = mysqli_query($conn, $query);
					}
					// QuickBooks customer creating
					if (isQuickBooksActive(1)) {
						$qb_cust_id = getCustomerQBId($cust_id);
						if ($qb_cust_id) { // updated QB customer
							try {
								$qb_result = QBCustomerUpdate($cust_id);
								$qb_msg = $qb_result['message'];
								if ((isset($qb_result['status'])) && ($qb_result['status'] != 'success')) {
									throw new Exception($qb_msg);
								}
							} catch (Exception $e) {
								$qb_msg = "<br>QuickBooks error: " . $e->getMessage();
								throw new Exception($qb_msg);
							}
						}
					}

					// Commit the transaction
					mysqli_commit($conn);
					$message = 'Customer was updated successfully! ' . $qb_msg;
					return true;
				} else {
					$message = 'Customer could not be updated! ' . $qb_msg;
					throw new Exception($message);
				}
			} else {
				if (($upload1 == 1) && ($image1 == 1))
					unlink('images/customerdata/' . $systemid . '/' . str_pad($cust_id . '_1', 10, "0", STR_PAD_LEFT) . '.jpg');
				if (($upload2 == 1) && ($image2 == 1))
					unlink('images/customerdata/' . $systemid . '/' . str_pad($cust_id . '_2', 10, "0", STR_PAD_LEFT) . '.jpg');
				if (($upload3 == 1) && ($image3 == 1))
					unlink('images/customerdata/' . $systemid . '/' . str_pad($cust_id . '_3', 10, "0", STR_PAD_LEFT) . '.jpg');
				if (($upload4 == 1) && ($image4 == 1))
					unlink('images/customerdata/' . $systemid . '/' . str_pad($cust_id . '_4', 10, "0", STR_PAD_LEFT) . '.jpg');
				if (($upload1 == 1) && ($image1 == 0))
					unlink('images/customerdata/' . $systemid . '/uploads/' . str_pad($cust_id . '_1', 10, "0", STR_PAD_LEFT) . '.jpg');
				if (($upload2 == 1) && ($image2 == 0))
					unlink('images/customerdata/' . $systemid . '/uploads/' . str_pad($cust_id . '_2', 10, "0", STR_PAD_LEFT) . '.jpg');
				if (($upload3 == 1) && ($image3 == 0))
					unlink('images/customerdata/' . $systemid . '/uploads/' . str_pad($cust_id . '_3', 10, "0", STR_PAD_LEFT) . '.jpg');
				if (($upload4 == 1) && ($image4 == 0))
					unlink('images/customerdata/' . $systemid . '/uploads/' . str_pad($cust_id . '_4', 10, "0", STR_PAD_LEFT) . '.jpg');
				$message = $msg;
				throw new Exception($message);
			}
		} else {
			throw new Exception('An error occurred during the update process.');
		}
	} catch (Exception $e) {
		// Rollback the transaction in case of an error
		mysqli_rollback($conn);
		$message = $e->getMessage();
		error_log("Error in updateCust(): " . $e->getMessage()); // Log the error
		return false;
	}
}

function setStatusCust($status)
{
	global $message;
	$id = $_REQUEST['id'];
	if ($status == 0)
		$msg = 'Activated';
	if ($status == 1)
		$msg = 'Deactivated';
	include('config.php');


	$query = "UPDATE `cust` SET `status`='$status' WHERE `id`='$id'";
	$result = mysqli_query($conn, $query);

	if ($result) {
		$message = 'Customer was ' . $msg . ' Successfully!';
		return true;
	} else {
		$message = 'Customer could not be ' . $msg . '!';
		return false;
	}
}

function deleteCust()
{
	global $message;
	$id = $_REQUEST['id'];
	$systemid = inf_systemid(1);
	include('config.php');
	$query = "SELECT count(invoice_no) FROM bill_main WHERE `cust`='$id'";
	$row = mysqli_fetch_row(mysqli_query($conn, $query));
	$bill_count = $row[0];

	$query = "SELECT image1,image2,image3,image4 FROM cust WHERE `id`='$id'";
	$row = mysqli_fetch_row(mysqli_query($conn, $query));
	$image1 = $row[0];
	$image2 = $row[1];
	$image3 = $row[2];
	$image4 = $row[3];

	if ($bill_count == 0) {
		$query = "DELETE FROM `cust` WHERE `id`='$id'";
		$result = mysqli_query($conn, $query);

		if ($result) {
			if ($image1 == 1)
				unlink('images/customerdata/' . $systemid . '/' . str_pad($id . '_1', 10, "0", STR_PAD_LEFT) . '.jpg');
			if ($image2 == 1)
				unlink('images/customerdata/' . $systemid . '/' . str_pad($id . '_2', 10, "0", STR_PAD_LEFT) . '.jpg');
			if ($image3 == 1)
				unlink('images/customerdata/' . $systemid . '/' . str_pad($id . '_3', 10, "0", STR_PAD_LEFT) . '.jpg');
			if ($image4 == 1)
				unlink('images/customerdata/' . $systemid . '/' . str_pad($id . '_4', 10, "0", STR_PAD_LEFT) . '.jpg');
			$message = 'Customer was Deleted Successfully!';
			return true;
		} else {
			$message = 'Customer could not be Deleted !';
			return false;
		}
	} else {
		$message = 'Customer could not be Deleted!';
		return false;
	}
}

function addCustGroup()
{
	global $message;
	$gp_name = $_POST['name'];
	$sub_system = $_COOKIE['sub_system'];
	if ($gp_name != '') {
		include('config.php');
		$query = "INSERT INTO `cust_group` (`name`,`sub_system`) VALUES ('$gp_name','$sub_system')";
		$result = mysqli_query($conn, $query);
		if ($result) {
			$message = 'Group was Created Successfully!';
			return true;
		} else {
			$message = 'Error: Group Cannot Be Created!';
			return false;
		}
	} else {
		$message = 'Error: Group Name Cannot Be Empty!';
		return false;
	}
}

function updateCustGroup()
{
	global $message;
	$gp_id = $_POST['group_id'];
	$gp_name = $_POST['name'];
	if ($gp_name != '') {
		include('config.php');
		$query = "UPDATE `cust_group` SET `name`='$gp_name' WHERE id='$gp_id'";
		$result = mysqli_query($conn, $query);
		if ($result) {
			$message = 'Group was Updated Successfully!';
			return true;
		} else {
			$message = 'Error: Group Cannot Be Updated!';
			return false;
		}
	} else {
		$message = 'Error: Group Name Cannot Be Empty!';
		return false;
	}
}

function deleteCustGroup()
{
	global $message;
	$gp_id = $_GET['id'];
	include('config.php');
	$query = "DELETE FROM `cust_group` WHERE id='$gp_id'";
	$result = mysqli_query($conn, $query);
	if ($result) {
		$message = 'Group was Deleted Successfully!';
		return true;
	} else {
		$message = 'Error: Group Cannot Be Deleted! Please un-map Customers from this Group';
		return false;
	}
}

function addCustTown()
{
	global $message;
	$town_name = $_POST['name'];
	$out = true;
	$message = 'Town was Created Successfully!';

	include('config.php');

	if ($town_name == '') {
		$message = 'Error: Town Name Cannot Be Empty!';
		$out = false;
	}

	if ($out) {
		$query = "SELECT count(id) FROM town WHERE `name`='$town_name'";
		$row = mysqli_fetch_row(mysqli_query($conn, $query));
		if ($row[0] > 0) {
			$message = 'Error: Town is Already Exist!';
			$out = false;
		}
	}

	if ($out) {
		$query = "INSERT INTO `town` (`name`,`default`) VALUES ('$town_name','0')";
		$result = mysqli_query($conn, $query);
		if (!$result) {
			$message = 'Error: Town Cannot Be Created!';
			$out = false;
		}
	}

	return $out;
}

function updateCustTown()
{
	global $message;
	$town_id = $_POST['town_id'];
	$town_name = $_POST['name'];
	$out = true;
	$message = 'Group was Updated Successfully!';

	include('config.php');

	if ($town_name == '') {
		$message = 'Error: Town Name Cannot Be Empty!';
		$out = false;
	}

	if ($out) {
		$query = "SELECT count(id) FROM town WHERE `name`='$town_name' and id!='$town_id'";
		$row = mysqli_fetch_row(mysqli_query($conn, $query));
		if ($row[0] > 0) {
			$message = 'Error: Town is Already Exist!';
			$out = false;
		}
	}
	if ($out) {
		include('config.php');
		$query = "UPDATE `town` SET `name`='$town_name' WHERE id='$town_id'";
		$result = mysqli_query($conn, $query);
		if (!$result) {
			$message = 'Error: Town Name Cannot Be Updated!';
			$out = false;
		}
	}

	return $out;
}

function deleteCustTown()
{
	global $message;
	$town_id = $_GET['id'];
	include('config.php');
	$query = "DELETE FROM `town` WHERE id='$town_id'";
	$result = mysqli_query($conn, $query);
	if ($result) {
		$message = 'Town was Deleted Successfully!';
		return true;
	} else {
		$message = 'Error: Town Cannot Be Deleted! Please un-map Customers from this Town';
		return false;
	}
}

function setTownDefault()
{
	global $message;
	$town_id = $_GET['town_id'];
	$message = 'Error';
	include('config.php');
	$query = "UPDATE `town` SET `default`='0' WHERE id!='$town_id'";
	$result = mysqli_query($conn, $query);
	if ($result) {
		$query = "UPDATE `town` SET `default`='1' WHERE id='$town_id'";
		$result = mysqli_query($conn, $query);
		if ($result)
			$message = 'Done';
	}
	return $message;
}

// added by nirmal 25_05_2022
function dateMonthValidation($date)
{
	if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $date)) {
		return false;
	} else {
		return true;
	}
}

// added by nirmal 25_05_2022
function getCustDOB()
{
	global $date, $cust_id, $cust_name, $cust_mob, $cust_dob, $cust_home_address, $cust_shop_address, $from_date,
	$to_date, $cust_age;
	$cust_id = $cust_name = $cust_mob = $cust_dob = $cust_home_address = $cust_shop_address = $cust_age = array();
	$category_qry = $store_qry = $user_query = '';
	$out = true;
	$store = $_COOKIE['store'];
	include('config.php');
	$date = substr(dateNow(), 5);
	$date_filter_qry = "DATE_FORMAT(`dob`, '%m-%d') = '$date'";

	if (isset($_REQUEST['from_date']) && isset($_REQUEST['to_date'])) {
		if (dateMonthValidation($_REQUEST['from_date'])) {
			$from_date = substr($_REQUEST['from_date'], 5);
		} else {
			$out = false;
		}

		if (dateMonthValidation($_REQUEST['to_date'])) {
			$to_date = substr($_REQUEST['to_date'], 5);
		} else {
			$out = false;
		}

		if ($out) {
			$date_filter_qry = "DATE_FORMAT(`dob`, '%m-%d') BETWEEN '$from_date' AND '$to_date'";
		}
	}

	if ($out) {
		$query = "SELECT `id`,`name`,`mobile`,`home_address`,`shop_address`,`dob`,(YEAR(CURDATE())-YEAR(`dob`)) FROM cust WHERE $date_filter_qry AND `associated_store` = '$store' AND `status`='1' ORDER BY DATE_FORMAT(`dob`, '%m-%d') ASC";
		$result = mysqli_query($conn2, $query);
		while ($row = mysqli_fetch_array($result)) {
			$cust_id[] = $row[0];
			$cust_name[] = $row[1];
			$cust_mob[] = $row[2];
			$cust_home_address[] = $row[3];
			$cust_shop_address[] = $row[4];
			$cust_dob[] = $row[5];
			$cust_age[] = $row[6];
		}
	}
}

// added by nirmal 26_05_2022
function generateTag()
{
	global $cust_id, $from_name, $from_address, $from_mob, $to_name, $to_address, $to_mob;
	$from_name = $from_address = $from_mob = '';
	$cust_id = $to_name = $to_address = $to_mob = array();

	$id = $_GET['id'];
	$store = $_COOKIE['store'];
	if ($id != '') {
		include('config.php');
		$query = "SELECT shop_name,address,tel FROM stores WHERE id='$store'";
		$result = mysqli_query($conn2, $query);
		$row = mysqli_fetch_row($result);
		$from_name = $row[0];
		$from_address = $row[1];
		$from_mob = $row[2];
		$query = "SELECT `id`,`name`,`shop_address`,`mobile` FROM cust WHERE `id` IN($id)";
		$result = mysqli_query($conn2, $query);
		while ($row = mysqli_fetch_array($result)) {
			$cust_id[] = $row[0];
			$to_name[] = $row[1];
			$to_address[] = $row[2];
			$to_mob[] = $row[3];
		}
	}
}

//---------------------------------------SALES REPORT--------------------------------------------------------//

// added by nirmal 30_01_2025
function groupStores2()
{
	include('config.php');

	$result = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='systemid'");
	$row = mysqli_fetch_assoc($result);
	$systemid = $row['value'];

	if ((isset($_COOKIE['store'])) && ($_GET['components'] != 'fin') && ((($_GET['components'] != 'manager') || ($systemid != 14)))) {
		$salesman_store = $_COOKIE['store'];
		$result = mysqli_query($conn2, "SELECT `group` FROM store_group WHERE store='$salesman_store'");
		$row = mysqli_fetch_assoc($result);
		$group = $row['group'];

		$query = "SELECT store FROM store_group WHERE `group` ='$group'";
		$result = mysqli_query($conn2, $query);
		while ($row = mysqli_fetch_array($result)) {
			$stores[] = $row[0];
		}
		$store_list = implode(',', $stores);
	} else {
		$query = "SELECT id FROM stores WHERE `status` ='1'";
		$result = mysqli_query($conn2, $query);
		while ($row = mysqli_fetch_array($result)) {
			$stores[] = $row[0];
		}
		$store_list = implode(',', $stores);
	}

	return $store_list;
}

// update by nirmal 20_08_2024 (order by sub system and store id)
// update by nirmal 30_01_2025 (added sup, mk module store filters)
function getFilter($sub_system)
{
	global $store_id, $store_name, $up_id, $up_name;

	if ($sub_system == 'all') {
		$sub_system_qry = '';
	} else {
		$sub_system_qry = "AND sub_system='$sub_system'";
	}
	$order_by = " ORDER BY `sub_system`, `id`";
	include('config.php');

	$storefilter2 = $storefilter3 = '';
	if ($_REQUEST['components'] == 'supervisor' || $_REQUEST['components'] == 'marketing') {
		$store_list = groupStores2();
		$storefilter2 = "AND id IN ($store_list)";
		$storefilter3 = "AND `store` IN ($store_list)";
	}

	$query = "SELECT `id`, `name` FROM stores WHERE `status`='1' $sub_system_qry $storefilter2 $order_by";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$store_id[] = $row[0];
		$store_name[] = $row[1];
	}

	$query = "SELECT `id`, `username` FROM userprofile WHERE `status`=0 $sub_system_qry $storefilter3 ORDER BY username";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$up_id[] = $row[0];
		$up_name[] = $row[1];
	}
}

// updated by nirmal 21_12_13
// update by nirmal 10_02_2024 (added invoice type to show in view in different colors (bill row item_type))
// update by nirmal 14_02_2024 (to get payment cheque date and cheque no to view new two columns)
// update by nirmal 21_02_2024 bug fix in result (added missing payment amount selecting for each invoice as payment type)
// update by nirmal 13_08_2024 (remove round ups numbers in sql queries)
// update by nirmal 30_01_2025 (sup, mkt module related code added)

function dailySale($store, $sub_system)
{
	global $gps, $graph_user, $graph_total, $lock_req, $type_req, $date1, $date2, $invoice_no, $invoice_Total, $billed_district, $billed_by, $billed_cust, $billed_time, $billed_store, $payment_cash, $payment_chque, $payment_id, $payment_amount, $payment_type, $payment_salesman, $payment_cust, $payment_time, $payment_store, $chq_details, $payment_details, $bi_discount, $rtn_no, $rtn_time, $rtn_pay, $rtn_salesman, $rtn_store, $rtn_cust, $payment_bank, $payment_card, $payment_bank_tr, $payment_card_tr, $wa_no, $wa_time, $wa_pay, $wa_salesman, $wa_entity, $wa_store, $item_type, $payment_cheque_no, $payment_cheque_date;
	$lock_qry = $type_qry = $type_req = $gps = '';
	$payment_bank = $payment_bank_tr = $payment_cash = $payment_chque = $chq_details = $payment_card = $payment_card_tr = $cu_list_arr = $invoice_no = $payment_id = $rtn_no = $wa_no = $item_type = $payment_cheque_date = $payment_cheque_no = $graph_user = $graph_total = array();
	$systemid = inf_systemid(1);
	$user_id = $_COOKIE['user_id'];
	$order_by = "st.name, bm.billed_timestamp";
	include('config.php');

	// sub system
	if (isset($sub_system) && $sub_system == 'all') {
		$sub_sys_bmqry = $sub_sys_pyqry = $sub_sys_rtqry = $sub_sys_waqry = '';
	} else {
		$sub_sys_bmqry = "AND bm.`sub_system`='$sub_system'";
		$sub_sys_pyqry = "AND py.sub_system='$sub_system'";
		$sub_sys_rtqry = "AND rm.sub_system='$sub_system'";
		$sub_sys_waqry = "AND wa.sub_system='$sub_system'";
	}
	// store
	if (isset($store) && $store == 'all') {
		$storesearch = '';
	} else {
		$storesearch = "AND st.id='" . $store . "'";
	}
	// group
	if (isset($_REQUEST['group'])) {
		$group = $_REQUEST['group'];
	}
	if ($group == 'all') {
		$groupsearch = '';
	} else {
		$groupsearch = "AND gp.id='" . $group . "'";
	}
	// salesman
	if (isset($_REQUEST['salesman']) && $_REQUEST['salesman'] == 'all') {
		$salesmansearch = '';
	} else {
		$salesmansearch = "AND up.`id`='" . $_REQUEST['salesman'] . "'";
	}
	// process by
	if (isset($_REQUEST['processby']) && $_REQUEST['processby'] == 'all') {
		$processbysearch = '';
	} else {
		$processbysearch = "AND bm.`packed_by`='" . $_REQUEST['processby'] . "'";
	}
	// type
	if (isset($_REQUEST['type'])) {
		$type_req = $_REQUEST['type'];
		if ($type_req == 1) {
			$type_qry = "AND bm.`type` IN (1,4)";
		}
		if ($type_req == 2) {
			$type_qry = "AND bm.`type` IN (2,5)";
		}
		if ($type_req == 4) {
			$type_qry = "AND bm.`type`='3'";
		}
	}
	// lock
	if (isset($_REQUEST['lock'])) {
		$lock_req = $_REQUEST['lock'];
		if ($lock_req == 'all') {
			$lock_qry = '';
		} else {
			if ($lock_req == 0)
				$lock_qry = "AND bm.`lock` IN (0,2)";
			else
				$lock_qry = "AND bm.`lock`=$lock_req";
		}
	} else {
		$lock_req = 1;
		$lock_qry = "AND bm.`lock`=$lock_req";
	}
	// date 1
	if (isset($_REQUEST['date1'])) {
		$date1 = $_REQUEST['date1'];
	} else {
		$date1 = dateNow();
	}

	$date_qry1 = "AND date(bm.billed_timestamp)='$date1'";
	$date_qry2 = "AND date(py.payment_date)='$date1'";
	$date_qry3 = "AND date(rm.return_date)='$date1'";
	$date_qry4 = "AND date(wa.cust_pay_date)='$date1'";
	$date_qry5 = "AND date(wa.suplier_pay_date)='$date1'";

	// date 1 & date 2
	if ((isset($_REQUEST['date1'])) && (isset($_REQUEST['date2']))) {
		$date1 = $_REQUEST['date1'];
		$date2 = $_REQUEST['date2'];
		if ($date1 != '' && $date2 != '') {
			$date_qry1 = "AND date(bm.billed_timestamp) BETWEEN  '$date1' AND '$date2'";
			$date_qry2 = "AND date(py.payment_date) BETWEEN  '$date1' AND '$date2'";
			$date_qry3 = "AND date(rm.return_date) BETWEEN  '$date1' AND '$date2'";
			$date_qry4 = "AND date(wa.cust_pay_date) BETWEEN  '$date1' AND '$date2'";
			$date_qry5 = "AND date(wa.suplier_pay_date) BETWEEN  '$date1' AND '$date2'";
		}
	}
	// cashback
	if (isset($_REQUEST['cashback']) && $_REQUEST['cashback'] == 'yes') {
		$cashback_qry = "AND bi.qty<0";
		$pycashback_qry = "AND py.amount<0";
	} else {
		$cashback_qry = $pycashback_qry = "";
	}

	// sup & mk module filters
	if (isset($_REQUEST['components']) && ($_REQUEST['components'] != 'manager' && $_REQUEST['components'] != 'topmanager')) {
		// cashback
		$cashback_qry = '';
		// group search
		if (($group != '') && ($group != 'all')) {
			$groupsearch = "AND cu.associated_group='$group'";
		}
		if ($_REQUEST['components'] == 'marketing') {
			$gp_id = '';
			$query = "SELECT cg.id FROM cust_group cg, user_to_group ug WHERE cg.id=ug.`group` AND ug.`user`='$user_id'";
			$result = mysqli_query($conn2, $query);
			if ($result) {
				while ($row = mysqli_fetch_array($result)) {
					$gp_id .= $row[0] . ',';
				}
			}
			$gp_id = rtrim($gp_id, ',');
			$groupsearch = "AND cu.associated_group IN ($gp_id)";
		}

		// stores
		$store_list = groupStores2();
		if ($store == 'all') {
			$storesearch = "AND st.id IN (" . $store_list . ")";
		} else {
			$storesearch = "AND st.id='" . $store . "'";
		}

		// order format
		if ($systemid == 25) {
			$order_by = "st.name, CAST(bm.invoice_no AS UNSIGNED)";
		} else {
			$order_by = "st.name, bm.billed_timestamp";
		}
	}

	if ($type_req != 3 && $type_req != 5) {
		$query1 = "SELECT py.payment_type,py.amount,py.chque_no,bk.bank_code,py.chque_branch,py.chque_date,bk.name,py.invoice_no,py.bank_trans,py.card_no FROM payment py LEFT JOIN bank bk ON py.chque_bank=bk.id WHERE py.bill_pay=1 AND py.`status`='0' $date_qry2";
		$result1 = mysqli_query($conn2, $query1);
		while ($row1 = mysqli_fetch_array($result1)) {
			$invoice_no_id = $row1[7];
			if ($row1[0] == 1)
				$payment_cash[$invoice_no_id] = (double) $row1[1];
			if ($row1[0] == 2) {
				$payment_chque[$invoice_no_id] = (double) $row1[1];
				$chq_details[$invoice_no_id] = 'Chque No    : ' . $row1[2] . ' | ' . $row1[3] . ' | ' . $row1[4] . '&#10;Bank             : ' . $row1[6] . '&#10;Chque Date : ' . $row1[5];
			}
			if ($row1[0] == 3) {
				$query2 = "SELECT name FROM accounts WHERE id='$row1[8]'";
				$row2 = mysqli_fetch_row(mysqli_query($conn2, $query2));
				$payment_bank[$invoice_no_id] = (double) $row1[1];
				$payment_bank_tr[$invoice_no_id] = 'Bank    : ' . $row2[0];
			}
			if ($row1[0] == 4) {
				$query2 = "SELECT name FROM accounts WHERE id='$row1[8]'";
				$row2 = mysqli_fetch_row(mysqli_query($conn2, $query2));
				$payment_card[$invoice_no_id] = (double) $row1[1];
				$card_no = "**** **** **** " . substr($row1[9], 15);
				$payment_card_tr[$invoice_no_id] = 'Card    : ' . $card_no . ' | Bank : ' . $row2[0];
			}
		}

		$query = "SELECT
		bm.invoice_no,SUM(bi.qty*bi.unit_price),di.name,up.username,cu.name,bm.billed_timestamp,st.name,SUM(bi.discount),bm.`cust`,up.id,bm.gps_x,bm.gps_y
		FROM bill bi, bill_main bm, userprofile up, district di, cust cu, stores st, cust_group gp
		WHERE bm.store=st.id AND cu.`associated_group`=gp.id AND bm.`cust`=cu.id AND bi.invoice_no=bm.invoice_no AND bm.billed_by=up.id AND bm.billed_district=di.id AND bm.`status` NOT IN (0,7) AND bm.exclude=0
		$sub_sys_bmqry $cashback_qry $lock_qry $type_qry $date_qry1 $storesearch $groupsearch $salesmansearch $processbysearch
		GROUP BY bm.invoice_no ORDER BY $order_by";

		$result = mysqli_query($conn2, $query);
		while ($row = mysqli_fetch_array($result)) {
			$invoice_no_id = $row[0];
			$invoice_no[] = $row[0];
			$invoice_Total[] = $row[1];
			$billed_district[] = $row[2];
			$billed_by[] = $row[3];
			$billed_cust[] = $row[4];
			$billed_time[] = substr($row[5], 0, 16);
			$billed_store[] = $row[6];
			if (isset($_REQUEST['components']) && $_REQUEST['components'] != 'marketing') {
				$bi_discount[] = $row[7];
			}
			if (!in_array($row[3], $graph_user)) {
				$graph_user[] = $row[3];
			}
			$key = array_search($row[3], $graph_user);
			if (isset($graph_total[$key]))
				$total = $graph_total[$key];
			else
				$total = 0;
			$graph_total[$key] = $total + $row[1];

			if ($row[10] != 0 && $row[11] != 0) {
				$gps = $gps . $row[8] . ':' . $row[9] . ':' . $row[10] . ':' . $row[11] . ',';
				$cu_list_arr[] = $row[8];
			}

			if (!isset($payment_cash[$invoice_no_id]))
				$payment_cash[$invoice_no_id] = 0.0;
			if (!isset($payment_chque[$invoice_no_id]))
				$payment_chque[$invoice_no_id] = 0.0;
			if (!isset($payment_card[$invoice_no_id]))
				$payment_card[$invoice_no_id] = 0.0;
			if (!isset($payment_bank[$invoice_no_id]))
				$payment_bank[$invoice_no_id] = 0.0;
			if (!isset($chq_details[$invoice_no_id]))
				$chq_details[$invoice_no_id] = "";
			if (!isset($payment_bank_tr[$invoice_no_id]))
				$payment_bank_tr[$invoice_no_id] = "";
			if (!isset($payment_card_tr[$invoice_no_id]))
				$payment_card_tr[$invoice_no_id] = "";

			if ($systemid == 14) {
				$query2 = "SELECT `item_type` FROM bill WHERE `invoice_no`='$row[0]'";
				$result2 = mysqli_query($conn2, $query2);
				$item_type_1_present = false;
				$item_type_2_present = false;
				while ($row2 = mysqli_fetch_array($result2)) {
					if ($row2[0] == 1) {
						$item_type_1_present = true;
					} elseif ($row2[0] == 2) {
						$item_type_2_present = true;
					}
				}
				if ($item_type_1_present && $item_type_2_present) {
					$item_type[$row[0]] = 3; // Both item types 1 and 2 present
				} elseif ($item_type_1_present) {
					$item_type[$row[0]] = 1; // Only item type 1 present
				} elseif ($item_type_2_present) {
					$item_type[$row[0]] = 2; // Only item type 2 present
				} else {
					$item_type[$row[0]] = ''; // No item types present
				}
			}
		}
	}

	$query2 = "SELECT py.id,py.amount,py.payment_type,up.username,cu.name,py.payment_date, st.name,py.chque_no,bk.bank_code,py.chque_branch,py.chque_date,bk.name,py.`cust`,up.id,py.gps_x,py.gps_y,py.bank_trans,py.card_no
	FROM userprofile up, cust cu, stores st, cust_group gp, payment py
	LEFT JOIN bank bk ON py.chque_bank=bk.id WHERE py.salesman=up.id AND py.cust=cu.id AND py.store=st.id AND cu.`associated_group`=gp.id AND py.bill_pay=2 AND py.`status`=0
	$sub_sys_pyqry $date_qry2 $pycashback_qry $storesearch $groupsearch $salesmansearch
	ORDER BY st.name, py.payment_date";

	$result2 = mysqli_query($conn2, $query2);
	while ($row2 = mysqli_fetch_array($result2)) {
		if ($row2[0] > 0) {
			$chq_details_tmp = 'Chque No    : ' . $row2[7] . ' | ' . $row2[8] . ' | ' . $row2[9] . '&#10;Bank             : ' . $row2[11] . '&#10;Chque Date : ' . $row2[10];
			$payment_id[] = $row2[0];
			$payment_amount[] = $row2[1];
			if ($row2[2] == 1) {
				$payment_type[] = 'Cash';
				$payment_details[] = 'href="#" style="text-decoration:none; color:#009900;" title="CASH"';
			}
			if ($row2[2] == 2) {
				$payment_type[] = 'Chque';
				$payment_details[] = 'href="#" style="text-decoration:none; color:blue;" title="' . $chq_details_tmp . '"';
			}
			if ($row2[2] == 3) {
				$payment_type[] = 'Bank';
				$query1 = "SELECT name FROM accounts WHERE id='$row2[16]'";
				$row1 = mysqli_fetch_row(mysqli_query($conn2, $query1));
				$payment_details[] = 'href="#" style="text-decoration:none; color:#00AAAA;" title="Bank : ' . $row1[0] . '"';
			}
			if ($row2[2] == 4) {
				$payment_type[] = 'Card';
				$query1 = "SELECT name FROM accounts WHERE id='$row2[16]'";
				$row1 = mysqli_fetch_row(mysqli_query($conn2, $query1));
				$card_no = "**** **** **** " . substr($row2[17], 15);
				$payment_details[] = 'href="#" style="text-decoration:none; color:#CC3399;" title="Card : ' . $card_no . ' | Bank : ' . $row1[0] . '"';
			} else {
				$bank_trans[] = '';
			}
			$payment_salesman[] = $row2[3];
			$payment_cust[] = $row2[4];
			$payment_time[] = substr($row2[5], 0, 16);
			$payment_store[] = $row2[6];
			$payment_cheque_no[] = $row2[7];
			$payment_cheque_date[] = $row2[10];
			if (array_search($row2[12], $cu_list_arr) === false) {
				if ($row2[14] != 0 && $row2[15] != 0) {
					$gps = $gps . $row2[12] . ':' . $row2[13] . ':' . $row2[14] . ':' . $row2[15] . ',';
				}
			}
		} else {
			$payment_id = $payment_amount = $payment_type = $payment_salesman = $payment_cust = $payment_time = $payment_store = $payment_details = $payment_cheque_no = $payment_cheque_date = array();
		}
	}

	if ($type_req == 3 || $type_req == '') {

		$query3 = "SELECT rm.invoice_no,time(rm.return_date),SUM(rt.extra_pay),up.username,st.name,cu.name
		FROM return_main rm, `return` rt, stores st, cust_group gp, userprofile up, cust cu
		WHERE rm.invoice_no=rt.invoice_no AND rm.store=st.id AND cu.`associated_group`=gp.id AND rm.return_by=up.id AND rm.`cust`=cu.id AND rt.extra_pay!=0 AND rm.`status`=2
		$sub_sys_rtqry $date_qry3 $storesearch $groupsearch $salesmansearch
		GROUP BY rm.invoice_no ORDER BY st.name";

		$result3 = mysqli_query($conn2, $query3);
		while ($row3 = mysqli_fetch_array($result3)) {
			$rtn_no[] = $row3[0];
			$rtn_time[] = $row3[1];
			$rtn_pay[] = $row3[2];
			$rtn_salesman[] = $row3[3];
			$rtn_store[] = $row3[4];
			$rtn_cust[] = $row3[5];
		}
	}

	if ($type_req == 5 || $type_req == '') {

		$query3 = "SELECT wa.id,wa.cust_pay_date,wa.cust_pay_amount,up.username,st.name
		FROM warranty wa, userprofile up, stores st
		WHERE wa.store=st.id AND wa.cust_pay_by=up.id AND wa.`status`!=0 AND wa.cust_pay_amount!=0
		$sub_sys_waqry $date_qry4 $storesearch $salesmansearch";

		$result3 = mysqli_query($conn2, $query3);
		while ($row3 = mysqli_fetch_array($result3)) {
			$wa_no[] = $row3[0];
			$wa_time[] = $row3[1];
			$wa_pay[] = $row3[2];
			$wa_salesman[] = $row3[3];
			$wa_entity[] = '<span style="color:#0000CC">Paid by</span> Customer';
			$wa_store[] = $row3[4];
		}

		$query3 = "SELECT wa.id,wa.suplier_pay_date,wa.suplier_pay,up.username,st.name
		FROM warranty wa, userprofile up, stores st
		WHERE wa.store=st.id AND wa.suplier_pay_by=up.id AND wa.`status`!=0 AND wa.suplier_pay!=0
		$sub_sys_waqry $date_qry5 $storesearch $salesmansearch";

		$result3 = mysqli_query($conn2, $query3);
		while ($row3 = mysqli_fetch_array($result3)) {
			$wa_no[] = $row3[0];
			$wa_time[] = $row3[1];
			$wa_pay[] = -$row3[2];
			$wa_salesman[] = $row3[3];
			$wa_entity[] = '<span style="color:#00CC00">Paid to</span> Supplier';
			$wa_store[] = $row3[4];
		}
	}
}

// updated by nirmal 21_12_9
// update by nirmal 12_02_2024 (added invoice type to show in view in different colors (bill row item_type))
// update by nirmal 21_02_2024 bug fix in result (added missing payment amount selecting for each invoice as payment type)
// update by nirmal 14_07_2024 (added missing $sub_sys_waqry to if ($sub_system == 'all'))
// update by nirmal 13_08_2024 (remove round ups numbers in sql queries)

function dailySale2($store, $sub_system)
{
	global $lock_req, $type_req, $date, $invoice_no, $invoice_Total, $billed_district, $billed_by, $billed_cust, $billed_time, $billed_store, $payment_cash, $payment_chque, $payment_id, $payment_amount, $payment_type, $payment_salesman, $payment_cust, $payment_time, $payment_store, $chq_details, $payment_chq_details, $bi_item, $bi_qty, $bi_uprice, $bi_discount, $rtn_no, $rtn_time, $rtn_pay, $rtn_salesman, $rtn_store, $rtn_cust, $rtn_returnitem, $rtn_replaceitem, $rtn_qty, $rtn_expay, $payment_bank, $payment_card, $wa_no, $wa_time, $wa_pay, $wa_salesman, $wa_entity, $wa_store, $item_type;
	$lock_qry = $type_qry = $type_req = '';
	$invoice_no = $payment_id = $rtn_no = $wa_no = $item_type = array();
	$systemid = inf_systemid(1);

	if (isset($_REQUEST['date']))
		$date = $_REQUEST['date'];
	else
		$date = date("Y-m-d", time());
	$group = $_REQUEST['group'];
	if ($sub_system == 'all') {
		$sub_sys_bmqry = $sub_sys_pyqry = $sub_sys_rtqry = $sub_sys_waqry = '';
	} else {
		$sub_sys_bmqry = "AND bm.`sub_system`='$sub_system'";
		$sub_sys_pyqry = "AND py.`sub_system`='$sub_system'";
		$sub_sys_rtqry = "AND rm.`sub_system`='$sub_system'";
		$sub_sys_waqry = "AND wa.`sub_system`='$sub_system'";
	}
	if ($group == 'all')
		$groupsearch = '';
	else
		$groupsearch = "AND gp.id='" . $group . "'";
	if ($store == 'all')
		$storesearch = '';
	else
		$storesearch = "AND st.id='" . $store . "'";
	if ($_REQUEST['salesman'] == 'all')
		$salesmansearch = '';
	else
		$salesmansearch = "AND up.`id`='" . $_REQUEST['salesman'] . "'";
	if ($_REQUEST['processby'] == 'all')
		$processbysearch = '';
	else
		$processbysearch = "AND bm.`packed_by`='" . $_REQUEST['processby'] . "'";
	if (isset($_REQUEST['type'])) {
		$type_req = $_REQUEST['type'];
		if ($type_req == 1)
			$type_qry = "AND bm.`type` IN (1,4)";
		if ($type_req == 2)
			$type_qry = "AND bm.`type` IN (2,5)";
		if ($type_req == 4)
			$type_qry = "AND bm.`type`='3'";
	}
	if (isset($_REQUEST['lock'])) {
		$lock_req = $_REQUEST['lock'];
		if ($lock_req == 'all') {
			$lock_qry = '';
		} else {
			if ($lock_req == 0)
				$lock_qry = "AND bm.`lock` IN (0,2)";
			else
				$lock_qry = "AND bm.`lock`=$lock_req";
		}
	} else {
		$lock_req = 1;
		$lock_qry = "AND bm.`lock`=$lock_req";
	}
	if ($_REQUEST['cashback'] == 'yes') {
		$cashback_qry = "AND bi.qty<0";
		$pycashback_qry = "AND py.amount<0";
	} else {
		$cashback_qry = $pycashback_qry = "";
	}

	include('config.php');
	if ($type_req != 3) {
		$query1 = "SELECT py.payment_type,py.amount,py.chque_no,bk.bank_code,py.chque_branch,py.chque_date,bk.name,py.invoice_no,py.bank_trans,py.card_no FROM payment py LEFT JOIN bank bk ON py.chque_bank=bk.id WHERE py.bill_pay=1 AND py.`status`='0' AND date(py.payment_date)='$date'";
		$result1 = mysqli_query($conn2, $query1);
		while ($row1 = mysqli_fetch_array($result1)) {
			$invoice_no_id = $row1[7];
			if ($row1[0] == 1)
				$payment_cash[$invoice_no_id] = $row1[1];
			if ($row1[0] == 2) {
				$payment_chque[$invoice_no_id] = $row1[1];
				$chq_details[$invoice_no_id] = 'Chque No    : ' . $row1[2] . ' | ' . $row1[3] . ' | ' . $row1[4] . '&#10;Bank             : ' . $row1[6] . '&#10;Chque Date : ' . $row1[5];
			}
			if ($row1[0] == 3) {
				$query2 = "SELECT name FROM accounts WHERE id='$row1[8]'";
				$row2 = mysqli_fetch_row(mysqli_query($conn2, $query2));
				$payment_bank[$invoice_no_id] = $row1[1];
				$chq_details[$invoice_no_id] = 'Bank    : ' . $row2[0];
			}
			if ($row1[0] == 4) {
				$query2 = "SELECT name FROM accounts WHERE id='$row1[8]'";
				$row2 = mysqli_fetch_row(mysqli_query($conn2, $query2));
				$payment_card[$invoice_no_id] = $row1[1];
				$card_no = "**** **** **** " . substr($row1[9], 15);
				$chq_details[$invoice_no_id] = 'Card    : ' . $card_no . ' | Bank : ' . $row2[0];
			}
		}

		$query1 = "SELECT itm.description,bi.qty,bi.unit_price,bm.invoice_no FROM bill_main bm, bill bi, inventory_items itm WHERE bi.invoice_no=bm.invoice_no AND bi.item=itm.id AND bm.`status` NOT IN (0,7) AND bm.exclude=0 AND date(bm.billed_timestamp)='$date'";
		$result1 = mysqli_query($conn2, $query1);
		while ($row1 = mysqli_fetch_array($result1)) {
			$invoice_no_id = $row1[3];
			$bi_item[$invoice_no_id][] = $row1[0];
			$bi_qty[$invoice_no_id][] = $row1[1];
			$bi_uprice[$invoice_no_id][] = $row1[2];
		}

		$query = "SELECT bm.invoice_no,SUM(bi.qty*bi.unit_price),di.name,up.username,cu.name,time(bm.billed_timestamp),st.name,SUM(bi.discount) FROM bill bi, bill_main bm, userprofile up, district di, cust cu, stores st, cust_group gp WHERE bm.store=st.id AND cu.`associated_group`=gp.id AND bm.`cust`=cu.id AND bi.invoice_no=bm.invoice_no AND bm.billed_by=up.id AND bm.billed_district=di.id AND bm.`status` NOT IN (0,7) AND bm.exclude=0 AND date(bm.billed_timestamp)='$date' $sub_sys_bmqry $cashback_qry $lock_qry $type_qry $storesearch $groupsearch $salesmansearch $processbysearch GROUP BY bm.invoice_no ORDER BY st.name,bm.invoice_no";
		$result = mysqli_query($conn2, $query);
		while ($row = mysqli_fetch_array($result)) {
			$invoice_no_id = $row[0];
			$invoice_no[] = $row[0];
			$invoice_Total[] = $row[1];
			$billed_district[] = $row[2];
			$billed_by[] = $row[3];
			$billed_cust[] = $row[4];
			$billed_time[] = $row[5];
			$billed_store[] = $row[6];
			$bi_discount[] = $row[7];

			// added by nirmal 21_02_2024
			$query1 = "SELECT payment_type,amount FROM payment WHERE bill_pay=1 AND `status`='0' AND `invoice_no`='$invoice_no_id'";
			$result1 = mysqli_query($conn2, $query1);
			while ($row1 = mysqli_fetch_array($result1)) {
				if ($row1[0] == 1)
					$payment_cash[$invoice_no_id] = $row1[1];
				if ($row1[0] == 2)
					$payment_chque[$invoice_no_id] = $row1[1];
				if ($row1[0] == 3)
					$payment_bank[$invoice_no_id] = $row1[1];
				if ($row1[0] == 4)
					$payment_card[$invoice_no_id] = $row1[1];
			}

			if (!isset($payment_cash[$invoice_no_id]))
				$payment_cash[$invoice_no_id] = 0;
			if (!isset($payment_card[$invoice_no_id]))
				$payment_card[$invoice_no_id] = 0;
			if (!isset($payment_bank[$invoice_no_id]))
				$payment_bank[$invoice_no_id] = 0;
			if (!isset($payment_chque[$invoice_no_id]))
				$payment_chque[$invoice_no_id] = 0;
			if (!isset($chq_details[$invoice_no_id]))
				$chq_details[$invoice_no_id] = '';

			if ($systemid == 14) {
				$query1 = "SELECT `item_type` FROM bill WHERE `invoice_no`='$row[0]'";
				$result1 = mysqli_query($conn2, $query1);
				$item_type_1_present = false;
				$item_type_2_present = false;
				while ($row1 = mysqli_fetch_array($result1)) {
					if ($row1[0] == 1) {
						$item_type_1_present = true;
					} elseif ($row1[0] == 2) {
						$item_type_2_present = true;
					}
				}
				if ($item_type_1_present && $item_type_2_present) {
					$item_type[$row[0]] = 3; // Both item types 1 and 2 present
				} elseif ($item_type_1_present) {
					$item_type[$row[0]] = 1; // Only item type 1 present
				} elseif ($item_type_2_present) {
					$item_type[$row[0]] = 2; // Only item type 2 present
				} else {
					$item_type[$row[0]] = ''; // No item types present
				}
			}
		}
	}

	$query2 = "SELECT py.id,py.amount,py.payment_type,up.username,cu.name,time(py.payment_date), st.name,py.chque_no,bk.bank_code,py.chque_branch,py.chque_date,bk.name,py.bank_trans,py.card_no FROM userprofile up, cust cu, stores st, cust_group gp, payment py LEFT JOIN bank bk ON py.chque_bank=bk.id WHERE py.salesman=up.id AND py.cust=cu.id AND py.store=st.id AND cu.`associated_group`=gp.id AND py.bill_pay=2 AND py.`status`=0 AND date(py.payment_date)='$date' $sub_sys_pyqry $pycashback_qry $storesearch $groupsearch $salesmansearch ORDER BY st.name,py.id";
	$result2 = mysqli_query($conn2, $query2);
	while ($row2 = mysqli_fetch_array($result2)) {
		if ($row2[0] > 0) {
			$payment_id[] = $row2[0];
			$payment_amount[] = $row2[1];
			if ($row2[2] == 1) {
				$payment_type[] = 'Cash';
				$payment_chq_details[] = '';
			}
			if ($row2[2] == 2) {
				$payment_type[] = 'Chque';
				$payment_chq_details[] = 'Chque No    : ' . $row2[7] . ' | ' . $row2[8] . ' | ' . $row2[9] . '&#10;Bank             : ' . $row2[11] . '&#10;Chque Date : ' . $row2[10];
				;
			}
			if ($row2[2] == 3) {
				$payment_type[] = 'Bank';
				$query1 = "SELECT name FROM accounts WHERE id='$row2[12]'";
				$row1 = mysqli_fetch_row(mysqli_query($conn2, $query1));
				$payment_chq_details[] = 'Bank : ' . $row1[0];
			}
			if ($row2[2] == 4) {
				$payment_type[] = 'Card';
				$query1 = "SELECT name FROM accounts WHERE id='$row2[12]'";
				$row1 = mysqli_fetch_row(mysqli_query($conn2, $query1));
				$card_no = "**** **** **** " . substr($row2[13], 15);
				$payment_chq_details[] = 'Card : ' . $card_no . ' | Bank : ' . $row1[0];
			} else {
				$bank_trans[] = '';
			}
			$payment_salesman[] = $row2[3];
			$payment_cust[] = $row2[4];
			$payment_time[] = $row2[5];
			$payment_store[] = $row2[6];
		} else
			$payment_id = $payment_amount = $payment_type = $payment_salesman = $payment_cust = $payment_time = $payment_store = $payment_chq_details = array();
	}

	if ($type_req == 3 || $type_req == '') {
		$query3 = "SELECT rm.invoice_no,time(rm.return_date),SUM(rt.extra_pay),up.username,st.name,cu.name FROM return_main rm, `return` rt, stores st, cust_group gp, userprofile up, cust cu WHERE rm.invoice_no=rt.invoice_no AND rm.store=st.id AND cu.`associated_group`=gp.id AND rm.return_by=up.id AND rm.`cust`=cu.id AND date(rm.return_date)='$date' AND rt.extra_pay!=0 AND rm.`status`=2 $sub_sys_rtqry $storesearch $groupsearch $salesmansearch ORDER BY st.name, rt.invoice_no";
		$result3 = mysqli_query($conn2, $query3);
		while ($row3 = mysqli_fetch_array($result3)) {
			if ($row3[2] != 0) {
				$rtn_invno = $row3[0];
				$rtn_no[] = $row3[0];
				$rtn_time[] = $row3[1];
				$rtn_pay[] = $row3[2];
				$rtn_salesman[] = $row3[3];
				$rtn_store[] = $row3[4];
				$rtn_cust[] = $row3[5];
				$query1 = "SELECT itm1.description,itm2.description,rt.qty,rt.extra_pay FROM `return` rt, inventory_items itm1, inventory_items itm2 WHERE rt.return_item=itm1.id AND rt.replace_item=itm2.id AND rt.invoice_no='$rtn_invno'";
				$result1 = mysqli_query($conn2, $query1);
				while ($row1 = mysqli_fetch_array($result1)) {
					$rtn_returnitem[$rtn_invno][] = $row1[0];
					$rtn_replaceitem[$rtn_invno][] = $row1[1];
					$rtn_qty[$rtn_invno][] = $row1[2];
					$rtn_expay[$rtn_invno][] = $row1[3];
				}
			}
		}
	}
	if ($type_req == 5 || $type_req == '') {
		$query3 = "SELECT wa.id,wa.cust_pay_date,wa.cust_pay_amount,up.username,st.name FROM warranty wa, userprofile up, stores st WHERE wa.store=st.id AND wa.cust_pay_by=up.id AND wa.`status`!=0 AND wa.cust_pay_amount!=0 AND date(wa.cust_pay_date)='$date' $sub_sys_waqry $storesearch $salesmansearch";
		$result3 = mysqli_query($conn2, $query3);
		while ($row3 = mysqli_fetch_array($result3)) {
			$wa_no[] = $row3[0];
			$wa_time[] = $row3[1];
			$wa_pay[] = $row3[2];
			$wa_salesman[] = $row3[3];
			$wa_entity[] = '<span style="color:#0000CC" >Paid by</span> Customer';
			$wa_store[] = $row3[4];
		}
		$query3 = "SELECT wa.id,wa.suplier_pay_date,wa.suplier_pay,up.username,st.name FROM warranty wa, userprofile up, stores st WHERE wa.store=st.id AND wa.suplier_pay_by=up.id AND wa.`status`!=0 AND wa.suplier_pay!=0 AND date(wa.suplier_pay_date)='$date' $sub_sys_waqry $storesearch $salesmansearch";
		$result3 = mysqli_query($conn2, $query3);
		while ($row3 = mysqli_fetch_array($result3)) {
			$wa_no[] = $row3[0];
			$wa_time[] = $row3[1];
			$wa_pay[] = -$row3[2];
			$wa_salesman[] = $row3[3];
			$wa_entity[] = '<span style="color:#00CC00" >Paid to</span> Supplier';
			$wa_store[] = $row3[4];
		}
	}
}

// updated by nirmal 21_12_13
// update by nirmal 27_07_2024 (set default 3 month old day as from date when view loading)
function getCustSale($systemid)
{
	global $customer, $decimal, $fromdate, $todate, $invoice_no, $invoice_Total, $billed_district, $billed_by, $billed_date, $billed_time, $billed_store,
	$payment_cash, $payment_card, $payment_chque, $payment_id, $payment_amount, $payment_type, $payment_salesman, $payment_date, $payment_time,
	$payment_store, $credit_balance, $payment_chq_return, $chqpayment_id, $chqpayment_amount, $chqpayment_salesman, $chqpayment_cust, $chqrtn_date,
	$chqrtn_time, $chqpayment_store, $chqpayment_invno, $chq_details, $payment_card_details, $payment_chq_details, $chqrtn_chq_details, $payment_bank,
	$payment_card_tr, $payment_bank_tr, $bank_trans, $payment_invoice, $payment_chq_postpone, $billed_chq_return, $billed_chq_postpone, $cust_mtype;

	$invoice_no = $payment_id = $invoice_Total = $payment_cash = $payment_card = $payment_chque = $billed_date = $payment_date = $chqrtn_date = $chqpayment_id = $payment_invoice = $payment_card_details = $payment_chq_details = array();
	$acc_arr = $py_inv_no = $py_type = $py_amount = $py_chq_no = $py_bnk_code = $py_chq_br = $py_chq_date = $py_bnk_name = $py_bnk_trans = $py_chq_rtn = $py_chq_ppone = $py_card_no = array();

	include('config.php');
	$decimal = getDecimalPlaces(1);

	if ($systemid == 10 || $systemid == 13 || $systemid == 14 || $systemid == 24) {
		if (isset($_GET['datefrom'])) {
			if ($_GET['datefrom'] != '') {
				$fromdate = $_GET['datefrom'];
			} else {
				$fromdate = date("Y-m-d", time() - (60 * 60 * 24 * 30 * 3));
			}
		} else {
			$fromdate = date("Y-m-d", time() - (60 * 60 * 24 * 30 * 3));
		}
		if (isset($_GET['datefrom'])) {
			if ($_GET['datefrom'] != '') {
				$todate = $_GET['datefrom'];
			} else {
				$todate = dateNow();
			}
		} else {
			$todate = dateNow();
		}
	}
	if (isset($_REQUEST['customer_id'])) {
		$customer_id = $_REQUEST['customer_id'];
		$fromdate = $_REQUEST['datefrom'];
		$todate = $_REQUEST['dateto'];

		//-------------Customer M Type----------------------//
		// Legecy code before change 16_02_2024
		// $cust_mtype = 'normal';
		// $query = "SELECT master_cust,`name` FROM cust WHERE id='$customer_id'";
		// $row = mysqli_fetch_row(mysqli_query($conn, $query));
		// $master_cust = $row[0];
		// $customer = $row[1];
		// if ($master_cust > 0) {
		// 	$cust_mtype = 'secondary';
		// } else {
		// 	$query = "SELECT COUNT(id) FROM cust WHERE master_cust='$customer_id'";
		// 	$row = mysqli_fetch_row(mysqli_query($conn, $query));
		// 	if ($row[0] > 0){
		// 		$cust_mtype = 'primary';
		// 	}
		// }
		$query = "SELECT c.master_cust, c.name,  COUNT(c2.id) AS child_count FROM cust c LEFT JOIN cust c2 ON c.id = c2.master_cust WHERE c.id = '$customer_id' GROUP BY c.id";
		$result = mysqli_query($conn2, $query);
		$row = mysqli_fetch_assoc($result);
		$master_cust = $row['master_cust'];
		$customer = $row['name'];

		if ($master_cust > 0) {
			$cust_mtype = 'secondary';
		} elseif ($row['child_count'] > 0) {
			$cust_mtype = 'primary';
		} else {
			$cust_mtype = 'normal';
		}
		//-------------Customer M Type END-------------------//
		$acc_arr[''] = '';
		$query = "SELECT id,name FROM accounts";
		$result = mysqli_query($conn2, $query);
		while ($row = mysqli_fetch_array($result)) {
			$acc_arr[$row[0]] = $row[1];
		}

		// 0=deleted,1=billed, 2=seen, 3=packed, 4=shipped, 5=delivered, 6=repair_reject, 7=reject_delivered
		$exclude_status = "0,7";
		if ($systemid == 13) {
			$exclude_status = "0,1,2,3,4,6,7";
		}

		// Legecy code before change 16_02_2024
		// $query = "SELECT py.invoice_no,py.payment_type,py.amount,py.chque_no,bk.bank_code,py.chque_branch,py.chque_date,bk.name,py.bank_trans,py.chque_return,py.chque_postpone,py.card_no FROM bill_main bm, payment py LEFT JOIN bank bk ON py.chque_bank=bk.id WHERE bm.invoice_no=py.invoice_no AND py.bill_pay=1 AND py.`status`='0' AND py.`cust`='$customer_id' AND date(bm.billed_timestamp)>='$fromdate'";
		$query = "SELECT py.invoice_no, py.payment_type, py.amount, py.chque_no, bk.bank_code, py.chque_branch, py.chque_date, bk.name, py.bank_trans, py.chque_return, py.chque_postpone, py.card_no
		FROM payment py
		INNER JOIN bill_main bm ON bm.invoice_no = py.invoice_no
		LEFT JOIN bank bk ON py.chque_bank = bk.id
		WHERE py.bill_pay = 1
		AND py.`status` = '0'
		AND py.`cust` = '$customer_id'
		AND bm.billed_timestamp >= '$fromdate'";
		$result = mysqli_query($conn2, $query);
		while ($row = mysqli_fetch_array($result)) {
			$py_inv_no[] = $row[0];
			$py_type[] = $row[1];
			$py_amount[] = $row[2];
			$py_chq_no[] = $row[3];
			$py_bnk_code[] = $row[4];
			$py_chq_br[] = $row[5];
			$py_chq_date[] = $row[6];
			$py_bnk_name[] = $row[7];
			$py_bnk_trans[] = $acc_arr[$row[8]];
			$py_chq_rtn[] = $row[9];
			$py_chq_ppone[] = $row[10];
			$py_card_no[] = $row[11];
		}

		// Legecy code before change 16_02_2024
		// $query = "SELECT bm.invoice_no,round(SUM(bi.qty*bi.unit_price),2),di.name,up.username,date(bm.billed_timestamp),time(bm.billed_timestamp), st.name FROM bill bi, bill_main bm, userprofile up, district di, stores st WHERE bm.store=st.id AND bi.invoice_no=bm.invoice_no AND bm.billed_by=up.id AND bm.billed_district=di.id AND bm.`status` NOT IN (0,7) AND bm.exclude=0 AND bm.lock=1 AND (date(bm.billed_timestamp) BETWEEN '$fromdate' AND '$todate') AND bm.`cust`='$customer_id' GROUP BY bm.invoice_no ORDER BY bm.billed_timestamp";
		$query = "SELECT bm.invoice_no, ROUND(SUM(bi.qty * bi.unit_price), 2) AS total_amount, di.name AS district_name, up.username AS billed_by_username, DATE(bm.billed_timestamp) AS billed_date, TIME(bm.billed_timestamp) AS billed_time, st.name AS store_name
		FROM bill_main bm
		INNER JOIN stores st ON bm.store = st.id
		INNER JOIN userprofile up ON bm.billed_by = up.id
		INNER JOIN district di ON bm.billed_district = di.id
		INNER JOIN bill bi ON bi.invoice_no = bm.invoice_no
		WHERE bm.`status` NOT IN (0, 7)
		AND bm.exclude = 0
		AND bm.lock = 1
		AND DATE(bm.billed_timestamp) BETWEEN '$fromdate' AND '$todate'
		AND bm.`cust` = '$customer_id'
		GROUP BY bm.invoice_no
		ORDER BY bm.billed_timestamp";
		$result = mysqli_query($conn2, $query);
		while ($row = mysqli_fetch_array($result)) {
			$invoice_no_id = $row[0];
			$invoice_no[] = $row[0];
			$invoice_Total[] = $row[1];
			$billed_district[] = $row[2];
			$billed_by[] = $row[3];
			$billed_date[] = $row[4];
			$billed_time[] = $row[5];
			$billed_store[] = $row[6];

			$payment_cash[$invoice_no_id] = $payment_card[$invoice_no_id] = $payment_bank[$invoice_no_id] = $payment_chque[$invoice_no_id] = 0;
			$payment_bank_tr[$invoice_no_id] = $payment_card_tr[$invoice_no_id] = $chq_details[$invoice_no_id] = $billed_chq_return[$invoice_no_id] = $billed_chq_postpone[$invoice_no_id] = '';

			for ($i = 0; $i < sizeof($py_inv_no); $i++) {
				if ($py_inv_no[$i] == $invoice_no_id) {
					$key = $i;
					if ($py_type[$key] == 1) {
						$payment_cash[$invoice_no_id] += $py_amount[$key];
					}
					if ($py_type[$key] == 2) {
						$payment_chque[$invoice_no_id] += $py_amount[$key];
						;
						$chq_details[$invoice_no_id] .= 'Chque No    : ' . $py_chq_no[$key] . ' | ' . $py_bnk_code[$key] . ' | ' . $py_chq_br[$key] . '&#10;Bank             : ' . $py_bnk_name[$key] . '&#10;Chque Date : ' . $py_chq_date[$key];
					}
					if ($py_type[$key] == 3) {
						$payment_bank[$invoice_no_id] += $py_amount[$key];
						$payment_bank_tr[$invoice_no_id] .= 'Bank    : ' . $py_bnk_trans[$key];
					}
					if ($py_type[$key] == 4) {
						$payment_card[$invoice_no_id] += $py_amount[$key];
						$card_no = "**** **** **** " . substr($py_card_no[$key], 15);
						$payment_card_tr[$invoice_no_id] .= 'Card No    : ' . $card_no . ' | Bank : ' . $py_bnk_trans[$key];
					}
					$billed_chq_return[$invoice_no_id] .= $py_chq_rtn[$key];
					$billed_chq_postpone[$invoice_no_id] .= $py_chq_ppone[$key];
				}
			}

			/*
					 $query1="SELECT py.payment_type,py.amount,py.chque_no,bk.bank_code,py.chque_branch,py.chque_date,bk.name,py.bank_trans,py.chque_return,py.chque_postpone FROM payment py LEFT JOIN bank bk ON py.chque_bank=bk.id WHERE py.bill_pay=1 AND py.invoice_no='$invoice_no_id' AND py.`status`='0'";
					 $result1=mysqli_query($conn2,$query1);
					 while($row1=mysqli_fetch_array($result1)){
						 if($row1[0]==1)	$payment_cash[$invoice_no_id]=$row1[1];
						 if($row1[0]==2){
							 $payment_chque[$invoice_no_id]=$row1[1];
							 $chq_details[$invoice_no_id]='Chque No    : '.$row1[2].' | '.$row1[3].' | '.$row1[4].'&#10;Bank             : '.$row1[6].'&#10;Chque Date : '.$row1[5];
						 }
						 if($row1[0]==3){
							 $query2="SELECT name FROM accounts WHERE id='$row1[7]'";
							 $row2=mysqli_fetch_row(mysqli_query($conn2,$query2));
							 $payment_bank[$invoice_no_id]=$row1[1];
							 $payment_bank_tr[$invoice_no_id]='Bank    : '.$row2[0];
						 }
						 $billed_chq_return[$invoice_no_id]=$row1[8];
						 $billed_chq_postpone[$invoice_no_id]=$row1[9];
					 }
					 */
			/*
					 if(!isset($payment_cash[$invoice_no_id])) $payment_cash[$invoice_no_id]=0;
					 if(!isset($payment_bank[$invoice_no_id])) $payment_bank[$invoice_no_id]=0;
					 if(!isset($payment_bank_tr[$invoice_no_id]))$payment_bank_tr[$invoice_no_id]='';
					 if(!isset($payment_chque[$invoice_no_id])) $payment_chque[$invoice_no_id]=0;
					 if(!isset($chq_details[$invoice_no_id])) $chq_details[$invoice_no_id]='';
					 if(!isset($billed_chq_return[$invoice_no_id])) $billed_chq_return[$invoice_no_id]='';
					 if(!isset($billed_chq_postpone[$invoice_no_id])) $billed_chq_postpone[$invoice_no_id]='';
					 */
		}

		// Legecy code before change 16_02_2024
		// $query2 = "SELECT py.id,round(py.amount),py.payment_type,up.username,date(py.payment_date),time(py.payment_date), st.name,py.chque_return,py.chque_no,bk.bank_code,py.chque_branch,py.chque_date,bk.name,py.bank_trans,py.invoice_no,py.chque_postpone,py.card_no FROM userprofile up, stores st, payment py LEFT JOIN bank bk ON py.chque_bank=bk.id WHERE py.salesman=up.id AND py.store=st.id AND py.bill_pay=2 AND py.`status`=0 AND (date(py.payment_date) BETWEEN '$fromdate' AND '$todate') AND py.`cust`='$customer_id'";
		$query2 = "SELECT py.id, ROUND(py.amount) AS rounded_amount, py.payment_type, up.username AS salesman_username, DATE(py.payment_date) AS payment_date, TIME(py.payment_date) AS payment_time, st.name AS store_name, py.chque_return, py.chque_no, bk.bank_code, py.chque_branch, DATE(py.chque_date) AS chque_date, bk.name AS bank_name, py.bank_trans, py.invoice_no, py.chque_postpone, py.card_no
		FROM payment py
		JOIN userprofile up ON py.salesman = up.id
		JOIN stores st ON py.store = st.id
		LEFT JOIN bank bk ON py.chque_bank = bk.id
		WHERE py.bill_pay = 2 AND py.status = 0 AND DATE(py.payment_date) BETWEEN '$fromdate' AND '$todate' AND py.cust = '$customer_id'";

		$result2 = mysqli_query($conn2, $query2);
		while ($row2 = mysqli_fetch_array($result2)) {
			if ($row2[0] > 0) {
				$payment_id[] = $row2[0];
				$payment_invoice[] = $row2[14];
				$payment_amount[] = $row2[1];
				if ($row2[2] == 1)
					$payment_type[] = 'Cash';
				if ($row2[2] == 2) {
					$payment_type[] = 'Chque';
				}
				if ($row2[2] == 3)
					$payment_type[] = 'Bank';
				if ($row2[2] == 4) {
					$payment_type[] = 'Card';
					$card_no = "**** **** **** " . substr($row2[16], 15);
				}
				$payment_salesman[] = $row2[3];
				$payment_date[] = $row2[4];
				$payment_time[] = $row2[5];
				$payment_store[] = $row2[6];
				$payment_chq_return[] = $row2[7];
				$payment_chq_postpone[] = $row2[15];
				$bank_trans[] = $acc_arr[$row2[13]];
				if ($row2[2] != 3) {
					$bank_trans[] = '';
				} else {
					$bank_trans[] = $acc_arr[$row2[13]];
				}
				if ($row2[2] != 2) {
					$payment_chq_details[] = '';
				} else {
					$payment_chq_details[] = 'Chque No    : ' . $row2[8] . ' | ' . $row2[9] . ' | ' . $row2[10] . '&#10;Bank             : ' . $row2[12] . '&#10;Chque Date : ' . $row2[11];
				}
				if ($row2[2] != 4) {
					$payment_card_details[] = '';
				} else {
					$payment_card_details[] = 'Card No    : ' . $card_no . ' | Bank : ' . $acc_arr[$row2[13]];
				}
			}
		}

		// Legecy code before change 16_02_2024
		// $query2 = "SELECT py.id,round(py.amount),up.username,date(py.chque_return_date),time(py.chque_return_date),st.name,py.invoice_no,py.chque_no,bk.bank_code,py.chque_branch,py.chque_date,bk.name FROM payment py, userprofile up, stores st, bank bk WHERE py.chque_bank=bk.id AND py.salesman=up.id AND up.store=st.id AND py.chque_return=1 AND py.`status`=0 AND (date(py.chque_return_date) BETWEEN '$fromdate' AND '$todate') AND py.cust='$customer_id'";
		$query2 = "SELECT py.id, ROUND(py.amount), up.username, DATE(py.chque_return_date), TIME(py.chque_return_date), st.name, py.invoice_no, py.chque_no, bk.bank_code, py.chque_branch, py.chque_date, bk.name
		FROM payment py
		JOIN userprofile up ON py.salesman = up.id
		JOIN stores st ON up.store = st.id
		JOIN bank bk ON py.chque_bank = bk.id
		WHERE py.chque_return = 1 AND py.`status` = 0 AND (DATE(py.chque_return_date) BETWEEN '$fromdate' AND '$todate') AND py.cust = '$customer_id'";
		/*
				  CREATE INDEX idx_chque_return_date ON payment(chque_return_date);
				  CREATE INDEX idx_chque_bank ON payment(chque_bank);
				  CREATE INDEX idx_salesman ON payment(salesman);
				  CREATE INDEX idx_store ON userprofile(store);
				  CREATE INDEX idx_cust ON payment(cust);
			  */
		$result2 = mysqli_query($conn2, $query2);
		while ($row2 = mysqli_fetch_array($result2)) {
			if ($row2[0] > 0) {
				$chqpayment_id[] = $row2[0];
				$chqpayment_amount[] = -$row2[1];
				$chqpayment_salesman[] = $row2[2];
				$chqrtn_date[] = $row2[3];
				$chqrtn_time[] = $row2[4];
				$chqpayment_store[] = $row2[5];
				$chqpayment_invno[] = $row2[6];
				$chqrtn_chq_details[] = 'Chque No    : ' . $row2[7] . ' | ' . $row2[8] . ' | ' . $row2[9] . '&#10;Bank             : ' . $row2[11] . '&#10;Chque Date : ' . $row2[10];
			}
		}

		// Legecy code before change 16_02_2024
		// $query = "SELECT SUM(bi.qty*bi.unit_price) FROM bill bi, bill_main bm WHERE bm.invoice_no=bi.invoice_no AND bm.`status` NOT IN (0,7) AND bm.exclude=0 AND bm.`lock`=1 AND bm.`cust`='$customer_id' AND date(bm.billed_timestamp) < '$fromdate'";
		$query = "SELECT SUM(bi.qty * bi.unit_price)
		FROM bill_main bm
		JOIN bill bi ON bm.invoice_no = bi.invoice_no
		WHERE bm.`status` NOT IN (0, 7) AND bm.exclude = 0 AND bm.`lock` = 1 AND bm.`cust` = '$customer_id' AND DATE(bm.billed_timestamp) < '$fromdate'";
		$row = mysqli_fetch_row(mysqli_query($conn2, $query));
		$totalbill0 = $row[0];

		// Legecy code before change 16_02_2024
		// $query = "SELECT SUM(py.amount) FROM payment py, bill_main bm WHERE py.invoice_no=bm.invoice_no AND py.`status`=0 AND py.`cust`='$customer_id' AND py.bill_pay=1 AND date(bm.billed_timestamp)< '$fromdate'";
		$query = "SELECT SUM(py.amount)
		FROM payment py
		JOIN bill_main bm ON py.invoice_no = bm.invoice_no
		WHERE py.`status` = 0 AND py.`cust` = '$customer_id' AND py.bill_pay = 1 AND DATE(bm.billed_timestamp) < '$fromdate'";
		$row = mysqli_fetch_row(mysqli_query($conn2, $query));
		$payment_upto0 = $row[0];

		// Legecy code before change 16_02_2024
		// $query = "SELECT SUM(py.amount) FROM payment py WHERE py.`status`=0 AND py.`cust`='$customer_id' AND py.bill_pay=2 AND date(py.payment_date) < '$fromdate'";
		$query = "SELECT SUM(py.amount)
		FROM payment py
		WHERE py.`status` = 0 AND py.`cust` = '$customer_id' AND py.bill_pay = 2 AND DATE(py.payment_date) < '$fromdate'";
		$row = mysqli_fetch_row(mysqli_query($conn2, $query));
		$payment_upto0 += $row[0];

		// Legecy code before change 16_02_2024
		// $query = "SELECT SUM(py.amount) FROM payment py WHERE py.`status`=0 AND py.chque_return=1 AND py.cust='$customer_id' AND date(py.chque_return_date) < '$fromdate'";
		$query = "SELECT SUM(py.amount)
		FROM payment py
		WHERE py.`status` = 0 AND py.chque_return = 1 AND py.cust = '$customer_id' AND DATE(py.chque_return_date) < '$fromdate'";
		$row = mysqli_fetch_row(mysqli_query($conn2, $query));
		$chqreturn_upto0 = $row[0];

		$credit_balance = $totalbill0 - $payment_upto0 + $chqreturn_upto0;
	}
}

function getUnvisited($sub_system)
{
	global $from_date, $to_date, $visited_cust_id, $visited_cust_name, $unvisited_cust_id, $unvisited_cust_name, $visited_cust_sm_id, $visited_cust_sm_name, $unvisited_cust_sm_id, $unvisited_cust_sm_name;
	$unvisited_cust_id = $visited_custs = array();
	if (isset($_POST['from_date']) && isset($_POST['to_date'])) {
		$from_date = $_POST['from_date'];
		$to_date = $_POST['to_date'];
	} else {
		if ($_GET['components'] == 'billing')
			$from_date = date("Y-m-d", time() - (60 * 60 * 24 * 14));
		else
			$from_date = date("Y-m-d", time() - (60 * 60 * 24 * 30));
		$to_date = dateNow();
	}

	if ($sub_system == 'all') {
		$sub_sys_bmqry = $sub_sys_pyqry = $sub_sys_rtqry = $sub_sys_cuqry = '';
	} else {
		$sub_sys_bmqry = "AND bm.`sub_system`='$sub_system'";
		$sub_sys_pyqry = "AND py.sub_system='$sub_system'";
		$sub_sys_rtqry = "AND rm.sub_system='$sub_system'";
		$sub_sys_cuqry = "AND cu.sub_system='$sub_system'";
	}
	if ($sub_system == 'all')
		$sub_system_qry = '';
	else
		$sub_system_qry = "WHERE `sub_system`='$sub_system'";
	include('config.php');
	$query = "SELECT bm.`cust` FROM bill_main bm WHERE bm.`status` NOT IN (0,7) $sub_sys_bmqry AND date(bm.billed_timestamp) BETWEEN '$from_date' AND '$to_date' GROUP BY bm.`cust`";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$visited_custs[] = $row[0];
	}
	$query = "SELECT py.`cust` FROM payment py WHERE py.`status`=0 $sub_sys_pyqry AND date(py.payment_date) BETWEEN '$from_date' AND '$to_date' GROUP BY py.`cust`";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$key = array_search($row[0], $visited_custs);
		if (!($key > -1)) {
			$visited_custs[] = $row[0];
		}
	}
	$query = "SELECT rm.`cust` FROM return_main rm, `return` rt WHERE rm.invoice_no=rt.invoice_no AND date(rm.return_date) BETWEEN '$from_date' AND '$to_date' $sub_sys_rtqry GROUP BY rm.`cust`";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$key = array_search($row[0], $visited_custs);
		if (!($key > -1)) {
			$visited_custs[] = $row[0];
		}
	}

	$query = "SELECT cu.id,cu.name,up.id,up.username FROM cust cu, userprofile up WHERE cu.`associated_salesman`=up.id AND cu.`status`=1 $sub_sys_cuqry";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$key = array_search($row[0], $visited_custs);
		if ($key > -1) {
			$visited_cust_id[] = $row[0];
			$visited_cust_name[] = $row[1];
			$visited_cust_sm_id[] = $row[2];
			$visited_cust_sm_name[] = $row[3];
		} else {
			$unvisited_cust_id[] = $row[0];
			$unvisited_cust_name[] = $row[1];
			$unvisited_cust_sm_id[] = $row[2];
			$unvisited_cust_sm_name[] = $row[3];
		}
	}
}

function checkPaymentCorrelate()
{
	$inv_no = $_GET['inv_no'];
	include('config.php');
	$query = "SELECT `invoice_+total`+`invoice_-total` FROM bill_main WHERE invoice_no='$inv_no'";
	$result = mysqli_query($conn2, $query);
	$row = mysqli_fetch_row($result);
	$inv_total = $row[0];
	$query = "SELECT SUM(amount) FROM payment WHERE chque_return=0 AND `status`=0 AND invoice_no='$inv_no'";
	$result = mysqli_query($conn2, $query);
	$row = mysqli_fetch_row($result);
	$pay_total = $row[0];
	if ($inv_total == $pay_total)
		return 'yes';
	else
		return 'no';
}

//-----------------------------------------Chque_Return-------------------------------//

function getChqueNo($sub_system, $postpone)
{
	global $py_chqnofull;
	$backdate = date("Y-m-d", time() - 3 * 30 * 24 * 60 * 60);     //-----Chques diposits during last 90 days
	$today = date("Y-m-d", time());
	$py_chqnofull = array();
	if ($sub_system == 'all') {
		$sub_system_qry = "";
	} else {
		$sub_system_qry = "AND py.`sub_system`='$sub_system'";
	}
	if ($postpone == 1) {
		$postpone_ary = "OR py.chque_postpone='1')";
	} else {
		$postpone_ary = ")";
	}
	include('config.php');
	$query = "SELECT py.chque_no,bk.bank_code,py.chque_branch FROM payment py, bank bk WHERE py.chque_bank=bk.id AND py.chque_no IS NOT NULL AND py.chque_return=0 AND py.`status`='0' $sub_system_qry AND ((chque_date BETWEEN '$backdate' AND  '$today') $postpone_ary";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$py_chqnofull[] = $row[0] . '-' . str_pad($row[1], 4, "0", STR_PAD_LEFT) . '-' . str_pad($row[2], 3, "0", STR_PAD_LEFT);
	}
}

function getReturnedChque($sub_system)
{
	global $chq0_id, $chq0_invno, $chq0_cuname, $chq0_amount, $chq0_no, $chq0_bank, $chq0_branch, $chq0_date, $chq0_paymentdate, $chq0_salesman, $chq0_returndate, $chq0_rtn_clear,
	$chq0_rtn_cle_date, $chq0_code, $salesman_filter;
	if ($sub_system == 'all') {
		$sub_system_qry = "";
	} else {
		$sub_system_qry = "AND py.`sub_system`='$sub_system'";
	}
	$salesman_filter = $chq0_salesman = $chq0_id = array();
	include('config.php');

	$query1 = "SELECT py.id,py.invoice_no,cu.name,py.amount,py.chque_no,ba.name,ba.bank_code,py.chque_branch,py.chque_date,py.payment_date,up.username,date(py.chque_return_date),py.chque_rtn_clear,date(py.chque_rtn_cle_date)
	FROM payment py, bank ba, userprofile up, cust cu
	WHERE py.cust=cu.id AND py.salesman=up.id AND py.chque_bank=ba.id AND py.`status`=0 AND py.chque_return=1 AND py.chque_rtn_clear IN (0,1) $sub_system_qry
	ORDER BY py.chque_rtn_clear,py.chque_return_date DESC";
	$result1 = mysqli_query($conn2, $query1);
	while ($row1 = mysqli_fetch_array($result1)) {
		$chq0_id[] = $row1[0];
		if ($row1[1] != 0)
			$chq0_invno[] = str_pad($row1[1], 7, "0", STR_PAD_LEFT);
		else
			$chq0_invno[] = '';
		$chq0_cuname[] = $row1[2];
		$chq0_amount[] = $row1[3];
		$chq0_no[] = $row1[4];
		$chq0_bank[] = $row1[5];
		$chq0_branch[] = str_pad($row1[7], 3, "0", STR_PAD_LEFT);
		$chq0_date[] = $row1[8];
		$chq0_paymentdate[] = $row1[9];
		$chq0_salesman[] = $row1[10];
		$chq0_returndate[] = $row1[11];
		if ($row1[12] == 0)
			$chq0_rtn_clear[] = 'Pending';
		else
			$chq0_rtn_clear[] = 'Cleared';
		$chq0_rtn_cle_date[] = $row1[13];
		$chq0_code[] = $row1[4] . '-' . str_pad($row1[6], 4, "0", STR_PAD_LEFT) . '-' . str_pad($row1[7], 3, "0", STR_PAD_LEFT);
	}
	$salesman_filter = array_unique($chq0_salesman);
	$salesman_filter = array_values($salesman_filter);
}

// updated by nirmal 24_12_2024 (added cheque clear variable)
// update by nirmal 27_12_2024 (get cheque deposited bank)
function getChqueOne()
{
	global $chq_no, $chq_id, $chq_invno, $chq_cuname, $chq_amount, $chq_bank, $chq_bank_code, $chq_branch, $chq_date, $chq_paymentdate, $chq_salesman, $chq_return,
	$chq_postpone, $chq_date2, $chq_clear, $chq_deposited_bank;
	$chq_salesman = array();
	include('config.php');

	if (isset($_REQUEST['chque_no'])) {
		$py_chqnofull = $_REQUEST['chque_no'];
		$dash1 = stripos($py_chqnofull, '-');
		$dash2 = strripos($py_chqnofull, '-');
		$dash3 = strlen($py_chqnofull);
		$py_chqno = substr($py_chqnofull, 0, $dash1);
		$py_chqbank = substr($py_chqnofull, $dash1 + 1, $dash2 - $dash1 - 1);
		$py_branch = substr($py_chqnofull, $dash2 + 1, $dash3 - $dash2 - 1);
		$chq_no = $py_chqno;

		// $query = "SELECT py.id,py.invoice_no,cu.name,py.amount,ba.name,ba.bank_code,py.chque_branch,py.chque_date,py.payment_date,up.username,py.chque_return,py.chque_postpone,
		// py.chque_date2,py.chque_clear,baa.name
		// FROM payment py, bank ba, userprofile up, cust cu, bank baa
		// WHERE py.cust=cu.id AND py.salesman=up.id AND py.chque_bank=ba.id AND py.chque_deposit_bank=baa.id AND py.`status`=0 AND py.chque_no='$py_chqno' AND ba.bank_code='$py_chqbank' AND py.chque_branch='$py_branch'";
		$query = "SELECT py.id, py.invoice_no,cu.name,py.amount,ba.name AS bank_name,ba.bank_code,py.chque_branch,py.chque_date,py.payment_date,up.username,py.chque_return,py.chque_postpone,
		py.chque_date2,py.chque_clear,a.name AS deposit_bank_name
		FROM payment py JOIN cust cu ON py.cust = cu.id
		JOIN userprofile up ON py.salesman = up.id
		JOIN bank ba ON py.chque_bank = ba.id
		LEFT JOIN accounts a ON py.chque_deposit_bank = a.id
		WHERE py.status = 0 AND py.chque_no = '$py_chqno' AND ba.bank_code = '$py_chqbank' AND py.chque_branch = '$py_branch'";

		$result = mysqli_query($conn, $query);
		while ($row = mysqli_fetch_array($result)) {
			$chq_id = $row[0];
			if ($row[1] != 0)
				$chq_invno[] = str_pad($row[1], 7, "0", STR_PAD_LEFT);
			else
				$chq_invno[] = '';
			$chq_cuname[] = $row[2];
			$chq_amount[] = $row[3];
			$chq_bank = $row[4];
			$chq_bank_code = str_pad($row[5], 4, "0", STR_PAD_LEFT);
			$chq_branch = str_pad($row[6], 3, "0", STR_PAD_LEFT);
			$chq_paymentdate = $row[8];
			$chq_salesman[] = $row[9];
			$chq_return = $row[10];
			$chq_postpone = $row[11];
			if (($chq_postpone == 1) && ($_GET['action'] == 'chque_postpone')) {
				$chq_date = $row[12];
				$chq_date2 = $row[7];
			} else {
				$chq_date = $row[7];
				$chq_date2 = $row[12];
			}
			$chq_clear = $row[13];
			$chq_deposited_bank = $row[14];
		}
	}
}

// update by nirmal 29_02_2024

function setChqueStatus($status)
{
	global $message;
	$id = $_REQUEST['id'];
	$user_id = $_COOKIE['user_id'];
	$time_now = timeNow();
	$postpone_qry = $qb_msg = $qb_result = $reason = $salesman = $qb_cust_id = '';
	include('config.php');
	$out = true;

	if ($out) {
		if (isQuickBooksActive(1)) {
			$query = "SELECT cust FROM payment WHERE `id`='$id'";
			$row = mysqli_fetch_row(mysqli_query($conn2, $query));
			$cust = $row[0];

			$query1 = "SELECT qb_cust_id FROM cust WHERE `id`='$cust'";
			$row1 = mysqli_fetch_row(mysqli_query($conn2, $query1));
			$qb_cust_id = $row1[0];

			if ($qb_cust_id == '') {
				$message = 'Error : This user is not registered in QuickBooks';
				$out = false;
			}
		}
	}

	if ($out) {
		if (isSalesmanPaymentDepositActive()) {
			$reason = $_REQUEST['reason'];
			if (!in_array($reason, [7, 8, 9])) {
				$message = 'Error: This reason is not in range';
				$out = false;
			}
			$query = "SELECT salesman FROM payment WHERE `id`='$id'";
			$row = mysqli_fetch_row(mysqli_query($conn2, $query));
			$salesman = $row[0];
			if ($salesman == '') {
				$message = 'Error : Salesman not found in this payment';
				$out = false;
			}
		}
	}

	if ($out) {
		if ($status == 0) {
			$status2 = 2;
			$message = 'The chque was removed from returned chques';
		}
	}
	if ($out) {
		if ($status == 1) {
			$status2 = 4;
			$message = 'The chque was marked as returned';
		}
	}
	if ($out) {
		if ($status == 1) {
			$query = "SELECT chque_postpone,chque_date,chque_date2 FROM payment WHERE id='$id'";
			$row = mysqli_fetch_row(mysqli_query($conn2, $query));
			$chque_postpone = $row[0];
			$old_chque_date = $row[1];
			$old_chque_date2 = $row[2];
			if ($chque_postpone == 1)
				$postpone_qry = ",`chque_postpone`='0',`chque_date`='$old_chque_date2',`chque_date2`='$old_chque_date'";
			if ($chque_postpone == 2)
				$postpone_qry = ",`chque_postpone`='0'";
		}
	}

	if ($out) {
		$query = "SELECT chque_no,chque_bank,chque_branch FROM payment WHERE `id`='$id'";
		$row = mysqli_fetch_row(mysqli_query($conn2, $query));
		$chque_no = $row[0];
		$chque_bank = $row[1];
		$chque_branch = $row[2];
	}

	if ($out) {
		try {
			// Start transaction
			if (!mysqli_begin_transaction($conn)) {
				$out = false;
				throw new Exception("Could not begin transaction: " . mysqli_error($conn));
			}
			if ($out) {
				$query1 = "UPDATE `payment` SET `chque_return`='$status',`chque_return_date`='$time_now' $postpone_qry WHERE `chque_no`='$chque_no' AND `chque_bank`='$chque_bank' AND `chque_branch`='$chque_branch'";
				if (!mysqli_query($conn, $query1)) {
					$out = false;
					throw new Exception("Failed to update payment: " . mysqli_error($conn));
				}
				$query2 = "UPDATE `payment_subsys` SET `status`='$status2',`chque_return_date`='$time_now' WHERE `chque_no`='$chque_no' AND `chque_bank`='$chque_bank' AND `chque_branch`='$chque_branch'";
				if (!mysqli_query($conn, $query2)) {
					$out = false;
					throw new Exception("Failed to update payment_subsys: " . mysqli_error($conn));
				}
			}
			if ($out) {
				if (isSalesmanPaymentDepositActive()) {
					try {
						// Insert new cheque transaction
						$query3 = "INSERT INTO cheque_trans (`from`, `to`, `payment_id`, `time`, `status`, `latest`)
						VALUES ('$user_id', '$salesman', '$id', '$time_now', '$reason', 1)";
						if (!($result3 = mysqli_query($conn, $query3))) {
							$out = false;
							throw new Exception("Failed to insert cheque transaction: " . mysqli_error($conn));
						}

						$lastid_temp = mysqli_insert_id($conn);
						if (empty($lastid_temp)) {
							$out = false;
							throw new Exception("Failed to get last inserted ID.");
						}

						// Update previous transactions
						$query4 = "UPDATE cheque_trans SET `latest` = NULL WHERE `payment_id` = '$id' AND `id` != '$lastid_temp'";
						if (!($result4 = mysqli_query($conn, $query4))) {
							$out = false;
							throw new Exception("Failed to update previous cheque transactions: " . mysqli_error($conn));
						}
					} catch (Exception $e) {
						throw new Exception($e->getMessage());
					}
				}
				if (isQuickBooksActive(1)) {
					if ($status == 1) {
						$paymentQuery = "SELECT py.invoice_no, py.amount, c.name, py.qb_id, py.chque_deposit_bank FROM payment py, cust c
								WHERE c.id = py.cust AND py.id = '$id'";
						$paymentRow = mysqli_fetch_row(mysqli_query($conn2, $paymentQuery));

						if ($paymentRow) {
							if ($paymentRow[3] != '') {  // Assuming qb_id
								if ($paymentRow[4] != '') {  // Assuming chque_deposit_bank
									if ($reason == 7) { // bank return
										$batch_id = 'batch_' . substr(md5(microtime() . mt_rand()), 0, 16); // 16 characters
										$accountQuery = "SELECT `name` FROM `accounts` WHERE `id`='$paymentRow[4]'";
										$accountResult = mysqli_query($conn, $accountQuery);
										$accountRow = mysqli_fetch_assoc($accountResult);
										$bank_name = $accountRow['name'];

										$invoice_no = $paymentRow[0];
										$amount = $paymentRow[1];
										$custName = $paymentRow[2];
										$cheque_no = $chque_no . '-' . $chque_bank . '-' . str_pad($chque_branch, 3, "0", STR_PAD_LEFT);
										$debitAccountName = "Accounts Receivable (A/R)";
										$creditAccountName = $bank_name;
										$description = "[PAYMENT] - CHEQUE RETURN:BANK, Method: Cheque Payment ($bank_name), Cheque Number $cheque_no";
										if ($invoice_no != 0) {
											$description .= ", Invoice No: $invoice_no";
										}
										$description .= ", Customer : $custName";
										$debitEntityType = "Customer";
										$debitEntityID = $qb_cust_id;
										$creditEntityType = "";
										$creditEntityID = "";

										$journalEntryForCheque = buildJournalEntry($conn2, $amount, $debitAccountName, $creditAccountName, $description, $debitEntityType, $debitEntityID, $creditEntityType, $creditEntityID);
										if (isset($journalEntryForCheque['error'])) {
											$qb_msg = $journalEntryForCheque['error'];
											throw new Exception("QuickBooks error: " . $qb_msg);
										} else {
											$action_name = 'return_cheque_insert';
											foreach ($journalEntryForCheque as $entry) {
												$posting_type = mysqli_real_escape_string($conn, $entry['posting_type']);
												$account_id = mysqli_real_escape_string($conn, $entry['account_id']);
												$account_name = mysqli_real_escape_string($conn, $entry['account_name']);
												$amount = mysqli_real_escape_string($conn, $entry['amount']);
												$description = mysqli_real_escape_string($conn, $entry['description']);
												$entity_type = isset($entry['entity_type']) ? mysqli_real_escape_string($conn, $entry['entity_type']) : null;
												$entity_id = isset($entry['entity_id']) ? mysqli_real_escape_string($conn, $entry['entity_id']) : null;

												$query = "INSERT INTO qb_queue (`batch_id`, `action`, `payment_id`, `posting_type`, `account_id`, `account_name`, `amount`, `description`, `entity_type`, `entity_id`)
														VALUES ('$batch_id','$action_name', '$id', '$posting_type', '$account_id', '$account_name', '$amount', '$description',
															" . ($entity_type !== null ? "'$entity_type'" : "NULL") . ",
															" . ($entity_id !== null ? "'$entity_id'" : "NULL") . ")";
												$result = mysqli_query($conn, $query);
												if (!$result) {
													$message = "MySQL Error while inserting into qb_queue: " . mysqli_error($conn);
													throw new Exception($message);
												}
											}
										}
									}
									if ($reason == 8 || $reason == 9) { // 8 = Cash Receive, 9 = Issue New Cheque
										$batch_id = 'batch_' . substr(md5(microtime() . mt_rand()), 0, 16); // 16 characters
										$accountQuery = "SELECT `name` FROM `accounts` WHERE `id`='$paymentRow[4]'";
										$accountResult = mysqli_query($conn, $accountQuery);
										$accountRow = mysqli_fetch_assoc($accountResult);
										$bank_name = $accountRow['name'];

										$invoice_no = $paymentRow[0];
										$amount = $paymentRow[1];
										$custName = $paymentRow[2];
										$cheque_no = $chque_no . '-' . $chque_bank . '-' . str_pad($chque_branch, 3, "0", STR_PAD_LEFT);
										$debitAccountName = "Accounts Receivable (A/R)";
										$creditAccountName = "Undeposited Funds";
										$description = "[PAYMENT] - CHEQUE RETURN:OTHER, Method: Cheque Payment ($bank_name), Cheque Number $cheque_no";
										if ($invoice_no != 0) {
											$description .= ", Invoice No: $invoice_no";
										}
										$description .= ", Customer : $custName";
										$debitEntityType = "Customer";
										$debitEntityID = $qb_cust_id;
										$creditEntityType = "";
										$creditEntityID = "";

										$journalEntryForCheque = buildJournalEntry($conn2, $amount, $debitAccountName, $creditAccountName, $description, $debitEntityType, $debitEntityID, $creditEntityType, $creditEntityID);
										if (isset($journalEntryForCheque['error'])) {
											$qb_msg = $journalEntryForCheque['error'];
											throw new Exception("QuickBooks error: " . $qb_msg);
										} else {
											$action_name = 'return_cheque_insert';
											foreach ($journalEntryForCheque as $entry) {
												$posting_type = mysqli_real_escape_string($conn, $entry['posting_type']);
												$account_id = mysqli_real_escape_string($conn, $entry['account_id']);
												$account_name = mysqli_real_escape_string($conn, $entry['account_name']);
												$amount = mysqli_real_escape_string($conn, $entry['amount']);
												$description = mysqli_real_escape_string($conn, $entry['description']);
												$entity_type = isset($entry['entity_type']) ? mysqli_real_escape_string($conn, $entry['entity_type']) : null;
												$entity_id = isset($entry['entity_id']) ? mysqli_real_escape_string($conn, $entry['entity_id']) : null;

												$query = "INSERT INTO qb_queue (`batch_id`, `action`, `payment_id`, `posting_type`, `account_id`, `account_name`, `amount`, `description`, `entity_type`, `entity_id`)
														VALUES ('$batch_id','$action_name', '$id', '$posting_type', '$account_id', '$account_name', '$amount', '$description',
															" . ($entity_type !== null ? "'$entity_type'" : "NULL") . ",
															" . ($entity_id !== null ? "'$entity_id'" : "NULL") . ")";

												if (!mysqli_query($conn, $query)) {
													$message = "MySQL Error while inserting into qb_queue: " . mysqli_error($conn);
													throw new Exception($message);
												}
											}
										}
									}
								} else {
									$qb_msg = "QuickBooks error: This payment isn't cleared yet to return.";
									throw new Exception($qb_msg);
								}
							} else {
								$qb_msg = "QuickBooks error: This payment isn't recorded in QuickBooks.";
								throw new Exception($qb_msg);
							}
						}
					}
				}
			}

			// Commit transaction
			mysqli_commit($conn);
			$message = "Cheque status updated successfully.";
		} catch (Exception $e) {
			// Roll back transaction on failure
			mysqli_rollback($conn);
			$message = "Transaction failed: " . $e->getMessage();
			error_log("Error in setChqueStatus(): " . $message); // Log the error
			$out = false;
		}
	}
	$message = $message . '. ' . $qb_msg;
	return $out;
}

// old
/*
function setChqueStatus($status){
	global $message;
	$id = $_REQUEST['id'];
	$time_now = timeNow();
	$postpone_qry = $qb_msg = '';
	$out=true;
	if ($status == 0) {
		$status2 = 2;
		$msg = 'The chque was removed from returned chques';
	}
	if ($status == 1) {
		$status2 = 4;
		$msg = 'The chque was marked as returned';
	}
	include('config.php');
	if ($status == 1) {
		$query = "SELECT chque_postpone,chque_date,chque_date2 FROM payment WHERE id='$id'";
		$row = mysqli_fetch_row(mysqli_query($conn2, $query));
		$chque_postpone = $row[0];
		$old_chque_date = $row[1];
		$old_chque_date2 = $row[2];
		if ($chque_postpone == 1) $postpone_qry = ",`chque_postpone`='0',`chque_date`='$old_chque_date2',`chque_date2`='$old_chque_date'";
		if ($chque_postpone == 2) $postpone_qry = ",`chque_postpone`='0'";
	}

	$query = "SELECT chque_no,chque_bank,chque_branch FROM payment WHERE `id`='$id'";
	$row = mysqli_fetch_row(mysqli_query($conn2, $query));
	$chque_no = $row[0];
	$chque_bank = $row[1];
	$chque_branch = $row[2];

	$query1 = "UPDATE `payment` SET `chque_return`='$status',`chque_return_date`='$time_now' $postpone_qry WHERE `chque_no`='$chque_no' AND `chque_bank`='$chque_bank' AND `chque_branch`='$chque_branch'";
	$result1 = mysqli_query($conn, $query1);
	$query2 = "UPDATE `payment_subsys` SET `status`='$status2',`chque_return_date`='$time_now' WHERE `chque_no`='$chque_no' AND `chque_bank`='$chque_bank' AND `chque_branch`='$chque_branch'";
	$result2 = mysqli_query($conn, $query2);
	$message = $msg;
	if (!$result1) {
		$out=false;
	}
	$message = $message.'. '.$qb_msg;
	return $out;
}
*/
function setChqRtnSts($status)
{
	global $message;
	$id = $_REQUEST['id'];
	$time_now = timeNow();
	include('config.php');
	if ($status == 0) {
		$query = "UPDATE `payment` SET `chque_rtn_clear`='0' WHERE `id`='$id'";
		$message = 'The Return Chque was Marked as Pending';
	}
	if ($status == 2) {
		$query = "UPDATE `payment` SET `chque_rtn_clear`='2' WHERE `id`='$id'";
		$message = 'The Return Chque was Deleted';
	}
	$result = mysqli_query($conn, $query);

	if ($result) {
		return true;
	} else {
		$message = 'Status of the Return Chque Could Not be Updated';
		return false;
	}
}

//-----------------------------------------Postpone-------------------------------//
function getPostponedChque($sub_system)
{
	global $chq0_id, $chq0_invno, $chq0_cuname, $chq0_amount, $chq0_no, $chq0_bank, $chq0_branch, $chq0_postponed_date, $chq0_paymentdate, $chq0_salesman, $chq0_date, $chq0_postpone, $chq0_code, $salesman_filter;
	if ($sub_system == 'all') {
		$sub_system_qry = "";
	} else {
		$sub_system_qry = "AND py.`sub_system`='$sub_system'";
	}
	$chq0_salesman = $salesman_filter = $chq0_id = array();
	include('config.php');
	$query1 = "SELECT py.id,py.invoice_no,cu.name,py.amount,py.chque_no,ba.name,ba.bank_code,py.chque_branch,py.chque_date,py.payment_date,up.username,date(py.chque_date2),py.chque_postpone FROM payment py, bank ba, userprofile up, cust cu WHERE py.cust=cu.id AND py.salesman=up.id AND py.chque_bank=ba.id AND py.`status`=0 AND py.chque_postpone IN (1,2) $sub_system_qry ORDER BY py.chque_postpone,py.chque_date,py.chque_date2 DESC";
	$result1 = mysqli_query($conn, $query1);
	while ($row1 = mysqli_fetch_array($result1)) {
		$chq0_id[] = $row1[0];
		if ($row1[1] != 0)
			$chq0_invno[] = str_pad($row1[1], 7, "0", STR_PAD_LEFT);
		else
			$chq0_invno[] = '';
		$chq0_cuname[] = $row1[2];
		$chq0_amount[] = $row1[3];
		$chq0_no[] = $row1[4];
		$chq0_bank[] = $row1[5];
		$chq0_branch[] = $row1[7];
		$chq0_postponed_date[] = $row1[8];
		$chq0_paymentdate[] = $row1[9];
		$chq0_salesman[] = $row1[10];
		$chq0_date[] = $row1[11];
		if ($row1[12] == 1)
			$chq0_postpone[] = 'Postponed';
		if ($row1[12] == 2)
			$chq0_postpone[] = 'Postpone-Clear';
		$chq0_code[] = $row1[4] . '-' . str_pad($row1[6], 4, "0", STR_PAD_LEFT) . '-' . str_pad($row1[7], 3, "0", STR_PAD_LEFT);
	}
	$salesman_filter = array_unique($chq0_salesman);
	$salesman_filter = array_values($salesman_filter);
}

function setChquePostpone($case)
{
	global $py_chqnofull, $message;
	$postpone_date = $_POST['postpone_date'];
	$master_pw3 = $_POST['master_pw3'];
	$out = true;
	$py_chqnofull = $_REQUEST['chque_no'];
	$dash1 = stripos($py_chqnofull, '-');
	$dash2 = strripos($py_chqnofull, '-');
	$dash3 = strlen($py_chqnofull);
	$py_chqno = substr($py_chqnofull, 0, $dash1);
	$py_chqbank = substr($py_chqnofull, $dash1 + 1, $dash2 - $dash1 - 1);
	$py_branch = substr($py_chqnofull, $dash2 + 1, $dash3 - $dash2 - 1);
	if ($master_pw3 == '') {
		$message = 'Please fill Date and Password';
		$out = false;
	}

	include('config.php');
	if ($out) {
		$query = "SELECT `value` FROM settings WHERE setting='master_pw3'";
		$row = mysqli_fetch_row(mysqli_query($conn, $query));
		if ($row[0] != $master_pw3) {
			$message = 'Invalid Master Password';
			$out = false;
		}
	}
	if ($out) {
		$query = "SELECT COUNT(py.id) FROM payment py, bank bk WHERE py.chque_bank=bk.id AND py.chque_no='$py_chqno' AND bk.bank_code='$py_chqbank' AND py.chque_branch='$py_branch'";
		$row = mysqli_fetch_row(mysqli_query($conn, $query));
		if ($row[0] == 0) {
			$message = 'No Cheque Found. Please contact support';
			$out = false;
		}
	}
	if ($out) {
		$query = "SELECT py.chque_postpone, py.chque_date, py.chque_date2 FROM payment py, bank bk WHERE py.chque_bank=bk.id AND py.chque_no='$py_chqno' AND bk.bank_code='$py_chqbank' AND py.chque_branch='$py_branch'";
		$row = mysqli_fetch_row(mysqli_query($conn, $query));
		$chque_postpone = $row[0];
		$old_chque_date = $row[1];
		$old_chque_date2 = $row[2];

		if ($case == 'add') {
			$query = "UPDATE `payment` py, bank bk SET py.`chque_date2`='$old_chque_date',py.`chque_postpone`='1',py.`chque_date`='$postpone_date' WHERE py.chque_bank=bk.id AND py.chque_no='$py_chqno' AND bk.bank_code='$py_chqbank' AND py.chque_branch='$py_branch'";
			$result = mysqli_query($conn, $query);
			if ($result) {
				$message = 'Postpone Date was Added';
			} else {
				$message = 'Error: Cannot Add the Postpone Date';
				$out = false;
			}
		} elseif ($case == 'edit') {
			$query = "UPDATE `payment` py, bank bk SET py.`chque_date`='$postpone_date' WHERE py.chque_bank=bk.id AND py.chque_no='$py_chqno' AND bk.bank_code='$py_chqbank' AND py.chque_branch='$py_branch'";
			$result = mysqli_query($conn, $query);
			if ($result) {
				$message = 'Postpone Date was Modified';
			} else {
				$message = 'Error: Cannot Modify the Postpone Date';
				$out = false;
			}
		} elseif ($case == 'remove') {
			$query = "UPDATE `payment` py, bank bk SET py.`chque_date`='$old_chque_date2',py.`chque_postpone`='2',py.`chque_date2`='$old_chque_date' WHERE py.chque_bank=bk.id AND py.chque_no='$py_chqno' AND bk.bank_code='$py_chqbank' AND py.chque_branch='$py_branch'";
			$result = mysqli_query($conn, $query);
			if ($result) {
				$message = 'Postpone was Cleared';
			} else {
				$message = 'Error: Cannot Clear the Postpone Status';
				$out = false;
			}
		} else {
			$message = 'Error: Invalid Input';
			$out = false;
		}
	}
	return $out;
}

function moveToPostpone()
{
	global $message;
	$id = $_GET['id'];
	$out = true;

	include('config.php');
	if ($out) {
		$query = "SELECT chque_postpone,chque_date,chque_date2 FROM payment WHERE id='$id'";
		$row = mysqli_fetch_row(mysqli_query($conn, $query));
		$chque_postpone = $row[0];
		$old_chque_date = $row[1];
		$old_chque_date2 = $row[2];
		if ($chque_postpone != 2) {
			$out = false;
			$message = 'Error: Unauthorized Request';
		}
	}
	if ($out) {
		$query = "UPDATE `payment` SET `chque_date`='$old_chque_date2',`chque_postpone`='1',`chque_date2`='$old_chque_date' WHERE id='$id'";
		$result = mysqli_query($conn, $query);
		if ($result) {
			$message = 'Cheque was moved to Postpone';
		} else {
			$message = 'Error: Cannot Change Cheque Status';
			$out = false;
		}
	}
	return $out;
}

function fullClearPostpone()
{
	global $message;
	$id = $_GET['id'];
	$out = true;

	include('config.php');
	if ($out) {
		$query = "SELECT chque_postpone FROM payment WHERE id='$id'";
		$row = mysqli_fetch_row(mysqli_query($conn, $query));
		$chque_postpone = $row[0];
		if ($chque_postpone != 2) {
			$out = false;
			$message = 'Error: Unauthorized Request';
		}
	}
	if ($out) {
		$query = "UPDATE `payment` SET `chque_postpone`='3' WHERE id='$id'";
		$result = mysqli_query($conn, $query);
		if ($result) {
			$message = 'Record was Cleared';
		} else {
			$message = 'Error: Cannot Change Cheque Status';
			$out = false;
		}
	}
	return $out;
}

//-----------------------------------------Return-------------------------------//
function getReturnSummary()
{
	global $rtn_inv, $rtn_date, $rtn_by, $rtn_cust, $from_date, $to_date, $cust;
	$store = $_COOKIE['store'];
	$sm = $_GET['sm'];
	$cu = $_GET['cu'];
	$cust = $sm_qry = $cu_qry = '';
	$rtn_inv = array();
	$today = date("Y-m-d", time());
	if (isset($_GET['from_date']))
		$from_date = $_GET['from_date'];
	else
		$from_date = date("Y-m-d", time() - (60 * 60 * 24 * 30));
	if (isset($_GET['to_date']))
		$to_date = $_GET['to_date'];
	else
		$to_date = date("Y-m-d", time());
	if ($sm != 'all')
		$sm_qry = "AND rm.return_by='$sm'";
	if ($cu != '')
		$sm_qry = "AND rm.cust='$cu'";
	include('config.php');
	$query = "SELECT DISTINCT rm.invoice_no,date(rm.return_date),up.username,cu.name FROM return_main rm, cust cu, userprofile up WHERE rm.cust=cu.id AND rm.return_by=up.id AND rm.store='$store' AND date(rm.return_date) BETWEEN '$from_date' AND '$to_date' $sm_qry $cu_qry ORDER BY rm.invoice_no DESC";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$rtn_inv[] = $row[0];
		$rtn_date[] = $row[1];
		$rtn_by[] = $row[2];
		$rtn_cust[] = $row[3];
		if ($cu != '')
			$cust = $row[3];
	}
}

function getReturn($display)
{
	global $rtn_inv, $dis_id, $rtn_date, $rtn_by, $rtn_itm, $rtn_qty, $rtn_cust, $dis_date, $rtn_st, $dis_by, $from_date, $to_date, $cust;
	$store = $_COOKIE['store'];
	$sm = $_GET['sm'];
	$cu = $_GET['cu'];
	$cust = $packed_qry = $sm_qry = $cu_qry = '';
	$dis_id = array();
	$today = date("Y-m-d", time());
	if ($display == 'unpacked')
		$packed_qry = "AND rt.odr_packed='0'";
	if (isset($_GET['from_date']))
		$from_date = $_GET['from_date'];
	else
		$from_date = date("Y-m-d", time() - (60 * 60 * 24 * 30));
	if (isset($_GET['to_date']))
		$to_date = $_GET['to_date'];
	else
		$to_date = date("Y-m-d", time());
	if ($sm != 'all')
		$sm_qry = "AND rm.return_by='$sm'";
	if ($cu != '')
		$sm_qry = "AND rm.cust='$cu'";
	include('config.php');
	$query = "SELECT id,username FROM userprofile";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$userarr[$row[0]] = $row[1];
	}

	$query = "SELECT DISTINCT rm.invoice_no,rt.disposal_id,date(rm.return_date),rm.return_by,itm.description,rt.qty,cu.name,date(rt.process_date),rt.process_by,rt.`status` FROM return_main rm, `return` rt, inventory_items itm, cust cu WHERE rm.invoice_no=rt.invoice_no AND rt.return_item=itm.id AND rm.cust=cu.id AND rm.store='$store' AND date(rm.return_date) BETWEEN '$from_date' AND '$to_date' $packed_qry $sm_qry $cu_qry ORDER BY rm.invoice_no DESC";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$rtn_inv[] = $row[0];
		$dis_id[] = $row[1];
		$rtn_date[] = $row[2];
		$rtn_by[] = $userarr[$row[3]];
		$rtn_itm[] = $row[4];
		$rtn_qty[] = $row[5];
		$rtn_cust[] = $row[6];
		$dis_date[] = $row[7];
		if ($row[8] != '')
			$dis_by[] = $userarr[$row[8]];
		else
			$dis_by[] = '';
		if ($row[9] == 0)
			$rtn_st[] = 'Pending';
		else
			$rtn_st[] = 'Processed';
		if ($cu != '')
			$cust = $row[6];
	}
}

//-----------------------------------------Disposal-------------------------------//

function getDisposal()
{
	global $permission, $dis_id, $dis_description, $dis_qty, $dis_store, $dis_date, $rtn_inv, $rtn_qty, $from_date, $to_date;
	$store = $_COOKIE['store'];
	$today = date("Y-m-d", time());
	if (isset($_REQUEST['from_date']))
		$from_date = $_REQUEST['from_date'];
	else
		$from_date = date("Y-m-d", time() - (60 * 60 * 24 * 30));
	if (isset($_REQUEST['to_date']))
		$to_date = $_REQUEST['to_date'];
	else
		$to_date = date("Y-m-d", time());
	$k = 0;
	$rtn_inv = $rtn_qty = $dis_id = array();
	include('config.php');
	$query = "SELECT dis.id,itm.description,dis.qty,st.name,date(dis.`date`) FROM return_disposal dis, inventory_items itm, stores st WHERE dis.store=st.id AND dis.item=itm.id AND dis.`store`='$store' AND date(dis.`date`) BETWEEN '$from_date' AND '$to_date' ORDER BY dis.id DESC";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$dis_id[] = $row[0];
		$dis_id_tmp = $row[0];
		$dis_description[] = $row[1];
		$dis_qty[] = $row[2];
		$dis_store[] = $row[3];
		$dis_date[] = $row[4];
		if ($today == $row[4])
			$permission[] = true;
		else
			$permission[] = false;
		$query1 = "SELECT DISTINCT invoice_no,qty FROM `return` WHERE disposal_id='$dis_id_tmp'";
		$result1 = mysqli_query($conn2, $query1);
		while ($row1 = mysqli_fetch_array($result1)) {
			$rtn_inv[$k][] = $row1[0];
			$rtn_qty[$k][] = $row1[1];
		}
		$k++;
	}
}

function moveDisposal()
{
	global $message;
	$disposal_id = $_REQUEST['id'];
	$output = false;

	include('config.php');

	$result7 = mysqli_query($conn, "SELECT item,qty,store FROM return_disposal WHERE id='$disposal_id'");
	$row = mysqli_fetch_assoc($result7);
	$item = $row['item'];
	$qty = $row['qty'];
	$store = $row['store'];

	$result8 = mysqli_query($conn, "SELECT id,qty FROM inventory_qty WHERE location='$store' AND item='$item'");
	$row = mysqli_fetch_assoc($result8);
	$itq_id = $row['id'];
	$itq_qty = $row['qty'];

	$new_qty = $itq_qty + $qty;

	if ($result7 && $result8) {
		$query1 = "UPDATE `inventory_qty` SET `qty`='$new_qty' WHERE `id`='$itq_id'";
		$result1 = mysqli_query($conn, $query1);
	}
	if ($result1) {
		$query2 = "UPDATE `return` SET `status`='0' WHERE `disposal_id`='$disposal_id'";
		$result2 = mysqli_query($conn, $query2);
	}
	if ($result2) {
		$query3 = "DELETE FROM `return_disposal` WHERE `id` = '$disposal_id'";
		$result3 = mysqli_query($conn, $query3);
	}

	if ($result3) {
		$message = 'Item was Moved to Processing Successfully!';
		return true;
	} else {
		$message = 'Item Could Not be Moved !';
		return false;
	}
}

//----------------------------------------Devices---------------------------------//

function getDevices($sub_system)
{
	global $dev_id, $dev_name, $key_dev_name;
	$key_dev_name = '';
	$dev_id = array();
	if ($sub_system == 'all')
		$sub_system_qry = '';
	else
		$sub_system_qry = "AND `sub_system`='$sub_system'";
	include('config.php');
	$query = "SELECT id,name FROM devices WHERE `status`=1 $sub_system_qry";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$dev_id[] = $row[0];
		$dev_name[] = $row[1];
	}

	if (isset($_COOKIE['rsaid'])) {
		$key = $_COOKIE['rsaid'];
		$result = mysqli_query($conn2, "SELECT `name` FROM devices WHERE `key`='$key' $sub_system_qry");
		$row = mysqli_fetch_assoc($result);
		$key_dev_name = $row['name'];
	}
}

function registerDevice()
{
	global $message;
	$date1 = date_create(dateNow());
	include('config.php');
	$dev_id = $_REQUEST['device'];
	$result = mysqli_query($conn2, "SELECT `key`,`expiration` FROM devices WHERE id='$dev_id'");
	$row = mysqli_fetch_assoc($result);
	$dev_key = $row['key'];
	$date2 = date_create($row['expiration']);
	$diff0 = date_diff($date1, $date2);
	$diff = $diff0->format("%a");
	if (setcookie("rsaid", $dev_key, time() + 86400 * $diff)) {
		$message = 'Device was Registerd Successfully!';
		return true;
	} else {
		$message = 'Device could not be Registerd!';
		return false;
	}
}

function unregisterDevice()
{
	global $message;
	if (setcookie("rsaid", '', time() - 86400 * 30)) {
		$message = 'Device was Unregisterd Successfully!';
		return true;
	} else {
		$message = 'Device could not be Unregisterd!';
		return false;
	}
}

// updated by nirmal 2025_01_20 (changed store variable name to something else, this cause store variable in views)
function getUnlockedBills($sub_system)
{
	global $invoice_no, $billed_by, $billed_cust, $date, $time, $bill_store, $lock;

	if ($sub_system == 'all')
		$sub_system_qry = '';
	else
		$sub_system_qry = "AND bm.sub_system='$sub_system'";
	$invoice_no = array();
	include('config.php');

	$query = "SELECT DISTINCT bm.invoice_no,up.username,cu.name,DATE(bm.billed_timestamp),TIME(bm.billed_timestamp),st.name,bm.`lock`
	FROM bill bi ,bill_main bm, userprofile up, cust cu, stores st
	WHERE bi.invoice_no=bm.invoice_no AND bm.billed_by=up.id AND bm.`cust`=cu.id AND bm.store=st.id AND bm.`lock`!=1 AND bm.`status` NOT IN (0,7) $sub_system_qry
	ORDER BY st.name, bm.billed_timestamp";

	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$invoice_no[] = $row[0];
		$billed_by[] = $row[1];
		$billed_cust[] = $row[2];
		$date[] = $row[3];
		$time[] = $row[4];
		$bill_store[] = $row[5];
		$lock[] = $row[6];
	}
}

// added by nirmal 11_07_2023
// update by nirmal 03_05_2024 (added group clause to prevent duplicate bill no), 13_05_2024 (added salesman filter)
function getTemporaryBills($sub_system)
{
	global $invoice_no, $billed_by, $billed_cust, $date, $time, $stores, $lock;
	if ($sub_system == 'all')
		$sub_system_qry = '';
	else
		$sub_system_qry = "AND bm.`sub_system`='$sub_system'";
	$invoice_no = array();
	include('config.php');

	$salesmansearch = "";
	if (isset($_REQUEST['salesman'])) {
		if ($_REQUEST['salesman'] == 'all')
			$salesmansearch = '';
		else
			$salesmansearch = "AND up.`id`='" . $_REQUEST['salesman'] . "'";
	}

	$query = "SELECT DISTINCT bm.`bm_no`,up.`username`,cu.`name`,DATE(bi.`date`),TIME(bi.`date`),st.`name`
	FROM bill_tmp bi ,bill_main_tmp bm, userprofile up, cust cu, stores st
	WHERE bi.`bm_no`=bm.`bm_no` AND bm.`billed_by`=up.`id` AND bm.`cust`=cu.`id` AND bm.`store`=st.`id` AND bm.`status` NOT IN (0,7) $sub_system_qry $salesmansearch GROUP BY bm.`bm_no` ORDER BY st.`name`, bi.`date` DESC";

	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$invoice_no[] = $row[0];
		$billed_by[] = $row[1];
		$billed_cust[] = $row[2];
		$date[] = $row[3];
		$time[] = $row[4];
		$stores[] = $row[5];
	}
}
// updated by nirmal 17_06_2024 (added new salesman filter query)
// update by nirmal 16_07_2024 (added new filter for item group by sold price)
function getSalesReport2($sub_system)
{
	global $item_des, $item_qty, $selection, $customer, $customer_id, $store1, $fromdate, $todate, $category, $cat2_id, $cat2_name, $cat2_total_count,
	$cat2_sold_total, $salesman, $item_price, $group_by, $item_is_duplicate;
	$k = 0;
	$case = false;
	$store = $_COOKIE['store'];
	$cust_qry = $store_qry = $price_group_qry = $salesman_qry = '';
	$sold_catid = $item_des = $item_price = $item_is_duplicate = array();
	$fromdate = date('Y-m-d', strtotime('-1 month'));
	$todate = date('Y-m-d');
	if ($sub_system == 'all') {
		$sub_system_qry = '';
	} else {
		$sub_system_qry = "AND bm.`sub_system`='$sub_system'";
	}
	if (isset($_REQUEST['selection'])) {
		$selection = $_REQUEST['selection'];
		if ($selection == 'customer') if (isset($_REQUEST['customer_id']))
			$case = true;
		if ($selection == 'store') if (isset($_REQUEST['store1']))
			$case = true;
	}
	if (isset($_REQUEST['salesman'])) {
		$salesman = $_REQUEST['salesman'];
		if ($salesman == 'all') {
			$salesman_qry = '';
		} else {
			$salesman_qry = "AND bm.`billed_by`='$salesman'";
		}
	}
	if (isset($_REQUEST['group_by'])) {
		$group_by = $_REQUEST['group_by'];
		if ($_REQUEST['group_by'] == 'sold_price') {
			$price_group_qry = ",bi.`unit_price`";
		}
	}
	if ($case) {
		if ($selection == 'customer') {
			$customer_id = $_REQUEST['customer_id'];
			if ($customer_id != '')
				$cust_qry = "AND bm.`cust`='$customer_id'";
		}
		if ($selection == 'store') {
			$store1 = $_REQUEST['store1'];
			if ($store1 != '')
				$store_qry = "AND st.name='$store1'";
		}
		$category = $_REQUEST['category'];
		if ($category == 'all') {
			$cat_qry = '';
		} else {
			$cat_qry = "AND itm.`category`='$category'";
		}
		$fromdate = $_REQUEST['datefrom'];
		$todate = $_REQUEST['dateto'];
		include('config.php');
		if ($selection == 'customer') {
			$query = "SELECT cu.`name` FROM cust cu WHERE cu.id='$customer_id'";
			$row = mysqli_fetch_row(mysqli_query($conn2, $query));
			$customer = $row[0];
		}

		if ($selection == 'customer') {
			if ((isset($_REQUEST["components"]) && ($_REQUEST['components'] == 'bill2')) && (inf_systemid(1) == 24)) { // logged store billed item data only
				$query = "SELECT itm.description,SUM(bi.qty),bi.`unit_price` FROM bill_main bm, bill bi, inventory_items itm WHERE bm.invoice_no=bi.invoice_no AND bi.item=itm.id AND bm.store = '$store' AND bm.`status` NOT IN (0,7) AND bm.`lock`=1 AND itm.description!='CHQUE' AND itm.description!='CREDIT' $cust_qry AND bm.`status` NOT IN (0,7) AND bm.exclude=0 AND date(bi.`date`) BETWEEN '$fromdate' AND '$todate' $cat_qry $sub_system_qry $salesman_qry GROUP BY bi.item $price_group_qry ORDER BY itm.description";
			} else {
				$query = "SELECT itm.description,SUM(bi.qty),bi.`unit_price` FROM bill_main bm, bill bi, inventory_items itm WHERE bm.invoice_no=bi.invoice_no AND bi.item=itm.id AND bm.`status` NOT IN (0,7) AND bm.`lock`=1 AND itm.description!='CHQUE' AND itm.description!='CREDIT' $cust_qry AND bm.`status` NOT IN (0,7) AND bm.exclude=0 AND date(bi.`date`) BETWEEN '$fromdate' AND '$todate' $cat_qry $sub_system_qry $salesman_qry GROUP BY bi.item $price_group_qry ORDER BY itm.description";
			}
		}
		if ($selection == 'store') {
			$query = "SELECT itm.description,SUM(bi.qty),bi.`unit_price`FROM bill_main bm, bill bi, inventory_items itm, stores st WHERE bm.invoice_no=bi.invoice_no AND bi.item=itm.id AND bm.`store`=st.id AND bm.`status` NOT IN (0,7) AND bm.`lock`=1 AND itm.description!='CHQUE' AND itm.description!='CREDIT' $store_qry AND bm.`status` NOT IN (0,7) AND bm.exclude=0 AND date(bi.`date`) BETWEEN '$fromdate' AND '$todate' $cat_qry $sub_system_qry $salesman_qry GROUP BY bi.item $price_group_qry ORDER BY itm.description";
		}
		$result = mysqli_query($conn2, $query);
		$previous_item = null;
		while ($row = mysqli_fetch_array($result)) {
			$item_des[] = $row[0];
			$description = $row[0];
			$item_qty[] = $row[1];
			$item_price[] = $row[2];

			$is_duplicate = ($previous_item === $description) ? true : false;
			$previous_item = $description;
			$item_is_duplicate[] = $is_duplicate;
		}

		if ($selection == 'customer') {
			$query = "SELECT itm.category, COUNT(DISTINCT itm.description) FROM bill_main bm, bill bi, inventory_items itm, cust cu WHERE bm.invoice_no=bi.invoice_no AND bi.item=itm.id AND bm.`cust`=cu.id AND bm.`status` NOT IN (0,7) AND bm.`lock`=1 AND itm.description!='CHQUE' AND itm.description!='CREDIT' $cust_qry AND bm.`status` NOT IN (0,7) AND bm.exclude=0 AND date(bi.`date`) BETWEEN '$fromdate' AND '$todate' $cat_qry $sub_system_qry $salesman_qry GROUP BY itm.category";
		}
		if ($selection == 'store') {
			$query = "SELECT itm.category, COUNT(DISTINCT itm.description) FROM bill_main bm, bill bi, inventory_items itm, stores st WHERE bm.invoice_no=bi.invoice_no AND bi.item=itm.id AND bm.`store`=st.id AND bm.`status` NOT IN (0,7) AND bm.`lock`=1 AND itm.description!='CHQUE' AND itm.description!='CREDIT' $store_qry AND bm.`status` NOT IN (0,7) AND bm.exclude=0 AND date(bi.`date`) BETWEEN '$fromdate' AND '$todate' $cat_qry $sub_system_qry $salesman_qry GROUP BY itm.category";
		}
		$result = mysqli_query($conn2, $query);
		while ($row = mysqli_fetch_array($result)) {
			$sold_catid[] = $row[0];
			$sold_item_typeqty[] = $row[1];
		}

		$query = "SELECT itc.name,itc.id,COUNT(itm.id) FROM item_category itc, inventory_items itm WHERE itm.category=itc.id AND itm.`status`=1 GROUP BY itm.category ORDER BY itc.name";
		$result = mysqli_query($conn2, $query);
		while ($row = mysqli_fetch_array($result)) {
			$cat2_name[$k] = $row[0];
			$cat2_id[$k] = $row[1];
			$cat2_idtmp = $row[1];
			$cat2_total_count[$k] = $row[2];
			$cat2_sold_total[$k] = 0;

			for ($i = 0; $i < sizeof($sold_catid); $i++) {
				if ($sold_catid[$i] == $cat2_idtmp) {
					$cat2_sold_total[$k] = $sold_item_typeqty[$i];
				}
			}
			$k++;
		}
	}
}

function getSalesSummary($sub_system)
{
	global $store, $from_date, $to_date, $category, $sum_date, $sum_sale, $sum_cashback, $sum_totalsale;
	$sub_system_qry = $cat_qry = $st_qry = "";
	$sum_date = $sum_sale = $sum_cashback = $sum_totalsale = array();
	if ($sub_system != 'all')
		$sub_system_qry = "AND bm.`sub_system`='$sub_system'";
	$today = date("Y-m-d", time());
	if (isset($_GET['from_date']))
		$from_date = $_GET['from_date'];
	else
		$from_date = date("Y-m-d", time() - (60 * 60 * 24 * 30));
	if (isset($_GET['to_date']))
		$to_date = $_GET['to_date'];
	else
		$to_date = $today;

	if ((isset($_GET['from_date'])) && (isset($_GET['to_date']))) {
		$category = $_GET['category'];
		if ($category != 'all')
			$cat_qry = "AND itm.category='$category'";
		$store = $_GET['store'];
		if ($store != 'all')
			$st_qry = "AND bm.store='$store'";

		include('config.php');
		$query = "SELECT DATE(bm.billed_timestamp),SUM(bi.unit_price * bi.qty) FROM bill bi, bill_main bm, inventory_items itm WHERE bi.invoice_no=bm.invoice_no AND bi.item=itm.id AND bm.`lock`='1' AND bm.`status` NOT IN (0,7) AND bm.exclude=0 AND (date(bm.billed_timestamp) BETWEEN '$from_date' AND '$to_date') $sub_system_qry $cat_qry $st_qry GROUP BY date(bm.billed_timestamp)";
		$result = mysqli_query($conn2, $query);
		while ($row = mysqli_fetch_array($result)) {
			$sum_date[] = $row[0];
			$sum_totalsale[] = $row[1];
			$sum_sale[$row[0]] = 0;
			$sum_cashback[$row[0]] = 0;
		}
		$query = "SELECT DATE(bm.billed_timestamp),SUM(bi.unit_price * bi.qty) FROM bill bi, bill_main bm, inventory_items itm WHERE bi.invoice_no=bm.invoice_no AND bi.item=itm.id AND bm.`lock`='1' AND bm.`status` NOT IN (0,7) AND bm.exclude=0 AND (date(bm.billed_timestamp) BETWEEN '$from_date' AND '$to_date') AND bi.qty>0 $sub_system_qry $cat_qry $st_qry GROUP BY date(bm.billed_timestamp)";
		$result = mysqli_query($conn2, $query);
		while ($row = mysqli_fetch_array($result)) {
			$sum_sale[$row[0]] = $row[1];
		}
		$query = "SELECT DATE(bm.billed_timestamp),SUM(bi.unit_price * bi.qty) FROM bill bi, bill_main bm, inventory_items itm WHERE bi.invoice_no=bm.invoice_no AND bi.item=itm.id AND bm.`lock`='1' AND bm.`status` NOT IN (0,7) AND bm.exclude=0 AND (date(bm.billed_timestamp) BETWEEN '$from_date' AND '$to_date') AND bi.qty<0 $sub_system_qry $cat_qry $st_qry GROUP BY date(bm.billed_timestamp)";
		$result = mysqli_query($conn2, $query);
		while ($row = mysqli_fetch_array($result)) {
			$sum_cashback[$row[0]] = $row[1];
		}
	}
}

function getSalesSummaryDetail($sub_system)
{
	global $store, $date, $category, $sumd_desc, $sumd_qty, $sumd_total;
	$sub_system_qry = $cat_qry = $st_qry = "";
	$sumd_desc = $sumd_qty = $sumd_total = array();
	if ($sub_system != 'all')
		$sub_system_qry = "AND bm.`sub_system`='$sub_system'";

	if (isset($_GET['date'])) {
		$date = $_GET['date'];
		$category = $_GET['category'];
		if ($category != 'all')
			$cat_qry = "AND itm.category='$category'";
		$store = $_GET['store'];
		if ($store != 'all')
			$st_qry = "AND bm.store='$store'";

		include('config.php');
		$query = "SELECT itm.description,SUM(bi.qty),SUM(bi.qty * bi.unit_price) FROM bill bi, bill_main bm, inventory_items itm WHERE bi.invoice_no=bm.invoice_no AND bi.item=itm.id AND bm.`lock`='1' AND bm.`status` NOT IN (0,7) AND bm.exclude=0 AND date(bm.billed_timestamp)='$date' $sub_system_qry $cat_qry $st_qry GROUP BY itm.id ORDER BY itm.description";
		$result = mysqli_query($conn2, $query);
		while ($row = mysqli_fetch_array($result)) {
			$sumd_desc[] = $row[0];
			$sumd_qty[] = $row[1];
			$sumd_total[] = $row[2];
		}
	}
}

// updated by nirmal 12_09_2023
function getSalesReport3($sub_system)
{
	global $selection, $customer, $customer_id, $store1, $category, $fromdate, $todate, $itm2_id, $itm2_name, $itm2_sold, $sold_itemid, $itm2_qty;
	$sold_itemid = array();
	$k = 0;
	$case = false;
	if ($sub_system == 'all')
		$sub_system_qry = '';
	else
		$sub_system_qry = "AND bm.`sub_system`='$sub_system'";
	if (isset($_REQUEST['selection'])) {
		$selection = $_REQUEST['selection'];
		if ($selection == 'customer') if (isset($_REQUEST['customer']))
			$case = true;
		if ($selection == 'store') if (isset($_REQUEST['store1']))
			$case = true;
	}
	if ($case) {
		if ($selection == 'customer')
			$customer_id = $_REQUEST['customer_id'];
		if ($selection == 'store')
			$store1 = $_REQUEST['store1'];
		$category = $_REQUEST['category'];
		$fromdate = $_REQUEST['datefrom'];
		$todate = $_REQUEST['dateto'];
		include('config.php');
		if ($selection == 'customer')
			$query = "SELECT DISTINCT itm.id FROM bill_main bm, bill bi, inventory_items itm WHERE bm.invoice_no=bi.invoice_no AND bi.item=itm.id AND bm.`status` NOT IN (0,7) AND bm.`lock`=1 AND itm.description!='CHQUE' AND itm.description!='CREDIT' AND bm.`cust`='$customer_id' AND bm.`status` NOT IN (0,7) AND bm.exclude=0 AND date(bi.`date`) BETWEEN '$fromdate' AND '$todate' AND itm.`category`='$category' $sub_system_qry ORDER BY itm.description";
		if ($selection == 'store')
			$query = "SELECT DISTINCT itm.id FROM bill_main bm, bill bi, inventory_items itm, stores st WHERE bm.invoice_no=bi.invoice_no AND bi.item=itm.id AND bm.`store`=st.id AND bm.`status` NOT IN (0,7) AND bm.`lock`=1 AND itm.description!='CHQUE' AND itm.description!='CREDIT' AND st.name='$store1' AND bm.`status` NOT IN (0,7) AND bm.exclude=0 AND date(bi.`date`) BETWEEN '$fromdate' AND '$todate' AND itm.`category`='$category' $sub_system_qry ORDER BY itm.description";
		$result = mysqli_query($conn2, $query);
		while ($row = mysqli_fetch_array($result)) {
			$sold_itemid[] = $row[0];
		}
		// $query = "SELECT itm.id,itm.description FROM item_category itc, inventory_items itm WHERE itm.category=itc.id AND itm.`status`=1 AND itm.`category`='$category' ORDER BY itm.description";
		$query = "SELECT itm.`id`, itm.`description`, SUM(iq.`qty`) + SUM(COALESCE(inew.`qty`, 0)) AS total_qty
					FROM item_category itc LEFT JOIN inventory_items itm ON itc.`id` = itm.`category` LEFT JOIN inventory_qty iq ON itm.`id` = iq.`item` LEFT JOIN inventory_new inew ON itm.`id` = inew.`item`
					WHERE itm.`status` = 1 AND itm.`category` = '$category'
					GROUP BY itm.`id`, itm.`description`
					ORDER BY itm.`description`";
		$result = mysqli_query($conn2, $query);
		while ($row = mysqli_fetch_array($result)) {
			$itm2_idtmp = $row[0];
			$itm2_id[$k] = $row[0];
			$itm2_name[$k] = $row[1];
			$itm2_sold[$k] = false;
			$itm2_qty[$k] = $row[2];
			for ($i = 0; $i < sizeof($sold_itemid); $i++) {
				if ($sold_itemid[$i] == $itm2_idtmp) {
					$itm2_sold[$k] = true;
				}
			}
			$k++;
		}
	}
}

// updated by nirmal 06_11_2023
function getSalesReport4($sub_system)
{
	$user = $_COOKIE['user'];
	$inf_company = inf_company(1);
	$systemid = inf_systemid(1);
	$inf_web = inf_web();
	$cust_st_qry = '';
	$item_category = $item_store = $item_salesman = $item_cust = $item_desc = $item_sn = $item_sold_price = $item_bm_date = array();
	$case = false;
	if ($sub_system == 'all')
		$sub_system_qry = '';
	else
		$sub_system_qry = "AND bm.`sub_system`='$sub_system'";
	if (isset($_REQUEST['selection'])) {
		$selection = $_REQUEST['selection'];
		if ($selection == 'customer') if (isset($_REQUEST['customer_id']))
			$case = true;
		if ($selection == 'store') if (isset($_REQUEST['store1']))
			$case = true;
	}
	if ($case) {
		if ($selection == 'customer') {
			$customer_id = $_REQUEST['customer_id'];
			$customer = $_REQUEST['customer'];
			if ($customer_id != '') {
				$cust_st_qry = "AND cu.id='$customer_id'";
				$excel_title = 'Customer: ' . $customer;
			} else {
				$excel_title = 'Customer: ALL';
			}
		}
		if ($selection == 'store') {
			$store1 = $_REQUEST['store1'];
			if ($store1 != '') {
				$cust_st_qry = "AND st.name='$store1'";
				$excel_title = 'Store: ' . $store1;
			} else {
				$excel_title = 'Store: ALL';
			}
		}
		$category = $_REQUEST['category'];
		if ($category == 'all') {
			$cat_qry = '';
		} else {
			$cat_qry = "AND itm.`category`='$category'";
		}
		$fromdate = $_REQUEST['datefrom'];
		$todate = $_REQUEST['dateto'];
		include('config.php');
		//	if($selection=='customer')
		//if($selection=='store')    $query="SELECT itc.name,st.name,cu.name,itm.description,itu.sn FROM bill_main bm, bill bi, inventory_items itm, item_category itc, inventory_unic_item itu, cust cu, stores st WHERE bi.id=itu.bill_id AND bm.invoice_no=bi.invoice_no AND bi.item=itm.id AND itm.category=itc.id AND bm.`cust`=cu.id AND bm.`store`=st.id AND itm.description!='CHQUE' AND itm.description!='CREDIT' $store_qry AND bm.`status` NOT IN (0,7) AND itm.unic=1 AND itu.`status`=1 AND bm.exclude=0 AND date(bi.`date`) BETWEEN '$fromdate' AND '$todate' $cat_qry ORDER BY itc.name,itm.description";
		//print $query;
		$query = "SELECT itc.name,st.name,up.username,cu.name,itm.description,bi.`comment`,bi.unit_price,date(bm.billed_timestamp) FROM bill_main bm, bill bi, inventory_items itm, item_category itc, cust cu, stores st, userprofile up WHERE bm.billed_by=up.id AND bm.invoice_no=bi.invoice_no AND bi.item=itm.id AND itm.category=itc.id AND bm.`cust`=cu.id AND bm.`store`=st.id AND bm.`status` NOT IN (0,7) AND bm.`lock`=1 AND itm.description!='CHQUE' AND itm.description!='CREDIT' $cust_st_qry  AND bm.`status` NOT IN (0,7) AND itm.unic=1 AND bm.exclude=0 AND date(bi.`date`) BETWEEN '$fromdate' AND '$todate' $cat_qry $sub_system_qry ORDER BY itc.name,st.name,cu.name,bm.billed_timestamp,itm.description";
		$result = mysqli_query($conn2, $query);
		while ($row = mysqli_fetch_array($result)) {
			$item_category[] = $row[0];
			$item_store[] = $row[1];
			$item_salesman[] = ucwords($row[2]);
			$item_cust[] = $row[3];
			$item_desc[] = $row[4];
			$item_sn[] = $row[5];
			$item_sold_price[] = $row[6];
			$item_bm_date[] = $row[7];
		}
		require_once('components/manager/view/excel_sales_report2.php');
		// require_once('plugin/PHPExcel-1.8/production/Sales_Report2.php');
	}
}

// updated by nirmal 26_09_2022
function salesByCategory($sub_system)
{
	global $fromdate, $todate, $cat_name, $cat_sale, $customer;

	include('config.php');
	// Date filter
	if (isset($_REQUEST['datefrom'])) {
		$fromdate = $_REQUEST['datefrom'];
	} else {
		$fromdate = date("Y-m-d", time() - 2592000);
	}
	if (isset($_REQUEST['dateto'])) {
		$todate = $_REQUEST['dateto'];
	} else {
		$todate = dateNow();
	}

	// Sub system filter
	if ($sub_system == 'all') {
		$sub_system_qry = '';
	} else {
		$sub_system_qry = "AND bm.`sub_system`='$sub_system'";
	}

	// Salesaman filter
	if (isset($_REQUEST['salesman'])) {
		if ($_REQUEST['salesman'] == 'all')
			$salesmanfilter = '';
		else
			$salesmanfilter = " AND bm.`billed_by`='" . $_REQUEST['salesman'] . "'";
	} else {
		$salesmanfilter = '';
	}

	// Customer filter
	if (isset($_REQUEST['customer_id'])) {
		if ($_REQUEST['customer_id'] == '') {
			$customerfilter = '';
		} else {
			$customer_id = $_REQUEST['customer_id'];
			$query = "SELECT `name` FROM cust WHERE `id`='$customer_id'";
			$row = mysqli_fetch_row(mysqli_query($conn, $query));
			$customer = $row[0];
			$customerfilter = " AND bm.`cust`='" . $customer_id . "'";
		}
	} else {
		$customerfilter = '';
	}

	$cat_name = $cat_sale = array();

	$query = "SELECT itc.name,SUM(bi.qty * bi.unit_price) FROM bill_main bm, bill bi, inventory_items itm, item_category itc WHERE bm.invoice_no=bi.invoice_no AND bi.item=itm.id AND itm.category=itc.id AND bm.`status` NOT IN (0,7) AND bm.`lock`=1 $sub_system_qry $salesmanfilter $customerfilter AND date(bm.billed_timestamp) BETWEEN '$fromdate' AND '$todate' GROUP BY itc.id";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$cat_name[] = $row[0];
		$cat_sale[] = $row[1];
	}
}

// update by nirmal 25_09_2024 (get sales rep according to sub system)
function salesByRep($sub_system)
{
	global $fromdate, $todate, $category, $store, $rep_id, $rep_name, $itm_id, $itm_desc, $itm_qty, $itm_stock, $total_rep;
	if (isset($_REQUEST['datefrom']))
		$fromdate = $_REQUEST['datefrom'];
	else
		$fromdate = date("Y-m-d", time() - 2592000);
	if (isset($_REQUEST['dateto']))
		$todate = $_REQUEST['dateto'];
	else
		$todate = dateNow();
	if (isset($_REQUEST['category']))
		$category = $_REQUEST['category'];
	else
		$category = '';
	if (isset($_REQUEST['store']))
		$store = $_REQUEST['store'];
	else
		$store = '';

	if ($sub_system == 'all') {
		$sub_system_qry = '';
		$sub_system_qry_user = '';
	} else {
		$sub_system_qry = "AND bm.`sub_system`='$sub_system'";
		$sub_system_qry_user = "AND up.`sub_system`='$sub_system'";
	}

	if ($store == '') {
		$store_qry = $itqst_qry = $itnst_qry = '';
	} else {
		$store_qry = "AND bm.`store`='$store'";
		$itqst_qry = "AND itq.`location`='$store'";
		$itnst_qry = "AND itn.`store`='$store'";
	}
	$rep_id = $rep_name = $itm_id = $itm_desc = $itm_qty = $itm_stock = array();
	$rep_id_list = $itm_id_tmp = '';
	include('config.php');

	$query = "SELECT up.id,up.username FROM userprofile up WHERE up.sales_rep='1' AND up.`status`='0' $sub_system_qry_user";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$rep_id[] = $row[0];
		$rep_name[] = $row[1];
		$rep_id_list .= $row[0] . ',';
		$total_rep[] = 0;
	}
	$rep_id_list = substr($rep_id_list, 0, -1);

	if ($category != '') {
		$query = "SELECT itm.id,itm.description,SUM(itq.qty),SUM(itn.qty) FROM inventory_qty itq, inventory_items itm LEFT JOIN inventory_new itn ON itm.id=itn.item $itnst_qry WHERE itm.id=itq.item AND itm.`status`='1' AND itm.category='$category' $itqst_qry GROUP BY itm.id";
		$result = mysqli_query($conn2, $query);
		while ($row = mysqli_fetch_array($result)) {
			$itm_id_tmp = $row[0];
			$itm_id[] = $row[0];
			$itm_desc[] = $row[1];
			$itm_stock[] = $row[2] + $row[3];

			for ($i = 0; $i < sizeof($rep_id); $i++) {
				$itm_qty[$itm_id_tmp][$rep_id[$i]] = 0;
			}

			$query1 = "SELECT bm.billed_by,SUM(bi.qty) FROM bill_main bm, bill bi WHERE bm.invoice_no=bi.invoice_no AND bm.`status`>3 AND bi.item='$itm_id_tmp' AND bm.`lock`='1' AND bm.billed_by IN ($rep_id_list) AND (date(bm.`billed_timestamp`) BETWEEN '$fromdate' AND '$todate') $sub_system_qry $store_qry GROUP BY bm.billed_by";
			$result1 = mysqli_query($conn2, $query1);
			while ($row1 = mysqli_fetch_array($result1)) {
				$itm_qty[$itm_id_tmp][$row1[0]] = $row1[1];
			}
		}
	}
}

function getRepairIncome($sub_system)
{
	global $inf_company, $fromdate, $todate, $re_uid, $re_uname, $re_count, $re_amount, $del_uid, $del_uname, $del_count, $del_amount;
	$inf_company = inf_company(1);
	if (isset($_GET['datefrom']))
		$fromdate = $_GET['datefrom'];
	else
		$fromdate = date("Y-m-d", time() - 2592000);
	if (isset($_GET['dateto']))
		$todate = $_GET['dateto'];
	else
		$todate = dateNow();
	if ($sub_system == 'all')
		$sub_system_qry = '';
	else
		$sub_system_qry = "AND bm.`sub_system`='$sub_system'";
	$re_uid = $re_uname = $re_count = $re_amount = array();
	include('config.php');
	$query = "SELECT up.id,up.username,SUM(bm.`invoice_+total`+bm.`invoice_-total`),COUNT(bm.invoice_no) FROM bill_main bm, userprofile up WHERE bm.packed_by=up.id AND bm.`type`='3' AND bm.`status`='5' AND DATE(bm.deliverd_timestamp) BETWEEN '$fromdate' AND '$todate' AND (bm.`invoice_+total`+bm.`invoice_-total`)!=0 $sub_system_qry GROUP BY up.id ORDER BY up.username";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$re_uid[] = $row[0];
		$re_uname[] = $row[1];
		$re_amount[] = $row[2];

		$query1 = "SELECT COUNT(bm.invoice_no) FROM bill_main bm WHERE bm.`type`='3' AND bm.`status`='5' AND DATE(bm.deliverd_timestamp) BETWEEN '$fromdate' AND '$todate' AND (bm.`invoice_-total`)!=0 AND bm.shipped_by='$row[0]' $sub_system_qry";
		$row1 = mysqli_fetch_row(mysqli_query($conn2, $query1));
		$re_count[] = $row[3] - ($row1[0] * 2);
	}
	$query = "SELECT up.id,up.username,SUM(bm.`invoice_+total`+bm.`invoice_-total`),COUNT(bm.invoice_no) FROM bill_main bm, userprofile up WHERE bm.deliverd_by=up.id AND bm.`type`='3' AND bm.`status`='5' AND DATE(bm.deliverd_timestamp) BETWEEN '$fromdate' AND '$todate' AND (bm.`invoice_+total`+bm.`invoice_-total`)!=0 $sub_system_qry GROUP BY up.id ORDER BY up.username";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$del_uid[] = $row[0];
		$del_uname[] = $row[1];
		$del_amount[] = $row[2];

		$query1 = "SELECT COUNT(bm.invoice_no) FROM bill_main bm WHERE bm.`type`='3' AND bm.`status`='5' AND DATE(bm.deliverd_timestamp) BETWEEN '$fromdate' AND '$todate' AND (bm.`invoice_-total`)!=0 AND bm.deliverd_by='$row[0]' $sub_system_qry";
		$row1 = mysqli_fetch_row(mysqli_query($conn2, $query1));
		$del_count[] = $row[3] - ($row1[0] * 2);
	}
}

function getRepairIncomeOne($sub_system)
{
	global $inf_company, $tech_name, $fromdate, $todate, $ro_inv, $ro_deliver_date, $ro_amount, $ro_billed_by, $ro_cust;
	$inf_company = inf_company(1);
	$user = $_GET['user'];
	$type = $_GET['type'];
	$fromdate = $_GET['from'];
	$todate = $_GET['to'];
	if ($sub_system == 'all')
		$sub_system_qry = '';
	else
		$sub_system_qry = "AND bm.`sub_system`='$sub_system'";
	$ro_inv = $ro_deliver_date = $ro_amount = $ro_billed_by = $ro_cust = array();
	$user_qry = "";

	if ($type == 'tech')
		$user_qry = "AND bm.shipped_by='$user'";
	if ($type == 'delivery')
		$user_qry = "AND bm.deliverd_by='$user'";

	include('config.php');
	$result = mysqli_query($conn2, "SELECT username FROM userprofile WHERE id='$user'");
	$row = mysqli_fetch_assoc($result);
	$tech_name = ucfirst($row['username']);

	$query = "SELECT bm.invoice_no,date(bm.deliverd_timestamp),(bm.`invoice_+total`+bm.`invoice_-total`),up.username,cu.`name`  FROM userprofile up, cust cu, bill_main bm WHERE bm.billed_by=up.id AND bm.`cust`=cu.id AND bm.`type`='3' AND bm.`status`='5' AND DATE(bm.deliverd_timestamp) BETWEEN '$fromdate' AND '$todate' AND (bm.`invoice_+total`+bm.`invoice_-total`)!=0 $user_qry $sub_system_qry ORDER BY bm.deliverd_timestamp";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$ro_inv[] = $row[0];
		$ro_deliver_date[] = $row[1];
		$ro_amount[] = $row[2];
		$ro_billed_by[] = ucfirst($row[3]);
		$ro_cust[] = ucfirst($row[4]);
	}
}

//----------------------------------------Unic Items---------------------------------//
function getUnicStatus($st_id)
{
	switch ($st_id) {
		case "0":
			$status_sw = 'Available';
			break;
		case "1":
			$status_sw = 'Sold';
			break;
		case "2":
			$status_sw = 'Deleted';
			break;
		case "3":
			$status_sw = 'Pending a Transfer';
			break;
		case "4":
			$status_sw = 'Return';
			break;
		case "5":
			$status_sw = 'Replacement';
			break;
		case "6":
			$status_sw = 'Disposal';
			break;
		case "7":
			$status_sw = 'Warranty';
			break;
	}
	return $status_sw;
}

function getUnicItems()
{
	global $item_des, $store_arr, $status_list, $unic_item_id, $unic_item_des, $store_id, $store_name, $itu_shipment, $itu_sn, $itu_invoice_no, $itu_trans_no, $cust, $cust_id, $return_invoice_no, $item, $store, $status, $sn, $data_bill_no, $data_rtn_no, $data_tr_no, $warranty_no, $warranty_store, $warranty_st_name, $warranty_st_color;
	$data_rtn_no = $data_bill_no = $data_tr_no = $itu_sn = $unic_item_id = $cust = $status_list = $item_des = $store_arr = $itu_trans_no = $itu_invoice_no = $return_invoice_no = $status_arr = $warranty_no = $warranty_store = $warranty_st_name = $warranty_st_color = array();
	$status = '';
	include('config.php');

	if (isset($_GET['search_unic'])) {
		$sn = preg_replace("/[^A-Za-z0-9+-,.]/", '', $_GET['search_unic']);
		$snqry = "AND itu.`sn`='$sn'";
		$itemqry = $storeqry = '';
		$result = mysqli_query($conn2, "SELECT itm.description,itq.item,st.name,itu.`status` FROM inventory_unic_item itu, inventory_qty itq, inventory_items itm, stores st WHERE itm.id=itq.item AND itu.itq_id=itq.id AND itq.location=st.id AND itu.sn='$sn'");
		while ($row = mysqli_fetch_array($result)) {
			$item_des[] = $row[0];
			$itq_id = '';
			$store_arr[] = $row[2];
			$status_arr[] = $row[3];
			$status_list[] = getUnicStatus($row[3]);
		}
	} else {
		$snqry = $sn = '';
		$item = $_GET['item'];
		$store = $_GET['store'];
		$status_arr[] = $_GET['status'];
		$itemqry = "AND itq.item='$item'";
		if ($_GET['store'] == 'all')
			$storeqry = '';
		else
			$storeqry = "AND itq.location='$store'";
	}

	$query = "SELECT id,description FROM inventory_items WHERE unic='1' ORDER BY description";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$unic_item_id[] = $row[0];
		$unic_item_des[] = $row[1];
	}
	$query = "SELECT id,name FROM stores WHERE `status`=1 ORDER BY name";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$store_id[] = $row[0];
		$store_name[] = $row[1];
	}

	for ($i = 0; $i < sizeof($status_arr); $i++) {
		if ($status != $status_arr[$i]) {
			$status = $status_arr[$i];
			$statusqry = "AND itu.`status`='$status'";
			if ($status == 1)
				$query = "SELECT itu.shipment_no,itu.sn,itu.invoice_no,itu.trans_no,cu.name,cu.id FROM inventory_unic_item itu, inventory_qty itq, bill_main bm, bill bi, cust cu WHERE bm.invoice_no=bi.invoice_no AND itq.id=itu.itq_id AND itu.bill_id=bi.id AND bm.`cust`=cu.id $snqry $statusqry $itemqry $storeqry";
			else if ($status == 4)
				$query = "SELECT itu.shipment_no,itu.sn,itu.invoice_no,itu.trans_no,cu.name,cu.id,rt.invoice_no FROM inventory_unic_item itu,  inventory_qty itq, return_main rm, `return` rt, cust cu WHERE itq.id=itu.itq_id AND itu.return_id=rt.id AND rm.invoice_no=rt.invoice_no AND rm.`cust`=cu.id $snqry $statusqry $itemqry $storeqry";
			else
				$query = "SELECT itu.shipment_no,itu.sn,itu.invoice_no,itu.trans_no FROM inventory_unic_item itu, inventory_qty itq WHERE itq.id=itu.itq_id $snqry $statusqry $itemqry $storeqry";
			$result = mysqli_query($conn2, $query);
			while ($row = mysqli_fetch_array($result)) {
				$itu_shipment[] = $row[0];
				$itu_sn[] = $row[1];
				$itu_invoice_no[] = $row[2];
				$itu_trans_no[] = $row[3];
				if (($status == 1) || ($status == 4)) {
					$cust[] = $row[4];
					$cust_id[] = $row[5];
				} else {
					$cust[] = '';
					$cust_id[] = '';
				}
				if ($status == 4)
					$return_invoice_no[] = $row[6];
				else
					$return_invoice_no[] = 0;
			}
		}
	}

	if ($sn != '') {
		$query = "SELECT invoice_no FROM bill WHERE `comment` LIKE '%$sn%'";
		$result = mysqli_query($conn2, $query);
		while ($row = mysqli_fetch_array($result)) {
			$data_bill_no[] = $row[0];
		}
		$query = "SELECT invoice_no FROM `return` WHERE `return_sn` LIKE '%$sn%' OR replace_sn LIKE '%$sn%'";
		$result = mysqli_query($conn2, $query);
		while ($row = mysqli_fetch_array($result)) {
			$data_rtn_no[] = $row[0];
		}
		$query = "SELECT gtn_no FROM `transfer` WHERE `sn_list` LIKE '%$sn%'";
		$result = mysqli_query($conn2, $query);
		while ($row = mysqli_fetch_array($result)) {
			$data_tr_no[] = $row[0];
		}


		$query = "SELECT wa.id,st.name,wa.`status` FROM warranty wa, stores st WHERE wa.store=st.id AND (wa.claim_sn='$sn' OR wa.inv_replace_sn='$sn' OR wa.suplier_replace_sn='$sn' OR wa.handover_sn='$sn')";
		$result = mysqli_query($conn2, $query);
		while ($row = mysqli_fetch_array($result)) {
			$warranty_no[] = $row[0];
			$warranty_store[] = $row[1];
			$json_array = json_decode(warrantyStatus2($row[2]));
			$warranty_st_name[] = $json_array->{"st_name"};
			$warranty_st_color[] = $json_array->{"st_color"};
		}
	}
}

function snLookupList()
{
	$data_list = '';
	include('config.php');
	if ((isset($_POST['item_desc'])) || (isset($_POST['item_sn']))) {
		$store = $_POST['store'];
		$item_desc = str_replace("'", "", $_POST['item_desc']);
		$item_sn = str_replace("'", "", $_POST['item_sn']);
		$unic_cal = unicCal();
		$i = 1;

		if ($item_sn != '')
			$qry_sn = "AND itu.sn='$item_sn'";
		else
			$qry_sn = "AND itm.description='$item_desc'";
		if ($store == 'all')
			$qry_store = "";
		else
			$qry_store = "AND itq.location='$store'";

		if ($unic_cal) {
			$query = "SELECT itu.sn,itu.c_price,itu.w_price,itu.r_price,itq.qty,itm.description, st.name FROM inventory_unic_item itu, inventory_qty itq, inventory_items itm, stores st WHERE itu.itq_id=itq.id AND itq.item=itm.id AND itq.location=st.id AND itu.`status`=0 $qry_store $qry_sn ORDER BY itu.c_price";
		} else {
			$query = "SELECT itu.sn,itq.c_price,itq.w_price,itq.r_price,itq.qty,itm.description, st.name FROM inventory_unic_item itu, inventory_qty itq, inventory_items itm, stores st WHERE itu.itq_id=itq.id AND itq.item=itm.id AND itq.location=st.id AND itu.`status`=0 $qry_store $qry_sn ORDER BY itu.id";
		}
		$result = mysqli_query($conn, $query);
		while ($row = mysqli_fetch_array($result)) {
			$sn = $row[0];
			$c_price = $row[1];
			$w_price = $row[2];
			$r_price = $row[3];
			$itm_desc = $row[5];
			$store = $row[6]; // added by e.s.p nirmal 2021-06-23
			if (($i > $row[4]) && (!$unic_cal))
				$c_price = $w_price = $r_price = 'New Stock Price Available';
			$data_list .= $store . ','; // added by e.s.p nirmal 2021-06-23
			$data_list .= $sn . ',';
			$data_list .= $c_price . ',';
			$data_list .= $w_price . ',';
			$data_list .= $r_price . ',';
			$data_list .= $itm_desc . '|';
			$i++;
		}
		$data_list = rtrim($data_list, '|');
		return $data_list;
	}
}

// edit by nirmal - 21_9_30
function snLookupPriceList()
{
	$data_list = '';
	include('config.php');
	if ((isset($_POST['max_price'])) || (isset($_POST['min_price']))) {
		$store = $_POST['store'];
		$max_price = $_POST['max_price'];
		$min_price = $_POST['min_price'];
		$key_word = str_replace("'", "", $_POST['key_word']);
		$unic_cal = unicCal();
		$i = 1;

		if ($store == 'all')
			$qry_store = "";
		else
			$qry_store = "AND itq.location='$store'";
		if ($key_word == '')
			$qry_keyword = "";
		else
			$qry_keyword = "AND itm.description  LIKE '%$key_word%'";
		if ($unic_cal) {
			//			$query = "SELECT itu.sn,itu.c_price,itq.qty,itm.description, st.`name`,tt.min_profit FROM inventory_unic_item itu, inventory_qty itq, stores st, inventory_items itm LEFT JOIN (SELECT ta.item,tn.min_profit FROM tag_assignment ta, tag_name tn WHERE ta.tag=tn.id) AS tt ON itm.id=tt.item WHERE itu.itq_id=itq.id AND itq.item=itm.id AND itq.location=st.id AND itu.`status`=0  $qry_store $qry_keyword AND ((itu.c_price + tt.min_profit) BETWEEN $min_price AND $max_price ) ORDER BY itu.c_price";
			$query = "SELECT itu.sn,itu.c_price,itq.qty,itm.description, st.`name`,tt.min_profit FROM inventory_unic_item itu, inventory_qty itq, stores st, inventory_items itm LEFT JOIN (SELECT ta.item,tn.min_profit FROM tag_assignment ta, tag_name tn WHERE ta.tag=tn.id) AS tt ON itm.id=tt.item WHERE itu.itq_id=itq.id AND itq.item=itm.id AND itq.location=st.id AND itu.`status`=0  $qry_store $qry_keyword AND ( ((itu.c_price + tt.min_profit) BETWEEN $min_price AND $max_price ) OR ((itu.c_price BETWEEN $min_price AND $max_price) AND (tt.min_profit IS NULL)) ) ORDER BY itu.c_price";
		} else {
			//			$query = "SELECT itu.sn,itq.c_price,itq.qty,itm.description, st.`name`,tt.min_profit FROM inventory_unic_item itu, inventory_qty itq, stores st, inventory_items itm LEFT JOIN (SELECT ta.item,tn.min_profit FROM tag_assignment ta, tag_name tn WHERE ta.tag=tn.id) AS tt ON itm.id=tt.item WHERE itu.itq_id=itq.id AND itq.item=itm.id AND itq.location=st.id AND itu.`status`=0  $qry_store $qry_keyword AND ((itq.c_price + tt.min_profit) BETWEEN $min_price AND $max_price ) ORDER BY itu.id";
			$query = "SELECT itu.sn,itq.c_price,itq.qty,itm.description, st.`name`,tt.min_profit FROM inventory_unic_item itu, inventory_qty itq, stores st, inventory_items itm LEFT JOIN (SELECT ta.item,tn.min_profit FROM tag_assignment ta, tag_name tn WHERE ta.tag=tn.id) AS tt ON itm.id=tt.item WHERE itu.itq_id=itq.id AND itq.item=itm.id AND itq.location=st.id AND itu.`status`=0  $qry_store $qry_keyword AND ( ((itq.c_price + tt.min_profit) BETWEEN $min_price AND $max_price ) OR ((itq.c_price BETWEEN $min_price AND $max_price) AND (tt.min_profit IS NULL)) ) ORDER BY itu.id";
		}
		$result = mysqli_query($conn, $query);
		while ($row = mysqli_fetch_array($result)) {
			$sn = $row[0];
			$c_price = $row[1];
			$itm_desc = $row[3];
			$store = $row[4];
			if (($row[5] == "") || ($row[5] == 0))
				$min_profit = 'No Price Tag';
			else
				$min_profit = $row[5] + $c_price;
			// if (($i > $row[2]) && (!$unic_cal)) $min_profit = 'New Stock Price Available';
			$data_list .= $store . ',';
			$data_list .= $sn . ',';
			$data_list .= $c_price . ',';
			$data_list .= $min_profit . ',';
			$data_list .= $itm_desc . '|';
			$i++;
		}
		$data_list = rtrim($data_list, '|');
		return $data_list;
	}
}

function snListAll()
{
	global $data_list, $fn;
	$data_list = array();
	$fn = 'selectSN';
	include('config.php');
	if (isset($_POST['keyword'])) {
		$keyword = $_POST['keyword'];
		$query = "SELECT itu.sn FROM inventory_unic_item itu WHERE itu.`status`=0 AND itu.sn LIKE '%$keyword%' LIMIT 20";
		$result = mysqli_query($conn, $query);
		while ($row = mysqli_fetch_array($result)) {
			$data_list[] = $row[0];
		}
	}
}
//-----------------------------Shipment------------------------------------//

// update by nirmal 22_11_2023
function getShipmentList()
{
	global $sm_id, $sm_inv_date, $sm_inv_no, $su_name, $filter_shipno, $filter_invdate, $filter_invno, $filter_invsup, $sm_fully_paid;
	$systemid = inf_systemid(1);
	$sub_system = $_COOKIE['sub_system'];
	if (isset($_POST['ship_no'])) {
		$filter_shipno = $_POST['ship_no'];
		if ($filter_shipno != '')
			$qry_shipno = "AND sm.id='$filter_shipno'";
		else
			$qry_shipno = '';
	} else {
		$filter_shipno = '';
		$qry_shipno = '';
	}
	;
	if (isset($_POST['inv_date'])) {
		$filter_invdate = $_POST['inv_date'];
		if ($filter_invdate != '')
			$qry_invdate = "AND sm.invoice_date='$filter_invdate'";
		else
			$qry_invdate = '';
	} else {
		$filter_invdate = '';
		$qry_invdate = '';
	}
	;
	if (isset($_POST['inv_no'])) {
		$filter_invno = $_POST['inv_no'];
		if ($filter_invno != '')
			$qry_invno = "AND sm.invoice_no LIKE '%$filter_invno%'";
		else
			$qry_invno = '';
	} else {
		$filter_invno = '';
		$qry_invno = '';
	}
	;
	if (isset($_POST['inv_sup'])) {
		$filter_invsup = $_POST['inv_sup'];
		if ($filter_invsup != '')
			$qry_invsup = "AND su.name LIKE '%$filter_invsup%'";
		else
			$qry_invsup = '';
	} else {
		$filter_invsup = '';
		$qry_invsup = '';
	}
	;
	$sm_id = array();
	$qry_sub_system = '';
	if (($systemid == 13) && ($_REQUEST['components'] == 'manager')) {
		$qry_sub_system = "AND sm.sub_system='$sub_system'";
	}
	include('config.php');

	$query = "SELECT sm.id,sm.invoice_date,sm.invoice_no,su.name,sm.fully_paid FROM shipment_main sm, supplier su WHERE sm.`supplier`=su.id AND sm.`status`!='3' $qry_shipno $qry_invdate $qry_invno $qry_invsup $qry_sub_system ORDER BY sm.id DESC LIMIT 50";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$sm_id[] = $row[0];
		$sm_inv_date[] = $row[1];
		$sm_inv_no[] = $row[2];
		$su_name[] = $row[3];
		$sm_fully_paid[] = $row[4];
	}
}

function getShipmentOne()
{
	global $sm1_id, $sm1_date, $sm1_invdate, $sm1_invdue, $sm1_inv, $sm1_sup, $sm1_amount, $ms2_des, $ms2_qty, $ms2_c_price, $ms2_unic, $sm3_soldprice, $sm3_soldqty, $acc_id, $acc_name, $sp_id, $sp_user, $sp_type, $sp_journal, $sp_pay_date, $sp_sys_date, $sp_ref, $sp_amount;
	$ms2_des = $sp_id = array();
	if (isset($_GET['id'])) {
		$id = $_GET['id'];
		$unic_cal = unicCal();
		$i = 0;
		include('config.php');
		$result = mysqli_query($conn2, "SELECT sm.id,sm.shipment_date,sm.invoice_date,sm.invoice_due,sm.invoice_no,su.name FROM shipment_main sm, supplier su WHERE sm.`supplier`=su.id AND sm.id='$id'");
		$row = mysqli_fetch_assoc($result);
		$sm1_id = $row['id'];
		$sm1_date = $row['shipment_date'];
		$sm1_invdate = $row['invoice_date'];
		$sm1_invdue = $row['invoice_due'];
		$sm1_inv = $row['invoice_no'];
		$sm1_sup = $row['name'];
		//		$sm1_amount=$row['amount'];

		$query = "SELECT ins.id,itm.description,ins.added_qty,ins.cost,itm.unic FROM inventory_shipment ins, inventory_items itm WHERE itm.id=ins.inv_item AND ins.shipment_no='$id'";
		$result = mysqli_query($conn2, $query);
		while ($row = mysqli_fetch_array($result)) {
			$ins_id = $row[0];
			$ms2_des[] = $row[1];
			$ms2_unic[] = $row[4];
			if (($unic_cal) && ($row[4] == 1)) {
				$query1 = "SELECT COUNT(id),SUM(c_price) FROM inventory_unic_item WHERE inv_ship_id='$ins_id' AND `status`!='2'";
				$row1 = mysqli_fetch_row(mysqli_query($conn2, $query1));
				$ms2_qty[] = $row1[0];
				if ($row1[0] != 0)
					$ms2_c_price[] = $row1[1] / $row1[0];
				else
					$ms2_c_price[] = 0;
			} else {
				$ms2_qty[] = $row[2];
				$ms2_c_price[] = $row[3];
			}

			if ($row[4] == 1) {
				$query1 = "SELECT SUM(bi.unit_price) as `soldprice`, SUM(bi.qty) as `soldqty` FROM inventory_unic_item itu, bill bi WHERE itu.bill_id=bi.id AND itu.`status`='1' AND itu.inv_ship_id='$ins_id' AND itu.shipment_no='$id'";
				$result1 = mysqli_query($conn2, $query1);
				$row1 = mysqli_fetch_assoc($result1);
				$sm3_soldprice[$i] = $row1['soldprice'];
				$sm3_soldqty[$i] = $row1['soldqty'];
			}
			$i++;
		}
		$query = "SELECT id,`name` FROM accounts WHERE payment_ac='1' AND `status`='1'";
		$result = mysqli_query($conn2, $query);
		while ($row = mysqli_fetch_array($result)) {
			$acc_id[] = $row[0];
			$acc_name[] = $row[1];
		}
		$query = "SELECT sp.id,up.username,sp.`type`,sp.journal_id,sp.pay_date,sp.system_date,sp.ref,sp.amount FROM shipment_pay sp, userprofile up WHERE sp.placed_by=up.id AND sp.shipment_no='$id' ORDER BY sp.pay_date";
		$result = mysqli_query($conn2, $query);
		while ($row = mysqli_fetch_array($result)) {
			$sp_id[] = $row[0];
			$sp_user[] = $row[1];
			$sp_type[] = $row[2];
			$sp_journal[] = $row[3];
			$sp_pay_date[] = $row[4];
			$sp_sys_date[] = substr($row[5], 0, 16);
			$sp_ref[] = $row[6];
			$sp_amount[] = $row[7];
		}
	}
}

// update by nirmal 23_11_2023
// update by nirmal 09_08_2024 (add quickbooks journal entry add)
function addShipPayment($case)
{
	global $message, $shipment_no;
	$shipment_no = $_GET['id'];
	$date = $_POST['date'];
	$ref = trim($_POST['ref']);
	$amount = $_POST['amount'];
	$qb_msg = $journal_entry_result = '';
	$unic_cal = unicCal();

	$memo = '';
	$placed_by = $_COOKIE['user_id'];
	$today = timeNow();
	$systemid = inf_systemid(1);
	if ($case == 'dis') {
		$word = 'Discount';
		$message = "Discount was added successfully";
	}
	if ($case == 'pay') {
		$word = 'Payment';
		$message = "Payment was added successfully.";
	}
	$out = true;
	include('config.php');

	try {
		// Start the transaction
		mysqli_begin_transaction($conn);

		$query = "SELECT location,SUM(cost*added_qty) FROM inventory_shipment WHERE shipment_no='$shipment_no'";
		$row = mysqli_fetch_row(mysqli_query($conn2, $query));
		$store = $row[0];
		$ship_total = $row[0];
		$query = "SELECT SUM(amount) FROM shipment_pay WHERE shipment_no='$shipment_no'";
		$row = mysqli_fetch_row(mysqli_query($conn, $query));
		$total_paid = $row[0] + $amount;
		if ($ship_total == $total_paid)
			$fully_paid = 1;
		else
			$fully_paid = 0;


		$result = mysqli_query($conn, "SELECT MAX(journal_id) as `maxid` FROM journal_main");
		$row = mysqli_fetch_assoc($result);
		$journal_id = $row['maxid'];
		if ($journal_id == '')
			$journal_id = 1;
		else
			$journal_id++;

		$result = mysqli_query($conn, "SELECT su.id,su.name FROM shipment_main sm, supplier su WHERE sm.`supplier`=su.id AND sm.id='$shipment_no'");
		$row = mysqli_fetch_assoc($result);
		$sup_id = $row['id'];
		$sup_name = $row['name'];
		$result = mysqli_query($conn, "SELECT id FROM accounts WHERE `name`='Dis-$sup_name'");
		$row = mysqli_fetch_assoc($result);
		$account1 = $row['id'];
		$result = mysqli_query($conn, "SELECT id FROM accounts WHERE `name`='$sup_name'");
		$row = mysqli_fetch_assoc($result);
		$account2 = $row['id'];
		if ($case == 'dis') {
			$from_account = $account1;
			$to_account = $account2;
			$type = 1;
			$des = 'Shipment: ' . str_pad($shipment_no, 7, "0", STR_PAD_LEFT) . ' | Discount';
		}
		if ($case == 'pay') {
			$from_account = $_POST['from_account'];
			$to_account = $account2;
			$type = 2;
			$des = 'Shipment: ' . str_pad($shipment_no, 7, "0", STR_PAD_LEFT) . ' | Payment';
		}

		if (($from_account == '') || ($to_account == '')) {
			$out = false;
			$message = 'Accounts missing | 501';
			throw new Exception($message);
		}

		$cr_dr1 = 'cr';
		$cr_dr2 = 'dr';
		$amount1 = -1 * $amount;
		$amount2 = $amount;
		$payee_type = 'supplier';

		if ($out) { // restrict duplicate of ref no
			$result = mysqli_query($conn, "SELECT `ref` FROM shipment_pay WHERE `ref`='$ref'");
			if ($result) {
				$num_rows = mysqli_num_rows($result);
				if ($num_rows > 0) {
					$out = false;
					$message = "Error : Reference number already exists.";
					throw new Exception($message);
				}
			} else {
				$out = false;
				$message = "Error: " . mysqli_error($conn);
				throw new Exception($message);
			}
		}
		if ($out) {
			$query = "INSERT INTO `shipment_pay` (`shipment_no`,`placed_by`,`type`,`pay_date`,`system_date`,`ref`,`amount`) VALUES ('$shipment_no','$placed_by','$type','$date','$today','$ref','$amount')";
			$result = mysqli_query($conn, $query);
			$lastid_temp = mysqli_insert_id($conn);
			if (!$result) {
				$out = false;
				$message = $word . ' Cannot Be Added | 502';
				throw new Exception($message);
			}
		}
		if ($out) {
			$query = "INSERT INTO `journal_main` (`journal_id`,`placed_by`,`placed_date`,`journal_date`,`store`,`ref_no`,`memo`,`no_delete`,`status`) VALUES ('$journal_id','$placed_by','$today','$date','$store','$ref','$memo','1','1')";
			$result = mysqli_query($conn, $query);
			if (!$result) {
				$out = false;
				$message = 'Payment cannot be added | 503';
				throw new Exception($message);
			}
		}
		if ($out) {
			$query = "INSERT INTO `journal_item` (`journal_id`,`account`,`description`,`cr_dr`,`amount`,`stakeholder_type`,`stakeholder_id`) VALUES ('$journal_id','$from_account','$des','$cr_dr1','$amount1','$payee_type','$sup_id')";
			$result1 = mysqli_query($conn, $query);
			$query = "INSERT INTO `journal_item` (`journal_id`,`account`,`description`,`cr_dr`,`amount`,`stakeholder_type`,`stakeholder_id`) VALUES ('$journal_id','$to_account','$des','$cr_dr2','$amount2','$payee_type','$sup_id')";
			$result2 = mysqli_query($conn, $query);
			if ((!$result1) || (!$result2)) {
				$out = false;
				$message = $word . ' cannot ae added | 503';
				throw new Exception($message);
			}
		}
		if ($out) {
			$query = "UPDATE `shipment_pay` SET `journal_id`='$journal_id' WHERE `id`='$lastid_temp'";
			$result = mysqli_query($conn, $query);
			if (!$result) {
				$out = false;
				$message = $word . ' cannot be added | 502';
				throw new Exception($message);
			}
		}
		if ($out) {
			$query = "UPDATE `shipment_main` SET `fully_paid`='$fully_paid' WHERE `id`='$shipment_no'";
			$result = mysqli_query($conn, $query);
			if (!$result) {
				$out = false;
				$message = $word . ' cannot be added | 504';
				throw new Exception($message);
			}
		}

		if ($out) {
			if (isQuickBooksActive(1)) {
				$shipmentQuery = "SELECT  sm.`qb_id`, a.`qb_account_id`, s.`name` FROM `shipment_main` sm
								JOIN `supplier` s ON sm.`supplier` = s.`id` JOIN `accounts` a ON s.`name` = a.`name`
								WHERE sm.`id` = '$shipment_no' AND sm.`status` = 0";
				$shipmentRow = mysqli_fetch_row(mysqli_query($conn2, $shipmentQuery));
				$qbID = $shipmentRow[0];
				$vendorID = $shipmentRow[1];
				$vendorName = $shipmentRow[2];

				$result1 = mysqli_query($conn2, "SELECT `qb_account_id`,`name` FROM accounts WHERE `id`='$from_account'");
				$row = mysqli_fetch_assoc($result1);
				$qb_from_account_id = $row['qb_account_id'];
				$qb_from_account_name = $row['name'];

				$accountQuery2 = "SELECT `qb_account_id` FROM `accounts` WHERE `name` = 'Accounts Payable (A/P)' AND `status` = 1";
				$accountResult2 = mysqli_fetch_row(mysqli_query($conn2, $accountQuery2));
				$accountsPayableID = $accountResult2[0];

				$amount = abs(floatval($amount));
				if (($qbID != '') && ($vendorID != '')) {
					$journal_entries = [
						[
							"posting_type" => "Debit",
							"account_id" => $accountsPayableID,
							"account_name" => "Accounts Payable (A/P)",
							"amount" => $amount,
							"description" => "[SHIPMENT PAY] Shipment No : $shipment_no",
							"entity_type" => "Vendor",
							"entity_id" => $vendorID,
							"entity_name" => $vendorName
						],
						[
							"posting_type" => "Credit",
							"account_id" => $qb_from_account_id,
							"account_name" => $qb_from_account_name,
							"amount" => $amount,
							"description" => "Ref: $ref (Shipment No : $shipment_no)",
						]
					];
					$action_name = "shipment_pay_insert";
					$batch_id = 'batch_' . substr(md5(microtime() . mt_rand()), 0, 16); // 16 characters
					foreach ($journal_entries as $entry) {
						$posting_type = mysqli_real_escape_string($conn, $entry['posting_type']);
						$account_id = mysqli_real_escape_string($conn, $entry['account_id']);
						$account_name = mysqli_real_escape_string($conn, $entry['account_name']);
						$amount = mysqli_real_escape_string($conn, $entry['amount']);
						$description = mysqli_real_escape_string($conn, $entry['description']);
						$entity_type = isset($entry['entity_type']) ? mysqli_real_escape_string($conn, $entry['entity_type']) : null;
						$entity_id = isset($entry['entity_id']) ? mysqli_real_escape_string($conn, $entry['entity_id']) : null;
						$entity_name = isset($entry['entity_name']) ? mysqli_real_escape_string($conn, $entry['entity_name']) : null;

						$query = "INSERT INTO qb_queue (`batch_id`,`action`, `shipment_no`, `posting_type`, `account_id`, `account_name`, `amount`, `description`, `entity_type`, `entity_id`, `entity_name`)
								VALUES ('$batch_id','$action_name', '$shipment_no', '$posting_type', '$account_id', '$account_name', '$amount', '$description',
									" . ($entity_type !== null ? "'$entity_type'" : "NULL") . ",
									" . ($entity_id !== null ? "'$entity_id'" : "NULL") . ",
									" . ($entity_name !== null ? "'$entity_name'" : "NULL") . ")";
						if (!mysqli_query($conn, $query)) {
							$message = "MySQL Error while inserting into qb_queue: " . mysqli_error($conn);
							throw new Exception($message);
						}
					}
					// try {
					// 	$journal_entry_result = QBAddJournalEntry($journal_entries);
					// 	$qb_msg = $journal_entry_result['message'];

					// 	if($journal_entry_result['status'] == 'success'){
					// 		$qb_journal_entry_id = $journal_entry_result['qb_journal_entry_id'];
					// 		$query = "UPDATE `shipment_pay` SET `qb_id`='$qb_journal_entry_id' WHERE `shipment_no`='$shipment_no'";
					// 		$result1 = mysqli_query($conn, $query);
					// 		if(!$result1){
					// 			$out = false;
					// 			$qb_msg = 'Quickbooks shipment main id update error.';
					// 			throw new Exception($qb_msg);
					// 		}
					// 		$query = "UPDATE `journal_main` SET `qb_id`='$qb_journal_entry_id' WHERE `journal_id`='$journal_id'";
					// 		$result1 = mysqli_query($conn, $query);
					// 		if(!$result1){
					// 			$out = false;
					// 			$qb_msg .= ' Quickbooks journal main id update error.';
					// 			throw new Exception($qb_msg);
					// 		}
					// 	}else{
					// 		$out = false;
					// 		throw new Exception($qb_msg);
					// 	}
					// } catch (Exception $e) {
					// 	$out = false;
					// 	$qb_msg = "<br>QuickBooks error: " . $e->getMessage();
					// 	$journal_entry_result['status'] = 'error';
					// 	throw new Exception($qb_msg);
					// }
				} else {
					$out = false;
					$qb_msg = "Quickbooks error: The shipment is missing a corresponding journal entry, or the supplier is not registered in QuickBooks through the system.";
					throw new Exception($qb_msg);
				}

			}
		}
		// Commit the transaction
		mysqli_commit($conn);
	} catch (Exception $e) {
		mysqli_rollback($conn);
		$message = $e->getMessage();
		error_log("Error in addShipPayment(): " . $message); // Log the error
	}
	$message = $message . ' ' . $qb_msg;
	return $out;
}

// updated by nirmal 13_08_2024 (added quickbooks journal entry delete)
function deleteShipPayment()
{
	global $message, $shipment_no;
	$shipment_no = $_GET['shipment_no'];
	$id = $_GET['id'];
	$pass = $_GET['pass'];
	$journal_id = $journal_entry_array = $qb_msg = $qb_id = $qb_result = '';
	$out = true;
	$message = "Payment was deleted successfully.";
	include('config.php');

	try {
		// Start the transaction
		mysqli_begin_transaction($conn);

		$result = mysqli_query($conn, "SELECT `value` FROM settings WHERE setting='master_pw2'");
		$row = mysqli_fetch_assoc($result);
		$master_pw2 = $row['value'];

		$result = mysqli_query($conn, "SELECT journal_id FROM shipment_pay WHERE id='$id'");
		$row = mysqli_fetch_assoc($result);
		$journal_id = $row['journal_id'];

		$result = mysqli_query($conn, "SELECT ji.`account`, ji.`amount` FROM journal_main jm, journal_item ji WHERE ji.`journal_id` = jm.`journal_id` AND ji.journal_id='$journal_id' AND cr_dr = 'cr'");
		$row_account = mysqli_fetch_assoc($result);
		$from_account = $row_account['account'];
		$amount = $row_account['amount'];

		if ($out) {
			if ($pass != $master_pw2) {
				$out = false;
				$message = 'Error: Invalid password';
				throw new Exception($message);
			}
		}
		if ($out) {
			if ($journal_id == '') {
				$out = false;
				$message = 'Error: No journal entry found';
				throw new Exception($message);
			}
		}
		if ($out && isQuickBooksActive(1)) {
			$result = mysqli_query($conn, "SELECT `qb_id` FROM shipment_pay WHERE id='$id'");
			$row = mysqli_fetch_assoc($result);
			$qb_id = $row['qb_id'];
			$journal_entry_array = array('id' => $qb_id);
		}
		if ($out) {
			$result = mysqli_query($conn, "DELETE FROM journal_item WHERE journal_id='$journal_id'");
			if (!$result) {
				$out = false;
				$message = 'Error: Record cannot be deleted | 601';
				throw new Exception($message);
			}
		}
		if ($out) {
			$result = mysqli_query($conn, "DELETE FROM shipment_pay WHERE id='$id'");
			if (!$result) {
				$out = false;
				$message = 'Error: Record cannot be deleted | 603';
				throw new Exception($message);
			}
		}
		if ($out) {
			$result = mysqli_query($conn, "DELETE FROM journal_main WHERE journal_id='$journal_id'");
			if (!$result) {
				$out = false;
				$message = 'Record cannot be deleted | 602';
				throw new Exception($message);
			}
		}
		if ($out) {
			$query = "SELECT SUM(cost*added_qty) FROM inventory_shipment WHERE shipment_no='$shipment_no'";
			$row = mysqli_fetch_row(mysqli_query($conn, $query));
			$ship_total = $row[0];
			$query = "SELECT SUM(amount) FROM shipment_pay WHERE shipment_no='$shipment_no'";
			$row = mysqli_fetch_row(mysqli_query($conn, $query));
			$total_paid = $row[0];
			if ($ship_total != $total_paid) {
				$query = "UPDATE `shipment_main` SET `fully_paid`='0' WHERE `id`='$shipment_no'";
				$result = mysqli_query($conn, $query);
				if (!$result) {
					$out = false;
					$message = 'Record cannot be deleted | 604';
					throw new Exception($message);
				}
			}
		}
		if (($out) && (count($journal_entry_array) > 0)) {
			if (isQuickBooksActive(1)) {
				if ($qb_id != '') {
					$batch_id = 'batch_' . substr(md5(microtime() . mt_rand()), 0, 16); // 16 characters
					$shipmentQuery = "SELECT  sm.`qb_id`, a.`qb_account_id`, s.`name` FROM `shipment_main` sm
								JOIN `supplier` s ON sm.`supplier` = s.`id` JOIN `accounts` a ON s.`name` = a.`name`
								WHERE sm.`id` = '$shipment_no' AND sm.`status` = 0";
					$shipmentRow = mysqli_fetch_row(mysqli_query($conn2, $shipmentQuery));
					$qbID = $shipmentRow[0];
					$vendorID = $shipmentRow[1];
					$vendorName = $shipmentRow[2];

					$result1 = mysqli_query($conn2, "SELECT `qb_account_id`,`name` FROM accounts WHERE `id`='$from_account'");
					$row = mysqli_fetch_assoc($result1);
					$qb_from_account_id = $row['qb_account_id'];
					$qb_from_account_name = $row['name'];

					$accountQuery2 = "SELECT `qb_account_id` FROM `accounts` WHERE `name` = 'Accounts Payable (A/P)' AND `status` = 1";
					$accountResult2 = mysqli_fetch_row(mysqli_query($conn2, $accountQuery2));
					$accountsPayableID = $accountResult2[0];

					$amount = abs(floatval($amount));

					if (($qbID != '') && ($vendorID != '') && ($qb_from_account_id != '')) {
						$journal_entries = [
							[
								"posting_type" => "Credit",
								"account_id" => $accountsPayableID,
								"account_name" => "Accounts Payable (A/P)",
								"amount" => $amount,
								"description" => "[SHIPMENT PAY DELETE] Shipment No : $shipment_no",
								"entity_type" => "Vendor",
								"entity_id" => $vendorID,
								"entity_name" => $vendorName
							],
							[
								"posting_type" => "Debit",
								"account_id" => $qb_from_account_id,
								"account_name" => $qb_from_account_name,
								"amount" => $amount,
								"description" => "[SHIPMENT PAY DELETE] Shipment No : $shipment_no",
							]
						];
						$action_name = "shipment_pay_delete";
						$batch_id = 'batch_' . substr(md5(microtime() . mt_rand()), 0, 16); // 16 characters

						foreach ($journal_entries as $entry) {
							$posting_type = mysqli_real_escape_string($conn, $entry['posting_type']);
							$account_id = mysqli_real_escape_string($conn, $entry['account_id']);
							$account_name = mysqli_real_escape_string($conn, $entry['account_name']);
							$amount = mysqli_real_escape_string($conn, $entry['amount']);
							$description = mysqli_real_escape_string($conn, $entry['description']);
							$entity_type = isset($entry['entity_type']) ? mysqli_real_escape_string($conn, $entry['entity_type']) : null;
							$entity_id = isset($entry['entity_id']) ? mysqli_real_escape_string($conn, $entry['entity_id']) : null;
							$entity_name = isset($entry['entity_name']) ? mysqli_real_escape_string($conn, $entry['entity_name']) : null;

							$query = "INSERT INTO qb_queue (`batch_id`,`action`, `shipment_no`, `posting_type`, `account_id`, `account_name`, `amount`, `description`, `entity_type`, `entity_id`, `entity_name`)
								VALUES ('$batch_id','$action_name', '$shipment_no', '$posting_type', '$account_id', '$account_name', '$amount', '$description',
									" . ($entity_type !== null ? "'$entity_type'" : "NULL") . ",
									" . ($entity_id !== null ? "'$entity_id'" : "NULL") . ",
									" . ($entity_name !== null ? "'$entity_name'" : "NULL") . ")";
							if (!mysqli_query($conn, $query)) {
								$message = "MySQL Error: " . mysqli_error($conn);
								throw new Exception($message);
							}
						}
					} else {
						$out = false;
						$qb_msg = "Quickbooks error: The shipment is missing a corresponding journal entry or debit account not registered in QB or vendor not registered in QB.";
						throw new Exception($qb_msg);
					}
				}
			}
		}
		// Commit the transaction
		mysqli_commit($conn);
	} catch (Exception $e) {
		mysqli_rollback($conn);
		$message = $e->getMessage();
		error_log("Error in deleteShipPayment(): " . $message); // Log the error
	}
	$message = $message . ' ' . $qb_msg;
	return $out;
}

//-----------------------------Chques------------------------------------//
function getBankAccounts()
{
	global $bnk_id, $bnk_name;
	include('config.php');
	$query = "SELECT id,name FROM accounts WHERE bank_ac=1 AND `status`=1 ORDER BY name";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$bnk_id[] = $row[0];
		$bnk_name[] = $row[1];
	}
}

// updated by nirmal 05_11_2024 (get user accepted cheques only)
function chquePendingFinalyze()
{
	global $todate, $py_id2, $customer2, $payment_amount2, $chq_date2, $payment_date2, $payment_salesman2, $payment_store2, $cheque_no2, $cheque_name2;
	$sub_system = $_COOKIE['sub_system'];
	$py_id2 = $chq_date = $cheque_name2 = array();
	$user_id = $_COOKIE['user_id'];
	$and_clause = $start_date = '';

	if (isSalesmanPaymentDepositActive()) {
		$start_date = salesmanPaymentDepositStartDate();
		$and_clause = " AND py.`payment_date` < '$start_date'";
	}

	include('config.php');
	if (isset($_GET['dateto'])) {
		$todate = $_GET['dateto'];
		$fromdate = dateNow();
	} else
		$todate = dateNow();

	if (isset($_GET['st'])) {
		if ($_GET['st'] != '') {
			$st_id = $_GET['st'];
			$storefilter = 'AND cu.associated_store=' . $st_id;
		} else
			$storefilter = '';
	} else
		$storefilter = '';

	if (isset($_GET['sm'])) {
		if ($_GET['sm'] != '') {
			$sm_id = $_GET['sm'];
			$salesmanfilter = 'AND py.salesman=' . $sm_id;
		} else
			$salesmanfilter = '';
	} else
		$salesmanfilter = '';

	$query2 = "SELECT py.id,cu.name,round(py.amount),date(py.chque_date),date(py.payment_date),up.username,st.name,py.chque_no,bk.bank_code,py.chque_branch,cn.`name`
	FROM payment py, userprofile up, cust cu, stores st, bank bk, cheque_name cn
	WHERE py.salesman=up.id $and_clause AND py.cust=cu.id AND cu.associated_store=st.id AND py.chque_bank=bk.id AND py.chque_name=cn.id
	AND py.`chque_return`=0 AND py.`payment_type`=2 AND py.`status`=0 AND py.`chque_clear`=0 AND py.`sub_system`='$sub_system' AND date(py.chque_date)<='$todate' $storefilter $salesmanfilter ORDER BY py.chque_date,cu.name";

	$result2 = mysqli_query($conn2, $query2);
	while ($row2 = mysqli_fetch_array($result2)) {
		$py_id2[] = $row2[0];
		$customer2[] = $row2[1];
		$payment_amount2[] = $row2[2];
		$chq_date2[] = $row2[3];
		$payment_date2[] = $row2[4];
		$payment_salesman2[] = $row2[5];
		$payment_store2[] = $row2[6];
		$cheque_no2[] = $row2[7] . '-' . $row2[8] . '-' . str_pad($row2[9], 3, "0", STR_PAD_LEFT);
		$cheque_name2[] = $row2[10];
	}
}

function chquePendingFinalyze2()
{
	global $todate, $py_id2, $customer2, $payment_amount2, $chq_date2, $payment_date2, $payment_salesman2, $payment_store2, $cheque_no2, $cheque_name2;
	$sub_system = $_COOKIE['sub_system'];
	$py_id2 = $chq_date = $cheque_name2 = array();
	$user_id = $_COOKIE['user_id'];
	$and_clause = $where_cluase = $from_clause = '';

	$start_date = salesmanPaymentDepositStartDate();
	$and_clause = " AND ct.`from` = '$user_id' AND ct.`status` IN(1,5) AND ct.`latest` = 1 ";
	$where_cluase = " ct.`payment_id` = py.`id` AND py.`payment_date` > '$start_date' AND ";
	$from_clause = ", cheque_trans ct";

	include('config.php');
	if (isset($_GET['dateto'])) {
		$todate = $_GET['dateto'];
		$fromdate = dateNow();
	} else
		$todate = dateNow();

	if (isset($_GET['st'])) {
		if ($_GET['st'] != '') {
			$st_id = $_GET['st'];
			$storefilter = 'AND cu.associated_store=' . $st_id;
		} else
			$storefilter = '';
	} else
		$storefilter = '';

	if (isset($_GET['sm'])) {
		if ($_GET['sm'] != '') {
			$sm_id = $_GET['sm'];
			$salesmanfilter = 'AND py.salesman=' . $sm_id;
		} else
			$salesmanfilter = '';
	} else
		$salesmanfilter = '';

	$query2 = "SELECT py.id,cu.name,round(py.amount),date(py.chque_date),date(py.payment_date),up.username,st.name,py.chque_no,bk.bank_code,py.chque_branch,cn.`name`
	FROM payment py, userprofile up, cust cu, stores st, bank bk, cheque_name cn $from_clause
	WHERE $where_cluase py.salesman=up.id
	$and_clause AND py.cust=cu.id AND cu.associated_store=st.id AND py.chque_bank=bk.id AND py.chque_name=cn.id
	AND py.`chque_return`=0 AND py.`payment_type`=2 AND py.`status`=0 AND py.`chque_clear`=0 AND py.`sub_system`='$sub_system' AND date(py.chque_date)<='$todate' $storefilter $salesmanfilter ORDER BY py.chque_date,cu.name";

	$result2 = mysqli_query($conn2, $query2);
	while ($row2 = mysqli_fetch_array($result2)) {
		$py_id2[] = $row2[0];
		$customer2[] = $row2[1];
		$payment_amount2[] = $row2[2];
		$chq_date2[] = $row2[3];
		$payment_date2[] = $row2[4];
		$payment_salesman2[] = $row2[5];
		$payment_store2[] = $row2[6];
		$cheque_no2[] = $row2[7] . '-' . $row2[8] . '-' . str_pad($row2[9], 3, "0", STR_PAD_LEFT);
		$cheque_name2[] = $row2[10];
	}
}

// update by nirmal 22_01_07
// updated by nirmal 21_11_2024 show cheques names
function getChqueData($sub_system)
{
	global $customer, $todate, $payment_amount, $chque_no, $chque_bnk_code, $chque_bnk_brn, $chq_date, $payment_date, $payment_salesman,
	$payment_store, $chque_total, $py_id2, $customer2, $payment_amount2, $chq_date2, $invoice,
	$payment_date2, $payment_salesman2, $payment_store2, $cheque_no2, $payment_sub_sys, $cheque_name2;
	$py_id2 = $chq_date = $cheque_name2 = array();
	$sub_sys_filter = $sub_sys_fileter1 = '';
	$user_id = $_COOKIE['user_id'];
	$and_clause = $start_date = '';
	include('config.php');

	if (isSalesmanPaymentDepositActive()) {
		$start_date = salesmanPaymentDepositStartDate();
		$and_clause = " AND py.`payment_date` < '$start_date'";
	}

	if (isset($_GET['dateto'])) {
		$todate = $_GET['dateto'];
	} else
		$todate = dateNow();

	if (isset($_GET['st'])) {
		if ($_GET['st'] != '') {
			$st_id = $_GET['st'];
			$storefilter = 'AND cu.associated_store=' . $st_id;
		} else
			$storefilter = '';
	} else
		$storefilter = '';

	if (isset($_GET['sb'])) {
		if ($_GET['sb'] != '') {
			if ($sub_system != 'all') {
				$sub_system = $_COOKIE['sub_system'];
				$sub_sys_filter = "AND py.`sub_system`='$sub_system'";
			} else {
				$sb_id = $_GET['sb'];
				$sub_sys_filter = "AND py.`sub_system`='$sb_id'";
			}
		} else
			$sub_sys_filter = '';
	} else
		$sub_sys_filter = '';

	if (isset($_GET['sm'])) {
		if ($_GET['sm'] != '') {
			$sm_id = $_GET['sm'];
			$salesmanfilter = 'AND py.salesman=' . $sm_id;
		} else
			$salesmanfilter = '';
	} else
		$salesmanfilter = '';

	if (isset($_GET['bnk'])) {
		if ($_GET['bnk'] != '') {
			if ($_GET['bnk'] == 1)
				$dipfilter = 'AND py.chque_deposit_bank is not null';
			else if ($_GET['bnk'] == 0)
				$dipfilter = 'AND py.chque_deposit_bank is null';
			else
				$dipfilter = '';
		} else
			$dipfilter = '';
	} else
		$dipfilter = '';

	if (isset($_GET['dateto'])) {
		$chque_total = 0;
		$query2 = "SELECT cu.name,round(py.amount),date(py.chque_date),date(py.payment_date),up.username,st.name,py.`sub_system`,py.`chque_no`,bk.`bank_code`,py.`chque_branch`,py.`invoice_no`
		FROM payment py, userprofile up, cust cu, stores st, bank bk
		WHERE py.salesman=up.id $and_clause AND py.cust=cu.id AND cu.associated_store=st.id AND py.chque_bank=bk.id AND py.`chque_return`=0 AND py.`payment_type`=2 AND py.`status`=0 $dipfilter
		$sub_sys_filter AND (date(py.chque_date)<='$todate') $storefilter $salesmanfilter
		ORDER BY py.chque_date,cu.name";

		$result2 = mysqli_query($conn2, $query2);
		while ($row2 = mysqli_fetch_array($result2)) {
			$customer[] = $row2[0];
			$payment_amount[] = $row2[1];
			$chq_date[] = $row2[2];
			$payment_date[] = $row2[3];
			$payment_salesman[] = $row2[4];
			$payment_store[] = $row2[5];
			$payment_sub_sys[] = $row2[6];
			$chque_no[] = $row2[7];
			$chque_bnk_code[] = $row2[8];
			$chque_bnk_brn[] = $row2[9];
			$invoice[] = $row2[10];
			$chque_total += $row2[1];
		}
	}

	if ($sub_system != 'all') {
		$sub_system = $_COOKIE['sub_system'];
		$sub_sys_fileter1 = "AND py.`sub_system`='$sub_system'";
	}

	$query2 = "SELECT py.id,cu.name,round(py.amount),date(py.chque_date),date(py.payment_date),up.username,st.name,py.chque_no,bk.bank_code,py.chque_branch,cn.`name`
	FROM payment py, userprofile up, cust cu, stores st, bank bk, cheque_name cn
	WHERE py.salesman=up.id $and_clause AND py.cust=cu.id AND cu.associated_store=st.id AND py.chque_bank=bk.id AND py.chque_name=cn.id AND
	py.`chque_return`=0 AND py.`payment_type`=2 AND py.`status`=0 AND py.`chque_clear`=0  $sub_sys_fileter1
	AND date(py.chque_date)<='$todate' $storefilter $salesmanfilter ORDER BY py.chque_date,cu.name";

	$result2 = mysqli_query($conn2, $query2);
	while ($row2 = mysqli_fetch_array($result2)) {
		$py_id2[] = $row2[0];
		$customer2[] = $row2[1];
		$payment_amount2[] = $row2[2];
		$chq_date2[] = $row2[3];
		$payment_date2[] = $row2[4];
		$payment_salesman2[] = $row2[5];
		$payment_store2[] = $row2[6];
		$cheque_no2[] = $row2[7] . '-' . $row2[8] . '-' . str_pad($row2[9], 3, "0", STR_PAD_LEFT);
		$cheque_name2[] = $row2[10];
	}
}

function getChqueData2($sub_system)
{
	global $customer, $todate, $payment_amount, $chque_no, $chque_bnk_code, $chque_bnk_brn, $chq_date, $payment_date, $payment_salesman,
	$payment_store, $chque_total, $py_id2, $customer2, $payment_amount2, $chq_date2, $invoice,
	$payment_date2, $payment_salesman2, $payment_store2, $cheque_no2, $payment_sub_sys, $cheque_name2;
	$py_id2 = $chq_date = $cheque_name2 = array();
	$sub_sys_filter = $sub_sys_fileter1 = '';
	$user_id = $_COOKIE['user_id'];
	$and_clause = $where_cluase = $from_clause = '';
	include('config.php');

	$start_date = salesmanPaymentDepositStartDate();
	$and_clause = " ct.`from` = '$user_id' AND ct.`status` IN(1,5) AND ct.`latest` = 1 AND ";
	$where_cluase = " ct.`payment_id` = py.`id` AND py.`payment_date` > '$start_date' AND ";
	$from_clause = ", cheque_trans ct";

	if (isset($_GET['dateto'])) {
		$todate = $_GET['dateto'];
	} else
		$todate = dateNow();

	if (isset($_GET['st'])) {
		if ($_GET['st'] != '') {
			$st_id = $_GET['st'];
			$storefilter = 'AND cu.associated_store=' . $st_id;
		} else
			$storefilter = '';
	} else
		$storefilter = '';

	if (isset($_GET['sb'])) {
		if ($_GET['sb'] != '') {
			if ($sub_system != 'all') {
				$sub_system = $_COOKIE['sub_system'];
				$sub_sys_filter = "AND py.`sub_system`='$sub_system'";
			} else {
				$sb_id = $_GET['sb'];
				$sub_sys_filter = "AND py.`sub_system`='$sb_id'";
			}
		} else
			$sub_sys_filter = '';
	} else
		$sub_sys_filter = '';

	if (isset($_GET['sm'])) {
		if ($_GET['sm'] != '') {
			$sm_id = $_GET['sm'];
			$salesmanfilter = 'AND py.salesman=' . $sm_id;
		} else
			$salesmanfilter = '';
	} else
		$salesmanfilter = '';

	if (isset($_GET['bnk'])) {
		if ($_GET['bnk'] != '') {
			if ($_GET['bnk'] == 1)
				$dipfilter = 'AND py.chque_deposit_bank is not null';
			else if ($_GET['bnk'] == 0)
				$dipfilter = 'AND py.chque_deposit_bank is null';
			else
				$dipfilter = '';
		} else
			$dipfilter = '';
	} else
		$dipfilter = '';

	if (isset($_GET['dateto'])) {
		$chque_total = 0;
		$query2 = "SELECT cu.name,round(py.amount),date(py.chque_date),date(py.payment_date),up.username,st.name,py.`sub_system`,py.`chque_no`,bk.`bank_code`,py.`chque_branch`,py.`invoice_no`
		FROM payment py, userprofile up, cust cu, stores st, bank bk $from_clause
		WHERE $where_cluase $and_clause py.salesman=up.id AND py.cust=cu.id AND cu.associated_store=st.id AND py.chque_bank=bk.id AND py.`chque_return`=0 AND py.`payment_type`=2 AND py.`status`=0 $dipfilter
		$sub_sys_filter AND (date(py.chque_date)<='$todate') $storefilter $salesmanfilter
		ORDER BY py.chque_date,cu.name";

		$result2 = mysqli_query($conn2, $query2);
		while ($row2 = mysqli_fetch_array($result2)) {
			$customer[] = $row2[0];
			$payment_amount[] = $row2[1];
			$chq_date[] = $row2[2];
			$payment_date[] = $row2[3];
			$payment_salesman[] = $row2[4];
			$payment_store[] = $row2[5];
			$payment_sub_sys[] = $row2[6];
			$chque_no[] = $row2[7];
			$chque_bnk_code[] = $row2[8];
			$chque_bnk_brn[] = $row2[9];
			$invoice[] = $row2[10];
			$chque_total += $row2[1];
		}
	}

	if ($sub_system != 'all') {
		$sub_system = $_COOKIE['sub_system'];
		$sub_sys_fileter1 = "AND py.`sub_system`='$sub_system'";
	}

	$query2 = "SELECT py.id,cu.name,round(py.amount),date(py.chque_date),date(py.payment_date),up.username,st.name,py.chque_no,bk.bank_code,py.chque_branch,cn.`name`
	FROM payment py, userprofile up, cust cu, stores st, bank bk, cheque_name cn $from_clause
	WHERE $where_cluase $and_clause py.salesman=up.id AND py.cust=cu.id AND cu.associated_store=st.id AND py.chque_bank=bk.id AND py.chque_name=cn.id AND
	py.`chque_return`=0 AND py.`payment_type`=2 AND py.`status`=0 AND py.`chque_clear`=0  $sub_sys_fileter1
	AND date(py.chque_date)<='$todate' $storefilter $salesmanfilter ORDER BY py.chque_date,cu.name";

	$result2 = mysqli_query($conn2, $query2);
	while ($row2 = mysqli_fetch_array($result2)) {
		$py_id2[] = $row2[0];
		$customer2[] = $row2[1];
		$payment_amount2[] = $row2[2];
		$chq_date2[] = $row2[3];
		$payment_date2[] = $row2[4];
		$payment_salesman2[] = $row2[5];
		$payment_store2[] = $row2[6];
		$cheque_no2[] = $row2[7] . '-' . $row2[8] . '-' . str_pad($row2[9], 3, "0", STR_PAD_LEFT);
		$cheque_name2[] = $row2[10];
	}
}

// update by nirmal 29_02_2024 (Added quickbooks payment add)
function clearChque()
{
	$id = $_POST['id'];
	$bnkac_name = $_POST['bnk'];
	$pydate = $_POST['pydate'];
	$deposit_by = $_COOKIE['user_id'];
	$out = true;
	$ac_id = $qb_msg = $qb_cheque_payment_result = '';
	$msg = "Done";
	include('config.php');

	try {
		// Start the transaction
		mysqli_begin_transaction($conn);

		if ($bnkac_name != "") {
			$query = "SELECT id FROM accounts WHERE name='$bnkac_name'";
			$result = mysqli_query($conn2, $query);
			$row = mysqli_fetch_assoc($result);
			$ac_id = $row['id'];
		}
		if ($ac_id == '') {
			$out = false;
			$msg = "Error: Bank account not found";
			throw new Exception($msg);
		}

		if ($out) {
			$query = "UPDATE `payment` SET `chque_clear`='1',`chque_deposit_bank`='$ac_id', `chque_deposit_date`='$pydate', `chque_deposit_by`='$deposit_by' WHERE `id`='$id'";
			$result = mysqli_query($conn, $query);
			if (!$result) {
				$out = false;
				$msg = "Error: DB could not be updated";
				throw new Exception($msg);
			}
		}

		if ($out) {
			if (isQuickBooksActive(1)) {
				$query = "SELECT py.invoice_no, cu.qb_cust_id, py.amount, py.cust, py.chque_no, py.chque_branch, bk.bank_code, cu.`name` FROM payment py  LEFT JOIN cust cu ON py.cust = cu.id  LEFT JOIN bill_main bm ON bm.invoice_no = py.invoice_no LEFT JOIN bank bk ON py.chque_bank = bk.id WHERE py.id = '$id'";
				$row = mysqli_fetch_row(mysqli_query($conn2, $query));
				if (!empty($row)) {
					$invoice_no = $row[0];
					$amount = $row[2];
					$custName = $row[7];
					$cheque_no = $row[4] . '-' . $row[6] . '-' . str_pad($row[5], 3, "0", STR_PAD_LEFT);
					$debitAccountName = $bnkac_name;
					$creditAccountName = "Undeposited Funds";
					$description = "[PAYMENT] - Method: Cheque Payment ($bnkac_name), Check Number $cheque_no";
					if ($invoice_no != 0) {
						$description .= ", Invoice No: $invoice_no";
					}
					$description .= ", Customer : $custName";
					$debitEntityType = "";
					$debitEntityID = "";
					$creditEntityType = "";
					$creditEntityID = "";

					$journalEntryForCheque = buildJournalEntry($conn2, $amount, $debitAccountName, $creditAccountName, $description, $debitEntityType, $debitEntityID, $creditEntityType, $creditEntityID);
					if (isset($journalEntryForCheque['error'])) {
						$out = false;
						$qb_msg = $journalEntryForCheque['error'];
						throw new Exception($qb_msg);
					} else {
						try {
							$journal_entry_result = QBAddJournalEntry($journalEntryForCheque);
							$qb_msg = $journal_entry_result['message'];
							if ((isset($journal_entry_result['status'])) && ($journal_entry_result['status'] != 'success')) {
								$out = false;
								throw new Exception($qb_msg);
							}
						} catch (Exception $e) {
							$out = false;
							$qb_msg = "<br>QuickBooks error: " . $e->getMessage();
							$journal_entry_result['status'] = 'error';
							throw new Exception($qb_msg);
						}
					}
				}
			}
		}

		// Commit the transaction
		mysqli_commit($conn);
	} catch (Exception $e) {
		mysqli_rollback($conn);
		$msg = $e->getMessage();
		error_log("Error in clearChque(): " . $msg); // Log the error
	}
	return $msg . '|' . $qb_msg;
}

// added by nirmal 05_11_2024
function clearChque2()
{
	include('config.php');
	$deposit_by = $_COOKIE['user_id'];
	$out = true;
	$qb_msg = '';
	$responses = []; // Initialize an array to hold responses for each cheque
	$currentId = null; // Track the current ID being processed

	// Check if 'data' parameter is set
	if (isset($_POST['data'])) {
		try {
			// Start the transaction
			mysqli_begin_transaction($conn);

			// Decode the JSON data and handle potential errors
			$depositData = json_decode($_POST['data'], true);
			if ($depositData === null && json_last_error() !== JSON_ERROR_NONE) {
				echo json_encode(['success' => false, 'message' => 'Invalid JSON data received.']);
				throw new Exception("Invalid JSON data received.");
			}

			// Loop through each cheque data
			foreach ($depositData as $item) {
				$qb_msg = '';
				$id = $item['id'];
				$currentId = $item['id']; // Track the current ID being processed
				$bnkac_name = $item['bnk'];
				$pydate = $item['pydate'];
				$amount = $item['amount']; // Currently not used, but available if needed

				// TEMPORARY TEST CODE END
				if ($bnkac_name != "") {
					$query = "SELECT id FROM accounts WHERE name='$bnkac_name'";
					$result = mysqli_query($conn2, $query);
					$row = mysqli_fetch_assoc($result);
					$ac_id = isset($row['id']) ? $row['id'] : null;
				}

				if (empty($ac_id)) {
					$out = false;
					$msg = "Error: Bank account not found for cheque ID $id";
					$responses[] = ['success' => false, 'message' => $msg, 'chequeId' => $id];
					throw new Exception($msg);
				}

				if ($out) {
					// Update payment table for the current cheque
					$query = "UPDATE `payment` SET `chque_clear`='1', `chque_deposit_bank`='$ac_id', `chque_deposit_date`='$pydate', `chque_deposit_by`='$deposit_by' WHERE `id`='$id'";
					if (mysqli_query($conn, $query)) {
						$responses[] = ['success' => true, 'message' => 'Status updated successfully.', 'chequeId' => $id];
					} else {
						$out = false;
						$msg = 'Failed to update status: ' . mysqli_error($conn);
						$responses[] = ['success' => $out, 'message' => $msg, 'chequeId' => $id];
						throw new Exception($msg);
					}
				}
				if ($out) {
					if (isQuickBooksActive(1)) {
						$journal_entry_result = $journalEntryForCheque = [];

						$query = "SELECT py.invoice_no, cu.qb_cust_id, py.amount, py.cust, py.chque_no, py.chque_branch, bk.bank_code, cu.`name` FROM payment py  LEFT JOIN cust cu ON py.cust = cu.id  LEFT JOIN bill_main bm ON bm.invoice_no = py.invoice_no LEFT JOIN bank bk ON py.chque_bank = bk.id WHERE py.id = '$id'";
						$row = mysqli_fetch_row(mysqli_query($conn2, $query));
						if (!empty($row)) {
							$invoice_no = $row[0];
							$amount = $row[2];
							$custName = $row[7];
							$cheque_no = $row[4] . '-' . $row[6] . '-' . str_pad($row[5], 3, "0", STR_PAD_LEFT);
							$debitAccountName = $bnkac_name;
							$creditAccountName = "Undeposited Funds";
							$description = "[PAYMENT] - Method: Cheque Payment ($bnkac_name), Check Number $cheque_no";
							if ($invoice_no != 0) {
								$description .= ", Invoice No: $invoice_no";
							}
							$description .= ", Customer : $custName";
							$debitEntityType = "";
							$debitEntityID = "";
							$creditEntityType = "";
							$creditEntityID = "";

							$journalEntryForCheque = buildJournalEntry($conn2, $amount, $debitAccountName, $creditAccountName, $description, $debitEntityType, $debitEntityID, $creditEntityType, $creditEntityID);
							if (isset($journalEntryForCheque['error'])) {
								$out = false;
								$qb_msg = $journalEntryForCheque['error'];
								$responses[] = ['success' => $out, 'message' => $qb_msg, 'chequeId' => $id];
								throw new Exception($qb_msg);
							} else {
								try {
									$journal_entry_result = QBAddJournalEntry($journalEntryForCheque);
									$qb_msg = $journal_entry_result['message'];
									if (isset($journal_entry_result['status']) && ($journal_entry_result['status'] == 'success')) {
										$qb_journal_entry_id = $journal_entry_result['qb_journal_entry_id'];
										$query = "UPDATE `payment_deposit` SET `qb_id`='$qb_journal_entry_id' WHERE `payment_id`='$id'";
										$result1 = mysqli_query($conn, $query);
										if (!$result1) {
											$out = false;
											$qb_msg = "QuickBooks payment ID update error";
											$responses[] = ['success' => $out, 'message' => $qb_msg, 'chequeId' => $id];
											throw new Exception($qb_msg);
										} else {
											$qb_msg = "Journal Entry successfully recorded in QuickBooks. Cheque Number: $cheque_no";
											$responses[] = ['success' => true, 'message' => "Status updated successfully. $qb_msg", 'chequeId' => $id];
										}
									} else {
										$out = false;
										$responses[] = ['success' => $out, 'message' => $qb_msg, 'chequeId' => $id];
										throw new Exception($qb_msg);
									}
								} catch (Exception $e) {
									$out = false;
									$qb_msg = "<br> QuickBooks Error: Unable to record the cheque. Cheque Number: $cheque_no, Error: " . $error->getResponseBody();
									$responses[] = ['success' => $out, 'message' => $qb_msg, 'chequeId' => $id];
									$journal_entry_result['status'] = 'error';
									throw new Exception($qb_msg);
								}
							}
						}
					}
				}
			}
			// Return the array of responses as JSON
			echo json_encode($responses);
			// Commit the transaction
			mysqli_commit($conn);
		} catch (Exception $e) {
			mysqli_rollback($conn);
			$message = $e->getMessage();

			// If responses array is empty, create a generic error response
			if (empty($responses)) {
				$responses[] = [
					'success' => false,
					'message' => $message,
					'chequeId' => $currentId  // Use the tracked ID
				];
			}

			// Add a final error response with the current ID being processed
			$responses[] = [
				'success' => false,
				'message' => "Transaction rolled back. Error: " . $message,
				'chequeId' => $currentId
			];

			// Return the array of responses as JSON
			echo json_encode($responses);
			error_log("Error in clearChque2(): " . $message); // Log the error
		}
	} else {
		// Return error response for missing 'data' parameter
		echo json_encode(['success' => false, 'message' => 'Invalid input parameters.']);
	}
}


// updated by nirmal 21_12_3
// update by nirmal 02_10_2024 (show all payment without consider sub system if module is top-manager)
// update by nirmal 24_12_2024 (added payment number)

function getClearedChques()
{
	global $from_date, $to_date, $bnk, $py_date, $invoice, $chque_no, $chque_bnk_code, $chque_bnk_brn,
	$chque_bnk_name, $chque_date, $cust, $deposit_date, $deposit_bnk, $salesman, $deposit_by, $amount, $payment_id;
	$qry_bnk = $qry_date = $qry_year = $filter_year = '';
	$py_date = $payment_id = array();
	$sub_sys_qry = '';
	include('config.php');

	if (isset($_REQUEST['year']))
		$filter_year = $_REQUEST['year'];
	if ($filter_year != '')
		$qry_year = "AND py.chque_date LIKE '$filter_year%'";

	if ((isset($_REQUEST['from_date'])) && (isset($_REQUEST['to_date']))) {
		$from_date = $_REQUEST['from_date'];
		$to_date = $_REQUEST['to_date'];
		if (($from_date != '') && ($to_date != ''))
			$qry_date = " AND py.`chque_deposit_date` BETWEEN '$from_date' AND '$to_date'";
	}

	if ((isset($_REQUEST['bnk']))) {
		$bnk = $_REQUEST['bnk'];
		if ($bnk != '')
			$qry_bnk = "AND bk2.id ='$bnk'";
	}
	if ((isset($_REQUEST["components"]) && ($_REQUEST['components'] != 'topmanager'))) {
		$sub_system = $_COOKIE['sub_system'];
		$sub_sys_qry = "AND py.`sub_system`='$sub_system'";
	}

	$query = "SELECT date(py.payment_date),py.invoice_no,py.chque_no,bk1.bank_code,py.chque_branch,bk1.name,py.chque_date,cu.name,py.chque_deposit_date,bk2.name,up1.username,up2.username,py.amount,py.id
			FROM payment py, cust cu, bank bk1, accounts bk2, userprofile up1 , userprofile up2
			WHERE py.chque_bank=bk1.id AND py.chque_deposit_bank=bk2.id AND py.`cust`=cu.id AND py.salesman=up1.id AND py.chque_deposit_by=up2.id AND py.payment_type='2' AND py.`status`='0' AND py.chque_clear='1' $sub_sys_qry $qry_year
			$qry_bnk $qry_date ORDER BY py.chque_date DESC";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$py_date[] = $row[0];
		$invoice[] = $row[1];
		$chque_no[] = $row[2];
		$chque_bnk_code[] = $row[3];
		$chque_bnk_brn[] = $row[4];
		$chque_bnk_name[] = $row[5];
		$chque_date[] = $row[6];
		$cust[] = $row[7];
		$deposit_date[] = $row[8];
		$deposit_bnk[] = $row[9];
		$salesman[] = $row[10];
		$deposit_by[] = $row[11];
		$amount[] = $row[12];
		$payment_id[] = $row[13];
	}
}

// updated by nirmal 21_12_19
function getChqueRange($sub_system)
{
	global $from_date, $to_date, $py_date, $invoice, $chque_no, $chque_bnk_code, $chque_bnk_brn,
	$chque_bnk_name, $chque_date, $cust, $deposit_date, $deposit_bnk, $salesman, $deposit_by,
	$amount, $chque_clear, $chque_return, $payment_sub_sys;
	$py_date = array();
	$sub_sys_filter = '';

	if ((isset($_REQUEST['from_date'])) && (isset($_REQUEST['to_date']))) {
		$from_date = $_REQUEST['from_date'];
		$to_date = $_REQUEST['to_date'];

		if (isset($_GET['st'])) {
			if ($_GET['st'] != '') {
				$st_id = $_GET['st'];
				$storefilter = 'AND cu.associated_store=' . $st_id;
			} else
				$storefilter = '';
		} else
			$storefilter = '';

		if (isset($_GET['sm'])) {
			if ($_GET['sm'] != '') {
				$sm_id = $_GET['sm'];
				$salesmanfilter = 'AND py.salesman=' . $sm_id;
			} else
				$salesmanfilter = '';
		} else
			$salesmanfilter = '';

		if (isset($_GET['sb'])) {
			if ($_GET['sb'] != '') {
				if ($sub_system != 'all') {
					$sub_system = $_COOKIE['sub_system'];
					$sub_sys_filter = "AND py.`sub_system`='$sub_system'";
				} else {
					$sb_id = $_GET['sb'];
					$sub_sys_filter = "AND py.`sub_system`='$sb_id'";
				}
			} else
				$sub_sys_filter = '';
		} else
			$sub_sys_filter = '';

		if (isset($_GET['bnk'])) {
			if ($_GET['bnk'] != '') {
				if ($_GET['bnk'] == 1)
					$dipfilter = 'AND py.chque_deposit_bank is not null';
				else if ($_GET['bnk'] == 0)
					$dipfilter = 'AND py.chque_deposit_bank is null';
				else
					$dipfilter = '';
			} else
				$dipfilter = '';
		} else
			$dipfilter = '';

		include('config.php');

		$query = "SELECT id,name,bank_code FROM bank";
		$result = mysqli_query($conn2, $query);
		while ($row = mysqli_fetch_array($result)) {
			$bnk_name[$row[0]] = $row[1];
			$bnk_code[$row[0]] = $row[2];
		}

		$query = "SELECT id,name FROM accounts";
		$result = mysqli_query($conn2, $query);
		while ($row = mysqli_fetch_array($result)) {
			$acc_name[$row[0]] = $row[1];
		}

		$query = "SELECT id,username FROM userprofile WHERE `status`=0";
		$result = mysqli_query($conn2, $query);
		while ($row = mysqli_fetch_array($result)) {
			$up_name[$row[0]] = $row[1];
		}

		$query = "SELECT date(py.payment_date),py.invoice_no,py.chque_no,py.chque_bank,py.chque_branch,py.chque_date,
		cu.name,py.chque_deposit_date,py.chque_deposit_bank,py.salesman,py.chque_deposit_by,py.amount,py.chque_clear,py.chque_return
		FROM cust cu, payment py, userprofile up, stores st
		WHERE  py.salesman=up.id AND py.cust=cu.id AND cu.associated_store=st.id $sub_sys_filter AND py.payment_type='2'
		AND py.`status`='0' $dipfilter AND py.chque_date BETWEEN '$from_date' AND '$to_date' $storefilter $salesmanfilter
		ORDER BY py.chque_date DESC";

		$result = mysqli_query($conn2, $query);
		while ($row = mysqli_fetch_array($result)) {
			$py_date[] = $row[0];
			$invoice[] = $row[1];
			$chque_no[] = $row[2];
			$chque_bnk_code[] = $bnk_code[$row[3]];
			$chque_bnk_brn[] = $row[4];
			$chque_bnk_name[] = $bnk_name[$row[3]];
			$chque_date[] = $row[5];
			$cust[] = $row[6];
			$deposit_date[] = $row[7];
			if ($row[8] != '')
				$deposit_bnk[] = $acc_name[$row[8]];
			else
				$deposit_bnk[] = '';
			$salesman[] = $up_name[$row[9]];
			if ($row[10] != '')
				$deposit_by[] = $up_name[$row[10]];
			else
				$deposit_by[] = '';
			$amount[] = $row[11];
			$chque_clear[] = $row[12];
			$chque_return[] = $row[13];
		}
	}
}


//-----------------------------------Authorize Code-------------------------------------//
function getAuthorizeCodelist()
{
	global $tmp_bm_no_list, $tmp_code_list, $tmp_inv_total, $invoice_no_list, $code_list, $inv_total;
	$tmp_bm_no_list = $tmp_code_list = $invoice_no_list = $code_list = array();
	$sub_system = $_COOKIE['sub_system'];
	include('config.php');
	$query = "SELECT bm.bm_no,bm.authorize_code,SUM(bi.qty * bi.unit_price) FROM bill_main_tmp bm, bill_tmp bi WHERE bm.bm_no=bi.bm_no AND bm.sub_system='$sub_system' GROUP BY bm.bm_no ORDER BY bm.bm_no DESC LIMIT 5";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$tmp_bm_no_list[] = $row[0];
		$tmp_code_list[] = $row[1];
		$tmp_inv_total[] = $row[2];
	}
	$query = "SELECT bm.invoice_no,bm.authorize_code,SUM(bi.qty * bi.unit_price) FROM bill_main bm, bill bi WHERE bm.invoice_no=bi.invoice_no AND bm.sub_system='$sub_system' GROUP BY bm.invoice_no ORDER BY bm.invoice_no DESC LIMIT 5";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$invoice_no_list[] = $row[0];
		$code_list[] = $row[1];
		$inv_total[] = $row[2];
	}
}

function getAuthorize2()
{
	$invoice_no = $_GET['invoice_no'];
	$jasonArray = array();
	include('config.php');
	if (substr($invoice_no, 0, 1) == 'T') {
		$invoice_no = substr($invoice_no, 1);
		$query = "SELECT bm.authorize_code,SUM(bi.qty * bi.unit_price) FROM bill_main_tmp bm, bill_tmp bi WHERE bm.bm_no=bi.bm_no AND bm.bm_no='$invoice_no'";
		$row = mysqli_fetch_row(mysqli_query($conn2, $query));
		$authorize_code = $row[0];
		$invoice_total = $row[1];
	} else {
		$query = "SELECT bm.authorize_code,SUM(bi.qty * bi.unit_price) FROM bill_main bm, bill bi WHERE bm.invoice_no=bi.invoice_no AND bm.invoice_no='$invoice_no'";
		$row = mysqli_fetch_row(mysqli_query($conn2, $query));
		$authorize_code = $row[0];
		$invoice_total = $row[1];
	}
	$jasonArray['auth_code'] = $authorize_code;
	$jasonArray['inv_total'] = $invoice_total;

	$myJSON = json_encode($jasonArray);
	return $myJSON;
}
//----------------------------------------------------------------------------------------//

function getSubSystems()
{
	global $sub_system_list, $sub_system_names;
	include('config.php');
	$query = "SELECT id,name FROM sub_system WHERE `status`=1 AND id!=0";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$sub_system_list[] = $row[0];
		$sub_system_names[] = $row[1];
	}
}

function getSubSystems2()
{
	global $sub_system_list, $sub_system_names;
	include('config.php');
	$query = "SELECT id,name FROM sub_system WHERE `status`=1";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$sub_system_list[] = $row[0];
		$sub_system_names[] = $row[1];
	}
}

// added by nirmal 21_12_29
function getSubSystems3($sub_system)
{
	global $sb_id, $sb_name;
	include('config.php');
	$user_id = $_COOKIE['user_id'];

	if ($sub_system == 'all') {
		$query = "SELECT `id`,`name` FROM sub_system WHERE `status`='1'";
	} else {
		$query = "SELECT s.`id`,s.`name` FROM sub_system s, userprofile u WHERE s.`status`='1' AND s.`id`=u.`sub_system` AND u.`id`='$user_id'";
	}
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$sb_id[] = $row[0];
		$sb_name[] = $row[1];
	}
}

function getPaySubStatus($status_id)
{
	$status = $color = '';
	switch ($status_id) {
		case 0:
			$status = 'Deleted';
			$color = 'orange';
			break;
		case 1:
			$status = 'Pending';
			$color = 'blue';
			break;
		case 2:
			$status = 'Accepted';
			$color = 'green';
			break;
		case 3:
			$status = 'Rejected';
			$color = 'orange';
			break;
		case 4:
			$status = 'Cheque Return';
			$color = 'red';
			break;
	}
	return $status . '|' . $color;
}

function getPaymentData($sub_system)
{
	global $bm_total, $py_total, $last_id, $last_amount, $last_submited_by, $last_submited_date, $last_processed_by, $last_processed_date, $last_status, $pending_id, $pending_amount, $pending_submited_by, $pending_submited_date, $pending_submited_time, $cheque_py_id, $cheque_no, $cheque_amount;
	$cheque_py_id = $last_id = $pending_id = array();
	include('config.php');
	$query = "SELECT SUM(bi.qty * bi.unit_price) FROM bill_main bm, bill bi WHERE bm.invoice_no=bi.invoice_no AND bm.`status` NOT IN (0,7) AND bm.`lock`=1 AND bm.exclude=0 AND bm.sub_system='$sub_system'";
	$row = mysqli_fetch_row(mysqli_query($conn2, $query));
	$bm_total = $row[0];

	$query = "SELECT SUM(amount) FROM payment_subsys WHERE `status`=2 AND sub_system='$sub_system'";
	$row = mysqli_fetch_row(mysqli_query($conn2, $query));
	$py_total = $row[0];

	$query = "SELECT py.id,py.chque_no,bk.bank_code,py.chque_branch,py.amount FROM payment py, bank bk WHERE py.chque_bank=bk.id AND py.payment_type=2 AND py.`status`=0 AND py.chque_return=0 AND py.chque_clear=0 AND py.chque_submit=0 AND py.`sub_system`='$sub_system' ORDER BY chque_no";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$cheque_py_id[] = $row[0];
		$cheque_no[] = $row[1] . ' : ' . $row[2] . ' : ' . $row[3];
		$cheque_amount[] = $row[4];
	}

	$query = "SELECT ps.id,ps.amount,up1.username,date(ps.submited_date),up2.username,ps.processed_date,ps.`status` FROM userprofile up1, payment_subsys ps LEFT JOIN userprofile up2 ON ps.processed_by=up2.id WHERE ps.submited_by=up1.id AND ps.`status` IN (0,2,3,4) AND ps.sub_system='$sub_system' ORDER BY id DESC LIMIT 10";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$last_id[] = $row[0];
		$last_amount[] = $row[1];
		$last_submited_by[] = $row[2];
		$last_submited_date[] = $row[3];
		$last_processed_by[] = $row[4];
		$last_processed_date[] = $row[5];
		$last_status[] = getPaySubStatus($row[6]);
	}

	$query = "SELECT ps.id,ps.amount,up.username,date(ps.submited_date),time(ps.submited_date) FROM payment_subsys ps, userprofile up WHERE ps.submited_by=up.id AND ps.`status`='1' AND ps.sub_system='$sub_system'";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$pending_id[] = $row[0];
		$pending_amount[] = $row[1];
		$pending_submited_by[] = $row[2];
		$pending_submited_date[] = $row[3];
		$pending_submited_time[] = $row[4];
	}
}

function addPayment($sub_system)
{
	global $message;
	$user_id = $_COOKIE['user_id'];
	$payment_type = $_POST['payment_type'];
	$amount = $_POST['amount'];
	$cheque_py = $_POST['cheque_py'];  //cheques directly from customer
	$chque_no = $_POST['chque_no'];
	$chque_bank = $_POST['chque_bank'];
	$chque_branch = $_POST['chque_branch'];
	$chque_date = $_POST['chque_date'];
	$amount = $_POST['amount'];
	$datetime = timeNow();
	$qry_cols = $qry_values = '';
	$out = false;

	include('config.php');

	if ($payment_type == 2) {
		if ($cheque_py != '') {
			$query = "SELECT amount,chque_no,chque_bank,chque_branch,chque_date FROM payment WHERE id='$cheque_py'";
			$row = mysqli_fetch_row(mysqli_query($conn, $query));
			$amount = $row[0];
			$chque_no = $row[1];
			$chque_bank = $row[2];
			$chque_branch = $row[3];
			$chque_date = $row[4];
			$qry_cols = ",`cust_chq`,`chque_no`,`chque_bank`,`chque_branch`,`chque_date`";
			$qry_values = ",'$cheque_py','$chque_no','$chque_bank','$chque_branch','$chque_date'";
		} else {
			$query = "SELECT id FROM bank WHERE bank_code='$chque_bank'";
			$row = mysqli_fetch_row(mysqli_query($conn, $query));
			$chque_bank = $row[0];
			$qry_cols = ",`chque_no`,`chque_bank`,`chque_branch`,`chque_date`";
			$qry_values = ",'$chque_no','$chque_bank','$chque_branch','$chque_date'";
		}
	}

	$query = "INSERT INTO `payment_subsys` (`amount`,`type`,`submited_by`,`submited_date`,`sub_system`,`status` $qry_cols) VALUES ('$amount','$payment_type','$user_id','$datetime','$sub_system','1' $qry_values)";
	$result = mysqli_query($conn, $query);
	if ($result)
		$out = true;
	else
		$msg = 'The Payment could not be Submited !';

	if ($out) {
		if (($payment_type == 2) && ($cheque_py != '')) {
			$query1 = "UPDATE `payment` SET `chque_submit`='1' WHERE `id`='$cheque_py' ";
			$result1 = mysqli_query($conn, $query1);
			if ($result1)
				$out = true;
			else
				$msg = 'Error: Cust payment table could not be updated. Please contact NegoIT !';
		}
	}

	if ($out) {
		$message = 'The Payment was Submited Successfully!';
		return true;
	} else {
		$message = $msg;
		return false;
	}
}

function deletePayment($sub_system)
{
	global $message;
	$id = $_GET['id'];
	$out = false;
	$msg = '';
	include('config.php');

	$query = "SELECT `sub_system`,`status`,cust_chq FROM payment_subsys WHERE id='$id'";
	$row = mysqli_fetch_row(mysqli_query($conn, $query));
	$pay_sub_system = $row[0];
	$py_status = $row[1];
	$py_cust_chq = $row[2];
	if (($pay_sub_system == $sub_system) && ($py_status == 1)) {
		$query1 = "UPDATE `payment_subsys` SET `status`='0' WHERE `id`='$id'";
		$result1 = mysqli_query($conn, $query1);
		if ($result1)
			$out = true;
		else
			$msg = 'Error: The Payment Could Not Be Deleted';
	} else
		$msg = 'Unauthorized Request';

	if (($out) && ($py_cust_chq != '')) {
		$query1 = "UPDATE `payment` SET `chque_submit`='0' WHERE `id`='$py_cust_chq' ";
		$result1 = mysqli_query($conn, $query1);
		if (!$result1) {
			$out = false;
			$msg = 'Error: Cust payment table could not be updated. Please contact NegoIT !';
		}
	}

	if ($out) {
		$message = 'The Payment was Deleted Successfully!';
		return true;
	} else {
		$message = $msg;
		return false;
	}
}

function getOnePayment()
{
	global $ps_amount, $ps_type, $ps_chque_no, $ps_chque_bank, $ps_chque_branch, $ps_chque_date, $ps_chque_return_date, $ps_submited_by, $ps_submited_date, $ps_processed_by, $ps_processed_date, $ps_status, $ps_cust_chq;
	if (isset($_GET['pay_id'])) {
		$payment_id = $_GET['pay_id'];
		include('config.php');
		$query = "SELECT ps.amount,ps.`type`,ps.chque_no,ps.chque_bank,ps.chque_branch,ps.chque_date,ps.chque_return_date,up1.username,ps.submited_date,up2.username,ps.processed_date,ps.`status`,ps.cust_chq FROM userprofile up1, payment_subsys ps LEFT JOIN userprofile up2 ON ps.processed_by=up2.id WHERE ps.submited_by=up1.id AND ps.id='$payment_id'";
		$row = mysqli_fetch_row(mysqli_query($conn2, $query));
		$ps_amount = $row[0];
		$ps_type = $row[1];
		$ps_chque_no = $row[2];
		$ps_chque_bank = $row[3];
		$ps_chque_branch = $row[4];
		$ps_chque_date = $row[5];
		$ps_chque_return_date = $row[6];
		$ps_submited_by = $row[7];
		$ps_submited_date = $row[8];
		$ps_processed_by = $row[9];
		$ps_processed_date = $row[10];
		$ps_status = getPaySubStatus($row[11]);
		$ps_cust_chq = $row[12];
	}
}

function getBank2()
{
	global $bank_id, $bank_code, $bank_name;
	include('config.php');
	$query = "SELECT id,bank_code,name FROM bank WHERE `status`='1'";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$bank_id[] = $row[0];
		$bank_code[] = $row[1];
		$bank_name[] = $row[2];
	}
}

function getPaymentHistory($sub_system)
{
	global $from_date, $to_date, $payment_id, $ps_amount, $ps_type, $ps_chque_no, $ps_chque_bank, $ps_chque_branch, $ps_chque_date, $ps_chque_return_date, $ps_submited_by, $ps_submited_date, $ps_processed_by, $ps_processed_date, $ps_status, $ps_status_code, $ps_cust_chq, $balance_before_from, $balance_within_period, $balance_after_to, $store_company;
	$store = $_COOKIE['store'];
	$balance_before_from = $balance_within_period = $balance_after_to = 0;
	if (isset($_GET['from_date']) && isset($_GET['to_date'])) {
		$from_date = $_GET['from_date'];
		$to_date = $_GET['to_date'];
	} else {
		$from_date = date("Y-m-d", time() - (60 * 60 * 24 * 30));
		$to_date = dateNow();
	}
	$ps_amount = array();
	include('config.php');
	$query = "SELECT ps.amount,ps.`type`,ps.chque_no,ps.chque_bank,ps.chque_branch,ps.chque_date,ps.chque_return_date,up1.username,ps.submited_date,up2.username,ps.processed_date,ps.`status`,ps.cust_chq FROM userprofile up1, payment_subsys ps LEFT JOIN userprofile up2 ON ps.processed_by=up2.id WHERE ps.submited_by=up1.id AND ps.`status` IN (2,4) AND date(ps.processed_date) BETWEEN '$from_date' AND '$to_date' AND ps.`sub_system`='$sub_system' ORDER BY ps.processed_date DESC";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$ps_amount[] = $row[0];
		$ps_type[] = $row[1];
		$ps_chque_no[] = $row[2];
		$ps_chque_bank[] = $row[3];
		$ps_chque_branch[] = $row[4];
		$ps_chque_date[] = $row[5];
		$ps_chque_return_date[] = $row[6];
		$ps_submited_by[] = $row[7];
		$ps_submited_date[] = $row[8];
		$ps_processed_by[] = $row[9];
		$ps_processed_date[] = $row[10];
		$ps_status[] = getPaySubStatus($row[11]);
		$ps_cust_chq[] = $row[12];
		$ps_status_code[] = $row[11];
	}

	//-------Summary Calculation---------------------//
	$query = "SELECT SUM(bi.qty * bi.unit_price) FROM bill_main bm, bill bi WHERE bm.invoice_no=bi.invoice_no AND bm.`status` NOT IN (0,7) AND bm.`lock`=1 AND bm.exclude=0 AND bm.sub_system='$sub_system' AND date(billed_timestamp)<'$from_date'";
	$row = mysqli_fetch_row(mysqli_query($conn2, $query));
	$bm_total = $row[0];
	$query = "SELECT SUM(amount) FROM payment_subsys WHERE `status`=2 AND sub_system='$sub_system' AND date(processed_date)<'$from_date'";
	$row = mysqli_fetch_row(mysqli_query($conn2, $query));
	$py_total = $row[0];
	$balance_before_from = $bm_total - $py_total;
	$query = "SELECT SUM(bi.qty * bi.unit_price) FROM bill_main bm, bill bi WHERE bm.invoice_no=bi.invoice_no AND bm.`status` NOT IN (0,7) AND bm.`lock`=1 AND bm.exclude=0 AND bm.sub_system='$sub_system' AND date(billed_timestamp) BETWEEN '$from_date' AND '$to_date'";
	$row = mysqli_fetch_row(mysqli_query($conn2, $query));
	$bm_total = $row[0];
	$query = "SELECT SUM(amount) FROM payment_subsys WHERE `status`=2 AND sub_system='$sub_system' AND date(processed_date) BETWEEN '$from_date' AND '$to_date'";
	$row = mysqli_fetch_row(mysqli_query($conn2, $query));
	$py_total = $row[0];
	$balance_within_period = $bm_total - $py_total;
	$query = "SELECT SUM(bi.qty * bi.unit_price) FROM bill_main bm, bill bi WHERE bm.invoice_no=bi.invoice_no AND bm.`status` NOT IN (0,7) AND bm.`lock`=1 AND bm.exclude=0 AND bm.sub_system='$sub_system' AND date(billed_timestamp)>'$to_date'";
	$row = mysqli_fetch_row(mysqli_query($conn2, $query));
	$bm_total = $row[0];
	$query = "SELECT SUM(amount) FROM payment_subsys WHERE `status`=2 AND sub_system='$sub_system' AND date(processed_date)>'$to_date'";
	$row = mysqli_fetch_row(mysqli_query($conn2, $query));
	$py_total = $row[0];
	$balance_after_to = $bm_total - $py_total;

	$result = mysqli_query($conn2, "SELECT shop_name FROM stores WHERE id='$store'");
	$row = mysqli_fetch_assoc($result);
	$store_company = $row['shop_name'];
}

//-------------------------INV MGMT----------------------------------------------//
function searchInv($sub_system)
{
	global $type, $id, $inv_billed_by, $inv_type_id, $inv_store, $inv_sms, $inv_odr_date, $inv_inv_date, $inv_total, $status_out, $inv_status, $status_color, $inv_pay_id, $inv_pay_date, $inv_pay_typen, $inv_pay_typec, $inv_pay_amount, $inv_pay_by, $pay_date, $recovery_agent;

	$type = $_GET['type'];
	if ($sub_system == 'all') {
		$sub_system_qry1 = $sub_system_qry2 = '';
	} else {
		$sub_system_qry1 = "AND bm.`sub_system`='$sub_system'";
		$sub_system_qry2 = "AND py.`sub_system`='$sub_system'";
	}
	include('config.php');
	if ($type == 'bill_no') {
		$id = $_GET['id'];
		$query = "SELECT bm.billed_by,st.name,date(bm.order_timestamp),date(bm.billed_timestamp),(bm.`invoice_+total` + bm.`invoice_-total`),bm.`type`,bm.sms,bm.`status`,bm.recovery_agent FROM bill_main bm, stores st WHERE bm.store=st.id $sub_system_qry1 AND bm.invoice_no='$id'";
		$row = mysqli_fetch_row(mysqli_query($conn2, $query));
		$inv_billed_by = $row[0];
		$inv_store = $row[1];
		$inv_odr_date = $row[2];
		$inv_inv_date = $row[3];
		$inv_total = $row[4];
		$inv_type = $row[5];
		$inv_type_id = $row[5];
		$inv_sms = $row[6];
		$inv_status = $row[7];
		$recovery_agent = $row[8];
		$query1 = "SELECT py.id,date(py.payment_date),py.payment_type,py.amount,up.username FROM payment py, userprofile up WHERE py.salesman=up.id AND py.`status`='0' AND py.invoice_no='$id'";
		//		$row = mysqli_fetch_row(mysqli_query($conn2, $query));
		$result1 = mysqli_query($conn2, $query1);
		while ($row1 = mysqli_fetch_array($result1)) {
			$inv_pay_id[] = $row1[0];
			$inv_pay_date[] = $row1[1];
			$inv_pay_amount[] = $row1[3];
			$inv_pay_by[] = $row1[4];
			if ($row1[2] != '') {
				$json_array = json_decode(paymentType($row1[2]));
				$inv_pay_typen[] = $json_array->{"name"};
				$inv_pay_typec[] = $json_array->{"color"};
			} else {
				$inv_pay_typen[] = 'NA';
				$inv_pay_typec[] = '#000000';
			}
		}

		if ($row[0] != '') {
			switch ($inv_status) {
				case 0:
					$status_out = 'Deleted';
					$status_color = '#FF3300';
					break;
				case 1:
					$status_out = 'Billed (Pending)';
					$status_color = 'yellow';
					break;
				case 2:
					$status_out = 'Billed (Picked)';
					$status_color = 'yellow';
					break;
				case 3:
					if ($inv_type == 3) {
						$status_out = 'Billed (Picked)';
					} else {
						$status_out = 'Billed (Packed)';
					}
					$status_color = 'yellow';
					break;
				case 4:
					if ($inv_type == 3) {
						$status_out = 'Repaired';
					} else {
						$status_out = 'Billed (Shipped)';
					}
					$status_color = 'yellow';
					break;
				case 5:
					if ($inv_type == 3) {
						$status_out = 'Repaired | Delivered';
					} else {
						$status_out = 'Billed (Delivered)';
					}
					$status_color = 'white';
					break;
				case 6:
					$status_out = 'Rejected';
					$status_color = 'orange';
					break;
				case 7:
					$status_out = 'Rejected | Delivered';
					$status_color = 'orange';
					break;
			}
		}
	} else
		if ($type == 'pay_no') {
			$id = $_GET['id'];
			$query = "SELECT py.salesman,st.name,date(py.payment_date),py.amount,py.`status` FROM payment py, stores st WHERE py.store=st.id $sub_system_qry2 AND py.id='$id'";
			$row = mysqli_fetch_row(mysqli_query($conn2, $query));
			$inv_billed_by = $row[0];
			$inv_store = $row[1];
			$pay_date = $row[2];
			$inv_total = $row[3];
			$inv_status = $row[4];
			if ($row[0] != '') {
				switch ($inv_status) {
					case 0:
						$status_out = 'Paid';
						$status_color = 'white';
						break;
					case 1:
						$status_out = 'Deleted';
						$status_color = '#FF3300';
						break;
				}
			}
		}
}

function addBillPayEditAudit($type, $no, $ori_date, $ch_date, $ori_salesman, $ch_salesman, $ori_recag, $ch_recag)
{
	$edit_by = $_COOKIE['user_id'];
	$time_now = timeNow();
	$out = true;
	include('config.php');
	if ($type == 'bill') {
		if ($ori_date != '') {
			$query = "INSERT INTO audit_inv_date (`invoice_no`,`original_date`,`changed_date`,`changed_by`,`changed_time`) VALUES ('$no','$ori_date','$ch_date','$edit_by','$time_now')";
			$result = mysqli_query($conn, $query);
			if (!$result) {
				$out = false;
			}
		}
		if ($ori_salesman != '') {
			$query = "INSERT INTO audit_inv_date (`invoice_no`,`original_salesman`,`changed_salesman`,`changed_by`,`changed_time`) VALUES ('$no','$ori_salesman','$ch_salesman','$edit_by','$time_now')";
			$result = mysqli_query($conn, $query);
			if (!$result) {
				$out = false;
			}
		}
		if ($ori_recag != '') {
			$query = "INSERT INTO audit_inv_date (`invoice_no`,`original_rec_agent`,`changed_rec_agent`,`changed_by`,`changed_time`) VALUES ('$no','$ori_recag','$ch_recag','$edit_by','$time_now')";
			$result = mysqli_query($conn, $query);
			if (!$result) {
				$out = false;
			}
		}
	}

	if ($type == 'pay') {
		if ($ori_date != '') {
			$query = "INSERT INTO audit_pay_date (`payment_no`,`original_date`,`changed_date`,`changed_by`,`changed_time`) VALUES ('$no','$ori_date','$ch_date','$edit_by','$time_now')";
			$result = mysqli_query($conn, $query);
			if (!$result) {
				$out = false;
			}
		}
		if ($ori_salesman != '') {
			$query = "INSERT INTO audit_pay_date (`payment_no`,`original_salesman`,`changed_salesman`,`changed_by`,`changed_time`) VALUES ('$no','$ori_salesman','$ch_salesman','$edit_by','$time_now')";
			$result = mysqli_query($conn, $query);
			if (!$result) {
				$out = false;
			}
		}
	}
	return $out;
}

function changeSalesman()
{
	global $message, $type, $id;
	$id = $_POST['id'];
	$type = $_POST['type'];
	$sm = $_POST['sm'];
	$master_pw04 = $_POST['master_pw4'];
	$out = true;
	$message = 'Done';
	include('config.php');

	$query = "SELECT `value` FROM settings WHERE setting='master_pw4'";
	$row = mysqli_fetch_row(mysqli_query($conn2, $query));
	$master_pw4 = $row[0];
	if ($master_pw04 != $master_pw4) {
		$message = 'Error: Invalid Master Password!';
		$out = false;
	}
	if ($out) {
		$query = "SELECT count(id),sm_pay FROM hp_inv_schedule WHERE invoice_no='$id'";
		$row = mysqli_fetch_row(mysqli_query($conn2, $query));
		if (($row[0] > 0) && ($row[1] != 0)) {
			$message = 'Error: Cannot Change the Salesman once Commision has paid!';
			$out = false;
		}
	}
	if ($out) {
		if ($type == 'bill_no') {
			$query = "SELECT billed_by FROM bill_main WHERE invoice_no='$id'";
			$row = mysqli_fetch_row(mysqli_query($conn2, $query));
			$original_sm = $row[0];

			$query = "UPDATE bill_main SET billed_by='$sm' WHERE invoice_no='$id'";
			$result = mysqli_query($conn, $query);
			if (!$result) {
				$message = 'Error: Salesman could not be Changed !';
				$out = false;
			}

			if ($out) {
				if (!addBillPayEditAudit('bill', $id, '', '', $original_sm, $sm, '', '')) {
					$message = 'Error: Failed to add the audit trail';
					$out = false;
				}
			}

			if ($out) {
				$query = "SELECT id,salesman FROM payment WHERE bill_pay='1' AND `status`='0' AND invoice_no='$id'";
				$result = mysqli_query($conn2, $query);
				while ($row = mysqli_fetch_array($result)) {
					$py_id = $row[0];
					$py_old_sm = $row[1];

					$query1 = "UPDATE payment SET salesman='$sm' WHERE bill_pay='1' AND `id`='$py_id'";
					$result1 = mysqli_query($conn, $query1);
					if (!$result1) {
						$message = 'Error: Salesman could not be Changed !';
						$out = false;
					}

					if ($out) {
						if (!addBillPayEditAudit('pay', $py_id, '', '', $py_old_sm, $sm, '', '')) {
							$message = 'Error: Failed to add the audit trail';
							$out = false;
						}
					}
				}
			}
		}

		if ($type == 'pay_no') {
			$query = "SELECT salesman FROM payment WHERE id='$id'";
			$row = mysqli_fetch_row(mysqli_query($conn2, $query));
			$original_sm = $row[0];

			$query = "UPDATE payment SET salesman='$sm' WHERE id='$id'";
			$result = mysqli_query($conn, $query);
			if (!$result) {
				$message = 'Error: Salesman could not be Changed !';
				$out = false;
			}

			if ($out) {
				if (!addBillPayEditAudit('pay', $id, '', '', $original_sm, $sm, '', '')) {
					$message = 'Error: Failed to add the audit trail';
					$out = false;
				}
			}
		}
	}
	return $message;
}

// added by e.s.p nirmal to save changed recovery agent
function changeRecoveryAgent()
{
	global $message, $id;
	$id = $_POST['id'];
	$rg_new = $_POST['rg_new'];
	$master_pw04 = $_POST['master_pw4'];
	$out = true;
	$message = 'Done';

	include('config.php');
	$query = "SELECT `value` FROM settings WHERE `setting`='master_pw4'";
	$row = mysqli_fetch_row(mysqli_query($conn2, $query));
	$master_pw4 = $row[0];
	if ($master_pw04 != $master_pw4) {
		$message = 'Error: Invalid Master Password!';
		$out = false;
	}
	if ($out) {
		$query = "SELECT COUNT(his.id),bm.recovery_agent,his.rg_pay FROM bill_main bm, hp_inv_schedule his WHERE bm.invoice_no=his.invoice_no AND bm.`invoice_no`='$id'";
		$row = mysqli_fetch_row(mysqli_query($conn2, $query));
		$count = $row[0];
		$rg_old = $row[1];
		$rg_pay = $row[2];
		if ($out) {
			if ($count == 0) {
				$message = "Error: No Payment Schedule Found for Hire Purchase Invoice!";
				$out = false;
			}
		}
		if ($out) {
			if ($rg_pay != 0) {
				$message = "Error: Recover Agent could not be changed after Commision allocation!";
				$out = false;
			}
		}
	}
	if ($out) {
		$query = "UPDATE bill_main SET `recovery_agent`='$rg_new' WHERE `invoice_no`='$id'";
		$result = mysqli_query($conn, $query);
		if (!$result) {
			$message = 'Error: Recover agent could not be Changed!';
			$out = false;
		}
	}

	if ($out) {
		if (!addBillPayEditAudit('bill', $id, '', '', '', '', $rg_old, $rg_new)) {
			$message = 'Error: Failed to add the audit trail';
			$out = false;
		}
	}
	return $message;
}

function getLock($type, $status)
{
	$lock = 0;
	switch ($status) {
		case 1:
			if ($type == 1)
				$lock = 1;
			if ($type == 4)
				$lock = 2;
			break;
		case 2:
			if ($type == 1)
				$lock = 1;
			if ($type == 4)
				$lock = 2;
			break;
		case 3:
			if ($type == 1)
				$lock = 1;
			if ($type == 4)
				$lock = 1;
			break;
		case 4:
			if ($type == 1)
				$lock = 1;
			if ($type == 4)
				$lock = 1;
			break;
		case 5:
			if ($type == 1)
				$lock = 1;
			if ($type == 4)
				$lock = 1;
			break;
	}
	return $lock;
}

function setInvMain()
{
	global $message, $bill_no;
	$bill_no = $_POST['inv_no'];
	$type = $_POST['inv_type'];
	$sms = $_POST['inv_sms'];
	$status = $_POST['inv_status'];
	$lock = getLock($type, $status);
	$password = $_POST['auth_pass'];
	$master_pw04 = $_POST['master_pw4'];
	$out = true;
	$message = 'Nothing is Changed';
	include('config.php');

	$query = "SELECT `value` FROM settings WHERE setting='master_pw4'";
	$row = mysqli_fetch_row(mysqli_query($conn2, $query));
	$master_pw4 = $row[0];
	if ($master_pw04 != $master_pw4) {
		$message = 'Error: Invalid Master Password !';
		$out = false;
	}

	if ($out) {
		$query = "SELECT authorize_code FROM bill_main WHERE invoice_no='$bill_no'";
		$row = mysqli_fetch_row(mysqli_query($conn2, $query));
		$authorize_code = $row[0];
		if ($password != $authorize_code) {
			$message = 'Error: Invalid Auth Code !';
			$out = false;
		}
	}

	$query = "SELECT `type`,sms,`status` FROM bill_main WHERE invoice_no='$bill_no'";
	$row = mysqli_fetch_row(mysqli_query($conn2, $query));
	$old_type = $row[0];
	$old_sms = $row[1];
	$old_status = $row[2];

	if ($out && ($old_type != $type)) {
		$query = "UPDATE bill_main SET `type`='$type',`lock`='$lock' WHERE invoice_no='$bill_no'";
		$result = mysqli_query($conn, $query);
		if ($result)
			$message = 'Done';
		else {
			$message = 'Error: Invoice Details could not be Changed !';
			$out = false;
		}
	}
	if ($out && ($old_status != $status)) {
		$query = "UPDATE bill_main SET `status`='$status',`lock`='$lock' WHERE invoice_no='$bill_no'";
		$result = mysqli_query($conn, $query);
		if ($result)
			$message = 'Done';
		else {
			$message = 'Error: Invoice Details could not be Changed !';
			$out = false;
		}
	}
	if ($out && ($old_sms != $sms)) {
		$query = "UPDATE bill_main SET `sms`='$sms' WHERE invoice_no='$bill_no'";
		$result = mysqli_query($conn, $query);
		if ($result)
			$message = 'Done';
		else {
			$message = 'Error: Invoice Details could not be Changed !';
			$out = false;
		}
	}
	return $message;
}

function setInvDate()
{
	$out = true;
	$msg = 'Done';

	$inv_no = $_POST['inv_no'];
	$new_bm_date = $_POST['bm_date'] . ' 00:00:00';
	$with_pay = $_POST['with_pay'];
	$master_pw4 = $_POST['master_pw4'];
	$edit_by = $_COOKIE['user_id'];
	$time_now = timeNow();

	include('config.php');

	$query = "SELECT `value` FROM settings WHERE setting='master_pw4'";
	$row = mysqli_fetch_row(mysqli_query($conn2, $query));
	$authorize_code = $row[0];
	$query = "SELECT `billed_timestamp` FROM bill_main WHERE invoice_no='$inv_no'";
	$row = mysqli_fetch_row(mysqli_query($conn2, $query));
	$original_date = $row[0];

	if ($authorize_code != $master_pw4) {
		$out = false;
		$msg = 'Invalid Master Password';
	}
	if ($out) {
		$query = "UPDATE bill_main SET `billed_timestamp`='$new_bm_date' WHERE invoice_no='$inv_no'";
		$result = mysqli_query($conn, $query);
		if (!$result) {
			$out = false;
			$msg = 'Error: Failed to set the Invoice Date';
		}
	}
	if ($out && ($with_pay == 'yes')) {
		$query = "UPDATE payment SET `payment_date`='$new_bm_date' WHERE `bill_pay`='1' AND `status`='0' AND invoice_no='$inv_no'";
		$result = mysqli_query($conn, $query);
		if (!$result) {
			$out = false;
			$msg = 'Error: Failed to set the Invoice Payment Date';
		}
	}
	if ($out) {
		$query = "INSERT INTO audit_inv_date (`invoice_no`,`original_date`,`changed_date`,`changed_by`,`changed_time`) VALUES ('$inv_no','$original_date','$new_bm_date','$edit_by','$time_now')";
		$result = mysqli_query($conn, $query);
		if (!$result) {
			$out = false;
			$msg = 'Error: Failed to add the audit trail';
		}
	}

	return $msg;
}

function setPayDate()
{
	$out = true;
	$msg = 'Done';

	$py_no = $_POST['py_no'];
	$new_py_date = $_POST['py_date'] . ' 00:00:00';
	$master_pw4 = $_POST['master_pw4'];
	$edit_by = $_COOKIE['user_id'];
	$time_now = timeNow();

	include('config.php');

	$query = "SELECT `value` FROM settings WHERE setting='master_pw4'";
	$row = mysqli_fetch_row(mysqli_query($conn2, $query));
	$authorize_code = $row[0];
	$query = "SELECT `payment_date` FROM payment WHERE id='$py_no'";
	$row = mysqli_fetch_row(mysqli_query($conn2, $query));
	$original_date = $row[0];

	if ($authorize_code != $master_pw4) {
		$out = false;
		$msg = 'Invalid Master Password';
	}
	if ($out) {
		$query = "UPDATE payment SET `payment_date`='$new_py_date' WHERE `id`='$py_no'";
		$result = mysqli_query($conn, $query);
		if (!$result) {
			$out = false;
			$msg = 'Error: Failed to set the Payment Date';
		}
	}
	if ($out) {
		$query = "INSERT INTO audit_pay_date (`payment_no`,`original_date`,`changed_date`,`changed_by`,`changed_time`) VALUES ('$py_no','$original_date','$new_py_date','$edit_by','$time_now')";
		$result = mysqli_query($conn, $query);
		if (!$result) {
			$out = false;
			$msg = 'Error: Failed to add the audit trail';
		}
	}

	return $msg;
}

//-----------------------Qty MGMT---------------------------------//
function getQtyMgmt()
{
	global $item_id0, $item_id, $code, $description, $code1, $description1, $approve_edit, $inv_id, $inv_qty, $inv_wprice, $inv_rprice, $inv_cprice, $inn_id, $inn_qty, $inn_wprice, $inn_rprice, $inn_cprice;
	$store = $_COOKIE['store'];
	$approve_edit = false;
	$inn_id = array();
	include('config.php');
	$query = "SELECT itm.id,itm.code,itm.description FROM inventory_items itm, inventory_qty itq WHERE itm.id=itq.item AND itq.location='$store' AND itm.`status`='1' AND itm.unic=0 AND itm.pr_sr=1";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$item_id[] = $row[0];
		$code[] = $row[1];
		$description[] = $row[2];
	}

	if (isset($_GET['item_id'])) {
		$item_id0 = $_GET['item_id'];
		$qty = 0;
		$query = "SELECT `code`,description,unic,pr_sr FROM inventory_items WHERE id='$item_id0'";
		$row = mysqli_fetch_row(mysqli_query($conn2, $query));
		$code1 = $row[0];
		$description1 = $row[1];
		$unic = $row[2];
		$pr_sr = $row[3];
		$query = "SELECT id,qty,w_price,r_price,c_price FROM inventory_qty WHERE item='$item_id0' AND location='$store'";
		$row = mysqli_fetch_row(mysqli_query($conn2, $query));
		$inv_id = $row[0];
		$inv_qty = $row[1];
		$inv_wprice = $row[2];
		$inv_rprice = $row[3];
		$inv_cprice = $row[4];
		$query = "SELECT id,qty,w_price,r_price,c_price FROM inventory_new WHERE item='$item_id0' AND store='$store'";
		$result = mysqli_query($conn2, $query);
		while ($row = mysqli_fetch_array($result)) {
			$inn_id[] = $row[0];
			$inn_qty[] = $row[1];
			$inn_wprice[] = $row[2];
			$inn_rprice[] = $row[3];
			$inn_cprice[] = $row[4];
		}
		if ($unic == 0 && $pr_sr == 1)
			$approve_edit = true;
	}
}

// update by nirmal 19_11_2024 (add quickbooks journal entry)
function adjustQty()
{
	global $message;
	$tag = $_GET['tag'];
	$id = $_GET['id'];
	$item_id = $_GET['item_id'];
	$qty_adj = $_GET['qty_adj'];
	$comment = $_GET['comment'];
	$shop_name = preg_replace("/[^A-Za-z0-9-+,. ]/", '', $comment);
	$store = $_COOKIE['store'];
	$edit_by = $_COOKIE['user_id'];
	$time_now = timeNow();
	$out = false;
	$message = 'Quantity was updated successfully.';
	$qb_msg = '';
	include('config.php');

	try {
		// Start the transaction
		mysqli_begin_transaction($conn);

		if ($id != '' && $id != 0 && $qty_adj != '' && $qty_adj != 0) {
			if ($tag == 'inv') {
				$query = "SELECT qty,item,c_price FROM inventory_qty WHERE id='$id'";
				$row = mysqli_fetch_row(mysqli_query($conn2, $query));
				$qty = $row[0];
				$item_id = $row[1];
				$item_cprice = $row[2];
				$debug_id = debugStart($store, $qty_adj);

				$query = "SELECT SUM(qty) FROM inventory_new WHERE `item`='$item_id' AND store='$store'";
				$row = mysqli_fetch_row(mysqli_query($conn2, $query));
				$inn_qty = $row[0];
				if ($inn_qty > 0) {
					if (($qty + $qty_adj) > 0) {
						$query1 = "UPDATE `inventory_qty` SET `qty`=qty+$qty_adj WHERE `id`='$id'";
						$result1 = mysqli_query($conn, $query1);
						if ($result1) {
							$out = true;
						} else {
							$message = 'Error: Quantity update failed';
							throw new Exception($message);
						}
					} else {
						$message = 'Error: New inventory available; therefore, you cannot set 0 for inventory qty';
						throw new Exception($message);
					}
				} else if ($inn_qty == 0) {
					if (($qty + $qty_adj) >= 0) {
						$query1 = "UPDATE `inventory_qty` SET `qty`=qty+$qty_adj WHERE `id`='$id'";
						$result1 = mysqli_query($conn, $query1);
						if ($result1) {
							$out = true;
						} else {
							$message = 'Error: Quantity update failed';
							throw new Exception($message);
						}
					} else {
						$message = 'Error: Invalid quantity in new inventory (-)';
						throw new Exception($message);
					}
				}
			} else if ($tag == 'inn') {
				$query = "SELECT qty,c_price FROM inventory_new WHERE id='$id'";
				$row = mysqli_fetch_row(mysqli_query($conn2, $query));
				$qty = $row[0];
				$item_cprice = $row[1];
				if (($qty + $qty_adj) > 0) {
					$query1 = "UPDATE `inventory_new` SET `qty`=qty+$qty_adj WHERE `id`='$id'";
					$result1 = mysqli_query($conn, $query1);
					if ($result1) {
						$out = true;
					} else {
						$message = 'Error: Quantity update failed';
						throw new Exception($message);
					}
				} else if (($qty + $qty_adj) == 0) {
					$query1 = "DELETE FROM `inventory_new` WHERE `id`='$id'";
					$result1 = mysqli_query($conn, $query1);
					if ($result1) {
						$out = true;
					} else {
						$message = 'Error: Quantity update failed';
						throw new Exception($message);
					}
				} else {
					$message = 'Error: Invalid quantity in new inventory (-)';
					throw new Exception($message);
				}
			}
		} else {
			$message = 'Error: Invalid data';
			throw new Exception($message);
		}
		if ($out) {
			$query2 = "INSERT INTO `inventory_edit` (`item`,`store`,`edit_by`,`datetime`,`inventory`,`inventory_id`,`item_cost`,`old_qty`,`action_qty`,`comment`) VALUES ('$item_id','$store','$edit_by','$time_now','$tag','$id','$item_cprice','$qty','$qty_adj','$comment')";
			$result2 = mysqli_query($conn, $query2);
			if ($result2) {
				$out = true;
			} else {
				$out = false;
				$message = 'Error: Inventory edit log could not be created';
				throw new Exception($message);
			}
		}

		if ($tag == 'inv') {
			if ($out) {
				debugEnd($debug_id, 'success');
			} else {
				debugEnd($debug_id, 'fail');
			}
		}

		if ($out && isQuickBooksActive(1)) {
			$journal_entry_result = $journalEntryForQtyAdjust = [];
			$amount = $item_cprice * $qty_adj;

			$query = "SELECT `username` FROM userprofile WHERE id=$edit_by";
			$row = mysqli_fetch_row(mysqli_query($conn2, $query));
			$placed_by = $row[0];

			$debitEntityType = "";
			$debitEntityID = "";
			$creditEntityType = "";
			$creditEntityID = "";
			$description = "[QUANTITY ADJUST] - User : $placed_by";
			if (!empty($comment)) {
				$description .= ", Comment: $comment";
			}

			if ($qty_adj > 0) { // plus
				$debitAccountName = "Inventory Asset";
				$creditAccountName = "Stock Written Off";
			} else { // minus
				$debitAccountName = "Stock Written Off";
				$creditAccountName = "Inventory Asset";
			}
			$journalEntryForQtyAdjust = buildJournalEntry($conn2, abs($amount), $debitAccountName, $creditAccountName, $description, $debitEntityType, $debitEntityID, $creditEntityType, $creditEntityID);
			if (isset($journalEntryForQtyAdjust['error'])) {
				$out = false;
				$qb_msg = $journalEntryForQtyAdjust['error'];
				throw new Exception($qb_msg);
			} else {
				try {
					$journal_entry_result = QBAddJournalEntry($journalEntryForQtyAdjust);
					$qb_msg = $journal_entry_result['message'];
					if ((isset($journal_entry_result['status']) && ($journal_entry_result['status'] != 'success'))) {
						$out = false;
						throw new Exception($qb_msg);
					} else {
						$out = true;
					}
				} catch (Exception $e) {
					$out = false;
					$qb_msg = "<br>QuickBooks error: " . $e->getMessage();
					throw new Exception($qb_msg);
				}
			}
			$message = $message . ' ' . $qb_msg;
		}
		// Commit the transaction
		mysqli_commit($conn);
	} catch (Exception $e) {
		mysqli_rollback($conn);
		$message = $e->getMessage();
		error_log("Error in adjustQty(): " . $message); // Log the error
	}
	return $out;
}

function getQtyAudit($count)
{
	global $item, $from_date, $to_date, $ie_date, $ie_item, $ie_old_qty, $ie_action_qty, $ie_user, $comment;
	if (isset($_GET['item_id'])) {
		$item_id = $_GET['item_id'];

		$ie_date = array();
		include('config.php');
		$query1 = "SELECT ie.`datetime`,itm.description,ie.action_qty,up.username,ie.comment FROM inventory_edit ie, inventory_items itm, userprofile up WHERE ie.item=itm.id AND ie.edit_by=up.id AND date(ie.`datetime`) AND itm.id='$item_id' LIMIT $count";
		$result1 = mysqli_query($conn2, $query1);
		while ($row1 = mysqli_fetch_array($result1)) {
			$ie_date[] = $row1[0];
			$ie_item[] = $row1[1];
			$ie_action_qty[] = $row1[2];
			$ie_user[] = $row1[3];
			if (strlen($row1[4]) > 20)
				$comment[] = substr($row1[4], 0, 20) . '..';
			else
				$comment[] = $row1[4];
		}
	}
}

//-----------------------Quotation---------------------------------//
function pendingQuot($sub_system)
{
	global $qm_id, $qm_created_date, $qm_validity, $qm_store, $qm_cust, $qm_amount, $qm_created_by, $qm_custid;
	$qm_amount = $qm_id = array();
	include('config.php');

	$query = "SELECT qm.id,qm.quo_timestamp,qm.validity,st.name,cu.name,SUM(qi.qty * qi.unit_price),qm.created_by,qm.`cust`
	FROM quotation_main qm, quotation qi, cust cu, stores st
	WHERE qm.id=qi.quot_no AND qm.`cust`=cu.id AND qm.store=st.id AND qm.`status`=2 AND qm.sub_system='$sub_system'
	GROUP BY qm.id";

	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$qm_id[] = $row[0];
		$qm_created_date[] = $row[1];
		$qm_validity[] = $row[2];
		$qm_store[] = $row[3];
		$qm_cust[] = $row[4];
		$qm_amount[] = $row[5];
		$qm_created_by[] = $row[6];
		$qm_custid[] = $row[7];
	}
}
//-----------------------------MAP------------------------------------//
function decodeMapData()
{
	global $map_api, $map_cust, $map_sm, $sm_pinter, $map_x, $map_y;
	$cust_arr = $datalist = $map_cust = $map_x = $map_y = $map_sm = $unique_sm = $sm_pinter0 = $sm_pinter = array();
	$map_data = $_POST['gps'];
	include('config.php');
	$result = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='api_map'");
	$row = mysqli_fetch_assoc($result);
	$map_api = $row['value'];

	$query = "SELECT id,name FROM cust";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$cust_arr[$row[0]] = $row[1];
	}
	$query = "SELECT id,username FROM userprofile";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$salesman_arr[$row[0]] = ucfirst($row[1]);
	}
	$datalist = explode(",", $map_data);
	array_pop($datalist);
	for ($i = 0; $i < sizeof($datalist); $i++) {
		$datacxy = array();
		$datacxy = explode(":", $datalist[$i]);
		$map_cust[$i] = $cust_arr[$datacxy[0]];
		$map_sm[$i] = $salesman_arr[$datacxy[1]];
		$map_x[$i] = $datacxy[2];
		$map_y[$i] = $datacxy[3];
	}
	$unique_sm = array_values(array_unique($map_sm));
	for ($i = 0; $i < sizeof($unique_sm); $i++) {
		$sm_pinter0[$unique_sm[$i]] = $i;
	}

	for ($i = 0; $i < sizeof($map_sm); $i++) {
		$sm_pinter[] = $sm_pinter0[$map_sm[$i]];
	}
}

function getWarranty()
{
	global $from_date, $to_date, $store, $war_st_type, $inv_st_count, $wa_id, $wa_cl_date, $wa_cl_item, $wa_ho_date, $wa_ho_item, $wa_cust, $wa_suplier, $wa_cl_byid, $wa_cl_byname, $wa_store, $wa_status, $war_status_name, $war_status_color, $wac_id, $wac_cl_date, $wac_ho_date, $wac_cl_item, $wac_ho_item, $wac_cust, $wac_suplier, $wac_cl_byid, $wac_cl_byname, $wac_store;
	$store = $_GET['store'];
	$wa_id = $wac_id = $item_arr = array();
	$item_arr[''] = '';
	include('config.php');
	if (isset($_GET['from_date']) && isset($_GET['to_date'])) {
		$from_date = $_GET['from_date'];
		$to_date = $_GET['to_date'];
	} else {
		$result = mysqli_query($conn2, "SELECT value FROM settings WHERE setting='timezone'");
		$row = mysqli_fetch_assoc($result);
		$timezone = $row['value'];
		$from_date = date("Y-m-d", time() + (60 * 60 * $timezone) - (30 * 86400));
		$to_date = date("Y-m-d", time() + (60 * 60 * $timezone));
	}
	if ($store != 'all') {
		$store_qry = "AND wa.store='$store'";
	} else {
		$store_qry = "";
	}

	$query = "SELECT id,description FROM inventory_items";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$item_arr[$row[0]] = $row[1];
	}

	$query = "SELECT wa.id,date(wa.claim_date),date(wa.handover_date),wa.claim_item,wa.handover_item,cu.name,su.name,up.id,up.username,st.name,wa.`status` FROM warranty wa, cust cu, supplier su, userprofile up, stores st WHERE wa.store=st.id AND wa.customer=cu.id AND wa.suplier=su.id AND wa.taken_by=up.id AND wa.`status`='4' AND date(wa.claim_date) BETWEEN '$from_date' AND '$to_date' $store_qry";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$wac_id[] = $row[0];
		$wac_cl_date[] = $row[1];
		$wac_ho_date[] = $row[2];
		$wac_cl_item[] = $item_arr[$row[3]];
		$wac_ho_item[] = $item_arr[$row[4]];
		$wac_cust[] = $row[5];
		$wac_suplier[] = $row[6];
		$wac_cl_byid[] = $row[7];
		$wac_cl_byname[] = $row[8];
		$wac_store[] = $row[9];
	}
	$query = "SELECT wa.id,date(wa.claim_date),date(wa.handover_date),wa.claim_item,wa.handover_item,cu.name,su.name,up.id,up.username,st.name,wa.`status` FROM warranty wa, cust cu, supplier su, userprofile up, stores st WHERE wa.store=st.id AND wa.customer=cu.id AND wa.suplier=su.id AND wa.taken_by=up.id AND wa.`status` IN (1,2,3) $store_qry";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$wa_id[] = $row[0];
		$wa_cl_date[] = $row[1];
		$wa_ho_date[] = $row[2];
		$wa_cl_item[] = $item_arr[$row[3]];
		$wa_ho_item[] = $item_arr[$row[4]];
		$wa_cust[] = $row[5];
		$wa_suplier[] = $row[6];
		$wa_cl_byid[] = $row[7];
		$wa_cl_byname[] = $row[8];
		$wa_store[] = $row[9];
		$wa_status[] = $row[10];
		$json_array = json_decode(warrantyStatus2($row[10]));
		$war_status_name[] = $json_array->{"st_name"};
		$war_status_color[] = $json_array->{"st_color"};
	}

	$query = "SELECT `status`,count(id) FROM warranty WHERE `status` IN (1,2,3) GROUP BY `status`";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$json_array = json_decode(warrantyStatus2($row[0]));
		$war_st_type[] = $json_array->{"st_name"};
		$inv_st_count[] = $row[1];
	}
}

// added by nirmal 03_08_2023
// update by nirmal 18_09_2023
function getTaxReport($sub_system)
{
	global $from_date, $to_date, $total_tax, $group, $lock_req;

	// date filter
	$today = date("Y-m-d", time());
	if (isset($_GET['from_date']))
		$from_date = $_GET['from_date'];
	else
		$from_date = date("Y-m-d", time() - (60 * 60 * 24 * 30));

	if (isset($_GET['to_date']))
		$to_date = $_GET['to_date'];
	else
		$to_date = $today;

	// group filter
	if (isset($_GET['group'])) {
		$group = $_REQUEST['group'];
		if ($group == 'all')
			$groupsearch = '';
		else
			$groupsearch = " AND gp.`id`='" . $group . "'";
	} else {
		$groupsearch = '';
	}

	// salesman filter
	if (isset($_GET['salesman'])) {
		if ($_REQUEST['salesman'] == 'all')
			$salesmansearch = '';
		else
			$salesmansearch = " AND up.`id`='" . $_REQUEST['salesman'] . "'";
	} else {
		$salesmansearch = '';
	}

	// subsystem filter
	if ($sub_system == 'all') {
		$sub_sys_bmqry = '';
	} else {
		$sub_sys_bmqry = " AND bm.`sub_system`='$sub_system'";
	}

	// bill lock filter
	if (isset($_REQUEST['lock'])) {
		$lock_req = $_REQUEST['lock'];
		if ($lock_req == 'all') {
			$lock_qry = '';
		} else {
			if ($lock_req == 0)
				$lock_qry = " AND bm.`lock` IN (0,2)";
			else
				$lock_qry = " AND bm.`lock`=$lock_req";
		}
	} else {
		$lock_req = 1;
		$lock_qry = " AND bm.`lock`=$lock_req";
	}

	include('config.php');

	$query = "SELECT SUM(bm.`tax`)
	FROM bill_main bm, userprofile up, cust cu, cust_group gp
	WHERE bm.`billed_by`=up.`id` AND bm.`cust`=cu.`id` AND cu.`associated_group`=gp.id AND bm.`status` NOT IN (0,7) AND bm.exclude=0 $sub_sys_bmqry $lock_qry $salesmansearch $groupsearch AND date(bm.`billed_timestamp`) BETWEEN '$from_date' AND '$to_date'";
	$result = mysqli_query($conn2, $query);
	$row = mysqli_fetch_row($result);
	$total_tax = $row[0];
}

// added by nirmal 03_08_2023
// update by nirmal 18_09_2023
function getDetailTaxReport($sub_system)
{
	global $from_date, $to_date, $total_tax, $invoice_no, $billed_time, $invoice_total, $tax_amount, $billed_cust, $billed_time, $billed_by, $group, $lock_req,
	$bills_total, $tax_amount_total, $gross_amount, $gross_amount_total;
	$invoice_no = $billed_time = $invoice_total = $tax_amount = $billed_cust = $billed_time = $billed_by = $gross_amount = array();

	// date filter
	$today = date("Y-m-d", time());
	if (isset($_GET['from_date']))
		$from_date = $_GET['from_date'];
	else
		$from_date = date("Y-m-d", time() - (60 * 60 * 24 * 30));

	if (isset($_GET['to_date']))
		$to_date = $_GET['to_date'];
	else
		$to_date = $today;

	// group filter
	if (isset($_GET['group'])) {
		$group = $_REQUEST['group'];
		if ($group == 'all')
			$groupsearch = '';
		else
			$groupsearch = " AND gp.`id`='" . $group . "'";
	} else {
		$groupsearch = '';
	}

	// salesman filter
	if (isset($_GET['salesman'])) {
		if ($_REQUEST['salesman'] == 'all')
			$salesmansearch = '';
		else
			$salesmansearch = " AND up.`id`='" . $_REQUEST['salesman'] . "'";
	} else {
		$salesmansearch = '';
	}

	// subsystem filter
	if ($sub_system == 'all') {
		$sub_sys_bmqry = '';
	} else {
		$sub_sys_bmqry = " AND bm.`sub_system`='$sub_system'";
	}

	// bill lock filter
	if (isset($_REQUEST['lock'])) {
		$lock_req = $_REQUEST['lock'];
		if ($lock_req == 'all') {
			$lock_qry = '';
		} else {
			if ($lock_req == 0)
				$lock_qry = " AND bm.`lock` IN (0,2)";
			else
				$lock_qry = " AND bm.`lock`=$lock_req";
		}
	} else {
		$lock_req = 1;
		$lock_qry = " AND bm.`lock`=$lock_req";
	}

	include('config.php');

	$query = "SELECT bm.`invoice_no`, SUM(bm.`invoice_+total` + bm.`invoice_-total`), SUM(bm.`tax`), up.`username`,cu.`name`, date(bm.`billed_timestamp`), time(bm.`billed_timestamp`)
	FROM bill_main bm, userprofile up, cust cu, cust_group gp
	WHERE bm.`billed_by`=up.`id` AND bm.`cust`=cu.`id` AND cu.`associated_group`=gp.id AND  bm.`status` NOT IN (0,7) AND bm.exclude=0 $sub_sys_bmqry $lock_qry $salesmansearch $groupsearch AND date(bm.`billed_timestamp`) BETWEEN '$from_date' AND '$to_date' GROUP BY bm.invoice_no ORDER BY bm.`billed_timestamp` DESC";

	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$invoice_no[] = $row[0];
		$gross_amount[] = ($row[1] - $row[2]); // inv_total - tax = gross
		$gross_amount_total += ($row[1] - $row[2]); // inv_total - tax = gross
		$invoice_total[] = (($row[1] - $row[2]) + $row[2]); // gross_amount + tax = total
		$bills_total += (($row[1] - $row[2]) + $row[2]);
		$tax_amount[] = $row[2];
		$tax_amount_total += $row[2];
		$billed_by[] = $row[3];
		$billed_cust[] = $row[4];
		$billed_time[] = $row[5] . ' - ' . substr($row[6], 0, 5);
	}
}

// added by nirmal 08_07_2024
function getStoreSettings($sub_system)
{
	global $store_settings_store_id, $store_settings_store_name, $store_settings_store_sub_system, $store_setting_store_p_u;
	$store_settings_store_id = $store_settings_store_name = $store_settings_store_sub_system = $store_setting_store_p_u = array();

	include('config.php');

	$query = "SELECT s.`id`, s.`name`, ss.`name`, s.billing_price_under_value
	FROM stores s, sub_system ss
	WHERE s.`sub_system` = ss.`id` AND ss.`id` = '$sub_system'";

	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$store_settings_store_id[] = $row[0];
		$store_settings_store_name[] = $row[1];
		$store_settings_store_sub_system[] = $row[2];
		$store_setting_store_p_u[] = $row[3];
	}

}

// added by nirmal 08_07_2024
function updateStoreBillingPU($sub_system)
{
	global $message;
	$out = true;
	include('config.php');
	$store_id = $_POST['store_id'];

	if (($out) && ($store_id == '')) {
		$out = false;
		$message = 'Error. Store null';
	}
	if ($out) {
		$query = "SELECT id, billing_price_under_value FROM stores WHERE id='$store_id' AND sub_system = '$sub_system'";
		$result = mysqli_query($conn2, $query);
		if (mysqli_num_rows($result) <= 0) {
			$out = false;
			$message = 'Error. Store not exisit in our system';
		} else {
			$row = mysqli_fetch_row($result);
			$billing_price_under_value = $row[1];
		}
	}

	if ($out) {
		$status = 1;
		if ($billing_price_under_value == 1) {
			$status = 0;
		}
		$query1 = "UPDATE `stores` SET `billing_price_under_value`='$status' WHERE `id`='$store_id'";
		$result1 = mysqli_query($conn, $query1);
		if ($result1) {
			$message = 'Success. Store billing price undervalue updated';
		} else {
			$out = false;
			$message = 'Error. Store billing price undervalue update failed';
		}
	}
	return $out;
}
// added by nirmal 21_12_2023
// function getSpecialEventSMSData(){
// 	global $message, $date, $group, $cust_id, $cust_name, $cust_mobile, $event_message, $status, $text;
// 	include('config.php');
// 	$date = $group = '';
// 	$sms_shop_name = getSMSShopName(1);
// 	$text = "Happy Holidays from " . $sms_shop_name . "
// 	Wishing you joy, peace, and prosperity in the coming year
// 	Thanks for your support! 🎄🎁✨";
// 	$out=true;

// 	$cust_id = $cust_name = $cust_mobile = $event_message = $status = array();
// 	if ((!isset($_REQUEST['date'])) || (!isset($_REQUEST['group']))){
// 		$out=false;
// 		$message = "Error: Date and group required";
// 	}else{
// 		// date validation
// 		if($out){
// 			if(dateMonthValidation($_REQUEST['date'])){
// 				$date = substr($_REQUEST['date'], 5);
// 			}else{
// 				$out=false;
// 				$message = "Error: Invalid date format";
// 			}
// 		}
// 		// group validation
// 		if($out){
// 			$group = $_REQUEST['group'];
// 			if ($group != '') {
// 				$group = mysqli_real_escape_string($conn2, $group); // Sanitize input
// 				$result = mysqli_query($conn2, "SELECT `id` FROM cust_group WHERE `id`='$group'");
// 				$row = mysqli_fetch_assoc($result);
// 				$db_group = $row['id'];
// 				if ($db_group === null) {
// 					$out = false;
// 					$message =  "Error: Group ID does not exist.";
// 				}
// 			}else{
// 				$out = false;
// 				$message = 'Error : Group cannot be empty';
// 			}
// 		}
// 		if($out){
// 			$query="SELECT cu.`id`, cu.`name`, cu.`mobile`, ses.`text`, ses.`status` FROM cust cu
// 			INNER JOIN cust_group gp ON cu.`associated_group` = gp.`id`
// 			LEFT JOIN special_event_sms ses ON cu.`id` = ses.`cust`
// 			WHERE cu.`associated_group` = '$group' AND cu.`status` NOT IN(0)";
// 			$result=mysqli_query($conn2,$query);
// 			while($row=mysqli_fetch_array($result)){
// 				$cust_id[] = $row[0];
// 				$cust_name[] = $row[1];
// 				$cust_mobile[] = $row[2];
// 				$temp = str_replace('+', ' ', $row[3]);
// 				$event_message[] = str_replace('-NLC-','\n', $temp);
// 				if($row[4] == 1) $status[] = "SENT";
// 				else $status[] = 'NOT SEND';
// 			}
// 		}
// 	}
// }

// added by nirmal 22_12_2023
// function sendBulkSMS(){
// 	global $message;
// 	include('config.php');
// 	$password = $group = '';
// 	$out=true;
// 	$sent_by = $_COOKIE['user_id'];
// 	if(isset($_COOKIE['sub_system'])) $sub_system=$_COOKIE['sub_system']; else $sub_system=0;
// 	$sms_shop_name=getSMSShopName(1);
// 	$message = "SMS sent successfully!";

// 	if ((!isset($_REQUEST['group'])) || (!isset($_REQUEST['text'])) || (!isset($_REQUEST['password']))){
// 		$out=false;
// 		$message = "Error: Group, Text and Master password is required";
// 	}
// 	// group validation
// 	if($out){
// 		$group = $_REQUEST['group'];
// 		if ($group != '') {
// 			$group = mysqli_real_escape_string($conn2, $group); // Sanitize input
// 			$result = mysqli_query($conn2, "SELECT `id` FROM cust_group WHERE `id`='$group'");
// 			$row = mysqli_fetch_assoc($result);
// 			$db_group = $row['id'];
// 			if ($db_group === null) {
// 				$out = false;
// 				$message =  "Error: Group ID does not exist.";
// 			}
// 		}else{
// 			$out = false;
// 			$message = 'Error : Group cannot be empty';
// 		}
// 	}
// 	// text validation
// 	if($out){
// 		$text = trim($_REQUEST['text']);
// 		if($text == ''){
// 			$out = false;
// 			$message = 'Error : SMS text cannot be empty';
// 		}
// 	}
// 	// master password validation
// 	if($out){
// 		$password = trim($_REQUEST['password']);
// 		if($password != ''){
// 			$query = "SELECT `value` FROM settings WHERE `setting`='master_pw'";
// 			$row = mysqli_fetch_row(mysqli_query($conn2, $query));
// 			if ($row[0] != $password) {
// 				$message = 'Error : Invalid Master password';
// 				$out = false;
// 			}
// 		}else{
// 			$out = false;
// 			$message = 'Error : Password cannot be empty';
// 		}
// 	}
// 	// text construct
// 	if($out){
// 		// $stringWithPlus = str_replace(' ', '+', $text); // Replace spaces with '+'
// 		// $text = str_replace("\n", '-NLC-', $stringWithPlus); // Replace new lines with '-NLC-'
// 		$text = "Happy+Holidays+from+".str_replace(" ","+",$sms_shop_name)."!+-NLC-+Wishing+you+joy,+peace,+and+prosperity+in+the+coming+year-NLC-+Thanks+for+your+support!+🎄🎁✨";
// 	}
// 	if($out){
// 		$query="SELECT `mobile`,`id` FROM cust WHERE `associated_group` = '$group'";
// 		$result=mysqli_query($conn,$query);
// 		while($row=mysqli_fetch_array($result)){
// 			$cust_mobile = $row[0];
// 			$cust_id = $row[1];
// 			$timenow = timeNow();
// 			$query1 = "INSERT INTO special_event_sms (`cust`,`text`,`timestamp`,`sent_by`,`status`) VALUES ('$cust_id','$text','$timenow','$sent_by','0')";
// 			$result1 = mysqli_query($conn, $query1);
// 			$last_id=mysqli_insert_id($conn);
// 			if ($result1) {
// 				sendCustomSMS(1,$sub_system,$last_id,$cust_mobile,$text);
// 			}else{
// 				$out=false;
// 				$message = "Error : Could not send the message";
// 			}
// 		}
// 	}
// 	return $out;
// }

// added by nirmal 07_11_2024

function getChequeNames()
{
	global $cheque_name_id, $cheque_name;
	$cheque_name_id = $cheque_name = [];
	include('config.php');
	$query = "SELECT `id`,`name` FROM cheque_name WHERE `status`='1'";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$cheque_name_id[] = $row[0];
		$cheque_name[] = $row[1];
	}
}
function getSalesman5($sub_system)
{
	global $manage_user_id, $manage_user_name;
	include('config.php');
	$query = "SELECT DISTINCT up.id,up.username FROM userprofile up, permission pe, `function` fn WHERE up.id=pe.`user` AND pe.`function`=fn.id AND up.`sub_system`='$sub_system' AND up.`status`='0' AND fn.`status`=1 AND (fn.`name`='Supervisor' OR fn.`name`='Manager' OR fn.`name`='Top Manager') ORDER BY up.username";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$manage_user_id[] = $row[0];
		$manage_user_name[] = $row[1];
	}
}

// updated by nirmal 28_01_2025 (send payment sms, after bank payment approved)
function changePaymentDepositStatusAjax($sub_system)
{
	include('config.php');
	$qb_msg = '';
	$user_id = $_COOKIE['user_id'];
	$bank_payment_updated = false;

	// Check if deposit_id and status are set in the POST request
	if (isset($_POST['deposit_id']) && isset($_POST['status'])) {
		$deposit_id = mysqli_real_escape_string($conn, $_POST['deposit_id']);
		$status = mysqli_real_escape_string($conn, $_POST['status']);

		try {
			// Start MySQL transaction
			mysqli_begin_transaction($conn);

			$query = "SELECT pd.`type`, pd.`payment_id`, pd.`transfer_source`, up.`username`, pd.`amount`, p.`cust`, p.`amount`
			FROM payment_deposit pd, userprofile up, payment p
			WHERE p.`id` = pd.`payment_id` AND pd.`placed_by` = up.`id` AND pd.`id`='$deposit_id'";
			$row = mysqli_fetch_row(mysqli_query($conn2, $query));
			$type = $row[0];
			$payment_id = $row[1];
			$transfer_source = $row[2];
			$placed_by = $row[3];
			$amount = $row[4];
			$cust = $row[5];
			$payment_amount = $row[6];

			// if bank add qb journal entry
			if ((isQuickBooksActive(1))) {
				if ($type == 1 && $transfer_source == 2 && $status == 2) { // cash deposit to bank
					$journal_entry_result = $journalEntryForBankDeposit = [];
					$bnk_qry = "SELECT `bank_id` FROM `payment_deposit` WHERE `id`='$deposit_id'";
					$bnk_row = mysqli_fetch_row(mysqli_query($conn2, $bnk_qry));
					$account_id = $bnk_row[0];

					$accountQuery = "SELECT `name` FROM `accounts` WHERE `id`='$account_id'";
					$accountResult = mysqli_query($conn, $accountQuery);
					$accountRow = mysqli_fetch_assoc($accountResult);
					;
					$bank_name = $accountRow['name'];

					$debitAccountName = $bank_name;
					$creditAccountName = "Cash on Hand";
					$description = "[CASH ON HAND DEPOSIT] - Bank : ($bank_name), User : $placed_by";
					$debitEntityType = "";
					$debitEntityID = "";
					$creditEntityType = "";
					$creditEntityID = "";

					$journalEntryForBankDeposit = buildJournalEntry($conn2, $amount, $debitAccountName, $creditAccountName, $description, $debitEntityType, $debitEntityID, $creditEntityType, $creditEntityID);
					if (isset($journalEntryForBankDeposit['error'])) {
						$qb_msg = $journalEntryForBankDeposit['error'];
						throw new Exception($qb_msg);
					} else {
						try {
							$journal_entry_result = QBAddJournalEntry($journalEntryForBankDeposit);
							$qb_msg = $journal_entry_result['message'];
							if ((isset($journal_entry_result['status'])) && ($journal_entry_result['status'] == 'success')) {
								$qb_journal_entry_id = $journal_entry_result['qb_journal_entry_id'];
								$query = "UPDATE `payment_deposit` SET `qb_id`='$qb_journal_entry_id' WHERE `id`='$deposit_id'";
								$result1 = mysqli_query($conn, $query);
								if (!$result1) {
									$qb_msg = 'Error: QuickBooks payment ID update error.';
									throw new Exception($qb_msg);
								}
							} else {
								throw new Exception($qb_msg);
							}
						} catch (Exception $e) {
							$qb_msg = "<br>QuickBooks error: " . $e->getMessage();
							$journal_entry_result['status'] = 'error';
							throw new Exception($qb_msg);
						}
					}
				}
				if ($type == 2 && $transfer_source == 2 && $status == 2) {
					$paymentQuery = "SELECT py.`invoice_no`, py.`bank_trans`,py.`comment`, cu.`name`, py.`amount`, cu.`qb_cust_id` FROM payment py, cust cu WHERE py.`cust` = cu.`id` AND py.`id` = '$payment_id' AND py.`status` = '2'";
					$paymentResult = mysqli_query($conn2, $paymentQuery);
					if ($paymentResult && mysqli_num_rows($paymentResult) > 0) {
						$paymentRow = mysqli_fetch_row($paymentResult);
						$invoice_no = $paymentRow[0];
						$tr_bank = $paymentRow[1];
						$comment = $paymentRow[2];
						$custName = $paymentRow[3];
						$amount_bank = $paymentRow[4];
						$qb_cust_id = $paymentRow[5];

						if (is_numeric($amount_bank) && $amount_bank > 0) { // Bank payment
							$accountNameQuery = "SELECT `name` FROM accounts WHERE id='$tr_bank'";
							$accountNameResult = mysqli_query($conn2, $accountNameQuery);
							if ($accountNameResult && mysqli_num_rows($accountNameResult) > 0) {
								$accountNameRow = mysqli_fetch_row($accountNameResult);
								$debitAccountName = $accountNameRow[0];
								$creditAccountName = 'Accounts Receivable (A/R)';
								$description = "[PAYMENT] - Method: Bank Payment ($debitAccountName), Ref: $comment, Invoice No: $invoice_no, Customer: $custName";
								$result_array = processQBPayment($creditAccountName, $debitAccountName, $conn, $amount_bank, $qb_cust_id, $description);
								if (is_array($result_array)) {
									try {
										$journal_entry_result = QBAddJournalEntry($result_array);
										$qb_msg = $journal_entry_result['message'];

										if ((isset($journal_entry_result['status'])) && ($journal_entry_result['status'] == 'success')) {
											$qb_journal_entry_id = $journal_entry_result['qb_journal_entry_id'];
											$query = "UPDATE `payment` SET `qb_id`='$qb_journal_entry_id', `qb_status`=1, `status` =0 WHERE `id`='$payment_id'";
											$result1 = mysqli_query($conn, $query);
											if (!$result1) {
												$qb_msg = 'QuickBooks payment ID update error.';
												throw new Exception($qb_msg);
											} else {
												$bank_payment_updated = true;
											}
										} else {
											throw new Exception($qb_msg);
										}
									} catch (Exception $e) {
										$qb_msg = "<br>QuickBooks error: " . $e->getMessage();
										$journal_entry_result['status'] = 'error';
										throw new Exception($qb_msg);
									}
								} else {// Handle the error
									$qb_msg = $result_array;
									throw new Exception($qb_msg);
								}
							}
						}
					}
				}
			} else {
				if ($type == 2 && $transfer_source == 2 && $status == 2) { // if bank payment
					$query = "UPDATE `payment` SET `status` = 0 WHERE `id`='$payment_id'";
					$result1 = mysqli_query($conn, $query);
					if (!$result1) {
						$message = 'Failed to update status: ' . mysqli_error($conn);
						throw new Exception($qb_msg);
					} else {
						$bank_payment_updated = true;
					}
				}
			}

			$query = "SELECT `approved_by` FROM `payment_deposit` WHERE `id`='$deposit_id'";
			$row = mysqli_fetch_row(mysqli_query($conn2, $query));
			$approved_by = $row[0];

			if (empty($approved_by) && $status == 2) {
				$query1 = "UPDATE payment_deposit SET `status` = '$status', `user_id` = '$user_id', `approved_by` = '$user_id' WHERE id = '$deposit_id'";
			} else if ((!empty($approved_by)) && ($status == 3)) {
				$query2 = "SELECT `from` FROM `payment_deposit_trans` WHERE `payament_deposit_id`='$deposit_id'";
				$row2 = mysqli_fetch_row(mysqli_query($conn2, $query2));
				$from = $row2[0];

				$query1 = "UPDATE payment_deposit SET `status` = '2', `user_id` = '$from' WHERE id = '$deposit_id'";
			} else {
				$query1 = "UPDATE payment_deposit SET `status` = '$status', `user_id` = '$user_id' WHERE id = '$deposit_id'";
			}

			if (!mysqli_query($conn, $query1)) {
				// Return error response
				$message = 'Failed to update status: ' . mysqli_error($conn);
				throw new Exception($message);
			}

			// Commit the transaction
			mysqli_commit($conn);

			// send sms when bank payment approved
			if ($bank_payment_updated = true) {
				sendPaymentSMS($payment_id, $cust, $payment_amount);
			}
			echo json_encode(['success' => true, 'message' => 'Status updated successfully. ' . $qb_msg]);
		} catch (Exception $e) {
			// Rollback the transaction on error
			mysqli_rollback($conn);
			$message = $e->getMessage();
			error_log("Error in changePaymentDepositStatusAjax(): " . $message); // Log the error

			echo json_encode(['success' => false, 'message' => $message]);
		}
	} else {
		// Return error response for missing parameters
		echo json_encode(['success' => false, 'message' => 'Invalid input parameters.']);
	}
}

function getPendingCashDeposits($sub_system)
{
	global $payment_id, $payment_type, $payment_amount, $payment_received, $payment_note, $payment_date, $payment_status, $payment_placed_by, $payment_source, $fromdate, $todate,
	$payment_image;
	include('config.php');
	$user_id = $_COOKIE['user_id'];
	$payment_id = $payment_type = $payment_amount = $payment_received = $payment_note = $payment_date = $payment_source = $payment_status = $payment_placed_by = $payment_image = array();
	$user_qry = $bank_qry = $date_qry = $status_qry = $source_qry = $salesman_qry = '';

	// Handle salesman filter
	if ((isset($_REQUEST['salesaman'])) && ($_REQUEST['salesaman'] != '')) {
		$py_salesaman = $_REQUEST['salesaman'];
		$salesman_qry = " AND pd.`placed_by` = '$py_salesaman'";
	}

	// Handle soruce filter
	if ((isset($_REQUEST['source'])) && ($_REQUEST['source'] != '')) {
		$py_source = $_REQUEST['source'];
		$source_qry = " AND pd.`transfer_source` = '$py_source'";
	}
	// Handle bank filter
	if ((isset($_REQUEST['bank'])) && ($_REQUEST['bank'] != '')) {
		$py_bank = $_REQUEST['bank'];
		$bank_qry = " AND pd.`bank_id` = '$py_bank'";
	}

	// Set date filters based on input parameters
	if (isset($_GET['datefrom']) && $_GET['datefrom'] != '') {
		$fromdate = $_GET['datefrom'];
	} else {
		$fromdate = ''; // No specific start date if not provided
	}

	if (isset($_GET['dateto']) && $_GET['dateto'] != '') {
		$todate = $_GET['dateto'];
	} else {
		$todate = dateNow(); // Set to today's date if no end date provided
	}

	// Create date filter query
	if (!empty($fromdate) && !empty($todate)) {
		$date_qry = " AND DATE(pd.transfer_date) BETWEEN '$fromdate' AND '$todate'";
	} elseif (!empty($fromdate)) {
		$date_qry = " AND DATE(pd.transfer_date) >= '$fromdate'";
	}

	$query = "SELECT pd.`id`, pd.`type`, pd.`amount`, pd.`transfer_date`, pd.`transfer_source`, pd.`transfer_note`, pd.`status`, pd.`image`,
		COALESCE(u.username, a.name, 'Unknown') AS bank_or_user_name,
		pu.username AS placed_by_username
	FROM payment_deposit pd
	LEFT JOIN userprofile u ON pd.`user_id` = u.id
	LEFT JOIN userprofile pu ON pd.`placed_by` = pu.id
	LEFT JOIN accounts a ON pd.`bank_id` = a.id
	WHERE (pd.`user_id` = '$user_id' OR pd.`bank_id` IS NOT NULL)
	AND pd.`status` = 1 AND pd.`type` = 1 $source_qry $bank_qry $date_qry $salesman_qry ORDER BY pd.`transfer_date` DESC";

	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$payment_id[] = $row[0];
		$payment_type[] = $row[1];
		$payment_amount[] = $row[2];
		$payment_date[] = $row[3];
		$payment_source[] = $row[4];
		$payment_note[] = $row[5];
		$payment_status[] = $row[6];
		$payment_image[] = $row[7];
		$payment_received[] = $row[8];
		$payment_placed_by[] = $row[9];
	}
}

// update by nirmal 30_01_2025 (added payment status to remove deleted payment)
function getPendingBankDeposits($sub_system)
{
	global $payment_id, $invoice_no, $cust_name, $payment_amount, $bank_name, $comment, $payment_date, $payment_status, $payment_type, $payment_transfer_date, $payment_deposit_id,
	$payment_placed_by;
	$payment_id = $invoice_no = $cust_name = $payment_amount = $bank_name = $comment = $payment_date = $payment_status = $payment_type = $payment_transfer_date = $payment_deposit_id =
		$payment_placed_by = [];
	include('config.php');
	$user_id = $_COOKIE['user_id'];
	$user_qry = $bank_qry = $date_qry = $status_qry = $source_qry = $salesman_qry = '';

	// Handle salesman filter
	if ((isset($_REQUEST['salesaman'])) && ($_REQUEST['salesaman'] != '')) {
		$py_salesaman = $_REQUEST['salesaman'];
		$salesman_qry = " AND pd.`placed_by` = '$py_salesaman'";
	}

	// Handle bank filter
	if ((isset($_REQUEST['bank'])) && ($_REQUEST['bank'] != '')) {
		$py_bank = $_REQUEST['bank'];
		$bank_qry = " AND py.`bank_trans` = '$py_bank'";
	}

	// Set date filters based on input parameters
	if (isset($_GET['datefrom']) && $_GET['datefrom'] != '') {
		$fromdate = $_GET['datefrom'];
	} else {
		$fromdate = ''; // No specific start date if not provided
	}

	if (isset($_GET['dateto']) && $_GET['dateto'] != '') {
		$todate = $_GET['dateto'];
	} else {
		$todate = dateNow(); // Set to today's date if no end date provided
	}

	// Create date filter query
	if (!empty($fromdate) && !empty($todate)) {
		$date_qry = " AND DATE(pd.transfer_date) BETWEEN '$fromdate' AND '$todate'";
	} elseif (!empty($fromdate)) {
		$date_qry = " AND DATE(pd.transfer_date) >= '$fromdate'";
	}

	$query = "SELECT py.`id`, py.`invoice_no`, cu.`name`, py.`amount`, ac.`name`, py.`comment`, py.`payment_date`, pd.`status`, pd.`type`, pd.`transfer_date`, pd.`id`, up.`username`
	FROM payment_deposit pd
	JOIN payment py ON pd.`payment_id` = py.`id`
	JOIN cust cu ON py.`cust` = cu.`id`
	JOIN userprofile up ON pd.`placed_by` = up.`id`
	JOIN accounts ac ON py.`bank_trans` = ac.`id`
	WHERE py.`status` = '2' AND pd.`status` = '1' AND pd.`type` = '2' $bank_qry $date_qry $salesman_qry ORDER BY pd.`transfer_date` DESC";

	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$payment_id[] = $row[0];
		$invoice_no[] = $row[1];
		$cust_name[] = $row[2];
		$payment_amount[] = $row[3];
		$bank_name[] = $row[4];
		$comment[] = $row[5];
		$payment_date[] = $row[6];
		$payment_status[] = $row[7];
		$payment_type[] = $row[8];
		$payment_transfer_date[] = $row[9];
		$payment_deposit_id[] = $row[10];
		$payment_placed_by[] = $row[11];
	}
}

// update by nirmal 30_01_2025 (added payment status to remove deleted payment show)
function getBankDepositsReport($sub_system)
{
	global $payment_id, $invoice_no, $cust_name, $payment_amount, $bank_name, $comment, $payment_date, $payment_status, $payment_type, $payment_transfer_date, $payment_deposit_id,
	$payment_placed_by, $payment_reviewed_by, $fromdate, $todate;
	$payment_id = $invoice_no = $cust_name = $payment_amount = $bank_name = $comment = $payment_date = $payment_status = $payment_type = $payment_transfer_date = $payment_deposit_id =
		$payment_placed_by = $payment_reviewed_by = [];
	include('config.php');
	$user_id = $_COOKIE['user_id'];
	$user_qry = $bank_qry = $date_qry = $status_qry = $source_qry = $salesman_qry = $reviewed_by_qry = '';

	// Handle salesman filter
	if ((isset($_REQUEST['salesaman'])) && ($_REQUEST['salesaman'] != '')) {
		$py_salesaman = $_REQUEST['salesaman'];
		$salesman_qry = " AND pd.`placed_by` = '$py_salesaman'";
	}

	// Handle reviewed filter
	if ((isset($_REQUEST['reviewed_by'])) && ($_REQUEST['reviewed_by'] != '')) {
		$py_reviewed_by = $_REQUEST['reviewed_by'];
		$reviewed_by_qry = " AND pd.`approved_by` = '$py_reviewed_by'";
	}

	if ($_REQUEST['components'] == 'supervisor') {
		$reviewed_by_qry = " AND pd.`approved_by` = '$user_id'";
	}

	// Handle status filter
	if (isset($_REQUEST['status']) && $_REQUEST['status'] != '') {
		$py_status = $_REQUEST['status'];
		$status_qry = " AND pd.status = '$py_status'";
	}

	// Handle bank filter
	if ((isset($_REQUEST['bank'])) && ($_REQUEST['bank'] != '')) {
		$py_bank = $_REQUEST['bank'];
		$bank_qry = " AND py.`bank_trans` = '$py_bank'";
	}

	// Set date filters based on input parameters
	if (isset($_GET['datefrom']) && $_GET['datefrom'] != '') {
		$fromdate = $_GET['datefrom'];
	} else {
		// Set to one month back from the current date
		$fromdate = date('Y-m-d', strtotime('-1 month'));
	}

	if (isset($_GET['dateto']) && $_GET['dateto'] != '') {
		$todate = $_GET['dateto'];
	} else {
		$todate = dateNow(); // Set to today's date if no end date provided
	}

	// Create date filter query
	if (!empty($fromdate) && !empty($todate)) {
		$date_qry = " AND DATE(pd.transfer_date) BETWEEN '$fromdate' AND '$todate'";
	} elseif (!empty($fromdate)) {
		$date_qry = " AND DATE(pd.transfer_date) >= '$fromdate'";
	}

	$query = "SELECT py.`id`, py.`invoice_no`, cu.`name`, py.`amount`, ac.`name`, py.`comment`, py.`payment_date`, pd.`status`, pd.`type`, pd.`transfer_date`, pd.`id`,
	 u.`username`, up.`username`
	FROM payment_deposit pd
	JOIN payment py ON pd.`payment_id` = py.`id`
	JOIN cust cu ON py.`cust` = cu.`id`
	JOIN userprofile up ON pd.`placed_by` = up.`id`
	JOIN accounts ac ON py.`bank_trans` = ac.`id`
	LEFT JOIN userprofile u ON pd.`approved_by` = u.`id`
	WHERE pd.`type` = '2' AND py.`status` != 1 $status_qry $bank_qry $date_qry $salesman_qry $reviewed_by_qry ORDER BY pd.`transfer_date` DESC";

	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$payment_id[] = $row[0];
		$invoice_no[] = $row[1];
		$cust_name[] = $row[2];
		$payment_amount[] = $row[3];
		$bank_name[] = $row[4];
		$comment[] = $row[5];
		$payment_date[] = $row[6];
		$payment_status[] = $row[7];
		$payment_type[] = $row[8];
		$payment_transfer_date[] = $row[9];
		$payment_deposit_id[] = $row[10];
		$payment_reviewed_by[] = $row[11];
		$payment_placed_by[] = $row[12];
	}
}

function getCashDepositsReport($sub_system)
{
	global $payment_id, $payment_type, $payment_amount, $payment_received, $payment_source, $payment_note, $payment_date, $payment_status, $payment_placed_by, $payment_reviewed_by,
	$payment_image, $fromdate, $todate;
	include('config.php');
	$payment_id = $payment_type = $payment_amount = $payment_received = $payment_source = $payment_note = $payment_date = $payment_status = $payment_placed_by = $payment_reviewed_by =
		$payment_image = array();
	$user_id = $_COOKIE['user_id'];
	$user_qry = $bank_qry = $date_qry = $status_qry = $source_qry = $reviewed_by_qry = '';

	// Handle soruce filter
	if ((isset($_REQUEST['source'])) && ($_REQUEST['source'] != '')) {
		$py_source = $_REQUEST['source'];
		$source_qry = " AND pd.`transfer_source` = '$py_source'";
	}
	// Handle bank filter
	if ((isset($_REQUEST['bank'])) && ($_REQUEST['bank'] != '')) {
		$py_bank = $_REQUEST['bank'];
		$bank_qry = " AND pd.`bank_id` = '$py_bank'";
	}

	// Handle user filter
	if ((isset($_REQUEST['user'])) && ($_REQUEST['user'] != '')) {
		$py_user = $_REQUEST['user'];
		$user_qry = " AND pd.`placed_by` = '$py_user'";
	}

	// Handle reviewed filter
	if ((isset($_REQUEST['reviewed_by'])) && ($_REQUEST['reviewed_by'] != '')) {
		$py_reviewed_by = $_REQUEST['reviewed_by'];
		$reviewed_by_qry = " AND pd.`approved_by` = '$py_reviewed_by'";
	}

	if ($_REQUEST['components'] == 'supervisor') {
		$reviewed_by_qry = " AND pd.`approved_by` = '$user_id'";
	}

	// Handle status filter
	if (isset($_REQUEST['status']) && $_REQUEST['status'] != '') {
		$py_status = $_REQUEST['status'];
		$status_qry = " AND pd.status = '$py_status'";
	}

	// Set date filters based on input parameters
	if (isset($_GET['datefrom']) && $_GET['datefrom'] != '') {
		$fromdate = $_GET['datefrom'];
	} else {
		// Set to one month back from the current date
		$fromdate = date('Y-m-d', strtotime('-1 month'));
	}

	if (isset($_GET['dateto']) && $_GET['dateto'] != '') {
		$todate = $_GET['dateto'];
	} else {
		$todate = dateNow(); // Set to today's date if no end date provided
	}

	// Create date filter query
	if (!empty($fromdate) && !empty($todate)) {
		$date_qry = " AND DATE(pd.transfer_date) BETWEEN '$fromdate' AND '$todate'";
	} elseif (!empty($fromdate)) {
		$date_qry = " AND DATE(pd.transfer_date) >= '$fromdate'";
	}

	$query = "SELECT pd.`id`, pd.`type`, pd.`amount`, pd.`transfer_date`,pd.`transfer_source`, pd.`transfer_note`, pd.`status`, up.`username`, uu.`username`, pd.`image`,
		CASE
			WHEN pd.`user_id` IS NOT NULL THEN u.username
			WHEN pd.`bank_id` IS NOT NULL THEN a.name
			ELSE 'Unknown'
		END AS bank_or_user_name
	FROM payment_deposit pd
	JOIN userprofile up ON pd.`placed_by` = up.id
	LEFT JOIN userprofile u ON pd.`user_id` = u.id
	LEFT JOIN userprofile uu ON pd.`approved_by` = uu.id
	LEFT JOIN accounts a ON pd.`bank_id` = a.id
	WHERE pd.`type` = '1' $source_qry $status_qry $user_qry $reviewed_by_qry $bank_qry $date_qry ORDER BY pd.`transfer_date` DESC";

	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$payment_id[] = $row[0];
		$payment_type[] = $row[1];
		$payment_amount[] = $row[2];
		$payment_date[] = $row[3];
		$payment_source[] = $row[4];
		$payment_note[] = $row[5];
		$payment_status[] = $row[6];
		$payment_placed_by[] = $row[7];
		$payment_reviewed_by[] = $row[8];
		$payment_image[] = $row[9];
		$payment_received[] = $row[10];
	}
}

function getCashOnHandReport($sub_system)
{
	global $amount_to_settle, $payment_in_trans, $user_id, $user_name, $fromdate, $todate;
	$amount_to_settle = $payment_in_trans = $user_id = $user_name = [];
	$payment_cash_total_sum = $payment_deposit_total_sum = $payment_deposit_to_user_sum = 0;
	$date_qry = $date_qry1 = '';
	include('config.php');
	$start_date = salesmanPaymentDepositStartDate();

	// Set date filters based on input parameters
	if (isset($_GET['datefrom']) && $_GET['datefrom'] != '') {
		$fromdate = $_GET['datefrom'];
	} else {
		// Set to one month back from the current date
		$fromdate = date('Y-m-d', strtotime('-1 month'));
	}

	if (isset($_GET['dateto']) && $_GET['dateto'] != '') {
		$todate = $_GET['dateto'];
	} else {
		$todate = dateNow(); // Set to today's date if no end date provided
	}

	// Create date filter query
	if (!empty($fromdate) && !empty($todate)) {
		$date_qry = " AND DATE(transfer_date) BETWEEN '$fromdate' AND '$todate'";
		$date_qry1 = " AND DATE(payment_date) BETWEEN '$fromdate' AND '$todate'";
	} elseif (!empty($fromdate)) {
		$date_qry = " AND DATE(transfer_date) >= '$fromdate'";
		$date_qry1 = " AND DATE(payment_date) >= '$fromdate'";
	}

	$query = "SELECT up.id,up.username FROM userprofile up WHERE up.`sub_system`='$sub_system' AND up.`status`='0' ORDER BY up.username";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$user_id[] = $row[0];
		$user_name[] = $row[1];

		$query1 = "SELECT SUM(amount) FROM payment WHERE `salesman` = '$row[0]' AND `status` = 0 AND `payment_type` = 1 AND payment_date > '$start_date' $date_qry1";
		$result1 = mysqli_query($conn2, $query1);
		$row1 = mysqli_fetch_row($result1);
		$payment_cash_total_sum = $row1[0];

		$query2 = "SELECT SUM(amount) FROM payment_deposit WHERE `type` = 1 AND `placed_by` = '$row[0]' AND `status` NOT IN(0,3) $date_qry";
		$result2 = mysqli_query($conn2, $query2);
		$row2 = mysqli_fetch_row($result2);
		$payment_deposit_total_sum = $row2[0];

		$query3 = "SELECT SUM(amount) FROM payment_deposit WHERE `type` = 1 AND `transfer_source` = 1 AND `user_id` = '$row[0]' AND `status` NOT IN(0,1,3) $date_qry";
		$result3 = mysqli_query($conn2, $query3);
		$row3 = mysqli_fetch_row($result3);
		$payment_deposit_to_user_sum = $row3[0];

		$query4 = "SELECT SUM(amount) FROM payment_deposit WHERE `type` = 1 AND `placed_by` = '$row[0]' AND `status` IN(1) $date_qry";
		$result4 = mysqli_query($conn2, $query4);
		$row4 = mysqli_fetch_row($result4);
		$payment_in_trans[] = $row4[0];

		$amount_to_settle[] = ($payment_cash_total_sum - $payment_deposit_total_sum) + $payment_deposit_to_user_sum;
	}
}

// update by nirmal 30_01_2025 (added payment status to remove deleted payment show)
function getPendingChequeTransfers($sub_system)
{
	include('config.php');
	global $payment_id, $invoice_no, $cust_name, $payment_amount, $bank_name, $comment, $payment_date, $chq_full_no, $chq_date, $trans_id, $trans_to, $trans_time, $trans_from, $trans_status,
	$fromdate, $todate, $salesman, $modify_cheque;
	$payment_id = $invoice_no = $cust_name = $payment_amount = $bank_name = $comment = $payment_date = $chq_full_no = $chq_date = $trans_id = $trans_to = $trans_time = $trans_from = $trans_status =
		$salesman = $modify_cheque = [];
	$user_id = $_COOKIE['user_id'];
	$date_qry = $status_qry = $salesman_qry = $from_qry = '';

	// Handle salesman filter
	if ((isset($_REQUEST['salesman'])) && ($_REQUEST['salesman'] != '')) {
		$py_salesaman = $_REQUEST['salesman'];
		$salesman_qry = " AND p.`salesman` = '$py_salesaman'";
	}

	// Handle from filter
	if ((isset($_REQUEST['from'])) && ($_REQUEST['from'] != '')) {
		$py_from = $_REQUEST['from'];
		$from_qry = " AND ct.`from` = '$py_from'";
	}

	// Handle status filter
	if (isset($_REQUEST['status']) && $_REQUEST['status'] != '') {
		$py_status = $_REQUEST['status'];
		$status_qry = " AND ct.status = '$py_status'";
	} else {
		$status_qry = 'AND ct.`status` IN(0,4)';
	}

	// Set date filters
	if (isset($_GET['datefrom']) && $_GET['datefrom'] != '') {
		$fromdate = $_GET['datefrom'];
	} else {
		// Set to one month back from the current date
		$fromdate = date('Y-m-d', strtotime('-1 month'));
	}

	if (isset($_GET['dateto']) && $_GET['dateto'] != '') {
		$todate = $_GET['dateto'];
	} else {
		$todate = dateNow(); // Set to today's date if no end date provided
	}

	// Create date filter query
	if (!empty($fromdate) && !empty($todate)) {
		$date_qry = " AND DATE(ct.time) BETWEEN '$fromdate' AND '$todate'";
	} elseif (!empty($fromdate)) {
		$date_qry = " AND DATE(ct.time) >= '$fromdate'";
	}

	$query = "SELECT ct.id AS id, p.id AS payment_id, p.chque_no, p.chque_bank, p.chque_branch, p.chque_date, p.amount, c.name AS customer_name, p.payment_date,
	b.name AS bank_name, b.bank_code, ct.time,u.username AS `to`, up.username AS `from`, ct.status, upp.username, ct.modify_cheque
	FROM cheque_trans ct JOIN payment p ON ct.payment_id = p.id
	JOIN bank b ON p.chque_bank = b.id
	JOIN cust c ON p.cust = c.id
    JOIN userprofile u ON ct.to = u.id
    JOIN userprofile up ON ct.from = up.id
    JOIN userprofile upp ON p.salesman = upp.id
	WHERE p.`status` = 0 AND ct.`to` = $user_id $status_qry $salesman_qry $from_qry $date_qry AND ct.`latest` = 1 ORDER BY ct.id DESC";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$trans_id[] = $row[0];
		$payment_id[] = $row[1];
		$chq_no = $row[2];
		$chq_bnk = $row[3];
		$chq_branch = $row[4];
		$chq_date[] = $row[5];
		$payment_amount[] = $row[6];
		$cust_name[] = $row[7];
		$payment_date[] = $row[8];
		$bank_name[] = $row[9];
		$chq_bnk = $row[10];
		$trans_time[] = $row[11];
		$trans_from[] = $row[13];
		$trans_status[] = $row[14];
		$salesman[] = $row[15];
		$modify_cheque[] = $row[16];

		if ($chq_bnk > 0) {
			$chq_full_no[] = $chq_no . '-' . $chq_bnk . '-' . $chq_branch;
		} else {
			$chq_full_no[] = '';
		}
	}
}

function addChequeTransferAjax($sub_system)
{
	include('config.php');
	$time_now = timeNow();
	$out = true;
	$message = '';
	$user_id = $_COOKIE['user_id'];

	// Check if deposit_id and status are set in the POST request
	if (isset($_POST['deposit_id']) && isset($_POST['status'])) {
		$deposit_id = mysqli_real_escape_string($conn, $_POST['deposit_id']);
		$tarns_status = mysqli_real_escape_string($conn, $_POST['status']);

		if ($deposit_id == '') {
			$out = false;
			$message = 'Payment ID cannot be null.';
		}

		if ($out && $tarns_status == '') {
			$out = false;
			$message = 'Payment status cannot be null.';
		}

		if ($out) {
			// Check if record exists
			$query = "SELECT `from`,`payment_id`,`status`,`modify_cheque` FROM `cheque_trans` WHERE `id`='$deposit_id' AND `to` = $user_id AND `status` IN (0,4) AND `latest` = 1";
			$result = mysqli_query($conn2, $query);
			if ($result) {
				$row = mysqli_fetch_row($result);
				$from = $row[0];
				$payment_id = $row[1];
				$status = $row[2];
				$modify_cheque = $row[3];

				if ($status == 0) {
					if ($tarns_status != 1 || $tarns_status != 2) {
						$out = false;
						$message = 'You can only accept or reject this payment.';
					}
				}

				if ($status == 4) {
					if ($tarns_status != 5 || $tarns_status != 6) {
						$out = false;
						$message = 'You can only accept or reject this payment (in-trans).';
					}
				}

				if ($from <= 0) {
					$out = false;
					$message = 'No eligible payment record found for this payment number.';
				} else {
					try {
						// Start transaction
						if (!mysqli_begin_transaction($conn)) {
							throw new Exception("Could not begin transaction: " . mysqli_error($conn));
						}
						// Insert new cheque transaction
						if ($modify_cheque == 1) {
							$query1 = "INSERT INTO cheque_trans (`from`, `to`, `payment_id`, `time`, `status`, `latest`,`modify_cheque`)
							VALUES ('$user_id', '$from', '$payment_id', '$time_now', '$tarns_status', 1, 1)";
						} else {
							$query1 = "INSERT INTO cheque_trans (`from`, `to`, `payment_id`, `time`, `status`, `latest`)
							VALUES ('$user_id', '$from', '$payment_id', '$time_now', '$tarns_status', 1)";
						}
						if (!($result1 = mysqli_query($conn, $query1))) {
							throw new Exception("Failed to insert cheque transaction: " . mysqli_error($conn));
						}

						$lastid_temp = mysqli_insert_id($conn);
						if (empty($lastid_temp)) {
							throw new Exception("Failed to get last inserted ID.");
						}

						// Update previous transactions
						$query2 = "UPDATE cheque_trans SET `latest` = NULL WHERE `payment_id` = '$payment_id' AND `id` != '$lastid_temp'";
						if (!($result2 = mysqli_query($conn, $query2))) {
							throw new Exception("Failed to update previous cheque transactions: " . mysqli_error($conn));
						}

						// Commit the transaction if all queries succeed
						if (!mysqli_commit($conn)) {
							throw new Exception("Transaction commit failed: " . mysqli_error($conn));
						}

						$out = true;
						$message = "Cheque transfer updated successfully.";
					} catch (Exception $e) {
						mysqli_rollback($conn); // Rollback transaction on error
						$message = 'Transaction failed: ' . $e->getMessage();
					}
				}
			} else {
				$out = false;
				$message = 'Query failed: ' . mysqli_error($conn);
			}
		}
	} else {
		$out = false;
		$message = 'Invalid input parameters.';
	}
	// Final JSON response
	echo json_encode(['success' => $out, 'message' => $message]);
}

// update by nirmal 30_01_2025 (added payment status to remove deleted payment show)
function getChequeApprovedByUser($sub_system)
{
	global $payment_id, $invoice_no, $cust_name, $payment_amount, $bank_name, $comment, $payment_date, $chq_full_no, $chq_date, $trans_id, $trans_to, $trans_time, $trans_from, $trans_status,
	$fromdate, $todate, $salesman;
	$payment_id = $invoice_no = $cust_name = $payment_amount = $bank_name = $comment = $payment_date = $chq_full_no = $chq_date = $trans_id = $trans_to = $trans_time = $trans_from = $trans_status =
		$salesman = [];
	include('config.php');
	$user_id = $_COOKIE['user_id'];
	$date_qry = $from_qry = $from_qry2 = '';

	// Handle from filter
	if ((isset($_REQUEST['from'])) && ($_REQUEST['from'] != '')) {
		$py_from = $_REQUEST['from'];
		$from_qry = " AND ct.`to` = '$py_from'";
		$from_qry2 = " AND ct.`from` = '$py_from'";
	}

	// Set date filters based on input parameters
	if (isset($_GET['datefrom']) && $_GET['datefrom'] != '') {
		$fromdate = $_GET['datefrom'];
	} else {
		// Set to one month back from the current date
		$fromdate = date('Y-m-d', strtotime('-1 month'));
	}

	if (isset($_GET['dateto']) && $_GET['dateto'] != '') {
		$todate = $_GET['dateto'];
	} else {
		$todate = dateNow(); // Set to today's date if no end date provided
	}

	// Create date filter query
	if (!empty($fromdate) && !empty($todate)) {
		$date_qry = " AND DATE(ct.time) BETWEEN '$fromdate' AND '$todate'";
	} elseif (!empty($fromdate)) {
		$date_qry = " AND DATE(ct.time) >= '$fromdate'";
	}

	$query = "SELECT ct.id AS id, p.id AS payment_id, p.chque_no, p.chque_bank, p.chque_branch, p.chque_date, p.amount, c.name AS customer_name, p.payment_date,
	b.name AS bank_name, b.bank_code, ct.time, up.username AS `from`, ct.status, u.username, upp.username
	FROM cheque_trans ct JOIN payment p ON ct.payment_id = p.id
	JOIN bank b ON p.chque_bank = b.id
	JOIN cust c ON p.cust = c.id
    JOIN userprofile u ON ct.to = u.id
    JOIN userprofile up ON ct.from = up.id
    JOIN userprofile upp ON p.salesman = upp.id
	WHERE (ct.`from` = $user_id AND ct.`status` IN(1,5,10) AND ct.`latest` = 1 $from_qry) OR (ct.`to` = $user_id AND ct.`status` = 6 AND ct.`latest` = 1 AND p.`status` = 0 $from_qry2) $date_qry
	ORDER BY ct.`id` DESC"; //1:accept, 5=accept-trans, 6=reject-trans

	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$trans_id[] = $row[0];
		$payment_id[] = $row[1];
		$chq_no = $row[2];
		$chq_bnk = $row[3];
		$chq_branch = $row[4];
		$chq_date[] = $row[5];
		$payment_amount[] = $row[6];
		$cust_name[] = $row[7];
		$payment_date[] = $row[8];
		$bank_name[] = $row[9];
		$chq_bnk = $row[10];
		$trans_time[] = $row[11];
		if ($row[13] == 5) {
			$trans_from[] = $row[14];
		} else {
			$trans_from[] = $row[12];
		}
		$trans_status[] = $row[13];
		$salesman[] = $row[15];

		if ($chq_bnk > 0) {
			$chq_full_no[] = $chq_no . '-' . $chq_bnk . '-' . $chq_branch;
		} else {
			$chq_full_no[] = '';
		}
	}
}

function addChequeTransferToUserAjax($sub_system)
{
	include('config.php');
	$user_id = $_COOKIE['user_id'];
	$status = 'error';
	$message = $payment_id = $username = $lastid_temp = $modify_cheque = $status = '';
	$time_now = timeNow();
	$out = true;

	$id = isset($_POST['id']) ? trim($_POST['id']) : ''; // trans id
	$user = isset($_POST['user']) ? trim($_POST['user']) : ''; // trans to

	if ($id == '') {
		$out = false;
		$message = 'Payment ID cannot be null.';
	}

	if (($out) && ($user == '')) {
		$out = false;
		$message = 'User cannot be null.';
	}

	if ($out) {
		// Validate if the selected user exists and has the required permission
		$query = "SELECT COUNT(up.id) AS user_count, up.`username` FROM userprofile up
		WHERE up.`id` = '$user' AND up.`id` != $user_id";
		$result = mysqli_query($conn2, $query);
		if ($row = mysqli_fetch_assoc($result)) {
			$username = $row['username'];
			$user_count = $row['user_count'];
			if ($user_count > 0) {
				$message = "User found: $username.";
				$out = true;
			} else {
				$message = 'Error: User not found or lacks permission.';
				$out = false;
			}
		} else {
			$message = 'Error: Query failed or no results.';
			$out = false;
		}
	}

	if ($out) {
		// Check if record exists
		$query = "SELECT COUNT(`id`), `payment_id`,`status`,`modify_cheque` FROM cheque_trans WHERE `id` ='$id' AND `latest` = 1 AND `status` IN(1,5,6,10)"; //1 accept, 5=accept-trans, 6=reject-trans, 10=modify-cheque
		$result = mysqli_query($conn2, $query);
		$row = mysqli_fetch_row($result);
		if ($row[0] <= 0) {
			$message = 'Error: could not find a record.';
			$out = false;
		} else {
			$payment_id = $row[1];
			$status = $row[2];
			$modify_cheque = $row[3];
		}
	}

	if ($out) {
		try {
			// Start transaction
			if (!mysqli_begin_transaction($conn)) {
				throw new Exception("Could not begin transaction: " . mysqli_error($conn));
			}
			// Insert new cheque transaction
			if ($status == 10) {
				$query1 = "INSERT INTO cheque_trans (`from`, `to`, `payment_id`, `time`, `status`, `latest`,`modify_cheque`)
					VALUES ('$user_id', '$user', '$payment_id', '$time_now', 4, 1, 1)";
			} else if ($modify_cheque == 1) {
				$query1 = "INSERT INTO cheque_trans (`from`, `to`, `payment_id`, `time`, `status`, `latest`,`modify_cheque`)
					VALUES ('$user_id', '$user', '$payment_id', '$time_now', 0, 1, 1)";
			} else {
				$query1 = "INSERT INTO cheque_trans (`from`, `to`, `payment_id`, `time`, `status`, `latest`)
					VALUES ('$user_id', '$user', '$payment_id', '$time_now', 4, 1)";
			}

			if (!($result1 = mysqli_query($conn, $query1))) {
				throw new Exception("Failed to insert cheque transaction: " . mysqli_error($conn));
			}

			$lastid_temp = mysqli_insert_id($conn);
			if (empty($lastid_temp)) {
				throw new Exception("Failed to get last inserted ID");
			}

			// Update previous transactions
			$query2 = "UPDATE cheque_trans SET `latest` = NULL WHERE `payment_id` = '$payment_id' AND `id` != '$lastid_temp'";
			if (!($result2 = mysqli_query($conn, $query2))) {
				throw new Exception("Failed to update previous cheque transactions: " . mysqli_error($conn));
			}

			// Commit transaction
			if (!mysqli_commit($conn)) {
				throw new Exception("Transaction commit failed: " . mysqli_error($conn));
			}

			$status = 'success';
			$message = 'Cheque transfer added successfully';
		} catch (Exception $e) {
			// Rollback transaction in case of any error
			mysqli_rollback($conn);

			// Log the error (consider using a proper logging mechanism)
			error_log('Cheque Transaction Error: ' . $e->getMessage());
			$message = 'Transaction failed: ' . $e->getMessage();
		}
	}
	echo json_encode(['status' => $status, 'message' => $message]);
}

// update by nirmal 30_01_2025 (added payment status to remove deleted payment show)
function getApprovedChequeTransfers($sub_system)
{
	include('config.php');
	global $payment_id, $invoice_no, $cust_name, $payment_amount, $bank_name, $comment, $payment_date, $chq_full_no, $chq_date, $trans_id, $trans_to, $trans_time, $trans_from, $trans_status,
	$salesman;
	$payment_id = $invoice_no = $cust_name = $payment_amount = $bank_name = $comment = $payment_date = $chq_full_no = $chq_date = $trans_id = $trans_to = $trans_time = $trans_from =
		$trans_status = $salesman = [];
	$user_id = $_COOKIE['user_id'];

	$query = "SELECT ct.id AS id, p.id AS payment_id, p.chque_no, p.chque_bank, p.chque_branch, p.chque_date, p.amount, c.name AS customer_name, p.payment_date,
	b.name AS bank_name, b.bank_code, ct.time,u.username AS `to`, up.username AS `from`, ct.status, upp.username
	FROM cheque_trans ct JOIN payment p ON ct.payment_id = p.id
	JOIN bank b ON p.chque_bank = b.id
	JOIN cust c ON p.cust = c.id
    JOIN userprofile u ON ct.to = u.id
    JOIN userprofile up ON ct.from = up.id
    JOIN userprofile upp ON p.salesman = upp.id
	WHERE p.`status` = 0 AND ct.`from` = $user_id AND ct.`status` IN(1,5) AND ct.`latest` = 1 ORDER BY ct.`id` DESC"; // 1: accept, 5: accept-trans
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$trans_id[] = $row[0];
		$payment_id[] = $row[1];
		$chq_no = $row[2];
		$chq_bnk = $row[3];
		$chq_branch = $row[4];
		$chq_date[] = $row[5];
		$payment_amount[] = $row[6];
		$cust_name[] = $row[7];
		$payment_date[] = $row[8];
		$bank_name[] = $row[9];
		$chq_bnk = $row[10];
		$trans_time[] = $row[11];
		$trans_from[] = $row[12];
		$trans_status[] = $row[14];
		$salesman[] = $row[15];

		if ($chq_bnk > 0) {
			$chq_full_no[] = $chq_no . '-' . $chq_bnk . '-' . $chq_branch;
		} else {
			$chq_full_no[] = '';
		}
	}
}

function changeChequeTransferStatusAjax($sub_system)
{
	include('config.php');
	$time_now = timeNow();
	$out = true;
	$message = $from = $payment_id = '';
	$user_id = $_COOKIE['user_id'];

	// Check if deposit_id and status are set in the POST request
	if (isset($_POST['deposit_id']) && isset($_POST['status'])) {
		$deposit_id = mysqli_real_escape_string($conn, $_POST['deposit_id']);
		$tarns_status = mysqli_real_escape_string($conn, $_POST['status']);

		if ($deposit_id == '') {
			$out = false;
			$message = 'Payment ID cannot be null.';
		}

		if ($out && $tarns_status == '') {
			$out = false;
			$message = 'Payment status cannot be null.';
		}

		if ($out && ($tarns_status != 7 && $tarns_status != 8 && $tarns_status != 9 && $tarns_status != 10)) {
			$out = false;
			$message = 'Payment status not in range';
		}

		if ($out) {
			// Check if record exists
			$query = "SELECT `payment_id` FROM `cheque_trans` WHERE `id`='$deposit_id' AND `from` = $user_id AND `status` IN (1,5) AND `latest` = 1";
			$result = mysqli_query($conn2, $query);
			if ($result) {
				$row = mysqli_fetch_row($result);
				if ($row && isset($row[0])) {
					$payment_id = $row[0]; // Assuming payment_id is the first column
				} else {
					$out = false;
					$message = 'No eligible payment record found for this payment number.';
				}

				if ($payment_id != '') {
					$query3 = "SELECT salesman FROM payment WHERE id = '$payment_id'";
					$result3 = mysqli_query($conn2, $query3);
					if ($result3) {
						$row3 = mysqli_fetch_row($result3);
						if ($row3 && isset($row3[0])) {
							$from = $row3[0];
						} else {
							$out = false;
							$message = 'No eligible payment record found for this payment number.';
						}
					} else {
						$out = false;
						$message = 'No eligible payment record found for this payment number.';
					}
				} else {
					$out = false;
					$message = 'No eligible payment record found for this payment number.';
				}

				if (isset($from) && $from <= 0) {
					$out = false;
					$message = 'No eligible payment record found for this payment number.';
				}

				if ($out) {
					try {
						// Start transaction
						if (!mysqli_begin_transaction($conn)) {
							throw new Exception("Could not begin transaction: " . mysqli_error($conn));
						}
						// Insert new cheque transaction
						$query1 = "INSERT INTO cheque_trans (`from`, `to`, `payment_id`, `time`, `status`, `latest`, `modify_cheque`)
						VALUES ('$user_id', '$from', '$payment_id', '$time_now', '$tarns_status', 1, 1)";
						if (!($result1 = mysqli_query($conn, $query1))) {
							throw new Exception("Failed to insert cheque transaction: " . mysqli_error($conn));
						}

						$lastid_temp = mysqli_insert_id($conn);
						if (empty($lastid_temp)) {
							throw new Exception("Failed to get last inserted ID.");
						}

						// Update previous transactions
						$query2 = "UPDATE cheque_trans SET `latest` = NULL WHERE `payment_id` = '$payment_id' AND `id` != '$lastid_temp'";
						if (!($result2 = mysqli_query($conn, $query2))) {
							throw new Exception("Failed to update previous cheque transactions: " . mysqli_error($conn));
						}

						// Commit the transaction if all queries succeed
						if (!mysqli_commit($conn)) {
							throw new Exception("Transaction commit failed: " . mysqli_error($conn));
						}

						$out = true;
						$message = "Cheque transfer updated successfully.";
					} catch (Exception $e) {
						mysqli_rollback($conn); // Rollback transaction on error
						$message = 'Transaction failed: ' . $e->getMessage();
					}
				}
			} else {
				$out = false;
				$message = 'Query failed: ' . mysqli_error($conn);
			}
		}
	} else {
		$out = false;
		$message = 'Invalid input parameters.';
	}
	// Final JSON response
	echo json_encode(['success' => $out, 'message' => $message]);
}

// update by nirmal 30_01_2025 (added payment status to remove deleted payment show)
function getChequeTransSummery($sub_system)
{
	include('config.php');
	global $payment_id, $invoice_no, $cust_name, $payment_amount, $bank_name, $comment, $payment_date, $chq_full_no, $chq_date, $trans_id, $trans_to, $trans_time, $status, $salesman,
	$fromdate, $todate;
	$payment_id = $invoice_no = $cust_name = $payment_amount = $bank_name = $comment = $payment_date = $chq_full_no = $chq_date = $trans_id = $trans_to = $trans_time = $status = $salesman = [];
	$user_id = $_COOKIE['user_id'];
	$date_qry = $inhand_qry = '';

	// Handle from filter
	if ((isset($_REQUEST['in-hand'])) && ($_REQUEST['in-hand'] != '')) {
		$py_inhand = $_REQUEST['in-hand'];
		$inhand_qry = " AND (ct.`from` = $py_inhand AND ct.`latest` = 1) OR (ct.to = $py_inhand AND ct.latest = 1 AND ct.status NOT IN(7,8,9,10))";
	}

	// Set date filters based on input parameters
	if (isset($_GET['datefrom']) && $_GET['datefrom'] != '') {
		$fromdate = $_GET['datefrom'];
	} else {
		// Set to one month back from the current date
		$fromdate = date('Y-m-d', strtotime('-1 month'));
	}

	if (isset($_GET['dateto']) && $_GET['dateto'] != '') {
		$todate = $_GET['dateto'];
	} else {
		$todate = dateNow(); // Set to today's date if no end date provided
	}

	// Create date filter query
	if (!empty($fromdate) && !empty($todate)) {
		$date_qry = " AND DATE(ct.time) BETWEEN '$fromdate' AND '$todate'";
	} elseif (!empty($fromdate)) {
		$date_qry = " AND DATE(ct.time) >= '$fromdate'";
	}

	$query = "SELECT ct.id AS id, p.id AS payment_id, p.chque_no, p.chque_bank, p.chque_branch, p.chque_date, p.amount, c.name AS customer_name, p.payment_date,
	b.name AS bank_name, b.bank_code, ct.time, u.username AS `to`, ct.status, up.username, uup.username AS `from`
	FROM cheque_trans ct JOIN payment p ON ct.payment_id = p.id
	JOIN bank b ON p.chque_bank = b.id
	JOIN cust c ON p.cust = c.id
    JOIN userprofile u ON ct.to = u.id
    JOIN userprofile up ON p.salesman = up.id
    JOIN userprofile uup ON ct.from = uup.id
	WHERE p.`status` = 0 AND ct.`latest` = 1 $inhand_qry $date_qry ORDER BY ct.id DESC";

	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$trans_id[] = $row[0];
		$payment_id[] = $row[1];
		$chq_no = $row[2];
		$chq_bnk = $row[3];
		$chq_branch = $row[4];
		$chq_date[] = $row[5];
		$payment_amount[] = $row[6];
		$cust_name[] = $row[7];
		$payment_date[] = $row[8];
		$bank_name[] = $row[9];
		$chq_bnk = $row[10];
		$trans_time[] = $row[11];
		if (($row[13] == 12) || ($row[13] == 5 || ($row[13] == 7) || ($row[13] == 8) || ($row[13] == 9) || ($row[13] == 10))) {
			$trans_to[] = $row[15];
		} else {
			$trans_to[] = $row[12];
		}
		$status[] = $row[13];
		$salesman[] = $row[14];

		if ($chq_bnk > 0) {
			$chq_full_no[] = $chq_no . '-' . $chq_bnk . '-' . $chq_branch;
		} else {
			$chq_full_no[] = '';
		}
	}
}

function getChequeTransfersStatusSummery($sub_system)
{
	include('config.php');
	global $payment_id, $invoice_no, $cust_name, $payment_amount, $bank_name, $comment, $payment_date, $chq_full_no, $chq_date, $trans_id, $trans_to, $trans_time, $status, $trans_from;
	$payment_id = $invoice_no = $cust_name = $payment_amount = $bank_name = $comment = $payment_date = $chq_full_no = $chq_date = $trans_id = $trans_to = $trans_time = $status = $trans_from = [];
	$user_id = $_COOKIE['user_id'];
	$out = true;
	$id = $_GET['id'];

	if ($id != '') {
		$query = "SELECT COUNT(id) FROM payment WHERE id = '$id'";
		$result = mysqli_query($conn2, $query);
		if ($result) {
			$row = mysqli_fetch_row($result);
			if ($row && isset($row[0])) {
				if ($row[0] <= 0) {
					$out = false;
					$message = 'No eligible payment record found for this payment number.';
				}
			} else {
				$out = false;
				$message = 'No eligible payment record found for this payment number.';
			}
		}
	}

	if ($out) {
		$query = "SELECT ct.id AS id, p.id AS payment_id, p.chque_no, p.chque_bank, p.chque_branch, p.chque_date, p.amount, c.name AS customer_name, p.payment_date, b.name, b.bank_code, ct.time, u.username,
		ct.status, up.username
		FROM cheque_trans ct
		JOIN payment p ON ct.payment_id = p.id
		LEFT JOIN bank b ON p.chque_bank = b.id
		LEFT JOIN cust c ON p.cust = c.id
		LEFT JOIN userprofile u ON ct.to = u.id
		LEFT JOIN userprofile up ON ct.from = up.id
		WHERE p.id=$id ORDER BY ct.id ASC";

		$result = mysqli_query($conn2, $query);
		while ($row = mysqli_fetch_array($result)) {
			$trans_id[] = $row[0];
			$payment_id[] = $row[1];
			$chq_no = $row[2];
			$chq_bnk = $row[3];
			$chq_branch = $row[4];
			$chq_date[] = $row[5];
			$payment_amount[] = $row[6];
			$cust_name[] = $row[7];
			$payment_date[] = $row[8];
			$bank_name[] = $row[9];
			$chq_bnk = $row[10];
			$trans_time[] = $row[11];
			$trans_to[] = $row[12];
			$status[] = $row[13];
			$trans_from[] = $row[14];

			if ($chq_bnk > 0) {
				$chq_full_no[] = $chq_no . '-' . $chq_bnk . '-' . $chq_branch;
			} else {
				$chq_full_no[] = '';
			}
		}
	}
}

// update by nirmal 30_01_2025 (added payment status to remove deleted payment show)
function getChequeOnHandReport($sub_system)
{
	include('config.php');
	global $payment_id, $invoice_no, $cust_name, $payment_amount, $bank_name, $comment, $payment_date, $chq_full_no, $chq_date, $trans_id, $trans_to, $trans_time, $status, $salesman;
	$payment_id = $invoice_no = $cust_name = $payment_amount = $bank_name = $comment = $payment_date = $chq_full_no = $chq_date = $trans_id = $trans_to = $trans_time = $status = $salesman = [];
	$user_id = $_COOKIE['user_id'];
	$date_qry = $user_qry = '';

	// Handle salesman filter
	if ((isset($_REQUEST['user'])) && ($_REQUEST['user'] != '')) {
		$py_user = $_REQUEST['user'];
		$user_qry = " AND ct.`to` = '$py_user'";
	}

	// Set date filters based on input parameters
	if (isset($_GET['datefrom']) && $_GET['datefrom'] != '') {
		$fromdate = $_GET['datefrom'];
	} else {
		// Set to one month back from the current date
		$fromdate = date('Y-m-d', strtotime('-1 month'));
	}

	if (isset($_GET['dateto']) && $_GET['dateto'] != '') {
		$todate = $_GET['dateto'];
	} else {
		$todate = dateNow(); // Set to today's date if no end date provided
	}

	// Create date filter query
	if (!empty($fromdate) && !empty($todate)) {
		$date_qry = " AND DATE(ct.time) BETWEEN '$fromdate' AND '$todate'";
	} elseif (!empty($fromdate)) {
		$date_qry = " AND DATE(ct.time) >= '$fromdate'";
	}

	$query = "SELECT ct.id AS id, p.id AS payment_id, p.chque_no, p.chque_bank, p.chque_branch, p.chque_date, p.amount, c.name AS customer_name, p.payment_date,
	b.name AS bank_name, b.bank_code, ct.time, u.username AS `to`, ct.status, up.username, upp.username
	FROM cheque_trans ct JOIN payment p ON ct.payment_id = p.id
	JOIN bank b ON p.chque_bank = b.id
	JOIN cust c ON p.cust = c.id
    JOIN userprofile u ON ct.to = u.id
    JOIN userprofile upp ON ct.from = upp.id
    JOIN userprofile up ON p.salesman = up.id
	WHERE p.`status` = 0 AND ct.`latest` = 1 AND ct.`status` IN(1,2,5) $user_qry $date_qry ORDER BY ct.id DESC";

	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$trans_id[] = $row[0];
		$payment_id[] = $row[1];
		$chq_no = $row[2];
		$chq_bnk = $row[3];
		$chq_branch = $row[4];
		$chq_date[] = $row[5];
		$payment_amount[] = $row[6];
		$cust_name[] = $row[7];
		$payment_date[] = $row[8];
		$bank_name[] = $row[9];
		$chq_bnk = $row[10];
		$trans_time[] = $row[11];
		if ($row[13] == 5) {
			$trans_to[] = $row[15];
		} else {
			$trans_to[] = $row[12];
		}
		$status[] = $row[13];
		$salesman[] = $row[14];

		if ($chq_bnk > 0) {
			$chq_full_no[] = $chq_no . '-' . $chq_bnk . '-' . $chq_branch;
		} else {
			$chq_full_no[] = '';
		}
	}
}

// update by nirmal 30_01_2025 (added payment status to remove deleted payment show)
// not send cheques
function getSalesmanChequeOnHandReport($sub_system)
{
	include('config.php');
	global $payment_id_1, $invoice_no_1, $cust_name_1, $payment_amount_1, $bank_name_1, $comment_1, $payment_date_1, $chq_full_no_1, $chq_date_1, $trans_id_1, $trans_to_1, $trans_time_1, $status_1, $salesman_1, $fromdate, $todate;
	$payment_id_1 = $invoice_no_1 = $cust_name_1 = $payment_amount_1 = $bank_name_1 = $comment_1 = $payment_date_1 = $chq_full_no_1 = $chq_date_1 = $trans_id_1 = $trans_to_1 = $trans_time_1 = $status_1 = $salesman_1 = [];
	$user_id_1 = $_COOKIE['user_id'];
	$date_qry_1 = $user_qry_1 = '';

	// Handle salesman filter
	if ((isset($_REQUEST['user'])) && ($_REQUEST['user'] != '')) {
		$py_user = $_REQUEST['user'];
		$user_qry_1 = " AND p.`salesman` = '$py_user'";
	}

	// Set date filters based on input parameters
	if (isset($_GET['datefrom']) && $_GET['datefrom'] != '') {
		$fromdate = $_GET['datefrom'];
	} else {
		// Set to one month back from the current date
		$fromdate = date('Y-m-d', strtotime('-1 month'));
	}

	if (isset($_GET['dateto']) && $_GET['dateto'] != '') {
		$todate = $_GET['dateto'];
	} else {
		$todate = dateNow(); // Set to today's date if no end date provided
	}

	// Create date filter query
	if (!empty($fromdate) && !empty($todate)) {
		$date_qry_1 = " AND DATE(p.payment_date) BETWEEN '$fromdate' AND '$todate'";
	} elseif (!empty($fromdate)) {
		$date_qry_1 = " AND DATE(p.payment_date) >= '$fromdate'";
	}

	$query = "SELECT p.id AS payment_id, p.chque_no, p.chque_bank, p.chque_branch, p.chque_date, p.amount, c.name AS customer_name, p.payment_date, b.name AS bank_name, b.bank_code,
	up.username
	FROM payment p LEFT JOIN cheque_trans ct ON p.id = ct.payment_id LEFT JOIN bank b ON p.chque_bank = b.id LEFT JOIN cust c ON p.cust = c.id LEFT JOIN userprofile up ON p.salesman = up.id
	WHERE p.`status` = 0 AND p.payment_type = 2 AND p.status = 0 AND p.chque_return != 1 AND ct.payment_id IS NULL $user_qry_1 $date_qry_1 ORDER BY p.id DESC";
	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$payment_id_1[] = $row[0];
		$chq_no = $row[1];
		$chq_bnk = $row[2];
		$chq_branch = $row[3];
		$chq_date_1[] = $row[4];
		$payment_amount_1[] = $row[5];
		$cust_name_1[] = $row[6];
		$payment_date_1[] = $row[7];
		$bank_name_1[] = $row[8];
		$chq_bnk = $row[9];
		$salesman_1[] = $row[10];
		if ($chq_bnk > 0) {
			$chq_full_no_1[] = $chq_no . '-' . $chq_bnk . '-' . $chq_branch;
		} else {
			$chq_full_no_1[] = '';
		}
	}
}

function getTransReturnMarkedCheques($sub_system)
{
	include('config.php');
	global $payment_id, $invoice_no, $cust_name, $payment_amount, $bank_name, $comment, $payment_date, $chq_full_no, $chq_date, $trans_id, $trans_to, $trans_time, $status,
	$trans_from, $salesman, $salesman_id;
	$payment_id = $invoice_no = $cust_name = $payment_amount = $bank_name = $comment = $payment_date = $chq_full_no = $chq_date = $trans_id = $trans_to = $trans_time =
		$status = $trans_from = $salesman = $salesman_id = [];
	$user_id = $_COOKIE['user_id'];

	$query = "SELECT ct.id AS id, p.id AS payment_id, p.chque_no, p.chque_bank, p.chque_branch, p.chque_date, p.amount, c.name AS customer_name, p.payment_date, b.name, b.bank_code, ct.time, u.username,
	ct.status, up.username AS `from`, upp.username AS `salesman`, p.salesman
	FROM cheque_trans ct
	JOIN payment p ON ct.payment_id = p.id
	LEFT JOIN bank b ON p.chque_bank = b.id
	LEFT JOIN cust c ON p.cust = c.id
	LEFT JOIN userprofile u ON ct.to = u.id
	LEFT JOIN userprofile up ON ct.from = up.id
	JOIN userprofile upp ON p.salesman = upp.id
	WHERE (ct.from = $user_id AND ct.status IN(7,8,9,13) AND ct.latest = 1)
	OR (ct.to = $user_id AND ct.status = 13 AND ct.latest = 1)
	ORDER BY ct.id ASC"; // 7=bank-return, 8=cash-receive, 9=issue-new-cheque, 13=return-reject

	$result = mysqli_query($conn2, $query);
	while ($row = mysqli_fetch_array($result)) {
		$trans_id[] = $row[0];
		$payment_id[] = $row[1];
		$chq_no = $row[2];
		$chq_bnk = $row[3];
		$chq_branch = $row[4];
		$chq_date[] = $row[5];
		$payment_amount[] = $row[6];
		$cust_name[] = $row[7];
		$payment_date[] = $row[8];
		$bank_name[] = $row[9];
		$chq_bnk = $row[10];
		$trans_time[] = $row[11];
		$status[] = $row[13];
		$trans_from[] = $row[14];
		$salesman[] = $row[15];
		$salesman_id[] = $row[16];

		if ($chq_bnk > 0) {
			$chq_full_no[] = $chq_no . '-' . $chq_bnk . '-' . $chq_branch;
		} else {
			$chq_full_no[] = '';
		}
	}
}

function addChequeTransferReturnToUserAjax($sub_system)
{
	include('config.php');
	$user_id = $_COOKIE['user_id'];
	$status = 'error';
	$message = $payment_id = $username = $lastid_temp = $modify_cheque = '';
	$time_now = timeNow();
	$out = true;

	$id = isset($_POST['id']) ? trim($_POST['id']) : ''; // trans id
	$user = isset($_POST['user']) ? trim($_POST['user']) : ''; // trans to

	if ($id == '') {
		$out = false;
		$message = 'Payment ID cannot be null.';
	}

	if (($out) && ($user == '')) {
		$out = false;
		$message = 'User cannot be null.';
	}

	if ($out) {
		// Validate if the selected user exists and has the required permission
		$query = "SELECT COUNT(up.id) AS user_count, up.`username` FROM userprofile up
		WHERE  up.`sub_system` = '$sub_system' AND up.`status` = '0' AND up.`id` = '$user' AND up.`id` != $user_id GROUP BY up.`username`";
		$result = mysqli_query($conn2, $query);
		if ($row = mysqli_fetch_assoc($result)) {
			$username = $row['username'];
			$user_count = $row['user_count'];
			if ($user_count > 0) {
				$message = "User found: $username.";
				$out = true;
			} else {
				$message = 'Error: User not found or lacks permission.';
				$out = false;
			}
		} else {
			$message = 'Error: Query failed or no results.';
			$out = false;
		}
	}

	if ($out) {
		// Check if record exists
		$query = "SELECT COUNT(id), payment_id, modify_cheque FROM cheque_trans WHERE `id` ='$id' AND `latest` = 1 AND `status` IN(7,8,9,10,13)"; //7=bank-return, 8=cash-receive, 9=issue-new-cheque, 10=modify-cheque, 13=return-reject
		$result = mysqli_query($conn2, $query);
		$row = mysqli_fetch_row($result);
		if ($row[0] <= 0) {
			$message = 'Error: could not find a record.';
			$out = false;
		} else {
			$payment_id = $row[1];
			$modify_cheque = $row[2];
		}
	}

	if ($out) {
		try {
			// Start transaction
			if (!mysqli_begin_transaction($conn)) {
				throw new Exception("Could not begin transaction: " . mysqli_error($conn));
			}
			// Insert new cheque transaction
			if ($modify_cheque == 1) {
				$query1 = "INSERT INTO cheque_trans (`from`, `to`, `payment_id`, `time`, `status`, `latest`,`modify_cheque`)
					VALUES ('$user_id', '$user', '$payment_id', '$time_now', 11, 1, 1)";
			} else {
				$query1 = "INSERT INTO cheque_trans (`from`, `to`, `payment_id`, `time`, `status`, `latest`)
					VALUES ('$user_id', '$user', '$payment_id', '$time_now', 11, 1)";
			}
			if (!($result1 = mysqli_query($conn, $query1))) {
				throw new Exception("Failed to insert cheque transaction: " . mysqli_error($conn));
			}

			$lastid_temp = mysqli_insert_id($conn);
			if (empty($lastid_temp)) {
				throw new Exception("Failed to get last inserted ID");
			}

			// Update previous transactions
			$query2 = "UPDATE cheque_trans SET `latest` = NULL WHERE `payment_id` = '$payment_id' AND `id` != '$lastid_temp'";
			if (!($result2 = mysqli_query($conn, $query2))) {
				throw new Exception("Failed to update previous cheque transactions: " . mysqli_error($conn));
			}

			// Commit transaction
			if (!mysqli_commit($conn)) {
				throw new Exception("Transaction commit failed: " . mysqli_error($conn));
			}

			$status = 'success';
			$message = 'Cheque return transfer added successfully';
		} catch (Exception $e) {
			// Rollback transaction in case of any error
			mysqli_rollback($conn);

			// Log the error (consider using a proper logging mechanism)
			error_log('Cheque Transaction Error: ' . $e->getMessage());
			$message = 'Transaction failed: ' . $e->getMessage();
		}
	}
	echo json_encode(['status' => $status, 'message' => $message]);
}
?>