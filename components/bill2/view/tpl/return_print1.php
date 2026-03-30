<?php
include_once '../../modle/bill2Module.php';
include_once '../../../../template/common.php';
generateRtnInvoice();
$paper_size = paper_size(2);
$page_height = 760; // default A4
if ($paper_size == 'A5') {
    $page_height = 430;
}

// Safely initialize variables to prevent null errors
$bill_id = $bill_id ?? [];
$bill_item = $bill_item ?? [];
$bill_qty = $bill_qty ?? [];
$extra_pay = $extra_pay ?? [];
$bill_date = $bill_date ?? '';
$bill_salesman = $bill_salesman ?? '';
$bill_cust = $bill_cust ?? '';
$tm_company = $tm_company ?? '';
$tm_address = $tm_address ?? '';
$tm_tel = $tm_tel ?? '';
?>

<table width="100%">
    <tr>
        <td rowspan="2" style="font-family:Arial; font-size:11pt">
            <strong><?php print $tm_company; ?></strong><br />
            <?php print $tm_address; ?><br />
            Tel: <?php print $tm_tel; ?>
        </td>
        <td align="right"><span style="font-family:'Arial Black'; font-size:20pt">RETURN<br>INVOICE</span><br /><br /></td>
    </tr>
    <tr>
        <td align="right">
            INVOICE # [<?php print str_pad($_REQUEST['id'] ?? '', 7, "0", STR_PAD_LEFT); ?> ]<br />
            <span style="font-family:Arial; font-size:11pt">
                DATE: <?php print $bill_date; ?><br />
                ISSUED BY: <?php print ucfirst($bill_salesman); ?>
                <br /><br />
            </span>
        </td>
    </tr>
</table>

<table align="center" height="<?php print $page_height; ?>px" width="100%" border="1" cellspacing="0">
    <tr style="font-family:Arial; font-size:10pt">
        <th height="20px">ITEM</th>
        <th width="80px">QTY</th>
        <th width="80px">EXTRA PAY</th>
    </tr>

    <?php if (is_array($bill_id) && count($bill_id) > 0): ?>
        <?php for ($i = 0; $i < count($bill_id); $i++): ?>
            <tr style="font-size:10pt" height="30px">
                <td align="left" style="border-bottom:0; border-top:0; padding-left:20px;">
                    <?php print $bill_item[$i] ?? ''; ?>
                </td>
                <td style="border-bottom:0; border-top:0; padding-right:30px" align="right">
                    <?php print $bill_qty[$i] ?? ''; ?>
                </td>
                <td style="border-bottom:0; border-top:0; padding-right:10px" align="right">
                    <?php print isset($extra_pay[$i]) ? number_format($extra_pay[$i]) : ''; ?>
                </td>
            </tr>
            <tr>
                <td height="10px" style="border-bottom:0; border-top:0;"></td>
                <td style="border-bottom:0; border-top:0;"></td>
                <td style="border-bottom:0; border-top:0;"></td>
            </tr>
        <?php endfor; ?>
    <?php else: ?>
        <tr>
            <td colspan="3" align="center" style="padding:20px;">No items found</td>
        </tr>
    <?php endif; ?>

    <tr>
        <td style="border-bottom:0; border-top:0;"></td>
        <td style="border-bottom:0; border-top:0;"></td>
        <td style="border-bottom:0; border-top:0;"></td>
    </tr>
    <tr>
        <td colspan="2" height="10px" style="padding-left:20px">Total Extra Pay</td>
        <td align="right" style="padding-right:10px">
            <?php print is_array($extra_pay) ? number_format(array_sum($extra_pay)) : '0.00'; ?>
        </td>
    </tr>
</table>

<br />

<table align="center" width="100%" border="1" cellspacing="0">
    <tr style="font-size:8pt;">
        <td>
            <table align="center" width="100%">
                <tr>
                    <td width="65px" style="font-family:Arial; font-size:9pt">Customer :</td>
                    <td style="font-family:Arial; font-size:9pt"><?php print ucfirst($bill_cust); ?></td>
                    <td width="80px" style="font-family:Arial; font-size:9pt">Name</td>
                    <td width="130px">..............................</td>
                </tr>
                <tr>
                    <td style="font-family:Arial; font-size:9pt"></td>
                    <td></td>
                    <td style="font-family:Arial; font-size:9pt">Signature</td>
                    <td>..............................</td>
                </tr>
                <tr>
                    <td colspan="5">
                        <p style="font-style:italic; font-size:8pt"><br />Note: By Signing this, Customer confirms that he/she received replacement items for above listed</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>