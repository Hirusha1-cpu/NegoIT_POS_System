<?php
                include_once  'template/m_header.php';
                if(isset($_COOKIE['manager']) || isset($_COOKIE['top manager']) || isset($_COOKIE['report'])) $topuser=true; else $topuser=false;
?>
<!-- ------------------------------------------------------------------------------------ -->
<?php 
	if(isset($_REQUEST['id'])) $id=$_REQUEST['id']; else $id=0;
?>
	<style type="text/css">
	.style2 {
		color: navy;
		font-weight: bold;
		background-color:#EEEEEE;
	}
	</style>
</head>

<div class="w3-container" style="margin-top:75px">
<?php 
	if(isset($_REQUEST['message'])){
		if($_REQUEST['re']=='success') $color='green'; else $color='red';
	print '<span style="color:'.$color.'; font-weight:bold;font-size:large;">'.$_REQUEST['message'].'</span>'; 
	}
?>	

<div class="w3-row">
  <div class="w3-col s3">
  </div>
  <div class="w3-col">
  <?php if($topuser){ ?>
		<form action="index.php?components=repair&action=change_st" method="post" >
		<table align="center" style="font-family:Calibri; font-weight:bold;" width="300px"><tr style="background-color:#EEEEEE"><td align="center"><input type="text" id="invoice_no" name="invoice_no" placeholder="Job No" style="width:80px; text-align:center" value="<?php print $invoice_no; ?>" /></td><td width="50px" align="center"><div id="auth_result" style="color:red; font-weight:bold"></div></td><td><input type="submit" value="Search Job" style="width:100px; height:40px" /></td></tr></table>
		</form>

		<br />
		
		<form action="index.php?components=repair&action=update_st" method="post" >
		<input type="hidden" name="invoice_no" value="<?php print $invoice_no; ?>" />
		<?php 	if($cu_status==4 || $cu_status==5 || $cu_status==6 || $cu_status==7){ ?>
			<table align="center" style="font-family:Calibri;">
			<tr style="background-color:#EEEEEE;" ><td width="110px" class="shipmentTB3" style="color:maroon; font-weight:bold">Current Status</td><td width="140px" class="shipmentTB3"><?php print $cu_status_name; ?></td></tr>
			<tr style="background-color:#EEEEEE;" ><td width="110px" class="shipmentTB3" style="color:maroon; font-weight:bold">New Status</td><td width="140px" class="shipmentTB3">
				<select name="new_st" >
					<option value="">-SELECT-</option>
					<option value="3">Picked</option>
				</select>
			</td></tr>
			<tr style="background-color:#EEEEEE;" ><td width="110px" class="shipmentTB3" style="color:maroon; font-weight:bold">Current Technician</td><td width="140px" class="shipmentTB3"><?php print ucfirst($cu_tech); ?></td></tr>
			<tr style="background-color:#EEEEEE;" ><td width="110px" class="shipmentTB3" style="color:maroon; font-weight:bold">New Technician</td><td width="140px" class="shipmentTB3">
				<select name="new_tech" >
					<option value="">-SELECT-</option>
					<?php for($i=0;$i<sizeof($tech_id);$i++){
						print '<option value="'.$tech_id[$i].'">'.ucfirst($tech_name[$i]).'</option>';
					} ?>
				</select>
			</td></tr>
			<tr style="background-color:#EEEEEE;"><td colspan="2" align="center"><input type="submit" value="Update" style="width:100px; height:40px" /></td></tr>
			</table>
		<?php } ?>
		</form>
	<?php } ?>
  </div>
</div>
</div>
<hr>


<?php
                include_once  'template/m_footer.php';
?>