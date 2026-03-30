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
	<table><tr><td style="vertical-align:top;">
	<?php
                include_once  'components/inventory/view/tpl/addUnic_form.php';
	?>
	</td><td width="10px"></td><td style="vertical-align:top;">
	<div id="landscape" style="vertical-align:top" ></div>
	</td></tr></table>
  </div>
</div>
<hr>
	<div class="w3-row">
	  <div class="w3-col s3">
	  </div>
	  <div class="w3-col" style="vertical-align:top">
		<div id="portrait">
		<?php
                include_once  'components/inventory/view/tpl/addUnic_list.php';
		?>
	  </div>
	</div>
</div>
<hr />
<div class="w3-row">
  <div class="w3-col s3"></div>
  <div class="w3-col " align="center">
  </div>
</div>
<hr>
</div>
<?php
                include_once  'template/m_footer.php';
?>
