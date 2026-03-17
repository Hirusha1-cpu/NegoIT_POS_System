<?php
if($_REQUEST['action']=='show_edit_item'){ ?>

	<link rel="stylesheet" href="plugin/jquery/css/jquery.ui.all.css" />
	<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.core.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.widget.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.position.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.autocomplete.js"></script>
	<script>
	$(function() {
		var availableTags1 = [<?php for ($x=0;$x<sizeof($code);$x++){ print '"'.$code[$x].'",'; } ?>	];
		$( "#tags1" ).autocomplete({
			source: availableTags1
		});
		var availableTags2 = [<?php for ($x=0;$x<sizeof($description);$x++){ print '"'.$description[$x].'",'; } ?>	];
		$( "#tags2" ).autocomplete({
			source: availableTags2
		});
	});

	function setCode(){
		var code_arr = [<?php for ($x=0;$x<sizeof($code);$x++){ print '"'.$code[$x].'",'; } ?>	];
		var desc_arr = [<?php for ($x=0;$x<sizeof($description);$x++){ print '"'.$description[$x].'",'; } ?>	];
		var code=document.getElementById('tags1').value;
		var desc=document.getElementById('tags2').value;
		if(code==''){
			var a=desc_arr.indexOf(desc);
			document.getElementById('tags1').value=code_arr[a];
		}
	}
	</script>

	<form action="index.php?components=inventory&action=show_one_item" method="post" >
		<table align="center" bgcolor="#E5E5E5">
			<!-- Notifications -->
			<tr>
				<td colspan="4">
					<?php
						if(isset($_REQUEST['message'])){
							if($_REQUEST['re']=='success') $color='green'; else $color='red';
						print '<span style="color:'.$color.'; font-weight:bold;font-size:12pt;">'.$_REQUEST['message'].'</span>';
						}
					?>
					<br/>
				</td>
			</tr>
			<!-- Item Code -->
			<tr>
				<td width="50px"></td>
				<td style="font-size:12pt">Item Code</td>
				<td>
					<input type="text" name="code0" id="tags1" />
				</td>
				<td width="50px"></td>
			</tr>
			<!-- Item Description -->
			<tr>
				<td></td>
				<td style="font-size:12pt">Item Description</td>
				<td>
					<input type="text" id="tags2" />
				</td>
				<td></td>
			</tr>
			<!-- Submit -->
			<tr>
				<td colspan="4" align="center">
					<br/>
					<input type="submit" value="Search Item" style="width:130px; height:50px" onclick="setCode()" />
					<br/><br/>
				</td>
			</tr>
		</table>
	</form>

<?php }else{ ?>

<script type="text/javascript">
	function updateQtyTB($i,$inv_type,$tb_id,$stores_id){
		var $item_id=document.getElementById('id').value;
		var $master_pw=document.getElementById('master_pw').value;
		var $w_price=document.getElementById('w_price'+$i).value;
		var $r_price=document.getElementById('r_price'+$i).value;
		var $c_price=document.getElementById('c_price'+$i).value;
		var $qty=document.getElementById('qty'+$i).value;
		var $drawer=document.getElementById('drawer'+$i).value;
		//window.location = 'index.php?components=inventory&action=edit_item2&master_pw='+$master_pw+'&inv_type='+$inv_type+'&tb_id='+$tb_id+'&item_id='+$item_id+'&stores_id='+$stores_id+'&w_price='+$w_price+'&r_price='+$r_price+'&c_price='+$c_price+'&qty='+$qty+'&drawer='+$drawer;

		document.getElementById('div_btn_'+$i).innerHTML=document.getElementById('loading').innerHTML;
		  var xhttp = new XMLHttpRequest();
		  xhttp.onreadystatechange = function() {
		  if(this.readyState == 4 && this.status == 200) {
		 	var returntext=this.responseText;
		 	$values=returntext.split('|');
			    if($values[1]=='Done'){
					document.getElementById('div_btn_'+$i).innerHTML='<span style="color:green">Done</span>';
		    	}else{
		  			document.getElementById('div_btn_'+$i).innerHTML='<span style="color:maroon">'+$values[1]+'</span>';
		    	}
		    }
		  };
		xhttp.open("GET", 'index.php?components=inventory&action=edit_item2&master_pw='+$master_pw+'&inv_type='+$inv_type+'&tb_id='+$tb_id+'&item_id='+$item_id+'&stores_id='+$stores_id+'&w_price='+$w_price+'&r_price='+$r_price+'&c_price='+$c_price+'&qty='+$qty+'&drawer='+$drawer, true);
		xhttp.send();
	}
</script>

<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px" /></div>

<form action="index.php?components=inventory&action=edit_item1" method="post" >
	<input type="hidden" id="id" name="id" value="<?php print $id; ?>" />
	<table align="center" bgcolor="#E5E5E5">
		<!-- Notifications -->
		<tr>
			<td colspan="4">
				<?php
					if(isset($_REQUEST['message'])){
						if($_REQUEST['re']=='success') $color='green'; else $color='red';
					print '<span style="color:'.$color.'; font-weight:bold;">'.$_REQUEST['message'].'</span>';
					}
				?>
		<br/></td>
		</tr>
		<!-- Item Status -->
		<tr>
			<td width="<?php print $tb_width; ?>"></td>
			<td>Item Status</td>
			<td>
				<input type="radio" name="item_st" value="on" <?php if($itm_status==1) print 'checked="checked"'; ?>> ON &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="radio" name="item_st" value="off" <?php if($itm_status==0) print 'checked="checked"'; ?>> OFF<br>
			</td>
			<td width="<?php print $tb_width; ?>"></td>
		</tr>
		<!-- Item Category -->
		<tr>
			<td></td>
			<td>Item Category</td>
			<td>
				<select name="category" id="category">
					<?php
						for($i=0;$i<sizeof($category_id);$i++){
							if($category_id[$i]==$category) $select='selected="selected"'; else $select='';
							print '<option value="'.$category_id[$i].'" '.$select.'>'.$category_name[$i].'</option>';
						}
					?>
				</select>
			</td>
			<td></td>
		</tr>
		<!-- Supplier -->
		<tr>
			<td></td>
			<td>Supplier</td>
			<td>
				<select name="supplier" id="supplier" style="width:160px">
					<?php
						print '<option value="0">-No Default Supplier-</option>';
						for($i=0;$i<sizeof($su_id);$i++){
							if($su_id[$i]==$supplier) $select='selected="selected"'; else $select='';
							print '<option value="'.$su_id[$i].'" '.$select.'>'.$su_name[$i].'</option>';
						}
					?>
				</select>
			</td>
			<td></td>
		</tr>
		<!-- Item Code -->
		<tr>
			<td></td>
			<td>Item Code</td>
			<td>
				<input type="text" name="code" id="code" value="<?php print $code; ?>" />
			</td>
			<td></td>
		</tr>
		<!-- Item Description -->
		<tr>
			<td></td>
			<td>Item Description</td>
			<td>
				<input type="text" name="description" id="description" value="<?php print $description; ?>" />
			</td>
			<td></td>
		</tr>
		<!-- PO Description -->
		<tr>
			<td></td>
			<td>PO Description</td>
			<td>
				<input type="text" name="po_description" id="po_description" value="<?php print $po_description; ?>" />
			</td>
			<td></td>
		</tr>
		<?php if($systemid == 13){?>
			<!-- Unit -->
			<tr>
				<td></td>
				<td>Unit Type</td>
				<td>
				<select name="unit_type" id="unit_type">
					<option value="">-SELECT-</option>
					<?php
						for($i=0;$i<sizeof($unit_type_id);$i++){
							if($unit_type_id[$i]==$unit_type) $select='selected="selected"'; else $select='';
							print '<option value="'.$unit_type_id[$i].'" '.$select.'>'.$unit_type_name[$i].'</option>';
						}
					?>
				</select>
				</td>
				<td></td>
			</tr>
		<?php } ?>
		<?php if($itm_pr_sr==1){ ?>
			<!-- Min Wholesale Discount -->
			<tr>
				<td></td>
				<td>Min Wholesale Discount %</td>
				<td>
					<input type="text" name="min_w_rate" id="min_w_rate" value="<?php print $min_w_rate; ?>" step="0.01"/>
				</td>
				<td></td>
			</tr>
			<!-- Max Wholesale Discount -->
			<tr>
				<td></td>
				<td>Max Wholesale Discount %</td>
				<td>
					<input type="text" name="max_w_rate" id="max_w_rate" value="<?php print $max_w_rate; ?>" step="0.01"/>
				</td>
				<td></td>
			</tr>
			<!-- Max Retail Discount -->
			<tr>
				<td></td>
				<td>Max Retail Discount %</td>
				<td>
					<input type="text" name="max_r_rate" id="max_r_rate" value="<?php print $max_r_rate; ?>" step="0.01"/>
				</td>
				<td></td>
			</tr>
		<?php } if($itm_pr_sr==2 || $itm_pr_sr==3){ ?>
			<!-- Default Cost -->
			<tr>
				<td></td>
				<td>Default Cost</td>
				<td>
					<input type="text" name="c_price" id="c_price" value="<?php print $itm_def_cost; ?>" />
				</td>
				<td></td>
			</tr>
			<!-- Default Price -->
			<tr>
				<td></td>
				<td>Default Price</td>
				<td>
					<input type="text" name="d_price" id="d_price" value="<?php print $itm_def_price; ?>" />
				</td>
				<td></td>
			</tr>
			<tr>
				<td colspan="4">
				<input type="hidden" name="min_w_rate" id="min_w_rate" value="<?php print $min_w_rate; ?>" />
				<input type="hidden" name="max_w_rate" id="max_w_rate" value="<?php print $max_w_rate; ?>" />
				<input type="hidden" name="max_r_rate" id="max_r_rate" value="<?php print $max_r_rate; ?>" />
				<input type="hidden" name="max_r_rate" id="max_r_rate" value="<?php print $max_r_rate; ?>" />
				<input type="hidden" name="item_unic" value="<?php if($itm_unic==1) print 'yes'; else print 'no'; ?>" />
				</td>
			</tr>
		<?php } ?>
		<tr><td></td><td>Commision &nbsp;%</td><td><input type="text" name="commision" id="commision" value="<?php print $commision; ?>" /></td><td></td></tr>
		<?php if($itm_pr_sr==1){ ?>
			<!-- Unique Item -->
			<tr>
				<td></td>
				<td>Unique Item</td>
				<td>
					<input type="hidden" name="d_price" id="d_price" value="<?php print $itm_def_price; ?>" />
					<input type="hidden" name="c_price" id="c_price" value="<?php print $itm_def_cost; ?>" />
					<?php if(array_sum($qty)>0){ ?>
						<input type="hidden" name="item_unic" value="<?php if($itm_unic==1) print 'yes'; else print 'no'; ?>" />
						<input type="radio" <?php if($itm_unic==1) print 'checked="checked"'; if(array_sum($qty)>0) print 'disabled="disabled"'; ?> > Yes &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="radio" <?php if($itm_unic==0) print 'checked="checked"'; if(array_sum($qty)>0) print 'disabled="disabled"'; ?> > No<br>
					<?php }else{ ?>
						<input type="radio" name="item_unic" value="yes" <?php if($itm_unic==1) print 'checked="checked"'; if(array_sum($qty)>0) print 'disabled="disabled"'; ?>  > Yes &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="radio" name="item_unic" value="no" <?php if($itm_unic==0) print 'checked="checked"'; if(array_sum($qty)>0) print 'disabled="disabled"'; ?> > No<br>
					<?php }?>
				</td>
				<td></td>
			</tr>
		<?php } ?>
			<!-- Submit -->
			<tr>
				<td colspan="4" align="center">
					<br/>
					<input type="submit" value="Edit Item" style="width:130px; height:50px" />
					<br/><br/>
				</td>
			</tr>
		<?php if($itm_pr_sr==1){ ?>
			<tr>
				<td></td>
				<td colspan="2">
					<table width="100%" border="1" cellspacing="0">
						<tr>
							<th align="left">Store</th>
							<th style="background-color:#AAAAAA">Wholesale<br/>Price</th>
							<th style="background-color:#AAAAAA">Retail<br />Price</th>
							<th style="background-color:#AAAAAA">Cost</th>
							<th>Inventory<br/>Quantity</th>
							<th>Drawer No.</th>
							<th></th>
						</tr>
						<?php
							if($itm_unic==1) $style1='disabled="disabled" style="width:50px; background-color:silver; text-align:right;"'; else $style1='style="width:50px; text-align:right;"';
							for($i=0;$i<sizeof($stores_id);$i++){
								if($inv_type[$i]=='new') $style2='disabled="disabled" style="width:50px; background-color:silver; text-align:right;"'; else $style2='style="width:50px; text-align:right;"';
								if(($unic_cal) && ($itm_unic==1)) $style3='disabled="disabled"'; else $style3='';
								print '	<tr style="background-color:'.$color1[$i].'"><td>'.$stores_name[$i].'</td>
								<td style="background-color:#AAAAAA" align="center"><input type="text" id="w_price'.$i.'" style="width:50px" value="'.$w_price[$i].'" '.$style3.' /></td>
								<td style="background-color:#AAAAAA" align="center"><input type="text" id="r_price'.$i.'" style="width:50px" value="'.$r_price[$i].'" '.$style3.' /></td>
								<td style="background-color:#AAAAAA" align="center"><input type="text" id="c_price'.$i.'" style="width:50px" value="'.$c_price[$i].'" '.$style3.' /></td>
								<td align="center"><input type="number" id="qty'.$i.'" value="'.$qty[$i].'" '.$style1.' /></td>
								<td align="center"><input type="text" id="drawer'.$i.'" style="width:50px;" value="'.$drawer[$i].'" '.$style2.' /></td>
								<td align="center"><div id="div_btn_'.$i.'"><input type="button" value="Update" onclick="updateQtyTB('."'$i','$inv_type[$i]','$tb_id[$i]','$stores_id[$i]'".')" /></div></td>
								</tr>';
							}
						?>
					</table>
				</td>
				<td></td>
			</tr>
			<!-- Password -->
			<tr>
				<td></td>
				<td style="font-size:8pt; color:red;"><b>Important : to update quantity, please enter Master Password (01)</b></td>
				<td>
					<input type="password" id="master_pw" value="" />
				</td>
				<td></td>
			</tr>
			<tr>
				<td colspan="4" align="center"><br /></td>
			</tr>
		<?php } ?>
	</table>
</form>
<?php } ?>