<?php
    include_once  'template/header.php';
?>
<link rel="stylesheet" href="plugin/jquery/css/demos.css" />
<table align="center"><tr><td>
<?php
    include_once  'components/inventory/view/tpl/addUnic_form_Tmp.php';
?>
</td><td width="50px"></td><td style="vertical-align:top">
<!-- ------------------Item List----------------------- -->
<?php
    include_once  'components/inventory/view/tpl/addUnic_list_Tmp.php';
?>
</td></tr></table>
<?php           
    include_once  'template/footer.php';
?>