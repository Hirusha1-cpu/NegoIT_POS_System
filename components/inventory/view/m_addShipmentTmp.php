<?php
  include_once  'template/m_header.php';
?>
<!-------------------------------------------------------->

<!-- ------------------Item List----------------------- -->
<div class="w3-container" style="margin-top:75px">
  <hr>
  <div class="w3-row">
      <div class="w3-col s3">
      </div>
        <div class="w3-col">
          <?php           
              include_once  'components/inventory/view/tpl/addShipmentTmp.php';
          ?>
      </div>
  </div>
</div>

<hr>
<br />
<?php
  include_once  'template/m_footer.php';
?>
