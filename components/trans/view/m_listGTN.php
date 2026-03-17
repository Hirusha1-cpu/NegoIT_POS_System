<?php
                include_once  'template/m_header.php';
?>
<!-- ------------------------------------------------------------------------------------ -->
<?php 
	if(isset($_REQUEST['id'])) $id=$_REQUEST['id']; else $id=0;
?>
	<style type="text/css">
	.style2 {
		color: navy;
		font-weight: bold;
		background-color:#EEEEEE;
	}
	</style>
</head>

<div class="w3-container" style="margin-top:75px">
<?php 
	if(isset($_REQUEST['message'])){
		if($_REQUEST['re']=='success') $color='green'; else $color='red';
	print '<span style="color:'.$color.'; font-weight:bold;font-size:large;">'.$_REQUEST['message'].'</span>'; 
	}
?>	

<hr>
<div class="w3-row">
  <div class="w3-col s3">
  </div>
  <div class="w3-col">
	<table align="center" bgcolor="#E5E5E5" border="1" cellspacing="0" style="font-family:Calibri">
	<tr><th width="100px">GTN No</th><th width="100px">Date</th><?php if(!isset($_COOKIE['report'])) print '<th width="100px">Time</th>'; ?><th width="100px">Total<br>Wholesale Price</th><?php if(isset($_COOKIE['report'])) print '<th width="100px">Total Cost</th>'; ?><th width="100px">From Store</th><th width="100px">To Store</th><th width="100px">Status</th></tr>
<?php
$inv=0;
	for($i=0;$i<sizeof($gtn_no);$i++){
		if(isset($_COOKIE['report'])){ $showcost='<td align="right" style="padding-right:20px">'.number_format($gtn_total_c_price[$i]).'</td>'; $showtime=''; }else{ $showcost=''; $showtime='<td align="right" style="padding-right:20px">'.$time[$i].'</td>'; }
		if($salesman[$i]==$user){
			$color1='blue'; 
			if(($gtn_status[$i]=='Pending')||($gtn_status[$i]=='Transfering')) $edit='<input type="Button" value="Edit"  onclick="window.location = '."'".'index.php?components=trans&action=edit_gtn&id='.$gtn_no[$i].'&remotestore='.$gtn_to_storeid[$i]."'".'" />';
			else $edit='';
		}else{
			$color1='gray';
			$edit='';
		}
			print '<tr><td align="center"><a style="color:'.$color1.';" href="index.php?components=trans&action=print_gtn&id='.$gtn_no[$i].'&approve_permission='.$approve_permission[$i].'">'.str_pad($gtn_no[$i], 7, "0", STR_PAD_LEFT).'</a>&nbsp;&nbsp;&nbsp;'.$edit.'</td><td width="50px" align="center">'.$date[$i].'</td>'.$showtime.'<td align="right" style="padding-right:20px">'.number_format($gtn_total_w_price[$i]).'</td>'.$showcost.'<td align="center">'.$gtn_from_store[$i].'</td><td align="center">'.$gtn_to_store[$i].'</td><td align="center" style="color:'.$gtn_color[$i].';" ><span title="'.$gtn_remote_user[$i].'" >'.$gtn_status[$i].'</span></td></tr>';
	}
?>	
	</table>
  </div>
</div>
</div>
<hr>


<?php
                include_once  'template/m_footer.php';
?>