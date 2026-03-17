<?php
                include_once  'template/m_header.php';
?>
<!-- ------------------------------------------------------------------------------------ -->
<script type="text/javascript">
	
	

</script>
<!-- ------------------Item List----------------------- -->
<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px" /></div>
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
		<table align="center">
			<tr>
				<td valign="top"><?php print($test); ?> </td>
			</tr>
		</table>
	</div>	
  </div>
</div>
</div>
<hr>
<br />

<script type="text/javascript">
	billLocation();
</script>

<?php
                include_once  'template/m_footer.php';
?>
