<?php
                include_once  'template/header.php';
                $action=$_GET['action'];
                $username=$_COOKIE['user'];
?>
	<link rel="stylesheet" href="plugin/jquery/css/jquery.ui.all.css" />
	<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.core.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.widget.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.position.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.autocomplete.js"></script>
	<link rel="stylesheet" href="plugin/jquery/css/demos.css" />

<?php 
	if(isset($_REQUEST['id'])) $id=$_REQUEST['id']; else $id=0;
?>

<table width="100%">
<tr><td align="center"><?php 
	if(isset($_REQUEST['message'])){
		if($_REQUEST['re']=='success') $color='green'; else $color='red';
	print '<span style="color:'.$color.'; font-weight:bold;font-size:12pt;">'.$_REQUEST['message'].'</span>'; 
	}
	?></td></tr>
</table>
<?php
if($action=='list_pending'){ 
	$menu_status='<th style="padding-left:20px; padding-right:20px">Status</th>'; 
	$filter_status='<th></th>';
}else{
	$menu_status=$td_status=$filter_status='';
}
?>
<form action="index.php?components=repair&action=<?php print $_GET['action'] ?>" method="post" >
	<input type="hidden" id="action" value="<?php print $_GET['action']; ?>" />
	<table align="center" style="font-size:12pt">
	<tr style="background-color:#C0C0C0"><th style="padding-left:20px; padding-right:20px">Invoice No</th><th  style="padding-left:20px; padding-right:20px">Customer</th><th  style="padding-left:20px; padding-right:20px">Billed By</th><th  style="padding-left:20px; padding-right:20px">Billed Date</th><th style="padding-left:20px; padding-right:20px">Technicien</th><th style="padding-left:20px; padding-right:20px"><?php print $menu_date; ?></th><?php print $menu_status; ?></tr>
	<tr style="background-color:#C0C0C0"><th style="padding-left:20px; padding-right:20px"><input type="text" name="filter_inv" placeholder="Invoice Filter" style="width:100px" value="<?php print $filter_inv; ?>" /></th><th style="padding-left:20px; padding-right:20px"><input type="text" name="filter_cust" placeholder="Customer Filter" value="<?php print $filter_cust; ?>" /></th><th  style="padding-left:20px; padding-right:20px"><input type="text" name="filter_sm" placeholder="Salesman Filter" style="width:100px" value="<?php print $filter_sm; ?>" /></th><th  style="padding-left:20px; padding-right:20px"></th><th style="padding-left:20px; padding-right:20px"><input type="text" name="filter_tec" placeholder="Technicien Filter" style="width:100px" value="<?php print $filter_tec; ?>" /></th><th style="padding-left:20px; padding-right:20px"></th><?php print $filter_status; ?></tr>
	<?php
	if($action=='list_rejected' || $action=='list_finished'){ 
		for($i=0;$i<sizeof($bi_invoice_no);$i++){
			print '<tr style="background-color:#F0F0F0"><td align="center"><a href="index.php?components=repair&action=list_one_done&id='.$bi_invoice_no[$i].'">'.str_pad($bi_invoice_no[$i], 7, "0", STR_PAD_LEFT).'</a></td><td style="padding-left:20px;">'.$bi_cust[$i].'</td><td style="padding-left:20px;">'.ucfirst($bi_billed_by[$i]).'</td><td align="center" ><a style="cursor:pointer; color:blue;" title="Time: '.substr($bi_billed_time[$i],0,5).'">'.$bi_billed_date[$i].'</a></td><td style="padding-left:20px;">'.ucfirst($bi_repaired_by[$i]).'</td><td align="center" ><a style="cursor:pointer; color:blue;" title="Time: '.substr($bi_repaired_time[$i],0,5).'">'.$bi_repaired_date[$i].'</a></td></tr>';
		}
	}else if($action=='list_my'){
		for($i=0;$i<sizeof($bi_invoice_no);$i++){
			if($username==$bi_picked_by[$i])
			print '<tr style="background-color:#F0F0F0"><td align="center"><a href="index.php?components=repair&action=list_one&id='.$bi_invoice_no[$i].'">'.str_pad($bi_invoice_no[$i], 7, "0", STR_PAD_LEFT).'</a></td><td style="padding-left:20px;">'.$bi_cust[$i].'</td><td style="padding-left:20px;">'.ucfirst($bi_billed_by[$i]).'</td><td align="center" ><a style="cursor:pointer; color:blue;" title="Time: '.substr($bi_billed_time[$i],0,5).'">'.$bi_billed_date[$i].'</a></td><td style="padding-left:20px;">'.ucfirst($bi_picked_by[$i]).'</td><td align="center" ><a style="cursor:pointer; color:blue;" title="Time: '.substr($bi_picked_time[$i],0,5).'">'.$bi_picked_date[$i].'</a></td></tr>';
		}
	}else{
		for($i=0;$i<sizeof($bi_invoice_no);$i++){
			if(($bi_status[$i]==1 || $bi_status[$i]==2)) $td_status='<td align="center" style="color:green">Pending</td>'; else
			if(($bi_status[$i]==3)) $td_status='<td align="center" style="color:orange">Picked</td>';
			print '<tr style="background-color:#F0F0F0"><td align="center"><a href="index.php?components=repair&action=list_one&id='.$bi_invoice_no[$i].'">'.str_pad($bi_invoice_no[$i], 7, "0", STR_PAD_LEFT).'</a></td><td style="padding-left:20px;">'.$bi_cust[$i].'</td><td style="padding-left:20px;">'.ucfirst($bi_billed_by[$i]).'</td><td align="center" ><a style="cursor:pointer; color:blue;" title="Time: '.substr($bi_billed_time[$i],0,5).'">'.$bi_billed_date[$i].'</a></td><td style="padding-left:20px;">'.ucfirst($bi_picked_by[$i]).'</td><td align="center" ><a style="cursor:pointer; color:blue;" title="Time: '.substr($bi_picked_time[$i],0,5).'">'.$bi_picked_date[$i].'</a></td>'.$td_status.'</tr>';
		}
	}
	?>
	</table>
	<input type="submit" style="visibility: hidden;" />
</form>

<?php
                include_once  'template/footer.php';
?>