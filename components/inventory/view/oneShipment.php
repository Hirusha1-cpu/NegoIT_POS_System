<?php
    include_once  'template/header.php';
    $decimal = getDecimalPlaces(1);
?>
<table align="center" cellspacing="0">
    <!-- Notifications -->
    <tr>
        <td colspan="4">
            <?php
                if(isset($_REQUEST['message'])){
                    if($_REQUEST['re']=='success') $color='green'; else $color='red';
                print '<span style="color:'.$color.'; font-weight:bold;">'.$_REQUEST['message'].'</span>';
                }
            ?>
            <br />
        </td>
    </tr>
</table>

<table align="center">
    <tr>
        <td valign="top">
            <?php
                include_once  'components/inventory/view/tpl/oneShipment_header.php';
            ?>
        </td>
        <td width="100px"></td>
        <td valign="top">
            <?php
                include_once  'components/inventory/view/tpl/oneShipment_footer.php';
            ?>
        </td>
    </tr>
</table>
<?php
    include_once  'template/footer.php';
?>