<?php
                include_once  'template/m_header.php';
?>
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
	<table align="center" style="font-size:12pt">
	<tr style="background-color:#C0C0C0"><th style="padding-left:20px; padding-right:20px" width="300px">Item</th><th  style="padding-left:20px; padding-right:20px">Qty</th><th>Move to<br />Inventory</th><th>Move to <br />Disposal</th><th></th></tr>
	<?php
	for($i=0;$i<sizeof($rt_item);$i++){
		if($rt_unic[$i]==0){
			$dis='<td align="center"><input type="text" name="inv'.$rt_item[$i].'" id="inv'.$rt_item[$i].'" value="0" style="width:35px; text-align:right; padding-right:5px;" /></td><td align="center"><input type="text" name="dis'.$rt_item[$i].'" id="dis'.$rt_item[$i].'" value="0" style="width:35px; text-align:right; padding-right:5px;" /></td><td><input type="Button" value="Submit" onclick="processRtn('.$rt_item[$i].')" /></td>';
			$link='';
		}else{
			$dis='<td colspan="3"></td>';
			$link='href="index.php?components='.$components.'&action=list_unic_return&item='.$rt_item[$i].'"';
		}
		print '<tr style="background-color:#F0F0F0"><td style="padding-left:20px; padding-right:20px;"><a '.$link.'>'.$rt_itmdesc[$i].'</a></td><td style="padding-right:20px;" align="right"><input  disabled="disabled" type="text" id="qty'.$rt_item[$i].'" value="'.$rt_qty[$i].'" style="width:35px; text-align:right; padding-right:5px;" /></td>'.$dis.'</tr>';
	}
	?>
	</table>
  </div>
</div>
</div>
<hr>


<?php
                include_once  'template/m_footer.php';
?>