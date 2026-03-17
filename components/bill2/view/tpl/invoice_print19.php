<?php
include_once '../../modle/bill2Module.php';
include_once '../../../../template/common.php';
generateInvoice('itq.drawer_no, bi.id');
$paper_size = paper_size(2);
if ($paper_size == 'A4') {
  $page_height = 820;
  if ($chq0_date != '')
    $chequedate = '[Cheque Date: ' . $chq0_date . ' ]&nbsp;&nbsp;&nbsp;&nbsp;';
  else
    $chequedate = '';
}
if ($paper_size == 'A5') {
  $page_height = 520;
  $chequedate = '';
}
if ($bi_type == 1 || $bi_type == 2) {
  $bill_title = 'GTN';
  $sub_title = 'GTN';
  $advance = '';
} else if ($bi_type == 3) {
  $bill_title = 'GTN';
  $sub_title = 'GTN';
  $advance = 'Advance';
} else if ($bi_type == 4 || $bi_type == 5) {
  if ($bm_status < 3) {
    $bill_title = 'GTN';
    $sub_title = 'ORDER NO';
    $advance = 'Advance';
  } else {
    $bill_title = 'GTN';
    $sub_title = 'GTN';
    $advance = '';
  }
}
$dn = $_GET['dn'];
if ($dn == 'yes') {
  $bill_title = 'GTN';
  $sub_title = 'GTN';
  $page_height -= 110;
}
$systemid = inf_systemid(2);
$decimal = getDecimalPlaces(2);
?>

<div id="print_top"></div>
<table width="100%">
  <tr>
    <td rowspan="2" style="font-family:Arial; font-size:11pt">
      <strong><?php print $tm_company; ?></strong><br />
      <?php print $tm_address; ?><br />
      Tel: <?php print $tm_tel; ?>
    </td>
    <td></td>
    <td align="right"><span
        style="font-family:'Arial Black'; font-size:18pt"><?php print $bill_title; ?></span><br /><span
        style="font-size:12pt; font-family:Arial"><?php if ($bi_type == 2 || $bi_type == 5)
          print '<strong>Service Invoice</strong>';
        else if ($bi_type == 3)
          print '<strong>Repair Invoice</strong>'; ?></span><br />
    </td>
  </tr>
  <tr>
    <td></td>
    <td align="right">
      <?php print $sub_title; ?> # [<?php print str_pad($_GET['id'], 7, "0", STR_PAD_LEFT); ?>]<br />
      <span style="font-family:Arial; font-size:11pt">
        TIME: <?php print substr($bi_time, 0, 5); ?> &nbsp;&nbsp;&nbsp;&nbsp;DATE:
        <?php print $bi_date; ?><br /><br />
      </span>
    </td>
  </tr>
</table>

<table align="center" height="<?php print $page_height; ?>px" width="100%" border="1" cellspacing="0" border="1">
  <tr style="font-family:Arial; font-size:10pt">
    <th width="40px" height="20px">Code</th>
    <th>DESCRIPTION</th>
    <th width="40px">QTY</th>
  </tr>
  <?php
  for ($i = 0; $i < sizeof($bill_id); $i++) {
    if ($bill_cross_tr[$i] > 0)
      $color1 = "#CCCCCC";
    else
      $color1 = "auto";
    if ($bi_return_odr[$i] == 0) {
      print '<tr style="font-size:10pt; background-color:' . $color1 . '" height="20px">
						<td align="center" style="border-bottom:0; border-top:0;">' . $bi_drawer[$i] . '</td>
						<td style="border-bottom:0; border-top:0; padding: 0 10px;">' . $bi_desc[$i] . '</td>
						<td align="right" style="border-bottom:0; border-top:0; padding: 0 10px;">' . number_format($bi_qty[$i]) . '</td>
					</tr>';
    }
  }
  print '<tr style="font-size:10pt">
				<td style="border-bottom:0; border-top:0;"></td>
				<td style="border-bottom:0; border-top:0;"></td>
				<td style="border-bottom:0; border-top:0;"></td>
      </tr>';
  ?>
</table>
<table align="center" width="100%" border="1" cellspacing="0">
  <tr style="font-size:8pt;">
    <td>
      <table align="center" width="100%">
        <tr>
          <td width="65px" style="font-family:Arial; font-size:9pt">Salesman : </td>
          <td style="font-family:Arial; font-size:9pt"> <?php print ucfirst($up_salesman); ?></td>
          <td></td>
          <td width="80px" style="font-family:Arial; font-size:9pt">Name</td>
          <td width="130px"> ..............................</td>
        </tr>
        <tr>
          <td style="font-family:Arial; font-size:9pt" colspan="2">
            <?php print '<a href="../../../../index.php?components=bill2&action=cust_details&id=' . $cu_id . '&action2=finish_bill&id2=' . $_GET['id'] . '" target="_parent" title="' . $cu_details . '" style="text-decoration:none" >' . ucfirst($bi_cust) . '</a>'; ?>
          </td>
          <td></td>
          <td style="font-family:Arial; font-size:9pt">Signature</td>
          <td> ..............................</td>
        </tr>
      </table>
    </td>
  </tr>
</table>