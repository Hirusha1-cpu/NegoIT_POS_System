<?php
                include_once  'template/m_header.php';
?>
<!-- ------------------------------------------------------------------------------------ -->

	<link rel="stylesheet" href="plugin/jquery/css/jquery.ui.all.css" />
	<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.core.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.widget.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.position.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.autocomplete.js"></script>
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	<script type="text/javascript">
	<?php if($selection=='store'){ ?>
		$(function() {
			var availableTags2 = [<?php for ($x=0;$x<sizeof($st_name);$x++){ print '"'.$st_name[$x].'",'; } ?>	];
			$( "#tags2" ).autocomplete({ source: availableTags2	});
		});
	<?php }else{ ?>
		$(function() {
			var availableTags1 = [<?php for ($x=0;$x<sizeof($cu_name0);$x++){ print '"'.$cu_name0[$x].'",'; } ?>	];
			$( "#tags1" ).autocomplete({ source: availableTags1	});
		});
	<?php } ?>
	
	function changeSelection(){
		var availableTags1 = [<?php for ($x=0;$x<sizeof($cu_name0);$x++){ print '"'.$cu_name0[$x].'",'; } ?>	];
		var availableTags2 = [<?php for ($x=0;$x<sizeof($st_name);$x++){ print '"'.$st_name[$x].'",'; } ?>	];
		var selection=document.getElementById('selection').value;
		if(selection=='customer'){
			document.getElementById('div_sel').innerHTML='<input type="text" name="customer" id="tags1" style="width:300px" />';
			$( "#tags1" ).autocomplete({ source: availableTags1	});
		}
		if(selection=='store'){
			document.getElementById('div_sel').innerHTML='<input type="text" name="store1" id="tags2" style="width:300px" />';
			$( "#tags2" ).autocomplete({ source: availableTags2	});
		}
	}
	
	function setCustID(){
		var id_arr = [<?php for ($x=0;$x<sizeof($cu_id0);$x++){ print '"'.$cu_id0[$x].'",'; } ?>	];
		var name_arr = [<?php for ($x=0;$x<sizeof($cu_name0);$x++){ print '"'.$cu_name0[$x].'",'; } ?>	];
		var name=document.getElementById('tags1').value;
		if(name!=''){
			var a=name_arr.indexOf(name);
			document.getElementById('customer_id').value=id_arr[a];
		}
	}
</script>
<!-- ------------------Item List----------------------- -->
<div class="w3-container" style="margin-top:75px">
<hr>
<div class="w3-row">
  <div class="w3-col s3">
  </div>
  <div class="w3-col">

	<form action="index.php" method="get" onsubmit="return validateDateRange()" >
		<input type="hidden" name="components" value="<?php print $components; ?>" />
		<input type="hidden" name="action" value="sales_report2" />
		<input type="hidden" id="customer_id" name="customer_id" value="" />
		<table align="center" height="100%" style="font-size:10pt">
		<tr bgcolor="#EEEEEE"><td>
			<?php if($_GET['components']=='manager'){ ?>
			<select name="selection" id="selection" style="font-weight:bold" onchange="changeSelection()">
			<option value="customer" <?php if($selection=='customer') print 'selected="selected"'; ?> >Customer</option> 
			<option value="store" <?php if($selection=='store') print 'selected="selected"'; ?> >Store</option> 
			</select>
			<?php }else{ ?>
			<input type="hidden" name="selection" value="customer" />
			<strong>Customer</strong>
			<?php } ?>
		</td><td colspan="3"><div id="div_sel">
			<?php if($selection=='store')
				print '<input type="text" name="store1" id="tags2" value="'.$store1.'" style="width:300px" />';
			else
				print '<input type="text" name="customer" id="tags1" value="'.$customer.'" style="width:300px" />';
			?>
		</div></td></tr>
		<tr bgcolor="#EEEEEE"><td width="70px" ><strong>Category </strong></td><td colspan="3">
		<select name="category" id="category">
			<option value="all" >-ALL-</option>
			<?php for($i=0;$i<sizeof($cat_id);$i++){
				if($category==$cat_id[$i]) $select='selected="selected"'; else $select='';
				print '<option value="'.$cat_id[$i].'" '.$select.'>'.$cat_name[$i].'</option>';
			} ?>
		</select>
		</td></tr>
		<tr bgcolor="#EEEEEE"><td width="100px" ><strong>From Date </strong></td><td >
		<input type="date" id="datefrom" name="datefrom" style="width:130px" value="<?php print $fromdate; ?>" />
		</td><td align="right">&nbsp;&nbsp;&nbsp;&nbsp;<strong>To Date </strong></td><td>
		<input type="date" id="dateto" name="dateto" style="width:130px" value="<?php print $todate; ?>" />
		<input type="submit" value="GET" onclick="setCustID()" style="width:50px; height:40px" />
		</td></tr>
		</table>
	</form>
	
	<br />
		<table align="center" bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0" style="font-size:10pt">
		<tr><td colspan="11" style="border:0; background-color:black; color:white; font-weight:bold"></td></tr>
		<tr bgcolor="#CCCCCC"><th width="200px">Item Description</th><th width="100px">Sold Qty</th></tr>
		<?php
		if(($customer!='')||($store1!='')){
			for($j=0;$j<sizeof($item_des);$j++){
				print '<tr><td style="padding-left:30px">'.$item_des[$j].'</td><td align="right" style="padding-right:30px">'.$item_qty[$j].'</td></tr>';
			}
		}
		?>
		</table>
		<hr />
		<table align="center" bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0" style="font-size:10pt;">
		<tr><td colspan="11" style="border:0; background-color:black; color:white; font-weight:bold"></td></tr>
		<tr bgcolor="#CCCCCC"><th width="200px">Item Category</th><th width="100px">Sold/Total Items<br>In the category <?php print $customer_id; ?></th></tr>
		<?php
		if($selection=='store') $cus_st='store1='.$store1; else $cus_st='customer='.$customer.'&customer_id='.$customer_id;
		if(($customer!='')||($store1!='')){
			for($i=0;$i<sizeof($cat2_id);$i++){
				if($cat2_sold_total[$i]!=0){ 
					$url='href="index.php?components='.$_GET['components'].'&action=sales_report3&selection='.$selection.'&category='.$cat2_id[$i].'&'.$cus_st.'&datefrom='.$fromdate.'&dateto='.$todate.'"'; 
					$color2='blue';
				}else{ 
					$url=''; 
					$color2='black';
				}
				print '<tr><td style="padding-left:30px"><a style="text-decoration:none; color:'.$color2.'" '.$url.'>'.$cat2_name[$i].'</a></td><td align="right" style="padding-right:30px">'.$cat2_sold_total[$i].' / '.$cat2_total_count[$i].'</td></tr>';
			}
		}
		?>
		</table>

  </div>
</div>
</div>
<hr>
<br />
<?php
                include_once  'template/m_footer.php';
?>
