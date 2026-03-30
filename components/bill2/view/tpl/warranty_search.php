<?php
include_once  '../../modle/bill2Module.php';

if(isset($_GET['searchinv'])){
    searchWarranty('inv',$_GET['searchinv']);
    print '<table style="font-family:Calibri; font-size:10pt">';
	for($i=0;$i<sizeof($inv_id);$i++){
		if(($i%2)==0) $color='#EEEEEE'; else $color='#F9F9F9';
		print '<tr bgcolor="'.$color.'">
					<td><img src="../../../../images/bullet_blue.gif" /></td>
					<td>
						<a style="text-decoration:none" href="warranty_search.php?list_bill='.$inv_id[$i].'">'.str_pad($inv_id[$i],7, "0", STR_PAD_LEFT).'</a>
					</td>
					<td width="5px"></td>
					<td>
						<a style="text-decoration:none" target="_parent" href="../../../../index.php?components=bill2&action=warranty_show&id='.$warranty_id[$i].'">'.$cust_name[$i].'</a>
					</td>
				</tr>';
	}
	print '</table>';
	print '<p style="color:silver; font-family:Calibri; font-size:10pt;"">Result is limited to 50 records</p>';
}

if(isset($_GET['searchname'])){
	searchWarranty('name',$_GET['searchname']);
	print '<table style="font-family:Calibri; font-size:10pt">';
	for($i=0;$i<sizeof($warranty_id);$i++){
		if(($i%2)==0) $color='#EEEEEE'; else $color='#F9F9F9';
		print '<tr bgcolor="'.$color.'">
					<td><img src="../../../../images/bullet_blue.gif" /></td>
					<td>
						<a style="text-decoration:none" target="_parent" href="../../../../index.php?components=bill2&action=warranty_show&id='.$warranty_id[$i].'" >'.$cust_name[$i].'</a>
					</td>
					<td width="5px"></td>
					<td>
						<a style="text-decoration:none" target="_parent" href="../../../../index.php?components=bill2&action=warranty_show&id='.$warranty_id[$i].'">'.str_pad($warranty_id[$i], 7, "0", STR_PAD_LEFT).'</a>
					</td>
				</tr>';
	}
	print '</table>';
	print '<p style="color:silver; font-family:Calibri; font-size:10pt;"">Result is limited to 50 records</p>';
}

if(isset($_GET['searchmob'])){
	searchWarranty('mob',$_GET['searchmob']);
	print '<table style="font-family:Calibri; font-size:10pt">';
	for($i=0;$i<sizeof($warranty_id);$i++){
		if(($i%2)==0) $color='#EEEEEE'; else $color='#F9F9F9';
		print '<tr bgcolor="'.$color.'">
				<td><img src="../../../../images/bullet_blue.gif" /></td>
				<td>
					<a style="text-decoration:none" target="_parent" href="../../../../index.php?components=bill2&action=warranty_show&id='.$warranty_id[$i].'">'.$cust_name[$i].'</a>
				</td>
				<td width="5px"></td>
				<td>
					<a style="text-decoration:none" target="_parent" href="../../../../index.php?components=bill2&action=warranty_show&id='.$warranty_id[$i].'">'.$cust_mobile[$i].'</a>
				</td>
				<td width="5px"></td>
				<td>
					<a style="text-decoration:none" target="_parent" href="../../../../index.php?components=bill2&action=warranty_show&id='.$warranty_id[$i].'" >'.str_pad($warranty_id[$i], 7, "0", STR_PAD_LEFT).'</a>
				</td>
			</tr>';
	}
	print '</table>';
	print '<p style="color:silver; font-family:Calibri; font-size:10pt;"">Result is limited to 50 records</p>';
}

if(isset($_GET['searchemei'])){
	searchWarranty('emei',$_GET['searchemei']);
	print '<table style="font-family:Calibri; font-size:10pt">';
	for($i=0;$i<sizeof($warranty_id);$i++){
		if(($i%2)==0) $color='#EEEEEE'; else $color='#F9F9F9';
		print '<tr bgcolor="'.$color.'">
					<td><img src="../../../../images/bullet_blue.gif" /></td>
					<td>
						<a style="text-decoration:none" href="../../../../index.php?components=bill2&action=warranty_show&id='.$warranty_id[$i].'" target="_parent">'.$sn[$i].'</a>
					</td>
					<td width="5px"></td>
					<td>
						<a style="text-decoration:none" href="../../../../index.php?components=bill2&action=warranty_show&id='.$warranty_id[$i].'" target="_parent">'.str_pad($warranty_id[$i], 7, "0", STR_PAD_LEFT).'</a>
					</td>
				</tr>';
	}
	print '</table>';
	print '<p style="color:silver; font-family:Calibri; font-size:10pt;"">Result is limited to 50 records</p>';
}

if(isset($_GET['list_bill'])){
	searchWarrantyListBill($_GET['list_bill']);
	print '<table style="font-family:Calibri; font-size:10pt">';
	for($i=0;$i<sizeof($warranty_id);$i++){
		if(($i%2)==0) $color='#EEEEEE'; else $color='#F9F9F9';
		print '<tr bgcolor="'.$color.'">
					<td><img src="../../../../images/bullet_blue.gif" /></td>
					<td>
						<a style="text-decoration:none" target="_parent" href="../../../../index.php?components=bill2&action=warranty_show&id='.$warranty_id[$i].'" >'.str_pad($warranty_id[$i], 7, "0", STR_PAD_LEFT).'</a>
					</td>
					<td width="40px"></td>
					<td>'.$warranty_date[$i].'</td>
				</tr>';
	}
	print '</table>';
	print '<p style="color:silver; font-family:Calibri; font-size:10pt;"">Result is limited to 50 records</p>';
}

?>