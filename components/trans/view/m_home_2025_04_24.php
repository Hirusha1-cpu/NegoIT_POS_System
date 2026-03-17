<?php
                include_once  'template/m_header.php';
?>
	<link rel="stylesheet" href="plugin/jquery/css/jquery.ui.all.css" />
	<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.core.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.widget.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.position.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.autocomplete.js"></script>
	<script type="text/javascript">
	$(function() {
		var availableTags1 = [<?php for ($x=0;$x<sizeof($code);$x++){ print '"'.$code[$x].'",'; } ?>	];
		$( "#tags1" ).autocomplete({
			source: availableTags1
		});
		var availableTags2 = [<?php for ($x=0;$x<sizeof($description);$x++){ print '"'.$description[$x].'",'; } ?>	];
		$( "#tags2" ).autocomplete({
			source: availableTags2
		});
		var availableTags4 = [<?php for ($x=0;$x<sizeof($unic_item_list);$x++){ print '"'.$unic_item_list[$x].'",'; } ?>	];
		$( "#tags4" ).autocomplete({
			source: availableTags4
		});
		$( "#tags5" ).autocomplete({ source: availableTags4	});
		$( "#tags6" ).autocomplete({ source: availableTags4	});
		$( "#tags7" ).autocomplete({ source: availableTags4	});
		$( "#tags8" ).autocomplete({ source: availableTags4	});
		$( "#tags9" ).autocomplete({ source: availableTags4	});
		$( "#tags10" ).autocomplete({ source: availableTags4 });
		$( "#tags11" ).autocomplete({ source: availableTags4 });
		$( "#tags12" ).autocomplete({ source: availableTags4 });
		$( "#tags13" ).autocomplete({ source: availableTags4 });
	});
	
	function getPrice(){
		var itemid = [<?php for ($x=0;$x<sizeof($id);$x++){ print '"'.$id[$x].'",'; } ?>	];
		var code = [<?php for ($x=0;$x<sizeof($code);$x++){ print '"'.$code[$x].'",'; } ?>	];		
		var description = [<?php for ($x=0;$x<sizeof($description);$x++){ print '"'.$description[$x].'",'; } ?>	];
		var wprice = [<?php for ($x=0;$x<sizeof($w_price);$x++){ print '"'.$w_price[$x].'",'; } ?>	];
		var rprice = [<?php for ($x=0;$x<sizeof($r_price);$x++){ print '"'.$r_price[$x].'",'; } ?>	];
		var qty = [<?php for ($x=0;$x<sizeof($qty);$x++){ print '"'.$qty[$x].'",'; } ?>	];		
		var drawer = [<?php for ($x=0;$x<sizeof($drawer);$x++){ print '"'.$drawer[$x].'",'; } ?>	];		
		var ttitm = [<?php for ($x=0;$x<sizeof($tt_item);$x++){ print '"'.$tt_item[$x].'",'; } ?>	];	
		var ttqty = [<?php for ($x=0;$x<sizeof($tt_qty);$x++){ print '"'.$tt_qty[$x].'",'; } ?>	];	
		var unic = [<?php for ($x=0;$x<sizeof($unic);$x++){ print '"'.$unic[$x].'",'; } ?>	];	
		var itemcode=document.getElementById('tags1').value;
		var itemdesc=document.getElementById('tags2').value;
		var itm_tmp=document.getElementById('itm_tmp').value;
		var trans_no=document.getElementById('id').value;
		var trans_remotestore=document.getElementById('remotestore').value;
		var unic_list_size=document.getElementById('unic_list_size').value;
		if(itemcode!=''){
			var a=code.indexOf(itemcode);
			var b=ttitm.indexOf(itemcode);
			document.getElementById('itemid').value=itemid[a];
			document.getElementById('tags2').value=description[a];			
			document.getElementById('priceshow').innerHTML='<select name="price"><option value="'+wprice[a]+'">Wholesale - '+wprice[a]+'</option><option value="'+rprice[a]+'">Retail - '+rprice[a]+'</option></select>';
			document.getElementById('av_qty').innerHTML=qty[a];
			document.getElementById('tt_qty').innerHTML='';
			document.getElementById('it_drawer').innerHTML=drawer[a];
			if(b>-1){
				document.getElementById('av_qty').innerHTML='Old Stock - '+qty[a];
				document.getElementById('tt_qty').innerHTML='New Stock - '+ttqty[b];
			}
			if((unic[a]==1)&&(itm_tmp!=itemcode)){
				document.getElementById('addtogtn').innerHTML='';
				window.location = 'index.php?components=trans&action=home&id='+trans_no+'&s=1&remotestore='+trans_remotestore+'&itemid='+itemid[a]+'&unic=yes';
			}
			if((unic[a]==0)&&(unic_list_size>0)){
				window.location = 'index.php?components=trans&action=home&id='+trans_no+'&s=1&remotestore='+trans_remotestore+'&itemid='+itemid[a]+'&unic=no';
			}
		}else if(itemdesc!=''){
			var a=description.indexOf(itemdesc);
			document.getElementById('itemid').value=itemid[a];			
			document.getElementById('tags1').value=code[a];
			document.getElementById('priceshow').innerHTML='<select name="price"><option value="'+wprice[a]+'">Wholesale - '+wprice[a]+'</option><option value="'+rprice[a]+'">Retail - '+rprice[a]+'</option></select>';
			document.getElementById('av_qty').innerHTML=qty[a];
			document.getElementById('tt_qty').innerHTML='';
			document.getElementById('it_drawer').innerHTML=drawer[a];		
			var itemcode=document.getElementById('tags1').value;
			var b=ttitm.indexOf(itemcode);
			if(b>-1){
				document.getElementById('av_qty').innerHTML='Old Stock - '+qty[a];
				document.getElementById('tt_qty').innerHTML='New Stock - '+ttqty[b];
			}

			if((unic[a]==1)&&(itm_tmp!=itemcode)){
				document.getElementById('addtogtn').innerHTML='';
				window.location = 'index.php?components=trans&action=home&id='+trans_no+'&s=1&remotestore='+trans_remotestore+'&itemid='+itemid[a]+'&unic=yes';
			}
			if((unic[a]==0)&&(unic_list_size>0)){
				window.location = 'index.php?components=trans&action=home&id='+trans_no+'&s=1&remotestore='+trans_remotestore+'&itemid='+itemid[a]+'&unic=no';
			}
		}
	}
	
	function finalize1(){
		document.getElementById('finalize1').innerHTML=''; 
		window.location = 'index.php?components=trans&action=finish_gtn&id=<?php if(isset($_GET['id'])) print $_GET['id']; ?>&approve_permission=0';
	}
	</script>

