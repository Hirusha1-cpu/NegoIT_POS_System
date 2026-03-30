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
		var availableTags1 = [<?php for ($x=0;$x<sizeof($rep_itm_name);$x++){ print '"'.ucfirst($rep_itm_name[$x]).'",'; } ?>	];
		$( "#part" ).autocomplete({
			source: availableTags1
		});
	});
	</script>
<table width="100%">
<tr><td align="center"><?php 
	if(isset($_REQUEST['message'])){
		if($_REQUEST['re']=='success') $color='green'; else $color='red';
	print '<span style="color:'.$color.'; font-weight:bold;font-size:12pt;">'.$_REQUEST['message'].'</span>'; 
	}
	?></td></tr>
</table>
<?php           
                include_once  'components/inventory/view/tpl/repairPartMap.php';
?>
<?php
                include_once  'template/footer.php';
?>