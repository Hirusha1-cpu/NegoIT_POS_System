<?php
                include_once  'template/header.php';
?>
	<link rel="stylesheet" href="css/billing.css" />
<table align="center"><tr><td style="vertical-align:top">
<?php
                include_once  'components/inventory/view/tpl/drawerSH_form.php';
?>
</td><td width="50px"></td><td style="vertical-align:top">
<!-- ------------------Item List----------------------- -->
<?php
                include_once  'components/inventory/view/tpl/drawerSH_list.php';
?>
<br />
<table align="center"><tr><td align="center">
<div style="background-color:#6699FF; border:medium; border-color:black; width:80px;">
	<a class="shortcut-button" onclick="printdiv('print','printheader')" href="#"><span style="text-decoration:none; font-family:Arial; color:navy;">
	<img src="images/print.png" alt="icon" /><br />
	Print
	</span></a>
</div>
</td></tr></table>

</td></tr></table>
<?php           
                include_once  'template/footer.php';
?>