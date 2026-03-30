<?php
                include_once  'template/header.php';
?>
<!-- ------------------Item List----------------------- -->
<br /><br />
<table align="center" cellspacing="0"><tr><td>

<?php
if(isset($_REQUEST['message'])){
	if($_REQUEST['re']=='success') $color0='green'; else $color0='red';
print '<span style="color:'.$color0.'; font-weight:bold;">'.$_REQUEST['message'].'</span>'; 
}
?>
</td></tr></table>
	<table align="center" bgcolor="#E5E5E5" border="1" cellspacing="0" style="font-family:Calibri">
	<tr><th width="120px">GTN No</th><?php if($cross_inv) print '<th width="120px">Related Invoice</th>'; ?><th width="100px">Date</th><th width="150px">Total<br>Wholesale Price</th><?php if(isset($_COOKIE['report'])) print '<th width="150px">Total Cost</th>'; ?><th width="100px">From Store</th><th width="100px">To Store</th><th width="100px">Status</th></tr>
<?php
$inv=0;
	for($i=0;$i<sizeof($gtn_no);$i++){
		$cross_invoice_no=$pick='';
		if($gtn_cross_invoice[$i]>0){ 
			$cross_invoice_no=str_pad($gtn_cross_invoice[$i], 7, "0", STR_PAD_LEFT); 
			if($gtn_status[$i]=='Cross Transfer')
			$pick='<input type="Button" value="Pick"  onclick="window.location = '."'".'index.php?components=trans&action=pick_gtn&id='.$gtn_no[$i]."'".'" />';
		}
		if($cross_inv) $cross_inv_data='<td align="center">'.$cross_invoice_no.'</td>'; else  $cross_inv_data='';
		if(isset($_COOKIE['report'])) $showcost='<td align="right" style="padding-right:20px">'.number_format($gtn_total_c_price[$i]).'</td>'; else $showcost=''; 
		if($salesman[$i]==$user){
			$color1='blue'; 
			if(($gtn_status[$i]=='Pending')||($gtn_status[$i]=='Transfering')) $edit='<input type="Button" value="Edit"  onclick="window.location = '."'".'index.php?components=trans&action=edit_gtn&id='.$gtn_no[$i].'&remotestore='.$gtn_to_storeid[$i]."'".'" />';
			else $edit='';
		}else{
			$color1='gray';
			$edit='';
		}
			print '<tr><td align="center"><a style="color:'.$color1.';" href="index.php?components=trans&action=print_gtn&id='.$gtn_no[$i].'&approve_permission='.$approve_permission[$i].'">'.str_pad($gtn_no[$i], 7, "0", STR_PAD_LEFT).'</a>&nbsp;&nbsp;&nbsp;'.$edit.$pick.'</td>'.$cross_inv_data.'<td width="50px" align="center"><a href="" title="Time : '.$time[$i].'">'.$date[$i].'</a></td><td align="right" style="padding-right:20px">'.number_format($gtn_total_w_price[$i]).'</td>'.$showcost.'<td align="center">'.$gtn_from_store[$i].'</td><td align="center">'.$gtn_to_store[$i].'</td><td align="center" style="color:'.$gtn_color[$i].';" ><span title="'.$gtn_remote_user[$i].'" >'.$gtn_status[$i].'</span></td></tr>';
	}
?>	
	</table>

<?php
                include_once  'template/footer.php';
?>