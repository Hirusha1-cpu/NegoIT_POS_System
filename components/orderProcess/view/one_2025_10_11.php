<?php
    include_once  'template/header.php';
	$systemid=inf_systemid(1);
	$bill_module=bill_module(1);
?>
<link rel="stylesheet" href="plugin/jquery/css/demos.css" />
<script type="text/javascript">
	function orderProcess(){
		var btn_action="<?php print $button_action; ?>";
		if(btn_action=="set_shipped"){
			var check= confirm("Do want to Move this Order to Shipped?");
			if(check==true){
				document.getElementById('orderprocess').innerHTML=document.getElementById('loading').innerHTML;
				window.location = 'index.php?components=order_process&action='+btn_action+'&id=<?php print $_REQUEST['id']; ?>'
			}
		}else{
			document.getElementById('orderprocess').innerHTML=document.getElementById('loading').innerHTML;
			window.location = 'index.php?components=order_process&action='+btn_action+'&id=<?php print $_REQUEST['id']; ?>'
		}
	}
	function orderUnassign($type){
		var check= confirm("Do want to Unassign this Order from Current User ?");
		if(check==true){
			document.getElementById('orderprocess3').innerHTML=document.getElementById('loading').innerHTML;
			if($type==4 || $type==5) $action2='list_custodr'; else $action2='list_pending';
			window.location = 'index.php?components=order_process&action=set_unassign&next_action='+$action2+'&id=<?php print $_GET['id']; ?>';
		}
	}
	function moveCustOdr($id){
		var check= confirm("Do want to Move this Order to Cust Order ?");
		if(check==true){
			document.getElementById('moveodr').innerHTML=document.getElementById('loading').innerHTML;
			window.location = 'index.php?components=order_process&action=move_cust_odr&id='+$id;
		}
	}
</script>
<?php
	if(isset($_REQUEST['id'])) $id=$_REQUEST['id']; else $id=0;
?>

<table width="100%">
	<tr>
		<td align="center">
			<?php
				if(isset($_REQUEST['message'])){
					if($_REQUEST['re']=='success') $color='green'; else $color='red';
				print '<span style="color:'.$color.'; font-weight:bold;font-size:12pt;">'.$_REQUEST['message'].'</span>';
				}
			?>
		</td>
	</tr>
</table>

