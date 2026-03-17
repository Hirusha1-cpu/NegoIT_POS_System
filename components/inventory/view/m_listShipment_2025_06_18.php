<?php
                include_once  'template/m_header.php';
?>
<table align="center" cellspacing="0"><tr><td>
<?php
if(isset($_REQUEST['message'])){
	if($_REQUEST['re']=='success') $color0='green'; else $color0='red';
print '<span style="color:'.$color0.'; font-weight:bold;">'.$_REQUEST['message'].'</span>'; 
}
?>
</td></tr></table>
<!-- ------------------------------------------------------------------------------------ -->
<div class="w3-container" style="margin-top:75px">
<hr>
<div class="w3-row">
  <div class="w3-col s3">
  </div>
  <div class="w3-col">

<div style="display: flex;margin: auto;overflow: hidden;text-align: center;justify-content: center;align-items: center;padding:20px; font-family:Calibri">
	<span>Month Filter : </span><input type="month" name="month" id="month" value="<?php print $_GET['month']; ?>" onchange="window.location = 'index.php?components=inventory&action=shipmentlist&month='+this.value" style="margin-left: 10px"/>
</div>

<table align="center">
	<tr>
		<td valign="top"><?php include_once  'components/inventory/view/tpl/listShipment_table1.php'; ?></td>
	</tr>
	<tr><td><br /></td></tr>
	<tr>
		<td valign="top"><?php include_once  'components/inventory/view/tpl/listShipment_table2.php'; ?></td>
	</tr>
</table>

  </div>
</div>
</div>
<hr>
<br />
<?php
                include_once  'template/m_footer.php';
?>
