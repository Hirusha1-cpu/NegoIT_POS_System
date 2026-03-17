<?php
    include_once  'components/orderProcess/modle/orderProcessModule.php';
	generateAddressTag();

?>
<div id="print_address" style="display:none">
################################################
FROM
<?php print  $from_name; ?>

<?php print  str_replace("<br />","<br>",$from_address); ?>

<?php print  $from_mob; ?><br>
------------------------------------------------<br>
TO
<?php print  $to_name; ?>

<?php print  str_replace("<br />","<br>",$to_address); ?><br><?php print  $to_mob; ?><br>
################################################

<br>
<CUT>
</div>