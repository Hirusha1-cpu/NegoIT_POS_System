<?php
include_once  'template/m_header.php';
?>
<!-- ------------------------------------------------------------------------------------ -->
</head>

<div class="w3-container" style="margin-top:75px">
	<div id="notifications"></div>
			<?php
			if (isset($_REQUEST['message'])){
				if ($_REQUEST['re'] == 'success') $color = 'green';	else $color = '#DD3333';
				if(strpos($_REQUEST['message'],'|')==false){
					$message='<span style="color:'.$color.'; font-weight:bold;font-size:12pt;">'.$_REQUEST['message'].'</span>';
				}else{
					$messages=explode("|",$_REQUEST['message']);
					$message='<span style="color:green; font-weight:bold;font-size:12pt;">'.$messages[0].'</span> | <span style="color:#DD3333; font-weight:bold;font-size:12pt;">'.$messages[1].'</span>';
				}
				print '<script type="text/javascript">document.getElementById("notifications").innerHTML=\''.$message.'\'</script>';
			}
			?>
	<hr>
	<div class="w3-row">
		<div class="w3-col s3">
		</div>
		<div class="w3-col">
			<?php
			if ($_GET['action'] == 'wholesale_cust')	include_once  'components/billing/view/tpl/cust_create.php';
			if ($_GET['action'] == 'onetime_cust')	include_once  'components/billing/view/tpl/cust_create.php';
			if ($_GET['action'] == 'cust_details')	include_once  'components/billing/view/tpl/cust_details.php';
			?>
		</div>
	</div>
</div>
<hr>


<?php
include_once  'template/m_footer.php';
?>