<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px" /></div>
<form action="#" method="post" >
	<table align="center" style="font-size:12pt" width="600px">
		<tr>
			<td style="background-color:#467898;color :white;"><strong><?php if($bm_status<3) print 'Order No'; else print 'Invoice No'; ?></strong></td>
			<td bgcolor="#EEEEEE"><?php print  str_pad($_REQUEST['id'], 7, "0", STR_PAD_LEFT); ?></td>
			<td></td>
			<td style="background-color:#467898;color :white;"><strong>Salesman</strong></td>
			<td bgcolor="#EEEEEE"><?php print  ucfirst($bi_salesman); ?></td>
			<td rowspan="2">
				<?php if($button!='' && $button!='Packed'){
					if($bm_status==1 && $bm_type==1){
						print '<div id="orderprocess"><input type="button" value="'.$button.'" style="height:50px; width:70px; background-color:#CC5100; font-weight:bold; color:white" onclick="orderProcess()" /></div>';
						print '<div id="moveodr"><button type="button" style="height:50px; width:70px; background-color:#336699; font-weight:bold; color:white" onclick="moveCustOdr('.$id.')">Move to<br />Cust Order</button></div>';
					}else{
						print '<div id="orderprocess"><input type="button" value="'.$button.'" style="height:100px; width:70px; background-color:#CC5100; font-weight:bold; color:white" onclick="orderProcess()" /></div>';
					}
					?>
				<?php } ?>
				<?php if($button=='Packed' && $bi_seen_by==$_COOKIE['user']){ ?>
					<div id="orderprocess">
						<input type="button" value="<?php print $button; ?>" style="height:100px; width:70px; background-color:#CC5100; font-weight:bold; color:white" onclick="orderProcess()" />
					</div>
				<?php } ?>
				<?php if($button!='Pick'){ ?>
					<td rowspan="2">
						<input type="button" value="Print" style="height:50px; width:70px; background-color:#007799; font-weight:bold; color:white" onclick="window.location = 'index.php?components=<?php print $bill_module; ?>&action=finish_bill&id=<?php print $_REQUEST['id']; ?>'" /><br />
						<input type="button" value="Print DN" title="Print Delivery Note" style="height:50px; width:70px; background-color:#007799; font-weight:bold; color:white" onclick="window.location = 'index.php?components=<?php print $bill_module; ?>&action=finish_dn&id=<?php print $_REQUEST['id']; ?>'" />
				<?php } ?>
				<?php if((isset($_COOKIE['report']) || isset($_COOKIE['manager']))&& $button=='Packed'){
					print '<div id="orderprocess3"><input type="button" value="Unassign" style="height:50px; width:70px; background-color:orange; font-weight:bold; color:white" onclick="orderUnassign('.$bm_type.')" /></div>';
		  		}?>
					</td>
			</td>
		</tr>
		<tr>
			<td style="background-color:#467898;color :white;"><strong>Order Date</strong></td>
			<td bgcolor="#EEEEEE"><?php print  substr($odr_date,0,16); ?></td>
			<td></td>
			<td style="background-color:#467898;color :white;"><strong>Customer</strong></td>
			<td bgcolor="#EEEEEE"><?php print '<a href="index.php?components='.$bill_module.'&action=cust_details&id='.$cu_id.'&action2=list_one_custodr&id2='.$_REQUEST['id'].'" >'.ucfirst($bi_cust).'</a>'; ?></td>
		</tr>
		<tr><td colspan="5" height="50px"></td><td></td></tr>
		<tr>
			<td colspan="5">
				<table>
					<tr style="background-color:#C0C0C0">
						<th>Item Description</th>
						<th style="padding-left:20px; padding-right:20px">Item Qty</th>
						<th style="padding-left:20px; padding-right:20px">Drawer No</th>
					</tr>
					<tr>
						<td style="padding-left:20px; padding-right:20px"></td><td style="padding-right:20px" align="right"></td><td style="padding-right:20px" align="right"></td>
					</tr>
					<?php
						for($i=0;$i<sizeof($odr_bill_id);$i++){
							print '<tr style="background-color:#F0F0F0"><td style="padding-left:20px; padding-right:20px">'.$odr_bi_desc[$i].'</td><td style="padding-right:20px" align="right">'.$odr_bi_qty[$i].'</td><td style="padding-right:20px" align="right">'.$odr_bi_drawer[$i].'</td></tr>';
						}
					?>
				</table>
			</td>
			<td></td>
		</tr>
		<tr><td colspan="5" height="50px"></td><td></td></tr>
		<tr>
			<td colspan="5">
				<table width="100%">
					<tr style="background-color:#C0C0C0">
						<td style="padding-left:20px;">Picked By</td>
						<td style="padding-left:20px;"><?php if($systemid == 13) echo 'Started'; else print 'Packed';?> By</td>
						<?php if($systemid != 13) { ?><td style="padding-left:20px;">Shipped By</td><?php } ?>
						<td style="padding-left:20px;"><?php if($systemid == 13) echo 'Finished'; else print 'Delivered';?> By</td>
					</tr>
					<tr style="background-color:#F0F0F0">
						<td style="padding-left:20px;"><?php print  ucfirst($bi_seen_by); ?></td>
						<td style="padding-left:20px;"><?php print  ucfirst($bi_packed_by); ?></td>
						<?php if($systemid != 13) { ?><td style="padding-left:20px;"><?php print  ucfirst($bi_shipped_by); ?></td><?php } ?>
						<td style="padding-left:20px;"><?php print  ucfirst($bi_deliverd_by); ?></td>
					</tr>
					<tr style="background-color:#F0F0F0">
					<td style="padding-left:20px;"><?php print  $bi_seen_date.'<br/>'.$bi_seen_time; ?></td>
						<td style="padding-left:20px;"><?php print  $bi_packed_date.'<br/>'.$bi_packed_time; ?></td>
						<?php if($systemid != 13) { ?><td style="padding-left:20px;"><?php print  $bi_shipped_date.'<br/>'.$bi_shipped_time; ?></td><?php } ?>
						<td style="padding-left:20px;"><?php print  $bi_deliverd_date.'<br/>'.$bi_deliverd_time; ?></td>
					</tr>
				</table>
			</td>
			<td></td>
		</tr>
	</table>
</form>

<?php
    include_once  'template/footer.php';
?>