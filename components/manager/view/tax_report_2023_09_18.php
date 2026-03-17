<?php
	include_once  'template/header.php';
    $decimal = getDecimalPlaces(1);
?>
<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
<script>
    function validateForm(){
  		if(validateDateRange()){
  			document.getElementById('div_submit').innerHTML=document.getElementById('loading').innerHTML;
  			return true;
  		}else{
			return false;
  		}
  	}
</script>
<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px" /></div>

<div id="printheader" style="display:none" >
	<h1 style="color:navy"><?php print $inf_company; ?></h1>
	<h2 align="center" style="color:#3333FF; text-decoration:underline">Tax Report</h2>
</div>

<form action="index.php" method="get" onsubmit="return validateForm()" >
	<input type="hidden" name="components" value="<?php print $components; ?>" />
	<input type="hidden" name="action" value="tax_report" />
	<table align="center">
        <tr>
            <td>
                <div style="background-color:#DFDFDF; border-radius:10px; font-family:Calibri">
                    <table align="center" height="100%" cellspacing="0" style="font-size:10pt">
                        <tr>
                            <td width="30px"></td>
                            <td align="right"><strong>From Date : </strong></td>
                            <td>
                                <input type="date" id="from_date" name="from_date" style="width:130px" value="<?php print $from_date; ?>" />
                            </td>
                            <td width="50px"></td>
                            <td align="right"><strong>To Date : </strong></td>
                            <td>
                                <input type="date" id="to_date" name="to_date" style="width:130px" value="<?php print $to_date; ?>" />
                            </td>
                            <td width="50px"></td>
                            <td>
                                <div id="div_submit"><input type="submit" value="GET" style="width:50px; height:40px" /></div>
                            </td>
                            <td width="30px"></td>
                            <td colspan="2" width="200px" align="right">
                                <input style="height:40px" type="button" value="Detail Report" onclick="window.location = 'index.php?components=manager&action=tax_report_detail'" />
                            </td>
                            </tr>
                    </table>
                </div>
	        </td>
        </tr>
    </table>
</form>

<div id="print">
    <p><center>Total Tax : <?php print number_format($total_tax, $decimal); ?></center></p>
</div>

<table align="center">
    <tr>
        <td align="center">
            <div style="background-color:#6699FF; border:medium; border-color:black; width:80px;">
                <a class="shortcut-button" onclick="printdiv('print','printheader')" href="#"><span style="text-decoration:none; font-family:Arial; color:navy;">
                <img src="images/print.png" alt="icon" /><br />
                Print
                </span></a>
            </div>
        </td>
    </tr>
</table>
<?php
    include_once  'template/footer.php';
?>