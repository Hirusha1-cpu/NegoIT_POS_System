<?php
                include_once  'template/header.php';
                $action=$_GET['action'];
                $username=$_COOKIE['user'];
                if(isset($_COOKIE['store_name'])) $currentst_name=$_COOKIE['store_name']; else $currentst_name='';
                
?>
	<script type="text/javascript">
	</script>
<?php 
	if(isset($_REQUEST['id'])) $id=$_REQUEST['id']; else $id=0;
?>

<table width="100%">
<tr><td align="center"><?php 
	if(isset($_REQUEST['message'])){
		if($_REQUEST['re']=='success') $color='green'; else $color='red';
	print '<span style="color:'.$color.'; font-weight:bold;font-size:12pt;">'.$_REQUEST['message'].'</span>'; 
	}
	?></td></tr>
</table>

<form action="index.php?components=order_process&action=search_order" id="filterForm" method="post" style="font-size:12pt" >
<table align="center">
	<tr><td><input type="number" id="order_no" name="order_no" placeholder="Order BARCODE" style="width:300px; height:50px; font-size:16pt; text-align:center" /></td></tr>
</table>
</form>
<br /><br />	
<form action="index.php?components=order_process&action=report_trackingid" id="form2" target="_blank" method="post" style="font-size:12pt; font-family:'Courier New', Courier, monospace" >
<table align="center">
	<tr><td align="center" colspan="2">Generate Tracking Report</td></tr>
	<tr><td align="center"><input type="date" id="rep_date" name="rep_date" value="<?php print dateNow(); ?>" /></td><td><a onclick="document.getElementById('form2').submit();" style="cursor:pointer"><img src="images/search.png" style="width:30px; vertical-align:middle" /></a></td></tr>
</table>
</form>

<script type="text/javascript">
	document.getElementById("order_no").focus();
</script>
<?php
                include_once  'template/footer.php';
?>