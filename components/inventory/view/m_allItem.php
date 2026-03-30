<?php
                include_once  'template/m_header.php';
				$type=$_GET['type'];
?>
<!-- ------------------------------------------------------------------------------------ -->

<!-- ------------------Item List----------------------- -->
<div class="w3-container" style="margin-top:75px">
<hr>
<div class="w3-row">
  <div class="w3-col s3">
  </div>
  <div class="w3-col" style="font-size:x-small">

<?php           
                include_once  'components/inventory/view/tpl/allItem.php';
?>

  </div>
</div>
</div>
<hr>
<br />
<?php
                include_once  'template/m_footer.php';
?>
