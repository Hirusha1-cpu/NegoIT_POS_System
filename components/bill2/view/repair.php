<?php
                include_once  'template/header.php';
?>
	<link rel="stylesheet" href="plugin/jquery/css/jquery.ui.all.css" />
	<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.core.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.widget.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.position.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.autocomplete.js"></script>
	<link rel="stylesheet" href="plugin/jquery/css/demos.css" />
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
	});
	
	function getPrice(){
		var itemid = [<?php for ($x=0;$x<sizeof($id);$x++){ print '"'.$id[$x].'",'; } ?>	];
		var code = [<?php for ($x=0;$x<sizeof($code);$x++){ print '"'.$code[$x].'",'; } ?>	];		
		var description = [<?php for ($x=0;$x<sizeof($description);$x++){ print '"'.$description[$x].'",'; } ?>	];
		var qty = [<?php for ($x=0;$x<sizeof($qty);$x++){ print '"'.$qty[$x].'",'; } ?>	];		
		var itemcode=document.getElementById('tags1').value;
		var itemdesc=document.getElementById('tags2').value;
		
		if(itemcode!=''){
			var a=code.indexOf(itemcode);
			document.getElementById('itemid').value=itemid[a];
			document.getElementById('tags2').value=description[a];			
			document.getElementById('av_qty').innerHTML=qty[a];
		}else if(itemdesc!=''){
			var a=description.indexOf(itemdesc);
			document.getElementById('itemid').value=itemid[a];			
			document.getElementById('tags1').value=code[a];
		}

		
	}
	</script>

<?php 
	if(isset($_REQUEST['id'])) $id=$_REQUEST['id']; else $id=0;
?>

<form action="index.php?components=bill2&action=apend_bill2" method="post" >
<input type="hidden" name="id" value="<?php print $id; ?>" />
<table align="center">
<tr><td>
<h1 style="color:green">Repair Billing</h1>
<table align="center" bgcolor="#E5E5E5">
<tr><td colspan="5"><?php 
if(isset($_REQUEST['message'])){
	if($_REQUEST['re']=='success') $color='green'; else $color='red';
print '<span style="color:'.$color.'; font-weight:bold;font-size:12pt;">'.$_REQUEST['message'].'</span>'; 
}
?><br /></td></tr>
<tr><td width="50px"></td><td style="font-size:12pt">Item Code</td><td colspan="2"><input type="text" name="code" id="tags1" /></td><td width="50px"></td></tr>
<tr><td width="50px"></td><td style="font-size:12pt">Item Description</td><td colspan="2"><input type="text" name="description" id="tags2" /></td><td width="50px"></td></tr>
<tr><td></td><td style="font-size:12pt">Quantity</td><td><input type="text" name="qty" id="qty" onfocus="getPrice()" style="width:50px" /></td><td><div style="font-size:12pt" id="av_qty" align="right"></div></td><td></td></tr>
<tr><td></td><td style="font-size:12pt">Repair Price</td><td colspan="2">
<input type="text" name="price" id="price" />
<input type="hidden" name="itemid" id="itemid" />
<input type="hidden" name="type" id="type" value="2" />
</td><td></td></tr>
<tr><td></td><td style="font-size:12pt">Description</td><td colspan="2"><input type="text" name="comment" id="comment" /></td><td></td><td></td></tr>
<tr><td colspan="5" align="center"><br /><input type="submit" value="Add to Bill" style="width:100px; height:30px" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
<input type="Button" value="Finalyze" style="width:100px; height:70px" onclick="window.location = 'index.php?components=bill2&action=finish_bill&id=<?php if(isset($_GET['id'])) print $_GET['id']; ?>'" />
<br /><br /></td></tr>

</table>
</td><td width="50px"></td><td>
<!-- ------------------Item List----------------------- -->
	<table align="center" bgcolor="#E5E5E5" height="100%">
<?php
	for($i=0;$i<sizeof($bill_id);$i++){
		print '<tr style="font-size:12pt"><td>'.$bi_desc[$i].'</td><td width="50px"></td><td align="right">'.$bi_qty[$i].'</td></tr>';
	}
		print '<tr style="font-size:12pt; font-weight:900;"><td>Total Amount</td><td width="50px"></td><td align="right">'.number_format($total).'</td></tr>';	
?>	
	</table>

</td></tr>
</table>
</form>

<?php
                include_once  'template/footer.php';
?>