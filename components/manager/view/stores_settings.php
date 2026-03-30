<?php
    include_once 'template/header.php';
    $components=$_GET['components'];
?>

<table align="center" style="font-size:11pt">
	<tr>
		<td>
			<?php
				if(isset($_REQUEST['message'])){
					if($_REQUEST['re']=='success') $color='green'; else $color='red';
				print '<span style="color:'.$color.'; font-weight:bold;">'.$_REQUEST['message'].'</span><br /><br />';
				}
			?>
		</td>
	</tr>
</table>

<table align="center" bgcolor="#E5E5E5" height="100%" border="1" cellspacing="0" style="font-family:Calibri">
    <tr>
        <td colspan="8" style="border:0; background-color:black; color:white; font-weight:bold">Billing Price Undervalue</td>
    </tr>
    <tr>
        <th width="60px">#</th>
        <th width="150px">Store Name</th>
        <th width="100px">Sub System</th>
        <th width="150px">Price Undervalue</th>
        <th width="100px">Action</th>
    </tr>
    <?php
    for ($i = 0; $i < sizeof($store_settings_store_id); $i++) {
        if($store_setting_store_p_u[$i] == 1){
            $status = 'Active';
            $button_text = 'Deactivate';
        }else{
            $status = 'De-active';
            $button_text = 'Activate';
        }
        print '<tr>
                <td align="center">
                    ' . sprintf('%02d', ($i + 1)) . '
                </td>
                <td align="left" class="shipmentTB3">
                    ' . $store_settings_store_name[$i] . '
                </td>
                <td align="center" width="50px" class="shipmentTB3">' . $store_settings_store_sub_system[$i] . '</td>
                <td align="center" class="shipmentTB3">' . $status . '</td>
                <td align="center"  class="shipmentTB3">
                    <form method="POST" action="index.php?components=' . $components . '&action=update_store_billing_p_u_setting">
                        <input type="hidden" name="store_id" value="' . $store_settings_store_id[$i] . '">
                        <button type="submit">'.$button_text.'</button>
                    </form>
                </td>
            </tr>';
    }
    ?>
</table>

<?php
include_once 'template/footer.php';
?>