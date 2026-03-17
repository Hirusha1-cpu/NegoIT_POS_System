<?php
    if(isset($_REQUEST['q'])) {
        $q = $_REQUEST['q'];
    }
    for ($i=0; $i < $q; $i++) {
        print '<svg id="code'.$i.'"></svg>';
    }
?>
<script src="js/JsBarcode.all.min.js"></script>
<script>
    <?php
        $out=true;
        if(isset($_REQUEST['code']) && ($_REQUEST['code'] != '')) {
            $code = $_REQUEST['code'];
        }else{
            $out = false;
            $message = "Code cannot be null";
        }
        if(isset($_REQUEST['q']) && ($_REQUEST['q'] != '') && ($_REQUEST['q'] > 0)) {
            $q = intval($_REQUEST['q']);
        }else{
            $out = false;
            $message = "Barcode quantity cannot be null or less than 1 or it has to be a number";
        }
        if(isset($_REQUEST['w'])  && ($_REQUEST['w'] != '')) {
            $width = intval($_REQUEST['w']);
        }else{
            $width = 2;
        }
        if(isset($_REQUEST['h'])  && ($_REQUEST['h'] != '')) {
            $height = intval($_REQUEST['h']);
        }else{
            $height = 100;
        }
        if((isset($_REQUEST['color'])) && ($_REQUEST['color'] != '')) {
            $color = $_REQUEST['color'];
        }else{
            $color = '#000000';
        }
        if((isset($_REQUEST['print_val'])) && ($_REQUEST['print_val'] != '')) {
            $print_val = $_REQUEST['print_val'];
        }else{
            $print_val = 'true';
        }
        if((isset($_REQUEST['ml'])) && ($_REQUEST['ml'] != '')) {
            $margin_left = intval($_REQUEST['ml']);
        }else{
            $margin_left = 0;
        }
        if((isset($_REQUEST['mr'])) && ($_REQUEST['mr'] != '')) {
            $margin_right = intval($_REQUEST['mr']);
        }else{
            $margin_right = 0;
        }
        if((isset($_REQUEST['mt'])) && ($_REQUEST['mt'] != '')) {
            $margin_top =intval($_REQUEST['mt']);
        }else{
            $margin_top = 0;
        }
        if((isset($_REQUEST['mb'])) && ($_REQUEST['mb'] != '')) {
            $margin_bottom = intval($_REQUEST['mb']);
        }else{
            $margin_bottom = 0;
        }
        if((isset($_REQUEST['fs'])) && ($_REQUEST['fs'] != '')) {
            $font_size = intval($_REQUEST['fs']);
        }else{
            $font_size = 20;
        }
        if($out){
            for ($i=0; $i < $q; $i++) {
                print 'JsBarcode("#code'.$i.'", "'.$code.'",{
                    width: "'.$width.'",
                    height: "'.$height.'",
                    fontSize: '.$font_size.',
                    lineColor: "'.$color.'",
                    displayValue: "'.$print_val.'",
                    marginLeft: '.$margin_left.',
                    marginRight: '.$margin_right.',
                    marginTop: '.$margin_top.',
                    marginBottom: '.$margin_bottom.',
                  });';
            }
        }
    ?>
</script>
<?php
    if(!$out){
        print '<center><span style="color:red;"><b>'.$message.'</b></span></center>';
    }
?>
