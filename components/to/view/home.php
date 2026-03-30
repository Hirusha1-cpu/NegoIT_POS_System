<?php
                include_once  'template/header.php';
?>
<script type="text/javascript">


</script>
<!-- -------------------------------------------------------------------------------------------------------------------- -->
<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px" /></div>
<table align="center" style="font-size:12pt"><tr><td>
<?php 
	if(isset($_REQUEST['message'])){
		if($_REQUEST['re']=='success') $color='green'; else $color='#DD3333';
		print '<script type="text/javascript">document.getElementById("notifications").innerHTML='."'".'<span style="color:'.$color.'; font-weight:bold;font-size:12pt;">'.$_REQUEST['message'].'</span>'."'".';</script>';
	}
?></td></tr></table>

<table align="center">
	<tr>
		<td valign="top"><?php print($test); ?> </td>
	</tr>
</table>

<?php
                include_once  'template/footer.php';
?>