<?php
	include_once  'template/m_header.php';
	$retail_shop=$_COOKIE['retail'];
	$sub_system=$_COOKIE['sub_system'];
?>
<link rel="stylesheet" href="plugin/jquery/css/jquery.ui.all.css" />
<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
<script src="plugin/jquery/ui/jquery.ui.core.js"></script>
<script src="plugin/jquery/ui/jquery.ui.widget.js"></script>
<script src="plugin/jquery/ui/jquery.ui.position.js"></script>
<script src="plugin/jquery/ui/jquery.ui.autocomplete.js"></script>
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
			var code = [<?php for ($x=0;$x<sizeof($code);$x++){ print '"'.$code[$x].'",'; } ?>	];
			var description = [<?php for ($x=0;$x<sizeof($description);$x++){ print '"'.$description[$x].'",'; } ?>	];
			var itemcode=document.getElementById('tags1').value;
			var itemdesc=document.getElementById('tags2').value;

			if(itemcode!=''){
				var a=code.indexOf(itemcode);
				document.getElementById('tags2').value=description[a];
			}else if(itemdesc!=''){
				var a=description.indexOf(itemdesc);
				document.getElementById('tags1').value=code[a];
			}
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

	<div class="w3-container" style="margin-top:75px">
		<?php
			if(isset($_REQUEST['message'])){
				if($_REQUEST['re']=='success') $color='green'; else $color='red';
			print '<span style="color:'.$color.'; font-weight:bold;font-size:large;">'.$_REQUEST['message'].'</span>';
			}
		?>

		<hr>
		<div class="w3-row">
			<div class="w3-col s3"></div>
			<div class="w3-col">
				<table>
					<tr>
						<td style="vertical-align:top;">
							<?php if($current_district!=''){ ?>
								<table>
									<tr style="font-size:large; background-color:#EEEEEE">
										<td>Item Code</td>
										<td colspan="2"><input type="text" name="code" id="tags1" value="<?php print $search_code; ?>"
												onfocus="this.value=''" /></td>
									</tr>
									<tr style="font-size:large; background-color:#EEEEEE">
										<td>Item Description</td>
										<td colspan="2"><input type="text" name="description" id="tags2"
												value="<?php print $search_description; ?>" onfocus="this.value=''" onclick="clearBox()" /></td>
									</tr>
									<tr style="font-size:large; background-color:#EEEEEE">
										<td colspan="3" align="center"><input type="Submit" value="Check Availability"
												style="width:170px; height:60px" onclick="getPrice()" /><br /><br /></td>
									</tr>
									<tr>
										<td colspan="3" style="font-size:12pt">
											<table border="1" cellspacing="0" width="90%" align="center">
												<tr>
													<th>DR</th>
													<th>Store</th>
													<?php if($retail_shop==1) print '<th>R/Price</th>'; else print '<th>W/Price</th>'; ?><th>Qty
													</th>
												</tr>
												<?php
													for($i=0;$i<sizeof($store_name);$i++){
														print '<tr><td align="center">'.$item_dr[$i].'</td><td style="padding-left:10px">'.$store_name[$i].'</td>';
														if($retail_shop==1){
															print '<td style="text-align:right; padding-right:10px">'.number_format($item_r_price[$i]).'</td>';
														}else{
															print '<td style="text-align:right; padding-right:10px">'.number_format($item_w_price[$i]).'</td>';
														}
														print '<td style="text-align:right; padding-right:10px">'.$item_qty[$i].'</td></tr>';
													}
												?>
											</table>
										</td>
									</tr>
									<tr>
										<td colspan="3">
											<hr />
										</td>
									</tr>
									<tr style="font-size:large; background-color:#EEEEEE">
										<td>List By Category</td>
										<td colspan="2">
											<select id="category"
												onchange="window.location = 'index.php?components=availability&action=home&category='+document.getElementById('category').value">
												<option value="off">--SELECT--</option>
												<?php
													for($i=0;$i<sizeof($category_id);$i++){
														if ($category_id[$i] == 17 && $systemid == 13 && $sub_system == 1) {
															continue; // Skip this iteration
														}
														if($category_id[$i]==$_GET['category']) $select='selected="selected"'; else $select='';
														print '<option value="'.$category_id[$i].'" '.$select.'>'.$category_name[$i].'</option>';
													}
												?>
											</select>
										</td>
									</tr>
								</table>
							<?php } ?>
						</td>
					</tr>
					<tr style="font-size:large; background-color:#EEEEEE">
						<td colspan="3"><br /><br /></td>
					</tr>
				</table>
			</div>
		</div>
		<hr>
		<div class="w3-row">
			<div class="w3-col s3"></div>
			<div class="w3-col" style="vertical-align:top">
				<div id="portrait">
					<?php if($current_district!=''){ ?>
						<?php
							if(isset($_GET['category'])){
								if($_GET['category']!=='off'){
									if(($systemid!=1)&&($systemid!=4)){ ?>
										<table align="center" bgcolor="#EFEFEF" border="1">
											<tr>
												<th>Item Code</th>
												<th>Description</th>
												<th>Wholesale Price</th>
												<th>Retail Price</th>
												<th>Quantity</th>
												<th>Location</th>
											</tr>
											<?php
												for($i=0;$i<sizeof($id);$i++){
													if($_GET['category']==$cat_id[$i])
														print '<tr><td>' .$code[$i].'</td> <td>
														'.$description[$i].'</td>
														<td align="right">'.number_format($w_price[$i]).'</td>
														<td align="right">'.number_format($r_price[$i]).'</td>
														<td>'.number_format($qty[$i]).'</td>
														<td>'.$sto_name[$i].'</td>
														</tr>';
												}
											?>
										</table>
									<?php }
								}
							}
					}?>
				</div>
			</div>
		</div>
		<hr />
		<div class="w3-row">
			<div class="w3-col s3"></div>
			<div class="w3-col " align="center"></div>
		</div>
		<hr>
	</div>
</form>

<?php
    include_once  'template/m_footer.php';
?>