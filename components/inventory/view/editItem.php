<?php
    include_once  'template/header.php';
?>
<link rel="stylesheet" href="plugin/jquery/css/demos.css" />
	<div style="font-size:12pt">
		<table align="center">
			<tr>
				<td valign="top">
					<?php    
					$tb_width='50px'; 
					include_once  'components/inventory/view/tpl/editItem.php';
					print '<td width="50px"></td><td valign="top">';
						if($_REQUEST['action']=='show_one_item'){
							if(($systemid!=1)&&($systemid!=4)) include_once  'components/inventory/view/tpl/tags2.php';
						}
					?>
				</td>
			</tr>
		</table>
	</div>
<?php           
	include_once  'template/footer.php';
?>