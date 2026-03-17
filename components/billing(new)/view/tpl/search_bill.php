<?php
include_once  '../../modle/billingModule.php';
$sub_system=$_COOKIE['sub_system'];

if(isset($_GET['searchcust'])){
	searchBillCust($_GET['searchcust'],$sub_system);
	print '<table style="font-family:Calibri; font-size:10pt">';
	for($i=0;$i<sizeof($cust_id);$i++){
		if(($i%2)==0) $color='#EEEEEE'; else $color='#F9F9F9';
		print '<tr bgcolor="'.$color.'"><td><img src="../../../../images/bullet_blue.gif" /></td><td><a style="text-decoration:none" href="search_bill.php?list_bill='.$cust_id[$i].'" >'.$cust_name[$i].'</a></td></tr>';
	}
	print '</table>';
	print '<p style="color:silver; font-family:Calibri; font-size:10pt;"">Result is limited to 50 records</p>';
}

if(isset($_GET['searchmob'])){
	searchBillMob($_GET['searchmob'],$sub_system);
	print '<table style="font-family:Calibri; font-size:10pt">';
	for($i=0;$i<sizeof($cust_id);$i++){
		if(($i%2)==0) $color='#EEEEEE'; else $color='#F9F9F9';
		print '<tr bgcolor="'.$color.'"><td><img src="../../../../images/bullet_blue.gif" /></td><td><a style="text-decoration:none" href="search_bill.php?list_bill='.$cust_id[$i].'" >'.$cust_name[$i].'</a></td><td><a style="text-decoration:none" href="search_bill.php?list_bill='.$cust_id[$i].'" >'.$cust_mobile[$i].'</a></td></tr>';
	}
	print '</table>';
	print '<p style="color:silver; font-family:Calibri; font-size:10pt;"">Result is limited to 50 records</p>';
}

if(isset($_GET['list_bill'])){
	searchListBill($_GET['list_bill']);
	print '<table style="font-family:Calibri; font-size:10pt">';
	for($i=0;$i<sizeof($cubill_id);$i++){
		if(($i%2)==0) $color='#EEEEEE'; else $color='#F9F9F9';
		print '<tr bgcolor="'.$color.'"><td><img src="../../../../images/bullet_blue.gif" /></td><td><a style="text-decoration:none" target="_parent" href="../../../../index.php?components=billing&action=finish_bill&id='.$cubill_id[$i].'" >'.str_pad($cubill_id[$i], 7, "0", STR_PAD_LEFT).'</a></td><td width="40px"></td><td>'.$cubill_date[$i].'</td></tr>';
	}
	print '</table>';
}

if(isset($_GET['searchunic'])){
	searchUnicBill($_GET['searchunic']);
	print '<table style="font-family:Calibri; font-size:10pt">';
	for($i=0;$i<sizeof($bill_no);$i++){
		if(($i%2)==0) $color='#EEEEEE'; else $color='#F9F9F9';
		print '<tr bgcolor="'.$color.'"><td><img src="../../../../images/bullet_blue.gif" /></td><td><a style="text-decoration:none" target="_parent" href="../../../../index.php?components=billing&action=finish_bill&id='.$bill_no[$i].'" >'.str_pad($bill_no[$i], 7, "0", STR_PAD_LEFT).'</a></td><td width="40px"></td><td bgcolor="'.$bill_color[$i].'" style="padding-left:5px; padding-right:5px; color:white; font-weight:bold;">'.$bill_type[$i].'</td></tr>';
	}
	for($i=0;$i<sizeof($wa_no);$i++){
		if(($i%2)==0) $color='#EEEEEE'; else $color='#F9F9F9';
		print '<tr bgcolor="'.$color.'"><td><img src="../../../../images/bullet_red.gif" /></td><td><a style="text-decoration:none" target="_parent" href="../../../../index.php?components=billing&action=warranty_show&id='.$wa_no[$i].'" >'.str_pad($wa_no[$i], 7, "0", STR_PAD_LEFT).'</a></td><td width="40px"></td><td bgcolor="#0055FF" style="padding-left:5px; padding-right:5px; color:'.$wa_st_color[$i].'; font-weight:bold;">'.$wa_st_name[$i].'</td></tr>';
	}
	print '</table>';
	print '<p style="color:silver; font-family:Calibri; font-size:10pt;"">Result is limited to 15 records</p>';
}

?> 