<?php
	include_once  'template/header.php';
	$retail_shop=$_COOKIE['retail'];
	$sub_system=$_COOKIE['sub_system'];
?>
<link rel="stylesheet" href="plugin/jquery/css/jquery.ui.all.css" />
<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
<script src="plugin/jquery/ui/jquery.ui.core.js"></script>
<script src="plugin/jquery/ui/jquery.ui.widget.js"></script>
<script src="plugin/jquery/ui/jquery.ui.position.js"></script>
<script src="plugin/jquery/ui/jquery.ui.autocomplete.js"></script>
<link rel="stylesheet" href="plugin/jquery/css/demos.css" />
<?php if($current_district!=''){ ?>
	<script type="text/javascript">
		$(function() {
			var availableTags1 = [<?php for ($x=0;$x<sizeof($code_uni);$x++){ print '"'.$code_uni[$x].'",'; } ?>	];
			$( "#tags1" ).autocomplete({
				source: availableTags1
			});
			var availableTags2 = [<?php for ($x=0;$x<sizeof($description_uni);$x++){ print '"'.$description_uni[$x].'",'; } ?>	];
			$( "#tags2" ).autocomplete({
				source: availableTags2
			});
		});

		function getPrice(){
			var code = [<?php for ($x=0;$x<sizeof($code_uni);$x++){ print '"'.$code_uni[$x].'",'; } ?>	];
			var description = [<?php for ($x=0;$x<sizeof($description_uni);$x++){ print '"'.$description_uni[$x].'",'; } ?>	];
			var itemcode=document.getElementById('tags1').value;
			var itemdesc=document.getElementById('tags2').value;

			if(itemcode!=''){
				var a=code.indexOf(itemcode);
				document.getElementById('tags2').value=description[a];
				document.getElementById('txt_result').value=description[a];
			}else if(itemdesc!=''){
				var a=description.indexOf(itemdesc);
				document.getElementById('tags1').value=code[a];
				document.getElementById('txt_result').value=itemdesc;
			}
		}

		function refresh($textbox){
			var category=document.getElementById('category').value;
			if(category!='off') window.location = 'index.php?components=availability&action=home&category=all'
			document.getElementById($textbox).value='';
		}

		function clearBox(){
			document.getElementById('tags1').value='';
			document.getElementById('tags2').value='';
		}
	</script>
<?php } ?>

