<?php
                include_once  'template/m_header.php';
?>

<!-- ------------------------------------------------------------------------------------ -->
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
<?php
	include_once  'components/manager/view/tpl/cust_group.php';
?>
  </div>
</div>
</div>
<hr>


<?php
                include_once  'template/m_footer.php';
?>