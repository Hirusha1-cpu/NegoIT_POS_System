<?php
                include_once  'template/header.php';
?>
	<link rel="stylesheet" href="plugin/jquery/css/demos.css" />
<table align="center"><tr><td valign="top">
<?php
                include_once  'components/inventory/view/tpl/addQty_form.php';
?>
</td><td width="50px"></td><td style="vertical-align:top">
<!-- ------------------Item List----------------------- -->
<?php
                include_once  'components/inventory/view/tpl/addQty_list.php';
?>
</td></tr></table>
<?php           
                include_once  'template/footer.php';
?>