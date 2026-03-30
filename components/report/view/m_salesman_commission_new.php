<?php
    include_once  'template/m_header.php';
?>
<?php if($_REQUEST['components'] == 'report'){ ?>
<script type="text/javascript">
function setOrder($type){
	$order='';
	if($type=='sm'){
        <?php if(isset($_GET["sm_order"])){ ?>
         $order='';
        <?php }else{  ?>
		if(document.getElementById("sm_order").checked==true){
		 	$order='&sm_order=1';
		}
        <?php } ?>
	}
	window.location = 'index.php?components=<?php print $components; ?>&action=salesman_commission_new'+$order;
}

function setSMCommission($sm_name){
	var $sm_selected=document.getElementById('sm_selected').value;
	var $total_com=parseFloat(document.getElementById('total_com').value);
	var $one_com=parseFloat(document.getElementById('sm_amo_'+$sm_name).value);
	if(document.getElementById('sm_tik_'+$sm_name).checked){ 
		$sm_selected+=$sm_name +','+$one_com+'|'; 
		$total_com+=$one_com;
	}else{
		if($sm_selected!=''){
			$sm_select_arr=($sm_selected.slice(0, -1)).split('|');
			$sm_selected='';
			for($i=0;$i<$sm_select_arr.length;$i++){
				$sm_arr=$sm_select_arr[$i].split(',');
				if($sm_arr[0] != $sm_name)	$sm_selected+=$sm_select_arr[$i]+'|';
			}	
		}
		$total_com-=$one_com;
	}
	document.getElementById('sm_selected').value=$sm_selected;
	document.getElementById('total_com').value=$total_com.toFixed(2);
}

function validateCommissionReport(){
	$month=document.getElementById('month').value;
	$sm_selected=document.getElementById('sm_selected').value;
	$out=true;
	
	if(document.getElementById('sm_selected').value==''){ 
		document.getElementById("div_status").innerHTML='<div class="blink">Please Select Salesmans<br /></div>';
		$out=false;
	}
	
	if($month==''){ 
		document.getElementById("div_status").innerHTML='<div class="blink">Please Select the month</div>';
		$out=false;
	}
	
	if($out){
		document.getElementById("div_status").innerHTML='Okay to Procced';
		document.getElementById("div_gen_btn").innerHTML=document.getElementById("loading").innerHTML;
		return true;
	}else{
		return false;
	}
}
</script>
<?php } ?>
<style>
    #wrapper {
        display: flex;
        flex-direction: column;
    }

    #firstDiv {
    order: 1;
    }

    #secondDiv {
    order: 0;
    }
</style>

