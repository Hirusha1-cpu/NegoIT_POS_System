<?php
                include_once  'template/header.php';
				$type=$_GET['type'];
?>
<script type="text/javascript">
	function updateRepairInv($id){
		$item_cost=document.getElementById('cost'+$id).value; 
		$item_qty=document.getElementById('qty'+$id).value; 
		$reorder_level=document.getElementById('relvl'+$id).value; 
		$reorder_qty=document.getElementById('reqty'+$id).value; 
		  
		  document.getElementById('partaction|'+$id).innerHTML=document.getElementById("loading").innerHTML;
		  var xhttp = new XMLHttpRequest();
		  xhttp.onreadystatechange = function() {
		    if (this.readyState == 4 && this.status == 200) {
		    var returntext=this.responseText;
		    if(returntext=='Done'){
		    	document.getElementById('partaction|'+$id).innerHTML='<span style="color:green;">'+returntext+'</span>';
		    	document.getElementById('inv_rtotal1').innerHTML='<span style="color:red;">Please Refresh the page for the TOTAL</span>';
		    	document.getElementById('inv_rtotal2').innerHTML='';
		    }else{
		    	document.getElementById('partaction|'+$id).innerHTML='<span style="color:red;">'+returntext+'</span>';
		    }
		    }
		  };
		  xhttp.open("GET", 'index.php?components=inventory&action=update_repair_inv&id='+$id+'&cost='+$item_cost+'&qty='+$item_qty+'&reorder_level='+$reorder_level+'&reorder_qty='+$reorder_qty, true);
		  xhttp.send();
	}
</script>
<?php if($type==4) { ?>
	<link rel="stylesheet" href="plugin/jquery/css/jquery.ui.all.css" />
	<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.core.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.widget.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.position.js"></script>
	<script src="plugin/jquery/ui/jquery.ui.autocomplete.js"></script>
	<link rel="stylesheet" href="plugin/jquery/css/demos.css" />
	<script type="text/javascript">
	$(function() {
		var availableTags1 = [<?php for ($x=0;$x<sizeof($part_name);$x++){ print '"'.ucfirst($part_name[$x]).'",'; } ?>	];
		$( "#part" ).autocomplete({
			source: availableTags1
		});
	});
	</script>
<?php } ?>
<div id="loading" style="display:none"><img src="images/loading.gif" style="width:30px" /></div>
<table width="100%">
<tr><td align="center"><?php 
	if(isset($_REQUEST['message'])){
		if($_REQUEST['re']=='success') $color='green'; else $color='red';
	print '<span style="color:'.$color.'; font-weight:bold;font-size:12pt;">'.$_REQUEST['message'].'</span>'; 
	}
	?></td></tr>
</table>
<?php           
                include_once  'components/inventory/view/tpl/allItem.php';
?>
<table align="center" style="font-size:8pt"><tr><td align="center">
<?php if(($_GET['type']==4)&&(repairPartReorder())){ ?>
<div style="background-color:#6699FF; border:medium; border-color:black; width:80px;">
	<a class="shortcut-button" target="_blank" href="index.php?components=inventory&action=reorder_repairparts" style="text-decoration:none;">
	<img src="images/po.png" alt="icon" /><br />
	Generate PO
	</span></a>
</div>
<?php } ?>
</td><td align="center">
<div style="background-color:#6699FF; border:medium; border-color:black; width:80px;">
	<a class="shortcut-button" onclick="printdiv('print','printheader')" href="#"><span style="text-decoration:none; font-family:Arial; color:navy;">
	<img src="images/print.png" alt="icon" /><br />
	Print
	</span></a>
</div>
</td><td align="center">
<div style="background-color:#6699FF; border:medium; border-color:black; width:90px;">
	<?php print '<a class="shortcut-button" style="cursor:pointer" onclick="setFilter(\'export_unic_list\')" ><span style="text-decoration:none; font-family:Arial; color:navy;">'; ?>
	<img src="images/excel.jpg" style="width:50px" alt="icon" /><br />
	Export Unique List
	</span></a>
</div>
</td></tr></table>
<?php
                include_once  'template/footer.php';
?>