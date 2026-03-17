<?php
                include_once  'template/m_header.php';
?>
<!-- ------------------------------------------------------------------------------------ -->

<!-- ------------------Item List----------------------- -->
<div class="w3-container" style="margin-top:75px">
<hr>
<div class="w3-row">
  <div class="w3-col s3">
  </div>
  <div class="w3-col">
<table align="center" cellspacing="0" style="font-size:12pt" ><tr><td>
<?php
if(isset($_REQUEST['message'])){
	if($_REQUEST['re']=='success') $color0='green'; else $color0='red';
print '<span style="color:'.$color0.'; font-weight:bold;">'.$_REQUEST['message'].'</span><br /><br />'; 
}
?>
</td></tr></table>
<?php           
                include_once  'components/inventory/view/tpl/unic_items.php';
?>

  </div>
</div>
</div>
<hr>
<br />
<?php
                include_once  'template/m_footer.php';
?>
