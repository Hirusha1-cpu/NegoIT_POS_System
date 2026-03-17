<?php
	$default_min_w_rate=$default_max_w_rate=$default_max_r_rate=$unic_yes=$unic_no=$default_commision='';
	getDefaultPrices();
?>

<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px" /></div>

<form action="index.php?components=inventory&action=add_item" onsubmit="return validateAddItem()" method="post">
	<input type="hidden" name="type" value="<?php print $_GET['type']; ?>" />
	<table align="center" bgcolor="#E5E5E5" style="font-family:Calibri">
		<!-- Notifications -->
		<tr>
			<td colspan="4">
				<?php
					if(isset($_REQUEST['message'])){
						if($_REQUEST['re']=='success') $color='green'; else $color='red';
					print '<span style="color:'.$color.'; font-weight:bold;">'.$_REQUEST['message'].'</span>';
					}
				?>
				<br />
			</td>
		</tr>
		<!-- Current Store  -->
		<tr>
			<td width="50px"></td>
			<td style="font-size:12pt">Current Store</td>
			<td style="font-size:12pt"><strong><?php print ucfirst($currentstore); ?></strong></td>
			<td width="50px">
				<br /><br /><br />
			</td>
		</tr>
		<!-- Product/Service -->
		<tr>
			<td width="50px"></td>
			<td>Product/Service <span style="color:red;">*</span></td>
			<td>
				<select id="pr_sr" name="pr_sr"
					onchange="window.location = 'index.php?components=inventory&action=show_add_item&type='+this.value"
					style="width:100%">
					<option value="1" <?php if($_GET['type']==1) print 'selected="selected"';  ?>>Product</option>
					<option value="2" <?php if($_GET['type']==2) print 'selected="selected"';  ?>>Service</option>
					<option value="3" <?php if($_GET['type']==3) print 'selected="selected"';  ?>>Repair</option>
				</select>
			</td>
			<td width="50px">
				<div id="id1"></div>
			</td>
		</tr>
		<!-- Item Category -->
		<tr>
			<td width="50px"></td>
			<td>Item Category <span style="color:red;">*</span></td>
			<td>
				<select id="category" name="category">
					<option value="">--SELECT--</option>
					<?php
						for($i=0;$i<sizeof($category_id);$i++){
							print '<option value="'.$category_id[$i].'" >'.$category_name[$i].'</option>';
						}
					?>
				</select>
			</td>
			<td width="50px">
				<div id="id2"></div>
			</td>
		</tr>
		<!-- Supplier -->
		<tr>
			<td width="50px"></td>
			<td>Supplier</td>
			<td>
				<select id="supplier" name="supplier" style="width:100%">
					<option value="">--SELECT--</option>
					<?php
						for($i=0;$i<sizeof($su_id);$i++){
							print '<option value="'.$su_id[$i].'" >'.$su_name[$i].'</option>';
						}
					?>
				</select>
			</td>
			<td width="50px"></td>
		</tr>
		<!-- Item Code -->
		<tr>
			<td></td>
			<td>Item Code <span style="color:red;">*</span></td>
			<td>
				<input type="text" name="code" id="code" />
			</td>
			<td width="50px">
				<div id="id3"></div>
			</td>
		</tr>
		<!-- Item Description -->
		<tr>
			<td></td>
			<td>Item Description <span style="color:red;">*</span></td>
			<td>
				<input type="text" name="description" id="description" />
			</td>
			<td>
				<div id="id4"></div>
			</td>
		</tr>
		<!-- PO Description -->
		<tr>
			<td></td>
			<td>PO Description</td>
			<td>
				<input type="text" name="po_description" id="po_description"/>
			</td>
			<td></td>
		</tr>
		<!-- Unit -->
		<?php if($systemid == 13){?>
		<tr>
			<td></td>
			<td>Unit Type</td>
			<td>
				<select name="unit_type" id="unit_type">
					<option value="">-SELECT-</option>
					<?php
						for($i=0;$i<sizeof($unit_type_id);$i++){
							print '<option value="'.$unit_type_id[$i].'" >'.$unit_type_name[$i].'</option>';
						}
					?>
				</select>
			</td>
			<td></td>
		</tr>
		<?php } ?>
		<?php if($_GET['type']==1){ ?>
		<!-- Item Wholesale Price -->
		<tr>
			<td></td>
			<td>Item Wholesale Price <span style="color:red;">*</span></td>
			<td>
				<input type="text" name="w_price" id="w_price" />
			</td>
			<td>
				<div id="id5"></div>
			</td>
		</tr>
		<!-- Item Retail Price -->
		<tr>
			<td></td>
			<td>Item Retail Price <span style="color:red;">*</span></td>
			<td>
				<input type="text" name="r_price" id="r_price" />
			</td>
			<td>
				<div id="id6"></div>
			</td>
		</tr>
		<!-- Item Cost -->
		<tr>
			<td></td>
			<td>Item Cost <span style="color:red;">*</span></td>
			<td>
				<input type="text" name="cost" id="cost" />
			</td>
			<td>
				<div id="id7"></div>
			</td>
		</tr>
		<!-- Min Wholesale Discount % -->
		<tr>
			<td></td>
			<td>Min Wholesale Discount % <span style="color:red;">*</span></td>
			<td>
				<input type="number" name="min_w_rate" id="min_w_rate" value="<?php print $default_min_w_rate; ?>"
					step="0.01" />
			</td>
			<td>
				<div id="id8"></div>
			</td>
		</tr>
		<!-- Max Wholesale Discount % -->
		<tr>
			<td></td>
			<td>Max Wholesale Discount % <span style="color:red;">*</span></td>
			<td>
				<input type="number" name="max_w_rate" id="max_w_rate" value="<?php print $default_max_w_rate; ?>"
					step="0.01" />
			</td>
			<td>
				<div id="id9"></div>
			</td>
		</tr>
		<!-- Max Retail Discount % -->
		<tr>
			<td></td>
			<td>Max Retail Discount % <span style="color:red;">*</span></td>
			<td>
				<input type="number" name="max_r_rate" id="max_r_rate" value="<?php print $default_max_r_rate; ?>"
					step="0.01" />
			</td>
			<td>
				<div id="id10"></div>
			</td>
		</tr>
		<!-- Drawer Number -->
		<tr>
			<td></td>
			<td>Drawer Number</td>
			<td>
				<input type="text" name="drawer" id="drawer" />
			</td>
			<td></td>
		</tr>
		<!-- Unique Item -->
		<tr>
			<td></td>
			<td>Unique Item <span style="color:red;">*</span></td>
			<td>
				<select name="unic" id="unic" onchange="setUnic()">
					<option value="">-SELECT-</option>
					<option value="1" <?php print $unic_yes; ?>>Yes</option>
					<option value="0" <?php print $unic_no; ?> selected="selected">No</option>
				</select>
			</td>
			<td>
				<div id="id11"></div>
			</td>
		</tr>
		<!-- Initial Quantity -->
		<tr>
			<td></td>
			<td>Initial Quantity</td>
			<td>
				<input type="number" name="qty" id="qty" />
			</td>
			<td></td>
		</tr>
		<?php }else if($_GET['type']==2 || $_GET['type']==3){?>
		<!-- Default Cost -->
		<tr>
			<td></td>
			<td>Default Cost</td>
			<td>
				<input type="number" name="cost" id="cost" />
			</td>
			<td>
				<div id="id7"></div>
			</td>
		</tr>
		<!-- Default Price -->
		<tr>
			<td></td>
			<td>Default Price</td>
			<td>
				<input type="number" name="w_price" id="w_price" />
			</td>
			<td>
				<div id="id5"></div>
			</td>
		</tr>
		<tr>
			<td colspan="4">
				<input type="hidden" id="r_price" value="10" />
				<input type="hidden" id="min_w_rate" value="10" />
				<input type="hidden" id="max_w_rate" value="10" />
				<input type="hidden" id="max_r_rate" value="10" />
				<input type="hidden" id="drawer" value="10" />
				<input type="hidden" id="unic" value="0" />
				<input type="hidden" id="qty" value="10" />
				<div style="display:none" id="id6"></div>
				<div style="display:none" id="id7"></div>
				<div style="display:none" id="id8"></div>
				<div style="display:none" id="id9"></div>
				<div style="display:none" id="id10"></div>
				<div style="display:none" id="id11"></div>
			</td>
		</tr>
		<?php } ?>
		<!-- Commision -->
		<tr>
			<td></td>
			<td>Commision &nbsp;%</td>
			<td>
				<input type="text" name="commision" id="commision" value="<?php print $default_commision; ?>" />
			</td>
			<td></td>
		</tr>
		<?php
			if(($systemid!=1)&&($systemid!=4))  include_once  'components/inventory/view/tpl/tags1.php';
		?>
		<tr>
			<td colspan="4" align="center">
				<br />
				<div id="div_add_item">
					<input type="submit" value="Add Item" style="width:130px; height:50px" />
				</div>
				<br /><br />
			</td>
		</tr>
		<tr>
			<td></td>
			<td colspan="2">
				<span style="color:red;">*</span> Required fields </small>
				<br>
				<span><small><em>Note : If item description and po description are same, leave po description empty</em></small></span>
				<br /><br />
			</td>
			<td></td>
		</tr>
	</table>
</form>