<form action="index.php?components=availability&action=home&category=all" method="post" >
	<input type="hidden" id="cust_odr" value="" />
	<input type="hidden" id="salesman" value="" />
	<table align="center">
		<tr>
			<td>
				<table width="100%">
					<tr>
						<td>
							<h1 style="color:orange">Check Item Availability</h1>
						</td>
						<td align="right">
							<select name="district" id="district" onchange="setDistrict('availability')">
								<option>-SELECT-</option>
								<?php for($i=0;$i<sizeof($district_id);$i++){
									if($current_district==$district_id[$i]){
										$select='selected="selected"';
										$style='style="color:red; font-weight:bold;"';
									}else{
										$select='';
										$style='';
									}
									print '<option '.$select.' '.$style.'  value="'.$district_id[$i].'">'.$district_name[$i].'</option>';
								} ?>
							</select>
						</td>
					</tr>
				</table>
				<?php if($current_district!=''){ ?>
					<table align="center" bgcolor="#E5E5E5">
						<tr>
							<td colspan="5">
								<?php
									if(isset($_REQUEST['message'])){
										if($_REQUEST['re']=='success') $color='green'; else $color='red';
									print '<span style="color:'.$color.'; font-weight:bold;font-size:12pt;">'.$_REQUEST['message'].'</span>';
									}
								?>
								<br />
							</td>
						</tr>
						<tr>
							<td width="50px"></td>
							<td style="font-size:12pt">Item Code</td>
							<td colspan="2"><input type="text" name="code" id="tags1" value="<?php print $search_code; ?>"
									onfocus="refresh('tags1')" /></td>
							<td width="50px"></td>
						</tr>
						<tr>
							<td width="50px"></td>
							<td style="font-size:12pt">Item Description</td>
							<td colspan="2"><input type="text" name="description" id="tags2" value="<?php print $search_description; ?>"
									onfocus="refresh('tags2')" onclick="clearBox()" /></td>
							<td width="50px"></td>
						</tr>
						<tr>
							<td width="50px"></td>
							<td style="font-size:12pt" colspan="3"><input type="text" readonly="readonly" id="txt_result"
									style="background-color:#EEEEEE; border-color:#CCCCCC; text-align:center; width:100%"
									value="<?php print $search_description; ?>" /></td>
							<td width="50px"></td>
						</tr>
						<tr>
							<td colspan="5" align="center"><input type="submit" onfocus="getPrice()" value="Check Availability"
									style="width:130px; height:40px; font-weight:bold" /><br /><br /></td>
						</tr>
						<tr>
							<td colspan="5" style="font-size:12pt">
								<table border="1" cellspacing="0" width="90%" align="center" >
									<tr>
										<th>DR</th>
										<th>Store</th>
										<th>W/Price</th><?php if($retail_shop==1) print '<th>R/Price</th>'; ?><th>Qty</th>
									</tr>
									<?php
										for($i=0;$i<sizeof($store_name);$i++){ if($item_qty[$i]=='Available' || $item_qty[$i]=='-' ) $qty_print=$item_qty[$i];
											else if($sub_system!=0){ if($item_qty[$i]>100) $qty_print='100+'; else $qty_print=$item_qty[$i]; }else{
											$qty_print=number_format($item_qty[$i]); }
											print '<tr>
												<td align="center">'.$item_dr[$i].'</td>
												<td style="padding-left:10px">'.$store_name[$i].'</td>
												<td style="text-align:right; padding-right:10px">'.number_format($item_w_price[$i],$decimal).'</td>';
												if($retail_shop==1) print '<td style="text-align:right; padding-right:10px">
													'.number_format($item_r_price[$i],$decimal).'</td>';
												print '<td style="text-align:right; padding-right:10px">'.$qty_print.'</td>
											</tr>';
											}
											$qty_print='';
									?>
								</table>
							</td>
						</tr>
						<tr>
							<td colspan="5">
								<hr />
							</td>
						</tr>
						<tr>
							<td width="50px"></td>
							<td style="font-size:12pt">List By Category</td>
							<td colspan="2">
								<select id="category"
									onchange="window.location = 'index.php?components=availability&action=home&category='+document.getElementById('category').value">
									<option value="off">--SELECT--</option>
									<?php
									for($i=0;$i<sizeof($category_id);$i++){
										// Add a condition to skip category_id = 17 and systemid = 13
										if ($category_id[$i] == 17 && $systemid == 13 && $sub_system == 1) {
											continue; // Skip this iteration
										}
										if($category_id[$i]==$_GET['category']) $select='selected="selected"'; else $select='';
										print '<option value="'.$category_id[$i].'" '.$select.'>'.$category_name[$i].'</option>';
									}
								?>
								</select>
							</td>
							<td width="50px"></td>
						</tr>
						<tr>
							<td colspan="5"><br /><br /></td>
						</tr>
					</table>
					<br />
					<?php
						if(isset($_GET['category'])){
							if($_GET['category']!=='off'){
								if(($systemid!=1)&&($systemid!=4)){ ?>
									<table align="center" bgcolor="gray">
										<tr bgcolor="#CCCCCC">
											<th>Item Code</th>
											<th>Description</th>
											<th>Wholesale Price</th>
											<th>Retail Price</th>
											<th>Quantity</th>
											<th>Location</th>
										</tr>
										<?php
										for($i=0;$i<sizeof($id);$i++){
											if($_GET['category']==$cat_id[$i]){
												if($qty[$i]=='Available' || $qty[$i]=='-') $qty_print=$qty[$i]; else
												if($sub_system!=0){ if($qty[$i]>100) $qty_print='100+'; else $qty_print=$qty[$i]; }else{ $qty_print=number_format($qty[$i]); }
												print '<tr bgcolor="#EEEEEE"><td style="padding-left:10px">'.$code[$i].'</td><td style="padding-left:10px; padding-right:10px">'.$description[$i].'</td><td align="right" style="padding-right:10px">'.number_format($w_price[$i],$decimal).'</td><td align="right" style="padding-right:10px">'.number_format($r_price[$i],$decimal).'</td><td align="right" style="padding-right:10px">'.$qty_print.'</td><td style="padding-left:10px; padding-right:10px">'.$sto_name[$i].'</td></tr>';
											}
										}

											?>

									</table>
								<?php }
							}
						}
					?>
				<?php } ?>
			</td>
		</tr>
	</table>
</form>

<?php
    include_once  'template/footer.php';
?>