<!-- ------------------------------------------------------------------------------------ -->
<?php 
	if(isset($_GET['id'])) $id=$_GET['id']; else $id=$gtn_no;
?>
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
	<form action="index.php?components=trans&action=apend_gtn" onsubmit="return validateGTN()" method="post" >
	<input type="hidden" name="id" id="id" value="<?php print $id; ?>" />
	<input type="hidden" id="itm_tmp" value="<?php if($unic_item_code!='') print $unic_item_code; ?>" />
	<input type="hidden" id="qty" name="qty" value="" />
	<input type="hidden" id="unic_list_size" value="<?php print sizeof($unic_item_list); ?>" />
	<?php if(sizeof($unic_item_list)==0){
		print '<input type="hidden" name="unic_item" value="0" />';
	}else{
		print '<input type="hidden" name="unic_item" value="1" />';
	 } ?>
	
	<table align="center">
	<tr><td valign="top">
	<table width="100%"><tr><td><h1 style="color:orange">Stores Transfer</h1></td><td align="right" style="font-weight:bold; font-size:12pt;">TR No: <?php print str_pad($id, 7, "0", STR_PAD_LEFT); ?></td></tr></table>
		<table align="center" bgcolor="#E5E5E5">
		<tr><td width="50px"></td><td style="font-size:12pt">Item Code</td><td colspan="2"><input type="text" name="code" id="tags1" value="<?php if($unic_item_code!='') print $unic_item_code; ?>"/></td><td width="50px"></td></tr>
		<tr><td width="50px"></td><td style="font-size:12pt">Item Description</td><td colspan="2"><input type="text" name="description" id="tags2" /></td><td width="50px"></td></tr>
		<tr><td></td><td style="font-size:12pt">Quantity</td><td><input type="number" name="qty1" id="qty1" onfocus="getPrice()" style="width:50px" <?php if(sizeof($unic_item_list)>0){ ?> value="1" disabled="disabled"  <?php }?> /></td><td><div style="font-size:10pt;" id="av_qty" align="right"></div><div style="font-size:10pt; color:#CC0000" id="tt_qty" align="right"></div></td><td></td></tr>
		<tr><td></td><td style="font-size:12pt">Unit Price</td><td colspan="2">
		<div id="priceshow"></div>
		<input type="hidden" name="itemid" id="itemid" />
		<input type="hidden" name="salesman" id="salesman" value="<?php print $_COOKIE['user_id']; ?>" />
		</td><td></td></tr>
		<tr><td width="50px"></td><td style="font-size:12pt">Remote Store</td><td colspan="2">
		<?php
			if(isset($_GET['remotestore'])){
				if($_GET['remotestore']!=0){
				$temp_id=array_search($_GET['remotestore'],$store_id);
				print '<span style="font-size:12pt">'.$store_name[$temp_id].'</span>';
				print '<input type="hidden" name="remotestore" id="remotestore" value="'.$_GET['remotestore'].'" />';
				}else{ ?>
				<select name="remotestore" id="remotestore">
					<option value="0">--SELECT--</option>
					<?php
					 for($i=0;$i<sizeof($store_id);$i++) print '<option value="'.$store_id[$i].'">'.$store_name[$i].'</option>';
					?>
				</select>
			<?php		}
			}else{
		?>
			<select name="remotestore" id="remotestore">
				<option value="0">--SELECT--</option>
				<?php
				 for($i=0;$i<sizeof($store_id);$i++) print '<option value="'.$store_id[$i].'">'.$store_name[$i].'</option>';
				?>
			</select>
		<?php } ?>
		</td><td width="50px"></td></tr>
		<tr><td width="50px"></td><td style="font-size:12pt">Drawer No</td><td colspan="2"><div style="font-size:12pt" id="it_drawer"></div></td><td width="50px"></td></tr>
		<?php 
		$k=0;
		if(sizeof($unic_item_list)>0){ 
				for($i=1;$i<=10;$i++){
					if($i<=$unic_qty){
						$k++;
						if($i==1) $trigger='onfocus="getPrice()"'; else $trigger='';
						print '<tr><td></td><td style="font-size:12pt">Unic Item'.$i.'</td><td><input type="text" name="unic_item'.$i.'" id="tags'.($i+3).'" '.$trigger.' /></td><td></td><td></td></tr>';
			 		}else{
			 			print '<input type="hidden" name="unic_item'.$i.'"  />';
			 		}
			 	}
			 	print '<input type="hidden" id="uitem_limit" value="'.$k.'" />';
		} ?>
		<tr><td colspan="2" align="right"><div id="addtogtn"><input type="submit" value="Add to GTN" style="width:100px; height:30px" />&nbsp;</div> 
		</td><td colspan="3" align="left"><div id="finalize1"><?php if(sizeof($gtn_itemid)>0){ ?><input type="Button" value="Finalize" style="width:100px; height:70px" onclick="finalize1()" /><?php } ?></div>
		<br /><br /></td></tr>
		
		</table>
	</td><td width="10px"></td><td style="vertical-align:top;">
		<div id="landscape" style="vertical-align:top" ></div>
	</td></tr>
	</table>
	</form>
  </div>
