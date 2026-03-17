<?php
    include_once  'template/m_header.php';
?>

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

<div class="w3-container" style="margin-top:75px; margin-bottom:75px;">
    <div class="w3-row">
        <div class="w3-col s3"></div>
        <div class="w3-col">

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
                <p><center>Total Tax : <?php print $total_tax; ?></center></p>
            </div>
        </div>
</div>
<?php
    include_once  'template/m_footer.php';
?>