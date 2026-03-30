<?php
                include_once  'template/m_header.php';
                $action=$_GET['action'];
                $username=$_COOKIE['user'];
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
<?php
	$td_status='#F0F0F0';
?>

<div class="w3-row">
  <div class="w3-col s3">
  </div>
  <div class="w3-col">
	<form action="#" method="post" >
		<table align="center" style="font-size:xx-small"><td width="15px" bgcolor="#FFC997"></td><td> - Picked</td></table>
		<table align="center" style="font-size:xx-small">
		<tr style="background-color:#C0C0C0"><th style="padding-left:5px; padding-right:5px">Invoice No</th><th  style="padding-left:5px; padding-right:5px">Customer</th><th  style="padding-left:5px; padding-right:5px">Billed By</th><th width="60px">Billed Date</th><th style="padding-left:5px; padding-right:5px">Technicien</th><th width="60px"><?php print $menu_date; ?></th></tr>
		<?php
		if($action=='list_rejected' || $action=='list_finished'){ 
			for($i=0;$i<sizeof($bi_invoice_no);$i++){
				print '<tr style="background-color:#F0F0F0"><td align="center"><a href="index.php?components=repair&action=list_one_done&id='.$bi_invoice_no[$i].'">'.str_pad($bi_invoice_no[$i], 7, "0", STR_PAD_LEFT).'</a></td><td style="padding-left:5px;">'.$bi_cust[$i].'</td><td style="padding-left:5px;">'.ucfirst($bi_billed_by[$i]).'</td><td align="center" ><a style="cursor:pointer; color:blue;" title="Time: '.substr($bi_billed_time[$i],0,5).'">'.$bi_billed_date[$i].'</a></td><td style="padding-left:5px;">'.ucfirst($bi_repaired_by[$i]).'</td><td align="center" ><a style="cursor:pointer; color:blue;" title="Time: '.substr($bi_repaired_time[$i],0,5).'">'.$bi_repaired_date[$i].'</a></td></tr>';
			}
		}else if($action=='list_my'){
			for($i=0;$i<sizeof($bi_invoice_no);$i++){
				if($username==$bi_picked_by[$i])
				print '<tr style="background-color:#F0F0F0"><td align="center"><a href="index.php?components=repair&action=list_one&id='.$bi_invoice_no[$i].'">'.str_pad($bi_invoice_no[$i], 7, "0", STR_PAD_LEFT).'</a></td><td style="padding-left:5px;">'.$bi_cust[$i].'</td><td style="padding-left:5px;">'.ucfirst($bi_billed_by[$i]).'</td><td align="center" ><a style="cursor:pointer; color:blue;" title="Time: '.substr($bi_billed_time[$i],0,5).'">'.$bi_billed_date[$i].'</a></td><td style="padding-left:5px;">'.ucfirst($bi_picked_by[$i]).'</td><td align="center" ><a style="cursor:pointer; color:blue;" title="Time: '.substr($bi_picked_time[$i],0,5).'">'.$bi_picked_date[$i].'</a></td></tr>';
			}
		}else{
			for($i=0;$i<sizeof($bi_invoice_no);$i++){
				if(($bi_status[$i]==1 || $bi_status[$i]==2)) $td_status='#F0F0F0'; else
				if(($bi_status[$i]==3)) $td_status='#FFC997';
				print '<tr style="background-color:'.$td_status.'"><td align="center"><a href="index.php?components=repair&action=list_one&id='.$bi_invoice_no[$i].'">'.str_pad($bi_invoice_no[$i], 7, "0", STR_PAD_LEFT).'</a></td><td style="padding-left:5px;">'.$bi_cust[$i].'</td><td style="padding-left:5px;">'.ucfirst($bi_billed_by[$i]).'</td><td align="center" ><a style="cursor:pointer; color:blue;" title="Time: '.substr($bi_billed_time[$i],0,5).'">'.$bi_billed_date[$i].'</a></td><td style="padding-left:5px;">'.ucfirst($bi_picked_by[$i]).'</td><td align="center" ><a style="cursor:pointer; color:blue;" title="Time: '.substr($bi_picked_time[$i],0,5).'">'.$bi_picked_date[$i].'</a></td></tr>';
			}
		}
		?>
		</table>
	</form>
  </div>
</div>
</div>
<hr>


<?php
                include_once  'template/m_footer.php';
?>