</div>

<hr>
	<div class="w3-row">
	  <div class="w3-col s3">
	  </div>
	  <div class="w3-col" style="vertical-align:top">
		<div id="portrait">

	<!-- ------------------Item List----------------------- -->
		<table align="center" bgcolor="#E5E5E5" height="100%">
	<?php
		for($i=0;$i<sizeof($gtn_itemid);$i++){
			$new_description[]=$gtn_desc2[$i];
			$counts=array_count_values($new_description);
			$count=$counts[$gtn_desc2[$i]];
			$tc=array_search($gtn_desc2[$i],$dups);
			if($tc>-1){ 
				if($dups_count[$tc]==$count) $allow_remove=true; else $allow_remove=false; 
			}else $allow_remove=true;
			if($gtn_no_update[$i]==0) $update_button='<input type="Button" value="Update"  onclick="updateGTN('.$gtn_itemid[$i].')" />'; else $update_button='<input type="Button" value="Update" onclick="alert('."'Update is Restricted for this item'".')" />';
			if($allow_remove) $remove_button='<input type="Button" value="Remove"  onclick="removeGTN('.$gtn_itemid[$i].')" style="background-color:maroon; color:white"/>'; else $remove_button='';
			print '<tr style="font-size:12pt"><td width="30px" style="color:blue"><strong>'.($i+1).'</strong></td><td>'.$gtn_desc[$i].'</td><td width="50px"></td><td align="right"><input style="width:50px; type="text" id="gtnitemid'.$gtn_itemid[$i].'" value="'.$gtn_qty[$i].'" /> '.$update_button.' '.$remove_button.'</td></tr>';
		}
	?>	
		</table>
	  </div>
	</div>
</div>
<hr>
<?php
if(isset($unic_item_code))
if($unic_item_code!=''){
?>
<script type="text/javascript">
getPrice();
</script>
<?php
}
                include_once  'template/m_footer.php';
?>