<div class="w3-container" style="margin-top:75px">
    <?php 
        if(isset($_REQUEST['message'])){
            if($_REQUEST['re']=='success') $color='green'; else $color='red';
        print '<span style="color:'.$color.'; font-weight:bold;font-size:large;">'.$_REQUEST['message'].'</span>'; 
        }
    ?>
    <hr>
    <div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px" /></div>
    <div class="w3-row">
        <div class="w3-col s3"></div>
        <div class="w3-col">
            <div id="wrapper">
                <div id="firstDiv">
                <table width="100%">
                    <tr style="background-color:#467898; color:white;">
                        <th class="shipmentTB3" rowspan="2">Invoice No</th>
                        <th class="shipmentTB3" rowspan="2">Customer</th>
                        <th class="shipmentTB3" colspan="2">Salesman</th>
                        <th class="shipmentTB3" rowspan="2">Invoice Total</th>
                    </tr>
                    <tr style="background-color:#467898; color:white;">
                        <th class="shipmentTB3">Name<br /><span style="font-size:8pt"><input type="checkbox" id="sm_order" onclick="setOrder('sm')"/> Order</span></th>
                        <th class="shipmentTB3">Commission<br /><span style="font-size:8pt"></span></th>
                    </tr>
                        <?php
                        for($i=0;$i<sizeof($inv_no);$i++){
                            if(strlen($cust[$i])>25) $cust_name=substr($cust[$i],0,25).'...'; else $cust_name=$cust[$i];
                            $sm_commission_arr[$salesman[$i]]+=$sm_commission[$i];
                            if(($i%2)==0) $color='#EEEEEE'; else $color='#F9F9F9';
                            print '<tr bgcolor="'.$color.'">
                                <td class="shipmentTB3" align="center">
                                        <a target="_blank" href="index.php?components=billing&action=finish_bill&id='.$inv_no[$i].'" style="text-decoration:none" >'.str_pad($inv_no[$i], 7, "0", STR_PAD_LEFT).'</a>
                                </td>
                                <td class="shipmentTB3"><a title="'.$cust[$i].'">'.$cust_name.'</a></td>';
                            print '<td class="shipmentTB3">'.ucfirst($salesman[$i]).'</td>
                                <td class="shipmentTB3" align="right">'.number_format($sm_commission[$i]).'</td>';
                            print '<td class="shipmentTB3" align="right">'.number_format($inv_total[$i]).'</td>';
                            print '</tr>';
                        }
                print '</table>
                <br />';
                ?>
                </div>
                
                <div id="secondDiv">
                    <table width="100%">
                        <tr style="background-color:#467898; color:white;">
                            <td></td><th class="shipmentTB3">Salesman</th>
                            <th class="shipmentTB3">Total Commission</th>
                        </tr>
                    <?php
                    $proceed_status=true;
                    for($i=0;$i<sizeof($sm_list);$i++){
                        if($sm_commission_arr[$sm_list[$i]]<0) $proceed_status=false;
                        if(($i%2)==0) $color='#EEEEEE'; else $color='#F9F9F9';
                        print '<tr bgcolor="'.$color.'">
                                <td align="center"><input type="checkbox" id="sm_tik_'.$sm_list[$i].'" onclick="setSMCommission(\''.$sm_list[$i].'\')" /></td>
                                <td class="shipmentTB3">'.ucfirst($sm_list[$i]).'</td>
                                <td class="shipmentTB3" align="right">'.number_format($sm_commission_arr[$sm_list[$i]]).'<input type="hidden" id="sm_amo_'.$sm_list[$i].'" value="'.$sm_commission_arr[$sm_list[$i]].'" /></td>
                            </tr>';
                    }
                    ?>
                    </table>
	                <hr />
                    <form action="index.php?components=<?php print $components; ?>&action=salesman_generate_commission" method="post" onsubmit="return validateCommissionReport()">
                        <input type="hidden" id="sm_selected" name="sm_selected" value="" />
                        <table width="100%">
                            <?php if($_REQUEST['components'] == 'report'){ ?>
                            <tr style="background-color:#467898; color:white;">
                                <th class="shipmentTB3" align="left">Total<br />Commission</th>
                                <th class="shipmentTB3"><input type="text" id="total_com" value="0" readonly="readonly" style="text-align:right" /></th>
                            </tr>
                            <?php } ?>
                            <tr style="background-color:#467898; color:white;">
                                <th class="shipmentTB3" align="left">Month</th>
                                <th class="shipmentTB3"><input type="month" id="month" name="month" value="<?php print date("Y-m",time()); ?>" <?php if($_REQUEST['components'] != 'report') echo 'disabled';  ?>/></th>
                            </tr>
                            <tr style="background-color:#467898; color:white;">
                                <th class="shipmentTB3" align="left">Status</th>
                                <td class="shipmentTB3"><div id="div_status" ><?php if($proceed_status) print 'Okay to Procced'; else print 'Error: Commission<br />should be larger than 0'; ?></div></td>
                            </tr>
                            <?php
                                if(($proceed_status) && ($_REQUEST['components'] == 'report')){ ?>
                                <tr style="background-color:#EEEEEE;"><th class="shipmentTB3" colspan="2"><div id="div_gen_btn"><input type="submit" value="Generate Commission Report" style="width:250px; height:50px;" /></div></th></tr>
                            <?php }
                        ?>
                        </table>
                    </form>
                    <br>
                </div>
            </div>
        </div>
    </div>
</div>

<hr>
<br />

<?php
    include_once  'template/m_footer.php';
?>