<?php
	include_once  'template/m_header.php';
	$custname_0=$bi_cust;
	generateInvoice('itm.description');
	generalPrint();
	generateReturnList();
	$bill_module=bill_module(1);
	$fqdn=$_SERVER['SERVER_NAME'];
	if($fqdn==$inf_url_primary){
		$url=$inf_url_primary;
	}else{
		$url=$inf_url_backup;
	}
	if(($tm_template==3)||($tm_template==4)) $execute='pos_bill2.php'; else $execute='pos_bill1.php';
	$systemid=inf_systemid(1);
?>
<script type="text/javascript">
	function orderProcess(){
		var btn_action="<?php print $button_action; ?>";
		if(btn_action=="set_shipped"){
			var check= confirm("Do want to Move this Order to Shipped?");
			<?php if($systemid == 13){ ?>
				var check= confirm("Do want to Move this Order to Finished?");
			<?php } ?>
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

	function showCustOrderList(){
		window.location = 'index.php?components=order_process&action=list_custodr';
	}
	function sendToQuickPrinterChrome(){
		var commandsToPrint =document.getElementById('print').innerHTML;
		var textEncoded = encodeURI(commandsToPrint);
		window.location.href="intent://"+textEncoded+"#Intent;scheme=quickprinter;package=pe.diegoveloper.printerserverapp;end;";
	}

	function sendToQuickPrinterChrome($case){
		var commandsToPrint =document.getElementById($case).innerHTML;
		var textEncoded = encodeURI(commandsToPrint);
		window.location.href="intent://"+textEncoded+"#Intent;scheme=quickprinter;package=pe.diegoveloper.printerserverapp;end;";
	}
</script>

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
		if($button!='Pick'){
			if($bm_status<3) include_once  'components/orderProcess/view/tpl/pos_odr.php';
			else include_once  'components/'.$bill_module.'/view/tpl/'.$execute;
		}
	?>
	<?php
		include_once  'components/orderProcess/view/tpl/pos_address.php';
	?>

<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px" /></div>
<hr>
<div class="w3-row">
  <div class="w3-col s3">
  </div>
  <div class="w3-col">
	<table align="center" style="font-size:12pt" width="100%">
		<tr>
			<td style="background-color:#467898;color :white;"><strong><?php if($bm_status<3) print 'Order No'; else print 'Invoice No'; ?></strong></td>
			<td bgcolor="#EEEEEE"><?php print  str_pad($_REQUEST['id'], 7, "0", STR_PAD_LEFT); ?></td>
			<td></td>
			<td style="background-color:#467898;color :white;"><strong>Salesman</strong></td>
			<td bgcolor="#EEEEEE"><?php print  ucfirst($bi_salesman); ?></td>
		</tr>
		<tr>
			<td style="background-color:#467898;color :white;"><strong>Order Date</strong></td>
			<td bgcolor="#EEEEEE"><?php print  substr($odr_date,0,16); ?></td>
			<td></td>
			<td style="background-color:#467898;color :white;"><strong>Customer</strong></td>
			<td bgcolor="#EEEEEE"><?php print '<a href="index.php?components='.$bill_module.'&action=cust_details&id='.$cu_id.'&action2=list_one_custodr&id2='.$_REQUEST['id'].'" >'.ucfirst($custname_0).'</a>'; ?></td>
		</tr>
		<tr>
			<td colspan="5" height="50px" align="center">
				<table align="center" style="font-size:10pt">
					<tr>
						<td>
							<?php if($button!='' && $button!='Packed'){ ?>
								<div id="orderprocess">
									<input type="button" value="<?php print $button; ?>" style="height:50px; width:80px; background-color:#CC5100; font-weight:bold; color:white" onclick="orderProcess()" />
								</div>
							<?php } ?>
							<?php if($bm_status==1 && $bm_type==1){
								print '</td>
								<td>
									<div id="moveodr">
										<button type="button" style="height:50px; width:70px; background-color:#336699; font-weight:bold; color:white" onclick="moveCustOdr('.$id.')">Move to<br />Cust Order</button>
									</div>';
							} ?>
							<?php if($button=='Packed' && $bi_seen_by==$_COOKIE['user']){ ?>
								<div id="orderprocess">
									<input type="button" value="<?php print $button; ?>" style="height:50px; width:80px; background-color:#CC5100; font-weight:bold; color:white" onclick="orderProcess()" />
								</div>
							<?php } ?>
						</td>
						<td width="20px"></td>
						<td>
							<?php if($button!='Pick'){
								if($bm_status<3){
									print '<input type="button" value="Print" style="height:50px; width:80px; background-color:#007799; font-weight:bold; color:white" onclick="sendToQuickPrinterChrome(\'print_odr\')" />';
									//	 print '<input type="button" value="Print" style="height:50px; width:80px; background-color:#007799; font-weight:bold; color:white" onclick="parent.location='."'printscheme://".$url."/pos_odr.php?id=".$_REQUEST['id']."&user=".$_COOKIE['user']."'".'" />';
								}else{
									print '<input type="button" value="Print" style="height:50px; width:80px; background-color:#007799; font-weight:bold; color:white" onclick="sendToQuickPrinterChrome(\'print\')" />';
									//	 print '<input type="button" value="Print" style="height:50px; width:80px; background-color:#007799; font-weight:bold; color:white" onclick="parent.location='."'printscheme://".$url."/".$execute."?id=".$_REQUEST['id']."'".'" />';
								}
								}
								print '&nbsp;<input type="button" value="Print Tag" style="height:50px; width:90px; background-color:#007777; font-weight:bold; color:white" onclick="sendToQuickPrinterChrome(\'print_address\')" />';
									//	print '&nbsp;<input type="button" value="Print Tag" style="height:50px; width:90px; background-color:#007777; font-weight:bold; color:white" onclick="parent.location='."'printscheme://".$url."/pos_address.php?id=".$_REQUEST['id']."'".'" />';
								?>
						</td>
						<td width="20px"></td>
						<td>
							<?php if((isset($_COOKIE['report']) || isset($_COOKIE['manager']))&& $button=='Packed'){
										print '<div id="orderprocess3"><input type="button" value="Unassign" style="height:50px; width:80px; background-color:orange; font-weight:bold; color:white" onclick="orderUnassign('.$bm_type.')" /></div>';
								}
							?>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="5">
				<table>
					<tr style="background-color:#C0C0C0">
						<th>Item Description</th>
						<th style="padding-left:20px; padding-right:20px">Item Qty</th>
						<th style="padding-left:20px; padding-right:20px">Drawer No</th>
					</tr>
					<tr>
						<td style="padding-left:20px; padding-right:20px"></td>
						<td style="padding-right:20px" align="right"></td>
						<td style="padding-right:20px" align="right"></td>
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
		<tr>
			<td colspan="5" height="50px"></td>
			<td></td>
		</tr>
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
  </div>
</div>
</div>
<hr>

<?php
	if(isset($_GET['link'])){ ?>
		<script type="text/javascript">
			document.getElementById('orderprocess').innerHTML='<input type="button" value="Show List" style="height:50px; width:90px; background-color:#AA5100; font-weight:bold; color:white" onclick="showCustOrderList()" />';
		</script>
<?php }
    include_once  'template/m_footer.php';
?>