<?php
	include_once  'template/m_header.php';
	$currency=getCurrency(1);
?>
	<link rel="stylesheet" href="plugin/jquery/css/jquery.ui.all.css" />
	<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.core.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.widget.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.position.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.autocomplete.js"></script>

	<script type="text/javascript">
	$(function() {
		var availableTags2 = [<?php for ($x=0;$x<sizeof($description);$x++){ print '"'.$description[$x].'",'; } ?>	];
		$( "#item" ).autocomplete({
			source: availableTags2
		});
	});

	function getPrice(){
		var itemid = [<?php for ($x=0;$x<sizeof($id);$x++){ print '"'.$id[$x].'",'; } ?>	];
		var description = [<?php for ($x=0;$x<sizeof($description);$x++){ print '"'.$description[$x].'",'; } ?>	];
		var wprice = [<?php for ($x=0;$x<sizeof($w_price);$x++){ print '"'.$w_price[$x].'",'; } ?>	];
		var rprice = [<?php for ($x=0;$x<sizeof($r_price);$x++){ print '"'.$r_price[$x].'",'; } ?>	];
		var qty = [<?php for ($x=0;$x<sizeof($qty);$x++){ print '"'.$qty[$x].'",'; } ?>	];
		var drawer = [<?php for ($x=0;$x<sizeof($drawer);$x++){ print '"'.$drawer[$x].'",'; } ?>	];
		var ttitm = [<?php for ($x=0;$x<sizeof($tt_item);$x++){ print '"'.$tt_item[$x].'",'; } ?>	];
		var ttqty = [<?php for ($x=0;$x<sizeof($tt_qty);$x++){ print '"'.$tt_qty[$x].'",'; } ?>	];
		var pr_sr = [<?php for ($x=0;$x<sizeof($pr_sr);$x++){ print '"'.$pr_sr[$x].'",'; } ?>	];
		var itemdesc=document.getElementById('item').value;


		if(itemdesc!=''){
			var a=description.indexOf(itemdesc);
			document.getElementById('itemid').value=itemid[a];
			<?php if($_COOKIE['direct_mkt']==1){ ?>
				document.getElementById('priceshow').innerHTML='<select name="price" id="price"><option value="'+rprice[a]+'">Retail - '+rprice[a]+'</option></select>';
			<?php }else{ ?>
				<?php if($_COOKIE['retail']==0){ ?>
				document.getElementById('priceshow').innerHTML='<select name="price" id="price"><option value="'+wprice[a]+'">Wholesale - '+wprice[a]+'</option><option value="'+rprice[a]+'">Retail - '+rprice[a]+'</option></select>';
				<?php } ?>
				<?php if($_COOKIE['retail']==1){ ?>
				document.getElementById('priceshow').innerHTML='<input type="hidden" name="price" id="price" value="'+rprice[a]+'" /><select disabled="disabled"><option value="'+rprice[a]+'">Retail - '+rprice[a]+'</option><option value="'+wprice[a]+'">Wholesale - '+wprice[a]+'</option></select>';
	//			document.getElementById('price_div').innerHTML='<select name="price" ><option value="'+rprice[a]+'">Retail - '+rprice[a]+'</option><option value="'+wprice[a]+'">Wholesale - '+wprice[a]+'</option></select>';
				<?php }
			} ?>
			if(pr_sr[a]==2){
				document.getElementById('priceshow').innerHTML='<input type="text" name="price" value="'+wprice[a]+'" />';
				document.getElementById('com').innerHTML='Comment';
				document.getElementById('it_drawer').innerHTML='<textarea name="comment" ></textarea>';
			}else{
				document.getElementById('com').innerHTML='Drawer No<input type="hidden" name="comment" id="comment" value="" />';
				document.getElementById('it_drawer').innerHTML=drawer[a];
			}
			document.getElementById('av_qty').innerHTML=qty[a];
			document.getElementById('tt_qty').innerHTML='';
			var b=ttitm.indexOf(itemid[a]);
			if(b>-1){
				document.getElementById('av_qty').innerHTML='Old Stock - '+qty[a];
				document.getElementById('tt_qty').innerHTML='New Stock - '+ttqty[b];
			}
		}
	}

	function calSellPrice(){
		var qty=document.getElementById('qty').value;
		var uprice=document.getElementById('price').value;
		var discount=document.getElementById('discount').value;
		if(discount_type=='percentage') discount=((uprice/100)*(discount)).toFixed(<?php print decimal_paces(); ?>);
		document.getElementById('sell_price').value=uprice-discount;
		document.getElementById('total_price').value=(uprice-discount)*qty;
	}

	function changeDiscount(){
		var old_discount_type=document.getElementById('discount_type').value;
		if(old_discount_type=='price'){
			document.getElementById('discount_type').value='percentage';
			document.getElementById('discount_div').innerHTML='%';
		}else{
			document.getElementById('discount_type').value='price';
			document.getElementById('discount_div').innerHTML='<?php print $currency; ?>';
		}
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

<hr>
<div class="w3-row">
  <div class="w3-col s3">
  </div>
  <div class="w3-col">
  	<div id="loading" style="display:none"><img src="images/loading.gif" style="width:70px" /><br><span style="color:maroon; vertical-align:middle">Please Wait</span></div>
		<form action="index.php?components=order_process&action=apend_bill" onsubmit="return validateAppendCustOrder()" method="post" >
			<input type="hidden" name="id" value="<?php print $_GET['id']; ?>" />
			<input type="hidden" name="salesman" value="<?php print $bm_salesman; ?>" />
			<input type="hidden" name="type" id="type" value="1" />
			<input type="hidden" name="cust" value="<?php print $bm_cust; ?>" />
			<input type="hidden" name="unic_item" value="0" />
			<input type="hidden" name="cust_odr" value="yes" />
			<input type="hidden" name="itemid" id="itemid" />
			<input type="hidden" name="return" id="return" value="<?php print $_GET['return']; ?>" />
			<input type="hidden" name="discount_type" id="discount_type" value="<?php print $discount; ?>" />
			<table style="font-size:12pt">
				<tr>
					<td align="center" style="font-size:16pt; color:#4678BB">Edit Customer Order</td>
				</tr>
				<tr>
					<td height="20px"></td>
				</tr>
				<tr>
					<td align="center">
						<table>
							<tr>
								<th style="background-color:#C0C0C0; padding-left:10px" align="left">Item
									Description&nbsp;&nbsp;&nbsp;&nbsp;</th>
								<td style="background-color:#F0F0F0"><input type="text" name="item" id="item"
										value="<?php print $bi_desc; ?>" onclick="this.value=''" /></td>
								<td style="background-color:#F0F0F0"></td>
							</tr>
							<tr>
								<th style="background-color:#C0C0C0; padding-left:10px" align="left">Item Qty</th>
								<td style="background-color:#F0F0F0"><input type="text" name="qty" id="qty"
										value="<?php print $bi_qty; ?>" onfocus="getPrice()" /></td>
								<td style="background-color:#F0F0F0">
									<div style="font-size:10pt;" id="av_qty" align="right"></div>
									<div style="font-size:10pt; color:#CC0000" id="tt_qty" align="right"></div>
								</td>
							</tr>
							<tr>
								<th style="background-color:#C0C0C0; padding-left:10px" align="left">Unit Price</th>
								<td style="background-color:#F0F0F0">
									<div id="priceshow"><input type="text" name="price" id="price" disabled="disabled"
											value="<?php print ($bi_price+$bi_discount); ?>" /></div>
								</td>
								<td style="background-color:#F0F0F0"></td>
							</tr>
							<tr>
								<th style="background-color:#C0C0C0; padding-left:10px" align="left">
									<table border="0">
										<tr>
											<td>Unit Discount</td>
											<td>
												<div id="discount_div" onclick="changeDiscount()"
													style="color:blue; cursor:pointer;">
													<?php if($discount=='price') print $currency; else print '%'; ?></div>
											</td>
										</tr>
									</table>
								</th>
								<td style="background-color:#F0F0F0"><input type="text" name="discount" id="discount"
										value="<?php print $bi_discount; ?>" /></td>
								<td style="background-color:#F0F0F0"></td>
							</tr>
							<tr>
								<th style="background-color:#C0C0C0; padding-left:10px" align="left">Selling Price</th>
								<td style="background-color:#F0F0F0"><input type="text" id="sell_price" disabled="disabled"
										value="<?php print ($bi_qty*$bi_price); ?>" /></td>
								<td style="background-color:#F0F0F0"><input type="button" value="Calculate"
										onclick="calSellPrice()" /></td>
							</tr>
							<tr>
								<th style="background-color:#C0C0C0; padding-left:10px" align="left">Total</th>
								<td style="background-color:#F0F0F0"><input type="text" id="total_price" disabled="disabled"
										value="<?php print $bi_price; ?>" /></td>
								<td style="background-color:#F0F0F0"></td>
							</tr>
							<tr>
								<th style="background-color:#C0C0C0; padding-left:10px" align="left">
									<div id="com">Drawer No<input type="hidden" name="comment" id="comment" value="" /></div>
								</th>
								<td style="background-color:#F0F0F0">
									<div style="font-size:12pt" id="it_drawer"></div>
								</td>
								<td style="background-color:#F0F0F0"></td>
							</tr>
							<tr>
								<td style="padding-left:20px; padding-right:20px"></td>
								<td style="padding-right:20px" align="right"></td>
								<td style="padding-right:20px" align="right"></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td height="50px" align="center">
						<div id="div_submit"><input type="submit" value="Add to Order" style="width:120px; height:50px" /></div>
					</td>
				</tr>
			</table>
		</form>
  </div>
</div>
</div>
<hr>


<?php
                include_once  'template/m_footer.php';
?>